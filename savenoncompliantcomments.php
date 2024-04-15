<?php
try {
	//code...


include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

				//Save cart items
if(isset($_POST['checklistid'])){
	$ckid = $_POST['checklistid'];
	$projid = $_POST['projid'];
	$comments = $_POST['comments'];
	$user = $_POST['username'];
	$date = date("Y-m-d");
	//--------------------------------------------------------------------------
	// 1) create SQL insert statement
	//--------------------------------------------------------------------------							  
	$insert = $db->prepare("INSERT INTO tbl_project_inspection_noncompliance_comments (ckid, comments, user, commentsdate) VALUES (:ckid, :comments, :user, :date)");
	$insert->execute(array(':ckid' => $ckid, ':comments' => $comments, ':user' => $user, ':date' => $date));
	
	$last_id = $db->lastInsertId();
	$stage = 11;
	//Check that we have a file
	if(!empty($_FILES['file']['name'])) {
		//Check if the file is JPEG image and it's size is less than 350Kb
		$filecategory = 'Inspection Non-Compliance';
		$filename = basename($_FILES['file']['name']);
						  
		$ext = substr($filename, strrpos($filename, '.') + 1);
							  
		if (($ext != "exe") && ($_FILES["file"]["type"] != "application/x-msdownload"))  {
								
			$newname=$ckid."-".$filename; 
			$filepath="uploads/inspection/".$newname;        
			//Check if the file with the same name already exists in the server
			if (!file_exists($filepath)) {
				//Attempt to move the uploaded file to it's new place
				if(move_uploaded_file($_FILES['file']['tmp_name'],$filepath)) {
					//successful upload										
					$qry2 = $db->prepare("INSERT INTO tbl_files (`projid`, `projstage`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, `date_uploaded`) VALUES (:pjid, :stage, :fname, :ext, :floc, :filecategory, :reason, :user, :udate)");	
					$qry2->execute(array(':pjid' => $projid, ':stage' => $stage, ':fname' => $newname, ':ext' => $ext, ':floc' => $filepath, ':filecategory' => $filecategory, ':reason' => $comments, ':user' => $user, ':udate' => $date));								
				}	
			}		  
		}		
	}
			
	echo json_encode("success");
}

} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine()); 
}
?>
