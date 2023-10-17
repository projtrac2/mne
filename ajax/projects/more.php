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
    $user_name = $_POST['user_name'];
    $date = date("Y-m-d");

    $query_year = $db->prepare("SELECT id FROM tbl_fiscal_year WHERE yr=:yr");
    $query_year->execute(array(":yr" => $year));
    $rows_year = $query_year->fetch();
    $yrid = $rows_year["id"];

    $query_project_details = $db->prepare("SELECT * FROM tbl_project_details WHERE projid=:projid");
    $query_project_details->execute(array(":projid" => $projid));
    $project_details_row_rsCount = $query_project_details->rowCount();

    $result2 = false;   
    if ($project_details_row_rsCount > 0) {
        while ($row_project_details = $query_project_details->fetch()) {
            $prjopid = $row_project_details['id'];
            $opyear = $row_project_details['output_start_year'];
            $difference = $yrid - $opyear;
            $fyid = $opyear + $difference;
            $query_update = $db->prepare("UPDATE tbl_project_details SET output_start_year=:fyid WHERE id=:opid");
            $result2  = $query_update->execute(array(":fyid" => $fyid, ":opid" => $prjopid));
        }
    }

    $query_output_details = $db->prepare("SELECT * FROM tbl_project_output_details WHERE projid=:projid");
    $query_output_details->execute(array(":projid" => $projid));
    $row_rsCount = $query_output_details->rowCount();

    $msg = "Error while updating financial year!!";
    if ($row_rsCount > 0) {
        while ($row_output_details = $query_output_details->fetch()) {
            $opid = $row_output_details['id'];
            $outputyear = $row_output_details['year'];
            $difference = $year - $outputyear;
            $fy = $outputyear + $difference;

            $query_update = $db->prepare("UPDATE tbl_project_output_details SET year=:year WHERE id=:opid");
            $result2  = $query_update->execute(array(":year" => $fy, ":opid" => $opid));
        }
    }
    $msg = "Error updating financial year!";

    $query_update = $db->prepare("UPDATE tbl_projects SET projfscyear=:year WHERE projid=:projid");
    $result2  = $query_update->execute(array(":year" => $yrid, ":projid" => $projid));
    if ($result2) {
        $msg = "Financial year successfully updated";
    }
    echo json_encode(array("msg" => $msg, "success" => $result2));
}


