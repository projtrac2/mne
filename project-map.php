<?php
try {
    require('includes/head.php');
    if ($permission && (isset($_GET['proj']) && !empty($_GET["proj"]))) {
        $decode_projid =  base64_decode($_GET['proj']);
        $projid_array = explode("projid54321", $decode_projid);
        $projid = $projid_array[1];
        $original_projid = $_GET['proj'];

        $back_url = $_SESSION['back_url'];
        $query_project = $db->prepare("SELECT * FROM tbl_projects WHERE projid = :projid");
        $query_project->execute(array(":projid" => $projid));
        $row_project = $query_project->fetch();
        $projname = $row_project['projname'];
        $projstage = $row_project["projstage"];
        $projcat = $row_project["projcategory"];
        $percent2 = number_format(calculate_project_progress($projid, $projcat), 2);

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
                        <div class="btn-group" style="float:right; margin-right:10px">
                            <input type="button" VALUE="Go Back to Projects Dashboard" class="btn btn-warning pull-right" onclick="location.href='<?= $back_url ?>'" id="btnback">
                        </div>
                    </h4>
                </div>
                <div class="row clearfix">
                    <div class="block-header">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="header" style="padding-bottom:0px">
                                <div class="" style="margin-top:-15px">
                                    <a href="project-dashboard.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Dashboard</a>
                                    <a href="project-mne-details.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px"> M&E </a>
                                    <a href="project-finance.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Finance</a>
                                    <a href="project-timeline.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Timeline</a>
                                    <?php if ($projcat == 2 && $projstage > 4) { ?>
                                        <a href="project-contract-details.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Contract</a>
                                    <?php } ?>
                                    <a href="project-team-members.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Team</a>
                                    <a href="project-issues.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Risks & Issues</a>
                                    <a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; width:100px">Map</a>
                                    <a href="project-media.php?proj=<?php echo $original_projid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; width:100px">Media</a>
                                </div>
                            </div>
                            <h4>
                                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12" style="font-size:15px; background-color:#CDDC39; border:#CDDC39 thin solid; border-radius:5px; margin-bottom:2px; height:25px; padding-top:2px; vertical-align:center">
                                    Project Name: <font color="white"><?php echo $projname; ?></font>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="font-size:15px; background-color:#CDDC39; border-radius:5px; height:25px; margin-bottom:2px">
                                    <div class="progress" style="height:23px; margin-bottom:1px; margin-top:1px; color:black">
                                        <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="<?= $percent2 ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $percent2 ?>%; margin:auto; padding-left: 10px; padding-top: 3px; text-align:left; color:black">
                                            <?= $percent2 ?>%
                                        </div>
                                    </div>
                                </div>
                            </h4>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="body">
                                <?php
                                $query_rsMarkers = $db->prepare("SELECT * FROM tbl_markers WHERE projid = :projid");
                                $query_rsMarkers->execute(array(":projid" => $projid));
                                $row_rsMarkers = $query_rsMarkers->rowCount();
                                if ($row_rsMarkers > 0) {
                                ?>
                                    <input type="hidden" name="lat" id="lat" value="-1.2864">
                                    <input type="hidden" name="long" id="long" value="36.8172">
                                    <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                    <div class="mt-map-wrapper">
                                        <div class="mt-map propmap" id="map">
                                            <div style="height: 100%; width: 100%; position: relative; overflow: hidden; background-color: rgb(229, 227, 223);">
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                } else {
                                ?>
                                    <h1 class="text-warning">No Coordinates Found</h1>
                                <?php
                                }
                                ?>

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
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>


<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDiyrRpT1Rg7EUpZCUAKTtdw3jl70UzBAU&callback=initMap"></script>
<script src="assets/js/map/project.js"></script>