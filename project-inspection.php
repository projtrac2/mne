<?php
require('includes/head.php');
if ($permission) {
    try {
        $decode_projid = (isset($_GET['projid']) && !empty($_GET["projid"])) ? base64_decode($_GET['projid']) : "";
        $projid_array = explode("projid54321", $decode_projid);
        $projid = $projid_array[1];

        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.deleted='0' and p.projid=:projid");
        $query_rsProjects->execute(array(":projid" => $projid));
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();
        $progid = $project = $sectorid = "";
        $project_name = ($totalRows_rsProjects > 0) ? $row_rsProjects['projname'] : "";
        $projcode = ($totalRows_rsProjects > 0) ? $row_rsProjects['projcode'] : "";

        $query_rsQuestions_pending = $db->prepare("SELECT * FROM tbl_inspection_checklist_questions WHERE projid=:projid AND answer=0");
        $query_rsQuestions_pending->execute(array(":projid" => $projid));
        $totalRows_rsQuestions_pending = $query_rsQuestions_pending->rowCount();

        $query_rsQuestions = $db->prepare("SELECT * FROM tbl_inspection_checklist_questions WHERE projid=:projid AND answer > 0");
        $query_rsQuestions->execute(array(":projid" => $projid));
        $totalRows_rsQuestions = $query_rsQuestions->rowCount();

        if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addprojectfrm")) {
            $projid = $_POST['projid'];
            $comments =  $_POST['comments'];
            $approval_name = $_POST['approval_name'];

            $datecreated = date('Y-m-d');


            if (isset($_POST['attachmentpurpose'])) {
                $countP = count($_POST["attachmentpurpose"]);
                $stage = 1;
                for ($cnt = 0; $cnt < $countP; $cnt++) {
                    if (!empty($_FILES['monitorattachment']['name'][$cnt])) {
                        $purpose = $_POST["attachmentpurpose"][$cnt];
                        $filename = basename($_FILES['monitorattachment']['name'][$cnt]);
                        $ext = substr($filename, strrpos($filename, '.') + 1);
                        if (($ext != "exe") && ($_FILES["monitorattachment"]["type"][$cnt] != "application/x-msdownload")) {
                            $newname = time() . '_' . $projid . "_" . $stage . "_" . $filename;
                            $filepath = "uploads/payments/" . $newname;
                            if (!file_exists($filepath)) {
                                if (move_uploaded_file($_FILES['monitorattachment']['tmp_name'][$cnt], $filepath)) {
                                    $fname = $newname;
                                    $mt = $filepath;
                                    $filecategory = "Project Inspection";
                                    $qry1 = $db->prepare("INSERT INTO tbl_files (projid, projstage, filename, ftype, floc, fcategory, reason, uploaded_by, date_uploaded) VALUES (:projid, :stage, :filename, :ftype, :floc,:fcategory,:reason,:uploaded_by, :date_uploaded)");
                                    $results =  $qry1->execute(array(":projid" => $projid, ":stage" => $stage, ":filename" => $filename, ":ftype" => $ext, ":floc" => $mt, ":fcategory" => $filecategory, ":reason" => $purpose, ":uploaded_by" => $user_name, ":date_uploaded" => $datecreated));
                                    if ($results) {
                                        $type = true;
                                        $msg =  "Successfully uploaded files";
                                    } else {
                                        $msg =  "Error uploading files";
                                    }
                                } else {
                                    $msg =  "file culd not be  allowed";
                                }
                            } else {
                                $msg = 'File you are uploading already exists, try another file!!';
                            }
                        } else {
                            $msg = 'This file type is not allowed, try another file!!';
                        }
                    }
                }
            }

            $query_rsPayement_reuests =  $db->prepare("SELECT * FROM  tbl_contractor_payment_requests WHERE status=1 AND stage=2 AND projid=:projid ");
            $query_rsPayement_reuests->execute(array(":projid" => $projid));
            $total_rsPayement_reuests = $query_rsPayement_reuests->rowCount();

            $msg = 'Approval Error';
            if ($total_rsPayement_reuests > 0) {
                $counter = 0;
                $rows_rsPayement_reuests = $query_rsPayement_reuests->fetch();
                $request_id = $rows_rsPayement_reuests['id'];
                $projid = $rows_rsPayement_reuests['projid'];
                $status = $approval_name == 1 ? 1 : 3;
                $stage = 3;
                $sql = $db->prepare("UPDATE tbl_contractor_payment_requests SET  stage = :stage, cof=:cof, cof_action_date=:cof_action_date WHERE  id = :request_id");
                $results  = $sql->execute(array(":stage" => $stage, ":cof" => $created_by, "cof_action_date" => $created_at, ":request_id" => $request_id));
                if ($results) {
                    $sql = $db->prepare("INSERT INTO tbl_contractor_payment_request_comments (request_id,stage,status,comments,created_by,created_at) VALUES (:request_id,:stage,:status,:comments,:created_by,:created_at)");
                    return $sql->execute(array(":request_id" => $request_id, ":stage" => $stage, ":status" => $status, ":comments" => $comments, ":created_by" => $created_by, ":created_at" => $created_at));
                }
                $msg = 'Project approval Successfully';
            }

            $results = "<script type=\"text/javascript\">
                swal({
                    title: \"Success!\",
                    text: \" $msg\",
                    type: 'Success',
                    timer: 2000,
                    'icon':'success',
                showConfirmButton: false });
                setTimeout(function(){
                    window.location.href = 'general-project-progress';
                }, 2000);
            </script>";
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
                                        <li class="list-group-item list-group-item list-group-item-action active">Project Name: <?= $project_name ?> </li>
                                        <li class="list-group-item"><strong>Code: </strong> <?= $projcode ?> </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <ul class="nav nav-tabs" style="font-size:14px">
                                    <li class="active">
                                        <a data-toggle="tab" href="#menu1">
                                            <i class="fa fa-caret-square-o-up bg-deep-purple" aria-hidden="true"></i>
                                            Pending Questions &nbsp;&nbsp;<span class="badge bg-orange" id="total-programs"><?= $totalRows_rsQuestions_pending ?></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#menu2">
                                            <i class="fa fa-caret-square-o-right bg-indigo" aria-hidden="true"></i>
                                            Answered Questions &nbsp;<span class="badge bg-indigo"><?= $totalRows_rsQuestions ?></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <fieldset class="scheduler-border row setup-content" style="padding:10px">
                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Questions</legend>

                                        <!-- ============================================================== -->
                                        <!-- Start Page Content -->
                                        <!-- ============================================================== -->
                                        <div class="tab-content">
                                            <div id="menu1" class="tab-pane fade in active">
                                                <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                                                    <h4 style="width:100%"><i class="fa fa-hourglass-half fa-sm" style="font-size:25px;color:#6c0eb0"></i> Pending Questions</h4>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
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
                                                            if ($totalRows_rsQuestions > 0) {
                                                                $counter = 0;
                                                                while ($row = $query_rsQuestions_pending->fetch()) {
                                                                    $counter++;
                                                                    $question_id = $row['id'];
                                                                    $question = htmlspecialchars($row['question']);
                                                                    $answer = $row['answer'];
                                                                    $question_details = "{
                                                                        question_id: $question_id,
                                                                        question:'$question',
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
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div id="menu2" class="tab-pane">
                                                <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                                                    <h4 style="width:100%"><i class="fa fa-money" style="font-size:25px;color:indigo"></i> Answered Questions</h4>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:5%" align="center">#</th>
                                                                <th style="width:40%">Item</th>
                                                                <th style="width:5%">Answer</th>
                                                                <th style="width:40%">Comment</th>
                                                                <th style="width:10%">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php

                                                            $question_arr = [];
                                                            if ($totalRows_rsQuestions > 0) {
                                                                $counter = 0;
                                                                while ($row = $query_rsQuestions->fetch()) {
                                                                    $counter++;
                                                                    $question_id = $row['id'];
                                                                    $question = $row['question'];
                                                                    $comment = htmlspecialchars($row['comment']);
                                                                    $answ = $row['answer'];
                                                                    $answer = $row['answer'] == 1 ? 'Yes' : 'No';
                                                                    $question_arr[] = $answ;

                                                                    $question_details = "{
                                                                        question_id: $question_id,
                                                                        question:'$question',
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

                                                            $approve = in_array(2, $question_arr) ? 2 : 1;
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ============================================================== -->
                                        <!-- End PAge Content -->
                                        <!-- ============================================================== -->
                                    </fieldset>

                                    <?php
                                    if ($totalRows_rsQuestions_pending == 0) {
                                    ?>
                                        <form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                                            <fieldset class="scheduler-border">
                                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                    <i class="fa fa-comment" aria-hidden="true"></i> Inspection & Acceptance Remark(s)
                                                </legend>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label class="control-label">Remarks *:</label>
                                                    <div class="form-line">
                                                        <textarea name="comments" cols="" rows="7" class="form-control" id="comment" placeholder="Enter Comments if necessary" style="width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required></textarea>
                                                    </div>
                                                </div>
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

                                            <div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
                                                <div class="col-md-12 text-center">
                                                    <input type="hidden" name="MM_insert" value="addprojectfrm">
                                                    <input type="hidden" name="projid" value="<?= $projid ?>">
                                                    <input type="hidden" name="approval_name" value="<?= $approve ?>">
                                                    <button type="submit" class="btn btn-success">Approve</button>
                                                </div>
                                            </div>
                                        </form>
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
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Project Inspection Checklist</h4>
                </div>
                <form class="form-horizontal" id="add_questions_form" action="" method="POST">
                    <div class="modal-body">
                        <fieldset class="scheduler-border" id="tasks_div">
                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Add Inspection & Acceptance Checklists </legend>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <ul class="list-group">
                                    <li class="list-group-item list-group-item list-group-item-action active">Project Name: <span id="projname"><?= $project_name ?></span> </li>
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
                            <input type="hidden" name="projid" id="projid" value="">
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

require('includes/footer.php');
?>

<script>
    const ajax_url1 = "ajax/inspection/general";
    $(document).ready(function() {
        $("#add_questions_form").submit(function(e) {
            e.preventDefault();
            $("#tag-form-submit").prop("disabled", true);
            $.ajax({
                type: "post",
                url: ajax_url1,
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        success_alert("Record saved successfully");
                    } else {
                        error_alert("Record could not be saved successfully");
                    }
                    $("#tag-form-submit-2").prop("disabled", false);
                    setTimeout(() => {
                        location.reload(true);
                    }, 3000);
                }
            });
        });
    });
    const get_details = (details) => {
        $("#project_approve_div").hide();
        $("#question_id").val(details.question_id);
        $("#question").html(details.question);
        $("#comment").removeAttr('required');
        $("#comment").html("");
        $(`#question1`).prop("checked", false);
        $(`#question2`).prop("checked", false);
        if (details.answer == 2) {
            $(`#question2`).prop("checked", true);
            $("#project_approve_div").show();
            $("#comment").html(details.comment);
            $("#comment").attr('required', 'required');
        } else if (details.answer == 1) {
            $(`#question1`).prop("checked", true);
        }
    }

    const check_box = (id) => {
        if (id == 1) {
            $("#project_approve_div").hide();
            $("#comment").removeAttr('required');
        } else {
            $("#project_approve_div").show();
            $("#comment").attr('required', 'required');
        }
    }


    function add_attachment() {
        var rand = Math.floor(Math.random() * 6) + 1;
        var rowno = $("#attachments_table tr").length + "" + rand + "" + Math.floor(Math.random() * 7) + 1;
        $("#attachments_table tr:last").after(`
        <tr id="rw${rowno}">
            <td>1</td>
            <td>
                <input type="file" name="monitorattachment[]"  id="monitorattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
            </td>
            <td>
                <input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control"  placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"/>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm"  onclick=delete_attach("rw${rowno}")>
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>
    `);
        number_table();
    }

    function delete_attach(rownm) {
        $("#" + rownm).remove();
        number_table();
    }

    function number_table() {
        $("#attachments_table tr").each(function(idx) {
            $(this)
                .children()
                .first()
                .html(idx + 1);
        });
    }
</script>