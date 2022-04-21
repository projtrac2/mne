<?php

include_once "controller.php";

try {
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

        $projectPlan = '
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
					<div  class="header" style="background-color:#c7e1e8; border-radius:3px">
						<i class="fa fa-file" aria-hidden="true"></i> Project Details
					</div> 
					<div class="body">
						<div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
							<div class="col-md-12 clearfix" style="margin-top:5px; margin-bottom:5px">
								<label class="control-label">Project Name:</label>
								<div class="form-line">
									<input type="text" class="form-control" value="' . $projname . ' " readonly>
								</div>
							</div>
							<div class="col-md-6 clearfix" style="margin-top:5px; margin-bottom:5px">
								<label class="control-label">Project Code:</label>
								<div class="form-line">
									<input type="text" class="form-control" value="' . $projcode . ' " readonly>
								</div>
							</div>
							<div class="col-md-6 clearfix" style="margin-top:5px; margin-bottom:5px">
								<label class="control-label">Project Approved Budget:</label>
								<div class="form-line">
									<input type="text" class="form-control" value="Ksh. '.$projcost.'" readonly>
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
											<tr id="row<?= $rowno ?>">
												<td>
													' . $rowno . '
												</td>
												<td>
													' .  $inputs . '
												</td>
												<td>
													' .  number_format($projamountfunding, 2) . '
												</td>
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

        $Ocounter = 0;
        $summary = '';
        $output_cost_val = [];
        do {
            $Ocounter++;
            //get indicator
            $outputName = $row_rsOutputs['output'];
            $outputCost = $row_rsOutputs['budget'];
            $outputid = $row_rsOutputs['opid'];
            $output_cost_val[] = $outputid;
            $output_remeinder = 0;

            $projectPlan .= '
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card"> 
						<div class="panel panel-info">
							<div class="panel-heading list-group-item list-group-item list-group-item-action active collapse careted" data-toggle="collapse" data-target=".output' . $outputid . '">
								<i class="fa fa-list-ul" aria-hidden="true"></i> <i class="fa fa-caret-down" aria-hidden="true"></i>
								<strong> Output'.$Ocounter.': 
									<span class="">
										'.$outputName.'
									</span>
								</strong>
							</div>
							<div class="body collapse output'.$outputid.'">
								<h4>' . $Ocounter . '.1) Direct Project Cost</h4>';
								
								$query_rsMilestones = $db->prepare("SELECT * FROM tbl_milestone WHERE projid=:projid and outputid =:outputid ORDER BY sdate");
								$query_rsMilestones->execute(array(":projid" => $projid, ":outputid" => $outputid));
								$row_rsMilestones = $query_rsMilestones->fetch();
								$totalRows_rsMilestones = $query_rsMilestones->rowCount();
								$mcounter = 0;
								$sum = 0;
								if ($totalRows_rsMilestones > 0) {
									$projectPlan .= '  
									<div class="table-responsive">
										<table class="table table-bordered" id="funding_table">
											<thead>
												<tr>
													<th style="width:2%"></th>
													<th style="width:2%"># </th> 
													<th style="width:10%">Description </th>
													<th style="width:10%">Unit</th>
													<th style="width:10%">Unit Cost (Ksh)</th>
													<th style="width:10%">No. of Units</th>
													<th style="width:10%">Total Cost (Ksh)</th>
												</tr>
											</thead>
											<tbody>';
												do {
													$mcounter++;
													$milestone = $row_rsMilestones['msid'];
													$milestoneName = $row_rsMilestones['milestone'];
													$query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE projid=:projid and msid=:milestone ORDER BY sdate ");
													$query_rsTasks->execute(array(":projid" => $projid, ":milestone" => $milestone));
													$row_rsTasks = $query_rsTasks->fetch();
													$totalRows_rsTasks = $query_rsTasks->rowCount();

													if ($totalRows_rsTasks > 0) {
														$projectPlan .= '  
														<tr>  
															<td>' . $Ocounter . "." . 1 . "." . $mcounter   . '</td>
															<td colspan="7"><strong> Milestone: ' . $milestoneName . '</strong> </td>
														</tr>';
														$tcounter = 0;
														do {
															$tcounter++;
															$task =  $row_rsTasks['task'];
															$tkid =  $row_rsTasks['tkid'];
															$edate =  date_create($row_rsTasks['edate']);
															$sdate =  date_create($row_rsTasks['sdate']);
															$taskid = $outputid . $tkid; // to distinguish between different outputs
															$cost_type = 1;
															$projectPlan .= '
															<tr>  
																<td>' .  $Ocounter . "." . 1 . "." . $mcounter . "." . $tcounter  . '</td>
																<td colspan="3">Task:<strong> ' .  $task . '</strong> </td>
																<td colspan="2">Start Date:<strong> ' . date_format($sdate, 'd M Y') . ' </strong></td>
																<td colspan="2">End Date:<strong> ' .  date_format($edate, 'd M Y') . ' </strong></td>
															</tr>';

															$query_rsDirect_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type AND tasks=:tkid");
															$query_rsDirect_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":tkid" => $tkid));
															$row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch();
															$totalRows_rsDirect_cost_plan = $query_rsDirect_cost_plan->rowCount();

															if ($totalRows_rsDirect_cost_plan > 0) {
																do {
																	$unit = $row_rsDirect_cost_plan['unit'];
																	$comments = $row_rsDirect_cost_plan['comments'];
																	$description = $row_rsDirect_cost_plan['description'];
																	$unit_cost = $row_rsDirect_cost_plan['unit_cost'];
																	$units_no = $row_rsDirect_cost_plan['units_no'];
																	$rmkid = $row_rsDirect_cost_plan['id'];
																	$total_cost = $unit_cost * $units_no;

																	$sum = $sum + $total_cost;
																	$output_remeinder = $output_remeinder + $total_cost;

																	$query_rs_cost_funders =  $db->prepare("SELECT * FROM tbl_project_cost_funders_share WHERE projid =:projid AND type=:cost_type AND plan_id=:rmkid ");
																	$query_rs_cost_funders->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":rmkid" => $rmkid));
																	$row_rs_cost_funders = $query_rs_cost_funders->fetch();
																	$totalRows_rs_cost_funders = $query_rs_cost_funders->rowCount();
																	$fund_id = [];
																	do {
																		$fund_id[] = $row_rs_cost_funders['id'];
																	} while ($row_rs_cost_funders = $query_rs_cost_funders->fetch());
																	$fnid = implode(",", $fund_id);
																	$projectPlan .= '  
																	<tr>
																		<td align="center" class="mb-0" >
																			<button class="btn btn-link collapsed"  title="Click once to expand and Click twice to Collapse!!" data-toggle="collapse" data-target=".task' . $tkid .$rmkid. '">
																				<i class="fa fa-plus-square" style="font-size:16px"></i>
																			</button> 
																		</td>
																		<td>
																			' . $tcounter . '
																		</td>  
																		<td>
																		' .  $description . '
																		</td>
																		<td>
																			' .  $unit . '
																		</td>
																		<td>
																			' .  number_format($unit_cost, 2) . '
																		</td>
																		<td>
																			' .  number_format($units_no, 2) . '
																		</td>
																		<td>
																			' .  number_format($total_cost, 2) . '
																		</td>  
																	</tr>   
																	<tr class="collapse task'. $tkid .$rmkid . '"  style="background-color:#9E9E9E; color:#FFF">  
																		<th style="width: 2%">#</th>
																		<th  style="width:78%"  colspan="5">Financiers</th>
																		<th  style="width: 20%"  colspan="2">Amount</th>
																	</tr>';
																	$rowno = 0;
																	$totalAmount = 0;
																	// query the
																	$query_rsProjFinancier = $db->prepare("SELECT * FROM tbl_project_cost_funders_share WHERE projid =:projid AND outputid=:outputid AND plan_id=:plan_id ORDER BY amount desc");
																	$query_rsProjFinancier->execute(array(":projid" => $projid, ":outputid" => $outputid, ":plan_id" => $rmkid));
																	$row_rsProjFinancier = $query_rsProjFinancier->fetch();
																	$totalRows_rsProjFinancier = $query_rsProjFinancier->rowCount();

																	if ($totalRows_rsProjFinancier > 0) {
																		do {
																			$rowno++; 
																			$projfinid = $row_rsProjFinancier['funder'];
																			$projamountfunding =  $row_rsProjFinancier['amount'];
																			$totalAmount = $projamountfunding + $totalAmount; 
																			$inputs = '';

																			$query_rsFunding =  $db->prepare("SELECT * FROM tbl_myprojfunding WHERE id =:projfinid AND projid=:projid ");
																			$query_rsFunding->execute(array(":projfinid" => $projfinid, ":projid" => $projid));
																			$row_rsFunding = $query_rsFunding->fetch();
																			$totalRows_rsFunding = $query_rsFunding->rowCount();
																			$projamount = $row_rsFunding['amountfunding'];
																			$sourcecat = $row_rsFunding['sourcecategory'];
																		
																			$query_rsFunder = $db->prepare("SELECT * FROM tbl_financiers WHERE id='$projfinid'");
																			$query_rsFunder->execute();
																			$row_rsFunder = $query_rsFunder->fetch();
																			
																			$funder = $row_rsFunder['financier']; 
																			$inputs .= '<span>' . $funder . '</span>';
																			
																			$projectPlan .= '
																			<tr class="collapse task'. $tkid .$rmkid . '"">
																				<td>
																					' . $rowno . '
																				</td>
																				<td colspan="5">
																					' . $inputs . '
																				</td>
																				<td style="text-align:left" colspan="2">
																					' . number_format($projamountfunding, 2) . '
																				</td>
																			</tr>';
																		} while ($row_rsProjFinancier = $query_rsProjFinancier->fetch());
																		$projectPlan .= '   
																		<tr class="collapse task' .  $tkid .$rmkid. '"  style="background-color:#9E9E9E; color:#FFF">  
																			<th style="width: 2%">#</th>
																			<th  style="width: 96%"  colspan="7">Remarks</th>
																		</tr>
																		<tr class="collapse task' . $tkid. $rmkid.'"  style="">  
																			<td>=></td>
																			<td colspan="7"> ' . $comments . ' </td>
																		</tr>';
																	}
																} while ($row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch());
															}
														} while ($row_rsTasks = $query_rsTasks->fetch());
													}
												} while ($row_rsMilestones = $query_rsMilestones->fetch());
												$per  = ($sum / $outputCost) * 100;
												$percent = number_format($per, 2);
												$projectPlan .= ' 
											</tbody>
											<tfoot>
												<tr>
													<td colspan="2"><strong>Sub Total</strong></td>
													<td colspan="1">
														<strong>' . number_format($sum, 2) . '</strong>
													</td>
													<td colspan="1"> <strong>% Sub Total</strong></td>
													<td colspan="1">
														<strong>' . $percent . ' %</strong>
													</td>
													<td colspan="1"> <strong>Output Budget Bal</strong></td>
													<td colspan="2">
														<strong> ' . number_format(($outputCost - $output_remeinder), 2) . '</strong>
													</td>
												</tr>
											</tfoot> 
										</table>
									</div> ';
								}
								
								$cost_type = 2;
								$query_rsDirect_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type AND outputid=:opid ");
								$query_rsDirect_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":opid" => $outputid));
								$row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch();
								$totalRows_rsDirect_cost_plan = $query_rsDirect_cost_plan->rowCount();
								$prowno = "";
								$pcounter = 1;
								$personnel_sum = 0;
								
								if ($totalRows_rsDirect_cost_plan > 0) {
									$projectPlan .= ' 
									<h4>' . $Ocounter . '.2) Personnel</h4>
									<div class="table-responsive">
										<table class="table table-bordered">
											<thead>
												<tr>
													<th style="width: 4%"></th>
													<th style="width: 2%">#</th>
													<th style="width: 20%">Personnel </th>
													<th style="width: 10%">Unit</th>
													<th style="width: 20%">Unit Cost</th>
													<th style="width: 20%">No. of Units</th>
													<th style="width: 20%">Total Cost</th> 
												</tr>
											</thead>
											<tbody>';
												do {
													$pcounter++;
													$unit = $row_rsDirect_cost_plan['unit'];
													$unit_cost = $row_rsDirect_cost_plan['unit_cost'];
													$units_no = $row_rsDirect_cost_plan['units_no'];
													$rmkid = $row_rsDirect_cost_plan['id'];
													$personnel = $row_rsDirect_cost_plan['personnel'];
													$comments = $row_rsDirect_cost_plan['comments'];
													$total_cost = $unit_cost * $units_no;
													$personnel_sum = $personnel_sum + $total_cost;
													$output_remeinder = $output_remeinder + $total_cost;
													$prowno = $pcounter . $outputid;

													$query_rs_timeline_id =  $db->prepare("SELECT * FROM tbl_project_expenditure_timeline WHERE projid =:projid AND type=:cost_type AND plan_id=:rmkid ");
													$query_rs_timeline_id->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":rmkid" => $rmkid));
													$row_rs_timeline_id = $query_rs_timeline_id->fetch();
													$totalRows_rs_timeline_id = $query_rs_timeline_id->rowCount();
													$timeline_id = $row_rs_timeline_id['id'];

													$query_rs_cost_funders =  $db->prepare("SELECT * FROM tbl_project_cost_funders_share WHERE projid =:projid AND type=:cost_type AND plan_id=:rmkid ");
													$query_rs_cost_funders->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":rmkid" => $rmkid));
													$row_rs_cost_funders = $query_rs_cost_funders->fetch();
													$totalRows_rs_cost_funders = $query_rs_cost_funders->rowCount();
													$fund_id = [];
													do {
														$fund_id[] = $row_rs_cost_funders['id'];
													} while ($row_rs_cost_funders = $query_rs_cost_funders->fetch());
													
													$fnid = implode(",", $fund_id);

													$query_rsPersonel = $db->prepare("SELECT * FROM tbl_projteam2 WHERE ptid=:personnel ");
													$query_rsPersonel->execute(array(":personnel" => $personnel));
													$row_rsPersonel = $query_rsPersonel->fetch();
													$totalRows_rsPersonel = $query_rsPersonel->rowCount();
													$ptnname = $row_rsPersonel['fullname'];

													$query_rsTimeline = $db->prepare("SELECT * FROM tbl_project_expenditure_timeline WHERE plan_id=:plan_id ");
													$query_rsTimeline->execute(array(":plan_id" => $rmkid));
													$row_rsTimeline = $query_rsTimeline->fetch();
													$totalRows_rsTimeline = $query_rsTimeline->rowCount();
													$disbursement_date = date_create($row_rsTimeline['disbursement_date']);
													
													$projectPlan .= '
                                                    <tr id="row">
														<td>
															<button class="btn btn-link collapsed"  title="Click once to expand and Click twice to Collapse!!" data-toggle="collapse" data-target=".personnel' . $rmkid . '">
															<i class="fa fa-plus-square" style="font-size:16px"></i>
															</button> 
														</td>
														<td>
															' . $Ocounter . ".2." . $pcounter . '
														</td>
														<td>
															' . $ptnname . '
														</td>
														<td>
															' . $unit . '
														</td>
														<td>
															' . number_format($unit_cost, 2) . '
														</td>
														<td>
															' . number_format($units_no, 2) . '
														</td>
														<td>
															' . number_format($total_cost, 2) . '
														</td>
													</tr> 
													<tr class="collapse personnel' . $rmkid . '"  style="background-color:#9E9E9E; color:#FFF">  
														<th style="width: 2%">#</th>
														<th  style="width: 96%"  colspan="8">Disbursement Date</th>
													</tr>
													<tr class="collapse personnel' . $rmkid . '"  style="">  
														<td>=></td>
														<td colspan="8"> ' . date_format($disbursement_date, 'd M Y') . ' </td>
													</tr> 
													<tr class="collapse personnel' . $rmkid . '"  style="background-color:#9E9E9E; color:#FFF">  
														<th style="width: 2%">#</th>
														<th  style="width:78%"  colspan="5">Financiers</th>
														<th  style="width: 20%"  colspan="3">Amount</th>
													</tr>';
													
													$rowno = 0;
													$totalAmount = 0;
													// query the
													$query_rsProjFinancier = $db->prepare("SELECT * FROM tbl_project_cost_funders_share WHERE projid =:projid AND outputid=:outputid AND plan_id=:plan_id ORDER BY amount desc");
													$query_rsProjFinancier->execute(array(":projid" => $projid, ":outputid" => $outputid, ":plan_id" => $rmkid));
													$row_rsProjFinancier = $query_rsProjFinancier->fetch();
													$totalRows_rsProjFinancier = $query_rsProjFinancier->rowCount();

													if ($totalRows_rsProjFinancier > 0) {
														do {
															$rowno++; 
															$projfinid = $row_rsProjFinancier['funder'];
															$projamountfunding =  $row_rsProjFinancier['amount'];
															$totalAmount = $projamountfunding + $totalAmount; 
															$inputs = '';

															$query_rsFunding =  $db->prepare("SELECT * FROM tbl_myprojfunding WHERE id =:projfinid AND projid=:projid ");
															$query_rsFunding->execute(array(":projfinid" => $projfinid, ":projid" => $projid));
															$row_rsFunding = $query_rsFunding->fetch();
															$totalRows_rsFunding = $query_rsFunding->rowCount();
															$projamount = $row_rsFunding['amountfunding'];
															$sourcecat = $row_rsFunding['sourcecategory'];


															$query_rsFundingType = $db->prepare("SELECT * FROM tbl_funding_type WHERE id='$sourcat'");
															$query_rsFundingType->execute();
															$row_rsFundingType = $query_rsFundingType->fetch();
															$totalRows_rsFundingType = $query_rsFundingType->rowCount();
															$category = $row_rsFundingType['category'];

															$inputs = '';
															if ($category ==2) {
																$query_rsDonor = $db->prepare("SELECT * FROM tbl_donors WHERE dnid='$source'");
																$query_rsDonor->execute();
																$row_rsDonor = $query_rsDonor->fetch();
																$totalRows_rsDonor = $query_rsDonor->rowCount();
																$donor = $row_rsDonor['donorname'];
																$inputs .= '<span>' . $donor . '</span>'; 
															} else {
																$query_rsFunder = $db->prepare("SELECT * FROM tbl_funder WHERE id='$source'");
																$query_rsFunder->execute();
																$row_rsFunder = $query_rsFunder->fetch();
																$totalRows_rsFunder = $query_rsFunder->rowCount();
																$funder = $row_rsFunder['name']; 
																	$inputs .= '<span>' . $funder . '</span>'; 
															}
															
															$projectPlan .= '
															<tr class="collapse personnel' . $rmkid . '"">
																<td>
																	' . $rowno . '
																</td>
																<td colspan="5">
																	' . $inputs . '
																</td>
																<td style="text-align:left" colspan="3">
																	' . number_format($projamountfunding, 2) . '
																</td>
															</tr>';
														} while ($row_rsProjFinancier = $query_rsProjFinancier->fetch());

														$projectPlan .= ' 
														<tr class="collapse personnel' . $rmkid . '"  style="background-color:#9E9E9E; color:#FFF">  
															<th style="width: 2%">#</th>
															<th  style="width: 96%"  colspan="8">Remarks</th>
														</tr>
														<tr class="collapse personnel' . $rmkid . '"  style="">
															<td>=></td>
															<td colspan="8"> ' . $comments . ' </td>
														</tr>';
													}
												} while ($row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch());

												$personnel_per  = ($personnel_sum / $outputCost) * 100;
												$personnel_percent = number_format($personnel_per, 2);
												$projectPlan .= ' 
                                            </tbody>
											<tfoot id="pfoot<?= $outputid ?>">
												<tr>
													<td colspan="1"><strong>Sub Total</strong></td>
													<td colspan="2">
														<strong>' . number_format($personnel_sum, 2) . '</strong>
													</td>
													<td colspan="1"> <strong>% Sub Total</strong></td>
													<td colspan="1">
														<strong>' . $personnel_percent . ' %</strong>
													</td>
													<td colspan="1"> <strong>Output Budget Bal</strong></td>
													<td colspan="2">
														<strong>' . number_format(($outputCost - $output_remeinder), 2) . '</strong>
													</td>
												</tr>
											</tfoot>
										</table>
									</div>';
								}
								
								// query budget lines 
								$query_rsBilling = $db->prepare("SELECT * FROM tbl_budget_lines");
								$query_rsBilling->execute();
								$row_rsBilling = $query_rsBilling->fetch();
								$totalRows_rsBilling = $query_rsBilling->rowCount();
								$counter = 2;
								
								if($totalRows_rsBilling>0){
									do {
										$counter++;
										$budget_line = $row_rsBilling['name'];
										$budget_line_id = $row_rsBilling['id'];
										$budget_lineid = $budget_line_id . $outputid;
										$cost_type = 3;
										
										$query_rsOther_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type AND outputid=:opid  AND other_plan_id =:other_plan_id ");
										$query_rsOther_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":opid" => $outputid, ":other_plan_id" => $budget_line_id));
										$row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch();
										$totalRows_rsOther_cost_plan = $query_rsOther_cost_plan->rowCount();

										$rowno = 1;
										$other_sum = 0;

										if ($totalRows_rsOther_cost_plan > 0) {
											$projectPlan .= ' 
											<h4>' . $Ocounter . '.' . $counter . ") " . $budget_line . '</h4> 
											<div class="table-responsive">
												<table class="table table-bordered">
													<thead>
														<tr>
															<th width="3%"> # </th>
															<th width="27%">Description </th>
															<th width="10%">Unit</th>
															<th width="15%">Unit Cost</th>
															<th width="10%">No. of Units</th>
															<th width="20%">Total Cost</th>
															<th width="15%">Other Details</th>
														</tr>
													</thead>
													<tbody">';
														do {
															$pcounter++;
															$rowno++;
															$unit = $row_rsOther_cost_plan['unit'];
															$unit_cost = $row_rsOther_cost_plan['unit_cost'];
															$units_no = $row_rsOther_cost_plan['units_no'];
															$rmkid = $row_rsOther_cost_plan['id'];
															$description = $row_rsOther_cost_plan['description'];
															$comments = $row_rsOther_cost_plan['comments'];
															$total_cost = $unit_cost * $units_no;
															$other_sum = $other_sum + $total_cost;
															$output_remeinder = $output_remeinder + $total_cost;


															$query_rs_timeline_id =  $db->prepare("SELECT * FROM tbl_project_expenditure_timeline WHERE projid =:projid AND type=:cost_type AND plan_id=:rmkid ");
															$query_rs_timeline_id->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":rmkid" => $rmkid));
															$row_rs_timeline_id = $query_rs_timeline_id->fetch();
															$totalRows_rs_timeline_id = $query_rs_timeline_id->rowCount();
															$disbursement_date = date_create($row_rs_timeline_id['disbursement_date']);
															$personnel = $row_rs_timeline_id['responsible'];

															$query_rsPersonel = $db->prepare("SELECT * FROM tbl_projteam2 WHERE ptid=:personnel ");
															$query_rsPersonel->execute(array(":personnel" => $personnel));
															$row_rsPersonel = $query_rsPersonel->fetch();
															$totalRows_rsPersonel = $query_rsPersonel->rowCount();
															$ptnname = $row_rsPersonel['fullname'];

															$query_rs_cost_funders =  $db->prepare("SELECT * FROM tbl_project_cost_funders_share WHERE projid =:projid AND type=:cost_type AND plan_id=:rmkid ");
															$query_rs_cost_funders->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":rmkid" => $rmkid));
															$row_rs_cost_funders = $query_rs_cost_funders->fetch();
															$totalRows_rs_cost_funders = $query_rs_cost_funders->rowCount();
															$fund_id = [];

															do {
																$fund_id[] = $row_rs_cost_funders['id'];
															} while ($row_rs_cost_funders = $query_rs_cost_funders->fetch());
															$fnid = implode(",", $fund_id);
															$budget_line_rowno = $rowno + $budget_lineid;

															$projectPlan .= '
															<tr>
																<td>
																<button class="btn btn-link collapsed"  title="Click once to expand and Click twice to Collapse!!" data-toggle="collapse" data-target=".budget_line' . $rmkid . '">
																	<i class="fa fa-plus-square" style="font-size:16px"></i>
																</button> 
																</td>
																	<td>
																	' . $Ocounter . ". " . $counter . '
																	</td>
																	<td>
																		' . $description . " rmkd" . $rmkid . ' 
																	</td>
																	<td>
																		' . $unit . '
																	</td>
																	<td>
																		' . number_format($unit_cost) . '
																	</td>
																	<td>
																		' . number_format($units_no, 2) . '
																	</td>
																	<td>
																		' . number_format($total_cost) . '
																	</td>
															</tr>
															<tr class="collapse budget_line' . $rmkid . '"  style="background-color:#9E9E9E; color:#FFF">  
																<th style="width: 2%">#</th>
																<th  style="width: 43%"  colspan="5">Disbursement Date</th>
																<th  style="width: 43%"  colspan="3">Responsible</th>
															</tr>
															<tr class="collapse budget_line' . $rmkid . '"  style="">  
																<td>=></td>
																<td colspan="5"> ' . date_format($disbursement_date, 'd M Y') . ' </td>
																<td colspan="3"> ' . $ptnname . ' </td>
															</tr>
															<tr class="collapse budget_line' . $rmkid . '"  style="background-color:#9E9E9E; color:#FFF">  
																<th style="width: 2%">#</th>
																<th  style="width:78%"  colspan="5">Financiers</th>
																<th  style="width: 20%"  colspan="3">Amount</th>
															</tr>';
															
															$rowno = 0;
															$totalAmount = 0;
															// query the
															$query_rsProjFinancier = $db->prepare("SELECT * FROM tbl_project_cost_funders_share WHERE projid =:projid AND outputid=:outputid AND plan_id=:plan_id ORDER BY amount desc");
															$query_rsProjFinancier->execute(array(":projid" => $projid, ":outputid" => $outputid, ":plan_id" => $rmkid));
															$row_rsProjFinancier = $query_rsProjFinancier->fetch();
															$totalRows_rsProjFinancier = $query_rsProjFinancier->rowCount();

															if ($totalRows_rsProjFinancier > 0) {
																do {
																	$rowno++; 
																	$projfinid = $row_rsProjFinancier['funder'];
																	$projamountfunding =  $row_rsProjFinancier['amount'];
																	$totalAmount = $projamountfunding + $totalAmount; 
																	$inputs = '';

																	$query_rsFunding =  $db->prepare("SELECT * FROM tbl_myprojfunding WHERE id =:projfinid AND projid=:projid ");
																	$query_rsFunding->execute(array(":projfinid" => $projfinid, ":projid" => $projid));
																	$row_rsFunding = $query_rsFunding->fetch();
																	$totalRows_rsFunding = $query_rsFunding->rowCount();
																	$projamount = $row_rsFunding['amountfunding'];
																	$sourcecat = $row_rsFunding['sourcecategory'];
																	
																	$query_rsFundingType = $db->prepare("SELECT * FROM tbl_funding_type WHERE id='$sourcat'");
																	$query_rsFundingType->execute();
																	$row_rsFundingType = $query_rsFundingType->fetch();
																	$totalRows_rsFundingType = $query_rsFundingType->rowCount();
																	$category = $row_rsFundingType['category'];
																	
																	$inputs = '';
																	if ($category==2) {
																		$query_rsDonor = $db->prepare("SELECT * FROM tbl_donors WHERE dnid='$source'");
																		$query_rsDonor->execute();
																		$row_rsDonor = $query_rsDonor->fetch();
																		$totalRows_rsDonor = $query_rsDonor->rowCount();
																		$donor = $row_rsDonor['donorname']; 
																		$inputs .= '<span>' . $donor . '</span>'; 
																	} else {
																		$query_rsFunder = $db->prepare("SELECT * FROM tbl_funder WHERE id='$source'");
																		$query_rsFunder->execute();
																		$row_rsFunder = $query_rsFunder->fetch();
																		$totalRows_rsFunder = $query_rsFunder->rowCount();
																		$funder = $row_rsFunder['name']; 
																		$inputs .= '<span>' . $funder . '</span>'; 
																	}
																	
																	$projectPlan .= '
																	<tr class="collapse budget_line' . $rmkid . '">
																		<td> 
																			' . $rowno . '
																		</td>
																		<td colspan="5"> 
																			' . $inputs . '
																		</td>
																		<td style="text-align:left" colspan="3">
																			' . number_format($projamountfunding, 2) . '
																		</td>
																	</tr>';
																} while ($row_rsProjFinancier = $query_rsProjFinancier->fetch());
																
																$projectPlan .= ' 
																<tr class="collapse budget_line' . $rmkid . '"  style="background-color:#9E9E9E; color:#FFF">  
																	<th style="width: 2%">#</th>
																	<th  style="width: 96%"  colspan="8">Remarks</th>
																</tr>
																<tr class="collapse budget_line' . $rmkid . '"  style="">
																	<td>=></td>
																	<td colspan="8"> ' . $comments . ' </td>
																</tr>';
															}
														} while ($row_rsOther_cost_plan = $query_rsOther_cost_plan->fetch());
														$other_per  = ($other_sum / $outputCost) * 100;
														$other_percent = number_format($other_per, 2);
														$projectPlan .= '
													</tbody">
													<tfoot>
														<tr>
															<td colspan="1"><strong>Sub Total</strong></td>
															<td colspan="2">
																<strong>' . number_format($other_sum, 2) . '</strong>
															</td>
															<td colspan="1"> <strong>% Sub Total</strong></td>
															<td colspan="1">
																<strong>' . $other_percent . ' %</strong>
															</td> 
															<td colspan="1"> <strong>Output Budget Bal</strong></td>
															<td colspan="2">
																<strong>' . number_format(($outputCost - $output_remeinder), 2) . '</strong>
															</td>
														</tr>
													</tfoot>
												</table>
											</div>';
										}
									} while ($row_rsBilling = $query_rsBilling->fetch());
								}
								$projectPlan .= '   
							</div>
						</div>
					</div>
				</div>
			</div>
			<script>
				$(".collapsed").click(function(e) {
					e.preventDefault(); 
					$(this)
					.find("i")
					.toggleClass("fa-plus-square fa-minus-square");
				});
				$(".careted").click(function(e) {
					e.preventDefault(); 
					$(this)
					.find("i")
					.toggleClass("fa fa-caret-down fa fa-caret-up");
				});
			</script>';
			
			$summary  .= '
			<tr>
				<td>' . $Ocounter . '</td>
				<td>' . $outputName . '</td>
				<td style="text-align:left">' . number_format($outputCost, 2) . '</td>
				<td id="summaryOutput' . $outputid . '"  style="text-align:left">' . number_format($output_remeinder, 2) . '</td>
				<td id="perc' . $outputid .  '"  style="text-align:left">' . number_format((($output_remeinder / $outputCost) * 100), 2) . ' %</td>
			</tr>';
		} while ($row_rsOutputs = $query_rsOutputs->fetch());

        $projectPlan .= ' 
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
