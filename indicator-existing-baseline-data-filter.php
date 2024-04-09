<?php
include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';
include_once("system-labels.php");

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

function level1($comm)
{
	try {
		global $db;
		$query_ward = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:comm ");
		$query_ward->execute(array(":comm" => $comm));
		$data = 0;
		while ($row = $query_ward->fetch()) {
			$projlga = $row['id'];
			$query_rsLocations = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:id");
			$query_rsLocations->execute(array(":id" => $projlga));
			$row_rsLocations = $query_rsLocations->fetch();
			$total_locations = $query_rsLocations->rowCount();
			if ($total_locations > 0) {
				$data += 1;
			}
		}
		return $data;
	} catch (\PDOException $th) {
		customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
	}
}

try {
	//code...

if (isset($_POST['getward'])) {
	$getward = explode(",", $_POST['getward']);
	$data = '';
	for ($i = 0; $i < count($getward); $i++) {
		$query_rsComm = $db->prepare("SELECT id, state FROM tbl_state WHERE parent IS NULL AND id=:getward LIMIT 1");
		$query_rsComm->execute(array(":getward" => $getward[$i]));
		$row_rsComm = $query_rsComm->fetch();
		$community = $row_rsComm['state'];

		$query_ward = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:getward");
		$query_ward->execute(array(":getward" => $getward[$i]));
		$data .= '<optgroup label="' . $community . '"> ';
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
		$data .= '<optgroup>';
	}
	echo $data;
}




if (isset($_POST['get_all'])) {
	$indid = $_POST['indid'];
	$body = "";
	$query_rsIndicator =  $db->prepare("SELECT * FROM tbl_indicator WHERE indid = :indid and active = '1'");
	$query_rsIndicator->execute(array(":indid" => $indid));
	$row_rsIndicator = $query_rsIndicator->fetch();
	$indicator_level = $row_rsIndicator ? $row_rsIndicator['indicator_baseline_level'] : 0;

	$level = $indicator_level == 1 ? 1 : 0;
	$query_location_level1 = $db->prepare("SELECT * FROM tbl_state WHERE level=:level AND parent IS NULL ORDER BY state ASC");
	$query_location_level1->execute(array(":level" =>  $level));
	$row_level1 = $query_location_level1->rowCount();
	$nm = 0;
	$total_amount = 0;
	while ($rows_level1 = $query_location_level1->fetch()) {
		$nm++;
		$sr = 0;
		$lv1id = $rows_level1["id"];
		$level1 = $rows_level1["state"];

		$query_lvtwosum = $db->prepare("SELECT sum(value) AS sumvalue FROM tbl_indicator_output_baseline_values b inner join tbl_state s on s.id=b.level3 WHERE indid=:indid and parent=:parent");
		$query_lvtwosum->execute(array(":indid" => $indid, ":parent" => $lv1id));
		$row_lvtwosum = $query_lvtwosum->fetch();
		$lvonesum = $row_lvtwosum["sumvalue"] != null ? $row_lvtwosum["sumvalue"] : 0;


		$body .=  '
		<tr style="background-color:#607D8B; color:#FFF">
			<td>' . $nm . '</td>
			<td>' . $level1 . ' ' . $level1label . '</td>
			<td align="center">' . number_format($lvonesum, 0) . '</td>
			<input type="hidden" name="lvid[]" value="' . $lv1id . '">
		</tr>';
		$query_location_level2 =  $db->prepare("SELECT * FROM tbl_state WHERE parent = '$lv1id' and state NOT LIKE 'All%' ORDER BY state ASC");
		$query_location_level2->execute();
		while ($rows_level2 = $query_location_level2->fetch()) {
			$sr++;
			$lv2id = $rows_level2["id"];
			$level2 = $rows_level2["state"];

			$query_rstotal = $db->prepare("SELECT * FROM tbl_indicator_output_baseline_values WHERE indid=:indid and level3=:lv2id ");
			$query_rstotal->execute(array(":indid" => $indid, ":lv2id" => $lv2id));
			$row_rstotal = $query_rstotal->fetch();
			$row_rstotals = $query_rstotal->rowCount();
			$base_val = ($row_rstotals > 0) ? $row_rstotal['value'] : 0;
			$total_amount += $base_val;


			$query_rstotal = $db->prepare("SELECT * FROM tbl_indicator_output_baseline_values WHERE indid='$indid' and level3='$lv2id' ");
			$query_rstotal->execute();
			$row_rstotal = $query_rstotal->fetch();
			$row_rstotals = $query_rstotal->rowCount();
			if ($row_rstotals > 0) {
				$base_val = $row_rstotal['value'];
				$body .=  '
					<tr style="background-color:#f2fcf5">
						<td>' . $nm . '.' . $sr . '</td>
						<td>' . $level2 . ' ' . $level2label . '</td>
						<td>
							<input id="level2' . $lv2id . '" type="hidden" value="' . $level2 . '" />
							<button type="button" class="btn bg-light-green btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_inddiss_inputs(' . $lv2id . ',' . $base_val . ')">
								Edit Baseline Value (' . $base_val . ')
							</button>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<button type="button" class="btn bg-red btn-block btn-xs waves-effect" onclick="delete_baseline(' . $lv2id . ')">
								Delete Baseline Value
							</button>
						</td>
					</tr>';
			} else {
				$body .= '
					<tr style="background-color:#f2fcf5">
						<td>' . $nm . '.' . $sr . '</td>
						<td>' . $level2 . ' ' . $level2label . '</td>
						<td>
							<input id="level2' . $lv2id . '" type="hidden" value="' . $level2 . '" />
							<button type="button" class="btn bg-blue btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_inddiss_inputs(' . $lv2id . ',   \'\')">Add Baseline Value
							</button>
						</td>
					</tr>';
			}
		}
	}

	echo  json_encode(array("body" => $body, "total_amount" => $total_amount));
}

if (isset($_POST['subcountydata'])) {
	$indid = $_POST['indid'];
	$query_rsIndicator =  $db->prepare("SELECT * FROM tbl_indicator WHERE indid = :indid and active = '1'");
	$query_rsIndicator->execute(array(":indid" => $indid));
	$row_rsIndicator = $query_rsIndicator->fetch();
	$diss = $row_rsIndicator['indicator_disaggregation'];
	$unit = $row_rsIndicator['indicator_unit'];
	$indicator_level = $row_rsIndicator['indicator_baseline_level'];
	$baseline = $row_rsIndicator['baseline'];

	$subcounty_id = $_POST['subcountydata'];
	$total_subcounties = count($subcounty_id);
	$body = "";
	$nm = 0;
	$total_amount = 0;

	for ($j = 0; $j < $total_subcounties; $j++) {
		$query_location_level1 = $db->prepare("SELECT * FROM tbl_state WHERE id='$subcounty_id[$j]'");
		$query_location_level1->execute();
		$rows_level1 = $query_location_level1->fetch();
		$totalrows_level1 = $query_location_level1->rowCount();
		if ($totalrows_level1 > 0) {
			$sr = 0;
			$nm++;

			$lv1id = $rows_level1["id"];
			$level1 = $rows_level1["state"];
			$chandler =  level1($lv1id);
			$query_lvtwosum = $db->prepare("SELECT sum(value) AS sumvalue FROM tbl_indicator_output_baseline_values b inner join tbl_state s on s.id=b.level3 WHERE indid=:indid and parent=:parent");
			$query_lvtwosum->execute(array(":indid" => $indid, ":parent" => $lv1id));
			$row_lvtwosum = $query_lvtwosum->fetch();
			$lvonesum = $row_lvtwosum["sumvalue"] != null ? $row_lvtwosum["sumvalue"] : 0;

			$total_amount += $lvonesum;

			$body .=  '
				<tr style="background-color:#607D8B; color:#FFF">
					<td>' . $nm . '</td>
					<td>' . $level1 . ' ' . $level1label . '</td>
					<td align="center">' . number_format($lvonesum, 0) . '</td>
					<input type="hidden" name="lvid[]" value="' . $lv1id . '">
				</tr>';
			$query_location_level2 =  $db->prepare("SELECT * FROM tbl_state WHERE parent = '$lv1id' and state NOT LIKE 'All%' ORDER BY state ASC");
			$query_location_level2->execute();
			while ($rows_level2 = $query_location_level2->fetch()) {
				$sr++;
				$lv2id = $rows_level2["id"];
				$level2 = $rows_level2["state"];

				$query_rstotal = $db->prepare("SELECT * FROM tbl_indicator_output_baseline_values WHERE indid=:indid and level3=:lv2id ");
				$query_rstotal->execute(array(":indid" => $indid, ":lv2id" => $lv2id));
				$row_rstotal = $query_rstotal->fetch();
				$row_rstotals = $query_rstotal->rowCount();
				$base_val = ($row_rstotals > 0) ? $row_rstotal['value'] : 0;


				$query_rstotal = $db->prepare("SELECT * FROM tbl_indicator_output_baseline_values WHERE indid='$indid' and level3='$lv2id' ");
				$query_rstotal->execute();
				$row_rstotal = $query_rstotal->fetch();
				$row_rstotals = $query_rstotal->rowCount();
				if ($baseline == 0) {
					if ($row_rstotals > 0) {
						$base_val = $row_rstotal['value'];
						$body .=  '
							<tr style="background-color:#f2fcf5">
								<td>' . $nm . '.' . $sr . '</td>
								<td>' . $level2 . ' ' . $level2label . '</td>
								<td>
									<input id="level2' . $lv2id . '" type="hidden" value="' . $level2 . '" />
									<button type="button" class="btn bg-light-green btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_inddiss_inputs(' . $lv2id . ',   ' . $base_val . ')">
										Edit Baseline Value (' . $base_val . ')
									</button>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<button type="button" class="btn bg-red btn-block btn-xs waves-effect" onclick="delete_baseline(' . $lv2id . ')">
										Delete Baseline Value
									</button>
								</td>
							</tr>';
					} else {
						$body .= '
							<tr style="background-color:#f2fcf5">
								<td>' . $nm . '.' . $sr . '</td>
								<td>' . $level2 . ' ' . $level2label . '</td>
								<td>
									<input id="level2' . $lv2id . '" type="hidden" value="' . $level2 . '" />
									<button type="button" class="btn bg-blue btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_inddiss_inputs(' . $lv2id . ',   \'\')">Add Baseline Value
									</button>
								</td>
							</tr>';
					}
				}
			}
		}
	}
	echo  json_encode(array("body" => $body, "total_amount" => $total_amount));
}

if (isset($_POST['warddata'])) {
	$body = "";
	$indid = $_POST['indid'];
	$subcounty_id = $_POST['subcounty_id'];
	$query_rsIndicator =  $db->prepare("SELECT * FROM tbl_indicator WHERE indid = :indid and active = '1'");
	$query_rsIndicator->execute(array(":indid" => $indid));
	$row_rsIndicator = $query_rsIndicator->fetch();
	$diss = $row_rsIndicator['indicator_disaggregation'];
	$unit = $row_rsIndicator['indicator_unit'];
	$indicator_level = $row_rsIndicator['indicator_baseline_level'];
	$baseline = $row_rsIndicator['baseline'];

	$total_subcounties = count($subcounty_id);

	$nm = 0;

	$total_amount = 0;
	for ($j = 0; $j < $total_subcounties; $j++) {
		$warddata = $_POST['warddata'];
		$total_wards = count($warddata);
		$query_location_level1 = $db->prepare("SELECT * FROM tbl_state WHERE id='$subcounty_id[$j]' AND  state NOT LIKE 'All%' AND parent IS NULL ORDER BY state ASC");
		$query_location_level1->execute();
		$rows_level1 = $query_location_level1->fetch();
		$totalrows_level1 = $query_location_level1->rowCount();

		if ($totalrows_level1 > 0) {
			$xy = 0;
			$lv1id = $rows_level1["id"];
			$level1 = $rows_level1["state"];

			$query_lvtwosum = $db->prepare("SELECT sum(value) AS sumvalue FROM tbl_indicator_output_baseline_values b inner join tbl_state s on s.id=b.level3 WHERE indid=:indid and parent=:parent");
			$query_lvtwosum->execute(array(":indid" => $indid, ":parent" => $lv1id));
			$row_lvtwosum = $query_lvtwosum->fetch();
			$lvonesum = $row_lvtwosum["sumvalue"] != null ? $row_lvtwosum["sumvalue"] : 0;
			$nm++;
			$body .=  '
			<tr style="background-color:#607D8B; color:#FFF">
				<td>' . $nm . '</td>
				<td>' . $level1 . ' ' . $level1label . '</td>
				<td align="center">' . number_format($lvonesum, 0) . '</td>
				<input type="hidden" name="lvid[]" value="' . $lv1id . '">
			</tr>';
			$sr = 0;
			for ($k = 0; $k < $total_wards; $k++) {
				$query_location_level2 =  $db->prepare("SELECT * FROM tbl_state WHERE  id='$warddata[$k]' AND  parent = '$lv1id' and state NOT LIKE 'All%' ORDER BY state ASC");
				$query_location_level2->execute();
				$rows_level2 = $query_location_level2->fetch();
				$rows_level2_total = $query_location_level2->rowCount();
				if ($rows_level2_total > 0) {
					$level = ($indicator_level == 1) ? 1 : 0;
					$sr++;
					$lv2id = $rows_level2["id"];
					$level2 = $rows_level2["state"];

					$query_rstotal = $db->prepare("SELECT * FROM tbl_indicator_output_baseline_values WHERE indid='$indid' and level3='$lv2id' ");
					$query_rstotal->execute();
					$row_rstotal = $query_rstotal->fetch();
					$row_rstotals = $query_rstotal->rowCount();
					if ($baseline == 0) {
						if ($row_rstotals > 0) {
							$base_val = $row_rstotal['value'];
							$total_amount += $base_val;
							$body .=  '
							<tr style="background-color:#f2fcf5">
								<td>' . $nm . '.' . $sr . '</td>
								<td>' . $level2 . ' ' . $level2label . '</td>
								<td>
									<input id="level2' . $lv2id . '" type="hidden" value="' . $level2 . '" />
									<button type="button" class="btn bg-light-green btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_inddiss_inputs(' . $lv2id . ',  ' . $base_val . ')">
										Edit Baseline Value (' . $base_val . ')
									</button>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<button type="button" class="btn bg-red btn-block btn-xs waves-effect" onclick="delete_baseline(' . $lv2id . ')">
										Delete Baseline Value
									</button>
								</td>
							</tr>';
						} else {
							$body .= '
							<tr style="background-color:#f2fcf5">
								<td>' . $nm . '.' . $sr . '</td>
								<td>' . $level2 . ' ' . $level2label . '</td>
								<td>
									<input id="level2' . $lv2id . '" type="hidden" value="' . $level2 . '" />
									<button type="button" class="btn bg-blue btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_inddiss_inputs(' . $lv2id . ', \'\')">Add Baseline Value
									</button>
								</td>
							</tr>';
						}
					}
				}
			}
		}
	}

	echo  json_encode(array("body" => $body, "total_amount" => $total_amount));
}

if (isset($_POST['get_baseline_ind'])) {
	$indid = $_POST['get_baseline_ind'];
	$query_lvonesum = $db->prepare("SELECT sum(value) AS sumvalue FROM tbl_indicator_output_baseline_values WHERE indid=:indid");
	$query_lvonesum->execute(array(":indid" => $indid));
	$row_lvonesum = $query_lvonesum->fetch();
	echo ($row_lvonesum["sumvalue"] != null) ?  $row_lvonesum["sumvalue"] : 0;
}
} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}