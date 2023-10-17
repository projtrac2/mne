<?php
require('includes/head.php');
if ($permission) {
    try {
        $parameter_id = isset($_GET['parameter_id']) ? base64_decode($_GET['parameter_id']) : "";
        $d_site_id = isset($_GET['site_id']) ? base64_decode($_GET['site_id']) : "";
        $d_state_id = isset($_GET['state_id']) ? base64_decode($_GET['state_id']) : "";
        $output_id = $design_id = $task_id = $task = $unit_of_measure = $parameter = "";
        $design = $project_name = $output_name = $mapping_type = $design_name = $output_sites = $sites = $site_name = "";
        $query_rsDesigns = $db->prepare("SELECT * FROM tbl_task_parameters p INNER JOIN tbl_task t ON t.tkid = p.task_id INNER JOIN tbl_project_output_designs o ON t.design_id = o.id WHERE p.id = :parameter_id");
        $query_rsDesigns->execute(array(":parameter_id" => $parameter_id));
        $row_rsDesigns = $query_rsDesigns->fetch();
        $totalRows_rsDesigns = $query_rsDesigns->rowCount();
        if ($totalRows_rsDesigns > 0) {
            $design_name = $row_rsDesigns['design'];
            $design_id = $row_rsDesigns['design_id'];
            $output_id = $row_rsDesigns['output_id'];
            $task_id = $row_rsDesigns['tkid'];
            $output_sites = $row_rsDesigns['sites'];
            $parameter = $row_rsDesigns['parameter'];
            $unit_of_measure = $row_rsDesigns['unit_of_measure'];
            $site_id = explode(",", $output_sites);
            $output_id = $row_rsDesigns['output_id'];
            $task = $row_rsDesigns['task'];
            $projid = $row_rsDesigns['projid'];

            $query_rsMyP =  $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' AND projid = :projid");
            $query_rsMyP->execute(array(":projid" => $projid));
            $row_rsMyP = $query_rsMyP->fetch();
            $total_row_rsMyP = $query_rsMyP->rowCount();
            $project_name = $total_row_rsMyP > 0 ? $row_rsMyP['projname'] : "";

            $query_Output = $db->prepare("SELECT * FROM tbl_project_details WHERE id = :output_id ");
            $query_Output->execute(array(":output_id" => $output_id));
            $row_rsOutput = $query_Output->fetch();
            $total_Output = $query_Output->rowCount();

            $indicator_id = $total_Output > 0 ? $row_rsOutput['indicator'] : "";
            $query_rsIndicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid=:indicator_id");
            $query_rsIndicator->execute(array(":indicator_id" => $indicator_id));
            $row_rsIndicator = $query_rsIndicator->fetch();
            $totalRows_rsIndicator = $query_rsIndicator->rowCount();
            $output_name = $totalRows_rsIndicator > 0 ? $row_rsIndicator['indicator_name'] : "";
            $mapping_type = $totalRows_rsIndicator > 0 ? $row_rsIndicator['indicator_mapping_type'] : "";
            $projid = $total_Output > 0 ?  $row_rsOutput['projid'] : "";

            if ($mapping_type == 1 || $mapping_type == 3) {
                $querysSite = $db->prepare("SELECT * FROM tbl_project_sites WHERE site_id = :id ");
                $querysSite->execute(array(":id" => $d_site_id));
                $totalsSite = $querysSite->rowCount();
                $row_rsSite = $querysSite->fetch();
                if ($total_Output > 0) {
                    $site_name = $row_rsSite['site'];
                }
            } else {
                $query_rsState = $db->prepare("SELECT * FROM tbl_output_disaggregation d INNER JOIN tbl_state s ON s.id = d.outputstate WHERE outputstate=:state_id ");
                $query_rsState->execute(array(":state_id" => $d_state_id));
                $total_rsState = $query_rsState->rowCount();
                $row_rsState = $query_rsState->fetch();
                if ($total_rsState > 0) {
                    $site_name = $row_rsState['state'];
                }
            }
        }

        function get_standards($edit_id)
        {
            global $db;
            $sql = $db->prepare("SELECT * FROM tbl_standards s INNER Join tbl_standard_categories c ON s.category_id = c.category_id  WHERE standard_id=:standard_id ORDER BY `standard_id` ASC");
            $sql->execute(array(':standard_id' => $edit_id));
            $row = $sql->fetch();
            $rows_count = $sql->rowCount();
            return ($rows_count > 0)  ? $row['standard'] : false;
        }
    } catch (PDOException $ex) {
        $results =  flashMessage("An error occurred: " . $ex->getMessage());
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
                                        <li class="list-group-item list-group-item list-group-item-action active">Project Name: <?= $project_name ?> </li>
                                        <li class="list-group-item"><strong>Output: </strong> <?= $output_name ?> </li>
                                        <li class="list-group-item"><strong>Design/Plan: </strong> <?= $design_name ?> </li>
                                        <li class="list-group-item"><strong><?= $mapping_type == 1 || $mapping_type == 3 ? "Site" : "Location" ?>: </strong> <?= $site_name ?> </li>
                                        <li class="list-group-item"><strong>Task : </strong> <?= $task ?> </li>
                                        <li class="list-group-item"><strong>Task Parameters: </strong> <?= $parameter ?> </li>
                                        <li class="list-group-item"><strong>Unit of Measure: </strong> <?= $unit_of_measure ?> </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <fieldset class="scheduler-border row setup-content" style="padding:10px">
                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Specifications</legend>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover js-basic-example " id="direct_table">
                                                    <thead>
                                                        <tr style="background-color:#0b548f; color:#FFF">
                                                            <th style="width:5%" align="center">#</th>
                                                            <th style="width:40%">Specification</th>
                                                            <th style="width:40%">Standard</th>
                                                            <th style="width:10%">Action</th>
                                                        </tr>
                                                    </thead>
                                                    </tbody>
                                                    <?php
                                                    $query_rsSpecifions = $db->prepare("SELECT * FROM tbl_project_specifications WHERE parameter_id=:parameter_id");
                                                    $query_rsSpecifions->execute(array(":parameter_id" => $parameter_id));
                                                    $totalRows_rsSpecifions = $query_rsSpecifions->rowCount();
                                                    if ($totalRows_rsSpecifions > 0) {
                                                        $counter = 0;
                                                        while ($row_rsSpecifions = $query_rsSpecifions->fetch()) {
                                                            $specification_id = $row_rsSpecifions['id'];
                                                            $specification = $row_rsSpecifions['specification'];
                                                            $standard_id = $row_rsSpecifions['standard_id'];
                                                            $standard = get_standards($standard_id);

                                                            $query_rsCompliance = $db->prepare("SELECT * FROM tbl_project_inspection_specification_compliance WHERE specification_id=:specification_id  ORDER BY id DESC LIMIT 1");
                                                            $query_rsCompliance->execute(array(":specification_id" => $specification_id));
                                                            $Rows_rsCompliance = $query_rsCompliance->fetch();
                                                            $totalRows_rsCompliance = $query_rsCompliance->rowCount();
                                                            $compliant = false;
                                                            $inspect = "false";
                                                            $compliance = 0;
                                                            if ($totalRows_rsCompliance > 0) {
                                                                $compliance = $Rows_rsCompliance['compliance'];
                                                                $compliant = $compliance == 1 ? true : false;

                                                                $query_rsIssues = $db->prepare("SELECT * FROM tbl_projissues WHERE status <> 7 AND specification_id=:specification_id");
                                                                $query_rsIssues->execute(array(":specification_id" => $specification_id));
                                                                $totalRows_rsIssues = $query_rsIssues->rowCount();
                                                                $inspect = $compliance == 2 && $totalRows_rsIssues > 0  ? 2 : 1;
                                                            }
                                                            $details = "
                                                            {  
                                                                specification_id: '$specification_id',
                                                                specification: '$specification',
                                                                non_compliant: $inspect,
                                                            }";

                                                            if (!$compliant) {
                                                                $counter++;
                                                    ?>
                                                                <tr style="background-color:#FFFFFF">
                                                                    <td align="center"><?php echo  $counter ?></td>
                                                                    <td><?= $specification ?></td>
                                                                    <td>
                                                                        <?php
                                                                        if ($standard) {
                                                                        ?>
                                                                            <a type="button" data-toggle="modal" data-target="#specification_modal" id="addFormlBtn" onclick="get_standard(<?php echo $standard_id ?>)">
                                                                                <?= $standard ?>
                                                                            </a>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <a type="button" data-toggle="modal" data-target="#addFormModal" id="addFormModalBtn" onclick="inspect(<?php echo $details ?>)">
                                                                            <i class="fa fa-comment-o"></i> <?= $inspect == 2 ? "Issues" : "Inspect"; ?>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                    <?php
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </fieldset>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>


    <!-- Start Item more -->
    <div class="modal fade" tabindex="-1" role="dialog" id="specification_modal">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info-circle"></i> Standards</h4>
                </div>
                <div class="modal-body">
                    <h1 id="standrad_topic"></h1>
                    <p id="modal_body_spec"></p>
                </div>
                <div class="modal-footer">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- End Item more -->


    <div class="modal fade" id="addFormModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> <span id="modal_info"> General Inspection</span></h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item list-group-item-action active"> Specification: <span id="specification_names"></span> </li>
                        </ul>
                    </div>
                    <div class="card-header">
                        <ul class="nav nav-tabs" style="font-size:14px">
                            <li class="active">
                                <a data-toggle="tab" href="#home"><i class="fa fa-caret-square-o-down bg-deep-orange" aria-hidden="true"></i>Inspect &nbsp;<span class="badge bg-orange">|</span></a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#menu1"><i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> Notes &nbsp;<span class="badge bg-blue">|</span></a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">
                            <form class="form-horizontal" id="modal_form_submit" action="" method="POST" enctype="multipart/form-data">
                                <div id="non_compliant_form">
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="card" style="margin-bottom:-20px">
                                                <div class="body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped table-hover js-basic-example">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width:5%">#</th>
                                                                    <th style="width:30%">Issue</th>
                                                                    <th style="width:45%">Reason</th>
                                                                    <th style="width:10%">Status</th>
                                                                    <th style="width:10%">Issue Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="non_compliance_table">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="compliance_form">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                            <i class="fa fa-check-square" aria-hidden="true"></i> Compliance
                                        </legend>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <label>Compliance *:</label>
                                            <div class="form-line">
                                                <select name="compliance" id="compliance" class="form-control" required>
                                                    <option value="">Select</option>
                                                    <option value="1">Compliant</option>
                                                    <option value="2">Non-Compliant</option>
                                                    <option value="3">Comment</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="comment_section">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label class="control-label">Remark(s) :</label>
                                                <br>
                                                <div class="form-line">
                                                    <textarea name="comments" cols="" rows="7" class="form-control" id="comment" placeholder="Enter Comments if necessary" style="width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset class="scheduler-border" id="specification_issues">
                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Issue(s)
                                        </legend>
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:2%">#</th>
                                                                <th style="width:21%">Category</th>
                                                                <th style="width:75%">Description</th>
                                                                <th style="width:2%"><button type="button" name="addplus" onclick="add_issues();" title="Add another question" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="issues_table">
                                                            <tr></tr>
                                                            <tr id="removeTr" class="text-center">
                                                                <td colspan="4">Add Issues</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Task Checklist Questions -->
                                    </fieldset>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                            <i class="fa fa-paperclip" aria-hidden="true"></i> Means of Verification (Files/Documents)
                                        </legend>
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:2%">#</th>
                                                                <th style="width:40%">Attachments</th>
                                                                <th style="width:58%">Attachment Purpose</th>
                                                                <th style="width:2%"><button type="button" name="addplus" onclick="add_attachment();" title="Add another document" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="attachments_table">
                                                            <tr>
                                                                <td>1</td>
                                                                <td>
                                                                    <input type="file" name="monitorattachment[]" id="monitorattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control" placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
                                                                </td>
                                                                <td></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="modal-footer">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                        <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                        <input type="hidden" name="output_id" id="output_id" value="<?= $output_id ?>">
                                        <input type="hidden" name="design_id" id="design_id" value="<?= $design_id ?>">
                                        <input type="hidden" name="site_id" id="site_id" value="<?= $d_site_id ?>">
                                        <input type="hidden" name="state_id" id="state_id" value="<?= $d_state_id ?>">
                                        <input type="hidden" name="task_id" id="task_id" value="<?= $task_id ?>">
                                        <input type="hidden" name="parameter_id" id="parameter_id" value="<?= $parameter_id ?>">
                                        <input type="hidden" name="specification_id" id="specification_id" value="">
                                        <input type="hidden" name="formid" id="formid" value="<?= date("Y-m-d") ?>">
                                        <input type="hidden" name="store" id="store" value="new">
                                        <input type="hidden" name="inspection_type" id="inspection_type" value="2">
                                        <button name="save" type="" class="btn btn-primary waves-effect waves-light compliance_form" id="tag-form-submit" value="">Save</button>
                                        <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                    </div>
                                </div> <!-- /modal-footer -->
                            </form>
                        </div>
                        <div id="menu1" class="tab-pane fade">
                            <div id="non_compliant_form">
                            </div>
                            <div id="previous_remarks"></div>
                            <div class="modal-footer">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- /modal-footer -->

            </div> <!-- /modal-content -->
        </div> <!-- /modal-dailog -->
    </div>
    <!-- End add item -->




<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>

<script src="assets/js/inspection/inspect.js"></script>