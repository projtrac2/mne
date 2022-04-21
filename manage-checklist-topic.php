<?php 

require 'authentication.php';

try{

	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
	  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}
	
	if (isset($_GET['msg']) && !empty($_GET['msg'])) {
		$msg = $_GET['msg'];
		$results = "<script type=\"text/javascript\">
			swal({
				title: \"Success!\",
				text: \" $msg\",
				type: 'Success',
				timer: 3000,
				showConfirmButton: false });
			setTimeout(function(){
				window.location.href = 'manage-checklist-topic';
			}, 3000);
			</script>";
	}
	
	if (isset($_GET['tp_id'])) {
		$topicid = $_GET['tp_id'];
		$mbraction = "Update";
		$formname = "MM_update";
		$formvalue = "edittopicfrm";
	  
	  
		$select_edittopic = $db->prepare("SELECT * FROM tbl_inspection_checklist_topics WHERE id=:topicid");
		$select_edittopic->execute(array(":topicid" => $topicid));
		$row_edittopic = $select_edittopic->fetch();
	}
	else{
	  $mbraction = "Save New";
	  $formname = "MM_insert";
	  $formvalue = "addtopicfrm";
	}

	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addtopicfrm")) {
		$topic = $_POST['topic'];
		$desc = $_POST['desc'];
		$currentdate = date("Y-m-d");
		$user = $_POST['user_name'];
		
		$insertSQL = $db->prepare("INSERT INTO tbl_inspection_checklist_topics (topic, description, created_by, date_created) VALUES (:topic, :desc, :user, :date)");
                //add the data into the database										  
		$insertSQL->execute(array(':topic' => $topic, ':desc' => $desc, ':user' => $user, ':date' => $currentdate));

		$msg = 'Topic successfully added .';
		$results = "<script type=\"text/javascript\">
			swal({
				title: \"Success!\",
				text: \" $msg\",
				type: 'Success',
				timer: 3000,
				showConfirmButton: false });
			setTimeout(function(){
				window.location.href = 'manage-checklist-topic';
			}, 3000);
			</script>";
	}
	elseif((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "edittopicfrm")){
		$tpid = $_POST['topicid'];
		$topic = $_POST['topic'];
		$desc = $_POST['desc'];
		$currentdate = date("Y-m-d");
		$user = $_POST['user_name'];
		
		$updatequery = $db->prepare("UPDATE tbl_inspection_checklist_topics SET topic=:topic, description=:desc, updated_by=:user, date_updated=:date WHERE id=:tpid");
                //add the data into the database										  
		$updatequery->execute(array(':topic' => $topic, ':desc' => $desc, ':user' => $user, ':date' => $currentdate, ':tpid' => $tpid));	

		$msg = 'Topic successfully updated .';
		$results = "<script type=\"text/javascript\">
			swal({
				title: \"Success!\",
				text: \" $msg\",
				type: 'success',
				timer: 3000,
				showConfirmButton: false });
			setTimeout(function(){
				window.location.href = 'manage-checklist-topic';
			}, 3000);
			</script>";
	}
	if (isset($_GET['del']) && $_GET['del']==1 && isset($_GET['tp_id']) && !empty($_GET['tp_id'])) {
		$topicid = $_GET['tp_id'];
		$deleteSQL = $db->prepare("DELETE FROM tbl_inspection_checklist_topics WHERE id=:topicid");
		$deleteSQL->execute(array(":topicid" => $topicid));
		
		$msg = 'Topic successfully deleted!!';
		$results = "<script type=\"text/javascript\">
			swal({
				title: \"Success!\",
				text: \" $msg\",
				type: 'success',
				timer: 3000,
				showConfirmButton: false });
			setTimeout(function(){
				window.location.href = 'manage-checklist-topic';
			}, 3000);
			</script>";
	}

	
	 
	
	$query_topics = $db->prepare("SELECT * FROM tbl_inspection_checklist_topics WHERE active='1'");
	$query_topics->execute();
	$row_topics = $query_topics->fetch();
	$totalRows_topics = $query_topics->rowCount();
	
}catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $result;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Result-Based M &amp; E System: Inspection Checklist Topics</title>
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
	<script src="ckeditor/ckeditor.js"></script>
	
	<style>
	#links a {
		color: #FFFFFF;
		text-decoration: none;
	}  </style>

	<script type="text/javascript">

	$(document).ready(function(){
		$('#projcommunity').on('change',function(){
			var scID = $(this).val();
			if(scID){
				$.ajax({
					type:'POST',
					url:'addProjectLocation.php',
					data:'sc_id='+scID,
					success:function(html){
						$('#projlga').html(html);
						$('#projstate').html('<option value="">Select Ward first</option>'); 
					}
				}); 
			}else{
				$('#projlga').html('<option value="">Select Sub-County first</option>');
				$('#projstate').html('<option value="">Select Ward first</option>'); 
			}
		});
		
		$('#projlga').on('change',function(){
			var wardID = $(this).val();
			if(wardID){
				$.ajax({
					type:'POST',
					url:'addProjectLocation.php',
					data:'ward_id='+wardID,
					success:function(html){
						$('#projstate').html(html);
					}
				}); 
			}else{
				$('#projstate').html('<option value="">Select Ward first</option>'); 
			}
		});
		
		$('#outputsector').on('change',function(){
			var sctID = $(this).val();
			if(sctID){
				$.ajax({
					type:'POST',
					url:'addProjectOutput.php',
					data:'sct_id='+sctID,
					success:function(html){
						$('#opdeparment').html(html);
					}
				}); 
			}else{
				$('#outputdept').html('<option value="">Select Sector first</option>');
			}
		});
	});
	
	function checkAvailability() {
		$("#loaderIcon").show();
		var opcode = $.trim($("#outputcode").val());
		jQuery.ajax({
			url: "checkopcode.php",
			data:'code_id='+opcode,
			type: "POST",
			success:function(data){
				if(data){
					$("#addopfrm")[0].reset();
					$("#user-availability-status").html(data);
					$("#loaderIcon").hide();
				}else{
					if( opcode!=''){
						$("#user-availability-status").html("<label>&nbsp;</label><div class='alert bg-green alert-dismissible' role='alert' style='height:35px; padding-top:5px'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>This Output Code does not exist in the database and can be used once: "+opcode+"</div>");
						$("#loaderIcon").hide();
					}
					else if( opcode==''){
						$("#user-availability-status").html("");
						$("#loaderIcon").hide();
					}
				}
			},
			error:function (){}
		});
	}
	</script>
</head>

<body class="theme-blue">
    <!-- Page Loader -->
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
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
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
							include_once('manage-checklist-topic-inner.php');
							?>
						<!--</div> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
  
  
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
    <script src="projtrac-dashboard/js/admin.js"></script>
    <script src="projtrac-dashboard/js/pages/forms/basic-form-elements.js"></script>
    <script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script>
	
    <!-- Demo Js -->
    <script src="projtrac-dashboard/js/demo.js"></script>
</body>

</html>