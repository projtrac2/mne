    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
                        <tr>
                            <td width="80%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF">
                                <div align="left" style="vertical-align: text-bottom">
                                    <font size="3" color="#FFF"><i class="fa fa-globe" aria-hidden="true"></i></font>
                                    <font size="3" color="#FFC107"><strong>Countries</strong></font>
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
                            <th width="47%">Country</th>
                            <th width="15%">ISO Code</th>
                            <th width="15%">Country Code </th>
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
                <form class="form-horizontal" id="submitItemForm" action="general-settings/action/project-countries-action.php" method="POST" enctype="multipart/form-data">
                    <?= csrf_token_html(); ?>
                    <div class="modal-header" style="background-color:#03A9F4">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-plus"></i> Add Countries</h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="body">
                                        <div id="add-item-messages"></div>
                                        <div class="col-md-12 form-input">
                                            <label>
                                                <font color="#174082">Country Name : </font>
                                            </label>
                                            <input type="text" class="form-control" id="country" placeholder="Name of Country" name="country" required autocomplete="off">
                                        </div>
                                        <div class="col-md-12 form-input">
                                            <label>
                                                <font color="#174082">Country ISO Code: </font>
                                            </label>
                                            <input type="text" class="form-control" id="isocode" placeholder="Country ISO Code" name="isocode" required autocomplete="off">
                                        </div>
                                        <div class="col-md-12 form-input">
                                            <label>
                                                <font color="#174082">Country Code: </font>
                                            </label>
                                            <input type="number" class="form-control" id="code" placeholder="Country Code" name="code" required autocomplete="off">
                                        </div>
                                        <div class="col-md-12 form-input">
                                            <label>
                                                <font color="#174082">Country Value: </font>
                                            </label>
                                            <input type="number" class="form-control" id="value" placeholder="Country Value" name="value" required autocomplete="off">
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
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Edit Country</h4>
                </div>
                <div class="modal-body" style="max-height:450px; overflow:auto;">
                    <div class="card">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="body">
                                    <div class="div-result">
                                        <form class="form-horizontal" id="editItemForm" action="general-settings/action/project-countries-action.php" method="POST">
                                            <?= csrf_token_html(); ?>
                                            <br />
                                            <div class="col-md-12 id=" edit-product-messages"></div>
                                            <div class="col-md-12 form-input">
                                                <label>
                                                    <font color="#174082">Country Name : </font>
                                                </label>
                                                <input type="text" class="form-control" id="editcountry" placeholder="Name of Country" name="editcountry" required autocomplete="off">
                                            </div>
                                            <div class="col-md-12 form-input">
                                                <label>
                                                    <font color="#174082">Country ISO Code: </font>
                                                </label>
                                                <input type="text" class="form-control" id="editisocode" placeholder="Country ISO Code" name="editisocode" required autocomplete="off">
                                            </div>
                                            <div class="col-md-12 form-input">
                                                <label>
                                                    <font color="#174082">Country Code: </font>
                                                </label>
                                                <input type="number" class="form-control" id="editcode" placeholder="Country Code" name="editcode" required autocomplete="off">
                                            </div><!-- /form-group-->
                                            <div class="col-md-8 form-input">
                                                <label>
                                                    <font color="#174082">Country Value: </font>
                                                </label>
                                                <input type="number" class="form-control" id="editvalue" placeholder="Country Value" name="editvalue" required autocomplete="off">
                                            </div>
                                            <div class="col-md-4 form-input">
                                                <label for="editStatus">
                                                    <font color="#174082">Title Status: </font>
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
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i> Delete Country</h4>
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

    <script src="general-settings/js/fetch-countries.js"></script>