<?php

try{
	include_once 'projtrac-dashboard/resource/Database.php';
	include_once 'projtrac-dashboard/resource/utilities.php';	
	$message;
		//Check that we have a file
	// !empty($_POST['username']) && 
	if(!empty($_FILES['file'])) {
		//Check if the file is JPEG image and it's size is less than 350Kb
		$filecategory = 'Payment';
		$user = $_POST['username'];
		$comments = $_POST['receivecomment'];
		$pjid =10;
		$refid =10;
		$filename = basename($_FILES['file']['name']);
		$ext = substr($filename, strrpos($filename, '.') + 1);
		$requestid =30;		  
		if (($ext != "exe") && ($_FILES["file"]["type"] != "application/x-msdownload"))  {
			$newname=$requestid."_".$filename; 
			$filepath="uploads/payments/".$newname;        
			//Check if the file with the same name already exists in the server
			if (!file_exists($filepath)) {
				//Attempt to move the uploaded file to it's new place
				if(move_uploaded_file($_FILES['file']['tmp_name'],$filepath)) {

					$qry2 = $db->prepare("INSERT INTO tbl_files (`filename`, `fcategory`, `catid`, `ftype`, `reason`, `description`, `projid`, `floc`, `user_name`) VALUES (:fname, :filecategory, :catid, :ext, :purpose, :reason, :pjid, :floc, :user)");	
					$qry2->execute(array(':fname' => $newname, ':filecategory' => $filecategory, ':catid' => $requestid, ':ext' => $ext,':purpose' => $refid,':reason' => $comments, ':pjid' => $pjid, ':floc' => $filepath, ':user' => $user));	

					if ($qry2) {
							$message = "true";
						}else {
							$message = "false";
						}							
				}	
			}		  
		}		
	}

	echo json_encode($message);
}
catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
    print($result);
}	
?>