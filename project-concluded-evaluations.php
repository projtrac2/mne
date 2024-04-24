<?php
require('includes/head.php');

if ($permission) {
	try {
		$query_conclEvaluation = $db->prepare("SELECT p.projid, p.projname, c.variable_category AS cat, c.date_created AS date, c.id, i.indicator_name, c.survey_type, c.formkey FROM tbl_projects p INNER JOIN tbl_survey_conclusion c ON c.projid=p.projid inner join tbl_indicator i on i.indid=c.indid GROUP BY c.formkey ORDER BY c.id ASC");
		$query_conclEvaluation->execute();
		$count_conclEvaluation = $query_conclEvaluation->rowCount();
	} catch (PDOException $ex) {
		$results = flashMessage("An error occurred: " . $ex->getMessage());
	}
?>

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
						<div class="body">
							<!-- ============================================================== -->
							<!-- Start Page Content -->
							<!-- ============================================================== -->
							<div class="table-responsive">
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"> LIST </legend>
									<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
										<thead>
											<tr class="bg-light-green">
												<th style="width:3%">#</th>
												<th style="width:38%">Project Name</th>
												<th style="width:25%">Indicator</th>
												<th style="width:15%">Evaluation Type</th>
												<th style="width:12%">Conclusion Date</th>
												<th style="width:7%">Report</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$nm = 0;
											while ($row_conclEvaluation = $query_conclEvaluation->fetch()) {
												$nm = $nm + 1;
												$formkey = $row_conclEvaluation['formkey'];
												$surveytype = $row_conclEvaluation['survey_type'];
												$project = $row_conclEvaluation['projname'];
												$category = $row_conclEvaluation['cat'];
												$indicator = $row_conclEvaluation['indicator_name'];
												$concludeddate = date("d M Y", strtotime($row_conclEvaluation["date"]));
												$conclid = $row_conclEvaluation["id"];
												$projid = $row_conclEvaluation['projid'];

												$query_evaluation_data_source = $db->prepare("SELECT data_source FROM tbl_project_expected_outcome_details WHERE projid=:projid");
												$query_evaluation_data_source->execute(array(":projid" => $projid));
												$row_evaluation_data_source = $query_evaluation_data_source->fetch();
												$data_source = $row_evaluation_data_source["data_source"];
												$formkey = base64_encode($formkey);
												$projid = base64_encode($projid);
											?>
												<tr style="background-color:#eff9ca">
													<td align="center"><?php echo $nm; ?></td>
													<td><?php echo $project; ?></td>
													<td><?php echo $indicator; ?></td>
													<td><?php echo $surveytype; ?></td>
													<td><?php echo $concludeddate; ?></td>
													<td>
														<div align="center">
															<?php if ($data_source == 1) { ?>
																<a href="primary-data-evaluation-report?fkey=<?php echo $formkey; ?>" alt="Project Evaluation Report" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Project Evaluation Report (Primary Data)"><i class="fa fa-bar-chart fa-2x text-success" aria-hidden="true"></i></a>
															<?php } else { ?>
																<a href="secondary-data-evaluation-report?prjid=<?php echo $projid; ?>" alt="Project Evaluation Report (Secondary Data)" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Project Evaluation Report"><i class="fa fa-bar-chart fa-2x text-success" aria-hidden="true"></i></a>
															<?php } ?>
														</div>
													</td>
												</tr>
											<?php
											}
											?>
										</tbody>
									</table>
								</fieldset>
							</div>

							<!-- ============================================================== -->
							<!-- End PAge Content -->
							<!-- ============================================================== -->
						</div>
					</div>
				</div>
			</div>
	</section>
	<!-- end body  -->

	<script language="javascript" type="text/javascript">
		$(document).ready(function() {
			$('#assign-inspection-form').on('submit', function(event) {
				event.preventDefault();
				var form_data = $(this).serialize();
				$.ajax({
					type: "POST",
					url: "inspectorassignment.php",
					data: form_data,
					dataType: "json",
					success: function(response) {
						if (response) {
							alert('Record successfully saved');
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
	<!-- Modal Receive Payment -->
	<div class="modal fade" id="assignModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center">
						<font color="#FFF">Milestone Inspection Assignment</font>
					</h3>
				</div>
				<form class="tagForm" action="inspectorassignment" method="post" id="assign-inspection-form" enctype="multipart/form-data" autocomplete="off">
					<div class="modal-body">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="card">
									<div class="body">
										<div class="table-responsive" style="background:#eaf0f9">
											<div id="checklistassignment">
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
							<input type="hidden" name="username" id="username" value="<?php echo $user_name; ?>" />
						</div>
						<div class="col-md-4">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- #END# Modal Receive Payment-->
<?php
} else {
	$results =  restriction();
	echo $results;
}
require('includes/footer.php');
?>