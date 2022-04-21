<?php  
require 'authentication.php';

try{		
	
	
 
	$query_rsTask =  $db->prepare("SELECT tbl_task.*, tbl_projects.progid AS Progid, tbl_projects.projid AS Prjid, tbl_projects.projname AS projname, tbl_projects.projstatus AS projstatus, tbl_projects.projcost AS projcost, tbl_projects.projcommunity, tbl_projects.projlga, tbl_projects.projstate  FROM tbl_projects INNER JOIN tbl_task ON tbl_projects.projid=tbl_task.projid WHERE tbl_projects.deleted='0' AND tbl_projects.projcategory = '1' AND tbl_task.status NOT LIKE '%Completed Task%'  AND tbl_task.status NOT LIKE 'Cancelled Task' AND tbl_task.status NOT LIKE 'On Hold Task' AND tbl_task.status NOT LIKE 'Pending Task' AND tbl_task.paymentstatus='0'");
	$query_rsTask->execute();		
	$Rows_rsTask = $query_rsTask->rowCount();

 

	if (isset($_GET['projid'])) {
		$projectid_rsMyP = $_GET['projid'];
	}

	$query_rsMyP =  $db->prepare("SELECT tbl_projects.*, tbl_projects.projchangedstatus AS projchangedstatus, FORMAT(tbl_projects.projcost, 2), tbl_projects.projstartdate AS sdate, tbl_projects.projenddate AS edate, tbl_milestone.milestone, tbl_milestone.msid, COUNT(CASE WHEN tbl_milestone.status = 'Completed' THEN 1 END) AS `Completed`,    COUNT(CASE WHEN tbl_milestone.status = 'In Progress' THEN 1 END) AS `In Progress`,   COUNT(CASE WHEN tbl_milestone.status = 'Pending' THEN 1 END) AS `Pending`,   COUNT(tbl_milestone.status) AS 'Total Status', @curRow := @curRow + 1 AS sn FROM tbl_projects JOIN (SELECT @curRow := 0) r LEFT JOIN tbl_milestone ON tbl_projects.projid = tbl_milestone.projid WHERE tbl_projects.deleted='0' AND tbl_projects.user_name = '$user_name' AND tbl_projects.projid = '$projectid_rsMyP'");
	$query_rsMyP->execute();		
	$row_rsMyP = $query_rsMyP->fetch();
	$totalRows_rsMyP = $query_rsMyP->rowCount();
 
	$query_rsUsers =  $db->prepare("SELECT * FROM tbl_projteam WHERE user_name = '$user_name'");
	$query_rsUsers->execute();		
	$row_rsUsers = $query_rsUsers->fetch();
	$totalRows_rsUsers = $query_rsUsers->rowCount();

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

    <!-- JQuery Nestable Css -->
    <link href="projtrac-dashboard/plugins/nestable/jquery-nestable.css" rel="stylesheet" />

    <!-- Sweet Alert Css -->
    <link href="projtrac-dashboard/plugins/sweetalert/sweetalert.css" rel="stylesheet" />

    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="projtrac-dashboard/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

    <!-- Bootstrap DatePicker Css -->
    <link href="projtrac-dashboard/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />

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

	<?php
	$projectID =  $row_rsMyP['projid']; 				
	$currentStatus =  $row_rsMyP['projstatus'];
	
	$query_rsMlsProg =  $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid = '$projectID'");
	$query_rsMlsProg->execute();		
	$row_rsMlsProg = $query_rsMlsProg->fetch();

	$prjprogress = $row_rsMlsProg["mlprogress"]/$row_rsMlsProg["nmb"];

	$percent2 = round($prjprogress,2);
	?>
	<style type="text/CSS">

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
	.cf:after { visibility: hidden; display: block; font-size: 0; content: " "; clear: both; height: 0; }
* html .cf { zoom: 1; }
*:first-child+html .cf { zoom: 1; }
h1 { font-size: 1.75em; margin: 0 0 0.6em 0; }
a { color: #2996cc; }
a:hover { text-decoration: none; }
p { line-height: 1.5em; }
.small { color: #666; font-size: 0.875em; }
.large { font-size: 1.25em; }
/**
 * Nestable
 */
.dd { position: relative; display: block; margin: 0; padding: 0; max-width: 100%; list-style: none; font-size: 13px; line-height: 20px; }
.dd-list { display: block; position: relative; margin: 0; padding: 0; list-style: none; }
.dd-list .dd-list { padding-left: 30px; }
.dd-collapsed .dd-list { display: none; }
.dd-item,
.dd-empty,
.dd-placeholder { display: block; position: relative; margin: 0; padding: 0; min-height: 20px; font-size: 13px; line-height: 20px; }
.dd-handle { display: block; height: 30px; margin: 5px 0; padding: 5px 10px; color: #333; text-decoration: none; font-weight: bold; border: 1px solid #ccc;
    background: #fafafa;
    background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
    background:    -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
    background:         linear-gradient(top, #fafafa 0%, #eee 100%);
    -webkit-border-radius: 3px;
            border-radius: 3px;
    box-sizing: border-box; -moz-box-sizing: border-box;
}
.dd-handle:hover { color: #2ea8e5; background: #fff; }
.dd-item > button { display: block; position: relative; cursor: pointer; float: left; width: 25px; height: 20px; margin: 5px 0; padding: 0; text-indent: 100%; white-space: nowrap; overflow: hidden; border: 0; background: transparent; font-size: 12px; line-height: 1; text-align: center; font-weight: bold; }
.dd-item > button:before { content: '+'; display: block; position: absolute; width: 100%; text-align: center; text-indent: 0; }
.dd-item > button[data-action="collapse"]:before { content: '-'; }
.dd-placeholder,
.dd-empty { margin: 5px 0; padding: 0; min-height: 30px; background: #f2fbff; border: 1px dashed #b6bcbf; box-sizing: border-box; -moz-box-sizing: border-box; }
.dd-empty { border: 1px dashed #bbb; min-height: 100px; background-color: #e5e5e5;
    background-image: -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                      -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
    background-image:    -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                         -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
    background-image:         linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                              linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
    background-size: 60px 60px;
    background-position: 0 0, 30px 30px;
}
.dd-dragel { position: absolute; pointer-events: none; z-index: 9999; }
.dd-dragel > .dd-item .dd-handle { margin-top: 0; }
.dd-dragel .dd-handle {
    -webkit-box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1);
            box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1);
}
/**
 * Nestable Extras
 */
.nestable-lists { display: block; clear: both; padding: 30px 0; width: 100%; border: 0; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd; }
#nestable-menu { padding: 0; margin: 20px 0; }
#nestable-output,
#nestable2-output { width: 100%; height: 7em; font-size: 0.75em; line-height: 1.333333em; font-family: Consolas, monospace; padding: 5px; box-sizing: border-box; -moz-box-sizing: border-box; }
#nestable2 .dd-handle {
    color: #fff;
    border: 1px solid #999;
    background: #bbb;
    background: -webkit-linear-gradient(top, #bbb 0%, #999 100%);
    background:    -moz-linear-gradient(top, #bbb 0%, #999 100%);
    background:         linear-gradient(top, #bbb 0%, #999 100%);
}
#nestable2 .dd-handle:hover { background: #bbb; }
#nestable2 .dd-item > button:before { color: #fff; }
@media only screen and (min-width: 100%) {
    .dd { float: left; width: 48%; }
    .dd + .dd { margin-left: 2%; }
}
.dd-hover > .dd-handle { background: #2ea8e5 !important; }
/**
 * Nestable Draggable Handles
 */
.dd3-content { display: block; height: 30px; margin: 5px 0; padding: 5px 10px 5px 40px; color: #333; text-decoration: none; font-weight: bold; border: 1px solid #ccc;
    background: #fafafa;
    background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
    background:    -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
    background:         linear-gradient(top, #fafafa 0%, #eee 100%);
    -webkit-border-radius: 3px;
            border-radius: 3px;
    box-sizing: border-box; -moz-box-sizing: border-box;
}
.dd3-content:hover { color: #2ea8e5; background: #fff; }
.dd-dragel > .dd3-item > .dd3-content { margin: 0; }
.dd3-item > button { margin-left: 30px; }
.dd3-handle { position: absolute; margin: 0; left: 0; top: 0; cursor: pointer; width: 30px; text-indent: 100%; white-space: nowrap; overflow: hidden;
    border: 1px solid #aaa;
    background: #ddd;
    background: -webkit-linear-gradient(top, #ddd 0%, #bbb 100%);
    background:    -moz-linear-gradient(top, #ddd 0%, #bbb 100%);
    background:         linear-gradient(top, #ddd 0%, #bbb 100%);
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}
.dd3-handle:before { content: '≡'; display: block; position: absolute; left: 0; top: 3px; width: 100%; text-align: center; text-indent: 0; color: #fff; font-size: 20px; font-weight: normal; }
.dd3-handle:hover { background: #ddd; }
	</style>
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
	function CallTskPaymentRequest(tkid)
	{
		$.ajax({
			type: 'post',
			url: 'callpaymentrequest.php',
			data: {tskid:tkid},
			success: function (data) {
				$('#requestformcontent').html(data);
				 $("#payModal").modal({backdrop: "static"});
			}
		});
	}
	</script>
	<script type="text/javascript">
	function CallPaymentReceive(id)
	{
		$.ajax({
			type: 'post',
			url: 'callpaymentreceive.php',
			data: {rqid:id},
			success: function (data) {
				$('#receiveformcontent').html(data);
				 $("#recModal").modal({backdrop: "static"});
			}
		});
	}
	</script>
	<script type="text/javascript">
	function CallRequestComments(id)
	{
		$.ajax({
			type: 'post',
			url: 'getreqcomments.php',
			data: {reqid:id},
			success: function (data) {
				$('#commentcontent').html(data);
				 $("#commModal").modal({backdrop: "static"});
			}
		});
	}
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
	<?php
		$pid = $row_rsMyP['projid'];
		$query_rsSDate =  $db->prepare("SELECT projstartdate FROM tbl_projects where projid='$pid'");
		$query_rsSDate->execute();		
		$row_rsSDate = $query_rsSDate->fetch();
				
		$projstartdate = $row_rsSDate["projstartdate"];
		//$start_date = date_format($projstartdate, "Y-m-d");
		$current_date = date("Y-m-d");
		
		$query_rsTender =  $db->prepare("SELECT * FROM tbl_tenderdetails where projid='$pid'");
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
		<?php  include_once('projectfinancials-inhouse-inner.php');?>
    <!-- #END# Inner Section -->
	<script language="javascript" type="text/javascript">
	$(document).ready(function(){		
		$('#payment-request-form').on('submit', function(event){
			event.preventDefault();
			//var taskscore = $("#tskscid").val();
			var form_data = $(this).serialize();
			$.ajax({
				type: "POST",
				url: "savepaymentrequest.php",
				data:form_data,
				dataType:"json",
				success:function(response)
				{     
					if(response){
						alert('Request successfully sent');
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
	<script language="javascript" type="text/javascript">
	$(document).ready(function(){		
		$('#payment-receipt-form').on('submit', function(event){
			event.preventDefault();
			var form_info = new FormData(this);
			form_info.append('file',$('#file')[0].files[0]);
			$.ajax({
				type: "POST",
				url: "savepaymentreceive.php",
				data:form_info,
				dataType:"json",
				mimeType: 'multipart/form-data',
				cache: false,
				contentType: false,
				processData: false,
				success:function(response)
				{   
					if(response){
						alert('Record successfully saved');
						window.location.reload();
					}
					else {
						alert('Error saving record');
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
	<!-- Modal Request Payment -->
	<div class="modal fade" id="payModal" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content"> 
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center"><font color="#FFF">PAYMENT REQUEST FORM</font></h3>
				</div>
				<form class="tagForm" action="callpaymentrequest" method="post" id="payment-request-form" enctype="multipart/form-data">
					<div class="modal-body" id="requestformcontent">
				
					</div>
					<div class="modal-footer">
						<div class="col-md-4">
						</div>
						<div class="col-md-4" align="center">
							<input name="submit" type="submit" class="btn btn-success waves-effect waves-light" id="tag-form-submit" value="Save" />
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
    <!-- #END# Modal Request Payment -->
	<!-- Modal Receive Payment -->
	<div class="modal fade" id="recModal" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center"><font color="#FFF">PAYMENT RECEIPT FORM</font></h3>
				</div>
				<form class="tagForm" action="savepaymentreceive" method="post" id="payment-receipt-form" enctype="multipart/form-data" autocomplete="off">
					<div class="modal-body">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="card">
									<div class="body">
										<div class="table-responsive" style="background:#eaf0f9">
											<div id="receiveformcontent">
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label"><font color="#174082">Payment Release Date</font></label>  
												<div class="col-sm-6 inputGroupContainer">
													<div class="input-group date" id="bs_datepicker_component_container">
														<span class="input-group-addon"><i class="glyphicon glyphicon-th-list"></i></span>
														<input name="datepaid" type="text" title="d/m/Y" id="datepaid" class="form-control" style="border:#CCC thin solid; border-radius: 5px; padding-left:5px"  placeholder="Format: 2019-12-31"/>
													</div>
												</div>
											</div>
											
											<div class="form-group">
												<div class="col-sm-12 inputGroupContainer">
													<div class="input-group">
														<div style="margin-bottom:5px"><font color="#174082"><strong>Comments: </strong></font></div>                 
														<textarea name="receivecomment" id="receivecomment" cols="60" rows="5" style="font-size:13px; color:#000; width:99.5%"></textarea>
														<script>
															CKEDITOR.replace( "receivecomment",
																{
																	height: "150px",
																	toolbar :
																			[
																		{ name: "clipboard", items : [ "Cut","Copy","Paste","PasteText","PasteFromWord","-","Undo","Redo" ] },
																		{ name: "editing", items : [ "Find","Replace","-","SelectAll","-","Scayt" ] },
																		{ name: "insert", items : [ "Image","Flash","Table","HorizontalRule","Smiley","SpecialChar","PageBreak"
																			 ,"Iframe" ] },
																			"/",
																		{ name: "styles", items : [ "Styles","Format" ] },
																		{ name: "basicstyles", items : [ "Bold","Italic","Strike","-","RemoveFormat" ] },
																		{ name: "paragraph", items : [ "NumberedList","BulletedList","-","Outdent","Indent","-","Blockquote" ] },
																		{ name: "links", items : [ "Link","Unlink","Anchor" ] },
																		{ name: "tools", items : [ "Maximize","-","About" ] }
																	]

																});
														</script>
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
						</div>
						<div class="col-md-4">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
    <!-- #END# Modal Receive Payment-->
	<!-- Modal -->
	<div class="modal fade" id="commModal" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center"><font color="#FFF">Funds/Payment Request Comments</font></h3>
				</div>
					<div class="modal-body" id="commentcontent">
				
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
    <!-- #END# Modal -->

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

    <!-- Jquery Nestable -->
    <script src="projtrac-dashboard/plugins/nestable/jquery.nestable.js"></script>
	
    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>

    <!-- Bootstrap Datepicker Plugin Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

    <!-- Custom Js -->
    <script src="projtrac-dashboard/js/admin.js"></script>
    <script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script>
    <script src="projtrac-dashboard/js/pages/forms/basic-form-elements.js"></script>

    <!-- Demo Js -->
    <script src="projtrac-dashboard/js/demo.js"></script>
	
</body>

</html>