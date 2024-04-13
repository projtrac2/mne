<?php
try {
	
include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';


$current_date = date("Y-m-d");
$user = $_POST['username'];
// inserting data to tbl_form and tbl_section form
if (isset($_POST['closesurvey']) && $_POST['closesurvey'] == 1) {		
	$indicator = $_POST['indid'];
	$formid = $_POST['formid'];
	$inddate = date("Y-m-d");
	$user = $_POST['username'];
	//$baseline = 1;
	$status = 2;
	$surveystatus = 3;
	$results = '';
	
	$updateform = $db->prepare("UPDATE tbl_indicator_baseline_survey_forms SET status=:status, closed_by=:user, date_closed=:dates WHERE id=:formid");
	$updateform->execute(array(":status" => $status, ":user" => $user, ":dates" => $inddate, ":formid" => $formid));
	
	$updatequery = $db->prepare("UPDATE tbl_indicator SET surveystatus=:surveystatus WHERE indid=:indid");
	$updateresult = $updatequery->execute(array(":surveystatus" => $surveystatus, ":indid" => $indicator));

	$url = "indicator-baseline-survey";
	if($updateresult){
		$msg = 'Indicator baseline survey closed successfully';
		$results .= "<script type=\"text/javascript\">
						swal({
							title: \"Error!\",
							text: \" $msg\",
							type: 'warning',
							timer: 3000,
							showConfirmButton: false });
						setTimeout(function(){
							window.location.href = '$url';
						}, 3000);
						</script>";	
	}else{		
		$msg = 'Error while closing Indicator baseline survey!!';
		$results .= "<script type=\"text/javascript\">
						swal({
							title: \"Error!\",
							text: \" $msg\",
							type: 'Danger',
							timer: 3000,
							showConfirmButton: false });
						setTimeout(function(){
							window.location.href = '$url';
						}, 3000);
						</script>";
	}
	echo $results;
}

// inserting data to tbl_form and tbl_section form
if (isset($_POST['insertbaseline']) && $_POST['insertbaseline'] == 1) {		
	$indicator = $_POST['indid'];
	$formid = $_POST['formid'];
	$baseyear = $_POST['baseyear'];
	$inddate = date("Y-m-d");
	$user = $_POST['username'];
	$status = 3;
	//$surveystatus = 4;
	
	$count = count($_POST["lvid"]);
	$k=0;
	for($cnt=0; $cnt<$count; $cnt++){ 	
		$k++;
		$m=0;
		$level1id = $_POST["lvid"][$cnt];
		$count2 = count($_POST["lvid".$k]);
		for($i=0; $i<$count2; $i++){ 
			$m++;
			$level2id = $_POST["lvid".$k][$i];
			$count3 = count($_POST["lvid".$k.$m]);
			for($j=0; $j<$count3; $j++){ 
				$level3id = $_POST["lvid".$k.$m][$j];
				$basevalue = $_POST['basevalue'.$k.$m][$j];
				$insertSQL = $db->prepare("INSERT INTO tbl_indicator_details (indid, baseyear, level1, level2, level3, basevalue, created_by, date_created) VALUES (:indid, :baseyear, :level1, :level2, :level3, :basevalue, :user, :date)");
				$insertquery = $insertSQL->execute(array(':indid' => $indicator, ':baseyear' => $baseyear, ':level1' => $level1id, ':level2' => $level2id, ':level3' => $level3id, ':basevalue' => $basevalue, ':user' => $user, ':date' => $inddate));
			}
		}
	}
	$results = '';
	
	if($insertquery){
		$updateform = $db->prepare("UPDATE tbl_indicator_baseline_survey_forms SET status=:status WHERE id=:formid");
		$updateresult = $updateform->execute(array(":status" => $status, ":formid" => $formid));
		
		/* $updatequery = $db->prepare("UPDATE tbl_indicator SET surveystatus=:surveystatus WHERE indid=:indid");
		$updateresult = $updatequery->execute(array(":surveystatus" => $surveystatus, ":indid" => $indicator)); */

		if($updateresult){
			
			///////////////////////////////////////////
			//Query the indicator and form details
			///////////////////////////////////////////
			$query_rsFormDetails = $db->prepare("SELECT f.form_name, i.indname, i.indcategory FROM `tbl_indicator_baseline_survey_submission` s INNER JOIN tbl_indicator_baseline_survey_forms f ON f.id=s.formid INNER JOIN tbl_indicator i ON i.indid =s.indid WHERE  s.indid=:indid AND s.formid=:formid GROUP BY i.indname");
			$query_rsFormDetails->execute(array(":indid" => $indicator, ":formid" => $formid));
			$row_rsFormDetails = $query_rsFormDetails->fetch();
			$totalRows_rsFormDetails = $query_rsFormDetails->rowCount();

			/////////////////////////////////////////////
			//	Query section for the answers 
			/////////////////////////////////////////////
			$query_rsSection = $db->prepare("SELECT o.id, o.section  FROM tbl_indicator_baseline_survey_answers a INNER JOIN tbl_indicator_baseline_survey_form_question_fields q ON q.id=a.fieldid INNER JOIN tbl_indicator_baseline_survey_form_sections o ON o.id =q.sectionid WHERE o.formid=:formid GROUP BY q.sectionid ");
			$query_rsSection->execute(array(":formid" => $formid));
			$row_rsSection = $query_rsSection->fetchAll();
			$totalRows_rsSection = $query_rsSection->rowCount();
			
			///////////////////////////////////////////
			//	Query the form sections 
			///////////////////////////////////////////
			$query_rsSections = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_form_sections WHERE  formid=:formid");
			$query_rsSections->execute(array(":formid" => $formid));
			$row_rsSections = $query_rsSections->fetchAll();
			$totalRows_rsSections = $query_rsSections->rowCount();
			
			$query_rsForm = $db->prepare("SELECT indid FROM tbl_indicator_baseline_survey_forms WHERE id='$formid'");
			$query_rsForm->execute();
			$row_rsForm = $query_rsForm->fetch();
			
			$query_rsIndicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid='$indicator' and active='1'");
			$query_rsIndicator->execute();
			$row_rsIndicator = $query_rsIndicator->fetch();
			
			$query_rsOpDept = $db->prepare("SELECT * FROM tbl_sectors WHERE parent='0' AND deleted='0'");
			$query_rsOpDept->execute();
			$row_rsOpDept = $query_rsOpDept->fetch();
			
			$query_baseyear = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE status=1");
			$query_baseyear->execute();
			
			$current_datetime = date("Y-m-d H:i:s");
			while($row_baseyear = $query_baseyear->fetch()){
				$sdate = date("Y-m-d H:i:s", strtotime($row_baseyear["sdate"]));
				$edate = date("Y-m-d H:i:s", strtotime($row_baseyear["edate"]));
				if($sdate <= $current_datetime && $edate >= $current_datetime){
					$baseyear = $row_baseyear["id"];
					$baseyr = $row_baseyear["year"];
					
				}
			}
			
			
			$results .= '
			<fieldset class="scheduler-border">
				<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Baseline Values</legend>
				<div class="block-header" id="sweetalert">
				</div>
				<div class="col-md-3">
					<label>Indicator Code: '.$row_rsIndicator["indcode"].'</label>
				</div>
				<div  class="col-md-12">
					<label>Indicator Name: '.$row_rsIndicator["indname"].'</label>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:-10px; padding-top:-15px">
					<input name="indid" type="hidden" value="'.$indid.'">
					<input name="formid" type="hidden" value="'.$formid.'">
					<input name="type" type="hidden" value="time">
					<div class="col-md-4">
						<label>Base-Year *: '.$baseyr.'</label>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:-5px">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover" style="width:100%">
							<thead>
								<tr id="colrow">
									<th width="4%"><strong id="colhead">#</strong></th>
									<th width="76%">Location</th>
									<th width="20%">Base Value</th>
								</tr>
							</thead>
							<tbody>';
								$query_location_level1 =  $db->prepare("SELECT d.id, level1, state FROM tbl_indicator_details d inner join tbl_state s on s.id=d.level1 WHERE indid='$indicator' GROUP BY d.level1 ORDER BY d.id ASC");
								$query_location_level1->execute();
								
								$nm = 0;
								while($rows_level1 = $query_location_level1->fetch()){
									$nm++;
									$dtid = $rows_level1["id"];
									$lv1id = $rows_level1["level1"];
									$level1 = $rows_level1["state"];
									$results .= '<tr style="background-color:#607D8B; color:#FFF">
										<td>'.$nm.'</td>
										<td>'.$level1.' '.$level1label.'</td>
										<td></td>
									</tr>';
			
									$query_location_level2 =  $db->prepare("SELECT d.id, level2, state FROM tbl_indicator_details d inner join tbl_state s on s.id=d.level2 WHERE indid='$indicator' and level1='$lv1id' GROUP BY level2 ORDER BY d.id ASC");
									$query_location_level2->execute();
									$sr = 0;
									while($rows_level2 = $query_location_level2->fetch()){
										$sr++;
										$dt2id = $rows_level2["id"];
										$lv2id = $rows_level2["level2"];
										$level2 = $rows_level2["state"];
										$results .= '<tr style="background-color:#9E9E9E; color:#FFF">
											<td>'.$nm.'.'.$sr.'</td>
											<td>'.$level2.' '.$level2label.'</td>
											<td></td>
										</tr>';
										
										$query_location_level3 =  $db->prepare("SELECT d.id, level3, basevalue, state FROM tbl_indicator_details d inner join tbl_state s on s.id=d.level3 WHERE indid='$indicator' and level1='$lv1id' and level2='$lv2id' GROUP BY level3 ORDER BY d.id ASC");
										$query_location_level3->execute();
										
										$nmb = 0;
										while($rows_level3 = $query_location_level3->fetch()){
											$nmb++;
											$xy++;
											$dt3id = $rows_level3["id"];
											$lv3id = $rows_level3["level3"];
											$level3 = $rows_level3["state"];
											$basevalue = $rows_level3["basevalue"];
											$results .= '<tr>
												<td>'.$nm.'.'.$sr.'.'.$nmb.'</td>
												<td>'.$level3.' '.$level3label.'</td>
												<td><input type="number" name="basevalue[]" value="'.$basevalue.'" class="form-control" required>
												<input type="hidden" name="dt3id[]" value="'.$dt3id.'"></td>
											</tr>';						
										}
									}
								}
							$results .= '</tbody>
						</table>
					</div>
				</diV>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:-5px">
					<input type="hidden" name="MM_update" value="updateindfrm" />
					<input name="username" type="hidden" id="username" value="'.$user.'" />
					<input type="hidden" name="updatebaseline" id="updatebaseline" value="1">
					<ul class="list-inline" style="margin-top:20px" align="center">
						<li><input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="bssurvey" value="Update" /></li>
					</ul>
				</div>
			</fieldset>';
			
			
			$msg = 'Baseline information successfully saved';
			$results .= "<script type=\"text/javascript\">
							swal({
								title: \"Success!\",
								text: \" $msg\",
								type: 'Success',
								timer: 2000,
								showConfirmButton: false });
						</script>";	
		}
	}else{		
		$msg = 'Failed!! Baseline information was not saved!!';
		$results .= "<script type=\"text/javascript\">
						swal({
							title: \"Error!\",
							text: \" $msg\",
							type: 'Danger',
							timer: 3000,
							showConfirmButton: false });
						</script>";
	}
	echo $results;
}


// updating data to tbl_indicator_details
if (isset($_POST['updatebaseline']) && $_POST['updatebaseline'] == 1) {		
	$indicator = $_POST['indid'];
	$formid = $_POST['formid'];
	$inddate = date("Y-m-d");
	$user = $_POST['username'];

	$count = count($_POST["dt3id"]);
	for($j=0; $j<$count; $j++){ 
		$level3id = $_POST["dt3id"][$j];
		$basevalue = $_POST['basevalue'][$j];
		
		$updateform = $db->prepare("UPDATE tbl_indicator_details SET basevalue=:basevalue, updated_by=:user, date_updated=:dates WHERE id=:level3id");
		$updateresult = $updateform->execute(array(":basevalue" => $basevalue, ":user" => $user, ":dates" => $inddate, ":level3id" => $level3id));
	}
	
	$results = '';
	
	if($updateresult){
			
		///////////////////////////////////////////
		//Query the indicator and form details
		///////////////////////////////////////////
		$query_rsFormDetails = $db->prepare("SELECT f.form_name, i.indname, i.indcategory FROM `tbl_indicator_baseline_survey_submission` s INNER JOIN tbl_indicator_baseline_survey_forms f ON f.id=s.formid INNER JOIN tbl_indicator i ON i.indid =s.indid WHERE  s.indid=:indid AND s.formid=:formid GROUP BY i.indname");
		$query_rsFormDetails->execute(array(":indid" => $indicator, ":formid" => $formid));
		$row_rsFormDetails = $query_rsFormDetails->fetch();
		$totalRows_rsFormDetails = $query_rsFormDetails->rowCount();

		/////////////////////////////////////////////
		//	Query section for the answers 
		/////////////////////////////////////////////
		$query_rsSection = $db->prepare("SELECT o.id, o.section  FROM tbl_indicator_baseline_survey_answers a INNER JOIN tbl_indicator_baseline_survey_form_question_fields q ON q.id=a.fieldid INNER JOIN tbl_indicator_baseline_survey_form_sections o ON o.id =q.sectionid WHERE o.formid=:formid GROUP BY q.sectionid ");
		$query_rsSection->execute(array(":formid" => $formid));
		$row_rsSection = $query_rsSection->fetchAll();
		$totalRows_rsSection = $query_rsSection->rowCount();
		
		///////////////////////////////////////////
		//	Query the form sections 
		///////////////////////////////////////////
		$query_rsSections = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_form_sections WHERE  formid=:formid");
		$query_rsSections->execute(array(":formid" => $formid));
		$row_rsSections = $query_rsSections->fetchAll();
		$totalRows_rsSections = $query_rsSections->rowCount();
		
		$query_rsForm = $db->prepare("SELECT indid FROM tbl_indicator_baseline_survey_forms WHERE id='$formid'");
		$query_rsForm->execute();
		$row_rsForm = $query_rsForm->fetch();
		
		$query_rsIndicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid='$indicator' and active='1'");
		$query_rsIndicator->execute();
		$row_rsIndicator = $query_rsIndicator->fetch();
		
		$query_rsOpDept = $db->prepare("SELECT * FROM tbl_sectors WHERE parent='0' AND deleted='0'");
		$query_rsOpDept->execute();
		$row_rsOpDept = $query_rsOpDept->fetch();
		
		$query_baseyear = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE status=1");
		$query_baseyear->execute();
		
		$current_datetime = date("Y-m-d H:i:s");
		while($row_baseyear = $query_baseyear->fetch()){
			$sdate = date("Y-m-d H:i:s", strtotime($row_baseyear["sdate"]));
			$edate = date("Y-m-d H:i:s", strtotime($row_baseyear["edate"]));
			if($sdate <= $current_datetime && $edate >= $current_datetime){
				$baseyear = $row_baseyear["id"];
				$baseyr = $row_baseyear["year"];
				
			}
		}
		
		$results .= '
		<fieldset class="scheduler-border">
			<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Baseline Values</legend>
			<div class="block-header" id="sweetalert">
			</div>
			<div class="col-md-3">
				<label>Indicator Code: '.$row_rsIndicator["indcode"].'</label>
			</div>
			<div  class="col-md-12">
				<label>Indicator Name: '.$row_rsIndicator["indname"].'</label>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:-10px; padding-top:-15px">
				<input name="indid" type="hidden" value="'.$indicator.'">
				<input name="formid" type="hidden" value="'.$formid.'">
				<input name="type" type="hidden" value="time">
				<div class="col-md-4">
					<label>Base-Year *: '.$baseyr.'</label>
				</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:-5px">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover" style="width:100%">
						<thead>
							<tr id="colrow">
								<th width="4%"><strong id="colhead">#</strong></th>
								<th width="76%">Location</th>
								<th width="20%">Base Value</th>
							</tr>
						</thead>
						<tbody>';
							$query_location_level1 =  $db->prepare("SELECT d.id, level1, state FROM tbl_indicator_details d inner join tbl_state s on s.id=d.level1 WHERE indid='$indicator' GROUP BY d.level1 ORDER BY d.id ASC");
							$query_location_level1->execute();
							
							$nm = 0;
							while($rows_level1 = $query_location_level1->fetch()){
								$nm++;
								$dtid = $rows_level1["id"];
								$lv1id = $rows_level1["level1"];
								$level1 = $rows_level1["state"];
								$results .= '<tr style="background-color:#607D8B; color:#FFF">
									<td>'.$nm.'</td>
									<td>'.$level1.' '.$level1label.'</td>
									<td></td>
								</tr>';
		
								$query_location_level2 =  $db->prepare("SELECT  d.id, level2, state FROM tbl_indicator_details d inner join tbl_state s on s.id=d.level2 WHERE indid='$indicator' and level1='$lv1id' GROUP BY level2 ORDER BY d.id ASC");
								$query_location_level2->execute();
								$sr = 0;
								while($rows_level2 = $query_location_level2->fetch()){
									$sr++;
									$dt2id = $rows_level2["id"];
									$lv2id = $rows_level2["level2"];
									$level2 = $rows_level2["state"];
									$results .= '<tr style="background-color:#9E9E9E; color:#FFF">
										<td>'.$nm.'.'.$sr.'</td>
										<td>'.$level2.' '.$level2label.'</td>
										<td></td>
									</tr>';
									
									$query_location_level3 =  $db->prepare("SELECT  d.id, level3, basevalue, state FROM tbl_indicator_details d inner join tbl_state s on s.id=d.level3 WHERE indid='$indicator' and level1='$lv1id' and level2='$lv2id' ORDER BY d.id ASC");
									$query_location_level3->execute();
									
									$nmb = 0;
									while($rows_level3 = $query_location_level3->fetch()){
										$nmb++;
										$xy++;
										$dt3id = $rows_level3["id"];
										$lv3id = $rows_level3["level3"];
										$level3 = $rows_level3["state"];
										$basevalue = $rows_level3["basevalue"];
										$results .= '<tr>
											<td>'.$nm.'.'.$sr.'.'.$nmb.'</td>
											<td>'.$level3.' '.$level3label.'</td>
											<td><input type="number" name="basevalue[]" value="'.$basevalue.'" class="form-control" required>
											<input type="hidden" name="dt3id[]" value="'.$dt3id.'"></td>
										</tr>';						
									}
								}
							}
						$results .= '</tbody>
					</table>
				</div>
			</diV>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:-5px">
				<input type="hidden" name="MM_update" value="updateindfrm" />
				<input name="username" type="hidden" id="username" value="'.$user.'" />
				<input type="hidden" name="updatebaseline" id="updatebaseline" value="1">
				<ul class="list-inline" style="margin-top:20px" align="center">
					<li><input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="bssurvey" value="Update" /></li>
				</ul>
			</div>
		</fieldset>';	
		
		$msg = 'Baseline information successfully Updated';
		$results .= "<script type=\"text/javascript\">
						swal({
							title: \"Success!\",
							text: \" $msg\",
							type: 'Success',
							timer: 3000,
							showConfirmButton: false });
					</script>";	
	}else{		
		$msg = 'Failed!! Baseline information was not Updated!!';
		$results .= "<script type=\"text/javascript\">
			swal({
				title: \"Warning\",
				text: \" $msg \",
				icon: 'warning',
				buttons: false,
				dangerMode: true,
				timer: 3000,
				showConfirmButton: false 
			});
		</script>";
	}
	echo $results;
}

} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>