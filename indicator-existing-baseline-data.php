<?php
require('functions/strategicplan.php');
$pageName = "Strategic Plans";
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";

require('includes/head.php');
if ($permission) {
    $pageTitle ="Output Indicator Baseline";
    $yrid = "";
    try {
        $results = '';
        function clocation($indid)
        {
            global $db;
            $query_disaggregation_type =  $db->prepare("SELECT t.id, s.type, s.category FROM tbl_indicator_measurement_variables_disaggregation_type t
            inner join tbl_indicator_disaggregation_types s ON s.id=t.disaggregation_type
            WHERE indicatorid=:indid ORDER BY s.type ASC");
            $query_disaggregation_type->execute(array(":indid" => $indid));
            $disstypecount = $query_disaggregation_type->rowCount();
            $row_rsTypes = $query_disaggregation_type->fetch();
            $data_arr = array();
            if ($disstypecount > 0) {
                do {
                    $type = $row_rsTypes['type'];
                    if ($type == 0) {
                        array_push($data_arr, true);
                    } else {
                        array_push($data_arr, false);
                    }
                } while ($row_rsTypes = $query_disaggregation_type->fetch());
            }

            if (in_array(true, $data_arr)) {
                return true;
            } else {
                return false;
            }
        }

        $indicator_level = "";
        if (isset($_GET["ind"]) && !empty($_GET["ind"])) {
            $indid = base64_decode($_GET['ind']);
            $query_rsIndicator =  $db->prepare("SELECT * FROM tbl_indicator WHERE indid = :indid and active = '1'");
            $query_rsIndicator->execute(array(":indid" => $indid));
            $row_rsIndicator = $query_rsIndicator->fetch();
            $diss = $row_rsIndicator['indicator_disaggregation'];
            $unit = $row_rsIndicator['indicator_unit'];
            $indicator_level = $row_rsIndicator['indicator_baseline_level'];
            $baseline = $row_rsIndicator['baseline'];
            $lhandler = clocation($indid);

            $query_opunit = $db->prepare("SELECT unit FROM  tbl_measurement_units  WHERE id ='$unit'");
            $query_opunit->execute();
            $row = $query_opunit->fetch();
            $opunit = $row['unit'];


            $query_rsbaseYear = $db->prepare("SELECT * FROM tbl_indicator_baseline_years WHERE indid=:indid");
            $query_rsbaseYear->execute(array(":indid" => $indid));
            $row_rsbaseYear = $query_rsbaseYear->fetch();
            $total = $query_rsbaseYear->rowCount();

            $yearid = ($total > 0) ? $row_rsbaseYear['year'] : '';
            $yrid = $yearid;
        }

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
                    $url = "add-indicators";
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

        $query_rsOpDept = $db->prepare("SELECT * FROM tbl_sectors WHERE parent='0' AND deleted='0'");
        $query_rsOpDept->execute();
        $row_rsOpDept = $query_rsOpDept->fetch();

        $query_baseyear = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE status=1");
        $query_baseyear->execute();

        $query_rstotal = $db->prepare("SELECT SUM(value) as value FROM tbl_indicator_output_baseline_values WHERE indid='$indid' ");
        $query_rstotal->execute();
        $row_rstotal = $query_rstotal->fetch();
        $row_rstotals = $query_rstotal->rowCount();
        $base_val1  = 0;
        if ($row_rstotals > 0) {
            $base_val1 = $row_rstotal['value'];
        }
    } catch (PDOException $ex) {
        $result = flashMessage("An error occurred: " . $ex->getMessage());
        echo $result;
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
                          <form id="addindfrm" method="POST" name="addindfrm" action="" enctype="multipart/form-data" autocomplete="off">
                              <fieldset class="scheduler-border">
                                  <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-exchange" aria-hidden="true"></i> Baseline Information</legend>
                                  <div class="col-md-3">
                                      <label>Indicator Code: <?= $row_rsIndicator["indicator_code"] ?></label>
                                  </div>
                                  <div class="col-md-12">
                                      <label>Indicator Name: <?= $opunit . " of " . $row_rsIndicator["indicator_name"] ?></label>
                                  </div>
                                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:-10px; padding-top:-15px">
                                      <input name="projid" type="hidden" value="'.$projid.'">
                                      <input name="issueid" type="hidden" value="'.$issueid.'">
                                      <input name="type" type="hidden" value="time">
                                  </div>
                                  <div class="col-md-4">
                                      <label>Base-Year *:</label>
                                      <div class="form-line">
                                          <?php
                                          if ($baseline == 0) { ?>
                                              <select name="baseyear" id="baseyear" class="form-control show-tick" data-live-search="true" style="border:#CCC thin solid; border-radius:5px" required>
                                                  <option value="" selected="selected" class="selection">....Select Base-Year....</option>
                                                  <?php
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
                                  <div class="col-md-6">

                                  </div>
                                  <div class="col-md-2">
                                      <div class="form-control text-center">
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
                                              <tbody>
                                                  <?php
                                                  $query_location_level1 = $db->prepare("SELECT * FROM tbl_state WHERE state NOT LIKE 'All%' AND parent IS NULL ORDER BY state ASC");
                                                  $query_location_level1->execute();
                                                  $nm = 0;
                                                  $xy = 0;
                                                  function level1($comm)
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
                                                      $chandler =  level1($lv1id);
                                                      $lvonesum = 0;

                                                      $query_lvoneids = $db->prepare("SELECT id FROM tbl_state WHERE parent='$lv1id'");
                                                      $query_lvoneids->execute();
                                                      while ($row_lvoneids = $query_lvoneids->fetch()) {
                                                          $lvoneids = $row_lvoneids["id"];

                                                          $query_lvonesum = $db->prepare("SELECT sum(value) AS sumvalue FROM tbl_indicator_output_baseline_values b inner join tbl_state s on s.id=b.level3 WHERE indid='$indid' and parent='$lvoneids'");
                                                          $query_lvonesum->execute();
                                                          $row_lvonesum = $query_lvonesum->fetch();
                                                          $lvonesum = $lvonesum + $row_lvonesum["sumvalue"];
                                                      }


                                                      if ($chandler > 0) {
                                                          $nm++;
                                                          $query_location_level2 =  $db->prepare("SELECT * FROM tbl_state WHERE parent = '$lv1id' and state NOT LIKE 'All%' ORDER BY state ASC");
                                                          $query_location_level2->execute();
                                                          $sr = 0;

                                                          if ($indicator_level == 1) {

                                                              if ($lv1id == 1) {
                                                                  echo '
                                                                  <tr style="background-color:#607D8B; color:#FFF">
                                                                      <td>' . $nm . '</td>
                                                                      <td>' . $level1 . ' ' . $level1label . '</td>
                                                                      <td align="center">' . number_format($lvonesum, 0) . '</td>
                                                                      <input type="hidden" name="lvid[]" value="' . $lv1id . '">
                                                                  </tr>';
                                                                  while ($rows_level2 = $query_location_level2->fetch()) {
                                                                      $sr++;
                                                                      $lv2id = $rows_level2["id"];
                                                                      $level2 = $rows_level2["state"];

                                                                      $query_lvtwosum = $db->prepare("SELECT sum(value) AS sumvalue FROM tbl_indicator_output_baseline_values b inner join tbl_state s on s.id=b.level3 WHERE indid='$indid' and parent='$lv2id'");
                                                                      $query_lvtwosum->execute();
                                                                      $row_lvtwosum = $query_lvtwosum->fetch();
                                                                      $lvtwosum = $row_lvtwosum["sumvalue"];
                                                                      echo '<tr style="background-color:#9E9E9E; color:#FFF">
                                                                          <td>' . $nm . '.' . $sr . '</td>
                                                                          <td>' . $level2 . ' ' . $level2label . '</td>
                                                                          <td align="center"><input type="hidden" name="lvid' . $nm . '[]" value="' . $lv2id . '">' . number_format($lvtwosum, 0) . '</td>
                                                                      </tr>';

                                                                      $query_location_level3 =  $db->prepare("SELECT * FROM tbl_state WHERE parent = '$lv2id' and state NOT LIKE 'All%' ORDER BY state ASC");
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
                                                                                              if ($baseline == 0) {
                                                                                                  if ($row_rstotals > 0) {
                                                                                                      $base_val = $row_rstotal['value'];
                                                                                                      echo '
                                                                                                      <tr style="background-color:#f2fcf5">
                                                                                                          <td>' . $nm . '.' . $sr . '.' . $nmb . '.' . $str . '</td>
                                                                                                          <td>' . $level4 . '</td>
                                                                                                          <td>
                                                                                                              <input id="level3' . $lv4id . '" type="hidden" value="' . $level4 . '" />
                                                                                                              <button type="button" class="btn bg-light-green btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_dissedit_inputs(' . $lv3id . ',' . $lv4id . ',' . $indid . ')">
                                                                                                                  Edit Baseline Value (' . $base_val . ')
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
                                                                                              } else {
                                                                                                  $value = $row_rstotal['value'];
                                                                                                  echo '
                                                                                                  <tr style="background-color:#f2fcf5">
                                                                                                      <td>' . $nm . '.' . $sr . '.' . $nmb . '.' . $str . '</td>
                                                                                                      <td>' . $level4 . ' </td>
                                                                                                      <td>
                                                                                                          <input id="level3' . $lv4id . '" type="text" class="text-center" disabled value="' . $value . '" />
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

                                                                                      if ($baseline == 0) {
                                                                                          $base_val = $row_rstotal['value'];

                                                                                          if ($row_rstotals > 0) {
                                                                                              echo '
                                                                                              <tr style="background-color:#f2fcf5">
                                                                                                  <td>' . $nm . '.' . $sr . '.' . $nmb . '</td>
                                                                                                  <td>' . $level3 . ' ' . $level3label . '</td>
                                                                                                  <td>
                                                                                                  <input id="level3' . $lv3id . '" type="hidden" value="' . $level3 . '" />
                                                                                                      <button type="button" class="btn bg-light-green btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_dissedit_inputs(' . $lv3id . ',' . $location . ',' . $indid . ')">
                                                                                                          Edit Baseline Value (' . $base_val . ')
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
                                                                                      } else {
                                                                                          $value = $row_rstotal['value'];
                                                                                          echo '
                                                                                          <tr style="background-color:#f2fcf5">
                                                                                              <td>' . $nm . '.' . $sr . '.' . $nmb . '</td>
                                                                                              <td>' . $level3 . ' ' . $level3label . '</td>
                                                                                              <td>
                                                                                                  <input id="level3' . $lv3id . '" type="text" class="text-center" disabled value="' . $value . '" />
                                                                                              </td>
                                                                                          </tr>';
                                                                                      }
                                                                                  }
                                                                              } else {
                                                                                  $query_rstotal = $db->prepare("SELECT * FROM tbl_indicator_output_baseline_values WHERE indid='$indid' and level3='$lv3id' ");
                                                                                  $query_rstotal->execute();
                                                                                  $row_rstotal = $query_rstotal->fetch();
                                                                                  $row_rstotals = $query_rstotal->rowCount();

                                                                                  if ($baseline == 0) {
                                                                                      $base_val = $row_rstotal['value'];
                                                                                      if ($row_rstotals > 0) {
                                                                                          echo '
                                                                                          <tr style="background-color:#f2fcf5">
                                                                                              <td>' . $nm . '.' . $sr . '.' . $nmb . '</td>
                                                                                              <td>' . $level3 . ' ' . $level3label . '</td>
                                                                                              <td>
                                                                                              <input id="level3' . $lv3id . '" type="hidden" value="' . $level3 . '" />
                                                                                              <button type="button" class="btn bg-light-green btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_inddissedit_inputs(' . $lv3id . ')">
                                                                                                  Edit Baseline Value (' . $base_val . ')
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
                                                                                  } else {

                                                                                      //-------------Take note here--------------------------------
                                                                                      if (!empty($row_rstotal)) {
                                                                                          $value = $row_rstotal['value'];
                                                                                      } else {
                                                                                          $value = 0;
                                                                                      }
                                                                                      //-----------------------------------------------------------
                                                                                      echo '
                                                                                      <tr style="background-color:#f2fcf5">
                                                                                          <td>' . $nm . '.' . $sr . '.' . $nmb . '</td>
                                                                                          <td>' . $level3 . ' ' . $level3label . '</td>
                                                                                          <td>
                                                                                              <input id="level3' . $lv3id . '" type="text" class="text-center" disabled value="' . $value . '" />
                                                                                          </td>
                                                                                      </tr>';
                                                                                  }
                                                                              }
                                                                          }
                                                                      }
                                                                  }
                                                              }
                                                          } else {
                                                              if ($lv1id != 1) {
                                                                  echo '
                                                                  <tr style="background-color:#607D8B; color:#FFF">
                                                                      <td>' . $nm . '</td>
                                                                      <td>' . $level1 . ' ' . $level1label . '</td>
                                                                      <td align="center">' . number_format($lvonesum, 0) . '</td>
                                                                      <input type="hidden" name="lvid[]" value="' . $lv1id . '">
                                                                  </tr>';
                                                                  while ($rows_level2 = $query_location_level2->fetch()) {
                                                                      $sr++;
                                                                      $lv2id = $rows_level2["id"];
                                                                      $level2 = $rows_level2["state"];

                                                                      $query_lvtwosum = $db->prepare("SELECT sum(value) AS sumvalue FROM tbl_indicator_output_baseline_values b inner join tbl_state s on s.id=b.level3 WHERE indid='$indid' and parent='$lv2id'");
                                                                      $query_lvtwosum->execute();
                                                                      $row_lvtwosum = $query_lvtwosum->fetch();
                                                                      $lvtwosum = $row_lvtwosum["sumvalue"];
                                                                      echo '<tr style="background-color:#9E9E9E; color:#FFF">
                                                                          <td>' . $nm . '.' . $sr . '</td>
                                                                          <td>' . $level2 . ' ' . $level2label . '</td>
                                                                          <td align="center"><input type="hidden" name="lvid' . $nm . '[]" value="' . $lv2id . '">' . number_format($lvtwosum, 0) . '</td>
                                                                      </tr>';

                                                                      $query_location_level3 =  $db->prepare("SELECT * FROM tbl_state WHERE parent = '$lv2id' and state NOT LIKE 'All%' ORDER BY state ASC");
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
                                                                                              if ($baseline == 0) {
                                                                                                  $base_val = $row_rstotal['value'];
                                                                                                  if ($row_rstotals > 0) {
                                                                                                      echo '
                                                                                                      <tr style="background-color:#f2fcf5">
                                                                                                          <td>' . $nm . '.' . $sr . '.' . $nmb . '.' . $str . '</td>
                                                                                                          <td>' . $level4 . '</td>
                                                                                                          <td>
                                                                                                              <input id="level3' . $lv4id . '" type="hidden" value="' . $level4 . '" />
                                                                                                              <button type="button" class="btn bg-light-green btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_dissedit_inputs(' . $lv3id . ',' . $lv4id . ',' . $indid . ')">
                                                                                                                  Edit Baseline Value (' . $base_val . ')
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
                                                                                              } else {
                                                                                                  $value = $row_rstotal['value'];
                                                                                                  echo '
                                                                                                  <tr style="background-color:#f2fcf5">
                                                                                                      <td>' . $nm . '.' . $sr . '.' . $nmb . '.' . $str . '</td>
                                                                                                      <td>' . $level4 . ' </td>
                                                                                                      <td>
                                                                                                          <input id="level3' . $lv4id . '" type="text" class="text-center" disabled value="' . $value . '" />
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

                                                                                      if ($baseline == 0) {
                                                                                          if ($row_rstotals > 0) {
                                                                                              $base_val = $row_rstotal['value'];
                                                                                              echo '
                                                                                              <tr style="background-color:#f2fcf5">
                                                                                                  <td>' . $nm . '.' . $sr . '.' . $nmb . '</td>
                                                                                                  <td>' . $level3 . ' ' . $level3label . '</td>
                                                                                                  <td>
                                                                                                  <input id="level3' . $lv3id . '" type="hidden" value="' . $level3 . '" />
                                                                                                      <button type="button" class="btn bg-light-green btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_dissedit_inputs(' . $lv3id . ',' . $location . ',' . $indid . ')">
                                                                                                          Edit Baseline Value (' . $base_val . ')
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
                                                                                      } else {
                                                                                          $value = $row_rstotal['value'];
                                                                                          echo '
                                                                                          <tr style="background-color:#f2fcf5">
                                                                                              <td>' . $nm . '.' . $sr . '.' . $nmb . '</td>
                                                                                              <td>' . $level3 . ' ' . $level3label . '</td>
                                                                                              <td>
                                                                                                  <input id="level3' . $lv3id . '" type="text" class="text-center" disabled value="' . $value . '" />
                                                                                              </td>
                                                                                          </tr>';
                                                                                      }
                                                                                  }
                                                                              } else {
                                                                                  $query_rstotal = $db->prepare("SELECT * FROM tbl_indicator_output_baseline_values WHERE indid='$indid' and level3='$lv3id' ");
                                                                                  $query_rstotal->execute();
                                                                                  $row_rstotal = $query_rstotal->fetch();
                                                                                  $row_rstotals = $query_rstotal->rowCount();

                                                                                  if ($baseline == 0) {
                                                                                      if ($row_rstotals > 0) {
                                                                                          $base_val = $row_rstotal['value'];
                                                                                          echo '
                                                                                          <tr style="background-color:#f2fcf5">
                                                                                              <td>' . $nm . '.' . $sr . '.' . $nmb . '</td>
                                                                                              <td>' . $level3 . ' ' . $level3label . '</td>
                                                                                              <td>
                                                                                              <input id="level3' . $lv3id . '" type="hidden" value="' . $level3 . '" />
                                                                                              <button type="button" class="btn bg-light-green btn-block btn-xs waves-effect" data-toggle="modal" data-target="#myModal" onclick="get_add_inddissedit_inputs(' . $lv3id . ')">
                                                                                                  Edit Baseline Value (' . $base_val . ')
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
                                                                                  } else {

                                                                                      //-------------Take note here--------------------------------
                                                                                      if (!empty($row_rstotal)) {
                                                                                          $value = $row_rstotal['value'];
                                                                                      } else {
                                                                                          $value = 0;
                                                                                      }
                                                                                      //-----------------------------------------------------------
                                                                                      echo '
                                                                                      <tr style="background-color:#f2fcf5">
                                                                                          <td>' . $nm . '.' . $sr . '.' . $nmb . '</td>
                                                                                          <td>' . $level3 . ' ' . $level3label . '</td>
                                                                                          <td>
                                                                                              <input id="level3' . $lv3id . '" type="text" class="text-center" disabled value="' . $value . '" />
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
                                                  }
                                                  ?>
                                              </tbody>
                                          </table>
                                      </div>
                                  </div>
                                  <?php if ($baseline == 0) { ?>
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
        <div class="modal fade" id="addlevel3dissModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h3 class="modal-title" align="center">
                            <font color="#FFF">Location Disaggregation Form</font>
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
                            <font color="#FFF"><span id="locationName"></span> Base Values</font>
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
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>
<script src="assets/custom js/indicator-existing-baseline-data.js"></script>
