    <section class="content" style="margin-top:-20px; padding-bottom:0px">
        <div class="container-fluid">
            <div class="block-header bg-brown" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader"><i class="fa fa-columns" aria-hidden="true"></i> <font color="#CDDC39"><?=$strategicplan?></font> Strategic Plans</h4>
            </div>
			<div class="row clearfix" style="margin-top:10px">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
					<div class="card">
						<div class="header" style="padding-bottom:0px">
                            <div class="button-demo" style="margin-top:-15px">
								<span class="label bg-black" style="font-size:18px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" /> Menu </span>
								<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:4px">Strategic Plan Details</a>
								<a href="view-kra?plan=<?php echo $stplan; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Key Results Area</a>
								<a href="view-strategic-plan-objectives?plan=<?php echo $stplan; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Strategic Objectives</a>
								<a href="strategic-workplan-budget?plan=<?php echo $stplan; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Strategic Workplan & Budget</a>
								<a href="objective-performance?plan=<?php echo $stplan; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Strategic Plan Implementation Report</a>
							</div>
						</div>
					</div>
				</div>
				<div class="block-header">
					<?php 
						echo $results;
					?>
				</div>
				<!-- Advanced Form Example With Validation -->
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
						<div class="tab-content">
							<div id="home" class="tab-pane fade in active">
								<div class="header">
									<div style="color:#333; background-color:#EEE; width:100%; height:30px">
										<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
											<tr>
												<td width="100%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF"><div align="left" ><img src="images/projbrief.png" alt="img" /> <strong>Strategic Plan Framework</strong></div></td>
											</tr>
										</table>
									</div>
								</div>
								<div class="body table-responsive">
									<table class="table table-bordered">
										<thead>
											<tr style="background-color:#eaf1fc">
												<th style="width:14%"><img src="images/code.png" alt="img" /> <strong>Plan</strong></th>
												<th colspan="2" style="width:38%"><img src="images/status.png" alt="img" /> <strong>Vision</strong></th>
												<th colspan="3" style="width:38%"><img src="images/status.png" alt="img" /> <strong>Mission</strong></th>
												<th style="width:10%"><img src="images/date.png" alt="img" /> <strong>End Date</strong></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><?php echo $strategicplan; ?></td>
												<?php
												$pdate = strtotime($datecreated);
												$dateadded = date("d M Y",$pdate);
												?>
												<td colspan="2"><?php echo $vision; ?></td>
												<td colspan="3"><?php echo $mission; ?></td>
												<td><?php echo $dateadded; ?></td>
											</tr>
										</tbody>
										
										<thead>
											<tr style="background-color:#eaf1fc">
												<th colspan="7">Key Results Areas (KRA)</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td colspan="7">
													<div class="clearfix m-b-20">
														<div class="dd" id="nestable">
															<ol class="dd-list">
																<?php 
																if($totalRows_KRA ==0){
																	echo '<div style="color:RED">NO KRAs DEFINED FOR THIS STRATEGIC PLAN!!</div>';
																}else{
																	$sn = 0;
																	while($row_KRA = $query_KRA->fetch()){ 
																		$sn = $sn + 1;
																		?>
																		<li class="dd-item" data-id="4">
																			<div class="dd-handle">KRA <?php echo $sn; ?>:<font color="blue"> <?php echo $row_KRA['kra']; ?></font></div>
																			<ol class="dd-list">
																			<?php
																				$kraid =  $row_KRA['id'];
																				$kradesc =  $row_KRA['description'];
																				$kradate = date("d M Y",strtotime($row_KRA["date_created"]));
																						
																				$query_obj =  $db->prepare("SELECT * FROM tbl_strategic_plan_objectives WHERE kraid = '$kraid' ORDER BY id");
																				$query_obj->execute();		
																				$totalRows_obj = $query_obj->rowCount();
																				if($totalRows_obj ==0){
																					echo '<div style="color:red">NO OBJECTIVE(S) DEFINED FOR THIS KRA!!</div>';
																				}else{
																					$nb = 0;	
																					while($row_obj = $query_obj->fetch()) {  
																						$nb = $nb + 1;
																						$objid = $row_obj['id'];
																						$objective = $row_obj['objective'];
																						$objdesc = $row_obj['description'];
																						$objdate = date("d M Y",strtotime($row_obj['date_created']));
																						?>
																						<li class="dd-item" data-id="5">
																							<div class="dd-handle">Objective <?php echo $nb; ?>: <font color="#9C27B0"><?php echo $objective; ?></font></div>
																							<ol class="dd-list">
																								<?php
																								
																								$query_strategy =  $db->prepare("SELECT * FROM tbl_objective_strategy where objid='$objid'");
																								$query_strategy->execute();		
																								$totalRows_strategy = $query_strategy->rowCount();
																								
																								if($totalRows_strategy > 0){
																									$sr = 0;
																									while($row_strategy = $query_strategy->fetch()){
																										$sr = $sr + 1;
																										$strgid = $row_strategy['id'];
																										$strategy = $row_strategy['strategy'];
																										$strgdesc = $row_strategy['description'];
																										$strgdate = date("d M Y",strtotime($row_strategy['date_created']));
																								?>
																										<li class="dd-item" data-id="13">
																											<div class="dd-handle">Strategy <?php echo $sr; ?>: <?php echo $row_strategy['strategy']; ?></div>
																										</li>
																									<?php
																									}
																								}else{
																								?>
																									<li class="dd-item" data-id="9">
																										<div class="dd-handle" style="color:#F44336">No Strategy defined for this Objective</div>
																									</li>
																								<?php
																								}
																								?>
																							</ol>
																						</li>
																					<?php
																					}
																				} 
																				?>
																			</ol>
																		</li>
																	<?php
																	}
																}
																?>
															</ol>
														</div>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>   
							</div>
							<?php 
							if($projcat == '2'){
							?>
							<div id="menu1" class="tab-pane fade"> 
								<div class="header">
									<div style="color:#333; background-color:#EEE; width:100%; height:30px">
										<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
											<tr>
												<td width="100%" height="35" style="padding-left:5px; background-color:#2196F3; color:#FFF"><div align="left" ><img src="images/projbrief.png" alt="img" /> <strong>Project Contract/Tender Info</strong></div></td>
											</tr>
										</table>
									</div>
								</div>
								<?php if($tendercount>0){?>
								<div class="body table-responsive">
									<table class="table table-bordered">
										<thead>
											<tr style="background-color:#eaf1fc">
												<th style="width:34%"><i class="fa fa-id-card" aria-hidden="true"></i> <strong>Contractor Name</strong></th>
												<th style="width:16%"><img src="images/code.png" alt="img" /> <strong>Contract Number</strong></th>
												<th style="width:17%"><i class="fa fa-list" aria-hidden="true"></i> <strong>Contract Category</strong></th>
												<th style="width:16%"><i class="fa fa-clipboard" aria-hidden="true"></i> <strong>Procurement Type</strong></th>
												<th style="width:17%"><i class="fa fa-money" aria-hidden="true"></i> <strong>Contract Cost</strong></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><?php echo $contractor['contractor_name']; ?></td>
												<td><?php echo $tenderDetails['contractrefno']; ?></td>
												<td><?php echo $tenderDetails['type']; ?></td>
												<td><?php echo $tenderDetails['cat']; ?></td>
												<td>Ksh. <?php echo number_format($tenderDetails['tenderamount'], 2); ?></td>
											</tr>
										</tbody>
										<thead>
											<tr style="background-color:#eaf1fc">
												<th colspan="1"><i class="fa fa-calendar" aria-hidden="true"> <strong>Contract Signing Date</strong></th>
												<th colspan="2"><i class="fa fa-calendar" aria-hidden="true"> <strong>Contract Start Date</strong></th>
												<th colspan="2"><i class="fa fa-calendar" aria-hidden="true"> <strong>Contract End Date</strong></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td colspan="1">
													<?php echo date("d M Y",strtotime($tenderDetails['signaturedate'])); ?>
												</td>
												<td colspan="2">
													<?php echo date("d M Y",strtotime($tenderDetails['startdate'])); ?>
												</td>
												<td colspan="2">
													<?php echo date("d M Y",strtotime($tenderDetails['enddate'])); ?>
												</td>
											</tr>
										</tbody>
									</table>
								</div> 
								<?php }else{ ?>
								<div style="margin-left:10px; color:red">No Record Found!!!<?php echo $projcat; ?></div>
								<?php } ?>								
							</div>
							<?php 
							}
							?>
						</div>
                    </div>
                </div>
            </div>
            <!-- #END# Advanced Form Example With Validation -->
        </div>
    </section>