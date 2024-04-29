<?php
try {
    require('includes/head.php');
    if ($permission && isset($_GET['site_id']) && isset($_GET['state_id']) && isset($_GET['opid'])) {
        $d_site_id = base64_decode($_GET['site_id']);
        $d_state_id =  base64_decode($_GET['state_id']);
        $output_id =  base64_decode($_GET['opid']);


        $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE id = :output_id ");
        $query_Output->execute(array(":output_id" => $output_id));
        $row_rsOutput = $query_Output->fetch();
        $total_Output = $query_Output->rowCount();

        if ($total_Output > 0) {

            $indicator_id =  $row_rsOutput['indicator'];
            $projid = $row_rsOutput['projid'];
            $projid =  $row_rsOutput['projid'];
            $projid_hashed = base64_encode("projid54321{$projid}");
            $output_name = $row_rsOutput['indicator_name'];
            $mapping_type = $row_rsOutput['indicator_mapping_type'];
            $unit_id = $row_rsOutput['indicator_unit'];

            $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.deleted='0' and p.projid=:projid AND projstage=:projstage");
            $query_rsProjects->execute(array(":projid" => $projid, ":projstage" => $workflow_stage));
            $row_rsProjects = $query_rsProjects->fetch();
            $totalRows_rsProjects = $query_rsProjects->rowCount();

            if ($totalRows_rsProjects > 0) {
                $projname = $row_rsProjects['projname'];
                $projcode = $row_rsProjects['projcode'];
                $sub_stage = $row_rsProjects['proj_substage'];

                $approval_stage = ($sub_stage  >= 2) ? true : false;


                $query_rsIndUnit = $db->prepare("SELECT * FROM  tbl_measurement_units WHERE id =:unit_id");
                $query_rsIndUnit->execute(array(":unit_id" => $unit_id));
                $row_rsIndUnit = $query_rsIndUnit->fetch();
                $totalRows_rsIndUnit = $query_rsIndUnit->rowCount();
                $unit = $totalRows_rsIndUnit > 0 ? $row_rsIndUnit['unit'] : "";

                $query_rsMapType =  $db->prepare("SELECT id, type FROM tbl_map_type WHERE id=:map");
                $query_rsMapType->execute(array(":map" => $mapping_type));
                $row_rsMapType = $query_rsMapType->fetch();
                $totalRows_rsMapType = $query_rsMapType->rowCount();
                $map = $totalRows_rsMapType > 0 ? $row_rsMapType['type'] : "";


                $total_target = $distance_mapped = 0;
                $site = "N/A";
                $state = [];
                $previous_lat = $previous_lng  = "";
                if ($mapping_type == 1) {
                    $querysSite = $db->prepare("SELECT * FROM tbl_project_sites d INNER JOIN tbl_state s ON s.id = d.state_id WHERE site_id = :id ");
                    $querysSite->execute(array(":id" => $d_site_id));
                    $totalsSite = $querysSite->rowCount();
                    $row_rsSite = $querysSite->fetch();
                    if ($total_Output > 0) {
                        $site = $row_rsSite['site'];
                        $state[] = $row_rsSite['state'];
                        $query_rsState = $db->prepare("SELECT * FROM tbl_output_disaggregation  WHERE output_site=:site_id ");
                        $query_rsState->execute(array(":site_id" => $d_site_id));
                        $total_rsState = $query_rsState->rowCount();
                        $row_rsState = $query_rsState->fetch();
                        $target = ($total_rsState > 0) ? $row_rsState['total_target'] : "";
                        $total_target = $target == 0 ? 1 : $target;
                    }
                } else {
                    $query_rsState = $db->prepare("SELECT * FROM tbl_output_disaggregation d INNER JOIN tbl_state s ON s.id = d.outputstate WHERE outputid=:output_id ");
                    $query_rsState->execute(array(":output_id" => $output_id));
                    $total_rsState = $query_rsState->rowCount();

                    if ($total_rsState > 0) {
                        while ($row_rsState = $query_rsState->fetch()) {
                            $state[] = $row_rsState['state'];
                        }
                    }
                }

                $query_rsMapping = $db->prepare("SELECT * FROM tbl_markers  WHERE opid=:output_id AND site_id=:site_id ORDER BY id DESC LIMIT 1");
                $query_rsMapping->execute(array(":output_id" => $output_id, ':site_id' => $d_site_id));
                $rows_rsMapping = $query_rsMapping->fetch();
                $total_rsMapping = $query_rsMapping->rowCount();
                $action =  $total_rsMapping > 0 ? 'Update' : "Submit";

                if (isset($_POST['submit'])) {
                    $projid = $_POST['projid'];
                    if (validate_csrf_token($_POST['csrf_token'])) {
                        $current_date = date("Y-m-d");
                        $projid = $_POST['projid'];
                        $outputid = $_POST['opid'];
                        $state_id = $_POST['state_id'];
                        $site_id = $_POST['site_id'];
                        $user_name = $_POST['user_name'];
                        $mapping_type = $_POST['mapping_type'];
                        $lat = $_POST['latitude'];
                        $lng = $_POST['longitude'];

                        $sql = $db->prepare("DELETE FROM tbl_markers  WHERE opid=:output_id AND site_id=:site_id");
                        $sql->execute(array(":output_id" => $output_id, ':site_id' => $d_site_id));

                        $sql = $db->prepare("INSERT INTO tbl_markers (projid,opid,site_id,lat,lng,mapped_date,mapped_by)  VALUES(:projid,:opid,:site_id,:lat,:lng,:mapped_date,:mapped_by)");
                        $result = $sql->execute(array(':projid' => $projid, ":opid" => $outputid, ':site_id' => $site_id, ':lat' => $lat, ':lng' => $lng, ":mapped_date" => $current_date, ":mapped_by" => $user_name));
                        $hashproc = base64_encode("projid54321{$projid}");
                        $results = success_message('Records created successfully added.', 2, "add-project-mapping?projid=" . $hashproc);
                    } else {
                        $hashproc = base64_encode("projid54321{$projid}");
                        $results = error_message('Error occured please try again later', 2, "add-project-mapping?projid=" . $hashproc);
                    }
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
                                <?= $icon . "  " . $pageTitle ?>
                                <?= $results ?>
                                <div class="btn-group" style="float:right">
                                    <div class="btn-group" style="float:right">
                                        <a type="button" id="outputItemModalBtnrow" onclick="history.back()" class="btn btn-warning pull-right">
                                            Go Back
                                        </a>
                                    </div>
                                </div>
                            </h4>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row clearfix">
                                            <div class="col-md-12">
                                                <ul class="list-group">
                                                    <li class="list-group-item list-group-item list-group-item-action active">Project Name: <?= $projname ?> </li>
                                                    <li class="list-group-item"><strong>Output: </strong> <?= $output_name ?> </li>
                                                    <li class="list-group-item"> <strong><?= $level2label ?>: </strong> <?= implode(",", $state) ?>&nbsp;&nbsp;&nbsp;&nbsp; <strong>Site: </strong> <?= $site ?> &nbsp;&nbsp;&nbsp;&nbsp; <strong>Mapping Type: </strong> <?= $map ?> </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="body">
                                        <input type="hidden" name="sub_stage" value="<?= $sub_stage ?>">
                                        <div class="mt-map-wrapper">
                                            <div class="mt-map propmap" id="map">
                                                <div style="height: 100%; width: 100%; position: relative; overflow: hidden; background-color: rgb(229, 227, 223);">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="header">
                                            <div class="row clearfix">
                                                <form action="" method="post" id="submitform">
                                                    <?= csrf_token_html(); ?>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <label for="latitude">Project Latitude *:</label>
                                                        <input type="text" name="latitude" id="latitude" readonly class="form-control">
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <label for="longitude">Project Longitude *:</label>
                                                        <input type="text" name="longitude" id="longitude" readonly class="form-control">
                                                    </div>
                                                    <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                                    <input type="hidden" name="opid" id="opid" value="<?= $output_id ?>">
                                                    <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                                    <input type="hidden" name="state_id" id="state_id" value="<?= $d_state_id ?>">
                                                    <input type="hidden" name="site_id" id="site_id" value="<?= $d_site_id ?>">
                                                    <input type="hidden" name="mapping_type" id="mapping_type" value="<?= $mapping_type ?>">
                                                    <div class="col-md-12" align="center">
                                                        <?php
                                                        if (!$approval_stage) {
                                                        ?>
                                                            <button type="submit" name="submit" class="btn btn-success"> <?= $action ?></button>
                                                            <a type="button" id="outputItemModalBtnrow" onclick="history.back()" class="btn btn-warning">
                                                                Cancel
                                                            </a>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </form>
                                            </div>
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
    } else {
        $results =  restriction();
        echo $results;
    }
} catch (PDOException $ex) {
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}

require('includes/footer.php');
?>

<script src="assets/js/map/auto.js"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDiyrRpT1Rg7EUpZCUAKTtdw3jl70UzBAU"></script>