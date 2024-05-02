<?php
try {
?>
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
							<th style="width:10%">Action</th>
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

								//if($count_projects > 0){
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
								//}

							}
						} else {
							echo '<td colspan="5">No concluded survey</td>';
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

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
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>