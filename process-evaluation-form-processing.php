<?php

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

$current_date = date("Y-m-d");
$user = $_POST['username'];
//get departments
if (isset($_POST['sec'])) {
	$sec = $_POST['sec'];
	$query_dep = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent='$sec'");
	$query_dep->execute();
	$rowscount = $query_dep->rowCount();
	if($rowscount){
		echo '<option value="">....  Select Department ....</option>';
		while ($row = $query_dep->fetch()) {
			echo '<option value="' . $row['stid'] . '"> ' . $row['sector'] . '</option>';
		}
	}else{
		echo '<option value=""> No defined department for this ministry </option>';
	}
}

//get projects from a given department
if (isset($_POST['dept'])) {
	$getdept = $_POST['dept'];
	$query_proj = $db->prepare("SELECT  * FROM tbl_projects WHERE projdepartment='$getdept'");
	$query_proj->execute();
	$rowscount = $query_proj->rowCount();
	if($rowscount){
		echo '<option value="">....Select Project from list....</option>';
		while ($row = $query_proj->fetch()) {
			echo '<option value="' . $row['projid'] . '"> ' . $row['projname'] . '</option>';
		}
	}else{
		echo '<option value=""> No defined project for this department </option>';
	}
}


//get users from a given department 
if (isset($_POST['member'])) {
	$getmember = $_POST['member'];
	$query_member = $db->prepare("SELECT  * FROM tbl_projteam2 WHERE department='$getmember'");
	$query_member->execute();
	$rowscount = $query_member->rowCount();
	if($rowscount){
		echo '<option value="">.... Select responsible ....</option>';
		while ($row = $query_member->fetch()) {
			echo '<option value="' . $row['ptid'] . '">' . $row['title'] . '. ' . $row['fullname'] . '</option>';
		}
	}else{
		echo '<option value=""> No defined User for this department </option>';
	}
}

//get users from a given department 
if (isset($_POST['limit'])) {
	$limittype = $_POST['limit'];
	if($limittype == 1){
		echo '
		<div class="col-md-3">
			<label class="control-label">Evaluation Start Date *:</label>
			<div class="form-line">
				<input name="startdate" type="date" class="form-control" required>
			</div>
		</div>	
		<div class="col-md-3">
			<label class="control-label">Evaluation End Date *:</label>
			<div class="form-line">
				<input name="enddate" type="date" class="form-control" required>
			</div>
		</div>';
	}else{
		echo '
		<div class="col-md-3">
			<label class="control-label">Number of Responses *:</label>
			<div class="form-line">
				<input name="responsesno" type="number" class="form-control" required>
			</div>
		</div>';
	}
}
/* 
/////////////////////////////////////////
//add to process.php 
/////////////////////////////////////////
if(isset($_POST['prjid'])) {
	$projid = $_POST['prjid'];
	$formid = $_POST['fmid'];
	//encode the url inputs
	$encode_prjid = base64_encode("evprj{$projid}");
	$encode_frmid = base64_encode("evfrm{$formid}");
	
	//var_dump($encode_prjid);	
	require 'PHPMailer/PHPMailerAutoload.php';

	$mail = new PHPMailer;
	$subject = "Project Evaluation Form Link";
	
	
	$query_rsReceipient = $db->prepare("SELECT	* FROM tbl_project_evaluation_forms WHERE  id=:formid");
	$query_rsReceipient->execute(array(":formid" => $formid));
	$row_rsReceipient = $query_rsReceipient->fetch();
	$totalRows_rsReceipient = $query_rsReceipient->rowCount();
	$evalstdate = date("d M Y", strtotime($row_rsReceipient["startdate"]));
	$evalendate = date("d M Y", strtotime($row_rsReceipient["enddate"]));
	
	if ($totalRows_rsReceipient == 0) {
		echo "No users found in the database";
	} else {
		$email = $row_rsReceipient['correspondents'];
		$mails = explode(',', $email);
		$receipients = count($mails);
		for ($i = 0; $i < $receipients; $i++) {
			try {
				$j=$i;
				$inspectoremail = $mails[$i];
				$inspectormail = $mails[$j];
				
				$mail = new PHPMailer(true);
				//Server settings
				//$mail->SMTPDebug = 2;
				$mail->isSMTP();                                            // Set mailer to use SMTP
				$mail->Host       = 'mail.projtrac.co.ke';  // Specify main and backup SMTP servers
				$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
				$mail->Username   = 'info@projtrac.co.ke';                     // SMTP username
				$mail->Password   = 'softcimes@2018';                               // SMTP password
				$mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
				$mail->Port       = 587;                                    // TCP port to connect to

				//Recipients
				$mail->setFrom('info@projtrac.co.ke', 'Projtrac Systems Limited');
				$mail->addAddress($inspectoremail, "Correspondent");     // Add a recipient
				//$mail->addCC('projtracsystems@gmail.com', 'Dennis Kitheka');               // Name is optional
				//$mail->addReplyTo('info@afyasend.com', 'Information');

				//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
				//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
				
				$encode_emailid = base64_encode("eveml{$inspectoremail}");
				$formurl = '<a href="http://104.197.153.188/KFS/public-evaluation-form?prj='.$encode_prjid.'&fm='.$encode_frmid.'&em='.$inspectoremail.'" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Project Evaluation Form</a>';
				include_once("evaluation-form-email-body.php"); 
				
				$mail->isHTML(True);  
				$mail->Subject = $subject; 
				$mail->Body    = $body;
				$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

				$mail->send();
			} catch (Exception $e) {
				//echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}
		}
	}
} */


// inserting data to tbl_form and tbl_section form
if (isset($_POST['formname'])) {
	/* $respondent = $_POST['respondents'];
	$respondents = implode(',', $respondent); */
	$formName = $_POST['formname'];
	$formdesc = $_POST['description'];
	$limittype = $_POST['responseslimit'];
	$responsible = $_POST['responsible'];
	$responsesno = $_POST['responsesno'];
	$startdate = date("Y-m-d", strtotime($_POST['startdate']));
	$enddate = date("Y-m-d", strtotime($_POST['enddate']));
	$evalid = $_POST['evalid'];
	$projid = $_POST['projid'];
	
	//var_dump($formName);
	
	if(!empty($projid) && !empty($formName) && !empty($limittype) && !empty($responsible) && !empty($formdesc)){
		if($limittype == 1){
			$formInsert = $db->prepare("INSERT INTO tbl_project_evaluation_forms (projid, evaluation_id, form_name, description, responsible, limit_type, startdate, enddate, created_by, date_created) VALUES (:projid, :evalid, :form, :desc, :responsible, :limittype, :startdate, :enddate, :user, :dates )");
			$resultform = $formInsert->execute(array(":projid" => $projid, ":evalid" => $evalid, ":form" => $formName, ":desc" => $formdesc, ":responsible" => $responsible, ":limittype" => $limittype, ":startdate" => $startdate, ":enddate" => $enddate, ":user" => $user, ":dates" => $current_date));
			$formid = $db->lastInsertId();
		}else{
			$formInsert = $db->prepare("INSERT INTO tbl_project_evaluation_forms (projid, evaluation_id, form_name, description, responsible, limit_type, responses_number, created_by, date_created) VALUES (:projid, :evalid, :form, :desc, :responsible, :limittype, :responsesno, :user, :dates )");
			$resultform = $formInsert->execute(array(":projid" => $projid, ":evalid" => $evalid, ":form" => $formName, ":desc" => $formdesc, ":responsible" => $responsible, ":limittype" => $limittype, ":responsesno" => $responsesno, ":user" => $user, ":dates" => $current_date));
			$formid = $db->lastInsertId();
		}
		if($resultform){
			echo '<input type="hidden" name="formid" id="formid" value="'.$formid.'">';
		}
	}else{
	}
}

$access = $requireValidOption = $role = $label = $subtype = $style = "";
$name = $className = $placeholder = $description = $required = "";
$toggle = $inline = $other = $maxlength = "";
$max = $min = $step = $hvalue = $multiple = "";
if (isset($_POST['data'])) {
	$formData = $_POST['data'];
	$formDataArray = json_decode($formData, true);
	//var_dump($formDataArray);
	
	$section = $_POST['section'];
	$formid = $_POST['formid'];
	$projid = $_POST['projid'];
	$sectionInsert = $db->prepare("INSERT INTO tbl_project_evaluation_form_sections (formid, section) VALUES (:formid, :section)");
	$result = $sectionInsert->execute(array(":formid" => $formid, ":section" => $section));
		
	if($result){
		$select_sectionid = $db->prepare("select * from tbl_project_evaluation_form_sections ORDER BY id DESC LIMIT 1");
		$select_sectionid->execute();
		$row_sectionid = $select_sectionid->fetch();
		$sectionid = $row_sectionid["id"];
		
		foreach ($formDataArray as $key => $value){
			$type = $value['type'];
			if (isset($value['required'])) {
				$required = $value['required'];
			}
			if (isset($value['maxlength'])) {
				$maxlength = $value['maxlength'];
			}
			if (isset($value['rows'])) {
				$rows = $value = $value['rows'];
			}
			if (isset($value['multiple'])) {
				$multiple = $value['multiple'];
			}
			if (isset($value['toggle'])) {
				$toggle = $value['toggle'];
			}
			if (isset($value['inline'])) {
				$inline = $value['inline'];
			}
			if (isset($value['other'])) {
				$other = $value['other'];
			}
			if (isset($value['description'])) {
				$description = $value['description'];
			}
			if (isset($value['placeholder'])) {
				$placeholder = $value['placeholder'];
			}
			if (isset($value['className'])) {
				$className = $value['className'];
			}
			if (isset($value['name'])) {
				$name = $value['name'];
			}
			if (isset($value['access'])) {
				$access = $value['access'];
			}
			if (isset($value['requireValidOption'])) {
				$requireValidOption = $value['requireValidOption'];
			}
			if (isset($value['role'])) {
				$role = $value['role'];
			}
			if (isset($value['label'])) {
				$label = $value['label'];
			}
			if (isset($value['subtype'])) {
				$subtype = $value['subtype'];
			}
			if (isset($value['style'])) {
				$style = $value['style'];
			}
			if (isset($value['value'])) {

				$hvalue = $value['value'];
			}
			if (isset($value['min'])) {
				$min = $value['min'];
			}
			if (isset($value['max'])) {
				$max = $value['max'];
			}
			if (isset($value['step'])) {
				$step = $value['step'];
			}
			
			$formfields = $db->prepare("INSERT INTO tbl_project_evaluation_form_question_fileds (formid, sectionid, fieldtype, access, requirevalidoption, 
			fieldrole, label, subtype, style, fieldname, classname, placeholder, fielddesc, fieldrequired, toggle, inline, other, 
			fieldmaxlength, fieldmin, fieldmax, step, fieldvalue, multiple ) VALUES (:formid, :sectionid, :ftype, :access, :requireValidOption, 
			:fRole, :label, :subtype, :style, :Fname, :className, :placeholder, :Fdescription, :Frequired, :toggle, :inline, :other, 
			:Fmaxlength, :Fmin, :Fmax, :step, :Fvalue, :multiple)");
			$results = $formfields->execute(array(
				":formid" => $formid, ":sectionid" => $sectionid, ":ftype" => $type, ":access" => $access, ":requireValidOption" => $requireValidOption,
				":fRole" => $role, ":label" => $label, ":subtype" => $subtype, ":style" => $style, ":Fname" => $name, ":className" => $className, ":placeholder" => $placeholder,
				":Fdescription" => $description, ":Frequired" => $required, ":toggle" => $toggle, ":inline" => $inline, ":other" => $other,
				":Fmaxlength" => $maxlength, ":Fmin" => $min, ":Fmax" => $max, ":step" => $step, ":Fvalue" => $hvalue, ":multiple" => $multiple
			));

			if($results){
				if (($type == "select") || ($type == "radio-group") || ($type == "checkbox-group")) {
					$select_fieldid = $db->prepare("select * from tbl_project_evaluation_form_question_fileds ORDER BY id DESC LIMIT 1");
					$select_fieldid->execute();
					$row_fieldid = $select_fieldid->fetch();
					$fieldid = $row_fieldid["id"];
					
					$fieldvalues = $value['values'];
					foreach ($fieldvalues as $val) {
						$label = $val['label'];
						$fvalue = $val['value'];
						$valInsert = $db->prepare("INSERT INTO tbl_project_evaluation_form_question_filed_values (fieldid, label, value) VALUES (:fieldid, :label, :val)");
						$result = $valInsert->execute(array(":fieldid" => $fieldid, ":label" => $label, ":val" => $fvalue));
					}
				} 
			}
		}
		
		$status = 1;
		$updateQuery = $db->prepare("UPDATE tbl_projects_evaluation SET status=:status WHERE id=:evalid");
		$updatest = $updateQuery->execute(array(':status' => $status, ':evalid' => $evaluationid));	
		
		$msg = 'Form fields successfully saved.';
		$results = "<script type=\"text/javascript\">
			swal({
			title: \"Success!\",
			text: \" $msg\",
			type: 'Success',
			timer: 3000,
			showConfirmButton: false });
		</script>";	
		echo $results;		
	}
}

//get the preview of the forms and also used to get the final form 
if (isset($_POST['fdata'])) {

	$formid = $_POST['fdata'];
	$query_rsForm = $db->prepare("SELECT * FROM tbl_project_evaluation_forms WHERE id=:formid");
	$query_rsForm->execute(array(":formid" => $formid));
	$row_rsForm = $query_rsForm->fetch();
	
	$query_rsSection = $db->prepare("SELECT * FROM tbl_project_evaluation_form_sections WHERE  formid=:formid");
	$query_rsSection->execute(array(":formid" => $formid));
	$row_rsSection = $query_rsSection->fetchAll();
	$totalRows_rsSection = $query_rsSection->rowCount();

	echo '<h4 class="text-align-center bg-light-green" style="border-radius:4px; padding:10px">FORM NAME: '.$row_rsForm["form_name"].'</h4>';
	if ($totalRows_rsSection == 0) {
		echo '<h4 class="text-align-center" style="color:red">No records found in the field table</h4>';
	} else {
		$count = 0;
		foreach ($row_rsSection as $key) {
			$section = $key['section'];
			$count++;
			$sectionid = $key['id'];
			$query_rsFormfield = $db->prepare("SELECT * FROM tbl_project_evaluation_form_question_fileds WHERE formid=:formid AND sectionid=:sectionid ORDER BY id");
			$query_rsFormfield->execute(array(":formid" => $formid, ":sectionid" => $sectionid));
			$row_rsFormfield = $query_rsFormfield->fetchAll();
			$totalRows_rsFormfield = $query_rsFormfield->rowCount();

			if ($totalRows_rsFormfield == 0) {
				echo '<h4 class="text-align-center" style="color:red">No records found in the field table</h4>';
			} else {
				$cnt = 0;
				echo '
				<fieldset class="scheduler-border">
					<legend class="scheduler-border bg-light-blue" style="border-radius:3px">Objective ' . $count . ':</label> ' . $section . '</legend>';
				echo '
				<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover" style="color:#000">
						<thead>
							<tr style="background-color:#607D8B; color:#FFF">
								<th width="3%">SN</th>
								<th width="97%">Question</th>
							</tr>
						</thead>
						<tbody>';
							foreach ($row_rsFormfield as $field){
								$cnt++;
								$type = $field['fieldtype'];
								$requireValidOption = $field['requirevalidoption'];
								$label = $field['label'];
								$subtype = $field['subtype'];
								$style = $field['style'];
								$name = $field['fieldname'];
								$placeholder = $field['placeholder'];
								$description = $field['fielddesc'];
								$other = $field['other'];
								$maxlength = $field['fieldmaxlength'];
								$max = $field['fieldmin'];
								$min = $field['fieldmax'];
								$multiple = $field['multiple'];
								$fieldid = $field['id'];

								if ($type == "textarea") {
									echo '
									<tr>
										<td>'.$cnt.'</td>
										<td><label class="control-label">' . $label  . '*: <font align="left" style="background-color:#eff2f4">
											(' .  $placeholder . '.) </font></label>
											<div class="form-line">
												<p align="left">
													<textarea name="' . $name . '" cols="45" rows="5" class="txtboxes" id="' . $name .$fieldid. '" required="required"
														style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"
														placeholder="' . $placeholder . '"></textarea>
													<script>
													CKEDITOR.replace("' . $name . $fieldid.'", {
														on: {
															instanceReady: function(ev) {
																// Output paragraphs as <p>Text</p>.
																this.dataProcessor.writer.setRules("p", {
																	indent: false,
																	breakBeforeOpen: false,
																	breakAfterOpen: false,
																	breakBeforeClose: false,
																	breakAfterClose: false
																});
																this.dataProcessor.writer.setRules("ol", {
																	indent: false,
																	breakBeforeOpen: false,
																	breakAfterOpen: false,
																	breakBeforeClose: false,
																	breakAfterClose: false
																});
																this.dataProcessor.writer.setRules("ul", {
																	indent: false,
																	breakBeforeOpen: false,
																	breakAfterOpen: false,
																	breakBeforeClose: false,
																	breakAfterClose: false
																});
																this.dataProcessor.writer.setRules("li", {
																	indent: false,
																	breakBeforeOpen: false,
																	breakAfterOpen: false,
																	breakBeforeClose: false,
																	breakAfterClose: false
																});
															}
														}
													});
													</script>
												</p>
											</div>
										</td>
									</tr>';
								} else if ($type == "radio-group") {
									echo '
									<tr>
										<td>'.$cnt.'</td>
										<td><label class="control-label">' . $label  . '*:</label>
											<div class="form-line">';
												$query_rsValue = $db->prepare("SELECT * FROM tbl_project_evaluation_form_question_filed_values WHERE sectionid=:sectionid AND fieldid=:field");
												$query_rsValue->execute(array(":sectionid" => $sectionid, ":field" => $fieldid));
												$row_rsValue = $query_rsValue->fetchAll();
												$totalRows_rsValue = $query_rsValue->rowCount();
												if ($totalRows_rsValue == 0) {
													echo '<h4 class="text-align-center" style="color:red">No records found in the field table</h4>';
												} else {
													$nm=0;
													echo '
													<div class="demo-radio-button">';
														foreach ($row_rsValue as $row) {
															$nm++;
															$lab = $row['label'];
															$option = $row['val'];
															$vlid = $row['id'];
															echo '
															<input type="radio" name="' .$fieldid. $name . '" id="rd'.$sectionid.$fieldid.$vlid.'" value="' . $option . '" class="with-gap radio-col-green"  required/>
															<label for="rd'.$sectionid.$fieldid.$vlid.'">' . $lab . '</label>';
														}
													echo '</div>';
												}
											echo '</div>
										</td>
									</tr>';
								} else if ($type == "select" || $type == "autocomplete") {
									$query_rsValue = $db->prepare("SELECT * FROM tbl_project_evaluation_form_question_filed_values WHERE sectionid=:sectionid AND fieldid=:field");
									$query_rsValue->execute(array(":sectionid" => $sectionid, ":field" => $fieldid));
									$row_rsValue = $query_rsValue->fetchAll();
									$totalRows_rsValue = $query_rsValue->rowCount();
									if ($totalRows_rsValue == 0) {
										echo'<h4 class="text-align-center" style="color:red">No records found in the field table</h4>';
									} else{
										if ($multiple == 1) {
											echo '
											<tr>
												<td>'.$cnt.'</td>
												<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
													<div class="form-line">
														<select name="' . $name . '" id="' . $name . '" class="selectpicker" multiple style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
															<option value="">....Select ' . $name . ' first....</option>';
															foreach ($row_rsValue as $row) {
																$desc = $row['label'];
																$option = $row['val'];
																echo '<option value="' . $option . '">' . $desc . '</option>';
															}
														echo '</select>
													</div>
												</td>
											</tr>';
										} else {
											echo '
											<tr>
												<td>'.$cnt.'</td>
												<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
													<div class="form-line">
														<select name="' . $name . '" id="' . $name . '" class="form-control" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
															<option value="">....Select ' . $name . ' first....</option>';
															foreach ($row_rsValue as $row) {
																$desc = $row['label'];
																$option = $row['val'];
																echo '<option value="' . $option . '">' . $desc . '</option>';
															}
														echo '</select>
													</div>
												</td>
											</tr>';
										}
									}
								} else if ($type == "date") {
									echo '
									<tr>
										<td>'.$cnt.'</td>
										<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
											<div class="form-line">
												<input type="date" name="' . $name . '" id="" class="form-control" placeholder="' . $placeholder . '" aria-describedby="helpId"">
											</div>
										</td>
									</tr>';
								} else if ($type == "header") {
									echo '
									<tr>
										<td>'.$cnt.'</td>
										<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
											<div class="form-line">
											<' . $subtype . '>' . $label . '</' . $subtype . '>
											</div>
										</td>
									</tr>';
								} else if ($type == "number") {
									echo '
									<tr>
										<td>'.$cnt.'</td>
										<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
											<div class="form-line">
												<input type="number" name="' . $name . '" id="" class="form-control" placeholder="' . $placeholder . '" aria-describedby="helpId" max="' . $max . '" min=" ' . $min . ' ">
												<small id="helpId" class="text-muted">' . $label . '</small>
											</div>
										</td>
									</tr>';
								} else if ($type == "paragraph") {
									echo '
									<tr>
										<td>'.$cnt.'</td>
										<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
											<div class="form-line">
												<' . $subtype . '> </' . $subtype . '>
											</div>
										</td>
									</tr>';
								} else if ($type == "checkbox-group") {
									echo '
									<tr>
										<td>'.$cnt.'</td>
										<td><label class="control-label">' . $label  . '*:</label>
											<div class="form-line">';
												$query_rsValue = $db->prepare("SELECT * FROM tbl_project_evaluation_form_question_filed_values WHERE sectionid=:sectionid AND fieldid=:field");
												$query_rsValue->execute(array(":sectionid" => $sectionid, ":field" => $fieldid));
												$row_rsValue = $query_rsValue->fetchAll();
												$totalRows_rsValue = $query_rsValue->rowCount();
												if ($totalRows_rsValue == 0) {
													echo'<h4 class="text-align-center" style="color:red">No records found in the field table</h4>';
												}else {
													$nm=0;
													echo '
													<div class="demo-checkbox">';
														foreach ($row_rsValue as $row) {
															$nm++;
															$lab = $row['label'];
															$option = $row['val'];
															$vlid = $row['id'];
															echo '
															<input type="checkbox" name="' . $name . '" id="'.$sectionid.$fieldid.$vlid.'" value="' . $option . '" class="filled-in chk-col-light-blue"  required/>
															<label for="'.$sectionid.$fieldid.$vlid.'">' . $lab . '</label>';
														}
													echo '</div>';
												}
											echo '</div>
										</td>
									</tr>';
								} else if ($type == "autocomplete") {
									$query_rsValue = $db->prepare("SELECT * FROM tbl_project_evaluation_form_question_filed_values WHERE sectionid=:sectionid AND fieldid=:field");
									$query_rsValue->execute(array(":sectionid" => $sectionid, ":field" => $fieldid));
									$row_rsValue = $query_rsValue->fetchAll();
									$totalRows_rsValue = $query_rsValue->rowCount();
									if ($totalRows_rsValue == 0) {
										echo	'<h4 class="text-align-center" style="color:red">No records found in the field table</h4>';
									} else { }
								} else if ($type == "text") {
									echo '
									<tr>
										<td>'.$cnt.'</td>
										<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
											<div class="form-line">
												<input type=" ' . $subtype . '" name="' . $name . '" id="' . $name . '" class="form-control" placeholder="' . $placeholder . '" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
											</div>
										</td>
									</tr>';
								} else if ($type == "file") {
									if ($multiple == 1) {
										echo '
										<tr>
											<td>'.$cnt.'</td>
											<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
												<div class="form-line">
													<input type="file" name="' . $name . '" id="' . $name . '" multiple class="form-control form-control-file">
													<small id="helpId" class="text-muted">' . $placeholder . '</small>
												</div>
											</td>
										</tr>';
									} else {
										echo '
										<tr>
											<td>'.$cnt.'</td>
											<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
												<div class="form-line">
													<input type="file" name="' . $name . '" id="' . $name . '"  class="form-control form-control-file">
													<small id="helpId" class="text-muted">' . $placeholder . '</small>
												</div>
											</td>
										</tr>';
									}
								}
							}
						echo '
						</tbody>
					</table>
				</div>
				</div>';
			}
			echo'</fieldset>';
		}
	}
}
//update project status 
if(isset($_POST['prjid'])) {
	$projid = $_POST['prjid'];
	$formid = $_POST['fmid'];
	$projstage = 2;
	$query_projupdate = $db->prepare("UPDATE tbl_projects SET projstage=:projstage WHERE projid=:projid");
	$query_projupdate->execute(array(":projid" => $projid, ":projstage" => $projstage));
	
	$formurl = '<a href="evaluation-form?prj='.$projid.'&fm='.$formid.'" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">View Your Form</a>';
	
	echo $formurl;
}

/* if (isset($_POST['conclusion'])) {
	$conclusion = $_POST['conclusion'];
	$recommendation = $_POST['recommendation'];
	$user = $_POST['username'];
	$projid = $_POST['projid'];
	$formid = $_POST['formid'];
	
	$formInsert = $db->prepare("INSERT INTO tbl_project_evaluation_conclusion (projid, formid, conclusion, recommendation, user, date) VALUES (:projid, :formid, :conclusion, :recommendation, :user, :date)");
	$resultform = $formInsert->execute(array(":projid" => $projid, ":formid" => $formid, ":conclusion" => $conclusion, ":recommendation" => $recommendation, ":user" => $user, ":date" => $current_date));
	//var_dump("YES");
	if($resultform){
		$msg = 'Data successfully submitted.';
		var_dump($msg);
		$results = "<script type=\"text/javascript\">
						swal({
						title: \"Success!\",
						text: \" $msg\",
						type: 'Success',
						timer: 3000,
						showConfirmButton: false });
						setTimeout(function(){
							window.location.href = 'projects-evaluation';
						}, 3000);
					</script>";	
		echo $results;
	}
} */