<?php

include_once "controller.php";

if (isset($_POST['getChecklist'])) {
	$projid = $_POST['projid'];
	$ckbody = '';
  
	$query_rsOutputs = $db->prepare("SELECT p.output as  output, o.id as opid, p.indicator FROM tbl_project_details o INNER JOIN tbl_progdetails p ON p.id = o.outputid WHERE projid=:projid");
	$query_rsOutputs->execute(array(":projid" => $projid));
	$row_rsOutputs = $query_rsOutputs->fetch();
	$totalRows_rsOutputs = $query_rsOutputs->rowCount();
	
	$ckbody .= '        
	<div class="table-responsive">
		<table class="table table-bordered table-striped table-hover  " id="">
			<thead>
				<tr class="bg-light-blue">
					<th style="width:4%">#</th>
					<th style="width:48%" colspan="2">Output</th>
					<th style="width:48%" colspan="2">Indicator </th>
				</tr>
			</thead>
			<tbody>';

				if ($totalRows_rsOutputs > 0) {
					$Ocounter = 0;
					do {
						$Ocounter++;
						$outputid = $row_rsOutputs['opid'];
						$indid = $row_rsOutputs['indicator'];

						$query_rsMilestones = $db->prepare("SELECT *  FROM tbl_milestone WHERE projid=:projid AND outputid=:outputid ");
						$query_rsMilestones->execute(array(":projid" => $projid, ":outputid" => $outputid));
						$row_rsMilestones = $query_rsMilestones->fetch();
						$totalRows_rsMilestones = $query_rsMilestones->rowCount();

						//get indicator
						$query_rsIndicator = $db->prepare("SELECT *  FROM tbl_indicator WHERE indid='$indid' ");
						$query_rsIndicator->execute();
						$row_rsIndicator = $query_rsIndicator->fetch();
						$totalRows_rsIndicator = $query_rsIndicator->rowCount();
						
						$ckbody .= '<tr style="background-color:#eff9ca">
							<td>'.$Ocounter.'</td>
							<td colspan="2">'.$row_rsOutputs["output"].'</td>
							<td colspan="2">'.$row_rsIndicator["indicator_name"].'</td>
						</tr>
						<tr class="collapse output'.$outputid.'" data-parent="outputs'.$outputid.'" style="background-color:#FF9800; color:#FFF">
							<th style="width: 4%">#</th>
							<th colspan="4" style="width: 96%">Milestone Name</th>
						</tr>';
						if ($totalRows_rsMilestones > 0) {
							$mcounter = 0;
							do {
								$mcounter++;
								$milestone = $row_rsMilestones['msid'];
								$query_rsTasks = $db->prepare("SELECT *  FROM tbl_task WHERE projid='$projid' and msid='$milestone' ");
								$query_rsTasks->execute();
								$row_rsTasks = $query_rsTasks->fetch();
								$totalRows_rsTasks = $query_rsTasks->rowCount();
							
								$ckbody .= '<tr style="background-color:#CDDC39">
									<td>'.$Ocounter . "." . $mcounter.'</td>
									<td colspan="4">'.$row_rsMilestones["milestone"].'</td>
								</tr>
								<tr style="background-color:#4bab65">
									<th style="width: 4%">#</th>
									<th colspan="4" style="width: 96%">Task Name</th>
								</tr>';
								 
								$tcounter = 0;
								if ($totalRows_rsTasks > 0) {
									do {
										$tcounter++;
										$taskid = $row_rsTasks['tkid'];
										$query_rsChecklist = $db->prepare("SELECT *  FROM tbl_project_monitoring_checklist WHERE  taskid='$taskid'");
										$query_rsChecklist->execute();
										$row_rsChecklist = $query_rsChecklist->fetch();
										$totalRows_rsChecklist = $query_rsChecklist->rowCount(); 
										
										$ckbody .= '<tr style="background-color:#FFF">
											<td style="background-color:#4bab65">'.$Ocounter . "." . $mcounter . "." . $tcounter.'</td>
											<td COLSPAN=4>'.$row_rsTasks["task"].'</td>';
												
											$ckbody .= '
										</tr>';
						 
										$ckbody .= '<tr style="background-color:#b8f9cb">
											<th style="width: 4%; background-color:#4bab65"></th>
											<th style="width: 4%; background-color:#b8f9cb">#</th>
											<th colspan="3" style="width: 92%">Task Checklist Questions</th> 
										</tr>';
										 
										$rowno = 0; 
										do {
											$checklist =  $row_rsChecklist['name'];
											$rowno++;
											
												$ckbody .= '<tr>
													<td style="width: 4%; background-color:#4bab65">
													</td> 
												<td align="center" style="width: 4%; background-color:#b8f9cb; color:#FFF">'. $Ocounter . "." . $mcounter . "." . $tcounter."." . $rowno . '</td>
													<td colspan="3">
													' . $checklist . '
													</td> 
												</tr>';
										} while ($row_rsChecklist = $query_rsChecklist->fetch());
									} while ($row_rsTasks = $query_rsTasks->fetch());
								}
							} while ($row_rsMilestones = $query_rsMilestones->fetch());
						}
					} while ($row_rsOutputs = $query_rsOutputs->fetch());
				}
				
			$ckbody .= '</tbody>
		</table>
	</div>';
	echo $ckbody;
}