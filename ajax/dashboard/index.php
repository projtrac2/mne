<?php
include_once '../controller.php';


function get_access_level()
{
	global $user_designation, $user_department, $user_section, $user_directorate;
	$access_level = "";
	if (($user_designation < 5)) {
		$access_level = "";
	} elseif ($user_designation == 5) {
		$access_level = " AND g.projsector=$user_department";
	} elseif ($user_designation == 6) {
		$access_level = " AND g.projsector=$user_department AND g.projdept=$user_section";
	} elseif ($user_designation > 6) {
		$access_level = " AND g.projsector=$user_department AND g.projdept=$user_section AND g.directorate=$user_directorate";
	}
	return $access_level;
}


if (isset($_POST['get_indicator'])) {
	$department_id = $_POST['department'];
	// get output indicators
	$query_rsOutputIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_sector='$department_id' AND indicator_category='Output' AND active = '1' ORDER BY indid");
	$query_rsOutputIndicators->execute();
	$totalRows_rsOutputIndicators = $query_rsOutputIndicators->rowCount();

	$input = '';
	if ($totalRows_rsOutputIndicators > 0) {
		$input .=  '<option value=""> Select Indicator</option>';
		while ($row_rsOutputIndicators = $query_rsOutputIndicators->fetch()) {
			$input .= '<option value="' . $row_rsOutputIndicators['indid'] . '">' . $row_rsOutputIndicators['indicator_name'] . '</option>';
		}
	} else {
		$input .=  '<option value=""> Indicators Not Found</option>';
	}
	echo $input;
}


function widgets($stage_id, $level_one_id, $level_two_id)
{
	global $db;
	$access_level =	get_access_level();
	$query_rsprojects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.stage_id=:stage_id $access_level ");
	$query_rsprojects->execute(array(":stage_id" => $stage_id));
	$allprojects = $query_rsprojects->rowCount();

	$counter = 0;
	if ($level_one_id != "") {
		if ($allprojects > 0) {
			while ($all_projects = $query_rsprojects->fetch()) {
				$level_one_ids = explode(",", $all_projects['projcommunity']);
				if ($level_two_id != '') {
					$level_two_ids = explode(",", $all_projects['projlga']);
					$counter += in_array($level_two_id, $level_two_ids) ? 1 : 0;
				} else {
					$counter += in_array($level_one_id, $level_one_ids) ? 1 : 0;
				}
			}
		}
	} else {
		$counter = $allprojects;
	}

	return $counter;
}



if (isset($_GET['get_to_financial_year'])) {
	$financial_year_from_id = !empty($_GET['financial_year_from_id']) ? $_GET['financial_year_from_id']  : "";
	$financial_year_to_id = !empty($_GET['financial_year_to_id']) ? $_GET['financial_year_to_id']  : "";
	$level_one_id = !empty($_GET['level_one_id']) ? $_GET['level_one_id']  : "";
	$level_two_id = !empty($_GET['level_two_id']) ? $_GET['level_two_id'] : "";

	$data = '<option value="" >Select Financial Year To</option>';
	$query_fy = $db->prepare("SELECT id, year FROM tbl_fiscal_year WHERE id >= :financial_year_from_id");
	$query_fy->execute(array(":financial_year_from_id" => $financial_year_from_id));
	while ($row = $query_fy->fetch()) {
		$financial_year_to_id = $row['id'];
		$data .= '<option value="' . $financial_year_to_id . '"> ' . $row['year'] . '</option>';
	}

	$dashboard_projects_url = "&projfyfrom=$financial_year_from_id&projfyto=$financial_year_to_id&projscounty=$level_one_id&projward=$level_two_id&btn_search=FILTER";
	$access_level = get_access_level();
	$sql = $access_level;
	if (!empty($financial_year_from_id)) {
		$sql .= !empty($financial_year_to_id) ?  " AND p.projfscyear >= $financial_year_from_id AND p.projfscyear <= $financial_year_to_id" : " AND p.projfscyear >= $financial_year_from_id";
	}
	$widget_array = widgets($sql, $level_one_id, $level_two_id);
	echo json_encode(array("success" => true, "dashboard_projects_url" => $dashboard_projects_url, "financial_years" => $data, "project_details" => $widget_array));
}

if (isset($_GET['get_level2'])) {
	$level_one_id = !empty($_GET['level_one_id']) ? $_GET['level_one_id']  : "";
	$level_two_id = !empty($_GET['level_two_id']) ? $_GET['level_two_id'] : "";

	$data = '<option value="" >Select Ward</option>';
	$query_ward = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:level_one_id");
	$query_ward->execute(array(":level_one_id" => $level_one_id));
	while ($row = $query_ward->fetch()) {
		$data .= '<option value="' . $row['id'] . '"> ' . $row['state'] . '</option>';
	}

	$dashboard_projects_url = "&projscounty=$level_one_id&projward=$level_two_id&btn_search=FILTER";
	$widget_array['stage_one'] = widgets(1, $level_one_id, $level_two_id);
	$widget_array['stage_two'] = widgets(2, $level_one_id, $level_two_id);
	$widget_array['stage_three'] = widgets(3, $level_one_id, $level_two_id);
	$widget_array['stage_four'] = widgets(4, $level_one_id, $level_two_id);
	echo json_encode(array("success" => true, "dashboard_projects_url" => $dashboard_projects_url, "level_two" => $data, "project_details" => $widget_array));
}

if (isset($_GET['get_project_details'])) {
	$level_one_id = !empty($_GET['level_one_id']) ? $_GET['level_one_id']  : "";
	$level_two_id = !empty($_GET['level_two_id']) ? $_GET['level_two_id'] : "";
	$dashboard_projects_url = "&projscounty=$level_one_id&projward=$level_two_id&btn_search=FILTER";
	$widget_array['stage_one'] = widgets(1, $level_one_id, $level_two_id);
	$widget_array['stage_two'] = widgets(2, $level_one_id, $level_two_id);
	$widget_array['stage_three'] = widgets(3, $level_one_id, $level_two_id);
	$widget_array['stage_four'] = widgets(4, $level_one_id, $level_two_id);
	echo json_encode(array("success" => true, "dashboard_projects_url" => $dashboard_projects_url, "project_details" => $widget_array));
}

if (isset($_GET['get_tooltip'])) {
	$risk_level = $_GET['risk_level'];
	$impact = $_GET['impact'];
	$likelihood = $_GET['likelihood'];

	$query_rs_projects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.projstatus <> 5 AND p.projstatus <> 2 AND p.projstatus <> 6 ");
	$query_rs_projects->execute();
	$total_projects = $query_rs_projects->rowCount();
	$counter = 0;
	if ($total_projects > 0) {
		while ($row_rs_project = $query_rs_projects->fetch()) {
			$projid = $row_rs_project['projid'];
			$query_rs_risks = $db->prepare("SELECT * FROM `tbl_project_risks` WHERE projid=:projid");
			$query_rs_risks->execute(array(":projid" => $projid));
			$total_risks = $query_rs_risks->rowCount();

			if ($total_risks > 0) {
				$id = [];
				while ($row_risks = $query_rs_risks->fetch()) {
					$risk_id = $row_risks['id'];
					$query_rs_project_risks = $db->prepare("SELECT * FROM `tbl_project_risk_monitoring` WHERE projid=:projid AND riskid=:risk_id ORDER BY id DESC LIMIT 1");
					$query_rs_project_risks->execute(array(":projid" => $projid, ":risk_id" => $risk_id));
					$total_project_risks = $query_rs_project_risks->rowCount();
					$row_project_risks = $query_rs_project_risks->fetch();
					if ($total_project_risks > 0) {
						$likelihood_id =  $row_project_risks['risk_likelihood'];
						$impact_id =  $row_project_risks['risk_impact'];
						$risk_level_id =  $row_project_risks['risk_level'];

						if ($risk_level_id == $risk_level && $impact == $impact_id && $likelihood == $likelihood_id && !in_array($projid, $id)) {
							$counter += 1;
							$id[] = $projid;
						}
					}
				}
			}
		}
	}

	$query_rs_severity = $db->prepare("SELECT * FROM `tbl_risk_severity` WHERE digit=:risk_level");
	$query_rs_severity->execute(array(":risk_level" => $risk_level));
	$total_severity = $query_rs_severity->rowCount();
	$row_severity = $query_rs_severity->fetch();
	$risk_severity = $total_severity > 0 ? $row_severity['description'] : 0;

	echo json_encode(array("tooltip" => $counter, "risk_severity" => $risk_severity, "success" => true));
}
