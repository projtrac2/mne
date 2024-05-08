<?php
include '../controller.php';

function get_page_sectors($page_id)
{
    global $db;
    $sql = $db->prepare("SELECT * FROM tbl_page_sectors WHERE page_id=:page_id");
    $result = $sql->execute(array(":page_id" => $page_id));
    $row = $sql->fetch();
    $total_rows = $sql->rowCount();

    $checked = $total_rows > 0 ? true : false;

    $department_id = $row ? $row['department_id'] : "";
    $directorate_id = $row ? $row['directorate_id'] : "";
    $sector_id = $row ? $row['sector_id'] : "";

    $departments = '<option value="">Select Department</option>';
    $sql2 = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent=0");
    $result1 = $sql2->execute();
    while ($row = $sql2->fetch()) {
        $selected = $row['stid'] == $department_id ? "selected" : "";
        $departments .= '<option value="' . $row['stid'] . '" ' . $selected . '> ' . $row['sector'] . '</option>';
    }

    $sections = '<option value="">Select Section</option>';
    $directorates = '<option value="">Select Directorate</option>';
    if ($department_id != "") {
        $dpmt = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent=:parent");
        $result1 = $dpmt->execute(array(":parent" => $department_id));
        while ($row_dpmt = $dpmt->fetch()) {
            $selected = $row_dpmt['stid'] == $sector_id ? "selected" : "";
            $sections .= '<option value="' . $row_dpmt['stid'] . '" ' . $selected . '> ' . $row_dpmt['sector'] . '</option>';
        }


        if ($sector_id != "" && $sector_id != 0) {
            $drl = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent=:parent");
            $result2 = $drl->execute(array(":parent" => $sector_id));
            while ($row = $drl->fetch()) {
                $selected = $row['stid'] == $directorate_id ? "selected" : "";
                $directorates .= '<option value="' . $row['stid'] . '" ' . $selected . '> ' . $row['sector'] . '</option>';
            }
        }
    }
    return array("departments" => $departments, "sections" => $sections, "directorates" => $directorates, "checked" => $checked);
}

function get_children($id)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM tbl_pages WHERE id=:id");
    $stmt->execute(array(":id" => $id));
    $row_stmt = $stmt->fetch();
    $parent = $row_stmt['parent'];
    $parent_id = $parent == 0 ? $id : $row_stmt['parent'];


    $drl = $db->prepare("SELECT * FROM tbl_pages WHERE parent=:parent");
    $result2 = $drl->execute(array(":parent" => $parent_id));
    $children = '<option value="">Select Child</option>';
    while ($row = $drl->fetch()) {
        $selected = $row['id'] == $id ? ' selected="selected"' : '';
        $children .= '<option value="' . $row['id'] . '" ' . $selected . '> ' . $row['name'] . '</option>';
    }
    return $children;
}

function get_page_designations($page_id)
{
    global $db;
    $sql = $db->prepare("SELECT * FROM tbl_page_designations WHERE page_id=:page_id");
    $result = $sql->execute(array(":page_id" => $page_id));
    $row = $sql->fetchAll();
    return $row;
}

function get_page_permissions($page_id)
{
    global $db;
    $sql = $db->prepare("SELECT * FROM tbl_page_permissions WHERE page_id=:page_id");
    $result = $sql->execute(array(":page_id" => $page_id));
    $row = $sql->fetchAll();
    return $row;
}

if (isset($_GET['page'])) {
    $id = $_GET['id'];
    $sql = $db->prepare("SELECT * FROM tbl_pages WHERE id=:id");
    $result = $sql->execute(array(":id" => $id));
    $row = $sql->fetch();

    $children =  get_children($id);
    $page_sectors = get_page_sectors($id);
    $page_designations = get_page_designations($id);
    $page_permissions = get_page_permissions($id);

    echo json_encode(array("success" => $result, "page" => $row, "page_sectors" => $page_sectors, "page_designations" => $page_designations, "page_permissions" => $page_permissions, "children" => $children));
}

if (isset($_GET['get_children'])) {
    $parent_id = $_GET['parent_id'];
    $child_id = "";
    $children =  get_children($parent_id);
    echo json_encode(array("success" => true, "children" => $children));
}

// get department
if (isset($_GET['get_sections'])) {
    $department_id = $_GET['department_id'];
    $sql = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent=:parent");
    $result = $sql->execute(array(":parent" => $department_id));
    $sections = '<option value="">Select Section</option>';
    while ($row = $sql->fetch()) {
        $sections .= '<option value="' . $row['stid'] . '"> ' . $row['sector'] . '</option>';
    }
    echo json_encode(array("success" => $result, "sections" => $sections));
}

if (isset($_GET['get_directorate'])) {
    $sector_id = $_GET['sector_id'];
    $sql = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent=:parent");
    $result = $sql->execute(array(":parent" => $sector_id));
    $directorates = '<option value="">Select Directorate</option>';
    while ($row = $sql->fetch()) {
        $directorates .= '<option value="' . $row['stid'] . '"> ' . $row['sector'] . '</option>';
    }
    echo json_encode(array("success" => $result, "directorates" => $directorates));
}



if (isset($_POST['store'])) {
    $created_at = date('d-m-Y');
    $created_by = $user_name;
    $name = $_POST['name'];
    $allow_read = isset($_POST['allow_read']) ? $_POST['allow_read'] : 0;
    $icons = $_POST['icon'];
    $status = 1;
    $priority = $_POST['priority'];
    $workflow_stage = isset($_POST['workflow_stage']) && !empty($_POST['workflow_stage']) ? $_POST['workflow_stage'] : 0;
    $page_id = $_POST['id'];
    $url = $_POST['url'];
    $store = $_POST['store'];
    $parent_id = isset($_POST['parent']) && !empty($_POST['parent']) ? $_POST['parent'] : 0;
    $child = isset($_POST['child']) && !empty($_POST['child']) ? $_POST['child'] : 0;
    $parent = $child != 0 ? $child : $parent_id;
    $result = false;

    if ($store == "edit") {
        $sql = $db->prepare("UPDATE tbl_pages SET name=:name,icon=:icon,url=:url,parent=:parent,priority=:priority,workflow_stage=:workflow_stage, allow_read=:allow_read,status=:status, updated_by=:updated_by,updated_at=:updated_at  WHERE id =:id");
        $result  = $sql->execute(array(":name" => $name, ":icon" => $icons, ":url" => $url, ":parent" => $parent, ":priority" => $priority, ":workflow_stage" => $workflow_stage, ":allow_read" => $allow_read, ":status" => $status, ":updated_by" => $created_by, ":updated_at" => $created_at, ":id" => $page_id));

        $sql = $db->prepare("DELETE FROM tbl_page_permissions WHERE page_id=:page_id");
        $result1 = $sql->execute(array(':page_id' => $page_id));

        $sql = $db->prepare("DELETE FROM tbl_page_sectors WHERE page_id=:page_id");
        $result1 = $sql->execute(array(':page_id' => $page_id));

        $sql = $db->prepare("DELETE FROM tbl_page_designations WHERE page_id=:page_id");
        $result1 = $sql->execute(array(':page_id' => $page_id));
    } else {
        $sql = $db->prepare("INSERT INTO tbl_pages (name,icon,url,parent,priority,workflow_stage,allow_read,status,created_by,created_at) VALUES(:name,:icon,:url,:parent,:priority,:workflow_stage,:status,:allow_read,:created_by,:created_by)");
        $result  = $sql->execute(array(":name" => $name, ":icon" => $icons, ":url" => $url, ":parent" => $parent, ":priority" => $priority, ":workflow_stage" => $workflow_stage, ":allow_read" => $allow_read, ":status" => $status, ":created_by" => $created_by, ":created_at" => $created_at));
        $page_id = $db->lastInsertId();
    }

    if ($result) {
        if (isset($_POST['department_id'])  && !empty($_POST['department_id'])) {
            $department_id = isset($_POST['department_id']) && !empty($_POST['department_id']) ? $_POST['department_id'] : 0;;
            $sector_id = isset($_POST['sector_id']) && !empty($_POST['sector_id']) ? $_POST['sector_id'] : 0;;
            $directorate_id = isset($_POST['directorate_id']) && !empty($_POST['directorate_id']) ? $_POST['directorate_id'] : 0;;
            $sql = $db->prepare("INSERT INTO tbl_page_sectors (page_id,department_id,sector_id,directorate_id,created_by,created_at) VALUES(:page_id,:department_id,:sector_id,:directorate_id,:created_by,:created_at)");
            $result  = $sql->execute(array(":page_id" => $page_id, ":department_id" => $department_id, ":sector_id" => $sector_id, ":directorate_id" => $directorate_id, ":created_by" => $created_by, ":created_at" => $created_at));
        }

        if (isset($_POST['designation_id'])) {
            $designations = $_POST['designation_id'];
            for ($d = 0; $d < count($designations); $d++) {
                $designation_id = $designations[$d];
                $sql = $db->prepare("INSERT INTO tbl_page_designations (page_id,designation_id,created_by,created_at) VALUES(:page_id,:designation_id,:created_by,:created_at)");
                $result  = $sql->execute(array(":page_id" => $page_id, ":designation_id" => $designation_id, ":created_by" => $created_by, ":created_at" => $created_at));
            }
        }

        if (isset($_POST['permission'])) {
            $permissions = $_POST['permission'];
            for ($d = 0; $d < count($permissions); $d++) {
                $permission_id = $permissions[$d];
                $sql = $db->prepare("INSERT INTO tbl_page_permissions (page_id,permission_id,created_by,created_at) VALUES(:page_id,:permission_id,:created_by,:created_at)");
                $result  = $sql->execute(array(":page_id" => $page_id, ":permission_id" => $permission_id, ":created_by" => $created_by, ":created_at" => $created_at));
            }
        }
    }
    echo json_encode(array("page_id" => $page_id, "success" => true));
}

if (isset($_POST['destroy'])) {
    $id = $_POST['id'];
    $updateStatus = '';

    $stmt = $db->prepare("SELECT * FROM `tbl_page_permissions` where id=:id");
    $stmt->execute([':id' => $id]);
    $stmt_results = $stmt->fetch();

    $stmt_results['status'] == 1 ? $updateStatus = '0' : $updateStatus = '1';

    $sql = $db->prepare("UPDATE tbl_pages SET status=:status WHERE id=:id");
    $result = $sql->execute(array(':id' => $id, ':status' => $updateStatus));
    if ($result) {
        $valid = true;
    } else {
        $valid = false;
    }
    echo json_encode($valid);
}
