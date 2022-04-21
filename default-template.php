<?php
if (isset($_POST['submit'])) {
    $projid = 291;
    $mstart = $_POST['mstart'];
    $mend = $_POST['mend'];
    $tstart = $_POST['tstart'];
	$ml = $_POST['msid'];
	$tk = $_POST['tkid'];
    $tend = $_POST['tend'];
	
    if (!empty($mstart) && !empty($mend)) {
        $total = count($mstart);
        for ($i = 0; $i < $total; $i++) {
            $sql = $pdo->prepare("INSERT INTO tbl_milestone_d (projid, mluniqueid, MStart, MEnd) VALUES (:projid, :mluniqueid, :mstart, :mend) ");
            $result = $sql->execute(array(':projid' => $projid, ':mluniqueid' => $ml[$i], ':mstart' => $mstart[$i], ':mend' => $mend[$i]));
        }
		
		if (!empty($tstart) && !empty($tend)) {
			$count = count($tstart);
			for ($j = 0; $j < $count; $j++) {
				$query_mluniqueid = $db->prepare("SELECT mid FROM tbl_milestone where mluniqueid = ".$tk[$j]);
				$query_mluniqueid->execute();		
				$row_mluniqueid = $query_mluniqueid->fetch();
				$mid = $row_mluniqueid['mid'];
				
				$sql = $pdo->prepare("INSERT INTO tbl_task_d(mid, TStart, TEnd) VALUES ( :mid, :tstart, :tend)");
				$result = $sql->execute(array(":mid" => $mid, ':tstart' => $tstart[$j], ':tend' => $tend[$j]));
				if ($result) {
					// echo "All was a success";
				} else {
					// echo "All was not a success at all";
				}
			}
		}
    }
}
?>