<?php
$objid = (isset($_GET['objid'])) ? base64_decode($_GET['objid']) : "";
$pageName = "Strategic Plan Programs";
$Id = 2;
$subId = 20;


require('includes/head.php');
require('includes/header.php');
require('functions/programs.php');

try {
    $strategic_plan_programs = get_programs(3, $objid);
    $total_strategic_plan_programs = ($strategic_plan_programs) ? count($strategic_plan_programs) : 0;
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
}

?>

<style>
    .modal-lg {
        max-width: 100% !important;
        width: 90%;
    }
</style>

<div class="body">
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="manageItemTable">
            <thead>
                <tr id="colrow">
                    <th width="3%">#</th>
                    <th width="24%">Program</th>
                    <th width="12%">Program Type</th>
                    <th width="13%">Budget (ksh)</th>
                    <th width="15%">Budget Bal (ksh)</th>
                    <th style="width:8%">Project(s)</th>
                    <th width="10%">Start Year </th>
                    <th width="8%">Duration </th>
                    <th width="7%" data-orderable="false">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($total_strategic_plan_programs > 0) {
                    $nm = 0;
                    foreach ($strategic_plan_programs as $strategic_plan_program) {
                        $nm++;
                        $progname =  $strategic_plan_program['progname'];
                        $projduration = $strategic_plan_program['years'] . " Years";
                        $projsyear = $strategic_plan_program['syear'];
                        $projendsfc = $projsyear + 1;
                        $progid = $strategic_plan_program['progid'];
                        $program_budget = get_program_budget($progid);
                        $program_amount_spent = get_program_amount_spent($progid);
                        $budget_balance = ($program_budget > 0) ? $program_budget - $program_amount_spent : 0;
                        $program_projects =  get_program_projects($progid);
                        $total_projects = ($program_projects) ? count($program_projects) : 0;
                ?>
                        <tr>
                            <td><?= $nm ?></td>
                            <td><?= $progname ?></td>
                            <td><?= $progname ?></td>
                            <td> <?= number_format($program_budget, 2) ?> </td>
                            <td> <?= number_format($budget_balance, 2) ?> </td>
                            <td>
                                <a href="view-projects?progid=<?= base64_encode($progid) ?>">
                                    <span class="badge bg-purple"><?= $total_projects ?></span>
                                </a>
                            </td>
                            <td> <?= $projsyear . "/" . $projendsfc ?> </td>
                            <td><?= $projduration ?> </td>
                            <td>
                                <!-- Single button -->
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Options <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a type="button" data-toggle="modal" data-target="#removeItemModal" id="moreInfoModalBtn" onclick="more(<?= $progid ?>)">
                                                <i class="glyphicon glyphicon-file"></i> More Info
                                            </a>
                                        </li>
                                    </ul>
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
<?php
require('includes/footer.php');
?>



<!-- Start Item Delete -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeItemModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#03A9F4">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i>Program </h4>
            </div>
            <div class="modal-body">
                <div id="moreinfo"></div> 
            </div>
            <div class="modal-footer removeContractor NationalityFooter">
                <div class="col-md-12 text-center"> 
                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Cancel</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Item Delete -->

<script>
    function more(itemId = null) {
        if (itemId) {
            $.ajax({
                url: "general-settings/selected-items/fetch-selected-program-item",
                type: "post",
                data: {
                    itemId: itemId
                },
                dataType: "html",
                success: function(response) {
                    $("#moreinfo").html(response);
                    console.log(response);
                },
            });
        } else {
            alert("error please refresh the page");
        }
    }
</script>