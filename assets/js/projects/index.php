<?php

include '../controller.php';
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

try {
    if (isset($_POST['insert_project'])) {
        $progid = $_POST['progid'];
        $projcode = $_POST['projcode'];
        $projname = $_POST['projname'];
        $projdesc = $_POST['projdescription'];
        $projimplmethod = $_POST['projimplmethod'];
        $projfscyear = $_POST['projfscyear1'];
        $projduration = $_POST['projduration1'];
        $key_unique = $_POST['key_unique'];
        $impact = $_POST['impact'];

        $projevaluation = $_POST['projevaluation'];
        $projlevel1 = implode(",", $_POST['projcommunity']);
        $projlevel2 = implode(",", $_POST['projlga']);
        $projlevel3 = implode(",", $_POST['projstate']);
        $mne_budget =  0;
        $projtype = "New";
        $datecreated = date("Y-m-d");
        $createdby = $user_name;
        $projapprovestatus = 0;
        $projinspection = 0;
        $msg = false;


        $query_rs_fsc =  $db->prepare("SELECT yr FROM tbl_fiscal_year where id =:id");
        $query_rs_fsc->execute(array(":id" => $projfscyear));
        $row_rs_fsc = $query_rs_fsc->fetch();
        $project_start_year = $row_rs_fsc ? $row_rs_fsc['yr'] : 0;
        $projstartdate = $project_start_year . "-07-01";
        $projenddate = date('Y-m-d', strtotime($projstartdate . ' + ' . $projduration . ' days'));
        $projid = false;

        if (isset($_POST['project_id']) && !empty($_POST['project_id'])) {
            $projid = $_POST['project_id'];
            $insertSQL = $db->prepare("UPDATE `tbl_projects` SET projcode=:projcode, projname=:projname, projdesc=:projdesc, projfscyear=:projfscyear, projduration=:projduration,projimpact=:projimpact,projevaluation=:projevaluation, projcommunity=:projlevel1, projlga=:projlevel2, projstate=:projlevel3, projcategory=:projimplmethod,projstartdate=:projstartdate,projenddate=:projenddate, updated_by=:createdby, date_updated=:datecreated WHERE projid =:projid");
            $result  = $insertSQL->execute(array(":projcode" => $projcode, ":projname" => $projname, ":projdesc" => $projdesc, ":projfscyear" => $projfscyear, ":projduration" => $projduration, ":projimpact" => $impact, ":projevaluation" => $projevaluation, ":projlevel1" => $projlevel1, ":projlevel2" => $projlevel2, ":projlevel3" => $projlevel3,  ":projimplmethod" => $projimplmethod, ":projstartdate" => $projstartdate, ":projenddate" => $projenddate, ":createdby" => $createdby, ":datecreated" => $datecreated, ":projid" => $projid));
        } else {
            $sql = $db->prepare("INSERT INTO `tbl_projects` (progid,key_unique, projcode, projname, projdesc, projtype, projfscyear, projduration,projimpact,projevaluation, projcommunity, projlga, projstate, projcategory, projstartdate,projenddate, user_name, date_created) VALUES(:progid,:key_unique, :projcode, :projname,:projdesc, :projtype, :projfscyear, :projduration, :projimpact,:projevaluation,:projlevel1, :projlevel2, :projlevel3, :projimplmethod, :projstartdate, :projenddate, :createdby, :datecreated)");
            $result  = $sql->execute(array(":progid" => $progid, ":key_unique" => $key_unique, ":projcode" => $projcode, ":projname" => $projname, ":projdesc" => $projdesc, ":projtype" => $projtype, ":projfscyear" => $projfscyear, ":projduration" => $projduration, ":projimpact" => $impact, ":projevaluation" => $projevaluation, ":projlevel1" => $projlevel1, ":projlevel2" => $projlevel2, ":projlevel3" => $projlevel3,  ":projimplmethod" => $projimplmethod, ":projstartdate" => $projstartdate, ":projenddate" => $projenddate, ":createdby" => $createdby, ":datecreated" => $datecreated));
            $projid = $result ?  $db->lastInsertId() : false;
        }

        if ($projid) {
            $sql = $db->prepare("DELETE FROM `tbl_project_sites` WHERE projid=:projid");
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
                        $sql = $db->prepare("INSERT INTO `tbl_project_sites`(unique_key,projid,site) VALUES(:unique_key,:projid,:site)");
                        $result  = $sql->execute(array(":unique_key" => $key_unique, ":projid" => $projid, ":site" => $site));
                    }
                }
            }
            $msg = true;
        }

        echo json_encode(array("projid" => $projid, "success" => $msg));
    }

    if (isset($_POST['insert_project_financiers'])) {
        $type = false;
        $projid = $_POST['project_id'];
        $key_unique = $_POST['key_unique'];
        $progid = $_POST['progid'];
        $datecreated = date("Y-m-d");

        $sql = $db->prepare("DELETE FROM `tbl_projfunding` WHERE projid=:projid");
        $results = $sql->execute(array(':projid' => $projid));

        if (isset($_POST['amountfunding'])) {
            for ($i = 0; $i < count($_POST['amountfunding']); $i++) {
                $sourcecatergory = $_POST['finance'][$i];
                $amountfunding = $_POST['amountfunding'][$i];
                $sql = $db->prepare("INSERT INTO `tbl_projfunding`(progid, projid, sourcecategory, amountfunding, created_by, date_created) VALUES(:progid, :projid, :sourcecatergory, :amountfunding, :created_by, :date_created)");
                $result  = $sql->execute(array(":progid" => $progid, ":projid" => $projid, ":sourcecatergory" => $sourcecatergory, ":amountfunding" => $amountfunding, ":created_by" => $user_name, ":date_created" => $datecreated));
                $type = $result ? true : false;
            }
        }
        echo json_encode(array("success" => $type));
    }

    if (isset($_POST['insert_project_files'])) {
        $projid = $_POST['project_id'];
        $progid = $_POST['progid'];
        $datecreated = date("Y-m-d");

        $type = false;
        $msg = "";

        if (isset($_POST['attachmentpurpose'])) {
            $countP = count($_POST["attachmentpurpose"]);
            $stage = 1;
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
        echo json_encode(array("success" => $type, "msg" => $msg));
    }

    if (isset($_POST['store_data'])) {
        $unique_key = $_POST['key_unique'];
        $progid = $_POST['progid'];
        $projid = $_POST['projid'];
        $output_id = $_POST['output_id'];
        $indicator_id = $_POST['indicator_id'];
        $output_financial_year = $_POST['output_financial_year'];
        $output_duration = $_POST['output_duration'];
        $target = array_sum($_POST['output_target']);
        $budget = array_sum($_POST['output_budget']);
        $unit_type = isset($_POST['unit_type']) && !empty($_POST['unit_type'])  ? $_POST['unit_type'] : 0;
        $mapping_type = $_POST['mapping_type'];

        $query_rsOutput =  $db->prepare("SELECT * FROM  tbl_project_details WHERE  projid=:projid AND indicator=:output_indicator");
        $query_rsOutput->execute(array(":projid" => $projid, ":output_indicator" => $indicator_id));
        $Rows_rsOutput = $query_rsOutput->fetch();
        $totalRows_rsOutput = $query_rsOutput->rowCount();

        $last_id = 0;
        if ($totalRows_rsOutput == 0) {
            $sql = $db->prepare("INSERT INTO `tbl_project_details`(unique_key, progid, projid, outputid, indicator, duration, budget, total_target,unit_type) VALUES(:unique_key,:progid, :projid, :output, :indicator,  :outputduration, :budget,:outputTarget,:unit_type)");
            $result  = $sql->execute(array(":unique_key" => $unique_key, ":progid" => $progid, ":projid" => $projid, ":output" => $output_id, ":indicator" => $indicator_id, ":outputduration" => $output_duration, ":budget" => $budget, ":outputTarget" => $target, ":unit_type" => $unit_type));
            $last_id = $db->lastInsertId();
        } else {
            $sql = $db->prepare("UPDATE  tbl_project_details SET duration=:output_duration, budget=:budget, total_target=:target, unit_type=:unit_type WHERE unique_key=:unique_key AND indicator=:indicator");
            $result  = $sql->execute(array(":output_duration" => $output_duration, ":budget" => $budget, ":target" => $target, ":unit_type" => $unit_type, "unique_key" => $unique_key, ":indicator" => $indicator_id));

            $last_id = $Rows_rsOutput['id'];
            $sql = $db->prepare("DELETE FROM `tbl_project_output_details` WHERE outputid=:outputid");
            $results = $sql->execute(array(':outputid' => $last_id));

            $sql = $db->prepare("DELETE FROM `tbl_output_disaggregation` WHERE outputid=:outputid");
            $results = $sql->execute(array(':outputid' => $last_id));
        }

        $total_years = count($_POST['output_year']);
        for ($j = 0; $j < $total_years; $j++) {
            $output_year = $_POST['output_year'];
            $output_target = $_POST['output_target'];
            $output_budget = $_POST['output_budget'];
            $target = $output_target[$j];
            $budget = $output_budget[$j];
            $year = $output_year[$j];
            $sql = $db->prepare("INSERT INTO `tbl_project_output_details`(unique_key,progid,projid,outputid,indicator,year,target,budget) VALUES(:unique_key,:progid,:projid,:outputid,:indicator,:year,:target,:budget)");
            $result  = $sql->execute(array(":unique_key" => $unique_key, ":progid" => $progid, ":projid" => $projid, ":outputid" => $last_id, ":indicator" => $indicator_id, ":year" => $year, ":target" => $target, ":budget" => $budget));
        }

        $output_location = isset($_POST['output_location']) && !empty(['output_location']) ? $_POST['output_location'] : [];
        $total_output_locations = count($output_location);

        if ($total_output_locations > 0) {
            for ($i = 0; $i < $total_output_locations; $i++) {
                $state = $output_location[$i];
                $target = isset($_POST['units']) && !empty(['units']) ? $_POST['units'][$i] : 0;
                $output_site = isset($_POST['site']) && !empty(['site']) ? $_POST['site'][$i] : 0;
                $sql = $db->prepare("INSERT INTO `tbl_output_disaggregation`(unique_key,projid,outputid,outputstate,output_site,total_target) VALUES(:unique_key,:projid,:output,:outputstate,:output_site,:target)");
                $result = $sql->execute(array(":unique_key" => $unique_key, ":projid" => $projid, ":output" => $last_id, ":outputstate" => $state, ":output_site" => $output_site, ":target" => $target));
            }
        }
        echo json_encode(array("success" => true, "output_id" => $last_id));
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

    if (isset($_GET['get_output_end_year'])) {
        $projid = $_GET['projid'];
        $output_fsc_year = $_GET['output_fsc_year'];
        $project_duration = $_GET['project_duration'];
        $output_duration = $_GET['output_duration'];

        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid=:projid");
        $query_rsProjects->execute(array(":projid" => $projid));
        $row_rsProgjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();
        $output_starting_year = $output_end_year = $output_years = 0;

        if ($totalRows_rsProjects > 0) {
            $project_end_date = date('Y-m-d', strtotime($row_rsProgjects['projenddate']));
            $query_rsIndicatorYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year WHERE id=:id");
            $query_rsIndicatorYear->execute(array(":id" => $output_fsc_year));
            $row_rsIndicatorYear = $query_rsIndicatorYear->fetch();
            $output_starting_year = $row_rsIndicatorYear['yr'];
            $output_start_date = "$output_starting_year-07-01";
            $output_end_date = date('Y-m-d', strtotime($output_start_date . ' + ' . $output_duration . ' days'));


            $validate_end_year = false;
            if ($output_end_date <= $project_end_date) {
                $output_end_month = date("m", strtotime($output_end_date));
                $project_end_year = date("Y", strtotime($project_end_date));
                $output_end_year = date("Y", strtotime($output_end_date));

                if ($project_end_year == $output_end_year) {
                    if ($output_end_month < 7) {
                        $validate_end_year = true; 
                    } else {
                        $output_end_year = date("Y", strtotime($output_end_date)) + 1;
                    }
                } else {
                    $validate_end_year = true;
                }
            }

            $d1 = new DateTime($output_end_date);
            $d2 = new DateTime($output_start_date);
            $diff = $d2->diff($d1); 
            $month = $diff->m;
            $days = $diff->d;
            $output_years = $month > 0 || $days > 0 ?  $diff->y + 1 : $diff->y;
        }
     
        echo json_encode(array('success' =>  $validate_end_year, "output_start_year" => $output_starting_year, "output_end_year" => $output_end_year, "output_years" => $output_years));
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

    if (isset($_GET['get_project_locations'])) {
        $projid = $_GET['projid'];
        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid=:projid");
        $query_rsProjects->execute(array(":projid" => $projid));
        $row_rsProgjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();

        $options = '<option value="">Select Location list</option>';
        if ($totalRows_rsProjects > 0) {
            $locations = explode(",", $row_rsProgjects['projstate']);
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
                $options .= '<option value="' . $row_rsProgjects['id'] . '"> ' . $row_rsProgjects['site'] . '</option>';
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
            $filter_department = $permissions->open_permission_filter($project_department, $project_section, $project_directorate);
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

                $query_program = $db->prepare("SELECT SUM(target) as target, SUM(budget) AS budget FROM tbl_progdetails WHERE progid=:progid AND indicator =:indicator");
                $query_program->execute(array(":progid" => $progid, ":indicator" => $indicator));
                $row_program = $query_program->fetch();
                $program_target = $row_program['target'] != null ? $row_program['target'] : 0;
                $program_budget = $row_program['budget'] != null ? $row_program['budget'] : 0;

                $query_projTarget = $db->prepare("SELECT SUM(target) as target, SUM(budget) as budget FROM tbl_project_output_details WHERE progid=:progid AND indicator =:indicator AND projid <> 0");
                $query_projTarget->execute(array(":progid" => $progid, ":indicator" => $indicator));
                $rowproj = $query_projTarget->fetch();
                $project_target = $rowproj['target'];
                $project_budget = $rowproj['budget'];

                $remaining_target = $program_target - $project_target;
                $remaining_budget = $program_budget - $project_budget;
                $input .= $remaining_budget > 0 && $remaining_target > 0 ? '<option value="' . $output_id . '">' . $output_name . '</option>' : "";
            } while ($row_Target = $query_Target->fetch());
        }
        echo $input;
    }

    function get_edit_output_details($indicator_id, $unique_key)
    {
        global $db;
        $query_Output = $db->prepare("SELECT * FROM tbl_project_details WHERE indicator =:indicator AND unique_key=:unique_key");
        $query_Output->execute(array(":indicator" => $indicator_id, "unique_key" => $unique_key));
        $row_rsOutput = $query_Output->fetch();
        $total_Output = $query_Output->rowCount();
        $row_rsOutput_details = $row_rsoutput_years = false;
        if ($total_Output > 0) {
            $outputid = $row_rsOutput['id'];
            $query_output_years = $db->prepare("SELECT * FROM tbl_project_output_details WHERE outputid = :outputid AND unique_key = :unique_key ");
            $query_output_years->execute(array(":outputid" => $outputid, ":unique_key" => $unique_key));
            $row_rsoutput_years = $query_output_years->fetchAll();

            $query_Output_details = $db->prepare("SELECT * FROM tbl_output_disaggregation WHERE outputid = :outputid AND unique_key = :unique_key ");
            $query_Output_details->execute(array(":outputid" => $outputid, ":unique_key" => $unique_key));
            $row_rsOutput_details = $query_Output_details->fetchAll();
        }
        return array("output_data" => $row_rsOutput, "output_details" => $row_rsOutput_details, "output_years" => $row_rsoutput_years);
    }

    if (isset($_GET['get_output_details'])) {
        $progid = $_GET['progid'];
        $indicator_id = $_GET['indicator_id'];
        $unique_key = $_GET['unique_key'];

        $query_Target = $db->prepare("SELECT SUM(target) as target, SUM(budget) as budget FROM `tbl_progdetails` WHERE progid =:progid and indicator =:indicator_id");
        $query_Target->execute(array(":progid" => $progid, ":indicator_id" => $indicator_id));
        $row_Target = $query_Target->fetch();
        $program_budget = $row_Target['budget'] != null ? $row_Target['budget'] : 0;
        $program_target = $row_Target['target'] != null ? $row_Target['target'] : 0;

        $query_projTarget = $db->prepare("SELECT SUM(target) as target, SUM(budget) as budget FROM tbl_project_output_details WHERE progid=:progid AND indicator =:indicator AND projid <> 0");
        $query_projTarget->execute(array(":progid" => $progid, ":indicator" => $indicator_id));
        $rowproj = $query_projTarget->fetch();
        $project_target = $rowproj != null ? $rowproj['target'] : 0;
        $project_budget = $rowproj != null ? $rowproj['budget'] : 0;
        $remaining_target = $program_target - $project_target;
        $remaining_budget = $program_budget - $project_budget;

        $query_Indicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid =:indicator_id ");
        $query_Indicator->execute(array(":indicator_id" => $indicator_id));
        $row_Indicator = $query_Indicator->fetch();
        $mapping_type = $row_Indicator ? $row_Indicator['indicator_mapping_type'] : 0;

        $output_edit_details = get_edit_output_details($indicator_id, $unique_key);
        echo json_encode(array("program_budget" => $remaining_budget, "program_target" => $remaining_target, "mapping_type" => $mapping_type, $output_edit_details));
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
