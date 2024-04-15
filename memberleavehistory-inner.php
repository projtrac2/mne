<?php 
try {
	//code...

?>
	<section class="content" style="margin-top:-20px; padding-bottom:0px">
        <div class="container-fluid">
			<div class="row clearfix" style="margin-top:10px">
				<!-- Advanced Form Example With Validation -->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
							<div class="row clearfix" style="margin-top:5px">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:0px">
									<div class="card">
										<div class="header" style="padding-bottom:0px">
											<div class="button-demo" style="margin-top:-15px">
												<span class="label bg-black" style="font-size:18px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" /> Staff Menu</span>
												<a href="managemember?staff=<?php echo $ptid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; padding-left:-5px">Availability</a>
												<a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Availability Records</a>
												<a href="member-performance?staff=<?php echo $ptid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px;  margin-left:-9px">Performance</a>
												<a href="member-profile?staff=<?php echo $ptid; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px;  margin-left:-9px">Profile Details</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div style="color:#333; background-color:#EEE; width:100%; height:30px; padding-top:5px; padding-left:2px">
								<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-3px">
									<tr>
										<td width="80%" style="padding-left:10px; font-size:16px; font-weight:bold"><i class="fa fa-user" aria-hidden="true"></i> Staff Full Name: <font color="indigo"><?php echo $row_rsStaff["title"].". ".$row_rsStaff["fullname"]." (".$row_rsStaff["desgn"].")"; ?></font></td>
										<td width="20%" style="font-size:11px">
											<div class="btn-group" style="float:right">
												<a href="projteam" class="btn btn-warning"  style="height:27px; ; margin-top:-1px; vertical-align:center">Go Back</a>
											</div>
										</td>
									</tr>
								</table>
							</div>
                        </div>
                        <div class="body">
							<div style="margin-top:5px">
								<fieldset class="scheduler-border">
									<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">LEAVE HISTORY</legend>
									<!-- File Upload | Drag & Drop OR With Click & Choose -->
									<div class="row clearfix">
										<div  class="col-md-12 table-responsive">
											<table class="table table-bordered table-striped table-hover dataTable js-exportable">
												<thead>
													<tr id="colrow">
														<td width="3%"><strong>SN</strong></td>
														<td width="12%"><strong>Leave Type</strong></td>
														<td width="10%"><strong>Days</strong></td>
														<td width="10%"><strong>Start Date</strong></td>
														<td width="10%"><strong>End Date</strong></td>
														<td width="15%"><strong>Caretaker</strong></td>
														<td width="40%"><strong>Comments</strong></td>
													</tr>
												</thead>
												<tbody>
													<?php
													$nm = 0;
													while($row_rsLvHistory = $query_rsLvHistory->fetch()){
														$nm = $nm + 1;
														$leavedays = $row_rsLvHistory['days'];	
														$leavestdate = $row_rsLvHistory['startdate'];
														$leavendate = $row_rsLvHistory['enddate'];
														$leavecat = $row_rsLvHistory['cat'];	
														$leavecomments = $row_rsLvHistory['comments'];
														$leavecktker = $row_rsLvHistory['caretaker'];
														
														$query_projcaretaker = $db->prepare("SELECT T.title, T.fullname FROM tbl_projteam2 T INNER JOIN tbl_employee_leave L ON T.ptid=L.caretaker WHERE L.caretaker='$leavecktker'");
														$query_projcaretaker->execute();		
														$row_projcaretaker = $query_projcaretaker->fetch();
															
														$leavestartdate = date("d M Y",strtotime($leavestdate));
														$leaveenddate = date("d M Y",strtotime($leavendate));
														?>
														<tr>
															<td><?php echo $nm; ?></td>
															<td><?php echo $leavecat; ?></td>
															<td align="center"><?php echo $leavedays; ?></td>
															<td><?php echo $leavestartdate; ?></td>
															<td><?php echo $leaveenddate; ?></td>
															<td><?php echo $row_projcaretaker["title"].". ".$row_projcaretaker["fullname"]; ?></td>
															<td><?php echo $leavecomments; ?></td>
														</tr>
													<?php 
													} 
													?>
												</tbody>
											</table>
										</div>
									</div>
									<!-- #END# File Upload | Drag & Drop OR With Click & Choose -->
								</fieldset>
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