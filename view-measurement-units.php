<?php
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');

if ($permission) {
    require('functions/strategicplan.php');
    $pageName = "Measurement Units";
    try {
    } catch (PDOException $ex) {
        $result = flashMessage("An error occurred: " . $ex->getMessage());
    }
?>

    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <i class="fa fa-columns" aria-hidden="true"></i>
                    <?php echo $pageName ?>
                    <div class="btn-group" style="float:right">
                        <div class="btn-group" style="float:right">
                            <?php
                            if ($file_rights->add) {
                            ?>
                                <button type="button" id="modal_button" style="float:right; margin-top:-5px" class="pull-right btn bg-deep-purple" data-toggle="modal" id="addItemModalBtn" data-target="#addItemModal"> <i class="fa fa-plus-square"></i> Add Item </button>
                            <?php
                            }
                            ?>
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
                                <table class="table table-bordered table-striped table-hover" id="manageItemTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Measurement Unit</th>
                                            <th>Measurement Unit Description</th>
                                            <th>Status</th>
                                            <?php
                                            if ($file_rights->edit && $file_rights->add) {
                                            ?>
                                                <th>Action</th>
                                            <?php
                                            }
                                            ?>
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



    <!-- add item -->
    <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form class="form-horizontal" id="submitItemForm" action="general-settings/action/measurement-units-action.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-plus"></i> Add Measurement Unit</h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="body">
                                        <div id="add-item-messages"></div>
                                        <div class="col-md-4 form-input">
                                            <label>
                                                <font color="#174082">Measurement Unit: </font>
                                            </label>
                                            <input type="text" class="form-control" id="unit" placeholder="Enter measurement unit" name="unit" required autocomplete="off">
                                        </div>
                                        <div class="col-md-8 form-input">
                                            <label>
                                                <font color="#174082">Measurement Unit Description: </font>
                                            </label>
                                            <input type="text" class="form-control" id="description" placeholder="Describe the measurement unit" name="description" required autocomplete="off">
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
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Edit Meaurement Unit</h4>
                </div>
                <div class="modal-body" style="max-height:450px; overflow:auto;">
                    <div class="card">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="body">
                                    <div class="div-result">
                                        <form class="form-horizontal" id="editItemForm" action="general-settings/action/measurement-units-action.php" method="POST">
                                            <br />
                                            <div class="col-md-12 id=" edit-product-messages"></div>
                                            <div class="col-md-4 form-input">
                                                <label>
                                                    <font color="#174082">Measurement Unit: </font>
                                                </label>
                                                <input type="text" class="form-control" id="editunit" placeholder="Enter measurement unit" name="editunit" required autocomplete="off">
                                            </div>
                                            <div class="col-md-8 form-input">
                                                <label>
                                                    <font color="#174082">Measurement Unit Description: </font>
                                                </label>
                                                <input type="text" class="form-control" id="editdescription" placeholder="Describe the measurement unit" name="editdescription" autocomplete="off">
                                            </div>

                                            <div class="col-md-4 form-input">
                                                <label for="editStatus">
                                                    <font color="#174082">Measurement Unit Status: </font>
                                                </label>
                                                <select class="form-control" id="editStatus" name="editStatus" required>
                                                    <option value="">~~SELECT~~</option>
                                                    <option value="1">Enabled</option>
                                                    <option value="0">Disabled</option>
                                                </select>
                                            </div> <!-- /form-group-->
                                            <div class="modal-footer editItemFooter">
                                                <div class="col-md-12 text-center">
                                                    <input type="hidden" name="edititem" id="edititem" value="edit">
                                                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                                    <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
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

    <!-- Start Item Delete -->
    <div class="modal fade" tabindex="-1" role="dialog" id="removeItemModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i> Delete Item</h4>
                </div>
                <div class="modal-body">
                    <div class="removeItemMessages"></div>
                    <p align="center">Are you sure you want to delete this record?</p>
                </div>
                <div class="modal-footer removeProductFooter">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Cancel</button>
                        <button type="button" class="btn btn-success" id="removeItemBtn"> <i class="fa fa-check-square-o"></i> Delete</button>
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

<script src="assets/js/indicators/measurement_unit.js"></script>
<!-- <script src="general-settings/js/fetch-measurement-units.js"></script> -->