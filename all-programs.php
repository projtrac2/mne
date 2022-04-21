<?php
$pageName = "Strategic Plans";
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');

if ($permission) {
    $pageTitle = "Independent Programs & Projects";
    try {

        $sql_ind_projects = $db->prepare("SELECT * FROM `tbl_projects` p left join `tbl_programs` g on g.progid=p.progid WHERE program_type=0 and p.deleted='0'");
        $sql_ind_projects->execute();
        $totalRows_ind_projects = $sql_ind_projects->rowCount();

        $sql_ind_programs = $db->prepare("SELECT * FROM `tbl_programs` WHERE program_type=0 ORDER BY `syear` ASC");
        $sql_ind_programs->execute();
        $totalRows_ind_programs = $sql_ind_programs->rowCount();
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
?>
    <style>
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
                        <div class="card-header">
                            <ul class="nav nav-tabs" style="font-size:14px">
                                <li class="active">
                                    <a data-toggle="tab" href="#home"><i class="fa fa-caret-square-o-down bg-deep-orange" aria-hidden="true"></i> Independent Programs &nbsp;<span class="badge bg-orange"><?= $totalRows_ind_programs ?></span></a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#menu1"><i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> Independent Projects &nbsp;<span class="badge bg-blue"><?= $totalRows_ind_projects ?></span></a>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <!-- ============================================================== -->
                            <!-- Start Page Content -->
                            <!-- ============================================================== -->
                            <div class="tab-content">
                                <div id="home" class="tab-pane fade in active">
                                    <div class="body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover" id="indepedentPrograms" style="width:100%" id="indepedentPrograms">
                                                <thead style="width:100%">
                                                    <tr class="bg-orange" style="width:100%">
                                                        <th width="3%">#</th>
                                                        <th width="40%">Program</th>
                                                        <th width="13%">Budget (ksh)</th>
                                                        <th width="15%">Budget Bal (ksh)</th>
                                                        <th width="4%">Project(s)</th>
                                                        <th width="10%">Start Year </th>
                                                        <th width="4%">Duration </th>
                                                        <th width="4%">SP Linked </th>
                                                        <th width="7%">Action</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div id="menu1" class="tab-pane fade">
                                    <div class="body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover" id="indprojects" style="width:100%">
                                                <thead>
                                                    <tr class="bg-blue">
                                                        <th width="3%">#</th>
                                                        <th width="40%">Project</th>
                                                        <th width="13%">Budget (ksh)</th>
                                                        <th width="10%">Start Year </th>
                                                        <th width="10%">Duration (day)</th>
                                                        <th width="8%">SP Linked </th>
                                                        <th width="10%">Status </th>
                                                        <th width="8%">Action</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ============================================================== -->
                            <!-- End PAge Content -->
                            <!-- ============================================================== -->
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- end body  -->

    <!-- Start Modal Add Quarterly Targets -->
    <div class="modal fade" id="quarterlyTargetsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Program Quarterly Targets</h4>
                </div>
                <div class="modal-body" style="max-height:450px; overflow:auto;">
                    <div class="div-result">
                        <form class="form-horizontal" id="quarterlyTargetsForm" action="assets/processor/padp-process" method="POST">
                            <br />
                            <div class="col-md-12" id="quarterlyTargetsBody">

                            </div>
                            <div class="modal-footer approveItemFooter">
                                <div class="col-md-12 text-center">
                                    <input type="hidden" name="indepedentProgramsquarterlytargets" id="indepedentProgramsquarterlytargets" value="1">
                                    <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                    <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                </div>
                            </div> <!-- /modal-footer -->
                        </form> <!-- /.form -->
                    </div>
                </div> <!-- /modal-body -->
            </div>
            <!-- /modal-content -->
        </div>
    </div>


    <!-- Start Modal Add Quarterly Targets -->
    <div class="modal fade" id="editquarterlyTargetsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Edit Program Quarterly Targets</h4>
                </div>
                <div class="modal-body" style="max-height:450px; overflow:auto;">
                    <div class="div-result">
                        <form class="form-horizontal" id="editquarterlyTargetsForm" action="general-settings/action/adp-edit-action.php" method="POST">
                            <br />
                            <div class="col-md-12" id="editquarterlyTargetsBody">

                            </div>
                            <div class="modal-footer approveItemFooter">
                                <div class="col-md-12 text-center">
                                    <input type="hidden" name="editindependentprogramquarterlytargets" id="editindependentprogramquarterlytargets" value="1">
                                    <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                    <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Update" />
                                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                </div>
                            </div> <!-- /modal-footer -->
                        </form> <!-- /.form -->
                    </div>
                </div> <!-- /modal-body -->
            </div>
            <!-- /modal-content -->
        </div>
    </div>


    <!-- Start Edit Modal Item approve -->
    <div class="modal fade" id="viewQTargetsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> View Program Quarterly Targets</h4>
                </div>
                <div class="modal-body" style="max-height:450px; overflow:auto;">
                    <div class="div-result">
                        <br />
                        <div class="col-md-12" id="viewQTargetsBody">

                        </div>
                        <div class="modal-footer approveItemFooter">
                            <div class="col-md-12 text-center">
                                <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Close</button>
                            </div>
                        </div> <!-- /modal-footer -->
                    </div>
                </div> <!-- /modal-body -->
            </div>
            <!-- /modal-content -->
        </div>
    </div>



    <!-- Start Item more -->
    <div class="modal fade" tabindex="-1" role="dialog" id="progmoreInfoModal">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info" style="font-size:24px"></i> Program More Information</h4>
                </div>
                <div class="modal-body" id="progmoreinfo">
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

    <!-- Start projects Item more Info -->
    <div class="modal fade" tabindex="-1" role="dialog" id="moreItemModal">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info"></i> Project More Information</h4>
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
    </div>
    <!-- End  Item more Info -->


    <!-- Start Item Delete -->
    <div class="modal fade" tabindex="-1" role="dialog" id="removeProjModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i> Delete Project</h4>
                </div>
                <div class="modal-body">
                    <div class="removeItemMessages"></div>
                    <p align="center">Are you sure you want to delete this record?</p>
                </div>
                <div class="modal-footer removeProductFooter">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-success" id="removeItemBtn"> <i class="fa fa-check-square-o"></i> Delete</button>
                        <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Cancel</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <!-- Start Modal Item approve -->
    <div class="modal fade" id="approveItemModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Approve Project</h4>
                </div>
                <div class="modal-body" style="max-height:450px; overflow:auto;">
                    <div class="div-result">
                        <form class="form-horizontal" id="approveItemForm" action="general-settings/action/project-edit-action.php" method="POST">
                            <br />
                            <div class="col-md-12" id="aproveBody"></div>
                            <div class="modal-footer approveItemFooter">
                                <div class="col-md-12 text-center">
                                    <input type="hidden" name="approveitem" id="approveitem" value="1">
                                    <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                                    <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Approve" />
                                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                </div>
                            </div> <!-- /modal-footer -->
                        </form> <!-- /.form -->
                    </div>
                </div> <!-- /modal-body -->
            </div>
            <!-- /modal-content -->
        </div>
    </div>


<?php
} else {
    $results =  restriction();
    echo $results;
}
require('includes/footer.php');
?>

<script>
    var indprojects;
    var indepedentPrograms;
    varurl = "";
    $(document).ready(function() {
        // manage Contractor Nationality data table
        indprojects = $("#indprojects").DataTable({
            ajax: "general-settings/selected-items/fetch-selected-ind-projects?type=1",
            order: [],
            'columnDefs': [{
                'targets': [7],
                'orderable': false,
            }]
        });
        // remove Contractor Nationality

        // indepedent programs data table
        indepedentPrograms = $("#indepedentPrograms").DataTable({
            ajax: "general-settings/selected-items/fetch-selected-programs?type=2",
            order: [],
            'columnDefs': [{
                'targets': [7],
                'orderable': false,
            }]
        });
    }); // document.ready fucntion

    // get the program budget/target div from db 
    function addQuarterlytargets(progid = null, adpyr = null) {
        if (progid) {
            $.ajax({
                type: "post",
                url: "general-settings/action/adp-edit-action",
                data: {
                    create_independent_qtargets_div: "create_independent_qtargets_div",
                    progid: progid,
                    adpyr: adpyr
                },
                dataType: "html",
                success: function(response) {
                    $("#quarterlyTargetsBody").html(response);
                }
            });
        }
    }

    // get the program budget/target div from db 
    function editQuarterlytargets(progid = null, adpyr = null) {
        if (progid) {
            $.ajax({
                type: "post",
                url: "general-settings/action/adp-edit-action",
                data: {
                    edit_indepedent_programs_qtargets_div: "edit_indepedent_programs_qtargets_div",
                    progid: progid,
                    adpyr: adpyr
                },
                dataType: "html",
                success: function(response) {
                    $("#editquarterlyTargetsBody").html(response);
                }
            });
        }
    }

    // submit program quarterly targets
    $("#quarterlyTargetsForm").submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        //console.log(form_data);
        $.ajax({
            type: "post",
            url: "assets/processor/padp-process",
            data: form_data,
            dataType: "json",
            success: function(response) {
                if (response) {
                    alert(response.messages);
                    $(".modal").each(function() {
                        $(this).modal("hide");
                    });
                }
                window.location.reload(true);
            }
        });
    });

    // submit editted program quarterly targets
    $("#editquarterlyTargetsForm").submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            type: "post",
            url: "assets/processor/padp-process",
            data: form_data,
            dataType: "json",
            success: function(response) {
                if (response) {
                    alert(response.messages);
                    $(".modal").each(function() {
                        $(this).modal("hide");
                    });
                }
                window.location.reload(true);
            }
        });
    });


    // view the program budget/target 
    function viewPBB(progid = null, adpyr = null) {
        if (progid) {
            $.ajax({
                type: "post",
                url: "general-settings/action/adp-edit-action",
                data: {
                    view_independent_qtargets_div: "view_independent_qtargets_div",
                    progid: progid,
                    adpyr: adpyr
                },
                dataType: "html",
                success: function(response) {
                    $("#viewQTargetsBody").html(response);
                }
            });
        }
    }
</script>


<script src="general-settings/js/fetch-adp.js"></script>
<script src="assets/custom js/approve-adp.js"></script>
