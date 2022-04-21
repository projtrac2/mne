
<?php
try {
	?>
<!--<div class="clearfix m-b-20">
    <div class="content" style="margin-top:-10px">-->
<div class="body">
    <div class="table-responsive">
		<ul class="nav nav-tabs" style="font-size:14px">
			<li class="active">
				<a data-toggle="tab" href="#home"><i class="fa fa-caret-square-o-down bg-deep-orange" aria-hidden="true"></i> Untendered/Pending &nbsp;<span class="badge bg-orange"><?php echo $totalRows_projects; ?></span></a>
			</li>
			<li>
				<a data-toggle="tab" href="#menu1"><i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> Contracts &nbsp;<span class="badge bg-blue"><?php echo $totalRows_tenderedprojects; ?></span></a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="home" class="tab-pane fade in active">
				<div style="color:#333; background-color:#EEE; width:100%; height:30px">
					<h4 style="width:100%"><i class="fa fa-list" style="font-size:25px;color:#FF9800"></i> Project Pending Tenders Info</h4>
				</div>  
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <tr class="bg-orange">
                            <th style="width:3%">#</th>
                            <th style="width:8%">Code</th>
                            <th style="width:29%">Project Name</th>
                            <th style="width:10%"><?=$departmentlabel?></th>
                            <th style="width:10%">Cost</th>
                            <th style="width:10%">Funder</th>
                            <th style="width:10%">Start Date</th>
							<th style="width:10%">Status</th>
                            <th style="width:10%">Add Tender</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php
						$nm = 0;
						while($row_projects = $query_projects->fetch())
						{ 							
							$nm = $nm + 1;
							$projid = $row_projects['projid'];
							$deptid = $row_projects['projdepartment'];
							
							$query_projfunding = $db->prepare("SELECT gf.sourcecategory, gf.sourceid FROM tbl_myprojfunding f inner join tbl_myprogfunding gf on gf.id=f.progfundid WHERE f.projid = :projid");
							$query_projfunding->execute(array(":projid" => $projid));
							$count_projfunding = $query_projfunding->rowCount();
	
							$duedate = strtotime($row_projects['projstartdate']."+ 30 days");
							$tenderduedate = date("d M Y",$duedate);
							
							$startdate = date("d M Y",strtotime($row_projects['projstartdate']));
							
							$funders = '';
							while($row_projfunding = $query_projfunding->fetch()){
								$sourceid = $row_projfunding['sourceid'];
								$category = $row_projfunding['sourcecategory'];
								
								if($category == "donor"){
									$query_funder = $db->prepare("SELECT donorname FROM tbl_donors WHERE dnid = '$sourceid'");
									$query_funder->execute();
									$row_funder = $query_funder->fetch();
									$funder = $row_funder["donorname"];
								}else{
									$query_funder = $db->prepare("SELECT name FROM tbl_funder WHERE id = '$sourceid'");
									$query_funder->execute();
									$row_funder = $query_funder->fetch();
									$funder = $row_funder["name"];
								}
								
								if($count_projfunding > 1){
									$funders .= $funder.", ";
								} else {
									$funders .= $funder;
								}
							}
										
							$query_reqstatus = $db->prepare("SELECT status FROM tbl_payment_status WHERE id = '$reqstatus'");
							$query_reqstatus->execute();
							$row_reqstatus = $query_reqstatus->fetch();
							$requeststatus = $row_reqstatus["status"];
							
							$query_dept = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid = '$deptid'");
							$query_dept->execute();
							$row_dept = $query_dept->fetch();
							$department = $row_dept["sector"];
										
							?>
							<tr style="background-color:#eff9ca">
								<td align="center"><?php echo $nm; ?></td>
								<td><?php echo $row_projects['projcode']; ?></td>
								<td><?php echo $row_projects['projname']; ?></td>
								<td><?php echo $department; ?></td>
								<td><?php echo number_format($row_projects['projcost'], 2); ?></td>
								<td><?php echo $funders; ?></td>
								<td><?php echo $startdate; ?></td>
								<td><?php echo $row_projects['projstatus']; ?></td>
								<td>
									<div align="center"><a href="addtender?proj=<?php echo $projid; ?>" width="16" height="16" id="view" data-toggle="tooltip" data-placement="bottom" title="Add Tender Details"><i class="fa fa-plus fa-2x text-success" aria-hidden="true"></i></a></div>
								</td>
							</tr>
						<?php
						}
						?> 
					</tbody>
                </table>    
			</div>
			<div id="menu1" class="tab-pane fade">
				<div style="color:#333; background-color:#EEE; width:100%; height:30px">
					<h4><i class="fa fa-list" style="font-size:25px;color:blue"></i> Project with Contract/Tender Info</h4>
				</div>  
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <tr id="colrow">
                            <th style="width:3%">#</th>
                            <th style="width:25%">Project Name</th>
                            <th style="width:8%">Contract No</th>
                            <th style="width:12%">Contract Category</th>
                            <th style="width:12%">Procurement Type</th>
                            <th style="width:10%">Project Budget (Ksh)</th>
                            <th style="width:10%">Contract Amount (Ksh)</th>
                            <th style="width:10%">Start Date</th>
							<th style="width:10%">End Date</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php
						$nm = 0;
						while($tenderedprojects = $query_tenderedprojects->fetch())
						{ 
							$nm = $nm + 1;
							$tenderid = $tenderedprojects['td_id'];
							$project = $tenderedprojects['projname'];
							$projid = $tenderedprojects['projid'];
							$tender = $tenderedprojects['tendertitle'];
							$type = $tenderedprojects['type'];
							$category = $tenderedprojects['category'];
							$budget = number_format($tenderedprojects['projcost'], 2);
							$tenderamount = number_format($tenderedprojects['tenderamount'], 2);
							
							$appduedate = strtotime($approveddate."+ 30 days");
							$paymentduedate = date("d M Y",$appduedate);
							
							$sdate = date("d M Y",strtotime($tenderedprojects["startdate"]));
							$edate = date("d M Y",strtotime($tenderedprojects["enddate"]));
							?>
							<tr>
								<td align="center"><?php echo $nm; ?></td>
								<td><?php echo $project; ?></td>
								<td><?php echo $tenderedprojects['contractrefno']; ?></td>
								<td><?php echo $category; ?></td>
								<td><?php echo $type; ?></td>
								<td><?php echo $budget; ?></td>
								<td><?php echo $tenderamount; ?></td>
								<td><?php echo $sdate; ?></td>
								<td><?php echo $edate; ?></td>
							</tr>
						<?php
						}
						?> 
					</tbody>
                </table>
			</div>
		</div>
	</div>
</div>
<?php
}
catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
    print($result);
}
?>