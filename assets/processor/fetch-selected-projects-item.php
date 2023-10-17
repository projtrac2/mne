<?php
include_once "controller.php";
$projid = $_POST['itemId'];
try {
    //get the project name 
    $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid=:projid");
    $query_rsProjects->execute(array(":projid" => $projid));
    $row_rsProjects = $query_rsProjects->fetch();
    $totalRows_rsProjects = $query_rsProjects->rowCount();
    $stakeholders = '';
    $projectDetails = '';
    $MEPlan = '';
    $MEPlan1 = '';
    $MEPlan2 = '';
    $MEPlan3 = '';
    $MEPlan4 = '';
    $WorkPlan = '';

    if ($totalRows_rsProjects > 0) {
        //get the project name  
        $progid = $row_rsProjects['progid'];
        $projcode = $row_rsProjects['projcode'];
        $projname = $row_rsProjects['projname'];
        $projstatus  = $row_rsProjects['projstatus'];
        $projtype = $row_rsProjects['projtype'];
        $projbudget = $row_rsProjects['projbudget'];
        $projfscyear = $row_rsProjects['projfscyear'];
        $projduration = $row_rsProjects['projduration'];
        $projinspection = $row_rsProjects['projinspection'];
        $projmapping  = $row_rsProjects['projmapping'];
        $projins = "";
        if ($projinspection == 0) {
            $projins = "Yes";
        } else if ($projinspection == 0) {
            $projins = "No";
        }

        $projstatement = $row_rsProjects['projstatement'];
        $projsolution = $row_rsProjects['projsolution'];
        $projcase = $row_rsProjects['projcase'];
        $projcommunity = explode(",", $row_rsProjects['projcommunity']);
        $projlga = explode(",", $row_rsProjects['projlga']);
        $projstate = explode(",", $row_rsProjects['projstate']);
        $projlocation = $row_rsProjects['projlocation'];
        $projcategory = $row_rsProjects['projcategory'];
        $projstage  = $row_rsProjects['projstage'];
        $user_name = $row_rsProjects['user_name'];
        $dateentered = $row_rsProjects['date_created'];

        $query_projstage = $db->prepare("SELECT stage FROM tbl_project_workflow_stage WHERE id='$projstage'");
        $query_projstage->execute();
        $row_projstage = $query_projstage->fetch();
        $projectstage = $row_projstage['stage'];

        $projstatus = 1;
        $query_projstatus = $db->prepare("SELECT statusname FROM tbl_status WHERE statusid='$projstatus'");
        $query_projstatus->execute();
        $row_projstatus = $query_projstatus->fetch();
        $projectstatus = $row_projstatus['statusname'];

        $level1  = [];
        for ($i = 0; $i < count($projcommunity); $i++) {
            $query_rslga = $db->prepare("SELECT * FROM tbl_state WHERE id ='$projcommunity[$i]' ");
            $query_rslga->execute();
            $row_rslga = $query_rslga->fetch();
            $level1[] = $row_rslga['state'];
        }


        $level2  = [];
        for ($i = 0; $i < count($projlga); $i++) {
            $query_rslga = $db->prepare("SELECT * FROM tbl_state WHERE id ='$projlga[$i]' ");
            $query_rslga->execute();
            $row_rslga = $query_rslga->fetch();
            $level2[] = $row_rslga['state'];
        }

        $level3  = [];
        for ($i = 0; $i < count($projstate); $i++) {
            $query_rslga = $db->prepare("SELECT * FROM tbl_state WHERE id ='$projstate[$i]' ");
            $query_rslga->execute();
            $row_rslga = $query_rslga->fetch();
            $level3[] = $row_rslga['state'];
        }

        $query_rsProgram = $db->prepare("SELECT progname, syear, years FROM tbl_programs WHERE deleted='0' and progid='$progid'");
        $query_rsProgram->execute();
        $row_rsProgram = $query_rsProgram->fetch();
        $totalRows_rsProgram = $query_rsProgram->rowCount();
        $progname = $row_rsProgram['progname'];
        $syear = $row_rsProgram['syear'];
        $years = $row_rsProgram['years'];

        //get project implementation methods 
        $query_rsProjImplMethod =  $db->prepare("SELECT DISTINCT id, method FROM tbl_project_implementation_method WHERE id='$projcategory'");
        $query_rsProjImplMethod->execute();
        $row_rsProjImplMethod = $query_rsProjImplMethod->fetch();
        $totalRows_rsProjImplMethod = $query_rsProjImplMethod->rowCount();
        $projimplementationMethod = $row_rsProjImplMethod['method'];


        $query_rsUser =  $db->prepare("SELECT * FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid WHERE userid ='$user_name'");
        $query_rsUser->execute();
        $row_rsUser = $query_rsUser->fetch();
        $userName  = $row_rsUser ?  $row_rsUser['fullname'] : "";

        $query_rsprojPartner =  $db->prepare("SELECT * FROM tbl_myprojpartner WHERE projid ='$projid'");
        $query_rsprojPartner->execute();
        $row_rsprojPartner = $query_rsprojPartner->fetch();
        $lead_implementer  = $row_rsprojPartner['lead_implementer'];
        $implementing_partner  = $row_rsprojPartner['implementing_partner'];

        /* if ($lead_implementer == 0) {
            $query_rsLPartner =  $db->prepare("SELECT company_name FROM tbl_company_settings");
            $query_rsLPartner->execute();
            $row_rsLPartner = $query_rsLPartner->fetch();
            $partnerLName  = $row_rsLPartner['company_name'];
        } else {
            $query_rsLPartner =  $db->prepare("SELECT * FROM tbl_partners WHERE ptnid ='$lead_implementer'");
            $query_rsLPartner->execute();
            $row_rsLPartner = $query_rsLPartner->fetch();
            $partnerLName  = $row_rsLPartner ? $row_rsLPartner['partnername'] : "";
        }

        $partnerimplementerarray = [];
        if ($implementing_partner == 0) {
            $partnerimplementerarray[] = "Not Applicable";
        } else {
            $projpimplementers = explode(",", $implementing_partner);
            $r = 0;
            foreach ($projpimplementers as $projimplementer) {
                $r++;
                $query_projpimplementer = $db->prepare("SELECT partnername FROM tbl_partners WHERE ptnid = '$projimplementer' AND active = '1'");
                $query_projpimplementer->execute();
                while ($row_projpimplementer = $query_projpimplementer->fetch()) {
                    $partnerimplementerarray[] = $r . '.' . $row_projpimplementer["partnername"];
                }
            }
        } */
		

        if ($lead_implementer == 0) {
            $query_rsLPartner =  $db->prepare("SELECT company_name FROM tbl_company_settings");
            $query_rsLPartner->execute();
            $row_rsLPartner = $query_rsLPartner->fetch();
            $partnerLName  = $row_rsLPartner ? $row_rsLPartner['company_name'] : "";
        } else {
            $query_rsLPartner =  $db->prepare("SELECT * FROM tbl_financiers WHERE id ='$lead_implementer'");
            $query_rsLPartner->execute();
            $row_rsLPartner = $query_rsLPartner->fetch();
            $partnerLName  = $row_rsLPartner ? $row_rsLPartner['financier'] : "";
        }

        $partnerimplementerarray = [];
        if ($implementing_partner == 0) {
            $partnerimplementerarray[] = "No Partner/s";
        } else {
            $projpimplementers = explode(",", $implementing_partner);
            $r = 0;
            foreach ($projpimplementers as $projimplementer) {
                $r++;
                $query_projpimplementer = $db->prepare("SELECT financier FROM tbl_financiers WHERE id = '$projimplementer' AND active = '1'");
                $query_projpimplementer->execute();
                while ($row_projpimplementer = $query_projpimplementer->fetch()) {
                    $partnerimplementerarray[] = $r . '.' . $row_projpimplementer["financier"];
                }
            }
        }
        $partnerIName = implode("; ", $partnerimplementerarray);

        $query_rsprojectYear =  $db->prepare("SELECT id, year, yr FROM tbl_fiscal_year WHERE  id='$projfscyear'");
        $query_rsprojectYear->execute();
        $row_rsprojectYear = $query_rsprojectYear->fetch();
        $projectfscyear = $row_rsprojectYear['year'];
        $projstartYear = $row_rsprojectYear['yr'];


        $durationYears = floor($projduration / 365);
        $remaining  = $projduration % 365;
        if ($remaining > 0) {
            $durationYears = $durationYears  + 1;
        }

        $sdate = $projstartYear . '-07-01';
        $newDate = date('Y-m-d', strtotime($sdate . " + {$projduration} days"));
        $projectendYear = $durationYears + $projstartYear;
		$pjendate = date("d M Y", strtotime($newDate));
		$pjstdate = date("d M Y", strtotime($sdate));

        $query_rsBudget =  $db->prepare("SELECT SUM(budget) as budget FROM tbl_project_details WHERE projid ='$projid'");
        $query_rsBudget->execute();
        $row_rsBudget = $query_rsBudget->fetch();
        $totalRows_rsBudget = $query_rsBudget->rowCount();
        $projbudget = $row_rsBudget['budget'];
		$projcost = number_format($projbudget, 2);
		$duration = $projduration;
		$startdatelebel = "Expected Start Date: ";
		$enddatelebel = "Expected End Date: ";
		
		if($projstage > 5){
			if ($projcategory == '2') {
				$query_rsContractDates =  $db->prepare("SELECT startdate, enddate, tenderamount FROM tbl_tenderdetails WHERE projid = :projid");
				$query_rsContractDates->execute(array(":projid" => $projid));
				$row_rsContractDates = $query_rsContractDates->fetch();
				$totalRows_rsContractDates = $query_rsContractDates->rowCount();
				
				$query_tenderdetails =  $db->prepare("SELECT * FROM tbl_project_tender_details WHERE projid = :projid");
				$query_tenderdetails->execute(array(":projid" => $projid));
				
				$totalRows_tenderdetails = $query_tenderdetails->rowCount();
				$tenderamount = 0;
				
				if($totalRows_tenderdetails > 0){
					while($row_tenderdetails = $query_tenderdetails->fetch()){					
						$unitcost = $row_tenderdetails["unit_cost"];
						$unitsno = $row_tenderdetails["units_no"];
						$itemcost = $unitcost * $unitsno;
						$tenderamount = $tenderamount + $itemcost;
					}
				}

				$othercost = 0;
				$query_other_fin_lines =  $db->prepare("SELECT unit_cost, units_no FROM tbl_project_direct_cost_plan WHERE projid = :projid AND tasks IS NULL");
				$query_other_fin_lines->execute(array(":projid" => $projid));
				while ($row_other_fin_lines = $query_other_fin_lines->fetch()) {
					$unitcost = $row_other_fin_lines["unit_cost"];
					$unitsno = $row_other_fin_lines["units_no"];
					$itemcost = $unitcost * $unitsno;
					$othercost = $othercost + $itemcost;
				}

				$consumed = 0;
				$query_consumed =  $db->prepare("SELECT SUM(amount_requested) AS consumed FROM tbl_payments_request WHERE status=3 AND projid = :projid");
				$query_consumed->execute(array(":projid" => $projid));
				$row_consumed = $query_consumed->fetch();

				if ($row_consumed) {
					$consumed = $row_consumed["consumed"];
				}

				$totalcost = $othercost + $tenderamount;

				$date1 = new DateTime($row_rsContractDates["startdate"]);
				$date2 = new DateTime($row_rsContractDates["enddate"]);
				$duration = $date1->diff($date2);
				$duration = $duration->format('%a');
				$pjstdate = date("d M Y", strtotime($row_rsContractDates["startdate"]));
				$pjendate = date("d M Y", strtotime($row_rsContractDates["enddate"]));

				$projcost = number_format($totalcost, 2);
			} else {
				$query_proj_tasks_dates =  $db->prepare("SELECT MIN(sdate) AS projstartdate, MAX(edate) AS projenddate FROM tbl_task WHERE projid = :projid");
				$query_proj_tasks_dates->execute(array(":projid" => $projid));
				$row_proj_tasks_dates = $query_proj_tasks_dates->fetch();

				$date1 = new DateTime($row_proj_tasks_dates["projstartdate"]);
				$date2 = new DateTime($row_proj_tasks_dates["projenddate"]);
				$duration = $date1->diff($date2);
				$duration = $duration->format('%a');
				$pjstdate = date("d M Y", strtotime($row_proj_tasks_dates["projstartdate"]));
				$pjendate = date("d M Y", strtotime($row_proj_tasks_dates["projenddate"]));

				$othercost = 0;
				$query_other_fin_lines =  $db->prepare("SELECT unit_cost, units_no FROM tbl_project_direct_cost_plan WHERE projid = :projid");
				$query_other_fin_lines->execute(array(":projid" => $projid));
				while ($row_other_fin_lines = $query_other_fin_lines->fetch()) {
					$unitcost = $row_other_fin_lines["unit_cost"];
					$unitsno = $row_other_fin_lines["units_no"];
					$itemcost = $unitcost * $unitsno;
					$othercost = $othercost + $itemcost;
				}

				$projcost = number_format($othercost, 2);
			}
			
			$startdatelebel = "Start Date: ";
			$enddatelebel = "End Date: ";
		}

        $projectDetails  .= '
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">  
					<div class="header"> 
						<div class="row clearfix">
						<div class="col-md-3">
							<ul class="list-group"> 
								<li class="list-group-item list-group-item-action active">Project Code: ' . $projcode . ' </li>
							</ul>
						</div>  
						<div class="col-md-9">
							<ul class="list-group"> 
								<li class="list-group-item list-group-item-action active">Project Name: ' . $projname . ' </li>
							</ul>
						</div>  
						</div> 
					</div>   
					<div class="body"> 
						<div class="row clearfix">
							<div class="col-md-12">
								<ul class="list-group"> 
									<li class="list-group-item"><strong>Program Name: </strong>' . $progname . '</li>
								</ul>
							</div>  
							<div class="col-md-6"> 
								<ul class="list-group"> 
									<li class="list-group-item"><strong>Project Budget (Ksh.): </strong>' . $projcost . ' </li>
									<li class="list-group-item"><strong>Project Stage: </strong>' . $projectstage . ' </li>';
									if ($projstage == 1) {
										$projectDetails  .= '<li class="list-group-item"><strong>Project Status: </strong>' . $projectstatus . ' </li>';
									}
									$projectDetails  .= '<li class="list-group-item"><strong>Implementation Method: </strong>' . $projimplementationMethod . ' </li>  
								</ul>
							</div> 
							<div class="col-md-6"> 
								<ul class="list-group"> 
									<li class="list-group-item"><strong>Project Duration: </strong>' . $duration . ' Days </li> 
									<li class="list-group-item"><strong>'.$startdatelebel.'</strong>' . $pjstdate . ' </li>
									<li class="list-group-item"><strong>'.$enddatelebel.'</strong>' . $pjendate . ' </li>
								</ul>
							</div> 
							<div class="col-md-12 table-responsive">
								<table class="table table-bordered table-striped table-hover js-basic-example table-responsive" style="width:100%">
									<tr>
										<th width="5%">#</th>
										<th width="20%">' . $level1label . '</th>
										<th width="20%">' . $level2label . '</th>
										<th width="20%">' . $level3label . '</th> 
									</tr>
									<tr> 
										<td>
											1
										</td> 
										<td>
											' . implode(",", $level1) . '
										</td>
										<td>
											' . implode(",", $level2) . '
										</td>
										<td>
											' . implode(",", $level3) . '
										</td> 
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>';



        $stakeholders .= '  
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card"> 
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="col-md-12"> 
									<ul class="list-group">  
										<li class="list-group-item list-group-item list-group-item-action active">Project Implementors</li>
										<li class="list-group-item"><strong>Implementor : </strong>' . $partnerLName . '</li>
										<li class="list-group-item"><strong>Partners : </strong>' . $partnerIName . ' </li> 
									</ul>
								</div>  
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>';

        if ($projstage == 1) {
            //get project funding 
            $query_rsFunding =  $db->prepare("SELECT jf.amountfunding as totalAmount, jf.sourcecategory, gf.type FROM tbl_projfunding jf INNER JOIN  tbl_funding_type gf ON gf.id = jf.sourcecategory WHERE jf.projid ='$projid'");
            $query_rsFunding->execute();
            $row_rsFunding = $query_rsFunding->fetch();
            $totalRows_rsFunding = $query_rsFunding->rowCount();
            $stakeholders .= '   
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">  
                        <div class="header"> 
                            <li class="list-group-item list-group-item list-group-item-action active">Project Funding Source</li>
                        </div>
                        <div class="body">
                            <div class="table-responsive">  
                                <table class="table table-bordered table-striped table-hover" id="funding_table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th width="3%">#</th>
                                            <th width="72%">Source</th> 
                                            <th width="25%">Amount Funding (Ksh.) </th> 
                                        </tr>
                                    </thead>
                                    <tbody id="funding_table_body">';
										if ($totalRows_rsFunding > 0) {
											$Acounter = 0;
											$TTAmount = 0;
											do {
												$Acounter++;
												$sourceCategory = $row_rsFunding['sourcecategory'];
												$amountfunding = $row_rsFunding['totalAmount'];
												$type = $row_rsFunding['type'];
												$stakeholders .= '
                                                <tr>
                                                    <td>' . $Acounter . '</td> 
                                                    <td>' . $type . '</td>
                                                    <td>' . number_format($amountfunding, 2) . '</td>
                                                </tr>';
												$TTAmount = $TTAmount + $amountfunding;
											} while ($row_rsFunding = $query_rsFunding->fetch());
											$stakeholders .= ' <tr>
												<td colspan="2"><strong>Total Funding</strong></td>
												<td><strong>' . number_format($TTAmount, 2) . '</strong></td>
											</tr>';
										}
										$stakeholders .= '  
                                    </tbody>
                                </table> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        } else {
            //get project funding 
            $query_rsFunding =  $db->prepare("SELECT  * FROM tbl_myprojfunding WHERE projid ='$projid'");
            $query_rsFunding->execute();
            $row_rsFunding = $query_rsFunding->fetch();
            $totalRows_rsFunding = $query_rsFunding->rowCount();

            $stakeholders .= '   
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">  
                        <div class="header"> 
                            <li class="list-group-item list-group-item list-group-item-action active">Project Financiers</li>
                        </div>
                        <div class="body">
                            <div class="table-responsive">  
                                <table class="table table-bordered table-striped table-hover" id="funding_table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th width="3%">#</th>
                                            <th width="25%">Source Category</th> 
                                            <th width="57%">Financier</th> 
                                            <th width="15%">Amount Funding (Ksh.) </th> 
                                        </tr>
                                    </thead>
                                    <tbody id="funding_table_body">';
										if ($totalRows_rsFunding > 0) {
											$Acounter = 0;
											$TTAmount = 0;
											do {
												$Acounter++;
												$sourceCategory = $row_rsFunding['sourcecategory'];
												$financier = $row_rsFunding['financier'];
												$amountfunding = $row_rsFunding['amountfunding'];

												$query_rsFunderSource = $db->prepare("SELECT * FROM tbl_funding_type  WHERE id=:sourceCategory ");
												$query_rsFunderSource->execute(array(":sourceCategory" => $sourceCategory));
												$row_rsFunderSource = $query_rsFunderSource->fetch();
												$totalRows_rsFunderSource = $query_rsFunderSource->rowCount();
												$type = $row_rsFunderSource['type'];

												$query_rsFunder = $db->prepare("SELECT * FROM tbl_financiers  WHERE id=:financier ");
												$query_rsFunder->execute(array(":financier" => $financier));
												$row_rsFunder = $query_rsFunder->fetch();
												$totalRows_rsFunder = $query_rsFunder->rowCount();
												$financier = $row_rsFunder['financier'];

												$stakeholders .= '
                                                <tr>
                                                    <td>' . $Acounter . '</td> 
                                                    <td>' . $type . '</td>
                                                    <td>' . $financier . '</td>
                                                    <td>' . number_format($amountfunding, 2) . '</td>
                                                </tr>';
												$TTAmount = $TTAmount + $amountfunding;
											} while ($row_rsFunding = $query_rsFunding->fetch());
											$stakeholders .= ' 
											<tr>
												<td colspan="3"><strong>Total Funding</strong></td>
												<td><strong>' . number_format($TTAmount, 2) . '</strong></td>
											</tr>';
										}
										$stakeholders .= '  
                                    </tbody>
                                </table> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }

        $query_rsOutput =  $db->prepare("SELECT * FROM tbl_project_details WHERE projid ='$projid' ORDER BY id ASC");
        $query_rsOutput->execute();
        $row_rsOutput = $query_rsOutput->fetch();
        $totalRows_rsOutput = $query_rsOutput->rowCount();

        $MEPlan4 .= '  
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                <div class="body">';
					if ($totalRows_rsOutput > 0) {
						$opcounter = 0;
						do {
							$opcounter++;
							$opid = $row_rsOutput['id'];
							$oipid = $row_rsOutput['outputid'];
							$indicatorID = $row_rsOutput['indicator'];
							$outcomeYear = $row_rsOutput['year'];
							$outputDuration = number_format($row_rsOutput['duration']);
							$outputBudget = number_format($row_rsOutput['budget'], 2);
														
							if($projstage > 5){
								$outputcost = 0;
								if ($projcategory == '2') {
									$query_opcost = $db->prepare("SELECT * FROM tbl_project_tender_details WHERE projid = :projid AND outputid=:outputid");
								} else {
									$query_opcost = $db->prepare("SELECT unit_cost, units_no FROM tbl_project_direct_cost_plan WHERE projid = :projid AND outputid=:outputid");
								}
								$query_opcost->execute(array(":projid" => $projid, ":outputid" => $opid));
								$totalRows_opcost = $query_opcost->rowCount();
								
								if($totalRows_opcost > 0){
									while ($row_opcost = $query_opcost->fetch()) {
										$unitcost = $row_opcost["unit_cost"];
										$unitsno = $row_opcost["units_no"];
										$itemcost = $unitcost * $unitsno;
										$outputcost = $outputcost + $itemcost;
									}
								}

								$query_proj_tasks_dates =  $db->prepare("SELECT MIN(sdate) AS projstartdate, MAX(edate) AS projenddate FROM tbl_task WHERE projid = :projid AND outputid=:outputid");
								$query_proj_tasks_dates->execute(array(":projid" => $projid, ":outputid" => $opid));
								$row_proj_tasks_dates = $query_proj_tasks_dates->fetch();

								$date1 = new DateTime($row_proj_tasks_dates["projstartdate"]);
								$date2 = new DateTime($row_proj_tasks_dates["projenddate"]);
								$duration = $date1->diff($date2);
								$outputDuration = $duration->format('%r%a');
								$outputDuration = number_format($outputDuration);
								$outputBudget = number_format($outputcost, 2);
							}

							$query_Indicator = $db->prepare("SELECT indicator_name,indicator_unit,indicator_mapping_type, indicator_disaggregation FROM tbl_indicator WHERE indid ='$indicatorID'");
							$query_Indicator->execute();
							$row = $query_Indicator->fetch();
							$indname = $row['indicator_name'];
							$unitid = $row['indicator_unit'];
							$disaggregated = $row['indicator_disaggregation'];
							$projwaypoints = $row['indicator_mapping_type'];

							$map = "";
							if ($projmapping == 1) {
								//get mapping type 
								$query_rsMapType =  $db->prepare("SELECT id, type FROM tbl_map_type WHERE id='$projwaypoints'");
								$query_rsMapType->execute();
								$row_rsMapType = $query_rsMapType->fetch();
								if ($row_rsMapType) {
									$totalRows_rsMapType = $query_rsMapType->rowCount();
									$map_type = $row_rsMapType['type'];
									$map = '<li class="list-group-item"><strong>Output Mapping Type: </strong>' . $map_type . '</li>';
								}
							}

							$query_rsOPUnit = $db->prepare("SELECT unit FROM  tbl_measurement_units WHERE id ='$unitid'");
							$query_rsOPUnit->execute();
							$row_rsOPUnit = $query_rsOPUnit->fetch();
							$opunit = $row_rsOPUnit['unit'];


							$query_out = $db->prepare("SELECT * FROM tbl_progdetails WHERE id='$oipid'");
							$query_out->execute();
							$row_out = $query_out->fetch();
							$outputName = $row_out['output'];

							$query_rsYear =  $db->prepare("SELECT id, year, yr FROM tbl_fiscal_year WHERE id='$outcomeYear'");
							$query_rsYear->execute();
							$row_rsYear = $query_rsYear->fetch();
							$fscyear = $row_rsYear['year'];
							$projstartyear = $row_rsYear['yr'];

							// get project details 
							$query_projYear = $db->prepare("SELECT * FROM tbl_project_output_details WHERE projid = '$projid' and projoutputid = '$opid' ORDER BY year");
							$query_projYear->execute();
							$rows_OutpuprojYear = $query_projYear->rowCount();
							$row_projYear =  $query_projYear->fetch();

							$query_rs_bendiss =  $db->prepare("SELECT * FROM tbl_output_disaggregation WHERE outputid='$opid'");
							$query_rs_bendiss->execute();
							$row_rs_bendiss = $query_rs_bendiss->fetch();
							$totalRows_rs_bendiss = $query_rs_bendiss->rowCount();

							$MEPlan4 .= '
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="header"> 
                                            <li class="list-group-item list-group-item list-group-item-action active">Output ' . $opcounter . ': ' . $outputName . ' </li>
                                        </div> 
                                        <div class="body">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"> 
                                                    <ul class="list-group"> 
                                                        <li class="list-group-item"><strong>Indicator: </strong>' . $opunit . " of " . $indname . ' </li>   
                                                        ' . $map . '
                                                    </ul>
                                                </div>  
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"> 
                                                    <ul class="list-group"> 
                                                        <li class="list-group-item"><strong>Financial Start Year: </strong>' . $fscyear . ' </li>  
                                                        <li class="list-group-item"><strong>Duration: </strong>' . $outputDuration . ' Days </li>
                                                        <li class="list-group-item"><strong>Budget: </strong>Ksh.' . $outputBudget . '</li> 
                                                    </ul>
                                                </div> 
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> ';
													if ($rows_OutpuprojYear > 0) {
														$MEPlan4 .= ' <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
														<div class="">
															<h5 class="list-group-item list-group-item list-group-item-action active">Output yearly targets distribution </h5>        
														</div>
															<div class="table-responsive">
																<table class="table table-bordered table-striped table-hover" id="" style="width:100%">
																	<thead>
																		<tr>';
																			$body = '';
																			do {
																				$Pyear =  $row_projYear['year'];
																				$Target =  $row_projYear['target'];
																				$Eyear =  $Pyear + 1;
																				$qt = 0;
																				$MEPlan4 .= '<th>' . $Pyear . '/' . $Eyear . ' Targets (' . $opunit . ')</th>';
																				$body .= '<td>' . number_format($Target) . ' </td>';
																			} while ($row_projYear =  $query_projYear->fetch());

																			$MEPlan4 .= '
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="funding_table_body" > 
                                                                        <tr>';
																			$MEPlan4 .= '
                                                                        </tr>  
                                                                        <tr>';
																			$MEPlan4 .= $body;
																			$MEPlan4 .= '
																		</tr> 
																	</tbody>
																</table> 
															</div> 
														</div>';
													}

													if ($disaggregated == 1) {
														$query_rs_bendiss =  $db->prepare("SELECT * FROM  tbl_output_disaggregation d INNER JOIN tbl_state s ON s.id = d.outputstate WHERE outputid='$opid' ");
														$query_rs_bendiss->execute();
														$row_rs_bendiss = $query_rs_bendiss->fetch();
														$totalRows_rs_bendiss = $query_rs_bendiss->rowCount();

														if ($totalRows_rs_bendiss > 0) {
															$MEPlan4 .= '
															<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
																<div class="">
																	<h5 class="list-group-item list-group-item list-group-item-action active">Output target distribution (Location/s)</h5>        
																</div>
																<div class="table-responsive"> 
																	<table class="table table-bordered table-striped table-hover" id="" style="width:100%">
																		<thead>
																			<tr>
																				<th style="width:5%">#</th>
																				<th style="width:70%">Location</th>
																				<th style="width:25%">Value</th>
																			</tr>
                                                                        </thead>
                                                                        <tbody id="funding_table_body" >';
																			$q = 0;
																			do {
																				$q++;
																				$state =  $row_rs_bendiss['outputstate'];
																				$level3 =  $row_rs_bendiss['state'];
																				$projoutputid =  $row_rs_bendiss['outputid'];

																				$query_rs_bendissgrated =  $db->prepare("SELECT * FROM tbl_indicator_level3_disaggregations s INNER JOIN tbl_projects_location_targets l ON l.locationdisid = s.id WHERE l.projid =:projid and outputid = :opid AND  s.level3=:opstate ");
																				$query_rs_bendissgrated->execute(array(":projid" => $projid, ":opid" => $opid, ":opstate" => $state));

																				$row_rs_bendissgrated = $query_rs_bendissgrated->fetch();
																				$totalRows_rs_bendissgrated = $query_rs_bendissgrated->rowCount();

																				$MEPlan4 .= '
																				<tr>
																					<th colspan="">' . $q . '</th> 
																					<th colspan="2">' . $level3 . '</th> 
																				</tr>';
																				$p = 0;
																				do {
																					$p++;
																					$MEPlan4 .= '
																					<tr>
																						<td>' . $q . "." . $p . '</td>
																						<td>' .  $row_rs_bendissgrated['disaggregations'] . '</td>
																						<td>
																							' . number_format($row_rs_bendissgrated['target']) . " " . $opunit . '
																						</td>
																					</tr>';
																				} while ($row_rs_bendissgrated = $query_rs_bendissgrated->fetch());
																			} while ($row_rs_bendiss = $query_rs_bendiss->fetch());
																			$MEPlan4 .= '
																		</tbody>
																	</table> 
																</div>
															</div>';
														}
													} else {
														$MEPlan4 .= '
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
															<div class="">
																<h5 class="list-group-item list-group-item list-group-item-action active">Output target distribution (Location/s)</h5>        
															</div>
															<div class="table-responsive"> 
																<table class="table table-bordered table-striped table-hover" id="" style="width:100%">
																	<thead>
																		<tr>  
																			<th style="width:5%">#</th>
																			<th style="width:70%">' . $level3label . '</th>
																			<th style="width:25%">Value</th>
																		</tr>
																	</thead> 
                                                                    <tbody id="funding_table_body" >';
																		$query_rs_bendissgrated =  $db->prepare("SELECT * FROM tbl_output_disaggregation WHERE outputid=:opid ");
																		$query_rs_bendissgrated->execute(array(":opid" => $opid));
																		$row_rs_bendissgrated = $query_rs_bendissgrated->fetch();
																		$totalRows_rs_bendissgrated = $query_rs_bendissgrated->rowCount();

																		if ($totalRows_rs_bendissgrated > 0) {
																			$q = 0;
																			do {
																				$q++;
																				$state = $row_rs_bendissgrated['outputstate'];
																				$query_rslga = $db->prepare("SELECT * FROM tbl_state WHERE id =:state ");
																				$query_rslga->execute(array(":state" => $state));
																				$row_rslga = $query_rslga->fetch();
																				$level3 = $row_rslga['state'];

																				$MEPlan4 .= '
                                                                                <tr>  
                                                                                    <td>' . $q   . ' </td> 
                                                                                    <td>' . $level3   . ' </td> 
                                                                                    <td>' . number_format($row_rs_bendissgrated['total_target']) . " " . $opunit . ' </td> 
                                                                                </tr>';
																			} while ($row_rs_bendissgrated = $query_rs_bendissgrated->fetch());
																		}
																		$MEPlan4 .= ' 
																	</tbody>
																</table> 
															</div>
														</div>';
													}
													$MEPlan4 .= ' 
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>';
						} while ($row_rsOutput = $query_rsOutput->fetch());
					}
					$MEPlan4 .= '
                </div>
            </div> 
		</div>';
    }

    $input =  '
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs txt-cyan" role="tablist">
				<li class="active">
					<a href="#1" role="tab" data-toggle="tab"><div style="color:#673AB7">Project Details</div></a>
				</li>  
				<li>
					<a href="#3" role="tab" data-toggle="tab"><div style="color:#673AB7">Output Plan</div></a>
				</li>
				<li>
					<a href="#4" data-toggle="tab"><div style="color:#673AB7">Key Stakeholders</div></a>
                </li>
            </ul>
        </div>
        <div class="card-body tab-content"> 
            <div class="tab-pane active" id="1"> 
                ' . $projectDetails . '
            </div> 
            <div class="tab-pane" id="3"> 
				'  .  $MEPlan4 . '
            </div> 
            <div class="tab-pane" id="4">
                ' . $stakeholders . '
            </div>
        </div>
	</div>';
    echo $input;
} catch (PDOException $ex) {
    // $result = flashMessage("An error occurred: " . $ex->getMessage());
    print($ex->getMessage());
}
