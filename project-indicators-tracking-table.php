<?php
require('includes/head.php');
if ($permission) {
	$rows_count = 0;
	try {
		function projfy()
		{
			global $db;
			$projfy = $db->prepare("SELECT * FROM tbl_fiscal_year");
			$projfy->execute();
			while ($row = $projfy->fetch()) {
				echo '<option value="' . $row['id'] . '">' . $row['year'] . '</option>';
			}
		}

		if (isset($_GET["prg"]) && !empty($_GET["prg"])) {
			$progid = $_GET["prg"];
		}



		//get financial years
		$query_rsYear =  $db->prepare("SELECT id, year FROM tbl_fiscal_year");
		$query_rsYear->execute();
		$row_rsYear = $query_rsYear->fetch();
		$totalRows_rsYear = $query_rsYear->rowCount();

		//get subcounty
		$query_rsComm =  $db->prepare("SELECT id, state FROM tbl_state WHERE parent IS NULL ORDER BY state ASC");
		$query_rsComm->execute();
		$row_rsComm = $query_rsComm->fetch();
		$totalRows_rsComm = $query_rsComm->rowCount();

		//get mapping type
		$query_rsMapType =  $db->prepare("SELECT id, type FROM tbl_map_type");
		$query_rsMapType->execute();
		$row_rsMapType = $query_rsMapType->fetch();
		$totalRows_rsMapType = $query_rsMapType->rowCount();

		//get project implementation methods
		$query_rsProjImplMethod =  $db->prepare("SELECT id, method FROM tbl_project_implementation_method");
		$query_rsProjImplMethod->execute();
		$row_rsProjImplMethod = $query_rsProjImplMethod->fetch();
		$totalRows_rsProjImplMethod = $query_rsProjImplMethod->rowCount();

		// get project risks
		$query_rsRiskCategories =  $db->prepare("SELECT catid, category FROM tbl_projrisk_categories");
		$query_rsRiskCategories->execute();
		$row_rsRiskCategories = $query_rsRiskCategories->fetch();
		$totalRows_rsRiskCategories = $query_rsRiskCategories->rowCount();


		$base_url = "";

		if (isset($_GET['btn_search']) and $_GET['btn_search'] == "FILTER") {
			$prjsector = $_GET['sector'];
			$prjdept = $_GET['department'];
			$projfyfrom = $_GET['projfyfrom'];
			$projfyto = $_GET['projfyto'];


			if (!empty($prjsector) && empty($prjdept) && empty($projfyfrom) && empty($projfyto)) {
				$sql = $db->prepare("SELECT * FROM tbl_projects p INNER JOIN tbl_programs g ON g.progid= p.progid WHERE p.projstatus > 9 and g.projsector=:prjsector and p.deleted='0' ORDER BY `projid` ASC");
				$sql->execute(array(":prjsector" => $prjsector));
			} elseif (!empty($prjsector) && !empty($prjdept) && empty($projfyfrom) && empty($projfyto)) {
				$sql = $db->prepare("SELECT * FROM tbl_projects p INNER JOIN tbl_programs g ON g.progid= p.progid WHERE p.projstatus > 9 and g.projsector=:prjsector and g.projdept=:prjdept and p.deleted='0' ORDER BY `projid` ASC");
				$sql->execute(array(":prjsector" => $prjsector, ":prjdept" => $prjdept));
			} elseif (empty($prjsector) && empty($prjdept) && !empty($projfyfrom) && empty($projfyto)) {
				$sql = $db->prepare("SELECT * FROM tbl_projects WHERE projstatus > 9 and projfscyear >= :projfyfrom and deleted='0' ORDER BY `projid` ASC");
				$sql->execute(array(":projfyfrom" => $projfyfrom));
			} elseif (empty($prjsector) && empty($prjdept) && !empty($projfyfrom) && !empty($projfyto)) {
				$sql = $db->prepare("SELECT * FROM tbl_projects WHERE projstatus > 9 and (projfscyear >= :projfyfrom and projfscyear <=:projfyto) and deleted='0' ORDER BY `projid` ASC");
				$sql->execute(array(":projfyfrom" => $projfyfrom, ":projfyto" => $projfyto));
			} elseif (!empty($prjsector) && empty($prjdept) && !empty($projfyfrom) && empty($projfyto)) {
				$sql = $db->prepare("SELECT * FROM tbl_projects p INNER JOIN tbl_programs g ON g.progid= p.progid WHERE p.projstatus > 9 and g.projsector=:prjsector and p.projfscyear >= :projfyfrom and p.deleted='0' ORDER BY `projid` ASC");
				$sql->execute(array(":prjsector" => $prjsector, ":projfyfrom" => $projfyfrom));
			} elseif (!empty($prjsector) && !empty($prjdept) && !empty($projfyfrom) && empty($projfyto)) {
				$sql = $db->prepare("SELECT * FROM tbl_projects p INNER JOIN tbl_programs g ON g.progid= p.progid WHERE p.projstatus > 9 and g.projsector=:prjsector and g.projdept=:prjdept and p.projfscyear >= :projfyfrom and p.deleted='0' ORDER BY `projid` ASC");
				$sql->execute(array(":prjsector" => $prjsector, ":prjdept" => $prjdept, ":projfyfrom" => $projfyfrom));
			} elseif (!empty($prjsector) && empty($prjdept) && !empty($projfyfrom) && !empty($projfyto)) {
				$sql = $db->prepare("SELECT * FROM tbl_projects p INNER JOIN tbl_programs g ON g.progid= p.progid WHERE p.projstatus > 9 and g.projsector=:prjsector and (projfscyear >= :projfyfrom and projfscyear <= :projfyto) and p.deleted='0' ORDER BY `projid` ASC");
				$sql->execute(array(":prjsector" => $prjsector, ":projfyfrom" => $projfyfrom, ":projfyto" => $projfyto));
			} elseif (!empty($prjsector) && !empty($prjdept) && !empty($projfyfrom) && !empty($projfyto)) {
				$sql = $db->prepare("SELECT * FROM tbl_projects p INNER JOIN tbl_programs g ON g.progid= p.progid WHERE p.projstatus > 9 and g.projsector=:prjsector and g.projdept=:prjdept and (projfscyear >= :projfyfrom and projfscyear <=:projfyto) and p.deleted='0' ORDER BY `projid` ASC");
				$sql->execute(array(":prjsector" => $prjsector, ":prjdept" => $prjdept, ":projfyfrom" => $projfyfrom, ":projfyto" => $projfyto));
			} elseif (empty($prjsector) && empty($prjdept) && empty($projfyfrom) && empty($projfyto)) {
				$sql = $db->prepare("SELECT * FROM `tbl_projects` WHERE projstatus > 9 ORDER BY `projid` ASC");
				$sql->execute();
			}

			$base_url = "?sector=$prjsector&department=$prjdept&projfyfrom=$projfyfrom&projfyto=$projfyto&btn_search=FILTER";
		} else {
			$sql = $db->prepare("SELECT * FROM `tbl_projects` WHERE projstage > 9 ORDER BY `projid` ASC");
			$sql->execute();
		}

		$rows_count = $sql->rowCount();

		include_once('system-labels.php');
	} catch (PDOException $ex) {
		// $result = flashMessage("An error occurred: " .$ex->getMessage());
		print($ex->getMessage());
	}
?>
	<style>
		.modal-lg {
			max-width: 100% !important;
			width: 90%;
		}

		#links a {
			color: #FFFFFF;
			text-decoration: none;
		}

		hr {
			display: block;
			margin-top: 0.5em;
			margin-bottom: 0.5em;
			margin-left: auto;
			margin-right: auto;
			border-style: inset;
			border-width: 1px;
		}

		#report tbody tr:not(:first-child):nth-child(odd) {
			display: none;
		}
	</style>
	<!-- start body  -->
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<i class="fa fa-columns" aria-hidden="true"></i>
					<?php echo $pageTitle ?>
					<div class="btn-group" style="float:right">
						<div class="btn-group" style="float:right">
						</div>
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
							<div class="row clearfix" style="margin-top:5px">
								<div class="col-md-12 row">
									<ul class="list-inline pull-right">
										<li>
											<a href="reports/project-indicators-tracking-table-pdf.php<?= $base_url ?>" target="_blank" class="btn btn-danger btn-sm" type="button">
												<i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="body">

						</div>
						<div class="body">
							<div class="row clearfix">
								<form id="searchform" name="searchform" method="get" style="margin-top:10px" action="<?php echo $_SERVER['PHP_SELF']; ?>">
									<div class="col-md-3">
										<select name="sector" id="sector" onchange="sectors()" class="form-control show-tick " style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="false">
											<option value="">Select <?= $ministrylabel ?></option>
											<?php
											$data = '';
											$query_sectors = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent=0");
											$query_sectors->execute(array(":comm" => $comm));
											while ($row = $query_sectors->fetch()) {
												$stid = $row['stid'];
												$sector = $row['sector'];
												$data .= '<option value="' . $stid . '">' . $sector . '</option>';
											}
											echo $data;
											?>
										</select>
									</div>
									<div class="col-md-3">
										<select name="department" id="dept" class="form-control show-tick " style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="false">
											<option value="">No <?= $departmentlabelplural ?></option>
										</select>
									</div>
									<div class="col-md-2">
										<select name="projfyfrom" id="fyfrom" onchange="finyearfrom()" class="form-control show-tick" data-live-search="false" data-live-search-style="startsWith">
											<option value="" selected="selected">Select FY From</option>
											<?php
											projfy();
											?>
										</select>
									</div>
									<div class="col-md-2">
										<select name="projfyto" id="fyto" class="form-control show-tick" data-live-search="false" data-live-search-style="startsWith">
											<option value="" selected="selected">Select FY To</option>
											<?php
											//projfy();
											?>
										</select>
									</div>
									<div class="col-md-2">
										<input type="submit" class="btn btn-primary" name="btn_search" id="btn_search" value="FILTER" />
										<input type="button" VALUE="RESET" class="btn btn-warning" onclick="location.href='project-indicators-tracking-table.php'" id="btnback">
									</div>
								</form>
							</div>
						</div>
						<div class="body">
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover" id="manageItemTable">
									<thead>
										<tr>
											<th width="5%">#</th>
											<th width="38%">Project Name</th>
											<th width="15%"><?= $level2label ?></th>
											<th width="12%">Start/End&nbsp;Date</th>
											<th width="10%">Cost</th>
											<th width="10%">Expenditure</th>
											<th width="10%">Status & Progress</th>
											<!--<th width="8%">Report</th>-->
										</tr>
									</thead>
									<tbody>
										<?php
										try {
											function get_status($status_id)
											{
												global $db;
												$query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :status_id");
												$query_Projstatus->execute(array(":status_id" => $status_id));
												$row_Projstatus = $query_Projstatus->fetch();
												$total_Projstatus = $query_Projstatus->rowCount();
												$status = "";
												if ($total_Projstatus > 0) {
													$status_name = $row_Projstatus['statusname'];
													$status_class = $row_Projstatus['class_name'];
													$status = '<button type="button" class="' . $status_class . '" style="width:100%">' . $status_name . '</button>';
												}
												return $status;
											}



											function get_subtask_status($progress, $start_date, $end_date, $status, $project_status)
											{
												$today = date('Y-m-d');
												if ($project_status == 6) {
													$status_id = 6;
												} else {
													$status_id = $progress > 0 ? 4 : 3;
													if ($today > $start_date) {
														if ($today < $end_date) {
															$status_id = 4;
															if ($progress == 0) {
																$status_id = 11;
															}
														} else if ($today > $end_date) {
															$status_id = 11;
														}
													}
												}

												$status_id = $status == 5 ? 5 : $status_id;
												return	get_status($status_id);
											}



											function get_progress($progress)
											{
												$project_progress = '
                                                    <div class="progress" style="height:20px; font-size:10px; color:black">
                                                        <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
                                                            ' . $progress . '%
                                                        </div>
                                                    </div>';

												if ($progress == 100) {
													$project_progress = '
													<div class="progress" style="height:20px; font-size:10px; color:black">
														<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progress . '%; height:20px; font-size:10px; color:black">
														' . $progress . '%
														</div>
													</div>';
												}
												return $project_progress;
											}

											if ($rows_count > 0) {
												$active = "";
												$sn = 0;
												while ($row = $sql->fetch()) {
													$sn++;
													$itemId = $row['projid'];
													$progid = $row['progid'];
													$progid = $row['projcost'];
													$projname = $row["projname"];
													$projbudget = $row["projcost"];
													$project_cost = number_format($projbudget, 2);
													$projstatus = $row["projstatus"];
													$projstartdate = $row["projstartdate"];
													$projenddate = $row["projenddate"];
													$implementation = $row['projcategory'];
													$projstate = explode(",", $row['projlga']);
													$status = get_status($projstatus);
													$progress = number_format(calculate_project_progress($itemId, $implementation), 2);
													$percent2 = get_progress($progress);

													$queryobj = $db->prepare("SELECT objective FROM `tbl_programs` g inner join tbl_strategic_plan_objectives o on o.id=g.strategic_obj WHERE progid=:progid");
													$queryobj->execute(array(":progid" => $progid));
													$rowobj = $queryobj->fetch();
													$objective = $rowobj ? $rowobj["objective"] : "";

													$query_projectexp = $db->prepare("SELECT SUM(amount) AS exp FROM `tbl_payments_disbursed` WHERE projid=:projid");
													$query_projectexp->execute(array(":projid" => $itemId));
													$row_projectexp = $query_projectexp->fetch();
													$totalRows_projectexp = $query_projectexp->rowCount();
													$project_expense = ($totalRows_projectexp > 0) ? $row_projectexp["exp"] : 0;

													$states = array();
													for ($i = 0; $i < count($projstate); $i++) {
														$state = $db->prepare("SELECT * FROM `tbl_state` WHERE id=:stateid LIMIT 1");
														$state->execute(array(":stateid" => $projstate[$i]));
														$rowstate = $state->fetch();
														$state = $rowstate["state"];
														array_push($states, $state);
													}


													$query_projremarks = $db->prepare("SELECT * FROM `tbl_projects_performance_report_remarks` WHERE projid=:projid LIMIT 1");
													$query_projremarks->execute(array(":projid" => $itemId));
													$totalRows_projremarks = $query_projremarks->rowCount();
										?>
													<tr class="projects">
														<td class="text-center mb-0" id="projects<?= $itemId ?>" data-toggle="collapse" data-target=".project<?= $itemId ?>"><?= $sn ?>
															<button class="btn btn-link " title="Click once to expand and Click once to Collapse!!">
																<i class="fa fa-plus-square" style="font-size:16px"></i>
															</button>
														</td>
														<td><?= $projname ?></td>
														<td><?= implode(", ", $states) ?></td>
														<td><?= $projstartdate . '/' . $projenddate ?></td>
														<td><?= $project_cost ?></td>
														<td><?= number_format($project_expense, 2) ?></td>
														<td><?= get_status($projstatus) . '<br>' . $percent2 ?></td>
													</tr>
													<tr class="collapse project<?= $itemId ?>">
														<td class="bg-grey text-center"></td>
														<td colspan="6"><strong>Project Objective: </strong><?= $objective ?></td>
													</tr>
													<tr class="collapse project<?= $itemId ?>">
														<th class="bg-grey text-center"></th>
														<th colspan="2">Activity</th>
														<th colspan="3">Start and End Dates</th>
														<th>Status & Progress</th>
													</tr>
													<?php
													$nm = 0;
													$queryactivities = $db->prepare("SELECT * FROM `tbl_task` WHERE projid=:projid");
													$queryactivities->execute(array(":projid" => $itemId));
													while ($rowact = $queryactivities->fetch()) {
														$nm++;
														$activity = $rowact["task"];
														$subtask_id = $rowact["tkid"];
														$statusid = $rowact["status"];
														$progress = $rowact["progress"];

														$querytaskstatus = $db->prepare("SELECT MIN(start_date) as start_date, MAX(end_date) as end_date FROM `tbl_program_of_works` WHERE subtask_id=:subtask_id");
														$querytaskstatus->execute(array(":subtask_id" => $subtask_id));
														$rowtaskstatus = $querytaskstatus->fetch();
														$startdate = $rowtaskstatus ? date('d M Y', strtotime($rowtaskstatus["start_date"])) : "";
														$enddate = $rowtaskstatus ? date('d M Y', strtotime($rowtaskstatus["end_date"])) : "";

														$query_Site_score = $db->prepare("SELECT SUM(achieved) as achieved FROM tbl_project_monitoring_checklist_score where  subtask_id=:subtask_id");
														$query_Site_score->execute(array(":subtask_id" => $subtask_id));
														$row_site_score = $query_Site_score->fetch();
														$units_no = ($row_site_score['achieved'] != null) ? $row_site_score['achieved'] : 0;

														$query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(units_no) as units_no FROM tbl_project_direct_cost_plan WHERE subtask_id=:subtask_id");
														$query_rsOther_cost_plan_budget->execute(array(":subtask_id" => $subtask_id));
														$row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
														$target_units = !is_null($row_rsOther_cost_plan_budget['units_no']) ? $row_rsOther_cost_plan_budget['units_no'] : 0;
														$progress = number_format(($units_no / $target_units) * 100);

														$query_rsPlan = $db->prepare("SELECT * FROM tbl_program_of_works WHERE  subtask_id=:subtask_id AND complete=0 ");
														$query_rsPlan->execute(array(':subtask_id' => $subtask_id));
														$totalRows_plan = $query_rsPlan->rowCount();
														$status = $totalRows_plan > 0 ? 0 : 5;
													?>
														<tr class="collapse project<?= $itemId ?>">
															<td class="bg-grey text-center"><?= $sn . '.' . $nm ?></td>
															<td colspan="2"><?= $activity ?></td>
															<td colspan="3"><?= $startdate . ' AND ' . $enddate ?></td>
															<td><?= get_subtask_status($progress, $startdate, $enddate, $status, $projstatus) . '<br>' . get_progress($progress) ?></td>
														</tr>
												<?php
													}
													echo '
														<tr class="collapse project' . $itemId . '">
															<td class="bg-grey text-center"></td><td colspan="6" class="bg-grey">';
													if ($totalRows_projremarks > 0) {
														$row_projremarks = $query_projremarks->fetch();
														echo '<strong>Project Remarks: </strong>' . $row_projremarks["remarks"];
													} else {
														if (in_array("add_remarks", $page_actions)) {
															echo '<button type="button" data-toggle="modal" data-target="#remarksItemModal" id="remarksItemModalBtn"  class="btn btn-success btn-sm" onclick=remarks("' . $itemId . '")> <i class="glyphicon glyphicon-file"></i><strong> Add Project Remarks</strong></button>';
														}
													}
													echo '</td>
													</tr>';
												}
											} else {
												?>
												<tr class="projects">
													<td class="text-center mb-0">
													</td>
													<td colspan="6">No records found</td>
												</tr>
										<?php
											}
										} catch (PDOException $ex) {
											$result = flashMessage("An error occurred: " . $ex->getMessage());
											echo $result;
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
	</section>
	<!-- end body  -->
	<!-- Start Item more -->
	<div class="modal fade" tabindex="-1" role="dialog" id="remarksItemModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-file"></i> Project Remarks</h4>
				</div>
				<div class="modal-body" style="max-height:450px; overflow:auto;">
					<div class="card">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="body">
									<div class="div-result">
										<form class="form-horizontal" id="addProjRemarksForm" action="assets/processor/reports-processor.php" method="POST" autocomplete="off">
											<br>
											<div id="result">
												<div class="col-md-12">
													<label>Comments:</label>
													<div class="form-line">
														<textarea name="remarks" id="remarks" rows="5" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" placeholder="Enter project remarks"></textarea>
													</div>
												</div>
											</div>
											<div class="modal-footer editItemFooter">
												<div class="col-md-12 text-center strat">
													<input type="hidden" name="username" id="username" value="<?= $user_name ?>">
													<input type="hidden" name="projid" id="projid" value="">
													<input type="hidden" name="addremarks" value="addremarks">
													<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
													<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
												</div>
											</div> <!-- /modal-footer -->
										</form> <!-- /.form -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> <!-- /modal-body -->
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<!-- End Item more -->
<?php
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');
?>
<script type="text/javascript">
	function CallRiskAction(id) {
		$.ajax({
			type: 'post',
			url: 'callriskaction.php',
			data: {
				rskid: id
			},
			success: function(data) {
				$('#riskaction').html(data);
				$("#riskModal").modal({
					backdrop: "static"
				});
			}
		});
	}

	$(document).ready(function() {
		$(".account").click(function() {
			var X = $(this).attr('id');
			if (X == 1) {
				$(".submenus").hide();
				$(this).attr('id', '0');
			} else {
				$(".submenus").show();
				$(this).attr('id', '1');
			}
		});
		//Mouseup textarea false
		$(".submenus").mouseup(function() {
			return false
		});
		$(".account").mouseup(function() {
			return false
		});

		//Textarea without editing.
		$(document).mouseup(function() {
			$(".submenus").hide();
			$(".account").attr('id', '');
		});

	});

	function goBack() {
		window.history.back();
	}

	function sectors() {
		var sector = $("#sector").val();
		if (sector != "") {
			$.ajax({
				type: "post",
				url: "assets/processor/reports-processor",
				data: {
					get_dept: sector
				},
				dataType: "html",
				success: function(response) {
					$("#dept").html(response);
				},
			});
		}
	}

	function finyearfrom() {
		var fyfrom = $("#fyfrom").val();
		console.log(fyfrom);
		if (fyfrom != "") {
			$.ajax({
				type: "post",
				url: "assets/processor/reports-processor",
				data: {
					get_fyto: fyfrom
				},
				dataType: "html",
				success: function(response) {
					$("#fyto").html(response);
				},
			});
		}
	}

	// /get info for objectives
	function remarks(itemId = null) {
		if (itemId) {
			$("#projid").val(itemId);
		}
	}

	$(document).ready(function() {
		$("#addProjRemarksForm").on("submit", function(event) {
			event.preventDefault();
			var form_info = $(this).serialize();
			$.ajax({
				type: "POST",
				url: "assets/processor/reports-processor",
				data: form_info,
				dataType: "json",
				success: function(response) {
					// loading remove button
					//$("#addStrategyBtn").button("reset");
					if (response.success == true) {
						swal({
							title: "Succuss!",
							text: response.messages,
							icon: "success",
						});
						$(".modal").each(function() {
							$(this).modal("hide");
						});
						setTimeout(function() {
							window.location.reload(true);
						}, 3000);
					} else {
						swal({
							title: "Data Warning!",
							text: response.messages,
							icon: "error",
						});
						$(".modal").each(function() {
							$(this).modal("hide");
						});
						setTimeout(function() {
							window.location.reload(true);
						}, 3000);
					} // /error
				},
				error: function() {
					swal({
						title: "Form Warning!",
						text: "Form error!!!",
						icon: "error",
					});
					setTimeout(function() {
						window.location.reload(true);
					}, 3000);
					$(".modal").each(function() {
						$(this).modal("hide");
					});
				},
			});
			return false;
		});
	});

	$(".collapse td").click(function(e) {
		e.preventDefault();
		$(this)
			.find("i")
			.toggleClass("fa-plus-square fa-minus-square");
	});

	$(".projects td").click(function(e) {
		e.preventDefault();
		$(this)
			.find("i")
			.toggleClass("fa-plus-square fa-minus-square");
	});
</script>