<?php
try {
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
            $sites  .= '<li class="list-group-item"><strong>  ' . $level3 . ' Sites: </strong>'  . implode(", ", $site) . ' </li>';
        }

        if ($sites != '') {
            return
                '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <ul class="list-group">
                <li class="list-group-item list-group-item-action active">Sites </li>
                ' . $sites . '
            </ul>
        </div>';
        }
        return;
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
                $unit_of_measure = $row_rsOutput['indicator_unit'];
                $o_target = $row_rsOutput['total_target'];

                $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
                $query_rsIndUnit->execute(array(":unit_id" => $unit_of_measure));
                $row_rsIndUnit = $query_rsIndUnit->fetch();
                $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                $measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';

                $site_location_table = '';
                if ($indicator_mapping_type == 2 || $indicator_mapping_type == 0) {
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
                            <td>' . $target . ' ' . $measure . '</td>
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
                            <td>' . $target . ' ' . $measure . '</td>
                        </tr>';
                        }
                    }
                }

                $output_table .= '
            <fieldset class="scheduler-border row setup-content" id="step-2">
                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Output ' . $counter . ':  ' . $indicator_name . ' </legend>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Target : </strong>' . $o_target . ' ' . $measure . ' </li>
                    </ul>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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

    function get_key_stake_holders($projid, $contractor_id)
    {
        global $db;
        $financial_partners_table_body = $other_partners_table_body = "";
        $query_rsFunding =  $db->prepare("SELECT m.amountfunding, f.financier, t.type FROM tbl_myprojfunding m INNER JOIN tbl_financiers f ON f.id = m.financier INNER JOIN tbl_financier_type t ON t.id = f.type WHERE m.projid=:projid");
        $query_rsFunding->execute(array(":projid" => $projid));
        $totalRows_rsFunding = $query_rsFunding->rowCount();

        $Acounter = 0;
        if ($totalRows_rsFunding > 0) {
            while ($row_rsFunding = $query_rsFunding->fetch()) {
                $Acounter++;
                $financier = $row_rsFunding['financier'];
                $financial_partners_table_body .= '
            <tr>
                <td>' . $Acounter . '</td>
                <td>' . $financier . '</td>
                <td>Financier</td>
            </tr>';
            }
        }

        $query_partnerprojs = $db->prepare("SELECT p.* FROM tbl_partners p inner join tbl_myprojpartner m on m.projid=m.partner_id WHERE m.projid = :projid");
        $query_partnerprojs->execute(array(":projid" => $projid));
        $row_partnerprojs = $query_partnerprojs->rowCount();

        if ($row_partnerprojs > 0) {
            while ($row_partnerproj = $query_partnerprojs->fetch()) {
                $Acounter++;
                $partner = $row_partnerproj['partner'];
                $financial_partners_table_body .= '
            <tr>
                <td>' . $Acounter . '</td>
                <td>' . $partner . '</td>
                <td>Development</td>
            </tr>';
            }
        }

        $query_contractor = $db->prepare("SELECT * FROM tbl_projects p LEFT JOIN tbl_contractor c ON p.projcontractor = c.contrid WHERE projid=:projid AND contrid=:contractor_id");
        $query_contractor->execute(array(":projid" => $projid, ":contractor_id" => $contractor_id));
        $row_contractor = $query_contractor->fetch();
        $totalRows_contractor = $query_contractor->rowCount();
        $Acounter += 1;
        $financial_partners_table_body .= ($totalRows_contractor > 0) ? '<tr><td>' . $Acounter . '</td><td>' . $row_contractor['contractor_name'] . '</td><td>Contractor</td></tr>' : '';

        $key_stake_holder = '
    <fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Partners</legend>
        <div class="row clearfix" id="">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="approve_financier_table" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="40%">Partner</th>
                                <th width="40%">Type</th>
                            </tr>
                        </thead>
                        <tbody id="">
                            ' . $financial_partners_table_body . '
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </fieldset>';
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
            $contractor_id = $row_rsProjects['projcontractor'];
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


            $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid = :projid");
            $query_Output->execute(array(":projid" => $projid));
            $total_Output = $query_Output->rowCount();

            $project_status = get_project_status($projstatus);
            if ($projstatus == 0) {
                $status_name = $total_Output > 0 ? "Pending Approval" : "Pending Outputs";
                $status = '<span type="button" class="btn btn-warning" style="width:70%">' . $status_name . '</span>';
            }

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
                    </li> ';
            if ($total_Output > 0) {
                $input .=  '
            <li>
                <a href="#2" role="tab" data-toggle="tab"><div style="color:#673AB7">Output Plan</div></a>
            </li>';
            }
            if ($projstage > 1) {
                $input .=  '
            <li>
                <a href="#3" data-toggle="tab"><div style="color:#673AB7">Key Stakeholders</div></a>
            </li>';
            }
            $input .=  '
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
                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                    <ul class="list-group">
                                        <li class="list-group-item list-group-item-action">
                                            <strong>Program Name </strong>: <strong>' . $ministrylabel . '</strong> : ' . $program_department . '
                                        </li>
                                        <li class="list-group-item list-group-item-action"><strong>' . $departmentlabel . '</strong> : ' . $program_section . ' </li>
                                        <li class="list-group-item list-group-item-action"><strong>' . $directoratelabel . '</strong> : ' . $program_directorate . ' </li>
                                        <li class="list-group-item list-group-item-action"><strong>Outcome </strong>: ' . $outcome . '</li>
                                        <li class="list-group-item list-group-item-action"><strong>Impact </strong>: ' . $impact . ' </li>
                                        <li class="list-group-item"><strong>Stage: </strong>' . $project_stage . ' </li>
                                        <li class="list-group-item"><strong style="width:30%">Status: </strong>' . $project_status . ' </li>
                                    </ul>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <ul class="list-group">
                                        <li class="list-group-item list-group-item-action"> <strong> Start Date: </strong>' . $project_start_date . ' </li>
                                        <li class="list-group-item list-group-item-action"><strong> Duration: </strong>' . $project_duration . ' Days </li>
                                        <li class="list-group-item list-group-item-action"><strong> End Date: </strong>' . $project_end_date . ' </li>
                                        <li class="list-group-item list-group-item-action"><strong>Implementation Method: </strong>' . $projimplementationMethod . ' </li>
                                        <li class="list-group-item"><strong>Budget (Ksh.): </strong>' . number_format($projcost, 2) . ' </li>
                                    </ul>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <ul class="list-group">
                                        <li class="list-group-item"><strong>' . $level1labelplural . ' : </strong>' . $level1 . ' </li>
                                    </ul>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <ul class="list-group">
                                        <li class="list-group-item"><strong>' . $level2labelplural . ' : </strong>' . $level2 . ' </li>
                                    </ul>
                                </div>
                                ' . get_sites($projid, $projlga) . '
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="2">
                    ' . get_output_details($projid) . '
                </div>
                <div class="tab-pane" id="3">
                    ' . get_key_stake_holders($projid, $contractor_id) . '
                </div>
            </div>
        </div>';
        }

        echo $input;
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
