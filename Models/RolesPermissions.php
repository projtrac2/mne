<?php 
class RolesPermissions
{
    protected $db;
    public function __construct()
    {
        $conn = new Connection();
        $this->db = $conn->openConnection();
    }

    public function index($user_id)
    {
        $user = $this->get_user($user_id);
        $role_group = false;
        if ($user) {
            $department = $user->ministry;
            $section = $user->department;
            $designation = $user->designation;
            if ($department) {
                $department_details = $this->get_department($department);
                if ($department_details) {
                    $role_group = $department_details->role_id;
                }
            } else {
                $role_group = 3;
            }
        }
        return $role_group;
    }

    public function get_user($user_id)
    {
        $query_user =  $this->db->prepare("SELECT p.*, u.username FROM tbl_projteam2 p INNER JOIN users u ON u.pt_id = p.ptid WHERE u.userid =:user_id");
        $query_user->execute(array(":user_id" => $user_id));
        $row_rsUser = $query_user->fetch();
        $count = $query_user->rowCount();
        if ($count > 0) {
            return $row_rsUser;
        } else {
            return false;
        }
    }

    // 
    public function get_department($department)
    {
        $query_rsDepartment =  $this->db->prepare("SELECT * FROM `tbl_sectors` WHERE stid=:department");
        $query_rsDepartment->execute(array(":department" => $department));
        $row_rsDepartment = $query_rsDepartment->fetch();
        $count = $query_rsDepartment->rowCount();
        if ($count > 0) {
            return $row_rsDepartment;
        } else {
            return false;
        }
    }

    // users
    public function get_sidebar($role_id)
    {
        // $where = $role_id == 3 ? "AND Name != 'Projects Data'" : " "; 
        $query_Sidebar =  $this->db->prepare("SELECT * FROM tbl_sidebar_menu WHERE parent = 0 AND status =1 ");
        $query_Sidebar->execute(array(":role_id" => $role_id));
        $row_rsSidebar = $query_Sidebar->fetchAll();
        $count = $query_Sidebar->rowCount();
        if ($count > 0) {
            return $row_rsSidebar;
        } else {
            return false;
        }
    }

    public function get_sidebar_children($parent_id)
    {
        $query_Sidebar =  $this->db->prepare("SELECT * FROM tbl_sidebar_menu WHERE parent = :parent_id AND status = 1");
        $query_Sidebar->execute(array(":parent_id" => $parent_id));
        $row_rsSidebar = $query_Sidebar->fetchAll();
        $count = $query_Sidebar->rowCount();
        if ($count > 0) {
            return $row_rsSidebar;
        } else {
            return false;
        }
    }

    // 
    public function get_designation_permissions($designation)
    {
        $query_DesignationPermission =  $this->db->prepare("SELECT * FROM tbl_designation_permissions WHERE designation_id =:designation");
        $query_DesignationPermission->execute(array(":designation" => $designation));
        $row_rsDesignationPermission = $query_DesignationPermission->fetchAll();
        $count = $query_DesignationPermission->rowCount();
        if ($count > 0) {
            return $row_rsDesignationPermission;
        } else {
            return false;
        }
    }
}
