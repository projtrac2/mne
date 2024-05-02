<?php
try {
  require('includes/head.php');
  if ($permission) {
    $formkey = (isset($_GET['fkey'])) ? base64_decode($_GET['fkey']) : header("Location: project-concluded-evaluations");
    //$formid = base64_encode($frmid);
    $query_concluded_evaluations = $db->prepare("SELECT * FROM tbl_survey_conclusion WHERE formkey=:formkey GROUP BY formkey");
    $query_concluded_evaluations->execute(array(":formkey" => $formkey));
    $row_concluded_evaluations = $query_concluded_evaluations->fetch();
    $projid = $row_concluded_evaluations["projid"];
    $indid = $row_concluded_evaluations["indid"];
    $evaluationtype = $row_concluded_evaluations["survey_type"];

    $query_variables_cat =  $db->prepare("SELECT indicator_calculation_method FROM tbl_indicator WHERE indid='$indid'");
    $query_variables_cat->execute();
    $row_variables_cat = $query_variables_cat->fetch();
    $cat = $row_variables_cat["indicator_calculation_method"];

    $query_evaluation_data_source = $db->prepare("SELECT data_source FROM tbl_project_expected_outcome_details WHERE projid=:projid");
    $query_evaluation_data_source->execute(array(":projid" => $projid));
    $row_evaluation_data_source = $query_evaluation_data_source->fetch();
    $data_source = $row_evaluation_data_source["data_source"];

    if ($data_source == 1) {
      $query_survey_form = $db->prepare("SELECT id FROM tbl_indicator_baseline_survey_forms WHERE projid=:projid and form_name=:evaluationtype");
      $query_survey_form->execute(array(":projid" => $projid, ":evaluationtype" => $evaluationtype));
      $row_survey_form = $query_survey_form->fetch();
      $formid = $row_survey_form["id"];
    }


    $query_rsEvalDates = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE id=:frmid");
    $query_rsEvalDates->execute(array(":frmid" => $formid));
    $row_rsrsEvalDates = $query_rsEvalDates->fetch();
    $totalRows_rsEvalDates = $query_rsEvalDates->rowCount();

    $formname = $row_rsrsEvalDates["form_name"];
    $projid = $row_rsrsEvalDates["projid"];
    $enumeratortype = $row_rsrsEvalDates["enumerator_type"];
    $sample = $row_rsrsEvalDates["sample_size"];
    $evalstartdate = $row_rsrsEvalDates["startdate"];
    $evalenddate = $row_rsrsEvalDates["enddate"];
    $current_date = date("Y-m-d");
    $evalid = $row_rsrsEvalDates["id"];
    $indid = $row_rsrsEvalDates["indid"];
    $sdate = date_create($evalstartdate);
    $startdate = date_format($sdate, "d M Y");
    $edate = date_create($evalenddate);
    $enddate = date_format($edate, "d M Y");

    $query_proj = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
    $query_proj->execute(array(":projid" => $projid));
    $row_proj = $query_proj->fetch();
    $project = $row_proj["projname"];
    $projstage = $row_proj["projstage"];
    $proj_locations = $row_proj["projstate"];
    $projlocations = explode(",", $proj_locations);
    $proj_location_count = count($projlocations);

    $query_yesno_questions = $db->prepare("SELECT * FROM tbl_project_outcome_evaluation_questions WHERE projid=:projid AND answertype=1");
    $query_yesno_questions->execute(array(":projid" => $projid));
    //$row_main_questions = $query_main_questions->fetch();
    $count_yesno_questions = $query_yesno_questions->rowCount();

    $query_other_questions = $db->prepare("SELECT * FROM tbl_project_outcome_evaluation_questions WHERE projid=:projid AND answertype<>1");
    $query_other_questions->execute(array(":projid" => $projid));
    //$row_other_questions = $query_other_questions->fetchAll();
    $count_other_questions = $query_other_questions->rowCount();

    $query_indicator = $db->prepare("SELECT * FROM tbl_indicator i left join tbl_measurement_units u on u.id=i.indicator_unit WHERE indid=:indid");
    $query_indicator->execute(array(":indid" => $indid));
    $row_indicator = $query_indicator->fetch();
    $rowspan = 2;
    $colspan = 2;
    if (!empty($row_indicator)) {
      $change = $row_indicator["indicator_name"];
      $expected_change = $row_indicator["indicator_name"];
      $unit = $row_indicator["unit"];
      $disaggregated = $row_indicator["indicator_disaggregation"];
      $indicator = $unit . " of " . $change;
      $calculation_method = $row_indicator["indicator_calculation_method"];
      $count_disaggregations = '';

      if ($disaggregated == 1) {
        $query_indicator_disag_type = $db->prepare("SELECT t.category as cat FROM tbl_indicator_measurement_variables_disaggregation_type c left join tbl_indicator_disaggregation_types t on t.id=c.disaggregation_type WHERE c.indicatorid=:indid");
        $query_indicator_disag_type->execute(array(":indid" => $indid));
        $row_indicator_disag_type = $query_indicator_disag_type->fetch();
        $rowspan = $query_indicator_disag_type->rowCount();
        $variable_category = $row_indicator_disag_type["cat"];

        $query_indicator_disaggregations = $db->prepare("SELECT *, d.id AS disid FROM tbl_indicator_disaggregations d left join tbl_indicator_disaggregation_types t on t.id=d.disaggregation_type WHERE indicatorid=:indid");
        $query_indicator_disaggregations->execute(array(":indid" => $indid));
        $row_indicator_disaggregations = $query_indicator_disaggregations->fetchAll();
        $count_disaggregations = $query_indicator_disaggregations->rowCount();
        $rowspan = 3;
        $colspan = $count_disaggregations;
        //$colspan = $count_disaggregations * 2;
        //$rowspan = count($variable_category);
      }
    }

    $query_conclusion_comments = $db->prepare("SELECT comments FROM tbl_survey_conclusion WHERE projid=:projid and survey_type=:surveytype");
    $query_conclusion_comments->execute(array(":projid" => $projid, ":surveytype" => $formname));
    $row_conclusion_comments = $query_conclusion_comments->fetch();
    $comments = $row_conclusion_comments["comments"];

?>
    <script src="ckeditor/ckeditor.js"></script>
    <!-- start body  -->
    <section class="content">
      <div class="container-fluid">
        <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
          <h4 class="contentheader">
            <?= $icon ?>
            <?= $pageTitle ?>
            <div class="btn-group" style="float:right">
              <div class="btn-group" style="float:right">
                <input type="button" VALUE="Go Back" class="btn btn-warning" onclick="location.href='project-concluded-evaluations.php'" id="btnback">
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
              <div class="body">
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12">
                      <h5 class="text-align-center bg-light-green" style="border-radius:4px; padding:10px"><strong>Project Name: <?= $project ?></strong></h5>
                    </div>
                    <div class="col-md-12">
                      <label class="control-label">Outcome Indicator:</label>
                      <div class="form-line">
                        <div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
                          <strong><?php echo $unit . " of " . $change; ?></strong>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <label class="control-label">Sample size used:</label>
                      <div class="form-line">
                        <div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
                          <strong><?php echo $sample; ?> samples per location</strong>
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
                              <strong>' . $variable_category . '</strong>
                            </div>
                          </div>
                        </div>';
                    }
                    ?>
                    <div class="col-md-4">
                      <label class="control-label">Survey Start Date:</label>
                      <div class="form-line">
                        <div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
                          <strong><?php echo $startdate; ?></strong>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <label class="control-label">Survey End Date:</label>
                      <div class="form-line">
                        <div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
                          <strong><?php echo $enddate; ?></strong>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row clearfix">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <fieldset class="scheduler-border">
                      <legend class="scheduler-border" style="background-color:#03A9F4; border:#000 thin solid;; color:white"><strong> Data: </strong>
                      </legend>

                      <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <div class="col-md-12 table-responsive">
                            <table class="table table-bordered table-striped">
                              <thead>
                                <tr class="bg-light-blue">
                                  <th colspan="" rowspan="<?= $rowspan ?>" style="width:30%">Yes/No Questions</th>
                                  <th colspan="" rowspan="<?= $rowspan ?>" style="width:20%">Project&nbsp;Location/s</th>
                                  <th colspan="<?= $colspan ?>" rowspan="">Answers</th>
                                </tr>
                                <?php
                                if ($disaggregated == 1) { ?>
                                  <tr class="bg-light-blue">
                                    <?php
                                    foreach ($row_indicator_disaggregations as $disaggregations) { ?>
                                      <th colspan="2"><?php echo $disaggregations["disaggregation"] ?></th>
                                    <?php
                                    }
                                    ?>
                                  </tr>
                                  <tr class="bg-light-blue">
                                    <?php
                                    foreach ($row_indicator_disaggregations as $disaggregations) { ?>
                                      <th>Yes </th>
                                      <th>No </th>
                                    <?php
                                    } ?>
                                  </tr>
                                <?php
                                } else {
                                ?>
                                  <tr class="bg-light-blue">
                                    <th>Yes </th>
                                    <th>No </th>
                                  </tr>
                                <?php
                                }
                                ?>
                              </thead>
                              <tbody>
                                <?php
                                if ($count_yesno_questions > 0) {

                                  $query_answers_yes_total =  $db->prepare("SELECT * FROM tbl_project_outcome_evaluation_questions q left join tbl_project_evaluation_answers a on a.questionid=q.id left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE q.projid='$projid' and formid='$formid' and q.answertype=1 and answer=1");
                                  $query_answers_yes_total->execute();
                                  $count_answers_yes_total = $query_answers_yes_total->rowCount();

                                  $query_answers_no_total =  $db->prepare("SELECT * FROM tbl_project_outcome_evaluation_questions q left join tbl_project_evaluation_answers a on a.questionid=q.id left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE q.projid='$projid' and formid='$formid' and q.answertype=1 and answer=0");
                                  $query_answers_no_total->execute();
                                  $count_answers_no_total = $query_answers_no_total->rowCount();

                                  while ($row_yesno_questions = $query_yesno_questions->fetch()) {
                                    $questionid = $row_yesno_questions["id"];
                                    $question = $row_yesno_questions["question"];

                                    $query_proj_location =  $db->prepare("SELECT projstate FROM tbl_projects WHERE projid='$projid'");
                                    $query_proj_location->execute();
                                    $row_locatios = $query_proj_location->fetch();
                                    $proj_locations = $row_locatios["projstate"];
                                    $projlocations = explode(",", $proj_locations);
                                    $proj_location_count = count($projlocations);

                                ?>
                                    <tr class="bg-lime">
                                      <td class="bg-light-green" rowspan="<?= $proj_location_count ?>"><?php echo $question ?></td>
                                      <?php
                                      foreach ($projlocations as $locations) {
                                        $query_location =  $db->prepare("SELECT * FROM tbl_state WHERE id='$locations'");
                                        $query_location->execute();
                                        $row_location = $query_location->fetch();
                                        $location = $row_location["state"];
                                      ?>
                                        <td class="bg-lime">
                                          <font color="#000"><?php echo $location; ?></font>
                                        </td>
                                        <?php
                                        if ($disaggregated == 1) {
                                          foreach ($row_indicator_disaggregations as $rows) {
                                            $disaggregationid = $rows["disid"];

                                            $query_answers_yes =  $db->prepare("SELECT * FROM tbl_project_evaluation_answers a left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE projid='$projid' and formid='$formid' and level3='$locations' and disaggregation='$disaggregationid' and questionid='$questionid' and answer=1");
                                            $query_answers_yes->execute();
                                            $count_answers_yes = $query_answers_yes->rowCount();

                                            $query_answers_no =  $db->prepare("SELECT * FROM tbl_project_evaluation_answers a left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE projid='$projid' and formid='$formid' and level3='$locations' and disaggregation='$disaggregationid' and questionid='$questionid' and answer=0");
                                            $query_answers_no->execute();
                                            $count_answers_no = $query_answers_no->rowCount();
                                        ?>
                                            <td class="bg-lime text-center">
                                              <font color="#f7070b"><?= $count_answers_yes ?></font>
                                            </td>
                                            <td class="bg-lime text-center">
                                              <font color="#f7070b"><?= $count_answers_no ?></font>
                                            </td>
                                          <?php
                                          }
                                        } else {
                                          $query_answers_yes =  $db->prepare("SELECT * FROM tbl_project_evaluation_answers a left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE projid='$projid' and formid='$formid' and level3='$locations' and questionid='$questionid' and answer=1");
                                          $query_answers_yes->execute();
                                          $count_answers_yes = $query_answers_yes->rowCount();

                                          $query_answers_no =  $db->prepare("SELECT * FROM tbl_project_evaluation_answers a left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE projid='$projid' and formid='$formid' and level3='$locations' and questionid='$questionid' and answer=0");
                                          $query_answers_no->execute();
                                          $count_answers_no = $query_answers_no->rowCount();
                                          ?>
                                          <td class="bg-lime text-center">
                                            <font color="#f7070b"><?= $count_answers_yes ?></font>
                                          </td>
                                          <td class="bg-lime text-center">
                                            <font color="#f7070b"><?= $count_answers_no ?></font>
                                          </td>
                                      <?php
                                        }
                                        echo "</tr>";
                                      }
                                      ?>
                                    </tr>
                                  <?php
                                  }
                                  echo '
								  <tr class="bg-lime">
									<td class="bg-green" colspan="2" align="right">Total</td>';
                                  if ($disaggregated == 1) {
                                    foreach ($row_indicator_disaggregations as $rows) {
                                      $disaggregationid = $rows["disid"];
                                      $query_answers_yes_total =  $db->prepare("SELECT * FROM tbl_project_evaluation_answers a left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE projid='$projid' and formid='$formid' and a.answer=1 and disaggregation='$disaggregationid'");
                                      $query_answers_yes_total->execute();
                                      $count_answers_yes_total = $query_answers_yes_total->rowCount();

                                      $query_answers_no_total =  $db->prepare("SELECT * FROM tbl_project_evaluation_answers a left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE projid='$projid' and formid='$formid' and answer=0 and disaggregation='$disaggregationid'");
                                      $query_answers_no_total->execute();
                                      $count_answers_no_total = $query_answers_no_total->rowCount();

                                      echo '<td class="bg-green" align="center">' . $count_answers_yes_total . '</td>
										<td class="bg-green" align="center">' . $count_answers_no_total . '</td>';
                                    }
                                  } else {
                                    echo '<td class="bg-green" align="center">' . $count_answers_yes_total . '</td>
									  <td class="bg-green" align="center">' . $count_answers_no_total . '</td>';
                                  } ?>
                                  </tr>
                                <?php
                                }
                                ?>
                              </tbody>
                            </table>
                          </div>
                        </div>

                        <!-- =======================================Narration Questions====================================== -->

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <div class="col-md-12 table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                              <thead>
                                <tr class="bg-light-blue">
                                  <th style="width:75%">Narration Questions</th>
                                  <th style="width:25%">Responses</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                if ($count_other_questions > 0) {
                                  $query_answers_yes_total =  $db->prepare("SELECT * FROM tbl_project_evaluation_answers a left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE projid='$projid' and formid='$formid' and answer=1");
                                  $query_answers_yes_total->execute();
                                  $count_answers_yes_total = $query_answers_yes_total->rowCount();

                                  $query_answers_no_total =  $db->prepare("SELECT * FROM tbl_project_evaluation_answers a left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE projid='$projid' and formid='$formid' and answer=0");
                                  $query_answers_no_total->execute();
                                  $count_answers_no_total = $query_answers_no_total->rowCount();

                                  while ($row_other_questions = $query_other_questions->fetch()) {
                                    $question = $row_other_questions["question"];
                                    $questionid = $row_other_questions["id"];

                                    $query_proj_location =  $db->prepare("SELECT projstate FROM tbl_projects WHERE projid='$projid'");
                                    $query_proj_location->execute();
                                    $row_locatios = $query_proj_location->fetch();
                                    $proj_locations = $row_locatios["projstate"];
                                    $projlocations = explode(",", $proj_locations);
                                    $proj_location_count = count($projlocations);
                                    $qst = base64_encode($questionid);

                                ?>
                                    <tr class="bg-lime">
                                      <td class="bg-light-green"><a href="survey-narration-question-data.php?qst=<?= $qst ?>"><?php echo $question ?></a></td>
                                      <?php
                                      $query_other_questions_answers =  $db->prepare("SELECT * FROM tbl_project_evaluation_answers a left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE projid='$projid' and formid='$formid' and questionid='$questionid'");
                                      $query_other_questions_answers->execute();
                                      $count_other_questions_answers = $query_other_questions_answers->rowCount();
                                      ?>
                                      <td class="bg-lime text-center">
                                        <font color="#f7070b"><?= $count_other_questions_answers ?></font>
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
                      </div>
                    </fieldset>
                    <fieldset class="scheduler-border">
                      <legend class="scheduler-border" style="background-color:#03A9F4; border:#000 thin solid;; color:white"><strong> Beneficiaries: </strong>
                      </legend>

                      <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <div class="table-responsive">
                            <?php
                            if ($disaggregated == 0) {
                            ?>
                              <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                  <tr class="bg-light-blue">
                                    <th style="width:40%">Beneficiary</th>
                                    <th style="width:15%">Location</th>
                                    <th style="width:15%">Baseline</th>
                                    <th style="width:15%">Endline</th>
                                    <th style="width:15%">Change</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr class="bg-lime">
                                    <td class="bg-light-green" rowspan="<?php echo $proj_location_count + 1; ?>">
                                      <?php echo $expected_change ?>
                                    </td>
                                  </tr>
                                  <?php
                                  foreach ($projlocations as $locations) {
                                    $query_baseline_survey = $db->prepare("SELECT c.numerator, c.denominator, c.comments FROM tbl_projects p INNER JOIN tbl_survey_conclusion c ON p.projid=c.projid inner join tbl_indicator i on i.indid=c.indid WHERE survey_type='Baseline' and c.projid=:projid and level3=:location");
                                    $query_baseline_survey->execute(array(":projid" => $projid, ":location" => $locations));
                                    $rows_baseline_survey = $query_baseline_survey->fetch();
                                    $total_rows_baseline_survey = $query_baseline_survey->rowCount();

                                    $query_location =  $db->prepare("SELECT * FROM tbl_state WHERE id='$locations'");
                                    $query_location->execute();
                                    $row_location = $query_location->fetch();
                                    $location = $row_location["state"];

                                    //$cat = $rows_baseline_survey["cat"];
                                    $baseline = "";
                                    if ($total_rows_baseline_survey > 0) {
                                      $numerator = $rows_baseline_survey["numerator"];
                                      $denominator = $rows_baseline_survey["denominator"];
                                      $baseline = $numerator > 0 ? ($numerator) : number_format(0, 2);
                                      if ($calculation_method == 2) {
                                        $baseline = $numerator > 0 && $denominator > 0 ?  number_format(($numerator / $denominator) * 100, 2) . "%" : number_format(0, 2);
                                      }
                                    }


                                    $query_endline_survey = $db->prepare("SELECT variable_category AS cat, c.numerator, c.denominator, c.comments FROM tbl_projects p INNER JOIN tbl_survey_conclusion c ON p.projid=c.projid inner join tbl_indicator i on i.indid=c.indid WHERE survey_type='Endline' and c.projid=:projid and level3=:location");
                                    $query_endline_survey->execute(array(":projid" => $projid, ":location" => $locations));
                                    $rows_endline_survey = $query_endline_survey->fetch();
                                    $count_endline_surveys = $query_endline_survey->rowCount();

                                    $endline = 'Pending';
                                    $difference = 'Pending';
                                    if ($count_endline_surveys > 0) {
                                      //$endcategory = $rows_endline_survey["cat"];
                                      $endnumerator = $rows_endline_survey["numerator"];
                                      $enddenominator = $rows_endline_survey["denominator"];
                                      if ($calculation_method == 2) {
                                        $endline = number_format(($endnumerator / $enddenominator) * 100, 2);
                                      } else {
                                        $endline = $endnumerator;
                                      }
                                      $difference = $endline - $baseline;
                                    }

                                  ?>
                                    <tr class="bg-lime">
                                      <td class="bg-light-green"><?php echo $location; ?></td>
                                      <td class="bg-light-green"><?php echo $baseline; ?></td>
                                      <td class="bg-light-green"><?php echo $endline; ?></td>
                                      <td class="bg-light-green"><?php echo $difference; ?></td>
                                    </tr>
                                  <?php
                                  }
                                  ?>
                                </tbody>
                              </table>
                            <?php
                            } else {
                              //$colspan = 2;
                            ?>
                              <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                  <tr class="bg-light-blue">
                                    <th colspan="" rowspan="2" style="width:20%">Beneficiaries</th>
                                    <th colspan="" rowspan="2" style="width:20%">Location</th>
                                    <th colspan="<?= $colspan ?>" rowspan="" style="width:20%">Baseline</th>
                                    <th colspan="<?= $colspan ?>" rowspan="" style="width:20%">Endline</th>
                                    <th colspan="<?= $colspan ?>" rowspan="" style="width:20%"><?= $colspan ?></th>
                                  </tr>
                                  <tr class="bg-light-blue">
                                    <?php
                                    for ($i = 0; $i < 3; $i++) {
                                      foreach ($row_indicator_disaggregations as $disaggregations) { ?>
                                        <th><?php echo $disaggregations["disaggregation"] ?></th>
                                    <?php
                                      }
                                    }
                                    ?>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr class="bg-lime">
                                    <td class="bg-light-green" rowspan="<?php echo $proj_location_count + 1; ?>">
                                      <?php echo $expected_change ?>
                                    </td>
                                  </tr>
                                  <?php
                                  foreach ($projlocations as $locations) {
                                    if ($projstage == 10) {
                                      $query_baseline_survey = $db->prepare("SELECT variable_category AS cat, c.disaggregation, c.numerator, c.denominator, c.comments FROM tbl_projects p INNER JOIN tbl_survey_conclusion c ON p.projid=c.projid inner join tbl_indicator i on i.indid=c.indid WHERE survey_type='Baseline' and c.projid=:projid and level3=:location");
                                      $query_baseline_survey->execute(array(":projid" => $projid, ":location" => $locations));
                                      $count_baseline_survey = $query_baseline_survey->rowCount();
                                      $rows_baseline_survey = $query_baseline_survey->fetchAll();
                                    } else {
                                      $query_baseline_survey = $db->prepare("SELECT  disaggregation, numerator, denominator FROM tbl_survey_conclusion inner join tbl_indicator i on i.indid=c.indid WHERE survey_type='Baseline' and c.projid=:projid and level3=:location");
                                      $query_baseline_survey->execute(array(":projid" => $projid, ":location" => $locations));
                                      $count_baseline_survey = $query_baseline_survey->rowCount();
                                      $rows_baseline_survey = $query_baseline_survey->fetchAll();
                                    }

                                    if ($projstage == 11) {
                                      $query_endline_survey = $db->prepare("SELECT variable_category AS cat, c.disaggregation, c.numerator, c.denominator, c.comments FROM tbl_projects p INNER JOIN tbl_survey_conclusion c ON p.projid=c.projid inner join tbl_indicator i on i.indid=c.indid WHERE survey_type='Endline' and c.projid=:projid and level3=:location");
                                      $query_endline_survey->execute(array(":projid" => $projid, ":location" => $locations));
                                      $rows_endline_survey = $query_endline_survey->fetchAll();
                                      $count_endline_surveys = $query_endline_survey->rowCount();
                                    }

                                    $query_location =  $db->prepare("SELECT * FROM tbl_state WHERE id='$locations'");
                                    $query_location->execute();
                                    $row_location = $query_location->fetch();
                                    $location = $row_location["state"];
                                  ?>
                                    <tr class="bg-lime">
                                      <td class="bg-lime">
                                        <font color="#000">
                                          <?php echo $location; ?></font>
                                      </td>
                                      <?php
                                      foreach ($rows_baseline_survey as $row) {
                                        $numerator = $row["numerator"];
                                        $denominator = $row["denominator"];
                                        $baseline = '';
                                        if ($calculation_method == 2) {
                                          $baseline = number_format(($numerator / $denominator) * 100, 2);
                                        } else {
                                          $baseline = $numerator;
                                        }
                                        echo '<td class="bg-lime text-center"><font color="#f7070b">' . $baseline . '</font></td>';
                                      }

                                      if ($projstage == 10) {
                                        for ($j = 0; $j < $count_baseline_survey; $j++) {
                                          echo '<td class="bg-lime text-center"><font color="#f7070b">Pending</font></td><td class="bg-lime text-center"><font color="#f7070b">Pending</font></td>
                                            ';
                                        }
                                      }

                                      if ($projstage == 11) {
                                        foreach ($rows_endline_survey as $row) {
                                          if ($count_endline_surveys > 0) {
                                            $endnumerator = $row["numerator"];
                                            $enddenominator = $row["denominator"];
                                            if ($calculation_method == 2) {
                                              $endline = number_format(($endnumerator / $enddenominator) * 100, 2);
                                            } else {
                                              $endline = $endnumerator;
                                            }
                                          }
                                          echo '<td class="bg-lime text-center"><font color="#f7070b">' . $endline . '</font></td>';
                                        }

                                        foreach ($rows_endline_survey as $rows) {
                                          if ($count_endline_surveys > 0) {
                                            $endnumerator = $rows["numerator"];
                                            $enddenominator = $rows["denominator"];
                                            $disaggregation = $rows["disaggregation"];

                                            $query_baseline = $db->prepare("SELECT numerator, denominator FROM tbl_survey_conclusion WHERE survey_type='Baseline' and projid=:projid and disaggregation=:disaggregation");
                                            $query_baseline->execute(array(":projid" => $projid, ":disaggregation" => $disaggregation));
                                            $rows_baseline = $query_baseline->fetch();
                                            $baseline_numerator = $rows_baseline["numerator"];
                                            $baseline_denominator = $rows_baseline["denominator"];

                                            $numeratordifference = $endnumerator - $baseline_numerator;
                                            $denominatordifference = $enddenominator - $baseline_denominator;

                                            if ($calculation_method == 2) {
                                              $change = number_format(($numeratordifference / $denominatordifference) * 100, 2);
                                            } else {
                                              $change = $numeratordifference;
                                            }
                                            echo '<td class="bg-lime text-center"><font color="#f7070b">' . $change . '</font></td>';
                                          }
                                        }
                                      }
                                      ?>
                                    </tr>
                                  <?php
                                  }
                                  ?>
                                  <tr class="bg-lime">
                                    <td class="bg-green" colspan="2" align="left">Total <?= $expected_change ?></td>
                                    <?php
                                    $query_baseline_survey = $db->prepare("SELECT SUM(numerator) as numerator, SUM(denominator) as denominator FROM tbl_survey_conclusion WHERE survey_type='Baseline' and projid=:projid");
                                    $query_baseline_survey->execute(array(":projid" => $projid));
                                    $count_baseline_survey = $query_baseline_survey->rowCount();
                                    $rows_baseline_survey = $query_baseline_survey->fetchAll();

                                    $query_baseline_count = $db->prepare("SELECT * FROM tbl_survey_conclusion WHERE survey_type='Baseline' and projid=:projid");
                                    $query_baseline_count->execute(array(":projid" => $projid));
                                    $count_baseline_count = $query_baseline_count->rowCount();

                                    $combined_baseline = 0;
                                    foreach ($rows_baseline_survey as $row) {
                                      $numerator = $row["numerator"];
                                      $denominator = $row["denominator"];
                                      $baseline = '';
                                      if ($calculation_method == 2) {
                                        $baseline = number_format(($numerator / $denominator) * 100, 2);
                                      } else {
                                        $baseline = $numerator;
                                      }
                                      $combined_baseline = $combined_baseline + $baseline;
                                    }
                                    echo '<td class="bg-green text-center" colspan="' . $colspan . '">' . $combined_baseline . '</td>';


                                    if ($projstage == 10) {
                                      echo '<td class="bg-green text-center" colspan="' . $colspan . '">Pending</td><td class="bg-green text-center" colspan="' . $colspan . '">Pending</td>';
                                    }

                                    if ($projstage == 11) {
                                      $combined_endline = 0;
                                      $combined_change = 0;

                                      $query_combined_endline = $db->prepare("SELECT SUM(numerator) as numerator, SUM(denominator) as denominator FROM tbl_survey_conclusion WHERE survey_type='Endline' and projid=:projid");
                                      $query_combined_endline->execute(array(":projid" => $projid));
                                      $rows_combined_endline = $query_combined_endline->fetch();
                                      $count_combined_endline = $query_combined_endline->rowCount();


                                      if ($count_combined_endline > 0) {
                                        $endnumerator = $rows_combined_endline["numerator"];
                                        $enddenominator = $rows_combined_endline["denominator"];
                                        if ($calculation_method == 2) {
                                          $endline = number_format(($endnumerator / $enddenominator) * 100, 2);
                                        } else {
                                          $endline = $endnumerator;
                                        }
                                        $combined_endline = $endline;
                                      }
                                      echo '<td class="bg-green text-center" colspan="' . $colspan . '">' . $combined_endline . '</td>';


                                      $query_combined_change = $db->prepare("SELECT SUM(numerator) AS numerator, SUM(denominator) as denominator FROM tbl_survey_conclusion WHERE survey_type='Endline' and projid=:projid");
                                      $query_combined_change->execute(array(":projid" => $projid));
                                      $rows_combined_change = $query_combined_change->fetch();
                                      $count_combined_change = $query_combined_change->rowCount();

                                      if ($count_combined_change > 0) {
                                        $endnumerator = $rows_combined_change["numerator"];
                                        $enddenominator = $rows_combined_change["denominator"];

                                        $query_baseline = $db->prepare("SELECT SUM(numerator) AS numerator, SUM(denominator) as denominator FROM tbl_survey_conclusion WHERE survey_type='Baseline' and projid=:projid");
                                        $query_baseline->execute(array(":projid" => $projid));
                                        $rows_baseline = $query_baseline->fetch();
                                        $baseline_numerator = $rows_baseline["numerator"];
                                        $baseline_denominator = $rows_baseline["denominator"];

                                        $numeratordifference = $endnumerator - $baseline_numerator;
                                        $denominatordifference = $enddenominator - $baseline_denominator;

                                        if ($calculation_method == 2) {
                                          $change = number_format(($numeratordifference / $denominatordifference) * 100, 2);
                                        } else {
                                          $change = $numeratordifference;
                                        }

                                        $combined_change = $change;
                                      }
                                      echo '<td class="bg-green text-center" colspan="' . $colspan . '">' . $combined_change . '</td>';
                                    }
                                    ?>
                                  </tr>
                                </tbody>
                              </table>
                            <?php
                            }
                            ?>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                    <fieldset class="scheduler-border">
                      <legend class="scheduler-border" style="background-color:#03A9F4; border:#000 thin solid;; color:white"><strong> Conclusion Remarks: </strong>
                      </legend>
                      <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <div class="form-line">
                            <div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
                              <strong><?php echo $comments; ?></strong>
                            </div>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
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
} catch (PDOException $ex) {
  customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>