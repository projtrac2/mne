<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="block-header" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; background-color:#607D8B; color:#FFF">
				<h4 class="contentheader"><i class="fa fa-arrows" aria-hidden="true"></i> Projects Approval
				</h4>
            </div>
            <div class="header" align="center">
                <div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
                    <div class="col-md-2">
                        <div class="form-line">
                            <input type="checkbox" name="selectAll" id="selectAll" class="" value="">
                            <label for="selectAll">Select All</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="pull-left" id="hidden">
                            <button type="button" id="approveItems" class="btn btn-info button1" data-target="#approveMultipleModal" data-toggle="modal" data-placement="top" title="Approve Items"> <i class="fa fa-check"></i></button>
                            <button type="button" id="deleteMultiple" class="btn btn-info button1" data-target="#deleteMultipleModal" data-toggle="modal" data-placement="top" title="Delete Projects"> <i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="manageItemTable">
                        <thead>
                            <tr>
                                <th width="4%"></th>
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
  
<!-- Start Item more -->
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
  
<!-- Start of multiple Item apeprove -->
<div class="modal fade" tabindex="-1" role="dialog" id="approveMultipleModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#03A9F4">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Approve Multiple Projects </h4>
            </div>
            <div class="modal-body">
                <div class="approveMultiItemMessages"></div> 
                <div>
                </div>
                <form class="form-horizontal" id="approveformMulti" action="general-settings/action/project-edit-action.php" method="POST"> 
                    <div id="approveProject">

                    </div>
            </div>
            <div class="modal-footer removeProductFooter">
                <div class="col-md-12 text-center">
                    <input type="hidden" name="approveprojects" id="" value="1">
                    <button type="button" class="btn btn-success" id="approveMultipleProjects"> <i class="fa fa-check-square-o"></i> Approve</button>
                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Cancel</button>
                    </form>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End multiple Item approve -->
 

<!-- Start multiple Item Delete --> 
<div class="modal fade" tabindex="-1" role="dialog" id="deleteMultipleModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#03A9F4">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i> Delete Item</h4>
            </div>
            <div class="modal-body">
                <div class="deleteultiItemMessages"></div>
                <p align="center">Are you sure you want to delete this records?</p>

                <!-- <form class="form-horizontal" id="" action="" method="POST"> -->
                <div id="deleteProject">
                </div>
            </div>
            <div class="modal-footer removeProductFooter">
                <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-success" id="deleteMultipleProjects"> <i class="fa fa-check-square-o"></i> Delete</button>
                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal" id="cancel"> <i class="fa fa-remove"></i> Cancel</button>
                    <!-- </form> -->
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End  multiple Item Delete -->
<script type="text/javascript">
	var url;
	url = "general-settings/selected-items/fetch-selected-project-items.php?prg=<?php echo $progid; ?>";
</script>
<script src="general-settings/js/fetch-projects.js"></script>
<script src="assets/custom js/approve-projects.js"></script>
<script src="dates.js"></script>