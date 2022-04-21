    <section class="content" style="margin-top:-20px; padding-bottom:0px">
        <div class="container-fluid">
			<div class="row clearfix" style="margin-top:10px">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
					<div class="card">
						<div class="header" style="padding-bottom:15px">
                            <div class="button-demo" style="margin-top:-15px">
								<span class="label bg-black" style="font-size:18px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" />Financier Menu</span>
								<a href="manage-financier?fn=<?php echo $hash; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; padding-left:-5px">Financier Details</a>
								<a href="financier-funds?fn=<?php echo $hash; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-5px">Funds Contributed</a>
								<a href="utilized-funds?fn=<?php echo $hash; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Funds Utilized</a>
								<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px;  margin-left:-9px">Financier Status</a>
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
                        <div class="body">
							<div style="margin-top:5px">
								<form id="add_donation" method="POST" name="financierstatusfrm" action="<?php echo $editFormAction; ?>" enctype="multipart/form-data" autocomplete="off">
									
									<fieldset class="scheduler-border">
										<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">DETAILS</legend>
										<div  class="col-md-12">
											<label>Financier :</label>
											<div>
												<input type="text" class="form-control" value="<?php echo $row_financier['financier']; ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px"/>
											</div>
										</div>
										<div class="col-md-4">
											<label>Financier Status *:</label>
											<div class="form-line">
												<select name="status" id="status"  class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
													<option value="" class="selection">...Select...</option>
													<?php 
													if($statusid == 1){
														echo '<option value="1" selected="selected" class="selection">Activate</option>
														<option value="0" class="selection">Deactivate</option>';
													} else {
														echo '<option value="0" selected="selected" class="selection">Inactive</option>
														<option value="1" class="selection">Activate</option>';
													}
													?>
												</select>
											</div>
										</div>	
										<script src="http://afarkas.github.io/webshim/js-webshim/minified/polyfiller.js"></script>
										<script type="text/javascript">
										
											webshims.setOptions('forms-ext', {
												replaceUI: 'auto',
												types: 'number'
											});
											webshims.polyfill('forms forms-ext');
										</script>
										<div class="col-md-6">	
											<label>Action Date (Activated/Deactived) *:</label>
											<div class="form-line">
												<input name="statusdate" type="date" class="form-control" placeholder="Please choose a date..." style="border:#CCC thin solid; border-radius: 5px; padding-left:10px" value="<?php echo $row_financier['statusdate']; ?>" required/>
											</div>
										</div>							
										<div  class="col-md-12">
											<label>Comments *:</label>
											<div class="form-line">
												<textarea name="comments" cols="45" rows="7" class="form-control" id="comments" style="border:#CCC thin solid; border-radius: 5px" required><?php echo Strip_tags($row_financier['comments']); ?></textarea>
											</div>
										</div>
									</fieldset>
									<fieldset class="scheduler-border">
										<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Status AttachmentS</legend>
										<!-- File Upload | Drag & Drop OR With Click & Choose -->
										<div class="row clearfix">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="card" style="margin-bottom:-20px">
													<div class="header">
															<i class="ti-link"></i>MULTIPLE FILES UPLOAD - WITH CLICK & CHOOSE
													</div>
													<div class="body">
														<table class="table table-bordered" id="donor_status">
															<tr>
																<th style="width:40%">Attachments</th>
																<th style="width:58%">Attachment Purpose</th>
																<th style="width:2%"><button type="button" name="addplus" onclick="add_row();" title="Add another document" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
															</tr>
															<tr>
																<td>
																	<input type="file" name="financierstatusattachment[]" multiple id="financierstatusattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required>
																</td>
																<td>
																	<input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control"  placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required>
																</td>
																<td></td>
															</tr>
														</table>
														<script type="text/javascript">
														function add_row(){
															$rowno=$("#donor_status tr").length;
															$rowno=$rowno+1;
															$("#donor_status tr:last").after('<tr id="row'+$rowno+'"><td><input type="file" name="donorstatusattachment[]" multiple id="donorstatusattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control"  placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_row("row'+$rowno+'")><span class="glyphicon glyphicon-minus"></span></button></td></tr>');
															// <input type='text' name='funding[]' placeholder='Enter Name'></td><td><input type='button' value='DELETE' onclick=delete_row('row"+$rowno+"')></td></tr>");
														}
														function delete_row(rowno){
															$('#'+rowno).remove();
														}
														</script>
													</div>
												</div>
											</div>
										</div>
										<!-- #END# File Upload | Drag & Drop OR With Click & Choose -->
									</fieldset>
									<div class="row clearfix">
										<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
										</div>
										<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
											<input name="fnid" type="hidden" id="fnid" value="<?php echo $fnid; ?>" />
											<input name="user_name" type="hidden" id="user_name" value="<?php echo $username; ?>" />
											<div class="btn-group">
												<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="Save" />
											</div>
											<input type="hidden" name="MM_insert" value="financierstatusfrm" />
										</div>
										<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
										</div>
									</div>
								</form>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Advanced Form Example With Validation -->
        </div>
    </section>