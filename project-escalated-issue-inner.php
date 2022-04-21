<section class="content" style="margin-top:-20px; padding-bottom:0px">
        <div class="container-fluid">
            <div class="block-header">
				<div>
					<?php echo $results; ?>
				</div>
            </div>
            <div class="block-header bg-brown" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader"><i class="fa fa-columns" aria-hidden="true"></i> Issue Details</h4>
            </div>
			<?php if($rows_issuedetails > 0){?>
				<div class="row clearfix" style="margin-top:10px">
					<!-- Advanced Form Example With Validation -->
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="card">
							<div class="body">
								<div class="panel-group" id="accordion_17" role="tablist" aria-multiselectable="true">
									<div class="panel panel-col-grey">
										<div class="panel-heading" role="tab" id="headingOne_17">
											<h4 class="panel-title">
												<a role="button" data-toggle="collapse" data-parent="#accordion_17" href="#collapseOne_17" aria-expanded="true" aria-controls="collapseOne_17">
													<img src="images/task.png" alt="task" /> Issue History
												</a>
											</h4>
										</div>
										<div id="collapseOne_17" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_17" style="padding-top: 20px; padding-bottom:-50px">
											<div  class="col-md-12">
												<label>Project:</label>
												<div <?=$style?>><?=$projname?></div>
											</div>
											<div class="col-md-12">
												<label>Issue:</label>
												<div <?=$style?>><?=$issuename?></div>
											</div>
											<div class="col-md-12">
												<label>Issue Description:</label>
												<div <?=$style?>><?=$issuedescription?></div>
											</div>
											<div class="col-md-3">
												<label>Severity Level:</label>
												<div <?=$style?>><?=$severity?></div>
											</div>
											<div class="col-md-9">
												<label>Mitigation:</label>
												<div <?=$style?>><?=$mitigation?></div>
											</div>
											<div class="col-md-3">
												<label>Recorded By:</label>
												<div <?=$style?>><?=$recordedby?></div>
											</div>
											<div class="col-md-3">
												<label>Date Recorded:</label>
												<div <?=$style?>><?=$daterecorded?></div>
											</div>
											<div class="col-md-3">
												<label>Issue Owner:</label>
												<div <?=$style?>><?=$issueowner?></div>
											</div>
											<div class="col-md-3">
												<label>Date Analysed:</label>
												<div <?=$style?>><?=$dateanalysed?></div>
											</div>
											<div class="col-md-12">
												<label>Analysis Recommendation:</label>
												<div <?=$style?>><?=$analysisrecm?></div>
											</div>
											<div class="col-md-4">
												<label>Escalated By:</label>
												<div <?=$style?>><?=$escalatedby?></div>
											</div>
											<div class="col-md-4">
												<label>Date Escalated:</label>
												<div <?=$style?>><?=$dateescalated?></div>
											</div>
											<div class="col-md-12">
												<label>Team Leader Comments:</label>
												<div <?=$style?>><?=$tmleadercomments?></div>
											</div>
											<fieldset class="scheduler-border">
												<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-users" aria-hidden="true"></i> Project Team Members</legend>
												<div class="table-responsive">
													<table class="table table-bordered table-striped table-hover">
														<thead>
															<tr id="colrow">
																<th width="2%"><strong id="colhead">SN</strong></th>
																<th width="23%">Name</strong></td>
																<th width="15%">Role</th>
																<th width="15%">Designation</th>
																<th width="10%">Availability</th>
																<th width="15%">Phone</th>
																<th width="20%">Email</th>
															</tr>
														</thead>
														<tbody>
														<?php
														//return; 
														$sn = 0;
														while ($rows = $query_tmembers->fetch()) { 
															$sn = $sn + 1;
															$name = $rows['title'].".".$rows['fullname'];
															$designation = $rows['designation'];
															$role = $rows['role'];
															if($role==1){
																$role = "Team Leader";
															}elseif($role==2){
																$role = "Deputy Team Leader";
															}elseif($role==3){
																$role = "Officer";
															}else{
																$role = "monitoring Officer";
															}
															
															$email = $rows['email'];
															$phone = $rows['phone'];
															$avail = $rows['ptleave'];
															if($avail==1){
																$availability = "Unavailable";
																$reassignee = $rows['reassignee'];
																$datereassigned = date("d M Y",strtotime($rows['datereassigned']));
																
																$query_reassignee =  $db->prepare("SELECT fullname, title, phone, email FROM tbl_projteam2 where ptid='$reassignee'");
																$query_reassignee->execute();		
																$row_reassignee = $query_reassignee->fetch();
																$reassigneedto = $row_reassignee["title"].".".$row_reassignee["fullname"];
																$reassigneedphone = $row_reassignee["phone"];
																$reassigneedemail = $row_reassignee["email"];
																$availclass = 'class="text-warning"';
															}else{
																$availability = "Available";
																$availclass = 'class="text-success"';
															}
															?>
															<tr id="rowlines">
																<td><?php echo $sn; ?></td>
																<td><?php echo $name; ?></td>
																<td><?php echo $role; ?></td>
																<td><?php echo $designation; ?></td>
																<?php if($avail==1){ ?>
																<td <?=$availclass?>><span class="mytooltip tooltip-effect-1"><span class="tooltip-item2"><?php echo $availability; ?></span><span class="tooltip-content4 clearfix" style="background-color:#CDDC39; color:#000"><span class="tooltip-text2"><h4 align="center"><u>Details</u></h4><strong>Unavailable From Date:</strong> <?php echo $datereassigned; ?><br> <strong>In-Place:</strong> <?php echo $reassigneedto; ?><br> <strong>In-Place Phone:</strong> <?php echo $reassigneedphone; ?><br> <strong>In-Place Email:</strong> <?php echo $reassigneedemail; ?></span></span></span></td>
																<?php }else{ ?>
																<td><?php echo $availability; ?></td>
																<?php } ?>
																<td><?php echo $phone; ?></td>
																<td><?php echo $email; ?></td>
															</tr>
														<?php 
														}
														?>
														</tbody>
													</table>
												</div>
											</fieldset>
										</div>
									</div>
									<div class="panel panel-col-teal">
										<div class="panel-heading" role="tab" id="headingTwo_17">
											<h4 class="panel-title">
												<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_17" href="#collapseTwo_17" aria-expanded="false"
												   aria-controls="collapseTwo_17">
													<i class="fa fa-plus-square" aria-hidden="true"></i> Project Committee Recommendation
												</a>
											</h4>
										</div>
										<div id="collapseTwo_17" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo_17" style="padding-top: 20px">
											<form id="addcmtfrm" method="POST" name="addcmtfrm" action="" autocomplete="off">
												<div class="row clearfix" style="padding-left:10px; padding-right:10px">
													<?php
													$query_projstatuschange =  $db->prepare("SELECT projcode, projstatus, projchangedstatus FROM tbl_projects WHERE projid = '$projid'");
													$query_projstatuschange->execute();		
													$row_projstatuschange = $query_projstatuschange->fetch();
													$projstatus = $row_projstatuschange["projstatus"];
													$projcode = $row_projstatuschange["projcode"];
													
													$query_escalationstage = $db->prepare("SELECT * FROM tbl_projissue_comments WHERE projid='$projid' and rskid='$issueid'");
													$query_escalationstage->execute();	
													$escalationstage_count = $query_escalationstage->rowCount();
													$assessmentcomments = array();
													while($row_escalationstage = $query_escalationstage->fetch()){
														$assessmentcomments[] = $row_escalationstage["stage"];
													}
													
													//6 is On Hold status
													if($projstatus==6){
														$query_issueassessment = $db->prepare("SELECT assessment FROM tbl_projissues WHERE id='$issueid' and projid = '$projid'");
														$query_issueassessment->execute();		
														$row_issueassessment = $query_issueassessment->fetch();
														$assessment = $row_issueassessment["assessment"];
		
														$query_escalationstage = $db->prepare("SELECT * FROM tbl_projissue_comments WHERE projid='$projid' and rskid='$issueid'");
														$query_escalationstage->execute();	
														$escalationstage_count = $query_escalationstage->rowCount();
														$assessmentcomments = array();
														while($row_escalationstage = $query_escalationstage->fetch()){
															$assessmentcomments[] = $row_escalationstage["stage"];
														}
														
														if($assessment==1){
															if(in_array(5, $assessmentcomments)){
																$query_escalationstage_comments = $db->prepare("SELECT comments FROM tbl_projissue_comments WHERE projid='$projid' and rskid='$issueid' and stage=5 LIMIT 1");
																$query_escalationstage_comments->execute();	
																$count_comments = $query_escalationstage_comments->rowCount();
																$escalationstage_comments = $query_escalationstage_comments->fetch();
																?>
																<div class="col-md-12">
																	<label><font color="#174082"><i class="fa fa-bar-chart" aria-hidden="true"></i> Issue Assessment Report:</font>
																	</font></label>
																	<div class="form-control" >
																	<?php echo $escalationstage_comments["comments"]; ?>
																	</div>
																</div>
																<div class="col-md-6">
																	<label><font color="#174082">Committee Final Action:</font></label>
																	<div class="form-line">
																		<select name="projstatuschange" id="issueaction" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px" required>
																			<option value="" selected="selected" class="selection">... Select ...</option>
																			<option value="Restore">Restore Project</option>
																			<option value="Cancelled">Cancel Project</option>
																		</select>
																	</div>
																</div>
															<?php
															}elseif(!in_array(5, $assessmentcomments)){
															?>
																<div class="col-md-12" style="color:#FF5722">
																	<strong>Awaiting for Issue Assessment Report. Please come back later!!</strong>
																</div>
															<?php
															}	
														}
														elseif($assessment==0){
															?>
															<div class="col-md-6">
																<label><font color="#174082">Committee Final Action: <?=$assessment?></font></label>
																<div class="form-line">
																	<select name="projstatuschange" id="issueaction" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px" required>
																		<option value="" selected="selected" class="selection">... Select ...</option>
																		<option value="Restore">Restore Project</option>
																		<option value="Cancelled">Cancel Project</option>
																	</select>
																</div>
															</div>
														<?php
														}
													}
													elseif($projstatus !== 6){
													?>
														<div class="col-md-6">
															<label><font color="#174082">Committee Action:</font></label>
															<div class="form-line">
																<select name="projstatuschange" id="issueaction" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px" required>
																	<option value="" selected="selected" class="selection">... Select ...</option>
																	<option value="Continue">Close Issue and Let Project Continue</option>
																	<option value="On Hold">Put Project On Hold</option>
																	<option value="Cancelled">Cancel Project</option>
																</select>	
															</div>
														</div>
													<?php
													}
													?>
													<div id="content">
													</div> 
													<input name="projid" type="hidden" id="projid" value="<?php echo $projid; ?>" />
													<input name="user_name" type="hidden" id="user_name" value="<?php echo $username; ?>" />
													<input name="deptid" type="hidden" id="deptid" value="<?php echo $opid; ?>" />
													<input name="issuename" type="hidden" id="issuename" value="<?php echo $issuename; ?>" />
													<input name="projstatus" type="hidden" id="projstatus" value="<?php echo $projstatus; ?>" />
													<input name="escalator" type="hidden" id="escalator" value="<?php echo $escdby; ?>" />
													<input name="projissueid" type="hidden" id="issueid" value="<?php echo $issueid; ?>" />
												</div>
											</form>
										</div>
									</div> 
								</div>
							</div>
						</div>
					</div>
				</div>
            <!-- #END# Advanced Form Example With Validation -->
			
			<?php } else {
				echo '	
				<div class="row clearfix" style="margin-top:10px">
					<!-- Advanced Form Example With Validation -->
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="card">
							<div class="body">
								<div class="panel-group" id="accordion_17" role="tablist" aria-multiselectable="true">
									<div class="panel panel-col-red">
										Sorry no data found!
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>';
			}
			?>
        </div>
    </section>