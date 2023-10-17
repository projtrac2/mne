<?php
require('includes/head.php');
$permission = true;
if ($permission) {
    try {
        $query_rsMinistry = $db->prepare("SELECT * FROM tbl_sectors  WHERE parent='0' ");
        $query_rsMinistry->execute();
        $row_rsMinistry = $query_rsMinistry->fetch();
        $total_rsMinistry = $query_rsMinistry->rowCount();
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
                    <?php echo $pageTitle ?>
                    <div class="btn-group" style="float:right">
                        <div class="btn-group" style="float:right">
                            <button type="button" id="modal_button" onclick="add('0', 'Ministry')" class="pull-right btn bg-deep-purple" data-toggle="modal" id="addItemModalBtn" data-target="#addItemModal">
                                <i class="fa fa-plus-square"></i> Add Ministry
                            </button>
                        </div>
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
                            <table class="table table-bordered table-striped table-hover " id="manageItemTable">
                                <thead>
                                    <tr style="background-color:#0b548f; color:#FFF">
                                        <th></th>
                                        <th>#</th>
                                        <th colspan="2">Ministry</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($total_rsMinistry > 0) {
                                        $counter = 0;
                                        do {
                                            $counter++;
                                            $ministry_id = $row_rsMinistry['stid'];
                                            $ministry = $row_rsMinistry['sector'];
                                            $role_group = $row_rsMinistry['role_id'];
                                            $deleted = $row_rsMinistry['deleted'];
                                            $status = $deleted ? "Are you sure you wan to enable" . $ministry : "Are you sure you want to disable" . $ministry;
                                    ?>
                                            <tr class="projects" style="background-color:#eff9ca">
                                                <td align="center" class="mb-0" id="projects<?php echo $ministry_id ?>" data-toggle="collapse" data-target=".project<?php echo $ministry_id ?>" style="background-color:#0b548f">
                                                    <button class="btn btn-link " title="Click once to expand and Click once to Collapse!!" style="color:#FFF">
                                                        <i class="fa fa-plus-square" style="font-size:16px"></i>
                                                    </button>
                                                </td>
                                                <td align="center"><?= $counter ?></td>
                                                <td colspan="2"><?= $ministry ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Options <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a type="button" data-toggle="modal" data-target="#editItemModal" id="moreModalBtn" onclick="edit(0, 'Ministry' ,'<?= $ministry ?>' ,<?= $role_group ?>,<?= $ministry_id ?>)">
                                                                    <i class="fa fa-file-text"></i> Edit
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a type="button" onclick="destroy(<?= $ministry_id ?>,'<?= $status ?>', <?= $deleted ? 0 : 1 ?>)">
                                                                    <i class="fa fa-file-text"></i> <?= $deleted ? "Enable" : "Disable" ?>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a type="button" id="modal_button" onclick="add(<?= $ministry_id ?>, 'Sector')" class="" data-toggle="modal" id="addItemModalBtn" data-target="#addItemModal">
                                                                    <i class="fa fa-plus-square"></i> Add Sector
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                            $query_rsSector = $db->prepare("SELECT * FROM tbl_sectors WHERE parent='$ministry_id'");
                                            $query_rsSector->execute();
                                            $row_rsSector = $query_rsSector->fetch();
                                            $total_rsSector = $query_rsSector->rowCount();

                                            if ($total_rsSector > 0) {
                                            ?>
                                                <tr class="collapse project<?php echo $ministry_id ?>" style="background-color:#2d8bd6; color:#FFF">
                                                    <th width="5%"></th>
                                                    <th width="5%">#</th>
                                                    <th colspan="2" width="40%">Section</th>
                                                    <th width="10%">Action</th>
                                                </tr>
                                                <?php
                                                $Ocounter = 0;
                                                do {
                                                    $Ocounter++;
                                                    $sector_id = $row_rsSector['stid'];
                                                    $sector = $row_rsSector['sector'];
                                                    $role_group = $row_rsSector['role_id'];
                                                    $deleted = $row_rsSector['deleted'];
                                                    $status = $deleted ? "Are you sure you wan to enable" . $sector : "Are you sure you want to disable" . $sector;
                                                ?>
                                                    <tr class="collapse project<?php echo $ministry_id ?>" style="background-color:#dbdbdb">
                                                        <td align="center" class="mb-0" id="outputs<?php echo $sector_id ?>" data-toggle="collapse" data-parent="#accordion<?= $ministry_id ?>" data-target=".output<?php echo $sector_id ?>" style="background-color:#2d8bd6">
                                                            <button class="btn btn-link output_class<?php echo $sector_id ?>" title="Click once to expand and Click once to Collapse!!" style="color:#FFF"> <i class="more-less fa fa-plus-square" style="font-size:16px"></i>
                                                            </button>
                                                        </td>
                                                        <td align="center"> <?php echo $counter . "." . $Ocounter ?></td>
                                                        <td colspan="2"><?php echo $sector ?></td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    Options <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a type="button" data-toggle="modal" data-target="#editItemModal" id="moreModalBtn" onclick="edit($ministry_id, 'Sector' ,'<?= $sector ?>' ,<?= $role_group ?>,<?= $sector_id ?>)">
                                                                            <i class="fa fa-file-text"></i> Edit
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a type="button" onclick="destroy(<?= $sector_id ?>,'<?= $status ?>', <?= $deleted ? 0 : 1 ?>)">
                                                                            <i class="fa fa-file-text"></i> <?= $deleted ? "Enable" : "Disable" ?>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a type="button" id="modal_button" onclick="add(<?= $sector_id ?>, 'Directorate')" data-toggle="modal" id="addItemModalBtn" data-target="#addItemModal">
                                                                            <i class="fa fa-plus-square"></i> Add Directorate
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $query_rsDirectorate = $db->prepare("SELECT * FROM tbl_sectors WHERE parent='$sector_id'");
                                                    $query_rsDirectorate->execute();
                                                    $row_rsDirectorate = $query_rsDirectorate->fetch();
                                                    $total_rsDirectorate = $query_rsDirectorate->rowCount();

                                                    if ($total_rsDirectorate > 0) {
                                                    ?>
                                                        <tr class="collapse output<?php echo $sector_id ?>" style="background-color:#03affc; color:#FFF">
                                                            <th></th>
                                                            <th>#</th>
                                                            <th colspan="2">Directorate</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        <?php
                                                        $mcounter = 0;
                                                        do {
                                                            $mcounter++;
                                                            $directorate_id = $row_rsDirectorate['stid'];
                                                            $directorate = $row_rsDirectorate['sector'];
                                                            $role_group = $row_rsDirectorate['role_id'];
                                                            $deleted = $row_rsDirectorate['deleted'];
                                                            $status = $deleted ? "Are you sure you wan to enable" . $directorate : "Are you sure you want to disable" . $directorate;
                                                        ?>
                                                            <tr class="collapse output<?php echo $sector_id ?>" style="background-color:#f5f5f5">
                                                                <td align="center" class="mb-0" data-toggle="collapse" data-parent="#accordion<?= $ministry_id ?>" data-target=".milestone<?php echo $directorate ?>" style="background-color:#03affc">

                                                                </td>
                                                                <td align="center"> <?php echo $counter . "." . $Ocounter . "." . $mcounter ?></td>
                                                                <td colspan="2"><?php echo $directorate ?></td>
                                                                <td>
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Options <span class="caret"></span>
                                                                        </button>
                                                                        <ul class="dropdown-menu">
                                                                            <li>
                                                                                <a type="button" data-toggle="modal" data-target="#editItemModal" id="moreModalBtn" onclick="edit(<?= $directorate_id ?>, 'Directorate' ,'<?= $directorate ?>' ,<?= $role_group ?>,<?= $directorate_id ?>)">
                                                                                    <i class="fa fa-file-text"></i> Edit
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a type="button" onclick="destroy(<?= $directorate_id ?>,'<?= $status ?>', <?= $deleted ? 0 : 1 ?>)">
                                                                                    <i class="fa fa-file-text"></i> <?= $deleted ? "Enable" : "Disable" ?>
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        } while ($row_rsDirectorate = $query_rsDirectorate->fetch());
                                                    } else {
                                                        ?>
                                                        <tr class="collapse output<?php echo $sector_id ?>" style="background-color:#FFC107; color:#FFF">
                                                            <td width="5%"></td>
                                                            <td colspan="5">Sorry this Sector does not have Directorate/s!!!</td>
                                                        </tr>
                                        <?php
                                                    }
                                                } while ($row_rsSector = $query_rsSector->fetch());
                                            } else {
                                            }
                                        } while ($row_rsMinistry = $query_rsMinistry->fetch());
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

    <!-- add item -->
    <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form class="form-horizontal" id="submitItemForm" action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-plus"></i> Add </h4>
                    </div>

                    <div class="modal-body">
                        <div class="card">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="body">
                                        <div class="col-md-3">
                                            <label>Role Group *:</label>
                                            <div class="form-line">
                                                <select name="role_group" class="form-control show-tick" data-live-search="true" style="border:#CCC thin solid; border-radius:5px" required>
                                                    <option value="" class="selection">...Select Role Group Type...</option>
                                                    <option value="1" class="selection">...Role Group 1...</option>
                                                    <option value="2" class="selection">...Role Group 2...</option>
                                                    <option value="3" class="selection">...Role Group 3...</option>
                                                    <option value="4" class="selection">...Role Group 4...</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 form-input">
                                            <label>
                                                <font color="#174082"> <span id="value_type"></span> </font>
                                            </label>
                                            <div class="form-input">
                                                <input type="text" name="sector" class="form-control" id="sector" value="" style="height:35px; width:98%" placeholder="" required />
                                                <input type="hidden" name="parent" id="parent">
                                                <span id="projdurationmsg1" style="color:red"></span>
                                            </div>
                                        </div>
                                        <!-- /form-group-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /modal-body -->

                    <div class="modal-footer">
                        <div class="col-md-12 text-center">
                            <input type="hidden" name="newitem" id="newitem" value="new">
                            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                            <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                        </div>
                    </div> <!-- /modal-footer -->
                </form> <!-- /.form -->
            </div> <!-- /modal-content -->
        </div> <!-- /modal-dailog -->
    </div>
    <!-- End add item -->


    <!-- Start Modal Item Edit -->
    <div class="modal fade" id="editItemModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Edit Project Funding Type</h4>
                </div>
                <div class="modal-body" style="max-height:450px; overflow:auto;">
                    <div class="card">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="body">
                                    <div class="div-result">
                                        <form class="form-horizontal" id="editItemForm" action="" method="POST">
                                            <div class="col-md-3">
                                                <label>Role Group *:</label>
                                                <div class="form-line">
                                                    <select name="role_group" id="role_group1" class=" form-control show-tick" data-live-search="true" style="border:#CCC thin solid; border-radius:5px" required>
                                                        <option value="" class="selection">...Select Role Group Type...</option>
                                                        <option value="1" class="selection">...Role Group 1...</option>
                                                        <option value="2" class="selection">...Role Group 2...</option>
                                                        <option value="3" class="selection">...Role Group 3...</option>
                                                        <option value="4" class="selection">...Role Group 4...</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12 form-input">
                                                <label>
                                                    <font color="#174082"> <span id="value_type1"></span> </font>
                                                </label>
                                                <div class="form-input">
                                                    <input type="text" name="sector" class="form-control" id="sector1" value="<?php echo (isset($_GET['sctid'])) ? $row_sctparent['sector'] : ""; ?>" style="height:35px; width:98%" placeholder="Enter sector/department" required />
                                                    <input type="hidden" name="parent" id="parent1">
                                                    <input type="hidden" name="stid" id="stid1">
                                                    <span id="projdurationmsg1" style="color:red"></span>
                                                </div>
                                            </div>
                                            <div class="modal-footer editItemFooter">
                                                <div class="col-md-12 text-center">
                                                    <input type="hidden" name="edititem" id="edititem" value="edit">
                                                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                                    <input name="edit" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                                                </div>
                                            </div> <!-- /modal-footer -->
                                        </form> <!-- /.form -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- /modal-body -->
            </div>
            <!-- /modal-content -->
        </div>
        <!-- /modal-dailog -->
    </div>
    <!-- End Item Edit -->
<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $(document).ready(function() {
        $(".collapse td").click(function(e) {
            e.preventDefault();
            $(this)
                .find("i")
                .toggleClass("fa-plus-square fa-minus-square");
        });

        $(".projects td").click(function(e) {
            e.preventDefault();
            $(this)
                .find("i")
                .toggleClass("fa-plus-square fa-minus-square");
        });

        $(".output td").click(function(e) {
            e.preventDefault();
            $(this)
                .find("i")
                .toggleClass("fa-plus-square fa-minus-square");
        });

        $("#submitItemForm").submit(function(e) {
            e.preventDefault();
            var form_data = $(this).serialize();
            $.ajax({
                type: "post",
                url: "ajax/sectors/index",
                data: form_data,
                dataType: "json",
                success: function(response) {
                    if (response) {
                        swal("Success!", "Record created successfully!", "success");
                    } else {
                        swal("Error!", "Could not create record!", "error");
                    }
                    window.location.reload(true)
                }
            });
        });

        $("#editItemForm").submit(function(e) {
            e.preventDefault();
            var form_data = $(this).serialize();
            $.ajax({
                type: "post",
                url: "ajax/sectors/index",
                data: form_data,
                dataType: "json",
                success: function(response) {
                    if (response) {
                        swal("Success!", "Record updated successfully!", "success");
                    } else {
                        swal("Error!", "Could not update record!", "error");
                    }
                    window.location.reload(true)
                }
            });
        });
    });


    function add(parent, value_type) {
        $("#parent").val(parent);
        $("#value_type").html(value_type);
    }

    function edit(parent, value_type, sector, role_group, stid) {
        $("#parent1").val(parent);
        $("#value_type1").html(value_type);
        $("#sector1").val(sector);
        $("#parent1").val(parent);
        $("#role_group1").val(role_group);
        $("#stid1").val(stid);
    }

    function destroy(stid, status, statusid) {
        swal({
                title: "Are you sure?",
                text: `${status}`,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "post",
                        url: "ajax/sectors/index",
                        data: {
                            deleteItem: "deleteItem",
                            stid: stid,
                            status: statusid,
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response) {
                                swal("Success!", "Status updated successfully!", "success");
                            } else {
                                swal("Error!", "Could not update status!", "error");
                            }
                            window.location.reload(true)
                        }
                    });
                } else {
                    swal("You have canceled the action!");
                }
            });
    }
</script>