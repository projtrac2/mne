<?php
try {
    include '../controller.php';
    if (isset($_POST['insert_project'])) {
        $progid = $_POST['progid'];
        $projcode = $_POST['projcode'];
        $projname = $_POST['projname'];
        $projdesc = $_POST['projdescription'];
        $asset = $_POST['project_asset'];
        $projimplmethod = $_POST['projimplmethod'];
        $projduration = $_POST['projduration1'];
        $projcost = $_POST['project_budget'];
        $strategic_plan_program_id = $_POST['strategic_plan_program_id'];
        $project_type = $_POST['project_type'];
        $impact = isset($_POST['impact'])  ? $_POST['impact'] : 0;

        $projevaluation = $_POST['projevaluation'];
        $projlevel1 = implode(",", $_POST['projcommunity']);
        $projlevel2 = implode(",", $_POST['projlga']);
        $mne_budget =  0;
        $projtype = "New";
        $datecreated = date("Y-m-d");
        $createdby = $user_name;
        $projapprovestatus = 0;
        $projinspection = 0;
        $msg = false;
        $projid = false;



        if (isset($_POST['project_id']) && !empty($_POST['project_id'])) {
            $projid = $_POST['project_id'];
            $insertSQL = $db->prepare("UPDATE `tbl_projects` SET projcode=:projcode, projname=:projname, projdesc=:projdesc, projduration=:projduration,projimpact=:projimpact,project_type=:project_type,asset=:asset,projevaluation=:projevaluation, projcommunity=:projlevel1, projlga=:projlevel2,projcost=:projcost, projcategory=:projimplmethod,updated_by=:createdby, date_updated=:datecreated WHERE projid =:projid");
            $result  = $insertSQL->execute(array(":projcode" => $projcode, ":projname" => $projname, ":projdesc" => $projdesc, ":projduration" => $projduration, ":projimpact" => $impact, ":project_type" => $project_type, ":asset" => $asset, ":projevaluation" => $projevaluation, ":projlevel1" => $projlevel1, ":projlevel2" => $projlevel2, ":projcost" => $projcost, ":projimplmethod" => $projimplmethod, ":createdby" => $createdby, ":datecreated" => $datecreated, ":projid" => $projid));
        } else {
            $sql = $db->prepare("INSERT INTO `tbl_projects` (progid,strategic_plan_program_id, projcode, projname, projdesc, projtype, projduration,projimpact,project_type,asset,projevaluation, projcommunity, projlga,projcost,projcategory,user_name, date_created) VALUES(:progid,:strategic_plan_program_id, :projcode, :projname,:projdesc, :projtype, :projduration, :projimpact,:project_type,:asset,:projevaluation,:projlevel1, :projlevel2,:projcost, :projimplmethod, :createdby, :datecreated)");
            $result  = $sql->execute(array(":progid" => $progid, ":strategic_plan_program_id" => $strategic_plan_program_id, ":projcode" => $projcode, ":projname" => $projname, ":projdesc" => $projdesc, ":projtype" => $projtype, ":projduration" => $projduration, ":projimpact" => $impact, ":project_type" => $project_type, ":asset" => $asset, ":projevaluation" => $projevaluation, ":projlevel1" => $projlevel1, ":projlevel2" => $projlevel2, ":projcost" => $projcost,  ":projimplmethod" => $projimplmethod, ":createdby" => $createdby, ":datecreated" => $datecreated));
            $projid = $result ?  $db->lastInsertId() : false;
        }

        if ($projid) {
            $sql = $db->prepare("DELETE FROM `tbl_project_sites` WHERE projid=:projid");
            $results = $sql->execute(array(':projid' => $projid));

            $sql = $db->prepare("DELETE FROM `tbl_project_details` WHERE projid=:projid");
            $results = $sql->execute(array(':projid' => $projid));

            $sql = $db->prepare("DELETE FROM `tbl_output_disaggregation` WHERE projid=:projid");
            $results = $sql->execute(array(':projid' => $projid));

            if (isset($_POST['lvid'])) {
                $counter = count($_POST['lvid']);
                for ($i = 0; $i < $counter; $i++) {
                    $state_id = $_POST['lvid'][$i];
                    $sites = $_POST['site'][$i];
                    $site_ids = explode(",", $sites);
                    $count_sites = count($site_ids);
                    for ($j = 0; $j < $count_sites; $j++) {
                        $site = $site_ids[$j];
                        $sql = $db->prepare("INSERT INTO `tbl_project_sites`(projid,state_id,site) VALUES(:projid,:state_id,:site)");
                        $result  = $sql->execute(array(":projid" => $projid, ":state_id" => $state_id, ":site" => $site));
                    }
                }
            }
            $msg = true;
            $sql = $db->prepare("DELETE FROM `tbl_files` WHERE projid=:projid");
            $results = $sql->execute(array(':projid' => $projid));

            if (isset($_POST['attachmentpurpose'])) {
                $countP = count($_POST["attachmentpurpose"]);
                $stage = 4;
                for ($cnt = 0; $cnt < $countP; $cnt++) {
                    if (!empty($_FILES['pfiles']['name'][$cnt])) {
                        $purpose = $_POST["attachmentpurpose"][$cnt];
                        $filename = basename($_FILES['pfiles']['name'][$cnt]);
                        $ext = substr($filename, strrpos($filename, '.') + 1);
                        if (($ext != "exe") && ($_FILES["pfiles"]["type"][$cnt] != "application/x-msdownload")) {
                            $newname = time() . '_' . $projid . "_" . $stage . "_" . $filename;
                            $filepath = "../../uploads/main-project/" . $newname;
                            if (!file_exists($filepath)) {
                                if (move_uploaded_file($_FILES['pfiles']['tmp_name'][$cnt], $filepath)) {
                                    $fname = $newname;
                                    $mt = $filepath;
                                    $filecategory = "Project Planning";
                                    $qry1 = $db->prepare("INSERT INTO tbl_files (projid, projstage, filename, ftype, floc, fcategory, reason, uploaded_by, date_uploaded) VALUES (:projid, :stage, :filename, :ftype, :floc,:fcategory,:reason,:uploaded_by, :date_uploaded)");
                                    $results =  $qry1->execute(array(":projid" => $projid, ":stage" => $stage, ":filename" => $filename, ":ftype" => $ext, ":floc" => $mt, ":fcategory" => $filecategory, ":reason" => $purpose, ":uploaded_by" => $user_name, ":date_uploaded" => $datecreated));
                                    if ($results) {
                                        $type = true;
                                        $msg =  "Successfully uploaded files";
                                    } else {
                                        $msg =  "Error uploading files";
                                    }
                                } else {
                                    $msg =  "file culd not be  allowed";
                                }
                            } else {
                                $msg = 'File you are uploading already exists, try another file!!';
                            }
                        } else {
                            $msg = 'This file type is not allowed, try another file!!';
                        }
                    }
                }
            }
        }


        echo json_encode(array("projid" => $projid, "success" => $msg));
    }

    if (isset($_POST['store_data'])) {
        $progid = $_POST['progid'];
        $projid = $_POST['projid'];
        $output_id = $_POST['output_id'];
        $indicator_id = $_POST['indicator_id'];
        $output_duration = 0;
        $target = $_POST['project_target'];
        $budget = 0;
        $output_financial_year = 0;
        $unit_type = isset($_POST['unit_type']) && !empty($_POST['unit_type'])  ? $_POST['unit_type'] : 0;
        $mapping_type = $_POST['mapping_type'];

        $query_rsOutput =  $db->prepare("SELECT * FROM  tbl_project_details WHERE  projid=:projid AND indicator=:output_indicator");
        $query_rsOutput->execute(array(":projid" => $projid, ":output_indicator" => $indicator_id));
        $Rows_rsOutput = $query_rsOutput->fetch();
        $totalRows_rsOutput = $query_rsOutput->rowCount();

        $last_id = 0;
        if ($totalRows_rsOutput == 0) {
            $sql = $db->prepare("INSERT INTO `tbl_project_details`(progid, projid, outputid, indicator,output_start_year, duration, budget, total_target,unit_type) VALUES(:progid, :projid, :output, :indicator,:output_start_year, :outputduration, :budget,:outputTarget,:unit_type)");
            $result  = $sql->execute(array(":progid" => $progid, ":projid" => $projid, ":output" => $output_id, ":indicator" => $indicator_id, ":output_start_year" => $output_financial_year, ":outputduration" => $output_duration, ":budget" => $budget, ":outputTarget" => $target, ":unit_type" => $unit_type));
            $last_id = $db->lastInsertId();
        } else {
            $sql = $db->prepare("UPDATE  tbl_project_details SET output_start_year=:output_start_year, duration=:output_duration, budget=:budget, total_target=:target, unit_type=:unit_type WHERE projid=:projid AND indicator=:indicator");
            $result  = $sql->execute(array(":output_start_year" => $output_financial_year, ":output_duration" => $output_duration, ":budget" => $budget, ":target" => $target, ":unit_type" => $unit_type, "projid" => $projid, ":indicator" => $indicator_id));

            $last_id = $Rows_rsOutput['id'];

            $sql = $db->prepare("DELETE FROM `tbl_output_disaggregation` WHERE outputid=:outputid");
            $results = $sql->execute(array(':outputid' => $last_id));

            $sql = $db->prepare("DELETE FROM `tbl_output_disaggregation_values` WHERE output_id=:outputid");
            $results = $sql->execute(array(':outputid' => $last_id));
        }

        $output_location = isset($_POST['output_location']) && !empty(['output_location']) ? $_POST['output_location'] : [];
        $output_site = isset($_POST['site']) && !empty(['site']) ? $_POST['site'] : [];
        $total_output_locations = count($output_location);
        $total_output_sites = count($output_site);


        if ($total_output_locations > 0) {
            for ($i = 0; $i < $total_output_locations; $i++) {
                $state = $output_location[$i];
                $target = isset($_POST['units']) && !empty(['units']) ? $_POST['units'][$i] : 0;
                $output_site = isset($_POST['site']) && !empty(['site']) ? $_POST['site'][$i] : 0;
                $sequence = isset($_POST['sequence']) && !empty(['sequence']) ? $_POST['sequence'][$i] : 0;
                $sql = $db->prepare("INSERT INTO `tbl_output_disaggregation`(projid,outputid,outputstate,output_site,sequence,total_target) VALUES(:projid,:output,:outputstate,:output_site,:sequence,:target)");
                $result = $sql->execute(array(":projid" => $projid, ":output" => $last_id, ":outputstate" => $state, ":output_site" => $output_site, ":sequence" => $sequence, ":target" => $target));
            }
        }

        if ($output_site > 0) {
            for ($i = 0; $i < $total_output_sites; $i++) {
                $state = 0;
                $target = isset($_POST['units']) && !empty(['units']) ? $_POST['units'][$i] : 1;
                $output_site = isset($_POST['site']) && !empty(['site']) ? $_POST['site'][$i] : 0;
                $sql = $db->prepare("INSERT INTO `tbl_output_disaggregation`(projid,outputid,outputstate,output_site,total_target) VALUES(:projid,:output,:outputstate,:output_site,:target)");
                $result = $sql->execute(array(":projid" => $projid, ":output" => $last_id, ":outputstate" => $state, ":output_site" => $output_site, ":target" => $target));
            }
        }

        if (isset($_POST['dissaggregation']) && $_POST['dissaggregation'] == 1) {
            $total_diss_val = count($_POST['dissaggregation_val']);
            for ($i = 0; $i < $total_diss_val; $i++) {
                $dissaggregation = isset($_POST['dissaggregation_val']) && !empty(['dissaggregation_val']) ? $_POST['dissaggregation_val'][$i] : 0;
                $target = isset($_POST['dissaggregation_target']) && !empty(['dissaggregation_target']) ? $_POST['dissaggregation_target'][$i] : 0;
                $sql = $db->prepare("INSERT INTO `tbl_output_disaggregation_values`(projid,output_id,dissaggregation,dissaggregation_target) VALUES(:projid,:output,:dissaggregation,:dissaggregation_target)");
                $result = $sql->execute(array(":projid" => $projid, ":output" => $last_id, ":dissaggregation" => $dissaggregation, ":dissaggregation_target" => $target));
            }
        }

        echo json_encode(array("success" => true, "output_id" => $last_id));
    }

    if (isset($_POST['store_monitoring_frequency'])) {
        $results = false;
        if (validate_csrf_token($_POST['csrf_token'])) {
            $projid = $_POST['projid'];
            $monitoring_frequency = $_POST['monitoring_frequency'];
            $sql = $db->prepare("UPDATE tbl_projects SET monitoring_frequency=:monitoring_frequency WHERE  projid=:projid");
            $result  = $sql->execute(array(":monitoring_frequency" => $monitoring_frequency, ":projid" => $projid));
        }
        echo json_encode(array("success" => $result));
    }

    if (isset($_GET['projcode'])) {
        $data = $_GET['projcode'];
        $query_rsProject = $db->prepare("SELECT projcode FROM tbl_projects WHERE projcode=:data");
        $query_rsProject->execute(array(":data" => $data));
        $row_rsProject = $query_rsProject->fetch();
        $totalRows_rsProject = $query_rsProject->rowCount();
        $result =  $totalRows_rsProject > 0 ? true : false;
        echo json_encode($result);
    }

    if (isset($_GET['get_project_end_year'])) {
        $program_start_year = $_GET['program_start_year'];
        $program_end_year = $_GET['program_end_year'];
        $project_start_year = $_GET['project_start_year'];
        $project_duration = $_GET['project_duration'];

        $query_rsIndicatorYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year WHERE id=:id");
        $query_rsIndicatorYear->execute(array(":id" => $project_start_year));
        $row_rsIndicatorYear = $query_rsIndicatorYear->fetch();
        $project_starting_year = $row_rsIndicatorYear['yr'];

        $project_start_date = "$project_starting_year-07-01";
        $project_end_date = date('Y-m-d', strtotime($project_start_date . ' + ' . $project_duration . ' days'));
        $project_end_year = date("Y", strtotime($project_end_date));
        $project_end_month = date("m", strtotime($project_end_date));
        $validate_end_year = false;

        if ($program_end_year >= $project_end_year) {
            if ($program_end_year == $project_end_year) {
                $validate_end_year = $project_end_month < 7 ? true : false;
            } else {
                $validate_end_year = true;
            }
        }

        // change program duration into days
        $project_start_date = "01-07-$project_starting_year";
        $program_end_date = "30-06-$program_end_year";
        $program_duration_difference = strtotime($program_end_date) - strtotime($project_start_date);
        $program_duration = abs(round($program_duration_difference / 86400)) + 1;
        $remaining_duration = $program_duration - $project_duration;
        $duration = $remaining_duration > 0 ? $remaining_duration : $program_duration;
        echo json_encode(array('remaining_duration' =>  $duration, 'success' => $validate_end_year, "project_end_year" => $project_end_year));
    }


    if (isset($_POST['getward'])) {
        $getward = explode(",", $_POST['getward']);
        $data = '';
        for ($i = 0; $i < count($getward); $i++) {
            $query_rsComm = $db->prepare("SELECT id, state FROM tbl_state WHERE parent IS NULL AND id=:getward LIMIT 1");
            $query_rsComm->execute(array(":getward" => $getward[$i]));
            $row_rsComm = $query_rsComm->fetch();
            $community = $row_rsComm['state'];

            $query_ward = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:getward");
            $query_ward->execute(array(":getward" => $getward[$i]));
            $data .= '
            <optgroup label="' . $community . '"> ';
            while ($row = $query_ward->fetch()) {
                $projlga = $row['id'];
                $query_rsLocations = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:id");
                $query_rsLocations->execute(array(":id" => $projlga));
                $row_rsLocations = $query_rsLocations->fetch();
                $total_locations = $query_rsLocations->rowCount();
                if ($total_locations > 0) {
                    $data .= '<option value="' . $row['id'] . '"> ' . $row['state'] . '</option>';
                }
            }
            $data .= '
            <optgroup>';
        }
        echo $data;
    }

    if (isset($_POST['getlocation'])) {
        $getlocation = explode(",", $_POST['getlocation']);
        $data = '';
        for ($j = 0; $j < count($getlocation); $j++) {
            $query_ward = $db->prepare("SELECT id, state, parent FROM tbl_state WHERE id='$getlocation[$j]'");
            $query_ward->execute();
            $row_ward = $query_ward->fetch();
            $ward = $row_ward['state'];
            $parent = $row_ward['parent'];

            $query_rsComm = $db->prepare("SELECT id, state FROM tbl_state WHERE parent IS NULL AND id='$parent' LIMIT 1");
            $query_rsComm->execute();
            $row_rsComm = $query_rsComm->fetch();
            $community = $row_rsComm['state'];

            $query_loca = $db->prepare("SELECT id, state FROM tbl_state WHERE parent='$getlocation[$j]'");
            $query_loca->execute();
            $data .= '<optgroup label="' . $ward . ' (' .  $community . ')">';
            while ($row = $query_loca->fetch()) {
                $data .= '<option value="' . $row['id'] . '"> ' . $row['state'] . '</option>';
            }
            $data .= '<optgroup>';
        }
        echo $data;
    }

    if (isset($_GET['get_site_locations'])) {
        $locations = $_GET['locations'];
        $options = '<option value="">Select ' . $level2label . ' list</option>';
        $count_locations = count($locations);
        if ($count_locations > 0) {
            for ($j = 0; $j < $count_locations; $j++) {
                $state_id = $locations[$j];
                $query_ward = $db->prepare("SELECT id, state FROM tbl_state WHERE id=:state_id");
                $query_ward->execute(array(":state_id" => $state_id));
                $row_ward = $query_ward->fetch();
                $options .= '<option value="' . $row_ward['id'] . '"> ' . $row_ward['state'] . '</option>';
            }
        }
        echo $options;
    }


    if (isset($_GET['get_project_locations'])) {
        $projid = $_GET['projid'];
        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid=:projid");
        $query_rsProjects->execute(array(":projid" => $projid));
        $row_rsProgjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();

        $options = '<option value="">Select ' . $level2label . ' list</option>';
        if ($totalRows_rsProjects > 0) {
            $locations = explode(",", $row_rsProgjects['projlga']);
            $count_locations = count($locations);
            for ($j = 0; $j < $count_locations; $j++) {
                $state_id = $locations[$j];
                $query_ward = $db->prepare("SELECT id, state FROM tbl_state WHERE id=:state_id");
                $query_ward->execute(array(":state_id" => $state_id));
                $row_ward = $query_ward->fetch();
                $options .= '<option value="' . $row_ward['id'] . '"> ' . $row_ward['state'] . '</option>';
            }
        }
        echo $options;
    }


    if (isset($_GET['get_project_sites'])) {
        $projid = $_GET['projid'];
        $query_rsProjects = $db->prepare("SELECT * FROM tbl_project_sites WHERE projid=:projid");
        $query_rsProjects->execute(array(":projid" => $projid));
        $totalRows_rsProjects = $query_rsProjects->rowCount();

        $options = '<option value="">Select Sites list</option>';
        if ($totalRows_rsProjects > 0) {
            while ($row_rsProgjects = $query_rsProjects->fetch()) {
                $options .= '<option value="' . $row_rsProgjects['site_id'] . '"> ' . $row_rsProgjects['site'] . '</option>';
            }
        }
        echo $options;
    }


    if (isset($_POST['getfinancier'])) {
        $progid = $_POST['getfinancier'];
        $query_rsFunding =  $db->prepare("SELECT p.amountfunding, p.sourcecategory,f.type  FROM tbl_myprogfunding p INNER JOIN tbl_funding_type f  ON  p.sourcecategory= f.id WHERE progid =:progid");
        $query_rsFunding->execute(array(":progid" => $progid));
        $row_rsFunding = $query_rsFunding->fetch();
        $totalRows_rsFunding = $query_rsFunding->rowCount();
        $options = '<option value="">Select Source list</option>';
        if ($totalRows_rsFunding > 0) {
            do {
                $source_category = $row_rsFunding['sourcecategory'];
                $source_name = $row_rsFunding['type'];
                $options .=  '<option value="' . $source_category . '">' . $source_name . '</option>';
            } while ($row_rsFunding = $query_rsFunding->fetch());
        }
        echo $options;
    }

    if (isset($_GET['get_total_number_of_projects'])) {
        if (isset($_GET["type"]) && $_GET["type"] == 1) {
            $sql = $db->prepare("SELECT * FROM `tbl_programs` g left join `tbl_projects` p on p.progid=g.progid left join tbl_fiscal_year y on y.id=p.projfscyear left join tbl_status s on s.statusid=p.projstatus WHERE g.program_type=0 AND p.deleted='0' ORDER BY `projfscyear` ASC");
            $sql->execute();
        }

        $projects = 0;
        while ($row_project = $sql->fetch()) {
            $itemId = $row_project['projid'];
            $project_department = $row_project['projsector'];
            $project_section = $row_project['projdept'];
            $project_directorate = $row_project['directorate'];

            $page_detials = get_page_details($user_designation, "all-programs");
            $allow_read_records = $page_detials ? $page_detials['allow_read'] : false;
            $filter_department = view_record($project_department, $project_section, $project_directorate, $allow_read_records);
            if ($filter_department) {
                $projects++;
            }
        }
        echo json_encode(array("success" => true, "projects" => $projects));
    }

    if (isset($_POST['getIndicator'])) {
        $indicator =  $_POST['indicator'];
        $progid =  $_POST['progid'];
        $query_Indicator = $db->prepare("SELECT indid, indicator_name, indicator_disaggregation, indicator_unit FROM tbl_progdetails INNER JOIN tbl_indicator ON tbl_indicator.indid = tbl_progdetails.indicator WHERE progid =:progid AND id=:indicator");
        $query_Indicator->execute(array(":progid" => $progid, ":indicator" => $indicator));
        $row_rsInd = $query_Indicator->fetch();
        $indid = $opunit = $indicator_name = "";
        if ($row_rsInd) {
            $indid = $row_rsInd['indid'];
            $indicator_name = $row_rsInd['indicator_name'];
            $ben_diss = $row_rsInd['indicator_disaggregation'];
            $diss_type_op = 'Location';
            $unit = $row_rsInd['indicator_unit'];

            $query_rsunit = $db->prepare("SELECT * FROM tbl_measurement_units WHERE id ='$unit' ");
            $query_rsunit->execute();
            $row_rsunit = $query_rsunit->fetch();
            $opunit = $row_rsunit ?  $row_rsunit['unit'] : "";
        }

        echo json_encode(array("indid" => $indid,  "indicator_name" => $opunit . " of " . $indicator_name));
    }

    if (isset($_POST['getprojoutput'])) {
        $progid = $_POST['progid'];
        $query_Target = $db->prepare("SELECT i.indicator_name, i.indid, g.id FROM tbl_progdetails g INNER JOIN tbl_indicator i ON i.indid=g.indicator WHERE progid =:progid GROUP BY g.indicator");
        $query_Target->execute(array(":progid" => $progid));
        $row_Target = $query_Target->fetch();
        $totalRows_Target = $query_Target->rowCount();
        $input = "";
        if ($totalRows_Target > 0) {
            $input = '<option value="">Select Output</option>';
            do {
                $output_name = $row_Target['indicator_name'];
                $indicator = $row_Target['indid'];
                $output_id = $row_Target['id'];

                $query_program = $db->prepare("SELECT SUM(target) as target FROM tbl_progdetails WHERE progid=:progid AND indicator =:indicator");
                $query_program->execute(array(":progid" => $progid, ":indicator" => $indicator));
                $row_program = $query_program->fetch();
                $program_target = $row_program['target'] != null ? $row_program['target'] : 0;
                $query_projTarget = $db->prepare("SELECT SUM(total_target) as target FROM tbl_project_details WHERE progid=:progid AND indicator =:indicator");
                $query_projTarget->execute(array(":progid" => $progid, ":indicator" => $indicator));
                $rowproj = $query_projTarget->fetch();
                $project_target = $rowproj['target'];

                $remaining_target = $program_target - $project_target;
                $input .= $remaining_target > 0 ? '<option value="' . $output_id . '">' . $output_name . '</option>' : "";
            } while ($row_Target = $query_Target->fetch());
        }
        echo $input;
    }

    if (isset($_GET['get_output_details'])) {
        $progid = $_GET['progid'];
        $projid = $_GET['projid'];
        $indicator_id = $_GET['indicator_id'];

        $query_Output = $db->prepare("SELECT * FROM tbl_project_details WHERE indicator =:indicator AND projid=:projid");
        $query_Output->execute(array(":indicator" => $indicator_id, ":projid" => $projid));
        $row_rsOutput = $query_Output->fetch();
        $total_Output = $query_Output->rowCount();

        $program_target  =  $output_target = 0;
        $row_rsOutput_details = $row_rsOutput_dissaggregation = [];
        if ($total_Output > 0) {
            $outputid = $row_rsOutput['id'];
            $indicator_id = $row_rsOutput['indicator'];
            $output_budget = $row_rsOutput['budget'];
            $output_target = $row_rsOutput['total_target'];

            $query_Output_details = $db->prepare("SELECT * FROM tbl_output_disaggregation WHERE outputid = :outputid");
            $query_Output_details->execute(array(":outputid" => $outputid));
            $row_rsOutput_details = $query_Output_details->fetchAll();

            $query_Output_dissaggregation = $db->prepare("SELECT * FROM tbl_output_disaggregation_values WHERE output_id = :outputid AND projid = :projid ");
            $query_Output_dissaggregation->execute(array(":outputid" => $outputid, ":projid" => $projid));
            $row_rsOutput_dissaggregation = $query_Output_dissaggregation->fetchAll();
        }


        $query_Target = $db->prepare("SELECT SUM(target) as target FROM `tbl_progdetails` WHERE progid =:progid and indicator =:indicator_id");
        $query_Target->execute(array(":progid" => $progid, ":indicator_id" => $indicator_id));
        $row_Target = $query_Target->fetch();
        $program_target = $row_Target['target'] != null ? $row_Target['target'] : 0;

        $query_projTarget = $db->prepare("SELECT SUM(total_target) as target FROM tbl_project_details WHERE progid=:progid AND indicator =:indicator");
        $query_projTarget->execute(array(":progid" => $progid, ":indicator" => $indicator_id));
        $rowproj = $query_projTarget->fetch();
        $project_target = $rowproj != null ? $rowproj['target'] : 0;
        $program_target = ($program_target - $project_target) + $output_target;

        $query_Indicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid =:indicator_id ");
        $query_Indicator->execute(array(":indicator_id" => $indicator_id));
        $row_Indicator = $query_Indicator->fetch();
        $mapping_type = $row_Indicator ? $row_Indicator['indicator_mapping_type'] : 0;
        $indicator_name = $row_Indicator ? $row_Indicator['indicator_name'] : '';
        $indicator_unit = $row_Indicator ? $row_Indicator['indicator_unit'] : '';

        $query_rsunit = $db->prepare("SELECT * FROM tbl_measurement_units WHERE id =:indicator_unit ");
        $query_rsunit->execute(array(":indicator_unit" => $indicator_unit));
        $row_rsunit = $query_rsunit->fetch();
        $opunit = $row_rsunit ?  $row_rsunit['unit'] : "";

        echo json_encode(array(
            "program_target" => $program_target . " " . $opunit . ' of ' . $indicator_name,
            "mapping_type" => $mapping_type,
            "output_data" => $row_rsOutput,
            "output_details" => $row_rsOutput_details,
            "dissaggregation_details" => $row_rsOutput_dissaggregation
        ));
    }
} catch (PDOException $ex) {
    var_dump($ex);
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
