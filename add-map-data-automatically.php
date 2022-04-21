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
      if (isset($_GET['mapid'])) {
          $mapping_id = $_GET['mapid'];
          $query_rsMap = $db->prepare("SELECT *  FROM tbl_project_mapping WHERE id=:mapping_id ");
          $query_rsMap->execute(array(":mapping_id" => $mapping_id));
          $row_rsMap = $query_rsMap->fetch();
          $totalRows_rsMap = $query_rsMap->rowCount();

          $responsible = $row_rsMap['responsible'];
          $projid = $row_rsMap['projid'];
          $opid = $row_rsMap['outputid'];
          $location = $row_rsMap['location'];
          $stid = $row_rsMap['stid'];
          $locationName = '';
          if ($location != 0) {
              $query_rsComm =  $db->prepare("SELECT id, name FROM tbl_project_results_level_disaggregation WHERE id='$location'");
              $query_rsComm->execute();
              $row_rsComm = $query_rsComm->fetch();
              $totalRows_rsComm = $query_rsComm->rowCount();
              $name = $row_rsComm['name'];
              $locationName = '<li class="list-group-item"><strong>Location: </strong> ' . $name . '</li>';
          }

          $query_rsProjects = $db->prepare("SELECT *  FROM tbl_projects WHERE deleted='0' and projid=:projid");
          $query_rsProjects->execute(array(":projid" => $projid));
          $row_rsProjects = $query_rsProjects->fetch();
          $totalRows_rsProjects = $query_rsProjects->rowCount();
          $projname = $row_rsProjects['projname'];
          $projcode = $row_rsProjects['projcode'];

          $query_rsOutput =  $db->prepare("SELECT * FROM tbl_project_details WHERE id ='$opid'  ORDER BY id ASC");
          $query_rsOutput->execute();
          $row_rsOutput = $query_rsOutput->fetch();
          $totalRows_rsOutput = $query_rsOutput->rowCount();
          $indicatorID = $row_rsOutput['indicator'];
          $outputid = $row_rsOutput['outputid'];
          $mapping_type = $row_rsOutput['mapping_type'];

          $query_Indicator = $db->prepare("SELECT indicator_name,indicator_unit FROM tbl_indicator WHERE indid ='$indicatorID'");
          $query_Indicator->execute();
          $row = $query_Indicator->fetch();
          $indname = $row['indicator_name'];
          $unitid = $row['indicator_unit'];

          $query_rsOPUnit = $db->prepare("SELECT unit FROM  tbl_measurement_units WHERE id ='$unitid'");
          $query_rsOPUnit->execute();
          $row_rsOPUnit = $query_rsOPUnit->fetch();
          $opunit = $row_rsOPUnit['unit'];

          $query_out = $db->prepare("SELECT * FROM tbl_progdetails WHERE id='$outputid'");
          $query_out->execute();
          $row_out = $query_out->fetch();
          $count_row = $query_out->rowCount();
          $outputName = "";
          if ($count_row > 0) {
              $outputName = $row_out['output'];
          }

          // get the state level3
          $query_rsForest =  $db->prepare("SELECT id, state , parent FROM tbl_state WHERE id=:projstate");
          $query_rsForest->execute(array(":projstate" => $stid));
          $row_rsForest = $query_rsForest->fetch();
          $totalRows_rsForest = $query_rsForest->rowCount();
          $level3 = $row_rsForest['state'];
          $parent = $row_rsForest['parent'];

          // get the state level2
          $query_rsForest =  $db->prepare("SELECT id, state, parent FROM tbl_state WHERE id=:projstate");
          $query_rsForest->execute(array(":projstate" => $parent));
          $row_rsForest = $query_rsForest->fetch();
          $totalRows_rsForest = $query_rsForest->rowCount();
          $level2 = $row_rsForest['state'];
          $parent1 = $row_rsForest['parent'];

          // get the state location
          $query_rsForest =  $db->prepare("SELECT id, state FROM tbl_state WHERE id=:projstate");
          $query_rsForest->execute(array(":projstate" => $parent1));
          $row_rsForest = $query_rsForest->fetch();
          $totalRows_rsForest = $query_rsForest->rowCount();
          $level1 = $row_rsForest['state'];
      }

      if (isset($_POST['submit'])) {
          $projid = $_POST['projid'];
          $outputid = $_POST['opid'];
          $comment = $_POST['comment'];
          $location = ($_POST['location'] != "") ? $_POST['location'] : 0;
          $user_name = $_POST['user_name'];
          $current_date = date("Y-m-d");
          $state = $_POST['state'];
          $mapid = $_POST['mapid'];
          $lat = $_POST['lat'];
          $lng = $_POST['lng'];

          $result = [];
          $counter = count($lat);
          for ($i = 0; $i < $counter; $i++) {
              $lat = $lat[$i];
              $lng = $lng[$i];
              $params = array(':projid' => $projid, ":opid" => $outputid, ":mapid" => $mapid, ":state" => $state, ':location' => $location, ':lat' => $lat, ':lng' => $lng, ":comment" => $comment, ":mapped_date" => $current_date, ":mapped_by" => $user_name);
              $sql = $db->prepare("INSERT INTO tbl_markers (projid,opid,mapid,state, location, lat, lng, comment, mapped_date,mapped_by)  VALUES(:projid,:opid,:mapid, :state, :location,:lat, :lng, :comment,:mapped_date,:mapped_by)");
              $result[] = $sql->execute($params);
          }

          if ($result) {
              $query_rsProjects = $db->prepare("SELECT *  FROM tbl_projects WHERE deleted='0' and projid=:projid");
              $query_rsProjects->execute(array(":projid" => $projid));
              $row_rsProjects = $query_rsProjects->fetch();
              $totalRows_rsProjects = $query_rsProjects->rowCount();
              $projstate = $row_rsProjects['projlocation'];
              $state = explode(",", $projstate);
              $handler = [];
              for ($i = 0; $i < count($state); $i++) {
                  $query_rsMap = $db->prepare("SELECT * FROM tbl_markers WHERE state=:state");
                  $query_rsMap->execute(array(":state" => $state[$i]));
                  $row_rsMap = $query_rsMap->fetch();
                  $totalRows_rsMap = $query_rsMap->rowCount();

                  if ($totalRows_rsMap > 0) {
                      $handler[] = true;
                  } else {
                      $handler[] = false;
                  }
              }

              if (!in_array(false, $handler)) {
                  $mapped = 1;
                  $sql = "UPDATE tbl_projects SET mapped=:mapped WHERE projid=:projid";
                  $stmt = $db->prepare($sql);
                  $result = $stmt->execute(array(':mapped' => $mapped, ':projid' => $projid));
                  if ($result) {
                      $msg = "Successfully Mapped";
                      $results = "<script type=\"text/javascript\">
                          swal({
                          title: \"Success!\",
                          text: \" $msg\",
                          type: 'Success',
                          timer: 2000,
                          showConfirmButton: false });
                          setTimeout(function(){
                                  window.location.href = 'project-mapping.php';
                              }, 2000);
                      </script>";
                      echo $results;
                  }
              } else {
                  $msg = "Successfully Mapped";
                  $results = "<script type=\"text/javascript\">
                          swal({
                          title: \"Success!\",
                          text: \" $msg\",
                          type: 'Success',
                          timer: 2000,
                          showConfirmButton: false });
                          setTimeout(function(){
                                  window.location.href = 'project-mapping.php';
                              }, 2000);
                      </script>";
                  echo $results;
              }
          }
      }
  } catch (PDOException $ex) {
      $result = flashMessage("An error occurred: " . $ex->getMessage());
      print($result);
  }
?>
<style>
    /* Always set the map height explicitly to define the size of the div
   * element that contains the map. */
    #map {
        height: 500px;
        width: 98%;
        /* The width is the width of the web page */
    }

    /* Optional: Makes the sample page fill the window. */
    html,
    body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
</style>
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
                 <div class="card-header">
                     <div class="row clearfix">
                         <div class="col-md-12">
                             <ul class="list-group">
                                 <li class="list-group-item list-group-item list-group-item-action active">Project Name: <?= $projname ?> </li>
                                 <li class="list-group-item"><strong>Output: </strong> <?= $outputName ?> </li>
                                 <li class="list-group-item"><strong>Output Unit of Measure: </strong> <?= $opunit ?> </li>
                                 <li class="list-group-item"><strong>Project <?= $level1label ?>: </strong> <?= $level1 ?> </li>
                                 <li class="list-group-item"><strong>Project <?= $level2label ?>: </strong> <?= $level2 ?> </li>
                                 <li class="list-group-item"><strong>Project <?= $level3label ?>: </strong> <?= $level3 ?> </li>
                                 <?= $locationName ?>
                             </ul>
                         </div>
                         <div class="col-md-12" id="sbutton">
                         </div>
                     </div>
                 </div>
                  <div class="body">
                    <?php
                    if (isset($mapping_type) == 1) {
                        // add area map
                    ?>
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <form action="" method="post" id="submitform">
                                    <div class="col-md-6">
                                        <label for="lat">Project Latitude *:</label>
                                        <input type="text" name="lat[]" id="lat" readonly class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="long">Project Longitude *:</label>
                                        <input type="text" name="lng[]" id="lng" readonly class="form-control">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="comment">Mapping Comments *:</label>
                                        <textarea name="comment" class="form-control" id="" cols="" rows="5" required></textarea>
                                    </div>
                                    <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                    <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                    <input type="hidden" name="mapid" id="mapid" value="<?= $mapping_id ?>">
                                    <input type="hidden" name="opid" id="opid" value="<?= $opid ?>">
                                    <input type="hidden" name="state" id="state" value="<?= $stid ?>">
                                    <input type="hidden" name="location" id="location" value="<?= $location ?>">
                                    <div class="col-md-12" align="center">
                                        <button type="submit" name="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php
                    } else if ($mapping_type == 2) {
                        // static maps
                    ?>
                        <div class="row clearfix">
                            <div class="col-md-12" align="right">
                                <button type="button" name="start" onclick="check_position()" id="start" class="btn btn-warning">Start</button>
                            </div>
                            <div class="col-md-12">
                                <form action="" method="post" id="submitform">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover" id="assign_table" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th width="30%">Latitude</th>
                                                    <th width="30%">Longitude</th>
                                                </tr>
                                            </thead>
                                            <tbody id="waypoint_table_body">
                                                <tr></tr>
                                                <tr id="removeTr">
                                                    <td colspan="3">Assign</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="comment">Mapping Comments *:</label>
                                        <textarea name="comment" class="form-control" id="" cols="" rows="5" required></textarea>
                                    </div>
                                    <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                    <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                    <input type="hidden" name="mapid" id="mapid" value="<?= $mapping_id ?>">
                                    <input type="hidden" name="opid" id="opid" value="<?= $opid ?>">
                                    <input type="hidden" name="state" id="state" value="<?= $stid ?>">
                                    <input type="hidden" name="location" id="location" value="<?= $location ?>">

                                    <div class="col-md-12" align="center">
                                        <button type="submit" name="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php
                    } else if ($mapping_type == 3) {
                        // waypoint maps
                    ?>
                        <div class="row clearfix">
                            <div class="col-md-12" align="right">
                                <button type="button" name="start" onclick="checkposition()" id="start" class="btn btn-warning">Start</button>
                            </div>
                            <div class="col-md-12">
                                <form action="" method="post" id="submitform">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover" id="assign_table" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th width="30%">Latitude</th>
                                                    <th width="30%">Longitude</th>
                                                </tr>
                                            </thead>
                                            <tbody id="area_table_body">
                                                <tr></tr>
                                                <tr id="removeTr">
                                                    <td colspan="3">Assign</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="comment">Mapping Comments *:</label>
                                        <textarea name="comment" class="form-control" id="" cols="" rows="5" required></textarea>
                                    </div>
                                    <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                    <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                    <input type="hidden" name="mapid" id="mapid" value="<?= $mapping_id ?>">
                                    <input type="hidden" name="opid" id="opid" value="<?= $opid ?>">
                                    <input type="hidden" name="state" id="state" value="<?= $stid ?>">
                                    <input type="hidden" name="location" id="location" value="<?= $location ?>">
                                    <div class="col-md-12" align="center">
                                        <button type="submit" name="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php
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
<script src="assets/custom js/add-mapping.js"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8Ii3rrQB5FLivgpihlQPuQSUU6EMc-sQ">
</script>
