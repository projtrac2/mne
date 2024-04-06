<?php  
  try {

require('includes/head.php'); 

if ($permission) {
	$query_baseline_survey = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_project_expected_impact_details o on o.projid=p.projid WHERE data_source=2 and (projstage=9 OR projstage=10) AND responsible=:user_name ORDER BY p.projid ASC");
	$query_baseline_survey->execute(array(":user_name" => $user_name));
	
    if($designation == 1){
      $query_baseline_survey = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_project_expected_impact_details o on o.projid=p.projid WHERE data_source=2 and (projstage=9 OR projstage=10) ORDER BY p.projid ASC");
		$query_baseline_survey->execute();
    }
	//$rows = $query_baseline_survey->fetch();
    $count_baseline_survey = $query_baseline_survey->rowCount();
  
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
            </div>
          </div>
        </h4>
      </div>
      <div class="row clearfix">
        <div class="block-header">
          <?= $results; ?>
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="header" style="padding-bottom:0px; margin-left:10px; margin-right:10px">
              <div class="button-demo" style="margin-top:-15px">
                <span class="label bg-black" style="font-size:17px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" />Menu</span>
                <a href="view-project-impact-evaluation.php" class="btn bg-light-blue waves-effect" style="margin-top:10px; padding-left:-5px">Survey Data Source</a>
                <a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Records Data Source</a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="card">
            <div class="body">
              <div class="table-responsive">
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
                        $impactindid = $rows_baseline_survey['indid'];
						$impactid = $rows_baseline_survey['id'];
                        $projdate = date('d-m-Y');
                        $evaluationtype = "Baseline";
                        if ($projstage == 10) {
                          $evaluationtype = "Endline";
                        }

                        $query_count_conclusions = $db->prepare("SELECT * FROM tbl_survey_conclusion WHERE projid=:projid AND survey_type=:evaluationtype");
                        $query_count_conclusions->execute(array(":projid" => $projid, ":evaluationtype" => $evaluationtype));
                        $count_count_conclusions = $query_count_conclusions->rowCount();
                        $rows_count_conclusions = $query_count_conclusions->fetch();

                        $query_impact_ind = $db->prepare("SELECT * FROM tbl_indicator WHERE indid=:indid");
                        $query_impact_ind->execute(array(":indid" => $impactindid));
                        $rows_impact_ind = $query_impact_ind->fetch();
                        $impactindicator = $rows_impact_ind['indicator_name'];
                        
						
                        $query_impact_evaluated = $db->prepare("SELECT * FROM tbl_survey_conclusion WHERE resultstype=1 AND resultstypeid=:resultstypeid");
                        $query_impact_evaluated->execute(array(":resultstypeid" => $impactid));
                        $rows_impact_evaluated = $query_impact_evaluated->rowCount();
						
						if($rows_impact_evaluated == 0){
							$impactidencoded = base64_encode("resultssecdata{$impactid}");

							$deploy_counter++;
							echo '
							  <tr>
								<td style="width:3%">' . $deploy_counter . '</td>
								<td style="width:20%">' . $impactindicator . '</td>
								<td style="width:35%">' . $projname . '</td>
								<td style="width:12%">' . $evaluationtype . '</td>
								<td style="width:10%">';
									if (( $designation == 1) || ($designation >= 7 && $designation <= 13)) {
									  echo '
									  <a type="button" class="badge bg-purple" href="secondary-data-evaluation?results=' . $impactidencoded . '&resultstype=1">
										Add Data
									  </a>';
									}
									echo '
								</td>
							  </tr>';
						}
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
} catch (PDOException $ex) {
  customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
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