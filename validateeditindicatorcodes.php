 <?php
//Include database configuration file
include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

if(isset($_POST["ind_code"]) && !empty($_POST["ind_code"])){
    //Get all state data
	$indid = $_POST["ind_id"];
	$code = $_POST["ind_code"];
    $query = $db->query("SELECT * FROM tbl_indicator WHERE indcode = '$code'");
	$query->execute();
	$rows = $query->fetch();
	$rowsnum = $query->rowCount();
	$indicatorid = $rows["indid"];
    
    //Display states list
    if($rowsnum > 0 && $indid !== $indicatorid){
		echo "A";
	}
	elseif($rowsnum > 0 && $indid == $indicatorid){
		echo "B";
	}
	elseif($rowsnum == 0){
		echo "C";
	}
}
?>