<?php

include_once "controller.php";

$itemId = $_POST['itemId'];
$TargetB = '';

$query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid='$itemId'");
$query_rsProjects->execute();
$row_rsProjects = $query_rsProjects->fetch();
$totalRows_rsProjects = $query_rsProjects->rowCount();

$progid = $row_rsProjects['progid'];
$projname = $row_rsProjects['projname'];
$projdurationInDays = $row_rsProjects['projduration'];
$projfscyear = $row_rsProjects['projfscyear'];
$projcode = $row_rsProjects['projcode'];
$projbudget = $row_rsProjects['projbudget'];
$projenddate = $row_rsProjects['projenddate'];
$projstartdate = $row_rsProjects['projstartdate'];
$projduration = $row_rsProjects['projduration'];
$projid = $row_rsProjects['projid'];

$query_rsYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year WHERE id = '$projfscyear'");
$query_rsYear->execute();
$row_rsYear = $query_rsYear->fetch();
$projstartyear =  $row_rsYear['yr'];
$projstart = $projstartyear  . '-07-01';

//fetch program details 
$query_item = $db->prepare("SELECT * FROM tbl_programs WHERE tbl_programs.progid = '$progid'");
$query_item->execute();
$row_item = $query_item->fetch();

$progstartDate = $row_item['syear'] . '-07-01'; //program start date  
$progduration = $row_item['years']; //program duration in years 
$sdate = $row_item['syear'] . '-06-30'; //for calculating program end year   
$progendDate = date('Y-m-d', strtotime($sdate . " + {$progduration} years"));  //program end date 


$projectendDate = date('Y-m-d', strtotime($projstart . " + {$projdurationInDays} days"));



$TargetB .= '   
  <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="card"> 
              <div class="header" >
                  <div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
                    <h4>Project Name:- ' . $projname . ' </h4> 
                  </div>  
              </div>
              <div class="body"> 
                <div class="row clearfix">
                  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <label for="syear">Program Start Date *:</label>  
                    <div class="form-line">
                      <input type="hidden"  name="progid" id="progid" class="form-control" value="' . $progid . '" required>
                      <input type="hidden"  name="projid" id="projid" class="form-control" value="' . $itemId . '" required>
                      <input type="text" name="progstartyear" id="progstartyear" value="' . date('d M Y', strtotime($progstartDate)) . '"  class="form-control" disabled>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <label for="syear">Program End Date *:</label>  
                    <div class="form-line">
                      <input type="text" name="programendyear" id="programendyear" value="' . date('d M Y', strtotime($progendDate)) . '"  class="form-control" disabled>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <label for="syear">Program Duration (Years)*:</label>  
                    <div class="form-line">
                      <input type="text" name="programduration" id="programduration" value="' . $progduration . '"  class="form-control" disabled>
                    </div>
                  </div>
                </div> 
                <div class="row clearfix">
                  <div class="col-md-4">
                      <label for="projstartYear">Project Expected Start Date *:</label>
                      <div class="form-line">
                        <input type="text" name="projstartYears" id="startYear1" class="form-control"  value="' . date('d M Y', strtotime($projstart)) . '" disabled>
                      </div>
                  </div> 
                  <div class="col-md-4">
                      <label for="projendYear">Project Expected End Date *:</label>
                      <div class="form-line"> 
                        <input type="text" name="projendYears" id="projendyearDate" class="form-control"  value="' . date('d M Y', strtotime($projectendDate)) . '"  disabled>
                      </div>
                  </div> 
                  <div class="col-md-4">
                    <label for="projduration">Project Duration (Days)*:</label>
                    <div class="form-line">
                      <input type="text"  name="projduration" id="projduration1" class="form-control" value="' . $projduration . '" required disabled>
                    </div>
                  </div>
                </div>  
              </div>
          </div>
      </div>
  </div>';


$query_Data = $db->prepare("SELECT * FROM  tbl_project_details  WHERE projid = '$itemId'");
$query_Data->execute();
$rows_OutpuData = $query_Data->rowCount();
$row_Data =  $query_Data->fetch();
$Ocounter = 0;

$TargetB .= '  
  <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="card"> 
              <div class="header" >
                  <div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
                    <h4>Project Output Details </h4> 
                  </div>  
              </div>
              <div class="body">
                  <div class="table-responsive">
                      <table class="table table-bordered table-striped table-hover" id="funding_table" style="width:100%">
                          <thead>
                              <tr>
                                  <th width="5%">#</th>
                                  <th width="20%">Output</th> 
                                  <th width="20%">Start Year</th>
                                  <th width="15%">Duration (Days) </th>
                                  <th width="20%">Ceiling (Ksh) </th>
                                  <th width="20%">Budget (Ksh) </th> 
                              </tr>
                          </thead>
                          <tbody id="funding_table_body" >';
$rowno = 0;
do {
  $Ocounter++;
  $rowno++;
  $year =  $row_Data['year'];
  $projoutputID =  $row_Data['id'];
  $duration =  $row_Data['duration'];
  $outputid =  $row_Data['outputid'];
  $budget =  $row_Data['budget'];
  $indicatorId =  $row_Data['indicator'];

  $query_rsYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year WHERE id = '$year'");
  $query_rsYear->execute();
  $row_rsYear = $query_rsYear->fetch();
  $projstartyear =  $row_rsYear['yr'];
  $projend = $projstartyear + 1;

  $query_dep = $db->prepare("SELECT tbl_indicator.indname FROM tbl_indicator   WHERE indid ='$indicatorId' ");
  $query_dep->execute();
  $row_dep = $query_dep->fetch();
  $indname =  $row_dep['indname'];

  $query_getprogbudget = $db->prepare("SELECT * FROM tbl_progdetails  WHERE indicator ='$indicatorId' AND year='$projstartyear' AND progid='$progid' ");
  $query_getprogbudget->execute();
  $row_rsprogbudget = $query_getprogbudget->fetch();
  $outputname = $row_rsprogbudget['output'];
  $progbudget = $row_rsprogbudget['budget'];

  $query_projcost = $db->prepare("SELECT SUM(budget) as projectbudget FROM  tbl_project_details WHERE  outputid ='$outputid' and year='$year' AND progid='$progid' LIMIT 1 ");
  $query_projcost->execute();
  $rowproj = $query_projcost->fetch();

  $totalUsed  =  $rowproj['projectbudget'];
  $projbudget = $progbudget - $totalUsed;

  $projceiling = $projbudget + $budget;

  $TargetB .= ' 
            <tr id="row' . $rowno . '">
                  <td width="5%">' . $Ocounter  . '</td>
                  <td>' . $outputname  . '
                    <input type="hidden" name="projoutput[]" id="projoutputrow' . $rowno . '"  value="' . $outputid . '" >
                    <input type="hidden" name="projoutputid[]" id="projoutputidrow' . $rowno . '"  value="' . $projoutputID . '" >
                    <input type="hidden" name="indicator[]" id="indicatorrow' . $rowno . '"  value="' . $indicatorId . '" >
                  </td> 
                  <td>' . $projstartyear . '/' . $projend  . '
                    <input type="hidden" name="opstaryear[]" id="opstaryearrow' . $rowno . '"  value="' . $year . '" > 
                  </td>
                  <td> 
                    <div class="form-input">
                      <input type="text" name="projoutputduration[]" id="projdurationrow' . $rowno . '"  value="' . $duration . '" placeholder="Enter" 
                      onkeyup= onKeyUpDays("row' . $rowno . '")  class="form-control validProjduration  projdurationrow' . $rowno . '" required /> 
                    </div> 
                  </td> 
                  <td> 
                  <div class="form-input">
                    <input type="hidden" name="projoutputceilingVal[]" id="projoutputceilingValrow' . $rowno . '"  value= ' . $projceiling . '  >
                    <input type="hidden" name="projoutputceilingValue[]" id="projoutputceilingValuerow' . $rowno . '"  value= ' . $projceiling . '  >
                    <span id="projoutputceilingrow' . $rowno . '" class="projoutputdetailsC" style="color:coral">' . number_format($projceiling, 2) . '</span>
                  </div>
                  </td> 
                  <td> 
                    <div class="form-input">
                      <input type="hidden" name="projudget[]" id="projudget' . $rowno . '"  value= ' . $budget . '  >
                      <input type="number" name="projcost[]" onkeyup= onKeyUpBudget("row' . $rowno . '") id="projcostrow' . $rowno .  '" value="' . $budget . '"  class="form-control" required />
                    </div> 
                  </td>    
              </tr>';
} while ($row_Data =  $query_Data->fetch());
$TargetB .= '
                          </tbody> 
                      </table> 
                  </div>
              </div>
          </div>
      </div>
  </div>';


$query_rsOutputCeiling =  $db->prepare("SELECT SUM(budget) as budget FROM  tbl_project_details WHERE projid ='$itemId'");
$query_rsOutputCeiling->execute();
$row_rsOutputCeiling = $query_rsOutputCeiling->fetch();
$totalRows_rsOutputCeiling = $query_rsOutputCeiling->rowCount();
$projcounter = $row_rsOutputCeiling['budget'];

$rowno = 0;
$query_rsProjFinancier =  $db->prepare("SELECT * FROM tbl_myprojfunding WHERE projid ='$itemId'");
$query_rsProjFinancier->execute();
$row_rsProjFinancier = $query_rsProjFinancier->fetch();
$totalRows_rsProjFinancier = $query_rsProjFinancier->rowCount();


$TargetB .= '
  <fieldset class="scheduler-border"> 
      <div class="col-md-12" id="indirectbenname">
          <label for="projindirectbeneficiary" id="projindirectbeneficiary" class="control-label">Total Output Cost *:</label>
          <div class="form-input">
              <input type="hidden" name="financierceiling" id="financierceiling" value="'.$projcounter.'">
              <input type="text" name="outputcost" id="outputcost" value="" placeholder="0" class="form-control" disabled>
          </div>
      </div>
      <div class="col-md-12" id="projfinancier">
          <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover" id="financier_table" style="width:100%">
                  <thead>
                      <tr>
                          <th width="10%">#</th>
                          <th width="30%">Financier</th>
                          <th width="30%">Ceiling (Ksh)</th>
                          <th width="30%">Amount (Ksh)</th> 
                      </tr>
                  </thead>
                  <tbody id="financier_table_body">';
do {
  $rowno++;
  $progfundid =  $row_rsProjFinancier['progfundid'];
  $projamountfunding =  $row_rsProjFinancier['amountfunding'];

  //get program funding amount for this financier  
  $query_rsprogFunding =  $db->prepare("SELECT amountfunding FROM tbl_myprogfunding WHERE id ='$progfundid'");
  $query_rsprogFunding->execute();
  $row_rsprogFunding = $query_rsprogFunding->fetch();
  $totalRows_rsprogFunding = $query_rsprogFunding->rowCount();
  $amountprogFunding = $row_rsprogFunding['amountfunding'];

  // get the sum of the amount of fuds used up by all pojects under this given program 
  $query_rsprojectFunding =  $db->prepare("SELECT SUM(amountfunding) as amountfunding FROM tbl_myprojfunding WHERE progfundid ='$progfundid'");
  $query_rsprojectFunding->execute();
  $row_rsprojectFunding = $query_rsprojectFunding->fetch();
  $totalRows_rsprojectFunding = $query_rsprojectFunding->rowCount();
  $amountprojectFunding = $row_rsprojectFunding['amountfunding'];

  // Get the ceiling by adding the amount spent on this project to the overall remaining funds 
  $remaining =  ($amountprogFunding - $amountprojectFunding) + $projamountfunding;

  $query_rsFunding =  $db->prepare("SELECT * FROM tbl_myprogfunding WHERE progid ='$progid'");
  $query_rsFunding->execute();
  $row_rsFunding = $query_rsFunding->fetch();
  $totalRows_rsFunding = $query_rsFunding->rowCount();

  $inputs = '<option value="">Select Financier from list</option>';
  do {
    $source = $row_rsFunding['sourceid'];
    $progfundids = $row_rsFunding['id'];
	$rate = $row_rsFunding['rate'];
	$progfunds = $row_rsFunding['amountfunding'] * $rate;

    $query_rsprojFunding =  $db->prepare("SELECT SUM(amountfunding) as amountfunding FROM tbl_myprojfunding WHERE progid ='$progid' and progfundid ='$progfundids' ");
    $query_rsprojFunding->execute();
    $row_rsprojFunding = $query_rsprojFunding->fetch();
    $totalRows_rsprojFunding = $query_rsprojFunding->rowCount();
	
    $projfunds = $row_rsprojFunding['amountfunding'];
    $remaining = $progfunds - $projfunds;
    $remainingB = ($progfunds - $projfunds) - $projamountfunding;
	
    if ($remaining > 0) {
      if ($row_rsFunding['sourcecategory']  == "donor") {
        $query_rsDonor = $db->prepare("SELECT * FROM tbl_donors WHERE dnid='$source'");
        $query_rsDonor->execute();
        $row_rsDonor = $query_rsDonor->fetch();
        $totalRows_rsDonor = $query_rsDonor->rowCount();
		
        $donor = $row_rsDonor['donorname'];
        if ($row_rsFunding['id'] == $progfundid) {
          $inputs .= '<option value="' . $row_rsFunding['id'] . '" selected>' . $donor . '</option>';
        } else {
          $inputs .= '<option value="' . $row_rsFunding['id'] . '">' . $donor . '</option>';
        }
      } else if ($row_rsFunding['sourcecategory']  == "others") {
        $query_rsFunder = $db->prepare("SELECT * FROM tbl_funder WHERE id='$source'");
        $query_rsFunder->execute();
        $row_rsFunder = $query_rsFunder->fetch();
        $totalRows_rsFunder = $query_rsFunder->rowCount();
        $funder = $row_rsFunder['name'];
        if ($row_rsFunding['id'] == $progfundid) {
          $inputs .= '<option value="' . $row_rsFunding['id'] . '" selected>' . $funder . '</option>';
        } else {
          $inputs .= '<option value="' . $row_rsFunding['id'] . '">' . $funder . '</option>';
        }
      }
    }
  } while ($row_rsFunding = $query_rsFunding->fetch());

  $TargetB .= '<tr id="row' . $rowno . '">
    <td>
    ' . $rowno . '
    </td>
    <td>
      <input type="hidden" name="progfundid[]" id="progfundidrow' . $rowno . '" value="' . $progfundid . '" />
      <select disabled onchange=financeirChange("row' . $rowno . '") data-id="' . $rowno . '" name="finance[]" id="financerow' . $rowno . '" class="form-control validoutcome selectedfinance"  required="required">
        ' . $inputs . '
      </select>
    </td>
    <td>
      <input type="hidden" name="ceilingval[]" id="ceilingvalrow' . $rowno . '" value="' . $remaining . '" />
      <span id="financierCeilingrow' . $rowno . '" style="color:red"> ' . number_format($remainingB, '2') . '</span>
    </td>
    <td>
      <input type="text" name="amountfunding[]" onkeyup=amountfunding("row' . $rowno . '") onchange=amountfunding("row' . $rowno . '") id="amountfundingrow' . $rowno . '" placeholder="Enter" class="form-control" value="' . $projamountfunding . '" required />
    </td> 
  </tr>';
} while ($row_rsProjFinancier = $query_rsProjFinancier->fetch());
$TargetB     .= '
                      </tbody>
                    </table>
                </div>
            </div>
        </fieldset>';



$query_OutputData = $db->prepare("SELECT * FROM tbl_project_details WHERE projid = '$itemId' ");
$query_OutputData->execute();
$rows_OutpuData = $query_OutputData->rowCount();
$row_OutputData =  $query_OutputData->fetch();
$counter = 0;
$Targets = "";
do {
  $counter++;
  // get indicator name 
  $indicator = $row_OutputData['indicator'];
  $query_rsIndicator = $db->prepare("SELECT indname, indid FROM tbl_indicator WHERE indid ='$indicator'");
  $query_rsIndicator->execute();
  $row_rsIndicator = $query_rsIndicator->fetch();
  $indname = $row_rsIndicator['indname'];
  $projoutputID = $row_OutputData['id'];
  // get unit 
  $query_Indicator = $db->prepare("SELECT tbl_measurement_units.unit FROM tbl_indicator  INNER JOIN tbl_measurement_units ON tbl_measurement_units.id =tbl_indicator.unit WHERE tbl_indicator.indid ='$indicator' AND baseline=1 AND indcategory='Output' ");
  $query_Indicator->execute();
  $row = $query_Indicator->fetch();
  $unit = $row['unit'];

  // Get outputstart year
  $year = $row_OutputData['year'];
  $query_rsIndicatorYear =  $db->prepare("SELECT yr FROM tbl_fiscal_year WHERE id='$year'");
  $query_rsIndicatorYear->execute();
  $row_rsIndicatorYear = $query_rsIndicatorYear->fetch();
  $projstartyear = $row_rsIndicatorYear['yr'];

  // get output name 
  $outputid = $row_OutputData['outputid'];
  $query_rsOutput = $db->prepare("SELECT * FROM tbl_progdetails WHERE id='$outputid'");
  $query_rsOutput->execute();
  $row_rsOutput = $query_rsOutput->fetch();
  $outputName = $row_rsOutput['output'];
  $programid = $row_rsOutput['progid'];



  $Targets .= '
    <div class="row clearfix " id="rowcontainerrow' . $counter . '">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="card">
              <div class="header">
                  <div class="col-md-4 clearfix" style="margin-top:5px; margin-bottom:5px">
                      <h5 style="color:#FF5722"><strong> Output: ' .  $outputname . '</strong></h5>
                  </div>
                  <div class="col-md-4 clearfix" style="margin-top:5px; margin-bottom:5px">
                      <h5 style="color:#FF5722"><strong> Indicator: ' . $indname . '</strong></h5>
                  </div>
                  <div class="col-md-4 clearfix" style="margin-top:5px; margin-bottom:5px">
                      <h5 style="color:#FF5722"><strong> Unit : ' .  $unit . '</strong></h5>
                  </div>
              </div>
              <div class="body">
                  <div class="row">
                      <div class="col-md-12">
                          <div class="spanYears">';
  //get financial years with specific outputid 
  $query_projYear = $db->prepare("SELECT * FROM  tbl_project_output_details  WHERE projid = '$projid' and projoutputid = '$projoutputID'  GROUP BY year");
  $query_projYear->execute();
  $rows_OutpuprojYear = $query_projYear->rowCount();
  $row_projYear =  $query_projYear->fetch();
  $TargetPlan = "";
  $containerTH = "";
    $containerTH2 = "";
    $contain = "";
    $containerTB = "";
  do {
    $Pyear =  $row_projYear['year'];
    //get financial years with specific outputid 
    $query_projTarget = $db->prepare("SELECT * FROM  tbl_project_output_details  WHERE projid = '$projid' and projoutputid = '$projoutputID' and year = '$Pyear' and  indicator = '$indicator' ");
    $query_projTarget->execute();
    $rows_OutpuprojTarget = $query_projTarget->rowCount();
    $row_projTarget =  $query_projTarget->fetch();
    $totalRows_ProjTarget = $query_projTarget->rowCount();
    $Fyear =  $Pyear + 1;


    // get program targets
    $query_getProgTarget = $db->prepare("SELECT * FROM tbl_progdetails  WHERE indicator ='$indicator' AND year='$Pyear' AND progid='$programid' ");
    $query_getProgTarget->execute();
    $row_rsProgTarget = $query_getProgTarget->fetch();
    $totalRows_ProgTarget = $query_getProgTarget->rowCount();
    $progtarget  =  $row_rsProgTarget['target'];

    // get sum of all used program targets under specific indicator 
    $query_rsprojTarget = $db->prepare("SELECT SUM(target) as projtarget FROM  tbl_project_output_details  WHERE progid='$programid' AND indicator ='$indicator' and year='$Pyear'  LIMIT 1 ");
    $query_rsprojTarget->execute();
    $row_rsprojTarget = $query_rsprojTarget->fetch();
    $totalRows_rsprojTarget = $query_rsprojTarget->rowCount();
    $totalUsedTarget  =  $row_rsprojTarget['projtarget'];

    // get sum of the given project indicator targets
    $query_proTargetSum = $db->prepare("SELECT SUM(target) as projtargets FROM  tbl_project_output_details WHERE progid='$programid' AND indicator ='$indicator' and year='$Pyear' and projid='$projid'  LIMIT 1 ");
    $query_proTargetSum->execute();
    $rowprojSum = $query_proTargetSum->fetch();
    $targetSum = $rowprojSum['projtargets'];

    $projTarget = ($progtarget - $totalUsedTarget)  + $targetSum;
    $projTargetB = ($progtarget - $totalUsedTarget);

    $TargetPlan .=  '<input type="hidden" class="projdurationerow' . $counter . '" name="projTragetplan[]" value="' . $Pyear . '" />';
    $containerTH .=
      '<th colspan="' . $totalRows_ProjTarget . '">
          ' . $Pyear .  "/" . $Fyear . ' Remaining Target 
          <input type="hidden" class="output' . $outputid . '"  name="projoutputYearValue[]" value="' . $Pyear . '" /> 
          <input type="hidden" name="targetPlan[]" value="' . $projTarget  .  '" id="targetVal' . $Pyear .  $counter . '" >
          <span id="targetmsg' . $Pyear .  $counter . '" style="color:red"> (' . $projTargetB . ')</span>
      </th>';
    $k = 0;

    do {
      $Target =  $row_projTarget['target'];
      $Tyear =  $row_projTarget['year'];
      $k++;
      $containerTH2 .=  '<th width="300px"> Quarter ' .  $k . 'Target</th>';
      $containerTB .=
        '<td width="300px"> 
          <input type="hidden"  name="projoutputValue[]" value="' . $projoutputID . '" />  
            <input type="number" onkeyup=targetsBlur("' .  $counter  .  '","' .  $Tyear . '") name="target[]" id="' .  $Tyear . '" placeholder="Enter" data-id="' .  $Tyear . '" value="' . $Target . '" class="form-control selectSource' . $counter .  $Tyear . " selected" . $outputid . $Tyear . '" required>' .
        "</td>";
      $contain  .=  '<input type="hidden" name="projyear[]"   value="' . $Tyear .  '" />';
    } while ($row_projTarget =  $query_projTarget->fetch());
  } while ($row_projYear =  $query_projYear->fetch());

  $Targets .= '
  <div id="theadyearsrow' .  $counter  .  '">

 ' . $TargetPlan .  $contain . '</div>
                          </div>
                      </div>
                  </div>
                  <div class="table-responsive">
                      <table class="table table-bordered table-striped table-hover" id="targets" style="width:100%">
                          <thead>
                              <tr id="target_headrow' .  $counter  .  '">
                                  ' . $containerTH . '
                              </tr>
                          </thead>
                          <tbody>
                              <tr id="target_bodyheadrow' .  $counter  .  '">
                                  ' . $containerTH2 . '
                              </tr>
                              <tr id="target_bodyrow' .  $counter  .  '">
                                  ' . $containerTB . '
                              </tr>
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
      </div>
  </div>';
} while ($row_OutputData =  $query_OutputData->fetch());

echo $TargetB . $Targets;
