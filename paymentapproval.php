<?php
try {
	//code...

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

				//Save cart items
if(isset($_POST['requestid'])){
	$requestid = $_POST['requestid'];
	$actionstatus = $_POST['actionstatus'];
	$user = $_POST['username'];
	$comments = $_POST['comments'];
	$recorddate = date("Y-m-d");
	//--------------------------------------------------------------------------
	// 1) create SQL insert statement
	//--------------------------------------------------------------------------							  
	$payapprovalquery = $db->prepare("INSERT INTO tbl_payment_request_comments (reqid,comments,user,date) VALUES (:requestid, :comments, :user, :recorddate)");
	$payapprovalquery->execute(array(':requestid' => $requestid, ':comments' => $comments, ':user' => $user, ':recorddate' => $recorddate));

	
	$updateQuery = $db->prepare("UPDATE tbl_payments_request SET status=:status WHERE id=:requestid");
    $update = $updateQuery->execute(array(':status' => $actionstatus, ':requestid' => $requestid));	
	
	if($actionstatus == 2){
		//--------------------------------------------------------------------------
		// Query database for data
		//--------------------------------------------------------------------------
		$query_request = $db->prepare("SELECT * FROM tbl_payments_request WHERE id='$requestid'");
		$query_request->execute();	
		$requests = $query_request->fetch();
		$total_requests = $query_request->rowCount();	
		
		$itemid = $requests['itemid'];
		$projid = $requests['projid'];
		$itemcat = $requests['itemcategory'];	
		$amount = $requests['amountrequested'];
	
		if($itemcat == 1){
			$cat = "TSK";
		}
		elseif($itemcat == 2){
			$cat = "MST";
		}
		else{
			$cat = "PRJ";
		}

		
		if($total_requests > 0){
			$query_cert = $db->prepare("SELECT * FROM tbl_certificates WHERE category='$itemcat'");
			$query_cert->execute();	
			$certs = $query_cert->fetch();
			$total_certs = $query_cert->rowCount();
			
			if($total_certs == 0 && $itemcat == 1){
				$num = 0;
			}elseif($total_certs > 0 && $itemcat == 1){
				$num = $certs["previousnumber"];
			}
			
			if($total_certs == 0 && $itemcat == 2){
				$num = 4999;
			}elseif($total_certs > 0 && $itemcat == 2){
				$num = $certs["previousnumber"];
			}
			$numb = $num + 1;
			$prefix = $cat;
			$certyear =  date('y');
			$incvalue = str_pad($numb, 7, "0", STR_PAD_LEFT);
			$certno = 'MCK/'.$prefix.'/'. $certyear.'-'.$incvalue;
			$insertcomm = $db->prepare("INSERT INTO tbl_certificates (certificateno, projid, category, itemid, prefix, year, previousnumber, certficatedate) VALUES (:certno, :projid, :category, :itemid, :prefix, :year, :previousnumber, :certficatedate)");
			$insertcomm->execute(array(':certno' => $certno, ':projid' => $projid, ':category' => $itemcat, ':itemid' => $itemid, ':prefix' =>$prefix, ':year' => $certyear, ':previousnumber' => $numb, ':certficatedate' => $recorddate));
		}
	}
		
	echo json_encode("success");
}

} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>
