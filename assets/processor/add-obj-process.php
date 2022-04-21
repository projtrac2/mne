<?php
include_once "controller.php";

try {
    if (isset($_POST['kpiindid'])) {
        $kpiindid = $_POST['kpiindid'];

        $query_unity = $db->prepare("SELECT u.unit, u.description FROM tbl_indicator i inner join tbl_measurement_units u on u.id=i.indicator_unit WHERE indid='$kpiindid'");
        $query_unity->execute();
		$row =  $query_unity->fetch();
        echo $row['description']." (".$row['unit'].")";
    }
	
    if (isset($_POST['kraid'])) {
        $kraid = $_POST['kraid'];

		$query_rsObjective = $db->prepare("SELECT p.starting_year, p.years FROM tbl_strategicplan p INNER JOIN tbl_key_results_area k ON p.id = k.spid WHERE k.id = :kraid AND p.current_plan=1");
		$query_rsObjective->execute(array(":kraid" => $kraid));
		$row_rsStrategicplan = $query_rsObjective->fetch();
		$totalRows_rsStrategicplan = $query_rsObjective->rowCount();

		$startyear = $row_rsStrategicplan['starting_year'];
		$years = $row_rsStrategicplan['years'];
		
		
        $target= '<thead>
            <tr class="bg-grey">';												
				$dispyear  = $startyear;
				for ($i = 0; $i < $years; $i++) {
					$dispyear++;
					$targetyr = $startyear + $i;
					$target .= '<input type="hidden" name="targetyr[]" value="'.$targetyr.'" required="required" />
					<th>'.$targetyr.'/'.$dispyear.' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>';
				}
            $target .= '</tr>
		</thead>
		<tbody id="financier_table_body">
			<tr>';
				for ($j = 0; $j < $years; $j++) {
					$target .= '<td>
						<input type="number" name="target[]" class="form-control" placeholder="Enter target" style="width:100%; border:#CCC thin solid; border-radius: 5px"  required>
					</td>';
				} 
			$target .= '</tr>
			<tr>';
				$thresholdyr = 0;
				for ($k = 0; $k < $years; $k++) {
					$thresholdyr++;
					$target .= '<td align="center"><img src="images/system/rag_values.png" style="clear: both; display: block; margin: 0px 0px 3px 0px;">
						<input data-type="RAG" type="number" name="thresholdyr'.$thresholdyr.'[]" class="form-control" placeholder="Threshold 1" style="width:24%; border:#CCC thin solid; border-radius: 5px"  required>
						<input data-type="RAG" type="number" name="thresholdyr'.$thresholdyr.'[]" class="form-control" placeholder="Threshold 2" style="width:24%; border:#CCC thin solid; border-radius: 5px"  required>
						<input data-type="RAG" type="number" name="thresholdyr'.$thresholdyr.'[]" class="form-control" placeholder="Threshold 3" style="width:24%; border:#CCC thin solid; border-radius: 5px"  required>
						<input data-type="RAG" type="number" name="thresholdyr'.$thresholdyr.'[]" class="form-control" placeholder="Threshold 3" style="width:24%; border:#CCC thin solid; border-radius: 5px"  required>
					</td>';
				} 
			$target .= '</tr>
		</tbody>';
		
        echo $target;
    }
	
    if (isset($_POST['kpiindicator2'])) {
        $objid = $_POST['objectiveid'];
        $kpiind2 = $_POST['kpiindicator2'];
		
        $query_baseline = $db->prepare("SELECT sum(i.basevalue) as basevalue, o.id, o.target FROM tbl_indicator_details i inner join tbl_strategic_plan_objectives o on o.indicator=i.indid WHERE i.indid='$kpiind2' and o.id='$objid'");
        $query_baseline->execute();
		$rows =  $query_baseline->fetch();
		
        $arr = [];
		
		if($rows['basevalue'] > 0){
			$bvalue = $rows['basevalue'];
			$objid = $rows['id'];
			$target = $rows['target'];
		}else{
			$bvalue = 0;
			$objid = 0;
			$target = 0;
		}
		
        $arr =   array("basevalue" => $bvalue, "objid" => $objid,  "octg" => $target);
        echo json_encode($arr);
    }
} catch (PDOException $ex) {
    // $result = flashMessage("An error occurred: " .$ex->getMessage());
    echo $ex->getMessage();
}
