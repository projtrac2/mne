<?php

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

$current_date = date("Y-m-d");
$user = $_POST['username'];
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

// inserting data to tbl_form and tbl_section form
if (isset($_POST['formname'])) {
	$formName = $_POST['formname'];
	$formdesc = $_POST['description'];
	$responsible = $_POST['responsible'];
	$projid = $_POST['projid'];
	$formInsert = $db->prepare("INSERT INTO tbl_project_evalution_forms (projid, form_name, description, responsible, created_by, date_created) VALUES (:projid, :form, :desc,:responsible, :user, :dates )");
	$resultform = $formInsert->execute(array(":projid" => $projid, ":form" => $formName, ":desc" => $formdesc, ":responsible" => $responsible, ":user" => $user, ":dates" => $current_date));

	if ($resultform) {
		$formid = $db->lastInsertId();
		for ($cnt = 0; $cnt < count($_POST["section"]); $cnt++) {
			$section = $_POST['section'][$cnt];
			$sectionInsert = $db->prepare("INSERT INTO tbl_project_evalution_form_sections (formid, section) VALUES (:formid, :section)");
			$result = $sectionInsert->execute(array(":formid" => $formid, ":section" => $section));
		}
	}

	$query_rsSection = $db->prepare("SELECT * FROM tbl_project_evalution_form_sections WHERE formid='$formid'");
	$query_rsSection->execute();
	$totalRows_rsSection = $query_rsSection->rowCount();
	$count = 1;
	while ($row_rsSection = $query_rsSection->fetch()) {
		echo '<fieldset class="scheduler-border">
			<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">' . $row_rsSection['section'] . '</legend>				
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card" style="margin-bottom:-20px">
						<div class="header">
							<i class="ti-link"></i>MULTIPLE QUESTIONS - WITH CLICK & ADD
						</div>
						<div class="body">';
						echo ' <table class="table table-bordered" id="section_table' . $row_rsSection['id'] . '">
								<tr>
									<th style="width:78%">Question</th>
									<th style="width:20%">Field Type </th>
									<th style="width:2%"><button type="button" name="addplus" onclick="addsection_row' . $row_rsSection['id'] . '();" title="Add another field" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button>
									</th>
								</tr>
								<tr> 
									<input type="hidden" name="section[]" value="' . $row_rsSection['id'] . '">
									<input type="hidden" name="formid" value="' . $formid . '">
									<td>
										<input type="text" name="question' . $row_rsSection['id']  . '[]" id="question' . $row_rsSection['id']  . '[]" class="form-control" placeholder="Enter evaluation question" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
									</td>
									<td>
										<div class="form-line">
											<select name="fieldtype' . $row_rsSection['id']  . '[]" id="fieldtype' . $row_rsSection['id']  . '[]" class="form-control"
												style="border:#CCC thin solid; border-radius:5px"
												data-live-search="true" required>
												<option value="">....Select Field Type....</option>
												<option value="text">Short Answer</option>
												<option value="textarea">Paragraph</option>
												<option value="radio">Multiple Choice</option>
												<option value="checkbox">Checkboxes</option>
												<option value="select">Dropdown</option>
												<option value="file">File Upload</option>
												<option value="email">Email</option>
												<option value="tel">Telephone</option>
												<option value="date">Date</option>
												<option value="datetime">Date Time</option>
												<option value="number">Number</option>
												<option value="range">Range</option>
												<option value="url">URL</option>
											</select>
										</div>
									</td>
									<td></td>
								</tr>
							</table> 
							<script type="text/javascript">
							function addsection_row' . $row_rsSection['id'] . '() {
								$rowno = $("#section_table' . $row_rsSection['id'] . ' tr").length;
								$rowno = $rowno + 1; 
								$("#section_table' . $row_rsSection['id'] . ' tr:last").after(\'<tr id="row' . $row_rsSection['id'] . '\' + $rowno + \'"><td><input type="text" name="question' . $row_rsSection['id']  . '[]" id="question' . $row_rsSection['id']  . '[]" class="form-control" placeholder="Enter Evaluation Question" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td> <td><div class="form-line"> <select name="fieldtype' . $row_rsSection['id']  . '[]" id="fieldtype' . $row_rsSection['id']  . '[]" class="form-control" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>  <option value="">....Select Field Type....</option>  <option value="email">Email</option>  <option value="radio">Radio</option>  <option value="text">Text</option>  <option value="tel">Telephone </option>  <option value="password">Password</option>  <option value="date">Date</option>  <option value="datetime">Date Time Local</option>  <option value="file">File</option>  <option value="image">Image</option>  <option value="month">Month</option>  <option value="number">Number</option>  <option value="range">Range</option>  <option value="url">URL</option>  <option value="week">Week</option>  <option value="checkbox">Checkbox</option> </select> </div></td><td>  <button type="button" class="btn btn-danger btn-sm"  onclick=deletesection_row' . $row_rsSection['id'] . '("row' . $row_rsSection['id'] . '\' +   $rowno + \'")><span class="glyphicon glyphicon-minus"></span></button></td></tr>\');
							}
							function deletesection_row' . $row_rsSection['id'] . '(rowno) {
								$("#" + rowno).remove();
							}
							</script>
						</div>
					</div>
				</div>
			</div>
		</fieldset>';
	}
}


// inserting data into tbl_formfields
if (isset($_POST['formid'])) {
	$formid = $_POST['formid'];
	for ($i = 0; $i < count($_POST["section"]); $i++) {
		$sectionid = $_POST['section'][$i];
		$fldname = $_POST["question" . $sectionid];
		$fieldname  = count($fldname);
		for ($j = 0; $j < $fieldname; $j++) {
			$question = $_POST["question" . $sectionid][$j];
			$fieldtype = $_POST["fieldtype" . $sectionid][$j];
			$objectivesInsert = $db->prepare("INSERT INTO tbl_project_evalution_form_questions (formid, sectionid, question, field_type) VALUES (:formid,:sectionid, :question, :fieldtype)");
			$resultstrplan = $objectivesInsert->execute(array(":formid" => $formid, ":sectionid" => $sectionid, ":question" => $question, ":fieldtype" => $fieldtype));
		}
	}
	echo "You have successfully completed creating this form";
}