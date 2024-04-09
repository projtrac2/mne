<?php

try {
    //code...

require('includes/head.php');
if ($permission) {

?>
    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <?= $icon . " " . $pageTitle ?>
                    <div class="btn-group" style="float:right">
                        <div class="btn-group" style="float:right">
                            <button type="button" id="modal_button" onclick="add_location('0','','new')" class="pull-right btn bg-deep-purple" data-toggle="modal" id="addItemModalBtn" data-target="#addItemModal">
                                <i class="fa fa-plus-square"></i> Add Location
                            </button>
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
                            <!-- ============================================================== -->
                            <!-- Start Page Content -->
                            <!-- ============================================================== -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover " id="manageItemTable">
                                    <thead>
                                        <tr style="background-color:#0b548f; color:#FFF">
                                            <th style="width: 5%;"></th>
                                            <th style="width: 5%;">#</th>
                                            <th colspan="2" style="width: 80%;"> <?= $level1label ?></th>
                                            <th style="width: 10%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $counter = 0;
                                        $query_rsState = $db->prepare("SELECT * FROM tbl_state WHERE parent IS NULL");
                                        $query_rsState->execute();
                                        $rows_rsState = $query_rsState->rowCount();
                                        if ($rows_rsState > 0) {
                                            while ($row_rsState = $query_rsState->fetch()) {
                                                $counter++;
                                                $level1_id = $row_rsState['id'];
                                                $level1 = $row_rsState['state'];
                                                $status_id = $row_rsState['active'];
                                                $status = $status_id == 1 ? "Disable"   : "Enable";
                                        ?>
                                                <tr class="projects" style="background-color:#eff9ca">
                                                    <td align="center" class="mb-0" id="projects<?php echo $level1_id ?>" data-toggle="collapse" data-target=".project<?php echo $level1_id ?>" style="background-color:#0b548f">
                                                        <button class="btn btn-link " title="Click once to expand and Click once to Collapse!!" style="color:#FFF">
                                                            <i class="fa fa-plus-square" style="font-size:16px"></i>
                                                        </button>
                                                    </td>
                                                    <td align="center"><?= $counter ?></td>
                                                    <td colspan="2"><?= $level1 ?></td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Options <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a type="button" data-toggle="modal" data-target="#addItemModal" id="moreModalBtn" onclick="add_location('0','<?= $level1_id ?>','edit')">
                                                                        <i class="fa fa-file-text"></i> Edit <?= $level1label ?>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a type="button" onclick="destroy(<?= $level1_id ?>, <?= $status_id == 1 ? 0 : 1 ?>,'<?= $status ?>', '<?= $level1 ?>')">
                                                                        <i class="fa fa-file-text"></i> <?= $status  ?> <?= $level1label ?>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a type="button" id="modal_button" onclick="add_location('<?= $level1_id ?>','','new')" class="" data-toggle="modal" id="addItemModalBtn" data-target="#addItemModal">
                                                                        <i class="fa fa-plus-square"></i> Add <?= $level2label ?>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                                $query_rsAllWards = $db->prepare("SELECT * FROM `tbl_state` WHERE parent=:parent ORDER BY id ASC");
                                                $query_rsAllWards->execute(array(":parent" => $level1_id));
                                                $rows_rsAllWards = $query_rsAllWards->rowCount();
                                                if ($rows_rsAllWards > 0) {
                                                ?>
                                                    <tr class="collapse project<?php echo $level1_id ?>" style="background-color:#2d8bd6; color:#FFF">
                                                        <th width="5%"></th>
                                                        <th width="5%">#</th>
                                                        <th colspan="2" width="40%"> <?= $level2label ?></th>
                                                        <th width="10%">Action</th>
                                                    </tr>
                                                    <?php
                                                    $Ocounter = 0;
                                                    while ($row_rsAllWards = $query_rsAllWards->fetch()) {
                                                        $Ocounter++;
                                                        $level2 = $row_rsAllWards['state'];
                                                        $level2_id = $row_rsAllWards['id'];
                                                        $status_id = $row_rsAllWards['active'];
                                                        $status = $status_id == 1 ? "Disable"   : "Enable";
                                                    ?>
                                                        <tr class="collapse project<?php echo $level1_id ?>" style="background-color:#dbdbdb">
                                                            <td align="center" class="mb-0" id="outputs" data-toggle="collapse" data-parent="#accordion" data-target=".output" style="background-color:#2d8bd6">
                                                            </td>
                                                            <td align="center"> <?php echo $counter . "." . $Ocounter ?></td>
                                                            <td colspan="2"><?= $level2; ?></td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        Options <span class="caret"></span>
                                                                    </button>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <a type="button" data-toggle="modal" data-target="#addItemModal" id="moreModalBtn" onclick="add_location('<?= $level1_id ?>','<?= $level2_id ?>','edit')">
                                                                                <i class="fa fa-file-text"></i> Edit <?= $level2label ?>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a type="button" onclick="destroy(<?= $level2_id ?>, <?= $status_id == 1 ? 0 : 1 ?>,'<?= $status ?>', '<?= $level2 ?>')">
                                                                                <i class="fa fa-file-text"></i> <?= $status  ?>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                            <?php
                                                    }
                                                }
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="7">No Locations Currently</td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
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
    <!-- add item -->
    <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form class="form-horizontal" id="submitItemForm" action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-plus"></i> Location </h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="body">
                                        <div class="col-md-12 form-input">
                                            <label>
                                                <font color="#174082"> <span id="value_type">Location :</span> </font>
                                            </label>
                                            <div class="form-input">
                                                <input type="text" name="location" class="form-control" id="location" value="" style="height:35px; width:98%" placeholder="" required />
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
                            <input type="hidden" name="store_location" id="store_location" value="new">
                            <input type="hidden" name="parent" id="parent">
                            <input type="hidden" name="id" id="id">
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

} catch (\PDOException $th) {
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>

<script>
    var ajax_url = "ajax/location/index";
    $(document).ready(function() {
        $("#submitItemForm").submit(function(e) {
            e.preventDefault();
            var form_data = $(this).serialize();
            $.ajax({
                type: "post",
                url: ajax_url,
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
    });

    function add_location(parent_id, state_id, edit) {
        $("#store_location").val(edit);
        $("#parent").val(parent_id);
        $("#id").val(state_id);

        if (edit == 'edit') {
            $.ajax({
                type: "get",
                url: ajax_url,
                data: {
                    get_location_details: "get_location_details",
                    state_id: state_id,
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        var state = response.state;
                        $("#location").val(state.state);
                    } else {
                        error_alert("Sorry details not found ! try later");
                    }
                }
            });
        }
    }

    function destroy(state_id, status_id, status, state) {
        swal({
                title: "Are you sure?",
                text: `you want to ${status} ${state}`,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "post",
                        url: ajax_url,
                        data: {
                            deleteItem: "deleteItem",
                            state_id: state_id,
                            status_id: status_id,
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response) {
                                swal("Success!", "Status updated successfully!", "success");
                            } else {
                                swal("Error!", "Could not update status!", "error");
                            }
                            window.location.reload(true);
                        }
                    });
                } else {
                    swal("You have canceled the action!");
                }
            });
    }
</script>