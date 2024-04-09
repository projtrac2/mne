<?php 
try {
	//code...

?>
<div class="body">
    <div class="table-responsive">
		<ul class="nav nav-tabs" style="font-size:14px">
			<li class="active">
				<a data-toggle="tab" href="#home"><i class="fa fa-hourglass-half bg-orange" aria-hidden="true"></i> Projects Requiring Evaluation &nbsp;<span class="badge bg-orange"><?php echo $count_evaluation; ?></span></a>
			</li>
			<li>
				<a data-toggle="tab" href="#menu1"><i class="fa fa-file-text-o bg-blue-grey" aria-hidden="true"></i> Forms Ready For Deployment&nbsp;<span class="badge bg-blue-grey"><?php echo $count_evaluation_form; ?></span></a>
			</li>
			<li>
				<a data-toggle="tab" href="#menu2"><i class="fa fa-pencil-square-o bg-light-blue" aria-hidden="true"></i> Active Projects Evaluation&nbsp;<span class="badge bg-light-blue"><?php echo $count_active_evaluation; ?></span></a>
			</li>
			<li>
				<a data-toggle="tab" href="#menu3"><i class="fa fa-check-square-o bg-light-green" aria-hidden="true"></i> Completed Projects Evaluation&nbsp;<span class="badge bg-light-green"><?php echo $count_evaluated; ?></span></a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="home" class="tab-pane fade in active">  
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <tr class="bg-orange">
                            <th style="width:3%">#</th>
                            <th style="width:30%">Project Name</th>
                            <th style="width:25%">Project Location</th>
                            <th style="width:15%">Evaluation Type</th>
                            <th style="width:10%">Due date</th>
							<th style="width:10%">Status</th>
                            <th style="width:7%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php						
						$nm = 0;
						while($row_evaluation = $query_evaluation->fetch())
						{ 
							$nm = $nm + 1;
							$projid = $row_evaluation['projid'];
							$project = $row_evaluation['projname'];
							$projstatus = $row_evaluation['projstatus'];
							$projevaluate = $row_evaluation['projevaluate'];
							$sc = $row_evaluation['projcommunity'];
							$wards = $row_evaluation['projlga'];
							$locs = $row_evaluation['projstate'];
							$evaluationtype = $row_evaluation['evaluation_type'];
							$evalstatus = $row_evaluation["status"];
							$evaltype = $row_evaluation["type"];
							$evaldesc = $row_evaluation["description"];
							$evalid = $row_evaluation['evalid'];
							
							$query_statusname =  $db->prepare("SELECT statusname FROM tbl_status WHERE statusid = '$projstatus'");
							$query_statusname->execute();		
							$row_statusname = $query_statusname->fetch();
							$statusname = $row_statusname["statusname"];
							
							
							$query_evalconf =  $db->prepare("SELECT state FROM tbl_state WHERE id = '$sc'");
							$query_evalconf->execute();		
							$row_evalconf = $query_evalconf->fetch();
							$evaldays = $row_evalconf["days"];
							
							$datecompleteddate = $row_evaluation["projdatecompleted"];
							$evaldate = strtotime($datecompleteddate. " + 0 days");
							$evalduedate = date('Y-m-d', $evaldate);
							$evaluationduedate = date("d M Y", $evaldate);
								
							$query_sc =  $db->prepare("SELECT state FROM tbl_state WHERE id = '$sc'");
							$query_sc->execute();		
							$row_sc = $query_sc->fetch();
							$subcounty = $row_sc["state"];
							
							$query_ward =  $db->prepare("SELECT state FROM tbl_state WHERE id = '$wards'");
							$query_ward->execute();		
							$row_ward = $query_ward->fetch();
							$ward = $row_ward["state"];
							
							$query_locs =  $db->prepare("SELECT state FROM tbl_state WHERE id = '$locs'");
							$query_locs->execute();		
							$row_locs = $query_locs->fetch();
							$loc = $row_locs["state"];

							if($subcounty=="All"){
								$location = $subcounty." ".$level1labelplural."; ".$ward." ".$level2labelplural."; ".$loc." ".$level3labelplural;
							}else{
								$location = $subcounty." ".$level1label."; ".$ward." ".$level2label."; ".$loc." ".$level3label;
							}
							
							$current_date = date("Y-m-d");
							
							if($current_date == $evalduedate){
								$status = "Create Form";
							}elseif($current_date < $evalduedate){
								$status = "Action not ready";
							}elseif($current_date > $evalduedate){
								$status = "Action Overdue";
							}
							
							?>
							<tr style="background-color:#eff9ca">
								<td align="center"><?php echo $nm; ?></td>
								<td><?php echo $project; ?></td>
								<td><?php echo $location; ?></td>
								<td><span data-toggle="tooltip" data-placement="bottom" title="<?php echo "Project Status: ".$statusname; ?>"><?php echo $evaltype; ?></span></td>
								<td><?php echo $evaluationduedate; ?></td>
								<?php if($current_date == $evalduedate){ ?>
									<td style="color:green"><strong><?php echo $status; ?></strong></td>
									<td>
										<div align="center">
											<a href="create-evaluation-form?projid=<?php echo $projid; ?>" alt="Evaluate This Project" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Evaluate This Project"><i class="fa fa-american-sign-language-interpreting fa-2x text-success" aria-hidden="true"></i></a>
										</div>
									</td>
								<?php }elseif($current_date < $evalduedate){ ?>
									<td style="color:blue"><strong><?php echo $status; ?></strong></td>
									<td>
										<div align="center">
											<p><i class="fa fa-hourglass-half fa-2x text-primary" aria-hidden="true"></i></p>
										</div>
									</td>
								<?php }elseif($current_date > $evalduedate){ ?>
									<td style="color:red"><strong><?php echo $status; ?></strong></td>
									<td>
										<div align="center">
										<?php if($projstatus==5){ ?>
											<a href="create-process-evaluation-form?evalid=<?php echo $evalid; ?>" alt="Create Project Process Evaluation Form" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Create Project Process Evaluation Form"><i class="fa fa-american-sign-language-interpreting fa-2x text-success" aria-hidden="true"></i></a>
										<?php }elseif($projstatus==6 && $projevaluate==1){ ?>
											<a href="create-process-evaluation-form?evalid=<?php echo $evalid; ?>" alt="Create Project Process Evaluation Form" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Create Project Process Evaluation Form"><i class="fa fa-american-sign-language-interpreting fa-2x text-success" aria-hidden="true"></i></a>
										<?php }else{ ?>
											<a href="create-rapid-evaluation-form?evalid=<?php echo $evalid; ?>" alt="Create Project Rapid Evaluation Form" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Create Project Rapid Evaluation Form"><i class="fa fa-american-sign-language-interpreting fa-2x text-success" aria-hidden="true"></i></a>
										<?php } ?>
										</div>
									</td>
								<?php } ?>
							</tr>
						<?php
						}
						?> 
					</tbody>
                </table>    
			</div>
			<div id="menu1" class="tab-pane fade"> 
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
				
                    <thead>
                        <tr class="bg-blue-grey">
                            <th style="width:3%">#</th>
                            <th style="width:37%">Project Name</th>
                            <th style="width:20%">Evaluation Form Name</th>
                            <th style="width:15%">Evaluation Type</th>
                            <th style="width:15%">Responsible</th>
                            <th style="width:10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php						
						$nm = 0;
						while($row_evaluation_form = $query_evaluation_form->fetch())
						{ 
							$nm = $nm + 1;
							$projid = $row_evaluation_form['projid'];
							$project = $row_evaluation_form['projname'];
							$evalstartdate = $row_evaluation_form['startdate'];
							$evalenddate = $row_evaluation_form['enddate'];
							$formid = $row_evaluation_form['id'];
							$formname = $row_evaluation_form['form_name'];
							$title = $row_evaluation_form['title'];
							$fullname = $row_evaluation_form['fullname'];
							$responsible = $title.".".$fullname;
							$evaltype = $row_evaluation_form["type"];
							$limittype = $row_evaluation_form["limit_type"];
							$responsesnumber = $row_evaluation_form["responses_number"];
							$evalid = $row_evaluation['evalid'];
							
							$query_latestmonitoring = $db->prepare("SELECT adate FROM `tbl_monitoring` WHERE mid=(SELECT MAX(mid) FROM `tbl_monitoring` WHERE projid='$projid')");
							$query_latestmonitoring->execute();	
							$row_latestmonitoring = $query_latestmonitoring->fetch();
							$latestmonitoring = $row_latestmonitoring["adate"];
							$latestmndate = date("d M Y",strtotime($latestmonitoring));
							
							$query_TotalSub = $db->prepare("SELECT * FROM tbl_project_evaluation_submission WHERE projid ='$projid' AND formid='$formid' GROUP BY submission_code");
							$query_TotalSub->execute();		
							$row_TotalSub = $query_TotalSub->fetch();
							$totalRows_TotalSub = $query_TotalSub->rowCount();	
							?>
							<tr style="background-color:#eff9ca">
								<td style="width:3%" align="center"><?php echo $nm; ?></td>
								<td style="width:27%"><?php echo $project; ?></td>
								<td style="width:20%"><span  data-toggle="tooltip" data-placement="bottom" <?php if($limittype==1){?> title="<?php echo 'Start Date: '.date("d M Y",strtotime($evalstartdate)).'; End Date: '.date("d M Y",strtotime($evalenddate)); ?>" <?php } else {?> title="<?php echo 'Maximum Required Responses: '.$responsesnumber; ?>" <?php } ?> style="color:#2196F3"><?php echo $formname; ?></span></td>
								<td style="width:12%"><?php echo $evaltype; ?></td>
								<td style="width:10%"><?php echo $responsible; ?></td>
								<td style="width:10%">
									<div align="center">
										<a href="deploy-evaluation-form?prjid=<?php echo $projid; ?>&frmid=<?php echo $formid; ?>" alt="Deploy this Project Evaluation Form" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Deploy this Project Evaluation Form" style="color:#9C27B0">
											<i class="fa fa-paper-plane-o" aria-hidden="true"></i> DEPLOY
										</a>
									</div>
								</td>
							</tr>
						<?php
						}
						?> 
					</tbody>
                </table>
			</div>
			<div id="menu2" class="tab-pane fade"> 
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <tr class="bg-light-blue">
                            <th style="width:3%">#</th>
                            <th style="width:32%">Project Name</th>
                            <th style="width:22%">Evaluation Form Name</th>
                            <th style="width:15%">Evaluation Type</th>
							<th style="width:18%">Other Details</th>
                            <th style="width:10%">Responses</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php						
						$nm = 0;
						while($row_active_evaluation = $query_active_evaluation->fetch())
						{ 
							$nm = $nm + 1;
							$projid = $row_active_evaluation['projid'];
							$project = $row_active_evaluation['projname'];
							$evalstartdate = $row_active_evaluation['startdate'];
							$evalenddate = $row_active_evaluation['enddate'];
							$formid = $row_active_evaluation['id'];
							$formname = $row_active_evaluation['form_name'];
							$evaltype = $row_active_evaluation["type"];
							$limittype = $row_active_evaluation["limit_type"];
							$responsesnumber = $row_active_evaluation["responses_number"];
							
							$query_TotalSub = $db->prepare("SELECT * FROM tbl_project_evaluation_submission WHERE projid ='$projid' AND formid='$formid' GROUP BY submission_code");
							$query_TotalSub->execute();		
							$row_TotalSub = $query_TotalSub->fetch();
							$totalRows_TotalSub = $query_TotalSub->rowCount();	
							?>
							<tr style="background-color:#eff9ca">
								<td style="width:3%" align="center"><?php echo $nm; ?></td>
								<td style="width:32%"><?php echo $project; ?></td>
								<td style="width:22%"><a href="project-evaluation-submissions?projid=<?php echo $projid; ?>&formid=<?php echo $formid; ?>" alt="View Project Evaluation Submissions" width="16" height="16" style="color:blue" data-toggle="tooltip" data-placement="bottom" title="View Project Evaluation Submissions"><?php echo $formname; ?></a></td>
								<td style="width:15%"><?php echo $evaltype; ?></td>
								<td style="width:18%"><?php if($limittype==1){ echo "Start Date: ".date("d M Y",strtotime($evalstartdate))."; End Date: ".date("d M Y",strtotime($evalenddate)); } else { echo "Required Responses: ".$responsesnumber; }?></td>
								<td style="width:10%">
									<div align="center"><a href="project-evaluation-submissions?projid=<?php echo $projid; ?>&formid=<?php echo $formid; ?>" alt="View Project Evaluation Submissions" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="View Project Evaluation Submissions">
										<span class="badge bg-purple" style="margin-bottom:2px" id="<?php echo "resp".$projid; ?>"><?php echo $totalRows_TotalSub; ?></span></a>
									</div>
								</td>
							</tr>
						<?php
						}
						?> 
					</tbody>
                </table>
			</div>
			<div id="menu3" class="tab-pane fade"> 
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <tr class="bg-light-green">
                            <th style="width:3%">#</th>
                            <th style="width:35%">Project Name</th>
                            <th style="width:19%">Project Location</th>
                            <th style="width:15%">Evaluation Type</th>
							<th style="width:19%">Details</th>
                            <th style="width:9%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php						
						$nm = 0;
						while($row_evaluated = $query_evaluated->fetch())
						{ 
							$nm = $nm + 1;
							$projid = $row_evaluated['projid'];
							$project = $row_evaluated['projname'];
							$projstatus = $row_evaluated['projstatus'];
							$evalstartdate = $row_evaluated['startdate'];
							$evalenddate = $row_evaluated['enddate'];
							$formid = $row_evaluated['id'];
							$evalid = $row_evaluated['evalid'];
							$formname = $row_evaluated['form_name'];
							$evaltype = $row_evaluated["type"];
							$limittype = $row_evaluated["limit_type"];
							$responsesnumber = $row_evaluated["responses_number"];
							
							$query_evaluation= $db->prepare("SELECT * FROM tbl_project_evaluation_submission WHERE formid='$formid'");
							$query_evaluation->execute();	
							$count_evaluation = $query_evaluation->rowCount();
							?>
							<tr style="background-color:#eff9ca">
								<td style="width:3%" align="center"><?php echo $nm; ?></td>
								<td style="width:35%"><?php echo $project; ?></td>
								<td style="width:19%"><?php echo $formname; ?></td>
								<td style="width:15%"><span data-toggle="tooltip" data-placement="bottom" title="<?php echo "Project Status: ".$projstatus; ?>"><?php echo $evaltype; ?></span></td>
								<td style="width:19%"><?php if($limittype==1){ echo "Evaluation Start Date: ".date("d M Y",strtotime($evalstartdate))."; End Date: ".date("d M Y",strtotime($evalenddate)); } else { echo "Required Responses: ".$responsesnumber."; Received Responses: ".$count_evaluation; }?></td>
								<td style="width:9%">
									<div align="center">
										<a href="project-evaluation-conclusion?proj=<?=$projid?>&eval=<?=$evalid?>" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Add Project Evaluation Conclusion and Recommendation"><i class="fa fa-pencil-square-o fa-2x text-primary" aria-hidden="true"></i></a>
									</div>
								</td>
							</tr>
						<?php
						}
						?> 
					</tbody>
                </table>
			</div>
		</div>
	</div>
</div>
<?php 

} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}

?>