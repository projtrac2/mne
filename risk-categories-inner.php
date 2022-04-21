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
										<td width="100%" height="35" style="padding-left:5px; background-color:#000; color:#FFF" bgcolor="#000000"><div align="left" ><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Project Risk Categories</strong></div></td>
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
								<form id="addprojrisks" method="POST" name="addprojrisks" action="" enctype="multipart/form-data" autocomplete="off">
									<fieldset class="scheduler-border">
										<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-plus-square" aria-hidden="true"></i>  <?=$action?> Risk Category</legend>
										<div  class="col-md-12">
											<label>Risk Category Name *:</label>
											<div>
												<input name="riskcat" type="text" class="form-control" id="riskcat" value="<?php echo htmlentities($riskcategory); ?>" placeholder="Add write category name here" style="border:#CCC thin solid; border-radius: 5px"  required/>
											</div>
										</div>
										<div class="row clearfix">
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
											</div>
											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
												<input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
												<input type="hidden" name="<?=$actionName?>" value="<?=$action?>" />
												<?php 
												if (isset($_GET['risk'])) {
													echo '<input type="hidden" name="riskid" value="'.$riskid.'" />';
												}
												?>
												<div class="btn-group">
													<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="<?=$button?>" />
												</div>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
											</div>
										</div>
									</fieldset>
								</form>
							</div>
                        </div>
						<div class="body">
							<fieldset class="scheduler-border">
								<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> All Project Risks/Assumptions</legend>
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
										<thead>
											<tr class="bg-orange">
												<th style="width:5%">#</th>
												<th style="width:90%">Risks/Assumptions Name</th>
												<!--<th style="width:30%">Output</th>
												<th style="width:25">Department</th>-->
												<th style="width:5%">Action</th>
											</tr>
										</thead>
										<tbody>
										<?php
										if($totalrows_rsriskcategory == 0){
											?>
											<tr>
												<td  colspan="6"><div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div></td>
											</tr>
										<?php 
										}
										else{
											$nm = 0;
											while($row_rsriskcategory = $query_rsriskcategory->fetch())
											{ 
												$nm = $nm + 1;
												$riskid = $row_rsriskcategory['rskid'];
												$riskcat = $row_rsriskcategory['category'];
												
												$ckdate = strtotime($dateadded);
												$checklistdate = date("d M Y",$ckdate);
															
												?>
												<tr  style="background-color:#fff">
													<td align="center"><?php echo $nm; ?></td>
													<td><?php echo $riskcat; ?></td>
													<td>
														<a href="risk-categories?action=1&risk=<?php echo $riskid; ?>" data-toggle="tooltip" data-placement="bottom" title="Edit Risk"><img src="images/edit.png" width="16" height="16"/></a>&nbsp;&nbsp;<a href="risk-categories?action=2&risk=<?php echo $riskid; ?>" onclick="return confirm('Are you sure you want to delete this record?')" data-toggle="tooltip" data-placement="bottom" title="Delete Risk!!"><img src="images/delete.png" width="16" height="16"/></a>
													</td>
												</tr>
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