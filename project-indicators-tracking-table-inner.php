<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <div class="block-header" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; padding-right:15px; background-color:#607D8B; color:#FFF">
                    <h4 class="contentheader"><i class="fa fa-line-chart" aria-hidden="true"></i> Projects Performance Report
                        <!--<button  class="btn btn-primary pull-right" onclick="goBack()" type="button">Go Back</button>-->
                    </h4>
                </div>
            </div>
            <div class="header">
                <div class="row clearfix" style="margin-top:5px">
                    <div class="col-md-12 row">
                        <ul class="list-inline pull-right">
                            <li>
                                <a href="reports/project-indicators-tracking-table-doc.php" class="btn btn-primary">
                                    <i class="fa fa-file-word-o" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a href="reports/project-indicators-tracking-table-pdf.php<?= $base_url ?>" target="_blank" class="btn btn-danger btn-sm" type="button">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <form id="searchform" name="searchform" method="get" style="margin-top:10px" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <?= csrf_token_html(); ?>
                        <div class="col-md-3">
                            <select name="sector" id="sector" onchange="sectors()" class="form-control show-tick " style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="false">
                                <option value="">Select <?= $ministrylabel ?></option>
                                <?php
                                $data = '';
                                $query_sectors = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent=0");
                                $query_sectors->execute(array(":comm" => $comm));
                                while ($row = $query_sectors->fetch()) {
                                    $stid = $row['stid'];
                                    $sector = $row['sector'];
                                    $data .= '<option value="' . $stid . '">' . $sector . '</option>';
                                }
                                echo $data;
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="department" id="dept" class="form-control show-tick " style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="false">
                                <option value="">No <?= $departmentlabelplural ?></option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="projfyfrom" id="fyfrom" onchange="finyearfrom()" class="form-control show-tick" data-live-search="false" data-live-search-style="startsWith">
                                <option value="" selected="selected">Select FY From</option>
                                <?php
                                projfy();
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="projfyto" id="fyto" class="form-control show-tick" data-live-search="false" data-live-search-style="startsWith">
                                <option value="" selected="selected">Select FY To</option>
                                <?php
                                //projfy();
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="submit" class="btn btn-primary" name="btn_search" id="btn_search" value="FILTER" />
                            <input type="button" VALUE="RESET" class="btn btn-warning" onclick="location.href='project-indicators-tracking-table.php'" id="btnback">
                        </div>
                    </form>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="manageItemTable">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="38%">Project Name</th>
                                <th width="15%">Location</th>
                                <th width="12%">Start/End&nbsp;Date</th>
                                <th width="10%">Cost</th>
                                <th width="10%">Expenditure</th>
                                <th width="10%">Status & Progress</th>
                                <!--<th width="8%">Report</th>-->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                function statuses($projstatus)
                                {
                                    global $db;
                                    $status = $db->prepare("SELECT * FROM `tbl_status` WHERE statusid=:statusid LIMIT 1");
                                    $status->execute(array(":statusid" => $projstatus));
                                    $rowstatus = $status->fetch();
                                    $status = $rowstatus["statusname"];

                                    $active = '';
                                    if ($projstatus == 3) {
                                        $active = '<button type="button" class="btn bg-yellow waves-effect" style="width:100%">' . $status . '</button>';
                                    } else if ($projstatus == 4) {
                                        $active = '<button type="button" class="btn btn-primary waves-effect" style="width:100%">' . $status . '</button>';
                                    } else if ($projstatus == 11) {
                                        $active = '<button type="button" class="btn bg-red waves-effect" style="width:100%">' . $status . '</button>';
                                    } else if ($projstatus == 5) {
                                        $active = '<button type="button" class="btn btn-success waves-effect" style="width:100%">' . $status . '</button>';
                                    } else if ($projstatus == 1) {
                                        $active = '<button type="button" class="btn bg-grey waves-effect" style="width:100%">' . $status . '</button>';
                                    } else if ($projstatus == 2) {
                                        $active = '<button type="button" class="btn bg-brown waves-effect" style="width:100%">' . $status . '</button>';
                                    } else if ($projstatus == 6) {
                                        $active = '<button type="button" class="btn bg-pink waves-effect" style="width:100%">' . $status . '</button>';
                                    }
                                    return $active;
                                }

                                function progress($percentage)
                                {
                                    $percent = '';
                                    if ($percentage < 100) {
                                        $percent = '
										<div class="progress" style="height:20px; font-size:10px; color:black">
											<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $percentage . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percentage . '%; height:20px; font-size:10px; color:black">
												' . $percentage . '%
											</div>
										</div>';
                                    } elseif ($percentage == 100) {
                                        $percent = '
										<div class="progress" style="height:20px; font-size:10px; color:black">
											<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $percentage . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percentage . '%; height:20px; font-size:10px; color:black">
											' . $percentage . '%
											</div>
										</div>';
                                    }
                                    return $percent;
                                }

                                if ($rows_count > 0) {
                                    // $row = $result->fetch_array();
                                    $active = "";
                                    $sn = 0;
                                    while ($row = $sql->fetch()) {
                                        $sn++;
                                        $itemId = $row['projid'];
                                        $progid = $row['progid'];

                                        $queryobj = $db->prepare("SELECT objective FROM `tbl_programs` g inner join tbl_strategic_plan_objectives o on o.id=g.strategic_obj WHERE progid=:progid");
                                        $queryobj->execute(array(":progid" => $progid));
                                        $rowobj = $queryobj->fetch();
                                        $objective = $rowobj["objective"];

                                        $query_rsBudget = $db->prepare("SELECT SUM(budget) as budget FROM tbl_project_details WHERE projid ='$itemId'");
                                        $query_rsBudget->execute();
                                        $row_rsBudget = $query_rsBudget->fetch();
                                        $totalRows_rsBudget = $query_rsBudget->rowCount();
                                        $projbudget = $row_rsBudget['budget'];

                                        $projname = $row["projname"];
                                        $projbudget = $row["projcost"];
                                        $budget = number_format($projbudget, 2);
                                        $projstatus = $row["projstatus"];
                                        $projstartdate = $row["projstartdate"];
                                        $projenddate = $row["projenddate"];
                                        $projstate = explode(",", $row['projstate']);

                                        $query_projectexp = $db->prepare("SELECT SUM(amountpaid) AS exp FROM `tbl_payments_request` r inner join `tbl_payments_disbursed` d on d.reqid=r.id WHERE r.projid=:projid");
                                        $query_projectexp->execute(array(":projid" => $itemId));
                                        $row_projectexp = $query_projectexp->fetch();
                                        $totalRows_projectexp = $query_projectexp->rowCount();
                                        $projectcost = 0;
                                        if ($totalRows_projectexp > 0) {
                                            $projectcost = $row_projectexp["exp"];
                                        }

                                        $status = $db->prepare("SELECT * FROM `tbl_status` WHERE statusid=:statusid LIMIT 1");
                                        $status->execute(array(":statusid" => $projstatus));
                                        $rowstatus = $status->fetch();
                                        $status = $rowstatus["statusname"];
                                        //$progress = 45;

                                        $states = array();
                                        for ($i = 0; $i < count($projstate); $i++) {
                                            $state = $db->prepare("SELECT * FROM `tbl_state` WHERE id=:stateid LIMIT 1");
                                            $state->execute(array(":stateid" => $projstate[$i]));
                                            $rowstate = $state->fetch();
                                            $state = $rowstate["state"];
                                            array_push($states, $state);
                                        }

                                        // project percentage progress
                                        $query_rsMlsProg = $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid = :projid");
                                        $query_rsMlsProg->execute(array(":projid" => $itemId));
                                        $row_rsMlsProg = $query_rsMlsProg->fetch();
                                        $percent2 = 0;
                                        if ($row_rsMlsProg["mlprogress"] > 0 && $row_rsMlsProg["nmb"] > 0) {
                                            $prjprogress = $row_rsMlsProg["mlprogress"] / $row_rsMlsProg["nmb"];
                                            $percent2 = round($prjprogress, 2);
                                        }

                                        $queryactivities = $db->prepare("SELECT * FROM `tbl_task` WHERE projid=:projid");
                                        $queryactivities->execute(array(":projid" => $itemId));

                                        $query_projremarks = $db->prepare("SELECT * FROM `tbl_projects_performance_report_remarks` WHERE projid=:projid LIMIT 1");
                                        $query_projremarks->execute(array(":projid" => $itemId));
                                        $totalRows_projremarks = $query_projremarks->rowCount();

                                        echo '<tr class="projects">
                                            <td class="text-center mb-0" id="projects' . $itemId . '" data-toggle="collapse" data-target=".project' . $itemId . '">' . $sn . '
												<button class="btn btn-link " title="Click once to expand and Click once to Collapse!!">
													<i class="fa fa-plus-square" style="font-size:16px"></i>
												</button>
											</td>
                                            <td>' . $projname . '</td>
                                            <td>' . implode(", ", $states) . '</td>
                                            <td>' . $projstartdate . '/' . $projenddate . '</td>
                                            <td>' . number_format($projbudget, 2) . '</td>
                                            <td>' . number_format($projectcost, 2) . '</td>
                                            <td>' . statuses($projstatus) . '<br>' . progress($percent2) . '</td>';

                                        echo '</tr>
										<tr class="collapse project' . $itemId . '">
											<td class="bg-grey text-center"></td><td colspan="6"><strong>Project Objective: </strong>' . $objective . '</td>
										</tr>
										<tr class="collapse project' . $itemId . '">
											<th class="bg-grey text-center"></th>
											<th colspan="2">Activity</th>
											<th colspan="3">Start and End Dates</th>
											<th>Status & Progress</th>
										</tr>';
                                        $nm = 0;
                                        while ($rowact = $queryactivities->fetch()) {
                                            $nm++;
                                            $activity = $rowact["task"];
                                            $startdate = $rowact["sdate"];
                                            $enddate = $rowact["edate"];
                                            $statusid = $rowact["status"];
                                            $progress = $rowact["progress"];

                                            $querytaskstatus = $db->prepare("SELECT statusname FROM `tbl_task_status` WHERE statusid=:statusid");
                                            $querytaskstatus->execute(array(":statusid" => $statusid));
                                            $rowtaskstatus = $querytaskstatus->fetch();
                                            $taskstatus = $rowtaskstatus["statusname"];

                                            echo '<tr class="collapse project' . $itemId . '">
												<td class="bg-grey text-center">' . $sn . '.' . $nm . '</td><td colspan="2">' . $activity . '</td><td colspan="3">' . $startdate . ' AND ' . $enddate . '</td><td>' . statuses($statusid) . '<br>' . progress($progress) . '</td>
											</tr>';
                                        }
                                        echo '
										<tr class="collapse project' . $itemId . '">
											<td class="bg-grey text-center"></td><td colspan="6" class="bg-grey">';
                                        if ($totalRows_projremarks > 0) {
                                            $row_projremarks = $query_projremarks->fetch();
                                            echo '<strong>Project Remarks: </strong>' . $row_projremarks["remarks"];
                                        } else {
                                            echo '<button type="button" data-toggle="modal" data-target="#remarksItemModal" id="remarksItemModalBtn"  class="btn btn-success btn-sm" onclick=remarks("' . $itemId . '")> <i class="glyphicon glyphicon-file"></i><strong> Add Project Remarks</strong></button>';
                                        }
                                        echo '</td>
										</tr>';
                                    }
                                } else {
                                    echo '
									<tr class="projects">
										<td class="text-center mb-0">
										</td>
										<td colspan="6">No records found</td>
									</tr>';
                                }
                            } catch (PDOException $ex) {
                                $result = flashMessage("An error occurred: " . $ex->getMessage());
                                echo $result;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Start Item more -->
<div class="modal fade" tabindex="-1" role="dialog" id="remarksItemModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#03A9F4">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-file"></i> Project Remarks</h4>
            </div>
            <div class="modal-body" style="max-height:450px; overflow:auto;">
                <div class="card">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="body">
                                <div class="div-result">
                                    <form class="form-horizontal" id="addProjRemarksForm" action="assets/processor/reports-processor.php" method="POST" autocomplete="off">
                                        <?= csrf_token_html(); ?>
                                        <br>
                                        <div id="result">
                                            <div class="col-md-12">
                                                <label>Comments:</label>
                                                <div class="form-line">
                                                    <textarea name="remarks" id="remarks" rows="5" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" placeholder="Enter project remarks"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer editItemFooter">
                                            <div class="col-md-12 text-center strat">
                                                <input type="hidden" name="username" id="username" value="<?= $username ?>">
                                                <input type="hidden" name="projid" id="projid" value="">
                                                <input type="hidden" name="addremarks" value="addremarks">
                                                <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                                <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                                            </div>
                                        </div> <!-- /modal-footer -->
                                    </form> <!-- /.form -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- /modal-body -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Item more -->