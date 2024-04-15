<?php
try{//For Load All Data

//include_once 'projtrac-dashboard/resource/session.php';
include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

    if($_POST["action"] == "Risk")
    {
		$projid= $_POST["projid"];
		$query_rsProjRisk = $db->prepare("SELECT tbl_projectrisks.id, tbl_projectrisks.rskid, tbl_projrisk_categories.category FROM `tbl_projectrisks` INNER JOIN tbl_projrisk_categories ON tbl_projrisk_categories.rskid =tbl_projectrisks.rskid WHERE tbl_projectrisks.projid ='$projid'");	
		$query_rsProjRisk->execute();
		$row_rsProjRisk = $query_rsProjRisk->fetchAll();
		$count_rsProjRisk = $query_rsProjRisk->rowCount();

		//if($count_rsProjRisk > 0){
		$output = '';
		$output .= '
		<tr>
			<th style="width:5%">#</th>
			<th style="width:85%">Risk/Assumption</th>
			<th style="width:10%">Action</th>
		</tr>';
		if($count_rsProjRisk > 0){
			$nm = 0;
			foreach($row_rsProjRisk as $row){
				$nm = $nm + 1;
				
				$query_riskassump = $db->prepare("SELECT * FROM tbl_projrisk_categories");	
				$query_riskassump->execute();
				$output .= '
				<tr>
					<td>'.$nm.'</td>
					<td>'.$row['category'].'</td>
					<td>
						<button type="button" class="btn btn-warning sm" data-toggle="modal" data-target="#riskModal'.$row["id"].'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&nbsp;
						<button type="button" class="btn btn-danger btn-sm pull-right" onclick="delete_row1('.$row["id"].')"><i class="fa fa-window-close" aria-hidden="true"></i></button>
						<div class="modal fade riskModal" id="riskModal'.$row['id'].'" tabindex="-1" role="dialog" aria-labelledby="riskModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="riskModalLabel">Project Risk Assumptions</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<form action="mmeditaction.php" method="post" enctype="multipart/form-data" class="riskform">
										<div class="modal-body">
											<input type="hidden" name="projid" value="'.$projid.'">
											<input type="hidden" name="rkid" value="'.$row['id'].'">
											<input type="hidden" name="user_name" value="'.$user_name.'">
											<div class="col-md-12">
												<label for="">Risk</label>
												<select name="projriskass" class="form-control">
													<option value="">... Select Risk ...</option>';
													while($row_riskassump = $query_riskassump->fetch()){ 
														$output .= '<option value="'.$row_riskassump['rskid'].'"'; if (!(strcmp($row_riskassump['category'], $row['category']))) { $output .= 'selected="selected"'; } $output .= '>'.$row_riskassump['category'].'</option>';
													} 
												$output .= '</select>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
											<button type="submit" id="riskedit" class="btn btn-primary" name="mmupdate">Update</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</td>
				</tr>
				';
			}
		}
		else {
			$output .= '
				<tr>
					<td align="center">Data not Found</td>
				</tr>
			';
		}
		echo $output;
	}
    if($_POST["action"] == "Load")
    {
		$projid= $_POST["projid"];
		$query_rsMeeting = $db->prepare("SELECT tbl_meetings.id, tbl_meetings.description, tbl_meetings.date, tbl_files.filename FROM `tbl_meetings` INNER JOIN tbl_files ON tbl_files.catid =tbl_meetings.id WHERE tbl_meetings.projid ='$projid'");	
		$query_rsMeeting->execute();
		$row_rsMeeting = $query_rsMeeting->fetchAll();
		$totalRows_rsMeeting = $query_rsMeeting->rowCount();

		if($totalRows_rsMeeting > 0){
		  $output = '';
		  $output .= '
			<table class="table table-bordered table-striped table-hover js-basic-example table-responsive" style="width:100%">
			<tr>
				<th style="width:12%">Meeting Date</th>
				<th style="width:47%">Attachment</th>
				<th style="width:30%">Purpose</th>
				<th style="width:11%">Action</th>
			</tr>
		  ';
		  
		  if($query_rsMeeting->rowCount() > 0){
			foreach($row_rsMeeting as $row)
			{
				$output .= '
				<tr>
					<td>'.$row["date"].'</td>
					<td>'.$row["filename"].'</td>
					<td>'.$row["description"].'</td>
					<td>
					<button type="button" class="btn btn-warning sm" data-toggle="modal" data-target="#mmModal'.$row["id"].'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&nbsp;
					<button type="button" class="btn btn-danger btn-sm pull-right" onclick="delete_row1('.$row["id"].')"><i class="fa fa-window-close" aria-hidden="true"></i></button>
					<div class="modal fade mmModal" id="mmModal'.$row["id"].'" tabindex="-1" role="dialog" aria-labelledby="mmModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="mmModalLabel" align="center">MEETING DETAILS</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<form action="mmeditaction.php" method="post" enctype="multipart/form-data" class="mmform">
									<div class="modal-body">
										<input type="hidden" name="projid" id="projid" value="'.$projid.'">
										<input type="hidden" name="mid" value="'.$row["id"].'">
										<input type="hidden" name="user_name" value="<?php echo $user_name;?>">
										<div class="col-md-12">
											<label for="">Meeting Date</label>
											<input name="meetingdate" type="date" id="meetingdate" class="form-control" value="'.$row["date"].'"/>
										</div>
										<div class="col-md-12">
											<span>Existing file: '.$row["filename"].'</span><br>
											<label for="">Edit File </label>
											<input type="file" name="meetingattachment"  id="meetingattachment" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
										</div>												
										<div class="col-md-12">
											<label for="">Meeting Description/Purpose</label>
											<input type="text" name="meetingpurpose" class="form-control" id="meetingpurpose" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" value="'.$row["description"].'">
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										<button type="submit" class="btn btn-primary" name="mmupdate">Update</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</tr>
				';
			}
		  }
		  else {
			$output .= '
				<tr>
				<td align="center">Data not Found</td>
				</tr>
			';
		  }
		  $output .= '</table>';
		  echo $output;
		}
	}
	
	if(isset($_POST["projriskass"])) {
		$msg = true;
		$risk = $_POST["projriskass"];
		$id = $_POST['rkid'];
		$projid = $_POST['projid'];
		$user_name = $_POST['user_name'];
		$current_date = date('Y-m-d');
		
		var_dump($risk." ".$id." ".$projid);
		//("Unable to establish a connection to ");
	
		$insertSQL = $db->prepare("UPDATE tbl_projectrisks SET rskid =:risk WHERE id=:id AND projid=:projid");
		$mmrow=$insertSQL->execute(array( ':risk' => $risk, ':id' => $id, ':projid' => $projid));

		/* $query_rsMeeting = $db->prepare("SELECT tbl_meetings.description, tbl_meetings.date, tbl_files.filename FROM tbl_meetings INNER JOIN tbl_files ON tbl_files.catid =tbl_meetings.id WHERE tbl_meetings.id ='$mid'");	
		$query_rsMeeting->execute();
		$mmrow = $query_rsMeeting->fetch();
		echo json_encode($mmrow); */
		$msg = true;
		echo json_encode($msg);		
	}
	
	if(isset($_POST["meetingpurpose"])) {
		$meetingU = $results = $msg = true;
		$description = $_POST["meetingpurpose"];
		$mid = $_POST['mid'];
		$projid = $_POST['projid'];
		$user_name = $_POST['user_name'];
		$prjmtng = date('Y-m-d', strtotime($_POST['meetingdate']));
		$current_date = date('Y-m-d');

		$insertSQL = $db->prepare("UPDATE tbl_meetings  SET description =:description,date=:date WHERE id=:mid");
		$meetingU =$insertSQL->execute(array( ':description' => $description, ':date' => $prjmtng, ':mid' => $mid));

		if(!empty($_FILES['meetingattachment']['name'])) {
			$purpose = $description;
			//Check if the file is JPEG image and it's size is less than 350Kb
			$filename = basename($_FILES['meetingattachment']['name']);
			$ext = substr($filename, strrpos($filename, '.') + 1);
			if (($ext != "exe") && ($_FILES["meetingattachment"]["type"] != "application/x-msdownload"))  {
				$newname=$projid."-".$mid."-".$filename;
				$filepath="uploads/projmeetings/".$newname;       
				//Check if the file with the same name already exists in the server
				if (!file_exists($filepath)) {
					//Attempt to move the uploaded file to it's new place
					if(move_uploaded_file($_FILES['meetingattachment']['tmp_name'],$filepath)) {
						$fname = $newname;	   
						$fpath = $filepath;
						$qry1 = $db->prepare("UPDATE tbl_files SET filename = :fname, ftype= :ext, description=:desc, floc= :fpath, user_name =:user, udate=:date WHERE catid=:mid and projid=:projid");
						$qry1->execute(array(':fname' => $fname, ':ext' => $ext, ':desc' => $purpose, ':fpath' => $fpath, ':user' => $user_name, ':date' => $current_date, ':mid' => $mid, ':projid' => $projid));
					}	
				}
				else{ 
					$msg = "File already exist";
					echo json_encode($msg);
				} 		  
			}
			else{  
				$msg = "File extention not allowed";
				echo json_encode($msg);
			}		
		}

		/* $query_rsMeeting = $db->prepare("SELECT tbl_meetings.description, tbl_meetings.date, tbl_files.filename FROM tbl_meetings INNER JOIN tbl_files ON tbl_files.catid =tbl_meetings.id WHERE tbl_meetings.id ='$mid'");	
		$query_rsMeeting->execute();
		$mmrow = $query_rsMeeting->fetch();
		echo json_encode($mmrow); */
		$msg = true;
		echo json_encode($msg);		
	}

	if($_POST["action"] == "deleterisk"){
		$tbl1 = $db->prepare("DELETE FROM tbl_projectrisks WHERE id = :id");
		$result1 = $tbl1->execute(array(':id' => $_POST["mmid"]));

		if($result1){
			echo 'Risk Assumption Deleted Successfully!!';
		}
	}

	if($_POST["action"] == "Delete"){
		$tbl1 = $db->prepare("DELETE FROM tbl_meetings WHERE id = :id");
		$result1 = $tbl1->execute(array(':id' => $_POST["mmid"]));

		$statement = $db->prepare("DELETE FROM tbl_files WHERE catid = :id");
		$results = $statement->execute(array(':id' => $_POST["mmid"]));

		if($results && $result1){
			echo 'Meeting Deleted Successfully!!';
		}
	}
}
catch (PDOException $th){
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>