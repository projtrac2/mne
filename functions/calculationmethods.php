<?php 
// indicator calculation methods 
function get_indicator_calculation_methods(){
    global $db;
    $query_calcmethod = $db->prepare("SELECT * FROM  tbl_indicator_calculation_method WHERE active = 1");
    $query_calcmethod->execute();
    $row_calcmethod = $query_calcmethod->fetchAll();
    $totalRows_rscalcmethod = $query_calcmethod->rowCount();
    if($totalRows_rscalcmethod > 0){
        return $row_calcmethod; 
    }else{
        return false;
    }
}

// indicator calculation method by id
function get_indicator_calculation_method($calculation_method_id){
    global $db;
    $query_calcmethod = $db->prepare("SELECT * FROM  tbl_indicator_calculation_method WHERE id=:calculationmethod");
    $query_calcmethod->execute(array(":calculationmethod" => $calculation_method_id));
    $row_calcmethod = $query_calcmethod->fetch();
    $totalRows_rscalcmethod = $query_calcmethod->rowCount();
    if($totalRows_rscalcmethod > 0){
        return $row_calcmethod; 
    }else{
        return false;
    }
}