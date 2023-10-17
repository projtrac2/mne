<?php
include '../controller.php';

if (isset($_GET["myprojid"]) && $_GET["evaluation"] == 1) {
	$projid = $_GET["myprojid"];
	$sql = $db->prepare("SELECT * FROM `tbl_project_expected_impact_details` WHERE projid = :projid ORDER BY `id` ASC");
	$sql->execute(array(":projid" => $projid));
	$rows_count = $sql->rowCount();
	$output = array('data' => array());
	if ($rows_count > 0) {
		$datasource = "";
		$sn = 0;
		while ($row = $sql->fetch()) {
			$sn++;
			$itemId = $row['id'];
			$impact = $row['impact'];
			$number_of_evaluations = $row['number_of_evaluations'];
			$evaluation_frequency = $row['evaluation_frequency'];
			// status
			if ($row['data_source'] == 1) {
				$datasource = "Primary";
			} else {
				$datasource = "Secondary";
			}

			$query_frequency = $db->prepare("SELECT * FROM `tbl_datacollectionfreq` WHERE fqid = :fqid");
			$query_frequency->execute(array(":fqid" => $evaluation_frequency));
			$row_frequency = $query_frequency->fetch();

			$evalfreq = $row_frequency["frequency"];

			$button = '<a type="button" data-toggle="modal" id="editItemModalBtn" data-target="#addImpactModal" onclick=\'editItem("impact",' . $itemId . ')\'> <i class="glyphicon glyphicon-edit"></i> Edit</a><a type="button" onclick=\'deleteItem("impact",' . $itemId . ')\' > <i class="glyphicon glyphicon-trash btn-danger" ></i>Delete</a>';

			$output['data'][] = array(
				$sn,
				$impact,
				$datasource,
				$evalfreq,
				$number_of_evaluations,
				$button
			);
		} // /while

	} // if num_rows

	echo json_encode($output);
}

if (isset($_GET["myprojid"]) && $_GET["evaluation"] == 2) {
	$projid = $_GET["myprojid"];
	//var_dump("Project ID: ".$projid);
	$sql = $db->prepare("SELECT * FROM `tbl_project_expected_outcome_details` WHERE projid = :projid ORDER BY `id` ASC");
	$sql->execute(array(":projid" => $projid));
	$rows_count = $sql->rowCount();
	$output = array('data' => array());
	if ($rows_count > 0) {
		// $row = $result->fetch_array();
		$datasource = "";
		$sn = 0;
		while ($row = $sql->fetch()) {
			$sn++;
			$itemId = $row['id'];
			$outcome = $row['outcome'];
			$number_of_evaluations = $row['number_of_evaluations'];
			// status
			if ($row['data_source'] == 1) {
				$datasource = "Primary";
			} else {
				$datasource = "Secondary";
			}

			$evaluation_frequency = $row['evaluation_frequency'];

			$query_frequency = $db->prepare("SELECT * FROM `tbl_datacollectionfreq` WHERE fqid = :fqid");
			$query_frequency->execute(array(":fqid" => $evaluation_frequency));
			$row_frequency = $query_frequency->fetch();

			$evalfreq = $row_frequency["frequency"];

			$button = '<a type="button" data-toggle="modal" id="editItemModalBtn" data-target="#addOutcomeModal" onclick=\'editItem("outcome",' . $itemId . ')\'> <i class="glyphicon glyphicon-edit"></i> Edit</a><a type="button"  onclick=\'deleteItem("outcome",' . $itemId . ')\'> <i class="glyphicon glyphicon-minus btn-danger"></i>Delete</a>';

			$output['data'][] = array(
				$sn,
				$outcome,
				$datasource,
				$evalfreq,
				$number_of_evaluations,
				$button
			);
		} // /while

	} // if num_rows

	echo json_encode($output);
}

function get_designations($designation_id)
{
	global $db;
	$query_rsDesignations = $db->prepare("SELECT * FROM tbl_pmdesignation WHERE active='1'");
	$query_rsDesignations->execute();
	$totalRows_rsDesignations = $query_rsDesignations->rowCount();
	$options = "";
	if ($totalRows_rsDesignations > 0) {
		while ($row_rsDesignations = $query_rsDesignations->fetch()) {
			$moid = $row_rsDesignations['moid'];
			$designation =  $row_rsDesignations['designation'];
			$selected = $moid == $designation_id ? "selected" : "";
			$options .= "<option value='$moid' $selected>$designation</option>";
		}
	}
	return $options;
}


function get_designation($designation_id)
{
	global $db;
	$query_designation = $db->prepare("SELECT * FROM tbl_pmdesignation WHERE moid = :designation_id");
	$query_designation->execute(array(":designation_id" => $designation_id));
	$row_designation = $query_designation->fetch();
	$totalRows_designation = $query_designation->rowCount();
	return $totalRows_designation > 0 ? $row_designation['designation'] : "";
}


if (isset($_GET['get_designations'])) {
	$designation_id = "";
	$designations = get_designations($designation_id);
	echo json_encode(array("success" => true, "designations" => $designations));
}

if (isset($_POST["evalform"])) {
	$body = '';
	if ($_POST["evalform"] == "impact") {
		$impactid = $_POST["edititemId"];

		$query_impact = $db->prepare("SELECT * FROM `tbl_project_expected_impact_details` WHERE id = :impactid");
		$query_impact->execute(array(":impactid" => $impactid));
		$row_impact = $query_impact->fetch();
		$rows_impact = $query_impact->rowCount();

		if ($rows_impact > 0) {
			$projid = $row_impact["projid"];
			$projectimpact = $row_impact["impact"];
			$impactindid = $row_impact["indid"];
			$impactSourceid = $row_impact["data_source"];
			$impact_evaluation_frequency = $row_impact["evaluation_frequency"];
			$impact_evaluation_number = $row_impact["number_of_evaluations"];
			$responsible = $row_impact["responsible"];
			$report_user  = explode(',', $row_impact["report_user"]);
			$reporting_timeline  = explode(',', $row_impact["reporting_timeline"]);
			$user_name = $row_impact["added_by"];

			$selected = "";
			$selected1 = "";
			if ($impactSourceid == 1) {
				$selected = "selected";
			} elseif ($impactSourceid == 2) {
				$selected1 = "selected";
			}

			$selected2 = $impact_evaluation_frequency;

			$query_project = $db->prepare("SELECT * FROM `tbl_projects` p inner join `tbl_programs` g on g.progid=p.progid WHERE projid = :projid");
			$query_project->execute(array(":projid" => $projid));
			$row_project = $query_project->fetch();
			$project = $row_project["projname"];
			$projdept = $row_project["projdept"];

			$query_impact_ind_unit = $db->prepare("SELECT * FROM `tbl_measurement_units` u inner join `tbl_indicator` i on i.indicator_unit=u.id inner join tbl_indicator_calculation_method m on m.id=i.indicator_calculation_method WHERE indid = :indid");
			$query_impact_ind_unit->execute(array(":indid" => $impactindid));
			$row_impact_ind_unit = $query_impact_ind_unit->fetch();
			$indunit = $row_impact_ind_unit["unit"];
			$impact_calc_method = $row_impact_ind_unit["method"];

			//main  evaluation questions
			$query_impactmainevalqstns =  $db->prepare("SELECT * FROM tbl_project_evaluation_questions WHERE projid=:projid AND resultstypeid = :resultstypeid AND resultstype=1 AND questiontype=1");
			$query_impactmainevalqstns->execute(array(":projid" => $projid, ":resultstypeid" => $impactid));
			$row_impactmainevalqstns = $query_impactmainevalqstns->fetch();
			$count_impactmainevalqstns = $query_impactmainevalqstns->rowCount();

			//Other  evaluation questions
			$query_impactotherevalqstns =  $db->prepare("SELECT * FROM tbl_project_evaluation_questions WHERE projid=:projid AND resultstypeid = :resultstypeid AND questiontype=2");
			$query_impactotherevalqstns->execute(array(":projid" => $projid, ":resultstypeid" => $impactid));
			$row_impactotherevalqstns = $query_impactotherevalqstns->fetch();
			$count_impactotherevalqstns = $query_impactotherevalqstns->rowCount();

			$body = '
				<input type="hidden" id="impactSourceid" value="' . $impactSourceid . '">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label" style="color:#0b548f; font-size:16px">Project: <u>' . $project . '</u></label>
				</div>
				<br />
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label for="impact" class="control-label">Impact *:</label>
					<div class="form-line">
						<input type="text" name="impact" id="impact" placeholder="Enter the impact" value="' . $projectimpact . '" class="form-control" required>
					</div>
				</div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label for="impactName" class="control-label">Indicator *:</label>
					<div class="form-input">
						<select name="impactIndicator" id="impactIndicator" onchange="get_impact_details()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
							<option value="">.... Select from list ....</option>';
			$query_impactIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Impact' AND indicator_type=2 AND active = '1' AND indicator_dept = '$projdept' ORDER BY indid");
			$query_impactIndicators->execute();
			$row_impactIndicators = $query_impactIndicators->fetch();
			$totalRows_impactIndicators = $query_impactIndicators->rowCount();

			do {
				$indId = $row_impactIndicators['indid'];
				$selectedind = $impactindid == $indId ? ' selected="selected"' : '';

				$body .= '<option value="' . $indId . '" ' . $selectedind . '>' . $row_impactIndicators['indicator_name'] . '</option>';
			} while ($row_impactIndicators = $query_impactIndicators->fetch());

			$body .= '</select>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<label class="control-label">Unit of Measure <span id="impunit"></span>*:</label>
					<div class="form-input">
						<input type="text" name="impactunitofmeasure" value="' . $indunit . '" readonly id="impactunitofmeasure" class="form-control" placeholder="Select change" required="required">
					</div>
				</div>

				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<label for="impact_calc_method" class="control-label">Calculation Method *:</label>
					<div class="form-input">
						<input type="text" name="impact_calc_method" value="' . $impact_calc_method . '" readonly id="impact_calc_method" class="form-control" placeholder="First select change to be measured" required="required">
					</div>
				</div>
				<br />
				<br />
				<br />
				<br />
				<br />
				<br />
				<br />
				<br />
				<br />
				<h4><u>Data collection Plan:</u></h4>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<label for="impactData" class="control-label">Source of data *:</label>
					<div class="form-input">
						<select name="impactdataSource" id="impactdataSource" onchange="add_impact_questions()" class="form-control" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
							<option value="">.... Select from list ....</option>
							<option value="1" ' . $selected . '>Survey</option>
							<option value="2" ' . $selected1 . '>Records</option>
						</select>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<label for="impactData" class="control-label">Evaluation Frequency*:</label>
					<div class="form-input">
						<select data-id="0" name="impact_frequency" id="impact_frequency" class="form-control  selected_impact_frequency" required="required">';
			$query_frequency =  $db->prepare("SELECT * FROM tbl_datacollectionfreq where status=1");
			$query_frequency->execute();
			$totalRows_frequency = $query_frequency->rowCount();

			$input = '<option value="">... Select from list ...</option>';
			if ($totalRows_frequency > 0) {
				while ($row_frequency = $query_frequency->fetch()) {
					$selectedfq = $row_frequency['fqid'] ==  $selected2 ? ' selected="selected"' : '';
					$input .= '<option value="' . $row_frequency['fqid'] . '" ' . $selectedfq . '>' . $row_frequency['frequency'] . ' </option>';
				}
			} else {
				$input .= '<option value="">No defined frequency</option>';
			}
			$body .= $input . '</select>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					<label class="control-label">Number of Endline Evaluations *:</label>
					<div class="form-line">
						<input type="hidden" name="evaluationNumber" id="evaluationNumber">
						<input type="number" name="evaluationNumberFreq" id="evaluationNumberFreq" onchange="outcome_Evaluation()" onkeyup="outcome_Evaluation()" class="form-control" placeholder="Enter Number of endline evaluations" value="' . $impact_evaluation_number . '" required="required">
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 impactquestions">
					<label class="control-label">Main Question</label>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover" id="impact_table" style="width:100%">
							<thead>
								<tr>
									<th width="55%">Question</th>
									<th width="15%">Answer Type</th>
									<th width="30%">Answer Labels</th>
								</tr>
							</thead>
							<tbody>';
			$impactmainquestion = $impactmainanswertype1 = $impactmainanswertype2 = $impactmainlabel = "";
			if ($count_impactmainevalqstns > 0) {
				$impactmainquestion = $row_impactmainevalqstns['question'];
				$impactmainquestionid = $row_impactmainevalqstns['id'];
				$impactmainanswertype = $row_impactmainevalqstns['answertype'];
				$impactmainlabel = $row_impactmainevalqstns['answerlabels'];
				$impactmainanswertype1 = $impactmainanswertype == 1 ? ' selected="selected"' : '';
				$impactmainanswertype2 = $impactmainanswertype == 2 ? ' selected="selected"' : '';
			}
			$body .= '<tr>
									<td>
										<input type="text" name="impactmainquestion" id="impactmainquestion" value="' . $impactmainquestion . '" placeholder="Enter main impact evaluation question" class="form-control querry"/>
									</td>
									<td>
										<select data-id="0" name="impactmainanswertype" id="impactmainanswertype" class="form-control querry">';
			$impactmaininput = '<option value="">... Select ...</option>';
			$impactmaininput .= '<option value="1" ' . $impactmainanswertype1 . '>Number</option>';
			$impactmaininput .= '<option value="2" ' . $impactmainanswertype2 . '>Mutiple Choice</option>';

			$body .= $impactmaininput . '
										</select>
									</td>
									<td>
										<input type="text" name="impact_main_answer_labels" value="' . $impactmainlabel . '" placeholder="Enter comma seperated labels" class="form-control querry"/>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 impactquestions">
					<label class="control-label">Other Question/s</label>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover" id="impact_table" style="width:100%">
							<thead>
								<tr>
									<th width="5%">#</th>
									<th width="50%">Question</th>
									<th width="15%">Answer Type</th>
									<th width="25%">Answer Labels</th>
									<th width="5%">
										<button type="button" name="addplus" id="addplus" onclick="add_row_impact_question();" class="btn btn-success btn-sm">
											<span class="glyphicon glyphicon-plus"></span>
										</button>
									</th>
								</tr>
							</thead>
							<tbody id="impact_questions_table_body">';
			$iprowno = 0;
			if ($count_impactotherevalqstns > 0) {
				do {
					$impactquestion = $row_impactotherevalqstns['question'];
					$impactanswertype = $row_impactotherevalqstns['answertype'];
					$impactotherlabel = $row_impactotherevalqstns['answerlabels'];
					$impactquestionid = $row_impactotherevalqstns['id'];
					$iprowno++;

					$body .= '<tr id="impactquestionrow' . $iprowno . '">
											<td>' . $iprowno . '</td>
											<td>
												<input type="text" name="impactquestions[]" id="impactquestions' . $iprowno . '" value="' . $impactquestion . '" placeholder="Enter any other impact evaluation question" class="form-control impactquerry"/>
											</td>
											<td>
												<select data-id="0" name="impactanswertype[]" id="impactanswertype' . $iprowno . '" class="form-control impactquerry">';

					$impactselected1 = $impactanswertype ==  1 ? ' selected="selected"' : '';
					$impactselected2 = $impactanswertype ==  2 ? ' selected="selected"' : '';
					$impactselected3 = $impactanswertype ==  3 ? ' selected="selected"' : '';
					$impactselected4 = $impactanswertype ==  4 ? ' selected="selected"' : '';
					$impactselected5 = $impactanswertype ==  5 ? ' selected="selected"' : '';
					$impactselected6 = $impactanswertype ==  6 ? ' selected="selected"' : '';
					$impactinput = '<option value="">... Select ...</option>';
					$impactinput .= '<option value="1" ' . $impactselected1 . '>Number</option>';
					$impactinput .= '<option value="2" ' . $impactselected2 . '>Mutiple Choice</option>';
					$impactinput .= '<option value="3" ' . $impactselected3 . '>Checkboxes';
					$impactinput .= '<option value="4" ' . $impactselected4 . '>Dropdown</option>';
					$impactinput .= '<option value="5" ' . $impactselected5 . '>Text</option>';
					$impactinput .= '<option value="6" ' . $impactselected6 . '>File Upload</option>';

					$body .= $impactinput . '
												</select>
											</td>
											<td>
												<input type="text" name="impact_other_answer_label[]" id="impact_other_answer_label' . $iprowno . '" value="' . $impactotherlabel . '" placeholder="Enter comma seperated labels" class="form-control querry"/>
											</td>
											<td>';
					if ($iprowno != 1) {
						$body .= '<button type="button" class="btn btn-danger btn-sm" id="impactdelete" onclick=\'delete_row_impact_question("impactquestionrow' . $iprowno . '")\'>
														<span class="glyphicon glyphicon-minus"></span>
													</button>';
					}
					$body .= '</td>
										</tr>';
				} while ($row_impactotherevalqstns = $query_impactotherevalqstns->fetch());
			} else {
				$iprowno++;
				$body .= '<tr id="impactquestionrow90">
										<td> 1 </td>
										<td>
											<input type="text" name="impactquestions[]" id="impactquestions90" value="" placeholder="Enter any other impact evaluation questions" class="form-control impactquerry"/>
										</td>
										<td>
											<select data-id="0" name="impactanswertype[]" id="impactanswertype' . $iprowno . '" class="form-control impactquerry">';
				$impactinput = '<option value="">... Select ...</option>';
				$impactinput .= '<option value="1">Number</option>';
				$impactinput .= '<option value="2">Mutiple Choice</option>';
				$impactinput .= '<option value="3">Checkboxes';
				$impactinput .= '<option value="4">Dropdown</option>';
				$impactinput .= '<option value="5">Text</option>';
				$impactinput .= '<option value="6">File Upload</option>';

				$body .=  $impactinput . '
											</select>
										</td>
										<td>
											<input type="text" name="impact_other_answer_label[]" id="impact_other_answer_label' . $iprowno . '" placeholder="Enter comma seperated labels" class="form-control querry"/>
										</td>
										<td>
										</td>
									</tr>';
			}
			$body .= '</tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
						<input type="hidden" name="editimpact" id="editimpact" value="editimpact">
						<input type="hidden" name="projid" id="projid" value="' . $projid . '" />
						<input type="hidden" name="user_name" id="user_name" value="' . $user_name . '">
						<input type="hidden" name="impactid" id="impactid" value="' . $impactid . '">
						<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Update" />
						<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
					</div>
				</div>';
		}
	} else {
		$outcomeid = $_POST["edititemId"];

		$query_outcome = $db->prepare("SELECT * FROM `tbl_project_expected_outcome_details` WHERE id = :outcomeid");
		$query_outcome->execute(array(":outcomeid" => $outcomeid));
		$row_outcome = $query_outcome->fetch();
		$rows_outcome = $query_outcome->rowCount();

		if ($rows_outcome > 0) {
			$projid = $row_outcome["projid"];
			$projectoutcome = $row_outcome["outcome"];
			$outcomeindid = $row_outcome["indid"];
			$outcomeSourceid = $row_outcome["data_source"];
			$outcome_evaluation_frequency = $row_outcome["evaluation_frequency"];
			$outcome_evaluation_number = $row_outcome["number_of_evaluations"];
			$responsible = $row_outcome["responsible"];
			$report_user  = explode(',', $row_outcome["report_user"]);
			$reporting_timeline  = explode(',', $row_outcome["reporting_timeline"]);
			$user_name = $row_outcome["added_by"];

			$query_project_impact = $db->prepare("SELECT * FROM `tbl_project_expected_impact_details` WHERE projid = :projid");
			$query_project_impact->execute(array(":projid" => $projid));
			$count_rows_project_impact = $query_project_impact->rowCount();

			$selected = "";
			$selected1 = "";
			if ($outcomeSourceid == 1) {
				$selected = "selected";
			} elseif ($outcomeSourceid == 2) {
				$selected1 = "selected";
			}

			$selected2 = $outcome_evaluation_frequency;

			$query_project = $db->prepare("SELECT * FROM `tbl_projects` p inner join `tbl_programs` g on g.progid=p.progid WHERE projid = :projid");
			$query_project->execute(array(":projid" => $projid));
			$row_project = $query_project->fetch();
			$project = $row_project["projname"];
			$projdept = $row_project["projdept"];

			$query_outcome_ind_unit = $db->prepare("SELECT * FROM `tbl_measurement_units` u inner join `tbl_indicator` i on i.indicator_unit=u.id inner join tbl_indicator_calculation_method m on m.id=i.indicator_calculation_method WHERE indid = :indid");
			$query_outcome_ind_unit->execute(array(":indid" => $outcomeindid));
			$row_outcome_ind_unit = $query_outcome_ind_unit->fetch();
			$indunit = $row_outcome_ind_unit["unit"];
			$outcome_calc_method = $row_outcome_ind_unit["method"];

			//main  evaluation questions
			$query_outcomemainevalqstns =  $db->prepare("SELECT * FROM tbl_project_evaluation_questions WHERE projid=:projid AND resultstypeid = :resultstypeid AND resultstype=2 AND questiontype=1");
			$query_outcomemainevalqstns->execute(array(":projid" => $projid, ":resultstypeid" => $outcomeid));
			$row_outcomemainevalqstns = $query_outcomemainevalqstns->fetch();
			$count_outcomemainevalqstns = $query_outcomemainevalqstns->rowCount();

			//Other  evaluation questions
			$query_outcomeotherevalqstns =  $db->prepare("SELECT * FROM tbl_project_evaluation_questions WHERE projid=:projid AND resultstypeid = :resultstypeid AND resultstype=2 AND questiontype=2");
			$query_outcomeotherevalqstns->execute(array(":projid" => $projid, ":resultstypeid" => $outcomeid));
			$row_outcomeotherevalqstns = $query_outcomeotherevalqstns->fetch();
			$count_outcomeotherevalqstns = $query_outcomeotherevalqstns->rowCount();


			//selected parent impact
			$query_outcome_parent_impact = $db->prepare("SELECT * FROM tbl_project_expected_outcome_details WHERE projid=:projid AND id = :outcomeid");
			$query_outcome_parent_impact->execute(array(":projid" => $projid, ":outcomeid" => $outcomeid));
			$row_outcome_parent_impact = $query_outcome_parent_impact->fetch();
			$outcome_parent_impact = $row_outcome_parent_impact["impactid"];

			$body = '
				<input type="hidden" id="outcomeSourceid" value="' . $outcomeSourceid . '">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label" style="color:#0b548f; font-size:16px">Project: <u>' . $project . '</u></label>
				</div>
				<br />
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label for="outcome" class="control-label">Outcome *:</label>
					<div class="form-line">
						<input type="text" name="outcome" id="outcome" placeholder="Enter the outcome" value="' . $projectoutcome . '" class="form-control" required>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label for="outcomeName" class="control-label">Indicator *:</label>
					<div class="form-input">
						<select name="outcomeIndicator" id="outcomeIndicator" onchange="get_outcome_details()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
							<option value="">.... Select from list ....</option>';
			$query_outcomeIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Outcome' AND indicator_type=2 AND active = '1' AND indicator_dept = '$projdept' ORDER BY indid");
			$query_outcomeIndicators->execute();
			$totalRows_outcomeIndicators = $query_outcomeIndicators->rowCount();

			if ($totalRows_outcomeIndicators > 0) {
				while ($row_outcomeIndicators = $query_outcomeIndicators->fetch()) {
					$indId = $row_outcomeIndicators['indid'];
					$selectedind = $outcomeindid == $indId ? ' selected="selected"' : '';

					$body .= '<option value="' . $indId . '" ' . $selectedind . '>' . $row_outcomeIndicators['indicator_name'] . '</option>';
				}
			}

			$body .= '</select>
					</div>
				</div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<label class="control-label">Unit of Measure <span id="impunit"></span>*:</label>
					<div class="form-input">
						<input type="text" name="outcomeunitofmeasure" value="' . $indunit . '" readonly id="outcomeunitofmeasure" class="form-control" placeholder="Select change" required="required">
					</div>
				</div>

				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<label for="outcome_calc_method" class="control-label">Calculation Method *:</label>
					<div class="form-input">
						<input type="text" name="outcome_calc_method" value="' . $outcome_calc_method . '" readonly id="outcome_calc_method" class="form-control" placeholder="First select change to be measured" required="required">
					</div>
				</div>';
			if ($count_rows_project_impact > 0) {
				$body .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label class="control-label">Parent Impact</label>
						<div class="form-line">
							<select name="parentimpact" id="parentimpact" onclick="get_parent_impacts()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
								<option value="">.... Select from list ....</option>';
				$query_parent_impact = $db->prepare("SELECT * FROM tbl_project_expected_impact_details WHERE projid='$projid' ORDER BY id");
				$query_parent_impact->execute();
				$totalRows_parent_impact = $query_parent_impact->rowCount();

				if ($totalRows_parent_impact > 0) {
					while ($row_parent_impact = $query_parent_impact->fetch()) {
						$parentimpactId = $row_parent_impact['id'];
						$selectedimpact = $outcome_parent_impact == $parentimpactId ? ' selected="selected"' : '';

						$body .= '<option value="' . $parentimpactId . '" ' . $selectedimpact . '>' . $row_parent_impact['impact'] . '</option>';
					}
				}

				$body .= '</select>
						</div>
					</div>';
			}
			$body .= '<br />
				<br />
				<br />
				<br />
				<br />
				<br />
				<br />
				<br />
				<br />
				<h4><u>Data collection Plan:</u></h4>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<label for="outcomeData" class="control-label">Source of data *:</label>
					<div class="form-input">
						<select name="outcomedataSource" id="outcomedataSource" onchange="add_outcome_questions()" class="form-control" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
							<option value="">.... Select from list ....</option>
							<option value="1" ' . $selected . '>Survey</option>
							<option value="2" ' . $selected1 . '>Records</option>
						</select>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<label for="outcomeData" class="control-label">Evaluation Frequency*:</label>
					<div class="form-input">
						<select data-id="0" name="outcome_frequency" id="outcome_frequency" class="form-control  selected_outcome_frequency" required="required">';
			$query_frequency =  $db->prepare("SELECT * FROM tbl_datacollectionfreq where status=1");
			$query_frequency->execute();
			$totalRows_frequency = $query_frequency->rowCount();

			$input = '<option value="">... Select from list ...</option>';
			if ($totalRows_frequency > 0) {
				while ($row_frequency = $query_frequency->fetch()) {
					$selectedfq = $row_frequency['fqid'] ==  $selected2 ? ' selected="selected"' : '';
					$input .= '<option value="' . $row_frequency['fqid'] . '" ' . $selectedfq . '>' . $row_frequency['frequency'] . ' </option>';
				}
			} else {
				$input .= '<option value="">No defined frequency</option>';
			}
			$body .= $input . '</select>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					<label class="control-label">Number of Endline Evaluations *:</label>
					<div class="form-line">
						<input type="hidden" name="evaluationNumber" id="evaluationNumber">
						<input type="number" name="evaluationNumberFreq" id="evaluationNumberFreq" onchange="outcome_Evaluation()" onkeyup="outcome_Evaluation()" class="form-control" placeholder="Enter Number of endline evaluations" value="' . $outcome_evaluation_number . '" required="required">
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 outcomequestions">
					<label class="control-label">Main Question</label>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover" id="outcome_table" style="width:100%">
							<thead>
								<tr>
									<th width="55%">Question</th>
									<th width="15%">Answer Type</th>
									<th width="30%">Answer Labels</th>
								</tr>
							</thead>
							<tbody>';
			$outcomemainlabel = $outcomemainanswertype2 = $outcomemainanswertype1 = $outcomemainquestion = "";
			if ($count_outcomemainevalqstns > 0) {
				$outcomemainquestion = $row_outcomemainevalqstns['question'];
				$outcomemainquestionid = $row_outcomemainevalqstns['id'];
				$outcomemainanswertype = $row_outcomemainevalqstns['answertype'];
				$outcomemainlabel = $row_outcomemainevalqstns['answerlabels'];
				$outcomemainanswertype1 = $outcomemainanswertype == 1 ? ' selected="selected"' : '';
				$outcomemainanswertype2 = $outcomemainanswertype == 2 ? ' selected="selected"' : '';
			}
			$body .= '<tr>
									<td>
										<input type="text" name="outcomemainquestion" id="outcomemainquestion" value="' . $outcomemainquestion . '" placeholder="Enter main outcome evaluation question" class="form-control querry"/>
									</td>
									<td>
										<select data-id="0" name="outcomemainanswertype" id="outcomemainanswertype" class="form-control querry">';
			$outcomemaininput = '<option value="">... Select ...</option>';
			$outcomemaininput .= '<option value="1" ' . $outcomemainanswertype1 . '>Number</option>';
			$outcomemaininput .= '<option value="2" ' . $outcomemainanswertype2 . '>Mutiple Choice</option>';

			$body .= $outcomemaininput . '
										</select>
									</td>
									<td>
										<input type="text" name="outcome_main_answer_labels" value="' . $outcomemainlabel . '" placeholder="Enter comma seperated labels" class="form-control querry"/>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 outcomequestions">
					<label class="control-label">Other Question/s</label>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover" id="outcome_table" style="width:100%">
							<thead>
								<tr>
									<th width="5%">#</th>
									<th width="50%">Question</th>
									<th width="15%">Answer Type</th>
									<th width="25%">Answer Labels</th>
									<th width="5%">
										<button type="button" name="addplus" id="addplus" onclick="add_row_question();" class="btn btn-success btn-sm">
											<span class="glyphicon glyphicon-plus"></span>
										</button>
									</th>
								</tr>
							</thead>
							<tbody id="questions_table_body">';

			$ocrowno = 0;
			if ($count_outcomeotherevalqstns > 0) {
				do {
					$outcomequestion = $row_outcomeotherevalqstns['question'];
					$outcomeanswertype = $row_outcomeotherevalqstns['answertype'];
					$outcomequestionid = $row_outcomeotherevalqstns['id'];
					$ocrowno++;

					$body .= '<tr id="outcomequestionrow' . $ocrowno . '">
											<td>' . $ocrowno . '</td>
											<td>
												<input type="text" name="outcomeotherquestions[]" id="outcomequestions' . $ocrowno . '" value="' . $outcomequestion . '" placeholder="Enter any other outcome evaluation question" class="form-control outcomequerry" required />
											</td>
											<td>
												<select data-id="0" name="outcomeotheranswertype[]" id="outcomeanswertype' . $ocrowno . '" class="form-control outcomequerry" required="required">';

					$outcomeselected1 = $outcomeanswertype ==  1 ? ' selected="selected"' : '';
					$outcomeselected2 = $outcomeanswertype ==  2 ? ' selected="selected"' : '';
					$outcomeselected3 = $outcomeanswertype ==  3 ? ' selected="selected"' : '';
					$outcomeselected4 = $outcomeanswertype ==  4 ? ' selected="selected"' : '';
					$outcomeselected5 = $outcomeanswertype ==  5 ? ' selected="selected"' : '';
					$outcomeselected6 = $outcomeanswertype ==  6 ? ' selected="selected"' : '';

					$outcomeinput = '<option value="">... Select ...</option>';
					$outcomeinput .= '<option value="1" ' . $outcomeselected1 . '>Number</option>';
					$outcomeinput .= '<option value="2" ' . $outcomeselected2 . '>Mutiple Choice</option>';
					$outcomeinput .= '<option value="3" ' . $outcomeselected3 . '>Checkboxes';
					$outcomeinput .= '<option value="4" ' . $outcomeselected4 . '>Dropdown</option>';
					$outcomeinput .= '<option value="5" ' . $outcomeselected5 . '>Text</option>';
					$outcomeinput .= '<option value="6" ' . $outcomeselected6 . '>File Upload</option>';

					$body .= $outcomeinput . '
												</select>
											</td>
											<td>
												<input type="text" name="outcome_other_answer_label[]" id="outcome_other_answer_label' . $ocrowno . '" placeholder="Enter comma seperated labels" class="form-control querry"/>
											</td>
											<td>';
					if ($ocrowno != 1) {

						$body .= '<button type="button" class="btn btn-danger btn-sm" id="outcomedelete" onclick=\'delete_row_outcome_question("outcomequestionrow' . $ocrowno . '")\'>
														<span class="glyphicon glyphicon-minus"></span>
													</button>';
					}
					$body .= '</td>
										</tr>';
				} while ($row_outcomeotherevalqstns = $query_outcomeotherevalqstns->fetch());
			} else {
				$ocrowno++;
				$body .= '<tr id="outcomequestionrow90">
										<td> 1 </td>
										<td>
											<input type="text" name="outcomeotherquestions[]" id="outcomequestions90" value="" placeholder="Enter any other outcome evaluation questions" class="form-control outcomequerry" required />
										</td>
										<td>
											<select data-id="0" name="outcomeotheranswertype[]" id="outcomeanswertype' . $ocrowno . '" class="form-control outcomequerry" required >';

				$outcomeinput = '<option value="">... Select ...</option>';
				$outcomeinput .= '<option value="1">Number</option>';
				$outcomeinput .= '<option value="2">Mutiple Choice</option>';
				$outcomeinput .= '<option value="3">Checkboxes';
				$outcomeinput .= '<option value="4">Dropdown</option>';
				$outcomeinput .= '<option value="5">Text</option>';
				$outcomeinput .= '<option value="6">File Upload</option>';

				$body .= $outcomeinput . '
											</select>
										</td>
										<td>
											<input type="text" name="outcome_other_answer_label[]" id="outcome_other_answer_label' . $ocrowno . '" placeholder="Enter comma seperated labels" class="form-control querry"/>
										</td>
										<td>

										</td>
									</tr>';
			}
			$body .= '</tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
						<input type="hidden" name="editoutcome" id="editoutcome" value="editoutcome">
						<input type="hidden" name="projid" id="projid" value="' . $projid . '" />
						<input type="hidden" name="user_name" id="user_name" value="' . $user_name . '">
						<input type="hidden" name="outcomeid" id="outcomeid" value="' . $outcomeid . '">
						<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Update" />
						<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
					</div>
				</div>';
		}
	}
	echo $body;
}


function get_expected_returns($projid, $project_impact, $projectevaluation)
{
	global $db;

	$impact = false;
	if ($project_impact == 1) {
		$sql = $db->prepare("SELECT * FROM `tbl_project_expected_impact_details` WHERE projid = :projid ORDER BY `id` ASC");
		$sql->execute(array(":projid" => $projid));
		$rows_count = $sql->rowCount();
		$impact = $rows_count > 0 ? true : false;
	} else {
		$impact = true;
	}


	$evaluation = false;
	if ($projectevaluation) {
		$sql = $db->prepare("SELECT * FROM `tbl_project_expected_outcome_details` WHERE projid = :projid ORDER BY `id` ASC");
		$sql->execute(array(":projid" => $projid));
		$rows_count = $sql->rowCount();
		$evaluation = $rows_count > 0 ? true : false;
	} else {
		$evaluation = true;
	}


	$query_OutputData = $db->prepare("SELECT * FROM tbl_project_details WHERE projid = :projid ORDER BY id ASC");
	$query_OutputData->execute(array(":projid" => $projid));
	$countrows_OutpuData = $query_OutputData->rowCount();
	$row_OutputData =  $query_OutputData->fetch();
	$msg = array();
	if ($countrows_OutpuData > 0) {
		while ($row_OutputData =  $query_OutputData->fetch()) {
			$outputid = $row_OutputData['id'];
			$query_rsDetails =  $db->prepare("SELECT * FROM tbl_project_outputs_mne_details WHERE projid=:projid AND outputid=:outputid");
			$query_rsDetails->execute(array(":projid" => $projid, ":outputid" => $outputid));
			$totalRows_rsDetails = $query_rsDetails->rowCount();

			$msg[] = $totalRows_rsDetails > 0 ? true : false;
		}
	}
	$output =  !in_array(false, $msg) ? true : false;

	$response = true;
	$message = "";
	if (!$impact || !$evaluation || !$output) {
		$message = "Ensure that you have entered all required field";
		$response = false;
	}
	return array("responses" => $response, "message" => $message);
}

function unit_of_measurement($unit_of_measure)
{
	global $db;
	$query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE active=1");
	$query_rsIndUnit->execute();
	$totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
	$unit_options = '<option value="">..Select Unit of Measure..</option>';
	if ($totalRows_rsIndUnit > 0) {
		while ($row_rsIndUnit = $query_rsIndUnit->fetch()) {
			$unit_id = $row_rsIndUnit['id'];
			$unit = $row_rsIndUnit['unit'];
			$selected = $unit_of_measure == $unit_id ? "selected" : '';
			$unit_options .= '<option value="' . $unit_id . '" ' . $selected . '>' . $unit . '</option>';
		}
	}
	return $unit_options;
}


if (isset($_GET['get_unit_of_measure'])) {
	$query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE active=1");
	$query_rsIndUnit->execute();
	$totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
	$unit_options = '<option value="">..Select Unit of Measure..</option>';
	if ($totalRows_rsIndUnit > 0) {
		while ($row_rsIndUnit = $query_rsIndUnit->fetch()) {
			$unit_id = $row_rsIndUnit['id'];
			$unit = $row_rsIndUnit['unit'];
			$unit_options .= '<option value="' . $unit_id . '">' . $unit . '</option>';
		}
	}
	echo json_encode(array("measurements" => $unit_options, "success" => true));
}


if (isset($_GET['get_budgetline_details'])) {
	$projid = $_GET['projid'];
	$cost_type = $_GET['cost_type'];
	$mne_cost = $_GET['mne_cost'];
	$query_rs_output_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid=:projid AND cost_type=:cost_type ");
	$query_rs_output_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type));
	$totalRows_rs_output_cost_plan = $query_rs_output_cost_plan->rowCount();
	$table_body = '';
	$sub_total_amount = 0;
	if ($totalRows_rs_output_cost_plan > 0) {
		$table_counter = 0;
		while ($row_rsOther_cost_plan = $query_rs_output_cost_plan->fetch()) {
			$table_counter++;
			$description = $row_rsOther_cost_plan['description'];
			$unit_of_measure = $row_rsOther_cost_plan['unit'];
			$task_parameter_id = $row_rsOther_cost_plan['id'];
			$unit_cost = $row_rsOther_cost_plan['unit_cost'];
			$units_no = $row_rsOther_cost_plan['units_no'];
			$order = $row_rsOther_cost_plan['item_order'];
			$subtask_id = $row_rsOther_cost_plan['subtask_id'];
			$total_cost = $unit_cost * $units_no;
			$sub_total_amount += $total_cost;
			$measurements = unit_of_measurement($unit_of_measure);
			$table_body .= '
				<tr id="budget_line_cost_line' . $table_counter . '">
					<td>
						<input type="text" name="order[]" class="form-control sequence" id="order' . $table_counter . '" value="' . $order . '">
					</td>
					<td>
						<input type="text" name="description[]" class="form-control" id="description' . $table_counter . '" value="' . htmlspecialchars($description) . '">
					</td>
					<td>
						<select name="unit_of_measure[]" id="unit_of_measure' . $table_counter . '" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true">
							' . $measurements . '
						</select>
					</td>
					<td>
						<input type="number" name="no_units[]" min="0" value="' . $units_no . '" class="form-control" onchange="calculate_total_cost(' . $table_counter .  ')" onkeyup="calculate_total_cost(' . $table_counter . ')" id="no_units' . $table_counter . '">
					</td>
					<td>
						<input type="number" name="unit_cost[]" min="0" value="' . $unit_cost . '" class="form-control" onchange="calculate_total_cost(' . $table_counter .  ')" onkeyup="calculate_total_cost(' . $table_counter .   ')" id="unit_cost' . $table_counter . '">
					</td>
					<td>
						<input type="hidden" name="subtask_id[]"  class="form-control" id="subtask_id' . $table_counter . '" value="' . $subtask_id . '"/>
						<input type="hidden" name="task_type[]"  class="form-control" id="task_type' . $table_counter . '" value="0"/>
						<input type="hidden" name="subtotal_amount[]" id="subtotal_amount' . $table_counter . '" class="subtotal_amount subamount" value="' . $total_cost . '">
						<span id="subtotal_cost' . $table_counter . '" style="color:red">' . number_format($total_cost, 2) . '</span>
					</td>
					<td style="width:2%">';
			if ($table_counter != 1) {
				$table_body .= '
				<button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_budget_costline("budget_line_cost_line' . $table_counter . '")>
					<span class="glyphicon glyphicon-minus"></span>
				</button>';
			}
			$table_body .= '</td></tr>';
		}
	}


	$sub_total_percentage  = ($sub_total_amount / $mne_cost) * 100;
	$query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type >2");
	$query_rsOther_cost_plan_budget->execute(array(":projid" => $projid));
	$row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
	$monitoring_budget = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;
	$balance = ($mne_cost - $monitoring_budget) + $sub_total_amount;

	$footer =
		'<tr>
			<td><strong>Balance</strong></td>
			<td>
				<input type="hidden" name="remaining_balance" id="remaining_balance" value="' . $balance . '" />
				<input type="text" name="remaining" value="' . number_format($balance, 2) . '" id="remaining_balance1" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
			</td>
			<td><strong>Sub Total</strong></td>
			<td>
				<input type="text" name="subtotal_amount" value="' . number_format($sub_total_amount, 2) . '" id="psub_total_amount3" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
			</td>
			<td> <strong>% Sub Total</strong></td>
			<td colspan="2">
				<input type="text" name="subtotal_percentage" value="' . number_format($sub_total_percentage, 2) . '%" id="psub_total_percentage3" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
			</td>
		</tr>';
	echo json_encode(array("success" => true, "body" => $table_body, "footer" => $footer, "budget" => $sub_total_amount));
}

if (isset($_POST['store'])) {
	$projid = $_POST['projid'];
	$outputid = $_POST['output_id'];
	$created_by  = $_POST['user_name'];
	$date_created = date("Y-m-d");
	$cost_type = $_POST['cost_type'];
	$tasks = $_POST['task_id'];
	$other_plan_id = $_POST['budget_line_id'];
	$plan_id = $_POST['plan_id'];
	$site_id = 0;
	$response = false;
	$sql = $db->prepare("DELETE FROM tbl_project_direct_cost_plan WHERE projid=:projid AND cost_type=:cost_type");
	$sql->execute(array(':projid' => $projid, ':cost_type' => $cost_type));
	$units_count = count($_POST['no_units']);
	if (isset($_POST['no_units']) && !empty($_POST['no_units'])) {
		for ($j = 0; $j < $units_count; $j++) {
			$description = $_POST['description'][$j];
			$unit = $_POST['unit_of_measure'][$j];
			$subtask_id = $_POST['subtask_id'][$j];
			$unit_cost = $_POST['unit_cost'][$j];
			$units_no = $_POST['no_units'][$j];
			$task_type = $_POST['task_type'][$j];
			$order = $_POST['order'][$j];
			$sql = $db->prepare("INSERT INTO tbl_project_direct_cost_plan (projid,outputid,site_id,plan_id,tasks,subtask_id,other_plan_id,description,unit,unit_cost,units_no,cost_type,task_type,item_order,created_by,date_created) VALUES (:projid,:outputid,:site_id,:plan_id,:tasks,:subtask_id,:other_plan_id, :description,:unit,:unit_cost,:units_no,:cost_type,:task_type,:item_order,:created_by, :date_created)");
			$result[]  = $sql->execute(array(":projid" => $projid, ":outputid" => $outputid, ":site_id" => $site_id, ":plan_id" => $plan_id, ":tasks" => $tasks, ":subtask_id" => $subtask_id, ":other_plan_id" => $other_plan_id, ":description" => $description, ":unit" => $unit, ":unit_cost" => $unit_cost, ":units_no" => $units_no, ":cost_type" => $cost_type, ":task_type" => $task_type, ":item_order" => $order, ":created_by" => $created_by, ":date_created" => $date_created));
		}
	}
	echo json_encode(array("success" => true));
}


if (isset($_POST['deleteItem'])) {
	$item_type = $_POST['item_type'];
	$id = $_POST['itemId'];
	if ($item_type == "outcome") {
		$sql = $db->prepare("DELETE FROM tbl_project_expected_outcome_details WHERE id=:id");
		$sql->execute(array(':id' => $id));
	} elseif ($item_type == "impact") {
		$sql = $db->prepare("DELETE FROM tbl_project_expected_impact_details WHERE id=:id");
		$sql->execute(array(':id' => $id));
	}

	$sql = $db->prepare("DELETE FROM tbl_project_evaluation_questions WHERE resultstypeid=:resultstypeid");
	$sql->execute(array(':resultstypeid' => $id));
	echo json_encode(array("success" => true));
}
