<?php
$pageName = "Strategic Plans";
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
$pageTitle = $planlabelplural;

if ($permission) {
    try {
        $stplan = (isset($_GET['plan'])) ? base64_decode($_GET['plan']) : header("Location: view-program.php");
        $stplane = base64_encode($stplan);

        $query_rsSector = $db->prepare("SELECT stid,sector FROM tbl_sectors WHERE deleted='0' and parent='0'");
        $query_rsSector->execute();
        $row_rsSector = $query_rsSector->fetch();
        $totalRows_rsSector = $query_rsSector->rowCount();

        $query_rsObjective = $db->prepare("SELECT * FROM tbl_strategic_plan_objectives ORDER BY id ASC");
        $query_rsObjective->execute();
        $row_rsObjective = $query_rsObjective->fetch();
        $totalRows_rsObjective = $query_rsObjective->rowCount();

        $query_rsDnCurrency = $db->prepare("SELECT * FROM tbl_currency WHERE active='1'");
        $query_rsDnCurrency->execute();
        $row_rsDnCurrency = $query_rsDnCurrency->fetch();
        $totalRows_rsDnCurrency = $query_rsDnCurrency->rowCount();

        $query_rsIndicator = $db->prepare("SELECT * FROM tbl_indicator");
        $query_rsIndicator->execute();
        $row_rsIndicator = $query_rsIndicator->fetch();
        $totalRows_rsIndicator = $query_rsIndicator->rowCount();

        $query_rsOutput = $db->prepare("SELECT * FROM tbl_outputs");
        $query_rsOutput->execute();
        $row_rsOutput = $query_rsOutput->fetch();
        $totalRows_rsOutput = $query_rsOutput->rowCount();

        if (isset($_GET["plan"]) && !empty($_GET["plan"])) {
            $spid = $_GET["plan"];
        }
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
?>
    <style>
        #links a {
            color: #FFFFFF;
            text-decoration: none;
        }

        hr {
            display: block;
            margin-top: 0.5em;
            margin-bottom: 0.5em;
            margin-left: auto;
            margin-right: auto;
            border-style: inset;
            border-width: 1px;
        }

        .modal-lg {
            max-width: 100% !important;
            width: 90%;
        }
    </style>
    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <i class="fa fa-columns" aria-hidden="true"></i>
                    <?php echo $pageTitle ?> PROGRAMS
                    <div class="btn-group" style="float:right">
                        <div class="btn-group" style="float:right">
                            <button onclick="history.go(-1)" class="btn bg-orange waves-effect pull-right" style="margin-right: 10px">
                                Go Back
                            </button>
                        </div>
                    </div>
                </h4>
            </div>
            <div class="row clearfix">
                <div class="block-header">
                    <?= $results; ?>
                    <input type="hidden" name="objid" id="spid" value="<?= $spid ?>">
                    <input type="hidden" name="spid" id="planid" value="<?= $stplan ?>">
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="header" style="padding-bottom:0px">
                                <div class="button-demo" style="margin-top:-15px">
                                    <span class="label bg-black" style="font-size:18px"><img src="assets/images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" /> Menu </span>
                                    <a href="view-strategic-plan-framework.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:4px"><?= $planlabel ?> Details</a>
                                    <a href="view-kra.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Key Results Area</a>
                                    <a href="view-strategic-plan-objectives.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Strategic Objectives</a>
                                    <a href="view-strategic-workplan-budget.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Targets Distribution</a>
                                    <a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Programs</a>
                                    <a href="strategic-plan-projects.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Projects</a>
                                    <a href="view-objective-performance.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Progress Report</a>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="manageItemTable">
                                    <thead>
                                        <tr>
                                            <th width="3%">#</th>
                                            <th width="36%">Program</th>
                                            <th width="13%">Budget (ksh)</th>
                                            <th width="15%">Budget Bal (ksh)</th>
                                            <th style="width:8%">Project(s)</th>
                                            <th width="10%">Start Year </th>
                                            <th width="8%">Duration </th>
                                            <th width="7%">Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- end body  -->
    <!-- Start Item more -->
    <div class="modal fade" tabindex="-1" role="dialog" id="moreInfoModal">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info" style="font-size:24px"></i> Program More Information</h4>
                </div>
                <div class="modal-body" id="moreinfo">
                </div>
                <div class="modal-footer">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Close</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Item more -->



    <!-- Start Item Delete -->
    <div class="modal fade" tabindex="-1" role="dialog" id="removeItemModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i> Delete Program</h4>
                </div>
                <div class="modal-body">
                    <div class="removeItemMessages"></div>
                    <p align="center">Are you sure you want to delete this record?</p>
                </div>
                <div class="modal-footer removeContractor NationalityFooter">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-success" id="removeItemBtn"> <i class="fa fa-check-square-o"></i> Delete</button>
                        <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Cancel</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Item Delete -->

<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>
<script type="text/javascript">
    var manageItemTable;

    $(document).ready(function() {
        var spid = $("#planid").val();
        let req = '';
        if (spid) {
            req = `general-settings/selected-items/fetch-selected-program-items?sp=${spid}`;
        } else {
            req = `general-settings/selected-items/fetch-selected-program-items`;
        }

        // manage Contractor Nationality data table
        manageItemTable = $("#manageItemTable").DataTable({
            ajax: req,
            order: [],
            'columnDefs': [{
                'targets': [7],
                'orderable': false,
            }]
        });
        // remove Contractor Nationality
    }); // document.ready fucntion

    function CallRiskAction(id) {
        $.ajax({
            type: 'post',
            url: 'callriskaction',
            data: {
                rskid: id
            },
            success: function(data) {
                $('#riskaction').html(data);
                $("#riskModal").modal({
                    backdrop: "static"
                });
            }
        });
    }

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

    $(document).ready(function() {
        // get department
        $('#projsector').on('change', function() {
            var projsectorid = $(this).val();
            if (projsectorid) {
                $.ajax({
                    type: 'POST',
                    url: 'addProjectLocation',
                    data: 'getdept=' + projsectorid,
                    success: function(html) {
                        $('#projdept').html(html);
                    }
                });
            } else {
                $('#projdept').html('<option value="">... Select Ministry First ... </option>');
            }
        });

        //get output
        $('#projdept').on('change', function() {
            var projdeptid = $(this).val();
            if (projdeptid) {
                $.ajax({
                    type: 'POST',
                    url: 'addProjectLocation',
                    data: 'getoutput=' + projdeptid,
                    success: function(html) {
                        $('.output').html(html);
                    }
                });
            } else {
                $('#projdept').html('<option value="">... Select Ministry First ... </option>');
            }
        });

        // /get ndicator
        $('#projdept').on('change', function() {
            var projdeptid = $(this).val();
            console.log(projdeptid);
            if (projdeptid) {
                $.ajax({
                    type: 'POST',
                    url: 'addProjectLocation',
                    data: 'getindicator=' + projdeptid,
                    success: function(html) {
                        $('.indicator').html(html);
                    }
                });
            } else {
                $('#projdept').html('<option value="">... Select Ministry First ... </option>');
            }
        });

        $('#progobj').on('change', function() {
            var progobjid = $(this).val();
            console.log(progobjid)
            if (progobjid) {
                $.ajax({
                    type: 'POST',
                    url: 'addProjectLocation',
                    data: 'progobj=' + progobjid,
                    success: function(html) {
                        $('#progstrategy').html(html);
                    }
                });
            } else {
                $('#progstrategy').html('<option value="">... Select Objective First ... </option>');
            }
        });
    });
</script>