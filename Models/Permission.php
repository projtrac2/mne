<?php
class Permission
{
    protected $role_group, $designation, $ministry, $sector,  $directorate, $db, $user_name;
    protected  $action = false;

    public function __construct()
    {
        $this->role_group = $_SESSION['role_group'];
        $this->designation = $_SESSION['designation'];
        $this->ministry = $_SESSION['ministry'];
        $this->sector = $_SESSION['sector'];
        $this->directorate = $_SESSION['directorate'];
        $this->user_name = $_SESSION['MM_Username'];

        // connection 
        $conn = new Connection();
        $this->db = $conn->openConnection();
        $this->today = date('d-m-Y');
    }

    public function get_parent_side_bar()
    {
        $query_Sidebar =  $this->db->prepare("SELECT * FROM tbl_sidebar_menu WHERE parent = 0 AND status =1  ORDER BY sidebar_order ASC ");
        $query_Sidebar->execute();
        $row_rsSidebar = $query_Sidebar->fetchAll();
        $count = $query_Sidebar->rowCount();
        return ($count > 0) ? $row_rsSidebar : false;
    }

    public function get_child_sidebar($parent_id)
    {
        $query_Sidebar =  $this->db->prepare("SELECT * FROM tbl_sidebar_menu WHERE parent = :parent_id AND status = 1 ORDER BY sidebar_order ASC");
        $query_Sidebar->execute(array(":parent_id" => $parent_id));
        $row_rsSidebar = $query_Sidebar->fetchAll();
        $count = $query_Sidebar->rowCount();
        return ($count > 0) ? $row_rsSidebar : false;
    }

    public function validation($row_pages)
    {
        $permission = false;
        if ($row_pages) {
            $designation_array = (!empty($row_pages->designation)) ? explode(",", $row_pages->designation) : [];
            $role_group_array = (!empty($row_pages->role_group)) ?  explode(",", $row_pages->role_group) : [];
            $ministry_array = (!empty($row_pages->ministry)) ?  explode(",", $row_pages->ministry) : [];
            $sector_array = (!empty($row_pages->sector)) ?  explode(",", $row_pages->sector) : [];
            $directorate_array = (!empty($row_pages->directorate)) ?  explode(",", $row_pages->directorate) : [];

            if (!empty($this->designation) && !empty($this->role_group)) {
                if ($this->designation ==  1 && $this->role_group == 4) {
                    $permission = true;
                } else {
                    if (in_array($this->designation, $designation_array) && in_array($this->role_group, $role_group_array)) {
                        if (!empty($this->ministry) && in_array($this->ministry, $ministry_array)) {
                            if (!empty($this->sector) && (in_array($this->sector, $sector_array))) {
                                if (!empty($this->directorate) && (in_array($this->directorate, $directorate_array))) {
                                    $permission =  true;
                                } else {
                                    $permission = ($this->designation == 6 || empty($directorate_array)) ? true : false;
                                }
                            } else {
                                $permission = ($this->designation == 5 || empty($sector_array)) ? true : false;
                            }
                        } else {
                            $permission =   (empty($ministry_array)) ? true : false;;
                        }
                    }
                }
            }
        }
        return $permission;
    }

    public function get_page_permissions($current_page)
    {
        $query_pages =  $this->db->prepare("SELECT * FROM tbl_sidebar_menu WHERE url='$current_page'  AND  status = 1");
        $query_pages->execute();
        $row_pages = $query_pages->fetch();
        $count = $query_pages->rowCount();
        return ($count > 0 && $this->validation($row_pages)) ? $row_pages :  false;
    }

    public function get_action_permissions($page_id, $action)
    {
        $query_pages =  $this->db->prepare("SELECT * FROM tbl_page_actions WHERE sidebar_id=:page_id AND action=:action  AND  status = 1");
        $query_pages->execute(array(":page_id" => $page_id, ":action" => $action));
        $row_pages = $query_pages->fetch();
        $count = $query_pages->rowCount();
        return ($count > 0) ? $this->validation($row_pages) : false;
    }

    public function verify_action($project_department, $project_section, $project_directorate, $permission_id)
    {
        if ($permission_id) {
            if ($this->designation == 1 && $this->role_group == 4) {
                return true;
            } else {
                if ($project_department == $this->ministry) {
                    if ($project_section == $this->sector) {
                        if ($project_directorate == $this->directorate) {
                            return true;
                        } else {
                            return ($this->designation == 6)  ? true : false;
                        }
                    } else {
                        return ($this->designation == 5)  ? true : false;
                    }
                }
            }
        } else {
            return false;
        }
    }

    // all 
    public  function filter_department_list($progsector, $progdept, $progdirectorate)
    {
        $perm = false;
        if ($this->designation == 1 && $this->role_group == 4) {
            $perm  = true;
        } else {
            // for department specific
            if ($progsector == $this->ministry) {
                if ($progdept == $this->sector) { // co
                    if ($progdirectorate == $this->directorate) {
                        // director public works
                        // display all public works programs
                        $perm = true;
                    } else {
                        // display for co only
                        $perm = ($this->designation == 6) ? true : false;
                    }
                } else {
                    // display only for cec
                    $perm = ($this->designation == 5) ? true : false;
                }
            }
        }
        return $perm;
    }

    public function open_departmental_filter($dept_array, $section_array, $directorate_array)
    {
        $perm = false;
        if ($this->designation == 1 && $this->role_group == 4) {
            $perm  = true;
        } else {
            if (in_array($this->ministry, $dept_array)) {
                if (in_array($this->sector, $section_array)) {
                    if (in_array($this->directorate, $directorate_array)) {
                        $perm = true;
                    } else {
                        $perm = ($this->designation == 6) ? true : false;
                    }
                } else {
                    $perm = ($this->designation == 5) ? true : false;
                }
            }
        }
        return $perm;
    }

    public function open_permission_filter($progsector, $progdept, $progdirectorate)
    {
        $perm = false;
        if ($this->role_group != 2) {
            $perm = true;
        } else {
            // for department specific 
            if ($progsector == $this->ministry) { 
                if ($progdept == $this->sector) { // co 
                    if ($progdirectorate == $this->directorate) {
                        // director public works
                        // display all public works programs 
                        $perm = true;
                    } else { 
                        // display for co only
                        $perm = ($this->designation == 6) ? true : false;
                    }
                } else {
                    // display only for cec
                    $perm = ($this->designation == 5) ? true : false;
                }
            } 
        }
        return $perm;
    }

    public function verify_created_by($created_by)
    {
        return (($created_by === $this->user_name) || ($this->role_group == 4 && $this->designation == 1)) ? true : false;
    }
}
