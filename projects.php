<?php
$pageName = "Projects";
$replacement_array = array(
	'planlabel' => "CIDP",
	'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
if ($permission) {
	$pageTitle = "Projects";

	try {
		$query_rsUpP = $db->prepare("SELECT p.*, p.projcost, p.projstartdate AS sdate, p.projenddate AS edate, p.dateentered AS pdate FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid WHERE  p.projstage=10 AND g.program_type=1 AND p.deleted='0' ORDER BY p.projid DESC");
		$query_rsUpP->execute();
		$row_rsUpP = $query_rsUpP->fetch();
		$rows_count = $query_rsUpP->rowCount();

		$query_indProjs = $db->prepare("SELECT p.*, p.projcost, p.projstartdate AS sdate, p.projenddate AS edate, p.dateentered AS pdate FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid WHERE p.projstage=10 AND g.program_type=0 AND p.deleted='0' ORDER BY p.projid DESC");
		$query_indProjs->execute();
		$row_indProjs = $query_indProjs->fetch();
		$count_rows_indProjs = $query_indProjs->rowCount();
	} catch (PDOException $ex) {
		$results = flashMessage("An error occurred: " . $ex->getMessage());
	}
?>
	<!-- start body  -->
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<i class="fa fa-columns" aria-hidden="true"></i>
					<?php echo $pageTitle ?>
					<div class="btn-group" style="float:right">
						<div class="btn-group" style="float:right">
							<?php
							if ($action_permission) {
							?>
							<?php
							}
							?>
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
														<th width="9%"><strong>Location</strong></th>
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
														<th width="9%"><strong>Location</strong></th>
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
<?php
} else {
	$results =  restriction();
	echo $results;
}
require('includes/footer.php');
?>
