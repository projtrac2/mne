<?php
include '../controller.php';
if (isset($_POST["deleteItem"])) {
    $itemid = $_POST['itemId'];
    $deleteQuery = $db->prepare("DELETE FROM `tbl_projects` WHERE projid=:itemid");
    $results = $deleteQuery->execute(array(':itemid' => $itemid));

    $deleteQuery = $db->prepare("DELETE FROM `tbl_files` WHERE projid=:itemid");
    $results1 = $deleteQuery->execute(array(':itemid' => $itemid));
    $valid = false;
    $messages = "Error while deletng the record!!";
    if ($results) {
        $valid = true;
        $messages = "Successfully Deleted";
    }
    echo json_encode(array("success" => $valid, "messages" => $valid));
}

if (isset($_POST["add_to_adp"])) {
    $current_date = date("Y-m-d");
    $currentfy = date("Y");
    $stplane = $_POST['plan'];
    $projid = $_POST['projid'];

    //get financial year
    $query_rsYear =  $db->prepare("SELECT id, year FROM tbl_fiscal_year WHERE yr=:yr");
    $query_rsYear->execute(array(":yr" => $currentfy));
    $row_rsYear = $query_rsYear->fetch();
    $currentfyid = $row_rsYear["id"];

    $sql = $db->prepare("INSERT INTO tbl_annual_dev_plan (projid, financial_year, created_by, date_created) VALUES (:projid, :finyear, :user, :dates)");
    $results = $sql->execute(array(":projid" => $projid, ":finyear" => $currentfyid, ":user" => $user_name, ":dates" => $current_date));
    echo json_encode(array("success" => $results));
}

if (isset($_POST["remove_from_adp"])) {
    $projid = $_POST["projid"];
    $sql = $db->prepare("DELETE FROM `tbl_annual_dev_plan` WHERE projid=:projid");
    $results = $sql->execute(array(':projid' => $projid));

    $valid = false;
    $messages = "Error while removing the project from ADP!!";
    if ($results  === TRUE) {
        $valid = true;
        $messages = "Project successfully removed from ADP";
    }
    echo json_encode(array("success" => $valid, "messages" => $valid));
}


//approve item
if (isset($_POST["financialyear"])) {
    $projid = $_POST['projid'];
    $year = $_POST['financialyear'];
    $date = date("Y-m-d");

    $query_year = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE yr=:yr");
    $query_year->execute(array(":yr" => $year));
    $rows_year = $query_year->fetch();

    $query_project = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
    $query_project->execute(array(":projid" => $projid));
    $rows_project = $query_project->fetch();
    $msg = "Financial year successfully updated";

    if ($rows_year && $rows_project) {
        $yrid = $rows_year["id"];
        $yr = $rows_year["yr"];
        $start_date = $yr . "-07-01";
        $project_duration = $rows_project['projduration'] - 1;
        $end_date =  date($start_date, strtotime('+' . $project_duration . ' days'));
        $end_date = date('Y-m-d', strtotime($start_date . ' + ' . $project_duration . ' days'));
        $query_update = $db->prepare("UPDATE tbl_projects SET projfscyear=:year, projstartdate=:projstartdate, projenddate=:projenddate WHERE projid=:projid");
        $result2  = $query_update->execute(array(":year" => $yrid, ":projstartdate" => $start_date, ":projenddate" => $end_date, ":projid" => $projid));
        if ($result2) {
            $msg = "Financial year successfully updated";
        }
    }

    echo json_encode(array("msg" => $msg, "success" => $result2));
}
