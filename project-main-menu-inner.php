<?php
$query_rsMenu =  $db->prepare("SELECT * FROM tbl_sidebar_menu WHERE parent = 0 ORDER BY id ASC");
$query_rsMenu->execute();		
$row_rsMenu = $query_rsMenu->fetch();
$totalRows_rsMenu = $query_rsMenu->rowCount();
?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
			<div class="header">
				<div style="color:#333; background-color:#EEE; width:100%; height:30px">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
						<tr>
							<td width="80%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF">
								<div align="left" style="vertical-align: text-bottom"><font size="3" color="#FFC107"><i class="fa fa-cog" aria-hidden="true"></i> <strong>Project Main Menu</strong></font>
							</td>
							<td width="20%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF">
								<button type="button" id="modal_button" class="pull-right btn bg-deep-purple" data-toggle="modal" id="addItemModalBtn" data-target="#addItemModal"> <i class="fa fa-plus-square"></i> Add Item </button></div>
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
                                <th>#</th>
                                <th>Parent</th>
                                <th>Name</th>
                                <th>Icons</th>
                                <th>URL</th>
                                <th>Status</th>
                                <th>Action</th>
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
            <form class="form-horizontal" id="submitItemForm" action="general-settings/action/project-main-menu-action.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-plus"></i> Add Project Main Menu</h4>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="body">
                                    <div id="add-item-messages"></div>
                                    <div class="col-md-6 form-input">
                                        <label>
                                            <font color="#174082">Left Menu Parent: </font>
                                        </label>
                                        <select name="parent" id="parent" class="form-control show-tick selectpicker" data-live-search="false">
                                            <option value="" class="selection">Choose Parent</option>
                                            <option value="0">Parent</option>
                                            <?php
                                            do {
                                                ?>
                                                <option value="<?php echo $row_rsMenu['id'] ?>"><?php echo $row_rsMenu['Name'] ?></option>
                                            <?php
                                            } while ($row_rsMenu = $query_rsMenu->fetch());
                                            ?>
                                        </select>                                    
                                    </div>
                                    <div class="col-md-6 form-input">
                                        <label>
                                            <font color="#174082">Menu Name: </font>
                                        </label>
                                        <input type="text" class="form-control" id="name" placeholder="Name of Main Menu" name="name" required autocomplete="off">
                                    </div>
                                    <div class="col-md-6 form-input">
                                        <label>
                                            <font color="#174082">Menu URL: </font>
                                        </label>
                                        <input type="text" class="form-control" id="url" placeholder="Name of Project Main Menu" name="url" required autocomplete="off">
                                    </div>
                                    <div class="col-md-6 form-input">
                                        <label>
                                            <font color="#174082">Menu Icons: </font>
                                        </label>
                                        <input type="text" class="form-control" id="icons" placeholder="Name of Project Main Menu" name="icons" required autocomplete="off">
                                    </div>
                                    <div class="col-md-6 form-input">
                                        <label>
                                            <font color="#174082">Order No. in <span id=""order"></span>: </font>
                                        </label>
                                        <input type="text" class="form-control" id="icons" placeholder="Name of Project Main Menu" name="icons" required autocomplete="off">
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
                <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Edit Product</h4>
            </div>
            <div class="modal-body" style="max-height:450px; overflow:auto;">
                <div class="card">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="body">
                                <div class="div-result">
                                    <form class="form-horizontal" id="editItemForm" action="general-settings/action/project-main-menu-action.php" method="POST">
                                    <?php 
                                        $query_rsMenu =  $db->prepare("SELECT * FROM tbl_sidebar_menu WHERE parent = 0 ORDER BY id ASC");
                                        $query_rsMenu->execute();		
                                        $row_rsMenu = $query_rsMenu->fetch();
                                        $totalRows_rsMenu = $query_rsMenu->rowCount();
                                    ?>
                                    <br />
                                        <div class="col-md-12 id=" edit-product-messages"></div>
                                    <div class="col-md-12 form-input">
                                        <label>
                                            <font color="#174082">Left Menu Parent: </font>
                                        </label>
                                        <select name="editparent" id="editparent" class="form-control show-tick selectpicker" data-live-search="false">
                                            <option value="" class="selection">Choose Parent</option>
                                            <option value="0">Parent</option>
                                            <?php
                                            do {
                                                ?>
                                                <option value="<?php echo $row_rsMenu['id'] ?>"><?php echo $row_rsMenu['Name'] ?></option>
                                            <?php
                                            } while ($row_rsMenu = $query_rsMenu->fetch());
                                            ?>
                                        </select>                                    
                                    </div>
                                    <div class="col-md-12 form-input">
                                        <label>
                                            <font color="#174082">Menu Name: </font>
                                        </label>
                                        <input type="text" class="form-control" id="editname" placeholder="Name of Main Menu" name="editname" required autocomplete="off">
                                    </div>
                                    <div class="col-md-12 form-input">
                                        <label>
                                            <font color="#174082">Menu URL: </font>
                                        </label>
                                        <input type="text" class="form-control" id="editurl" placeholder="Name of Project Main Menu" name="editurl"  autocomplete="off">
                                    </div>
                                    <div class="col-md-8 form-input">
                                        <label>
                                            <font color="#174082">Menu Icons: </font>
                                        </label>
                                        <input type="text" class="form-control" id="editicons" placeholder="Name of Project Main Menu" name="editicons" required autocomplete="off">
                                    </div><!-- /form-group-->

                                        <div class="col-md-4 form-input">
                                            <label for="editStatus">
                                                <font color="#174082">Menu Status: </font>
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

<script src="general-settings/js/fetch-main-menu.js"></script>