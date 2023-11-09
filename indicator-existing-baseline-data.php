<?php
require('includes/head.php');
if ($permission && isset($_GET['ind']) && !empty($_GET["ind"])) {
    $yrid = "";
    try {
        $decode_indid =  base64_decode($_GET['ind']);
        $indid_array = explode("opid", $decode_indid);
        $indid = $indid_array[1];

        $query_rsIndicator =  $db->prepare("SELECT * FROM tbl_indicator WHERE indid = :indid and active = '1'");
        $query_rsIndicator->execute(array(":indid" => $indid));
        $row_rsIndicator = $query_rsIndicator->fetch();
        $totalrow_rsIndicator = $query_rsIndicator->rowCount();

        if ($totalrow_rsIndicator > 0) {
            $diss = $row_rsIndicator['indicator_disaggregation'];
            $unit = $row_rsIndicator['indicator_unit'];
            $indicator_level = $row_rsIndicator['indicator_baseline_level'];
            $baseline = $row_rsIndicator['baseline'];
            $indicator_code = $row_rsIndicator["indicator_code"];

            $query_opunit = $db->prepare("SELECT unit FROM  tbl_measurement_units  WHERE id ='$unit'");
            $query_opunit->execute();
            $row = $query_opunit->fetch();
            $opunit = $row['unit'];

            $indicator =  $opunit . " of " . $row_rsIndicator["indicator_name"];


            $query_rsbaseYear = $db->prepare("SELECT * FROM tbl_indicator_baseline_years WHERE indid=:indid");
            $query_rsbaseYear->execute(array(":indid" => $indid));
            $row_rsbaseYear = $query_rsbaseYear->fetch();
            $total = $query_rsbaseYear->rowCount();

            $yearid = ($total > 0) ? $row_rsbaseYear['year'] : '';
            $yrid = $yearid;

            $query_rstotal = $db->prepare("SELECT SUM(value) as value FROM tbl_indicator_output_baseline_values WHERE indid='$indid' ");
            $query_rstotal->execute();
            $row_rstotal = $query_rstotal->fetch();
            $base_val1 = $row_rstotal['value'] != null  ? $row_rstotal['value'] : 0;


            if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addindfrm")) {
                $indicator = $_POST['indicator'];
                $baseyear = $_POST['baseyear'];
                $inddate = date("Y-m-d");
                $user = $_POST['user_name'];
                $baseline = 1;

                // delete from
                $deleteQuery = $db->prepare("DELETE FROM `tbl_indicator_baseline_years` WHERE indid=:indicatorid");
                $results = $deleteQuery->execute(array(':indicatorid' => $indid));

                //insert
                $insertSQL = $db->prepare("INSERT INTO tbl_indicator_baseline_years (indid, year, datecreated)  VALUES (:indid, :year, :datecreated)");
                $insertquery = $insertSQL->execute(array(':indid' => $indicator, ":year" => $baseyear, ':datecreated' => $inddate));

                if ($insertquery) {
                    $updatequery = $db->prepare("UPDATE tbl_indicator SET baseline=:baseline WHERE indid=:indid");
                    $updateresult = $updatequery->execute(array(":baseline" => $baseline, ":indid" => $indicator));

                    if ($updateresult) {
                        $msg = 'Baseline information successfully added';
                        $url = "view-indicators";
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
                    }
                } else {
                    $msg = 'Failed!! Baseline information was not added!!';
                    $results = "<script type=\"text/javascript\">
				swal({
					title: \"Error!\",
					text: \" $msg\",
					type: 'Danger',
					timer: 3000,
					showConfirmButton: false });
			</script>";
                }
            }
?>
            <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

            <!-- start body  -->
            <section class="content">
                <div class="container-fluid">
                    <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                        <h4 class="contentheader">
                            <i class="fa fa-columns" aria-hidden="true"></i>
                            <?php echo $pageTitle ?>
                            <div class="btn-group" style="float:right">
                                <button onclick="history.go(-1)" class="btn bg-orange waves-effect pull-right" style="margin-right: 10px">
                                    Go Back
                                </button>
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
                                                <li class="list-group-item list-group-item list-group-item-action active">Indicator: <?= $indicator ?> </li>
                                                <li class="list-group-item"><strong>Indicator Code: </strong> <?= $indicator_code ?> </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="body">
                                    <form id="addindfrm" method="POST" name="addindfrm" action="" enctype="multipart/form-data" autocomplete="off">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-exchange" aria-hidden="true"></i> Baseline Information</legend>
                                            <?php
                                            if ($indicator_level == 2) {
                                            ?>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-left:0px; padding-right:0px" id="levels">
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <label>Sub-county*:</label>
                                                        <div class="form-line">
                                                            <?php
                                                            $query_subcounty = $db->prepare("SELECT * FROM  tbl_state WHERE active = 1 and parent IS NULL AND id != 1 ");
                                                            $query_subcounty->execute();
                                                            ?>
                                                            <select name="subcounty[]" id="subcounty" data-actions-box="true" class="form-control show-tick selectpicker" title="Choose Multipe" multiple style="border:#CCC thin solid; border-radius:5px; width:98%; padding-left:50px">
                                                                <?php
                                                                while ($row_subcounty = $query_subcounty->fetch()) {
                                                                ?>
                                                                    <option value="<?php echo $row_subcounty['id'] ?>"><?php echo $row_subcounty['state'] ?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <label>Ward:</label>
                                                        <div class="form-line" id="inddeparment">
                                                            <select name="ward[]" id="ward" data-actions-box="true" class="form-control show-tick selectpicker" title="Choose Multipe" multiple style="border:#CCC thin solid; border-radius:5px; width:98%; padding-left:50px">
                                                                <option value="" selected="selected" class="selection">....Select Ward....</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            } else {
                                            ?>
                                                <input name="subcounty[]" type="hidden" id="subcounty">
                                                <input name="ward[]" type="hidden" id="ward">
                                                <input name="loc[]" type="hidden" id="loc">
                                            <?php
                                            }
                                            ?>
                                            <input type="hidden" name="indicator_id" id="indicator_id" value="<?= $indid ?>">
                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                <label>Base-Year *:</label>
                                                <div class="form-line">
                                                    <?php
                                                    if ($baseline == 0) { ?>
                                                        <select name="baseyear" id="baseyear" class="form-control show-tick" data-live-search="true" style="border:#CCC thin solid; border-radius:5px" required>
                                                            <option value="" selected="selected" class="selection">....Select Base-Year....</option>
                                                            <?php
                                                            $query_baseyear = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE status=1");
                                                            $query_baseyear->execute();
                                                            while ($row_baseyear = $query_baseyear->fetch()) {
                                                                $current_year = date('Y');
                                                                if ($row_baseyear['year'] <= $current_year) {
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
                                                            }
                                                                ?>
                                                        </select>
                                                    <?php
                                                    } else {
                                                        $query_year = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE id = '$yrid'");
                                                        $query_year->execute();
                                                        $row_year = $query_year->fetch();
                                                        $count_year = $query_year->rowCount();
                                                        echo ($count_year  > 0) ? $row_year['year'] : "";
                                                    } ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-5 col-sm-12 col-xs-12">
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                                <div class="form-control text-center" id="base_val">
                                                    <?= $base_val1 ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:-5px">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped table-hover" style="width:100%">
                                                        <thead>
                                                            <tr id="colrow">
                                                                <th width="4%"><strong id="colhead">#</strong></th>
                                                                <th width="76%">Location</th>
                                                                <th width="20%" class="text-center">Base Value</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="scbody">
                                                            <?php
                                                            $level = $indicator_level == 1 ? 1 : 0;
                                                            $arr_ = [];
                                                            $query_location_level1 = $db->prepare("SELECT * FROM tbl_state WHERE level=:level AND parent IS NULL ORDER BY state ASC");
                                                            $query_location_level1->execute(array(":level" =>  $level));
                                                            $row_level1 = $query_location_level1->rowCount();
                                                            if ($row_level1 > 0) {
                                                                $nm = 0;
                                                                while ($rows_level1 = $query_location_level1->fetch()) {
                                                                    $lv1id = $rows_level1["id"];
                                                                    $level1 = $rows_level1["state"];

                                                                    $query_location_level2 =  $db->prepare("SELECT * FROM tbl_state WHERE parent =:parent  ORDER BY state ASC");
                                                                    $query_location_level2->execute(array(":parent" => $lv1id));
                                                                    $row_level2 = $query_location_level2->rowCount();
                                                                    if ($row_level2 > 0) {
                                                                        $nm++;
                                                                        $query_lvtwosum = $db->prepare("SELECT sum(value) AS sumvalue FROM tbl_indicator_output_baseline_values b inner join tbl_state s on s.id=b.level3 WHERE indid=:indid and parent=:parent");
                                                                        $query_lvtwosum->execute(array(":indid" => $indid, ":parent" => $lv1id));
                                                                        $row_lvtwosum = $query_lvtwosum->fetch();
                                                                        $lvonesum = $row_lvtwosum["sumvalue"] != null ? $row_lvtwosum["sumvalue"] : 0;
                                                            ?>
                                                                        <tr style="background-color:#607D8B; color:#FFF">
                                                                            <td><?= $nm ?></td>
                                                                            <td><?= $level1 . ' ' . $level1label  ?></td>
                                                                            <td align="center"><?= number_format($lvonesum, 0)  ?></td>
                                                                            <input type="hidden" name="lvid[]" value="<?= $lv1id  ?>">
                                                                        </tr>
                                                                        <?php
                                                                        $sr = 0;
                                                                        while ($rows_level2 = $query_location_level2->fetch()) {
                                                                            $sr++;
                                                                            $lv2id = $rows_level2["id"];
                                                                            $level2 = $rows_level2["state"];

                                                                            $query_rstotal = $db->prepare("SELECT * FROM tbl_indicator_output_baseline_values WHERE indid=:indid and level3=:lv2id ");
                                                                            $query_rstotal->execute(array(":indid" => $indid, ":lv2id" => $lv2id));
                                                                            $row_rstotal = $query_rstotal->fetch();
                                                                            $row_rstotals = $query_rstotal->rowCount();
                                                                            $base_val = ($row_rstotals > 0) ? $row_rstotal['value'] : 0;

                                                                            if ($row_rstotals > 0) {
                                                                                $base_val = $row_rstotal['value'];
                                                                                $arr_[] = true;
                                                                        ?>
                                                                                <tr style="background-color:#f2fcf5">
                                                                                    <td><?= $nm . '.' . $sr ?></td>
                                                                                    <td><?= $level2 . ' ' . $level2label ?></td>
                                                                                    <td>
                                                                                        <input id="level2<?= $lv2id ?>" type="hidden" value="<?= $level2 ?>" />
                                                                                        <button type="button" class="btn bg-light-green btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_inddiss_inputs(<?= $lv2id ?>, <?= $base_val ?>)">
                                                                                            Edit Baseline Value (<?= $base_val ?>)
                                                                                        </button>
                                                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                        <button type="button" class="btn bg-red btn-block btn-xs waves-effect" onclick="delete_baseline(<?= $lv2id ?>)">
                                                                                            Delete Baseline Value
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php
                                                                            } else {
                                                                            ?>
                                                                                <tr style="background-color:#f2fcf5">
                                                                                    <td><?= $nm . '.' . $sr ?></td>
                                                                                    <td><?= $level2 . ' ' . $level2label ?></td>
                                                                                    <td>
                                                                                        <input id="level2<?= $lv2id ?>" type="hidden" value="<?= $level2 ?>" />
                                                                                        <button type="button" class="btn bg-blue btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_inddiss_inputs(<?= $lv2id ?>, <?= '' ?>)">Add Baseline Value
                                                                                        </button>

                                                                                    </td>
                                                                                </tr>
                                                            <?php
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
                                            <input class="base_value_type" name="base_value_type" type="hidden" value="<?= in_array(true, $arr_) ? 1 : 0 ?>" />
                                            <?php if ($baseline == 0) { ?>
                                                <div class="row clearfix" id="submit_div">
                                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
                                                        <input name="user_name" type="hidden" value="<?php echo $colname_rsMyP; ?>" />
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
                                            <?php } ?>
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
            <!-- end body  -->

            <!-- Modal Request Payment -->
            <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#03A9F4">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h3 class="modal-title" align="center">
                                <font color="#FFF"><span id="locationName"></span> Base Values</font>
                            </h3>
                        </div>
                        <form class="tagForm" action="" method="post" id="indicator_baseline_form" enctype="multipart/form-data">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="modal-body">
                                        <div class="col-md-5">
                                            <label for="base_val" id="">Baseline Value *:</label>
                                            <div class="form-input">
                                                <input type="number" name="base_value" id="base_val_id" value="" min="0" placeholder="Enter Value" class="form-control" required>
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
            <!-- end body  -->
<?php
        } else {
            $results =  restriction();
            echo $results;
        }
    } catch (PDOException $ex) {
        $result = flashMessage("An error occurred: " . $ex->getMessage());
        echo $result;
    }
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>
<script src="assets/custom js/indicator-existing-baseline-data.js"></script>