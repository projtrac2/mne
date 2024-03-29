<?php 
$results  ='';
require 'authentication.php';

try {
     

    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "assignofficerform")) {
        $officer = $_POST["officer"];
        $inspection_date = $_POST["inspection_date"];
        $projid = $_POST["projid"];
        $outputid = $_POST["outputid"];
        $level3 = $_POST["level3"];
        $level4 = $_POST["level4"];
        $comments = $_POST["comments"];
        $formid = $_POST["formid"];
        $created_by = 3;
        $created_at = date('Y-m-d');

        $insertSQL1 = $db->prepare("UPDATE `tbl_inspection_assignment` SET projid=:projid, outputid=:outputid, level3=:level3, level4=:level4, officer=:officer, inspection_date=:inspection_date, updated_by=:created_by, update_reason=:comments,updated_at=:created_at WHERE id=:formid");
        $result    = $insertSQL1->execute(array(":projid" => $projid, ":outputid" => $outputid, ":level3" => $level3, ":level4" => $level4, ":officer"=>$officer,':inspection_date'=>$inspection_date,  ":created_by"=>$created_by, ":comments"=>$comments, ":created_at"=>$created_at, ":formid" =>$formid));

        if ($result) {
            $msg = 'Reassignment was successful.';
            $results = "
            <script type=\"text/javascript\">
                swal({
                title: \"Success!\",
                text: \" $msg\",
                type: 'Success',
                timer: 2000, 
                showConfirmButton: false });
                setTimeout(function(){
                        window.location.href = 'project-inspections-list'; 
                }, 2000);
            </script>";
        } else {
            $msg = 'Error in reassignment.';
            $results = "<script type=\"text/javascript\">
                swal({
                title: \"Error!\",
                text: \" $msg\",
                type: 'error',
                timer: 2000, 
                showConfirmButton: false });
                setTimeout(function(){
                        window.location.href = 'project-inspections-list';
                    }, 2000);
            </script>";
        }
    }

    if(isset($_GET['formid'])){
        $formid = $_GET['formid'];
        $query_rsassignment = $db->prepare("SELECT * FROM `tbl_inspection_assignment` WHERE id = $formid ");
        $query_rsassignment->execute();
        $row_rsassignment = $query_rsassignment->fetch();
        $count_rsassignment = $query_rsassignment->rowCount();
        $projid = $row_rsassignment['projid'];
        $outputid = $row_rsassignment['outputid'];
        $level3 = $row_rsassignment['level3'];
        $level4 = $row_rsassignment['level4'];
        $officer = $row_rsassignment['officer'];
        $inspection_date = $row_rsassignment['inspection_date'];
        $comments = $row_rsassignment['comments'];
    }

    $query_rsTeam = $db->prepare("SELECT ptid, fullname FROM `tbl_projteam2` WHERE availability = 1 ");
    $query_rsTeam->execute();
    $row_rsTeam = $query_rsTeam->fetch();
    $count_rsTeam = $query_rsTeam->rowCount();

    // get incomplete tasks
    $query_rsProjects = $db->prepare("SELECT p.projname, p.projid FROM `tbl_project_monitoring_checklist_score` s INNER join tbl_task t ON t.tkid = s.taskid INNER join tbl_projects p ON p.projid = t.projid WHERE s.score = 10 AND p.projinspection = 1 AND (p.projstatus = 4 OR p.projstatus = 11)GROUP BY t.projid");
    $query_rsProjects->execute();
    $row_rsProjects = $query_rsProjects->fetch();
    $count_rsProjects = $query_rsProjects->rowCount();
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    print($result);
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Result-Based Monitoring &amp; Evaluation System: Contractor Information</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- Bootstrap Core Css -->
    <link href="projtrac-dashboard/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="projtrac-dashboard/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="projtrac-dashboard/plugins/animate-css/animate.css" rel="stylesheet" />

    <!--WaitMe Css-->
    <link href="projtrac-dashboard/plugins/waitme/waitMe.css" rel="stylesheet" />

    <!-- Multi Select Css -->
    <link href="projtrac-dashboard/plugins/multi-select/css/multi-select.css" rel="stylesheet">

    <!-- Bootstrap Spinner Css -->
    <link href="projtrac-dashboard/plugins/jquery-spinner/css/bootstrap-spinner.css" rel="stylesheet">

    <!-- Bootstrap Tagsinput Css -->
    <link href="projtrac-dashboard/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">

    <!-- Bootstrap Select Css -->
    <!-- <link href="projtrac-dashboard/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" /> -->

    <!-- JQuery DataTable Css -->
    <link href="projtrac-dashboard/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

    <!-- Sweet Alert Css -->
    <link href="projtrac-dashboard/plugins/sweetalert/sweetalert.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="projtrac-dashboard/css/style.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="projtrac-dashboard/css/themes/all-themes.css" rel="stylesheet" />

    <link rel="stylesheet" href="projtrac-dashboard/ajxmenu.css" type="text/css" />
    <script src="projtrac-dashboard/ajxmenu.js" type="text/javascript"></script>

    <link href="css/left_menu.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="assets/ckeditor/ckeditor.js"></script>
    <style>
        #links a {
            color: #FFFFFF;
            text-decoration: none;
        }

        hr {
            display: block;
            margin-top: 0.5em;
            margin-bottom: 0.5em;
            margin-left: auto;
            margin-right: auto;
            border-style: inset;
            border-width: 1px;
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
            <?php
            include_once("allnotifications.php");
            ?>
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

    <!-- Modal Receive Payment -->
    <div class="modal fade" id="assignModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title" align="center">
                        <font color="#FFF">Milestone Inspection Assignment</font>
                    </h3>
                </div>
                <form class="tagForm" action="inspectorassignment" method="post" id="assign-inspection-form" enctype="multipart/form-data" autocomplete="off">
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="card">
                                    <div class="body">
                                        <div class="table-responsive" style="background:#eaf0f9">
                                            <div id="checklistassignment">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4" align="center">
                            <input name="save" type="submit" class="btn btn-success waves-effect waves-light" id="tag-form-submit" value="Save" />
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                            <input type="hidden" name="username" id="username" value="<?php echo $user_name; ?>" />
                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- #END# Modal Receive Payment-->

    <section class="content" style="margin-top:-20px; padding-bottom:0px">
        <div class="container-fluid">
            <div class="block-header" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; background-color:#000; color:#FFF">
                <h4 class="contentheader"><i class="fa fa-list-ol" aria-hidden="true"></i> Project Milestones Inspection
                </h4>
            </div>
            <!-- Draggable Handles -->
            <div class="row clearfix">
                <div class="block-header">
                    <?php
                    echo $results;
                    ?>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <!-- <div class="body"> -->
                        <?php
                            include_once('reassign-officer-inner.php');
                        ?>
                        <!--</div> -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="assets/custom js/task-inspection.js"></script>
    <!-- Jquery Core Js -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="projtrac-dashboard/plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <!-- <script src="projtrac-dashboard/plugins/bootstrap-select/js/bootstrap-select.js"></script> -->

    <!-- Multi Select Plugin Js -->
    <script src="projtrac-dashboard/plugins/multi-select/js/jquery.multi-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="projtrac-dashboard/plugins/node-waves/waves.js"></script>

    <!-- Sweet Alert Plugin Js -->
    <script src="projtrac-dashboard/plugins/sweetalert/sweetalert.min.js"></script>

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

    <!-- Jquery Knob Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-knob/jquery.knob.min.js"></script>

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
    <script src="projtrac-dashboard/js/admin.js"></script>
    <script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script>
    <script src="projtrac-dashboard/js/pages/charts/jquery-knob.js"></script>

    <!-- Demo Js -->
    <script src="projtrac-dashboard/js/demo.js"></script>
</body>

</html>