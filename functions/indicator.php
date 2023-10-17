<?php
    // include_once '../assets/projtrac-dashboard/resource/Database.php';

    // get disaggregation types
    function indicator_disaggregation_types(){
        global $db; 
        $query_rsdisaggregation_types = $db->prepare("SELECT * FROM tbl_indicator_disaggregation_types ORDER BY id ASC");
        $query_rsdisaggregation_types->execute();
        $row_rsdisaggregation_types = $query_rsdisaggregation_types->fetchAll();
        $totalRows_rsdisaggregation_types = $query_rsdisaggregation_types->rowCount();

        if($totalRows_rsdisaggregation_types > 0){
            return $row_rsdisaggregation_types; 
        }else{
            return false;
        }
    }

    // get disaggregation types
    function indicator_disaggregation_type($type_id){
        global $db; 
        $query_rsdisaggregation_types = $db->prepare("SELECT * FROM tbl_indicator_disaggregation_types ORDER BY id ASC");
        $query_rsdisaggregation_types->execute();
        $row_rsdisaggregation_types = $query_rsdisaggregation_types->fetchAll();
        $totalRows_rsdisaggregation_types = $query_rsdisaggregation_types->rowCount();

        if($totalRows_rsdisaggregation_types > 0){
            return $row_rsdisaggregation_types; 
        }else{
            return false;
        }
    }

    // get output indicators 
    function get_output_indicators(){
        global $db; 
        // get output indicators 
        $query_rsOutputIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='OUTPUT' AND indicator_type=2 AND active = '1' ORDER BY indid");
        $query_rsOutputIndicators->execute();
        $row_rsOutputIndicators = $query_rsOutputIndicators->fetchAll();
        $totalRows_rsOutputIndicators = $query_rsOutputIndicators->rowCount();
        if($totalRows_rsOutputIndicators > 0){
            return $row_rsOutputIndicators; 
        }else{
            return false;
        }
    }

     // get output indicators 
     function get_output_indicators_by_department($deptid){
        global $db; 
        // get output indicators 
        $query_rsOutputIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='OUTPUT' AND indicator_type = 2 AND active = '1' AND  indicator_sector= '$deptid' ORDER BY indid");
        $query_rsOutputIndicators->execute();
        $row_rsOutputIndicators = $query_rsOutputIndicators->fetchAll();
        $totalRows_rsOutputIndicators = $query_rsOutputIndicators->rowCount();
        if($totalRows_rsOutputIndicators > 0){
            return $row_rsOutputIndicators; 
        }else{
            return false;
        }
    }

    function get_outcome_indicators(){
        global $db; 
        // getoutcome indicators
        $query_rsOutcomeIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='OUTCOME' AND indicator_type=2 AND active = '1' ORDER BY indid");
        $query_rsOutcomeIndicators->execute();
        $row_rsOutcomeIndicators = $query_rsOutcomeIndicators->fetchAll();
        $totalRows_rsOutcomeIndicators = $query_rsOutcomeIndicators->rowCount();
        if($totalRows_rsOutcomeIndicators > 0){
            return $row_rsOutcomeIndicators; 
        }else{
            return false;
        }
    }

    function get_impact_indicators(){
        global $db; 
        // get impact indicators
        $query_rsImpactIndicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='IMPACT' AND indicator_type=2 AND active = '1' ORDER BY indid");
        $query_rsImpactIndicators->execute();
        $row_rsImpactIndicators = $query_rsImpactIndicators->fetchAll();
        $totalRows_rsImpactIndicators = $query_rsImpactIndicators->rowCount();
        if($totalRows_rsImpactIndicators > 0){
            return $row_rsImpactIndicators; 
        }else{
            return false;
        }
    }

    function get_indicator_by_indcode($indcd){
        global $db;  
        $query_rsIndicator = $db->prepare("SELECT indicator_code FROM tbl_indicator WHERE indicator_code = '$indcd'");
        $query_rsIndicator->execute();
        $row_rsIndicator = $query_rsIndicator->fetch();
        $indcount = $query_rsIndicator->rowCount();
        if($indcount > 0){
            return $row_rsIndicator;
        }else{
            return false; 
        }
    }
    
    function get_indicator_by_indid($indcd){
        global $db; 
        $query_rsIndicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid = '$indcd'");
        $query_rsIndicator->execute();
        $row_rsIndicator = $query_rsIndicator->fetch();
        $indcount = $query_rsIndicator->rowCount();
        if($indcount > 0){
            return $row_rsIndicator;
        }else{
            return false; 
        }
    }

    function get_indicator_targets($indid){
        global $db; 
        $query_rsIndicator_objectives = $db->prepare("SELECT * FROM tbl_indicator_strategic_plan_targets  WHERE indicatorid='$indid' ORDER BY fscyear");
        $query_rsIndicator_objectives->execute();
        $row_rsIndicator_objectives= $query_rsIndicator_objectives->fetchAll();
        $indcount = $query_rsIndicator_objectives->rowCount();
        if($indcount > 0){
            return $row_rsIndicator_objectives;
        }else{
            return false;
        }
    }