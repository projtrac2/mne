<?php 

require 'authentication.php';

try{

	
 
	if(isset($_GET["proj"]) && !empty($_GET["proj"])){
		$prjid = $_GET["proj"];
		$query_issuesanalysis = $db->prepare("SELECT i.id, projname, category, observation, status, i.created_by AS monitor, i.date_assigned , i.date_created AS issuedate, i.assigned_by, pr.priority FROM tbl_projissues i INNER JOIN tbl_projects p ON p.projid=i.projid INNER JOIN tbl_projrisk_categories c ON c.rskid=i.risk_category inner join tbl_priorities pr on pr.id=i.priority WHERE p.projid='$prjid' and i.status=2");
		$query_issuesanalysis->execute();	
		$rows = $query_issuesanalysis->fetch();
		$count_issuesanalysis = $query_issuesanalysis->rowCount();
	}
	
	$query_user = $db->prepare("SELECT t.ptid FROM tbl_users u INNER JOIN tbl_projteam2 t ON t.ptid=u.pt_id WHERE u.username='$user_name'");
	$query_user->execute();	
	$row_user = $query_user->fetch();
}
catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
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
	
    <!-- Custom CSS -->
    <link href="css/popper.css" rel="stylesheet">

    <!-- Custom Css -->
    <link href="projtrac-dashboard/css/style.css" rel="stylesheet">

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

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="projtrac-dashboard/css/themes/all-themes.css" rel="stylesheet" />

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

	<?php	
	$query_rsMlsProg =  $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid = '$prjid'");
	$query_rsMlsProg->execute();		
	$row_rsMlsProg = $query_rsMlsProg->fetch();

	$prjprogress = $row_rsMlsProg["mlprogress"]/$row_rsMlsProg["nmb"];

	$percent2 = round($prjprogress,2);
	?>

    <link href="css/left_menu.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style>
	/* If you want the label inside the progress bar */
	#label {
		text-align: center; /* If you want to center it */
		line-height: 25px; /* Set the line-height to the same as the height of the progress bar container, to center it vertically */
		color: black;
	}

	/* Defining the animation */

	@-webkit-keyframes progress
	{
		to {background-position: 30px 0;}
	}

	@-moz-keyframes progress
	{
	  to {background-position: 30px 0;}
	}

	@keyframes progress
	{
	  to {background-position: 30px 0;}
	}

	/* Set the base of our loader */

	.barBg {
		background:#CCCCCC;
		width:100%;
		height:25px;
		border:1px solid #CCCCCC;
		-moz-border-radius: 0px;
		-webkit-border-radius: 0px;
		margin-bottom:30px;
		border-radius: 0px;
	}


	.bar {
		background: #CDDC39;
		width: <?php echo $percent2; ?>%;
		height:24px;
		-moz-border-radius: 0px;
		-webkit-border-radius: 0px;
	}

	/* Set the linear gradient tile for the animation and the playback */

	.barFill {
		width: 100%;
		height: 25px; 
		-webkit-animation: progress 1s linear infinite;
		-moz-animation: progress 1s linear infinite;
		animation: progress 1s linear infinite;
		background-repeat: repeat-x;
		background-size: 30px 30px;
		background-image: -webkit-linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
		background-image: linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
	}

	/* Here's some predefined widths to control the fill. Add these classes to the "bar" div */

	.ten {
		width: 10%; /* Sets the progress to 10% */
	}	

	.twenty {
		width: 20%; /* Sets the progress to 20% */
	}	

	.thirty {
		width: 30%; /* Sets the progress to 30% */
	}	

	.forty {
		width: 40%; /* Sets the progress to 40% */
	}	

	.fifty {
		width: 50%; /* Sets the progress to 50% */
	}

	.sixty {
		width: 60%; /* Sets the progress to 60% */
	}	

	.seventy {
		width: 70%; /* Sets the progress to 70% */
	}	

	.eighty {
		width: 80%; /* Sets the progress to 80% */
	}	

	.ninety {
		width: 90%; /* Sets the progress to 90% */
	}	

	.hundred {
		width: 100%; /* Sets the progress to 100% */
	}	

	/* Some colour classes to get you started. Add the colour class to the "bar" div */

	.aquaGradient{
		background: -moz-linear-gradient(top,  #7aff32 0%, #54a6e5 100%);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#7aff32), color-stop(100%,#54a6e5));
		background: -webkit-linear-gradient(top,  #7aff32 0%,#54a6e5 100%);
		background: -o-linear-gradient(top,  #7aff32 0%,#54a6e5 100%);
		background: -ms-linear-gradient(top,  #7aff32 0%,#54a6e5 100%);
		background: linear-gradient(to bottom,  #7aff32 0%,#54a6e5 100%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#7aff32', endColorstr='#54a6e5',GradientType=0 );
	}

	.roseGradient {
		background: #ff3232;
		background: -moz-linear-gradient(top,  #ff3232 0%, #ed89ff 47%, #ff8989 100%);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ff3232), color-stop(47%,#ed89ff), color-stop(100%,#ff8989));
		background: -webkit-linear-gradient(top,  #ff3232 0%,#ed89ff 47%,#ff8989 100%);
		background: -o-linear-gradient(top,  #ff3232 0%,#ed89ff 47%,#ff8989 100%);
		background: -ms-linear-gradient(top,  #ff3232 0%,#ed89ff 47%,#ff8989 100%);
		background: linear-gradient(to bottom,  #ff3232 0%,#ed89ff 47%,#ff8989 100%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ff3232', endColorstr='#ff8989',GradientType=0 );
	}

	.madras { /* Credit to Divya Manian via http://lea.verou.me/css3patterns/#madras */
		background-color: hsl(34, 53%, 82%);
		background-image: -webkit-repeating-linear-gradient(45deg, transparent 5px, hsla(197, 62%, 11%, 0.5) 5px, hsla(197, 62%, 11%, 0.5) 10px,                  
		  hsla(5, 53%, 63%, 0) 10px, hsla(5, 53%, 63%, 0) 35px, hsla(5, 53%, 63%, 0.5) 35px, hsla(5, 53%, 63%, 0.5) 40px,
		  hsla(197, 62%, 11%, 0.5) 40px, hsla(197, 62%, 11%, 0.5) 50px, hsla(197, 62%, 11%, 0) 50px, hsla(197, 62%, 11%, 0) 60px,                
		  hsla(5, 53%, 63%, 0.5) 60px, hsla(5, 53%, 63%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 80px,
		  hsla(35, 91%, 65%, 0) 80px, hsla(35, 91%, 65%, 0) 90px, hsla(5, 53%, 63%, 0.5) 90px, hsla(5, 53%, 63%, 0.5) 110px,
		  hsla(5, 53%, 63%, 0) 110px, hsla(5, 53%, 63%, 0) 120px, hsla(197, 62%, 11%, 0.5) 120px, hsla(197, 62%, 11%, 0.5) 140px       
		  ),
		-webkit-repeating-linear-gradient(-45deg, transparent 5px, hsla(197, 62%, 11%, 0.5) 5px, hsla(197, 62%, 11%, 0.5) 10px, 
		  hsla(5, 53%, 63%, 0) 10px, hsla(5, 53%, 63%, 0) 35px, hsla(5, 53%, 63%, 0.5) 35px, hsla(5, 53%, 63%, 0.5) 40px,
		  hsla(197, 62%, 11%, 0.5) 40px, hsla(197, 62%, 11%, 0.5) 50px, hsla(197, 62%, 11%, 0) 50px, hsla(197, 62%, 11%, 0) 60px,                
		  hsla(5, 53%, 63%, 0.5) 60px, hsla(5, 53%, 63%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 80px,
		  hsla(35, 91%, 65%, 0) 80px, hsla(35, 91%, 65%, 0) 90px, hsla(5, 53%, 63%, 0.5) 90px, hsla(5, 53%, 63%, 0.5) 110px,
		  hsla(5, 53%, 63%, 0) 110px, hsla(5, 53%, 63%, 0) 140px, hsla(197, 62%, 11%, 0.5) 140px, hsla(197, 62%, 11%, 0.5) 160px       
		);; background-color: hsl(34, 53%, 82%);
		background-image: -webkit-repeating-linear-gradient(45deg, transparent 5px, hsla(197, 62%, 11%, 0.5) 5px, hsla(197, 62%, 11%, 0.5) 10px,                  
		  hsla(5, 53%, 63%, 0) 10px, hsla(5, 53%, 63%, 0) 35px, hsla(5, 53%, 63%, 0.5) 35px, hsla(5, 53%, 63%, 0.5) 40px,
		  hsla(197, 62%, 11%, 0.5) 40px, hsla(197, 62%, 11%, 0.5) 50px, hsla(197, 62%, 11%, 0) 50px, hsla(197, 62%, 11%, 0) 60px,                
		  hsla(5, 53%, 63%, 0.5) 60px, hsla(5, 53%, 63%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 80px,
		  hsla(35, 91%, 65%, 0) 80px, hsla(35, 91%, 65%, 0) 90px, hsla(5, 53%, 63%, 0.5) 90px, hsla(5, 53%, 63%, 0.5) 110px,
		  hsla(5, 53%, 63%, 0) 110px, hsla(5, 53%, 63%, 0) 120px, hsla(197, 62%, 11%, 0.5) 120px, hsla(197, 62%, 11%, 0.5) 140px       
		  ),
		-webkit-repeating-linear-gradient(-45deg, transparent 5px, hsla(197, 62%, 11%, 0.5) 5px, hsla(197, 62%, 11%, 0.5) 10px, 
		  hsla(5, 53%, 63%, 0) 10px, hsla(5, 53%, 63%, 0) 35px, hsla(5, 53%, 63%, 0.5) 35px, hsla(5, 53%, 63%, 0.5) 40px,
		  hsla(197, 62%, 11%, 0.5) 40px, hsla(197, 62%, 11%, 0.5) 50px, hsla(197, 62%, 11%, 0) 50px, hsla(197, 62%, 11%, 0) 60px,                
		  hsla(5, 53%, 63%, 0.5) 60px, hsla(5, 53%, 63%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 70px, hsla(35, 91%, 65%, 0.5) 80px,
		  hsla(35, 91%, 65%, 0) 80px, hsla(35, 91%, 65%, 0) 90px, hsla(5, 53%, 63%, 0.5) 90px, hsla(5, 53%, 63%, 0.5) 110px,
		  hsla(5, 53%, 63%, 0) 110px, hsla(5, 53%, 63%, 0) 140px, hsla(197, 62%, 11%, 0.5) 140px, hsla(197, 62%, 11%, 0.5) 160px       
		);
	}

	.cornflowerblue {
		background-color: CornflowerBlue;
		box-shadow:inset 0px 0px 6px 2px rgba(255,255,255,.3);
		width: <?php echo $percent2; ?>%;
	}

	.carrot {
		background: #f2a130;
		background: -moz-linear-gradient(-45deg,  #f2a130 0%, #e5bd6e 100%);
		background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,#f2a130), color-stop(100%,#e5bd6e));
		background: -webkit-linear-gradient(-45deg,  #f2a130 0%,#e5bd6e 100%);
		background: -o-linear-gradient(-45deg,  #f2a130 0%,#e5bd6e 100%);
		background: -ms-linear-gradient(-45deg,  #f2a130 0%,#e5bd6e 100%);
		background: linear-gradient(135deg,  #f2a130 0%,#e5bd6e 100%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f2a130', endColorstr='#e5bd6e',GradientType=1 );

	}
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
		
	<script type="text/javascript">
	function CallIssueAssignment(id)
	{
		$.ajax({
			type: 'post',
			url: 'callissueaction.php',
			data: {rskid:id},
			success: function (data) {
				$('#issueassignment').html(data);
				 $("#assignModal").modal({backdrop: "static"});
			}
		});
	}
	
	function CallRiskAction(id)
	{
		$.ajax({
			type: 'post',
			url: 'callriskaction.php',
			data: {rskid:id},
			success: function (data) {
				$('#riskaction').html(data);
				 $("#riskModal").modal({backdrop: "static"});
			}
		});
	}
	
	function CallRiskAnalysis(id)
	{
		$.ajax({
			type: 'post',
			url: 'callriskanalysis.php',
			data: {rskid:id},
			success: function (data) {
				$('#riskanalysis').html(data);
				 $("#riskAnalysisModal").modal({backdrop: "static"});
			}
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
	
	<script language="javascript" type="text/javascript">
	$(document).ready(function(){		
		$('#assign-issues-form').on('submit', function(event){
			event.preventDefault();
			var form_data = $(this).serialize();
			$.ajax({
				type: "POST",
				url: "issueassignment.php",
				data: form_data,
				dataType: "json",
				success:function(response)
				{   
					if(response){
						alert('Record successfully saved');
						window.location.reload();
					}
				},
				error: function() {
					alert('Error');
				}
			});
			return false;
		});
	});
	</script>
	<!-- Modal Receive Payment -->
	<div class="modal fade" id="assignModal" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center"><font color="#FFF">Assign Issue Owner</font></h3>
				</div>
				<form class="tagForm" action="issueassignment" method="post" id="assign-issues-form" enctype="multipart/form-data" autocomplete="off">
					<div class="modal-body">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="card">
									<div class="body">
										<div class="table-responsive" style="background:#eaf0f9">
											<div id="issueassignment">
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
							<input type="hidden" name="username" id="username" value="<?php echo $user_name; ?>"/>
						</div>
						<div class="col-md-4">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
    <!-- #END# Modal Receive Payment-->

	<script language="javascript" type="text/javascript">
	$(document).ready(function(){		
		$('#issue-analysis-form').on('submit', function(event){
			event.preventDefault();
			var form_data = $(this).serialize();
			$.ajax({
				type: "POST",
				url: "issueanalysis.php",
				data: form_data,
				dataType: "json",
				success:function(response)
				{   
					if(response){
						alert('Record Successfully Saved');
						window.location.reload();
					}
				},
				error: function() {
					alert('Error');
				}
			});
			return false;
		});
	});
	</script>
	<!-- Modal Issue Action -->
	<div class="modal fade" id="riskModal" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center"><font color="#FFF">Project Issue Analysis</font></h3>
				</div>
				<form class="tagForm" action="issueanalysis" method="post" id="issue-analysis-form" enctype="multipart/form-data" autocomplete="off">
					<div class="modal-body">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="card">
									<div class="body">
										<div class="table-responsive" style="background:#eaf0f9">
											<div id="riskaction">
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
							<input type="hidden" name="username" id="username" value="<?php echo $user_name; ?>"/>
						</div>
						<div class="col-md-4">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
    <!-- #END# Modal Issue Action -->
	<!-- Modal Issue Analysis -->
	<div class="modal fade" id="riskAnalysisModal" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center"><font color="#FFF">Project Issue Analysis Report</font></h3>
				</div>
					<div class="modal-body">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="card">
									<div class="body">
										<div class="table-responsive" style="background:#eaf0f9">
											<div id="riskanalysis">
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
							<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
						</div>
						<div class="col-md-4">
						</div>
					</div>
			</div>
		</div>
	</div>
    <!-- #END# Modal Issue Analysis -->

    <section class="content" style="margin-top:-20px; padding-bottom:0px">
        <div class="container-fluid">
            <div class="block-header">          
				<h4>
				<div class="col-md-12" style="background-color:#F7F7F7; border:#F7F7F7 thin solid; border-radius:2px; margin-bottom:5px; height:25px"><i class="fa fa-bar-chart" aria-hidden="true"></i> Project Issues Analysis</div>       
				<div class="col-md-8" style="font-size:15px; background-color:#CDDC39; border:#CDDC39 thin solid; border-radius:5px; margin-bottom:2px; height:25px; padding-top:2px; vertical-align:center">
					Project Name: <font color="white"><?php echo $row_issues['projname']; ?></font>
				</div>
				<div class="col-md-4" style="font-size:15px; background-color:#CDDC39; border-radius:5px; height:25px; margin-bottom:2px">
					<div class="barBg" style="margin-top:0px; width:100%; border-radius:1px">
						<div class="bar hundred cornflowerblue">
							<div id="label" class="barFill" style="margin-top:0px; border-radius:1px"><?php echo $percent2 ?>%</div>
						</div>
					</div>
				</div>
				</h4>
            </div>
			<div class="row clearfix" style="margin-top:10px">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
					<div class="card">
						<div class="header" style="padding-bottom:0px">
                            <div class="button-demo" style="margin-top:-15px">
								<a href="myprojectdash?projid=<?php echo $prjid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; padding-left:-5px">Details</a>
								<?php if($totalRows_rsTender == 0 && $projcategory == '2' ){?>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Milestones</a>
									<?php }else{?>
									<a href="myprojectmilestones?projid=<?php echo $prjid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Milestones</a>
								<?php } ?>
								<?php if($totalRows_rsTender == 0 && $projcategory == '2' ){?>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Tasks</a>
									<?php }else{?>
									<a href="myprojecttask?projid=<?php echo $prjid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Tasks</a>
								<?php } ?>
								<?php if($totalRows_rsTender == 0 && $projcategory == '2' ){?>
									<div class="dropdown">
									  <button type="button" class="btn bg-light-blue waves-effect dropdown-toggle" data-toggle="dropdown">
										Issues
									  </button>
									  <div class="dropdown-menu">
										<a class="dropdown-item" href="projectissueslist?proj=<?=$prjid?>">Issues Log</a>
										<a class="dropdown-item" href="projectissuesanalysis?proj=<?=$prjid?>">Issues Analysis</a>
										<a class="dropdown-item" href="#">Issues Escalated</a>
									  </div>
									</div>
									<?php }else{?>
									<div class="btn-group" style="background-color: transparent; border-color: transparent; box-shadow: none;">
										<button type="button" class="btn bg-light-blue waves-effect dropdown-toggle" style="margin-top:10px; margin-left:-9px" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<span class="sr-only">Project Issues</span>
											<span class="caret"></span>
										</button>
										<ul class="dropdown-menu" style="position:absolute; padding-left:1px; margin-left:-10px; margin-bottom:1px; padding-top:12px; background-color:#ebf3f5">
											<li style="width:100%"><a href="projectissueslist?proj=<?=$prjid?>">Issues Log</a></li>
											<li style="width:100%"><a href="projectissuesanalysis?proj=<?=$prjid?>">Issues Analysis</a></li>
											<li style="width:100%"><a href="#">Issues Escalated</a></li>
										</ul>
									</div>
								<?php } ?>
								<?php if($totalRows_rsTender == 0 && $projcategory == '2' ){?>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Team Members</a>
									<?php }else{?>
									<a href="myprojmembers?projid=<?php echo $prjid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Team Members</a>
								<?php } ?>
								<?php //if($totalRows_rsTender == 0 && $projcategory == '2' ){?>
									<!--<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px;  margin-left:-9px">Funding</a>
									<?php //}else{?>
									<a href="myprojectfunding?projid=<?php //echo $prjid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Funding</a>-->
								<?php //} ?>
								<?php if($totalRows_rsTender == 0 && $projcategory == '2' ){?>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Team Discussions</a>
									<?php }else{?>
									<a href="myprojectmsgs?projid=<?php echo $prjid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Team Discussions</a>
								<?php } ?>
								<?php if($totalRows_rsTender == 0 && $projcategory == '2' ){?>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Files</a>
									<?php }else{?>
									<a href="myprojectfiles?projid=<?php echo $prjid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Files</a>
								<?php } ?>
								<?php if($totalRows_rsTender == 0 && $projcategory == '2' ){?>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Progress Reports</a>
									<?php }else{?>
									<a href="projreports?projid=<?php echo $prjid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Progress Report</a>
								<?php } ?>
							</div>
						</div>
					</div>
                    <div class="card">
                        <!-- <div class="body"> -->
							<?php
							include_once('projectanalysedissues-inner.php');
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
	
    <!-- Bootstrap tether Core JavaScript -->
    <script src="assets/plugins/popper/popper.min.js"></script>
	
    <!-- Demo Js -->
    <script src="projtrac-dashboard/js/demo.js"></script>
	
    <!--stickey kit -->
    <script src="assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="assets/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!--Custom JavaScript -->
    <script src="js/custom.min.js"></script>
    <script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js?lang=css&amp;skin=default"></script>
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
</body>

</html>