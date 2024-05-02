<?php
try {
    require('includes/head.php');
    $decode_progid = (isset($_GET['progid']) && !empty($_GET["progid"])) ? base64_decode($_GET['progid']) : "";
    if ($permission && $decode_progid != "") {
        $progid_array = explode("progid54321", $decode_progid);
        $progid = $progid_array[1];
        $stplane = $_GET['plan'];

        $query_rsProgram =  $db->prepare("SELECT * FROM tbl_programs WHERE progid =:progid");
        $query_rsProgram->execute(array(":progid" => $progid));
        $row_rsProgram = $query_rsProgram->fetch();
        $totalRows_rsProgram = $query_rsProgram->rowCount();

        if ($totalRows_rsProgram > 0) {
            $progname = $row_rsProgram['progname'];
            $description = $row_rsProgram['description'];
            $problem_statement = $row_rsProgram['problem_statement'];
            $projsector = $row_rsProgram['projsector'];
            $projdept = $row_rsProgram['projdept'];
            $directorate = $row_rsProgram['directorate'];

            if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editprogramfrm")) {
                $user_name = 3;
                $currentdate = date("Y-m-d");
                $progname = $_POST['progname'];
                $progid = $_POST['progid'];

                $url =  "view-program.php?plan=" . $stplane;
                $projsector = $_POST['department_id'];
                $projdept = $_POST['sector_id'];
                $directorate = $_POST['directorate_id'];
                $progdescription = $_POST['progdesc'];
                $program_statement = $_POST['progstatement'];

                $insertSQL = $db->prepare("UPDATE tbl_programs SET progname=:progname, description=:progdescription, problem_statement=:program_statement,projsector=:projsector, projdept=:projdept,directorate=:directorate,modifiedby=:proguser, datemodified=:progdate WHERE progid =:progid");
                $result  = $insertSQL->execute(array(':progname' => $progname, ':progdescription' => $progdescription, ':program_statement' => $program_statement, ':projsector' => $projsector, ':projdept' => $projdept, ":directorate" => $directorate, ':proguser' => $user_name, ':progdate' => $currentdate, ':progid' => $progid));

                if ($result) {
                    $msg = 'The Program was successfully updated.';
                    $results =
                        "<script type=\"text/javascript\">
                        swal({
                            title: \"Success!\",
                            text: \" $msg\",
                            type: 'Success',
                            timer: 2000,
                            icon:'success',
                            showConfirmButton: false
                        });
                        setTimeout(function(){
                                window.location.href = '$url';
                            }, 2000);
                    </script>";
                } else {
                    $msg = 'Error Editing Program.';
                    $results =
                        "<script type=\"text/javascript\">
                        swal({
                        title: \"Error!\",
                        text: \" $msg\",
                        type: 'Error',
                        timer: 2000,
                        icon:'error',
                        showConfirmButton: false });
                        setTimeout(function(){
                                window.location.reload(true);
                            }, 2000);
                    </script>";
                }
            }
?>

            <script src="assets/ckeditor/ckeditor.js"></script>
            <!-- start body  -->
            <section class="content">
                <div class="container-fluid">
                    <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                        <h4 class="contentheader">
                            <?= $icon . " " . $pageTitle ?>
                            <?= $results; ?>
                            <div class="btn-group" style="float:right">
                                <div class="btn-group" style="float:right">
                                    <button type="button" id="outputItemModalBtnrow" class="btn btn-warning" style="margin-left: 10px;" onclick="window.history.back()">
                                        Go Back
                                    </button>
                                </div>
                            </div>
                        </h4>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="body">
                                    <form id="addprogform" method="POST" name="addprogform" action="" enctype="multipart/form-data" autocomplete="off">
                                        <?= csrf_token_html(); ?>
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-plus-square" aria-hidden="true"></i> Edit Program Details</legend>
                                            <div class="col-md-12">
                                                <label for="">Program Name *:</label>
                                                <div class="form-line">
                                                    <input type="text" name="progname" id="progname" placeholder="Name Your Program" class="form-control" required value="<?= $progname ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="control-label">Program Problem Statement *:</label>
                                                <div class="form-line">
                                                    <input type="text" name="progstatement" id="progstatement" placeholder="Program Problem Statement" class="form-control" style="border:#CCC thin solid; border-radius: 5px" required value="<?= $problem_statement ?>">
                                                </div>
                                            </div>
                                            <?php
                                            if ($designation_id == 1) {
                                            ?>
                                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                    <label><?= $ministrylabel ?>*:</label>
                                                    <div class="form-line">
                                                        <select name="department_id" id="department_id" onchange="get_sections()" class="form-control show-tick" false style="border:#CCC thin solid; border-radius:5px">
                                                            <option value="" selected="selected" class="selection">....Select <?= $ministrylabel ?>....</option>
                                                            <?php
                                                            $query_rsDepartments =  $db->prepare("SELECT * FROM tbl_sectors WHERE parent =0 ORDER BY stid ASC");
                                                            $query_rsDepartments->execute();
                                                            $totalRows_rsDepartments = $query_rsDepartments->rowCount();
                                                            if ($totalRows_rsDepartments > 0) {
                                                                while ($row_rsDepartment = $query_rsDepartments->fetch()) {
                                                                    $sector = $row_rsDepartment['sector'];
                                                                    $sector_id = $row_rsDepartment['stid'];
                                                                    $selected = $projsector == $sector_id ? "selected" : "";
                                                            ?>
                                                                    <option value="<?= $sector_id ?>" <?= $selected ?>><?= $sector ?></option>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                    <label><?= $departmentlabel ?>*:</label>
                                                    <div class="form-line" id="">
                                                        <select name="sector_id" id="sector_id" onchange="get_directorate()" class="form-control show-tick" false style="border:#CCC thin solid; border-radius:5px">
                                                            <option value="" selected="selected" class="selection">....Select <?= $departmentlabel ?> first....</option>
                                                            <?php
                                                            $query_rsDepartments =  $db->prepare("SELECT * FROM tbl_sectors WHERE parent =:parent ORDER BY stid ASC");
                                                            $query_rsDepartments->execute(array(":parent" => $projsector));
                                                            $totalRows_rsDepartments = $query_rsDepartments->rowCount();
                                                            if ($totalRows_rsDepartments > 0) {
                                                                while ($row_rsDepartment = $query_rsDepartments->fetch()) {
                                                                    $sector = $row_rsDepartment['sector'];
                                                                    $sector_id = $row_rsDepartment['stid'];
                                                                    $selected = $projdept == $sector_id ? "selected" : "";
                                                            ?>
                                                                    <option value="<?= $sector_id ?>" <?= $selected ?>><?= $sector ?></option>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                    <label><?= $directoratelabel ?>*:</label>
                                                    <div class="form-line" id="">
                                                        <select name="directorate_id" id="directorate_id" class="form-control show-tick" false style="border:#CCC thin solid; border-radius:5px">
                                                            <option value="" selected="selected" class="selection">....Select <?= $directoratelabel ?> ....</option>
                                                            <?php
                                                            $query_rsDepartments =  $db->prepare("SELECT * FROM tbl_sectors WHERE parent =:parent ORDER BY stid ASC");
                                                            $query_rsDepartments->execute(array(":parent" => $projdept));
                                                            $totalRows_rsDepartments = $query_rsDepartments->rowCount();
                                                            if ($totalRows_rsDepartments > 0) {
                                                                while ($row_rsDepartment = $query_rsDepartments->fetch()) {
                                                                    $sector = $row_rsDepartment['sector'];
                                                                    $sector_id = $row_rsDepartment['stid'];
                                                                    $selected = $directorate == $sector_id ? "selected" : "";
                                                            ?>
                                                                    <option value="<?= $sector_id ?>" <?= $selected ?>><?= $sector ?></option>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            <?php
                                            } else {
                                            ?>
                                                <input type="hidden" name="department_id" id="department_id" value="<?= $department_id ?>">
                                                <input type="hidden" name="sector_id" id="sector_id" value="<?= $section_id ?>">
                                                <input type="hidden" name="directorate_id" id="directorate_id" value="<?= $directorate_id ?>">
                                            <?php
                                            } ?>
                                            <div class="col-md-12">
                                                <label class="control-label">Program Description *: <font align="left" style="background-color:#eff2f4">(Briefly describe goals and objectives of the program, approaches and execution methods, and other relevant information that explains the need for program.) </font></label>
                                                <p align="left">
                                                    <textarea name="progdesc" cols="45" rows="5" class="txtboxes" id="projdesc" required="required" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder="Briefly describe the goals and objectives of the project, the approaches and execution methods, resource estimates, people and organizations involved, and other relevant information that explains the need for project as well as the amount of work planned for implementation."><?= $description ?></textarea>
                                                    <script>
                                                        CKEDITOR.replace('projdesc', {
                                                            height: 200,
                                                            on: {
                                                                instanceReady: function(ev) {
                                                                    this.dataProcessor.writer.setRules('p', {
                                                                        indent: false,
                                                                        breakBeforeOpen: false,
                                                                        breakAfterOpen: false,
                                                                        breakBeforeClose: false,
                                                                        breakAfterClose: false
                                                                    });
                                                                    this.dataProcessor.writer.setRules('ol', {
                                                                        indent: false,
                                                                        breakBeforeOpen: false,
                                                                        breakAfterOpen: false,
                                                                        breakBeforeClose: false,
                                                                        breakAfterClose: false
                                                                    });
                                                                    this.dataProcessor.writer.setRules('ul', {
                                                                        indent: false,
                                                                        breakBeforeOpen: false,
                                                                        breakAfterOpen: false,
                                                                        breakBeforeClose: false,
                                                                        breakAfterClose: false
                                                                    });
                                                                    this.dataProcessor.writer.setRules('li', {
                                                                        indent: false,
                                                                        breakBeforeOpen: false,
                                                                        breakAfterOpen: false,
                                                                        breakBeforeClose: false,
                                                                        breakAfterClose: false
                                                                    });
                                                                }
                                                            }
                                                        });
                                                    </script>
                                                </p>
                                            </div>
                                            <div class="col-md-12" style="margin-top:15px" align="center">
                                                <input type="hidden" name="progid" value="<?= $progid ?>">
                                                <input type="hidden" name="MM_update" value="editprogramfrm">
                                                <button class="btn btn-success" type="submit">Update</button>
                                            </div>
                                        </fieldset>
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
    require('includes/footer.php');
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>

<script>
    const details = {
        ministry: '<?= $ministrylabel ?>',
        section: '<?= $departmentlabel ?>',
        directorate: '<?= $directoratelabel ?>',
        edit: 1,
    }
</script>
<script src="assets/js/programs/index.js"></script>