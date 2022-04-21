
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h4><i class="fa fa-tasks" aria-hidden="true"></i>  Search Projects</h4>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="header">
							<div class="row clearfix" style="margin-top:5px">
								<form id="searchform" align="right" name="searchform" method="get" action="">
									<div class="col-md-2">
										<select name="srcfyear" id="srcfyear" class="form-control show-tick" data-live-search="true">
											<option value="" selected="selected" class="selection">Financial Year</option>
											<?php
											do {  
												$fyid = $row_rsFY['projfscyear'];
												$query_rsFYR = $db->prepare("SELECT year FROM tbl_fiscal_year WHERE id = '$fyid'");
												$query_rsFYR->execute();		
												$row_rsFYR = $query_rsFYR->fetch();
											?>
												<option value="<?php echo $row_rsFY['projfscyear']?>"><?php echo $row_rsFYR['year']?></option>
											<?php
											} while ($row_rsFY = $query_rsFY->fetch());
											?>
										</select>
									</div>
									<div class="col-md-2">
										<select name="srcsct" id="srcsct" class="form-control show-tick" data-live-search="true">
											<option value="" selected="selected" class="selection"><?=$ministrylabel?></option>
											<?php
											do {  
												$sctid = $row_rsSCT['projsector'];
												$query_rsSector = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid = '$sctid'");
												$query_rsSector->execute();		
												$row_rsSector = $query_rsSector->fetch();
											?>
												<option value="<?php echo $row_rsSCT['projsector']?>"><?php echo $row_rsSector['sector']?></option>
											<?php
											} while ($row_rsSCT = $query_rsSCT->fetch());
											?>
										</select> 
									</div>
									<div class="col-md-2">
										<select name="srccomm" id="srccomm" class="form-control show-tick" data-live-search="true">
											<option value="" selected="selected" class="selection"><?=$level1label?></option>
											<?php
											do {
											?>
												<option value="<?php echo $row_rsComm['id']?>"><?php echo $row_rsComm['state'] ?></option>
											<?php
											} while ($row_rsComm = $query_rsComm->fetch());
											?>
										</select>
									</div>
									<div class="col-md-2">
										<select name="srcstate" id="srcstate" class="form-control show-tick" data-live-search="true">
											<option value="" selected="selected" class="selection"><?=$level2label?></option>
											<?php
											do {
											?>
												<option value="<?php echo $row_rsWards['id']?>"><?php echo $row_rsWards['state'] ?></option>
											<?php
											} while ($row_rsWards = $query_rsWards->fetch());
											?>
										</select>
									</div>
									<div class="col-md-2">
										<select name="srctype" id="srctype" class="form-control show-tick" data-live-search="true">
											<option value="" selected="selected" class="selection">Type</option>
											<?php
											do {  
											?>
												<option value="<?php echo $row_rsPType['projtype']?>"><?php echo $row_rsPType['projtype']?></option>
											<?php
											} while ($row_rsPType = $query_rsPType->fetch());
											?>
										</select>
									</div>
									<div class="col-md-2">
										<div class="btn-group">
											<input type="submit" class="btn bg-light-green waves-effect waves-light" name="btn_search" id="btn_search" value="FILTER" />
										</div>
										<div class="btn-group">
											<input type="button" VALUE="RESET" class="btn btn-warning" onclick="location.href='projgeneralreport'" id="btnback">
										</div>
									</div>
								</form>
							</div>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
										<tr id="colrow">
										  <td width="3%"><strong>SN</strong></td>
										  <td width="26%"><strong> Name</strong></td>
										  <td width="7%"><strong>Status</strong></td>
										  <td width="7%"><strong>Progress</strong></td>
										  <td width="10%"><strong>Cost(ksh)</strong></td>
										  <td width="10%"><strong>Expenditure</strong></td>
										  <td width="7%"><strong>Output Name</strong></td>
										  <td width="10%"><strong>Actual/Target Output</strong></td>
										  <td width="10%"><strong>Location</strong></td>
										  <td width="10%"><strong>Start Date / End Date</strong></td>
										</tr>
                                    </thead>
                                    <tbody>
										<?php  include_once('projgeneralreport-code.php');?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>