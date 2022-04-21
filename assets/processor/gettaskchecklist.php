<?php
include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';
include_once("../../system-labels.php");

if(isset($_POST['tskid'])) {
	$tkid = $_POST["tskid"];
	$frmid = $_POST["pmtid"];
	$lev3id = $_POST["lev3id"];
	$lev4id = '';
	if(isset($_POST['lev4id'])){
		$lev4id = $_POST["lev4id"];
	}


	$query_rslevel3 = $db->prepare("SELECT * FROM tbl_state WHERE id = :lev3id");
	$query_rslevel3->execute(array(":lev3id" => $lev3id));
	$row_rslevel3 = $query_rslevel3->fetch();
	$totalRows_rslevel3 = $query_rslevel3->rowCount();
	$level3 = $row_rslevel3['state'];
	$locate ='';

	if($lev4id != ''){
		$query_rslevel4 = $db->prepare("SELECT * FROM tbl_indicator_level3_disaggregations WHERE id = :lev4id");
		$query_rslevel4->execute(array(":lev4id" => $lev4id));
		$row_rslevel4 = $query_rslevel4->fetch();
		$totalRows_rslevel4 = $query_rslevel4->rowCount();
		$level4 = $row_rslevel4['disaggregations'];
		$locate ='
		<div class="col-md-6 clearfix" style="margin-top:5px; margin-bottom:5px">
			<h5 style="color:#2B982B"><strong> Location: '. $level4.' </strong></h5>
			<input type="hidden" name="lev4id" id="lev4id" value="'.$lev4id.'"/> 
		</div>';
	}
  

	//var_dump($tkid." ".$frmid);
	//$progress = $_POST["scprog"];
	$query_checklist = $db->prepare("SELECT ckid, taskid, name FROM tbl_project_monitoring_checklist WHERE taskid=:tkid");
	$query_checklist->execute(array(':tkid'=>$tkid));
	$row = $query_checklist->fetch();
	$totalRows_rsChk = $query_checklist->rowCount();
	

	if($totalRows_rsChk > 0){
		$num = 0;
		echo '
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
				<div class="header">
					<div class="col-md-6" style="margin-top:5px; margin-bottom:5px">
						<h5 style="color:#2B982B"><strong> '.$level3label.': '. $level3 .'</strong></h5>
						<input type="hidden" name="lev3id" id="lev3id" value="'.$lev3id.'"/> 
					</div>
					'.$locate.'
				</div>

				<div class="body">
					<div class="row clearfix"> 
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover dataTable js-exportable">
									<thead>
										<tr style="background-color:#607D8B; color:#FFF">
											<td width="3%">SN</td>
											<td width="80%">Checklist</td>
											<td width="17%">Progress Score</td>
										</tr>
									</thead>
									<tbody>
										<input type="hidden" name="tskscid" id="tskscid" value="'.$row['taskid'].'"/>';
										do{
											$checklistid = $row['ckid'];
											$query_checkform = $db->prepare("SELECT * FROM tbl_project_monitoring_checklist_score WHERE checklistid=:checklistid and taskid=:tkid and formid=:frmid ");
											 
											$query_checkform->execute(array(":checklistid"=>$checklistid,':tkid'=>$tkid,':frmid'=>$frmid));
											$totalRows_checkform = $query_checkform->rowCount();

											if($totalRows_checkform > 0){
												$query_checklistscore = $db->prepare("SELECT MAX(score) as score FROM tbl_project_monitoring_checklist_score WHERE checklistid=:checklistid and taskid=:tkid and formid<>:frmid");
												$query_checklistscore->execute(array(":checklistid"=>$checklistid,':tkid'=>$tkid,':frmid'=>$frmid));
												$row_checklistscore = $query_checklistscore->fetch();
												$totalRows_checklistscore = $query_checklistscore->rowCount();
												$total_checklistscore = $row_checklistscore["score"];
												
												$scoreoptions = ''; 
												do{
													$query_editchecklistscore = $db->prepare("SELECT score FROM tbl_project_monitoring_checklist_score WHERE checklistid='$checklistid' and taskid='$tkid' and formid = '$frmid' ");
													$query_editchecklistscore->execute();
													$row_editchecklistscore = $query_editchecklistscore->fetch(); 
													$totalRows_editchecklistscore = $query_editchecklistscore->rowCount();
													
													if(is_null($row_checklistscore["score"])){
														$max_checklistscore = 0;
													} else {
														$max_checklistscore = $row_checklistscore["score"];
													}

													for($i = $max_checklistscore; $i <= 10; $i++){
														if($row_editchecklistscore["score"] == $i){
															$scoreoptions .= '<option value="'.$row_editchecklistscore["score"].'" selected="selected" class="selection">'.$row_editchecklistscore["score"].'</option>';
														} else{
															$scoreoptions .= '<option value="'.$i.'">'.$i.'</option>';
														}
													}
												}while($row_checklistscore = $query_checklistscore->fetch());

												if($total_checklistscore < 10){
													$num = $num + 1;
													echo '<tr id="rowlines">
														<td>'.$num.'</td>
														<td>'.$row['name'].'</td>
														<td align="center">
															<div align="center">									
																<div class="form-line" align="center">
																	<select name="scores'.$row['ckid'].'" id="md_checkbox1_'.$row['ckid'].'" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" align="center" required>
																		'.$scoreoptions.'
																	</select>
																</div>
															</div>
														</td>
														<input type="hidden" name="ckids[]" id="checklistid" value="'.$row['ckid'].'"/>
														<input type="hidden" name="frmid[]" id="checklistid" value="'.$frmid.'"/>
													</tr>';
												}else {	
													$query_finalchecklistscore = $db->prepare("SELECT MAX(score) as score FROM tbl_project_monitoring_checklist_score WHERE checklistid='$checklistid' and taskid='$tkid' and formid <> '$frmid'");
													$query_finalchecklistscore->execute();	
													$row_finalchecklistscore = $query_finalchecklistscore->fetch();
													$totalRows_finalchecklistscore = $query_finalchecklistscore->rowCount();
													$num = $num + 1;
													echo '<tr id="rowlines">
														<td>'.$num.'</td>
														<td>'.$row['name'].'</td>
														<td align="center">
															<div align="center">									
																<div class="form-line" align="center">
																	<input type="text" name="scores'.$row['ckid'].'" id="md_checkbox1_'.$row['ckid'].'" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" value="'.$row_finalchecklistscore["score"].'" align="center" readonly>
																</div>
															</div>
														</td>
														<input type="hidden" name="ckids[]" id="checklistid" value="'.$row['ckid'].'"/>
														<input type="hidden" name="frmid[]" id="checklistid" value="'.$frmid.'"/>
													</tr>';
												}
											} else {
												$query_checklistscore = $db->prepare("SELECT MAX(score) as score FROM tbl_project_monitoring_checklist_score WHERE checklistid='$checklistid' and taskid='$tkid' and formid <> '$frmid'");
												$query_checklistscore->execute();
												$row_checklistscore = $query_checklistscore->fetch();
												$totalRows_checklistscore = $query_checklistscore->rowCount(); 
												$total_checklistscore = $row_checklistscore["score"]; 
												$scoreoptions = '';

												// do{
													if(is_null($row_checklistscore["score"])){
														$max_checklistscore = 0;
													} else {
														$max_checklistscore = $row_checklistscore["score"] ;
													}
													
													$scoreoptions .= '<option value="" selected="selected" class="selection">Add Score</option>';
													for($i = $max_checklistscore; $i <= 10; $i++){
														$scoreoptions .= '<option value="'.$i.'">'.$i.'</option>';
													} 

												if($total_checklistscore < 10){
													$num = $num + 1;
													echo '<tr id="rowlines">
														<td>'.$num.'</td>
														<td>'.$row['name'].'</td>
														<td align="center">
															<div align="center">									
																<div class="form-line" align="center">
																	<select name="scores'.$row['ckid'].'" id="md_checkbox1_'.$row['ckid'].'" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" align="center" required>
																		'.$scoreoptions.'
																	</select>
																</div>
															</div>
														</td>
														<input type="hidden" name="ckids[]" id="checklistid" value="'.$row['ckid'].'"/>
														<input type="hidden" name="frmid[]" id="checklistid" value="'.$frmid.'"/>
													</tr>';
												}
												else {	
													$query_finalchecklistscore = $db->prepare("SELECT MAX(score) as score FROM tbl_project_monitoring_checklist_score WHERE checklistid='$checklistid' and taskid='$tkid' and formid <> '$frmid'");
													$query_finalchecklistscore->execute();	
													$row_finalchecklistscore = $query_finalchecklistscore->fetch();
													$totalRows_finalchecklistscore = $query_finalchecklistscore->rowCount();

													$num = $num + 1;
													echo '<tr id="rowlines">
														<td>'.$num.'</td>
														<td>'.$row['name'].'</td>
														<td align="center">
															<div align="center">									
																<div class="form-line" align="center">
																	<input type="text" name="scores'.$row['ckid'].'" id="md_checkbox1_'.$row['ckid'].'" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" value="'.$row_finalchecklistscore["score"].'" align="center" readonly>
																</div>
															</div>
														</td>
														<input type="hidden" name="ckids[]" id="checklistid" value="'.$row['ckid'].'"/>
														<input type="hidden" name="frmid[]" id="checklistid" value="'.$frmid.'"/>
													</tr>';
												}
											}
										}while($row = $query_checklist->fetch());
									echo '</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>';
									}else{
	echo '<div class="row clearfix">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
				<div class="body">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover dataTable js-exportable">
							<thead>
								<tr id="colrow">
									<td width="3%">SN</td>
									<td width="75%">Checklist</td>
									<td width="22%">Score</td>
								</tr>
							</thead>
							<tbody>
								<tr id="rowlines">
									<td colspan="3" style="color:RED" align="center">NO CHECKLIST DEFINED!!!</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>';
	}
}

if(isset($_POST['delete'])){
	$formid = $_POST['formid'];
	$deleteQuery = $db->prepare("DELETE FROM `tbl_project_monitoring_checklist_score` WHERE formid=:formid");
	$results = $deleteQuery->execute(array(':formid' => $formid));
}


?>