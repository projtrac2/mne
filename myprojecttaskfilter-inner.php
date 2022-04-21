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
						<div class="header" style="padding-bottom:15px">
                            <div class="button-demo" style="margin-top:-15px">
								<span class="label bg-black" style="font-size:18px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" /> Menu</span>
								<a href="myprojectdash?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; padding-left:-5px">Details</a>
								<?php if($totalRows_rsTender == 0 && $projcategory == '2'){?>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Milestones</a>
									<?php }else{?>
									<a href="myprojectmilestones?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Milestones</a>
								<?php } ?>
								<?php if($totalRows_rsTender == 0 && $projcategory == '2'){?>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Tasks</a>
									<?php }else{?>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Tasks</a>
								<?php } ?>
								<?php if($totalRows_rsTender == 0 && $projcategory == '2'){?>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Team Members</a>
									<?php }else{?>
									<a href="myprojmembers?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Team Members</a>
								<?php } ?>
								<?php //if($totalRows_rsTender == 0 && $projcategory == '2'){?>
									<!--<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px;  margin-left:-9px">Funding</a>
									<?php //}else{?>
									<a href="myprojectfunding?projid=<?php //echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Funding</a>-->
								<?php //} ?>
								<?php if($totalRows_rsTender == 0 && $projcategory == '2'){?>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Team Messages</a>
									<?php }else{?>
									<a href="myprojectmsgs?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Team Messages</a>
								<?php } ?>
								<?php if($totalRows_rsTender == 0 && $projcategory == '2'){?>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Files</a>
									<?php }else{?>
									<a href="myprojectfiles?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Files</a>
								<?php } ?>
								<?php if($totalRows_rsTender == 0 && $projcategory == '2'){?>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Progress Reports</a>
									<?php }else{?>
									<a href="projreports?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Progress Report</a>
								<?php } ?>
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
                        <div class="header">
							<div style="color:#333; width:100%; height:35px; padding-top:5px; padding-left:2px">
								<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-3px">
									<tr>
										<td width="50%" style="font-size:14px; font-weight:bold"></td>
										<?php
										$pjStatus = $row_rsMSTask['mlstatus'];
										if($pjStatus =="Cancelled" || $pjStatus =="On Hold")
										{
										}
										else{
										?>
											<td width="50%" style="font-size:10px">
												<div class="btn-group" style="float:right">
													<a type="button" class="btn bg-brown waves-effect" href="addtask?projid=<?php echo $row_rsMyP['projid']; ?>&msid=<?php echo $pmsid_rsMSTask; ?>">Add Task</a>
												</div>
											</td>
										<?php
										}
										?>
									</tr>
								</table>
							</div>
                        </div>
                        <div class="body">
							<div class="row clearfix">
                                <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12">
                                    <div class="panel-group" id="accordion_17" role="tablist" aria-multiselectable="true">
                                        <div class="panel panel-col-grey">
                                            <div class="panel-heading" role="tab" id="headingOne_17">
                                                <h4 class="panel-title">
                                                    <a role="button" data-toggle="collapse" data-parent="#accordion_17" href="#collapseOne_17" aria-expanded="true" aria-controls="collapseOne_17">
                                                        <img src="images/task.png" alt="task" /> Milestone Tasks List
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseOne_17" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_17">
                                                <div class="panel-body">
													<div class="table-responsive">
														<table class="table table-bordered table-striped table-hover">
															<?php if($row_rsMyP["projcategory"] == 1){?>
															<thead>
																<tr id="colrow">
																	<th width="3%"><strong> # </strong></th>
																	<th width="23%"><strong>Task</strong></th>
																	<th width="22%"><strong>Milestone</strong></th>
																	<th width="8%"><strong>Status</strong></th>
																	<th width="7%"><strong>Progress</strong></th>
																	<th width="10%"><strong>Budget</strong></th>
																	<th width="10%"><strong>Start Date</strong></th>
																	<th width="10%"><strong>End Date</strong></th>
																	<th colspan="3"><strong>Action</strong></th>
																</tr>
															</thead>
															<?php }else {?>
															<thead>
																<tr id="colrow">
																	<th width="5%"><strong> # </strong></th>
																	<th width="26%"><strong>Task</strong></th>
																	<th width="25%"><strong>Milestone</strong></th>
																	<th width="10%"><strong>Status</strong></th>
																	<th width="7%"><strong>Progress</strong></th>
																	<th width="10%"><strong>Start Date</strong></th>
																	<th width="10%"><strong>End Date</strong></th>
																	<th colspan="3"><strong>Action</strong></th>
																</tr>
															</thead>
															<?php } ?>
															<tbody>
																<!-- =========================================== -->
																<?php 
																$nm = 0;
																do { 
																	$nm = $nm + 1;
																	$taskid = $row_rsMSTask['tkid'];
																	$query_rsStDate = $db->prepare("SELECT status,progress,monitored,sdate,edate FROM tbl_task where tkid='$taskid'");
																	$query_rsStDate->execute();
																	$row_rsStDate = $query_rsStDate->fetch();
																	
																	$tkstatus = $row_rsStDate["status"];
																	$tkmonitored = $row_rsStDate["monitored"];
																	$tkstartdate = $row_rsStDate["sdate"];
																	$tkenddate = $row_rsStDate["edate"];
																	$tkprog = $row_rsStDate["progress"];
																	//$start_date = date_format($projstartdate, "Y-m-d");
																	$current_date = date("Y-m-d");
													
																	/* if($tkstatus == 'Cancelled Task' || $tkstatus == 'On Hold Task')
																	{
																		$tkstatus = $tkstatus;
																		$sqlupdate="UPDATE tbl_task SET status='$tkstatus' WHERE tkid=$taskid";
																	}
																	else{
																		if ($tkstatus == "Pending Task" && $tkstartdate <= $current_date) {
																			$sqlupdate = $db->prepare("UPDATE tbl_task SET status='Task In Progress' WHERE tkid='$taskid'");
																			$sqlupdate->execute();
																		}elseif ($tkstartdate > $current_date){
																			$sqlupdate = $db->prepare("UPDATE tbl_task SET progress='0',status='Pending Task' WHERE tkid='$taskid'");
																			$sqlupdate->execute();
																		}elseif ($tkenddate < $current_date && $tkstatus !== "Completed Task"){
																			$sqlupdate = $db->prepare("UPDATE tbl_task SET status='Task Behind Schedule' WHERE tkid='$taskid'");
																			$sqlupdate->execute();
																		}elseif ($tkstartdate <= $current_date && $tkmonitored==0) {
																			$sqlupdate = $db->prepare("UPDATE tbl_task SET status='Task In Progress' WHERE tkid='$taskid'");
																			$sqlupdate->execute();
																		}
																	} */
																	
																	if($tkprog > 0){
																		$prog = 1;
																	}else{
																		$prog = 0;
																	}
												
																	$prjsdate = strtotime($row_rsMSTask['stdate']);
																	$prjedate = strtotime($row_rsMSTask['endate']);
																	$prjstartdate = date("d M Y",$prjsdate);
																	$prjenddate = date("d M Y",$prjedate);
																	$prjbudget = $row_rsMSTask['taskbudget'];
																	$taskStatus = $row_rsMSTask["status"];
																	
																	?>
																	<tr>
																		<td><?php echo $nm; ?></td>
																		<td><?php echo $row_rsMSTask['task']; ?></td>
																		<td><?php echo $row_rsMSTask['milestone']; ?></td>
																		<td>
																		<?php
																			if($row_rsMSTask['status'] == "Pending Task"){
																				echo '<button type="button" class="btn bg-yellow waves-effect" style="width:100%">'.$row_rsMSTask['status']. '</button>';
																			}else if($row_rsMSTask['status'] == "Task In Progress"){
																				echo '<button type="button" class="btn btn-primary waves-effect" style="width:100%">'.$row_rsMSTask['status'].'</button>';
																			}else if($row_rsMSTask['status'] == "Completed Task"){
																				echo '<button type="button" class="btn btn-success waves-effect" style="width:100%">'.$row_rsMSTask['status']. '</button>';
																			}else if($row_rsMSTask['status'] == "Task Behind Schedule"){
																				echo '<button type="button" class="btn bg-red waves-effect" style="width:100%">'.$row_rsMSTask['status']. '</button>';
																			}elseif($row_rsMSTask['status'] == "Cancelled Task"){
																				echo '<button type="button" class="btn bg-brown waves-effect" style="width:100%">'.$row_rsMSTask['status']. '</button>';
																			}elseif($row_rsMSTask['status'] == "On Hold Task"){
																				echo '<button type="button" class="btn bg-pink waves-effect" style="width:100%">'.$row_rsMSTask['status']. '</button>';
																			}
																		?>
																		</td>
																		<td>
																			<?php
																			$percent3 = $row_rsMSTask['tskprogress'];
																			if ($percent3 < 100) {
																				echo '
																				<div class="progress" style="height:20px; font-size:10px; color:black">
																					<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="'.$row_rsMSTask['tskprogress'].'" aria-valuemin="0" aria-valuemax="100" style="width: '.$row_rsMSTask['tskprogress'].'%; height:20px; font-size:10px; color:black">
																						'.$row_rsMSTask['tskprogress'].'%
																					</div>
																				</div>';
																			} 
																			elseif ($percent3 ==100){
																				echo '
																				<div class="progress" style="height:20px; font-size:10px; color:black">
																					<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="'.$row_rsMSTask['tskprogress'].'" aria-valuemin="0" aria-valuemax="100" style="width: '.$row_rsMSTask['tskprogress'].'%; height:20px; font-size:10px; color:black">
																					'.$row_rsMSTask['tskprogress'].'%                                   
																					</div>
																				</div>';
																			}
																			?>
																		</td>
																		<?php if($row_rsMyP["projcategory"] == 1){?>
																		<td><?php echo number_format($prjbudget, 2); ?></td>
																		<?php } ?>
																		<td><?php echo $prjstartdate; ?></td>
																		<td><?php echo $prjenddate; ?></td>
																		<td width="2%"><?php if($totalRows_rsTender == 0 && $projcategory == '2'){?><?php }else{?><a href="mytaskdetails?tkid=<?php echo $row_rsMSTask['tkid']; ?>"><img src="images/preview.png" alt="view" /><?php } ?></td>
																		<?php
																		if($taskStatus == "Cancelled Task" || $taskStatus == "On Hold Task")
																		{
																		}
																		else{
																		?>
																		<td width="2%"><?php if($totalRows_rsTender == 0 && $projcategory == '2'){?><?php }else{?><a href="updatemytask?tkid=<?php echo $row_rsMSTask['tkid']; ?>"><img src="images/edit.png" alt="edit" /></a><?php } ?></td>
																		<td width="2%"><?php if($totalRows_rsTender == 0 && $projcategory == '2'){?><?php }else{?><a href="deltask?proj=<?php echo $row_rsMSTask['projid']; ?>&tkid=<?php echo $row_rsMSTask['tkid']; ?>" onclick="return confirm('Are you sure you want to delete this task?')"><img src="images/delete.png" alt="del" /></a><?php } ?></td>
																		<?php }
																		?>
																	</tr>
																<?php } while ($row_rsMSTask = $query_rsMSTask->fetch()); ?>
															</tbody>
														</table>
													</div>
												</div>
                                            </div>
                                        </div>
                                        <div class="panel panel-col-cyan">
                                            <div class="panel-heading" role="tab" id="headingTwo_17">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_17" href="#collapseTwo_17" aria-expanded="false"
                                                       aria-controls="collapseTwo_17">
                                                        <i class="fa fa-line-chart" aria-hidden="true"></i> Milestone Tasks Gantt Chart
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseTwo_17" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo_17">
                                                <div class="panel-body">
                                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute,
                                                    non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                                                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid
                                                    single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh
                                                    helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
                                                    Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table,
                                                    raw denim aesthetic synth nesciunt you probably haven't heard of them
                                                    accusamus labore sustainable VHS.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>