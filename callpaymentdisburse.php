<?php
try {
	//code...

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

if(isset($_POST['rqid'])) 
{
	$id = $_POST["rqid"];
	//$progress = $_POST["scprog"];
	$query_receive = $db->prepare("SELECT projid, requestid, itemid, amountrequested, itemcategory FROM tbl_payments_request WHERE id='$id'");
	$query_receive->execute();
	$recdata = $query_receive->fetch();
	$requestid = $recdata["requestid"];
	$itemid = $recdata["itemid"];
	$projid = $recdata["projid"];
	$itemcategory = $recdata["itemcategory"];
	$amountrec = $recdata['amountrequested'];
	$recamnt =  number_format($recdata['amountrequested'], 2);
	$current_date = date("Y-m-d");
								
	$query_rsPMembers =  $db->prepare("SELECT tbl_projteam2.*, tbl_projmembers.pmid  FROM tbl_projteam2 LEFT JOIN tbl_projmembers ON tbl_projteam2.ptid=tbl_projmembers.ptid LEFT JOIN tbl_projects ON tbl_projmembers.projid = tbl_projects.projid WHERE tbl_projects.projid = '$projid' ORDER BY tbl_projmembers.pmid ASC");
	$query_rsPMembers->execute();		
	$row_rsPMembers = $query_rsPMembers->fetch();
	
	echo '
							<input type="hidden" name="amount" id="amount" value="'.$amountrec.'"/>
							<input type="hidden" name="currentdate" id="currentdate" value="'.$current_date.'"/>
							<input type="hidden" name="refno" id="refno" value="'.$requestid.'"/>
							<input type="hidden" name="reqid" id="reqid" value="'.$id.'"/>
							<input type="hidden" name="projid" id="projid" value="'.$projid.'"/>
							<input type="hidden" name="itemid" id="itemid" value="'.$itemid.'"/>
							<input type="hidden" name="itemcategory" id="itemcategory" value="'.$itemcategory.'"/>
							<div class="col-sm-12 inputGroupContainer" style="margin-top:10px">
								<table class="table table-bordered">
									<tr>
										<th width="30%"><font color="#174082">Amount Received:</font></th>
										<td width="70%"><font color="#000">Ksh.'.$recamnt.'</font></td>
									</tr>
								</table>
							</div>
						
							<div class="form-group">
								<label class="col-sm-4 control-label"><font color="#174082">Payment Reference Code</font></label>  
								<div class="col-sm-6 inputGroupContainer">
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-th-list"></i></span>
										<input type="text" name="refcode" id="refcode" class="form-control" style="border:#CCC thin solid; border-radius: 5px; padding-left:5px" required />
									</div>
								</div>
							</div>
						
							<div class="form-group">
								<label class="col-sm-4 control-label"><font color="#174082">Please Select Mode of Payment</font></label>  
								<div class="col-sm-6 inputGroupContainer">
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-th-list"></i></span>
										<select name="paymentmode" class="form-control" style="border:#CCC thin solid; border-radius: 5px; padding-left:5px" required >
											<option value="" selected >... Select ...</option>
											<option value="1">Cash</option>
											<option value="2">M-Pesa</option>
											<option value="3">Airtel Money</option>
											<option value="4">Cheque</option>
											<option value="5">Others</option>
										</select>
									</div>
								</div>
							</div>
						
							<div class="form-group">
								<label class="col-sm-4 control-label"><font color="#174082">Name of Receiving Officer</font></label>  
								<div class="col-sm-6 inputGroupContainer">
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-th-list"></i></span>
										<select name="recipient" id="recipient" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px" required>
										<option value="" selected="selected" class="selection">Select Recipient</option>';
										do {  
											echo '<option value="'.$row_rsPMembers['ptid'].'">'.$row_rsPMembers['title'].". ".$row_rsPMembers['fullname'].'</option>';
										} while ($row_rsPMembers = $query_rsPMembers->fetch());
									echo '</select>
									</div>
								</div>
							</div>
							';
}
} catch (\Throwable $th) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>