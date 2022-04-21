<?php
require 'authentication.php';
 
try {

    $results = "";
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }

    if (isset($_GET['projid'])) {
        $projid = $_GET['projid'];

        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid='$projid'");
        $query_rsProjects->execute();
        $row_rsProgjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();

        $progid = $row_rsProgjects['progid'];
        $projcode = $row_rsProgjects['projcode'];
        $projname = $row_rsProgjects['projname'];
        $projtype = $row_rsProgjects['projtype'];
        $projbudget = $row_rsProgjects['projbudget'];
        $projfscyear = $row_rsProgjects['projfscyear'];
        $projduration = $row_rsProgjects['projduration'];
        $projinspection = $row_rsProgjects['projinspection']; 
        $projevaluation = $row_rsProgjects['projevaluation'];
        $projmapping = $row_rsProgjects['projmapping']; 

        $projcase = $row_rsProgjects['projcase']; 
        $projcommunity = $row_rsProgjects['projcommunity'];
        $projlga = $row_rsProgjects['projlga'];
        $projstate = $row_rsProgjects['projstate'];
        $projlocation = $row_rsProgjects['projlocation'];
        $projcategory = $row_rsProgjects['projcategory'];
        $projwaypoints = $row_rsProgjects['projwaypoints'];
        $projstatus = $row_rsProgjects['projstatus'];
        $user_name = $row_rsProgjects['user_name'];

        $endYear = floor($projduration / 365);
        $remaining  = $projduration % 365;

        $query_rsFscYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year where id ='$projfscyear'");
        $query_rsFscYear->execute();
        $row_rsFscYear = $query_rsFscYear->fetch();
        $projstartYear = $row_rsFscYear['yr'];

        if ($remaining > 0) {
            $endYear = $endYear  + 1;
        }

        $projectendYear = $endYear + $projstartYear;
        $Date = $projstartYear . "-07-01";
        $projectendYearDate =  date('Y-m-d', strtotime($Date . ' + ' . $projduration . ' days'));

        //get the project name 
        $query_rsProgram = $db->prepare("SELECT * FROM tbl_programs WHERE deleted='0' and progid='$progid'");
        $query_rsProgram->execute();
        $row_rsProgram = $query_rsProgram->fetch();
        $totalRows_rsProgram = $query_rsProgram->rowCount();


        $progname = $row_rsProgram['progname'];
        $syear = $row_rsProgram['syear'];
        $years = $row_rsProgram['years'];
        $progobjective = $row_rsProgram['kpi'];
        $outcomeIndicatorid = $row_rsProgram['outcomeIndicator'];
        $outcome = $row_rsProgram['outcome'];


        //get the indicators
        $query_rsOutcomeIndicator = $db->prepare("SELECT DISTINCT indid, indicator_name FROM `tbl_indicator` WHERE baseline=1  AND indid ='$outcomeIndicatorid'");
        $query_rsOutcomeIndicator->execute();
        $row_rsOutcomeIndicator = $query_rsOutcomeIndicator->fetch();
        $totalRows_rsOutcomeIndicator = $query_rsOutcomeIndicator->rowCount();
        $outcomeIndicator = $row_rsOutcomeIndicator['indicator_name'];

        //get  funding 
        $query_rsFunding =  $db->prepare("SELECT * FROM tbl_myprogfunding WHERE progid ='$progid'");
        $query_rsFunding->execute();
        $row_rsFunding = $query_rsFunding->fetch();
        $totalRows_rsFunding = $query_rsFunding->rowCount();

        //get the indicators
        $query_rsOutput = $db->prepare("SELECT DISTINCT indid, indicator_name FROM `tbl_progdetails` d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE d.progid ='$progid'");
        $query_rsOutput->execute();
        $row_rsOutput = $query_rsOutput->fetch();
        $totalRows_rsOutput = $query_rsOutput->rowCount();

        //get the project name 
        $query_rsProjectPartner = $db->prepare("SELECT * FROM tbl_myprojpartner WHERE projid='$projid'");
        $query_rsProjectPartner->execute();
        $row_rsProgjectPartner = $query_rsProjectPartner->fetch();
        $totalRows_rsProjectPartner = $query_rsProjectPartner->rowCount();
        $lead_implementer = $row_rsProgjectPartner['lead_implementer'];
        $implementing_partner = explode(",", $row_rsProgjectPartner['implementing_partner']);
        $collaborative_partner = explode(",", $row_rsProgjectPartner['collaborative_partner']);
    }

    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addprojectfrm")) {
        //upload random name/number
        $progid = $_POST['progid'];
        // $projid = $_GET['projid'];
        $projid = $_POST['projid'];
        
        $projname = $_POST['projname'];
        $projcode = $_POST['projcode'];
        $projimplmethod = $_POST['projimplmethod'];
        $projfscyear = $_POST['projfscyear1'];
        $projduration = $_POST['projduration1'];
        $projinspection = $_POST['projinspection'];
        $projevaluation = $_POST['projevaluation'];
        $projmapping = $_POST['projmapping'];
        $projlevel1 = implode(",", $_POST['projcommunity']);
        $projlevel2 = implode(",", $_POST['projlga']);
        $projlevel3 = implode(",", $_POST['projstate']);
        $datecreated = date("Y-m-d");
        $createdby = $user_name;

        $insertSQL = $db->prepare("UPDATE `tbl_projects` SET projcode=:projcode, projname=:projname, projfscyear=:projfscyear, projduration=:projduration, projmapping=:projmapping, projinspection=:projinspection,projevaluation=:projevaluation, projcommunity=:projlevel1, projlga=:projlevel2, projstate=:projlevel3, projcategory=:projimplmethod, updated_by=:createdby, date_updated=:datecreated WHERE projid =:projid");
        $result  = $insertSQL->execute(array(":projcode" => $projcode, ":projname" => $projname, ":projfscyear" => $projfscyear, ":projduration" => $projduration,":projmapping"=>$projmapping, ":projinspection" => $projinspection,":projevaluation"=>$projevaluation, ":projlevel1" => $projlevel1, ":projlevel2" => $projlevel2, ":projlevel3" => $projlevel3,  ":projimplmethod" => $projimplmethod, ":createdby" => $createdby, ":datecreated" => $datecreated, ":projid" => $projid));

        if ($result) {
            $stage = 1;
            $query_rsFile = $db->prepare("SELECT * FROM tbl_files WHERE projstage=:stage and projid=:projid");
            $query_rsFile->execute(array(":stage" => $stage, ":projid" => $projid));
            $row_rsFile = $query_rsFile->fetch();
            $totalRows_rsFile = $query_rsFile->rowCount();

            if (isset($_POST['fid'])) {
                function validate_file($fid)
                {
                    $popfid = [];
                    for ($pop = 0; $pop < count($_POST['fid']); $pop++) {
                        $popfid[] = $_POST['fid'][$pop];
                    }
                    $handler = in_array($fid, $popfid);
                    if ($handler) {
                        return true; // if it exists return false
                    } else {
                        return false;
                    }
                }

                if ($totalRows_rsFile > 0) {
                    do {
                        $stage = 1;
                        $pdfname = $row_rsFile['filename'];
                        $newname = $projid . "_" . $stage . "_" . $pdfname;
                        $fid = $row_rsFile['fid'];
                        $handler = validate_file($fid);
                        if ($handler == false) {
                            $deleteQuery = $db->prepare("DELETE FROM `tbl_files` WHERE projid=:projid AND projstage=:stage AND fid =:fid");
                            $results = $deleteQuery->execute(array(":projid" => $projid, ":stage" => $stage, ':fid' => $fid));
                            unlink("uploads/" . $newname);
                        }
                    } while ($row_rsFile = $query_rsFile->fetch());
                }
            } 

            $catid = $projid;
            if (isset($_POST['attachmentpurpose'])) {
                $stage = 1;
                $countP = count($_POST["attachmentpurpose"]);
                // insert new data 
                for ($cnt = 0; $cnt < $countP; $cnt++) {
                    if (!empty($_FILES['pfiles']['name'][$cnt])) {
                        $purpose = $_POST["attachmentpurpose"][$cnt];
                        $filename = basename($_FILES['pfiles']['name'][$cnt]);
                        $catid = $catid + 1;
                        $ext = substr($filename, strrpos($filename, '.') + 1);
                        if (($ext != "exe") && ($_FILES["pfiles"]["type"][$cnt] != "application/x-msdownload")) {
                            $newname = $projid . "_" . $stage . "_" . $filename;
                            $filepath = "uploads/" . $newname;
                            if (!file_exists($filepath)) {
                                if (move_uploaded_file($_FILES['pfiles']['tmp_name'][$cnt], $filepath)) {
                                    $fname = $newname;
                                    $mt = $filepath;
                                    $user_name = 23;
                                    $filecategory = "Project Planning";

                                    $qry1 = $db->prepare("INSERT INTO tbl_files (projid, projstage, filename, ftype, floc, fcategory, reason, uploaded_by, date_uploaded)
                                     VALUES (:projid, :stage, :filename, :ftype, :floc, :fcategory, :reason, :uploaded_by, :date_uploaded)");
                                    $qry1->execute(array(":projid" => $projid, ":stage" => $stage, ":filename" => $filename, ":ftype" => $ext, ":floc" => $mt, ":fcategory" => $filecategory, ":reason" => $purpose, ":uploaded_by" => $createdby, ":date_uploaded" => $datecreated));
                                }
                            } else {
                                $type = 'error';
                                $msg = 'File you are uploading already exists, try another file!!';
                                $results = "<script type=\"text/javascript\">
                                    swal({
                                    title: \"Error!\",
                                    text: \" $msg \",
                                    type: 'Danger',
                                    timer: 10000,
                                    showConfirmButton: false });
                                </script>";
                            }
                        } else {
                            $type = 'error';
                            $msg = 'This file type is not allowed, try another file!!';

                            $results = "<script type=\"text/javascript\">
                                swal({
                                title: \"Error!\",
                                text: \" $msg \",
                                type: 'Danger',
                                timer: 10000,
                                showConfirmButton: false });
                            </script>";
                        }
                    }
                }
            }

            if (isset($_POST['projleadimplementor'])) {
                $projleadimplementor = $_POST['projleadimplementor'];
                $projimplementingpartner = implode(",", $_POST['projimplementingpartner']); 
                $insertSQL1 = $db->prepare("UPDATE `tbl_myprojpartner` SET lead_implementer=:lead_implementer, implementing_partner=:implementing_partner WHERE projid=:projid");
                $result1  = $insertSQL1->execute(array(":projid" => $projid, ":lead_implementer" => $projleadimplementor, ":implementing_partner" => $projimplementingpartner));
            }

            // project funding 
            if (isset($_POST['amountfunding'])) {
                $deleteQuery = $db->prepare("DELETE FROM `tbl_projfunding` WHERE projid=:projid");
                $results = $deleteQuery->execute(array(':projid' => $projid));
                for ($i = 0; $i < count($_POST['amountfunding']); $i++) {
                    $sourcecategory = $_POST['finance'][$i];
                    $amountfunding = $_POST['amountfunding'][$i];

                    $insertSQL1 = $db->prepare("INSERT INTO `tbl_projfunding`(progid, projid, sourcecategory, amountfunding, created_by, date_created) VALUES(:progid, :projid, :sourcecategory, :amountfunding, :created_by, :date_created)");
                    $result1  = $insertSQL1->execute(array(":progid" => $progid, ":projid" => $projid, ":sourcecategory" => $sourcecategory, ":amountfunding" => $amountfunding, ":created_by" => $createdby, ":date_created" => $datecreated));
                }
            } 
            
            for ($i = 0; $i < count($_POST['outputIdsTrue']); $i++) {
                $outputid = $_POST['outputIdsTrue'][$i];
                $indicatorid = $_POST['indicatorid'][$i];
                $opyear = "output_years" . $outputid;
                $topyear = "target_year"  . $outputid;

                $insertSQL1 = $db->prepare("UPDATE `tbl_project_details` SET  projid = :projid  WHERE id=:outputid ");
                $result1  = $insertSQL1->execute(array(":projid" => $projid, ":outputid" => $outputid));
 
                $insertSQL2 = $db->prepare("UPDATE `tbl_output_disaggregation` SET  projid = :projid  WHERE outputid=:outputid ");
                $result2  = $insertSQL2->execute(array(":projid" => $projid, ":outputid" => $outputid));


                $deleteQuery = $db->prepare("DELETE FROM `tbl_project_output_details` WHERE projid=:projid AND projoutputid=:opid");
                $results = $deleteQuery->execute(array(':projid' => $projid, ":opid" => $outputid));


                for ($j = 0; $j < count($_POST[$opyear]); $j++) {
                    $target = $_POST[$topyear][$j];
                    $qyear = $_POST[$opyear][$j];
                    $insertSQL2 = $db->prepare("INSERT INTO `tbl_project_output_details`(projoutputid, progid, projid, indicator, year, target) VALUES(:outputid, :progid, :projid,:indicatorid, :qyear, :target)");
                    $result2  = $insertSQL2->execute(array(":outputid" => $outputid, ":progid" => $progid, ":projid" => $projid, ":indicatorid" => $indicatorid, ":qyear" => $qyear, ":target" => $target));
                }

                if (isset($_POST['ben_diss']) && !empty($_POST['ben_diss'])) {
                    $ben_diss = $_POST['ben_diss'][$i];
                    if ($ben_diss == 1) {
                        $deleteQuery = $db->prepare("DELETE FROM `tbl_projects_location_targets` WHERE projid=:projid AND outputid=:opid");
                        $results = $deleteQuery->execute(array(':projid' => $projid, ":opid" => $outputid));
                        $outputstate = "outputstate" . $outputid;
                        for ($j = 0; $j < count($_POST[$outputstate]); $j++) {
                            $outputstate_val = $_POST[$outputstate][$j];
                            $outputlocation = "outputlocation" . $outputstate_val . $outputid;
                            $outputlocationtarget = "outputlocationtarget"  . $outputstate_val . $outputid;
                            for ($p = 0; $p < count($_POST[$outputlocation]); $p++) {
                                $outputlocationtarget_val = $_POST[$outputlocationtarget][$p];
                                $outputlocation_val = $_POST[$outputlocation][$p];
                                $type = 3;
                                $insertSQL2 = $db->prepare("INSERT INTO `tbl_projects_location_targets`(projid, outputid, level3, locationdisid, target)VALUES(:projid, :outputid,:opstate, :outputlocation, :outputlocationtarget)");
                                $result2  = $insertSQL2->execute(array(":projid" => $projid, ":outputid" => $outputid, ":opstate" => $outputstate_val, ":outputlocation" => $outputlocation_val, ":outputlocationtarget" => $outputlocationtarget_val));
                            }
                        }
                    }
                }
            }
 
            if ($result1) {
                if (isset($_POST['report'])) {
                    $msg = 'The Project was successfully Updated.';
                    $results = "<script type=\"text/javascript\">
                        swal({
                        title: \"Success!\",
                        text: \" $msg\",
                        type: 'Success',
                        timer: 2000, 
                        showConfirmButton: false });
                        setTimeout(function(){
                            var pdfPage = window.open('add-projects-pdf?projid=$projid');
                            $(pdfPage).bind('beforeunload',function(){ 
                                window.location.href = 'view-project';
                            });  

                            }, 2000);
                    </script>";
                } else {
                    $msg = 'The Project was successfully Updated.';
                    $results = "<script type=\"text/javascript\">
                        swal({
                        title: \"Success!\",
                        text: \" $msg\",
                        type: 'Success',
                        timer: 2000, 
                        showConfirmButton: false });
                        setTimeout(function(){
                                window.location.href = 'view-project';
                            }, 2000);
                    </script>";
                }
            }
        } else {
            echo "could not enter";
        }
    }

    //get the project name 
    $query_rsProgram = $db->prepare("SELECT * FROM tbl_programs WHERE deleted='0' and progid='$progid'");
    $query_rsProgram->execute();
    $row_rsProgram = $query_rsProgram->fetch();
    $totalRows_rsProgram = $query_rsProgram->rowCount();

    $progname = $row_rsProgram['progname'];
    $syear = $row_rsProgram['syear'];
    $years = $row_rsProgram['years'];
    $kpi = $row_rsProgram['kpi'];
    $outcome = $row_rsProgram['outcome'];
    $outcomeIndicatorid = $row_rsProgram['outcomeIndicator'];

    $query_rsOutcomeIndicator = $db->prepare("SELECT DISTINCT indid, indicator_name FROM `tbl_indicator` WHERE baseline=1  AND indid ='$outcomeIndicatorid'");
    $query_rsOutcomeIndicator->execute();
    $row_rsOutcomeIndicator = $query_rsOutcomeIndicator->fetch();
    $totalRows_rsOutcomeIndicator = $query_rsOutcomeIndicator->rowCount();
    $outcomeIndicator = $row_rsOutcomeIndicator['indicator_name'];

    //get  funding 
    $query_rsFunding =  $db->prepare("SELECT * FROM tbl_myprogfunding WHERE progid ='$progid'");
    $query_rsFunding->execute();
    $row_rsFunding = $query_rsFunding->fetch();
    $totalRows_rsFunding = $query_rsFunding->rowCount();


    //get the indicators
    $query_rsOutput = $db->prepare("SELECT DISTINCT indid, indicator_name FROM `tbl_progdetails` INNER JOIN tbl_indicator ON tbl_indicator.indid = tbl_progdetails.indicator WHERE tbl_progdetails.progid ='$progid'");
    $query_rsOutput->execute();
    $row_rsOutput = $query_rsOutput->fetch();
    $totalRows_rsOutput = $query_rsOutput->rowCount();

    //get subcounty  
    $query_rsComm =  $db->prepare("SELECT id, state FROM tbl_state WHERE parent IS NULL ORDER BY state ASC");
    $query_rsComm->execute();
    $row_rsComm = $query_rsComm->fetch();
    $totalRows_rsComm = $query_rsComm->rowCount();

    //get mapping type 
    $query_rsMapType =  $db->prepare("SELECT id, type FROM tbl_map_type");
    $query_rsMapType->execute();
    $row_rsMapType = $query_rsMapType->fetch();
    $totalRows_rsMapType = $query_rsMapType->rowCount();

    //get project implementation methods 
    $query_rsProjImplMethod =  $db->prepare("SELECT DISTINCT id, method FROM tbl_project_implementation_method");
    $query_rsProjImplMethod->execute();
    $row_rsProjImplMethod = $query_rsProjImplMethod->fetch();
    $totalRows_rsProjImplMethod = $query_rsProjImplMethod->rowCount();

    // get project impact risks 
    $query_rsRiskImpactCategories =  $db->prepare("SELECT rskid, category FROM tbl_projrisk_categories where type=1");
    $query_rsRiskImpactCategories->execute();
    $totalRows_rsRiskImpactCategories = $query_rsRiskImpactCategories->rowCount();

    // get project outcome risks 
    $query_rsRiskOCCategories =  $db->prepare("SELECT rskid, category FROM tbl_projrisk_categories where type=2");
    $query_rsRiskOCCategories->execute();
    $totalRows_rsRiskOCCategories = $query_rsRiskOCCategories->rowCount();

    // get project output risks 
    $query_rsRiskOPCategories =  $db->prepare("SELECT rskid, category FROM tbl_projrisk_categories where type=3");
    $query_rsRiskOPCategories->execute();
    $totalRows_rsRiskOPCategories = $query_rsRiskOPCategories->rowCount();


    // get project impacts 
    $query_rsImpacts =  $db->prepare("SELECT impid, impact FROM tbl_impacts");
    $query_rsImpacts->execute();
    $row_rsImpacts = $query_rsImpacts->fetch();
    $totalRows_rsImpacts = $query_rsImpacts->rowCount();

    // get project impacts 
    $query_rsOutcomes =  $db->prepare("SELECT ocid, outcome FROM tbl_outcomes");
    $query_rsOutcomes->execute();
    $row_rsOutcomes = $query_rsOutcomes->fetch();
    $totalRows_rsOutcomes = $query_rsOutcomes->rowCount();

    $query_Years = $db->prepare("SELECT year FROM `tbl_progdetails` WHERE progid ='$progid' group by year ORDER BY year ASC");
    $query_Years->execute();
    $row_Years = $query_Years->fetch();
    $totalRows_Years = $query_Years->rowCount();
    $financialYear = []; 
    do {
        $year = $row_Years['year'];
        $query_Output = $db->prepare("SELECT * FROM `tbl_progdetails` WHERE progid ='$progid' and year ='$year'");
        $query_Output->execute();
        $row_Output = $query_Output->fetch();
        do {

            $progtarget = $row_Output['target'];
            $outputid = $row_Output['output'];
            $indicator = $row_Output['indicator'];

            $query_Projtarget = $db->prepare("SELECT SUM(target) as projtarget FROM `tbl_project_output_details` WHERE progid = '$progid' and year = '$year' and  indicator = '$indicator'");
            $query_Projtarget->execute();
            $row_Projtarget = $query_Projtarget->fetch();
            $projtarget = $row_Projtarget['projtarget'];

            $query_Projtarget1 = $db->prepare("SELECT target FROM `tbl_project_output_details` WHERE projid = '$projid' and year = '$year' and  indicator = '$indicator'");
            $query_Projtarget1->execute();
            $row_Projtarget1 = $query_Projtarget1->fetch();
            $projtarget1 = $row_Projtarget1['target'];
             
            $remainingTarget = $progtarget - ($projtarget - $projtarget1);
             
            if ($remainingTarget > 0) {
                $financialYear[] = $year;
            }
        } while ($row_Output = $query_Output->fetch()); //loop output 

    } while ($row_Years = $query_Years->fetch()); //loop year  

 
    $query_rsPartner =  $db->prepare("SELECT * FROM tbl_financiers WHERE active=1");
    $query_rsPartner->execute();
    $row_rsPartner = $query_rsPartner->fetch();

    $query_mainfunder =  $db->prepare("SELECT company_name FROM tbl_company_settings");
    $query_mainfunder->execute();
    $row_mainfunder = $query_mainfunder->fetch();
    $maincompany = $row_mainfunder['company_name'];

    // get project indicators 
    // $query_rsindicator =  $db->prepare("SELECT * FROM tbl_progdetails WHERE progid='$progid' GROUP BY indicator");
    // $query_rsindicator->execute();
    // $row_rsindicator = $query_rsindicator->fetch();
    // $totalRows_rsindicator = $query_rsImpacts->rowCount();
    // return; 


    
} catch (PDOException $ex) {
    $result = "An error occurred: " . $ex->getMessage();
    print($result);
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Result-Based Monitoring &amp; Evaluation System: Add Project</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!--CUSTOM MAIN STYLES-->
    <link href="css/custom.css" rel="stylesheet" />

    <!-- Bootstrap Core Css -->
    <link href="projtrac-dashboard/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="projtrac-dashboard/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="projtrac-dashboard/plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="projtrac-dashboard/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

    <!-- Bootstrap DatePicker Css -->
    <link href="projtrac-dashboard/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />

    <!--WaitMe Css-->
    <link href="projtrac-dashboard/plugins/waitme/waitMe.css" rel="stylesheet" />

    <!-- Multi Select Css -->
    <link href="projtrac-dashboard/plugins/multi-select/css/multi-select.css" rel="stylesheet">

    <!-- Bootstrap Spinner Css -->
    <link href="projtrac-dashboard/plugins/jquery-spinner/css/bootstrap-spinner.css" rel="stylesheet">

    <!-- Bootstrap Tagsinput Css -->
    <link href="projtrac-dashboard/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">

    <!-- Bootstrap Select Css -->
    <link href="projtrac-dashboard/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <!-- JQuery DataTable Css -->
    <link href="projtrac-dashboard/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

    <!-- Custom Css -->
    <link href="projtrac-dashboard/css/style.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="projtrac-dashboard/css/themes/all-themes.css" rel="stylesheet" />

    <link rel="stylesheet" href="projtrac-dashboard/ajxmenu.css" type="text/css" />
    <script src="projtrac-dashboard/ajxmenu.js" type="text/javascript"></script>
    <link href="css/left_menu.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css"> -->
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tooltipster/3.3.0/js/jquery.tooltipster.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="style.css" rel="stylesheet">
    <script src="ckeditor/ckeditor.js"></script>
    <script language='JavaScript' type='text/javascript' src='JScript/CalculatedField.js'></script>
    <link rel="stylesheet" href="css/addprojects.css">
    <style>
        @media (min-width: 1200px) {
            .modal-lg {
                width: 90%;
                height: 100%;
            }
        }
    </style>
    <script type="text/javascript">
	$(document).ready(function() {
		disable_refresh();
		$("#beneficiary").hide();
		$("#indirectbenname").hide();
		$("#indirectbeneficiary").hide();
		$("#projindirectBenfTypey").hide();
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
	// function disable refreshing functionality
	function disable_refresh() {
	  //
	  return (window.onbeforeunload = function(e) {
		return "You cannot refresh the page";
	  });
	}
	</script>
</head>

<body class="theme-blue">
    <!-- Page Loader -->
    <!-- <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div> 
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div> -->
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Top Bar -->
    <nav class="navbar" style="height:69px; padding-top:-10px">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <img src="images/logo.png" alt="logo" width="239" height="39">
            </div>
            
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <img src="images/user.png" width="48" height="48" alt="User" />
                </div>
                <?php
                include_once("includes/user-info.php");
                ?>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <?php
            include_once("includes/sidebar.php");
            ?>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 copyright">
                    ProjTrac M&E - Your Best Result-Based Monitoring & Evaluation System.
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 version" align="right">
                    Copyright @ 2017 - 2019. ProjTrac Systems Ltd.
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
    </section>

    <!-- <div class="section"> -->
    <?php
        include_once('edit-project-inner.php');
    ?>
    <!--</section> -->

    <!-- Bootstrap Core Js -->
    <script src="projtrac-dashboard/plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Multi Select Plugin Js -->
    <script src="projtrac-dashboard/plugins/multi-select/js/jquery.multi-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="projtrac-dashboard/plugins/node-waves/waves.js"></script>

    <!-- Autosize Plugin Js -->
    <script src="projtrac-dashboard/plugins/autosize/autosize.js"></script>

    <!-- Moment Plugin Js -->
    <script src="projtrac-dashboard/plugins/momentjs/moment.js"></script>

    <!-- Sparkline Chart Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-sparkline/jquery.sparkline.js"></script>

    <!-- Bootstrap Colorpicker Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>

    <!-- Input Mask Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>

    <!-- Jquery Spinner Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-spinner/js/jquery.spinner.js"></script>

    <!-- Bootstrap Tags Input Plugin Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>

    <!-- noUISlider Plugin Js -->
    <script src="projtrac-dashboard/plugins/nouislider/nouislider.js"></script>


    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>

    <!-- Bootstrap Datepicker Plugin Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

    <!-- Dropzone Plugin Js -->
    <script src="projtrac-dashboard/plugins/dropzone/dropzone.js"></script>


    <!-- <script src="projtrac-dashboard/js/admin.js"></script> -->
    <!--  <script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script> -->
    <script src="projtrac-dashboard/js/pages/forms/advanced-form-elements.js"></script>

    <!-- Demo Js -->
    <script src="projtrac-dashboard/js/demo.js"></script>

    <!-- validation cdn files  -->
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-steps/1.1.0/jquery.steps.js"></script>
</body>

</html>