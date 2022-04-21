<?php
// get data sources 
function get_data_sources(){
    global $db;
    $query_dataSource =  $db->prepare("SELECT * FROM tbl_data_source");
    $query_dataSource->execute();
    $row_dataSource = $query_dataSource->fetchAll();
    $totalRows_rsdataSource = $query_dataSource->rowCount();
    if($totalRows_rsdataSource > 0){
        return $row_dataSource; 
    }else{
        return false;
    }
}