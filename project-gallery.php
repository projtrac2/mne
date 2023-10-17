<?php
require('includes/head.php');
if ($permission) {
	try {
		$decode_photoid = (isset($_GET['photo']) && !empty($_GET["photo"])) ? base64_decode($_GET['photo']) : "";
		$photoid_array = explode("projid54321", $decode_photoid);
		$photoid = $photoid_array[1];

		$query_photo = $db->prepare("SELECT projid, filename, floc, reason FROM tbl_files WHERE fid=:photoid");
		$query_photo->execute(array(":photoid" => $photoid));
		$row_photo = $query_photo->fetch();
		if ($row_photo) {
			$projid = $row_photo['projid'];
			
			$query_project = $db->prepare("SELECT projname FROM tbl_projects WHERE projid=:projid");
			$query_project->execute(array(":projid" => $projid));
			$row_project = $query_project->fetch();
			$projname = $row_project['projname'];
			$projectid = base64_encode("projid54321{$projid}"); 
		}
	} catch (PDOException $ex) {
		$result = flashMessage("An error occurred: " . $ex->getMessage());
	}
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
					  <input type="button" VALUE="Go Back to Project Media" class="btn btn-warning pull-right" onclick="location.href='project-media.php?proj=<?=$projectid?>'" id="btnback">
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
							if ($row_photo) {
								$filename = $row_photo['filename'];
								$filepath = $row_photo['floc'];
								$filepurpose = $row_photo['reason'];
								?>
								<div class="gallery-container d-flex " id="gallery-container" align="center">
									<a data-lg-size="1443-1329" class="gallery-item" data-src="<?= $filepath ?>" data-sub-html="<h4>Photo by - <a href='#' >Uasin Gishu County </a></h4><p> Uasin Gishu County - <a href='#'><?= $filepurpose ?></a></p>">
										 <img class="img-fluid" src="<?= $filepath ?>" style="width:300px; height:300px; margin-bottom:0px"/>
									</a>
									<H4> CLICK THE IMAGE TO EXPAND FOR MORE DETAILS</H4>
								</div>
								<?php
							} else {
							?>
								<h4 class="text-warning text-center text-danger">Sorry the photo does not exist!!</h4>
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