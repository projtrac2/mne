<?php
$decode_projid = (isset($_GET['proj']) && !empty($_GET["proj"])) ? base64_decode($_GET['proj']) : "";
$projid_array = explode("projid54321", $decode_projid);
$projid = $projid_array[1];
$original_projid = $_GET['proj'];

require('includes/head.php');
include_once('projects-functions.php');
if ($permission) {
	try {
		$query_rsMyP =  $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' AND projid = '$projid'");
		$query_rsMyP->execute();
		$row_rsMyP = $query_rsMyP->fetch();
		$implementation_type = $row_rsMyP["projcategory"];
		$projname = $row_rsMyP['projname'];
		$projcode = $row_rsMyP['projcode'];
		$projcost = $row_rsMyP['projcost'];
		$projwards = explode(",", $row_rsMyP['projlga']);
		$projfscyear = $row_rsMyP['projfscyear'];
		$projduration = $row_rsMyP['projduration'];
        $implimentation_type = $row_rsMyP['projcategory'];
		$percent2 = number_format(calculate_project_progress($projid, $implimentation_type),2);

		$locations = [];
		foreach ($projwards as $projward) {
			$query_projward = $db->prepare("SELECT parent, state FROM tbl_state WHERE id=:projward");
			$query_projward->execute(array(":projward" => $projward));
			$row_projward = $query_projward->fetch();
			$wards = $row_projward['state'];
			$parent = $row_projward['parent'];

			$query_projsb = $db->prepare("SELECT state FROM tbl_state WHERE id=:parent");
			$query_projsb->execute(array(":parent" => $parent));
			$row_projsb = $query_projsb->fetch();
			$subcounty = $wards == "Headquarters" ? "" : $row_projsb['state']." Sub-County";

			$location = '<span data-container="body" data-toggle="tooltip" data-html="true" data-placement="bottom" title="' . $subcounty . '" style="color:#2196F3">' . $wards . '</span>';

			$locations[] = $location;
		}
		$projlocations = implode("; ", $locations);


		$query_rsTender = $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid=:projid");
		$query_rsTender->execute(array(":projid" => $projid));
		$row_rsTender = $query_rsTender->fetch();
		$totalRows_rsTender = $query_rsTender->rowCount();

		$contractrefno = $tenderno  =  $tendertitle = $tendertype = $tendercat = $procurementmethod = "";
		$tenderevaluationdate = $tenderawarddate = $tendernotificationdate = $tendersignaturedate = "";
		$tenderstartdate = $tenderenddate = $financialscore = $technicalscore = $comments = $contractor_id = "";
		$pinnumber = $bizregno = $biztype = "";

		if ($totalRows_rsTender > 0) {
		  $contractrefno = $row_rsTender['contractrefno'];
		  $tenderno = $row_rsTender['tenderno'];
		  $tendertitle = $row_rsTender['tendertitle'];
		  $tendertypeid = $row_rsTender['tendertype'];
		  $tendercatid = $row_rsTender['tendercat'];
		  $procurementmethodid = $row_rsTender['procurementmethod'];

		  $tenderevaluationdate = $row_rsTender['evaluationdate'];
		  $tenderawarddate = $row_rsTender['awarddate'];
		  $tendernotificationdate = $row_rsTender['notificationdate'];
		  $tendersignaturedate = $row_rsTender['signaturedate'];
		  $tenderstartdate = $row_rsTender['startdate'];
		  $tenderenddate = $row_rsTender['enddate'];
		  $financialscore = $row_rsTender['financialscore'];
		  $technicalscore = $row_rsTender['technicalscore'];
		  $comments = $row_rsTender['comments'];
		  $contractor_id = $row_rsTender['contractor'];

		  $query_cont = $db->prepare("SELECT contractor_name, pinno, busregno, type FROM tbl_contractor c left join tbl_contractorbusinesstype b on c.businesstype=b.id WHERE contrid='$contractor_id'");
		  $query_cont->execute();
		  $row_cont = $query_cont->fetch();
		  if ($row_cont) {
			$contractor = $row_cont['contractor_name'];
			$pinnumber = $row_cont['pinno'];
			$bizregno = $row_cont['busregno'];
			$biztype = $row_cont['type'];
		  }


			$query_rsprocurementmethod = $db->prepare("SELECT * FROM tbl_procurementmethod WHERE id=:method");
			$query_rsprocurementmethod->execute(array(":method" => $procurementmethodid));
			$row_rsprocurementmethod = $query_rsprocurementmethod->fetch();
			$procurementmethod = $row_rsprocurementmethod['method'];

			$query_rscategory = $db->prepare("SELECT * FROM tbl_tender_category WHERE id=:tendercat");
			$query_rscategory->execute(array(":tendercat" => $tendercatid));
			$row_rscategory = $query_rscategory->fetch();
			$tendercat = $row_rscategory["category"];

			$query_rstender = $db->prepare("SELECT * FROM tbl_tender_type WHERE id=:typeid");
			$query_rstender->execute(array(":typeid" => $tendertypeid));
			$row_rstender = $query_rstender->fetch();
			$tendertype = $row_rstender["type"];
		}
	} catch (PDOException $ex) {
		$result = flashMessage("An error occurred: " . $ex->getMessage());
		echo $result;
	}
?>
<style>
@import url("https://code.highcharts.com/css/highcharts.css");
#container-gantt {
    max-width: 1000px;
    margin: 1em auto;
}

.highcharts-label-icon {
    opacity: 0.5;
}
</style>
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
								<a href="project-finance.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Finance</a>
								<a href="project-timeline.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Timeline</a>
								<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; width:100px">Contract</a>
								<a href="project-team-members.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Team</a>
								<a href="project-issues.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Risks & Issues</a>
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
						<div class="row clearfix" style="border:1px solid #f0f0f0; border-radius:3px; margin-left:3px; margin-right:3px">
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="margin-top:15px; margin-bottom:15px">
								<strong>Project Code: </strong> <?= $projcode ?>
							</div>
							<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12" style="margin-top:15px; margin-bottom:15px">
								<strong>Project Location (Ward/s): </strong> <?= $projlocations ?>
							</div>
						</div>
						<div class="body">
							<ul class="nav nav-tabs" style="font-size:14px">
								<li class="active">
									<a data-toggle="tab" href="#menu1"><i class="fa fa-list-alt bg-green" aria-hidden="true"></i> Main Contract Details &nbsp;<span class="badge bg-green">|</span></a>
								</li>
								<li>
									<a data-toggle="tab" href="#menu2"><i class="fa fa-certificate bg-blue" aria-hidden="true"></i> Contract Statutory Guarantees &nbsp;<span class="badge bg-blue">|</span></a>
								</li>
								<li>
									<a data-toggle="tab" href="#menu3"><i class="fa fa-paperclip bg-orange" aria-hidden="true"></i> Other Details &nbsp;<span class="badge bg-orange">|</span></a>
								</li>
							</ul>
						</div>
						<div class="body">
							<div class="tab-content">
								<div id="menu1" class="tab-pane fade in active">
									<fieldset class="scheduler-border" style="background-color:#edfcf1; border-radius:3px">
									  <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-briefcase" style="color:#F44336" aria-hidden="true"></i> Contractor Details</legend>
									  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-inline">
										  <label for="">Contractor Name</label>
										  <div class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%">
											<?php echo $contractor; ?>
										  </div>
										</div>
									  </div>
									  <div id="contrinfo">
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										  <label for="">Pin Number</label>
										 <div class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%"><?= $pinnumber ?></div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										  <label for="">Business Reg No.</label>
										  <div class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%"><?= $bizregno ?></div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										  <label for="">Business Type</label>
										  <div class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%"><?= $biztype ?></div>
										</div>
									  </div>
									</fieldset>
									<fieldset class="scheduler-border" style="border-radius:3px">
										<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
											<i class="fa fa-list" style="color:#F44336" aria-hidden="true"></i> Tender and Contract Details
										</legend>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<label for="">Contract Ref. Number *</label>
											<div class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%"><?= $contractrefno ?></div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<label for="">Contract Signature Date *</label>
											<div class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%"><?= $tendersignaturedate ?></div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<label for="">Contract Start Date *</label>
											<div class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%"><?= $tenderstartdate ?></div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<label for="">Contract Expiry Date *</label>
											<div class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%"><?= $tenderenddate ?></div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<label for="">Tender Number *</label>
											<div class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%"><?= $tenderno ?></div>
										</div>
										<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
											<label for="Title">Tender Title *:</label>
											<div class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%"><?= $tendertitle; ?>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<label for="">Tender Type *</label>
											<div class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%"><?php echo $tendertype; ?></div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<label for="">Tender Category *</label>
											<div class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%"><?php echo $tendercat; ?></div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<label for="">Procurement Method *</label>
											<div class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%"><?php echo $procurementmethod ?></div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<label for="">Tender Evaluation Date *</label>
											<div class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%"><?= $tenderevaluationdate ?></div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<label for="">Tender Technical Score *</label>
											<div class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%"><?= $technicalscore ?></div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<label for="">Tender Financial Score *</label>
											<div class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%"><?= $financialscore ?></div>
										  </div>
										  <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<label for="">Tender Award Date *</label>
											<div class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%"><?= $tenderawarddate ?></div>
										  </div>
										  <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<label for="">Tender Notification Date *</label>
											<div class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%"><?= $tendernotificationdate ?></div>
										  </div>
									</fieldset>
								</div>
								<div id="menu2" class="tab-pane fade">
									<fieldset class="scheduler-border" style="border-radius:3px">
										<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
											<i class="fa fa-certificate" style="color:#F44336" aria-hidden="true"></i> Statutory Guarantees
										</legend>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="table-responsive">
											  <table class="table table-bordered" id="guarantees_table">
												<thead>
												  <tr>
													<th style="width:5%">#</th>
													<th style="width:35%">Guarantee</th>
													<th style="width:13%">Start Date</th>
													<th style="width:13%">End Date</th>
													<th style="width:10%">Duration</th>
													<th style="width:10%">Due In</th>
													<th style="width:14%">Notification</th>
												  </tr>
												</thead>
												<tbody>
													<?php
													$query_contract_guarantees = $db->prepare("SELECT * FROM tbl_contract_guarantees WHERE projid=:projid");
													$query_contract_guarantees->execute(array(":projid" => $projid));
													$totalRows_contract_guarantees = $query_contract_guarantees->rowCount();

													if ($totalRows_contract_guarantees > 0) {
														$rowno = 0;
														while ($row_contract_guarantees = $query_contract_guarantees->fetch()) {
															$rowno++;
															$start_date = $row_contract_guarantees['start_date'];
															$duration = $row_contract_guarantees['duration'];
															$notification = $row_contract_guarantees['notification'];
															$end_date = date('Y-m-d', strtotime($start_date . ' + ' . $duration . ' days'));

															$today=date('Y-m-d');
															$origin = date_create($today);
															$target = date_create($end_date);
															$interval = date_diff($origin, $target);
															$remaining_time = $interval->format('%a');

															if($remaining_time >= 30){
																$badge = "bg-green";
															}elseif($remaining_time < 30 && $remaining_time >= 10){
																$badge = "bg-orange";
															}elseif($remaining_time < 10){
																$badge = "bg-danger";
															}
															?>
															<tr id="guarantee_row">
															  <td style="width:5%"><?= $rowno ?></td>
															  <td style="width:35%"><?= $row_contract_guarantees['guarantee'] ?></td>
															  <td style="width:13%"><?= $start_date ?></td>
															  <td style="width:13%"><?= $end_date ?></td>
															  <td style="width:10%"><?= $duration ?> Days</td>
															  <td style="width:10%"><span class="badge <?=$badge?>"><?= $remaining_time ?> Days</span></td>
															  <td style="width:14%"><?= $notification ?> Days</td>
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
								<div id="menu3" class="tab-pane fade">
									<fieldset class="scheduler-border" style="border-radius:3px">
										<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
											<i class="fa fa-commenting" style="color:#F44336" aria-hidden="true"></i> Comments
										</legend>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="form-control require" style="border:#CCC thin solid; border-radius:5px; width:98%"><?= $comments ?></div>
										</div>
									</fieldset>
									<fieldset class="scheduler-border" style="border-radius:3px">
										<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
											<i class="fa fa-paperclip" style="color:#F44336" aria-hidden="true"></i> Files and Documents
										</legend>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="table-responsive">
											  <table class="table table-bordered" id="files_table">
												<thead>
												  <tr>
													<th style="width:40%">Attachments *</th>
													<th style="width:58%">Attachment Purpose *</th>
													<th style="width:2%">Action</th>
												  </tr>
												</thead>
												<tbody>
													<?php
													$query_contract_files = $db->prepare("SELECT * FROM tbl_files WHERE projid=:projid AND projstage=4");
													$query_contract_files->execute(array(":projid" => $projid));
													$totalRows_contract_files = $query_contract_files->rowCount();

													if ($totalRows_contract_files > 0) {
														while ($row_contract_files = $query_contract_files->fetch()) {
															$filepath = $row_contract_files['floc'];
															?>
															<tr>
																<td style="width:40%"><?= $row_contract_files['filename'] ?></td>
																<td style="width:58%"><?= $row_contract_files['reason'] ?></td>
																<td style="width:2%">
																	<a href="<?=$filepath?>" type="button" name="addplus" title="Download document" class="btn btn-success btn-sm" download>
																		<i class="fa fa-cloud-download" aria-hidden="true"></i>
																	</a>
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