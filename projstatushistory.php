<?php				
				if($projectStatus =="Cancelled" || $projectStatus =="On Hold"){	
				?>
					<table width="98%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="100%" height="40" align="center">
								<div align="center" class="topbutton" style="width:100%">
									<a href="myepdash?projid=<?php echo $row_rsMyP['projid']; ?>">Evaluate Project</a>
								</div>
							</td>
						</tr>
					</table>
				 
					<table width="98%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="98%" height="40" align="center">
								<button type="button" class="btn btn-secondary btn-lg btn-block" href="myprojectstatushistory?projid=<?php echo $row_rsMyP['projid']; ?>">VIEW PROJECT STATUS HISTORY</button>
							</td>
						</tr>
					</table>
						<!-- Modal -->
					
				<?php
				}	
				elseif($projectStatus == "Completed" && (!empty($projectprevstatus) || $projectprevstatus!=='')) {

				?>
					<table width="98%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="100%" height="40" align="center">
								<div align="center" class="topbutton" style="width:90%">
									<a href="myepdash?projid=<?php echo $row_rsMyP['projid']; ?>">Evaluate Project</a>
								</div>
							</td>
						</tr>
					</table>
					<table width="98%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="80%" height="40" align="center">
							<a href="myprojectstatushistory?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn btn-primary btn-lg btn-block" role="button" aria-pressed="true">VIEW PROJECT STATUS HISTORY</a>
							</td>
						</tr>
					</table>
				<?php
				}
				elseif($projectStatus == "Completed" && (empty($projectprevstatus) || $projectprevstatus=='')) {	?>
					<table width="98%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="50%" height="40" align="center">
								<div align="center" class="gtopbutton" style="width:90%">Monitor Project</div>
							</td>
							<td width="50%" height="40" align="center">
								<div align="center" class="topbutton" style="width:90%">
									<a href="myepdash?projid=<?php echo $row_rsMyP['projid']; ?>">Evaluate Project</a>
								</div>
							</td>
						</tr>
					</table>	
				<?php
				}					
				elseif($projectStatus == $projectprevstatus){ 
				?>
					<table width="98%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="50%" height="40" align="center">
								<div align="center" class="topbutton" style="width:90%">
									<a href="addmilestone?projid=<?php echo $row_rsMyP['projid']; ?>">Add Milestone</a>
								</div>
							</td>
							<td width="50%" height="40" align="center">
								<div align="center" class="topbutton" style="width:90%">
									<a href="myprojectmilestones?projid=<?php echo $row_rsMyP['projid']; ?>">Add Task</a>
								</div>
							</td>
						</tr>
					</table>
					<table width="98%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="50%" height="40" align="center">
								<div align="center" class="topbutton" style="width:90%">
									<a href="monitorprojdash?projid=<?php echo $row_rsMyP['projid']; ?>">Monitor Project</a>
								</div>
							</td>
							<td width="50%" height="40" align="center">
								<div align="center" class="topbutton" style="width:90%">
									<a href="myepdash?projid=<?php echo $row_rsMyP['projid']; ?>">Evaluate Project</a>
								</div>
							</td>
						</tr>
					</table>
					<table width="98%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="80%" height="40" align="center">
							<a href="myprojectstatushistory?projid=<?php echo $row_rsMyP['projid']; ?>" class="btn btn-primary btn-lg btn-block" role="button" aria-pressed="true">VIEW PROJECT STATUS HISTORY</a>
							</td>
						</tr>
					</table>
				<?php
				}				
				else{
				?>
					<table width="98%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="50%" height="40" align="center">
								<div align="center" class="topbutton" style="width:90%">
									<a href="addmilestone?projid=<?php echo $row_rsMyP['projid']; ?>">Add Milestone</a>
								</div>
							</td>
							<td width="50%" height="40" align="center">
								<div align="center" class="topbutton" style="width:90%">
									<a href="myprojectmilestones?projid=<?php echo $row_rsMyP['projid']; ?>">Add Task</a>
								</div>
							</td>
						</tr>
					</table>
					<br />
					<table width="98%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="50%" height="40" align="center">
								<div align="center" class="topbutton" style="width:90%">
									<a href="monitorprojdash?projid=<?php echo $row_rsMyP['projid']; ?>">Monitor Project</a>
								</div>
							</td>
							<td width="50%" height="40" align="center">
								<div align="center" class="topbutton" style="width:90%">
									<a href="myepdash?projid=<?php echo $row_rsMyP['projid']; ?>">Evaluate Project</a>
								</div>
							</td>
						</tr>
					</table>
			  
				<?php
				}