<section class="content" style="margin-top:-20px; padding-bottom:0px">
	<div class="container-fluid">
		<div class="block-header">
			<?php
			echo $results;
			?>
			<h4 class="contentheader"><i class="fa fa-plus-square" aria-hidden="true"></i> Add New Project</h4>
			<div>
			</div>
		</div> 
		<!-- Draggable Handles -->
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="body" style="margin-top:5px">
						<div class="stepwizard" style="margin-bottom:15px">
							<div class="stepwizard-row setup-panel bg-light-blue" style="margin-top:10px">
								<div class="stepwizard-step">
									<a href="#step-1" type="button" data-toggle="tab" class="btn btn-primary btn-circle"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
									<p>Project Details</p>
								</div>
								<div class="stepwizard-step">
									<a href="#step-2" type="button" data-toggle="tab" class="btn btn-default btn-circle disabled"><i class="fa fa-bullseye fa-3x" aria-hidden="true"></i></a></a>
									<p>Output Plan</p>
								</div>
								<div class="stepwizard-step">
									<a href="#step-3" type="button" data-toggle="tab" class="btn btn-default btn-circle disabled"><i class="fa fa-bullseye fa-3x" aria-hidden="true"></i></a></a>
									<p>Implementors</p>
								</div>
								<div class="stepwizard-step">
									<a href="#step-4" type="button" data-toggle="tab" class="btn btn-default btn-circle disabled">
										<i class="fa fa-bullseye fa-3x" aria-hidden="true"></i>
									</a>
									<p>Documents</p>
								</div>
								<div class="stepwizard-step">
									<a href="#step-5" type="button" data-toggle="tab" onclick="display_finish()" class="btn btn-default btn-circle disabled nextBtn1  finish"><i class="fa fa-check" aria-hidden="true"></i></a>
									<p>Finish</p>
								</div>
							</div>
						</div>
						<form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
							<fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">ADD PROJECT DETAILS</legend>
								<div class="col-md-6">
									<label class="control-label">Program Name*:</label>
									<div class="form-line">
										<input type="hidden" name="progid" id="progid" class="form-control" value="<?php echo $progid ?>">
										<input type="text" name="progid" id="prog" value="<?php echo $progname ?>" placeholder="Please enter name of your project" class="form-control" style="border:#CCC thin solid; border-radius: 5px" disabled>
									</div>
								</div>
								<div class="col-md-2">
									<label class="control-label">Program Start Year *:</label>
									<div class="form-line">
										<input type="text" name="progstartyear" id="progstartyear" value="<?php echo $syear ?>" placeholder="" class="form-control" style="border:#CCC thin solid; border-radius: 5px" disabled>
									</div>
								</div>
								<div class="col-md-2">
									<label class="control-label">Program End Year *:</label>
									<div class="form-line">
										<input type="text" name="progendyear" id="progendyear" value="<?php echo $syear + $years ?>" placeholder="" class="form-control" style="border:#CCC thin solid; border-radius: 5px" disabled>
									</div>
								</div>
								<div class="col-md-2">
									<label class="control-label">Program Duration *:</label>
									<div class="form-line">
										<input type="text" name="progduration" id="progduration" value="<?php echo $years ?>"" placeholder="" class=" form-control" style="border:#CCC thin solid; border-radius: 5px" disabled>
									</div>
								</div>
								<div class="col-md-3">
									<label class="control-label">Project Code (Eg. 2018/12/AB23) *:</label>
									<span id="gt" style="display:none; color:#fff; background-color:#F44336; padding:5px"> Code Exists </span>
									<div class="form-line">
										<input type="text" name="projcode" onblur="validate_projcode()" id="projcode" value="" placeholder="Project Code" class="form-control" style="border:#CCC thin solid; border-radius: 5px" required="required">
										<span id="projcodemsg" style="color:red"> </span>
									</div>
								</div>
								<div class="col-md-9">
									<label class="control-label">Project Name *:</label>
									<div class="form-line">
										<input type="text" name="projname" id="projname" placeholder="Project Name" class="form-control" style="border:#CCC thin solid; border-radius: 5px" required>
									</div>
								</div>
								<div class="col-md-4">
									<label class="control-label">Project Start Financial Year *:</label>
									<div class="form-line">
										<input type="hidden" name="hfscyear" id="hfscyear">
										<select name="projfscyear1" id="projfscyear1" onchange="finacial_year_change()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
											<option value="">.... Select Year from list ....</option>
											<?php
											$financialYear = array_unique($financialYear);
											foreach ($financialYear as $financialyear) {
												// get financial years 
												$query_rsYear =  $db->prepare("SELECT * FROM tbl_fiscal_year where yr ='$financialyear'");
												$query_rsYear->execute();
												$row_rsYear = $query_rsYear->fetch();

												$yrstartdate = $row_rsYear["sdate"];
												$yrenddate = $row_rsYear["edate"];
												$currdatetime = date("Y-m-d H:i:s");

												if ($currdatetime <= $yrenddate) {
													$finyear = $row_rsYear['year'];
													$finyearid = $row_rsYear['id'];
													$yr = $row_rsYear["yr"];
													echo '<option value="' . $finyearid . '">' . $finyear . '</option>';
												}
											}
											?>
										</select>
										<span id="projfscyearmsg1" style="color:red"></span>
									</div>
								</div>
								<div class="col-md-4">
									<label for="projduration">Project Duration *:</label><span id="projdurationmsg" style="color:darkgoldenrod"></span>
									<div class="form-input">
										<input type="hidden" name="hduration" id="hduration">
										<input type="number" name="projduration1" onkeyup="project_duration_validate()" onchange="project_duration_validate()" id="projduration1" placeholder="Enter" class="form-control" required>
										<span id="projdurationmsg1" style="color:red"></span>
									</div>
								</div>
								<div class="col-md-4">
									<label for="projendyear">Project End Financial Year *:</label>
									<input type="text" name="projendyear" id="projendyear" class="form-control" disabled>
									<input type="hidden" name="hprojendyear" id="hprojendyear" class="form-control" disabled>
									<input type="hidden" name="projendyearDate" id="projendyearDate" class="form-control">
									<input type="hidden" name="projectStartingYear" id="projectStartingYear" class="form-control">
									<span id="" style="color:red"></span>
								</div>
								<div class="col-md-3">
									<label for="" class="control-label">Evaluation Required? *:</label>
									<div class="form-line">
										<input name="projevaluation" type="radio" value="1" id="evaluation1" class="with-gap radio-col-green evaluation" required="required" />
										<label for="evaluation1">YES</label>
										<input name="projevaluation" type="radio" value="0" id="evaluation2" class="with-gap radio-col-red evaluation" required="required" />
										<label for="evaluation2">NO</label>
									</div>
								</div>
								<div class="col-md-3">
									<label for="" class="control-label">Mapping Required? *:</label>
									<div class="form-line">
										<input name="projmapping" type="radio" value="1" id="mapp1" class="with-gap radio-col-green mapp" required="required" />
										<label for="mapp1">YES</label>
										<input name="projmapping" type="radio" value="0" id="mapp2" class="with-gap radio-col-red mapp" required="required" />
										<label for="mapp2">NO</label>
									</div>
								</div>
								<div class="col-md-3">
									<label for="" class="control-label">Inspection Required? *:</label>
									<div class="form-line">
										<input name="projinspection" type="radio" value="1" id="insp1" class="with-gap radio-col-green insp" required="required" />
										<label for="insp1">YES</label>
										<input name="projinspection" type="radio" value="0" id="insp2" class="with-gap radio-col-red insp" required="required" />
										<label for="insp2">NO</label>
									</div>
								</div>
								<div class="col-md-3">
									<label class="control-label">Implementation Method *:</label>
									<div class="form-line">
										<select name="projimplmethod" id="projimplmethod" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
											<option value="">.... Select the method ....</option>
											<?php
											do {
											?>
												<option value="<?php echo $row_rsProjImplMethod['id'] ?>"><?php echo $row_rsProjImplMethod['method'] ?></option>
											<?php
											} while ($row_rsProjImplMethod = $query_rsProjImplMethod->fetch());
											?>
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<label class="control-label">Project <?= $level1label ?>*:</label>
									<div class="form-line">
										<input type="hidden" name="level1label" id="level1label" value="" />
										<select name="projcommunity[]" id="projcommunity" onchange="conservancy()" data-actions-box="true" class="form-control show-tick selectpicker" title="Choose Multipe" multiple style="border:#CCC thin solid; border-radius:5px; width:98%; padding-left:50px" required>
											<?php
											$data = '';
											$id = [];
											do {
												$comm = $row_rsComm['id'];
												$query_ward = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:comm AND active=1");
												$query_ward->execute(array(":comm" => $comm));
												while ($row = $query_ward->fetch()) {
													$projlga = $row['id'];
													$query_rsLocations = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:id");
													$query_rsLocations->execute(array(":id" => $projlga));
													$row_rsLocations = $query_rsLocations->fetch();
													$total_locations = $query_rsLocations->rowCount();
													if ($total_locations > 0) {
														if (!in_array($comm, $id)) {
															$data .= '<option value="' . $row_rsComm['id'] . '">' . $row_rsComm['state'] . '</option>';
														}
														$id[] = $row_rsComm['id'];
													}
												}
											} while ($row_rsComm = $query_rsComm->fetch());
											echo $data;
											?>
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<label class="control-label">Project <?= $level2label ?>*:</label>
									<div class="form-line">
										<input type="hidden" name="level2label" id="level2label" value="" />
										<select name="projlga[]" id="projlga" onchange="ecosystem()" class="form-control show-tick selectpicker" multiple data-actions-box="true" title="Choose Multipe" style="border:#CCC thin solid; border-radius:5px; width:98%; padding-right:0px" required>
											<option value="" style="padding-right:0px">.... Select <?= $level1label ?> First ....</option>
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<label class="control-label">Project <?= $level3label ?>*:</label>
									<div class="form-line">
										<input type="hidden" name="level2label" id="level2label" value="" />
										<input type="hidden" name="level3label" id="level3label" value="" />
										<select name="projstate[]" class="form-control show-tick selectpicker" onchange="forest()" data-actions-box="true" title="Choose Multipe" multiple id="projstate" style="border:#CCC thin solid; border-radius:5px; width:98%; padding-right:0px" required>
											<option value="" style="padding-right:0px">.... Select <?= $level2label ?> First ....</option>
										</select>
									</div>
								</div>
								<div class="col-md-12">
									<button class="btn btn-primary nextBtn pull-right" id="nextT" type="button">Next</button>
								</div>
							</fieldset>
							<fieldset class="scheduler-border row setup-content" id="step-2">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">ADD OUTPUT PLAN</legend>
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">3.0 Add Output Details </legend>
									<div class="col-md-12" id="projoutputTable">
									</div>
									<div id="val_states">
										<input type="hidden" name="stateVal" id="stateVal">
									</div>
								</fieldset>
								<fieldset class="scheduler-border" id="op_targets_div_fieldset">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">3.1 Output Plan Details </legend>
									<div id="op_targets_div">
										<div class="elementT">
										</div>
									</div>
								</fieldset>
								<fieldset class="scheduler-border" id="location_targets_div_fieldset">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">3.2 Location Dissagragation Details </legend>
									<div id="location_targets_div">
										<div class="element">
										</div>
									</div>
								</fieldset>
								<div class="col-md-12">
									<ul class="list-inline pull-right">
										<li><button type="button" class="btn btn-warning prev-step">Previous</button></li>
										<li><button class="btn btn-primary nextBtn btn-sm" type="button">Next</button> </li>
									</ul>
								</div>
							</fieldset>
							<fieldset class="scheduler-border row setup-content" id="step-3">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">ADD IMPLEMENTORS</legend>
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Implementors</legend>
									<div class="col-md-6">
										<label class="control-label">Lead Implementor *:</label>
										<div class="form-line">
											<select name="projleadimplementor" id="projleadimplementor" onchange="project_lead_implementor()" class="form-control show-tick selectpicker" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
												<option value="">.... Select from list ....</option>
												<?php
												do {
												?>
													<option value="<?php echo $row_rsPartner['id'] ?>"><?php echo $row_rsPartner['financier'] ?></option>
												<?php
												} while ($row_rsPartner = $query_rsPartner->fetch());
												?>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<label class="control-label">Partner *:</label>
										<div class="form-line">
											<select name="projimplementingpartner[]" multiple id="projimplementingpartner" class="form-control show-tick selectpicker" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
												<option value="">.... Select from list ....</option>
											</select>
										</div>
									</div> 
									<div class="col-md-12">
									</div>
								</fieldset>
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Project Financier/s</legend>
									<div class="col-md-6" id="">
										<label for="" id="" class="control-label">Project Budget *:</label>
										<div class="form-input">
											<input type="hidden" name="financierceiling" value="" id="financierceiling">
											<input type="text" name="outputcost" id="outputcost" value="" placeholder="" class="form-control" disabled>
										</div>
									</div>
									<div class="col-md-12" id="projfinancier">
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-hover" id="financier_table" style="width:100%">
												<thead>
													<tr>
														<th width="10%">#</th>
														<th width="30%">Financier</th>
														<th width="30%">Ceiling (ksh)</th>
														<th width="30%">Amount (Ksh)</th>
														<th width="5%">
															<button type="button" name="addplus" id="addplus_financier" onclick="add_row_financier();" class="btn btn-success btn-sm">
																<span class="glyphicon glyphicon-plus">
																</span>
															</button>
														</th>
													</tr>
												</thead>
												<tbody id="financier_table_body">
													<tr></tr>
													<tr id="removeTr">
														<td colspan="5">Add Financiers</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</fieldset>
								<div class="col-md-12">
									<ul class="list-inline pull-right">
										<li><button type="button" class="btn btn-warning prev-step">Previous</button></li>
										<li><button class="btn btn-primary nextBtn nextBtn1 btn-sm" type="button">Next</button> </li>
									</ul>
								</div>
							</fieldset>
							<fieldset class="scheduler-border row setup-content" id="step-4">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">FILES</legend>
								<div class="col-md-12">
									<div class="body table-responsive">
										<table class="table table-bordered" style="width:100%">
											<thead>
												<tr>
													<th style="width:2%">#</th>
													<th style="width:36%">Attachment</th>
													<th style="width:60%">Purpose</th>
													<th style="width:2%">
														<button type="button" name="addplus1" onclick="add_row_files();" title="Add another document" class="btn btn-success btn-sm">
															<span class="glyphicon glyphicon-plus"></span>
														</button>
													</th>
												</tr>
											</thead>
											<tbody id="meetings_table">
												<tr>
													<td>
														1
													</td>
													<td>
														<input type="file" name="pfiles[]" multiple id="pfiles" class="form-control file_attachment" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
													</td>
													<td>
														<input type="text" name="attachmentpurpose[]" id="attachmentpurpose" class="form-control attachment_purpose" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
													</td>
													<td></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<div class="col-md-12">
									<ul class="list-inline pull-right">
										<li><button type="button" class="btn btn-warning prev-step">Previous</button></li>
										<li><button class="btn btn-primary nextBtn btn-sm" onclick="display_finish()" type="button">Next</button> </li>
									</ul>
								</div>
							</fieldset>
							<fieldset class="scheduler-border row setup-content" id="step-5">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">FINISH</legend>
								<div class="row clearfix " id="">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="card">
											<div class="body">
												<div class="row">
													<div class="col-xs-12">
														<fieldset class="scheduler-border">
															<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">1.0) Project Details</legend>
															<div class="table-responsive">
																<table summary="This table shows how to create responsive tables using Bootstrap's default functionality" class="table table-bordered table-hover">
																	<thead>
																		<tr>
																			<th width="5%">#</th>
																			<th width="35%">Field</th>
																			<th width="60%">Value</th>
																		</tr>
																	</thead>
																	<tbody>
																		<tr>
																			<td>1</td>
																			<td>Programe Name</td>
																			<td id="progs"></td>
																		</tr>
																		<tr>
																			<td>2</td>
																			<td>Project Code</td>
																			<td id="projcodes"></td>
																		</tr>
																		<tr>
																			<td>3</td>
																			<td>Project Name</td>
																			<td id="projName"></td>
																		</tr>
																		<tr>
																			<td>4</td>
																			<td>Implementation Method</td>
																			<td id="implementation"></td>
																		</tr>
																		<tr>
																			<td>5</td>
																			<td>Financial Year </td>
																			<td id="projfscyears"></td>
																		</tr>
																		<!--<tr>
																			<td>8</td>
																			<td>Big Four Agenda Category</td>
																			<td id="bigfourA"></td>
																		</tr>-->
																		<tr>
																			<td>6</td>
																			<td>Project Duration </td>
																			<td id="projdurations"></td>
																		</tr>
																		<tr>
																			<td>7</td>
																			<td>Project Budget</td>
																			<td><span>Ksh.</span><span id="projcosts"></span></td>
																		</tr>
																		<tr>
																			<td>8</td>
																			<td>Evaluation Required?</td>
																			<td id="projeval"></td>
																		</tr>
																		<tr>
																			<td>9</td>
																			<td>Mapping Required?</td>
																			<td id="projmap"></td>
																		</tr>
																		<tr>
																			<td>10</td>
																			<td>Inspection Required?</td>
																			<td id="projinsps"></td>
																		</tr>
																	</tbody>
																</table>
															</div>
															<div class="table-responsive">
																<table summary="This table shows how to create responsive tables using Bootstrap's default functionality" class="table table-bordered table-hover">
																	<thead>
																		<tr>
																			<th width="20%"><?= $level1label ?>/s</th>
																			<th width="20%"><?= $level2label ?>/s</th>
																			<th width="20%"><?= $level3label ?>/s</th>
																		</tr>
																	</thead>
																	<tbody>
																		<tr>
																			<td id="projcommunitys"></td>
																			<td id="projlgas"></td>
																			<td id="projstates"></td>
																		</tr>
																	</tbody>
																</table>
															</div>
														</fieldset>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row clearfix " id="">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="card">
											<div class="body">
												<div class="row">
													<div class="col-xs-12">
														<fieldset class="scheduler-border">
															<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">2.0) Ouput Plan Details</legend>
															<fieldset class="scheduler-border">
																<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">2.1) Output Details</legend>
																<div id="outputDataDisp">
																</div>
															</fieldset>
															<fieldset class="scheduler-border">
																<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">2.2) Output Yearly Plan</legend>
																<div id="workplanDetails">
																</div>
															</fieldset>
															<fieldset class="scheduler-border" id="location_sec_div">
																<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">2.3) Output Distribution Per Location</legend>
																<div id="locate_target_plan">
																	<div class="elementDiv"></div>
																</div>
															</fieldset>
														</fieldset>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row clearfix " id="">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="card">
											<div class="body">
												<div class="row">
													<div class="col-xs-12">
														<fieldset class="scheduler-border">
															<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">3.0) Stakeholders Details</legend>
															<div class="table-responsive">
																<table summary="This table shows how to create responsive tables using Bootstrap's default functionality" class="table table-bordered table-hover">
																	<thead>
																		<tr>
																			<th width="5%">#</th>
																			<th width="35%">Field</th>
																			<th width="60%">Value</th>
																		</tr>
																	</thead>
																	<tbody>
																		<tr>
																			<td>1</td>
																			<td>Lead Implementor</td>
																			<td id="leadImple"></td>
																		</tr>
																		<tr>
																			<td>2</td>
																			<td>Implementing Partner</td>
																			<td id="ImplPart"></td>
																		</tr>
																		<tr>
																			<td>3</td>
																			<td>Financier/s</td>
																			<td id="financiers"></td>
																		</tr> 
																	</tbody>
																</table>
															</div>
														</fieldset>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row clearfix " id="">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="card">
											<div class="body">
												<div class="row">
													<div class="col-xs-12">
														<fieldset class="scheduler-border">
															<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">4.0) Files</legend>
															<div class="table-responsive">
																<table summary="This table shows how to create responsive tables using Bootstrap's default functionality" class="table table-bordered table-hover">
																	<thead>
																		<tr>
																			<th width="5%">#</th>
																			<th width="35%">Attachment Purpose</th>
																			<th width="60%">File Name</th>
																		</tr>
																	</thead>
																	<tbody id="files_attached">
																	</tbody>
																</table>
															</div>
														</fieldset>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row clearfix " id="">
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<input type="hidden" name="MM_insert" value="addprojectfrm">
										<input type="hidden" name="username" value="<?= $user_name ?>">
										<button type="button" class="btn btn-warning prev-step">Previous</button>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<!--<input class="btn btn-success" name="report" type="submit" value="Save and Export as PDF !">-->
										<button class="btn btn-success" type="submit">Save! </button>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Start Modal Item Edit -->
<div class="modal fade" id="outputItemModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header" style="background-color:#03A9F4">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" style="color:#fff" align="center" id="modal-title"></h4>
			</div>
			<div class="modal-body" style="max-height:450px; overflow:auto;">
				<div class="card">
					<div class="row clearfix">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="body">
								<div class="div-result">
									<form class="form-horizontal" id="addprojoutput" action="" method="POST">
										<br />
										<div class="col-md-6">
											<label class="control-label">Start Year *:</label>
											<div class="form-line">
												<select name="outputfscyear" id="outputfscyear" onchange="output_year_change()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
													<option value="">.... Select Year from list ....</option>
												</select>
												<span id="outputfscyearmsg" style="color:red"></span>
											</div>
										</div>
										<div class="col-md-6">
											<label for="outputduration" class="control-label">Output Duration (Days) *:</label>
											<span id="outputdurationmsg1" style="color: red"></span>
											<div class="form-input">
												<input type="hidden" name="outputduration1" id="outputduration1">
												<input type="hidden" name="outputStartYear" id="outputStartYear">
												<input type="number" name="outputduration" id="outputduration" onkeyup="onKeyUpDays()" onchange="onKeyUpDays()" placeholder="Enter" class="form-control" required>
												<span id="outputdurationmsg" style="color:red"></span>
											</div>
										</div>
										<div class="col-md-6">
											<label for="outputbudget" class="control-label">Output Budget *:</label>
											<div class="form-input">
												<input type="hidden" name="indicatorid" id="indicatorids">
												<input type="hidden" name="myprogid" id="myprogid" value="<?= $progid ?>">
												<input type="hidden" name="outputids" id="outputids">
												<input type="hidden" name="rowno" id="rowno">
												<input type="number" name="outputbudget" onkeyup="budgetCalculate()" onchange="budgetCalculate()" id="outputbudget" placeholder="Enter" class="form-control" required>
												<span id="outputbudgetmsg" style="color:red"></span>
											</div>
										</div>
										<div class="col-md-6">
											<label for="outputceiling" class="control-label"> Output Budget Ceiling:</label>
											<div class="form-input">
												<input type="hidden" name="outputceilingVal" id="outputceilingVal">
												<input type="text" name="outputceiling" id="outputceiling" placeholder="Output Budget Ceiling" class="form-control" disabled>
											</div>
										</div>
										<div class="col-md-6">
											<label for="outputTarget" class="control-label">Total <span id="unit_measure"></span> *:</label>
											<span id="ceiling_output_target_msg" style="color: red"></span>
											<div class="form-input">
												<input type="hidden" name="label3level" id="label3level" value="<?= $level3label ?>" class="form-control">
												<input type="hidden" name="houtputTarget" id="houtputTarget" class="form-control">
												<input type="hidden" name="ceiling_output_target" id="ceiling_output_target" value="" class="form-control">
												<input type="number" name="outputTarget" id="outputTarget" onkeyup="optarget()" onchange="optarget()" placeholder="Enter Output Target" class="form-control" required="required">
											</div>
										</div>
										<div class="col-md-6">
											<label>Project Location Map Type *:</label>
											<div class="form-line">
												<select name="projwaypoints" class="form-control show-tick" id="projwaypoints" style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="true" required>
													<option value="">.... Select ....</option>
													<?php
													do {
														if ($projwaypoints == $row_rsMapType['id']) {
													?>
															<option value="<?php echo $row_rsMapType['id'] ?>" selected><?php echo $row_rsMapType['type'] ?></option>
														<?php
														} else {
														?>
															<option value="<?php echo $row_rsMapType['id'] ?>"><?php echo $row_rsMapType['type'] ?></option>
													<?php
														}
													} while ($row_rsMapType = $query_rsMapType->fetch());
													?>
												</select>
											</div>
										</div>
										<div class="col-md-12" id="ben_dissegragation">
											<label class="control-label">Output Distribution (<span id="diss_type"></span>)</label>
											<div class="table-responsive">
												<table class="table table-bordered table-striped table-hover" id="diss_state_table" style="width:100%">
													<thead>
														<tr>
															<th width="5%">#</th>
															<th width="60%"><?= $level3label ?></th>
															<th width="25%">Output Share</th>
															<th width="10%">
																<button type="button" name="addplus" id="addplus" onclick="add_row_diss();" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button>
															</th>
														</tr>
													</thead>
													<tbody id="diss_state_body">
														<tr></tr>
														<tr id="remove_diss">
															<td align="center" colspan="5">Add Locations </td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="modal-footer">
											<div class="col-md-12 text-center">
												<input type="hidden" name="addoutput" id="addoutput" value="addoutput">
												<input type="hidden" name="opid" id="opid" value="">
												<input type="hidden" name="key_unique" id="key_unique" value="<?=$key_unique?>">
												<input type="hidden" name="dben_diss" id="dben_diss" value="">
												<input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
												<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
												<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
											</div> 
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- /modal-body -->
		</div>
		<!-- /modal-content -->
	</div>
	<!-- /modal-dailog -->
</div>

<!-- End Item Edit -->
<script src="assets/custom js/add-project.js"></script>