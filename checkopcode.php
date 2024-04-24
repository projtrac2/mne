<?php
//Include database configuration file
include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

if(isset($_POST["code_id"]) && !empty($_POST["code_id"])){
    //Get all state data
	$code = $_POST["code_id"];
    $query = $db->query("SELECT code FROM tbl_outputs WHERE code = '$code'");
	$query->execute();
	$rows = $query->rowCount();
    
    //Display states list
    if($rows > 0){
		echo "<label>&nbsp;</label><div class='alert bg-red alert-dismissible' role='alert' style='height:35px; padding-top:5px'>
                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                This Output Code already exist in the database and you can not use it again: ".$code."
                            </div>";
	}
}

if(isset($_POST["occode_id"]) && !empty($_POST["occode_id"])){
    //Get all state data
	$code = $_POST["occode_id"];
    $query = $db->query("SELECT code FROM tbl_outcomes WHERE code = '$code'");
	$query->execute();
	$rows = $query->rowCount();
    
    //Display states list
    if($rows > 0){
		echo "<label>&nbsp;</label><div class='alert bg-red alert-dismissible' role='alert' style='height:35px; padding-top:5px'>
                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                This Outcome Code already exist in the database and you can not use it again: ".$code."
                            </div>";
	}
}

if(isset($_POST["impcode_id"]) && !empty($_POST["impcode_id"])){
    //Get all state data
	$code = $_POST["impcode_id"];
    $query = $db->query("SELECT code FROM tbl_impacts WHERE code = '$code'");
	$query->execute();
	$rows = $query->rowCount();
    
    //Display states list
    if($rows > 0){
		echo "<label>&nbsp;</label><div class='alert bg-red alert-dismissible' role='alert' style='height:35px; padding-top:5px'>
                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                This Impact Code already exist in the database and you can not use it again: ".$code."
                            </div>";
	}
}
?>