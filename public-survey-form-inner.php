<form method="POST" name="submitevalfrm" action="" enctype="multipart/form-data" autocomplete="off">
	<?php
	if(isset($_GET['fm']) && !empty($_GET['fm'])){
		$query_proj = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
		$query_proj->execute(array(":projid" => $projid));
		$row_proj = $query_proj->fetch();
		$project = $row_proj["projname"];
		$projlocs = explode(",",$row_proj["projstate"]);
		
		
		$query_questions = $db->prepare("SELECT * FROM tbl_project_outcome_evaluation_questions WHERE projid=:projid");
		$query_questions->execute(array(":projid" => $projid));
		$row_questions = $query_questions->fetchAll();
		
		$query_indicator = $db->prepare("SELECT * FROM tbl_indicator i left join tbl_measurement_units u on u.id=i.indicator_unit WHERE indid=:indid");
		$query_indicator->execute(array(":indid" => $indid));
		$row_indicator = $query_indicator->fetch();

		if(!empty($row_indicator)){
			$change = $row_indicator["indicator_name"];
			$unit = $row_indicator["unit"];
			$disaggregated = $row_indicator["indicator_disaggregation"];
			$indicator = $unit." of ".$change;
			
			if($disaggregated == 1){
				$query_indicator_disag_type = $db->prepare("SELECT * FROM tbl_indicator_measurement_variables_disaggregation_type c left join tbl_indicator_disaggregation_types t on t.id=c.disaggregation_type WHERE c.indicatorid=:indid");
				$query_indicator_disag_type->execute(array(":indid" => $indid));
				$row_indicator_disag_type = $query_indicator_disag_type->fetch();
				$category = $row_indicator_disag_type["category"];

				$query_indicator_disaggregations = $db->prepare("SELECT *, d.id AS disid FROM tbl_indicator_disaggregations d left join tbl_indicator_disaggregation_types t on t.id=d.disaggregation_type WHERE indicatorid=:indid");
				$query_indicator_disaggregations->execute(array(":indid" => $indid));
			}
		}
		
		if($enumeratortype == 1){
			$query_assign_user = $db->prepare("SELECT * FROM users u left join tbl_projteam2 t on t.ptid=u.pt_id WHERE t.email='$email'");
			$query_assign_user->execute();
			$row_assign_user = $query_assign_user->fetch();
			$user = $row_assign_user["userid"];		
		}

		echo '<h5 class="text-align-center bg-light-green" style="border-radius:4px; padding:10px"><strong>Project Name: '.$project.'</strong></h5>
		<fieldset class="scheduler-border">
			<legend class="scheduler-border bg-light-blue" style="border-radius:3px">Details:</legend>
			<div class="col-lg-12 col-md-12">			
				<label for="" id="" class="control-label">Change to be measured:</label>
				<div class="form-input"> 
					<div class="form-control">'.$change.'</div>
				</div> 
			</div>
			<div class="col-lg-6 col-md-6">			
				<label for="" id="" class="control-label">Unit of Measure:</label>
				<div class="form-input"> 
					<div class="form-control">'.$unit.'</div>
				</div> 
			</div>
			<div class="col-lg-6 col-md-6">  		
				<label for="" id="" class="control-label">Sample Size:</label>
				<div class="form-input"> 
					<div class="form-control">'.$sample.'</div>
				</div> 
			</div>';
			if($disaggregated == 1){
			echo '<div class="col-lg-6 col-md-6">   		
				<label for="" id="" class="control-label">Disaggregation Type:</label>
				<div class="form-input"> 
					<div class="form-control">'.$category.'</div>
				</div> 
			</div>';
			}
			echo '
			<div class="col-lg-6 col-md-6">   		
				<label for="" id="" class="control-label">Survey Start Date:</label>
				<div class="form-input"> 
					<div class="form-control">'.$startdate.'</div>
				</div> 
			</div>
			<div class="col-lg-6 col-md-6">   		
				<label for="" id="" class="control-label">Survey End Date:</label>
				<div class="form-input"> 
					<div class="form-control">'.$enddate.'</div>
				</div> 
			</div>
			<div class="col-lg-6 col-md-6">   		
				<label for="" id="" class="control-label">Survey Location: </label>
				<div class="form-select"> 
					<select name="location" class="form-select" style="border:#CCC thin solid; border-radius:5px; width:95%" data-live-search="false" required>
						<option value="">....Select survey location....</option>';
						foreach($projlocs as $projloc){
							$query_loc = $db->prepare("SELECT * FROM tbl_state WHERE id=:loc");
							$query_loc->execute(array(":loc" => $projloc));
							$row_loc = $query_loc->fetch();
							$loc = $row_loc["state"];
							echo '<option value="'.$projloc.'">'.$loc.'</option>';
						}
					echo '</select>
				</div> 
			</div>
		</fieldset>
		';

		$cnt = 0;
		if($disaggregated == 1){
			echo '
			<fieldset class="scheduler-border">
				<legend class="scheduler-border bg-light-blue" style="border-radius:3px">Survey Questions</legend>				
					<div class="col-lg-4 col-md-4">
						<div class="form-line">
							<select name="disaggregationid" id="disaggregationid" style="border:#CCC thin solid; border-radius:5px; width:95%" data-live-search="false" required>
								<option value="">....Select disaggregation....</option>';
								while($row_indicator_disaggregations = $query_indicator_disaggregations->fetch()){
									$disaggregationid = $row_indicator_disaggregations["disid"];
									$disaggregation = $row_indicator_disaggregations["disaggregation"];
									echo '<option value="'.$disaggregationid.'">'.$disaggregation.'</option>';
								}
							echo '</select>
						</div>
					</div>
				<div class="col-lg-12 col-md-12">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover" style="color:#000">
							<thead>
								<tr style="background-color:#607D8B; color:#FFF">
									<th width="5%">SN</th>
									<th width="70%">Question</th>
									<th width="25%">Answer</th>
								</tr>
							</thead>
							<tbody>';
								foreach ($row_questions as $field){
									$cnt++;
									$questionid = $field['id'];
									$question = $field['question'];
								
									echo '
									<tr><input type="hidden" name="questionid[]" id="questionid" value="'.$questionid.'">
										<td>'.$cnt.'</td>
										<td><label class="control-label">' . $question  . '</label></td>
										<td><div class="form-line">
												<select name="answer[]" id="answer' . $questionid . '" style="border:#CCC thin solid; border-radius:5px; width:95%" data-live-search="false" required>
													<option value="">....Select appropiate answer....</option>
													<option value="0">No</option>
													<option value="1">Yes</option>
												</select>
											</div>
										</td>
									</tr>';
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
				<div class="col-lg-12 col-md-12">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover" style="color:#000">
							<thead>
								<tr style="background-color:#607D8B; color:#FFF">
									<th width="5%">SN</th>
									<th width="70%">Question</th>
									<th width="25%">Answer</th>
								</tr>
							</thead>
							<tbody>';
								foreach ($row_questions as $field){
									$cnt++;
									$questionid = $field['id'];
									$question = $field['question'];
								
									echo '
									<tr><input type="hidden" name="questionid[]" id="questionid" value="'.$questionid.'">
										<td>'.$cnt.'</td>
										<td><label class="control-label">' . $question  . '</label></td>
										<td><div class="form-line">
												<select name="answer[]" id="answer' . $questionid . '" style="border:#CCC thin solid; border-radius:5px; border-radius:5px" data-live-search="false" required>
													<option value="">....Select the appropiate answer....</option>
													<option value="0">No</option>
													<option value="1">Yes</option>
												</select>
											</div>
										</td>
									</tr>';
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
			<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
			</div>
			<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">	
				<a href="public-evaluation-form?prj=<?php echo $encoded_projid; ?>&fm=<?php echo $encoded_formid; ?>&em=<?php echo $encoded_email; ?>" class="btn btn-warning" style="margin-right:10px">Cancel</a>
				<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" />
				<input name="projid" type="hidden" id="projid" value="<?php echo $projid; ?>" />
				<input name="user_name" type="hidden" id="user_name" value="<?php echo $user; ?>" />
				<input name="formid" type="hidden" value="<?php echo $formid; ?>" />
			</div>
			<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
			</div>
		</div>
	<?php
	}
	?>
</form>	