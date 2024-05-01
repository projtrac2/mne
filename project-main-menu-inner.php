 <!-- start body  -->

 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
     <div class="card">
         <div class="header">
             <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
                     <tr>
                         <td width="80%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF">
                             <div align="left" style="vertical-align: text-bottom">
                                 <font size="3" color="#FFF"><i class="fa fa-globe" aria-hidden="true"></i></font>
                                 <font size="3" color="#FFC107"><strong>Main Menu</strong></font>
                             </div>
                         </td>
                         <td width="20%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF">
                             <button type="button" id="modal_button" class="pull-right btn bg-deep-purple" data-toggle="modal" id="addItemModalBtn" data-target="#addItemModal"> <i class="fa fa-plus-square"></i> Add Menu </button>
                         </td>
                     </tr>
                 </table>
             </div>
         </div>
         <div class="body">
             <!-- ============================================================== -->
             <!-- Start Page Content -->
             <!-- ============================================================== -->
             <div class="body">
                 <div class="table-responsive">
                     <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                         <thead>
                             <tr>
                                 <th width="5%">#</th>
                                 <th width="20%">Parent</th>
                                 <th width="20%">Name</th>
                                 <th width="30%">URL</th>
                                 <th width="10%">Icon</th>
                                 <th width="10%">Status</th>
                                 <th width="5%">Action</th>
                             </tr>
                         </thead>
                         <tbody>
                             <?php
                                $sql = $db->prepare("SELECT * FROM tbl_pages ORDER BY `id` ASC");
                                $sql->execute();
                                $rows_count = $sql->rowCount();

                                if ($rows_count > 0) {
                                    $sn = 0;
                                    while ($row = $sql->fetch()) {
                                        $sn++;
                                        $id = $row['id'];
                                        $parent = $row['parent'];
                                        $name = $row['name'];
                                        $url = $row['url'];
                                        $icon = $row['icon'];
                                        $active = ($row['status'] == 1) ? "<label class='label label-success'>Enabled</label>" : "<label class='label label-danger'>Disabled</label>";

                                        $stmt = $db->prepare("SELECT * FROM tbl_pages WHERE id=:parent ORDER BY `id` ASC");
                                        $stmt->execute(array(":parent" => $parent));
                                        $rows_stmt = $stmt->fetch();
                                        $total_stmt = $stmt->rowCount();
                                        $parent_name = $total_stmt > 0 ? $rows_stmt['name'] : 'N/A';

                                        if ($row['status']) {
                                            $wordings = 'disable';
                                            $wordingsCapital = 'Disable';
                                        } else {
                                            $wordings = 'enable';
                                            $wordingsCapital = 'Enable';
                                        }
                                ?>
                                     <tr>
                                         <td><?= $sn ?></td>
                                         <td><?= $parent_name ?></td>
                                         <td><?= $name ?></td>
                                         <td><?= $url ?></td>
                                         <td style="background-color:grey" class="text-center"><?= $icon ?></td>
                                         <td><?= $active ?></td>
                                         <td>
                                             <!-- Single button -->
                                             <div class="btn-group">
                                                 <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                     Options <span class="caret"></span>
                                                 </button>
                                                 <ul class="dropdown-menu">
                                                     <li>
                                                         <a type="button" data-toggle="modal" id="editItemModalBtn" data-target="#addItemModal" onclick="get_edit_details(<?= $id ?>)">
                                                             <i class="glyphicon glyphicon-edit"></i> Edit
                                                         </a>
                                                     </li>
                                                     <li>
                                                        <a type="button" id="disableBtn" class="disableBtn" onclick="disable(<?=$id?>, '<?= $name ?>', '<?=$wordings?>')">
                                                            <i class="glyphicon glyphicon-trash"></i><?= $wordingsCapital ?>
                                                        </a>
                                                    </li>
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
             <!-- ============================================================== -->
             <!-- End PAge Content -->
             <!-- ============================================================== -->
         </div>
     </div>
 </div>
 <!-- end body  -->

 <!-- add item -->
 <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <form class="form-horizontal" id="submitItemForm" action="" method="POST" enctype="multipart/form-data">
                 <div class="modal-header" style="background-color:#03A9F4">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-plus"></i> Add Menu Detaills</h4>
                 </div>
                 <div class="modal-body">
                     <fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
                         <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Menu Details</legend>
                         <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                             <div class="form-input">
                                 <label for="name"> Name *: </label>
                                 <input type="text" class="form-control" id="name" placeholder="Enter page name" name="name" required autocomplete="off">
                             </div>
                         </div>
                         <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                             <div class="form-input">
                                 <label for="url"> URL *: </label>
                                 <input type="text" class="form-control" id="url" placeholder="Enter page url" name="url" autocomplete="off" required>
                             </div>
                         </div>
                         <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                             <div class="form-input">
                                 <label for="icon"> Icon *: </label>
                                 <input type="text" class="form-control" id="icon" placeholder="Enter page icon" name="icon" required autocomplete="off">
                             </div>
                         </div>
                         <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                             <div class="form-input">
                                 <label for="parent"> Parent (Select if applicable): </label>
                                 <select name="parent" id="parent" class="form-control show-tick" onchange="get_child()" style="border:#CCC thin solid; border-radius:5px" data-live-search="true">
                                     <option value="">.... Select Parent ....</option>
                                     <?php
                                        $sql = $db->prepare("SELECT * FROM tbl_pages WHERE parent=0 ORDER BY `id` ASC");
                                        $sql->execute();
                                        $rows_count = $sql->rowCount();
                                        if ($rows_count > 0) {
                                            $sn = 0;
                                            while ($row = $sql->fetch()) {
                                        ?>
                                             <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                                     <?php
                                            }
                                        }
                                        ?>
                                 </select>
                             </div>
                         </div>
                         <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" id="child_div">
                             <div class="form-input">
                                 <label for="child"> Child (Select if applicable): </label>
                                 <select name="child" id="child" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true">
                                     <option value="">.... Select Parent First ....</option>
                                 </select>
                             </div>
                         </div>
                         <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                             <div class="form-input">
                                 <label for="priority"> Priority *: </label>
                                 <input type="text" class="form-control" id="priority" placeholder="Enter page icon" name="priority" required autocomplete="off">
                             </div>
                         </div>
                         <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                             <div class="form-input">
                                 <label for="workflow_stage"> Workflow Stage (Select if applicable): </label>
                                 <select name="workflow_stage" id="workflow_stage" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true">
                                     <option value="">.... Select Workflow ....</option>
                                     <?php
                                        $sql = $db->prepare("SELECT * FROM tbl_project_workflow_stage ORDER BY `id` ASC");
                                        $sql->execute();
                                        $rows_count = $sql->rowCount();
                                        if ($rows_count > 0) {
                                            $sn = 0;
                                            while ($row = $sql->fetch()) {
                                        ?>
                                             <option value="<?php echo $row['priority'] ?>"><?php echo $row['stage'] ?></option>
                                     <?php
                                            }
                                        }
                                        ?>
                                 </select>
                             </div>
                         </div>
                     </fieldset>
                     <fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
                         <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Page Sectors (Select if required)</legend>
                         <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                             <label><?= $ministrylabel ?>*:</label>
                             <div class="form-line">
                                 <select name="department_id" id="department_id" onchange="get_sections()" class="form-control show-tick" false style="border:#CCC thin solid; border-radius:5px">
                                     <option value="" selected="selected" class="selection">....Select <?= $ministrylabel ?>....</option>
                                     <?php
                                        $query_rsDepartments =  $db->prepare("SELECT * FROM tbl_sectors WHERE parent =0 ORDER BY stid ASC");
                                        $query_rsDepartments->execute();
                                        $totalRows_rsDepartments = $query_rsDepartments->rowCount();
                                        if ($totalRows_rsDepartments > 0) {
                                            while ($row_rsDepartment = $query_rsDepartments->fetch()) {
                                                $sector = $row_rsDepartment['sector'];
                                                $sector_id = $row_rsDepartment['stid'];
                                        ?>
                                             <option value="<?= $sector_id ?>"><?= $sector ?></option>
                                     <?php
                                            }
                                        }
                                        ?>
                                 </select>
                             </div>
                         </div>
                         <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                             <label><?= $departmentlabel ?>*:</label>
                             <div class="form-line" id="">
                                 <select name="sector_id" id="sector_id" onchange="get_directorate()" class="form-control show-tick" false style="border:#CCC thin solid; border-radius:5px">
                                     <option value="" selected="selected" class="selection">....Select <?= $ministrylabel ?> first....</option>
                                 </select>
                             </div>
                         </div>
                         <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                             <label><?= $directoratelabel ?>*:</label>
                             <div class="form-line" id="">
                                 <select name="directorate_id" id="directorate_id" class="form-control show-tick" false style="border:#CCC thin solid; border-radius:5px">
                                     <option value="" selected="selected" class="selection">....Select <?= $directoratelabel ?> first....</option>
                                 </select>
                             </div>
                         </div>
                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="read_access" style="margin-top: 10px;">
                             <div class="form-line">
                                 <input name="allow_read" type="checkbox" value="1" id="allow_read" class="with-gap radio-col-green allow_read" />
                                 <label for="allow_read">Allow read access to other designations who are not from this department ?</label>
                             </div>
                         </div>
                     </fieldset>
                     <fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
                         <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Page Designations (Select at least one designations that will access the page *)</legend>
                         <?php
                            $query_rsDesignation =  $db->prepare("SELECT * FROM tbl_pmdesignation ORDER BY position ASC");
                            $query_rsDesignation->execute();
                            $totalRows_rsDesignation = $query_rsDesignation->rowCount();
                            if ($totalRows_rsDesignation > 0) {
                                while ($row_rsDesignation = $query_rsDesignation->fetch()) {
                                    $designation = $row_rsDesignation['designation'];
                                    $designation_id = $row_rsDesignation['moid'];
                                    if ($designation_id == 1) {
                            ?>
                                     <input type="hidden" name="designation_id[]" value="<?= $designation_id ?>">
                                 <?php
                                    } else {
                                    ?>
                                     <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                         <div class="form-line">
                                             <input name="designation_id[]" type="checkbox" value="<?= $designation_id ?>" id="designation<?= $designation_id ?>" class="with-gap radio-col-green designation" />
                                             <label for="designation<?= $designation_id ?>"><?= $designation ?></label>
                                         </div>
                                     </div>
                         <?php
                                    }
                                }
                            }
                            ?>
                     </fieldset>
                     <fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
                         <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Page actions: (Select applicable page actions)</legend>
                         <?php
                            $query_rspermission =  $db->prepare("SELECT * FROM tbl_permissions ORDER BY id ASC");
                            $query_rspermission->execute();
                            $totalRows_rsPermissions = $query_rspermission->rowCount();
                            if ($totalRows_rsPermissions > 0) {
                                while ($row_rspermission = $query_rspermission->fetch()) {
                                    $permission = $row_rspermission['name'];
                                    $phrase = $row_rspermission['phrase'];
                                    $permission_id = $row_rspermission['id'];
                                    if ($phrase == "read") {
                            ?>
                                     <input type="hidden" name="permission[]" value="<?= $permission_id ?>">
                                 <?php
                                    } else {
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
                            }
                            ?>
                     </fieldset>
                 </div>
                 <!-- /modal-body -->
                 <div class="modal-footer">
                     <div class="col-md-12 text-center">
                         <input type="hidden" name="store" id="store" value="new">
                         <input type="hidden" name="id" id="id" value="">
                         <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                         <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                     </div>
                 </div> <!-- /modal-footer -->
             </form> <!-- /.form -->
         </div> <!-- /modal-content -->
     </div> <!-- /modal-dailog -->
 </div>
 <!-- End add item -->
 <script src="assets/js/settings/main-menu/main-menu.js" defer></script>