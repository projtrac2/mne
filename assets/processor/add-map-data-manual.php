<?php

include_once '../projtrac-dashboard/resource/Database.php';
include_once '../projtrac-dashboard/resource/utilities.php';

function disaggregation($outputid)
{
   global $db;
   $query_disaggregation =
      $db->prepare("SELECT o.id,o.mapping_type,d.output,i.indicator_disaggregation,i.indicator_name FROM tbl_project_details as o  INNER JOIN tbl_progdetails  as d ON d.id = o.outputid    INNER JOIN tbl_indicator  as i ON d.indicator = i.indid   WHERE  o.id=:outputid ORDER BY o.id ASC");
   $query_disaggregation->execute(array("outputid" => $outputid));
   $row_disaggregation = $query_disaggregation->fetch();
   $disaggregation = $row_disaggregation['indicator_disaggregation'];
   $outputName = $row_disaggregation['output'];
   $indicator = $row_disaggregation['indicator_name'];
   $mapping_type = $row_disaggregation['mapping_type'];
   $output_details = array("outputName" => $outputName, "disaggregation" => $disaggregation, "outputid" => $outputid, "indicator" => $indicator, "mapping_type" => $mapping_type);
   return $output_details;
}

function get_markers($projid, $outputid)
{
   global $db;
   if ($projid && $outputid) {
      try {
         $query_markers = $db->prepare("SELECT * FROM tbl_markers m where projid=:projid AND  opid=:opid");
         $query_markers->execute(array("projid" => $projid, "opid" => $outputid));
         $total_markers = $query_markers->rowCount();
         if ($total_markers > 0) {
            while ($row = $query_markers->fetch()) {
               $items = array($row['lat'], $row['lng']);
               $data[] = $items;
            }
            return array("msg" => true, "markers" => $data);
         }
      } catch (PDOException $ex) {
         return array("msg" => true, "results" => $ex->getMessage());
      }
   }
}

if (isset($_GET['get_markers'])) {
   if (isset($_GET['projid'])) {
      $results = [];
      $projid = $_GET['projid'];

      if (isset($_GET['outputid']) && !empty($_GET['outputid'])) {
         $outputid = $_GET['outputid'];
         $output_details = disaggregation($outputid);
         $response = get_markers($projid, $outputid);
         if ($response['msg']) {

            $results[] = array("msg" => true, "output_details" => $output_details, "markers" => $response['markers']);
         }
      } else {
         $query_rsOutputs =  $db->prepare("SELECT id FROM tbl_project_details WHERE projid=:projid ORDER BY id ASC");
         $query_rsOutputs->execute(array("projid" => $projid));
         $row_rsOutputs = $query_rsOutputs->fetch();
         $totalRows_rsOutputs = $query_rsOutputs->rowCount();

         if ($totalRows_rsOutputs > 0) {
            do {
               $outputid =  $row_rsOutputs['id'];
               $output_details = disaggregation($outputid);
               $response = get_markers($projid, $outputid);

               if ($response['msg']) {
                  $results[] = array("msg" => true, "output_details" => $output_details, "markers" => $response['markers']);
               }
            } while ($row_rsOutputs = $query_rsOutputs->fetch());
         }
      }
      echo json_encode($results);
   }
}

if (isset($_POST['get_company_coordinates'])) {
   try {
      $query_rsCoordinates =  $db->prepare("SELECT latitude, longitude FROM tbl_company_settings LIMIT 1");
      $query_rsCoordinates->execute();
      $row_rsCoordinates = $query_rsCoordinates->fetch();
      $totalRows_rsCoordinates = $query_rsCoordinates->rowCount();

      if ($totalRows_rsCoordinates > 0) {
         $latitude = $row_rsCoordinates['latitude'];
         $longitude = $row_rsCoordinates['longitude'];
         $results = array("msg" => true, "results" => array("lat" => $latitude, "longitude" => $longitude));
         echo json_encode($results);
      }
   } catch (PDOException $ex) {
      return array("msg" => true, "results" => $ex->getMessage());
   }
}


if (isset($_POST['get_outputs'])) {
   $projid = $_POST['projid'];
   $query_rsOutputs =  $db->prepare("SELECT p.output, d.id FROM `tbl_project_details` dINNER JOIN tbl_progdetails p ON p.id = d.outputid WHERE projid=:projid ORDER BY d.id ASC");
   $query_rsOutputs->execute(array("projid" => $projid));
   $row_rsOutputs = $query_rsOutputs->fetch();
   $totalRows_rsOutputs = $query_rsOutputs->rowCount();

   $option = '<option value="">No Outputs Found</option>';
   $msg = false;
   if ($totalRows_rsOutputs > 0) {
      $option = '<option value="" selected="selected">Select Output</option>';
      $msg = true;
      do {
         $query_markers = $db->prepare("SELECT * FROM tbl_markers m where projid=:projid AND  opid=:opid");
         $query_markers->execute(array(":projid" => $projid, ":opid" => $row_rsOutputs['id']));
         $row_markers = $query_markers->fetch();
         $total_markers = $query_markers->rowCount();
         if ($total_markers > 0) {
            $option .= '<option value="' . $row_rsOutputs['id'] . '">' . $row_rsOutputs['output'] . '</option>';
         }
      } while ($row_rsOutputs = $query_rsOutputs->fetch());
   }
   echo json_encode(array("success" => $msg, "outputs" => $option));
}

if (isset($_POST['submit_coords'])) {
   $coords = json_decode($_POST['coords']);
   $projid = $_POST['projid'];
   $outputid = $_POST['outputid'];
   $comment = $_POST['comment'];
   $location = ($_POST['location'] != "") ? $_POST['location'] : 0;
   $user_name = $_POST['user_name'];
   $current_date = date("Y-m-d");
   $state = $_POST['state'];
   $mapid = $_POST['mapid'];

   $result = [];
   for ($i = 0; $i < count($coords); $i++) {
      $lat = $coords[$i]->lat;
      $lng = $coords[$i]->lng;
      $params = array(
         ':projid' => $projid,
         ":opid" => $outputid,
         ":mapid" => $mapid,
         ":state" => $state,
         ':location' => $location,
         ':lat' => $lat,
         ':lng' => $lng,
         ":comment" => $comment,
         ":mapped_date" => $current_date,
         ":mapped_by" => $user_name
      );
      $sql = $db->prepare("INSERT INTO tbl_markers (projid,opid,mapid,state, location, lat, lng, comment, mapped_date,mapped_by)  VALUES(:projid,:opid,:mapid, :state, :location,:lat, :lng, :comment,:mapped_date,:mapped_by)");
      $result[] = $sql->execute($params);
   }

   if (!in_array(false, $result)) {
      $handler = [];
      $query_rsproject_val = $db->prepare("SELECT *  FROM tbl_project_mapping WHERE projid='$projid' ");
      $query_rsproject_val->execute();
      $row_rsproject_val = $query_rsproject_val->fetch();
      $totalRows_rsproject_val = $query_rsproject_val->rowCount();

      do {
         $query_rsMap = $db->prepare("SELECT *  FROM tbl_markers WHERE mapid=:mapid");
         $query_rsMap->execute(array(":mapid" => $row_rsproject_val['id']));
         $row_rsMap = $query_rsMap->fetch();
         $totalRows_rsMap = $query_rsMap->rowCount();

         if ($totalRows_rsMap > 0) {
            $handler[] = true;
         } else {
            $handler[] = false;
         }
      } while ($row_rsproject_val = $query_rsproject_val->fetch());
 
return;
      if (!in_array(false, $handler)) {
         $mapped = 1;
         $sql = "UPDATE tbl_projects SET mapped=:mapped, projstage=:projstage WHERE projid=:projid";
         $stmt = $db->prepare($sql);
         $result = $stmt->execute(array(':mapped' => $mapped, ':projstage'=>4,  ':projid' => $projid));
         echo json_encode(array("msg" => true));
      } else {
         echo json_encode(array("msg" => true));
      }
   }
}