<?php
try {
    require('includes/head.php');
    if ($permission) {
        if (isset($_POST['store'])) {
            $projid = $_POST['projid'];
            $comments = $_POST['comments'];
            $parent_issue_id = $_POST['issue_id'];
            $date_requested = date("Y-m-d");


            $sql = $db->prepare("UPDATE tbl_projissues SET status=7 WHERE projid=:projid AND id=:parent_issue_id");
            $result  = $sql->execute(array(":projid" => $projid, ":parent_issue_id" => $parent_issue_id));
            $stage_id = 4;
            $projstage = 20;
            $sub_stage =  5;

            if ($comments != '') {
                $stage_id = 3;
                $projstage = 20;
                $sub_stage =  4;
                $sql = $db->prepare("INSERT INTO tbl_projissues (projid, issue_description, issue_area, risk_category, issue_priority, issue_impact, created_by, date_created) VALUES (:projid, :issue_description, :issue_area, :risk_category, :issue_priority, :issue_impact, :user, :date)");
                $results  = $sql->execute(array(':projid' => $projid, ':issue_description' => $comments, ':issue_area' => 5, ':risk_category' => 5, ':issue_priority' => 5, ':issue_impact' => 5, ':user' => $user_name, ':date' => $date_requested));

                $issue_id = $db->lastInsertId();
                $sql = $db->prepare("UPDATE tbl_inspection_checklist SET issue_id=:issue_id WHERE  projid=:projid AND issue_id=0 AND answer=2 AND parent_issue_id=:parent_issue_id ");
                $result  = $sql->execute(array(":issue_id" => $issue_id, ":projid" => $projid, ":parent_issue_id" => $parent_issue_id));
            }

            $sql = $db->prepare("UPDATE tbl_projects SET stage_id=:stage_id, projstage=:projstage,proj_substage=:proj_substage WHERE projid=:projid");
            $result  = $sql->execute(array(":stage_id" => $stage_id, ":projstage" => $projstage, ":proj_substage" => $sub_stage, ":projid" => $projid));


            $msg = "Record created Successfully";
            $results = "<script type=\"text/javascript\">
            swal({
                    title: \"Success!\",
                    text: \" $msg\",
                    type: 'Success',
                    timer: 2000,
                    'icon':'success',
                showConfirmButton: false });
                setTimeout(function(){
                    window.location.href = 'general-project-progress.php';
                }, 2000);
            </script>";

            echo $results;
        }

        if (isset($_GET['projid'])) {
            $encoded_projid = $_GET['projid'];
            $decode_projid = base64_decode($encoded_projid);
            $projid_array = explode("projid54321", $decode_projid);
            $projid = $projid_array[1];

            $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p INNER JOIN tbl_programs g on g.progid=p.progid WHERE p.deleted='0' AND projid = :projid");
            $query_rsProjects->execute(array(":projid" => $projid));
            $row_rsProjects = $query_rsProjects->fetch();
            $totalRows_rsProjects = $query_rsProjects->rowCount();

            if ($totalRows_rsProjects > 0) {
                $implimentation_type = $row_rsProjects['projcategory'];
                $projname = $row_rsProjects['projname'];
                $projcode = $row_rsProjects['projcode'];
                $proceed_array = $proceed = [];

                $query_issuedetails =  $db->prepare("SELECT * FROM tbl_projissues WHERE projid = :projid AND status=1 AND issue_area=5");
                $query_issuedetails->execute(array(":projid" => $projid));
                $rows_issuedetails = $query_issuedetails->rowCount();
                $row_issuedetails = $query_issuedetails->fetch();
                $issue_id =  $rows_issuedetails > 0 ? $row_issuedetails['id'] : '';

                function get_comments($projid, $site_id, $output_id, $question_id, $checklist_id)
                {
                    global $db, $projid;
                    $checklist_stmt_comment = $db->prepare("SELECT * FROM tbl_inspection_checklist_comments WHERE projid=:projid AND site_id=:site_id AND output_id=:output_id AND question_id=:question_id AND checklist_id=:checklist_id");
                    $checklist_stmt_comment->execute(array(":projid" => $projid, ":site_id" => $site_id, ":output_id" => $output_id, ":question_id" => $question_id, ':checklist_id' => $checklist_id));
                    $stmt_result_comment = $checklist_stmt_comment->fetch();
                    return ($stmt_result_comment) ? $stmt_result_comment['comment'] : '';
                }

                function get_new_comments($site_id, $output_id, $question_id, $checklist_id)
                {
                    global $db, $projid;
                    $checklist_stmt_comment = $db->prepare("SELECT * FROM tbl_inspection_checklist_comments WHERE projid=:projid AND site_id=:site_id AND output_id=:output_id AND question_id=:question_id AND checklist_id=:checklist_id");
                    $checklist_stmt_comment->execute(array(":projid" => $projid, ":site_id" => $site_id, ":output_id" => $output_id, ":question_id" => $question_id, ':checklist_id' => $checklist_id));
                    $stmt_result_comment = $checklist_stmt_comment->fetch();
                    return ($stmt_result_comment) ? $stmt_result_comment['comment'] : '';
                }

                function check_if_issue($site_id, $output_id, $question_id)
                {
                    global $db, $projid, $issue_id;
                    $stmt = $db->prepare("SELECT * FROM tbl_inspection_checklist WHERE projid=:projid AND site_id=:site_id AND output_id=:output_id AND question_id=:question_id AND issue_id=:issue_id");
                    $stmt->execute(array(":projid" => $projid, ":site_id" => $site_id, ":output_id" => $output_id, ":question_id" => $question_id, ":issue_id" => $issue_id));
                    return $stmt->fetch();
                }

                function check_if_issue_is_answered($site_id, $output_id, $question_id)
                {
                    global $db, $projid, $issue_id;
                    $stmt = $db->prepare("SELECT * FROM tbl_inspection_checklist WHERE projid=:projid AND site_id=:site_id AND output_id=:output_id AND question_id=:question_id AND parent_issue_id=:issue_id");
                    $stmt->execute(array(":projid" => $projid, ":site_id" => $site_id, ":output_id" => $output_id, ":question_id" => $question_id, ":issue_id" => $issue_id));
                    return $stmt->fetch();
                }
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
                                                        $checklist_stmt = $db->prepare("SELECT * FROM tbl_inspection_checklist WHERE projid=:projid AND site_id=:site_id AND answer=2 AND issue_id=:issue_id");
                                                        $checklist_stmt->execute(array(":projid" => $projid, ":site_id" => $site_id, ":issue_id" => $issue_id));
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
                                                                            $outputlist_stmt = $db->prepare("SELECT * FROM tbl_inspection_checklist WHERE projid=:projid AND site_id=:site_id AND answer=2 AND output_id=:output_id AND issue_id=:issue_id");
                                                                            $outputlist_stmt->execute(array(":projid" => $projid, ":site_id" => $site_id, ":output_id" => $output_id, ":issue_id" => $issue_id));
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
                                                                                                            <th style="width:5%">Compliant</th>
                                                                                                            <th style="width:20%">Old Comment</th>
                                                                                                            <th style="width:20%">New Comment</th>
                                                                                                            <th style="width:10%">Action</th>
                                                                                                        </tr>
                                                                                                    </thead>
                                                                                                    <tbody>
                                                                                                        <?php
                                                                                                        $query_rsQuestions = $db->prepare("SELECT * FROM tbl_inspection_checklist_questions WHERE projid=:projid AND output_id=:output_id ");
                                                                                                        $query_rsQuestions->execute(array(":projid" => $projid, ":output_id" => $output_id));
                                                                                                        $totalRows_rsQuestions = $query_rsQuestions->rowCount();
                                                                                                        if ($totalRows_rsQuestions > 0) {
                                                                                                            $counter = 0;
                                                                                                            while ($row = $query_rsQuestions->fetch()) {
                                                                                                                $question_id = $row['id'];
                                                                                                                $question = $row['question'];
                                                                                                                $issue_details = check_if_issue($site_id, $output_id, $question_id);
                                                                                                                if ($issue_details) {
                                                                                                                    $checklist_id = $issue_details['id'];
                                                                                                                    $old_comment = get_comments($projid, $site_id, $output_id, $question_id, $checklist_id);
                                                                                                                    $answer_details = check_if_issue_is_answered($site_id, $output_id, $question_id);

                                                                                                                    $answer_checklist_id = $answer_checklist_answer = $new_comment = $answ = '';
                                                                                                                    if ($answer_details) {
                                                                                                                        $proceed_array[] = true;
                                                                                                                        $answer_checklist_id = $answer_details['id'];
                                                                                                                        $answer_checklist_answer = $answer_details['answer'];
                                                                                                                        $answ = $answer_checklist_answer == 1 ? 'yes' : 'no';
                                                                                                                        $proceed[] = $answer_checklist_answer == 1 ? true : false;
                                                                                                                        $new_comment =  get_new_comments($site_id, $output_id, $question_id, $answer_checklist_id);
                                                                                                                    } else {
                                                                                                                        $proceed_array[] = false;
                                                                                                                    }

                                                                                                                    $question_details =
                                                                                                                        "{
                                                                                                                            question_id: $question_id,
                                                                                                                            question:'$question',
                                                                                                                            output_id: '$output_id',
                                                                                                                            site_id: '$site_id',
                                                                                                                            comment:'$new_comment',
                                                                                                                            answer:'$answer_checklist_answer',
                                                                                                                        }";
                                                                                                                    $counter++;
                                                                                                        ?>
                                                                                                                    <tr>
                                                                                                                        <td style="width:5%"><?= $counter ?> </td>
                                                                                                                        <td style="width:40%"><?= $question ?></td>
                                                                                                                        <td style="width:5%"><?= $answ ?></td>
                                                                                                                        <td style="width:20%"><?= $old_comment ?></td>
                                                                                                                        <td style="width:20%"><?= $new_comment ?></td>
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
                                                if ($total_Output > 0) {
                                                    $counter = 0;
                                                    while ($row_rsOutput = $query_Output->fetch()) {
                                                        $output_id = $row_rsOutput['id'];
                                                        $output = $row_rsOutput['indicator_name'];
                                                        $counter++;

                                                        $outputlist_stmt = $db->prepare("SELECT * FROM tbl_inspection_checklist WHERE projid=:projid AND site_id=:site_id AND answer=2 AND output_id=:output_id AND issue_id=:issue_id");
                                                        $outputlist_stmt->execute(array(":projid" => $projid, ":site_id" => $site_id, ":output_id" => $output_id, ":issue_id" => $issue_id));
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
                                                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="direct_table">
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
                                                                                    $query_rsQuestions = $db->prepare("SELECT * FROM tbl_inspection_checklist_questions WHERE projid=:projid AND output_id=:output_id");
                                                                                    $query_rsQuestions->execute(array(":projid" => $projid, ":output_id" => $output_id));
                                                                                    $totalRows_rsQuestions = $query_rsQuestions->rowCount();
                                                                                    if ($totalRows_rsQuestions > 0) {
                                                                                        $counter = 0;
                                                                                        while ($row = $query_rsQuestions->fetch()) {
                                                                                            $question_id = $row['id'];
                                                                                            $question = $row['question'];
                                                                                            $issue_details = check_if_issue($site_id, $output_id, $question_id);
                                                                                            if ($issue_details) {
                                                                                                $checklist_id = $issue_details['id'];
                                                                                                $old_comment = get_comments($projid, $site_id, $output_id, $question_id, $checklist_id);
                                                                                                $answer_details = check_if_issue_is_answered($site_id, $output_id, $question_id);

                                                                                                $answer_checklist_id = $answer_checklist_answer = $new_comment = $answ = '';
                                                                                                if ($answer_details) {
                                                                                                    $proceed_array[] = true;
                                                                                                    $answer_checklist_id = $answer_details['id'];
                                                                                                    $answer_checklist_answer = $answer_details['answer'];
                                                                                                    $answ = $answer_checklist_answer == 1 ? 'yes' : 'no';
                                                                                                    $proceed[] = $answer_checklist_answer == 1 ? true : false;
                                                                                                    $new_comment =  get_new_comments($site_id, $output_id, $question_id, $answer_checklist_id);
                                                                                                } else {
                                                                                                    $proceed_array[] = false;
                                                                                                }

                                                                                                $question_details =
                                                                                                    "{
                                                                                                        question_id: $question_id,
                                                                                                        question:'$question',
                                                                                                        output_id: '$output_id',
                                                                                                        site_id: '$site_id',
                                                                                                        comment:'$new_comment',
                                                                                                        answer:'$answer_checklist_answer',
                                                                                                    }";
                                                                                                $counter++;
                                                                                    ?>
                                                                                                <tr>
                                                                                                    <td style="width:5%"><?= $counter ?></td>
                                                                                                    <td style="width:40%"><?= $question ?></td>
                                                                                                    <td style="width:5%"><?= $answ ?></td>
                                                                                                    <td style="width:20%"><?= $old_comment ?></td>
                                                                                                    <td style="width:20%"><?= $new_comment ?></td>
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
                                                if (!empty($proceed_array) && !in_array(false, $proceed_array)) {
                                                    ?>
                                                    <fieldset class="scheduler-border" id="direct_cost">
                                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                            <i class="fa fa-calendar" aria-hidden="true"></i> Proceed
                                                        </legend>
                                                        <form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                                                            <?php
                                                            if (in_array(false, $proceed)) {
                                                            ?>
                                                                <div id="comment_section">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label class="control-label">Remarks *:</label>
                                                                        <br>
                                                                        <div class="form-line">
                                                                            <textarea name="comments" cols="" rows="7" class="form-control" id="comment" placeholder="Enter Comments if necessary" style="width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php
                                                            }
                                                            ?>
                                                            <div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
                                                                <div class="col-md-12 text-center">
                                                                    <input type="hidden" name="projid" value="<?= $projid ?>">
                                                                    <input type="hidden" name="issue_id" value="<?= $issue_id ?>">
                                                                    <input type="hidden" name="store" value="store">
                                                                    <button type="submit" class="btn btn-success">Proceed</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </fieldset>
                                                <?php
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

                <!-- Start Modal Item approve -->
                <div class="modal fade" id="inspection_acceptance_modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color:#03A9F4">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Inspection & Acceptance Checklists</h4>
                            </div>
                            <form class="form-horizontal" id="add_questions_form" action="" method="POST">
                                <div class="modal-body">
                                    <fieldset class="scheduler-border" id="tasks_div">
                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Is it compliant? </legend>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <ul class="list-group">
                                                <li class="list-group-item list-group-item list-group-item-action active">Project Name: <span id="projname"><?= $projname ?></span> </li>
                                                <li class="list-group-item"><strong>Code: </strong> <span id="projcode"></span><?= $projcode ?> </li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="" class="control-label"><span id="question"></span>? *:</label>
                                            <div class="form-line">
                                                <input name="question" type="radio" value="1" id="question1" onchange="check_box(1)" class="with-gap radio-col-green question" />
                                                <label for="question1">YES</label>
                                                <input name="question" type="radio" value="2" id="question2" onchange="check_box(2)" class="with-gap radio-col-red question" />
                                                <label for="question2">NO</label>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset class="scheduler-border" id="project_approve_div">
                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                            <i class="fa fa-comment" aria-hidden="true"></i> Action Required
                                        </legend>
                                        <div id="comment_section">
                                            <div class="col-md-12">
                                                <label class="control-label">Action Required *:</label>
                                                <br />
                                                <div class="form-line">
                                                    <textarea name="comments" cols="" rows="7" class="form-control" id="comment" placeholder="Describe action required" style="width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div> <!-- /modal-body -->
                                <div class="modal-footer approveItemFooter">
                                    <div class="col-md-12 text-center">
                                        <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                        <input type="hidden" name="issue_id" id="issue_id" value="<?= $issue_id ?>">
                                        <input type="hidden" name="site_id" id="site_id" value="">
                                        <input type="hidden" name="output_id" id="output_id" value="">
                                        <input type="hidden" name="question_id" id="question_id" value="">
                                        <input type="hidden" name="answer_question" id="answer_question" value="new">
                                        <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Submit" />
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
        } else {
            $results =  restriction();
            echo $results;
        }
    } else {
        $results =  restriction();
        echo $results;
    }
    require('includes/footer.php');
} catch (PDOException $th) {
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>

<script src="assets/js/inspection/reinspection.js" defer></script>