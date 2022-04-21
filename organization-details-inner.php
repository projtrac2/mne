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
							<div style="color:#333; background-color:#EEE; width:100%; height:30px; padding-top:5px; padding-left:2px">
								<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-3px">
									<tr>
										<td width="50%" style="font-size:14px; font-weight:bold"><i class="fa fa-university" aria-hidden="true"></i> Add Financier</td>
										<td width="50%" style="font-size:11px">
											<div class="btn-group" style="float:right">
												<a href="financiers" class="btn btn-warning"  style="height:27px; ; margin-top:-1px; vertical-align:center">Back to Financiers</a>
											</div>
										</td>
									</tr>
								</table>
							</div>
                        </div>
                        <div class="body">
							<div style="margin-top:5px">
								<form id="add_financier" method="POST" name="addfinancierfrm" action="<?php echo $editFormAction; ?>" enctype="multipart/form-data" autocomplete="off">
									
									<fieldset class="scheduler-border">
										<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">DETAILS</legend>
										<div  class="col-md-12">
											<label>Organization Name *:</label>
											<div>
												<input name="company_name" type="text" class="form-control" placeholder="Enter organization name" value="<?=$setting["company_name"]?>" style="border:#CCC thin solid; border-radius: 5px" required/>
											</div>
										</div>
										<div class="col-md-12">
											<label>Address *:</label>
											<div class="form-line">
												<input type="text" class="form-control" name="postal_address" placeholder="Enter the organization address" value="<?=$setting["postal_address"]?>" style="border:#CCC thin solid; border-radius: 5px"  required>
											</div>
										</div>
										<div class="col-md-3">
											<label>City *:</label>
											<div class="form-line">
												<input type="text" class="form-control" name="city" placeholder="Enter city" value="<?=$setting["city"]?>" style="border:#CCC thin solid; border-radius: 5px"  required>
											</div>
										</div>
										<div  class="col-md-3">
											<label>Country *:</label>
											<div class="form-line">
												<select name="country" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px" required>
													<option value="" selected="selected" class="selection">...Select organization Country...</option>
													<?php
													while ($row_country = $query_country->fetch()){  
													?>
														<option value="<?php echo $row_country['id']?>"><?php echo $row_country['country']?></option>
													<?php
													}
													?>
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<label>Telephone Number *:</label>
											<div class="form-line">
												<input type="text" class="form-control" name="phone" placeholder="Enter Telephone number" value="<?=$setting["telephone_no"]?>" style="border:#CCC thin solid; border-radius: 5px"  required>
											</div>
										</div>
										<div class="col-md-3">
											<label>Mobile Number *:</label>
											<div class="form-line">
												<input type="text" class="form-control" name="mobile" placeholder="Enter mobile number" value="<?=$setting["mobile_no"]?>" style="border:#CCC thin solid; border-radius: 5px"  required>
											</div>
										</div>
										<div class="col-md-3">
											<label>Plot Number *:</label>
											<div class="form-line">
												<input type="text" class="form-control" name="plot_no" placeholder="Enter organization location plot number" value="<?=$setting["plot_no"]?>" style="border:#CCC thin solid; border-radius: 5px">
											</div>
										</div>
										<div class="col-md-3">
											<label>Email *:</label>
											<div class="form-line">
												<input type="email" class="form-control" name="email" placeholder="Enter financier email" value="<?=$setting["email_address"]?>" style="border:#CCC thin solid; border-radius: 5px"  required>
											</div>
										</div>
										<div class="col-md-3">
											<label>Domain name *:</label>
											<div class="form-line">
												<input type="text" class="form-control" name="domain_address" placeholder="Enter system domain name" value="<?=$setting["domain_address"]?>" style="border:#CCC thin solid; border-radius: 5px">
											</div>
										</div>
										<div class="col-md-3">
											<label>System host ip address *:</label>
											<div class="form-line">
												<input type="text" class="form-control" name="ip_address" placeholder="Enter system host ip address" value="<?=$setting["ip_address"]?>" style="border:#CCC thin solid; border-radius: 5px">
											</div>
										</div>
									</fieldset>
									<fieldset class="scheduler-border">
										<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Organization Logo</legend>
										<!-- File Upload | Drag & Drop OR With Click & Choose -->
										<div class="row clearfix">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="card" style="margin-bottom:-20px">
													<div class="body">
														<table class="table table-bordered" id="donation_table">
															<tr>
																<th style="width:40%">Attachments</th>
																<th style="width:58%">Attachment Purpose</th>
																<th style="width:2%"><button type="button" name="addplus" onclick="add_row();" title="Add another document" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
															</tr>
															<tr>
																<td>
																	<input type="file" name="financierattachment" multiple id="financierattachment" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required>
																</td>
																<td>
																	<input type="text" name="attachmentpurpose" id="attachmentpurpose" class="form-control"  placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required>
																</td>
																<td></td>
															</tr>
														</table>
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
											<input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
											<div class="btn-group">
												<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="Save" />
											</div>
											<input type="hidden" name="MM_insert" value="addfinancierfrm" />
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