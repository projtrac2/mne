<?php
include_once "controller.php";

try {
	$valid['success'] = array('success' => false, 'messages' => array());

	//approve item 
	if (isset($_POST["approveitem"])) {
		$projid = $_POST['projid'];
		$progid = $_POST['progid'];
		$approved = $_POST['approveitem'];
		$projcost = $_POST['projcost']; // cost of each output 
		$projoutputduration = $_POST['opduration']; //output duration 
		$user_name = $_POST['user_name'];
		$date = date("Y-m-d");

		$queryODetails = $db->prepare("SELECT * FROM tbl_project_details WHERE projid = '$projid'");
		$queryODetails->execute();
		$data_ODetails =  $queryODetails->fetch();
		$mapping_type = 0;
		if (!empty($data_ODetails['mapping_type'])) {
			$mapping_type = $data_ODetails['mapping_type'];
		}

		do {
			$insertOutputHistory = $db->prepare("INSERT INTO `tbl_project_details_history`(pdid, progid, projid, outputid, indicator, year, duration, budget, mapping_type, total_target)  VALUES(:pdid, :progid, :projid, :outputid,:indicator, :year, :duration, :budget, :mapping_type, :total_target)");
			$resultOutputresults  = $insertOutputHistory->execute(array(":pdid" => $data_ODetails['id'], ":progid" => $progid, ":projid" => $projid, ":outputid" => $data_ODetails['outputid'], ":indicator" => $data_ODetails['indicator'], ":year" => $data_ODetails['year'], ":duration" => $data_ODetails['duration'], ":budget" => $data_ODetails['budget'], ":mapping_type" => $mapping_type, ":total_target" => $data_ODetails['total_target']));
		} while ($data_ODetails =  $queryODetails->fetch());


		if ($resultOutputresults === TRUE) {
			$budget = $_POST['projapprovedbudget'];
			$budgetyear = $_POST['budgetyear'];

			$queryuserdetails = $db->prepare("SELECT * FROM users WHERE username = '$user_name'");
			$queryuserdetails->execute();
			$row_user_details =  $queryuserdetails->fetch();
			$userid = $row_user_details["userid"];

			$insertbudget = $db->prepare("INSERT INTO `tbl_project_approved_yearly_budget`(projid, year, amount, created_by, date_created)  VALUES(:projid, :year, :amount, :createdby, :datecreated)");
			$results  = $insertbudget->execute(array(":projid" => $projid, ":year" => $budgetyear, ":amount" => $budget, ":createdby" => $userid, ":datecreated" => $date));

			for ($i = 0; $i < count($_POST['projoutput']); $i++) {
				$outputid = $_POST['projoutput'][$i];
				$projoutputid = $_POST['projoutputid'][$i];
				$indicator = $_POST['indicator'][$i];
				$year = $_POST['opstaryear'][$i];
				$duration = $_POST['opduration'][$i];
				$budget = $_POST['projcost'][$i];
				$insertSQL1 = $db->prepare("UPDATE tbl_project_details  SET duration=:duration, budget=:budget WHERE id=:opid");
				$result1  = $insertSQL1->execute(array(":duration" => $duration, ":budget" => $budget, ":opid" => $projoutputid));
			}

			//  select data to be moved to the history table 
			$queryDetails = $db->prepare("SELECT * FROM tbl_project_output_details WHERE projid = '$projid'");
			$queryDetails->execute();
			$data_Details = $queryDetails->fetch();

			do {
				$insertTargetHistory = $db->prepare("INSERT INTO `tbl_project_output_details_history`(projoutputid, progid, projid, indicator, year, target) VALUES( :projoutputid, :progid, :projid, :indicator, :qyear, :target)");
				$insertTargetHistory->execute(array(":projoutputid" => $data_Details['projoutputid'], ":progid" => $data_Details['progid'], ":projid" => $data_Details['projid'], ":indicator" => $data_Details['indicator'], ":qyear" => $data_Details['year'], ":target" => $data_Details['target']));
			} while ($data_Details =  $queryDetails->fetch());


			//  select data to be moved to the history table 
			$query_Diss_Details = $db->prepare("SELECT * FROM tbl_project_results_level_disaggregation WHERE projid = '$projid'");
			$query_Diss_Details->execute();
			$data_Diss_Details = $query_Diss_Details->fetch();
			$row_rsCount = $query_Diss_Details->rowCount();
			if ($row_rsCount > 0) {
				do {
					$type = 3;
					$insertSQL2 = $db->prepare("INSERT INTO `tbl_project_history_results_level_disaggregation` (projid, projoutputid,opstate, name, value, type)  VALUES(:projid, :outputid,:opstate, :outputlocation, :outputlocationtarget, :type)");
					$result2  = $insertSQL2->execute(array(":projid" => $data_Diss_Details['projid'], ":outputid" => $data_Diss_Details['projoutputid'], ":opstate" => $data_Diss_Details['opstate'], ":outputlocation" => $data_Diss_Details['name'], ":outputlocationtarget" => $data_Diss_Details['value'], ":type" => $type));
				} while ($data_Diss_Details =  $query_Diss_Details->fetch());
			}

			// delete tbl_project_output_details table 
			$deleteQuery = $db->prepare("DELETE FROM `tbl_project_output_details` WHERE projid=:projid");
			$results = $deleteQuery->execute(array(':projid' => $projid));

			// delete tbl_project_output_details table 
			$deleteQuery = $db->prepare("DELETE FROM `tbl_project_results_level_disaggregation` WHERE projid=:projid");
			$results = $deleteQuery->execute(array(':projid' => $projid));

			for ($i = 0; $i < count($_POST['projoutputid']); $i++) {
				$outputid = $_POST['projoutputid'][$i];
				$indicatorid = $_POST['indicator'][$i];
				$opyear = "output_years" . $outputid;
				$topyear = "target_year"  . $outputid;

				for ($j = 0; $j < count($_POST[$opyear]); $j++) {
					$target = $_POST[$topyear][$j];
					$qyear = $_POST[$opyear][$j];
					$insertSQL2 = $db->prepare("INSERT INTO `tbl_project_output_details`(projoutputid, progid, projid, indicator, year, target) VALUES(:outputid, :progid, :projid,:indicatorid, :qyear, :target)");
					$result2  = $insertSQL2->execute(array(":outputid" => $outputid, ":progid" => $progid, ":projid" => $projid, ":indicatorid" => $indicatorid, ":qyear" => $qyear, ":target" => $target));
				}

				if (isset($_POST['ben_diss']) && !empty($_POST['ben_diss'])) {
					$ben_diss = $_POST['ben_diss'][$i];

					if ($ben_diss == 1) {
						$outputstate = "outputstate" . $outputid;
						for ($j = 0; $j < count($_POST[$outputstate]); $j++) {
							$outputstate_val = $_POST[$outputstate][$j];
							$outputlocation = "outputlocation" . $outputstate_val . $outputid;
							$outputlocationtarget = "outputlocationtarget"  . $outputstate_val . $outputid;

							for ($p = 0; $p < count($_POST[$outputlocation]); $p++) {
								$outputlocationtarget_val = $_POST[$outputlocationtarget][$p];
								$outputlocation_val = $_POST[$outputlocation][$p];
								$type = 3;
								$insertSQL2 = $db->prepare("INSERT INTO `tbl_project_results_level_disaggregation`(projid, projoutputid,opstate, name, value, type) VALUES(:projid, :outputid,:opstate, :outputlocation, :outputlocationtarget, :type)");
								$result2  = $insertSQL2->execute(array(":projid" => $projid, ":outputid" => $outputid, ":opstate" => $outputstate_val, ":outputlocation" => $outputlocation_val, ":outputlocationtarget" => $outputlocationtarget_val, ":type" => $type));
							}
						}
					}
				}
			}

			if (isset($_POST['amountfunding'])) {
				for ($i = 0; $i < count($_POST['amountfunding']); $i++) {
					$sourcecategory = $_POST['source_category'][$i];
					$source = $_POST['source'][$i];
					$amountfunding = $_POST['amountfunding'][$i];

					$insertSQL1 = $db->prepare("INSERT INTO `tbl_myprojfunding`(progid, projid, sourcecategory, financier,  amountfunding, created_by, date_created) VALUES(:progid, :projid, :sourcecategory,:financier, :amountfunding, :created_by, :date_created)");
					$result1  = $insertSQL1->execute(array(":progid" => $progid, ":projid" => $projid, ":sourcecategory" => $sourcecategory, ":financier" => $source, ":amountfunding" => $amountfunding, ":created_by" => $user_name, ":date_created" => $date));
				}
			}

			if (isset($_POST['attachmentpurpose'])) {
				$countP = count($_POST["attachmentpurpose"]);
				$stage = 2;
				// insert new data 
				for ($cnt = 0; $cnt < $countP; $cnt++) {
					$purpose = $_POST["attachmentpurpose"][$cnt];
					if (!empty($_FILES['pfiles']['name'][$cnt])) {
						$filename = basename($_FILES['pfiles']['name'][$cnt]);
						$ext = substr($filename, strrpos($filename, '.') + 1);
						if (($ext != "exe") && ($_FILES["pfiles"]["type"][$cnt] != "application/x-msdownload")) {
							$newname = $projid . "_" . $stage . "_" . $filename;
							$filepath = "../../uploads/project-approval/" . $newname;
							if (!file_exists($filepath)) {
								if (move_uploaded_file($_FILES['pfiles']['tmp_name'][$cnt], $filepath)) {
									$fname = $newname;
									$mt = "uploads/project-approval/" . $newname;
									$filecategory = "Project Approval";
									$qry1 = $db->prepare("INSERT INTO tbl_files (`projid`, `projstage`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, date_uploaded) VALUES (:projid, :stage, :fname, :ext, :mt, :filecat, :reason, :user, :date)");
									$qry1->execute(array(':projid' => $projid, ":stage" => $stage, ':fname' => $fname, ':ext' => $ext, ':mt' => $mt, ':filecat' => $filecategory, ':reason' => $purpose, ':user' => $user_name, ':date' => $date));
								}
							} else {
								$type = 'error';
								$msg = 'File you are uploading already exists, try another file!!';
								$results = "<script type=\"text/javascript\">
                                    swal({
                                    title: \"Error!\",
                                    text: \" $msg \",
                                    type: 'Danger',
                                    timer: 10000,
                                    showConfirmButton: false });
                                </script>";
							}
						} else {
							$type = 'error';
							$msg = 'This file type is not allowed, try another file!!';
							$results = "<script type=\"text/javascript\">
                                swal({
                                title: \"Error!\",
                                text: \" $msg \",
                                type: 'Danger',
                                timer: 10000,
                                showConfirmButton: false });
                            </script>";
						}
					}
				}
			}

			$stage = 2;
			$status = 1;
			$sumCost = array_sum($_POST['projcost']);

			$approveItemQuery = $db->prepare("UPDATE `tbl_projects` SET projcost=:projcost, projplanstatus=:approved, projstage=:stage, approved_date=:approved_date, approved_by=:approved_by WHERE projid=:projid");
			$approveItemQuery->execute(array(':projcost' => $sumCost, ":approved" => $approved, ":stage" => $stage, ":approved_date" => $date, ":approved_by" => $user_name, ':projid' => $projid));

			$approveQuery = $db->prepare("UPDATE `tbl_annual_dev_plan` SET status=:status, approved_by=:approved_by, date_approved=:approved_date WHERE projid=:projid");
			$approveQuery->execute(array(':status' => $status, ":approved_by" => $user_name, ":approved_date" => $date, ':projid' => $projid));

			$valid['success'] = true;
			$valid['messages'] = "Successfully Approved";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while approving!!";
		}
		echo json_encode($valid);
	}

	//approve item
	if (isset($_POST["approveitem"])) {
		$projid = $_POST['projid'];
		$progid = $_POST['progid'];
		$approved = $_POST['approveitem'];
		$projcost = $_POST['projcost']; // cost of each output 
		$projoutputduration = $_POST['opduration']; //output duration 
		$user_name = $_POST['user_name'];
		$date = date("Y-m-d");

		$queryODetails = $db->prepare("SELECT * FROM tbl_project_details WHERE projid = '$projid'");
		$queryODetails->execute();
		$data_ODetails =  $queryODetails->fetch();
		$mapping_type = 0;
		if (!empty($data_ODetails['mapping_type'])) {
			$mapping_type = $data_ODetails['mapping_type'];
		}

		do {
			$insertOutputHistory = $db->prepare("INSERT INTO `tbl_project_details_history`(pdid, progid, projid, outputid, indicator, year, duration, budget, mapping_type, total_target)  VALUES(:pdid, :progid, :projid, :outputid,:indicator, :year, :duration, :budget, :mapping_type, :total_target)");
			$resultOutputresults  = $insertOutputHistory->execute(array(":pdid" => $data_ODetails['id'], ":progid" => $progid, ":projid" => $projid, ":outputid" => $data_ODetails['outputid'], ":indicator" => $data_ODetails['indicator'], ":year" => $data_ODetails['year'], ":duration" => $data_ODetails['duration'], ":budget" => $data_ODetails['budget'], ":mapping_type" => $mapping_type, ":total_target" => $data_ODetails['total_target']));
		} while ($data_ODetails =  $queryODetails->fetch());


		if ($resultOutputresults === TRUE) {
			for ($i = 0; $i < count($_POST['projoutput']); $i++) {
				$outputid = $_POST['projoutput'][$i];
				$projoutputid = $_POST['projoutputid'][$i];
				$indicator = $_POST['indicator'][$i];
				$year = $_POST['opstaryear'][$i];
				$duration = $_POST['opduration'][$i];
				$budget = $_POST['projcost'][$i];
				$insertSQL1 = $db->prepare("UPDATE tbl_project_details  SET duration=:duration, budget=:budget WHERE id=:opid");
				$result1  = $insertSQL1->execute(array(":duration" => $duration, ":budget" => $budget, ":opid" => $projoutputid));
			}

			//  select data to be moved to the history table 
			$queryDetails = $db->prepare("SELECT * FROM tbl_project_output_details WHERE projid = '$projid'");
			$queryDetails->execute();
			$data_Details = $queryDetails->fetch();

			do {
				$insertTargetHistory = $db->prepare("INSERT INTO `tbl_project_output_details_history`(projoutputid, progid, projid, indicator, year, target) VALUES( :projoutputid, :progid, :projid, :indicator, :qyear, :target)");
				$insertTargetHistory->execute(array(":projoutputid" => $data_Details['projoutputid'], ":progid" => $data_Details['progid'], ":projid" => $data_Details['projid'], ":indicator" => $data_Details['indicator'], ":qyear" => $data_Details['year'], ":target" => $data_Details['target']));
			} while ($data_Details =  $queryDetails->fetch());


			//  select data to be moved to the history table 
			$query_Diss_Details = $db->prepare("SELECT * FROM tbl_project_results_level_disaggregation WHERE projid = '$projid'");
			$query_Diss_Details->execute();
			$data_Diss_Details = $query_Diss_Details->fetch();
			$row_rsCount = $query_Diss_Details->rowCount();
			if ($row_rsCount > 0) {
				do {
					$type = 3;
					$insertSQL2 = $db->prepare("INSERT INTO `tbl_project_history_results_level_disaggregation` (projid, projoutputid,opstate, name, value, type)  VALUES(:projid, :outputid,:opstate, :outputlocation, :outputlocationtarget, :type)");
					$result2  = $insertSQL2->execute(array(":projid" => $data_Diss_Details['projid'], ":outputid" => $data_Diss_Details['projoutputid'], ":opstate" => $data_Diss_Details['opstate'], ":outputlocation" => $data_Diss_Details['name'], ":outputlocationtarget" => $data_Diss_Details['value'], ":type" => $type));
				} while ($data_Diss_Details =  $query_Diss_Details->fetch());
			}

			// delete tbl_project_output_details table 
			$deleteQuery = $db->prepare("DELETE FROM `tbl_project_output_details` WHERE projid=:projid");
			$results = $deleteQuery->execute(array(':projid' => $projid));

			// delete tbl_project_output_details table 
			$deleteQuery = $db->prepare("DELETE FROM `tbl_project_results_level_disaggregation` WHERE projid=:projid");
			$results = $deleteQuery->execute(array(':projid' => $projid));

			for ($i = 0; $i < count($_POST['projoutputid']); $i++) {
				$outputid = $_POST['projoutputid'][$i];
				$indicatorid = $_POST['indicator'][$i];
				$opyear = "output_years" . $outputid;
				$topyear = "target_year"  . $outputid;

				for ($j = 0; $j < count($_POST[$opyear]); $j++) {
					$target = $_POST[$topyear][$j];
					$qyear = $_POST[$opyear][$j];
					$insertSQL2 = $db->prepare("INSERT INTO `tbl_project_output_details`(projoutputid, progid, projid, indicator, year, target) VALUES(:outputid, :progid, :projid,:indicatorid, :qyear, :target)");
					$result2  = $insertSQL2->execute(array(":outputid" => $outputid, ":progid" => $progid, ":projid" => $projid, ":indicatorid" => $indicatorid, ":qyear" => $qyear, ":target" => $target));
				}

				if (isset($_POST['ben_diss']) && !empty($_POST['ben_diss'])) {
					$ben_diss = $_POST['ben_diss'][$i];

					if ($ben_diss == 1) {
						$outputstate = "outputstate" . $outputid;
						for ($j = 0; $j < count($_POST[$outputstate]); $j++) {
							$outputstate_val = $_POST[$outputstate][$j];
							$outputlocation = "outputlocation" . $outputstate_val . $outputid;
							$outputlocationtarget = "outputlocationtarget"  . $outputstate_val . $outputid;

							for ($p = 0; $p < count($_POST[$outputlocation]); $p++) {
								$outputlocationtarget_val = $_POST[$outputlocationtarget][$p];
								$outputlocation_val = $_POST[$outputlocation][$p];
								$type = 3;
								$insertSQL2 = $db->prepare("INSERT INTO `tbl_project_results_level_disaggregation`(projid, projoutputid,opstate, name, value, type) VALUES(:projid, :outputid,:opstate, :outputlocation, :outputlocationtarget, :type)");
								$result2  = $insertSQL2->execute(array(":projid" => $projid, ":outputid" => $outputid, ":opstate" => $outputstate_val, ":outputlocation" => $outputlocation_val, ":outputlocationtarget" => $outputlocationtarget_val, ":type" => $type));
							}
						}
					}
				}
			}

			if (isset($_POST['amountfunding'])) {
				for ($i = 0; $i < count($_POST['amountfunding']); $i++) {
					$sourcecategory = $_POST['source_category'][$i];
					$source = $_POST['source'][$i];
					$amountfunding = $_POST['amountfunding'][$i];

					$insertSQL1 = $db->prepare("INSERT INTO `tbl_myprojfunding`(progid, projid, sourcecategory, financier,  amountfunding, created_by, date_created) VALUES(:progid, :projid, :sourcecategory,:financier, :amountfunding, :created_by, :date_created)");
					$result1  = $insertSQL1->execute(array(":progid" => $progid, ":projid" => $projid, ":sourcecategory" => $sourcecategory, ":financier" => $source, ":amountfunding" => $amountfunding, ":created_by" => $user_name, ":date_created" => $date));
				}
			}

			if (isset($_POST['attachmentpurpose'])) {
				$countP = count($_POST["attachmentpurpose"]);
				$stage = 2;
				// insert new data 
				for ($cnt = 0; $cnt < $countP; $cnt++) {
					$purpose = $_POST["attachmentpurpose"][$cnt];
					if (!empty($_FILES['pfiles']['name'][$cnt])) {
						$filename = basename($_FILES['pfiles']['name'][$cnt]);
						$ext = substr($filename, strrpos($filename, '.') + 1);
						if (($ext != "exe") && ($_FILES["pfiles"]["type"][$cnt] != "application/x-msdownload")) {
							$newname = $projid . "_" . $stage . "_" . $filename;
							$filepath = "../../uploads/project-approval/" . $newname;
							if (!file_exists($filepath)) {
								if (move_uploaded_file($_FILES['pfiles']['tmp_name'][$cnt], $filepath)) {
									$fname = $newname;
									$mt = "uploads/project-approval/" . $newname;
									$filecategory = "Project Approval";
									$qry1 = $db->prepare("INSERT INTO tbl_files (`projid`, `projstage`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, date_uploaded) VALUES (:projid, :stage, :fname, :ext, :mt, :filecat, :reason, :user, :date)");
									$qry1->execute(array(':projid' => $projid, ":stage" => $stage, ':fname' => $fname, ':ext' => $ext, ':mt' => $mt, ':filecat' => $filecategory, ':reason' => $purpose, ':user' => $user_name, ':date' => $date));
								}
							} else {
								$type = 'error';
								$msg = 'File you are uploading already exists, try another file!!';
								$results = "<script type=\"text/javascript\">
                                    swal({
                                    title: \"Error!\",
                                    text: \" $msg \",
                                    type: 'Danger',
                                    timer: 10000,
                                    showConfirmButton: false });
                                </script>";
							}
						} else {
							$type = 'error';
							$msg = 'This file type is not allowed, try another file!!';
							$results = "<script type=\"text/javascript\">
                                swal({
                                title: \"Error!\",
                                text: \" $msg \",
                                type: 'Danger',
                                timer: 10000,
                                showConfirmButton: false });
                            </script>";
						}
					}
				}
			}

			$stage = 2;
			$status = 1;
			$sumCost = array_sum($_POST['projcost']);

			$approveItemQuery = $db->prepare("UPDATE `tbl_projects` SET projcost=:projcost, projplanstatus=:approved, projstage=:stage, approved_date=:approved_date, approved_by=:approved_by WHERE projid=:projid");
			$approveItemQuery->execute(array(':projcost' => $sumCost, ":approved" => $approved, ":stage" => $stage, ":approved_date" => $date, ":approved_by" => $user_name, ':projid' => $projid));

			$approveQuery = $db->prepare("UPDATE `tbl_annual_dev_plan` SET status=:status, approved_by=:approved_by, date_approved=:approved_date WHERE projid=:projid");
			$approveQuery->execute(array(':status' => $status, ":approved_by" => $user_name, ":approved_date" => $date, ':projid' => $projid));

			$valid['success'] = true;
			$valid['messages'] = "Successfully Approved";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while approving!!";
		}
		echo json_encode($valid);
	}

	//Un approve single item 
	if (isset($_POST["unapproveitem"])) {
		$projid = $_POST['itemId'];
		$approved = 0;
		$status = 0;
		$stage = 1;
		$date = date("Y-m-d");
		$sumCost = 0;

		// // delete tbl_project_details table 
		$deleteQuery = $db->prepare("DELETE FROM `tbl_project_details` WHERE projid=:projid");
		$results = $deleteQuery->execute(array(':projid' => $projid));

		// // // restore the initial values 
		$queryODetails = $db->prepare("SELECT * FROM tbl_project_details_history  WHERE projid = '$projid'");
		$queryODetails->execute();
		$data_ODetails =  $queryODetails->fetch();


		do {
			$insertOutputHistory = $db->prepare("INSERT INTO `tbl_project_details`(progid, projid, outputid, indicator, year, duration, budget, mapping_type, total_target)  VALUES(:progid, :projid, :outputid,:indicator, :year, :duration, :budget,:mapping_type, :total_target)");
			$resultOutputresults  = $insertOutputHistory->execute(array(":progid" => $data_ODetails['progid'], ":projid" => $projid, ":outputid" => $data_ODetails['outputid'], ":indicator" => $data_ODetails['indicator'], ":year" => $data_ODetails['year'], ":duration" => $data_ODetails['duration'], ":budget" => $data_ODetails['budget'], ":mapping_type" => $data_ODetails['mapping_type'], ":total_target" => $data_ODetails['total_target']));
			$last_id = $db->lastInsertId(); // get the project id   
			$projoutputid = $data_ODetails['pdid'];
			$progid = $data_ODetails['progid'];



			// delete tbl_project_output_details table 
			$deleteQuery = $db->prepare("DELETE FROM `tbl_project_output_details` WHERE projoutputid=:projoutputid");
			$results = $deleteQuery->execute(array(':projoutputid' => $projoutputid));

			//  restore initial data 
			$queryDetails = $db->prepare("SELECT * FROM tbl_project_output_details_history WHERE projid = '$projid' AND projoutputid ='$projoutputid' ");
			$queryDetails->execute();
			$data_Details = $queryDetails->fetch();

			do {
				$insertTargetHistory = $db->prepare("INSERT INTO `tbl_project_output_details`(projoutputid, progid,projid, indicator, year, target) VALUES(:projoutputid, :progid, :projid, :indicator, :qyear, :target)");
				$insertTargetHistory->execute(array(":projoutputid" => $last_id, ":progid" => $progid, ":projid" => $data_Details['projid'], ":indicator" => $data_Details['indicator'], ":qyear" => $data_Details['year'], ":target" => $data_Details['target']));
			} while ($data_Details =  $queryDetails->fetch());


			// delete tbl_project_output_details table 
			$deleteQuery = $db->prepare("DELETE FROM `tbl_project_results_level_disaggregation` WHERE projid=:projid AND projoutputid=:projoutputid");
			$results = $deleteQuery->execute(array(':projid' => $projid, ':projoutputid' => $projoutputid));

			//  select data to be moved to the  table 
			$query_Diss_Details = $db->prepare("SELECT * FROM tbl_project_history_results_level_disaggregation  WHERE projid = :projid AND projoutputid=:projoutputid");
			$query_Diss_Details->execute(array(':projid' => $projid, ':projoutputid' => $projoutputid));
			$data_Diss_Details = $query_Diss_Details->fetch();
			$data_row = $query_Diss_Details->rowCount();

			if ($data_row > 0) {
				$queryDetails = $db->prepare("UPDATE `tbl_output_disaggregation` SET `outputid` = '$last_id' WHERE  outputid ='$projoutputid' ");
				$queryDetails->execute();
				do {
					$type = 3;
					$insertSQL2 = $db->prepare("INSERT INTO `tbl_project_results_level_disaggregation` (projid, projoutputid,opstate, name, value, type)  VALUES(:projid, :outputid,:opstate, :outputlocation, :outputlocationtarget, :type)");
					$result2  = $insertSQL2->execute(array(":projid" => $data_Diss_Details['projid'], ":outputid" => $last_id, ":opstate" => $data_Diss_Details['opstate'], ":outputlocation" => $data_Diss_Details['name'], ":outputlocationtarget" => $data_Diss_Details['value'], ":type" => $type));
				} while ($data_Diss_Details =  $query_Diss_Details->fetch());
			}
		} while ($data_ODetails =  $queryODetails->fetch());



		// delete the files in myprojfunding 
		$deleteQueryR = $db->prepare("DELETE FROM `tbl_myprojfunding` WHERE projid=:itemid");
		$resultsR = $deleteQueryR->execute(array(':itemid' => $projid));

		// unapprove and set cost to 0 
		$approveItemQuery = $db->prepare("UPDATE `tbl_projects` set projcost=:projcost,  projplanstatus=:approved, projstage=:stage WHERE projid=:projid");
		$results = $approveItemQuery->execute(array(':projcost' => $sumCost, ":approved" => $approved, ":stage" => $stage, ':projid' => $projid));

		$unapproveItemQuery = $db->prepare("UPDATE `tbl_annual_dev_plan` SET status=:status, unapproved_by=:approved_by, date_unapproved=:approved_date WHERE projid=:projid");
		$unapproveItemQuery->execute(array(':status' => $status, ":approved_by" => $user_name, ":approved_date" => $date, ':projid' => $projid));

		// delete the files in myprojfunding 
		$deleteQueryHistory = $db->prepare("DELETE FROM `tbl_project_details_history` WHERE projid=:itemid");
		$resulryHistory = $deleteQueryHistory->execute(array(':itemid' => $projid));

		// delete the files in myprojfunding 
		$deleteQueryHistory_plan = $db->prepare("DELETE FROM `tbl_project_output_details_history` WHERE projid=:itemid");
		$resultsHistory_plan = $deleteQueryHistory_plan->execute(array(':itemid' => $projid));

		// delete tbl_project_output_details table 
		$deleteQuery = $db->prepare("DELETE FROM `tbl_project_history_results_level_disaggregation` WHERE projid=:projid");
		$results = $deleteQuery->execute(array(':projid' => $projid));

		$query_rsFile = $db->prepare("SELECT * FROM tbl_files WHERE projstage=2 and  projid=:projid");
		$query_rsFile->execute(array(":projid" => $projid));
		$row_rsFile = $query_rsFile->fetch();
		$totalRows_rsFile = $query_rsFile->rowCount();
		if ($totalRows_rsFile > 0) {
			do {
				$stage = 2;
				$pdfname = $row_rsFile['filename'];
				$newname = $projid . "_" . $stage . "_" . $pdfname;
				$fid = $row_rsFile['fid'];
				$deleteQuery = $db->prepare("DELETE FROM `tbl_files` WHERE projid=:projid AND projstage=:stage AND fid =:fid");
				$results = $deleteQuery->execute(array(":projid" => $projid, ":stage" => $stage, ':fid' => $fid));
				unlink("uploads/" . $newname);
			} while ($row_rsFile = $query_rsFile->fetch());
		}

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

			$deleteQueryR = $db->prepare("DELETE FROM `tbl_project_results_level_disaggregation` WHERE projid=:itemid");
			$resultsR = $deleteQueryR->execute(array(':itemid' => $itemid));

			$deleteQueryR = $db->prepare("DELETE FROM `tbl_projfunding` WHERE projid=:itemid");
			$resultsR = $deleteQueryR->execute(array(':itemid' => $itemid));


			$deleteQueryI = $db->prepare("DELETE FROM `tbl_project_expected_impact_details` WHERE projid=:itemid");
			$resultsI = $deleteQueryI->execute(array(':itemid' => $itemid));

			$deleteQueryE = $db->prepare("DELETE FROM `tbl_project_expected_outcome_details` WHERE projid=:itemid");
			$resultsE = $deleteQueryE->execute(array(':itemid' => $itemid));

			$deleteQueryO = $db->prepare("DELETE FROM `tbl_project_details` WHERE projid=:itemid");
			$resultsO = $deleteQueryO->execute(array(':itemid' => $itemid));

			if ($resultsO === TRUE) {
				// $deleteQueryOD = $db->prepare("DELETE FROM `tbl_output_disaggregation` WHERE projid=:itemid");
				// $resultsOD = $deleteQueryOD->execute(array(':itemid' => $itemid));

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

	if (isset($_POST['get_category'])) {
		$projid = $_POST['projid'];
		$query_rsFunding =  $db->prepare("SELECT p.amountfunding, p.sourcecategory, f.type 
		 FROM tbl_projfunding p 
		 INNER JOIN tbl_funding_type f ON  p.sourcecategory= f.id WHERE projid =:projid");
		$query_rsFunding->execute(array(":projid" => $projid));
		$row_rsFunding = $query_rsFunding->fetch();
		$totalRows_rsFunding = $query_rsFunding->rowCount();
		$inputs = '<option value="">Select Category from list</option>';
		do {
			$source_category = $row_rsFunding['sourcecategory'];
			$source_name = $row_rsFunding['type'];
			$progfunds = $row_rsFunding['amountfunding'];
			$inputs .= '<option value="' . $source_category . '">' . $source_name . '</option>';
		} while ($row_rsFunding = $query_rsFunding->fetch());
		echo $inputs;
	}

	if (isset($_POST['get_source'])) {
		$inputs = '<option value="">Select Source list</option>';
		$source_category = $_POST['sourcecategory'];
		$projfscyear = $_POST['projfscyear'];
		$projid = $_POST['projid'];

		$query_rsCategory = $db->prepare("SELECT * FROM tbl_funding_type WHERE id=:source_category");
		$query_rsCategory->execute(array(":source_category" => $source_category));
		$row_rsCategory = $query_rsCategory->fetch();
		$totalRows_rsCategory = $query_rsCategory->rowCount();
		$category = $row_rsCategory['category'];


		$query_rsFunder = $db->prepare("SELECT *, s.id as fnid FROM tbl_financiers s  
		INNER JOIN tbl_funds f ON s.id =f.funder WHERE f.financial_year=:financial_year AND s.type=:sourcecategory");
		$query_rsFunder->execute(array(":financial_year" => $projfscyear, ":sourcecategory" => $source_category));
		$row_rsFunder = $query_rsFunder->fetch();
		$totalRows_rsFunder = $query_rsFunder->rowCount();
		$funderId = [];


		do {
			$fndid = $row_rsFunder['fnid'];
			$funder = $row_rsFunder['financier'];
			$rate = $row_rsFunder['exchange_rate'];
			$funding_amount = $row_rsFunder['amount'] * $rate; // check 

			// check amount that it has been used up 
			$query_rsUsedFunds = $db->prepare("SELECT sum(amountfunding) as amount FROM tbl_myprojfunding f INNER JOIN tbl_projects p ON  p.projid= f.projid WHERE p.projfscyear=:projfscyear AND financier=:fndid ");
			$query_rsUsedFunds->execute(array(":fndid" => $fndid, ":projfscyear" => $projfscyear));
			$row_rsUsedFunds = $query_rsUsedFunds->fetch();
			$totalRows_rsUsedFunds = $query_rsUsedFunds->rowCount();
			$used_amount = $row_rsUsedFunds['amount'];

			if ($used_amount == NULL) {
				$used_amount = 0;
			}

			$remaining = $funding_amount - $used_amount;

			if ($remaining > 0 && !in_array($fndid, $funderId)) {
				$inputs .= '<option value="' . $fndid . '">' . $funder . '</option>';
			}
			$funderId[] = $fndid;
		} while ($row_rsFunder = $query_rsFunder->fetch());

		$query_rsprojcost =  $db->prepare("SELECT sum(amountfunding) as projbudget FROM  tbl_projfunding WHERE projid =:projid AND sourcecategory=:sourcecategory");
		$query_rsprojcost->execute(array(":projid" => $projid, ":sourcecategory" => $source_category));
		$row_rsprojcost = $query_rsprojcost->fetch();
		$totalRows_rsprojcost = $query_rsprojcost->rowCount();
		$funding_amount = $row_rsprojcost['projbudget'];

		$query_rsUsed_category_Funds = $db->prepare("SELECT sum(amountfunding) as amount FROM tbl_myprojfunding f INNER JOIN tbl_projects p ON  p.projid= f.projid WHERE p.projfscyear=:projfscyear AND  sourcecategory=:sourcecategory AND p.projid=:projid ");
		$query_rsUsed_category_Funds->execute(array(":sourcecategory" => $source_category, ":projfscyear" => $projfscyear, ":projid" => $projid));
		$row_rsUsed_category_Funds = $query_rsUsed_category_Funds->fetch();
		$totalRows_rsUsed_category_Funds = $query_rsUsed_category_Funds->rowCount();
		$used_amount = $row_rsUsed_category_Funds['amount'];
		if (is_null($used_amount)) {
			$used_amount = 0;
		}
		$remaining = $funding_amount - $used_amount;
		$arr = array("source" => $inputs, "category_ceiling" => $remaining);
		echo json_encode($arr);
	}

	if (isset($_POST['get_source_ceiling'])) {
		$sourceid = $_POST['sourceid'];
		$sourcecategory = $_POST['sourcecategory'];
		$projfscyear = $_POST['projfscyear'];

		$query_rsprojFunding =  $db->prepare("SELECT SUM(amountfunding) as amountfunding FROM tbl_myprojfunding f INNER JOIN tbl_projects p ON  p.projid= f.projid WHERE f.financier=:financier AND p.projfscyear=:projfscyear AND  f.sourcecategory=:sourcecategory");
		$query_rsprojFunding->execute(array(":financier" => $sourceid, ":projfscyear" => $projfscyear, ":sourcecategory" => $sourcecategory));
		$row_rsprojFunding = $query_rsprojFunding->fetch();
		$totalRows_rsprojFunding = $query_rsprojFunding->rowCount();
		$amountprojFunding = $row_rsprojFunding['amountfunding'];
		//var_dump($sourceid);

		$remaining = 0;

		if (is_null($amountprojFunding)) {
			$amountprojFunding = 0;
		} else {
			$amountprojFunding = $row_rsprojFunding['amountfunding'];
		}

		$query_rsCategory = $db->prepare("SELECT * FROM tbl_funding_type WHERE id=:source_category");
		$query_rsCategory->execute(array(":source_category" => $sourcecategory));
		$row_rsCategory = $query_rsCategory->fetch();
		$totalRows_rsCategory = $query_rsCategory->rowCount();
		$category = $row_rsCategory['category'];

		$query_rsFunder = $db->prepare("SELECT * FROM tbl_financiers s  INNER JOIN tbl_funds f ON s.id =f.funder WHERE f.financial_year=:financial_year AND s.id=:funder");
		$query_rsFunder->execute(array(":financial_year" => $projfscyear, ":funder" => $sourceid));
		$row_rsFunder = $query_rsFunder->fetch();
		$totalRows_rsFunder = $query_rsFunder->rowCount();
		$fndid = $row_rsFunder['id'];
		$funder = $row_rsFunder['financier'];
		$rate = $row_rsFunder['exchange_rate'];
		$funding_amount = $row_rsFunder['amount'] * $rate; // check 
		$remaining  = $funding_amount - $amountprojFunding;

		$arr = [];
		if ($remaining > 0) {
			$arr =   array("remaining" => $remaining, "msg" => "true");
		} else {
			$arr =   array("msg" => "false");
		}
		echo json_encode($arr);
	}

	if (isset($_POST['get_location_diss'])) {
		$outputids = $_POST['opid'];

		$query_rsOutput =  $db->prepare("SELECT * FROM  tbl_project_details WHERE  id='$outputids' ");
		$query_rsOutput->execute();
		$row_rsOutput = $query_rsOutput->fetch();
		$totalRows_rsOutput = $query_rsOutput->rowCount();

		$indicator = $row_rsOutput['indicator'];
		$query_rsIndicator = $db->prepare("SELECT indicator_name, indid, indicator_unit FROM tbl_indicator WHERE indid ='$indicator'");
		$query_rsIndicator->execute();
		$row_rsIndicator = $query_rsIndicator->fetch();
		$indname = $row_rsIndicator['indicator_name'];
		$opunit = $row_rsIndicator['indicator_unit'];

		$query_Unit = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id ='$opunit' ");
		$query_Unit->execute();
		$row = $query_Unit->fetch();
		$unit = $row['unit'];

		$output = $row_rsOutput['outputid'];
		$query_rsProgOutput = $db->prepare("SELECT * FROM tbl_progdetails WHERE id='$output'");
		$query_rsProgOutput->execute();
		$row_rsProgOutput = $query_rsProgOutput->fetch();
		$outputName = $row_rsProgOutput['output'];


		$query_rsStates =  $db->prepare("SELECT * FROM  tbl_output_disaggregation WHERE  outputid='$outputids' ");
		$query_rsStates->execute();
		$row_rsStates = $query_rsStates->fetch();
		$totalRows_rsStates = $query_rsStates->rowCount();

		$containerTH2 = '<tr>';
		$containerTB = '<tr>';
		$containerTH = '<tr>';

		$rowno = 0;
		do {
			$rowno++;

			$state =   $row_rsStates['outputstate'];
			$total_target =   $row_rsStates['total_target'];
			$locations =   explode(",", $row_rsStates['locations']);

			$query_ward = $db->prepare("SELECT id, state, parent FROM tbl_state WHERE id='$state'");
			$query_ward->execute();
			$row_ward = $query_ward->fetch();
			$level3 = $row_ward['state'];


			$containerTH .= '<th colspan="' . count($locations) . '">

				<input type="hidden"   name="locate_output_name[]" id="locate_opid' . $outputids . '" value="' . $outputName . '"/>  
				<input type="hidden"   name="level3label' . $state . $outputids . '[]" id="level3label' . $state . $outputids . '" value="' . $level3 . '"/>  
				<input type="hidden"   name="unitName' . $state . $outputids . '[]" id="unitName' . $state . $outputids . '" value="' . $unit . '"/>  
				<input type="hidden" data-id="' . $level3 . '"  name="outputstate' . $outputids . '[]" class="outputstate' . $outputids . '" value="' . $state . '" /> 
				' . $level3label . ': ' . $level3 . '
				<input type="number" class="form-control
				  state_diss' . $outputids . '" onkeyup="target_state_distribution(' . $outputids . "," . $state . ')"
				   onchange="target_state_distribution(' . $outputids . "," . $state . ')"  id="ceilinglocation_target' . $state . $outputids . '"  name="ceiloutputlocatontarget' . $outputids . '[]" value="" />
				(<span id="state_ceil' . $state . $outputids . '" style="color:red" ></span>' . $unit . ') </th>';
			$p = 0;

			for ($j = 0; $j < count($locations); $j++) {
				$p++;
				$gen_number =  mt_rand(15, 500);
				$number = $p . $gen_number;
				$containerTH2 .= '<th>' . $locations[$j] . '</th> ';
				$containerTB .= '
					<td>
						<input type="hidden"   name="outputlocation' . $state . $outputids . '[]" id="locate' . $number . '" value="' . trim($locations[$j]) . '"/>  
						<input type="number"  disabled data-loc="' . trim($locations[$j]) . '"  data-id="' . $outputids . '" id="locate_numb' . $number . '" placeholder="' . $unit . '" class="form-control locate_total' . $state .  $outputids . '  loc_op' . $outputids . '" onkeyup=get_sum("' . $state . '","' . $number . '") onchange=get_sum("' . $state . '","' . $number . '")  name="outputlocationtarget' . $state . $outputids . '[]" value="" required />  
					</td>';
			}
		} while ($row_rsStates = $query_rsStates->fetch());

		$containerTH .= '</tr>';
		$containerTH2 .= '</tr>';
		$containerTB .= '<tr>';

		$Targets  = '
					<div class="row clearfix" id="disstarget">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
							<div class="card">
								<div class="header">
									<div class="col-md-6" style="margin-top:5px; margin-bottom:5px">
										<h5 style="color:#2196F3"><strong> Output: ' . $outputName . '</strong></h5>
									</div>
									<div class="col-md-6" style="margin-top:5px; margin-bottom:5px">
										<h5 style="color:#2196F3"><strong> Indicator: ' . $indname . '</strong></h5>
										<input type="hidden" value="' . $indname . '" id="indicatorName' . trim($outputids) . '">
										<input type="hidden" value="' . $unit . '" id="unitNameL' . trim($outputids) . '">
									</div> 
								</div>
								<div class="body"> 
									<div class="row clearfix" >
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
											<div class="table-responsive">
												<table class="table table-bordered table-striped table-hover" id="" style="width:100%">
													<thead> 
															' . $containerTH . ' 
													</thead>
													<tbody>
														' . $containerTH2 . $containerTB . '
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';
		echo $Targets;
	}

	if (isset($_POST['get_target_div'])) {
		$programid = $_POST['prograid'];
		$outputIds = $_POST['outputid'];
		$opduration = $_POST['duration'];
		$target_plan = $_POST['target_plan'];
		$opbal = $_POST['opbal'];
		$query_OutputData = $db->prepare("SELECT * FROM tbl_project_details WHERE id = '$outputIds' ");
		$query_OutputData->execute();
		$rows_OutpuData = $query_OutputData->rowCount();
		$row_OutputData =  $query_OutputData->fetch();

		if ($rows_OutpuData > 0) {
			$op_target = $row_OutputData['total_target'];
			$indicator = $row_OutputData['indicator'];
			$query_rsIndicator = $db->prepare("SELECT indicator_name, indid FROM tbl_indicator WHERE indid ='$indicator'");
			$query_rsIndicator->execute();
			$row_rsIndicator = $query_rsIndicator->fetch();
			$indname = $row_rsIndicator['indicator_name'];

			$query_Indicator = $db->prepare("SELECT tbl_measurement_units.unit FROM tbl_indicator  INNER JOIN tbl_measurement_units ON tbl_measurement_units.id =tbl_indicator.indicator_unit WHERE tbl_indicator.indid ='$indicator' AND baseline=1 AND indicator_category='Output' ");
			$query_Indicator->execute();
			$row = $query_Indicator->fetch();
			$unit = $row['unit'];

			$year = $row_OutputData['year'];
			$query_rsIndicatorYear =  $db->prepare("SELECT yr FROM tbl_fiscal_year WHERE id='$year'");
			$query_rsIndicatorYear->execute();
			$row_rsIndicatorYear = $query_rsIndicatorYear->fetch();
			$projstartyear = $row_rsIndicatorYear['yr'];

			$output = $row_OutputData['outputid'];
			$query_rsOutput = $db->prepare("SELECT * FROM tbl_progdetails WHERE id='$output'");
			$query_rsOutput->execute();
			$row_rsOutput = $query_rsOutput->fetch();
			$outputName = $row_rsOutput['output'];

			$projoutputDValue = '';
			if ($target_plan == 1) {
				$years = floor($opduration / 365);
				$remainder = $opduration % 365;
			} else {
				$projoutputDValue = $row_OutputData['duration'];
				$years = floor($projoutputDValue / 365);
				$remainder = $projoutputDValue % 365;
			}

			if ($remainder > 0) {
				$years = $years + 1;
			}

			$spanYear = '';
			$TargetPlan = '';
			$containerTH = '';
			$containerTB = '';
			for ($i = 0; $i < $years; $i++) {
				$query_rsprogTarget =  $db->prepare("SELECT target FROM tbl_progdetails WHERE indicator ='$indicator' and progid ='$programid' and year ='$projstartyear'");
				$query_rsprogTarget->execute();
				$row_rsprogTarget = $query_rsprogTarget->fetch();
				$totalRows_rsprogTarget = $query_rsprogTarget->rowCount();
				$progTarget = $row_rsprogTarget['target'];

				$query_rsprojTarget =  $db->prepare("SELECT SUM(target) as target FROM tbl_project_output_details WHERE indicator ='$indicator' and progid ='$programid' and year ='$projstartyear'");
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

				$endyear = $projstartyear + 1;

				if ($target_plan == 1) {
					$containerTH .= '<th>
					' . $projstartyear . "/" . $endyear . '
					<input type="hidden" class="output_years' . $outputIds  . '" name="output_years' . $outputIds  . '[]" value="' . $projstartyear . ' " >
					<input type="hidden" name="dboutputId[]" value="' . $outputName . ' " >  
					<input type="hidden" id="outputName' . $outputIds . '" name="outputName[]" value="' . $outputIds . ' " > 
					<input type="hidden"   id="cyear_target' . $outputIds .  $projstartyear . '" name="cyear_target' . $outputIds . '[]" value="' . $arr . ' " >
					  <span>Program Target Bal: </span><span style="color:red" id="year_target' . $outputIds .  $projstartyear . '" > ' . number_format($arr, 2) . '</span>
					</th>';

					$containerTB .= '<td> 
					<input type="number" data-id=""  name="target_year' . $outputIds . '[]" placeholder="target"  id="target_year' . $outputIds . $projstartyear . '" class="form-control workplanTarget' . $outputIds . '"
					 onkeyup=get_op_sum_target(' . $outputIds . ',' . $projstartyear . ') required >
					</td>';
				} else {
					$containerTH .= '<th>
					' . $projstartyear . "/" . $endyear . '
					<input type="hidden" class="output_years' . $outputIds  . '" name="output_years' . $outputIds  . '[]" value="' . $projstartyear . ' " >
					<input type="hidden" name="dboutputId[]" value="' . $outputName . ' " >  
					<input type="hidden" id="outputName' . $outputIds . '" name="outputName[]" value="' . $outputIds . ' " > 
					<input type="hidden"   id="cyear_target' . $outputIds .  $projstartyear . '" name="cyear_target' . $outputIds . '[]" value="' . $arr . ' " >
					  <span>Program Target Bal: </span><span style="color:red" id="year_target' . $outputIds .  $projstartyear . '" > ' . number_format($arr, 2) . '</span>
					</th>';
					$containerTB .= '<td> 
					<input type="number" data-id=""  name="target_year' . $outputIds . '[]" placeholder="target"  id="target_year' . $outputIds . $projstartyear . '" class="form-control workplanTarget' . $outputIds . '"
					 onkeyup=get_op_sum_target(' . $outputIds . ',' . $projstartyear . ') required >
					</td>';
				}
				$projstartyear++;
			}



			$data = ' 
				<div class="row clearfix " id="Targetrowcontainer">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="card">
							<div class="header">
								<div class="col-md-5 clearfix" style="margin-top:5px; margin-bottom:5px">
									<h5 style="color:#2B982B"><strong> Output: ' . $outputName . '</strong></h5>
									<input type="hidden" value="' . $outputName . '" id="workplan_opName' . trim($outputIds) . '">
									</div>
								<div class="col-md-5 clearfix" style="margin-top:5px; margin-bottom:5px">
									<h5 style="color:#2B982B"><strong> Indicator: ' . $indname . '</strong></h5>
									<input type="hidden" value="' . $indname . '" id="indicatorName' . trim($outputIds) . '">
									</div>
								<div class="col-md-2 clearfix" style="margin-top:5px; margin-bottom:5px">
									<h5 style="color:#2B982B"><strong> Unit : ' . $unit . '</strong></h5>
									<input type="hidden" value="' . $unit . '" id="unit' . trim($outputIds) . '">
								</div>  
							</div>
							<div class="body">
								<div class="row clearfix "> 
									<div class="col-md-12 ">
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-hover" id="targets" style="width:100%">
												<thead> 

													<tr>
														<th colspan="' . $years . '" >
															<input type="hidden"   id="opid_name' . $outputIds . '" name="opid_name' . $outputIds . '[]" value="' . $outputName . ' " >';
			if ($target_plan == 1) {
				$data .= '
																<input type="hidden"   id="coptarget_target' . $outputIds . '" class="form-control" name="coptarget_target' . $outputIds . '[]" value="' . $op_target . ' " >
																	<span>Output Target Bal: </span>
																	<span style="color:red" id="op_target' . $outputIds . '" >
																		' . number_format($op_target, 2) . '
																	</span>';
			} else {
				$data .= '<input type="hidden"   id="coptarget_target' . $outputIds . '" class="form-control" name="coptarget_target' . $outputIds . '[]" value="' . $opbal . '" >
																<span>Output Target Bal: </span>
																<span style="color:red" id="op_target' . $outputIds . '" >
																' . number_format($opbal) . '
																</span>';
			}

			$data .= '
														</th>
													</tr>
													<tr>
														' . $containerTH . '
													</tr>
												</thead>
												<tbody>
												<tr> ' .  $containerTB . ' </tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>';
			echo $data;
		}
	}


	if (isset($_POST['create_padp_div'])) {
		$progid = $_POST['progid'];
		$year = date("Y");
		//$year = date("Y") - 1;

		//get program sector 
		$query_year = $db->prepare("SELECT id FROM `tbl_fiscal_year` WHERE yr=:adpyr");
		$query_year->execute(array(":adpyr" => $year));
		$rowyear = $query_year->fetch();
		$yearid = $rowyear["id"];

		// get program output details  
		$query_inddetails = $db->prepare("SELECT p.id, p.progid, p.projid, p.indicator, p.year, p.target, p.budget, d.outputid FROM tbl_project_output_details p inner join tbl_project_details d on d.id=p.projoutputid inner join tbl_annual_dev_plan a on a.projid=p.projid WHERE p.progid ='$progid' AND p.year='$year' GROUP BY d.outputid");
		//$query_outputdetails = $db->prepare("SELECT * FROM tbl_progdetails g inner join WHERE progid ='$progid' AND year='$year'");
		$query_inddetails->execute();

		// get program name  
		$query_program = $db->prepare("SELECT * FROM tbl_programs WHERE progid ='$progid'");
		$query_program->execute();
		$row_program = $query_program->fetch();
		$progname = $row_program["progname"];

		$optable  = '
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="header">
							<div class="col-md-12" style="margin-top:5px; margin-bottom:5px">
								<h5>
									<strong> Program: ' . $progname . '</strong>
									<input type="hidden" name="progid" value="' . $progid . '">
									<input type="hidden" name="finyear" value="' . $year . '">
								</h5>
							</div>                                
						</div>
						<div class="body">
							<div class=" class="col-md-12" table-responsive">
								<table class="table table-bordered table-striped table-hover" style="width:100%">
									<thead>
										<tr>
											<th>#</th>
											<th>Output</th>
											<th>Indicator</th>
											<th>Initial Target</th>
											<th>Target</th>
											<th>Initial Budget</th>
											<th>Budget</th>
										</tr>
									</thead>
									<tbody>';
		$sn = 0;
		while ($row_inddetails = $query_inddetails->fetch()) {
			$sn++;
			
			$outputid = $row_inddetails["outputid"];
			$indicatorid = $row_inddetails["indicator"];
			
			$query_outputdetails = $db->prepare("SELECT * FROM tbl_progdetails WHERE id ='$outputid' AND year='$year'");
			$query_outputdetails->execute();
			$row_outputdetails = $query_outputdetails->fetch();
			
			$output = $row_outputdetails["output"];


			//get program budget
			$query_prgbudget =  $db->prepare("SELECT SUM(d.budget) as prjbudget FROM tbl_project_details d left join tbl_annual_dev_plan a on a.projid=d.projid WHERE d.progid ='$progid' AND d.indicator ='$indicatorid' AND d.year='$yearid' AND a.status=0");
			$query_prgbudget->execute();
			$row_prgbudget = $query_prgbudget->fetch();
			//$progbudget = number_format($row_prgbudget['prjbudget'], 2);

			$query_indicator =  $db->prepare("SELECT * FROM tbl_indicator WHERE indid ='$indicatorid'");
			$query_indicator->execute();
			$row_indicator = $query_indicator->fetch();
			$indicator = $row_indicator['indicator_name'];

			$query_Indicator = $db->prepare("SELECT tbl_measurement_units.unit FROM tbl_indicator  INNER JOIN tbl_measurement_units ON tbl_measurement_units.id =tbl_indicator.indicator_unit WHERE tbl_indicator.indid ='$indicatorid' AND baseline=1 AND indicator_category='Output' ");
			$query_Indicator->execute();
			$row = $query_Indicator->fetch();
			$unit = $row['unit'];

			$query_initials =  $db->prepare("SELECT SUM(target) AS target, SUM(budget) as budget FROM `tbl_project_output_details` WHERE progid = '$progid' AND indicator ='$indicatorid' AND year= '$year'");
			$query_initials->execute();
			$row_initials = $query_initials->fetch();
			$initial_target = $row_initials['target'];
			//$initial_budget = $row_initials['budget'];
			$progbudget = number_format($row_initials['budget'], 2);

			$optable  .= '<tr>
					<td>' . $sn . '</td>
					<td>' . $output . '</td>
					<td>' . $unit . " of " . $indicator . '</td>
					<td>' . number_format($initial_target, 2) . '</td>
					<td>
						<input type="text" id="optarget' . $outputid . '" class="form-control" name="optarget[]" placeholder="Enter approved target">
					</td>
					<td>' . $progbudget . '</td>
					<td>
						<input type="number" id="opbudget' . $outputid . '" class="form-control" name="opbudget[]" placeholder="Enter approved budget">
					</td>
					<input type="hidden" name="indid[]" value="' . $indicatorid . '">
					<input type="hidden" name="opid[]" value="' . $outputid . '">
				</tr>';
		}
		$optable  .= '	
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>';
		echo $optable;
	}
	/* 	
	if (isset($_POST['create_padp_div'])) {
		$progid = $_POST['progid'];
		$year = date("Y");

		// get program output details  
		$query_outputdetails = $db->prepare("SELECT * FROM tbl_progdetails WHERE progid ='$progid' AND year='$year'");
		$query_outputdetails->execute();
		//$row_outputdetails = $query_outputdetails->fetch();

		// get program name  
		$query_program = $db->prepare("SELECT * FROM tbl_programs WHERE progid ='$progid'");
		$query_program->execute();
		$row_program = $query_program->fetch();
		$progname = $row_program["progname"];

		$optable  = '
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="header">
							<div class="col-md-12" style="margin-top:5px; margin-bottom:5px">
								<h5>
									<strong> Program: ' . $progname . '</strong>
									<input type="hidden" name="progid" value="'.$progid.'">
									<input type="hidden" name="finyear" value="'.$year.'">
								</h5>
							</div>                                
						</div>
						<div class="body">
							<div class=" class="col-md-12" table-responsive">
								<table class="table table-bordered table-striped table-hover" style="width:100%">
									<thead>
										<tr>
											<th>#</th><th>Output</th><th>Indicator</th><th>Target</th><th>Budget</th>
										</tr>
									</thead>
									<tbody>';
										$sn=0;
										while($row_outputdetails = $query_outputdetails->fetch()) {
											$sn++;
											$outputid = $row_outputdetails["id"];
											$output = $row_outputdetails["output"];
											$indicatorid = $row_outputdetails["indicator"];
											
											$query_indicator =  $db->prepare("SELECT * FROM tbl_indicator WHERE indid ='$indicatorid'");
											$query_indicator->execute();
											$row_indicator = $query_indicator->fetch();
											$indicator = $row_indicator['indicator_name'];
											
											$optable  .= '<tr>
												<td>'.$sn.'</td>
												<td>'.$output.'</td>
												<td>'.$indicator.'</td>
												<td><input type="text" id="optarget'.$outputid.'" class="form-control" name="optarget[]" placeholder="Enter approved target"></td>
												<td><input type="text" id="opbudget'.$outputid.'" class="form-control" name="opbudget[]" placeholder="Enter approved budget"></td>
												<input type="hidden" name="indid[]" value="'.$indicatorid.'">
												<input type="hidden" name="opid[]" value="'.$outputid.'">
											</tr>';
										}
                                    $optable  .= '</tbody>
                                </table>
                            </div>
						</div>
					</div>
			</div>';

		echo $optable;
	} */

	if (isset($_POST['edit_padp_div'])) {
		$progid = $_POST['progid'];
		$year = $_POST['adpyr'];

		//get program sector 
		$query_year = $db->prepare("SELECT id FROM `tbl_fiscal_year` WHERE yr=:adpyr");
		$query_year->execute(array(":adpyr" => $year));
		$rowyear = $query_year->fetch();
		$yearid = $rowyear["id"];

		// get program output details  
		$query_inddetails = $db->prepare("SELECT p.id, p.progid, p.projid, p.indicator, p.year, p.target, p.budget, d.outputid FROM tbl_project_output_details p inner join tbl_project_details d on d.id=p.projoutputid inner join tbl_annual_dev_plan a on a.projid=p.projid WHERE p.progid ='$progid' AND p.year='$year' GROUP BY indicator");
		//$query_outputdetails = $db->prepare("SELECT * FROM tbl_progdetails g inner join WHERE progid ='$progid' AND year='$year'");
		$query_inddetails->execute();

		// get program name  
		$query_program = $db->prepare("SELECT * FROM tbl_programs WHERE progid ='$progid'");
		$query_program->execute();
		$row_program = $query_program->fetch();
		$progname = $row_program["progname"];

		$optable  = '
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="header">
							<div class="col-md-12" style="margin-top:5px; margin-bottom:5px">
								<h5>
									<strong> Program: ' . $progname . '</strong>
									<input type="hidden" name="progid" value="' . $progid . '">
									<input type="hidden" name="finyear" value="' . $year . '">
								</h5>
							</div>                                
						</div>
						<div class="body">
							<div class=" class="col-md-12" table-responsive">
								<table class="table table-bordered table-striped table-hover" style="width:100%">
									<thead>
										<tr>
											<th>#</th>
											<th>Output</th>
											<th>Indicator</th>
											<th>Initial Target</th>
											<th>Target</th>
											<th>Initial Budget</th>
											<th>Budget</th>
										</tr>
									</thead>
									<tbody>';
		$sn = 0;
		while ($row_inddetails = $query_inddetails->fetch()) {
			$sn++;
			
			$outputid = $row_inddetails["outputid"];
			$indicatorid = $row_inddetails["indicator"];
			
			$query_outputdetails = $db->prepare("SELECT * FROM tbl_progdetails WHERE id ='$outputid' AND year='$year'");
			$query_outputdetails->execute();
			$row_outputdetails = $query_outputdetails->fetch();
			
			$output = $row_outputdetails["output"];

			//get program budget
			$query_prgbudget =  $db->prepare("SELECT SUM(d.budget) as prjbudget FROM tbl_project_details d left join tbl_annual_dev_plan a on a.projid=d.projid WHERE d.progid ='$progid' AND d.indicator ='$indicatorid' AND d.year='$yearid' AND a.status=0");
			$query_prgbudget->execute();
			$row_prgbudget = $query_prgbudget->fetch();
			$progbudget = number_format($row_prgbudget['prjbudget'], 2);

			$query_indicator =  $db->prepare("SELECT * FROM tbl_indicator WHERE indid ='$indicatorid'");
			$query_indicator->execute();
			$row_indicator = $query_indicator->fetch();
			$indicator = $row_indicator['indicator_name'];

			$query_Indicator = $db->prepare("SELECT tbl_measurement_units.unit FROM tbl_indicator  INNER JOIN tbl_measurement_units ON tbl_measurement_units.id =tbl_indicator.indicator_unit WHERE tbl_indicator.indid ='$indicatorid' AND baseline=1 AND indicator_category='Output' ");
			$query_Indicator->execute();
			$row = $query_Indicator->fetch();
			$unit = $row['unit'];

			$query_initials =  $db->prepare("SELECT SUM(target) AS target, SUM(budget) as budget FROM `tbl_project_output_details` WHERE progid = '$progid' AND indicator ='$indicatorid' AND year= '$year'");
			$query_initials->execute();
			$row_initials = $query_initials->fetch();
			$initial_target = $row_initials['target'];
			//$initial_budget = $row_initials['budget'];
			$initial_budget = $row_initials['budget'];

			$query_budgetdetails = $db->prepare("SELECT * FROM tbl_programs_based_budget WHERE progid ='$progid' AND finyear='$year' AND opid='$outputid'");
			$query_budgetdetails->execute();
			$row_budgetdetails = $query_budgetdetails->fetch();
			
			$pbbid = $row_budgetdetails['id'];
			$targetvalue = $row_budgetdetails['target'];
			$budgetvalue = $row_budgetdetails['budget'];
			
			$optable  .= '<tr>
												<td>' . $sn . '</td>
												<td>' . $output . '</td>
												<td>' . $unit . " of " . $indicator . '</td>
												<td>' . number_format($initial_target, 2) . '</td>
												<td><input type="text" id="optarget' . $outputid . '" class="form-control" name="optarget[]" placeholder="Enter approved target" value="' . $targetvalue . '"></td>
												<td>' . number_format($initial_budget, 2) . '</td>
												<td><input type="text" id="opbudget' . $outputid . '" class="form-control" name="opbudget[]" placeholder="Enter approved budget" value="' . $budgetvalue . '"></td>
												<input type="hidden" name="indid[]" value="' . $indicatorid . '">
												<input type="hidden" name="opid[]" value="' . $outputid . '">
											</tr>';
		}
		$optable  .= '</tbody>
                                </table>
                            </div>
						</div>
					</div>
			</div>';

		echo $optable;
	}

	if (isset($_POST['view_padp_div'])) {
		$progid = $_POST['progid'];
		$year = $_POST['adpyr'];
		
		//get program sector 
		$query_year = $db->prepare("SELECT id FROM `tbl_fiscal_year` WHERE yr=:adpyr");
		$query_year->execute(array(":adpyr" => $year));
		$rowyear = $query_year->fetch();
		$yearid = $rowyear["id"];
		
		// get program output details  
		$query_inddetails = $db->prepare("SELECT p.id, p.progid, p.projid, p.indicator, p.year, p.target, p.budget, d.outputid FROM tbl_project_output_details p inner join tbl_project_details d on d.id=p.projoutputid  inner join tbl_annual_dev_plan a on a.projid=p.projid WHERE p.progid ='$progid' AND p.year='$year' GROUP BY indicator");
		//$query_outputdetails = $db->prepare("SELECT * FROM tbl_progdetails g inner join WHERE progid ='$progid' AND year='$year'");
		$query_inddetails->execute();

		// get program name  
		$query_program = $db->prepare("SELECT * FROM tbl_programs WHERE progid ='$progid'");
		$query_program->execute();
		$row_program = $query_program->fetch();
		$progname = $row_program["progname"];

		$optable  = '
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="header">
							<div class="col-md-12" style="margin-top:5px; margin-bottom:5px">
								<h5>
									<strong> Program: ' . $progname . '</strong>
								</h5>
							</div>                                
						</div>
						<div class="body">
							<div class=" class="col-md-12" table-responsive">
								<table class="table table-bordered table-striped table-hover" style="width:100%">
									<thead>
										<tr>
											<th>#</th><th>Output</th><th>Indicator</th><th>Target</th><th>Budget</th>
										</tr>
									</thead>
									<tbody>';
		$sn = 0;
		$totalbudget = 0;
		$totaltarget = 0;
		while ($row_inddetails = $query_inddetails->fetch()) {
			$sn++;
			
			$outputid = $row_inddetails["outputid"];
			$indicatorid = $row_inddetails["indicator"];
			
			$query_outputdetails = $db->prepare("SELECT * FROM tbl_progdetails d inner join tbl_indicator i on i.indid=d.indicator WHERE d.id ='$outputid' AND d.year='$year' GROUP BY d.id");
			$query_outputdetails->execute();
			$row_outputdetails = $query_outputdetails->fetch();
			
			$output = $row_outputdetails["output"];
			$indicator = $row_outputdetails['indicator_name'];

			$query_budgetdetails = $db->prepare("SELECT * FROM tbl_programs_based_budget WHERE progid ='$progid' AND finyear='$year' AND opid='$outputid'");
			$query_budgetdetails->execute();
			$row_budgetdetails = $query_budgetdetails->fetch();

			$pbbid = $row_budgetdetails['id'];
			$targetvalue = $row_budgetdetails['target'];
			$budgetvalue = $row_budgetdetails['budget'];
			$totalbudget = $totalbudget + $budgetvalue;
			$totaltarget = $totaltarget + $targetvalue;

			$query_Indicator = $db->prepare("SELECT tbl_measurement_units.unit FROM tbl_indicator  INNER JOIN tbl_measurement_units ON tbl_measurement_units.id =tbl_indicator.indicator_unit WHERE tbl_indicator.indid ='$indicatorid' AND baseline=1 AND indicator_category='Output' ");
			$query_Indicator->execute();
			$row = $query_Indicator->fetch();
			$unit = $row['unit'];

			$optable  .= '<tr>
												<td>' . $sn . '</td>
												<td>' . $output . '</td>
												<td>' . $unit . " of " . $indicator . '</td>
												<td>' . number_format($targetvalue) . '</td>
												<td>' . number_format($budgetvalue, 2) . '</td>
											</tr>';
		}
		$optable  .= '<tr>
											<td></td><td colspan="2"><strong>Total</strong></td><td></td><td><strong>' . number_format($totalbudget, 2) . '</strong></td>
										</tr>
									</tbody>
                                </table>
                            </div>
						</div>
					</div>
			</div>';

		echo $optable;
	}

	if (isset($_POST['create_qtargets_div'])) {
		$progid = $_POST['progid'];
		$year = $_POST['adpyr'];
		$yearnxt = $year + 1;
		
		//get program sector 
		$query_year = $db->prepare("SELECT id FROM `tbl_fiscal_year` WHERE yr=:adpyr");
		$query_year->execute(array(":adpyr" => $year));
		$rowyear = $query_year->fetch();
		$yearid = $rowyear["id"];
		
		// get program output details  
		$query_inddetails = $db->prepare("SELECT p.id, p.progid, p.projid, p.indicator, p.year, p.target, p.budget, d.outputid  FROM tbl_project_output_details p inner join tbl_project_details d on d.id=p.projoutputid inner join tbl_annual_dev_plan a on a.projid=p.projid WHERE p.progid ='$progid' AND p.year='$year' GROUP BY indicator");
		//$query_outputdetails = $db->prepare("SELECT * FROM tbl_progdetails g inner join WHERE progid ='$progid' AND year='$year'");
		$query_inddetails->execute();
		$total_inddetails = $query_inddetails->rowCount();

		// get program output details 
		//$query_outputdetails = $db->prepare("SELECT * FROM tbl_progdetails d inner join tbl_indicator i on i.indid=d.indicator WHERE d.progid ='$progid' AND d.year='$year' GROUP BY d.id");
		//$query_outputdetails->execute();
		//$row_outputdetails = $query_outputdetails->fetch();

		// get program name  
		$query_program = $db->prepare("SELECT * FROM tbl_programs WHERE progid ='$progid'");
		$query_program->execute();
		$row_program = $query_program->fetch();
		$progname = $row_program["progname"];

		$optable  = '
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="header">
							<div class="col-md-12" style="margin-top:5px; margin-bottom:5px">
								<h5>
									<strong> Program: ' . $progname . '</strong>
									<input type="hidden" name="progid" value="' . $progid . '">
									<input type="hidden" name="finyear" value="' . $year . '">
									<br>
									<br>
									<strong>Financial Year: ' . $year . '/' . $yearnxt . '</strong>
								</h5>
							</div>                                
						</div>
						<div class="body">
							<div class=" class="col-md-12" table-responsive">
								<table class="table table-bordered table-striped table-hover" style="width:100%">
									<thead>
										<tr>
											<th>#</th><th>Output</th><th>Quarter 1</th><th>Quarter 2</th><th>Quarter 3</th><th>Quarter 4</th>
										</tr>
									</thead>
									<tbody>';
										if ($total_inddetails > 0) {
											$sn = 0;
											while ($row_inddetails = $query_inddetails->fetch()) {
												$sn++;
												
												$outputid = $row_inddetails["outputid"];
												$indicatorid = $row_inddetails["indicator"];
												
												$query_outputdetails = $db->prepare("SELECT * FROM tbl_progdetails d inner join tbl_indicator i on i.indid=d.indicator WHERE d.indicator ='$indicatorid' AND d.year='$year' GROUP BY d.id");
												$query_outputdetails->execute();
												$row_outputdetails = $query_outputdetails->fetch();
												
												$output = $row_outputdetails["output"];
												$indicator = $row_outputdetails['indicator_name'];
												$unitid = $row_outputdetails["indicator_unit"];

												$query_pbb =  $db->prepare("SELECT * FROM tbl_programs_based_budget WHERE progid ='$progid' AND opid ='$outputid' AND finyear ='$year'");
												$query_pbb->execute();
												$rows_pbb = $query_pbb->fetch();
												$yearlytarget = $rows_pbb['target'];
												$pbbid = $rows_pbb['id'];

												$query_indunit =  $db->prepare("SELECT * FROM tbl_measurement_units WHERE id ='$unitid'");
												$query_indunit->execute();
												$rows_indunit = $query_indunit->fetch();
												$unit = $rows_indunit['unit'];

												$optable  .= '<tr>
													<td>' . $sn . '</td>
													<td>' . $output . ' (Approved: <span id="' . $progid . $outputid . '">' . $yearlytarget . '</span> ' . $unit . ')</td>
													<td><input type="text" id="optarget' . $outputid . '" class="form-control" name="optargetq1[]" placeholder="Enter Q1 Target"></td>
													<td><input type="text" id="optarget' . $outputid . '" class="form-control" name="optargetq2[]" placeholder="Enter Q2 Target"></td>
													<td><input type="text" id="opbudget' . $outputid . '" class="form-control" name="optargetq3[]" placeholder="Enter Q3 Target"></td>
													<td><input type="text" id="opbudget' . $outputid . '" class="form-control" name="optargetq4[]" placeholder="Enter Q4 Target"></td>
													<input type="hidden" name="indid[]" value="' . $indicatorid . '">
													<input type="hidden" name="opid[]" value="' . $outputid . '">
													<input type="hidden" name="pbbid[]" value="' . $pbbid . '">
												</tr>';
											}
										} else {
											$optable .= '<tr><td colspan="7"> Sorry you cannot add targets and budget</td></tr>';
										}
									$optable  .= '</tbody>
                                </table>
                            </div>
						</div>
					</div>
			</div>';

		echo $optable;
	}

	if (isset($_POST['create_independent_qtargets_div'])) {
		$progid = $_POST['progid'];
		$year = $_POST['adpyr'];
		$yearnxt = $year + 1;

		// get program output details 
		$query_outputdetails = $db->prepare("SELECT * FROM tbl_progdetails d inner join tbl_indicator i on i.indid=d.indicator WHERE d.progid ='$progid' AND d.year='$year' GROUP BY d.id");
		$query_outputdetails->execute();
		//$row_outputdetails = $query_outputdetails->fetch();

		// get program name  
		$query_program = $db->prepare("SELECT * FROM tbl_programs WHERE progid ='$progid'");
		$query_program->execute();
		$row_program = $query_program->fetch();
		$progname = $row_program["progname"];

		$optable  = '
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="header">
							<div class="col-md-12" style="margin-top:5px; margin-bottom:5px">
								<h5>
									<strong> Program: ' . $progname . '</strong>
									<input type="hidden" name="progid" value="' . $progid . '">
									<input type="hidden" name="finyear" value="' . $year . '">
									<br>
									<br>
									<strong>Financial Year: ' . $year . '/' . $yearnxt . '</strong>
								</h5>
							</div>                                
						</div>
						<div class="body">
							<div class=" class="col-md-12" table-responsive">
								<table class="table table-bordered table-striped table-hover" style="width:100%">
									<thead>
										<tr>
											<th>#</th>
											<th>Output</th>
											<th>' . $year . '/' . $yearnxt . ' Targets</th>
											<th>Quarter 1</th>
											<th>Quarter 2</th>
											<th>Quarter 3</th>
											<th>Quarter 4</th>
										</tr>
									</thead>
									<tbody>';
										$sn = 0;
										while ($row_outputdetails = $query_outputdetails->fetch()) {
											$sn++;
											$outputid = $row_outputdetails["id"];
											$output = $row_outputdetails["output"];
											$indicatorid = $row_outputdetails["indicator"];
											$unitid = $row_outputdetails["indicator_unit"];
											$target = $row_outputdetails["target"];

											$query_indunit =  $db->prepare("SELECT * FROM tbl_measurement_units WHERE id ='$unitid'");
											$query_indunit->execute();
											$rows_indunit = $query_indunit->fetch();
											$unit = $rows_indunit['unit'];

											$optable  .= '<tr>
												<td>' . $sn . '</td>
												<td>' . $output . '</td>
												<td>' . $target . '</td>
												<td><input type="text" id="optarget' . $outputid . '" class="form-control" name="optargetq1[]" placeholder="Enter Q1 Target"></td>
												<td><input type="text" id="optarget' . $outputid . '" class="form-control" name="optargetq2[]" placeholder="Enter Q2 Target"></td>
												<td><input type="text" id="opbudget' . $outputid . '" class="form-control" name="optargetq3[]" placeholder="Enter Q3 Target"></td>
												<td><input type="text" id="opbudget' . $outputid . '" class="form-control" name="optargetq4[]" placeholder="Enter Q4 Target"></td>
												<input type="hidden" name="indid[]" value="' . $indicatorid . '">
												<input type="hidden" name="opid[]" value="' . $outputid . '">
											</tr>';
										}
									$optable  .= '</tbody>
                                </table>
                            </div>
						</div>
					</div>
			</div>';

		echo $optable;
	}


	if (isset($_POST['edit_qtargets_div'])) {
		$progid = $_POST['progid'];
		$year = $_POST['adpyr'];
		
		//get program sector 
		$query_year = $db->prepare("SELECT id FROM `tbl_fiscal_year` WHERE yr=:adpyr");
		$query_year->execute(array(":adpyr" => $year));
		$rowyear = $query_year->fetch();
		$yearid = $rowyear["id"];
		
		// get program output details  
		$query_inddetails = $db->prepare("SELECT p.id, p.progid, p.projid, p.indicator, p.year, p.target, p.budget, d.outputid  FROM tbl_project_output_details p inner join tbl_project_details d on d.id=p.projoutputid inner join tbl_annual_dev_plan a on a.projid=p.projid WHERE p.progid ='$progid' AND p.year='$year' GROUP BY indicator");
		//$query_outputdetails = $db->prepare("SELECT * FROM tbl_progdetails g inner join WHERE progid ='$progid' AND year='$year'");
		$query_inddetails->execute();
		$total_inddetails = $query_inddetails->rowCount();

		// get program output details  
		/* $query_outputdetails = $db->prepare("SELECT * FROM tbl_progdetails d inner join tbl_indicator i on i.indid=d.indicator WHERE d.progid ='$progid' AND d.year='$year' GROUP BY d.id");
		$query_outputdetails->execute(); */
		//$row_outputdetails = $query_outputdetails->fetch();

		// get program name  
		$query_program = $db->prepare("SELECT * FROM tbl_programs WHERE progid ='$progid'");
		$query_program->execute();
		$row_program = $query_program->fetch();
		$progname = $row_program["progname"];

		$optable  = '
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
				<div class="header">
					<div class="col-md-12" style="margin-top:5px; margin-bottom:5px">
						<h5>
							<strong> Program: ' . $progname . '</strong>
							<input type="hidden" name="progid" value="' . $progid . '">
							<input type="hidden" name="finyear" value="' . $year . '">
						</h5>
					</div>                                
				</div>
				<div class="body">
					<div class=" class="col-md-12" table-responsive">
						<table class="table table-bordered table-striped table-hover" style="width:100%">
							<thead>
								<tr>
									<th>#</th><th>Output</th><th>Quarter 1</th><th>Quarter 2</th><th>Quarter 3</th><th>Quarter 4</th>
								</tr>
							</thead>
							<tbody>';
								$sn = 0;
								while ($row_inddetails = $query_inddetails->fetch()) {
									$sn++;
									
									$outputid = $row_inddetails["outputid"];
									$indicatorid = $row_inddetails["indicator"];
									
									$query_outputdetails = $db->prepare("SELECT * FROM tbl_progdetails d inner join tbl_indicator i on i.indid=d.indicator WHERE d.id ='$outputid' AND d.year='$year' GROUP BY d.id");
									$query_outputdetails->execute();
									$row_outputdetails = $query_outputdetails->fetch();
									
									$output = $row_outputdetails["output"];
									$indicator = $row_outputdetails['indicator_name'];
									$unitid = $row_outputdetails["indicator_unit"];
									
									$query_pbb =  $db->prepare("SELECT * FROM tbl_programs_based_budget WHERE progid ='$progid' AND opid ='$outputid' AND finyear ='$year'");
									$query_pbb->execute();
									$rows_pbb = $query_pbb->fetch();
									$yearlytarget = $rows_pbb['target'];
									$pbbid = $rows_pbb['id'];

									$query_indunit =  $db->prepare("SELECT * FROM tbl_measurement_units WHERE id ='$unitid'");
									$query_indunit->execute();
									$rows_indunit = $query_indunit->fetch();
									$unit = $rows_indunit['unit'];

									$query_targetdetails = $db->prepare("SELECT * FROM tbl_programs_quarterly_targets WHERE progid ='$progid' AND year='$year' AND opid='$outputid'");
									$query_targetdetails->execute();
									$row_targetdetails = $query_targetdetails->fetch();

									$output = $row_outputdetails["output"];
									$indicatorid = $row_outputdetails["indicator"];
									$indicator = $row_outputdetails['indicator_name'];
									$targetQ1 = $row_targetdetails['Q1'];
									$targetQ2 = $row_targetdetails['Q2'];
									$targetQ3 = $row_targetdetails['Q3'];
									$targetQ4 = $row_targetdetails['Q4'];

									$optable  .= '<tr>
										<td>' . $sn . '</td>
										<td>' . $output . ' (Approved: <span id="' . $progid . $outputid . '">' . $yearlytarget . '</span> ' . $unit . ')</td>
										<td><input type="text" id="optarget' . $outputid . '" class="form-control" name="optargetq1[]" placeholder="Enter Q1 Target" value="' . $targetQ1 . '"></td>
										<td><input type="text" id="optarget' . $outputid . '" class="form-control" name="optargetq2[]" placeholder="Enter Q2 Target" value="' . $targetQ2 . '"></td>
										<td><input type="text" id="opbudget' . $outputid . '" class="form-control" name="optargetq3[]" placeholder="Enter Q3 Target" value="' . $targetQ3 . '"></td>
										<td><input type="text" id="opbudget' . $outputid . '" class="form-control" name="optargetq4[]" placeholder="Enter Q4 Target" value="' . $targetQ4 . '"></td>
										<input type="hidden" name="indid[]" value="' . $indicatorid . '">
										<input type="hidden" name="opid[]" value="' . $outputid . '">
										<input type="hidden" name="pbbid[]" value="' . $pbbid . '">
									</tr>';
								}
							$optable  .= '</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>';

		echo $optable;
	}

	if (isset($_POST['edit_indepedent_programs_qtargets_div'])) {
		$progid = $_POST['progid'];
		$year = $_POST['adpyr'];

		// get program output details  
		$query_outputdetails = $db->prepare("SELECT * FROM tbl_progdetails d inner join tbl_indicator i on i.indid=d.indicator WHERE d.progid ='$progid' AND d.year='$year' GROUP BY d.id");
		$query_outputdetails->execute();
		//$row_outputdetails = $query_outputdetails->fetch();

		// get program name  
		$query_program = $db->prepare("SELECT * FROM tbl_programs WHERE progid ='$progid'");
		$query_program->execute();
		$row_program = $query_program->fetch();
		$progname = $row_program["progname"];

		$optable  = '
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
				<div class="header">
					<div class="col-md-12" style="margin-top:5px; margin-bottom:5px">
						<h5>
							<strong> Program: ' . $progname . '</strong>
							<input type="hidden" name="progid" value="' . $progid . '">
							<input type="hidden" name="finyear" value="' . $year . '">
						</h5>
					</div>                                
				</div>
				<div class="body">
					<div class=" class="col-md-12" table-responsive">
						<table class="table table-bordered table-striped table-hover" style="width:100%">
							<thead>
								<tr>
									<th>#</th><th>Output</th><th>' . $year . '/' . $yearnxt . ' Targets</th><th>Quarter 1</th><th>Quarter 2</th><th>Quarter 3</th><th>Quarter 4</th>
								</tr>
							</thead>
							<tbody>';
								$sn = 0;
								while ($row_outputdetails = $query_outputdetails->fetch()) {
									$outputid = $row_outputdetails["id"];
									$unitid = $row_outputdetails["indicator_unit"];

									$query_indunit =  $db->prepare("SELECT * FROM tbl_measurement_units WHERE id ='$unitid'");
									$query_indunit->execute();
									$rows_indunit = $query_indunit->fetch();
									$unit = $rows_indunit['unit'];

									$query_targetdetails = $db->prepare("SELECT * FROM tbl_independent_programs_quarterly_targets WHERE progid ='$progid' AND year='$year' AND opid='$outputid'");
									$query_targetdetails->execute();
									$row_targetdetails = $query_targetdetails->fetch();

									$output = $row_outputdetails["output"];
									$indicatorid = $row_outputdetails["indicator"];
									$indicator = $row_outputdetails['indicator_name'];
									$target = $row_outputdetails['target'];
									$pbbid = $row_targetdetails['id'];
									$targetQ1 = $row_targetdetails['Q1'];
									$targetQ2 = $row_targetdetails['Q2'];
									$targetQ3 = $row_targetdetails['Q3'];
									$targetQ4 = $row_targetdetails['Q4'];

									$sn++;

									$optable  .= '<tr>
										<td>' . $sn . '</td>
										<td>' . $output . '</td>
										<td>' . $target . '</td>
										<td><input type="text" id="optarget' . $outputid . '" class="form-control" name="optargetq1[]" placeholder="Enter Q1 Target" value="' . $targetQ1 . '"></td>
										<td><input type="text" id="optarget' . $outputid . '" class="form-control" name="optargetq2[]" placeholder="Enter Q2 Target" value="' . $targetQ2 . '"></td>
										<td><input type="text" id="opbudget' . $outputid . '" class="form-control" name="optargetq3[]" placeholder="Enter Q3 Target" value="' . $targetQ3 . '"></td>
										<td><input type="text" id="opbudget' . $outputid . '" class="form-control" name="optargetq4[]" placeholder="Enter Q4 Target" value="' . $targetQ4 . '"></td>
										<input type="hidden" name="indid[]" value="' . $indicatorid . '">
										<input type="hidden" name="opid[]" value="' . $outputid . '">
										<input type="hidden" name="pbbid[]" value="' . $pbbid . '">
									</tr>';
								}
							$optable  .= '</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>';

		echo $optable;
	}

	if (isset($_POST['view_qtargets_div'])) {
		$progid = $_POST['progid'];
		$year = $_POST['adpyr'];
		$yearnxt = $year + 1;
		
		//get program sector 
		$query_year = $db->prepare("SELECT id FROM `tbl_fiscal_year` WHERE yr=:adpyr");
		$query_year->execute(array(":adpyr" => $year));
		$rowyear = $query_year->fetch();
		$yearid = $rowyear["id"];
		
		// get program output details  
		$query_inddetails = $db->prepare("SELECT p.id, p.progid, p.projid, p.indicator, p.year, p.target, p.budget, d.outputid FROM tbl_project_output_details p inner join tbl_project_details d on d.id=p.projoutputid inner join tbl_annual_dev_plan a on a.projid=p.projid WHERE p.progid ='$progid' AND p.year='$year' GROUP BY indicator");
		//$query_outputdetails = $db->prepare("SELECT * FROM tbl_progdetails g inner join WHERE progid ='$progid' AND year='$year'");
		$query_inddetails->execute();

		// get program name  
		$query_program = $db->prepare("SELECT * FROM tbl_programs WHERE progid ='$progid'");
		$query_program->execute();
		$row_program = $query_program->fetch();
		$progname = $row_program["progname"];

		$optable  = '
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
				<div class="header">
					<div class="col-md-12" style="margin-top:5px; margin-bottom:5px">
						<h5>
							<strong> Program: ' . $progname . '</strong>
							<br>
							<br>
							<strong>Financial Year: ' . $year . '/' . $yearnxt . '</strong>
						</h5>
					</div>                                
				</div>
				<div class="body">
					<div class=" class="col-md-12" table-responsive">
						<table class="table table-bordered table-striped table-hover" style="width:100%">
							<thead>
								<tr>
									<th>#</th><th>Output</th><th>Quarter 1</th><th>Quarter 2</th><th>Quarter 3</th><th>Quarter 4</th>
								</tr>
							</thead>
							<tbody>';
								$sn = 0;
								while ($row_inddetails = $query_inddetails->fetch()) {
									$sn++;
									
									$outputid = $row_inddetails["outputid"];
									$indicatorid = $row_inddetails["indicator"];
									
									$query_outputdetails = $db->prepare("SELECT * FROM tbl_progdetails d inner join tbl_indicator i on i.indid=d.indicator WHERE d.id ='$outputid' AND d.year='$year' GROUP BY d.id");
									$query_outputdetails->execute();
									$row_outputdetails = $query_outputdetails->fetch();
									
									$output = $row_outputdetails["output"];
									$indicator = $row_outputdetails['indicator_name'];
									$unitid = $row_outputdetails["indicator_unit"];

									$query_pbb =  $db->prepare("SELECT * FROM tbl_programs_based_budget WHERE progid ='$progid' AND opid ='$outputid' AND finyear ='$year'");
									$query_pbb->execute();
									$rows_pbb = $query_pbb->fetch();
									$yearlytarget = $rows_pbb['target'];
									$pbbid = $rows_pbb['id'];

									$query_indunit =  $db->prepare("SELECT * FROM tbl_measurement_units WHERE id ='$unitid'");
									$query_indunit->execute();
									$rows_indunit = $query_indunit->fetch();
									$unit = $rows_indunit['unit'];

									$query_targetdetails = $db->prepare("SELECT * FROM tbl_programs_quarterly_targets WHERE progid ='$progid' AND year='$year' AND opid='$outputid'");
									$query_targetdetails->execute();
									$row_targetdetails = $query_targetdetails->fetch();

									$output = $row_outputdetails["output"];
									$indicatorid = $row_outputdetails["indicator"];
									$indicator = $row_outputdetails['indicator_name'];
									$pbbid = $row_targetdetails['id'];
									$targetQ1 = $row_targetdetails['Q1'];
									$targetQ2 = $row_targetdetails['Q2'];
									$targetQ3 = $row_targetdetails['Q3'];
									$targetQ4 = $row_targetdetails['Q4'];

									$optable  .= '<tr>
										<td>' . $sn . '</td>
										<td>' . $output . ' (Ceiling: <span id="' . $progid . $outputid . '">' . $yearlytarget . '</span> ' . $unit . ')</td>
										<td>' . $targetQ1 . '</td>
										<td>' . $targetQ2 . '</td>
										<td>' . $targetQ3 . '</td>
										<td>' . $targetQ4 . '</td>
									</tr>';
								}
							$optable  .= '</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>';

		echo $optable;
	}

	if (isset($_POST['view_independent_qtargets_div'])) {
		$progid = $_POST['progid'];
		$year = $_POST['adpyr'];
		$yearnxt = $year + 1;

		// get program output details  
		$query_outputdetails = $db->prepare("SELECT * FROM tbl_progdetails d inner join tbl_indicator i on i.indid=d.indicator WHERE d.progid ='$progid' AND d.year='$year' GROUP BY d.id");
		$query_outputdetails->execute();
		//$row_outputdetails = $query_outputdetails->fetch();

		// get program name  
		$query_program = $db->prepare("SELECT * FROM tbl_programs WHERE progid ='$progid'");
		$query_program->execute();
		$row_program = $query_program->fetch();
		$progname = $row_program["progname"];

		$optable  = '
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
				<div class="header">
					<div class="col-md-12" style="margin-top:5px; margin-bottom:5px">
						<h5>
							<strong> Program: ' . $progname . '</strong>
							<br>
							<br>
							<strong>Financial Year: ' . $year . '/' . $yearnxt . '</strong>
						</h5>
					</div>                                
				</div>
				<div class="body">
					<div class=" class="col-md-12" table-responsive">
						<table class="table table-bordered table-striped table-hover" style="width:100%">
							<thead>
								<tr>
									<th>#</th><th>Output</th><th>Quarter 1</th><th>Quarter 2</th><th>Quarter 3</th><th>Quarter 4</th>
								</tr>
							</thead>
							<tbody>';
								$sn = 0;
								while ($row_outputdetails = $query_outputdetails->fetch()) {
									$outputid = $row_outputdetails["id"];
									$unitid = $row_outputdetails["indicator_unit"];

									$query_indunit =  $db->prepare("SELECT * FROM tbl_measurement_units WHERE id ='$unitid'");
									$query_indunit->execute();
									$rows_indunit = $query_indunit->fetch();
									$unit = $rows_indunit['unit'];

									$query_targetdetails = $db->prepare("SELECT * FROM tbl_independent_programs_quarterly_targets WHERE progid ='$progid' AND year='$year' AND opid='$outputid'");
									$query_targetdetails->execute();
									$row_targetdetails = $query_targetdetails->fetch();


									$output = $row_outputdetails["output"];
									$indicatorid = $row_outputdetails["indicator"];
									$indicator = $row_outputdetails['indicator_name'];
									$pbbid = $row_targetdetails ? $row_targetdetails['id'] : "";
									$targetQ1 = $row_targetdetails ? $row_targetdetails['Q1'] : 0;
									$targetQ2 = $row_targetdetails ? $row_targetdetails['Q2'] : 0;
									$targetQ3 = $row_targetdetails ? $row_targetdetails['Q3'] : 0;
									$targetQ4 = $row_targetdetails ? $row_targetdetails['Q4'] : 0;

									$sn++;

									$yearlytarget = $targetQ1 + $targetQ2 + $targetQ3 + $targetQ4;

									$optable  .= '<tr>
										<td>' . $sn . '</td>
										<td>' . $output . ' (Ceiling: <span id="' . $progid . $outputid . '">' . $yearlytarget . '</span> ' . $unit . ')</td>
										<td>' . $targetQ1 . '</td>
										<td>' . $targetQ2 . '</td>
										<td>' . $targetQ3 . '</td>
										<td>' . $targetQ4 . '</td>
									</tr>';
								}
							$optable  .= '</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>';
		echo $optable;
	}


	if (isset($_POST["approvedbudget"])) {
		$projid = $_POST['projid'];
		$budget = $_POST['projapprovedbudget'];
		$budgetyear = $_POST['budgetyear'];
		$user_name = $_POST['user_name'];
		$date = date("Y-m-d");

		$queryuserdetails = $db->prepare("SELECT * FROM users WHERE username = '$user_name'");
		$queryuserdetails->execute();
		$row_user_details =  $queryuserdetails->fetch();
		$userid = $row_user_details["userid"];

		$insertbudget = $db->prepare("INSERT INTO `tbl_project_approved_yearly_budget`(projid, year, amount, created_by, date_created)  VALUES(:projid, :year, :amount, :createdby, :datecreated)");
		$results  = $insertbudget->execute(array(":projid" => $projid, ":year" => $budgetyear, ":amount" => $budget, ":createdby" => $userid, ":datecreated" => $date));


		if ($results === TRUE) {

			$valid['success'] = true;
			$valid['messages'] = "Successfully Approved";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while approving!!";
		}
		echo json_encode($valid);
	}
} catch (PDOException $ex) {
	// $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $ex->getMessage();
}
