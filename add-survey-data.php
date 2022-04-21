<?php
require 'authentication.php';

try {

	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
		$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}

	$results = "";
	


	$query_rsUsers = $db->prepare("SELECT ptid FROM tbl_projteam2 t inner join tbl_users u on u.pt_id=t.ptid WHERE username = '$user_name'");
	$query_rsUsers->execute();
	$row_rsUsers = $query_rsUsers->fetch();
	$totalRows_rsUsers = $query_rsUsers->rowCount();
	$user_name = $row_rsUsers["ptid"];
	/* if(isset($_GET['formid'])){
			$user_name = 1; 
		}else{
			$user_name = $row_rsUsers["ptid"];
		} */

	$queryString_rsIndicators = "";
	$totalRows_rsIndicators = "";
	if (!empty($_SERVER['QUERY_STRING'])) {
		$params = explode("&", $_SERVER['QUERY_STRING']);
		$newParams = array();
		foreach ($params as $param) {
			if (
				stristr($param, "pageNum_rsIndicators") == false &&
				stristr($param, "totalRows_rsIndicators") == false
			) {
				array_push($newParams, $param);
			}
		}
		if (count($newParams) != 0) {
			$queryString_rsIndicators = "&" . htmlentities(implode("&", $newParams));
		}
	}
	$queryString_rsIndicators = sprintf("&totalRows_rsIndicators=%d%s", $totalRows_rsIndicators, $queryString_rsIndicators);


	if (isset($_POST['submit'])) {
		$measurement_variable = $_POST['measurement_variable'];
		$respondent = $_POST['respondent'];
		$form_id = $_POST['form_id'];
		$level3 = $_POST['level3'];

		$lat = $_POST['lat'];
		$lng = $_POST['lng'];

		$location = 0;
		if (isset($_POST['location']) && !empty($_POST['location'])) {
			$location = $_POST['location'];
		}

		$result = false;
		$form_type = $_POST['form_type'];

		if (!empty($lat) && !empty($lng)) {
			$sql = $db->prepare("INSERT INTO tbl_project_form_markers (form_id,level3, location, respondent,lat,lng) VALUES(:form_id,:level3, :location, :respondent,:lat,:lng)");
			$result = $sql->execute(array(":form_id" => $form_id, ":level3" => $level3, ":location" => $location, ":respondent" => $respondent, ":lat" => $lat, ":lng" => $lng));
		}

		for ($i = 0; $i < count($measurement_variable); $i++) {
			$measurement_variable_id = $measurement_variable[$i];
			$values = $_POST['value' . $measurement_variable_id];

			$disaggregation = $_POST['disaggregation' . $measurement_variable_id];
			$categorys = $_POST['category' . $measurement_variable_id];
			$keys = $_POST['key' . $measurement_variable_id];

			for ($j = 0; $j < count($values); $j++) {
				$disaggregations = NULL;
				$key = 0;
				if ($disaggregation[$j] != '') {
					$disaggregations = $disaggregation[$j];
				}
				if ($keys[$j] != '') {
					$key = $keys[$j];
				}

				$category = $categorys[$j];
				$value = $values[$j];
				$insertSQL = $db->prepare("INSERT INTO tbl_indicator_baseline_values (form_id,key_unique, level3, location, measurement_variable,disaggregations, value, respondent, form_type, type) 
					VALUES (:form_id,:key_unique, :level3, :location, :measurement_variable, :disaggregations, :value, :respondent, :form_type, :category)");
				$result = 	$insertSQL->execute(array(
					":form_id" => $form_id, ":key_unique" => $key, ":level3" => $level3, ":location" => $location, ":measurement_variable" => $measurement_variable_id,
					":disaggregations" => $disaggregations, ":value" => $value, ":respondent" => $respondent, ":form_type" => $form_type, ":category" => $category
				));
			}
		}

		if ($result) {
			$msg = 'The Survey data was successfully added.';
			$results = "
				<script type=\"text/javascript\">
					swal({
					title: \"Success!\",
					text: \" $msg\",
					type: 'Success',
					timer: 2000,
					showConfirmButton: false });
					setTimeout(function(){
							window.location.href = 'view-survey-tasks';
						}, 2000);
				</script>";
		}
	}

	function makeTree($indid, $category)
	{
		global $db;
		$query_rsdata = $db->prepare("SELECT disaggregation_type as id, parent, t.category as name 
			FROM tbl_indicator_measurement_variables_disaggregation_type m
			INNER JOIN tbl_indicator_disaggregation_types t ON t.id = m.disaggregation_type
			WHERE indicatorid ='$indid' AND m.type='$category'");
		$query_rsdata->execute();
		$indparendata = $query_rsdata->rowCount();
		$data = $query_rsdata->fetchAll();

		$tree = array(
			array('id' => 'root', 'parent' => -1, "name" => 'name', 'children' => array())
		);
		$treePtr = array(0 => &$tree[0]);
		foreach ($data as $item) {
			$children = &$treePtr[$item['parent']]['children'];
			if ($children  != '') {
				$c = count($children);
				$children[$c] = $item;
				$children[$c]['children'] = array();
				$treePtr[$item['id']] = &$children[$c];
				$treePtr[$item['name']] = &$children[$c];
			}
		}
		return $tree;
	}

	function printNode($node, $category, $placeholder, $level = 0, $islast = false, $element_arr = array())
	{
		global $db, $indid, $disaggregated;
		$id = $node['id'];
		$depth = $node['parent'];
		$total = count($node);
		static $c = 0;
		$field = 0;
		//var_dump($total);

		$last_id = end($node);
		if (empty($last_id)) {
			$total = count($node);
			$c = null;
			$c = $total;
			$query_measurement_variables = $db->prepare("SELECT * FROM  tbl_indicator_measurement_variables WHERE indicatorid = '$indid' AND category='$category' ORDER BY id");
			$query_measurement_variables->execute();
			$row_measurement_variables = $query_measurement_variables->fetch();
			$totalrow_measurement_variables = $query_measurement_variables->rowCount();

			if ($id != "root" && $id != 1) {
				do {
					$query_rschild = $db->prepare("SELECT * FROM tbl_indicator_disaggregations  WHERE  disaggregation_type='$id' AND indicatorid='$indid' ORDER BY id");
					$query_rschild->execute();
					$indparenchild = $query_rschild->rowCount();
					$row = $query_rschild->fetch();
					print("<div class='row clearfix'>
							<div class='col-md-12' id=''> 
							 <p>
							 <input type='hidden' name='measurement_variable[]' value='{$row_measurement_variables['id']}' id='financierceiling'> 
							 <strong>{$row_measurement_variables['measurement_variable']} </strong></p></div>");

					do {
						$data = end($element_arr);
						print('
							<div class="col-md-6" id="">
								<span for="" id="" >' . $row['disaggregation'] . ' *:</span>
								<input type="hidden" name="key' . $row_measurement_variables["id"] . '[]"" value="' . $data . '" id=""> 
								<input type="hidden" name="category' . $row_measurement_variables["id"] . '[]" value="' . $category . '">
								<input type="hidden" name="disaggregation' . $row_measurement_variables["id"] . '[]"" value="' . $row['id'] . '" id=""> 
								<div class="form-input">
									<input type="number" name="value' . $row_measurement_variables['id'] . '[]" id="outputcost" value="" placeholder="' . $placeholder . '" class="form-control" >
								</div>
							</div>');
						foreach ($node['children'] as $child) {
							printNode($child, $category, $placeholder, $level++, $islast1, $element_arr);
						}
					} while ($row = $query_rschild->fetch());
					print("</div>");
				} while ($row_measurement_variables = $query_measurement_variables->fetch());
			} else {
				print("<div class='row clearfix'>");
				do {
					print("  
							<div class='col-md-6' id=''> 
							 <p>
							 <input type='hidden' name='measurement_variable[]' value='{$row_measurement_variables['id']}' id='financierceiling'> 
							 <strong>{$row_measurement_variables['measurement_variable']} </strong></p>");
					print(' 
								<input type="hidden" name="key' . $row_measurement_variables["id"] . '[]"" value="" id=""> 
								<input type="hidden" name="category' . $row_measurement_variables["id"] . '[]" value="' . $category . '">
								<input type="hidden" name="disaggregation' . $row_measurement_variables["id"] . '[]"" value="" id=""> 
								<div class="form-input">
									<input type="number" name="value' . $row_measurement_variables['id'] . '[]" id="outputcost" value="" placeholder="' . $placeholder . '" class="form-control" >
								</div> ');
					print("</div>");
				} while ($row_measurement_variables = $query_measurement_variables->fetch());
				print("</div>");

				foreach ($node['children'] as $child) {
					printNode($child, $category, $placeholder, $level++, $islast1, $element_arr);
				}
			}
		} else {
			if ($id != "root" &&  $id != 1) {
				$query_rschild = $db->prepare("SELECT * FROM tbl_indicator_disaggregations  WHERE  disaggregation_type='$id' AND indicatorid='$indid' ORDER BY id");
				$query_rschild->execute();
				$indparenchild = $query_rschild->rowCount();
				$row = $query_rschild->fetch();
				do {
					$field++;
					print("<div class='row clearfix'><div class='col-md-12' id=''>  
						<p><strong> =>  {$row['disaggregation']}   </strong></p></div></div>");
					foreach ($node['children'] as $child) {
						$islast1 = false;
						if (($c == $total)) {
							$islast1 = true;
						} else {
							$c++;
						}
						array_push($element_arr, $row['id']);
						printNode($child, $category, $placeholder, $level++, $islast1, $element_arr);
					}
				} while ($row = $query_rschild->fetch());
			} else {
				foreach ($node['children'] as $child) {
					$islast1 = false;
					if (($c == $total)) {
						$islast1 = true;
					} else {
						$c++;
					}
					array_push($element_arr, "");
					printNode($child, $category, $placeholder, $level++, $islast1, $element_arr);
				}
			}
		}
	}

	function non_disaggregated($indid, $category, $placeholder)
	{
		global $db;
		$query_measurement_variables = $db->prepare("SELECT * FROM  tbl_indicator_measurement_variables WHERE indicatorid = '$indid' AND category='$category' ORDER BY id");
		$query_measurement_variables->execute();
		$row_measurement_variables = $query_measurement_variables->fetch();
		$totalrow_measurement_variables = $query_measurement_variables->rowCount();

		if ($totalrow_measurement_variables > 0) {
			do {
				print('
					<div class="col-md-6" id="">
						<label for="" id="" class="control-label">' . $row_measurement_variables['measurement_variable'] . ' *:</label>
						<div class="form-input">
							<input type="hidden" name="measurement_variable[]" value="' . $row_measurement_variables["id"] . '" id=""> 
							<input type="hidden" name="category' . $row_measurement_variables["id"] . '[]" value="' . $category . '">
							<input type="hidden" name="key' . $row_measurement_variables["id"] . '[]"" value="" id=""> 
							<input type="hidden" name="disaggregation' . $row_measurement_variables["id"] . '[]" value="" id=""> 
							<input type="number" name="value' . $row_measurement_variables["id"] . '[]" id="outputcost" value="" placeholder="' . $placeholder . '" class="form-control" >
						</div>
					</div>');
			} while ($row_measurement_variables = $query_measurement_variables->fetch());
		}
	}

	function check_exists($indid, $type)
	{
		global $db;
		$query_outcome = 'SELECT * FROM tbl_indicator_measurement_variables WHERE indicatorid=:indicatorid AND category=:category';
		$stmt_outcome = $db->prepare($query_outcome);
		$stmt_outcome->execute(array(":indicatorid" => $indid, ":category" => $type));
		$row_outcome = $stmt_outcome->fetch();
		$totalrow_outcome = $stmt_outcome->rowCount();
		$name = false;
		if ($totalrow_outcome > 0) {
			$name = true;
		}
		return $name;
	}

	$placeholder = "";
	$stid = $id = "";
	$indid = "";
	$projid = "";
	$userid = "";
	$form_id = "";
	$user = "";
	$created_at = "";
	$location_name = "";
	$form_body = "";
	$projcode = "";
	$form_name = "";
	$projname = "";
	$typeName = "";
	$indicator_name = "";
	$measurement_unit = "";
	$data_source = "";
	$respondent = "";
	$phone = "";
	$state = '';
	$locationName = '';

	if (isset($_GET['formid'])) {
		$form_id = $_GET['formid'];

		$query_form_details = $db->prepare("SELECT p.projid,p.projcode, p.projname,i.indid,i.indicator_name,i.indicator_category,
			i.indicator_disaggregation, f.form_name, f.respondents_type, m.unit, s.respondents, f.form_type, f.type, s.level3, s.location_disaggregation
			FROM tbl_indicator_baseline_survey_forms f 
            INNER JOIN tbl_projects p ON p.projid = f.projid
            INNER JOIN tbl_indicator i ON i.indid = f.indid
            INNER JOIN tbl_measurement_units m ON i.indicator_unit = m.id
            INNER JOIN tbl_indicator_baseline_survey_details s ON s.formid = f.id 
			WHERE f.id=:formid 	and s.respondents =:user_name");
		$query_form_details->execute(array(":formid" => $form_id, ":user_name" => $user_name));
		$row_form_details = $query_form_details->fetch();
		$totalrow_form_details = $query_form_details->rowCount();


		$form_name = $row_form_details['form_name'];
		$projname = $row_form_details['projname'];
		$projid = $row_form_details['projid'];
		$indid = $row_form_details['indid'];
		$respondents_type = $row_form_details['respondents_type'];
		$measurement_unit = $row_form_details['unit'];
		$indicator_name = $row_form_details['indicator_name'];
		$ptid = $row_form_details['respondents'];
		$disaggregated = $row_form_details['indicator_disaggregation'];
		$projcode = $row_form_details['projcode'];
		$indicator_category = $row_form_details['indicator_category'];
		$indicator_type = $row_form_details['type'];
		$form_type = $row_form_details['form_type'];
		$location_disaggregation = $row_form_details['location_disaggregation'];
		$stid = $row_form_details['level3'];


		$query = 'SELECT * FROM tbl_state WHERE id=:stid';
		$query_state = $db->prepare($query);
		$query_state->execute(array(":stid" => $stid));
		$row_state = $query_state->fetch();
		$state = $row_state['state'];

		if ($location_disaggregation != '') {
			$query = 'SELECT * FROM tbl_indicator_level3_disaggregations WHERE id=:loc';
			$query_state = $db->prepare($query);
			$query_state->execute(array(":loc" => $location_disaggregation));
			$row_state = $query_state->fetch();
			$location = $row_state['disaggregations'];
			$locationName = '<li class="list-group-item"><strong>Location: </strong> ' . $location . '</li>';
		}

		if ($form_type == 9) {
			$placeholder = "Enter Base Value";
			$survey_name = "Baseline Survey";
		} else if ($form_type == 11) {
			$survey_name = "Evaluation Survey";
			if ($indicator_type == 1) {
				$placeholder = "Enter Assessment Value";
			} else if ($indicator_type == 2) {
				$placeholder = "Enter Evaluation Value";
			}
		}


		if ($ptid == 1) {
			$respondent;
			$query = 'SELECT * FROM tbl_projteam2 WHERE ptid=:ptid';
			$stmt = $db->prepare($query);
			$stmt->execute(array(":ptid" => $ptid));
			$row = $stmt->fetch();
			$respondent =  $ptid;
			$phone = $row['phone'];
		} else {
			$respondent = $ptid;
		}


		if ($form_type == 9 && $indicator_type == 1) {
			$query_impact = 'SELECT data_source, impact FROM  tbl_project_expected_impact_details WHERE projid=:projid';
			$stmt_impact = $db->prepare($query_impact);
			$stmt_impact->execute(array(":projid" => $projid));
			$row_impact = $stmt_impact->fetch();
			$data_source = $row_impact['data_source'];
			$typeName = $row_impact['impact'];
		} else if ($form_type == 9 && $indicator_type == 2) {
			$query_outcome = 'SELECT e.data_source, g.outcome FROM tbl_project_expected_outcome_details e 
              INNER JOIN tbl_projects p ON p.projid = e.projid
              INNER JOIN tbl_programs g ON g.progid = p.progid
              WHERE p.projid=:projid';
			$stmt_outcome = $db->prepare($query_outcome);
			$stmt_outcome->execute(array(":projid" => $projid));
			$row_outcome = $stmt_outcome->fetch();
			$data_source = $row_outcome['data_source'];
			$typeName = $row_outcome['outcome'];
		}
	}

	
} catch (PDOException $ex) {

	function flashMessage($flashMessages)
	{
		return $flashMessages;
	}

	$result = flashMessage("An error occurred: " . $ex->getMessage());
	echo $result;
}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title>Result-Based Monitoring &amp; Evaluation System: Indicators</title>
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
		}
	</style>
	<script src="assets/custom js/survey.js"></script>
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANeDcXUz-GQssz7EHTzGGUHU-VPlAtMGY"></script>
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

	<section class="content" style="margin-top:-20px; padding-bottom:0px">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader"><i class="fa fa-plus" aria-hidden="true"></i> <?= $survey_name ?>
				</h4>
			</div>
			<!-- Draggable Handles -->
			<div class="row clearfix">
				<div class="block-header">
					<?php
					echo $results;
					?>
				</div>
				<div class="col-lg-12 col-md-3 col-sm-12 col-xs-12">
					<div class="card">
						<!-- <div class="body"> -->
						<?php
						include_once('add-survey-data-inner.php');
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

	<!-- Autosize Plugin Js -->
	<script src="projtrac-dashboard/plugins/autosize/autosize.js"></script>

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

	<!-- Demo Js -->
	<script src="projtrac-dashboard/js/demo.js"></script>
	<script src="assets/custom js/indicator-details.js"></script>

</body>

</html>