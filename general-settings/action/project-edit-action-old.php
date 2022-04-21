<?php

include_once "controller.php";
try {
	$valid['success'] = array('success' => false, 'messages' => array());
	//fetch items 
	if (isset($_POST['approveMultiple'])) {
		$itemId = $_POST['itemId'];
		$list = '';
		$listA = '';
		$listB = '';
		$programR = '';
		$listCount = 0;
		$idcount = 1;
		$list .= '  
				<div class="row clearfix">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="card"> 
							<div class="header" >
								<div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
									<h4>Approve Projects</h4> 
								</div>  
							</div>
							<div class="body">';
		$listA .= '	<div class="row clearfix">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-hover" id="multipleProjects" style="width:100%">
													<thead>
														<tr>
															<th>#</th>
															<th> Name</th>
															<th> Exp Start Date</th>
															<th> Exp End Date </th>  
														</tr>
													</thead>
													<tbody id="" >';
		for ($i = 0; $i  < count($itemId); $i++) {
			$listCount++;
			$idcount++;
			$query_item = $db->prepare("SELECT projname, progid, projid, yr, projduration, projenddate, projstartdate FROM tbl_projects INNER JOIN tbl_fiscal_year ON tbl_fiscal_year.id = tbl_projects.projfscyear WHERE projid = '$itemId[$i]'");
			$query_item->execute();
			$rows_count = $query_item->rowCount();
			$data =  $query_item->fetch();

			if ($rows_count > 0) {
				$startyear =  $data['yr'];
				$projenddate = $data['projenddate'];
				$projstartdate = $data['projstartdate'];
				$projduration =  $data['projduration'];
				$progid =  $data['progid'];

				//fetch program details 
				$query_item = $db->prepare("SELECT * FROM tbl_programs WHERE tbl_programs.progid = '$progid'");
				$query_item->execute();
				$row_item = $query_item->fetch();

				$progstartDate = $row_item['syear'] . '-07-01'; //program start date  
				$projectendDate = date('Y-m-d', strtotime($progstartDate . " + {$projduration} days"));  //program end date  
				$startyear = $startyear . '-07-01';
				$listB .= '	
																		<tr> 
																			<td>' . $listCount . '</td> 
																			<td> 
																			<input type="hidden" name="projid[]" id="projid' . $idcount . '"  value="' . $data['projid'] . '" class= "form-control">
																			' . $data['projname'] . '</td>
																			<td>
																			' . date('d M Y', strtotime($startyear)) . '
																			</td> 
																			<td> 
																			' . date('d M Y', strtotime($projectendDate)) . '
																			</td>  
																		</tr>';
			}
		}
		$list .=  $programR . $listA . $listB;
		$list .= '   
													</tbody>
												</table> 
											</div>
										</div>
									</div>
								</div> 
							</div>
						</div>
					</div> 
				</div> ';
		echo $list;
	}

	//fetch items for delete  
	if (isset($_POST['fetchItems'])) {
		$itemId = $_POST['itemId'];
		$list = '';
		$input = '';
		$contain = '';
		$list .= '<ol>';
		$contain .= '<div>';
		for ($i = 0; $i  < count($itemId); $i++) {
			$query_item = $db->prepare("SELECT projname, projid FROM tbl_projects WHERE projid = '$itemId[$i]'");
			$query_item->execute();
			$rows_count = $query_item->rowCount();
			$data =  $query_item->fetch();
			if ($rows_count > 0) {
				$list .= '<li> ' . $data['projname'] . ' </li>';
				$input .= '<input type="hidden" name="itemids[]" value="' .  $data['projid']  . '">';
			}
		}
		$list .= '</ol>';
		$contain .= $list;
		$contain .= $input;
		$contain .= '</div>';
		echo $contain;
	}

	//approve item
	if (isset($_POST["approveitem"])) {
		$projid = $_POST['projid'];
		$progid = $_POST['progid'];
		$approved = $_POST['approveitem'];
		$projcost = $_POST['projcost']; // cost of each output 
		$projoutputduration = $_POST['projoutputduration']; //output duration 

		$queryODetails = $db->prepare("SELECT * FROM tbl_project_details WHERE projid = '$projid'");
		$queryODetails->execute();
		$data_ODetails =  $queryODetails->fetch();
		do {
			$insertOutputHistory = $db->prepare("INSERT INTO `tbl_project_details_history`(pdid, progid, projid, outputid, indicator, year, duration, budget) 
			VALUES(:pdid, :progid, :projid, :outputid,:indicator, :year, :duration, :budget)");
			$resultOutputresults  = $insertOutputHistory->execute(array(":pdid" => $data_ODetails['id'], ":progid" => $progid, ":projid" => $projid, ":outputid" => $data_ODetails['outputid'], ":indicator" => $data_ODetails['indicator'], ":year" => $data_ODetails['year'], ":duration" => $data_ODetails['duration'], ":budget" => $data_ODetails['budget']));
		} while ($data_ODetails =  $queryODetails->fetch());

		if ($resultOutputresults === TRUE) {
			for ($i = 0; $i < count($_POST['projoutput']); $i++) {
				$outputid = $_POST['projoutput'][$i];
				$indicator = $_POST['indicator'][$i];
				$year = $_POST['opstaryear'][$i];
				$duration = $_POST['projoutputduration'][$i];
				$budget = $_POST['projcost'][$i];

				$insertSQL1 = $db->prepare("UPDATE tbl_project_details  SET duration=:duration, budget=:budget WHERE projid=:projid AND outputid=:outputid");
				$result1  = $insertSQL1->execute(array(":duration" => $duration, ":budget" => $budget, ":projid" => $projid, ":outputid" => $outputid));
			}

			$query_rsFinancier = $db->prepare("SELECT * FROM tbl_myprojfunding WHERE projid = '$projid'");
			$query_rsFinancier->execute();
			$data__rsFinancier =  $query_rsFinancier->fetch();
			
			do {
				$insertSQL1 = $db->prepare("INSERT INTO `tbl_myprojfunding_history`(fundid, progid, projid, progfundid, amountfunding, currency, rate, created_by, date_created) VALUES(:fundid, :progid, :projid, :progfundid, :amountfunding, :currency,:rate, :created_by, :date_created)");
				$result1  = $insertSQL1->execute(array(":fundid" => $data__rsFinancier['id'], ":progid" => $progid, ":projid" => $projid, ":progfundid" => $data__rsFinancier['progfundid'], ":amountfunding" => $data__rsFinancier['amountfunding'], ":currency" => $data__rsFinancier['currency'], ":rate" => $data__rsFinancier['rate'], ":created_by" => $data__rsFinancier['created_by'], ":date_created" => $data__rsFinancier['date_created']));
			} while ($data__rsFinancier =  $query_rsFinancier->fetch());

			for ($j = 0; $j < count($_POST['amountfunding']); $j++) {
				$amountfunding = $_POST['amountfunding'][$j];
				$progfundid = $_POST['progfundid'][$j];
				$insertSQL1 = $db->prepare("UPDATE tbl_myprojfunding  SET amountfunding=:amountfunding WHERE projid=:projid AND progfundid=:progfundid");
				$result1  = $insertSQL1->execute(array(":amountfunding" => $amountfunding, ":projid" => $projid, ":progfundid" => $progfundid));
			}

			//  select data to be moved to the history table 
			$queryDetails = $db->prepare("SELECT * FROM tbl_project_output_details WHERE projid = '$projid'");
			$queryDetails->execute();
			$data_Details = $queryDetails->fetch();

			do {
				$insertTargetHistory = $db->prepare("INSERT INTO `tbl_project_output_details_history`(odid, projoutputid, progid, projid, indicator, year, target) VALUES(:odid, :projoutputid, :progid, :projid, :indicator, :qyear, :target)");
				$insertTargetHistory->execute(array(":odid" => $data_Details['id'], ":projoutputid" => $data_Details['projoutputid'], ":progid" => $data_Details['progid'], ":projid" => $data_Details['projid'], ":indicator" => $data_Details['indicator'], ":qyear" => $data_Details['year'], ":target" => $data_Details['target']));
			} while ($data_Details =  $queryDetails->fetch());

			// delete tbl_project_output_details table 
			$deleteQuery = $db->prepare("DELETE FROM `tbl_project_output_details` WHERE projid=:projid");
			$results = $deleteQuery->execute(array(':projid' => $projid));


			for ($p = 0; $p < count($_POST['indicator']); $p++) {

				for ($j = 0; $j < count($_POST['projyear']); $j++) {
					$target = $_POST['target'][$j];
					$projoutputValue = $_POST['projoutputValue'][$j];
					$qyear = $_POST['projyear'][$j];

					$outputid = $_POST['projoutputid'][$p];
					$indicator = $_POST['indicator'][$p];

					$insertSQL2 = $db->prepare("INSERT INTO `tbl_project_output_details`(projoutputid, progid, projid, indicator, year, target)  VALUES(:projoutputID,:progid,:projid, :indicator, :qyear, :target)");
					$result2  = $insertSQL2->execute(array(":projoutputID" => $outputid, ":progid" => $progid, ":projid" => $projid, ":indicator" => $indicator, ":qyear" => $qyear, ":target" => $target));
				}
			}

			$stage = 2;
			$sumCost = array_sum($_POST['projcost']);
			$approveItemQuery = $db->prepare("UPDATE `tbl_projects` SET projcost=:projcost, projplanstatus=:approved, projstage=:stage WHERE projid=:projid");
			$results = $approveItemQuery->execute(array(':projcost' => $sumCost, ":approved" => $approved, ":stage" => $stage, ':projid' => $projid));

			$valid['success'] = true;
			$valid['messages'] = "Successfully Approved";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while approving!!";
		}
		echo json_encode($valid);
	}

	//approval of multiple projects  
	if (isset($_POST["approveItems"])) {
		$approved = 1;
		$stage = 2;
		$projid = $_POST['projid'];
		for ($i = 0; $i  < count($projid); $i++) {
			$approveItemQuery = $db->prepare("UPDATE `tbl_projects` set projplanstatus=:approved, projstage=:stage WHERE projid=:projid");
			$results = $approveItemQuery->execute(array(":approved" => $approved, ":stage" => $stage, ':projid' => $projid[$i]));
			if ($results === TRUE) {
				$valid['success'] = true;
				$valid['messages'] = "Successfully Approved";
			} else {
				$valid['success'] = false;
				$valid['messages'] = "Error while approving!!";
			}
		}
		echo json_encode($valid);
	}
	//Un approve single item 
	if (isset($_POST["unapproveitem"])) {
		$projid = $_POST['itemId'];
		$approved = 0;
		$stage = 1;
		$approveItemQuery = $db->prepare("UPDATE `tbl_projects` set  projplanstatus=:approved, projstage=:stage WHERE projid=:projid");
		$results = $approveItemQuery->execute(array(":approved" => $approved, ":stage" => $stage, ':projid' => $projid));
		if ($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Unapproved ";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while Unapproving  the record!!";
		}
		echo json_encode($valid);
	}

	//delete one item 
	if (isset($_POST["deleteItem"])) {
		$itemid = $_POST['itemId'];
		$deleteQuery = $db->prepare("DELETE FROM `tbl_projects` WHERE projid=:itemid");
		$results = $deleteQuery->execute(array(':itemid' => $itemid));

		if ($results === TRUE) {

			$deleteQueryR = $db->prepare("DELETE FROM `tbl_myprojpartner` WHERE projid=:itemid");
			$resultsR = $deleteQueryR->execute(array(':itemid' => $itemid));

			$deleteQueryR = $db->prepare("DELETE FROM `tbl_myprojfunding` WHERE projid=:itemid");
			$resultsR = $deleteQueryR->execute(array(':itemid' => $itemid));

			$deleteQueryR = $db->prepare("DELETE FROM `tbl_project_beneficiaries` WHERE projid=:itemid");
			$resultsR = $deleteQueryR->execute(array(':itemid' => $itemid));

			// $deleteQueryR = $db->prepare("DELETE FROM `tbl_project_beneficiary_disaggregation` WHERE projid=:itemid");
			// $resultsR = $deleteQueryR->execute(array(':itemid' => $itemid)); 

			$deleteQueryR = $db->prepare("DELETE FROM `tbl_output_risks` WHERE projid=:itemid");
			$resultsR = $deleteQueryR->execute(array(':itemid' => $itemid));

			$deleteQueryI = $db->prepare("DELETE FROM `tbl_project_expected_impact_details` WHERE projid=:itemid");
			$resultsI = $deleteQueryI->execute(array(':itemid' => $itemid));

			$deleteQueryE = $db->prepare("DELETE FROM `tbl_project_expected_outcome_details` WHERE projid=:itemid");
			$resultsE = $deleteQueryE->execute(array(':itemid' => $itemid));

			$deleteQueryO = $db->prepare("DELETE FROM `tbl_project_details` WHERE projid=:itemid");
			$resultsO = $deleteQueryO->execute(array(':itemid' => $itemid));

			if ($resultsO === TRUE) {
				$deleteQueryOD = $db->prepare("DELETE FROM `tbl_project_outputs` WHERE projid=:itemid");
				$resultsOD = $deleteQueryOD->execute(array(':itemid' => $itemid));

				$deleteQueryD = $db->prepare("DELETE FROM `tbl_project_output_details` WHERE projid=:itemid");
				$resultsD = $deleteQueryD->execute(array(':itemid' => $itemid));
				if ($results === TRUE) {
					$valid['success'] = true;
					$valid['messages'] = "Successfully Deleted";
				} else {
					$valid['success'] = false;
					$valid['messages'] = "Error while deletng the record!!";
				}
			} else {
				$valid['success'] = false;
				$valid['messages'] = "Error while deletng the record!!";
			}
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while deletng the record!!";
		}


		echo json_encode($valid);
	}

	//delete multile items 
	if (isset($_POST["deleteItems"])) {
		$itemid = $_POST['itemId'];
		for ($i = 0; $i  < count($itemid); $i++) {
			$deleteQuery = $db->prepare("DELETE FROM `tbl_projects` WHERE projid=:itemid");
			$results = $deleteQuery->execute(array(':itemid' => $itemid[$i]));

			$deleteQueryR = $db->prepare("DELETE FROM `tbl_myprojpartner` WHERE projid=:itemid");
			$resultsR = $deleteQueryR->execute(array(':itemid' => $itemid[$i]));

			$deleteQueryR = $db->prepare("DELETE FROM `tbl_myprojfunding` WHERE projid=:itemid");
			$resultsR = $deleteQueryR->execute(array(':itemid' => $itemid[$i]));

			$deleteQueryR = $db->prepare("DELETE FROM `tbl_project_beneficiaries` WHERE projid=:itemid");
			$resultsR = $deleteQueryR->execute(array(':itemid' => $itemid[$i]));

			// $deleteQueryR = $db->prepare("DELETE FROM `tbl_project_beneficiary_disaggregation` WHERE projid=:itemid");
			// $resultsR = $deleteQueryR->execute(array(':itemid' => $itemid[$i]));

			$deleteQueryR = $db->prepare("DELETE FROM `tbl_output_risks` WHERE projid=:itemid");
			$resultsR = $deleteQueryR->execute(array(':itemid' => $itemid[$i]));

			$deleteQueryI = $db->prepare("DELETE FROM `tbl_project_expected_impact_details` WHERE projid=:itemid");
			$resultsI = $deleteQueryI->execute(array(':itemid' => $itemid[$i]));

			$deleteQueryE = $db->prepare("DELETE FROM `tbl_project_expected_outcome_details` WHERE projid=:itemid");
			$resultsE = $deleteQueryE->execute(array(':itemid' => $itemid[$i]));

			$deleteQueryO = $db->prepare("DELETE FROM `tbl_project_details` WHERE projid=:itemid");
			$resultsO = $deleteQueryO->execute(array(':itemid' => $itemid[$i]));

			$deleteQueryD = $db->prepare("DELETE FROM `tbl_project_output_details` WHERE projid=:itemid");
			$resultsD = $deleteQueryD->execute(array(':itemid' => $itemid[$i]));
		}

		if ($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Deleted";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while deletng the record!!";
		}
		echo json_encode($valid);
	}

	if (isset($_POST['getTargetDiv'])) {
		$programid = $_POST['progid'];
		$projid = $_POST['projid'];
		$year = $_POST['year'];
		$output = $_POST['outputid'];
		$indicatorid = $_POST['indicatorid'];
		$projoutputDValue = $_POST['outputduration'];
		$counter = $_POST['counter'];

		$Targets = '';
		// get indicator name  
		$query_rsIndicator = $db->prepare("SELECT indname, indid FROM tbl_indicator WHERE indid ='$indicatorid'");
		$query_rsIndicator->execute();
		$row_rsIndicator = $query_rsIndicator->fetch();
		$indname = $row_rsIndicator['indname'];

		$query_Indicator = $db->prepare("SELECT tbl_measurement_units.unit FROM tbl_indicator  INNER JOIN tbl_measurement_units ON tbl_measurement_units.id =tbl_indicator.unit WHERE tbl_indicator.indid ='$indicatorid' AND baseline=1 AND indcategory='Output' ");
		$query_Indicator->execute();
		$row = $query_Indicator->fetch();
		$unit = $row['unit'];

		// Get outputstart year 
		$query_rsIndicatorYear =  $db->prepare("SELECT yr FROM tbl_fiscal_year WHERE id='$year'");
		$query_rsIndicatorYear->execute();
		$row_rsIndicatorYear = $query_rsIndicatorYear->fetch();
		$projstartyear = $row_rsIndicatorYear['yr'];

		// get output name  
		$query_rsOutput = $db->prepare("SELECT * FROM tbl_progdetails WHERE id='$output'");
		$query_rsOutput->execute();
		$row_rsOutput = $query_rsOutput->fetch();
		$outputName = $row_rsOutput['output'];

		$durationinmonths = floor($projoutputDValue / 30.4);
		$remainingdays = $projoutputDValue % 365;

		if ($remainingdays > 0) {
			$durationinmonths = $durationinmonths + 1;
		}

		$quaters = round($durationinmonths / 3);
		$remainderq = $durationinmonths % 3;

		if ($quaters == 0) {
			$quaters = $quaters + 1;
		}

		$startyear = $projstartyear + 1;
		$noofyears = $quaters / 4;
		$spanYear = '';
		$TargetPlan = '';
		$containerTH = '';
		$containerTH2 = '';
		$projoutputValue = $output;
		$projindicator = $indicatorid;
		$containerTB  = "<tr>";
		$contain  = "";

		for ($rowno = 0; $rowno < $noofyears; $rowno++) {
			$query_rsprogTarget =  $db->prepare("SELECT target FROM tbl_progdetails WHERE indicator ='$indicatorid' and progid ='$programid' and year ='$projstartyear'");
			$query_rsprogTarget->execute();
			$row_rsprogTarget = $query_rsprogTarget->fetch();
			$totalRows_rsprogTarget = $query_rsprogTarget->rowCount();
			$progTarget = $row_rsprogTarget['target'];

			$query_rsprojTarget =  $db->prepare("SELECT SUM(target) as target FROM tbl_project_output_details WHERE indicator ='$indicatorid' and progid ='$programid' and year ='$projstartyear'");
			$query_rsprojTarget->execute();
			$row_rsprojTarget = $query_rsprojTarget->fetch();
			$totalRows_rsprojTarget = $query_rsprojTarget->rowCount();
			$projTarget = $row_rsprojTarget['target'];
			$remaining = $progTarget - $projTarget;

			$arr = '';
			if ($remaining > 0) {
				$arr =  $remaining;
			} else {
				$arr =  0;
			}

			$spanYear .=
				'<div id="proj' .  $projstartyear .  "/" .  $startyear . '" class="col-md-4"> 
                        Project 
                    <strong> ' .  $projstartyear . "/" . $startyear . ' </strong> Annual Target Plan<br>
                        <span id="projmsg' . $projstartyear .  $counter . '" style="color:red">' . $arr . '</span></div>';
			$TargetPlan .=
				'<input type="hidden" class="projdurationerow' . $counter . '" name="projTragetplan[]" value="' . $projstartyear . '" />';
			if ($quaters > 4) {
				$containerTH .=
					'<th colspan="4">' . $projstartyear .  "/" . $startyear . ' 
                        <input type="hidden" class="output' . $output . '"  name="projoutputYearValue[]" value="' . $projstartyear . '" /> 
                        <input type="hidden" name="targetPlan[]" value="' . $arr  .  '" id="targetVal' . $projstartyear .  $counter . '" >
                        Remaining Target <span id="targetmsg' . $projstartyear .  $counter . '" style="color:red"> (' . $arr . ')</span>  </th>';

				for ($j = 0; $j < 4; $j++) {
					$k = $j + 1;
					$containerTH2 .=
						'<th width="300px"> Quarter ' .  $k .
						' Target</th>';
					$containerTB .=
						'<td width="300px"> <input type="hidden"  name="projoutputValue[]" value="' . $projoutputValue . '" />  ' .
						'<input type="number" onkeyup=targetsBlur("' .  $counter  .  '","' .  $projstartyear . '") 
                            name="target[]" id="' .  $projstartyear . '" placeholder="Enter" data-id="' .  $projstartyear . '"
                            class="form-control selectSource' . $counter .  $projstartyear .
						" selected" . $output . $projstartyear . '" required>' .
						"</td>";
					$contain  .=
						'<input type="hidden" name="projyear[]"   value="' . $projstartyear .  '" />';
				}
			} else {
				$containerTH   .=
					'<th colspan="' . $quaters .     '" >' . $projstartyear . "/" . $startyear .
					'
							<input type="hidden" class="output' . $output . '"  name="projoutputYearValue[]" value="' . $projstartyear . '" /> 
                            <input type="hidden" name="targetPlan[]" value="' . $arr  .  '" id="targetVal' . $projstartyear .  $counter . '" >
                            Remaining Target <span id="targetmsg' . $projstartyear .  $counter . '" style="color:red"> (' . $arr . ')</span> </th>';
				for ($j = 0; $j < $quaters; $j++) {
					$k = $j + 1;
					$containerTH2 .=
						'<th width="300px"> Quarter ' .  $k .
						' Target</th>';
					$containerTB  .=
						'<td width="300px">
								<input type="hidden"  name="projoutputValue[]" value="' .  $projoutputValue . '" /> ' .
						'<input type="number" name="target[]" id="' . $projstartyear . '" onkeyup=targetsBlur("' .  $counter  .  '","' .  $projstartyear . '")   
                            placeholder="Enter" data-id="' . $projstartyear . '" class="form-control selectSource' . $counter . $projstartyear .
						" selected" . $output . $projstartyear . '" required>
                            </td>';
					$contain  .= '<input type="hidden"  name="projyear[]" value="' . $projstartyear . '" />';
				}
			}
			$quaters = $quaters - 4;
			$projstartyear = $projstartyear + 1;
			$startyear = $startyear + 1;
		}
		$containerTH2 . $containerTB  .= "</tr>";

		$Targets  .= '
                <div class="row clearfix " id="rowcontainerrow' . $counter . '">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
								<div class="col-md-4 clearfix" style="margin-top:5px; margin-bottom:5px">
									<h5 style="color:#FF5722"><strong> Output: ' . $outputName . '</strong></h5>
                                </div>
								<div class="col-md-4 clearfix" style="margin-top:5px; margin-bottom:5px">
                                    <h5 style="color:#FF5722"><strong> Indicator: ' . $indname . '</strong></h5>
                                </div>
                                <div class="col-md-4 clearfix" style="margin-top:5px; margin-bottom:5px">
                                    <h5 style="color:#FF5722"><strong> Unit : ' . $unit . '</strong></h5>
                                </div>
                                
                            </div>
                            <div class="body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="spanYears">
                                            ' . $TargetPlan . '
                                            ' . $contain . '
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover" id="targets" style="width:100%">
                                        <thead>
                                            <tr>
                                                ' . $containerTH . '
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ' . $containerTH2 . $containerTB . '
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';

		echo $Targets;
	}
} catch (PDOException $ex) {
	// $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $ex->getMessage();
}
