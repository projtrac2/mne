<?php
require('includes/head.php');
if ($permission) {
    try {
        $accesslevel = "";
        $sector = 0;
        $indicator_data = '<option value="" >Select Indicator</option>';
        $query_fy = $db->prepare("SELECT i.indicator_name, i.indid, m.unit FROM tbl_indicator i INNER JOIN tbl_measurement_units m ON m.id  = i.indicator_unit WHERE indicator_sector = :indicator_sector AND indicator_mapping_type != 0");
        $query_fy->execute(array(":indicator_sector" => $department_id));
        while ($row = $query_fy->fetch()) {
            $indid = $row['indid'];
            $indicator_data .= '<option value="' . $indid . '"> ' . $row['unit'] . " of " . $row['indicator_name'] . '</option>';
        }

        if ($designation == 1 || ($designation < 5)) {
            $accesslevel = "";
            $sector = 1;
            $query_rsSectors = $db->prepare("SELECT * FROM tbl_sectors WHERE parent='0'");
            $query_rsSectors->execute();
            $totalRows_rsSectors = $query_rsSectors->rowCount();
        } elseif ($designation == 5) {
            $accesslevel = " AND g.projsector=$department_id";
        } elseif ($designation == 6) {
            $accesslevel = " AND g.projsector=$department_id AND g.projdept=$section_id";
        } elseif ($designation > 6) {
            $accesslevel = " AND g.projsector=$department_id AND g.projdept=$section_id AND g.directorate=$directorate_id";
        }

        function projfy()
        {
            global $db;
            $year = date('Y');
            $projfy = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE yr <=:year ");
            $projfy->execute(array(":year" => $year));
            while ($row = $projfy->fetch()) {
                echo '<option value="' . $row['id'] . '">' . $row['year'] . '</option>';
            }
        }


        //get subcounty
        $query_rsComm =  $db->prepare("SELECT id, state FROM tbl_state WHERE parent IS NULL ORDER BY state ASC");
        $query_rsComm->execute();
        $row_rsComm = $query_rsComm->fetch();
        $totalRows_rsComm = $query_rsComm->rowCount();
    } catch (PDOException $ex) {
        $result = flashMessage("An error occurred: " . $ex->getMessage());
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
                            <input type="hidden" name="lat" id="lat" value="-1.2864">
                            <input type="hidden" name="long" id="long" value="36.8172">
                            <div class="header">
                                <div class="row clearfix">
                                    <form id="searchform" name="searchform" method="get" style="margin-top:-10px" action="s">
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <select name="department" onchange="get_indicators()" id="department" class="form-control show-tick" data-live-search="true" data-live-search-style="startsWith">
                                                <option value="" selected="selected">Select Department</option>
                                                <?php
                                                while ($row_rsSectors = $query_rsSectors->fetch()) {
                                                    $query_rsMarkers =  $db->prepare(" SELECT * FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id =m.opid INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE i.indicator_sector=:department_id");
                                                    $query_rsMarkers->execute(array(":department_id" => $row_rsSectors['stid']));
                                                    $row_rsMarkers = $query_rsMarkers->fetch();
                                                    $totalRows_rsMarkers = $query_rsMarkers->rowCount();
                                                    if ($totalRows_rsMarkers > 0) {
                                                ?>
                                                        <option value="<?php echo $row_rsSectors['stid'] ?>"><?php echo $row_rsSectors['sector'] ?></option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <select name="indicators" id="indicator" onchange="get_coordinates()" class="form-control show-tick" data-live-search="false" data-live-search-style="startsWith">
                                                <?php if ($sector == 1) { ?>
                                                    <option value="" selected="selected">Select Department First</option>
                                                <?php } else {
                                                    echo $indicator_data;
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <select name="projfyfrom" id="fyfrom" onchange="finyearfrom()" class="form-control show-tick " data-live-search="true" style="border:#CCC thin solid; border-radius:5px;" data-live-search-style="startsWith">
                                                <option value="" selected="selected">Select Financial Year From</option>
                                                <?php
                                                projfy();
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <select name="projfyto" id="fyto" class="form-control show-tick" onchange="get_coordinates()" data-live-search="false" style="border:#CCC thin solid; border-radius:5px;" data-live-search-style="startsWith">
                                                <option value="" selected="selected">Select To Financial Year</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <select name="projscounty" id="projcommunity" onchange="conservancy()" class="form-control show-tick " style="border:#CCC thin solid; border-radius:5px;" data-live-search="false">
                                                <option value="">Select <?= $level1label ?></option>
                                                <?php
                                                $data = '';
                                                $id = [];
                                                do {
                                                    $comm = $row_rsComm['id'];
                                                    $query_ward = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:comm ");
                                                    $query_ward->execute(array(":comm" => $comm));
                                                    while ($row = $query_ward->fetch()) {
                                                        $projlga = $row['id'];
                                                        $query_rsLocations = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:id");
                                                        $query_rsLocations->execute(array(":id" => $projlga));
                                                        $row_rsLocations = $query_rsLocations->fetch();
                                                        $total_locations = $query_rsLocations->rowCount();
                                                        if ($total_locations > 0) {
                                                            if (!in_array($comm, $id)) {
                                                                $data .= '<option value="' . $row_rsComm['id'] . '">' . $row_rsComm['state'] . '</option>';
                                                            }
                                                            $id[] = $row_rsComm['id'];
                                                        }
                                                    }
                                                } while ($row_rsComm = $query_rsComm->fetch());
                                                echo $data;
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <select name="projward" id="projlga" onchange="get_coordinates()" class="form-control show-tick " style="border:#CCC thin solid; border-radius:5px;" data-live-search="false">
                                                <option value="">Select <?= $level2label ?></option>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="mt-map-wrapper">
                                <div class="mt-map propmap" id="map">
                                    <div style="height: 100%; width: 100%; position: relative; overflow: hidden; background-color: rgb(229, 227, 223);">
                                    </div>
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
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDiyrRpT1Rg7EUpZCUAKTtdw3jl70UzBAU&callback=initMap"></script>
<script src="assets/js/map/output.js"></script>