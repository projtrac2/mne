<?php
require('includes/head.php');
if ($permission) {

	try {
		if (isset($_GET['contrid'])) {
			$contrid_rsInfo = $_GET['contrid'];
		}

		$query_tenderedprojects = $db->prepare("SELECT p.projid, p.projcost, p.projname, t.tendertitle, t.tenderno, t.contractrefno,t.tenderamount, t.startdate, t.enddate, tt.type, tc.category FROM tbl_projects p INNER JOIN tbl_tenderdetails t  ON p.projtender=t.td_id INNER JOIN tbl_tender_category tc ON t.tendercat=tc.id INNER JOIN tbl_tender_type tt ON t.tendertype=tt.id WHERE p.projcategory = 2 AND p.deleted= '0' AND projtender IS NOT NULL Order BY p.projid ASC");
		$query_tenderedprojects->execute();
		$totalRows_tenderedprojects = $query_tenderedprojects->rowCount();

		$query_rsProjects = $db->prepare("SELECT g.progid, g.progname, g.projsector, p.projcode, p.projid, p.projname, p.projinspection, s.sector FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid inner join tbl_sectors s on s.stid=g.projdept WHERE p.deleted='0' and p.projstage=5");
		$query_rsProjects->execute();
		$row_rsProjects = $query_rsProjects->fetch();
		$totalRows_rsProjects = $query_rsProjects->rowCount();
	} catch (PDOException $ex) {
		$results = flashMessage("An error occurred: " . $ex->getMessage());
	}
?>
	<style>
		#links a {
			color: #FFFFFF;
			text-decoration: none;
		}

		hr {
			display: block;
			margin-top: 0.5em;
			margin-bottom: 0.5em;
			margin-left: auto;
			margin-right: auto;
			border-style: inset;
			border-width: 1px;
		}

		@media (min-width: 1200px) {
			.modal-lg {
				width: 90%;
			}
		}
	</style>

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
					<div class="card">
						<div class="body">
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover id=" manageItemTable">
									<thead>
										<tr style="background-color:#0b548f; color:#FFF">
											<th style="width:4%" align="center">#</th>
											<th style="width:12%">Project Code</th>
											<th style="width:50%">Project Name </th>
											<th style="width:25">Project Department</th>
											<th style="width:9%">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if ($totalRows_rsProjects > 0) {
											$counter = 0;
											do {
												$projid = $row_rsProjects['projid'];
												$progid = $row_rsProjects['progid'];
												$projsector = $row_rsProjects['projsector'];
												$department = $row_rsProjects['sector'];

												$query_projsector = $db->prepare("SELECT * FROM tbl_sectors WHERE stid = :sector");
												$query_projsector->execute(array(":sector" => $projsector));
												$row_projsector = $query_projsector->fetch();
												$sector = $row_projsector['sector'];

												$query_rsprojtenderdetails = $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid = :projid");
												$query_rsprojtenderdetails->execute(array(":projid" => $projid));
												$row_plan = $query_rsprojtenderdetails->fetch();
												$totalRows_rsprojtenderdetails = $query_rsprojtenderdetails->rowCount();

												$query_rsTender = $db->prepare("SELECT * FROM tbl_project_tender_details WHERE projid = :projid");
												$query_rsTender->execute(array(":projid" => $projid));
												$row_plan = $query_rsTender->fetch();
												$totalRows_Tender = $query_rsTender->rowCount();

												$counter++;
												$hash = base64_encode("encodefneditprj{$projid}");
												$hashproc = base64_encode("encodeprocprj{$projid}");
										?>
												<tr class="projects" style="background-color:#eff9ca">
													<td align="center"><?= $counter ?></td>
													<td><?php echo $row_rsProjects['projcode'] ?></td>
													<td><?php echo $row_rsProjects['projname'] ?></td>
													<td><?php echo $department ?></td>
													<td>
														<?php
														if ($totalRows_rsprojtenderdetails == 0) { ?>
															<div class="btn-group">
																<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																	Options <span class="caret"></span>
																</button>
																<ul class="dropdown-menu">
																	<li>
																		<a type="button" data-toggle="modal" data-target="#moreModal" id="moreModalBtn" onclick="more(<?php echo $row_rsProjects['projid'] ?>, 1)">
																			<i class="fa fa-file-text"></i> View Plan
																		</a>
																	</li>
																	<?php 
																	if ($add_procurement) {
																	?>
																		<li>
																			<a type="button" href="add-procurement-details?prj=<?= $hashproc ?>" id="addFormModalBtn">
																				<i class="fa fa-plus-square-o"></i> Add Procurement
																			</a>
																		</li>
																	<?php
																	}
																	?>
																</ul>
															</div>
														<?php
														} else {
														?>
															<div class="btn-group">
																<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																	Options <span class="caret"></span>
																</button>
																<ul class="dropdown-menu">
																	<li>
																		<a type="button" data-toggle="modal" data-target="#moreModal" id="moreModalBtn" onclick="more_procurement(<?php echo $row_rsProjects['projid'] ?>, 1)">
																			<i class="fa fa-file-text"></i> View Procurement
																		</a>
																	</li> 
																</ul>
															</div>
														<?php
														}
														?>
													</td>
												</tr>
											<?php
											} while ($row_rsProjects = $query_rsProjects->fetch());
										} else {
											?>
											<tr>
												<td colspan="7">No project requiring financial plan</td>
											</tr>
										<?php
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
	</section>
	<!-- end body  -->
	<!-- Modal Receive Payment -->
	<div class="modal fade" id="actModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center">
						<font color="#FFF">TENDER INFORMATION REQUEST(S)</font>
					</h3>
				</div>
				<form class="tagForm" action="paymentapproval" method="post" id="payment-approval-form" enctype="multipart/form-data" autocomplete="off">
					<div class="modal-body">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="card">
									<div class="body">
										<div class="table-responsive" style="background:#eaf0f9">
											<div id="actionformcontent">
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
	<!-- Modal -->
	<div class="modal fade" id="commModal" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title" align="center">
						<font color="#FFF">Funds/Payment Request Comments</font>
					</h3>
				</div>
				<div class="modal-body" id="commentcontent">

				</div>
				<div class="modal-footer">
					<div class="col-md-4">
					</div>
					<div class="col-md-4" align="center">
						<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
					</div>
					<div class="col-md-4">
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- #END# Modal -->
	<!-- Start Item more -->
	<div class="modal fade" tabindex="-1" role="dialog" id="moreModal">
		<div class="modal-dialog  modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info-circle"></i> More Information</h4>
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
	<!-- End Item more -->

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
	<!-- Start Item Delete -->

	<!-- End add item -->
<?php
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');
?>

<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>

<script language="javascript" type="text/javascript">
	$(document).ready(function() {
		$('#payment-approval-form').on('submit', function(event) {
			event.preventDefault();
			var form_data = $(this).serialize();
			$.ajax({
				type: "POST",
				url: "paymentapproval",
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

	$(document).ready(function() {
		$('#approvalstatus').on('change', function() {
			var actionID = $(this).val();
			var reqid = $("#requestid").val();

			$.ajax({
				type: 'post',
				url: 'callapprovalform',
				data: "actid=" + actionID + "&rqid=" + reqid,
				success: function(data) {
					$('#actionformcontent').html(data);
					$("#actModal").modal({
						backdrop: "static"
					});
				}
			});
		})
	})

	function CallRequestComments(id) {
		$.ajax({
			type: 'post',
			url: 'getreqcomments',
			data: {
				reqid: id
			},
			success: function(data) {
				$('#commentcontent').html(data);
				$("#commModal").modal({
					backdrop: "static"
				});
			}
		});
	}

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
</script>
<script src="general-settings/js/fetch-selected-project-financial-plan.js"></script>