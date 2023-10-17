<?php
$decode_projid = (isset($_GET['proj']) && !empty($_GET["proj"])) ? base64_decode($_GET['proj']) : "";
$projid_array = explode("projid54321", $decode_projid);
$projid = $projid_array[1];
$original_projid = $_GET['proj'];

require('includes/head.php');
include_once('projects-functions.php');
if ($permission) {
	try {
		$query_rsMyP =  $db->prepare("SELECT *, projcost, projstartdate AS sdate, projenddate AS edate, projcategory, progress FROM tbl_projects WHERE deleted='0' AND projid = '$projid'");
		$query_rsMyP->execute();
		$row_rsMyP = $query_rsMyP->fetch();
		$projname = $row_rsMyP['projname'];
		$projstage = $row_rsMyP["projstage"];
		$projcat = $row_rsMyP["projcategory"];
		$percent2 = number_format($row_rsMyP['progress'], 2);

		$query_rsOutputs = $db->prepare("SELECT p.output as  output, o.id as opid, p.indicator, o.budget as budget, o.total_target FROM tbl_project_details o INNER JOIN tbl_progdetails p ON p.id = o.outputid WHERE projid = :projid");
		$query_rsOutputs->execute(array(":projid" => $projid));
		$row_rsOutputs = $query_rsOutputs->fetch();
		$totalRows_rsOutputs = $query_rsOutputs->rowCount();

		// $percent2 = get_project_percentage($projid);


		function get_task_compliance($state_id, $site_id, $task_id)
		{
			global $db;
			$compliance = [];
			$query_rsSpecifions = $db->prepare("SELECT * FROM tbl_project_specifications WHERE task_id=:task_id");
			$query_rsSpecifions->execute(array(":task_id" => $task_id));
			$totalRows_rsSpecifions = $query_rsSpecifions->rowCount();
			if ($totalRows_rsSpecifions > 0) {
				while ($row_rsSpecifions = $query_rsSpecifions->fetch()) {
					$specification_id = $row_rsSpecifions['id'];
					$query_rsCompliance = $db->prepare("SELECT * FROM tbl_project_inspection_specification_compliance WHERE state_id=:state_id AND site_id=:site_id AND specification_id=:specification_id  ORDER BY id DESC LIMIT 1");
					$query_rsCompliance->execute(array(":state_id" => $state_id, ":site_id" => $site_id, ":specification_id" => $specification_id));
					$Rows_rsCompliance = $query_rsCompliance->fetch();
					$totalRows_rsCompliance = $query_rsCompliance->rowCount();
					$compliance[] = ($totalRows_rsCompliance > 0) ? $Rows_rsCompliance['compliance'] : 0;
				}
			}

			$task_compliance = "";
			if (in_array(1, $compliance)) {
				$task_compliance = "Compliant";
			} else if (in_array(2, $compliance)) {
				$task_compliance = "Non-Compliant";
			}else if (in_array(2, $compliance)) {
				$task_compliance = "On-Track";
			}
			return $task_compliance;
		}
	} catch (PDOException $ex) {
		$result = flashMessage("An error occurred: " . $ex->getMessage());
		echo $result;
	}
?>
	<link href="projtrac-dashboard/plugins/nestable/jquery-nestable.css" rel="stylesheet" />
	<link rel="stylesheet" href="assets/css/strategicplan/view-strategic-plan-framework.css">
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<?= $icon ?>
					<?= $pageTitle ?>
					
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
								<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; width:100px">Finance</a>
								<a href="project-timeline.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Timeline</a>
								<?php if($projcat == 2 && $projstage > 4){ ?>
									<a href="project-contract-details.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Contract</a>
								<?php } ?>
								<a href="project-team-members.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Team</a>
								<a href="project-issues.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Issues</a>
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
									<a data-toggle="tab" href="#home"><i class="fa fa-caret-square-o-down bg-orange" aria-hidden="true"></i> Funding &nbsp;<span class="badge bg-orange">|</span></a>
								</li>
								<li>
									<a data-toggle="tab" href="#menu1"><i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> Payment &nbsp;<span class="badge bg-blue">|</span></a>
								</li>
							</ul>
						</div>
						<div class="body">
							<div class="tab-content">
								<div id="home" class="tab-pane fade in active">
									<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<?php
											// query the
											$query_rsProjFinancier =  $db->prepare("SELECT *, f.financier FROM tbl_myprojfunding m inner join tbl_financiers f ON f.id=m.financier WHERE projid = :projid ORDER BY amountfunding desc");
											$query_rsProjFinancier->execute(array(":projid" => $projid));
											$row_rsProjFinancier = $query_rsProjFinancier->fetch();
											$totalRows_rsProjFinancier = $query_rsProjFinancier->rowCount();
											?>
											
											<fieldset class="scheduler-border" style="border-radius:3px">
												<legend class="scheduler-border" style="background-color:orange; border-radius:3px">
												   <i class="fa fa-university" aria-hidden="true"></i> Funding Details
												</legend>
												<div class="table-responsive">
												   <table class="table table-bordered table-striped table-hover" id="" style="width:100%">
													  <thead>
														 <tr>
															<th width="4%">#</th>
															<th width="80%">Financier</th>
															<th width="16%" align="right">Amount (Ksh)</th>
														 </tr>
													  </thead>
													  <tbody id="">
														 <tr></tr>
														 <?php
														 $rowno = 0;
														 $totalAmount = 0;
														 if ($totalRows_rsProjFinancier > 0) {
															do {
															   $rowno++;
															   $sourcat =  $row_rsProjFinancier['sourcecategory'];
															   $source = $row_rsProjFinancier['id'];
															   $financier = $row_rsProjFinancier['financier'];
															   $projamountfunding =  $row_rsProjFinancier['amountfunding'];
															   $totalAmount = $projamountfunding + $totalAmount;
															   $inputs = '';
															   $inputs .= '<span>' . $financier . '</span>';
																?>
																<tr id="row<?= $rowno ?>">
																  <td>
																	 <?= $rowno ?>
																  </td>
																  <td>
																	 <?php echo $inputs ?>
																  </td>
																  <td align="left">
																	 <?php echo number_format($projamountfunding, 2); ?>
																  </td>
															   </tr>
															<?php
															} while ($row_rsProjFinancier = $query_rsProjFinancier->fetch());
														 } else {
															?>
															<tr>
															   <td colspan="5">No Financier Found</td>
															</tr>
														 <?php
														 }
														 ?>
													  </tbody>
													  <tfoot>
														 <tr>
															<td colspan="2"><strong>Total Amount</strong></td>
															<td align="left"><strong><?= number_format($totalAmount, 2) ?></strong></td>
														 </tr>
													  </tfoot>
												   </table>
												</div>
											 </fieldset>
										</div>
									</div>
								</div>
								<div id="menu1" class="tab-pane fade">
									<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<fieldset class="scheduler-border row setup-content" id="step-2">
												<legend class="scheduler-border" style="background-color:#079BF5; color:#fff; border-radius:3px"><i class="fa fa-credit-card" aria-hidden="true"></i> Payment Details</legend>
												<div class="table-responsive">
													<table class="table table-bordered table-striped table-hover">
														<thead>
															<tr>
																<th style="width:5%" align="center">#</th>
																<th style="width:15%">Receipt No.</th>
																<th style="width:20%">Amount</th>
																<th style="width:15%">Date Paid</th>
																<th style="width:30%">Paid By</th>
																<th style="width:15%">Action</th>
															</tr>
														</thead>
														<tbody>
															<?php
															$query_payment_requests = $db->prepare("SELECT r.requested_amount,d.receipt_no, d.request_id, d.receipt, d.created_by, d.date_paid FROM tbl_contractor_payment_requests r INNER JOIN tbl_payments_disbursed d ON d.request_id = r.request_id WHERE r.projid=:projid AND r.status = 3 GROUP BY r.request_id");
															$query_payment_requests->execute(array(":projid" => $projid));
															
															$counter = $totalPaid = 0;
															
															while ($rows_payment_requests = $query_payment_requests->fetch()){
																$receipt_no = $rows_payment_requests['receipt_no'];
																$costline_id = $rows_payment_requests['request_id'];
																$date_paid = $rows_payment_requests['date_paid'];
																$created_by = $rows_payment_requests['created_by'];
																$amount_paid = $rows_payment_requests['requested_amount'] != null ? $rows_payment_requests['requested_amount'] : 0;
																$receipt = $rows_payment_requests['receipt'];

																$get_user = $db->prepare("SELECT *, tt.title As ttle FROM tbl_projteam2 t INNER JOIN users u ON u.pt_id = t.ptid inner join tbl_titles tt on tt.id=t.title WHERE u.userid=:user_id");
																$get_user->execute(array(":user_id" => $created_by));
																$user = $get_user->fetch();
																$officer = $user['ttle'].".".$user['fullname'];
																$totalPaid = $totalPaid + $amount_paid;
																$counter++;
																?>
																<tr class="">
																	<td style="width:5%"><?= $counter ?></td>
																	<td style="width:15%"><?= $receipt_no ?></td>
																	<td style="width:20%"><?= number_format($amount_paid, 2) ?></td>
																	<td style="width:15%"><?= date("Y-m-d", strtotime($date_paid)) ?></td>
																	<td style="width:30%"><?= $officer ?></td>
																	<td style="width:15%">
																		<div class="btn-group">
																			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																				Options <span class="caret"></span>
																			</button>
																			<ul class="dropdown-menu">
																				<li>
																					<a type="button" data-toggle="modal" id="moreItemModalBtn" data-target="#moreItemModal" onclick="get_more_info(<?= $costline_id ?>)">
																						<i class="fa fa-info"></i>More Info
																					</a>
																				</li>
																				<li>
																					<a type="button" href="<?= $receipt ?>" title="Download receipt" download>
																						<i class="fa fa-info"></i> View Receipt
																					</a>
																				</li>
																			</ul>
																		</div>
																	</td>
																</tr>
															<?php
															}
															?>
														</tbody>
														<tfoot>
															<tr>
																<td colspan="4"></td>
																<td><strong>Total Amount</strong></td>
																<td align="left"><strong><?= number_format($totalPaid, 2) ?></strong></td>
															</tr>
														</tfoot>
													</table>
												</div>
											</fieldset>
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
?>