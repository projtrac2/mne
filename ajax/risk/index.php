<?php
include '../controller.php';
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

    if (isset($_GET['get_category_risks'])) {
        $cat_id = $_GET['cat_id'];

        $query_category_risks = $db->prepare("SELECT * FROM tbl_risk_register WHERE risk_category = :cat_id");
        $query_category_risks->execute(array(":cat_id" => $cat_id));
        $total_category_risks = $query_category_risks->rowCount();

		if($total_category_risks > 0){
			$category_risks = '<option value="">.... Select Risk ....</option>';
			while ($rows_category_risks = $query_category_risks->fetch()) {
				$category_risks .= '
				<font color="black">
					<option value="'.$rows_category_risks['id'].'">'.$rows_category_risks['risk_description'].'</option>
				</font>';
			}
		} else {
			$category_risks = '<option value="">... No defined category risks ...</option>';
		}

        echo json_encode(["category_risks" => $category_risks]);
    }

    if (isset($_POST['save_risk'])) {
        $current_date = date("Y-m-d");
        $risk = $_POST['risk'];
        $risk_category = $_POST['risk_category'];
        $username = $_POST['user_name'];
		$riskid =  $_POST['riskid'];

		if(!empty($riskid) || $riskid !=""){
			$update_risk = $db->prepare("UPDATE tbl_risk_register SET risk_description=:risk, risk_category=:risk_category, updated_by=:user_name, date_updated=:date_entered WHERE id=:riskid");
			$risk_results = $update_risk->execute(array(":risk" => $risk, ':risk_category' => $risk_category, ':user_name' => $username, ':date_entered' => $current_date, ':riskid' => $riskid));

			$success_status = false;
			$message = "Failed to create the risk!!";

			if ($risk_results) {
				$success_status = true;
				$message = "Risk successfully updated!";
			}
		} else {
			$insert_risk = $db->prepare("INSERT INTO tbl_risk_register (risk_description, risk_category, created_by, date_created) VALUES (:risk, :risk_category, :user_name, :date_entered)");
			$risk_results = $insert_risk->execute(array(':risk' => $risk, ":risk_category" => $risk_category, ':user_name' => $username, ':date_entered' => $current_date));

			$success_status = false;
			$message = "Failed to create the risk!!";

			if ($risk_results) {
				$success_status = true;
				$message = "Risk created successfully";
			}
		}
        echo json_encode(array("success" => $success_status, "message" => $message));
    }

	//edit register risk

	if(isset($_GET['edit_register_risk'])){
		$riskid = $_GET['riskid'];
		$query_risk_details = $db->prepare("SELECT * FROM tbl_risk_register WHERE id=:riskid");
		$query_risk_details->execute(array(":riskid" =>$riskid));
		$row_risk_details = $query_risk_details->fetch();
        $total_risk_details = $query_risk_details->rowCount();

		$query_risk_categories = $db->prepare("SELECT * FROM tbl_projrisk_categories");
		$query_risk_categories->execute();

		$risk_details = '';
		if($total_risk_details > 0){
			$risk = $row_risk_details["risk_description"];
			$catid = $row_risk_details["risk_category"];

			$query_risks = $db->prepare("SELECT * FROM tbl_risk_register WHERE risk_category=:catid");
			$query_risks->execute(array(":catid" => $catid));

			$risk_details = '
			<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
				<div class="form-inline">
					<label for="">Risk Category</label>
					<select name="risk_category" id="risk_category" class="form-control require" onchange="category_risks();" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
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
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
				<div class="form-inline">
					<label for="">Risk Description</label>
					<input name="risk" id="risk" class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%" placeholder="Describe the risk" value="'.$risk.'" required>
				</div>
			</div>';
		}
        echo json_encode(["register_risk_detail" => $risk_details, "riskid" => $riskid]);
	}

    if (isset($_POST['destroy_register_risk'])) {
		$success_status = false;
		if(!empty($_POST['riskid']) || $_POST['riskid'] !=""){
			$riskid =  $_POST['riskid'];

			$delete_risk = $db->prepare("DELETE from tbl_risk_register WHERE id=:riskid");
			$delete_risk->execute(array(':riskid' => $riskid));
			$success_status = true;
		}
        echo json_encode(array("success" => $success_status));
    }

    if (isset($_POST['store_risk'])) {
        $current_date = date("Y-m-d");
        $projid = $_POST['projid'];
        $risk_id = $_POST['risk_id'];
        $risk_category = $_POST['risk_category'];
        $likelihood = $_POST['likelihood'];
        $impact = $_POST['impact'];
        $risk_level = $_POST['risk_level'];
        $username = $_POST['user_name'];
		$riskid =  $_POST['riskid'];

		if(!empty($riskid) || $riskid !=""){
			$riskid =  $_POST['riskid'];
			$update_risk = $db->prepare("UPDATE tbl_project_risks SET risk_id=:risk_id, likelihood=:likelihood, impact=:impact, risk_level=:risk_level, updated_by=:user_name, date_updated=:date_entered WHERE id=:riskid");
			$risk_results = $update_risk->execute(array(":risk_id" => $risk_id, ':likelihood' => $likelihood, ':impact' => $impact, ':risk_level' => $risk_level, ':user_name' => $username, ':date_entered' => $current_date, ':riskid' => $riskid));

			$success_status = false;
			$message = "Failed to create the risk!!";

			if ($risk_results) {
				$measures = $_POST['strategic_measure'];
				$total_measures = count($measures);
				if($total_measures > 0){
					$delete_strategic_measures = $db->prepare("DELETE from tbl_project_risk_strategic_measures WHERE riskid=:riskid");
					$delete_strategic_measures->execute(array(':riskid' => $riskid));

					for ($i = 0; $i < $total_measures; $i++) {
						$strategic_measure = $measures[$i];

						$update_strategic_measures = $db->prepare("INSERT INTO tbl_project_risk_strategic_measures (projid, riskid, strategic_measure) VALUES (:projid,:riskid,:strategic_measure)");
						$update_strategic_measures->execute(array(':projid' => $projid, ":riskid" => $riskid, ':strategic_measure' => $strategic_measure));
					}
				}
				$success_status = true;
				$message = "Risk successfully updated!";
			}
		} else {
			$insert_risk = $db->prepare("INSERT INTO tbl_project_risks (projid, risk_id, likelihood, impact, risk_level, created_by, date_created) VALUES (:projid, :risk_id, :likelihood, :impact, :risk_level, :user_name, :date_entered)");
			$risk_results = $insert_risk->execute(array(':projid' => $projid, ":risk_id" => $risk_id, ':likelihood' => $likelihood, ':impact' => $impact, ':risk_level' => $risk_level, ':user_name' => $username, ':date_entered' => $current_date));

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
			$update_project_status = $db->prepare("UPDATE tbl_projects SET proj_substage=2 WHERE projid=:projid");
			$update_project_status->execute(array(':projid' => $projid));

			$success_status = true;
			$message = "Risk Responsible details successfully updated";
        }
        echo json_encode(array("success" => $success_status, "message" => $message));
    }


    if (isset($_POST['store_risk_monitoring'])) {
        $current_date = date("Y-m-d");
        $projid = $_POST['projid'];
        $risk_id = $_POST['risk_id'];
        $risk_likelihood = $_POST['likelihood'];
        $risk_impact = $_POST['impact'];
        $risk_level = $_POST['risk_level'];
        $username = $_POST['user_name'];

		$insert_risk = $db->prepare("INSERT INTO tbl_project_risk_monitoring (projid, riskid, risk_likelihood, risk_impact, risk_level, created_by, date_created) VALUES (:projid, :risk_id, :risk_likelihood, :risk_impact, :risk_level, :user_name, :date_entered)");
		$risk_results = $insert_risk->execute(array(':projid' => $projid, ":risk_id" => $risk_id, ':risk_likelihood' => $risk_likelihood, ':risk_impact' => $risk_impact, ':risk_level' => $risk_level, ':user_name' => $username, ':date_entered' => $current_date));

		$success_status = false;
		$message = "Failed to save the risk monitoring data!!";

        if ($risk_results) {
			$risk_monitoring_id = $db->lastInsertId();
            $measures = $_POST['strategic_measure'];
            $total_measures = count($measures);
            for ($i = 0; $i < $total_measures; $i++) {
                $strategic_measure_id = $measures[$i];
                $strategic_measure_compliance = $_POST['compliance'][$i];

                $insert_strategic_measures = $db->prepare("INSERT INTO tbl_project_risk_strategic_measure_monitoring (risk_monitoring_id, strategic_measure_id, strategic_measure_compliance) VALUES (:risk_monitoring_id,:strategic_measure_id,:strategic_measure_compliance)");
                $insert_strategic_measures->execute(array(':risk_monitoring_id' => $risk_monitoring_id, ":strategic_measure_id" => $strategic_measure_id, ':strategic_measure_compliance' => $strategic_measure_compliance));
            }
			$success_status = true;
			$message = "Risk successfully monitored";
        }
        echo json_encode(array("success" => $success_status, "message" => $message));
    }

	if(isset($_GET['edit_risk'])){
		$riskid = $_GET['riskid'];
		$query_risk_details = $db->prepare("SELECT g.risk_description AS risk, c.category AS cat, c.catid, i.id AS impactid, i.description AS impact, p.id AS likelihoodid, p.description AS likelihood, s.digit AS risk_digit, s.description AS level, s.color AS color FROM tbl_project_risks r left join tbl_risk_register g on g.id=r.risk_id left join tbl_projrisk_categories c on c.catid=g.risk_category left join tbl_risk_impact i on i.id=r.impact left join tbl_risk_probability p on p.id=r.likelihood left join tbl_risk_severity s on s.digit=r.risk_level WHERE r.id=:riskid");
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

			$query_risks = $db->prepare("SELECT * FROM tbl_risk_register WHERE risk_category=:catid");
			$query_risks->execute(array(":catid" => $catid));

			$query_risk_id = $db->prepare("SELECT risk_id FROM tbl_project_risks WHERE id=:riskid");
			$query_risk_id->execute(array(":riskid" => $riskid));
			$row_risk_id = $query_risk_id->fetch();
			$risk_id = $row_risk_id["risk_id"];

			$risk_details = '
			<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
				<div class="form-inline">
					<label for="">Risk Category</label>
					<select name="risk_category" id="risk_category" class="form-control require" onchange="category_risks();" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
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
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
				<div class="form-inline">
					<label for="">Risk Description</label>
					<select name="risk_id" class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%" id="risk_id" required>
						<option value="">.... First Select Risk Category ....</option>';
						while ($row_risks = $query_risks->fetch()) {
							$selected_risk = $risk_id == $row_risks['id'] ? "selected" : "";
							$risk_details .= '<font color="black">
								<option value="'.$row_risks['id'].'" '.$selected_risk.'>'.$row_risks['risk_description'].'</option>
							</font>';
						}
					$risk_details .= '
					</select>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
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
			<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
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
			<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
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
				<tr id="m_row'.$measuresCount.'">
					<td>'.$measuresCount.'</td>
					<td>
						<input type="text" name="strategic_measure[]" id="taskrow'.$measuresCount.'" placeholder="Describe the Strategic Measure" class="form-control parameter" value="'.$measure.'" required/>
					</td>
					<td>
						<button type="button" class="btn btn-danger btn-sm" id="delete" onclick="delete_row_Items(m_row'.$measuresCount.')">
							<span class="glyphicon glyphicon-minus"></span>
						</button>
					</td>
				</tr>';
			}
			$risk_measures = $measures;
		}
        echo json_encode(["risk_more_info_body" => $risk_details, "risk_measures" => $risk_measures, "riskid" => $riskid]);
	}

	if(isset($_GET['risk_register_more_info'])){
		$riskid = $_GET['riskid'];
		$query_risk_details = $db->prepare("SELECT r.id AS risk_id, g.risk_description AS risk, c.category AS cat, i.description AS impact, p.description AS likelihood, s.description AS level, s.color AS color FROM tbl_project_risks r left join tbl_risk_register g on g.id=r.risk_id left join tbl_projrisk_categories c on c.catid=g.risk_category left join tbl_risk_impact i on i.id=r.impact left join tbl_risk_probability p on p.id=r.likelihood left join tbl_risk_severity s on s.digit=r.risk_level WHERE r.id=:riskid");
		$query_risk_details->execute(array(":riskid" =>$riskid));
		$row_risk_details = $query_risk_details->fetch();
        $total_risk_details = $query_risk_details->rowCount();

		$query_risk_monitored = $db->prepare("SELECT *, m.id AS mid FROM tbl_project_risk_monitoring m left join tbl_risk_severity s on s.digit=m.risk_level WHERE m.riskid=:riskid ORDER BY m.id DESC");
		$query_risk_monitored->execute(array(":riskid" =>$riskid));
		$total_risk_monitored = $query_risk_monitored->rowCount();


		$risk_more_info = '';
		if($total_risk_details > 0){
			$risk_id = $row_risk_details["risk_id"];
			$risk = $row_risk_details["risk"];
			$category = $row_risk_details["cat"];
			$impact = $row_risk_details["impact"];
			$likelihood = $row_risk_details["likelihood"];
			$risk_level = $row_risk_details["level"];
			$risk_color = $row_risk_details["color"];

			$risk_more_info = '
			<fieldset class="scheduler-border" id="milestone_div">
				<legend class="scheduler-border bg-primary" style="border-radius:3px">Risk Main Details</legend>
				<div class="row">
					<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
						<div class="form-inline">
							<label for="">Category</label>
							<div class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px">'.$category.'</div>
						</div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
						<div class="form-inline">
							<label for="">Risk Description</label>
							<div class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-bottom: 7px; padding-left: 10px; height: auto">'.$risk.'</div>
						</div>
					</div>
				</div>
			</fieldset>
			<fieldset class="scheduler-border" id="milestone_div">
				<legend class="scheduler-border bg-info" style="border-radius:3px">Inherent Risk Details</legend>
				<div class="row">
					<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
						<div class="form-inline">
							<label for="">Likelihood</label>
							<div id="severityname" class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px">'.$likelihood.'</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
						<div class="form-inline">
							<label for="">Impact</label>
							<div id="severityname" class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px">'.$impact.'</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
						<div class="form-inline">
							<label for="">Level</label>
							<div id="severityname" class="require '.$risk_color.'" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px">'.$risk_level.'</div>
						</div>
					</div>
				</div>
			</fieldset>';
		}

		if($total_risk_monitored > 0){
			while($row_risk_monitored = $query_risk_monitored->fetch()){
				$risk_monitoring_id = $row_risk_monitored["mid"];
				$impactid = $row_risk_monitored["risk_impact"];
				$likelihoodid = $row_risk_monitored["risk_likelihood"];
				$risk_level = $row_risk_monitored["description"];
				$risk_color = $row_risk_monitored["color"];
				$residual_date = $row_risk_monitored["date_created"];

				$query_likelihood = $db->prepare("SELECT description FROM tbl_risk_probability WHERE id=:likelihoodid");
				$query_likelihood->execute(array(":likelihoodid" =>$likelihoodid));
				$row_likelihood = $query_likelihood->fetch();

				$query_impact = $db->prepare("SELECT description FROM tbl_risk_impact WHERE id=:impactid");
				$query_impact->execute(array(":impactid" =>$impactid));
				$row_impact = $query_impact->fetch();

				$impact = $row_impact["description"];
				$likelihood = $row_likelihood["description"];

				$risk_more_info .= '
				<fieldset class="scheduler-border" id="milestone_div">
					<legend class="scheduler-border bg-brown" style="border-radius:3px">Residual Risk for Date: '.$residual_date.'</legend>
					<div class="row">
						<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
							<div class="form-inline">
								<label for="">Likelihood</label>
								<div id="severityname" class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px">'.$likelihood.'</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
							<div class="form-inline">
								<label for="">Impact</label>
								<div id="severityname" class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px">'.$impact.'</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
							<div class="form-inline">
								<label for="">Level</label>
								<div id="severityname" class="require '.$risk_color.'" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px">'.$risk_level.'</div>
							</div>
						</div>
					</div>';

					$risk_more_info .= '
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover" id="measures_table" style="width:100%">
									<thead>
										<tr>
											<th width="5%">#</th>
											<th width="75%">Strategic Measure</th>
											<th width="20%">Status</th>
										</tr>
									</thead>
									<tbody>';
										$measuresCount = 0;
										$risk_measures = "";
										$query_risk_measures_compliance = $db->prepare("SELECT * FROM tbl_project_risk_strategic_measure_monitoring mm left join tbl_project_risk_strategic_measures m on m.id=mm.strategic_measure_id WHERE risk_monitoring_id=:risk_monitoring_id");
										$query_risk_measures_compliance->execute(array(":risk_monitoring_id" =>$risk_monitoring_id));
										while($row_risk_measures_compliance = $query_risk_measures_compliance->fetch()){
											$measuresCount++;
											$measure_complianceid = $row_risk_measures_compliance["strategic_measure_compliance"];
											$measure_compliance = $measure_complianceid == 1 ? "Compliant" : "Non-Compliant";
											$measure = $row_risk_measures_compliance["strategic_measure"];

											$risk_measures .='
											<tr>
												<td align="center">'.$measuresCount.'</td>
												<td>'.$measure.'</td>
												<td>'.$measure_compliance.'</td>
											</tr>';
										}
									$risk_more_info .= $risk_measures.'</tbody>
								</table>
							</div>
				</fieldset>';
			}
		} else {
			$measuresCount = 0;
			$risk_measures = "";
			$query_risk_measures_compliance = $db->prepare("SELECT * FROM tbl_project_risk_strategic_measures WHERE riskid=:risk_id");
			$query_risk_measures_compliance->execute(array(":risk_id" =>$riskid));

			while($row_risk_measures_compliance = $query_risk_measures_compliance->fetch()){
				$measuresCount++;
				$measure = $row_risk_measures_compliance["strategic_measure"];

				$risk_measures .='
				<tr>
					<td align="center">'.$measuresCount.'</td>
					<td>'.$measure.'</td>
				</tr>';
			}
		}
        echo json_encode(["risk_more_info_body" => $risk_more_info, "risk_measures" => $risk_measures]);
	}

	if(isset($_GET['risk_more_info'])){
		$riskid = $_GET['riskid'];
		$query_risk_details = $db->prepare("SELECT r.id AS risk_id, g.risk_description AS risk, c.category AS cat, i.description AS impact, p.description AS likelihood, s.description AS level, s.color AS color FROM tbl_project_risks r left join tbl_risk_register g on g.id=r.risk_id left join tbl_projrisk_categories c on c.catid=g.risk_category left join tbl_risk_impact i on i.id=r.impact left join tbl_risk_probability p on p.id=r.likelihood left join tbl_risk_severity s on s.digit=r.risk_level WHERE r.id=:riskid");
		$query_risk_details->execute(array(":riskid" =>$riskid));
		$row_risk_details = $query_risk_details->fetch();
        $total_risk_details = $query_risk_details->rowCount();

		$query_risk_monitored = $db->prepare("SELECT *, m.id AS mid FROM tbl_project_risk_monitoring m left join tbl_risk_severity s on s.digit=m.risk_level WHERE m.riskid=:riskid ORDER BY m.id DESC");
		$query_risk_monitored->execute(array(":riskid" =>$riskid));
		$total_risk_monitored = $query_risk_monitored->rowCount();


		$risk_more_info = '';
		if($total_risk_details > 0){
			$risk_id = $row_risk_details["risk_id"];
			$risk = $row_risk_details["risk"];
			$category = $row_risk_details["cat"];
			$impact = $row_risk_details["impact"];
			$likelihood = $row_risk_details["likelihood"];
			$risk_level = $row_risk_details["level"];
			$risk_color = $row_risk_details["color"];

			$risk_more_info = '
			<fieldset class="scheduler-border" id="milestone_div">
				<legend class="scheduler-border bg-primary" style="border-radius:3px">Risk Main Details</legend>
				<div class="row">
					<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
						<div class="form-inline">
							<label for="">Category</label>
							<div class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px">'.$category.'</div>
						</div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
						<div class="form-inline">
							<label for="">Risk Description</label>
							<div class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-bottom: 7px; padding-left: 10px; height: auto">'.$risk.'</div>
						</div>
					</div>
				</div>
			</fieldset>
			<fieldset class="scheduler-border" id="milestone_div">
				<legend class="scheduler-border bg-info" style="border-radius:3px">Inherent Risk Details</legend>
				<div class="row">
					<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
						<div class="form-inline">
							<label for="">Likelihood</label>
							<div id="severityname" class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px">'.$likelihood.'</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
						<div class="form-inline">
							<label for="">Impact</label>
							<div id="severityname" class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px">'.$impact.'</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
						<div class="form-inline">
							<label for="">Level</label>
							<div id="severityname" class="require '.$risk_color.'" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px">'.$risk_level.'</div>
						</div>
					</div>
				</div>
			</fieldset>';
		}

		if($total_risk_monitored > 0){
			while($row_risk_monitored = $query_risk_monitored->fetch()){
				$risk_monitoring_id = $row_risk_monitored["mid"];
				$impactid = $row_risk_monitored["risk_impact"];
				$likelihoodid = $row_risk_monitored["risk_likelihood"];
				$risk_level = $row_risk_monitored["description"];
				$risk_color = $row_risk_monitored["color"];
				$residual_date = $row_risk_monitored["date_created"];

				$query_likelihood = $db->prepare("SELECT description FROM tbl_risk_probability WHERE id=:likelihoodid");
				$query_likelihood->execute(array(":likelihoodid" =>$likelihoodid));
				$row_likelihood = $query_likelihood->fetch();

				$query_impact = $db->prepare("SELECT description FROM tbl_risk_impact WHERE id=:impactid");
				$query_impact->execute(array(":impactid" =>$impactid));
				$row_impact = $query_impact->fetch();

				$impact = $row_impact["description"];
				$likelihood = $row_likelihood["description"];

				$risk_more_info .= '
				<fieldset class="scheduler-border" id="milestone_div">
					<legend class="scheduler-border bg-brown" style="border-radius:3px">Residual Risk for Date: '.$residual_date.'</legend>
					<div class="row">
						<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
							<div class="form-inline">
								<label for="">Likelihood</label>
								<div id="severityname" class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px">'.$likelihood.'</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
							<div class="form-inline">
								<label for="">Impact</label>
								<div id="severityname" class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px">'.$impact.'</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
							<div class="form-inline">
								<label for="">Level</label>
								<div id="severityname" class="require '.$risk_color.'" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px">'.$risk_level.'</div>
							</div>
						</div>
					</div>';

					$risk_more_info .= '
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover" id="measures_table" style="width:100%">
									<thead>
										<tr>
											<th width="5%">#</th>
											<th width="75%">Strategic Measure</th>
											<th width="20%">Status</th>
										</tr>
									</thead>
									<tbody>';
										$measuresCount = 0;
										$risk_measures = "";
										$query_risk_measures_compliance = $db->prepare("SELECT * FROM tbl_project_risk_strategic_measure_monitoring mm left join tbl_project_risk_strategic_measures m on m.id=mm.strategic_measure_id WHERE risk_monitoring_id=:risk_monitoring_id");
										$query_risk_measures_compliance->execute(array(":risk_monitoring_id" =>$risk_monitoring_id));
										while($row_risk_measures_compliance = $query_risk_measures_compliance->fetch()){
											$measuresCount++;
											$measure_complianceid = $row_risk_measures_compliance["strategic_measure_compliance"];
											$measure_compliance = $measure_complianceid == 1 ? "Compliant" : "Non-Compliant";
											$measure = $row_risk_measures_compliance["strategic_measure"];

											$risk_measures .='
											<tr>
												<td align="center">'.$measuresCount.'</td>
												<td>'.$measure.'</td>
												<td>'.$measure_compliance.'</td>
											</tr>';
										}
									$risk_more_info .= $risk_measures.'</tbody>
								</table>
							</div>
				</fieldset>';
			}
		} else {
			$measuresCount = 0;
			$risk_measures = "";
			$query_risk_measures_compliance = $db->prepare("SELECT * FROM tbl_project_risk_strategic_measures WHERE riskid=:risk_id");
			$query_risk_measures_compliance->execute(array(":risk_id" =>$risk_id));

			while($row_risk_measures_compliance = $query_risk_measures_compliance->fetch()){
				$measuresCount++;
				$measure = $row_risk_measures_compliance["strategic_measure"];

				$risk_measures .='
				<tr>
					<td align="center">'.$measuresCount.'</td>
					<td>'.$measure.'</td>
				</tr>';
			}
		}
        echo json_encode(["risk_more_info_body" => $risk_more_info, "risk_measures" => $risk_measures]);
	}

    if (isset($_POST['destroy_item'])) {
		$success_status = false;
		if(!empty($_POST['id']) || $_POST['id'] !=""){
			$riskid =  $_POST['id'];

			$delete_risk = $db->prepare("DELETE from tbl_project_risks WHERE id=:riskid");
			$delete_risk->execute(array(':riskid' => $riskid));

			$delete_strategic_measures = $db->prepare("DELETE from tbl_project_risk_strategic_measures WHERE riskid=:riskid");
			$delete_strategic_measures->execute(array(':riskid' => $riskid));
			$success_status = true;
		}
        echo json_encode(array("success" => $success_status));
    }


	if(isset($_GET['risk_performance'])){
		$riskid = $_GET['riskid'];
		$query_risk_details = $db->prepare("SELECT g.risk_description AS risk, c.category AS cat, i.description AS impact, p.description AS likelihood, s.description AS level, s.color AS color FROM tbl_project_risks r left join tbl_risk_register g on g.id=r.risk_id left join tbl_projrisk_categories c on c.catid=g.risk_category left join tbl_risk_impact i on i.id=r.impact left join tbl_risk_probability p on p.id=r.likelihood left join tbl_risk_severity s on s.digit=r.risk_level WHERE r.id=:riskid");
		$query_risk_details->execute(array(":riskid" =>$riskid));
		$row_risk_details = $query_risk_details->fetch();
        $total_risk_details = $query_risk_details->rowCount();

		$query_risk_monitored = $db->prepare("SELECT * FROM tbl_project_risk_monitoring m left join tbl_risk_severity s on s.digit=m.risk_level WHERE m.riskid=:riskid ORDER BY m.id DESC Limit 1");
		$query_risk_monitored->execute(array(":riskid" =>$riskid));
		$row_risk_monitored = $query_risk_monitored->fetch();
		$total_risk_monitored = $query_risk_monitored->rowCount();

		$query_risk_measures = $db->prepare("SELECT * FROM tbl_project_risk_strategic_measures WHERE riskid=:riskid");
		$query_risk_measures->execute(array(":riskid" =>$riskid));
		$total_risk_measures = $query_risk_measures->rowCount();

		$query_risk_performance = $db->prepare("SELECT date_created AS date, s.description AS level, s.color AS color, p.description AS likelihood, i.description AS impact FROM tbl_project_risk_monitoring m left join tbl_risk_severity s on s.digit=m.risk_level left join tbl_risk_probability p on p.id=m.risk_likelihood left join tbl_risk_impact i on i.id=m.risk_impact WHERE m.riskid=:riskid ORDER BY m.id DESC");
		$query_risk_performance->execute(array(":riskid" =>$riskid));
		$total_risk_performance = $query_risk_performance->rowCount();

		$risk_measures = $risk_level_performance_table = $risk_more_info = '';

		function monitoring_dates($riskid){
			global $db;
			$query_monitoring_dates = $db->prepare("SELECT date_created FROM tbl_project_risk_monitoring WHERE riskid=:riskid");
			$query_monitoring_dates->execute(array(":riskid" =>$riskid));
			$date_th = "";
			while($row_monitoring_dates = $query_monitoring_dates->fetch()){
				$measures_monitoring_dates = $row_monitoring_dates["date_created"];
				$date_th .= '<th>'.$measures_monitoring_dates.'</th>';
			}
			return $date_th;
		}

		if($total_risk_details > 0){
			$risk = $row_risk_details["risk"];
			$category = $row_risk_details["cat"];
			$impact = $row_risk_details["impact"];
			$likelihood = $row_risk_details["likelihood"];
			$risk_level = $row_risk_details["level"];
			$risk_color = $row_risk_details["color"];

			if($total_risk_monitored > 0){
				$impactid = $row_risk_monitored["risk_impact"];
				$likelihoodid = $row_risk_monitored["risk_likelihood"];
				$risk_level = $row_risk_monitored["description"];
				$risk_color = $row_risk_monitored["color"];

				$query_likelihood = $db->prepare("SELECT description FROM tbl_risk_probability WHERE id=:likelihoodid");
				$query_likelihood->execute(array(":likelihoodid" =>$likelihoodid));
				$row_likelihood = $query_likelihood->fetch();

				$query_impact = $db->prepare("SELECT description FROM tbl_risk_impact WHERE id=:impactid");
				$query_impact->execute(array(":impactid" =>$impactid));
				$row_impact = $query_impact->fetch();

				$impact = $row_impact["description"];
				$likelihood = $row_likelihood["description"];
			}

			$risk_more_info = '
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
				<div class="form-inline">
					<label for="">Risk Description</label>
					<div id="severityname" class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height: auto; width:98%">'.$risk.'</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom:10px">
				<div class="form-inline">
					<label for="">Risk Category</label>
					<div id="severityname" class="require" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px; width:98%">'.$category.'</div>
				</div>
			</div>';
		}

		if($total_risk_performance > 0){
			while($row_risk_performance = $query_risk_performance->fetch()){
				$date_monitored = $row_risk_performance["date"];
				$risk_level = $row_risk_performance["level"];
				$level_color = $row_risk_performance["color"];
				$risk_likelihood = $row_risk_performance["likelihood"];
				$risk_impact = $row_risk_performance["impact"];
				$risk_level_performance_table .= '
				<tr>
					<td>'.$date_monitored.'</td>
					<td>'.$risk_likelihood.'</td>
					<td>'.$risk_impact.'</td>
					<td class="'.$level_color.'">'.$risk_level.'</td>
				</tr>';
			}
		}

		if($total_risk_measures > 0){
			$measuresCount = 0;
			$measures = '
			<thead>';
				$measures .='
				<tr class="bg-grey">
					<th width="3%">#</th>
					<th>Measure</th>'.
					monitoring_dates($riskid).
				'</tr>';
			$measures .='</thead>
			<tbody>';

			if($total_risk_performance > 0){
				while($row_risk_measures = $query_risk_measures->fetch()){
					$measuresCount++;
					$measureid = $row_risk_measures["id"];
					$measure = $row_risk_measures["strategic_measure"];

					$measures .='
					<tr>
						<td>'.$measuresCount.'</td>
						<td style="background-color: #cccccc; color: white">'.$measure.'</td>';

						$query_risk_measures_compliance = $db->prepare("SELECT * FROM tbl_project_risk_strategic_measure_monitoring WHERE strategic_measure_id=:measureid");
						$query_risk_measures_compliance->execute(array(":measureid" =>$measureid));
						while($row_risk_measures_compliance = $query_risk_measures_compliance->fetch()){
							$measure_complianceid = $row_risk_measures_compliance["strategic_measure_compliance"];
							$measure_compliance = $measure_complianceid == 1 ? "Compliant" : "Non-Compliant";

							$measures .='<td>'.$measure_compliance.'</td>';
						}
					$measures .='</tr>';
				}
			}
			$measures .='</tbody>';
			$risk_measures = $measures;
		}
        echo json_encode(["risk_more_info_body" => $risk_more_info, "risk_level_performance_table" => $risk_level_performance_table, "risk_measures" => $risk_measures]);
	}


	if(isset($_GET['risk_monitoring'])){
		$riskid = $_GET['riskid'];
		$query_risk_details = $db->prepare("SELECT g.risk_description AS risk, c.category AS cat, c.catid, i.id AS impactid, i.description AS impact, p.id AS likelihoodid, p.description AS likelihood, s.digit AS risk_digit, s.description AS level, s.color AS color FROM tbl_project_risks r left join tbl_risk_register g on g.id=r.risk_id left join tbl_projrisk_categories c on c.catid=g.risk_category left join tbl_risk_impact i on i.id=r.impact left join tbl_risk_probability p on p.id=r.likelihood left join tbl_risk_severity s on s.digit=r.risk_level WHERE r.id=:riskid");
		$query_risk_details->execute(array(":riskid" =>$riskid));
		$row_risk_details = $query_risk_details->fetch();
        $total_risk_details = $query_risk_details->rowCount();

		$query_risk_monitored = $db->prepare("SELECT * FROM tbl_project_risk_monitoring m left join tbl_risk_severity s on s.digit=m.risk_level WHERE m.riskid=:riskid ORDER BY m.id DESC Limit 1");
		$query_risk_monitored->execute(array(":riskid" =>$riskid));
		$row_risk_monitored = $query_risk_monitored->fetch();
		$total_risk_monitored = $query_risk_monitored->rowCount();

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

		$risk_measures = $risk_details = $risk_level_update = '';
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

			if($total_risk_monitored > 0){
				$impactid = $row_risk_monitored["risk_impact"];
				$likelihoodid = $row_risk_monitored["risk_likelihood"];
				$risk_level = $row_risk_monitored["description"];
				$risk_color = $row_risk_monitored["color"];
			}

			$risk_details = '
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
				<div class="form-inline">
					<label for="">Risk Category</label>
					<div id="severityname" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px; width:98%">'.$category.'</div>
				</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
				<div class="form-inline">
					<label for="">Risk Description</label>
					<div id="severityname" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height: auto; width:98%">'.$risk.'</div>
				</div>
			</div>';

			$risk_level_update = '
			<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="margin-bottom:10px">
				<div class="form-inline">
					<label for="">Risk Likelihood</label>
					<select name="likelihood" id="risk_likelihood" class="form-control require" onchange="risk_monitor_severity()" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
						<option value="">.... Select Likelihood ....</option>';
						while ($row_risk_likelihood = $query_risk_likelihood->fetch()) {
							$selected = $likelihoodid == $row_risk_likelihood['id'] ? "selected" : "";
							$risk_level_update .= '<font color="black">
								<option value="'.$row_risk_likelihood['id'].'" '.$selected.'>'.$row_risk_likelihood['description'].'</option>
							</font>';
						}
					$risk_level_update .= '</select>
				</div>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="margin-bottom:10px">
				<div class="form-inline">
					<label for="">Risk Impact</label>
					<select name="impact" id="risk_impact" class="form-control require" onchange="risk_monitor_severity()" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
						<option value="">.... Select Impact ....</option>';
						while ($row_risk_impact = $query_risk_impact->fetch()) {
							$selected = $impactid == $row_risk_impact['id'] ? "selected" : "";
							$risk_level_update .= '<font color="black">
								<option value="'.$row_risk_impact['id'].'" '.$selected.'>'.$row_risk_impact['description'].'</option>
							</font>';
						}
					$risk_level_update .= '</select>
				</div>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="margin-bottom:10px">
				<div class="form-inline">
					<label for="">Risk Level</label>
					<input name="risk_level" type="hidden" id="risk_severity" value="'.$risk_digit.'" required>
					<div id="risk_severityname" class="require '.$risk_color.'" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height:35px; width:98%">'.$risk_level.'</div>
				</div>
			</div>';
		}

		if($total_risk_measures > 0){
			$measuresCount = 0;
			$measures = '';
			while($row_risk_measures = $query_risk_measures->fetch()){
				$measuresCount++;
				$measureid = $row_risk_measures["id"];
				$measure = $row_risk_measures["strategic_measure"];
				$measures .='
				<tr>
					<td align="center">
						'.$measuresCount.'
					</td>
					<td>
						'.$measure.'
						<input type="hidden" name="strategic_measure[]" value="'.$measureid.'" required/>
					</td>
					<td>
						<select name="compliance[]" class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%" required>
							<option value=""> Select Compliance </option>
							<option value="1">Compliant</option>
							<option value="0">Non-Compliant</option>
						</select>
					</td>
				</tr>';
			}
			$risk_measures = $measures;
		}

        echo json_encode(["risk_more_info_body" => $risk_details, "monitored_risk_id" => $riskid, "risk_measures" => $risk_measures, "risk_level" => $risk_level_update]);
	}



	if(isset($_GET['get_risk_more_info'])){
		$projid = $_GET['projid'];
		$likelihood = $_GET['likelihood'];
		$impact = $_GET['impact'];
		$level = $_GET['level'];
		$textclass = "text-primary";

		$query_risk_responsible = $db->prepare("SELECT tt.title, fullname, t.phone, t.email FROM tbl_project_risk_details r left join users u on u.userid=r.responsible left join tbl_projteam2 t on t.ptid=u.pt_id left join tbl_titles tt on tt.id=t.title WHERE r.projid=:projid");
		$query_risk_responsible->execute(array(":projid" =>$projid));
		$row_risk_responsible = $query_risk_responsible->fetch();
		$responsible = $row_risk_responsible["title"].'.'.$row_risk_responsible["fullname"];
		$responsible_phone = $row_risk_responsible["phone"];
		$responsible_email = $row_risk_responsible["email"];

		$risk_list ='
		<input type="hidden" value="0" id="clicked">
		<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
			<div class="form-inline">
				<label for="">Responsible</label>
				<div id="severityname" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height: auto; width:98%">'.$responsible.'</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
			<div class="form-inline">
				<label for="">Phone Number</label>
				<div id="severityname" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height: auto; width:98%"><a href="tel:'.$responsible_phone.'">'.$responsible_phone.'</a></div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:10px">
			<div class="form-inline">
				<label for="">Email Address</label>
				<div id="severityname" style="border:#CCC thin solid; border-radius:5px; padding-top: 7px; padding-left: 10px; height: auto; width:98%"><a href="mailto:'.$responsible_email.'" target="_blank">'.$responsible_email.'</a></div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
					<thead>
						<tr>
							<th style="width:4%">#</th>
							<th style="width:70%">Risk Description</th>
							<th style="width:26%">Risk Category</th>
						</tr>
					</thead>
					<tbody>';
						$query_project_risks = $db->prepare("SELECT riskid FROM tbl_project_risk_monitoring WHERE projid=:projid AND risk_likelihood=:likelihood AND risk_impact=:risk_impact AND risk_level=:risk_level GROUP BY riskid");
						$query_project_risks->execute(array(":projid" => $projid, ":likelihood" => $likelihood, ":risk_impact" => $impact, ":risk_level" => $level));
						$counter = 0;
						while($rows_project_risks= $query_project_risks->fetch()){
							$riskid = $rows_project_risks["riskid"];

							$query_risk_details = $db->prepare("SELECT m.id, g.risk_description AS risk, c.category AS cat, m.risk_likelihood, m.risk_impact, m.risk_level FROM tbl_project_risks r left join tbl_risk_register g on g.id=r.risk_id left join tbl_projrisk_categories c on c.catid=g.risk_category left join tbl_project_risk_monitoring m on m.riskid=r.id WHERE m.riskid=:riskid ORDER BY m.id DESC LIMIT 1");
							$query_risk_details->execute(array(":riskid" =>$riskid));
							$row_risk_details = $query_risk_details->fetch();
							$project_risk_likelihood = $row_risk_details["risk_likelihood"];
							$project_risk_impact = $row_risk_details["risk_impact"];
							$project_risk_level = $row_risk_details["risk_level"];

							if($project_risk_likelihood == $likelihood && $project_risk_impact == $impact && $project_risk_level == $level){
								$counter++;
								$monitoring_id = $row_risk_details["id"];
								$risk = $row_risk_details["risk"];
								$category = $row_risk_details["cat"];

								$risk_list .='
								<tr>
									<td style="width:4%">'. $counter .'</td>
									<td style="width:70%" class="'.$textclass.'"><div onclick="monitoredrisks('.$riskid.')">'. $risk .'</div></td>
									<td style="width:26%">'. $category .'</td>
								</tr>
								<tr class="adjustments '.$riskid.'" style="background-color:#cccccc">
									<th>#</th>
									<th>Control Measure</th>
									<th>Compliant</th>
								</tr>';

								$query_risk_measures_compliance = $db->prepare("SELECT strategic_measure, strategic_measure_compliance FROM tbl_project_risk_strategic_measures m left join tbl_project_risk_strategic_measure_monitoring c on c.strategic_measure_id=m.id WHERE c.risk_monitoring_id=:monitoring_id");
								$query_risk_measures_compliance->execute(array(":monitoring_id" =>$monitoring_id));

								$measuresCount = 0;
								while($row_risk_measures_compliance = $query_risk_measures_compliance->fetch()){
									$measuresCount++;
									$strategic_measure = $row_risk_measures_compliance["strategic_measure"];
									$measure_compliance_id = $row_risk_measures_compliance["strategic_measure_compliance"];
									$measure_compliance = $measure_compliance_id == 0 ? "No" : "Yes";
									$risk_list .='
									<tr class="adjustments '.$riskid.'">
										<td>
											'.$counter.'.'.$measuresCount.'
										</td>
										<td>
											'.$strategic_measure.'
										</td>
										<td>
											'.$measure_compliance.'
										</td>
									</tr>';
								}
							}
						}
					$risk_list .='
					<tbody>
				</table>
			</div>
		</div>
		<script>
		$(document).ready(function () {
			$(".adjustments").hide();
		});
		</script>';

        echo json_encode(["risk_more_details_body" => $risk_list]);
	}
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
