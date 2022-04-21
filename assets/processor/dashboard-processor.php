<?php
include_once "controller.php";

try {
   if (isset($_POST['get_level2'])) {
      $getward = $_POST['level1'];
      $data = '<option value="" >Select Ward</option>';
      $query_ward = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:getward");
      $query_ward->execute(array(":getward" => $getward));
      while ($row = $query_ward->fetch()) {
         $projlga = $row['id'];
         $query_rsLocations = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:id");
         $query_rsLocations->execute(array(":id" => $projlga));
         $row_rsLocations = $query_rsLocations->fetch();
         $total_locations = $query_rsLocations->rowCount();
         if ($total_locations > 0) {
            $data .= '<option value="' . $row['id'] . '"> ' . $row['state'] . '</option>';
         }
      }
      echo $data;
   }

   if (isset($_POST['get_level3'])) {
      $getlocation = $_POST['level2'];
      $data = '<option value="" >Select Location</option>';
      $query_loca = $db->prepare("SELECT id, state FROM tbl_state WHERE parent='$getlocation'");
      $query_loca->execute();
      while ($row = $query_loca->fetch()) {
         $data .= '<option value="' . $row['id'] . '"> ' . $row['state'] . '</option>';
      }
      echo $data;
   }


   if (isset($_POST['get_fyto'])) {
      $fyid = $_POST['get_fyto'];
      $data = '<option value="" >Select Financial Year To</option>';
      $query_fy = $db->prepare("SELECT id, year FROM tbl_fiscal_year WHERE id >= :fyid");
      $query_fy->execute(array(":fyid" => $fyid));
      while ($row = $query_fy->fetch()) {
         $yrid = $row['id'];
         $data .= '<option value="' . $yrid . '"> ' . $row['year'] . '</option>';
      }
      echo $data;
   }

   if(isset($_POST['get_dept_projects'])){
      $dept_id = $_POST['get_dept_projects'];
      $data = '<option value="" >Select Project</option>';
      $query_projects = $db->prepare("SELECT * FROM tbl_projects p INNER JOIN tbl_programs g ON g.progid = p.progid  WHERE g.projsector = :dept_id");
      $query_projects->execute(array(":dept_id" => $dept_id));

      while ($row = $query_projects->fetch()) {
         $id = $row['projid'];
         $data .= '<option value="' . $projid . '"> ' . $row['projname'] . '</option>';
      }
      echo $data;
   }

   if(isset($_POST['get_outputs'])){
      $projid = $_POST['get_outputs'];
      $data = '<option value="" >Select Output</option>';
      $query_outputs = $db->prepare("SELECT d.id, g.output FROM tbl_projects p INNER JOIN tbl_progdetails g ON g.progid = p.progid INNER JOIN tbl_project_details d ON d.outputid = g.id   WHERE p.projid = :projid");
      $query_outputs->execute(array(":projid" => $projid));
      while ($row = $query_outputs->fetch()) {
         $id = $row['id'];
         $data .= '<option value="' . $projid . '"> ' . $row['output'] . '</option>';
      }
      echo $data;
   }

} catch (PDOException $ex) {
   // $result = flashMessage("An error occurred: " .$ex->getMessage());
   echo $ex->getMessage();
}
