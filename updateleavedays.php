<?php

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

try{
	$current_date = date("Y-m-d");
	$current_mdate = date("m-d");
	$currentYear = date("Y");
				
	$query_rsLeaveEnd =  $db->prepare("SELECT * FROM tbl_projteam2 t inner join tbl_employee_leave l on t.ptid=l.employee inner join tbl_employees_leave_categories c on c.id=l.leavecategory WHERE availability=0 ORDER BY l.id");
	$query_rsLeaveEnd->execute();
	$countLeaveEnd = $query_rsLeaveEnd->rowCount();
		
	$nm = 0;
	while($row = $query_rsLeaveEnd->fetch()){
		$lvptid = $row["employee"];
		$lvenddate = $row["enddate"];
		$lvcat = $row["leavename"];
		$query_rsteammbr =  $db->prepare("SELECT title, fullname, email, phone FROM tbl_projteam2 WHERE ptid='$lvptid'");
		$query_rsteammbr->execute();	
		$rowteammbr = $query_rsteammbr->fetch();
		
		if($lvenddate <= $current_date){
			$nm = $nm + 1;
			$ptlv = 0;
			$aval = 1;
			
			$queryupdate = $db->prepare("UPDATE tbl_projmembers SET ptleave=:ptlv WHERE ptid=:staff");
			$queryupdate->execute(array(":ptlv"=>$ptlv, ":staff"=>$lvptid));
			
			$qryupdate = $db->prepare("UPDATE tbl_projteam2 SET availability=:aval WHERE ptid=:staff");
			$qryupdate->execute(array(":aval"=>$aval, ":staff"=>$lvptid));
			
			include_once "lvemail.php";
		}
	}
	echo $nm;
	
	if($current_mdate=="01-01"){
		$prevyear = $currentYear - 1;	
	
		$query_rsEmpDetails =  $db->prepare("SELECT ptid FROM tbl_projteam2");
		$query_rsEmpDetails->execute();	
		
		while($row = $query_rsEmpDetails->fetch()){
			$ptid = $row["ptid"];
			
			$query_rsPrevLvDays =  $db->prepare("SELECT leavecategory AS cat, sum(days) AS tdays FROM tbl_employee_leave WHERE employee = '$ptid' AND (startdate >= '$prevyear-01-01' AND startdate <= '$prevyear-12-31') AND (enddate >= '$prevyear-01-01' AND enddate <= '$prevyear-12-31') GROUP BY leavecategory ORDER BY id DESC");
			$query_rsPrevLvDays->execute();	
			//$countLvDays = $query_rsPrevLvDays->rowCount();
			
			while($rowPrevLvDays = $query_rsPrevLvDays->fetch()){
				$catid = $rowPrevLvDays["cat"];
				$Prevleavedays = $rowPrevLvDays["tdays"];
				
				$query_rsCatLeaveDays =  $db->prepare("SELECT id, days FROM tbl_employees_leave_categories where id='$catid'");
				$query_rsCatLeaveDays->execute();
				$rowCatLeaveDays = $query_rsCatLeaveDays->fetch();
				$catdays = $rowCatLeaveDays["days"];
				$bleavedays = $catdays - $Prevleavedays;
			/* }
		
			$query_rsLeaveDays =  $db->prepare("SELECT id, days FROM tbl_employees_leave_categories ORDER BY id ASC");
			$query_rsLeaveDays->execute();		
			
			while($row_rsLeaveDays = $query_rsLeaveDays->fetch()){
				$catid = $row_rsLeaveDays["id"];
				$catdays = $row_rsLeaveDays["days"]; */
				
				$query_rsLeaveDaysBal =  $db->prepare("SELECT * FROM tbl_employee_leave_bal WHERE category='$catid' AND staff = '$ptid' AND year='$currentYear' ORDER BY id ASC");
				$query_rsLeaveDaysBal->execute();	
				$rowBal = $query_rsLeaveDaysBal->fetch();			
				$countBal = $query_rsLeaveDaysBal->rowCount();
				
				//$balforward = 0;
				$totaldays = $catdays + $bleavedays;
				
				if($countBal == 0){
					$insertSQL = $db->prepare("INSERT INTO tbl_employee_leave_bal (category, staff, year, balforward, days, totaldays) VALUES( :catid, :staff, :year, :balforward, :days, :totaldays)");
					$insertSQL->execute(array(":catid"=>$catid, ":staff"=>$ptid, ":year"=>$currentYear, ":balforward"=>$bleavedays, ":days"=>$catdays, ":totaldays"=>$totaldays));
					
				}else{
					$queryupdate = $db->prepare("UPDATE tbl_employee_leave_bal SET days=:days, totaldays=:totaldays WHERE category=:catid AND staff=:staff AND year=:year");
					$queryupdate->execute(array(":days"=>$catdays, ":totaldays"=>$totaldays,":catid"=>$catid, ":staff"=>$ptid, ":year"=>$currentYear));
				}
			}	
		} 
	} 
	
	//echo "successfully executed!!!<br><br>";
	function add_work_days($stdate, $day){
		if($day == 0)
			return $stdate;

		$stdate->add(new DateInterval('P1D'));

		if(!in_array($stdate->format('N'), array('6', '7')))
			$day--;

		return add_work_days($stdate, $day);
	}

	$stdate  = add_work_days(new DateTime(), 30);
	//echo $stdate->format('Y-m-d');
			
}catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $result;
}
?>