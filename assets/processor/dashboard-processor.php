<?php
include_once "controller.php";

try {
	if (isset($_POST['get_level2'])) {
		$getward = $_POST['level1'];
		$data = '<option value="" >Select Ward</option>';
		$query_ward = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:getward");
		$query_ward->execute(array(":getward" => $getward));
		while ($row = $query_ward->fetch()) {
			$projlga = $row['id'];
			$query_rsLocations = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:id");
			$query_rsLocations->execute(array(":id" => $projlga));
			$row_rsLocations = $query_rsLocations->fetch();
			$total_locations = $query_rsLocations->rowCount();
			if ($total_locations > 0) {
				$data .= '<option value="' . $row['id'] . '"> ' . $row['state'] . '</option>';
			}
		}
		echo $data;
	}

	if (isset($_POST['get_level3'])) {
		$getlocation = $_POST['level2'];
		$data = '<option value="" >Select Location</option>';
		$query_loca = $db->prepare("SELECT id, state FROM tbl_state WHERE parent='$getlocation'");
		$query_loca->execute();
		while ($row = $query_loca->fetch()) {
			$data .= '<option value="' . $row['id'] . '"> ' . $row['state'] . '</option>';
		}
		echo $data;
	}


	if (isset($_POST['get_fyto'])) {
		$fyid = $_POST['get_fyto'];
		$data = '<option value="" >Select Financial Year To</option>';
		$query_fy = $db->prepare("SELECT id, year FROM tbl_fiscal_year WHERE id >= :fyid");
		$query_fy->execute(array(":fyid" => $fyid));
		while ($row = $query_fy->fetch()) {
			$yrid = $row['id'];
			$data .= '<option value="' . $yrid . '"> ' . $row['year'] . '</option>';
		}
		echo $data;
	}

	if (isset($_POST['get_dept_projects'])) {
		$dept_id = $_POST['get_dept_projects'];
		$data = '<option value="" >Select Project</option>';
		$query_projects = $db->prepare("SELECT * FROM tbl_projects p INNER JOIN tbl_programs g ON g.progid = p.progid  WHERE g.projsector = :dept_id");
		$query_projects->execute(array(":dept_id" => $dept_id));

		while ($row = $query_projects->fetch()) {
			$projid = $row['projid'];
			$data .= '<option value="' . $projid . '"> ' . $row['projname'] . '</option>';
		}
		echo $data;
	}

	if (isset($_POST['get_outputs'])) {
		$projid = $_POST['projid'];
		$data = '<option value="" >Select Output</option>';
		$query_outputs = $db->prepare("SELECT d.id, g.output FROM tbl_projects p INNER JOIN tbl_progdetails g ON g.progid = p.progid INNER JOIN tbl_project_details d ON d.outputid = g.id WHERE p.projid = :projid GROUP BY d.id");
		$query_outputs->execute(array(":projid" => $projid));
		while ($row = $query_outputs->fetch()) {
			$id = $row['id'];
			$data .= '<option value="' . $id . '"> ' . $row['output'] . '</option>';
		}
		echo $data;
	}


	if (isset($_POST['get_sector'])) {
		$sector = $_POST['get_sector'];
		$data = '<option value="" >Select Indicator</option>';
		$query_fy = $db->prepare("SELECT i.indicator_name, i.indid, m.unit FROM tbl_indicator i INNER JOIN tbl_measurement_units m ON m.id  = i.indicator_unit WHERE indicator_sector = :indicator_sector AND indicator_mapping_type != 0");
		$query_fy->execute(array(":indicator_sector" => $sector));
		while ($row = $query_fy->fetch()) {
			$indid = $row['indid'];
			$data .= '<option value="' . $indid . '"> ' . $row['unit'] . " of " . $row['indicator_name'] . '</option>';
		}
		echo $data;
	}

	if (isset($_POST['get_sector_mapping_type'])) {
		$indid = $_POST['get_sector_mapping_type'];
		$query_fy = $db->prepare("SELECT indicator_mapping_type FROM tbl_indicator WHERE indid = :indid");
		$query_fy->execute(array(":indid" => $indid));
		$row = $query_fy->fetch();
		$count_rows = $query_fy->rowCount();
		$mapping_type = $count_rows > 0 ? $row['indicator_mapping_type'] : "";
		echo json_encode($mapping_type);
	}


	if (isset($_POST['project_indicators_dashboard']) && $_POST['project_indicators_dashboard'] = 1) {
		$indid = $_POST['indid'];
		$projid = $_POST['projid'];

		$query_project = $db->prepare("SELECT * FROM tbl_projects WHERE projid = :projid");
		$query_project->execute(array(":projid" => $projid));
		$row_project = $query_project->fetch();
		$project_locations = explode(",", $row_project["projstate"]);

		$query_selected_proj_ind =  $db->prepare("SELECT p.id AS projopid, p.total_target, indicator_name,indicator_description, duration,unit FROM tbl_indicator i left join tbl_measurement_units u on u.id=i.indicator_unit left join tbl_progdetails g on g.indicator=i.indid left join tbl_project_details p on p.outputid=g.id WHERE projid = :projid AND indid = :indid");
		$query_selected_proj_ind->execute(array(":projid" => $projid, ":indid" => $indid));
		$row_selected_proj_ind = $query_selected_proj_ind->fetch();

		$opid = $row_selected_proj_ind["projopid"];
		$optarget = intval($row_selected_proj_ind["total_target"]);
		$indunitofmeasure = $row_selected_proj_ind["unit"];
		$indicator_description = strip_tags($row_selected_proj_ind["indicator_description"]);
		$indicator_duration = $row_selected_proj_ind["duration"];

		$query_proj_tasks_dates =  $db->prepare("SELECT MIN(start_date) AS projstartdate, MAX(end_date) AS projenddate FROM tbl_task t left join tbl_program_of_works w on w.task_id=t.tkid WHERE t.projid = :projid");
		$query_proj_tasks_dates->execute(array(":projid" => $projid));
		$row_proj_tasks_dates = $query_proj_tasks_dates->fetch();

		$date1 = $row_proj_tasks_dates["projstartdate"];
		$date2 = $row_proj_tasks_dates["projenddate"];

		$months = [];
		$achieved = [];
		$targeted = [];
		$diff = [];

		$time   = strtotime($date1);
		$last   = date('M-Y', strtotime($date2));
		do {
			$year = date('Y', $time);
			$month = date('M-Y', $time);
			$total = date('t', $time);

			$months[] = $month;

			$time = strtotime('+1 month', $time);

			$startdate = date('Y-m-01', $time);
			$edate = new DateTime($startdate);
			$enddate = $edate->format('Y-m-t');

			$query_achieved =  $db->prepare("SELECT SUM(actualoutput) AS achieved FROM tbl_monitoringoutput WHERE projid = :projid AND opid = :opid AND (date_created >= :sdate and date_created <= :edate)");
			$query_achieved->execute(array(":projid" => $projid, ":opid" => $opid, ":sdate" => $startdate, ":edate" => $enddate));
			$row_achieved = $query_achieved->fetch();

			$targetachieved = 0;
			if (!IS_NULL($row_achieved["achieved"])) {
				$targetachieved = $row_achieved["achieved"];
			}

			$achieved[] = $targetachieved;
			$targeted[] = $optarget;
		} while ($month != $last);

		$progresstable = '
			<table class="table table-bordered text-dark">
				<caption>Indicator Progress Table</caption>
				<thead style="background-color: #a5d6a7;">
					<tr>
						<th scope="col">Location</th>
						<th scope="col">Baseline</th>
						<th scope="col">Target</th>';

		$ptime   = strtotime($date1);
		$plast   = date('M-Y', strtotime($date2));
		do {
			$pmonth = date('M-Y', $ptime);
			$total = date('t', $ptime);

			$ptime = strtotime('+1 month', $ptime);
			$progresstable .= '<th scope="col">' . $pmonth . '</th>';
		} while ($pmonth != $plast);

		$progresstable .= '<th scope="col">Rate</th>
					</tr>
				</thead>
				<tbody  class="bg-success p-2 text-dark">';

		foreach ($project_locations as $projloc) {
			$query_location =  $db->prepare("SELECT state FROM tbl_state WHERE id = :projloc");
			$query_location->execute(array(":projloc" => $projloc));
			$row_location = $query_location->fetch();
			$location = $row_location["state"];

			$query_basevalues =  $db->prepare("SELECT SUM(value) AS basevalue FROM tbl_indicator_output_baseline_values WHERE indid = :indid AND level3 = :level3");
			$query_basevalues->execute(array(":indid" => $indid, ":level3" => $projloc));
			$row_basevalues = $query_basevalues->fetch();

			$query_proj_op_target =  $db->prepare("SELECT d.total_target AS target FROM tbl_output_disaggregation d left join tbl_project_details o on o.id=d.outputid WHERE o.projid = :projid AND o.id = :opid AND d.outputstate = :location");
			$query_proj_op_target->execute(array(":projid" => $projid, ":opid" => $opid, ":location" => $projloc));
			$row_proj_op_target = $query_proj_op_target->fetch();
			$op_location_target = intval($row_proj_op_target["target"]);

			$query_achieved_basevalues =  $db->prepare("SELECT SUM(actualoutput) AS achieved FROM tbl_monitoringoutput WHERE projid = :projid AND opid = :opid AND level3 = :level3 AND date_created < :date");
			$query_achieved_basevalues->execute(array(":projid" => $projid, ":opid" => $opid, ":level3" => $projloc, ":date" => $date1));
			$row_achieved_basevalues = $query_achieved_basevalues->fetch();

			$projindbasevalue = $row_basevalues["basevalue"] + $row_achieved_basevalues["achieved"];
			$progresstable .= '<tr>
							<td scope="row">' . $location . '</td>
							<td scope="row">' . $projindbasevalue . '</td>
							<td scope="row">' . $op_location_target . '</td>';
			$totalopprogress = 0;
			$opprogress = 0;

			$date   = strtotime($date1);
			$final   = date('M-Y', strtotime($date2));
			do {
				$mnth = date('M-Y', $date);
				$total = date('t', $date);

				$date = strtotime('+1 month', $date);

				$startdate = date('Y-m-01', $date);
				$edate = new DateTime($startdate);
				$enddate = $edate->format('Y-m-t');

				$query_achieved_cumulatively =  $db->prepare("SELECT SUM(actualoutput) AS achieved FROM tbl_monitoringoutput WHERE projid = :projid AND opid = :opid AND level3 = :level3 AND (date_created >= :sdate and date_created <= :edate)");
				$query_achieved_cumulatively->execute(array(":projid" => $projid, ":opid" => $opid, ":level3" => $projloc, ":sdate" => $startdate, ":edate" => $enddate));
				$row_achieved_cumulatively = $query_achieved_cumulatively->fetch();

				$opachieved = 0;
				if (!IS_NULL($row_achieved_cumulatively["achieved"])) {
					$opachieved = $row_achieved_cumulatively["achieved"];
				}
				$progresstable .= '<td scope="row">' . $opachieved . '</td>';
			} while ($mnth != $final);
			$rate = $op_location_target != '' || !empty($op_location_target) ? ($opachieved / $op_location_target) * 100 : 0;
			$progresstable .= '<td scope="row">' . $rate . '%</td>
						</tr>';
		}
		$progresstable .= '</tbody>
			</table>';
		echo json_encode(array("inddescription" => $indicator_description, "unitofmeasure" => $indunitofmeasure, "indduration" => $indicator_duration, "progresstable" => $progresstable, "achieved" => $achieved, "targeted" => $targeted, "months" => $months, "unitofmeasure" => $indunitofmeasure));
	}
} catch (PDOException $ex) {
	echo $ex->getMessage();
}
