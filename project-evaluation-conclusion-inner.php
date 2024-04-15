<?php 
try {

?>
<div class="container-fluid">
	<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
		<h4 class="contentheader"><i class="fa fa-newspaper-o" aria-hidden="true"></i> PROJECT EVALUATION CONCLUSION
		</h4>
	</div>
	<!-- Draggable Handles -->
	<div class="row clearfix">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
				<div class="body" style="margin-top:5px">
					<div class="wizard">
						<div class="wizard-inner" style="margin-top:-20px">
							<div class="connecting-line"></div>
							<ul class="nav nav-tabs" role="tablist">
								<li role="presentation" class="active">
									<a href="#step1" data-toggle="tab" aria-controls="step1" role="tab"	title="Project Evaluation Summary">
										<span class="round-tab">
											SUMMARY
										</span>
									</a>
								</li>
								<li role="presentation" class="disabled">
									<a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Evaluation Form Responses">
										<span class="round-tab">
											FORM <span class="badge bg-green"><?php echo $totalRows_rsSubmission; ?></span>
										</span>
									</a>
								</li>
								<li role="presentation" class="disabled">
									<a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Evaluation Data">
										<span class="round-tab">
											DATA
										</span>
									</a>
								</li>
								<li role="presentation" class="disabled">
									<a href="#step4" data-toggle="tab" aria-controls="step4" role="tab" title="Evaluation Conclusion & Recommendations">
										<span class="round-tab">
											CONCLUSION
										</span>
									</a>
								</li>
							</ul>
						</div>
						<div class="tab-content">
							<div class="tab-pane active" role="tabpanel" id="step1">
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Evaluation Summary
									</legend>
									<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="col-md-12">
												<strong>Project Name: <font color="#3F51B5"><?php echo $projname; ?></font></strong>
											</div>
											<div class="col-md-12">
												<label class="control-label">Project Description:</label>
												<div class="form-line">
													<div style="border:#CCC thin solid; border-radius:5px; height:40px; padding:10px; color:#3F51B5">
														<strong><?php echo $projdesc; ?></strong>
														<input name="projid" id="projid" type="hidden" value="<?=$projid?>">
													</div>
												</div>
											</div>
											<div class="col-md-12">
												<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
													<thead>
														<tr class="bg-light-blue">
															<th style="width:17%">Project Results</th>
															<th style="width:23%">Indicator</th>
															<th style="width:20%">Baseline</th>
															<th style="width:20%">Target</th>
															<th style="width:20%">Achieved</th>
														</tr>
													</thead>
													<tbody>
														<?php if($evaltype ==1 || $evaltype ==2 || $evaltype ==3 || $evaltype ==4){ } else{ ?>
														<tr>
															<td class="bg-light-blue">Outcome</td>
															<td><?php echo $location; ?></td>
															<td><?php echo $projstatus; ?></td>
															<td><?php echo $location; ?></td>
															<td><font color="red"><strong>??</strong></font></td>
														</tr>
														<?php } ?>
														<tr>
															<td class="bg-light-blue">Output</td>
															<td><?php echo $row_PrjDet['indicator']; ?></td>
															<td><?php echo $row_PrjDet['baseline']; ?></td>
															<td><?php echo $row_PrjDet['target']; ?></td>
															<td><font color="green"><strong>0</strong></font></td>
														</tr>
													</tbody>
												</table>
											</div>
											<div class="col-md-12">
												<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
													<thead>
														<tr class="bg-light-blue">
															<th style="width:17%">Project Constraint</th>
															<th style="width:23%">Indicator</th>
															<th style="width:20%">Planned</th>
															<th style="width:20%">Actual</th>
															<th style="width:20%">Rate(%)</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td class="bg-light-blue">Budget</td>
															<td><?php echo "Kenyan Shilling"; ?></td>
															<td><?php echo number_format($projbudget, 2); ?></td>
															<td><?php echo number_format($amountpaid, 2); ?></td>
															<td><?php echo $amntrate."%"; ?></td>
														</tr>
														<tr>
															<td class="bg-light-blue">Timeframe</td>
															<td><?php echo "Days"; ?></td>
															<td><?php echo $projdatediff; ?></td>
															<td><?php echo $projnowdiff; ?></td>
															<td><?php echo $prjtimelinerate."%"; ?></td>
														</tr>
													</tbody>
												</table>
											</div>
									
											<?php 
											if($totalRows_evaluationsummary > 0){
												
												$sn=0;
												while($summary = $query_evaluationsummary->fetch()){
													$sn=$sn+1;
													$obj = $summary["section"];
													$objid = $summary["id"];

													$query_objquestions =  $db->prepare("SELECT * FROM `tbl_project_evaluation_answers` a INNER JOIN tbl_project_evaluation_form_question_fileds q ON q.id =a.fieldid WHERE q.formid = '$formid' AND q.sectionid = '$objid' GROUP BY a.fieldid ORDER BY a.id ASC");
													$query_objquestions->execute();	
													$row_objquestions = $query_objquestions->fetchAll();
													?>
													<div class="col-md-12">
														<?php 
														echo '<strong><u><h5 style="color:blue">Objective '.$sn.': <font color="green">'.$obj.'</font></h5></u></strong>';
														?>
													</div>
													
													<?php
													foreach($row_objquestions as $row){
														$fieldid =$row['fieldid'];
														$type =$row['fieldtype'];
														if($type == "select" || $type =="radio-group"){
															$query_rsAnswers = $db->prepare("SELECT q.label AS label, v.label AS answer  FROM `tbl_project_evaluation_answers` a INNER JOIN tbl_project_evaluation_form_question_fileds q ON q.id =a.fieldid INNER JOIN tbl_project_evaluation_form_question_filed_values v ON v.id =a.answer WHERE q.sectionid=:objectiveid AND a.fieldid=:fieldid");
															$query_rsAnswers->execute(array(":objectiveid" => $objid, ":fieldid" => $fieldid));
															$row_rsAnswers = $query_rsAnswers->fetchAll();
															$totalRows_rsAnswers = $query_rsAnswers->rowCount();
															$answer =array();
															foreach($row_rsAnswers as $data){
																$answer[] =$data['answer'];
															}
															$data =array_count_values($answer);
															?>
															<div class="col-md-6" style="height: 600px; border:#CCC thin solid; border-radius:5px; padding:10px; color:#3F51B5" id="chart_div<?php echo $objid.$fieldid;?>"> </div>
															<script type="text/javascript">
															google.load("visualization", "1", {packages:["corechart"]});
															google.setOnLoadCallback(drawChart);
															function drawChart() {
																var data = google.visualization.arrayToDataTable([
																	['Label','Count'],
																	<?php 
																		foreach($data as $key=>$value){ 
																			echo "['".$key."',".$value."],";
																		}
																	?> 
																]);
																var options = {
																	title: '<?php echo $row['label'];?>',
																	is3D: true,
																	pieSliceTextStyle: {
																		color: 'black',
																	}
																};
																var chart = new google.visualization.PieChart(document.getElementById("chart_div<?php echo $objid.$fieldid;?>"));
																chart.draw(data,options);
															}
															</script>
														<?php
														}else{	
														?>
															<div class="col-md-6">
																<table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="section<?php echo $objid.$fieldid ?>">
																	<thead>
																		<tr class="bg-light-blue">
																			<th style="width:3%">ANS\QST</th>
																			<?php 
																				$question = $row["label"];
																				//$questionid = $query["id"];		
																			?>
																			<th><?=$question?></th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php 
																		//$fieldid =$query['fieldid'];
																		$query_rsFieldquestion = $db->prepare("SELECT * FROM tbl_project_evaluation_form_question_fileds q INNER JOIN  tbl_project_evaluation_answers a ON a.fieldid=q.id WHERE q.sectionid=:objectiveid AND a.fieldid=:fieldid ORDER BY a.fieldid");
																		$query_rsFieldquestion->execute(array(":objectiveid" => $objid, ":fieldid" => $fieldid));
																		$row_rsFieldquestion = $query_rsFieldquestion->fetchAll();
																		$totalRows_rsFieldquestion = $query_rsFieldquestion->rowCount();
																		
																		$nmb = 0;
																		foreach($row_rsFieldquestion as $group){ 
																		$nmb = $nmb + 1;
																		?>
																		<tr>
																			<td align="center">
																				<?php echo $nmb; ?>
																			</td>
																			<?php 
																			$fieldtype = $group['fieldtype'];
																			$vlid =$group['answer'];
																			if($fieldtype == "checkbox-group" || $fieldtype == "radio-group" || $fieldtype == "select"){
																				$query_rsValueName = $db->prepare("SELECT label FROM `tbl_project_evaluation_form_question_filed_values` WHERE id=:valid");
																				$query_rsValueName->execute(array(":valid" => $vlid));
																				$row_rsValueName = $query_rsValueName->fetch();
																				$answer = $row_rsValueName["label"];
																			}else{
																				$answer = $group["answer"];
																			}
																			?>
																			<td>
																				<?php echo $answer; ?>
																			</td>
																		</tr>
																		<?php 
																		} 
																		?>
																	</tbody>
																</table>
																<script>
																	$(document).ready(function() {
																		$('#section<?php echo $objid.$fieldid ?>').DataTable();
																	} );
																</script>
															</div>
														<?php 
														}
													}
												}
											}else{
												echo '<div class="col-md-12">
													<h5 style="color:red">Sorry no data found for this evaluation form<h5>
												</div>';
											}
											?>
										</div>
									</div>
								</fieldset>
							</div>
							<div class="tab-pane" role="tabpanel" id="step2">
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"> Form Submissions</legend>
									<div class="table-responsive">
										<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
											<thead>
												<tr class="bg-orange">
													<th style="width:4%"></th>
													<th style="width:4%">#</th>
													<th style="width:30%">Submission Email</th>
													<th style="width:30%">Submission Date</th>
												</tr>
											</thead>
											<tbody>
											<?php
												if ($totalRows_rsSubmission == 0) {
													?>
												<tr>
													<td colspan="4">
														<div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div>
													</td>
												</tr>
												<?php } else {
													$nm = 0;
													while($row_rsSubmission = $query_rsSubmission->fetch()){
														$nm = $nm + 1;
														$submissionid = $row_rsSubmission['id'];
														$submission = $row_rsSubmission['submission_code'];
														$submitter = $row_rsSubmission['email'];
														$submissionDate = date("d M Y", strtotime($row_rsSubmission['submission_date']));
														?>
														<tr data-toggle="collapse" data-target=".order<?php echo $nm; ?>"
															style="background-color:#eff9ca">
															<td align="center" class="mb-0">
																<button class="btn btn-link"
																	title="Click once to expand and Click twice to Collapse!!">
																	<i class="fa fa-plus-square" style="font-size:16px"></i>
																</button>
															</td>
															<td align="center"><?php echo $nm; ?></td>
															<td><?php echo $submitter; ?></td>
															<td><?php echo $submissionDate; ?></td>
														</tr>
														<?php
														$query_rsSection = $db->prepare("SELECT q.sectionid, o.section  FROM tbl_project_evaluation_form_question_fileds q INNER JOIN tbl_project_evaluation_form_sections o on o.id =q.sectionid  WHERE q.formid='$formid' GROUP BY q.sectionid");
														$query_rsSection->execute(array(":submission" => $submissionid));
														$totalRows_rsSection = $query_rsSection->rowCount();
														?>
														<tr class="collapse order<?php echo $nm; ?>"
															style="background-color:#FF9800; color:#FFF">
															<th></th>
															<th>#</th>
															<th colspan="2">Objectives</th>
														</tr>
														<?php
														if ($totalRows_rsSection == 0) {
															?>
															<tr class="collapse order<?php echo $nm; ?>">
																<td colspan="4">
																	<div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div>
																</td>
															</tr>
														<?php } 
														else {
															$num = 0;
															foreach($row_rsSection as $row){
																$sectionid = $row['id'];
																$section = $row['section'];
																$num = $num + 1;
																$query_rsFieldAnswer = $db->prepare("SELECT a.fieldid, a.answer, q.fieldtype, q.label FROM tbl_project_evaluation_answers a INNER JOIN tbl_project_evaluation_form_question_fileds q on q.id =a.fieldid WHERE a.submissionid='$submissionid' AND q.sectionid='$sectionid'");
																$query_rsFieldAnswer->execute();
																$totalRows_rsFieldAnswer = $query_rsFieldAnswer->rowCount();

																?>
																<tr data-toggle="collapse" data-target=".topic<?php echo $nm . $num; ?>"
																	class="collapse order<?php echo $nm; ?>" style="background-color:#CDDC39">
																	<td align="center" class="mb-0">
																		<button class="btn btn-link"
																			title="Click once to expand and Click twice to Collapse!!"> <i
																				class="more-less fa fa-plus-square" style="font-size:16px"></i>
																		</button>
																	</td>
																	<td align="center"> <?php echo $nm . "." . $num; ?></td>
																	<td colspan="2"><?php echo $section; ?></td>
																</tr>
																<tr class="collapse topic<?php echo $nm . $num; ?>"
																	style="background-color:#b8f9cb; color:#FFF">
																	<th></th>
																	<th>#</th>
																	<th>Question</th>
																	<th>Answer</th>
																</tr>
																<?php
																$nmb = 0;
																while($row_rsFieldAnswer = $query_rsFieldAnswer->fetch()) {
																	$nmb++;
																	$answer = $row_rsFieldAnswer['answer'];
																	$label = $row_rsFieldAnswer['label'];
																	$ftype = $row_rsFieldAnswer['fieldtype'];
																	$fid = $row_rsFieldAnswer['fieldid'];
																	
																	if($ftype=="checkbox-group" || $ftype=="radio-group" || $ftype=="select"){
																		$query_rsFieldID = $db->prepare("SELECT * FROM  tbl_project_evaluation_form_question_filed_values WHERE fieldid='$fid'");
																		$query_rsFieldID->execute();
																		$row_rsFieldID = $query_rsFieldID->fetch();
																		$answer = $row_rsFieldID["label"];
																	}else{
																		$answer = $answer;
																	}
																	?>
																	<tr class="collapse topic<?php echo $nm . $num; ?>"
																		style="background-color:#FFF">
																		<td style="background-color:#b8f9cb"></td>
																		<td align="center"><?php echo $nm . "." . $num . "." . $nmb; ?></td>
																		<td><?php echo $label; ?></td>
																		<td> <?php echo $answer; ?></td>
																	</tr>
																	<?php
																}
															}
														}
													}
												}
												?>
											</tbody>
											<script type="text/javascript">
											/*******************************
												* ACCORDION WITH TOGGLE ICONS
												*******************************/
											function toggleIcon(e) {
												$(e.target)
													.find(".more-less")
													.toggleClass('fa fa-plus-square fa fa-minus-square');
											}
											$('.mb-0').on('hidden.bs.collapse', toggleIcon);
											$('.mb-0').on('shown.bs.collapse', toggleIcon);
											</script>
										</table>
									</div>
								</fieldset>
							</div>
							<div class="tab-pane" role="tabpanel" id="step3">
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Data</legend>
									
									<?php 
									if($totalRows_evaluationobjs > 0){
									?>
									<?php 
									$sn=0;
									while($row_evaluationobjs = $query_evaluationobjs->fetch()){
										$sn=$sn+1;
										$objective = $row_evaluationobjs["section"];
										$objectiveid = $row_evaluationobjs["id"];

										$query_objquestions =  $db->prepare("SELECT * FROM tbl_project_evaluation_form_question_fileds WHERE formid = '$formid' AND sectionid = '$objectiveid' ORDER BY id");
										$query_objquestions->execute();	
										$row_objquestions = $query_objquestions->fetchAll();
										?>
										<div class="col-md-12">
											<?php 
											echo '<strong><u><h5 style="color:blue">Objective '.$sn.': <font color="green">'.$objective.'</font></h5></u></strong>';
											?>
										</div>
										<div class="col-md-12">
											<?php 
											$query_rsFieldquestions = $db->prepare("SELECT * FROM `tbl_project_evaluation_answers` a INNER JOIN tbl_project_evaluation_form_question_fileds q ON q.id =a.fieldid WHERE q.sectionid=:objectiveid GROUP BY a.fieldid");
											$query_rsFieldquestions->execute(array(":objectiveid" => $objectiveid));
											$row_rsFieldquestions = $query_rsFieldquestions->fetchAll();
											$totalRows_rsFieldquestions = $query_rsFieldquestions->rowCount();
											$nm =0; 
											?>
											<table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="section<?php echo $sectionid ?>">
												<thead class="thead-inverse">
													<tr class="bg-light-blue">
														<th style="width:3%">ANS\QST</th>
														<?php 
														foreach($row_objquestions as $query){
															$question = $query["label"];
															//$questionid = $query["id"];		
															?>
														<th><?=$question?></th>
														<?php } ?>
													</tr>
												</thead>
												<tbody>
													<?php 
													//$fieldid =$query['fieldid'];
													$query_rsFieldquestion = $db->prepare("SELECT * FROM `tbl_project_evaluation_answers` a INNER JOIN tbl_project_evaluation_form_question_fileds q ON q.id =a.fieldid WHERE q.sectionid=:objectiveid ORDER BY q.id");
													$query_rsFieldquestion->execute(array(":objectiveid" => $objectiveid));
													$row_rsFieldquestion = $query_rsFieldquestion->fetchAll();
													$totalRows_rsFieldquestion = $query_rsFieldquestion->rowCount();
													$grouped_rsFieldquestion = array_chunk($row_rsFieldquestion, $totalRows_rsFieldquestions);
													
													$nmb = 0;
													foreach($grouped_rsFieldquestion as $group){ 
													$nmb = $nmb + 1;
													?>
													<tr>
														<td align="center">
															<?php echo $nmb; ?>
														</td>
														<?php 
															foreach($group as $row_rsFieldquestion){
																$fieldtype = $row_rsFieldquestion['fieldtype'];
																$vlid =$row_rsFieldquestion['answer'];
																if($fieldtype == "checkbox-group" || $fieldtype == "radio-group" || $fieldtype == "select"){
																	$query_rsValueName = $db->prepare("SELECT label FROM `tbl_project_evaluation_form_question_filed_values` WHERE id=:valid");
																	$query_rsValueName->execute(array(":valid" => $vlid));
																	$row_rsValueName = $query_rsValueName->fetch();
																	$answer = $row_rsValueName["label"];
																}else{
																	$answer = $row_rsFieldquestion["answer"];
																}
														?>
																<td>
																	<?php echo $answer; ?>
																</td>
														<?php 
															}
														?>
													</tr>
													<?php 
													} 
													?>
												</tbody>
											</table>
											<script>
												$(document).ready(function() {
													$('#section<?php echo $objectiveid ?>').DataTable();
												} );
											</script>
										</div>
										<?php 
										}
									}else{
										echo '<div class="col-md-12">
											<h5 style="color:red">Sorry no data found for this evaluation form<h5>
										</div>';
									}
									?>
								</fieldset>
							</div>
							
							<div class="tab-pane" role="tabpanel" id="step4">
								
								<form action="" method="POST" role="form" id="conclusionform">
									<fieldset class="scheduler-border">
										<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Conclusion & Recommendations</legend>
										<div class="block-header" id="sweetalert">
											<?php echo $results; ?>
										</div>
										<div class="col-md-12">
											<label class="control-label">Projects *:</label>
											<div class="form-line">
												<div style="border:#CCC thin solid; border-radius:5px; height:40px; padding:10px; color:#3F51B5">
													<strong><?php echo $projname; ?></strong>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<label class="control-label">Evaluation Conclusion <font align="left" style="background-color:#CDDC39">(Explain your conclusion on this evaluation)</font>*:</label>
											<p align="left">
												<textarea name="conclusion" cols="45" rows="5"
													class="txtboxes" id="evalconcl" required="required"
													style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"
													></textarea>
												<script>
												CKEDITOR.replace('evalconcl', {
													on: {
														instanceReady: function(ev) {
															// Output paragraphs as <p>Text</p>.
															this.dataProcessor.writer.setRules('p', {
																indent: false,
																breakBeforeOpen: false,
																breakAfterOpen: false,
																breakBeforeClose: false,
																breakAfterClose: false
															});
															this.dataProcessor.writer.setRules('ol', {
																indent: false,
																breakBeforeOpen: false,
																breakAfterOpen: false,
																breakBeforeClose: false,
																breakAfterClose: false
															});
															this.dataProcessor.writer.setRules('ul', {
																indent: false,
																breakBeforeOpen: false,
																breakAfterOpen: false,
																breakBeforeClose: false,
																breakAfterClose: false
															});
															this.dataProcessor.writer.setRules('li', {
																indent: false,
																breakBeforeOpen: false,
																breakAfterOpen: false,
																breakBeforeClose: false,
																breakAfterClose: false
															});
														}
													}
												});
												</script>
											</p>
										</div>
										<?php if($evaltype==3){ }else{?>
											<div class="col-md-12">
												<label class="control-label">Recommendations <font align="left" style="background-color:#CDDC39">(Give a descriptive recommendations on your conclusion indicated above)</font> *:</label>
												<p align="left">
													<textarea name="recommendation" cols="45" rows="5"
														class="txtboxes" id="evalrecomm" required="required"
														style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"
														placeholder="Give a descriptive recommendations on your conclusion indicated above"></textarea>
													<script>
													CKEDITOR.replace('evalrecomm', {
														on: {
															instanceReady: function(ev) {
																// Output paragraphs as <p>Text</p>.
																this.dataProcessor.writer.setRules('p', {
																	indent: false,
																	breakBeforeOpen: false,
																	breakAfterOpen: false,
																	breakBeforeClose: false,
																	breakAfterClose: false
																});
																this.dataProcessor.writer.setRules('ol', {
																	indent: false,
																	breakBeforeOpen: false,
																	breakAfterOpen: false,
																	breakBeforeClose: false,
																	breakAfterClose: false
																});
																this.dataProcessor.writer.setRules('ul', {
																	indent: false,
																	breakBeforeOpen: false,
																	breakAfterOpen: false,
																	breakBeforeClose: false,
																	breakAfterClose: false
																});
																this.dataProcessor.writer.setRules('li', {
																	indent: false,
																	breakBeforeOpen: false,
																	breakAfterOpen: false,
																	breakBeforeClose: false,
																	breakAfterClose: false
																});
															}
														}
													});
													</script>
												</p>
											</div>
										<?php } ?>
										<input name="projid" type="hidden" id="projid" value="<?php echo $projid; ?>" />
										<input name="formid" type="hidden" id="formid" value="<?php echo $formid; ?>" />
										<input name="username" type="hidden" id="username" value="<?php echo $user_name; ?>" />
										<ul class="list-inline pull-right" style="margin-top:20px">
											<li><input name="submit" type="submit" class="btn btn-primary next-step" id="evalconclusion"></li>
										</ul>
									</fieldset>
								</form>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>