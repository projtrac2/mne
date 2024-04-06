<?php
try {
	include_once 'projtrac-dashboard/resource/Database.php';
	include_once 'projtrac-dashboard/resource/utilities.php';

	if(isset($_POST['reqid'])) 
	{
		$reqid = $_POST["reqid"];
		//$progress = $_POST["scprog"];
		$query_reqcomm = $db->prepare("SELECT * FROM tbl_payment_request_comments WHERE reqid='$reqid'");
		$query_reqcomm->execute();
		
		while($reqcommdata = $query_reqcomm->fetch()){
			$usernm = $reqcommdata["user"];
			$cmdate = $reqcommdata["date"];
			$comments =  strip_tags($reqcommdata['comments']);
			$recommdate = strtotime($cmdate);
			$commdate = date("d M Y",$recommdate);
			
			$query_commenter = $db->prepare("SELECT fullname FROM admin WHERE username='$usernm'");
			$query_commenter->execute();
			$row_commenter = $query_commenter->fetch();
			$commenter = $row_commenter['fullname'];
	
		echo '
			<div class="col-sm-12 inputGroupContainer" style="margin-top:10px">
				<table class="table table-bordered">
					<tr>
						<th width="50%"><font color="#174082">Posted By:</font></th>
						<th width="50%"><font color="#174082">Date Posted:</font></th>
					</tr>
					<tr>
						<td width="50%"><font color="#000">'.$commenter.'</font></td>
						<td width="50%"><font color="#000">'.$commdate.'</font></td>
					</tr>
					<tr>
						<th colspan="2"><font color="#174082">Comments:</font></th>
					</tr>
					<tr>
						<td colspan="2"><font color="#000">'.$comments.'</font></td>
					</tr>
				</table>
			</div>
			<div class="form-group">
				<div class="col-sm-12 inputGroupContainer">
					<div class="input-group">
						<hr>
					</div>
				</div>
			</div>
		';
	}
}
} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>