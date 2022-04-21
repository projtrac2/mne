<?php  

require 'authentication.php';

try{	
		
	

	$editFormAction = $_SERVER['PHP_SELF'];
 
	if (isset($_GET["msg"]) && !empty($_GET["msg"])) {
		$msg = $_GET["msg"];
		$results = "<script type=\"text/javascript\">
			swal({
				title: \"Success!\",
				text: \" $msg\",
				type: 'Success',
				timer: 3000,
				showConfirmButton: false });
			setTimeout(function(){
				window.location.href = 'create-projects-inspection-checklist';
			}, 3000);
		</script>";
	}
	
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addinspectionchecklist")) {
		
		$department = $_POST['department'];
		$output = $_POST['output'];
		$checklistname = $_POST['checklistname'];
		$current_date = date("Y-m-d");

		if(!empty($checklistname) && !empty($department) && !empty($output)){
			$insertSQL = $db->prepare("INSERT INTO tbl_inspection_checklist(department, output, name, active, created_by, date_created) VALUES (:dept, :op, :name, :active, :user, :date)");
			//add the data into the database										  
			$insertSQL->execute(array(':dept' => $department, ':op' => $output, ':name' => $checklistname, ':active' => 1, ':user' => $username, ':date' => $current_date));
						
			if($insertSQL->rowCount() == 1){
				$checklistname_id = $db->lastInsertId();
							
				for($cnt = 0; $cnt < count($_POST['topic']); $cnt++)
				{ 
				//Check that we have a file
					$topic = $_POST['topic'][$cnt];
					$question = $_POST["question"][$cnt];
					$qry2 = $db->prepare("INSERT INTO tbl_inspection_checklist_questions (checklistname,topic,question,created_by,date_created) VALUES (:cklstname,:topic,:question,:user,:date)");	
					$qry2->execute(array(':cklstname' => $checklistname_id, ':topic' => $topic, ':question' => $question, ':user' => $username,':date' => $current_date));								
				}	
				
				$msg = 'Checklist Successfully Added';
				
				$results = "<script type=\"text/javascript\">
					swal({
						title: \"Success!\",
						text: \" $msg\",
						type: 'Success',
						timer: 2000,
					showConfirmButton: false });
					setTimeout(function(){
						window.location.href = 'create-projects-inspection-checklist';
					}, 2000);
				</script>";
			}
			else{ 
				$msg = 'Sorry could not add the checklist!!';
				$results = "<script type=\"text/javascript\">
					swal({
						title: \"Error!\",
						text: \" $msg \",
						type: 'Danger',
						timer: 3000,
					showConfirmButton: false });
					setTimeout(function(){
						window.location.href = 'create-projects-inspection-checklist';
					}, 3000);
				</script>";
			} 		  
		}
		else{ 
			$msg = 'Sorry could not add the checklist!!';
			$results = "<script type=\"text/javascript\">
				swal({
					title: \"Error!\",
					text: \" $msg \",
					type: 'Danger',
					timer: 3000,
				showConfirmButton: false });
				setTimeout(function(){
					window.location.href = 'create-projects-inspection-checklist';
				}, 3000);
			</script>";
		}
	}		

	$query_alldepartments = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE deleted='0' AND parent<>'0'");
	$query_alldepartments->execute();	
	$rows_alldepartments = $query_alldepartments->fetch();
	$count_alldepartments = $query_alldepartments->rowCount();
	
	$query_rchecklist = $db->prepare("SELECT * FROM tbl_inspection_checklist c inner join tbl_indicator i ON i.indid=c.output WHERE c.active='1' and i.indicator_category='Output'");
	$query_rchecklist->execute();
	$totalrows_rchecklist = $query_rchecklist->rowCount();
	
	$query_alltopic = $db->prepare("SELECT indid, indicator_name FROM tbl_indicator WHERE indicator_category='Output'");
	$query_alltopic->execute();	
	$rows_alltopic = $query_alltopic->fetch();

	function fill_unit_select_box($db)
	{ 
		$topic = '';
		$query_alltopics = $db->prepare("SELECT id, topic FROM tbl_inspection_checklist_topics WHERE active='1'");
		$query_alltopics->execute();
		$rows_alltopics = $query_alltopics->fetchAll();
		foreach($rows_alltopics as $row)
		{
			$topic .= '<option value="'.$row["id"].'">'.$row["topic"].'</option>';
		}
		return $topic;
	}
	
	

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
    <title>Projtrac M&E - Create Projects Inspection Checklist</title>
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

    <!-- Bootstrap Spinner Css -->
    <link href="projtrac-dashboard/plugins/jquery-spinner/css/bootstrap-spinner.css" rel="stylesheet">

    <!-- JQuery DataTable Css -->
    <link href="projtrac-dashboard/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

    <!-- Sweet Alert Css -->
    <link href="projtrac-dashboard/plugins/sweetalert/sweetalert.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="projtrac-dashboard/css/style.css" rel="stylesheet">

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

	$(document).ready(function(){
		$('#department').on('change',function(){
			var deptID = $(this).val();
			if(deptID){
				$.ajax({
					type:'POST',
					url:'assets/processor/checklist-outputs.php',
					data:'dept='+deptID,
					success:function(html){
						$('#output').html(html);
					}
				}); 
			}else{
				$('#output').html('Select Department First');
			}
		});
	});
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
 
    </section>
	<!-- Inner Section -->
		<?php  include_once('create-projects-inspection-checklist-inner.php');?>
    <!-- #END# Inner Section -->

    <!-- Jquery Core Js -->
    <script src="projtrac-dashboard/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="projtrac-dashboard/plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="projtrac-dashboard/plugins/node-waves/waves.js"></script>

    <!-- Autosize Plugin Js -->
    <script src="projtrac-dashboard/plugins/autosize/autosize.js"></script>

    <!-- Moment Plugin Js -->
    <script src="projtrac-dashboard/plugins/momentjs/moment.js"></script>

    <!-- Sweet Alert Plugin Js -->
    <script src="projtrac-dashboard/plugins/sweetalert/sweetalert.min.js"></script>

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
	
    <!-- Demo Js -->
    <script src="projtrac-dashboard/js/demo.js"></script>
	
</body>

</html>