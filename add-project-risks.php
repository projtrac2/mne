<?php
    require('includes/head.php');
    if ($permission) {
        try {
            $decode_projid = (isset($_GET['proj']) && !empty($_GET["proj"])) ? base64_decode($_GET['proj']) : "";
            $projid_array = explode("projrisk047", $decode_projid);
            $projid = $projid_array[1];

            $query_project = $db->prepare("SELECT * FROM tbl_projects WHERE projid = :projid ");
            $query_project->execute(array(":projid" => $projid));
            $row_project = $query_project->fetch();
			$projid = $row_project['projid'];
			$projcode = $row_project['projcode'];
			$projname = $row_project['projname'];
			$projstage = $row_project['projstage'];

            $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.deleted='0' and p.projid=:projid");
            $query_rsProjects->execute(array(":projid" => $projid));
            $row_rsProjects = $query_rsProjects->fetch();
            $totalRows_rsProjects = $query_rsProjects->rowCount();
            $progid = $project = $sectorid = "";
            $project_name = ($totalRows_rsProjects > 0) ? $row_rsProjects['projname'] : "";
            $project_sub_stage = ($totalRows_rsProjects > 0) ? $row_rsProjects['proj_substage'] : "";
            $approve = $project_sub_stage > 1 ? true : false;
        } catch (PDOException $ex) {
            $results = flashMessage("An error occurred: " . $ex->getMessage());
        }
    ?>
        <section class="content">
            <div class="container-fluid">
                <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                    <h4 class="contentheader">
                        <?= $icon ?>
                        <?php echo $pageTitle ?>
                        <div class="btn-group" style="float:right">
                            <?php
                            if (!$approve) {
								?>
                                <a type="button" data-toggle="modal" data-target="#outputItemModal" id="outputItemModalBtnrow" class="btn btn-success" style="margin-right: 10px;">
                                    Add Risk
                                </a>
								<?php
                            }
                            ?>
                            <a type="button" id="outputItemModalBtnrow" onclick="history.back()" class="btn btn-warning pull-right" style="margin-right:10px;">
                                Go Back
                            </a>
                        </div>
                    </h4>
                </div>
                <div class="row clearfix">
                    <div class="block-header">
                        <?= $results; ?>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
							<div class="header">
								<fieldset class="scheduler-border" style="background-color:#edfcf1; border-radius:3px">
								  <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-list-alt" style="color:green" aria-hidden="true"></i> Project Details</legend>
								  <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
									<label class="control-label">Project Code:</label>
									<div class="form-line">
									  <input type="text" class="form-control" value=" <?= $projcode ?>" readonly>
									</div>
								  </div>
								  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
									<label class="control-label">Project Name:</label>
									<div class="form-line">
									  <input type="text" class="form-control" value=" <?= $projname ?>" readonly>
									</div>
								  </div>
								</fieldset>
							</div>
                            <div class="body">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<fieldset class="scheduler-border row setup-content" style="padding:10px">
											<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-exclamation-circle" style="color:#F44336" aria-hidden="true"></i> Project Risks</legend>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="table-responsive">
													<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
														<thead>
															<tr style="background-color:#0b548f; color:#FFF">
																<th style="width:5%" align="center">#</th>
																<th style="width:70%">Risk</th>
																<th style="width:20%">Category</th>
																<th style="width:5%" data-orderable="false">Action</th>
															</tr>
														</thead>
														<tbody>
															<?php	
															$query_proj_risks = $db->prepare("SELECT * FROM tbl_projectrisks r left join tbl_projrisk_categories c on c.catid=r.catid WHERE projid=:projid GROUP BY id");
															$query_proj_risks->execute(array(":projid" => $projid));
															$totalRows_proj_risks = $query_proj_risks->rowCount();
															if ($totalRows_proj_risks > 0) {
																$counter = 0;
																while ($row_proj_risks = $query_proj_risks->fetch()) {
																	$counter++;
																	$rskid = $row_proj_risks['id'];
																	$category = $row_proj_risks['category'];
																	$risk = $row_proj_risks['risk_description'];

																	?>
																	<tr style="background-color:#FFFFFF">
																		<td align="center"><?= $counter ?></td>
																		<td><?= $risk ?></td>
																		<td><?= $category ?></td>
																		<td>
																			<?php
																			if (!$approve) {
																			?>
																				<div class="btn-group">
																					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																						Options <span class="caret"></span>
																					</button>
																					<ul class="dropdown-menu">
																						<li>
																							<a type="button" data-toggle="modal" data-target="#outputItemModal" id="addFormModalBtn" onclick="add_details(<?= $rskid ?>, 2)">
																								<i class="fa fa-pencil-square"></i> Edit Risk
																							</a>
																						</li>
																						<li>
																							<a type="button" data-toggle="modal" data-target="#removeItemModal" id="#removeItemModalBtn" onclick="destroy_task(<?= $rskid ?>)">
																								<i class="fa fa-trash-o"></i> Delete Risk
																							</a>
																						</li>
																					</ul>
																				</div>
																			<?php
																			}
																			?>
																		</td>
																	</tr>
																<?php
																}
															}
															?>
														</tbody>
													</table>
												</div>
											</div>
										</fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>

        <!-- Start Item more -->
        <div class="modal fade" tabindex="-1" role="dialog" id="outputItemModal">
            <div class="modal-dialog  modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info-circle" style="color:orange"></i> Project's Potential Risks</h4>
                    </div>
                    <form class="form-horizontal" id="add_items" action="" method="POST">
                        <div class="modal-body">
                            <fieldset class="scheduler-border" id="milestone_div">
                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Risk Details </legend>
                                <div class="row" id="details">
									<?php	
									$query_risk_categories = $db->prepare("SELECT * FROM tbl_projrisk_categories");
									$query_risk_categories->execute();
									
									$query_risk_likelihood = $db->prepare("SELECT * FROM tbl_risk_probability where active=1");
									$query_risk_likelihood->execute();
									
									$query_risk_impact = $db->prepare("SELECT * FROM tbl_risk_impact where active=1");
									$query_risk_impact->execute();
									
									$query_risk_strategy = $db->prepare("SELECT * FROM tbl_risk_strategy where active=1");
									$query_risk_strategy->execute();
									
									$query_risk_monitoring_frequency = $db->prepare("SELECT * FROM tbl_datacollectionfreq where status=1");
									$query_risk_monitoring_frequency->execute();
									
									$query_risk_responsible = $db->prepare("SELECT *, tt.title AS user_title FROM users u left join tbl_projteam2 t on t.ptid=u.pt_id left join tbl_titles tt on tt.id=t.title where t.disabled=0");
									$query_risk_responsible->execute();
									?>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px">
										<div class="form-inline">
											<label for="">Risk Category</label>
											<select name="riskcategory" class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
												<option value="">.... Select Category ....</option>
												<?php
												while ($row_risk_categories = $query_risk_categories->fetch()) {
													?>
													<font color="black">
														<option value="<?php echo $row_risk_categories['catid'] ?>"><?php echo $row_risk_categories['category'] ?></option>
													</font>
												<?php
												}
												?>
											</select>
										</div>
									</div>
									<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12" style="margin-bottom:10px">
										<div class="form-inline">
											<label for="">Risk Description</label>
											<input name="risk" type="text" class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%" placeholder="Describe the risk" required>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px">
										<div class="form-inline">
											<label for="">Risk Source</label>
											<input name="risk" type="text" class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%" placeholder="Add risk source" required>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px">
										<div class="form-inline">
											<label for="">Risk Likelihood</label>
											<select name="likelihood" id="likelihood" class="form-control require" onchange="riskseverity()" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
												<option value="">.... Select Likelihood ....</option>
												<?php
												while ($row_risk_likelihood = $query_risk_likelihood->fetch()) {
													?>
													<font color="black">
														<option value="<?php echo $row_risk_likelihood['id'] ?>"><?php echo $row_risk_likelihood['description'] ?></option>
													</font>
												<?php
												}
												?>
											</select>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px">
										<div class="form-inline">
											<label for="">Risk Impact</label>
											<select name="impact" id="impact" class="form-control require" onchange="riskseverity()" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
												<option value="">.... Select Impact ....</option>
												<?php
												while ($row_risk_impact = $query_risk_impact->fetch()) {
													?>
													<font color="black">
														<option value="<?php echo $row_risk_impact['id'] ?>"><?php echo $row_risk_impact['description'] ?></option>
													</font>
												<?php
												}
												?>
											</select>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px">
										<div class="form-inline">
											<label for="">Risk Level</label>
											<input name="severity" type="hidden" id="severity" required>
											<div id="severityname" class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px; width:75%">Auto Calculated</div>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px">
										<div class="form-inline">
											<label for="">Risk Strategy</label>
											<select name="strategy" class="form-control require" onchange="riskstrategy()" style="border:#CCC thin solid; border-radius:5px; width:98%" id="strategy" required>
												<option value="">.... Select Strategy ....</option>
												<?php
												while ($row_risk_strategy = $query_risk_strategy->fetch()) {
													?>
													<font color="black">
														<option value="<?php echo $row_risk_strategy['id'] ?>"><?php echo $row_risk_strategy['strategy'] ?></option>
													</font>
												<?php
												}
												?>
											</select>
										</div>
									</div>
									<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12" style="margin-bottom:10px">
										<div class="form-inline">
											<label for="">Risk Strategic Measure</label>
											<input name="strategic_measure" type="text" class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%" placeholder="Add Strategic Measure" required>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px" id="frequency">
										<div class="form-inline">
											<label for="">Risk Monitoring Frequency</label>
											<select name="frequency" class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%">
												<option value="">.... Select Frequency ....</option>
												<?php
												while ($row_risk_monitoring_frequency = $query_risk_monitoring_frequency->fetch()) {
													?>
													<font color="black">
														<option value="<?php echo $row_risk_monitoring_frequency['fqid'] ?>"><?php echo $row_risk_monitoring_frequency['frequency'] ?></option>
													</font>
												<?php
												}
												?>
											</select>
										</div>
									</div> 
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px" id="responsible">
										<div class="form-inline">
											<label for="">Risk Monitoring Responsible</label>
											<select name="responsible" class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%">
												<option value="">.... Select Responsible ....</option>
												<?php
												while ($row_risk_responsible = $query_risk_responsible->fetch()) {
													$fullname = $row_risk_responsible['user_title'].'.'.$row_risk_responsible['fullname'];
													?>
													<font color="black">
														<option value="<?php echo $row_risk_responsible['user_id'] ?>"><?php echo $fullname ?></option>
													</font>
												<?php
												}
												?>
											</select>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px">
										<div class="form-inline">
											<label for="">Risk Budget</label>
											<input name="budget" type="number" class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%" placeholder="Add risk budget">
										</div>
									</div>
                                </div>
                            </fieldset>
                            <fieldset class="scheduler-border risk_checklist_div">
                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Add Monitoring Checklist </legend>
                                <div class="col-md-12" id="projRiskTable">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover" id="tasks_table" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th width="90%">Parameter</th>
                                                    <th width="5%">
                                                        <button type="button" name="addplus" id="addplus_output" onclick="add_row_items();" class="btn btn-success btn-sm addplus_output">
                                                            <span class="glyphicon glyphicon-plus">
                                                            </span>
                                                        </button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="tasks_table_body">
                                                <tr></tr>
                                                <tr id="hideinfo2" align="center">
                                                    <td colspan="5">Add Checklist Parameters!!</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                        <div class="modal-footer">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <input type="hidden" name="store_data" id="store_data" value="designs">
                                <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                <input type="hidden" name="output_id" id="output_id" value="<?= $output_id ?>">
                                <input type="hidden" name="milestone_id" id="milestone_id" value="">
                                <input type="hidden" name="task_id" id="task_id" value="">
                                <input type="hidden" name="mapping_type" id="mapping_type" value="">
                                <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                <input name="submtt" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                                <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                            </div>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <!-- End Item more -->
    <?php
    } else {
        $results =  restriction();
        echo $results;
    }

    require('includes/footer.php');
    ?>

    <script>
        const ajax_url = "ajax/risk/index";
    </script>

    <script src="assets/js/risk/index.js"></script>