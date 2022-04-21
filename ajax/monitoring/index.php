<?php 
	//Include database configuration file


include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';
include_once("../../includes/system-labels.php");	if(isset($_POST['outcome_details'])){
		$outcomeindid = $_POST['outcome_id'];
		$query_indicator =  $db->prepare("SELECT * FROM `tbl_indicator` WHERE indid=:indid ");
        $query_indicator->execute(array(":indid" => $outcomeindid));
        $row_indicator = $query_indicator->fetch();
        $total_indicator = $query_indicator->rowCount();

		if($total_indicator > 0){
			$unitid = $row_indicator['indicator_unit'];
			$ocindid = $row_indicator['indid'];
			$outcomeIndicator = $row_indicator['indicator_name'];
			$occalcid = $row_indicator['indicator_calculation_method'];

			$query_outcome_indicator_cal = $db->prepare("SELECT * FROM tbl_indicator_calculation_method WHERE id =:calcid ");
			$query_outcome_indicator_cal->execute(array(':calcid'=>$occalcid));
			$row_outcome_cal = $query_outcome_indicator_cal->fetch();
			$outcome_calc_method = $row_outcome_cal['method'];
	
			$query_Indicator = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id =:unit ");
			$query_Indicator->execute(array(":unit" => $unitid));
			$row = $query_Indicator->fetch();
			$unitofmeasure = $row['unit'];

			echo json_encode(array('success'=>true, "outcomeIndicator"=>$outcomeIndicator, "outcom_calc_method"=>$outcome_calc_method, "outcomeunitofmeasure"=>$unitofmeasure)); 
		}else{
			echo json_encode(array('success'=>false)); 
		} 
	}