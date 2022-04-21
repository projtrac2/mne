<?php
$Id = 6;
$subId = 22;
$cklstid = (isset($_GET['cklstid'])) ? base64_decode($_GET['cklstid'])  : header("Location: view-checklist.php");

$pageName  = "Add Checklist Information";
require('includes/head.php');
require('includes/header.php');
require('functions/inspection.php');
require('functions/department.php');
require('functions/indicator.php');

try {
    $editFormAction = $_SERVER['PHP_SELF'] . "?cklstid=" . base64_encode($cklstid);
    $departments = get_departments();
    $checklist = get_checklist($cklstid);

    if ($checklist) {
        $deptid = $checklist['department'];
        $output = $checklist['output'];
        $name = $checklist['name'];
        $questions = get_checklist_questions($cklstid);
        $indicators = get_output_indicators_by_department($deptid);
    } else {
        header("Location: view-checklist.php");
    }

    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addinspectionchecklist")) {
        $department = $_POST['department'];
        $output = $_POST['output'];
        $checklistname = $_POST['checklistname'];
        $current_date = date("Y-m-d");
        $user_name = $_POST['user_name'];
        $cklstid = $_POST['cklstid'];

        $url = 'view-checklist.php';
        if (!empty($checklistname) && !empty($department) && !empty($output)) {
            $response = $insertSQL->execute(array(':dept' => $department, ':op' => $output, ':name' => $checklistname, ':active' => 1, ':user' => $user_name, ':date' => $current_date, ':cklstid' => $cklstid));
            $insertSQL = $db->prepare("UPDATE tbl_inspection_checklist SET department = :dept, output = :op, name = :name, active = :active, updated_by = :user, date_updated = :date WHERE id=:cklstid");
            if ($response) {
                $deleteSQL = $db->prepare("DELETE FROM tbl_inspection_checklist_questions WHERE checklistname=:cklstid");
                $deleteSQL->execute(array(":cklstid" => $cklstid));

                for ($cnt = 0; $cnt < count($_POST['topic']); $cnt++) {
                    $topic = $_POST['topic'][$cnt];
                    $question = $_POST["question"][$cnt];
                    $qry2 = $db->prepare("INSERT INTO tbl_inspection_checklist_questions (checklistname,topic,question,created_by,date_created) VALUES (:cklstname,:topic,:question,:user,:date)");
                    $qry2->execute(array(':cklstname' => $cklstid, ':topic' => $topic, ':question' => $question, ':user' => $user_name, ':date' => $current_date));
                }

                $msg = 'Checklist Successfully Added';
                $results = "<script type=\"text/javascript\">
                        swal({
                            title: \"Success!\",
                            text: \" $msg\",
                            type: 'Success',
                            timer: 2000,
                        showConfirmButton: false });
                        setTimeout(function(){
                            window.location.href = '$url';
                        }, 2000);
                    </script>";
            } else {
                $msg = 'Sorry could not add the checklist!!';
                $results = "<script type=\"text/javascript\">
                        swal({
                            title: \"Error!\",
                            text: \" $msg \",
                            type: 'Danger',
                            timer: 3000,
                        showConfirmButton: false });
                        setTimeout(function(){
                            window.location.href = '$url';
                        }, 3000);
                    </script>";
            }
        } else {
            $msg = 'Sorry could not add the checklist!!';
            $results = "<script type=\"text/javascript\">
                    swal({
                        title: \"Error!\",
                        text: \" $msg \",
                        type: 'Danger',
                        timer: 3000,
                    showConfirmButton: false });
                    setTimeout(function(){
                        window.location.href = '$url';
                    }, 3000);
                </script>";
        }
    }
    echo $results;
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
}
?>
<div class="body">
    <div style="margin-top:5px">
        <form id="addinspectionchecklist" method="POST" name="addinspectionchecklist" action="" enctype="multipart/form-data" autocomplete="off">
            <div class="col-md-12" style="padding-left:0px">
                <div class="col-md-6">
                    <label>Division *:</label>
                    <div class="form-line">
                        <select name="department" id="department" class="form-control show-tick" data-live-search="true" style="border:#CCC thin solid; border-radius:5px" required>
                            <option value="" selected="selected" class="selection">....Select <?= $departmentlabel ?>....</option>
                            <?php
                            foreach ($departments as $department) {
                                $stid = $department['stid'];
                                $sector = $department['sector'];
                            ?>
                                <option value="<?php echo $stid ?>" <?php echo ($deptid == $stid) ? "selected" : ""; ?>>
                                    <?php echo $sector; ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <label>Output Indicator *:</label>
                    <div class="form-line">
                        <select name="output" id="output" class="form-control show-tick" data-live-search="true" style="border:#CCC thin solid; border-radius:5px" required>
                            <option value="" selected="selected" class="selection">....Select <?= $departmentlabel ?> First....</option>
                            <?php
                            foreach ($indicators as $indicator) {
                                $indid = $indicator['indid'];
                                $indicator_name = $indicator['indicator_name'];
                            ?>
                                <option value="<?php echo $indid ?>" <?php echo ($output == $indid) ? "selected" : ""; ?>>
                                    <?php echo $indicator_name; ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <label>Checklist Name *:</label>
                <div>
                    <input name="checklistname" type="text" class="form-control" id="checklistname" style="border:#CCC thin solid; border-radius: 5px" value="<?php echo $name ?>" required />
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card" style="margin-bottom:-20px">
                        <div class="header">
                            <h4><span class="label label-success"><i class="fa fa-plus-square" aria-hidden="true"></i> Add Checklist Questions</span></h4>
                        </div>
                        <div class="body">
                            <table class="table table-bordered" id="checklist">
                                <tr>
                                    <th style="width:30%">Topic</th>
                                    <th style="width:68%">Question</th>
                                    <th style="width:2%"><button type="button" name="add" title="Add another question" class="btn btn-success btn-sm add"><span class="glyphicon glyphicon-plus"></span></button></th>
                                </tr>
                                <?php
                                if ($questions) {
                                    foreach ($questions as $question) {
                                        $tc_id = $question['topic'];
                                        $quest = $question['question'];
                                ?>
                                        <tr>
                                            <td>
                                                <select name="topic[]" class="form-control topic" data-live-search="true" style="border:#CCC thin solid; border-radius:5px" required>
                                                    <option value="" selected="selected" class="selection">....Select Topic....</option>
                                                    <?php echo fill_unit_select_box($tc_id); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="question[]" class="form-control question" value="<?= $quest ?>" placeholder="Enter the your question here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
                                            </td>
                                            <td>
                                                <button type="button" name="remove" class="btn btn-danger btn-sm remove">
                                                    <span class="glyphicon glyphicon-minus"></span>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>

                                <?php
                                }
                                ?>

                            </table>
                            <div class="row clearfix">
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
                                    <input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
                                    <input name="cklstid" type="hidden" id="cklstid" value="<?php echo $cklstid; ?>" />
                                    <div class="btn-group">
                                        <input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="Update" />
                                    </div>
                                    <input type="hidden" name="MM_insert" value="addinspectionchecklist" />
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function() {
                    const url1 = '/ajax/inspection/index.php';
                    $('#department').on('change', function() {
                        var deptID = $(this).val();
                        if (deptID) {
                            $.ajax({
                                type: 'POST',
                                url: url1,
                                data: 'dept=' + deptID,
                                success: function(html) {
                                    $('#output').html(html);
                                }
                            });
                        } else {
                            $('#output').html('Select Department First');
                        }
                    });

                    $(document).on('click', '.add', function() {
                        var html = '';
                        html += '<tr>';
                        html += '<td><select name="topic[]" class="form-control topic" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px"  required><option value="" selected="selected" class="selection">....Select Topic....</option><?php echo fill_unit_select_box($db); ?></select></td>';
                        html += '<td><input type="text" name="question[]" class="form-control question"  placeholder="Enter the your question here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td>';
                        html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove"><span class="glyphicon glyphicon-minus"></span></button></td></tr>';
                        $('#checklist').append(html);
                    });

                    $(document).on('click', '.remove', function() {
                        $(this).closest('tr').remove();
                    });


                });
            </script>
        </form>
    </div>
</div>
<?php
include_once('includes/footer.php');
?>