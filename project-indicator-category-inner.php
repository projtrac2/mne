    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    	<div class="card">
    		<div class="header">
    			<div style="color:#333; background-color:#EEE; width:100%; height:30px">
    				<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
    					<tr>
    						<td width="80%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF">
    							<div align="left" style="vertical-align: text-bottom">
    								<font size="3" color="#FFF"><i class="fa fa-map-marker" aria-hidden="true"></i></font>
    								<font size="3" color="#FFC107"><strong>Indicator Category</strong></font>
    						</td>
    						<td width="20%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF">
    							<button type="button" id="modal_button" class="pull-right btn bg-deep-purple" data-toggle="modal" id="addItemModalBtn" data-target="#addItemModal"> <i class="fa fa-plus-square"></i> Add Indicator Category</button>
    			</div>
    			</td>
    			</tr>
    			</table>
    		</div>
    	</div>
    	<div class="body">
    		<div class="table-responsive">
    			<table class="table table-bordered table-striped table-hover" id="manageItemTable">
    				<thead>
    					<tr>
    						<th width="3%">#</th>
    						<th width="35%">Indicator Category</th>
    						<th width="42%">Indicator Category Description</th>
    						<th width="10%">Status</th>
    						<th width="10%">Action</th>
    					</tr>
    				</thead>
    			</table>
    		</div>
    	</div>
    </div>
    </div>
    </div>

    <!-- add item -->
    <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog">
    	<div class="modal-dialog modal-lg">
    		<div class="modal-content">
    			<form class="form-horizontal" id="submitItemForm" action="general-settings/action/project-indicator-category-action.php" method="POST" enctype="multipart/form-data">
    				<div class="modal-header" style="background-color:#03A9F4">
    					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    					<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-plus"></i> Add Indicator Category</h4>
    				</div>

    				<div class="modal-body">
    					<div class="card">
    						<div class="row clearfix">
    							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    								<div class="body">
    									<div id="add-item-messages"></div>
    									<div class="col-md-12 form-input">
    										<label>
    											<font color="#174082">Indicator Category: </font>
    										</label>
    										<input type="text" class="form-control" id="category" placeholder="Add Indicator Category" name="category" required autocomplete="off">
    									</div> <!-- /form-group-->

    									<div class="col-md-12 form-input">
    										<label>
    											<font color="#174082">Indicator Category Description: </font>
    										</label>
    										<textarea class="form-control" id="description" placeholder="Indicator Category Description" name="description" required autocomplete="off"></textarea>
    									</div>
    									<!-- /form-group-->
    									<div class="col-md-4 form-input">
    										<label for="editStatus">
    											<font color="#174082">Indicator Category Type: </font>
    										</label>
    										<select class="form-control" id="editCategoryType" name="editType" required>
    											<option value="select...">Select...</option>
    											<option value="1">Impact</option>
    											<option value="2">Outcome</option>
    											<option value="3">Output</option>
    										</select>
    									</div>
    								</div>
    							</div>
    						</div>
    					</div>
    				</div> <!-- /modal-body -->

    				<div class="modal-footer">
    					<div class="col-md-12 text-center">
    						<input type="hidden" name="newitem" id="newitem" value="new">
    						<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
    						<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
    					</div>
    				</div> <!-- /modal-footer -->
    			</form> <!-- /.form -->
    		</div> <!-- /modal-content -->
    	</div> <!-- /modal-dailog -->
    </div>
    <!-- End add item -->


    <!-- Start Modal Item Edit -->
    <div class="modal fade" id="editItemModal" tabindex="-1" role="dialog">
    	<div class="modal-dialog modal-lg">
    		<div class="modal-content">
    			<div class="modal-header" style="background-color:#03A9F4">
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    				<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Edit Indicator Category</h4>
    			</div>
    			<div class="modal-body" style="max-height:450px; overflow:auto;">
    				<div class="card">
    					<div class="row clearfix">
    						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    							<div class="body">
    								<div class="div-result">
    									<form class="form-horizontal" id="editItemForm" action="general-settings/action/project-indicator-category-action.php" method="POST">
    										<br />
    										<div class="col-md-12 id=" edit-product-messages"></div>
    										<div class="col-md-8 form-input">
    											<label>
    												<font color="#174082">Indicator Category: </font>
    											</label>
    											<input type="text" class="form-control" id="editCategory" placeholder="Add Indicator Category" name="editCategory" required autocomplete="off">
    										</div> <!-- /form-group-->

    										<div class="col-md-4 form-input">
												<label for="editStatus">
													<font color="#174082">Indicator Category Type: </font>
												</label>
												<select class="form-control" id="editECategoryType" name="editType" required>
													<option value="select...">Select...</option>
													<option value="1">Impact</option>
													<option value="2">Outcome</option>
													<option value="3">Output</option>
												</select>
											</div> <!-- /form-group-->
    										<div class="col-md-12 form-input" style="margin-top:10px">
    											<label>
    												<font color="#174082">Indicator Category Description: </font>
    											</label>
    											<textarea class="form-control" id="editDescription" placeholder="Name of Map  Description" name="editDescription" required autocomplete="off" required></textarea>
    										</div> <!-- /form-group-->
    										<div class="modal-footer editItemFooter">
    											<div class="col-md-12 text-center">
    												<input type="hidden" name="edititem" id="edititem" value="edit">
    												<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
    												<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
    											</div>
    										</div> <!-- /modal-footer -->
    									</form> <!-- /.form -->
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
    <!-- End Item Edit -->

    <!-- Start Item Delete -->
    <div class="modal fade" tabindex="-1" role="dialog" id="removeItemModal">
    	<div class="modal-dialog">
    		<div class="modal-content">
    			<div class="modal-header" style="background-color:#03A9F4">
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    				<h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i> Delete Indicator Category</h4>
    			</div>
    			<div class="modal-body">
    				<div class="removeItemMessages"></div>
    				<p align="center">Are you sure you want to delete this record?</p>
    			</div>
    			<div class="modal-footer removeProductFooter">
    				<div class="col-md-12 text-center">
    					<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Cancel</button>
    					<button type="button" class="btn btn-success" id="removeItemBtn"> <i class="fa fa-check-square-o"></i> Delete</button>
    				</div>
    			</div>
    		</div><!-- /.modal-content -->
    	</div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Item Delete -->
    <script src="assets/js/settings/indicators/indicator-category.js"></script>