<!-- Start Modal Item Edit -->
<div class="modal fade" id="adddetailsModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header" style="background-color:#03A9F4">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> <span id="modal_title">Add details</span> </h4>
			</div>
			<div class="modal-body" style="max-height:450px; overflow:auto;">
				<div class="card">
					<div class="row clearfix">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="body">
								<div class="div-result">
									<form class="form-horizontal" id="addform" action="" method="POST">
										<br />
										<div id="unitsof_measure">
											<div class="col-md-12" id="indirectbeneficiary">
												<label for="diss_type_name" class="control-label">Measurement Unit *:</label>
												<div class="form-input">
													<input type="text" name="unit" id="unit" placeholder="Enter" class="form-control">
												</div>
											</div>
											<div class="col-md-12" id="indirectbeneficiary">
												<label for="diss_type_name" class="control-label">Measurement Unit Description *:</label>
												<div class="form-input">
													<textarea name="unitdescription" id="unitdescription" cols="" rows="" class="form-control">
													</textarea>
												</div>
											</div>
										</div>
										<div id="diss_type">
											<div class="col-md-6" id="indirectbeneficiary">
												<label for="diss_type_name" class="control-label">Add Disaggregation Type*:</label>
												<div class="form-input">
													<input type="text" name="diss_type_name" id="diss_type_name" placeholder="Enter" class="form-control">
												</div>
											</div>
											<div class="col-md-6">
												<label class="control-label" title="">Disaggregation Category*:</label>
												<div class="form-line">
													<select name="disaggregation_cat" id="disaggregation_cat" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" false required="required">
														<option value="">.... Select from list ....</option>
														<option value="0">Location</option>
														<option value="1">Others</option>
													</select> 
												</div>
											</div>
											<div class="col-md-12" id="indirectbeneficiary">
												<label for="diss_type_name" class="control-label">Disaggregation Type Description *:</label>
												<div class="form-input">
													<textarea name="disdescription" id="disdescription" cols="" rows="" class="form-control">
													</textarea>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<div class="col-md-12 text-center" id="">
												<input type="hidden" name="addnew" id="addnew" value="addnew">
												<input type="hidden" name="type_diss" id="type_diss" value="">
												<input type="hidden" name="dissegration_category" id="dissegration_category" value="">
												<input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
												<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
												<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- /modal-body -->
		</div>
		<!-- /modal-content -->
	</div>
	<!-- /modal-dailog -->
</div>