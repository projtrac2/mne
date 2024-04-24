    <section class="content" style="margin-top:-20px; padding-bottom:0px">
        <div class="container-fluid">
            <div class="block-header">          
				<h4>      
					<div class="col-md-8" style="font-size:15px; background-color:#CDDC39; border:#CDDC39 thin solid; border-radius:5px; margin-bottom:2px; height:25px; padding-top:2px; vertical-align:center">
						Project Name: <font color="white"><?php echo $row_rsMyP['projname']; ?></font>
					</div>
					<div class="col-md-4" style="font-size:15px; background-color:#CDDC39; border-radius:5px; height:25px; margin-bottom:2px">
						<div class="barBg" style="margin-top:0px; width:100%; border-radius:1px">
							<div class="bar hundred cornflowerblue">
								<div id="label" class="barFill" style="margin-top:0px; border-radius:1px"><?php echo $percent2 ?>%</div>
							</div>
						</div>
					</div>
				</h4>
            </div>
			<div class="row clearfix" style="margin-top:10px">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
					<div class="card">
						<div class="header" style="padding-bottom:0px">
                            <div class="button-demo" style="margin-top:-15px">
								<a href="myprojectdash?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; padding-left:-5px">Details</a>
								<a href="myprojectmilestones?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Activities</a>
								<a href="myprojecttask?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Work Plan</a>
								<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Financial Plan</a>
								<a href="myprojmembers?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Key Stakeholders</a>
								<a href="projectissueslist?proj=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Issues Log</a>
								<a href="myprojectfiles?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Files</a>
								<a href="projreports?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Progress Report</a>
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
                        <ul class="nav nav-tabs" style="font-size:14px">
							<li class="active">
								<a data-toggle="tab" href="#home"><i class="fa fa-caret-square-o-down bg-deep-orange" aria-hidden="true"></i> PROJECT PLAN &nbsp;<span class="badge bg-orange">|</span></a>
							</li>
							<?php 
							if($projcat == '2'){
							?>
							<li>
								<a data-toggle="tab" href="#menu1"><i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> PROJECT CONTRACT &nbsp;<span class="badge bg-blue">|</span></a>
							</li>
							<?php 
							}
							?>
						</ul>
						<div class="tab-content">
							<div id="home" class="tab-pane fade in active">
								<div class="header">
									<div style="color:#333; background-color:#EEE; width:100%; height:30px">
										<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
											<tr>
												<td width="100%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF"><div align="left" ><img src="images/projbrief.png" alt="img" /> <strong>Project Framework</strong></div></td>
											</tr>
										</table>
									</div>
								</div>
								<div class="body table-responsive">
									<table class="table table-bordered">
										<thead>
											<tr style="background-color:#eaf1fc">
												<th style="width:13%"><img src="images/code.png" alt="img" /> <strong>Project Code</strong></th>
												<th style="width:13%"><img src="images/status.png" alt="img" /> <strong>Project Status</strong></th>
												<th><img src="images/location.png" alt="img" /> <strong>Project Location</strong></th>
												<th style="width:13%"><img src="images/ptype.png" alt="img" /> <strong>Project Type</strong></th>
												<th style="width:15%"><img src="images/money.png" alt="img" /> <strong>Project Budget</strong></th>
												<th style="width:12%"><img src="images/date.png" alt="img" /> <strong>Start Date</strong></th>
												<th style="width:12%"><img src="images/date.png" alt="img" /> <strong>End Date</strong></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><?php echo $row_rsMyP['projcode']; ?></td>
												<?php
												if($projectStatus =="Cancelled" || $projectStatus =="On Hold")
												{
												?>
													<td><?php echo "Project ".$row_rsMyP['projstatus']; ?></td>
												<?php
												}
												else{
												?>
													<td><?php echo $row_rsMyP['projstatus']; ?></td>
												<?php
												}
												
												$prjsdate = strtotime($row_rsMyP['sdate']);
												$prjedate = strtotime($row_rsMyP['edate']);
												$prjstartdate = date("d M Y",$prjsdate);
												$prjenddate = date("d M Y",$prjedate);
												?>
												<td><?php echo $projlocation; ?></td>
												<td><?php echo $row_rsMyP['projtype']; ?></td>
												<td>Ksh. <?php echo $row_rsMyP['FORMAT(tbl_projects.projcost, 2)']; ?></td>
												<td><?php echo $prjstartdate; ?></td>
												<td><?php echo $prjenddate; ?></td>
											</tr>
										</tbody>
										<?php
										if($totalRows_rsMyPrjFund > 0){
										?>
										<thead>
											<tr style="background-color:#eaf1fc">
												<th colspan="2"><img src="images/ptype.png" alt="img" /> <strong>Funding Source</strong></th>
												<th colspan="3"><img src="images/status.png" alt="img" /> <strong>Funder Name</strong></th>
												<th><img src="images/money.png" alt="img" /> <strong>Amount</strong></th>
												<th><img src="images/ptype.png" alt="img" /> <strong>Currency</strong></th>
											</tr>
										</thead>
										<tbody>
										<?php do{?>
											<tr>
												<td colspan="2"><?php echo $row_rsMyPrjFund['source']; ?></td>
												<td colspan="3"><?php echo $row_rsMyPrjFund['funder']; ?></td>
												<td><?php echo $row_rsMyPrjFund['amount']; ?></td>
												<td><?php echo $row_rsMyPrjFund['currency']; ?></td>
											</tr>
										<?php }while($row_rsMyPrjFund = $query_rsMyPrjFund->fetch()); ?>
										</tbody>
										<?php
										}
										?>
										<thead>
											<tr style="background-color:#eaf1fc">
												<th colspan="7">Project Description</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td colspan="7">
													<div id="img" style="background-color:#FFF; border:#EEE thin solid; border-radius:3px; padding-left:10px; font-family:Verdana, Geneva, sans-serif; padding-top:5px"><?php echo $row_rsPDecr['LEFT(projdesc, 500)']; ?>.... </div>
												</td>
											</tr>
										</tbody>
										<thead>
											<tr style="background-color:#eaf1fc">
												<th colspan="7">Expected Project Outcome</th>
												<!--<th colspan="2">Outcome Indicator</th>-->
											</tr>
										</thead>
										<tbody>
											<tr>
												<td colspan="7">
													<?php echo $row_rsExpOutcome['LEFT(Projexpoutcome, 500)']; ?>....
												</td>
												<!--<td colspan="2">NOT DEFINED</td>-->
											</tr>
										</tbody>
										
										<thead>
											<tr style="background-color:#eaf1fc">
												<th colspan="7">Expected Project Output(s)</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td colspan="7">
													<div class="clearfix m-b-20">
														<div class="dd" id="nestable">
															<ol class="dd-list">
																<li class="dd-item no-drag" data-id="1">
																	<div class="dd-handle">Project Output:<font color="green"> <?php echo $row_rsMyPrjDet['output']; ?></font></div>
																	<ol class="dd-list">
																		<li class="dd-item" data-id="2">
																			<table class="table table-bordered">
																				<thead>
																					<tr style="background-color:#eaf1fc">
																						<th width="50%">Indicator Name</th>
																						<th width="25%">Baseline</th>
																						<th width="25%">Target</th>
																					</tr>
																				</thead>
																				<tbody>
																					<tr>
																						<td><?php echo $row_rsMyPrjDet['indicator']; ?></td>
																						<td><?php echo $row_rsMyPrjDet['baseline']; ?></td>
																						<td><?php echo $row_rsMyPrjDet['target']; ?></td>
																					</tr>
																				</tbody>
																				<thead>
																					<tr style="background-color:#eaf1fc">
																						<th>Output Monitoring Frequency</th>
																						<th colspan="2">Next Monitoring Date</th>
																					</tr>
																				</thead>
																				<tbody>
																					<tr>
																						<td><?php echo $row_rsDataFreq["frequency"]; ?></th>
																						<td colspan="2"><?php echo date("d M Y",strtotime($nxtmonitoringdate)); ?></td>
																					</tr>
																				</tbody>
																			</table>
																		</li>
																		<?php 
																		if($totalRows_rsMilestone ==0){
																			echo '<div style="color:RED">NO MILESTONES DEFINED FOR THIS PROJECT!!</div>';
																		}else{
																			$sn = 0;
																			do { 
																			$sn = $sn + 1;
																			?>
																			<li class="dd-item" data-id="4">
																				<div class="dd-handle">Output Milestones <?php echo $sn; ?>:<font color="blue"> <?php echo $row_rsMilestone['milestone']; ?></font></div>
																				<ol class="dd-list">
																					<table class="table table-bordered">
																						<thead>
																							<tr style="background-color:#eaf1fc">
																								<?php if($projcategory==2){ ?>
																								<th>Budget (Ksh)</th>
																								<?php }else{ }?>
																								<th>Start Date</th>
																								<th>End Date</th>
																							</tr>
																						</thead>
																						<tbody>
																						<?php
																							$milestoneID =  $row_rsMilestone['msid'];
																							$mlStatus =  $row_rsMilestone['status'];
																							$mlsdate = strtotime($row_rsMilestone["sdate"]);
																							$mledate = strtotime($row_rsMilestone["edate"]);
																							$mlstartdate = date("d M Y",$mlsdate);
																							$mlenddate = date("d M Y",$mledate);
																							?>
																							<tr>
																								<?php if($projcategory==2){ ?>
																								<td><?php echo number_format($row_rsMilestone['milestonebudget'], 2); ?></td>
																								<?php }else{ }?>
																								<td><?php echo $mlstartdate; ?></td>
																								<td><?php echo $mlenddate; ?></td>
																							</tr>
																						</tbody>
																					</table>
																					<?php
																					
																					if (isset($_GET['projid'])) {
																						$MilestoneProjID = $_GET['projid'];
																					}
																					
																					$query_rsMSTask =  $db->prepare("SELECT tbl_task.task, tbl_task.tkid, tbl_task.taskbudget, tbl_task.responsible, tbl_task.parenttask, tbl_task.status, tbl_task.progress AS tskprogress, tbl_task.sdate AS tkstdate, tbl_task.edate AS tkendate FROM tbl_task INNER JOIN tbl_milestone ON tbl_milestone.msid=tbl_task.msid INNER JOIN tbl_projects ON tbl_projects.projid=tbl_milestone.projid WHERE tbl_task.projid = '$MilestoneProjID' AND tbl_task.msid = '$milestoneID' GROUP BY tbl_task.tkid ORDER BY tbl_task.tkid");
																					$query_rsMSTask->execute();		
																					$row_rsMSTask = $query_rsMSTask->fetch();
																					$totalRows_rsMSTask = $query_rsMSTask->rowCount();
																					if($totalRows_rsMSTask ==0){
																						echo '<div style="color:red">NO TASK(S) DEFINED FOR THIS MILESTONE!!</div>';
																					}else{
																					do { 
																					$taskid = $row_rsMSTask['tkid'];
																					$tskResp = $row_rsMSTask['responsible'];
																					
																					$query_rsTskInd =  $db->prepare("SELECT indname FROM tbl_indicator i inner join tbl_task t on t.taskindicator=i.indid where tkid='$taskid'");
																					$query_rsTskInd->execute();		
																					$row_rsTskInd = $query_rsTskInd->fetch();
																					
																					$query_rsStDate =  $db->prepare("SELECT status,progress,monitored,sdate,edate FROM tbl_task where tkid='$taskid'");
																					$query_rsStDate->execute();		
																					$row_rsStDate = $query_rsStDate->fetch();
																					
																					$tkstatus = $row_rsStDate["status"];
																					$tkmonitored = $row_rsStDate["monitored"];
																					$sdate = strtotime($row_rsMSTask['tkstdate']);
																					$edate = strtotime($row_rsMSTask['tkendate']);
																					$tkstartdate = date("d M Y",$sdate);
																					$tkenddate = date("d M Y",$edate);
																					$tkprog = $row_rsStDate["progress"];
																					//$start_date = date_format($projstartdate, "Y-m-d");
																					$current_date = date("Y-m-d");
																					?>
																					<li class="dd-item" data-id="5">
																						<div class="dd-handle">Task <?php echo $row_rsMSTask['sn']; ?>: <font color="#9C27B0"><?php echo $row_rsMSTask['task']; ?></font></div>
																						<ol class="dd-list">
																							<table class="table table-bordered">
																								<thead>
																									<tr style="background-color:#eaf1fc">
																										<th>Parent Task</th>
																										<th>Indicator Name</th>
																										<?php if($projcategory==1){ ?>
																										<th>Budget (Ksh)</th>
																										<?php }else{ }?>
																										<th>Start Date</th>
																										<th>End Date</th>
																									</tr>
																								</thead>
																								<tbody>
																									<tr>
																										<td><?php echo $row_rsMSTask['parenttask']; ?></td>
																										<td><?php echo $row_rsTskInd['indname']; ?></td>
																										<?php if($projcategory==1){ ?>
																										<td><?php echo number_format($row_rsMSTask['taskbudget'], 2); ?></td>
																										<?php }else{ }?>
																										<td><?php echo $tkstartdate; ?></td>
																										<td><?php echo $tkenddate; ?></td>
																									</tr>
																								</tbody>
																							</table>
																							<?php
																							
																							$query_rsTskChklist =  $db->prepare("SELECT * FROM tbl_task_checklist where taskid='$taskid'");
																							$query_rsTskChklist->execute();		
																							$row_rsTskChklist = $query_rsTskChklist->fetch();
																							$totalRows_rsTskChklist = $query_rsTskChklist->rowCount();
																							
																							?>
																							<li class="dd-item" data-id="13">
																								<div class="dd-handle">Task Monitoring Checklist</div>
																								<ol class="dd-list">
																								<?php
																								if($totalRows_rsTskChklist > 0){
																									$sr = 0;
																									do{
																										$sr = $sr + 1;
																										?>
																										<li class="dd-item" data-id="9">
																											<div class="dd-handle">Parameter <?php echo $sr; ?>: <?php echo $row_rsTskChklist['name']; ?></div>
																										</li>
																									<?php
																									} while($row_rsTskChklist = $query_rsTskChklist->fetch());
																								}else{
																								?>
																									<li class="dd-item" data-id="9">
																										<div class="dd-handle" style="color:#F44336">Data Input not defined</div>
																									</li>
																								<?php
																								}
																								?>
																								</ol>
																							</li>
																							<?php
																							
																							$query_rsTaskResp =  $db->prepare("SELECT T.*, D.designation AS desgn, S.sector AS dept FROM tbl_projteam2 T LEFT JOIN tbl_pmdesignation D ON T.designation=D.moid LEFT JOIN tbl_sectors S ON T.department=S.stid WHERE ptid = '$tskResp' AND disabled = '0'");
																							$query_rsTaskResp->execute();		
																							$row_rsTaskResp = $query_rsTaskResp->fetch();
																							$totalRows_rsTaskResp = $query_rsTaskResp->rowCount();
																							if(empty($row_rsTaskResp['dept']) || $row_rsTaskResp['dept']==''){
																								$department = "All ".$departmentlabelplural;
																							}else{
																								$department = $row_rsTaskResp['dept'];
																							}
																							?>
																							<li class="dd-item" data-id="13">
																								<div class="dd-handle">Responsible</div>
																								<ol class="dd-list">
																								<?php
																								if($totalRows_rsTaskResp > 0){
																								?>
																									<table class="table table-bordered">
																										<thead>
																											<tr style="background-color:#eaf1fc">
																												<th>#</th>
																												<th>Photo</th>
																												<th>Full Name</th>
																												<th>Designation</th>
																												<th><?=$departmentlabel?></th>
																												<th>Email</th>
																												<th>Phone</th>
																											</tr>
																										</thead>
																										<tbody>
																										<?php
																										$rwno = 0;
																										do{
																											$rwno = $rwno + 1;
																										?>
																											<tr>
																												<td><?php echo $rwno; ?></td>
																												<td><img src="<?php echo $row_rsTaskResp['floc']; ?>" style="width:30px; height:30px; margin-bottom:0px"/></td>
																												<td><?php echo $row_rsTaskResp['title'].". ".$row_rsTaskResp['fullname']; ?></td>
																												<td><?php echo $row_rsTaskResp['desgn']; ?></th>
																												<td><?php echo $department; ?></td>
																												<td><?php echo $row_rsTaskResp['email']; ?></td>
																												<td><?php echo $row_rsTaskResp['phone']; ?></td>
																											</tr>
																										<?php
																										}while($row_rsTaskResp = $query_rsTaskResp->fetch());
																										?>
																										</tbody>
																									</table>
																								<?php
																								}else{
																								?>
																									<li class="dd-item" data-id="9">
																										<div class="dd-handle" style="color:#F44336">No defined team member(s) responsible for this Task</div>
																									</li>
																								<?php
																								}
																								?>
																								</ol>
																							</li>
																							<li class="dd-item" data-id="13">
																								<div class="dd-handle">Project Fixed Assets</div>
																								<ol class="dd-list">
																								<?php if($projcategory==2){ ?>
																									<table class="table table-bordered">
																										<thead>
																											<tr style="background-color:#eaf1fc">
																												<th>#</th>
																												<th>Category</th>
																												<th>Name</th>
																												<th>Type</th>
																												<th>Serial No.</th>
																												<th>Status</th>
																												<th>Manufacturer</th>
																												<th>Vendor</th>
																												<th>Start Supplied</th>
																											</tr>
																										</thead>
																										<tbody>
																											<tr>
																												<td>1.</td>
																												<td>Construction & Civil Engineering</td>
																												<td>Compaction Rollers</td>
																												<td>HAMM 3520</td>
																												<td>M1761482</td>
																												<td>Good Condition</th>
																												<td>HAMM</td>
																												<td>BLC PLANT</td>
																												<td>25/04/2016</td>
																											</tr>
																											<tr>
																												<td>2.</td>
																												<td>Construction & Civil Engineering</td>
																												<td>Compaction Rollers</td>
																												<td>CAT CS76</td>
																												<td>CYX00360</th>
																												<td>Good Condition</th>
																												<td>CATERPILLAR</td>
																												<td>Mantrac Kenya</td>
																												<td>29/04/2016</td>
																											</tr>
																											<tr>
																												<td>3.</td>
																												<td>Construction & Civil Engineering</td>
																												<td>Motor Graders</td>
																												<td>CAT 140K</td>
																												<td>SZL02190</th>
																												<td>Good Condition</th>
																												<td>CATERPILLAR</td>
																												<td>Mantrac Kenya</td>
																												<td>29/04/2016</td>
																											</tr>
																											<tr>
																												<td>4.</td>
																												<td>Construction & Civil Engineering</td>
																												<td>Motor Graders</td>
																												<td>CAT 140K</td>
																												<td>SZL01603</th>
																												<td>Good Condition</th>
																												<td>CATERPILLAR</td>
																												<td>Mantrac Kenya</td>
																												<td>29/04/2016</td>
																											</tr>
																											<tr>
																												<td>5.</td>
																												<td>Construction & Civil Engineering</td>
																												<td>Motor Graders</td>
																												<td>CAT 140K</td>
																												<td>SZL01603</th>
																												<td>Good Condition</th>
																												<td>CATERPILLAR</td>
																												<td>Mantrac Kenya</td>
																												<td>29/04/2016</td>
																											</tr>
																											<tr>
																												<td>6.</td>
																												<td>Construction & Civil Engineering</td>
																												<td>Wheel Loaders</td>
																												<td>LG936L</td>
																												<td>LGS936L118756</th>
																												<td>Good Condition</th>
																												<td>SDLG</td>
																												<td>SDLG Africa</td>
																												<td>15/02/2017</td>
																											</tr>
																											<tr>
																												<td>7.</td>
																												<td>Construction & Civil Engineering</td>
																												<td>Wheel Loaders</td>
																												<td>LG978</td>
																												<td>LGS978756076</th>
																												<td>Good Condition</th>
																												<td>SDLG</td>
																												<td>SDLG Africa</td>
																												<td>15/02/2017</td>
																											</tr>
																											<tr>
																												<td>8.</td>
																												<td>Construction & Civil Engineering</td>
																												<td>LARGE CRAWLER EXCAVATORS</td>
																												<td>VOLVO EC950E</td>
																												<td>VV8936278</th>
																												<td>Good Condition</th>
																												<td>VOLVO</td>
																												<td>CONSTRUCTION EQUIPMENT AFRICA</td>
																												<td>21/02/2015</td>
																											</tr>
																											<tr>
																												<td>9.</td>
																												<td>Construction & Civil Engineering</td>
																												<td>TRACKED PAVERS</td>
																												<td>VOLVO P4820D ABG</td>
																												<td>VV83657459</th>
																												<td>Good Condition</th>
																												<td>VOLVO</td>
																												<td>CONSTRUCTION EQUIPMENT AFRICA</td>
																												<td>22/02/2015</td>
																											</tr>
																										</tbody>
																									</table>	
																								<?php
																								}else{
																								?>
																									<li class="dd-item" data-id="9">
																										<div class="dd-handle" style="color:#F44336">No defined asset(s) attached to this Task</div>
																									</li>
																								<?php
																								}
																								?>
																								</ol>
																							</li>
																						</ol>
																					</li>
																					<?php
																					} while ($row_rsMSTask = $query_rsMSTask->fetch()); 
																					}
																					?>
																				</ol>
																			</li>
																			<?php
																			} while ($row_rsMilestone = $query_rsMilestone->fetch()); 
																		}
																		?>
																	</ol>
																</li>
																<!--<li class="dd-item" data-id="19">
																	<div class="dd-handle">Another Output if more than One</div>
																</li>-->
															</ol>
														</div>
													</div>
												</td>
											</tr>
										</tbody>
										<thead>
											<tr style="background-color:#eaf1fc">
												<th colspan="7">Project Risks</th>
											</tr>
										</thead>
										<tbody>
										<?php if($cntAssump == 0) {?> 
											<tr>
												<td colspan="7" style="color:red"> 
													RISKS NOT DEFINED
												</td>
											</tr>
										<?php } else {
											$sn = 0;
											while($row_rsProjAssumptions = $query_rsProjAssumptions->fetch()){
												$sn = $sn + 1;
											?> 
												<tr>
													<td colspan="7">
														<?php echo $sn.". ".$row_rsProjAssumptions['category']; ?>
													</td>
												</tr>
											<?php 
											}
										} ?> 
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