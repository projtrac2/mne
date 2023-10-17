<?php
require('includes/head.php');
if ($permission) {
	try {
		$query_rsUpP = $db->prepare("SELECT p.*, p.projcost, p.projstartdate AS sdate, p.projenddate AS edate, p.dateentered AS pdate FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid WHERE  p.projstage=9 AND g.program_type=1 AND p.deleted='0' ORDER BY p.projid DESC");
		$query_rsUpP->execute();
		$row_rsUpP = $query_rsUpP->fetch();
		$rows_count = $query_rsUpP->rowCount();

		$query_indProjs = $db->prepare("SELECT p.*, p.projcost, p.projstartdate AS sdate, p.projenddate AS edate, p.dateentered AS pdate FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid WHERE p.projstage=9 AND g.program_type=0 AND p.deleted='0' ORDER BY p.projid DESC");
		$query_indProjs->execute();
		$row_indProjs = $query_indProjs->fetch();
		$count_rows_indProjs = $query_indProjs->rowCount();

		include_once('projects-functions.php');
	} catch (PDOException $ex) {
		$results = flashMessage("An error occurred: " . $ex->getMessage());
	}
?>
	<style>
		.modal-lg {
			max-width: 100% !important;
			width: 90%;
		}
	</style>
	<!-- start body  -->
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<?= $icon ?>
					<?= $pageTitle ?>
					<div class="btn-group" style="float:right">
						<div class="btn-group" style="float:right">
						</div>
					</div>
				</h4>
			</div>
			<div class="row clearfix">
				<div class="block-header">
					<?= $results; ?>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<ul class="nav nav-tabs" style="font-size:14px">
							<li class="active">
								<a data-toggle="tab" href="#home"><i class="fa fa-caret-square-o-down bg-green" aria-hidden="true"></i> Strategic Plan Projects &nbsp;<span class="badge bg-green"><?= $rows_count ?></span></a>
							</li>
							<li>
								<a data-toggle="tab" href="#menu1"><i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> Independent Projects &nbsp;<span class="badge bg-blue"><?= $count_rows_indProjs ?></span></a>
							</li>
						</ul>
						<div class="body">
							<!-- strat body -->
							<div class="tab-content">
								<div id="home" class="tab-pane fade in active">
									<div class="body">
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
												<thead>
													<tr id="colrow">
														<th width="4%"><strong>SN</strong></th>
														<th width="28%"><strong>Project</strong></th>
														<th width="12%"><strong><?= $departmentlabel ?></strong></th>
														<th width="10%"><strong>Status & Progress(%)</strong></th>
														<th width="7%"><strong>Issues</strong></th>
														<th width="9%"><strong><?= $level2label ?></strong></th>
														<th width="10%"><strong>Fiscal Year</strong></th>
														<th width="10%"><strong>Last Update</strong></th>
														<th width="10%"><strong>Other Details</strong></th>
													</tr>
												</thead>
												<tbody>
													<!-- =========================================== -->
													<?php include_once('all-sp-projects.php'); ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div id="menu1" class="tab-pane fade">
									<div class="body">
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
												<thead>
													<tr id="colrow">
														<th width="4%"><strong>SN</strong></th>
														<th width="28%"><strong>Project</strong></th>
														<th width="12%"><strong><?= $departmentlabel ?></strong></th>
														<th width="10%"><strong>Status & Progress(%)</strong></th>
														<th width="7%"><strong>Issues</strong></th>
														<th width="9%"><strong><?= $level2label ?></strong></th>
														<th width="10%"><strong>Fiscal Year</strong></th>
														<th width="10%"><strong>Last Update</strong></th>
														<th width="10%"><strong>Other Details</strong></th>
													</tr>
												</thead>
												<tbody>
													<!-- =========================================== -->
													<?php include_once('all-ind-projects.php'); ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							<!-- end body -->
						</div>
					</div>
				</div>
			</div>
	</section>
	<!-- end body  -->

	<!-- Modal Scorecard-->
	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">
						<font color="#000000">Project Scorecard</font>
					</h4>
				</div>
				<div class="modal-body" id="formcontent">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Project Details-->
	<div class="modal fade" id="projDetails" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title">
						<font color="#000000">Project Details</font>
					</h3>
				</div>
				<div class="modal-body" id="detailscontent">
				</div>
				<div class="modal-footer">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"  align="center">
						<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="projectModal" class="modal fade">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<h4 class="modal-title new-title" ALIGN="center" style="color:#FFF">Modal Title</h4>
				</div>
				<div class="modal-body">
					<div id="map"></div>
					<div id="photo"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Project Issues -->
	<div class="modal fade" id="projIssues" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 class="modal-title" align="center" style="color:#FF5722; font-size:24px">Project Issues</h2>
				</div>
				<div class="modal-body" id="issuescontent">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
<?php
} else {
	$results =  restriction();
	echo $results;
}
require('includes/footer.php');
?>

<script type="text/javascript">
	$(document).ready(function() {
		$(".account").click(function() {
			var X = $(this).attr('id');

			if (X == 1) {
				$(".submenus").hide();
				$(this).attr('id', '0');
			} else {

				$(".submenus").show();
				$(this).attr('id', '1');
			}

		});

		//Mouseup textarea false
		$(".submenus").mouseup(function() {
			return false
		});
		$(".account").mouseup(function() {
			return false
		});


		//Textarea without editing.
		$(document).mouseup(function() {
			$(".submenus").hide();
			$(".account").attr('id', '');
		});

	});

	function GetScorecard(projid) {
		var prog = $("#scardprog").val();
		$.ajax({
			type: 'post',
			url: 'getscorecard',
			data: {
				prjid: projid,
				scprog: prog
			},
			success: function(data) {
				$('#formcontent').html(data);
				$("#myModal").modal({
					backdrop: "static"
				});
			}
		});
	}

	function GetProjDetails(projid) {
		$.ajax({
			type: 'post',
			url: 'general-settings/selected-items/fetch-selected-project-details',
			data: {
				itemId: projid
			},
			success: function(data) {
				$('#detailscontent').html(data);
				$("#projDetails").modal({
					backdrop: "static"
				});
			}
		});
	}

	function GetProjIssues(projid) {
		$.ajax({
			type: 'post',
			url: 'getprojissues',
			data: {
				prjid: projid
			},
			success: function(data) {
				$('#issuescontent').html(data);
				$("#projIssues").modal({
					backdrop: "static"
				});
			}
		});
	}
</script>