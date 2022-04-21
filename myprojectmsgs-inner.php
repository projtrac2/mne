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
									<a href="myprojmembers?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Team Members</a>
								<?php } ?>
								<?php //if($totalRows_rsTender == 0 && $projcategory == '2'){?>
									<!--<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px;  margin-left:-9px">Funding</a>
									<?php// }else{?>
									<a href="myprojectfunding?projid=<?php //echo $row_rsMyP['projid']; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Funding</a>-->
								<?php //} ?>
								<?php if($totalRows_rsTender == 0 && $projcategory == '2'){?>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Team Discussions</a>
									<?php }else{?>
									<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Team Discussions</a>
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
										<td width="50%" style="font-size:14px; font-weight:bold"><img src="images/msgs.png" alt="task" /> Project Members' Messages</td>
										<?php
										$pjStatus = $row_rsMyP['projstatus'];
										if($pjStatus =="Cancelled" || $pjStatus =="On Hold")
										{
										}
										else{
										?>
											<td width="50%" style="font-size:10px">
												<div class="btn-group" style="float:right">
													<a href="addprojectmsg.php?projid=<?php echo $row_rsMyP['projid']; ?>" type="button" class="btn bg-blue waves-effect">Post New Message</a>
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
							<div <?php if ($row_rsMessages['mgid'] <=0){ echo 'style="display:none;"'; } ?>>
								<div class="table-responsive">
									<?php do { 
										$prjid = $row_rsMessages['projid'];
										$msgid = $row_rsMessages['mgid'];
										$query_rsMsgCom =  $db->prepare("SELECT * FROM tbl_messages WHERE projid = '$prjid' AND parent = '$msgid' ORDER BY mgid");
										$query_rsMsgCom->execute();		
										$totalRows_rsMsgCom = $query_rsMsgCom->rowCount();
										?>
										<table width="98%" border="0" cellspacing="0" cellpadding="0" style="border:thin solid #CCC">
											<tr style="border-bottom:thin dashed #CCC">
												<td height="40" colspan="2" style="padding-left:10px; font-family:Verdana, Geneva, sans-serif; color:#36C; font-weight:bold; font-size:14px"><a href="viewprojectmsgs?projid=<?php echo $row_rsMessages['projid']; ?>&amp;mgid=<?php echo $row_rsMessages['mgid']; ?>" style="color:#36C"><?php echo $row_rsMessages['msubject']. " (Replies: ".$totalRows_rsMsgCom.")"; ?></a></td>
											</tr>
											<tr style="border-bottom:thin solid #EEE">
												<td height="40" colspan="2" style="padding-left:10px; padding-top:10px; font-family:Verdana, Geneva, sans-serif; color:#000; font-size:12px"><?php echo $row_rsMessages['MsgTxt']; ?></td>
											</tr>
											<tr>
												<td width="19%">
													<div align="right" style="font-family:Verdana, Geneva, sans-serif; font-size:10px; color:#000; padding-right:10px">
														<div align="left"><em style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:#000; padding-left:10px"><strong><a href="viewprojectmsgs?projid=<?php echo $row_rsMessages['projid']; ?>&amp;mgid=<?php echo $row_rsMessages['mgid']; ?>"><img src="images/readmore.png" alt="readmore" /></a></strong></em></div>
													</div>
												</td>
												<td width="81%">
													<div align="right" style="font-family:Verdana, Geneva, sans-serif; font-size:10px; color:#000; padding-right:10px"><img src="images/edit2.png" alt="edit" title="Edit Message" style="margin-right:20px"/> 
														By:<img src="<?php echo $row_rsMessages['floc']; ?>" style="width:30px; height:30px"/> <?php echo $row_rsMessages['fullname']; ?> | Date: <?php echo $row_rsMessages['pdate']; ?>
													</div>
												</td>
											</tr>
										</table>
									<?php } while ($row_rsMessages = $query_rsMessages->fetch()); ?>
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Advanced Form Example With Validation -->
        </div>
    </section>