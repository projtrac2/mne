<?php

function get_funding_type(){
    global $db; 
    $query_rsFunding_type =  $db->prepare("SELECT * FROM tbl_funding_type WHERE status= 1");
    $query_rsFunding_type->execute();
    $row_rsFunding_type = $query_rsFunding_type->fetchAll();
    $totalRows_rsFunding_type = $query_rsFunding_type->rowCount();

    if($totalRows_rsFunding_type > 0){
        return $row_rsFunding_type; 
    }else{
        return false;
    }
}