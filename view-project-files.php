<?php
$replacement_array = array(
	'planlabel' => "CIDP",
	'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');

if ($permission) {
	$pageTitle = "All Project Files";
	try {
		$query_rsPFiles = $db->prepare("SELECT f.*, p.projid, p.projname, f.date_uploaded AS ufdate FROM tbl_projects p INNER JOIN tbl_files f ON p.projid=f.projid WHERE p.deleted = '0' ORDER BY f.fcategory, f.ftype");
		$query_rsPFiles->execute();
		$row_rsPFiles = $query_rsPFiles->fetch();
		$totalRows_rsPFiles = $query_rsPFiles->rowCount();
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
							<!-- start body -->
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
									<thead>
										<tr id="colrow">
											<td width="3%"><strong>#</strong></td>
											<td width="20%"><strong>File Name</strong></td>
											<td width="8%"><strong>File Type</strong></td>
											<td width="8%"><strong>File Category</strong></td>
											<td width="26%"><strong>Project</strong></td>
											<td width="17%"><strong>Purpose</strong></td>
											<td width="10%"><strong>File Date</strong></td>
											<td width="8%"><strong><i class="fa fa-file" aria-hidden="true"></i> Download</strong></td>
										</tr>
									</thead>
									<tbody>
										<?php
										if ($totalRows_rsPFiles > 0) {

											$nm = 0;
											do {
												$nm++;
												$flupdate = strtotime($row_rsPFiles['ufdate']);
												$fileuploaddate = date("d M Y", $flupdate);
										?>
												<tr>
													<td><?php echo $nm; ?></td>
													<td><?php echo $row_rsPFiles['filename']; ?></td>
													<td><?php echo $row_rsPFiles['ftype']; ?></td>
													<td><?php echo $row_rsPFiles['fcategory']; ?></td>
													<td><?php echo $row_rsPFiles['projname']; ?></td>
													<td><?php echo $row_rsPFiles['reason']; ?></td>
													<td><?php echo $fileuploaddate; ?></td>
													<td align="center"><a href="<?php echo $row_rsPFiles['floc']; ?>" style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" title="Download File" target="new"><i class="fa fa-cloud-download fa-2x" aria-hidden="true"></i></a></td>
												</tr>
											<?php
											} while ($row_rsPFiles = $query_rsPFiles->fetch()); ?>
										<?php
										}
										?>
									</tbody>
								</table>
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