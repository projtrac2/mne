										<?php
										try{
											do { 
												$projsc = $row_rsUpP['projcommunity'];
												$projwd = $row_rsUpP['projlga'];
												$projloc = $row_rsUpP['projstate'];
												
												$query_projsc = $db->prepare("SELECT state FROM tbl_state WHERE id='$projsc'");
												$query_projsc->execute();	
												$row_projsc = $query_projsc->fetch();
												$subcounty = $row_projsc["state"];
												
												$query_projwd = $db->prepare("SELECT state FROM tbl_state WHERE id='$projwd'");
												$query_projwd->execute();	
												$row_projwd = $query_projwd->fetch();
												$ward = $row_projwd["state"];
												
												$query_projloc = $db->prepare("SELECT state FROM tbl_state WHERE id='$projloc'");
												$query_projloc->execute();	
												$row_projloc = $query_projloc->fetch();
												$loc = $row_projloc["state"];

												if($subcounty=="All"){
													$location = $subcounty." ".$level1labelplural."; ".$ward." ".$level2labelplural."; ".$loc." ".$level3labelplural;
												}else{
													$location = $subcounty." ".$level1label."; ".$ward." ".$level2label."; ".$loc." ".$level3label;
												}	
												 				
												$projectID =  $row_rsUpP['projid']; 	
												$query_rsMlsProg = $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid ='$projectID'");
												$query_rsMlsProg->execute();		
												$row_rsMlsProg = $query_rsMlsProg->fetch();

												$prjprogress = $row_rsMlsProg["mlprogress"]/$row_rsMlsProg["nmb"];

												$percent2 = round($prjprogress,2);
												 				
												$projectID =  $row_rsUpP['projid']; 	
												$query_rsTargetOP = $db->prepare("SELECT * FROM tbl_expprojoutput WHERE projid ='$projectID'");
												$query_rsTargetOP->execute();		
												$row_rsTargetOP = $query_rsTargetOP->fetch();
												
												if($row_rsTargetOP["expoutputvalue"] > 0):
													$targetop = $row_rsTargetOP["expoutputvalue"];
												else:
													$targetop = 0;
												Endif;
												
												$opindicator = $row_rsTargetOP["expoutputindicator"];	
												$query_rsOPindicator = $db->prepare("SELECT indname FROM tbl_indicator WHERE indid ='$opindicator'");
												$query_rsOPindicator->execute();		
												$row_rsOPindicator = $query_rsOPindicator->fetch();
												$opindname = $row_rsOPindicator["indname"];
												
												$outputid = $row_rsTargetOP["expoutputname"];	
												$query_rsOutput = $db->prepare("SELECT output FROM tbl_outputs WHERE opid ='$outputid'");
												$query_rsOutput->execute();		
												$row_rsOutput = $query_rsOutput->fetch();
												$outputname = $row_rsOutput["output"];
												
												$query_rsActualOP = $db->prepare("SELECT SUM(actualoutput) AS actual FROM tbl_monitoringoutput WHERE projid = '$projectID'");
												$query_rsActualOP->execute();		
												$row_rsActualOP = $query_rsActualOP->fetch();
													
												if($row_rsActualOP["actual"] > 0):
													$actualop = $row_rsActualOP["actual"];
												else:
													$actualop = 0;
												Endif;
												
												$query_rsUpPF = $db->prepare("SELECT SUM(amtreq) AS TotalReq, SUM(amtdis) AS TotalDis FROM tbl_funding WHERE projid = '$projectID'");
												$query_rsUpPF->execute();		
												$row_rsUpPF = $query_rsUpPF->fetch();
									
												$totaldis = $row_rsUpPF['TotalDis'];
												$projcost = $row_rsUpP['projcost'];	
												$totalreq = $row_rsUpPF['TotalReq'];
												$abrate = ($totaldis / $projcost) * 100;
												$otbal= $projcost - $totaldis;
												$myprojcost = number_format($projcost, 2);
												$mytotaldis = number_format($totaldis, 2);
												$mytotalreq = number_format($totalreq, 2);
												$outstandingbal = number_format($otbal, 2);
												$abrate = round($abrate, 2);
												$sdate = strtotime($row_rsUpP['stdate']);
												$edate = strtotime($row_rsUpP['endate']);
												$prjsdate = date("d M Y",$sdate);
												$prjedate = date("d M Y",$edate);
											
												?>
												<tr>
													<td width="3%"><?php echo $row_rsUpP['sn']; ?><br/><a href="printgproject?projid=<?php echo $row_rsUpP['projid']; ?>" target="new"><img src="images/report.png" alt="report" width="18" height="18" data-toggle="tooltip" data-placement="right" title="Click here to view detailed report"/></a></td>
													<td width="26%"><?php echo $row_rsUpP['projname']; ?></td>
													<td width="7%" align="center"><?php echo $row_rsUpP['projstatus']; ?></td>
													<td width="7%"><?php echo $percent2."%"; ?></td>
													<td width="10%"><?php echo $row_rsUpP['FORMAT(p.projcost, 2)']; ?></td>
													<td width="10%"><?php echo $mytotaldis; ?></td>
													<td width="7%"><?php echo $outputname; ?></td>
													<td width="10%"><?php echo $actualop." / ".$targetop." (".$opindname.")"; ?></td>
													<td width="10%"><?php echo $location; ?> </td>
													<td width="10%"><?php echo $prjsdate; ?> / <?php echo $prjedate; ?></td>
												</tr>
											<?php 
											} while ($row_rsUpP = $query_rsUpP->fetch()); 
										}catch (PDOException $ex){
											customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine()); 

										}
										?>