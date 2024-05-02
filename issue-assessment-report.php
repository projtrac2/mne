<?php
try {
    require('includes/head.php');
    if ($permission) {

        if (isset($_GET['eval'])) {
            $evalid = $_GET['eval'];
        }

        $query_conclEvaluation = $db->prepare("SELECT f.id as formid, p.projid, projname, projdesc, form_name, f.description, sequence, date, c.id as conc, itemid FROM tbl_projects p INNER JOIN tbl_project_evaluation_forms f ON f.projid=p.projid INNER JOIN tbl_project_evaluation_conclusion c ON f.id=c.formid INNER JOIN tbl_projects_evaluation e ON e.id=f.evaluation_id INNER JOIN tbl_project_evaluation_types t ON t.sequence=e.evaluation_type WHERE e.status=4 and  f.status=3 and e.id = '$evalid' ORDER BY c.id ASC");
        $query_conclEvaluation->execute();
        $row_conclEvaluation = $query_conclEvaluation->fetch();

        $formid = $row_conclEvaluation['formid'];
        $projid = $row_conclEvaluation['projid'];
        $conc = $row_conclEvaluation['conc'];
        $issueid = $row_conclEvaluation['itemid'];
        $evaltype = $row_conclEvaluation['sequence'];
        $concludeddate = date("d M Y", strtotime($row_conclEvaluation["date"]));
        $projname = $row_conclEvaluation["projname"];
        $projdescription = $row_conclEvaluation["projdesc"];
        $evalpurpose = $row_conclEvaluation["description"];

        $query_formdetails =  $db->prepare("SELECT f.id, p.projname, p.projdesc, p.projcategory, p.projcost, e.evaluation_type FROM tbl_projects p inner join tbl_projects_evaluation e on e.projid=p.projid inner join tbl_project_evaluation_forms f on f.evaluation_id=e.id WHERE f.id='$formid' and e.status=4 and f.status=3");
        $query_formdetails->execute();
        $row_formdetails = $query_formdetails->fetch();
        $formid = $row_formdetails["id"];

        $query_evalobj = $db->prepare("SELECT * FROM tbl_project_evaluation_form_sections WHERE formid='$formid'");
        $query_evalobj->execute();

        $query_evalconcl = $db->prepare("SELECT * FROM tbl_project_evaluation_conclusion WHERE projid='$projid' AND formid='$formid'");
        $query_evalconcl->execute();
        $row_evalconcl = $query_evalconcl->fetch();
        $conclusion = $row_evalconcl["conclusion"];
        $recommendation = $row_evalconcl["recommendation"];

        $query_evalfindings =  $db->prepare("SELECT * FROM tbl_project_evaluation_form_sections WHERE formid = '$formid'");
        $query_evalfindings->execute();
        $totalRows_evalfindings = $query_evalfindings->rowCount();

?>
        <!-- start body  -->
        <section class="content">
            <div class="container-fluid">
                <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                    <h4 class="contentheader">
                        <?= $icon ?>
                        <?= $pageTitle ?>
                        <div class="btn-group" style="float:right">
                            <div class="btn-group" style="float:right">
                                <a href="project-escalated-issue.php?issueid=<?= $issueid ?>" class="btn btn-warning" style="height:27px; ; margin-top:-1px; vertical-align:center">Go Back</a>
                            </div>
                        </div>
                    </h4>
                </div>
                <div class="row clearfix">
                    <div class="block-header">
                        <?= $results; ?>
                        <div style="color:#333; background-color:#EEE; width:100%; height:35px; padding-top:5px; padding-left:2px">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-3px">
                                <tr>
                                    <td width="50%" style="float:right; font-size:11px" align="right">
                                        <div class="btn-group pull-right">
                                            <button type="button" class="btn bg-pink dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Export <span class="caret"></span></button>
                                            <ul class="dropdown-menu">
                                                <li style="padding-left:20px; background-color:#FF9800">Export to:</li>
                                                <li role="separator" class="divider"></li>
                                                <li><a href="evaluation-pdf-report?fm=<?= $formid ?>" target="new">PDF</a></li>
                                                <li><a href="evaluation-csv-report?fm=<?= $formid ?>" target="new">EXCEL</a></li>
                                                <li><a href="evaluation-word-report?fm=<?= $formid ?>" target="new">WORD</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="body">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"> REPORT </legend>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="col-md-12">
                                                <label class="control-label">Project Name: </label>
                                                <div class="form-line">
                                                    <div style="border:#CCC thin solid; border-radius:5px; padding:10px; color:#3F51B5">
                                                        <strong><?php echo $projname; ?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="control-label">Project Background:</label>
                                                <div class="form-line">
                                                    <div style="border:#CCC thin solid; border-radius:5px; padding:10px; color:#3F51B5">
                                                        <strong><?php echo $projdescription; ?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="control-label">Purpose of the Evaluation:</label>
                                                <div class="form-line">
                                                    <div style="border:#CCC thin solid; border-radius:5px; padding:10px; color:#3F51B5">
                                                        <strong><?php echo $evalpurpose; ?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="control-label">Objectives of the Evaluation:</label>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped table-hover">
                                                        <thead>
                                                            <tr class="bg-light-blue">
                                                                <th style="width:5%">#</th>
                                                                <th style="width:95%">Objectives</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $nm = 0;
                                                            while ($row_evalobj = $query_evalobj->fetch()) {
                                                                $nm = $nm + 1;
                                                                $objectives = $row_evalobj["section"];
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $nm; ?></td>
                                                                    <td><?php echo $objectives; ?></td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="control-label">Methodology/Approach:</label>
                                                <div class="form-line">
                                                    <div style="border:#CCC thin solid; border-radius:5px; padding:10px; color:#3F51B5">
                                                        <strong><?php echo "Surveys, FGDs, key informant interviews, Staff debriefing, Data analysis and documentation."; ?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="control-label">Evaluation Findings:</label>

                                                <?php
                                                if ($totalRows_evalfindings > 0) {

                                                    $sn = 0;
                                                    while ($summary = $query_evalfindings->fetch()) {
                                                        $sn = $sn + 1;
                                                        $obj = $summary["section"];
                                                        $objid = $summary["id"];

                                                        $query_objquestions =  $db->prepare("SELECT * FROM `tbl_project_evaluation_answers` a INNER JOIN tbl_project_evaluation_form_question_fileds q ON q.id =a.fieldid WHERE q.formid = '$formid' AND q.sectionid = '$objid' GROUP BY a.fieldid");
                                                        $query_objquestions->execute();
                                                        $row_objquestions = $query_objquestions->fetchAll();
                                                ?>
                                                        <div class="col-md-12">
                                                            <?php
                                                            echo '<strong><u><h5 style="color:blue">Objective ' . $sn . ': <font color="green">' . $obj . '</font></h5></u></strong>';
                                                            ?>
                                                        </div>

                                                        <?php
                                                        foreach ($row_objquestions as $row) {
                                                            $fieldid = $row['fieldid'];
                                                            $type = $row['fieldtype'];
                                                            if ($type == "select" || $type == "radio-group") {
                                                                $query_rsAnswers = $db->prepare("SELECT q.label AS label, v.label AS answer  FROM `tbl_project_evaluation_answers` a INNER JOIN tbl_project_evaluation_form_question_fileds q ON q.id =a.fieldid INNER JOIN tbl_project_evaluation_form_question_filed_values v ON v.id =a.answer WHERE q.sectionid=:objectiveid AND a.fieldid=:fieldid");
                                                                $query_rsAnswers->execute(array(":objectiveid" => $objid, ":fieldid" => $fieldid));
                                                                $row_rsAnswers = $query_rsAnswers->fetchAll();
                                                                $totalRows_rsAnswers = $query_rsAnswers->rowCount();
                                                                $answer = array();
                                                                foreach ($row_rsAnswers as $data) {
                                                                    $answer[] = $data['answer'];
                                                                }
                                                                $data = array_count_values($answer);
                                                        ?>
                                                                <div class="col-md-6" style="height: 350px; border:#CCC thin solid; border-radius:5px; padding:10px; color:#3F51B5" id="chart_div<?php echo $objid . $fieldid; ?>"> </div>
                                                                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                                                                <script type="text/javascript">
                                                                    google.load("visualization", "1", {
                                                                        packages: ["corechart"]
                                                                    });
                                                                    google.setOnLoadCallback(drawChart);

                                                                    function drawChart() {
                                                                        var data = google.visualization.arrayToDataTable([
                                                                            ['Label', 'Count'],
                                                                            <?php
                                                                            $nb = 0;
                                                                            $nb = $nb - 1;
                                                                            foreach ($data as $key => $value) {
                                                                                $nb = $nb + 1;
                                                                                echo "['" . $key . "'," . $value . "],";
                                                                            }
                                                                            ?>
                                                                        ]);
                                                                        var options = {
                                                                            title: '<?php echo $nb . ". " . $row['label']; ?>',
                                                                            is3D: true,
                                                                            pieSliceTextStyle: {
                                                                                color: 'black',
                                                                            }
                                                                        };
                                                                        var chart = new google.visualization.PieChart(document.getElementById("chart_div<?php echo $objid . $fieldid; ?>"));
                                                                        chart.draw(data, options);
                                                                    }
                                                                </script>
                                                <?php
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    echo '<div class="col-md-12">
													<h5 style="color:red">Sorry no data found for this evaluation form<h5>
												</div>';
                                                }
                                                ?>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="control-label">Conclusions:</label>
                                                <div class="form-line">
                                                    <div style="border:#CCC thin solid; border-radius:5px; padding:10px; color:#3F51B5">
                                                        <strong><?php echo $conclusion; ?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if ($evaltype == 3) {
                                            } else { ?>
                                                <div class="col-md-12">
                                                    <label class="control-label">Recommendations:</label>
                                                    <div class="form-line">
                                                        <div style="border:#CCC thin solid; border-radius:5px; padding:10px; color:#3F51B5">
                                                            <strong><?php echo $recommendation; ?></strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </fieldset>
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
<script type="text/javascript">
    $(document).ready(function() {
        $(".account").click(function() {
            var X = $(this).attr('id');

            if (X == 1) {
                $(".submenus").hide();
                $(this).attr('id', '0');
            } else {

                $(".submenus").show();
                $(this).attr('id', '1');
            }

        });

        //Mouseup textarea false
        $(".submenus").mouseup(function() {
            return false
        });
        $(".account").mouseup(function() {
            return false
        });


        //Textarea without editing.
        $(document).mouseup(function() {
            $(".submenus").hide();
            $(".account").attr('id', '');
        });

    });
</script>