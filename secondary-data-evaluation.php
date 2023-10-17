<?php 
$resultstypeid = $_GET['resultstype'];
$sourceurl = $resultstypeid == 1 ? "impact-evaluation-secondary-data-source" : "evaluation-secondary-data-source";
$resultstype = $resultstypeid == 1 ? "Impact" : "Outcome";

$decode_results = (isset($_GET['results']) && !empty($_GET["results"])) ? base64_decode($_GET['results']) : header("Location: {$sourceurl}"); 
$resultsid_array = explode("resultssecdata", $decode_results);
$resultsid = $resultsid_array[1];

require('includes/head.php'); 

if ($permission) {

	if ((isset($_POST["submit"]))) {
		$results = "";
		$indid = $_POST['indid'];
		$category = $_POST['category'];
		$comments = $_POST['comments'];
		$surveytype = $_POST['surveytype'];
		$projstage = $_POST['projstage'];
		$disaggregation = $_POST['disaggregation'];
		$projid = $_POST['projid'];
		$resultstype = $_POST['resultstype'];
		$resultstypeid = $_POST['resultstypeid'];
		$user = $_POST['user_name'];
		$current_date = date("Y-m-d");

		function submission()
		{
			$digits = "";
			$length = 6;
			$numbers = range(0, 9);
			shuffle($numbers);
			for ($i = 0; $i < $length; $i++) {
				$digits .= $numbers[$i];
			}
			return $digits;
		}
		$submissionid = submission();

		for ($j = 0; $j < count($_POST['location']); $j++) {
			$level3 = $_POST['location'][$j];
			if ($disaggregation) {
				$disaggregationid = $_POST['disaggregationid'];
				for ($i = 0; $i < count($_POST['numerator' . $level3]); $i++) {
					$numerator = $_POST['numerator' . $level3][$i];
					if ($category == 2) {
						$denominator = $_POST['denominator' . $level3][$i];
						$insertSQL = $db->prepare("INSERT INTO tbl_survey_conclusion (formkey, projid, indid, resultstype, resultstypeid, survey_type, level3, disaggregation, variable_category, numerator, denominator, comments, created_by, date_created) VALUES (:formkey, :projid, :indid, :resultstype, :resultstypeid, :surveytype, :level3, :disaggregation, :category, :numerator, :denominator, :comments, :user, :date)");
						$result = $insertSQL->execute(array(':formkey' => $submissionid, ':projid' => $projid, ':indid' => $indid, ':resultstype' =>$resultstype, ':resultstypeid' => $resultstypeid, ':surveytype' => $surveytype, ':level3' => $level3, ':disaggregation' => $disaggregationid[$i], ':category' => $category, ':numerator' => $numerator, ':denominator' => $denominator, ':comments' => $comments, ':user' => $user, ':date' => $current_date));
					} else {
						$insertSQL = $db->prepare("INSERT INTO tbl_survey_conclusion (formkey, projid, indid, resultstype, resultstypeid, survey_type, level3, disaggregation, variable_category, numerator, comments, created_by, date_created) VALUES (:formkey, :projid, :indid, :resultstype, :resultstypeid, :surveytype, :level3, :disaggregation, :category, :numerator, :comments, :user, :date)");
						$result = $insertSQL->execute(array(':formkey' => $submissionid, ':projid' => $projid, ':indid' => $indid, ':resultstype' =>$resultstype, ':resultstypeid' => $resultstypeid, ':surveytype' => $surveytype, ':level3' => $level3, ':disaggregation' => $disaggregationid[$i], ':category' => $category, ':numerator' => $numerator, ':comments' => $comments, ':user' => $user, ':date' => $current_date));
					}
				}
			} else {
				$numerator = $_POST['numerator' . $level3];
				if ($category == 2) {
					$denominator = $_POST['denominator' . $level3];
					$insertSQL = $db->prepare("INSERT INTO tbl_survey_conclusion (formkey, projid, indid, resultstype, resultstypeid, survey_type, level3, variable_category, numerator, denominator, comments, created_by, date_created) VALUES (:formkey, :projid, :indid, :resultstype, :resultstypeid, :surveytype, :level3, :category, :numerator, :denominator, :comments, :user, :date)");
					$result = $insertSQL->execute(array(':formkey' => $submissionid, ':projid' => $projid, ':indid' => $indid, ':resultstype' =>$resultstype, ':resultstypeid' => $resultstypeid, ':surveytype' => $surveytype, ':level3' => $level3, ':category' => $category, ':numerator' => $numerator, ':denominator' => $denominator, ':comments' => $comments, ':user' => $user, ':date' => $current_date));
				} else {
					$insertSQL = $db->prepare("INSERT INTO tbl_survey_conclusion (formkey, projid, indid, resultstype, resultstypeid, survey_type, level3, variable_category, numerator, comments, created_by, date_created) VALUES (:formkey, :projid, :indid, :resultstype, :resultstypeid, :surveytype, :level3, :category, :numerator, :comments, :user, :date)");
					$result = $insertSQL->execute(array(':formkey' => $submissionid, ':projid' => $projid, ':indid' => $indid, ':resultstype' =>$resultstype, ':resultstypeid' => $resultstypeid, ':surveytype' => $surveytype, ':level3' => $level3, ':category' => $category, ':numerator' => $numerator, ':comments' => $comments, ':user' => $user, ':date' => $current_date));
				}
			}
		}

		if ($result) {
			$query_project = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
			$query_project->execute(array(":projid" => $projid));
			$row_project = $query_project->fetch();
			$impact = $row_project["projimpact"];
			$rows_impacts = 0;
			$rows_outcomes = 0;
			
			$query_evaluation_data_row_count = $db->prepare("SELECT * FROM tbl_survey_conclusion WHERE projid=:projid GROUP BY resultstypeid");
			$query_evaluation_data_row_count->execute(array(":projid" => $projid));
			$evaluation_data_row_count = $query_evaluation_data_row_count->rowCount();
			
			if($impact == 1){
				$query_impacts = $db->prepare("SELECT * FROM tbl_project_expected_impact_details WHERE projid=:projid");
				$query_impacts->execute(array(":projid" => $projid));
				$rows_impacts = $query_impacts->rowCount();
			}
			
			$query_outcomes = $db->prepare("SELECT * FROM tbl_project_expected_outcome_details WHERE projid=:projid");
			$query_outcomes->execute(array(":projid" => $projid));
			$rows_outcomes = $query_outcomes->rowCount();
			
			$total_evaluation_count = $rows_outcomes + $rows_impacts;
			
			if($total_evaluation_count == $evaluation_data_row_count){
				$query_projstageupdate = $db->prepare("UPDATE tbl_projects SET projstage=:projstage WHERE projid=:projid");
				$query_projstageupdate->execute(array(":projstage" => $projstage, ":projid" => $projid));
			}

			$url = $sourceurl;
			$msg = 'Successfully saved.';
			$results = "<script type=\"text/javascript\">
			swal({
				title: \"Success!\",
				text: \" $msg\",
				type: 'Success',
				timer: 3000,
				showConfirmButton: false });
			setTimeout(function(){
				window.location.href = \"$url\"; 
			}, 3000); 
		</script>";
		} else {
			$msg = 'Failed!! Could not save your data!!';
			$results = "<script type=\"text/javascript\">
			swal({
				title: \"Error!\",
				text: \" $msg\",
				type: 'Danger',
				timer: 5000,
				showConfirmButton: false });
		</script>";
		}
	}

	if($resultstypeid == 1){
		$query_results = $db->prepare("SELECT * FROM tbl_project_expected_impact_details WHERE id=:resultsid");
		$query_results->execute(array(":resultsid" => $resultsid));
	} else {
		$query_results = $db->prepare("SELECT * FROM tbl_project_expected_outcome_details WHERE id=:resultsid");
		$query_results->execute(array(":resultsid" => $resultsid));
	}
	$row_results = $query_results->fetch();
	$projid = $row_results["projid"];
	$indid = $row_results["indid"];

	$query_proj = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
	$query_proj->execute(array(":projid" => $projid));
	$row_proj = $query_proj->fetch();
	$project = $row_proj["projname"];
	$projstage = $row_proj["projstage"];
	$proj_locations = $row_proj["projlga"];
	$projlocations = explode(",", $proj_locations);
	$proj_location_count = count($projlocations);

	$query_indicator = $db->prepare("SELECT * FROM tbl_indicator i left join tbl_measurement_units u on u.id=i.indicator_unit WHERE indid=:indid");
	$query_indicator->execute(array(":indid" => $indid));
	$row_indicator = $query_indicator->fetch();
	$rowspan = 2;
	$colspan = 2;
	if ($row_indicator) {
		$indicator = $row_indicator["indicator_name"];
		$unit = $row_indicator["unit"];
		$disaggregated = $row_indicator["indicator_disaggregation"];
		$count_disaggregations = '';

		if ($disaggregated == 1) {
			$query_indicator_disag_type = $db->prepare("SELECT * FROM tbl_indicator_measurement_variables_disaggregation_type c left join tbl_indicator_disaggregation_types t on t.id=c.disaggregation_type WHERE c.indicatorid=:indid");
			$query_indicator_disag_type->execute(array(":indid" => $indid));
			$row_indicator_disag_type = $query_indicator_disag_type->fetch();
			$category = $row_indicator_disag_type["category"];

			$query_indicator_disaggregations = $db->prepare("SELECT *, d.id AS disid FROM tbl_indicator_disaggregations d left join tbl_indicator_disaggregation_types t on t.id=d.disaggregation_type WHERE indicatorid=:indid");
			$query_indicator_disaggregations->execute(array(":indid" => $indid));
			$row_indicator_disaggregations = $query_indicator_disaggregations->fetchAll();
			$count_disaggregations = $query_indicator_disaggregations->rowCount();
			$colspan = 2 * $count_disaggregations;
			$rowspan = 2 + 1;
		}
	}

	$evaluationtype = "Baseline";
	if ($projstage == 8) {
		$evaluationtype = "Endline";
	}
	
	$pageTitle = "Project ".$resultstype." ".$evaluationtype." Evaluation";
?>
  <!-- start body  -->
  <section class="content">
    <div class="container-fluid">
      <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
        <h4 class="contentheader">
          <?= $icon ?>
          <?= $pageTitle ?>
          <div class="btn-group" style="float:right">
			<input type="button" VALUE="Go Back" class="btn btn-warning" onclick="location.href='<?=$sourceurl?>'" id="btnback">
          </div>
        </h4>
      </div>
      <div class="row clearfix">
        <div class="block-header">
          <?= $results; ?>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="card">
            <div class="body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="col-lg-12 col-md-12">
							<h5 class="text-align-center bg-light-green" style="border-radius:4px; padding:10px"><strong>Project Name: <?= $project ?></strong></h5>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label class="control-label">Indicator:</label>
							<div class="form-line">
								<div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
									<strong><?php echo $unit . " of " . $indicator; ?></strong>
								</div>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label class="control-label">Unit of Measure:</label>
							<div class="form-line">
								<div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
									<strong><?php echo $unit; ?></strong>
								</div>
							</div>
						</div>

						<?php
						if ($disaggregated == 1) {
							echo '
						<div class="col-md-4">   		
							<label class="control-label">Disaggregation Type:</label>
							<div class="form-line">
								<div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
									<strong>' . $category . '</strong>
								</div>
							</div>
						</div>';
						}
						?>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<fieldset class="scheduler-border">
							<legend class="scheduler-border" style="background-color:#03A9F4; border:#000 thin solid;; color:white"><strong> Data: </strong>
							</legend>
							<form method="POST" name="submitevalfrm" action="" enctype="multipart/form-data" autocomplete="off">
								<?php
								$query_variables_cat =  $db->prepare("SELECT category FROM tbl_indicator_measurement_variables WHERE indicatorid='$indid' GROUP BY indicatorid LIMIT 1");
								$query_variables_cat->execute();
								$row_variables_cat = $query_variables_cat->fetch();
								//$category = $row_variables_cat["category"];

								$query_variables_cat =  $db->prepare("SELECT indicator_calculation_method FROM tbl_indicator WHERE indid='$indid'");
								$query_variables_cat->execute();
								$row_variables_cat = $query_variables_cat->fetch();
								$category = $row_variables_cat["indicator_calculation_method"];

								$query_variables =  $db->prepare("SELECT * FROM tbl_indicator_measurement_variables WHERE indicatorid='$indid' order by id ASC");
								$query_variables->execute();
								$row_variable = $query_variables->fetchAll();
								$n = 0;
								foreach ($projlocations as $locations) {
									$n++;
									$query_location =  $db->prepare("SELECT * FROM tbl_state WHERE id='$locations'");
									$query_location->execute();
									$row_location = $query_location->fetch();
									$location = $row_location["state"];
									echo '<input name="location[]" type="hidden" value="' . $locations . '"/>';
									?>
									<fieldset class="scheduler-border">
										<legend class="scheduler-border" style="background-color:#03A9F4; border:#000 thin solid;; color:white"><strong> Location <?= $n ?>: <?= $location ?></strong>
										</legend>
										<h5 class="text-align-center bg-light-green" style="border-radius:4px; padding:10px"><strong>Measurement Variables</strong></h5>
										<?php
										if ($disaggregated == 1) {
											foreach ($row_indicator_disaggregations as $disaggregations) {
												foreach ($row_variable as $row_variables) {
													//$category = $row_variables["category"];
													$type = $row_variables["type"];
													//$indcalculation = $_POST['indcalculation'];
													$variable = $row_variables["measurement_variable"];
													$variableid = $row_variables["id"];
													$disaggregationid = $disaggregations["disid"];
													echo '<input name="disaggregationid[]" type="hidden" value="' . $disaggregationid . '"/>';
													if ($category == 2) {
														if ($type == "n") {
															?>
															<div class="col-md-6">
																<label class="control-label"><?= $variable ?> (<?= $disaggregations["disaggregation"] ?>):</label>
																<div class="form-line">
																	<input name="numerator<?= $locations ?>[]" type="text" class="form-control" placeholder="Enter numerator value" style="border:#CCC thin solid; border-radius: 5px" required />
																</div>
															</div>
															<?php
														} elseif ($type == "d") { ?>
															<div class="col-md-6">
																<label class="control-label"><?= $variable ?> (<?= $disaggregations["disaggregation"] ?>):</label>
																<div class="form-line">
																	<input name="denominator<?= $locations ?>[]" type="text" class="form-control" placeholder="Enter denominator value" style="border:#CCC thin solid; border-radius: 5px" required />
																</div>
															</div>
														<?php
														}
													} elseif ($category == 1 || $category == 3) {
														if ($type == "n") {
														?>
															<div class="col-md-6">
																<label class="control-label"><?= $variable ?> (<?= $disaggregations["disaggregation"] ?>):</label>
																<div class="form-line">
																	<input name="numerator<?= $locations ?>[]" type="text" class="form-control" placeholder="Enter value" style="border:#CCC thin solid; border-radius: 5px" required />
																</div>
															</div>
														<?php
														}
													}
												}
											}
										} else {
											foreach ($row_variable as $row_variables) {
												//$category = $row_variables["category"];
												$type = $row_variables["type"];
												//$indcalculation = $_POST['indcalculation'];
												$variable = $row_variables["measurement_variable"];
												$variableid = $row_variables["id"];
												//echo '<input name="variableid" type="hidden" value="'.$variableid.'"/>';
												if ($category == 2) {
													if ($type == "n") {
														?>
														<div class="col-md-6">
															<label class="control-label"><?= $variable ?>:</label>
															<div class="form-line">
																<input name="numerator<?= $locations ?>" type="text" class="form-control" placeholder="Enter numerator value" style="border:#CCC thin solid; border-radius: 5px" required />
															</div>
														</div>
													<?php
													} elseif ($type == "d") { ?>
														<div class="col-md-6">
															<label class="control-label"><?= $variable ?>:</label>
															<div class="form-line">
																<input name="denominator<?= $locations ?>" type="text" class="form-control" placeholder="Enter denominator value" style="border:#CCC thin solid; border-radius: 5px" required />
															</div>
														</div>
													<?php
													}
												} elseif ($category == 1 || $category == 3) {
													if ($type == "n") {
													?>
														<div class="col-md-6">
															<label class="control-label"><?= $variable ?>:</label>
															<div class="form-line">
																<input name="numerator<?= $locations ?>" type="text" class="form-control" placeholder="Enter value" style="border:#CCC thin solid; border-radius: 5px" required />
															</div>
														</div>
										<?php
													}
												}
											}
										}
										?>
									</fieldset>
								<?php
								}
								?>
								<label class="control-label">Evaluation Conclusion <font align="left" style="background-color:#CDDC39">(Explain your conclusion on this evaluation)</font>*:</label>
								<p align="left">
									<textarea name="comments" cols="45" rows="3" class="txtboxes" id="evalconcl" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"></textarea>
									<script>
										CKEDITOR.replace('evalconcl', {
											on: {
												instanceReady: function(ev) {
													// Output paragraphs as <p>Text</p>.
													this.dataProcessor.writer.setRules('p', {
														indent: false,
														breakBeforeOpen: false,
														breakAfterOpen: false,
														breakBeforeClose: false,
														breakAfterClose: false
													});
													this.dataProcessor.writer.setRules('ol', {
														indent: false,
														breakBeforeOpen: false,
														breakAfterOpen: false,
														breakBeforeClose: false,
														breakAfterClose: false
													});
													this.dataProcessor.writer.setRules('ul', {
														indent: false,
														breakBeforeOpen: false,
														breakAfterOpen: false,
														breakBeforeClose: false,
														breakAfterClose: false
													});
													this.dataProcessor.writer.setRules('li', {
														indent: false,
														breakBeforeOpen: false,
														breakAfterOpen: false,
														breakBeforeClose: false,
														breakAfterClose: false
													});
												}
											}
										});
									</script>
								</p>
								<div class="row clearfix">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center">
										<a href="project-survey" class="btn btn-warning" style="margin-right:10px">Cancel</a>
										<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" />
										<input name="projid" type="hidden" id="projid" value="<?php echo $projid; ?>" />
										<input name="indid" type="hidden" id="indid" value="<?php echo $indid; ?>" />
										<input name="resultstypeid" type="hidden" id="resultstypeid" value="<?php echo $resultsid; ?>" />
										<input name="resultstype" type="hidden" id="resultstype" value="<?php echo $resultstypeid; ?>" />
										<input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
										<input name="category" type="hidden" class="form-control" value="<?php echo $category; ?>" />
										<input name="surveytype" type="hidden" value="<?php echo $evaluationtype; ?>" />
										<input name="disaggregation" type="hidden" value="<?php echo $disaggregated; ?>" />
										<input name="projstage" type="hidden" value="<?php echo $projstage + 1; ?>" />
									</div>
								</div>
							</form>
						</fieldset>
					</div>
				</div>
            </div>
          </div>
        </div>
      </div>
  </section>
  <!-- end body  -->
<?php
} else {
  $results =  restriction();
  echo $results;
}

require('includes/footer.php');
?>

<script>
  $(document).ready(function() {
    // load_evaluation_responses();
    // setInterval(function() {
    //   load_active_baseline_responses();
    // }, 2000);
  });

  function more(formid) {
    if (formid != "") {
      $.ajax({
        url: "assets/processor/add-baseline-processor.php",
        method: "GET",
        data: {
          more: "view",
          formid: formid
        },
        dataType: "html",
        success: function(data) {
          $("#moreinfo").html(data);
        }
      });
    }
  }

  function load_active_baseline_responses(view = '') {
    $.ajax({
      url: "assets/processor/add-baseline-processor.php",
      method: "GET",
      data: {
        view: view
      },
      dataType: "json",
      success: function(data) {
        if (data.all_responses > 0) {
          $('#resp' + data.projid).html(data.all_responses);
        }
      }
    });
  }
</script>