<?php 
try {
	//code...

?>
<!--<div class="clearfix m-b-20">
    <div class="content" style="margin-top:-10px">-->
        <div class="body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr id="colrow">
							<th width="3%"><strong>SN</strong></th>
							<th width="15%"><strong>Grant Code</strong></th>
							<th width="13%"><strong>Grant Currency</strong></th>
							<th width="20%"><strong>Grant Amount</strong></th>
							<th width="12%"><strong>Exchange Rate</strong></th>
							<th width="20%"><strong>Amount (Ksh)</strong></th>
                            <th width="10%"><strong>Date Received</strong></th>
							<th colspan="2"><strong>Action</strong></th> <!--COLSPAN=4--> 
						</tr>
                    </thead>
					<tbody>
						<!-- =========================================== -->
						<?php
						if($totalRows_rsDonorGrant == 0){
						?>
							<tr>
								<td  colspan="9"><div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div></td>
							</tr>
						<?php 
						}
						else{
							$sn = 0;
							do { 
								$sn = $sn + 1;
								$grantid = $row_rsDonorGrant['gtid'];
								if($row_rsDonorGrant['currcode'] =='KES'){
									$rate = 1;
								}else{
									$rate = $row_rsDonorGrant['exchangerate'];
								}
								$amnt = $row_rsDonorGrant['amount'] * $rate;
								$dateRecd = strtotime($row_rsDonorGrant['datereceived']);
								$datereceived = date("d M Y",$dateRecd);
							?>
								<tr style="border-bottom:thin solid #EEE">
									<td><?php echo $sn; ?></td>
									<td><a href="#" onclick="javascript:CallDnPayment(<?php echo $grantid; ?>)" data-toggle="tooltip" data-placement="bottom" title="Click here for more details" style="color:blue"><?php echo $row_rsDonorGrant['donationcode']; ?></a>
									</td>
									<td><?php echo $row_rsDonorGrant['curr']; ?></td>
									<td><?php echo number_format($row_rsDonorGrant['amount'], 2); ?></td>
									<td><?php echo $row_rsDonorGrant['exchangerate']; ?></td>
									<td><?php echo number_format($amnt, 2); ?></td>
									<td><?php echo $datereceived; ?></td>
									<td><a href="receive-donor-grants?edit=1&grant=<?php echo $grantid; ?>"><img src="images/edit.png" alt="Edit"  title="Edit Grant Details" /></a></td>
									<td><a href="delgrant?del=1&amp;grant=<?php echo $grantid; ?>"  onclick="return confirm('Are you sure you want to delete this record?')"><img src="images/delete.png" alt="Delete"  title="Delete Grant" /></td>
								</tr>
					<?php 
							}while($row_rsDonorGrant = $query_rsDonorGrant->fetch());
						}
					?>
                    </tbody>
				</table>
			</div>
		</div>
<!--	</div>
</div> -->

<?php 
} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>