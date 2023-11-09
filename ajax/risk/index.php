<?php
include '../controller.php';
		// tbl_project_risks tbl_project_risk_strategic_measures tbl_project_risk_details
try {
    if (isset($_POST['get_severity'])) {
        $impact = $_POST['impact'];
        $probability = $_POST['probability'];
		$severity = $impact * $probability;
        $severityclass = '';

        $query_severity = $db->prepare("SELECT * FROM tbl_risk_severity WHERE digit = :severity");
        $query_severity->execute(array(":severity" => $severity));
		$row_severity = $query_severity->fetch();
        $total_severity = $query_severity->rowCount();

        if ($total_severity > 0) {
            $severityclass = $row_severity['color'];
			$severitydesc = $row_severity['description'];
        }
        echo json_encode(["severityclass" => $severityclass, "severityvalue" => $severity, "severitydesc" => $severitydesc]);
    }

    if (isset($_POST['store_risk'])) {
        $current_date = date("Y-m-d");
        $projid = $_POST['projid'];
        $risk_description = $_POST['risk'];
        $risk_category = $_POST['risk_category'];
        $likelihood = $_POST['likelihood'];
        $impact = $_POST['impact'];
        $risk_level = $_POST['risk_level'];
        $username = $_POST['user_name'];

        // $deleteQuery = $db->prepare("DELETE FROM `tbl_project_risks` WHERE projid=:projid");
        // $results = $deleteQuery->execute(array(':projid' => $projid));

        // // delete tbl_project_output_details table
        // $deleteQuery = $db->prepare("DELETE FROM `tbl_project_risk_strategic_measures` WHERE projid=:projid");
        // $results = $deleteQuery->execute(array(':projid' => $projid));

		$insert_risk = $db->prepare("INSERT INTO tbl_project_risks (projid, risk_description, risk_category, likelihood, impact, risk_level, created_by, date_created) VALUES (:projid, :risk_description, :risk_category, :likelihood, :impact, :risk_level, :user_name, :date_entered)");
		$risk_results = $insert_risk->execute(array(':projid' => $projid, ":risk_description" => $risk_description, ':risk_category' => $risk_category, ':likelihood' => $likelihood, ':impact' => $impact, ':risk_level' => $risk_level, ':user_name' => $username, ':date_entered' => $current_date));

		$success_status = false;
		$message = "Failed to create the risk!!";

        if ($risk_results) {
			$riskid = $db->lastInsertId();
            $measures = $_POST['strategic_measure'];
            $total_measures = count($measures);
            for ($i = 0; $i < $total_measures; $i++) {
                $strategic_measure = $measures[$i];

                $insert_strategic_measures = $db->prepare("INSERT INTO tbl_project_risk_strategic_measures (projid, riskid, strategic_measure) VALUES (:projid,:riskid,:strategic_measure)");
                $insert_strategic_measures->execute(array(':projid' => $projid, ":riskid" => $riskid, ':strategic_measure' => $strategic_measure));
            }
			$success_status = true;
			$message = "Risk created successfully";
        }
        echo json_encode(array("success" => $success_status, "message" => $message));
    }

    if (isset($_POST['store_responsible'])) {
        $current_date = date("Y-m-d");
        $projid = $_POST['projid'];
        $responsible = $_POST['responsible'];
        $frequency = $_POST['frequency'];
        $username = $_POST['user_name'];

		$insert_risk_details = $db->prepare("INSERT INTO tbl_project_risk_details (projid, responsible, frequency, created_by, date_created) VALUES (:projid, :responsible, :frequency, :user_name, :date_entered)");
		$risk_results = $insert_risk_details->execute(array(':projid' => $projid, ":responsible" => $responsible, ':frequency' => $frequency, ':user_name' => $username, ':date_entered' => $current_date));

		$success_status = false;
		$message = "Failed to update the Risk Responsible's details!!";

        if ($risk_results) {
			$success_status = true;
			$message = "Risk Responsible details successfully updated";
        }
        echo json_encode(array("success" => $success_status, "message" => $message));
    }

	if(isset($_GET['edit_risk'])){
		$riskid = $_GET['riskid'];
		$query_risk_details = $db->prepare("SELECT r.risk_description AS risk, c.category AS cat, c.catid, i.id AS impactid, i.description AS impact, p.id AS likelihoodid, p.description AS likelihood, s.digit AS risk_digit, s.description AS level, s.color AS color FROM tbl_project_risks r left join tbl_projrisk_categories c on c.catid=r.risk_category left join tbl_risk_impact i on i.id=r.impact left join tbl_risk_probability p on p.id=r.likelihood left join tbl_risk_severity s on s.digit=r.risk_level WHERE r.id=:riskid");
		$query_risk_details->execute(array(":riskid" =>$riskid));
		$row_risk_details = $query_risk_details->fetch();
        $total_risk_details = $query_risk_details->rowCount();

		$query_risk_measures = $db->prepare("SELECT * FROM tbl_project_risk_strategic_measures WHERE riskid=:riskid");
		$query_risk_measures->execute(array(":riskid" =>$riskid));
		$total_risk_measures = $query_risk_measures->rowCount();

		$query_risk_categories = $db->prepare("SELECT * FROM tbl_projrisk_categories");
		$query_risk_categories->execute();

		$query_risk_likelihood = $db->prepare("SELECT * FROM tbl_risk_probability where active=1");
		$query_risk_likelihood->execute();

		$query_risk_impact = $db->prepare("SELECT * FROM tbl_risk_impact where active=1");
		$query_risk_impact->execute();

		$query_risk_strategy = $db->prepare("SELECT * FROM tbl_risk_strategy where active=1");
		$query_risk_strategy->execute();

		$query_risk_monitoring_frequency = $db->prepare("SELECT * FROM tbl_datacollectionfreq where status=1");
		$query_risk_monitoring_frequency->execute();

		$query_risk_responsible = $db->prepare("SELECT *, tt.title AS user_title FROM users u left join tbl_projteam2 t on t.ptid=u.pt_id left join tbl_titles tt on tt.id=t.title where t.disabled=0");
		$query_risk_responsible->execute();

		$risk_measures = $risk_details = '';
		if($total_risk_details > 0){
			$risk = $row_risk_details["risk"];
			$catid = $row_risk_details["catid"];
			$likelihoodid = $row_risk_details["likelihoodid"];
			$impactid = $row_risk_details["impactid"];
			$risk_digit = $row_risk_details["risk_digit"];
			$category = $row_risk_details["cat"];
			$impact = $row_risk_details["impact"];
			$likelihood = $row_risk_details["likelihood"];
			$risk_level = $row_risk_details["level"];
			$risk_color = $row_risk_details["color"];
			$risk_details = '
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
				<div class="form-inline">
					<label for="">Risk Description</label>
					<input name="risk" type="text" class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:100%" value="'.$risk.'" placeholder="Describe the risk" required>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px">
				<div class="form-inline">
					<label for="">Risk Category</label>
					<select name="risk_category" class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
						<option value="">.... Select Category ....</option>';
						while ($row_risk_categories = $query_risk_categories->fetch()) {
							$selected = $catid == $row_risk_categories['catid'] ? "selected" : "";
							$risk_details .= '<font color="black">
								<option value="'.$row_risk_categories['catid'].'" '.$selected.'>'.$row_risk_categories['category'].'</option>
							</font>';
						}
					$risk_details .= '</select>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px">
				<div class="form-inline">
					<label for="">Risk Likelihood</label>
					<select name="likelihood" id="likelihood" class="form-control require" onchange="riskseverity()" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
						<option value="">.... Select Likelihood ....</option>';
						while ($row_risk_likelihood = $query_risk_likelihood->fetch()) {
							$selected = $likelihoodid == $row_risk_likelihood['id'] ? "selected" : "";
							$risk_details .= '<font color="black">
								<option value="'.$row_risk_likelihood['id'].'" '.$selected.'>'.$row_risk_likelihood['description'].'</option>
							</font>';
						}
					$risk_details .= '</select>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px">
				<div class="form-inline">
					<label for="">Risk Impact</label>
					<select name="impact" id="impact" class="form-control require" onchange="riskseverity()" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
						<option value="">.... Select Impact ....</option>';
						while ($row_risk_impact = $query_risk_impact->fetch()) {
							$selected = $impactid == $row_risk_impact['id'] ? "selected" : "";
							$risk_details .= '<font color="black">
								<option value="'.$row_risk_impact['id'].'" '.$selected.'>'.$row_risk_impact['description'].'</option>
							</font>';
						}
					$risk_details .= '</select>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px">
				<div class="form-inline">
					<label for="">Risk Level</label>
					<input name="risk_level" type="hidden" id="severity" value="'.$risk_digit.'" required>
					<div id="severityname" class="require '.$risk_color.'" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px; width:98%">'.$risk_level.'</div>
				</div>
			</div>';
		}

		if($total_risk_measures > 0){
			$measuresCount = 0;
			$measures = '';
			while($row_risk_measures = $query_risk_measures->fetch()){
				$measuresCount++;
				$measure = $row_risk_measures["strategic_measure"];
				$measures .='
				<tr>
					<td>'.$measuresCount.'</td>
					<td>
						<input type="text" name="strategic_measure[]" id="taskrow'.$measuresCount.'" placeholder="Describe the Strategic Measure" class="form-control parameter" value="'.$measure.'" required/>
					</td>
					<td>
						<button type="button" class="btn btn-danger btn-sm" id="delete" onclick="delete_row_Items(m_row'.$measuresCount.', 2)">
							<span class="glyphicon glyphicon-minus"></span>
						</button>
					</td>
				</tr>';
			}
			$risk_measures = $measures;
		}
        echo json_encode(["risk_more_info_body" => $risk_details, "risk_measures" => $risk_measures]);
	}

	if(isset($_GET['risk_more_info'])){
		$riskid = $_GET['riskid'];
		$query_risk_details = $db->prepare("SELECT r.risk_description AS risk, c.category AS cat, i.description AS impact, p.description AS likelihood, s.description AS level, s.color AS color FROM tbl_project_risks r left join tbl_projrisk_categories c on c.catid=r.risk_category left join tbl_risk_impact i on i.id=r.impact left join tbl_risk_probability p on p.id=r.likelihood left join tbl_risk_severity s on s.digit=r.risk_level WHERE r.id=:riskid");
		$query_risk_details->execute(array(":riskid" =>$riskid));
		$row_risk_details = $query_risk_details->fetch();
        $total_risk_details = $query_risk_details->rowCount();

		$query_risk_measures = $db->prepare("SELECT * FROM tbl_project_risk_strategic_measures WHERE riskid=:riskid");
		$query_risk_measures->execute(array(":riskid" =>$riskid));
		$total_risk_measures = $query_risk_measures->rowCount();

		$risk_measures = $risk_more_info = '';
		if($total_risk_details > 0){
			$risk = $row_risk_details["risk"];
			$category = $row_risk_details["cat"];
			$impact = $row_risk_details["impact"];
			$likelihood = $row_risk_details["likelihood"];
			$risk_level = $row_risk_details["level"];
			$risk_color = $row_risk_details["color"];
			$risk_more_info = '
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
				<div class="form-inline">
					<label for="">Risk Description</label>
					<div id="severityname" class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px; width:98%">'.$risk.'</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px">
				<div class="form-inline">
					<label for="">Risk Category</label>
					<div id="severityname" class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px; width:98%">'.$category.'</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px">
				<div class="form-inline">
					<label for="">Risk Likelihood</label>
					<div id="severityname" class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px; width:98%">'.$likelihood.'</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px">
				<div class="form-inline">
					<label for="">Risk Impact</label>
					<div id="severityname" class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px; width:98%">'.$impact.'</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px">
				<div class="form-inline">
					<label for="">Risk Level</label>
					<div id="severityname" class="require '.$risk_color.'" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px; width:98%">'.$risk_level.'</div>
				</div>
			</div>';
		}

		if($total_risk_measures > 0){
			$measuresCount = 0;
			$measures = '';
			while($row_risk_measures = $query_risk_measures->fetch()){
				$measuresCount++;
				$measure = $row_risk_measures["strategic_measure"];
				$measures .='
				<tr>
					<td align="center">'.$measuresCount.'</td>
					<td>'.$measure.'</td>
				</tr>';
			}
			$risk_measures = $measures;
		}
        echo json_encode(["risk_more_info_body" => $risk_more_info, "risk_measures" => $risk_measures]);
	}
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
