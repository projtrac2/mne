<?php

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

try{
	if(isset($_POST['opid']) && !empty($_POST['opid'])) 
	{
		$outputid = $_POST["opid"];
		$query_output = $db->prepare("SELECT output, indname FROM tbl_outputs o inner join tbl_indicator i on i.indid=o.indicator WHERE opid='$outputid'");
		$query_output->execute();
		$row_output = $query_output->fetch();
		$outputname = $row_output["output"];
		$outputindicator = $row_output["indname"];
	
		$baselinedetails = "";
		$baselinedetails = $baselinedetails.'
		<div class="col-md-8 form-input">
			<label><font color="#174082">Output : </font></label>
			<input type="text" class="form-control" value="'.$outputname.'" readonly>
		</div> <!-- /form-group-->     
		<div class="col-md-4 form-input">
			<label for="editStatus"><font color="#174082">Indicator  : </font></label>
			<input type="text" class="form-control" value="'.$outputindicator.'" readonly>
		</div> <!-- /form-group-->	

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:-5px" id="baselineDetails">
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-hover" style="width:100%">
					<thead>
						<tr id="colrow">
							<th width="4%"><strong id="colhead">#</strong></th>
							<th width="76%">Location</th>
							<th width="20%">Base Value</th>
						</tr>
					</thead>
					<tbody>';
						$query_location_level1 =  $db->prepare("SELECT opid, s.id, state FROM tbl_state s inner join tbl_output_details o on o.level1=s.id WHERE opid='$outputid' GROUP BY s.id ORDER BY o.id ASC");
						$query_location_level1->execute();
						
						$nm = 0;
						while($rows_level1 = $query_location_level1->fetch()){
							$nm++;
							$opid = $rows_level1["opid"];
							$stateid = $rows_level1["id"];
							$level1 = $rows_level1["state"];
							
							$query_level1_basevalue =  $db->prepare("SELECT sum(basevalue) as baseline FROM tbl_state s inner join tbl_output_details o on o.level1=s.id WHERE  opid='$outputid' and level1='$stateid'");
							$query_level1_basevalue->execute();
							$row_level1_basevalue = $query_level1_basevalue->fetch();
							$baseline = $row_level1_basevalue["baseline"];
							
							$baselinedetails = $baselinedetails.'
							<tr style="background-color:#607D8B; color:#FFF">
								<td>'.$nm.'</td>
								<td>'.$level1.' '.$level1label.'</td>
								<td><input type="number" value="'.$baseline.'" class="form-control" readonly></td>
							</tr>';

							$query_location_level2 =  $db->prepare("SELECT opid, s.id, state FROM tbl_state s inner join tbl_output_details o on o.level2=s.id WHERE o.opid='$opid' AND parent='$stateid' GROUP BY s.id ORDER BY o.id ASC");
							$query_location_level2->execute();
							
							$sr = 0;
						
							while($rows_level2 = $query_location_level2->fetch()){
								$sr++;
								$op2id = $rows_level2["opid"];
								$lv2id = $rows_level2["id"];
								$level2 = $rows_level2["state"];
								
								$query_level2_basevalue =  $db->prepare("SELECT sum(basevalue) as baseline FROM tbl_state s inner join tbl_output_details o on o.level2=s.id WHERE o.opid='$outputid' AND level2='$lv2id'");
								$query_level2_basevalue->execute();
								$row_level2_basevalue = $query_level2_basevalue->fetch();
								$level2baseline = $row_level2_basevalue["baseline"];
								
								$baselinedetails = $baselinedetails.'
								<tr style="background-color:#9E9E9E; color:#FFF">
									<td>'.$nm.'.'.$sr.'</td>
									<td>'.$level2.' '.$level2label.'</td>
									<td><input type="number" value="'.$level2baseline.'" class="form-control" readonly></td>
								</tr>';
								
								$query_location_level3 =  $db->prepare("SELECT opid, s.id, state, basevalue FROM tbl_state s inner join tbl_output_details o on o.level3=s.id WHERE o.opid='$outputid' AND level2='$lv2id' ORDER BY o.id ASC");
								$query_location_level3->execute();
								
								$nmb = 0;
								while($rows_level3 = $query_location_level3->fetch()){
									$nmb++;
									$lv3id = $rows_level3["id"];
									$level3 = $rows_level3["state"];
									$level3baseline = $rows_level3["basevalue"];
									
									$baselinedetails = $baselinedetails.'
									<tr>
										<td>'.$nm.'.'.$sr.'.'.$nmb.'</td>
										<td>'.$level3.' '.$level3label.'</td>
										<td><input type="number" value="'.$level3baseline.'" class="form-control" required readonly></td>
									</tr>';						
								}
							}
						}
					$baselinedetails = $baselinedetails.'
					</tbody>
				</table>
			</div>
		</div>';
		echo $baselinedetails;
	}

}catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $result;
}
?>