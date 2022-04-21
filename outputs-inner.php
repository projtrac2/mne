                        <div class="body">
							<fieldset class="scheduler-border">
								<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><img src="images/indicator.png" alt="task" /> All Outputs</legend>
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
										<thead>
											<tr id="colrow">
												<td width="4%"><strong id="colhead">SN</strong></td>
												<td width="10%"><strong id="colhead">Code</strong></td>
												<td width="28%"><strong id="colhead">Name</strong></td>
												<td width="12%"><strong id="colhead">Indicator</strong></td>
												<td width="11%"><strong id="colhead">Base-Value</strong></td>
												<td width="10%"><strong id="colhead">Base-Year</strong></td>
												<td width="18%"><strong id="colhead"><?=$departmentlabel?></strong></td>
												<td width="7%"><strong id="colhead">Action</strong></td>
											</tr>
										</thead>
										<tbody><?php
										if($totalRows_rsOutputs == 0){
											?>
											<tr>
												<td  colspan="9"><div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div></td>
											</tr>
										<?php }
										else{ 
											$sn = 0;
											do { 
												$sn = $sn + 1;
												$indicatordept = $row_rsOutputs['deptid'];
												$opid = $row_rsOutputs['opid'];
												$opindicator = $row_rsOutputs['indname'];
	
												$query_opbaseline = $db->prepare("SELECT sum(basevalue) as baseline FROM tbl_output_details WHERE opid='$opid'");
												$query_opbaseline->execute();
												$row_opbaseline = $query_opbaseline->fetch();
												$opbaseline = $row_opbaseline["baseline"];
												
												$query_Dept = $db->prepare("SELECT parent, sector FROM tbl_sectors WHERE stid = '$indicatordept'");
												$query_Dept->execute();
												$row_Dept = $query_Dept->fetch();
												$parent = $row_Dept["parent"];
												
												$query_rsSect = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid = '$parent'");
												$query_rsSect->execute();
												$row_rsSect = $query_rsSect->fetch();
												
												$query_baseYear = $db->prepare("SELECT distinct(year) FROM tbl_fiscal_year y inner join tbl_output_details d on d.baseyear=y.id WHERE opid='$opid'");
												$query_baseYear->execute();
												$row_baseYear = $query_baseYear->fetch();
												$baseyear = $row_baseYear["year"];
												?>
												<tr id="rowlines">
													<td><?php echo $sn; ?></td>
													<td><?php echo $row_rsOutputs['code']; ?></td>
													<td><?php echo $row_rsOutputs['output']; ?></td>
													<td><?php echo $opindicator; ?></td>
													<td><?php echo number_format($opbaseline, 0); ?></td>
													<td><?php echo $baseyear; ?></td>
													<td><span  aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="<?php echo $ministrylabel.': '.$row_rsSect['sector']; ?>"><?php echo $row_Dept['sector']; ?></span></td>
													<td>
														<div class="btn-group">
															<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																Options <span class="caret"></span>
															</button>
															<ul class="dropdown-menu">
																<li><a type="button" data-toggle="modal" id="baselineDetailsModalBtn" data-target="#baselineDetailsModal" onclick="baselineDetails(<?= $opid?>)"> <i class="fa fa-exchange"></i> Base Info</a></li>
																<li><a type="button" data-toggle="modal" href="editoutput?opid=<?=$opid?>"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
																<li><a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="removeItem(<?=$opid?>)"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>       
															</ul>
														</div>
													</td>
												</tr>
											<?php } while ($row_rsOutputs = $query_rsOutputs->fetch());
										}
										?>
										</tbody>
									</table>
								</div>
							</fieldset>
                        </div>