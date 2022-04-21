<?php
$pageName = "Strategic Plans";
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";

require('includes/head.php');
if ($permission) {
$pageTitle = "Project Maps";
try {

   if (isset($_GET['projid']) && !empty($_GET['projid'])) {
      $projid  = $_GET['projid'];
      $query_rsTPList = $db->prepare("SELECT projname, projcode, projmapping FROM tbl_projects WHERE projid=:projid");
      $query_rsTPList->execute(array(":projid" => $projid));
      $row_rsTPList = $query_rsTPList->fetch();
      $projname = $row_rsTPList['projname'];
      $projcode = $row_rsTPList['projcode'];
      $mapping = $row_rsTPList['projmapping'];

      $query_rsTP = $db->prepare("SELECT g.output, d.id FROM tbl_project_details d INNER JOIN tbl_progdetails g ON g.id = d.outputid WHERE projid=:projid");
      $query_rsTP->execute(array(":projid" => $projid));
      $count_rsTP = $query_rsTP->rowCount();
   } else {
      $url = "myprojects.php";
      $msg = 'Please select a project.';
      $results =
         "<script type=\"text/javascript\">
          swal({
            title: \"Error!\",
            text: \" $msg\",
            type: 'Error',
            timer: 5000,
            icon:'error',
            showConfirmButton: false
          });
            setTimeout(function(){
            window.location.href = '$url';
            }, 5000);
      </script>";
      echo $results;
      return;
   }
} catch (PDOException $ex) {
   $result = flashMessage("An error occurred: " . $ex->getMessage());
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
                          <?php
                          if ($mapping == 1) {
                          ?>
                             <div class="header">
                                <h4 class="card-title">Project Name: <?= $projname ?></h4> <br>
                                <h4 class="card-title">Project Code: <?= $projcode ?></h4>
                                <div class="form-group">
                                   <select name="output" id="output" class="form-control show-tick " data-live-search="true" style="border:#CCC thin solid; border-radius:5px;" data-live-search-style="startsWith">
                                      <option value="" selected="selected">Select Output</option>
                                      <?php
                                      while ($row_rsTP = $query_rsTP->fetch()) {
                                      ?>
                                         <option value="<?php echo $row_rsTP['id'] ?>"><?php echo $row_rsTP['output'] ?></option>
                                      <?php
                                      }
                                      ?>
                                   </select>
                                </div>
                             </div>
                             <div class="body">
                                <style>
                                   .mt-map-wrapper {
                                      width: 100%;
                                      padding-bottom: 41.6%;
                                      height: 0;
                                      overflow: hidden;
                                      position: relative;
                                   }

                                   .mt-map {
                                      width: 100%;
                                      height: 100%;
                                      left: 0;
                                      top: 0;
                                      position: absolute;
                                   }
                                </style>

                                <div class="mt-map-wrapper">
                                   <div class="mt-map propmap" id="map">
                                      <div style="height: 100%; width: 100%; position: relative; overflow: hidden; background-color: rgb(229, 227, 223);">
                                      </div>
                                   </div>
                                </div>
                             </div>
                             <input type="hidden" name="lat" id="lat" value="0.459995">
                             <input type="hidden" name="long" id="long" value="35.250637">
                             <script src="assets/js/maps/get_output_coordinates.js"></script>
                             <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDiyrRpT1Rg7EUpZCUAKTtdw3jl70UzBAU"></script>
                          <?php
                          } else {
                          ?>
                             <div class="body">
                                <div class="header">
                                   <h4 class="card-title">Project Name: <?= $projname ?></h4> <br>
                                   <h4 class="card-title">Project Code: <?= $projcode ?></h4>
                                </div>
                                <div class="card-body" style="margin-top: 60px;">
                                   <h1 class="text-warning text-center">Sorry this project does not require mapping</h1>
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
