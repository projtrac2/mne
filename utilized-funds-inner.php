<?php 
try {
	//code...

?>
		<div class="header">
			<div class="row clearfix" style="margin-top:5px">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:0px">
					<div class="card">
						<div class="header" style="padding-bottom:0px">
							<div class="button-demo" style="margin-top:-15px">
								<span class="label bg-black" style="font-size:18px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" />Financier Menu</span>
								<a href="manage-financier?fn=<?php echo $hash; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; padding-left:-5px">Financier Details</a>
								<a href="financier-funds?fn=<?php echo $hash; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-5px">Funds Contributed</a>
								<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Funds Utilized</a>
								<a href="financier-status?fn=<?php echo $hash; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px;  margin-left:-9px">Financier Status</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
        <div class="body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr id="colrow">
							<th width="3%"><strong>SN</strong></th>
							<th width="10%"><strong>Grant Code</strong></th>
							<th width="30%"><strong>Project Name</strong></th>
							<th width="32%"><strong>Milestone / Task</strong></th>
							<th width="15%"><strong>Amount Paid</strong></th>
                            <th width="10%"><strong>Date Paid</strong></th>
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
								$grantid = $row_rsDonorGrant['grantid'];
								$projcat = $row_rsDonorGrant['cat'];
								$itemid = $row_rsDonorGrant['itemid'];
								
								if($projcat =='1'){
									$query_rsTask = $db->prepare("SELECT task FROM tbl_task WHERE tkid='$itemid'");
									$query_rsTask->execute();
									$row_rsTask = $query_rsTask->fetch();
								}else{
									$query_rsMilestone = $db->prepare("SELECT milestone FROM tbl_milestone WHERE msid='$itemid'");
									$query_rsMilestone->execute();
									$row_rsMilestone = $query_rsMilestone->fetch();
								}
								
								if($row_rsDonorGrant['currcode'] =='KES'){
									$rate = 1;
								}else{
									$rate = $row_rsDonorGrant['exchangerate'];
								}
								$amnt = $row_rsDonorGrant['amntpaid'];
								$datepd = strtotime($row_rsDonorGrant['datepaid']);
								$datepaid = date("d M Y",$datepd);
								
								$query_rsGrant = $db->prepare("SELECT donationcode, type FROM tbl_donor_grants WHERE gtid='$grantid'");
								$query_rsGrant->execute();
								$row_rsGrant = $query_rsGrant->fetch();
							?>
								<tr style="border-bottom:thin solid #EEE">
									<td><?php echo $sn; ?></td>
									<td><?php echo $row_rsGrant['donationcode']; ?></td>
									<td><?php echo $row_rsDonorGrant['projname']; ?></td>
									<?php if($projcat =='1'){ ?>
									<td>TK - <?php echo $row_rsTask['task']; ?></td>
									<?php } else{ ?>
									<td>MS - <?php echo $row_rsMilestone['milestone']; ?></td>
									<?php } ?>
									<td><?php echo number_format($amnt, 2); ?></td>
									<td><?php echo $datepaid; ?></td>
								</tr>
					<?php 
							}while($row_rsDonorGrant = $query_rsDonorGrant->fetch());
						}
					?>
                    </tbody>
				</table>
			</div>
		</div>

	<?php 
} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
	?>
