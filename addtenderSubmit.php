<?php 
	try{

	include_once 'projtrac-dashboard/resource/Database.php';
	include_once 'projtrac-dashboard/resource/utilities.php';
		
	$user_name = $_POST['username'];
	$evaluation =date('Y-m-d', strtotime($_POST['tenderevaluationdate']));
	$award = date('Y-m-d', strtotime($_POST['tenderawarddate']));
	$pjid =$_POST['projid'];
	$contractrefno = $_POST['contractrefno'];
	$tenderno =$_POST['tenderno'];
	$tendertitle = $_POST['tendertitle'];
	$tendertype =$_POST['tendertype'];
	$tendercat = $_POST['tendercat'];
	$procurementmethod = $_POST['procurementmethod'];
	$financialscore = $_POST['financialscore'];
	$technicalscore = $_POST['technicalscore'];
	$comments = $_POST['comments'];
	$date_created = date("Y-m-d");
	
	$insertSQL = $db->prepare("INSERT INTO `tbl_tenderdetails` (`projid`, `contractrefno`, `tenderno`, `tendertitle`, `tendertype`, `tendercat`, `procurementmethod`, `evaluationdate`, `awarddate`, `notificationdate`, `signaturedate`, `startdate`, `enddate`, `financialscore`, `technicalscore`, `comments`, `created_by`, `date_created`)
	VALUES(
	:projid,
	:contractrefno,
	:tenderno,
	:tendertitle,
	:tendertype,
	:tendercat,
	:procurementmethod,
	:evaluationdate,
	:awarddate,
	:notificationdate,
	:signaturedate,
	:startdate, 
	:enddate,
	:financialscore,
	:technicalscore,
	:comments,
	:created_by,
	:date_created
	)");
	
	
	$insertSQL->execute(array(
	":projid"=>$pjid, 
	":contractrefno"=>$contractrefno, 
	":tenderno"=>$_POST['tenderno'], 
	":tendertitle"=>$tendertitle,
	":tendertype"=>$tendertype,
	":tendercat"=>$tendercat,
	":procurementmethod"=>$procurementmethod,
	":evaluationdate"=>$evaluation,
	":awarddate"=>$award,
	":notificationdate"=>date('Y-m-d', strtotime( $_POST['tendernotificationdate'])),
	":signaturedate"=> date('Y-m-d', strtotime( $_POST['tendersignaturedate'])),
	":startdate"=>date('Y-m-d', strtotime($_POST['tenderstartdate'])),
	":enddate"=>date('Y-m-d', strtotime($_POST['tenderenddate'])),
	":financialscore"=>$financialscore,
	":technicalscore"=>$technicalscore,
	":comments"=>$comments,
	":created_by"=>$user_name, 
	":date_created"=>$date_created
	
	));




//================================================    START OF FILES ATTACHMENTS    =========================================================

	if($insertSQL->rowCount() == 1){

		$filecategory = "Tenders";
		$last_id = $db->lastInsertId();
		$reason = "";
		$myUser = $user_name;
		$catid =5;
		$count = count($_POST["attachmentpurpose"]);
		
		for($cnt=0; $cnt<$count; $cnt++)
		{ 	
		
			if(!empty($_FILES['taskfile']['name'][$cnt])) {
				$purpose = $_POST["attachmentpurpose"][$cnt];
				//Check if the file is JPEG image and it's size is less than 350Kb
				$filename = basename($_FILES['taskfile']['name'][$cnt]);
				$catid = $catid+1;
				$ext = substr($filename, strrpos($filename, '.') + 1);
				if (($ext != "exe") && ($_FILES["taskfile"]["type"][$cnt] != "application/x-msdownload"))  {
					$newname=$catid."_".$filename; 
					$filepath="uploads/tenders/".$newname;       
					//Check if the file with the same name already exists in the server
					if (!file_exists($filepath)) {
						//Attempt to move the uploaded file to it's new place
						if(move_uploaded_file($_FILES['taskfile']['tmp_name'][$cnt],$filepath)) {
							$qry2 = $db->prepare("INSERT INTO tbl_files (filename, fcategory, catid, ftype, reason, description, projid, floc, user_name, udate) VALUES (:filename, :fcat, :catid, :ftype, :reason, :desc, :projid, :floc, :user, :date)");	
							$qry2->execute(array(':filename' => $newname, ':fcat' => $filecategory, ':catid' => $last_id, ':ftype'=>$ext, ':reason' => $filecategory,':desc' => $purpose, ':projid' => $pjid, ':floc' => $filepath, ':user' => $myUser, ':date'=>$date_created));	
						}	
					}
					else{ 
						$type = 'error';
						$msg = 'File you are uploading already exists, try another file!!';
						echo $msg;
						$results = "<script type=\"text/javascript\">
							swal({
							title: \"Error!\",
							text: \" $msg \",
							type: 'Danger',
							timer: 10000,
							showConfirmButton: false });
						</script>";
					} 		  
				}
				else{  
					$type = 'error';
					$msg = 'This file type is not allowed, try another file!!';
						echo $msg;
					$results = "<script type=\"text/javascript\">
						swal({
						title: \"Error!\",
						text: \" $msg \",
						type: 'Danger',
						timer: 10000,
						showConfirmButton: false });
					</script>";
				}		
			}
			else{   
				$type = 'error';
				$msg = 'You have not attached any file!!';
				echo $msg;
				$results = "<script type=\"text/javascript\">
					swal({
					title: \"Error!\",
					text: \" $msg \",
					type: 'Danger',
					timer: 10000,
					showConfirmButton: false });
				</script>";
			}
			
		}
		$type = 'success';
		$msg = 'Task added successfully.';
		echo $msg;
		$msid =1;
		$results = "<script type=\"text/javascript\">
				swal({
					title: \"Success!\",
					text: \" $msg\",
					type: 'Success',
					timer: 10000,
					showConfirmButton: false });
				setTimeout(function(){
					window.location.href = 'addtender';
				}, 10000);
			</script>";
	}

}catch (PDOException $ex){
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>
