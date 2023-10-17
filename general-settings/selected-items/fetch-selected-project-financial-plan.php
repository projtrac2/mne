<?php

include_once "controller.php";
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
try {

	function get_budget_lines($grp){
		global $db;
		$query_rsBilling = $db->prepare("SELECT * FROM tbl_budget_lines WHERE grp=:grp AND status=1");
		$query_rsBilling->execute(array(":grp"=>$grp));
		$row_rsBilling = $query_rsBilling->fetchAll();
		$totalRows_rsBilling = $query_rsBilling->rowCount();
		return $totalRows_rsBilling > 0 ?  $row_rsBilling : false;
	}
    // delete information on page readty 
    if (isset($_POST['deleteItem'])) {
        $projid = $_POST['projid'];
        $valid['success'] = true;
        $valid['messages'] = "Successfully Deleted";
        $deleteQuery = $db->prepare("DELETE FROM tbl_project_cost_funders_share WHERE  projid=:projid");
        $results1 = $deleteQuery->execute(array(':projid' => $projid));

        $deleteQuery = $db->prepare("DELETE FROM tbl_project_direct_cost_plan WHERE projid=:projid");
        $results2 = $deleteQuery->execute(array(':projid' => $projid));

        $deleteQuery = $db->prepare("DELETE FROM tbl_project_expenditure_timeline WHERE  projid=:projid");
        $results3 = $deleteQuery->execute(array(':projid' => $projid));

        if ($results1 && $results2 && $results3) {
            $projstage = 5;
            $insertSQL = $db->prepare("UPDATE tbl_projects SET  projstage=:projstage WHERE  projid=:projid");
            $results  = $insertSQL->execute(array(":projstage" => $projstage, ":projid" => $projid));

            if ($results === TRUE) {
                $valid['success'] = true;
                $valid['messages'] = "Successfully Deleted";
            } else {
                $valid['success'] = false;
                $valid['messages'] = "Error while deletng the record!!";
            }
            echo json_encode($valid);
        }
    }

    if (isset($_POST['more_info'])) {
        $projid = $_POST['projid'];
        $query_rsProjects = $db->prepare("SELECT *  FROM tbl_projects WHERE deleted='0' and projplanstatus='1' and projid='$projid' ");
        $query_rsProjects->execute();
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();
		
        $projname = $row_rsProjects['projname'];
        $projcode = $row_rsProjects['projcode'];
        $projcost = $row_rsProjects['projcost'];
        $mne_budget = $row_rsProjects['mne_budget'];
		  $implementation_cost= $projcost - $mne_budget;
        $progid = $row_rsProjects['progid'];
        $projstartdate = $row_rsProjects['projstartdate'];
        $projenddate = $row_rsProjects['projenddate'];

        $query_rsOutputs = $db->prepare("SELECT p.output as  output, o.id as opid, p.indicator, o.budget as budget FROM tbl_project_details o   INNER JOIN tbl_progdetails p ON p.id = o.outputid WHERE projid='$projid' ");
        $query_rsOutputs->execute();
        $row_rsOutputs = $query_rsOutputs->fetch();
        $totalRows_rsOutputs = $query_rsOutputs->rowCount();

        // query the 
        $query_rsProjFinancier =  $db->prepare("SELECT * FROM tbl_myprojfunding WHERE projid ='$projid' ORDER BY amountfunding desc");
        $query_rsProjFinancier->execute();
        $row_rsProjFinancier = $query_rsProjFinancier->fetch();
        $totalRows_rsProjFinancier = $query_rsProjFinancier->rowCount();
		$summary ="";
        $projectPlan = '
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
					<div  class="header" style="background-color:#c7e1e8; border-radius:3px">
						<i class="fa fa-file" aria-hidden="true"></i> Project Details
					</div> 
					<div class="body">
						<div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
							<div class="col-md-3 clearfix" style="margin-top:5px; margin-bottom:5px">
								<label class="control-label">Project Code:</label>
								<div class="form-line">
									<input type="text" class="form-control" value="' . $projcode . ' " readonly>
								</div>
							</div>
							<div class="col-md-9 clearfix" style="margin-top:5px; margin-bottom:5px">
								<label class="control-label">Project Name:</label>
								<div class="form-line">
									<input type="text" class="form-control" value="' . $projname . ' " readonly>
								</div>
							</div>
							<div class="col-md-6 clearfix" style="margin-top:5px; margin-bottom:5px">
								<label class="control-label">Total Project Cost:</label>
								<div class="form-line">
									<input type="text" class="form-control" value="Ksh. '.$projcost.'" readonly>
								</div>
							</div>
							<div class="col-md-6 clearfix" style="margin-top:5px; margin-bottom:5px">
								<label class="control-label">Project Implementation Cost:</label>
								<div class="form-line">
									<input type="text" class="form-control" value="Ksh. '.$implementation_cost.'" readonly>
								</div>
							</div>
							<div class="col-md-6 clearfix" style="margin-top:5px; margin-bottom:5px">
								<label class="control-label">Project M&E Cost:</label>
								<div class="form-line">
									<input type="text" class="form-control" value="Ksh. '.$mne_budget.'" readonly>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
				<div  class="header" style="background-color:#c7e1e8; border-radius:3px">
					<i class="fa fa-university" aria-hidden="true"></i> Funding Details
				</div> 
				<div class="body">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover" id="" style="width:100%">
							<thead>
								<tr>
									<th width="4%">#</th>
									<th width="80%">Financier</th>
									<th width="16%">Amount (Ksh)</th>
								</tr>
							</thead>
							<tbody id="">';
							$inputs ='';
								$rowno = 0;
								$totalAmount = 0;
								if ($totalRows_rsProjFinancier > 0) {
									do {
										$rowno++;
										$sourcat =  $row_rsProjFinancier['sourcecategory'];
										$financierid = $row_rsProjFinancier['financier'];
										$projamountfunding =  $row_rsProjFinancier['amountfunding'];
										$totalAmount = $projamountfunding + $totalAmount;
									
										$query_rsFunder = $db->prepare("SELECT * FROM tbl_financiers WHERE id='$financierid'");
										$query_rsFunder->execute();
										$row_rsFunder = $query_rsFunder->fetch();
										$totalRows_rsFunder = $query_rsFunder->rowCount();
										$funder = $row_rsFunder['financier'];
										$inputs .= '<span>' . $funder . '</span>';

										$projectPlan .= ' 
										<tr id="">
											<td> ' . $rowno . ' </td>
											<td> 	' .  $inputs . ' </td>
											<td> ' .  number_format($projamountfunding, 2) . ' </td>
										</tr>';
									} while ($row_rsProjFinancier = $query_rsProjFinancier->fetch());
								}
								$projectPlan .= ' 
								<tfoot>
									<tr>
										<td colspan="2"><strong>Total Amount</strong></td>
										<td><strong>' . number_format($totalAmount, 2) . '</strong></td>
									</tr>
								</tfoot>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		</div>';
			$query_rsOutputs = $db->prepare("SELECT p.output as  output, o.id as opid, p.indicator, o.budget as budget FROM tbl_project_details o INNER JOIN tbl_progdetails p ON p.id = o.outputid WHERE projid = :projid");
			$query_rsOutputs->execute(array(":projid" => $projid));
			$row_rsOutputs = $query_rsOutputs->fetch();
			$totalRows_rsOutputs = $query_rsOutputs->rowCount();
			$projectPlan .= ' 
			<fieldset class="scheduler-border">
			<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
				<i class="fa fa-money" aria-hidden="true"></i> Financial Plan
			</legend>';
			if($totalRows_rsOutputs > 0){
				$Ocounter = 0;
				do {
					$Ocounter++; 
					$outputName = $row_rsOutputs['output'];
					$outputCost = $row_rsOutputs['budget'];
					$outputid = $row_rsOutputs['opid'];
					$output_cost_val[] = $outputid;
					$output_remeinder = 0;
					$administrative_budget_lines= get_budget_lines(1);
					$non_expandable_budget_lines= get_budget_lines(2);
					$other_cost_budget_lines= get_budget_lines(3);

					$query_rs_output_cost_plan =  $db->prepare("SELECT SUM(unit_cost * units_no) as budget FROM tbl_project_direct_cost_plan WHERE projid =:projid AND outputid=:outputid ");
					$query_rs_output_cost_plan->execute(array(":projid" => $projid, ":outputid" => $outputid));
					$row_rs_output_cost_plan = $query_rs_output_cost_plan->fetch();
					$totalRows_rs_output_cost_plan = $query_rs_output_cost_plan->rowCount();
					$output_cost = $row_rs_output_cost_plan ? $row_rs_output_cost_plan['budget'] : 0 ; 
					$projectPlan .= ' 
						<div class="panel panel-primary">
							<div class="panel-heading list-group-item list-group-item list-group-item-action active collapse careted" data-toggle="collapse" data-target=".output'.$outputid.'">
								<i class="fa fa-caret-down" aria-hidden="true"></i>
								<strong> Output '.$Ocounter.':<span class="">'.$outputName.'</span></strong>
							</div>
							<div class="collapse output'.$outputid.'" style="padding:5px">
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
										<i class="fa fa-list-ol" aria-hidden="true"></i>'. $Ocounter .'.1. Direct Project Cost
									</legend>
									<div class="table-responsive">
										<table class="table table-bordered" id="">
											<thead>
												<tr>
													<th style="width:2%"># </th>
													<th colspan="2" style="width:16%">Description</th>
													<th colspan="2" style="width:8%">Unit</th>
													<th style="width:8%">Unit Cost (Ksh)</th>
													<th style="width:9%">No. of Units</th>
													<th style="width:10%">Total Cost (Ksh)</th> 
												</tr>
											</thead>
											<tbody>'; 
												$query_rsMilestones = $db->prepare("SELECT * FROM tbl_milestone WHERE projid=:projid and outputid = :outputid ORDER BY sdate");
												$query_rsMilestones->execute(array(":projid" => $projid, ":outputid" => $outputid));
												$row_rsMilestones = $query_rsMilestones->fetch();
												$totalRows_rsMilestones = $query_rsMilestones->rowCount();
												$mcounter = 0;
												$sum = 0;

												$bg_id = 0 . $outputid;
												if ($totalRows_rsMilestones > 0) {
													do {
														$mcounter++;
														$milestone = $row_rsMilestones['msid'];
														$milestoneName = $row_rsMilestones['milestone'];
														$query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE projid=:projid and msid=:milestone ORDER BY sdate");
														$query_rsTasks->execute(array(":projid" => $projid, ":milestone" => $milestone));
														$row_rsTasks = $query_rsTasks->fetch();
														$totalRows_rsTasks = $query_rsTasks->rowCount();
														if ($totalRows_rsTasks > 0) {
															$projectPlan .= '
															<tr class="bg-blue-grey">
																<td>'.$mcounter.'</td>
																<td colspan="8"><strong> Milestone:</strong> '.$milestoneName.'</td>
															</tr>';
															$tcounter = 0;
															do {
																$tcounter++;
																$task =  $row_rsTasks['task'];
																$tkid =  $row_rsTasks['tkid'];
																$edate =  $row_rsTasks['edate'];
																$sdate =  $row_rsTasks['sdate'];
																$taskid = $outputid . $tkid; // to distinguish between different outputs
																$cost_type = 1;
																$projectPlan .= ' 
																<tr class="bg-grey">
																	<td>'. $mcounter . '.' . $tcounter  .'</td>
																	<td colspan="2"><strong> Task:</strong> '.$task.'</td>
																	<td colspan="2"><strong> Start Date:</strong> '.date("d M Y", strtotime($sdate)).'</td>
																	<td colspan="2"><strong> End Date:</strong> '.date("d M Y", strtotime($edate)).'</td>
																	<td> 
																	</td>
																</tr>';
																$query_rsDirect_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type AND tasks=:tkid ");
																$query_rsDirect_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":tkid" => $tkid));
																$row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch();
																$totalRows_rsDirect_cost_plan = $query_rsDirect_cost_plan->rowCount();
																$dddescription = $unit = $unit_cost = $units_no = $rmkid = $total_cost = "";
																if($totalRows_rsDirect_cost_plan > 0){
																	do{
																		$unit = $row_rsDirect_cost_plan['unit'];
																		$unit_cost = $row_rsDirect_cost_plan['unit_cost'];
																		$units_no = $row_rsDirect_cost_plan['units_no'];
																		$rmkid = $row_rsDirect_cost_plan['id'];
																		$dddescription = $row_rsDirect_cost_plan['description'];
																		$total_cost = $unit_cost * $units_no;
																		$sum = $sum + $total_cost;
																		$output_remeinder = $output_remeinder + $total_cost;
																		$projectPlan .= ' 
																		<tr class="">
																			<td style="color:#FF5722"> '. $mcounter . "." . $tcounter  .' 	</td>
																			<td colspan="2">'.$dddescription.'</td>
																			<td colspan="2">'.$unit.'</td>
																			<td>'.number_format($unit_cost, 2).'</td>
																			<td>'.number_format($units_no).'</td>
																			<td>'.number_format($total_cost,2).'</td> 
																		</tr>';
																	
																	}while($row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch());
																}
															} while ($row_rsTasks = $query_rsTasks->fetch());
														}
													} while ($row_rsMilestones = $query_rsMilestones->fetch());
													$per  = ($sum && $implementation_cost) ? ($sum / $implementation_cost) * 100 :0;
													$percent = number_format($per, 2);
												}
												$projectPlan .= ' 
											</tbody>
											<tfoot>
												<tr>
													<td colspan="2"><strong>Sub Total</strong></td>
													<td colspan="2"> 
														<input type="text" name="d_sub_total_amount" id="sub_total_amoun" value="'.number_format($sum,2).'" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
													</td>
													<td colspan="2"> <strong>% Sub Total</strong></td>
													<td colspan="2">
														<input type="text" name="d_sub_total_percentage" id="sub_total_percentage3" value="'.$percent.'" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
													</td> 
												</tr>
											</tfoot>
										</table>
									</div>
								</fieldset>';
								if($administrative_budget_lines){
									$projectPlan .= ' 
									<fieldset class="scheduler-border">
										<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
											<i class="fa fa-list-ol" aria-hidden="true"></i> '.$Ocounter.'.2. Administrative/Operational Cost
										</legend>';
										
										$counter = 2;
										$a_counter =0;
										$pcounter=0;
										foreach($administrative_budget_lines as $administrative_budget_line){ 
											$a_counter++;
											$budget_line = $administrative_budget_line['name'];
											$budget_line_id = $administrative_budget_line['id'];
											$budget_lineid = $budget_line_id . $outputid;
											$rowno = 1;
											$other_sum = $other_percent = 0;
											$cost_type = 3;
											$query_rsOther_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type AND outputid=:opid  AND other_plan_id =:other_plan_id ");
											$query_rsOther_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":opid" => $outputid, ":other_plan_id" => $budget_line_id));
											$row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch();
											$totalRows_rsOther_cost_plan = $query_rsOther_cost_plan->rowCount();
											if($totalRows_rsOther_cost_plan > 0){
												$projectPlan .= '
												<div class="panel  panel-info">
													<div class="panel-heading list-group-item list-group-item list-group-item-action active collapse careted" data-toggle="collapse" data-target=".administrative'.$budget_lineid.'">
														<i class="fa fa-caret-down" aria-hidden="true"></i>
														<strong> '.$Ocounter . "." . $counter . "." . $a_counter  . ") " . $budget_line.'
													</div>
													<div class="collapse administrative'.$budget_lineid.'" style="padding:5px">
														<div class="row clearfix" style="margin-top:5px; margin-bottom:5px"> 
															<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																<div class="table-responsive">
																	<table class="table table-bordered">
																		<thead>
																			<tr>
																				<th># </th>
																				<th colspan="2">Description </th>
																				<th colspan="2">Unit</th>
																				<th>Unit Cost</th>
																				<th>No. of Units</th>
																				<th>Total Cost</th> 
																			</tr>
																		</thead>
																		<tbody id="budget_lines_table">';
																			if ($totalRows_rsOther_cost_plan > 0) {
																					$table_counter=0;
																					do {
																						$table_counter++;
																						$rowno++;
																						$unit = $row_rsOther_cost_plan['unit'];
																						$unit_cost = $row_rsOther_cost_plan['unit_cost'];
																						$units_no = $row_rsOther_cost_plan['units_no'];
																						$rmkid = $row_rsOther_cost_plan['id'];
																						$description = $row_rsOther_cost_plan['description'];
																						$total_cost = $unit_cost * $units_no;
																						$other_sum = $other_sum + $total_cost;
																						$output_remeinder = $output_remeinder + $total_cost;
																						$budget_line_rowno = $rowno + $budget_lineid;
																						$projectPlan .= ' 
																						<tr id="row">
																							<td>'.$table_counter.'</td>
																							<td colspan="2">'.$description.'</td>
																							<td colspan="2">'.$unit .'</td>
																							<td>'.number_format($unit_cost, 2).'</td>
																							<td>'.number_format($units_no).'</td>
																							<td>'.number_format($total_cost,2) .'</td> 
																						</tr>';
																					
																				} while ($row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch());
																					$other_per  = ($other_sum / $implementation_cost) * 100;
																					$other_percent = number_format($other_per, 2);
																			}
																			$projectPlan .= ' 
																		</tbody>
																		<tfoot id="budget_line_foot">
																			<tr>
																				<td colspan="2"><strong>Sub Total</strong></td>
																				<td colspan="2"> 
																					<input type="text" name="subtotal_amount3" value="'.number_format($other_sum, 2).'" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																				</td>
																				<td colspan="2"> <strong>% Sub Total</strong></td>
																				<td colspan="2">
																					<input type="text" name="subtotal_percentage3" value="'.$other_percent.' %" id="sub_total_percentage3" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																				</td>
																			</tr>
																		</tfoot>
																	</table>
																</div>
															</div>
														</div>
													</div>
												</div>';
											}
										}
										$projectPlan .= ' 
									</fieldset>';
								}

								if($non_expandable_budget_lines){
									$projectPlan .= ' 
									<fieldset class="scheduler-border">
										<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
											<i class="fa fa-list-ol" aria-hidden="true"></i> '.$Ocounter.'.3. Non Expendable Equipment Cost
										</legend>';
											
										$counter = 2;
										$n_counter =0;
										foreach($non_expandable_budget_lines as $non_expandable_budget_line){
											$counter++;
											$n_counter++;
											$budget_line = $non_expandable_budget_line['name'];
											$budget_line_id = $non_expandable_budget_line['id'];
											$budget_lineid = $budget_line_id . $outputid;
											
											$cost_type = 3;
											$query_rsOther_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type AND outputid=:opid  AND other_plan_id =:other_plan_id ");
											$query_rsOther_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":opid" => $outputid, ":other_plan_id" => $budget_line_id));
											$row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch();
											$totalRows_rsOther_cost_plan = $query_rsOther_cost_plan->rowCount();
											if($totalRows_rsOther_cost_plan > 0){
												$projectPlan .= '
												<div class="panel panel-info">
													<div class="panel-heading list-group-item list-group-item list-group-item-action active collapse careted" data-toggle="collapse" data-target=".administrative'.$budget_lineid.'">
														<i class="fa fa-caret-down" aria-hidden="true"></i>
														<strong> '.$Ocounter . "." . $counter . "." . $n_counter . ") " . $budget_line.'</span></strong>
													</div>
													<div class="collapse administrative'.$budget_lineid.'" style="padding:5px">
														<div class="row clearfix" style="margin-top:5px; margin-bottom:5px"> 
															<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																	<div class="table-responsive">
																		<table class="table table-bordered">
																			<thead>
																				<tr>
																					<th># </th>
																					<th colspan="2">Description </th>
																					<th colspan="2">Unit</th>
																					<th>Unit Cost</th>
																					<th>No. of Units</th>
																					<th>Total Cost</th> 
																				</tr>
																			</thead>
																			<tbody id="budget_lines_table">';

																				$rowno = 1;
																				$other_sum = $other_percent= 0;
																				if ($totalRows_rsOther_cost_plan > 0) {
																					$pcounter=0;
																						do {
																							$pcounter++;
																							$rowno++;
																							$unit = $row_rsOther_cost_plan['unit'];
																							$unit_cost = $row_rsOther_cost_plan['unit_cost'];
																							$units_no = $row_rsOther_cost_plan['units_no'];
																							$rmkid = $row_rsOther_cost_plan['id'];
																							$description = $row_rsOther_cost_plan['description'];
																							$total_cost = $unit_cost * $units_no;
																							$other_sum = $other_sum + $total_cost;
																							$output_remeinder = $output_remeinder + $total_cost;
																							$budget_line_rowno = $rowno + $budget_lineid;
																							$projectPlan .= ' 
																						<tr id="row">
																							<td>'.$table_counter.'</td>
																							<td colspan="2">'.$description.'</td>
																							<td colspan="2">'.$unit .'</td>
																							<td>'.number_format($unit_cost, 2).'</td>
																							<td>'.number_format($units_no).'</td>
																							<td>'.number_format($total_cost,2) .'</td> 
																						</tr>';
																					} while ($row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch());
																						$other_per  = ($other_sum / $implementation_cost) * 100;
																						$other_percent = number_format($other_per, 2);
																				}
																				$projectPlan .= ' 
																			</tbody>
																			<tfoot id="budget_line_foot">
																				<tr>
																					<td colspan="2"><strong>Sub Total</strong></td>
																					<td colspan="2"> 
																						<input type="text" name="subtotal_amount3" value="'.number_format($other_sum, 2).'" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																					</td>
																					<td colspan="2"> <strong>% Sub Total</strong></td>
																					<td colspan="2">
																						<input type="text" name="subtotal_percentage3" value="'.$other_percent.' %" id="sub_total_percentage3" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																					</td>
																				</tr>
																			</tfoot>
																		</table>
																	</div> 
															</div>
														</div>
													</div>
												</div>';
											}
										}
										$projectPlan .= ' 
									</fieldset>';
									
								} 
								if($other_cost_budget_lines){
									$projectPlan .= ' 
									<fieldset class="scheduler-border">
										<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
											<i class="fa fa-list-ol" aria-hidden="true"></i>'.$Ocounter.'.4. Other Cost Lines
										</legend>';

										$counter = 4;
										$o_counter=0; 
										foreach($other_cost_budget_lines as $other_cost_budget_line){
											// $counter++;
											$o_counter++;
											$budget_line = $other_cost_budget_line['name'];
											$budget_line_id = $other_cost_budget_line['id'];
											$budget_lineid = $budget_line_id . $outputid;
											
											$cost_type = 3;
											$query_rsOther_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type AND outputid=:opid  AND other_plan_id =:other_plan_id ");
											$query_rsOther_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":opid" => $outputid, ":other_plan_id" => $budget_line_id));
											$row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch();
											$totalRows_rsOther_cost_plan = $query_rsOther_cost_plan->rowCount();

											$rowno = 1;
											$other_sum = 0;
											$pcounter=0;
											$other_percent = 0;
											if ($totalRows_rsOther_cost_plan > 0) {
												$projectPlan .= ' 
												<div class="panel panel-info">
													<div class="panel-heading list-group-item list-group-item list-group-item-action active collapse careted" data-toggle="collapse" data-target=".administrative'.$budget_lineid.'">
														<i class="fa fa-caret-down" aria-hidden="true"></i>
														<strong> '.$Ocounter . "." . $counter . "." . $n_counter . ") " . $budget_line.'</span></strong>
													</div>
													<div class="collapse administrative'.$budget_lineid.'" style="padding:5px">
														<div class="row clearfix" style="margin-top:5px; margin-bottom:5px"> 
															<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																	<div class="table-responsive">
																		<table class="table table-bordered">
																			<thead>
																				<tr>
																					<th># </th>
																					<th colspan="2">Description </th>
																					<th colspan="2">Unit</th>
																					<th>Unit Cost</th>
																					<th>No. of Units</th>
																					<th>Total Cost</th> 
																				</tr>
																			</thead>
																			<tbody id="budget_lines_table">';
																						do {
																							$pcounter++;
																							$rowno++;
																							$unit = $row_rsOther_cost_plan['unit'];
																							$unit_cost = $row_rsOther_cost_plan['unit_cost'];
																							$units_no = $row_rsOther_cost_plan['units_no'];
																							$rmkid = $row_rsOther_cost_plan['id'];
																							$description = $row_rsOther_cost_plan['description'];
																							$total_cost = $unit_cost * $units_no;
																							$other_sum = $other_sum + $total_cost;
																							$output_remeinder = $output_remeinder + $total_cost;
																							$budget_line_rowno = $rowno + $budget_lineid;
																							$projectPlan .= ' 
																							<tr id="row">
																								<td>'.$table_counter.'</td>
																								<td colspan="2">'.$description.'</td>
																								<td colspan="2">'.$unit .'</td>
																								<td>'.number_format($unit_cost, 2).'</td>
																								<td>'.number_format($units_no).'</td>
																								<td>'.number_format($total_cost,2) .'</td> 
																							</tr>';
																					} while ($row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch());
																						$other_per  = ($other_sum / $implementation_cost) * 100;
																						$other_percent = number_format($other_per, 2);
																				$projectPlan .= ' 
																			</tbody>
																			<tfoot id="budget_line_foot">
																				<tr>
																					<td colspan="2"><strong>Sub Total</strong></td>
																					<td colspan="2"> 
																						<input type="text" name="subtotal_amount3" value="'.number_format($other_sum, 2).'" class="form-control" placeholder="Total sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																					</td>
																					<td colspan="2"> <strong>% Sub Total</strong></td>
																					<td colspan="2">
																						<input type="text" name="subtotal_percentage3" value="'.$other_percent.' %" id="sub_total_percentage3" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
																					</td>
																				</tr>
																			</tfoot>
																		</table>
																	</div>
															</div>
														</div>
													</div>
												</div> ';
											}
										}
										$projectPlan .= ' 
									</fieldset> ';
								}
								$projectPlan .= ' 
							</div>
						</div>';
					$summary  .= 
					'<tr>
						<td>' . $Ocounter . '</td>
						<td>' . $outputName . '</td>
						<td>' . number_format($outputCost, 2) . '</td>
						<td id="summaryOutput' . $outputid . '" >' . number_format($output_remeinder, 2) . '</td>
						<td id="perc' . $outputid .  '" >' . number_format((($output_remeinder / $implementation_cost) * 100), 2) . ' %</td>
					</tr>';
				} while ($row_rsOutputs = $query_rsOutputs->fetch());
			}
        $projectPlan .= '
		  </fieldset>
        <div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div  class="header" style="background-color:#c7e1e8; border-radius:3px">
						<i class="fa fa-bar-chart" aria-hidden="true"></i> <strong>Financial Plan Summary</strong>
					</div>
					<div class="body">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover" id="" style="width:100%">
								<thead>
									<tr>
										<th width="2%">#</th>
										<th width="58%">Output</th>
										<th width="15%">Output Budget</th>
										<th width="15%">Amount Planned(Ksh)</th>
										<th width="10%">% Planned</th>
									</tr>
								</thead>
								<tbody id="">
									' . $summary . '
									<tfoot>
										<tr>
											<td colspan="2">
												<strong>
													Total Amount 
												</strong>
											</td>
											<td style="text-align:left">
												<strong>
													' . number_format($projcost, 2) . '
												</strong>
											</td>
											<td style="text-align:left">
												<strong id="summary_total" >
												' . number_format($projcost, 2) . '
												</strong>
											</td>
											<td style="text-align:left">
												<strong id="summary_percentage"  >
												' . number_format(100, 2) . ' %
												</strong>
											</td>
										</tr>
									</tfoot>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div> ';
        echo $projectPlan;
    }
} catch (PDOException $ex) {
    // $result = flashMessage("An error occurred: " .$ex->getMessage());
    print($ex->getMessage());
}
