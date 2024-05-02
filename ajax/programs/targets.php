<?php
try {
    include '../controller.php';
    if (isset($_POST['create_independent_qtargets_div'])) {
        $progid = $_POST['progid'];

        $query_program = $db->prepare("SELECT * FROM tbl_programs WHERE progid ='$progid'");
        $query_program->execute();
        $row_program = $query_program->fetch();
        $optable  = '';

        if ($row_program) {
            $progname = $row_program["progname"];
            $duration = $row_program["years"];
            $start_year = $row_program["syear"];
            $month =  date('m');
            if ($month >= 7 && $month <= 12) {
                $currentYear = date('Y') + 1;
            } else {
                $currentYear = date('Y');
            }

            $thead_1 = $thead_2 = $thead_3 = '';
            $table_body = "";
            $program_start_year  = $start_year;
            for ($j = 0; $j < $duration; $j++) {
                $program_end_year  = $program_start_year + 1;
                $thead_1  .= '<th colspan="4" class="text-center">' . $program_start_year . '/' . $program_end_year . '</th> <input type="hidden" name="progyear[]" value="' . $program_start_year . '" />';
                $thead_2 .= '<th colspan="4" class="text-center">Target</th>';
                $thead_3 .= '<th class="text-center">Q1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th class="text-center">Q2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th class="text-center">Q3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th class="text-center">Q4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>';
                $program_start_year++;
            }

            $query_outputdetails = $db->prepare("SELECT * FROM tbl_progdetails d inner join tbl_indicator i on i.indid=d.indicator WHERE d.progid =:progid GROUP BY d.indicator");
            $query_outputdetails->execute(array(":progid" => $progid));
            $rows_outputdetails = $query_outputdetails->rowCount();
            if ($rows_outputdetails > 0) {
                $sn = 0;
                while ($row_outputdetails = $query_outputdetails->fetch()) {
                    $sn++;
                    $outputid = $row_outputdetails["id"];
                    $output = $row_outputdetails["indicator_name"];
                    $indicatorid = $row_outputdetails["indicator"];
                    $unitid = $row_outputdetails["indicator_unit"];
                    $target = $row_outputdetails["target"];

                    $query_indunit =  $db->prepare("SELECT * FROM tbl_measurement_units WHERE id ='$unitid'");
                    $query_indunit->execute();
                    $rows_indunit = $query_indunit->fetch();
                    $unit = $rows_indunit['unit'];

                    $program_start_year = $start_year;

                    $table_body  .= '
                    <tr>
                        <input type="hidden" name="indid[]" value="' . $indicatorid . '">
                        <input type="hidden" name="opid[]" value="' . $outputid . '"></td>
                        <td>' . $output . ' (' . $program_start_year . ') </td>';

                    for ($j = 0; $j < $duration; $j++) {
                        $program_end_year  = $program_start_year + 1;
                        $query_targetdetails = $db->prepare("SELECT * FROM tbl_independent_programs_quarterly_targets WHERE progid =:progid AND year=:year AND opid=:outputid");
                        $query_targetdetails->execute(array(":progid" => $progid, ":year" => $program_start_year, ":outputid" => $outputid));
                        $row_targetdetails = $query_targetdetails->fetch();

                        $output = $row_outputdetails["output"];
                        $indicatorid = $row_outputdetails["indicator"];
                        $indicator = $row_outputdetails['indicator_name'];
                        $pbbid = $row_targetdetails ? $row_targetdetails['id'] : "";
                        $targetQ1 = $row_targetdetails ? $row_targetdetails['Q1'] : '';
                        $targetQ2 = $row_targetdetails ? $row_targetdetails['Q2'] : '';
                        $targetQ3 = $row_targetdetails ? $row_targetdetails['Q3'] : '';
                        $targetQ4 = $row_targetdetails ? $row_targetdetails['Q4'] : '';

                        $table_body  .= '
                        <input type="hidden" name="program_start_year' . $outputid . '[]" value="' . $program_start_year . '">
                        <td><input type="number" min="0" id="optarget" value="' . $targetQ1 . '" class="form-control" name="optargetq1' . $outputid . '[]" size="40">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td><input type="number" min="0" id="optarget" value="' . $targetQ2 . '" class="form-control" name="optargetq2' . $outputid . '[]" size="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td><input type="number" min="0" id="opbudget" value="' . $targetQ3 . '" class="form-control" name="optargetq3' . $outputid . '[]" size="30">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td><input type="number" min="0" id="opbudget" value="' . $targetQ4 . '" class="form-control" name="optargetq4' . $outputid . '[]" size="100">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
                        $program_start_year++;
                    }

                    $table_body  .= '
                    </tr>';
                }
            }

            $optable  = '
					<div class="card clearfix">
						<div class="header">
							<div class="col-md-12" style="margin-top:5px; margin-bottom:5px">
								<h5>
									<strong> Program: ' . $progname . '</strong>
									<input type="hidden" name="progid" value="' . $progid . '">
								</h5>
							</div>
						</div>
						<div class="body">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
								<table class="table table-bordered table-striped table-hover" style="width:100%">
									<thead>
                                        <tr>
                                            <th rowspan="3" width="20%">Output &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            ' . $thead_1 . '
                                        </tr>
                                        <tr>
                                        ' . $thead_2 . '
                                        </tr>
                                        <tr>
                                        ' . $thead_3 . '
                                        </tr>
									</thead>
									<tbody>
                                    ' . $table_body . '
                                    </tbody>
                                </table>
                            </div>
						</div>
					</div>';
        }

        echo $optable;
    }

    if (isset($_POST['indepedentProgramsquarterlytargets'])) {
        $progid = $_POST['progid'];
        $createdby = $_POST['user_name'];
        $datecreated = date("Y-m-d");
        $result1 = array();

        $deleteQuery = $db->prepare("DELETE FROM `tbl_independent_programs_quarterly_targets` WHERE progid=:progid");
        $results = $deleteQuery->execute(array(':progid' => $progid));
        for ($i = 0; $i < count($_POST['opid']); $i++) {
            $opid = $_POST['opid'][$i];
            $indid = $_POST['indid'][$i];
            $count_targets =  isset($_POST['optargetq1' . $opid]) ? count($_POST['optargetq1' . $opid]) : 0;
            for ($q = 0; $q < $count_targets; $q++) {
                $finyear = $_POST['program_start_year' . $opid][$q];
                $target1 = $_POST['optargetq1' . $opid][$q];
                $target2 = $_POST['optargetq2' . $opid][$q];
                $target3 = $_POST['optargetq3' . $opid][$q];
                $target4 = $_POST['optargetq4' . $opid][$q];
                $sql = $db->prepare("INSERT INTO `tbl_independent_programs_quarterly_targets`(progid, opid, indid, year, Q1, Q2, Q3, Q4, created_by, date_created) VALUES(:progid, :opid, :indid, :finyear, :target1, :target2, :target3, :target4, :createdby, :datecreated)");
                $result1[]  = $sql->execute(array(":progid" => $progid, ":opid" => $opid, ":indid" => $indid, ":finyear" => $finyear, ":target1" => $target1, ":target2" => $target2, ":target3" => $target3, ":target4" => $target4, ":createdby" => $createdby, ":datecreated" => $datecreated));
            }
        }

        $success = false;
        $messages = "Error while adding quarterly targets!!";
        if (!in_array(false, $result1)) {
            $success = true;
            $messages = "Quarterly targets successfully added";
        }
        echo json_encode(array("success" => $success, "messages" => $messages));
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
