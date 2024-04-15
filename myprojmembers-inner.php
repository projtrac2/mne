<?php 
try {
?>
	<section class="content" style="margin-top:-20px; padding-bottom:0px">
        <div class="container-fluid">
            <div class="block-header">          
				<h4>      
					<div class="col-md-8" style="font-size:15px; background-color:#CDDC39; border:#CDDC39 thin solid; border-radius:5px; margin-bottom:2px; height:25px; padding-top:2px; vertical-align:center">
						Project Name: <font color="white"><?php echo $row_rsMyP['projname']; ?></font>
					</div>
					<div class="col-md-4" style="font-size:15px; background-color:#CDDC39; border-radius:5px; height:25px; margin-bottom:2px">
						<div class="barBg" style="margin-top:0px; width:100%; border-radius:1px">
							<div class="bar hundred cornflowerblue">
								<div id="label" class="barFill" style="margin-top:0px; border-radius:1px"><?php echo $percent2 ?>%</div>
							</div>
						</div>
					</div>
				</h4>
            </div>
			<div class="row clearfix" style="margin-top:10px">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
					<div class="card">
						<div class="header" style="padding-bottom:15px">
                            <div class="button-demo" style="margin-top:-15px">
								<span class="label bg-black" style="font-size:18px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" /> Menu</span>
								<a href="myprojectdash?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; padding-left:-5px">Details</a>
								<?php if($totalRows_rsTender == 0 && $projcategory == '2'){?>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Milestones</a>
									<?php }else{?>
									<a href="myprojectmilestones?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Milestones</a>
								<?php } ?>
								<?php if($totalRows_rsTender == 0 && $projcategory == '2'){?>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Tasks</a>
									<?php }else{?>
									<a href="myprojecttask?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Tasks</a>
								<?php } ?>
								<?php if($totalRows_rsTender == 0 && $projcategory == '2'){?>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Team Members</a>
									<?php }else{?>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Team Members</a>
								<?php } ?>
								<?php //if($totalRows_rsTender == 0 && $projcategory == '2'){?>
									<!--<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px;  margin-left:-9px">Funding</a>
									<a href="myprojectfunding?projid=<?php //echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Funding</a>-->
								<?php //} ?>
								<?php if($totalRows_rsTender == 0 && $projcategory == '2'){?>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Team Discussions</a>
									<?php }else{?>
									<a href="myprojectmsgs?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Team Discussions</a>
								<?php } ?>
								<?php if($totalRows_rsTender == 0 && $projcategory == '2'){?>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Files</a>
									<?php }else{?>
									<a href="myprojectfiles?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Files</a>
								<?php } ?>
								<?php if($totalRows_rsTender == 0 && $projcategory == '2'){?>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Progress Reports</a>
									<?php }else{?>
									<a href="projreports?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Progress Report</a>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
            <div class="block-header">
				<?php 
					echo $results;
				?>
            </div>
            <!-- Advanced Form Example With Validation -->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
							<div style="color:#333; background-color:#EEE; width:100%; height:35px; padding-top:5px; padding-left:2px">
								<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-3px">
									<tr>
										<td width="50%" style="font-size:14px; font-weight:bold"><img src="images/members.png" alt="task" /> Members List</td>
										<?php
										$pjStatus = $row_rsMyP['projstatus'];
										if($pjStatus =="Cancelled" || $pjStatus =="On Hold")
										{
										}
										else{
										?>
											<td width="50%" style="font-size:10px">
												<div class="btn-group" style="float:right">
													<a type="button" class="btn bg-brown waves-effect" href="projteam?projid=<?php echo $row_rsMyP['projid']; ?>">Add New Member</a>
												</div>
											</td>
										<?php
										}
										?>
									</tr>
								</table>
							</div>
                        </div>
                        <div class="body">
							<div <?php if ($row_rsPMembers['pmid'] <=0){ echo 'style="display:none;"'; } ?>>
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover">
										<thead>
											<tr id="colrow">
												<th width="2%"><strong>Photo</strong></th>
												<th width="20%"><strong>Fullname</strong></th>
												<th width="13%"><strong>Designation</strong></th>
												<th width="9%"><strong>Availability</strong></th>
												<th width="15%"><strong><?=$ministrylabel?></strong></th>
												<th width="15%"><strong><?=$departmentlabel?></strong></th>
												<th width="13%"><strong>Email</strong></th>
												<th width="8%"><strong>Phone</strong></th>
												<?php
												$pjStatus = $row_rsMyP['projstatus'];
												if($pjStatus =="Cancelled" || $pjStatus =="On Hold")
												{
												}
												else{
												?>
													<th width="5%"><strong>Action</strong></th>
												<?php
												}
												?>
											</tr>
										</thead>
										<tbody>
											<!-- =========================================== -->
											<?php
											do { 
												$myprjmbrid = $row_rsPMembers['ptid'];
												
												$query_rsPMbrs = $db->prepare("SELECT * FROM tbl_projteam2 WHERE ptid = '$myprjmbrid' ORDER BY ptid ASC");
												$query_rsPMbrs->execute();
												$row_rsPMbrs = $query_rsPMbrs->fetch();
												
												$mbrministry = $row_rsPMbrs['ministry'];
												$mbrdept = $row_rsPMbrs['department'];
												$mbrdesg = $row_rsPMbrs['designation'];
												
												if($row_rsPMbrs['availability'] == 1){
													$availability = "<font color='#4CAF50'>Available</font>";
												}else{
													$query_reassignee = $db->prepare("SELECT reassignee FROM tbl_projmembers WHERE ptid = '$myprjmbrid'");
													$query_reassignee->execute();
													$row_reassignee = $query_reassignee->fetch();
													$reassignee = $row_reassignee["reassignee"];
													
													$query_caretaker = $db->prepare("SELECT title, fullname FROM tbl_projteam2  WHERE ptid = '$reassignee'");
													$query_caretaker->execute();
													$row_caretaker = $query_caretaker->fetch();
													
													$caretaker = $row_caretaker["title"].". ".$row_caretaker["fullname"];
													$availability = "<font color='red'>Unavailable</font>";
												}
												
												$query_rsMinistry = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid = '$mbrministry'");
												$query_rsMinistry->execute();
												$row_rsMinistry = $query_rsMinistry->fetch();
												if(empty($row_rsMinistry['sector']) || $row_rsMinistry['sector']==''){
													$ministry = "All ".$ministrylabelplural;
												}else{
													$ministry = $row_rsMinistry['sector'];
												}
												
												$query_rsDept = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid = '$mbrdept'");
												$query_rsDept->execute();
												$row_rsDept = $query_rsDept->fetch();
												if(empty($row_rsDept['sector']) || $row_rsDept['sector']==''){
													$department = "All ".$departmentlabelplural;
												}else{
													$department = $row_rsMinistry['sector'];
												}
												
												$query_rsPMbrDesg = $db->prepare("SELECT * FROM tbl_pmdesignation WHERE moid = '$mbrdesg' ORDER BY moid ASC");
												$query_rsPMbrDesg->execute();
												$row_rsPMbrDesg = $query_rsPMbrDesg->fetch();
											
											?>
												<tr>
													<td>
														<img src="<?php echo $row_rsPMbrs['floc']; ?>" style="width:30px; height:30px; margin-bottom:0px"/>
													</td>
													<td><?php echo $row_rsPMbrs['title']; ?>. <?php echo $row_rsPMbrs['fullname']; ?></td>
													<td><?php echo $row_rsPMbrDesg['designation']; ?></td>
													<td><?php if($row_rsPMbrs['availability'] == 0){ echo '<p  data-toggle="tooltip" data-placement="bottom" title="'.$caretaker.'">';}else{ echo '<p>'; } echo $availability; ?></p></td>
													<td><?php echo $ministry; ?></td>
													<td><?php echo $department; ?></td>
													<td><?php echo $row_rsPMbrs['email']; ?></td>
													<td><?php echo $row_rsPMbrs['phone']; ?></td>
													<?php
													$pjStatus = $row_rsMyP['projstatus'];
													if($pjStatus =="Cancelled" || $pjStatus =="On Hold")
													{
													}
													else{
													?>
														<td align="center">
															<a href="delprojmember?projid=<?php echo $row_rsPMembers['projid']; ?>&pmid=<?php echo $row_rsPMembers['pmid']; ?>"  onclick="return confirm('Are you sure you want to remove this member?')"><img src="images/delete.png" alt="delete" width="16" height="16" style="margin-right:5px" title="Remove Member"/></a>
														</td>
													<?php
													}
													?>
												</tr>
											<?php } while ($row_rsPMembers = $query_rsPMembers->fetch()); ?>
										</tbody>
									</table>
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Advanced Form Example With Validation -->
        </div>
    </section>

<?php 

} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}

?>