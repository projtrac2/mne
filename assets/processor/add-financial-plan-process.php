<?php
include_once "controller.php";
try {
    // delete information on page readty 
    if (isset($_POST['emptyTables'])) {
        $projid = $_POST['projid'];
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
            if ($results) {
                echo json_encode("Data Deleted Successfully");
            }
        }
    }

    // get personnel from project team // select 
    if (isset($_POST['getpersonel'])) {
        $projid = $_POST['projid'];
        $query_rsPersonel = $db->prepare("SELECT t.* FROM tbl_projmembers m inner join tbl_projteam2 t on t.ptid=m.ptid where m.projid = :projid");
        $query_rsPersonel->execute(array(":projid" => $projid));
        $row_rsPersonel = $query_rsPersonel->fetch();
        $totalRows_rsPersonel = $query_rsPersonel->rowCount();

        $input = '<option value="">.... Select from list ....</option>';
        do {
            $ptnid = $row_rsPersonel['ptid'];
            $ptnname = $row_rsPersonel['fullname'];
            $title = $row_rsPersonel['title'];
            $input .= '<option value="' . $ptnid . '">' . $title. '.'. $ptnname . '</option>';
        } while ($row_rsPersonel = $query_rsPersonel->fetch());
		
        echo $input;
    }

    // get financiers // select 
    if (isset($_POST['getfinancier'])) {
        $projid = $_POST['getfinancier'];
        $query_rsFunding =  $db->prepare("SELECT * FROM tbl_myprojfunding WHERE projid = :projid");
        $query_rsFunding->execute(array(":projid" => $projid));
        $row_rsFunding = $query_rsFunding->fetch();
        $totalRows_rsFunding = $query_rsFunding->rowCount();

        if ($totalRows_rsFunding > 0) {
            echo '<option value="">Select Financier from list</option>';
            do {
                $projfinid = $row_rsFunding['id'];
                $projamount = $row_rsFunding['amountfunding'];
                $funderid = $row_rsFunding['financier'];
                $sourcat =$row_rsFunding['sourcecategory'];
				
                $query_rsPlanFunding =  $db->prepare("SELECT SUM(amount) as amount FROM tbl_project_cost_funders_share WHERE funder =:funderid AND projid=:projid");
                $query_rsPlanFunding->execute(array(":funderid" => $funderid, ':projid' => $projid));
                $row_rsPlanFunding = $query_rsPlanFunding->fetch();
                $totalRows_rsPlanFunding = $query_rsPlanFunding->rowCount();
                $spent_plan_amount = $row_rsPlanFunding['amount'];
				if(is_null($spent_plan_amount)){
					$spent_plan_amount = 0;
				}
					

                //calculate amount remaining 
                $remaining = $projamount - $spent_plan_amount;

                if ($remaining > 0) {
					$query_rsFunder = $db->prepare("SELECT * FROM tbl_financiers WHERE id='$funderid'");
					$query_rsFunder->execute();
					$row_rsFunder = $query_rsFunder->fetch();
					$totalRows_rsFunder = $query_rsFunder->rowCount();
					$funder = $row_rsFunder['financier'];
					echo '<option value="' . $funderid . '">' . $funder . '</option>';
                }
            } while ($row_rsFunding = $query_rsFunding->fetch());
        }
    }

    // get financier ceiling 
    if (isset($_POST['cfinance'])) {
        $projfinid = $_POST['financeId'];
        $projid = $_POST['projid'];

        $query_rsFunding =  $db->prepare("SELECT * FROM tbl_myprojfunding WHERE financier =:projfinid AND projid=:projid ");
        $query_rsFunding->execute(array(":projfinid" => $projfinid, ":projid" => $projid));
        $row_rsFunding = $query_rsFunding->fetch();
        $totalRows_rsFunding = $query_rsFunding->rowCount();
        $projamount = $row_rsFunding['amountfunding'];

        $query_rsPlanFunding =  $db->prepare("SELECT SUM(amount) as amount FROM tbl_project_cost_funders_share WHERE funder =:funderid AND projid=:projid");
        $query_rsPlanFunding->execute(array(":funderid" => $projfinid, ":projid" => $projid));
        $row_rsPlanFunding = $query_rsPlanFunding->fetch();
        $totalRows_rsPlanFunding = $query_rsPlanFunding->rowCount();
        $spent_plan_amount = $row_rsPlanFunding['amount'];
		if(is_null($spent_plan_amount)){
			$spent_plan_amount = 0;
		}
		
        //calculate amount remaining 
        $remaining = $projamount - $spent_plan_amount;
        $arr = [];
        if ($remaining > 0) {
            $remaining = $remaining;
            $arr =   array("remaining" => $remaining, "msg" => "true");
        } else {
            $arr =   array("msg" => "false");
        }

        echo json_encode($arr);
    }

    // add new important details from modal to db 
    if (isset($_POST['newitem'])) {
        $result3 = true;
        $outputid = $_POST['foutputid'];
        $projid = $_POST['projid'];
        $type = $_POST['ftype'];
        $planid = $_POST['fplanid'];
        $createdby = $_POST['user_name'];
        $current_date = date("Y-m-d H:i:s");
        $financeids = [];
        $timelineid = "";
        $remarkid = "";

        // add the remarks 
        if (isset($type) && !empty($type)) {
            $comments = trim(stripslashes($_POST['comments']));
            //remarks
            $insertSQL = $db->prepare("INSERT INTO tbl_project_direct_cost_plan (projid, outputid, comments, cost_type, created_by, date_created) VALUES (:projid, :outputid, :comments, :type, :created_by, :date_created)");
            $result2  = $insertSQL->execute(array(':projid' => $projid, ':outputid' => $outputid,  ":comments" => $comments, ":type" => $type, ':created_by' => $createdby, ':date_created' => $current_date));
            if ($result2) {
                $remarkid = $db->lastInsertId();
            }
        }

        // add financiers 
        if (isset($_POST['amountfunding']) && isset($_POST['finance'])) {
            $amountfunding = $_POST['amountfunding'];
            $financierid = $_POST['finance'];
            for ($t = 0; $t < count($financierid); $t++) {
                // get financier id 
                $funder = $financierid[$t];
                $amount = $amountfunding[$t];
                $insertSQL = $db->prepare("INSERT INTO tbl_project_cost_funders_share (projid, outputid, type, plan_id, funder, amount, created_by, date_created) VALUES (:projid,:outputid, :type, :plan_id, :funder, :amount,  :created_by, :date_created)");
                $result1  = $insertSQL->execute(array(':projid' => $projid, ':outputid' => $outputid, ":type" => $type, ":plan_id" => $remarkid, ":funder" => $funder, ":amount" => $amount, ':created_by' => $createdby, ':date_created' => $current_date));
                if ($result1) {
                    $financeids[] =  $db->lastInsertId();
                }
            }
        }

        $finids = implode(",", $financeids);
        //add timeline 
        if (isset($_POST['timelinedate']) && !empty($_POST['timelinedate'])) {
            $timeline = $_POST['timelinedate'];
            $responsible = null;
            if (isset($_POST['responsible']) && !empty($_POST['responsible'])) {
                $responsible = $_POST['responsible'];
            }
			
            $insertSQL = $db->prepare("INSERT INTO tbl_project_expenditure_timeline (projid, outputid, type, plan_id, disbursement_date, responsible, created_by, date_created) VALUES (:projid, :outputid, :type, :plan_id, :disbursement_date, :responsible,  :created_by, :date_created)");
            $result3  = $insertSQL->execute(array(':projid' => $projid, ':outputid' => $outputid, ":type" => $type, ":plan_id" => $remarkid, ":disbursement_date" => $timeline, ":responsible" => $responsible, ':created_by' => $createdby, ':date_created' => $current_date));
            if ($result3) {
                $timelineid = $db->lastInsertId();
            }
        }

        if ($result1  && $result2 && $result3) {
            $message = "Record Created Successfully";
        } else {
            $message = "Record could not be Successfully";
        }

        $arr = array("message" => $message, "finance" => $finids, "remarks" => $remarkid, "timeline" => $timelineid);
        echo json_encode($arr);
    }

    if (isset($_POST['edititem'])) {
        $result3 = true;
        $outputid = $_POST['foutputid'];
        $type = $_POST['ftype'];
        $planid = $_POST['fplanid'];
        $projid = $_POST['projid'];
        $createdby = $_POST['user_name'];
        $current_date = date("Y-m-d H:i:s");
        $financeids = [];
        $remarkid = $_POST['remarkid'];

        // update remarks
        if (isset($_POST['comments'])) {
            $comments = trim(stripslashes($_POST['comments']));
            $insertSQL = $db->prepare("UPDATE tbl_project_direct_cost_plan SET comments=:comments WHERE id=:id");
            $result2  = $insertSQL->execute(array(":comments" => $comments, ":id" => $remarkid));
        }

        // update financier information 
        if (isset($_POST['amountfunding']) && isset($_POST['finance'])) {
            $amountfunding = $_POST['amountfunding'];
            $financierid = $_POST['finance'];
            $dfinid = explode(",", $_POST['dfinid']);

            for ($i = 0; $i < count($dfinid); $i++) {
                $deleteQuery = $db->prepare("DELETE FROM tbl_project_cost_funders_share WHERE outputid=:outputid AND id=:id");
                $results1 = $deleteQuery->execute(array(':outputid' => $outputid, ':id' => $dfinid[$i]));
            }

            if ($results1) {
                for ($t = 0; $t < count($financierid); $t++) {
                    $funder = $financierid[$t];
                    $amount = $amountfunding[$t];
					
                    $insertSQL = $db->prepare("INSERT INTO tbl_project_cost_funders_share (projid, outputid, type, plan_id, funder, amount, created_by, date_created) VALUES (:projid,:outputid, :type, :plan_id, :funder, :amount,  :created_by, :date_created)");
                    $result1  = $insertSQL->execute(array(':projid' => $projid, ':outputid' => $outputid, ":type" => $type, ":plan_id" => $remarkid, ":funder" => $funder, ":amount" => $amount, ':created_by' => $createdby, ':date_created' => $current_date));
                    if ($result1) {
                        $financeids[] =  $db->lastInsertId();
                    }
                }
            }
        }

        //update timeline  
        if (isset($_POST['timelinedate'])) {
            $timeline = $_POST['timelinedate'];
            $timelineid = $_POST['timelineid']; 
            $responsible =null;
            if(!empty($_POST['responsible'])){
               $responsible = $_POST['responsible'];
            }

            $insertSQL = $db->prepare("UPDATE tbl_project_expenditure_timeline SET disbursement_date=:disbursement_date, responsible=:responsible WHERE  id=:id");
            $result3  = $insertSQL->execute(array(":disbursement_date" => $timeline, ":responsible" => $responsible, ":id" => $timelineid));
        }

        if ($result1  && $result2 && $result3) {
            $projstage = 6;
            $insertSQL = $db->prepare("UPDATE tbl_projects SET  projstage=:projstage WHERE  projid=:projid");
            $results  = $insertSQL->execute(array(":projstage" => $projstage, ":projid" => $projid));
            if ($results) {
                $message = "Record Updated Successfully";
            }
        } else {
            $message = "Record Could not be Updated ";
        }

        $finids = implode(",", $financeids);
        $arr = array("message" => $message, "finance" => $finids, "remarks" => $remarkid, "timeline" => $timelineid);
        echo json_encode($arr);
    }

    // delete information for user // when row is deleted  // when values of either no of units of units cost changes 
    if (isset($_POST['deleteItem'])) {
        $projid = $_POST['projid'];
        $outputid = $_POST['outputid'];

        $results3 = true;
        $results1 = true;
        $results2 = true;

        if (isset($_POST['dfinid'])) {
            $dfinid = explode(",", $_POST['dfinid']);
            for ($i = 0; $i < count($dfinid); $i++) {
                $deleteQuery = $db->prepare("DELETE FROM tbl_project_cost_funders_share WHERE  id=:id");
                $results1 = $deleteQuery->execute(array(':id' => $dfinid[$i]));
            }
        }

        if (isset($_POST['drmid'])) {
            $drmid = $_POST['drmid'];
            $deleteQuery = $db->prepare("DELETE FROM tbl_project_direct_cost_plan WHERE id=:id");
            $results2 = $deleteQuery->execute(array(':id' => $drmid));
        }


        if (isset($_POST['dexpid'])) {
            $dexpid = $_POST['dexpid'];
            $deleteQuery = $db->prepare("DELETE FROM tbl_project_expenditure_timeline WHERE  id=:id");
            $results3 = $deleteQuery->execute(array(':id' => $dexpid));
        }

        if ($results1 && $results2 && $results3) {
            $projstage = 5;
            $insertSQL = $db->prepare("UPDATE tbl_projects SET  projstage=:projstage WHERE  projid=:projid");
            $results  = $insertSQL->execute(array(":projstage" => $projstage, ":projid" => $projid));
            if ($results) {
                echo json_encode("Data Deleted Successfully");
            }
        }
    }

    // delete a financier on remove financier row // delete financier on change that value of a 
    if (isset($_POST['deleteFinancier'])) {
        if (isset($_POST['deletefinid'])) {
            $dfinid = $_POST['deletefinid'];
            $deleteQuery = $db->prepare("DELETE FROM tbl_project_cost_funders_share WHERE  id=:id");
            $results1 = $deleteQuery->execute(array(':id' => $dfinid));

            if ($results1) {
                $projstage = 5;
                $insertSQL = $db->prepare("UPDATE tbl_projects SET  projstage=:projstage WHERE  projid=:projid");
                $results  = $insertSQL->execute(array(":projstage" => $projstage, ":projid" => $projid));
                if ($results) {
                    echo json_encode("Record Deleted Successfully");
                }
            } else {
                echo json_encode("Record Could not be Deleted");
            }
        }
    }

    // get details for editing for modal 
    if (isset($_POST['editdetails'])) {
        $crmkid = $_POST['crmkid'];
        $outputid = $_POST['outputid'];
        $disbursement_date = "";
        $responsible = "";

        if (isset($_POST['cdsmttimeline'])) {
            $cdsmttimeline = $_POST['cdsmttimeline'];
            //get distributed timeline details 
            $query_rsTimeline = $db->prepare("SELECT *  FROM tbl_project_expenditure_timeline WHERE outputid =:outputid AND id=:cdsmttimeline ");
            $query_rsTimeline->execute(array(":outputid" => $outputid, ":cdsmttimeline" => $cdsmttimeline));
            $row_rsTimeline = $query_rsTimeline->fetch();
            $totalRows_rsTimeline = $query_rsTimeline->rowCount();
            $disbursement_date  = $row_rsTimeline['disbursement_date'];
            $responsible = $row_rsTimeline['responsible'];
        }

        // get remarks 
        $query_rsRemarks = $db->prepare("SELECT *  FROM tbl_project_direct_cost_plan WHERE outputid =:outputid AND id=:crmkid ");
        $query_rsRemarks->execute(array(":outputid" => $outputid, ":crmkid" => $crmkid));
        $row_rsRemarks = $query_rsRemarks->fetch();
        $totalRows_rsRemarks = $query_rsRemarks->rowCount();
        $comment = $row_rsRemarks['comments'];

        $arr = array("disbursement_date" => $disbursement_date, "responsible" => $responsible, "comment" => $comment);
        echo json_encode($arr);
    }

    // get financier rows for editing 
    if (isset($_POST['getfinancieredit'])) {
        $cfinid = explode(",", $_POST['cfinid']);
        $outputid = $_POST['outputid'];
        $projid = $_POST['projid'];

        $rowno = 1;
        $fin = '<tr></tr>';
        // $ceiling = 0;
        // $remaining = 0;
        $plan_ceiling = 0;
        $plan_ceiling_value = 0;

        $history = '';
        for ($i = 0; $i < count($cfinid); $i++) {
            $rowno++;
            $option = '';

            $cfid = $cfinid[$i];
            // get fiancier details 
            $query_rsFinancier = $db->prepare("SELECT *  FROM tbl_project_cost_funders_share WHERE outputid =:outputid AND id=:cfinid ");
            $query_rsFinancier->execute(array(":outputid" => $outputid, ":cfinid" => $cfid));
            $row_rsFinancier = $query_rsFinancier->fetch();
            $totalRows_rsFinancier = $query_rsFinancier->rowCount();
            $amount = $row_rsFinancier['amount'];
            $fndid = $row_rsFinancier['funder'];

            $history = ' <input type="hidden" name="financierId[]"  id="financierIdfinancierrow' . $rowno . '" value="' . $fndid . '"/>';

            $query_rsFunding =  $db->prepare("SELECT * FROM tbl_myprojfunding WHERE projid = :projid");
            $query_rsFunding->execute(array(":projid" => $projid));
            $row_rsFunding = $query_rsFunding->fetch();
            $totalRows_rsFunding = $query_rsFunding->rowCount();

            if ($totalRows_rsFunding > 0) {
                do {
                    $sourcat =  $row_rsFunding['sourcecategory'];
                    $funderid = $row_rsFunding['id'];
                    $projamount = $row_rsFunding['amountfunding'];
                    $financierId = $row_rsFunding['financier'];

                    $query_rsPlanFunding =  $db->prepare("SELECT SUM(amount) as amount FROM tbl_project_cost_funders_share WHERE funder =:funderid AND projid=:projid");
                    $query_rsPlanFunding->execute(array(':funderid' => $financierId, ':projid' => $projid));
                    $row_rsPlanFunding = $query_rsPlanFunding->fetch();
                    $totalRows_rsPlanFunding = $query_rsPlanFunding->rowCount();
                    $spent_plan_amount = $row_rsPlanFunding['amount'];
					
					
                    $remaining = $projamount - $spent_plan_amount;
					var_dump($spent_plan_amount);

                    if ($fndid == $financierId) {
                        $plan_ceiling = $projamount - $spent_plan_amount;
                        $plan_ceiling_value = $remaining + $amount;
                        $ceiling = $remaining + $amount;
                    } else {
                        $ceiling = $remaining;
                    }
                    //calculate amount remaining 

                    if ($ceiling > 0) {
						$query_rsFunder = $db->prepare("SELECT * FROM tbl_financiers WHERE id='$financierId'");
						$query_rsFunder->execute();
						$row_rsFunder = $query_rsFunder->fetch();
						
						$funder = $row_rsFunder['financier'];
						if ($fndid == $financierId) {
							$option .= '<option value="' . $financierId . '" selected>' . $funder . '</option>';
						} else {
							$option .= '<option value="' . $financierId . '">' . $funder . '</option>';
						}
                    }
                } while ($row_rsFunding = $query_rsFunding->fetch());
            }
			
            $tp = $rowno - 1;
            $fin .=  '
			<tr id="financierrow' . $rowno . '">
                <td>
                    ' . $tp . ' 
                </td>
                <td>
                    ' . $history . '
                    <select onchange=financeirChange("row' . $rowno . '") data-id="' . $rowno . '" name="finance[]" id="financerow' . $rowno . '" class="form-control validoutcome selectedfinance" required="required">
                        <option value="">Select Financier from list</option> 
                        ' . $option . '
                    </select>
                </td>
                <td>
                    <input type="hidden" name="hceilingval[]"  id="hceilingvalrow' . $rowno . '" value="' . $plan_ceiling_value . '" /> 
                    <input type="hidden" name="ceilingval[]"  id="ceilingvalrow' . $rowno . '" value="' . $plan_ceiling_value . '" /> 
                    <span id="currrow' .  $rowno . '"></span> 
                    <span id="financierCeilingrow' . $rowno . '" style="color:red">
                    ' .    number_format($plan_ceiling, 2) . '
                    </span>
                </td>
                <td> 
                    <input type="hidden" name="hamountfunding[]"  id="hamountfundingrow' . $rowno . '" value="' . $amount . '" />
                    <input type="number" name="amountfunding[]" onkeyup=amountfunding("row' . $rowno  . '")   onchange=amountfunding("row' . $rowno  . '")
                     id="amountfundingrow' . $rowno . '"   placeholder="Enter" 
                     value="' . $amount . '" class="form-control financierTotal" style="width:85%; float:right" required/>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_financier("financierrow' . $rowno . '")>
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </td>
            </tr>';
        }
        echo $fin;
    }

    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "add_budget_line_frm")) {
        $opid = $_POST['opid'];
        $projid = $_POST['projid'];
        $current_date = date("Y-m-d H:i:s");
        $createdby = 3;
        for ($i = 0; $i < count($opid); $i++) {
            $outputid = $opid[$i];

            if (isset($_POST['taskid' . $outputid]) && !empty($_POST['taskid' . $outputid])) {
                $task = $_POST['taskid' . $outputid];
                for ($j = 0; $j < count($task); $j++) {
                    $taskid = $task[$j];
                    $optkid = $outputid . $taskid;
                    $dunitcost = $dunit = $dtotalunits = $table_id1 = $description = "";
                    $type = 1;

                    if (isset($_POST['dtotalunits' . $optkid])) {
                        $dtotalunits = $_POST['dtotalunits' . $optkid];
                    }

                    if (isset($_POST['dunitcost' . $optkid])) {
                        $dunitcost = $_POST['dunitcost' . $optkid];
                    }

                    if (isset($_POST['dunit' . $optkid])) {
                        $dunit = $_POST['dunit' . $optkid];
                    }

                    if (isset($_POST['ddescription' . $optkid])) {
                        $description = $_POST['ddescription' . $optkid];
                    }

                    if (isset($_POST['rmkid1' . $outputid . $taskid])) {
                        $table_id1 = $_POST['rmkid1' . $outputid . $taskid];
                    }

                    if (!empty($dunitcost)  && !empty($dunit)  && !empty($dtotalunits) && !empty($table_id1)) {
                        for ($pt = 0; $pt < count($dunitcost); $pt++) {
                            $dunitcost_1 = $dunitcost[$pt];
                            $insertSQL = $db->prepare("UPDATE tbl_project_direct_cost_plan  SET   tasks=:tasks, description=:description, unit=:unit, unit_cost=:unit_cost, units_no=:units_no, cost_type=:cost_type WHERE id=:id");
                            $result  = $insertSQL->execute(array(
                                ':tasks' => $taskid, ':description' => $description[$pt],  ':unit' => $dunit[$pt], ':unit_cost' => $dunitcost[$pt], ':units_no' => $dtotalunits[$pt], ':cost_type' => $type, ':id' => $table_id1[$pt]
                            ));
                        }
                    }
                }
            }

            if (isset($_POST['rmkid2' . $outputid])) {
                $personel = $unitcost = $unit = $noofunits = $table_id2 = "";
                $table_id2 = $_POST['rmkid2' . $outputid];
                if (isset($_POST['pnoofunits' . $outputid])) {
                    $pnoofunits = $_POST['pnoofunits' . $outputid];
                }
                if (isset($_POST['punitcost' . $outputid])) {
                    $punitcost = $_POST['punitcost' . $outputid];
                }

                if (isset($_POST['punit' . $outputid])) {
                    $punit = $_POST['punit' . $outputid];
                }

                if (isset($_POST['personel' . $outputid])) {
                    $personel = $_POST['personel' . $outputid];
                }

                if (!empty($personel) && !empty($punitcost) && !empty($punit)  && !empty($pnoofunits) &&  !empty($table_id2)) {

                    $type = 2;
                    for ($q = 0; $q < count($personel); $q++) {
                        $insertSQL = $db->prepare("UPDATE tbl_project_direct_cost_plan SET personnel=:personel, unit=:unit, unit_cost=:unit_cost, units_no=:units_no, cost_type=:cost_type WHERE id=:id ");
                        $result  = $insertSQL->execute(array(
                            ':personel' => $personel[$q], ':unit' => $punit[$q], ':unit_cost' => $punitcost[$q], ':units_no' => $pnoofunits[$q], ':cost_type' => $type, ':id' => $table_id2[$q]
                        ));
                    }
                } else {
                    echo "does not submit";
                }
            }

            if (isset($_POST["budgetlineid" . $outputid])) {
                $budgetlineid  =  $_POST["budgetlineid" . $outputid];

                for ($p = 0; $p < (count($budgetlineid)); $p++) {
                    $bopid = $budgetlineid[$p] . $outputid;
                    $type = 3;
                    $description = $unitcost = $unit = $noofunits = $table_id = "";

                    if (isset($_POST['budget_linenoofunits' . $bopid])) {
                        $noofunits = $_POST['budget_linenoofunits' . $bopid];
                    }

                    if (isset($_POST['budget_lineunitcost' . $bopid])) {
                        $unitcost = $_POST['budget_lineunitcost' . $bopid];
                    }

                    if (isset($_POST['budget_lineunit' . $bopid])) {
                        $unit = $_POST['budget_lineunit' . $bopid];
                    }

                    if (isset($_POST['budget_line_description' . $bopid])) {
                        $description = $_POST['budget_line_description' . $bopid];
                    }

                    if (isset($_POST['rmkid3' . $bopid])) {
                        $table_id = $_POST['rmkid3' . $bopid];
                    }

                    if (!empty($description) && !empty($unitcost) && !empty($unit) && !empty($noofunits)) {
                        for ($t = 0; $t < count($description); $t++) {
                            $insertSQL = $db->prepare("UPDATE tbl_project_direct_cost_plan SET other_plan_id=:other_plan_id,  description=:description, unit=:unit, unit_cost=:unit_cost, units_no=:units_no,cost_type=:cost_type WHERE id=:plan_id");
                            $result  = $insertSQL->execute(array(':other_plan_id' => $budgetlineid[$p], ':description' => $description[$t], ':unit' => $unit[$t], ':unit_cost' => $unitcost[$t], ':units_no' => $noofunits[$t], ":cost_type" => $type, ':plan_id' => $table_id[$t]));
                        }
                    }
                }
            }
        }
 
        $implimentation_method = $_POST['implimentation_type'];
        $projstage = ($implimentation_method == '1') ? 7 : 6;
        $url = ($implimentation_method == '1') ? 1 : 2;
        $insertSQL = $db->prepare("UPDATE tbl_projects SET  projstage = :projstage WHERE  projid = :projid");
        $results  = $insertSQL->execute(array(":projstage" => $projstage, ":projid" => $projid));
        if ($results) {
            echo json_encode(array('url'=> $url, 'msg' => true));
        }else{
            echo json_encode(array('url'=> $url, 'msg' => true));
        }
    }

    if (isset($_POST['getdetails'])) {
        $projid = $_POST['projid'];
        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted=:del and projplanstatus=:planstatus and projid=:projid");
        $query_rsProjects->execute(array(":del" => '0', ":planstatus" => 1, ":projid" => $projid));
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

        // query the 
        $query_rsProjFinancier =  $db->prepare("SELECT * FROM tbl_myprojfunding WHERE projid = :projid ORDER BY amountfunding desc");
        $query_rsProjFinancier->execute(array(":projid" => $projid));
        $row_rsProjFinancier = $query_rsProjFinancier->fetch();
        $totalRows_rsProjFinancier = $query_rsProjFinancier->rowCount();
    }
} catch (PDOException $ex) {
    // $result = flashMessage("An error occurred: " .$ex->getMessage());
    echo $ex->getMessage();
}
