<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="block-header" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; background-color:#607D8B; color:#FFF">
				<h4 class="contentheader"><i class="fa fa-arrows" aria-hidden="true"></i> All Projects
				</h4>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="manageItemTable">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="28%">Project Name</th>
                                <th width="27%">Program Name</th>
                                <th width="10%">Budget</th>
                                <th width="10%">Financial Year</th>
                                <th width="8%">Status</th>
                                <th width="8%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> 

<!-- Start Item Delete -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeItemModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#03A9F4">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i> Delete Item</h4>
            </div>
            <div class="modal-body">
                <div class="removeItemMessages"></div>
                <p align="center">Are you sure you want to delete this record?</p>
            </div>
            <div class="modal-footer removeProductFooter">
                <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-success" id="removeItemBtn"> <i class="fa fa-check-square-o"></i> Delete</button>
                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Cancel</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
 
<div class="modal fade" tabindex="-1" role="dialog" id="approveItemModals">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#03A9F4">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i> Undo? Approval of Project</h4>
            </div>
            <div class="modal-body">
                <div class="undotemMessages"></div>
                <p align="center">Are you sure you want to unapprove this Project?</p>
            </div>
            <div class="modal-footer">
                <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-success" id="Unapprove"> <i class="fa fa-check-square-o"></i> Unapprove</button>
                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Cancel</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Start Modal Item approve -->
<div class="modal fade" id="approveItemModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#03A9F4">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Approve Project</h4>
            </div>
            <div class="modal-body" style="max-height:450px; overflow:auto;">
				<div class="div-result">
					<form class="form-horizontal" id="approveItemForm" action="general-settings/action/project-edit-action.php" method="POST">
						<br /> 
						<div class="col-md-12" id="aproveBody"></div>
						<div class="modal-footer approveItemFooter">
							<div class="col-md-12 text-center"> 
								<input type="hidden" name="approveitem" id="approveitem" value="1">
								<input type="hidden" name="user_name" id="user_name" value="<?=$user_name?>">
								<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Approve" />
								<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
							</div>
						</div> <!-- /modal-footer -->
					</form> <!-- /.form -->
				</div> 
            </div> <!-- /modal-body -->
        </div>
        <!-- /modal-content -->
    </div>
</div>   
  
<!-- Start Item more Info -->
<div class="modal fade" tabindex="-1" role="dialog" id="moreItemModal">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#03A9F4">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info"></i> More Information</h4>
            </div>
            <div class="modal-body" id="moreinfo">
            </div>
            <div class="modal-footer">
                <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Close</button>
                </div> 
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog --> 
</div> 
<!-- End  Item more Info -->

<script type="text/javascript">
	var url;
	url = "general-settings/selected-items/fetch-selected-project-items?prg=<?php echo $progid; ?>";
</script>
<script src="general-settings/js/fetch-projects.js"></script>
<script src="assets/custom js/approve-projects.js"></script>
<script src="dates.js"></script>