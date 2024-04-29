<?php
require('includes/head.php');
if ($permission) {
	try {
		$decode_topic_id = (isset($_GET['topic']) && !empty($_GET["topic"])) ? base64_decode($_GET['topic']) : "";
		$topic_id_array = explode("projid54321", $decode_topic_id);
		$topic_id = $topic_id_array[1];

		if (isset($_POST['send'])) {
			$projid = $_POST['projid'];
			$topic_id = $_POST['topic_id'];
			$comments = $_POST['comments'];
			$file_path = $file_type = '';
			if (!empty($_FILES['file']['name'])) {
				$filename = basename($_FILES['file']['name']);
				$file_type = substr($filename, strrpos($filename, '.') + 1);
				if (($file_type != "exe") && ($_FILES["file"]["type"] != "application/x-msdownload")) {
					$newname = time() . "_" . $filename;
					$file_path = "uploads/discussions/" . $newname;
					if (!file_exists($file_path)) {
						if (!move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
							$message = "Error";
						}
					}
				}
			}

			$sql = $db->prepare("INSERT INTO tbl_project_discussions (projid,topic_id,message,file_path,file_type,created_by) VALUES(:projid,:topic_id,:message,:file_path,:file_type,:created_by)");
			$sql->execute(array(":projid" => $projid, ":topic_id" => $topic_id, ":message" => $comments, ":file_path" => $file_path, ":file_type" => $file_type, ":created_by" => $user_name));
			$topic_hashed = base64_encode("projid54321{$topic_id}");
			$redirect_url = "project-discussion.php?topic=" . $topic_hashed;
			$msg = 'Message sent';
			$results = "<script type=\"text/javascript\">
				swal({
					title: \"Success!\",
					text: \" $msg\",
					type: 'Success',
					timer: 2000,
					'icon':'success',
				showConfirmButton: false });
				setTimeout(function(){
					window.location.href = '$redirect_url';
				}, 2000);
			</script>";
		}

		$query_discussion_topics = $db->prepare("SELECT * FROM `tbl_project_discussion_topics` d INNER JOIN users u  ON u.userid=d.created_by INNER JOIN tbl_projteam2 p  ON u.pt_id = p.ptid WHERE id = :topic_id ORDER BY id ASC");
		$query_discussion_topics->execute(array(':topic_id' => $topic_id));
		$row_issuediscussion_topics = $query_discussion_topics->fetch();
		$totalRows_issuediscussion_topics = $query_discussion_topics->rowCount();
		$projid = $row_issuediscussion_topics['projid'];
		$topic = $row_issuediscussion_topics['topic'];
		$projid_hashed = base64_encode("projid54321{$projid}");

		//get active module
		$query_discussion = $db->prepare("SELECT * FROM `tbl_project_discussions` d INNER JOIN users u  ON u.userid=d.created_by INNER JOIN tbl_projteam2 p  ON u.pt_id = p.ptid WHERE topic_id = :topic_id ORDER BY id ASC");
		$query_discussion->execute(array(':topic_id' => $topic_id));
		$totalRows_issuediscussion = $query_discussion->rowCount();
	} catch (PDOException $ex) {
		$result = flashMessage("An error occurred: " . $ex->getMessage());
		print($result);
	}
?>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/style.css">
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:-60px; padding-top:5px; padding-bottom:5px; padding-left:15px; padding-right:15px; color:#FFF">
				<h4 class="contentheader" style="color:#FFF">
					<?= $icon ?>
					<?= $pageTitle ?>
					<?= $results ?>
					<div class="btn-group" style="float:right">
						<a type="button" id="outputItemModalBtnrow" href="project-collaboration.php?projid=<?= $projid_hashed ?>" class="btn btn-warning pull-right" style="margin-top:-5px">
							Exit Discussion
						</a>
					</div>
				</h4>
			</div>
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="card-header">
							<div class="row clearfix">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<ul class="list-group">
										<li class="list-group-item "> <strong>Discussion Subject: <u><?= $topic ?> </u></strong></li>
									</ul>
								</div>
							</div>
						</div>
						<div class="body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="m-b-0">
										<div class="chat-main-box right-panel v-scroll col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<!-- .chat-right-panel -->
											<div class="chat-right-aside">
												<div class="chat-rbox">
													<ul class="timeline">
														<?php
														if ($totalRows_issuediscussion > 0) {
															while ($row_issuediscussion = $query_discussion->fetch()) {
																$comments = $row_issuediscussion['message'];
																$file_type = $row_issuediscussion['file_type'];
																$avatar = $row_issuediscussion['floc'];
																$file_path = $row_issuediscussion['file_path'];
																$created_at = $row_issuediscussion['created_at'];
																$created_by = $row_issuediscussion['userid'];
																$image_array = array('gif', 'jpg', 'jpeg', 'png');
																$audio_array = array('mp3');
																$video_array = array('mp4');
																$title_id = $row_issuediscussion["title"];

																$query_rsTitle = $db->prepare("SELECT * FROM tbl_titles WHERE id=:title_id");
																$query_rsTitle->execute(array(":title_id" => $title_id));
																$row_rsTitle = $query_rsTitle->fetch();
																$totalRows_rsTitle = $query_rsTitle->rowCount();
																$title = $totalRows_rsTitle > 0 ? $row_rsTitle['title'] : '';
																$poster = $title . "." . $row_issuediscussion["fullname"];

																if ($created_by == $user_name) {
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
																		<img class="img-responsive" alt="user" src="<?= $avatar ?>" alt="img" data-toggle="tooltip" title="Posted By: <?= $poster ?>;">
																		<span> text</span>
																	</div>
																	<div class="timeline-panel">
																		<div class="timeline-body">
																			<?php
																			if ($file_path != '') {
																				if (in_array($file_type, $image_array)) {
																			?>
																					<img class="img-responsive" alt="user" src="<?= $file_path ?>" alt="img" data-toggle="tooltip" title="Posted By: <?= $poster ?>;">
																				<?php
																				} else if (in_array($file_type, $video_array)) {
																				?>
																					<video controls width="250" class="img-responsive">
																						<source src="<?= $file_path ?>" type="video/<?= $file_type ?>" />
																					</video>
																				<?php
																				} else if (in_array($file_type, $audio_array)) {
																				?>
																					<figure>
																						<figcaption>Listen:</figcaption>
																						<audio controls src="<?= $file_path ?>">
																							<a href="<?= $file_path ?>"> Download audio </a>
																						</audio>
																					</figure>
																				<?php
																				} else {
																				?>
																					<a href="<?= $file_path ?>"> Download File </a>
																			<?php
																				}
																			}
																			?>
																			<p class="more"><?= $comments; ?></p>
																			<span style="color:red" class="pull-right"><small><?= $created_at ?></small></span>
																		</div>
																	</div>
																	</li>
															<?php
															}
														}
															?>
													</ul>
												</div>
											</div>
											<!-- .chat-right-panel -->
										</div>
										<form action="" method="post" enctype="multipart/form-data">
											<?= csrf_token_html(); ?>
											<div style="margin-top:10px">
												<input type="hidden" name="projid" value="<?php echo $projid ?>">
												<input type="hidden" name="topic_id" value="<?php echo $topic_id ?>">
												<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
													<textarea placeholder="Type your message here" class="form-control " name="comments"></textarea>
												</div>
												<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 text-right">
													<span id="counter"></span>
													<label class="btn btn-secondary btn-circle btn-sm" data-toggle="tooltip" data-placement="top" title="Attach file">
														<i class="fa fa-paperclip" aria-hidden="true"></i>
														<input type="file" name="file" id="file">
													</label>
													<button type="submit" class="btn btn-info btn-lg" name="send">Post</button>
												</div>
											</div>
										</form>
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