<?php
$id = isset($_GET['monitor_id']) && !empty($_GET['monitor_id']) ? $_GET['monitor_id'] : header("location:projects-monitoring.php");
require('includes/head.php'); 

if ($permission) {
  try {
    $pageTitle = "Add Monitoring Data";
    function error_msg($msg)
    {
      $results = "
        <script type=\"text/javascript\">
          swal({
          title: \"Error!\",
          text: \" $msg \",
          type: 'Danger',
          timer: 10000,
          icon:'error',
          showConfirmButton: false });
        </script>";
      return $results;
    }
    function success_msg($msg)
    {
      return "
        <script type=\"text/javascript\">
            swal({
              title: \"Success!\",
              text: \" $msg\",
              type: 'Success',
              timer: 5000,
              icon:'success',
              showConfirmButton: false
            });
              setTimeout(function(){
              window.location.href = 'projects-monitoring';
              }, 5000);
        </script>";
    }


    function incrementalHash($len = 5)
    {
      $charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
      $base = strlen($charset);
      $result = '';

      $now = explode(' ', microtime())[1];
      while ($now >= $base) {
        $i = $now % $base;
        $result = $charset[$i] . $result;
        $now /= $base;
      }
      return substr($result, -5);
    }


    function update_output_code($projid, $outputid, $mne_code)
    {
      global $db;
      $query_tkMs = $db->prepare("SELECT * FROM tbl_milestone WHERE projid=:projid AND outputid=:outputid  AND (status=4 OR status=11) ORDER BY msid DESC");
      $query_tkMs->execute(array(":projid" => $projid, ":outputid" => $outputid));
      $row_tkMs = $query_tkMs->fetchAll();
      $total_tkMs = $query_tkMs->rowCount();

      $data_handler = array();
      if ($total_tkMs > 0) {
        foreach ($row_tkMs as $milestones) {
          $msid = $milestones["msid"];
          $query_monitored = $db->prepare("SELECT * FROM tbl_monitoring WHERE mne_code=:mne_code AND milestone_id=:msid");
          $query_monitored->execute(array(":mne_code" => $mne_code, ":msid" => $msid));
          $total_monitored = $query_monitored->rowCount();
          $data_handler[] = ($total_monitored > 0) ? true : false;
        }
      }

      if (!in_array(false, $data_handler)) {
        $next_monitoring_date = next_monitoring_date($projid, $outputid);
        $new_mne_code = incrementalHash() . $projid . $outputid;
        $sqlUpdate = $db->prepare("UPDATE tbl_project_outputs_mne_details SET next_monitoring_date=:next_monitoring_date, mne_code = :mne_code WHERE outputid=:output_id");
        $sqlUpdate->execute(array(':next_monitoring_date' => $next_monitoring_date, ':mne_code' => $new_mne_code, ':output_id' => $outputid));
      }

      return true;
    }


    function next_monitoring_date($projid, $outputid)
    {
      global $db;
      $query_monitoringfreq =  $db->prepare("SELECT opid, frequency, days, outputid FROM tbl_project_outputs_mne_details o inner join tbl_datacollectionfreq f on f.fqid=o.monitoring_frequency WHERE o.projid = :projid AND o.outputid = :outputid");
      $query_monitoringfreq->execute(array(":projid" => $projid, ":outputid" => $outputid));
      $row_monitoringfreq = $query_monitoringfreq->fetch();
      $totalRows_monitoringfreq = $query_monitoringfreq->rowCount();

      $next_mon_date = "";
      if ($totalRows_monitoringfreq > 0) {
        $monfreq = $row_monitoringfreq['days'];
        $next_monitoring_date = $row_monitoringfreq['next_monitoring_date'];
        $next_mon_date = date("Y-m-d", strtotime($next_monitoring_date . ' + ' . $monfreq));
      }
      return $next_mon_date;
    }



    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "pmfrm")) {
      $projid = $_POST["projid"];
      $output_id = $_POST["output_id"];
      $milestone_id = $_POST["milestone"];
      $mne_code = $_POST["mainformid"];
      $form_id = $_POST["opformid"];
      $level3 = $_POST['location'];
      $progress = $_POST['outputprogress'];
      $currentdate = date("Y-m-d");
      $origin = "Monitoring";
      $latitude = 100;
      $longitude = 100;
      $geoerror = 1111;

      if (trim($_POST["mainformid"]) !== '' || !empty(trim($_POST["mainformid"]))) {
        $insertSQL = $db->prepare("INSERT INTO tbl_monitoring (projid, output_id,milestone_id,mne_code,formid,projlatitude,projlongitude,projgeopositionerror,level3,progress,adate,user_name) VALUES (:projid,:outputid,:milestoneid,:mne_code,:formid, :projlat, :projlong, :projgeopositionerror,:level3, :progress, :adate, :username)");
        $Result1  = $insertSQL->execute(array(":projid" => $projid, ":outputid" => $output_id, ":milestoneid" => $milestone_id, ":mne_code" => $mne_code, ":formid" => $form_id, ':projlat' => $latitude, ':projlong' => $longitude, ':projgeopositionerror' => $geoerror, ":level3" => $level3, ":progress" => $progress, ":adate" => $currentdate, ":username" => $user_name));

        if ($Result1) {
          $last_id = $db->lastInsertId();
          if (isset($_POST["issuedescription"])) {
            $nmb = count($_POST["issuedescription"]);
            for ($k = 0; $k < $nmb; $k++) {
              if (trim($_POST["issuedescription"][$k]) !== '' || !empty(trim($_POST["issuedescription"][$k]))) {
                $SQLinsert = $db->prepare("INSERT INTO tbl_projissues (milestone_id,monitoringid, formid, projid,level3, origin, opid, risk_category, observation, created_by, date_created) VALUES (:milestone_id,:monitoringid, :formid, :projid,:level3, :origin, :opid, :riskcat, :obsv, :user, :date)");
                $Rst  = $SQLinsert->execute(array(":milestone_id" => $milestone_id, ":monitoringid" => $last_id, ":formid" => $form_id, ":projid" => $projid, ':level3' => $level3, ':origin' => $origin, ':opid' => $output_id, ':riskcat' => $_POST['issue'][$k], ':obsv' => $_POST['issuedescription'][$k], ':user' => $user_name, ':date' => $currentdate));
              }
            }
          }

          if (isset($_POST['observation'])) {
            $observ = $_POST['observation'];
            $SQLinsert = $db->prepare("INSERT INTO tbl_monitoring_observations (projid, monitoringid, formid, opid, observation, location, created_by, date_created) VALUES (:projid, :monitoringid, :formid, :opid, :observ, :location, :user, :date)");
            $Rst  = $SQLinsert->execute(array(":projid" => $projid, ":monitoringid" => $last_id, ":formid" => $form_id, ':opid' => $output_id, ':observ' => $observ, ':location' => $level3, ':user' => $user_name, ':date' => $currentdate));
          }


          $stage = 10;
          $myprojid = $projid;
          $filecategory = "Monitoring";
          $count = count($_POST["attachmentpurpose"]);

          for ($cnt = 0; $cnt < $count; $cnt++) {
            if (!empty($_FILES['monitorattachment']['name'][$cnt])) {
              $purpose = $_POST["attachmentpurpose"][$cnt];
              $filename = basename($_FILES['monitorattachment']['name'][$cnt]);
              $ext = substr($filename, strrpos($filename, '.') + 1);
              if (($ext != "exe") && ($_FILES["monitorattachment"]["type"][$cnt] != "application/x-msdownload")) {
                $newname = $projid . "-" . $filecategory . "-" . time() . "-" . $filename;
                if ($ext == "jpg" || $ext == "png" || $ext == "jpeg") {
                  $filepath = "uploads/monitoring/photos/" . $newname;
                  if (!file_exists($filepath)) {
                    if (move_uploaded_file($_FILES['monitorattachment']['tmp_name'][$cnt], $filepath)) {
                      $qry2 = $db->prepare("INSERT INTO tbl_project_photos (projid, monitoringid, form_id, opid, projstage, fcategory, filename, ftype, description, floc, uploaded_by, date_uploaded) VALUES (:projid,:monitoringid,:formid,:opid, :stage, :fcat, :filename, :ftype, :desc, :floc, :user, :date)");
                      $data = $qry2->execute(array(':projid' => $projid, ':monitoringid' => $last_id, ':formid' => $form_id, ":opid" => $output_id, ':stage' => $stage, ':fcat' => $filecategory, ':filename' => $newname, ":ftype" => $ext, ":desc" => $purpose, ":floc" => $filepath, ':user' => $user_name, ':date' => $currentdate));
                    } else {
                      echo "Could not move the file";
                    }
                  } else {
                    $msg = "Sorry the filepath does not exist";
                    $results = error_msg($msg);
                  }
                } else {
                  $filepath = "uploads/monitoring/other-files/" . $newname;
                  if (!file_exists($filepath)) {
                    if (move_uploaded_file($_FILES['monitorattachment']['tmp_name'][$cnt], $filepath)) {
                      $qry2 = $db->prepare("INSERT INTO tbl_files (projid, projstage, monitoringid, form_id, filename, ftype, floc, fcategory, reason, uploaded_by, date_uploaded)  VALUES (:projid, :projstage, :monitoringid, :formid, :filename, :ftype, :floc, :fcat, :desc, :user, :date)");
                      $result =  $qry2->execute(array(':projid' => $projid, ':projstage' => $stage, ':monitoringid' => $last_id, ':formid' => $form_id, ':filename' => $newname, ":ftype" => $ext, ":floc" => $filepath, ':fcat' => $filecategory, ":desc" => $purpose, ':user' => $user_name, ':date' => $currentdate));
                    } else {
                      echo "could not move the file ";
                    }
                  } else {
                    $msg = 'File you are uploading already exists, try another file!!';
                    $results = error_msg($msg);
                  }
                }
              } else {
                $msg = 'This file type is not allowed, try another file!!';
                $results = error_msg($msg);
              }
            } else {
              $msg = 'You have not attached any file!!';
              $results = error_msg($msg);
            }
          }
        }

        $update_data = update_output_code($projid, $output_id, $mne_code);
        if ($update_data) {
          $results  = success_msg("Successfully monitored the Milestone");
        }
      }
    }

    $output_id = base64_decode($id);
    $query = "SELECT * FROM tbl_projects p INNER JOIN tbl_project_outputs_mne_details d ON d.projid = p.projid WHERE projstage=10 AND (projstatus=4 OR projstatus=11)  AND d.responsible= '$user_name' AND outputid=:output_id  ORDER BY d.next_monitoring_date ASC";
    if ($role_group == 4 && $designation == 1) {
      $query = 'SELECT * FROM tbl_projects p INNER JOIN tbl_project_outputs_mne_details d ON d.projid = p.projid WHERE projstage=10 AND (projstatus=4 OR projstatus=11) AND outputid=:output_id ORDER BY d.next_monitoring_date ASC';
    }

    $query_projects = $db->prepare($query);
    $query_projects->execute(array(":output_id" => $output_id));
    $row_projects = $query_projects->fetch();
    $totalrow_projects = $query_projects->rowCount();

    if ($totalrow_projects > 0) {
      $projname = $row_projects['projname'];
      $projstatus = $row_projects['projstatus'];
      $output_id = $row_projects['outputid'];
      $indid = $row_projects['indicator'];
      $projstatus = $row_projects['projstatus'];
      $projid = $row_projects['projid'];
      $mainformid = $row_projects['mne_code'];

      // project status
      $query_rsstatus = $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
      $query_rsstatus->execute(array(":projstatus" => $projstatus));
      $row_rsstatus = $query_rsstatus->fetch();
      $totalRows_rsstatus = $query_rsstatus->rowCount();
      $status_of_project = $row_rsstatus['statusname'];

      $query_OutputData = $db->prepare("SELECT * FROM tbl_project_details b INNER JOIN tbl_progdetails g ON g.id = b.outputid WHERE b.id=:id");
      $query_OutputData->execute(array(":id" => $output_id));
      $countrows_OutpuData = $query_OutputData->rowCount();
      $row_OutputData =  $query_OutputData->fetch();
      $output = $countrows_OutpuData > 0 ? $row_OutputData['output'] : "";

      $query_rsIndicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid = :indid");
      $query_rsIndicator->execute(array(":indid" => $indid));
      $row_rsIndicator = $query_rsIndicator->fetch();
      $totalRows_rsIndicator = $query_rsIndicator->rowCount();
      $indicator = $row_rsIndicator['indicator_name'];
      $unitid = $row_rsIndicator['indicator_unit'];

      $query_rsmeasurement = $db->prepare("SELECT * FROM tbl_measurement_units WHERE id = :unitid");
      $query_rsmeasurement->execute(array(":unitid" => $unitid));
      $row_rsmeasurement = $query_rsmeasurement->fetch();
      $totalRows_rsmeasurement = $query_rsmeasurement->rowCount();
      $unit = $row_rsmeasurement['unit'];
    } else {
      return;
    }


    function get_locations($projid, $outputid)
    {
      global $db;
      $query_locs =  $db->prepare("SELECT s.id, s.state FROM tbl_state s INNER JOIN tbl_output_disaggregation o ON o.outputstate =s.id WHERE o.projid=:projid AND o.outputid=:outputid ");
      $query_locs->execute(array(":projid" => $projid, ":outputid" => $outputid));
      $states = $query_locs->fetchAll();
      $total_tkMs = $query_locs->rowCount();
      $options = "";
      if ($total_tkMs > 0) {
        foreach ($states as $state) {
          $options .= '<option value="' . $state["id"] . '">' . $state["state"] . '</option>';
        }
      }
      return $options;
    }

    function get_milestones($projid, $outputid, $mne_code)
    {
      global $db;
      $query_tkMs = $db->prepare("SELECT * FROM tbl_milestone WHERE projid=:projid AND outputid=:outputid  AND (status=4 OR status=11) ORDER BY msid DESC");
      $query_tkMs->execute(array(":projid" => $projid, ":outputid" => $outputid));
      $row_tkMs = $query_tkMs->fetchAll();
      $total_tkMs = $query_tkMs->rowCount();

      $options = "";
      if ($total_tkMs > 0) {
        foreach ($row_tkMs as $milestones) {
          $msid = $milestones["msid"];
          $query_monitored = $db->prepare("SELECT * FROM tbl_monitoring WHERE mne_code=:mne_code AND milestone_id=:msid");
          $query_monitored->execute(array(":mne_code" => $mne_code, ":msid" => $msid));
          $total_monitored = $query_monitored->rowCount();

          if ($total_monitored == 0) {
            $options .= '<option value="' . $msid . '">' . $milestones["milestone"] . '</option>';
          }
        }
      }
      return $options;
    }

    $pmtid = incrementalHash();

    function risk_category_select_box()
    {
      global $db, $projid, $output_id;
      $risk = '';
      $query_allrisks = $db->prepare("SELECT C.rskid, C.category FROM tbl_projrisk_categories C INNER JOIN tbl_projectrisks R ON C.rskid=R.rskid where R.projid = :projid and R.outputid = :opid and R.type=3 ORDER BY R.id ASC");
      $query_allrisks->execute(array(":projid" => $projid, ":opid" => $output_id));
      $rows_allrisks = $query_allrisks->fetchAll();
      foreach ($rows_allrisks as $row) {
        $risk .= '<option value="' . $row["rskid"] . '">' . $row["category"] . '</option>';
      }
      return $risk;
    }

    // project percentage progress
    $query_rsMlsProg =  $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid = :projid");
    $query_rsMlsProg->execute(array(":projid" => $projid));
    $row_rsMlsProg = $query_rsMlsProg->fetch();
    $prjprogress = $row_rsMlsProg["mlprogress"] / $row_rsMlsProg["nmb"];
    $percent2 = round($prjprogress, 2);
  } catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $result;
  }
?>
  <!-- <link rel="stylesheet" href="assets/custom-css/add-monitoring-data.css"> -->
  <style>
    .bar {
      background: #CDDC39;
      height: 24px;
      -moz-border-radius: 0px;
      -webkit-border-radius: 0px;
      width: <?php echo $percent2; ?>%;
    }

    .cornflowerblue {
      background-color: CornflowerBlue;
      box-shadow: inset 0px 0px 6px 2px rgba(255, 255, 255, .3);
      width: <?php echo $percent2; ?>%;
    }
  </style>
  <section class="content">
    <div class="container-fluid">
      <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px;padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
        <h4 class="contentheader">
          <?= $icon ?>
          <?php echo $pageTitle ?>
          <div class="btn-group" style="float:right">
            <button onclick="history.go(-1)" class="btn bg-orange waves-effect pull-right" style="margin-right: 10px">
              Go Back
            </button>
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
              <form id="pmfrm" name="pmfrm" method="POST" action="" onsubmit="return validateForm()" style="width:100%" enctype="multipart/form-data" autocomplete="off">
                <fieldset class="scheduler-border">
                  <legend class="scheduler-border" style="background-color:#c7e1e8;  border:#CCC thin dashed; border-radius:3px">Project Details</legend>
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Project Name:</label>
                    <input type="text" class="form-control" value="<?php echo $projname; ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px" />
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Output Name :</label>
                    <div class="form-line">
                      <input type="text" class="form-control" value="<?php echo $output; ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px" />
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <label>Output <?= $level3labelplural ?> *:</label>
                    <div class="form-line">
                      <select name="location" id="location" onchange="get_op_milestones()" class="form-control topic" data-live-search="true" style="border:#CCC thin solid; border-radius:5px" required>
                        <option value="" selected="selected" class="selection">... Select ...</option>
                        <?= get_locations($projid, $output_id) ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <label>Milestones *:</label>
                    <div class="form-line">
                      <select name="milestone" id="milestone" onclick="get_tasks()" class="form-control topic" data-live-search="true" style="border:#CCC thin solid; border-radius:5px" required>
                        <option value="" selected="selected" class="selection">... First select Milestone location ...</option>
                        <? //= get_milestones($projid, $output_id, $mainformid) 
                        ?>
                      </select>
                    </div>
                  </div>
                </fieldset>
                <fieldset class="scheduler-border">
                  <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px" align="left">
                    <a class="btn btn-link tasks_toogle" data-toggle="collapse" href="#tasks" role="button" aria-expanded="false" aria-controls="tasks">
                      <i class="fa fa-plus-square" style="font-size:16px"></i> Project Output (Tasks/Activities Progress)
                    </a>
                  </legend>
                  <!-- Task Checklist Questions -->
                  <div class="row clearfix collapse" id="tasks">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <div class="card">
                        <div class="body">
                          <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="tasks_table" style="width: 100%;">
                                  <thead>
                                    <tr id="colrow">
                                      <td width="3%">SN</td>
                                      <td width="30%">Tasks (In Progress)</td>
                                      <td width="30%">Milestone Name</td>
                                      <td width="15%">Current Status</td>
                                      <td width="8%">Previous Record</td>
                                      <td width="9%">Checklist</td>
                                      <td width="5%">Score (%)</td>
                                    </tr>
                                  </thead>
                                  <tbody id="tasks_table_body">
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </fieldset>

                <fieldset class="scheduler-border">
                  <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px" align="left">
                    <a class="btn btn-link obs" data-toggle="collapse" href="#obs" role="button" aria-expanded="false" aria-controls="obs">
                      <i class="fa fa-plus-square" style="font-size:16px"></i> Observation(s), Issue(s), and Means of Verification (Files)
                    </a>
                  </legend>
                  <!-- Task Checklist Questions -->
                  <div class="row clearfix collapse" id="obs">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <div class="card" style="margin-bottom:-20px">
                        <div class="body">
                          <h4>OBSERVATIONS</h4>
                          <table class="table table-bordered" id="observations_table" style="width:100%">
                            <thead>
                              <tr>
                                <th style="width:100%">Previous Observations</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>
                                  <!--<div class="form-control" id="previouscomments" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"></div>-->
                                  <input type="text" id="previouscomments" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder="Location and milestone not selected!" readonly>
                                </td>
                              </tr>
                            </tbody>
                            <thead>
                              <tr>
                                <th style="width:100%">Record New Observation</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>
                                  <input type="text" name="observation" class="form-control" placeholder="Enter your observation here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <div class="card" style="margin-bottom:-20px" id="issue_table">
                        <div class="body">
                          <h4>ISSUES</h4>
                          <table class="table table-bordered" style="width:100%">
                            <h5 style="width:100%">Previous Issues</h5>
                            <thead>
                              <tr>
                                <th style="width:2%">#</th>
                                <th style="width:35%">Issue</th>
                                <th style="width:50%">Description</th>
                                <th style="width:13%">Status</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr id="previousissues">
                                <td colspan="4">Location and milestone not selected!</td>
                              </tr>
                            </tbody>
                          </table>

                          <table class="table table-bordered" id="issues_table" style="width:100%">
                            <h5 style="width:100%">Record New Issues</h5>
                            <thead>
                              <tr>
                                <th style="width:2%">#</th>
                                <th style="width:21%">Issue</th>
                                <th style="width:75%">Description</th>
                                <th style="width:2%"><button type="button" name="addplus" onclick="add_issues();" title="Add another question" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>1</td>
                                <td>
                                  <div class="form-line">
                                    <select name="issue[]" id="issue[]" class="form-control topic" data-live-search="true" style="border:#CCC thin solid; border-radius:5px">
                                      <option value="" selected="selected" class="selection">... Select ...</option>
                                      <?php
                                      echo risk_category_select_box();
                                      ?>
                                    </select>
                                  </div>
                                </td>
                                <td>
                                  <input type="text" name="issuedescription[]" id="issuedescription[]" class="form-control" placeholder="Description the issue here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
                                </td>
                                <td></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <div class="card" style="margin-bottom:-20px">
                        <div class="body">
                          <h4>Files/Documents Attachments </h4>
                          <div class="table-responsive">
                            <table class="table table-bordered" id="attachments_table">
                              <tr>
                                <th style="width:2%">#</th>
                                <th style="width:40%">Attachments</th>
                                <th style="width:58%">Attachment Purpose</th>
                                <th style="width:2%"><button type="button" name="addplus" onclick="add_attachment();" title="Add another document" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
                              </tr>
                              <tr>
                                <td>1</td>
                                <td>
                                  <input type="file" name="monitorattachment[]" id="monitorattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
                                </td>
                                <td>
                                  <input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control" placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
                                </td>
                                <td></td>
                              </tr>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Task Checklist Questions -->
                </fieldset>
                <fieldset class="scheduler-border">
                  <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Output Measurements</legend>
                  <div class="card" style="margin-bottom:-20px">
                    <div class="body">
                      <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                            <label>Output Target (<?= $unit ?>): </label>
                            <div class="form-line">
                              <input type="text" id="outputceiling" class="form-control" value="" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px" />
                            </div>
                          </div>
                          <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4">
                            <div class="form-input">
                              <label for="" id="">Cumulative Measurement *:</label>
                              <input type="text" id="cumulative_measurement" value="" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" readonly />
                            </div>
                          </div>
                          <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4">
                            <div class="form-input">
                              <label for="" id="">Previous Measurement *:</label>
                              <input type="text" id="previous" value="" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" readonly />
                            </div>
                          </div>
                          <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4" id="">
                            <label for="" id=""> Current Measurement *:</label>
                            <div class="form-input">
                              <input type="number" min="0" id="outputprogress" name="outputprogress" onchange="validate_output()" onkeyup="validate_output()" placeholder="Enter current progress value" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </fieldset>
                <div class="row clearfix">
                  <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
                    <input type="hidden" name="projid" id="projid" value="<?php echo $projid; ?>" />
                    <input type="hidden" name="issues" id="issues" value="" />
                    <input type="hidden" name="output_id" id="output_id" value="<?php echo $output_id; ?>" />
                    <input type="hidden" name="opformid" id="opformid" value="<?php echo $pmtid; ?>" />
                    <input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
                    <input name="mainformid" type="hidden" id="mainformid" value="<?php echo $mainformid; ?>" />
                    <div class="btn-group">
                      <input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="Submit" />
                    </div>
                    <input type="hidden" name="MM_insert" value="pmfrm" />
                  </div>
                  <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- #START# Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header" style="background-color:#03A9F4">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h3 class="modal-title" align="center">
            <font color="#FFF">Task CheckList </font>
          </h3>
        </div>
        <form class="tagForm" action="savechecklistscore" method="post" id="tag-form" enctype="multipart/form-data">
          <div class="modal-body" id="formcontent">

          </div>
          <div class="modal-footer">
            <div class="col-md-4">
            </div>
            <div class="col-md-4" align="center">
              <input name="submit" type="submit" class="btn btn-success waves-effect waves-light" id="tag-form-submit" value="Assign Score" />
              <input type="hidden" name="user_name" id="user_name" value="<?php echo $user_name; ?>" />
              <input type="hidden" name="level3" id="level3" value="" />
              <input type="hidden" name="formid" id="formid" value="" />
              <input type="hidden" name="mne_code" id="mne_code" value="<?= $mainformid ?>" />
              <input type="hidden" name="milestone_id" id="milestone_id" value="" />
              <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
            </div>
            <div class="col-md-4">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- #END# Modal -->
<?php
} else {
  $results =  restriction();
  echo $results;
}
require('includes/footer.php');
?>
<script src="assets/js/monitoring/index.js"></script>

<script>
  function add_issues() {
    $rowno = $("#issues_table tr").length;
    $rowno = $rowno + 1;
    $listno = $rowno - 1;
    $("#issues_table tr:last").after('<tr id="row' + $rowno + '"><td>' + $listno + '</td><td><div class="form-line"><select name="issue[]" id="issue[]" class="form-control topic" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px"><option value="" selected="selected" class="selection">... Select ...</option><?php echo risk_category_select_box($output_id); ?></select></div></td><td><input type="text" name="issuedescription[]" id="issuedescription[]" class="form-control" placeholder="Description the issue here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" ></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_issues("row' + $rowno + '")><span class="glyphicon glyphicon-minus"></span></button></td></tr>');
  }

  function delete_issues(rowno) {
    $('#' + rowno).remove();
  }

  function add_attachment() {
    $rownm = $("#attachments_table tr").length;
    $rownm = $rownm + 1;
    $attno = $rownm - 1;
    $("#attachments_table tr:last").after('<tr id="rw' + $rownm + '"><td>' + $attno + '</td><td><input type="file" name="monitorattachment[]"  id="monitorattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" /></td><td><input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control"  placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"/></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_attach("rw' + $rownm + '")><span class="glyphicon glyphicon-minus"></span></button></td></tr>');
  }

  function delete_attach(rownm) {
    $('#' + rownm).remove();
  }

  function get_op_milestones() {
    var projid = $("#projid").val();
    var output_id = $("#output_id").val();
    var location = $("#location").val();

    if (projid != "" && output_id != "") {
      if (location != '') {
        $.ajax({
          type: "get",
          url: "ajax/monitoring/index",
          data: {
            get_milestones: "get_milestones",
            projid: projid,
            output_id: output_id,
            location: location,
          },
          dataType: "html",
          success: function(response) {
            if (response) {
              $("#milestone").html(response);
            }
          }
        });
      } else {
        $("#milestone").html('<option value="" selected="selected" class="selection">... Please select a location ...</option>')
        $("#tasks_table_body").html("");
      }
    }
  }

  function get_tasks() {
    var projid = $("#projid").val();
    var output_id = $("#output_id").val();
    var milestone = $("#milestone").val();
    var location = $("#location").val();
    var pmtid = $("#opformid").val();

    if (projid != "" && output_id != "") {
      if (location != '') {
        if (milestone != '') {
          $.ajax({
            type: "get",
            url: "ajax/monitoring/index",
            data: {
              get_tasks: "get_tasks",
              projid: projid,
              output_id: output_id,
              milestone: milestone,
              pmtid: pmtid,
              location: location,
            },
            dataType: "json",
            success: function(response) {
              $("#tasks_table_body").html("");
              $("#outputceiling").val("");
              $("#cumulative_measurement").val("");
              $("#previous").val("");
              $("#issues").val("");
              if (response.success) {
                $("#tasks_table_body").html(response.message);
                $("#outputceiling").val(response.targets.output_target);
                $("#cumulative_measurement").val(response.targets.cumulative_measurement);
                $("#previous").val(response.targets.previous_measurement);
                $("#previouscomments").val(response.targets.previous_observation);
                $("#issues").val(response.issues);
              }
              $("#level3").val(location);
              $("#milestone_id").val(milestone);
              $("#formid").val(pmtid);
            }
          });
        } else {
          $("#tasks_table_body").html("");
        }
      } else {
        $("#tasks_table_body").html("");
      }
    }
  }


  $(document).ready(function() {
    $("#milestone").on("change", function(event) {
      var projid = $("#projid").val();
      var output_id = $("#output_id").val();
      var milestone = $("#milestone").val();
      var location = $("#location").val();

      $.ajax({
        type: "POST",
        url: "ajax/monitoring/index",
        data: {
          get_issues: "get_issues",
          projid: projid,
          output_id: output_id,
          milestone: milestone,
          location: location,
        },
        dataType: "html",
        success: function(data) {
          $("#previousissues").html(data);
        },
        error: function() {
          alert("Error");
        },
      });
      return false;
    });
  });

  function GetTaskChecklist(tkid = null, pmtid = null) {
    if (tkid && pmtid) {
      var pmtid = $("#opformid").val();
      var milestone = $("#milestone").val();
      var level3 = $("#location").val();
      $.ajax({
        type: "post",
        url: "assets/processor/gettaskchecklist",
        data: {
          tskid: tkid,
          pmtid: pmtid,
          milestone: milestone,
          level3: level3,
        },
        success: function(data) {
          $("#formcontent").html(data);
          $("#myModal").modal({
            backdrop: "static"
          });
        },
      });
    }
  }

  $(document).ready(function() {
    enableSubmit();
    $("#tag-form").on("submit", function(event) {
      event.preventDefault();
      var taskscore = $("#tskscid").val();
      var form_data = $(this).serialize();
      $.ajax({
        type: "POST",
        url: "assets/processor/savechecklistscore",
        data: form_data,
        dataType: "json",
        success: function(data) {
          $("#" + taskscore).val(data);
          $("#btn" + taskscore).html("Edit Score");
          $("#tag-form")[0].reset();
          $("#myModal").modal("hide");
          enableSubmit();
        },
        error: function() {
          alert("Error");
        },

      });
      return false;
    });
  });

  function validate_output() {
    var current = $("#outputprogress").val();
    var cumulative = $("#cumulative_measurement").val();
    var outputceiling = $("#outputceiling").val();
    var issues = $("#issues").val();

    if (current != "") {
      current = parseFloat(current);
      cumulative = parseFloat(cumulative);
      outputceiling = parseFloat(outputceiling);
      var total = current + cumulative;

      if (total <= outputceiling) {
        if (issues == "true" && total == outputceiling) {
          $("#outputprogress").val("");
        }
      } else {
        $("#outputprogress").val("");
      }
    } else {
      $("#outputprogress").val("");
    }
  }

  function enableSubmit() {
    let handler = [];
    if ($('.tasks').length) {
      $(".tasks").each(function() {
        var val = $(this).val();
        (val != "") ? handler.push(true): handler.push(false);
      });
    } else {
      handler.push(false);
    }
    (handler.includes(false)) ? $("#submit").prop("disabled", true): $("#submit").prop("disabled", false);
  }

  $(".obs").click(function(e) {
    $(this).find("i").toggleClass("fa-plus-square fa-minus-square");
  });

  $('.tasks_toogle').click(function() {
    $(this).find('i').toggleClass('fa-plus-square').toggleClass('fa-minus-square');
  });
</script>