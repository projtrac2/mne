<?php 
//include_once 'projtrac-dashboard/resource/session.php';

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

if (!isset($_SESSION)) {
  session_start();
}

require 'authentication.php';

try{	
		
	
	
	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
	  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}

	$username = $user_name;

	if (isset($_GET['plan'])) {
		$stplan = $_GET['plan'];
	}
		
	if ((isset($_GET["action"])) && $_GET["action"] == "edit") {
		$tmid = $_GET['tmgy'];
		
		$query_terminologies =  $db->prepare("SELECT * FROM tbl_terminologies WHERE id='$tmid'");
		$query_terminologies->execute();		
		$row_terminology = $query_terminologies->fetch();
		$cat = $row_terminology['category'];
		$name = $row_terminology['name'];
		$label = $row_terminology['label'];
		$labelplural = $row_terminology['label_plural'];

		$action = "Edit";
		$submitAction = "MM_update";
		$formName = "editterminology";
		$submitValue = "Update";
		if (isset($_POST["MM_update"]) && $_POST["MM_update"] == "editterminology"){
			$tmgyid=$_POST['tmgy'];
			$category=$_POST['category'];
			$tmgyname=$_POST['name'];
			$tmgylabel=$_POST['label'];
			$tmgylabelplural=$_POST['label-plural'];
			
			//if($milestonestatus == "Pending" || $milestonestatus == "Approved") {
			if(!empty($tmgyid) && !empty($category) && !empty($tmgyname) && !empty($tmgylabel) && !empty($tmgylabelplural)) {
			
				$updateQuery = $db->prepare("Update tbl_terminologies SET category=:category, name=:name, label=:label, label_plural=:labelp WHERE id=:tmgyid");
								//add the data into the database										  
				$updresult = $updateQuery->execute(array(':category' => $category, ':name' => $tmgyname, ':label' => $tmgylabel, ':labelp' => $tmgylabelplural, ':tmgyid' => $tmgyid));	
						
				if(!$updresult )
				{
					$msg = 'Terminology was not updated.';
					$result = "<script type=\"text/javascript\">
									swal({
									title: \"Error!\",
									text: \" $msg \",
									icon: 'warning',
									dangerMode: true,
									timer: 6000,
									showConfirmButton: false });
									setTimeout(function(){
											window.location.href = 'system-terminologies?action=edit&tmgy=$tmgyid';
										}, 6000);
								</script>";
				}
				else{
					$msg = 'Terminology successfully updated.';
					$result = "<script type=\"text/javascript\">
						swal({
							title: \"Success!\",
							text: \" $msg\",
							type: 'Success',
							timer: 3000,
							showConfirmButton: false });
						setTimeout(function(){
							window.location.href = 'system-terminologies';
							}, 3000);
					 </script>";
				}
			}
			else{
				$msg = "Please fill all required fields";
				$result = "<script type=\"text/javascript\">
					swal({
						title: \"Success!\",
						text: \" $msg\",
						dangerMode: true,
						timer: 3000,
						showConfirmButton: false });
					setTimeout(function(){
						window.location.href = 'system-terminologies?action=edit&tmgy=$tmgyid';
						}, 3000);
				</script>";
			}
		}
	}
	elseif((isset($_GET["action"])) && $_GET["action"] == "del"){
		$tmid = $_GET['tmgy'];
		$result ="<script type=\"text/javascript\">
			swal({
			  title: \"Are you sure??\",
			  text: \"Once deleted, you will not be able to recover this record!\",
			  icon: 'warning',
			  buttons: true,
			  dangerMode: true,
			})
			.then((willDelete) => {
			  if (willDelete) {
				swal({
				 url: window.location.href = 'delterminology?id=$tmid',
				});
			  } else {
				swal(\"Your terminology is safe!\");
			  }
			});
		</script>";
	}
	else{
		$action = "Add";
		$submitAction = "MM_insert";
		$formName = "addterminology";
		$submitValue = "Submit";
		
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addterminology")) {
			//if ($projstatus == "Pending" || $projstatus == "Unapproved") {
			if(!empty($_POST['category']) && !empty($_POST['name']) && !empty($_POST['label']) && !empty($_POST['label-plural'])){
					
				$cat = $_POST['category'];
				$name = $_POST['name'];
				$label = $_POST['label'];
				$labelplural = $_POST['label-plural'];
				
				$insertSQL = $db->prepare("INSERT INTO tbl_terminologies (category, name, label, label_plural) VALUES (:cat, :name, :label, :labelp)");
							
				$Result1 = $insertSQL->execute(array(':cat' => $cat, ':name' => $name, ':label' => $label, ':labelp' => $labelplural));
				if($Result1){
					$msg = 'Terminology successfully added.';
					$result = "<script type=\"text/javascript\">
									swal({
									title: \"Success!\",
									text: \" $msg\",
									type: 'Success',
									timer: 3000,
									showConfirmButton: false });
									setTimeout(function(){
											window.location.href = 'system-terminologies';
										}, 3000);
								  </script>";
				}
				else{
					$msg = "Sorry the terminology  was not added!!";
					$result = "<script type=\"text/javascript\">
							swal({
							title: \"Failed!!!\",
							text: \" $msg\",
							icon: 'warning',
							dangerMode: true,
							timer: 3000,
							showConfirmButton: false });
						</script>";
				}
			}
			else{
				$msg = 'Please fill all required fields!!';
						$result = "<script type=\"text/javascript\">
						swal({
						title: \"Error!!\",
						text: \" $msg \",
						icon: 'warning',
						dangerMode: true,
						timer: 6000,
						showConfirmButton: false });
					</script>";
			}
		}
	}

	$query_terminologies =  $db->prepare("SELECT * FROM tbl_terminologies order by category asc");
	$query_terminologies->execute();		
	$rows_terminologies = $query_terminologies->rowCount();

}catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $result;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Projtrac M&E - Project Logframe</title>
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

    <!-- Sweet Alert Css -->
    <link href="projtrac-dashboard/plugins/sweetalert/sweetalert.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="projtrac-dashboard/css/style.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="projtrac-dashboard/css/themes/all-themes.css" rel="stylesheet" />
	
	<!-- InstanceBeginEditable name="head" -->
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.tabs .tab-links a').on('click', function(e)  {
			var currentAttrValue = jQuery(this).attr('href');
	 
			// Show/Hide Tabs
			jQuery('.tabs ' + currentAttrValue).show().siblings().hide();
	 
			// Change/remove current tab to active
			jQuery(this).parent('li').addClass('active').siblings().removeClass('active');
	 
			e.preventDefault();
		});
	});
	</script>

	<script type="text/javascript" >
	$(document).ready(function()
	{
	$(".account").click(function()
	{
	var X=$(this).attr('id');

	if(X==1)
	{
	$(".submenus").hide();
	$(this).attr('id', '0');	
	}
	else
	{

	$(".submenus").show();
	$(this).attr('id', '1');
	}
		
	});

	//Mouseup textarea false
	$(".submenus").mouseup(function()
	{
	return false
	});
	$(".account").mouseup(function()
	{
	return false
	});


	//Textarea without editing.
	$(document).mouseup(function()
	{
	$(".submenus").hide();
	$(".account").attr('id', '');
	});
		
	});</script>
	<link rel="stylesheet" href="projtrac-dashboard/ajxmenu.css" type="text/css" />
	<script src="projtrac-dashboard/ajxmenu.js" type="text/javascript"></script>

    <link href="css/left_menu.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">	
	<style>
	#links a {
		color: #FFFFFF;
		text-decoration: none;
	}  
	</style>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#projchange').on('change',function(){
				var statusID = $(this).val();
				var projID = $("#projid").val();
				var projOrigID = $("#projorigstatus").val();
					$.ajax({
						type: 'POST',
						url: 'status_notes.php',
						//data: {'members_id': memberID},
						data: "status_id="+statusID+"&proj_id="+projID+"&projOrig_id="+projOrigID,
						success: function (data) {
						  $('#formcontent').html(data);
						  $("#myModal").modal({backdrop: "static"});
						}
				
			   
				});
		});
		});
	</script>
</head>

<body class="theme-blue">
	<!-- Modal -->
	  <div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog modal-lg span5">
		  <div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title"><font color="#000000">PROJECT STATUS CHANGE REASON(S)</font></h4>
			</div>
			<div class="modal-body" id="formcontent">
			
			</div>
		  </div>
		</div>
	  </div>
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
	<?php
		$pid = $row_rsMyP['projid'];
		$query_rsSDate =  $db->prepare("SELECT projstartdate FROM tbl_projects where projid='$pid'");
		$query_rsSDate->execute();		
		$row_rsSDate = $query_rsSDate->fetch();
				
		$projstartdate = $row_rsSDate["projstartdate"];
		//$start_date = date_format($projstartdate, "Y-m-d");
		$current_date = date("Y-m-d");
				
		$tndprojid = $row_rsMyP['projid'];
		$query_rsTender =  $db->prepare("SELECT * FROM tbl_tenderdetails where projid='$tndprojid'");
		$query_rsTender->execute();		
		$row_rsTender = $query_rsTender->fetch();
		$totalRows_rsTender = $query_rsTender->rowCount();
	?>
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars ->
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
	<!-- Inner Section -->
		<?php  include_once('system-terminologies-inner.php');?>
    <!-- #END# Inner Section -->
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

    <!-- Autosize Plugin Js -->
    <script src="projtrac-dashboard/plugins/autosize/autosize.js"></script>

    <!-- Sweet Alert Plugin Js -->
    <script src="projtrac-dashboard/plugins/sweetalert/sweetalert.min.js"></script>

    <!-- Moment Plugin Js -->
    <script src="projtrac-dashboard/plugins/momentjs/moment.js"></script>

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
    <script src="projtrac-dashboard/js/pages/forms/basic-form-elements.js"></script>
    <script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script>
	
</body>

</html>