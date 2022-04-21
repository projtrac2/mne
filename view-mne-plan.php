<?php
$pageName = "Strategic Plans";
$replacement_array = array(
	'planlabel' => "CIDP",
	'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');

if ($permission) {
	$pageTitle = "Projects Monitoring and Evaluation";
	try {
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
					<i class="fa fa-columns" aria-hidden="true"></i>
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
					<div class="card">
						<div class="body">
							<!-- ============================================================== -->
							<!-- Start Page Content -->
							<!-- ============================================================== -->
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover" id="manageItemTable">
									<thead>
										<tr>
											<th width="5%">#</th>
											<th width="33%">Project Name</th>
											<th width="32%">Program Name</th>
											<th width="10%">Status</th>
											<th width="10%">Due Date</th>
											<th width="10%">Action</th>
										</tr>
									</thead>
								</table>
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


	<!-- Start Item more -->
	<div class="modal fade" tabindex="-1" role="dialog" id="moreItemModal">
		<div class="modal-dialog  modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info"></i> More Information</h4>
				</div>
				<div class="modal-body" id="moreinfo">
				</div>
				<div class="modal-footer">
					<div class="col-md-12 text-center">
						<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Close</button>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>

	<!-- Start Item Delete -->
	<div class="modal fade" tabindex="-1" role="dialog" id="removeItemModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i> Delete Item</h4>
				</div>
				<div class="modal-body">
					<div class="removeItemMessages"></div>
					<p align="center">Are you sure you want to delete this record?</p>
				</div>
				<div class="modal-footer removeProductFooter">
					<div class="col-md-12 text-center">
						<button type="button" class="btn btn-success" id="removeItemBtn"> <i class="fa fa-check-square-o"></i> Delete</button>
						<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Cancel</button>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->


	<div class="modal fade" id="checklistModal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-list"></i> Monitoring Checklist</h4>
				</div>
				<div class="modal-body">
					<div class="card">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="body" id="">
									<div class="table-responsive">
										<table class="table table-bordered table-striped table-hover" id="manageItemTable">
											<thead>
												<tr>
													<th width="5%">#</th>
													<th width="33%">Project Name</th>
													<th width="32%">Program Name</th>
													<th width="10%">Status</th>
													<th width="10%">Due Date</th>
													<th width="10%">Action</th>
												</tr>
											</thead>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> <!-- /modal-body -->
				<div class="modal-footer">
					<div class="col-md-12 text-center">
						<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Close</button>
					</div>
				</div> <!-- /modal-footer -->
			</div> <!-- /modal-content -->
		</div> <!-- /modal-dailog -->
	</div>

<?php
} else {
	$results =  restriction();
	echo $results;
}
require('includes/footer.php');
?>
<script src="assets/custom js/fetch-monitoring-evaluation.js"></script>