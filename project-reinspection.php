<?php

require('includes/head.php');

// if ($permission) {


try {
    if (isset($_GET['projid'])) {
        $encoded_projid = $_GET['projid'];
        $decode_projid = base64_decode($encoded_projid);
        $projid_array = explode("projid54321", $decode_projid);
        $projid = $projid_array[1];
        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.deleted='0' AND projid = :projid");
        $query_rsProjects->execute(array(":projid" => $projid));
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();

        $pageTitle = "Reinspection";

        if ($totalRows_rsProjects > 0) {
            $implimentation_type = $row_rsProjects['projcategory'];
            $projname = $row_rsProjects['projname'];
            $projcode = $row_rsProjects['projcode'];
            $projcost = $row_rsProjects['projcost'];
            $projfscyear = $row_rsProjects['projfscyear'];
            $projduration = $row_rsProjects['projduration'];
            $mne_cost = $row_rsProjects['mne_budget'];
            $direct_cost = $row_rsProjects['direct_cost'];
            $administrative_cost = $row_rsProjects['administrative_cost'];
            $implementation_cost = $projcost - $mne_cost;
            $progid = $row_rsProjects['progid'];
            $projstartdate = $row_rsProjects['projstartdate'];
            $projenddate = $row_rsProjects['projenddate'];
            $project_sub_stage = $row_rsProjects['proj_substage'];
            $workflow_stage = $row_rsProjects['projstage'];
            $project_directorate = $row_rsProjects['directorate'];

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

                    <div class="card">
                        <div class="row clearfix container-fluid">
                            <div class="block-header">
                                <?= $results; ?>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="card-header">
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <ul class="list-group">
                                                <li class="list-group-item list-group-item list-group-item-action active">Project Name: <?= $projname ?> </li>
                                                <li class="list-group-item"><strong>Project Code: </strong> <?= $projcode ?> </li>
                                            </ul>
                                        </div>

                                        <div class="body">
                                            <?php
                                            $query_Sites = $db->prepare("SELECT * FROM tbl_project_sites WHERE projid=:projid");
                                            $query_Sites->execute(array(":projid" => $projid));
                                            $rows_sites = $query_Sites->rowCount();

                                            if ($rows_sites > 0) {
                                                $counter = 0;
                                                while ($row_Sites = $query_Sites->fetch()) {
                                                    $site_id = $row_Sites['site_id'];
                                                    $site = $row_Sites['site'];
                                                    $counter++;

                                                    $checklist_stmt = $db->prepare("SELECT * FROM tbl_inspection_checklist WHERE projid=:projid AND site_id=:site_id AND answer=2");
                                                    $checklist_stmt->execute(array(":projid" => $projid, ":site_id" => $site_id));
                                                    $checklist_total_rows = $checklist_stmt->rowCount();

                                                    if ($checklist_total_rows > 0) {
                                                    
                                            ?>

                                                    <fieldset class="scheduler-border">
                                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                            <i class="fa fa-list-ol" aria-hidden="true"></i> Site <?= $counter ?> : <?= $site ?>
                                                        </legend>

                                                        <?php
                                                        $query_Site_Output = $db->prepare("SELECT * FROM tbl_output_disaggregation  WHERE output_site=:site_id");
                                                        $query_Site_Output->execute(array(":site_id" => $site_id));
                                                        $rows_Site_Output = $query_Site_Output->rowCount();
                                                        if ($rows_Site_Output > 0) {
                                                            $output_counter = 0;
                                                            while ($row_Site_Output = $query_Site_Output->fetch()) {
                                                                $output_counter++;
                                                                $output_id = $row_Site_Output['outputid'];
                                                                $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE id =:outputid");
                                                                $query_Output->execute(array(":outputid" => $output_id));
                                                                $row_Output = $query_Output->fetch();
                                                                $total_Output = $query_Output->rowCount();

                                                                

                                                                if ($total_Output) {
                                                                    $output_id = $row_Output['id'];
                                                                    $output = $row_Output['indicator_name'];

                                                                    $outputlist_stmt = $db->prepare("SELECT * FROM tbl_inspection_checklist WHERE projid=:projid AND site_id=:site_id AND answer=2 AND output_id=:output_id");
                                                                    $outputlist_stmt->execute(array(":projid" => $projid, ":site_id" => $site_id, ":output_id" => $output_id));
                                                                    $outputlist_total_rows = $outputlist_stmt->rowCount();

                                                                    if ($outputlist_total_rows > 0) {
                                                        ?>

                                                                    <fieldset class="scheduler-border">
                                                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                                            <i class="fa fa-list-ol" aria-hidden="true"></i> Output <?= $counter ?> : <?= $output ?>
                                                                        </legend>
                                                                        <div class="row clearfix">
                                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                <div class="table-responsive">
                                                                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="direct_table<?= $output_id ?>">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th style="width:5%" align="center">#</th>
                                                                                                <th style="width:40%">Question</th>
                                                                                                <th style="width:5%">Answer</th>
                                                                                                <th style="width:40%">Comment</th>
                                                                                                <th style="width:10%">Action</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <?php
                                                                                            $question_arr = [];
                                                                                            $query_rsQuestions = $db->prepare("SELECT * FROM tbl_inspection_checklist_questions WHERE projid=:projid AND output_id=:output_id");
                                                                                            $query_rsQuestions->execute(array(":projid" => $projid, ":output_id" => $output_id));
                                                                                            $totalRows_rsQuestions = $query_rsQuestions->rowCount();
                                                                                            if ($totalRows_rsQuestions > 0) {
                                                                                                $counter = 0;
                                                                                                while ($row = $query_rsQuestions->fetch()) {
                                                                                                    $question_id = $row['id'];
                                                                                                    $question = $row['question'];


                                                                                                    $stmt = $db->prepare("SELECT * FROM tbl_inspection_checklist WHERE projid=:projid AND site_id=:site_id AND output_id=:output_id AND question_id=:question_id");
                                                                                                    $stmt->execute(array(":projid" => $projid, ":site_id" => $site_id, ":output_id" => $output_id, ":question_id" => $question_id));
                                                                                                    $total_rows = $stmt->rowCount();
                                                                                                    $stmt_result = $stmt->fetch();


                                                                                                    $answ = '';
                                                                                                    $answer = '';


                                                                                                    if ($total_rows > 0) {
                                                                                                        $checklist_id = $stmt_result['id'];

                                                                                                        $checklist_stmt_comment = $db->prepare("SELECT * FROM tbl_inspection_checklist_comments WHERE projid=:projid AND site_id=:site_id AND output_id=:output_id AND question_id=:question_id AND checklist_id=:checklist_id");
                                                                                                        $checklist_stmt_comment->execute(array(":projid" => $projid, ":site_id" => $site_id, ":output_id" => $output_id, ":question_id" => $question_id, ':checklist_id' => $checklist_id));
                                                                                                        $stmt_result_comment = $checklist_stmt_comment->fetch();

                                                                                                        $answ = $stmt_result['answer'];
                                                                                                        $answer = $stmt_result['answer'] == 1 ? 'Yes' : 'No';
                                                                                                        $question_arr[] = $answ;

                                                                                                        $comment = '';

                                                                                                        if ($stmt_result_comment) {
                                                                                                            $comment = $stmt_result_comment['comment'];
                                                                                                        }

                                                                                                        $question_details =
                                                                                                            "{
                                                                                                            question_id: $question_id,
                                                                                                            question:'$question',
                                                                                                            output_id: '$output_id',
                                                                                                            site_id: '$site_id',
                                                                                                            comment:'$comment',
                                                                                                            answer:$answ,
                                                                                                        }";
                                                                                                        if ($answ == 2) {
                                                                                                            $counter++;
                                                                                            ?>
                                                                                                        <tr>
                                                                                                            <td style="width:5%"><?= $counter ?> </td>
                                                                                                            <td style="width:40%"><?= $question ?></td>
                                                                                                            <td style="width:5%"><?= $answer ?></td>
                                                                                                            <td style="width:40%"><?= $comment ?></td>
                                                                                                            <td style="width:10%">
                                                                                                                <a type="button" class="btn bg-purple waves-effect" onclick="get_details(<?= $question_details ?>)" data-toggle="modal" id="addFormModalbtn" data-target="#inspection_acceptance_modal" title="Click here to request payment" style="height:25px; padding-top:0px">
                                                                                                                    <i class="fa fa-money" style="color:white; height:20px; margin-top:0px"></i> Answer
                                                                                                                </a>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                            <?php
                                                                                                        }

                                                                                                    }
                                                                                                }
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
                                                            }
                                                        }
                                                        ?>

                                                <?php
                                                ?>
                                                    </fieldset>
                                                <?php 
                                                    }

                                                    }
                                                }


                                                $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE indicator_mapping_type<>1 AND projid = :projid");
                                                $query_Output->execute(array(":projid" => $projid));
                                                $total_Output = $query_Output->rowCount();
                                                $outputs = '';
                                                if ($total_Output > 0) {
                                                    $counter = 0;

                                                    while ($row_rsOutput = $query_Output->fetch()) {
                                                        $output_id = $row_rsOutput['id'];
                                                        $output = $row_rsOutput['indicator_name'];
                                                        $counter++;

                                                        $outputlist_stmt = $db->prepare("SELECT * FROM tbl_inspection_checklist WHERE projid=:projid AND site_id=:site_id AND answer=2 AND output_id=:output_id");
                                                        $outputlist_stmt->execute(array(":projid" => $projid, ":site_id" => $site_id, ":output_id" => $output_id));
                                                        $outputlist_total_rows = $outputlist_stmt->rowCount();

                                                        if ($outputlist_total_rows > 0) {
                                                ?>

                                                
                                                            <fieldset class="scheduler-border">
                                                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                                    <i class="fa fa-list-ol" aria-hidden="true"></i> Output <?= $counter ?> : <?= $output ?>
                                                                </legend>
                                                                <div class="row clearfix">

                                                                    <input type="hidden" name="task_amount[]" id="task_amount<?= $msid ?>" class="task_costs" value="<?= $sum_cost ?>">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="direct_table<?= $output_id ?>">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th style="width:5%" align="center">#</th>
                                                                                        <th style="width:40%">Question</th>
                                                                                        <th style="width:5%">Answer</th>
                                                                                        <th style="width:40%">Comment</th>
                                                                                        <th style="width:10%">Action</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    $question_arr = [];
                                                                                    $query_rsQuestions = $db->prepare("SELECT * FROM tbl_inspection_checklist_questions WHERE projid=:projid AND output_id=:output_id");
                                                                                    $query_rsQuestions->execute(array(":projid" => $projid, ":output_id" => $output_id));
                                                                                    $totalRows_rsQuestions = $query_rsQuestions->rowCount();
                                                                                    if ($totalRows_rsQuestions > 0) {
                                                                                        $counter = 0;
                                                                                        while ($row = $query_rsQuestions->fetch()) {
                                                                                            $question_id = $row['id'];
                                                                                            $question = $row['question'];
                                                                                            $comment = $row['comment'];
                                                                                            $answ = $row['answer'];
                                                                                            $answer = $row['answer'] == 1 ? 'Yes' : 'No';
                                                                                            $question_arr[] = $answ;
                                                                                            $stmt = $db->prepare("SELECT * FROM tbl_inspection_checklist WHERE projid=:projid AND site_id=:site_id AND output_id=:output_id AND question_id=:question_id  ");
                                                                                            $stmt->execute(array(":projid" => $projid, ":site_id" => 0, ":output_id" => $output_id, ":question_id" => $question_id));
                                                                                            $total_rows = $stmt->rowCount();
                                                                                            $stmt_result = $stmt->fetch();


                                                                                            // $answ = $stmt_result['answer'];
                                                                                            // $answer = $stmt_result['answer'] == 1 ? 'Yes' : 'No';
                                                                                            // $question_arr[] = $answ;
                                                                                            $answ = '';
                                                                                            $answer = '';

                                                                                            if ($total_rows > 0) {
                                                                                                $checklist_id = $stmt_result['id'];
                                                                                                $checklist_stmt_comment = $db->prepare("SELECT * FROM tbl_inspection_checklist_comments WHERE projid=:projid AND site_id=:site_id AND output_id=:output_id AND question_id=:question_id AND checklist_id=:checklist_id");
                                                                                                $checklist_stmt_comment->execute(array(":projid" => $projid, ":site_id" => 0, ":output_id" => $output_id, ":question_id" => $question_id, ':checklist_id' => $checklist_id));
                                                                                                $stmt_result_comment = $checklist_stmt_comment->fetch();

                                                                                                $answ = $stmt_result['answer'];
                                                                                                $answer = $stmt_result['answer'] == 1 ? 'Yes' : 'No';
                                                                                                $question_arr[] = $answ;

                                                                                                $comment = '';

                                                                                                if ($stmt_result_comment) {
                                                                                                    $comment = $stmt_result_comment['comment'];
                                                                                                }

                                                                                                $question_details = "{
                                                                                                question_id: $question_id,
                                                                                                question:'$question',
                                                                                                output_id: '$output_id',
                                                                                                site_id: '0',
                                                                                                comment:'$comment',
                                                                                                answer:$answ,
                                                                                            }";

                                                                                                if ($answ == 2) {
                                                                                                    $counter++;

                                                                                    ?>
                                                                                                    <tr>
                                                                                                        <td style="width:5%"><?= $counter ?></td>
                                                                                                        <td style="width:40%"><?= $question ?></td>
                                                                                                        <td style="width:5%"><?= $answer ?></td>
                                                                                                        <td style="width:40%"><?= $comment ?></td>
                                                                                                        <td style="width:10%">
                                                                                                            <a type="button" class="btn bg-purple waves-effect" onclick="get_details(<?= $question_details ?>)" data-toggle="modal" id="addFormModalbtn" data-target="#inspection_acceptance_modal" title="Click here to request payment" style="height:25px; padding-top:0px">
                                                                                                                <i class="fa fa-money" style="color:white; height:20px; margin-top:0px"></i> Answer
                                                                                                            </a>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                    <?php
                                                                                                }

                                                                                            }
                                                                                        }
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
                                                }
                                                ?>

                                        </div>

                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                </div>
            </section>

<?php

        } else {
            $results =  restriction();
            echo $results;
        }
    } else {
        $results =  restriction();
        echo $results;
    }
} catch (\Throwable $th) {
    $results = flashMessage("An error occurred: " . $ex->getMessage());
}

// } else {
// $results =  restriction();
// echo $results;
// }

require('includes/footer.php');

?>