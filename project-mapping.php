<?php
$pageName = "Strategic Plans";
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
$pageTitle = "Mapping";

if ($permission) {
    try {
        $query_rsMap = $db->prepare("SELECT * FROM tbl_project_mapping m INNER JOIN  tbl_projects p  ON  p.projid = m.projid WHERE p.projmapping = 1 and p.mapped = 0 ");
        $query_rsMap->execute();
        $row_rsMap = $query_rsMap->fetch();
        $map_rsMap = $query_rsMap->rowCount();

        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projmapping=1 and projstage = 3");
        $query_rsProjects->execute();
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();
    } catch (PDOException $ex) {
        $result = flashMessage("An error occurred: " . $ex->getMessage());
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

        .bootstrap-select .dropdown-menu {
            margin: 15px 0 0;
            padding: 15px;
        }
    </style>
    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <i class="fa fa-list" aria-hidden="true"></i>
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
                                    <a data-toggle="tab" href="#home">
                                        <i class="fa fa-caret-square-o-down bg-deep-orange" aria-hidden="true"></i> Assign Team &nbsp;
                                    </a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#menu1">
                                        <i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> Map Project &nbsp;
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="tab-content">
                                <div id="home" class="tab-pane fade in active">
                                    <div class="body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover  " id="mapping_table1">
                                                <thead>
                                                    <tr class="bg-orange">
                                                        <th style="width:3%"></th>
                                                        <th style="width:7%">#</th>
                                                        <th style="width:10%">Code</th>
                                                        <th style="width:60%" colspan="4">Project </th>
                                                        <th style="width:15">Implementation Method </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if ($totalRows_rsProjects > 0) {
                                                        $counter = 0;
                                                        do { //projects loops
                                                            $projid = $row_rsProjects['projid'];

                                                            // members 
                                                            $query_rsMembers = $db->prepare("SELECT *  FROM tbl_projmembers WHERE projid=:projid");
                                                            $query_rsMembers->execute(array(":projid" => $projid));
                                                            $row_rsMembers = $query_rsMembers->fetch();
                                                            $totalRows_rsMembers = $query_rsMembers->rowCount();

                                                            // check if project has locations  
                                                            $query_rsdissegragations = $db->prepare("SELECT * FROM tbl_output_disaggregation WHERE projid=:projid");
                                                            $result = $query_rsdissegragations->execute(array(":projid" => $projid));
                                                            $row_rsdissegragations = $query_rsdissegragations->fetch();
                                                            $totalRows_rsdissegragations = $query_rsdissegragations->rowCount();

                                                            if ($totalRows_rsMembers > 0) {
                                                                $counter++;
                                                                $implementation = $row_rsProjects['projcategory'];
                                                                $query_rsImplementation = $db->prepare("SELECT * FROM tbl_project_implementation_method WHERE id='$implementation' ");
                                                                $query_rsImplementation->execute();
                                                                $row_rsImplementation = $query_rsImplementation->fetch();
                                                                $implType = $row_rsImplementation['method'];

                                                                $query_rsOutputs = $db->prepare("SELECT p.output as  output, o.id as opid, p.indicator, mapping_type FROM tbl_project_details o INNER JOIN tbl_progdetails p ON p.id = o.outputid WHERE projid='$projid' ");
                                                                $query_rsOutputs->execute();
                                                                $row_rsOutputs = $query_rsOutputs->fetch();
                                                                $totalRows_rsOutputs = $query_rsOutputs->rowCount();
                                                                if ($totalRows_rsOutputs > 0) {
                                                    ?>
                                                                    <tr class="projects details-control" style="background-color:#eff9ca">
                                                                        <td align="center" class="mb-0 output_class<?php echo $projid ?>" id="projects<?php echo $projid ?>" data-toggle="collapse" data-target=".project<?php echo $projid ?>">
                                                                            <button class="btn btn-link " title="Click to expand and Click to Collapse!!">
                                                                                <i class="fa fa-plus-square" style="font-size:16px"></i>
                                                                            </button>
                                                                        </td>
                                                                        <td align="center"><?= $counter ?></td>
                                                                        <td><?php echo $row_rsProjects['projcode'] ?> <?= $projid ?></td>
                                                                        <td colspan="4"><?php echo $row_rsProjects['projname'] ?></td>
                                                                        <td><?php echo $implType ?></td>
                                                                    </tr>
                                                                    <tr class="collapse project<?php echo $projid ?> " style="background-color:#42b6f5; color:#FFF">
                                                                        <th style="width:3%">#</th>
                                                                        <th style="width:40%" colspan="3">Output Name</th>
                                                                        <th style="width:40%" colspan="2">Indicator</th>
                                                                        <th style="width:10%">Mapping Type</th>
                                                                        <th style="width:7%">Action</th>
                                                                    </tr>
                                                                    <?php
                                                                    $Ocounter = 0;
                                                                    do {
                                                                        $outputid = $row_rsOutputs['opid'];
                                                                        $indid = $row_rsOutputs['indicator'];
                                                                        $projwaypoints = $row_rsOutputs['mapping_type'];

                                                                        $query_rs_locations = $db->prepare("SELECT * FROM tbl_project_mapping WHERE  outputid='$outputid' AND projid='$projid'");
                                                                        $query_rs_locations->execute();
                                                                        $row_rs_locations = $query_rs_locations->fetch();
                                                                        $total_rs_locations = $query_rs_locations->rowCount();

                                                                        //get indicator
                                                                        $query_rsIndicator = $db->prepare("SELECT *  FROM tbl_indicator WHERE indid='$indid' ");
                                                                        $query_rsIndicator->execute();
                                                                        $row_rsIndicator = $query_rsIndicator->fetch();
                                                                        $totalRows_rsIndicator = $query_rsIndicator->rowCount();

                                                                        //get indicator
                                                                        $query_rsMapping = $db->prepare("SELECT *  FROM tbl_map_type WHERE id='$projwaypoints' ");
                                                                        $query_rsMapping->execute();
                                                                        $row_rsMapping = $query_rsMapping->fetch();
                                                                        $totalRows_rsMapping = $query_rsMapping->rowCount();


                                                                        $unit = $row_rsIndicator['indicator_unit'];
                                                                        $calcid =  $row_rsIndicator['indicator_calculation_method'];
                                                                        $dissagragated =  $row_rsIndicator['indicator_disaggregation'];

                                                                        $query_Indicator = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id =:unit ");
                                                                        $query_Indicator->execute(array(":unit" => $unit));
                                                                        $row = $query_Indicator->fetch();
                                                                        $cont_nit = $query_Indicator->rowCount();
                                                                        $unit = $cont_nit > 0 ? $row['unit'] : "";
                                                                        $outputid = $row_rsOutputs['opid'];
                                                                        $indid = $row_rsOutputs['indicator'];
                                                                        $Ocounter++;

                                                                        //get indicator
                                                                        $query_rsIndicator = $db->prepare("SELECT *  FROM tbl_indicator WHERE indid='$indid' ");
                                                                        $query_rsIndicator->execute();
                                                                        $row_rsIndicator = $query_rsIndicator->fetch();
                                                                        $totalRows_rsIndicator = $query_rsIndicator->rowCount();


                                                                    ?>
                                                                        <tr class="collapse project<?php echo $projid ?>" style="background-color:#e9e9e9">
                                                                            <td align="center"> <?php echo $counter . "." . $Ocounter ?></td>
                                                                            <td colspan="3"><?php echo $row_rsOutputs['output'] ?></td>
                                                                            <td colspan="2"><?php echo $row_rsIndicator['indicator_name'] ?></td>
                                                                            <td><?= $row_rsMapping['type'] ?></td>
                                                                            <td>
                                                                                <div class="btn-group">
                                                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                        Options <span class="caret"></span>
                                                                                    </button>
                                                                                    <ul class="dropdown-menu">
                                                                                        <?php
                                                                                        if ($total_rs_locations > 0) {
                                                                                            if ($file_rights->edit) {
                                                                                        ?>
                                                                                                <li>
                                                                                                    <a type="button" data-toggle="modal" data-target="#addFormModal" id="addFormModalBtn" onclick="edit(<?php echo $projid . ',' . $outputid . ',' . $dissagragated ?>)">
                                                                                                        <i class="fa fa-pencil-square"></i> </i> Edit
                                                                                                    </a>
                                                                                                </li>
                                                                                            <?php
                                                                                            }
                                                                                            ?>
                                                                                            <li>
                                                                                                <a type="button" data-toggle="modal" data-target="#moreModal" id="moreModalBtn" onclick="more(<?php echo $projid . ',' . $outputid . ',' . $dissagragated ?>)">
                                                                                                    <i class="fa fa-file-text"></i> View
                                                                                                </a>
                                                                                            </li>
                                                                                            <?php
                                                                                        } else {
                                                                                            if ($file_rights->edit) {
                                                                                            ?>
                                                                                                <li>
                                                                                                    <a type="button" data-toggle="modal" data-target="#addFormModal" id="addFormModalBtn" onclick="add(<?php echo $projid . ',' . $outputid  . ',' . $dissagragated ?>)">
                                                                                                        <i class="fa fa-pencil-square"></i> </i> Assign
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
                                                                    } while ($row_rsOutputs = $query_rsOutputs->fetch());
                                                                } else {
                                                                    ?>
                                                                    <!-- <td>Outputs not available</td> -->
                                                        <?php
                                                                }
                                                            }
                                                        } while ($row_rsProjects = $query_rsProjects->fetch());
                                                    } else {
                                                        ?>
                                                        <tr>
                                                            <td colspan="8">No projects Approved Currently</td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div id="menu1" class="tab-pane fade">
                                    <div class="body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover  " id="mapping_table">
                                                <thead>
                                                    <tr style="background-color:#0b548f; color:#FFF">
                                                        <th style="width:4%" align="center">#</th>
                                                        <th style="width:10%">Project Code</th>
                                                        <th style="width:38%">Project Name </th>
                                                        <th style="width:10%">Mapping Type </th>
                                                        <th style="width:10%">Location </th>
                                                        <th style="width:9%">Status </th>
                                                        <th style="width:10%">Mapping Date </th>
                                                        <th style="width:9%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php
                                                    if ($map_rsMap > 0) {
                                                        $counter = 0;
                                                        do {
                                                            $counter++;
                                                            $map_id = $row_rsMap['id'];
                                                            $stid = $row_rsMap['stid'];
                                                            $projid = $row_rsMap['projid'];
                                                            $responsible = $row_rsMap['responsible'];
                                                            $mapDate = date_create($row_rsMap['mapping_date']);
                                                            $progid = $row_rsMap['progid'];
                                                            $outputid = $row_rsMap['outputid'];
                                                            $members = explode(',', $row_rsMap['ptid']);

                                                            //get mapping type 
                                                            $query_rsOutput =  $db->prepare("SELECT * FROM tbl_project_details WHERE id=:outputid");
                                                            $query_rsOutput->execute(array(":outputid" => $outputid));
                                                            $row_rsOutput = $query_rsOutput->fetch();
                                                            $totalRows_rsOutput = $query_rsOutput->rowCount();
                                                            $mapping_type = $row_rsOutput['mapping_type'];
                                                            $indicator = $row_rsOutput['indicator'];

                                                            $query_rsIndicator =  $db->prepare("SELECT indicator_disaggregation FROM tbl_indicator WHERE indid=:indicator");
                                                            $query_rsIndicator->execute(array(":indicator" => $indicator));
                                                            $row_rsIndicator = $query_rsIndicator->fetch();
                                                            $totalRows_rsIndicator = $query_rsIndicator->rowCount();
                                                            $disaggregation = $row_rsIndicator['indicator_disaggregation'];

                                                            //get mapping type 
                                                            $query_rsMapType =  $db->prepare("SELECT id, type FROM tbl_map_type WHERE id=:map");
                                                            $query_rsMapType->execute(array(":map" => $mapping_type));
                                                            $row_rsMapType = $query_rsMapType->fetch();
                                                            $totalRows_rsMapType = $query_rsMapType->rowCount();
                                                            $map = $row_rsMapType['type'];

                                                            // get the state location 
                                                            $query_rsForest =  $db->prepare("SELECT id, state FROM tbl_state WHERE id=:projstate");
                                                            $query_rsForest->execute(array(":projstate" => $stid));
                                                            $row_rsForest = $query_rsForest->fetch();
                                                            $totalRows_rsForest = $query_rsForest->rowCount();
                                                            $state = $row_rsForest['state'];

                                                            // $counter++;
                                                            // $data = "Planning,";
                                                            // $hash = base64_encode($data .  $projid);
                                                            // $hash_stid = base64_encode($data .  $stid);

                                                            $query_rsMapped = $db->prepare("SELECT * FROM tbl_markers WHERE mapid=:map_id");
                                                            $query_rsMapped->execute(array(":map_id" => $map_id));
                                                            $row_rsMapped = $query_rsMapped->fetch();
                                                            $totalRows_rsMapped = $query_rsMapped->rowCount();

                                                            // $totalRows_rsMapped = "";
                                                            $status = '';
                                                            if ($totalRows_rsMapped > 0) {
                                                                $status =  '<span class="badge bg-green" style="margin-bottom:2px">Mapped</span> <br />';
                                                            } else {
                                                                $mapping_date = $row_rsMap['mapping_date'];
                                                                $today = date("Y-m-d");
                                                                if ($today <= $mapping_date) {
                                                                    $status =  '<span class="badge bg-blue" style="margin-bottom:2px">Pending</span> <br />';
                                                                } else if ($today > $mapping_date) {
                                                                    $status =  '<span class="badge bg-deep-orange" style="margin-bottom:2px">Behind Schedule</span> <br />';
                                                                }
                                                            }
                                                            // if(in_array($ptid, $members)){
                                                    ?>
                                                            <tr style="background-color:#eff9ca">
                                                                <td align="center"><?= $counter ?></td>
                                                                <td><?php echo $row_rsMap['projcode'] ?></td>
                                                                <td><?php echo $row_rsMap['projname'] ?></td>
                                                                <td><?php echo $map ?></td>
                                                                <td><?php echo $state ?></td>
                                                                <td><?php echo $status ?> </td>
                                                                <td><?php echo date_format($mapDate, 'd M Y') ?></td>
                                                                <td>
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Options <span class="caret"></span>
                                                                        </button>
                                                                        <ul class="dropdown-menu">
                                                                            <li>
                                                                                <a type="button" data-toggle="modal" data-target="#moreModal" id="moreModalBtn" onclick="more_info(<?php echo $map_id ?>)">
                                                                                    <i class="fa fa-file-text"></i> View
                                                                                </a>
                                                                            </li>
                                                                            <?php
                                                                            if ($totalRows_rsMapped > 0) {
                                                                            ?>
                                                                                <li>
                                                                                    <a type="button" data-toggle="modal" data-target="#mapModal" id="mapModalBtn" onclick="map(<?php echo $map_id . ',' . $mapping_type ?>)">
                                                                                        <i class="fa fa-file-text"></i> View Map
                                                                                    </a>
                                                                                </li>
                                                                            <?php
                                                                            } else {
                                                                            ?>
                                                                                <li>
                                                                                    <a type="button" href="add-map-data-automatically.php?mapid=<?= $map_id ?>">
                                                                                        <i class="fa fa-file-text"></i> Automated Mapping
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a type="button" href="add-map-data-manual.php?mapid=<?= $map_id ?>">
                                                                                        <i class="fa fa-file-text"></i> Manual Mapping
                                                                                    </a>
                                                                                </li>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                            // }
                                                        } while ($row_rsMap = $query_rsMap->fetch());
                                                    } else {
                                                        ?>
                                                        <tr>
                                                            <td colspan="7">No project requiring mapping</td>
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
                    </div>
                </div>
            </div>
    </section>
    <!-- end body  -->

    <!-- More Item more -->
    <div class="modal fade" tabindex="-1" role="dialog" id="moreModal">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info-circle"></i> More Information</h4>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="body" id="">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover" id="assign_table" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th width="20%">Location</th>
                                                    <th width="20%">Responsible</th>
                                                    <th width="40%">Team</th>
                                                    <th width="10%">Mapping Date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="moreinfo">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Close</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- End Item more -->
    <!-- add item -->
    <div class="modal fade" id="addFormModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="submitMilestoneForm" action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> Assign </h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="body" id="">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover" id="assign_table" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">#</th>
                                                        <th width="20%">Location</th>
                                                        <th width="40%">Team</th>
                                                        <th width="15%">Responsible</th>
                                                        <th width="20%">Mapping Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="assign_table_body">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /modal-body -->

                    <div class=" modal-footer">
                        <div class="col-md-12 text-center">
                            <input type="hidden" name="newitem" id="newitem" value="new">
                            <input type="hidden" name="user_name" id="user_name" value="55">
                            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                            <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                        </div>
                    </div> <!-- /modal-footer -->
                </form> <!-- /.form -->
            </div> <!-- /modal-content -->
        </div> <!-- /modal-dailog -->
    </div>
    <!-- End add item -->
<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>
<script src="assets/custom js/assign.js"></script>
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
    $(document).ready(function() {
        $('#mapping_table').DataTable();
        var table = $('#mapping_table1').DataTable({});
    });
</script>