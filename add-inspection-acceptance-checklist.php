<?php
try {

require('includes/head.php');
if ($permission) {
        $proj_id_decode = base64_decode($_GET['projid']);
        $proj_id_array = explode("projid54321", $proj_id_decode);
        $projid = $proj_id_array[1];
        $query_rsProjects = $db->prepare("SELECT p.*, s.sector, g.projsector, g.projdept, g.directorate FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND proj_substage = 1  ORDER BY p.projid DESC");
        $query_rsProjects->execute();
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();
    
?>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <?= $icon . ' ' . $pageTitle ?>
                    <div class="btn-group" style="float:right">
                        <div class="btn-group" style="float:right">
                            <a type="button" id="outputItemModalBtnrow" href="general-project-progress.php" class="btn btn-warning pull-right" style="margin-right:10px;">
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
                            <?php
                            $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid = :projid");
                            $query_Output->execute(array(":projid" => $projid));
                            $total_Output = $query_Output->rowCount();
                            $proceed = [];

                            if ($total_Output > 0) {
                                $counter = 0;
                                while ($row_rsOutput = $query_Output->fetch()) {
                                    $output_id = $row_rsOutput['id'];
                                    $output = $row_rsOutput['indicator_name'];
                                    $projname = $row_rsProjects['projname'];
                                    $projcode = $row_rsProjects['projcode'];

                                    $query_rsQuestions = $db->prepare("SELECT * FROM tbl_inspection_checklist_questions WHERE projid=:projid AND output_id=:output_id");
                                    $query_rsQuestions->execute(array(":projid" => $projid, ":output_id" => $output_id));
                                    $totalRows_rsQuestions = $query_rsQuestions->rowCount();
                                    $edit = $totalRows_rsQuestions > 0 ? 1 : 0;
                                    $proceed[] = $totalRows_rsQuestions > 0 ? true : false;
                                    $counter++;
                                    $details =
                                        "{
                                        projid:$projid,
                                        projcode:'$projcode',
                                        project_name:'$projname',
                                        output_id: '$output_id',
                                        output_name: '$output',
                                        edit:$totalRows_rsQuestions ,
                                    }";
                            ?>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                            <i class="fa fa-list-ol" aria-hidden="true"></i> Output <?= $counter ?> : <?= $output ?>
                                        </legend>
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="card-header">
                                                    <div class="row clearfix">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="btn-group" style="float:right">
                                                                <div class="btn-group" style="float:right">
                                                                    <button type="button" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#inspection_acceptance_modal" id="addFormModalBtn" onclick="add_checklists(<?= $details ?>)" class="btn btn-success btn-sm" style="float:right; margin-top:-5px">
                                                                        <?php echo $edit == 1 ? '<span class="glyphicon glyphicon-pencil"></span>' : '<span class="glyphicon glyphicon-plus"></span>' ?>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="direct_table<?= $output_id ?>">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%">#</th>
                                                                <th width="90%">Question</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $hash = 0;
                                                            while ($Rows_rsQuestions = $query_rsQuestions->fetch()) {
                                                                $hash++;
                                                            ?>
                                                                <tr>
                                                                    <td><?= $hash ?></td>
                                                                    <td><?= $Rows_rsQuestions['question']  ?></td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    </fieldset>
                                <?php
                                }
                            }

                            if (!empty($proceed) && !in_array(false, $proceed)) {
                                ?>
                                <div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
                                    <div class="col-md-12 text-center">
                                        <button type="button" onclick="proceed(<?= $projid ?>, 3)" class="btn btn-success">Proceed</button>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end body  -->

    <!-- Start Modal Item approve -->
    <div class="modal fade" id="inspection_acceptance_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Inspection & Acceptance</h4>
                </div>
                <form class="form-horizontal" id="add_questions_form" action="" method="POST">
                    <div class="modal-body">
                        <fieldset class="scheduler-border" id="tasks_div">
                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"> Checklists </legend>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <ul class="list-group">
                                    <li class="list-group-item list-group-item list-group-item-action active"> Project Name: <span id="projname"></span> </li>
                                    <li class="list-group-item"><strong> Output Name: </strong><span id="outputname"></span> </li>
                                    <li class="list-group-item"><strong> Code: </strong> <span id="projcode"></span> </li>
                                </ul>
                            </div>
                            <div class="col-md-12" id="projoutputTable">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover" id="question_table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="90%">Question</th>
                                                <th width="5%">
                                                    <button type="button" name="addplus" id="addplus_output" onclick="add_row_checklist();" class="btn btn-success btn-sm addplus_output">
                                                        <span class="glyphicon glyphicon-plus">
                                                        </span>
                                                    </button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="checklist_table_body">
                                            <tr id="m_row0">
                                                <td>1</td>
                                                <td>
                                                    <input type="text" name="question[]" id="questionrow0" placeholder="Enter Question" class="form-control" required />
                                                </td>
                                                <td> </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </fieldset>
                    </div> <!-- /modal-body -->
                    <div class="modal-footer approveItemFooter">
                        <div class="col-md-12 text-center">
                            <input type="hidden" name="projid" id="checklist_projid" value="">
                            <input type="hidden" name="add_questions" id="add_questions" value="new">
                            <input type="hidden" name="output_id" id="output_id" />
                            <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit-1" value="Submit" />
                            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                        </div>
                    </div> <!-- /modal-footer -->
                </form> <!-- /.form -->
            </div>
            <!-- /modal-content -->
        </div>
    </div>
    <!-- end assignment modal -->

<?php
} else {
    $results =  restriction();
    echo $results;
}

} catch (PDOException $ex) {
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}

require('includes/footer.php');
?>
<script src="assets/js/inspection/checklist.js" defer></script>