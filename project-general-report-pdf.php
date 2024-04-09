<?php
$stplan = (isset($_GET['projid'])) ? $_GET['projid'] : "";

 
//include_once 'projtrac-dashboard/resource/session.php';

include_once '../projtrac-dashboard/resource/Database.php';
include_once '../projtrac-dashboard/resource/utilities.php';
require_once __DIR__ . '../../vendor/autoload.php'; 

try { 
    $logo = 'logo.jpg';
    $logo = 'logo.jpg';
    $mpdf = new \Mpdf\Mpdf(['setAutoTopMargin' => 'pad']);
    $mpdf->SetWatermarkImage($logo);
    $mpdf->showWatermarkImage = true;
    $mpdf->SetProtection(array(), 'UserPassword', 'password');
 
    $mpdf->AddPage('l');
    $mpdf->WriteHTML('
      <div style="text-align: center;">
         <img src="' . $logo . '" height="180px" style="max-height: 200px; text-align: center;"/>
         <h2 style="">COUNTY GOVERNMENT OF UASIN GISHU</h2>
         <br/>
         <hr/>
         <h3 style="margin-top:10px;" >PROJECT GENERAL REPORT</h3> 
         <hr/>
         <div style="margin-top:80px;" >
            <address>
               <h5>The County Treasury P. O. Box 40-30100 ELDORET, KENYA </h5>
               <h5>Email: info@uasingishu.go.ke </h5>
               <h5>Website: www.uasingishu.go.ke </h5>
            </address>
            <h4>' . date('d M Y') . '</h4>
         </div>
      </div>
      ');

    $mpdf->SetHTMLHeader('
      <div style="text-align: right;">
        <img src="' . $logo . '" height="80px" style="max-height: 100px; text-align: center;"/>
         <p><i>Project general report</i></p>
      </div>'
    );

    
    $mpdf->AddPage('L');
    $body ="";


    $body .='
      <table class="table table-bordered table-striped table-hover  " id="" style="background-color:light-blue">
         <thead>
            <tr class="bg-orange">
                  <th style="width:2%"></th>
                  <th style="width:7%">#</th>
                  <th style="width:30%" colspan="3">Output</th>
                  <th style="width:25%" colspan="2">Indicator </th>
                  <th style="width:7%">Target</th>
                  <th style="width:7%">Achieved</th>
                  <th style="width:7%">Rate %</th>  
            </tr>
         </thead>
         <tbody>';    
        $query_rsProjects = $db->prepare("SELECT *  FROM tbl_projects WHERE deleted='0' and projid='$projid'");
        $query_rsProjects->execute();
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();
        $projstage  = $row_rsProjects['projstage']; 
    
        $query_rsOutputs = $db->prepare("SELECT g.output as  output, d.id as opid, g.indicator 
        FROM tbl_project_details d INNER JOIN tbl_progdetails g ON g.id = d.outputid WHERE projid='$projid'");
        $query_rsOutputs->execute();
        $row_rsOutputs = $query_rsOutputs->fetch();
        $totalRows_rsOutputs = $query_rsOutputs->rowCount();
            if ($totalRows_rsOutputs > 0) {
                  $Ocounter = 0;
                  do {
                     $Ocounter++;
                     $outputid = $row_rsOutputs['opid'];
                     $indid = $row_rsOutputs['indicator'];

                     $query_rsAchieved =  $db->prepare("SELECT SUM(actualoutput) as achieved FROM tbl_monitoringoutput WHERE projid=:projid AND opid=:opid"); 
                     $query_rsAchieved->execute(array(":projid"=>$projid, ":opid"=>$outputid));
                     $row_rsAchieved = $query_rsAchieved->fetch();
                     $totalRows_rsAchieved = $query_rsAchieved->rowCount();           
                     $achieved = 0; 
                     $achieved = $row_rsAchieved['achieved']; 

                     $query_rsTarget =  $db->prepare("SELECT total_target FROM tbl_project_details  WHERE projid=:projid AND id=:opid"); 
                     $query_rsTarget->execute(array(":projid"=>$projid, ":opid"=>$outputid));
                     $row_rsTarget = $query_rsTarget->fetch();
                     $totalRows_rsTarget = $query_rsTarget->rowCount();        
                     $target = $row_rsTarget['total_target']; 
                     $rate =0; 

                     if($target > 0  && $achieved > 0){
                        $rate = ($achieved/$target) * 100;
                     }

                     $query_rsMilestones = $db->prepare("SELECT *  FROM tbl_milestone WHERE projid=:projid AND outputid=:outputid ");
                     $query_rsMilestones->execute(array(":projid" => $projid, ":outputid" => $outputid));
                     $row_rsMilestones = $query_rsMilestones->fetch();
                     $totalRows_rsMilestones = $query_rsMilestones->rowCount();

                     //get indicator
                     $query_rsIndicator = $db->prepare("SELECT *  FROM tbl_indicator WHERE indid='$indid' ");
                     $query_rsIndicator->execute();
                     $row_rsIndicator = $query_rsIndicator->fetch();
                     $totalRows_rsIndicator = $query_rsIndicator->rowCount(); 
                     
                     $body .='
                     <tr class="outputs" style="background-color:#eff9ca">
                        <td>
                              
                        </td>
                        <td align="center">'. $Ocounter .'</td>
                        <td colspan="3">'.  $row_rsOutputs['output'] .'</td>
                        <td colspan="2">'. $row_rsIndicator['indicator_name'] .'</td>
                        <td>'. number_format($target) .'</td>
                        <td>'. number_format($achieved ).'</td> 
                        <td>'. number_format($rate, 2) .'</td> 
                     </tr>
                     <tr style="background-color:#FF9800; color:#FFF">
                        <th style="width: 2%"></th>
                        <th style="width: 2%">#</th>
                        <th colspan="3" style="width: 96%">Milestone Name</th>
                        <th style="width:7%">Status</th>
                        <th style="width:7%">Progress</th>
                        <th style="width:7%">Budget</th>
                        <th style="width:7%">Start Date</th>
                        <th style="width:7%">End Date</th>
                     </tr>';
                     
                     if ($totalRows_rsMilestones > 0) {
                        $mcounter = 0;
                        do {
                              $mcounter++;
                              $milestone = $row_rsMilestones['msid'];
                              $mile = $row_rsMilestones['milestone'];
                              $sdate = $row_rsMilestones['sdate'];
                              $edate = $row_rsMilestones['edate'];
                              $mstatus = $row_rsMilestones['status'];
                              $query_rsTasks = $db->prepare("SELECT *  FROM tbl_task WHERE projid='$projid' and msid='$milestone' ");
                              $query_rsTasks->execute();
                              $row_rsTasks = $query_rsTasks->fetch();
                              $totalRows_rsTasks = $query_rsTasks->rowCount();

                              $status = $db->prepare("SELECT * FROM `tbl_status` WHERE statusid=:statusid LIMIT 1");
                              $status->execute(array(":statusid" => $mstatus));
                              $rowstatus = $status->fetch(); 
                              $milestatus = $rowstatus["statusname"]; 

                              $milefinance = $db->prepare(" SELECT SUM((p.units_no * p.unit_cost)) milecost FROM tbl_project_direct_cost_plan p 
                              INNER JOIN tbl_task t ON t.tkid = p.tasks WHERE t.projid='$projid' AND t.msid='$milestone'");
                              $milefinance->execute();
                              $rowmilefinance = $milefinance->fetch();
                              $milestonefinance =number_format($rowmilefinance["milecost"], 2);

                              // project percentage progress
                              $query_rsMlsProg =  $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid = :projid AND outputid=:outputid AND  msid=:msid");
                              $query_rsMlsProg->execute(array(":projid" => $projid, ":outputid"=>$outputid, ":msid"=>$milestone));	
                              $row_rsMlsProg = $query_rsMlsProg->fetch();
                              $percent1 = 0;
                              
                              if($row_rsMlsProg["mlprogress"] >0 && $row_rsMlsProg["nmb"] > 0 ){
                                 $prjprogress = $row_rsMlsProg["mlprogress"]/$row_rsMlsProg["nmb"];
                                 $percent1 = round($prjprogress,2);
                              }


                              $percent ='';
                              if ($percent1 < 100) {
                                 $percent = '
                                 <div class="progress" style="height:20px; font-size:10px; color:black">
                                    <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="'.$percent1.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$percent1.'%; height:20px; font-size:10px; color:black">
                                          '.$percent1.'%
                                    </div>
                                 </div>';
                              } 
                              elseif ($percent1 ==100){
                                 $percent = '
                                 <div class="progress" style="height:20px; font-size:10px; color:black">
                                    <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="'.$percent1.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$percent1.'%; height:20px; font-size:10px; color:black">
                                    '.$percent1.'%                                   
                                    </div>
                                 </div>';
                              }


                              $mactive = '';
                              if($mstatus == 3){
                                 $mactive = '<button type="button" class="btn bg-yellow waves-effect" style="width:100%">'.$milestatus. '</button>';
                              }else if($mstatus == 4){
                                 $mactive = '<button type="button" class="btn btn-primary waves-effect" style="width:100%">'.$milestatus.'</button>';
                              }else if($mstatus == 11){
                                 $mactive = '<button type="button" class="btn bg-red waves-effect" style="width:100%">'.$milestatus. '</button>';
                              }else if($mstatus == 5){
                                 $mactive = '<button type="button" class="btn btn-success waves-effect" style="width:100%">'.$milestatus. '</button>';
                              }else if($mstatus == 1){
                                 $mactive = '<button type="button" class="btn bg-grey waves-effect" style="width:100%">'.$milestatus. '</button>';
                              }else if($mstatus == 2){
                                 $mactive = '<button type="button" class="btn bg-brown waves-effect" style="width:100%">'.$milestatus. '</button>';
                              }else if($mstatus == 6){
                                 $mactive = '<button type="button" class="btn bg-pink waves-effect" style="width:100%">'.$milestatus. '</button>';
                              }
                              $body .='
                              <tr class="collapse output" style="background-color:#CDDC39">
                                 <td align="center" class="mb-0">
                                    
                                 </td>
                                 <td align="center">'.$Ocounter . "." . $mcounter .'</td>
                                 <td colspan="3">' .$row_rsMilestones['milestone'] .'</td>
                                 <td>'.$mactive .'</td>
                                 <td>'. $percent .'</td>
                                 <td>'. $milestonefinance .'</td>
                                 <td>'. $sdate .'</td>
                                 <td>'. $edate .'</td>
                              </tr>
                              <tr class="collapse milestone<?php echo $milestone  ?>" data-parent="outputs<?php echo $outputid ?>" style="background-color:#b8f9cb; color:#FFF">
                                 <th style="width: 2%"></th>
                                 <th style="width: 2%">#</th>
                                 <th colspan="3" style="width: 86%">Task Name</th> 
                                 <th style="width:7%">Status</th>
                                 <th style="width:7%">Progress</th>
                                 <th style="width:7%">Budget</th>
                                 <th style="width:7%">Start Date</th>
                                 <th style="width:7%">End Date</th>
                              </tr>';

                              $tcounter = 0;
                              if ($totalRows_rsTasks > 0) {
                                 do {
                                    $tcounter++;
                                    $taskid = $row_rsTasks['tkid'];
                                    $tsdate = $row_rsTasks['sdate'];
                                    $tedate = $row_rsTasks['edate'];                                                        

                                    $tsksfinance = $db->prepare(" SELECT (p.units_no * p.unit_cost) as taskcost FROM tbl_project_direct_cost_plan p 
                                    INNER JOIN tbl_task t ON t.tkid = p.tasks WHERE t.tkid='$taskid' AND t.projid='$projid' AND t.msid='$milestone'");
                                    $tsksfinance->execute();
                                    $rowtsksfinance = $tsksfinance->fetch();
                                    $tasksfinance = number_format($rowtsksfinance["taskcost"], 2);

                                    $query_rsChecklist = $db->prepare("SELECT *  FROM tbl_project_monitoring_checklist WHERE  taskid='$taskid'");
                                    $query_rsChecklist->execute();
                                    $row_rsChecklist = $query_rsChecklist->fetch();
                                    $totalRows_rsChecklist = $query_rsChecklist->rowCount(); 

                                    $tstatus = $row_rsTasks['status']; 
                                    $tskstatus = $db->prepare("SELECT * FROM `tbl_status` WHERE statusid=:statusid LIMIT 1");
                                    $tskstatus->execute(array(":statusid" => $tstatus));
                                    $rowtskstatus = $tskstatus->fetch();
                                    $taskstatus = $rowtskstatus["statusname"]; 

                                    $tactive = '';
                                    if($tstatus == 3){
                                          $tactive = '<button type="button" class="btn bg-yellow waves-effect" style="width:100%">'.$taskstatus. '</button>';
                                    }else if($tstatus == 4){
                                          $tactive = '<button type="button" class="btn btn-primary waves-effect" style="width:100%">'.$taskstatus.'</button>';
                                    }else if($tstatus == 11){
                                          $tactive = '<button type="button" class="btn bg-red waves-effect" style="width:100%">'.$taskstatus. '</button>';
                                    }else if($tstatus == 5){
                                          $tactive = '<button type="button" class="btn btn-success waves-effect" style="width:100%">'.$taskstatus. '</button>';
                                    }else if($tstatus == 1){
                                          $tactive = '<button type="button" class="btn bg-grey waves-effect" style="width:100%">'.$taskstatus. '</button>';
                                    }else if($tstatus == 2){
                                          $tactive = '<button type="button" class="btn bg-brown waves-effect" style="width:100%">'.$taskstatus. '</button>';
                                    }else if($tstatus == 6){
                                          $tactive = '<button type="button" class="btn bg-pink waves-effect" style="width:100%">'.$taskstatus. '</button>';
                                    } 

                                    $percent5 ='';
                                    $percent3 =66;
                                    if ($percent3 < 100) {
                                          $percent5 = '
                                          <div class="progress" style="height:20px; font-size:10px; color:black">
                                             <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="'.$percent3.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$percent3.'%; height:20px; font-size:10px; color:black">
                                                '.$percent3.'%
                                             </div>
                                          </div>';
                                    } 
                                    elseif ($percent3 ==100){
                                          $percent5 = '
                                          <div class="progress" style="height:20px; font-size:10px; color:black">
                                             <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="'.$percent3.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$percent3.'%; height:20px; font-size:10px; color:black">
                                             '.$percent3.'%                                   
                                             </div>
                                          </div>';
                                    } 
                                    $body .='
                                    <tr style="background-color:#FFF">
                                          <td style="background-color:#b8f9cb"></td>
                                          <td align="center">'. $Ocounter . "." . $mcounter . "." . $tcounter .'</td>
                                          <td COLSPAN=3>'. $row_rsTasks['task'].'</td>
                                          <td>'.  $tactive .'</td>
                                          <td>'.  $percent5 .'</td>
                                          <td>'.  $tasksfinance.'</td>
                                          <td>'.  $tsdate .'</td>
                                          <td>'.  $tedate .'</td>
                                          <td> 
                                          </td>
                                    </tr>';


                              if($totalRows_rsChecklist > 0 && $projstage >= 10){ 
                              $body .='
                                 <tr style="background-color:#b8f9cb; color:#FFF">
                                    <th style="width: 2%"></th>
                                    <th style="width: 2%">#</th>
                                    <th colspan="6" style="width: 86%">Task List</th> 
                                 </tr>';

                                 $rowno = 0;
                                 do {
                                    $checklist =  $row_rsChecklist['name'];
                                    $rowno++;
                                    $body .=
                                          '<tr id="row' . $rowno  . '" class ="collapse milestone'. $milestone.'" d >
                                          <td></td>    
                                          <td align="center">'. $Ocounter . "." . $mcounter . "." . $tcounter."." . $rowno . '</td>
                                             <td colspan="3">
                                             ' . $checklist . '
                                             </td> 
                                          </tr>';
                                 } while ($row_rsChecklist = $query_rsChecklist->fetch());
                              }
                                 } while ($row_rsTasks = $query_rsTasks->fetch());
                              }
                        } while ($row_rsMilestones = $query_rsMilestones->fetch());
                     }
                  } while ($row_rsOutputs = $query_rsOutputs->fetch());
            }
            $body .='
         </tbody>
      </table>';

    $body .= '
      <table width="100%" border="1" cellspacing="0" cellpadding="0" style="border:#EEE thin solid">
         <thead>
            <tr id="" style="padding-left:10px background-color:light-blue">
                  <td width="3%" height="35"><div align="center"><strong id="colhead">#</strong></div></td>
                  <td width="18%"><strong id="colhead">&nbsp;&nbsp;Full Name</strong></td>
                  <td width="13%"><strong id="colhead">&nbsp;&nbsp;Designation</strong></td>
                  <td width="35%"><strong id="colhead">&nbsp;&nbsp;Conservancy</strong></td>
                  <td width="12%"><strong id="colhead">&nbsp;&nbsp;Ecosystem</strong></td>
                  <td width="10%"><strong id="colhead">&nbsp;&nbsp;Email</strong></td>
                  <td width="10%"><strong id="colhead">&nbsp;&nbsp;Phone</strong></td>
            </tr>
         </thead>
         <tbody>';
            $query_rsPrjTeam =  $db->prepare("SELECT t.fullname AS fullname, t.title AS title, t.email AS email, t.phone AS phone, d.designation AS designation, t.ministry AS ministry, t.department AS department FROM tbl_projmembers m INNER JOIN tbl_projteam2 t ON t.ptid=m.ptid INNER JOIN tbl_pmdesignation d ON d.moid=t.designation WHERE m.projid = '$projid' AND t.disabled='0' ORDER BY d.Reporting ASC");
            $query_rsPrjTeam->execute();	
            $totalRows_rsPrjTeam = $query_rsPrjTeam->rowCount();
            $num =0;
            while ($row_rsPrjTeam = $query_rsPrjTeam->fetch()){ 
                  $num = $num+1;
                  $mnst = $row_rsPrjTeam["ministry"];
                  $dept = $row_rsPrjTeam["department"];

                  if($mnst==0){
                     $ministry = "All Ministries";
                  }else{
                     $query_rsMinistry =  $db->prepare("SELECT sector FROM tbl_sectors WHERE stid='$mnst' AND deleted='0'");
                     $query_rsMinistry->execute();		
                     $row_rsMinistry = $query_rsMinistry->fetch();
                     $ministry = $row_rsMinistry["sector"];
                  }
                  
                  if($dept==0){
                     $department = "All Departments";
                  }else{
                     $query_rsDept =  $db->prepare("SELECT sector FROM tbl_sectors WHERE stid='$dept' AND deleted='0'");
                     $query_rsDept->execute();		
                     $row_rsDept = $query_rsDept->fetch();
                     $department = $row_rsDept["sector"];
                  }
                  
                  $fullname = $row_rsPrjTeam['title'].". ".$row_rsPrjTeam['fullname'];

                  $body .='
                  <tr id="rowlines">
                     <td height="35"><div align="center">'. $num .'</div></td>
                     <td><div align="left">&nbsp;&nbsp;'.$fullname .'</div></td>
                     <td><div align="left">&nbsp;&nbsp;'. $row_rsPrjTeam['designation'] .'</div></td>
                     <td><div align="left">&nbsp;&nbsp;'. $ministry .'</div></td>
                     <td><div align="left">&nbsp;&nbsp;'. $department .'</div></td>
                     <td><div align="left">&nbsp;&nbsp;'. $row_rsPrjTeam['email'].'</div></td>
                     <td><div align="left">&nbsp;&nbsp;'. $row_rsPrjTeam['phone'].'</div></td>
                  </tr>';
            
            }
            $body .='
         </tbody>
      </table>';
   $stylesheet = file_get_contents('bootstrap.css');
   $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
   $mpdf->WriteHTML($body, \Mpdf\HTMLParserMode::HTML_BODY);
   $mpdf->SetFooter('Uasin Gishu County {PAGENO}');
   $mpdf->Output();
} catch (PDOException $th) {
   customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
