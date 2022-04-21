<?php
$pageName = "Strategic Plans";
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";

require('includes/head.php');
if ($permission) {
    $pageTitle ="PROJECTS SURVEY";

    try {

      $query_baseline_survey = $db->prepare("SELECT * FROM tbl_projects p left join tbl_project_expected_outcome_details o on o.projid=p.projid WHERE o.data_source=2 AND (p.projstage=9 OR p.projstage=11) ORDER BY p.projid ASC");
      $query_baseline_survey->execute();
      $count_baseline_survey = $query_baseline_survey->rowCount();
    } catch (PDOException $ex) {
      function flashMessage($err)
      {
        return $err;
      }

      $result = flashMessage("An error occurred: " . $ex->getMessage());
      print($result);
    }
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
                    <i class="fa fa-columns" aria-hidden="true"></i>
                    <?php echo $pageTitle ?>
                    <div class="btn-group" style="float:right">
                        <div class="btn-group" style="float:right">

                        </div>
                    </div>
                </h4>
            </div>
            <div class="row clearfix">
                <div class="block-header">
                    <?= $results; ?>
                    <div class="header" style="padding-bottom:0px; margin-left:10px; margin-right:10px">
                      <div class="button-demo" style="margin-top:-15px">
                        <span class="label bg-black" style="font-size:17px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" />Menu</span>
                        <a href="view-project-survey.php" class="btn bg-light-blue waves-effect" style="margin-top:10px; padding-left:-5px">Primary Data Source</a>
                        <a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Secondary Data Source</a>
                      </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                          <div class="table-responsive">
                            <div class="header">
                              <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
                                  <tr>
                                    <td width="100%" height="35" style="padding-left:5px; background-color:#000; color:#FFF" bgcolor="#000000">
                                      <div align="left"><i class="fa fa-file-text-o" aria-hidden="true"></i> Project Outcome Evaluation</strong></div>
                                    </td>
                                  </tr>
                                </table>
                              </div>
                            </div>
                            <div class="body">
                              <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                  <tr class="bg-green">
                                    <th style="width:3%">#</th>
                                    <th style="width:30%">Indicator</th>
                                    <th style="width:42%">Project Name</th>
                                    <th style="width:15%">Evaluation&nbsp;Type</th>
                                    <th style="width:10%" data-orderable="false">Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  if ($count_baseline_survey > 0) {
                                    $deploy_counter = 0;
                                    while ($rows_baseline_survey = $query_baseline_survey->fetch()) {
                                      $projid = $rows_baseline_survey['projid'];
                                      $projname = $rows_baseline_survey['projname'];
                                      $startdate = date_format(date_create($rows_baseline_survey['projstartdate']), "d M Y");
                                      $enddate = date_format(date_create($rows_baseline_survey['projenddate']), "d M Y");
                                      // $status = $rows_baseline_survey['status'];
                                      $projstage = $rows_baseline_survey['projstage'];
                                      $form_id = $rows_baseline_survey['id'];
                                      $projstatus = $rows_baseline_survey['projstatus'];
                                      $outcomeindid = $rows_baseline_survey['outcome_indicator'];
                                      $projdate = date('d-m-Y');
                                      $evaluationtype = "Baseline";
                                      if ($projstage == 11) {
                                        $evaluationtype = "Endline";
                                      }

                                      $query_count_conclusions = $db->prepare("SELECT * FROM tbl_survey_conclusion WHERE projid=:projid AND survey_type=:evaluationtype");
                                      $query_count_conclusions->execute(array(":projid" => $projid, ":evaluationtype" => $evaluationtype));
                                      $count_count_conclusions = $query_count_conclusions->rowCount();
                                      $rows_count_conclusions = $query_count_conclusions->fetch();

                                      $query_outcome_ind = $db->prepare("SELECT * FROM tbl_indicator WHERE indid=:indid");
                                      $query_outcome_ind->execute(array(":indid" => $outcomeindid));
                                      $rows_outcome_ind = $query_outcome_ind->fetch();
                                      $outcomeindicator = $rows_outcome_ind['indicator_name'];
                                      $projid = base64_encode($projid);

                                      $deploy_counter++;
                                      echo '
                                        <tr>
                                          <td style="width:3%">' . $deploy_counter . '</td>
                                          <td style="width:20%">' . $outcomeindicator . '</td>
                                          <td style="width:35%">' . $projname . '</td>
                                          <td style="width:12%">' . $evaluationtype . '</td>
                                          <td style="width:10%">
                                            <a type="button" class="badge bg-purple" href="secondary-data-evaluation?prj=' . $projid . '">
                                              Add Data
                                            </a>
                                          </td>
                                        </tr>';
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
    </section>
    <!-- end body  -->
    <!-- Start Item more -->
    <div class="modal fade" tabindex="-1" role="dialog" id="moreItemModal">
      <div class="modal-dialog  modal-lg">
        <div class="modal-content">
          <div class="modal-header" style="background-color:#03A9F4">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info"></i> Submissions</h4>
          </div>
          <div class="modal-body" id="moreinfo">
          </div>
          <div class="modal-footer">
            <div class="col-md-12 text-center">
              <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Close</button>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>
<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>

<script>
  $(document).ready(function() {
    // load_evaluation_responses();
    // setInterval(function() {
    //   load_active_baseline_responses();
    // }, 2000);
  });

  function more(formid) {
    if (formid != "") {
      $.ajax({
        url: "assets/processor/add-baseline-processor.php",
        method: "GET",
        data: {
          more: "view",
          formid: formid
        },
        dataType: "html",
        success: function(data) {
          $("#moreinfo").html(data);
        }
      });
    }
  }

  function load_active_baseline_responses(view = '') {
    $.ajax({
      url: "assets/processor/add-baseline-processor.php",
      method: "GET",
      data: {
        view: view
      },
      dataType: "json",
      success: function(data) {
        if (data.all_responses > 0) {
          $('#resp' + data.projid).html(data.all_responses);
        }
      }
    });
  }
</script>
