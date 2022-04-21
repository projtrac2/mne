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
															<select name="op" id="output" class="form-control show-tick" data-live-search="true" id="projsubcouty" required>
																<option value="" selected="selected" >Select Output Indicator</option>
															</select>
														</div>
														<div class="col-md-4">
															<select name="obj" id="obj" class="form-control show-tick" data-live-search="true" required>
																<option value="" selected="selected" >Sub-County</option>
																<?php
																while($row = $query_obj->fetch()){  
																	$objective = $row["obj"];
																	$objectiveid = $row["objid"];
																	?>
																	<option value="<?php echo $objectiveid; ?>"><?php echo $objective; ?></option>
																<?php
																}
																?>
															</select>
														</div>
														<div class="col-md-3">
															<select name="obj" id="obj" class="form-control show-tick" data-live-search="true" required>
																<option value="" selected="selected" >Ward</option>
																<?php
																while($row = $query_obj->fetch()){  
																	$objective = $row["obj"];
																	$objectiveid = $row["objid"];
																	?>
																	<option value="<?php echo $objectiveid; ?>"><?php echo $objective; ?></option>
																<?php
																}
																?>
															</select>
														</div>
														<div class="col-md-3">
															<select name="obj" id="obj" class="form-control show-tick" data-live-search="true" required>
																<option value="" selected="selected" >Location</option>
																<?php
																while($row = $query_obj->fetch()){  
																	$objective = $row["obj"];
																	$objectiveid = $row["objid"];
																	?>
																	<option value="<?php echo $objectiveid; ?>"><?php echo $objective; ?></option>
																<?php
																}
																?>
															</select>
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
										<div class="col-md-12 table-responsive">
											<h4><u>Output Analysis</u></h4>
											<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
												<thead>
													<tr class="bg-light-blue">
														<th style="width:20%" rowspan="2">Indicator</th>
														<th style="width:20%" rowspan="2">Financial Year</th>
														<th colspan="4" align="center">Q1 </th>
														<th colspan="4" align="center">Q2 </th>
														<th colspan="4" align="center">Q3 </th>
														<th colspan="4" align="center">Q4 </th>
													</tr>
													<tr class="bg-light-blue">
														<th>Baseline</th>
														<th>Target</th>
														<th>Achieved</th>
														<th>Rate (%)</th>
														<th>Baseline</th>
														<th>Target</th>
														<th>Achieved</th>
														<th>Rate (%)</th>
														<th>Baseline</th>
														<th>Target</th>
														<th>Achieved</th>
														<th>Rate (%)</th>
														<th>Baseline</th>
														<th>Target</th>
														<th>Achieved</th>
														<th>Rate (%)</th>
													</tr>
												</thead>
												<tbody>
													<?php 
													if($totalRows_stratplan > 0){
														//$fnyear = 2002;
														for($i=0; $i<$years; $i++){
															$fnyear = $fnyear + 1;
															$fnyr = $fnyear + 1;
															$fnyear34 = $fnyear + 1;
															$qt1sdate = $fnyear."-".$q1startdate;
															$qt1edate = $fnyear."-".$q1enddate;
															$qt2sdate = $fnyear."-".$q2startdate;
															$qt2edate = $fnyear."-".$q2enddate;
															$qt3sdate = $fnyear34."-".$q3startdate;
															$qt3edate = $fnyear34."-".$q3enddate;
															$qt4sdate = $fnyear34."-".$q4startdate;
															$qt4edate = $fnyear34."-".$q4enddate;
		
															$query_projfy = $db->prepare("SELECT id FROM tbl_fiscal_year WHERE yr = '$fnyear'");
															$query_projfy->execute();
															$row_projfy = $query_projfy->fetch();
															$fnyearid = $row_projfy["id"];
		
															//OUTPUT;
															//Start Quarter One;
															/* $query_optargetq1 = $db->prepare("SELECT sum(e.expoutputvalue) AS target FROM tbl_expprojoutput e inner join tbl_projects p on p.projid=e.projid WHERE e.expoutputname = '$opid' AND p.projfscyear = '$fnyearid' AND p.projstartdate BETWEEN '$qt1sdate' AND '$qt1edate'"); */
															
															$query_optargetq1 = $db->prepare("SELECT SUM(target) as target FROM tbl_progdetails d inner join tbl_projects p on p.progid=d.progid inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.id=g.progstrategy WHERE p.projfscyear = '$fnyearid' AND year='$fnyear' AND s.objid='$objid'");
															$query_optargetq1->execute();
															$row_optargetq1 = $query_optargetq1->fetch();
															$optargetq1 = $row_optargetq1["target"] / 4;	
															
															$query_opachievedq1 = $db->prepare("SELECT sum(o.actualoutput) AS achieved FROM tbl_monitoringoutput o inner join tbl_projects p on p.projid=o.projid inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.id=g.progstrategy WHERE s.objid='$objid' AND o.date_created BETWEEN '$qt1sdate' AND '$qt1edate'");
															$query_opachievedq1->execute();
															$row_opachievedq1 = $query_opachievedq1->fetch();
															$opachievedq1 = $row_opachievedq1["achieved"];
															if($opachievedq1 == '' || empty($opachievedq1)){
																$opachievedq1 = 0;
															}else{
																$opachievedq1 = $opachievedq1;
															}
															
															$oprateq1 = round(($opachievedq1/$optargetq1)*100,1);
		
															//Start Quarter Two;
															$query_optargetq2 = $db->prepare("SELECT SUM(target) as target FROM tbl_progdetails d inner join tbl_projects p on p.progid=d.progid inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.id=g.progstrategy WHERE p.projfscyear = '$fnyearid' AND year='$fnyear' AND s.objid='$objid'");
															$query_optargetq2->execute();
															$row_optargetq2 = $query_optargetq2->fetch();
															$optargetq2 = $row_optargetq2["target"] / 4;		
															
															$query_opachievedq2 = $db->prepare("SELECT sum(o.actualoutput) AS achieved FROM tbl_monitoringoutput o inner join tbl_projects p on p.projid=o.projid inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.id=g.progstrategy WHERE s.objid='$objid' AND o.date_created BETWEEN  '$qt2sdate' AND '$qt2edate'");
															$query_opachievedq2->execute();
															$row_opachievedq2 = $query_opachievedq2->fetch();
															$opachievedq2 = $row_opachievedq2["achieved"];
															if($opachievedq2 == '' || empty($opachievedq2)){
																$opachievedq2 = 0;
															}else{
																$opachievedq2 = $opachievedq2;
															}
															
															$oprateq2 = round(($opachievedq2/$optargetq2)*100,1);
		
															//Start Quarter Three;
															$query_optargetq3 = $db->prepare("SELECT SUM(target) as target FROM tbl_progdetails d inner join tbl_projects p on p.progid=d.progid inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.id=g.progstrategy WHERE p.projfscyear = '$fnyearid' AND year='$fnyear' AND s.objid='$objid'");
															$query_optargetq3->execute();
															$row_optargetq3 = $query_optargetq3->fetch();
															$optargetq3 = $row_optargetq3["target"] / 4;
															
															$query_opachievedq3 = $db->prepare("SELECT sum(o.actualoutput) AS achieved FROM tbl_monitoringoutput o inner join tbl_projects p on p.projid=o.projid inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.id=g.progstrategy WHERE s.objid='$objid' AND o.date_created BETWEEN '$qt3sdate' AND '$qt3edate'");
															$query_opachievedq3->execute();
															$row_opachievedq3 = $query_opachievedq3->fetch();
															$opachievedq3 = $row_opachievedq3["achieved"];
															if($opachievedq3 == '' || empty($opachievedq3)){
																$opachievedq3 = 0;
															}else{
																$opachievedq3 = $opachievedq3;
															}
															
															$oprateq3 = round(($opachievedq3/$optargetq3)*100,1);
		
															//Start Quarter Four;
															$query_optargetq4 = $db->prepare("SELECT SUM(d.target) as target FROM tbl_progdetails d inner join tbl_projects p on p.progid=d.progid inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.id=g.progstrategy WHERE p.projfscyear = '$fnyearid' AND year='$fnyear' AND s.objid='$objid'");
															$query_optargetq4->execute();
															$row_optargetq4 = $query_optargetq4->fetch();
															$optargetq4 = $row_optargetq4["target"] / 4;	
															
															$query_opachievedq4 = $db->prepare("SELECT sum(o.actualoutput) AS achieved FROM tbl_monitoringoutput o inner join tbl_projects p on p.projid=o.projid inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.id=g.progstrategy WHERE s.objid='$objid' AND o.date_created BETWEEN '$qt4sdate' AND '$q4edate'");
															$query_opachievedq4->execute();
															$row_opachievedq4 = $query_opachievedq4->fetch();
															$opachievedq4 = $row_opachievedq4["achieved"];
															
															if($opachievedq4 == '' || empty($opachievedq4)){
																$opachievedq4 = 0;
															}else{
																$opachievedq4 = $opachievedq4;
															}
															
															$oprateq4 = round(($opachievedq4/$optargetq4)*100,1);
														?>
															<tr>
																<td><?php echo $fnyear."/".$fnyr?></td>
																<td class="bg-lime"><font color="#FFF"><?php echo $fnyear."/".$fnyr?></font></td>
																<td><?php echo $opbaselineq1; ?></td>
																<td><?php echo $optargetq1; ?></td>
																<td><?php echo $opachievedq1; ?></td>
																<td><?php echo $oprateq1."%"; ?></td>
																<td><?php echo $opbaselineq1; ?></td>
																<td><?php echo $optargetq2; ?></td>
																<td><?php echo $opachievedq2; ?></td>
																<td><?php echo $oprateq2."%"; ?></td>
																<td><?php echo $opbaselineq1; ?></td>
																<td><?php echo $optargetq3; ?></td>
																<td><?php echo $opachievedq3; ?></td>
																<td><?php echo $oprateq3."%"; ?></td>
																<td><?php echo $opbaselineq1; ?></td>
																<td><?php echo $optargetq4; ?></td>
																<td><?php echo $opachievedq4; ?></td>
																<td><?php echo $oprateq4."%"; ?></td>
															</tr>
														<?php
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