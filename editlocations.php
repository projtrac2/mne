<?php 

require 'authentication.php';

try {
	

	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
	  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}

	if ((isset($_GET["edit"])) && ($_GET["edit"] == "1")) {
		if (isset($_GET['stid'])) {
		  $stid = $_GET['stid'];
		}	
		
		$query_stEdit = $db->prepare("SELECT * FROM tbl_state WHERE id='$stid'");
		$query_stEdit->execute();
		$row_rsSTEdit = $query_stEdit->fetch();
		$totalRows_rsSTEdit = $query_stEdit->rowCount();
		  
		$locname = $row_rsSTEdit['state'];

		$action = "Edit";
		$submitAction = "MM_update";
		$formName = "editlocfrm";
		$submitValue = "Update";
		if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editlocfrm")) {
			$state=$_POST['location'];
			$username=$_POST['user_name'];
			$changedon = date("Y-m-d H:i:s");
			
			$SQLUpdate = $db->prepare("Update tbl_state set state='$state',changed_by='$username',date_changed='$changedon' WHERE id='$stid'");
			$updresult = $SQLUpdate->execute();
				
			if ($updresult) {
				$msg = 'Location successfully updated.';
				$results = "<script type=\"text/javascript\">
					swal({
					title: \"Success!\",
					text: \" $msg\",
					type: 'Success',
					timer: 5000, 
					showConfirmButton: false });
					setTimeout(function(){
							window.location.href = 'locations.php';
						}, 5000);
				</script>";
			}else{
				$type = 'error';
				$msg = 'Error updating the location, kindly try again!!';

				$results = "<script type=\"text/javascript\">
					swal({
					title: \"Error!\",
					text: \" $msg \",
					type: 'Danger',
					timer: 5000,
					showConfirmButton: false });
				</script>";
			}
		}
	}
 

	$query_rsState = $db->prepare("SELECT id,state FROM tbl_state WHERE location='0' and parent IS NULL");
	$query_rsState->execute();
	$rsState = $query_rsState->fetch();
	$row_rsState = $query_rsState->rowCount();

	$query_rsAllLocations = $db->prepare("SELECT * FROM tbl_state WHERE location='0' and parent IS NULL and id<>'1' ORDER BY state ASC");
	$query_rsAllLocations->execute();
	$row_rsAllLocations = $query_rsAllLocations->fetch();

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
    include_once('editlocations-inner.php');
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