<?php 
try {
?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header" align="center">
                <div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="100%" height="40" style="padding-left:5px; background-color:#000; color:#FFF; font-size:16px">
                                <div align="left">
                                    <strong><i class="fa fa-money" aria-hidden="true"></i>  Output Indicator Performance Tracking Table</strong>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example " id="manageItemTable">
                        <thead>
                            <tr style="background-color:#0b548f; color:#FFF"> 
                                <th style="width:4%" align="center" rowspan="2">#</th> 
                                <th style="width:50%" rowspan="2">Indicator Name </th> 
                                <th style="width:25" rowspan="2">Measurement Unit </th>
                                <th style="width:25" rowspan="2">Disaggregated </th> 
                                <th style="width:9%" colspan="3">Indicator Performance</th>
                                <th style="width:9%" rowspan="2">Action</th> 
                            </tr>
                            <tr>
                                <th>Target</th> 
                                <th>Achieved</th>
                                <th>Rate %</th>
                            </tr>
                        </thead>
                        <tbody> 
							<?php  
							$query_rsOutput =  $db->prepare("SELECT * FROM tbl_indicator i INNER JOIN tbl_measurement_units m ON i.indicator_unit = m.id 
							WHERE i.indicator_category='Output' AND i.baseline=1");
							$query_rsOutput->execute();
							$totalRows_rsOutput = $query_rsOutput->rowCount();

							if($totalRows_rsOutput > 0){
								$countp =0;
								while($row_rsOutput = $query_rsOutput->fetch()){ 
									$countp++;
									$indid = $row_rsOutput['indid'];
									$indicator_name = $row_rsOutput['indicator_name'];
									$unit = $row_rsOutput['unit'];
									$disaggregated = $row_rsOutput['indicator_disaggregation'];
									//$disaggregated =0;
									$diss = "No";
									if($disaggregated == 1){
										$diss = "Yes";
									}

									//$query_rsTarget =  $db->prepare("SELECT SUM(year_target) as target FROM tbl_strategic_plan_op_indicator_targets WHERE op_indicator_id='$indid' AND year >= $syear AND year <= $endyear");
									
									$query_rsTarget =  $db->prepare("SELECT SUM(year_target) as target FROM tbl_strategic_plan_op_indicator_targets 
									WHERE op_indicator_id='$indid'");
									$query_rsTarget->execute();
									$row_rsTarget = $query_rsTarget->fetch();
									$totalRows_rsTarget = $query_rsTarget->rowCount();
									$target= $row_rsTarget['target'];

									// $sdate =  $syear . "-01-07";
									// $enddate = $endyear .  "-30-06"; 
									$sdate = '2020-01-07';
									$enddate = '2021-30-06';

									// m.date_created >= '$sdate' AND m.date_created <= '$enddate' AND
									$query_rsAchieved =  $db->prepare("SELECT SUM(actualoutput) as achieved FROM tbl_monitoringoutput  m
									INNER JOIN tbl_project_details d ON m.opid = d.id 
									WHERE d.indicator ='$indid'");
									$query_rsAchieved->execute();
									$row_rsAchieved = $query_rsAchieved->fetch();
									$totalRows_rsAchieved = $query_rsAchieved->rowCount();

									$achieved = $row_rsAchieved['achieved'];
									$rate ="N/A";

									if($target > 0 && $achieved >= 0){
										$rate = number_format(($achieved/ $target) * 100, 2); 
									} 
									?> 
									<tr style="">
										<td style="width:4%" align="center"><?=$countp?></td> 
										<td style="width:50%"> <?=$indicator_name?> </td> 
										<td style="width:25"> <?=$unit?> </td>
										<td style="width:25"><?=$diss?> </td>
										<td style="width:8.3"> <?=number_format($target)?> </td> 
										<td style="width:8.3"> <?=number_format($achieved)?> </td> 
										<td style="width:8.3"> <?=$rate?> </td> 
										<td style="width:9%">
											<div class="btn-group">
												<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													Options <span class="caret"></span>
												</button>
												<ul class="dropdown-menu">  
													<li>
														<a type="button" href="indicator-tracking-individual.php?indid=<?=$indid?>">
															<i class="fa fa-file-text"></i> More Info 
														</a>
													</li>
												</ul>
											</div>
										</td>
									</tr>   
									<?php  
								}
							} 
							?>
                        </tbody>
                    </table>
                    <div class="coordinates">
                        <input type="hidden" name="latitude" id="clat">
                        <input type="hidden" name="longitude" id="clng">
                    </div>
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