<?php
require('includes/head.php');

if ($permission) {

  try {
    $query_baseline_survey = $db->prepare("SELECT o.id AS id, projstage, projstatus FROM tbl_projects p inner join tbl_project_expected_outcome_details o on o.projid=p.projid WHERE data_source=1 and (projstage=9 OR projstage=10) AND responsible=:user_name ORDER BY p.projid ASC");
    $query_baseline_survey->execute(array(":user_name" => $user_name));

    if ( $designation == 1) {
      $query_baseline_survey = $db->prepare("SELECT o.id AS id, projstage, projstatus FROM tbl_projects p inner join tbl_project_expected_outcome_details o on o.projid=p.projid WHERE data_source=1 and (projstage=9 OR projstage=10) ORDER BY p.projid ASC");
      $query_baseline_survey->execute();
    }
    //$rows = $query_baseline_survey->fetch();
    $count_baseline_survey = $query_baseline_survey->rowCount();

    $newsurveys = 0;

    if ($count_baseline_survey > 0) {
      while ($row = $query_baseline_survey->fetch()) {
        $outcomeid = $row["id"];
        $outcomeprojstatus = $row["projstatus"];
        $outcomeprojstage = $row["projstage"];
		if($outcomeprojstage == 9){
			$query_survey_forms = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE resultstypeid=:outcomeid and resultstype = 2 and created_by=:responsible");
			$query_survey_forms->execute(array(":outcomeid" => $outcomeid, ":responsible" => $user_name));
			if ( $designation == 1) {
			  $query_survey_forms = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE resultstypeid=:outcomeid and resultstype = 2");
			  $query_survey_forms->execute(array(":outcomeid" => $outcomeid));
			}
			$totalrows_survey_forms = $query_survey_forms->rowCount();

			if ($totalrows_survey_forms > 0) {
			  $query_survey_forms_status = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE resultstypeid=:outcomeid and resultstype = 2 and created_by=:responsible and status = 1");
			  $query_survey_forms_status->execute(array(":outcomeid" => $outcomeid, ":responsible" => $user_name));
			  if ( $designation == 1) {
				$query_survey_forms_status = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE resultstypeid=:outcomeid and resultstype = 2 and status = 1");
				$query_survey_forms_status->execute(array(":outcomeid" => $outcomeid));
			  }
			  $totalrows_survey_forms_status = $query_survey_forms_status->rowCount();
			  if ($totalrows_survey_forms_status > 0) {
				$newsurveys++;
			  }
			} else {
			  $newsurveys++;
			}
		}else{
			if($outcomeprojstatus == 5){
				$query_survey_forms = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE resultstypeid=:outcomeid and resultstype = 2 and created_by=:responsible");
				$query_survey_forms->execute(array(":outcomeid" => $outcomeid, ":responsible" => $user_name));
				if ( $designation == 1) {
				  $query_survey_forms = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE resultstypeid=:outcomeid and resultstype = 2");
				  $query_survey_forms->execute(array(":outcomeid" => $outcomeid));
				}
				$totalrows_survey_forms = $query_survey_forms->rowCount();

				if ($totalrows_survey_forms > 0) {
				  $query_survey_forms_status = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE resultstypeid=:outcomeid and resultstype = 2 and created_by=:responsible and status = 1");
				  $query_survey_forms_status->execute(array(":outcomeid" => $outcomeid, ":responsible" => $user_name));
				  if ( $designation == 1) {
					$query_survey_forms_status = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE resultstypeid=:outcomeid and resultstype = 2 and status = 1");
					$query_survey_forms_status->execute(array(":outcomeid" => $outcomeid));
				  }
				  $totalrows_survey_forms_status = $query_survey_forms_status->rowCount();
				  if ($totalrows_survey_forms_status > 0) {
					$newsurveys++;
				  }
				} else {
				  $newsurveys++;
				}
			}
		}
      }
    }

    $query_baseline_step1 = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_project_expected_outcome_details o on o.projid=p.projid WHERE data_source=1 and (projstage=9 OR projstage=10) AND responsible='$user_name' ORDER BY p.projid ASC");
    if ( $designation == 1) {
      $query_baseline_step1 = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_project_expected_outcome_details o on o.projid=p.projid WHERE data_source=1 and (projstage=9 OR projstage=10) ORDER BY p.projid ASC");
    }
    $query_baseline_step1->execute();
    $count_baseline_step1 = $query_baseline_step1->rowCount();


    //============================= ACTIVE SURVEYS ==============================================

    $query_active_survey = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE resultstype = 2 AND status=2 and created_by=:responsible ORDER BY id ASC");
    $query_active_survey->execute(array(":responsible" => $user_name));
    if ( $designation == 1) {
      $query_active_survey = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE resultstype = 2 AND status=2 ORDER BY id ASC");
      $query_active_survey->execute();
    }
    $count_active_survey = $query_active_survey->rowCount();


    //============================= SURVEYS DATA ==============================================

    $query_survey_data = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE resultstype = 2 AND status=3 and created_by=:responsible ORDER BY id ASC");
    $query_survey_data->execute(array(":responsible" => $user_name));
    if ($designation == 1) {
		$query_survey_data = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE resultstype = 2 AND status=3 ORDER BY id ASC");
		$query_survey_data->execute();
    }
    $count_survey_data = 0;
	
	while($row_survey_data = $query_survey_data->fetch()){
		$projid = $row_survey_data["projid"];
		$resultstypeid = $row_survey_data["resultstypeid"];
		$query_outcome_evaluation_concluded = $db->prepare("SELECT * FROM tbl_survey_conclusion WHERE resultstype=2 AND resultstypeid=:resultstypeid AND projid=:projid");
		$query_outcome_evaluation_concluded->execute(array(":resultstypeid" => $resultstypeid, ":projid" => $projid));
		$rows_outcome_evaluation_concluded = $query_outcome_evaluation_concluded->rowCount();

		if ($rows_outcome_evaluation_concluded == 0) {
			$count_survey_data++;
		}
	}


    //============================= SURVEYS CONCLUSION ==============================================

    $query_survey_submissions = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE resultstype = 2 AND status=3 and created_by=:responsible ORDER BY id ASC");
    $query_survey_submissions->execute(array(":responsible" => $user_name));
    if ( $designation == 1) {
      $query_survey_submissions = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE resultstype = 2 AND status=3 ORDER BY id ASC");
      $query_survey_submissions->execute();
    }

    $count_baseline_step5 = 0;
    while ($rows_survey_submissions = $query_survey_submissions->fetch()) {
      $projid = $rows_survey_submissions['projid'];
      $form_id = $rows_survey_submissions['id'];
      $samplesize = $rows_survey_submissions['sample_size'];
	  $resultstypeid = $rows_survey_submissions['resultstypeid'];

      $query_projects = $db->prepare("SELECT * FROM tbl_projects WHERE  projid=:projid ");
      $query_projects->execute(array(":projid" => $projid));
      $rows_projects = $query_projects->fetch();

      $locations = count(explode(",", $rows_projects['projlga']));

      $query_count_submissions = $db->prepare("SELECT * FROM tbl_project_evaluation_submission WHERE projid=:projid AND formid=:formid");
      $query_count_submissions->execute(array(":projid" => $projid, ":formid" => $form_id));
      $count_total_submissions = $query_count_submissions->rowCount();

      $totalsamplesize = $locations * $samplesize;

      $query_evaluation_conclusion = $db->prepare("SELECT * FROM tbl_survey_conclusion WHERE resultstype = 2 AND resultstypeid=:resultstypeid");
      $query_evaluation_conclusion->execute(array(":resultstypeid" => $resultstypeid));
      $count_evaluation_conclusion = $query_evaluation_conclusion->rowCount();

      if ($totalsamplesize == $count_total_submissions && $count_evaluation_conclusion == 0) {
        $count_baseline_step5++;
      }
    }

    $query_survey_conclusion = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE resultstype = 2 AND status=3 and created_by=:responsible ORDER BY id ASC");
    $query_survey_conclusion->execute(array(":responsible" => $user_name));
    if ( $designation == 1) {
      $query_survey_conclusion = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE resultstype = 2 AND status=3 ORDER BY id ASC");
      $query_survey_conclusion->execute();
    }
    $count_survey_conclusion = $query_survey_conclusion->rowCount();
  } catch (PDOException $ex) {
    $results = flashMessage("An error occurred: " . $ex->getMessage());
  }
?>
  <style>
    .modal-lg {
      max-width: 100% !important;
      width: 90%;
    }
  </style>
  <!-- start body  -->
  <section class="content">
    <div class="container-fluid">
      <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
        <h4 class="contentheader">
          <?= $icon ?>
          <?= $pageTitle ?>
          <div class="btn-group" style="float:right">
            <div class="btn-group" style="float:right">
            </div>
          </div>
        </h4>
      </div>
      <div class="row clearfix">
        <div class="block-header">
          <?= $results; ?>
          <div class="header" style="padding-bottom:0px; margin-left:10px; margin-right:10px">
            <div class="button-demo" style="margin-top:-15px">
              <span class="label bg-black" style="font-size:17px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" />Menu</span>
              <a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; padding-left:-5px">Survey Data Source</a>
              <a href="evaluation-secondary-data-source.php" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Records Data Source</a>
            </div>
          </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="card">
            <div class="card-header">
              <ul class="nav nav-tabs" style="font-size:14px">
                <li class="active">
                  <a data-toggle="tab" href="#home"><i class="fa fa-hourglass-half bg-orange" aria-hidden="true"></i> Projects Requiring Survey &nbsp;<span class="badge bg-orange"><?= $newsurveys ?></span></a>
                </li>
                <li>
                  <a data-toggle="tab" href="#menu1"><i class="fa fa-pencil-square-o bg-light-blue" aria-hidden="true"></i> Active Surveys&nbsp;<span class="badge bg-light-blue"><?= $count_active_survey ?></span></a>
                </li>
                <li>
                  <a data-toggle="tab" href="#menu2"><i class="fa fa-check-square-o bg-light-green" aria-hidden="true"></i> Surveys Data&nbsp;<span class="badge bg-light-green"><?= $count_survey_data ?></span></a>
                </li>
                <li>
                  <a data-toggle="tab" href="#menu3"><i class="fa fa-check-square-o bg-green" aria-hidden="true"></i>Survey Conclusion&nbsp;<span class="badge bg-green"><?= $count_baseline_step5 ?></span></a>
                </li>
              </ul>
            </div>
            <div class="body">
              <!-- ============================================================== -->
              <!-- Start Page Content -->
              <!-- ============================================================== -->

              <div class="table-responsive">
                <div class="tab-content">
                  <div id="home" class="tab-pane fade in active">
                    <div class="header">
                      <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
                          <tr>
                            <td width="100%" height="35" style="padding-left:5px; background-color:#000; color:#FFF" bgcolor="#000000">
                              <div align="left"><i class="fa fa-file-text-o" aria-hidden="true"></i> Survey Details </strong></div>
                            </td>
                          </tr>
                        </table>
                      </div>
                    </div>
                    <div class="body">
                      <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                          <tr class="bg-orange">
                            <th style="width:3%">#</th>
                            <th style="width:20%">Indicator</th>
                            <th style="width:35%">Project Name</th>
                            <th style="width:15%">Survey&nbsp;Type</th>
                            <th style="width:10%">Due Date</th>
                            <th style="width:10%">Status</th>
                            <th style="width:7%">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          if ($count_baseline_step1 > 0) {
                            $sn = 0;
                            $outcome_link = '';
                            $impact_link = '';
                            while ($row = $query_baseline_step1->fetch()) {
                              $outcomeid = $row['id'];
                              $projname = $row['projname'];
                              $projid = $row['projid'];
                              $projcode = $row['projcode'];
                              $projstatus = $row['projstatus'];
                              $projstage = $row['projstage'];
                              $outcomeindid = $row['indid'];
                              $projdatecompleted = $row['projdatecompleted'];
                              $responsible = $row['responsible'];
                              $resultstype = 2;


                              $query_outcome_ind = $db->prepare("SELECT * FROM tbl_indicator WHERE indid=:indid");
                              $query_outcome_ind->execute(array(":indid" => $outcomeindid));
                              $rows_outcome_ind = $query_outcome_ind->fetch();
                              $outcomeindicator = $rows_outcome_ind['indicator_name'];
                              $indicatorunitid = $rows_outcome_ind['indicator_unit'];

                              $query_ind_unit = $db->prepare("SELECT * FROM tbl_measurement_units WHERE id=:unitid");
                              $query_ind_unit->execute(array(":unitid" => $indicatorunitid));
                              $rows_ind_unit = $query_ind_unit->fetch();
                              $indicatorunit = $rows_ind_unit['unit'];


                              if ($projstage == 9) {
                                $stagetype = "Baseline";
                              } else {
                                $stagetype = "Endline";
                              }
                              $today = date('Y-m-d');

                              $query_due_date = $db->prepare("SELECT * FROM tbl_project_workflow_stage_timelines WHERE stage=1 and category=:baseline");
                              $query_due_date->execute(array(":baseline" => $stagetype));
                              $count_due_date = $query_due_date->rowCount();
                              $rows_due_date = $query_due_date->fetch();
                              $days = $rows_due_date['time']; // 30

                              $query_outcome_date = $db->prepare("SELECT * FROM tbl_project_expected_outcome_details WHERE id=:outcomeid");
                              $query_outcome_date->execute(array(":outcomeid" => $outcomeid));
                              $count_outcome_date = $query_outcome_date->rowCount();
                              $rows_outcome_date = $query_outcome_date->fetch();
                              $survey_date = '';

                              $date_changed = $rows_outcome_date['date_changed'];
                              $date_added = $rows_outcome_date['date_added'];
                              $endline_timing = $rows_outcome_date['evaluation_frequency'];

                              if ($projstage == 9) {
                                if ($date_changed != NULL) {
                                  $survey_date = $date_changed;
                                } else {
                                  $survey_date = $date_added;
                                }
                                $date_due  = date('Y-m-d', strtotime($survey_date . ' + ' . $days . ' days'));
                              } else {
                                $survey_date = $projdatecompleted;
                                $date_due  = date('Y-m-d', strtotime($survey_date . ' + ' . $endline_timing . ' years'));
                              }

                              $duedate = date_create($date_due);
                              $due_date = date_format($duedate, "d M Y");

                              $query_rs_survey = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE projid=:projid and resultstypeid = :resultstypeid and status=1");
                              $query_rs_survey->execute(array(":projid" => $projid, ":resultstypeid" => $outcomeid));
                              $row_rs_survey = $query_rs_survey->fetch();
                              $totalrows_rs_survey = $query_rs_survey->rowCount();
                              $status = [];
                              $form_name = [];
                              //$evaluation_responsible = $permissions->verify_created_by($responsible);

                              if ($totalrows_rs_survey > 0) {
                                do {
                                  $form_name[]  = trim($row_rs_survey['form_name']);
                                } while ($row_rs_survey = $query_rs_survey->fetch());
                              }
                              $resultsoc = base64_encode("results{$outcomeid}");


                              if (in_array($stagetype, $form_name)) {
                                $query_survey_form = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE projid=:projid and form_name=:formname and status = 1");
                                $query_survey_form->execute(array(":projid" => $projid, ":formname" => $stagetype));
                                $rows_survey_form = $query_survey_form->fetch();
                                $form_id = $rows_survey_form["id"];
                                $duedate = $rows_survey_form["startdate"];
                                $duedate = date_create($duedate);
                                $due_date = date_format($duedate, "d M Y");

                                $surveyform = base64_encode("surveyform{$form_id}");
                                $outcome_link = '
                                <li>
                                  <a type="button" href="preview-project-survey-form?resultstype=' . $resultstype . '&resultstypeid=' . $resultsoc . '" >
                                    <i class="glyphicon glyphicon-file"></i>
                                    Form preview
                                  </a>
                                </li>';

                                if ($rows_survey_form) {
                                  $outcome_link .=
                                    '<li>
                                    <a type="button" href="deploy-survey-form?formid=' . $surveyform . '" >
                                      <i class="fa fa-telegram">
                                      </i>
                                      Deploy Form
                                    </a>
                                  </li>';
                                }
                              } else {
                                $outcome_link = '
                                  <li>
                                    <a type="button" href="create-project-survey-form?resultstype=' . $resultstype . '&resultstypeid=' . $resultsoc . '" >
                                      <i class="glyphicon glyphicon-file"></i>
                                      Form Details
                                    </a>
                                  </li>';
                              }

                              $active = '';
                              if ($today  < $duedate) {
                                $active = "<label class='label label-primary'>Pending</label>";
                              } else {
                                $active = "<label class='label label-danger'>Behind Schedule</label>";
                              }


                              $query_survey = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE resultstypeid=:resultstypeid and status>1");
                              $query_survey->execute(array(":resultstypeid" => $outcomeid));
                              $totalrows_survey = $query_survey->rowCount();

                              $survey_form = [];

                              while ($row_survey = $query_survey->fetch()) {
                                $survey_form[]  = trim($row_survey['form_name']);
                              }

                              if ($projstage == 9) {
                                $resultstagetype = "Baseline";
                              } else {
                                $resultstagetype = "Endline";
                              }

                              if (!in_array($stagetype, $survey_form)) {

								  if ($projstage == 9) {
									$sn++;
									echo '
									<tr>
									  <td style="width:3%">' . $sn . '</td>
									  <td style="width:20%">' . $outcomeindicator . '</td>
									  <td style="width:35%">' . $projname . '</td>
									  <td style="width:15%">' . $resultstagetype . '</td>
									  <td style="width:10%">' . $due_date . '</td>
									  <td style="width:10%">' . $active . '</td>
									  <td style="width:7%">
										<div class="btn-group">
										  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Options <span class="caret"></span>
										  </button>
										  <ul class="dropdown-menu">
											' . $outcome_link . '
										  </ul>
										</div>
									  </td>
									</tr>';
								  }else{
									  if ($projstatus == 5) {
										$sn++;
										echo '
										<tr>
										  <td style="width:3%">' . $sn . '</td>
										  <td style="width:20%">' . $outcomeindicator . '</td>
										  <td style="width:35%">' . $projname . '</td>
										  <td style="width:15%">' . $resultstagetype . '</td>
										  <td style="width:10%">' . $due_date . '</td>
										  <td style="width:10%">' . $active . '</td>
										  <td style="width:7%">
											<div class="btn-group">
											  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												Options <span class="caret"></span>
											  </button>
											  <ul class="dropdown-menu">
												' . $outcome_link . '
											  </ul>
											</div>
										  </td>
										</tr>';
									  }
								  }
                              }
                            }
                          } else {
                            echo '<td colspan="6">No project requiring survey</td>';
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>

                  <div id="menu1" class="tab-pane fade">
                    <div class="header">
                      <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
                          <tr>
                            <td width="100%" height="35" style="padding-left:5px; background-color:#000; color:#FFF" bgcolor="#000000">
                              <div align="left"><i class="fa fa-file-text-o" aria-hidden="true"></i> Active Survey </strong></div>
                            </td>
                          </tr>
                        </table>
                      </div>
                    </div>
                    <div class="body">
                      <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                          <tr class="bg-light-blue">
                            <th style="width:3%">#</th>
                            <th style="width:20%">Indicator</th>
                            <th style="width:35%">Project Name</th>
                            <th style="width:15%">Survey&nbsp;Type</th>
                            <th style="width:10%">Start Date</th>
                            <th style="width:10%">End Date</th>
                            <th style="width:7%">Number of Submissions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          if ($count_active_survey > 0) {
                            $deploy_counter = 0;
                            while ($rows_active_survey = $query_active_survey->fetch()) {
                              $projid = $rows_active_survey['projid'];

                              $query_projects = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid ");
                              $query_projects->execute(array(":projid" => $projid));
                              $count_projects = $query_projects->rowCount();
                              $rows_projects = $query_projects->fetch();

                              if ($count_projects > 0) {
                                $projname = $rows_projects['projname'];
                                $startdate = date_format(date_create($rows_active_survey['startdate']), "d M Y");
                                $enddate = date_format(date_create($rows_active_survey['enddate']), "d M Y");
                                $resultstypeid = $rows_active_survey['resultstypeid'];
                                $resultstype = $rows_active_survey['resultstype'];
                                $status = $rows_active_survey['status'];
                                $form_name = $rows_active_survey['form_name'];
                                $form_id = $rows_active_survey['id'];
                                $indid = $rows_active_survey['indid'];
                                $projstatus = $rows_projects['projstatus'];
                                $projdate = date('d-m-Y');

                                $query_count = $db->prepare("SELECT * FROM tbl_project_evaluation_submission WHERE formid=:formid");
                                $query_count->execute(array("formid" => $form_id));
                                $count_count = $query_count->rowCount();
                                $rows_count = $query_count->fetch();

                                $query_outcome_ind = $db->prepare("SELECT * FROM tbl_indicator i left join tbl_measurement_units u on u.id=i.indicator_unit WHERE indid=:indid");
                                $query_outcome_ind->execute(array(":indid" => $indid));
                                $rows_outcome_ind = $query_outcome_ind->fetch();
                                $outcomeindicator = $rows_outcome_ind['indicator_name'];

                                $deploy_counter++;

                                echo '
                                <tr>
                                  <td style="width:3%">' . $deploy_counter . '</td>
                                  <td style="width:20%">' . $outcomeindicator . '</td>
                                  <td style="width:35%">' . $projname . '</td>
                                  <td style="width:15%">' . $form_name . '</td>
                                  <td style="width:10%">' . $startdate . '</td>
                                  <td style="width:10%">' . $enddate . '</td>
                                  <td style="width:7%">
                                    <a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="more(' . $form_id . ')">
                                      <span class="badge bg-purple" id="active_count' . $form_id . '">
                                        ' . $count_count . '
                                      </span>
                                    </a>
                                  </td>
                                </tr>';
                              }
                            }
                          } else {
                            echo '<td colspan="6">No active survey</td>';
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>

                  <div id="menu2" class="tab-pane fade">
                    <div class="header">
                      <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
                          <tr>
                            <td width="100%" height="35" style="padding-left:5px; background-color:#000; color:#FFF" bgcolor="#000000">
                              <div align="left"><i class="fa fa-file-text-o" aria-hidden="true"></i> Surveys Data</strong></div>
                            </td>
                          </tr>
                        </table>
                      </div>
                    </div>
                    <div class="body">
                      <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                          <tr class="bg-light-green">
                            <th style="width:3%">#</th>
                            <th style="width:20%">Indicator</th>
                            <th style="width:35%">Project Name</th>
                            <th style="width:12%">Survey&nbsp;Type</th>
                            <th style="width:10%">Start Date</th>
                            <th style="width:10%">End Date</th>
                            <th style="width:10%">Number of Submissions</th>
                          </tr>
                        </thead>
                        <tbody>
							<?php
						      //============================= SURVEYS DATA ==============================================

							$query_survey_data_inner = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE resultstype = 2 AND status=3 and created_by=:responsible ORDER BY id ASC");
							$query_survey_data_inner->execute(array(":responsible" => $user_name));
							if ($designation == 1) {
								$query_survey_data_inner = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE resultstype = 2 AND status=3 ORDER BY id ASC");
								$query_survey_data_inner->execute();
							}
							$count_survey_data_inner = $query_survey_data_inner->rowCount();
							if ($count_survey_data_inner > 0) {
								$deploy_counter = 0;
								while ($rows_survey_data = $query_survey_data_inner->fetch()) {
									$projid = $rows_survey_data['projid'];
									$resultstypeid = $rows_survey_data['resultstypeid'];
									$query_impact_evaluation_concluded = $db->prepare("SELECT * FROM tbl_survey_conclusion WHERE resultstype=2 AND resultstypeid=:resultstypeid AND projid=:projid");
									$query_impact_evaluation_concluded->execute(array(":resultstypeid" => $resultstypeid, ":projid" => $projid));
									$rows_impact_evaluation_concluded = $query_impact_evaluation_concluded->rowCount();

									if ($rows_impact_evaluation_concluded == 0) {
										$query_projects = $db->prepare("SELECT * FROM tbl_projects WHERE  projid=:projid ");
										$query_projects->execute(array(":projid" => $projid));
										$count_projects = $query_projects->rowCount();
										$rows_projects = $query_projects->fetch();
										$projname = $rows_projects['projname'];
										$startdate = date_format(date_create($rows_survey_data['startdate']), "d M Y");
										$enddate = date_format(date_create($rows_survey_data['enddate']), "d M Y");
										$resultstype = $rows_survey_data['resultstype'];
										$status = $rows_survey_data['status'];
										$form_name = $rows_survey_data['form_name'];
										$form_id = $rows_survey_data['id'];
										$projstatus = $rows_projects['projstatus'];
										$indid = $rows_survey_data['indid'];
										$projdate = date('d-m-Y');

										$query_count = $db->prepare("SELECT * FROM tbl_project_evaluation_submission WHERE projid=:projid AND formid=:formid");
										$query_count->execute(array(":projid" => $projid, ":formid" => $form_id));
										$count_count = $query_count->rowCount();
										$rows_count = $query_count->fetch();

										$query_outcome_ind = $db->prepare("SELECT * FROM tbl_indicator WHERE indid=:indid");
										$query_outcome_ind->execute(array(":indid" => $indid));
										$rows_outcome_ind = $query_outcome_ind->fetch();
										$outcomeindicator = $rows_outcome_ind['indicator_name'];
										$frmid = base64_encode("surveydata{$form_id}");
										$deploy_counter++;
										echo '
										<tr>
											<td style="width:3%">' . $deploy_counter . '</td>
											<td style="width:20%">' . $outcomeindicator . '</td>
											<td style="width:35%">' . $projname . '</td>
											<td style="width:12%">' . $form_name . '</td>
											<td style="width:10%">' . $startdate . '</td>
											<td style="width:10%">' . $enddate . '</td>
											<td style="width:10%">
											  <a href="view-survey-data?frm=' . $frmid . '">
												<span class="badge bg-purple">
												  ' . $count_count . '
												</span>
											  </a>
											</td>
										</tr>';
									}
								}
							}
							?>
                        </tbody>
                      </table>
                    </div>
                  </div>

                  <div id="menu3" class="tab-pane fade">
                    <div class="header">
                      <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
                          <tr>
                            <td width="100%" height="35" style="padding-left:5px; background-color:#000; color:#FFF" bgcolor="#000000">
                              <div align="left"><i class="fa fa-file-text-o" aria-hidden="true"></i> Survey Conclusion</strong></div>
                            </td>
                          </tr>
                        </table>
                      </div>
                    </div>
                    <div class="body">
                      <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                          <tr class="bg-green">
                            <th style="width:3%">#</th>
                            <th style="width:20%">Indicator</th>
                            <th style="width:35%">Project Name</th>
                            <th style="width:12%">Survey&nbsp;Type</th>
                            <th style="width:10%">Start Date</th>
                            <th style="width:10%">End Date</th>
                            <th style="width:10%">Number of Submissions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          if ($count_survey_conclusion > 0) {
                            $deploy_counter = 0;
                            while ($rows_survey_conclusion = $query_survey_conclusion->fetch()) {
                              $projid = $rows_survey_conclusion['projid'];
                              $query_projects = $db->prepare("SELECT * FROM tbl_projects WHERE  projid=:projid ");
                              $query_projects->execute(array(":projid" => $projid));
                              $count_projects = $query_projects->rowCount();
                              $rows_projects = $query_projects->fetch();


                              if ($count_projects > 0) {
                                $projname = $rows_projects['projname'];
                                $startdate = date_format(date_create($rows_survey_conclusion['startdate']), "d M Y");
                                $enddate = date_format(date_create($rows_survey_conclusion['enddate']), "d M Y");
                                $status = $rows_survey_conclusion['status'];
                                $form_name = $rows_survey_conclusion['form_name'];
                                $form_id = $rows_survey_conclusion['id'];
                                $indid = $rows_survey_conclusion['indid'];
                                $outcomeid = $rows_survey_conclusion['resultstypeid'];
                                $samplesize = $rows_survey_conclusion['sample_size'];
                                $resultstype = $rows_survey_conclusion['resultstype'];
                                $projstatus = $rows_projects['projstatus'];
                                $locations = count(explode(",", $rows_projects['projlga']));
                                $projdate = date('d-m-Y');

                                $query_count = $db->prepare("SELECT * FROM tbl_project_evaluation_submission WHERE projid=:projid AND formid=:formid");
                                $query_count->execute(array(":projid" => $projid, ":formid" => $form_id));
                                $count_count = $query_count->rowCount();
                                $rows_count = $query_count->fetch();

                                $totalsamplesize = $locations * $samplesize;

                                $query_outcome_ind = $db->prepare("SELECT * FROM tbl_indicator i left join tbl_measurement_units u on u.id=i.indicator_unit WHERE indid=:indid");
                                $query_outcome_ind->execute(array(":indid" => $indid));
                                $rows_outcome_ind = $query_outcome_ind->fetch();
                                $outcomeindicator = $rows_outcome_ind['indicator_name'];

                                //$frmid = base64_encode("frm_id{$form_id}");
                                $outcomeidencoded = base64_encode("resultssecdata{$outcomeid}");

                                if ($totalsamplesize == $count_count) {

                                  $query_outcome_evaluated = $db->prepare("SELECT * FROM tbl_survey_conclusion WHERE resultstype=2 AND resultstypeid=:resultstypeid");
                                  $query_outcome_evaluated->execute(array(":resultstypeid" => $outcomeid));
                                  $rows_outcome_evaluated = $query_outcome_evaluated->rowCount();

                                  if ($rows_outcome_evaluated == 0) {
                                    $deploy_counter++;
                                    echo '
										<tr>
										  <td style="width:3%">' . $deploy_counter . '</td>
										  <td style="width:20%">' . $outcomeindicator . '</td>
										  <td style="width:35%">' . $projname . '</td>
										  <td style="width:12%">' . $form_name . '</td>
										  <td style="width:10%">' . $startdate . '</td>
										  <td style="width:10%">' . $enddate . '</td>
										  <td style="width:10%">
											<a type="button" class="badge bg-purple" href="survey-conclusion.php?results=' . $outcomeidencoded . '&resultstype=2">
											  Conclude
											</a>
										  </td>
										</tr>';
                                  }
                                }
                              }
                            }
                          } else {
                            echo '<td colspan="5">No concluded survey</td>';
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>


              <!-- ============================================================== -->
              <!-- End PAge Content -->
              <!-- ============================================================== -->
            </div>
          </div>
        </div>
      </div>
  </section>
  <!-- end body  -->
  <!-- Start Item more -->
  <div class="modal fade" tabindex="-1" role="dialog" id="moreItemModal">
    <div class="modal-dialog  modal-lg">
      <div class="modal-content">
        <div class="modal-header" style="background-color:#03A9F4">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info"></i> Submissions</h4>
        </div>
        <div class="modal-body" id="moreinfo">
        </div>
        <div class="modal-footer">
          <div class="col-md-12 text-center">
            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Close</button>
          </div>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>
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
        url: "assets/processor/add-baseline-processor",
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
      url: "assets/processor/add-baseline-processor",
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