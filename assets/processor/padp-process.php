<?php
// require_once('../../Connections/ProjMonEva.php');
include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';
include_once("../../system-labels.php");
try {
	$valid['success'] = array('success' => false, 'messages' => array());

    if (isset($_POST['approveitem'])) {
		$progid = $_POST['progid'];
		$finyear = $_POST['finyear'];
		$createdby = $_POST['user_name'];
		$datecreated = date("Y-m-d");
		$budget = $_POST['progadpbudget'];
		
		$insertSQL1 = $db->prepare("INSERT INTO `tbl_programs_based_budget`(progid, finyear, budget, created_by, date_created) VALUES(:progid, :finyear, :budget, :createdby, :datecreated)");
		$result1  = $insertSQL1->execute(array(":progid" => $progid, ":finyear" => $finyear, ":budget" => $budget, ":createdby" => $createdby, ":datecreated" => $datecreated));
 
        if($result1){
			$valid['success'] = true;
			$valid['messages'] = "Successfully Approved";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while approving!!";
		}
		echo json_encode($valid);
    }

    if (isset($_POST['editpbbitem'])) {
		$pbbid = $_POST['pbbid'];
		$progid = $_POST['progid'];
		$finyear = $_POST['finyear'];
		$createdby = $_POST['user_name'];
		$datecreated = date("Y-m-d");
		$budget = $_POST['progadpbudget'];
		
		$insertSQL1 = $db->prepare("UPDATE `tbl_programs_based_budget` SET budget=:budget, created_by=:createdby, date_created=:datecreated WHERE id=:pbbid AND progid=:progid AND finyear=:finyear");
		$result1  = $insertSQL1->execute(array(":budget" => $budget, ":createdby" => $createdby, ":datecreated" => $datecreated, ":pbbid" => $pbbid, ":progid" => $progid, ":finyear" => $finyear));
 
        if($result1){
			$valid['success'] = true;
			$valid['messages'] = "Successfully Updated";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while updating!!";
		}
		echo json_encode($valid);
    }
	

    if (isset($_POST['quarterlytargets'])) {
		$progid = $_POST['progid'];
		$finyear = $_POST['finyear'];
		$createdby = $_POST['user_name'];
		$datecreated = date("Y-m-d");
		
		// delete tbl_programs_based_budget table 
		$deleteQuery = $db->prepare("DELETE FROM `tbl_programs_quarterly_targets` WHERE progid=:progid AND year=:finyear");
		$results = $deleteQuery->execute(array(':progid' => $progid, ':finyear' => $finyear));
		
		for ($i = 0; $i < count($_POST['optargetq1']); $i++) {			
			$pbbid = $_POST['pbbid'][$i];
			if(empty($pbbid)){
				$pbbid = 0;
			}
			$opid = $_POST['opid'][$i];			
			$indid = $_POST['indid'][$i];
			$target1 = $_POST['optargetq1'][$i];
			$target2 = $_POST['optargetq2'][$i];
			$target3 = $_POST['optargetq3'][$i];
			$target4 = $_POST['optargetq4'][$i];
			
			$insertSQL1 = $db->prepare("INSERT INTO `tbl_programs_quarterly_targets`(pbbid, progid, opid, indid, year, Q1, Q2, Q3, Q4, created_by, date_created) VALUES(:pbbid, :progid, :opid, :indid, :finyear, :target1, :target2, :target3, :target4, :createdby, :datecreated)");
			$result1  = $insertSQL1->execute(array(":pbbid" => $pbbid, ":progid" => $progid, ":opid" => $opid, ":indid" => $indid, ":finyear" => $finyear, ":target1" => $target1, ":target2" => $target2, ":target3" => $target3, ":target4" => $target4, ":createdby" => $createdby, ":datecreated" => $datecreated));
			$last_id = $db->lastInsertId(); 
		}
 
        if($last_id){
			$valid['success'] = true;
			$valid['messages'] = "Quraterly targets successfully added";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while adding quraterly targets!!";
		}
		echo json_encode($valid);
    }
	

    if (isset($_POST['indepedentProgramsquarterlytargets'])) {
		$progid = $_POST['progid'];
		$finyear = $_POST['finyear'];
		$createdby = $_POST['user_name'];
		$datecreated = date("Y-m-d");
		
		for ($i = 0; $i < count($_POST['optargetq1']); $i++) {
			$opid = $_POST['opid'][$i];			
			$indid = $_POST['indid'][$i];
			$target1 = $_POST['optargetq1'][$i];
			$target2 = $_POST['optargetq2'][$i];
			$target3 = $_POST['optargetq3'][$i];
			$target4 = $_POST['optargetq4'][$i];
			
			$insertSQL1 = $db->prepare("INSERT INTO `tbl_independent_programs_quarterly_targets`(progid, opid, indid, year, Q1, Q2, Q3, Q4, created_by, date_created) VALUES(:progid, :opid, :indid, :finyear, :target1, :target2, :target3, :target4, :createdby, :datecreated)");
			$result1  = $insertSQL1->execute(array(":progid" => $progid, ":opid" => $opid, ":indid" => $indid, ":finyear" => $finyear, ":target1" => $target1, ":target2" => $target2, ":target3" => $target3, ":target4" => $target4, ":createdby" => $createdby, ":datecreated" => $datecreated));
			$last_id = $db->lastInsertId(); 
		}
 
        if($last_id){
			$valid['success'] = true;
			$valid['messages'] = "Quraterly targets successfully added";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while adding quraterly targets!!";
		}
		echo json_encode($valid);
    }
	

    if (isset($_POST['editquarterlytargets'])) {
		$progid = $_POST['progid'];
		$finyear = $_POST['finyear'];
		$createdby = $_POST['user_name'];
		$datecreated = date("Y-m-d");

		// delete tbl_programs_based_budget table 
		$deleteQuery = $db->prepare("DELETE FROM `tbl_programs_quarterly_targets` WHERE progid=:progid AND year=:finyear");
		$results = $deleteQuery->execute(array(':progid' => $progid, ':finyear' => $finyear));
		
		for ($i = 0; $i < count($_POST['optargetq1']); $i++) {			
			$pbbid = $_POST['pbbid'][$i];
			if(empty($pbbid)){
				$pbbid = 0;
			}
			$opid = $_POST['opid'][$i];			
			$indid = $_POST['indid'][$i];
			$target1 = $_POST['optargetq1'][$i];
			$target2 = $_POST['optargetq2'][$i];
			$target3 = $_POST['optargetq3'][$i];
			$target4 = $_POST['optargetq4'][$i];
			
			$insertSQL1 = $db->prepare("INSERT INTO `tbl_programs_quarterly_targets`(pbbid, progid, opid, indid, year, Q1, Q2, Q3, Q4, created_by, date_created) VALUES(:pbbid, :progid, :opid, :indid, :finyear, :target1, :target2, :target3, :target4, :createdby, :datecreated)");
			$result1  = $insertSQL1->execute(array(":pbbid" => $pbbid, ":progid" => $progid, ":opid" => $opid, ":indid" => $indid, ":finyear" => $finyear, ":target1" => $target1, ":target2" => $target2, ":target3" => $target3, ":target4" => $target4, ":createdby" => $createdby, ":datecreated" => $datecreated));
			$last_id = $db->lastInsertId(); 
		}
 
        if($last_id){
			$valid['success'] = true;
			$valid['messages'] = "Quraterly targets successfully updated";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while updating quraterly targets!!";
		}
		echo json_encode($valid);
    }
	

    if (isset($_POST['editindependentprogramquarterlytargets'])) {
		$progid = $_POST['progid'];
		$finyear = $_POST['finyear'];
		$createdby = $_POST['user_name'];
		$datecreated = date("Y-m-d");

		// delete tbl_programs_based_budget table 
		$deleteQuery = $db->prepare("DELETE FROM `tbl_independent_programs_quarterly_targets` WHERE progid=:progid AND year=:finyear");
		$results = $deleteQuery->execute(array(':progid' => $progid, ':finyear' => $finyear));
		
		for ($i = 0; $i < count($_POST['optargetq1']); $i++) {	
			$opid = $_POST['opid'][$i];			
			$indid = $_POST['indid'][$i];
			$target1 = $_POST['optargetq1'][$i];
			$target2 = $_POST['optargetq2'][$i];
			$target3 = $_POST['optargetq3'][$i];
			$target4 = $_POST['optargetq4'][$i];
			
			$insertSQL1 = $db->prepare("INSERT INTO `tbl_independent_programs_quarterly_targets`(progid, opid, indid, year, Q1, Q2, Q3, Q4, created_by, date_created) VALUES(:progid, :opid, :indid, :finyear, :target1, :target2, :target3, :target4, :createdby, :datecreated)");
			$result1  = $insertSQL1->execute(array(":progid" => $progid, ":opid" => $opid, ":indid" => $indid, ":finyear" => $finyear, ":target1" => $target1, ":target2" => $target2, ":target3" => $target3, ":target4" => $target4, ":createdby" => $createdby, ":datecreated" => $datecreated));
			$last_id = $db->lastInsertId(); 
		}
 
        if($last_id){
			$valid['success'] = true;
			$valid['messages'] = "Quraterly targets successfully updated";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while updating quraterly targets!!";
		}
		echo json_encode($valid);
    }
} catch (PDOException $ex) {
    // $result = flashMessage("An error occurred: " .$ex->getMessage());
    echo $ex->getMessage();
}
