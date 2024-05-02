<?php
try {
    require 'includes/head.php';

    if ($permission) {

?>
        <style>
            .modal-lg {
                max-width: 100% !important;
                width: 90%;
            }
        </style>
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
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <ul class="nav nav-tabs" style="font-size:14px">
                                    <li class="active">
                                        <a data-toggle="tab" href="#home"><i class="fa fa-folder-o bg-orange" aria-hidden="true"></i> Standard Categories &nbsp;</a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#menu1"><i class="fa fa-certificate bg-light-blue" aria-hidden="true"></i> Standards&nbsp;</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="body">
                                <!-- ============================================================== -->
                                <!-- Start Page Content -->
                                <!-- ============================================================== -->

                                <div class="tab-content">
                                    <div id="home" class="tab-pane fade in active">
                                        <div class="header">
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <h4 class="contentheader">
                                                        <div class="btn-group" style="float:right">
                                                            <div class="btn-group" style="float:right">
                                                                <button type="button" id="modal_button" class="pull-right btn bg-deep-purple" data-toggle="modal" onclick="get_category_edit_details('')" id="addItemModalBtn" data-target="#addItemModal">
                                                                    <i class="fa fa-plus-square"></i> Add Standard Category
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="manageItemTable">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:5%">#</th>
                                                            <th style="width:75%">Category</th>
                                                            <th style="width:10%">Status</th>
                                                            <th style="width:10%">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $sql = $db->prepare("SELECT * FROM tbl_standard_categories  ORDER BY `category_id` ASC");
                                                        $sql->execute();
                                                        $rows_count = $sql->rowCount();
                                                        $output = array('data' => array());

                                                        if ($rows_count > 0) {
                                                            $sn = 0;
                                                            while ($row = $sql->fetch()) {
                                                                $sn++;
                                                                $category_id = $row['category_id'];
                                                                $category = $row['category'];
                                                                $active = ($row['status'] == 1) ? "<label class='label label-success'>Enabled</label>" : "<label class='label label-danger'>Disabled</label>";
                                                        ?>
                                                                <tr>
                                                                    <td><?= $sn ?></td>
                                                                    <td><?= $category ?></td>
                                                                    <td><?= $active ?></td>
                                                                    <td>
                                                                        <!-- Single button -->
                                                                        <div class="btn-group">
                                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                Options <span class="caret"></span>
                                                                            </button>
                                                                            <ul class="dropdown-menu">
                                                                                <li><a type="button" data-toggle="modal" id="editItemModalBtn" data-target="#addItemModal" onclick="get_category_edit_details(<?= $category_id ?>)"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
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

                                    <div id="menu1" class="tab-pane fade">
                                        <div class="header">
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="btn-group" style="float:right">
                                                        <div class="btn-group" style="float:right">
                                                            <button type="button" id="modal_button" class="pull-right btn bg-deep-purple" data-toggle="modal" onclick="get_edit_details('')" id="" data-target="#addStandards">
                                                                <i class="fa fa-plus-square"></i> Add Standard
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="body">
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="manageItemTable">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width:5%">#</th>
                                                                    <th style="width:12%">Code</th>
                                                                    <th style="width:45%">Title</th>
                                                                    <th style="width:20%">Category</th>
                                                                    <th style="width:10%">Status</th>
                                                                    <th style="width:8%">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $sql = $db->prepare("SELECT * FROM tbl_standards s INNER Join tbl_standard_categories c ON s.category_id = c.category_id  ORDER BY `standard_id` ASC");
                                                                $sql->execute();
                                                                $rows_count = $sql->rowCount();
                                                                $output = array('data' => array());
                                                                if ($rows_count > 0) {
                                                                    $sn = 0;
                                                                    while ($row = $sql->fetch()) {
                                                                        $sn++;
                                                                        $standard_id = $row['standard_id'];
                                                                        $code = $row['code'];
                                                                        $standard = $row['standard'];
                                                                        $description = $row['description'];
                                                                        $category = $row['category'];
                                                                        $status = $row['status'];
                                                                        $active = ($status == 1) ? "<label class='label label-success'>Enabled</label>" : "<label class='label label-danger'>Disabled</label>";
                                                                ?>
                                                                        <tr>
                                                                            <td style="width:5%"><?= $sn ?></td>
                                                                            <td style="width:12%"><?= $code ?></td>
                                                                            <td style="width:45%"><?= $standard ?></td>
                                                                            <td style="width:20%"><?= $category ?></td>
                                                                            <td style="width:10%"><?= $active ?></td>
                                                                            <td style="width:8%">
                                                                                <!-- Single button -->
                                                                                <div class="btn-group">
                                                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                        Options <span class="caret"></span>
                                                                                    </button>
                                                                                    <ul class="dropdown-menu">
                                                                                        <li><a type="button" data-toggle="modal" id="viewItemModalBtn" data-target="#viewStandards" onclick="get_view_details(<?= $standard_id ?>)"> <i class="fa fa-eye"></i> View</a></li>
                                                                                        <li><a type="button" data-toggle="modal" id="editItemModalBtn" data-target="#addStandards" onclick="get_edit_details(<?= $standard_id ?>)"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
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
                                    </div>

                                </div>


                                <!-- ============================================================== -->
                                <!-- End PAge Content -->
                                <!-- ============================================================== -->
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
                    <form class="form-horizontal" id="submitItemForm" action="general-settings/action/project-main-menu-action.php" method="POST" enctype="multipart/form-data">
                        <div class="modal-header" style="background-color:#03A9F4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-plus"></i> Add Standard Categoriess</h4>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="body">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label for="">Standard Category *:</label>
                                                <input type="text" class="form-control" id="category" placeholder="Enter Standard Category" name="category" required autocomplete="off" required>
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                <label>
                                                    <font color="#174082">Status: </font>
                                                </label>
                                                <select name="category_status" id="category_status" class="form-control show-tick" data-live-search="false" required>
                                                    <option value="" class="selection">Select Status</option>
                                                    <option value="1">Enable</option>
                                                    <option value="0">Disabled</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- /modal-body -->

                        <div class="modal-footer">
                            <div class="col-md-12 text-center">
                                <input type="hidden" name="store_category" id="store_category" value="new">
                                <input type="hidden" name="category_id" id="category_id" value="">
                                <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                            </div>
                        </div> <!-- /modal-footer -->
                    </form> <!-- /.form -->
                </div> <!-- /modal-content -->
            </div> <!-- /modal-dailog -->
        </div>
        <!-- End add item -->

        <!-- add item -->
        <div class="modal fade" id="addStandards" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form class="form-horizontal" id="submit_standard_form" action="" method="POST" enctype="multipart/form-data">
                        <div class="modal-header" style="background-color:#03A9F4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-plus"></i> Add Standards</h4>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="body">
                                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                <label>
                                                    <font color="#174082">Standard Category: </font>
                                                </label>
                                                <select name="category" id="category" class="form-control show-tick" data-live-search="false" required>
                                                    <option value="" class="selection">Select Standard Category</option>
                                                    <?php
                                                    $sql = $db->prepare("SELECT * FROM tbl_standard_categories WHERE status = 1 ORDER BY `category_id` ASC");
                                                    $sql->execute();
                                                    $row = $sql->fetch();
                                                    $rows_count = $sql->rowCount();
                                                    if ($rows_count > 0) {
                                                        do {
                                                    ?>
                                                            <option value="<?php echo $row['category_id'] ?>"><?php echo $row['category'] ?></option>
                                                    <?php
                                                        } while ($row = $sql->fetch());
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                <label>
                                                    <font color="#174082">Code *: </font>
                                                </label>
                                                <input type="text" class="form-control" id="code" placeholder="Enter Standard Code" name="code" required autocomplete="off" required>
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                <label>
                                                    <font color="#174082">Status: </font>
                                                </label>
                                                <select name="standard_status" id="standard_status" class="form-control show-tick" data-live-search="false" required>
                                                    <option value="" class="selection">Select Status</option>
                                                    <option value="1">Enable</option>
                                                    <option value="0">Disabled</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label>
                                                    <font color="#174082">Title *: </font>
                                                </label>
                                                <input type="text" class="form-control" id="title" placeholder="Enter Standard Title" name="title" required autocomplete="off" required>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label class="control-label">Description *: </font></label>
                                                <p align="left">
                                                    <textarea name="standard" cols="45" rows="5" class="txtboxes" id="standard" required="required" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"></textarea>
                                                    <script>
                                                        CKEDITOR.replace('standard', {
                                                            height: 200,
                                                            on: {
                                                                instanceReady: function(ev) {
                                                                    this.dataProcessor.writer.setRules('p', {
                                                                        indent: false,
                                                                        breakBeforeOpen: false,
                                                                        breakAfterOpen: false,
                                                                        breakBeforeClose: false,
                                                                        breakAfterClose: false
                                                                    });
                                                                    this.dataProcessor.writer.setRules('ol', {
                                                                        indent: false,
                                                                        breakBeforeOpen: false,
                                                                        breakAfterOpen: false,
                                                                        breakBeforeClose: false,
                                                                        breakAfterClose: false
                                                                    });
                                                                    this.dataProcessor.writer.setRules('ul', {
                                                                        indent: false,
                                                                        breakBeforeOpen: false,
                                                                        breakAfterOpen: false,
                                                                        breakBeforeClose: false,
                                                                        breakAfterClose: false
                                                                    });
                                                                    this.dataProcessor.writer.setRules('li', {
                                                                        indent: false,
                                                                        breakBeforeOpen: false,
                                                                        breakAfterOpen: false,
                                                                        breakBeforeClose: false,
                                                                        breakAfterClose: false
                                                                    });
                                                                }
                                                            }
                                                        });
                                                    </script>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- /modal-body -->

                        <div class="modal-footer">
                            <div class="col-md-12 text-center">
                                <input type="hidden" name="store" id="store" value="new">
                                <input type="hidden" name="standard_id" id="standard_id" value="">
                                <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                            </div>
                        </div> <!-- /modal-footer -->
                    </form> <!-- /.form -->
                </div> <!-- /modal-content -->
            </div> <!-- /modal-dailog -->
        </div>
        <!-- End add item -->

        <!-- View Standard -->
        <div class="modal fade" id="viewStandards" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-plus"></i> Standard Content</h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="body" id="standard-content">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /modal-body -->

                    <div class="modal-footer">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Close</button>
                        </div>
                    </div> <!-- /modal-footer -->
                </div> <!-- /modal-content -->
            </div> <!-- /modal-dailog -->
        </div>
        <!-- End add item -->
<?php
    } else {
        $results = restriction();
        echo $results;
    }
    require 'includes/footer.php';
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>
<script src="assets/js/settings/standard_category.js"></script>
<!-- <script src="assets/js/settings/standard.js"></script> -->