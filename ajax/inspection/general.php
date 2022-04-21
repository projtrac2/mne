<?php 



include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';
include_once("../../includes/system-labels.php");

if(isset($_POST['get_locations'])){
   $projid = $_POST['projid'];
   $query_rsdissegragations = $db->prepare("SELECT s.id, s.state FROM tbl_output_disaggregation d INNER JOIN tbl_state s ON s.id = d.outputstate WHERE projid=:projid");
   $result = $query_rsdissegragations->execute(array(":projid" => $projid));
   $row_rsdissegragations = $query_rsdissegragations->fetch();
   $totalRows_rsdissegragations = $query_rsdissegragations->rowCount();
   $data = "";
   if($totalRows_rsdissegragations > 0){
      $data = "<option value=''>Select Location</option>";
      do{
         $data .= "<option value='".$row_rsdissegragations['id']."'>".$row_rsdissegragations['state']."</option>";
      }while($row_rsdissegragations = $query_rsdissegragations->fetch());
   }else{
      $data = "<option value=''>No Locations Found</option>";
   }

   echo $data;
}