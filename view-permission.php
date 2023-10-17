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
                                 <font size="3" color="#FFC107"><strong>Permissions</strong></font>
                             </div>
                         </td>
                         <td width="20%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF">
                             <button type="button" id="modal_button" class="pull-right btn bg-deep-purple" data-toggle="modal" id="addItemModalBtn" data-target="#addItemModal"> <i class="fa fa-plus-square"></i> Add Permission </button>
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
                                 <th style="width:5%">#</th>
                                 <th style="width:65%">Permission</th>
                                 <th style="width:10%">Tag</th>
                                 <th style="width:10%">Status</th>
                                 <th style="width:10%">Action</th>
                             </tr>
                         </thead>
                         <tbody>
                             <?php
                                $sql = $db->prepare("SELECT * FROM tbl_permissions ORDER BY `id` ASC");
                                $sql->execute();
                                $rows_count = $sql->rowCount();

                                if ($rows_count > 0) {
                                    $sn = 0;
                                    while ($row = $sql->fetch()) {
                                        $sn++;
                                        $id = $row['id'];
                                        $name = $row['name'];
                                        $phrase = $row['phrase'];
                                        $active = ($row['status'] == 1) ? "<label class='label label-success'>Enabled</label>" : "<label class='label label-danger'>Disabled</label>";

                                        $stmt = $db->prepare("SELECT * FROM tbl_permissions ORDER BY `id` ASC");
                                        $stmt->execute();
                                        $total_stmt = $stmt->rowCount();
                                ?>
                                     <tr>
                                         <td><?= $sn ?></td>
                                         <td><?= $name ?></td>
                                         <td><?= $phrase ?></td>
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
                     <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-plus"></i> Add Permission</h4>
                 </div>
                 <div class="modal-body">
                     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                             <label for="name">Name *:</label>
                             <input type="text" class="form-control" id="name" placeholder="Enter permission name" name="name" required autocomplete="off" required>
                         </div>
                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                             <label for="phrase">Tag *:</label>
                             <input type="text" class="form-control" id="phrase" placeholder="Enter permission phrase" name="phrase" required autocomplete="off" required>
                         </div>
                         <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                             <label>
                                 <font color="#174082">Status: </font>
                             </label>
                             <select name="status" id="status" class="form-control show-tick" data-live-search="false" required>
                                 <option value="" class="selection">Select Status</option>
                                 <option value="1">Enable</option>
                                 <option value="0">Disable</option>
                             </select>
                         </div>
                     </div>
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

 <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
 <script>
     // sweet alert notifications
     function success_alert(msg) {
         return swal({
             title: "Success",
             text: msg,
             type: "Success",
             icon: 'success',
             dangerMode: true,
             timer: 15000,
             showConfirmButton: false
         });
     }


     // sweet alert notifications
     function error_alert(msg) {
         return swal({
             title: "Error !!!",
             text: msg,
             type: "Error",
             icon: 'warning',
             dangerMode: true,
             timer: 15000,
             showConfirmButton: false
         });
     }
 </script>
 <script src="assets/js/settings/permission.js"></script>