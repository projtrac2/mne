<?php
try {
	//code...

	include_once 'projtrac-dashboard/resource/Database.php';
	include_once 'projtrac-dashboard/resource/utilities.php';

	//Save cart items
	if (isset($_POST['refno'])) {
		$projid = $_POST['projid'];
		$reqid = $_POST['reqid'];
		$requestid = $_POST['refno'];
		$refid = $_POST['refcode'];
		$amount = $_POST['amount'];
		$itemid = $_POST['itemid'];
		$itemcategory = $_POST['itemcategory'];
		$paymentmode = $_POST['paymentmode'];
		$recipient = $_POST['recipient'];
		$datepaid = $_POST['datepaid'];
		$recorddate = $_POST['currentdate'];
		$user = $_POST['username'];
		$comments = $_POST['receivecomment'];
		$projstage = 10;
		if ($itemcategory == 1) {
			$task = $itemid;
		} else {
			$milestone = $itemid;
		}

		//--------------------------------------------------------------------------
		// 1) create SQL insert statement
		//--------------------------------------------------------------------------							  
		$payreceivequery = $db->prepare("INSERT INTO tbl_payments_disbursed (reqid, requestid, refid, projid, amountpaid, paymentmode, comments, paidby, recipient, datepaid, recordedby, daterecorded) VALUES (:reqid, :requestid, :refid, :projid, :amount, :paymentmode, :comments, :paidby, :recipient, :datepaid, :user, :recorddate)");
		$payreceivequery->execute(array(':reqid' => $reqid, ':requestid' => $requestid, ':refid' => $refid, ':projid' => $projid, ':amount' => $amount, ':paymentmode' => $paymentmode, ':comments' => $comments, ':paidby' => $user, ':recipient' => $recipient, ':datepaid' => $datepaid, ':user' => $user, ':recorddate' => $recorddate));

		$last_id = $db->lastInsertId();
		//Check that we have a file
		if (!empty($_FILES['file'])) {
			//Check if the file is JPEG image and it's size is less than 350Kb
			$filecategory = 'Payment';
			$filename = basename($_FILES['file']['name']);

			$ext = substr($filename, strrpos($filename, '.') + 1);

			if (($ext != "exe") && ($_FILES["file"]["type"] != "application/x-msdownload")) {

				$newname = $last_id . "-" . $filename;
				$filepath = "uploads/payments/" . $newname;
				//Check if the file with the same name already exists in the server
				if (!file_exists($filepath)) {
					//Attempt to move the uploaded file to it's new place
					if (move_uploaded_file($_FILES['file']['tmp_name'], $filepath)) {
						//successful upload										
						$qry2 = $db->prepare("INSERT INTO tbl_files (projid, projstage, form_id, filename, ftype, floc, fcategory, reason, uploaded_by, date_uploaded) VALUES (:projid, :projstage, :formid, :fname, :ext, :floc, :filecategory, :reason, :user, :date)");
						$qry2->execute(array(':projid' => $projid, ':projstage' => $projstage, ':formid' => $itemid, ':fname' => $newname, ':ext' => $ext, ':floc' => $filepath, ':filecategory' => $filecategory, ':reason' => $comments, ':user' => $user, ':date' => $$recorddate));
					}
				}
			}
		}


		if ($last_id) {
			$status = 4;
			$updateQuery = $db->prepare("UPDATE tbl_payments_request SET status=:status WHERE requestid=:requestid");
			//add the data into the database										  
			$updateQuery->execute(array(':status' => $status, ':requestid' => $requestid));

			if ($itemcategory == 1) {
				$paymentstatus = 1;
				$updateQuery = $db->prepare("UPDATE tbl_task SET paymentstatus=:paymentstatus WHERE tkid=:itemid");
				//add the data into the database										  
				$updateQuery->execute(array(':paymentstatus' => $paymentstatus, ':itemid' => $itemid));
			} else {
				$paymentstatus = 1;
				$updateQuery = $db->prepare("UPDATE tbl_milestone SET paymentstatus=:paymentstatus WHERE msid=:itemid");
				//add the data into the database										  
				$updateQuery->execute(array(':paymentstatus' => $paymentstatus, ':itemid' => $itemid));
			}

			echo json_encode("success");
		}
	}
} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine()); 
}
