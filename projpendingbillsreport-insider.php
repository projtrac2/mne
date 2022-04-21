            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h4><i class="fa fa-search" aria-hidden="true"></i> Search Complete Projects With Pending Payment(s)</h4>
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
												$row_SCName = $row_rsComm['projcommunity'];
												if($row_SCName == '101')
												{
													$subcounty = "All Sub-Counties";
												}
												else
												{															
													$query_rsSCName = $db->prepare("SELECT state FROM tbl_state WHERE id='$row_SCName'");
													$query_rsSCName->execute();		
													$row_rsSCName = $query_rsSCName->fetch();
													$totalRows_rsSCName = $query_rsSCName->rowCount();
													$subcounty = $row_rsSCName['state'];
												}
											?>
												<option value="<?php echo $row_rsComm['projcommunity']?>"><?php echo $subcounty?></option>
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
												$row_WardName = $row_rsState['projlga'];
												if($row_WardName == "102")
												{
													$ward = "All Wards";
												}
												elseif($row_WardName == "")
												{
													
												}
												else{																			
													$query_rsWardName = $db->prepare("SELECT id,state FROM tbl_state WHERE id='$row_WardName'");
													$query_rsWardName->execute();		
													$row_rsWardName = $query_rsWardName->fetch();
													$totalRows_rsWardName = $query_rsWardName->rowCount();
													$ward = $row_rsWardName['state'];
												}
											?>
												<option value="<?php echo $row_rsWardName['id']?>"><?php echo $ward?></option>
											<?php
											} while ($row_rsState = $query_rsState->fetch());
											?>
										</select>
									</div>
									<div class="col-md-3" style="float:right; padding-top:10px">
										<div class="btn-group">
											<input type="submit" class="btn bg-light-green waves-effect waves-light" name="btn_search" id="btn_search" value="FILTER" />
										</div>&nbsp;					
										<div class="btn-group" style="float:right">
											<button type="button" class="btn bg-pink dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												ACTION <span class="caret"></span>
											</button>
											<ul class="dropdown-menu">
												<li><input type="button" VALUE="Reset Filter" class="btn btn-warning" onclick="location.href='projpendingbillsreport'" id="btnback" style="margin-left:10px"></li>
												<li role="separator" class="divider"></li>
												<li><a href="projpendingbills?srcfyear=<?=$pfscyr_rsUpP?>&srcsct=<?=$psector_rsUpP?>&srcdept=<?=$pdept_rsUpP?>&srccomm=<?=$pcomm_rsUpP?>&srcstate=<?=$pstate_rsUpP?>&srcstatus=<?=$pstatus_rsUpP?>&btn_csv=CSV" title="Export to CSV">Export to CSV</a></li>
												<li><a href="pdfprojpendingbills?pfscyr=<?=$pfscyr_rsUpP?>&psector=<?=$psector_rsUpP?>&pdept=<?=$pdept_rsUpP?>&pscounty=<?=$pcomm_rsUpP?>&pward=<?=$pstate_rsUpP?>&srcstatus=<?=$pstatus_rsUpP?>&btn_pdf=PDF"  target="_blank" title="Export to pdf">Export to PDF</a></li>
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
										  <td width="12%"><strong><?=$departmentlabel?></strong></td>
										  <td width="11%"><strong>Location</strong></td>
										  <td width="12%"><strong>Cost (Ksh)</strong></td>
										  <td width="11%"><strong>Disbursed (Ksh)</strong></td>
										  <td width="12%"><strong>Outstanding Bal (Ksh)</strong></td>
										  <td width="7%"><strong>Date Completed</strong></td>
										  <td width="10%"><strong>Contractor</strong></td>
										</tr>
                                    </thead>
                                    <tbody>
										<?php  include_once('projpendingbillsreport-code.php');?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>