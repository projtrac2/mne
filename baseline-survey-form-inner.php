<form method="POST" name="submitsvyfrm" action="" enctype="multipart/form-data" autocomplete="off">
	<?= csrf_token_html(); ?>
	<?php
	if (isset($_GET['fm']) && !empty($_GET['fm'])) {
		$query_rsForm = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_forms WHERE id=:formid");
		$query_rsForm->execute(array(":formid" => $formid));
		$row_rsForm = $query_rsForm->fetch();

		$query_rsSection = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_form_sections WHERE formid=:formid");
		$query_rsSection->execute(array(":formid" => $formid));
		$row_rsSection = $query_rsSection->fetchAll();
		$totalRows_rsSection = $query_rsSection->rowCount();

		echo '<h5 class="text-align-center bg-light-green" style="border-radius:4px; padding:10px"><strong>FORM NAME: ' . $row_rsForm["form_name"] . '</strong></h5>
		<h5 class="text-align-center bg-brown" style="border-radius:4px; padding:10px"><strong>SURVEY LOCATION: ' . $surveyLevel1 . ' ' . $level1label . ' - ' . $surveyLevel2 . ' ' . $level2label . ' - ' . $surveyLevel3 . ' ' . $level3label . '</strong></h5>';
		if ($totalRows_rsSection == 0) {
			echo '<h4 class="text-align-center" style="color:red">No records found in the field table</h4>';
		} else {
			$count = 0;
			foreach ($row_rsSection as $key) {
				$section = $key['section'];
				$count++;
				$sectionid = $key['id'];
				$query_rsFormfield = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_form_question_fields WHERE formid=:formid AND sectionid=:sectionid ORDER BY id");
				$query_rsFormfield->execute(array(":formid" => $formid, ":sectionid" => $sectionid));
				$row_rsFormfield = $query_rsFormfield->fetchAll();
				$totalRows_rsFormfield = $query_rsFormfield->rowCount();

				if ($totalRows_rsFormfield == 0) {
					echo '<h4 class="text-align-center" style="color:red">No records found in the field table</h4>';
				} else {
					$cnt = 0;
					echo '
					<fieldset class="scheduler-border">
						<legend class="scheduler-border bg-light-blue" style="border-radius:3px">Objective ' . $count . ':</label> ' . $section . '</legend>';
					echo '
					<input type="hidden" name="sectionid[]" id="sectionid[]" value="' . $sectionid . '">
					<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover" style="color:#000">
							<thead>
								<tr style="background-color:#607D8B; color:#FFF">
									<th width="3%">SN</th>
									<th width="97%">Question</th>
								</tr>
							</thead>
							<tbody>';
					foreach ($row_rsFormfield as $field) {
						$cnt++;
						$type = $field['fieldtype'];
						$requireValidOption = $field['requirevalidoption'];
						$label = $field['label'];
						$subtype = $field['subtype'];
						$style = $field['style'];
						$name = $field['fieldname'];
						$placeholder = $field['placeholder'];
						$description = $field['fielddesc'];
						$other = $field['other'];
						$maxlength = $field['fieldmaxlength'];
						$max = $field['fieldmin'];
						$min = $field['fieldmax'];
						$multiple = $field['multiple'];
						$fieldid = $field['id'];
						//echo '<input type="hidden" name="fieldid[]" id="fieldid" value="'.$fieldid.'">';
						if ($type == "textarea") {
							echo '
										<tr><input type="hidden" name="fieldid[]" id="fieldid" value="' . $fieldid . '">
											<td>' . $cnt . '</td>
											<td><label class="control-label">' . $label  . '*: <font align="left" style="background-color:#eff2f4">
												(' .  $placeholder . '.) </font></label>
												<div class="form-line">
													<p align="left">
														<textarea name="answer[]" cols="45" rows="5" class="txtboxes" id="' . $name . $fieldid . '" required="required"
															style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"
															placeholder="' . $placeholder . '"></textarea>
														<script>
														CKEDITOR.replace("' . $name . $fieldid . '", {
															on: {
																instanceReady: function(ev) {
																	// Output paragraphs as <p>Text</p>.
																	this.dataProcessor.writer.setRules("p", {
																		indent: false,
																		breakBeforeOpen: false,
																		breakAfterOpen: false,
																		breakBeforeClose: false,
																		breakAfterClose: false
																	});
																	this.dataProcessor.writer.setRules("ol", {
																		indent: false,
																		breakBeforeOpen: false,
																		breakAfterOpen: false,
																		breakBeforeClose: false,
																		breakAfterClose: false
																	});
																	this.dataProcessor.writer.setRules("ul", {
																		indent: false,
																		breakBeforeOpen: false,
																		breakAfterOpen: false,
																		breakBeforeClose: false,
																		breakAfterClose: false
																	});
																	this.dataProcessor.writer.setRules("li", {
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
											</td>
										</tr>';
						} else if ($type == "radio-group") {
							echo '
										<tr><input type="hidden" name="rdid' . $fieldid . '" id="fieldid" value="' . $fieldid . '">
											<td>' . $cnt . '</td>
											<td><label class="control-label">' . $label  . '*:</label>
												<div class="form-line">';
							$query_rsValue = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_form_question_field_values WHERE fieldid=:field");
							$query_rsValue->execute(array(":field" => $fieldid));
							$row_rsValue = $query_rsValue->fetchAll();
							$totalRows_rsValue = $query_rsValue->rowCount();
							if ($totalRows_rsValue == 0) {
								echo '<h4 class="text-align-center" style="color:red">No records found in the field table</h4>';
							} else {
								$nm = 0;
								echo '
														<div class="demo-radio-button">';
								foreach ($row_rsValue as $row) {
									$nm++;
									$lab = $row['label'];
									$option = $row['val'];
									$vlid = $row['id'];
									echo '
																<input type="radio" name="rd' . $fieldid . '" id="rd' . $sectionid . $fieldid . $vlid . '" value="' . $vlid . '" class="with-gap radio-col-green"  required/>
																<label for="rd' . $sectionid . $fieldid . $vlid . '">' . $lab . '</label>';
								}
								echo '</div>';
							}
							echo '</div>
											</td>
										</tr>';
						} else if ($type == "select" || $type == "autocomplete") {
							$query_rsValue = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_form_question_field_values WHERE fieldid=:field");
							$query_rsValue->execute(array(":field" => $fieldid));
							$row_rsValue = $query_rsValue->fetchAll();
							$totalRows_rsValue = $query_rsValue->rowCount();
							if ($totalRows_rsValue == 0) {
								echo '<h4 class="text-align-center" style="color:red">No records found in the field table</h4>';
							} else {
								if ($multiple == 1) {
									echo '
												<tr><input type="hidden" name="smid[]" id="fieldid" value="' . $fieldid . '">
													<td>' . $cnt . '</td>
													<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
														<div class="form-line">
															<select name="sm' . $fieldid . '[]" id="' . $name . '" class="selectpicker" multiple style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
																<option value="">....Select ' . $name . ' first....</option>';
									foreach ($row_rsValue as $row) {
										$desc = $row['label'];
										$option = $row['val'];
										$vlid = $row['id'];
										echo '<option value="' . $vlid . '">' . $desc . '</option>';
									}
									echo '</select>
														</div>
													</td>
												</tr>';
								} else {
									echo '
												<tr><input type="hidden" name="fieldid[]" id="fieldid" value="' . $fieldid . '">
													<td>' . $cnt . '</td>
													<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
														<div class="form-line">
															<select name="answer[]" id="' . $name . '" class="form-control" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
																<option value="">....Select ' . $name . ' first....</option>';
									foreach ($row_rsValue as $row) {
										$desc = $row['label'];
										$option = $row['val'];
										$vlid = $row['id'];
										echo '<option value="' . $vlid . '">' . $desc . '</option>';
									}
									echo '</select>
														</div>
													</td>
												</tr>';
								}
							}
						} else if ($type == "date") {
							echo '
										<tr><input type="hidden" name="fieldid[]" id="fieldid" value="' . $fieldid . '">
											<td>' . $cnt . '</td>
											<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
												<div class="form-line">
													<input type="date" name="answer[]" id="" class="form-control" placeholder="' . $placeholder . '" aria-describedby="helpId"">
												</div>
											</td>
										</tr>';
						} else if ($type == "header") {
							echo '
										<tr><input type="hidden" name="fieldid[]" id="fieldid" value="' . $fieldid . '">
											<td>' . $cnt . '</td>
											<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
												<div class="form-line">
												<' . $subtype . '>' . $label . '</' . $subtype . '>
												</div>
											</td>
										</tr>';
						} else if ($type == "number") {
							echo '
										<tr><input type="hidden" name="fieldid[]" id="fieldid" value="' . $fieldid . '">
											<td>' . $cnt . '</td>
											<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
												<div class="form-line">
													<input type="number" name="answer[]" id="" class="form-control" placeholder="' . $placeholder . '" aria-describedby="helpId" max="' . $max . '" min=" ' . $min . ' ">
													<small id="helpId" class="text-muted">' . $label . '</small>
												</div>
											</td>
										</tr>';
						} else if ($type == "paragraph") {
							echo '
										<tr><input type="hidden" name="fieldid[]" id="fieldid" value="' . $fieldid . '">
											<td>' . $cnt . '</td>
											<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
												<div class="form-line">
													<' . $subtype . '> </' . $subtype . '>
												</div>
											</td>
										</tr>';
						} else if ($type == "checkbox-group") {
							echo '
										<tr><input type="hidden" name="chkid[]" id="chkid" value="' . $fieldid . '">
											<td>' . $cnt . '</td>
											<td><label class="control-label">' . $label  . '*:</label>
												<div class="form-line">';
							$query_rsValue = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_form_question_field_values WHERE fieldid=:field");
							$query_rsValue->execute(array(":field" => $fieldid));
							$row_rsValue = $query_rsValue->fetchAll();
							$totalRows_rsValue = $query_rsValue->rowCount();
							if ($totalRows_rsValue == 0) {
								echo '<h4 class="text-align-center" style="color:red">No records found in the field table</h4>';
							} else {
								$nm = 0;
								echo '
														<div class="demo-checkbox">';
								foreach ($row_rsValue as $row) {
									$nm++;
									$lab = $row['label'];
									$option = $row['val'];
									$vlid = $row['id'];
									echo '
																<input type="checkbox" name="chk' . $fieldid . '[]" id="' . $sectionid . $fieldid . $vlid . '" value="' . $vlid . '" class="filled-in chk-col-light-blue"/>
																<label for="' . $sectionid . $fieldid . $vlid . '">' . $lab . '</label>';
								}
								echo '</div>';
							}
							echo '</div>
											</td>
										</tr>';
						} else if ($type == "autocomplete") {
							$query_rsValue = $db->prepare("SELECT * FROM tbl_indicator_baseline_survey_form_question_field_values WHERE fieldid=:field");
							$query_rsValue->execute(array(":field" => $fieldid));
							$row_rsValue = $query_rsValue->fetchAll();
							$totalRows_rsValue = $query_rsValue->rowCount();
							if ($totalRows_rsValue == 0) {
								echo	'<h4 class="text-align-center" style="color:red">No records found in the field table</h4>';
							} else {
								echo '<input type="hidden" name="fieldid[]" id="fieldid" value="' . $fieldid . '">';
							}
						} elseif ($type == "text") {
							echo '
										<tr><input type="hidden" name="fieldid[]" id="fieldid" value="' . $fieldid . '">
											<td>' . $cnt . '</td>
											<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
												<div class="form-line">
													<input type=" ' . $subtype . '" name="answer[]" id="' . $name . '" class="form-control" placeholder="' . $placeholder . '" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
												</div>
											</td>
										</tr>';
						} elseif ($type == "file") {
							if ($multiple == 1) {
								echo '
											<tr><input type="hidden" name="fieldid[]" id="fieldid" value="' . $fieldid . '">
												<td>' . $cnt . '</td>
												<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
													<div class="form-line">
														<input type="file" name="answer[]" id="' . $name . '" multiple class="form-control form-control-file">
														<small id="helpId" class="text-muted">' . $placeholder . '</small>
													</div>
												</td>
											</tr>';
							} else {
								echo '
											<tr><input type="hidden" name="fieldid[]" id="fieldid" value="' . $fieldid . '">
												<td>' . $cnt . '</td>
												<td><label class="control-label" for="' . $name . '">' . $label  . '*:</label>
													<div class="form-line">
														<input type="file" name="answer[]" id="' . $name . '"  class="form-control form-control-file">
														<small id="helpId" class="text-muted">' . $placeholder . '</small>
													</div>
												</td>
											</tr>';
							}
						}
					}
					echo '
							</tbody>
						</table>
					</div>
					</div>';
				}
				echo '</fieldset>';
			}
		}
	}
	?>
	<div class="row clearfix">
		<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
		</div>
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
			<a href="baseline-survey-form?ind=<?php echo $encoded_indid; ?>&fm=<?php echo $encoded_formid; ?>&em=<?php echo $encoded_email; ?>" class="btn btn-warning" style="margin-right:10px">Cancel</a>
			<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" />
			<input name="indid" type="hidden" id="indid" value="<?php echo $indid; ?>" />
			<input name="locid" type="hidden" id="locid" value="<?php echo $locid; ?>" />
			<input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
			<input name="formid" type="hidden" value="<?php echo $formid; ?>" />
		</div>
		<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
		</div>
	</div>
</form>