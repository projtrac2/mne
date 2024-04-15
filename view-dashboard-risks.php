<?php
try {

require('includes/head.php');
if ($permission) {
        $risk_level = $_GET['risk_level'];
        $risk_impact = $_GET['impact'];
        $risk_likelihood = $_GET['likelihood'];

        $query_risks_projects = $db->prepare("SELECT m.projid, projname, statusname FROM tbl_projects p left join tbl_project_risk_monitoring m on m.projid=p.projid left join tbl_status s on s.statusid=p.projstatus WHERE (p.projstatus <> 5 AND p.projstatus <> 2 AND p.projstatus <> 6) AND risk_likelihood=:likelihood AND risk_impact=:risk_impact AND risk_level=:risk_level GROUP BY m.projid");
        $query_risks_projects->execute(array(":likelihood" => $risk_likelihood, ":risk_impact" => $risk_impact, ":risk_level" => $risk_level));
        $total_risks_projects= $query_risks_projects->rowCount();

        $query_risk_level = $db->prepare("SELECT description FROM tbl_risk_severity WHERE digit=:risk_level");
        $query_risk_level->execute(array(":risk_level" => $risk_level));
        $row_risk_level= $query_risk_level->fetch();
		$risk_level_description = $row_risk_level["description"];
		
        $query_risk_impact = $db->prepare("SELECT description FROM tbl_risk_impact WHERE digit=:risk_impact");
        $query_risk_impact->execute(array(":risk_impact" => $risk_impact));
        $row_risk_impact= $query_risk_impact->fetch();
		$risk_impact_description = $row_risk_impact["description"];
		
        $query_risk_likelihood = $db->prepare("SELECT description FROM tbl_risk_probability WHERE digit=:risk_likelihood");
        $query_risk_likelihood->execute(array(":risk_likelihood" => $risk_likelihood));
        $row_risks_likelihood= $query_risk_likelihood->fetch();
		$risk_likelihood_description = $row_risks_likelihood["description"];
    
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
    width: 55%
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
.modal-body{
    height: 80vh;
    overflow-y: auto;
}
</style>
<style src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"></style>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <?= $icon . ' ' . $pageTitle ?>
					<div class="btn-group" style="float:right; padding-right:5px">
						<input type="button" VALUE="Go Back" class="btn btn-warning pull-right" onclick="location.href='dashboard'" id="btnback">
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
							<fieldset class="scheduler-border">
								<legend class="scheduler-border bg-orange" style="border-radius:3px"><i class="fa fa-info-circle" aria-hidden="true"></i> Details</legend>

								<div class="col-lg-4 col-md-6 col-sm12 col-xs-12">
									<label>Risk Level:</label>
									<div class="form-control">
										<?=$risk_level_description?>
									</div>
								</div>

								<div class="col-lg-4 col-md-6 col-sm12 col-xs-12">
									<label>Risk Likelihood:</label>
									<div class="form-control">
										<?=$risk_likelihood_description?>
									</div>
								</div>

								<div class="col-lg-4 col-md-6 col-sm12 col-xs-12">
									<label>Risk Impact:</label>
									<div class="form-control">
										<?=$risk_impact_description?>
									</div>
								</div>
							</fieldset>
							<fieldset class="scheduler-border">
								<legend class="scheduler-border bg-primary" style="border-radius:3px"><i class="fa fa-tasks" aria-hidden="true"></i> Projects</legend>
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover js-basic-example" id="guarantees_table">
										<thead>
											<tr>
												<th style="width:5%">#</th>
												<th style="width:55%">Project Name</th>
												<th style="width:15%">Project Status</th>
												<th style="width:15%">Last Monitoring Date</th>
												<th style="width:10%">Risks</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$counter = 0;
											if ($total_risks_projects > 0) {
												while ($row_risks_projects = $query_risks_projects->fetch()){
													$projid = $row_risks_projects['projid'];
													$projname = $row_risks_projects['projname'];
													$projstatus = $row_risks_projects['statusname'];
																																					
													$query_risks_count = $db->prepare("SELECT riskid FROM tbl_project_risk_monitoring WHERE projid=:projid GROUP BY riskid");
													$query_risks_count->execute(array(":projid" => $projid));
													$riskcounts = 0;
													while($total_risks_count = $query_risks_count->fetch()){
														$riskid = $total_risks_count["riskid"];
														
														$query_risks = $db->prepare("SELECT * FROM tbl_project_risk_monitoring WHERE projid=:projid AND riskid=:riskid ORDER BY id DESC LIMIT 1");
														$query_risks->execute(array(":projid" => $projid, ":riskid" => $riskid));
														$rows_risks = $query_risks->fetch();
														$project_risk_likelihood = $rows_risks["risk_likelihood"];
														$project_risk_impact = $rows_risks["risk_impact"];
														$project_risk_level = $rows_risks["risk_level"];
														$last_monitoring_date = date('d M Y', strtotime($rows_risks["date_created"]));
							
														if($project_risk_likelihood == $risk_likelihood && $project_risk_impact == $risk_impact && $project_risk_level == $risk_level){
															$counter++;
															$riskcounts++;
															?>
															<tr>
																<td><?= $counter ?></td>
																<td><?= $projname ?></td>
																<td><?= $projstatus ?></td>
																<td><?= $last_monitoring_date ?></td>
																<td>
																	<a data-toggle="modal" data-target="#modal-right" data-toggle-class="modal-open-aside" style="font-family:Verdana, Geneva, sans-serif; color:white; font-size:12px; padding-top:0px" onclick="risks_more_info(<?= $projid ?>,<?= $risk_likelihood ?>,<?= $project_risk_impact ?>,<?= $project_risk_level ?>)" >
																		<span class="badge bg-orange">
																				<?= $riskcounts ?> 
																		</span>
																	</a>
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
							</fieldset>
                        </div>
                    </div>
                </div>
            </div>
    </section>
	
	<div id="modal-right" class="modal fade" data-backdrop="true">
		<div class="modal-dialog modal-right w-xl">
		   <div class="modal-content h-100 no-radius">
                <div class="modal-header" style="background-color:#03A9F4">
                    <h3 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-warning" style="color:yellow"></i> <span id="modal_info"> PROJECT RISK DETAILS</span></h3>
                </div>
				<div class="modal-body">
					<div class="p-4" id="risks_details">
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
<script src="assets/js/risk/index.js"></script>