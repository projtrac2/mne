<form method="POST" name="submitevalfrm" id="survey" action="" enctype="multipart/form-data" autocomplete="off">
	<?php
	if(isset($_GET['fm']) && !empty($_GET['fm'])){
		$query_proj = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
		$query_proj->execute(array(":projid" => $projid));
		$row_proj = $query_proj->fetch();
		$project = $row_proj["projname"];
		$projlocs = explode(",",$row_proj["projlga"]);
		
		
		$query_questions = $db->prepare("SELECT * FROM tbl_project_evaluation_questions WHERE projid=:projid AND resultstype =:resultstype AND resultstypeid=:resultstypeid AND questiontype = 1");
		$query_questions->execute(array(":projid" => $projid, ":resultstype" => $resultstype, ":resultstypeid" => $resultstypeid));
		$row_questions = $query_questions->fetchAll();
		
		$query_indicator = $db->prepare("SELECT * FROM tbl_indicator i left join tbl_measurement_units u on u.id=i.indicator_unit WHERE indid=:indid");
		$query_indicator->execute(array(":indid" => $indid));
		$row_indicator = $query_indicator->fetch();
		$indicator = "";
		$unit = "";
		if($row_indicator){
			$unit = $row_indicator["unit"];
			$disaggregated = $row_indicator["indicator_disaggregation"];
			$ind_calculation_method = $row_indicator["indicator_calculation_method"];
			$indicator = $row_indicator["indicator_name"];
			
			if($disaggregated == 1){
				$query_indicator_disag_type = $db->prepare("SELECT * FROM tbl_indicator_measurement_variables_disaggregation_type c left join tbl_indicator_disaggregation_types t on t.id=c.disaggregation_type WHERE c.indicatorid=:indid");
				$query_indicator_disag_type->execute(array(":indid" => $indid));
				$row_indicator_disag_type = $query_indicator_disag_type->fetch();
				$category = $row_indicator_disag_type["category"];

				$query_indicator_disaggregations = $db->prepare("SELECT *, d.id AS disid FROM tbl_indicator_disaggregations d left join tbl_indicator_disaggregation_types t on t.id=d.disaggregation_type WHERE indicatorid=:indid");
				$query_indicator_disaggregations->execute(array(":indid" => $indid));
			}
		}
			
		$query_survey_location = $db->prepare("SELECT state FROM tbl_state WHERE id=:location");
		$query_survey_location->execute(array(":location" => $location));
		$row_survey_location = $query_survey_location->fetch();
		$survey_location = $row_survey_location["state"];

		echo '<h5 class="text-align-center bg-light-green" style="border-radius:4px; padding:10px"><strong>Project Name: '.$project.'</strong></h5>
		<fieldset class="scheduler-border">
			<legend class="scheduler-border bg-light-blue" style="border-radius:3px">Details:</legend>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">			
				<label for="" id="" class="control-label">Indicator:</label>
				<div class="form-input"> 
					<div class="form-control">'.$indicator.'</div>
				</div> 
			</div>
			<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">			
				<label for="" id="" class="control-label">Unit of Measure:</label>
				<div class="form-input"> 
					<div class="form-control">'.$unit.'</div>
				</div> 
			</div>
			<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">   		
				<label for="" id="" class="control-label">Survey Location: </label>
				<div class="form-input"> 
					<div class="form-control">'.$survey_location.'</div>
				</div> 
				<div class="form-select"> 
					<input name="location" type="hidden" value="'.$location.'">';
					/* <select name="location" class="form-select" style="border:#CCC thin solid; border-radius:5px; width:95%" data-live-search="false" required>
						<option value="">....Select survey location....</option>';
						foreach($projlocs as $projloc){
							$query_loc = $db->prepare("SELECT * FROM tbl_state WHERE id=:loc");
							$query_loc->execute(array(":loc" => $projloc));
							$row_loc = $query_loc->fetch();
							$loc = $row_loc["state"];
							echo '<option value="'.$projloc.'">'.$loc.'</option>';
						}
					echo '</select>'; */
				echo '</div> 
			</div>
			<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">  		
				<label for="" id="" class="control-label">Sample Size for '.$survey_location.':</label>
				<div class="form-input"> 
					<div class="form-control">'.$sample.'</div>
				</div> 
			</div>';
			if($disaggregated == 1){
			echo '<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">   		
				<label for="" id="" class="control-label">Disaggregation Type:</label>
				<div class="form-input"> 
					<div class="form-control">'.$category.'</div>
				</div> 
			</div>';
			}
			
			echo '
			<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">   		
				<label for="" id="" class="control-label">Target Respondents:</label>
				<div class="form-input"> 
					<div class="form-control">'.$targetrespondents.'</div>
				</div> 
			</div>
			<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">   		
				<label for="" id="" class="control-label">Survey Start Date:</label>
				<div class="form-input"> 
					<div class="form-control">'.$startdate.'</div>
				</div> 
			</div>
			<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">   		
				<label for="" id="" class="control-label">Survey End Date:</label>
				<div class="form-input"> 
					<div class="form-control">'.$enddate.'</div>
				</div> 
			</div>
		</fieldset>
		';

		$cnt = 0;
		if($disaggregated == 1){
			echo '
			<fieldset class="scheduler-border">
				<legend class="scheduler-border bg-light-blue" style="border-radius:3px">Survey Questions</legend>				
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="form-line">
						<select name="disaggregationid" id="disaggregationid" style="border:#CCC thin solid; border-radius:5px; height:35px; width:95%" data-live-search="false" required>
							<option value="">....Select disaggregation....</option>';
							while($row_indicator_disaggregations = $query_indicator_disaggregations->fetch()){
								$disaggregationid = $row_indicator_disaggregations["disid"];
								$disaggregation = $row_indicator_disaggregations["disaggregation"];
								echo '<option value="'.$disaggregationid.'">'.$disaggregation.'</option>';
							}
						echo '</select>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover" style="color:#000">
							<thead>
								<tr style="background-color:#607D8B; color:#FFF">
									<th width="5%">SN</th>
									<th width="60%">Question</th>
									<th width="35%">Answer</th>
								</tr>
							</thead>
							<tbody>';
								foreach ($row_questions as $field){
									$cnt++;
									$questionid = $field['id'];
									$question = $field['question'];
									$answertype = $field['answertype'];
									$answerlabels = $field['answerlabels'];
								
									echo '
									<tr><input type="hidden" name="questionid[]" id="questionid" value="'.$questionid.'">
										<td>'.$cnt.'</td>
										<td><label class="control-label">' . $question  . '</label></td>
										<td>
											<input name="answertype[]" type="hidden" value="' . $answertype . '">
											<div class="form-group form-line">';
												if($answertype == 1){
													echo '<input name="answer'.$questionid.'" type="number" id="answer' . $questionid . '" style="border:#CCC thin solid; border-radius:5px; height:30px; width:95%" required placeholder="Enter the answer">';
												}
												elseif($answertype == 2){
													$answerlabels = explode(",",$answerlabels);
													$multiples = '';
													foreach($answerlabels as $answerlabel){
														$multiples .= '<div class="checkbox-inline">
														  <input class="form-check-input" type="radio" name="answer'.$questionid.'" id="answer' .$answerlabel. $questionid.'" value="'.$answerlabel.'">
														  <label class="form-check-label" for="answer' . $answerlabel.$questionid.'">'.$answerlabel.'</label>
														</div>';
													}
													echo $multiples;
												}
												elseif($answertype == 3){
													$answerlabels = explode(",",$answerlabels);
													$checkbox = '';
													foreach($answerlabels as $answerlabel){
														$checkbox .= '<div class="form-check-inline">
														  <input class="form-check-input" type="checkbox" name="answer'.$questionid.'" id="answer' .$answerlabel. $questionid.'" value="'.$answerlabel.'">
														  <label class="form-check-label" for="answer' .$answerlabel. $questionid.'">'.$answerlabel.'</label>
														</div>
														';
													}
													echo $checkbox;
												}
												elseif($answertype == 4){
													$answerlabels = explode(",",$answerlabels);
													$dropdown = '<select name="answer'.$questionid.'" id="answer' . $questionid . '" style="border:#CCC thin solid; border-radius:5px; height:30px; width:95%" data-live-search="false" required>
														<option value="">....Select appropiate answer....</option>';
														foreach($answerlabels as $answerlabel){
															$dropdown .= '<option value="'.$answerlabel.'">'.$answerlabel.'</option>';
														}
														echo $dropdown.'
													</select>';
												}
												elseif($answertype == 5){
													echo '<textarea name="answer'.$questionid.'" id="answer' . $questionid . '" style="border:#CCC thin solid; border-radius:5px; width:95%" required></textarea>';
												}
												elseif($answertype == 6){
													echo '
													<input class="form-control" name="answer'.$questionid.'" type="file" id="file'.$questionid.'" required />
													';
												}
												echo '
											</div>
										</td>
									</tr>';
			
									if($field['questiontype'] == 1){
										//evaluation follow-up questions
										$query_survey_follow_up_questions =  $db->prepare("SELECT * FROM tbl_project_evaluation_questions WHERE parent_question=:parentquestionid");
										$query_survey_follow_up_questions->execute(array(":parentquestionid" => $questionid));
										$count_survey_follow_up_questions = $query_survey_follow_up_questions->rowCount();
										$sn = 0;
										if ($count_survey_follow_up_questions > 0) {
											while ($row_survey_follow_up_questions = $query_survey_follow_up_questions->fetch()) {
												$sn++;
												$followupsquestionid = $row_survey_follow_up_questions['id'];
												$followupsquestion = $row_survey_follow_up_questions['question'];
												$followupsquestiontype = "Follow Up";
												$followupsparent_question = $row_survey_follow_up_questions['parent_question'];
												$followupsanswertypeid = $row_survey_follow_up_questions['answertype'];
												$answerlabels = $row_survey_follow_up_questions['answerlabels'];
												$followupscalculation_method = $followupsanswerlabels = "N/A";
												
												echo '
												<tr><input type="hidden" name="questionid[]" id="questionid" value="'.$followupsquestionid.'">
													<td>'.$cnt.'.'.$sn.'</td>
													<td><label class="control-label">' . $followupsquestion  . '</label></td>
													<td>
														<input name="answertype[]" type="hidden" value="' . $answertype . '">
														<div class="form-group form-line">';
															if($followupsanswertypeid == 1){
																echo '<input name="answer'.$followupsquestionid.'" type="number" id="answer' . $followupsquestionid . '" style="border:#CCC thin solid; border-radius:5px; height:30px; width:95%" required placeholder="Enter the answer">';
															}
															elseif($followupsanswertypeid == 2){
																$answerlabels = explode(",",$answerlabels);
																$multiples = '';
																foreach($answerlabels as $answerlabel){
																	$multiples .= '<div class="checkbox-inline">
																	  <input class="form-check-input" type="radio" name="answer'.$followupsquestionid.'" id="answer' .$answerlabel. $followupsquestionid.'" value="'.$answerlabel.'">
																	  <label class="form-check-label" for="answer' . $answerlabel.$followupsquestionid.'">'.$answerlabel.'</label>
																	</div>';
																}
																echo $multiples;
															}
															elseif($followupsanswertypeid == 3){
																$answerlabels = explode(",",$answerlabels);
																$checkbox = '';
																foreach($answerlabels as $answerlabel){
																	$checkbox .= '<div class="form-check-inline">
																	  <input class="form-check-input" type="checkbox" name="answer'.$followupsquestionid.'" id="answer' .$answerlabel. $followupsquestionid.'" value="'.$answerlabel.'">
																	  <label class="form-check-label" for="answer' .$answerlabel. $followupsquestionid.'">'.$answerlabel.'</label>
																	</div>
																	';
																}
																echo $checkbox;
															}
															elseif($followupsanswertypeid == 4){
																$answerlabels = explode(",",$answerlabels);
																$dropdown = '<select name="answer'.$followupsquestionid.'" id="answer' . $followupsquestionid . '" style="border:#CCC thin solid; border-radius:5px; height:30px; width:95%" data-live-search="false" required>
																	<option value="">....Select appropiate answer....</option>';
																	foreach($answerlabels as $answerlabel){
																		$dropdown .= '<option value="'.$answerlabel.'">'.$answerlabel.'</option>';
																	}
																	echo $dropdown.'
																</select>';
															}
															elseif($followupsanswertypeid == 5){
																echo '<textarea name="answer'.$followupsquestionid.'" id="answer' . $followupsquestionid . '" style="border:#CCC thin solid; border-radius:5px; width:95%" required></textarea>';
															}
															elseif($followupsanswertypeid == 6){
																echo '
																<input class="form-control" name="answer'.$followupsquestionid.'" type="file" id="file'.$followupsquestionid.'" required />
																';
															}
															echo '
														</div>
													</td>
												</tr>';
											}
										}
									}
								}
							echo '
							</tbody>
						</table>
					</div>
				</div>
			</fieldset>';
		}else{
			echo '
			<fieldset class="scheduler-border">
				<legend class="scheduler-border bg-light-blue" style="border-radius:3px">Survey Questions</legend>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover" style="color:#000">
							<thead>
								<tr style="background-color:#607D8B; color:#FFF">
									<th width="5%">SN</th>
									<th width="60%">Question</th>
									<th width="35%">Answer</th>
								</tr>
							</thead>
							<tbody>';
								foreach ($row_questions as $field){
									$cnt++;
									$questionid = $field['id'];
									$question = $field['question'];
									$answertype = $field['answertype'];
									$answerlabels = $field['answerlabels'];
								
									echo '
									<tr><input type="hidden" name="questionid[]" id="questionid" value="'.$questionid.'">
										<td>'.$cnt.'</td>
										<td><label class="control-label">' . $question  . '</label></td>
										<td>
											<input name="answertype[]" type="hidden" value="' . $answertype . '">
											<div class="form-group form-line">';
												if($answertype == 1){
													echo '<input name="answer'.$questionid.'" type="number" id="answer' . $questionid . '" style="border:#CCC thin solid; border-radius:5px; height:30px; width:95%" required placeholder="Enter number">';
												}
												elseif($answertype == 2){
													$answerlabels = explode(",",$answerlabels);
													$multiples = '';
													foreach($answerlabels as $answerlabel){
														$multiples .= '<div class="checkbox-inline">
														  <input class="form-check-input" type="radio" name="answer'.$questionid.'" id="answer' .$answerlabel. $questionid.'" value="'.$answerlabel.'">
														  <label class="form-check-label" for="answer' . $answerlabel.$questionid.'">'.$answerlabel.'</label>
														</div>';
													}
													echo $multiples;
												}
												elseif($answertype == 3){
													$answerlabels = explode(",",$answerlabels);
													$checkbox = '';
													foreach($answerlabels as $answerlabel){
														$checkbox .= '<div class="form-check-inline">
														  <input class="form-check-input" type="checkbox" name="answer'.$questionid.'[]" id="answer' .$answerlabel. $questionid.'" value="'.$answerlabel.'">
														  <label class="form-check-label" for="answer' .$answerlabel. $questionid.'">'.$answerlabel.'</label>
														</div>
														';
													}
													echo $checkbox;
												}
												elseif($answertype == 4){
													$answerlabels = explode(",",$answerlabels);
													$dropdown = '<select name="answer'.$questionid.'" id="answer' . $questionid . '" style="border:#CCC thin solid; border-radius:5px; height:30px; width:95%" data-live-search="false" required>
														<option value="">....Select appropiate answer....</option>';
														foreach($answerlabels as $answerlabel){
															$dropdown .= '<option value="'.$answerlabel.'">'.$answerlabel.'</option>';
														}
														echo $dropdown.'
													</select>';
												}
												elseif($answertype == 5){
													echo '<textarea name="answer'.$questionid.'" id="answer' . $questionid . '" style="border:#CCC thin solid; border-radius:5px; width:95%" required
												placeholder = "Enter description"></textarea>';
												}
												elseif($answertype == 6){
													echo '
													<input class="form-control" name="answer'.$questionid.'" type="file" id="file'.$questionid.'" required />
													';
												}
												echo '
											</div>
										</td>
									</tr>';
									
			
									if($field['questiontype'] == 1){
										//evaluation follow-up questions
										$query_survey_follow_up_questions =  $db->prepare("SELECT * FROM tbl_project_evaluation_questions WHERE parent_question=:parentquestionid");
										$query_survey_follow_up_questions->execute(array(":parentquestionid" => $questionid));
										$count_survey_follow_up_questions = $query_survey_follow_up_questions->rowCount();
										$sn = 0;
										if ($count_survey_follow_up_questions > 0) {
											while ($row_survey_follow_up_questions = $query_survey_follow_up_questions->fetch()) {
												$sn++;
												$followupsquestionid = $row_survey_follow_up_questions['id'];
												$followupsquestion = $row_survey_follow_up_questions['question'];
												$followupsquestiontype = "Follow Up";
												$followupsparent_question = $row_survey_follow_up_questions['parent_question'];
												$followupsanswertypeid = $row_survey_follow_up_questions['answertype'];
												$answerlabels = $row_survey_follow_up_questions['answerlabels'];
												$followupscalculation_method = $followupsanswerlabels = "N/A";
												
												echo '
												<tr><input type="hidden" name="questionid[]" id="questionid" value="'.$followupsquestionid.'">
													<td>'.$cnt.'.'.$sn.'</td>
													<td><label class="control-label">' . $followupsquestion  . '</label></td>
													<td>
														<input name="answertype[]" type="hidden" value="' . $answertype . '">
														<div class="form-group form-line">';
															if($followupsanswertypeid == 1){
																echo '<input name="answer'.$followupsquestionid.'" type="number" id="answer' . $followupsquestionid . '" style="border:#CCC thin solid; border-radius:5px; height:30px; width:95%" required placeholder="Enter the answer">';
															}
															elseif($followupsanswertypeid == 2){
																$answerlabels = explode(",",$answerlabels);
																$multiples = '';
																foreach($answerlabels as $answerlabel){
																	$multiples .= '<div class="checkbox-inline">
																	  <input class="form-check-input" type="radio" name="answer'.$followupsquestionid.'" id="answer' .$answerlabel. $followupsquestionid.'" value="'.$answerlabel.'">
																	  <label class="form-check-label" for="answer' . $answerlabel.$followupsquestionid.'">'.$answerlabel.'</label>
																	</div>';
																}
																echo $multiples;
															}
															elseif($followupsanswertypeid == 3){
																$answerlabels = explode(",",$answerlabels);
																$checkbox = '';
																foreach($answerlabels as $answerlabel){
																	$checkbox .= '<div class="form-check-inline">
																	  <input class="form-check-input" type="checkbox" name="answer'.$followupsquestionid.'" id="answer' .$answerlabel. $followupsquestionid.'" value="'.$answerlabel.'">
																	  <label class="form-check-label" for="answer' .$answerlabel. $followupsquestionid.'">'.$answerlabel.'</label>
																	</div>
																	';
																}
																echo $checkbox;
															}
															elseif($followupsanswertypeid == 4){
																$answerlabels = explode(",",$answerlabels);
																$dropdown = '<select name="answer'.$followupsquestionid.'" id="answer' . $followupsquestionid . '" style="border:#CCC thin solid; border-radius:5px; height:30px; width:95%" data-live-search="false" required>
																	<option value="">....Select appropiate answer....</option>';
																	foreach($answerlabels as $answerlabel){
																		$dropdown .= '<option value="'.$answerlabel.'">'.$answerlabel.'</option>';
																	}
																	echo $dropdown.'
																</select>';
															}
															elseif($followupsanswertypeid == 5){
																echo '<textarea name="answer'.$followupsquestionid.'" id="answer' . $followupsquestionid . '" style="border:#CCC thin solid; border-radius:5px; width:95%" required></textarea>';
															}
															elseif($followupsanswertypeid == 6){
																echo '
																<input class="form-control" name="answer'.$followupsquestionid.'" type="file" id="file'.$followupsquestionid.'" required />
																';
															}
															echo '
														</div>
													</td>
												</tr>';
											}
										}
									}
								}
							echo '
							</tbody>
						</table>
					</div>
				</div>
			</fieldset>';
		}
		?>
		<div class="row clearfix">
			<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
			</div>
			<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" align="center">	
				<a href="public-survey-form?prj=<?php echo $encoded_projid; ?>&fm=<?php echo $encoded_formid; ?>&em=<?php echo $encoded_email; ?>" class="btn btn-warning" style="margin-right:10px">Cancel</a>
				<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" onclick="disablesubmit()"/>
				<input name="projid" type="hidden" id="projid" value="<?php echo $projid; ?>" />
				<input name="user_name" type="hidden" id="user_name" value="<?php echo $user; ?>" />
				<input name="formid" type="hidden" value="<?php echo $formid; ?>" />
			</div>
			<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
			</div>
		</div>
	<?php
	}
	?>
</form>	
