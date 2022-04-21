<?php
// get programs
function get_programs($type, $objid = NULL){
    global $db;
    if($type == 1){
        $query_rsprograms = $db->prepare("SELECT * FROM `tbl_programs` WHERE strategic_obj IS NOT NULL ORDER BY `syear` ASC");
    }else if($type == 2) {
        $query_rsprograms = $db->prepare("SELECT * FROM `tbl_programs` WHERE strategic_obj IS NULL ORDER BY `syear` ASC");
    }else if($type == 3 && $objid != NULL){ 
        $query_rsprograms = $db->prepare("SELECT * FROM `tbl_programs` WHERE strategic_obj = '$objid' ORDER BY `syear` ASC");
    }else{
	    $query_rsprograms = $db->prepare("SELECT * FROM `tbl_programs` ORDER BY `syear` ASC");
    }

    $query_rsprograms->execute();
    $row_rsprograms = $query_rsprograms->fetchAll();
    $totalRows_rsprograms = $query_rsprograms->rowCount();

    if($totalRows_rsprograms > 0){
        return $row_rsprograms; 
    }else{
        return false;
    }
}

// get program 
function get_program($progid){
    global $db;
    $query_rsPrograms =  $db->prepare("SELECT * FROM tbl_programs WHERE progid='$progid'");
    $query_rsPrograms->execute();
    $row_rsPrograms = $query_rsPrograms->fetch();
    $totalRows_rsPrograms = $query_rsPrograms->rowCount();
    return ($totalRows_rsPrograms > 0) ?  $row_rsPrograms : false ;
}

// get program funding details
function get_program_funding_details($progid){ 
    global $db;
    $query_funding = $db->prepare("SELECT * FROM tbl_myprogfunding f inner join tbl_funding_type t on t.id=f.sourcecategory WHERE progid = '$progid' AND t.status = 1 ");
    $query_funding->execute();
    $rows_funding = $query_funding->fetchAll();
    $totalrows_funding = $query_funding->rowCount();
    return ($totalrows_funding > 0) ?  $rows_funding : false ;
}

// get programs budget 
function get_program_budget($progid){ 
    global $db;
    $query_rsBudget =  $db->prepare("SELECT SUM(budget) as budget FROM tbl_progdetails WHERE progid='$progid'");
    $query_rsBudget->execute();		
    $row_rsBudget = $query_rsBudget->fetch();
    $totalRows_rsBudget = $query_rsBudget->rowCount(); 
    return ($totalRows_rsBudget > 0) ?  $row_rsBudget['budget'] : 0 ;
}

// get programs budget spent 
function get_program_amount_spent($progid){ 
    global $db;
    $query_projsbudget = $db->prepare("SELECT SUM(projcost) as budget FROM tbl_projects WHERE progid = '$progid'");
    $query_projsbudget->execute();
    $row_projsbudget = $query_projsbudget->fetch();
    $count_projsbudget = $query_projsbudget->rowCount();
    return ($count_projsbudget > 0) ?  $row_projsbudget['budget'] : 0 ;
}

// get projects under a specific program 
function get_program_projects($progid){ 
    global $db;
    $query_rsprojects = $db->prepare("SELECT projid FROM tbl_projects WHERE progid = '$progid'");
    $query_rsprojects->execute();
    $row_rsprojects = $query_rsprojects->fetchAll();
    $count_rsprojects = $query_rsprojects->rowCount();
    return ($count_rsprojects > 0) ? $row_rsprojects  : false; 
}