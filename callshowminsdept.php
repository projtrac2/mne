<?php
	//include_once 'projtrac-dashboard/resource/session.php';
	include_once 'projtrac-dashboard/resource/Database.php';
	include_once 'projtrac-dashboard/resource/utilities.php';

    if($_POST["action"] == "level")
    {
		$output = array();
		$statement = $db->prepare("SELECT level FROM tbl_pmdesignation WHERE moid = '".$_POST["id"]."' LIMIT 1");
		$statement->execute();
		while($row = $statement->fetch())
		{
			$output[] = array('level'=>$row["level"]);
		}
		echo json_encode($output);
    }

    if($_POST["action"] == "design")
    {
		$query = $db->prepare("SELECT level FROM tbl_pmdesignation WHERE moid = '".$_POST["dnid"]."' LIMIT 1");
		$query->execute();
		$level = $query->fetch();
			
		if($level["level"]==0){
			echo '<option value="0">All Conservancies</option>';
		}else{
			$statement = $db->prepare("SELECT id, state FROM tbl_state WHERE parent IS NULL ORDER BY state ASC");	
			$statement->execute();
			$row_rsSubcounty = $statement->fetch();
			$rowcount = $statement->rowCount();
			if($rowcount>0){
				echo '<option value="">... Select Conservancy ...</option>';
				while($row = $statement->fetch())
				{
					echo '<option value="'. $row['id'] .'">'. $row['state'].'</option>';
				}
			}else{
				echo '<option value="">... Conservancies Not Defined ...</option>';
			}
		}
    }

    if($_POST["action"] == "department")
    {
        $output = array();
        $statement = $db->prepare("SELECT * FROM tbl_sectors WHERE parent = '".$_POST["stid"]."'");
        $statement->execute();
		$rowcount = $statement->rowCount();
		if($rowcount>0){
			echo '<option value="">... Select Department ...</option>';
			while($row = $statement->fetch())
			{
				echo '<option value="'. $row['stid'] .'">'. $row['sector'].'</option>';
			}
		}else{
			echo '<option value="">... Department Not Defined ...</option>';
		}
    }

    if($_POST["action"] == "ward")
    {
        $statement = $db->prepare("SELECT * FROM tbl_state WHERE parent = '".$_POST["wdid"]."'");
        $statement->execute();
		$rowcount = $statement->rowCount();
		if($rowcount>0){
			echo '<option value="">... Select Ecosystem ...</option>';
			while($row = $statement->fetch())
			{
				echo '<option value="'. $row['id'] .'">'. $row['state'].'</option>';
			}
		}else{
			echo '<option value="">... Ecosystem Not Defined ...</option>';
		}
    }

    if($_POST["action"] == "station")
    {
        $statement = $db->prepare("SELECT * FROM tbl_state WHERE parent = '".$_POST["lcid"]."'");
        $statement->execute();
		$rowcount = $statement->rowCount();
		if($rowcount>0){
			echo '<option value="">... Select Station ...</option>';
			while($row = $statement->fetch())
			{
				echo '<option value="'. $row['id'] .'">'. $row['state'].'</option>';
			}
		}else{
			echo '<option value="">... Station Not Defined ...</option>';
		}
    }

?>
 