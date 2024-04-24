<?php
include('dbconnection.php');

if(isset($_POST['req']) && $_POST['req']=='1') 
{
	$id = $_POST["prid"];
	$sqlquery = $db->query("SELECT * FROM tbl_projects WHERE projid = ".$id);
	$rowCount = $sqlquery->num_rows;
    $res = $sqlquery->fetch_assoc();
	$status = $res['application_status'];
	$appname = $res["title"].'. '.$res["first_name"].' '.$res["middle_name"].' '.$res["surname"];
	if($status == 2){
		$appstatus = "Application Approved";
	}
    
    //Display price
    if($rowCount > 0){
		$sqlprc = $db->query("select * from afs_hmo_temp_info where reg_id=".$id);
			//$prc = $p->num_rows;
		$prc = $sqlprc->fetch_assoc();
			$virtualPaymentClientURL = $prc['virtualPaymentClientUR'];
			$vpc_AccessCode = $prc['vpc_AccessCode'];
			$vpc_Amount = $prc['vpc_Amount'];
			$vpc_MerchTxnRef = $prc['vpc_MerchTxnRef'];
			$vpc_Merchant = $prc['vpc_Merchant'];
			$vpc_OrderInfo = $prc['vpc_OrderInfo'];
			$quantity = $prc['quantity'];
			$vpc_ReturnURL = $prc['vpc_ReturnURL'];
			$vpc_TicketNo = $prc['vpc_TicketNo'];
			$currency = $prc['currency'];
			$ptype = $prc['ptype'];
			$pid = $prc['pid'];
			$afstype = $prc['afstype'];
			$vpc_ServiceName = $prc['vpc_ServiceName'];
			$applicationNo = $prc['applicationNo'];
			$prodname = $prc['prodname'];
					
function incrementalHash($len = 5){
  $charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $base = strlen($charset);
  $result = '';

  $now = explode(' ', microtime())[1];
  while ($now >= $base){
    $i = $now % $base;
    $result = $charset[$i] . $result;
    $now /= $base;
  }
  return substr($result, -5);
}

$pmtid = incrementalHash(); 
$account = $pmtid.'AV'.$id;
		
echo '<form class="well form-horizontal" action="./post_hmo_details" method="post"  id ="signupForm1" accept-charset="UTF-8" data-toggle="validator">

							<input type="hidden" name="Title" value = "PHP VPC 3 Party Transaction">			
							<input type="hidden" name="virtualPaymentClientURL" size="63" value="'.$virtualPaymentClientURL.'" maxlength="250"/>
							<input type="hidden" name="vpc_AccessCode" value="'.$vpc_AccessCode.'" size="20" maxlength="8"/>
							<input type="hidden" name="vpc_Amount" id="amount" value="'.$vpc_Amount.'" size="20" maxlength="10"/>
							<input type="hidden" name="vpc_Command" value="pay" size="20" maxlength="16"/>
							<input type="hidden" name="vpc_Locale" value="en" size="20" maxlength="5"/>
							<input type="hidden" name="vpc_MerchTxnRef" value="'.$vpc_MerchTxnRef.'" maxlength="40"/>
							<input type="hidden" name="vpc_Merchant" value="'.$vpc_Merchant .'" size="20" maxlength="16"/>
							<input type="hidden" name="vpc_OrderInfo" value="'.$vpc_OrderInfo.'"  maxlength="200"/>
							<input type="hidden" name="quantity" value="'.$quantity.'"  maxlength="200"/>
							<input type="hidden" name="vpc_ReturnURL" size="63" value="'.$vpc_ReturnURL.'" maxlength="250"/>
							<input type="hidden" name="vpc_TicketNo" value="'.$vpc_TicketNo.'" maxlength="15"/>
							<input type="hidden" name="vpc_Version" value="1" size="20" maxlength="8"/>
							<input type="hidden" name="currency" value="'.$currency.'" maxlength="15"/>
							<input type="hidden" name="vpc_ReturnAuthResponseData" value="Y" size="20" maxlength="8"/>
							<input type="hidden" name="ptype" value="'.$ptype.'" />
							<input type="hidden" name="pid" value="'.$pid.'" maxlength="15"/>
							<input type="hidden" name="afstype" value="'.$afstype.'" maxlength="15"/>
							<input type="hidden" name="vpc_ServiceName" value="'.$vpc_ServiceName.'" size="20" maxlength="16"/>
							<input type="hidden" name="applicationNo" value="'.$applicationNo.'" maxlength="15"/>
							<input type="hidden" name="prodname" value="'.$prodname.'" maxlength="15"/>
							<input type="hidden" name="appid" value="'.$res['id'].'" maxlength="15"/>
							<input type="hidden" name="acc" value="'.$account.'" maxlength="15"/>
					

<div class="table-responsive"><font color="#174082">
<table class="table table-bordered" style="width:80%"  align="center">
<h4>Application No: '.$res['application_no'].'</h4>
<tr>
<th>Principal Member</th>
<td>'.$appname.'</td>
</tr>
<tr>
<th>Principal Member ID/PASSPORT</th>
<td>'.$res['id_passport'].'</td>
</tr>
<tr>
<th>Application Status</th>
<td>'.$appstatus.'</td>
</tr>
<tr>
<th>Package Price</th>
<td>'.$res['currency'].' '.$res['package_amount'].'</td>
</tr>


</table></font>
</div>
  
<div class="form-group">
  <label class="col-sm-4 control-label">Please Select Type of Payment </label>  
  <div class="col-sm-4 inputGroupContainer">
	<div class="input-group">
		<span class="input-group-addon"><i class="glyphicon glyphicon-th-list"></i></span>
		<select name="paymenttype" id="paymenttype" class="form-control selectpicker" required >
          <option value="" selected >Select</option>
          <option value="1">One off Payment</option>
          <option value="2">Installments Payment</option>
        </select>
    </div>
  </div>
</div>

<br>
<br>
  <div class="form-group"> 
  <label class="col-sm-2 control-label"></label> 
    <div class="col-sm-offset-2 col-sm-8">
      <button type="submit" class="btn btn-primary" name="pay">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Select Payment Method &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
    </div>
  </div>
</form>
';

}
}
?>