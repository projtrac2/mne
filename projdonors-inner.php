<!--<div class="clearfix m-b-20">
    <div class="content" style="margin-top:-10px">-->
        <div class="body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr id="colrow">
							<th width="3%"><strong>SN</strong></th>
							<th width="28%"><strong>Donor Name</strong></th>
							<th width="20%"><strong>Contact</strong></th>
                            <th width="10%"><strong>Phone</strong></th>
							<th width="10%"><strong>Total Projects</strong></th>
							<th width="15%"><strong>Total Donation (Ksh)</strong></th>
							<th width="5%"><strong>Status</strong></th> <!--COLSPAN=4--> 
							<th colspan="3"><strong>Action</strong></th> <!--COLSPAN=4--> 
						</tr>
                    </thead>
					<tbody>
					<!-- =========================================== -->
					<?php
					try{
						if($totalRows_rsPDonor == 0){
						?>
							<tr>
								<td  colspan="9"><div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div></td>
							</tr>
						<?php 
						}
						else{
							$sn = 0;						
							$query_sourcecat = $db->prepare("SELECT * FROM tbl_funding_type WHERE category = 2");
							$query_sourcecat->execute();
							$row_sourcecat = $query_sourcecat->fetch();
							$sourcecat = $row_sourcecat["id"];
							do { 
								$sn = $sn + 1;
								$country = $row_rsPDonor['country'];
								$donorid = $row_rsPDonor['dnid'];
								
								$query_rsDonorProjs = $db->prepare("SELECT p.projid FROM tbl_projects p inner join tbl_myprojfunding m on p.projid=m.projid WHERE p.deleted='0' and m.sourcecategory='$sourcecat' and m.financier = '$donorid' GROUP BY p.projid ORDER BY m.id ASC");
								$query_rsDonorProjs->execute();	
								$row_rsDonorProjs = $query_rsDonorProjs->rowCount();
								
								$query_totaldonation = $db->prepare("SELECT * FROM tbl_donor_grants WHERE  dnid = '$donorid'");
								$query_totaldonation->execute();
								$tdn = 0;
								while($ttamt = $query_totaldonation->fetch()){
									$amnt = $ttamt["amount"] * $ttamt["exchangerate"];
									$tdn = $tdn + $amnt;
								}
								
								if($row_totaldonation['totaldonation'] == '' || empty($row_totaldonation['totaldonation'])){
									$totaldonation = 0;
								}else{
									$totaldonation = $row_totaldonation['totaldonation'];
								}
								?>
								<tr style="border-bottom:thin solid #EEE">
									<td><?php echo $sn; ?></td>
									<td><?php echo $row_rsPDonor['donorname']; ?></td>
									<td><?php echo $row_rsPDonor['contact']; ?> (<?php echo $row_rsPDonor['designation']; ?>)</td>
									<td><?php echo $row_rsPDonor['phone']; ?></td>
									<td align="center"><span class="badge bg-brown"><a href="donorprojects?dnid=<?php echo $row_rsPDonor['dnid']; ?>"  style="font-family:Verdana, Geneva, sans-serif; color:white; font-size:12px; padding-top:0px"><?php echo $row_rsDonorProjs; ?></td>
									<td><?php echo number_format($tdn, 2); ?></td>
									<td><?php if($row_rsPDonor['active']==1){?><i class="fa fa-check-square" style="font-size:18px;color:green" title="Active"></i><?php }else{?><i class="fa fa-exclamation-triangle" style="font-size:18px;color:#bc2d10" title="Disabled"></i><?php } ?></td>
									<td><a href="managedonor?donor=<?php echo $row_rsPDonor['dnid']; ?>"><img src="images/preview.png"  alt="Manage"  title="Manage Donor" /></a></td>
									<td><a href="adddonors?donor=<?php echo $row_rsPDonor['dnid']; ?>"><img src="images/edit.png" alt="Edit"  title="Edit Donor Details" /></a></td>
									<td><a href="projdonors?del=1&amp;donor=<?php echo $row_rsPDonor['dnid']; ?>"  onclick="return confirm('Are you sure you want to delete this record?')"><img src="images/delete.png" alt="Delete"  title="Delete Donor" /></td>
								</tr>
								<?php 
							} while ($row_rsPDonor = $query_rsPDonor->fetch());
						}
					}
					catch (PDOException $ex){
						customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
					}
					?>
                    </tbody>
				</table>
			</div>
		</div>
<!--	</div>
</div> -->