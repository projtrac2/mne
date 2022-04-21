<?php
$stplan = (isset($_GET['plan'])) ? base64_decode($_GET['plan']) : header("Location: view-strategic-plans.php");
$stplane = base64_encode($stplan);
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
$pageTitle = $planlabelplural;

if ($permission) {
    require('functions/strategicplan.php');
    try {
        $strategicPlan = get_strategic_plan($stplan);
        if (!$strategicPlan) {
            header("Location: view-strategic-plans.php");
        }

        $strategicplan = $strategicPlan["plan"];
        $vision = $strategicPlan["vision"];
        $mission = $strategicPlan["mission"];
        $datecreated = $strategicPlan["date_created"];

        // get the key results areas under this strategic plan 
        $kras = get_strategic_plan_kras($stplan);
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
?>
    <!-- JQuery Nestable Css -->
    <link href="projtrac-dashboard/plugins/nestable/jquery-nestable.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/strategicplan/view-strategic-plan-framework.css">

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
                    <?= $results; ?> FRAMEWORK
                    <button onclick="history.go(-1)" class="btn bg-orange waves-effect pull-right" style="margin-right: 10px">
                        Go Back
                    </button>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="header" style="padding-bottom:0px">
                                <div class="button-demo" style="margin-top:-15px">
                                    <span class="label bg-black" style="font-size:18px">
                                        <img src="assets/images/proj-icon.png" alt="Project" title="Project" style="vertical-align:middle; height:25px" /> Menu
                                    </span>
                                    <a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:4px"><?= $planlabel ?> Details</a>
                                    <a href="view-kra.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Key Results Area</a>
                                    <a href="view-strategic-plan-objectives.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Strategic Objectives</a>
                                    <a href="view-strategic-workplan-budget.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Targets Distribution</a>
                                    <a href="view-program.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Programs</a>
                                    <a href="strategic-plan-projects.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Projects</a>
                                    <a href="view-objective-performance.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Progress Report</a>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <div class="tab-content">
                                <div id="home" class="tab-pane fade in active">
                                    <div class="header">
                                        <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
                                                <tr>
                                                    <td width="100%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF">
                                                        <div align="left">
                                                            <img src="assets/images/projbrief.png" alt="img" />
                                                            <strong><?= $planlabel ?> Framework</strong>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="body table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr style="background-color:#eaf1fc">
                                                    <th style="width:14%"><img src="assets/images/code.png" alt="img" /> <strong>Plan</strong></th>
                                                    <th colspan="2" style="width:38%"><img src="assets/images/status.png" alt="img" /> <strong>Vision</strong></th>
                                                    <th colspan="3" style="width:38%"><img src="assets/images/status.png" alt="img" /> <strong>Mission</strong></th>
                                                    <th style="width:10%"><img src="assets/images/date.png" alt="img" /> <strong>End Date</strong></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?php echo $strategicplan; ?></td>
                                                    <?php
                                                    $pdate = strtotime($datecreated);
                                                    $dateadded = date("d M Y", $pdate);
                                                    ?>
                                                    <td colspan="2"><?php echo $vision; ?></td>
                                                    <td colspan="3"><?php echo $mission; ?></td>
                                                    <td><?php echo $dateadded; ?></td>
                                                </tr>
                                            </tbody>

                                            <thead>
                                                <tr style="background-color:#eaf1fc">
                                                    <th colspan="7">Key Results Areas (KRA)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="7">
                                                        <div class="clearfix m-b-20">
                                                            <div class="dd" id="nestable">
                                                                <ol class="dd-list">
                                                                    <?php
                                                                    if (!$kras) {
                                                                        echo '<div style="color:RED">NO KRAs DEFINED FOR THIS STRATEGIC PLAN!!</div>';
                                                                    } else {
                                                                        $sn = 0;
                                                                        foreach ($kras as $kra) {
                                                                            $sn = $sn + 1;
                                                                    ?>
                                                                            <li class="dd-item" data-id="4">
                                                                                <div class="dd-handle">KRA <?php echo $sn; ?>:<font color="blue"> <?php echo $kra['kra']; ?></font>
                                                                                </div>
                                                                                <ol class="dd-list">
                                                                                    <?php
                                                                                    $kraid =  $kra['id'];
                                                                                    $kradesc =  $kra['description'];
                                                                                    $kradate = date("d M Y", strtotime($kra["date_created"]));

                                                                                    // get strategic objectives under this kra 
                                                                                    $strategicObjectives = get_kra_strategic_objectives($kraid);

                                                                                    if (!$strategicObjectives) {
                                                                                        echo '<div style="color:red">NO OBJECTIVE(S) DEFINED FOR THIS KRA!!</div>';
                                                                                    } else {
                                                                                        $nb = 0;
                                                                                        foreach ($strategicObjectives as $strategicObjective) {
                                                                                            $nb = $nb + 1;
                                                                                            $objid = $strategicObjective['id'];
                                                                                            $objective = $strategicObjective['objective'];
                                                                                            $objdesc = $strategicObjective['description'];
                                                                                            $objdate = date("d M Y", strtotime($strategicObjective['date_created']));
                                                                                    ?>
                                                                                            <li class="dd-item" data-id="5">
                                                                                                <div class="dd-handle">Objective <?php echo $nb; ?>: <font color="#9C27B0"><?php echo $objective; ?></font>
                                                                                                </div>
                                                                                                <ol class="dd-list">
                                                                                                    <?php

                                                                                                    // get objective strategy 
                                                                                                    $objectiveStrategies =  get_strategic_objectives_strategy($objid);

                                                                                                    if ($objectiveStrategies) {
                                                                                                        $sr = 0;
                                                                                                        foreach ($objectiveStrategies as $objectiveStrategy) {
                                                                                                            $sr = $sr + 1;
                                                                                                            $strgid = $objectiveStrategy['id'];
                                                                                                            $strategy = $objectiveStrategy['strategy'];
                                                                                                            // $strgdesc = $objectiveStrategy['description'];
                                                                                                            $strgdate = date("d M Y", strtotime($objectiveStrategy['date_created']));
                                                                                                    ?>
                                                                                                            <li class="dd-item" data-id="13">
                                                                                                                <div class="dd-handle">Strategy <?php echo $sr; ?>: <?php echo $objectiveStrategy['strategy']; ?></div>
                                                                                                            </li>
                                                                                                        <?php
                                                                                                        }
                                                                                                    } else {
                                                                                                        ?>
                                                                                                        <li class="dd-item" data-id="9">
                                                                                                            <div class="dd-handle" style="color:#F44336">No Strategy defined for this Objective</div>
                                                                                                        </li>
                                                                                                    <?php
                                                                                                    }
                                                                                                    ?>
                                                                                                </ol>
                                                                                            </li>
                                                                                    <?php
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                </ol>
                                                                            </li>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </ol>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
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
?>

<!-- Jquery Nestable -->
<script src="assets/projtrac-dashboard/plugins/nestable/jquery.nestable.js"></script>
<script src="assets/projtrac-dashboard/js/pages/ui/sortable-nestable.js"></script>