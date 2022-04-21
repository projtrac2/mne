            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h4><i class="fa fa-list" aria-hidden="true"></i> All Outputs</h4>
                        </div>
                        <div class="header">
							<div class="row clearfix" style="margin-top:5px">								
								<form id="searchform" name="searchform" method="get" style="margin-top:-10px" action="<?php echo $_SERVER['PHP_SELF']; ?>" autocomplete="off">
									<div class="col-md-2" style="width:150px">
										<select name="srcsct" id="srcsct" class="form-control show-tick" data-live-search="true">
											<option value="" selected="selected" class="selection"><?=$ministrylabel?></option>
											<?php
											do {  					
												$projsct = $row_rsSector["projsector"];													
												$query_rsSct = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid='$projsct'");
												$query_rsSct->execute();		
												$row_rsSct = $query_rsSct->fetch();
												?>
												<option value="<?php echo $row_rsSector['projsector']?>"><?php echo $row_rsSct['sector']?></option>
											<?php
											} while ($row_rsSector = $query_rsSector->fetch());
											?>
										</select> 
									</div>
									<div class="col-md-2" style="width:170px">
										<select name="srcdept" id="srcdept" class="form-control show-tick" data-live-search="true">
											<option value="" selected="selected" class="selection"><?=$departmentlabel?></option>
											<?php
											do {  
												$projDept = $row_rsDept["projdepartment"];
												$query_rsDpmnt = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid='$projDept'");
												$query_rsDpmnt->execute();		
												$row_rsDpmnt = $query_rsDpmnt->fetch();
												?>
												<option value="<?php echo $row_rsDept['projdepartment']?>"><?php echo $row_rsDpmnt['sector']?></option>
											<?php
											} while ($row_rsDept = $query_rsDept->fetch());
											?>
										</select>
									</div>
									<div class="col-md-2" style="width:170px">
										<select name="srccomm" id="srccomm" class="form-control show-tick" data-live-search="true">
											<option value="" selected="selected" class="selection"><?=$level1label?></option>
											<?php
											do {
												?>
												<option value="<?php echo $row_rsComm['id']?>"><?php echo $row_rsComm['state']?></option>
											<?php
											} while ($row_rsComm = $query_rsComm->fetch());
											?>
										</select>
									</div>
									<div class="col-md-2" style="width:150px">
										<select name="srcward" id="srcward" class="form-control show-tick" data-live-search="true">
											<option value="" selected="selected" class="selection"><?=$level2label?></option>
											<?php
											do {
												?>
												<option value="<?php echo $row_rsWard['id']?>"><?php echo $row_rsWard['state']?></option>
											<?php
											} while ($row_rsWard = $query_rsWard->fetch());
										?>
										</select>
									</div>	
									<div class="col-xs-3">
										<div class="input-daterange input-group" id="bs_datepicker_range_container">
											<div class="form-line">
												<input name="startdate" type="text" class="form-control" placeholder="Start Date">
											</div>
											<span class="input-group-addon">to</span>
											<div class="form-line">
												<input name="enddate" type="text" class="form-control" placeholder="End Date">
											</div>
										</div>
									</div>									
									<div class="col-md-2" style="width:180px">
										<div class="btn-group">
											<input type="submit" class="btn bg-light-green waves-effect waves-light" name="btn_search" id="btn_search" value="FILTER" />
										</div>&nbsp;
										<div class="btn-group">
											<input type="button" VALUE="RESET" class="btn btn-warning" onclick="location.href='alloutputsreport'" id="btnback">
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
											<td width="5%"><strong id="colhead">SN</strong></td>
											<td width="30%"><strong id="colhead">Output Name</strong></td>
											<td width="20%"><strong id="colhead">Indicator</strong></td>
											<td width="12%"><strong id="colhead">Baseline</strong></td>
											<td width="11%"><strong id="colhead">Target</strong></td>
											<td width="11%"><strong id="colhead">Achieved</strong></td>
											<td width="11%"><strong id="colhead">Beneficiaries</strong></td>
										</tr>
                                    </thead>
                                    <tbody>
										<?php  include_once('alloutputsreport-code.php');?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>