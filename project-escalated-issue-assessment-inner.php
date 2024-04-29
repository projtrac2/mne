    <section class="content" style="margin-top:-20px; padding-bottom:0px">
    	<div class="container-fluid">
    		<div class="block-header">
    			<div>
    				<?php echo $results; ?>
    			</div>
    		</div>
    		<div class="block-header bg-brown" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
    			<h4 class="contentheader"><i class="fa fa-columns" aria-hidden="true"></i> Issue Assessment Report</h4>
    		</div>
    		<div class="row clearfix" style="margin-top:10px">
    			<!-- Advanced Form Example With Validation -->
    			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    				<div class="card">
    					<div class="body">
    						<fieldset class="scheduler-border">
    							<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-alert" aria-hidden="true"></i> Issue History</legend>
    							<div style="padding-top: 20px; padding-bottom:-50px">
    								<div class="col-md-12">
    									<label>Project:</label>
    									<div <?= $style ?>><?= $projname ?></div>
    								</div>
    								<div class="col-md-12">
    									<label>Issue:</label>
    									<div <?= $style ?>><?= $issuename ?></div>
    								</div>
    								<div class="col-md-12">
    									<label>Issue Description:</label>
    									<div <?= $style ?>><?= $issuedescription ?></div>
    								</div>
    								<div class="col-md-3">
    									<label>Severity Level:</label>
    									<div <?= $style ?>><?= $severity ?></div>
    								</div>
    								<div class="col-md-9">
    									<label>Mitigation:</label>
    									<div <?= $style ?>><?= $mitigation ?></div>
    								</div>
    								<div class="col-md-3">
    									<label>Recorded By:</label>
    									<div <?= $style ?>><?= $recordedby ?></div>
    								</div>
    								<div class="col-md-3">
    									<label>Date Recorded:</label>
    									<div <?= $style ?>><?= $daterecorded ?></div>
    								</div>
    								<div class="col-md-3">
    									<label>Issue Owner:</label>
    									<div <?= $style ?>><?= $issueowner ?></div>
    								</div>
    								<div class="col-md-3">
    									<label>Date Analysed:</label>
    									<div <?= $style ?>><?= $dateanalysed ?></div>
    								</div>
    								<div class="col-md-12">
    									<label>Analysis Recommendation:</label>
    									<div <?= $style ?>><?= $analysisrecm ?></div>
    								</div>
    								<div class="col-md-4">
    									<label>Escalated By:</label>
    									<div <?= $style ?>><?= $escalatedby ?></div>
    								</div>
    								<div class="col-md-4">
    									<label>Date Escalated:</label>
    									<div <?= $style ?>><?= $dateescalated ?></div>
    								</div>
    								<div class="col-md-12">
    									<label>Team Leader Comments:</label>
    									<div <?= $style ?>><?= $tmleadercomments ?></div>
    								</div>
    								<fieldset class="scheduler-border">
    									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-users" aria-hidden="true"></i> Project Team Members</legend>
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
														$name = $rows['title'] . "." . $rows['fullname'];
														$designation = $rows['designation'];
														$role = $rows['role'];
														if ($role == 1) {
															$role = "Team Leader";
														} elseif ($role == 2) {
															$role = "Deputy Team Leader";
														} elseif ($role == 3) {
															$role = "Officer";
														} else {
															$role = "monitoring Officer";
														}

														$email = $rows['email'];
														$phone = $rows['phone'];
														$avail = $rows['ptleave'];
														if ($avail == 1) {
															$availability = "Unavailable";
															$reassignee = $rows['reassignee'];
															$datereassigned = date("d M Y", strtotime($rows['datereassigned']));

															$query_reassignee =  $db->prepare("SELECT fullname, title, phone, email FROM tbl_projteam2 where ptid='$reassignee'");
															$query_reassignee->execute();
															$row_reassignee = $query_reassignee->fetch();
															$reassigneedto = $row_reassignee["title"] . "." . $row_reassignee["fullname"];
															$reassigneedphone = $row_reassignee["phone"];
															$reassigneedemail = $row_reassignee["email"];
															$availclass = 'class="text-warning"';
														} else {
															$availability = "Available";
															$availclass = 'class="text-success"';
														}
													?>
    													<tr id="rowlines">
    														<td><?php echo $sn; ?></td>
    														<td><?php echo $name; ?></td>
    														<td><?php echo $role; ?></td>
    														<td><?php echo $designation; ?></td>
    														<?php if ($avail == 1) { ?>
    															<td <?= $availclass ?>><span class="mytooltip tooltip-effect-1"><span class="tooltip-item2"><?php echo $availability; ?></span><span class="tooltip-content4 clearfix" style="background-color:#CDDC39; color:#000"><span class="tooltip-text2">
    																			<h4 align="center"><u>Details</u></h4><strong>Unavailable From Date:</strong> <?php echo $datereassigned; ?><br> <strong>In-Place:</strong> <?php echo $reassigneedto; ?><br> <strong>In-Place Phone:</strong> <?php echo $reassigneedphone; ?><br> <strong>In-Place Email:</strong> <?php echo $reassigneedemail; ?>
    																		</span></span></span></td>
    														<?php } else { ?>
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
    						</fieldset>

    						<fieldset class="scheduler-border">
    							<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-comments" aria-hidden="true"></i> Issue Assessment Comments</legend>
    							<div class="col-lg-12 col-md-12 col-sm-12" style="padding-top: 20px">
    								<form id="assessmentcomments" method="POST" name="assessmentcomments" action="" autocomplete="off">
    									<?= csrf_token_html(); ?>
    									<div class="row clearfix" style="padding-left:10px; padding-right:10px">
    										<?php
											$query_projstatuschange =  $db->prepare("SELECT projcode, projstatus, projchangedstatus, projevaluate FROM tbl_projects WHERE projid = '$projid'");
											$query_projstatuschange->execute();
											$row_projstatuschange = $query_projstatuschange->fetch();
											$projstatus = $row_projstatuschange["projstatus"];
											$projcode = $row_projstatuschange["projcode"];
											$projevaluate = $row_projstatuschange["projevaluate"];

											?>
    										<div class="col-md-12">
    											<label>
    												<font color="#174082">Comments:</font>
    											</label>
    											<div class="form-line">
    												<textarea name="comments" cols="45" rows="5" class="txtboxes" id="comments" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required></textarea>
    												<script src="js/issueescalationtextareajs.js"></script>
    											</div>
    										</div>
    										<div class="col-md-12">
    											<label>
    												<font color="#174082">Select and attach a file:</font>
    											</label>
    											<div class="form-line">
    												<input type="file" class="form-control" name="attachment" id="attachment">
    											</div>
    										</div>
    										<div class="row clearfix">
    											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
    												&nbsp;
    											</div>
    											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
    												<div class="btn-group">
    													<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="Save" />
    												</div>
    												<div class="btn-group">
    													<a type="button" class="btn btn-warning" href="project-escalated-issue">Cancel</a>
    												</div>
    											</div>
    											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
    												<input name="projid" type="hidden" id="projid" value="<?php echo $projid; ?>" />
    												<input name="user_name" type="hidden" id="user_name" value="<?php echo $username; ?>" />
    												<input name="issueid" type="hidden" value="<?php echo $issueid; ?>" />
    											</div>
    										</div>
    									</div>
    								</form>
    							</div>
    						</fieldset>
    					</div>
    				</div>
    			</div>
    		</div>
    		<!-- #END# Advanced Form Example With Validation -->
    	</div>
    </section>