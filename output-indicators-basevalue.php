<?php
try {
	$pageName = "Output Indicator Base Values";
	require('includes/head.php');
	require('includes/header.php');

	$query_rsAllIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_type=2 AND indicator_category='Output' AND active = '1' ORDER BY indid");
	$query_rsAllIndicators->execute();
	$row_rsAllIndicators = $query_rsAllIndicators->fetch();
	$totalRows_rsAllIndicators = $query_rsAllIndicators->rowCount();


	$query_rsSector = $db->prepare("SELECT stid, sector FROM  tbl_sectors WHERE parent = '0' AND deleted = '0' ORDER BY stid ASC");
	$query_rsSector->execute();
	$row_rsSector = $query_rsSector->fetch();

	$query_rsDept = $db->prepare("SELECT stid, sector FROM  tbl_sectors WHERE parent != '0' AND deleted = '0' ORDER BY stid ASC");
	$query_rsDept->execute();
	$row_rsDept = $query_rsDept->fetch();

	$query_rsCat = $db->prepare("SELECT category FROM  tbl_indicator_categories WHERE active = '1' ORDER BY catid ASC");
	$query_rsCat->execute();
	$row_rsCat = $query_rsCat->fetch();



?>
	<div class="body">
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
				<thead>
					<tr id="colrow">
						<td width="3%"><strong id="colhead">#</strong></td>
						<td width="5%"><strong id="colhead">Code</strong></td>
						<td width="45%"><strong id="colhead">Indicator</strong></td>
						<td width="28%"><strong id="colhead"><?= $departmentlabel ?></strong></td>
						<td width="7%"><strong id="colhead">Action</strong></td>
					</tr>
				</thead>
				<tbody><?php
						if ($totalRows_rsAllIndicators == 0) {
						?>
						<tr>
							<td colspan="9">
								<div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div>
							</td>
						</tr>
						<?php } else {
							$num = 0;
							do {
								$indid = $row_rsAllIndicators['indid'];
								$inddept = $row_rsAllIndicators['indicator_dept'];
								$basevalue = $row_rsAllIndicators['baseline'];
								$num = $num + 1;

								$query_rsIndDept = $db->prepare("SELECT * FROM tbl_sectors WHERE stid = '$inddept'");
								$query_rsIndDept->execute();
								$row_rsIndDept = $query_rsIndDept->fetch();
								$totalRows_rsIndDept = $query_rsIndDept->rowCount();
								$dept = $row_rsIndDept['sector'];
								$indid = base64_encode($indid);
						?>
							<tr id="rowlines">
								<td><?php echo $num; ?></td>
								<td><?php echo $row_rsAllIndicators['indicator_code']; ?></td>
								<td><?php echo $row_rsAllIndicators['indicator_name']; ?></td>
								<td><?php echo $dept; ?></td>
								<td>
									<div class="btn-group">
										<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Options <span class="caret"></span>
										</button>
										<ul class="dropdown-menu">
											<li>
												<a type="button" data-toggle="modal" data-target="#moreModal" id="moreModalBtn" onclick=more("<?php echo $indid ?>")>
													<i class="fa fa-file-text"></i> More Info
												</a>
											</li>
											<?php if ($basevalue == 0) { ?>
												<li>
													<a type="button" href="indicator-existing-baseline-data?ind=<?= $indid ?>">
														<i class="fa fa-pencil-square"></i> </i> Add Basevalue
													</a>
												</li>
											<?php } else { ?>
												<li>
													<a type="button" href="indicator-existing-baseline-data?ind=<?= $indid ?>&view=1">
														<i class="fa fa-pencil-square"></i> </i> View Basevalue
													</a>
												</li>
											<?php } ?>
										</ul>
									</div>
								</td>
							</tr>
					<?php
							} while ($row_rsAllIndicators = $query_rsAllIndicators->fetch());
						}
					?>
				</tbody>
			</table>
		</div>
	</div>

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
					<h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i> Delete Indicator</h4>
				</div>
				<div class="modal-body">
					<div class="removeItemMessages"></div>
					<p align="center">Are you sure you want to delete this indicator?</p>
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
	<script src="assets/custom js/fetch-selected-indicators.js"></script>
<?php
	require('includes/footer.php');
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>