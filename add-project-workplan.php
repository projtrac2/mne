<?php
require('includes/head.php'); 
if ($permission) { 
    try {
        $results = "";
        $editFormAction = $_SERVER['PHP_SELF'];
		$today = date("Y-m-d");
        if (isset($_SERVER['QUERY_STRING'])) {
            $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
        }
        if (isset($_GET['projid'])) {
            $projid = $_GET['projid'];

            $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid=:projid");
            $query_rsProjects->execute(array(":projid" => $projid));
            $row_rsProgjects = $query_rsProjects->fetch();
            $totalRows_rsProjects = $query_rsProjects->rowCount();

            $progid = $row_rsProgjects['progid'];
            $projcode = $row_rsProgjects['projcode'];
            $projname = $row_rsProgjects['projname'];

            $query_rsPrograms = $db->prepare("SELECT * FROM tbl_programs WHERE progid=:progid");
            $query_rsPrograms->execute(array(":progid" => $progid));
            $row_rsProggrams = $query_rsPrograms->fetch();
            $totalRows_rsPrograms = $query_rsPrograms->rowCount();
            $program_type = $row_rsProggrams ? $row_rsProggrams['program_type']: 0;
            

            $query_OutputData = $db->prepare("SELECT * FROM tbl_project_details WHERE projid = :projid ORDER BY id");
            $query_OutputData->execute(array(":projid" => $projid));
            $rows_OutpuData = $query_OutputData->rowCount();
        }

        if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addworkplan")) {
            $projstage = 7;
            //$result = true;
            $projid = $_POST['projid'];
            $opid = $_POST['opid'];
            $indicator = $_POST['indid'];
            $result = [];

            for ($i = 0; $i < count($opid); $i++) {
                $outputid = $opid[$i];
                $indid = $indicator[$i];
				$years = $_POST['year'.$outputid];
				for ($j = 0; $j < count($years); $j++) {
					$year = $_POST['year'.$outputid][$j];
					$quater_target1 = $_POST['quater_target1' . $outputid.$year];
					$quater_target2 = $_POST['quater_target2' . $outputid.$year];
					$quater_target3 = $_POST['quater_target3' . $outputid.$year];
					$quater_target4 = $_POST['quater_target4' . $outputid.$year];

					$insertSQL1 = $db->prepare("UPDATE tbl_project_details SET workplan_interval = :workplan_interval WHERE id=:outputid");
					$result1  = $insertSQL1->execute(array(":workplan_interval" => 4, ":outputid" => $outputid));

					$insertSQL2 = $db->prepare("INSERT INTO `tbl_workplan_targets`(projid, outputid,indid, year, Q1, Q2, Q3, Q4, created_by, date_created) VALUES(:projid,:outputid,:indid,:qyear,:q1,:q2,:q3,:q4, :user, :date)");
					$data  = $insertSQL2->execute(array(":projid" => $projid, ":outputid" => $outputid, ":indid" => $indid, ":qyear" => $year, ":q1" => $quater_target1, ":q2" => $quater_target2, ":q3" => $quater_target3, ":q4" => $quater_target4, ":user" => $user_name, ":date" => $today));

					$result[] = ($data) ? true : false;
				}
            }

			if(in_array(false, $result)){
				$type = 'error';
				$msg = 'Error occured while saving your workplan!!';
				$results = "<script type=\"text/javascript\">
					swal({
					title: \"Error!\",
					text: \" $msg \",
					type: 'Danger',
					timer: 10000,
					showConfirmButton: false });
				</script>";
			}else{
				$updateSQL1 = $db->prepare("UPDATE tbl_projects SET projstage = :projstage WHERE projid=:projid");
				$updateSQL1->execute(array(":projstage" => $projstage, ":projid" => $projid));

				$msg = 'The workplan has been successfully saved.';
				$results = "<script type=\"text/javascript\">
						swal({
						title: \"Success!\",
						text: \" $msg\",
						type: 'Success',
						timer: 2000, 
						showConfirmButton: false });
						setTimeout(function(){
								window.location.href = 'view-workplan';
							}, 2000);
					</script>";
			}
        }
    } catch (PDOException $ex) {
        $result = "An error occurred: " . $ex->getMessage();
        print($result);
    }
?>
    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <?= $icon ?>
                    <?php echo $pageTitle ?>
                    <div class="btn-group" style="float:right">
                        <div class="btn-group" style="float:right">
                        </div>
                    </div>
                </h4>
            </div>
            <div class="row clearfix">
                <div class="block-header">
                    <?= $results; ?>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <form id="addworkplan" method="POST" name="addworkplan" action="" enctype="multipart/form-data" autocomplete="off">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                        <i class="fa fa-plus-square" aria-hidden="true"></i> Project Name: <?=$projname?>
                                    </legend>

                                    <?php
                                    try {
                                        if ($rows_OutpuData > 0) {
                                            $counter = 0;
                                            while($row_OutputData =  $query_OutputData->fetch()) {
                                                $counter++;
                                                $indicator = $row_OutputData['indicator'];
                                                $t_target = $row_OutputData['total_target'];
                                                $projopid = $row_OutputData['id'];
                                                $oipid = $row_OutputData['outputid'];
                                                $duration = $row_OutputData['duration'];
                                                $fscyear = $row_OutputData['year'];

                                                $query_syear = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE id='$fscyear'");
                                                $query_syear->execute();
                                                $row_syear = $query_syear->fetch();
                                                $opsyear = $row_syear['yr'];

                                                $query_projopyears = $db->prepare("SELECT year FROM tbl_project_output_details WHERE projoutputid='$projopid'");
                                                $query_projopyears->execute();
												
												$query_rsIndicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid ='$indicator'");
												$query_rsIndicator->execute();
												$row_rsIndicator = $query_rsIndicator->fetch();
												$indname = $row_rsIndicator['indicator_name'];
												$indicator_unit = $row_rsIndicator['indicator_unit'];

												// get unit 
												$query_Indicator = $db->prepare("SELECT * FROM tbl_measurement_units  WHERE id ='$indicator_unit' ");
												$query_Indicator->execute();
												$row = $query_Indicator->fetch();
												$unit = $row['unit'];
												?>

                                                <fieldset class="scheduler-border">
                                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Output <?= $counter ?> : <?= $indname ?></legend>
                                                    <div class="col-md-6">
                                                        <label class="control-label"> Indicator:</label>
                                                        <div class="form-line">
                                                            <input type="text" name="duration" id="prog" value="<?php echo $unit . " of " . $indname ?>" class="form-control" style="font-weight:bold; border:#CCC thin solid; border-radius: 5px" readonly>
                                                        </div>
                                                    </div> 
                                                    <div class="col-md-3">
                                                        <label class="control-label"> Output Start Year:</label>
                                                        <div class="form-line">
                                                            <input type="text" name="duration" id="prog" value="<?php echo $opsyear ?>" class="form-control" style="border:#CCC thin solid; border-radius: 5px" readonly>
                                                            <input type="hidden" name="opid[]" value="<?= $projopid ?>">
                                                            <input type="hidden" name="indid[]" value="<?= $indicator ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="control-label"> Output Duration (Days):</label>
                                                        <div class="form-line">
                                                            <input type="text" name="duration" id="prog" value="<?php echo $duration ?>" class="form-control" style="border:#CCC thin solid; border-radius: 5px" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div id="workplan_table<?= $projopid ?>">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped table-hover" id="targets" style="width:100%">
																	<?php
																	while($row_projopyears = $query_projopyears->fetch()){
																		$projopyear = $row_projopyears['year'];

																		$query_projopyear = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE yr='$projopyear'");
																		$query_projopyear->execute();
																		$row_projopyear = $query_projopyear->fetch();
																		$projopfnyear = $row_projopyear['year'];

																		/* $month =  date('m');
																		$financial_year = ($month >= 7 && $month <= 12) ?  date('Y') :  date('Y') - 1;
																		$end  = $financial_year + 1; */

																		// Get the output Name 
																		$query_rsProgTargets = "";
																		if ($program_type == 1) {
																			$query_rsProgTargets = $db->prepare("SELECT * FROM tbl_programs_quarterly_targets WHERE progid='$progid' AND year='$projopyear' AND indid='$indicator'");
																		} else {
																			$query_rsProgTargets = $db->prepare("SELECT * FROM tbl_independent_programs_quarterly_targets WHERE progid='$progid' AND year='$projopyear' AND indid='$indicator'");
																		} 
																		$query_rsProgTargets->execute();
																		$row_rsProgTargets = $query_rsProgTargets->fetch();
																		$total_row_rsProgTargets = $query_rsProgTargets->rowCount();

																		$query_rsProjectTargets = $db->prepare("SELECT SUM(target) as target FROM tbl_project_output_details WHERE projid='$projid' AND year='$projopyear' AND indicator='$indicator'");
																		$query_rsProjectTargets->execute();
																		$row_rsProjectTargets = $query_rsProjectTargets->fetch();
																		$total_row_rsProjectTargets = $query_rsProjectTargets->rowCount();
																		$proj_year_target = $total_row_rsProjectTargets > 0 ? $row_rsProjectTargets['target'] : 0;

																		$q1 = $q2 = $q3 = $q4 = "0";
																		if ($total_row_rsProgTargets > 0) {
																			$q1 = $row_rsProgTargets['Q1'];
																			$q2 = $row_rsProgTargets['Q2'];
																			$q3 = $row_rsProgTargets['Q3'];
																			$q4 = $row_rsProgTargets['Q4'];
																		}

																		$query_rsProjTargets = $db->prepare("SELECT * FROM tbl_workplan_targets WHERE projid='$projid' AND year='$projopyear' AND indid='$indicator'");
																		$query_rsProjTargets->execute();
																		$row_rsProjTargets = $query_rsProjTargets->fetch();
																		$total_row_rsProjTargets = $query_rsProjTargets->rowCount();
																		$proj_q1 = $proj_q2 = $proj_q3 = $proj_q4 = 0;

																		if ($total_row_rsProjTargets > 0) {
																			$proj_q1 = $row_rsProjTargets['Q1'];
																			$proj_q2 = $row_rsProjTargets['Q2'];
																			$proj_q3 = $row_rsProjTargets['Q3'];
																			$proj_q4 = $row_rsProjTargets['Q4'];
																		}

																		$q1 = $q1 - $proj_q1;
																		$q2 = $q2 - $proj_q2;
																		$q3 = $q3 - $proj_q3;
																		$q4 = $q4 - $proj_q4;
																		?>
																		<thead>
																			<tr class="bg-info">
																				<th colspan="2">Output Financial Year: <?=$projopfnyear?></th>
																				<th colspan="2"><font style="color:red">Target: <?php echo $proj_year_target . " " . $unit?></font></th>
																				<input type="hidden" id="project_output_target<?php echo $projopid.$projopyear ?>" value="<?php echo $proj_year_target . " " . $unit?>" class="form-control text-danger" style="border:#CCC thin solid; border-radius: 5px; color:red" readonly>
																			</tr>
																			<tr class="bg-default">
																				<th>Quarter 1 Target</th>
																				<th>Quarter 2 Target</th>
																				<th>Quarter 3 Target</th>
																				<th>Quarter 4 Target</th>
																			</tr>
																		</thead>
																		<tbody>
																			<tr>
																				<td>
																					<input type="number" min="0" class="form-control quarter_targets<?php echo $projopid.$projopyear ?>" name="quater_target1<?php echo $projopid.$projopyear ?>" value="" id="quarter_target1<?php echo $projopid.$projopyear ?>" onclick="calculate_targets(1,<?php echo $projopid.$projopyear ?>)" onkeyup="calculate_targets(1,<?php echo $projopid.$projopyear ?>)" required>
																				</td>
																				<td>
																					<input type="number" min="0" class="form-control quarter_targets<?php echo $projopid.$projopyear ?>" name="quater_target2<?php echo $projopid.$projopyear ?>" value="" id="quarter_target2<?php echo $projopid.$projopyear ?>" onclick="calculate_targets(2,<?php echo $projopid.$projopyear ?>)" onkeyup="calculate_targets(2,<?php echo $projopid.$projopyear ?>)" required>
																				</td>
																				<td>
																					<input type="number" min="0" class="form-control quarter_targets<?php echo $projopid.$projopyear ?>" name="quater_target3<?php echo $projopid.$projopyear ?>" value="" id="quarter_target3<?php echo $projopid.$projopyear ?>" onclick="calculate_targets(3,<?php echo $projopid.$projopyear ?>)" onkeyup="calculate_targets(3,<?php echo $projopid.$projopyear ?>)" required>
																				</td>
																				<td>
																					<input type="number" min="0" class="form-control quarter_targets<?php echo $projopid.$projopyear ?>" name="quater_target4<?php echo $projopid.$projopyear ?>" value="" id="quarter_target4<?php echo $projopid.$projopyear ?>" onclick="calculate_targets(4,<?php echo $projopid.$projopyear ?>)" onkeyup="calculate_targets(4,<?php echo $projopid.$projopyear ?>)" required>
																				</td>
																			</tr>
																		</tbody>
																		<input type="hidden" name="year<?=$projopid?>[]" value="<?= $projopyear ?>">
																	<?php
																	}
																	?>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
												<?php
                                            }
                                        }
                                    } catch (PDOException $ex) {
                                        // $result = flashMessage("An error occurred: " . $ex->getMessage());
                                        echo $result;
                                    }
                                    ?>

                                    <div class="col-md-12" style="margin-top:15px" align="center">
                                        <input type="hidden" name="projid" id="projid" class="form-control" value="<?php echo $projid ?>">
                                        <input type="hidden" name="MM_insert" value="addworkplan">
                                        <button class="btn btn-success" type="submit">Save</button>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- end body  -->
<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>
<script type="text/javascript">
    $(document).ready(function() {
        $(".account").click(function() {
            var X = $(this).attr("id");

            if (X == 1) {
                $(".submenus").hide();
                $(this).attr("id", "0");
            } else {
                $(".submenus").show();
                $(this).attr("id", "1");
            }
        });

        //Mouseup textarea false
        $(".submenus").mouseup(function() {
            return false;
        });
        $(".account").mouseup(function() {
            return false;
        });

        //Textarea without editing.
        $(document).mouseup(function() {
            $(".submenus").hide();
            $(".account").attr("id", "");
        });
    });

    function calculate_targets(id, opid) {
        var ceiling_target = $(`#project_output_target${opid}`).val(); 
        ceiling_target = parseFloat(ceiling_target);
console.log(ceiling_target);
         var target=0;
         var output_class = `.quarter_targets${opid}`;
         $(output_class).each(function () {
            if ($(this).val() != "") {
                target = target + parseFloat($(this).val());
            }
        });

        target = parseFloat(target);
        var remaining = ceiling_target - target;
        if (remaining >= 0) {
            // $(`#qtarget${id}${opid}`).html(remaining);
        } else {
            // $(`#qtarget${id}${opid}`).html(ceiling_target);
            $(`#quarter_target${id}${opid}`).val("");
            alert("The entered output target should not exceed project output annual target!");
        } 
    }
</script>