<?php
require('includes/head.php');
if ($permission) {
    try {
        $d_site_id = isset($_GET['site_id']) ? base64_decode($_GET['site_id']) : "";
        $d_state_id = isset($_GET['state_id']) ? base64_decode($_GET['state_id']) : "";
        $output_id = isset($_GET['opid']) ? base64_decode($_GET['opid']) : "";

        $query_Output = $db->prepare("SELECT * FROM tbl_project_details WHERE id = :output_id ");
        $query_Output->execute(array(":output_id" => $output_id));
        $row_rsOutput = $query_Output->fetch();
        $total_Output = $query_Output->rowCount();

        $indicator_id = $total_Output > 0 ? $row_rsOutput['indicator'] : "";
        $projid = $total_Output > 0 ?  $row_rsOutput['projid'] : "";
        $projid = $total_Output > 0 ?  $row_rsOutput['projid'] : "";

        $projid_hashed = base64_encode("projid54321{$projid}");


        $query_rsIndicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid=:indicator_id");
        $query_rsIndicator->execute(array(":indicator_id" => $indicator_id));
        $row_rsIndicator = $query_rsIndicator->fetch();
        $totalRows_rsIndicator = $query_rsIndicator->rowCount();

        $output_name = $totalRows_rsIndicator > 0 ? $row_rsIndicator['indicator_name'] : "";
        $mapping_type = $totalRows_rsIndicator > 0 ? $row_rsIndicator['indicator_mapping_type'] : "";
        $unit_id = $totalRows_rsIndicator > 0 ? $row_rsIndicator['indicator_unit'] : "";


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

        $query_rsProjects = $db->prepare("SELECT *  FROM tbl_projects WHERE deleted='0' and projid=:projid");
        $query_rsProjects->execute(array(":projid" => $projid));
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();
        $projname = $totalRows_rsProjects > 0 ? $row_rsProjects['projname'] : "";
        $projcode = $totalRows_rsProjects > 0 ? $row_rsProjects['projcode'] : "";
        $sub_stage = $totalRows_rsProjects > 0 ? $row_rsProjects['proj_substage'] : "";
        $approval_stage = ($sub_stage  >= 2) ? true : false;

        $total_target = $distance_mapped = 0;
        $site = "N/A";
        $state = [];
        $previous_lat = $previous_lng  = "";
        $action = '';
        if ($mapping_type == 1 || $mapping_type == 3) {
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

            $query_rsMapping = $db->prepare("SELECT * FROM tbl_markers  WHERE opid=:output_id AND site_id=:site_id ORDER BY id DESC LIMIT 1");
            $query_rsMapping->execute(array(":output_id" => $output_id, ':site_id' => $d_site_id));
            $rows_rsMapping = $query_rsMapping->fetch();
            $total_rsMapping = $query_rsMapping->rowCount();
            $action =  $total_rsMapping > 0 ? 'Update' : "Submit";
        } else {
            $query_rsState = $db->prepare("SELECT * FROM tbl_output_disaggregation d INNER JOIN tbl_state s ON s.id = d.outputstate WHERE outputid=:output_id ");
            $query_rsState->execute(array(":output_id" => $output_id));
            $total_rsState = $query_rsState->rowCount();

            if ($total_rsState > 0) {
                while ($row_rsState = $query_rsState->fetch()) {
                    $state[] = $row_rsState['state'];
                }
            }

            $query_rsMapping = $db->prepare("SELECT * FROM tbl_markers  WHERE opid=:output_id ORDER BY id DESC LIMIT 1");
            $query_rsMapping->execute(array(":output_id" => $output_id));
            $rows_rsMapping = $query_rsMapping->fetch();
            $total_rsMapping = $query_rsMapping->rowCount();
            $action = "Pin";
        }

        if (isset($_POST['submit'])) {
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
            $msg = 'The Mapping was successfully.';

            $hashproc = base64_encode("projid54321{$projid}");
            $msg = 'Records created successfully added.';
            $results = "<script type=\"text/javascript\">
                swal({
                title: \"Success!\",
                text: \" $msg\",
                type: 'Success',
                timer: 2000,
                icon:'success',
                showConfirmButton: false });
                setTimeout(function(){
                        window.location.href = 'add-project-mapping?projid=$hashproc';
                    }, 3000);
            </script>";
        }
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
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
                                        <li class="list-group-item"><strong><?= $level2label ?>: </strong> <?= implode(",", $state) ?> </li>
                                        <li class="list-group-item"><strong>Site: </strong> <?= $site ?> </li>
                                        <li class="list-group-item"><strong>Mapping Type: </strong> <?= $map ?> </li>
                                    </ul>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="sbutton">
                                    <?php
                                    if ($mapping_type == 2 || $mapping_type == 3) {
                                    ?>
                                        <button class="btn btn-sm btn-sucess" onclick="pin_coordinates()">Pin</button>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <input type="hidden" name="lat" id="lat" value="-1.2864">
                            <input type="hidden" name="long" id="long" value="36.8172">
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
require('includes/footer.php');
?>

<script src="assets/js/map/auto.js"></script>
<!-- <script src="assets/js/maps/get_output_coordinates.js"></script> -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDiyrRpT1Rg7EUpZCUAKTtdw3jl70UzBAU"></script>