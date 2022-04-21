<?php
include_once "controller.php";

try {
    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "add_budget_line_frm")) {
        $opid = $_POST['opid'];
        $hash = $_POST['projid'];
	
		$dec = explode("encodeprocprj", base64_decode($hash));
		$projid = $dec[1];
		
        $user_name = $_POST['user_name'];
        $datecreated = date("Y-m-d");
		
		if(isset($_POST['contractrefno']) && !empty($_POST['contractrefno'])){
			$evaluation =date('Y-m-d', strtotime($_POST['tenderevaluationdate']));
			$award = date('Y-m-d', strtotime($_POST['tenderawarddate']));
			$contractrefno = $_POST['contractrefno'];
			$tenderno =$_POST['tenderno'];
			$tendertitle = $_POST['tendertitle'];
			$tendertype =$_POST['tendertype'];
			$tendercat = $_POST['tendercat'];
			$tenderamount = $_POST['totalcost'];
			$procurementmethod = $_POST['procurementmethod'];
			$financialscore = $_POST['financialscore'];
			$technicalscore = $_POST['technicalscore'];
			$comments = $_POST['comments'];
			$projcontractor = $_POST['projcontractor'];
			$date_created = date("Y-m-d");
			
			$insertSQL = $db->prepare("INSERT INTO `tbl_tenderdetails` (`projid`, `contractrefno`, `tenderno`, `tendertitle`, `tendertype`, `tendercat`, `tenderamount`, `procurementmethod`, `evaluationdate`, `awarddate`, `notificationdate`, `signaturedate`, `startdate`, `enddate`, `financialscore`, `technicalscore`, `contractor`, `comments`, `created_by`, `date_created`)
			VALUES( :projid, :contractrefno, :tenderno, :tendertitle, :tendertype, :tendercat, :tenderamount, :procurementmethod, :evaluationdate, :awarddate, :notificationdate, :signaturedate, :startdate, :enddate, :financialscore, :technicalscore, :contractor, :comments, :created_by, :date_created)");
				
			$insertSQL->execute(array(":projid"=>$projid, ":contractrefno"=>$contractrefno, ":tenderno"=>$_POST['tenderno'], ":tendertitle"=>$tendertitle, ":tendertype"=>$tendertype, ":tendercat"=>$tendercat, ":tenderamount"=>$tenderamount, ":procurementmethod"=>$procurementmethod, ":evaluationdate"=>$evaluation, ":awarddate"=>$award,
			":notificationdate"=>date('Y-m-d', strtotime( $_POST['tendernotificationdate'])), ":signaturedate"=> date('Y-m-d', strtotime( $_POST['tendersignaturedate'])), ":startdate"=>date('Y-m-d', strtotime($_POST['tenderstartdate'])), ":enddate"=>date('Y-m-d', strtotime($_POST['tenderenddate'])), ":financialscore"=>$financialscore, ":technicalscore"=>$technicalscore, ":contractor"=>$projcontractor, ":comments"=>$comments, ":created_by"=>$user_name, ":date_created"=>$date_created));
			
			
			$last_id = $db->lastInsertId();	
			//--------------------------------------------------------------------------
			// 1)Update project and add tender info
			//--------------------------------------------------------------------------							  
			$update = $db->prepare("UPDATE tbl_projects SET projtender = :projtender, projcontractor = :projcontractor WHERE projid = :projid");
			$update->execute(array(':projtender' => $last_id, ':projcontractor' => $projcontractor, ':projid' => $projid));
		}

        for ($i = 0; $i < count($opid); $i++) {
            $outputid = $opid[$i];

            if (isset($_POST['taskid' . $outputid]) && !empty($_POST['taskid' . $outputid])) {
                $task = $_POST['taskid' . $outputid];
                $mileid = $_POST['mileid' . $outputid];

                for ($k = 0; $k < count($mileid); $k++) {
                    $msid = $mileid[$k];
                    $opmsid = $outputid . $msid;
                    $medate = $msdate = "";

                    if (isset($_POST['mpsdate' . $opmsid])) {
                        $msdate = $_POST['mpsdate' . $opmsid];
                    }

                    if (isset($_POST['mpedate' . $opmsid])) {
                        $medate = $_POST['mpedate' . $opmsid];
                    }

                    // update milestone table 
                    $insertSQL = $db->prepare("UPDATE tbl_milestone SET  sdate=:sdate, edate=:edate WHERE  projid=:projid AND msid=:msid");
                    $results  = $insertSQL->execute(array(':sdate' => $msdate, ':edate' => $medate, ":projid" => $projid, ":msid" => $msid));
                }

                for ($j = 0; $j < count($task); $j++) {
                    $taskid = $task[$j];
                    $optkid = $outputid . $taskid;
                    $dunitcost = $dunit = $dtotalunits = $sdate = $edate = $description = "";
                    $type = 1;

                    if (isset($_POST['description' . $optkid])) {
                        $description = $_POST['description' . $optkid];
                    }

                    if (isset($_POST['hunit' . $optkid])) {
                        $dunit = $_POST['hunit' . $optkid];
                    }

                    if (isset($_POST['dunitcost' . $optkid])) {
                        $dunitcost = $_POST['dunitcost' . $optkid];
                    }

                    if (isset($_POST['dtotalunits' . $optkid])) {
                        $dtotalunits = $_POST['dtotalunits' . $optkid];
                    }

                    if (isset($_POST['psdate' . $optkid])) {
                        $tsdate = $_POST['psdate' . $optkid];
                    }

                    if (isset($_POST['pedate' . $optkid])) {
                        $tedate = $_POST['pedate' . $optkid];
                    }

                    if (isset($_POST['rmkid' . $optkid])) {
                        $costlineid = $_POST['rmkid' . $optkid];
                    }

                    if (!empty($dunitcost)  && !empty($dunit)  && !empty($dtotalunits)) {
                        // update task Table 
                        $insertSQL = $db->prepare("UPDATE tbl_task SET sdate=:sdate, edate=:edate WHERE projid=:projid AND tkid=:tkid");
                        $results  = $insertSQL->execute(array(':sdate' => $tsdate, ':edate' => $tedate, ":projid" => $projid, ":tkid" => $taskid));
						
                        // create procuremt 
                        for ($pt = 0; $pt < count($dunitcost); $pt++) {
                            $insertSQL = $db->prepare("INSERT INTO  tbl_project_tender_details(projid, outputid, costlineid, tasks, description, unit, unit_cost, units_no, created_by, date_created)  VALUES(:projid, :outputid, :costlineid, :tasks,:description, :unit, :unit_cost, :units_no, :created_by, :date_created)");
                            $result  = $insertSQL->execute(array(
                                ":projid" => $projid, ":outputid" => $outputid, ':costlineid' => $costlineid[$pt], ':tasks' => $taskid, ':description' => $description[$pt], ':unit' => $dunit[$pt], ':unit_cost' => $dunitcost[$pt], ':units_no' => $dtotalunits[$pt], ":created_by" => $user_name, ":date_created" => $datecreated
                            ));
                        }
                    }
                }
            }
        }
		
		$myUser = $user_name;
		$count = count($_POST["attachmentpurpose"]);
			
		if($count > 0){
			$filestage = 6;
			$filecategory = 0;
			for($cnt=0; $cnt<$count; $cnt++)
			{ 				
				if(!empty($_FILES['tenderfile']['name'][$cnt])) {
					$purpose = $_POST["attachmentpurpose"][$cnt];
					//Check if the file is JPEG image and it's size is less than 350Kb
					$filename = basename($_FILES['tenderfile']['name'][$cnt]);
					$ext = substr($filename, strrpos($filename, '.') + 1);
					if (($ext != "exe") && ($_FILES["tenderfile"]["type"][$cnt] != "application/x-msdownload"))  {
						$newname=$filestage."-".$filename; 
						$filepath="uploads/procurement/".$newname;       
						//Check if the file with the same name already exists in the server
						if (!file_exists($filepath)) {
							//Attempt to move the uploaded file to it's new place
							if(move_uploaded_file($_FILES['tenderfile']['tmp_name'][$cnt],$filepath)) {
								$qry2 = $db->prepare("INSERT INTO tbl_files (projid, projstage, filename, ftype, floc, fcategory, reason, uploaded_by, date_uploaded) VALUES (:projid, :projstage, :filename, :ftype, :floc, :fcat, :reason, :user, :date)");	
								$qry2->execute(array(':projid' => $projid, ':projstage' => $filestage, ':filename' => $newname, ':ftype' => $ext, ':floc' => $filepath, ':fcat' => $filecategory, ':reason' => $purpose, ':user' => $myUser, ':date'=>$date_created));	
							}	
						}		  
					}	
				}
			}
		}
		
		$projstage = 7;

        //$projid = $_POST['projid'];
        $insertSQL = $db->prepare("UPDATE tbl_projects SET projstage=:projstage WHERE projid=:projid");
        $results  = $insertSQL->execute(array(":projstage" => $projstage, ":projid" => $projid));
        if ($results) {
            echo json_encode("Successfully added Procurement Plan");
        }
    }

    if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "edit_budget_line_frm")) {
        $opid = $_POST['opid'];
        $hash = $_POST['projid'];
        $user_name = $_POST['user_name'];
        $dec =  explode(",", base64_decode($hash));
        $projid =  $dec[1];

        $datecreated = date("Y-m-d");
		
		if(isset($_POST['contractrefno']) && !empty($_POST['contractrefno'])){
			$tdid = $_POST['tenderid'];
			$evaluation =date('Y-m-d', strtotime($_POST['tenderevaluationdate']));
			$award = date('Y-m-d', strtotime($_POST['tenderawarddate']));
			$contractrefno = $_POST['contractrefno'];
			$tenderno =$_POST['tenderno'];
			$tendertitle = $_POST['tendertitle'];
			$tendertype =$_POST['tendertype'];
			$tendercat = $_POST['tendercat'];
			$tenderamount = $_POST['totalcost'];
			$procurementmethod = $_POST['procurementmethod'];
			$financialscore = $_POST['financialscore'];
			$technicalscore = $_POST['technicalscore'];
			$comments = $_POST['comments'];
			$projcontractor = $_POST['projcontractor'];
			$date_created = date("Y-m-d");
			
			$insertSQL = $db->prepare("UPDATE `tbl_tenderdetails` SET `projid` = :projid, `contractrefno` = :contractrefno, `tenderno` = :tenderno, `tendertitle` = :tendertitle, `tendertype` = :tendertype, `tendercat` = :tendercat, `tenderamount` = :tenderamount, `procurementmethod` = :procurementmethod, `evaluationdate` = :evaluationdate, `awarddate` = :awarddate, `notificationdate` = :notificationdate, `signaturedate` = :signaturedate, `startdate` = :startdate, `enddate` = :enddate, `financialscore` = :financialscore, `technicalscore` = :technicalscore, `contractor` = :contractor, `comments` = :comments, `created_by` = :created_by, `date_created` = :date_created WHERE td_id = :tdid");
				
			$insertSQL->execute(array(":projid"=>$projid, ":contractrefno"=>$contractrefno, ":tenderno"=>$tenderno, ":tendertitle"=>$tendertitle, ":tendertype"=>$tendertype, ":tendercat"=>$tendercat, ":tenderamount"=>$tenderamount, ":procurementmethod"=>$procurementmethod, ":evaluationdate"=>$evaluation, ":awarddate"=>$award, ":notificationdate"=>date('Y-m-d', strtotime( $_POST['tendernotificationdate'])), ":signaturedate"=> date('Y-m-d', strtotime( $_POST['tendersignaturedate'])), ":startdate"=>date('Y-m-d', strtotime($_POST['tenderstartdate'])), ":enddate"=>date('Y-m-d', strtotime($_POST['tenderenddate'])), ":financialscore"=>$financialscore, ":technicalscore"=>$technicalscore, ":contractor"=>$projcontractor, ":comments"=>$comments, ":created_by"=>$user_name, ":date_created"=>$date_created, ":tdid" => $tdid));
			
			//--------------------------------------------------------------------------
			// 1)Update project and add tender info
			//--------------------------------------------------------------------------							  
			$update = $db->prepare("UPDATE tbl_projects SET projcontractor = :projcontractor WHERE projid = :projid AND projtender = :projtender");
			$update->execute(array(':projcontractor' => $projcontractor, ':projid' => $projid, ':projtender' => $tdid));

		}

        for ($i = 0; $i < count($opid); $i++) {
            $outputid = $opid[$i];

            if (isset($_POST['taskid' . $outputid]) && !empty($_POST['taskid' . $outputid])) {
                $task = $_POST['taskid' . $outputid];
                $mileid = $_POST['mileid' . $outputid];

                for ($k = 0; $k < count($mileid); $k++) {
                    $msid = $mileid[$k];
                    $opmsid = $outputid . $msid;
                    $medate = $msdate = "";
                    if (isset($_POST['mpsdate' . $opmsid])) {
                        $msdate = $_POST['mpsdate' . $opmsid];
                    }

                    if (isset($_POST['mpedate' . $opmsid])) {
                        $medate = $_POST['mpedate' . $opmsid];
                    }

                    // update milestone table 
                    $insertSQL = $db->prepare("UPDATE tbl_milestone SET sdate=:sdate, edate=:edate WHERE projid=:projid AND msid=:msid");
                    $results  = $insertSQL->execute(array(':sdate' => $msdate, ':edate' => $medate, ":projid" => $projid, ":msid" => $msid));
                }

                for ($j = 0; $j < count($task); $j++) {
                    $taskid = $task[$j];
                    $optkid = $outputid . $taskid;
                    $dunitcost = $dtotalunits = $pid = $tsdate = $tedate = "";
                    $type = 1;

                    if (isset($_POST['dunitcost' . $optkid])) {
                        $dunitcost = $_POST['dunitcost' . $optkid];
                    }

                    if (isset($_POST['dtotalunits' . $optkid])) {
                        $dtotalunits = $_POST['dtotalunits' . $optkid];
                    }

                    if (isset($_POST['psdate' . $optkid])) {
                        $tsdate = $_POST['psdate' . $optkid];
                    }

                    if (isset($_POST['pedate' . $optkid])) {
                        $tedate = $_POST['pedate' . $optkid];
                    }

                    if (isset($_POST['pid' . $optkid])) {
                        $pid = $_POST['pid' . $optkid];
                    }

                    if (!empty($dunitcost) && !empty($dtotalunits) && !empty($tedate) && !empty($tsdate)) {
                        // update task dates 
                        $insertSQL = $db->prepare("UPDATE tbl_task SET sdate=:sdate, edate=:edate WHERE projid=:projid AND tkid=:tkid");
                        $results  = $insertSQL->execute(array(':sdate' => $tsdate, ':edate' => $tedate, ":projid" => $projid, ":tkid" => $taskid));
						
                        // update project procurement details
                        for ($q = 0; $q < count($dunitcost); $q++) {
                            $insertSQL = $db->prepare("UPDATE tbl_project_tender_details SET unit_cost=:unit_cost, units_no=:units_no, update_by=:created_by, date_updated=:date_created WHERE id=:pid");
                            $result  = $insertSQL->execute(array(
                                ':unit_cost' => $dunitcost[$q], ':units_no' => $dtotalunits[$q], ":created_by" => $user_name, ":date_created" => $datecreated, ":pid" => $pid[$q]
                            ));
                        }
                    }
                }
            }
        }
		
		$myUser = $user_name;
		if (isset($_POST['attachmentpurpose'])) {
			$stage = 6;
			$countP = count($_POST["attachmentpurpose"]);
			// insert new data 
			for ($cnt = 0; $cnt < $countP; $cnt++) {
				if (!empty($_FILES['pfiles']['name'][$cnt])) {
					$purpose = $_POST["attachmentpurpose"][$cnt];
					var_dump($purpose);
					$filename = basename($_FILES['pfiles']['name'][$cnt]);
					$ext = substr($filename, strrpos($filename, '.') + 1);
					if (($ext != "exe") && ($_FILES["pfiles"]["type"][$cnt] != "application/x-msdownload")) {
						$newname = $projid . "_" . $stage . "_" . $filename;
						$filepath = "uploads/procurement/" . $newname;
						if (!file_exists($filepath)) {
							if (move_uploaded_file($_FILES['pfiles']['tmp_name'][$cnt], $filepath)) {
								$fname = $newname;
								$mt = $filepath;
								$filecategory = 0;

								$qry1 = $db->prepare("INSERT INTO tbl_files (projid, projstage, filename, ftype, floc, fcategory, reason, uploaded_by, date_uploaded)
								 VALUES (:projid, :stage, :filename, :ftype, :floc, :fcategory, :reason, :uploaded_by, :date_uploaded)");
								$qry1->execute(array(":projid" => $projid, ":stage" => $stage, ":filename" => $filename, ":ftype" => $ext, ":floc" => $mt, ":fcategory" => $filecategory, ":reason" => $purpose, ":uploaded_by" => $myUser, ":date_uploaded" => $datecreated));
							}
						}
					}
				}
			}
		}
		
		$projstage = 7;

        $insertSQL = $db->prepare("UPDATE tbl_projects SET  projstage=:projstage WHERE  projid=:projid");
        $results  = $insertSQL->execute(array(":projstage" => $projstage, ":projid" => $projid));
        if ($results) {
            echo json_encode("Successfully Updated Procurement Plan");
        }
    }

    if (isset($_POST['getprocurementdetails'])) {
        $projid = $_POST['projid'];

        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid=:projid");
        $query_rsProjects->execute(array(":projid" => $projid));
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();
        $projname = $row_rsProjects['projname'];
        $projcode = $row_rsProjects['projcode'];
        $projcost = $row_rsProjects['projcost'];
        $progid = $row_rsProjects['progid'];
        $projstartdate = $row_rsProjects['projstartdate'];
        $projenddate = $row_rsProjects['projenddate'];

        $query_rsOutputs = $db->prepare("SELECT p.output as  output, o.id as opid, p.indicator, o.budget as budget FROM tbl_project_details o INNER JOIN tbl_progdetails p ON p.id = o.outputid WHERE projid=:projid");
        $query_rsOutputs->execute(array(":projid" => $projid));
        $row_rsOutputs = $query_rsOutputs->fetch();
        $totalRows_rsOutputs = $query_rsOutputs->rowCount();

        $query_rsProjFinancier =  $db->prepare("SELECT * FROM tbl_myprojfunding WHERE projid =:projid ORDER BY amountfunding desc");
        $query_rsProjFinancier->execute(array(":projid" => $projid));
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
							<div class="col-md-3 clearfix" style="margin-top:5px; margin-bottom:5px">
								<label class="control-label">Project Code:</label>
								<div class="form-line">
									<input type="text" class="form-control" value="' . $projcode . ' " readonly>
								</div>
							</div>
							<div class="col-md-12 clearfix" style="margin-top:5px; margin-bottom:5px">
								<label class="control-label">Project Name:</label>
								<div class="form-line">
									<input type="text" class="form-control" value="' . $projname . ' " readonly>
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
											$progfundid =  $row_rsProjFinancier['progfundid'];
											$query_rsProcurement = $db->prepare("SELECT SUM(amount) as funds FROM tbl_project_cost_funders_share WHERE projid = :projid AND funder=:funder AND type=:type");
											$query_rsProcurement->execute(array(":projid" => $projid, ":funder" => $progfundid, ":type" => 1));
											$row_plan = $query_rsProcurement->fetch();
											$totalRows_Procurement = $query_rsProcurement->rowCount();
											$contribution_amount = $row_plan['funds'];
											$totalAmount = $contribution_amount + $totalAmount;

											$query_rsFunding =  $db->prepare("SELECT * FROM tbl_myprogfunding WHERE progid = :progid");
											$query_rsFunding->execute(array(":progid" => $progid));
											$row_rsFunding = $query_rsFunding->fetch();
											$totalRows_rsFunding = $query_rsFunding->rowCount();

											$inputs = '';
											do {
												$source = $row_rsFunding['sourceid'];
												$progfundids = $row_rsFunding['id'];

												if ($row_rsFunding['sourcecategory']  == "donor") {
													$query_rsDonor = $db->prepare("SELECT * FROM tbl_donors WHERE dnid=:source");
													$query_rsDonor->execute(array(":source" => $source));
													$row_rsDonor = $query_rsDonor->fetch();
													$totalRows_rsDonor = $query_rsDonor->rowCount();
													$donor = $row_rsDonor['donorname'];

													if ($row_rsFunding['id'] == $progfundid) {
														$inputs .= '<span>' . $donor . '</span>';
													}
												} else if ($row_rsFunding['sourcecategory']  == "others") {
													$query_rsFunder = $db->prepare("SELECT * FROM tbl_funder WHERE id=:source");
													$query_rsFunder->execute(array(":source" => $source));
													$row_rsFunder = $query_rsFunder->fetch();
													$totalRows_rsFunder = $query_rsFunder->rowCount();
													$funder = $row_rsFunder['name'];

													if ($row_rsFunding['id'] == $progfundid) {
														$inputs .= '<span>' . $funder . '</span>';
													}
												}
											} while ($row_rsFunding = $query_rsFunding->fetch());
											if ($contribution_amount > 0) {
												$projectPlan .= '        
												<tr id="row<?= $rowno ?>">
													<td>
														<?= $rowno ?>
													</td>
													<td>
														' . $inputs . '
													</td>
													<td align="right">
														' . number_format($contribution_amount, 2) . '
													</td>
												</tr>';
											}
										} while ($row_rsProjFinancier = $query_rsProjFinancier->fetch());
									}
									$projectPlan .= '  <tfoot>
										<tr>
											<td colspan="2"><strong>Total Amount</strong></td>
											<td align="right"><strong>' . number_format($totalAmount, 2) . '</strong></td>
										</tr>
									</tfoot>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>';
		
        $query_rsTender = $db->prepare("SELECT SUM(amount) as funds FROM tbl_project_cost_funders_share WHERE projid = :projid AND type=:type");
        $query_rsTender->execute(array(":projid" => $projid, ":type" => 1));
        $row_plan = $query_rsTender->fetch();
        $totalRows_Tender = $query_rsTender->rowCount();
        $contribution_val = $row_plan['funds'];

        $Ocounter = 0;
        $summary = '';
        $output_cost_val = [];
        $total_amount = 0;
        do {
            $Ocounter++;
            //get indicator
            $outputName = $row_rsOutputs['output'];
            $outputCost = $row_rsOutputs['budget'];
            $outputid = $row_rsOutputs['opid'];
            $output_cost_val[] = $outputid;
            $output_remeinder = 0;
            $poutput_remeinder = 0;

            $query_rsTender = $db->prepare("SELECT SUM(amount) as funds FROM tbl_project_cost_funders_share WHERE projid = :projid AND outputid=:opid AND type=:type");
            $query_rsTender->execute(array(":projid" => $projid, ":opid" => $outputid, ":type" => 1));
            $row_plan = $query_rsTender->fetch();
            $totalRows_Tender = $query_rsTender->rowCount();
            $contribution_amount = $row_plan['funds'];


            $projectPlan .= '
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card"> 
						<div class="panel panel-info">
							<div class="panel-heading list-group-item list-group-item list-group-item-action active collapse careted" data-toggle="collapse" data-target=".output' . $outputid . '">
								<i class="fa fa-list-ul" aria-hidden="true"></i> <i class="fa fa-caret-down" aria-hidden="true"></i>
								<strong> Output '.$Ocounter.': 
									<span class="">
										'.$outputName.'
									</span>
								</strong>
							</div>
							<div class="body collapse output'.$outputid.'">
							<h4>' . $Ocounter . '.1) Direct Project Cost</h4>';
							$query_rsMilestones = $db->prepare("SELECT *  FROM tbl_milestone WHERE projid=:projid and outputid =:outputid ORDER BY sdate ");
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
												<th style="width:2%"># </th>
												<th style="width:35%">Description </th> 
												<th style="width:10%">Unit</th>
												<th style="width:13%">Unit Cost (Ksh)</th>
												<th style="width:20%">No. of Units</th>
												<th style="width:20%">Total Cost (Ksh)</th> 
											</tr>
										</thead>
										<tbody>';
											do {
												$mcounter++;
												$milestone = $row_rsMilestones['msid'];
												$milestoneName = $row_rsMilestones['milestone'];
												$medate = date_create($row_rsMilestones['edate']);
												$msdate = date_create($row_rsMilestones['sdate']);
												
												$query_rsTasks = $db->prepare("SELECT *  FROM tbl_task WHERE projid=:projid and msid=:milestone ORDER BY sdate");
												$query_rsTasks->execute(array(":projid" => $projid, ":milestone" => $milestone));
												$row_rsTasks = $query_rsTasks->fetch();
												$totalRows_rsTasks = $query_rsTasks->rowCount();
												if ($totalRows_rsTasks > 0) {
													$projectPlan .= '  
													<tr class="bg-blue-grey">
														<td>' . $Ocounter . "." . 1 . "." . $mcounter   . '</td>
														<td colspan="3"><strong>Milestone: ' . $milestoneName . '</strong> </td>
														<td colspan="1"><strong>Start Date:' . date_format($msdate, "d M Y") . '</strong> </td>
														<td colspan="1"><strong>End Date:' . date_format($medate, "d M Y")  . '</strong> </td>
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
														$datetime1 = new DateTime($row_rsTasks['sdate']);
														$datetime2 = new DateTime($row_rsTasks['edate']);
														$difference = $datetime1->diff($datetime2);
														$duration = $difference->d + 1;
														
														$projectPlan .= '  
														<tr class="bg-grey">
															<td>' . $Ocounter . "." . 1 . "." . $mcounter . "." . $tcounter  . '</td>
															<td colspan="3"><strong>Task: ' . $task . '</strong> </td>
															<td colspan="1">
																<span><strong>Start Date:</strong></span><strong style="color:RED"> ' . date_format($sdate, "d M Y") . '</strong>
															</td>
															<td colspan="1">
																<span><strong>End Date :</strong></span><strong style="color:RED">' . date_format($edate, "d M Y") . '</strong><br><span><strong>Duration :</strong></span><strong style="color:RED"> ' . $duration  . ' Days</strong> 
															</td>
														</tr>';

														$query_rsDirect_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND cost_type=:cost_type AND tasks=:tkid ");
														$query_rsDirect_cost_plan->execute(array(":projid" => $projid, ":cost_type" => $cost_type, ":tkid" => $tkid));
														$row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch();
														$totalRows_rsDirect_cost_plan = $query_rsDirect_cost_plan->rowCount();
										
														if ($totalRows_rsDirect_cost_plan > 0) {
															$plan_counter = 0;
															do {
																$plan_counter++;
																$ounit_cost = $row_rsDirect_cost_plan['unit_cost'];
																$ounits_no = $row_rsDirect_cost_plan['units_no'];
																$costlineid  = $row_rsDirect_cost_plan['id'];
																$ototal_cost = $ounit_cost * $ounits_no;
																$output_remeinder = $output_remeinder + $ototal_cost;

																$query_rsTender =  $db->prepare("SELECT * FROM tbl_project_tender_details WHERE projid =:projid  AND tasks=:tkid  AND costlineid=:costlineid");
																$query_rsTender->execute(array(":projid" => $projid, ":tkid" => $tkid, ':costlineid' => $costlineid));
																$row_rsTender = $query_rsTender->fetch();
																$totalRows_rsTender = $query_rsTender->rowCount();

																$unit = $row_rsTender['unit'];
																$unit_cost = $row_rsTender['unit_cost'];
																$units_no = $row_rsTender['units_no'];
																$description = $row_rsTender['description'];
																$rmkid = $row_rsTender['id'];
																$total_cost = $unit_cost * $units_no;
																$sum = $sum + $total_cost;
																$poutput_remeinder = $poutput_remeinder + $total_cost;

																$total_amount = $total_amount + $total_cost;
																$projectPlan .= '  
																<tr> 
																	<td>
																		' .  $Ocounter . "." . 1 . "." . $mcounter . "." . $tcounter . "." . $plan_counter . '
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
																		' .  number_format($units_no, 0) . '
																	</td>
																	<td>
																		' .  number_format($total_cost, 2) .
																	'</td> 
																</tr>';
															} while ($row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch());

															$sub_per = number_format((($poutput_remeinder / $output_remeinder) * 100), 2);
															$balance = number_format(($output_remeinder - $poutput_remeinder), 2);
														}
													} while ($row_rsTasks = $query_rsTasks->fetch());
												}
											} while ($row_rsMilestones = $query_rsMilestones->fetch());
								
											$projectPlan .= '   
											<tfoot class="bg-brown">
												<tr>
													<td colspan="3">
													</td> 
													<td colspan="2"><strong>Sub Total (Ksh.)</strong></td>
													<td colspan="1" align="left">
														<strong>' . number_format($poutput_remeinder, 2) . '</strong>
													</td>
												</tr>
												<tr>
													<td colspan="3">
													</td> 
													<td colspan="2" align="left"> <strong>% Sub Total</strong></td>
													<td colspan="1">
														<strong>' . $sub_per . ' %</strong>
													</td> 
												</tr>
												<tr>
													<td colspan="3">
													</td> 
													<td colspan="2" align="left"> <strong>Planned amount Balance (Ksh)</strong></td>
													<td colspan="1">
														<strong> ' . $balance . '</strong>
													</td>
												</tr>
											</tfoot>
										</tbody>
									</table>
								</div>';
							}
							$projectPlan .= '  
						</div>
					</div>
				</div>
			</div>                      
            <script>
            $(".careted").click(function(e) {
                e.preventDefault(); 
                $(this)
                .find("i")
                .toggleClass("fa fa-caret-down fa fa-caret-up");
            });
            </script>';

            $summary  .= '<tr>
				<td>' . $Ocounter . '</td>
				<td>' . $outputName . '</td>
				<td style="text-align:left">' . number_format($contribution_amount, 2) . '</td>
				<td id="summaryOutput' . $outputid . '"  style="text-align:left">' . number_format($poutput_remeinder, 2) . '</td>
				<td id="perc' . $outputid .  '"  style="text-align:left">' . number_format((($poutput_remeinder / $output_remeinder) * 100), 2) . ' %</td>
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
											<td colspan="2" style="text-align:left">
												<strong>
													Total Amount
												</strong>
											</td>
											<td style="text-align:left">
												<strong>
													' . number_format($contribution_val, 2) . '
												</strong>
											</td>
											<td style="text-align:left">
												<strong id="summary_total">
													' . number_format($total_amount, 2) . '
												</strong>
											</td>
											<td style="text-align:left">
												<strong id="summary_percentage">
													' . number_format((($total_amount / $contribution_val) * 100), 2) . '%
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
		</div>';
        echo $projectPlan;
    }

    if (isset($_POST['deleteItem'])) {
        $projid = $_POST['projid'];

        $valid['success'] = true;
        $valid['messages'] = "Procurement Plan Successfully Deleted";

        $deleteQuery = $db->prepare("DELETE FROM tbl_tenderdetails WHERE projid=:projid");
        $results = $deleteQuery->execute(array(':projid' => $projid));
        if ($results) {
			$deleteQuery = $db->prepare("DELETE FROM tbl_project_tender_details WHERE  projid=:projid");
			$results = $deleteQuery->execute(array(':projid' => $projid));

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
} catch (PDOException $ex) {
    // $result = flashMessage("An error occurred: " .$ex->getMessage());
    print($ex->getMessage());
}
