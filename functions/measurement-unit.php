<?php 
// get measurement units 
function get_measurement_units(){
    global $db;
    $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE active = 1");
    $query_rsIndUnit->execute();
    $row_rsIndUnit = $query_rsIndUnit->fetchall();
    $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
    if($totalRows_rsIndUnit > 0){
        return $row_rsIndUnit; 
    }else{
        return false;
    } 
}

// get measurement unit by id
function get_measurement_unit($unit_id){
    global $db;
    $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = $unit_id");
    $query_rsIndUnit->execute();
    $row_rsIndUnit = $query_rsIndUnit->fetch();
    $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
    if($totalRows_rsIndUnit > 0){
        return $row_rsIndUnit;
    }else{
        return false;
    } 
}