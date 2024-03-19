<?php
require('includes/head.php');
// if ($permission) {
    try {

        if (isset($_POST['store'])) {
            $projid = $_POST['projid'];
            $comments = $_POST['comments'];
            $date_requested = date("Y-m-d");
            $workflow_stage = 9;
            $sub_stage = $comments != '' ? 4 : 5;
            $sql = $db->prepare("UPDATE tbl_projects SET proj_substage=:proj_substage WHERE  projid=:projid");
            $result  = $sql->execute(array(":proj_substage" => $sub_stage, ":projid" => $projid));

            if ($comments != '') {
                $sql = $db->prepare("INSERT INTO tbl_projissues (projid, issue_description, issue_area, risk_category, issue_priority, issue_impact, created_by, date_created) VALUES (:projid, :issue_description, :issue_area, :risk_category, :issue_priority, :issue_impact, :user, :date)");
                $results  = $sql->execute(array(':projid' => $projid, ':issue_description' => $comments, ':issue_area' => 5, ':risk_category' => 5, ':issue_priority' => 5, ':issue_impact' => 5, ':user' => $user_name, ':date' => $date_requested));
            }
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
            $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.deleted='0' AND projid = :projid");
            $query_rsProjects->execute(array(":projid" => $projid));
            $row_rsProjects = $query_rsProjects->fetch();
            $totalRows_rsProjects = $query_rsProjects->rowCount();

            $approve_details = "";

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

                $query_rsQuestions_pending = $db->prepare("SELECT * FROM tbl_inspection_checklist_questions WHERE projid=:projid");
                $query_rsQuestions_pending->execute(array(":projid" => $projid));
                $totalRows_rsQuestions_pending = $query_rsQuestions_pending->rowCount();
                $insp_questions = $query_rsQuestions_pending->fetchAll(PDO::FETCH_OBJ);
                $not_answered = 0;
                $answered = 0;
                foreach ($insp_questions as $key => $qn) {
                    $qn_id = $qn->id;
                    $stmt_check = $db->prepare("SELECT * FROM tbl_inspection_checklist WHERE projid=:projid AND question_id=:question_id");
                    $stmt_check->execute(array(":projid" => $projid, ":question_id" => $qn_id));
                    $total_rows_check = $stmt_check->rowCount();
                    if ($total_rows_check > 0) {
                        $answered++;
                    } else {
                        $not_answered++;
                    }
                }
<<<<<<< HEAD
=======


>>>>>>> c0e07d118193ba7810b6bbffd6d3380090237cbf
?>
                <!-- start body  -->
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
                            <div class="row clearfix">
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
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <ul class="nav nav-tabs" style="font-size:14px">
                                                    <li class="active">
                                                        <a data-toggle="tab" href="#menu1">
                                                            <i class="fa fa-caret-square-o-up bg-deep-purple" aria-hidden="true"></i>
                                                            Pending Questions &nbsp;&nbsp;<span class="badge bg-orange" id="total-programs"><?= $not_answered ?></span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a data-toggle="tab" href="#menu2">
                                                            <i class="fa fa-caret-square-o-right bg-indigo" aria-hidden="true"></i>
                                                            Answered Questions &nbsp;<span class="badge bg-indigo"><?= $answered ?></span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="body">
                                        <div class="tab-content">
                                            <div id="menu1" class="tab-pane fade in active">
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
                                                                                                        <th style="width:80%">Item</th>
                                                                                                        <th style="width:10%">Action</th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                    <?php
                                                                                                    $questions = '';
                                                                                                    $query_rsQuestions_pending = $db->prepare("SELECT * FROM tbl_inspection_checklist_questions WHERE projid=:projid AND answer=0 AND output_id=:output_id");
                                                                                                    $query_rsQuestions_pending->execute(array(":projid" => $projid, ":output_id" => $output_id));
                                                                                                    $totalRows_rsQuestions_pending = $query_rsQuestions_pending->rowCount();
                                                                                                    if ($totalRows_rsQuestions_pending > 0) {
                                                                                                        $hash = 0;
                                                                                                        while ($row = $query_rsQuestions_pending->fetch()) {
                                                                                                            $question_id = $row['id'];
                                                                                                            $question = $row['question'];
                                                                                                            $answer = $row['answer'];

                                                                                                            $stmt = $db->prepare("SELECT * FROM tbl_inspection_checklist WHERE projid=:projid AND site_id=:site_id AND output_id=:output_id AND question_id=:question_id  ");
                                                                                                            $stmt->execute(array(":projid" => $projid, ":site_id" => $site_id, ":output_id" => $output_id, ":question_id" => $question_id));
                                                                                                            $total_rows = $stmt->rowCount();

                                                                                                            if ($total_rows == 0) {
                                                                                                                $hash++;
                                                                                                                $question_details =
                                                                                                                    "{
                                                                                                                    question_id: $question_id,
                                                                                                                    question:'$question',
                                                                                                                    output_id: '$output_id',
                                                                                                                    site_id: '$site_id',
                                                                                                                    comment:'',
                                                                                                                    answer:'',
                                                                                                                }";



                                                                                                    ?>
                                                                                                                <tr>
                                                                                                                    <td><?= $hash ?></td>
                                                                                                                    <td>

                                                                                                                        <?= $question ?>
                                                                                                                    </td>
                                                                                                                    <td>
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
                                                                ?>
                                                            </fieldset>
                                                        <?php
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
                                                                                        <th style="width:80%">Item</th>
                                                                                        <th style="width:10%">Action</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    $questions = '';
                                                                                    $query_rsQuestions_pending = $db->prepare("SELECT * FROM tbl_inspection_checklist_questions WHERE projid=:projid AND answer=0 AND output_id=:output_id");
                                                                                    $query_rsQuestions_pending->execute(array(":projid" => $projid, ":output_id" => $output_id));
                                                                                    $totalRows_rsQuestions_pending = $query_rsQuestions_pending->rowCount();
                                                                                    if ($totalRows_rsQuestions_pending > 0) {
                                                                                        $counter = 0;
                                                                                        while ($row = $query_rsQuestions_pending->fetch()) {
                                                                                            $question_id = $row['id'];
                                                                                            $question = $row['question'];
                                                                                            $answer = $row['answer'];


                                                                                            $stmt = $db->prepare("SELECT * FROM tbl_inspection_checklist WHERE projid=:projid AND site_id=:site_id  AND output_id=:output_id AND question_id=:question_id  ");
                                                                                            $stmt->execute(array(":projid" => $projid, ":site_id" => 0, ":output_id" => $output_id,  ":question_id" => $question_id));
                                                                                            $total_rows = $stmt->rowCount();


                                                                                            if ($total_rows == 0) {
                                                                                                $counter++;
                                                                                                $question_details =
                                                                                                    "{
                                                                                                    question_id: $question_id,
                                                                                                    question:'$question',
                                                                                                    output_id: '$output_id',
                                                                                                    site_id: '0',
                                                                                                    comment:'',
                                                                                                    answer:'',
                                                                                                }";
                                                                                    ?>
                                                                                                <tr>
                                                                                                    <td><?= $counter ?></td>
                                                                                                    <td>
                                                                                                        <?= $question ?>
                                                                                                    </td>
                                                                                                    <td>
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
                                                    ?>
                                                </div>
                                            </div>

                                            <div id="menu2" class="tab-pane">
                                                <div class="body">
                                                    <?php
                                                    $proceed = [];
                                                    $query_Sites = $db->prepare("SELECT * FROM tbl_project_sites WHERE projid=:projid");
                                                    $query_Sites->execute(array(":projid" => $projid));
                                                    $rows_sites = $query_Sites->rowCount();
                                                    if ($rows_sites > 0) {
                                                        $counter = 0;
                                                        while ($row_Sites = $query_Sites->fetch()) {
                                                            $site_id = $row_Sites['site_id'];
                                                            $site = $row_Sites['site'];
                                                            $counter++;
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
                                                                        $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE id = :outputid");
                                                                        $query_Output->execute(array(":outputid" => $output_id));
                                                                        $row_Output = $query_Output->fetch();
                                                                        $total_Output = $query_Output->rowCount();
                                                                        if ($total_Output) {
                                                                            $output_id = $row_Output['id'];
                                                                            $output = $row_Output['indicator_name'];
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
                                                                                                    $query_rsQuestions = $db->prepare("SELECT * FROM tbl_inspection_checklist_questions WHERE projid=:projid AND output_id=:output_id");
                                                                                                    $query_rsQuestions->execute(array(":projid" => $projid, ":output_id" => $output_id));
                                                                                                    $totalRows_rsQuestions = $query_rsQuestions->rowCount();
                                                                                                    if ($totalRows_rsQuestions > 0) {
                                                                                                        $counter = 0;
                                                                                                        while ($row = $query_rsQuestions->fetch()) {
                                                                                                            $counter++;
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
                                                                                                                $answer = $answ == 1 ? 'Yes' : 'No';
                                                                                                                $proceed[] = $answ == 1 ? true : false;
                                                                                                                $comment = ($stmt_result_comment) ? $stmt_result_comment['comment'] : 'N/A';


                                                                                                                $question_details =
                                                                                                                    "{
                                                                                                                    question_id: $question_id,
                                                                                                                    question:'$question',
                                                                                                                    output_id: '$output_id',
                                                                                                                    site_id: '$site_id',
                                                                                                                    comment:'$comment',
                                                                                                                    answer:$answ,
                                                                                                                }";
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
                                                            </fieldset>
                                                        <?php
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
                                                                                    $query_rsQuestions = $db->prepare("SELECT * FROM tbl_inspection_checklist_questions WHERE projid=:projid AND output_id=:output_id");
                                                                                    $query_rsQuestions->execute(array(":projid" => $projid, ":output_id" => $output_id));
                                                                                    $totalRows_rsQuestions = $query_rsQuestions->rowCount();
                                                                                    if ($totalRows_rsQuestions > 0) {
                                                                                        $counter = 0;
                                                                                        while ($row = $query_rsQuestions->fetch()) {
                                                                                            $counter++;
                                                                                            $question_id = $row['id'];
                                                                                            $question = $row['question'];
                                                                                            $comment = $row['comment'];
                                                                                            $answ = $row['answer'];
                                                                                            $answer = $answ == 1 ? 'Yes' : 'No';
                                                                                            $proceed[] = $answ == 1 ? true : false;
                                                                                            $stmt = $db->prepare("SELECT * FROM tbl_inspection_checklist WHERE projid=:projid AND site_id=:site_id AND output_id=:output_id AND question_id=:question_id  ");
                                                                                            $stmt->execute(array(":projid" => $projid, ":site_id" => 0, ":output_id" => $output_id, ":question_id" => $question_id));
                                                                                            $total_rows = $stmt->rowCount();
                                                                                            $stmt_result = $stmt->fetch();
                                                                                            $answ = '';
                                                                                            $answer = '';

                                                                                            if ($total_rows > 0) {
                                                                                                $checklist_id = $stmt_result['id'];
                                                                                                $checklist_stmt_comment = $db->prepare("SELECT * FROM tbl_inspection_checklist_comments WHERE projid=:projid AND site_id=:site_id AND output_id=:output_id AND question_id=:question_id AND checklist_id=:checklist_id");
                                                                                                $checklist_stmt_comment->execute(array(":projid" => $projid, ":site_id" => 0, ":output_id" => $output_id, ":question_id" => $question_id, ':checklist_id' => $checklist_id));
                                                                                                $stmt_result_comment = $checklist_stmt_comment->fetch();

                                                                                                $answ = $stmt_result['answer'];
                                                                                                $answer = $stmt_result['answer'] == 1 ? 'Yes' : 'No';
                                                                                                $comment = ($stmt_result_comment) ? $stmt_result_comment['comment'] : 'N/A';


                                                                                                $question_details =
                                                                                                    "{
                                                                                                        question_id: $question_id,
                                                                                                        question:'$question',
                                                                                                        output_id: '$output_id',
                                                                                                        site_id: '0',
                                                                                                        comment:'$comment',
                                                                                                        answer:$answ,
                                                                                                    }";
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
                                                    ?>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                        // if ($proceed) {
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
                                                                <textarea name="comments" cols="" rows="7" class="form-control" id="comment" placeholder="Enter Comments if necessary" style="width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                                <div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
                                                    <div class="col-md-12 text-center">
                                                        <input type="hidden" name="projid" value="<?= $projid ?>">
                                                        <input type="hidden" name="store" value="store">
                                                        <button type="submit" class="btn btn-success">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </fieldset>
                                        <?php
                                        // }
                                        ?>
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
                                <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Project Inspection Checklist</h4>
                            </div>
                            <form class="form-horizontal" id="add_questions_form" action="" method="POST">
                                <div class="modal-body">
                                    <fieldset class="scheduler-border" id="tasks_div">
                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Add Inspection & Acceptance Checklists </legend>
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
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
// } else {
//     $results =  restriction();
//     echo $results;
// }

require('includes/footer.php');
?>
<script src="assets/js/inspection/inspection-acceptance-answer.js" defer></script>