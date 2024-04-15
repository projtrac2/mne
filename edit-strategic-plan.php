<?php
    try {

$stplan = (isset($_GET['stplan'])) ? base64_decode($_GET['stplan']) : header("Location:view-strategic-plans.php");
$stplane = base64_encode($stplan);
require('includes/head.php');
require('functions/strategicplan.php');
if ($permission) {
        $strategicPlan = get_strategic_plan_by_id($stplan);
        if (!$strategicPlan) {
            header("Location: view-strategic-plans.php");
        }

        $strategic_plan = $strategicPlan["plan"];
        $strid = $strategicPlan["id"];
        $vision = $strategicPlan["vision"];
        $mission = $strategicPlan["mission"];
        $years = $strategicPlan["years"];
        $syear = $strategicPlan["starting_year"];
        $datecreated = $strategicPlan["date_created"];
        $kras = get_strategic_plan_kras($strid);

        function get_end_year()
        {
            global $db, $stplan;
            $query_rsEndYear =  $db->prepare("SELECT * FROM tbl_strategicplan where id != $stplan ORDER BY id DESC LIMIT 1 ");
            $query_rsEndYear->execute();
            $row_rsEndYear = $query_rsEndYear->fetch();
            $totalRows_rsEndYear = $query_rsEndYear->rowCount();

            $end_year = 0;
            if ($totalRows_rsEndYear > 0) {
                $start_year = $row_rsEndYear['starting_year'];
                $years = $row_rsEndYear['years'];
                $end_year = $start_year + $years;
            }
            return $end_year;
        }


        $current_date = date("Y-m-d");
        $user = $user_name;
        if (isset($_POST['addplan'])) {
            $strategicplan = $_POST['strategicplan'];
            $vision = $_POST['vision'];
            $mission = $_POST['mission'];
            $years = $_POST['years'];
            $styear = $_POST['startingyear'];
            $stratplanid = $_POST['stratplanid'];
            $active = 0;
            $strplanInsert = $db->prepare("UPDATE tbl_strategicplan  SET plan=:plan, vision=:vision, mission=:mission, years=:years, starting_year=:styear, updated_by=:user, date_updated=:dates WHERE id=:stratplanid");
            $resultstrplan = $strplanInsert->execute(array(":plan" => $strategicplan, ":vision" => $vision, ":mission" => $mission, ":years" => $years, ":styear" => $styear, ":user" => $user, ":dates" => $current_date, ':stratplanid' => $stratplanid));

            if ($resultstrplan) {
                $query_delete = $db->prepare("DELETE FROM tbl_key_results_area WHERE spid=:spid");
                $query_delete->execute(array(":spid" => $stratplanid));
                for ($cnt = 0; $cnt < count($_POST["kra"]); $cnt++) {
                    $kra = $_POST['kra'][$cnt];
                    $kraInsert = $db->prepare("INSERT INTO tbl_key_results_area (spid,kra,created_by,date_created) VALUES (:spid, :kra, :user, :dates)");
                    $result = $kraInsert->execute(array(":spid" => $stratplanid, ":kra" => $kra, ":user" => $user, ":dates" => $current_date));
                }

                $msg = 'The ' . $planlabel . ' was successfully created.';
                $results = "<script type=\"text/javascript\">
                    swal({
                        title: \"Success!\",
                        text: \" $msg\",
                        type: 'Success',
                        timer: 2000,
                        icon:'success',
                        showConfirmButton: false });
                        setTimeout(function(){
                                window.location.href = 'view-strategic-plans.php';
                        }, 2000);
                    </script>";
                echo $results;
            }
        }

?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet" />
    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <?= $icon ?>
                    <?= $pageTitle ?>
                    <div class="btn-group" style="float:right">
                        <div class="btn-group" style="float:right">
                            <button onclick="history.back()" type="button" class="btn bg-orange waves-effect" style="float:right; margin-top:-5px">
                                Go Back
                            </button>
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
                            <form action="" method="POST" class="form-inline" role="form" id="stratcplan">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                        <span id="">Edit</span> <?= $planlabel ?>.
                                    </legend>
                                    <div class="col-md-12">
                                        <label class="control-label"> <?= $planlabel ?> *:</label>
                                        <div class="form-line">
                                            <input name="strategicplan" type="text" value="<?= $strategic_plan ?>" class="form-control" style="width:100%; border:#CCC thin solid; border-radius: 5px" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="control-label">Vision *:</label>
                                        <div class="form-line">
                                            <input name="vision" type="text" value="<?= $vision ?>" class="form-control" style="width:100%; border:#CCC thin solid; border-radius: 5px" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="control-label">Mission *:</label>
                                        <div class="form-line">
                                            <input name="mission" type="text" value="<?= $mission ?>" class="form-control" style="width:100%; border:#CCC thin solid; border-radius: 5px" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Starting from (Year) *:</label>
                                        <input name="startingyear" id="stryear" type="text" value="<?= $syear ?>" onchange="validate()" class="form-control" style="width:100%; border:#CCC thin solid; border-radius: 5px" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Number of Years *: </label>
                                        <div class="form-line">
                                            <input name="years" type="number" value="<?= $years ?>" class="form-control" style="width:100%; border:#CCC thin solid; border-radius: 5px" required>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Add Key Results Area(s).
                                    </legend>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="card" style="margin-bottom:-20px">
                                                <div class="header">
                                                    <i class="ti-link"></i>MULTIPLE KEY RESULTS AREA/S - WITH CLICK & ADD
                                                </div>
                                                <div class="body">
                                                    <table class="table table-bordered" id="funding_table">
                                                        <tr>
                                                            <th style="width:98%">Key Results Area</th>
                                                            <th style="width:2%">
                                                                <button type="button" name="addplus" onclick="add_row();" title="Add another field" class="btn btn-success btn-sm">
                                                                    <span class="glyphicon glyphicon-plus"></span>
                                                                </button>
                                                            </th>
                                                        </tr>
                                                        <?php
                                                        if ($kras) {
                                                            $l = 0;
                                                            foreach ($kras as $kra) {
                                                                $l++;
                                                        ?>
                                                                <tr id="row<?= $l ?>">
                                                                    <td>
                                                                        <input type="text" name="kra[]" id="kra" class="form-control" value="<?= $kra['kra'] ?>" placeholder="Enter the Key Results Area" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
                                                                    </td>
                                                                    <?php
                                                                    if ($l == 1) {
                                                                    ?>
                                                                        <td></td>
                                                                    <?php
                                                                    } else {
                                                                    ?>
                                                                        <td>
                                                                            <button type="button" class="btn btn-danger btn-sm" onclick="delete_row('row<?= $l ?>')">
                                                                                <span class="glyphicon glyphicon-minus"></span>
                                                                            </button>
                                                                        </td>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </tr>
                                                            <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <input type="text" name="kra[]" id="kra" class="form-control" placeholder="Enter the Key Results Area" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
                                                                </td>
                                                                <td></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                    </table>
                                                    <input name="addplan" type="hidden" id="addplan" value="addplan" />
                                                    <input name="stratplanid" type="hidden" id="stratplanid" value="<?= $stplan ?>" />
                                                    <input name="username" type="hidden" id="username" value="<?php echo $user_name; ?>" />
                                                    <div class="list-inline" align="center" style="margin-top:20px">
                                                        <button type="submit" class="btn btn-primary" id="">
                                                            Save
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
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
require('includes/footer.php');
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>

<script src="assets/custom js/add-strategic-plan.js"></script>
<script>
    $(document).ready(function() {
        $("#stryear").datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            startView: 'decade',
            viewSelect: 'decade',
            autoclose: true,
        });
    });

    function validate() {
        const end_year = <?= get_end_year() ?>;
        var year = $("#stryear").val();
        if (year != "") {
            if (year <= end_year) {
                $("#stryear").val("");
                swal("Starting year should be greater than the final year of the previous <?= $planlabel ?>");
            }
        }
    }
</script>