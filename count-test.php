<?php
	$count = 5;
	for($j = 1; $j = $count; $j++){
		$scorename = "score".$j;
		$scoreid = "ckid".$j;
		$score = $_POST[$scorename];
		$ckid = $_POST[$scoreid];
		
		echo '<div>'.$score.'</div>';
	}
?>