<?php
include_once "controller.php";

try {
	$valid['success'] = array('success' => false, 'messages' => array());

	function makeTree($indid)
	{
		global $db;
		$query_rsdata = $db->prepare("SELECT disaggregation_type as id, parent, t.category as name 
		FROM tbl_indicator_measurement_variables_disaggregation_type m
		INNER JOIN tbl_indicator_disaggregation_types t ON t.id = m.disaggregation_type
		WHERE indicatorid ='$indid'  ORDER BY parent");
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


	function printNode($node, $level = 0, $islast = false, $element_arr = array())
	{
		global $db, $indid, $disaggregated;
		$id = $node['id'];
		$depth = $node['parent'];
		$total = count($node);
		static $c = 0;
		$field = 0;
		$last_id = end($node);
		if (empty($last_id)) {
			$total = count($node);
			$c = null;
			$c = $total;

			if ($id != "root" && $id != 1) {
				$data = end($element_arr);
				$flex = $data;

				if (!$data) {
					$flex = 0;
				}

				$query_rschild = $db->prepare("SELECT * FROM tbl_indicator_disaggregations  WHERE  disaggregation_type='$id' AND indicatorid='$indid' ORDER BY id");
				$query_rschild->execute();
				$indparenchild = $query_rschild->rowCount();
				$row = $query_rschild->fetch();
				print("<div class='row clearfix'>");
				do {
					print('
					<div class="col-md-6">
						<span for="" id="" >' . $row['disaggregation'] . ' *:</span> 
						<div class="form-input">
							<input type="hidden" name="disstype[]" id="disstype" value="' . $row['id'] . '" class="form-control" value=""> 
							<input type="hidden" name="key[]" value="' . $flex . '" id="">
							<input type="number" name="base_value[]" id="outputcost" value="" placeholder="Enter Value" class="form-control"  required>
						</div>
					</div>');
					foreach ($node['children'] as $child) {
						printNode($child, $level++, $islast1, $element_arr);
					}
				} while ($row = $query_rschild->fetch());
				print("</div>");
			} else {
				$data = end($element_arr);
				$flex = $data;

				if (!$data) {
					$flex = 0;
				}

				print('
				<div class="row clearfix">
					<div class="col-md-6" id="">
						<div class="form-input">
								<input type="hidden" name="disstype[]" id="disstype" value="' . $id . '" class="form-control" value=""> 
								<input type="hidden" name="key[]" value="' . $data . '" id="">
							<input type="number" name="base_value[]" id="outputcost" value="" placeholder="Enter Value" class="form-control" required >
						</div>
					</div>
				</div> ');
				foreach ($node['children'] as $child) {
					printNode($child, $level++, $islast1, $element_arr);
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
					print("
					<div class='row clearfix'>
						<div class='col-md-12' id=''>  
							<p>
								<strong> =>  {$row['disaggregation']}   </strong>
							</p>
						</div>
					</div>");
					foreach ($node['children'] as $child) {
						$islast1 = false;
						if (($c == $total)) {
							$islast1 = true;
						} else {
							$c++;
						}
						array_push($element_arr, $row['id']);
						printNode($child, $level++, $islast1, $element_arr);
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
					printNode($child, $level++, $islast1, $element_arr);
				}
			}
		}
	}

	// for the non disaggregated values 
	function non_disaggregated()
	{
		$input = '
		<div class="col-md-12">
			<label for="" id="" > Base Value *:</label> 
			<div class="form-input">
				<input type="hidden" name="disstype[]" id="disstype" class="form-control" value="0">
				<input type="hidden" name="key[]" id="key" class="form-control" value="0">
				<input type="number" name="base_value[]" id="outputcost" value="" placeholder="Enter Value" class="form-control" required >
			</div>
		</div>';
		echo $input;
	}


	if (isset($_POST['level3dis'])) {
		$level3disaggregations = explode(",", $_POST['level3disaggregations']);
		$indid = $_POST['indid1'];
		$level3 = $_POST['level3dis'];

		$results = false;
		for ($i = 0; $i < count($level3disaggregations); $i++) {
			$disaggregations = $level3disaggregations[$i];
			$insertSQL = $db->prepare("INSERT INTO tbl_indicator_level3_disaggregations (indicatorid, level3, disaggregations) 
			VALUES (:indicatorid, :level3, :disaggregations)");
			$results = $insertquery = $insertSQL->execute(array(':indicatorid' => $indid, ':level3' => $level3, ':disaggregations' => $disaggregations));
		}

		if ($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Added";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while Adding the record!!";
		}
		echo json_encode($valid);
	}


	if (isset($_POST['addbaseline'])) {
		$indid = $_POST['indid'];
		$level3 = $_POST['level3'];
		$location = $_POST['location'];
		$base_values = $_POST['base_value'];
		$disstypes = $_POST['disstype'];
		$keys = $_POST['key'];

		$results = false;
		$deleteQueryD = $db->prepare("DELETE FROM `tbl_indicator_output_baseline_values` WHERE indid=:indid AND level3=:level3 AND location=:location");
		$resultsD = $deleteQueryD->execute(array(':indid' => $indid, ':level3' => $level3, ':location' => $location));

		for ($i = 0; $i < count($base_values); $i++) {
			$base_value = $base_values[$i];
			$disstype = $disstypes[$i];
			$key = $keys[$i];

			$insertSQL = $db->prepare("INSERT INTO tbl_indicator_output_baseline_values (indid,key_unique, level3, location, disaggregations, value) 
			VALUES (:indid, :key_unique, :level3, :location, :disaggregations, :value)");
			$results = $insertquery = $insertSQL->execute(array(':indid' => $indid, ":key_unique" => $key, ':level3' => $level3, ':location' => $location, ":disaggregations" => $disstype,  ":value" => $base_value));
		}

		if ($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Added";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while Adding the record!!";
		}
		echo json_encode($valid);
	}


	if (isset($_GET['getdiss'])) {
		$indid = $_GET['indicatorid'];
		$tree  = makeTree($indid);
		printNode($tree[0]);
	}

	if (isset($_GET['getinddiss'])) {
		non_disaggregated();
	}

	function printNodeedit($node, $lv3id, $lv4id, $level = 0, $islast = false, $element_arr = array())
	{
		global $db, $indid, $disaggregated;
		$id = $node['id'];
		$depth = $node['parent'];
		$total = count($node);
		static $c = 0;
		$field = 0;

		$last_id = end($node);
		if (empty($last_id)) {
			$total = count($node);
			$c = null;
			$c = $total;

			if ($id != "root" && $id != 1) {
				$data = end($element_arr);
				$flex = $data;

				if (!$data) {
					$flex = 0;
				}
				$query_rschild = $db->prepare("SELECT * FROM tbl_indicator_disaggregations  WHERE  disaggregation_type='$id' AND indicatorid='$indid' ORDER BY id");
				$query_rschild->execute();
				$indparenchild = $query_rschild->rowCount();
				$row = $query_rschild->fetch();
				print("<div class='row clearfix'>");
				do {
					$diss_id = $row['id'];
					$query_rstotal = $db->prepare("SELECT * FROM tbl_indicator_output_baseline_values WHERE indid='$indid' and level3='$lv3id'   and location='$lv4id' and key_unique='$flex' and disaggregations ='$diss_id'");
					$query_rstotal->execute();
					$row_rstotal = $query_rstotal->fetch();
					$row_rstotals = $query_rstotal->rowCount();
					$value = $row_rstotal['value'];
					$data = end($element_arr);

					print('
						<div class="col-md-6">
							<span for="" id="" >' . $row['disaggregation'] . ' *:</span> 
							<div class="form-input">
								<input type="hidden" name="key[]" value="' . $data . '" id="">
								<input type="hidden" name="disstype[]" id="disstype" value="' . $row['id'] . '" class="form-control" value=""> 
								<input type="number" name="base_value[]" id="outputcost" value="' . $value . '" placeholder="Enter Value" class="form-control"  required>
							</div>
						</div>');
					foreach ($node['children'] as $child) {
						printNodeedit($child, $lv3id, $lv4id, $level++, $islast1, $element_arr);
					}
				} while ($row = $query_rschild->fetch());
				print("</div>");
			} else {
				$data = end($element_arr);
				$flex = $data;

				if (!$data) {
					$flex = 0;
				}

				$query_rstotal = $db->prepare("SELECT * FROM tbl_indicator_output_baseline_values WHERE indid='$indid' and level3='$lv3id'   and key_unique='$flex'");
				$query_rstotal->execute();
				$row_rstotal = $query_rstotal->fetch();
				$row_rstotals = $query_rstotal->rowCount();
				$value = $row_rstotal['value'];


				print(' 
				<div class="row clearfix">
					<div class="col-md-6" id="">
						<div class="form-input">
							<input type="hidden" name="key[]" value="' . $data . '" id="">
							<input type="hidden" name="disstype[]" id="disstype" value="' . $id . '" class="form-control" value=""> 
							<input type="number" name="base_value[]" id="outputcost" value="" placeholder="Enter Value" class="form-control" required >
						</div>
					</div>
				</div> ');
				foreach ($node['children'] as $child) {
					printNodeedit($child, $lv3id, $lv4id, $level++, $islast1, $element_arr);
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
					print("
					<div class='row clearfix'>
						<div class='col-md-12' id=''>  
							<p>
								<strong> =>  {$row['disaggregation']}   </strong>
							</p>
						</div>
					</div>");
					foreach ($node['children'] as $child) {
						$islast1 = false;
						if (($c == $total)) {
							$islast1 = true;
						} else {
							$c++;
						}
						array_push($element_arr, $row['id']);
						printNodeedit($child, $lv3id, $lv4id, $level++, $islast1, $element_arr);
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
					printNodeedit($child, $lv3id, $lv4id, $level++, $islast1, $element_arr);
				}
			}
		}
	}

	// for the non disaggregated values
	function non_disaggregated_edit($indid, $lv3id)
	{
		global $db;
		$query_rstotal = $db->prepare("SELECT * FROM tbl_indicator_output_baseline_values WHERE indid='$indid' and level3='$lv3id' ");
		$query_rstotal->execute();
		$row_rstotal = $query_rstotal->fetch();
		$row_rstotals = $query_rstotal->rowCount();
		$value = $row_rstotal['value'];
		$input = '
		<div class="col-md-5">
			<label for="" id="" > Forest Baseline *:</label> 
			<div class="form-input">
				<input type="hidden" name="disstype[]" id="disstype" class="form-control" value="0">
				<input type="hidden" name="key[]" id="key" class="form-control" value="0">
				<input type="number" name="base_value[]" id="outputcost" value="' . $value . '" placeholder="Enter Value" class="form-control" required >
			</div>
		</div>';
		echo $input;
	}

	if (isset($_GET['getdissedit'])) {
		$indid = $_GET['indicatorid'];
		$level3 = $_GET['level3'];
		$level4 = $_GET['level4'];
		$tree  = makeTree($indid);
		printNodeedit($tree[0], $level3, $level4);
	}

	if (isset($_GET['getinddissedit'])) {
		$indid = $_GET['indid'];
		$level3 = $_GET['level3'];
		non_disaggregated_edit($indid, $level3);
	}

	if (isset($_POST['validate'])) {
		$indid = $_POST['indid'];
		$disaggregated = $_POST['disaggregated'];
		$data = array();

		$query_rsIndicator = $db->prepare("SELECT * FROM tbl_indicator WHERE  indid ='$indid' ");
		$query_rsIndicator->execute();
		$row_rsIndicator = $query_rsIndicator->fetch();
		$totalrow_rsIndicator = $query_rsIndicator->rowCount();


		if ($totalrow_rsIndicator > 0) {
			$indicator_level = $row_rsIndicator['indicator_baseline_level'];

			if ($indicator_level == 1) {
				$query_rsbase = $db->prepare("SELECT * FROM tbl_indicator_output_baseline_values WHERE  indid=:indid and level3=:level3");
				$query_rsbase->execute(array(":indid" => $indid, ":level3" => 3));
				$row_rsbase = $query_rsbase->fetch();
				$row_rsbases = $query_rsbase->rowCount();
				if ($row_rsbases > 0) {
					array_push($data, true);
				} else {
					array_push($data, false);
				}
			} else {
				if ($disaggregated) {
					$query_rstotal = $db->prepare("SELECT * FROM tbl_state WHERE state NOT LIKE 'All%' AND parent IS NULL ORDER BY state ASC ");
					$query_rstotal->execute();
					$row_rstotal = $query_rstotal->fetch();
					$row_rstotals = $query_rstotal->rowCount();

					if ($row_rstotals > 0) {
						do {
							$comm = $row_rstotal['id'];
							$query_rsprojlga = $db->prepare("SELECT * FROM tbl_state WHERE  parent ='$comm' ");
							$query_rsprojlga->execute();
							$row_rsprojlga = $query_rsprojlga->fetch();
							$row_rsprojlgas = $query_rsprojlga->rowCount();

							if ($comm == 1) {
								array_push($data, true);
							} else {
								if ($row_rsprojlgas > 0) {
									do {
										$projlga = $row_rsprojlga['id'];
										$query_rsward = $db->prepare("SELECT * FROM tbl_state WHERE  parent='$projlga'  ORDER BY state ASC ");
										$query_rsward->execute();
										$row_rsward = $query_rsward->fetch();
										$row_rswards = $query_rsward->rowCount();
										if ($row_rswards > 0) {
											do {
												$ward = $row_rsward['id'];

												$query_rsdiss = $db->prepare("SELECT * FROM tbl_indicator_level3_disaggregations WHERE  indicatorid=:indid  and level3=:level3 ");
												$query_rsdiss->execute(array(":indid" => $indid, ":level3" => $ward));
												$row_rsdiss = $query_rsdiss->fetch();
												$row_rsdisss = $query_rsdiss->rowCount();
												if ($row_rsdisss > 0) {
													do {
														$level4 = $row_rsdiss['id'];
														$query_rsbase = $db->prepare("SELECT * FROM tbl_indicator_output_baseline_values WHERE  indid=:indid and level3=:level3 and location=:level4");
														$query_rsbase->execute(array(":indid" => $indid, ":level3" => $ward, ":level4" => $level4));
														$row_rsbase = $query_rsbase->fetch();
														$row_rsbases = $query_rsbase->rowCount();

														if ($row_rsbases > 0) {
															array_push($data, true);
														} else {
															array_push($data, false);
														}
													} while ($row_rsdiss = $query_rsdiss->fetch());
												} else {
													array_push($data, true);
												}
											} while ($row_rsward = $query_rsward->fetch());
										}
									} while ($row_rsprojlga = $query_rsprojlga->fetch());
								}
							}
						} while ($row_rstotal = $query_rstotal->fetch());
					}
				} else {
					$query_rstotal = $db->prepare("SELECT * FROM tbl_state WHERE state NOT LIKE 'All%' AND parent IS NULL ORDER BY state ASC ");
					$query_rstotal->execute();
					$row_rstotal = $query_rstotal->fetch();
					$row_rstotals = $query_rstotal->rowCount();
					if ($row_rstotals > 0) {
						do {
							$comm = $row_rstotal['id'];
							$query_rsprojlga = $db->prepare("SELECT * FROM tbl_state WHERE  parent ='$comm'  ORDER BY state ASC ");
							$query_rsprojlga->execute();
							$row_rsprojlga = $query_rsprojlga->fetch();
							$row_rsprojlgas = $query_rsprojlga->rowCount();

							if ($comm == 1) {
								array_push($data, true);
							} else {
								if ($row_rsprojlgas > 0) {
									do {
										$projlga = $row_rsprojlga['id'];
										$query_rsward = $db->prepare("SELECT * FROM tbl_state WHERE  parent='$projlga'  ORDER BY state ASC ");
										$query_rsward->execute();
										$row_rsward = $query_rsward->fetch();
										$row_rswards = $query_rsward->rowCount();

										if ($row_rswards > 0) {
											do {
												$ward = $row_rsward['id'];
												$query_rsbase = $db->prepare("SELECT * FROM tbl_indicator_output_baseline_values WHERE  indid=:indid and level3=:level3");
												$query_rsbase->execute(array(":indid" => $indid, ":level3" => $ward));
												$row_rsbase = $query_rsbase->fetch();
												$row_rsbases = $query_rsbase->rowCount();
												if ($row_rsbases > 0) {
													array_push($data, true);
												} else {
													array_push($data, false);
												}
											} while ($row_rsward = $query_rsward->fetch());
										}
									} while ($row_rsprojlga = $query_rsprojlga->fetch());
								}
							}
						} while ($row_rstotal = $query_rstotal->fetch());
					}
				}
			}
		}
		$msg = true;
		if (in_array(false, $data)) {
			$msg = false;
		}

		echo json_encode(array("msg" => $msg));
	}
} catch (PDOException $ex) {
	// $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $ex->getMessage();
}
