    <?php 

    try {
        //code...
    

    ?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
                        <tr>
                            <td width="80%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF">
                                <div align="left" style="vertical-align: text-bottom">
                                    <font size="3" color="#FFF"><i class="fa fa-globe" aria-hidden="true"></i></font>
                                    <font size="3" color="#FFC107"><strong>Designations</strong></font>
                                </div>
                            </td>
                            <td width="20%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF">
                                <button type="button" id="modal_button" class="pull-right btn bg-deep-purple" data-toggle="modal" id="addItemModalBtn" data-target="#addItemModal"> <i class="fa fa-plus-square"></i> Add Item </button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                            <tr>
                                <th width="3%">#</th>
                                <th width="44%">Designation</th>
                                <th width="43%">Reporting</th>
                                <th width="43%">Status</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = $db->prepare("SELECT * FROM tbl_pmdesignation ORDER BY `moid` ASC");
                            $sql->execute();
                            $rows_count = $sql->rowCount();
                            $output = array('data' => array());
                            if ($rows_count > 0) {
                                $active = "";
                                $sn = 0;
                                while ($row = $sql->fetch()) {
                                    $sn++;
                                    $moid = $row['moid'];
                                    $designation = $row['designation'];
                                    $reportingid = $row['Reporting'];
                                    $status = $row['active'];
                                    if ($reportingid == $moid) {
                                        $reporting = "N/A";
                                    } else {
                                        $sqlquery = $db->prepare("SELECT * FROM tbl_pmdesignation where `moid`= '$reportingid'");
                                        $sqlquery->execute();
                                        $rowdesignation = $sqlquery->fetch();
                                        $reporting = $rowdesignation ? $rowdesignation["designation"] : "N/A";
                                    }
                                    $active = ($row['active'] == 1) ? "<label class='label label-success'>Enabled</label>" : "<label class='label label-danger'>Disabled</label>";

                                    $stmt = $db->prepare("SELECT * FROM tbl_designation_permissions WHERE designation_id=:designation_id ORDER BY `id` ASC");
                                    $stmt->execute(array(":designation_id" => $moid));
                                    $total_stmt = $stmt->rowCount();
                                    $edit = $total_stmt > 0 ? 1 : 0;
                                    if ($status == 1) {
                                        $wordings = 'disable';
                                        $wordingsCapital = 'Disable';
                                    } else {
                                        $wordings = 'enable';
                                        $wordingsCapital = 'Enable';
                                    }
                            ?>
                                    <tr>
                                        <td><?= $sn ?></td>
                                        <td><?= $designation ?></td>
                                        <td><?= $reporting ?></td>
                                        <td><?php if ($status == 1) { ?> <label class='label label-success'>Enabled</label> <?php } else { ?> <label class='label label-danger'>Disabled</label> <?php } ?></td>
                                        <td>
                                            <!-- Single button -->
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Options <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a type="button" data-toggle="modal" id="editItemModalBtn1" data-target="#addPermissionModal" onclick="get_edit_details(<?= $moid ?>, <?= $edit ?>)">
                                                            <i class="glyphicon glyphicon-edit"></i> <?= $total_stmt == 0 ? "Add" : "Edit" ?> Designations Permission
                                                        </a>
                                                    </li>
                                                    <li><a type="button" data-toggle="modal" id="editItemModalBtn" data-target="#editItemModal" onclick="editItem(<?= $moid ?>)"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
                                                    <!-- <li><a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="removeItem()"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li> -->
                                                    <li>
                                                    <a type="button" id="disableBtn" class="disableBtn" onclick="disable(<?=$moid?>, '<?= $designation ?>', '<?=$wordings?>')">
                                                        <i class="glyphicon glyphicon-trash"></i><?= $wordingsCapital ?>
                                                    </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>

                            <?php
                                } // /while 
                            } // if num_rows


                            ?>
                        </tbody>
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
                <form class="form-horizontal" id="submitItemForm" action="general-settings/action/designation-action.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-plus"></i> Add Desgnation</h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="body">
                                        <div id="add-item-messages"></div>
                                        <div class="col-md-4 form-input">
                                            <label>
                                                <font color="#174082">Designation: </font>
                                            </label>
                                            <input type="text" class="form-control" id="designation" placeholder="Enter designation" name="designation" required autocomplete="off">
                                        </div>
                                        <div class="col-md-4 form-input">
                                            <label>
                                                <font color="#174082"> Reporting to/manager : </font>
                                            </label>
                                            <div class="form-line">
                                                <select name="reporting" id="reporting" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                    <option value="">.... Select Reporting to/manager ....</option>
                                                    <?php
                                                    $query_designationmanager = $db->prepare("SELECT * FROM tbl_pmdesignation WHERE active = 1 ORDER BY `Reporting` ASC");
                                                    $query_designationmanager->execute();
                                                    $row_designationmanager = $query_designationmanager->fetch();
                                                    do {
                                                    ?>
                                                        <option value="<?php echo $row_designationmanager['moid'] ?>"><?php echo $row_designationmanager['designation'] ?></option>
                                                    <?php
                                                    } while ($row_designationmanager = $query_designationmanager->fetch());
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- /form-group-->
                                        <div class="col-md-4 form-input">
                                            <label>
                                                <font color="#174082"> Access Levels : </font>
                                            </label>
                                            <div class="form-line">
                                                <select name="level" id="level" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                    <option value="">.... Select Access Levels ....</option>
                                                    <option value="0">Level 1</option>
                                                    <option value="1">Level 2</option>
                                                    <option value="2">Level 3</option>
                                                </select>
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
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Edit Designation</h4>
                </div>
                <div class="modal-body" style="max-height:450px; overflow:auto;">
                    <div class="card">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="body">
                                    <div class="div-result">
                                        <form class="form-horizontal" id="editItemForm" action="general-settings/action/designation-action.php" method="POST">
                                            <br />
                                            <div class="col-md-12 id=" edit-product-messages"></div>
                                            <div class="col-md-4 form-input">
                                                <label>
                                                    <font color="#174082">Designation: </font>
                                                </label>
                                                <input type="text" class="form-control" id="editDesignation" placeholder="Enter designation" name="designation" required autocomplete="off">
                                            </div>
                                            <div class="col-md-4 form-input">
                                                <label>
                                                    <font color="#174082"> Reporting to/manager : </font>
                                                </label>
                                                <div class="form-line">
                                                    <select name="reporting" id="editReporting" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required autocomplete="off">
                                                        <option value="">.... Select Reporting to/manager ....</option>
                                                        <?php
                                                        $query_designationmanager = $db->prepare("SELECT * FROM tbl_pmdesignation WHERE active = 1 ORDER BY `Reporting` ASC");
                                                        $query_designationmanager->execute();
                                                        $row_designationmanager = $query_designationmanager->fetch();
                                                        do {
                                                        ?>
                                                            <option value="<?php echo $row_designationmanager['moid'] ?>"><?php echo $row_designationmanager['designation'] ?></option>
                                                        <?php
                                                        } while ($row_designationmanager = $query_designationmanager->fetch());
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 form-input">
                                                <label>
                                                    <font color="#174082"> Access Levels : </font>
                                                </label>
                                                <div class="form-line">
                                                    <select name="level" id="editLevel" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                        <option value="">.... Select Access Levels ....</option>
                                                        <option value="0">Level 1</option>
                                                        <option value="1">Level 2</option>
                                                        <option value="2">Level 3</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- /form-group-->
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



    <!-- add item -->
    <div class="modal fade" id="addPermissionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form class="form-horizontal" id="submitPermissionForm" action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-plus"></i> Add Permission</h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Page Permissions</legend>
                                <?php
                                $query_rspermission =  $db->prepare("SELECT * FROM tbl_permissions ORDER BY id ASC");
                                $query_rspermission->execute();
                                $totalRows_rsPermissions = $query_rspermission->rowCount();
                                if ($totalRows_rsPermissions > 0) {
                                    while ($row_rspermission = $query_rspermission->fetch()) {
                                        $permission = $row_rspermission['name'];
                                        $permission_id = $row_rspermission['id'];
                                ?>
                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                            <div class="form-line">
                                                <input name="permission[]" type="checkbox" value="<?= $permission_id ?>" id="permission<?= $permission_id ?>" class="with-gap radio-col-green permission" />
                                                <label for="permission<?= $permission_id ?>"><?= $permission ?></label>
                                            </div>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                            </fieldset>
                        </div>
                    </div> <!-- /modal-body -->
                    <div class="modal-footer">
                        <div class="col-md-12 text-center">
                            <input type="hidden" name="store_designation" id="store_designation" value="new">
                            <input type="hidden" name="designation_id" id="designation_id" value="">
                            <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                        </div>
                    </div> <!-- /modal-footer -->
                </form> <!-- /.form -->
            </div> <!-- /modal-content -->
        </div> <!-- /modal-dailog -->
    </div>
    <!-- End add item -->
    <?php
        } catch (\PDOException $th) {
            customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
        }
    ?>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="general-settings/js/fetch-designation.js"></script>
    <script src="assets/js/settings/designation.js"></script>