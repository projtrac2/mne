<?php

include '../controller.php';
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
try {
    if (isset($_POST['getcont'])) {
        $getcont = $_POST['getcont'];
        $query_cont = $db->prepare("SELECT pinno, busregno, type  FROM tbl_contractor left join tbl_contractorbusinesstype on tbl_contractor.businesstype=tbl_contractorbusinesstype.id WHERE contrid='$getcont'");
        $query_cont->execute();
        $contractor_info = "";
        while ($row = $query_cont->fetch()) {
            $contractor_info .=  '
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <label for="">Pin Number</label>
                <input type="text" name="pinnumber" id="pinnumber" class="form-control" style="border:#CCC thin solid; border-radius: 5px; width:98%" value="' . $row['pinno'] . '" disabled="disabled" required>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <label for="">Business Reg No.</label>
                <input type="text" name="bizregno" id="bizregno" class="form-control" style="border:#CCC thin solid; border-radius: 5px; width:98%" value="' . $row['busregno'] . '" disabled="disabled" required>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <label for="">Business Type</label>
                <input type="text" name="biztype" id="biztype" class="form-control" style="border:#CCC thin solid; border-radius: 5px; width:98%" value="' . $row['type'] . '" disabled="disabled" required>
            </div>';
        }

        echo $contractor_info;
    }


    if(isset($_GET['get_milestones'])){
        $projid = $_GET['projid'];
        $query_rsMilestone = $db->prepare("SELECT * FROM tbl_project_milestone WHERE projid=:projid");
        $query_rsMilestone->execute(array(":projid" => $projid));
        $totalRows_rsMilestone = $query_rsMilestone->rowCount();
        $row_rsMilestone = $query_rsMilestone->fetchAll();
        $success = $totalRows_rsMilestone > 0 ? true : false;
        echo json_encode(['success'=>$success, 'milestones'=>$row_rsMilestone]);
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
