<?php
$pageName = "Strategic Plans";
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
$pageTitle = "ADD OUTPUT QUARTERLY TARGETS";

if ($permission) {

    try {

        $results = "";
        $editFormAction = $_SERVER['PHP_SELF'];
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

            $query_OutputData = $db->prepare("SELECT * FROM tbl_project_details WHERE projid = :projid ORDER BY id");
            $query_OutputData->execute(array(":projid" => $projid));
            $row_OutputData =  $query_OutputData->fetch();
            $rows_OutpuData = $query_OutputData->rowCount();
        }

        if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addworkplan")) {
            $projstage = 8;
            $result = true;
            $projid = $_POST['projid'];
            $year = $_POST['year'];
            $opid = $_POST['opid'];
            $indicator = $_POST['indid'];
            $result = [];

            for ($i = 0; $i < count($opid); $i++) {
                $outputid = $opid[$i];
                $indid = $indicator[$i];
                $quater_target1 = $_POST['quater_target1' . $outputid];
                $quater_target2 = $_POST['quater_target2' . $outputid];
                $quater_target3 = $_POST['quater_target3' . $outputid];
                $quater_target4 = $_POST['quater_target4' . $outputid];

                $insertSQL1 = $db->prepare("UPDATE tbl_project_details SET workplan_interval = :workplan_interval WHERE id=:outputid");
                $result1  = $insertSQL1->execute(array(":workplan_interval" => 4, ":outputid" => $outputid));

                $insertSQL2 = $db->prepare("INSERT INTO `tbl_workplan_targets`(projid, outputid,indid, year, Q1, Q2, Q3, Q4) VALUES(:projid,:outputid,:indid,:qyear,:q1,:q2,:q3,:q4)");
                $data  = $insertSQL2->execute(array(":projid" => $projid, ":outputid" => $outputid, ":indid" => $indid, ":qyear" => $year, ":q1" => $quater_target1, ":q2" => $quater_target2, ":q3" => $quater_target3, ":q4" => $quater_target4));

                $result[] = ($data) ? true : false;
            }

            $updateSQL1 = $db->prepare("UPDATE tbl_projects SET projstage = :projstage WHERE projid=:projid");
            $result = $updateSQL1->execute(array(":projstage" => $projstage, ":projid" => $projid));

            $msg = 'The Project was successfully Updated.';
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
                    <i class="fa fa-list-alt" aria-hidden="true"></i>
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
                                        <i class="fa fa-plus-square" aria-hidden="true"></i> Add Details
                                    </legend>

                                    <?php
                                    try {
                                        if ($rows_OutpuData > 0) {
                                            $counter = 0;
                                            do {
                                                $counter++;
                                                $indicator = $row_OutputData['indicator'];
                                                $t_target = $row_OutputData['total_target'];
                                                $opid = $row_OutputData['id'];
                                                $oipid = $row_OutputData['outputid'];
                                                $duration = $row_OutputData['duration'];
                                                $fscyear = $row_OutputData['year'];

                                                $query_syear = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE id='$fscyear'");
                                                $query_syear->execute();
                                                $row_syear = $query_syear->fetch();
                                                $opsyear = $row_syear['yr'];

                                                $month =  date('m');
                                                $financial_year = ($month >= 7 && $month <= 12) ?  date('Y') :  date('Y') - 1;
                                                $end  = $financial_year + 1;

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

                                                // Get the output Name 
                                                $query_rsProgTargets = "";
                                                if ($program_type == 1) {
                                                    $query_rsProgTargets = $db->prepare("SELECT * FROM tbl_programs_quarterly_targets WHERE progid='$progid' AND year='$financial_year' AND indid='$indicator'");
                                                } else {
                                                    $query_rsProgTargets = $db->prepare("SELECT * FROM tbl_independent_programs_quarterly_targets WHERE progid='$progid' AND year='$financial_year' AND indid='$indicator'");
                                                }

                                                $query_rsProgTargets->execute();
                                                $row_rsProgTargets = $query_rsProgTargets->fetch();
                                                $total_row_rsProgTargets = $query_rsProgTargets->rowCount();


                                                $q1 = $q2 = $q3 = $q4 = "0";
                                                if ($total_row_rsProgTargets > 0) {
                                                    $q1 = $row_rsProgTargets['Q1'];
                                                    $q2 = $row_rsProgTargets['Q2'];
                                                    $q3 = $row_rsProgTargets['Q3'];
                                                    $q4 = $row_rsProgTargets['Q4'];
                                                }

                                                $query_rsProjTargets = $db->prepare("SELECT * FROM tbl_workplan_targets WHERE projid='$projid' AND year='$financial_year' AND indid='$indicator'");
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

                                                <fieldset class="scheduler-border">
                                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Output <?= $counter ?> :<?= $outputName ?></legend>
                                                    <div class="col-md-4">
                                                        <label class="control-label"> Output Start Year:</label>
                                                        <div class="form-line">
                                                            <input type="text" name="duration" id="prog" value="<?php echo $opsyear ?>" class="form-control" style="border:#CCC thin solid; border-radius: 5px" readonly>
                                                            <input type="hidden" name="opid[]" value="<?= $opid ?>">
                                                            <input type="hidden" name="indid[]" value="<?= $indicator ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="control-label"> Output Duration (Days):</label>
                                                        <div class="form-line">
                                                            <input type="text" name="duration" id="prog" value="<?php echo $duration ?>" class="form-control" style="border:#CCC thin solid; border-radius: 5px" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="control-label"> Targets Financial Year:</label>
                                                        <div class="form-line">
                                                            <input type="text" name="duration" id="prog" value="<?php echo $financial_year . "/" . $end ?>" class="form-control" style="border:#CCC thin solid; border-radius: 5px" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="control-label"> Indicator:</label>
                                                        <div class="form-line">
                                                            <input type="text" name="duration" id="prog" value="<?php echo $unit . " of " . $indname ?>" class="form-control" style="border:#CCC thin solid; border-radius: 5px" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div id="workplan_table<?= $opid ?>">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped table-hover" id="targets" style="width:100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Q1 (<span id="qtarget1<?= $opid ?>" style="color:red"><?= $q1 ?></span>) <input type="hidden" name="qtargets1" id="qceiling1<?= $opid ?>" value="<?= $q1 ?>"></th>
                                                                            <th>Q2 (<span id="qtarget2<?= $opid ?>" style="color:red"><?= $q2 ?></span>) <input type="hidden" name="qtargets2" id="qceiling2<?= $opid ?>" value="<?= $q2 ?>"></th>
                                                                            <th>Q3 (<span id="qtarget3<?= $opid ?>" style="color:red"><?= $q3 ?></span>) <input type="hidden" name="qtargets3" id="qceiling3<?= $opid ?>" value="<?= $q3 ?>"></th>
                                                                            <th>Q4 (<span id="qtarget4<?= $opid ?>" style="color:red"><?= $q4 ?></span>) <input type="hidden" name="qtargets4" id="qceiling4<?= $opid ?>" value="<?= $q4 ?>"></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>
                                                                                <input type="number" class="form-control" name="quater_target1<?= $opid ?>" value="" id="quarter_target1<?= $opid ?>" onclick="calculate_targets(1, <?= $opid ?>)" onkeyup="calculate_targets(1, <?= $opid ?>)">
                                                                            </td>
                                                                            <td>
                                                                                <input type="number" class="form-control" name="quater_target2<?= $opid ?>" value="" id="quarter_target2<?= $opid ?>" onclick="calculate_targets(2, <?= $opid ?>)" onkeyup="calculate_targets(2, <?= $opid ?>)">
                                                                            </td>
                                                                            <td>
                                                                                <input type="number" class="form-control" name="quater_target3<?= $opid ?>" value="" id="quarter_target3<?= $opid ?>" onclick="calculate_targets(3, <?= $opid ?>)" onkeyup="calculate_targets(3, <?= $opid ?>)">
                                                                            </td>
                                                                            <td>
                                                                                <input type="number" class="form-control" name="quater_target4<?= $opid ?>" value="" id="quarter_target4<?= $opid ?>" onclick="calculate_targets(4, <?= $opid ?>)" onkeyup="calculate_targets(4, <?= $opid ?>)">
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                    <?php
                                            } while ($row_OutputData = $query_OutputData->fetch());
                                        }
                                    } catch (PDOException $ex) {
                                        $result = flashMessage("An error occurred: " . $ex->getMessage());
                                        echo $result;
                                    }
                                    ?>

                                    <div class="col-md-12" style="margin-top:15px" align="center">
                                        <input type="hidden" name="year" value="<?= $financial_year ?>">
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
        var ceiling_target = $(`#qceiling${id}${opid}`).val();
        var target = $(`#quarter_target${id}${opid}`).val();
        // if (ceiling_target != "" && parseFloat(ceiling_target) > 0) {
        ceiling_target = parseFloat(ceiling_target);
        if (target != "") {
            target = parseFloat(target);
            var remaining = ceiling_target - target;
            if (remaining >= 0) {
                $(`#qtarget${id}${opid}`).html(remaining);
            } else {
                $(`#qtarget${id}${opid}`).html(ceiling_target);
                $(`#quarter_target${id}${opid}`).val("");
                alert("Output targets should not exceed program targets");
            }
        }
        // } else {
        // 	alert("Ensure that the program targets are not equal to 0");
        // 	$(`#quarter_target${id}${opid}`).val("");
        // }
    }
</script>