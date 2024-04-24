    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
			<div class="header">
				<div style="color:#333; background-color:#EEE; width:100%; height:30px">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
						<tr>
							<td width="80%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF">
								<div align="left" style="vertical-align: text-bottom"><font size="3" color="#FFF"><i class="fa fa-clock-o" aria-hidden="true"></i></font> <font size="3" color="#FFC107"><strong>Project Workflow Stage Timelines</strong></font>
							</td>
							<td width="20%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF">
								<button type="button" id="modal_button" class="pull-right btn bg-deep-purple" data-toggle="modal" id="addItemModalBtn" data-target="#addItemModal"> <i class="fa fa-plus-square"></i> Add Timeline</button></div>
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
                                <th>#</th>
                                <th >Category</th>
                                <th >Stage</th>
                                <th >Status</th>
                                <th >Description</th>
                                <th >Time </th>
                                <th >Units</th>
                                <th >Status</th>
                                <th >Action</th>
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
    	<form class="form-horizontal" id="submitItemForm" action="general-settings/action/project-workflow-stage-timelines-action.php" method="POST" enctype="multipart/form-data">
			<div class="modal-header" style="background-color:#03A9F4">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-plus"></i> Add Project Workflow Stage Timeline</h4>
			</div>
			<div class="modal-body">
				<div class="card">
					<div class="row clearfix">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="body">
								<?php 
									$query_workflow =  $db->prepare("SELECT * FROM tbl_project_workflow_stage WHERE active=1 ORDER BY id ASC");
									$query_workflow->execute();		
									$rows_workflow = $query_workflow->fetchAll();
									$totalRows_workflow = $query_workflow->rowCount();
									
									$query_designation =  $db->prepare("SELECT * FROM tbl_pmdesignation WHERE Reporting <= 6 and active=1 ORDER BY moid ASC");
									$query_designation->execute();		
									$rows_designation = $query_designation->fetchAll();
									$totalRows_designation = $query_designation->rowCount();
								?>
								<div id="add-item-messages"></div>   
								<div class="col-md-6 form-input">
									<label><font color="#174082">Timeline Workflow Stage: </font></label>
									<select name="workflow" id="workflow" class="form-control show-tick selectpicker" data-live-search="false" required>
										<option value="" selected="selected" class="selection">Choose workflow stage</option>
										<?php
										foreach ($rows_workflow as $row_workflow) {
											?>
											<option value="<?php echo $row_workflow['id'] ?>"><?php echo $row_workflow['stage'] ?></option>
										<?php
										}
										?>
									</select>  
								</div> <!-- /form-group-->    
								<div class="col-md-6 form-input">
									<label><font color="#174082">Timeline Category: </font></label>
									<input type="text" class="form-control" id="category" placeholder="Workflow category" name="category" required autocomplete="off">
								</div> <!-- /form-group-->        	 
								<div class="col-md-6 form-input">
									<label><font color="#174082">Timeline Stage: </font></label>
									<input type="text" class="form-control" id="stage" placeholder="Timeline workflow stage" name="stage" required autocomplete="off">
								</div> <!-- /form-group-->  
								<div class="col-md-6 form-input">
									<label><font color="#174082">Timeline Status Name: </font></label>
									<input type="text" class="form-control" id="statusname" placeholder="Add timeline Status Name" name="statusname" required autocomplete="off">
								</div> <!-- /form-group-->  
								<div class="col-md-12 form-input">
									<label><font color="#174082">Timeline Description: </font></label>
									<textarea class="form-control" id="description" placeholder="More info about the timeline if necessary" name="description" autocomplete="off"></textarea>
								</div>
								<div class="col-md-6 form-input">
									<label><font color="#174082">Timeline Time: </font></label>
									<input type="number" class="form-control" id="time" placeholder="Amount of time in numbers" name="time" required autocomplete="off">
								</div> <!-- /form-group-->  
								<div class="col-md-6 form-input">
									<label><font color="#174082">Timeline Units: </font></label>
									<input type="text" class="form-control" id="units" placeholder="E.g Days, Weeks,... " name="units" required autocomplete="off">
								</div>  <!-- /form-group-->   
								<div class="col-md-6 form-input">
									<label><font color="#174082">Escalate To: </font></label>
									<select name="escalateto" id="escalateto" class="form-control show-tick selectpicker" data-live-search="false" required>
										<option value="" selected="selected" class="selection">Choose designation to escalate to</option>
										<?php
										foreach ($rows_designation as $row_designation) {
											?>
											<option value="<?php echo $row_designation['moid'] ?>"><?php echo $row_designation['designation'] ?></option>
										<?php
										}
										?>
									</select>  
								</div> <!-- /form-group-->  
								<div class="col-md-6 form-input">
									<label><font color="#174082">Escalate After (days): </font></label>
									<input type="number" class="form-control" id="escalateafter" placeholder="Escalate after how many days" name="escalateafter" required autocomplete="off">
								</div> <!-- /form-group-->
								<!-- /form-group-->	
							</div>
						</div>	   
					</div>		   
				</div>	         	        
			</div> <!-- /modal-body -->
	      
			<div class="modal-footer">  
				<div class="col-md-12 text-center">
					<input type="hidden" name="newitem" id="newitem" value="new">
					<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
					<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
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
				<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Edit Project Workflow Stage Timeline</h4>
			</div>
			<div class="modal-body" style="max-height:450px; overflow:auto;">
				<div class="card">
					<div class="row clearfix">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="body">
								<div class="div-result">
									<form class="form-horizontal" id="editItemForm" action="general-settings/action/project-workflow-stage-timelines-action.php" method="POST">	
										<br />
										<div class="col-md-12 id="edit-Workflow Stage Timelines-messages"></div>  
										<div class="col-md-6 form-input">
											<label><font color="#174082">Timeline Workflow Stage: </font></label>
											<select name="editworkflow" id="editworkflow" class="form-control show-tick selectpicker" data-live-search="false" required>
												<option value="" selected="selected" class="selection">Choose workflow stage</option>
												<?php
												foreach ($rows_workflow as $row_workflow) {
													?>
													<option value="<?php echo $row_workflow['id'] ?>"><?php echo $row_workflow['stage'] ?></option>
												<?php
												}
												?>
											</select>  
										</div> <!-- /form-group-->  	     
										<div class="col-md-6 form-input">
											<label><font color="#174082">Timeline Category: </font></label>
											<input type="text" class="form-control" id="editcategory" placeholder="Workflow category" name="editcategory" required autocomplete="off">
										</div> <!-- /form-group-->        	 
										<div class="col-md-6 form-input">
											<label><font color="#174082">Timeline Stage: </font></label>
											<input type="number" class="form-control" id="editstage" placeholder="Timeline workflow stage" name="editstage" required autocomplete="off">
										</div> <!-- /form-group-->  
										<div class="col-md-6 form-input">
											<label><font color="#174082">Timeline Status Name: </font></label>
											<input type="text" class="form-control" id="editstatusname" placeholder="Add timeline status name" name="editstatusname" required autocomplete="off">
										</div> <!-- /form-group-->  
										<div class="col-md-12 form-input">
											<label><font color="#174082">Timeline Description: </font></label>
											<textarea class="form-control" id="editdescription" placeholder="More info about the timeline if necessary" name="editdescription" autocomplete="off"></textarea>
										</div>
										<div class="col-md-6 form-input">
											<label><font color="#174082">Timeline Time: </font></label>
											<input type="text" class="form-control" id="edittime" placeholder="Amount of time in numbers" name="edittime" required autocomplete="off">
										</div> <!-- /form-group-->  
										<div class="col-md-6 form-input">
											<label><font color="#174082">Timeline Units: </font></label>
											<input type="text" class="form-control" id="editunits" placeholder="E.g Days, Weeks,... " name="editunits" required autocomplete="off">
										</div>  <!-- /form-group-->   
										<div class="col-md-6 form-input">
											<label><font color="#174082">Escalate To: </font></label>
											<select name="editescalateto" id="editescalateto" class="form-control show-tick selectpicker" data-live-search="false" required>
												<option value="" selected="selected" class="selection">Choose designation to escalate to</option>
												<?php
												foreach ($rows_designation as $row_designation) {
													?>
													<option value="<?php echo $row_designation['moid'] ?>"><?php echo $row_designation['designation'] ?></option>
												<?php
												}
												?>
											</select>  
										</div> <!-- /form-group-->  
										<div class="col-md-6 form-input">
											<label><font color="#174082">Escalate After (days): </font></label>
											<input type="number" class="form-control" id="editescalateafter" placeholder="Escalate after how many days" name="editescalateafter" required autocomplete="off">
										</div> <!-- /form-group-->
										<div class="col-md-6 form-input">
											<label for="editStatus"><font color="#174082">Timeline Condition: </font></label>
											<select class="form-control" id="editStatus" name="editStatus" required>
												<option value="">~~SELECT~~</option>
												<option value="1">Enabled</option>
												<option value="0">Disabled</option>
											</select>
										</div> 	
										<div class="modal-footer editItemFooter">  
											<div class="col-md-12 text-center">
												<input type="hidden" name="edititem" id="edititem" value="edit">
												<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
												<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
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
                <h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i> Delete Project Workflow Stage Timeline</h4>
            </div>
            <div class="modal-body">
                <div class="removeItemMessages"></div>
                <p align="center">Are you sure you want to delete this record?</p>
            </div>
            <div class="modal-footer removeWorkflow Stage TimelinesFooter">
                <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Cancel</button>
                    <button type="button" class="btn btn-success" id="removeItemBtn"> <i class="fa fa-check-square-o"></i> Delete</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Item Delete -->

<script src="general-settings/js/fetch-project-workflow-timelines.js"></script> 