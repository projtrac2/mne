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
        $projcommunity = $projlga = $projlocation = $projstate = "";
        $projcategory = $projstatus = "";
        $progname =  $program_start_date = $program_end_date =  $program_duration = $projectendYearDate = "";

        $projcode = $projname = "";
        if ($totalRows_rsProjects > 0) {
            $progid = $row_rsProgjects['progid'];
            $projcode = $row_rsProgjects['projcode'];
            $projname = $row_rsProgjects['projname'];
        }

        function get_outputs()
        {
            global $db, $projid;
            $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid = :projid");
            $query_Output->execute(array(":projid" => $projid));
            $total_Output = $query_Output->rowCount();
            $outputs = '';
            if ($total_Output > 0) {
                while ($row_rsOutput = $query_Output->fetch()) {
                    $output_id = $row_rsOutput['id'];
                    $output = $row_rsOutput['indicator_name'];
                    $outputs .= '<option value="' . $output_id . '">' . $output . '</option>';
                }
            }
            return $outputs;
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
                    <?php echo $pageTitle ?>
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
                                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th width="85%">Milestone</th>
                                                    <th width="10%">
                                                        <button type="button" name="addplus" id="addplus_outputBTnm" data-toggle="modal" data-target="#addplus_output" class="btn btn-success btn-sm">
                                                            <span class="glyphicon glyphicon-plus">
                                                            </span>
                                                        </button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $query_rsMilestone =  $db->prepare("SELECT * FROM tbl_project_milestone WHERE projid =:projid ORDER BY id");
                                                $query_rsMilestone->execute(array(":projid" => $projid));
                                                $totalRows_rsMilestone = $query_rsMilestone->rowCount();
                                                if ($totalRows_rsMilestone > 0) {
                                                    $rowno = 0;
                                                    while ($row_rsMilestone = $query_rsMilestone->fetch()) {
                                                        $rowno++;
                                                        $milestone_id = $row_rsMilestone['id'];
                                                        $milestone = $row_rsMilestone['milestone'];

                                                        $details = "{
                                                            milestone:'$milestone',
                                                            milestone_id:$milestone_id
                                                        }";
                                                ?>
                                                        <tr>
                                                            <td><?= $rowno ?></td>
                                                            <td><?= $milestone ?></td>
                                                            <td>
                                                                <button type="button" data-toggle="modal" data-target="#addplus_output" class="btn btn-warning btn-sm" onclick="get_milestone_edit_details(<?= $details ?>)">
                                                                    <span class="glyphicon glyphicon-pencil"></span>
                                                                </button>
                                                                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick="delete_milestone(<?= $details ?>)">
                                                                    <span class="glyphicon glyphicon-trash"></span>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }
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
    <div class="modal fade" id="addplus_output" tabindex="-1" role="dialog">
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
                                        <form class="form-horizontal" id="output_form" action="" method="POST">
                                            <br />
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label for="milestone_name" class="control-label">Milestone *:</label>
                                                <div class="form-input">
                                                    <input type="text" name="milestone_name" id="milestone_name" placeholder="Enter" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label class="control-label">Select Outputs</label>
                                                    <div class="table-responsive" id="output_details">
                                                        <table class="table table-bordered table-striped table-hover" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Output</th>
                                                                    <th>Target</th>
                                                                    <th width="5%">
                                                                        <button type="button" name="addplus" id="addplus_milestone" onclick="add_row_output();" class="btn btn-success btn-sm">
                                                                            <span class="glyphicon glyphicon-plus">
                                                                            </span>
                                                                        </button>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="mile_table_body">
                                                                <tr></tr>
                                                                <tr id="hideinfo">
                                                                    <td colspan="5">
                                                                        Add Milestones!!
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                    <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                                    <input type="hidden" name="id" id="id" value="">
                                                    <input type="hidden" name="store_output_data" id="store_output_data" value="new">
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
    const details = {
        outputs: '<?= get_outputs() ?>'
    }
</script>
<script src="assets/js/activities/milestone.js"></script>