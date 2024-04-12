<?php
try {
require('includes/head.php');

if ($permission) { 
      $query_rsriskcategory = $db->prepare("SELECT * FROM tbl_projrisk_categories");
      $query_rsriskcategory->execute();
      $totalrows_rsriskcategory = $query_rsriskcategory->rowCount();
   
?>

   <!-- start body  -->
   <section class="content">
      <div class="container-fluid">
         <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
            <h4 class="contentheader">
               <?= $icon ?>
               <?= $pageTitle ?>
               <div class="btn-group" style="float:right">
                  <div class="btn-group" style="float:right">
                     <?php
                     if (in_array("create", $page_actions)) {
                     ?>
                        <a href="add-risk-category.php" class="btn btn-primary pull-right">Add New Category</a>
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
                     <!-- start body -->
                     <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                           <thead>
                              <tr id="colrow">
                                 <th style="width:5%">#</th>
                                 <th style="width:80%">Risk/Issue Category</th>
                                 <?php
                                 if (in_array("update", $page_actions) || in_array("delete", $page_actions)) {
                                 ?>
                                    <th style="width:15%" data-orderable="false">Action</th>
                                 <?php
                                 }
                                 ?>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              if ($totalrows_rsriskcategory > 0) {
                                 $nm = 0;
                                 while ($row_rsriskcategory = $query_rsriskcategory->fetch()) {
                                    $nm = $nm + 1;
                                    $catid = $row_rsriskcategory['catid'];   
                                    $category = $row_rsriskcategory['category'];                                
									?>
                                    <tr style="background-color:#fff">
                                       <td align="center"><?php echo $nm; ?></td>
                                       <td><?php echo $category; ?></td>
                                       <?php
                                       if (in_array("update", $page_actions) || in_array("edit", $page_actions)) {
                                       ?>
                                          <td>
                                             <div class="btn-group">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                   Options <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                   <?php
                                                   if (in_array("update", $page_actions)) {
                                                   ?>
                                                      <li>
                                                         <a type="button" href="add-risk-category.php?risk=<?= base64_encode($catid) ?>" id="addFormModalBtn">
                                                            <i class="fa fa-pencil-square"></i> </i> Edit
                                                         </a>
                                                      </li>
                                                   <?php
                                                   }
                                                   if (in_array("delete", $page_actions)) {
                                                   ?>
                                                      <li>
                                                         <a type="button" onclick="removeItem('<?php echo $catid ?>')">
                                                            <i class="fa fa-trash-o"></i> Delete
                                                         </a>
                                                      </li>
                                                   <?php
                                                   }
                                                   ?>
                                                </ul>
                                             </div>
                                          </td>
                                       <?php
                                       }
                                       ?>
                                    </tr>
                              <?php
                                 }
                              }
                              ?>
                           </tbody>
                        </table>
                     </div>
                     <!-- end body -->
                  </div>
               </div>
            </div>
         </div>
   </section>
   <!-- end body  -->
<?php
} else {
   $results =  restriction();
   echo $results;
}
require('includes/footer.php');

} catch (PDOException $th) {
   customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>

<script>
   function removeItem(riskid) {
      var url = "ajax/issuesandrisks/risk-categories";
      swal({
         title: "Are you sure?",
         text: "Once deleted, you will not be able to recover!",
         icon: "warning",
         buttons: true,
         dangerMode: true,
      }).then((willDelete) => {
         if (willDelete) {
            $.ajax({
               type: "post",
               url: url,
               data: {
                  delete: "delete",
                  riskid: riskid
               },
               dataType: "json",
               success: function(response) {
                  if (response.success) {
                     swal("Successful, category has been deleted!", {
                        icon: "success",
                     });
                     setTimeout(() => {
                        location.reload(true);
                     }, 3000);
                  } else {
                     swal("Error! Could not delete category!", {
                        icon: "error",
                     });
                     setTimeout(() => {
                        location.reload(true);
                     }, 3000);
                  }
               }
            });
         } else {
            swal("You have cancelled the action!", {
               icon: "error",
            });
         }
      });
   }
</script>