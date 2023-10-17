<?php
include '../controller.php';

function get_sections($sector_id)
{
    global $db;
    $query_rsSector = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE deleted='0' and stid=:sector_id");
    $query_rsSector->execute(array(":sector_id" => $sector_id));
    $row_rsSector = $query_rsSector->fetch();
    return $row_rsSector ? $row_rsSector['sector'] : "";
}

function get_state($state_arr)
{
    global $db;
    $total_states = count($state_arr);
    $states = [];
    if ($total_states > 0) {
        for ($i = 0; $i < $total_states; $i++) {
            $state_id = $state_arr[$i];
            $query_rslga = $db->prepare("SELECT * FROM tbl_state WHERE id =:state_id ");
            $query_rslga->execute(array(":state_id" => $state_id));
            $row_rslga = $query_rslga->fetch();
            $states[] = $row_rslga ? $row_rslga['state'] : '';
        }
    }
    return implode(', ', $states);
}


function get_dates($projid, $project_start_date, $project_duration, $projcategory)
{
    global $db;
    $start_date = $project_start_date;
    $duration = $project_duration;
    $end_date = date('Y-m-d', strtotime($project_start_date . ' + ' . $project_duration . ' days'));

    $query_rsTask_Start_Dates = $db->prepare("SELECT MIN(start_date) as start_date, MAX(end_date) as end_date FROM tbl_program_of_works WHERE projid=:projid LIMIT 1");
    $query_rsTask_Start_Dates->execute(array(':projid' => $projid));
    $rows_rsTask_Start_Dates = $query_rsTask_Start_Dates->fetch();

    if (!is_null($rows_rsTask_Start_Dates['start_date'])) {
        $start_date =  $rows_rsTask_Start_Dates['start_date'];
        $end_date =  $rows_rsTask_Start_Dates['end_date'];
    } else {
        if ($projcategory == 2) {
            $query_rsTender_start_Date = $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid=:projid LIMIT 1");
            $query_rsTender_start_Date->execute(array(':projid' => $projid));
            $rows_rsTender_start_Date = $query_rsTender_start_Date->fetch();
            $total_rsTender_start_Date = $query_rsTender_start_Date->rowCount();
            if ($total_rsTender_start_Date > 0) {
                $start_date =  $rows_rsTender_start_Date['startdate'];
                $end_date =  $rows_rsTender_start_Date['enddate'];
            }
        }
    }

    return array("start_date" => $start_date, "end_date" => $end_date, "duration" => $duration);
}

function get_project_status($projstatus)
{
    global $db;
    $query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
    $query_Projstatus->execute(array(":projstatus" => $projstatus));
    $row_Projstatus = $query_Projstatus->fetch();
    $total_Projstatus = $query_Projstatus->rowCount();
    $status = "";
    if ($total_Projstatus > 0) {
        $status_name = $row_Projstatus['statusname'];
        $status_class = $row_Projstatus['class_name'];
        $status = '<span type="button" class="' . $status_class . '" style="width:70%">' . $status_name . '</span>';
    }

    return $status;
}

function get_files_table($projid)
{
    global $db;
    $query_rsFile = $db->prepare("SELECT * FROM tbl_files WHERE projid=:projid");
    $query_rsFile->execute(array(":projid" => $projid));
    $totalRows_rsFile = $query_rsFile->rowCount();
    $table_body = '';

    if ($totalRows_rsFile > 0) {
        $counter = 0;
        while ($row_rsFile = $query_rsFile->fetch()) {
            $counter++;
            $pdfname = $row_rsFile['filename'];
            $filecategory = $row_rsFile['fcategory'];
            $ext = $row_rsFile['ftype'];
            $filepath = $row_rsFile['floc'];
            $fid = $row_rsFile['fid'];
            $attachmentPurpose = $row_rsFile['reason'];
            $projstage = $row_rsFile['projstage'];
            $query_projstage = $db->prepare("SELECT stage FROM tbl_project_workflow_stage WHERE priority=:projstage");
            $query_projstage->execute(array(":projstage" => $projstage));
            $row_projstage = $query_projstage->fetch();
            $project_stage = $row_projstage ? $row_projstage['stage'] : '';
            $table_body .= '<tr><td>' . $counter . '</td><td>' . $pdfname . '</td><td>' . $attachmentPurpose . '</td><td>' . $project_stage . '</td></tr>';
        }
    }

    return $table_body;
}

function get_sites($projid, $state_arr)
{
    global $db, $level2labelplural;
    $sites = '';
    $total_states = count($state_arr);
    for ($i = 0; $i < $total_states; $i++) {
        $state_id = $state_arr[$i];
        $query_rsSites =  $db->prepare("SELECT * FROM tbl_project_sites WHERE projid =:projid AND state_id=:state_id");
        $query_rsSites->execute(array(":projid" => $projid, ":state_id" => $state_id));
        $totalRows_rsSites = $query_rsSites->rowCount();

        $site = [];
        if ($totalRows_rsSites > 0) {
            while ($row_rsSites = $query_rsSites->fetch()) {
                $site[] = $row_rsSites['site'];
            }
        }

        $query_rslga = $db->prepare("SELECT * FROM tbl_state WHERE id =:state_id ");
        $query_rslga->execute(array(":state_id" => $state_id));
        $row_rslga = $query_rslga->fetch();
        $level3 = $row_rslga ? $row_rslga['state'] : '';
        $sites  .= '<li class="list-group-item"><strong> ' . $level2labelplural . ' ' . $level3 . ' Sites: </strong>'  . implode(", ",$site) . ' </li>';
    }
    return $sites;
}


function get_output_details($projid)
{
    global $db;
    $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid = :projid");
    $query_Output->execute(array(":projid" => $projid));
    $total_Output = $query_Output->rowCount();
    $output_table = '';
    if ($total_Output > 0) {
        $counter = 0;
        while ($row_rsOutput = $query_Output->fetch()) {
            $counter++;
            $output_id = $row_rsOutput['id'];
            $indicator_mapping_type = $row_rsOutput['indicator_mapping_type'];
            $indicator_name = $row_rsOutput['indicator_name'];
            $o_target = $row_rsOutput['total_target'];
            $o_budget = $row_rsOutput['budget'];
            $duration = $row_rsOutput['duration'];
            $projfscyear = $row_rsOutput['output_start_year'];
            $status = $row_rsOutput['status'];
            $progress = $row_rsOutput['progress'];

            $query_OutputSites = $db->prepare("SELECT * FROM tbl_project_output_details WHERE outputid = :output_id ORDER BY year");
            $query_OutputSites->execute(array(":output_id" => $output_id));
            $total_OutputSites = $query_OutputSites->rowCount();
            $year_table = '';
            if ($total_OutputSites > 0) {
                $counter_p = 0;
                while ($row_rsOutputSites = $query_OutputSites->fetch()) {
                    $counter_p++;
                    $budget  = $row_rsOutputSites['budget'];
                    $target  = $row_rsOutputSites['target'];
                    $year  = $row_rsOutputSites['year'];
                    $year_table .= '
                    <tr >
                        <td align="center">' . $counter_p . '</td>
                        <td>' . $year . '</td>
                        <td>' . $budget . '</td>
                        <td>' . $target . '</td>
                    </tr>';
                }
            }

            $site_location_table = '';
            if ($indicator_mapping_type == 2 || $indicator_mapping_type == 3) {
                $query_Sites = $db->prepare("SELECT * FROM tbl_output_disaggregation d INNER JOIN tbl_state s ON s.id = d.outputstate WHERE outputid = :output_id ORDER BY sequence ASC");
                $query_Sites->execute(array(":output_id" => $output_id));
                $total_Sites = $query_Sites->rowCount();
                if ($total_Sites > 0) {
                    $counter_p = 0;
                    while ($row_rsSites = $query_Sites->fetch()) {
                        $counter_p++;
                        $state  = $row_rsSites['state'];
                        $target  = $row_rsSites['total_target'];
                        $site_location_table .= '
                        <tr >
                            <td align="center">' . $counter_p . '</td>
                            <td>' . $state . '</td>
                            <td>N/A</td>
                            <td>' . $target . '</td>
                        </tr>';
                    }
                }
            } else {
                $query_Output = $db->prepare("SELECT * FROM tbl_project_sites d INNER JOIN tbl_state s ON s.id = d.state_id INNER JOIN tbl_output_disaggregation o ON d.site_id = o.output_site WHERE outputid = :outputid ");
                $query_Output->execute(array(":outputid" => $output_id));
                $total_Output = $query_Output->rowCount();
                if ($total_Output > 0) {
                    $counter_p  = 0;
                    while ($row_rsOutput = $query_Output->fetch()) {
                        $counter_p++;
                        $site_name = $row_rsOutput['site'];
                        $state = $row_rsOutput['state'];
                        $target = $row_rsOutput['total_target'];
                        $site_location_table .= '
                        <tr>
                            <td align="center">' . $counter_p . '</td>
                            <td>' . $state . '</td>
                            <td>' . $site_name . '</td>
                            <td>' . $target . '</td>
                        </tr>';
                    }
                }
            }

            $query_rsYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year WHERE id = :projfscyear");
            $query_rsYear->execute(array(":projfscyear" => $projfscyear));
            $row_rsYear = $query_rsYear->fetch();


            $start_date = $end_date = '';
            if ($row_rsYear) {
                $start_year = $row_rsYear['yr'];
                $start_date = date("Y-d-m", strtotime($start_year . "-07-01"));
                $end_date = date('Y-m-d', strtotime($start_date . ' + ' . $duration . ' days'));
            }


            $status = get_project_status($status);
            $output_table .= '
            <fieldset class="scheduler-border row setup-content" id="step-2">
                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Output ' . $counter . ':  ' . $indicator_name . ' </legend>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Start Date: </strong>' . $start_date . '</li>
                        <li class="list-group-item"><strong>Duration: </strong>' . $duration . ' Days </li>
                        <li class="list-group-item"><strong>End Date: </strong>' . $end_date . ' </li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Budget (Ksh.): </strong>' . $o_budget . ' </li>
                        <li class="list-group-item"><strong>Target (Ksh.): </strong>' . $o_target . ' </li>
                        <li class="list-group-item"><strong style="width:30%">Status: </strong>' . $status . ' </li>
                        <li class="list-group-item"><strong>Progress: </strong>' . $progress . ' </li>
                    </ul>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr style="background-color:#0b548f; color:#FFF">
                                    <th style="width:10%" align="center">#</th>
                                    <th style="width:30%">Year</th>
                                    <th style="width:30%">Budget</th>
                                    <th style="width:30%">Target</th>
                                </tr>
                            </thead>
                            <tbody>
                            ' . $year_table . '
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr style="background-color:#0b548f; color:#FFF">
                                    <th style="width:10%" align="center">#</th>
                                    <th style="width:30%">Location</th>
                                    <th style="width:30%">Site</th>
                                    <th style="width:30%">Target</th>
                                </tr>
                            </thead>
                            <tbody>
                            ' . $site_location_table . '
                            </tbody>
                        </table>
                    </div>
                </div>
            </fieldset>';
        }
    }

    return  $output_table;
}

function get_key_stake_holders($projid)
{
    global $db;
    $financial_partners_table_body = $other_partners_table_body = "";
    $query_rsFunding =  $db->prepare("SELECT jf.amountfunding as totalAmount, jf.sourcecategory, gf.type FROM tbl_projfunding jf INNER JOIN  tbl_funding_type gf ON gf.id = jf.sourcecategory WHERE jf.projid =:projid");
    $query_rsFunding->execute(array(":projid" => $projid));
    $totalRows_rsFunding = $query_rsFunding->rowCount();

    $Acounter = 0;
    if($totalRows_rsFunding > 0){
        $Acounter++;
        while($row_rsFunding = $query_rsFunding->fetch()){
            $source_category = $row_rsFunding['sourcecategory'];
            $amountfunding = $row_rsFunding['totalAmount'];
            $type = $row_rsFunding['type'];

            $query_rsFunder = $db->prepare("SELECT * FROM tbl_financiers WHERE type=:sourcecategory");
            $query_rsFunder->execute(array(":sourcecategory" => $source_category));
            $row_rsFunder = $query_rsFunder->fetch();
            $totalRows_rsFunder = $query_rsFunder->rowCount();
            $sourceCategory = $totalRows_rsFunder > 0 ? $row_rsFunder['financier'] : "";

            $financial_partners_table_body .= '
            <tr>
                <td>' . $Acounter . '</td>
                <td>' . $sourceCategory . '</td>
                <td>' . $type . '</td>
                <td>' . number_format($amountfunding, 2) . '</td>
            </tr>';
        }
    }

    $key_stake_holder = '
    <fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Financial Partners</legend>
        <div class="row clearfix" id="">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="approve_financier_table" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="40%">Source Category</th>
                                <th width="40%">Financier</th>
                                <th width="15%">Amount (Ksh)</th>
                            </tr>
                        </thead>
                        <tbody id="">
                           ' . $financial_partners_table_body . '
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Other Partners</legend>
        <div class="row clearfix" id="">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="approve_partners_table" style="width:100%">
                    <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="25%">Partner</th>
                        <th width="20%">Role of Partner</th>
                        <th width="50%">Description</th>
                    </tr>
                    </thead>
                    <tbody id="">
                    ' . $other_partners_table_body . '
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </fieldset> ';

    return $key_stake_holder;
}


if (isset($_GET['project_info'])) {
    $projid = $_GET['projid'];
    $query_rsProjects = $db->prepare("SELECT p.*, s.sector, g.projsector, g.projdept, g.directorate, g.progname FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND p.projid = :projid LIMIT 1");
    $query_rsProjects->execute(array(":projid" => $projid));
    $row_rsProjects = $query_rsProjects->fetch();
    $totalRows_rsProjects = $query_rsProjects->rowCount();
    $input = '';
    if ($totalRows_rsProjects > 0) {
        $progid = $row_rsProjects['progid'];
        $program_name = $row_rsProjects['progname'];
        $department_id = $row_rsProjects['projsector'];
        $sector_id = $row_rsProjects['projdept'];
        $directorate = $row_rsProjects['directorate'];
        $mne_budget = $row_rsProjects['mne_budget'];
        $program_department = get_sections($department_id);
        $program_section = get_sections($sector_id);
        $program_directorate = get_sections($directorate);

        $projcode = $row_rsProjects['projcode'];
        $projname = $row_rsProjects['projname'];
        $description = $row_rsProjects['projdesc'];
        $start_date =  $row_rsProjects['projstartdate'];
        $project_duration = $row_rsProjects['projduration'];
        $projcategory = $row_rsProjects['projcategory'];
        $projstage = $row_rsProjects['projstage'];
        $project_dates = get_dates($projid, $start_date, $project_duration, $projcategory);
        $project_start_date = $project_dates['start_date'];
        $project_end_date = $project_dates['end_date'];
        $project_duration = $project_dates['duration'];
        $projstatus  = $row_rsProjects['projstatus'];

        //get project implementation methods
        $query_rsProjImplMethod =  $db->prepare("SELECT DISTINCT id, method FROM tbl_project_implementation_method WHERE id=:projcategory");
        $query_rsProjImplMethod->execute(array(":projcategory" => $projcategory));
        $row_rsProjImplMethod = $query_rsProjImplMethod->fetch();
        $totalRows_rsProjImplMethod = $query_rsProjImplMethod->rowCount();
        $projimplementationMethod = $totalRows_rsProjImplMethod > 0 ? $row_rsProjImplMethod['method'] : '';

        $query_projstage = $db->prepare("SELECT stage FROM tbl_project_workflow_stage WHERE priority=:projstage");
        $query_projstage->execute(array(":projstage" => $projstage));
        $row_projstage = $query_projstage->fetch();
        $project_stage = $row_projstage ? $row_projstage['stage'] : '';

        $project_status = get_project_status($projstatus);

        $projtype = $row_rsProjects['projtype'];
        $projcost = $row_rsProjects['projcost'];
        $projbudget = $row_rsProjects['projbudget'];
        $projfscyear = $row_rsProjects['projfscyear'];
        $projevaluation = $row_rsProjects['projevaluation'];
        $projimpact = $row_rsProjects['projimpact'];
        $outcome = ($projevaluation == 1) ?  "Yes" : "No";
        $impact = ($projimpact == 1) ?  "Yes" : "No";

        $projstatement = $row_rsProjects['projstatement'];
        $projsolution = $row_rsProjects['projsolution'];
        $projcase = $row_rsProjects['projcase'];
        $projcommunity = explode(",", $row_rsProjects['projcommunity']);
        $projlga = explode(",", $row_rsProjects['projlga']);
        $level1 = get_state($projcommunity);
        $level2 = get_state($projlga);
        $user_name = $row_rsProjects['user_name'];
        $dateentered = $row_rsProjects['date_created'];



        $input =  '
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs txt-cyan" role="tablist">
                    <li class="active">
                        <a href="#1" role="tab" data-toggle="tab"><div style="color:#673AB7">Project Details</div></a>
                    </li>
                    <li>
                        <a href="#2" role="tab" data-toggle="tab"><div style="color:#673AB7">Output Plan</div></a>
                    </li>
                    <li>
                        <a href="#3" data-toggle="tab"><div style="color:#673AB7">Key Stakeholders</div></a>
                    </li>
                    <li>
                        <a href="#4" data-toggle="tab"><div style="color:#673AB7">Files</div></a>
                    </li>
                </ul>
            </div>
            <div class="card-body tab-content">
                <div class="tab-pane active" id="1">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="body" style="margin-top:5px; margin-bottom:5px">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <ul class="list-group">
                                        <li class="list-group-item list-group-item-action active">Name: ' . $projname . ' </li>
                                        <li class="list-group-item list-group-item-action"><strong>Program Name </strong>: ' . $program_name . ' </li>
                                        <li class="list-group-item list-group-item-action"><strong>Code </strong>: ' . $projcode . ' </li>
                                        <li class="list-group-item list-group-item-action"><strong>Description </strong>: ' . $description . ' </li>
                                    </ul>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <ul class="list-group">
                                        <li class="list-group-item"><strong>Start Date</strong>' . $project_start_date . '</li>
                                        <li class="list-group-item"><strong>Duration: </strong>' . $project_duration . ' Days </li>
                                        <li class="list-group-item"><strong>End Date</strong>' . $project_end_date . ' </li>
                                        <li class="list-group-item"><strong>Budget (Ksh.): </strong>' . $projcost . ' </li>
                                        <li class="list-group-item"><strong>Budget (Ksh.): </strong>' . $mne_budget . ' </li>
                                        <li class="list-group-item list-group-item-action"><strong>' . $ministrylabel . '</strong> : ' . $program_department . ' </li>
                                        <li class="list-group-item list-group-item-action"><strong>' . $departmentlabel . '</strong> : ' . $program_section . ' </li>
                                        <li class="list-group-item list-group-item-action"><strong>' . $directoratelabel . '</strong> : ' . $program_directorate . ' </li>
                                        <li class="list-group-item list-group-item-action"><strong>Outcome </strong>: ' . $outcome . ' </li>
                                        <li class="list-group-item list-group-item-action"><strong>Impact </strong>: ' . $impact . ' </li>
                                    </ul>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <ul class="list-group">
                                        <li class="list-group-item"><strong>Stage: </strong>' . $project_stage . ' </li>
                                        <li class="list-group-item"><strong style="width:30%">Status: </strong>' . $project_status . ' </li>
                                        <li class="list-group-item"><strong>Implementation Method: </strong>' . $projimplementationMethod . ' </li>
                                        <li class="list-group-item"><strong>' . $level1labelplural . ' : </strong>' . $level1 . ' </li>
                                        <li class="list-group-item"><strong>' . $level2labelplural . ' : </strong>' . $level2 . ' </li>
                                        <li class="list-group-item list-group-item-action active">Sites </li>
                                        ' . get_sites($projid, $projlga) . '
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="2">
                    ' . get_output_details($projid) . '
                </div>
                <div class="tab-pane" id="3">
                    ' . get_key_stake_holders($projid) . '
                </div>
                <div class="tab-pane" id="4">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover" id="funding_table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th style="width:68%">Purpose</th>
                                                <th style="width:28%">Attachment</th>
                                                <th style="width:28%">Project Stage</th>
                                            </tr>
                                        </thead>
                                        <tbody id="output_table" >
                                            ' . get_files_table($projid) . '
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    }

    echo $input;
}
