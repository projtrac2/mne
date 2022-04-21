<?php
$pageName = "Strategic Plans";
$replacement_array = array(
   'planlabel' => "CIDP",
   'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
$pageTitle = $planlabelplural;

if ($permission) {
  $pageTitle = "Add Procurement Details";
  try{
  	if(isset($_GET['prj'])) {
  		$hash = $_GET['prj'];
  		$decode_projid = base64_decode($hash);
  		$projid_array = explode("encodeprocprj", $decode_projid);
  		$projid = $projid_array[1];
  	}
      $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid='$projid'");
      $query_rsProjects->execute();
      $row_rsProjects = $query_rsProjects->fetch();
      $totalRows_rsProjects = $query_rsProjects->rowCount();
      $projname = $row_rsProjects['projname'];
      $projcode = $row_rsProjects['projcode'];
      $projcost = $row_rsProjects['projcost'];
      $projcategory = $row_rsProjects['projcategory'];
      $progid = $row_rsProjects['progid'];
      $projstartdate = $row_rsProjects['projstartdate'];
      $projenddate = $row_rsProjects['projenddate'];

      $query_rsOutputs = $db->prepare("SELECT p.output as  output, o.id as opid, p.indicator, o.budget as budget FROM tbl_project_details o INNER JOIN tbl_progdetails p ON p.id = o.outputid WHERE projid='$projid'");
      $query_rsOutputs->execute();
      $row_rsOutputs = $query_rsOutputs->fetch();
      $totalRows_rsOutputs = $query_rsOutputs->rowCount();

      // query the
      $query_rsProjFinancier =  $db->prepare("SELECT * FROM tbl_myprojfunding WHERE projid ='$projid' ORDER BY amountfunding desc");
      $query_rsProjFinancier->execute();
      $row_rsProjFinancier = $query_rsProjFinancier->fetch();
      $totalRows_rsProjFinancier = $query_rsProjFinancier->rowCount();

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

  	$query_rsproject = $db->prepare("SELECT * FROM tbl_projects");
  	$query_rsproject->execute();
  	$row_rsproject = $query_rsproject->fetch();
  	$totalRows_rsproject = $query_rsproject->rowCount();

  	$query_rsSector = $db->prepare("SELECT stid,sector FROM tbl_sectors WHERE deleted='0' and parent='0'");
  	$query_rsSector->execute();
  	$row_rsSector = $query_rsSector->fetch();
  	$totalRows_rsSector = $query_rsSector->rowCount();

  	$query_rsContractor = $db->prepare("SELECT * FROM tbl_contractor WHERE pinstatus='1'");
  	$query_rsContractor->execute();

  } catch (PDOException $ex) {
      // $result = flashMessage("An error occurred: " .$ex->getMessage());
      print($ex->getMessage());
  }
?>
<style>
    @media (min-width: 1200px) {
        .modal-lg {
            width: 90%;
            height: 100%;
        }
    }
</style>

<script src="ckeditor/ckeditor.js"></script>
<link rel="stylesheet" href="css/addprojects.css">
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
                 <div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
                   <div class="col-md-3 clearfix" style="margin-top:5px; margin-bottom:5px">
                     <label class="control-label">Project Code:</label>
                     <div class="form-line">
                       <input type="text" class="form-control" value=" <?= $projcode ?>" readonly>
                     </div>
                   </div>
                   <div class="col-md-12 clearfix" style="margin-top:5px; margin-bottom:5px">
                     <label class="control-label">Project Name:</label>
                     <div class="form-line">
                       <input type="text" class="form-control" value=" <?= $projname ?>" readonly>
                     </div>
                   </div>
                 </div>
                  <div class="body">
                    <?php
                    $query_rsTender = $db->prepare("SELECT SUM(amount) as funds FROM tbl_project_cost_funders_share WHERE projid = :projid AND type = :type");
                    $query_rsTender->execute(array(":projid" => $projid, ":type" => 1));
                    $row_plan = $query_rsTender->fetch();
                    $totalRows_Tender = $query_rsTender->rowCount();
                    $contribution_val = $row_plan['funds'];
                    ?>
                    <form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                <?php
                if($projcategory==2){
                ?>
                <fieldset class="scheduler-border" style="background-color:#edfcf1; border-radius:3px">
                  <legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-cogs" style="color:#F44336" aria-hidden="true"></i> Contractor Details</legend>
                  <div class="col-md-12">
                    <div class="form-inline">
                      <label for="">Contractor Name</label>
                      <select name="projcontractor" id="projcontractor"  class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%">
                        <option value="">.... Select Project Contractor from list ....</option>
                        <?php
                        while ($row_rsContractor = $query_rsContractor->fetch()){
                        ?>
                          <font color="black"> <option value="<?php echo $row_rsContractor['contrid']?>"><?php echo $row_rsContractor['contractor_name']?></option></font>
                        <?php
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div id="contrinfo">
                    <div class="col-md-4">
                      <label for="">Pin Number</label>
                      <input type="text" name="pinnumber" id="pinnumber" class="form-control" style="border:#CCC thin solid; border-radius: 5px; width:98%" disabled="disabled">
                    </div>
                    <div class="col-md-4">
                      <label for="">Business Reg No.</label>
                      <input type="text" name="bizregno" id="bizregno" class="form-control" style="border:#CCC thin solid; border-radius: 5px; width:98%" disabled="disabled">
                    </div>
                    <div class="col-md-4">
                      <label for="">Business Type</label>
                      <input type="text" name="biztype" id="biztype" class="form-control" style="border:#CCC thin solid; border-radius: 5px; width:98%" disabled="disabled">
                    </div>
                  </div>
                  <!-- #END# File Upload | Drag & Drop OR With Click & Choose -->
                </fieldset>
                <fieldset class="scheduler-border" style="border-radius:3px">
                  <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                    <i class="fa fa-shopping-bag" style="color:#F44336" aria-hidden="true"></i> Tender Details
                  </legend>
                  <div class="col-md-12">
                    <label for="Title">Tender Title *:</label>
                    <div class="form-line">
                      <input type="text" name="tendertitle" id="tendertitle" class="form-control" required>
                    </div>
                  </div>
                  <!--<script src="http://afarkas.github.io/webshim/js-webshim/minified/polyfiller.js"></script>
                  <script type="text/javascript">

                    /* webshims.setOptions('forms-ext', {
                      replaceUI: 'auto',
                      types: 'number'
                    }); */
                    //webshims.polyfill('forms forms-ext');
                  </script>-->
                  <div class="col-md-12">
                    <table class="table table-bordered table-striped table-hover js-basic-example table-responsive" id="item_table" style="width:100%">
                      <tr>
                        <th style="width:34%">Tender Type *</th>
                        <th style="width:33%">Tender Category *</th>
                        <th style="width:33%"> Procurement Method *</th>
                      </tr>
                      <tr>
                        <td>
                          <select name="tendertype" id="tendertype" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                              <option value="">.... Select Tender Type ....</option>
                              <?php
                                do {
                                  ?>
                                  <option value="<?php echo $row_rstender['id']?>"><?php echo $row_rstender['type']?></option>
                                  <?php
                                } while ($row_rstender = $query_rstender->fetch());
                              ?>
                          </select>
                        </td>
                        <td>
                          <select name="tendercat" id="tendercat" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                              <option value="">.... Select Tender Category ....</option>
                              <?php
                                do {
                                  ?>
                                  <option value="<?php echo $row_rscategory['id']?>"><?php echo $row_rscategory['category']?></option>
                                  <?php
                                } while ($row_rscategory = $query_rscategory->fetch());
                              ?>
                          </select>
                        </td>
                        <td>
                          <select name="procurementmethod" id="procurementmethod" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                              <option value="">Select Procurement Method</option>
                              <?php
                                do {
                                  ?>
                                  <option value="<?php echo $row_rsprocurementmethod['id']?>"><?php echo $row_rsprocurementmethod['method']?></option>
                                  <?php
                                } while ($row_rsprocurementmethod = $query_rsprocurementmethod->fetch());
                              ?>
                          </select>
                        </td>
                      </tr>
                    </table>
                  </div>

                  <div class="col-md-12">
                    <table class="table table-bordered table-striped table-hover js-basic-example table-responsive" style="width:100%">
                      <tr>
                        <th style="width:30%">Contract Reference Number *</th>
                        <th style="width:30%">Tender Number *</th>
                        <th style="width:20%">Tender Technical Score *</th>
                        <th style="width:20%">Tender Financial Score *</th>
                      </tr>
                      <tr>
                        <td>
                          <div class="form-line">
                            <input name="contractrefno" type="text"  id="contractrefno" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Add Contract Ref Number" required/>
                          </div>
                        </td>
                        <td>
                          <div class="form-line">
                            <input name="tenderno" type="text"  id="tenderno" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Enter Tender Number" required/>
                          </div>
                        </td>
                        <td>
                          <div class="form-line">
                            <input name="technicalscore" type="number" id="technicalscore" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Enter Technical Score" required/>
                          </div>
                        </td>
                        <td>
                          <div class="form-line">
                            <input name="financialscore" type="number"  id="financialscore" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Add Funancial Score" required/>
                          </div>
                        </td>
                      </tr>
                    </table>
                  </div>
                  <div class="col-md-12">
                    <table class="table table-bordered table-striped table-hover js-basic-example table-responsive" id="item_table" style="width:100%">
                      <tr>
                        <th style="width:33.3%">Tender Evaluation Date *</th>
                        <th style="width:33.3%">Tender Award Date *</th>
                        <th style="width:33.4%">Tender Notification Date *</th>
                      </tr>
                      <tr>
                        <td>
                          <div class="form-line">
                            <input name="tenderevaluationdate" type="date" id="tenderevaluationdate" class="form-control" placeholder="Enter Tender Evaluation date" required/>
                          </div>
                        </td>
                        <td>
                          <div class="form-line">
                            <input name="tenderawarddate" type="date" id="tenderawarddate" class="form-control" placeholder="Enter Award date" required/>
                          </div>
                        </td>
                        <td>
                          <div class="form-line">
                            <input name="tendernotificationdate" type="date" id="tendernotificationdate" class="form-control" placeholder="Enter Notification Date" required/>
                          </div>
                        </td>
                      </tr>
                    </table>
                  </div>
                  <div class="col-md-12">
                    <table class="table table-bordered table-striped table-hover" id="item_table" style="width:100%">
                      <tr>
                        <th style="width:33.3%">Contract Signature Date *</th>
                        <th style="width:33.3%">Contract Start Date *</th>
                        <th style="width:33.4%">Contract End Date *</th>
                      </tr>
                      <tr>
                        <td>
                          <div class="form-line">
                            <input name="tendersignaturedate" type="date" id="tendersignaturedate" class="form-control" placeholder="Click Signature Date" required/>
                          </div>
                        </td>
                        <td>
                          <div class="form-line">
                            <input name="tenderstartdate" type="date" id="tenderstartdate" class="form-control" placeholder="Click Start Date" required/>
                          </div>
                        </td>
                        <td>
                          <div class="form-line">
                            <input name="tenderenddate" type="date" id="tenderenddate" class="form-control"  placeholder="Click End Date" required/>
                          </div>
                        </td>
                      </tr>
                    </table>
                  </div>

                  <div class="col-md-12">
                    <label class="control-label">Contract/Tender Comments *:</label>
                    <p align="left">
                    <textarea name="comments" cols="45" rows="5" class="form-control" required="required"></textarea>
                    </p>
                  </div>
                </fieldset>
                <?php
                }
                ?>
                <fieldset class="scheduler-border">
                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                  <i class="fa fa-money" style="color:#F44336" aria-hidden="true"></i> Procurement Cost
                </legend>
                <input type="hidden" name="contributed_amount" id="contributed_amount" value="<?= $contribution_val ?>">
                <?php
                $Ocounter = 0;
                $summary = '';
                $output_cost_val = [];
                $total_amount = 0;
                do {
                  $Ocounter++;
                  //get indicator
                  $outputName = $row_rsOutputs['output'];
                  $outputCost = $row_rsOutputs['budget'];
                  $outputid = $row_rsOutputs['opid'];
                  $output_cost_val[] = $outputid;
                  $output_remeinder = 0;

                  $query_rsTender = $db->prepare("SELECT SUM(amount) as funds FROM tbl_project_cost_funders_share WHERE projid = :projid AND outputid = :opid AND type=:type");
                  $query_rsTender->execute(array(":projid" => $projid, ":opid" => $outputid, ":type" => 1));
                  $row_plan = $query_rsTender->fetch();
                  $totalRows_Tender = $query_rsTender->rowCount();
                  $contribution_amount = $row_plan['funds'];
                  ?>

                  <div class="panel panel-info">
                    <div class="panel-heading panel-info list-group-item list-group-item-action active collapse careted" data-toggle="collapse" data-target=".output<?php echo $outputid ?>">
                      <i class="fa fa-caret-down" aria-hidden="true"></i>
                      <strong> Output <?= $Ocounter ?>:
                        <span class="">
                          <?= $outputName ?>
                        </span>
                      </strong>
                    </div>
                    <div class="collapse output<?php echo $outputid ?>" style="padding:5px">
                      <div class="col-md-8">
                      </div>
                      <div class="col-md-4 bg-brown">
                        <h5>
                          <strong> Procurement Budget (Ksh):
                            <span class="">
                              <?= number_format($contribution_amount, 2) ?>
                            </span>
                          </strong>
                        </h5>
                      </div>
                      <div class="row clearfix" style="margin-top:3px; margin-bottom:3px">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <input type="hidden" name="projcost" id="projcost" value="<?= $projcost ?>">
                          <input type="hidden" name="projid" id="projid" value="<?= $hash ?>">
                          <input type="hidden" name="opid[]" id="opid<?= $outputid ?>" value="<?= $outputid ?>">
                          <input type="hidden" name="contribution_amount[]" id="contribution_amount<?= $outputid ?>" class="contribution_amount" value="<?= $contribution_amount ?>">
                          <input type="hidden" name="outputcost" id="outputcost<?= $outputid ?>" class="outputcost" value="<?= $outputCost ?>">
                          <input type="hidden" name="output_name" id="output_name<?= $outputid ?>" class="output_name" value="<?= $outputName ?>">
                          <!-- direct cost  -->
                          <div class="table-responsive">
                            <table class="table table-bordered" id="funding_table">
                              <thead>
                                <tr>
                                  <th style="width:2%"># </th>
                                  <th style="width:30%">Description </th>
                                  <th style="width:15%">Unit</th>
                                  <th style="width:18%">Unit Cost (Ksh)</th>
                                  <th style="width:15%">No. of Units</th>
                                  <th style="width:20%">Total Cost (Ksh)</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                $query_rsMilestones = $db->prepare("SELECT * FROM tbl_milestone WHERE projid='$projid' and outputid ='$outputid' ORDER BY sdate");
                                $query_rsMilestones->execute();
                                $row_rsMilestones = $query_rsMilestones->fetch();
                                $totalRows_rsMilestones = $query_rsMilestones->rowCount();
                                $mcounter = 0;
                                $sum = 0;
                                if ($totalRows_rsMilestones > 0) {
                                  do {
                                    $mcounter++;
                                    $milestone = $row_rsMilestones['msid'];
                                    $milestoneName = $row_rsMilestones['milestone'];
                                    $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE projid=:projid and msid=:milestone ORDER BY sdate");
                                    $query_rsTasks->execute(array(":projid" => $projid, ":milestone" => $milestone));
                                    $row_rsTasks = $query_rsTasks->fetch();
                                    $totalRows_rsTasks = $query_rsTasks->rowCount();

                                    $msid = $outputid . $milestone;
                                    if ($totalRows_rsTasks > 0) {
                                      ?>
                                      <input type="hidden" name="mileid<?= $outputid ?>[]" id="mileid<?= $milestone ?>" value="<?= $milestone ?>">
                                      <tr class="bg-blue-grey">
                                        <td><?= $Ocounter . "." . 1 . "." . $mcounter   ?></td>
                                        <td colspan="3">Milestone:<strong> <?= $milestoneName ?></strong> </td>
                                        <td colspan="1">
                                          Start Date
                                          <input type="date" name="mpsdate<?= $msid ?>" readonly id="mpsdate<?= $msid ?>" value="" class="form-control" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
                                        </td>
                                        <td colspan="1">
                                          End Date
                                          <input type="date" name="mpedate<?= $msid ?>" readonly id="mpedate<?= $msid ?>" value="" class="form-control" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
                                        </td>
                                      </tr>
                                      <?php
                                      $tcounter = 0;
                                      do {
                                        $tcounter++;
                                        $task =  $row_rsTasks['task'];
                                        $tkid =  $row_rsTasks['tkid'];
                                        $edate =  $row_rsTasks['edate'];
                                        $sdate =  $row_rsTasks['sdate'];
                                        $taskid = $outputid . $tkid; // to distinguish between different outputs
                                        $cost_type = 1;

                                        /* $datetime1 = new DateTime($sdate);
                                        $datetime2 = new DateTime($edate);
                                        $difference = $datetime1->diff($datetime2);
                                        $duration = $difference->d + 1; */

                                        /* $datetime1 = date_create($sdate);
                                        $datetime2 = date_create($edate);
                                        $difference = date_diff($datetime1, $datetime2);
                                        $duration = $difference->d + 1; */

                                        $firstDate  = date_create($sdate);
                                        $secondDate = date_create($edate);
                                        $difference = $firstDate->diff($secondDate);
                                        $duration = $difference->days;
                                        ?>
                                        <tr class="bg-grey">
                                          <td><?= $Ocounter . "." . 1 . "." . $mcounter . "." . $tcounter  ?></td>
                                          <td colspan="3"> Task:<strong> <?= $task ?></strong> </td>
                                          <td colspan="1">
                                            <strong style="color:#CDDC39">Start Date:</strong>
                                            <span style="color: white"> <?php echo date("d M Y",strtotime($sdate)); ?></span>
                                            <input type="hidden" name="sdate<?= $taskid ?>[]" id="sdate<?= $taskid ?>" value="<?= $sdate ?>" class="form-control" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
                                            <input type="date" name="psdate<?= $taskid ?>" id="psdate<?= $taskid ?>" onchange="start_date(<?= $tkid ?>, <?= $outputid ?>, <?= $milestone ?>)" value="" class="form-control mile_start<?= $msid ?>" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
                                            <input type="hidden" name="edate<?= $taskid ?>[]" id="edate<?= $taskid ?>" value="<?= $edate ?>" class="form-control" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
                                          </td>
                                          <td colspan="1">
                                            <strong style="color:#CDDC39">Duration</strong>
                                            <span style="color: white" id="pdurationmsg<?= $taskid ?>"> <?=$duration?> (Days)</span>
                                            <input type="number" name="tduration<?= $taskid ?>[]" id="tduration<?= $taskid ?>" onchange="duration(<?= $tkid ?>, <?= $outputid ?>, <?= $milestone ?>)" onkeyup="duration(<?= $tkid ?>, <?= $outputid ?>, <?= $milestone ?>)" class="form-control" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
                                            <input type="hidden" name="pduration<?= $taskid ?>[]" id="pduration<?= $taskid ?>" value="<?= $duration ?>" class="form-control" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
                                            <input type="hidden" name="pedate<?= $taskid ?>" id="pedate<?= $taskid ?>" value="" class="form-control mile_end<?= $msid ?>" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
                                          </td>
                                        </tr>
                                        <?php

                                        $query_rsDirect_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type AND tasks=:tkid");
                                        $query_rsDirect_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":tkid" => $tkid));
                                        $row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch();
                                        $totalRows_rsDirect_cost_plan = $query_rsDirect_cost_plan->rowCount();

                                        if ($totalRows_rsDirect_cost_plan > 0) {
                                                                            $plan_counter = 0;
                                          do {
                                            $plan_counter++;
                                            $newid = $taskid . $plan_counter;
                                            $unit = $row_rsDirect_cost_plan['unit'];
                                            $unit_cost = $row_rsDirect_cost_plan['unit_cost'];
                                            $units_no = $row_rsDirect_cost_plan['units_no'];
                                            $description = $row_rsDirect_cost_plan['description'];
                                            $rmkid = $row_rsDirect_cost_plan['id'];
                                            $total_cost = $unit_cost * $units_no;
                                            $output_remeinder = $output_remeinder + $total_cost;

                                            $query_rs_cost_funders =  $db->prepare("SELECT * FROM tbl_project_cost_funders_share WHERE projid =:projid AND type=:cost_type AND plan_id=:rmkid ");
                                            $query_rs_cost_funders->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":rmkid" => $rmkid));
                                            $row_rs_cost_funders = $query_rs_cost_funders->fetch();
                                            $totalRows_rs_cost_funders = $query_rs_cost_funders->rowCount();

                                            $fund_id = [];
                                            do {
                                              $fund_id[] = $row_rs_cost_funders['id'];
                                            } while ($row_rs_cost_funders = $query_rs_cost_funders->fetch());
                                            $fnid = implode(",", $fund_id);
                                            ?>

                                            <input type="hidden" name="taskid<?= $outputid ?>[]" id="taskid<?= $tkid ?>" value="<?= $tkid ?>">
                                            <tr>
                                              <td>
                                                <?= $plan_counter ?>
                                              </td>
                                              <td>
                                                <?= $description ?>
                                                <input type="hidden" name="rmkid<?= $taskid ?>[]" id="rmkid<?= $newid ?>" value="<?= $rmkid ?>">
                                                <input type="hidden" name="hdescription<?= $taskid ?>[]" id="description<?= $newid ?>" value="<?= $description ?>">
                                                <input type="hidden" name="description<?= $taskid ?>[]" value="<?= $description ?>" id="description<?= $newid ?>" readonly class="form-control" placeholder="Description" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
                                              </td>
                                              <td>
                                                <?= $unit ?>
                                                <input type="hidden" name="hunit<?= $taskid ?>[]" id="hunit<?= $newid ?>" value="<?= $unit ?>">
                                                <input type="hidden" name="dunit<?= $taskid ?>[]" value="<?= $unit ?>" id="unit<?= $newid ?>" readonly class="form-control" placeholder="Unit" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
                                              </td>
                                              <td>
                                                <input type="hidden" name="hunitcost<?= $taskid ?>[]" value="<?= $unit_cost ?>" id="hunitcost<?= $newid ?>">
                                                <input type="number" name="dunitcost<?= $taskid ?>[]" value="" id="unitcost<?= $newid ?>" onchange="number_of_units_change(<?= $tkid ?>, <?= $outputid ?>,1, <?= $plan_counter ?>)" onkeyup="number_of_units_change(<?= $tkid ?>, <?= $outputid ?>,1, <?= $plan_counter ?>)" class="form-control" placeholder="<?= $unit_cost ?>" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
                                              </td>
                                              <td>
                                                <input type="hidden" name="htotalunits<?= $taskid ?>[]" value="<?= $units_no ?>" id="htotalunits<?= $newid ?>">
                                                <input type="number" name="dtotalunits<?= $taskid ?>[]" value="" id="totalunits<?= $newid ?>" onkeyup="totalCost(<?= $tkid ?>, <?= $outputid ?>,1, <?= $plan_counter ?>)" onchange="totalCost(<?= $tkid ?>, <?= $outputid ?>,1, <?= $plan_counter ?>)" class="form-control" placeholder="<?= $units_no ?>" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
                                              </td>
                                              <td>
                                                <input type="hidden" name="htotalcost<?= $taskid ?>[]" value="<?= $total_cost ?>" id="htotalcost<?= $newid ?>">
                                                <input type="text" name="dtotalcost<?= $taskid ?>[]" value="" id="totalcost<?= $newid ?>" class="form-control totalCost summarytotal  output_cost<?= $outputid ?> direct_sub_total_amount1<?= $outputid ?>" placeholder="Total Cost" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required disabled>
                                              </td>
                                            </tr>
                                            <?php
                                          } while ($row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch());
                                        }
                                      } while ($row_rsTasks = $query_rsTasks->fetch());
                                    }
                                  } while ($row_rsMilestones = $query_rsMilestones->fetch());
                                }
                                ?>
                                <tfoot>
                                  <tr>
                                    <td colspan="3"></td>
                                    <td colspan="2"><strong>Sub Total</strong></td>
                                    <td colspan="1">
                                      <input type="text" name="d_sub_total_amount" value="" id="sub_total_amount1<?= $outputid ?>" class="form-control" placeholder="Total Sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="3"></td>
                                    <td colspan="2"> <strong>% Sub Total</strong></td>
                                    <td colspan="1">
                                      <input type="text" name="d_sub_total_percentage" value="" id="sub_total_percentage1<?= $outputid ?>" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="3"></td>
                                    <td colspan="2"> <strong>Planned amount Balance</strong></td>
                                    <td colspan="1">
                                      <input type="text" name="outputBal" id="" class="form-control output_cost_bal<?= $outputid ?>" value="<?= number_format(($contribution_amount), 2) ?>" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
                                    </td>
                                  </tr>
                                </tfoot>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php
                  $summary  .= '<tr>
                    <td>' . $Ocounter . '</td>
                    <td>' . $outputName . '</td>
                    <td style="text-align:left">' . number_format($output_remeinder, 2) . '</td>
                    <td id="summaryOutput' . $outputid . '"  style="text-align:left"></td>
                    <td id="perc' . $outputid .  '"  style="text-align:left"></td>
                  </tr>';
                } while ($row_rsOutputs = $query_rsOutputs->fetch());
                ?>
                        </fieldset>
                <fieldset class="scheduler-border" style="background-color:#ebedeb; border-radius:3px">
                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                  <i class="fa fa-file-text" aria-hidden="true" style="color:#F44336"></i> Procurement Cost Summary
                </legend>
                <div class="table-responsive">
                  <table class="table table-bordered table-striped table-hover" id="" style="width:100%">
                    <thead>
                      <tr>
                        <th width="2%">#</th>
                        <th width="64%">Output</th>
                        <th width="12%">Planned Amount (Ksh)</th>
                        <th width="12%">Procurement Amount (Ksh)</th>
                        <th width="10%">% Amount </th>
                      </tr>
                    </thead>
                    <tbody id="">
                      <?php echo $summary ?>
                      <tfoot>
                        <tr>
                          <td colspan="2">
                            <strong>
                              Total Amount
                            </strong>
                          </td>
                          <td style="text-align:left">
                            <strong>
                              <?= number_format($projcost, 2) ?>
                            </strong>
                          </td>
                          <td style="text-align:left">
                            <strong id="summary_total">
                            </strong>
                          </td>
                          <td style="text-align:left">
                            <strong id="summary_percentage">
                            </strong>
                          </td>
                        </tr>
                      </tfoot>
                    </tbody>
                  </table>
                </div>
                </fieldset>
                <fieldset class="scheduler-border">
                <legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-paperclip" style="color:#F44336" aria-hidden="true"></i> Procurement Documents/Files Attachment</legend>
                <!-- File Upload | Drag & Drop OR With Click & Choose -->
                <div class="header">
                    <i class="ti-link"></i>MULTIPLE FILES UPLOAD - WITH CLICK & CHOOSE
                </div>
                <div class="body">
                  <table class="table table-bordered" id="files_table">
                    <tr>
                      <th style="width:40%">Attachments *</th>
                      <th style="width:58%">Attachment Purpose *</th>
                      <th style="width:2%"><button type="button" name="addplus" onclick="add_row();" title="Add another document" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
                    </tr>
                  </table>
                  <script type="text/javascript">
                  function add_row(){
                    $rowno=$("#files_table tr").length;
                    $rowno=$rowno+1;
                    $("#files_table tr:last").after('<tr id="row'+$rowno+'"><td><input type="file" name="tenderfile[]" id="tenderfile[]" multiple class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control"  placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_row("row'+$rowno+'")><span class="glyphicon glyphicon-minus"></span></button></td></tr>');
                  }
                  function delete_row(rowno){
                    $('#'+rowno).remove();
                  }
                  </script>
                </div>
                <!-- #END# File Upload | Drag & Drop OR With Click & Choose -->
                </fieldset>
                        <div>
                            <input type="hidden" name="output_cost_val" id="output_cost_val" class="" value="<?= implode(",", $output_cost_val) ?>">
                        </div>
                        <input type="hidden" name="MM_insert" value="add_budget_line_frm">
                        <input type="hidden" name="user_name" value="<?= $user_name ?>">
                        <div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
                            <div class="col-md-4 text-center">
                            </div>
                            <div class="col-md-4 text-center">
                                <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                                <!--<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit-print" value="Save and Print" />-->
                            </div>
                            <div class="col-md-4 text-center">
                            </div>
                        </div>
                    </form>
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
<script type="text/javascript">
  $(document).ready(function(){
  // get contractor details
    $('#projcontractor').on('change',function(){
      var contrID = $(this).val();
      if(contrID){
        $.ajax({
          type:'POST',
          url:'addProjectLocation.php',
          data:'getcont='+contrID,
          success:function(html){
            $('#contrinfo').html(html);
          }
        });
      }else{
        $('#contrinfo').html('<div class="col-md-12">Select Contractor First</div>');
      }
    });
  });
</script>
  <script type="text/javascript">
      $(document).ready(function() {
          var projid = $("#projid").val();
          disable_refresh();
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
  </script>
  <script src="assets/custom js/add-procurement.js"></script>
