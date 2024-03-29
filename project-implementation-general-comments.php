<?php
$pageName = "Project Implementation General Comments";
$replacement_array = array(
  'planlabel' => "CIDP",
  'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
$pageTitle = $planlabelplural;

if ($permission) {
  $pageTitle = "Project Implementation General Comments";
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
              $query_general_comments = $db->prepare("SELECT * FROM tbl_general_inspection WHERE projid = :projid AND subject=2 ORDER BY created_at ASC");
              $query_general_comments->execute(array(":projid" => $projid));
              $row_general_comments = $query_general_comments->fetch();
              $totalRows_general_comments = $query_general_comments->rowCount();
			  
              $nm = 0;
              if ($totalRows_general_comments > 0) {
                do {
                  $inspection_id = $row_general_comments['id'];
                  $location = $row_general_comments['location'];
                  $observations = $row_general_comments['observations'];
                  $created_by = $row_general_comments['created_by'];
                  $created_at = $row_general_comments['created_at'];
                  $inspectionid = $row_general_comments['inspectionid'];

                  $query_rsState = $db->prepare("SELECT * FROM tbl_state WHERE id = :location");
                  $query_rsState->execute(array(":location" => $location));
                  $row_rsState = $query_rsState->fetch();
                  $totalRows_rsState = $query_rsState->rowCount();
                  $state = $row_rsState['state'];
                  $stid = $row_rsState['id'];

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
                          <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Project Observations</legend>
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
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <label>Observations :</label>
                            <div class="form-line">
                              <?= $observations ?>
                            </div>
                          </div>
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
                } while ($row_general_comments = $query_general_comments->fetch());
              } else {
                echo "No comments recorded!";
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