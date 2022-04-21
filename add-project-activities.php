<?php
$pageName = "Strategic Plans";
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);
$page = "view";
require('includes/head.php');
$pageTitle = "Add Project Activities";

if ($permission) {
    try {
        $query_rsTP = $db->prepare("SELECT COUNT(projname) FROM tbl_projects WHERE deleted='0' and projplanstatus='1'");
        $query_rsTP->execute();
        $row_rsTP = $query_rsTP->fetch();

        $query_rsTPList = $db->prepare("SELECT projname, COUNT(projname) FROM tbl_projects WHERE deleted='0' and projplanstatus='1' GROUP BY projname");
        $query_rsTPList->execute();
        $row_rsTPList = $query_rsTPList->fetch();

        $query_srcSector = $db->prepare("SELECT DISTINCT projdept, g.projsector FROM tbl_programs g inner join tbl_projects p on p.progid=g.progid where projplanstatus='1' ORDER BY g.projsector ASC");
        $query_srcSector->execute();

        $query_rsFSYear = $db->prepare("SELECT * FROM tbl_fiscal_year");
        $query_rsFSYear->execute();

        $query_rsStatus = $db->prepare("SELECT statusname FROM tbl_status where level=1 AND active=1");
        $query_rsStatus->execute();

        if (isset($_GET['prog']) && !empty($_GET['prog'])) {
            $progid = $_GET['prog'];
            if (isset($_POST["search"])) {
                $projcode = trim($_POST["srccode"]);
                $projsector = $_POST["srcsector"];

                if (!(empty($projcode)) && (empty($projsector))) {
                    $query_rsProjects = $db->prepare("SELECT p.*, s.sector FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND p.projcode = :projcode AND p.progid = :progid AND p.projstage = 4 GROUP BY p.projname ORDER BY p.projid DESC");
                    $query_rsProjects->execute(array(":projcode" => $projcode, ":progid" => $progid));
                    $row_rsProjects = $query_rsProjects->fetch();
                    $totalRows_rsProjects = $query_rsProjects->rowCount();
                } elseif ((empty($projcode)) && !(empty($projsector))) {
                    $query_rsProjects = $db->prepare("SELECT p.*, s.sector FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND g.projdept = :projsector AND p.progid = :progid AND p.projstage = 4 GROUP BY p.projname ORDER BY p.projid DESC");
                    $query_rsProjects->execute(array(":projsector" => $projsector, ":progid" => $progid));
                    $row_rsProjects = $query_rsProjects->fetch();
                    $totalRows_rsProjects = $query_rsProjects->rowCount();
                } elseif (!(empty($projcode)) && !(empty($projsector))) {
                    $query_rsProjects = $db->prepare("SELECT p.*, s.sector FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND g.projdept = :projsector AND p.projcode = :projcode AND p.progid = :progid AND p.projstage = 4 GROUP BY p.projname ORDER BY p.projid DESC");
                    $query_rsProjects->execute(array(":projcode" => $projcode, ":projsector" => $projsector, ":progid" => $progid));
                    $row_rsProjects = $query_rsProjects->fetch();
                    $totalRows_rsProjects = $query_rsProjects->rowCount();
                }
            } else {
                $query_rsProjects = $db->prepare("SELECT p.*, s.sector FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND p.progid='$progid' AND p.projstage = 4 GROUP BY p.projname ORDER BY p.projid DESC");
                $query_rsProjects->execute();
                $row_rsProjects = $query_rsProjects->fetch();
                $totalRows_rsProjects = $query_rsProjects->rowCount();
            }
        } else {
            if (isset($_POST["search"])) {
                $projcode = trim($_POST["srccode"]);
                $projsector = $_POST["srcsector"];

                if (!(empty($projcode)) && (empty($projsector))) {
                    $query_rsProjects = $db->prepare("SELECT p.*, s.sector FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND p.projcode = :projcode AND p.projstage = 4 GROUP BY p.projname ORDER BY p.projid DESC");
                    $query_rsProjects->execute(array(":projcode" => $projcode));
                    $row_rsProjects = $query_rsProjects->fetch();
                    $totalRows_rsProjects = $query_rsProjects->rowCount();
                } elseif ((empty($projcode)) && !(empty($projsector))) {
                    $query_rsProjects = $db->prepare("SELECT p.*, s.sector FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND g.projdept = :projsector AND p.projstage = 4 GROUP BY p.projname ORDER BY p.projid DESC");
                    $query_rsProjects->execute(array(":projsector" => $projsector));
                    $row_rsProjects = $query_rsProjects->fetch();
                    $totalRows_rsProjects = $query_rsProjects->rowCount();
                } elseif (!(empty($projcode)) && !(empty($projsector))) {
                    $query_rsProjects = $db->prepare("SELECT p.*, s.sector FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND g.projdept = :projsector AND p.projcode = :projcode AND p.projstage = 4 GROUP BY p.projname ORDER BY p.projid DESC");
                    $query_rsProjects->execute(array(":projcode" => $projcode, ":projsector" => $projsector));
                    $row_rsProjects = $query_rsProjects->fetch();
                    $totalRows_rsProjects = $query_rsProjects->rowCount();
                }
            } else {
                $query_rsProjects = $db->prepare("SELECT p.*, s.sector FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND p.projstage = 4 GROUP BY p.projname ORDER BY p.projid DESC");
                $query_rsProjects->execute();
                $row_rsProjects = $query_rsProjects->fetch();
                $totalRows_rsProjects = $query_rsProjects->rowCount();
            }
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
                        <div class="body">
                            <div class="row clearfix" style="margin-top:5px">
                                <form id="searchform" align="right" name="searchform" method="POST" action="">
                                    <div class="col-md-3">
                                        <input name="srccode" type="text" class="form-control" placeholder="Search By Project Code">
                                    </div>
                                    <div class="col-md-4">
                                        <select name="srcsector" id="srcsector" class="form-control show-tick" data-live-search="false">
                                            <option value="" selected="selected" class="selection">.... Search By <?= $departmentlabel ?> ....</option>
                                            <?php
                                            while ($row_srcSector = $query_srcSector->fetch()) {
                                                $row_deptName = $row_srcSector['projdept'];

                                                $query_rsSectorName = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid='$row_deptName'");
                                                $query_rsSectorName->execute();
                                                $row_rsSectorName = $query_rsSectorName->fetch();
                                            ?>
                                                <option value="<?php echo $row_deptName ?>"><?php echo $row_rsSectorName['sector'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">

                                    </div>
                                    <div class="col-md-3">
                                        <input name="search" type="submit" class="btn btn-primary waves-effect waves-light" id="search-form-submit" value="SEARCH" />
                                        <button type="button" class="btn bg-orange waves-effect"><a href="add-project-activities" style="color:#FFF">RESET</a></button>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example " id="manageItemTable">
                                    <thead>
                                        <tr style="background-color:#0b548f; color:#FFF">
                                            <th style="width:4%"></th>
                                            <th style="width:4%" align="center">#</th>
                                            <th style="width:10%">Project Code</th>
                                            <th style="width:50%">Project Name </th>
                                            <th style="width:24">Project Implementation Method</th>
                                            <th style="width:8%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($totalRows_rsProjects > 0) {
                                            $counter = 0;
                                            do {
                                                $counter++;
                                                $projid = $row_rsProjects['projid'];
                                                $implementation = $row_rsProjects['projcategory'];

                                                $query_rsImplementation = $db->prepare("SELECT * FROM tbl_project_implementation_method WHERE id='$implementation'");
                                                $query_rsImplementation->execute();
                                                $row_rsImplementation = $query_rsImplementation->fetch();
                                                $implType = $row_rsImplementation['method'];

                                                $query_rsOutputs = $db->prepare("SELECT g.output as output, p.id as opid, g.indicator FROM tbl_project_details p INNER JOIN tbl_progdetails g ON g.id = p.outputid WHERE p.projid='$projid' ");
                                                $query_rsOutputs->execute();
                                                $row_rsOutputs = $query_rsOutputs->fetch();
                                                $totalRows_rsOutputs = $query_rsOutputs->rowCount();

                                                // check if has milestones 
                                                $query_rsMilestones = $db->prepare("SELECT * FROM tbl_milestone WHERE projid = :projid");
                                                $query_rsMilestones->execute(array(":projid" => $projid));
                                                $totalRows_rsMilestones = $query_rsMilestones->rowCount();
                                        ?>
                                                <tr class="projects" style="background-color:#eff9ca">
                                                    <td align="center" class="mb-0" id="projects<?php echo $projid ?>" data-toggle="collapse" data-target=".project<?php echo $projid ?>" style="background-color:#0b548f">
                                                        <button class="btn btn-link " title="Click once to expand and Click once to Collapse!!" style="color:#FFF">
                                                            <i class="fa fa-plus-square" style="font-size:16px"></i>
                                                        </button>
                                                    </td>
                                                    <td align="center"><?= $counter ?></td>
                                                    <td><?php echo $row_rsProjects['projcode'] ?></td>
                                                    <td><?php echo $row_rsProjects['projname'] ?></td>
                                                    <td><?php echo $implType ?></td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Options <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a type="button" data-toggle="modal" data-target="#moreModal" id="moreModalBtn" onclick="more(<?php echo $row_rsProjects['projid'] ?>, 1)">
                                                                        <i class="fa fa-file-text"></i> View More
                                                                    </a>
                                                                    <?php
                                                                    if ($totalRows_rsMilestones > 0) {
                                                                    ?>
                                                                        <a type="button" data-toggle="modal" data-target="#finishAddItemModal" id="#finishAddItemModalBtn" onclick="javascript:endaddingactivities(<?php echo $row_rsProjects['projid'] ?>)" title="Click here to finish adding activities"><i class="fa fa-hand-paper-o"></i> Finish Add</a>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                                if ($totalRows_rsOutputs > 0) {
                                                ?>
                                                    <tr class="collapse project<?php echo $projid ?>" style="background-color:#2d8bd6; color:#FFF">
                                                        <th width="5%"></th>
                                                        <th width="5%">#</th>
                                                        <th colspan="2" width="40%">Output Name</th>
                                                        <th width="40%">Indicator</th>
                                                        <th width="10%">Action</th>
                                                    </tr>
                                                    <?php
                                                    $Ocounter = 0;
                                                    do {
                                                        $outputid = $row_rsOutputs['opid'];
                                                        $indid = $row_rsOutputs['indicator'];
                                                        $Ocounter++;
                                                        $query_rsMilestones = $db->prepare("SELECT * FROM tbl_milestone WHERE projid='$projid' and outputid ='$outputid' ORDER BY sdate");
                                                        $query_rsMilestones->execute();
                                                        $row_rsMilestones = $query_rsMilestones->fetch();
                                                        $totalRows_rsMilestones = $query_rsMilestones->rowCount();

                                                        //get indicator
                                                        $query_rsIndicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid='$indid'");
                                                        $query_rsIndicator->execute();
                                                        $row_rsIndicator = $query_rsIndicator->fetch();
                                                        $totalRows_rsIndicator = $query_rsIndicator->rowCount();


                                                        $unit = $row_rsIndicator['indicator_unit'];
                                                        $indicator_name = $row_rsIndicator['indicator_name'];

                                                        $query_Indicator = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id =:unit ");
                                                        $query_Indicator->execute(array(":unit" => $unit));
                                                        $row = $query_Indicator->fetch();
                                                        $op_unitofmeasure = $row['unit'];

                                                    ?>
                                                        <tr class="collapse project<?php echo $projid ?>" style="background-color:#dbdbdb">
                                                            <td align="center" class="mb-0" id="outputs<?php echo $outputid ?>" data-toggle="collapse" data-parent="#accordion<?= $projid ?>" data-target=".output<?php echo $outputid ?>" style="background-color:#2d8bd6">
                                                                <button class="btn btn-link output_class<?php echo $projid ?>" title="Click once to expand and Click once to Collapse!!" style="color:#FFF"> <i class="more-less fa fa-plus-square" style="font-size:16px"></i>
                                                                </button>
                                                            </td>
                                                            <td align="center"> <?php echo $counter . "." . $Ocounter ?></td>
                                                            <td colspan="2"><?php echo $row_rsOutputs['output'] ?></td>
                                                            <td><?php echo $op_unitofmeasure . " of " . $indicator_name ?></td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        Options <span class="caret"></span>
                                                                    </button>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <a type="button" data-toggle="modal" data-target="#addFormModal" id="addFormModalBtn" onclick="getMilestoneForm(<?php echo $projid . ',' . $outputid ?>)">
                                                                                <i class="fa fa-plus-square-o"></i> Add Milestone
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        if ($totalRows_rsMilestones > 0) {
                                                        ?>
                                                            <tr class="collapse output<?php echo $outputid ?>" style="background-color:#03affc; color:#FFF">
                                                                <th></th>
                                                                <th>#</th>
                                                                <th colspan="2">Milestone Name</th>
                                                                <th>Successor/s</th>
                                                                <th>Action</th>
                                                            </tr>
                                                            <?php
                                                            $mcounter = 0;
                                                            do {
                                                                $mcounter++;
                                                                $milestone = $row_rsMilestones['msid'];
                                                                $parentid =  $row_rsMilestones['parent'];
                                                                $parent = "";

                                                                $query_rsMilestoneParent = $db->prepare("SELECT * FROM tbl_milestone WHERE parent='$milestone'");
                                                                $query_rsMilestoneParent->execute();
                                                                $row_rsMilestoneParent = $query_rsMilestoneParent->fetch();
                                                                $totalRows_rsMilestoneParent = $query_rsMilestoneParent->rowCount();


                                                                $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE projid='$projid' and msid='$milestone' ORDER BY sdate");
                                                                $query_rsTasks->execute();
                                                                $row_rsTasks = $query_rsTasks->fetch();
                                                                $totalRows_rsTasks = $query_rsTasks->rowCount();
                                                            ?>
                                                                <tr class="collapse output<?php echo $outputid ?>" style="background-color:#f5f5f5">
                                                                    <td align="center" class="mb-0" data-toggle="collapse" data-parent="#accordion<?= $projid ?>" data-target=".milestone<?php echo $milestone ?>" style="background-color:#03affc">
                                                                        <button class="btn btn-link mile_class<?php echo $outputid ?>" title="Click once to expand and Click once to Collapse!!" style="color:#FFF"> <i class="more-less fa fa-plus-square" style="font-size:16px"></i>
                                                                        </button>
                                                                    </td>
                                                                    <td align="center"> <?php echo $counter . "." . $Ocounter . "." . $mcounter ?></td>
                                                                    <td colspan="2"><?php echo $row_rsMilestones['milestone'] ?></td>
                                                                    <td><?= $totalRows_rsMilestoneParent ?></td>
                                                                    <td>
                                                                        <div class="btn-group">
                                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                Options <span class="caret"></span>
                                                                            </button>
                                                                            <ul class="dropdown-menu">
                                                                                <li>
                                                                                    <a type="button" data-toggle="modal" data-target="#moreModal" id="moreModalBtn" onclick="more(<?php echo $row_rsMilestones['msid'] ?>, 2)">
                                                                                        <i class="fa fa-file-text"></i> View More
                                                                                    </a>
                                                                                </li>
                                                                                <?php
                                                                                if ($totalRows_rsTasks > 0) {
                                                                                } else {
                                                                                ?>
                                                                                    <li>
                                                                                        <a type="button" data-toggle="modal" data-target="#addFormModal" id="addFormModalBtn" onclick="getMilestoneEditForm(<?php echo $row_rsMilestones['msid'] ?>)">
                                                                                            <i class="fa fa-pencil-square"></i> </i> Edit Milestone
                                                                                        </a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a type="button" data-toggle="modal" data-target="#removeItemModal" id="#removeItemModalBtn" onclick="removeItem(<?php echo $row_rsMilestones['msid'] ?>, 2)">
                                                                                            <i class="fa fa-trash-o"></i> Remove Milestone
                                                                                        </a>
                                                                                    </li>
                                                                                <?php
                                                                                }
                                                                                ?>

                                                                                <li>
                                                                                    <a type="button" data-toggle="modal" data-target="#addFormModal" id="addFormModalBtn" onclick="getAddTaskForm(<?php echo $row_rsMilestones['msid'] ?>)">
                                                                                        <i class="fa fa-plus-square-o"></i> Add Task
                                                                                    </a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                if ($totalRows_rsTasks > 0) {
                                                                ?>
                                                                    <tr class="collapse milestone<?php echo $milestone ?>" style="background-color:#7fd1fa; color:#FFF">
                                                                        <th></th>
                                                                        <th>#</th>
                                                                        <th COLSPAN=2>Task Name</th>
                                                                        <th>Successor/s</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                    <script>
                                                                        $("#projects<?php echo $projid ?>").click(function(e) {
                                                                            e.preventDefault();
                                                                            $(".project<?php echo $projid ?>").on('hide.bs.collapse', function() {
                                                                                $(".milestone<?php echo $milestone  ?>").collapse("hide");
                                                                                $(".output<?php echo $outputid ?>").collapse("hide");
                                                                                $(".output_class<?php echo $projid ?> ").html('<i class="fa fa-plus-square" style="font-size:16px"></i>');
                                                                                $(".mile_class<?php echo $outputid ?> ").html('<i class="fa fa-plus-square" style="font-size:16px"></i>');
                                                                            });
                                                                        });
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
                                                                    do {

                                                                        $parentid = $row_rsTasks['parenttask'];
                                                                        $tkid = $row_rsTasks['tkid'];
                                                                        $query_rsTaskid = $db->prepare("SELECT * FROM tbl_task WHERE parenttask='$tkid'");
                                                                        $query_rsTaskid->execute();
                                                                        $row_rsTaskid = $query_rsTaskid->fetch();
                                                                        $totalRows_rstaskParent = $query_rsTaskid->rowCount();
                                                                        $tcounter++;
                                                                    ?>
                                                                        <tr class="collapse milestone<?php echo $milestone ?>" style="background-color:#FFFFFF">
                                                                            <td style="background-color:#7fd1fa"></td>
                                                                            <td align="center"><?php echo $counter . "." . $Ocounter . "." . $mcounter . "." . $tcounter ?></td>
                                                                            <td COLSPAN=2><?php echo $row_rsTasks['task'] ?></td>
                                                                            <td><?php echo $totalRows_rstaskParent ?></td>
                                                                            <td>
                                                                                <div class="btn-group">
                                                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                        Options <span class="caret"></span>
                                                                                    </button>
                                                                                    <ul class="dropdown-menu">
                                                                                        <li>
                                                                                            <a type="button" data-toggle="modal" data-target="#moreModal" id="moreModalBtn" onclick="more(<?php echo $row_rsTasks['tkid'] ?>,3)">
                                                                                                <i class="fa fa-file-text"></i> View More
                                                                                            </a>
                                                                                        </li>
                                                                                        <li>
                                                                                            <a type="button" data-toggle="modal" data-target="#addFormModal" id="addFormModalBtn" onclick="getTaskEditForm(<?php echo $row_rsTasks['tkid'] ?>)">
                                                                                                <i class="fa fa-pencil-square"></i> Edit Task
                                                                                            </a>
                                                                                        </li>
                                                                                        <li><a type="button" data-toggle="modal" data-target="#removeItemModal" id="#removeItemModalBtn" onclick="removeItem(<?php echo $row_rsTasks['tkid'] ?>,3)">
                                                                                                <i class="fa fa-trash-o"></i> Remove Task
                                                                                            </a>
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    <?php
                                                                    } while ($row_rsTasks = $query_rsTasks->fetch());
                                                                } else {
                                                                    ?>
                                                                    <tr class="collapse milestone<?php echo $milestone ?>" style="background-color:#FFC107; color:#FFF">
                                                                        <td width="5%"></td>
                                                                        <td colspan="5">Sorry this Milestone does not have Task/s!!!</td>
                                                                    </tr>
                                                            <?php
                                                                }
                                                            } while ($row_rsMilestones = $query_rsMilestones->fetch());
                                                        } else {
                                                            ?>
                                                            <tr class="collapse output<?php echo $outputid ?>" style="background-color:#FFC107; color:#FFF">
                                                                <td width="5%"></td>
                                                                <td colspan="5">Sorry this Output does not have Milestone/s!!!</td>
                                                            </tr>
                                            <?php
                                                        }
                                                    } while ($row_rsOutputs = $query_rsOutputs->fetch());
                                                } else {
                                                }
                                            } while ($row_rsProjects = $query_rsProjects->fetch());
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="7">No projects Approved Currently</td>
                                            </tr>
                                        <?php
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
<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>
<script src="general-settings/js/fetch-selected-project-milestone-task.js"></script>

<script src="projinfolive.js"></script>
<script type="text/javascript">
    function CallRiskAction(id) {
        $.ajax({
            type: 'post',
            url: 'callriskaction.php',
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