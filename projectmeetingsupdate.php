<?php
//include_once 'projtrac-dashboard/resource/session.php';
include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';	

if(isset($_POST['mmupdate'])){
	
	$mid =$_POST['mid'];
	$projid =$_POST['projid'];
	$description = $_POST["attachmentpurpose"];
	
	$prjmeetingdate = date('Y-m-d', strtotime($_POST['projmeeting1']));
	$insertSQL = $db->prepare("UPDATE tbl_meetings  SET description =:description, date=:date WHERE projid=:projid AND id=:mid");
	$meeting =$insertSQL->execute(array( ':description' => $description, ':date' => $prjmeetingdate, ':projid' => $projid, ':mid' => $mid));
	
	if($meeting){
		if(!empty($_FILES['meetingattachment']['name'])) {
			$purpose = $description;
			//Check if the file is JPEG image and it's size is less than 350Kb
			$filename = basename($_FILES['meetingattachment']['name']);
			$ext = substr($filename, strrpos($filename, '.') + 1);
			if (($ext != "exe") && ($_FILES["meetingattachment"]["type"] != "application/x-msdownload"))  {
				$newname=$projid."-".$mid."-".$filename;
				$filepath="uploads/meeting/".$newname;       
				//Check if the file with the same name already exists in the server
				if (!file_exists($filepath)) {
					//Attempt to move the uploaded file to it's new place
					if(move_uploaded_file($_FILES['meetingattachment']['tmp_name'],$filepath)) {
						$fname = $newname;	   
						$fpath = $filepath;
						$qry1 = $db->prepare("UPDATE tbl_files SET filename = :fname, ftype= :ext, description=:desc, floc= :fpath, user_name =:user
						 WHERE projid='$projid'  AND catid='$mid' ");
						$qry1->execute(array(':fname' => $fname, ':ext' => $ext, ':desc' => $purpose,':fpath' => $fpath, ':user' => $user_name));
					}	
				}
				else{ 
					$type = 'error';
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
				$type = 'error';
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
			$type = 'error';
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
					
	$msg = 'Meeting Successfully Updated.';
	$results = "<script type=\"text/javascript\">
			swal({
				title: \"Success!\",
				text: \" $msg\",
				type: 'Success',
				timer: 3000,
				showConfirmButton: false });
		</script>";
}

?>