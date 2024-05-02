<?php
try {
	$pageName = "Project Escalated Issue Assessment";
	$replacement_array = array(
		'planlabel' => "Escalated Issue Action",
		'plan_id' => base64_encode(6),
	);

	$page = "view";
	require('includes/head.php');
	$pageTitle = $planlabelplural;

	if ($permission) {
		$projstage = 10;

		if (isset($_GET['issueid'])) {
			$issueid = $_GET['issueid'];
		}

		$action = "Add";
		$submitAction = "MM_insert";
		$formName = "addterminology";
		$submitValue = "Submit";

		if ((isset($_POST["issueid"])) && !empty($_POST["issueid"])) {
			if (isset($_POST["submit"]) && $_POST["submit"] == "Save") {
				$projissue = $_POST["issueid"];
				$project = $_POST["projid"];
				$comments = $_POST["comments"];
				$projissuename = $_POST["issuename"];
				$subject = "Issue assessment report";
				$stage = 5;
				$user = $_POST["user_name"];
				$origstatus = $_POST["projstatus"];
				$evaluation = $_POST["evaluation"];
				//$escalator = 45;
				$escalator = $_POST["escalator"];
				$actiondate = date("Y-m-d");
				$changedon = date("Y-m-d H:i:s");
				$issuemessage = "The issue assessment report is ready:";
				//$catid =$last_id;

				$insertSQL = $db->prepare("INSERT INTO tbl_projissue_comments (projid, rskid, stage, comments, created_by, date_created) VALUES (:projid, :rskid, :stage, :comments, :user, :date)");
				$insertSQL->execute(array(':projid' => $project, ':rskid' => $projissue, ':stage' => $stage, ':comments' => $comments, ':user' => $user_name, ':date' => $actiondate));
				$formid = $db->lastInsertId();

				if (!empty($_FILES['attachment']['name'])) {
					$filecategory = "Issue";
					$reason = "Project Committee Action: " . $subject;
					return;
					//Check if the file is JPEG image and it's size is less than 350Kb
					$filename = basename($_FILES['attachment']['name']);
					$ext = substr($filename, strrpos($filename, '.') + 1);
					if (($ext != "exe") && ($_FILES["attachment"]["type"] != "application/x-msdownload")) {
						$newname = $project . "-" . $projissue . "-" . $filename;
						$filepath = "uploads/projissue/" . $newname;
						//Check if the file with the same name already exists in the server
						if (!file_exists($filepath)) {
							//Attempt to move the uploaded file to it's new place
							if (move_uploaded_file($_FILES['attachment']['tmp_name'], $filepath)) {
								//successful upload
								$fname = $newname;

								$queryinsert = $db->prepare("INSERT INTO tbl_files (`projid`, `projstage`, `form_id`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, `date_uploaded`) VALUES (:projid, :stage, :formid, :fname, :ext, :filepath, :filecat, :reason, :user, :date)");
								$queryinsert->execute(array(':projid' => $project, ':stage' => $projissue, ':formid' => $formid, ':fname' => $fname, ':ext' => $ext, ':filepath' => $filepath, ':filecat' => $filecategory, ':reason' => $reason, ':user' => $user, ':date' => $actiondate));
							}
						} else {
							$msg = 'File you are uploading already exists, try another file!!';
							$results = "<script type=\"text/javascript\">
							swal({
							title: \"Error!\",
							text: \" $msg \",
							icon: 'warning',
							dangerMode: true,
							timer: 5000,
							showConfirmButton: false });
						</script>";
						}
					} else {
						$msg = 'This file type is not allowed, try another file!!';
						$results = "<script type=\"text/javascript\">
						swal({
						title: \"Error!\",
						text: \" $msg \",
						icon: 'warning',
						dangerMode: true,
						timer: 5000,
						showConfirmButton: false });
					</script>";
					}
				} else {
					$msg = 'Please attach a file and try again!!';
					$results = "<script type=\"text/javascript\">
					swal({
					title: \"Error!\",
					text: \" $msg \",
					icon: 'warning',
					dangerMode: true,
					timer: 5000,
					showConfirmButton: false });
				</script>";
				}

				$query_manager = $db->prepare("SELECT owner FROM tbl_escalations where category='issue' and projid='$project' and itemid='$issueid' and status=1");
				$query_manager->execute();
				$rows = $query_manager->fetch();
				$managerid = $rows["owner"];

				$query_userowner =  $db->prepare("SELECT userid, ptid, fullname, title, t.email AS email, designation FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid WHERE userid = '$managerid'");
				$query_userowner->execute();
				$row = $query_userowner->fetch();

				$query_url =  $db->prepare("SELECT * FROM tbl_company_settings");
				$query_url->execute();
				$row_url = $query_url->fetch();
				$url = $row_url["main_url"];
				$org = $row_url["company_name"];
				$org_email = $row_url["email_address"];

				$issuestatus = 5;
				$updateQuery = $db->prepare("UPDATE tbl_projissues SET status=:status WHERE projid=:projid and id=:issueid");
				$updatest = $updateQuery->execute(array(':status' => $issuestatus, ':projid' => $project, ':issueid' => $projissue));

				// Comments link back to the system
				$issuelink = '<a href="' . $url . 'projectissuescomments?issueid=' . $projissue . '&stage=5&owner=' . $managerid . '" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">More Details</a>';

				$receipientName = $row["title"] . '. ' . $row["fullname"]; // The receipients names
				$receipient = $row["email"];

				require_once("project-committee-issue-action-email.php");
				require 'PHPMailer/PHPMailerAutoload.php';
				require("email-conf-settings.php");

				$msg = 'Issue assessment comments successfully saved!!';
				$results = "<script type=\"text/javascript\">
				swal({
				title: \"Success!\",
				text: \" $msg \",
				icon: 'success',
				dangerMode: false,
				timer: 3000,
				showConfirmButton: false });
			setTimeout(function(){
				window.location.href = 'projects-escalated-issues';
				}, 3000);
			</script>";
			}
		}

		$query_issuedetails =  $db->prepare("SELECT p.projid, projname, projstatus, c.category as issue, v.name as severity, r.response as mitigation, observation as description, i.created_by as recordedby, i.date_created as daterecorded, fullname as owner, title, s.notes as recommendation, s.date_analysed as dateanalysed, e.comments as leadercomments, e.date_escalated as dateescalated, e.escalated_by FROM tbl_escalations e inner join tbl_projissues i on i.id=e.itemid inner join tbl_projrisk_categories c on c.rskid=i.risk_category inner join tbl_projects p on p.projid=i.projid inner join tbl_project_riskscore s on s.issueid=i.id inner join tbl_projissue_severity v on v.id=s.score inner join tbl_projrisk_response r on r.id=s.mitigation inner join users u on u.userid=i.owner inner join tbl_projteam2 t on t.ptid=u.pt_id where itemid='$issueid' and e.category='issue' and e.owner='$user_name'");
		$query_issuedetails->execute();
		$rows = $query_issuedetails->fetch();
		$rows_issuedetails = $query_issuedetails->rowCount();

		$projid = $rows["projid"];
		$projname = $rows["projname"];
		$projstatus = $rows["projstatus"];
		$issuename = $rows["issue"];
		$severity = $rows["severity"];
		$mitigation = $rows["mitigation"];
		$issuedescription = $rows["description"];
		$recordedby = $rows["recordedby"];
		$escdby = $rows["escalated_by"];
		$daterecorded = date("d M Y", strtotime($rows["daterecorded"]));
		$issueowner = $rows["title"] . "." . $rows["owner"];
		$analysisrecm = $rows["recommendation"];
		$dateanalysed = date("d M Y", strtotime($rows["dateanalysed"]));
		$tmleadercomments = $rows["leadercomments"];
		$dateescalated = date("d M Y", strtotime($rows["dateescalated"]));

		$query_tmembers =  $db->prepare("SELECT * FROM tbl_projmembers where projid='$projid' GROUP BY pmid ORDER BY role ASC");
		$query_tmembers->execute();

		$query_teamleader = $db->prepare("SELECT pt_id, fullname, title FROM users u inner join tbl_projteam2 t on t.ptid=u.pt_id where userid='$escdby'");
		$query_teamleader->execute();
		$teamleader = $query_teamleader->fetch();
		$escdbyid = $teamleader["pt_id"];
		$escalatedby = $teamleader["title"] . "." . $teamleader["fullname"];
		$style = 'style="padding:8px; border:#CCC thin solid; border-radius:5px"';

		$query_recordedby = $db->prepare("SELECT fullname, title FROM users u inner join tbl_projteam2 t on t.ptid=u.pt_id where userid='$recordedby'");
		$query_recordedby->execute();
		$row_recordedby = $query_recordedby->fetch();
		$recordedby = $row_recordedby["title"] . "." . $row_recordedby["fullname"];
?>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#issueaction').on('change', function() {
					var statusID = $(this).val();
					var projID = $("#projid").val();
					var issueid = $("#issueid").val();
					$.ajax({
						type: 'POST',
						url: 'callcommitteeaction',
						//data: {'members_id': memberID},
						data: "statusid=" + statusID + "&projid=" + projID + "&issueid=" + issueid,
						success: function(data) {
							$('#content').html(data);
						}
					});
				});


				$('#impact').on('change', function() {
					var statusID = $(this).val();
					var projID = $("#projid").val();
					var projOrigID = $("#projorigstatus").val();
					$.ajax({
						type: 'POST',
						url: 'callchangeimpact',
						//data: {'members_id': memberID},
						data: "status_id=" + statusID + "&proj_id=" + projID + "&projOrig_id=" + projOrigID,
						success: function(data) {
							$('#formcontent').html(data);
							$("#myModal").modal({
								backdrop: "static"
							});
						}
					});
				});
			});

			function CallRiskResponse(projid) {
				$.ajax({
					type: 'post',
					url: 'callriskresponse',
					data: {
						projid: projid
					},
					success: function(data) {
						$('#riskresponse').html(data);
						$("#riskModal").modal({
							backdrop: "static"
						});
					}
				});
			}
		</script>
		<!-- Modal -->
		<div class="modal fade" id="myModal" role="dialog">
			<div class="modal-dialog modal-lg span5">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">
							<font color="#000000">PROJECT STATUS CHANGE REASON(S)</font>
						</h4>
					</div>
					<div class="modal-body" id="formcontent">

					</div>
				</div>
			</div>
		</div>
		<!-- start body  -->
		<section class="content">
			<div class="container-fluid">
				<div class="block-header">
					<div>
						<?php echo $results; ?>
					</div>
				</div>
				<div class="block-header bg-brown" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
					<h4 class="contentheader"><i class="fa fa-columns" aria-hidden="true"></i> Issue Assessment Report</h4>
				</div>
				<div class="row clearfix" style="margin-top:10px">
					<!-- Advanced Form Example With Validation -->
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="card">
							<div class="body">
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-alert" aria-hidden="true"></i> Issue History</legend>
									<div style="padding-top: 20px; padding-bottom:-50px">
										<div class="col-md-12">
											<label>Project:</label>
											<div <?= $style ?>><?= $projname ?></div>
										</div>
										<div class="col-md-12">
											<label>Issue:</label>
											<div <?= $style ?>><?= $issuename ?></div>
										</div>
										<div class="col-md-12">
											<label>Issue Description:</label>
											<div <?= $style ?>><?= $issuedescription ?></div>
										</div>
										<div class="col-md-3">
											<label>Severity Level:</label>
											<div <?= $style ?>><?= $severity ?></div>
										</div>
										<div class="col-md-9">
											<label>Mitigation:</label>
											<div <?= $style ?>><?= $mitigation ?></div>
										</div>
										<div class="col-md-3">
											<label>Recorded By:</label>
											<div <?= $style ?>><?= $recordedby ?></div>
										</div>
										<div class="col-md-3">
											<label>Date Recorded:</label>
											<div <?= $style ?>><?= $daterecorded ?></div>
										</div>
										<div class="col-md-3">
											<label>Issue Owner:</label>
											<div <?= $style ?>><?= $issueowner ?></div>
										</div>
										<div class="col-md-3">
											<label>Date Analysed:</label>
											<div <?= $style ?>><?= $dateanalysed ?></div>
										</div>
										<div class="col-md-12">
											<label>Analysis Recommendation:</label>
											<div <?= $style ?>><?= $analysisrecm ?></div>
										</div>
										<div class="col-md-4">
											<label>Escalated By:</label>
											<div <?= $style ?>><?= $escalatedby ?></div>
										</div>
										<div class="col-md-4">
											<label>Date Escalated:</label>
											<div <?= $style ?>><?= $dateescalated ?></div>
										</div>
										<div class="col-md-12">
											<label>Team Leader Comments:</label>
											<div <?= $style ?>><?= $tmleadercomments ?></div>
										</div>
										<fieldset class="scheduler-border">
											<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-users" aria-hidden="true"></i> Project Team Members</legend>
											<div class="table-responsive">
												<table class="table table-bordered table-striped table-hover">
													<thead>
														<tr id="colrow">
															<th width="2%"><strong id="colhead">SN</strong></th>
															<th width="23%">Name</strong></td>
															<th width="15%">Role</th>
															<th width="15%">Designation</th>
															<th width="10%">Availability</th>
															<th width="15%">Phone</th>
															<th width="20%">Email</th>
														</tr>
													</thead>
													<tbody>
														<?php
														//return;
														$sn = 0;
														while ($rows = $query_tmembers->fetch()) {
															$mbrid = $rows['ptid'];

															$query_tmember =  $db->prepare("SELECT t.designation as role, d.designation, t.ptid, t.fullname, t.title, t.email, t.phone FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_pmdesignation d on d.moid=t.designation where u.userid='$mbrid' ORDER BY t.designation ASC");
															$query_tmember->execute();
															$row = $query_tmember->fetch();

															$sn = $sn + 1;
															$name = $row['title'] . "." . $row['fullname'];
															$designation = $row['designation'];
															$role = $rows['role'];
															if ($role == 1) {
																$role = "Team Leader";
															} elseif ($role == 2) {
																$role = "Deputy Team Leader";
															} elseif ($role == 3) {
																$role = "Officer";
															} else {
																$role = "monitoring Officer";
															}

															$email = $row['email'];
															$phone = $row['phone'];
															$avail = $rows['ptleave'];

															if ($avail == 1) {
																$availability = "Unavailable";
																$reassignee = $rows['reassignee'];
																$datereassigned = date("d M Y", strtotime($rows['datereassigned']));

																$query_reassignee =  $db->prepare("SELECT fullname, title, phone, email FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid where userid='$reassignee'");
																$query_reassignee->execute();
																$row_reassignee = $query_reassignee->fetch();
																$reassigneedto = $row_reassignee["title"] . "." . $row_reassignee["fullname"];
																$reassigneedphone = $row_reassignee["phone"];
																$reassigneedemail = $row_reassignee["email"];
																$availclass = 'class="text-warning"';
															} else {
																$availability = "Available";
																$availclass = 'class="text-success"';
															}
														?>
															<tr id="rowlines">
																<td><?php echo $sn; ?></td>
																<td><?php echo $name; ?></td>
																<td><?php echo $role; ?></td>
																<td><?php echo $designation; ?></td>
																<?php if ($avail == 1) { ?>
																	<td <?= $availclass ?>><span class="mytooltip tooltip-effect-1"><span class="tooltip-item2"><?php echo $availability; ?></span><span class="tooltip-content4 clearfix" style="background-color:#CDDC39; color:#000"><span class="tooltip-text2">
																					<h4 align="center"><u>Details</u></h4><strong>Unavailable From Date:</strong> <?php echo $datereassigned; ?><br> <strong>In-Place:</strong> <?php echo $reassigneedto; ?><br> <strong>In-Place Phone:</strong> <?php echo $reassigneedphone; ?><br> <strong>In-Place Email:</strong> <?php echo $reassigneedemail; ?>
																				</span></span></span></td>
																<?php } else { ?>
																	<td><?php echo $availability; ?></td>
																<?php } ?>
																<td><?php echo $phone; ?></td>
																<td><?php echo $email; ?></td>
															</tr>
														<?php
														}
														?>
													</tbody>
												</table>
											</div>
										</fieldset>
									</div>
								</fieldset>

								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-comments" aria-hidden="true"></i> Issue Assessment Comments</legend>
									<div class="col-lg-12 col-md-12 col-sm-12" style="padding-top: 20px">
										<form id="assessmentcomments" method="POST" name="assessmentcomments" action="" autocomplete="off">
											<?= csrf_token_html(); ?>
											<div class="row clearfix" style="padding-left:10px; padding-right:10px">
												<?php
												$query_projstatuschange =  $db->prepare("SELECT projcode, projstatus, projchangedstatus, projevaluate FROM tbl_projects WHERE projid = '$projid'");
												$query_projstatuschange->execute();
												$row_projstatuschange = $query_projstatuschange->fetch();
												$projstatus = $row_projstatuschange["projstatus"];
												$projcode = $row_projstatuschange["projcode"];
												$projevaluate = $row_projstatuschange["projevaluate"];

												?>
												<div class="col-md-12">
													<label>
														<font color="#174082">Comments:</font>
													</label>
													<div class="form-line">
														<textarea name="comments" cols="45" rows="5" class="txtboxes" id="comments" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required></textarea>
														<script src="js/issueescalationtextareajs.js"></script>
													</div>
												</div>
												<div class="col-md-12">
													<label>
														<font color="#174082">Select and attach a file:</font>
													</label>
													<div class="form-line">
														<input type="file" class="form-control" name="attachment" id="attachment">
													</div>
												</div>
												<div class="row clearfix">
													<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
														&nbsp;
													</div>
													<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
														<div class="btn-group">
															<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="Save" />
														</div>
														<div class="btn-group">
															<a type="button" class="btn btn-warning" href="project-escalated-issue">Cancel</a>
														</div>
													</div>
													<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
														<input name="projid" type="hidden" id="projid" value="<?php echo $projid; ?>" />
														<input name="user_name" type="hidden" id="user_name" value="<?php echo $username; ?>" />
														<input name="issueid" type="hidden" value="<?php echo $issueid; ?>" />
													</div>
												</div>
											</div>
										</form>
									</div>
								</fieldset>
							</div>
						</div>
					</div>
				</div>
				<!-- #END# Advanced Form Example With Validation -->
			</div>
		</section>
		<!-- end body  -->

		<script language="javascript" type="text/javascript">
			$(document).ready(function() {
				$('#escalation-response-form').on('submit', function(event) {
					event.preventDefault();
					var form_data = $(this).serialize();
					$.ajax({
						type: "POST",
						url: "escalationresponse",
						data: form_data,
						dataType: "json",
						success: function(response) {
							if (response) {
								alert('Record Successfully Saved');
								window.location.reload();
							}
						},
						error: function() {
							alert('Error');
						}
					});
					return false;
				});
			});
		</script>
		<!-- Modal Issue Response -->
		<div class="modal fade" id="escalationActionModal" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header" style="background-color:#795548">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h3 class="modal-title" align="center">
							<font color="#FFF">PROJECT RISK RESPONSE</font>
						</h3>
					</div>
					<form class="tagForm" action="escalationresponse" method="post" id="escalation-response-form" enctype="multipart/form-data" autocomplete="off">
						<?= csrf_token_html(); ?>
						<div class="modal-body">
							<div class="row clearfix">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="card">
										<div class="body">
											<div class="table-responsive" style="background:#eaf0f9">
												<div id="riskresponse">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>
						<div class="modal-footer">
							<div class="col-md-4">
							</div>
							<div class="col-md-4" align="center">
								<input name="save" type="submit" class="btn btn-success waves-effect waves-light" id="tag-form-submit" value="Save" />
								<button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
								<input type="hidden" name="username" id="username" value="<?php echo $username; ?>" />
								<input type="hidden" name="stchange" value="1" />
							</div>
							<div class="col-md-4">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- #END# Modal Issue Response -->

		<script language="javascript" type="text/javascript">
			$(document).ready(function() {
				$('#par-change-form').on('submit', function(event) {
					event.preventDefault();
					var form_data = $(this).serialize();
					$.ajax({
						type: "POST",
						url: "parameterschange",
						data: form_data,
						dataType: "json",
						success: function(response) {
							if (response) {
								alert('Record Successfully Saved');
								$('.modal').each(function() {
									$(this).modal('hide');
								});
							}
						},
						error: function() {
							alert('Error');
						}
					});
					return false;
				});
			});
		</script>
		<!-- Modal Project Constrain Parameters -->
		<div class="modal fade" id="parChangeModal" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header" style="background-color:#795548">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h3 class="modal-title" align="center">
							<font color="#FFF">PARAMETERS CHANGES</font>
						</h3>
					</div>
					<form class="tagForm" action="parameterschange" method="post" id="par-change-form" enctype="multipart/form-data" autocomplete="off">
						<?= csrf_token_html(); ?>
						<div class="modal-body">
							<div class="row clearfix">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="card">
										<div class="body">
											<div class="table-responsive" style="background:#eaf0f9">
												<div id="parameterschange">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>
						<div class="modal-footer">
							<div class="col-md-3">
							</div>
							<div class="col-md-6" align="center">
								<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
								<button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
								<input type="hidden" name="username" id="username" value="<?php echo $username; ?>" />
								<input type="hidden" name="stchange" value="1" />
							</div>
							<div class="col-md-3">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- #END# Modal Project Constrain Parameters -->
<?php
	} else {
		$results =  restriction();
		echo $results;
	}

	require('includes/footer.php');
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>

</body>

</html>