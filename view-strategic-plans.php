<?php
require('functions/strategicplan.php');
$pageName = "Strategic Plans";
$replacement_array = array(
   'planlabel' => "CIDP",
   'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
$pageTitle = $planlabelplural;

if ($permission) {
   try {
      $strategicPlans = get_strategic_plans();
      $currentPlan = get_current_strategic_plan();
      $planyrs = 1;
      $eyear = date("Y");
      if ($currentPlan) {
         $planyrs = $currentPlan["years"];
         $syear = $currentPlan["starting_year"];
         $eyear = $syear + $planyrs;
      }
   } catch (PDOException $ex) {
      $result = flashMessage("An error occurred: " . $ex->getMessage());
   }
?>

   <!-- start body  -->
   <section class="content">
      <div class="container-fluid">
         <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
            <h4 class="contentheader">
               <i class="fa fa-columns" aria-hidden="true"></i>
               <?php echo $pageTitle ?>
               <div class="btn-group" style="float:right">
                  <div class="btn-group" style="float:right">
                     <?php
                     if ($file_rights->add) {
                     ?>
                        <a href="add-strategic-plan.php" class="btn btn-success" style="height:27px; ; margin-top:-1px; vertical-align:center">
                           Add New <?= $planlabel ?>
                        </a>
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
                     <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="manageItemTable">
                           <thead>
                              <tr id="colrow">
                                 <th style="width:3%"><strong> # </strong></th>
                                 <th style="width:20%"><strong><?= $planlabel ?></strong></th>
                                 <th style="width:31%"><strong>Vision</strong></th>
                                 <th style="width:31%"><strong>Mission</strong></th>
                                 <th style="width:8%"><strong>Status</strong></th>
                                 <?php
                                 if ($file_rights->edit && $file_rights->delete_permission) {
                                 ?>
                                    <th style="width:7%" data-orderable="false"><strong>Action</strong></th>
                                 <?php
                                 }
                                 ?>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              if ($strategicPlans) {
                                 $sn = 0;
                                 foreach ($strategicPlans as  $strategicPlan) {
                                    $sn = $sn + 1;
                                    $planid = $strategicPlan['id'];
                                    $strplanid = base64_encode($strategicPlan['id']);
                                    $plan = $strategicPlan['plan'];
                                    $starting_year = $strategicPlan['starting_year'];
                                    $vision = $strategicPlan['vision'];
                                    $mission = $strategicPlan['mission'];
                                    $active = $strategicPlan['current_plan'];
                                    if ($active == 1) {
                                       $status = "<label class='label label-success'>Active</label>";
                                    } else if ($active == 2) {
                                       $status = "<label class='label label-warning'>Implemented</label>";
                                    } else {
                                       $status = "<label class='label label-primary'>Pending</label>";
                                    }
                              ?>
                                    <tr style="border-bottom:thin solid #EEE">
                                       <td><?php echo $sn; ?></td>
                                       <td>
                                          <a href="view-strategic-plan-framework.php?plan=<?php echo $strplanid; ?>" style="color:blue" title="More Details"><strong><?php echo $plan; ?></strong></a>
                                       </td>
                                       <td><?php echo $vision; ?></td>
                                       <td><?php echo $mission; ?></td>
                                       <td><?php echo $status; ?></td>
                                       <?php
                                       if ($file_rights->edit && $file_rights->delete_permission) {
                                       ?>
                                          <td>
                                             <?php
                                             if ($active == 0) {
                                             ?>
                                                <div class="btn-group">
                                                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" onchange="checkBoxes()" aria-haspopup="true" aria-expanded="false">
                                                      Options <span class="caret"></span>
                                                   </button>
                                                   <ul class="dropdown-menu">
                                                      <li>
                                                         <a type="button" id="editstrategicplan" href="edit-strategic-plan.php?stplan=<?= base64_encode($planid) ?>">
                                                            <i class="fa fa-plus-square"></i> Edit
                                                         </a>
                                                      </li>
                                                      <li>
                                                         <a type="button" id="editstrategicplan" onclick="delete_plan(<?= $planid ?>)">
                                                            <i class="fa fa-plus-square"></i> Delete
                                                         </a>
                                                      </li>
                                                   </ul>
                                                </div>
                                             <?php
                                             }
                                             ?>
                                          </td>
                                       <?php
                                       }
                                       ?>
                                    </tr>
                              <?php }
                              }
                              ?>
                           </tbody>
                        </table>
                     </div>
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
   function delete_plan(planid) {
      swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover the data!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
         })
         .then((willDelete) => {
            if (willDelete) {
               swal("Strategic plan has been successfully deleted", {
                  icon: "success",
               });
            } else {
               swal("You have canceled the action!");
            }
         });
   }
</script> -->
