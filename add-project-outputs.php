<?php

require('includes/head.php');
if ($permission) {
    try {
        $decode_projid = (isset($_GET['projid']) && !empty($_GET["projid"])) ? base64_decode($_GET['projid']) : "";
        $projid_array = explode("projid54321", $decode_projid);
        $projid = $projid_array[1];

        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid=:projid");
        $query_rsProjects->execute(array(":projid" => $projid));
        $row_rsProgjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();

        $program_type = $planid = $progid  = $projcode = $projname = $projdescription = $projtype = $projendyear = "";
        $projbudget = $projfscyear = $projduration = $projevaluation = $projimpact  = $projimpact = "";
        $projcommunity = $projlga = $projlocation = "";
        $projcategory = $projstatus = "";
        $progname =  $program_start_date = $program_end_date =  $program_duration = $projectendYearDate = "";

        $projstartdate = $projenddate = "";
        if ($totalRows_rsProjects > 0) {
            $progid = $row_rsProgjects['progid'];
            $projcode = $row_rsProgjects['projcode'];
            $projname = $row_rsProgjects['projname'];
            $projdescription = $row_rsProgjects['projdesc'];
            $projtype = $row_rsProgjects['projtype'];
            $projbudget = $row_rsProgjects['projbudget'];
            $projfscyear = $row_rsProgjects['projfscyear'];
            $projduration = $row_rsProgjects['projduration'];
            $projevaluation = $row_rsProgjects['projevaluation'];
            $projcommunity = $row_rsProgjects['projcommunity'];
            $projlga = $row_rsProgjects['projlga'];
            $projlocation = $row_rsProgjects['projlocation'];
            $projcategory = $row_rsProgjects['projcategory'];
            $projstatus = $row_rsProgjects['projstatus'];
            $projimpact = $row_rsProgjects['projimpact'];
            $key_unique = $row_rsProgjects['key_unique'];
            $projstartdate = $row_rsProgjects['projstartdate'];
            $projenddate = $row_rsProgjects['projenddate'];
        }


        $end_year_moth = date("m", strtotime($projenddate));
        $end_year = date("Y", strtotime($projenddate));
        $end_year = $end_year_moth >= 7  ? $end_year :  $end_year -1;

        $query_rsFscYear =  $db->prepare("SELECT id, yr, year FROM tbl_fiscal_year WHERE id >=:id AND yr <=:yr ORDER BY id");
        $query_rsFscYear->execute(array(":id" => $projfscyear, ":yr" => $end_year));
        $total_rsFscYear = $query_rsFscYear->rowCount();
        $output_options ='';
        if ($total_rsFscYear > 0) {
            while ($row_rsFscYear = $query_rsFscYear->fetch()) {
                $output_options .= '<option value="' . $row_rsFscYear['id'] . '">' . $row_rsFscYear['year'] . '</option>';
            }
        }
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
?>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <?= $icon ?>
                    <?php echo $pageTitle  ?>
                    <div class="btn-group" style="float:right">
                        <a type="button" id="outputItemModalBtnrow" onclick="history.back()" class="btn btn-warning pull-right">
                            Go Back
                        </a>
                    </div>
                </h4>
            </div>
            <div class="row clearfix">
                <div class="block-header">
                    <?= $results; ?>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <ul class="list-group">
                                        <li class="list-group-item list-group-item list-group-item-action active">Project Name: <?= $projname ?> </li>
                                        <li class="list-group-item list-group-item list-group-item-action">Project Code: <?= $projcode ?> </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover" id="output_table" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th width="3%">#</th>
                                                    <th width="47%">Output</th>
                                                    <th width="30%">Indicator</th>
                                                    <th width="15%">Other Details</th>
                                                    <th width="5%">
                                                        <button type="button" name="addplus" id="addplus_output" onclick="add_row_output('<?= $projid ?>');" class="btn btn-success btn-sm">
                                                            <span class="glyphicon glyphicon-plus">
                                                            </span>
                                                        </button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="output_table_body">
                                                <tr></tr>
                                                <?php
                                                $query_rsOutput =  $db->prepare("SELECT * FROM tbl_project_details WHERE projid =:projid ORDER BY id");
                                                $query_rsOutput->execute(array(":projid" => $projid));
                                                $row_rsOutput = $query_rsOutput->fetch();
                                                $totalRows_rsOutput = $query_rsOutput->rowCount();

                                                if ($totalRows_rsOutput > 0) {
                                                    $rowno = 0;
                                                    do {
                                                        $rowno++;
                                                        $outputid = $row_rsOutput['outputid'];
                                                        $opids = $row_rsOutput['id'];
                                                        $indicatorID = $row_rsOutput['indicator'];

                                                        $query_Indicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid =:indid ");
                                                        $query_Indicator->execute(array(":indid" => $indicatorID));
                                                        $row = $query_Indicator->fetch();
                                                        $indicator_name = $row ? $row['indicator_name'] : "";
                                                ?>
                                                        <tr id="row<?= $rowno ?>">
                                                            <td>
                                                                <?= $rowno ?>
                                                            </td>
                                                            <td>
                                                                <select data-id="<?= $rowno ?>" name="output[]" class="form-control validoutcome select_output" id="outputrow<?= $rowno ?>" onchange='getIndicator("row<?= $rowno ?>")' class="form-control validoutcome selectOutcome" required="required" disabled>
                                                                    <option value="">Select Output from list</option>
                                                                    <?php
                                                                    $query_rsprogOutput =  $db->prepare("SELECT * FROM  tbl_progdetails WHERE progid=:progid GROUP BY indicator ");
                                                                    $query_rsprogOutput->execute(array(":progid" => $progid));
                                                                    $totalRows_rsprogOutput = $query_rsprogOutput->rowCount();
                                                                    if ($totalRows_rsprogOutput > 0) {
                                                                        while ($row_rsprogOutput = $query_rsprogOutput->fetch()) {
                                                                            $program_details_id = $row_rsprogOutput['id'];
                                                                            $indid = $row_rsprogOutput['indicator'];
                                                                            $query_Indicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid =:indid ");
                                                                            $query_Indicator->execute(array(":indid" => $indid));
                                                                            $row = $query_Indicator->fetch();
                                                                            $indname = $row ? $row['indicator_name'] : "";
                                                                            $selected = $program_details_id == $outputid ? "selected" : "";
                                                                    ?>
                                                                            <option value="<?= $program_details_id ?>" <?= $selected ?>><?= $indname ?></option>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="indicator[]" id="indicatorrow<?= $rowno ?>" placeholder="Enter" value=" <?= $indicator_name ?>" class="form-control" disabled />
                                                            </td>
                                                            <td>
                                                                <a type="button" data-toggle="modal" data-target="#outputItemModal" onclick='get_output_details("row<?= $rowno ?>")' id="outputItemModalBtnrow<?= $rowno ?>"> Edit Details</a>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick='delete_row_output("row<?= $rowno ?>")'>
                                                                    <span class="glyphicon glyphicon-minus"></span>
                                                                </button>
                                                            </td>
                                                            <input type="hidden" name="outputIdsTrue[]" id="output_result_id_row<?= $rowno ?>" value="<?php echo $opids ?>" />
                                                            <input type="hidden" name="output_id[]" id="output_id_row<?= $rowno ?>" value="<?= $outputid ?>" />
                                                            <input type="hidden" name="indicator[]" id="indicator_id_row<?= $rowno ?>" placeholder="Enter" class="form-control" value="<?= $indicatorID ?>" />
                                                        </tr>
                                                    <?php
                                                    } while ($row_rsOutput = $query_rsOutput->fetch());
                                                } else {
                                                    ?>
                                                    <tr id="hideinfo">
                                                        <td colspan="5" align="center">
                                                            Add Outputs!!
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <input type="hidden" name="progid" id="progid" value="<?= $progid ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <!-- Start Modal Item Edit -->
    <div class="modal fade" id="outputItemModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center" id="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="body">
                                    <div class="div-result">
                                        <form class="form-horizontal" id="add_output" action="" method="POST">
                                            <br />
                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                <label for="program_target" class="control-label">Program Target *:</label>
                                                <div class="form-input">
                                                    <input type="number" name="program_target" id="program_target" placeholder="Enter" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                <label for="program_target" class="control-label">Project Output Target *:</label>
                                                <div class="form-input">
                                                    <input type="number" min="0" name="project_target" id="project_target" onchange="validate_output_target()" onkeyup="validate_output_target()" placeholder="Enter" class="form-control" >
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" id="unit_type_div">
                                                <label class="control-label">Unit Type *:</label>
                                                <div class="form-line">
                                                    <select name="unit_type" id="unit_type" class="form-control show-tick" onchange="output_target_div([])" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true">
                                                        <option value="">.... Select Unit Type from list ....</option>
                                                        <option value="1">One-Point</option>
                                                        <option value="2">Distributed</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div id="output_distribution_div">

                                            </div>
                                            <div id="output_target_distribution_div">

                                            </div>
                                            <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
                                                <label for="" class="control-label">Output Indicator Disaggregation Required? *:</label>
                                                <div class="form-line">
                                                    <input name="dissaggregation" type="radio" value="1" onchange="show_dissaggregation(1)" id="dissaggregation1" class="with-gap radio-col-green dissaggregation" required="required" />
                                                    <label for="dissaggregation1">YES</label>
                                                    <input name="dissaggregation" type="radio" value="0" onchange="show_dissaggregation(0)" id="dissaggregation2" class="with-gap radio-col-red dissaggregation" required="required" />
                                                    <label for="dissaggregation2">NO</label>
                                                </div>
                                            </div>
                                            <div id="output_dissaggregation_div">

                                            </div>
                                            <div class="modal-footer">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                    <input type="hidden" name="key_unique" id="key_unique" value="<?= $key_unique ?>">
                                                    <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                                    <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                                    <input type="hidden" name="progid" id="program_id" value="<?= $progid ?>">
                                                    <input type="hidden" name="progid" id="program_id" value="<?= $progid ?>">
                                                    <input type="hidden" name="projduration1" id="projduration1" value="<?= $projduration ?>">
                                                    <input type="hidden" name="indicator_id" id="indicator_id" value="">
                                                    <input type="hidden" name="output_id" id="output_id" value="">
                                                    <input type="hidden" name="rowno" id="rowno" value="">
                                                    <input type="hidden" name="store_data" id="store_data" value="store_data">
                                                    <input type="hidden" name="mapping_type" id="mapping_type" value="">
                                                    <input name="submtt" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                                                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- /modal-body -->
            </div>
            <!-- /modal-content -->
        </div>
        <!-- /modal-dailog -->
    </div>
<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>


<script>
    $(document).ready(function() {
        show_dissaggregation(0);
    });

    function show_dissaggregation(outpuval) {
        console.log(outpuval);
        var html_ = '';
        if (outpuval == 1) {
            html_ = `<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label class="control-label">Output Yearly Distribution</label>
                <div class="table-responsive" id="output_details">
                    <table class="table table-bordered table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th style="width:70">Dissaggregation</th>
                                <th style="width:20">Targets</th>
                                <th width="10%">
                                    <button type="button" name="addplus" id="addplus" onclick="add_output_dissagggregation();" class="btn btn-success btn-sm">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="output_dissaggregations">
                            <tr></tr>
                            <tr>
                                <td></td>
                                <td>
                                    <input type="text" name="dissaggregation_val[]" id="diss1row0"   placeholder="Enter"  class="form-control" required/>
                                </td>
                                <td>
                                    <input type="number" name="dissaggregation_target[]" id="dissrow0"  onchange="validate_units_diss('row0')" onkeyup="validate_units_diss('row0')"  placeholder="Enter"  class="form-control diss" required/>
                                </td>
                                <td>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>`;
        }
        $("#output_dissaggregation_div").html(html_);
        number_diss_table();
    }

    // function to add financiers
    function add_output_dissagggregation() {
        $row = $("#output_dissaggregations tr").length;
        $row = $row + 1;
        var randno = Math.floor((Math.random() * 1000) + 1);
        var $rowno = $row + "" + randno;
        var locations = $(".output_target").val();
        $("#output_dissaggregations tr:last").after(`
            <tr id="testrow${$rowno}">
                <td></td>
                <td>
                    <input type="text" name="dissaggregation_val[]" id="diss1row${$rowno}"   placeholder="Enter"  class="form-control" required/>
                </td>
                <td>
                    <input type="number" name="dissaggregation_target[]" id="dissrow${$rowno}"  onchange="validate_units_diss('row${$rowno}')" onkeyup="validate_units_diss('row${$rowno}')"  placeholder="Enter"  class="form-control diss" required/>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_dissaggregation("testrow${$rowno}")>
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </td>
            </tr>`);

        number_diss_table();
    }

    // function to delete financiers
    function delete_row_dissaggregation(rowno) {
        $("#" + rowno).remove();
        number_diss_table();
        var number = $("#output_dissaggregations tr").length;
        if (number == 1) {
            $("#output_dissaggregations tr:last").after('<tr id="remove_Diss" class="text-center"><td colspan="4"> Add Dissaggregations</td></tr>');
        }
    }

    // auto numbering table rows on delete and add new for financier table
    function number_diss_table() {
        $("#output_dissaggregations tr").each(function(idx) {
            $(this)
                .children()
                .first()
                .html(idx - 1 + 1);
        });
    }

    function validate_units_diss(rowno) {
        var output_target = parseFloat($("#project_target").val());
        if (output_target > 0) {
            var units = 0;
            $(".diss").each(function() {
                var val = $(this).val();
                units += val != "" ? parseFloat(val) : 0;
            });

            if (output_target < units) {
                $(`#diss${rowno}`).val("");
                error_alert("Sorry you cannot exceed the output targets limit");
            }
        } else {
            error_alert("Ensure you have output target first");
        }
    }
</script>
<script src="assets/js/projects/output.js"></script>