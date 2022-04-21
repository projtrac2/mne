<section class="content" style="margin-top:-20px; padding-bottom:0px">
    <div class="container-fluid">
        <div class="row clearfix" style="margin-top:10px">
            <div class="block-header">
                <?php
                echo $results;
                ?>
            </div>
            <!-- Advanced Form Example With Validation -->
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
                                <tr>
                                    <td width="100%" height="35" style="padding-left:5px; background-color:#000; color:#FFF" bgcolor="#000000">
                                        <div align="left"><i class="fa fa-list-alt" aria-hidden="true"></i> ADD MONITORING AND EVALUATION </strong></div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="body">
                        <div style="margin-top:5px">
                            <form id="addprogform" method="POST" name="addprogform" action="" onsubmit="return formVal()" enctype="multipart/form-data" autocomplete="off">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                        <i class="fa fa-plus-square" aria-hidden="true"></i> Add Monitoring and Evaluation Details
                                    </legend>
                                    <div class="col-md-12">
                                        <label class="control-label" style="color:#0b548f; font-size:16px">Project: <u><?php echo $project; ?></u></label>
                                    </div>
									<?php if($projevaluation == 1){ ?>
										<fieldset class="scheduler-border">
											<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">1. Add Outcome Details </legend>
											<div class="col-md-12">
												<label for="outcomeIndicator" class="control-label">Outcome *:</label>
												<div class="form-line">
													<input type="text" name="outcome" id="outcome" value="" class="form-control" required>
												</div>
											</div>
											<div class="col-md-2">
												<label class="control-label">Unit of Measure <span id="impunit"></span>*:</label>
												<div class="form-input">
													<input type="text" name="outcomeunitofmeasure" value="" readonly id="outcomeunitofmeasure" class="form-control" required="required">
												</div>
											</div>
											<div class="col-md-1">
												<label for="outcomeunitofmeasure" class="control-label">&nbsp;</label>
												<div class="form-input">
													<div class="form-control" disabled> of </div>
												</div>
											</div>
											<div class="col-md-9">
												<label for="outcomeName" class="control-label">Change to be measured *:</label>
												<div class="form-input"> 
													<select name="outcomeIndicator" id="outcomeIndicator" onchange="get_outcome_details()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
														<option value="">.... Select from list ....</option>
														<?php                                         
														do {
														?>
															<option value="<?php echo $row_rsOutcomeIndicators['indid'] ?>"><?php echo $row_rsOutcomeIndicators['indicator_name'] ?></option>
														<?php
														} while ($row_rsOutcomeIndicators = $query_rsOutcomeIndicators->fetch());
														?>
													</select>
												</div>
											</div>
											
											<div class="col-md-12 row"> 
												<div class="col-md-4"> 
													<label for="outcom_calc_method" class="control-label">Calculation Method *:</label>
													<div class="form-input">
														<input type="text" name="outcom_calc_method" value="" readonly id="outcom_calc_method" class="form-control" required="required">
													</div>
												</div> 
												<div class="col-md-8">
													<label class="control-label">&nbsp; </label>
													<div class="form-line"> 
														&nbsp; 
													</div>
												</div>
											</div>
											<br/>
											<br/>
											<br/>
											<br/>
											<br/>
											<br/>
											<br/>
											<br/>
											<br/>
											<h4><u>Data collection Plan:</u></h4> 
											<div class="col-md-4">
												<label for="outcomeName" class="control-label">Source of data *:</label>
												<div class="form-input"> 
													<select name="outcomedataSource" id="outcomedataSource" onchange="add_questions()" class="form-control" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
														<option value="">.... Select from list ....</option>
														<option value="1">Primary</option>
														<option value="2">Secondary</option>
													</select>
												</div>
											</div>
											<div class="col-md-4">
												<label class="control-label">Timing (Years)*:</label>
												<div class="form-line">
													<input type="hidden" name="outcomeEvaluation" id="outcomeEvaluation">
													<input type="number" name="outcomeEvaluationFreq" id="outcomeEvaluationFreq" onchange="outcome_Evaluation()" onkeyup="outcome_Evaluation()" class="form-control" placeholder="Enter Number of Year" required="required">
												</div>
											</div>  
											<div class="col-md-12 questions">
												<label class="control-label">Key Questions</label>
												<div class="table-responsive">
													<table class="table table-bordered table-striped table-hover" id="impact_table" style="width:100%">
														<thead>
															<tr>
																<th width="5%">#</th>
																<th width="90%">Question</th>
																<th width="5%">
																	<button type="button" name="addplus" id="addplus" onclick="add_row_question();" class="btn btn-success btn-sm">
																		<span class="glyphicon glyphicon-plus"></span>
																	</button>
																</th>
															</tr>
														</thead>
														<tbody id="questions_table_body">
															<?php
															$orowno = 1;
															/* while($row_outcomeevalqstns = $query_outcomeevalqstns->fetch()) {
																$question = $row_outcomeevalqstns['question'];
																$questionid = $row_outcomeevalqstns['id'];
																$orowno++; */
																?>
																<tr id="questionrow<?= $orowno ?>">
																	<td> <?= $orowno ?> </td>
																 
																	<td>
																		<input type="text" name="questions[]" id="questions<?= $orowno ?>" value="<?= $question ?>" placeholder="Enter evaluation question" class="form-control querry" required />
																	</td>
																	<td>
																		<?php
																		if ($orowno != 1) {
																		?>
																			<button type="button" class="btn btn-danger btn-sm" id="delete" onclick='delete_row_question("questionrow<?= $orowno ?>")'>
																				<span class="glyphicon glyphicon-minus"></span>
																			</button>
																		<?php
																		}
																		?>
																	</td>
																</tr>
															<?php
															//}
															?>
														</tbody>
													</table>
												</div>
											</div> 
											<div class="col-md-12">
												<label class="control-label">Outcome Risks and Assumptions </label>
												<div class="table-responsive">
													<table class="table table-bordered table-striped table-hover" id="impact_table" style="width:100%">
														<thead>
															<tr>
																<th width="5%">#</th>
																<th width="30%">Outcome Risks Category</th>
																<th width="60%">Assumption/s</th>
																<th width="5%">
																	<button type="button" name="addplus" id="addplus" onclick="add_row_outcome();" class="btn btn-success btn-sm">
																		<span class="glyphicon glyphicon-plus"></span>
																	</button>
																</th>
															</tr>
														</thead>
														<tbody id="outcome_table_body">
															<tr id="outcomerow0">
																<td> 1 </td>
																<td>
																	<select data-id="0" name="outcomerisk[]" id="outcomeriskrow0" class="form-control  selected_outcome" required="required">
																		<?php
																		$query_rsRisk =  $db->prepare("SELECT * FROM tbl_projrisk_categories ");
																		$query_rsRisk->execute();
																		$row_rsRisk = $query_rsRisk->fetch();
																		$totalRows_rsRisk = $query_rsRisk->rowCount();

																		$input = '<option value="">... Select from list ...</option>';
																		if ($totalRows_rsRisk > 0) {
																			do {
																				$type = explode(',',$row_rsRisk['type']); 
																				if(in_array(2, $type)) {
																					$input .= '<option value="' . $row_rsRisk['rskid'] . '">' . $row_rsRisk['category'] . ' </option>';
																				}
																			} while ($row_rsRisk = $query_rsRisk->fetch());
																		} else {
																			$input .= '<option value="">No Risks Found</option>';
																		}
																		echo $input;
																		?>
																	</select>
																</td>
																<td>
																	<input type="text" name="outcome_assumptions[]" id="outcome_assumptions0" placeholder="Enter" class="form-control"  required />
																</td>
																<td>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
											<div class="col-md-6">
												<label class="control-label">Responsible *:</label>
												<div class="form-line">
													<select name="responsible" id="responsible" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
														<option value="">.... Select from list ....</option>
														<?php
														$query_reportUser =  $db->prepare("SELECT * FROM tbl_projteam2 ");
														$query_reportUser->execute();
														$row_reportUser = $query_reportUser->fetch();
														do {
														?>
															<option value="<?php echo $row_reportUser['ptid'] ?>"><?php echo $row_reportUser['fullname'] ?></option>
														<?php
														} while ($row_reportUser = $query_reportUser->fetch());
														?>
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<label class="control-label">Report User *:</label>
												<div class="form-line">
													<select name="reportUser[]" multiple id="reportUser" data-actions-box="true" class="form-control show-tick selectpicker" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
														<?php
														$query_reportUser =  $db->prepare("SELECT * FROM tbl_pmdesignation ");
														$query_reportUser->execute();
														$row_reportUser = $query_reportUser->fetch();
														do {
														?>
															<option value="<?php echo $row_reportUser['moid'] ?>"><?php echo $row_reportUser['designation'] ?></option>
														<?php
														} while ($row_reportUser = $query_reportUser->fetch());
														?>
													</select>
												</div>
											</div> 
										</fieldset>
									<?php } ?>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">2. Add Output Details </legend>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover" id="" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th width="3%">#</th>
                                                        <th width="47%">Output</th>
                                                        <th width="30%">Indicator</th>
                                                        <th width="15%">Add Details</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="">
                                                    <?php 
                                                    //get project output details 
                                                    $query_OutputData = $db->prepare("SELECT * FROM  tbl_project_details WHERE projid = '$projid' ORDER BY id ASC");
                                                    $query_OutputData->execute();
                                                    $countrows_OutpuData = $query_OutputData->rowCount();
                                                    $row_OutputData =  $query_OutputData->fetch();
                                                    $Ocounter = 0;
                                                    if ($countrows_OutpuData > 0) {
                                                        do {
                                                            $input = '';
                                                            $body = '';
                                                            $Ocounter++;
                                                            $year =  $row_OutputData['year'];
                                                            $projoutputID =  $row_OutputData['id'];
                                                            $projduration =  $row_OutputData['duration'];
                                                            $outputid =  $row_OutputData['outputid'];
                                                            $indicatorId =  $row_OutputData['indicator'];

                                                            //get output name 
                                                            $query_Output = $db->prepare("SELECT output, id FROM `tbl_progdetails`  WHERE id = '$outputid'");
                                                            $query_Output->execute();
                                                            $rows_Outputcount = $query_Output->rowCount();
                                                            $row_output =  $query_Output->fetch();
                                                            $outputname =  $row_output['output'];

                                                            //get indicator name 
															$query_dep = $db->prepare("SELECT * FROM  tbl_indicator  WHERE  indid ='$indicatorId' ");
															$query_dep->execute();
															$row = $query_dep->fetch();
															$indname =  $row['indicator_name'];
															$unit =  $row['indicator_unit'];
															$calcid =  $row['indicator_calculation_method'];

															$query_Indicator_cal = $db->prepare("SELECT * FROM tbl_indicator_calculation_method WHERE id =:calcid ");
															$query_Indicator_cal->execute(array(':calcid'=>$calcid));
															$row_cal = $query_Indicator_cal->fetch();
															$op_calc_method = $row_cal['method'];
                                                           
															$query_rsIndType = $db->prepare("SELECT * FROM tbl_indicator_beneficiaries WHERE indicatorid= '$indicatorId'");
															$query_rsIndType->execute();
															$row_rsIndType = $query_rsIndType->fetch();
															$indIndTypecount = $query_rsIndType->rowCount();
															$dissagragated =0;
 
															if($indIndTypecount > 0){
																$dissagragated = $row_rsIndType['dissagragated'];
															}  

                                                           $query_Indicator = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id =:unit ");
                                                           $query_Indicator->execute(array(":unit" => $unit));
                                                           $row = $query_Indicator->fetch();
                                                           $op_unitofmeasure = $row['unit'];
                                                           
                                                    ?>
                                                            <tr>
                                                                <td><?= $Ocounter ?></td>
                                                                <td>
                                                                    <?= $outputname ?>
                                                                </td>
                                                                <td>
                                                                    <?= $indname ?>
                                                                    <input type="hidden" name="outputid[]" id="outputid<?= $projoutputID ?>" value="<?= $projoutputID ?>" />
                                                                    <input type="hidden" name="outputName[]" id="outputName<?= $projoutputID ?>" value="<?= $outputname ?>" />
                                                                    <input type="hidden" name="indicatorid[]" id="indicatorid<?= $projoutputID ?>" value="<?= $indicatorId ?>" />
                                                                    <input type="hidden" name="ben_diss[]" id="ben_diss<?= $projoutputID ?>" value="<?= $dissagragated ?>" />
                                                                    <input type="hidden" name="indicatorName[]" id="indicatorName<?= $projoutputID ?>" value="<?= $indname ?>" />
                                                                    <input type="hidden" name="output_details_unitof_measure[]" id="output_details_unitof_measure<?= $projoutputID ?>" value="<?= $op_unitofmeasure ?>" />
                                                                    <input type="hidden" name="output_calculation_method[]" id="output_calculation_method<?= $projoutputID ?>" value="<?= $op_calc_method ?>" />
                                                                    <input type="hidden" name="output_details_id[]" data-id="<?= $outputname ?>" id="output_details_id<?= $projoutputID ?>" value="" />
                                                                    <input type="hidden" name="projid" id="projidid" value="<?= $projid ?>" />
                                                                </td>
                                                                <td>
                                                                    <a type=" button" data-toggle="modal" data-target="#outputItemModal" onclick="getopdetails(<?= $projoutputID ?>)" id="outputItemModalBtn<?= $projoutputID ?>"> Add Details </a>
                                                                </td>
                                                            </tr>
                                                    <?php
                                                        } while ($row_OutputData =  $query_OutputData->fetch());
                                                    } else {
                                                        echo '<tr><td colspan="4">No Output Found</td></tr>';
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </fieldset>
                                    <div class="col-md-12" style="margin-top:15px" align="center">
										<?php if($projevaluation == 1){ ?>
											<input type="hidden" name="MM_insert" value="addmefrm">
											<button class="btn btn-success" type="submit">Save</button>
										<?php } else{ ?>
											<button onclick="history.go(-1)" class="btn bg-orange waves-effect" style="margin-right: 10px">
												Save and Go Back
											</button>
										<?php } ?>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Start Modal Item Edit -->
<div class="modal fade" id="outputItemModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#03A9F4">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="color:#fff" align="center" id="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="body">
                                <div class="div-result">
                                    <form class="form-horizontal" id="outputform" action="" method="POST">
                                        <br/>
                                        <div class="col-md-12">
                                            <label for="outputName" class="control-label">Output *:</label>
                                            <div class="form-input">
                                                <input type="text" name="outputName" id="outputsName" value="" class="form-control" disabled>
                                            </div>
                                        </div>  
                                        <div class="col-md-2">
                                            <label for="outputunitofmeasure" class="control-label">Units of Measure *:</label>
                                            <div class="form-input">
                                                <input type="text" name="outputunitofmeasure" value="<?= $unitofmeasure ?>" readonly id="outputunitofmeasure" placeholder="Enter Impact Target" class="form-control" required="required">
                                            </div>
                                        </div> 
                                        <div class="col-md-1">
                                            <label for="outputunitofmeasure" class="control-label">&nbsp;</label>
                                            <div class="form-input">
                                                <div class="form-control" disabled> of </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <label for="outputIndicator" class="control-label">Result to be measured (Indicator) *:</label>
                                            <div class="form-line">
                                                <input type="text" name="outputIndicator" id="outputIndicator" value="" class="form-control" disabled>
                                            </div>
                                        </div>   
                                        <div class="col-md-2">
                                            <label class="control-label">Monitoring Frequency *:</label>
                                            <div class="form-line">
                                                <!--<select name="outputMonitorigFreq" id="outputMonitorigFreq" onchange="get_reporting_timeline()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">-->
                                                <select name="outputMonitorigFreq" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                                    <option value="">.... Select from list ....</option> 
													<?php
													$query_rs_frequency =  $db->prepare("SELECT * FROM tbl_datacollectionfreq where level <= 4 AND status=1");
													$query_rs_frequency->execute(array(":level" => $level));
													$row_rs_frequency = $query_rs_frequency->fetch();
													$totalRows_rs_frequency = $query_rs_frequency->rowCount();
													$input='';
													 if ($totalRows_rs_frequency > 0) {
														do {
															$input .= '<option value="' . $row_rs_frequency['fqid'] . '">' . $row_rs_frequency['frequency'] . ' </option>';
														} while ($row_rs_frequency = $query_rs_frequency->fetch());
													} else {
														$input .= '<option value="">No Frequency Found</option>';
													}
													echo $input;
													?>
                                                </select>
                                            </div>
                                        </div>  
                                        <!--<div class="col-md-4">
                                            <label class="control-label">Reporting Frequency *:</label>
                                            <div class="form-line">
                                                <select name="outputReportingTimeline[]" multiple id="outputReportingTimeline" data-actions-box="true" class="form-control show-tick selectpicker" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                                <option value="">.... Select Monitoring Frequency First ....</option> 
                                                </select>
                                            </div>
                                        </div>-->
                                        <div class="col-md-12" >
                                            <label class="control-label">Output Location/s (Disaggregation) </label>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover" id="" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">#</th>
                                                            <th width="50%">Location </th>
                                                            <th width="45%">Responsible </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="op_table_body">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="control-label">Output Risks and Assumptions</label>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover" id="output_table" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">#</th>
                                                            <th width="30%">Output Risks Category</th>
                                                            <th width="60%">Assumption/s</th>
                                                            <th width="5%">
                                                                <button type="button" name="addplus" id="addplus" onclick="add_row_output();" class="btn btn-success btn-sm">
                                                                    <span class="glyphicon glyphicon-plus"></span>
                                                                </button>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="output_table_body">
                                                        <tr id="row0">
                                                            <td> 1 </td>
                                                            <td>
                                                                <select data-id="0" name="outputrisk[]" id="outputriskrow0" class="form-control  selected_output" required="required">
                                                                    <?php
                                                                    $query_rsRisk =  $db->prepare("SELECT * FROM tbl_projrisk_categories");
                                                                    $query_rsRisk->execute();
                                                                    $row_rsRisk = $query_rsRisk->fetch();
                                                                    $totalRows_rsRisk = $query_rsRisk->rowCount();
                                                                    $input = '<option value="">... Select from list ...</option>';
                                                                    if ($totalRows_rsRisk > 0) {
                                                                        do {
                                                                            $type = explode(',',$row_rsRisk['type']); 
                                                                            if(in_array(3, $type)) {
                                                                                $input .= '<option value="' . $row_rsRisk['rskid'] . '">' . $row_rsRisk['category'] . ' </option>';
                                                                            }
                                                                        } while ($row_rsRisk = $query_rsRisk->fetch());
                                                                    } else {
                                                                        $input .= '<option value="">No Risks Found</option>';
                                                                    }
                                                                    echo $input;
                                                                    
                                                                    ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="output_assumptions[]" id="output_assumptions0" placeholder="Enter" class="form-control"  required />
                                                            </td>
                                                            <td>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="col-md-12 text-center">
                                                <input type="hidden" name="addoutput" id="addoutput" value="addoutput">
                                                <input type="hidden" name="opid" id="opid" value="">
                                                <input type="hidden" name="output_indicator" id="output_indicator" value="">
                                                <input type="hidden" name="dpid" id="dpid" value="">
                                                <input type="hidden" name="projid" id="projid" value="<?= $projid ?>" />
                                                <input type="hidden" name="user_name" id="user_name" value="<?= 4 ?>">
                                                <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                                                <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- /modal-body -->
        </div>
        <!-- /modal-content -->
    </div>
    <!-- /modal-dailog -->
</div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
</script>