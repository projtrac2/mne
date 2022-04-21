<?php 
// get departments 
function get_departments(){
    global $db;
    $query_rsIndDept = $db->prepare("SELECT * FROM tbl_sectors WHERE parent='0' and deleted='0'");
    $query_rsIndDept->execute();
    $row_rsIndDept = $query_rsIndDept->fetchAll();
    $totalRows_rsIndDept = $query_rsIndDept->rowCount();

    if($totalRows_rsIndDept > 0){
        return $row_rsIndDept; 
    }else{
        return false;
    }
}

// get department/sector
function get_department_1($inddept){
    global $db; 
    if($inddept){
        $query_rsIndDept = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE deleted='0' and stid = '$inddept'");
        $query_rsIndDept->execute();
        $row_rsIndDept = $query_rsIndDept->fetch();
        $totalRows_rsIndDept = $query_rsIndDept->rowCount();
        if($totalRows_rsIndDept > 0){
           return $row_rsIndDept;
        }else{
            return false; 
        }
    }else{
        return false; 
    }
}

// get department children 
function get_department_child($dept){
    global $db;
    $query_rsdepartment = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent='$dept' AND deleted='0'");
	$query_rsdepartment->execute();
    $row_rsdepartment = $query_rsdepartment->fetchAll();
    $totalRows_rsdepartment = $query_rsdepartment->rowCount();
    if($totalRows_rsdepartment > 0){
        return $row_rsdepartment; 
    }else{
        return false;
    } 
}