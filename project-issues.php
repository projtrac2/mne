<?php
$decode_projid = (isset($_GET['proj']) && !empty($_GET["proj"])) ? base64_decode($_GET['proj']) : header("Location: projects");
$projid_array = explode("projid54321", $decode_projid);
$projid = $projid_array[1];

$original_projid = $_GET['proj'];
require('includes/head.php');

if ($permission) {
	try {
		$query_projdetails = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
		$query_projdetails->execute(array(":projid" => $projid));
		$row_projdetails = $query_projdetails->fetch();
		$projcode = $row_projdetails['projcode'];
		$projname = $row_projdetails['projname'];
		$projstage = $row_projdetails["projstage"];
		$projcat = $row_projdetails["projcategory"];
		$percent2 = number_format(calculate_project_progress($projid, $projcat),2);
			
		$query_proj_risks = $db->prepare("SELECT * FROM tbl_project_risks r left join tbl_projrisk_categories c on c.catid=r.risk_category WHERE projid=:projid GROUP BY id");
		$query_proj_risks->execute(array(":projid" => $projid));
		$totalRows_proj_risks = $query_proj_risks->rowCount();
			
		$query_risks_more_details = $db->prepare("SELECT fullname,tt.title, f.frequency FROM tbl_project_risk_details d left join users u on u.userid=d.responsible left join tbl_projteam2 t on t.ptid=u.pt_id left join tbl_datacollectionfreq f on f.fqid=d.frequency left join tbl_titles tt on tt.id=t.title WHERE projid=:projid");
		$query_risks_more_details->execute(array(":projid" => $projid));
		$row_risks_more_details = $query_risks_more_details->fetch();
		$totalRows_risks_more_details = $query_risks_more_details->rowCount();
		$frequency = $responsible = "";
		if($row_risks_more_details){
			$frequency = $row_risks_more_details["frequency"];
			$responsible = $row_risks_more_details["title"].".".$row_risks_more_details["fullname"];
		}
		
		function string_length($x, $length)
		{
			$y = "";
			if(strlen($x)<=$length)
			{
				$y = $x;
				return $y;
			}
			else
			{
				$y=substr($x,0,$length) . ' <span class="text-danger"><strong>...</strong></span>';
				return $y;
			}
		}
		
		$query_last_monitoring = $db->prepare("SELECT date_created FROM tbl_project_risk_monitoring WHERE projid=:projid ORDER BY id DESC Limit 1");
		$query_last_monitoring->execute(array(":projid" =>$projid));
		$row_last_monitoring = $query_last_monitoring->fetch();
		$last_monitoring = date("d M Y", strtotime($row_last_monitoring["date_created"]));

		$query_issues = $db->prepare("SELECT c.catid, c.category, i.id, i.issue_description, i.issue_area, i.issue_impact, i.issue_priority, i.date_created, i.status FROM tbl_projrisk_categories c left join tbl_projissues i on c.catid = i.risk_category WHERE projid = :projid");
		$query_issues->execute(array(":projid" => $projid));
		$count_issues = $query_issues->rowCount();

		function get_inspection_status($status_id)
		{
			global $db;
			$sql = $db->prepare("SELECT * FROM tbl_issue_status WHERE id = :status_id");
			$sql->execute(array(":status_id" => $status_id));
			$row = $sql->fetch();
			$rows_count = $sql->rowCount();
			return ($rows_count > 0) ? $row['status'] : "";
		}
	} catch (PDOException $ex) {
		$result = flashMessage("An error occurred: " . $ex->getMessage());
		print($result);
	}
?>
	<!-- JQuery Nestable Css -->
	<link href="projtrac-dashboard/plugins/nestable/jquery-nestable.css" rel="stylesheet" />
	<link rel="stylesheet" href="assets/css/strategicplan/view-strategic-plan-framework.css">
	<link rel="stylesheet" href="css/highcharts.css">
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/highcharts-3d.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>
	<script src="https://code.highcharts.com/modules/export-data.js"></script>
	<script src="https://code.highcharts.com/modules/accessibility.js"></script>
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<?= $icon ?>
					<?php echo $pageTitle ?>

					<div class="btn-group" style="float:right; margin-right:10px">
						<input type="button" VALUE="Go Back to Projects Dashboard" class="btn btn-warning pull-right" onclick="location.href='projects.php'" id="btnback">
					</div>
				</h4>
			</div>
			<div class="row clearfix">
				<div class="block-header">
					<?= $results; ?>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="header" style="padding-bottom:0px">
							<div class="" style="margin-top:-15px">
								<a href="project-dashboard.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Dashboard</a>
								<a href="project-indicators.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Outputs</a>
								<a href="project-finance.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Finance</a>
								<a href="project-timeline.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Timeline</a>
								<?php if($projcat == 2 && $projstage > 4){ ?>
									<a href="project-contract-details.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Contract</a>
								<?php } ?>
								<a href="project-team-members.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Team</a>
								<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; width:100px">Risks & Issues</a>
								<a href="project-map.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Map</a>
								<a href="project-media.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Media</a>
							</div>
						</div>
						<h4>
							<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12" style="font-size:15px; background-color:#CDDC39; border:#CDDC39 thin solid; border-radius:5px; margin-bottom:2px; height:25px; padding-top:2px; vertical-align:center">
								Project Name: <font color="white"><?php echo $projname; ?></font>
							</div>
							<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="font-size:15px; background-color:#CDDC39; border-radius:5px; height:25px; margin-bottom:2px">
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
						<div class="card-header">
							<ul class="nav nav-tabs" style="font-size:14px">
								<li class="active">
									<a data-toggle="tab" href="#menu1"><i class="fa fa-exclamation-triangle bg-orange" aria-hidden="true"></i> Project Risks &nbsp;<span class="badge bg-orange">|</span></a>
								</li>
								<li>
									<a data-toggle="tab" href="#menu2"><i class="fa fa-question-circle bg-red" aria-hidden="true"></i> Project Issues &nbsp;<span class="badge bg-red">|</span></a>
								</li>
							</ul>
						</div>
						<div class="body">
							<div class="tab-content">
								<div id="menu1" class="tab-pane fade in active">
									<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">											
											<div class="header">
												<fieldset class="scheduler-border" style="background-color:#edfcf1; border-radius:3px">
												  <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-list-alt" style="color:green" aria-hidden="true"></i> Project Details</legend>
												  <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
													<label class="control-label">Project Code:</label>
													<div class="form-line">
													  <input type="text" class="form-control" value=" <?= $projcode ?>" readonly>
													</div>
												  </div>
													<?php 
													if($totalRows_risks_more_details > 0){
													  ?>
													  <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
														<label class="control-label">Monitoring Frequency:</label>
														<div class="form-line">
														  <input type="text" class="form-control" value=" <?= $frequency ?>" readonly>
														</div>
													  </div>
													  <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
														<label class="control-label">Monitoring Responsible:</label>
														<div class="form-line">
														  <input type="text" class="form-control" value=" <?= $responsible ?>" readonly>
														</div>
													  </div>
													  <?php
													}
													?>
													  <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 clearfix" style="margin-top:5px; margin-bottom:5px">
														<label class="control-label">Last Monitoring:</label>
														<div class="form-line">
														  <input type="text" class="form-control" value=" <?= $last_monitoring ?>" readonly>
														</div>
													  </div>
												</fieldset>
											</div>
											<div class="body">
												<div class="row clearfix">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<fieldset class="scheduler-border row setup-content" style="padding:10px">
															<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-exclamation-circle" style="color:orange" aria-hidden="true"></i> Project Risks</legend>
															<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																<div class="table-responsive">
																	<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
																		<thead>
																			<tr style="background-color:orange">
																				<th style="width:3%" align="center">#</th>
																				<th style="width:60%">Risk</th>
																				<th style="width:20%">Category</th>
																				<th style="width:12%">Risk Level</th>
																				<th style="width:5%" data-orderable="false">Action</th>
																			</tr>
																		</thead>
																		<tbody>
																			<?php
																			if ($totalRows_proj_risks > 0) {
																				$counter = 0;
																				while ($row_proj_risks = $query_proj_risks->fetch()) {
																					$counter++;
																					$rskid = $row_proj_risks['id'];
																					$category = $row_proj_risks['category'];
																					$risk = $row_proj_risks['risk_description'];
																					$riskleveldigit = $row_proj_risks['risk_level'];
																					
																					$query_risk_level = $db->prepare("SELECT * FROM tbl_risk_severity WHERE digit=:riskleveldigit");
																					$query_risk_level->execute(array(":riskleveldigit" => $riskleveldigit));
																					$row_risk_level = $query_risk_level->fetch();
																					$risklevel = $row_risk_level['description'];
																					$levelcolor = $row_risk_level['color'];
						
																					$query_risk_monitored = $db->prepare("SELECT * FROM tbl_project_risk_monitoring m left join tbl_risk_severity s on s.digit=m.risk_level WHERE m.riskid=:riskid ORDER BY m.id DESC Limit 1");
																					$query_risk_monitored->execute(array(":riskid" =>$rskid));
																					$row_risk_monitored = $query_risk_monitored->fetch();
																					$total_risk_monitored = $query_risk_monitored->rowCount();
							
																					if($total_risk_monitored > 0){
																						$risklevel = $row_risk_monitored["description"];
																						$levelcolor = $row_risk_monitored["color"];				
																					}
																														
																					$risk = string_length($risk, 100);

																					?>
																					<tr style="background-color:#FFFFFF">
																						<td align="center"><?= $counter ?></td>
																						<td><?= $risk ?></td>
																						<td><?= $category ?></td>
																						<td align="center" class="<?=$levelcolor?>"><?= $risklevel ?></td>
																						<td>
																							<div class="btn-group">
																								<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																									Options <span class="caret"></span>
																								</button>
																								<ul class="dropdown-menu">
																									<li>
																										<a type="button" data-toggle="modal" data-target="#riskperformanceModal" id="riskperformanceModalBtn" onclick="risk_performance(<?= $rskid ?>)">
																											<i class="fa fa-info"></i> More Info
																										</a>
																									</li>
																								</ul>
																							</div>
																						</td>
																					</tr>
																				<?php
																				}
																			}
																			?>
																		</tbody>
																	</table>
																</div>
															</div>
														</fieldset>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div id="menu2" class="tab-pane fade">
									<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">								
											<div class="table-responsive">
												<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
													<thead>
														<tr class="bg-red">
															<th style="width:4%">#</th>
															<th style="width:36%">Issue</th>
															<th style="width:10%">Category</th>
															<th style="width:10%">Impact</th>
															<th style="width:10%">Priority</th>
															<th style="width:10%">Status</th>
															<th style="width:10%">Date Recorded</th>
															<th style="width:10%">Action</th>
														</tr>
													</thead>
													<tbody>
														<?php
														if ($count_issues > 0) {
															$nm = 0;

															while ($row_issues = $query_issues->fetch()) {
																$nm = $nm + 1;
																$issueid = $row_issues["id"];
																$issueareaid = $row_issues["issue_area"];
																$category = $row_issues["category"];
																$issue = $row_issues["issue_description"];
																$impactid = $row_issues["issue_impact"];
																$priorityid = $row_issues["issue_priority"];
																$status_id = $row_issues["status"];
																$issuedate = $row_issues["date_created"];
																$status = get_inspection_status($status_id);

																$query_risk_impact =  $db->prepare("SELECT * FROM tbl_risk_impact WHERE id=:impactid");
																$query_risk_impact->execute(array(":impactid" => $impactid));
																$row_risk_impact = $query_risk_impact->fetch();
																$impact = $row_risk_impact["description"];
																
																if($priorityid == 1){
																	$priority = "High";
																	$priorityclass = 'bg-red';
																}elseif($priorityid == 2){
																	$priority = "Medium";
																	$priorityclass = 'bg-blue';
																}else{
																	$priority = "Low";
																	$priorityclass = 'bg-green';
																}
																
																if($issueareaid == 1){
																	$issue_area = "Quality";
																}elseif($issueareaid == 2){
																	$issue_area = "Scope";
																}elseif($issueareaid == 3){
																	$issue_area = "Schedule";
																}else{
																	$issue_area = "Cost";
																}

																$query_timeline =  $db->prepare("SELECT * FROM tbl_project_workflow_stage_timelines WHERE category = 'issue' and stage=1 and active=1");
																$query_timeline->execute();
																$row_timeline = $query_timeline->fetch();
																$timelineid = $row_timeline["id"];
																$time = $row_timeline["time"];
																$units = $row_timeline["units"];
																$stgstatus = $row_timeline["status"];

																$duedate = strtotime($issuedate . "+ " . $time . " " . $units);
																$actionnduedate = date("d M Y", $duedate);

																$current_date = date("Y-m-d");
																$actduedate = date("Y-m-d", $duedate);

																$styled = 'style="color:blue"';
																if ($status_id == 1 && $actduedate < $current_date) {
																	$actionstatus = "Behind Schedule";
																	$styled = 'style="color:red"';
																}
															?>
																<tr style="background-color:#fff">
																	<td width="4%" align="center"><?php echo $nm; ?></td>
																	<td width="36%"><?php echo $issue; ?></td>
																	<td width="10%"><?php echo $category; ?></td>
																	<td width="10%"><?php echo $impact; ?></td>
																	<td width="10%"><span class="badge <?= $priorityclass; ?>"><?php echo $priority; ?></span></td>
																	<td width="10%" <?= $styled ?>><?php echo $status; ?></td>
																	<td width="10%"><?php echo date("d M Y", strtotime($issuedate)); ?></td>
																	<td width="10%">
																		<div align="center" class="btn-group">
																			<a type="button" data-toggle="modal" data-target="#issueDetailsModal" id="issueDetailsModalBtn" onclick="issue_details(<?= $issueid ?>)" class="btn btn-default"><i class="fa fa-info-circle fa-2x text-success" aria-hidden="true"></i> More Info</a>
																		</div>
																	</td>
																</tr>
														<?php
															}
														}
														?>
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
		</div>
	</section>
	<!-- end body  -->
				
	<!-- Start Risk More -->
	<div class="modal fade" tabindex="-1" role="dialog" id="riskperformanceModal">
		<div class="modal-dialog  modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info-circle" style="color:orange"></i> Risk Monitoring Performance</h4>
				</div>
				<div class="modal-body">
					<fieldset class="scheduler-border" id="milestone_div">
						<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Risk Details </legend>
						<div class="row" id="risk_more_info">
							
						</div>
					</fieldset>
					<fieldset class="scheduler-border" id="milestone_div">
						<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Risk Level Performance</legend>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">								
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
									<thead>
										<tr class="bg-grey">
											<th style="width:25%">Date</th>
											<th style="width:25%">Likeliwood</th>
											<th style="width:25%">Impact</th>
											<th style="width:25%">Risk Level</th>
										</tr>
									</thead>
									<tbody id="risk_level_performance_table">
									</tbody>
								</table>
							</div>
						</div>
					</fieldset>
					<fieldset class="scheduler-border">
						<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Strategic Measures Performance</legend>
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover" id="measures_performance_table" style="width:100%">
								</table>
							</div>
						</div>
					</fieldset>
				</div>

				<div class="modal-footer">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
						<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Close</button>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>
	<!-- End Item more -->


	<!-- Modal Issue Escalation -->
	<div class="modal fade" id="issueDetailsModal" role="dialog">
		<div class="modal-dialog  modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-red">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center">
						<font color="#FFF">Issue Details</font>
					</h3>
				</div>
				<div class="modal-body">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div id="issue_details">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center">
						<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- #END# Modal Issue Escalation -->

<?php
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');
?>

<script>
	const ajax_url = "ajax/risk/index";
</script>

<script src="assets/js/risk/index.js"></script>

<script src="assets/js/issues/index.js"></script>