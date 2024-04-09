<?php
try {
	//code...
	include_once 'projtrac-dashboard/resource/Database.php';
	include_once 'projtrac-dashboard/resource/utilities.php';

					//Save cart items
	if(isset($_POST['refno'])){
		$requestid = $_POST['refno'];
		$itemid = $_POST['itemid'];
		$projid = $_POST['projid'];
		$itemcat = $_POST['cat'];	
		$amount = $_POST['amount'];
		$reqdate = $_POST['currentdate'];
		$user = $_POST['username'];
		$comments = $_POST['comments'];

		//--------------------------------------------------------------------------
		// 1) create SQL insert statement
		//--------------------------------------------------------------------------							  
		$statusquery = $db->prepare("INSERT INTO tbl_payments_request (requestid, projid, itemid, itemcategory, amountrequested, requestedby, daterequested) VALUES (:requestid, :projid, :itemid, :itemcat, :amount, :user, :reqdate)");
		$statusquery->execute(array(':requestid' => $requestid, ':projid' => $projid, ':itemid' => $itemid, ':itemcat' => $itemcat, ':amount' => $amount, ':user' => $user, ':reqdate' => $reqdate));
		$last_id = $db->lastInsertId();
		
		if(!empty($comments) || $comments !== ''){
			$insertcomm = $db->prepare("INSERT INTO tbl_payment_request_comments (reqid, comments, user, date) VALUES (:reqid, :comments, :user, :date)");
			$insertcomm->execute(array(':reqid' => $last_id, ':comments' => $comments, ':user' => $user, ':date' => $reqdate));
		}

		//--------------------------------------------------------------------------
		// 2) Query database for data
		//--------------------------------------------------------------------------
		$query_requestedpay = $db->prepare("SELECT requestid FROM tbl_payments_request WHERE id='$last_id'");
		$query_requestedpay->execute();	
		$array = $query_requestedpay->fetch();
		$total_rows = $query_requestedpay->rowCount();
		//--------------------------------------------------------------------------
			// 3) echo result as json 
		//--------------------------------------------------------------------------
		if($total_rows > 0):
			echo json_encode($array);
		Endif;
	}

} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine()); 
}
?>
