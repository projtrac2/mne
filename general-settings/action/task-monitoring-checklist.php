<?php
include_once "controller.php";

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// get monitoring checklist form 
if (isset($_POST['getMonitoringChecklist'])) {
	$taskid = $_POST['taskid'];
	echo ' 
	<fieldset class="scheduler-border">
		<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">TASK MONITORING CHECKLIST</legend>
		<!-- Task Checklist Questions -->
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card" style="margin-bottom:-20px">
					<div class="header">
						<i class="fa fa-ckecklist"></i>ADD MONITORING CHECKLIST FOR THIS TASK
					</div>
					<div class="body">
					<input type="hidden" name="taskid[]" id="taskid"  value="' . $taskid . '">
						<table class="table table-bordered" >
							<thead>
							<tr>
								<th style="width:2%">#</th>
								<th style="width:96%">Add Checklist Question</th>
								<th style="width:2%"><button type="button" name="addplus" onclick="add_list();" title="Add another question" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
							</tr>
							</thead>
							<tbody id="checklist_table">
							<tr>
								<td>1</td>
								<td>
									<input type="text" name="checklist[]" id="checklist[]" class="form-control checklist" placeholder="Define your question here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
								</td>
								<td></td>
							</tr>
							</tbody>
						</table>
						<script type="text/javascript">
							function add_list() {
								$rowno = $("#checklist_table tr").length;
								$rowno = $rowno + 1;
								$listno = $rowno - 1;
								$("#checklist_table tr:last").after(\'<tr id="row\' + $rowno + \'"><td>\' + $listno + \'</td><td><input type="text" name="checklist[]" id="checklist[]" class="form-control checklist"  placeholder="Define your question here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_list("row\' + $rowno + \'")><span class="glyphicon glyphicon-minus"></span></button></td></tr>\');
								numbering();
							}
							function delete_list(rowno) {
								$(\'#\' + rowno).remove();
								numbering()
							}

							function numbering() {
								$("#checklist_table tr").each(function(idx) {
									$(this)
										.children()
										.first()
										.html(idx+1);
								});
							}

							$("#tag-form-submit").click(function(e) {
								e.preventDefault();
								var checklist = [];
								$("input[name=\'checklist[]\']").each(function() {
									checklist.push($(this).val());
								});
								var projid = $("#projid").val();
								var user_name = $("#user_name").val();
								var taskid = $("#taskid").val();
								var name = false;
								$(".checklist").each(function(k, v) {
									var getVal = $(v).val();   
									if (getVal =="") {
										 alert("Insert Data to all fields");
										 name = false;
									} else { 
										name = true;
									} 
								  }); 

								if (checklist && name) {
									$.ajax({
										type: "post",
										url: "general-settings/action/task-monitoring-checklist.php",
										data: {
											addmonitoring: "new",
											checklist: checklist,
											projid: projid,
											taskid: taskid,
											user_name: user_name
										},
										dataType: "json",
										success: function(response) {
											location.reload(true);
											alert(response.messages); 
										}
									});
								} 
							});
						</script>
					</div>
				</div>
			</div>
		</div>
		<!-- Task Checklist Questions -->
	</fieldset>';
}

if (isset($_POST['geteditMonitoringChecklist'])) {
	$taskid = $_POST['taskid'];
	$query_rsChecklist = $db->prepare("SELECT *  FROM tbl_project_monitoring_checklist WHERE  taskid = :taskid");
	$query_rsChecklist->execute(array(":taskid" => $taskid));
	$row_rsChecklist = $query_rsChecklist->fetch();
	$totalRows_rsChecklist = $query_rsChecklist->rowCount();
	echo ' 
	<fieldset class="scheduler-border">
		<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">TASK MONITORING CHECKLIST</legend>
		<!-- Task Checklist Questions -->
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card" style="margin-bottom:-20px">
					<div class="header">
						<i class="fa fa-ckecklist"></i>ADD MONITORING CHECKLIST FOR THIS TASK
					</div>
					<div class="body">
					<input type="hidden" name="taskid[]" id="taskid"  value="' . $taskid . '">
						<table class="table table-bordered" id="checklist_table">
						<tr>
						<th style="width:2%">#</th>
						<th style="width:96%">Add Checklist Question</th>
						<th style="width:2%"><button type="button" name="addplus" onclick="add_list();" title="Add another question" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
					</tr>';
					$rowno = 0;

					do {
						$checklist =  $row_rsChecklist['name'];
						$rowno++;
						echo
							'<tr id="row' . $rowno  . '">
								<td>' . $rowno . '</td>
								<td>
									<input type="text" name="checklist[]" id="checklist" value="' . $checklist . '" class="form-control checklist" placeholder="Define your question here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
								</td>
								<td>
								<button type="button" class="btn btn-danger btn-sm"  onclick=delete_list("row' . $rowno . '")>
									<span class="glyphicon glyphicon-minus"></span>
								</button>
								</td>
							</tr>';
					} while ($row_rsChecklist = $query_rsChecklist->fetch());

					echo '</table>
						<script type="text/javascript">
							function add_list() {
								$rowno = $("#checklist_table tr").length;
								$rowno = $rowno + 1;
								$listno = $rowno - 1;
								$("#checklist_table tr:last").after(\'<tr id="row\' + $rowno + \'"><td>\' + $listno + \'</td><td><input type="text" name="checklist[]" id="checklist[]" class="form-control checklist"  placeholder="Define your question here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_list("row\' + $rowno + \'")><span class="glyphicon glyphicon-minus"></span></button></td></tr>\');
							}
							function delete_list(rowno) {
								$(\'#\' + rowno).remove(); 
							}

							$("#tag-form-submit").click(function(e) {
								e.preventDefault();
								var checklist = [];
								$("input[name=\'checklist[]\']").each(function() {
									checklist.push($(this).val());
								});
						
								var projid = $("#projid").val();
								var user_name = $("#user_name").val();
								var taskid = $("#taskid").val();

								var name = false;
								$(".checklist").each(function(k, v) {
									var getVal = $(v).val();   
									if (getVal =="") {
										 alert("Insert Data to all fields");
										 name = false;
									} else { 
										name = true;
									} 
								  }); 

								if (checklist && name) {
									$.ajax({
										type: "post",
										url: "general-settings/action/task-monitoring-checklist.php",
										data: {
											editmonitoring: "edit",
											checklist: checklist,
											projid: projid,
											taskid: taskid,
											user_name: user_name
										},
										dataType: "json",
										success: function(response) {
											alert(response.messages);
											location.reload(true); 
										}
									});
								}
							});  
						</script>
					</div>
				</div>
			</div>
		</div>
		<!-- Task Checklist Questions -->
	</fieldset>';
}

// adding monitoring checklist to the db 
if (isset($_POST["addmonitoring"])) {
	$current_date = date("Y-m-d H:i:s");
	$projid = $_POST['projid'];
	$taskid = $_POST['taskid'];
	$user_name = $_POST['user_name'];

	for ($j = 0; $j < count($_POST["checklist"]); $j++) {
		$insertSQL = $db->prepare("INSERT INTO tbl_project_monitoring_checklist (projid, taskid, name, created_by, date_created) VALUES (:projid,:taskid, :name,  :username, :datecreated)");
		$results = $insertSQL->execute(array(':projid' => $projid, ':taskid' => $taskid, ':name' => $_POST['checklist'][$j],  ':username' => $user_name, ':datecreated' => $current_date));
	}

	if ($results === TRUE) {
		$valid['success'] = true;
		$valid['messages'] = "Successfully Added";
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while adding the record!!";
	}
	echo json_encode($valid);
}

// updating monitoring checklist 
if (isset($_POST["editmonitoring"])) {
	$current_date = date("Y-m-d H:i:s");
	$projid = $_POST['projid'];
	$taskid = $_POST['taskid'];
	$user_name = $_POST['taskid'];

	$deleteQueryO = $db->prepare("DELETE FROM `tbl_project_monitoring_checklist` WHERE taskid=:taskid");
	$resultsO = $deleteQueryO->execute(array(':taskid' => $taskid));

	for ($j = 0; $j < count($_POST["checklist"]); $j++) {
		$insertSQL = $db->prepare("INSERT INTO tbl_project_monitoring_checklist (projid, taskid, name, created_by, date_created) VALUES (:projid, :taskid, :name, :username, :datecreated)");
		$results = $insertSQL->execute(array("projid" => $projid, ':taskid' => $taskid, ':name' => $_POST['checklist'][$j], ':username' => $user_name, ':datecreated' => $current_date));
	}

	if ($results === TRUE) {
		$valid['success'] = true;
		$valid['messages'] = "Successfully Edited";
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while editing the record!!";
	}
	echo json_encode($valid);
}

// deleting monitoring checklist
if (isset($_POST['deleteMonitoring'])) {
	$itemid = $_POST['itemId'];
	$deleteQuery = $db->prepare("DELETE FROM `tbl_project_monitoring_checklist` WHERE taskid=:itemid");
	$results = $deleteQuery->execute(array(':itemid' => $itemid));

	if ($results === TRUE) {
		$valid['success'] = true;
		$valid['messages'] = "Successfully Deleted";
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while deleting the record!!";
	}
	echo json_encode($valid);
}
