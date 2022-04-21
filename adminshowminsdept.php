<?php
	//include_once 'projtrac-dashboard/resource/session.php';
	include_once 'projtrac-dashboard/resource/Database.php';
	include_once 'projtrac-dashboard/resource/utilities.php';

    if($_POST["action"] == "level")
    {
		$output = array();
		$statement = $db->prepare("SELECT level FROM tbl_countyadmindesignation WHERE id = '".$_POST["id"]."' LIMIT 1");
		$statement->execute();
		while($row = $statement->fetch())
		{
			$output[] = array('level'=>$row["level"]);
		}
		echo json_encode($output);
    }

    if($_POST["action"] == "ecosystem")
    {
        $output = array();
        $statement = $db->prepare("SELECT * FROM tbl_state WHERE parent = '".$_POST["stid"]."'");
        $statement->execute();
        echo '<option value="">... Select Ecosystem ...</option>';
        while($row = $statement->fetch())
        {
			echo '<option value="'. $row['id'] .'">'. $row['state'].'</option>';
        }
    }

    if($_POST["action"] == "station")
    {
        $output = array();
        $statement = $db->prepare("SELECT * FROM tbl_state WHERE parent = '".$_POST["stid"]."'");
        $statement->execute();
        echo '<option value="">... Select Forest Station ...</option>';
        while($row = $statement->fetch())
        {
			echo '<option value="'. $row['id'] .'">'. $row['state'].'</option>';
        }
    }
