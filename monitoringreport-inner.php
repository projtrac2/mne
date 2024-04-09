<?php 
try {
	//code...

?>
   <section class="content" style="margin-top:-20px; padding-bottom:0px">
        <div class="container-fluid">
            <div class="block-header">          
				<h4><i class="fa fa-list" aria-hidden="true"></i>  Project Monitoring Report</h4>
            </div>
			<div class="row clearfix">
                <div class="table-responsive" style="color:#333; background-color:#EEE; width:98%; height:30px; padding-left:2px">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-3px">
						<tr>
							<td width="50%" style="font-size:14px; font-weight:bold"> <img src="images/monitoricon.png" alt="task" /> Detailed Report of Project Under Monitoring</td>
							<td width="50%" style="font-size:12px">
								<div align="right">
									<div class="addbutton" style="width:50px; height:25px">
										<div align="center"><a href="mympdash?projid=<?php echo $row_rsMyP['projid']; ?>">Close</a></div>
									</div>
								</div>
							</td>
						</tr>
					</table>
                </div>
				<!-- Advanced Form Example With Validation -->
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
					<?php
					if($totalRows_rsMyP > 0){
					?>
                        <div class="body">
							<div style="margin-top:5px">	
								<table width="98%" border="0" cellspacing="0" cellpadding="0">
									<?php 
									do { 
									  
										$MnForm = $row_rsPuM['formid'];

										mysql_select_db($database_ProjMonEva, $ProjMonEva);
										$query_rsMnPrg = sprintf("SELECT * FROM tbl_task_progress WHERE formid = %s", GetSQLValueString($MnForm, "text"));
										$rsMnPrg = mysql_query($query_rsMnPrg, $ProjMonEva) or die(mysql_error());
										$row_rsMnPrg = mysql_fetch_assoc($rsMnPrg);
										$totalRows_rsMnPrg = mysql_num_rows($rsMnPrg);

										mysql_select_db($database_ProjMonEva, $ProjMonEva);
										$query_rsMnOP = sprintf("SELECT * FROM tbl_monitoringoutput WHERE formid = %s", GetSQLValueString($MnForm, "text"));
										$rsMnOP = mysql_query($query_rsMnOP, $ProjMonEva) or die(mysql_error());
										$row_rsMnOP = mysql_fetch_assoc($rsMnOP);
										$totalRows_rsMnOP = mysql_num_rows($rsMnOP);
										?>
							
										<script type="text/javascript">
											function getLocation() {
													var projid = $("#projgeo").val();
													$.ajax({
														type:'POST',
														url:'myprojmarkerxml.php',
														data:'prjid='+projid
													}); 
											};
										</script>
										<tr>
											<td>
												<div class="container-fluid" style="margin-top:10px">
													<div class="row-fluid">
														<div class="span4" style="border:#CCC thin solid; border-top-right-radius:5px; border-top-left-radius:5px">
															<p align="justify" style="width:100%; background-color:#EEE; height:25px; padding-top:5px"><strong>MOnitoring Date</strong></p>
															<p align="justify"><?php echo $row_rsPuM['madate']; ?></p>
															<br />
														</div>
													</div>
												</div>
												<div class="container-fluid" style="margin-top:10px">
													<div class="row-fluid">
														<div class="span6" style="border:#CCC thin solid; border-top-right-radius:5px; border-top-left-radius:5px">
															<p align="justify" style="width:100%; background-color:#EEE; height:25px; padding-top:5px"><strong>Project Location</strong></p>
															<p align="justify" id="map">Project Location</p>
											  
															<script>
															  var customLabel = {
																Approved: {
																  label: 'A'
																},
																Unapproved: {
																  label: 'U'
																},
																Pending: {
																  label: 'P'
																},
																'In Progress': {
																  label: 'I'
																},
																Completed: {
																  label: 'C'
																},
																Cancelled: {
																  label: 'X'
																},
																'On Hold': {
																  label: 'H'
																},
																Overdue: {
																  label: 'O'
																},
																'Behind Schedule': {
																  label: 'B'
																}
															  };

															function initMap() {
															var map = new google.maps.Map(document.getElementById('map'), {
															  center: new google.maps.LatLng(-1.292066, 36.821946),
															  zoom: 12
															});
															var infoWindow = new google.maps.InfoWindow;

															  // Change this depending on the name of your PHP or XML file
															  downloadUrl('myprojgeomarkerxml.php', function(data) {
																var xml = data.responseXML;
																var markers = xml.documentElement.getElementsByTagName('marker');
																Array.prototype.forEach.call(markers, function(markerElem) {
																  var id = markerElem.getAttribute('id');
																  var name = 'Project Name: '+markerElem.getAttribute('name');
																  var cost = 'Project Cost: Ksh.'+markerElem.getAttribute('cost');
																  var status = 'Project Status: '+markerElem.getAttribute('type');
																  var type = markerElem.getAttribute('type');
																  var point = new google.maps.LatLng(
																	  parseFloat(markerElem.getAttribute('lat')),
																	  parseFloat(markerElem.getAttribute('lng')));

																  var infowincontent = document.createElement('div');
																  var strong = document.createElement('strong');
																  strong.textContent = name
																  infowincontent.appendChild(strong);
																  infowincontent.appendChild(document.createElement('br'));

																  var text = document.createElement('text');
																  text.textContent = cost
																  infowincontent.appendChild(text);
																  infowincontent.appendChild(document.createElement('br'));
																  
																  var text = document.createElement('text');
																  text.textContent = status
																  infowincontent.appendChild(text);
																  var icon = customLabel[type] || {};
																  var marker = new google.maps.Marker({
																	map: map,
																	position: point,
																	label: icon.label
																  });
																  marker.addListener('click', function() {
																	infoWindow.setContent(infowincontent);
																	infoWindow.open(map, marker);
																  });
																});
															  });
															}



															function downloadUrl(url, callback) {
																var request = window.ActiveXObject ?
																	new ActiveXObject('Microsoft.XMLHTTP') :
																	new XMLHttpRequest;

																request.onreadystatechange = function() {
																  if (request.readyState == 4) {
																	request.onreadystatechange = doNothing;
																	callback(request, request.status);
																  }
																};

																request.open('GET', url, true);
																request.send(null);
															}

															function doNothing() {}
															</script>
															<br />
														</div>
														<div class="span6" style="border:#CCC thin solid; border-top-right-radius:5px; border-top-left-radius:5px; float:right">
															<p align="justify" style="width:100%; background-color:#EEE; height:25px; padding-top:5px"><strong>Monitored Location</strong></p>
															<p align="justify" id="geopos">Location where Monitoring was done</p>

															<br />
														</div>
													</div>
												</div>
												<div class="container-fluid">
													<div class="row-fluid">
														<div class="span12" align="left" style="border:#CCC thin solid; margin-top:10px; border-top-right-radius:5px; border-top-left-radius:5px">
															<p align="justify" style="width:100%; background-color:#EEE; height:25px; padding-top:5px"><strong>Project Activities:</strong></p>
															<table width="100%" border="0" cellpadding="0" cellspacing="0">
																<tr id="colrow">
																	<td width="3%" height="35"><div align="center"><strong id="colhead">SN</strong></div></td>
																	<td width="30%"><div align="center"><strong id="colhead">Milestone</strong></div></td>
																	<td width="28%"><div align="center"><strong id="colhead">Task Name</strong></div></td>
																	<td width="12%"><div align="center"><strong id="colhead">Task Indicator</strong></div></td>
																	<td width="15%"><div align="center"><strong id="colhead">Current Task status</strong></div></td>
																	<td width="12%"><div align="center"><strong id="colhead">Monitored Progress</strong></div></td>
																</tr>
																<?php 
																
																//$num = count($rsTaskPrg);
																$num =0;
																do { 
																$num = $num+1;
																$tskid = $row_rsMnPrg["tkid"];
																
																mysql_select_db($database_ProjMonEva, $ProjMonEva);
																$query_rsTaskDet = "SELECT * FROM tbl_task WHERE tkid='$tskid'";
																$rsTaskDet = mysql_query($query_rsTaskDet, $ProjMonEva) or die(mysql_error());
																$row_rsTaskDet = mysql_fetch_assoc($rsTaskDet);
																
																$tkmsid = $row_rsTaskDet['msid'];
																$tkindid = $row_rsTaskDet['taskindicator'];
																
																mysql_select_db($database_ProjMonEva, $ProjMonEva);
																$query_tkMs = "SELECT milestone FROM tbl_milestone WHERE msid='$tkmsid' ORDER BY msid DESC LIMIT 1";
																$tkMs = mysql_query($query_tkMs, $ProjMonEva) or die(mysql_error());
																$row_tkMs = mysql_fetch_assoc($tkMs);
																
																mysql_select_db($database_ProjMonEva, $ProjMonEva);
																$query_tkInd = "SELECT indname FROM tbl_indicator WHERE indid='$tkindid'";
																$tkInd = mysql_query($query_tkInd, $ProjMonEva) or die(mysql_error());
																$row_tkInd = mysql_fetch_assoc($tkInd);
																?>
																<tr id="rowlines">
																  <td width="3%" height="35"><div align="center"><?php echo $num; ?></div></td>
																  <td width="30%"><div align="left">&nbsp;&nbsp;<?php echo $row_tkMs['milestone']; ?></div></td>
																  <td width="28%"><div align="left">&nbsp;&nbsp;<?php echo $row_rsTaskDet['task']; ?></div></td>
																  <td width="12%"><div align="left">&nbsp;&nbsp;<?php echo $row_tkInd['indname']; ?></div></td>
																  <td width="15%"><div align="left">&nbsp;&nbsp;<?php echo $row_rsTaskDet['status']; ?></div></td>
																  <td width="12%"><div align="left">&nbsp;&nbsp;<?php echo $row_rsMnPrg['progress']."%"; ?></div></td>
																</tr>
																<?php
																} while ($row_rsMnPrg = mysql_fetch_assoc($rsMnPrg)); 
																?>
															</table>
															<p align="left">&nbsp;</p>
														</div>
													</div>
												</div>    
												<div class="container-fluid">
													<div class="row-fluid">
														<div class="span12" align="left" style="border:#CCC thin solid; margin-top:10px; border-top-right-radius:5px; border-top-left-radius:5px">
															<p align="justify" style="width:100%; background-color:#EEE; height:25px; padding-top:5px"><strong>Project Output(s):</strong></p>
															<table width="100%" border="0" cellpadding="0" cellspacing="0">
																<tr id="colrow">
																	<td width="3%" height="35"><div align="center"><strong id="colhead">#</strong></div></td>
																	<td width="30%"><div align="left" style="padding-left:10px"><strong id="colhead">Output</strong></div></td>
																	<td width="22%"><div align="left" style="padding-left:10px"><strong id="colhead">Indicator</strong></div></td>
																	<td width="15%"><div align="left" style="padding-left:10px"><strong id="colhead">Target</strong></div></td>
																	<td width="15%"><div align="left" style="padding-left:10px"><strong id="colhead">Achieved</strong></div></td>
																	<td width="15%"><div align="left" style="padding-left:10px"><strong id="colhead">Variance</strong></div></td>
																</tr>
																<?php 
																
																//$num = count($rsTaskPrg);
																$num =0;
																do { 
																	$num = $num+1;
																	$optid = $row_rsMnOP["opid"];
																	
																	mysql_select_db($database_ProjMonEva, $ProjMonEva);
																	$query_rsExpOpt = "SELECT * FROM tbl_expprojoutput WHERE opid='$optid'";
																	$rsExpOpt = mysql_query($query_rsExpOpt, $ProjMonEva) or die(mysql_error());
																	$row_rsExpOpt = mysql_fetch_assoc($rsExpOpt);
																	
																	$outputid = $row_rsExpOpt['expoutputname'];
																	$indid = $row_rsExpOpt['expoutputindicator'];;
																	$variance = ($row_rsExpOpt['expoutputvalue']) - ($row_rsMnOP['actualoutput']);
																	
																	mysql_select_db($database_ProjMonEva, $ProjMonEva);
																	$query_Outp = "SELECT output FROM tbl_outputs WHERE opid='$outputid'";
																	$Outp = mysql_query($query_Outp, $ProjMonEva) or die(mysql_error());
																	$row_Outp = mysql_fetch_assoc($Outp);
																	
																	mysql_select_db($database_ProjMonEva, $ProjMonEva);
																	$query_OPInd = "SELECT indname FROM tbl_indicator WHERE indid='$indid'";
																	$OPInd = mysql_query($query_OPInd, $ProjMonEva) or die(mysql_error());
																	$row_OPInd = mysql_fetch_assoc($OPInd);
																	?>
																	<tr id="rowlines">
																		<td width="3%" height="35"><div align="center"><?php echo $num; ?></div></td>
																		<td width="30%"><div align="left">&nbsp;&nbsp;<?php echo $row_Outp['output']; ?></div></td>
																		<td width="22%"><div align="left">&nbsp;&nbsp;<?php echo $row_OPInd['indname']; ?></div></td>
																		<td width="15%"><div align="left">&nbsp;&nbsp;<?php echo $row_rsExpOpt['expoutputvalue']; ?></div></td>
																		<td width="15%"><div align="left">&nbsp;&nbsp;<?php echo $row_rsMnOP['actualoutput']; ?></div></td>
																		<td width="15%"><div align="left">&nbsp;&nbsp;<?php echo $variance; ?></div></td>
																	</tr>
																	<?php
																} while ($row_rsMnOP = mysql_fetch_assoc($rsMnOP)); 
																?>
															</table>
															<p align="left">&nbsp;</p>
														</div>
													</div>
												</div> 
												<div class="container-fluid">
													<div class="row-fluid">
														<div class="span12" align="left" style="border:#CCC thin solid; margin-top:10px; border-top-right-radius:5px; border-top-left-radius:5px">
															<p align="justify" style="width:100%; background-color:#EEE; height:25px; padding-top:5px"><strong>Observations</strong></p>
															<p align="justify"><?php echo $row_rsPuM['observations']; ?></p>
															<p align="justify">&nbsp;</p>
														</div>
													</div>
												</div>
												<div class="container-fluid">
													<div class="row-fluid">
														<div class="span12" align="left" style="border:#CCC thin solid; margin-top:10px; border-top-right-radius:5px; border-top-left-radius:5px">
															<p align="justify" style="width:100%; background-color:#EEE; height:25px; padding-top:5px"><strong>Lessons Learnt</strong></p>
															<p align="justify"><?php echo $row_rsPuM['lessons']; ?></p>
															<p align="justify">&nbsp;</p>
														</div>
													</div>
												</div>
												<div class="container-fluid">
													<div class="row-fluid">
														<div class="span12" align="left" style="border:#CCC thin solid; margin-top:10px; border-top-right-radius:5px; border-top-left-radius:5px">
															<p align="justify" style="width:100%; background-color:#EEE; height:25px; padding-top:5px"><strong>Means of Verification</strong></p>
															<?php
															if(!empty($row_rsPuM['floc'])){
															?>
															<p align="justify"><div align="left" style="padding-left:10px">Click here to download the attached monitoring file: <img src="images/files.png" alt="task" /><a href="<?php echo $row_rsPuM['floc']; ?>" style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" title="Download File" target="new">Download</a></div></p>
															<?php
															}else{
															?>
															<p align="justify"><div align="left" style="padding-left:10px">There is no attachment</div></p>
															<?php
															}
															?>
															<p align="justify">&nbsp;</p>
														</div>
													</div>
												</div>
												<p align="center">
												</p>
											<td>
										</tr>
									<?php } while ($row_rsPuM = mysql_fetch_assoc($rsPuM)); ?>
									<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANeDcXUz-GQssz7EHTzGGUHU-VPlAtMGY&callback=initMap"></script>
								</table>
							</div>
                        </div>
					<?php
					}
					else{
					?>
                        <div class="body">
							<div style="color:#333; background-color:#EEE; width:98%; height:30px; padding-top:5px; padding-left:2px">
								<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-3px">
									<tr>
										<td width="50%" style="font-size:14px; font-weight:bold" align="center">
											<br><br><br><br>
											<font color="red" size="4">Sorry, there is no Output ready for monitoring at the moment!!!!</font>
											<br><br><br><br>
										</td>
									</tr>
								</table>
							</div>
						</div>
					<?php				
					}
					?>
                    </div>
                </div>
            </div>
            <!-- #END# Advanced Form Example With Validation -->
        </div>
    </section>

<?php 
} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());

}
?>