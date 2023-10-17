<?php 
require('includes/head.php'); 

if ($permission) { 
    try {
        if (isset($_POST["search"])) {
            $projcode = trim($_POST["srccode"]);
            $projsector = $_POST["srcsector"];

            if (!empty(($projcode)) && empty($projsector)) {
                $query_rsProjects = $db->prepare("SELECT g.progid, g.progname, p.projcode, p.projid, p.projname, p.projinspection, s.sector FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid inner join tbl_sectors s on s.stid=g.projdept WHERE p.projcode = :projcode and p.deleted='0' and p.projstage=5");
                $query_rsProjects->execute(array(":projcode" => $projcode));
                $row_rsProjects = $query_rsProjects->fetch();
                $totalRows_rsProjects = $query_rsProjects->rowCount();
            } elseif (empty(($projcode)) && !empty($projsector)) {
                $query_rsProjects = $db->prepare("SELECT g.progid, g.progname, p.projcode, p.projid, p.projname, p.projinspection, s.sector FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid inner join tbl_sectors s on s.stid=g.projdept WHERE g.projdept = :projsector and p.deleted='0' and p.projstage=5");
                $query_rsProjects->execute(array(":projsector" => $projsector));
                $row_rsProjects = $query_rsProjects->fetch();
                $totalRows_rsProjects = $query_rsProjects->rowCount();
            } elseif (!empty(($projcode)) && !empty($projsector)) {
                $query_rsProjects = $db->prepare("SELECT g.progid, g.progname, p.projcode, p.projid, p.projname, p.projinspection, s.sector FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid inner join tbl_sectors s on s.stid=g.projdept WHERE p.projcode = :projcode and g.projdept = :projsector and p.deleted='0' and p.projstage=5");
                $query_rsProjects->execute(array(":projcode" => $projcode, ":projsector" => $projsector));
                $row_rsProjects = $query_rsProjects->fetch();
                $totalRows_rsProjects = $query_rsProjects->rowCount();
            }
        } else {
            $query_rsProjects = $db->prepare("SELECT g.progid, g.progname, p.projcode, p.projid, p.projname, p.projinspection, s.sector FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid inner join tbl_sectors s on s.stid=g.projdept WHERE p.deleted='0' and p.projstage=5");
            $query_rsProjects->execute();
            $row_rsProjects = $query_rsProjects->fetch();
            $totalRows_rsProjects = $query_rsProjects->rowCount();
        }

        $query_rsOutputs = $db->prepare("SELECT p.output as output, o.id as opid, p.indicator FROM tbl_project_details o INNER JOIN tbl_progdetails p ON p.id = o.outputid WHERE projid = :projid");
        $query_rsOutputs->execute(array(":projid" => $projid));
        $row_rsOutputs = $query_rsOutputs->fetch();
        $totalRows_rsOutputs = $query_rsOutputs->rowCount();

        $query_rsTP = $db->prepare("SELECT COUNT(projname) FROM tbl_projects WHERE deleted='0' and projplanstatus='1'");
        $query_rsTP->execute();
        $row_rsTP = $query_rsTP->fetch();

        $query_rsTPList = $db->prepare("SELECT projname, COUNT(projname) FROM tbl_projects WHERE deleted='0' and projplanstatus='1' GROUP BY projname");
        $query_rsTPList->execute();
        $row_rsTPList = $query_rsTPList->fetch();

        $query_srcSector = $db->prepare("SELECT DISTINCT projdept FROM tbl_programs g inner join tbl_projects p on p.progid=g.progid where projplanstatus='1' ORDER BY g.projsector ASC");
        $query_srcSector->execute();
        //$row_srcSector = $query_srcSector->fetch();


    } catch (PDOException $ex) {
        $result = flashMessage("An error occurred: " . $ex->getMessage());
        echo $result;
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

        @media (min-width: 1200px) {
            .modal-lg {
                width: 90%;
            }
        }
    </style>
    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                <?=$icon?>
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
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="">
                                    <thead>
                                        <tr class="bg-deep-orange">
                                            <th style="width:5%"></th>
                                            <th style="width:5%">#</th>
                                            <th style="width:10%">Project Code</th>
                                            <th style="width:50%" colspan="2">Project Name</th>
                                            <th style="width:30%" colspan="2">Project <?= $departmentlabel ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($totalRows_rsProjects > 0) {
                                            $Ocounter = 0;
                                            do {
                                                $projid = $row_rsProjects['projid'];
                                                $division = $row_rsProjects['sector'];
                                                $projcode = $row_rsProjects['projcode'];
                                                $projname = $row_rsProjects['projname'];
                                                $inspection  = $row_rsProjects['projinspection'];

                                                $query_rsMntChk = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist WHERE projid=:projid");
                                                $query_rsMntChk->execute(array(":projid" => $projid));
                                                $totalRows_rsMntChk = $query_rsMntChk->rowCount();

                                                $query_rsInspChk = $db->prepare("SELECT * FROM tbl_project_inspection_checklist WHERE projid=:projid");
                                                $query_rsInspChk->execute(array(":projid" => $projid));
                                                $totalRows_rsInspChk = $query_rsInspChk->rowCount();

                                                $query_Milestones = $db->prepare("SELECT * FROM tbl_milestone WHERE projid=:projid");
                                                $query_Milestones->execute(array(":projid" => $projid));
                                                $totalRows_Milestones = $query_Milestones->rowCount();

                                                $taskno = 0;
                                                if ($totalRows_Milestones > 0) {
                                                    while ($row_Milestones = $query_Milestones->fetch()) {
                                                        $msid = $row_Milestones["msid"];
                                                        $query_rsMilestoneTasks = $db->prepare("SELECT * FROM tbl_task WHERE msid=:msid");
                                                        $query_rsMilestoneTasks->execute(array(":msid" => $msid));
                                                        $totalRows_rsMilestoneTasks = $query_rsMilestoneTasks->rowCount();

                                                        $taskno = $taskno + $totalRows_rsMilestoneTasks;
                                                    }
                                                }

                                                if ($taskno > 0 && ($totalRows_rsMntChk == 0 || $totalRows_rsInspChk == 0)) {
                                                    $Ocounter++;
                                        ?>
                                                    <tr class="outputs" style="background-color:#eff9ca">
                                                        <td align="center" id="outputs<?php echo $outputid ?>" class="mb-0" data-toggle="collapse" data-target=".output<?php echo $outputid ?>">
                                                            <button class="btn btn-link " title="Click once to expand and Click twice to Collapse!!">
                                                                <i class="fa fa-plus-square" style="font-size:16px"></i>
                                                            </button>
                                                        </td>
                                                        <td align="center"><?= $Ocounter ?></td>
                                                        <td><?= $projcode ?></td>
                                                        <td colspan="2"><?= $projname ?> </td>
                                                        <td colspan="2"><?= $division ?></td>
                                                    </tr>
                                                    <tr class="collapse output<?php echo $outputid ?>" data-parent="outputs<?php echo $outputid ?>" style="background-color:#FF9800; color:#FFF">
                                                        <th style="width: 5%"></th>
                                                        <th style="width: 5%">#</th>
                                                        <th colspan="5" style="width: 90%">Milestone Name</th>
                                                    </tr>
                                                    <?php
                                                    $query_rsMilestones = $db->prepare("SELECT * FROM tbl_milestone WHERE projid=:projid");
                                                    $query_rsMilestones->execute(array(":projid" => $projid));
                                                    $row_rsMilestones = $query_rsMilestones->fetch();
                                                    $totalRows_rsMilestones = $query_rsMilestones->rowCount();

                                                    if ($totalRows_rsMilestones > 0) {
                                                        $mcounter = 0;
                                                        do {
                                                            $mcounter++;
                                                            $milestone = $row_rsMilestones['msid'];
                                                            $query_rsTasks = $db->prepare("SELECT *  FROM tbl_task WHERE projid = :projid and msid = :milestone");
                                                            $query_rsTasks->execute(array(":projid" => $projid, ":milestone" => $milestone));
                                                            $row_rsTasks = $query_rsTasks->fetch();
                                                            $totalRows_rsTasks = $query_rsTasks->rowCount();
                                                    ?>
                                                            <tr class="collapse output<?php echo $outputid ?>" data-parent="outputs<?php echo $outputid ?>" style="background-color:#CDDC39">
                                                                <td align="center" class="mb-0" data-toggle="collapse" data-target=".milestone<?php echo $milestone  ?>">
                                                                    <button class="btn btn-link mile_class<?php echo $outputid ?>" title="Click once to expand and Click twice to Collapse!!">
                                                                        <i class="more-less fa fa-plus-square" style="font-size:16px"></i>
                                                                    </button>
                                                                </td>
                                                                <td align="center"> <?php echo   $Ocounter . "." . $mcounter ?></td>
                                                                <td colspan="5"><?php echo $row_rsMilestones['milestone'] ?></td>
                                                            </tr>
                                                            <tr class="collapse milestone<?php echo $milestone  ?> bg-amber" data-parent="outputs<?php echo $outputid ?>">
                                                                <th style="width: 5%"></th>
                                                                <th style="width: 5%">#</th>
                                                                <th colspan="4" style="width: 80%">Task Name</th>
                                                                <th style="width: 10%">Action</th>
                                                            </tr>
                                                            <script>
                                                                $("#outputs<?php echo $outputid ?>").click(function(e) {
                                                                    e.preventDefault();
                                                                    $(".output<?php echo $outputid ?>").on('hide.bs.collapse', function() {
                                                                        $(".milestone<?php echo $milestone  ?>").collapse("hide");
                                                                        $(".mile_class<?php echo $outputid ?> ").html('<i class="fa fa-plus-square" style="font-size:16px"></i>');
                                                                    });
                                                                });
                                                            </script>
                                                            <?php
                                                            $tcounter = 0;
                                                            if ($totalRows_rsTasks > 0) {
                                                                do {
                                                                    $tcounter++;
                                                                    $taskid = $row_rsTasks['tkid'];
                                                                    $query_rsChecklist = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist WHERE taskid = :taskid");
                                                                    $query_rsChecklist->execute(array(":taskid" => $taskid));
                                                                    $row_rsChecklist = $query_rsChecklist->fetch();
                                                                    $totalRows_rsChecklist = $query_rsChecklist->rowCount();

                                                                    $query_rsInspChecklist = $db->prepare("SELECT * FROM tbl_project_inspection_checklist WHERE taskid = :taskid");
                                                                    $query_rsInspChecklist->execute(array(":taskid" => $taskid));
                                                                    $row_rsInspChecklist = $query_rsInspChecklist->fetch();
                                                                    $totalRows_rsInspChecklist = $query_rsInspChecklist->rowCount();
                                                            ?>
                                                                    <tr class="collapse milestone<?php echo $milestone  ?>" data-parent="outputs<?php echo $outputid ?>" style="background-color:#FFF">
                                                                        <td style="background-color:#FFC107"></td>
                                                                        <td align="center"><?php echo  $Ocounter . "." . $mcounter . "." . $tcounter ?></td>
                                                                        <td colspan="4"><?php echo $row_rsTasks['task'] ?></td>
                                                                        <td>
                                                                            <div class="btn-group">
                                                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                    Options <span class="caret"></span>
                                                                                </button>
                                                                                <ul class="dropdown-menu">
                                                                                    <?php
                                                                                    if ($totalRows_rsChecklist > 0) {
                                                                                    ?>
                                                                                        <li>
                                                                                            <a type="button" data-toggle="modal" data-target="#addFormModal" id="addFormModalBtn" onclick="editMonitoring(<?php echo $row_rsTasks['tkid'] ?>)">
                                                                                                <i class="fa fa-pencil-square"></i> Edit Monitoring Checklist
                                                                                            </a>
                                                                                        </li>
                                                                                        <li>
                                                                                            <a type="button" data-toggle="modal" data-target="#removeItemModal" id="#removeItemModalBtn" onclick="removeItem(<?php echo $row_rsTasks['tkid'] ?>, 3)">
                                                                                                <i class="fa fa-trash-o"></i> Remove Monitoring Checklist
                                                                                            </a>
                                                                                        </li>
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <li>
                                                                                            <a type="button" data-toggle="modal" data-target="#addFormModal" id="addFormModalBtn" onclick="addMonitoring(<?php echo $row_rsTasks['tkid'] ?>)">
                                                                                                <i class="fa fa-plus-square-o"></i> Add Monitoring Checklist
                                                                                            </a>
                                                                                        </li>

                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                    <?php
                                                                                    if ($inspection == 1) {
                                                                                        if ($totalRows_rsInspChecklist > 0) {
                                                                                    ?>
                                                                                            <li>
                                                                                                <a type="button" data-toggle="modal" data-target="#addFormModal" id="addFormModalBtn" onclick="editInspection(<?php echo $row_rsTasks['tkid'] ?>)">
                                                                                                    <i class="fa fa-pencil-square"></i> Edit Inspection Checklist
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a type="button" data-toggle="modal" data-target="#removeItemModal" id="#removeItemModalBtn" onclick="removeItem(<?php echo $row_rsTasks['tkid'] ?>, 2)">
                                                                                                    <i class="fa fa-trash-o"></i> Remove Inpection Checklist
                                                                                                </a>
                                                                                            </li>
                                                                                        <?php
                                                                                        } else {
                                                                                        ?>
                                                                                            <li>
                                                                                                <a type="button" data-toggle="modal" data-target="#addFormModal" id="addFormModalBtn" onclick="addInspection(<?php echo $row_rsTasks['tkid'] ?>)">
                                                                                                    <i class="fa fa-plus-square-o"></i> Add Inspection Checklist
                                                                                                </a>
                                                                                            </li>
                                                                                    <?php
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                </ul>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                        <?php
                                                                } while ($row_rsTasks = $query_rsTasks->fetch());
                                                            }
                                                        } while ($row_rsMilestones = $query_rsMilestones->fetch());
                                                    }
                                                }
                                            } while ($row_rsProjects = $query_rsProjects->fetch());
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- end body  -->
    <div class="modal fade" id="addFormModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form class="form-horizontal" id="submitMilestoneForm" action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color:#fff" align="center" id="addModal">
                            <div id="title"><i class="fa fa-plus"></i> Add Checklist</div>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="body" id="checklistForm">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /modal-body -->
                    <div class="modal-footer">
                        <div class="col-md-12 text-center">
                            <input type="hidden" name="newitem" id="newitem" value="new">
                            <input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
                            <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                            <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                        </div>
                    </div> <!-- /modal-footer -->
                </form> <!-- /.form -->
            </div> <!-- /modal-content -->
        </div> <!-- /modal-dailog -->
    </div>

    <!-- Start Item Delete -->
    <div class="modal fade" tabindex="-1" role="dialog" id="removeItemModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i> Delete Checklist</h4>
                </div>
                <div class="modal-body">
                    <div class="removeItemMessages"></div>
                    <p align="center">Are you sure you want to delete this checklist?</p>
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
    <!-- Start Item Delete -->

<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>
<script src="general-settings/js/fetch-selected-checklist.js"></script>
<script type="text/javascript">
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
</script>