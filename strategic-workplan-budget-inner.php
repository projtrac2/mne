		<?php 
			try {
				//code...
			
		?>
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader"><i class="fa fa-newspaper-o" aria-hidden="true"></i> <?php if(isset($_GET["finyear"])){ echo $annualplanyear." Annual Workplan and Budget"; }else{ ?> IMPLEMENTATION MATRIX <?php } ?>
				</h4>
			</div>
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="header" style="padding-bottom:0px">
							<div class="button-demo" style="margin-top:-15px">
								<span class="label bg-black" style="font-size:18px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" /> Menu </span>
								<a href="strategic-plan-framework?plan=<?php echo $stplan; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:4px">Strategic Plan Details</a>
								<a href="view-kra?plan=<?php echo $stplan; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Key Results Area</a>
								<a href="view-strategic-plan-objectives?plan=<?php echo $stplan; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Strategic Objectives</a>
								<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Strategic Workplan & Budget</a>
								<a href="objective-performance?plan=<?php echo $stplan; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Strategic Plan Implementation Report</a>
							</div>
						</div>
					</div>
				</div>
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
							<div class="col-md-6">
								<label class="control-label"><strong>STRATEGIC PLAN:</strong></label>
								<div style="border:#CCC thin solid; border-radius:5px; height:40px; padding:10px; color:#3F51B5">
									<strong><?php echo $plan; ?></strong>
								</div>
							</div>
							<div class="col-md-12">
								<label class="control-label">Vision:</label>
								<div class="form-line">
									<div style="border:#CCC thin solid; border-radius:5px; height:40px; padding:10px; color:#3F51B5">
										<strong><?php echo $vision; ?></strong>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<label class="control-label">Mission:</label>
								<div class="form-line">
									<div style="border:#CCC thin solid; border-radius:5px; height:40px; padding:10px; color:#3F51B5">
										<strong><?php echo $mission; ?></strong>
									</div>
								</div>
							</div>
							<div class="col-md-12" style="margin-bottom:-5px">
								<div class="card">
									<div class="body">
										<form id="searchform" name="searchform" method="get" style="margin-top:5px" action="<?php echo $_SERVER['PHP_SELF']; ?>">
											<input type="hidden" name="plan" value="<?=$stplan?>">
											<!--<input type="hidden" name="" value="">-->
											<div class="col-md-4">
												<select name="finyear" id="finyear" class="form-control show-tick" data-live-search="true" required>
													<option value="" selected="selected" >Select Financial Year</option>
													<?php
													while ($row = $query_years->fetch()){ 
														/* $yrstartdate = $row["sdate"];
														$yrenddate = $row["edate"];
														$currdatetime = date("Y-m-d H:i:s"); */
														
														//if ($currdatetime <= $yrenddate){ 
															$yrid = $row["id"];
															//$yr = $row["yr"];
															$year = $row["year"];
															echo "<option value=".$yrid.">".$year."</option>";
														//}
														?>
													<?php
													}
													?>
												</select>
											</div>
											<!--<div class="col-md-4">
												<select name="op" id="output" class="form-control show-tick" data-live-search="true" id="projsubcouty" >
													<option value="" selected="selected" >Select Strategic Objective Output</option>
												</select>
											</div>-->
											<div class="col-md-4">
												<input type="submit" class="btn btn-primary"name="btn_search" id="btn_search" value="FILTER" />
												<input type="button" VALUE="RESET" class="btn btn-warning" onclick="location.href='strategic-workplan-budget?plan=<?=$stplan?>'" id="btnback">
											</div>
											<div class="col-md-2">
											</div>
										</form>
										<div class="col-md-2">
											<select id="dynamic_select" class="form-control show-tick" data-live-search="true" >
												<option value="" selected>Export File As</option>
											<?php if(isset($_GET["finyear"])){?>
												<option value="strategic-workplan-budget-pdf.php?stplan=<?=$stplan?>&finyearid=<?php echo $_GET["finyear"]; ?>">PDF</option>
												<option value="strategic-workplan-budget-csv.php?stplan=<?=$stplan?>&finyearid=<?php echo $_GET["finyear"]; ?>">Excel</option>
												<option value="strategic-workplan-budget-doc.php?stplan=<?=$stplan?>&finyearid=<?php echo $_GET["finyear"]; ?>">Word</option>
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
							<?php
							if($totalRows_planobjectives > 0 ){
								$no=0;
								while($rows = $query_planobjectives->fetch()){
									$spobjective = $rows["objective"];
									$spobjoutcome = $rows["outcome"];
									$spobjkpi = $rows["indname"];
									$spobjid = $rows["id"];
									
									$query_objprgs = $db->prepare("SELECT g.progid, g.progname FROM tbl_programs g inner join tbl_strategic_plan_objectives o on o.id=g.kpi WHERE o.id='$spobjid'");
									$query_objprgs->execute();
									$rows_objprgs = $query_objprgs->fetchAll();
									$totalRows_objprgs = $query_objprgs->rowCount();
									
									$no=$no+1;
									?>
									<fieldset class="scheduler-border">
										<legend class="scheduler-border" style="background-color:#03A9F4; border:#000 thin solid;; color:white"><strong>Strategic Objective <?=$no?>: </strong><?=$spobjective?>
										</legend>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bg-blue-grey" style="margin-bottom:7px">
												<strong>Outcome: </strong><?=$spobjoutcome?>
											</div>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bg-grey">
												<strong>KPI: </strong><?=$spobjkpi?>
											</div>
										<div class="row clearfix">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="col-md-12 table-responsive">
													<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
														<thead>
															<tr class="bg-light-blue">
																<th rowspan="2" style="width:3%">#</th>
																<th rowspan="2" colspan="2">Output&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
																<th rowspan="2" colspan="2">Indicator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
																<th colspan="<?php echo $years * 4; ?>" align="center">Target</th>
																<th rowspan="2" align="center">Budget (ksh)</th>
																<th rowspan="2" align="center">Duration</th>
																<th colspan="3" align="center">Project Partners</th>
																<th rowspan="2">Big 4 Agenda</th>
															</tr>
															<tr class="bg-light-blue">
															<?php
															
															$query_stratplanyr =  $db->prepare("SELECT * FROM tbl_strategicplan WHERE id='$stplan'");
															$query_stratplanyr->execute();
															$row_stratplanyr = $query_stratplanyr->fetch();
															$totalRows_stratplanyr = $query_stratplanyr->rowCount();
															
															$spyears = $row_stratplanyr["years"];
															$spfnyear2 = $row_stratplanyr["years"];
															$spfnyear = $row_stratplanyr["starting_year"];
															
															$opfinyears = [];
															for($i=1; $i <= $spyears; $i++){
																//$spfnyear = $spfnyear + 1;
																$spfnyr = $spfnyear + $i -1;
																$opfinyears[] = $spfnyr;
																$spfnyear34 = $spfnyear + $i;
																$fiscalyear = $spfnyr."/".$spfnyear34;
																?>
																<th><?=$fiscalyear?> Q1 </th>
																<th><?=$fiscalyear?> Q2 </th>
																<th><?=$fiscalyear?> Q3 </th>
																<th><?=$fiscalyear?> Q4 </th>
															<?php } ?>
																<th>Lead Implementer</th>
																<th>Implementing Partner/s</th>
																<th>Collaborative Partner/s</th>
															</tr>
														</thead>
														<tbody>
															<?php 
															if($totalRows_objprgs > 0){
																//$fnyear = 2002;
																$sr = 0;
																foreach($rows_objprgs as $row){
																	$program = $row["progname"];
																	$progid = $row["progid"];
																	$sr = $sr + 1;
																	
																	if(isset($_GET["finyear"]) && !empty($_GET["finyear"])){
																		$query_projdetails = $db->prepare("SELECT p.projid, p.projname, p.projduration, y.year, y.yr, agenda, lead_implementer, implementing_partner, collaborative_partner FROM tbl_projects p inner join tbl_fiscal_year y on y.id=p.projfscyear inner join tbl_big_four_agenda b on b.id=p.projbigfouragenda inner join tbl_myprojpartner ptn on ptn.projid=p.projid inner join tbl_project_details pd on pd.projid=p.projid WHERE p.progid = '$progid' AND p.projfscyear='$annualplanyearid' AND p.deleted = '0' group by p.projid order by projfscyear ASC");
																	}else{
																		$query_projdetails = $db->prepare("SELECT p.projid, p.projname, p.projduration, y.year, y.yr, agenda, lead_implementer, implementing_partner, collaborative_partner FROM tbl_projects p inner join tbl_fiscal_year y on y.id=p.projfscyear inner join tbl_big_four_agenda b on b.id=p.projbigfouragenda inner join tbl_myprojpartner ptn on ptn.projid=p.projid inner join tbl_project_details pd on pd.projid=p.projid WHERE p.progid = '$progid' AND p.deleted = '0' group by p.projid order by projfscyear ASC");
																	}
																	$query_projdetails->execute();
																	?>
																	<tr>
																		<td class="bg-light-green" style="width:3%"><?php echo $no.".".$sr?></td><td class="bg-light-green" colspan="<?php echo 12 + ($years * 4); ?>"><font color="black"><strong>Program Name: </strong></font><font color="#FFF"><?php echo $program;?></font></td>
																	</tr>
																	<?php
																	$nm = 0;
																	while($row_projdetails = $query_projdetails->fetch()){
																		$nm = $nm + 1;
																		$projid = $row_projdetails["projid"];
																		$projname = $row_projdetails["projname"];
																		$projduration = $row_projdetails["projduration"];
																		$projfinyear = $row_projdetails["year"];
																		$projfinyr = $row_projdetails["yr"];
																		$projagenda = $row_projdetails["agenda"];
																		$projlimplementer = $row_projdetails["lead_implementer"];
																		$projpimplementer = $row_projdetails["implementing_partner"];
																		$projcimplementer = $row_projdetails["collaborative_partner"];
																		$projcost = $row_projdetails["projcost"];
																		$projoutput = $row_projdetails["output"];
																		$projopindname = $row_projdetails["indname"];
																		$projtarget = $row_projdetails["target"];
																		$projexpopid = $row_projdetails["expid"];
																		$projstartdate = $row_projdetails["projstartdate"];
																		$projenddate = $row_projdetails["projenddate"];
																		
																		if($projlimplementer==0){
																			$query_projlimplementer = $db->prepare("SELECT company_name FROM tbl_company_settings");
																			$query_projlimplementer->execute();
																			$row_projlimplementer = $query_projlimplementer->fetch();
																			$leadimplementer = $row_projlimplementer["company_name"];
																		}else{
																			$query_projlimplementer = $db->prepare("SELECT partnername FROM tbl_partners WHERE ptnid = '$projlimplementer' AND active = '1'");
																			$query_projlimplementer->execute();
																			$row_projlimplementer = $query_projlimplementer->fetch();
																			$leadimplementer = $row_projlimplementer["partnername"];
																		}
																		
																		
																		$partnerimplementerarray = [];
																		if($projpimplementer==0){
																			$partnerimplementerarray[] = "Not Applicable";
																		}else{
																			$projpimplementers = explode(",", $projpimplementer);
																			$r=0;
																			foreach($projpimplementers as $projimplementer){
																				$r++;
																				$query_projpimplementer = $db->prepare("SELECT partnername FROM tbl_partners WHERE ptnid = '$projimplementer' AND active = '1'");
																				$query_projpimplementer->execute();
																				while($row_projpimplementer = $query_projpimplementer->fetch()){
																					$partnerimplementerarray[] = $r.'.'.$row_projpimplementer["partnername"];
																				}
																			}
																		}
																		$partnerimplementer = implode("; ",$partnerimplementerarray);
																		
																		$collimplementerarray = [];
																		if($projcimplementer==0){
																			$collimplementerarray[] = "Not Applicable";
																		}else{
																			$projcimplementers = explode(",", $projcimplementer);
																			$r=0;
																			foreach($projcimplementers as $projcimplementer){
																				$r++;
																				$query_projcimplementer = $db->prepare("SELECT partnername FROM tbl_partners WHERE ptnid = '$projcimplementer' AND active = '1'");
																				$query_projcimplementer->execute();
																				while($row_projcimplementer = $query_projcimplementer->fetch()){
																					$collimplementerarray[] = $r.'.'.$row_projcimplementer["partnername"];
																				}
																			}
																		}
																		$collimplementer = implode(",", $collimplementerarray);
																		
																		$query_opdetails = $db->prepare("SELECT budget FROM tbl_project_details WHERE projid = '$projid'");
																		$query_opdetails->execute();
																		$row_opdetails = $query_opdetails->fetch();
																		$projbudget = 0;
																		do{
																			$opdtbudget = $row_opdetails["budget"];
																			$projbudget = $projbudget + $opdtbudget;
																		}while($row_opdetails = $query_opdetails->fetch());
																		
																		
																		//$projcostq = $projcost / 4;
																		$projcostq = $projcost;
																		//$projdurationdays = dateDiffInDays($projstartdate, $projenddate); 
																		
																		$date1 = strtotime($projstartdate);  
																		$date2 = strtotime($projenddate); 
																		$diff = abs($date2 - $date1); 
																		//$years = floor($diff / (365*60*60*24)); 
																		$months = floor($diff / (30*60*60*24)); 

																		$projdurationmonths = $months.' months';
																		$quarters = $projdurationmonths / 3;
																		list($whole, $decimal) = explode('.', $quarters);
																		$wholequarter = $whole;
																		$decimalquarter = $decimal;
																		if($decimalquarter > 0){
																			$quarters = $wholequarter + 1;
																		}else{
																			$quarters = $quarters;
																		}
																		$projtargetq = round($projtarget / $quarters, 2);
																		
																	?>
																		<tr class="bg-lime">
																			<td class="bg-light-green" style="width:3%"><?php echo $no.".".$sr.".".$nm?></td>
																			<td class="bg-lime" colspan="<?php echo 2; ?>"><font color="black"><strong>Financial Year: </strong></font><font color="#000"><?php echo $projfinyear;?></font></td>
																			<td class="bg-lime" colspan="<?php echo 2 + ($years * 4); ?>"><font color="black"><strong>Project Name: </strong></font><font color="#000"><?php echo $projname;?></font></td>
																			<td><font color="#000"><?php echo number_format($projbudget, 2); ?></font></td>
																			<td><font color="#000"><?php echo number_format($projduration)." Days"; ?></font></td>
																			<td><font color="#000"><?php echo $leadimplementer; ?></font></td>
																			<td><font color="#000">
																			<?php echo $partnerimplementer;?></font></td>
																			<td><font color="#000"><?php echo $collimplementer; ?></font></td><td><font color="#000"><?php echo $projagenda; ?></font></td>
																		</tr>
																		<?php
																		$query_outputdetails = $db->prepare("SELECT d.id, d.outputid, d.indicator, output, indname, d.budget, duration, d.year, u.unit FROM tbl_project_details d inner join tbl_progdetails o on o.id=d.outputid inner join tbl_indicator i on i.indid=d.indicator inner join tbl_measurement_units u on u.id=i.unit WHERE d.projid = '$projid'");
																		$query_outputdetails->execute();
																		
																		
																		$nmb = 0;
																		while($row_outputdetails = $query_outputdetails->fetch()){
																			$nmb = $nmb + 1;
																			$output = $row_outputdetails["output"];
																			$indicator = $row_outputdetails["indname"];
																			$projdetailsid = $row_outputdetails["id"];
																			$opbudget = $row_outputdetails["budget"];
																			$opduration = $row_outputdetails["duration"];
																			$opyear = $row_outputdetails["year"];
																			$outputid = $row_outputdetails["outputid"];
																			$outputindunit = $row_outputdetails["unit"];
																			?>
																			<tr>
																				<td class="bg-light-green" style="width:3%"><?php echo $no.".".$sr.".".$nm.".".$nmb?></td>
																				<td colspan="2"><?php echo $output; ?></td>
																				<td colspan="2"><?php echo $indicator; ?></td>
																				
																				<?php
																				$spfinyear = $spfnyear2;
																				
																				//$opfinyearsarray = explode(",", $opfinyears);
																				
																				for($k=0; $k<$spfinyear; $k++){
																					$financialyear = $opfinyears[$k];
																					
																					$query_projoutputdetails = $db->prepare("SELECT * FROM tbl_project_output_details WHERE projoutputid = '$projdetailsid' and year='$financialyear' order by id ASC");
																					$query_projoutputdetails->execute();
																					$totalRows_prjopd = $query_projoutputdetails->rowCount();
																					
																					$targetarr = [];
																					while($row_prjopd = $query_projoutputdetails->fetch()){
																						$targetarr[]=$row_prjopd["target"];
																					}
																					
																					if($totalRows_prjopd ==0){
																						for($x=0; $x<4; $x++){
																							echo '<td>0</td>';
																						}
																					}else{
																						for($x=0; $x<4; $x++){
																							if(!empty($targetarr[$x])){
																								echo '<td style="color:#000"><strong>'.$targetarr[$x].' '.$outputindunit.'</strong></td>';
																							}else{
																								echo '<td>0</td>';
																							}
																						}
																					}
																				} ?>
																				
																				<td><?php echo number_format($opbudget, 2); ?></td>
																				<td><?php echo number_format($opduration)." Days"; ?></td>
																				<td></td>
																				<td></td>
																				<td></td>
																				<td></td>
																			</tr>
																		<?php
																		}
																	}
																}
															}else{
															?>
																<tr>
																	<td class="bg-lime" colspan="<?php echo 11 + ($years * 4); ?>" align="left" style="padding-left:20px"><font color="red"><b>Sorry no record found!!</b></font></td>
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
								<?php
								}
							}else{
							?>
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#03A9F4; border:#000 thin solid;; color:white"><strong>!!!!</strong><?=$spobjective?>
									</legend>
									<div class="col-md-12">
										<font color="red">Sorry no record found for the selected financial year (<?=$annualplanyear?>)!!</font>
									</div>
								</fieldset>
							<?php
							}
							?>
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