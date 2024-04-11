<?php
$decode_stplanid = (isset($_GET['plan']) && !empty($_GET["plan"])) ? base64_decode($_GET['plan']) : "";
$stplanid_array = explode("strplan1", $decode_stplanid);
$spid = $stplanid_array[1];
$stplan = $stplanid_array[1];
$stplane = $_GET['plan'];
require('includes/head.php');
if ($permission) {
    try {
        $sql = $db->prepare("SELECT * FROM `tbl_programs` WHERE program_type=1 AND strategic_plan =:spid ORDER BY `syear`,`progid` ASC");
        $sql->execute(array(":spid" => $spid));
        $rows_count = $sql->rowCount();
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
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
                                    <a href="view-strategic-plan-framework.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:4px"><?= $planlabel ?> Details</a>
                                    <a href="view-kra.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Key Results Area</a>
                                    <a href="view-strategic-plan-objectives.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Strategic Objectives</a>
                                    <!-- <a href="portfolios.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Portfolios</a> -->
                                    <a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Programs</a>
                                    <a href="strategic-plan-projects.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px"><?= $planlabel ?> Projects</a>
                                    <a href="strategic-plan-implementation-matrix.php?plan=<?php echo $stplane; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Implementation Matrix</a>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="card-header">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="btn-group" style="float:right">
                                            <div class="btn-group" style="float:right">
                                                <a href="add-program.php?program_type=1" class="btn btn-success">New Program </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
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
                                            <tbody>
                                                <?php
                                                if ($rows_count > 0) {
                                                    $sn = 0;
                                                    while ($row_rsProgram = $sql->fetch()) {
                                                        $itemId = $row_rsProgram['progid'];
                                                        $spid = $row_rsProgram['strategic_plan'];
                                                        $project_department = $row_rsProgram['projsector'];
                                                        $project_section = $row_rsProgram['projdept'];
                                                        $project_directorate = $row_rsProgram['directorate'];
                                                        $created_by = $row_rsProgram['createdby'];
                                                        $progid_hashed = base64_encode("progid54321{$itemId}");


                                                        if ($spid == NULL) {
                                                            $progttype = 'Independent';
                                                        } else {
                                                            $progttype = 'Strategic Plan';
                                                        }

                                                        $progname =  $row_rsProgram['progname'];
                                                        $projduration = $row_rsProgram['years'] . " Years";
                                                        $projsyear = $row_rsProgram['syear'];

                                                        //get financial years
                                                        $query_rsYear =  $db->prepare("SELECT id, year FROM tbl_fiscal_year WHERE yr='$projsyear'");
                                                        $query_rsYear->execute();
                                                        $row_rsYear = $query_rsYear->fetch();
                                                        $totalRows_rsYear = $query_rsYear->rowCount();
                                                        $projsyear = $row_rsYear['year'];

                                                        //fetch budget
                                                        $query_rsBudget =  $db->prepare("SELECT SUM(budget) as budget FROM tbl_progdetails WHERE progid='$itemId'");
                                                        $query_rsBudget->execute();
                                                        $row_rsBudget = $query_rsBudget->fetch();
                                                        $totalRows_rsBudget = $query_rsBudget->rowCount();
                                                        $progbudget = number_format($row_rsBudget['budget'], 2);

                                                        //get project department
                                                        $progdepart = $row_rsProgram['projdept'];
                                                        $query_rsDepart = $db->prepare("SELECT stid,sector FROM tbl_sectors WHERE stid='$progdepart' ");
                                                        $query_rsDepart->execute();
                                                        $row_rsDepart = $query_rsDepart->fetch();
                                                        $dept = $row_rsDepart['sector'];

                                                        //get total projects
                                                        $query_projsbudget = $db->prepare("SELECT COUNT(*) as projectscount, SUM(projcost) as budget FROM tbl_projects WHERE progid = '$itemId'");
                                                        $query_projsbudget->execute();
                                                        $row_projsbudget = $query_projsbudget->fetch();
                                                        $count_projsbudget = $query_projsbudget->rowCount();

                                                        $projsbudget = ($count_projsbudget > 0) ? $row_projsbudget['budget'] : 0;
                                                        $projectscount = ($count_projsbudget > 0) ? $row_projsbudget['projectscount'] : 0;
                                                        $progbudgetbal = number_format(($row_rsBudget['budget'] - $projsbudget), 2);


                                                        $query_projs =  $db->prepare("SELECT projid FROM tbl_projects WHERE  progid='$itemId'");
                                                        $query_projs->execute();
                                                        $totalRows_projs = $query_projs->rowCount();


                                                        $projectscount = "";
                                                        if ($totalRows_projs > 0) {
                                                            $projectscount = '<a href="view-project.php?prg=' . $progid_hashed . '"><span class="badge bg-purple">' . $totalRows_projs . '</span></a>';
                                                        } else {
                                                            $projectscount = '<a href="#"><span class="badge bg-purple">' . $totalRows_projs . '</span></a>';
                                                        }


                                                        $filter_department = view_record($project_department, $project_section, $project_directorate);
                                                        if ($filter_department) {
                                                            $sn++;
                                                ?>
                                                            <tr>
                                                                <td><?= $sn ?></td>
                                                                <td><?= $progname ?></td>
                                                                <td><?= $progbudget ?></td>
                                                                <td><?= $progbudgetbal ?></td>
                                                                <td><?= $projectscount ?></td>
                                                                <td><?= $projsyear ?></td>
                                                                <td><?= $projduration ?></td>
                                                                <td>
                                                                    <!-- Single button -->
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Options <span class="caret"></span>
                                                                        </button>
                                                                        <ul class="dropdown-menu">
                                                                            <li>
                                                                                <a type="button" data-toggle="modal" data-target="#moreInfoModal" id="moreInfoModalBtn" onclick="program_info(<?= $itemId ?>)">
                                                                                    <i class="glyphicon glyphicon-file"></i> More Info</a>
                                                                            </li>
                                                                            <?php
                                                                            if (in_array("create", $page_actions)) {
                                                                            ?>
                                                                                <li><a type="button" id="addproject" href="add-project.php?progid=<?= $progid_hashed ?>"> <i class="fa fa-plus-square"></i> Add Project</a></li>
                                                                                <?php
                                                                            }
                                                                            if ($totalRows_projs == 0) {
                                                                                if (in_array("update", $page_actions)) {
                                                                                ?>
                                                                                    <li><a type="button" data-toggle="modal" id="editprogram" href="edit-program?progid=<?= $progid_hashed ?>"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
                                                                                <?php
                                                                                }
                                                                                if (in_array("delete", $page_actions)) {
                                                                                ?>
                                                                                    <li><a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="removeItem(<?= $itemId ?>)"> <i class="glyphicon glyphicon-trash"></i> Delete</a></li>
                                                                            <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                <?php
                                                        }
                                                    } // /while

                                                } // if num_rows
                                                ?>
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
    <!-- Start Item more -->
    <div class="modal fade" tabindex="-1" role="dialog" id="moreInfoModal">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info" style="font-size:24px"></i> Program More Information</h4>
                </div>
                <div class="modal-body" id="progmoreinfo">
                </div>
                <div class="modal-footer">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Close</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Item more -->

<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>
<script src="assets/js/programs/view-programs.js"></script>