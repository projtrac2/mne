    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
                        <tr>
                            <td width="80%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF">
                                <div align="left" style="vertical-align: text-bottom">
                                    <font size="3" color="#FFF"><i class="fa fa-list" aria-hidden="true"></i></font>
                                    <font size="3" color="#FFC107"><strong>Employee Leave Categories</strong></font>
                            </td>
                            <td width="20%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF">
                                <button type="button" id="modal_button" class="pull-right btn bg-deep-purple" data-toggle="modal" id="addItemModalBtn" data-target="#addItemModal"> <i class="fa fa-plus-square"></i> Add Item </button>
                </div>
                </td>
                </tr>
                </table>
            </div>
        </div>
        <div class="body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="manageItemTable">
                    <thead>
                        <tr>
                            <th width="3%">#</th>
                            <th width="37%">Leave Name</th>
                            <th width="25%">Leave Description</th>
                            <th width="15%">Leave Days </th>
                            <th width="10%">Status</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    </div>
    </div>

    <!-- add item -->
    <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form class="form-horizontal" id="submitItemForm" action="general-settings/action/project-employee-leave-categories-action.php" method="POST" enctype="multipart/form-data">
                    <?= csrf_token_html(); ?>
                    <div class="modal-header" style="background-color:#03A9F4">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-plus"></i> Add Employee Leave Category</h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="body">
                                        <div id="add-item-messages"></div>
                                        <div class="col-md-12 form-input">
                                            <label>
                                                <font color="#174082">Employee Leave Categories: </font>
                                            </label>
                                            <input type="text" class="form-control" id="name" placeholder="Name of Employee Leave " name="name" required autocomplete="off">
                                        </div>
                                        <div class="col-md-12 form-input">
                                            <label>
                                                <font color="#174082">Employee Leave Description: </font>
                                            </label>
                                            <textarea class="form-control" id="description" placeholder="Leave Category Description" name="description" required autocomplete="off"></textarea>
                                        </div>
                                        <div class="col-md-12 form-input">
                                            <label>
                                                <font color="#174082">Employee Leave Category Days: </font>
                                            </label>
                                            <input type="number" class="form-control" id="days" placeholder="Number of days of the leave " name="days" required autocomplete="off">
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
                            <input type="hidden" name="createdby" id="createdby" value="10">
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
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Edit Employee Leave Category</h4>
                </div>
                <div class="modal-body" style="max-height:450px; overflow:auto;">
                    <div class="card">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="body">
                                    <div class="div-result">
                                        <form class="form-horizontal" id="editItemForm" action="general-settings/action/project-employee-leave-categories-action.php" method="POST">
                                            <?= csrf_token_html(); ?>
                                            <br />
                                            <div class="col-md-12 id=" edit-product-messages"></div>
                                            <div class="col-md-12 form-input">
                                                <label>
                                                    <font color="#174082">Employee Leave Category: </font>
                                                </label>
                                                <input type="text" class="form-control" id="editName" placeholder="Name of Employee Leave " name="editName" required autocomplete="off">
                                            </div>
                                            <div class="col-md-12 form-input">
                                                <label>
                                                    <font color="#174082">Employee Leave Category Description: </font>
                                                </label>
                                                <textarea class="form-control" id="editDescription" placeholder="Leave Category Description" name="editDescription" required autocomplete="off"></textarea>
                                            </div>
                                            <div class="col-md-8 form-input">
                                                <label>
                                                    <font color="#174082">Employee Leave Category Days: </font>
                                                </label>
                                                <input type="number" class="form-control" id="editdays" placeholder="Number of days of the leave " name="editdays" required autocomplete="off">
                                            </div><!-- /form-group-->

                                            <div class="col-md-4 form-input">
                                                <label for="editStatus">
                                                    <font color="#174082">Leave Status: </font>
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
                                                    <input type="hidden" name="createdby" id="createdby" value="10">
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
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i> Delete Employee Leave Category</h4>
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

    <script src="general-settings/js/fetch-employee-leave-categories.js"></script>