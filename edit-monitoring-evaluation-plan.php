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

        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid=:projid");
        $query_rsProjects->execute(array(":projid" => $projid));
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();
        $progid = $row_rsProjects['progid'];
        $report_user =explode(",",$row_rsProjects['mne_report_users']); 
        $responsible =$row_rsProjects['mne_responsible'];
        $outcome  =$row_rsProjects['outcome'];
        $indid  =$row_rsProjects['outcome_indicator'];
 
        $query_indicator =  $db->prepare("SELECT * FROM `tbl_indicator`   WHERE indid=:indid ");
        $query_indicator->execute(array(":indid" => $indid));
        $row_indicator = $query_indicator->fetch();
        $ocunitid = $row_indicator['indicator_unit'];
        $ocindid = $row_indicator['indid'];
        $outcomeIndicator = $row_indicator['indicator_name'];
        $calcid = $row_indicator['indicator_calculation_method']; 

        $query_Indicator_cal = $db->prepare("SELECT * FROM tbl_indicator_calculation_method WHERE id =:calcid ");
        $query_Indicator_cal->execute(array(':calcid'=>$calcid));
        $row_cal = $query_Indicator_cal->fetch();
        $occalc_method = $row_cal['method'];

        $query_Indicator = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id =:unit ");
        $query_Indicator->execute(array(":unit" => $ocunitid));
        $row = $query_Indicator->fetch();
        $ocunitofmeasure = $row['unit']; 
        
        //Outcome  details 
        $query_rsOutcomeDetails =  $db->prepare("SELECT * FROM tbl_project_expected_outcome_details WHERE  projid='$projid' ");
        $query_rsOutcomeDetails->execute();
        $row_rsOutcomeDetails = $query_rsOutcomeDetails->fetch(); 
        $Outcomedata_source = $row_rsOutcomeDetails['data_source'];
        $Outcomeevaluation_frequency = $row_rsOutcomeDetails['evaluation_frequency'];
		
        //Outcome  evaluation questions 
        $query_outcomeevalqstns =  $db->prepare("SELECT * FROM tbl_project_outcome_evaluation_questions WHERE  projid='$projid'");
        $query_outcomeevalqstns->execute();	
        $count_outcomeevalqstns = $query_outcomeevalqstns->rowCount();
    }


    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addmefrm")) {
        $createdby  = $user_name;
        $datecreated  = date("Y-m-d");

        if(isset($_POST['responsible'])){
            $responsible = $_POST['responsible'];
            $outcome = $_POST['outcome'];
            $outcomeIndicator = $_POST['outcomeIndicator'];
            $report_user =implode(",",$_POST['reportUser']);
            $insertSQL1 = $db->prepare("UPDATE tbl_projects SET outcome = :outcome, outcome_indicator = :outcomeIndicator, mne_responsible = :responsible, mne_report_users=:reportUsers WHERE  projid=:projid");
            $result1  = $insertSQL1->execute(array(":outcome"=>$outcome, ":outcomeIndicator"=>$outcomeIndicator,":responsible" => $responsible, ":reportUsers"=>$report_user, ":projid" => $projid));
        }

        if (isset($_POST['outcomedataSource'])) { 
            $outcomedataSource = $_POST['outcomedataSource'];
            $outcomeEvaluationFreq = $_POST['outcomeEvaluationFreq'];  

            $type = 2;
            $deleteQuery = $db->prepare("DELETE FROM `tbl_projectrisks` WHERE projid=:projid and type=:type");
            $results = $deleteQuery->execute(array(':projid' => $projid, ':type' => $type));
  
            $insertSQL1 = $db->prepare("UPDATE `tbl_project_expected_outcome_details` SET   data_source=:data_source,evaluation_frequency=:evaluation_frequency, changed_by=:changed_by,  date_changed=:date_changed WHERE projid=:projid");
            $result1  = $insertSQL1->execute(array(":projid" => $projid, ":data_source" => $outcomedataSource,  ":evaluation_frequency" => $outcomeEvaluationFreq,":changed_by" => $createdby, ":date_changed" => $datecreated, ":projid" => $projid));

            if (isset($_POST['outcomerisk'])) {                
                for ($i = 0; $i < count($_POST['outcomerisk']); $i++) {
                    $riskid = $_POST['outcomerisk'][$i];
                    $assumption = $_POST['outcome_assumptions'][$i];
                    $insertSQL1 = $db->prepare("INSERT INTO `tbl_projectrisks`(projid, rskid, type, assumption) VALUES(:projid, :rskid, :type, :assumption)");
                    $result1  = $insertSQL1->execute(array(":projid" => $projid, ":rskid" => $riskid, ":type" => $type, ":assumption" => $assumption));
                }
            }
			
            if ($outcomedataSource == 1) {     
				$deleteQuery = $db->prepare("DELETE FROM `tbl_project_outcome_evaluation_questions` WHERE projid=:projid");
				$results = $deleteQuery->execute(array(':projid' => $projid)); 
				
                for ($j = 0; $j < count($_POST['questions']); $j++) {
                    $question = $_POST['questions'][$j];
					
                    $insertSQL1 = $db->prepare("INSERT INTO `tbl_project_outcome_evaluation_questions`(projid, question) VALUES(:projid, :question)");
                    $result1  = $insertSQL1->execute(array(":projid" => $projid, ":question" => $question));
                }
            }
        }

        if ($result1) {
            $current_date = date("Y-m-d H:i:s");
            $msg = 'The Monitoring and Evaluation was successfully Updated.';
            $results = "<script type=\"text/javascript\">
                        swal({
                        title: \"Success!\",
                        text: \" $msg\",
                        type: 'Success',
                        timer: 2000,
                        showConfirmButton: false });
                        setTimeout(function(){
                                window.location.href = 'view-mne-plan';
                            }, 2000);
                    </script>";
        }

    }
    
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
    <title>Result-Based Monitoring &amp; Evaluation System: Add Monitoring and Evaluation</title>
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
    <!-- <link href="projtrac-dashboard/plugins/multi-select/css/multi-select.css" rel="stylesheet"> -->

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
    <script src="assets/custom js/add-project-mne-plan.js"></script>
    <style>
        @media (min-width: 1200px) {
            .modal-lg {
                width: 90%;
                height: 100%;
            }
        }
    </style>
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
    include_once('edit-monitoring-evaluation-plan-inner.php');
    ?>
    <!--</section> -->
    <!-- Bootstrap Core Js -->
    <script src="projtrac-dashboard/plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Multi Select Plugin Js -->
    <!-- <script src="projtrac-dashboard/plugins/multi-select/js/jquery.multi-select.js"></script> -->

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

    <!-- Jquery DataTable Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="projtrac-dashboard/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="projtrac-dashboard/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="projtrac-dashboard/plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="projtrac-dashboard/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="projtrac-dashboard/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="projtrac-dashboard/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="projtrac-dashboard/plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="projtrac-dashboard/plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>

    <!-- Custom Js -->
    <script src="projtrac-dashboard/js/pages/tables/jquery-datatable.js"></script>
    <script src="projtrac-dashboard/js/admin2.js"></script>
    <!-- <script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script> -->
    <script src="projtrac-dashboard/js/pages/forms/basic-form-elements.js"></script>
    <script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script>
    <script src="projtrac-dashboard/js/pages/charts/jquery-knob.js"></script>

    <!-- Demo Js -->
    <script src="projtrac-dashboard/js/demo.js"></script>
</body>

</html>