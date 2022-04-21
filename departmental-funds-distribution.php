<? 
require 'authentication.php';

try{

    
 
	if(isset($_GET["fnd"]) && !empty($_GET["fnd"])){
		$hash = $_GET['fnd'];
		$decode_fndid = base64_decode($hash);
		$fndid_array = explode("fd918273AxZID", $decode_fndid);
		$fndid = $fndid_array[1];
		
		$action = "ADD";
		$fundsfrmid = "MM_insert";
		$fundsfrm = "addfundsfrm";
	
		$query_rsfunding = $db->prepare("SELECT *, s.type AS type, f.id AS fid FROM tbl_funds f inner join tbl_financiers s on s.id=f.funder WHERE f.id=:fndid");
		$query_rsfunding->execute(array(":fndid" => $fndid));
		$row_funding = $query_rsfunding->fetch();
		
		$fnid = $row_funding["fid"];
		$fundtype = $row_funding["type"];
		$fundcode = $row_funding["fund_code"];
		$financier = $row_funding["financier"];
		$finyear = $row_funding["financial_year"];
		$totalfund = $row_funding["amount"];
		$currency = $row_funding["currency"];
		$rate = $row_funding["exchange_rate"];
		$purpose = $row_funding["funds_purpose"];
		
		$query_distr =  $db->prepare("SELECT id FROM tbl_departments_allocation WHERE fundid = :fid");
		$query_distr->execute(array(":fid" => $fnid));
		$count_distr = $query_distr->rowCount();
	}
	
	/* if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addfundsfrm")) {
		
		$fundid = $_POST['fundid'];
		$fdate = strtotime($fund_date);
		$funddate = date("Y-m-d", $fdate);
		$current_date = date("Y-m-d");
		
		$query_fundyear = $db->prepare("SELECT financial_year FROM tbl_funds WHERE id=:fnd");
		$query_fundyear->execute(array(":fnd" => $fundid));
		$row_fundyear = $query_fundyear->fetch();
		$fnyear = $row_fundyear['financial_year'];
							
		$count = count($_POST['sector']);
			
		for($cnt=0; $cnt<$count; $cnt++)
		{ 
			//Check that we have a file
			$sector = $_POST['sector'][$cnt];
			$allocation = $_POST['allocation'][$cnt];
		
			$insertSQL = $db->prepare("INSERT INTO tbl_departments_allocation (fundid, department, allocation, financialyear, created_by, datecreated) VALUES (:fundid, :sector, :allocation, :financialyear, :createdby, :datecreated)");
			$result = $insertSQL->execute(array(':funder' => $fundid, ':sector' => $sector, ':allocation' => $allocation, ':financialyear' => $fnyear, ':createdby' => $_POST['user_name'], ':datecreated' => $current_date));
		
			if($result){				
				$msg = 'Departmental allocations successfully added.';
				$results = "<script type=\"text/javascript\">
					swal({
						title: \"Success!\",
						text: \" $msg\",
						type: 'Success',
						timer: 2000,
						showConfirmButton: false });
					setTimeout(function(){
						window.location.href = 'add-development-funds';
					}, 2000);
				</script>";
			}
		}
	}
	elseif ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editfundsfrm")) {
		$grantlifespan = $grantinstallments = $grantinstallmentdate = NULL;
		$funderid = $_POST['funderid'];
		$fund_date = $_POST['fundsdate'];
		$fdate = strtotime($fund_date);
		$funddate = date("Y-m-d", $fdate);
		$current_date = date("Y-m-d");
		$grantlifespan .= $_POST['grantlifespan'];
		$grantinstallments .= $_POST['grantinstallments'];
		$grantinstallmentdate .= $_POST['grantinstallmentdate'];
		

		if(!empty($_POST['code']) && !empty($_POST['year']) && !empty($_POST['amount']) && !empty($funddate) && !empty($_POST['user_name'])){
			$insertSQL = $db->prepare("UPDATE tbl_funds SET funder=:funder, fund_code=:code, financial_year=:year, amount=:amount, currency=:currency, exchange_rate=:rate, date_funds_released=:funddate, funds_purpose=:purpose, grant_life_span=:lifespan, grant_installments=:installments, grant_installment_date=:installmentsdate, updated_by=:recordedby, date_updated=:recorddate WHERE id=:funderid");
			$result = $insertSQL->execute(array(':funder' => $_POST['financier'], ':code' => $_POST['code'], ':year' => $_POST['year'], ':amount' => $_POST['amount'], ':currency' => $_POST['currency'], ':rate' => $_POST['rate'], ':funddate' => $funddate, ':purpose' => $_POST['purpose'], ':lifespan' => $grantlifespan, ':installments' => $grantinstallments, ':installmentsdate' => $grantinstallmentdate, ':recordedby' => $_POST['user_name'], ':recorddate' => $current_date, ':funderid' => $funderid));
			
			if($result){				
				$msg = 'Departmental allocations updated.';
				$results = "<script type=\"text/javascript\">
					swal({
						title: \"Success!\",
						text: \" $msg\",
						type: 'Success',
						timer: 2000,
						showConfirmButton: false });
					setTimeout(function(){
						window.location.href = 'funding';
					}, 2000);
				</script>";
			}
		}
	} */
  
	
	
} catch (PDOException $ex) {
    // $result = flashMessage("An error occurred: " .$ex->getMessage());
    print($ex->getMessage());
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
            var projid = $("#projid").val();
            disable_refresh();
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
    </script>
</head>

<body class="theme-blue">
    <!-- Page Loader -->
    <!-- <div class="page-loader-wrapper"> -->
    <!-- <div class="loader">
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
    <section class="content" style="margin-top:-20px; padding-bottom:0px">
        <div class="container-fluid">
            <?php
            include_once('departmental-funds-distribution-inner.php');
            ?>
        </div>
    </section>
    <!--</section> -->
  
    <!-- Jquery Core Js -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

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
    <script src="projtrac-dashboard/js/admin2.js"></script>
    <script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script>
    <script src="projtrac-dashboard/js/pages/charts/jquery-knob.js"></script>
	
    <!-- Demo Js -->
    <script src="projtrac-dashboard/js/demo.js"></script>
</body>

</html>