<?php
try {
    require('includes/head.php');
    if ($permission && isset($_GET['output_id']) && isset($_GET['site_id'])) {
        $hash_output_id = $_GET['output_id'];
        $hash_site = $_GET['site_id'];
        $decode_output_id = base64_decode($hash_output_id);
        $output_id_array = explode("encodeprocprj", $decode_output_id);
        $output_id = $output_id_array[1];

        $decode_site_id = base64_decode($hash_site);
        $site_id_array = explode("encodeprocprj", $decode_site_id);
        $site_id = $site_id_array[1];

        $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator  WHERE id = :output_id ");
        $query_Output->execute(array(":output_id" => $output_id));
        $row_rsOutput = $query_Output->fetch();
        $total_Output = $query_Output->rowCount();

        if ($total_Output > 0) {
            $projid =  $row_rsOutput['projid'];
            $indicator_id = $row_rsOutput['indicator'];
            $output_name = $row_rsOutput['indicator_name'];
            $mapping_type = $row_rsOutput['indicator_mapping_type'];


            $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.deleted='0' and p.projid=:projid AND projstage=:projstage");
            $query_rsProjects->execute(array(":projid" => $projid, ":projstage" => $workflow_stage));
            $row_rsProjects = $query_rsProjects->fetch();
            $totalRows_rsProjects = $query_rsProjects->rowCount();

            if ($totalRows_rsProjects > 0) {
                $projname = $row_rsProjects['projname'];
                $projcode = $row_rsProjects['projcode'];
                $projcost = $row_rsProjects['direct_cost'];
                $proj_substage = $row_rsProjects['proj_substage'];
                $approval_stage = ($proj_substage  >= 2) ? true : false;

                $query_Sites = $db->prepare("SELECT * FROM tbl_project_sites WHERE site_id=:site_id");
                $query_Sites->execute(array(":site_id" => $site_id));
                $rows_sites = $query_Sites->rowCount();
                $row_Sites = $query_Sites->fetch();
                $site = $rows_sites > 0 ?  $row_Sites['site'] : "N/A";


                if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "add_budget_line_frm")) {
                    $user_name = $_POST['user_name'];
                    $costlineids = $_POST['costlineid'];
                    $datecreated = date("Y-m-d");
                    $output_id = $_POST['output_id'];
                    $site_id = $_POST['site_id'];
                    $total_costlineid = count($costlineids);


                    $deleteQuery = $db->prepare("DELETE FROM tbl_project_tender_details WHERE outputid=:output_id AND site_id=:site_id");
                    $results = $deleteQuery->execute(array(":output_id" => $output_id, "site_id" => $site_id));
                    $result = [];

                    for ($i = 0; $i < $total_costlineid; $i++) {
                        $costlineid = $_POST['costlineid'][$i];
                        $unit_cost = $_POST['unit_cost'][$i];
                        $query_rsDirect_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE id =:costlineid AND cost_type=1 ");
                        $query_rsDirect_cost_plan->execute(array(":costlineid" => $costlineid));
                        $totalRows_rsDirect_cost_plan = $query_rsDirect_cost_plan->rowCount();
                        $row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch();
                        if ($totalRows_rsDirect_cost_plan > 0) {
                            $taskid = $row_rsDirect_cost_plan['tasks'];
                            $unit = $row_rsDirect_cost_plan['unit'];
                            $description = $row_rsDirect_cost_plan['description'];
                            $subtask_id = $row_rsDirect_cost_plan['subtask_id'];
                            $units_no = $row_rsDirect_cost_plan['units_no'];

                            $insertSQL = $db->prepare("INSERT INTO  tbl_project_tender_details(projid,outputid,site_id,costlineid,tasks,subtask_id,description,unit,unit_cost,units_no,created_by,date_created)  VALUES(:projid, :output_id, :site_id, :costlineid, :tasks, :subtask_id,:description, :unit, :unit_cost, :units_no, :created_by, :date_created)");
                            $result[]  = $insertSQL->execute(array(":projid" => $projid, ":output_id" => $output_id, ":site_id" => $site_id, ':costlineid' => $costlineid, ':tasks' => $taskid, ':subtask_id' => $subtask_id, ':description' => $description, ':unit' => $unit, ':unit_cost' => $unit_cost, ':units_no' => $units_no, ":created_by" => $user_name, ":date_created" => $datecreated));
                        }
                    }
                    $hashproc = base64_encode("encodeprocprj{$projid}");
                    $msg = 'Records created successfully added.';
                    $results = "<script type=\"text/javascript\">
                swal({
                title: \"Success!\",
                text: \" $msg\",
                type: 'Success',
                timer: 2000,
                icon:'success',
                showConfirmButton: false });
                setTimeout(function(){
                        window.location.href = 'add-procurement-details?prj=$hashproc';
                    }, 2000);
            </script>";
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
                                        <a type="button" id="outputItemModalBtnrow" onclick="history.back()" class="btn btn-warning ">
                                            Go Back
                                        </a>
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
                                        <!-- ============================================================== -->
                                        <!-- Start Page Content -->
                                        <!-- ============================================================== -->
                                        <div class="body">
                                            <form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                                                <?= csrf_token_html(); ?>
                                                <ul class="list-group">
                                                    <li class="list-group-item list-group-item list-group-item-action active">Project: <?= $projname ?></li>
                                                    <li class="list-group-item"><strong>Code: </strong> <?= $projcode ?> </li>
                                                    <li class="list-group-item"><strong>Site: </strong> <?= $site ?> </li>
                                                    <li class="list-group-item"><strong>Output: </strong> <?= $output_name ?> </li>
                                                </ul>
                                                <fieldset class="scheduler-border" style="border-radius:3px">
                                                    <legend class="scheduler-border" style=" border-radius:3px"><i class="fa fa-cogs" style="color:#F44336" aria-hidden="true"></i> Procurement Plan </legend>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered" id="funding_table">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width:2%"># </th>
                                                                    <th style="width:30%">Description </th>
                                                                    <th style="width:15%">Unit</th>
                                                                    <th style="width:15%">No. of Units</th>
                                                                    <th style="width:18%">Unit Cost (Ksh)</th>
                                                                    <th style="width:20%">Total Cost (Ksh)</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $query_rsDirect_cost_plan =  $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid =:projid AND outputid=:output_id AND site_id=:site_id AND cost_type=1 ");
                                                                $query_rsDirect_cost_plan->execute(array(":projid" => $projid, ":site_id" => $site_id, ":output_id" => $output_id));
                                                                $totalRows_rsDirect_cost_plan = $query_rsDirect_cost_plan->rowCount();
                                                                $contribution_amount = $subtotal_amount = 0;
                                                                if ($totalRows_rsDirect_cost_plan > 0) {
                                                                    $plan_counter = 0;
                                                                    while ($row_rsDirect_cost_plan = $query_rsDirect_cost_plan->fetch()) {
                                                                        $plan_counter++;
                                                                        $tkid = 0;
                                                                        $rmkid = $row_rsDirect_cost_plan['id'];
                                                                        $tkid = $row_rsDirect_cost_plan['tasks'];
                                                                        $unit = $row_rsDirect_cost_plan['unit'];
                                                                        $unit_cost = $row_rsDirect_cost_plan['unit_cost'];
                                                                        $units_no = $row_rsDirect_cost_plan['units_no'];
                                                                        $description = $row_rsDirect_cost_plan['description'];
                                                                        $subtask_id = $row_rsDirect_cost_plan['subtask_id'];

                                                                        $query_rsProcurement =  $db->prepare("SELECT * FROM tbl_project_tender_details WHERE outputid=:output_id AND  site_id=:site_id AND costlineid=:costlineid");
                                                                        $query_rsProcurement->execute(array(":output_id" => $output_id, ":site_id" => $site_id, ":costlineid" => $rmkid));
                                                                        $row_rsProcurement = $query_rsProcurement->fetch();
                                                                        $totalRows_rsProcurement = $query_rsProcurement->rowCount();

                                                                        $procurement_cost = '';
                                                                        $procurement_units  = 0;
                                                                        $procurement_total_cost = 0;
                                                                        if ($totalRows_rsProcurement > 0) {
                                                                            $procurement_cost = $row_rsProcurement['unit_cost'];
                                                                            $procurement_units = $row_rsProcurement['units_no'];
                                                                            $procurement_total_cost = $procurement_cost * $procurement_units;
                                                                            $subtotal_amount += $procurement_total_cost;
                                                                        }


                                                                        $total_cost = $unit_cost * $units_no;
                                                                        $contribution_amount += $total_cost;
                                                                        $taskid = $tkid . $rmkid;

                                                                        $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id = :unit_id");
                                                                        $query_rsIndUnit->execute(array(":unit_id" => $unit));
                                                                        $row_rsIndUnit = $query_rsIndUnit->fetch();
                                                                        $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                                                                        $unit_of_measure = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : '';

                                                                        $data_details =
                                                                            "{
                                                                    taskid:{$taskid},
                                                                    output_id:{$output_id},
                                                                    task_units:{$units_no},
                                                                    task_cost:{$unit_cost},
                                                                }";
                                                                ?>
                                                                        <input type="hidden" name="costlineid[]" value="<?= $rmkid ?>">
                                                                        <tr>
                                                                            <td>
                                                                                <?= $plan_counter ?>
                                                                            </td>
                                                                            <td><?= $description ?> </td>
                                                                            <td><?= $unit_of_measure ?> </td>
                                                                            <td>
                                                                                <?= number_format($units_no, 2) ?>
                                                                                <input type="hidden" name="units_no[]" value="<?= $units_no ?>" id="total_units<?= $taskid ?>">
                                                                                <input type="hidden" name="subtotal[]" value="<?= $units_no ?>" id="subtotal<?= $taskid ?>" class="subtotal" value="<?= $procurement_total_cost ?>">
                                                                            </td>
                                                                            <td>
                                                                                <input type="number" min="0" name="unit_cost[]" value="<?= $procurement_cost ?>" id="unit_cost<?= $taskid ?>" onkeyup="cost_change(<?= $data_details ?>)" onchange="cost_change(<?= $data_details ?>)" class="form-control" placeholder="<?= number_format($unit_cost, 2) ?>" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" name="dtotalcost[]" value="<?= number_format($procurement_total_cost, 2) ?>" id="total_cost<?= $taskid ?>" class="form-control " placeholder="Total Cost" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required disabled>
                                                                            </td>
                                                                        </tr>
                                                                <?php
                                                                    }
                                                                }
                                                                $percentage = ($subtotal_amount / $projcost) * 100;
                                                                ?>
                                                            <tfoot>
                                                                <tr>
                                                                    <td colspan="3"></td>
                                                                    <td colspan="2"><strong>Sub Total</strong></td>
                                                                    <td colspan="1">
                                                                        <input type="text" name="d_sub_total_amount" value="<?= $subtotal_amount ?>" id="d_sub_total_amount" class="form-control" placeholder="Total Sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="3"></td>
                                                                    <td colspan="2"> <strong>% Sub Total</strong></td>
                                                                    <td colspan="1">
                                                                        <input type="text" name="d_sub_total_percentage" value="<?= $percentage ?> %" id="d_sub_total_percentage" class="form-control" placeholder="% sub-total" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="3"></td>
                                                                    <td colspan="2"> <strong>Contract Cost Estimate</strong></td>
                                                                    <td colspan="1">
                                                                        <input type="text" name="outputBal" id="output_cost_bal" class="form-control output_cost_bal" value="<?= number_format(($contribution_amount), 2) ?>" placeholder="" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled>
                                                                    </td>
                                                                </tr>
                                                            </tfoot>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </fieldset>
                                                <div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
                                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-center">
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-center">
                                                        <input type="hidden" name="MM_insert" id="MM_insert" value="add_budget_line_frm">
                                                        <input type="hidden" name="output_id" id="output_id" value="<?= $output_id ?>">
                                                        <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                                        <input type="hidden" name="site_id" id="site_id" value="<?= $site_id ?>">
                                                        <input type="hidden" name="project_cost" id="project_cost" value="<?= $projcost ?>">
                                                        <input type="hidden" name="user_name" value="<?= $user_name ?>">
                                                        <?php if (!$approval_stage) { ?>
                                                            <input name="save_contract_details" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                                                        <?php } ?>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-center">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
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
        } else {
            $results =  restriction();
            echo $results;
        }
    } else {
        $results =  restriction();
        echo $results;
    }
} catch (PDOException $ex) {
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
require('includes/footer.php');
?>


<script>
    //function to put commas to the data
    function commaSeparateNumber(val) {
        while (/(\d+)(\d{3})/.test(val.toString())) {
            val = val.toString().replace(/(\d+)(\d{3})/, "$1" + "," + "$2");
        }
        return val;
    }

    function cost_change(details) {
        var taskid = details.taskid;
        var task_cost = details.task_cost;
        var new_unit_cost = $(`#unit_cost${taskid}`).val();
        var new_units = $(`#total_units${taskid}`).val();

        // if (task_cost != "" && parseFloat(task_cost) > 0) {
        if (new_unit_cost != "" && parseFloat(new_unit_cost) > 0) {
            new_units = parseFloat(new_units);
            total_cost = new_units >= 0 ? new_unit_cost * new_units : 0;
            $(`#total_cost${taskid}`).val(commaSeparateNumber(total_cost));
            $(`#subtotal${taskid}`).val(total_cost);
        } else {
            $(`#total_cost${taskid}`).val(0);
            $(`#subtotal${taskid}`).val(0);
        }
        // } else {
        //     $(`#total_cost${taskid}`).val(0);
        // }
        calculate_total_cost();
    }

    function calculate_total_cost() {
        var projcost = $("#project_cost").val();
        var project_cost = projcost != "" ? parseFloat(projcost) : 0;
        var subtotal = 0;
        if (project_cost > 0) {
            $(`.subtotal`).each(function() {
                subtotal += ($(this).val() != "") ? parseFloat($(this).val()) : 0;
            });
        }

        var sub_total_percentage = ((subtotal / project_cost) * 100);
        $("#d_sub_total_amount").val(commaSeparateNumber(subtotal));
        $("#d_sub_total_percentage").val(commaSeparateNumber(sub_total_percentage));
    }
</script>