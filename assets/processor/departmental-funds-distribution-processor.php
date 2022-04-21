<?php
include_once "controller.php";

try {
    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addfundsfrm")) {
        $fundid = $_POST['fundid'];
		$current_date = date("Y-m-d");
		
		$query_fundyear = $db->prepare("SELECT financial_year FROM tbl_funds WHERE id=:fnd");
		$query_fundyear->execute(array(":fnd" => $fundid));
		$row_fundyear = $query_fundyear->fetch();
		$fnyear = $row_fundyear['financial_year'];
							
		$count = count($_POST['sector']);
			
		for($cnt=0; $cnt<$count; $cnt++)
		{ 
			//Check that we have a file
			$sector = $_POST['sector'][$cnt];
			$allocation = $_POST['allocation'][$cnt];
		
			$insertSQL = $db->prepare("INSERT INTO tbl_departments_allocation (fundid, department, allocation, financialyear, createdby, datecreated) VALUES (:funder, :sector, :allocation, :financialyear, :createdby, :datecreated)");
			$results = $insertSQL->execute(array(':funder' => $fundid, ':sector' => $sector, ':allocation' => $allocation, ':financialyear' => $fnyear, ':createdby' => $_POST['user_name'], ':datecreated' => $current_date));
		}
        if ($results) {
            echo json_encode("Departmental allocations successfully added");
        }
    }	
} catch (PDOException $ex) {
    // $result = flashMessage("An error occurred: " .$ex->getMessage());
    print($ex->getMessage());
}