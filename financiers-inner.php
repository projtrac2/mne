		<div class="header">
			<div style="color:#333; width:100%; height:30px; padding-top:5px; padding-left:2px">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-3px">
					<tr>
						<td width="100%" style="font-size:11px">
							<div class="btn-group" style="float:right">
								<a href="add-financier" class="btn btn-success"  style="height:27px; ; margin-top:-1px; vertical-align:center">Add New Financier</a>
							</div>
						</td>
					</tr>
				</table>
			</div>
		</div>
        <div class="body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <tr id="colrow">
							<th width="3%"><strong>SN</strong></th>
							<th width="30%"><strong>Financier</strong></th>
                            <th width="8%"><strong>Type</strong></th>
							<th width="15%"><strong>Contact</strong></th>
                            <th width="9%"><strong>Phone</strong></th>
							<th width="8%"><strong>Projects</strong></th>
							<th width="15%"><strong>Total Amt (Ksh)</strong></th>
							<th width="5%"><strong>Status</strong></th>  
							<th width="7%"><strong>Action</strong></th> 
						</tr>
                    </thead>
					<tbody>
						<!-- =========================================== -->
						<?php
						if($totalRows_rsfinancier > 0){
							$sn = 0;						
							$query_sourcecat = $db->prepare("SELECT * FROM tbl_funding_type WHERE category = 4");
							$query_sourcecat->execute();
							$row_sourcecat = $query_sourcecat->fetch();
							$sourcecat = 5;
							do { 
								$sn = $sn + 1;
								$country = $row_rsfinancier['country'];
								$fnid = $row_rsfinancier['fnid'];
								$hashfnid = base64_encode("fn918273AxZID{$fnid}");
								
								$query_financierprojs = $db->prepare("SELECT p.projid FROM tbl_projects p inner join tbl_myprojfunding m on p.projid=m.projid WHERE p.deleted='0' and m.sourcecategory=:sourcecat and m.financier = :fnid GROUP BY p.projid ORDER BY m.id ASC");
								$query_financierprojs->execute(array(":sourcecat" => $sourcecat, ":fnid" => $fnid));	
								$row_financierprojs = $query_financierprojs->rowCount();
								
								$query_totalfunds = $db->prepare("SELECT * FROM tbl_funds WHERE funder = :fnid");
								$query_totalfunds->execute(array(":fnid" => $fnid));
								$tdn = 0;
								while($ttamt = $query_totalfunds->fetch()){
									$amnt = $ttamt["amount"] * $ttamt["exchange_rate"];
									$tdn = $tdn + $amnt;
								}
								
								if($row_rsfinancier['active'] == 1){
									$active = '<i class="fa fa-check-square" style="font-size:18px;color:green" title="Active"></i>';
								} else{
									$active = '<i class="fa fa-exclamation-triangle" style="font-size:18px;color:#bc2d10" title="Disabled"></i>';
								} 
								?>
								<tr style="border-bottom:thin solid #EEE">
									<td><?php echo $sn; ?></td>
									<td><?php echo $row_rsfinancier['financier']; ?></td>
									<td><?php echo $row_rsfinancier['ftype']; ?></td>
									<td><?php echo $row_rsfinancier['contact']; ?> (<?php echo $row_rsfinancier['designation']; ?>)</td>
									<td><a href="tel:<?php echo $row_rsfinancier['phone'];?>"><?php echo $row_rsfinancier['phone']; ?></a></td>
									<td align="center"><span class="badge bg-brown"><a href="donorprojects?fn=<?php echo $row_rsfinancier['id']; ?>"  style="font-family:Verdana, Geneva, sans-serif; color:white; font-size:12px; padding-top:0px"><?php echo $row_financierprojs; ?></td>
									<td><?php echo number_format($tdn, 2); ?></td>
									<td align="center"><?=$active?></td>
									
									<td>
										<div class="btn-group">
											<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"  onchange="checkBoxes()"  aria-haspopup="true" aria-expanded="false">
												Options <span class="caret"></span>
											</button> 
											<ul class="dropdown-menu">
												<li>
													<a  type="button" href="manage-financier?fn=<?php echo $hashfnid; ?>"><i class="fa fa-plus-square"></i> Manage</a>
												</li>
												<li>
													<a type="button"  href="add-financier?fn=<?php echo $hashfnid; ?>">
													 <i class="glyphicon glyphicon-edit"></i> Edit </a>
												</li>
												<li>
													<a type="button" href="delfn?del=1&amp;fn=<?php echo $hashfnid; ?>"  onclick="return confirm('Are you sure you want to delete this record?')"><i class="glyphicon glyphicon-trash" alt="Delete"  title="Delete Financier"></i>Remove</a>
												</li>       
											</ul>
										</div>
									</td>
								</tr>
								<?php 
							} while ($row_rsfinancier = $query_rsfinancier->fetch());
						}
						else{
						?>
							<tr>
								<td  colspan="9"><div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div></td>
							</tr>
						<?php 
						}
						?>
                    </tbody>
				</table>
			</div>
		</div>
<!--	</div>
</div> -->