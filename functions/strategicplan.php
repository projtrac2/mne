<?php
// get strategic plan 
function get_strategic_plans(){
    global $db;
    $query_rsStrategicPlan = $db->prepare("SELECT * FROM tbl_strategicplan");
    $query_rsStrategicPlan->execute();
    $row_rsStrategicPlan = $query_rsStrategicPlan->fetchAll();
    $totalRows_rsStrategicPlan = $query_rsStrategicPlan->rowCount();
    if($totalRows_rsStrategicPlan > 0){
        return $row_rsStrategicPlan; 
    }else{
        return false;
    }
}

// get strategic plan 
function get_splan($stplan){
    global $db;
    $query_rsStrategicPlan = $db->prepare("SELECT * FROM tbl_strategicplan WHERE id='$stplan'");
    $query_rsStrategicPlan->execute();
    $row_rsStrategicPlan = $query_rsStrategicPlan->fetch();
    $totalRows_rsStrategicPlan = $query_rsStrategicPlan->rowCount();
    if($totalRows_rsStrategicPlan > 0){
        return $row_rsStrategicPlan; 
    }else{
        return false;
    }
}

// get strategic plan 
function get_strategic_plan(){
    global $db;
    $query_rsStrategicPlan = $db->prepare("SELECT * FROM tbl_strategicplan WHERE current_plan=1 LIMIT 1");
    $query_rsStrategicPlan->execute();
    $row_rsStrategicPlan = $query_rsStrategicPlan->fetch();
    $totalRows_rsStrategicPlan = $query_rsStrategicPlan->rowCount();
    if($totalRows_rsStrategicPlan > 0){
        return $row_rsStrategicPlan; 
    }else{
        return false;
    }
}

// get current strategic plan year 
function get_current_year(){
    $strategic_plan = get_strategic_plan(); 
    $years = $strategic_plan['years']; 
    $starting_year = $strategic_plan['starting_year'];
    $ending_year = $starting_year + $years; 
    $currentYear = '';
    $month =  date('m');

    if ($month  < 7) {
        $currentYear =  date("Y") - 1;
    } else {
        $currentYear =  date("Y");
    }

    return $currentYear; 
}

// get strategic objectives 
function get_strategic_objectives(){
    global $db;
    $query_rsStrategicObjectives = $db->prepare("SELECT o.id, o.objective FROM tbl_key_results_area  k JOIN tbl_strategicplan p ON p.id = k.spid INNER JOIN tbl_strategic_plan_objectives o ON o.kraid = k.id WHERE p.current_plan=1 ");
    $query_rsStrategicObjectives->execute();
    $row_rsStrategicObjectives = $query_rsStrategicObjectives->fetchAll();
    $totalRows_rsStrategicObjectives = $query_rsStrategicObjectives->rowCount();
    if($totalRows_rsStrategicObjectives > 0){
        return $row_rsStrategicObjectives; 
    }else{
        return false;
    }
}

// get current strategic plan 
function get_current_strategic_plan(){
    global $db; 
    $query_currentplan = $db->prepare("SELECT * FROM tbl_strategicplan WHERE current_plan=1 ");
	$query_currentplan->execute();
	$row_currentplan = $query_currentplan->fetch();
    $totalRows_rscurrentplan = $query_currentplan->rowCount();

    if($totalRows_rscurrentplan > 0){ 
        return $row_currentplan; 
    }else{
        false; 
    }
}

// get strategic plan with id 
function get_strategic_plan_by_id($id){
    global $db;
    $query_rsStrategicPlan = $db->prepare("SELECT * FROM tbl_strategicplan WHERE id='$id' ");
    $query_rsStrategicPlan->execute();
    $row_rsStrategicPlan = $query_rsStrategicPlan->fetch();
    $totalRows_rsStrategicPlan = $query_rsStrategicPlan->rowCount();
    if($totalRows_rsStrategicPlan > 0){
        return $row_rsStrategicPlan;
    }else{
        return false;
    }
}

function get_strategic_plan_kras($stplan){
    global $db;
    $query_KRA =  $db->prepare("SELECT * FROM tbl_key_results_area WHERE spid='$stplan' ORDER BY id ASC");
    $query_KRA->execute();
	$row_KRA = $query_KRA->fetchAll();
    $totalRows_KRA = $query_KRA->rowCount();

    if($totalRows_KRA > 0){
        return $row_KRA; 
    }else{
        return false;
    }
}

function get_kra($kraid){
    global $db;
    $query_item = $db->prepare("SELECT * FROM tbl_key_results_area WHERE id = '$kraid' LIMIT 1");
    $query_item->execute();
    $row_item = $query_item->fetch();
    $rows_count = $query_item->rowCount(); 
    if($rows_count > 0){
        return $row_item; 
    }else{
        return false;
    }
}

function get_kra_strategic_objectives($kraid){
    global $db;
    $query_obj =  $db->prepare("SELECT * FROM tbl_strategic_plan_objectives WHERE kraid = '$kraid' ORDER BY id");
    $query_obj->execute();		
    $row_obj = $query_obj->fetchAll();
    $totalRows_obj = $query_obj->rowCount();

    if($totalRows_obj > 0){
        return $row_obj; 
    }else{
        return false;
    }
}

function get_strategic_objective($id){
    global $db;
    $query_obj =  $db->prepare("SELECT * FROM tbl_strategic_plan_objectives WHERE id = '$id' LIMIT 1");
    $query_obj->execute();		
    $row_obj = $query_obj->fetch();
    $totalRows_obj = $query_obj->rowCount();

    if($totalRows_obj > 0){
        return $row_obj; 
    }else{
        return false;
    }
}

function get_strategic_plan_strategic_objective_details($objid){
    global $db;
    $query_rsObjective = $db->prepare("SELECT p.starting_year, p.years, o.id, o.objective FROM tbl_key_results_area  k JOIN tbl_strategicplan p ON p.id = k.spid INNER JOIN tbl_strategic_plan_objectives o ON o.kraid = k.id WHERE o.id = '$objid' AND p.current_plan=1 ");
    $query_rsObjective->execute();
    $row_obj = $query_rsObjective->fetch();
    $totalRows_obj = $query_rsObjective->rowCount();

    if($totalRows_obj > 0){
        return $row_obj; 
    }else{
        return false;
    } 
}

function get_strategic_objectives_strategy($objid){
    global $db;
    $query_strategy =  $db->prepare("SELECT * FROM tbl_objective_strategy where objid='$objid'");
    $query_strategy->execute();		
    $row_strategy = $query_strategy->fetchAll();
    $totalRows_strategy = $query_strategy->rowCount();

    if($totalRows_strategy > 0){
        return $row_strategy; 
    }else{
        return false;
    }
}

function get_strategic_plan_objectives($planid){
    global $db;
    $query_rsObjective = $db->prepare("SELECT o.*, k.kra, plan FROM tbl_strategic_plan_objectives o INNER JOIN tbl_key_results_area k ON k.id = o.kraid inner join tbl_strategicplan p on p.id=k.spid where p.id=:planid");	
	$query_rsObjective->execute(array(":planid" => $planid));
    $row_Objective = $query_rsObjective->fetchAll();
	$totalRows_rsObjective = $query_rsObjective->rowCount();

    if($totalRows_rsObjective > 0){
        return $row_Objective; 
    }else{
        return false;
    }
}

function strategic_objective_programs($objid){
    global $db;
    $query_objPrograms = $db->prepare("SELECT * FROM tbl_programs WHERE strategic_obj='$objid'");	
    $query_objPrograms->execute();  
    $row_rsObjprograms = $query_objPrograms->fetchAll();
    $totalRows_objPrograms = $query_objPrograms->rowCount();

    if($totalRows_objPrograms > 0){
        return $row_rsObjprograms; 
    }else{
        return false;
    }
}

function get_strategic_plan_yearly_baseline($ind, $year){
    global $db;
	
	//$year = $year ;
	$enddate = $year."-06-30";
	$achieved = 0;
    $basevalue = 0;

	$query_baseline =  $db->prepare("SELECT SUM(value) as baseline FROM tbl_indicator_output_baseline_values WHERE indid='$ind'");
	$query_baseline->execute();
	$row_baseline = $query_baseline->fetch();
	
	if($row_baseline){
		$basevalue = $row_baseline["baseline"];
		if ($basevalue > 0) {
			$basevalue = $basevalue;
		} 
	}
	
    $query_opid =  $db->prepare("SELECT d.id as opid FROM tbl_project_details d INNER JOIN tbl_progdetails g ON g.id = d.outputid WHERE g.indicator='$ind'");
    $query_opid->execute();
	$totalRows_achieved = $query_opid->rowCount();
	
	while($row_opid = $query_opid->fetch()){
		$opid = $row_opid["opid"];
		$query_achieved =  $db->prepare("SELECT sum(achieved) AS achieved FROM tbl_monitoringoutput WHERE output_id='$opid' AND date_created <=  '" . $enddate . "'");
		$query_achieved->execute();
		$row_achieved = $query_achieved->fetch();
		$opachieved = $row_achieved["achieved"];
		$achieved = $achieved + $opachieved;
	}
	
	$baseline = $basevalue + $achieved;

    return number_format($baseline);
}

function get_strategic_plan_yearly_target($ind, $year){
    global $db;
	$target = 0;
    $query_target =  $db->prepare("SELECT SUM(target) AS year_target FROM tbl_progdetails WHERE indicator='$ind' AND year='$year'");
    $query_target->execute();
	$row_target = $query_target->fetch();
    $totalRows_target = $query_target->rowCount();

    if($totalRows_target > 0){
		$target = $row_target["year_target"];
	}
    
	return $target; 
}

function get_strategic_plan_yearly_achieved($ind, $year){
    global $db;
	$achieved = 0;
	$eyear = $year + 1;
	$startdate = $year."-07-01";
	$enddate = $eyear."-06-30";
	
    $query_opid =  $db->prepare("SELECT d.id as opid FROM tbl_project_details d INNER JOIN tbl_progdetails g ON g.id = d.outputid WHERE g.indicator='$ind'");
    $query_opid->execute();
	$totalRows_achieved = $query_opid->rowCount();
	
	while($row_opid = $query_opid->fetch()){
		$opid = $row_opid["opid"];
		$query_achieved =  $db->prepare("SELECT sum(achieved) AS achieved FROM tbl_monitoringoutput WHERE output_id='$opid' AND (date_created >=  '" . $startdate . "' AND  date_created <=  '" . $enddate . "')");
		$query_achieved->execute();
		$row_achieved = $query_achieved->fetch();
		
		$opachieved = 0;
		if($row_achieved){
			$opachieved = $row_achieved["achieved"];
		}
		$achieved = $achieved + $opachieved;
	}

    if($totalRows_achieved > 0){
		return $achieved;
    }else{
        return false;
    }
}


// update strategic plan
function update_strategic_plan(){
    global $db;
    $query_updatesp = $db->prepare("SELECT * FROM tbl_strategicplan");
    $query_updatesp->execute();
	
    while($row_updatesp = $query_updatesp->fetch()){
		$stid = $row_updatesp['id'];
		$starting_year = $row_updatesp['starting_year'];
		$active = $row_updatesp['current_plan'];
		$years = $row_updatesp['years'];
		$cyear = date("Y");
		$eyear = ($starting_year + $years) - 1;
										
		if($starting_year <= $cyear && $eyear >= $cyear && $active == 0){		
			$update_query = $db->prepare("UPDATE tbl_strategicplan set current_plan = 1 where id='$stid'");
			$update_query->execute();
		}
	}
	return true;
}
