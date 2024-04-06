<?php 
try {
	//code...

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

if(isset($_POST['sectorid'])) 
{
	$sectorid = $_POST['sectorid'];
    $query_dep = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent='$sectorid' AND deleted='0'");
    $query_dep->execute();		
    $re = $query_dep->fetchAll(); 
	$cnt = $query_dep->rowCount();
	
	if($cnt != 0){
		echo '<option value="">Select Department</option>';
		foreach($re as $row){
			echo '<option value="'.$row['stid'].'"> '. $row['sector'].'</option>';
		}
	}else{
		echo '<option value="">No Department Defined</option>';
	}
}

if(isset($_POST['deptid']) && !empty($_POST['deptid'])){
    $deptid = $_POST['deptid'];
    $query_project = $db->prepare("SELECT projid, projname FROM tbl_projects WHERE projdepartment=".$deptid);
    $query_project->execute();		
    $rec = $query_project->fetchAll(); 
	$cnt = $query_project->rowCount();
	if($cnt > 0){
		echo '<option value="">Select Project</option>';
		foreach($rec as $row){
			echo '<option value="'.$row['projid'].'"> '. $row['projname'].'</option>';
		}
	}else{
		echo '<option value="" style="color:red">No Projects Defined</option>';
	}
}

} catch (\Throwable $th) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>