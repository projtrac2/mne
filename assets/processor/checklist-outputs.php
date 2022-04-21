<?php
try{
	include_once "controller.php";
	if(isset($_POST["dept"]) && !empty($_POST["dept"])){
		$deptid = $_POST["dept"];
		
		$query_Outputs = $db->prepare("SELECT indid, indicator_name FROM tbl_indicator where indicator_dept=:deptid");
		$query_Outputs->execute(array(":deptid" => $deptid));	
		$rows_Outputs = $query_Outputs->fetch();
		$count_Outputs = $query_Outputs->rowCount();
		if($count_Outputs > 0){
			echo '
			<option value="" selected="selected" class="selection">....Select Department....</option>';
			do {
				echo '<option value="'.$rows_Outputs['indid'].'">'.$rows_Outputs['indicator_name'].'</option>';
			} while ($rows_Outputs = $query_Outputs->fetch());
		}else{
			echo '
				<option value="" selected="selected" class="selection">No Output Indicator defined for this Department</option>
			';
		}
	}

}catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $result;
}
?>