<?php
include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';
include_once("../../includes/system-labels.php");
require('../../functions/strategicplan.php');

try {
	$valid['success'] = array('success' => false, 'messages' => array());
	if (isset($_POST['more'])) {
		$itemId = $_POST['itemId'];
		$strategic_objectives = get_strategic_objectives_strategy($itemId);
		$role_group = 4;

		if ($role_group == 1) {
			$role_th = '<th width="10%">Action </th> ';
		}
		$input = '<div class="row clearfix">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
				<div class="body table-responsive">
					<table class="table table-bordered table-striped table-hover" id="moreInfo" style="width:100%">
						<thead>
							<tr>
								<th width="3%">#</th>
								<th width="87%">Strategy </th>
								' . $role_th . '
							</tr>
						</thead>
						<tbody>';



		if ($strategic_objectives) {
			$counter = 0;
			foreach ($strategic_objectives as $strategic_objective) {
				$counter++;
				$role_crud = '';
				if ($role_group == 1) {
					$role_crud =
						'
									<td>
										<div class="btn-group">
											<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"  onchange="checkBoxes()"  aria-haspopup="true" aria-expanded="false">
												Options <span class="caret"></span>
											</button>
											<ul class="dropdown-menu">
												<li>
													<a type="button" data-toggle="modal" data-target="#removeStrategyModal" id="removeStrategyModalBtn" onclick="removeStrategy(' . $strategic_objective['id'] . ')">
														<i class="glyphicon glyphicon-trash"></i> Remove
													</a>
												</li>
											</ul>
										</div>
									</td>
									';
				}
				$input .= '
								<tr>
									<td>' . $counter . '</td>
									<td>' . $strategic_objective['strategy'] . ' </td>
									' . $role_crud . '
								</tr>';
			}
		}
		$input .= '
						</tbody>
					</table>
				</div>
			</div>
		</div>
		</div>';
		echo $input;
	}


	if (isset($_POST['moreInfo'])) {
		$itemId = $_POST['itemId'];
		$query_rsObjective = $db->prepare("SELECT o.*, p.starting_year, p.years FROM tbl_strategicplan p INNER JOIN tbl_key_results_area k ON p.id = k.spid inner join tbl_strategic_plan_objectives o on o.kraid=k.id WHERE o.id = :objid");
		$query_rsObjective->execute(array(":objid" => $itemId));
		$row_Objective = $query_rsObjective->fetch();
		$row_Objectivecount = $query_rsObjective->rowCount();

		$kpi = $row_Objective['kpi'];

		$query_rsKPI = $db->prepare("SELECT * FROM tbl_indicator i inner join tbl_measurement_units u on u.id=i.indicator_unit WHERE indid = '$kpi'");
		$query_rsKPI->execute();
		$row_rsKPI = $query_rsKPI->fetch();
		$KPI = $row_rsKPI['indicator_name'];
		$unit = $row_rsKPI['unit'];

		$startyear = $row_Objective['starting_year'];
		$years = $row_Objective['years'];
		$objdesc = $row_Objective['description'];
		$styear  = $startyear;
		$stryear = $startyear;

		$query_strategies = $db->prepare("SELECT * FROM tbl_objective_strategy WHERE objid = :objid");
		$query_strategies->execute(array(":objid" => $itemId));

		$input = '<div class="row clearfix">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
				<div class="header">
					<div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
						<label style="font-size:16px"> Strategic Objective: <span style="color:#673AB7;">' . $row_Objective['objective'] . ' </span></label>
					</div>
				</div>
				<div class="body">
				<div class="row clearfix">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="">
						<label class="control-label">Strategic Objective Description : </label>
						<div class="form-line">
							<input name="objdesc" type="text" class="form-control" style="width:100%; border:#CCC thin solid; border-radius: 5px" value="' . strip_tags($objdesc) . '" readonly>
						</div>
					</div>
					<div class="col-md-12 table-responsive">
						<label class="control-label">Strategic Objective Strategies : </label>
						<table class="table table-bordered table-striped table-hover" id="objtargets" style="width:100%">
							<thead>
								<tr class="bg-grey">
									<th width="5%">#</th>
									<th width="95%">Strategy</th>
								</tr>
							</thead>
							<tbody id="financier_table_body">';
		$nm = 0;
		while ($row_strategies = $query_strategies->fetch()) {
			$nm++;
			$strategy = $row_strategies['strategy'];
			$input .= '<tr><td>' . $nm . '</td><td>' . $strategy . '</td></tr>';
		}
		$input .= '
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>';
		echo $input;
	}

	if (isset($_POST["addstrategy"])) {
		$objid = $_POST['objid'];
		$strategy = $_POST['strategy'];
		$user = $_POST['username'];
		$currentdate = date("Y-m-d");

		$query_rsKra = $db->prepare("SELECT *  FROM tbl_objective_strategy WHERE objid=:objid and strategy=:strategy");
		$query_rsKra->execute(array(":objid" => $objid, ":strategy" => $strategy));
		$row_rsKra = $query_rsKra->fetch();
		$totalRows_rsKra = $query_rsKra->rowCount();

		if ($totalRows_rsKra == 0) {
			$sql = $db->prepare("INSERT INTO tbl_objective_strategy (objid, strategy, created_by, date_created) VALUES(:objid,:strategy,:user, :date)");
			$results = $sql->execute(array(":objid" => $objid, ":strategy" => $strategy, ":user" => $user, ":date" => $currentdate));
			if ($results === TRUE) {
				$valid['success'] = true;
				$valid['messages'] = "Successfully Added";
			} else {
				$valid['success'] = false;
				$valid['messages'] = "Error while adding the record!!";
			}
		} else {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Added";
		}
		echo json_encode($valid);
	}

	if (isset($_POST['edit'])) {
		$itemId = $_POST['itemId'];

		//get indicators
		$query_Indicators = $db->prepare("SELECT * FROM tbl_indicator where active='1' and type=1");
		$query_Indicators->execute();
		$row_indicators = $query_Indicators->fetch();

		// getobjective
		$query_rsObjectives = $db->prepare("SELECT * FROM tbl_strategic_plan_objectives WHERE id = '$itemId'");
		$query_rsObjectives->execute();
		$row_rsObjectives = $query_rsObjectives->fetch();

		$indid = $row_rsObjectives['indicator'];

		//get KPI
		$query_inddetails = $db->prepare("SELECT * FROM tbl_indicator where indid='$indid'");
		$query_inddetails->execute();
		$row_inddetails = $query_inddetails->fetch();
		$indunity = $row_inddetails["unity"];

		//get kra
		$query_item = $db->prepare("SELECT k.* FROM tbl_key_results_area k INNER JOIN tbl_strategic_plan_objectives o ON o.kraid = k.id WHERE o.id = '$itemId'");
		$query_item->execute();
		$row_item = $query_item->fetch();
		$rows_count = $query_item->rowCount();
		$input =  '
			<fieldset class="scheduler-border">
				<div class="col-md-12">
					<label class="control-label">Strategic Objective *:</label>
					<div class="form-line">
						<input name="kraid" type="hidden" id="kraid" value="' . $row_item['id'] . '" />
						<input name="objid" type="hidden" id="objid" value="' . $row_rsObjectives['id'] . '" />
						<input name="objective" type="text" value="' . $row_rsObjectives['objective'] . '" class="form-control" style="width:100%; border:#CCC thin solid; border-radius: 5px"  required>
					</div>
				</div>
				<div class="col-md-12">
					<label class="control-label">Outcome  *:</label>
					<div class="form-line">
						<input name="outcome" type="text" value="' . $row_rsObjectives['outcome'] . '" class="form-control" style="width:100%; border:#CCC thin solid; border-radius: 5px"  required>
					</div>
				</div>
				<div class="col-md-6">
					<label class="control-label">Key Performnce Indicator (KPI) *:</label>
					<div class="form-line">
						<select name="indicator" id="kpiindicator" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
							<option value="">.... Select  Indicator from list ....</option>';
		do {
			if ($row_indicators['indid'] == $row_rsObjectives['indicator']) {
				$input .= '<option value="' . $row_indicators['indid'] . '" selected> ' . $row_indicators['indname'] . '</option>';
			} else {
				$input .= '<option value="' . $row_indicators['indid'] . '"> ' . $row_indicators['indname'] . '</option>';
			}
		} while ($row_indicators = $query_Indicators->fetch());
		$input .= '
						</select>
					</div>
				</div>
				<div class="col-md-3">
					<label class="control-label">KPI Baseline <span id="kpiunty1">(' . $indunity . ')</span> *: </label>
					<div class="form-line"><input name="kpibaseline" id="kpibaseline" type="hidden" value="' . $row_rsObjectives['baseline'] . '"><strong style="color:#673AB7"><div id="kpibaseline2">' . $row_rsObjectives['baseline'] . '</div></strong>
					</div>
				</div>
				<div class="col-md-3">
					<label class="control-label">Outcome Target <span id="kpiunty2">(' . $indunity . ')</span>*: </label>
					<div class="form-line">
						<input name="outcometarget" id="outcometarget" type="number" value="' . $row_rsObjectives['target'] . '" class="form-control" style="width:100%; border:#CCC thin solid; border-radius: 5px" placeholder="Enter Outcome target" required>
					</div>
				</div>
			</fieldset>';
		$input .= '
			<fieldset class="scheduler-border">
				<div class="table-responsive">
					<table class="table table-bordered" id="funding_table">
						<thead>
							<tr>
								<th style="width:98%">Strategic Objectives</th>
								<th style="width:2%">
									<button type="button" name="addplus" onclick="add_row();" title="Add another field" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button>
								</th>
							</tr>
						</thead>
						<tbody>';
							$query_strategy = $db->prepare("SELECT * FROM tbl_objective_strategy WHERE objid = '$itemId'");
							$query_strategy->execute();
							$row_strategy = $query_strategy->fetch();
							$counter = 1;
							do {
								$counter++;
								$strategy = $row_strategy['strategy'];
								$input .= '<tr id = "row' . $counter . '">
									<td>
										<input type="text" name="strategic[]" id="strategic" class="form-control" value="' . $strategy . '" placeholder="Enter the Strategic Objective" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
									</td>
									<td>
										<button type="button" class="btn btn-danger btn-sm"  onclick=delete_row("row' . $counter . '")>
										<span class="glyphicon glyphicon-minus"></span></button>
									</td>
								</tr>';
							} while ($row_strategy = $query_strategy->fetch());
							$input .= '
						</tbody>
					</table>
					<input name="username" type="hidden" id="username" value="<?php echo $user_name; ?>" />
				</div>
			</fieldset>
				<script>
			$(document).ready(function() {
				// get department
				$("#kpiindicator").on("change", function() {
					var kpiindicator = $(this).val();
					if (kpiindicator) {
						$.ajax({
							type: "post",
							url: "add-obj-process.php",
							data: {
								kpiindid: kpiindicator
							},
							dataType: "html",
							success: function(response) {
								$("#kpiunty1").html(response);
								$("#kpiunty2").html(response);
							}
						});
					}
				});

				$("#kpiindicator").on("change", function() {
					var kpiind = $( "#kpiindicator option:selected" ).val();
					var objid = $("#objid").val();
					if (kpiind) {
						$.ajax({
							type: "post",
							url: "add-obj-process.php",
							data: {
								objectiveid: objid,
								kpiindicator2: kpiind
							},
							dataType: "json",
							success: function(response) {
								if(response.basevalue > 0) {
									$("#kpibaseline").val(response.basevalue);
									$("#kpibaseline2").html(response.basevalue);
									$("#outcometarget").val(response.octg);
								} else {
									$("#kpibaseline2").html("<strong>Indicator does not have baseline value</strong>");
									$("#outcometarget").val("");
								}
							}
						});
					} else {
						$("#kpibaseline2").html("<strong>Please select KPI first</strong>");
						$("#outcometarget").val("");
					}
				});
			});
		</script>';
		echo $input;
	}

	if (isset($_POST["edititem"])) {
		$objid = $_POST['objid'];
		$objective = $_POST['objective'];
		$outcome = $_POST['outcome'];
		$indicator = $_POST['indicator'];
		$kpibaseline = $_POST['kpibaseline'];
		$outcometarget = $_POST['outcometarget'];
		$kraid = $_POST['kraid'];
		$current_date = date("Y-m-d");
		$user = $_POST['username'];

		$ObjectivesInsert = $db->prepare("UPDATE tbl_strategic_plan_objectives SET kraid=:kraid, objective=:objective, outcome=:outcome, indicator=:indicator, baseline=:kpibaseline, target=:target, created_by=:user, date_created=:dates WHERE id='$objid'");
		$resultObjectives = $ObjectivesInsert->execute(array(":kraid" => $kraid, ":objective" => $objective, ":outcome" => $outcome, ":indicator" => $indicator, ":kpibaseline" => $kpibaseline, ":target" => $outcometarget, ":user" => $user, ":dates" => $current_date));

		if ($resultObjectives) {
			$deleteQuery = $db->prepare("DELETE FROM tbl_objective_strategy WHERE objid=:objid");
			$results = $deleteQuery->execute(array(':objid' => $objid));
			$spcount = count($_POST["strategic"]);
			for ($m = 0; $m < $spcount; $m++) {
				$strategy = $_POST['strategic'][$m];
				$kraInsert = $db->prepare("INSERT INTO tbl_objective_strategy(objid,strategy,created_by,date_created) VALUES (:objid, :strategy, :user, :dates)");
				$kraInsert->execute(array(":objid" => $objid, ":strategy" => $strategy, ":user" => $user, ":dates" => $current_date));
			}
			if ($results === TRUE) {
				$valid['success'] = true;
				$valid['messages'] = "Successfully Updated";
			} else {
				$valid['success'] = false;
				$valid['messages'] = "Error while updatng the record!!";
			}
		}
		echo json_encode($valid);
	}

	if (isset($_POST["deleteItem"])) {
		$itemid = $_POST['itemId'];
		$deleteQuery = $db->prepare("DELETE FROM `tbl_strategic_plan_objectives` WHERE id=:itemid");
		$results = $deleteQuery->execute(array(':itemid' => $itemid));

		$deleteQuery = $db->prepare("DELETE FROM `tbl_objective_strategy` WHERE objid=:itemid");
		$results = $deleteQuery->execute(array(':itemid' => $itemid));

		if ($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Deleted";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while deletng the record!!";
		}
		echo json_encode($valid);
	}

	if (isset($_POST["deleteStrategy"])) {
		$itemid = $_POST['strategyid'];
		$deleteQuery = $db->prepare("DELETE FROM `tbl_objective_strategy` WHERE id=:itemid");
		$results = $deleteQuery->execute(array(':itemid' => $itemid));
		if ($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Deleted";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while deletng the record!!";
		}
		echo json_encode($valid);
	}

	if(isset($_GET['get_kpi_more_info'])){
		$kpi_id = $_GET['kpi_id'];

		$query_kpi_details = $db->prepare("SELECT indicator_name, objective, data_source, frequency, responsible FROM tbl_kpi k left join tbl_indicator i on i.indid=k.outcome_indicator_id left join tbl_strategic_plan_objectives o on o.id=k.strategic_objective_id left join tbl_datacollectionfreq f on f.fqid=k.data_frequency WHERE k.id=:kpi_id");
		$query_kpi_details->execute(array(":kpi_id"=>$kpi_id));
		$rows_kpi_details = $query_kpi_details->fetch();

		$strategic_objective = $rows_kpi_details["objective"];
		$data_frequency = $rows_kpi_details["frequency"];
		$indicator_name = $rows_kpi_details["indicator_name"];
		$data_source = $rows_kpi_details["data_source"];
		$responsible_id = $rows_kpi_details["responsible"];

		$query_responsible_designation = $db->prepare("SELECT designation FROM tbl_pmdesignation WHERE moid=:responsible_id");
		$query_responsible_designation->execute(array(":responsible_id"=>$responsible_id));
		$rows_responsible_designation = $query_responsible_designation->fetch();
		$responsible_designation = $rows_responsible_designation["designation"];

		$query_responsible = $db->prepare("SELECT tt.title AS title, fullname FROM users u left join tbl_projteam2 t on t.ptid=u.pt_id left join tbl_titles tt on tt.id=t.title WHERE u.userid=:responsible_id");
		$query_responsible->execute(array(":responsible_id"=>$responsible_id));
		$rows_responsible = $query_responsible->fetch();
		$title = $rows_responsible["title"];
		$full_name = $rows_responsible["fullname"];
		$responsible = $title.".".$full_name;

		$query_kpi_initial_baseline = $db->prepare("SELECT initial_baseline FROM tbl_kpi WHERE id=:kpi_id");
		$query_kpi_initial_baseline->execute(array(":kpi_id"=>$kpi_id));
		$rows_kpi_initial_baseline = $query_kpi_initial_baseline->fetch();
		$initial_baseline = $rows_kpi_initial_baseline["initial_baseline"];

		$kpi_details ='
		<input type="hidden" value="0" id="clicked">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
			<div class="form-inline">
				<label for="">Key Performnce Indicator (KPI):</label>
				<div id="severityname" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height: auto; width:98%">'.$indicator_name.'</div>
			</div>
		</div>
		<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
			<div class="form-inline">
				<label for="">Data Source:</label>
				<div id="severityname" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height: auto; width:98%">'.$data_source.'</div>
			</div>
		</div>
		<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
			<div class="form-inline">
				<label for="">Initial Basevalue:</label>
				<div id="severityname" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height: auto; width:98%">'.$initial_baseline.'</div>
			</div>
		</div>
		<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
			<div class="form-inline">
				<label for="">Data Collection Frequency:</label>
				<div id="severityname" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height: auto; width:98%">'.$data_frequency.'</div>
			</div>
		</div>
		<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
			<div class="form-inline">
				<label for="">Responsible:</label>
				<div id="severityname" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height: auto; width:98%">'.$responsible_designation.'</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th style="width:55%">Financial Year</th>
							<th style="width:40%">Target</th>
						</tr>
					</thead>
					<tbody>';
					$query_kpi_targets = $db->prepare("SELECT * FROM tbl_kpi_targets WHERE kpi_id=:kpi_id");
					$query_kpi_targets->execute(array(":kpi_id"=>$kpi_id));

					$achieved = $counter = 0;
					while($rows_kpi_targets = $query_kpi_targets->fetch()){
						$counter++;
						$target_id = $rows_kpi_targets["id"];
						$year = $rows_kpi_targets["year"];
						$end_year = $year + 1;
						$fn_year = $year."/".$end_year;
						$target = $rows_kpi_targets["target"];

						$kpi_details .='
						<tr>
							<td>'. $counter .'</td>
							<td>'. $fn_year .'</td>
							<td>'. $target .'%</td>
						</tr>';
					}
					$kpi_details .='
					<tbody>
				</table>
			</div>
		</div>';

		echo json_encode(["kpi_more_details_body" => $kpi_details]);
	}

	if(isset($_GET['get_kpi_score_details'])){
		$kpi_id = $_GET['kpi_id'];

		$query_kpi_details = $db->prepare("SELECT indicator_name, objective, data_source, initial_baseline, frequency, responsible, weighting FROM tbl_kpi k left join tbl_indicator i on i.indid=k.outcome_indicator_id left join tbl_strategic_plan_objectives o on o.id=k.strategic_objective_id left join tbl_datacollectionfreq f on f.fqid=k.data_frequency WHERE k.id=:kpi_id");
		$query_kpi_details->execute(array(":kpi_id"=>$kpi_id));
		$rows_kpi_details = $query_kpi_details->fetch();

		$strategic_objective = $rows_kpi_details["objective"];
		$data_frequency = $rows_kpi_details["frequency"];
		$indicator_name = $rows_kpi_details["indicator_name"];
		$data_source = $rows_kpi_details["data_source"];
		$initial_baseline = $rows_kpi_details["initial_baseline"];
		$responsible_id = $rows_kpi_details["responsible"];
		$kpi_weighting = $rows_kpi_details["weighting"];

		$query_responsible_designation = $db->prepare("SELECT designation FROM tbl_pmdesignation WHERE moid=:responsible_id");
		$query_responsible_designation->execute(array(":responsible_id"=>$responsible_id));
		$rows_responsible_designation = $query_responsible_designation->fetch();
		$responsible_designation = $rows_responsible_designation["designation"];

		$query_responsible = $db->prepare("SELECT tt.title AS title, fullname FROM users u left join tbl_projteam2 t on t.ptid=u.pt_id left join tbl_titles tt on tt.id=t.title WHERE u.userid=:responsible_id");
		$query_responsible->execute(array(":responsible_id"=>$responsible_id));
		$rows_responsible = $query_responsible->fetch();
		$title = $rows_responsible["title"];
		$full_name = $rows_responsible["fullname"];
		$responsible = $title.".".$full_name;

		$kpi_details ='
		<input type="hidden" value="0" id="clicked">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
			<div class="form-inline">
				<label for="">Key Performnce Indicator (KPI):</label>
				<div id="severityname" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height: auto; width:98%">'.$indicator_name.'</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
			<div class="form-inline">
				<label for="">Initial Basevalue:</label>
				<div id="severityname" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height: auto; width:98%">'.$initial_baseline.'</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
			<div class="form-inline">
				<label for="">Weighting:</label>
				<div id="severityname" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height: auto; width:98%">'.$kpi_weighting.'</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
			<div class="form-inline">
				<label for="">Data Collection Frequency:</label>
				<div id="severityname" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height: auto; width:98%">'.$data_frequency.'</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
					<thead>
						<tr>
							<th style="width:4%">#</th>
							<th style="width:19%">Financial Year</th>
							<th style="width:15%">Target</th>
							<th style="width:15%">Baseline</th>
							<th style="width:15%">Achieved</th>
							<th style="width:17%">Performance</th>
							<th style="width:15%">Record Date</th>
						</tr>
					</thead>
					<tbody>';
					$query_kpi_targets = $db->prepare("SELECT * FROM tbl_kpi_targets WHERE kpi_id=:kpi_id");
					$query_kpi_targets->execute(array(":kpi_id"=>$kpi_id));

					$query_first_kpi_target = $db->prepare("SELECT * FROM tbl_kpi_targets WHERE kpi_id=:kpi_id ORDER BY id ASC Limit 1");
					$query_first_kpi_target->execute(array(":kpi_id"=>$kpi_id));
					$rows_first_kpi_target = $query_first_kpi_target->fetch();
					$first_kpi_target_id = $rows_first_kpi_target["id"];
					$first_kpi_target = $rows_first_kpi_target["target"];

					$achieved = $counter = 0;
					while($rows_kpi_targets = $query_kpi_targets->fetch()){
						$counter++;
						$target_id = $rows_kpi_targets["id"];
						$year = $rows_kpi_targets["year"];
						$end_year = $year + 1;
						$fn_year = $year."/".$end_year;
						$target = $rows_kpi_targets["target"];

						$query_kpi_score = $db->prepare("SELECT score, date_created FROM tbl_kpi_scores WHERE kpi_id=:kpi_id AND year=:year ORDER BY id ASC Limit 1");
						$query_kpi_score->execute(array(":kpi_id"=>$kpi_id, ":year"=>$year));
						$rows_kpi_score = $query_kpi_score->fetch();

						$achieved = $performance = 0;
						$record_date = "N/A";
						if($first_kpi_target_id == $target_id){
							$baseline = $initial_baseline;
						} else {
							$baseline_year = $year - 1;
							$query_kpi_baseline = $db->prepare("SELECT score FROM tbl_kpi_scores WHERE kpi_id=:kpi_id AND year=:year ORDER BY id ASC Limit 1");
							$query_kpi_baseline->execute(array(":kpi_id"=>$kpi_id, ":year"=>$baseline_year));
							$rows_kpi_baseline = $query_kpi_baseline->fetch();
							$baseline = $rows_kpi_baseline["score"];
						}

						if($rows_kpi_score){
							$achieved = $rows_kpi_score["score"];
							$record_date = $rows_kpi_score["date_created"];

							//$achieved = 2 + ($achieved + $counter);
							$performance = number_format((($achieved - $baseline) / $baseline) * 100, 2);
						}

						$query_kpi_target_threshold = $db->prepare("SELECT * FROM tbl_kpi_target_thresholds WHERE kpi_target_id=:target_id");
						$query_kpi_target_threshold->execute(array(":target_id"=>$target_id));
						$rows_target_thresholds = $query_kpi_target_threshold->fetch();
						$threshold_1 = $rows_target_thresholds["threshold_1"];
						$threshold_2 = $rows_target_thresholds["threshold_2"];
						$threshold_3 = $rows_target_thresholds["threshold_3"];
						$threshold_4 = $rows_target_thresholds["threshold_4"];

						$progress_bar_success = "";
						if($performance < $threshold_1){
							$progress_bar_success = "progress-bar-danger";
						} elseif($performance >= $threshold_1 && $performance < $threshold_2){
							$progress_bar_success = "progress-bar-warning";
						} elseif($performance >= $threshold_2 && $performance < $threshold_3){
							$progress_bar_success = "progress-bar-primary";
						} elseif(($performance >= $threshold_3 && $performance <= $threshold_4) || $performance > $threshold_4){
							$progress_bar_success = "progress-bar-success";
						}

						$performance = $performance > 100 ? 100:$performance;
						$performance_progress = number_format(($performance/$target) * 100, 2);

						$kpi_details .='
						<tr>
							<td>'. $counter .'</td>
							<td>'. $fn_year .'</td>
							<td>'. $target .'%</td>
							<td>'. $baseline .'</td>
							<td>'. $achieved .'</td>
							<td>
                                <div class="progress" style="height:20px; font-size:10px; color:black">
                                    <div class="progress-bar '.$progress_bar_success.' progress-bar-striped active" role="progressbar" aria-valuenow="' . $performance_progress . '" aria-valuemin="0" aria-valuemax="'. $target .'" style="width: ' . $performance_progress . '%; height:20px; font-size:10px; color:black">
                                        ' . $performance . '%
									</div>
								</div>
							</td>
							<td>'. $record_date .'</td>
						</tr>';
					}
					$kpi_details .='
					<tbody>
				</table>
			</div>
		</div>';

		echo json_encode(["kpi_more_details_body" => $kpi_details]);
	}


	if (isset($_GET["get_kpi_evaluation_details"])) {
		$kpi_id = $_GET['kpi_id'];

		$query_kpi = $db->prepare("SELECT indicator_name, unit FROM `tbl_kpi` k left join `tbl_indicator` i on i.indid=k.outcome_indicator_id left join `tbl_measurement_units` u on u.id=i.indicator_unit WHERE k.id=:kpi_id");
		$results = $query_kpi->execute(array(':kpi_id' => $kpi_id));

		if ($results === TRUE) {
			$rows_kpi = $query_kpi->fetch();
			$kpi = $rows_kpi["indicator_name"];
			$unit = "Score (".$rows_kpi["unit"].") *:";

			echo json_encode(["kpi" => $kpi, "unit" => $unit]);
		}
	}

	if (isset($_GET["get_indicator_measurement_unit"])) {
		$indid = $_GET['indid'];

		$query_unit = $db->prepare("SELECT unit, indicator_calculation_method FROM `tbl_indicator` i left join `tbl_measurement_units` u on u.id=i.indicator_unit WHERE indid=:indid");
		$results = $query_unit->execute(array(':indid' => $indid));

		if ($results === TRUE) {
			$rows_unit = $query_unit->fetch();
			$unit = $rows_unit["unit"];
			$calculation_method = $rows_unit["indicator_calculation_method"];
			$threshold_placeholder = $calculation_method == 1 ? "#":"%";

			echo json_encode(["unit" => $unit, "threshold_placeholder" => $threshold_placeholder]);
		}
	}


    if (isset($_POST['save_kpi_evaluation'])) {
        $current_date = date("Y-m-d");
        $current_year = date("Y");
        $kpi_id = $_POST['kpi_id'];
        $score = $_POST['kpi_value'];
        $username = $_POST['username'];

		/* if(!empty($riskid) || $riskid !=""){
			$update_risk = $db->prepare("UPDATE tbl_risk_register SET risk_description=:risk, risk_category=:risk_category, updated_by=:user_name, date_updated=:date_entered WHERE id=:riskid");
			$risk_results = $update_risk->execute(array(":risk" => $risk, ':risk_category' => $risk_category, ':user_name' => $username, ':date_entered' => $current_date, ':riskid' => $riskid));

			$success_status = false;
			$message = "Failed to create the risk!!";

			if ($risk_results) {
				$success_status = true;
				$message = "Risk successfully updated!";
			}
		} else { */
		$insert_kpi_score = $db->prepare("INSERT INTO tbl_kpi_scores (kpi_id, score, year, created_by, date_created) VALUES (:kpi_id, :score, :year, :user_name, :date_entered)");
		$results = $insert_kpi_score->execute(array(':kpi_id' => $kpi_id, ":score" => $score, ":year" => $current_year, ':user_name' => $username, ':date_entered' => $current_date));

		$success_status = false;
		$message = "Failed to save the values!!";

		if ($results) {
			$success_status = true;
			$message = "KPI successfully evaluated";
		}
		//}
        echo json_encode(array("success" => $success_status, "message" => $message));
    }
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
