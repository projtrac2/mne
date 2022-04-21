		<div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader"><i class="fa fa-newspaper-o" aria-hidden="true"></i> STRATEGIC PLAN IMPLEMENTATION REPORT
				</h4>
            </div>
			<!-- Draggable Handles -->
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
                        <div class="header">
							<div class="row clearfix" style="margin-top:5px">
								<form id="searchform" align="right" name="searchform" method="get" action="">
									<div class="col-md-8"> 
										<input type="hidden" name="plan" value="<?=$stplan?>">
									</div>
									<div class="col-md-4">
										<select name="filter" id="filter" onchange="this.form.submit();"  class="form-control show-tick" data-live-search="true">
											<option value="" selected="selected" class="selection">Group By ..... </option>
											<option value="y">Group By Financial Year</option>
											<option value="s">Group By Strategic Objectives</option>
											<option value="">Group By All</option>
										</select>
									</div>
								</form>
							</div>
                        </div>
						<div class="body" style="margin-top:5px">
							<fieldset class="scheduler-border">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Report
								</legend>
								<div class="row clearfix">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
										<?php
										if(isset($_GET["plan"]) && (isset($_GET["filter"]) && $_GET["filter"]=="s")){
										?>
										<div class="col-md-12">
											<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
												<thead>
													<tr class="bg-light-blue">
														<th style="width:20%" rowspan="2">Strategic Objectives</th>
														<th colspan="6" align="center">Projects</th>
													</tr>
													<tr class="bg-light-blue">
														<th style="width:14%">Target</th>
														<th style="width:14%">Achieved</th>
														<th style="width:11%">Rate (%)</th>
														<th style="width:15%">Budget</th>
														<th style="width:15%">Expenditure</th>
														<th style="width:11%">Util.(%)</th>
													</tr>
												</thead>
												<tbody>
													<?php 
													if($totalRows_obj > 0){
														while($row_obj = $query_obj->fetch()){
															$objective = $row_obj["obj"];
															$objid = $row_obj["objid"];
		
															$query_projp = $db->prepare("SELECT projid FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.id=g.progstrategy WHERE s.objid = '$objid'");
															$query_projp->execute();
															$row_projp = $query_projp->fetch();
															$totalRows_projp = $query_projp->rowCount();	
															
															$query_projs = $db->prepare("SELECT projid FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.id=g.progstrategy WHERE s.objid = '$objid' AND p.projplanstatus=1");
															$query_projs->execute();
															$row_projs = $query_projs->fetch();
															$totalRows_projs = $query_projs->rowCount();
															$projrate = round(($totalRows_projs/$totalRows_projp)*100,1);
		
															$query_projbudget = $db->prepare("SELECT sum(p.projcost) AS pbudget FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.id=g.progstrategy WHERE s.objid = '$objid'");
															$query_projbudget->execute();
															$row_projbudget = $query_projbudget->fetch();
															$budget = $row_projbudget["pbudget"];
															$stbudget = number_format($budget, 2);
		
															$query_projcost = $db->prepare("SELECT sum(d.amountpaid) AS pcost FROM tbl_payments_disbursed d inner join tbl_payments_request r on r.id=d.reqid inner join tbl_projects p on p.projid=r.projid inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.id=g.progstrategy WHERE s.objid = '$objid'");
															$query_projcost->execute();
															$row_projcost = $query_projcost->fetch();
															$cost = $row_projcost["pcost"];
															
															if(empty($cost) || $cost==0 || $cost==0.00 || $cost==''){
																$scost = 0;
															}else{
																$scost = $cost;
															}
															$stcost = number_format($scost, 2);
															
															$mrate = round(($scost/$budget)*100,2);
														?>
															<tr>
																<td class="bg-amber"><a href="objective-performance?plan=<?=$stplan?>&obj=<?=$objid?>" style="color:#FFF"><?=$objective?></a></td>
																<td><?php echo $totalRows_projp; ?></td>
																<td><?php echo $totalRows_projs; ?></td>
																<td><?php echo $projrate."%"; ?></td>
																<td><?php echo $stbudget; ?></td>
																<td><?php echo $stcost; ?></td>
																<td><?php echo $mrate."%"; ?></td>
															</tr>
														<?php
														}
													}else{
													?>
														<tr>
															<td class="bg-amber" colspan="7" align="center"><font color="red">Sorry no record found!!</font></td>
														</tr>
													<?php
													}
													?>
												</tbody>
											</table>
										</div>
										<?php
										}elseif(isset($_GET["plan"]) && (isset($_GET["filter"]) && $_GET["filter"]=="y")){
										?>
										<div class="col-md-12">
											<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
												<thead>
													<tr class="bg-light-blue">
														<th style="width:20%" rowspan="2">Financial Year</th>
														<th colspan="6" align="center">Projects</th>
													</tr>
													<tr class="bg-light-blue">
														<th style="width:14%">Target</th>
														<th style="width:14%">Achieved</th>
														<th style="width:11%">Rate (%)</th>
														<th style="width:15%">Budget</th>
														<th style="width:15%">Expenditure</th>
														<th style="width:11%">Util.(%)</th>
													</tr>
												</thead>
												<tbody>
													<?php 
													if($totalRows_stratplan > 0){
														//$fnyear = 2002;
														for($j=0; $j<$years; $j++){
															$fnyear = $fnyear + 1;
		
															$query_projfy = $db->prepare("SELECT id FROM tbl_fiscal_year WHERE yr = '$fnyear'");
															$query_projfy->execute();
															$row_projfy = $query_projfy->fetch();
															$fnyearid = $row_projfy["id"];
		
															$query_projp = $db->prepare("SELECT projid FROM tbl_projects WHERE projfscyear = '$fnyearid'");
															$query_projp->execute();
															$row_projp = $query_projp->fetch();
															$totalRows_projp = $query_projp->rowCount();	
															
															$query_projs = $db->prepare("SELECT projid FROM tbl_projects WHERE projfscyear = '$fnyearid' AND projplanstatus=1");
															$query_projs->execute();
															$row_projs = $query_projs->fetch();
															$totalRows_projs = $query_projs->rowCount();
															$projrate = round(($totalRows_projs/$totalRows_projp)*100,1);
		
															$query_projbudget = $db->prepare("SELECT sum(projcost) AS pbudget FROM tbl_projects WHERE projfscyear = '$fnyearid'");
															$query_projbudget->execute();
															$row_projbudget = $query_projbudget->fetch();
															$budget = $row_projbudget["pbudget"];
															$stbudget = number_format($budget, 2);
		
															$query_projcost = $db->prepare("SELECT sum(d.amountpaid) AS pcost FROM tbl_payments_disbursed d inner join tbl_payments_request r on r.id=d.reqid inner join tbl_projects p on p.projid=r.projid WHERE  p.projfscyear = '$fnyearid'");
															$query_projcost->execute();
															$row_projcost = $query_projcost->fetch();
															$cost = $row_projcost["pcost"];
															
															if(empty($cost) || $cost==0 || $cost==0.00 || $cost==''){
																$scost = 0;
															}else{
																$scost = $cost;
															}
															$stcost = number_format($scost, 2);
															
															$mrate = round(($scost/$budget)*100,2);
														?>
															<tr>
																<td class="bg-lime"><a href="objective-performance?plan=<?=$stplan?>&fy=<?=$fnyearid?>"><?=$fnyear?></a></td>
																<td><?php echo $totalRows_projp; ?></td>
																<td><?php echo $totalRows_projs; ?></td>
																<td><?php echo $projrate."%"; ?></td>
																<td><?php echo $stbudget; ?></td>
																<td><?php echo $stcost; ?></td>
																<td><?php echo $mrate."%"; ?></td>
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
										<?php
										}else{
										?>
										<div class="col-md-12">
											<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
												<thead>
													<tr class="bg-light-blue">
														<th style="width:12%" rowspan="2">Financial Year</th>
														<th style="width:20%" rowspan="2">Strategic Objectives</th>
														<th colspan="6" align="center">Projects</th>
													</tr>
													<tr class="bg-light-blue">
														<th style="width:12%">Target</th>
														<th style="width:12%">Achieved</th>
														<th style="width:9%">Rate (%)</th>
														<th style="width:13%">Budget</th>
														<th style="width:13%">Expenditure</th>
														<th style="width:9%">Util.(%)</th>
													</tr>
												</thead>
												<tbody>
													<?php 			
													//$fnyear = 2002;
													for($j=0; $j<$years; $j++){
														$finyear = $finyear + 1;
	
														$query_projfy = $db->prepare("SELECT id FROM tbl_fiscal_year WHERE yr = '$finyear'");
														$query_projfy->execute();
														$row_projfy = $query_projfy->fetch();
														$fnyearid = $row_projfy["id"];
														
														$query_projobj = $db->prepare("SELECT o.id AS id, o.objective AS objective FROM tbl_strategic_plan_objectives o inner join tbl_key_results_area k on k.id=o.kraid WHERE k.spid='$stplan'");
														$query_projobj->execute();
														$totalRows_projobj = $query_projobj->rowCount();
														
														while($row_projobj = $query_projobj->fetch()){
															$objid = $row_projobj["id"];
															$objective = $row_projobj["objective"];
														
															$query_plannedprojs = $db->prepare("SELECT p.projid FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid  inner join tbl_objective_strategy s on s.id=g.progstrategy WHERE p.projfscyear = '$fnyearid' AND s.objid='$objid'");
															$query_plannedprojs->execute();
															$totalRows_plannedprojs = $query_plannedprojs->rowCount();
															
															$query_actualprojs = $db->prepare("SELECT p.projid FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid  inner join tbl_objective_strategy s on s.id=g.progstrategy WHERE p.projfscyear = '$fnyearid' AND p.projplanstatus=1 AND s.objid='$objid'");
															$query_actualprojs->execute();
															$totalRows_actualprojs = $query_actualprojs->rowCount();	
															
															$prjrate = round(($totalRows_actualprojs/$totalRows_plannedprojs)*100,1);
		
															$query_plannedbudget = $db->prepare("SELECT sum(p.projcost) AS pbudget FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.id=g.progstrategy WHERE p.projfscyear = '$fnyearid' AND  s.objid = '$objid'");
															$query_plannedbudget->execute();
															$row_plannedbudget = $query_plannedbudget->fetch();
															$projsbudget = $row_plannedbudget["pbudget"];
															$prjsbudget = number_format($projsbudget, 2);
		
															$query_prjcost = $db->prepare("SELECT sum(d.amountpaid) AS pcost FROM tbl_payments_disbursed d inner join tbl_payments_request r on r.id=d.reqid inner join tbl_projects p on p.projid=r.projid inner join tbl_programs g on g.progid=p.progid inner join tbl_objective_strategy s on s.id=g.progstrategy WHERE p.projfscyear = '$fnyearid' AND  s.objid = '$objid'");
															$query_prjcost->execute();
															$row_prjcost = $query_prjcost->fetch();
															$prjcost = $row_prjcost["pcost"];
															
															if(empty($prjcost) || $prjcost==0 || $prjcost==0.00 || $prjcost==''){
																$prjscost = 0;
															}else{
																$prjscost = $prjcost;
															}
															$pjscost = number_format($prjscost, 2);
															
															$pmrate = round(($prjscost/$projsbudget)*100, 2);
														?>
															<tr>
																<td class="bg-lime"><a href="objective-performance?plan=<?=$stplan?>&fy=<?=$fnyearid?>"><?=$finyear?></a></td>
																<td class="bg-amber"><?=$objective?></td>
																<td><?=$totalRows_plannedprojs?></td>
																<td><?=$totalRows_actualprojs?></td>
																<td><?php echo $prjrate."%"; ?></td>
																<td><?=$prjsbudget?></td>
																<td><?=$pjscost?></td>
																<td><?php echo $pmrate."%"; ?></td>
															</tr>
														<?php
														}
													}
													?>
												</tbody>
											</table>
										</div>
										<?php
										}
										?>
									</div>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
			</div>
		</div>