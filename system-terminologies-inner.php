    <section class="content" style="margin-top:-20px; padding-bottom:0px">
        <div class="container-fluid">
            <div class="block-header bg-brown" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader"><i class="fa fa-columns" aria-hidden="true"></i> Taxonomy</h4>
            </div>
			<div class="row clearfix" style="margin-top:10px">
				<div class="col-md-12" style="margin-top:10px">
					<div class="card">
                            <div class="button-demo" style="margin-top:-15px; margin-left:0px; margin-right:-2px">
								<?php include_once("settings-menu.php"); ?>
							</div>
					</div>
				</div>
				<div class="block-header">
					<?php 
						echo $result;
					?>
				</div>
				<!-- Advanced Form Example With Validation -->
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
						<div class="tab-content">
							<div id="home" class="tab-pane fade in active">
								<div class="header">
									<div style="color:#333; background-color:#EEE; width:100%; height:30px">
										<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
											<tr>
												<td width="100%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF"><div align="left" ><img src="images/projbrief.png" alt="img" /> <strong>System Terminologies</strong></div></td>
											</tr>
										</table>
									</div>
								</div>
								<div class="body">
									<form id="addopfrm" method="POST" name="addopfrm" action="" autocomplete="off">
										
										<fieldset class="scheduler-border">
											<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-plus-square" aria-hidden="true"></i> Add System Global Terminologies</legend>
											<div  class="col-md-2">
												<label>Category*:</label>
												<div class="form-line">
													<select name="category" id="category" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px"  required>
														<option value="">....Select....</option>
														<?php
														if($cat == 1){
															echo '<option value="1" selected="selected">Sector</option>
															<option value="2">Administrative</option>
															<option value="3">Plan</option>';
														} elseif($cat == 2){
															echo '<option value="1">Sector</option>
															<option value="2" selected="selected">Administrative</option>
															<option value="3">Plan</option>';
														}elseif($cat == 3){
															echo '<option value="1">Sector</option>
															<option value="2">Administrative</option>
															<option value="3" selected="selected">Plan</option>';
														}else {
															echo '<option value="1">Sector</option>
															<option value="2">Administrative</option>
															<option value="3">Plan</option>';
														}
														?>
													</select>
												</div>
											</div>
											<div class="col-md-3">
												<label>Terminology Name *:</label>
												<div class="form-line">
													<input type="text" class="form-control" name="name" value="<?=$name?>" style="border:#CCC thin solid; border-radius: 5px" required>
												</div>
											</div>
											<div class="col-md-3">
												<label>Terminology Label *:</label>
												<div class="form-line">
													<input type="text" class="form-control" name="label" value="<?=$label?>" style="border:#CCC thin solid; border-radius: 5px" required>
												</div>
											</div>
											<div class="col-md-4">
												<label>Terminology Label Plural *:</label>
												<div class="form-line">
													<input type="text" class="form-control" name="label-plural" value="<?=$labelplural?>" style="border:#CCC thin solid; border-radius: 5px" required>
												</div>
											</div>
											<div class="row clearfix">
												<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
												</div>
												<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
													<input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
													<input name="deptid" type="hidden" id="deptid" value="<?php echo $opid; ?>" />
													<div class="btn-group">
														<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit"  value="<?php echo $submitValue ?>" />
													</div>
													<input type="hidden" name="<?php echo $submitAction; ?>" value="<?php echo $formName; ?>" />
													<?php if(isset($_GET["tmgy"])){ ?>
														<input type="hidden" name="tmgy" value="<?php echo $_GET["tmgy"]; ?>" />
														<a type="button" class="btn btn-warning" href="system-terminologies">Cancel</a>
													<?php } ?>
												</div>
												<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
												</div>
											</div>
										</fieldset>
									</form>
									<fieldset class="scheduler-border">
										<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><img src="images/indicator.png" alt="task" /> All Terminologies</legend>
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
												<thead>
													<tr id="colrow">
														<td width="3%"><strong id="colhead">SN</strong></td>
														<td width="10%"><strong id="colhead">Category</strong></td>
														<td width="25%"><strong id="colhead">Name</strong></td>
														<td width="25%"><strong id="colhead">Label</strong></td>
														<td width="30%"><strong id="colhead">Label Plural</strong></td>
														<td width="7%"><strong id="colhead">Action</strong></td>
													</tr>
												</thead>
												<tbody><?php
												if($rows_terminologies == 0){
													?>
													<tr>
														<td  colspan="9"><div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div></td>
													</tr>
												<?php }
												else{ 
													$sn = 0;
													while ($rows = $query_terminologies->fetch()) { 
														$sn = $sn + 1;
														$tmgyid = $rows['id'];
														$cat = $rows['category'];
														if($cat==1){
															$category = "Sector";
														}elseif($cat==2){
															$category = "Administrative";
														}elseif($cat==3){
															$category = "Plan";
														}
														
														$name = $rows['name'];
														$label = $rows['label'];
														$labelplural = $rows['label_plural'];
														?>
														<tr id="rowlines">
															<td><?php echo $sn; ?></td>
															<td><?php echo $category; ?></td>
															<td><?php echo $name; ?></td>
															<td><?php echo $label; ?></td>
															<td><?php echo $labelplural; ?></td>
															<td><a href="system-terminologies?action=edit&tmgy=<?php echo $tmgyid; ?>"><img src="images/edit.png" width="16" height="16" alt="Edit"  title="Edit Output"/></a>&nbsp;&nbsp;<a href="system-terminologies?action=del&tmgy=<?php echo $tmgyid; ?>" onclick="return confirm('Are you sure you want to delete this record?')"><img src="images/delete.png" width="16" height="16" alt="Delete"  title="Delete Output"/></a></td>
														</tr>
													<?php }
												}
												?>
												</tbody>
											</table>
										</div>
									</fieldset>
								</div> 
							</div>
						</div>
                    </div>
                </div>
            </div>
            <!-- #END# Advanced Form Example With Validation -->
        </div>
    </section>