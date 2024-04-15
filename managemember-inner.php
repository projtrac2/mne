<?php 

try {
	//code...

?>
	<section class="content" style="margin-top:-20px; padding-bottom:0px">
    	<div class="container-fluid">
    		<div class="row clearfix" style="margin-top:10px">
    			<div class="block-header">
    				<?php
					echo $results;
					?>
    			</div>
    			<!-- Advanced Form Example With Validation -->
    			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    				<div class="card">
    					<div class="header">
    						<div class="row clearfix" style="margin-top:5px">
    							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:0px">
    								<div class="card">
    									<div class="header" style="padding-bottom:0px">
    										<div class="button-demo" style="margin-top:-15px">
    											<span class="label bg-black" style="font-size:18px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" /> Staff Menu</span>
    											<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; padding-left:-5px">Availability</a>
    										</div>
    									</div>
    								</div>
    							</div>
    						</div>
    						<div style="color:#333; background-color:#EEE; width:100%; height:30px; padding-top:5px; padding-left:2px">
    							<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-3px">
    								<tr>
    									<td width="80%" style="padding-left:10px; font-size:16px; font-weight:bold"><i class="fa fa-user" aria-hidden="true"></i> Staff Full Name: <font color="indigo"><?php echo $row_rsStaff["title"] . ". " . $row_rsStaff["fullname"] . " (" . $row_rsStaff["desgn"] . ")"; ?></font>
    									</td>
    									<td width="20%" style="font-size:11px">
    										<div class="btn-group" style="float:right">
    											<a href="projteam" class="btn btn-warning" style="height:27px; ; margin-top:-1px; vertical-align:center">Go Back</a>
    										</div>
    									</td>
    								</tr>
    							</table>
    						</div>
    					</div>
    					<div class="body">
    						<div style="margin-top:5px">
    							<fieldset class="scheduler-border" style="font-size:15px">
    								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">DETAILS</legend>
    								<div class="col-md-12" align="left">
    									<div class="form-line">
    										<img src="<?php echo $row_rsStaff['floc']; ?>" id="mbr" class="img img-rounded" width="120" />
    									</div>
    								</div>
    								<div class="col-md-4">
    									<label>Current Status: <?php echo $emplststus; ?></label>
    								</div>
    								<div class="col-md-4">
    									<label>Phone Number: <font color="indigo"><?php echo $row_rsStaff['phone'] ?></font></label>
    								</div>
    								<div class="col-md-4">
    									<label>Email Address: <font color="indigo"><?php echo $row_rsStaff['email'] ?></font></label>
    								</div>
    								<?php if ($row_rsStaff["availability"] == 1 && $role_group == 2) { ?>
    									<div class="col-md-6">
    										<label>Leave Type:</label>
    										<div class="form-line">
    											<select name="leave" id="leave" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
    												<option value="">... Select Leave ...</option>
    												<?php
													do {
													?>
    													<option value="<?php echo $row_rsLeave['id'] ?>"><?php echo $row_rsLeave['leavename'] ?></option>
    												<?php
													} while ($row_rsLeave = $query_rsLeave->fetch());
													?>
    											</select>
    										</div>
    									</div>
    								<?php } else if ($row_rsStaff["availability"] == 0) {
										$query_rsLvDetails =  $db->prepare("SELECT C.leavename, L.days, L.startdate, L.enddate, L.comments FROM tbl_employee_leave L INNER JOIN tbl_employees_leave_categories C ON L.leavecategory=C.id WHERE L.employee = '$ptid'  ORDER BY L.id DESC LIMIT 1");
										$query_rsLvDetails->execute();
										$row_rsLvDetails = $query_rsLvDetails->fetch();
									?>
    									<div class="col-md-3">
    										<label>Leave Type: <font color="indigo"><?php echo $row_rsLvDetails["leavename"]; ?></font></label>
    									</div>
    									<div class="col-md-3">
    										<label>Leave Days: <font color="indigo"><?php echo $row_rsLvDetails["days"]; ?> Working Days</font></label>
    									</div>
    									<div class="col-md-3">
    										<label>Leave Start Date: <font color="indigo"><?php echo date("d M Y", strtotime($row_rsLvDetails["startdate"])); ?></font></label>
    									</div>
    									<div class="col-md-3">
    										<label>Leave End Date: <font color="indigo"><?php echo date("d M Y", strtotime($row_rsLvDetails["enddate"])); ?></font></label>
    									</div>
    									<div class="col-md-12">
    										<label>Leave Notes: <font color="indigo"><?php echo $row_rsLvDetails["comments"]; ?></font></label>
    									</div>
    						</div>
    					<?php } else {
									} ?>
    					</fieldset>
    					<fieldset class="scheduler-border">
    						<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">ASSIGNED PROJECTS</legend>
    						<!-- File Upload | Drag & Drop OR With Click & Choose -->
    						<div class="row clearfix">
    							<div class="col-md-12 table-responsive">
    								<table class="table table-bordered table-striped table-hover " id="team_projects">
    									<thead>
    										<tr id="colrow">
    											<td width="3%"><strong>SN</strong></td>
    											<td width="40%"><strong>Project Name</strong></td>
    											<td width="11%"><strong>Project Status</strong></td>
    											<td width="13%"><strong>Start Date</strong></td>
    											<td width="13%"><strong>End Date</strong></td>
    											<td width="10%"><strong>Responsible</strong></td>

    										</tr>
    									</thead>
    									<tbody>
    										<?php
											$nm = 0;
											while ($row_mbrprojs = $query_mbrprojs->fetch()) {
												$nm = $nm + 1;
												$projid = $row_mbrprojs['projid'];

												$query_rsProjects =  $db->prepare("SELECT * FROM tbl_projects WHERE projid='$projid'");
												$query_rsProjects->execute();
												$row_rsProjects = $query_rsProjects->fetch();
												$statusid = $row_rsProjects["projstatus"];

												$query_projroles =  $db->prepare("SELECT r.role FROM tbl_projmembers m inner join tbl_project_team_roles r on r.id=m.role WHERE ptid='$ptid' and projid='$projid'");
												$query_projroles->execute();
												$roles = array();
												while ($row_projroles = $query_projroles->fetch()) {
													$rl = $row_projroles["role"];
													$roles[] = $rl;
												}

												if ($statusid == 0) {
													$projstatus = "Under Planning";
												} else {
													$query_projstatus =  $db->prepare("SELECT statusname FROM tbl_status WHERE statusid='$statusid'");
													$query_projstatus->execute();
													$row_projstatus = $query_projstatus->fetch();
													$projstatus = $row_projstatus['statusname'];
												}

												$project = $row_rsProjects['projname'];
												$projstage = $row_rsProjects['projstage'];

												$query_projresp = $db->prepare("SELECT T.title, T.fullname FROM tbl_projteam2 T INNER JOIN tbl_projmembers M ON T.ptid=M.reassignee WHERE M.projid='$projid' AND M.ptleave=1");
												$query_projresp->execute();
												$row_projresp = $query_projresp->fetch();

												if ($projstage > 9) {
													$query_tenderdates = $db->prepare("SELECT startdate, enddate FROM tbl_tenderdetails WHERE projid='$projid'");
													$query_tenderdates->execute();
													$row_tender = $query_tenderdates->fetch();

													$prjsdate = $row_tender['startdate'];
													$prjedate = $row_tender['enddate'];
												} else {
													$prjsdate = $row_rsProjects['projstartdate'];
													$prjedate = $row_rsProjects['projenddate'];
												}

												$projsdate = date("d M Y", strtotime($prjsdate));
												$projedate = date("d M Y", strtotime($prjedate));

											?>
    											<tr>
    												<td><?php echo $projid; ?></td>
    												<td><?php echo $project; ?></td>
    												<td align="center"><?php echo $projstatus; ?></td>
    												<td><?php echo $projsdate; ?></td>
    												<td><?php echo $projedate; ?></td>
    												<td><?php implode(",", $roles) ?></td>
    												<td>
    													<?php
														if ($row_rsStaff["availability"] == 0) {
															echo '' . $row_projresp["title"] . '.' . $row_projresp["fullname"] . '';
														} else {
															echo "N/A";
														}
														?>
    												</td>
    											</tr>
    										<?php
											}
											?>
    									</tbody>
    								</table>
    							</div>
    						</div>
    						<!-- #END# File Upload | Drag & Drop OR With Click & Choose -->
    					</fieldset>
    					<input name="ptid" type="hidden" id="ptid" value="<?php echo $ptid; ?>" />
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    	<!-- #END# Advanced Form Example With Validation -->
    	</div>
    </section>
<?php
} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>