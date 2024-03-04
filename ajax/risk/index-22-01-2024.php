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

    if (isset($_POST['store_risk'])) {
        $current_date = date("Y-m-d");
        $projid = $_POST['projid'];
        $risk_description = $_POST['risk'];
        $risk_category = $_POST['risk_category'];
        $likelihood = $_POST['likelihood'];
        $impact = $_POST['impact'];
        $risk_level = $_POST['risk_level'];
        $username = $_POST['user_name'];
		$riskid =  $_POST['riskid'];
		
		if(!empty($riskid) || $riskid !=""){
			$riskid =  $_POST['riskid'];
			$update_risk = $db->prepare("UPDATE tbl_project_risks SET risk_description=:risk_description, risk_category=:risk_category, likelihood=:likelihood, impact=:impact, risk_level=:risk_level, updated_by=:user_name, date_updated=:date_entered WHERE id=:riskid");
			$risk_results = $update_risk->execute(array(":risk_description" => $risk_description, ':risk_category' => $risk_category, ':likelihood' => $likelihood, ':impact' => $impact, ':risk_level' => $risk_level, ':user_name' => $username, ':date_entered' => $current_date, ':riskid' => $riskid));
			
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
	
	if(isset($_GET['risk_more_info'])){
		$riskid = $_GET['riskid'];
		$query_risk_details = $db->prepare("SELECT r.risk_description AS risk, c.category AS cat, i.description AS impact, p.description AS likelihood, s.description AS level, s.color AS color FROM tbl_project_risks r left join tbl_projrisk_categories c on c.catid=r.risk_category left join tbl_risk_impact i on i.id=r.impact left join tbl_risk_probability p on p.id=r.likelihood left join tbl_risk_severity s on s.digit=r.risk_level WHERE r.id=:riskid");
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
		
		$risk_measures = $risk_more_info = '';
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
			$measures = '
			<thead>';
				if($total_risk_monitored > 0){
					$measures .='<tr>
						<th width="5%">#</th>
						<th width="75%">Measure</th>
						<th width="20%">Compliance</th>
					</tr>';
				} else {
					$measures .='<tr>
						<th width="5%">#</th>
						<th width="95%">Measure</th>
					</tr>';
				}
			$measures .='</thead>
			<tbody id="risk_measures">';
			while($row_risk_measures = $query_risk_measures->fetch()){
				$measuresCount++;
				$measureid = $row_risk_measures["id"];
				$measure = $row_risk_measures["strategic_measure"];
				
				if($total_risk_monitored > 0){
					$query_risk_measures_compliance = $db->prepare("SELECT * FROM tbl_project_risk_strategic_measure_monitoring WHERE strategic_measure_id=:measureid");
					$query_risk_measures_compliance->execute(array(":measureid" =>$measureid));
					$row_risk_measures_compliance = $query_risk_measures_compliance->fetch();
					$measure_complianceid = $row_risk_measures_compliance["strategic_measure_compliance"];
					$measure_compliance = $measure_complianceid == 1 ? "Compliant" : "Non-Compliant";
				}
					
				$measures .='
				<tr>
					<td align="center">'.$measuresCount.'</td>
					<td>'.$measure.'</td>';
					if($total_risk_monitored > 0){
						$measures .='<td>'.$measure_compliance.'</td>';
					}
				$measures .='</tr>';
			}
			$measures .='</tbody>';
			$risk_measures = $measures;
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
		$query_risk_details = $db->prepare("SELECT r.risk_description AS risk, c.category AS cat, i.description AS impact, p.description AS likelihood, s.description AS level, s.color AS color FROM tbl_project_risks r left join tbl_projrisk_categories c on c.catid=r.risk_category left join tbl_risk_impact i on i.id=r.impact left join tbl_risk_probability p on p.id=r.likelihood left join tbl_risk_severity s on s.digit=r.risk_level WHERE r.id=:riskid");
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
		$query_risk_details = $db->prepare("SELECT r.risk_description AS risk, c.category AS cat, c.catid, i.id AS impactid, i.description AS impact, p.id AS likelihoodid, p.description AS likelihood, s.digit AS risk_digit, s.description AS level, s.color AS color FROM tbl_project_risks r left join tbl_projrisk_categories c on c.catid=r.risk_category left join tbl_risk_impact i on i.id=r.impact left join tbl_risk_probability p on p.id=r.likelihood left join tbl_risk_severity s on s.digit=r.risk_level WHERE r.id=:riskid");
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
		
        echo json_encode(["risk_more_info_body" => $risk_details, "risk_measures" => $risk_measures, "risk_level" => $risk_level_update]);
	}
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
