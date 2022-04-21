<?php

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';
	
$current_date = date("Y-m-d");
$current_date_time = date("Y-m-d H:m:s");

try{
	if(isset($_POST['type']) && $_POST['type']=='cost'){
		$cat = $_POST['category'];
		$issueid = $_POST['issueid'];
		$projid = $_POST['projid'];
		$itype = $_POST['type'];
		$date = $current_date;
		$user = $_POST['username'];

		for($j = 0; $j < count($_POST["itemid"]); $j++)
		{  
			$budget = $_POST['budget'][$j];
			$itemid = $_POST['itemid'][$j];
		
			$query_prevdetails =  $db->prepare("SELECT taskbudget FROM tbl_task WHERE tkid = '$itemid'");
			$query_prevdetails->execute();		
			$row_prevdetails = $query_prevdetails->fetch();
			$prvalue = $row_prevdetails["taskbudget"];
			
			$newbudget = $prvalue + $budget;
		
			$insertSQL = $db->prepare("INSERT INTO tbl_project_changed_parameters (projid, issueid, itype, category, parameter, parameter_value, previous_value, added_by, date_added) VALUES (:projid, :issueid, :itype, :category, :parameter, :val, :preval, :user, :date)");
				//add the data into the database										  
			$Result1 = $insertSQL->execute(array(':projid' => $projid, ':issueid' => $issueid, ':itype' => $itype, ':category' => $cat, ':parameter' => $itemid, ':val' => $budget, ':preval' => $prvalue, ':user' => $user, ':date' => $date));

			if($Result1){	
				$updateSQL = $db->prepare("UPDATE tbl_task SET taskbudget=:newbudget WHERE tkid=:itemid");
				//add the data into the database										  
				$updateSQL->execute(array(':newbudget' => $newbudget, ':itemid' => $itemid));
			}
		}

		echo json_encode("success");
	}
	elseif(isset($_POST['type']) && $_POST['type']=='time'){
		$issueid = $_POST['issueid'];
		$projid = $_POST['projid'];
		$itype = $_POST['type'];
		$date = $current_date;
		$user = $_POST['username'];

		for($j = 0; $j < count($_POST["itemid"]); $j++)
		{  
			$timeline = $_POST['timeline'][$j];
			$itemid = $_POST['itemid'][$j];
			$cat = $_POST['category'][$j];
			
			$query_prevdetails =  $db->prepare("SELECT edate FROM tbl_task WHERE tkid = '$itemid'");
			$query_prevdetails->execute();		
			$row_prevdetails = $query_prevdetails->fetch();
			$prvalue = $row_prevdetails["edate"];
			
			$newedate = date('Y-m-d', strtotime($prvalue. ' + '.$timeline.' days'));
			
			$insertSQL = $db->prepare("INSERT INTO tbl_project_changed_parameters (projid, issueid, itype, category, parameter, parameter_value, previous_value, added_by, date_added) VALUES (:projid, :issueid, :itype, :category, :parameter, :val, :preval, :user, :date)");
				//add the data into the database										  
			$Result1 = $insertSQL->execute(array(':projid' => $projid, ':issueid' => $issueid, ':itype' => $itype, ':category' => $cat, ':parameter' => $itemid, ':val' => $timeline, ':preval' => $prvalue, ':user' => $user, ':date' => $date));

			if($Result1){
				$updateSQL = $db->prepare("UPDATE tbl_task SET edate=:newedate WHERE tkid=:itemid");
				//add the data into the database										  
				$updateSQL->execute(array(':newedate' => $newedate, ':itemid' => $itemid));
			}
		}

		echo json_encode("success");
	}

}catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $result;
}
?>