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

        if ($designation_id == 1) {
            $role = 2;
        } else {
            $query_rsMembers = $db->prepare("SELECT m.role FROM tbl_projmembers m INNER JOIN tbl_project_team_roles r  ON m.role = r.id  WHERE projid=:projid  AND stage=10 and team_type=4 AND responsible=:user_id");
            $query_rsMembers->execute(array(":projid" => $projid, ":user_id" => $user_name));
            $row_rsMembers = $query_rsMembers->fetch();
            $total_rsMembers = $query_rsMembers->rowCount();
            $role =   $total_rsMembers > 0 ? $row_rsMembers['role'] : '';
        }

        if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addprojectfrm")) {
            $projid = $_POST['projid'];
            $datecreated = date('Y-m-d');
            if (isset($_POST['comments'])) {
                $observ = $_POST['comments'];
                $SQLinsert = $db->prepare("INSERT INTO tbl_monitoring_observations (projid,output_id,milestone_id,site_id,task_id,subtask_id,formid,observation,observation_type,created_at,created_by) VALUES (:projid,:output_id,:milestone_id,:site_id,:task_id,:subtask_id,:formid,:observation,:observation_type,:created_at,:created_by)");
                $Rst  = $SQLinsert->execute(array(":projid" => $projid, ":output_id" => 0, ":milestone_id" => 0, ":site_id" => 0, ":task_id" => 0, ":subtask_id" => 0, ':formid' => $datecreated, ':observation' => $observ, ":observation_type" => 5, ':created_at' => $datecreated, ':created_by' => $user_name));
            }

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
                                    $filecategory = "Project Observations";
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

            $results = "<script type=\"text/javascript\">
                swal({
                    title: \"Success!\",
                    text: \" $msg\",
                    type: 'Success',
                    timer: 2000,
                    'icon':'success',
                showConfirmButton: false });
                setTimeout(function(){
                    window.location.href = 'project-output-monitoring-checklist';
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
                            <?php
                            if ($role == 2) {
                            ?>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <ul class="nav nav-tabs" style="font-size:14px">
                                        <li class="active">
                                            <a data-toggle="tab" href="#menu1">
                                                <i class="fa fa-caret-square-o-up bg-deep-purple" aria-hidden="true"></i>
                                                Observations &nbsp;&nbsp;<span class="badge bg-orange" id="total-programs">|</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#menu2">
                                                <i class="fa fa-caret-square-o-right bg-indigo" aria-hidden="true"></i>
                                                Media &nbsp;<span class="badge bg-indigo">|</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <?php
                                    if ($role == 2) {
                                    ?>
                                        <fieldset class="scheduler-border row setup-content" style="padding:10px">
                                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Observations</legend>
                                            <!-- ============================================================== -->
                                            <!-- Start Page Content -->
                                            <!-- ============================================================== -->
                                            <div class="tab-content">
                                                <div id="menu1" class="tab-pane fade in active">
                                                    <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                                                        <h4 style="width:100%"><i class="fa fa-hourglass-half fa-sm" style="font-size:25px;color:#6c0eb0"></i> Observations</h4>
                                                    </div>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width:5%" align="center">#</th>
                                                                    <th style="width:80%">Observation</th>
                                                                    <th style="width:10%">Created By</th>
                                                                    <th style="width:10%">Role</th>
                                                                    <th style="width:10%">Created At</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $query_rsObservations_pending = $db->prepare("SELECT * FROM tbl_monitoring_observations WHERE projid=:projid AND observation_type=5");
                                                                $query_rsObservations_pending->execute(array(":projid" => $projid));
                                                                $totalRows_rsObservations_pending = $query_rsObservations_pending->rowCount();
                                                                if ($totalRows_rsObservations_pending > 0) {
                                                                    $counter = 0;
                                                                    while ($row = $query_rsObservations_pending->fetch()) {
                                                                        $counter++;
                                                                        $observation = $row['observation'];
                                                                        $created_at = $row['created_at'];
                                                                        $created_by = $row['created_by'];
                                                                        $query_rsPMbrs = $db->prepare("SELECT t.*, t.email AS email, tt.title AS ttitle, u.userid FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title WHERE userid = :standin_responsible ORDER BY ptid ASC");
                                                                        $query_rsPMbrs->execute(array(":standin_responsible" => $created_by));
                                                                        $row_rsPMbrs = $query_rsPMbrs->fetch();
                                                                        $count_row_rsPMbrs = $query_rsPMbrs->rowCount();
                                                                        $responsible =  $count_row_rsPMbrs > 0 ?  $row_rsPMbrs['ttitle'] . ". " . $row_rsPMbrs['fullname'] : "";

                                                                        $query_rsMembers = $db->prepare("SELECT r.role FROM tbl_projmembers m INNER JOIN tbl_project_team_roles r  ON m.role = r.id  WHERE projid=:projid  AND stage=10 and team_type=4 AND responsible=:user_id");
                                                                        $query_rsMembers->execute(array(":projid" => $projid, ":user_id" => $created_by));
                                                                        $row_rsMembers = $query_rsMembers->fetch();
                                                                        $total_rsMembers = $query_rsMembers->rowCount();
                                                                        $role =   $total_rsMembers > 0 ? $row_rsMembers['role'] : '';
                                                                ?>
                                                                        <tr>
                                                                            <td><?= $counter ?></td>
                                                                            <td><?= $observation ?></td>
                                                                            <td><?= $responsible ?></td>
                                                                            <td><?= $role ?></td>
                                                                            <td><?= date('d M Y', strtotime($created_at)) ?></td>
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
                                                        <h4 style="width:100%"><i class="fa fa-money" style="font-size:25px;color:indigo"></i> Media</h4>
                                                    </div>
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <ul class="nav nav-tabs" style="font-size:14px">
                                                                <li class="active">
                                                                    <a data-toggle="tab" href="#menu6"><i class="fa fa-file-text-o bg-green" aria-hidden="true"></i> Documents &nbsp;<span class="badge bg-green">|</span></a>
                                                                </li>
                                                                <li>
                                                                    <a data-toggle="tab" href="#menu7"><i class="fa fa-file-image-o bg-blue" aria-hidden="true"></i> Photos &nbsp;<span class="badge bg-blue">|</span></a>
                                                                </li>
                                                                <li>
                                                                    <a data-toggle="tab" href="#menu8"><i class="fa fa-file-video-o bg-orange" aria-hidden="true"></i> Videos &nbsp;<span class="badge bg-orange">|</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="body">
                                                            <div class="tab-content">
                                                                <div id="menu6" class="tab-pane fade in active">
                                                                    <div class="row clearfix">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <div class="table-responsive">
                                                                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                                                    <thead>
                                                                                        <tr class="bg-grey">
                                                                                            <th width="5%"><strong>#</strong></th>
                                                                                            <th width="35%"><strong>Name</strong></th>
                                                                                            <th width="35%"><strong>Purpose</strong></th>
                                                                                            <th width="10%"><strong>Stage</strong></th>
                                                                                            <th width="15%"><strong>Action</strong></th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <?php
                                                                                        $query_project_docs = $db->prepare("SELECT * FROM tbl_files WHERE projid=:projid AND  fcategory ='Project Observations' AND (ftype<>'jpg' and ftype<>'jpeg' and ftype<>'png' and ftype<>'mp4')");
                                                                                        $query_project_docs->execute(array(":projid" => $projid));
                                                                                        $count_project_docs = $query_project_docs->rowCount();
                                                                                        if ($count_project_docs > 0) {
                                                                                            $rowno = 0;
                                                                                            while ($rows_project_docs = $query_project_docs->fetch()) {
                                                                                                $rowno++;
                                                                                                $projstageid = $rows_project_docs['projstage'];
                                                                                                $filename = $rows_project_docs['filename'];
                                                                                                $filepath = $rows_project_docs['floc'];
                                                                                                $purpose = $rows_project_docs['reason'];

                                                                                                $query_project_stage = $db->prepare("SELECT stage FROM tbl_project_workflow_stage WHERE id=:projstageid");
                                                                                                $query_project_stage->execute(array(":projstageid" => $projstageid));
                                                                                                $rows_project_stage = $query_project_stage->fetch();
                                                                                                $projstage = $rows_project_stage['stage'];
                                                                                        ?>
                                                                                                <tr>
                                                                                                    <td width="5%"><?= $rowno; ?></td>
                                                                                                    <td width="35%"><?= $filename; ?></td>
                                                                                                    <td width="35%"><?= $purpose; ?></td>
                                                                                                    <td width="10%"><?= $projstage; ?></td>
                                                                                                    <td width="15%">
                                                                                                        <a href="<?= $filepath; ?>" download>Download</a>
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
                                                                    </div>
                                                                </div>
                                                                <div id="menu7" class="tab-pane fade">
                                                                    <div class="row clearfix">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <div class="table-responsive">
                                                                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                                                    <thead>
                                                                                        <tr class="bg-grey">
                                                                                            <th width="5%"><strong>#</strong></th>
                                                                                            <th width="5%"><strong>Photo</strong></th>
                                                                                            <th width="40%"><strong>Name</strong></th>
                                                                                            <th width="40%"><strong>Purpose</strong></th>
                                                                                            <th width="10%"><strong>Stage</strong></th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <?php
                                                                                        $query_project_photos = $db->prepare("SELECT * FROM tbl_files WHERE projid=:projid AND  fcategory ='Project Observations' AND (ftype='jpg' or ftype='jpeg' or ftype='png')");
                                                                                        $query_project_photos->execute(array(":projid" => $projid));
                                                                                        $count_project_photos = $query_project_photos->rowCount();
                                                                                        if ($count_project_photos > 0) {
                                                                                            $rowno = 0;
                                                                                            while ($rows_project_photos = $query_project_photos->fetch()) {
                                                                                                $rowno++;
                                                                                                $fileid = $rows_project_photos['fid'];
                                                                                                $projstageid = $rows_project_photos['projstage'];
                                                                                                $filename = $rows_project_photos['filename'];
                                                                                                $filepath = $rows_project_photos['floc'];
                                                                                                $purpose = $rows_project_photos['reason'];
                                                                                                $fileid = base64_encode("projid54321{$fileid}");

                                                                                                $photo = '<a href="project-gallery.php?photo=' . $fileid . '" class="gallery-item">
                                                                                                            <img class="img-fluid" src="' . $filepath . '" alt="Click to view the photo" style="width:30px; height:30px; margin-bottom:0px"/>
                                                                                                        </a>';

                                                                                                $query_project_stage = $db->prepare("SELECT stage FROM tbl_project_workflow_stage WHERE id=:projstageid");
                                                                                                $query_project_stage->execute(array(":projstageid" => $projstageid));
                                                                                                $rows_project_stage = $query_project_stage->fetch();
                                                                                                $projstage = $rows_project_stage['stage'];
                                                                                        ?>
                                                                                                <tr>
                                                                                                    <td width="5%"><?= $rowno; ?></td>
                                                                                                    <td width="5%"><?= $photo; ?></td>
                                                                                                    <td width="40%"><?= $filename; ?></td>
                                                                                                    <td width="40%"><?= $purpose; ?></td>
                                                                                                    <td width="10%"><?= $projstage; ?></td>
                                                                                                </tr>
                                                                                        <?php
                                                                                            }
                                                                                        }
                                                                                        ?>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="menu8" class="tab-pane fade">
                                                                    <div class="row clearfix">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <div class="table-responsive">
                                                                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                                                    <thead>
                                                                                        <tr class="bg-grey">
                                                                                            <th width="5%"><strong>#</strong></th>
                                                                                            <th width="35%"><strong>Name</strong></th>
                                                                                            <th width="35%"><strong>Purpose</strong></th>
                                                                                            <th width="10%"><strong>Stage</strong></th>
                                                                                            <th width="15%"><strong>Action</strong></th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <?php
                                                                                        $query_project_videos = $db->prepare("SELECT * FROM tbl_files WHERE projid=:projid AND  fcategory ='Project Observations' AND ftype='mp4'");
                                                                                        $query_project_videos->execute(array(":projid" => $projid));
                                                                                        $count_project_videos = $query_project_videos->rowCount();
                                                                                        if ($count_project_videos > 0) {
                                                                                            $rowno = 0;
                                                                                            while ($rows_project_videos = $query_project_videos->fetch()) {
                                                                                                $rowno++;
                                                                                                $projstageid = $rows_project_videos['projstage'];
                                                                                                $filename = $rows_project_videos['filename'];
                                                                                                $filepath = $rows_project_videos['floc'];
                                                                                                $purpose = $rows_project_videos['reason'];

                                                                                                $query_project_stage = $db->prepare("SELECT stage FROM tbl_project_workflow_stage WHERE id=:projstageid");
                                                                                                $query_project_stage->execute(array(":projstageid" => $projstageid));
                                                                                                $rows_project_stage = $query_project_stage->fetch();
                                                                                                $projstage = $rows_project_stage['stage'];
                                                                                        ?>
                                                                                                <tr>
                                                                                                    <td width="5%"><?= $rowno; ?></td>
                                                                                                    <td width="35%"><?= $filename; ?></td>
                                                                                                    <td width="35%"><?= $purpose; ?></td>
                                                                                                    <td width="10%"><?= $projstage; ?></td>
                                                                                                    <td width="15%">
                                                                                                        <a href="<?= $filepath; ?>" watch>Watch</a>
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
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- ============================================================== -->
                                            <!-- End PAge Content -->
                                            <!-- ============================================================== -->
                                        </fieldset>
                                    <?php
                                    }
                                    ?>
                                    <form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                                <i class="fa fa-comment" aria-hidden="true"></i> Observations
                                            </legend>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label class="control-label">Observations *:</label>
                                                <div class="form-line">
                                                    <textarea name="comments" cols="" rows="7" class="form-control" id="comment" placeholder="Enter Observations" style="width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required></textarea>
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
                                                <button type="submit" class="btn btn-success">Submit</button>
                                            </div>
                                        </div>
                                    </form>

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

require('includes/footer.php');
?>

<script>
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