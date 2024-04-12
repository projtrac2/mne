<?php
try {
require('includes/head.php');
if ($permission) {
		if (isset($_GET["prg"]) && !empty($_GET["prg"])) {
			$progid = $_GET["prg"];
		}

		if (isset($_GET["userid"]) && !empty($_GET["userid"])) {
			$userid = $_GET["userid"];
		}

		$query_userdetails =  $db->prepare("SELECT ptid FROM tbl_projteam2 t inner join tbl_users u on u.pt_id=t.ptid WHERE u.username = '$user_name'");
		$query_userdetails->execute();
		$row_userdetails = $query_userdetails->fetch();
		$userid = $row_userdetails["ptid"];
		//$userid = 30;


		//get financial years 
		$query_rsYear =  $db->prepare("SELECT id, year FROM tbl_fiscal_year");
		$query_rsYear->execute();
		$row_rsYear = $query_rsYear->fetch();
		$totalRows_rsYear = $query_rsYear->rowCount();

		//get subcounty
		$query_rsComm =  $db->prepare("SELECT id, state FROM tbl_state WHERE parent IS NULL ORDER BY state ASC");
		$query_rsComm->execute();
		$row_rsComm = $query_rsComm->fetch();
		$totalRows_rsComm = $query_rsComm->rowCount();

		//get mapping type 
		$query_rsMapType =  $db->prepare("SELECT id, type FROM tbl_map_type");
		$query_rsMapType->execute();
		$row_rsMapType = $query_rsMapType->fetch();
		$totalRows_rsMapType = $query_rsMapType->rowCount();

		//get project implementation methods 
		$query_rsProjImplMethod =  $db->prepare("SELECT id, method FROM tbl_project_implementation_method");
		$query_rsProjImplMethod->execute();
		$row_rsProjImplMethod = $query_rsProjImplMethod->fetch();
		$totalRows_rsProjImplMethod = $query_rsProjImplMethod->rowCount();

		// get project risks 
		$query_rsRiskCategories =  $db->prepare("SELECT rskid, category FROM tbl_projrisk_categories");
		$query_rsRiskCategories->execute();
		$row_rsRiskCategories = $query_rsRiskCategories->fetch();
		$totalRows_rsRiskCategories = $query_rsRiskCategories->rowCount();
	
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
						<div class="card-header">
							<ul class="nav nav-tabs" style="font-size:14px">
								<li class="active" id="output_tab">
									<a data-toggle="tab" href="#output">
										<i class="fa fa-hourglass-half bg-orange" aria-hidden="true"></i> Output Baseline Survey Tasks &nbsp;
										<span class="badge bg-orange" id="output_counter"> 0</span>
									</a>
								</li>
								<li id="outcome_tab">
									<a data-toggle="tab" href="#outcome">
										<i class="fa fa-file-text-o bg-blue-grey" aria-hidden="true"></i> Outcome Survey Tasks&nbsp;
										<span class="badge bg-blue-grey" id="outcome_counter"> 0</span>
									</a>
								</li>
								<li id="impact_tab">
									<a data-toggle="tab" href="#impact">
										<i class="fa fa-pencil-square-o bg-light-blue" aria-hidden="true"></i> Impact Survey Tasks&nbsp;
										<span class="badge bg-light-blue" id="impact_counter"> 0</span>
									</a>
								</li>
							</ul>
						</div>
						<div class="body">
							<div class="table-responsive">

								<div class="tab-content">
									<div id="output" class="tab-pane fade in active">
										<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
											<thead>
												<tr class="bg-orange">
													<th style="width:5%">#</th>
													<th style="width:40%">Project</th>
													<th style="width:35%">Indicator</th>
													<th style="width:10%">Location</th>
													<th style="width:10%">Action</th>
												</tr>
											</thead>
											<tbody id="tbody_output">

											</tbody>
										</table>
									</div>
									<div id="outcome" class="tab-pane fade">
										<ul class="nav nav-tabs" style="font-size:14px">
											<li id="outcome_base_tab" class="active">
												<a data-toggle="tab" href="#outcome_baseline">
													<i class="fa fa-file-text-o bg-blue-grey" aria-hidden="true"></i> Baseline Tasks&nbsp;
													<span class="badge bg-blue-grey" id="outcome_baseline_counter"> 0</span>
												</a>
											</li>
											<li id="outcome_eval_tab">
												<a data-toggle="tab" href="#outcome_evaluation">
													<i class="fa fa-pencil-square-o bg-light-green" aria-hidden="true"></i> Evaluation Tasks&nbsp;
													<span class="badge bg-light-green" id="outcome_evaluation_counter"> 0</span>
												</a>
											</li>
										</ul>
										<div class="tab-content">
											<div id="outcome_baseline" class="tab-pane fade in active">
												<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
													<thead>
														<tr class="bg-blue-grey">
															<th style="width:5%">#</th>
															<th style="width:40%">Project</th>
															<th style="width:35%">Indicator</th>
															<th style="width:10%">Location</th>
															<th style="width:10%">Action</th>
														</tr>
													</thead>
													<tbody id="tbody_outcome_baseline">

													</tbody>
												</table>
											</div>
											<div id="outcome_evaluation" class="tab-pane fade">
												<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
													<thead>
														<tr class="bg-light-green">
															<th style="width:5%">#</th>
															<th style="width:40%">Project</th>
															<th style="width:35%">Indicator</th>
															<th style="width:10%">Location</th>
															<th style="width:10%">Action</th>

														</tr>
													</thead>
													<tbody id="tbody_outcome_evaluation">

													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div id="impact" class="tab-pane fade">
										<ul class="nav nav-tabs" style="font-size:14px">
											<li id="impact_base_tab">
												<a data-toggle="tab" href="#impact_baseline">
													<i class="fa fa-file-text-o bg-light-blue" aria-hidden="true"></i> Baseline Tasks&nbsp;
													<span class="badge bg-light-blue" id="impact_baseline_counter"> 0</span>
												</a>
											</li>
											<li id="impact_eval_tab">
												<a data-toggle="tab" href="#impact_evaluation">
													<i class="fa fa-pencil-square-o bg-light-green" aria-hidden="true"></i> Evaluation Tasks&nbsp;
													<span class="badge bg-light-green" id="impact_evaluation_counter"> 0</span>
												</a>
											</li>
										</ul>
										<div class="tab-content">
											<div id="impact_baseline" class="tab-pane fade">
												<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
													<thead>
														<tr class="bg-light-blue">
															<th style="width:5%">#</th>
															<th style="width:40%">Project</th>
															<th style="width:35%">Indicator</th>
															<th style="width:10%">Location</th>
															<th style="width:10%">Action</th>

														</tr>
													</thead>
													<tbody id="tbody_impact_baseline">

													</tbody>
												</table>
											</div>
											<div id="impact_evaluation" class="tab-pane fade">
												<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
													<thead>
														<tr class="bg-light-green">
															<th style="width:5%">#</th>
															<th style="width:40%">Project</th>
															<th style="width:35%">Indicator</th>
															<th style="width:10%">Location</th>
															<th style="width:10%">Action</th>

														</tr>
													</thead>
													<tbody id="tbody_impact_evaluation">

													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
	</section>
	<!-- end body  -->
<?php
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');

} catch (PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());

}
?>
<script src="general-settings/js/fecth-selected-baseline-tasks-items.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		var userid;
		userid = <?php echo $user_name; ?>;
		get_all(userid);
	});

	function CallRiskAction(id) {
		$.ajax({
			type: 'post',
			url: 'callriskaction',
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
</script>