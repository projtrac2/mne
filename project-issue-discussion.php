<?php
try {
	require('includes/head.php');
	if ($permission && isset($_GET['issueid']) && !empty($_GET['issueid'])) {
		$projisd = $_GET['issueid'];

		if (isset($_GET['ds'])) {
			$discstatus = $_GET['ds'];
			$updateQuery = $db->prepare("UPDATE tbl_projissues_discussions SET status=:status WHERE issueid=:issueid and parent=0");
			$updatest = $updateQuery->execute(array(':status' => $discstatus, ':issueid' => $projisd));
		}
		if (isset($_POST['send'])) {
			$parent = $_POST['parent'];
			$projid = $_POST['projid'];
			$comments = $_POST['comments'];
			$issueid = $_POST['issueid'];
			$owner = $user_name;
			$date_created = date('Y-m-d H:i:s');
			$catid = $issueid;
			$totals = count($_FILES['files']['name']);

			$sql = $db->prepare("INSERT INTO tbl_projissues_discussions (parent, projid, issueid, owner, comment, date_created) VALUES(:parent, :projid, :issueid, :owner, :comment, :date_created)");
			$sql->execute(array(":parent" => $parent, ":projid" => $projid, ":issueid" => $issueid, ":owner" => $owner, ":comment" => $comments, ":date_created" => $date_created));

			for ($cnt = 0; $cnt < $totals; $cnt++) {
				if (!empty($_FILES['files']['name'][$cnt])) {
					$filename = basename($_FILES['file']['name'][$cnt]);
					$catid = $catid + 1;
					$ext = substr($filename, strrpos($filename, '.') + 1);
					if (($ext != "exe") && ($_FILES["file"]["type"][$cnt] != "application/x-msdownload")) {
						$newname = $catid . "_" . $filename;
						$filepath = "uploads/discussions/" . $newname;
						//Check if the file with the same name already exists in the server
						if (!file_exists($filepath)) {
							//Attempt to move the uploaded file to it's new place
							if (move_uploaded_file($_FILES['file']['tmp_name'][$cnt], $filepath)) {
								//successful upload
								$fname = $newname;
								$sql = $db->prepare("INSERT INTO tbl_projissues_discussions (parent, projid, issueid, owner, comment, floc, date_created)  VALUES(:parent, :projid, :issueid, :owner, :comment, :floc, :date_created)");
								$sql->execute(array(":parent" => $parent, ":projid" => $projid, ":issueid" => $issueid, ":owner" => $user_name, ":comment" => $comments, ":floc" => $filepath, ":date_created" => $date_created));
							}
						} else {
							$msg = 'File you are uploading already exists, try another file!!';
							$results = "<script type=\"text/javascript\">
									swal({
									title: \"Error!\",
									text: \" $msg \",
									type: 'Danger',
									timer: 10000,
									showConfirmButton: false });
								</script>";
						}
					} else {
						$msg = 'This file type is not allowed, try another file!!';
						$results = "<script type=\"text/javascript\">
								swal({
								title: \"Error!\",
								text: \" $msg \",
								type: 'Danger',
								timer: 10000,
								showConfirmButton: false });
							</script>";
					}

					$msg = 'File you are uploading already exists, try another file!!';
					$results = "<script type=\"text/javascript\">
							swal({
							title: \"Error!\",
							text: \" $msg \",
							type: 'Danger',
							timer: 10000,
							showConfirmButton: false });
						</script>";
				}
			}
		}
		//query table issues
		$query_projissue = $db->prepare("SELECT i.id, i.origin, p.projid, p.projname AS projname,p.projcategory, category, observation, recommendation, status, i.created_by AS monitor, i.date_created AS issuedate FROM `tbl_projissues` i INNER JOIN tbl_projrisk_categories c on c.rskid=i.risk_category INNER JOIN tbl_projects p on p.projid =i.projid WHERE i.id ='$projisd'");
		$query_projissue->execute();
		$row_projissue = $query_projissue->fetch();
		$rows_account = $query_projissue->rowCount();


		$query_issue_output = $db->prepare("SELECT output FROM tbl_projissues i INNER JOIN tbl_projects p ON p.projid=i.projid INNER JOIN tbl_projrisk_categories c ON c.rskid=i.risk_category inner join tbl_project_details d on d.id=i.output_id inner join tbl_progdetails o on o.id=d.outputid WHERE i.id='$projisd'");
		$query_issue_output->execute();
		$row_issue_output = $query_issue_output->fetch();

		$output = "No defined";
		if ($row_issue_output) {
			$output = $row_issue_output['output'];
		}

		$projid = $row_projissue['projid'];
		$issue = $row_projissue['category'];
		$issuedesc = $row_projissue['observation'];
		$issuestatus = $row_projissue['status'];
		$projname = $row_projissue["projname"];
		$created_by  = $row_projissue["created_by"]; //owner
		$owner  = $user_name;
		$formid = $row_projissue["formid"];

		//get active module
		$query_rsRelated = $db->prepare("SELECT * FROM `tbl_projissues_discussions` WHERE issueid ='$projisd' and parent = 0 and (status = 1 or status = 2)");
		$query_rsRelated->execute();
		$row_rsRelated = $query_rsRelated->fetch();
		$totalRows_rsRelated = $query_rsRelated->rowCount();

		//if there is no active discussion create one
		if ($totalRows_rsRelated == 0) {
			$parent = 0;
			$comments = "";
			$date_created = date('Y-m-d H:i:s');
			if ($issuestatus == 2) {
				$comments = $issue . ": " . $issuedesc;
			} else {
				$comments = "On Hold";
			}

			$create_discussion = $db->prepare("INSERT INTO tbl_projissues_discussions (parent, projid, issueid, owner, comment, date_created) VALUES(:parent, :projid, :issueid, :owner, :comment, :date_created)");
			$result = $create_discussion->execute(array(":parent" => $parent, ":projid" => $projid, ":issueid" => $projisd, ":owner" => $owner, ":comment" => $comments, ":date_created" => $date_created));
			if ($result) {
				//get the created discussion module
				$query_rsRelated = $db->prepare("SELECT * FROM `tbl_projissues_discussions` WHERE issueid ='$projisd' and status = 1 and parent = 0");
				$query_rsRelated->execute();
				$row_rsRelated = $query_rsRelated->fetch();
				$totalRows_rsRelated = $query_rsRelated->rowCount();
				$pstatus = $row_rsRelated['status'];
				$parentid = $row_rsRelated['id'];
			} else {
				header("location:projectissueslist.php");
			}
		} else {
			$pstatus = $row_rsRelated['status'];
			$parentid = $row_rsRelated['id'];
		}

		// get related discussions
		$query_issueby = $db->prepare("SELECT * FROM tbl_projissues i inner join users u on u.userid=i.created_by inner join tbl_projteam2 t on t.ptid = u.pt_id inner join tbl_pmdesignation r on r.moid = t.designation WHERE i.id ='$projisd'");
		$query_issueby->execute();
		$rows_issueby = $query_issueby->fetch();
		$totalRows_issueby = $query_issueby->rowCount();
		$issueby = $rows_issueby["title"] . "." . $rows_issueby["fullname"];
		$daterecorded = date("d M Y", strtotime($rows_issueby["date_created"]));
		$issueownerdate = date("d M Y", strtotime($rows_issueby["date_assigned"]));

		// issue owner
		$query_issueowner = $db->prepare("SELECT * FROM tbl_projissues_discussions d inner join users u on u.userid=d.owner inner join tbl_projteam2 t on t.ptid = u.pt_id inner join tbl_pmdesignation r on r.moid = t.designation WHERE issueid ='$projisd' and parent = 0");
		$query_issueowner->execute();
		$rows_issueowner = $query_issueowner->fetch();
		$totalRows_issueowner = $query_issueowner->rowCount();
		$issueowner = $rows_issueowner["title"] . "." . $rows_issueowner["fullname"];
		$issueownerusername = $rows_issueowner["username"];
		$issueownerid = $rows_issueowner["owner"];


		//get discussions on the issue details
		$query_issuediscussion = $db->prepare("SELECT d.owner, t.title, t.fullname, t.floc as avatar, r.designation, d.comment, d.date_created FROM tbl_projissues_discussions d inner join users u on u.userid=d.owner inner join tbl_projteam2 t on t.ptid = u.pt_id inner join tbl_pmdesignation r on r.moid = t.designation WHERE issueid = '$projisd' ORDER BY d.`id` ASC");
		$query_issuediscussion->execute();
		$row_issuediscussion = $query_issuediscussion->fetch();
		$totalRows_issuediscussion = $query_issuediscussion->rowCount();

		//get active module
		$query_discussionstatus = $db->prepare("SELECT status FROM `tbl_projissues_discussions` WHERE issueid ='$projisd' and parent = 0");
		$query_discussionstatus->execute();
		$row_discussionstatus = $query_discussionstatus->fetch();
		$pstatus = $row_discussionstatus['status'];
		$percent2 =  0;
?>
		<link rel="stylesheet" href="assets/css/bootstrap.min.css">
		<link rel="stylesheet" href="assets/css/style.css">
		<section class="content">
			<div class="container-fluid">
				<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:-60px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
					<h4 class="contentheader" style="color:#FFF">
						<?= $icon ?>
						<?= $pageTitle ?>
						<div class="btn-group" style="float:right">
						</div>
					</h4>
				</div>
				<div class="row clearfix">
					<div class="block-header">
						<?= $results; ?>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<h4>
								<div class="col-md-8" style="font-size:15px; background-color:#CDDC39; border:#CDDC39 thin solid; border-radius:5px; margin-bottom:2px; height:25px; padding-top:2px; vertical-align:center">
									Project Name: <font color="white"><?php echo $projname; ?></font>
								</div>
								<div class="col-md-4" style="font-size:15px; background-color:#CDDC39; border-radius:5px; height:25px; margin-bottom:2px">
									<div class="progress" style="height:23px; margin-bottom:1px; margin-top:1px; color:black">
										<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="<?= $percent2 ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $percent2 ?>%; margin:auto; padding-left: 10px; padding-top: 3px; text-align:left; color:black">
											<?= $percent2 ?>%
										</div>
									</div>
								</div>
							</h4>
						</div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="card">
							<div class="body">
								<div class="clearfix" style="margin-top:5px">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:15px">
										<h4 class="pull-center">
											Discussions Board
											<?php
											if ($pstatus == 2) {
											?><a href="projectissueslist.php?proj=<?= $projid ?>" type="button" class="btn btn-success" style="float:right">Back to Issues</a>
											<?php
											} elseif ($pstatus == 1 && ($issueownerid == $user_name)  || ($role_group == 4 && $designation == 1)) {
											?>
												<a href="project-issue-discussion?issueid=<?= $projisd ?>&ds=2" type="button" class="btn btn-warning" style="float:right">Close Discussion</a>
											<?php
											}
											?>
										</h4>
									</div>
									<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
										<p> <strong>Issue Category:</strong> <?= $issue ?></p>
										<p> <strong>Issue Description:</strong> <?php echo $issuedesc; ?></p>
										<p> <strong>Project Output: </strong><?php echo $output; ?></p>
										<p> <strong>Issue Status: </strong>
											<?php
											if ($issuestatus == 2) {
												$issuestatus = "Issue Under Analysis";
											} elseif ($issuestatus == 3) {
												$issuestatus = "Issue Analysed";
											} elseif ($issuestatus == 4) {
												$issuestatus = "Issue Escalated";
											} elseif ($issuestatus == 5) {
												$issuestatus = "Issue Closed";
											} elseif ($issuestatus == 4) {
												$issuestatus = "Issue Resolved";
											}
											echo $issuestatus;
											?>
										</p>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<p> <strong>Recorded By:</strong> <?= $issueby ?></p>
										<p> <strong>Date Recorded:</strong> <?= $daterecorded ?></p>
										<p> <strong>Issue Owner: </strong><?= $issueowner ?></p>
										<p> <strong>Date Assigned Owner:</strong> <?= $issueownerdate ?></p>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
										<div class="row clearfix ">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="card">
													<div class="header bg-grey">
														<strong> Issue Files </strong>
													</div>
													<div class="body">
														<div class="body table-responsive">
															<table class="table table-bordered" style="width:100%">
																<thead>
																	<tr>
																		<th style="width:2%">#</th>
																		<th style="width:40%">File Name</th>
																		<th style="width:10%">File Type</th>
																		<th style="width:38%">Purpose</th>
																		<th style="width:10%">Action</th>
																	</tr>
																</thead>
																<tbody>
																	<?php
																	$counter = 0;

																	$query_rsFile = $db->prepare("SELECT * FROM tbl_files WHERE form_id=:formid and projid=:projid");
																	$query_rsFile->execute(array(":formid" => $formid, ":projid" => $projid));
																	$row_rsFile = $query_rsFile->fetch();
																	$totalRows_rsFile = $query_rsFile->rowCount();

																	if ($totalRows_rsFile > 0) {
																		do {
																			$pdfname = $row_rsFile['filename'];
																			$type = $row_rsFile['ftype'];
																			$filepath = $row_rsFile['floc'];
																			$attachmentPurpose = $row_rsFile['reason'];
																			$action =  '<a href="' . $filepath . '" style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" title="Download File" target="new"><i class="fa fa-cloud-download fa-2x" aria-hidden="true"></i></a>';

																			$counter++;
																			echo '<tr>
													<td>
													  ' . $counter . '
													</td>
													<td>
													' . $pdfname . '
													</td>
													<td>
													' . $type . '
													</td>
													<td>
													' . $attachmentPurpose . '
													</td>
													<td align="center">
													' . $action . '
													</td>
												</tr>';
																		} while ($row_rsFile = $query_rsFile->fetch());
																	}

																	$query_photos = $db->prepare("SELECT * FROM tbl_project_photos WHERE fcategory=:cat and projid=:projid");
																	$query_photos->execute(array(":cat" => $formid, ":projid" => $projid));
																	$row_photos = $query_photos->fetch();
																	$totalRows_photos = $query_photos->rowCount();

																	if ($totalRows_photos > 0) {
																		do {
																			$pdfname = $row_photos['filename'];
																			$type = $row_photos['ftype'];
																			$filepath = $row_photos['floc'];
																			$attachmentPurpose = $row_photos['description'];
																			$action =  '<a class="thumbnail fancybox" rel="ligthbox" style="margin-bottom:0px" href="' . $filepath . '">
														<div class="text-center">
															<small class="text-muted">View Photo</small>
														</div>
													</a>';

																			$counter++;
																			echo '<tr>
													<td>
													  ' . $counter . '
													</td>
													<td>
													' . $pdfname . '
													</td>
													<td>
													' . $type . '
													</td>
													<td>
													' . $attachmentPurpose . '
													</td>
													<td>
													' . $action . '
													</td>
												</tr>';
																		} while ($row_photos = $query_photos->fetch());
																	}
																	?><script type="text/javascript">
																		$(document).ready(function() {
																			$(".fancybox").fancybox({
																				openEffect: "none",
																				closeEffect: "none"
																			});
																		});
																	</script>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<p> <strong>Total Comments:</strong> <?php echo $totalRows_issuediscussion - 1; ?></p>
									</div>
								</div>
							</div>
							<div class="body">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="m-b-0">
											<div class="chat-main-box right-panel v-scroll">
												<!-- .chat-right-panel -->
												<div class="chat-right-aside">
													<div class="chat-rbox">
														<ul class="timeline">
															<?php
															$counter = 0;
															do {
																$counter++;
																$postid = $row_issuediscussion['id'];
																$poster = $row_issuediscussion["title"] . "." . $row_issuediscussion["fullname"];
																$issuecomment = $row_issuediscussion['comment'];
																$avatar = $row_issuediscussion['avatar'];
																$date = $row_issuediscussion['date_created'];
																$designation = $row_issuediscussion['designation'];
																if ($counter & 1) {
															?>
																	<li class="timeline-inverted">
																	<?php
																} else {
																	?>
																	<li>
																	<?php
																}
																	?>
																	<div class="timeline-badge success click-to-top">
																		<img class="img-responsive" alt="user" src="<?= $avatar ?>" alt="img" data-toggle="tooltip" title="Posted By: <?= $poster ?>;  Designation: <?= $designation ?>">
																		<span> text</span>
																	</div>
																	<div class="timeline-panel">
																		<div class="timeline-body">
																			<p class="more"><?= $issuecomment; ?></p>
																			<span style="color:red" class="pull-right"><small><?= $date ?></small></span>
																		</div>
																	</div>
																	</li>
																<?php
															} while ($row_issuediscussion = $query_issuediscussion->fetch());
																?>
														</ul>
													</div>
												</div>
												<!-- .chat-right-panel -->
											</div>
											<?php
											if ($pstatus == 2) {
											?>
												<!--<form action="" method="post">
						<div style="margin-top:10px">
							<input type="hidden" name="projid" value="<?php //echo $projid
																		?>">
							<input type="hidden" name="issueid" value="<?php //echo $projisd
																		?>">
							<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
								<textarea placeholder="Type your message here" class="form-control " name="comments" disabled>
									You cannot contribute any longer to this Discussion
								</textarea>
							</div>
							<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 text-right">
								<!--<span id="counter"></span>
								<button disabled class="btn btn-secondary btn-circle btn-sm" data-toggle="tooltip" data-placement="top" title="Attach file">
									<i class="fa fa-paperclip" aria-hidden="true"></i>
									<input id="file" type="file" hidden multiple name="file" disabled>
								</button>
								<button type="submit" class="btn btn-info btn-lg" disabled>Send</button>-
							</div>
						</div>
					</form>-->
											<?php
											} else {
											?>
												<form action="" method="post" enctype="multipart/form-data">
													<?= csrf_token_html(); ?>
													<div style="margin-top:10px">
														<input type="hidden" name="projid" value="<?php echo $projid ?>">
														<input type="hidden" name="issueid" value="<?php echo $projisd ?>">
														<input type="hidden" name="username" value="<?php echo $owner ?>">
														<input type="hidden" name="parent" value="<?php echo $parentid ?>">
														<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
															<textarea placeholder="Type your message here" class="form-control " name="comments"></textarea>
														</div>
														<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 text-right">
															<span id="counter"></span>
															<label class="btn btn-secondary btn-circle btn-sm" data-toggle="tooltip" data-placement="top" title="Attach file">
																<i class="fa fa-paperclip" aria-hidden="true"></i>
																<input type="file" name="file" id="file" multiple>
															</label>
															<button type="submit" class="btn btn-info btn-lg" name="send">Post</button>
														</div>
													</div>
												</form>
											<?php
											}
											?>
										</div>
									</div>
								</div>
							</div>
							<!--</div> -->
						</div>
					</div>
				</div>
			</div>
		</section>


		<script src="assets/js/jquery.min.js"></script>
		<!-- Bootstrap tether Core JavaScript -->
		<script src="assets/js/popper.min.js"></script>
		<script src="assets/js/bootstrap.min.js"></script>
		<!-- slimscrollbar scrollbar JavaScript -->
		<script src="assets/js/jquery.slimscroll.js"></script>
		<!--Custom JavaScript -->
		<script src="assets/js/custom.min.js"></script>
		<script src="assets/js/chat.js"></script>
		<!-- end body  -->
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
<script>
	$(document).on('change', ':file', function(e) {
		var input = $(this),
			numFiles = input.get(0).files ? input.get(0).files.length : 1,
			label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		input.trigger('fileselect', [numFiles, label]);
		if (numFiles == 1) {
			$("#counter").html(numFiles + " file");
		} else {
			$("#counter").html(numFiles + " files");
		}
	});

	$(".right-panel").animate({
		scrollTop: $(document).height()
	}, "slow");
	$(document).ready(function() {
		var showChar = 150; // How many characters are shown by default
		var ellipsestext = "...";
		var moretext = "Show more >";
		var lesstext = "Show less";
		$('.more').each(function() {
			var content = $(this).html();
			if (content.length > showChar) {
				var c = content.substr(0, showChar);
				var h = content.substr(showChar, content.length - showChar);
				var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink" style="color:blue">' + moretext + '</a></span>';
				$(this).html(html);
			}

		});

		$(".morelink").click(function() {
			if ($(this).hasClass("less")) {
				$(this).removeClass("less");
				$(this).html(moretext);
			} else {
				$(this).addClass("less");
				$(this).html(lesstext);
			}
			$(this).parent().prev().toggle();
			$(this).prev().toggle();
			return false;
		});
	});
	$(document).ready(function() {
		$(".fancybox").fancybox({
			openEffect: "none",
			closeEffect: "none"
		});
	});

	function CallIssueAssignment(id) {
		$.ajax({
			type: 'post',
			url: 'callissueaction.php',
			data: {
				rskid: id
			},
			success: function(data) {
				$('#issueassignment').html(data);
				$("#assignModal").modal({
					backdrop: "static"
				});
			}
		});
	}

	function CallRiskAction(id) {
		$.ajax({
			type: 'post',
			url: 'callriskaction.php',
			data: {
				rskid: id
			},
			success: function(data) {
				$('#riskaction').html(data);
				$("#riskModal").modal({
					backdrop: "static"
				});
			}
		});
	}

	function CallRiskAnalysis(id) {
		$.ajax({
			type: 'post',
			url: 'callriskanalysis.php',
			data: {
				rskid: id
			},
			success: function(data) {
				$('#riskanalysis').html(data);
				$("#riskAnalysisModal").modal({
					backdrop: "static"
				});
			}
		});
	}
</script>