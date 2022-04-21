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
	try{
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
	        $user_name =10;

	        $locationName ='';
	        if($location !=0){
	            $query_rsComm =  $db->prepare("SELECT id, name FROM tbl_project_results_level_disaggregation WHERE id='$location'");
	            $query_rsComm->execute();
	            $row_rsComm = $query_rsComm->fetch();
	            $totalRows_rsComm = $query_rsComm->rowCount();
	            $name = $row_rsComm['name'];
	            $locationName ='<li class="list-group-item"><strong>Location: </strong> '. $name .'</li>';
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
	        if($count_row > 0){
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
	}catch (PDOException $ex){
		function flashMessage($data){
			return $data;
		}

	    $result = flashMessage("An error occurred: " .$ex->getMessage());
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

		/* Optional: Makes the sample page fill the window. */
		html,
		body {
		  height: 100%;
		  margin: 0;
		  padding: 0;
		}

		#description {
		  font-family: Roboto;
		  font-size: 15px;
		  font-weight: 300;
		}

		#infowindow-content .title {
		  font-weight: bold;
		}

		#infowindow-content {
		  display: none;
		}

		#map #infowindow-content {
		  display: inline;
		}

		.pac-card {
		  background-color: #fff;
		  border: 0;
		  border-radius: 2px;
		  box-shadow: 0 1px 4px -1px rgba(0, 0, 0, 0.3);
		  margin: 10px;
		  padding: 0 0.5em;
		  font: 400 18px Roboto, Arial, sans-serif;
		  overflow: hidden;
		  font-family: Roboto;
		  padding: 0;
		}

		#pac-container {
		  padding-bottom: 12px;
		  margin-right: 12px;
		}

		.pac-controls {
		  display: inline-block;
		  padding: 5px 11px;
		}

		.pac-controls label {
		  font-family: Roboto;
		  font-size: 13px;
		  font-weight: 300;
		}

		#pac-input {
		  background-color: #fff;
		  font-family: Roboto;
		  font-size: 15px;
		  font-weight: 300;
		  margin-left: 12px;
		  padding: 0 11px 0 13px;
		  text-overflow: ellipsis;
		  width: 400px;
		}

		#pac-input:focus {
		  border-color: #4d90fe;
		}

		#title {
		  color: #fff;
		  background-color: #4d90fe;
		  font-size: 25px;
		  font-weight: 500;
		  padding: 6px 12px;
		}

		#target {
		  width: 345px;
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
								 <div class="header">
										 <?= $results ?>
										 <div class="row clearfix">
												 <div class="col-md-12">
														 <ul class="list-group">
																 <div class="row">
																 <div class="col-md-12">
																	 <li class="list-group-item list-group-item list-group-item-action active">Project Name: <?= $projname ?> </li>
																	 <li class="list-group-item"><strong>Project Code: </strong> <?= $projcode ?> </li>
																	 <li class="list-group-item"><strong>Output: </strong> <?= $outputName ?> </li>
																	 <li class="list-group-item"><strong>Output Unit of Measure: </strong> <?= $opunit ?> </li>
																 </div>
																 <div class="col-md-4"><li class="list-group-item"><strong><?=$level1label?>: </strong> <?= $level1 ?> </li></div>
																 <div class="col-md-4"><li class="list-group-item"><strong><?=$level2label?>: </strong> <?= $level2 ?> </li></div>
																 <div class="col-md-4"><li class="list-group-item"><strong><?=$level3label?>: </strong> <?= $level3 ?> </li></div>
																 <?= $locationName ?>
																 </div>
														 </ul>
												 </div>
												 <div class="col-md-12" id="sbutton">
												 </div>
										 </div>
								 </div>
                  <div class="body">
										<div class="row clearfix">
												<div class="col-md-12">
														<input id="pac-input" class="controls" type="text" placeholder="Search Box"/>
														<div id="map"></div>
												</div>
												<div class="col-md-12">
														<label for="comment">Comments *:</label>
														<textarea name="comment" class="form-control" id="comments" cols="" rows="5" required></textarea>
												</div>
												<input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
												<input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
												<input type="hidden" name="mapid" id="mapid" value="<?= $mapping_id ?>">
												<input type="hidden" name="opid" id="opid" value="<?= $opid ?>">
												<input type="hidden" name="state" id="state" value="<?= $stid ?>">
												<input type="hidden" name="location" id="location" value="<?= $location ?>">
												<div class="col-md-12" align="center">
														<button type="submit" name="submit" onclick="submit_data()" class="btn btn-success">Submit</button>
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
<script src="assets/custom js/add-map-data-manual.js"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8Ii3rrQB5FLivgpihlQPuQSUU6EMc-sQ&callback=initMap&libraries=places&v=weekly">
</script>
