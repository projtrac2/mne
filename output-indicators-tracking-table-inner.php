	<?php
	try{
	?>
		<div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader"><i class="fa fa-newspaper-o" aria-hidden="true"></i> OUTPUT INDICATORS PERFORMANCE TRACKING TABLE
				</h4>
            </div>
			<!-- Draggable Handles -->
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
                        <div class="header">
							<div class="row clearfix" style="margin-top:5px">
								<div class="col-md-12"> 
									<div class="btn-group pull-right">
										<input type="button" VALUE="Go Back" class="btn btn-warning" onclick="location.href='spimplementationreport?plan=<?=$stplan?>'" id="btnback">
									</div>
								</div>
							</div>
                        </div>
						<div class="body" style="margin-top:5px">
							<fieldset class="scheduler-border">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Report
								</legend>
								<div class="row clearfix">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="col-md-12" style="margin-bottom:-5px">
											<div class="card">
												<div class="body">
													<form id="searchform" name="searchform" method="get" style="margin-top:5px" action="<?php echo $_SERVER['PHP_SELF']; ?>">
														<input type="hidden" name="plan" value="<?=$stplan?>">
														<!--<input type="hidden" name="" value="">-->
														<div class="col-md-12">
															<select name="indicator" id="indicator" class="form-control show-tick" data-live-search="true" id="projsubcouty">
																<option value="" selected="selected" >Select Output Indicator</option>
																<?php
																while($row = $query_op->fetch()){  
																	$indicator = $row["indicator_name"];
																	$indid = $row["indid"];
																	?>
																	<option value="<?php echo $indid; ?>"><?php echo $indicator; ?></option>
																<?php
																}
																?>
															</select>
														</div>
														<div class="col-md-4">
															<label class="control-label"><?= $level1label ?>*:</label>
															<div class="form-line">
																<input type="hidden" name="level1label" id="level1label" value="" />
																<select name="projcommunity" id="projcommunity" onchange="conservancy()" data-actions-box="true" class="form-control show-tick selectpicker" title="Choose Multipe" style="border:#CCC thin solid; border-radius:5px; width:98%; padding-left:50px">
																	<?php
																	$data = '';
																	$id = [];
																	do {
																		$comm = $row_rsComm['id'];
																		$query_ward = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:comm AND active=1");
																		$query_ward->execute(array(":comm" => $comm));
																		while ($row = $query_ward->fetch()) {
																			$projlga = $row['id'];
																			$query_rsLocations = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:id");
																			$query_rsLocations->execute(array(":id" => $projlga));
																			$row_rsLocations = $query_rsLocations->fetch();
																			$total_locations = $query_rsLocations->rowCount();
																			if ($total_locations > 0) {
																				if (!in_array($comm, $id)) {
																					$data .= '<option value="' . $row_rsComm['id'] . '">' . $row_rsComm['state'] . '</option>';
																				}
																				$id[] = $row_rsComm['id'];
																			}
																		}
																	} while ($row_rsComm = $query_rsComm->fetch());
																	echo $data;
																	?>
																</select>
															</div>
														</div>
														<div class="col-md-3">
															<label class="control-label"><?= $level2label ?>*:</label>
															<div class="form-line">
																<input type="hidden" name="level2label" id="level2label" value="" />
																<select name="projlga" id="projlga" onchange="ecosystem()" class="form-control show-tick selectpicker" data-actions-box="true" title="Choose Multipe" style="border:#CCC thin solid; border-radius:5px; width:98%; padding-right:0px">
																	<option value="" style="padding-right:0px">.... Select <?= $level1label ?> First ....</option>
																</select>
															</div>
														</div>
														<div class="col-md-3">
															<label class="control-label"><?= $level3label ?>*:</label>
															<div class="form-line">
																<input type="hidden" name="level2label" id="level2label" value="" />
																<input type="hidden" name="level3label" id="level3label" value="" />
																<select name="projstate" class="form-control show-tick selectpicker" onchange="forest()" data-actions-box="true" title="Choose Multipe" id="projstate" style="border:#CCC thin solid; border-radius:5px; width:98%; padding-right:0px">
																	<option value="" style="padding-right:0px">.... Select <?= $level2label ?> First ....</option>
																</select>
															</div>
														</div>
														<div class="col-md-2">
															<input type="submit" class="btn btn-primary"name="btn_search" id="btn_search" value="FILTER" />
															<input type="button" VALUE="RESET" class="btn btn-warning" onclick="location.href='objective-performance?plan=<?=$stplan?>'" id="btnback">
														</div>
													</form>
													<div class="col-md-2">
														<select id="dynamic_select" class="form-control show-tick" data-live-search="true" >
															<option value="" selected>Export File As</option>
														<?php if(isset($_GET["obj"]) && isset($_GET["op"])){?>
															<option value="objective-performance-pdf.php?stplan=<?=$stplan?>&opid=<?=$opid?>&objid=<?=$objid?>">PDF</option>
															<option value="objective-performance-csv.php?stplan=<?=$stplan?>&opid=<?=$opid?>&objid=<?=$objid?>">Excel</option>
															<option value="objective-performance-doc.php?stplan=<?=$stplan?>&opid=<?=$opid?>&objid=<?=$objid?>">Word</option>
														<?php }else{?>
															<option value="#">Filter Your Output First</option>
														<?php } ?>
														</select>
													</div>
													<script>
														$(function(){
															$('#dynamic_select').on('change', function () {
																var url = $(this).val(); 
																if (url) {
																	window.location = url;
																}
																return false;
															});
														});
													</script>
												</div>
											</div>
										</div>
										<?php if(isset($_GET["obj"]) && isset($_GET["op"])){?>
											<div class="col-md-5" style="float:left">
												<div class="form-line">
													<strong>Strategic Objective: </strong>
													<div style="border:#CCC thin solid; border-radius:5px; height:40px; padding:10px; color:#3F51B5">
														<?=$objectiv?>
													</div>
												</div>
											</div>
											<div class="col-md-3 pull-left">
												<div class="form-line">
													<strong>Output: </strong>
													<div style="border:#CCC thin solid; border-radius:5px; height:40px; padding:10px; color:#3F51B5">
														<?=$specoutput?>
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-line">
													<strong>Indicator: </strong>
													<div style="border:#CCC thin solid; border-radius:5px; height:40px; padding:10px; color:#3F51B5">
														<?=$indicator?>
													</div>
												</div>
											</div>
										<?php } ?>
										<div class="col-md-12 table-responsive clearfix">
											<h4><u>Output Analysis</u></h4>
											<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
												<thead>
													<tr class="bg-light-blue">
														<th style="width:2%" rowspan="2">#</th>
														<th rowspan="2" colspan="4">Indicator</th>
														<th rowspan="2" colspan="4">Unit of Measure</th>
														<?php
														for($i=0; $i<$years; $i++){
															$fnyear = $fnyear + 1;
															$fnyr = $fnyear + 1;
															echo '<th colspan="4" align="center">'.$fnyear."/".$fnyr.'</th>';
														}
														?>
														<th style="width:9%" rowspan="2">Action</th> 
													</tr>
													<tr class="bg-light-blue">
														<?php
														for($j=0; $j<$years; $j++){
															echo '
															<th>Baseline</th>
															<th>Target</th>
															<th>Achieved</th>
															<th>Rate (%)</th>';
														}
														?>
													</tr>
												</thead>
												<tbody>
													<?php 
													if($totalRows_stratplan > 0){
														$cnt = 0;
														$baseline = 1000;
														while($row_ind = $query_ind->fetch()){
															$cnt++;
															$opindicator = $row_ind["indicator_name"];
															$opindid = $row_ind["indid"];
															$unit = $row_ind["unit"];
															$fnyear = $row_stratplan["starting_year"];
															$baseline = $baseline + 500;;
															echo '
															<tr>
																<td>'.$cnt.'</td>
																<td colspan="4" class="bg-lime">'.$opindicator.'</td>
																<td colspan="4">'.$unit.'</td>';
																for($k=0; $k<$years; $k++){
																	$fnyear = $fnyear + 1;
																	$fnyear34 = $fnyear + 1;
																	$yrsdate = $fnyear."-".$q1startdate;
																	$yredate = $fnyear34."-".$q4enddate;
				
																	$query_projfy = $db->prepare("SELECT id FROM tbl_fiscal_year WHERE yr = '$fnyear'");
																	$query_projfy->execute();
																	$row_projfy = $query_projfy->fetch();
																	$fnyearid = $row_projfy["id"];
				
																	//OUTPUT;
																	//Start Quarter One;
																	/* $query_optargetq1 = $db->prepare("SELECT sum(e.expoutputvalue) AS target FROM tbl_expprojoutput e inner join tbl_projects p on p.projid=e.projid WHERE e.expoutputname = '$opid' AND p.projfscyear = '$fnyearid' AND p.projstartdate BETWEEN '$yrsdate' AND '$yredate'"); 
																	$query_optargetq1 = $db->prepare("SELECT SUM(target) as target FROM tbl_progdetails d inner join tbl_programs g on g.progid=d.progid inner join tbl_objective_strategy s on s.objid=g.strategic_obj WHERE year='$fnyear' AND s.objid='$objid'");
																	$query_optargetq1->execute();
																	$row_optargetq1 = $query_optargetq1->fetch();
																	$yearlytarget = $row_optargetq1["target"];	
																	
																	$query_opachievedq1 = $db->prepare("SELECT sum(o.actualoutput) AS achieved FROM tbl_monitoringoutput o inner join tbl_projects p on p.projid=o.projid inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.objid=g.strategic_obj WHERE s.objid='$objid' AND o.date_created BETWEEN '$yrsdate' AND '$yredate'");*/
																	
																	$query_optargetq1 = $db->prepare("SELECT SUM(target) as target FROM tbl_progdetails d inner join tbl_programs g on g.progid=d.progid inner join tbl_objective_strategy s on s.objid=g.strategic_obj WHERE year='$fnyear' and indicator='$opindid'");
																	$query_optargetq1->execute();
																	$row_optargetq1 = $query_optargetq1->fetch();
																	$yearlytarget = $row_optargetq1["target"];	
																	
																	$query_opachievedq1 = $db->prepare("SELECT sum(o.actualoutput) AS achieved FROM tbl_monitoringoutput o inner join tbl_projects p on p.projid=o.projid inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.objid=g.strategic_obj WHERE opid=3 AND o.date_created BETWEEN '$yrsdate' AND '$yredate'");
																	$query_opachievedq1->execute();
																	$row_opachievedq1 = $query_opachievedq1->fetch();
																	$opachieved = $row_opachievedq1["achieved"];
																	
																	if($_GET["btn_search"] && !empty($_GET["projstate"])){
																		$lv3 = $_GET["projstate"];
																		echo $lv3;
																		/*$query_opachievedq1 = $db->prepare("SELECT sum(o.actualoutput) AS achieved FROM tbl_monitoringoutput o inner join tbl_projects p on p.projid=o.projid inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.objid=g.strategic_obj WHERE s.objid='$objid' AND o.date_created BETWEEN '$yrsdate' AND '$yredate'");*/
																		
																		$query_optargetq1 = $db->prepare("SELECT SUM(target) as target FROM tbl_progdetails d inner join tbl_programs g on g.progid=d.progid inner join tbl_objective_strategy s on s.objid=g.strategic_obj WHERE year='$fnyear' and indicator='$opindid'");
																		$query_optargetq1->execute();
																		$row_optargetq1 = $query_optargetq1->fetch();
																		$yearlytarget = $row_optargetq1["target"];	
																		
																		$query_opachievedq1 = $db->prepare("SELECT sum(o.actualoutput) AS achieved FROM tbl_monitoringoutput o inner join tbl_projects p on p.projid=o.projid inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.objid=g.strategic_obj WHERE opid=3 AND level3='$lv3' AND o.date_created BETWEEN '$yrsdate' AND '$yredate'");
																		$query_opachievedq1->execute();
																		$row_opachievedq1 = $query_opachievedq1->fetch();
																		$opachieved = $row_opachievedq1["achieved"];
																	}else{
																		/*$query_opachievedq1 = $db->prepare("SELECT sum(o.actualoutput) AS achieved FROM tbl_monitoringoutput o inner join tbl_projects p on p.projid=o.projid inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.objid=g.strategic_obj WHERE s.objid='$objid' AND o.date_created BETWEEN '$yrsdate' AND '$yredate'");*/
																		
																		$query_optargetq1 = $db->prepare("SELECT SUM(target) as target FROM tbl_progdetails d inner join tbl_programs g on g.progid=d.progid inner join tbl_objective_strategy s on s.objid=g.strategic_obj WHERE year='$fnyear' and indicator='$opindid'");
																		$query_optargetq1->execute();
																		$row_optargetq1 = $query_optargetq1->fetch();
																		$yearlytarget = $row_optargetq1["target"];	
																		
																		$query_opachievedq1 = $db->prepare("SELECT sum(o.actualoutput) AS achieved FROM tbl_monitoringoutput o inner join tbl_projects p on p.projid=o.projid inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.objid=g.strategic_obj WHERE opid=3 AND o.date_created BETWEEN '$yrsdate' AND '$yredate'");
																		$query_opachievedq1->execute();
																		$row_opachievedq1 = $query_opachievedq1->fetch();
																		$opachieved = $row_opachievedq1["achieved"];
																	}
															
																	 
																	$oprate = round(($opachieved/$yearlytarget)*100,1)."%";
																	
																	if(IS_NULL($yearlytarget) || $opachieved == '' || empty($opachieved)){
																		$yearlytarget = 0;
																		$oprate = 0 . "%";
																		$opachieved = 0;
																	}
																	
																	$baseline = $baseline;
																	
																	?>
																	<td><?php echo $baseline; ?></td>
																	<td><?php echo $yearlytarget; ?></td>
																	<td><?php echo $opachieved; ?></td>
																	<td><?php echo $oprate; ?></td>
																<?php
																	if($k > 0){
																		$baseline = $baseline + $opachieved;
																	}
																}
															echo '
															<td style="width:9%">
																<div class="btn-group">
																	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																		Options <span class="caret"></span>
																	</button>
																	<ul class="dropdown-menu">  
																		<li>
																			<a type="button" href="indicator-tracking-individual.php?indid='.$opindid.'">
																				<i class="fa fa-file-text"></i> More Info 
																			</a>
																		</li>
																	</ul>
																</div>
															</td>
														</tr>';
														}
													}else{
														?>
														<tr>
															<td class="bg-lime" colspan="7" align="center"><font color="red">Sorry no record found!!</font></td>
														</tr>
														<?php
													}
													?>
												</tbody>
											</table>
										</div>									
									</div>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}catch (PDOException $ex){
		$result = flashMessage("An error occurred: " .$ex->getMessage());
		echo $result;
	}
	?>

<!-- End Item Edit -->
<script src="assets/custom js/add-project.js"></script>