<?php
include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';
	
$current_date = date("Y-m-d");
$current_date_time = date("Y-m-d H:m:s");

try{
	if(isset($_POST['type']) && $_POST['type']=='cost'){
		$issueid = $_POST['issueid'];
		$projid = $_POST['projid'];
		$itype = $_POST['type'];
		$date = $current_date;
		$user = $_POST['username'];

		for($j = 0; $j < count($_POST["itemid"]); $j++)
		{  
			$addedcost = $_POST['cost'][$j];
			$itemid = $_POST['itemid'][$j];
			$cat = $_POST['category'][$j];
		
			$query_prevdetails =  $db->prepare("SELECT projcategory FROM tbl_projects WHERE projid = :projid");
			$query_prevdetails->execute(array(':projid' => $projid));		
			$row_prevdetails = $query_prevdetails->fetch();
			$projcategory = $row_prevdetails["projcategory"];
			
			if($projcategory==1){				
				$query_prevdetails =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE id = :itemid");
				$query_prevdetails->execute(array(':itemid' => $itemid));		
				$row_prevdetails = $query_prevdetails->fetch();
				$prcost = $row_prevdetails["unit_cost"];
				$prunits = $row_prevdetails["units_no"];
				$newcost = $addedcost + $prcost;
		
				$insertSQL = $db->prepare("INSERT INTO tbl_project_direct_cost_plan_onhold_originals (id, projid, issueid, unit_cost, units_no, created_by, date_created) VALUES (:id, :projid, :issueid, :cost, :units, :user, :date)");
					//add the data into the database										  
				$Result1 = $insertSQL->execute(array(':id' => $itemid, ':projid' => $projid, ':issueid' => $issueid, ':cost' => $prcost, ':units' => $prunits, ':user' => $user, ':date' => $date));

				if($Result1){	
					$updateSQL = $db->prepare("UPDATE tbl_project_direct_cost_plan SET unit_cost=:newcost, units_no=:newunits WHERE id=:itemid");
					//add the data into the database										  
					$updateSQL->execute(array(':newcost' => $newcost, ':newunits' => $prunits, ':itemid' => $itemid));
				}
			}else{	
				if($cat == 1){
					$query_prevdetails =  $db->prepare("SELECT * FROM tbl_project_tender_details WHERE id = :itemid");
					$query_prevdetails->execute(array(':itemid' => $itemid));		
					$row_prevdetails = $query_prevdetails->fetch();
					$prcost = $row_prevdetails["unit_cost"];
					$prunits = $row_prevdetails["units_no"];
					$newcost = $addedcost + $prcost;
					//$tenderdetailsid = $row_prevdetails["id"];
			
					$insertSQL = $db->prepare("INSERT INTO tbl_project_tender_details_onhold_originals (id, projid, issueid, costlineid, unit_cost, units_no, created_by, date_created) VALUES (:id, :projid, :issueid, :costlineid, :unitcost, :units, :user, :date)");
						//add the data into the database										  
					$Result2 = $insertSQL->execute(array(':id' => $itemid, ':projid' => $projid, ':issueid' => $issueid, ':costlineid' => $itemid, ':unitcost' => $prcost, ':units' => $prunits, ':user' => $user, ':date' => $date));

					if($Result2){	
						$updateSQL = $db->prepare("UPDATE tbl_project_tender_details SET unit_cost=:newcost, units_no=:newunits WHERE id=:itemid");
						//add the data into the database										  
						$updateSQL->execute(array(':newcost' => $newcost, ':newunits' => $prunits, ':itemid' => $itemid));
					}
				}else{
					$query_prevdetails =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE id = :itemid");
					$query_prevdetails->execute(array(':itemid' => $itemid));		
					$row_prevdetails = $query_prevdetails->fetch();
					$prcost = $row_prevdetails["unit_cost"];
					$prunits = $row_prevdetails["units_no"];
					$newcost = $addedcost + $prcost;
			
					$insertSQL = $db->prepare("INSERT INTO tbl_project_direct_cost_plan_onhold_originals (id, projid, issueid, unit_cost, units_no, created_by, date_created) VALUES (:id, :projid, :issueid, :cost, :units, :user, :date)");
						//add the data into the database										  
					$Result1 = $insertSQL->execute(array(':id' => $itemid, ':projid' => $projid, ':issueid' => $issueid, ':cost' => $prcost, ':units' => $prunits, ':user' => $user, ':date' => $date));

					if($Result1){	
						$updateSQL = $db->prepare("UPDATE tbl_project_direct_cost_plan SET unit_cost=:newcost, units_no=:newunits WHERE id=:itemid");
						//add the data into the database										  
						$updateSQL->execute(array(':newcost' => $newcost, ':newunits' => $prunits, ':itemid' => $itemid));
					}
					
				}
			}
			
			/* $newbudget = $prvalue + $budget;
		
			$insertSQL = $db->prepare("INSERT INTO tbl_project_changed_parameters (projid, issueid, itype, category, parameter, parameter_value, previous_value, added_by, date_added) VALUES (:projid, :issueid, :itype, :category, :parameter, :val, :preval, :user, :date)");
				//add the data into the database										  
			$Result1 = $insertSQL->execute(array(':projid' => $projid, ':issueid' => $issueid, ':itype' => $itype, ':category' => $cat, ':parameter' => $itemid, ':val' => $budget, ':preval' => $prvalue, ':user' => $user, ':date' => $date));

			if($Result1){	
				$updateSQL = $db->prepare("UPDATE tbl_task SET taskbudget=:newbudget WHERE tkid=:itemid");
				//add the data into the database										  
				$updateSQL->execute(array(':newbudget' => $newbudget, ':itemid' => $itemid));
			} */
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
			
			$query_prevdetails =  $db->prepare("SELECT msid,sdate,edate FROM tbl_task WHERE tkid = '$itemid'");
			$query_prevdetails->execute();		
			$row_prevdetails = $query_prevdetails->fetch();
			$msid = $row_prevdetails["msid"];
			$prsvalue = $row_prevdetails["sdate"];
			$prvalue = $row_prevdetails["edate"];
			
			$query_milestone =  $db->prepare("SELECT edate FROM tbl_milestone WHERE projid = :projid AND msid = :msid");
			$query_milestone->execute(array(':projid' => $projid, ':msid' => $msid));		
			$row_milestone = $query_milestone->fetch();
			$msedate = $row_milestone["edate"];
			
			$query_onhold_date =  $db->prepare("SELECT date_on_hold FROM tbl_escalations WHERE projid = :projid AND itemid = :itemid");
			$query_onhold_date->execute(array(':projid' => $projid, ':itemid' => $issueid));		
			$row_onhold_date = $query_onhold_date->fetch();
			$date_on_hold = $row_onhold_date["date_on_hold"];
			$newsdate = $prsvalue;
			
			if($prsvalue > $date_on_hold){
				$newsdate = date('Y-m-d', strtotime($prsvalue. ' + '.$timeline.' days'));
			}
			
			$newedate = date('Y-m-d', strtotime($prvalue. ' + '.$timeline.' days'));				
			
			$insertSQL = $db->prepare("INSERT INTO tbl_project_changed_parameters (projid, issueid, itype, category, parameter, parameter_value, previous_value, added_by, date_added) VALUES (:projid, :issueid, :itype, :category, :parameter, :val, :preval, :user, :date)");
				//add the data into the database										  
			$Result1 = $insertSQL->execute(array(':projid' => $projid, ':issueid' => $issueid, ':itype' => $itype, ':category' => $cat, ':parameter' => $itemid, ':val' => $timeline, ':preval' => $prvalue, ':user' => $user, ':date' => $date));

			if($Result1){
				$updateSQL = $db->prepare("UPDATE tbl_task SET sdate=:newsdate, edate=:newedate WHERE tkid=:itemid");
				//add the data into the database										  
				$updateSQL->execute(array(':newsdate' => $newsdate, ':newedate' => $newedate, ':itemid' => $itemid));
			
				if($newedate > $msedate){
					$msedate = $newedate;
					$updatems = $db->prepare("UPDATE tbl_milestone SET edate=:msedate WHERE msid=:msid");
					//add the data into the database										  
					$updatems->execute(array(':msedate' => $msedate, ':msid' => $msid));
				}
			}
		}

		echo json_encode("success");
	}

}catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $result;
}
?>