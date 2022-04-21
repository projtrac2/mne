<?php 
require 'authentication.php';

try{
	
	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
	  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}
	 

	if (!function_exists("GetSQLValueString")) {
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
	{
	  if (PHP_VERSION < 6) {
		$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
	  }

	  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

	  switch ($theType) {
		case "text":
		  $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
		  break;    
		case "long":
		case "int":
		  $theValue = ($theValue != "") ? intval($theValue) : "NULL";
		  break;
		case "double":
		  $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
		  break;
		case "date":
		  $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
		  break;
		case "defined":
		  $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
		  break;
	  }
	  return $theValue;
	}
	}

	$projid = $_GET["projid"];
	// tbl_projects
	$query_rsProject = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid='$projid'");	
	$query_rsProject->execute();
	$row_rsProject = $query_rsProject->fetch();
	$totalRows_rsProject = $query_rsProject->rowCount();
	
	
	if (isset($_POST["MM_update"]) && $_POST["MM_update"] == "editprojectfrm") {
		$projid =$_POST['projid'];
		$projexpoutcome =$_POST['Projexpoutcome'];
		$UpdateSQL = $db->prepare("UPDATE tbl_projects SET progid=:progid, projinspection=:projinsp, projcategory=:projcat, projcode=:projcode, projsector=:projsector, projdepartment=:projdept, projfscyear=:projfscyear, projname =:projname, projdesc=:projdesc,projappdate=:projappdate, projcommunity= :projsc, projlga=:projward, projstate= :projloc, projcost =:projcost, projtype =:projtype, Projexpoutcome=:projexpoutcome, datafrequency=:projfreq, projwaypoints=:projwaypnt, projteamlead =:projteamlead, projdepteamlead=:projdepteamlead, projofficer =:projofficer, projliasonofficer=:projliasonofficer, projstartdate= :projstartdate, projenddate=:projenddate, user_name=:user_name WHERE projid ='$projid'");
				//add the data into the database										  
		$Result1 = $UpdateSQL->execute(array( 
		':progid' => $_POST['prog'],
		':projinsp' => $_POST['projinspection'],
		':projcat' => $_POST['projimplmethod'],
		':projcode' => $_POST['projcode'],
		':projsector' => $_POST['projsector'], 
		':projdept' => $_POST['projdept'], 
		':projfscyear' => $_POST['projfscyear'],
		':projname' => $_POST['projname'], 
		':projdesc' => $_POST['projdesc'],
		':projappdate' => date('Y-m-d', strtotime($_POST['projappdate'])),
		':projsc' => $_POST['projcommunity'], 
		':projward' => $_POST['projlga'],
		':projloc' => $_POST['projstate'],
		':projcost' => $_POST['projcost'], 
		':projtype' => $_POST['projtype'],
		':projexpoutcome' => $projexpoutcome,
		':projfreq' => $_POST['projdatafreq'],
		':projwaypnt' => $_POST['projwaypoints'],
		':projteamlead' => $_POST['projteamlead'], 
		':projdepteamlead'=> $_POST['projdepteamlead'], 
		':projofficer' => $_POST['projofficer'], 
		':projliasonofficer' => $_POST['projliasonofficer'], 
		':projstartdate' => $_POST['projstartdate'],
		':projenddate' => $_POST['projenddate'],
		':user_name' => $_POST['username']));

		$prjteamleader = $_POST['projteamlead'];
		$prjdepteamlead = $_POST['projdepteamlead'];
		$prjofficer = $_POST['projofficer'];
		$prjliasonofficer = $_POST['projliasonofficer'];
		$username = $_POST['username'];
		$dtupdated = date("Y-m-d");
		$arr = array(
			$prjteamleader,
			$prjdepteamlead,
			$prjofficer,
			$prjliasonofficer
		);
		if(count($arr)==4){
			$statement = $db->prepare("DELETE FROM tbl_projmembers WHERE projid = :projid");
			$statement->execute(array(':projid' => $projid));
			
			$designation =0;
			foreach($arr as $ar){
				$designation =$designation+1;
				$insertSQL = $db->prepare("INSERT INTO tbl_projmembers (ptid, projid, designation, dateentered, user_name) VALUES(:ptid, :projid, :designation, :date, :username)");
				$insertSQL->execute(array(':ptid' => $ar, ':projid' => $projid, ':designation' => $designation, ':date' => $dtupdated, ':username' => $username));
			}
		}

		if(isset($_POST["expoutputname"]))
		{
			$current_date = date("Y-m-d");
			for($cnt = 0; $cnt < count($_POST["expoutputname"]); $cnt++)
			{  
				$expop = $_POST['expoutputname'][$cnt];
				$expopind = $_POST['expoutputindicator'][$cnt];
				$expopval = $_POST['expoutputvalue'][$cnt];
				$opbaseline = $_POST['outputbaseline'][$cnt];
				$updateSQL = $db->prepare("UPDATE tbl_expprojoutput SET expoutputname=:expop, expoutputindicator=:expopind, expoutputvalue=:expopval,
				 outputbaseline=:opbaseline, changed_by=:username, date_changed=:cdate WHERE projid ='$projid' "); 
				//update the data into the database										  
				$updateSQL->execute(array(':expop' => $expop, ':expopind' => $expopind, ':expopval' => $expopval, ':opbaseline' => $opbaseline, ':username' => $username, ':cdate' => $current_date));
			}
		}
		
		if(isset($_POST['projrisk']) && !empty($_POST['projrisk'])){
			$projrisk = $_POST['projrisk'];
			//add the data into the database	
			foreach ($projrisk as $prjrisk) {
				// tbl_sectors
				$query_rsNewRisk = $db->prepare("SELECT * FROM tbl_projectrisks WHERE rskid='$prjrisk' AND projid='$projid'");	
				$query_rsNewRisk->execute();
				$count_rsNewRisk = $query_rsNewRisk->rowCount();
				if($count_rsNewRisk == 0){
					$queryinsert = $db->prepare("Insert INTO tbl_projectrisks(projid,rskid) VALUES (:projid, :risk)");
					$queryinsert->execute(array(':projid' => $projid, ':risk' => $prjrisk));
				}
			}
		}
		
		$count = count($_POST["attachmentpurpose"]);		
		if($count > 0){
			for($cnt=0; $cnt<$count; $cnt++){ 	
				$purpose = $_POST["attachmentpurpose"][$cnt];
				$prjmtngdate = $_POST['projmeetingdate'][$cnt];
				
				$insertSQL = $db->prepare("INSERT INTO tbl_meetings (projid, description,date) VALUES (:last_id, :description, :date)");
				$insertSQL->execute(array(':last_id' => $projid, ':description' => $purpose, ':date' => date('Y-m-d', strtotime($prjmtngdate))));
				$catid = $db->lastInsertId();
				//$mycount = $db->rowCount()
				
				if($insertSQL->rowCount() == 1){
					
					//$catid =$last_id;
					$filecategory = "Project";
					$reason = "Meeting number: ".$cnt+1;
					
					if(!empty($_FILES['meetingfiles']['name'][$cnt])) {
						//Check if the file is JPEG image and it's size is less than 350Kb
						$filename = basename($_FILES['meetingfiles']['name'][$cnt]);
						$ext = substr($filename, strrpos($filename, '.') + 1);
						if (($ext != "exe") && ($_FILES["meetingfiles"]["type"][$cnt] != "application/x-msdownload"))  {
							$newname=$projid."-".$catid."-".$filename;
							$filepath="uploads/projmeetings/".$newname;       
							//Check if the file with the same name already exists in the server
							if (!file_exists($filepath)) {
								//Attempt to move the uploaded file to it's new place
								if(move_uploaded_file($_FILES['meetingfiles']['tmp_name'][$cnt],$filepath)) {
									//successful upload
									$fname = $newname;	
									
									$queryinsert = $db->prepare("INSERT INTO tbl_files (`filename`, `fcategory`, `catid`, `ftype`, `reason`, `description`, `projid`, `floc`, `user_name`) VALUES (:fname, :filecat, :catid, :ext, :reason, :desc, :projid, :filepath, :user)");
									$queryinsert->execute(array(':fname' => $fname, ':filecat' => $filecategory, ':catid' => $catid, ':ext' => $ext, ':reason' => $reason, ':desc' => $purpose, ':projid' => $projid, ':filepath' => $filepath, ':user' => $user_name));
								}	
							}
							else{ 
								$msg = 'File you are uploading already exists, try another file!!';
								$results = "<script type=\"text/javascript\">
									swal({
									title: \"Error!\",
									text: \" $msg \",
									icon: 'warning',
									dangerMode: true,
									timer: 5000,
									showConfirmButton: false });
								</script>";
							} 		  
						}
						else{  
							$msg = 'This file type is not allowed, try another file!!';
							$results = "<script type=\"text/javascript\">
								swal({
								title: \"Error!\",
								text: \" $msg \",
								icon: 'warning',
								dangerMode: true,
								timer: 5000,
								showConfirmButton: false });
							</script>";
						}		
					}
					else{   
						$msg = 'You have not attached any file!!';
						$results = "<script type=\"text/javascript\">
							swal({
							title: \"Error!\",
							text: \" $msg \",
							icon: 'warning',
							dangerMode: true,
							timer: 5000,
							showConfirmButton: false });
						</script>";
					}
				}
			}
		}
		
		$msg = 'The project was successfully updated.';
		$results = "<script type=\"text/javascript\">
			swal({
				title: \"Success!\",
				text: \" $msg\",
				type: 'Success',
				timer: 5000,
				showConfirmButton: false });
				setTimeout(function(){
						window.location.href = 'myprojects';
					}, 5000);
			</script>";
	}

	// tbl_sectors
	$query_rsSector = $db->prepare("SELECT stid,sector FROM tbl_sectors WHERE deleted='0' and parent='0'");	
	$query_rsSector->execute();
	$row_rsSector = $query_rsSector->fetch();
	$totalRows_rsSector = $query_rsSector->rowCount();

	$ministry = $row_rsProject['projsector'];
	$query_rsSect = $db->prepare("SELECT * FROM tbl_sectors where stid='$ministry'");
	$query_rsSect->execute();
	$row_rsSect = $query_rsSect->fetch();
	$totalRows_rsSect = $query_rsSect->rowCount();

	// tbl_sectors getting department 
	$deptid = $row_rsProject['projdepartment']; 
	$query_rsDept = $db->prepare("SELECT * FROM tbl_sectors where stid='$deptid'");
	$query_rsDept->execute();
	$row_rsDept = $query_rsDept->fetch();

	$query_rsAllDept = $db->prepare("SELECT * FROM tbl_sectors WHERE parent = '$ministry' AND deleted='0' ORDER BY stid ASC");
	$query_rsAllDept->execute();
	$row_rsAllDept = $query_rsAllDept->fetch();
	
	$query_rsIndDept = $db->prepare("SELECT * FROM tbl_sectors WHERE parent='0' AND deleted='0'");
	$query_rsIndDept->execute();
	$row_rsIndDept = $query_rsIndDept->fetch();
	$totalRows_rsIndDept = $query_rsIndDept->rowCount();

	// tbl_programs
	$progid = $row_rsProject['progid'];
	$query_prog = $db->prepare("SELECT * FROM tbl_programs WHERE progid = '$progid'");
	$query_prog->execute();
	$row_prog = $query_prog->fetch();

	$query_programs = $db->prepare("SELECT progid, progname FROM tbl_programs WHERE projdept ='$deptid'");
	$query_programs->execute();
	$row_programs = $query_programs->fetch();

	// tbl_projtypelist
	$query_rsType = $db->prepare("SELECT * FROM tbl_projtypelist");	
	$query_rsType->execute();
	$row_rsType = $query_rsType->fetch();
	$totalRows_rsType = $query_rsType->rowCount();

	$projtype = $row_rsProject['projtype'];
	$query_rsTyp = $db->prepare("SELECT * FROM tbl_projtypelist where projtypelist='$projtype'");
	$query_rsTyp->execute();
	$row_rsTyp = $query_rsTyp->fetch();
	$totalRows_rsTyp = $query_rsTyp->rowCount();
	
	//project implementation method 
	$query_rsImpMethod = $db->prepare("SELECT * FROM tbl_project_implementation_method");	
	$query_rsImpMethod->execute();
	$row_rsImpMethod = $query_rsImpMethod->fetch();

	$projcat = $row_rsProject['projcategory'];
	$query_rsImptMethod = $db->prepare("SELECT * FROM tbl_project_implementation_method where id='$projcat'");
	$query_rsImptMethod->execute();
	$row_rsImptMethod = $query_rsImptMethod->fetch();
	
	//Financial Year
	$query_rsFY = $db->prepare("SELECT * FROM tbl_fiscal_year ORDER BY id ASC");	
	$query_rsFY->execute();
	$row_rsFY = $query_rsFY->fetch();

	$projFYR = $row_rsProject['projfscyear'];
	$query_rsFYR = $db->prepare("SELECT * FROM tbl_fiscal_year where id='$projFYR'");
	$query_rsFYR->execute();
	$row_rsFYR = $query_rsFYR->fetch();
	
	// tbl_datacollectionfreq
	$query_rsDCFreq = $db->prepare("SELECT fqid,frequency FROM tbl_datacollectionfreq ORDER BY fqid ASC");	
	$query_rsDCFreq->execute();
	$row_rsDCFreq = $query_rsDCFreq->fetch();
	$totalRows_rsDCFreq = $query_rsDCFreq->rowCount();

	$datafreq = $row_rsProject['datafrequency'];
	$query_rsDCFrq = $db->prepare("SELECT * FROM tbl_datacollectionfreq where fqid='$datafreq'");
	$query_rsDCFrq->execute();
	$row_rsDCFrq = $query_rsDCFrq->fetch();
	$totalRows_rsDCFrq = $query_rsDCFrq->rowCount();	

	//tbl_expprojoutput
	$query_rsExp = $db->prepare("SELECT * FROM tbl_expprojoutput where projid ='$projid' ORDER BY opid ASC");	
	$query_rsExp->execute();
	$row_rsExp = $query_rsExp->fetch();
	$totalRows_rsExp = $query_rsExp->rowCount();

	// tbl_output
	$query_rsOut = $db->prepare("SELECT * FROM tbl_outputs where deptid ='$deptid' ORDER BY opid ASC");	
	$query_rsOut->execute();
	$row_rsOut = $query_rsOut->fetch();
	$totalRows_rsOut = $query_rsOut->rowCount();

	$query_rsOutPut = $db->prepare("SELECT * FROM tbl_outputs INNER JOIN tbl_expprojoutput ON tbl_expprojoutput.expoutputname = tbl_outputs.opid WHERE projid ='$projid' ");	
	$query_rsOutPut->execute();
	$row_rsOutPut = $query_rsOutPut->fetch();
	$totalRows_rsOutPut = $query_rsOutPut->rowCount();
	
	// tbl_indicator
	$query_rsInd = $db->prepare("SELECT * FROM tbl_indicator where inddept ='$deptid' ORDER BY indid ASC");	
	$query_rsInd->execute();
	$row_rsInd = $query_rsInd->fetch();
	$totalRows_rsInd = $query_rsInd->rowCount();

	$query_rsIndicator = $db->prepare("SELECT * FROM tbl_indicator INNER JOIN tbl_expprojoutput ON tbl_expprojoutput.expoutputindicator = tbl_indicator.indid
    WHERE projid ='$projid' ");	
	$query_rsIndicator->execute();
	$row_rsIndicator = $query_rsIndicator->fetch();
	$totalRows_rsIndicator = $query_rsIndicator->rowCount();
	
	// project map type
	$query_rsMapType = $db->prepare("SELECT * FROM tbl_map_type ORDER BY type");	
	$query_rsMapType->execute();
	$row_rsMapType = $query_rsMapType->fetch();
	
	$maptype = $row_rsProject['projwaypoints'];
	$query_rsMapTYP = $db->prepare("SELECT * FROM tbl_map_type WHERE typeid ='$maptype' ");	
	$query_rsMapTYP->execute();
	$row_rsMapTYP = $query_rsMapTYP->fetch();
	
	//Get risk data
    //$query_risk = $db->prepare("SELECT * FROM tbl_projrisk_categories WHERE rskid <> (SELECT rskid FROM `tbl_projectrisks` WHERE projid ='$projid')");
    $query_risk = $db->prepare("SELECT * FROM tbl_projrisk_categories");
	$query_risk->execute();
	$totalRows_risk = $query_risk->rowCount();

	// tbl_locations // subcounty
	$query_rsCommunity = $db->prepare("SELECT id,state FROM tbl_state WHERE parent IS NULL ORDER BY state ASC");	
	$query_rsCommunity->execute();
	$row_rsCommunity = $query_rsCommunity->fetch();
	$totalRows_rsCommunity = $query_rsCommunity->rowCount();
	
	$subcounty = $row_rsProject['projcommunity'];
	$query_rsSubcounty = $db->prepare("SELECT * FROM tbl_state where id='$subcounty'");
	$query_rsSubcounty->execute();
	$row_rsSubcounty = $query_rsSubcounty->fetch();
	$totalRows_rsSubcounty = $query_rsSubcounty->rowCount();

	// tbl_locations //ward 
	$query_rsLGA = $db->prepare("SELECT id,state FROM tbl_state WHERE location='0' and parent IS NOT NULL ORDER BY state ASC");	
	$query_rsLGA->execute();
	$row_rsLGA = $query_rsLGA->fetch();
	$totalRows_rsLGA = $query_rsLGA->rowCount();
	
	$ward = $row_rsProject['projlga'];
	$query_rsward = $db->prepare("SELECT * FROM tbl_state where id='$ward'");
	$query_rsward->execute();
	$row_rsward = $query_rsward->fetch();
	$totalRows_rsward = $query_rsward->rowCount();

	$query_rsAllWard = $db->prepare("SELECT * FROM tbl_state WHERE parent = '$subcounty'  ORDER BY id ASC");
	$query_rsAllWard->execute();
	$row_rsAllWard = $query_rsAllWard->fetch();

	// tbl_locations //location 
	$query_rsLocation = $db->prepare("SELECT id,state FROM tbl_state WHERE location='1' ORDER BY state ASC");	
	$query_rsLocation->execute();
	$row_rsLocation = $query_rsLocation->fetch();
	$totalRows_rsStatLocation = $query_rsLocation->rowCount();	

	$location = $row_rsProject['projstate'];
	$query_rslocation = $db->prepare("SELECT * FROM tbl_state where id='$location'");
	$query_rslocation->execute();
	$row_rslocation = $query_rslocation->fetch();
	$totalRows_rslocation = $query_rslocation->rowCount();	

	$query_rsAllLocation = $db->prepare("SELECT * FROM tbl_state WHERE parent = '$ward'  ORDER BY id ASC");
	$query_rsAllLocation->execute();
	$row_rsAllLocation = $query_rsAllLocation->fetch();

// tbl_projteam 
	$query_rsProjectTeam = $db->prepare("SELECT ptid, title, fullname FROM tbl_projteam2  WHERE designation=4");	
	$query_rsProjectTeam->execute();
	$row_rsProjectTeam = $query_rsProjectTeam->fetch();
	$totalRows_rsProjectTeam = $query_rsProjectTeam->rowCount();
	
	$query_rsProjectTeamLeader = $db->prepare("SELECT * FROM tbl_projteam2 WHERE designation=4");	
	$query_rsProjectTeamLeader->execute();
	$row_rsProjectTeamLeader = $query_rsProjectTeamLeader->fetch();
	$totalRows_rsProjectTeamLeader = $query_rsProjectTeamLeader->rowCount();

	$query_rsProjectTeam2 = $db->prepare("SELECT ptid, fullname FROM tbl_projteam2 WHERE designation=4 OR designation=5");	
	$query_rsProjectTeam2->execute();
	$row_rsProjectTeam2 = $query_rsProjectTeam2->fetch();
	$totalRows_rsProjectTeam2 = $query_rsProjectTeam2->rowCount();

	$query_rsProjectDeptTeamLeader = $db->prepare("SELECT tbl_projteam2.ptid, tbl_projteam2.fullname FROM `tbl_projmembers` INNER join tbl_projteam2 ON tbl_projteam2.ptid =tbl_projmembers.ptid WHERE tbl_projmembers.projid ='$projid' AND tbl_projteam2.designation=4 OR tbl_projteam2.designation=5");
	$query_rsProjectDeptTeamLeader->execute();
	$row_rsProjectDeptTeamLeader = $query_rsProjectDeptTeamLeader->fetch();
	$totalRows_rsProjectDeptTeamLeader = $query_rsProjectDeptTeamLeader->rowCount();


	$query_rsProjectTeam6 = $db->prepare("SELECT ptid, fullname FROM tbl_projteam2 WHERE designation=6");	
	$query_rsProjectTeam6->execute();
	$row_rsProjectTeam6 = $query_rsProjectTeam6->fetch();
	$totalRows_rsProjectTeam6 = $query_rsProjectTeam6->rowCount();


	$query_rsProjectOfficer = $db->prepare("SELECT tbl_projteam2.ptid, tbl_projteam2.fullname FROM `tbl_projmembers` INNER join tbl_projteam2 ON tbl_projteam2.ptid =tbl_projmembers.ptid WHERE tbl_projmembers.projid ='$projid' AND tbl_projteam2.designation =6 ");	
	$query_rsProjectOfficer->execute();
	$row_rsProjectOfficer = $query_rsProjectOfficer->fetch();
	$totalRows_rsProjectOfficer = $query_rsProjectOfficer->rowCount();
	

	$query_rsProjectTeam7 = $db->prepare("SELECT ptid, fullname FROM tbl_projteam2 WHERE designation=6");	
	$query_rsProjectTeam7->execute();
	$row_rsProjectTeam7 = $query_rsProjectTeam7->fetch();
	$totalRows_rsProjectTeam7 = $query_rsProjectTeam7->rowCount();

	$query_rsProjectLiasonOfficer = $db->prepare("SELECT tbl_projteam2.ptid, tbl_projteam2.fullname FROM `tbl_projmembers` INNER join tbl_projteam2 ON tbl_projteam2.ptid =tbl_projmembers.ptid WHERE tbl_projmembers.projid ='$projid' AND tbl_projteam2.designation =6");	
	$query_rsProjectLiasonOfficer->execute();
	$row_rsProjectLiasonOfficer = $query_rsProjectLiasonOfficer->fetch();
	$totalRows_rsProjectLiasonOfficer = $query_rsProjectLiasonOfficer->rowCount();
	
	$query_rsMeeting = $db->prepare("SELECT tbl_meetings.id, tbl_meetings.description, tbl_meetings.date, tbl_files.filename FROM `tbl_meetings` INNER JOIN tbl_files ON tbl_files.catid =tbl_meetings.id WHERE tbl_meetings.projid ='$projid'");	
	$query_rsMeeting->execute();
	$row_rsMeeting = $query_rsMeeting->fetchAll();
	$totalRows_rsMeeting = $query_rsMeeting->rowCount();

	
	$query_rsProjRisk = $db->prepare("SELECT tbl_projectrisks.id, tbl_projectrisks.rskid, tbl_projrisk_categories.category FROM `tbl_projectrisks` INNER JOIN tbl_projrisk_categories ON tbl_projrisk_categories.rskid =tbl_projectrisks.rskid WHERE tbl_projectrisks.projid ='$projid'");	
	$query_rsProjRisk->execute();
	$row_rsProjRisk = $query_rsProjRisk->fetchAll();
	$count_rsProjRisk = $query_rsProjRisk->rowCount();	
	
	 
	$user_name =1;
	$query_rsUsers = $db->prepare("SELECT * FROM tbl_projteam WHERE user_name = '$user_name'");	
	$query_rsUsers->execute();
	$row_rsUsers = $query_rsUsers->fetch();
	$totalRows_rsUsers = $query_rsUsers->rowCount();
	
	$query_rsStatus = $db->prepare("SELECT statusname FROM tbl_status ORDER BY statusname ASC");	
	$query_rsStatus->execute();
	$row_rsStatus = $query_rsStatus->fetch();
	$totalRows_rsStatus = $query_rsStatus->rowCount();
	
	
	$query_rsAdm = $db->prepare("SELECT username FROM `users`");	
	$query_rsAdm->execute();
	$row_rsAdm = $query_rsAdm->fetch();
	$totalRows_rsAdm = $query_rsAdm->rowCount();
	
	
}
catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
	print($result);
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Result-Based Monitoring &amp; Evaluation System</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	
    <!--CUSTOM MAIN STYLES-->
    <link href="css/custom.css" rel="stylesheet" />

    <!-- Bootstrap Core Css -->
    <link href="projtrac-dashboard/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="projtrac-dashboard/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="projtrac-dashboard/plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="projtrac-dashboard/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

    <!-- Bootstrap DatePicker Css -->
    <link href="projtrac-dashboard/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />

    <!--WaitMe Css-->
    <link href="projtrac-dashboard/plugins/waitme/waitMe.css" rel="stylesheet" />

    <!-- Multi Select Css -->
    <link href="projtrac-dashboard/plugins/multi-select/css/multi-select.css" rel="stylesheet">

    <!-- Bootstrap Spinner Css -->
    <link href="projtrac-dashboard/plugins/jquery-spinner/css/bootstrap-spinner.css" rel="stylesheet">

    <!-- Bootstrap Tagsinput Css -->
    <link href="projtrac-dashboard/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">

    <!-- Bootstrap Select Css -->
    <link href="projtrac-dashboard/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <!-- JQuery DataTable Css -->
    <link href="projtrac-dashboard/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

    <!-- Custom Css -->
    <link href="projtrac-dashboard/css/style.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="projtrac-dashboard/css/themes/all-themes.css" rel="stylesheet" />

	<script type="text/javascript" >
	$(document).ready(function()
	{
	$(".account").click(function()
	{
	var X=$(this).attr('id');

	if(X==1)
	{
	$(".submenus").hide();
	$(this).attr('id', '0');	
	}
	else
	{

	$(".submenus").show();
	$(this).attr('id', '1');
	}
		
	});

	//Mouseup textarea false
	$(".submenus").mouseup(function()
	{
	return false
	});
	$(".account").mouseup(function()
	{
	return false
	});


	//Textarea without editing.
	$(document).mouseup(function()
	{
	$(".submenus").hide();
	$(".account").attr('id', '');
	});
		
	});</script>
	<link rel="stylesheet" href="projtrac-dashboard/ajxmenu.css" type="text/css" />
	<script src="projtrac-dashboard/ajxmenu.js" type="text/javascript"></script>
    <link href="css/left_menu.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css"> -->
	<link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tooltipster/3.3.0/js/jquery.tooltipster.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	    <link href="style.css" rel="stylesheet">
	<script src="ckeditor/ckeditor.js"></script>
	<script language='JavaScript' type='text/javascript' src='JScript/CalculatedField.js'></script>
<style>
.stepwizard-step p {
    margin-top: 10px;
}
.stepwizard-row {
    display: table-row;
}
.stepwizard {
    display: table;
    width: 100%;
    position: relative;
}

.stepwizard-step button[disabled] {
     opacity: 1 !important; 
     filter: alpha(opacity=100) !important; 
}

.stepwizard-row:before {
    top: 14px;
    bottom: 0;
    position: absolute;
    content: " ";
    width: 100%;
    height: 1px;
    background-color: #ccc; 
    z-order: 0;
}

.stepwizard-step {
    display: table-cell;
    text-align: center;
    position: relative;
}
.stepwizard-step a:visited,.stepwizard-step a:active {
	color:blue;
}
.btn-circle {
  width: 30px;
  height: 30px;
  text-align: center;
  padding: 6px 0;
  font-size: 12px;
  line-height: 1.428571429;
  border-radius: 15px;
	/* background-color:blue; */
}
	
/* add this to the css file to hide the "showhide" class  */
.showhide{
	display:none;
}
.red{
    width: 200px;
    background-color: #F44336;
    color: #fff;
    text-align: center;
    padding: 5px 0;
    border-radius: 6px;
    position: absolute;
    z-index: 1;
    bottom: 68%;
    left: 42%;
    margin-left: -60px;
      }
      
  .red::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #555 transparent transparent transparent;
  }
</style>

<script type="text/javascript">
$(document).ready(function(){

	$("#projteamlead2").on("change", function () {
	var ptid =$(this).val();
	if(ptid !=''){
		$.ajax({
			type: "post",
			url: "addProjectLocation.php",
			data: "projdepteamlead="+ptid,
			dataType: "html",
			success: function (response) {
				$("#projdepteamlead").html(response);
				$("#projofficer").html('<option value="">...Select Deputy Project Team lead</option>');
				$("#projliasonofficer").html('<option value="">...Select Deputy Project Team lead</option>');
			}
		});
	}else{
		$("#projdepteamlead").html('<option value="">...Select Project Team lead</option>');
		$("#projofficer").html('<option value="">...Select Project Team lead</option>');
		$("#projliasonofficer").html('<option value="">...Select Project Team lead</option>');
	}
});

$("#projdepteamlead").on("change", function () {
	var ptid =$(this).val();
	if(ptid !=''){
		$.ajax({
			type: "post",
			url: "addProjectLocation.php",
			data: "projofficer="+ptid,
			dataType: "html",
			success: function (response) {
				$("#projofficer").html(response);
				$("#projliasonofficer").html('<option value="">...Select Deputy Project Team lead</option>');
			}
		});
	}else{
		$("#projofficer").html('<option value="">...Select Deputy  Project Team lead</option>');
		$("#projliasonofficer").html('<option value="">...Select Deputy Project Team lead</option>');
	}
});
$("#projofficer").on("change", function () {
	var ptid =$(this).val();
	if(ptid !=''){
		$.ajax({
			type: "post",
			url: "addProjectLocation.php",
			data: "projliasonofficer="+ptid,
			dataType: "html",
			success: function (response) {
				console.log(response);
				$("#projliasonofficer").html(response);
			}
		});
	}else{
		$("#projliasonofficer").html('<option value="">...Select Project office </option>');
	}
});
	$('#projcommunity').on('change', function () {
		var scID = $(this).val();
		if(scID ==101){
			$('#ward').html('<option value="101">All Sub Counties</option>');
			$('#location').html('<option value="101">All Sub Counties</option>');
		}else if(scID != ''){
			$.ajax({
				type:'POST',
				url:'addProjectLocation.php',
				data:'getward='+scID,
				success:function(html){
					$('#projlga').html(html);
					$('#projstate').html('<option value="">Select Ward first</option>'); 
				}
			});
		}
		else{
			$('#projlga').html('<option value="">Select Sub- first</option>');
			$('#projstate').html('<option value="">Select Ward first</option>'); 
		}
});

$('#projlga').on('change',function(){
	var wardSelected = $(this).find("option:selected");
	var ward  = wardSelected.val();
	if(ward ==101){
		$('#projstate').html('<select name="projlga" id="ward" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="true" required><option value="101">All Sub Counties</option></select>');
	}else if(ward !=''){
		$.ajax({
			type:'POST',
			url:'addProjectLocation.php',
			data:'getlocation='+ward,
			success:function(html){
				$('#projstate').html(html);
			}
		});  
	}else{
		$('#projstate').html('<option value="">Select Ward first</option>'); 
	}
});
	



	// get department
    $('#projsector').on('change',function(){
        var projsectorid = $(this).val();
        if(projsectorid){
            $.ajax({
                type:'POST',
                url:'addProjectLocation.php',
                data:'getdept='+projsectorid,
                success:function(html){
                    $('#projdept').html(html);
                }
            }); 
        }else{
            $('#projdept').html('<option value="">Select country first</option>');
        }
    });
	

	//get output 
$('#projdept').on('change',function(){
		var projdeptid = $(this).val();
		if(projdeptid){
			$.ajax({
				type:'POST',
				url:'addProjectLocation.php',
				data:'getoutput='+projdeptid,
				success:function(html){
					$('#expoutputname').empty();
					$('#expoutputname').append(html);
				}
			}); 
		}else{
			$('#expoutputname').empty();
			$('#expoutputname').append('<option value="">Select country first</option>');
		}
});

// get indicator
$('#projdept').on('change',function(){
        var projdeptid = $(this).val();
        if(projdeptid){
            $.ajax({
                type:'POST',
                url:'addProjectLocation.php',
                data:'getindicator='+projdeptid,
                success:function(html){
					$('#expoutputindicator').empty();
                    $('#expoutputindicator').append(html);
                }
            }); 
        }else{
					$('#expoutputindicator').empty();
            $('#expoutputindicator').append('<option value="">Select country first</option>');
        }
});
	
	//get projects on certain department 
	$('#projdept').on('change',function(){
        var projdeptid = $(this).val();
        if(projdeptid){
            $.ajax({
                type:'POST',
                url:'addProjectLocation.php',
                data:'getdept='+projdeptid,
                success:function(html){
                console.log(html);
					$('#prog').empty();
                    $('#prog').append(html);
                }
            }); 
        }else{
					$('#prog').empty();
            $('#prog').append('<option value="">Select country first</option>');
        }
});

//calculate cost
$('#projcost').keyup(function(){
        //var prog = $('#prog').val();
        var  cost = $(this).val();
       console.log(cost); 
        if(cost){
            $.ajax({
                type:'POST',
                url:'addProjectLocation.php',
                data:'progid='+prog,
                success:function(html){	
					console.log(html);
					// var total = cost + html.cost;		
					// if(total >html.budget){
					// 	alert('The project cost exeeds the budget of the program ');
					// }
                }
            }); 
        }
});



	// get source of money
$('#sourcecategory').on('change',function(){
        var sourcecategory = $(this).val();
        if(sourcecategory =='donor'){
            $.ajax({
                type:'POST',
                url:'addProjectLocation.php',
                data:'getsource',
                success:function(html){
					$('#source').empty();
                    $('#source').append(html);
                }
            }); 
        }else if(sourcecategory =='others'){
           $.ajax({
                type:'POST',
                url:'addProjectLocation.php',
                data:'getsources',
                success:function(html){
					$('#source').empty();
                    $('#source').append(html);
                }
            });   
        }else{
			$('#source').empty();
            $('#source').append('<option value="">Select country first</option>');
        }
});

//get div roads 
$('#projdept').on('change',function(){
        var projdeptid = $(this).val();
        if(projdeptid =='1'){
            $.ajax({
                type:'POST',
                url:'maps.php',
                data:'getindicator',
                success:function(html){
			     $('#deptLocation').html(html);
                }
            }); 
        }
});

});
</script>
	<script src="projmeetingactions.js"></script>
</head>
<body class="theme-blue">
    <!-- Page Loader --
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Top Bar -->
    <nav class="navbar" style="height:69px; padding-top:-10px">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <img src="images/logo.png" alt="logo" width="239" height="39">
            </div>
			
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <img src="images/user.png" width="48" height="48" alt="User" />
                </div>
				<?php
					include_once("includes/user-info.php");
				?>
            </div>
            <!-- #User Info -->
            <!-- Menu -->            
			<?php
				include_once("includes/sidebar.php");
			?>
            <!-- #Menu -->
            <!-- Footer -->
			<div class="legal">
				<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 copyright">
					ProjTrac M&E - Your Best Result-Based Monitoring & Evaluation System.
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 version" align="right">
					Copyright @ 2017 - 2019. ProjTrac Systems Ltd.
				</div>
			</div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
    </section>

    <section class="content" style="margin-top:-20px; padding-bottom:0px">
        <div class="container-fluid">
            <div class="block-header">
				<h4 class="contentheader"><i class="fa fa-plus-square" aria-hidden="true"></i> Edit Project Information</h4>
					<div>
						<?php echo $results; ?>
					</div>
            </div>
            <!-- Draggable Handles -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                        <!-- <div class="body"> -->
							<?php
							include_once('updatemyproject-inner.php');
							//	include_once('Programs.php');
							?>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap Core Js -->
    <script src="projtrac-dashboard/plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Multi Select Plugin Js -->
    <script src="projtrac-dashboard/plugins/multi-select/js/jquery.multi-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="projtrac-dashboard/plugins/node-waves/waves.js"></script>

    <!-- Autosize Plugin Js -->
    <script src="projtrac-dashboard/plugins/autosize/autosize.js"></script>

    <!-- Moment Plugin Js -->
    <script src="projtrac-dashboard/plugins/momentjs/moment.js"></script>

    <!-- Sparkline Chart Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-sparkline/jquery.sparkline.js"></script>
	
    <!-- Bootstrap Colorpicker Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>

    <!-- Input Mask Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>
	
    <!-- Jquery Spinner Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-spinner/js/jquery.spinner.js"></script>
	
    <!-- Bootstrap Tags Input Plugin Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
	
    <!-- noUISlider Plugin Js -->
    <script src="projtrac-dashboard/plugins/nouislider/nouislider.js"></script>

	
    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>

    <!-- Bootstrap Datepicker Plugin Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

    <!-- Dropzone Plugin Js -->
    <script src="projtrac-dashboard/plugins/dropzone/dropzone.js"></script>

    <!-- Custom Js --
    <script src="projtrac-dashboard/js/admin.js"></script>
    <script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script> -->
    <script src="projtrac-dashboard/js/pages/forms/advanced-form-elements.js"></script>
	
    <!-- Demo Js -->
		<script src="projtrac-dashboard/js/demo.js"></script>
		
		<!-- validation cdn files  -->
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-steps/1.1.0/jquery.steps.js"></script>
</body>

</html>