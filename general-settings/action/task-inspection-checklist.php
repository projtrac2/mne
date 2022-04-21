<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//include_once 'projtrac-dashboard/resource/session.php';
include_once '../../projtrac-dashboard/resource/Database.php';
// include_once '../../projtrac-dashboard/resource/utilities.php';

// get add insoection checklist table 
if (isset($_POST['getaddInspectionChecklist'])) {
	$taskid = $_POST['tkid'];
	var_dump($taskid);
	$query_taskd =  $db->prepare("SELECT t.projid, t.msid, o.indicator FROM tbl_task t inner join tbl_project_output_details o ON o.projoutputid=t.outputid WHERE tkid = :taskid LIMIT 1");
	$query_taskd->execute(array(":taskid" => $taskid));
	$row_taskd = $query_taskd->fetch();
	$projid = $row_taskd['projid'];
	$msid = $row_taskd['msid'];
	$outputid = $row_taskd['indicator'];

	$query_projchklstname =  $db->prepare("SELECT id, name FROM tbl_inspection_checklist WHERE output = :outputid ORDER BY id ASC");
	$query_projchklstname->execute(array(":outputid" => $outputid));
	$row_projchklstname = $query_projchklstname->fetchAll();

	$query_topic = $db->prepare("SELECT topic, id FROM tbl_inspection_checklist_topics");
	$query_topic->execute();
	$row_topic = $query_topic->fetch();
	echo '
	<fieldset class="scheduler-border">
		<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">TASK INSPECTION CHECKLIST</legend>
		<input type="hidden" name="taskid[]" id="taskid"  value="' . $taskid . '"> 
		<input type="hidden" name="msid[]" id="msid"  value="' . $msid . '">  
		<div  class="col-md-6">
			<label>Select The Checklist Below *:</label>
			<div class="form-line">
				<select name="opchecklist" id="opchecklist" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px"  required>
					<option value="" selected="selected" class="selection">Select Inspection Checklist</option> ';
					foreach ($row_projchklstname as $row) {
						echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
					}
					echo '
				</select>
			</div>
		</div>

		<!-- Task Checklist Questions -->
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="checklist">
			</div>
		</div>
		<!-- Task Checklist Questions -->
	</fieldset>
	<script>
	$("#opchecklist").on("change", function() {
		var chk_id = $(this).val();
		if (chk_id) {
			$.ajax({
				type: "POST",
				url: "general-settings/action/task-inspection-checklist",
				data: {
					chkid:chk_id
				},
				success: function(html) {
					$("#checklist").html(html);
				}
			});
		} else {
			$("#checklist").html("Please Select Another Checklist");
		}
	});
	  
			function add_list() {
				$rowno = $("#checklist_table_body tr").length;
				$rowno = $rowno + 1;
				$listno = $rowno - 1;
				$("#checklist_table_body tr:last").after(\'<tr id="row\' + $rowno + \'"><td>\' + $listno + \'</td><td><select name="topic[]" id="topic" class="form-control" required="required"><option value="">Select Topic</option>';
	do {
		echo
			'<option value="' . $row_topic['id'] . '">' . $row_topic['topic'] . '</option>';
	} while ($row_topic = $query_topic->fetch());
	echo '</select></td><td><input type="text" name="checklist[]" id="checklist[]" class="form-control"  placeholder="Define your question here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_list("row\' + $rowno + \'")><span class="glyphicon glyphicon-minus"></span></button></td></tr>\');
	numbering_insp();               
} 
				function delete_list(rowno) {
					$(\'#\' + rowno).remove();
					numbering_insp();
					}

					function numbering_insp() {
						$("#checklist_table_body tr").each(function(idx) {
							$(this)
								.children()
								.first()
								.html(idx + 1);
						});

						console.log("This is numbering");
					}

					$("#tag-form-submit").click(function(e) {
						e.preventDefault();
						var checklist = [];
						$("input[name=\'checklist[]\']").each(function() {
						  checklist.push($(this).val());
						});
					  
						var question = [];
						$("input[name=\'question[]\']:checked").each(function() {
						  question.push($(this).val());
						});

						var topic = [];
						$("select[name=\'topic[]\']").each(function() {
						  topic.push($(this).val());
						}); 
						var msid = $("#msid").val();
						var projid = $("#projid").val();
						var taskid = $("#taskid").val();
						var user_name = $("#user_name").val();
						var opchecklist = $("#opchecklist").val();
						$.ajax({
						  type: "post",
						  url: "general-settings/action/task-inspection-checklist",
						  data: {
							addInsChecklist:"new",
							topic: topic,
							question: question,
							checklist: checklist,
							msid: msid, 
							projid: projid,
							user_name: user_name,
							taskid: taskid,
							opchecklist: opchecklist
						  },
						  dataType: "json",
						  success: function(response) { 
							if (response.success == true) { 
								alert(response.messages);
								$(".modal").each(function() {
								  $(this).modal("hide");
								});
								location.reload(true);
							  } else {
								alert(response.messages);
								location.reload(true);
							  }
						  }
						});
					  });
	</script>';
}

// get editing inpectin checklist table 
if (isset($_POST['geteditInspectionChecklist'])) {
	$taskid = $_POST['tkid'];
	
	$query_taskd =  $db->prepare("SELECT projid, msid, outputid FROM tbl_task WHERE tkid = :taskid");
	$query_taskd->execute(array(":taskid" => $taskid));
	$row_taskd = $query_taskd->fetch();
	$projid = $row_taskd['projid'];
	$msid = $row_taskd['msid'];
	$outputid = $row_taskd['outputid'];

	$query_projinspQsn = $db->prepare("SELECT questionid FROM tbl_project_inspection_checklist WHERE taskid = :taskid ORDER BY ckid DESC LIMIT 1");
	$query_projinspQsn->execute(array(":taskid" => $taskid));
	$row_projinspQsn = $query_projinspQsn->fetch();
	$questionid = $row_projinspQsn["questionid"];
	
	$query_checklist =  $db->prepare("SELECT checklistname FROM tbl_inspection_checklist_questions WHERE id = :questionid");
	$query_checklist->execute(array(":questionid" => $questionid));
	$row_checklist = $query_checklist->fetch();
	$chkid = $row_checklist["checklistname"];
	
	$query_projchklstname =  $db->prepare("SELECT id, name FROM tbl_inspection_checklist WHERE output = :outputid ORDER BY id ASC");
	$query_projchklstname->execute(array(":outputid" => $outputid));
	$row_projchklstname = $query_projchklstname->fetchAll();

	$query_topic = $db->prepare("SELECT topic, id FROM tbl_inspection_checklist_topics WHERE active=1");
	$query_topic->execute();
	$row_topic = $query_topic->fetch();

	$query_topics = $db->prepare("SELECT DISTINCT topic FROM tbl_inspection_checklist_questions WHERE checklistname = :chkid");
	$query_topics->execute(array(":chkid" => $chkid));
	$count_topics = $query_topics->rowCount();

	function validateQuestion($questionid, $taskid)
	{
		global $db;
		$query_projchecklistEdit =  $db->prepare("SELECT questionid FROM tbl_project_inspection_checklist WHERE questionid = :questionid and taskid = :taskid");
		$query_projchecklistEdit->execute(array(":questionid" => $questionid, ":taskid" => $taskid));
		$row_projchecklistEdit = $query_projchecklistEdit->fetch();
		$count_topics = $query_projchecklistEdit->rowCount();
		if ($count_topics > 0) {
			return true;
		} else {
			return false;
		}
	}

	echo '
	<fieldset class="scheduler-border">
		<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">TASK INSPECTION CHECKLIST</legend>
		<input type="hidden" name="taskid[]" id="taskid"  value="' . $taskid . '"> 
		<input type="hidden" name="msid[]" id="msid"  value="' . $msid . '">  
		<div  class="col-md-6">
			<label>Select The Checklist Below *:</label>
			<div class="form-line">
				<select name="opchecklist" id="opchecklist" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px"  required>
					<option value="" selected="selected" class="selection">Select Inspection Checklist</option> ';
					foreach ($row_projchklstname as $row) {
						if ($chkid == $row["id"]) {
							echo '<option value="' . $row["id"] . '" selected> ' . $row["name"] . '</option>';
						} else {
							echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
						}
					}
					echo '
				</select>
			</div>
		</div>

		<!-- Task Checklist Questions -->
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="checklist"> 
				<div class="card" style="margin-bottom:-20px">
					<div class="header">
						<i class="fa fa-ckecklist"></i>Choose the questions applicable for this task!!
					</div>
					<div class="body">
						<fieldset class="scheduler-border">
							<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Checklist </legend>
							<div class="table-responsive"> 
								<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
									<thead>
										<tr class="bg-blue-grey">
											<th style="width:5%">#</th>
											<th style="width:85%">Checklist Topic(s)</th>
											<th style="width:10%">Action</th>
										</tr>
									</thead>
									<tbody>';
										if ($count_topics == 0) {
											echo '<tr>
												<td  colspan="3"><div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div></td>
											</tr>';
										} else {
											$nm = 0;
											while ($rows_topics = $query_topics->fetch()) {
												$nm = $nm + 1;
												if ($nm == 1) {
													$nmb = "A";
												} elseif ($nm == 2) {
													$nmb = "B";
												} elseif ($nm == 3) {
													$nmb = "C";
												} elseif ($nm == 4) {
													$nmb = "D";
												} elseif ($nm == 5) {
													$nmb = "E";
												} elseif ($nm == 6) {
													$nmb = "F";
												} elseif ($nm == 7) {
													$nmb = "G";
												} elseif ($nm == 8) {
													$nmb = "H";
												} elseif ($nm == 9) {
													$nmb = "I";
												} elseif ($nm == 10) {
													$nmb = "J";
												} elseif ($nm == 11) {
													$nmb = "K";
												} elseif ($nm == 12) {
													$nmb = "L";
												} elseif ($nm == 13) {
													$nmb = "M";
												} elseif ($nm == 14) {
													$nmb = "N";
												} elseif ($nm == 15) {
													$nmb = "O";
												} elseif ($nm == 16) {
													$nmb = "P";
												} elseif ($nm == 17) {
													$nmb = "Q";
												} elseif ($nm == 18) {
													$nmb = "R";
												} elseif ($nm == 19) {
													$nmb = "S";
												} elseif ($nm == 20) {
													$nmb = "T";
												} elseif ($nm == 21) {
													$nmb = "U";
												} elseif ($nm == 22) {
													$nmb = "V";
												} elseif ($nm == 23) {
													$nmb = "W";
												} elseif ($nm == 24) {
													$nmb = "X";
												} elseif ($nm == 25) {
													$nmb = "Y";
												} elseif ($nm == 26) {
													$nmb = "Z";
												}
												
												$topicid = $rows_topics['topic'];

												$query_topicname = $db->prepare("SELECT topic FROM tbl_inspection_checklist_topics WHERE id = :topicid");
												$query_topicname->execute(array(":topicid" => $topicid));
												$row_topicname = $query_topicname->fetch();
												$topicname = $row_topicname["topic"];

												$query_questions = $db->prepare("SELECT id, question FROM tbl_inspection_checklist_questions WHERE topic = :topicid AND checklistname = :chkid");
												$query_questions->execute(array(":topicid" => $topicid, ":chkid" => $chkid));

												echo '<tr style="background-color:#CDDC39; color:#2196F3">
													<td align="center">' . $nmb . '</td>
													<td><strong>' . $topicname . '</strong></td>
													<td></td>
												</tr>
												<tr style="background-color:#9E9E9E; color:#FFF">
													<th>#</th>
													<th>Checklist Question</th>
													<th>Select</th>
												</tr>';
												$num = 0;
												while ($row_questions = $query_questions->fetch()) {
													$num = $num + 1;
													$question = $row_questions["question"];
													$questionid = $row_questions["id"];

													$handler = validateQuestion($questionid, $taskid);

													echo '<tr style="background-color:#eff9ca">
														<td align="center">' . $nmb . '.' . $num . '</td>
														<td>' . $question . '</td>
														<td>';
														if ($handler == true) {
															echo '<input type="checkbox" name="question[]" id="qsn' . $nmb . $num . '" value="' . $questionid . '" checked="checked" class="filled-in chk-col-blue"/>
																	<label for="qsn' . $nmb . $num . '"></label>
																';
														} else {
															echo '<input type="checkbox" name="question[]" id="qsn' . $nmb . $num . '" value="' . $questionid . '" class="filled-in chk-col-blue"/>
																	<label for="qsn' . $nmb . $num . '"></label>
																';
														}
														echo '</td>
													</tr>';
												}
											}
										}
									echo '</tbody>
								</table>
							</div>
						</fieldset>';
						
						$query_topic = $db->prepare("SELECT id, topic FROM tbl_inspection_checklist_topics");
						$query_topic->execute();
						$row_topic = $query_topic->fetch();
						
						$data = '';
						do {
							$topic = $row_topic["topic"];
							$data .= '<option value="' . $row_topic["id"] . '">' . $row_topic["topic"] . '</option>';
						} while ($row_topic = $query_topic->fetch());

						echo '<fieldset class="scheduler-border">
							<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Add New Questions</legend>
							<div class="table-responsive">
								<table class="table table-bordered" >
									<thead>
										<tr>
											<th style="width:2%">#</th>
											<th style="width:40%">Topic </th>
											<th style="width:50%">Add Checklist Question</th>
											<th style="width:2%"><button type="button" name="addplus" onclick="add_list();" title="Add another question" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
										</tr>
									</thead>
									<tbody id="checklist_table_body">
									<tr>
										<td>1</td> 
										<td>
										<select name="topic[]" id="topic" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px"  required>
										<option value="">... Select ...</option>
										' .
										$data
										. '</select>
										</td>
										<td>
											<input type="text" name="checklist[]" id="checklist[]" class="form-control" placeholder="Define your question here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" >
										</td>
										<td></td>
									</tr>
									</tbody>
								</table> 
							</div>
						</fieldset> 
					</div>
				</div> 
			</div>
		</div>
		<!-- Task Checklist Questions -->
	</fieldset>
	<script>
	$("#opchecklist").on("change", function() {
		var chk_id = $(this).val();
		if (chk_id) {
			$.ajax({
				type: "POST",
				url: "general-settings/action/task-inspection-checklist",
				data: {
					chkid:chk_id
				},
				success: function(html) {
					$("#checklist").html(html);
				}
			});
		} else {
			$("#checklist").html("Please Select Another Checklist");
		}
	});

	function add_list() {
		$rowno = $("#checklist_table_body tr").length;
		$rowno = $rowno + 1;
		$listno = $rowno - 1;
		$("#checklist_table_body tr:last").after(\'<tr id="row\' + $rowno + \'"><td>\' + $listno + \'</td><td><select name="topic[]" id="topic" class="form-control" required="required"><option value="">Select Topic</option>';
		do {
			echo
				'<option value="' . $row_topic['id'] . '">' . $row_topic['topic'] . '</option>';
		} while ($row_topic = $query_topic->fetch());
		echo '</select></td><td><input type="text" name="checklist[]" id="checklist[]" class="form-control"  placeholder="Define your question here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_list("row\' + $rowno + \'")><span class="glyphicon glyphicon-minus"></span></button></td></tr>\');
		numbering_insp();               
	} 
		
	function delete_list(rowno) {
		$(\'#\' + rowno).remove();
		numbering_insp();
	}

	function numbering_insp() {
		$("#checklist_table_body tr").each(function(idx) {
			$(this)
				.children()
				.first()
				.html(idx + 1);
		});
		console.log("Checklist")
	}

	$("#tag-form-submit").click(function(e) {
		e.preventDefault();
		var checklist = [];
		$("input[name=\'checklist[]\']").each(function() {
		  checklist.push($(this).val());
		}); 

		var question = [];
		$("input[name=\'question[]\']:checked").each(function() {
		  question.push($(this).val());
		});
	  
		var topic = [];
		$("select[name=\'topic[]\']").each(function() {
		  topic.push($(this).val());
		});
	  
		var msid = $("#msid").val();
		var projid = $("#projid").val();
		var taskid = $("#taskid").val();
		var user_name = $("#user_name").val();
		var opchecklist = $("#opchecklist").val();
		$.ajax({
		  type: "post",
		  url: "general-settings/action/task-inspection-checklist",
		  data: {
			editInsChecklist:"new",
			topic: topic,
			question: question,
			checklist: checklist,
			msid: msid, 
			projid: projid,
			user_name: user_name,
			taskid: taskid,
			opchecklist: opchecklist
		  },
		  dataType: "json",
		  success: function(response) { 
			if (response.success == true) { 
				alert(response.messages);
				$(".modal").each(function() {
				  $(this).modal("hide");
				});
				location.reload(true);
			  } else {
				alert(response.messages);
				location.reload(true);
			  }
		  }
		});
	});
	</script>';
}

// get the topics and cheklist questions 
if (isset($_POST["chkid"]) && !empty($_POST["chkid"])) {
	$chkid = $_POST["chkid"];

	$query_topics = $db->prepare("SELECT DISTINCT topic FROM tbl_inspection_checklist_questions where checklistname= :chkid");
	$query_topics->execute(array(":chkid" => $chkid));
	$count_topics = $query_topics->rowCount();

	echo '<div class="card" style="margin-bottom:-20px">
		<div class="header">
			<i class="fa fa-ckecklist"></i>Choose the questions applicable for this task!!
		</div>
		<div class="body">
			<fieldset class="scheduler-border">
				<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Checklist </legend>
				<div class="table-responsive"> 
					<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
						<thead>
							<tr class="bg-blue-grey">
								<th style="width:5%">#</th>
								<th style="width:85%">Checklist Topic(s)</th>
								<th style="width:10%">Action</th>
							</tr>
						</thead>
						<tbody>';
						if ($count_topics == 0) {
							echo '<tr>
								<td  colspan="3"><div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div></>
							</tr>';
						} else {
							$nm = 0;
							while ($rows_topics = $query_topics->fetch()) {
								$nm = $nm + 1;
								if ($nm == 1) {
									$nmb = "A";
								} elseif ($nm == 2) {
									$nmb = "B";
								} elseif ($nm == 3) {
									$nmb = "C";
								} elseif ($nm == 4) {
									$nmb = "D";
								} elseif ($nm == 5) {
									$nmb = "E";
								} elseif ($nm == 6) {
									$nmb = "F";
								} elseif ($nm == 7) {
									$nmb = "G";
								} elseif ($nm == 8) {
									$nmb = "H";
								} elseif ($nm == 9) {
									$nmb = "I";
								} elseif ($nm == 10) {
									$nmb = "J";
								} elseif ($nm == 11) {
									$nmb = "K";
								} elseif ($nm == 12) {
									$nmb = "L";
								} elseif ($nm == 13) {
									$nmb = "M";
								} elseif ($nm == 14) {
									$nmb = "N";
								} elseif ($nm == 15) {
									$nmb = "O";
								} elseif ($nm == 16) {
									$nmb = "P";
								} elseif ($nm == 17) {
									$nmb = "Q";
								} elseif ($nm == 18) {
									$nmb = "R";
								} elseif ($nm == 19) {
									$nmb = "S";
								} elseif ($nm == 20) {
									$nmb = "T";
								} elseif ($nm == 21) {
									$nmb = "U";
								} elseif ($nm == 22) {
									$nmb = "V";
								} elseif ($nm == 23) {
									$nmb = "W";
								} elseif ($nm == 24) {
									$nmb = "X";
								} elseif ($nm == 25) {
									$nmb = "Y";
								} elseif ($nm == 26) {
									$nmb = "Z";
								}
								$topicid = $rows_topics['topic'];

								$query_topicname = $db->prepare("SELECT topic FROM tbl_inspection_checklist_topics WHERE id = :topicid");
								$query_topicname->execute(array(":topicid" => $topicid));
								$row_topicname = $query_topicname->fetch();
								$topicname = $row_topicname["topic"];



								$query_questions = $db->prepare("SELECT id, question FROM tbl_inspection_checklist_questions WHERE topic = :topicid AND checklistname = :chkid");
								$query_questions->execute(array(":topicid" => $topicid, ":chkid" => $chkid));

								echo '<tr style="background-color:#CDDC39; color:#2196F3">
									<td align="center">' . $nmb . '</td>
									<td><strong>' . $topicname . '</strong></td>
									<td></td>
								</tr>
									<tr style="background-color:#9E9E9E; color:#FFF">
										<th>#</th>
										<th>Checklist Question</th>
										<th>Select</th>
									</tr>';
									$num = 0;
									while ($row_questions = $query_questions->fetch()) {
										$num = $num + 1;
										$question = $row_questions["question"];
										$questionid = $row_questions["id"];
										echo '<tr style="background-color:#eff9ca">
											<td align="center">' . $nmb . '.' . $num . '</td>
											<td>' . $question . '</td>
											<td>
												<input type="checkbox" name="question[]" id="qsn' . $nmb . $num . '" value="' . $questionid . '" class="filled-in chk-col-blue"/>
												<label for="qsn' . $nmb . $num . '"></label>
											</td>
										</tr>';
									}
								}
							}

								echo '</tbody>
											</table>
													</div>
													</fieldset>';

							$query_topic = $db->prepare("SELECT topic, id FROM tbl_inspection_checklist_topics");
							$query_topic->execute();
							$row_topic = $query_topic->fetch();
							$data = '';
							do {
								$topic = $row_topic["topic"];
								$data .= '<option value="' . $row_topic["id"] . '">' . $row_topic["topic"] . '</option>';
							} while ($row_topic = $query_topic->fetch());

							echo '<fieldset class="scheduler-border">
							<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Add New Questions</legend>
							<div class="table-responsive">
					<table class="table table-bordered" id="checklist_table"> 
						<thead>
						<tr>
							<th style="width:2%">#</th>
							<th style="width:40%">Topic </th>
							<th style="width:50%">Add Checklist Question</th>
							<th style="width:2%"><button type="button" name="addplus" onclick="add_list();" title="Add another question" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
						</tr>
						</thead>
						
						<tbody id="checklist_table_body">
						<tr>
							<td>1</td>
							
							<td>
							<select name="topic[]" id="topic" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px"  required>
							<option value="">... Select ...</option>
							' .
		$data
		. '</select>
							</td>
							<td>
								<input type="text" name="checklist[]" id="checklist[]" class="form-control" placeholder="Define your question here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" >
							</td>
							<td></td>
						</tr>
						</tbody>
					</table> 
				</div>
	</fieldset> 
						</div>
					</div>';
}

// ading checklist to the db 
if (isset($_POST['addInsChecklist'])) {
	$current_date = date("Y-m-d H:i:s");
	$msid = $_POST['msid'];
	$projid = $_POST['projid'];
	$taskid =  $_POST['taskid'];
	$user_name = $_POST['user_name'];
	for ($i = 0; $i < count($_POST["question"]); $i++) {
		$insertSQL = $db->prepare("INSERT INTO tbl_project_inspection_checklist (projid, msid, taskid, questionid, created_by, date_created) VALUES (:projid, :msid, :taskid, :questionid, :createdby, :datecreated)");
		$results = $insertSQL->execute(array(':projid' => $projid, ':msid' => $msid, ':taskid' => $taskid, ':questionid' => $_POST["question"][$i], ':createdby' => $user_name, ':datecreated' => $current_date));
	}

	if (isset($_POST['topic'])  && !empty($_POST['topic']) && isset($_POST['checklist'])  && !empty($_POST['checklist'])) {
		for ($i = 0; $i < count($_POST["checklist"]); $i++) {
			$question = $_POST["checklist"][$i];
			$topics = $_POST["topic"][$i];
			$checklistname = $_POST["opchecklist"];

			if ($topics != "" && $question != "") {
				$insertSQL = $db->prepare("INSERT INTO tbl_inspection_checklist_questions (checklistname, topic, question, created_by, date_created) VALUES (:checklistname, :topic, :question, :createdby, :datecreated)");
				$insertSQL->execute(array(':checklistname' => $checklistname, ':topic' => $topics, ':question' => $question, ':createdby' => $user_name, ':datecreated' => $current_date));

				$last_id = $db->lastInsertId();
				$insertSQL = $db->prepare("INSERT INTO tbl_project_inspection_checklist (projid, msid, taskid, questionid, created_by, date_created) VALUES (:projid, :msid, :taskid, :questionid, :createdby, :datecreated)");
				$insertSQL->execute(array(':projid' => $projid, ':msid' => $msid, ':taskid' => $taskid, ':questionid' => $last_id, ':createdby' => $user_name, ':datecreated' => $current_date));
			}
		}
	}

	if ($results === TRUE) {
		$valid['success'] = true;
		$valid['messages'] = "Checklist Successfully Added";
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while adding the Checklist!!";
	}
	echo json_encode($valid);
}

// updating  checklist 
if (isset($_POST['editInsChecklist'])) {
	$current_date = date("Y-m-d H:i:s");
	$msid = $_POST['msid'];
	$projid = $_POST['projid'];
	$taskid =  $_POST['taskid'];
	$user_name = $_POST['user_name'];

	$deleteQueryO = $db->prepare("DELETE FROM `tbl_project_inspection_checklist` WHERE taskid=:taskid");
	$resultsO = $deleteQueryO->execute(array(':taskid' => $taskid));

	for ($i = 0; $i < count($_POST["question"]); $i++) {
		$insertSQL = $db->prepare("INSERT INTO tbl_project_inspection_checklist (projid, msid, taskid, questionid, created_by, date_created) VALUES (:projid, :msid, :taskid, :questionid, :createdby, :datecreated)");
		$results = $insertSQL->execute(array(':projid' => $projid, ':msid' => $msid, ':taskid' => $taskid, ':questionid' => $_POST["question"][$i], ':createdby' => $user_name, ':datecreated' => $current_date));
	}

	if (isset($_POST['topic'])  && !empty($_POST['topic']) && isset($_POST['checklist'])  && !empty($_POST['checklist'])) {
		for ($i = 0; $i < count($_POST["checklist"]); $i++) {
			$question = $_POST["checklist"][$i];
			$topics = $_POST["topic"][$i];
			$checklistname = $_POST["opchecklist"];
			if ($topics != "" && $question != "") {
				$insertSQL = $db->prepare("INSERT INTO tbl_inspection_checklist_questions (checklistname, topic, question, created_by, date_created) VALUES (:checklistname, :topic, :question, :createdby, :datecreated)");
				$insertSQL->execute(array(':checklistname' => $checklistname, ':topic' => $topics, ':question' => $question, ':createdby' => $user_name, ':datecreated' => $current_date));

				$last_id = $db->lastInsertId();
				$insertSQL = $db->prepare("INSERT INTO tbl_project_inspection_checklist (projid, msid, taskid, questionid, created_by, date_created) VALUES (:projid, :msid, :taskid, :questionid, :createdby, :datecreated)");
				$insertSQL->execute(array(':projid' => $projid, ':msid' => $msid, ':taskid' => $taskid, ':questionid' => $last_id, ':createdby' => $user_name, ':datecreated' => $current_date));
			}
		}
	}

	if ($results === TRUE) {
		$valid['success'] = true;
		$valid['messages'] = "Checklist Successfully Updated";
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while Updating the Checklist!!";
	}
	echo json_encode($valid);
}

// deleting checklist
if (isset($_POST['deleteInspection'])) {
	$itemid = $_POST['itemId'];
	$deleteQuery = $db->prepare("DELETE FROM `tbl_project_inspection_checklist` WHERE taskid=:itemid");
	$results = $deleteQuery->execute(array(':itemid' => $itemid));

	if ($results === TRUE) {
		$valid['success'] = true;
		$valid['messages'] = "Successfully Deleted";
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while deletng the record!!";
	}
	echo json_encode($valid);
}
