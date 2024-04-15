<?php 
try {
	//code...

?>
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
									<td width="100%" height="35" style="padding-left:5px; background-color:#000; color:#FFF" bgcolor="#000000"><div align="left" ><i class="fa fa-list-alt" aria-hidden="true"></i> Create Project Risk Mitigation Measures</strong></div></td>
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
									<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-plus-square" aria-hidden="true"></i>  Add Mitigation Measures</legend>
									<div  class="col-md-12" style="padding-left:0px">
										<div  class="col-md-6">
											<label>Risk Category *:</label>
											<div class="form-line">
												<select name="category" id="category" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px"  required>
													<option value="" selected="selected" class="selection">.... Select Category ....</option>
													<?php
													do {  
													?>
														<option value="<?php echo $rows_allcategories['rskid']?>"><?php echo $rows_allcategories['category']?></option>
													<?php
													} while ($rows_allcategories = $query_allcategories->fetch());
													?>
												</select>
											</div>
										</div>
									</div>
									<div class="row clearfix">
										<div class="col-md-12">
											<div class="body table-responsive">
												<table class="table table-bordered" id="meetings_table" style="width:100%">
													<tr>
														<th style="width:98%">Mitigation Measure</th>
														<th style="width:2%"><button type="button" name="addplus1" onclick="add_row1();" title="Add another document" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
													</tr>
													<tr>
														<td>
															<input type="text" name="mitigation[]" id="mitigation[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
														</td>
														<td></td>
													</tr>
												</table>
												<script type="text/javascript">
													function add_row1(){
														$rowno=$("#meetings_table tr").length;
														$rowno=$rowno+1;
														$("#meetings_table tr:last").after('<tr id="mtng'+$rowno+'"><td>'+
														'<input type="text" name="mitigation[]" id="mitigation[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_row1("mtng'+$rowno+'")><span class="glyphicon glyphicon-minus"></span></button></td></tr>');
													}
													function delete_row1(rowno){
														$('#'+rowno).remove();
													}
												</script>
											</div>
										</div>	
									</div>
									<div class="row clearfix">
										<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
										</div>
										<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
											<input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
											
											<div class="btn-group">
												<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="Submit" />
											</div>
											<input type="hidden" name="MM_insert" value="addmitigation" />
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
							<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-list-ol" aria-hidden="true"></i> All Mitigation Measures</legend>
							<div class="table-responsive"> 
								<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
									<thead>
										<tr class="bg-orange">
											<th style="width:2%"></th>
											<th style="width:5%">#</th>
											<th style="width:88%">Risk Category Name</th>
											<th style="width:5%">Action</th>
										</tr>
									</thead>
									<tbody>
									<?php
									if($totalrows_allcats == 0){
										?>
										<tr>
											<td  colspan="4"><div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div></td>
										</tr>
									<?php }
									else{
										$nm = 0;
										while($row_allcats = $query_allcats->fetch())
										{ 
											$nm = $nm + 1;
											$catid = $row_allcats['rskid'];
											$checklistname = $row_allcats['category'];
														
											?>
											<tr  class="careted" data-toggle="collapse" data-target=".order<?php echo $nm; ?>" style="background-color:#eff9ca">
												<td align="center" class="mb-0">
													<button class="btn btn-link" title="Click once to expand and Click twice to Collapse!!">    
														<i class="fa fa-plus-square" style="font-size:16px"></i>
													</button>
												</td>
												<td align="center"><?php echo $nm; ?></td>
												<td><?php echo $checklistname; ?></td>
												<td>
													<a href="editmitigation?level=1&catid=<?php echo $catid; ?>" data-toggle="tooltip" data-placement="bottom" title="Edit Mitigation Details"><img src="images/edit.png" width="16" height="16"/></a>&nbsp;&nbsp;<a href="delmitigation?level=1&catid=<?php echo $catid; ?>" onclick="return confirm('Are you sure you want to delete this record?')" data-toggle="tooltip" data-placement="bottom" title="Delete Mitigation Category!!"><img src="images/delete.png" width="16" height="16"/></a>
												</td>
											</tr>
											<?php		
											$query_riskcat = $db->prepare("SELECT * FROM tbl_projrisk_response WHERE cat = '$catid'");
											$query_riskcat->execute();
											$totalRows_riskcat = $query_riskcat->rowCount();
											?> 
													<tr class="collapse order<?php echo $nm; ?>" style="background-color:#8BC34A; color:#FFF">
														<th></th>
														<th>#</th>
														<th>Mitigation Measure</th>
														<th>Action</th>
													</tr>
												<?php
												if($totalRows_riskcat == 0){
													?>
													<tr class="collapse order<?php echo $nm; ?>">
														<td  colspan="4"><div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div></td>
													</tr>
												<?php }
												else{
													$num = 0;
													while($row_riskcat = $query_riskcat->fetch())
													{
														$num = $num + 1;
														$mtid = $row_riskcat['id'];
														$mitigation = $row_riskcat['response'];
																	
														?>
														<tr data-toggle="collapse" data-target=".topic<?php echo $nm.$num; ?>"  class="collapse order<?php echo $nm; ?>" style="background-color:#FFF">
															<td align="center" class="mb-0">
															</td>
															<td align="center"> <?php echo $nm.".".$num; ?></td>
															<td><?php echo $mitigation; ?></td>
															<td>
																<a href="editmitigation?level=2&mtid=<?php echo $mtid; ?>" data-toggle="tooltip" data-placement="bottom" title="Edit Mitigation Details"><img src="images/edit.png" width="16" height="16"/></a>&nbsp;&nbsp;<a href="delmitigation?level=2&mtid=<?php echo $mtid; ?>" onclick="return confirm('Are you sure you want to delete this record?')" data-toggle="tooltip" data-placement="bottom" title="Delete Mitigation!!"><img src="images/delete.png" width="16" height="16"/></a>
															</td>
														</tr>
														<?php
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
										

										$(".careted").click(function(e) {
											e.preventDefault();
											$(this)
											.find("i")
											.toggleClass("fa fa-plus-square fa fa-minus-square");
										});
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

<?php 

} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}

?>