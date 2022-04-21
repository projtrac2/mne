    <section class="content" style="margin-top:5px; padding-bottom:0px">
        <div class="container-fluid">
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
                        <div class="header">
							<div style="background-color:#000; color:#FFF; width:100%; height:45px; padding-top:3px; padding-left:2px">
								<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-3px">
									<tr>
										<h4><td width="50%" class="col-md-8" style="font-size:14px; font-weight:bold"><i class="fa fa-list-ol" aria-hidden="true"></i> Project Milestone Inspection Form</td>
										<td width="50%" class="col-md-4" style="font-size:12px; float:right">
											<a href="project-inspections-list" class="btn bg-orange waves-effect waves-light" style="height:25px">Go Back</a></h4>
										</td>
									</tr>
								</table>
							</div>
                        </div>
					</div>
				</div>
				<div class="block-header">
				<?php 
					echo $results;
				?>
				</div>
				<!-- Advanced Form Example With Validation -->
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
					<?php
					try{
						if($count_rsInsptasks > 0){
						?>
							<div class="body">
								<div style="margin-top:5px">	
									<form id="pmfrm" name="pmfrm" method="POST" action="<?php echo $editFormAction; ?>" style="width:100%" enctype="multipart/form-data" autocomplete="off">
										<p id="geoposx"></p>		
										<p id="geoposy"></p>	
										<p id="geoposz"></p>
										<script>
										var x = document.getElementById("geoposx");
										var y = document.getElementById("geoposy");
										var z = document.getElementById("geoposz");

										function getLocation() {
										  if (navigator.geolocation) {
											navigator.geolocation.getCurrentPosition(showPosition, showError);
										  } else { 
											z.innerHTML = '<input name="geoerror" type="text" id="geoerror" value="Geolocation is not supported by this browser."/>';
										  }
										}

										function showPosition(position) {
											x.innerHTML = '<input name="latitude" type="text" id="monlatitude" value="'+ position.coords.latitude +'" />';
											y.innerHTML = '<input name="longitude" type="text" id="monlongitude" value="'+ position.coords.longitude +'" />';
										}

										function showError(error) {
										  switch(error.code) {
											case error.PERMISSION_DENIED:
											  z.innerHTML = '<input name="geoerror" type="hidden" id="geoerror" value="User denied the request for Geolocation."/>';
											  break;
											case error.POSITION_UNAVAILABLE:
											  z.innerHTML = '<input name="geoerror" type="text" id="geoerror" value=""Location information is unavailable."/>';
											  break;
											case error.TIMEOUT:
											  z.innerHTML = '<input name="geoerror" type="text" id="geoerror" value=""The request to get user location timed out."/>';
											  break;
											case error.UNKNOWN_ERROR:
											  z.innerHTML = '<input name="geoerror" type="text" id="geoerror" value=""An unknown error occurred."/>';
											  break;
										  }
										}
										</script>									
										<fieldset class="scheduler-border" style="background-color:#fff">
											<legend  class="scheduler-border" style="background-color:#CDDC39; color:#FFF; border:#CCC thin dashed; border-radius:3px">Milestone Details</legend>
											<div  class="col-md-7">
												<label><font color="#4f73bc" >Project Name:</font> <?php echo $row_projdetails["projname"]; ?></label>
											</div>
											<div  class="col-md-5">
												<label><font color="#4f73bc" >Project Location:</font> <?php echo $projectlocation; ?></label>
											</div>
											<div  class="col-md-12">
												<label><font color="#4f73bc" >Project Description:</font></label>
												<div>
													<textarea name="milestonedescr" cols="60" rows="5" readonly="readonly" style="padding: 10px; background-color:#fff; font-size:14px; width:99.5%"><?php echo strip_tags($row_projdetails['projdesc']); ?></textarea>
												</div>
											</div>
											<hr>
											<div  class="col-md-6">
												<label><font color="#4f73bc" >Milestone Name:</font> <?php echo $row_rsInsptasks["milestone"]; ?></label>
											</div>
											<div  class="col-md-3">
												<label><font color="#4f73bc" >Date Started:</font> <?php echo $mlstartdate; ?></label>
											</div>
											<div  class="col-md-3">
												<label><font color="#4f73bc" >Date Completed:</font> <?php echo $mlcompletiondate; ?></label>
											</div>
										</fieldset>
										<fieldset class="scheduler-border">
											<legend  class="scheduler-border" style="background-color:#CDDC39; color:#FFF; border:#CCC thin dashed; border-radius:3px">Checklist Details</legend>
											<div class="row clearfix">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="card">
														<div class="body">
															<div class="table-responsive">
																<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
																	<thead>
																		<tr  id="colrow">
																			<th width="5%">SN</th>
																			<th width="70%">Items Descriptio</th>
																			<th width="25%"></th>
																		</tr>
																	</thead>
																	<tbody>
																	<?php 
																	$sn = 0;
																	do{
																		$sn = $sn + 1;
																		$tskid = $row_rsInsptasks['taskid'];
		
																		$query_rsChecklistTopic = $db->prepare("SELECT DISTINCT t.topic AS topic, t.id AS topicid FROM tbl_inspection_checklist_topics t INNER JOIN tbl_inspection_checklist_questions q ON t.id=q.topic INNER JOIN tbl_project_inspection_checklist c ON q.id=c.questionid WHERE c.taskid='$tskid' AND c.score=0 AND (c.status=3 OR c.status=7) ORDER BY c.ckid");
																		$query_rsChecklistTopic->execute();
																	?>	<tr class="bg-blue-grey">
																			<td><?php echo $sn; ?></td>
																			<td colspan="2">Task Name: <?php echo $row_rsInsptasks['task']; ?></td>
																		</tr>
																		<?php $nm = 0;
																			while($row_rsChecklistTopic = $query_rsChecklistTopic->fetch()){
																				$nm = $nm + 1;
																				$tpid = $row_rsChecklistTopic['topicid'];
		
																				$query_rsChecklistQuestion = $db->prepare("SELECT q.question AS question, c.ckid AS ckid FROM tbl_inspection_checklist_questions q INNER JOIN tbl_project_inspection_checklist c ON q.id=c.questionid INNER JOIN tbl_inspection_checklist_topics t ON q.topic=t.id WHERE t.id='$tpid' AND c.score=0 AND (c.status=3 OR c.status=7) ORDER BY c.ckid");
																				$query_rsChecklistQuestion->execute();
																			?>
																			<tr class="bg-grey">
																				<td><?php echo $sn.".".$nm; ?></td>
																				<td colspan="2">Topic Name: <?php echo $row_rsChecklistTopic['topic']; ?></td>
																			</tr>
																			<?php $nmb = 0;
																				while($row_rsChecklistQuestion = $query_rsChecklistQuestion->fetch()){
																					$nmb = $nmb + 1;
																					$qstid = $row_rsChecklistQuestion['ckid'];
																				?>
																				<tr>
																					<input name="inspection" type="hidden" value="1"/>
																					<input name="questionid[]" type="hidden" value="<?php echo $qstid; ?>"/>
																					<td><?php echo $sn.".".$nm.".".$nmb; ?></td>
																					<td>Question Name: <?php echo $row_rsChecklistQuestion['question']; ?></td>
																					<td><font color="#9C27B0"><b>Complied?</b></font>
																						<input name="question<?php echo $qstid; ?>" type="radio" value="1" id="<?php echo $qstid; ?>-1" class="with-gap radio-col-green" />
																						<label for="<?php echo $qstid; ?>-1">YES</label>
																						<input name="question<?php echo $qstid; ?>" type="radio" value="0" id="<?php echo $qstid; ?>-2" class="with-gap radio-col-red" onclick="javascript:CallQuestionComment(<?php echo $qstid; ?>)"/>
																						<label for="<?php echo $qstid; ?>-2">NO</label>
																					</td>
																				</tr>
																				<?php 
																				}
																			}
																		}while($row_rsInsptasks = $query_rsInsptasks->fetch());
																		?>
																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</div>
											</div>
										</fieldset>
										<fieldset class="scheduler-border">
											<legend  class="scheduler-border" style="background-color:#CDDC39; color:#FFF; border:#CCC thin dashed; border-radius:3px">Files/Documents</legend>
											<div class="row clearfix">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="card" style="margin-bottom:-20px">
														<div class="body">
															<table class="table table-bordered" id="attachments_table">
																<tr>
																	<th style="width:2%">#</th>
																	<th style="width:40%">Attachments</th>
																	<th style="width:58%">Attachment Purpose</th>
																	<th style="width:2%"><button type="button" name="addplus" onclick="add_attachment();" title="Add another document" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
																</tr>
																<tr>
																	<td>1</td>
																	<td>
																		<input type="file" name="attachment[]" id="attachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" />
																	</td>
																	<td>
																		<input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control"  placeholder="Enter the purpose of this document" required="required" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"/>
																	</td>
																	<td></td>
																</tr>
															</table>
															<script type="text/javascript">
															function add_attachment()
															{
															 $rownm=$("#attachments_table tr").length;
															 $rownm=$rownm+1;
															 $attno=$rownm-1;
															 $("#attachments_table tr:last").after('<tr id="rw'+$rownm+'"><td>'+$attno+'</td><td><input type="file" name="attachment[]" id="attachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" /></td><td><input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control"  placeholder="Enter the purpose of this document" required="required" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"/></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_attach("rw'+$rownm+'")><span class="glyphicon glyphicon-minus"></span></button></td></tr>');
															}
															function delete_attach(rownm)
															{
															 $('#'+rownm).remove();
															}
															</script>
														</div>
													</div>
												</div>
											</div>
										</fieldset>
										<div class="row clearfix">
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
											</div>
											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">											
												<input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
												<input name="milestoneid" type="hidden" id="milestoneid" value="<?php echo $msid; ?>" />
												<input name="formid" type="hidden" id="formid" value="<?php echo $pmtid; ?>" />
												<div class="btn-group">
													<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="Submit" />
												</div>
												<input type="hidden" name="MM_insert" value="inspfrm" />
											</div>
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
											</div>
										</div>
									</form>
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
												<font color="red" size="4">Sorry there is no data for inspection at the moment.</font>
												<br><br><br><br>
											</td>
										</tr>
									</table>
								</div>
							</div>
						<?php				
						}
					
					}
					catch (PDOException $ex){
						$result = flashMessage("An error occurred: " .$ex->getMessage());
						print($result);
					}
					?>
                    </div>
                </div>
            </div>
            <!-- #END# Advanced Form Example With Validation -->
        </div>
    </section>