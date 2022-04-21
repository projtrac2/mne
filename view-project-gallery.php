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
   $pageTitle = "Project Gallery";
   try {
      $count_rsTP  = 0;
      if (isset($_GET['projid'])) {
         $projid  = $_GET['projid'];
         $query_rsTPList = $db->prepare("SELECT projname, projcode FROM tbl_projects WHERE projid=:projid");
         $query_rsTPList->execute(array(":projid" => $projid));
         $row_rsTPList = $query_rsTPList->fetch();
         $projname = $row_rsTPList['projname'];
         $projcode = $row_rsTPList['projcode'];

         $query_rsTP = $db->prepare("SELECT * FROM tbl_project_photos WHERE projid=:projid");
         $query_rsTP->execute(array(":projid" => $projid));
         $row_rsTP = $query_rsTP->fetch();
         $count_rsTP = $query_rsTP->rowCount();
      }
   } catch (PDOException $ex) {
      $result = flashMessage("An error occurred: " . $ex->getMessage());
   }
?>
   <!-- Light Gallery Plugin Js -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightgallery@2.0.0-beta.3/css/lightgallery.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightgallery@2.0.0-beta.3/css/lg-zoom.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightgallery@2.0.0-beta.3/css/lg-fullscreen.css">

   <style>
      .gallery-item {
         width: 200px;
         padding: 5px;
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
                     <div class="header">
                        <div style="color:#333; width:100%; height:30px; padding-top:5px; padding-left:2px">
                           <h4 class="card-title">Project Name: <?= $projname ?></h4> <br>
                           <h4 class="card-title">Project Code: <?= $projcode ?></h4>
                        </div>
                     </div>
                  </div>
                  <div class="body">
                     <div class="gallery-container d-flex align-items-center justify-content-center" id="gallery-container">
                        <?php
                        if ($count_rsTP > 0) {
                           do {
                        ?>
                              <a data-lg-size="1443-1329" class="gallery-item" data-src="<?= $row_rsTP['floc'] ?>" data-sub-html="<h4>Photo by - <a href='#' >Uasin Gishu County </a></h4><p> Uasin Gishu County - <a href='#'><?= $row_rsTP['description'] ?></a></p>">
                                 <img class="img-fluid" src="<?= $row_rsTP['floc'] ?>" />
                              </a>
                           <?php
                           } while ($row_rsTP = $query_rsTP->fetch());
                        } else {
                           ?>
                           <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                              <h2>Sorry No media found</h2>
                           </div>
                        <?php
                        }
                        ?>
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
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.0.0-beta.3/lightgallery.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.0.0-beta.3/plugins/zoom/lg-zoom.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.0.0-beta.3/plugins/fullscreen/lg-fullscreen.umd.js"></script>


<script>
   $(document).ready(function() {
      const container = document.getElementById("gallery-container");
      lightGallery(container, {
         speed: 500,
         plugins: [lgZoom, lgFullscreen]
      });
   });
</script>