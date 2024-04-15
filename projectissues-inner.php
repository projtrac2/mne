<?php 
try {
	//code...

?>
<div class="body">
    <div class="table-responsive">
		<div class="tab-content">
			<div id="home" class="tab-pane fade in active">  
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <tr class="bg-brown">
                            <th style="width:3%">#</th>
                            <th style="width:40%">Project Name</th>
                            <th style="width:25%">Project Location</th>
							<th style="width:15%">Project Status</th>
							<?php 
							if($issuesno == $analysedissues){
							?>
								<th style="width:10%">Risk Level</th>
							<?php 
							}
							?>
                            <th style="width:7%">Issues</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php
						$nm = 0;
						while($row_issues = $query_issues->fetch())
						{ 
							$nm = $nm + 1;
							$id = $row_issues['id'];
							$projid = $row_issues['projid'];
							$project = $row_issues['projname'];
							$projstatus = $row_issues['projstatus'];
							$subcounty = $row_issues['projcommunity'];
							$ward = $row_issues['projlga'];
							$loc = $row_issues['projstate'];
							$issuesno = $row_issues['issues'];
							$recommendation = $row_issues['recommendation'];
							$issuedate = $row_issues['issuedate'];
							
							$query_analysedissues = $db->prepare("SELECT count(id) AS analysedissues FROM tbl_projissues WHERE projid='$projid' AND status=1");
							$query_analysedissues->execute();	
							$count_analysedissues = $query_analysedissues->fetch();
							$analysedissues = $count_analysedissues["analysedissues"];
							
							/* $query_projriskscore = $db->prepare("SELECT count(scid) AS numanalysed, sum(score) AS sumscore FROM tbl_project_riskscore WHERE projid='$projid'");
							$query_projriskscore->execute();	
							$row_projriskscore = $query_projriskscore->fetch();
							//$count_projriskscore = $query_projriskscore->rowCount();

							$sumscore = $row_projriskscore["sumscore"];
							$number = $row_projriskscore["numanalysed"];
							
							if($issuesno == $analysedissues){
								$query_projriskscore = $db->prepare("SELECT MAX(score) AS maxscore FROM tbl_project_riskscore WHERE projid='$projid'");
								$query_projriskscore->execute();	
								$row_projriskscore = $query_projriskscore->fetch();
								$maxiscore = $row_projriskscore["maxscore"];
								if($maxiscore == 1){
									$level = "Negligible";
									$style = 'style="background-color:#4CAF50; color:#fff"';
								}elseif($maxiscore == 2){
									$level = "Minor";
									$style = 'style="background-color:#CDDC39; color:#fff"';
								}elseif($maxiscore == 3){
									$level = "Moderate";
									$style = 'style="background-color:#FFEB3B; color:#000"';
								}elseif($maxiscore == 4){
									$level = "Significant";
									$style = 'style="background-color:#FF9800; color:#fff"';
								}elseif($maxiscore == 5){
									$level = "Severe";
									$style = 'style="background-color:#F44336; color:#fff"';
								}
							}else{
								$level = "Not Analysed";
								$style = 'style="background-color:#9E9E9E; color:#fff"';
							} 
							*/
							$query_projstatus = $db->prepare("SELECT projstatus, projchangedstatus FROM tbl_projects WHERE projid='$projid'");
							$query_projstatus->execute();	
							$row_projstatus = $query_projstatus->fetch();
							$currentprojstatus = $row_projstatus["projstatus"];
							$projchangedstatus = $row_projstatus["projchangedstatus"];
							
							if(empty($projchangedstatus) || $projchangedstatus == '' || $currentprojstatus == 'On Hold'){
								if($issuesno == $analysedissues){
									$query_projriskscore = $db->prepare("SELECT MAX(score) AS maxscore FROM tbl_project_riskscore WHERE projid='$projid'");
									$query_projriskscore->execute();	
									$row_projriskscore = $query_projriskscore->fetch();
									$maxiscore = $row_projriskscore["maxscore"];
									if($maxiscore == 1){
										$level = "Negligible";
										$style = 'style="background-color:#4CAF50; color:#fff"';
									}elseif($maxiscore == 2){
										$level = "Minor";
										$style = 'style="background-color:#CDDC39; color:#fff"';
									}elseif($maxiscore == 3){
										$level = "Moderate";
										$style = 'style="background-color:#FFEB3B; color:#000"';
									}elseif($maxiscore == 4){
										$level = "Significant";
										$style = 'style="background-color:#FF9800; color:#fff"';
									}elseif($maxiscore == 5){
										$level = "Severe";
										$style = 'style="background-color:#F44336; color:#fff"';
									}
								}else{
									$level = "Not Analysed";
									$style = 'style="background-color:#9E9E9E; color:#fff"';
								}
							}elseif($currentprojstatus == $projchangedstatus || $currentprojstatus == "Cancelled"){
									$level = "Addressed";
									$style = 'style="background-color:#795548; color:#fff"';
							}								
							
							$query_subcounty = $db->prepare("SELECT state FROM tbl_state WHERE id='$subcounty'");
							$query_subcounty->execute();	
							$count_subcounty = $query_subcounty->fetch();
							$subcounty = $count_subcounty["state"];
							
							$query_ward = $db->prepare("SELECT state FROM tbl_state WHERE id='$ward'");
							$query_ward->execute();	
							$count_ward = $query_ward->fetch();
							$ward = $count_ward["state"];
							$loc = $count_loc["state"];
							
							$query_loc = $db->prepare("SELECT state FROM tbl_state WHERE id='$loc'");
							$query_loc->execute();	
							$count_loc = $query_loc->fetch();
							
							//$location = $count_subcounty["state"]." Sub-County; ".$count_ward["state"]." Ward; ".$count_loc["state"]." Location";

							if($subcounty=="All"){
								$location = $subcounty." ".$level1labelplural."; ".$ward." ".$level2labelplural."; ".$loc." ".$level3labelplural;
							}else{
								$location = $subcounty." ".$level1label."; ".$ward." ".$level2label."; ".$loc." ".$level3label;
							}
							?>
							<tr style="background-color:#eff9ca">
								<td align="center"><?php echo $nm; ?></td>
								<td><?php echo $project; ?></td>
								<td><?php echo $location; ?></td>
								<td><?php echo $projstatus; ?></td>
								<td <?php echo $style; ?> align="center"><?php echo $level; ?></td>
								<td align="center">
									<a href="projectissueslist?projid=<?php echo $projid; ?>"><span class="badge bg-purple"><?php echo $issuesno; ?></a></span></a>
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