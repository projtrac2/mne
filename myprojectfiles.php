<?php
$pageName = "Strategic Plans";
$replacement_array = array(
	'planlabel' => "CIDP",
	'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
$pageTitle = $planlabelplural;

if ($permission) {
	try {
		$projid = $_GET["projid"];
		$query_rsMyP = $db->prepare("SELECT *, FORMAT(projcost, 2), projstartdate AS sdate, projenddate AS edate, projcategory FROM tbl_projects WHERE user_name = :user AND projid = :projid");
		$query_rsMyP->execute(array(":user" => $user_name, ":projid" => $projid));
		$row_rsMyP = $query_rsMyP->fetch();
		$count_rsMyP = $query_rsMyP->rowCount();
		$projcategory = $row_rsMyP["projcategory"];


		if (isset($_GET['projcode'])) {
			$pcode_rsUpP = $_GET['projcode'];
		}

		if (isset($_GET['srccat'])) {
			$pcat_rsUpP = $_GET['srccat'];
		}

		if (isset($_GET['srcftype'])) {
			$ptype_rsUpP = $_GET['srcftype'];
		}

		if (isset($_GET['filedate'])) {
			$pdate_rsUpP = $_GET['filedate'];
		}

		if (isset($_GET['projid'])) {
			$projid = $_GET['projid'];
		}

		if (isset($_GET['btn_search'])) {
			$btn_search = $_GET['btn_search'];
		}

		if (isset($_GET["btn_search"])) {
			if (!empty($pcode_rsUpP) || !empty($pcat_rsUpP) || !empty($ptype_rsUpP) || !empty($pdate_rsUpP) || !empty($btn_search)) {
				$query_rsPFiles = $db->prepare("SELECT tbl_files.*, tbl_projects.projid, tbl_projects.projname FROM tbl_projects INNER JOIN tbl_files ON tbl_projects.projid=tbl_files.projid WHERE  tbl_projects.projid = '$projid' AND tbl_files.fcategory LIKE '%" . $pcat_rsUpP . "%' AND tbl_files.ftype LIKE '%" . $ptype_rsUpP . "%' AND tbl_projects.deleted = '0' ORDER BY tbl_projects.projid");
				$query_rsPFiles->execute();
			}
		} else {
			$query_rsPFiles = $db->prepare("SELECT f.*, p.projid, p.projname FROM tbl_projects p INNER JOIN tbl_files f ON p.projid=f.projid WHERE p.projid = '$projid' AND p.deleted = '0'");
			$query_rsPFiles->execute();
		}

		$row_rsPFiles = $query_rsPFiles->fetch();
		$totalRows_rsPFiles = $query_rsPFiles->rowCount();

		$query_rsFcat = $db->prepare("SELECT DISTINCT fcategory, fid FROM tbl_files WHERE fcategory IS NOT NULL ORDER BY fid ASC");
		$query_rsFcat->execute();
		$row_rsFcat = $query_rsFcat->fetch();
		$totalRows_rsFcat = $query_rsFcat->rowCount();

		$query_rsFType = $db->prepare("SELECT DISTINCT ftype, fid FROM tbl_files WHERE ftype IS NOT NULL ORDER BY fid ASC");
		$query_rsFType->execute();
		$row_rsFType = $query_rsFType->fetch();

		$query_rsUploadfile = $db->prepare("SELECT tbl_projects.projid, tbl_milestone.msid, tbl_task.tkid FROM tbl_projects INNER JOIN tbl_milestone ON tbl_projects.projid=tbl_milestone.projid  INNER JOIN tbl_task ON tbl_milestone.msid=tbl_task.msid WHERE tbl_projects.projid = '$colname_rsPFiles'");
		$query_rsUploadfile->execute();
		$row_rsUploadfile = $query_rsUploadfile->fetch();
		$totalRows_rsUploadfile = $query_rsUploadfile->rowCount();

		$currentStatus =  $row_rsMyP['projstatus'];

		$query_rsMlsProg =  $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid = :projid");
		$query_rsMlsProg->execute(array(":projid" => $projid));
		$row_rsMlsProg = $query_rsMlsProg->fetch();

		$prjprogress = $row_rsMlsProg["mlprogress"] / $row_rsMlsProg["nmb"];

		$percent2 = round($prjprogress, 2);
	} catch (PDOException $ex) {
		$result = flashMessage("An error occurred: " . $ex->getMessage());
		echo $result;
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
					<div class="header button-demo" style="padding-bottom:0px">
						<a href="myprojectdash.php?projid=<?php echo $projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; padding-left:-10px">Details</a>
						<a href="myprojectmilestones.php?projid=<?php echo $projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Activities</a>
						<a href="myprojectworkplan.php?projid=<?php echo $projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Quarterly Targets</a>
						<a href="myprojectfinancialplan.php?projid=<?php echo $projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Financial Plan</a>
						<a href="myproject-key-stakeholders.php?projid=<?php echo $projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Key Stakeholders</a>
						<a href="projectissueslist.php?proj=<?php echo $projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Issues Log</a>
						<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-10px">Files</a>
						<a href="projreports.php?projid=<?php echo $projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Progress Report</a>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<h4>
							<div class="col-md-8" style="font-size:15px; background-color:#CDDC39; border:#CDDC39 thin solid; border-radius:5px; margin-bottom:2px; height:25px; padding-top:2px; vertical-align:center">
								Project Name: <font color="white"><?php echo $row_rsMyP['projname']; ?></font>
							</div>
							<div class="col-md-4" style="font-size:15px; background-color:#CDDC39; border-radius:5px; height:25px; margin-bottom:2px">
								<div class="barBg" style="margin-top:0px; width:100%; border-radius:1px">
									<div class="bar hundred cornflowerblue">
										<div id="label" class="barFill" style="margin-top:0px; border-radius:1px"><?php echo $percent2 ?>%</div>
									</div>
								</div>
							</div>
						</h4>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="body">
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="">
									<thead>
										<tr id="colrow">
											<th width="3%"><strong>#</strong></th>
											<th width="32%"><strong>File Name</strong></th>
											<th width="10%"><strong>File Type</strong></th>
											<th width="15%"><strong>File Category</strong></th>
											<th width="20%"><strong>Purpose</strong></th>
											<th width="12%"><strong>File Date</strong></th>
											<th width="8%"><strong><i class="fa fa-file" aria-hidden="true"></i> Download</strong></th>
										</tr>
									</thead>
									<tbody>
										<?php
										if ($totalRows_rsPFiles > 0) {
											$nm = 0;
											do {
												$nm = $nm + 1;
												$flupdate = strtotime($row_rsPFiles['date_uploaded']);
												$fileuploaddate = date("d M Y", $flupdate);
										?>
												<tr>
													<td><?php echo $nm; ?></td>
													<td><?php echo $row_rsPFiles['filename']; ?></td>
													<td><?php echo $row_rsPFiles['ftype']; ?></td>
													<td><?php echo $row_rsPFiles['fcategory']; ?></td>
													<td><?php echo $row_rsPFiles['reason']; ?></td>
													<td><?php echo $fileuploaddate; ?></td>
													<td align="center">
														<a href="<?php echo $row_rsPFiles['floc']; ?>" style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" title="Download File" target="new"><i class="fa fa-cloud-download fa-2x" aria-hidden="true"></i></a>
													</td>
												</tr>
										<?php
											} while ($row_rsPFiles = $query_rsPFiles->fetch());
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
<?php
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');
?>