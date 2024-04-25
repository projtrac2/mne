<?php
function get_page_full_url()
{
    $path = $_SERVER['REQUEST_URI'];
    $paths = explode("/", $path);
    $url_path = isset($paths[2]) ? explode(".", $paths[2]) : explode(".", $paths[1]);
    return $url_path[0];
}

function get_page_url()
{
    $current_page_url = 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    $url_components = parse_url($current_page_url);

    if (isset($url_components['query'])) { // getting the parameters
        parse_str($url_components['query'], $params);
    }

    $paths = explode("/", $url_components['path']); // getting the filename
    $path = isset($paths[2]) ? explode(".", $paths[2]) : explode(".", $paths[1]); // production server
    return $path[0];
}

function get_page_details()
{
    global $db, $designation_id;
    $path = get_page_url();
    $stmt = $db->prepare("SELECT p.id, p.name, p.icon, p.allow_read, p.url, p.parent, p.workflow_stage FROM tbl_pages p INNER JOIN tbl_page_designations d ON p.id = d.page_id WHERE url=:url and d.designation_id=:designation_id LIMIT 1");
    $stmt->execute(array(":designation_id" => $designation_id, ":url" => $path));
    $row_stmt = $stmt->fetch();
    $rows_stmt = $stmt->rowCount();
    return $rows_stmt > 0 ? $row_stmt : false;
}

function page_sector($page_id) // validating pages that are department specific
{
    global $db, $designation_id, $department_id, $section_id, $directorate_id;
    $sql = $db->prepare("SELECT * FROM tbl_page_sectors  WHERE page_id=:page_id ");
    $sql->execute(array(":page_id" => $page_id));
    $rows = $sql->fetch();
    $total_rows = $sql->rowCount();
    $result = true;

    if ($total_rows > 0) {
        $page_department = $rows['department_id'];
        $page_sector = $rows['sector_id'];
        $page_directorate = $rows['directorate_id'];

        $result = false;
        if ($designation_id == 1) {
            $result = true;
        } else if ($designation_id == 5) {
            if ($page_department == $department_id) {
                $result = true;
            }
        } else if ($designation_id == 6) {
            if ($page_department == $department_id) {
                if ($page_sector == $section_id) {
                    $result = true;
                }
            }
        } else if ($designation_id >= 7) {
            if ($page_department == $department_id) {
                if ($page_sector  == $section_id) {
                    if ($page_directorate == $directorate_id) {
                        $result = true;
                    }
                }
            }
        }
    }
    return $result;
}

function get_page_actions($page_id, $read_allowed) // for getting the permission to access individual page
{
    global $db, $designation_id;
    $permissions = [];
    $sector_validation = page_sector($page_id); // ensure that the user can access if it is department specific
    if ($sector_validation) {
        $sql = $db->prepare("SELECT s.phrase, s.id FROM tbl_page_permissions p INNER JOIN tbl_permissions s ON p.permission_id = s.id WHERE p.page_id=:page_id");
        $sql->execute(array(":page_id" => $page_id));
        $count = $sql->rowCount();
        if ($count > 0) {
            while ($row = $sql->fetch()) { // store the page actions for the page in an array
                $permission_id = $row['id'];
                $stmt = $db->prepare("SELECT * FROM tbl_designation_permissions  WHERE designation_id=:designation_id AND permission_id=:permission_id ");
                $stmt->execute(array(":designation_id" => $designation_id, "permission_id" => $permission_id));
                $total_rows = $stmt->rowCount();
                $permissions[] = $total_rows > 0 ? $row['phrase'] : "";
            }
        }
    } else {
        if ($read_allowed) {
            $permissions = ['read'];
        }
    }

    return $permissions;
}

function get_parent_id($page_detials)
{
    global $db;
    $parent = $page_detials['id'];
    $parent_id = $page_detials['parent'];
    if ($parent_id != 0) {
        $stmt = $db->prepare("SELECT * FROM tbl_pages  WHERE id=:id LIMIT 1");
        $stmt->execute(array(":id" => $parent_id));
        $row_stmt = $stmt->fetch();
        if ($row_stmt) {
            $parent = $row_stmt['parent'] == 0 ?  $parent_id : $row_stmt['parent'];
        }
    }
    return $parent;
}

function get_child_id($page_detials)
{
    global $db;
    $child = $page_detials['id'];
    $parent_id = $page_detials['parent'];
    if ($parent_id != 0) {
        $stmt = $db->prepare("SELECT * FROM tbl_pages  WHERE id=:id LIMIT 1");
        $stmt->execute(array(":id" => $parent_id));
        $row_stmt = $stmt->fetch();
        if ($row_stmt) {
            $child = $row_stmt['parent'] == 0 ?  $child : $row_stmt['id'];
        }
    }
    return $child;
}

$page_info = get_page_details();
$full_page_url = get_page_full_url();
$pageTitle = $icon = $allow_read_records = $workflow_stage = $Id = $subId = '';
if ($page_info) {
    $page_id = $page_info['id'];
    $_SESSION['page_id'] = $page_id;
    $pageTitle = $page_info['name'];
    $icon = $page_info['icon'];
    $allow_read_records = $page_info['allow_read'];
    $workflow_stage = $page_info['workflow_stage'];
    $page_actions =  get_page_actions($page_id, $allow_read_records);
    $permission =  (in_array("read", $page_actions)) ? true : false;

    $Id = get_parent_id($page_detials);
    $subId = get_child_id($page_detials);
} else {
    $permission = false;
}
