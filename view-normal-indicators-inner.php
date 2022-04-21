
                        <div class="header">
                            <h4><i class="fa fa-list" aria-hidden="true"></i> INDICATORS REFERENCE SHEET</h4>
                        </div>
                        <div class="header">
							<div class="row clearfix" style="margin-top:5px">
								<form id="searchform" align="right" name="searchform" method="get" action="">
									<div class="col-md-2">	
										<input type="text" name="indcode" id="indcode" class="form-control" title="Indicator Code" placeholder="Filter by Code" />
									</div>
									<div class="col-md-3">	
										<select name="srcsector" id="srcsector" class="form-control show-tick" data-live-search="false">
											<option value="" selected="selected" class="selection">Filter by <?=$ministrylabel?></option>
											<?php
											do {  								
												?>
												<option value="<?php echo $row_rsSector['stid']?>"><?php echo $row_rsSector['sector']?></option>
												<?php
											} while ($row_rsSector = $query_rsSector->fetch());
											?>
										</select>
									</div>
									<div class="col-md-3">
										<select name="srcdept" id="srcdept" class="form-control show-tick" data-live-search="false">
											<option value="" selected="selected" class="selection">Filter by <?=$departmentlabel?></option>
											<?php
											do {  
												?>
												<option value="<?php echo $row_rsDept['stid']?>"><?php echo $row_rsDept['sector']?></option>
												<?php
											} while ($row_rsDept = $query_rsDept->fetch());
											?>
										</select> 
									</div>
									<div class="col-md-2">	
										<select name="srccat" id="srccat" class="form-control show-tick" data-live-search="false">
											<option value="" selected="selected" class="selection">Filter by Category</option>
											<?php
											do {  								
												?>
												<option value="<?php echo $row_rsCat['category']?>"><?php echo $row_rsCat['category']?></option>
												<?php
											} while ($row_rsCat = $query_rsCat->fetch());
											?>
										</select>
									</div>
									<div class="col-md-2">
										<div class="btn-group">
											<input type="submit" class="btn bg-light-green waves-effect waves-light" name="btn_search" id="btn_search" value="Filter" />
										</div>
										<div class="btn-group">
											<input type="button" VALUE="Reset" class="btn bg-yellow waves-effect waves-light" onclick="location.href='allindicators'" id="btnback">
										</div>
									</div>
								</form>
							</div>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
										<tr id="colrow">
											<td width="3%"><strong id="colhead">SN</strong></td>
											<td width="5%"><strong id="colhead">Code</strong></td>
											<td width="45%"><strong id="colhead">Indicator</strong></td>
											<td width="12%"><strong id="colhead">Category</strong></td>
											<td width="28%"><strong id="colhead"><?=$departmentlabel?></strong></td>
											<td width="7%"><strong id="colhead">Action</strong></td>
										</tr>
                                    </thead>
                                    <tbody><?php
									if($totalRows_rsAllIndicators == 0){
										?>
										<tr>
											<td  colspan="9"><div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div></td>
										</tr>
									<?php }
									else{
										$num=0;
										do { 
										$indid=$row_rsAllIndicators['indid'];
										$inddept=$row_rsAllIndicators['indicator_dept'];
										$num=$num + 1;
	
										$query_rsIndDept = $db->prepare("SELECT * FROM tbl_sectors WHERE stid = '$inddept'");
										$query_rsIndDept->execute();
										$row_rsIndDept = $query_rsIndDept->fetch();
										$totalRows_rsIndDept = $query_rsIndDept->rowCount();
										$dept=$row_rsIndDept['sector'];
										?>
										<tr id="rowlines">
											<td><?php echo $num; ?></td>
											<td><?php echo $row_rsAllIndicators['indicator_code']; ?></td>
											<td><?php echo $row_rsAllIndicators['indicator_name']; ?></td>
											<td><?php echo $row_rsAllIndicators['indicator_category']; ?></td>
											<td><?php echo $dept; ?></td>
											<td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Options <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a type="button" data-toggle="modal" data-target="#moreModal" id="moreModalBtn" onclick="more(<?php echo $indid ?>)">
                                                                <i class="fa fa-file-text"></i> More Info
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a type="button" href="edit-indicator?ind=<?= $indid ?>" id="addFormModalBtn">
                                                                <i class="fa fa-pencil-square"></i> </i> Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a type="button" data-toggle="modal" data-target="#removeItemModal" id="#removeItemModalBtn" onclick="removeItem(<?php echo $indid ?>)">
                                                                <i class="fa fa-trash-o"></i> Delete
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
											</td>
										</tr>
										<?php } while ($row_rsAllIndicators = $query_rsAllIndicators->fetch());
									}
									?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

<!-- Start Item more -->
<div class="modal fade" tabindex="-1" role="dialog" id="moreModal">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#03A9F4">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info-circle"></i> More Information</h4>
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
<!-- End Item more -->

<!-- Start Item Delete -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeItemModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#03A9F4">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i> Delete Indicator</h4>
            </div>
            <div class="modal-body">
                <div class="removeItemMessages"></div>
                <p align="center">Are you sure you want to delete this indicator?</p>
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
<!-- Start Item Delete -->

<!-- End add item -->
<script src="assets/custom js/fetch-selected-indicators.js"></script> 