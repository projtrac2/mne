<div class="body">
	<fieldset class="scheduler-border">
		<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"> All Submissions <span class="badge bg-pink" style="margin-bottom:2px" id="responses"></span></legend>
		<input type="hidden" value="<?php echo $indid; ?>" id="specprojid">
		<input type="hidden" value="<?php echo $formid; ?>" id="specformid">
		<div style="color:#3F51B5; font-size:16px"><strong>Indicator Name:</strong> <?=$row_rsFormDetails["indname"]?></div>
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
				<thead>
					<tr class="bg-orange">
						<th style="width:4%"></th>
						<th style="width:4%">#</th>
						<th style="width:30%"><?=$level3label?></th>
						<th style="width:52%">Correspondent/s</th>
						<th style="width:10%">Submissions</th>
					</tr>
				</thead>
				<tbody>
				<?php
					if ($totalRows_rsSubLoc == 0) {
						?>
					<tr>
						<td colspan="4">
							<div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div>
						</td>
					</tr>
					<?php } else {
						$nm = 0;
						while($row_rsSubLoc = $query_rsSubLoc->fetch()){
							$nm = $nm + 1;
							$lv3id = $row_rsSubLoc["level3id"];
							//get the submission number and date 
							$query_rsSubmEmail = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_submission WHERE indid='$indid' AND formid='$formid' AND level3id='$lv3id' GROUP BY email");
							$query_rsSubmEmail->execute();
							$row_rsSubmEmail = $query_rsSubmEmail->fetchAll();
							$totalRows_rsSubmEmail = $query_rsSubmEmail->rowCount();
							
							//get the number submissions 
							$query_rsSubmissions = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_submission WHERE indid='$indid' AND formid='$formid' AND level3id='$lv3id'");
							$query_rsSubmissions->execute();
							$svySubmissions = $query_rsSubmissions->rowCount();
							
							$submitteremails=[];
							foreach($row_rsSubmEmail as $row){
								$submitteremails[]=$row['email'];
							}
							$submitters = implode(",", $submitteremails);
								?>
								<tr data-toggle="collapse" data-target=".order<?php echo $nm; ?>" style="background-color:#eff9ca">
									<td align="center" class="mb-0">
										<button class="btn btn-link"
											title="Click once to expand and Click twice to Collapse!!">
											<i class="fa fa-plus-square" style="font-size:16px"></i>
										</button>
									</td>
									<td align="center"><?php echo $nm; ?></td>
									<td><?php echo $row_rsSubLoc["state"]; ?></td>
									<td><?php echo $submitters; ?></td>
									<td><?php echo $svySubmissions; ?></td>
								</tr>
								<tr class="collapse order<?php echo $nm; ?>" style="background-color:#FFC107; color:#FFF">
									<th></th>
									<th>#</th>
									<th>Submission</th>
									<th>Correspondent</th>
									<th style="width:30%">Submission Date</th>
								</tr>
								<?php
								
								$sr=0;
							$submitterEmail = Explode(",", $submitters);
							foreach($submitterEmail as $submitter){	
								//get the submission number and date 
								$query_rsSubmission = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_submission WHERE indid='$indid' AND formid='$formid' AND email='$submitter' AND level3id='$lv3id'");
								$query_rsSubmission->execute();
								$totalRows_rsSubmission = $query_rsSubmission->rowCount();
								
								if ($totalRows_rsSubmission == 0) {
									?>
									<tr class="collapse order<?php echo $nm; ?>">
										<td colspan="5">
											<div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div>
										</td>
									</tr>
								<?php } 
								else {
									$nb = 0;
									while($row_rsSubmission = $query_rsSubmission->fetch()) {
										$sr++;
										$submissionDate = date("d M Y", strtotime($row_rsSubmission['submission_date']));
										$submission = $row_rsSubmission['submission_code'];
										$submissionid = $row_rsSubmission['id'];

										$nb++;

										?>
										<tr data-toggle="collapse" data-target=".topic<?php echo $nm . $sr; ?>" class="collapse order<?php echo $nm; ?>" style="background-color:#CDDC39">
											<td align="center" class="mb-0" style="background-color:#FFC107">
												<button class="btn btn-link"> 
													<i class="more-less fa fa-plus-square" style="font-size:16px"></i>
												</button>
											</td>
											<td align="center"> <?php echo $nm . "." . $sr; ?></td>
											<td> <?php echo "Submission ".$sr; ?></td>
											<td> <?php echo $row_rsSubmission['email']; ?></td>
											<td colspan="2"><?php echo $submissionDate; ?></td>
										</tr>
										<?php
										$query_rsSection = $db->prepare("SELECT q.sectionid, s.section  FROM tbl_indicator_baseline_survey_form_question_fields q INNER JOIN tbl_indicator_baseline_survey_form_sections s on s.id =q.sectionid  WHERE q.formid='$formid' GROUP BY q.sectionid");
										$query_rsSection->execute();
										$totalRows_rsSection = $query_rsSection->rowCount();
										?>
										<tr class="collapse topic<?php echo $nm . $sr; ?>" style="background-color:#FFEB3B; color:#000">
											<th style="background-color:#FFC107"></th>
											<th>#</th>
											<th colspan="3">Objectives</th>
										</tr>
										<?php
										if ($totalRows_rsSection == 0) {
											?>
											<tr class="collapse topic<?php echo $nm . $sr; ?>">
												<td colspan="5">
													<div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div>
												</td>
											</tr>
										<?php } 
										else {
											$num = 0;
											while($row_rsSection = $query_rsSection->fetch()) {
												$sectionid = $row_rsSection['sectionid'];
												$section = $row_rsSection['section'];
												$num = $num + 1;
												$query_rsFieldAnswer = $db->prepare("SELECT a.fieldid, a.answer, q.fieldtype, q.label, q.multiple FROM  tbl_indicator_baseline_survey_answers a INNER JOIN tbl_indicator_baseline_survey_form_question_fields q on q.id =a.fieldid WHERE a.submissionid='$submissionid' AND q.sectionid='$sectionid'");
												$query_rsFieldAnswer->execute();
												$totalRows_rsFieldAnswer = $query_rsFieldAnswer->rowCount();

												?>
												<tr data-toggle="collapse" data-target=".qry<?php echo $nm. $sr . $num; ?>" class="collapse topic<?php echo $nm . $sr; ?>" style="background-color:#CDDC39">
													<td align="center" class="mb-0" style="background-color:#FFC107">
														<button class="btn btn-link"
															title="Click once to expand and Click twice to Collapse!!"> <i
																class="more-less fa fa-plus-square" style="font-size:16px"></i>
														</button>
													</td>
													<td align="center" style="background-color:#FFEB3B"> <?php echo $nm . "." . $sr. "." . $num; ?></td>
													<td colspan="3"><?php echo $section; ?></td>
												</tr>
												<tr class="collapse qry<?php echo $nm. $sr . $num; ?>"
													style="background-color:#b8f9cb; color:#FFF">
													<th style="background-color:#FFC107"></th>
													<th style="background-color:#FFEB3B">#</th>
													<th style="width:40%">Question </th>
													<th colspan="2" style="width:40%">Answer</th>
												</tr>
												<?php
												$nmb = 0;
												while($row_rsFieldAnswer = $query_rsFieldAnswer->fetch()) {
													$nmb++;
													$answerVal = $row_rsFieldAnswer['answer'];
													$label = $row_rsFieldAnswer['label'];
													$ftype = $row_rsFieldAnswer['fieldtype'];
													$fid = $row_rsFieldAnswer['fieldid'];
													$multiple = $row_rsFieldAnswer['multiple'];
													
													if($ftype=="checkbox-group" || $ftype=="radio-group" || $ftype=="select"){
														if($multiple==1){
															$answerArr = [];
															$arrAnswers = Explode(",", $answerVal); 
															foreach($arrAnswers as $arrAnswer){
																$query_rsFieldID = $db->prepare("SELECT * FROM  tbl_indicator_baseline_survey_form_question_field_values WHERE id='$arrAnswer'");
																$query_rsFieldID->execute();
																$row_rsFieldID = $query_rsFieldID->fetch();
																$answerArr[] = $row_rsFieldID["label"];
															}
															$answer = implode(",", $answerArr);
														}else{
															$query_rsFieldID = $db->prepare("SELECT * FROM  tbl_indicator_baseline_survey_form_question_field_values WHERE id='$answerVal'");
															$query_rsFieldID->execute();
															$row_rsFieldID = $query_rsFieldID->fetch();
															$answer = $row_rsFieldID["label"];
														}
													}else{
														$answer = $answerVal;
													}
													?>
													<tr class="collapse qry<?php echo $nm. $sr . $num; ?>" style="background-color:#FFF">
														<td style="background-color:#FFC107"></td>
														<td align="center" style="background-color:#FFEB3B"><?php echo $nm . "." . $sr . "." . $num . "." . $nmb; ?></td>
														<td><?php echo $label; ?></td>
														<td colspan="2"> <?php echo $answer; ?></td>
													</tr>
													<?php
												}
											}
										}
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