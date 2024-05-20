<?php
include_once "controller.php";

$valid['success'] = true;
$valid['messages'] = "";
function get_data($db, $formid)
{
	global $level2label;
	require '../../PHPMailer/PHPMailerAutoload.php';

	if ($formid != "") {
		$queryrs_projects = $db->prepare("SELECT m.startdate, m.enddate, m.enumerator_type, m.form_name, m.resultstype, d.level3, d.location_disaggregation, d.enumerators, s.id, s.state, p.projid AS proj, p.projname, p.projcode FROM tbl_indicator_baseline_survey_forms m INNER JOIN tbl_indicator_baseline_survey_details d ON d.formid = m.id INNER JOIN tbl_state s ON s.id = d.level3 INNER JOIN tbl_projects p ON p.projid = m.projid WHERE m.id = :form_id");
		$queryrs_projects->execute(array(':form_id' => $formid));
		$rows_projects = $queryrs_projects->fetchAll();
		$totalrows_rs_projects = $queryrs_projects->rowCount();

		if ($totalrows_rs_projects > 0) {
			foreach ($rows_projects as $rows_rs_projects) {
				$projid = $rows_rs_projects["proj"];
				$projname = $rows_rs_projects["projname"];
				$projcode = $rows_rs_projects["projcode"];
				$startdate = $rows_rs_projects["startdate"];
				$enddate = $rows_rs_projects["enddate"];
				$resultstype = $rows_rs_projects["resultstype"];
				$formname = $rows_rs_projects["form_name"];
				$state = $rows_rs_projects["state"];
				$level2id = $rows_rs_projects["id"];
				$enumerator_type = $rows_rs_projects["enumerator_type"];
				$receipient = $rows_rs_projects["enumerators"];
				$location_disaggregation = $rows_rs_projects["location_disaggregation"];

				$fullname = "Enumerator";

				$resultstypename = $resultstype == 1 ? "Impact " : "Outcome ";

				if ($enumerator_type == 1) {
					$query_rsteam = $db->prepare("SELECT t.email AS email, title, fullname FROM tbl_projteam2 t left join users u on u.pt_id=t.ptid WHERE userid = '$receipient'");
					$query_rsteam->execute();
					$row_rsteam = $query_rsteam->fetch();
					$totalRows_rsteam = $query_rsteam->rowCount();
					$title = $row_rsteam['title'];
					$fullname = $title . "." . $row_rsteam['fullname'];
					$receipient = $row_rsteam['email'];
				}

				$locationName = "";
				if ($location_disaggregation != NULL) {
					$query_rslocation = $db->prepare("SELECT disaggregations FROM tbl_indicator_level3_disaggregations WHERE id = '$location_disaggregation'");
					$query_rslocation->execute();
					$row_rslocation = $query_rslocation->fetch();
					$totalRows_rslocation = $query_rslocation->rowCount();
					$locationName = "Location :" . $row_rslocation['disaggregations'];
				}

				$query_url =  $db->prepare("SELECT * FROM tbl_company_settings");
				$query_url->execute();
				$row_url = $query_url->fetch();
				$url = $row_url["main_url"];
				$org = $row_url["company_name"];
				$org_email = $row_url["email_address"];

				//encode enumerator link details
				$encode_prjid = base64_encode("evprj{$projid}");
				$encode_frmid = base64_encode("evfrm{$formid}");
				$encode_emailid = base64_encode("eveml{$receipient}");
				$encode_level2id = base64_encode("evloc{$level2id}");

				$detailslink = '<a href="https://mne.projtrac.co.ke/public-survey-form?prj=' . $encode_prjid . '&fm=' . $encode_frmid . '&em=' . $encode_emailid . '&loc=' . $encode_level2id . '" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">CLICK HERE TO OPEN FORM</a>';

				$mainmessage = ' Dear ' . $fullname . ',
				<p>Please note you have been assigned to do project ' . $resultstypename . $formname . ' survey as per the details below:</p>
				<p>Project Code:' . $projcode . '<br>
				Project Name: ' . $projname . '<br>
				Survey start date: ' . $startdate . '<br>
				Survey end date : ' . $enddate . '<br>
				' . $level2label . ': ' . $state . '<br>
				' . $locationName . '
				<p>Prepare the required resources. </p>';
				$title = "Project " . $resultstypename . "Evaluation Survey";
				$subject = "Project " . $resultstypename . $formname . " Survey";
				$receipientName = $fullname;


				$mail = new Email();
				$body = $mail->email_body_template($subject, $mainmessage, $detailslink);
				$mail->sendMail($subject, $body, $receipient, $fullname, []);

				// include("email-body-orig.php");
				// include("email-conf-settings.php");
			}
		}
	}
}

if (isset($_POST['insert'])) {
	$indicatorid = $_POST['indid'];
	$projid = $_POST['projid'];
	$level2 = $_POST['level2'];
	$disaggregations = $_POST['disaggregations'];
	$form_id = $_POST['form_id'];
	$respondents_type = $_POST['respondents_type'];

	$results = false;
	$html = '';
	$data = false;

	for ($i = 0; $i < count($level2); $i++) {
		$disaggregation = explode(",", $disaggregations[$i]);
		for ($j = 0; $j < count($disaggregation); $j++) {
			$query_rsInsert = $db->prepare("INSERT INTO tbl_indicator_level3_disaggregations(projid, form_id, indicatorid, level3, disaggregations)  VALUES (:projid,:form_id,:indicatorid, :level2, :disaggregations)");
			$results = $query_rsInsert->execute(array(":projid" => $projid, ":form_id" => $form_id, ":indicatorid" => $indicatorid, ":level2" => $level2[$i], ":disaggregations" => $disaggregation[$j]));
		}
	}

	if ($results) {
		$data = true;
		$query_projects = $db->prepare("SELECT * FROM tbl_projects WHERE  projid=:projid ");
		$query_projects->execute(array(":projid" => $projid));
		$count_projects = $query_projects->rowCount();
		$rows_projects = $query_projects->fetch();
		$level2 = explode(",", $rows_projects['projlga']);
		$pcounter = 0;
		for ($i = 0; $i < count($level2); $i++) {
			$level2id = $level2[$i];
			$pcounter++;
			$query_rsComm =  $db->prepare("SELECT id, state FROM tbl_state WHERE id=:level2");
			$query_rsComm->execute(array(":level2" => $level2id));
			$row_rsComm = $query_rsComm->fetch();
			$totalRows_rsComm = $query_rsComm->rowCount();
			$state = $row_rsComm['state'];
			$html .=
				'<tr>
					<th width="10%">
						' . $pcounter . '
					</th>
					<th width="30%">
						' . $state . '
						<input name="level2[]" type="hidden" id="level2" value="' . $level2id . '" />
						<input name="directben[]" type="hidden" id="directben" value="" />
					</th>
				</tr>';
			$query_rs_level2 = $db->prepare("SELECT * FROM tbl_indicator_level3_disaggregations WHERE indicatorid = :indicatorid AND  projid=:projid AND form_id=:form_id AND level3=:level2");
			$query_rs_level2->execute(array(":indicatorid" => $indicatorid, ":projid" => $projid, ":form_id" => $form_id, ":level2" => $level2id));
			$row_rs_level2 = $query_rs_level2->fetch();
			$totalRows_rs_level2 = $query_rs_level2->rowCount();
			if ($totalRows_rs_level2 > 0) {
				$counter = 0;
				do {
					$counter++;
					$disaggregations = $row_rs_level2['disaggregations'];
					$id = $row_rs_level2['id'];
					$html .=
						'<tr>
						<td width="10%">
							' . $pcounter . '.' . $counter . '
						</td>
						<td width="30%">
							' . $disaggregations . '
							<input name="disaggregation' . $level2id . '[]" type="hidden" id="disaggregation" value="' . $id . '" />
						</td>
						<td width="30%">
							<div class="form-input">';
					if ($respondents_type == 1) {
						$html .= '
										<select name="enumerators' . $level2id . '[]" class="form-control show-tick" id="enumerators" style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="true" required>
											<option value="">.... Select ....</option>';
						$query_rsMembers = $db->prepare("SELECT *  FROM tbl_projmembers WHERE projid=:projid");
						$query_rsMembers->execute(array(":projid" => $projid));
						$row_rsMembers = $query_rsMembers->fetch();
						$totalRows_rsMembers = $query_rsMembers->rowCount();
						do {
							$ptid = $row_rsMembers['ptid'];
							$query_rsTeam = $db->prepare("SELECT *  FROM tbl_projteam2 WHERE ptid=:ptid ");
							$query_rsTeam->execute(array(":ptid" => $ptid));
							$row_rsTeam = $query_rsTeam->fetch();
							$totalRows_rsTeam = $query_rsTeam->rowCount();
							$html .=
								'<option value="' . $row_rsTeam['ptid'] . '">' . $row_rsTeam['fullname'] . '</option>';
						} while ($row_rsMembers = $query_rsMembers->fetch());
						$html .=
							'</select>';
					} else {
						$html .= '<input type="email" name="enumerators' . $level2id . '[]" id="mail" placeholder="example@mail.com" class="form-control" style="border:#CCC thin solid; border-radius: 5px" required>';
					}
					$html .=
						'</div>
						</td>
					</tr>';
				} while ($row_rs_level2 = $query_rs_level2->fetch());
			}
		}
	}
	echo json_encode(array("msg" => $data, "html" => $html));
}

if (isset($_POST['empty_data'])) {
	$formid = $_POST['formid'];
	$deleteQuery = $db->prepare("DELETE FROM tbl_indicator_baseline_survey_details WHERE  formid=:form_id");
	$results1 = $deleteQuery->execute(array(':form_id' => $formid));

	$deleteQuery = $db->prepare("DELETE FROM tbl_indicator_level3_disaggregations WHERE  form_id=:form_id");
	$results1 = $deleteQuery->execute(array(':form_id' => $formid));

	if ($results1) {
		$valid['success'] = true;
		$valid['messages'] = "Successfully deleted";
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error in deleting data";
	}
	echo json_encode($valid);
}

if ((isset($_POST["MM_insert"]))) {
	$indid = $_POST['indid'];
	$projid = $_POST['projid'];
	$formid = $_POST['form_id'];
	$created_by = $_POST['user_name'];
	$date_created = date("Y-m-d");

	if (isset($_POST['directben']) && !empty($_POST['directben'])) {
		$state = $_POST['level2'];
		for ($i = 0; $i < count($state); $i++) {
			$level2 = $state[$i];
			$disaggregations = $_POST['disaggregation' . $level2];
			$enumerators = $_POST['enumerators' . $level2];
			for ($j = 0; $j < count($disaggregations); $j++) {
				$diss = $disaggregations[$j];
				$enumerators = $enumerators[$j];
				$insertBeneficiary = $db->prepare("INSERT INTO tbl_indicator_baseline_survey_details(formid, level3,location_disaggregation, enumerators, added_by,date_added) VALUES(:formid, :level2,:diss,:enumerators, :created_by, :date_created)");
				$result2  = $insertBeneficiary->execute(array(":formid" => $formid, ":level2" => $level2, ":diss" => $diss, ":enumerators" => $enumerators, ":created_by" => $created_by, ":date_created" => $date_created));
			}
		}

		if ($result2) {
			$status = 2;
			$insertBeneficiary = $db->prepare("UPDATE tbl_indicator_baseline_survey_forms SET status=:status, date_deployed=:date_created WHERE id=:formid");
			$result2  = $insertBeneficiary->execute(array(":status" => $status, ":date_created" => $date_created, ":formid" => $formid));

			if ($result2) {
				$valid['success'] = true;
				$valid['messages'] = "Successfully created";
			} else {
				$valid['success'] = false;
				$valid['messages'] = "Could not create data";
			}
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Could not create data";
		}
	}

	if (isset($_POST['indirectben']) && !empty($_POST['indirectben'])) {
		//$state =$_POST['level2'];
		//$enumerators =$_POST['enumerators'];

		for ($j = 0; $j < count($_POST['enumerators']); $j++) {
			$enumerators = $_POST['enumerators'][$j];
			$level2 = $_POST['level2'][$j];
			$insertBeneficiary = $db->prepare("INSERT INTO tbl_indicator_baseline_survey_details(formid, level3, enumerators, added_by, date_added) VALUES(:formid, :level2, :enumerators, :created_by, :date_created)");
			$result2  = $insertBeneficiary->execute(array(":formid" => $formid, ":level2" => $level2, ":enumerators" => $enumerators, ":created_by" => $created_by, ":date_created" => $date_created));
		}

		if ($result2) {
			$status = 2;
			$insertBeneficiary = $db->prepare("UPDATE tbl_indicator_baseline_survey_forms SET status=:status, date_deployed=:date_created WHERE id=:formid");
			$result2  = $insertBeneficiary->execute(array(":status" => $status, ":date_created" => $date_created, ":formid" => $formid));

			if ($result2) {
				$valid['success'] = true;
				$valid['messages'] = "Successfully created";
			} else {
				$valid['success'] = false;
				$valid['messages'] = "Could not create data";
			}
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Could not create data";
		}
	}
	get_data($db, $formid);
	echo json_encode($valid);
}

if (isset($_GET['more'])) {
	$formid = $_GET['formid'];
	$query_baseline = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms f  INNER JOIN tbl_indicator_baseline_survey_details d on d.formid=f.id INNER JOIN tbl_project_evaluation_submission s ON s.formid=f.id WHERE f.id=:formid GROUP BY d.enumerators ORDER BY f.id ASC");

	$query_baseline->execute(array(":formid" => $formid));
	$count_baseline = $query_baseline->rowCount();

	$data = '
	<div class="table-responsive">
		<table class="table table-bordered table-striped table-hover" id="manageItemTable">
			<thead>
				<tr>
					<th width="5%">#</th>
					<th width="75%">Responsible</th>
					<th width="20%">Submissions</th>
				</tr>
			</thead>
			<tbody>';

	if ($count_baseline > 0) {
		$project_counter = 0;
		$deploy_counter = 0;
		while ($rows_baseline = $query_baseline->fetch()) {
			$submissions = 0;
			$projid = $rows_baseline["projid"];
			$query_projects = $db->prepare("SELECT * FROM tbl_projects WHERE  projid=:projid ");
			$query_projects->execute(array(":projid" => $projid));
			$rows_projects = $query_projects->fetch();
			$projname = $rows_projects["projname"];
			$startdate = $rows_baseline["startdate"];
			$enddate = $rows_baseline["enddate"];
			$status = $rows_baseline["status"];
			$form_name = $rows_baseline["form_name"];
			$enumerator_type = $rows_baseline["enumerator_type"];
			$formid = $rows_baseline["formid"];
			$enumerator = $rows_baseline["enumerators"];
			$email = $rows_baseline["email"];

			$fullname = $enumerator;
			$enumeratoremail = $enumerator;

			if ($enumerator_type == 1) {
				$query_rsteam = $db->prepare("SELECT * FROM tbl_projteam2 WHERE email = '$email'");
				$query_rsteam->execute();
				$row_rsteam = $query_rsteam->fetch();
				$totalRows_rsteam = $query_rsteam->rowCount();
				$fullname = $row_rsteam['fullname'];
				$enumeratoremail = $email;
			}

			$deploy_counter++;
			$projstatus = $rows_projects['projstatus'];
			$projdate = date('d-m-Y');

			$query_count = $db->prepare("SELECT id FROM tbl_project_evaluation_submission WHERE formid=:formid AND email = :email");
			$query_count->execute(array(":formid" => $formid, ":email" => $enumeratoremail));
			$submissions = $query_count->rowCount();

			$data .=  '
						<tr class="bg-orange">
							<td style="width:5%">' . $deploy_counter . '</td>
							<td style="width:75%">' . $fullname . '</td>
							<td style="width:20%">' . $submissions . '</td>
						</tr>';
		}
	} else {
		$data .=  '<td colspan="3">No form submisissions found!!</td>';
	}
	$data .= '
			</tbody>
		</table>
	</div>';
	echo $data;
}
