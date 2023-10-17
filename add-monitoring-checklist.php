<?php
require('includes/head.php');
if ($permission) {
  try {

    if (isset($_GET['projid'])) {
      $projid = $_GET['projid'];
    }
    // add-project-monitoring-checklist
    // add-project-monitoring-checkist

    if (isset($_POST['projid']) && isset($_POST['stage_set'])) {
      $projid = $_POST['projid'];
      $query_rsProjects = $db->prepare("SELECT *  FROM tbl_projects WHERE deleted='0' and projid=:projid");
      $query_rsProjects->execute(array(":projid" => $projid));
      $row_rsProjects = $query_rsProjects->fetch();
      $totalRows_rsProjects = $query_rsProjects->rowCount();

      $projstage = $row_rsProjects['projstage'] + 1;
      $insertSQL = $db->prepare("UPDATE tbl_projects SET  projstage = :projstage WHERE  projid = :projid");
      $results  = $insertSQL->execute(array(":projstage" => $projstage, ":projid" => $projid));

      if ($results) {
        $msg = 'The successfully updated stage.';
        $results =
          "<script type=\"text/javascript\">
              swal({
              title: \"Success!\",
              text: \" $msg\",
              type: 'Success',
              icon: 'success',
              timer: 2000, 
              showConfirmButton: false });
              setTimeout(function(){
                      window.location.href = 'view-mne-plan';
                  }, 2000);
          </script>";
      } else {
        $type = 'error';
        $msg = 'Error updating the stage!!';

        $results = "
          <script type=\"text/javascript\">
              swal({
              title: \"Error!\",
              text: \" $msg \",
              type: 'Danger',
              icon: 'error',
              timer: 10000,
              showConfirmButton: false });
          </script>";
      }
    }

    $query_rsProjects = $db->prepare("SELECT *  FROM tbl_projects WHERE deleted='0' and projid='$projid'");
    $query_rsProjects->execute();
    $row_rsProjects = $query_rsProjects->fetch();
    $totalRows_rsProjects = $query_rsProjects->rowCount();

    $projstage  = $row_rsProjects['projstage'];
    $query_rsOutputs = $db->prepare("SELECT p.output as  output, o.id as opid, p.indicator FROM tbl_project_details o INNER JOIN tbl_progdetails p ON p.id = o.outputid WHERE projid='$projid' ");
    $query_rsOutputs->execute();
    $row_rsOutputs = $query_rsOutputs->fetch();
    $totalRows_rsOutputs = $query_rsOutputs->rowCount();

    $query_rsTask_info = $db->prepare("SELECT *  FROM tbl_task WHERE projid=:projid");
    $query_rsTask_info->execute(array(":projid" => $projid));
    $row_rsTask_info = $query_rsTask_info->fetch();
    $totalRows_rsTask_info = $query_rsTask_info->rowCount();

    $result = array();
    if ($totalRows_rsTask_info > 0) {
      do {
        $taskid = $row_rsTask_info['tkid'];
        $query_rsChecklist = $db->prepare("SELECT *  FROM tbl_project_monitoring_checklist WHERE  taskid=:taskid");
        $query_rsChecklist->execute(array(":taskid" => $taskid));
        $row_rsChecklist = $query_rsChecklist->fetch();
        $totalRows_rsChecklist = $query_rsChecklist->rowCount();
        $result[] = $totalRows_rsChecklist > 0 ? true : false;
      } while ($row_rsTask_info = $query_rsTask_info->fetch());
    }
  } catch (PDOException $ex) {
    // $result = flashMessage("An error occurred: " .$ex->getMessage());
    print($ex->getMessage());
  }
?>
  <style media="screen">
    #links a {
      color: #FFFFFF;
      text-decoration: none;
    }

    hr {
      display: block;
      margin-top: 0.5em;
      margin-bottom: 0.5em;
      margin-left: auto;
      margin-right: auto;
      border-style: inset;
      border-width: 1px;
    }

    @media (min-width: 1200px) {
      .modal-lg {
        width: 90%;
      }
    }
  </style>

  <!-- start body  -->
  <section class="content">
    <div class="container-fluid">
      <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
        <h4 class="contentheader">
          <?= $icon ?>
          <?php echo $pageTitle ?>
          <?php
          if (!in_array(false, $result)) {
          ?>
            <div class="btn-group" style="float:right">
              <form method="post" action="">
                <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                <input type="hidden" name="stage_set" id="stage_set" value="8">
                <button class="btn btn-warning pull-right" id="nextT" type="submit">After adding checklist click here to proceed</button>
              </form>
            </div>
          <?php
          }
          ?>
          <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
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
                <table class="table table-bordered table-striped table-hover  " id="">
                  <thead>
                    <tr class="bg-light-blue">
                      <th style="width:2%"></th>
                      <th style="width:5%">#</th>
                      <th style="width:45%">Output</th>
                      <th style="width:48%" colspan="2">Indicator </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if ($totalRows_rsOutputs > 0) {
                      $Ocounter = 0;
                      do {
                        $Ocounter++;
                        $outputid = $row_rsOutputs['opid'];
                        $indid = $row_rsOutputs['indicator'];

                        $query_rsMilestones = $db->prepare("SELECT *  FROM tbl_milestone WHERE projid=:projid AND outputid=:outputid ");
                        $query_rsMilestones->execute(array(":projid" => $projid, ":outputid" => $outputid));
                        $row_rsMilestones = $query_rsMilestones->fetch();
                        $totalRows_rsMilestones = $query_rsMilestones->rowCount();

                        //get indicator
                        $query_rsIndicator = $db->prepare("SELECT *  FROM tbl_indicator WHERE indid='$indid' ");
                        $query_rsIndicator->execute();
                        $row_rsIndicator = $query_rsIndicator->fetch();
                        $totalRows_rsIndicator = $query_rsIndicator->rowCount();
                    ?>
                        <tr class="outputs" style="background-color:#eff9ca">
                          <td id="outputs<?php echo $outputid ?>" class="mb-0" data-toggle="collapse" data-target=".output<?php echo $outputid ?>">
                            <button class="btn btn-link " title="Click once to expand and Click twice to Collapse!!">
                              <i class="fa fa-plus-square" style="font-size:16px"></i>
                            </button>

                          </td>
                          <td><?= $Ocounter ?></td>
                          <td><?php echo  $row_rsOutputs['output'] ?></td>
                          <td colspan="2"><?php echo $row_rsIndicator['indicator_name'] ?> </td>
                        </tr>
                        <tr class="collapse output<?php echo $outputid ?>" data-parent="outputs<?php echo $outputid ?>" style="background-color:#FF9800; color:#FFF">
                          <th style="width: 2%"></th>
                          <th style="width: 2%">#</th>
                          <th colspan="3" style="width: 96%">Milestone Name</th>
                        </tr>
                        <?php
                        if ($totalRows_rsMilestones > 0) {
                          $mcounter = 0;
                          do {
                            $mcounter++;
                            $milestone = $row_rsMilestones['msid'];
                            $query_rsTasks = $db->prepare("SELECT *  FROM tbl_task WHERE projid='$projid' and msid='$milestone' ");
                            $query_rsTasks->execute();
                            $row_rsTasks = $query_rsTasks->fetch();
                            $totalRows_rsTasks = $query_rsTasks->rowCount();
                        ?>
                            <tr class="collapse output<?php echo $outputid ?>" data-parent="outputs<?php echo $outputid ?>" style="background-color:#CDDC39">
                              <td class="mb-0" data-toggle="collapse" data-target=".milestone<?php echo $milestone  ?>">
                                <button class="btn btn-link mile_class<?php echo $outputid ?>" title="Click once to expand and Click twice to Collapse!!">
                                  <i class="more-less fa fa-plus-square" style="font-size:16px"></i>
                                </button>
                              </td>
                              <td> <?php echo   $Ocounter . "." . $mcounter ?></td>
                              <td colspan="3"><?php echo $row_rsMilestones['milestone'] ?></td>
                            </tr>
                            <tr class="collapse milestone<?php echo $milestone  ?>" data-parent="outputs<?php echo $outputid ?>" style="background-color:#b8f9cb; color:#FFF">
                              <th style="width: 2%"></th>
                              <th style="width: 2%">#</th>
                              <th colspan="2" style="width: 86%">Task Name</th>
                              <th style="width: 10%">Action</th>
                            </tr>

                            <?php
                            $tcounter = 0;
                            if ($totalRows_rsTasks > 0) {
                              do {
                                $tcounter++;
                                $taskid = $row_rsTasks['tkid'];
                                $query_rsChecklist = $db->prepare("SELECT *  FROM tbl_project_monitoring_checklist WHERE  taskid='$taskid'");
                                $query_rsChecklist->execute();
                                $row_rsChecklist = $query_rsChecklist->fetch();
                                $totalRows_rsChecklist = $query_rsChecklist->rowCount();
                            ?>
                                <tr class="collapse milestone<?php echo $milestone  ?>" data-parent="outputs<?php echo $outputid ?>" style="background-color:#FFF">
                                  <td style="background-color:#b8f9cb"></td>
                                  <td><?php echo  $Ocounter . "." . $mcounter . "." . $tcounter ?></td>
                                  <td COLSPAN=2><?php echo $row_rsTasks['task'] ?></td>
                                  <td>
                                    <?php
                                    if ($projstage < 10) {
                                    ?>
                                      <div class="btn-group">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          Options <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                          <?php
                                          if ($totalRows_rsChecklist > 0) {
                                            if ($projstage < 10) {
                                          ?>
                                              <li>
                                                <a type="button" data-toggle="modal" data-target="#addFormModal" id="addFormModalBtn" onclick="editMonitoring(<?php echo $row_rsTasks['tkid'] ?>)">
                                                  <i class="fa fa-pencil"></i> Edit Checklist
                                                </a>
                                              </li>
                                            <?php
                                            }
                                            if ($projstage == 8) {
                                            ?>
                                              <li>
                                                <a type="button" data-toggle="modal" data-target="#removeItemModal" id="#removeItemModalBtn" onclick="removeItem(<?php echo $row_rsTasks['tkid'] ?>, 3)">
                                                  <i class="glyphicon glyphicon-trash"></i> Remove Checklist
                                                </a>
                                              </li>
                                            <?php
                                            }
                                          } else {
                                            ?>
                                            <li>
                                              <a type="button" data-toggle="modal" data-target="#addFormModal" id="addFormModalBtn" onclick="addMonitoring(<?php echo $row_rsTasks['tkid'] ?>)">
                                                <i class="fa fa-plus"></i> Add Checklist
                                              </a>
                                            </li>
                                          <?php
                                          }
                                          ?>
                                        </ul>
                                      </div>
                                    <?php
                                    }
                                    ?>
                                  </td>
                                </tr>

                                <?php
                                if ($totalRows_rsChecklist > 0 && $projstage > 9) {
                                ?>
                                  <tr class="collapse milestone<?php echo $milestone  ?>" data-parent="outputs<?php echo $outputid ?>" style="background-color:#b8f9cb; color:#FFF">
                                    <th style="width: 2%"></th>
                                    <th style="width: 2%">#</th>
                                    <th colspan="3" style="width: 86%">Checklist</th>
                                  </tr>
                    <?php
                                  $rowno = 0;
                                  do {
                                    $checklist =  $row_rsChecklist['name'];
                                    $rowno++;
                                    echo
                                    '<tr id="row' . $rowno  . '" class ="collapse milestone' . $milestone . '" d >
                                            <td></td>
                                            <td align="center">' . $Ocounter . "." . $mcounter . "." . $tcounter . "." . $rowno . '</td>
                                              <td colspan="3">
                                              ' . $checklist . '
                                              </td>
                                            </tr>';
                                  } while ($row_rsChecklist = $query_rsChecklist->fetch());
                                }
                              } while ($row_rsTasks = $query_rsTasks->fetch());
                            }
                          } while ($row_rsMilestones = $query_rsMilestones->fetch());
                        }
                      } while ($row_rsOutputs = $query_rsOutputs->fetch());
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


  <div class="modal fade" id="addFormModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form class="form-horizontal" id="submitMilestoneForm" action="" method="POST" enctype="multipart/form-data">
          <div class="modal-header" style="background-color:#03A9F4">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> Add Checklist</h4>
          </div>
          <div class="modal-body">
            <div class="card">
              <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="body" id="checklistForm">

                  </div>
                </div>
              </div>
            </div>
          </div> <!-- /modal-body -->
          <div class="modal-footer">
            <div class="col-md-12 text-center">
              <input type="hidden" name="newitem" id="newitem" value="new">
              <input type="hidden" name="user_name" id="user_name" value="55">
              <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
              <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
              <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
            </div>
          </div> <!-- /modal-footer -->
        </form> <!-- /.form -->
      </div> <!-- /modal-content -->
    </div> <!-- /modal-dailog -->
  </div>

  <div class="modal fade" id="addmoreModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header" style="background-color:#03A9F4">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> Task Monitoring Checklist</h4>
        </div>
        <div class="modal-body">
          <div class="card">
            <div class="row clearfix">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="body" id="morechecklistForm">

                </div>
              </div>
            </div>
          </div>
        </div> <!-- /modal-body -->
        <div class="modal-footer">
          <div class="col-md-12 text-center">
            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
          </div>
        </div> <!-- /modal-footer -->
      </div> <!-- /modal-content -->
    </div> <!-- /modal-dailog -->
  </div>

  <!-- Start Item Delete -->
  <div class="modal fade" tabindex="-1" role="dialog" id="removeItemModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background-color:#03A9F4">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i> Remove?Delete Item</h4>
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
  <!-- Start Item Delete -->



  <div class="modal fade" id="checklistModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header" style="background-color:#03A9F4">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> Monitoring Checklist</h4>
        </div>
        <div class="modal-body">
          <div class="card">
            <div class="row clearfix">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="body" id="checklistBody">

                </div>
              </div>
            </div>
          </div>
        </div> <!-- /modal-body -->
        <div class="modal-footer">
          <div class="col-md-12 text-center">
            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
          </div>
        </div> <!-- /modal-footer -->
      </div> <!-- /modal-content -->
    </div> <!-- /modal-dailog -->
  </div>

<?php
} else {
  $results =  restriction();
  echo $results;
}
require('includes/footer.php');
?>
<script type="text/javascript">
  function CallRiskAction(id) {
    $.ajax({
      type: 'post',
      url: 'callriskaction',
      data: {
        rskid: id
      },
      success: function(data) {
        $('#riskaction').html(data);
        $("#riskModal").modal({
          backdrop: "static"
        });
      }
    });
  } 

  $(document).ready(function() {
    $(".account").click(function() {
      var X = $(this).attr('id');
      if (X == 1) {
        $(".submenus").hide();
        $(this).attr('id', '0');
      } else {
        $(".submenus").show();
        $(this).attr('id', '1');
      }
    });
    //Mouseup textarea false
    $(".submenus").mouseup(function() {
      return false
    });
    $(".account").mouseup(function() {
      return false
    });

    //Textarea without editing.
    $(document).mouseup(function() {
      $(".submenus").hide();
      $(".account").attr('id', '');
    });

  });
</script>
<script src="assets/custom js/monitoring-checklist.js"></script>