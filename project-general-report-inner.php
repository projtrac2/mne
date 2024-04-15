<?php 
try {
    //code...

?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <div class="block-header" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; padding-right:15px; background-color:#607D8B; color:#FFF">
                    <h4 class="contentheader"><i class="fa fa-line-chart" aria-hidden="true"></i> Project General Report
                        <button  class="btn btn-primary pull-right" onclick="goBack()" type="button">Go Back</button>
                        <input type="hidden" name="projid" id="projid" value="<?=$projid?>">
                    </h4>
                </div>
            </div>
            <div class="header">
                <div class="row clearfix" style="margin-top:5px">
                    <div class="col-md-12 row">
						<div class="col-md-10"><h4><strong>Project Name:</strong> <?=$projname?></h4></div><div class="col-md-2">
                        <ul class="list-inline pull-right">
                            <li>
                                <a href="reports/project-general-report-doc.php?projid=<?=$projid?>" target="_blank" class="btn btn-primary">
                                    <i class="fa fa-file-word-o" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a href="reports/project-general-report-pdf.php?projid=<?=$projid?>" target="_blank" class="btn btn-danger btn-sm" type="button">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul></div>
                    </div>
                </div>
            </div>

            <div class="header">
                <fieldset class="scheduler-border">
                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                    <i class="fa fa-list-ol" aria-hidden="true"></i>
                    Summary
                </legend>
                    <div class="row clearfix align-center" style="margin-top:5px">
                        <div class="col-md-4">
                            <div style="height:30px; font-size:14px; font-family:Verdana, Geneva, sans-serif; border-bottom:#CCC thin dashed; padding-top:5px; margin-bottom:15px">
                                <strong>
                                    <img src="images/progress.png" alt="progress" style="width:16px; height:16px"/> Project % Progress
                                </strong>
                            </div>
                            <input type="text" class="knob" value="<?php echo $percent2 . '%' ?>" data-width="150" data-height="150" data-thickness="0.25" data-fgColor="#00BCD4">
                        </div>
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4" align="center">
                            <!--<div style="height:30px; font-size:14px; font-family:Verdana, Geneva, sans-serif; border-bottom:#CCC thin dashed; padding-top:5px">
                                <strong>
                                    <img src="images/progress.png" alt="progress" style="width:16px; height:16px"/> Output (Achived vs Target)
                                </strong>
                            </div>
                            <div id="graph"  style=" width:100%"></div>
                            <div id="chartContainer" style="height: 270px; width: 100%;"></div>-->

                            <script>
                                window.onload = function () {
                                    var chart = new CanvasJS.Chart("chartContainer", {
                                        theme: "light",
                                        animationEnabled: false,
                                        title:{
                                            text: "Indicator Rate %"
                                        },
                                        data: [
                                        {
                                            type: "column",
                                            dataPoints: [
                                                <?=$chart_data?>
                                            ]
                                        }
                                        ]
                                    });
                                    chart.render();
                                }
                            </script>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="body">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-list-ol" aria-hidden="true"></i> Project Indicator Performance Tracking Table</legend>
                    <div class="table-responsive">
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
                            <tbody>
                                <?php
                                if ($totalRows_rsOutputs > 0) {
                                    $Ocounter = 0;
                                    do {
                                        $Ocounter++;
                                        $outputid = $row_rsOutputs['opid'];
                                        $indid = $row_rsOutputs['indicator'];

                                        $query_rsAchieved = $db->prepare("SELECT SUM(actualoutput) as achieved FROM tbl_monitoringoutput WHERE projid=:projid AND opid=:opid");
                                        $query_rsAchieved->execute(array(":projid" => $projid, ":opid" => $outputid));
                                        $row_rsAchieved = $query_rsAchieved->fetch();
                                        $totalRows_rsAchieved = $query_rsAchieved->rowCount();
                                        $achieved = 0;
                                        $achieved = $row_rsAchieved['achieved'];

                                        $query_rsTarget = $db->prepare("SELECT total_target FROM tbl_project_details  WHERE projid=:projid AND id=:opid");
                                        $query_rsTarget->execute(array(":projid" => $projid, ":opid" => $outputid));
                                        $row_rsTarget = $query_rsTarget->fetch();
                                        $totalRows_rsTarget = $query_rsTarget->rowCount();
                                        $target = $row_rsTarget['total_target'];
                                        $rate = 0;

                                        if ($target > 0 && $achieved > 0) {
                                            $rate = ($achieved / $target) * 100;
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
                                        ?>
                                        <tr class="outputs" style="background-color:#eff9ca">
                                            <td align="center" id="outputs<?php echo $outputid ?>" class="mb-0" data-toggle="collapse" data-target=".output<?php echo $outputid ?>">
                                                <button class="btn btn-link " title="Click once to expand and Click twice to Collapse!!">
                                                    <i class="fa fa-plus-square" style="font-size:16px"></i>
                                                </button>
                                            </td>
                                            <td align="center"><?=$Ocounter?></td>
                                            <td colspan="3"><?php echo $row_rsOutputs['output'] ?></td>
                                            <td colspan="2"><?php echo $row_rsIndicator['indicator_name'] ?> </td>
                                            <td><?php echo number_format($target) ?></td>
                                            <td><?php echo number_format($achieved) ?></td>
                                            <td><?php echo number_format($rate, 2) ?></td>
                                        </tr>
                                        <tr class="collapse output<?php echo $outputid ?>" data-parent="outputs<?php echo $outputid ?>" style="background-color:#FF9800; color:#FFF">
                                            <th style="width: 2%"></th>
                                            <th style="width: 2%">#</th>
                                            <th colspan="3" style="width: 96%">Milestone Name</th>
                                            <th style="width:7%">Status</th>
                                            <th style="width:7%">Progress</th>
                                            <th style="width:7%">Budget</th>
                                            <th style="width:7%">Start Date</th>
                                            <th style="width:7%">End Date</th>
                                        </tr>
                                        <?php
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
                                                $milestonefinance = number_format($rowmilefinance["milecost"], 2);

                                                // project percentage progress
                                                $query_rsMlsProg = $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid = :projid AND outputid=:outputid AND  msid=:msid");
                                                $query_rsMlsProg->execute(array(":projid" => $projid, ":outputid" => $outputid, ":msid" => $milestone));
                                                $row_rsMlsProg = $query_rsMlsProg->fetch();
                                                $percent1 = 0;

                                                if ($row_rsMlsProg["mlprogress"] > 0 && $row_rsMlsProg["nmb"] > 0) {
                                                    $prjprogress = $row_rsMlsProg["mlprogress"] / $row_rsMlsProg["nmb"];
                                                    $percent1 = round($prjprogress, 2);
                                                }

                                                $percent = '';
                                                if ($percent1 < 100) {
                                                    $percent = '
                                                    <div class="progress" style="height:20px; font-size:10px; color:black">
                                                        <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $percent1 . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent1 . '%; height:20px; font-size:10px; color:black">
                                                            ' . $percent1 . '%
                                                        </div>
                                                    </div>';
                                                } elseif ($percent1 == 100) {
                                                    $percent = '
                                                    <div class="progress" style="height:20px; font-size:10px; color:black">
                                                        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $percent1 . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent1 . '%; height:20px; font-size:10px; color:black">
                                                        ' . $percent1 . '%
                                                        </div>
                                                    </div>';
                                                }

                                                $mactive = '';
                                                if ($mstatus == 3) {
                                                    $mactive = '<button type="button" class="btn bg-yellow waves-effect" style="width:100%">' . $milestatus . '</button>';
                                                } else if ($mstatus == 4) {
                                                    $mactive = '<button type="button" class="btn btn-primary waves-effect" style="width:100%">' . $milestatus . '</button>';
                                                } else if ($mstatus == 11) {
                                                    $mactive = '<button type="button" class="btn bg-red waves-effect" style="width:100%">' . $milestatus . '</button>';
                                                } else if ($mstatus == 5) {
                                                    $mactive = '<button type="button" class="btn btn-success waves-effect" style="width:100%">' . $milestatus . '</button>';
                                                } else if ($mstatus == 1) {
                                                    $mactive = '<button type="button" class="btn bg-grey waves-effect" style="width:100%">' . $milestatus . '</button>';
                                                } else if ($mstatus == 2) {
                                                    $mactive = '<button type="button" class="btn bg-brown waves-effect" style="width:100%">' . $milestatus . '</button>';
                                                } else if ($mstatus == 6) {
                                                    $mactive = '<button type="button" class="btn bg-pink waves-effect" style="width:100%">' . $milestatus . '</button>';
                                                }
                                                ?>
                                                <tr class="collapse output<?php echo $outputid ?>" data-parent="outputs<?php echo $outputid ?>" style="background-color:#CDDC39">
                                                    <td align="center" class="mb-0" data-toggle="collapse" data-target=".milestone<?php echo $milestone ?>">
                                                        <button class="btn btn-link mile_class<?php echo $outputid ?>" title="Click once to expand and Click twice to Collapse!!">
                                                            <i class="more-less fa fa-plus-square" style="font-size:16px"></i>
                                                        </button>
                                                    </td>
                                                    <td align="center"> <?php echo $Ocounter . "." . $mcounter ?></td>
                                                    <td colspan="3"><?php echo $row_rsMilestones['milestone'] ?></td>
                                                    <td><?php echo $mactive ?></td>
                                                    <td><?php echo $percent ?></td>
                                                    <td><?php echo $milestonefinance ?></td>
                                                    <td><?php echo $sdate ?></td>
                                                    <td><?php echo $edate ?></td>
                                                </tr>
                                                <tr class="collapse milestone<?php echo $milestone ?>" data-parent="outputs<?php echo $outputid ?>" style="background-color:#b8f9cb; color:#FFF">
                                                    <th style="width: 2%"></th>
                                                    <th style="width: 2%">#</th>
                                                    <th colspan="3" style="width: 86%">Task Name</th>
                                                    <th style="width:7%">Status</th>
                                                    <th style="width:7%">Progress</th>
                                                    <th style="width:7%">Budget</th>
                                                    <th style="width:7%">Start Date</th>
                                                    <th style="width:7%">End Date</th>
                                                </tr>
                                                <?php
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
                                                    if ($tstatus == 3) {
                                                        $tactive = '<button type="button" class="btn bg-yellow waves-effect" style="width:100%">' . $taskstatus . '</button>';
                                                    } else if ($tstatus == 4) {
                                                        $tactive = '<button type="button" class="btn btn-primary waves-effect" style="width:100%">' . $taskstatus . '</button>';
                                                    } else if ($tstatus == 11) {
                                                        $tactive = '<button type="button" class="btn bg-red waves-effect" style="width:100%">' . $taskstatus . '</button>';
                                                    } else if ($tstatus == 5) {
                                                        $tactive = '<button type="button" class="btn btn-success waves-effect" style="width:100%">' . $taskstatus . '</button>';
                                                    } else if ($tstatus == 1) {
                                                        $tactive = '<button type="button" class="btn bg-grey waves-effect" style="width:100%">' . $taskstatus . '</button>';
                                                    } else if ($tstatus == 2) {
                                                        $tactive = '<button type="button" class="btn bg-brown waves-effect" style="width:100%">' . $taskstatus . '</button>';
                                                    } else if ($tstatus == 6) {
                                                        $tactive = '<button type="button" class="btn bg-pink waves-effect" style="width:100%">' . $taskstatus . '</button>';
                                                    }

                                                    $percent5 = '';
                                                    $percent3 = 66;
                                                    if ($percent3 < 100) {
                                                        $percent5 = '
                                                        <div class="progress" style="height:20px; font-size:10px; color:black">
                                                            <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $percent3 . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent3 . '%; height:20px; font-size:10px; color:black">
                                                                ' . $percent3 . '%
                                                            </div>
                                                        </div>';
                                                    } elseif ($percent3 == 100) {
                                                        $percent5 = '
                                                        <div class="progress" style="height:20px; font-size:10px; color:black">
                                                            <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $percent3 . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent3 . '%; height:20px; font-size:10px; color:black">
                                                            ' . $percent3 . '%
                                                            </div>
                                                        </div>';
                                                    }
                                                    ?>
                                                        <tr class="collapse milestone<?php echo $milestone ?>" data-parent="outputs<?php echo $outputid ?>" style="background-color:#FFF">
                                                            <td style="background-color:#b8f9cb"></td>
                                                            <td align="center"><?php echo $Ocounter . "." . $mcounter . "." . $tcounter ?></td>
                                                            <td COLSPAN=3><?php echo $row_rsTasks['task'] ?></td>
                                                            <td><?php echo $tactive ?></td>
                                                            <td><?php echo $percent5 ?></td>
                                                            <td><?php echo $tasksfinance ?></td>
                                                            <td><?php echo $tsdate ?></td>
                                                            <td><?php echo $tedate ?></td>
                                                            <td>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        if ($totalRows_rsChecklist > 0 && $projstage >= 10) {
                                                            ?>
                                                            <tr class="collapse milestone<?php echo $milestone ?>" data-parent="outputs<?php echo $outputid ?>" style="background-color:#b8f9cb; color:#FFF">
                                                                <th style="width: 2%"></th>
                                                                <th style="width: 2%">#</th>
                                                                <th colspan="8" style="width: 86%">Task List</th>
                                                            </tr>
                                                            <?php
                                                            $rowno = 0;
                                                            do {
                                                                $checklist = $row_rsChecklist['name'];
                                                                $rowno++;
                                                                echo
                                                                    '<tr id="row' . $rowno . '" class ="collapse milestone' . $milestone . '" d >
                                                                        <td></td>
                                                                        <td align="center">' . $Ocounter . "." . $mcounter . "." . $tcounter . "." . $rowno . '</td>
                                                                            <td colspan="8">
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
                                ?>
                            </tbody>
                        </table>
                    </div>
                </fieldset>
                <fieldset class="scheduler-border">
                    <legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Project Team Member(s)</legend>
                    <div class="table-responsive">
                        <table width="100%" border="1" cellspacing="0" cellpadding="0" style="border:#EEE thin solid">
                                <thead>
                                    <tr id="" style="padding-left:10px; background-color:light-blue;">
                                        <td width="3%" height="35"><div align="center"><strong id="colhead">#</strong></div></td>
                                        <td width="18%"><strong id="colhead">&nbsp;&nbsp;Full Name</strong></td>
                                        <td width="13%"><strong id="colhead">&nbsp;&nbsp;Designation</strong></td>
                                        <td width="35%"><strong id="colhead">&nbsp;&nbsp;<?= $ministrylabel ?></strong></td>
                                        <td width="12%"><strong id="colhead">&nbsp;&nbsp;<?=$departmentlabel?></strong></td>
                                        <td width="10%"><strong id="colhead">&nbsp;&nbsp;Email</strong></td>
                                        <td width="10%"><strong id="colhead">&nbsp;&nbsp;Phone</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    //$num = count($rsTaskPrg);
                                    $num = 0;
                                    while ($row_rsPrjTeam = $query_rsPrjTeam->fetch()) {
                                        $num = $num + 1;
                                        $mnst = $row_rsPrjTeam["ministry"];
                                        $dept = $row_rsPrjTeam["department"];

                                        if ($mnst == 0) {
                                            $ministry = "All Ministries";
                                        } else {
                                            $query_rsMinistry = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid='$mnst' AND deleted='0'");
                                            $query_rsMinistry->execute();
                                            $row_rsMinistry = $query_rsMinistry->fetch();
                                            $ministry = $row_rsMinistry["sector"];
                                        }

                                        if ($dept == 0) {
                                            $department = "All Departments";
                                        } else {
                                            $query_rsDept = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid='$dept' AND deleted='0'");
                                            $query_rsDept->execute();
                                            $row_rsDept = $query_rsDept->fetch();
                                            $department = $row_rsDept["sector"];
                                        }

                                        $fullname = $row_rsPrjTeam['title'] . ". " . $row_rsPrjTeam['fullname'];
                                        ?>
                                        <tr id="rowlines">
                                            <td height="35"><div align="center"><?php echo $num; ?></div></td>
                                            <td><div align="left">&nbsp;&nbsp;<?php echo $fullname; ?></div></td>
                                            <td><div align="left">&nbsp;&nbsp;<?php echo $row_rsPrjTeam['designation']; ?></div></td>
                                            <td><div align="left">&nbsp;&nbsp;<?php echo $ministry; ?></div></td>
                                            <td><div align="left">&nbsp;&nbsp;<?php echo $department; ?></div></td>
                                            <td><div align="left">&nbsp;&nbsp;<?php echo $row_rsPrjTeam['email']; ?></div></td>
                                            <td><div align="left">&nbsp;&nbsp;<?php echo $row_rsPrjTeam['phone']; ?></div></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                        </table>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>
<?php 

} catch (\PDOException $th) {
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}

?>
