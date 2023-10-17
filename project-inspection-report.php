<?php
$pageName = "Project Inspection Report";
$replacement_array = array(
  'planlabel' => "CIDP",
  'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
$pageTitle = $planlabelplural;

if ($permission) {
  $pageTitle = "Project Inspection Report";
  try {
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
      $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }

    $currentdate = date("Y-m-d");
    $projname = $projcode = $projstatus = $locationName = '';
    if (isset($_GET['projid'])) {
		$projid = base64_decode(htmlspecialchars(trim($_GET['projid'])));
		//$projid = $_GET['projid'];
		$query_rsTPList = $db->prepare("SELECT projname, projcode FROM tbl_projects WHERE projid=:projid");
		$query_rsTPList->execute(array(":projid" => $projid));
		$row_rsTPList = $query_rsTPList->fetch();
		$projname = $row_rsTPList['projname'];
		$projcode = $row_rsTPList['projcode'];
    }
  } catch (PDOException $ex) {
    $results = flashMessage("An error occurred: " . $ex->getMessage());
  }
?>
  <!-- Light Gallery Plugin Js -->
  <link href="assets-1/projtrac-dashboard/plugins/light-gallery/css/lg-transitions.min.css">
  <link href="assets-1/projtrac-dashboard/plugins/light-gallery/css/lightgallery.css">
  <!-- start body  -->
  <section class="content">
    <div class="container-fluid">
      <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
        <h4 class="contentheader">
			<i class="fa fa-columns" aria-hidden="true"></i>
			<?php echo $pageTitle ?>
			<div class="btn-group" style="float:right">
				<button onclick="history.back()" type="button" class="btn bg-orange waves-effect" style="float:right; margin-top:-5px">Go Back </button>
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
              <fieldset class="scheduler-border">
                <legend class="scheduler-border" style="background-color:#c7e1e8;  border:#CCC thin dashed; border-radius:3px">Project Details</legend>
                <div class="col-lg-2 col-md-12col-sm-12 col-xs-12">
                  <label>Project Code:</label>
                  <input type="text" class="form-control" value="<?php echo $projcode; ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px" />
                </div>
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                  <label>Project Name:</label>
                  <input type="text" class="form-control" value="<?php echo $projname; ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px" />
                </div>
              </fieldset>
              <?php
              $username = 8;
              $query_rsInspection_details = $db->prepare("SELECT * FROM tbl_general_inspection WHERE projid = :projid AND subject=1 ORDER BY created_at ASC");
              $query_rsInspection_details->execute(array(":projid" => $projid));
              $row_rsInspection_details = $query_rsInspection_details->fetch();
              $totalRows_rsInspection_details = $query_rsInspection_details->rowCount();
			  
              $query_inspection_details = $db->prepare("SELECT * FROM tbl_projissues WHERE projid = :projid AND origin='Inspection' GROUP BY formid ORDER BY id ASC");
              $query_inspection_details->execute(array(":projid" => $projid));
              $totalRows_inspection_details = $query_inspection_details->rowCount();
              $nm = 0;
              if ($totalRows_rsInspection_details > 0) {
                do {
                  $inspection_id = $row_rsInspection_details['id'];
                  $location = $row_rsInspection_details['location'];
                  $observations = $row_rsInspection_details['observations'];
                  $created_by = $row_rsInspection_details['created_by'];
                  $created_at = $row_rsInspection_details['created_at'];
                  $inspectionid = $row_rsInspection_details['inspectionid'];

                  $query_rsState = $db->prepare("SELECT * FROM tbl_state WHERE id = :location");
                  $query_rsState->execute(array(":location" => $location));
                  $row_rsState = $query_rsState->fetch();
                  $totalRows_rsState = $query_rsState->rowCount();
                  $state = $row_rsState['state'];
                  $stid = $row_rsState['id'];
				  
                  $query_issues = $db->prepare("SELECT * FROM tbl_projissues WHERE projid = :projid AND formid = :inspectionid");
                  $query_issues->execute(array(":projid" => $projid, ":inspectionid" => $inspectionid));
                  $totalRows_issues = $query_issues->rowCount();

                  $query_rsUsers = $db->prepare("SELECT t.title, t.fullname FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid WHERE userid = '$created_by'");
                  $query_rsUsers->execute();
                  $row_rsUsers = $query_rsUsers->fetch();
                  $totalRows_rsUsers = $query_rsUsers->rowCount();

                  $query_rsFiles = $db->prepare("SELECT * FROM tbl_files WHERE projid = '$projid' AND general_inspection_id='$inspectionid'");
                  $query_rsFiles->execute();
                  $row_rsFiles = $query_rsFiles->fetch();
                  $totalRows_rsFiles = $query_rsFiles->rowCount();

                  if ($totalRows_rsState > 0) {
                    $nm++;
					//$inspection_id = $nm;
					?>
                    <div class="panel panel-primary">
                      <div class="panel-heading list-group-item list-group-item list-group-item-action active collapse careted" data-toggle="collapse" data-target=".output<?php echo $inspection_id ?>">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        <strong> Inspection <?= $nm ?>:
                          <span class="">
                            <?= $state ?>
                          </span>
                        </strong>
                      </div>
                      <div class="collapse output<?php echo $inspection_id ?>" style="padding:5px">
                        <fieldset class="scheduler-border">
                          <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Project Inspection Details</legend>
                          <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <label>Location :</label>
                            <div class="form-line">
                              <input type="text" class="form-control" value="<?php echo $state; ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px" />
                            </div>
                          </div>
                          <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <label>Date Recorded: </label>
                            <div class="form-line">
                              <input type="text" class="form-control" value="<?php echo $created_at; ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px" />
                            </div>
                          </div>
                          <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <label>Recorded By :</label>
                            <div class="form-line">
                              <input type="text" class="form-control" value="<?php echo $row_rsUsers['title'].".".$row_rsUsers['fullname']; ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px" />
                            </div>
                          </div>
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Observations :</label>
                            <div class="form-line">
							<textarea type="text" class="form-control" readonly="readonly"><?php echo strip_tags($observations) ?></textarea>
                            </div>
                          </div>
                          <div class="col-md-12"></div>
							<?php
							if($totalRows_issues > 0){
								?>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<fieldset class="scheduler-border">
										<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Inspection Issues</legend>
										<!-- Task Checklist Questions -->
										<div class="row clearfix">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="card" style="margin-bottom:-20px">
													<div class="body">
														<table class="table table-bordered" id="issues_table<?= $opid ?>" style="width:100%">
															<tr>
																<th style="width:2%">#</th>
																<th style="width:23%">Issue Category</th>
																<th style="width:75%">Issue Description</th>
															</tr>
															<?php
															$sn = 0;
															while($row_issues = $query_issues->fetch()){
																$sn++;
																$issuecatid = $row_issues["risk_category"];
																$issuedescription = $row_issues["observation"];

																$query_issuecat = $db->prepare("SELECT category FROM tbl_projrisk_categories WHERE rskid = '$issuecatid'");
																$query_issuecat->execute();
																$row_issuecat = $query_issuecat->fetch();
																$issuecategory = $row_issuecat["category"];
																?>
																<tr>
																	<td><?=$sn?></td>
																	<td>
																		<?php
																		  echo $issuecategory;
																		?>
																	</td>
																	<td>
																		<?php
																		  echo $issuedescription;
																		?>
																	</td>
																</tr>
															<?php
															}
															?>
														</table>
													</div>
												</div>
											</div>
										</div>
											<!-- Task Checklist Questions -->
									</fieldset>
								</div>
							<?php
							}
							?>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Media</legend>
									<!-- Task Checklist Questions -->
									<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div id="aniimated-thumbnials<?= $inspection_id ?>" class="list-unstyled row clearfix">
														<?php
														if ($totalRows_rsFiles > 0) {
															do {
															?>
																<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
																	<a href="<?= $row_rsFiles['floc'] ?>" data-sub-html="<?= $row_rsFiles['description'] ?>">
																	  <img class="img-responsive" src="<?= $row_rsFiles['floc'] ?>">
																	</a>
																</div>
															<?php
															} while ($row_rsFiles = $query_rsFiles->fetch());
														} else {
															?>
															<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
																<p align="center" style="color:red">Sorry no media found!!</p>
															</div>
															<?php
														}
														?>
													</div>
										</div>
									</div>
								</fieldset>
                            </div>
                        </fieldset>
                      </div>
                    </div>
              <?php
                  }
                } while ($row_rsInspection_details = $query_rsInspection_details->fetch());
              } else {
                echo "No Inspection Data Found";
              }
              
			  
			  if ($totalRows_inspection_details > 0) {
                while ($row_inspection_details = $query_inspection_details->fetch()) {
					$formid = $row_inspection_details['formid'];

					$query_form = $db->prepare("SELECT * FROM tbl_general_inspection WHERE inspectionid = :formid");
					$query_form->execute(array(":formid" => $formid));
					$totalRows_form = $query_form->rowCount();
					if($totalRows_form == 0){
					  $created_by = $row_inspection_details['created_by'];
					  $created_at = $row_inspection_details['date_created'];
					  $location = $row_inspection_details['level3'];

					  $query_rsState = $db->prepare("SELECT * FROM tbl_state WHERE id = :location");
					  $query_rsState->execute(array(":location" => $location));
					  $row_rsState = $query_rsState->fetch();
					  $totalRows_rsState = $query_rsState->rowCount();
					  $state = $row_rsState['state'];
					  $stid = $row_rsState['id'];
				  
					  $query_issues = $db->prepare("SELECT * FROM tbl_projissues WHERE formid = :formid");
					  $query_issues->execute(array(":formid" => $formid));

					  $query_rsUsers = $db->prepare("SELECT t.title, t.fullname FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid WHERE userid = '$created_by'");
					  $query_rsUsers->execute();
					  $row_rsUsers = $query_rsUsers->fetch();
					  $totalRows_rsUsers = $query_rsUsers->rowCount();

					  $query_rsFiles = $db->prepare("SELECT * FROM tbl_files WHERE projid = '$projid' AND general_inspection_id='$inspection_id'");
					  $query_rsFiles->execute();
					  $row_rsFiles = $query_rsFiles->fetch();
					  $totalRows_rsFiles = $query_rsFiles->rowCount();

					  if ($totalRows_rsState > 0) {
						$nm++;
						$inspection_id = $nm;
						?>
						<div class="panel panel-primary">
						  <div class="panel-heading list-group-item list-group-item list-group-item-action active collapse careted" data-toggle="collapse" data-target=".output<?php echo $inspection_id ?>">
							<i class="fa fa-plus" aria-hidden="true"></i>
							<strong> Inspection <?= $nm ?>:
							  <span class="">
								<?= $state ?>
							  </span>
							</strong>
						  </div>
						  <div class="collapse output<?php echo $inspection_id ?>" style="padding:5px">
							<fieldset class="scheduler-border">
							  <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Project Inspection Details</legend>
							  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<label>Created At: </label>
								<div class="form-line">
								  <input type="text" class="form-control" value="<?php echo $created_at; ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px" />
								</div>
							  </div>
							  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<label>Created By :</label>
								<div class="form-line">
								  <input type="text" class="form-control" value="<?php echo $row_rsUsers['title'].".".$row_rsUsers['fullname']; ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px" />
								</div>
							  </div>
							  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<label>Location :</label>
								<div class="form-line">
								  <input type="text" class="form-control" value="<?php echo $state; ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px" />
								</div>
							  </div>
							  <div class="col-md-12"></div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<fieldset class="scheduler-border">
										<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Inspection Issues</legend>
										<!-- Task Checklist Questions -->
										<div class="row clearfix">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="card" style="margin-bottom:-20px">
													<div class="body">
														<table class="table table-bordered" id="issues_table<?= $opid ?>" style="width:100%">
															<tr>
																<th style="width:2%">#</th>
																<th style="width:23%">Issue Category</th>
																<th style="width:75%">Issue Description</th>
															</tr>
															<?php
															$sn = 0;
															while($row_issues = $query_issues->fetch()){
																$sn++;
																$issuecatid = $row_issues["risk_category"];
																$issuedescription = $row_issues["observation"];

																$query_issuecat = $db->prepare("SELECT category FROM tbl_projrisk_categories WHERE rskid = '$issuecatid'");
																$query_issuecat->execute();
																$row_issuecat = $query_issuecat->fetch();
																$issuecategory = $row_issuecat["category"];
																?>
																<tr>
																	<td><?=$sn?></td>
																	<td>
																		<?php
																		  echo $issuecategory;
																		?>
																	</td>
																	<td>
																		<?php
																		  echo $issuedescription;
																		?>
																	</td>
																</tr>
															<?php
															}
															?>
														</table>
													</div>
												</div>
											</div>
										</div>
											<!-- Task Checklist Questions -->
									</fieldset>
								</div>
								
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<fieldset class="scheduler-border">
										<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Observation(s) & Media</legend>
										<!-- Task Checklist Questions -->
										<div class="row clearfix">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<div id="aniimated-thumbnials<?= $inspection_id ?>" class="list-unstyled row clearfix">
															<?php
															if ($totalRows_rsFiles > 0) {
																do {
																?>
																	<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
																		<a href="<?= $row_rsFiles['floc'] ?>" data-sub-html="<?= $row_rsFiles['description'] ?>">
																		  <img class="img-responsive" src="<?= $row_rsFiles['floc'] ?>">
																		</a>
																	</div>
																<?php
																} while ($row_rsFiles = $query_rsFiles->fetch());
															} else {
																?>
																<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
																	<p align="center" style="color:red">Sorry no media found!!</p>
																</div>
																<?php
															}
															?>
														</div>
											</div>
										</div>
									</fieldset>
								</div>
							</fieldset>
						  </div>
						</div>
					<?php
					  }
					} 
				}
              } else {
                echo "No Inspection Data Found";
              }
              ?>
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
<!-- Light Gallery Plugin Js -->
<script src="projtrac-dashboard/plugins/light-gallery/js/lightgallery-all.js"></script>
<script>
  $(function() {
    $(".careted").click(function(e) {
      e.preventDefault();
      console.log("carated")
      $(this)
        .find("i")
        .toggleClass("fa fa-minus fa fa-plus");
    });
  });
</script>