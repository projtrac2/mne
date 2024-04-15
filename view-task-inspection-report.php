<?php
try {
require('includes/head.php');
if ($permission) {
    $pageTitle = "Task Inspection Report";
        $task_name = $projname = $projcode = $projstatus = $locationName = '';
        if (isset($_GET['task_id'])) {
            $task_id = base64_decode(htmlspecialchars(trim($_GET['task_id'])));
            $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE tkid=:task_id");
            $query_rsTasks->execute(array(":task_id" => $task_id));
            $row_rsTasks = $query_rsTasks->fetch();
            $totalrow_rsTasks = $query_rsTasks->rowCount();

            if ($totalrow_rsTasks > 0) {
                $task_name = $row_rsTasks['task'];
                $projid = $row_rsTasks['projid'];

                $query_rsTPList = $db->prepare("SELECT projname, projcode FROM tbl_projects WHERE projid=:projid");
                $query_rsTPList->execute(array(":projid" => $projid));
                $row_rsTPList = $query_rsTPList->fetch();
                $totalrow_rsTPList = $query_rsTPList->rowCount();
                $projname = $row_rsTPList['projname'];
                $projcode = $row_rsTPList['projcode'];
            }
        }
    
?>

    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <i class="fa fa-columns" aria-hidden="true"></i>
                    <?php echo $pageTitle ?>
                    <div class="btn-group" style="float:right">
                        <button onclick="history.back()" type="button" class="btn bg-orange waves-effect" style="float:right; margin-top:-5px">Go Back </button>
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
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border" style="background-color:#c7e1e8;  border:#CCC thin dashed; border-radius:3px">Details</legend>
                                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                    <label>Project Code:</label>
                                    <input type="text" class="form-control" value="<?php echo $projcode; ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px" />
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                                    <label>Project Name:</label>
                                    <input type="text" class="form-control" value="<?php echo $projname; ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px" />
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Task Name:</label>
                                    <input type="text" class="form-control" value="<?php echo $task_name; ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px" />
                                </div>
                            </fieldset>


                            <?php

                            $query_rsInspection_details = $db->prepare("SELECT * FROM tbl_general_inspection WHERE projid = :projid AND task_id=:task_id AND origin=2 ORDER BY created_at ASC");
                            $query_rsInspection_details->execute(array(":projid" => $projid, ":task_id" => $task_id));
                            $row_rsInspection_details = $query_rsInspection_details->fetch();
                            $totalRows_rsInspection_details = $query_rsInspection_details->rowCount();

                            if ($totalRows_rsInspection_details > 0) {
                                do {
                                    $inspection_id = $row_rsInspection_details['id'];
                                    $location = $row_rsInspection_details['location'];
                                    $observations = $row_rsInspection_details['observations'];
                                    $created_by = $row_rsInspection_details['created_by'];
                                    $created_at = $row_rsInspection_details['created_at'];
                                    $inspectionid = $row_rsInspection_details['inspectionid'];

                                    $query_rsState = $db->prepare("SELECT * FROM tbl_state WHERE id = :location");
                                    $query_rsState->execute(array(":location" => $location));
                                    $row_rsState = $query_rsState->fetch();
                                    $totalRows_rsState = $query_rsState->rowCount();
                                    $state = $row_rsState['state'];
                                    $stid = $row_rsState['id'];

                                    $query_rsUsers = $db->prepare("SELECT t.title, t.fullname FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid WHERE userid = '$created_by'");
                                    $query_rsUsers->execute();
                                    $row_rsUsers = $query_rsUsers->fetch();
                                    $totalRows_rsUsers = $query_rsUsers->rowCount();



                                    if ($totalRows_rsState > 0) {
                                        $nm++;
                            ?>
                                        <div class="panel panel-primary">
                                            <div class="panel-heading list-group-item list-group-item list-group-item-action active collapse careted" data-toggle="collapse" data-target=".output<?php echo $inspection_id ?>">
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                                <strong> Inspection <?= $nm ?>: <?= $inspectionid ?>
                                                    <span class="">
                                                        <?= $state ?>
                                                    </span>
                                                </strong>
                                            </div>
                                            <div class="collapse output<?php echo $inspection_id ?>" style="padding:5px">
                                                <fieldset class="scheduler-border">
                                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Project Inspection Details</legend>
                                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                        <label>Location :</label>
                                                        <div class="form-line">
                                                            <input type="text" class="form-control" value="<?php echo $state; ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px" />
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                        <label>Date Recorded: </label>
                                                        <div class="form-line">
                                                            <input type="text" class="form-control" value="<?php echo $created_at; ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px" />
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                        <label>Recorded By :</label>
                                                        <div class="form-line">
                                                            <input type="text" class="form-control" value="<?php echo $row_rsUsers['title'] . "." . $row_rsUsers['fullname']; ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px" />
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <label>Observations :</label>
                                                        <div class="form-line">
                                                            <p><?php echo strip_tags($observations) ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12"></div>
                                                    <?php
                                                    $origin = "Inspection";
                                                    $query_issues = $db->prepare("SELECT * FROM tbl_projissues WHERE formid=:formid");
                                                    $query_issues->execute(array("formid" => $inspectionid));
                                                    $totalRows_issues = $query_issues->rowCount();

                                                    if ($totalRows_issues > 0) {
                                                    ?>
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <fieldset class="scheduler-border">
                                                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Inspection Issues</legend>
                                                                <!-- Task Checklist Questions -->
                                                                <div class="row clearfix">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="card" style="margin-bottom:-20px">
                                                                            <div class="body">
                                                                                <table class="table table-bordered" id="issues_table<?= $opid ?>" style="width:100%">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th style="width:2%">#</th>
                                                                                            <th style="width:23%">Issue Category</th>
                                                                                            <th style="width:75%">Issue Description</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <?php
                                                                                        $sn = 0;
                                                                                        while ($row_issues = $query_issues->fetch()) {
                                                                                            $sn++;
                                                                                            $issuecatid = $row_issues["risk_category"];
                                                                                            $issuedescription = $row_issues["observation"];

                                                                                            $query_issuecat = $db->prepare("SELECT category FROM tbl_projrisk_categories WHERE rskid = '$issuecatid'");
                                                                                            $query_issuecat->execute();
                                                                                            $row_issuecat = $query_issuecat->fetch();
                                                                                            $issuecategory = $row_issuecat["category"];
                                                                                        ?>
                                                                                            <tr>
                                                                                                <td><?= $sn ?></td>
                                                                                                <td>
                                                                                                    <?php
                                                                                                    echo $issuecategory;
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <?php
                                                                                                    echo $issuedescription;
                                                                                                    ?>
                                                                                                </td>
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
                                                                <!-- Task Checklist Questions -->
                                                            </fieldset>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <fieldset class="scheduler-border">
                                                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Media</legend>
                                                            <!-- Task Checklist Questions -->
                                                            <div class="row clearfix">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <div id="aniimated-thumbnials<?= $inspection_id ?>" class="list-unstyled row clearfix">
                                                                        <?php
                                                                        $query_rsFiles = $db->prepare("SELECT * FROM tbl_files WHERE projid = '$projid' AND general_inspection_id='$inspectionid'");
                                                                        $query_rsFiles->execute();
                                                                        $row_rsFiles = $query_rsFiles->fetch();
                                                                        $totalRows_rsFiles = $query_rsFiles->rowCount();

                                                                        $query_rsPhotos = $db->prepare("SELECT * FROM tbl_files WHERE projid = '$projid' AND general_inspection_id='$inspectionid'");
                                                                        $query_rsPhotos->execute();
                                                                        $row_rsPhotos = $query_rsPhotos->fetch();
                                                                        $totalRows_rsPhotos = $query_rsPhotos->rowCount();
                                                                        if ($totalRows_rsFiles > 0 || $totalRows_rsPhotos > 0) {
                                                                            if ($totalRows_rsPhotos > 0) {
                                                                                do {
                                                                        ?>
                                                                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                                                        <a href="<?= $row_rsPhotos['floc'] ?>" data-sub-html="<?= $row_rsPhotos['description'] ?>" target="_blank">
                                                                                            <i class="fa fa-cloud-download fa-2x" aria-hidden="true"></i>
                                                                                        </a>
                                                                                    </div>
                                                                                <?php
                                                                                } while ($row_rsPhotos = $query_rsPhotos->fetch());
                                                                            }
                                                                            if ($totalRows_rsFiles > 0) {
                                                                                do {
                                                                                ?>
                                                                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                                                        <a href="<?= $row_rsFiles['floc'] ?>" data-sub-html="<?= $row_rsFiles['description'] ?>" target="_blank">
                                                                                            <i class="fa fa-cloud-download fa-2x" aria-hidden="true"></i>
                                                                                        </a>
                                                                                    </div>
                                                                            <?php
                                                                                } while ($row_rsFiles = $query_rsFiles->fetch());
                                                                            }
                                                                        } else {
                                                                            ?>
                                                                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                                                <p align="center" style="color:red">Sorry no media found!!</p>
                                                                            </div>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                            <?php
                                    }
                                } while ($row_rsInspection_details = $query_rsInspection_details->fetch());
                            }

                            ?>

                        </div>
                        <!-- end body  -->
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

} catch (PDOException $th) {
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>