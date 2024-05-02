<?php
try {
    require('includes/head.php');
    require('functions/strategicplan.php');
    if ($permission && (isset($_GET['obj']) && !empty($_GET["obj"]))) {
        $decode_objid = base64_decode($_GET['obj']);
        $objid_array = explode("obj321", $decode_objid);
        $objid = $objid_array[1];
        $original_objid = $_GET['obj'];

        $strategic_objective = get_strategic_objective($objid);
        if ($strategic_objective) {
            $current_date = date("Y-m-d");
            $user = $user_name;
            $kraid = $strategic_objective["kraid"];
            $objective = $strategic_objective["objective"];
            $description = $strategic_objective["description"];
            $result_kra = get_kra($kraid);
            $stplanid = $result_kra['spid'];
            $stplan = base64_encode("strplan1{$stplanid}");
            $strategic_objectives_strategies = get_strategic_objectives_strategy($objid);

            if (isset($_POST['editplan'])) {
                $objid = $_POST['objid'];
                $objective = $_POST['objective'];
                $desc = $_POST['objdesc'];
                $kpi = 1;
                $kraid = $_POST['kraid'];

                $ObjectivesInsert = $db->prepare("UPDATE tbl_strategic_plan_objectives SET kraid=:kraid, objective=:objective, description=:desc, kpi=:kpi, created_by=:user, date_created=:dates WHERE id=:objid");
                $resultObjectives = $ObjectivesInsert->execute(array(":kraid" => $kraid, ":objective" => $objective, ":desc" => $desc, ":kpi" => $kpi, ":user" => $user, ":dates" => $current_date, ":objid" => $objid));

                if ($resultObjectives) {
                    $stcount = count($_POST["strategic"]);
                    if ($stcount > 0) {
                        $query_delete = $db->prepare("DELETE FROM tbl_objective_strategy WHERE objid=:objid");
                        $query_delete->execute(array(":objid" => $objid));
                        for ($cnt = 0; $cnt < $stcount; $cnt++) {
                            $strategy = $_POST['strategic'][$cnt];
                            $sqlinsert = $db->prepare("INSERT INTO tbl_objective_strategy (objid,strategy,created_by,date_created) VALUES (:objid, :strategy, :user, :dates)");
                            $sqlinsert->execute(array(":objid" => $objid, ":strategy" => $strategy, ":user" => $user, ":dates" => $current_date));
                        }
                    }
                    $msg = 'Strategic Objective successfully updated';
                    if (isset($_POST['update'])) {
                        $results = "<script type=\"text/javascript\">
                        swal({
                        title: \"Success!\",
                        text: \" $msg\",
                        type: 'Success',
                        timer: 3000,
                        icon:'success',
                        showConfirmButton: false });
                        setTimeout(function(){
                            window.location.href = 'view-strategic-plan-objectives?plan=$stplan';
                        }, 3000);
                    </script>";
                        echo $results;
                    }
                }
            }
?>
            <script src="assets/ckeditor/ckeditor.js"></script>

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
                                    <div class="body" id="objective_table"></div>
                                    <h5> <strong>Key Result Area:</strong><u> <?php echo $result_kra['kra'] ?> </u></h5>
                                    <form action="" method="POST" class="form-inline" role="form" id="stratcplan">
                                        <?= csrf_token_html(); ?>
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Add Strategic Objectives. </legend>
                                            <div class="col-md-12">
                                                <label class="control-label">Strategic Objective *:</label>
                                                <div class="form-line">
                                                    <input name="kraid" type="hidden" id="kraid" value="<?php echo $result_kra['id']; ?>" />
                                                    <input name="objective" type="text" class="form-control" style="width:100%; border:#CCC thin solid; border-radius: 5px" value="<?php echo $objective; ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="control-label">Strategic Objective Description : <font align="left" style="background-color:#eff2f4"> </font></label>
                                                <p align="left">
                                                    <textarea name="objdesc" cols="45" rows="4" class="txtboxes" id="objdesc" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"><?php echo $description; ?></textarea>
                                                    <script>
                                                        CKEDITOR.replace('objdesc', {
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
                                        </fieldset>

                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Add Strategy(s).
                                            </legend>
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="card" style="margin-bottom:-20px">
                                                        <div class="header">
                                                            <i class="ti-link"></i>MULTIPLE STRATEGIES - WITH CLICK & ADD
                                                        </div>
                                                        <div class="body">
                                                            <table class="table table-bordered" id="strategy_table">
                                                                <tr>
                                                                    <th style="width:98%">Strategy</th>
                                                                    <th style="width:2%">
                                                                        <button type="button" name="addplus" onclick="add_strow();" title="Add another field" class="btn btn-success btn-sm">
                                                                            <span class="glyphicon glyphicon-plus"></span>
                                                                        </button>
                                                                    </th>
                                                                </tr>
                                                                <?php
                                                                if ($strategic_objectives_strategies) {
                                                                    $nm = 0;
                                                                    foreach ($strategic_objectives_strategies as $strategic_objectives_strategy) {
                                                                        $nm++;
                                                                        $strategy = $strategic_objectives_strategy['strategy'];
                                                                        echo '
                                                            <tr id="row' . $nm . '">
                                                                <td>
                                                                <input type="text" name="strategic[]" id="strategic" class="form-control" value="' . $strategy . '" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
                                                                </td>
                                                                ';
                                                                        if ($nm == 1) {
                                                                            echo '<td></td>';
                                                                        } else {
                                                                            echo   '
                                                                <td>
                                                                    <button type="button" class="btn btn-danger btn-sm"  onclick=delete_strow("row' . $nm . '")>
                                                                        <span class="glyphicon glyphicon-minus"></span>
                                                                    </button>
                                                                </td>';
                                                                        }
                                                                        echo '
                                                            </tr>';
                                                                    }
                                                                }
                                                                ?>
                                                            </table>
                                                            <input name="editplan" type="hidden" id="editplan" value="editplan" />
                                                            <input name="username" type="hidden" id="username" value="<?php echo $user_name; ?>" />
                                                            <input name="objid" type="hidden" id="objid" value="<?php echo $objid; ?>" />
                                                            <div class="list-inline" align="center" style="margin-top:20px">
                                                                <button type="submit" name="update" class="btn btn-primary" id="">
                                                                    Update
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </form>
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
    } else {
        $results =  restriction();
        echo $results;
    }
    require('includes/footer.php');
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>
<script src="assets/js/strategicplan/view-kra-objective.js"></script>