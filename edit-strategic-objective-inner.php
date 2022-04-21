<section class="content" style="margin-top:-20px; padding-bottom:0px">
		<div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader"><i class="fa fa-plus" aria-hidden="true"></i> ADD STRATEGIC OBJECTIVE DETAILS
				</h4>
            </div>
			<?php 
				echo $results;
			?>
			<!-- Draggable Handles --> 
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card" style="margin-bottom:-20px">
						<div class="body"> 
							<div class="body" id="objective_table"></div>   
							<h5> <strong>Key Result Area:</strong><u> <?php  echo $row_rsKra['kra'] ?> </u></h5>  
							<form action="" method="POST" class="form-inline" role="form" id="stratcplan">
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Add Strategic Objectives. </legend>
									<div class="col-md-12">
										<label class="control-label">Strategic Objective *:</label>
										<div class="form-line">
											<input name="kraid" type="hidden" id="kraid" value="<?php echo $row_rsKra['id']; ?>" /> 
											<input name="objective" type="text" class="form-control" style="width:100%; border:#CCC thin solid; border-radius: 5px" value="<?php echo $row_Objective['objective']; ?>" required>
										</div>
									</div>
									<div class="col-md-12">
										<label class="control-label">Strategic Objective Description : <font align="left" style="background-color:#eff2f4"> </font></label>
										<p align="left">
											<textarea name="objdesc" cols="45" rows="4" class="txtboxes" id="objdesc" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"><?php echo $objdesc; ?></textarea>
											<script>
												CKEDITOR.replace('objdesc', {
													height: 200,
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
									<div class="col-md-9">
										<label class="control-label"> Key Performance Indicator (KPI) *:</label>
										<div class="form-line">
											<select name="kpi" id="kpiindicator" class="form-control" style="border:#CCC thin solid; border-radius:5px" required>
												<option value="">.... Select  Indicator from list ....</option>
												<?php
												do {
													if($row_kpis['indid'] == $kpi){
														echo '<option value="'.$row_kpis['indid'].'" selected>'.$row_kpis['indicator_name'].'</option>';
													} else {
														?>
														<option value="<?php echo $row_kpis['indid'] ?>"><?php echo $row_kpis['indicator_name'] ?></option>
														<?php 
													}
												} while ($row_kpis =$query_kpis->fetch());
												?>
											</select> 
										</div>
									</div>
									<div class="col-md-3">
										<label class="control-label">KPI Unit of Measure : </label>
										<div class="form-line">
											<input name="kpiunit" type="text" class="form-control" value="<?php echo $unit ?>" style="width:100%; border:#CCC thin solid; border-radius: 5px" id="kpiunit" readonly>  
										</div>
									</div>
                                    <div class="col-md-12 table-responsive" id="sobjtarget">
										<label class="control-label">Strategic Objective Target/s *: </label>
                                        <table class="table table-bordered table-striped table-hover" id="objtargets" style="width:100%">
											<thead>
												<tr class="bg-grey">
												<?php	
													$input = '';											
													$dispyear  = $startyear;
													for ($i = 0; $i < $years; $i++) {
														$dispyear++;
														$targetyr = $startyear + $i;
														$input .= '<input type="hidden" name="targetyr[]" value="'.$targetyr.'" required="required" />
														<th>'.$targetyr.'/'.$dispyear.' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>';
													}
												$input .= '</tr>
											</thead>
											<tbody id="financier_table_body">
												<tr>';
													for ($j = 0; $j < $years; $j++) {
														$query_yrtarget = $db->prepare("SELECT * FROM tbl_strategic_plan_objective_targets WHERE objid = :objid and year = :styear");
														$query_yrtarget->execute(array(":objid" => $objid, ":styear" => $styear));
														$row_yrtarget = $query_yrtarget->fetch(); 
														$yrtarget =$row_yrtarget['target'];  
														$yrtargetid =$row_yrtarget['id']; 
														
														$input .= '<td>
															<input type="hidden" name="targetid[]" value="'.$yrtargetid.'" required>
															<input type="text" name="target[]" class="form-control" value="'.$yrtarget.'" style="width:100%; border:#CCC thin solid; border-radius: 5px" required>
														</td>';
														$styear++;
													} 
												$input .= '</tr>
												<tr>';
													$thresholdyr = 0;
													for ($k = 0; $k < $years; $k++) {
														$query_targthreshold = $db->prepare("SELECT * FROM tbl_strategic_objective_targets_threshold WHERE objid = :objid and year = :styear");
														$query_targthreshold->execute(array(":objid" => $objid, ":styear" => $stryear));
														
														$thresholdyr++;
														$input .= '<td align="center">
															<img src="images/system/rag_values.png" style="clear: both; display: block; margin: 0px 0px 3px 0px;">';
															while($row_targthreshold = $query_targthreshold->fetch()){
																$thresholdid =$row_targthreshold['id']; 
																$targthreshold =$row_targthreshold['threshold']; 
																$input .= '
																<input type="hidden" name="thresholdid'.$thresholdyr.'[]" value="'.$thresholdid.'" required><input data-type="RAG" type="number" name="thresholdyr'.$thresholdyr.'[]" class="form-control" value="'.$targthreshold.'" style="width:24%; margin-left:3px; border:#CCC thin solid; border-radius: 5px; float:left" required> ';
															}
														$input .= '</td>';
														$stryear++;
													} 
												$input .= '</tr>
											</tbody>';
											echo $input;
											?>
										</table>
									</div>
								</fieldset>
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Add Strategy(s).
									</legend>
									<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="card" style="margin-bottom:-20px">
												<div class="header">
													<i class="ti-link"></i>MULTIPLE STRATEGIES - WITH CLICK & ADD
												</div>
												<div class="body">
													<table class="table table-bordered" id="strategy_table">
														<tr>
															<th style="width:98%">Strategy</th>
															<th style="width:2%"><button type="button" name="addplus"
																	onclick="add_strow();" title="Add another field"
																	class="btn btn-success btn-sm"><span
																		class="glyphicon glyphicon-plus"></span></button>
															</th>
														</tr>
														<?php
														while($row_strategies = $query_strategies->fetch()){ 
															$nm++;
															$strategy =$row_strategies['strategy'];
															echo '<tr>
																<td>
																	<input type="text" name="strategic[]" id="strategic" class="form-control" value="'.$strategy.'" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																</td>
																<td></td>
															</tr>';
														}
														?>
													</table>
													<input name="editplan" type="hidden" id="editplan" value="editplan" />
													<input name="username" type="hidden" id="username" value="<?php echo $user_name; ?>" />
													<input name="objid" type="hidden" id="objid" value="<?php echo $objid; ?>" />
													<div class="list-inline" align="center" style="margin-top:20px">
														<button type="submit" name="update" class="btn btn-primary" id="">
															Update
														</button> 
													</div>
												</div>
											</div>
										</div>
									</div>
								</fieldset>
							</form>  
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>