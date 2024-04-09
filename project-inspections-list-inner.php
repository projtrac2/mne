<?php 
try {
?>
<div class="body">
	<div class="table-responsive">
		<ul class="nav nav-tabs" style="font-size:14px">
			<li class="active">
				<a data-toggle="tab" href="#home">
					<i class="fa fa-hourglass-half bg-light-blue" aria-hidden="true"></i> Inspections Pending &nbsp;
					<span class="badge bg-light-blue"><?php echo $count_pendinginspections; ?></span>
				</a>
			</li>
			<li>
				<a data-toggle="tab" href="#menu1"><i class="fa fa-check-square-o bg-light-green" aria-hidden="true"></i>Inspections Done &nbsp;<span class="badge bg-light-green"><?php echo $count_inspectionsdone; ?></span></a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="home" class="tab-pane fade in active">
				<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
					<thead>
						<tr class="bg-blue-grey">
							<th style="width:3%">#</th>
							<th style="width:54%">Project</th>
							<th style="width:54%">Output</th>
							<th style="width:20%">Level 3</th>
							<th style="width:20%">Level 4</th>
							<th style="width:20%">Inspector</th>
							<th style="width:12%">Due Date</th>
							<th style="width:12%">Status</th>
							<th style="width:7%">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
							try {
								if ($count_pendinginspections > 0) {
									$nm = 0;
									while ($row_pendinginspections = $query_pendinginspections->fetch()) {
										$nm = $nm + 1;
										$id = $row_pendinginspections['id'];
										$opid = $row_pendinginspections['outputid'];
										$level3id = $row_pendinginspections['level3'];
										$level4id = $row_pendinginspections['level4'];
										$inspector_ids = $row_pendinginspections['officer'];
										$inspduedate = $row_pendinginspections['inspection_date']; 

										$query_rsProjects = $db->prepare("SELECT * FROM tbl_project_details INNER JOIN tbl_projects ON tbl_projects.projid = tbl_project_details.projid WHERE id = :opid");
										$query_rsProjects->execute(array(":opid" => $opid));
										$row_rsProjects = $query_rsProjects->fetch();
										$count_rsProjects = $query_rsProjects->rowCount();
										$projname = $count_rsProjects > 0 ?  $row_rsProjects['projname'] : "";
										$projid = $count_rsProjects > 0 ?  $row_rsProjects['projid'] : "";
										$output = $count_rsProjects > 0 ?  $row_rsProjects['outputid'] : "";

										$query_rsOutput = $db->prepare("SELECT * FROM tbl_progdetails WHERE id = :output");
										$query_rsOutput->execute(array(":output" => $output));
										$row_rsOutput = $query_rsOutput->fetch();
										$count_rsOutput = $query_rsOutput->rowCount();
										$outputName = $count_rsOutput > 0 ?  $row_rsOutput['output'] : 0;

										$query_rsLevel3 = $db->prepare("SELECT * FROM tbl_state WHERE id = :level3id");
										$query_rsLevel3->execute(array(":level3id" => $level3id));
										$row_rsLevel3 = $query_rsLevel3->fetch();
										$count_rsLevel3 = $query_rsLevel3->rowCount();
										$level3 = $count_rsLevel3 > 0 ?  $row_rsLevel3['state'] : 0;

										$level4 = "N/A";
										if($level4id != null){
											$query_rsLevel4 = $db->prepare("SELECT * FROM tbl_indicator_level3_disaggregations WHERE id = :level4id");
											$query_rsLevel4->execute(array(":level4id" => $level4id));
											$row_rsLevel4 = $query_rsLevel4->fetch();
											$count_rsLevel4 = $query_rsLevel4->rowCount();
											$level4 = $count_rsLevel4 > 0 ?  $row_rsLevel4['disaggregations'] : "N/A";
										}

										$query_rsInspector = $db->prepare("SELECT * FROM tbl_projteam2 WHERE ptid = :ptid");
										$query_rsInspector->execute(array(":ptid" => $inspector_ids));
										$row_rsInspector = $query_rsInspector->fetch();
										$count_rsInspector = $query_rsInspector->rowCount();
										$inspector = $count_rsInspector > 0 ?  $row_rsInspector['fullname'] : "";

										$current_date = date("Y-m-d");
										$inspectionstatus ="Pending";
										if ($inspduedate < $current_date) {
											$inspectionstatus = "Inspection Overdue";
										}
										?>
										<tr style="background-color:#eff9ca">
											<td align="center"><?php echo $nm; ?></td>
											<td><?php echo $projname; ?></td>
											<td><?php echo $outputName; ?></td>
											<td><?php echo $level3; ?></td>
											<td><?php echo $level4; ?></td> 
											<td><?php echo $inspector; ?></td>
											<td><?php echo $inspduedate; ?></td>
											<td style="color:<?php echo ($inspectionstatus == "Inspection Overdue") ? 'red' : 'blue'; ?>">
												<strong><?php echo $inspectionstatus; ?></strong>
											</td>
											<td>
												<!-- Single button -->
												<div class="btn-group">
													<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														Options <span class="caret"></span>
													</button>
													<ul class="dropdown-menu">
														<li>
															<a type="button" onclick="getTasks(<?php echo $projid?>, <?php echo $opid?>,<?php echo $level3id?>,<?php echo $level4id?>)"  data-toggle="modal" data-target="#moreInfoModal" id="moreInfoModalBtn" onclick="moreInfo(<?= $progid ?>)"> 
                                                                <i class="glyphicon glyphicon-file"></i> Tasks
                                                            </a>
														</li>
														<?php 
															// if($created_by == $current_user || $role == 'project manager){
																?>
																<li>
																	<a href="reassign-officer?formid=<?= $id ?>" data-toggle="tooltip" data-placement="bottom" title="Reassign project output officer"> 
																		<i class="glyphicon glyphicon-file"></i> Edit
																	</a>
																</li>
																<?php 
															// }
														?>
														
														<?php 
															if ($inspduedate <= $current_date) { 
																?>
																<li>
																	<a href="task-inspection?task_id=<?= $task_id ?>&level3=<?= $level3id ?>&level4=<?= $level4id ?>" data-toggle="tooltip" data-placement="bottom" title="Inspect Tasks For this Project"> 
																		<i class="glyphicon glyphicon-file"></i> Inspect Task
																	</a>
																</li>
																<?php
															} elseif ($inspduedate > $current_date) {
																?>
																<li>
																	<a href="#" data-toggle="tooltip" data-placement="bottom" title="Inspect Tasks For this Project"> 
																		<i class="glyphicon glyphicon-file"></i>Not Due Date
																	</a>
																</li>
																<?php 
															}
															?>
													</ul>
												</div>
											</td>
										</tr>
										<?php
									}
								}
							} catch (PDOException $ex) {
								$result = flashMessage("An error occurred: " . $ex->getMessage());
								print($result);
							}
						?>
					</tbody>
				</table>
			</div>
			<div id="menu1" class="tab-pane fade">
				<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
					<thead>
						<tr class="bg-light-blue">
							<th style="width:3%">#</th>
							<th style="width:23%">Task</th>
							<th style="width:25%">Project</th>
							<th style="width:20%">Level 3</th>
							<th style="width:20%">Level 4</th>
							<th style="width:20%">Inspector</th>
							<th style="width:7%">Score</th>
							<th style="width:10%">Date Inspected</th>
							<th style="width:12%">Report</th>
						</tr>
					</thead>
					<tbody>
					<?php
							try {
								if ($count_pendinginspections > 0) {
									$nm = 0;
									while ($row_pendinginspections = $query_pendinginspections->fetch()) {
										$nm = $nm + 1;
										$task_id = $row_pendinginspections['task_id'];
										$level3id = $row_pendinginspections['level3'];
										$level4id = $row_pendinginspections['level4'];
										$inspector_ids = $row_pendinginspections['inspector_ids'];
										$inspduedate = $row_pendinginspections['inspection_date'];

										$query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE tkid = :tkid");
										$query_rsTasks->execute(array(":tkid" => $task_id));
										$row_rsTasks = $query_rsTasks->fetch();
										$count_rsTasks = $query_rsTasks->rowCount();
										$task = $count_rsTasks > 0 ?  $row_rsTasks['task'] : "";
										$opid = $count_rsTasks > 0 ?  $row_rsTasks['outputid'] : "";

										$query_rsProjects = $db->prepare("SELECT * FROM tbl_project_details INNER JOIN tbl_projects ON tbl_projects.projid = tbl_project_details.projid WHERE id = :opid");
										$query_rsProjects->execute(array(":opid" => $opid));
										$row_rsProjects = $query_rsProjects->fetch();
										$count_rsProjects = $query_rsProjects->rowCount();
										$projname = $count_rsProjects > 0 ?  $row_rsProjects['projname'] : "";
										$projid = $count_rsProjects > 0 ?  $row_rsProjects['projid'] : "";
										$progid = $count_rsProjects > 0 ?  $row_rsProjects['progid'] : "";

										$query_rsLevel3 = $db->prepare("SELECT * FROM tbl_state WHERE id = :level3id");
										$query_rsLevel3->execute(array(":level3id" => $level3id));
										$row_rsLevel3 = $query_rsLevel3->fetch();
										$count_rsLevel3 = $query_rsLevel3->rowCount();
										$level3 = $count_rsLevel3 > 0 ?  $row_rsLevel3['state'] : 0;

										$level4 = "";
										if($level4id != null){
											$query_rsLevel4 = $db->prepare("SELECT * FROM tbl_indicator_level3_disaggregations WHERE id = :level4id");
											$query_rsLevel4->execute(array(":level4id" => $level4id));
											$row_rsLevel4 = $query_rsLevel4->fetch();
											$count_rsLevel4 = $query_rsLevel4->rowCount();
											$level4 = $count_rsLevel4 > 0 ?  $row_rsLevel4['disaggregations'] : 0;
										}else{
											$level4id = 0; 
										}

										$query_rsInspector = $db->prepare("SELECT * FROM tbl_projteam2 WHERE ptid = :ptid");
										$query_rsInspector->execute(array(":ptid" => $inspector_ids));
										$row_rsInspector = $query_rsInspector->fetch();
										$count_rsInspector = $query_rsInspector->rowCount();
										$inspector = $count_rsInspector > 0 ?  $row_rsInspector['fullname'] : "";

										$current_date = date("Y-m-d");
										$inspectionstatus ="Pending";
										if ($inspduedate < $current_date) {
											$inspectionstatus = "Inspection Overdue";
										}
										?>
										<tr style="background-color:#eff9ca">
											<td align="center"><?php echo $nm; ?></td>
											<td><?php echo $task; ?></td> 
											<td><?php echo $projname; ?></td>
											<td><?php echo $level3; ?></td>
											<td><?php echo $level4; ?></td> 
											<td><?php echo $inspector; ?></td>
											<td><?php echo $inspduedate; ?></td>
											<td style="color:<?php echo ($inspectionstatus == "Inspection Overdue") ? 'red' : 'blue'; ?>">
												<strong><?php echo $inspectionstatus; ?></strong>
											</td>
											<td>
												<!-- Single button -->
												<div class="btn-group">
													<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														Options <span class="caret"></span>
													</button>
													<ul class="dropdown-menu">
														<li>
															<a href="task-inspection-report?task_id=<?= $task_id ?>&level3=<?= $level3id ?>&level4=<?= $level4id ?>" data-toggle="tooltip" data-placement="bottom" title="Inspect Tasks For this Project"> 
																<i class="glyphicon glyphicon-file"></i> Inspect Task
															</a>
														</li>
													</ul>
												</div>
											</td>
										</tr>
							<?php
									}
								}
							} catch (PDOException $ex) {
								$result = flashMessage("An error occurred: " . $ex->getMessage());
								print($result);
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


<!-- Start Item more -->
<div class="modal fade" tabindex="-1" role="dialog" id="moreInfoModal">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#03A9F4">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info" style="font-size:24px"></i> Program More Information</h4>
            </div>
            <div class="modal-body" id ="moreinfo">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>#</th>
								<th>Task</th>
								<th>Inspection Status</th>
							</tr>
						</thead>
						<tbody id="tasks">
							
						</tbody>
					</table>
				</div>
            </div>
            <div class="modal-footer">
                <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Close</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Item more --> 

<?php 

} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}

?>

<script>
	const getTasks = function (projid, outputid, level3, level4) {  
		if(projid != "" && outputid !=""){
			$.ajax({
				type: "post",
				url: "ajax/inspection/index",
				data: {
					getTasks:"getTasks",
					projid:projid,
					outputid:outputid,
					level3:level3,
					level4:level4,
				},
				dataType: "json",
				success: function (response) {
					if(response.success){
						$("#tasks").html(response.tasks);
					}else{
						console.log("Could not find the required tasks");
					}
				}
			});
		}
	}
</script>