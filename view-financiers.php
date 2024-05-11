<?php
try {
	include 'includes/head.php';
	if ($permission) {
		$query_owner = $db->prepare("SELECT *, f.id as fnid, t.type as ftype, t.id as fid FROM tbl_financiers f inner join tbl_funding_type t ON t.id=f.type WHERE t.id=1 OR t.id=2 ORDER BY f.id ASC");
		$query_owner->execute();
		$totalRows_owner = $query_owner->rowCount();

		$query_rsfinancier = $db->prepare("SELECT *, f.id as fnid, t.type as ftype, t.id as fid FROM tbl_financiers f inner join tbl_funding_type t ON t.id=f.type WHERE t.id <> 1 AND t.id <> 2 ORDER BY f.id ASC");
		$query_rsfinancier->execute();
		$totalRows_rsfinancier = $query_rsfinancier->rowCount();

?>
		<!-- start body  -->
		<section class="content">
			<div class="container-fluid">
				<div class="row clearfix">
					<div class="col-md-12">
						<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
							<h4 class="contentheader">
								<?= $icon ?>
								<?= $pageTitle ?>
							</h4>
						</div>
					</div>
				</div>
				<div class="row clearfix">
					<div class="block-header">
						<div class="col-md-12">
							<div class="header" style="padding-bottom:0px">
								<div class="button-demo" style="margin-top:-15px">
									<span class="label bg-black" style="font-size:18px">
										<img src="assets/images/proj-icon.png" alt="Project" title="Project" style="vertical-align:middle; height:25px" /> Menu
									</span>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:4px"><i class="fa fa-university"></i> &nbsp; &nbsp; Financiers</a>
									<a href="view-funding.php" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px"><i class="fa fa-money"></i> &nbsp; &nbsp; Funding</a>
								</div>
							</div>
						</div>
						<?= $results; ?>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="card">
							<div class="card-header">
								<ul class="nav nav-tabs" style="font-size:14px">
									<li class="active">
										<a data-toggle="tab" href="#owner"><i class="fa fa-user bg-brown" aria-hidden="true"></i> OWNER &nbsp;<span class="badge bg-brown">|</span></a>
									</li>
									<li>
										<a data-toggle="tab" href="#partners"><i class="fa fa-users bg-deep-orange" aria-hidden="true"></i> PARTNERS &nbsp;<span class="badge bg-deep-orange"><?= $totalRows_rsfinancier ?></span></a>
									</li>
								</ul>
							</div>
							<div class="body">
								<div class="tab-content">
									<div id="owner" class="tab-pane fade in active">
										<!-- start body -->
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
												<thead>
													<tr id="colrow">
														<th width="3%"><strong>#</strong></th>
														<th width="27%"><strong>Financier</strong></th>
														<th width="15%"><strong>Contact</strong></th>
														<th width="9%"><strong>Phone</strong></th>
														<th width="10%"><strong>Projects</strong></th>
														<th width="15%"><strong>Total Amt (Ksh)</strong></th>
														<th width="11%"><strong>Status</strong></th>
														<th width="10%"><strong>Action</strong></th>
													</tr>
												</thead>
												<tbody>
													<!-- =========================================== -->
													<?php
													if ($totalRows_rsfinancier > 0) {
														$sn = 0;
														while ($row_owner = $query_owner->fetch()) {
															$sn++;
															$country = $row_owner['country'];
															$finstatus = $row_owner['active'];
															$fnid = $row_owner['fnid'];
															$sourcecat = $row_owner['fid'];
															$hashfnid = base64_encode("fn918273AxZID{$fnid}");

															$success = "Successfully enabled financier ";
															$status_text = " Are you sure you want to enable financier";
															$update_status = 1;
															if ($finstatus == 1) {
																$status_text = " Are you sure you want to disable financier";
																$success = "Successfully disabled financier ";
																$update_status = 0;
															}

															$query_financierprojs = $db->prepare("SELECT p.projid FROM tbl_projects p inner join tbl_myprojfunding m on p.projid=m.projid WHERE p.deleted='0' and m.sourcecategory=:sourcecat and m.financier = :fnid GROUP BY p.projid ORDER BY m.id ASC");
															$query_financierprojs->execute(array(":sourcecat" => $sourcecat, ":fnid" => $fnid));
															$row_financierprojs = $query_financierprojs->rowCount();

															$query_totalfunds = $db->prepare("SELECT * FROM tbl_funds WHERE funder = :fnid");
															$query_totalfunds->execute(array(":fnid" => $fnid));
															$tdn = 0;
															while ($ttamt = $query_totalfunds->fetch()) {
																$amnt = $ttamt["amount"] * $ttamt["exchange_rate"];
																$tdn = $tdn + $amnt;
															}

															if ($row_owner['active'] == 1) {
																$active = '<i class="fa fa-check-square" style="font-size:18px;color:green" title="Active"></i>';
															} else {
																$active = '<i class="fa fa-exclamation-triangle" style="font-size:18px;color:#bc2d10" title="Disabled"></i>';
															}
													?>
															<tr style="border-bottom:thin solid #EEE">
																<td><?php echo $sn; ?></td>
																<td><?php echo $row_owner['financier']; ?></td>
																<td><?php echo $row_owner['contact']; ?> (<?php echo $row_owner['designation']; ?>)</td>
																<td><a href="tel:<?php echo $row_owner['phone']; ?>"><?php echo $row_owner['phone']; ?></a></td>
																<td align="center">
																	<span class="badge bg-brown">
																		<a href="view-financier-projects.php?fndid=<?php echo base64_encode($fnid); ?>" style="font-family:Verdana, Geneva, sans-serif; color:white; font-size:12px; padding-top:0px">
																			<?php echo $row_financierprojs; ?>
																		</a>
																</td>
																<td><?php echo number_format($tdn, 2); ?></td>
																<td align="center"><?= $active ?></td>
																<td>
																	<div class="btn-group">
																		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" onchange="checkBoxes()" aria-haspopup="true" aria-expanded="false">
																			Options <span class="caret"></span>
																		</button>
																		<ul class="dropdown-menu">
																			<li>
																				<a type="button" href="view-financier-info.php?fn=<?php echo $hashfnid; ?>"><i class="fa fa-plus-square"></i> More Info</a>
																			</li>
																			<li>
																				<a type="button" onclick="update_financier_status(<?= $fnid ?>, '<?= $status_text ?>', '<?= $success ?>', <?= $update_status ?>)"><i class="fa fa-plus-square"></i> <?= $finstatus == 0 ? "Activate" : "Deactivate" ?></a>
																			</li>
																			<?php
																			if (in_array("create", $page_actions) && $finstatus) {
																			?>
																				<li>
																					<a type="button" href="add-development-funds.php?fn=<?php echo $hashfnid; ?>">
																						<i class="fa fa-money"></i> Add Funds </a>
																				</li>
																			<?php
																			}
																			if (in_array("update", $page_actions)) {
																			?>
																				<li>
																					<a type="button" href="edit-financier.php?fn=<?php echo $hashfnid; ?>">
																						<i class="glyphicon glyphicon-edit"></i> Edit </a>
																				</li>
																			<?php
																			}
																			?>
																			<li>
																				<a type="button" href="funding-report.php?fn=<?= $hashfnid ?>">
																					<i class="fa fa-info"></i> Report</a>
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

									<div id="partners" class="tab-pane fade">
										<div class="header">
											<div class="clearfix" style="margin-top:5px; margin-bottom:5px">
												<div class="btn-group" style="float:right">
													<?php
													if (in_array("create", $page_actions)) {
													?>
														<a href="add-financier.php" class="btn btn-primary pull-right">Add Partner</a>
													<?php
													}
													?>
												</div>
											</div>
										</div>
										<div class="body">
											<!--<div class="row clearfix">-->
											<div class="table-responsive">
												<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
													<thead>
														<tr id="colrow">
															<th width="3%"><strong>#</strong></th>
															<th width="27%"><strong>Financier</strong></th>
															<th width="8%"><strong>Type</strong></th>
															<th width="15%"><strong>Contact</strong></th>
															<th width="9%"><strong>Phone</strong></th>
															<th width="8%"><strong>Projects</strong></th>
															<th width="15%"><strong>Total Amt (Ksh)</strong></th>
															<th width="5%"><strong>Status</strong></th>
															<th width="10%"><strong>Action</strong></th>
														</tr>
													</thead>
													<tbody>
														<!-- =========================================== -->
														<?php
														if ($totalRows_rsfinancier > 0) {
															$sn = 0;
															while ($row_rsfinancier = $query_rsfinancier->fetch()) {
																$sn++;
																$country = $row_rsfinancier['country'];
																$finstatus = $row_rsfinancier['active'];
																$fnid = $row_rsfinancier['fnid'];
																$sourcecat = $row_rsfinancier['fid'];
																$hashfnid = base64_encode("fn918273AxZID{$fnid}");

																$success = "Successfully enabled financier ";
																$status_text = " Are you sure you want to enable financier";
																$update_status = 1;
																if ($finstatus == 1) {
																	$status_text = " Are you sure you want to disable financier";
																	$success = "Successfully disabled financier ";
																	$update_status = 0;
																}

																$query_financierprojs = $db->prepare("SELECT p.* FROM tbl_projects p inner join tbl_myprojfunding m on p.projid=m.projid WHERE p.deleted='0' and m.financier = :fnid GROUP BY p.projid ORDER BY m.id ASC");
																$query_financierprojs->execute(array(":fnid" => $fnid));
																$row_financierprojs = $query_financierprojs->rowCount();

																$query_totalfunds = $db->prepare("SELECT * FROM tbl_funds WHERE funder = :fnid");
																$query_totalfunds->execute(array(":fnid" => $fnid));
																$tdn = 0;
																while ($ttamt = $query_totalfunds->fetch()) {
																	$amnt = $ttamt["amount"] * $ttamt["exchange_rate"];
																	$tdn = $tdn + $amnt;
																}

																if ($row_rsfinancier['active'] == 1) {
																	$active = '<i class="fa fa-check-square" style="font-size:18px;color:green" title="Active"></i>';
																} else {
																	$active = '<i class="fa fa-exclamation-triangle" style="font-size:18px;color:#bc2d10" title="Disabled"></i>';
																}
														?>
																<tr style="border-bottom:thin solid #EEE">
																	<td><?php echo $sn; ?></td>
																	<td><?php echo $row_rsfinancier['financier']; ?></td>
																	<td><?php echo $row_rsfinancier['ftype']; ?></td>
																	<td><?php echo $row_rsfinancier['contact']; ?> (<?php echo $row_rsfinancier['designation']; ?>)</td>
																	<td><a href="tel:<?php echo $row_rsfinancier['phone']; ?>"><?php echo $row_rsfinancier['phone']; ?></a></td>
																	<td align="center">
																		<span class="badge bg-brown">
																			<a href="view-financier-projects.php?fndid=<?php echo base64_encode($fnid); ?>" style="font-family:Verdana, Geneva, sans-serif; color:white; font-size:12px; padding-top:0px">
																				<?php echo $row_financierprojs; ?>
																			</a>
																		</span>
																	</td>
																	<td><?php echo number_format($tdn, 2); ?></td>
																	<td align="center"><?= $active ?></td>
																	<td>
																		<div class="btn-group">
																			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" onchange="checkBoxes()" aria-haspopup="true" aria-expanded="false">
																				Options <span class="caret"></span>
																			</button>
																			<ul class="dropdown-menu">
																				<li>
																					<a type="button" href="view-financier-info.php?fn=<?php echo $hashfnid; ?>"><i class="fa fa-plus-square"></i> More Info</a>
																				</li>
																				<li>
																					<a type="button" onclick="update_financier_status(<?= $fnid ?>, '<?= $status_text ?>', '<?= $success ?>', <?= $update_status ?>)"><i class="fa fa-plus-square"></i> Manage</a>
																				</li>
																				<?php
																				if ($finstatus == 1) {
																					if (in_array("create", $page_actions) && $finstatus) {
																				?>
																						<li>
																							<a type="button" href="add-development-funds.php?fn=<?php echo $hashfnid; ?>">
																								<i class="fa fa-money"></i> Add Funds </a>
																						</li>
																					<?php
																					}
																					if (in_array("create", $page_actions)) {
																					?>
																						<li>
																							<a type="button" href="edit-financier.php?fn=<?php echo $hashfnid; ?>">
																								<i class="glyphicon glyphicon-edit"></i> Edit </a>
																						</li>
																				<?php
																					}
																				}
																				?>
																				<li>
																					<a type="button" href="funding-report.php?fn=<?= $hashfnid ?>" target="_blank">
																						<i class="fa fa-info"></i> Report
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
									</div>
								</div>
								<!-- end body -->
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
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>

<script>
	function update_financier_status(financier_id, status_text, success, status) {
		swal({
				title: "Are you sure?",
				text: status_text,
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					$.ajax({
						type: "post",
						url: "ajax/partners/financial",
						data: {
							update_status: 'update_status',
							financier_id: financier_id,
							status: status,
						},
						dataType: "json",
						success: function(response) {
							if (response.success == true) {
								swal({
									title: "Financier !",
									text: success,
									icon: "success",
								});
							} else {
								swal({
									title: "Financier !",
									text: "Error changing status",
									icon: "error",
								});
							}
							setTimeout(function() {
								window.location.reload(true);
							}, 3000);
						},
					});
				} else {
					swal("You cancelled the action!");
				}
			});
	}
</script>