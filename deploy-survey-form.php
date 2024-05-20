<?php
try {
    require('includes/head.php');
    if ($permission) {
        if (isset($_GET['formid']) && !empty($_GET['formid'])) {
            $encoded_formid = $_GET['formid'];
            $decode_formid = base64_decode($encoded_formid);
            $formid_array = explode("surveyform", $decode_formid);
            $formid = $formid_array[1];

            $query_rs_form = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE id=:formid");
            $query_rs_form->execute(array(":formid" => $formid));
            $row_rs_form = $query_rs_form->fetch();

            $projid = $row_rs_form['projid'];
            $indid = $row_rs_form['indid'];
            $form_name = $row_rs_form['form_name'];
            $resultstype = $row_rs_form['resultstype'];
			$resultstypeid = $row_rs_form['resultstypeid'];
            $enumtype = $row_rs_form['enumerator_type'];
            $startdate = date("d M Y", strtotime($row_rs_form['startdate']));
            $enddate = date("d M Y", strtotime($row_rs_form['enddate']));

            $enumeratortype = $enumtype == 1 ? "In-house" : "Out-Sourced";

            if ($resultstype == 2) {
                $query_results = $db->prepare("SELECT * FROM tbl_project_expected_outcome_details WHERE id=:resultstypeid");
                $query_results->execute(array(":resultstypeid" => $resultstypeid));
                $row_results = $query_results->fetch();
                $formtype = "Outcome ";
                $resultstobemeasured = $row_results['outcome'];
            } else {
                $query_results = $db->prepare("SELECT * FROM tbl_project_expected_impact_details WHERE id=:resultstypeid");
                $query_results->execute(array(":resultstypeid" => $resultstypeid));
                $row_results = $query_results->fetch();
                $formtype = "Impact ";
                $resultstobemeasured = $row_results['impact'];
            }

            $surveytype = $row_rs_form['form_type'] == 15 ? "Baseline":"Endline";
            $formname = "Project " . $formtype . $surveytype;

            $query_rsIndicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid ='$indid'");
            $query_rsIndicator->execute();
            $row_rsIndicator = $query_rsIndicator->fetch();
            $indname = $row_rsIndicator['indicator_name'];
            $disaggregated = $row_rsIndicator['indicator_disaggregation'];
            $indicator_calculation_method  = $row_rsIndicator['indicator_calculation_method'];
            $indunit  = $row_rsIndicator['indicator_unit'];

            // get unit
            $query_Indicator = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id ='$indunit'");
            $query_Indicator->execute();
            $row = $query_Indicator->fetch();
            $unit = $row['unit'];

            $location = false;

            if ($disaggregated == 1) {
                $disaggregation_arr = [];
                $query_rsInd_disaggregation_type = $db->prepare("SELECT * FROM tbl_indicator_measurement_variables_disaggregation_type d
            INNER JOIN tbl_indicator_disaggregation_types g on g.id=d.disaggregation_type
            WHERE d.indicatorid= '$indid' AND g.type=0");
                $query_rsInd_disaggregation_type->execute();
                $row_rsInd_disaggregation_type = $query_rsInd_disaggregation_type->fetch();
                $indInd_disaggregation_typecount = $query_rsInd_disaggregation_type->rowCount();

                if ($indInd_disaggregation_typecount > 0) {
                    do {
                        $disaggregation_arr[] = $row_rsInd_disaggregation_type['disaggregation_type'];
                    } while ($row_rsInd_disaggregation_type = $query_rsInd_disaggregation_type->fetch());
                }
                if (in_array(1, $disaggregation_arr)) {
                    $location = true;
                }
            }

            $query_projects = $db->prepare("SELECT * FROM tbl_projects WHERE  projid=:projid ");
            $query_projects->execute(array(":projid" => $projid));
            $count_projects = $query_projects->rowCount();
            $rows_projects = $query_projects->fetch();
            $level2 = explode(",", $rows_projects['projlga']);
            $projname  = $rows_projects['projname'];
        }
		?>
        <style>
            #links a {
                color: #FFFFFF;
                text-decoration: none;
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
                                <ul class="list-group">
                                    <li class="list-group-item list-group-item list-group-item-action active">Form Name: <?php echo $formname . " " . $form_name ?> </li>
									<li class="list-group-item">Project Name: <?= $projname ?> </li>
									<li class="list-group-item"><strong>Indicator: </strong> <?= $indname ?> </li>
                                    <li class="list-group-item "><strong>Enumerator Type: </strong> <?= $enumeratortype ?> </li>
                                </ul>
                            </div>
                            <div class="body">
                                <div class="row clearfix">
                                    <div class="col-md-12" id="disaggregation_div">
                                        <form id="addnewdata" method="POST" name="addnewdata" action="">
                                            <?= csrf_token_html(); ?>
                                            <fieldset class="scheduler-border">
                                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-plus-square" aria-hidden="true"></i> Add Location Disaggregations</legend>
                                                <div class="col-md-12" id="outcome_direct_disagregation">
                                                    <label class="control-label">Disaggregations *:</label>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped table-hover" id="direct_outcome_table" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th width="10%">#</th>
                                                                    <th width="30%"><?= $level2label ?></th>
                                                                    <th width="30%"><?= $level2label ?> Disaggregations</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="">
                                                                <?php
                                                                $counter = 0;
                                                                for ($i = 0; $i < count($level2); $i++) {
                                                                    $level2id = $level2[$i];
                                                                    $counter++;
                                                                    $query_rsComm =  $db->prepare("SELECT id, state FROM tbl_state WHERE id=:level2");
                                                                    $query_rsComm->execute(array(":level2" => $level2id));
                                                                    $row_rsComm = $query_rsComm->fetch();
                                                                    $totalRows_rsComm = $query_rsComm->rowCount();
                                                                    $state = $row_rsComm['state'];
                                                                ?>
                                                                    <tr>
                                                                        <td width="10%">
                                                                            <?= $counter ?>
                                                                        </td>
                                                                        <td width="30%">
                                                                            <?= $state ?>
                                                                            <input name="level2[]" type="hidden" id="level2" value="<?= $level2id; ?>" />
                                                                        </td>
                                                                        <td width="30%">
                                                                            <div class="form-input">
                                                                                <input type="text" name="disaggregations[]" id="disaggregations<?= $counter ?>" placeholder="Enter" class="form-control" required>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="row clearfix">
                                                    <div class="col-lg-12 col-md-12 col-sm-2 col-xs-2" align="center">
                                                        <input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
                                                        <input name="indid" type="hidden" id="indid" value="<?php echo $indid; ?>" />
                                                        <input name="projid" type="hidden" id="projid" value="<?php echo $projid; ?>" />
                                                        <input name="respondents_type" type="hidden" id="respondents_type" value="<?php echo $respondents_type; ?>" />
                                                        <input name="form_id" type="hidden" id="form_id" value="<?= $formid ?>" />
                                                        <input name="resultstype" type="hidden" id="resultstype" value="<?= $resultstype ?>" />
                                                        <input type="hidden" name="insert" value="insert" />
                                                        <div class="btn-group">
                                                            <button name="button" type="submit" id="insert" class="btn bg-light-blue waves-effect waves-light" />
                                                            Save
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>

                                    <div class="col-md-12" id="enumerator_div">
                                        <form id="addenumeratorbasefrm" method="POST" name="addenumeratorbasefrm" action="" enctype="multipart/form-data" autocomplete="off">
                                            <?= csrf_token_html(); ?>
                                            <fieldset class="scheduler-border">
                                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-plus-square" aria-hidden="true"></i> Assign Enumerator</legend>
                                                <div class="col-md-12" id="outcome_direct_disagregation">
                                                    <label class="control-label">Locations *:</label>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped table-hover" id="direct_outcome_table" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th width="4%">#</th>
                                                                    <th width="32%"><?= $level2label ?></th>
                                                                    <?php
                                                                    if ($enumtype == 1) { ?>
                                                                        <th width="32%"><?= $ministrylabel ?></th>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                    <th width="32%">Responsible</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="direct_outcome_table_body">
                                                                <?php
                                                                if (!$location) {
                                                                    $counter = 0;
                                                                    for ($i = 0; $i < count($level2); $i++) {
                                                                        $level2id = $level2[$i];
                                                                        $counter++;
                                                                        $query_rsComm =  $db->prepare("SELECT id, state FROM tbl_state WHERE id=:level2");
                                                                        $query_rsComm->execute(array(":level2" => $level2id));
                                                                        $row_rsComm = $query_rsComm->fetch();
                                                                        $totalRows_rsComm = $query_rsComm->rowCount();
                                                                        $state = $row_rsComm['state'];
                                                                ?>
                                                                        <tr>
                                                                            <td width="4%">
                                                                                <?= $counter ?>
                                                                            </td>
                                                                            <td width="32%">
                                                                                <?= $state ?>
                                                                                <input name="level2[]" type="hidden" id="level2" value="<?= $level2id; ?>" />
                                                                                <input name="indirectben[]" type="hidden" id="indirectben" value="" />
                                                                            </td>
                                                                            <?php
                                                                            if ($enumtype == 1) {
                                                                            ?>
                                                                                <td width="32%">
                                                                                    <select name="ministry" id="ministry<?= $counter ?>" onchange="userMinistry(<?= $counter ?>)" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="false" required>
                                                                                        <option value="">.... Select <?= $ministrylabel ?> ....</option>
                                                                                        <?php
                                                                                        $query_ministry = $db->prepare("SELECT * FROM tbl_sectors WHERE parent = 0 and deleted = '0'");
                                                                                        $query_ministry->execute();
                                                                                        while ($row = $query_ministry->fetch()) {
                                                                                            $stid = $row['stid'];
                                                                                            $sector = $row['sector'];

                                                                                            echo '<option value="' . $stid . '">' . $sector . '</option>';
                                                                                        }
                                                                                        ?>
                                                                                    </select>
                                                                                </td>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                            <td width="30%">
                                                                                <div class="form-line">
                                                                                    <?php
                                                                                    if ($enumtype == 1) {
                                                                                    ?>
                                                                                        <select name="enumerators[]" class="form-control show-tick" id="inhouse<?= $counter ?>" style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="true" required>
                                                                                            <option value="">.... Select <?= $ministrylabel ?> first....</option>
                                                                                        </select>
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <input type="email" name="enumerators[]" id="mail" placeholder="enter enumerator email addresses" class="form-control" style="border:#CCC thin solid; border-radius: 5px" required>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="row clearfix">
                                                    <div class="col-lg-12 col-md-12 col-sm-2 col-xs-2" align="center">
                                                        <input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
                                                        <input name="indid" type="hidden" id="indid" value="<?php echo $indid; ?>" />
                                                        <input name="projid" type="hidden" id="projid" value="<?php echo $projid; ?>" />
                                                        <input name="form_id" type="hidden" id="form_id" value="<?= $formid ?>" />
                                                        <input name="resultstype" type="hidden" id="resultstype" value="<?= $resultstype ?>" />
                                                        <a href="#" onclick="history.go(-1)" type="button" class="btn bg-orange"> Cancel </a>
                                                        <input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="Submit" />
                                                        <input type="hidden" name="MM_insert" value="addenumeratorbasefrme" />
                                                    </div>
                                                </div>
                                            </fieldset>
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
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>

<script src="ckeditor/ckeditor.js"></script>
<script src="assets/custom js/baseline.js"></script>

<script>
    $(document).ready(function() {
        //delete_location_disaggregations(<?= $formid ?>);
        disable_refresh();
        <?php
        if ($location) {
        ?>
            $("#enumerator_div").hide();
            $("#disaggregation_div").show();
        <?php
        } else {
        ?>
            $("#enumerator_div").show();
            $("#disaggregation_div").hide();
        <?php
        }
        ?>

        $("#addnewdata").submit(function(e) {
            e.preventDefault();
            var formdata = $(this).serialize();
            $.ajax({
                type: "post",
                url: "assets/processor/add-baseline-processor",
                data: formdata,
                dataType: "json",
                success: function(response) {
                    if (response.msg) {
                        $("#disaggregation_div").hide();
                        $("#enumerator_div").show();
                        $("#direct_outcome_table_body").html(response.html);
                    } else {
                        alert("Error encountered");
                    }
                },
            });
        });

        $("#addenumeratorbasefrm").submit(function(e) {
            e.preventDefault();
            var formdata = $(this).serialize();
            var resultstype = $("#resultstype").val();
            var redirurl = "view-project-survey";
            if (resultstype == 1) {
                redirurl = "view-project-impact-evaluation";
            }
            $("#submit").prop('disabled', true);

            $.ajax({
                type: "post",
                url: "assets/processor/add-baseline-processor",
                data: formdata,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        success_alert("Deployed Successfully");
                    } else {
                        sweet_alert("Problem", response.messages, "error", redirurl);
                    }
                    window.location.replace(redirurl);
                },
            });
        });
    });

    // sweet alert notifications
    function success_alert(msg) {
        return swal({
            title: "Success",
            text: msg,
            type: "Success",
            icon: 'success',
            dangerMode: true,
            timer: 15000,
            showConfirmButton: false
        });
    }

    function sweet_alert(title, text, msg, redirurl) {
        swal({
            title: title,
            text: text,
            type: msg
        });
        setTimeout(function() {
            window.location.href = redirurl;
        }, 3000);
    }

    function delete_location_disaggregations(formid) {
        if (formid) {
            $.ajax({
                type: "post",
                url: "assets/processor/add-baseline-processor",
                data: {
                    empty_data: "empty_data",
                    formid: formid,
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        alert("Success", response.messages);
                    } else {
                        alert("Success", response.messages);
                    }
                },
            });
        }
    }


    // function disable refreshing functionality
    function disable_refresh() {
        return (window.onbeforeunload = function(e) {
            return "you can not refresh the page";
        });
    }

    function userMinistry(min) {
        var ministry = $("#ministry" + min).val();
        if (ministry) {
            $.ajax({
                type: "post",
                url: 'assets/processor/indicator-details',
                data: {
                    get_enumerator: ministry,
                },
                dataType: "html",
                success: function(response) {
                    $("#inhouse" + min).html(response);
                    $(".selectpicker").selectpicker("refresh");
                }
            });
        }
    }
</script>