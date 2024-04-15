<?php

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';
try{
		//--------------------------------------------------------------------------
		// Query database for data
		//--------------------------------------------------------------------------
		$query_request = $db->prepare("SELECT * FROM tbl_payments_request WHERE id='1'");
		$query_request->execute();	
		$requests = $query_request->fetch();
		$total_requests = $query_request->rowCount();	
		
		$itemid = $requests['itemid'];
		$projid = $requests['projid'];
		$itemcat = $requests['itemcategory'];	
		$amount = $requests['amountrequested'];
		$recorddate = date("Y-m-d");
	
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
		

}catch (PDOException $ex){
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>