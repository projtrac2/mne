<?php
try {

    var_dump($indicator_level. " this is all thats working");

    return;
?>
    <div class="body">
        <div style="margin-top:5px">
            <form id="addindfrm" method="POST" name="addindfrm" action="" enctype="multipart/form-data" autocomplete="off">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-exchange" aria-hidden="true"></i> Baseline Information</legend>
                    <div class="col-md-3">
                        <label>Indicator Code: <?= $row_rsIndicator["indicator_code"] ?></label>
                    </div>
                    <div class="col-md-12">
                        <label>Indicator Name: <?= $row_rsIndicator["indicator_name"] ?></label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:-10px; padding-top:-15px">
                        <input name="projid" type="hidden" value="'.$projid.'">
                        <input name="issueid" type="hidden" value="'.$issueid.'">
                        <input name="type" type="hidden" value="time">
                    </div>
                    <div class="col-md-4">
                        <label>Base-Year *:</label>
                        <div class="form-line">
                            <select name="baseyear" id="baseyear" class="form-control show-tick" data-live-search="true" style="border:#CCC thin solid; border-radius:5px" required>
                                <option value="" selected="selected" class="selection">....Select Base-Year....</option>
                                <?php
                                while ($row_baseyear = $query_baseyear->fetch()) {
                                    if ($yearid == $row_baseyear['id']) {
                                ?>
                                        <option value="<?php echo $row_baseyear['id'] ?>" selected><?php echo $row_baseyear['year'] ?>
                                        <?php
                                    } else {
                                        ?>
                                        <option value="<?php echo $row_baseyear['id'] ?>"><?php echo $row_baseyear['year'] ?>
                                        <?php
                                    }
                                        ?>
                                        </option>
                                    <?php
                                }
                                    ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>Baseline Level *:</label>
                        <div class="form-line">
                            <select name="baselinelevel" id="baselinelevel" class="form-control show-tick" data-live-search="false" style="border:#CCC thin solid; border-radius:5px" required>
                                <option value="" selected="selected" class="selection">....Select Values Level....</option>
                                <option value="1">Top Level</option>
                                <option value="2">Lowest Level</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:-5px">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" style="width:100%">
                                <thead>
                                    <tr id="colrow">
                                        <th width="4%"><strong id="colhead">#</strong></th>
                                        <th width="76%">Location</th>
                                        <th width="20%">Base Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query_location_level1 = $db->prepare("SELECT * FROM tbl_state WHERE state NOT LIKE 'Level%' AND parent IS NULL ORDER BY state ASC");
                                    $query_location_level1->execute();
                                    $nm = 0;
                                    $xy = 0;
                                    function conservancy($comm)
                                    {
                                        global $db;
                                        $query_ward = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:comm ");
                                        $query_ward->execute(array(":comm" => $comm));
                                        $data = 0;
                                        while ($row = $query_ward->fetch()) {
                                            $projlga = $row['id'];
                                            $query_rsLocations = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:id");
                                            $query_rsLocations->execute(array(":id" => $projlga));
                                            $row_rsLocations = $query_rsLocations->fetch();
                                            $total_locations = $query_rsLocations->rowCount();
                                            if ($total_locations > 0) {
                                                $data += 1;
                                            }
                                        }
                                        return $data;
                                    }
                                    while ($rows_level1 = $query_location_level1->fetch()) {
                                        $lv1id = $rows_level1["id"];
                                        $level1 = $rows_level1["state"];
                                        $chandler =  conservancy($lv1id);
                                        if ($chandler > 0) {
                                            $nm++;


                                            if($indicator_level  == 1){
                                                if($lv1id == 1){
                                                    echo '
                                                    <tr style="background-color:#607D8B; color:#FFF">
                                                        <td>' . $nm . '</td>
                                                        <td>' . $level1 . ' ' . $level1label . '</td>
                                                        <td></td>
                                                        
                                                        <input type="hidden" name="lvid[]" value="' . $lv1id . '">
                                                    </tr>';
                                                    $query_location_level2 =  $db->prepare("SELECT * FROM tbl_state WHERE parent = '$lv1id' and state NOT LIKE 'Level%' ORDER BY state ASC");
                                                    $query_location_level2->execute();
                                                    $sr = 0;
                                                    while ($rows_level2 = $query_location_level2->fetch()) {
                                                        $sr++;
                                                        $lv2id = $rows_level2["id"];
                                                        $level2 = $rows_level2["state"];


                                                        echo '<tr style="background-color:#9E9E9E; color:#FFF">
                                                            <td>' . $nm . '.' . $sr . '</td>
                                                            <td>' . $level2 . ' ' . $level2label . '</td>
                                                            <td><input type="hidden" name="lvid' . $nm . '[]" value="' . $lv2id . '"></td>
                                                        </tr>';

                                                        $query_location_level3 =  $db->prepare("SELECT * FROM tbl_state WHERE parent = '$lv2id' and state NOT LIKE 'Level%' ORDER BY state ASC");
                                                        $query_location_level3->execute();
                                                        $total_rows = $query_location_level3->rowCount();
                                                        if ($total_rows > 0) {
                                                            $nmb = 0;
                                                            while ($rows_level3 = $query_location_level3->fetch()) {
                                                                $nmb++;
                                                                $xy++;
                                                                $lv3id = $rows_level3["id"];
                                                                $level3 = $rows_level3["state"];

                                                                if ($diss) {
                                                                    if ($lhandler) {
                                                                        $query_disaggregation_type_loc =  $db->prepare("SELECT * FROM tbl_indicator_level3_disaggregations WHERE indicatorid=:indid AND  level3=:level3 ");
                                                                        $query_disaggregation_type_loc->execute(array(":indid" => $indid, ":level3" => $lv3id));
                                                                        $rows_disstype_loc = $query_disaggregation_type_loc->fetch();
                                                                        $newdisstypecount = $query_disaggregation_type_loc->rowCount();

                                                                        if ($newdisstypecount == 0) {
                                                                            echo '
                                                                            <tr style="background-color:#f2fcf5">
                                                                                <td>' . $nm . '.' . $sr . '.' . $nmb . '</td>
                                                                                <td>' . $level3 . ' ' . $level3label . '</td>
                                                                                <td>
                                                                                <button type="button" class="btn bg-blue-grey btn-block btn-xs waves-effect" data-toggle="modal" data-target="#addlevel3dissModal" onclick="disaggregate(' . $lv3id . ')">Disaggregate
                                                                                </button>
                                                                                </td>
                                                                            </tr>';
                                                                        } else {
                                                                            echo '
                                                                            <tr style="background-color:#f2fcf5">
                                                                                <td>' . $nm . '.' . $sr . '.' . $nmb . '</td>
                                                                                <td>' . $level3 . ' ' . $level3label . '</td> 
                                                                                <td> 
                                                                                </td> 
                                                                            </tr>';

                                                                            $str = 0;
                                                                            do {
                                                                                $str++;
                                                                                $lv4id = $rows_disstype_loc['id'];
                                                                                $level4 = $rows_disstype_loc['disaggregations'];

                                                                                $query_rstotal = $db->prepare("SELECT * FROM tbl_indicator_output_baseline_values WHERE indid='$indid' and level3='$lv3id' AND location='$lv4id'");
                                                                                $query_rstotal->execute();
                                                                                $row_rstotal = $query_rstotal->fetch();
                                                                                $row_rstotals = $query_rstotal->rowCount();

                                                                                if ($row_rstotals > 0) {
                                                                                    echo '
                                                                                    <tr style="background-color:#f2fcf5">
                                                                                        <td>' . $nm . '.' . $sr . '.' . $nmb . '.' . $str . '</td>
                                                                                        <td>' . $level4 . '</td> 
                                                                                        <td>
                                                                                            <input id="level3' . $lv4id . '" type="hidden" value="' . $level4 . '" /> 
                                                                                            <button type="button" class="btn bg-light-green btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_dissedit_inputs(' . $lv3id . ',' . $lv4id . ',' . $indid . ')">Edit Baseline Value
                                                                                            </button>
                                                                                        </td> 
                                                                                    </tr>';
                                                                                } else {
                                                                                    echo '
                                                                                    <tr style="background-color:#f2fcf5">
                                                                                        <td>' . $nm . '.' . $sr . '.' . $nmb . '.' . $str . '</td>
                                                                                        <td>' . $level4 . ' </td> 
                                                                                        <td>
                                                                                            <input id="level3' . $lv4id . '" type="hidden" value="' . $level4 . '" /> 
                                                                                            <button type="button" class="btn bg-blue btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_diss_inputs(' . $lv3id . ',' . $lv4id . ',' . $indid . ')">Add Baseline Value
                                                                                            </button>
                                                                                        </td>
                                                                                    </tr>';
                                                                                }
                                                                            } while ($rows_disstype_loc = $query_disaggregation_type_loc->fetch());
                                                                        }
                                                                    } else {
                                                                        $location = 0;
                                                                        $query_rstotal = $db->prepare("SELECT * FROM tbl_indicator_output_baseline_values WHERE indid='$indid' and level3='$lv3id' ");
                                                                        $query_rstotal->execute();
                                                                        $row_rstotal = $query_rstotal->fetch();
                                                                        $row_rstotals = $query_rstotal->rowCount();

                                                                        if ($row_rstotals > 0) {
                                                                            echo '
                                                                            <tr style="background-color:#f2fcf5">
                                                                                <td>' . $nm . '.' . $sr . '.' . $nmb . '</td>
                                                                                <td>' . $level3 . ' ' . $level3label . '</td> 
                                                                                <td>
                                                                                <input id="level3' . $lv3id . '" type="hidden" value="' . $level3 . '" /> 
                                                                                    <button type="button" class="btn bg-light-green btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_dissedit_inputs(' . $lv3id . ',' . $location . ',' . $indid . ')">Edit Baseline Value
                                                                                    </button>
                                                                                </td> 
                                                                            </tr>';
                                                                        } else {
                                                                            echo '
                                                                            <tr style="background-color:#f2fcf5">
                                                                                <td>' . $nm . '.' . $sr . '.' . $nmb . '</td>
                                                                                <td>' . $level3 . ' ' . $level3label . '</td> 
                                                                                <td>
                                                                                    <input id="level3' . $lv3id . '" type="hidden" value="' . $level3 . '" /> 
                                                                                    <button type="button" class="btn bg-blue btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_diss_inputs(' . $lv3id . ',' . $location . ',' . $indid . ')">Add Baseline Value
                                                                                    </button>
                                                                                </td>
                                                                            </tr>';
                                                                        }
                                                                    }
                                                                } else {
                                                                    $query_rstotal = $db->prepare("SELECT * FROM tbl_indicator_output_baseline_values WHERE indid='$indid' and level3='$lv3id' ");
                                                                    $query_rstotal->execute();
                                                                    $row_rstotal = $query_rstotal->fetch();
                                                                    $row_rstotals = $query_rstotal->rowCount();

                                                                    if ($row_rstotals > 0) {
                                                                        echo '
                                                                        <tr style="background-color:#f2fcf5">
                                                                            <td>' . $nm . '.' . $sr . '.' . $nmb . '</td>
                                                                            <td>' . $level3 . ' ' . $level3label . '</td>
                                                                            <td>
                                                                            <input id="level3' . $lv3id . '" type="hidden" value="' . $level3 . '" /> 
                                                                            <button type="button" class="btn bg-light-green btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_inddissedit_inputs(' . $lv3id . ')">Edit Baseline Value
                                                                            </button>
                                                                        </td>
                                                                        </tr>';
                                                                    } else {
                                                                        echo '
                                                                        <tr style="background-color:#f2fcf5">
                                                                            <td>' . $nm . '.' . $sr . '.' . $nmb . '</td>
                                                                            <td>' . $level3 . ' ' . $level3label . '</td> 
                                                                            <td>
                                                                                <input id="level3' . $lv3id . '" type="hidden" value="' . $level3 . '" /> 
                                                                                <button type="button" class="btn bg-blue btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_inddiss_inputs(' . $lv3id . ')">Add Baseline Value
                                                                                </button>
                                                                            </td>
                                                                        </tr>';
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            } else {
                                                echo '
                                                <tr style="background-color:#607D8B; color:#FFF">
                                                    <td>' . $nm . '</td>
                                                    <td>' . $level1 . ' ' . $level1label . '</td>
                                                    <td></td>
                                                    
                                                    <input type="hidden" name="lvid[]" value="' . $lv1id . '">
                                                </tr>';

                                                $query_location_level2 =  $db->prepare("SELECT * FROM tbl_state WHERE parent = '$lv1id' and state NOT LIKE 'Level%' ORDER BY state ASC");
                                                $query_location_level2->execute();
                                                $sr = 0;
                                                while ($rows_level2 = $query_location_level2->fetch()) {
                                                    $sr++;
                                                    $lv2id = $rows_level2["id"];
                                                    $level2 = $rows_level2["state"];


                                                    echo '<tr style="background-color:#9E9E9E; color:#FFF">
                                                        <td>' . $nm . '.' . $sr . '</td>
                                                        <td>' . $level2 . ' ' . $level2label . '</td>
                                                        <td><input type="hidden" name="lvid' . $nm . '[]" value="' . $lv2id . '"></td>
                                                    </tr>';

                                                    $query_location_level3 =  $db->prepare("SELECT * FROM tbl_state WHERE parent = '$lv2id' and state NOT LIKE 'Level%' ORDER BY state ASC");
                                                    $query_location_level3->execute();
                                                    $total_rows = $query_location_level3->rowCount();
                                                    if ($total_rows > 0) {
                                                        $nmb = 0;
                                                        while ($rows_level3 = $query_location_level3->fetch()) {
                                                            $nmb++;
                                                            $xy++;
                                                            $lv3id = $rows_level3["id"];
                                                            $level3 = $rows_level3["state"];

                                                            if ($diss) {
                                                                if ($lhandler) {
                                                                    $query_disaggregation_type_loc =  $db->prepare("SELECT * FROM tbl_indicator_level3_disaggregations WHERE indicatorid=:indid AND  level3=:level3 ");
                                                                    $query_disaggregation_type_loc->execute(array(":indid" => $indid, ":level3" => $lv3id));
                                                                    $rows_disstype_loc = $query_disaggregation_type_loc->fetch();
                                                                    $newdisstypecount = $query_disaggregation_type_loc->rowCount();

                                                                    if ($newdisstypecount == 0) {
                                                                        echo '
                                                                        <tr style="background-color:#f2fcf5">
                                                                            <td>' . $nm . '.' . $sr . '.' . $nmb . '</td>
                                                                            <td>' . $level3 . ' ' . $level3label . '</td>
                                                                            <td>
                                                                            <button type="button" class="btn bg-blue-grey btn-block btn-xs waves-effect" data-toggle="modal" data-target="#addlevel3dissModal" onclick="disaggregate(' . $lv3id . ')">Disaggregate
                                                                            </button>
                                                                            </td>
                                                                        </tr>';
                                                                    } else {
                                                                        echo '
                                                                        <tr style="background-color:#f2fcf5">
                                                                            <td>' . $nm . '.' . $sr . '.' . $nmb . '</td>
                                                                            <td>' . $level3 . ' ' . $level3label . '</td> 
                                                                            <td> 
                                                                            </td> 
                                                                        </tr>';

                                                                        $str = 0;
                                                                        do {
                                                                            $str++;
                                                                            $lv4id = $rows_disstype_loc['id'];
                                                                            $level4 = $rows_disstype_loc['disaggregations'];

                                                                            $query_rstotal = $db->prepare("SELECT * FROM tbl_indicator_output_baseline_values WHERE indid='$indid' and level3='$lv3id' AND location='$lv4id'");
                                                                            $query_rstotal->execute();
                                                                            $row_rstotal = $query_rstotal->fetch();
                                                                            $row_rstotals = $query_rstotal->rowCount();

                                                                            if ($row_rstotals > 0) {
                                                                                echo '
                                                                                <tr style="background-color:#f2fcf5">
                                                                                    <td>' . $nm . '.' . $sr . '.' . $nmb . '.' . $str . '</td>
                                                                                    <td>' . $level4 . '</td> 
                                                                                    <td>
                                                                                        <input id="level3' . $lv4id . '" type="hidden" value="' . $level4 . '" /> 
                                                                                        <button type="button" class="btn bg-light-green btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_dissedit_inputs(' . $lv3id . ',' . $lv4id . ',' . $indid . ')">Edit Baseline Value
                                                                                        </button>
                                                                                    </td> 
                                                                                </tr>';
                                                                            } else {
                                                                                echo '
                                                                                <tr style="background-color:#f2fcf5">
                                                                                    <td>' . $nm . '.' . $sr . '.' . $nmb . '.' . $str . '</td>
                                                                                    <td>' . $level4 . ' </td> 
                                                                                    <td>
                                                                                        <input id="level3' . $lv4id . '" type="hidden" value="' . $level4 . '" /> 
                                                                                        <button type="button" class="btn bg-blue btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_diss_inputs(' . $lv3id . ',' . $lv4id . ',' . $indid . ')">Add Baseline Value
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>';
                                                                            }
                                                                        } while ($rows_disstype_loc = $query_disaggregation_type_loc->fetch());
                                                                    }
                                                                } else {
                                                                    $location = 0;
                                                                    $query_rstotal = $db->prepare("SELECT * FROM tbl_indicator_output_baseline_values WHERE indid='$indid' and level3='$lv3id' ");
                                                                    $query_rstotal->execute();
                                                                    $row_rstotal = $query_rstotal->fetch();
                                                                    $row_rstotals = $query_rstotal->rowCount();

                                                                    if ($row_rstotals > 0) {
                                                                        echo '
                                                                        <tr style="background-color:#f2fcf5">
                                                                            <td>' . $nm . '.' . $sr . '.' . $nmb . '</td>
                                                                            <td>' . $level3 . ' ' . $level3label . '</td> 
                                                                            <td>
                                                                            <input id="level3' . $lv3id . '" type="hidden" value="' . $level3 . '" /> 
                                                                                <button type="button" class="btn bg-light-green btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_dissedit_inputs(' . $lv3id . ',' . $location . ',' . $indid . ')">Edit Baseline Value
                                                                                </button>
                                                                            </td> 
                                                                        </tr>';
                                                                    } else {
                                                                        echo '
                                                                        <tr style="background-color:#f2fcf5">
                                                                            <td>' . $nm . '.' . $sr . '.' . $nmb . '</td>
                                                                            <td>' . $level3 . ' ' . $level3label . '</td> 
                                                                            <td>
                                                                                <input id="level3' . $lv3id . '" type="hidden" value="' . $level3 . '" /> 
                                                                                <button type="button" class="btn bg-blue btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_diss_inputs(' . $lv3id . ',' . $location . ',' . $indid . ')">Add Baseline Value
                                                                                </button>
                                                                            </td>
                                                                        </tr>';
                                                                    }
                                                                }
                                                            } else {
                                                                $query_rstotal = $db->prepare("SELECT * FROM tbl_indicator_output_baseline_values WHERE indid='$indid' and level3='$lv3id' ");
                                                                $query_rstotal->execute();
                                                                $row_rstotal = $query_rstotal->fetch();
                                                                $row_rstotals = $query_rstotal->rowCount();

                                                                if ($row_rstotals > 0) {
                                                                    echo '
                                                                    <tr style="background-color:#f2fcf5">
                                                                        <td>' . $nm . '.' . $sr . '.' . $nmb . '</td>
                                                                        <td>' . $level3 . ' ' . $level3label . '</td>
                                                                        <td>
                                                                        <input id="level3' . $lv3id . '" type="hidden" value="' . $level3 . '" /> 
                                                                        <button type="button" class="btn bg-light-green btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_inddissedit_inputs(' . $lv3id . ')">Edit Baseline Value
                                                                        </button>
                                                                    </td>
                                                                    </tr>';
                                                                } else {
                                                                    echo '
                                                                    <tr style="background-color:#f2fcf5">
                                                                        <td>' . $nm . '.' . $sr . '.' . $nmb . '</td>
                                                                        <td>' . $level3 . ' ' . $level3label . '</td> 
                                                                        <td>
                                                                            <input id="level3' . $lv3id . '" type="hidden" value="' . $level3 . '" /> 
                                                                            <button type="button" class="btn bg-blue btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_inddiss_inputs(' . $lv3id . ')">Add Baseline Value
                                                                            </button>
                                                                        </td>
                                                                    </tr>';
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
                            <input name="user_name" type="hidden" value="<?php echo $user_name; ?>" />
                            <input name="indicator" type="hidden" id="indicator" value="<?php echo $indid; ?>" />
                            <input name="disaggregated" type="hidden" id="disaggregated" value="<?php echo $diss; ?>" />
                            <div class="btn-group">
                                <input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="Submit" />
                            </div>
                            <input type="hidden" name="MM_insert" value="addindfrm" />
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>


    <!-- Modal Request Payment -->
    <div class="modal fade" id="addlevel3dissModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title" align="center">
                        <font color="#FFF">LOCATION DISAGGREGATION FORM</font>
                    </h3>
                </div>
                <form class="tagForm" action="assets/processor/indicator-existing-baseline-data-processor.php" method="post" id="indicator-disaggregation-form" enctype="multipart/form-data">
                    <div class="modal-body" id="level3locdisaggregation">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="modal-body">
                                    <label>Location Disaggregations (Separate using a comma)*:</label>
                                    <div class="form-input">
                                        <input type="text" name="level3disaggregations" id="level3disaggregations" class="form-control" value="" required="required" title="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4" align="center">
                                    <input type="hidden" name="indid1" id="indid1" class="form-control" value="<?= $indid ?>">
                                    <input type="hidden" name="level3dis" id="level3dis" class="form-control" value="">
                                    <input name="submit" type="submit" class="btn btn-success waves-effect waves-light" id="tag-form-submit" value="Save" />
                                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                                </div>
                                <div class="col-md-4">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- #END# Modal Request Payment -->


    <!-- Modal Request Payment -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title" align="center">
                        <font color="#FFF"><span id="locationName"></span> BASELINE VALUES</font>
                    </h3>
                </div>
                <form class="tagForm" action="assets/processor/indicator-existing-baseline-data-processor.php" method="post" id="indicator-baseline-form" enctype="multipart/form-data">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="modal-body">
                                <div id="baselineform"></div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4" align="center">
                                    <input type="hidden" name="indid" id="indid" class="form-control" value="<?= $indid ?>">
                                    <input type="hidden" name="level3" id="level3" class="form-control" value="">
                                    <input type="hidden" name="addbaseline" id="addbaseline" class="form-control" value="new">
                                    <input type="hidden" name="location" id="location" class="form-control" value="0">
                                    <input name="submit" type="submit" class="btn btn-success waves-effect waves-light" id="tag-form-base-submit" value="Save" />
                                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                                </div>
                                <div class="col-md-4">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- #END# Modal Request Payment -->

<?php
} catch (PDOException $ex) {
    function flashMessage($data)
    {
        return $data;
    }
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $result;
}
?>