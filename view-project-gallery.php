<?php
try {
require('includes/head.php');
if ($permission) {
      $count_rsTP  = 0;
      $decode_projid = (isset($_GET['projid']) && !empty($_GET["projid"])) ? base64_decode($_GET['projid']) : header("Location: myprojects");
      $projid_array = explode("projid54321", $decode_projid);
      $projid = $projid_array[1];
      $currentdate = date("Y-m-d");

      $original_projid = $_GET['projid'];

      $query_rsTPList = $db->prepare("SELECT projname, projcode FROM tbl_projects WHERE projid=:projid");
      $query_rsTPList->execute(array(":projid" => $projid));
      $row_rsTPList = $query_rsTPList->fetch();
      $projname = $row_rsTPList['projname'];
      $projcode = $row_rsTPList['projcode'];

      $query_rsTP = $db->prepare("SELECT * FROM tbl_files WHERE projid=:projid");
      $query_rsTP->execute(array(":projid" => $projid));
      $row_rsTP = $query_rsTP->fetch();
      $count_rsTP = $query_rsTP->rowCount();
   
?>
   <style>
      /* .gallery-item { 
         width: 200px;
         padding: 5px;
      } */
      .theme-blue {
         margin-top: -75px;
      }

      .rest-of-body {
         padding-top: 75px;
      }
   </style>
   <!-- Light Gallery Plugin Js -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightgallery@2.0.0-beta.3/css/lightgallery.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightgallery@2.0.0-beta.3/css/lg-zoom.css">
   <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css"> -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightgallery@2.0.0-beta.3/css/lg-fullscreen.css">

   <!-- start body  -->
   <section class="content rest-of-body">
      <div class="container-fluid">
         <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
            <h4 class="contentheader">
               <?= $icon ?>
               <?= $pageTitle ?>

               <div class="btn-group" style="float:right; margin-right:10px">
                  <input type="button" VALUE="Go Back to All Projects Dashboard" class="btn btn-warning pull-right" onclick="location.href='projects.php'" id="btnback">
               </div>
            </h4>
         </div>
         <div class="row clearfix">
            <div class="block-header">
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <h4>
                     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="font-size:15px; background-color:#CDDC39; border:#CDDC39 thin solid; border-radius:5px; margin-bottom:2px; height:25px; padding-top:2px; vertical-align:center">
                        Project Name: <font color="white"><?php echo $projname; ?></font>
                     </div>
                  </h4>
               </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
               <div class="card">
                  <div class="header">
                  </div>
                  <div class="body">
                     <?php
                     if ($count_rsTP > 0) {
                     ?>
                        <div class="gallery-container d-flex " id="gallery-container" align="center">
                           <?php
                           do {
                              $floc_type = $row_rsTP['ftype'];
                              $set_array = array('png', 'jpeg', 'jpg');
                              if (in_array($floc_type, $set_array)) {

                           ?>
                                 <a data-lg-size="1443-1329" class="gallery-item" data-src="<?= $row_rsTP['floc'] ?>" data-sub-html="<h4>Photo by - <a href='#' >Uasin Gishu County </a></h4><p> Uasin Gishu County - <a href='#'><?= $row_rsTP['description'] ?></a></p>">
                                    <img class="img-fluid" src="<?= $row_rsTP['floc'] ?>" />
                                 </a>
                           <?php
                              }
                           } while ($row_rsTP = $query_rsTP->fetch());
                           ?>
                        </div>
                     <?php
                     } else {
                     ?>
                        <h4 class="text-warning text-center text-danger">Sorry this project has no images!!</h4>
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

} catch (PDOException $th) {
   customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
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