<?php 
require('includes/head.php'); 
if ($permission) {
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

		$islast = false;
		$islast1 = false;
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
	<!-- start body  -->
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
				<?=$icon?>
				<?php echo $pageTitle ?>
					<div class="btn-group" style="float:right">
						<div class="btn-group" style="float:right">
						</div>
					</div>
				</h4>
			</div>
			<div class="row clearfix">
				<div class="block-header">
					<?= $results; ?>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="card-header">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<ul class="list-group">
									<li class="list-group-item list-group-item list-group-item-action active"> <?= $form_name ?> </li>
									<li class="list-group-item"><strong>Project Code: </strong> <?= $projcode ?> </li>
									<li class="list-group-item"><strong>Project Name:</strong> <?= $projname ?> </li>
									<li class="list-group-item"><strong><?= $level3label ?>: </strong> <?= $state ?> </li>
									<?php echo $locationName ?>
									<li class="list-group-item"><strong>Indicator: </strong> <?= $indicator_name ?> </li>
									<li class="list-group-item"><strong>Unit of Measure: </strong> <?= $measurement_unit ?> </li>
								</ul>
							</div>
						</div>
						<div class="body">
							<form action="" method="post" id="submitform">
								<div class="col-md-12">
									<fieldset class="scheduler-border row">
										<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Questions</legend>
										<?php
										if ($indicator_type == 1) {
											$category = 1;
											if ($disaggregated == 1) {
										?>
												<div class="row clearfix">
													<div class="col-md-12">
														<?php
														$tree = makeTree($indid, $category);
														printNode($tree[0], $category, $placeholder);
														?>
													</div>
												</div>
											<?php
											} else {
											?>
												<div class="row clearfix">
													<div class="col-md-12">
														<?php
														non_disaggregated($indid, $category, $placeholder);
														?>
													</div>
												</div>
											<?php
											}
										} else if ($indicator_type == 2) {
											if ($disaggregated == 1) {
											?>
												<div class="row clearfix">
													<div class="col-md-12">
														<h4><u>Direct Beneficiary</u></h4>
														<?php
														$category = 2;
														$tree = makeTree($indid, $category);
														printNode($tree[0], $category, $placeholder);
														?>
													</div>
												</div>
												<?php
												$category = 3;
												$query = 'SELECT * FROM tbl_indicator_measurement_variables WHERE indicatorid =:indid and category=3';
												$query_rsindid = $db->prepare($query);
												$query_rsindid->execute(array(":indid" => $indid));
												$row_rsindid = $query_rsindid->rowCount();
												if ($row_rsindid > 0) {
												?>
													<div class="row clearfix">
														<div class="col-md-12">
															<h4><u>Indirect Beneficiary</u></h4>
															<?php
															$category = 3;
															$tree = makeTree($indid, $category);
															printNode($tree[0], $category, $placeholder);
															?>
														</div>
													</div>
												<?php
												}
											} else {
												?>
												<div class="row clearfix">
													<div class="col-md-12">
														<h4>Direct Beneficiary</h4>
														<?php
														$category = 2;
														non_disaggregated($indid, $category, $placeholder);
														?>
													</div>
												</div>
												<?php
												$category = 3;
												$query = 'SELECT * FROM tbl_indicator_measurement_variables WHERE indicatorid =:indid and category=3';
												$query_rsindid = $db->prepare($query);
												$query_rsindid->execute(array(":indid" => $indid));
												$row_rsindid = $query_rsindid->rowCount();
												if ($row_rsindid > 0) {
												?>
													<div class="row clearfix">
														<div class="col-md-12">
															<h4>Indirect Beneficiary</h4>
															<?php
															$category = 3;
															non_disaggregated($indid, $category, $placeholder);
															?>
														</div>
													</div>
										<?php
												}
											}
										}
										?>
										<input type="hidden" name="form_id" value="<?= $form_id ?>">
										<input type="hidden" name="form_type" value="<?= $form_type ?>">
										<input type="hidden" name="respondent" value="<?= $respondent ?>">
										<input type="hidden" name="level3" value="<?= $stid ?>">
										<input type="hidden" name="location" value="<?= $location_disaggregation ?>">
										<input type="hidden" name="lat" id="lat" value="">
										<input type="hidden" name="lng" id="lng" value="">
										<div class="col-md-12" align="center">
											<button type="submit" name="submit" class="btn btn-success">Submit</button>
										</div>
									</fieldset>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
	</section>
	<!-- end body  -->
<?php
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');
?>
<script src="assets/custom js/indicator-details.js"></script>
<script src="assets/custom js/survey.js"></script>