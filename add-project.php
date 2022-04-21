<?php
include_once 'includes/head-alt.php';

$crud_permissions = $role_group != 2 ? true : false;

if ($crud_permissions) {
    $msg = 'You have no rights to access this page.';
    $results =
        "<script type=\"text/javascript\">
        swal({
        title: \"Permission Denied!\",
        text: \" $msg\",
        type: 'Error',
        timer: 5000,
        icon:'error',
        showConfirmButton: false });
        setTimeout(function(){
            window.history.back();
        }, 5000);
    </script>";
}

function generate_key($str_length)
{
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < $str_length; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}


try {

    $results = "";
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }


    $key_unique = time() . generate_key(6);

    if (isset($_GET['progid'])) {
        $progid = $_GET['progid'];
    }

    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addprojectfrm")) {
        //upload random name/number
        $projname = $_POST['projname'];
        $progid = $_POST['progid'];
        $projcode = $_POST['projcode'];
        $projbudget = 0;
        $projimplmethod = $_POST['projimplmethod'];
        $bigfour = $_POST['bigfour'];
        $projfscyear = $_POST['projfscyear1'];
        $projduration = $_POST['projduration1'];
        $projmapping = $_POST['projmapping'];
        $projinspection = $_POST['projinspection'];
        $projevaluation = $_POST['projevaluation'];
        $projlevel1 = implode(",", $_POST['projcommunity']);
        $projlevel2 = implode(",", $_POST['projlga']);
        $projlevel3 = implode(",", $_POST['projstate']);
        $projstartdate = $_POST['projectStartingYear'] . "-07-01";
        $projenddate = $_POST['projendyearDate'];
        $projtype = "New";
        $datecreated = date("Y-m-d");
        $createdby = $user_name;
        $projapprovestatus = 0;

        $insertSQL = $db->prepare("INSERT INTO `tbl_projects`(progid, projcode, projname, projtype, projbudget, projfscyear, projduration, projmapping, projinspection,projevaluation, projcommunity, projlga, projstate, projcategory, projbigfouragenda, projstartdate, projenddate, user_name, date_created) VALUES(:progid, :projcode, :projname, :projtype, :projbudget, :projfscyear, :projduration, :projmapping, :projinspection,:projevaluation,  :projlevel1, :projlevel2, :projlevel3, :projimplmethod, :bigfour, :projstartdate, :projenddate, :createdby, :datecreated)");
        //add the data into the database
        $result  = $insertSQL->execute(array(":progid" => $progid, ":projcode" => $projcode, ":projname" => $projname, ":projtype" => $projtype, ":projbudget" => $projbudget, ":projfscyear" => $projfscyear, ":projduration" => $projduration, ":projmapping" => $projmapping, ":projinspection" => $projinspection, ":projevaluation" => $projevaluation, ":projlevel1" => $projlevel1, ":projlevel2" => $projlevel2, ":projlevel3" => $projlevel3,  ":projimplmethod" => $projimplmethod, ":bigfour" => $bigfour, ":projstartdate" => $projstartdate, ":projenddate" => $projenddate, ":createdby" => $createdby, ":datecreated" => $datecreated));

        if ($result) {
            $last_id = $db->lastInsertId(); // get the project id   
            // add attachment files  
            $catid = $last_id;
            if (isset($_POST['attachmentpurpose'])) {
                $countP = count($_POST["attachmentpurpose"]);
                $stage = 1;
                for ($cnt = 0; $cnt < $countP; $cnt++) {
                    if (!empty($_FILES['pfiles']['name'][$cnt])) {
                        $purpose = $_POST["attachmentpurpose"][$cnt];
                        $filename = basename($_FILES['pfiles']['name'][$cnt]);
                        $catid = $catid + 1;
                        $ext = substr($filename, strrpos($filename, '.') + 1);
                        if (($ext != "exe") && ($_FILES["pfiles"]["type"][$cnt] != "application/x-msdownload")) {
                            $newname = $last_id . "_" . $stage . "_" . $filename;
                            $filepath = "uploads/main-project/" . $newname;
                            if (!file_exists($filepath)) {
                                if (move_uploaded_file($_FILES['pfiles']['tmp_name'][$cnt], $filepath)) {
                                    $fname = $newname;
                                    $mt = $filepath;
                                    $filecategory = "Project Planning";
                                    $qry1 = $db->prepare("INSERT INTO tbl_files (projid, projstage, filename, ftype, floc, fcategory, reason, uploaded_by, date_uploaded)
                                     VALUES (:projid, :stage, :filename, :ftype, :floc,:fcategory,:reason,:uploaded_by, :date_uploaded)");
                                    $qry1->execute(array(
                                        ":projid" => $last_id, ":stage" => $stage, ":filename" => $filename, ":ftype" => $ext, ":floc" => $mt, ":fcategory" => $filecategory, ":reason" => $purpose, ":uploaded_by" => $createdby, ":date_uploaded" => $datecreated
                                    ));
                                } else {
                                    echo "file culd not be  allowed";
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

            // add implementors 
            if (isset($_POST['projleadimplementor'])) {
                $projleadimplementor = $_POST['projleadimplementor'];
                $projimplementingpartner = implode(",", $_POST['projimplementingpartner']);
                $insertSQL1 = $db->prepare("INSERT INTO `tbl_myprojpartner`(projid, lead_implementer, implementing_partner)  VALUES(:projid, :lead_implementer, :implementing_partner)");
                $result1  = $insertSQL1->execute(array(":projid" => $last_id, ":lead_implementer" => $projleadimplementor, ":implementing_partner" => $projimplementingpartner));
            }

            // project funding 
            for ($i = 0; $i < count($_POST['amountfunding']); $i++) {
                $sourcecatergory = $_POST['finance'][$i];
                $amountfunding = $_POST['amountfunding'][$i];
                $insertSQL1 = $db->prepare("INSERT INTO `tbl_projfunding`(progid, projid, sourcecategory, amountfunding, created_by, date_created) VALUES(:progid, :projid, :sourcecatergory, :amountfunding, :created_by, :date_created)");
                $result1  = $insertSQL1->execute(array(":progid" => $progid, ":projid" => $last_id, ":sourcecatergory" => $sourcecatergory, ":amountfunding" => $amountfunding, ":created_by" => $createdby, ":date_created" => $datecreated));
            }

            for ($i = 0; $i < count($_POST['outputIdsTrue']); $i++) {
                $outputid = $_POST['outputIdsTrue'][$i];
                $indicatorid = $_POST['indicatorid'][$i];
                $opyear = "output_years" . $outputid;
                $topyear = "target_year"  . $outputid;

                $insertSQL1 = $db->prepare("UPDATE `tbl_project_details` SET  projid = :projid  WHERE id=:outputid ");
                $result1  = $insertSQL1->execute(array(":projid" => $last_id, ":outputid" => $outputid));

                $updateSQL2 = $db->prepare("UPDATE `tbl_output_disaggregation` SET  projid = :projid  WHERE outputid=:outputid ");
                $upresult2  = $updateSQL2->execute(array(":projid" => $last_id, ":outputid" => $outputid));

                for ($j = 0; $j < count($_POST[$opyear]); $j++) {
                    $target = $_POST[$topyear][$j];
                    $qyear = $_POST[$opyear][$j];
                    $insertSQL2 = $db->prepare("INSERT INTO `tbl_project_output_details`(projoutputid, progid, projid, indicator, year, target) VALUES(:outputid, :progid, :projid,:indicatorid, :qyear, :target)");
                    $result2  = $insertSQL2->execute(array(":outputid" => $outputid, ":progid" => $progid, ":projid" => $last_id, ":indicatorid" => $indicatorid, ":qyear" => $qyear, ":target" => $target));
                }

                if (isset($_POST['ben_diss']) && !empty($_POST['ben_diss'])) {
                    $ben_diss = $_POST['ben_diss'][$i];

                    if ($ben_diss == 1) {
                        $outputstate = "outputstate" . $outputid;
                        for ($j = 0; $j < count($_POST[$outputstate]); $j++) {
                            $outputstate_val = $_POST[$outputstate][$j];
                            $outputlocation = "outputlocation" . $outputstate_val . $outputid;
                            $outputlocationtarget = "outputlocationtarget"  . $outputstate_val . $outputid;
                            for ($p = 0; $p < count($_POST[$outputlocation]); $p++) {
                                $outputlocationtarget_val = $_POST[$outputlocationtarget][$p];
                                $outputlocation_val = $_POST[$outputlocation][$p];
                                $type = 3;
                                $insertSQL2 = $db->prepare("INSERT INTO `tbl_project_results_level_disaggregation`(projid, projoutputid,opstate, name, value, type) VALUES(:projid, :outputid,:opstate, :outputlocation, :outputlocationtarget, :type)");
                                $result2  = $insertSQL2->execute(array(":projid" => $last_id, ":outputid" => $outputid, ":opstate" => $outputstate_val, ":outputlocation" => $outputlocation_val, ":outputlocationtarget" => $outputlocationtarget_val, ":type" => $type));
                            }
                        }
                    }
                }
            }


            $query_rsProject_details = $db->prepare("SELECT SUM(budget) as budget FROM `tbl_project_details` WHERE projid ='$last_id'");
            $query_rsProject_details->execute();
            $row_rsProject_details = $query_rsProject_details->fetch();
            $totalRows_rsProject_details = $query_rsProject_details->rowCount();
            $projcost = ($totalRows_rsProject_details > 0) ? $row_rsProject_details['budget'] : 0;

            $approveItemQuery = $db->prepare("UPDATE `tbl_projects` SET projcost=:projcost WHERE projid=:projid");
            $approveItemQuery->execute(array(":projcost" => $projcost, ':projid' => $last_id));

            if ($result1) {
                if (isset($_POST['report'])) {
                    $results = "<script type=\"text/javascript\">
                    </script>";

                    $msg = 'The Project was successfully added.';
                    $results = "
                    <script type=\"text/javascript\">
                        swal({
                        title: \"Success!\",
                        text: \" $msg\",
                        type: 'Success',
                        timer: 2000, 
                        showConfirmButton: false });
                        setTimeout(function(){ 
                            var pdfPage = window.open('add-projects-pdf?projid=$last_id');
                            $(pdfPage).bind('beforeunload',function(){ 
                                window.location.href = 'add-project?progid=$progid';
                            });  
                        }, 2000);
                    </script>";
                } else {
                    $msg = 'The Project was successfully added.';
                    $results = "<script type=\"text/javascript\">
                        swal({
                        title: \"Success!\",
                        text: \" $msg\",
                        type: 'Success',
                        timer: 2000, 
                        showConfirmButton: false });
                        setTimeout(function(){
                                window.location.href = 'add-project?progid=$progid';
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

    //get  funding 
    $query_rsFunding =  $db->prepare("SELECT * FROM tbl_myprogfunding WHERE progid ='$progid'");
    $query_rsFunding->execute();
    $row_rsFunding = $query_rsFunding->fetch();
    $totalRows_rsFunding = $query_rsFunding->rowCount();

    //get subcounty  
    $query_rsComm =  $db->prepare("SELECT id, state FROM tbl_state WHERE parent IS NULL ORDER BY id ASC");
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

    $query_rsPartner =  $db->prepare("SELECT * FROM tbl_financiers WHERE active=1");
    $query_rsPartner->execute();
    $row_rsPartner = $query_rsPartner->fetch();

    $query_mainfunder =  $db->prepare("SELECT company_name FROM tbl_company_settings");
    $query_mainfunder->execute();
    $row_mainfunder = $query_mainfunder->fetch();
    $maincompany = $row_mainfunder['company_name'];

    $query_Years = $db->prepare("SELECT DISTINCT year FROM `tbl_progdetails` WHERE progid ='$progid' ORDER BY year ASC");
    $query_Years->execute();
    $row_Years = $query_Years->fetch();
    $totalRows_Years = $query_Years->rowCount();
    $financialYear = [];

    if ($totalRows_Years > 0) {
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
                $remainingTarget = $progtarget - $projtarget;

                if ($remainingTarget > 0) {
                    $financialYear[] = $year;
                }
            } while ($row_Output = $query_Output->fetch()); //loop output 

        } while ($row_Years = $query_Years->fetch()); //loop year 
    }
} catch (PDOException $ex) {
    $results = "An error occurred: " . $ex->getMessage();
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
    <!-- Page Loader --
    <div class="page-loader-wrapper">
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
    </div>
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
            echo $results;
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
    if (!$crud_permissions) {
        include_once('add-project-inner.php');
    }
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