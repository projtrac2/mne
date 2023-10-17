 <?php
//Include database configuration file
include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

if(isset($_POST["op_code"]) && !empty($_POST["op_code"])){
    //Get all state data
	$opid = $_POST["op_id"];
	$code = $_POST["op_code"];
    $query = $db->query("SELECT * FROM tbl_outputs WHERE code = '$code'");
	$query->execute();
	$rows = $query->fetch();
	$rowsnum = $query->rowCount();
	$outputid = $rows["opid"];
    
    //Display states list
    if($rowsnum > 0 && $opid !== $outputid){
		echo "A";
	}
	elseif($rowsnum > 0 && $opid == $outputid){
		echo "B";
	}
	elseif($rowsnum == 0){
		echo "C";
	}
}
?>