<?php
require('includes/head.php');

if ($permission) {
   try {
      $query_rsriskcategory = $db->prepare("SELECT * FROM tbl_projrisk_categories");
      $query_rsriskcategory->execute();
      $totalrows_rsriskcategory = $query_rsriskcategory->rowCount();
   } catch (PDOException $ex) {
      $results = flashMessage("An error occurred: " . $ex->getMessage());
   }
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
                        <a href="add-risk-category.php" class="btn btn-primary pull-right">Add Risk Category</a>
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
                              <tr class="bg-orange">
                                 <th style="width:5%">#</th>
                                 <th style="width:80%">Risks/Assumptions Name</th>
                                 <th style="width:10%">Result Level</th>
                                 <?php
                                 if (in_array("update", $page_actions) || in_array("delete", $page_actions)) {
                                 ?>
                                    <th style="width:5%" data-orderable="false">Action</th>
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
                                    $riskid = $row_rsriskcategory['catid'];
                                    $riskcat = $row_rsriskcategory['category'];
                                    $type = explode(',', $row_rsriskcategory['type']);
                                    $risk_type = array();
                                    if (in_array(1, $type)) {
                                       $risk_type[]  = "Impact";
                                    }
                                    if (in_array(2, $type)) {
                                       $risk_type[]  = "Outcome";
                                    }
                                    if (in_array(3, $type)) {
                                       $risk_type[]  = "Output";
                                    }
                                    $risk_type = !empty($risk_type) ?  implode(',', $risk_type) : '';
                              ?>
                                    <tr style="background-color:#fff">
                                       <td align="center"><?php echo $nm; ?></td>
                                       <td><?php echo $riskcat; ?></td>
                                       <td><?php echo $risk_type; ?></td>
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
                                                         <a type="button" href="add-risk-category.php?risk=<?= base64_encode($riskid) ?>" id="addFormModalBtn">
                                                            <i class="fa fa-pencil-square"></i> </i> Edit
                                                         </a>
                                                      </li>
                                                   <?php
                                                   }
                                                   if (in_array("delete", $page_actions)) {
                                                   ?>
                                                      <li>
                                                         <a type="button" onclick="removeItem('<?php echo $riskid ?>')">
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
?>

<script>
   function removeItem(riskid) {
      var url = "ajax/issuesandrisks/risk-categories.php";
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
                     swal("Success! category has been deleted!", {
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