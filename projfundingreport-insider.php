            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h4><i class="fa fa-tasks" aria-hidden="true"></i>  Search Projects Funding</h4>
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
												$fscYear = $row_rsYear["projfscyear"];				
												$query_rsFscYear = $db->prepare("SELECT year FROM tbl_fiscal_year WHERE id='$fscYear'");
												$query_rsFscYear->execute();		
												$row_rsFscYear = $query_rsFscYear->fetch();
												$totalRows_rsFscYear = $query_rsFscYear->rowCount();
												?>
												<option value="<?php echo $row_rsYear['projfscyear']?>"><?php echo $row_rsFscYear['year']?></option>
												<?php
											} while ($row_rsYear = $query_rsYear->fetch());
											?>
										</select>
									</div>
									<div class="col-md-2">
										<select name="srcsct" id="srcsct" class="form-control show-tick" data-live-search="true">
											<option value="" selected="selected" class="selection"><?=$ministrylabel?></option>
											<?php
											do {  
												$projsct = $row_rsSector["projsector"];												
												$query_rsSct = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid='$projsct'");
												$query_rsSct->execute();		
												$row_rsSct = $query_rsSct->fetch();
												$totalRows_rsSct = $query_rsSct->rowCount();
												?>
												<option value="<?php echo $row_rsSector['projsector']?>"><?php echo $row_rsSct['sector']?></option>
												<?php
											} while ($row_rsSector = $query_rsSector->fetch());
											?>
										</select> 
									</div>
									<div class="col-md-2">
										<select name="srcdept" id="srcdept" class="form-control show-tick" data-live-search="true">
											<option value="" selected="selected" class="selection"><?=$departmentlabel?></option>
											<?php
											do {  
												$projDept = $row_rsDept["projdepartment"];				
												$query_rsDpmnt = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid='$projDept'");
												$query_rsDpmnt->execute();		
												$row_rsDpmnt = $query_rsDpmnt->fetch();
												$totalRows_rsDpmnt = $query_rsDpmnt->rowCount();
											?>
												<option value="<?php echo $row_rsDept['projdepartment']?>"><?php echo $row_rsDpmnt['sector']?></option>
											<?php
											} while ($row_rsDept = $query_rsDept->fetch());
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
												<option value="<?php echo $row_rsState['id']?>"><?php echo $row_rsState['state']?></option>
											<?php
											} while ($row_rsState = $query_rsState->fetch());
											?>
										</select>
									</div>
									<div class="col-md-2">
										<select name="srcstatus" id="srcstatus"  class="form-control show-tick" data-live-search="true">
											<option value="" selected="selected" class="selection">Status</option>
											<?php
											do {  
												$row_status = $row_rsPStatus['projstatus'];																	
												$query_rsStatusName = $db->prepare("SELECT statusname FROM tbl_status WHERE statusid='$row_status'");
												$query_rsStatusName->execute();		
												$row_rsStatusName = $query_rsStatusName->fetch();
												$totalRows_rsStatusName = $query_rsStatusName->rowCount();
												$prjstatus = $row_rsStatusName['statusname'];
											?>
												<option value="<?php echo $row_rsPStatus['projstatus']?>"><?php echo $row_rsPStatus['projstatus']?></option>
											<?php
											} while ($row_rsPStatus = $query_rsPStatus->fetch());
											?>
										</select> 
									</div>
									<div class="col-md-3" style="float:right; margin:10px">
										<div class="btn-group">
											<input type="submit" class="btn bg-light-green waves-effect waves-light"class="srcbutton" name="btn_search" id="btn_search" value="FILTER" />
										</div>&nbsp;					
										<div class="btn-group" style="float:right">
											<button type="button" class="btn bg-pink dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												ACTION <span class="caret"></span>
											</button>
											<ul class="dropdown-menu">
												<li><input type="button" VALUE="RESET FILTER" class="btn btn-warning" onclick="location.href='projfundingreport'" id="btnback" style="margin-left:10px"></li>
												<li role="separator" class="divider"></li>
												<li><a href="allprojfunding?srcfyear=<?=$pfscyr_rsUpP?>&srcsct=<?=$psector_rsUpP?>&srcdept=<?=$pdept_rsUpP?>&srccomm=<?=$pcomm_rsUpP?>&srcstate=<?=$pstate_rsUpP?>&srcstatus=<?=$pstatus_rsUpP?>&btn_csv=CSV">Export to CSV</a></li>
												<li><a href="pdfexport?pfscyr=<?=$pfscyr_rsUpP?>&psector=<?=$psector_rsUpP?>&pdept=<?=$pdept_rsUpP?>&pscounty=<?=$pcomm_rsUpP?>&pward=<?=$pstate_rsUpP?>&srcstatus=<?=$pstatus_rsUpP?>&btn_pdf=PDF" target="new">Export to PDF</a></li>
											</ul>
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
										  <td width="22%"><strong>Project Name</strong></td>
										  <td width="6%"><strong>Status</strong></td>
										  <td width="12%"><strong><?=$ministrylabel?></strong></td>
										  <td width="13%"><strong>Budget (Ksh)</strong></td>
										  <td width="10%"><strong>Actual Expenditure (Ksh)</strong></td>
										  <td width="13%"><strong>Variance (Ksh)</strong></td>
										  <td width="7%"><strong>Rate of Utilization</strong></td>
										  <td width="7%"><strong>Start Date</strong></td>
										  <td width="7%"><strong>End Date</strong></td>
										</tr>
                                    </thead>
                                    <tbody>
										<?php  include_once('projfundingreport-code.php');?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>