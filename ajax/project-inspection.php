<?php
require('includes/head.php');
if ($permission) {
	try {
		$projid = base64_decode(htmlspecialchars(trim($_GET['projid'])));
		$query_OutputData = $db->prepare("SELECT d.id, g.output FROM tbl_project_details d INNER JOIN tbl_progdetails g ON  g.id = d.outputid WHERE projid=:projid ORDER BY id");
		$query_OutputData->execute(array(":projid" => $projid));
		$row_OutputData =  $query_OutputData->fetch();
		$rows_OutpuData = $query_OutputData->rowCount();

		function validate_tasks($output_id)
		{
			global $db;
			$query_rs_tasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid = :output_id");
			$query_rs_tasks->execute(array(":output_id" => $output_id));
			$totalRows_rs_tasks = $query_rs_tasks->rowCount();
			$msg = [];
			if ($totalRows_rs_tasks > 0) {
				while ($row_rs_tasks = $query_rs_tasks->fetch()) {
					$task_id = $row_rs_tasks['tkid'];
					$query_rs_direct_cost = $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE tasks = :task_id AND inspection_status =0");
					$query_rs_direct_cost->execute(array(":task_id" => $task_id));
					$totalRows_rs_direct_cost = $query_rs_direct_cost->rowCount();
					$msg[] = $totalRows_rs_direct_cost > 0 ? false : true;
				}
			}
			return (in_array(false, $msg)) ? true : false;
		}
	} catch (PDOException $ex) {
		$results =  flashMessage("An error occurred: " . $ex->getMessage());
	}
?>
	<!-- start body  -->
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<?= $icon ?>
					<?php echo $pageTitle ?>
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
					<form id="submitMilestoneForm" action="" method="POST" enctype="multipart/form-data">
						<div class="card">
							<div class="body">
								<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
									<label>Output *:</label>
									<div class="form-line">
										<select name="output_id" id="output_id" class="form-control" onchange="get_output_details()" required>
											<option value="">Select Output</option>
											<?php
											if ($rows_OutpuData > 0) {
												do {
													$output_id = $row_OutputData['id'];
													$output = $row_OutputData['output'];
													$perm = validate_tasks($output_id);
													if ($perm) {
											?>
														<option value="<?= $output_id ?>"><?= $output ?></option>
											<?php
													}
												} while ($row_OutputData == $query_OutputData->fetch());
											}
											?>
										</select>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
									<label>Project <?= $level3label ?> *:</label>
									<div class="form-line">
										<select name="location" id="location" class="form-control" required>
										</select>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
									<label>Milestones *:</label>
									<div class="form-line">
										<select name="milestone" id="milestone" class="form-control" required>
										</select>
									</div>
								</div>
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Tasks Inspection </legend>
									<div class="table-responsive">
										<table class="table table-bordered table-striped table-hover js-basic-example" id="manageItemTable">
											<thead>
												<tr class="bg-orange">
													<th style="width:7%">#</th>
													<th style="width:10%">Task</th>
													<th style="width:10">Action</th>
												</tr>
											</thead>
											<tbody id="task_table_body">

											</tbody>
										</table>
									</div>
								</fieldset>

								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Monitoring </legend>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="custom-control custom-checkbox">
											<input type="checkbox" name="monitoring" class="custom-control-input" value="1" id="defaultUnchecked">
											<label class="custom-control-label" for="defaultUnchecked">Monitoring required ?</label>
										</div>
									</div>
								</fieldset>
								<div class="row clearfix">

									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
										<input type="hidden" name="newitem" id="newitem" value="new">
										<input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
										<input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
										<button onclick="history.back()" type="button" class="btn btn-warning waves-effect waves-light">Cancel</button>
										<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Finish Inspection" />
									</div>
								</div>
							</div>
						</div> <!-- /modal-body -->
					</form> <!-- /.form -->
				</div>
			</div> <!-- /modal-content -->
		</div> <!-- /modal-dailog -->
	</section>
	<!-- End add item -->

<?php
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');
?>

<script>
	$(document).ready(function() {


	});

	function get_output_details() {
		var projid = $("#projid").val();
		var output_id = $("#output_id").val();
		if (projid != "" && output_id != "") {
			$.ajax({
				type: "get",
				url: "ajax/inspection/index",
				data: {
					get_project_details: "get_project_details",
					projid: projid,
					output_id: output_id,
				},
				dataType: "json",
				success: function(response) {
					if (response.success) {
						$("#milestone").html(response.milestone);
						$("#location").html(response.location);
					} else {
						console.log("This is the task");
					}
				}
			});
		}
	}
</script>