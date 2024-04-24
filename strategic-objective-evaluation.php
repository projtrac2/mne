<?php  
require('includes/head.php'); 

if ($permission) {
  try {
    $query_strategic_objective = $db->prepare("SELECT o.id AS id, objective FROM tbl_strategic_plan_objectives o left join tbl_key_results_area r on r.id=o.kraid left join tbl_strategicplan p on p.id=r.spid WHERE current_plan=1 ORDER BY o.id ASC");
	$query_strategic_objective->execute();
	
    /* if($designation == 1){
      $query_strategic_objective = $db->prepare("SELECT * FROM tbl_strategic_plan_objectives o inner join tbl_project_expected_outcome_details o on o.projid=p.projid WHERE data_source=2 and (projstage=9 OR projstage=10) ORDER BY p.projid ASC");
		$query_strategic_objective->execute();
    } */
	//$rows = $query_strategic_objective->fetch();
    $count_strategic_objective = $query_strategic_objective->rowCount();
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
.container{
     
     margin-top:100px;
 }
.modal.fade .modal-bottom,
.modal.fade .modal-left,
.modal.fade .modal-right,
.modal.fade .modal-top {
    position: fixed;
    z-index: 1055;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    margin: 0;
    max-width: 100%
}

.modal.fade .modal-right {
    left: auto!important;
    transform: translate3d(100%, 0, 0);
    transition: transform .3s cubic-bezier(.25, .8, .25, 1)
}

.modal.fade.show .modal-bottom,
.modal.fade.show .modal-left,
.modal.fade.show .modal-right,
.modal.fade.show .modal-top {
    transform: translate3d(0, 0, 0)
}
.w-xl {
    width: 75%
}

.modal-content,
.modal-footer,
.modal-header {
    border: none
}

.h-100 {
    height: 100%!important
}

.list-group.no-radius .list-group-item {
    border-radius: 0!important
}

.btn-light {
    color: #212529;
    background-color: #f5f5f6;
    border-color: #f5f5f6
}

.btn-light:hover {
    color: #212529;
    background-color: #e1e1e4;
    border-color: #dadade
}

.modal-footer {
    align-items: center
}

/* Important part */
.modal-dialog{
    overflow-y: initial !important
}
.modal-body-right{
    height: 80vh;
    overflow-y: auto;
}
</style>
<style src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"></style>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <!-- start body  -->
  <section class="content">
    <div class="container-fluid">
      <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
        <h4 class="contentheader">
          <?= $icon ?>
          <?= $pageTitle ?> Evaluation
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
            <div class="body">
				<input type="hidden" value="0" id="clicked">
              <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                  <thead>
                    <tr class="bg-green">
                      <th style="width:3%">#</th>
                      <th style="width:65%" colspan="3">Strategic Objective</th>
                      <th style="width:12%">Performance</th>
                      <th style="width:10%">Due Date</th>
                      <th style="width:10%" data-orderable="false">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if ($count_strategic_objective > 0) {
						$counter = 0;
						while ($rows_strategic_objective = $query_strategic_objective->fetch()) {
							$objid = $rows_strategic_objective['id'];
							$strategic_objective = $rows_strategic_objective['objective'];
							
							/* $startdate = date_format(date_create($rows_baseline_survey['projstartdate']), "d M Y");
							$enddate = date_format(date_create($rows_baseline_survey['projenddate']), "d M Y"); */

							$today = date('d-m-Y');
							$Performance = 20 ."%";
							
							$counter++;
							echo '
							<tr>
								<td>' . $counter . '</td>
								<td colspan="3"><div onclick="objective_kpi('. $objid .')">' . $strategic_objective . '</div></td>
								<td align="center">' . $Performance . '</td>
								<td>' . $today . '</td>
								<td class="text-primary">
									<div onclick="objective_kpi('. $objid .')" id="action'. $objid .'">';
										/* if (( $designation == 1) || ( $designation >= 7 && $designation <= 13)) {
										  echo '
										  <a type="button" class="badge bg-purple" href="secondary-data-evaluation?results=' . $outcomeidencoded . '&resultstype=2">
											Add Data
										  </a>';
										} */
										echo '
										<i class="fa fa-angle-double-right text-primary" aria-hidden="true"></i> View KPIs
									</div>
								</td>
							</tr>';
							
							$query_kpis = $db->prepare("SELECT id, indicator_name, data_source, responsible FROM tbl_kpi k left join tbl_indicator i on i.indid=k.outcome_indicator_id WHERE strategic_objective_id=:objid");	
							$query_kpis->execute(array(":objid"=>$objid));   
							$totalRows_kpis = $query_kpis->rowCount();
							  
							if($totalRows_kpis > 0){ ?>
								<tr class="objid <?=$objid?>" style="background-color:#cccccc">
									<th style="width:3%"></th>
									<th style="width:4%"></th>
									<th style="width:49%">Key Performance Indicator</th>
									<th style="width:12%">Data Source</th>
									<th style="width:12%">Responsible</th>
									<th style="width:10%">Due Date</th>
									<th style="width:10%">Action</th>
								</tr>
								<?php 
								$count_kpi=0;
								while($rows = $query_kpis->fetch()){ 
									$count_kpi++;
									$kpi_id = $rows['id']; 
									$kpi_description = $rows['indicator_name'];
									$data_source = $rows['data_source'];
									$responsible_id = $rows["responsible"];
									$responsible_id = 8;
									
									$query_responsible_designation = $db->prepare("SELECT designation FROM tbl_pmdesignation WHERE moid=:responsible_id");	
									$query_responsible_designation->execute(array(":responsible_id"=>$responsible_id));   
									$rows_responsible_designation = $query_responsible_designation->fetch();
									$responsible_designation = $rows_responsible_designation["designation"];
		
									/* $query_responsible = $db->prepare("SELECT tt.title AS title, fullname FROM users u left join tbl_projteam2 t on t.ptid=u.pt_id left join tbl_titles tt on tt.id=t.title WHERE u.userid=:responsible_id");	
									$query_responsible->execute(array(":responsible_id"=>$responsible_id));   
									$rows_responsible = $query_responsible->fetch();
									$title = $rows_responsible["title"];
									$full_name = $rows_responsible["fullname"];
									$responsible = $title.".".$full_name; */
									
									?>
									<tr class="objid <?=$objid?>" style="background-color:#e3e9ea">
										<td></td>
										<td><?= $counter.".".$count_kpi ?></td>
										<td><?= $kpi_description ?></td>
										<td><?= $data_source ?></td>
										<td><?= $responsible_designation ?></td>
										<td><?= $today ?></td>
										<td>
											<div class="btn-group">
												<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													Options <span class="caret"></span>
												</button>
												<ul class="dropdown-menu">
													<li>
														<a data-toggle="modal" data-target="#evaluateItemModal" data-toggle-class="evaluateItemModalID" onclick="kpi_evaluation(<?= $kpi_id ?>)" style="color:green">
															<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Evaluate 
														</a>
													</li>
													<li>
														<a data-toggle="modal" data-target="#kpi-modal-right" data-toggle-class="modal-open-aside" onclick="kpi_score_details(<?= $kpi_id ?>)" style="color:blue">
															<i class="fa fa-info"></i> Score Details
														</a>
													</li>
												</ul>
											</div>
										</td>
									</tr>
								<?php
								}
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
  <div class="modal fade" tabindex="-1" role="dialog" id="evaluateItemModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background-color:#03A9F4">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-file-text-o"></i> KPI DATA COLLECTION</h4>
        </div>
        <div class="modal-body" id="moreinfo">
			<form class="form-horizontal" id="addEvaluationForm" action="ajax/strategicplan/strategic-objectives.php" method="POST" autocomplete="off">
				<br>
				<div id="result">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label>Key Performance Indicator:</label>
						<div class="form-line">
							<div id="kpi" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"> </div>
						</div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:10px">
						<label id="unit"></label>
						<div class="form-line">
							<input type="text" name="kpi_value" id="kpi_value" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" placeholder="Enter the KPI value" />
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center strat" style="padding-top:10px">
						<input type="hidden" name="username" id="username" value="<?= $user_name ?>">
						<input type="hidden" name="kpi_id" id="kpi_id">
						<input type="hidden" name="save_kpi_evaluation" id="save_kpi_evaluation">
						<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-evaluation-form-submit" value="Save" />
						<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
					</div>
				</div> <!-- /modal-footer -->
			</form> <!-- /.form -->
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>
	
    <!-- Start View KPI Details Modal -->
    <div id="kpi-modal-right" class="modal fade" data-backdrop="true">
		<div class="modal-dialog modal-right modal-lg w-xl">
		   <div class="modal-content h-100 no-radius">
                <div class="modal-header" style="background-color:#03A9F4">
                    <h3 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-bar-chart" aria-hidden="true" style="color:yellow"></i> <span id="modal_info"> KPI Performance Details</span></h3>
                </div>
				<div class="modal-body modal-body-right">
					<div class="p-4" id="kpi_details">
					</div>
				</div>
				<div class="modal-footer">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
						<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Close</button>
					</div>
				</div>
		   </div>
		</div>
	</div>
    <!-- End View KPI Details Modal -->
<?php
} else {
  $results =  restriction();
  echo $results;
}

require('includes/footer.php');
?>
<script src="assets/js/strategicplan/strategic-objectives.js"></script>