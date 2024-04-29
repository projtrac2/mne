<?php
try {
  require('includes/head.php');
  if ($permission && (isset($_GET['prj']))) {
    $hash = $_GET['prj'];
    $decode_projid = base64_decode($hash);
    $projid_array = explode("encodeprocprj", $decode_projid);
    $projid = $projid_array[1];

    $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.deleted='0' AND projid=:projid AND projstage=:projstage");
    $query_rsProjects->execute(array(":projid" => $projid, ":projstage" => $workflow_stage));
    $row_rsProjects = $query_rsProjects->fetch();
    $totalRows_rsProjects = $query_rsProjects->rowCount();

    if ($totalRows_rsProjects > 0) {
      $projname = $row_rsProjects['projname'];
      $projcode = $row_rsProjects['projcode'];
      $projcost = $row_rsProjects['projcost'];
      $progid = $row_rsProjects['progid'];
      $workflow_stage = $row_rsProjects['projstage'];
      $project_sub_stage = $row_rsProjects['proj_substage'];
      $project_directorate = $row_rsProjects['directorate'];
      $payment_plan = $row_rsProjects['payment_plan'];

      $query_rsprocurementmethod = $db->prepare("SELECT * FROM tbl_procurementmethod");
      $query_rsprocurementmethod->execute();
      $row_rsprocurementmethod = $query_rsprocurementmethod->fetch();
      $totalRows_procurementmethod = $query_rsprocurementmethod->rowCount();

      $query_rscategory = $db->prepare("SELECT * FROM tbl_tender_category");
      $query_rscategory->execute();
      $row_rscategory = $query_rscategory->fetch();
      $totalRows_rscategory = $query_rscategory->rowCount();

      $query_rstender = $db->prepare("SELECT * FROM tbl_tender_type");
      $query_rstender->execute();
      $row_rstender = $query_rstender->fetch();
      $totalRows_rstender = $query_rstender->rowCount();

      $query_rsContractor = $db->prepare("SELECT * FROM tbl_contractor WHERE pinstatus='1'");
      $query_rsContractor->execute();

      function clear_tables($projid, $files)
      {
        global $db, $workflow_stage;
        $sql = $db->prepare("DELETE FROM tbl_tenderdetails WHERE projid=:projid ");
        $result1 = $sql->execute(array(':projid' => $projid));

        $sql = $db->prepare("DELETE FROM tbl_contract_guarantees WHERE projid=:projid ");
        $result1 = $sql->execute(array(':projid' => $projid));

        $query_rsTender_Files = $db->prepare("SELECT * FROM tbl_files WHERE projid=:projid AND projstage=:workflow_stage");
        $query_rsTender_Files->execute(array(":projid" => $projid, ":workflow_stage" => $workflow_stage));
        $totalRows_rsTender_Files = $query_rsTender_Files->rowCount();

        if ($totalRows_rsTender_Files > 0) {
          while ($row_rsTender_Files = $query_rsTender_Files->fetch()) {
            $file_id = $row_rsTender_Files[''];
            if (!in_array($file_id, $files)) {
              $file_name = $row_rsTender_Files['file_name'];
              if (file_exists("$file_name")) {
                unlink("$file_name");
              }
              $sql = $db->prepare("DELETE FROM tbl_files WHERE projid=:projid AND projstage=:workflow_stage");
              $result2 = $sql->execute(array(':projid' => $projid, ':workflow_stage' => $workflow_stage));
            }
          }
        }
      }

      if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "store_tender_details")) {
        if (validate_csrf_token($_POST['csrf_token'])) {
          $hash = $_POST['projid'];
          $dec = explode("encodeprocprj", base64_decode($hash));
          $projid = $dec[1];
          $user_name = $_POST['user_name'];
          $datecreated = date("Y-m-d");
          $existing_files = isset($_POST['attached_files']) ? $_POST['attached_files'] : "";
          clear_tables($projid, $existing_files);
          if (isset($_POST['contractrefno']) && !empty($_POST['contractrefno'])) {
            $evaluation = date('Y-m-d', strtotime($_POST['tenderevaluationdate']));
            $award = date('Y-m-d', strtotime($_POST['tenderawarddate']));
            $contractrefno = $_POST['contractrefno'];
            $tenderno = $_POST['tenderno'];
            $tendertitle = $_POST['tendertitle'];
            $tendertype = $_POST['tendertype'];
            $tendercat = $_POST['tendercat'];
            $tenderamount = 0;
            $procurementmethod = $_POST['procurementmethod'];
            $financialscore = $_POST['financialscore'];
            $technicalscore = $_POST['technicalscore'];
            $comments = $_POST['comments'];
            $projcontractor = $_POST['projcontractor'];
            $cost_variation = $_POST['cost_variation'];
            $date_created = date("Y-m-d");

            $insertSQL = $db->prepare("INSERT INTO `tbl_tenderdetails` (`projid`, `contractrefno`, `tenderno`, `tendertitle`,`tendertype`, `tendercat`, `tenderamount`, `procurementmethod`, `evaluationdate`, `awarddate`, `notificationdate`, `signaturedate`, `startdate`, `enddate`, `financialscore`, `technicalscore`, `contractor`, `comments`,`cost_variation`, `created_by`,`date_created`) VALUES( :projid, :contractrefno, :tenderno, :tendertitle, :tendertype, :tendercat, :tenderamount, :procurementmethod, :evaluationdate, :awarddate, :notificationdate, :signaturedate, :startdate, :enddate, :financialscore, :technicalscore, :contractor, :comments,:cost_variation, :created_by, :date_created)");

            $insertSQL->execute(array(":projid" => $projid, ":contractrefno" => $contractrefno, ":tenderno" => $_POST['tenderno'], ":tendertitle" => $tendertitle, ":tendertype" => $tendertype, ":tendercat" => $tendercat, ":tenderamount" => $tenderamount, ":procurementmethod" => $procurementmethod, ":evaluationdate" => $evaluation, ":awarddate" => $award, ":notificationdate" => date('Y-m-d', strtotime($_POST['tendernotificationdate'])), ":signaturedate" => date('Y-m-d', strtotime($_POST['tendersignaturedate'])), ":startdate" => date('Y-m-d', strtotime($_POST['tenderstartdate'])), ":enddate" => date('Y-m-d', strtotime($_POST['tenderenddate'])), ":financialscore" => $financialscore, ":technicalscore" => $technicalscore, ":contractor" => $projcontractor, ":comments" => $comments, ":cost_variation" => $cost_variation, ":created_by" => $user_name, ":date_created" => $date_created));
            $last_id = $db->lastInsertId();


            //--------------------------------------------------------------------------
            // 1)Update project and add tender info
            //--------------------------------------------------------------------------
            $update = $db->prepare("UPDATE tbl_projects SET projtender = :projtender, projcontractor = :projcontractor WHERE projid = :projid");
            $update->execute(array(':projtender' => $last_id, ':projcontractor' => $projcontractor, ':projid' => $projid));

            if (isset($_POST['guarantee']) && !empty($_POST['guarantee'])) {
              $myUser = $user_name;
              $count = count($_POST["guarantee"]);
              if ($count > 0) {
                for ($cnt = 0; $cnt < $count; $cnt++) {
                  $guarantee = $_POST["guarantee"][$cnt];
                  $start_date = $_POST["guarantee_start_date"][$cnt];
                  $duration = $_POST["guarantee_duration"][$cnt];
                  $notification = $_POST["guarantee_notification"][$cnt];

                  $insert_guarantee = $db->prepare("INSERT INTO tbl_contract_guarantees(projid, guarantee, start_date, duration, notification, date_created, created_by) VALUES (:projid, :guarantee, :start_date, :duration, :notification, :date_created, :created_by)");
                  $insert_guarantee->execute(array(':projid' => $projid, ':guarantee' => $guarantee, ':start_date' => $start_date, ':duration' => $duration, ':notification' => $notification, ':date_created' => $date_created, ':created_by' => $myUser));
                }
              }
            }

            if (isset($_POST['attachmentpurpose'])) {
              $myUser = $user_name;
              $count = count($_POST["attachmentpurpose"]);
              if ($count > 0) {
                $filestage = $workflow_stage;
                $filecategory = 0;
                for ($cnt = 0; $cnt < $count; $cnt++) {
                  if (!empty($_FILES['tenderfile']['name'][$cnt])) {
                    $purpose = $_POST["attachmentpurpose"][$cnt];
                    $filename = basename($_FILES['tenderfile']['name'][$cnt]);
                    $ext = substr($filename, strrpos($filename, '.') + 1);
                    if (($ext != "exe") && ($_FILES["tenderfile"]["type"][$cnt] != "application/x-msdownload")) {
                      $newname = time() . "-" . $cnt . "-" . $filestage . "-" . $filename;
                      $filepath = "uploads/procurement/" . $newname;
                      if (!file_exists($filepath)) {
                        if (move_uploaded_file($_FILES['tenderfile']['tmp_name'][$cnt], $filepath)) {
                          $qry2 = $db->prepare("INSERT INTO tbl_files (projid, projstage, filename, ftype, floc, fcategory, reason, uploaded_by, date_uploaded) VALUES (:projid, :projstage, :filename, :ftype, :floc, :fcat, :reason, :user, :date)");
                          $qry2->execute(array(':projid' => $projid, ':projstage' => $filestage, ':filename' => $newname, ':ftype' => $ext, ':floc' => $filepath, ':fcat' => $filecategory, ':reason' => $purpose, ':user' => $myUser, ':date' => $date_created));
                        }
                      }
                    }
                  }
                }
              }
            }
          }

          $hash = base64_encode("encodeprocprj{$projid}");
          $results = success_message('Contractor details were successfully created', 2, "add-procurement-details.php?prj=" . $hash);
        } else {
          $hash = base64_encode("encodeprocprj{$projid}");
          $results = error_message('Error occured please try again later', 2, "add-procurement-details.php?prj=" . $hash);
        }
      }

      if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "store_payment_details")) {
        if (validate_csrf_token($_POST['csrf_token'])) {
          $datecreated = date("Y-m-d");
          $payment_plan = $_POST['payment_plan'];

          $update = $db->prepare("UPDATE tbl_projects SET payment_plan = :payment_plan WHERE projid = :projid");
          $update->execute(array(':payment_plan' => $payment_plan, ':projid' => $projid));

          $sql = $db->prepare("DELETE FROM `tbl_project_payment_plan` WHERE projid=:projid ");
          $results = $sql->execute(array(':projid' => $projid));

          $sql = $db->prepare("DELETE FROM `tbl_project_payment_plan_details` WHERE projid=:projid ");
          $results = $sql->execute(array(':projid' => $projid));


          $count_mile = [];
          if ($payment_plan == 1) {
            if (isset($_POST['payment_phase'])) {
              $total_payment_phase = count($_POST['payment_phase']);
              for ($i = 0; $i < $total_payment_phase; $i++) {
                $payment_phase = $_POST['payment_phase'][$i];
                $percentage = $_POST['percentage'][$i];
                $row = $_POST['row'][$i];

                $sql = $db->prepare("INSERT INTO `tbl_project_payment_plan` (projid, payment_plan, percentage) VALUES(:projid, :payment_phase, :percentage)");
                $result = $sql->execute(array(":projid" => $projid, ":payment_phase" => $payment_phase, ":percentage" => $percentage));
                if (isset($_POST['milestone_name' . $row]) && $result) {
                  $payment_phase_id = $db->lastInsertId();
                  $total_milestone = count($_POST['milestone_name' . $row]);
                  $count_mile[] = $_POST['milestone_name' . $row];
                  for ($j = 0; $j < $total_milestone; $j++) {
                    $milestone = $_POST['milestone_name' . $row][$j];
                    $insertSQL = $db->prepare("INSERT INTO `tbl_project_payment_plan_details` (projid, payment_plan_id, milestone_id, created_by, created_at) VALUES(:projid,:payment_plan_id, :milestone_id, :created_by, :created_at)");
                    $insertSQL->execute(array(":projid" => $projid, ":payment_plan_id" => $payment_phase_id, ":milestone_id" => $milestone, ":created_by" => $user_name, ":created_at" => $datecreated));
                  }
                }
              }
            }
          }

          $hash = base64_encode("encodeprocprj{$projid}");
          $results = success_message('Payment plan successfully created', 2, "add-procurement-details.php?prj=" . $hash);
        } else {
          $hash = base64_encode("encodeprocprj{$projid}");
          $results = error_message('Error occured please try again later', 2, "add-procurement-details.php?prj=" . $hash);
        }
      }

      $query_rsTender = $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid=:projid");
      $query_rsTender->execute(array(":projid" => $projid));
      $row_rsTender = $query_rsTender->fetch();
      $totalRows_rsTender = $query_rsTender->rowCount();

      $contractrefno = $tenderno  =  $tendertitle = $tendertype = $tendercat = $procurementmethod = "";
      $tenderevaluationdate = $tenderawarddate = $tendernotificationdate = $tendersignaturedate = "";
      $tenderstartdate = $tenderenddate = $financialscore = $technicalscore = $comments = $contractor_id = "";
      $pinnumber = $bizregno = $biztype = $cost_variation = "";

      if ($totalRows_rsTender > 0) {
        $contractrefno = $row_rsTender['contractrefno'];
        $tenderno = $row_rsTender['tenderno'];
        $tendertitle = $row_rsTender['tendertitle'];
        $tendertype = $row_rsTender['tendertype'];
        $tendercat = $row_rsTender['tendercat'];
        $procurementmethod = $row_rsTender['procurementmethod'];

        $tenderevaluationdate = $row_rsTender['evaluationdate'];
        $tenderawarddate = $row_rsTender['awarddate'];
        $tendernotificationdate = $row_rsTender['notificationdate'];
        $tendersignaturedate = $row_rsTender['signaturedate'];
        $tenderstartdate = $row_rsTender['startdate'];
        $tenderenddate = $row_rsTender['enddate'];
        $financialscore = $row_rsTender['financialscore'];
        $technicalscore = $row_rsTender['technicalscore'];
        $comments = $row_rsTender['comments'];
        $contractor_id = $row_rsTender['contractor'];
        $cost_variation = $row_rsTender['cost_variation'];

        $query_cont = $db->prepare("SELECT pinno, busregno, type  FROM tbl_contractor left join tbl_contractorbusinesstype on tbl_contractor.businesstype=tbl_contractorbusinesstype.id WHERE contrid='$contractor_id'");
        $query_cont->execute();
        $row_cont = $query_cont->fetch();
        if ($row_cont) {
          $pinnumber = $row_cont['pinno'];
          $bizregno = $row_cont['busregno'];
          $biztype = $row_cont['type'];
        }
      }

      function validate_tender_details()
      {
        global $db, $projid;
        $query_rsTender = $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid=:projid");
        $query_rsTender->execute(array(":projid" => $projid));
        $totalRows_rsTender = $query_rsTender->rowCount();
        return ($totalRows_rsTender > 0) ? true : false;
      }

      function validate_payment_details()
      {
        global $db, $projid;
        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid=:projid AND payment_plan <> 0");
        $query_rsProjects->execute(array(":projid" => $projid));
        $totalRows_rsProjects = $query_rsProjects->rowCount();
        return $totalRows_rsProjects > 0 ? true : false;
      }

      function validate_output_details()
      {
        global $db, $projid;
        $query_rs_output_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid=:projid AND cost_type= 1 ");
        $query_rs_output_cost_plan->execute(array(":projid" => $projid));
        $totalRows_rs_output_cost_plan = $query_rs_output_cost_plan->rowCount();
        $outputs = $costlines = [];
        if ($totalRows_rs_output_cost_plan > 0) {
          while ($row_rsOther_cost_plan = $query_rs_output_cost_plan->fetch()) {
            $costlineid = $row_rsOther_cost_plan['id'];
            $query_rsProcurement =  $db->prepare("SELECT * FROM tbl_project_tender_details WHERE costlineid=:costlineid ");
            $query_rsProcurement->execute(array(":costlineid" => $costlineid));
            $totalRows_rsProcurement = $query_rsProcurement->rowCount();
            $outputs[] = $totalRows_rsProcurement > 0 ? true : false;
          }
        }
        return !in_array(false, $outputs) ? true : false;
      }

      $proceed = validate_payment_details() && validate_tender_details() && validate_output_details() ? true : false;
      $approval_stage = ($project_sub_stage  >= 2) ? true : false;
?>
      <!-- start body  -->
      <section class="content">
        <div class="container-fluid">
          <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
            <h4 class="contentheader">
              <?= $icon . " " . $pageTitle ?>
              <div class="btn-group" style="float:right">
                <div class="btn-group" style="float:right">
                  <a type="button" id="outputItemModalBtnrow" onclick="history.back()" class="btn btn-warning pull-right">
                    Go Back
                  </a>
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
                <div class="card-header">
                  <ul class="nav nav-tabs" style="font-size:14px">
                    <li class="active">
                      <a data-toggle="tab" href="#details"><i class="fa fa-hourglass-half bg-green" aria-hidden="true"></i> Main Contract Details &nbsp;<span class="badge bg-green">|</span></a>
                    </li>
                    <li>
                      <a data-toggle="tab" class="nav-link" href="#quotation"><i class="fa fa-users bg-lime" aria-hidden="true"></i> Contract Quotation &nbsp;<span class="badge bg-lime">|</span></a>
                    </li>
                    <li>
                      <a data-toggle="tab" href="#payment"><i class="fa fa-tasks bg-blue" aria-hidden="true"></i> Contract Payment Method&nbsp;<span class="badge bg-blue">|</span></a>
                    </li>
                  </ul>
                </div>
                <div class="body">
                  <!-- ============================================================== -->
                  <!-- Start Page Content -->
                  <!-- ============================================================== -->

                  <div class="table-responsive">
                    <div class="tab-content">
                      <input type="hidden" name="myprojid" id="myprojid" value="<?= $projid ?>">
                      <div id="details" class="tab-pane fade in active">
                        <div class="body">
                          <form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                            <?= csrf_token_html(); ?>
                            <div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
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
                            </div>
                            <fieldset class="scheduler-border" style="background-color:#edfcf1; border-radius:3px">
                              <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-cogs" style="color:#F44336" aria-hidden="true"></i> Contractor Details</legend>
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-inline">
                                  <label for="">Contractor Name</label>
                                  <select name="projcontractor" id="projcontractor" class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%">
                                    <option value="">.... Select Project Contractor from list ....</option>
                                    <?php
                                    while ($row_rsContractor = $query_rsContractor->fetch()) {
                                      $selected = $row_rsContractor['contrid'] == $contractor_id ? ' selected="selected"' : '';
                                    ?>
                                      <font color="black">
                                        <option value="<?php echo $row_rsContractor['contrid'] ?>" <?= $selected ?>><?php echo $row_rsContractor['contractor_name'] ?></option>
                                      </font>
                                    <?php
                                    }
                                    ?>
                                  </select>
                                </div>
                              </div>
                              <div id="contrinfo">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                  <label for="">Pin Number</label>
                                  <input type="text" name="pinnumber" id="pinnumber" value="<?= $pinnumber ?>" class="form-control" style="border:#CCC thin solid; border-radius: 5px; width:98%" disabled="disabled">
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                  <label for="">Business Reg No.</label>
                                  <input type="text" name="bizregno" id="bizregno" value="<?= $bizregno ?>" class="form-control" style="border:#CCC thin solid; border-radius: 5px; width:98%" disabled="disabled">
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                  <label for="">Business Type</label>
                                  <input type="text" name="biztype" id="biztype" value="<?= $biztype ?>" class="form-control" style="border:#CCC thin solid; border-radius: 5px; width:98%" disabled="disabled">
                                </div>
                              </div>
                            </fieldset>
                            <fieldset class="scheduler-border" style="border-radius:3px">
                              <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                <i class="fa fa-shopping-bag" style="color:#F44336" aria-hidden="true"></i> Other Contract Details
                              </legend>
                              <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <label for="">Contract Ref. Number *</label>
                                <input name="contractrefno" type="text" id="contractrefno" value="<?= $contractrefno ?>" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Add Contract Ref Number" required />
                              </div>
                              <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <label for="">Contract Signature Date *</label>
                                <input name="tendersignaturedate" type="date" id="tendersignaturedate" value="<?= $tendersignaturedate ?>" class="form-control" placeholder="Click Signature Date" required />
                              </div>
                              <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <label for="">Contract Start Date *</label>
                                <input name="tenderstartdate" type="date" id="tenderstartdate" value="<?= $tenderstartdate ?>" class="form-control" placeholder="Click Start Date" required />
                              </div>
                              <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <label for="">Contract Expiry Date *</label>
                                <input name="tenderenddate" type="date" id="tenderenddate" value="<?= $tenderenddate ?>" class="form-control" placeholder="Click End Date" required />
                              </div>
                              <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <label for="">Tender Number *</label>
                                <input name="tenderno" type="text" id="tenderno" class="form-control" value="<?= $tenderno ?>" style="border:#CCC thin solid; border-radius: 5px" placeholder="Enter Tender Number" required />
                              </div>
                              <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <label for="Title">Tender Title *:</label>
                                <div class="form-line">
                                  <input type="text" name="tendertitle" id="tendertitle" value="<?= $tendertitle ?>" class="form-control" required>
                                </div>
                              </div>
                              <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <label for="">Tender Type *</label>
                                <select name="tendertype" id="tendertype" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                  <option value="">.... Select Tender Type ....</option>
                                  <?php
                                  do {
                                    $selected = $tendertype == $row_rstender['id'] ?  ' selected="selected"' : '';
                                  ?>
                                    <option value="<?php echo $row_rstender['id'] ?>" <?= $selected ?>><?php echo $row_rstender['type'] ?></option>
                                  <?php
                                  } while ($row_rstender = $query_rstender->fetch());
                                  ?>
                                </select>
                              </div>
                              <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <label for="">Tender Category *</label>
                                <select name="tendercat" id="tendercat" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                  <option value="">.... Select Tender Category ....</option>
                                  <?php
                                  do {
                                    $selected = $tendercat == $row_rscategory['id'] ?  'selected' : '';
                                  ?>
                                    <option value="<?php echo $row_rscategory['id'] ?>" <?= $selected ?>><?php echo $row_rscategory['category'] ?></option>
                                  <?php
                                  } while ($row_rscategory = $query_rscategory->fetch());
                                  ?>
                                </select>
                              </div>
                              <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <label for="">Procurement Method *</label>
                                <select name="procurementmethod" id="procurementmethod" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                  <option value="">Select Procurement Method</option>
                                  <?php
                                  do {
                                    $selected = $procurementmethod == $row_rsprocurementmethod['id'] ?  ' selected="selected' : '';
                                  ?>
                                    <option value="<?php echo $row_rsprocurementmethod['id'] ?>" <?= $selected ?>><?php echo $row_rsprocurementmethod['method'] ?></option>
                                  <?php
                                  } while ($row_rsprocurementmethod = $query_rsprocurementmethod->fetch());
                                  ?>
                                </select>
                              </div>
                              <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <label for="">Tender Evaluation Date *</label>
                                <input name="tenderevaluationdate" type="date" id="tenderevaluationdate" value="<?= $tenderevaluationdate ?>" class="form-control" placeholder="Enter Tender Evaluation date" required />
                              </div>
                              <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <label for="">Tender Technical Score *</label>
                                <input name="technicalscore" type="number" id="technicalscore" value="<?= $technicalscore ?>" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Enter Technical Score" required />
                              </div>
                              <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <label for="">Tender Financial Score *</label>
                                <input name="financialscore" type="number" id="financialscore" value="<?= $financialscore ?>" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Add Funancial Score" required />
                              </div>
                              <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <label for="">Tender Award Date *</label>
                                <input name="tenderawarddate" type="date" id="tenderawarddate" value="<?= $tenderawarddate ?>" class="form-control" placeholder="Enter Award date" required />
                              </div>
                              <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <label for="">Tender Notification Date *</label>
                                <input name="tendernotificationdate" type="date" id="tendernotificationdate" value="<?= $tendernotificationdate ?>" class="form-control" placeholder="Enter Notification Date" required />
                              </div>
                              <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <label for="">Cost Variation (%)*</label>
                                <input name="cost_variation" type="number" step="0.01" id="cost_variation" value="<?= $cost_variation ?>" class="form-control" placeholder="Enter % cost variation" required />
                              </div>
                            </fieldset>
                            <fieldset class="scheduler-border" style="border-radius:3px">
                              <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                <i class="fa fa-certificate" style="color:#F44336" aria-hidden="true"></i> Contract Statutory Guarantees
                              </legend>
                              <div class="body">
                                <?php
                                $query_contract_guarantees = $db->prepare("SELECT * FROM tbl_contract_guarantees WHERE projid=:projid");
                                $query_contract_guarantees->execute(array(":projid" => $projid));
                                $totalRows_contract_guarantees = $query_contract_guarantees->rowCount();

                                if ($totalRows_contract_guarantees > 0) {
                                ?>
                                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                      <table class="table table-bordered" id="guarantees_table">
                                        <thead>
                                          <tr>
                                            <th style="width:40%">Guarantee *</th>
                                            <th style="width:18%">Start Date *</th>
                                            <th style="width:20%">Duration (Days) *</th>
                                            <th style="width:20%">Expiry Notification in Days *</th>
                                            <th style="width:2%">
                                              <button type="button" name="addplus" onclick="add_guarantee_row();" title="Add another guarantee" class="btn btn-success btn-sm">
                                                <span class="glyphicon glyphicon-plus"></span>
                                              </button>
                                            </th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <?php
                                          $rowno = 0;
                                          while ($row_contract_guarantees = $query_contract_guarantees->fetch()) {
                                            $rowno++;
                                          ?>
                                            <tr id="guarantee_row<?= $rowno ?>">
                                              <td style="width:40%"><?= $row_contract_guarantees['guarantee'] ?></td>
                                              <td style="width:18%"><?= $row_contract_guarantees['start_date'] ?></td>
                                              <td style="width:20%"><?= $row_contract_guarantees['duration'] ?></td>
                                              <td style="width:20%"><?= $row_contract_guarantees['notification'] ?></td>
                                              <td style="width:2%">
                                                <button type="button" name="addplus" onclick="delete_guarantee_row('guarantee_row<?= $rowno ?>')" title="Add another document" class="btn btn-danger btn-sm">
                                                  <span class="glyphicon glyphicon-minus"></span>
                                                </button>
                                              </td>
                                            </tr>
                                          <?php
                                          }
                                          ?>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                <?php
                                } else {
                                ?>
                                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                      <table class="table table-bordered" id="guarantees_table">
                                        <tr>
                                          <th style="width:40%">Guarantee *</th>
                                          <th style="width:18%">Start Date *</th>
                                          <th style="width:20%">Duration (Days) *</th>
                                          <th style="width:20%">Expiry Notification in Days *</th>
                                          <th style="width:2%">
                                            <button type="button" name="addplus" onclick="add_guarantee_row();" title="Add another guarantee" class="btn btn-success btn-sm">
                                              <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                          </th>
                                        </tr>
                                      </table>
                                    </div>
                                  </div>
                                <?php
                                }
                                ?>
                              </div>
                            </fieldset>
                            <fieldset class="scheduler-border" style="border-radius:3px">
                              <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                <i class="fa fa-file-text-o" style="color:#F44336" aria-hidden="true"></i> Contract/Tender Comments
                              </legend>
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label class="control-label"></label>
                                <p align="left">
                                  <textarea name="comments" cols="45" rows="5" class="form-control" required="required"><?= $comments ?></textarea>
                                </p>
                              </div>
                            </fieldset>
                            <fieldset class="scheduler-border">
                              <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-paperclip" style="color:#F44336" aria-hidden="true"></i> Procurement Documents/Files Attachment</legend>
                              <div class="header">
                                <i class="ti-link"></i>MULTIPLE FILES UPLOAD - WITH CLICK & CHOOSE
                              </div>
                              <div class="body">
                                <?php
                                $query_rsTender_Files = $db->prepare("SELECT * FROM tbl_files WHERE projid=:projid AND projstage=4");
                                $query_rsTender_Files->execute(array(":projid" => $projid));
                                $totalRows_rsTender_Files = $query_rsTender_Files->rowCount();

                                if ($totalRows_rsTender_Files > 0) {
                                ?>
                                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                      <table class="table table-bordered" id="files_table">
                                        <thead>
                                          <tr>
                                            <th style="width:40%">Attachments *</th>
                                            <th style="width:58%">Attachment Purpose *</th>
                                            <th style="width:2%">
                                              <button type="button" name="addplus" onclick="add_row();" title="Add another document" class="btn btn-success btn-sm">
                                                <span class="glyphicon glyphicon-plus"></span>
                                              </button>
                                            </th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <?php
                                          $rowno = 0;
                                          while ($row_rsTender_Files = $query_rsTender_Files->fetch()) {
                                            $rowno++;
                                          ?>
                                            <tr id="attached_row<?= $rowno ?>">
                                              <td style="width:40%"><?= $row_rsTender_Files['filename'] ?></td>
                                              <td style="width:58%"><?= $row_rsTender_Files['reason'] ?></td>
                                              <td style="width:2%">
                                                <button type="button" name="addplus" onclick="delete_row('attached_row<?= $rowno ?>')" title="Add another document" class="btn btn-danger btn-sm">
                                                  <span class="glyphicon glyphicon-minus"></span>
                                                </button>
                                              </td>
                                            </tr>
                                          <?php
                                          }
                                          ?>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                <?php
                                } else {
                                ?>
                                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                      <table class="table table-bordered" id="files_table">
                                        <tr>
                                          <th style="width:40%">Attachments *</th>
                                          <th style="width:58%">Attachment Purpose *</th>
                                          <th style="width:2%">
                                            <button type="button" name="addplus" onclick="add_row();" title="Add another document" class="btn btn-success btn-sm">
                                              <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                          </th>
                                        </tr>
                                      </table>
                                    </div>
                                  </div>
                                <?php
                                }
                                ?>
                              </div>
                            </fieldset>
                            <div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
                              <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-center">
                              </div>
                              <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-center">
                                <input type="hidden" name="MM_insert" id="MM_insert" value="store_tender_details">
                                <input type="hidden" name="projid" id="projid" value="<?= $hash ?>">
                                <input type="hidden" name="user_name" value="<?= $user_name ?>">
                                <input name="save_contract_details" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="<?= validate_tender_details($projid) ? 'Edit' : 'Save' ?> Contract Details" />
                              </div>
                              <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-center">
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                      <div id="quotation" class="tab-pane fade">
                        <div class="body">
                          <form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                            <?= csrf_token_html(); ?>
                            <fieldset class="scheduler-border">
                              <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                <i class="fa fa-list-ol" aria-hidden="true"></i> 1.0 Direct Project Cost
                              </legend>
                              <?php
                              $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid ");
                              $query_rsOther_cost_plan_budget->execute(array(":projid" => $projid));
                              $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
                              $sum_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;

                              $query_Procurement = $db->prepare("SELECT SUM(unit_cost * units_no) as total_cost FROM tbl_project_tender_details WHERE projid = :projid ");
                              $query_Procurement->execute(array(":projid" => $projid));
                              $row_rsProcurement = $query_Procurement->fetch();
                              $amount = ($row_rsProcurement['total_cost'] != NULL) ? $row_rsProcurement['total_cost'] : 0;

                              ?>
                              <ul class="list-group">
                                <li class="list-group-item list-group-item list-group-item-action active">Project: <?= $projname ?></li>
                                <li class="list-group-item"><strong>Contract Estimate: </strong> <?= number_format($sum_cost, 2) ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Contract Cost: </strong> <?= number_format($amount, 2) ?> </li>
                              </ul>
                              <?php
                              $query_Sites = $db->prepare("SELECT * FROM tbl_project_sites WHERE projid=:projid");
                              $query_Sites->execute(array(":projid" => $projid));
                              $rows_sites = $query_Sites->rowCount();
                              $summary = '';
                              if ($rows_sites > 0) {
                                $counter = 0;
                                while ($row_Sites = $query_Sites->fetch()) {
                                  $site_id = $row_Sites['site_id'];
                                  $site = $row_Sites['site'];
                                  $counter++;
                                  $query_Site_Output = $db->prepare("SELECT * FROM tbl_output_disaggregation  WHERE output_site=:site_id");
                                  $query_Site_Output->execute(array(":site_id" => $site_id));
                                  $rows_Site_Output = $query_Site_Output->rowCount();
                                  if ($rows_Site_Output > 0) {
                              ?>
                                    <fieldset class="scheduler-border">
                                      <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                        <i class="fa fa-list-ol" aria-hidden="true"></i> Site <?= $counter ?> : <?= $site ?>
                                      </legend>
                                      <div class="table-responsive">
                                        <table class="table table-bordered" id="direct_table<?= $site_id ?>">
                                          <thead>
                                            <tr>
                                              <th style="width:5%"># </th>
                                              <th style="width:60%">Output</th>
                                              <th style="width:15%">Contract Estimate</th>
                                              <th style="width:15%">Contract Cost</th>
                                              <th style="width:5%">Action</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <?php
                                            $output_counter = 0;
                                            while ($row_Site_Output = $query_Site_Output->fetch()) {
                                              $output_counter++;
                                              $output_id = $row_Site_Output['outputid'];
                                              $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE id = :outputid");
                                              $query_Output->execute(array(":outputid" => $output_id));
                                              $row_Output = $query_Output->fetch();
                                              $total_Output = $query_Output->rowCount();
                                              if ($total_Output) {
                                                $output_id = $row_Output['id'];
                                                $output = $row_Output['indicator_name'];

                                                $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND outputid=:output_id AND site_id=:site_id ");
                                                $query_rsOther_cost_plan_budget->execute(array(":projid" => $projid, ":output_id" => $output_id, ":site_id" => $site_id));
                                                $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
                                                $sum_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;

                                                $query_Procurement = $db->prepare("SELECT SUM(unit_cost * units_no) as total_cost FROM tbl_project_tender_details WHERE outputid = :output_id AND site_id = :site_id");
                                                $query_Procurement->execute(array(":output_id" => $output_id, ":site_id" => $site_id));
                                                $row_rsProcurement = $query_Procurement->fetch();

                                                $amount = 0;
                                                if ($row_rsProcurement['total_cost'] != NULL) {
                                                  $amount = $row_rsProcurement['total_cost'];
                                                }
                                            ?>
                                                <tr>
                                                  <td style="width:5%"><?= $output_counter ?></td>
                                                  <td style="width:60%"><?= $output  ?></td>
                                                  <td style="width:15%"><?= number_format($sum_cost, 2) ?></td>
                                                  <td style="width:15%"><?= number_format($amount, 2) ?></td>
                                                  <td style="width:5%">
                                                    <a href="add-procurement-plan.php?output_id=<?= base64_encode("encodeprocprj{$output_id}") ?>&site_id=<?= base64_encode("encodeprocprj{$site_id}") ?>" class="btn btn-success btn-sm">
                                                      <?php echo $amount > 0 ? '<span class="glyphicon glyphicon-pencil"></span>' : '<span class="glyphicon glyphicon-plus"></span>' ?>
                                                    </a>
                                                  </td>
                                                </tr>
                                            <?php
                                              }
                                            }
                                            ?>
                                          </tbody>
                                        </table>
                                      </div>
                                    </fieldset>
                              <?php
                                  }
                                }
                              }
                              ?>

                              <?php
                              $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE indicator_mapping_type<>1 AND projid = :projid");
                              $query_Output->execute(array(":projid" => $projid));
                              $total_Output = $query_Output->rowCount();
                              $outputs = '';
                              if ($total_Output > 0) {
                              ?>
                                <fieldset class="scheduler-border">
                                  <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                    <i class="fa fa-list-ol" aria-hidden="true"></i> Waypoint Outputs
                                  </legend>
                                  <div class="table-responsive">
                                    <table class="table table-bordered" id="direct_tableoutputoutput00000">
                                      <thead>
                                        <tr>
                                          <th style="width:5%"># </th>
                                          <th style="width:60%">Output</th>
                                          <th style="width:15%">Contract Estimate</th>
                                          <th style="width:15%">Contract Cost</th>
                                          <th style="width:5%">Action</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <?php
                                        $output_counter = 0;
                                        while ($row_rsOutput = $query_Output->fetch()) {
                                          $output_id = $row_rsOutput['id'];
                                          $output = $row_rsOutput['indicator_name'];
                                          $output_counter++;
                                          $site_id = 0;

                                          $query_rsOther_cost_plan_budget =  $db->prepare("SELECT SUM(unit_cost * units_no) as sum_cost FROM tbl_project_direct_cost_plan WHERE projid =:projid AND outputid=:output_id AND site_id=:site_id ");
                                          $query_rsOther_cost_plan_budget->execute(array(":projid" => $projid, ":output_id" => $output_id, ":site_id" => $site_id));
                                          $row_rsOther_cost_plan_budget = $query_rsOther_cost_plan_budget->fetch();
                                          $sum_cost = $row_rsOther_cost_plan_budget['sum_cost'] != null ? $row_rsOther_cost_plan_budget['sum_cost'] : 0;

                                          $query_Procurement = $db->prepare("SELECT SUM(unit_cost * units_no) as total_cost FROM tbl_project_tender_details WHERE outputid = :output_id AND site_id = :site_id");
                                          $query_Procurement->execute(array(":output_id" => $output_id, ":site_id" => $site_id));
                                          $row_rsProcurement = $query_Procurement->fetch();

                                          $amount =  0;
                                          if ($row_rsProcurement['total_cost'] != NULL) {
                                            $amount = $row_rsProcurement['total_cost'];
                                          }
                                        ?>
                                          <tr>
                                            <td style="width:5%"><?= $output_counter ?></td>
                                            <td style="width:60%"><?= $output  ?></td>
                                            <td style="width:15%"><?= number_format($sum_cost, 2) ?></td>
                                            <td style="width:15%"><?= number_format($amount, 2) ?></td>
                                            <td style="width:5%">
                                              <a href="add-procurement-plan.php?output_id=<?= base64_encode("encodeprocprj{$output_id}") ?>&site_id=<?= base64_encode("encodeprocprj{$site_id}") ?>" class="btn btn-success btn-sm">
                                                <?php echo $amount > 0 ? '<span class="glyphicon glyphicon-pencil"></span>' : '<span class="glyphicon glyphicon-plus"></span>' ?>
                                              </a>
                                            </td>
                                          </tr>
                                        <?php
                                        }
                                        ?>
                                      </tbody>
                                    </table>
                                  </div>
                                </fieldset>
                              <?php
                              }
                              ?>
                            </fieldset>
                          </form>
                        </div>
                      </div>
                      <div id="payment" class="tab-pane fade">
                        <div class="header">
                          <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
                              <tr>
                                <td width="80%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF">
                                  <div align="left" style="vertical-align: text-bottom">
                                    <font size="3" color="#FFC107"><i class="fa fa-file-text-o" aria-hidden="true"></i> <strong>Project Output Details</strong></font>
                                  </div>
                                </td>
                              </tr>
                            </table>
                          </div>
                        </div>
                        <div class="body">
                          <fieldset class="scheduler-border">
                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                              <i class="fa fa-money" style="color:#F44336" aria-hidden="true"></i> Project Payment Plan
                            </legend>
                            <div class="body">
                              <form role="form" id="form" action="" method="post" autocomplete="off" onsubmit="return validate_payment_form()" enctype="multipart/form-data">
                                <?= csrf_token_html(); ?>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                  <div class="form-line">
                                    <select name="payment_plan" id="payment_method" onchange="hide_show()" class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
                                      <option value="">.... Select Payment Plan from list ....</option>
                                      <option value="1" <?= $payment_plan == 1 ? "selected" : ''; ?>>Milestone</option>
                                      <option value="2" <?= $payment_plan == 2 ? "selected" : ''; ?>>Tasks</option>
                                      <option value="3" <?= $payment_plan == 3 ? "selected" : ''; ?>>Work Measured</option>
                                    </select>
                                    <input type="hidden" name="edit_payment_plan" value="<?= $payment_plan != '' ? 2 : 1 ?>" />
                                  </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="milestone">
                                  <div class="table-responsive">
                                    <table class="table table-bordered">
                                      <thead>
                                        <tr style="background-color:#0b548f; color:#FFF">
                                          <th style="width:5%">#</th>
                                          <th style="width:30%">Payment Phase *</th>
                                          <th style="width:50%">Milestone *</th>
                                          <th style="width:15%">Percentage</th>
                                          <td style="width:5%">
                                            <button type="button" class="btn btn-success btn-sm" onclick="add_milestone()">
                                              <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                          </td>
                                        </tr>
                                      </thead>
                                      <tbody id="milestone_payment">
                                        <tr></tr>
                                        <?php
                                        $query_rsPayment_plan = $db->prepare("SELECT * FROM tbl_project_payment_plan WHERE projid=:projid");
                                        $query_rsPayment_plan->execute(array(":projid" => $projid));
                                        $totalRows_rsPayment_plan = $query_rsPayment_plan->rowCount();
                                        $rowno = 0;

                                        $query_rsMilestones = $db->prepare("SELECT * FROM tbl_project_milestone WHERE projid=:projid");
                                        $query_rsMilestones->execute(array(":projid" => $projid));
                                        $totalRows_rsMilestones = $query_rsMilestones->rowCount();
                                        if ($totalRows_rsPayment_plan > 0) {

                                          function get_milestones($milestones)
                                          {
                                            global $db, $projid;
                                            $query_rsMilestone = $db->prepare("SELECT * FROM tbl_project_milestone WHERE projid=:projid");
                                            $query_rsMilestone->execute(array(":projid" => $projid));
                                            $totalRows_rsMilestone = $query_rsMilestone->rowCount();
                                            $options = '<options value="">Select Milestones</options>';
                                            if ($totalRows_rsMilestone > 0) {
                                              while ($row_rsMilestone = $query_rsMilestone->fetch()) {
                                                $selected = in_array($row_rsMilestone['id'], $milestones) ? 'selected' : '';
                                                $options .= '<option value="' . $row_rsMilestone['id'] . '" ' . $selected . '>' . $row_rsMilestone['milestone'] . '</option>';
                                              }
                                            }
                                            return $options;
                                          }

                                          while ($Rows_rsPayment_plan = $query_rsPayment_plan->fetch()) {
                                            $rowno++;
                                            $payment_plan_id = $Rows_rsPayment_plan['id'];
                                            $payment_plan = $Rows_rsPayment_plan['payment_plan'];
                                            $percentage = $Rows_rsPayment_plan['percentage'];
                                            $query_rsPayment_plan_details = $db->prepare("SELECT * FROM tbl_project_payment_plan_details WHERE projid=:projid AND payment_plan_id=:payment_plan_id ");
                                            $query_rsPayment_plan_details->execute(array(":projid" => $projid, ":payment_plan_id" => $payment_plan_id));
                                            $totalRows_rsPayment_plan_details = $query_rsPayment_plan_details->rowCount();

                                            $milestones = [];
                                            if ($totalRows_rsPayment_plan_details > 0) {
                                              while ($Rows_rsPayment_plan_details = $query_rsPayment_plan_details->fetch()) {
                                                $milestones[] =  $Rows_rsPayment_plan_details['milestone_id'];
                                              }
                                            }
                                        ?>
                                            <tr id="row<?= $rowno ?>">
                                              <td><?= $rowno ?></td>
                                              <td>
                                                <input type="text" name="payment_phase[]" id="payment_phaserow<?= $rowno ?>" class="form-control" value="<?= $payment_plan ?>" placeholder="Enter the payment phase" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
                                              </td>
                                              <td>
                                                <select name="milestone_name<?= $rowno ?>[]" multiple id="milestone_namerow<?= $rowno ?>" data-id="<?= $rowno ?>" class="form-control require output_location_select show-tick selectpicker" data-live-search="true" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
                                                  <?= get_milestones($milestones) ?>
                                                </select>
                                              </td>
                                              <td>
                                                <input type="number" name="percentage[]" id="percentagerow<?= $rowno ?>" onchange="calculate_percentage(<?= $rowno ?>)" onkeyup="calculate_percentage(<?= $rowno ?>)" class="form-control percent" placeholder="Enter the percentage" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" value="<?= $percentage ?>" required>
                                                <input type="hidden" name="row[]" value="<?= $rowno ?>" />
                                              </td>

                                              <td>
                                                <button type="button" class="btn btn-danger btn-sm" onclick='delete_milestones("row<?= $rowno ?>")'>
                                                  <span class="glyphicon glyphicon-minus"></span>
                                                </button>
                                              </td>
                                            </tr>
                                          <?php
                                          }
                                        } else {
                                          ?>
                                          <tr id="removeTR">
                                            <td>Add Milestones</td>
                                          </tr>
                                        <?php
                                        }
                                        ?>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                                <div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
                                  <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-center">
                                  </div>
                                  <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-center">
                                    <input type="hidden" name="MM_insert" id="MM_insert" value="store_payment_details">
                                    <input type="hidden" name="projid" id="projid" value="<?= $hash ?>">
                                    <input type="hidden" name="projid" id="m_projid" value="<?= $projid ?>">
                                    <input type="hidden" name="total_milestones" id="total_milestones" value="<?= $totalRows_rsMilestones ?>">
                                    <input type="hidden" name="user_name" value="<?= $user_name ?>">
                                    <input name="save_payment_details" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="<?= $payment_plan == '' ? "Save" : "Edit" ?> Payment Details" />
                                  </div>
                                  <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-center">
                                  </div>
                                </div>
                              </form>
                            </div>
                            <!-- #END# File Upload | Drag & Drop OR With Click & Choose -->
                          </fieldset>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
                    <div class="col-md-12 text-center">
                      <?php
                      if ($proceed) {
                        $assigned_responsible = check_if_assigned($projid, $workflow_stage, $project_sub_stage, 1);
                        $approve_details = "{
                        get_edit_details: 'details',
                        projid:$projid,
                        workflow_stage:$workflow_stage,
                        project_directorate:$project_directorate,
                        project_name:'$projname',
                        sub_stage:'$project_sub_stage',
                      }";
                        if ($assigned_responsible) {
                          if ($approval_stage) {
                      ?>
                            <button type="button" onclick="approve_project(<?= $approve_details ?>)" class="btn btn-success">Approve</button>
                          <?php
                          } else {
                            $data_entry_details = "{
                          get_edit_details: 'details',
                          projid:$projid,
                          workflow_stage:$workflow_stage,
                          project_directorate:$project_directorate,
                          project_name:'$projname',
                          sub_stage:'$project_sub_stage',
                        }";
                          ?>
                            <button type="button" onclick="save_data_entry_project(<?= $data_entry_details ?>)" class="btn btn-success">Proceed</button>
                      <?php
                          }
                        }
                      }
                      ?>
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
  } else {
    $results =  restriction();
    echo $results;
  }
} catch (PDOException $ex) {
  customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}

require('includes/footer.php');
?>

<script>

</script>
<script>
  const redirect_url = "add-project-procurement-details.php";
</script>
<script src="assets/js/procurement/index.js"></script>
<script src="assets/js/master/index.js"></script>