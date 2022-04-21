<?php
$pageName = "Strategic Plans";
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
$pageTitle = $planlabelplural;

if ($permission) {
    try {
        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE projstage = 10  AND projstatus=11 OR projstatus=4");
        $query_rsProjects->execute();
        $row_rsProjects = $query_rsProjects->fetch();
        $total_rsProjects = $query_rsProjects->rowCount();


        if (isset($_POST['newitem']) && isset($_POST['newitem']) == "new") {
            $location = $_POST['location'];
            $subject = isset($_POST['subject']) ? $_POST['subject'] : 1;
            $observations = $_POST['observations'];
            $description = $_POST['description'];
            $projid = $_POST['projid'];
            $created_by  = $_POST['user_name'];
            $created_at = date("Y-m-d");
            $insertSQL = $db->prepare("INSERT INTO tbl_general_inspection (projid, location,subject, observations, created_at, created_by) VALUES(:projid, :location,:subject, :observations, :created_at, :created_by)");
            $result  = $insertSQL->execute(array(':projid' => $projid, ':location' => $location, ":subject" => $subject, ':observations' => $observations, ':created_at' => $created_at, ':created_by' => $created_by));
            $general_inspection_id  = $db->lastInsertId();

            if (isset($_POST['description'])) {
                if (isset($_POST['description'])) {
                    $countP = count($_POST["description"]);
                    $stage = 10;
                    for ($cnt = 0; $cnt < $countP; $cnt++) {
                        if (!empty($_FILES['observation_file']['name'][$cnt])) {
                            $purpose = $_POST["description"][$cnt];
                            $filename = basename($_FILES['observation_file']['name'][$cnt]);
                            $ext = substr($filename, strrpos($filename, '.') + 1);
                            $newname =  time() . $projid . "_" . $stage . "_" . $filename;
                            $filepath = "uploads/inspection/" . $newname;

                            if (!file_exists($filepath)) {
                                if (move_uploaded_file($_FILES['observation_file']['tmp_name'][$cnt], $filepath)) {
                                    $fname = $newname;
                                    $mt = $filepath;
                                    $filecategory = "Project Inspection";
                                    $qry1 = $db->prepare("INSERT INTO tbl_files (projid, projstage,general_inspection_id, filename, ftype, floc, fcategory, reason, uploaded_by, date_uploaded) VALUES (:projid, :stage,:general_inspection_id, :filename, :ftype, :floc,:fcategory,:reason,:uploaded_by, :date_uploaded)");
                                    $test = $qry1->execute(array(":projid" => $projid, ":stage" => $stage, ":general_inspection_id" => $general_inspection_id, ":filename" => $filename, ":ftype" => $ext, ":floc" => $mt, ":fcategory" => $filecategory, ":reason" => $purpose, ":uploaded_by" => $created_by, ":date_uploaded" => $created_at));
                                } else {
                                    echo "file culd not be  allowed";
                                }
                            } else {
                                $type = 'error';
                                $msg = 'File you are uploading already exists, try another file!!';
                                $results = "<script type=\"text/javascript\">
                                    swal({
                                    title: \"Error!\",
                                    text: \" $msg \",
                                    type: 'Danger',
                                    timer: 10000,
                                    showConfirmButton: false });
                                </script>";
                            }
                        }
                    }
                }
            }

            $url = "general-inspection.php";
            $msg = 'Project inspected.';
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
            echo $results;
        }
    } catch (PDOException $ex) {
        $results =  flashMessage("An error occurred: " . $ex->getMessage());
    }
?>
    <style>
        #links a {
            color: #FFFFFF;
            text-decoration: none;
        }

        hr {
            display: block;
            margin-top: 0.5em;
            margin-bottom: 0.5em;
            margin-left: auto;
            margin-right: auto;
            border-style: inset;
            border-width: 1px;
        }

        @media (min-width: 1200px) {
            .modal-lg {
                width: 90%;
            }
        }

        .bootstrap-select .dropdown-menu {
            margin: 15px 0 0;
            padding: 15px;
        }
    </style>
    <script src="assets/ckeditor/ckeditor.js"></script>

    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <i class="fa fa-columns" aria-hidden="true"></i>
                    <?php echo $pageTitle ?>
                    <div class="btn-group" style="float:right">
                        <div class="btn-group" style="float:right">
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
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example " id="manageItemTable">
                                    <thead>
                                        <tr class="bg-orange">
                                            <th style="width:7%">#</th>
                                            <th style="width:10%">Code</th>
                                            <th style="width:60%">Project </th>
                                            <th style="width:15">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($total_rsProjects > 0) {
                                            $counter = 0;
                                            do {
                                                $counter++;
                                                $projid = $row_rsProjects['projid'];
                                                $inspection = $row_rsProjects['projinspection'];

                                                $query_rsInspection = $db->prepare("SELECT * FROM tbl_general_inspection WHERE projid=:projid");
                                                $query_rsInspection->execute(array(":projid" => $projid));
                                                $row_rsInspection = $query_rsInspection->fetch();
                                                $total_rsInspection = $query_rsInspection->rowCount();
                                        ?>
                                                <tr class="projects" style="background-color:#eff9ca">
                                                    <td><?= $counter ?></td>
                                                    <td><?php echo $row_rsProjects['projcode'] ?> <?= $projid ?></td>
                                                    <td><?php echo $row_rsProjects['projname'] ?></td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Options <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a type="button" data-toggle="modal" data-target="#addFormModal" id="addFormModalBtn" onclick="inspect(<?php echo $projid ?>,<?php echo $inspection ?>)">
                                                                        <i class="fa fa-pencil-square"></i> </i> Inspect
                                                                    </a>
                                                                </li>
                                                                <?php
                                                                if ($total_rsInspection > 0) {
                                                                ?>
                                                                    <li>
                                                                        <a type="button" href="project-inspection-report.php?projid=<?= $projid ?>">
                                                                            <i class="fa fa-file-text"></i> Report
                                                                        </a>
                                                                    </li>
                                                                <?php
                                                                }
                                                                ?>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php
                                            } while ($row_rsProjects = $query_rsProjects->fetch());
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="5" style="color:#03A9F4;" align="center">No projects Under Implimentation Currently</td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- end body  -->
    <!-- add item -->
    <div class="modal fade" id="addFormModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="submitMilestoneForm" action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> Inspect </h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="body" id="">
                                        <div class="col-md-6">
                                            <label> <?= $level3label ?> *:</label>
                                            <div class="form-line">
                                                <select name="location" id="location" class="form-control" required>
                                                    <option value="">.... Select Project Locations from list ....</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="subject_comments">

                                        </div>
                                        <div class="col-md-12">
                                            <label class="control-label">Project Observations *: <font align="left" style="background-color:#eff2f4">(Briefly describe project progress, approaches and execution methods, and other relevant information that explains the condition and status of the project.) </font></label>
                                            <p align="left">
                                                <textarea name="observations" cols="45" rows="5" class="txtboxes" id="projdesc" required="required" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder="Briefly describe the goals and objectives of the project, the approaches and execution methods, resource estimates, people and organizations involved, and other relevant information that explains the need for project as well as the amount of work planned for implementation."></textarea>
                                                <script>
                                                    CKEDITOR.replace('projdesc', {
                                                        height: 200,
                                                        on: {
                                                            instanceReady: function(ev) {
                                                                // Output paragraphs as <p>Text</p>.
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
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" id="observations_table" style="width:100%">
                                                    <tr>
                                                        <th style="width:2%">#</th>
                                                        <th style="width:21%">Attach File</th>
                                                        <th style="width:75%">Description</th>
                                                        <th style="width:2%">
                                                            <button type="button" name="addplus" onclick="add_list();" title="Add another question" class="btn btn-success btn-sm">
                                                                <span class="glyphicon glyphicon-plus"></span>
                                                            </button>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>
                                                            <input type="file" name="observation_file[]" class="form-control" placeholder="Enter your observation here" style="height:35px;color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="description[]" class="form-control" placeholder="Enter your observation here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
                                                        </td>
                                                        <td>

                                                        </td>
                                                    </tr>
                                                </table>
                                                <script type="text/javascript">
                                                    function add_list() {
                                                        $rowno = $("#observations_table tr").length;
                                                        $rowno = $rowno + 1;
                                                        $listno = $rowno - 1;
                                                        $("#observations_table tr:last").after(`
                                             <tr id="row${$rowno}">
                                                <td>${$listno}</td>
                                                <td>
                                                   <input type="file" name="observation_file[]" class="form-control" placeholder="Upload Observation" style="height:35px;color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
                                                </td>
                                                <td>
                                                   <input type="text" name="description[]" id="issuedescription[]" class="form-control" placeholder="Enter your observation here" style="height:35px; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
                                                </td>
                                                <td>
                                                   <button type="button" class="btn btn-danger btn-sm"  onclick=delete_list("row${$rowno}")>
                                                      <span class="glyphicon glyphicon-minus"></span>
                                                   </button>
                                                </td>
                                             </tr>`);
                                                    }

                                                    function delete_list(rowno) {
                                                        $('#' + rowno).remove();
                                                    }
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /modal-body -->
                    <div class=" modal-footer">
                        <div class="col-md-12 text-center">
                            <input type="hidden" name="newitem" id="newitem" value="new">
                            <input type="hidden" name="projid" id="projid" value="">
                            <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                            <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                        </div>
                    </div> <!-- /modal-footer -->
                </form> <!-- /.form -->
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
<script type="text/javascript">
    function CallRiskAction(id) {
        $.ajax({
            type: 'post',
            url: 'callriskaction.php',
            data: {
                rskid: id
            },
            success: function(data) {
                $('#riskaction').html(data);
                $("#riskModal").modal({
                    backdrop: "static"
                });
            }
        });
    }

    $(document).ready(function() {
        $(".account").click(function() {
            var X = $(this).attr('id');
            if (X == 1) {
                $(".submenus").hide();
                $(this).attr('id', '0');
            } else {
                $(".submenus").show();
                $(this).attr('id', '1');
            }
        });
        //Mouseup textarea false
        $(".submenus").mouseup(function() {
            return false
        });
        $(".account").mouseup(function() {
            return false
        });

        //Textarea without editing.
        $(document).mouseup(function() {
            $(".submenus").hide();
            $(".account").attr('id', '');
        });

    });

    function inspect(projid, inspection) {
        if (projid) {
            $.ajax({
                type: "post",
                url: "ajax/inspection/general",
                data: {
                    "get_locations": "get_locations",
                    projid: projid
                },
                dataType: "html",
                success: function(response) {
                    $("#location").html(response);
                    $("#projid").val(projid);
                    if (inspection == 1) {
                        $("#subject_comments").html(`
                     <label>Subject *:</label>
                     <div class="form-line">
                        <select name="subject" id="subject" class="form-control" required>
                           <option value="">.... Select Subject ....</option>
                           <option value="1">General Comments</option>
                           <option value="2">Inspection Comments</option>
                        </select>
                     </div>`);
                    } else {
                        $("#subject_comments").html(``);
                    }
                }
            });
        } else {
            console.log("Project is not selected");
        }
    }
</script>