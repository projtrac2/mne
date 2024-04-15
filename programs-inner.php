 <!--<div class="clearfix m-b-20">
    <div class="content" style="margin-top:-10px">-->
<?php 
try {
	//code...

?>
<div class="body">
    <div class="table-responsive">
		<ul class="nav nav-tabs" style="font-size:14px">
			<li class="active">
				&nbsp;
			</li>
		</ul>
		<div class="tab-content">
			<div id="home" class="tab-pane fade in active"> 
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <tr class="bg-light-green">
                            <th style="width:3%">#</th>
                            <th style="width:25%">Program Name</th>
                            <th style="width:20%">Program Outcome</th>
                            <th style="width:10%"><?=$ministrylabel?></th>
                            <th style="width:10%">Budget</th>
                            <th style="width:8%">Project(s)</th>
							<th style="width:8%">Funder(s)</th>
							<th style="width:10%">Date Created</th>
                            <th style="width:6%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php
						$nm = 0;
						while($row_programs = $query_programs->fetch())
						{ 
							$nm = $nm + 1;
							$progid = $row_programs['progid'];
							$progname = $row_programs['progname'];
							$output = strip_tags($row_programs['description']);
							$sector = $row_programs['projsector'];
							$dept = $row_programs['projdept'];
							$projects = $row_programs['projects'];
							$funders = $row_programs['funders'];
							
							$startdate = date("d M Y",strtotime($row_programs['datecreated']));

										
							
							$query_sector = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid = '$sector'");
							$query_sector->execute();
							$row_sector = $query_sector->fetch();
							$progsector = $row_sector["sector"];
							
							$query_dept = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid = '$dept'");
							$query_dept->execute();
							$row_dept = $query_dept->fetch();
							$department = $row_dept["sector"];
							
							$query_projs = $db->prepare("SELECT projid FROM tbl_projects WHERE progid = '$progid'");
							$query_projs->execute();
							$count_projs = $query_projs->rowCount();
							
							$query_fundsource = $db->prepare("SELECT * FROM tbl_myprojfunding WHERE progid = '$progid'");
							$query_fundsource->execute();
							$count_fundsource = $query_fundsource->rowCount();
							
										
							?>
							<tr>
								<td align="center"><?php echo $nm; ?></td>
								<td><?php echo $progname; ?></td>
								<td><p data-toggle="tooltip" data-placement="bottom" title="<?php echo $output; ?>"><?php echo substr($output,0,22); ?>...</p></td>
								<td><?php echo $progsector; ?></td>
								<td><?php echo number_format($row_programs['budget'], 2); ?></td>
								<td align="center"><a href="projects?prog=<?php echo $progid; ?>"><span class="badge bg-purple"><?php echo $count_projs; ?></span></a></td>
								<td align="center"><span class="badge bg-green" data-toggle="tooltip" data-placement="bottom" title="<?php 
									$sr = 0;
									echo "Funder"; if($count_fundsource > 1){ echo "s";} echo": ";
									while($row_fundsource = $query_fundsource->fetch())
									{ 
										$fdid = $row_fundsource["sourceid"];
										$scat = $row_fundsource["sourcecategory"];
										if($scat=="donor"){
											$query_funders = $db->prepare("SELECT donorname FROM tbl_donors WHERE dnid = '$fdid'");
											$query_funders->execute();
											$row_funders = $query_funders->fetch();
											$funder = $row_funders["donorname"];
										}else{
											$query_funders = $db->prepare("SELECT name FROM tbl_funder WHERE id = '$fdid'");
											$query_funders->execute();
											$row_funders = $query_funders->fetch();
											$funder = $row_funders["name"];
										}
										$sr = $sr + 1; 
										echo $funder; 
										if($count_fundsource > 1){ echo "; ";}
									}
									?>"><?php echo $count_fundsource; ?></span></td>
								<td><?php echo $startdate; ?></td>
								<td>
									<div align="center"><a href="editprogram?prog=<?php echo $progid; ?>"  style="font-size:20px" data-toggle="tooltip" data-placement="bottom" title="Edit Program Details"><i class="fa fa-edit text-warning" aria-hidden="true"></i></a>&nbsp;&nbsp;<a href="programs?action=2&prog=<?php echo $progid; ?>" style="font-size:20px" onclick="return confirm('Are you sure you want to delete this record?')" id="view" data-toggle="tooltip" data-placement="bottom" title="Delete Program"><i class="fa fa-window-close text-danger" aria-hidden="true"></i></a></div>
								</td>
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

} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());

}

?>