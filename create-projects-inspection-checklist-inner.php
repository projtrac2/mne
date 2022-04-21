    <section class="content" style="margin-top:-20px; padding-bottom:0px">
        <div class="container-fluid">
			<div class="row clearfix" style="margin-top:10px">
				<div class="block-header">
					<?php 
						echo $results;
					?>
				</div>
				<!-- Advanced Form Example With Validation -->
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
							<div style="color:#333; background-color:#EEE; width:100%; height:30px">
								<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
									<tr>
										<td width="100%" height="35" style="padding-left:5px; background-color:#000; color:#FFF" bgcolor="#000000"><div align="left" ><i class="fa fa-list-alt" aria-hidden="true"></i> Create Project Inspection Checklist</strong></div></td>
									</tr>
								</table>
							</div>
                        </div>
					</div>
                </div>
				<!-- Advanced Form Example With Validation -->
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
							<div style="margin-top:5px">
								<form id="addinspectionchecklist" method="POST" name="addinspectionchecklist" action="<?php echo $editFormAction; ?>" enctype="multipart/form-data" autocomplete="off">
									<fieldset class="scheduler-border">
										<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-plus-square" aria-hidden="true"></i>  Add Checklist Information</legend>
										<div  class="col-md-12" style="padding-left:0px">
											<div  class="col-md-6">
												<label>Division *:</label>
												<div class="form-line">
													<select name="department" id="department" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px"  required>
														<option value="" selected="selected" class="selection">....Select <?=$departmentlabel?>....</option>
														<?php
														do {  
														?>
															<option value="<?php echo $rows_alldepartments['stid']?>"><?php echo $rows_alldepartments['sector']?></option>
														<?php
														} while ($rows_alldepartments = $query_alldepartments->fetch());
														?>
													</select>
												</div>
											</div>
											<div  class="col-md-6">
												<label>Output Indicator *:</label>
												<div class="form-line">
													<select name="output" id="output" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px"  required>
														<option value="" selected="selected" class="selection">....Select <?=$departmentlabel?> First....</option>
													</select>
												</div>
											</div>
										</div> 
										<div  class="col-md-12">
											<label>Checklist Name *:</label>
											<div>
												<input name="checklistname" type="text" class="form-control" id="checklistname" style="border:#CCC thin solid; border-radius: 5px"  required/>
											</div>
										</div>
										<div class="row clearfix">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="card" style="margin-bottom:-20px">
													<div class="header">
														<h4><span class="label label-success"><i class="fa fa-plus-square" aria-hidden="true"></i> Add Checklist Questions</span></h4>
													</div>
													<div class="body">
														<table class="table table-bordered" id="checklist">
															<tr>
																<th style="width:30%">Topic</th>
																<th style="width:68%">Question</th>
																<th style="width:2%"><button type="button" name="add" title="Add another question" class="btn btn-success btn-sm add"><span class="glyphicon glyphicon-plus"></span></button></th>
															</tr>
														</table>
														<div class="row clearfix">
															<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
															</div>
															<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
																<input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
																<input name="ingid" type="hidden" id="ingid" value="<?php echo $row_rsIndGrps['ingid']; ?>" />
																<input name="ingcat" type="hidden" id="ingcat" value="<?php echo $row_rsIndGrps['indcategory']; ?>" />
																<div class="btn-group">
																	<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="Submit" />
																</div>
																<input type="hidden" name="MM_insert" value="addinspectionchecklist" />
															</div>
															<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<script>
											$(document).ready(function(){
												$(document).on('click', '.add', function(){
													var html = '';
													html += '<tr>';
													html += '<td><select name="topic[]" class="form-control topic" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px"  required><option value="" selected="selected" class="selection">....Select Topic....</option><?php echo fill_unit_select_box($db); ?></select></td>';
													html += '<td><input type="text" name="question[]" class="form-control question"  placeholder="Enter the your question here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td>';
													html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove"><span class="glyphicon glyphicon-minus"></span></button></td></tr>';
													$('#checklist').append(html);
												});
												 
												$(document).on('click', '.remove', function(){
													$(this).closest('tr').remove();
												});
												 
												
											});
										</script>
									</fieldset>
								</form>
							</div>
                        </div>
						<div class="body">
							<fieldset class="scheduler-border">
								<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-list-ol" aria-hidden="true"></i> All Checklists</legend>
								<div class="table-responsive"> 
									<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
										<thead>
											<tr class="bg-orange">
												<th style="width:2%"></th>
												<th style="width:7%">#</th>
												<th style="width:30%">Checklist Name</th>
												<th style="width:25%">Output</th>
												<th style="width:23"><?=$departmentlabel?></th>
												<th style="width:10">Date Added</th>
												<th style="width:5%">Action</th>
											</tr>
										</thead>
										<tbody>
										<?php
										if($totalrows_rchecklist == 0){
											?>
											<tr>
												<td  colspan="6"><div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div></td>
											</tr>
										<?php }
										else{
											$nm = 0;
											while($row_rschecklist = $query_rchecklist->fetch())
											{ 
												$nm = $nm + 1;
												$cklid = $row_rschecklist['id'];
												$dept = $row_rschecklist['department'];
												$output = $row_rschecklist['indicator_name'];
												$checklistname = $row_rschecklist['name'];
												$dateadded = $row_rschecklist['date_created'];
												
												$ckdate = strtotime($dateadded);
												$checklistdate = date("d M Y",$ckdate);
															
												$query_dept = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid = :dept");
												$query_dept->execute(array(":dept" => $dept));
												$row_dept = $query_dept->fetch();
												$department = $row_dept["sector"];
															
												?>
												<tr data-toggle="collapse" data-target=".order<?php echo $nm; ?>" style="background-color:#eff9ca">
													<td align="center" class="mb-0">
														<button class="btn btn-link" title="Click once to expand and Click twice to Collapse!!">         
															<i class="fa fa-plus-square" style="font-size:16px"></i>
														</button>
													</td>
													<td align="center"><?php echo $nm; ?></td>
													<td><?php echo $checklistname; ?></td>
													<td><?php echo $output; ?></td>
													<td><?php echo $department; ?></td>
													<td><?php echo $checklistdate; ?></td>
													<td>
														<a href="editchecklist?cklstid=<?php echo $cklid; ?>" data-toggle="tooltip" data-placement="bottom" title="Edit Checklist Details"><img src="images/edit.png" width="16" height="16"/></a>&nbsp;&nbsp;<a href="delchecklist?level=1&cklstid=<?php echo $cklid; ?>" onclick="return confirm('Are you sure you want to delete this record?')" data-toggle="tooltip" data-placement="bottom" title="Delete Checklist!!"><img src="images/delete.png" width="16" height="16"/></a>
													</td>
												</tr>
												<?php		
												$query_cklsttopics = $db->prepare("SELECT DISTINCT topic FROM tbl_inspection_checklist_questions WHERE checklistname = :cklid");
												$query_cklsttopics->execute(array(":cklid" => $cklid));
												$totalRows_cklsttopics = $query_cklsttopics->rowCount();
												?> 
														<tr class="collapse order<?php echo $nm; ?>" style="background-color:#FF9800; color:#FFF">
															<th></th>
															<th>#</th>
															<th colspan="4">Checklist Topic</th>
															<th>Action</th>
														</tr>
													<?php
													if($totalRows_cklsttopics == 0){
														?>
														<tr class="collapse order<?php echo $nm; ?>">
															<td  colspan="7"><div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div></td>
														</tr>
													<?php }
													else{
														$num = 0;
														while($row_cklsttopics = $query_cklsttopics->fetch())
														{
															$num = $num + 1;
															$topicid = $row_cklsttopics['topic'];
																		
															$query_topic = $db->prepare("SELECT topic FROM tbl_inspection_checklist_topics WHERE id = :topicid");
															$query_topic->execute(array(":topicid" => $topicid));
															$row_topic = $query_topic->fetch();
															$topic = $row_topic["topic"];
												
															$query_cklstqst = $db->prepare("SELECT id, question FROM tbl_inspection_checklist_questions WHERE topic = :topicid ORDER BY id ASC");
															$query_cklstqst->execute(array(":topicid" => $topicid));
																		
															?>
															<tr data-toggle="collapse" data-target=".topic<?php echo $nm.$num; ?>"  class="collapse order<?php echo $nm; ?>" style="background-color:#CDDC39">
																<td align="center" class="mb-0">
																	<button class="btn btn-link" title="Click once to expand and Click twice to Collapse!!">    <i class="more-less fa fa-plus-square" style="font-size:16px"></i>
																	</button>
																</td>
																<td align="center"> <?php echo $nm.".".$num; ?></td>
																<td colspan="4"><?php echo $topic; ?></td>
																<td>
																	<a href="editchecklist?cktopid=<?php echo $topicid; ?>" data-toggle="tooltip" data-placement="bottom" title="Edit Checklist Details"><img src="images/edit.png" width="16" height="16"/></a>&nbsp;&nbsp;<a href="delchecklist?level=2&cktopid=<?php echo $topicid; ?>" onclick="return confirm('Are you sure you want to delete this record?')" data-toggle="tooltip" data-placement="bottom" title="Delete Checklist!!"><img src="images/delete.png" width="16" height="16"/></a>
																</td>
															</tr>
															<tr class="collapse topic<?php echo $nm.$num; ?>" style="background-color:#b8f9cb; color:#FFF">
																<th></th>
																<th>#</th>
																<th COLSPAN=4>Checklist Question</th>
																<th>Action</th>
															</tr>
															<?php
															$nmb = 0;
															while($row_cklstqst = $query_cklstqst->fetch())
															{
																$nmb = $nmb + 1;
																$qstid = $row_cklstqst['id'];
																$question = $row_cklstqst["question"];
																			
																?>
																<tr class="collapse topic<?php echo $nm.$num; ?>" style="background-color:#FFF">
																	<td style="background-color:#b8f9cb"></td>
																	<td align="center"><?php echo $nm.".".$num.".".$nmb;?></td>
																	<td COLSPAN=4><?php echo $question;?></td>
																	<td>
																		<a href="editchecklist?qstid=<?php echo $qstid; ?>" data-toggle="tooltip" data-placement="bottom" title="Edit Checklist Details"><img src="images/edit.png" width="16" height="16"/></a>&nbsp;&nbsp;<a href="delchecklist?level=3&qstid=<?php echo $qstid; ?>" onclick="return confirm('Are you sure you want to delete this record?')" data-toggle="tooltip" data-placement="bottom" title="Delete Checklist!!"><img src="images/delete.png" width="16" height="16"/></a>
																	</td>
																</tr>
															<?php
															}
														}	
													}		
													?>
												
											<?php
											}
										}
										?>
										</tbody>
										<script type="text/javascript" >
										/*******************************
										* ACCORDION WITH TOGGLE ICONS
										*******************************/
											function toggleIcon(e) {
												$(e.target)
													.find(".more-less")
													.toggleClass('fa fa-plus-square fa fa-minus-square');
											}
											$('.mb-0').on('hidden.bs.collapse', toggleIcon);
											$('.mb-0').on('shown.bs.collapse', toggleIcon);
										</script>
									</table>
								</div>
							</fieldset>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Advanced Form Example With Validation -->
        </div>
    </section>