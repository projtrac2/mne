<?php 


include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';
include_once("../../includes/system-labels.php");
  if(isset($_POST['delete'])){
    $riskid = $_POST['riskid'];
    $query_rsRisk = $db->prepare("DELETE FROM tbl_projrisk_categories WHERE rskid = '$riskid'");
    if($query_rsRisk->execute()){
        echo json_encode(array('success'=>true));
    }else{
      echo json_encode(array('success'=>false));
    }
  }


  if(isset($_POST['delete_mitigation'])){
    $mtid = $_POST['mtid'];
    $query_rsRisk = $db->prepare("DELETE FROM tbl_projrisk_response WHERE id = '$mtid'");
    if($query_rsRisk->execute()){
        echo json_encode(array('success'=>true));
    }else{
      echo json_encode(array('success'=>false));
    }
  }