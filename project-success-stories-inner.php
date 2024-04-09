<?php 
try {
    //code...

?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <div class="block-header" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; background-color:#607D8B; color:#FFF">
                    <h4 class="contentheader"><i class="fa fa-file-audio-o" aria-hidden="true"></i> Project Success Stories
                    </h4>
                </div>
            </div>
            <div class="header">
                <div class="row clearfix" style="margin-top:5px">
                    <div class="col-md-12 row">
                        <ul class="list-inline pull-right">
                            <li>
                                <a href="reports/project-success-stories-pdf.php?projid=<?=$projid?>" target="_blank" class="btn btn-danger btn-sm" type="button">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="body">
                <div class="row">
					<div class="col-lg-12">
						<strong>Project Name: </strong> <?=$projname?>
					</div>
					<div class="col-lg-3">
						<strong>Project Code: </strong> <?=$projcode?>
					</div>
					<div class="col-lg-9">
						<label for="location" class="label label-primary">Project Location Name: <?=$projstate?></label>
					</div>
                </div>
                <?php
                    for ($i = 0; $i < count($state); $i++) {
                        $level3 = $state[$i];
                        $query_rsProjectstate = $db->prepare("SELECT state FROM tbl_state WHERE id='$level3'");
                        $query_rsProjectstate->execute();
                        $row_rsProjectstate = $query_rsProjectstate->fetch();
                        $projstate = $row_rsProjectstate['state'];

                        $query_rsPhotos = $db->prepare("SELECT * FROM tbl_project_photos  p INNER JOIN tbl_monitoring m ON m.mid = p.monitoringid WHERE m.projid='$projid' AND m.level3='$level3' AND  p.projstage=10");
                        $query_rsPhotos->execute();
                        $row_rsPhotos = $query_rsPhotos->fetch();
                        $total_photos = $query_rsPhotos->rowCount();
                        echo $total_photos;
                        if ($total_photos > 0) {
                            ?>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12">
                                    <div id="carousel-example-generic_<?=$level3?>" class="carousel slide" data-ride="carousel">
                                        <ol class="carousel-indicators">
                                            <?php
                                                for ($q = 0; $q < $total_photos; $q++) {
                                                            $name = '';
                                                            if ($q == 0) {
                                                                $name = 'active';
                                                            }
                                                            ?>
                                                        <li data-target="#carousel-example-generic_<?=$level3?>" data-slide-to="<?=$q?>" class="<?=$name?>"></li>
                                                    <?php
                                                }
                                                ?>
                                        </ol>
                                        <div class="carousel-inner" role="listbox">
                                            <?php
                                                $index = 0;
                                                do {
                                                    $fileNAme = $row_rsPhotos['filename'];
                                                    $path = $row_rsPhotos['floc'];
                                                    $reason = $row_rsPhotos['reason'];
                                                    $active = '';
                                                    if ($index == 0) {
                                                        $active = 'active';
                                                    }
                                                    ?>
                                                    <div class="item <?=$active?>">
                                                        <img src="<?=$path?>"  style="width: 100% !important; height: 300px;" />
                                                        <div class="carousel-caption">
                                                            <p><?=$reason?>.</p>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    $index++;
                                                } while ($row_rsPhotos = $query_rsPhotos->fetch());
                                                ?>
                                        </div>
                                        <!-- Controls -->
                                        <a class="left carousel-control" href="#carousel-example-generic_<?=$level3?>" role="button" data-slide="prev">
                                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="right carousel-control" href="#carousel-example-generic_<?=$level3?>" role="button" data-slide="next">
                                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
							<?php
                                $query_rsProjectobservation = $db->prepare("SELECT * FROM tbl_monitoring_observations  o INNER JOIN tbl_monitoring m ON m.mid = o.monitoringid  WHERE m.projid='$projid' AND m.level3='$level3' order by opid");
                                $query_rsProjectobservation->execute();
                                $row_rsProjectobservation = $query_rsProjectobservation->fetch();
                                $total_rsRows = $query_rsProjectobservation->rowCount();

                                if ($total_rsRows > 0) {
                                    do {
                                        $observation = $row_rsProjectobservation['observation'];
                                        ?>
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12">
                                                <?php echo $observation; ?>
                                            </div>
                                        </div>
                                        <?php
                                        } while ($row_rsProjectobservation = $query_rsProjectobservation->fetch());
                                }
                            } else {
                                echo '
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12">
                                    No success stories recorded!!
                                    </div>
                                </div>
                                ';
                            }
                        }
?>
            </div>

        </div>
    </div>
</div>

<?php 

} catch (\PDOException $th) {
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}

?>
