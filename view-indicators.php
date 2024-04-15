<?php

try {
    //code...

require('includes/head.php');
require('functions/indicator.php');
require('functions/department.php');

if ($permission) {

    $total_output_indicators = 0;
    $total_outcome_indicators = 0;
    $total_impact_indicators = 0;
    $output_indicators = get_output_indicators();
    $outcome_indicators = get_outcome_indicators();
    $impact_indicators = get_impact_indicators();

    if ($output_indicators) {
        $total_output_indicators = count($output_indicators);
    }

    if ($outcome_indicators) {
        $total_outcome_indicators = count($outcome_indicators);
    }

    if ($impact_indicators) {
        $total_impact_indicators = count($impact_indicators);
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
                        </div>
                    </div>
                </h4>
            </div>
            <div class="row clearfix">
                <div class="block-header">
                    <?= $results; ?>
                    <?= $results; ?>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs" style="font-size:14px">
                                <li class="active">
                                    <a data-toggle="tab" href="#output"><i class="fa fa-caret-square-o-down bg-deep-orange" aria-hidden="true"></i> Output Indicators&nbsp;<span class="badge bg-orange"><?= $total_output_indicators ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#outcome"><i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> Outcome Indicators&nbsp;<span class="badge bg-blue"><?= $total_outcome_indicators ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                                </li>
                                <li id="impact_tab">
                                    <a data-toggle="tab" href="#impact">
                                        <i class="fa fa-caret-square-o-right bg-green" aria-hidden="true"></i> Impact Indicators&nbsp;
                                        <span class="badge bg-green" id="impact_counter"> <?= $total_impact_indicators ?> </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <!-- ============================================================== -->
                            <!-- Start Page Content -->
                            <!-- ============================================================== -->
                            <div class="tab-content">
                                <div id="output" class="tab-pane fade in active">
                                    <div class="card-header">
                                        <div class="pull-right">
                                            <?php
                                            if (in_array("create", $page_actions)) {
                                            ?>
                                                <a href="add-output-indicator.php" class="btn btn-warning">New Indicator </a>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="spPrograms">
                                                <thead>
                                                    <tr class="bg-orange">
                                                        <th width="3%"><strong id="colhead">SN</strong></th>
                                                        <th width="10%"><strong id="colhead">Code</strong></th>
                                                        <th width="62%"><strong id="colhead">Indicator</strong></th>
                                                        <th width="10%"><strong id="colhead">Baseline</strong></th>
                                                        <th width="10%"><strong id="colhead">Action</strong></th>
                                                    </tr>
                                                <tbody>
                                                    <?php
                                                    if ($total_output_indicators > 0) {
                                                        $num = 0;
                                                        foreach ($output_indicators as $output_indicator) {
                                                            $indid = $output_indicator['indid'];
                                                            $indid = base64_encode("opid{$indid}");
                                                            $ind = $output_indicator['indid'];
                                                            $baselinelevel = $output_indicator['indicator_baseline_level'];
                                                            $baselineid = $output_indicator['baseline'];
                                                            $indunit = $output_indicator['indicator_unit'];

                                                            $query_indunit = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id='$indunit' LIMIT 1");
                                                            $query_indunit->execute();
                                                            $row_indunit = $query_indunit->fetch();
                                                            if (!empty($row_indunit)) {
                                                                $ms_unit = $row_indunit["unit"];
                                                            }

                                                            $baseline = '<span class="badge bg-red">Pending</span>';
                                                            if ($baselineid == 1) {
                                                                $baseline = '<span class="badge bg-green">Complete</span>';
                                                            }
                                                            $num = $num + 1;
                                                    ?>
                                                            <tr id="rowlines">
                                                                <td><?php echo $num; ?></td>
                                                                <td><?php echo $output_indicator['indicator_code']; ?></td>
                                                                <td><?php echo $ms_unit . " of " . $output_indicator['indicator_name']; ?></td>
                                                                <td><?php echo $baseline; ?></td>
                                                                <td>
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Options <span class="caret"></span>
                                                                        </button>
                                                                        <ul class="dropdown-menu">
                                                                            <li>
                                                                                <a type="button" data-toggle="modal" data-target="#moreModal" id="moreModalBtn" onclick="get_more('<?php echo $ind ?>')">
                                                                                    <i class="fa fa-file-text"></i> More Info
                                                                                </a>
                                                                            </li>
                                                                            <?php
                                                                            if (in_array("update", $page_actions)) {
                                                                            ?>
                                                                                <li>
                                                                                    <a type="button" href="edit-output-indicator.php?ind=<?= $indid ?>" id="addFormModalBtn">
                                                                                        <i class="fa fa-pencil-square"></i> Edit Indicator
                                                                                    </a>
                                                                                </li>
                                                                            <?php
                                                                            }
                                                                            if (in_array("delete", $page_actions)) {
                                                                            ?>
                                                                                <li>
                                                                                    <a type="button" data-toggle="modal" data-target="#removeItemModal" id="#removeItemModalBtn" onclick="removeItem('<?php echo $ind ?>')">
                                                                                        <i class="fa fa-trash-o"></i> Delete
                                                                                    </a>
                                                                                </li>
                                                                            <?php

                                                                            }

                                                                            if ($baselineid == 1) {
                                                                            ?>
                                                                                <li>
                                                                                    <a type="button" href="indicator-existing-baseline-data.php?ind=<?= $indid ?>&view=1">
                                                                                        <i class="fa fa-eye"></i> View Basevalue
                                                                                    </a>
                                                                                </li>
                                                                            <?php
                                                                            } else if (in_array("create", $page_actions)) {
                                                                            ?>
                                                                                <li>
                                                                                    <a type="button" href="indicator-existing-baseline-data.php?ind=<?= $indid ?>">
                                                                                        <i class="fa fa-plus-square"></i> Add Basevalue
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
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div id="outcome" class="tab-pane fade">
                                    <div class="card-header">
                                        <div class="pull-right">
                                            <?php
                                            if (in_array("create", $page_actions)) {
                                            ?>
                                                <a href="add-outcome-indicator.php" class="btn btn-primary"> New Indicator</a>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <div class="body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="indepedentPrograms" style="width:100%">
                                                <thead style="width:100%">
                                                    <tr class="bg-blue" style="width:100%">
                                                        <th width="3%"><strong id="colhead">SN</strong></th>
                                                        <th width="5%"><strong id="colhead">Code</strong></th>
                                                        <th width="70%"><strong id="colhead">Indicator</strong></th>
                                                        <th width="15%"><strong id="colhead">Unit of Measure</strong></th>
                                                        <th width="7%"><strong id="colhead">Action</strong></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if ($total_outcome_indicators == 0) {
                                                    ?>

                                                        <?php } else {
                                                        $num = 0;
                                                        foreach ($outcome_indicators as $outcome_indicator) {
                                                            $indid = $outcome_indicator['indid'];
                                                            $indid = base64_encode("ocid{$indid}");
                                                            $ind = $outcome_indicator['indid'];
                                                            $inddept = $outcome_indicator['indicator_dept'];
                                                            $num = $num + 1;

                                                            $result_department = get_department($inddept);
                                                            $department = "N/A";
                                                            if ($result_department) {
                                                                $department = $result_department['sector'];
                                                            }

                                                            $indunit = $outcome_indicator['indicator_unit'];

                                                            $query_indunit = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id='$indunit' LIMIT 1");
                                                            $query_indunit->execute();
                                                            $row_indunit = $query_indunit->fetch();
                                                            if (!empty($row_indunit)) {
                                                                $ms_unit = $row_indunit["unit"];
                                                            }
                                                        ?>
                                                            <tr id="rowlines">
                                                                <td width="3%"><?php echo $num; ?></td>
                                                                <td width="5%"><?php echo $outcome_indicator['indicator_code']; ?></td>
                                                                <td width="70%"><?php echo $outcome_indicator['indicator_name']; ?></td>
                                                                <td width="15%"><?php echo $ms_unit; ?></td>
                                                                <td width="7%">
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Options <span class="caret"></span>
                                                                        </button>
                                                                        <ul class="dropdown-menu">
                                                                            <li>
                                                                                <a type="button" data-toggle="modal" data-target="#moreModal" id="moreModalBtn" onclick="get_more(<?php echo $ind ?>)">
                                                                                    <i class="fa fa-file-text"></i> More Info
                                                                                </a>
                                                                            </li>
                                                                            <?php
                                                                            if (in_array("update", $page_actions)) {                                                                            ?>
                                                                                <li>
                                                                                    <a type="button" href="edit-outcome-indicator.php?ind=<?= $indid ?>" id="addFormModalBtn">
                                                                                        <i class="fa fa-pencil-square"></i> </i> Edit
                                                                                    </a>
                                                                                </li>
                                                                            <?php
                                                                            }
                                                                            if (in_array("delete", $page_actions)) {                                                                            ?>
                                                                                <li>
                                                                                    <a type="button" data-toggle="modal" data-target="#removeItemModal" id="#removeItemModalBtn" onclick="removeItem(<?php echo $ind ?>)">
                                                                                        <i class="fa fa-trash-o"></i> Delete
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
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div id="impact" class="tab-pane fade">
                                    <div class="card-header">
                                        <div class="pull-right">
                                            <?php
                                            if (in_array("create", $page_actions)) {
                                            ?>
                                                <a href="add-impact-indicator.php" class="btn btn-success"> New Indicator</a>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <div class="body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="indepedentPrograms" style="width:100%">
                                                <thead style="width:100%">
                                                    <tr class="bg-green" style="width:100%">
                                                        <th width="3%"><strong id="colhead">SN</strong></th>
                                                        <th width="5%"><strong id="colhead">Code</strong></th>
                                                        <th width="70%"><strong id="colhead">Indicator</strong></th>
                                                        <th width="15%"><strong id="colhead">Unit of Measure</strong></th>
                                                        <th width="7%"><strong id="colhead">Action</strong></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if ($total_impact_indicators == 0) {
                                                    ?>

                                                        <?php } else {
                                                        $num = 0;
                                                        foreach ($impact_indicators as $impact_indicator) {
                                                            $indid = $impact_indicator['indid'];
                                                            $indid = base64_encode("impid{$indid}");
                                                            $ind = $impact_indicator['indid'];
                                                            $inddept = $impact_indicator['indicator_dept'];
                                                            $num = $num + 1;

                                                            $result_department = get_department($inddept);
                                                            $department = "N/A";
                                                            if ($result_department) {
                                                                $department = $result_department['sector'];
                                                            }

                                                            $indunit = $impact_indicator['indicator_unit'];

                                                            $query_indunit = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id='$indunit' LIMIT 1");
                                                            $query_indunit->execute();
                                                            $row_indunit = $query_indunit->fetch();
                                                            if (!empty($row_indunit)) {
                                                                $ms_unit = $row_indunit["unit"];
                                                            }
                                                        ?>
                                                            <tr id="rowlines">
                                                                <td width="3%"><?php echo $num; ?></td>
                                                                <td width="5%"><?php echo $impact_indicator['indicator_code']; ?></td>
                                                                <td width="70%"><?php echo $impact_indicator['indicator_name']; ?></td>
                                                                <td width="15%"><?php echo $ms_unit; ?></td>
                                                                <td width="7%">
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Options <span class="caret"></span>
                                                                        </button>
                                                                        <ul class="dropdown-menu">
                                                                            <li>
                                                                                <a type="button" data-toggle="modal" data-target="#moreModal" id="moreModalBtn" onclick="get_more(<?php echo $ind ?>)">
                                                                                    <i class="fa fa-file-text"></i> More Info
                                                                                </a>
                                                                            </li>
                                                                            <?php
                                                                            if (in_array("update", $page_actions)) {
                                                                            ?>
                                                                                <li>
                                                                                    <a type="button" href="edit-impact-indicator.php?ind=<?= $indid ?>" id="addFormModalBtn">
                                                                                        <i class="fa fa-pencil-square"></i> </i> Edit
                                                                                    </a>
                                                                                </li>
                                                                            <?php
                                                                            }
                                                                            if (in_array("delete", $page_actions)) {                                                                            ?>
                                                                                <li>
                                                                                    <a type="button" data-toggle="modal" data-target="#removeItemModal" id="#removeItemModalBtn" onclick="removeItem(<?php echo $ind ?>)">
                                                                                        <i class="fa fa-trash-o"></i> Delete
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
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
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
    <style>
        @media (min-width: 1200px) {
            .modal-lg {
                width: 90%;
                height: 100%;
            }
        }
    </style>
    <!-- Start Item more -->
    <div class="modal fade" tabindex="-1" role="dialog" id="moreModal">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info-circle"></i> More Information</h4>
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
    <!-- End Item more -->
<?php
} else {
    $results =  restriction();
    echo $results;
}
require('includes/footer.php');

} catch (\PDOException $th) {
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>
<script>
    const url1 = 'ajax/indicators/index';

    function get_more(indid = null) {
        if (indid) {
            $.ajax({
                type: "get",
                url: url1,
                data: {
                    more: "more",
                    indid: indid
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        var indicator = response.msg;
                        $("#moreinfo").html(indicator);
                    } else {
                        swal(response.msg, {
                            icon: "error",
                        });
                    }
                }
            });
        } else {
            swal("Error id does not exist", {
                icon: "error",
            });
        }
    }

    function removeItem(indid = null) {
        if (indid) {
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this data!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: "post",
                            url: url1,
                            data: {
                                delete: "delete",
                                indid: indid
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.success) {
                                    swal(response.msg, {
                                        icon: "success",
                                    });
                                    setTimeout(function() {
                                        location.reload(true)
                                    }, 3000);
                                } else {
                                    swal(response.msg, {
                                        icon: "error",
                                    });
                                    setTimeout(function() {
                                        location.reload(true)
                                    }, 3000);
                                }
                            }
                        });
                    } else {
                        swal("You have canceled the action!");
                    }
                });

        } else {
            swal("Error id does not exist", {
                icon: "error",
            });
        }
    }
</script>