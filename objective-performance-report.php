<?php
require('includes/head.php');
if ($permission) {
    try {

        function get_financial_year($financial_year)
        {
            global $db;
            $query_rsFscYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year where yr =:year ");
            $query_rsFscYear->execute(array(":year" => $financial_year));
            $row_rsFscYear = $query_rsFscYear->fetch();
            $totalRows_years = $query_rsFscYear->rowCount();
            return ($totalRows_years > 0) ? $row_rsFscYear['id'] : 0;
        }

        function get_sector($sector_id)
        {
            global $db;
            $query_Sectors = $db->prepare("SELECT * FROM tbl_sectors WHERE stid=:sector_id");
            $query_Sectors->execute(array(":sector_id" => $sector_id));
            $Rows_Sectors = $query_Sectors->fetch();
            $totalRows_Sectors = $query_Sectors->rowCount();
            return ($totalRows_Sectors > 0) ? $Rows_Sectors['sector'] : "";
        }

        $yr = date("Y");
        $month = date("m");
        $sector_id = $user_department != '' ? $user_department :  15;
        $start_year = $end_year = '';
        if ($month >= 7 && $month <= 12) {
            $start_year = $yr;
            $end_year = $yr + 1;
        } elseif ($month >= 1 && $month <= 6) {
            $start_year = $yr - 1;
            $end_year = $yr;
        }



        $financial_start_date = $start_year . "-07-01";
        $financial_end_date = $end_year . "-06-30";

        $year = get_financial_year($start_year);
        $financial_year_id = $year;


        $financial_year = $start_year . "/" . $end_year;
        $start = $start_year - 1;
        $b_financial_year = $start . "/" . $start_year;

        $base_date = $start_year . "-06-30";
        $start_date = $end_date = "";

        $quarter = 0;
        if ($month >= 7 && $month <= 9) {
            $quarter = 1;
            $start_date = $start_year . "-07-01";
            $end_date = $start_year . "-09-30";
        } else if ($month >= 10 && $month <= 12) {
            $quarter = 2;
            $start_date = $start_year . "-10-01";
            $end_date = $start_year . "-12-31";
        } else if ($month >= 1 && $month <= 3) {
            $quarter = 3;
            $start_date = $end_year . "-01-01";
            $end_date = $end_year . "-03-31";
        } else if ($month >= 4 && $month <= 6) {
            $quarter = 4;
            $start_date = $end_year . "-04-01";
            $end_date = $end_year . "-06-30";
        }

        $financial_year_date = "Q$quarter (" . $start_date . " to " . $end_date . ")";
        $responsible = true;

        function get_results($msg)
        {
            return
                "<script type=\"text/javascript\">
                    swal({
                            title: \"Success!\",
                            text: \" $msg\",
                            type: 'Success',
                            timer: 2000,
                            'icon':'success',
                        showConfirmButton: false });
                        setTimeout(function(){
                            window.location.href='objective-performance-report';
                        }, 2000);
                </script>";
        }


        if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addQuarterReport")) {
            $year = $_POST['year'];
            $quarter = $_POST['quarter'];
            $appendice = $_POST['appendice'];
            $conclusion = $_POST['conclusion'];
            $challenges = $_POST['challenges'];
            $sector = $_POST['sector'];
            $sector_id = $_POST['stid'];
            $proceed = $_POST['proceed'];
            $proceed = $_POST['proceed'];
            $id = $_POST['q_report_id'];
            // $user_name = $_POST['username'];
            $edit = $_POST['edit'];
            $status =  (isset($_POST["quarter_button_two"]) && $_POST["quarter_button_two"] == "Approve") ? 2 : 1;
            if ($edit == "edit") {
                $insertSQL = $db->prepare("UPDATE tbl_qapr_report_conclusion SET stid=:stid,section_comments=:section_comments,challenges=:challenges,conclusion=:conclusion,appendices=:appendices,status=:status,updated_by=:updated_by, updated_at=:updated_at  WHERE id=:id");
                $result  = $insertSQL->execute(array(":stid" => $sector_id, ":section_comments" => $sector, ":challenges" => $challenges, ":conclusion" => $conclusion, ":appendices" => $appendice, ":status" => $status, ":updated_by" => $user_name, ":updated_at" => $today, ":id" => $id));
            } else {
                $insertSQL = $db->prepare("INSERT INTO tbl_qapr_report_conclusion (stid,year,quarter, section_comments,challenges,conclusion,appendices,status,created_by,created_at) VALUES (:stid,:year,:quarter, :section_comments,:challenges,:conclusion,:appendices,:status,:created_by,:created_at)");
                $result  = $insertSQL->execute(array(":stid" => $sector_id, ":year" => $year, ":quarter" => $quarter, ":section_comments" => $sector, ":challenges" => $challenges, ":conclusion" => $conclusion, ":appendices" => $appendice, ":status" => $status, ":created_by" => $user_name, ":created_at" => $today));
            }


            $msg = 'Remarks entered Successfully Added';
            $results = get_results($msg);
        }

        if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addReportyear")) {
            $year = $_POST['year'];
            $appendice = $_POST['year_appendice'];
            $conclusion = $_POST['year_conclusion'];
            $challenges = $_POST['year_challenges'];
            $sector = $_POST['year_sector'];
            $sector_id = $_POST['year_sector_id'];
            $user_name = $_POST['username'];
            $today = date("Y-m-d");
            $edit = $_POST['year_edit'];
            $id = $_POST['y_report_id'];
            $status =  (isset($_POST["year_button_two"]) && $_POST["year_button_two"] == "Approve") ? 2 : 1;
            if ($edit == "edit") {
                $insertSQL = $db->prepare("UPDATE tbl_capr_report_conclusion SET stid=:stid,section_comments=:section_comments,challenges=:challenges,conclusion=:conclusion,appendices=:appendices,status=:status, updated_by=:updated_by, updated_at=:updated_at WHERE id=:id");
                $result  = $insertSQL->execute(array(":stid" => $sector_id, ":section_comments" => $sector, ":challenges" => $challenges, ":conclusion" => $conclusion, ":appendices" => $appendice, ":status" => $status, ":updated_by" => $user_name, ":updated_at" => $today, ":id" => $id));
            } else {
                $insertSQL = $db->prepare("INSERT INTO tbl_capr_report_conclusion (stid,year,quarter, section_comments,challenges,conclusion,appendices,status,created_by, created_at) VALUES (:stid,:year,:quarter, :section_comments,:challenges,:conclusion,:appendices,:status,:created_by, :created_at)");
                $result  = $insertSQL->execute(array(":stid" => $sector_id, ":year" => $year, ":quarter" => $quarter, ":section_comments" => $sector, ":challenges" => $challenges, ":conclusion" => $conclusion, ":appendices" => $appendice, ":status" => $status, ":created_by" => $user_name, ":created_at" => $today));
            }

            $msg = 'Remarks entered Successfully Added';
            $results = get_results($msg);
        }

        $approve_responsible = 1;
    } catch (PDOException $ex) {
        $result = flashMessage("An error occurred: " . $ex->getMessage());
        echo $result;
    }
?>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <?= $icon ?> <?= $pageTitle ?>
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
                                        <li class="list-group-item list-group-item list-group-item-action active"> Financial Year: <?= $b_financial_year ?> </li>
                                        <li class="list-group-item">
                                            <strong>Financial Year Start Date: </strong> <?= $financial_start_date ?> : <strong> Financial Year End Date: </strong> <?= $financial_end_date ?>
                                        </li>
                                        <li class="list-group-item"><strong>Quarter: </strong> <?= $quarter ?> </li>
                                        <li class="list-group-item"><strong> Quarter Start Date: </strong> <?= $start_date ?> <strong>Quarter End Date: </strong> <?= $end_date ?></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-header">
                            <ul class="nav nav-tabs" style="font-size:14px">
                                <li class="active">
                                    <a data-toggle="tab" href="#home">
                                        <i class="fa fa-caret-square-o-down bg-orange" aria-hidden="true"></i> Quarter &nbsp;<span class="badge bg-orange">|</span>
                                    </a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#menu1">
                                        <i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> Yearly &nbsp;<span class="badge bg-blue">|</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="tab-content">
                                <div id="home" class="tab-pane fade in active">
                                    <?php
                                    $query_rsConclusion = $db->prepare("SELECT * FROM `tbl_qapr_report_conclusion` WHERE year=:year AND quarter= :quarter");
                                    $query_rsConclusion->execute(array(":year" => $year, ":quarter" => $quarter));
                                    $Rows_rsConclusion = $query_rsConclusion->fetch();
                                    $totalRows_rsConclusion = $query_rsConclusion->rowCount();

                                    $query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1 and indicator_sector=:sector ORDER BY `indid` ASC");
                                    $query_indicators->execute(array(":sector" => $sector_id));
                                    $sector = get_sector($sector_id);
                                    $totalRows_indicators = $query_indicators->rowCount();
                                    ?>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="body" style="margin-top:5px">
                                                <form action="" method="post" enctype="multipart/form-data">
                                                    <?= csrf_token_html(); ?>
                                                    <div class="row clearfix">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <h4>Chapter 1: Performance</h4>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <h5> 1.1 Mandate and overall goals </h5>
                                                                <p align="left">
                                                                    <textarea name="sector" cols="45" rows="5" class="txtboxes" id="sector" required="required" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder="Briefly describe the goals and objectives of the project, the approaches and execution methods, resource estimates, people and organizations involved, and other relevant information that explains the need for project as well as the amount of work planned for implementation."><?= ($totalRows_rsConclusion > 0) ? $Rows_rsConclusion['section_comments'] : ""; ?></textarea>
                                                                    <script>
                                                                        CKEDITOR.replace('sector', {
                                                                            height: 200,
                                                                            on: {
                                                                                instanceReady: function(ev) {
                                                                                    // Output paragraphs as <p>Text</p>.
                                                                                    this.dataProcessor.writer.setRules('p', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                    this.dataProcessor.writer.setRules('ol', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                    this.dataProcessor.writer.setRules('ul', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                    this.dataProcessor.writer.setRules('li', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                }
                                                                            }
                                                                        });
                                                                    </script>
                                                                </p>
                                                            </div>
                                                            <h5>1.2 Indicators Performance</h5>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                                    <thead>
                                                                        <tr class="bg-light-blue">
                                                                            <th colspan="" rowspan="2" style="width:3%">#</th>
                                                                            <th colspan="" rowspan="2">Output&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                                            <th colspan="" rowspan="2">Indicator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                                            <th colspan="" rowspan="2">Baseline&nbsp;&nbsp;(<?= $b_financial_year ?>)&nbsp;&nbsp;</th>
                                                                            <th colspan="" rowspan="2">Target at end of the FY&nbsp;&nbsp;(<?= $financial_year ?>)&nbsp;&nbsp;</th>
                                                                            <th colspan="3" rowspan=""><?= $financial_year_date ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                                            <th colspan="" rowspan="2">Comments&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                                        </tr>
                                                                        <tr class="bg-light-blue">
                                                                            <th>Target</th>
                                                                            <th>Achieved</th>
                                                                            <th>Rate (%)</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $proceed_button = [];
                                                                        if ($totalRows_indicators > 0) {
                                                                            $counter = 0;
                                                                            $quarter_target = 0;
                                                                            while ($row_indicators = $query_indicators->fetch()) {
                                                                                $counter++;
                                                                                $initial_basevalue = 0;
                                                                                $actual_basevalue = 0;
                                                                                $annualtarget = 0;
                                                                                $basevalue = 0;
                                                                                $indid = $row_indicators['indid'];
                                                                                $indicator = $row_indicators['indicator_name'];
                                                                                $unit = $row_indicators['indicator_unit'];

                                                                                $query_indunit =  $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id='$unit'");
                                                                                $query_indunit->execute();
                                                                                $row_indunit = $query_indunit->fetch();
                                                                                $unit = ($row_indunit) ? $row_indunit["unit"] : "";

                                                                                $query_indbasevalue_year = $db->prepare("SELECT * FROM tbl_indicator_baseline_years WHERE indid=:indid AND year <= :finyear");
                                                                                $query_indbasevalue_year->execute(array(":indid" => $indid, ":finyear" => $start));
                                                                                $totalRows_indbasevalue_year = $query_indbasevalue_year->rowCount();

                                                                                if ($totalRows_indbasevalue_year >= 0) {
                                                                                    $query_initial_indbasevalue = $db->prepare("SELECT SUM(value) AS basevalue FROM tbl_indicator_output_baseline_values WHERE indid=:indid ");
                                                                                    $query_initial_indbasevalue->execute(array(":indid" => $indid));
                                                                                    $rows_initial_indbasevalue = $query_initial_indbasevalue->fetch();
                                                                                    $initial_basevalue = !is_null($rows_initial_indbasevalue["basevalue"]) ? $rows_initial_indbasevalue["basevalue"] : 0;

                                                                                    $query_actual_ind_value = $db->prepare("SELECT SUM(achieved) AS basevalue FROM tbl_monitoringoutput m inner join tbl_project_details d on d.id=m.output_id WHERE m.record_type=1 AND d.indicator=:indid AND m.date_created <= '" . $base_date . "'");
                                                                                    $query_actual_ind_value->execute(array(":indid" => $indid));
                                                                                    $rows_actual_ind_value = $query_actual_ind_value->fetch();
                                                                                    $actual_basevalue = !is_null($rows_actual_ind_value["basevalue"]) ? $rows_actual_ind_value["basevalue"] : 0;

                                                                                    $basevalue = $initial_basevalue + $actual_basevalue;

                                                                                    $query_annual_target = $db->prepare("SELECT SUM(target) as target FROM `tbl_progdetails` WHERE indicator=:indid AND year=:finyear");
                                                                                    $query_annual_target->execute(array(":indid" => $indid, ":finyear" => $start_year));
                                                                                    $rows_annual_target = $query_annual_target->fetch();
                                                                                    $annualtarget = !is_null($rows_annual_target["target"]) ? $rows_annual_target["target"] : 0;

                                                                                    $query_quarterly_target = $db->prepare("SELECT * FROM tbl_programs_quarterly_targets WHERE indid=:indid AND year=:finyear");
                                                                                    $query_quarterly_target->execute(array(":indid" => $indid, ":finyear" => $start_year));
                                                                                    $rows_quarterly_target = $query_quarterly_target->fetch();
                                                                                    $totalRows_quarterly_target = $query_quarterly_target->rowCount();

                                                                                    $query_quarter_one_actual = $db->prepare("SELECT SUM(achieved) AS achieved FROM tbl_monitoringoutput m inner join tbl_project_details d on d.id=m.output_id WHERE indicator=:indid AND (date_created >= '" . $start_date . "' AND date_created <= '" . $end_date . "')");
                                                                                    $query_quarter_one_actual->execute(array(":indid" => $indid));
                                                                                    $rows_quarter_one_actual = $query_quarter_one_actual->fetch();
                                                                                    $totalRows_quarter_one_actual = $query_quarter_one_actual->rowCount();
                                                                                    $quarter_achived =  !is_null($rows_quarter_one_actual["achieved"]) ? $rows_quarter_one_actual["achieved"] : 0;


                                                                                    $quarter_rate = "";
                                                                                    if ($totalRows_quarterly_target > 0) {
                                                                                        $quarter_target =  $rows_quarterly_target["Q$quarter"];
                                                                                        $quarter_1_rate = $quarter_achived > 0 && $quarter_target > 0 ? number_format((($quarter_achived / $quarter_target) * 100), 2) : 0;
                                                                                        $quarter_rate = $quarter_1_rate . "%";
                                                                                    } else {
                                                                                        $quarter_rate = number_format(0, 2) . "%";
                                                                                    }
                                                                                }

                                                                                $query_indRemarks =  $db->prepare("SELECT * FROM tbl_qapr_report_remarks WHERE indid=:indid AND year=:year AND quarter=:quarter");
                                                                                $query_indRemarks->execute(array(":indid" => $indid, ":year" => $year, ":quarter" => $quarter));
                                                                                $row_indRemarks = $query_indRemarks->fetch();
                                                                                $count_row_indRemarks = $query_indRemarks->rowCount();
                                                                                $proceed_button[] = $count_row_indRemarks > 0 ? true : false;
                                                                                $edit = ($count_row_indRemarks > 0) ?  "edit" : "new";
                                                                                $remark_id = $count_row_indRemarks > 0 ? $row_indRemarks['id'] : '';

                                                                        ?>
                                                                                <tr>
                                                                                    <td><?= $counter ?></td>
                                                                                    <td colspan=""><?= $indicator ?></td>
                                                                                    <td colspan=""><?= $unit . " of " .  $indicator ?></td>
                                                                                    <td colspan=""><?= number_format($basevalue) ?></td>
                                                                                    <td colspan=""><?= number_format($annualtarget) ?></td>
                                                                                    <td><?= number_format($quarter_target) ?></td>
                                                                                    <td><?= number_format($quarter_achived) ?></td>
                                                                                    <td><?= $quarter_rate ?></td>
                                                                                    <td>
                                                                                        <button type="button" data-toggle="modal" data-target="#remarksItemModal" id="remarksItemModalBtn" class="btn btn-success btn-sm" onclick="remarks(<?= $indid ?>, 1,'<?= $edit ?>', '<?= $remark_id ?>')">
                                                                                            <i class="glyphicon glyphicon-file"></i>
                                                                                            <strong> <?= ($count_row_indRemarks > 0)  ? "Edit" : "Add" ?> Remarks</strong>
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <h4>Chapter 2: Challenges and Recommendations </h4>
                                                                <p align="left">
                                                                    <textarea name="challenges" cols="45" rows="5" class="txtboxes" id="challenges" required="required" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder="."><?= ($totalRows_rsConclusion > 0) ? $Rows_rsConclusion['challenges'] : "";  ?></textarea>
                                                                    <script>
                                                                        CKEDITOR.replace('challenges', {
                                                                            height: 200,
                                                                            on: {
                                                                                instanceReady: function(ev) {
                                                                                    // Output paragraphs as <p>Text</p>.
                                                                                    this.dataProcessor.writer.setRules('p', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                    this.dataProcessor.writer.setRules('ol', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                    this.dataProcessor.writer.setRules('ul', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                    this.dataProcessor.writer.setRules('li', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                }
                                                                            }
                                                                        });
                                                                    </script>
                                                                </p>
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <h4>Chapter 3: Lessons learnt and Conclusion</h4>
                                                                <p align="left">
                                                                    <textarea name="conclusion" cols="45" rows="5" class="txtboxes" id="conclusion" required="required" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder=""><?= ($totalRows_rsConclusion > 0) ? $Rows_rsConclusion['conclusion'] : "";  ?></textarea>
                                                                    <script>
                                                                        CKEDITOR.replace('conclusion', {
                                                                            height: 200,
                                                                            on: {
                                                                                instanceReady: function(ev) {
                                                                                    // Output paragraphs as <p>Text</p>.
                                                                                    this.dataProcessor.writer.setRules('p', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                    this.dataProcessor.writer.setRules('ol', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                    this.dataProcessor.writer.setRules('ul', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                    this.dataProcessor.writer.setRules('li', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                }
                                                                            }
                                                                        });
                                                                    </script>
                                                                </p>
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <h4>Appendices for additional documents/materials</h4>
                                                                <p align="left">
                                                                    <textarea name="appendice" cols="45" rows="5" class="txtboxes" id="appendice" required="required" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder="">  <?= ($totalRows_rsConclusion > 0) ? $Rows_rsConclusion['appendices'] : "";  ?></textarea>
                                                                    <script>
                                                                        CKEDITOR.replace('appendice', {
                                                                            height: 200,
                                                                            on: {
                                                                                instanceReady: function(ev) {
                                                                                    // Output paragraphs as <p>Text</p>.
                                                                                    this.dataProcessor.writer.setRules('p', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                    this.dataProcessor.writer.setRules('ol', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                    this.dataProcessor.writer.setRules('ul', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                    this.dataProcessor.writer.setRules('li', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                }
                                                                            }
                                                                        });
                                                                    </script>
                                                                </p>
                                                            </div>
                                                            <div class="col-md-12" style="margin-top:15px" align="center">
                                                                <input type="hidden" name="MM_insert" value="addQuarterReport">
                                                                <input type="hidden" name="q_report_id" value="<?= ($totalRows_rsConclusion > 0) ? $Rows_rsConclusion['id'] : "";  ?>">
                                                                <input type="hidden" name="year" value="<?= $year ?>">
                                                                <input type="hidden" name="quarter" value="<?= $quarter ?>">
                                                                <input type="hidden" name="stid" value="<?= $sector_id ?>">
                                                                <input type="hidden" name="edit" value="<?= ($totalRows_rsConclusion > 0) ? "edit" : "new";  ?>">
                                                                <input type="hidden" name="proceed" value="1">
                                                                <input type="hidden" name="username" id="username" value="<?= $user_name ?>">
                                                                <?php
                                                                if (!in_array(false, $proceed_button)) {
                                                                    $status_id = ($totalRows_rsConclusion > 0) ? $Rows_rsConclusion['status'] : "";
                                                                    if ($approve_responsible && $status_id == 1) {
                                                                ?>
                                                                        <input type="submit" class="btn btn-success" name="quarter_button_two" id="button1" value="Approve">
                                                                    <?php
                                                                    } else {
                                                                    ?>
                                                                        <input type="submit" class="btn btn-success" name="quarter_button_two" id="button2" value="Proceed">
                                                                <?php

                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="menu1" class="tab-pane fade">
                                    <?php
                                    $query_rsConclusion = $db->prepare("SELECT * FROM `tbl_capr_report_conclusion` WHERE year=:year ");
                                    $query_rsConclusion->execute(array(":year" => $year));
                                    $Rows_rsConclusion = $query_rsConclusion->fetch();
                                    $totalRows_rsConclusion = $query_rsConclusion->rowCount();

                                    $query_indicators = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND baseline=1 and indicator_sector=:sector ORDER BY `indid` ASC");
                                    $query_indicators->execute(array(":sector" => $sector_id));
                                    $sector = get_sector($sector_id);
                                    $totalRows_indicators = $query_indicators->rowCount();
                                    ?>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="body" style="margin-top:5px">
                                                <form action="" method="post" enctype="multipart/form-data">
                                                    <?= csrf_token_html(); ?>
                                                    <div class="row clearfix">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <h4>Chapter 1: Performance</h4>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <h5> 1.1 Mandate and overall goals </h5>
                                                                <p align="left">
                                                                    <textarea name="year_sector" cols="45" rows="5" class="txtboxes" id="year_sector" required="required" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder="Briefly describe the goals and objectives of the project, the approaches and execution methods, resource estimates, people and organizations involved, and other relevant information that explains the need for project as well as the amount of work planned for implementation."><?= ($totalRows_rsConclusion > 0) ? $Rows_rsConclusion['section_comments'] : ""; ?></textarea>
                                                                    <script>
                                                                        CKEDITOR.replace('year_sector', {
                                                                            height: 200,
                                                                            on: {
                                                                                instanceReady: function(ev) {
                                                                                    // Output paragraphs as <p>Text</p>.
                                                                                    this.dataProcessor.writer.setRules('p', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                    this.dataProcessor.writer.setRules('ol', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                    this.dataProcessor.writer.setRules('ul', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                    this.dataProcessor.writer.setRules('li', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                }
                                                                            }
                                                                        });
                                                                    </script>
                                                                </p>
                                                            </div>
                                                            <h5>1.2 Indicators Performance</h5>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                                    <thead>
                                                                        <tr class="bg-light-blue">
                                                                            <th style="width:3%">#</th>
                                                                            <th>Output&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                                            <th>Indicator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                                            <th>Baseline&nbsp;&nbsp;(<?= $b_financial_year ?>)&nbsp;&nbsp;</th>
                                                                            <th>Target at end of the CIDP period&nbsp;&nbsp;(<?= $financial_year ?>)&nbsp;&nbsp;</th>
                                                                            <th>Target in review period &nbsp;&nbsp;(<?= $financial_year ?>)&nbsp;&nbsp;</th>
                                                                            <th>Achievement for&nbsp;&nbsp;(<?= $financial_year ?>)&nbsp;&nbsp;</th>
                                                                            <th>Rate of achievement for&nbsp;&nbsp;(<?= $financial_year ?>)&nbsp;&nbsp;</th>
                                                                            <th>Comments&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $year_proceed_button = [];
                                                                        if ($totalRows_indicators > 0) {
                                                                            $counter = 0;
                                                                            $quarter_target = 0;
                                                                            while ($row_indicators = $query_indicators->fetch()) {
                                                                                $counter++;
                                                                                $initial_basevalue = 0;
                                                                                $actual_basevalue = 0;
                                                                                $annualtarget = 0;
                                                                                $basevalue = 0;
                                                                                $indid = $row_indicators['indid'];
                                                                                $indicator = $row_indicators['indicator_name'];
                                                                                $unit = $row_indicators['indicator_unit'];

                                                                                $query_indunit =  $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id='$unit'");
                                                                                $query_indunit->execute();
                                                                                $row_indunit = $query_indunit->fetch();
                                                                                $unit = ($row_indunit) ? $row_indunit["unit"] : "";

                                                                                $query_indbasevalue_year = $db->prepare("SELECT * FROM tbl_indicator_baseline_years WHERE indid=:indid AND year <= :finyear");
                                                                                $query_indbasevalue_year->execute(array(":indid" => $indid, ":finyear" => $start));
                                                                                $totalRows_indbasevalue_year = $query_indbasevalue_year->rowCount();
                                                                                $year_rate = $year_target = $year_achived = 0;

                                                                                if ($totalRows_indbasevalue_year >= 0) {
                                                                                    $query_initial_indbasevalue = $db->prepare("SELECT SUM(value) AS basevalue FROM tbl_indicator_output_baseline_values WHERE indid=:indid ");
                                                                                    $query_initial_indbasevalue->execute(array(":indid" => $indid));
                                                                                    $rows_initial_indbasevalue = $query_initial_indbasevalue->fetch();
                                                                                    $initial_basevalue = !is_null($rows_initial_indbasevalue["basevalue"]) ? $rows_initial_indbasevalue["basevalue"] : 0;

                                                                                    $query_actual_ind_value = $db->prepare("SELECT SUM(achieved) AS basevalue FROM tbl_monitoringoutput m inner join tbl_project_details d on d.id=m.output_id WHERE m.record_type=1 AND d.indicator=:indid AND m.date_created <= '" . $base_date . "'");
                                                                                    $query_actual_ind_value->execute(array(":indid" => $indid));
                                                                                    $rows_actual_ind_value = $query_actual_ind_value->fetch();
                                                                                    $actual_basevalue = !is_null($rows_actual_ind_value["basevalue"]) ? $rows_actual_ind_value["basevalue"] : 0;

                                                                                    $basevalue = $initial_basevalue + $actual_basevalue;

                                                                                    $query_annual_target = $db->prepare("SELECT SUM(target) as target FROM `tbl_progdetails` WHERE indicator=:indid AND year=:finyear");
                                                                                    $query_annual_target->execute(array(":indid" => $indid, ":finyear" => $start_year));
                                                                                    $rows_annual_target = $query_annual_target->fetch();
                                                                                    $annualtarget = !is_null($rows_annual_target["target"]) ? $rows_annual_target["target"] : 0;

                                                                                    $query_year_target = $db->prepare("SELECT * FROM tbl_programs_quarterly_targets WHERE indid=:indid AND year=:finyear");
                                                                                    $query_year_target->execute(array(":indid" => $indid, ":finyear" => $start_year));
                                                                                    $rows_year_target = $query_year_target->fetch();
                                                                                    $totalRows_year_target = $query_year_target->rowCount();


                                                                                    $query_year_one_actual = $db->prepare("SELECT SUM(achieved) AS achieved FROM tbl_monitoringoutput m inner join tbl_project_details d on d.id=m.output_id WHERE indicator=:indid AND (date_created >= '" . $start_date . "' AND date_created <= '" . $end_date . "')");
                                                                                    $query_year_one_actual->execute(array(":indid" => $indid));
                                                                                    $rows_year_one_actual = $query_year_one_actual->fetch();
                                                                                    $totalRows_year_one_actual = $query_year_one_actual->rowCount();
                                                                                    $year_achived =  !is_null($rows_year_one_actual["achieved"]) ? $rows_quarter_one_actual["achieved"] : 0;

                                                                                    $year_rate1 = 0;
                                                                                    if ($totalRows_year_target > 0) {
                                                                                        $year_target +=  $rows_year_target["Q1"];
                                                                                        $year_target +=  $rows_year_target["Q2"];
                                                                                        $year_target +=  $rows_year_target["Q3"];
                                                                                        $year_target +=  $rows_year_target["Q4"];
                                                                                        $year_rate1 = $year_achived > 0 && $year_target > 0 ? (($year_achived / $year_target) * 100) : 0;
                                                                                    }
                                                                                    $year_rate = number_format($year_rate1, 2) . "%";
                                                                                }

                                                                                $query_indRemarks =  $db->prepare("SELECT * FROM tbl_capr_report_remarks WHERE indid=:indid AND year=:financial_year_id");
                                                                                $query_indRemarks->execute(array(":indid" => $indid, ":financial_year_id" => $financial_year_id));
                                                                                $row_indRemarks = $query_indRemarks->fetch();
                                                                                $count_row_indRemarks = $query_indRemarks->rowCount();
                                                                                $remarks = ($count_row_indRemarks > 0) ?  $row_indRemarks['remarks'] : "No record";
                                                                                $year_proceed_button[] = ($count_row_indRemarks > 0) ?  true : false;
                                                                                $edit = ($count_row_indRemarks > 0) ?  "edit" : "new";
                                                                                $remark_id = $count_row_indRemarks > 0 ? $row_indRemarks['id'] : '';
                                                                        ?>
                                                                                <tr>
                                                                                    <td><?= $counter ?></td>
                                                                                    <td colspan=""><?= $indicator ?></td>
                                                                                    <td colspan=""><?= $unit . " of " .  $indicator ?></td>
                                                                                    <td colspan=""><?= number_format($basevalue) ?></td>
                                                                                    <td colspan=""><?= number_format($annualtarget) ?></td>
                                                                                    <td><?= number_format($quarter_target) ?></td>
                                                                                    <td><?= number_format($quarter_achived) ?></td>
                                                                                    <td><?= $quarter_rate ?></td>
                                                                                    <td>
                                                                                        <button type="button" data-toggle="modal" data-target="#remarksItemModal" id="remarksItemModalBtn" class="btn btn-success btn-sm" onclick="remarks(<?= $indid ?>, 2,'<?= $edit ?>', '<?= $remark_id ?>')">
                                                                                            <i class="glyphicon glyphicon-file"></i>
                                                                                            <strong> <?= ($count_row_indRemarks > 0)  ? "Edit" : "Add" ?> Remarks</strong>
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <h4>Chapter 2: Challenges and Recommendations </h4>
                                                                <p align="left">
                                                                    <textarea name="year_challenges" cols="45" rows="5" class="txtboxes" id="year_challenges" required="required" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder="."><?= ($totalRows_rsConclusion > 0) ? $Rows_rsConclusion['challenges'] : "";  ?></textarea>
                                                                    <script>
                                                                        CKEDITOR.replace('year_challenges', {
                                                                            height: 200,
                                                                            on: {
                                                                                instanceReady: function(ev) {
                                                                                    // Output paragraphs as <p>Text</p>.
                                                                                    this.dataProcessor.writer.setRules('p', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                    this.dataProcessor.writer.setRules('ol', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                    this.dataProcessor.writer.setRules('ul', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                    this.dataProcessor.writer.setRules('li', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                }
                                                                            }
                                                                        });
                                                                    </script>
                                                                </p>
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <h4>Chapter 3: Lessons learnt and Conclusion</h4>
                                                                <p align="left">
                                                                    <textarea name="year_conclusion" cols="45" rows="5" class="txtboxes" id="year_conclusion" required="required" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder=""><?= ($totalRows_rsConclusion > 0) ? $Rows_rsConclusion['conclusion'] : "";  ?></textarea>
                                                                    <script>
                                                                        CKEDITOR.replace('year_conclusion', {
                                                                            height: 200,
                                                                            on: {
                                                                                instanceReady: function(ev) {
                                                                                    // Output paragraphs as <p>Text</p>.
                                                                                    this.dataProcessor.writer.setRules('p', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                    this.dataProcessor.writer.setRules('ol', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                    this.dataProcessor.writer.setRules('ul', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                    this.dataProcessor.writer.setRules('li', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                }
                                                                            }
                                                                        });
                                                                    </script>
                                                                </p>
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <h4>Appendices for additional documents/materials</h4>
                                                                <p align="left">
                                                                    <textarea name="year_appendice" cols="45" rows="5" class="txtboxes" id="year_appendice" required="required" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder="">  <?= ($totalRows_rsConclusion > 0) ? $Rows_rsConclusion['appendices'] : "";  ?></textarea>
                                                                    <script>
                                                                        CKEDITOR.replace('year_appendice', {
                                                                            height: 200,
                                                                            on: {
                                                                                instanceReady: function(ev) {
                                                                                    // Output paragraphs as <p>Text</p>.
                                                                                    this.dataProcessor.writer.setRules('p', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                    this.dataProcessor.writer.setRules('ol', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                    this.dataProcessor.writer.setRules('ul', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                    this.dataProcessor.writer.setRules('li', {
                                                                                        indent: false,
                                                                                        breakBeforeOpen: false,
                                                                                        breakAfterOpen: false,
                                                                                        breakBeforeClose: false,
                                                                                        breakAfterClose: false
                                                                                    });
                                                                                }
                                                                            }
                                                                        });
                                                                    </script>
                                                                </p>
                                                            </div>
                                                            <div class="col-md-12" style="margin-top:15px" align="center">
                                                                <input type="hidden" name="MM_insert" value="addReport">
                                                                <input type="hidden" name="y_report_id" value="<?= ($totalRows_rsConclusion > 0) ? $Rows_rsConclusion['id'] : "";  ?>">
                                                                <input type="hidden" name="year_edit" value="<?= ($totalRows_rsConclusion > 0) ? "edit" : "new";  ?>">
                                                                <input type="hidden" name="year" value="<?= $year ?>">
                                                                <input type="hidden" name="year_sector_id" value="<?= $sector_id ?>">
                                                                <input type="hidden" name="username" id="username" value="<?= $user_name ?>">
                                                                <?php
                                                                if (!in_array(false, $year_proceed_button)) {
                                                                    $status_id = ($totalRows_rsConclusion > 0) ? $Rows_rsConclusion['status'] : "";
                                                                    if ($approve_responsible && $status_id == 1) {
                                                                ?>
                                                                        <input type="submit" class="btn btn-success" name="year_button_two" id="button1" value="Approve">
                                                                    <?php
                                                                    } else {
                                                                    ?>
                                                                        <input type="submit" class="btn btn-success" name="year_button_two" id="button2" value="Proceed">
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- end body  -->


    <div class="modal fade" tabindex="-1" role="dialog" id="remarksItemModal">
        <div class="modal-dialog md-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-file"></i> Indicator Remarks</h4>
                </div>
                <div class="modal-body" style="max-height:450px; overflow:auto;">
                    <div class="card">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="body">
                                    <div class="div-result">
                                        <form class="form-horizontal" id="add_items" action="" method="POST" autocomplete="off" enctype="multipart/form-data">
                                            <?= csrf_token_html(); ?>
                                            <br>
                                            <div id="result">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Comments:</label>
                                                    <div class="form-line">
                                                        <textarea name="remarks" cols="" rows="7" class="form-control" id="remarks" placeholder="Enter Comments if necessary" style="width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer editItemFooter">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                    <input type="hidden" name="username" id="username" value="<?= $user_name ?>">
                                                    <input type="hidden" name="year" id="year" value="<?= $year ?>">
                                                    <input type="hidden" name="quarter" id="quarter" value="<?= $quarter ?>">
                                                    <input type="hidden" name="indicator" id="indicator" value="">
                                                    <input type="hidden" name="record_type" id="record_type" value="">
                                                    <input type="hidden" name="remarks_id" id="remarks_id" value="">
                                                    <input type="hidden" name="store" id="store" value="">
                                                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal">Cancel</button>
                                                    <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save">
                                                </div>
                                            </div> <!-- /modal-footer -->
                                        </form> <!-- /.form -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- /modal-body -->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>
<script>

</script>