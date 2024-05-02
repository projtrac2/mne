<?php
try {
   require('includes/head.php');

   if ($permission  && isset($_GET['page_id']) & !empty($_GET['page_id'])) {
      $page_id =   $_GET['page_id'];
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
                        <button type="button" id="modal_button" class="pull-right btn bg-deep-purple" data-toggle="modal" id="addItemModalBtn" data-target="#addItemModal"> <i class="fa fa-plus-square"></i> Add Item </button>
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
                                    <th>Action</th>
                                    <th>Icons</th>
                                    <th>Role Group</th>
                                    <th>Designations</th>
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
      </section>
      <!-- end body  -->
      <!-- add item -->
      <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <form class="form-horizontal" id="submitItemForm" action="general-settings/action/project-main-menu-action.php" method="POST" enctype="multipart/form-data">
                  <?= csrf_token_html(); ?>
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
                                 <input type="hidden" name="sidebar_id" value="<?= $page_id ?>">
                                 <div class="col-md-6 form-input">
                                    <label>
                                       <font color="#174082">Action: </font>
                                    </label>
                                    <input type="text" class="form-control" id="url" placeholder="Name of Project Main Menu" name="url" required autocomplete="off">
                                 </div>
                                 <div class="col-md-6 form-input">
                                    <label>
                                       <font color="#174082">Page Icons: </font>
                                    </label>
                                    <input type="text" class="form-control" id="icons" placeholder="Name of Project Main Menu" name="icons" required autocomplete="off">
                                 </div>
                                 <div class="col-md-6 form-input">
                                    <label>
                                       <font color="#174082">Role Group: </font>
                                    </label>
                                    <select name="role_group[]" id="role_group" class="form-control show-tick selectpicker" multiple data-live-search="false">
                                       <option value="" class="selection">Choose Parent</option>
                                       <option value="1">Group 1</option>
                                       <option value="2">Group 2</option>
                                       <option value="3">Group 3</option>
                                       <option value="4">Group 4</option>
                                    </select>
                                 </div>
                                 <div class="col-md-6 form-input">
                                    <label>
                                       <font color="#174082">Designations: </font>
                                    </label>
                                    <select name="designation[]" id="designation" class="form-control show-tick selectpicker" multiple data-live-search="false">
                                       <option value="" class="selection">Choose Parent</option>
                                       <?php

                                       do {
                                       ?>
                                          <option value="<?php echo $row_rsDesignation['moid'] ?>"><?php echo $row_rsDesignation['designation'] ?></option>
                                       <?php
                                       } while ($row_rsDesignation = $query_rsDesignation->fetch());
                                       ?>
                                    </select>
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
                                       <input type="text" class="form-control" id="editurl" placeholder="Name of Project Main Menu" name="editurl" autocomplete="off">
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
                                    <div class="col-md-6 form-input">
                                       <label>
                                          <font color="#174082">Role Group: </font>
                                       </label>
                                       <select name="edit_role_group[]" id="edit_role_group" class="form-control show-tick selectpicker" multiple data-live-search="false">
                                          <option value="" class="selection">Choose Parent</option>
                                          <option value="1">Group 1</option>
                                          <option value="2">Group 2</option>
                                          <option value="3">Group 3</option>
                                          <option value="4">Group 4</option>
                                       </select>
                                    </div>
                                    <div class="col-md-6 form-input">
                                       <label>
                                          <font color="#174082">Designations: </font>
                                       </label>
                                       <select name="edit_designation[]" id="edit_designation" class="form-control show-tick selectpicker" multiple data-live-search="false">
                                          <option value="" class="selection">Choose Parent</option>
                                          <?php
                                          $query_rsDesignation =  $db->prepare("SELECT * FROM tbl_pmdesignation ORDER BY position ASC");
                                          $query_rsDesignation->execute();
                                          $row_rsDesignation = $query_rsDesignation->fetch();
                                          $totalRows_rsDesignation = $query_rsDesignation->rowCount();
                                          do {
                                          ?>
                                             <option value="<?php echo $row_rsDesignation['moid'] ?>"><?php echo $row_rsDesignation['designation'] ?></option>
                                          <?php
                                          } while ($row_rsDesignation = $query_rsDesignation->fetch());
                                          ?>
                                       </select>
                                    </div>

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
<?php
   } else {
      $results =  restriction();
      echo $results;
   }

   require('includes/footer.php');
} catch (PDOException $ex) {
   customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>
<script src="general-settings/js/fetch-page-actions.js"></script>