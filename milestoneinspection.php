<?php  
require 'authentication.php';

try{
		
	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
	  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	} 

	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "inspfrm")) {  
		if(trim($_POST["milestoneid"]) !== '' || !empty(trim($_POST["milestoneid"])))
		{ 
			$msid = $_POST['milestoneid'];
			$user = $_POST['user_name'];
			$projid = $_POST["projid"];
			$formid = $_POST["formid"];
			$latitude = $_POST["latitude"];
			$longitude = $_POST['longitude'];
			$gisfailuremsg = $_POST['geoerror'];
			$date = date("Y-m-d");
			
			//--------------------------------------------------------------------------
			// 1) create SQL insert statement
			//--------------------------------------------------------------------------							  
			$insert = $db->prepare("INSERT INTO tbl_project_inspection_gis_location (msid, formid, latitude, longitude, gisfailuremsg, user, date) VALUES (:msid, :formid, :latitude, :longitude, :gisfailuremsg, :user, :date)");
			$insert->execute(array(':msid' => $msid, ':formid' => $formid, ':latitude' => $latitude, ':longitude' => $longitude, ':gisfailuremsg' => $gisfailuremsg, ':user' => $user, ':date' => $date));
					
			$finalscore = 0;
			$number = count($_POST["questionid"]);
			for($i=0; $i<$number; $i++)
			{
				$j = $i + 1;
				$questionid = $_POST["questionid"][$i];
				$score = $_POST["question".$questionid];
				$status = 7;
				
				//--------------------------------------------------------------------------
				// 1)Update checklist score and status
				//--------------------------------------------------------------------------							  
				$update = $db->prepare("UPDATE tbl_project_inspection_checklist SET score = :score, status = :status, inspector = :inspector, date_inspected = :date WHERE ckid = :ckid");
				$update->execute(array(':score' => $score, ':status' => $status, ':inspector' => $user, ':date' => $date, ':ckid' => $questionid));
				
				$finalscore = $finalscore + $score;
			}
			
			$percentagescore = ($finalscore / $number)*100;
			if($percentagescore == 100){
				$mstnstatus =5;
			}elseif($percentagescore < 100 && $percentagescore > 0){
				$mstnstatus =6;
			}else{
				$mstnstatus =0;
			}
			
			//--------------------------------------------------------------------------
			// Update Milestone score
			//--------------------------------------------------------------------------							  
			$updatems = $db->prepare("UPDATE tbl_milestone SET inspectionscore = :score, inspectionstatus = :status WHERE msid = :msid");
			$updatems->execute(array(':score' => $percentagescore, ':status' => $mstnstatus, ':msid' => $msid));
					
			$filecategory = "Milestone Inspection";
			$catid = $_POST['formid'];
			$stage=11;
		 
			for($cnt = 0; $cnt < count($_POST["attachmentpurpose"]); $cnt++)
			{ 
				//Check that we have a file
				if(!empty($_FILES['attachment'][$cnt])) {
					$reason = $_POST["attachmentpurpose"][$cnt];
					//Check if the file is JPEG image and it's size is less than 350Kb
					$filename = basename($_FILES['attachment']['name'][$cnt]);
				  
					$ext = substr($filename, strrpos($filename, '.') + 1);
					  
					if (($ext != "exe") && ($_FILES["attachment"]["type"][$cnt] != "application/x-msdownload"))  {
						//Determine the path to which we want to save this file      
						//$newname = dirname(__FILE__).'/upload/'.$filename;
						$newname=$projid."-".$msid."_".$filename; 
						$filepath="uploads/inspection/".$newname;        
						//Check if the file with the same name already exists in the server
						if (!file_exists($filepath)) {
							//Attempt to move the uploaded file to it's new place
							if(move_uploaded_file($_FILES['attachment']['tmp_name'][$cnt],$filepath)) {
								//successful upload
								// echo "It's done! The file has been saved as: ".$newname;
								$fname = $newname;
								//$result2 = $connector->query($qry2);								  
								$insertfile = $db->prepare("INSERT INTO tbl_files (`projid`, `projstage`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, `date_uploaded`) VALUES (:projid, :projstage, :filename, :ftype, :floc, :fcat, :reason, :user, :date)");
								$insertfile->execute(array(':projid' => $projid, ':projstage' => $stage, ':filename' => $fname, ':ftype' => $ext, ':floc' => $filepath, ':fcat' => $filecategory, ':reason' => $reason, ':user' => $user, ':date' => $date));								
							}	
						}
						else{ 
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
					}
					else{  
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
				else{   
					$type = 'error';
					$msg = 'You have not attached any file!!';
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
					
			$msg = 'The inspection successfully recorded.';
			$results = "<script type=\"text/javascript\">
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
		}			
		else{		
			$msg = 'Failed!! This inspection could not be recorded.';
			$results = "<script type=\"text/javascript\">
							swal({
								title: \"Error!\",
								text: \" $msg\",
								type: 'Danger',
								timer: 2000,
								showConfirmButton: false });
							setTimeout(function(){
								window.location.href = 'project-inspections-list';
							}, 2000);
						</script>";	
		}
	}

	if (isset($_GET['msid'])) {
	  $msid = $_GET['msid'];
	}
	
	$query_rsInsptasks = $db->prepare("SELECT DISTINCT m.milestone AS milestone, c.taskid AS taskid, t.task AS task FROM tbl_project_inspection_checklist c INNER JOIN tbl_task t ON c.taskid=t.tkid INNER JOIN tbl_milestone m ON m.msid=t.msid WHERE c.score=0 AND (c.status=3 OR c.status=7) AND c.msid='$msid' GROUP BY taskid ORDER BY tkid");
	$query_rsInsptasks->execute();
	$row_rsInsptasks = $query_rsInsptasks->fetch();
	$count_rsInsptasks = $query_rsInsptasks->rowCount();
	
	$tk = $row_rsInsptasks["taskid"];
	
	$query_projdetails = $db->prepare("SELECT p.projid, p.projname AS projname, p.projdesc AS projdesc, p.projcommunity AS subcounty, p.projlga AS ward, p.projstate AS loc, m.sdate, m.datecompleted FROM tbl_projects p INNER JOIN tbl_milestone m ON p.projid=m.projid WHERE m.msid='$msid'");
	$query_projdetails->execute();
	$row_projdetails = $query_projdetails->fetch();
	
	$projsc = $row_projdetails['subcounty'];
	$projward = $row_projdetails['ward'];
	$projloc = $row_projdetails['loc'];
	$projid = $row_projdetails['projid'];
	
	$query_prosubcounty = $db->prepare("SELECT state FROM tbl_state WHERE id='$projsc'");
	$query_prosubcounty->execute();
	$row_prosubcounty = $query_prosubcounty->fetch();
	
	$query_projwards = $db->prepare("SELECT state FROM tbl_state WHERE id='$projward'");
	$query_projwards->execute();
	$row_projwards = $query_projwards->fetch();
	
	$query_projlocs = $db->prepare("SELECT state FROM tbl_state WHERE id='$projloc'");
	$query_projlocs->execute();
	$row_projlocs = $query_projlocs->fetch();
	
	$projectlocation = $row_prosubcounty["state"]." Sub-county; ".$row_projwards["state"]." ward; ".$row_projlocs["state"]." Location.";
	
	$mlsdate = strtotime($row_projdetails['sdate']);
	$mlstartdate = date("d M Y",$mlsdate);
	
	$mlcmpltdate = strtotime($row_projdetails['datecompleted']);
	$mlcompletiondate = date("d M Y",$mlcmpltdate);
						
	function incrementalHash($len = 5){
	  $charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	  $base = strlen($charset);
	  $result = '';

	  $now = explode(' ', microtime())[1];
	  while ($now >= $base){
		$i = $now % $base;
		$result = $charset[$i] . $result;
		$now /= $base;
	  }
	  return substr($result, -5);
	}

	$pmtid = incrementalHash();
	
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
    <title>Projtrac M&E - Project Result-Based Monitoring</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
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
	
	<script src="ckeditor/ckeditor.js"></script>

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
	}  </style>
		
	<script type="text/javascript">
	function CallQuestionComment(ckid)
	{
		$.ajax({
			type: 'post',
			url: 'callqstncomments.php',
			data: {cklst:ckid},
			success: function (data) {
				$('#questioncomments').html(data);
				 $("#qstnModal").modal({backdrop: "static"});
			}
		});
	}
	</script>
</head>

<body class="theme-blue" onload="getLocation()">
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
	<!-- Inner Section -->
		<?php  include_once('milestoneinspection-inner.php');?>
    <!-- #END# Inner Section -->

	<script language="javascript" type="text/javascript">
	$(document).ready(function(){		
		$('#non-compliant-form').on('submit', function(event){
			event.preventDefault();
			var form_data = $(this).serialize();
			$.ajax({
				type: "POST",
				url: "savenoncompliantcomments.php",
				data:form_data,
				dataType:"json",
				success:function(response)
				{   
					if(response){
						alert('Record successfully saved');
						$('#non-compliant-form')[0].reset();
						$('#qstnModal').modal('hide');
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
	<!-- Modal Question Comments -->
	<div class="modal fade" id="qstnModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center"><font color="#FFF">NON-COMPLIANCE COMMENTS</font></h3>
				</div>
				<form class="tagForm" action="savenoncompliantcomments" method="post" id="non-compliant-form" enctype="multipart/form-data" autocomplete="off">
					<div class="modal-body">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="card">
									<div class="body">
										<div class="table-responsive" style="background:#eaf0f9">
											<div id="questioncomments">
											</div>
											<div class="form-group">
												<div class="col-sm-12 inputGroupContainer">
													<div class="input-group">
														<div style="margin-bottom:5px"><font color="#174082"><strong>Comments: </strong></font></div>                 
														<textarea name="comments" id="comments" cols="60" rows="5" style="padding:10px; font-size:13px; color:#000; width:99.5%"></textarea>
													</div>
												</div>
											</div>
													<div class="body">
														<table class="table table-bordered" id="funding_table">
															<tr>
																<th style="width:50%">Attachments</th>
															</tr>
															<tr>
																<td>
																	<input type="file" name="file" id="file" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required>
																</td>
															</tr>
														</table>
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
							<input type="hidden" name="projid" id="projid" value="<?php echo $projid; ?>"/>
							<input name="formid" type="hidden" id="formid" value="<?php echo $pmtid; ?>" />
						</div>
						<div class="col-md-4">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
    <!-- #END# Modal Question Comments -->

    <!-- Jquery Core Js -->
    <script src="projtrac-dashboard/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="projtrac-dashboard/plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Multi Select Plugin Js -->
    <script src="projtrac-dashboard/plugins/multi-select/js/jquery.multi-select.js"></script>

    <!-- Select Plugin Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="projtrac-dashboard/plugins/node-waves/waves.js"></script>

    <!-- Input Mask Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>

    <!-- Jquery Validation Plugin Css -->
    <script src="projtrac-dashboard/plugins/jquery-validation/jquery.validate.js"></script>

    <!-- JQuery Steps Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-steps/jquery.steps.js"></script>

    <!-- Sweet Alert Plugin Js -->
    <script src="projtrac-dashboard/plugins/sweetalert/sweetalert.min.js"></script>
	
    <!-- Jquery Spinner Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-spinner/js/jquery.spinner.js"></script>
	
    <!-- Bootstrap Tags Input Plugin Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>

    <!-- Autosize Plugin Js -->
    <script src="projtrac-dashboard/plugins/autosize/autosize.js"></script>

    <!-- Moment Plugin Js -->
    <script src="projtrac-dashboard/plugins/momentjs/moment.js"></script>

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
    <script src="projtrac-dashboard/js/admin.js"></script>
    <script src="projtrac-dashboard/js/pages/tables/jquery-datatable.js"></script>
    <script src="projtrac-dashboard/js/pages/forms/basic-form-elements.js"></script>
    <script src="projtrac-dashboard/js/pages/forms/form-validation.js"></script>
    <script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script>
    <script src="projtrac-dashboard/js/pages/charts/jquery-knob.js"></script>

    <!-- Demo Js -->
    <script src="projtrac-dashboard/js/demo.js"></script>
	
</body>

</html>