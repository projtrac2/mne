<?php
try {
    require('includes/head.php');
    if ($permission && (isset($_GET['projid']) && !empty($_GET["projid"]))) {
        $decode_projid = base64_decode($_GET['projid']);
        $projid_array = explode("projid54321", $decode_projid);
        $projid = $projid_array[1];

        $query_rsProjects = $db->prepare("SELECT p.*, g.projsector, g.projdept, g.directorate FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid  WHERE p.deleted='0' and projid=:projid AND projstage=:projstage");
        $query_rsProjects->execute(array(":projid" => $projid, ":projstage" => $workflow_stage));
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();
        if ($totalRows_rsProjects > 0) {
            $projname = $row_rsProjects['projname'];
            $workflow_stage =  $row_rsProjects['projstage'];
            $implementation =  $row_rsProjects['projcategory'];
            $sub_stage = $row_rsProjects['proj_substage'];
            $projcode = $row_rsProjects['projcode'];
            $project_directorate = $row_rsProjects['directorate'];
            $project_impact = $row_rsProjects['projimpact'];
            $project_evaluation = $row_rsProjects['projevaluation'];
            $approval_stage = ($sub_stage  >= 2) ? true : false;

            $disabled = $sub_stage == 0 || $sub_stage == 1 ? "" : "disabled";
            $approve_details = "{
                get_edit_details: 'details',
                projid:$projid,
                workflow_stage:$workflow_stage,
                sub_stage:$sub_stage,
                project_directorate:$project_directorate,
                project_name:'$projname',
                sub_stage:'$sub_stage',
            }";

            function validate_mapping()
            {
                global $db, $projid;
                $query_Output = $db->prepare("SELECT * FROM tbl_project_details p INNER JOIN tbl_indicator i ON i.indid = p.indicator WHERE projid = :projid ");
                $query_Output->execute(array(":projid" => $projid));
                $total_Output = $query_Output->rowCount();
                $submit_arr = array();
                if ($total_Output > 0) {
                    while ($row_rsOutput = $query_Output->fetch()) {
                        $output_id = $row_rsOutput['id'];
                        $mapping_type = $row_rsOutput['indicator_mapping_type'];
                        if ($mapping_type == 1) {
                            $querysSite = $db->prepare("SELECT * FROM tbl_project_sites d INNER JOIN tbl_output_disaggregation o ON d.site_id = o.output_site INNER JOIN tbl_state s ON s.id = d.state_id WHERE o.projid = :projid AND outputid = :output_id");
                            $querysSite->execute(array(":projid" => $projid, ":output_id" => $output_id));
                            $totalsSite = $querysSite->rowCount();
                            if ($totalsSite > 0) {
                                while ($row_rsSite = $querysSite->fetch()) {
                                    $site_id = $row_rsSite['site_id'];
                                    $site = $row_rsSite['site'];
                                    $query_rsMapping = $db->prepare("SELECT * FROM tbl_markers  WHERE site_id=:site_id AND opid=:output_id");
                                    $query_rsMapping->execute(array(":site_id" => $site_id, ":output_id" => $output_id));
                                    $total_rsMapping = $query_rsMapping->rowCount();
                                    $submit_arr[] = $total_rsMapping > 0 ? true : false;
                                }
                            }
                        } else {
                            $query_rsMapping = $db->prepare("SELECT * FROM tbl_markers  WHERE opid=:output_id");
                            $query_rsMapping->execute(array(":output_id" => $output_id));
                            $total_rsMapping = $query_rsMapping->rowCount();
                            $submit_arr[] = $total_rsMapping > 0 ? true : false;
                        }
                    }
                }
                return !in_array(false, $submit_arr) ? true : false;
            }
?>
            <style>
                .mt-map-wrapper {
                    width: 100%;
                    padding-bottom: 41.6%;
                    height: 0;
                    overflow: hidden;
                    position: relative;
                }

                .mt-map {
                    width: 100%;
                    height: 100%;
                    left: 0;
                    top: 0;
                    position: absolute;
                }
            </style>
            <!-- start body  -->
            <section class="content">
                <div class="container-fluid">
                    <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                        <h4 class="contentheader">
                            <?= $icon ?>
                            <?= $pageTitle ?>
                            <div class="btn-group" style="float:right">
                                <div class="btn-group" style="float:right">
                                    <a type="button" id="outputItemModalBtnrow" href="project-mapping.php" class="btn btn-warning pull-right">
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

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                            <thead>
                                                <tr style="background-color:#0b548f; color:#FFF">
                                                    <th style="width:5%" align="center">#</th>
                                                    <th style="width:20%">Output</th>
                                                    <th style="width:10%">Mapping Type </th>
                                                    <th style="width:20%"><?= $level2label ?> </th>
                                                    <th style="width:20%">Site </th>
                                                    <th style="width:10%">Status </th>
                                                    <th style="width:10%">Mapping Date </th>
                                                    <th style="width:5%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $query_Output = $db->prepare("SELECT * FROM tbl_project_details p INNER JOIN tbl_indicator i ON i.indid = p.indicator WHERE projid = :projid ORDER BY p.indicator");
                                                $query_Output->execute(array(":projid" => $projid));
                                                $total_Output = $query_Output->rowCount();
                                                if ($total_Output > 0) {
                                                    $counter = 0;
                                                    while ($row_rsOutput = $query_Output->fetch()) {
                                                        $output_id = $row_rsOutput['id'];
                                                        $output_name = $row_rsOutput['indicator_name'];
                                                        $mapping_type = $row_rsOutput['indicator_mapping_type'];

                                                        $query_rsMapType =  $db->prepare("SELECT id, type FROM tbl_map_type WHERE id=:map");
                                                        $query_rsMapType->execute(array(":map" => $mapping_type));
                                                        $row_rsMapType = $query_rsMapType->fetch();
                                                        $totalRows_rsMapType = $query_rsMapType->rowCount();
                                                        $map = $totalRows_rsMapType > 0 ? $row_rsMapType['type'] : '';

                                                        $query_rsMapping_Date = $db->prepare("SELECT * FROM tbl_projmembers  WHERE projid =:projid AND output_id=:output_id");
                                                        $query_rsMapping_Date->execute(array(":projid" => $projid, ":output_id" => $output_id));
                                                        $rows_rsMapping_Date = $query_rsMapping_Date->fetch();
                                                        $total_rsMapping_Date = $query_rsMapping_Date->rowCount();
                                                        $mapping_date = $total_rsMapping_Date > 0 ? $rows_rsMapping_Date['created_at'] : date('Y-m-d H:i:s');
                                                        $responsible =    check_if_map_responsible($projid, $workflow_stage, $sub_stage, 1);

                                                        if ($responsible['member']) {
                                                            if ($mapping_type == 1) {
                                                                $querysSite = $db->prepare("SELECT * FROM tbl_project_sites d INNER JOIN tbl_output_disaggregation o ON d.site_id = o.output_site INNER JOIN tbl_state s ON s.id = d.state_id WHERE o.projid = :projid AND outputid = :output_id");
                                                                $querysSite->execute(array(":projid" => $projid, ":output_id" => $output_id));
                                                                $totalsSite = $querysSite->rowCount();
                                                                if ($totalsSite > 0) {
                                                                    while ($row_rsSite = $querysSite->fetch()) {
                                                                        $counter++;
                                                                        $site_id = $row_rsSite['site_id'];
                                                                        $state_id = $row_rsSite['state_id'];
                                                                        $site = $row_rsSite['site'];
                                                                        $state = $row_rsSite['state'];
                                                                        $query_rsMapping = $db->prepare("SELECT * FROM tbl_markers  WHERE site_id=:site_id AND opid=:output_id");
                                                                        $query_rsMapping->execute(array(":site_id" => $site_id, ":output_id" => $output_id));
                                                                        $total_rsMapping = $query_rsMapping->rowCount();

                                                                        $status = '';
                                                                        if ($total_rsMapping > 0) {
                                                                            $status =  '<span class="badge bg-green" style="margin-bottom:2px">Mapped</span> <br />';
                                                                        } else {
                                                                            $today = date("Y-m-d");
                                                                            if ($today <= $mapping_date) {
                                                                                $status =  '<span class="badge bg-blue" style="margin-bottom:2px">Pending</span> <br />';
                                                                            } else if ($today > $mapping_date) {
                                                                                $status =  '<span class="badge bg-deep-orange" style="margin-bottom:2px">Behind Schedule</span> <br />';
                                                                            }
                                                                        }

                                                ?>


                                                                        <tr>
                                                                            <td align="center"><?= $counter ?></td>
                                                                            <td><?= $output_name ?></td>
                                                                            <td><?= $map ?></td>
                                                                            <td><?= $state  ?> </td>
                                                                            <td><?= $site ?> </td>
                                                                            <td><?= $status ?> </td>
                                                                            <td> <?= date("d M Y", strtotime($mapping_date)) ?></td>
                                                                            <td>
                                                                                <div class="btn-group">
                                                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                        Options <span class="caret"></span>
                                                                                    </button>
                                                                                    <ul class="dropdown-menu">
                                                                                        <?php
                                                                                        if ($responsible['responsible']) {
                                                                                        ?>
                                                                                            <li>
                                                                                                <a type="button" href="add-map-data-automatically.php?state_id=<?= base64_encode($state_id) ?>&site_id=<?= base64_encode($site_id) ?>&opid=<?= base64_encode($output_id) ?>">
                                                                                                    <i class="fa fa-file-text"></i> Automated Mapping
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a type="button" href="add-map-data-manual.php?state_id=<?= base64_encode($state_id) ?>&site_id=<?= base64_encode($site_id) ?>&opid=<?= base64_encode($output_id) ?>">
                                                                                                    <i class="fa fa-file-text"></i> Manual Mapping
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
                                                                    }
                                                                }
                                                            } else {
                                                                $counter++;
                                                                $query_rsState = $db->prepare("SELECT * FROM tbl_output_disaggregation d INNER JOIN tbl_state s ON s.id = d.outputstate WHERE outputid=:output_id ");
                                                                $query_rsState->execute(array(":output_id" => $output_id));
                                                                $total_rsState = $query_rsState->rowCount();
                                                                $states = [];
                                                                if ($total_rsState > 0) {
                                                                    while ($row_rsState = $query_rsState->fetch()) {
                                                                        $states[] = $row_rsState['state'];
                                                                    }
                                                                }
                                                                $query_rsMapping = $db->prepare("SELECT * FROM tbl_markers  WHERE opid=:output_id");
                                                                $query_rsMapping->execute(array(":output_id" => $output_id));
                                                                $total_rsMapping = $query_rsMapping->rowCount();

                                                                $status = '';
                                                                if ($total_rsMapping > 0) {
                                                                    $status =  '<span class="badge bg-green" style="margin-bottom:2px">Mapped</span> <br />';
                                                                } else {
                                                                    $today = date("Y-m-d");
                                                                    if ($today <= $mapping_date) {
                                                                        $status =  '<span class="badge bg-blue" style="margin-bottom:2px">Pending</span> <br />';
                                                                    } else if ($today > $mapping_date) {
                                                                        $status =  '<span class="badge bg-deep-orange" style="margin-bottom:2px">Behind Schedule</span> <br />';
                                                                    }
                                                                }
                                                                $state_id = $site_id = 0;
                                                                ?>
                                                                <tr>
                                                                    <td align="center"><?= $counter ?></td>
                                                                    <td><?= $output_name ?></td>
                                                                    <td><?= $map ?></td>
                                                                    <td><?= implode(",", $states)  ?> </td>
                                                                    <td><?= "N/A" ?> </td>
                                                                    <td><?= $status ?> </td>
                                                                    <td><?= date("d M Y", strtotime($mapping_date)) ?></td>
                                                                    <td>
                                                                        <div class="btn-group">
                                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                Options <span class="caret"></span>
                                                                            </button>
                                                                            <ul class="dropdown-menu">
                                                                                <?php
                                                                                if ($responsible['responsible']) {
                                                                                ?>
                                                                                    <li>
                                                                                        <a type="button" href="add-map-data-automatically.php?state_id=<?= base64_encode($state_id) ?>&site_id=<?= base64_encode($site_id) ?>&opid=<?= base64_encode($output_id) ?>">
                                                                                            <i class="fa fa-file-text"></i> Automated Mapping
                                                                                        </a>
                                                                                    </li>
                                                                                    <?php
                                                                                    ?>
                                                                                    <li>
                                                                                        <a type="button" href="add-map-data-manual.php?state_id=<?= base64_encode($state_id) ?>&site_id=<?= base64_encode($site_id) ?>&opid=<?= base64_encode($output_id) ?>">
                                                                                            <i class="fa fa-file-text"></i> Manual Mapping
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
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="body">
                                    <input type="hidden" name="lat" id="lat" value="-1.2864">
                                    <input type="hidden" name="long" id="long" value="36.8172">
                                    <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                    <div class="mt-map-wrapper">
                                        <div class="mt-map propmap" id="map">
                                            <div style="height: 100%; width: 100%; position: relative; overflow: hidden; background-color: rgb(229, 227, 223);">
                                            </div>
                                        </div>
                                    </div>
                                    <form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                                        <?= csrf_token_html(); ?>
                                        <div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
                                            <div class="col-md-12 text-center">
                                                <?php
                                                $proceed = validate_mapping();
                                                if ($proceed) {
                                                    $assigned_responsible = check_if_assigned($projid, $workflow_stage, $sub_stage, 1);
                                                    if ($assigned_responsible) {
                                                        if ($approval_stage) {
                                                            $stage = $workflow_stage;
                                                            if ($project_evaluation == 0) {
                                                                $stage = $stage + 1;
                                                                if ($implementation == 1) {
                                                                    $stage = $stage + 1;
                                                                }
                                                            }

                                                            $approve_details =
                                                                "{
                                                                    get_edit_details: 'details',
                                                                    projid:$projid,
                                                                    workflow_stage:$stage,
                                                                    project_directorate:$project_directorate,
                                                                    project_name:'$projname',
                                                                    sub_stage:'$sub_stage',
                                                                }";
                                                ?>
                                                            <button type="button" onclick="approve_project(<?= $approve_details ?>)" class="btn btn-success">Approve</button>
                                                        <?php
                                                        } else {
                                                            $data_entry_details =
                                                                "{
                                                                    get_edit_details: 'details',
                                                                    projid:$projid,
                                                                    workflow_stage:$workflow_stage,
                                                                    project_directorate:$project_directorate,
                                                                    project_name:'$projname',
                                                                    sub_stage:'$sub_stage',
                                                                }";
                                                        ?>
                                                            <button type="button" onclick="save_data_entry_project(<?= $data_entry_details ?>)" class="btn btn-success">Proceed</button>
                                                <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </form>
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
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
require('includes/footer.php');
?>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDiyrRpT1Rg7EUpZCUAKTtdw3jl70UzBAU&callback=initMap"></script>

<script>
    const redirect_url = "project-mapping.php";
</script>
<script src="assets/js/map/index.js"></script>
<script src="assets/js/master/index.js"></script>
<script src="assets/js/map/project.js"></script>