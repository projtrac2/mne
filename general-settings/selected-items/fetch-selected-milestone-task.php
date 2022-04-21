<?php

include_once "controller.php";

if (isset($_POST['milestones'])) {
	$mileid = $_POST['itemId'];
	// order milestones by start date 
	$query_rsMilestones = $db->prepare("SELECT * FROM tbl_milestone WHERE msid = :msid");
	$query_rsMilestones->execute(array(":msid" => $mileid));
	$row_rsMilestones = $query_rsMilestones->fetch();
	$totalRows_rsMilestones = $query_rsMilestones->rowCount();

	$parentid =  $row_rsMilestones['parent'];
	$parent = "";
	if ($parentid != null) {
		$query_rsMilestoneParent = $db->prepare("SELECT * FROM tbl_milestone WHERE msid = :msid");
		$query_rsMilestoneParent->execute(array(":msid" => $parentid));
		$row_rsMilestoneParent = $query_rsMilestoneParent->fetch();
		$totalRows_rsMilestoneParent = $query_rsMilestoneParent->rowCount();
		$parent = $row_rsMilestoneParent['milestone'];
	} else {
		$parent = "N/A";
	}

	$checked = $row_rsMilestones['paymentrequired']  == 1 ? "Yes" : "No";

	$Mile = '  
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card"> 
				<div class="body">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover" id="milestoneTable" style="width:100%">
							<thead>
								<tr>
									<th width="4%">#</th> 
									<th width="38%">Milestone</th>  
									<th width="38%">Predecessor</th>  
									<th width="10%">Start Date </th> 
									<th width="10%">End Date </th>
									<th width="10%">Payment </th>
								</tr>
							</thead>
							<tbody id="funding_table_body" >';
								$Mcounter = 0;
								do {
									$Mcounter++;
									$Mile .= '
									<tr>
										<td >' . $Mcounter  . ' </td>
										<td >' . $row_rsMilestones['milestone']  . ' </td> 
										<td> ' . $parent . '</td>
										<td> ' . date("d M Y", strtotime($row_rsMilestones['sdate']))  . '</td>
										<td>' . date("d M Y", strtotime($row_rsMilestones['edate']))  . ' </td>
										<td>' . $checked . ' </td>
									</tr>';
								} while ($row_rsMilestones = $query_rsMilestones->fetch());
								$Mile .= '
							</tbody>
						</table> 
					</div>
				</div>
			</div>
		</div>
	</div>';


	$query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE parent = :parent");
	$query_rsMilestone->execute(array(":parent" => $mileid));
	$row_rsMilestone = $query_rsMilestone->fetch();
	$totalRows_rsMilestone = $query_rsMilestone->rowCount();

	if ($totalRows_rsMilestone > 0) {
		$Mile .= '  
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card"> 
					<div class="header">
						<div class=" clearfix" style="margin-top:5px; margin-bottom:5px; font-size:16px">
							<strong>Successors</strong>
						</div>
					</div>
					<div class="body">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover" id="milestoneTable" style="width:100%">
								<thead>
									<tr>
										<th width="5%">#</th> 
										<th width="75%">Milestone</th>   
										<th width="10%">Start Date </th> 
										<th width="10%">End Date </th> 
									</tr>
								</thead>
								<tbody id="funding_table_body" >';
		$Ccounter = 0;
		do {
			$Ccounter++;
			$Mile .= ' 
										<tr>  
											<td >' . $Ccounter  . ' </td>
											<td >' . $row_rsMilestone['milestone']  . ' </td>  
											<td> ' . date("d M Y", strtotime($row_rsMilestone['sdate']))  . '</td>
											<td>' . date("d M Y", strtotime($row_rsMilestone['edate']))  . ' </td>
										</tr>';
		} while ($row_rsMilestone = $query_rsMilestone->fetch());
		$Mile .= '  
								</tbody>
							</table> 
						</div>
					</div>
				</div>
			</div>
		</div>';
	}
	echo $Mile;
}

if (isset($_POST['tasks'])) {
	$taskid = $_POST['itemId'];
	$query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE tkid = :tkid");
	$query_rsTasks->execute(array(":tkid" => $taskid));
	$row_rsTasks = $query_rsTasks->fetch();
	$totalRows_rsTasks = $query_rsTasks->rowCount();

	$responsibleid = $row_rsTasks['responsible'];
	$query_rsResponsible = $db->prepare("SELECT * FROM tbl_projteam2 WHERE ptid = :ptid");
	$query_rsResponsible->execute(array(":ptid" => $responsibleid));
	$row_rsResponsible = $query_rsResponsible->fetch();
	$totalRows_rsResponsible = $query_rsResponsible->rowCount();

	$responsible = $row_rsResponsible['fullname'];
	$parentid = $row_rsTasks['parenttask'];
	$parent = "";
	if ($parentid != null) {
		$query_rsTaskid = $db->prepare("SELECT * FROM tbl_task WHERE tkid = :tkid");
		$query_rsTaskid->execute(array(":tkid" => $parentid));
		$row_rsTaskid = $query_rsTaskid->fetch();
		$parent = $row_rsTaskid['task'];
	} else {
		$parent = "N/A";
	}

	if ($totalRows_rsTasks > 0) {
		$Task = '  
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card"> 
					<div class="body">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover" id="Task_table" style="width:100%">
								<thead>
									<tr>
										<th width="2%">#</th> 
										<th width="34%">Task</th> 
										<th width="34%">Predecessor</th>  
										<th width="10%">Start Date</th> 
										<th width="10%">End Date </th> 
										<th width="10%">Responsible</th> 
									</tr>
								</thead>
								<tbody id="task_table_body" >';
		$Tcounter = 0;
		do {
			$Tcounter++;
			$Task .= ' 
										<tr>  
											<td >' . $Tcounter  . ' </td>
											<td >' . $row_rsTasks['task']  . ' </td>
											<td >' . $parent . ' </td>
											<td>' . date("d M Y", strtotime($row_rsTasks['sdate']))  . ' </td>
											<td>' . date("d M Y", strtotime($row_rsTasks['edate']))  . ' </td>
											<td>' . $responsible  . ' </td>
										</tr>';
		} while ($row_rsTasks = $query_rsTasks->fetch());
		$Task .= '  
								</tbody>
							</table> 
						</div>
					</div>
				</div>
			</div>
		</div>';

		$query_rsTask = $db->prepare("SELECT * FROM tbl_task WHERE parenttask = :parent");
		$query_rsTask->execute(array(":parent" => $taskid));
		$row_rsTask = $query_rsTask->fetch();
		$totalRows_rsTask = $query_rsTask->rowCount();

		if ($totalRows_rsTask > 0) {
			$Task .= '  
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card"> 
						<div class="header">
							<div class=" clearfix" style="margin-top:5px; margin-bottom:5px; font-size:16px">
							<strong>Successors</strong>
							</div>
						</div>
						<div class="body">
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover" id="Task_table" style="width:100%">
									<thead>
										<tr>
											<th width="5%">#</th> 
											<th width="60%">Task</th>  
											<th width="10%">Start Date</th> 
											<th width="10%">End Date </th> 
											<th width="15%">Responsible</th> 
										</tr>
									</thead>
									<tbody id="task_table_body" >';
			$Pcounter = 0;
			do {
				$Pcounter++;
				$responsibleid = $row_rsTask['responsible'];
				$query_rsResponsible = $db->prepare("SELECT * FROM tbl_projteam2 WHERE ptid = :ptid");
				$query_rsResponsible->execute(array(":ptid" => $responsibleid));
				$row_rsResponsible = $query_rsResponsible->fetch();
				$totalRows_rsResponsible = $query_rsResponsible->rowCount();
				$responsible  = $row_rsResponsible['fullname'];
				$Task .= ' 
											<tr>  
												<td>' . $Pcounter  . ' </td>
												<td>' . $row_rsTask['task']  . ' </td> 
												<td>' . date("d M Y", strtotime($row_rsTask['sdate']))  . ' </td>
												<td>' . date("d M Y", strtotime($row_rsTask['edate']))  . ' </td>
												<td>' . $responsible  . ' </td>
											</tr>';
			} while ($row_rsTask = $query_rsTask->fetch());
			$Task .= '  
									</tbody>
								</table> 
							</div>
						</div>
					</div>
				</div>
			</div>';
		}
	}
	echo $Task;
}

if (isset($_POST['getMilestoneForm'])) {
	$projid = $_POST['projid'];
	$outputid = $_POST['outputid'];
	//var_dump($outputid);
	$query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid = :projid");
	$query_rsProjects->execute(array(":projid" => $projid));
	$row_rsProjects = $query_rsProjects->fetch();

	$query_rsOutputs = $db->prepare("SELECT * FROM tbl_project_details WHERE id = :outputid and projid = :projid");
	$query_rsOutputs->execute(array(":outputid" => $outputid, ":projid" => $projid));
	$row_rsOutputs = $query_rsOutputs->fetch();
	$opduration = $row_rsOutputs['duration'];
	$yearid = $row_rsOutputs['year'];
	$output_id = $row_rsOutputs['outputid'];


	$query_rsProgramOutputs = $db->prepare("SELECT * FROM tbl_progdetails WHERE id = :outputid");
	$query_rsProgramOutputs->execute(array(":outputid" => $output_id));
	$row_rsProgramOutputs = $query_rsProgramOutputs->fetch();
	$output = $row_rsProgramOutputs['output'];


	$query_rsFscYear = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE id = :yrid");
	$query_rsFscYear->execute(array(":yrid" => $yearid));
	$row_rsFscYear = $query_rsFscYear->fetch();
	$opyear = $row_rsFscYear['yr'];
	$startDate  = date("d-m-Y", strtotime("01-07-" . $opyear));
	$endDate  = date("d-m-Y", strtotime($startDate . ' + ' . $opduration . ' days'));

	$opstart = date("m-d-Y", strtotime($startDate));
	$opend = date("m-d-Y", strtotime($endDate));

	$projsdate = date("d-m-Y", strtotime($row_rsProjects['projstartdate']));
	$projedate = date("d-m-Y", strtotime($row_rsProjects['projenddate']));

	$query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE projid = :projid and outputid = :outputid");
	$query_rsMilestone->execute(array(":projid" => $projid, ":outputid" => $outputid));
	$row_rsMilestone = $query_rsMilestone->fetch();
	$parentOptions =  '';

	do {
		$parentOptions .=  '<option value="' . $row_rsMilestone['msid'] . '">' . $row_rsMilestone['milestone'] . '</option>';
	} while ($row_rsMilestone = $query_rsMilestone->fetch());

	echo '<fieldset class="scheduler-border">
		<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">MILESTONE DETAILS</legend>
		<input type="hidden"  id="projid" class="form-control" value="' . $projid . '" >
		<input type="hidden"  id="outputid" class="form-control" value="' . $outputid . '" >
		<div class="col-md-12">
			<label>Output:</label>
			<div>
				<input type="text" id="output_name" class="form-control" value="' . $output . '" style="border:#CCC thin solid; border-radius: 5px" readonly>
			</div>
		</div>
		<div class="col-md-6">
			<label>Output Start Date:</label>
			<div>
				<input type="text" id="opstart" class="form-control" value="' . $opstart . '" style="border:#CCC thin solid; border-radius: 5px" readonly>
			</div>
		</div>
		<div class="col-md-6">
			<label>Output End Date:</label>
			<div>
				<input type="text"  id="opend" class="form-control" value="' . $opend . '" style="border:#CCC thin solid; border-radius: 5px" readonly>
			</div>
		</div>
		<div class="col-md-12">
			<label>Milestone Name *:</label> 
			<div class="form-input">
				<input type="text" name="milestone" id="milestone" placeholder="Milestone Name"  class="form-control" style="border:#CCC thin solid; border-radius: 5px" required>
				<span id="milestonemsg" style="color:red"></span> 
			</div> 
		</div>  
		<div class="col-md-6">
			<label class="control-label"> Predecessor :</label>
			<div class="form-line">
				<select name="milestoneParent" id="milestoneParent" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" >
					<option value="">.... Select ....</option> 
						' . $parentOptions . '
				</select>
				<script>
				$("#milestone").blur(function (e) { 
					var milestone = $(this).val();
					if(milestone){ 
						$("#milestonemsg").html("");
					}else{
						$("#milestonemsg").html("Field Required"); 
					}
				});

				$("#sdate").change(function (e) { 
					e.preventDefault();
					var mstart = new Date($(this).val()).setHours(0,0,0,0);
					var opstart = new Date($("#opstart").val()).setHours(0,0,0,0);
					var opend = new Date($("#opend").val()).setHours(0,0,0,0);  
					var parent = new Date($("#parentdate").val()).setHours(0,0,0,0);

					if(mstart < opstart){
						$("#sdatemsg").html("Milestone start date should be greater or equal to output start date"); 
						$("#sdate").val("");
						$("#edate").val("");
					}else if(mstart > opend){
						$("#sdatemsg").html("Milestone start date should be less or equal to output end date"); 
						$("#edate").val("");
						$("#sdate").val("");
					}else{
						if(parent !=""){
							if(mstart < parent){
								 $("#sdatemsg").html("Milestone start date should be  greater or equal to predecessor end date"); 
								$("#edate").val("");
								$("#sdate").val(""); 
							} else{
								$("#sdatemsg").html("");
							}
						}
					}
				});

				$("#edate").change(function (e) { 
					var mstart = new Date($("#sdate").val()).setHours(0,0,0,0); // milestone start date 
					var opend = new Date($("#opend").val()).setHours(0,0,0,0);  // output end date
					var mend = new Date($(this).val()).setHours(0,0,0,0); //milestone end date
						 
					if(mstart){
						if(mend < mstart ){
							$("#edatemsg").html("Milestone end date should be greater or equal to start date"); 
							$("#edate").val("");
						}else if(mend  > opend){
							$("#edatemsg").html("Milestone End date should be less or equal to output end date"); 
							$("#edate").val("");
						}else{
							$("#edatemsg").html("");
						}
					}else{
						$("#edate").val("");
						$("#edatemsg").html("Select Start Date"); 
					}
				});

				$("#milestoneParent").change(function(e) {
					e.preventDefault(); 
					var parent = $(this).val(); 
					$("#edate").val("");
					$("#sdate").val(""); 
					if (parent != "") { 
						$.ajax({
							type: "post",
							url: "general-settings/selected-items/fetch-selected-milestone-task",
							data: {
								getMilestoneParentStart: "parent",
								mileid: parent
							},
							dataType: "json",
							success: function(response) {
								html =
									\'<div>\' +
									"<label> Predecessor End Date *:</label>" +
									\'<div class="form-line">\' +
									\'<input name="parentdate" type="date" id="parentdate" class="form-control" value="\' +
									response +
									\'" placeholder="" disabled>\' +
									"</div>" +
									"</div>"; 
								$("#parentDate").html(html);
							}
						});
					}else{
						$("#parentDate").html("");
					}
				}); 

				$("#tag-form-submit").click(function (e) { 
					e.preventDefault(); 
					$("#tag-form-submit").attr("disabled", true);

					var milestone = $("#milestone").val();
					var parentid = $("#milestoneParent").val();  
					var startdate =$("#sdate").val();
					var enddate =$("#edate").val(); 
					var projid =$("#projid").val(); 
					var outputid =$("#outputid").val();
					var paymentrequired = $("#defaultUnchecked").is(":checked") ? 1 : 0;

					if(milestone){ 
						$("#milestonemsg").html("");
					}else{
						$("#milestonemsg").html("Field Required"); 
					}

					if(startdate){
						$("#sdatemsg").html("");   
					}else{ 
						$("#sdatemsg").html("Field Required");   
					}

					if(enddate){
						$("#sdatemsg").html("");   
					}else{ 
						$("#edatemsg").html("Field Required");   
					}

					if(milestone && startdate && enddate){
						$.ajax({
							type: "POST",
							url: "general-settings/selected-items/fetch-selected-milestone-task",
							data: {
								addMilestone: "addMilestone",
								milestone: milestone, 
								parentid: parentid,
								startdate:startdate,
								enddate:enddate,
								projid:projid,
								outputid:outputid,
								paymentrequired:paymentrequired,
								user_name:1
							},
							dataType: "json",
							success: function(response) { 
								if (response.success == true) { 
									alert(response.messages);
									$(".modal").each(function() {
										$(this).modal("hide");
									});
									location.reload(true);
								} else {
									alert(response.messages);
									location.reload(true);
								}
								$("#tag-form-submit").attr("disabled", false);
							}
						});
					}
				});
				</script>
			</div>
		</div> 
		<div  class="col-md-6">
				<div id="parentDate"></div>
		</div> 
		<div class="col-md-6">
			<label>Milestone Start Date *:</label>
			<div class="form-line">
				<input name="sdate" id="sdate" type="date" class="form-control" value="" placeholder="Start Date" required>
					<span id="sdatemsg" style="color:red"></span>
				</div>
		</div>
		<div class="col-md-6">
			<label>Milestone End Date *:</label>
			<div class="form-line">
				<input name="edate" id="edate" type="date" class="form-control" value="" placeholder="End Date" required>
				<span id="edatemsg" style="color:red"></span>
			</div>
		</div>
		<div class="col-md-12">
			<div class="custom-control custom-checkbox">
				<input name="paymentrequired"  type="checkbox" class="custom-control-input" value="1" id="defaultUnchecked">
				<label class="custom-control-label" for="defaultUnchecked">Payment to be made after this milestone?</label>
			</div>
		</div>
	</fieldset>';
}

if (isset($_POST['getMilestoneEditForm'])) {
	$mileid = $_POST['mileid'];
	$query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE msid = :msid");
	$query_rsMilestone->execute(array(":msid" => $mileid));
	$row_rsMilestone = $query_rsMilestone->fetch();

	$query_rsMilestones = $db->prepare("SELECT * FROM tbl_milestone WHERE parent = :parent");
	$query_rsMilestones->execute(array(":parent" => $mileid));
	$row_rsMilestones = $query_rsMilestones->fetch();
	$totalRows_rsMilestones = $query_rsMilestones->rowCount();
	$attribute_click = '';
	$Mile = '';
	if ($totalRows_rsMilestones > 0) {
		$attribute_click .= 'onclick=validate_dates(' . $mileid . ')';
		$Mile .= '  
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="header">
					<div class=" clearfix" style="margin-top:5px; margin-bottom:5px; font-size:16px">
							<strong>Successors</strong>
					</div>
				</div>
				<div class="body">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover" id="milestoneTable" style="width:100%">
							<thead>
								<tr>
									<th width="5%">#</th> 
									<th width="75%">Milestone</th>   
									<th width="10%">Start Date </th> 
									<th width="10%">End Date </th> 
								</tr>
							</thead>
							<tbody id="funding_table_body" >';
		$Ccounter = 0;
		do {
			$Ccounter++;
			$Mile .= ' 
									<tr>  
										<td >' . $Ccounter  . ' </td>
										<td >' . $row_rsMilestones['milestone']  . ' </td>  
										<td> ' . date("d M Y", strtotime($row_rsMilestones['sdate']))  . '</td>
										<td>' . date("d M Y", strtotime($row_rsMilestones['edate']))  . ' </td>
									</tr>';
		} while ($row_rsMilestones = $query_rsMilestones->fetch());
		$Mile .= '  
							</tbody>
						</table> 
					</div>
				</div> 
			</div>
		</div>';
	}

	$projid = $row_rsMilestone['projid'];
	$outputid = $row_rsMilestone['outputid'];
	$milestoneName = $row_rsMilestone['milestone'];
	$parentid = $row_rsMilestone['parent'];
	$milestartdate = $row_rsMilestone['sdate'];
	$mileenddate = $row_rsMilestone['edate'];
	$checked = $row_rsMilestone['paymentrequired'];

	if ($checked == 1) {
		$checked = "checked";
	} else {
		$checked = "";
	}

	$parentDate = '';
	$parentOptions =  '';

	if ($parentid != null) {
		$query_rsMileParent = $db->prepare("SELECT * FROM tbl_milestone WHERE msid = :msid");
		$query_rsMileParent->execute(array(":msid" => $parentid));
		$row_rsMileParent = $query_rsMileParent->fetch();
		$pdate = $row_rsMileParent['edate'];
		$parentDate = '<div class="col-md-2" id="">
			<label> Predecessor End Date *:</label>
			<div class="form-line">
				<input name="parentdate" type="date" id="parentdate" class="form-control" value="' . $pdate . '" placeholder="" disabled>
			</div>
		</div>';

		$query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE projid = :projid and outputid = :outputid");
		$query_rsMilestone->execute(array(":projid" => $projid, ":outputid" => $outputid));
		$row_rsMilestone = $query_rsMilestone->fetch();

		do {
			if ($mileid != $row_rsMilestone['msid']) {
				if ($parentid == $row_rsMilestone['msid']) {
					$parentOptions .=  '<option value="' . $row_rsMilestone['msid'] . '" selected>' . $row_rsMilestone['milestone'] . '</option>';
				} else {
					$parentOptions .=  '<option value="' . $row_rsMilestone['msid'] . '">' . $row_rsMilestone['milestone'] . '</option>';
				}
			}
		} while ($row_rsMilestone = $query_rsMilestone->fetch());
	} else {
		$parentDate = '';
	}

	$query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid = :projid");
	$query_rsProjects->execute(array(":projid" => $projid));
	$row_rsProjects = $query_rsProjects->fetch();

	$query_rsOutputs = $db->prepare("SELECT * FROM tbl_project_details WHERE id = :outputid and projid = :projid");
	$query_rsOutputs->execute(array(":outputid" => $outputid, ":projid" => $projid));
	$row_rsOutputs = $query_rsOutputs->fetch();
	$opduration = $row_rsOutputs['duration'];
	$yearid = $row_rsOutputs['year'];

	$query_rsFscYear = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE id = :yearid");
	$query_rsFscYear->execute(array(":yearid" => $yearid));
	$row_rsFscYear = $query_rsFscYear->fetch();
	$opyear = $row_rsFscYear['yr'];
	$startDate  = date("d-m-Y", strtotime("01-07-" . $opyear));
	$endDate  = date("d-m-Y", strtotime($startDate . ' + ' . $opduration . ' days'));

	$opstart = date("m-d-Y", strtotime($startDate));
	$opend = date("m-d-Y", strtotime($endDate));

	$projsdate = date("d-m-Y", strtotime($row_rsProjects['projstartdate']));
	$projedate = date("d-m-Y", strtotime($row_rsProjects['projenddate']));



	echo '<fieldset class="scheduler-border">
			<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">MILESTONE DETAILS</legend>
			<input type="hidden"  id="projid" class="form-control" value="' . $projid . '" >
			<input type="hidden"  id="msid" class="form-control" value="' . $mileid . '" >
			<input type="hidden"  id="outputid" class="form-control" value="' . $outputid . '" >
			<div class="col-md-12">
			<label>Output:</label>
			<div>
				<input type="text" id="output_name" class="form-control" value="' . $output . '" style="border:#CCC thin solid; border-radius: 5px" readonly>
			</div>
		</div>
			<div class="col-md-6">
			<label>Output Start Date:</label>
			<div class="">
				<input type="text" id="opstart" class="form-control" value="' . $opstart . '" style="border:#CCC thin solid; border-radius: 5px" readonly>
			</div>
		</div>
		<div class="col-md-6">
			<label>Output End Date:</label>
			<div class="">
				<input type="text"  id="opend" class="form-control" value="' . $opend . '" style="border:#CCC thin solid; border-radius: 5px" readonly>
			</div>
		</div>
			<div class="col-md-12">
				<label>Milestone Name *:</label>
				<div class="">
					<input type="text" name="milestone" id="milestone" value="' . $milestoneName . '" class="form-control" style="border:#CCC thin solid; border-radius: 5px" required>
					<span id="milestonemsg" style="color:red"></span> 
				
					</div>
			</div>  
			<div class="col-md-6">
				<label class="control-label"> Predecessor :</label>
				<div class="form-line">
					<select name="milestoneParent" id="milestoneParent" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true">
						<option value="">.... Select ....</option> 
							' . $parentOptions . '
					</select>
					<script>                            
					$("#milestone").blur(function (e) { 
						var milestone = $(this).val();
						if(milestone){ 
							$("#milestonemsg").html("");
						}else{
							$("#milestonemsg").html("Field Required"); 
						}
					});

					$("#sdate").change(function (e) { 
						e.preventDefault();
						var mstart = new Date($(this).val()).setHours(0,0,0,0);
						var opstart = new Date($("#opstart").val()).setHours(0,0,0,0);
						var opend = new Date($("#opend").val()).setHours(0,0,0,0);  
						var parent = new Date($("#parentdate").val()).setHours(0,0,0,0); 
						var milestonestart = $(this).val();
						$("#edate").val("");
						if(mstart < opstart){
						   $("#sdatemsg").html("Milestone start date should be greater or equal to output start date"); 
							$("#sdate").val("");
							$("#edate").val("");
						}else if(mstart > opend){
							 $("#sdatemsg").html("Milestone start date should be less or equal to output end date"); 
							$("#edate").val("");
							$("#sdate").val("");
						}else{
							if(parent !=""){
								if(mstart < parent){
									 $("#sdatemsg").html("Milestone start date should be  greater or equal to predecessor end date"); 
									$("#edate").val("");
									$("#sdate").val(""); 
								} else{
									$("#sdatemsg").html("");';
									if ($totalRows_rsMilestones > 0) {
										$attribute_click = 'validate_dates(' . $mileid . ',milestonestart,1)';
									}
									echo $attribute_click;
									echo '
																}
															}
														}
													});

													$("#edate").change(function (e) { 
														var mstart = new Date($("#sdate").val()).setHours(0,0,0,0);
														var opend = new Date($("#opend").val()).setHours(0,0,0,0); 
														var mend = new Date($(this).val()).setHours(0,0,0,0);
														var milestoneend = $(this).val();
														if(mstart){
															if(mend < mstart ){
																$("#edatemsg").html("Milestone start date should be greater or equal to start date"); 
																$("#edate").val("");
															}else if(mend  > opend){
																$("#edatemsg").html("Milestone End date should be less or equal to output end date"); 
																$("#edate").val("");
															}else{
																$("#edatemsg").html("");';
									if ($totalRows_rsMilestones > 0) {
										$attribute_click = 'validate_dates(' . $mileid . ',milestoneend,2)';
									}
									echo $attribute_click;
									echo '
							}
						}else{
							$("#edate").val("");
							$("#edatemsg").html("Select Start Date"); 
						}
					});

					$("#milestoneParent").change(function(e) {
						e.preventDefault(); 
						var parent = $(this).val(); 
						$("#edate").val("");
						$("#sdate").val(""); 
						if (parent != "") { 
						$.ajax({
							type: "post",
							url: "general-settings/selected-items/fetch-selected-milestone-task",
							data: {
								getMilestoneParentStart: "parent",
								mileid: parent
							},
							dataType: "json",
							success: function(response) {                                   
								html =
										\'<div class="col-md-2" id="">\' +
										"<label> Predecessor End Date *:</label>" +
										\'<div class="form-line">\' +
										\'<input name="parentdate" type="date" id="parentdate" class="form-control" value="\' +
										response +
										\'" placeholder="" disabled>\' +
										"</div>" +
										"</div>"; 
									$("#parentDate").html(html);
							}
							});
						}else{
							$("#parentDate").html("");
						}
					}); 
					$("#tag-form-submit").click(function(e) { 
						e.preventDefault();
						$("#tag-form-submit").attr("disabled", true);
						var milestone = $("#milestone").val();
						var parentid = $("#milestoneParent").val();  
						var startdate =$("#sdate").val();
						var enddate =$("#edate").val(); 
						var projid =$("#projid").val(); 
						var outputid =$("#outputid").val(); 
						var msid =$("#msid").val();
						var paymentrequired = $("#defaultUnchecked").is(":checked") ? 1 : 0;

						if(milestone){ 
							$("#milestonemsg").html("");
						}else{
							$("#milestonemsg").html("Field Required"); 
						}

						if(startdate){
							$("#sdatemsg").html("");   
						}else{ 
							$("#sdatemsg").html("Field Required");   
						}

						if(enddate){
							$("#sdatemsg").html("");   
						}else{ 
							$("#edatemsg").html("Field Required");   
						}

						if(milestone && startdate && enddate){
							$.ajax({
								type: "POST",
								url: "general-settings/selected-items/fetch-selected-milestone-task",
								data: {
									editMilestone: "editMilestone",
									milestone: milestone, 
									parentid: parentid,
									startdate:startdate,
									enddate:enddate,
									projid:projid,
									outputid:outputid,
									msid:msid,
									paymentrequired:paymentrequired,	
									user_name:1
								},
								dataType: "json",
								success: function(response) {   
									if (response.success == true) { 
										alert(response.messages);
										$(".modal").each(function() {
										  $(this).modal("hide");
										});
										location.reload(true);
									  } else {
										alert(response.messages);
										location.reload(true);
									  }
        								$("#tag-form-submit").attr("disabled", false);
								}
							});
						}else{
							alert("Enter add all values ");
						}
					});

					function validate_dates(msid, date, type) { 
						$.ajax({
						  type: "post",
						  url: "general-settings/selected-items/fetch-selected-milestone-task",
						  data: {
							m_val_dates: "valid",
							msid: msid,
							m_sdate:date
						  },
						  dataType: "json",
						  success: function(response) { 
							if (response.message == "Error") {
								var errors =JSON.parse(response.milestones); 
								var miles ="<ul>";

								for(var i =0; i<errors.length; i++){
									miles =miles + "<li>" + errors[i] +"</li>";
								}
								miles =miles + "</ul>";
								$("#errors").html(miles);
								 
								if(type ==1){
									$("#sdatemsg").html("You should adjust the Succesors dates first"); 
									$("#sdate").val("");
									$("#edate").val("");
								}else if(type ==2){
									$("#edatemsg").html("You should adjust the Succesors dates first"); 
									$("#edate").val("");
								}
							} else {
							  console.log("You are good to go the dates");
							  $("#errors").html("");
							}
						  }
						});
					} 
					</script>
				</div>
			</div> 
			<div id="parentDate">
			' . $parentDate . '
			</div>
			<div class="col-md-6">
				<label>Milestone Start Date *:</label>
				<div class="form-line">
					<input name="sdate" id="sdate" type="date"  value="' . $milestartdate . '" class="form-control" value="" placeholder="Start Date" required>
						<span id="sdatemsg" style="color:red"></span>
					</div>
			</div>
			<div class="col-md-6">
				<label>Milestone End Date *:</label>
				<div class="form-line">
					<input name="edate" id="edate" type="date" value="' . $mileenddate . '" class="form-control" value="" placeholder="End Date" required>
					<span id="edatemsg" style="color:red"></span>
				</div>
			</div>
			<div class="col-md-12">
				<div class="custom-control custom-checkbox">
					<input type="checkbox" name="paymentrequired" class="custom-control-input" value="1" id="defaultUnchecked" ' . $checked . '>
					<label class="custom-control-label" for="defaultUnchecked">Payment to be made after this milestone?</label>
				</div>
			</div>
			<div class="col-md-12" id="errors" style="color:red">

			</div>
		</fieldset>';

	echo $Mile;
}


if (isset($_POST['m_val_dates'])) {
	$mileid = $_POST['msid'];
	$m_date = date("m-d-Y", strtotime($_POST['m_sdate']));

	$query_rsMilestones = $db->prepare("SELECT *  FROM tbl_milestone WHERE parent = :parent");
	$query_rsMilestones->execute(array(":parent" => $mileid));
	$row_rsMilestones = $query_rsMilestones->fetch();
	$totalRows_rsMilestones = $query_rsMilestones->rowCount();
	$msg = [];
	do {
		$projid = $row_rsMilestones['projid'];
		$outputid = $row_rsMilestones['outputid'];
		$milestoneName = $row_rsMilestones['milestone'];
		$milestartdate = date("m-d-Y", strtotime($row_rsMilestones['sdate']));
		$mileenddate = date("m-d-Y", strtotime($row_rsMilestones['edate']));
		if ($m_date > $milestartdate) {
			$msg[] = $milestoneName;
		}
	} while ($row_rsMilestones = $query_rsMilestones->fetch());

	$message = array();
	if (!empty($msg)) {
		$message = array("message" => "Error", "milestones" => json_encode($msg));
	} else {
		$message = array("message" => "Success");
	}

	echo json_encode($message);
}

// getting add task form 
if (isset($_POST['getAddTaskForm'])) {
	$mileid = $_POST['mileid'];
	$query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE msid='$mileid'");
	$query_rsMilestone->execute();
	$row_rsMilestone = $query_rsMilestone->fetch();

	$projid = $row_rsMilestone['projid'];
	$outputid = $row_rsMilestone['outputid'];
	$milestoneName = $row_rsMilestone['milestone'];
	$parentid = $row_rsMilestone['parent'];
	$milestart = $row_rsMilestone['sdate'];
	$mileend = $row_rsMilestone['edate'];

	$responsible = '';

	$query_rsResponsible = $db->prepare("SELECT t.* FROM tbl_projmembers m inner join tbl_projteam2 t on t.ptid=m.ptid where m.projid = :projid");
	$query_rsResponsible->execute(array(":projid" => $projid));
	$row_rsResponsible = $query_rsResponsible->fetch();
	$totalRows_rsPersonel = $query_rsResponsible->rowCount();

	do {
		$ptnid = $row_rsResponsible['ptid'];
		$ptnname = $row_rsResponsible['fullname'];
		$title = $row_rsResponsible['title'];
		$responsible .= '<option value="' . $ptnid . '">' . $title . '.' . $ptnname . '</option>';
	} while ($row_rsResponsible = $query_rsResponsible->fetch());


	$query_rsTask = $db->prepare("SELECT * FROM tbl_task WHERE projid='$projid' and msid='$mileid'");
	$query_rsTask->execute();
	$row_rsTask = $query_rsTask->fetch();
	$parentOptions =  '';

	do {
		$parentOptions .=  '<option value="' . $row_rsTask['tkid'] . '">' . $row_rsTask['task'] . '</option>';
	} while ($row_rsTask = $query_rsTask->fetch());

	echo '
		<fieldset class="scheduler-border">
			<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">TASK DETAILS</legend> 
			<input type="hidden"  id="projid" class="form-control" value="' . $projid . '" >
			<input type="hidden"  id="msid" class="form-control" value="' . $mileid . '" >
			<input type="hidden"  id="outputid" class="form-control" value="' . $outputid . '" >
			<div class="col-md-12">
				<label>Milestone:</label>
				<input type="text" id="milestone_name" class="form-control" value="' .  $milestoneName . '" style="border:#CCC thin solid; border-radius: 5px" readonly>
			</div>
			<div class="col-md-6">
				<label>Milestone Start Date:</label>
				<input type="date" id="milestart" class="form-control" value="' .  $milestart . '" style="border:#CCC thin solid; border-radius: 5px" readonly>
			</div>
			<div class="col-md-6">
				<label>Milestone End Date:</label>
				<div>
					<input type="date"  id="mileend" class="form-control" value="' . $mileend . '" style="border:#CCC thin solid; border-radius: 5px" readonly>
				</div>
			</div>
			<div class="col-md-12">
				<label>Task Name *:</label>
				<div>
					<input type="text" name="task" id="task"  placeholder="Task Name" class="form-control" style="border:#CCC thin solid; border-radius: 5px" required>
					<span id="taskmsg" style="color:red"></span> 
				</div>
			</div>
		<div class="col-md-6">
			<label class="control-label"> Responsible *:</label>
			<div class="form-line">
				<select name="responsible" id="responsible" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" >
					<option value="">.... Select ....</option> 
						' . $responsible . '
				</select>
				<span id="responsiblemsg" style="color:red"></span> 
			</div>
		</div>
			<div class="col-md-6">
				<label class="control-label"> Predecessor :</label>
				<div class="form-line">
					<select name="taskParent" id="taskParent" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" >
						<option value="">.... Select ....</option> 
							' . $parentOptions . '
					</select>
					<script>          
						$("#task").keyup(function (e) {
							var task = $(this).val();
							if(task){
								$("#taskmsg").html("");
							}else{
								$("#taskmsg").html("Field is required");
							}
						});

						$("#responsible").change(function (e) {
							var responsible = $(this).val();
							if(responsible){
								$("#responsiblemsg").html("");
							}else{
								$("#responsiblemsg").html("Field is required");
							}
						});

						$("#tsdate").change(function (e) { 
							e.preventDefault();
							$("#tedate").val("");

							var tstart = new Date($(this).val()).setHours(0,0,0,0);
							var milestart = new Date($("#milestart").val()).setHours(0,0,0,0);
							var mileend = new Date($("#mileend").val()).setHours(0,0,0,0);  
							var parent = new Date($("#parentdate").val()).setHours(0,0,0,0);  

							if(tstart < milestart){
								$("#tsdatemsg").html("Task start date should be greater or equal to Milestone start date"); 
								$("#tsdate").val("");
								$("#tedate").val("");
							}else if(tstart > mileend){
								$("#tsdatemsg").html("Task start date should be less or equal to Milestone end date"); 
								$("#tedate").val("");
								$("#tsdate").val("");
							}else{
								if(parent !=""){
									if(tstart < parent){
										$("#tsdatemsg").html("Task start date should be  greater or equal than predecessor end date"); 
										$("#tedate").val("");
										$("#tsdate").val(""); 
									} else{
										$("#tsdatemsg").html("");
									}
								}
							}
						});

						$("#tedate").change(function (e) { 
							var tstart = new Date($("#tsdate").val()).setHours(0,0,0,0);
							var mileend = new Date($("#mileend").val()).setHours(0,0,0,0); 
							var tend = new Date($(this).val()).setHours(0,0,0,0);
						if(tstart){
								if(tend < tstart ){
									$("#tedatemsg").html("Task end date should be greater or equal to task start date"); 
									$("#tedate").val("");
								}else if(tend  > mileend){
									$("#tedatemsg").html("Task end date should be less or equal to milestone end date"); 
									$("#tedate").val("");
								}else{
									$("#tedatemsg").html("");
								}
							}else{
								$("#tedate").val("");
								$("#tedatemsg").html("Select Start Date"); 
							}
						});

						$("#taskParent").change(function(e) {
							e.preventDefault(); 
							var parent = $(this).val(); 
							$("#tedate").val("");
							$("#tsdate").val(""); 
							if (parent != "") { 
							$.ajax({
								type: "post",
								url: "general-settings/selected-items/fetch-selected-milestone-task",
								data: {
									getTaskParentStart: "parent",
									tkid: parent
								},
								dataType: "json",
								success: function(response) {                                   
									html =
											\'<div class="col-md-4" id="">\' +
											"<label> Predecessor End Date *:</label>" +
											\'<div class="form-line">\' +
											\'<input name="parentdate" type="date" id="parentdate" class="form-control" value="\' +
											response +
											\'" placeholder="" disabled>\' +
											"</div>" +
											"</div>"; 
										$("#parentDate").html(html);
								}
							  });
							}else{
								$("#parentDate").html("");
							}
						}); 

						$("#tag-form-submit").click(function (e) { 
							e.preventDefault();
							$("#tag-form-submit").attr("disabled", true);
							var task = $("#task").val();
							var parentid = $("#taskParent").val();  
							var startdate =$("#tsdate").val();
							var enddate =$("#tedate").val(); 
							var responsible =$("#responsible").val(); 
							var projid =$("#projid").val(); 
							var outputid =$("#outputid").val(); 
							var msid =$("#msid").val();

							if(task){
								$("#taskmsg").html("");
							}else{
								$("#taskmsg").html("Field is required");
							}

							if(responsible){
								$("#responsiblemsg").html("");
							}else{
								$("#responsiblemsg").html("Field is required");
							}

							if(startdate){
								$("#tsdatemsg").html("");
							}else{
								$("#tsdatemsg").html("Field is required");
							}

							if(enddate){
								$("#tedatemsg").html("");
							}else{
								$("#tedatemsg").html("Field is required");
							}

							if(task && startdate && enddate && responsible){
								$.ajax({
									type: "POST",
									url: "general-settings/selected-items/fetch-selected-milestone-task",
									data: {
										addTask: "addTask",
										task: task, 
										parentid: parentid,
										startdate:startdate,
										enddate:enddate,
										projid:projid,
										responsible:responsible,
										outputid:outputid,
										msid:msid,
										user_name:1
									},
									dataType: "json",
									success: function(response) {  
										if (response.success == true) { 
											alert(response.messages);
											$(".modal").each(function() {
											  $(this).modal("hide");
											});
											location.reload(true);
										  } else {
											alert(response.messages);
											location.reload(true);
										  }
        								$("#tag-form-submit").attr("disabled", false);
									}
								});
							}
						});
					</script>
				</div>
			</div> 
			<div id="parentDate">
			
			</div>
			<div class="col-md-4">
				<label>Task Start Date *:</label>
				<div class="form-line">
					<input name="tsdate" id="tsdate" type="date" class="form-control" value="" placeholder="Start Date" required>
						<span id="tsdatemsg" style="color:red"></span>
					</div>
			</div>
			<div class="col-md-4">
				<label>Task End Date *:</label>
				<div class="form-line">
					<input name="tedate" id="tedate" type="date" class="form-control" value="" placeholder="End Date" required>
					<span id="tedatemsg" style="color:red"></span>
				</div>
			</div>
		</fieldset>';
}

if (isset($_POST['getTaskEditForm'])) {
	$taskid = $_POST['taskid'];
	$query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE tkid = :tkid");
	$query_rsTasks->execute(array(":tkid" => $taskid));
	$row_rsTasks = $query_rsTasks->fetch();

	$mileid = $row_rsTasks['msid'];
	$task = $row_rsTasks['task'];
	$parentid = $row_rsTasks['parenttask'];
	$taskstart = $row_rsTasks['sdate'];
	$taskend = $row_rsTasks['edate'];
	$responsibleid = $row_rsTasks['responsible'];

	$query_rsMilestone = $db->prepare("SELECT *  FROM tbl_milestone WHERE msid = :msid");
	$query_rsMilestone->execute(array(":msid" => $mileid));
	$row_rsMilestone = $query_rsMilestone->fetch();

	$projid = $row_rsMilestone['projid'];
	$outputid = $row_rsMilestone['outputid'];
	$milestoneName = $row_rsMilestone['milestone'];

	$responsible = '';

	$query_rsResponsible = $db->prepare("SELECT t.* FROM tbl_projmembers m inner join tbl_projteam2 t on t.ptid=m.ptid where m.projid = :projid");
	$query_rsResponsible->execute(array(":projid" => $projid));
	$row_rsResponsible = $query_rsResponsible->fetch();
	$totalRows_rsPersonel = $query_rsResponsible->rowCount();

	do {
		$ptnid = $row_rsResponsible['ptid'];
		$ptnname = $row_rsResponsible['fullname'];
		$title = $row_rsResponsible['title'];
		$responsible .= '<option value="' . $ptnid . '">' . $title . '.' . $ptnname . '</option>';
	} while ($row_rsResponsible = $query_rsResponsible->fetch());

	$parentDate = '';
	$parentOptions =  '';


	if ($parentid != null) {
		$query_rsTaskParent = $db->prepare("SELECT * FROM tbl_task WHERE tkid = :tkid");
		$query_rsTaskParent->execute(array(":tkid" => $parentid));
		$row_rsTaskParent = $query_rsTaskParent->fetch();
		$pdate = $row_rsTaskParent['edate'];
		$parentDate = '
			<div class="col-md-4" id="">
				<label> Predecessor End Date *:</label>
				<div class="form-line">
					<input name="parentdate" type="date" id="parentdate" class="form-control" value="' . $pdate . '" placeholder="" disabled>
				</div>
			</div>';

		$query_rsTask = $db->prepare("SELECT *  FROM tbl_task WHERE projid = :projid and msid = :msid");
		$query_rsTask->execute(array(":projid" => $projid, ":msid" => $mileid));
		$row_rsTask = $query_rsTask->fetch();
		do {

			if ($taskid != $row_rsTask['tkid']) {
				if ($parentid == $row_rsTask['tkid']) {
					$parentOptions .=  '<option value="' . $row_rsTask['tkid'] . '" selected>' . $row_rsTask['task'] . '</option>';
				} else {
					$parentOptions .=  '<option value="' . $row_rsTask['tkid'] . '">' . $row_rsTask['task'] . '</option>';
				}
			}
		} while ($row_rsTask = $query_rsTask->fetch());
	} else {
		$parentDate = '';
	}

	$milestart = date("m-d-Y", strtotime($row_rsMilestone['sdate']));
	$mileend = date("m-d-Y", strtotime($row_rsMilestone['edate']));


	$query_rsTaskss = $db->prepare("SELECT * FROM tbl_task WHERE parenttask = :taskid");
	$query_rsTaskss->execute(array(":taskid" => $taskid));
	$row_rsTaskss = $query_rsTaskss->fetch();
	$totalRows_rsTaskss = $query_rsTaskss->rowCount();
	$attribute_click = '';
	$Mile = '';
	if ($totalRows_rsTaskss > 0) {
		$Mile .= '  
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="header">
						<div class=" clearfix" style="margin-top:5px; margin-bottom:5px; font-size:16px">
							<strong>Successors</strong>
						</div>
					</div>
					<div class="body">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover" id="milestoneTable" style="width:100%">
								<thead>
									<tr>
										<th width="5%">#</th> 
										<th width="75%">Task</th>   
										<th width="10%">Start Date </th> 
										<th width="10%">End Date </th> 
									</tr>
								</thead>
								<tbody id="funding_table_body" >';
		$Ccounter = 0;
		do {
			$Ccounter++;
			$Mile .= ' 
									<tr>  
										<td >' . $Ccounter  . ' </td>
										<td >' . $row_rsTaskss['task']  . ' </td>  
										<td> ' . date("d M Y", strtotime($row_rsTaskss['sdate']))  . '</td>
										<td>' . date("d M Y", strtotime($row_rsTaskss['edate']))  . ' </td>
									</tr>';
		} while ($row_rsTaskss = $query_rsTaskss->fetch());
		$Mile .= '  
								</tbody>
							</table> 
						</div>
					</div> 
			</div>
		</div>';
	}


	echo '<fieldset class="scheduler-border">
			<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">TASK DETAILS</legend>
			
			<input type="hidden"  name="projid" id="projid" class="form-control" value="' . $projid . '" >
			<input type="hidden" name="msid" id="msid" class="form-control" value="' . $mileid . '" >
			<input type="hidden" name="outputid"  id="outputid" class="form-control" value="' . $outputid . '" >
			<input type="hidden" name="taskid"  id="taskid" class="form-control" value="' . $taskid . '" >
			<div class="col-md-12">
				<label>Milestone:</label>
				<input type="text" id="milestone_name" class="form-control" value="' .  $milestoneName . '" style="border:#CCC thin solid; border-radius: 5px" readonly>
			</div>
			<div class="col-md-6">
			<label>Milestone Start Date:</label>
			<div>
				<input type="text" id="milestart" class="form-control" value="' .  $milestart . '" style="border:#CCC thin solid; border-radius: 5px" readonly>
			</div>
		</div>
		<div class="col-md-6">
			<label>Milestone End Date:</label>
			<div>
				<input type="text"  id="mileend" class="form-control" value="' . $mileend . '" style="border:#CCC thin solid; border-radius: 5px" readonly>
			</div>
		</div>
		<div class="col-md-12">
			<label>Task Name *:</label>
			<div>
				<input type="text" name="task" id="task" value="' . $task . '" placeholder="Task Name" class="form-control" style="border:#CCC thin solid; border-radius: 5px" required>
				<span id="taskmsg" style="color:red"></span> 
				</div>
		</div>
		<div class="col-md-6">
			<label class="control-label"> Responsible *:</label>
			<div class="form-line">
				<select name="responsible" id="responsible" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" >
					<option value="">.... Select ....</option> 
						' . $responsible . '
				</select>
				<span id="responsiblemsg" style="color:red"></span> 
			</div>
		</div>
			<div class="col-md-6">
				<label class="control-label"> Predecessor :</label>
				<div class="form-line">
					<select name="taskParent" id="taskParent" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
						<option value="">.... Select ....</option> 
							' . $parentOptions . '
					</select>
					<script>          
					$("#task").keyup(function (e) {
						var task = $(this).val();
						if(task){
							$("#taskmsg").html("");
						}else{
							$("#taskmsg").html("Field is required");
						}
					 });

					 $("#responsible").change(function (e) {
						var responsible = $(this).val();
						if(responsible){
							$("#responsiblemsg").html("");
						}else{
							$("#responsiblemsg").html("Field is required");
						}
					 });
					$("#tsdate").change(function (e) { 
						e.preventDefault(); 
						var tstart = new Date($(this).val()).setHours(0,0,0,0);
						var milestart = new Date($("#milestart").val()).setHours(0,0,0,0);
						var mileend = new Date($("#mileend").val()).setHours(0,0,0,0);  
						var parent = new Date($("#taskParent").val()).setHours(0,0,0,0);  
						$("#tedate").val("");
						var taskstart = $(this).val();
						if(tstart < milestart){
						   $("#tsdatemsg").html("Milestone start date should be greater or equal to output start date"); 
							$("#tsdate").val("");
							$("#tedate").val("");
						}else if(tstart > mileend){
							 $("#tsdatemsg").html("Milestone start date should be less or equal to output end date"); 
							$("#tedate").val("");
							$("#tsdate").val("");
						}else{
							if(parent !=""){
								if(tstart < parent){
									 $("#tsdatemsg").html("Milestone start date should be greater or equal to predecessor end date"); 
									$("#tedate").val("");
									$("#tsdate").val(""); 
								} else{
									$("#tsdatemsg").html("");
									';
	if ($totalRows_rsTaskss > 0) {
		$attribute_click = 'validate_dates(' . $taskid . ',taskstart,1)';
	}
	echo $attribute_click;
	echo '
								}
							}
						}
					});

					$("#tedate").change(function (e) { 
					var tstart = new Date($("#tsdate").val()).setHours(0,0,0,0);
					var mileend = new Date($("#mileend").val()).setHours(0,0,0,0); 
					var tend = new Date($(this).val()).setHours(0,0,0,0);
					var taskend = $(this).val();
					if(tstart){
						if(tend < tstart ){
							$("#tedatemsg").html("Task end date should be greater or equal to start date"); 
							$("#tedate").val("");
						}else if(tend  > mileend){
							$("#tedatemsg").html("Task end date should be less or equal to milestone end date"); 
							$("#tedate").val("");
						}else{
							$("#tedatemsg").html("");
							';
	if ($totalRows_rsTaskss > 0) {
		$attribute_click = 'validate_dates(' . $taskid . ',taskend,2)';
	}
	echo $attribute_click;
	echo '
						}
					}else{
						$("#tedate").val("");
						$("#tedatemsg").html("Select Start Date"); 
					}
					});

						$("#taskParent").change(function(e) {
							e.preventDefault(); 
							var parent = $(this).val(); 
							$("#tedate").val("");
							$("#tsdate").val(""); 
							if (parent != "") { 
							$.ajax({
								type: "post",
								url: "general-settings/selected-items/fetch-selected-milestone-task",
								data: {
									getTaskParentStart: "parent",
									tkid: parent
								},
								dataType: "json",
								success: function(response) {                                   
									html =
											\'<div class="col-md-4" id="">\' +
											"<label> Predecessor End Date *:</label>" +
											\'<div class="form-line">\' +
											\'<input name="parentdate" type="date" id="parentdate" class="form-control" value="\' +
											response +
											\'" placeholder="" disabled>\' +
											"</div>" +
											"</div>"; 
										$("#parentDate").html(html);
								}
							  });
							}else{
								$("#parentDate").html("");
							}
						}); 
						$("#tag-form-submit").click(function (e) { 
							e.preventDefault(); 
							var task = $("#task").val();
							var parentid = $("#taskParent").val();  
							var startdate =$("#tsdate").val();
							var enddate =$("#tedate").val(); 
							var responsible =$("#responsible").val(); 
							var projid =$("#projid").val(); 
							var outputid =$("#outputid").val(); 
							var msid =$("#msid").val();
							var taskid =$("#taskid").val();

							if(task){
								$("#taskmsg").html("");
							}else{
								$("#taskmsg").html("Field is required");
							}

							if(responsible){
								$("#responsiblemsg").html("");
							}else{
								$("#responsiblemsg").html("Field is required");
							}

							if(startdate){
								$("#tsdatemsg").html("");
							}else{
								$("#tsdatemsg").html("Field is required");
							}

							if(enddate){
								$("#tedatemsg").html("");
							}else{
								$("#tedatemsg").html("Field is required");
							}

							if(task && startdate && enddate && responsible){
								$.ajax({
									type: "POST",
									url: "general-settings/selected-items/fetch-selected-milestone-task",
									data: {
										editTask: "editTask",
										task: task, 
										taskid:taskid,
										parentid: parentid,
										startdate:startdate,
										enddate:enddate,
										projid:projid,
										responsible:responsible,
										outputid:outputid,
										msid:msid,
										user_name:1
									},
									dataType: "json",
									success: function(response) { 
										if (response.success == true) { 
											alert(response.messages);
											$(".modal").each(function() {
											  $(this).modal("hide");
											});
											location.reload(true);
										  } else {
											alert(response.messages);
											location.reload(true);
										  }
									}
								});
							}
						});

						function validate_dates(tsid, date, type) { 
							$.ajax({
							  type: "post",
							  url: "general-settings/selected-items/fetch-selected-milestone-task",
							  data: {
								t_val_dates: "valid",
								tsid: tsid,
								t_sdate:date
							  },
							  dataType: "json",
							  success: function(response) { 
								if (response.message == "Error") {
									var errors =JSON.parse(response.tasks); 
									var miles ="<ul>";

									for(var i =0; i<errors.length; i++){
										miles =miles + "<li>" + errors[i] +"</li>";
									}
									miles =miles + "</ul>";
									$("#errors").html(miles);
									 
									if(type ==1){
										$("#tsdatemsg").html("You should adjust the Succesors dates first"); 
										$("#tsdate").val("");
										$("#tedate").val("");
									}else if(type ==2){
										$("#tedatemsg").html("You should adjust the Succesors dates first"); 
										$("#tedate").val("");
									}
								} else {            
								  console.log("You are good to go the dates");
								  $("#errors").html("");
								}
							  }
							});
						} 
					</script>
				</div>
			</div> 
			<div id="parentDate">
				' .    $parentDate . '
			</div>
			<div class="col-md-4">
				<label>Task Start Date *:</label>
				<div class="form-line">
					<input name="tsdate" id="tsdate" type="date" class="form-control" value="' . $taskstart . '" placeholder="Start Date" required>
						<span id="tsdatemsg" style="color:red"></span>
					</div>
			</div>
			<div class="col-md-4">
				<label>Task End Date *:</label>
				<div class="form-line">
					<input name="tedate" id="tedate" type="date" class="form-control" value="' . $taskend . '" placeholder="End Date" required>
					<span id="tedatemsg" style="color:red"></span>
				</div>
			</div>
			<div class="col-md-12" id="errors" style="color:red">

			</div>
		</fieldset>';
	echo $Mile;
}

if (isset($_POST['t_val_dates'])) {
	$tkid = $_POST['tsid'];
	$t_date = date("m-d-Y", strtotime($_POST['t_sdate']));

	$query_rsTaks = $db->prepare("SELECT * FROM tbl_task WHERE parenttask=:tsid ");
	$query_rsTaks->execute(array(":tsid" => $tkid));
	$row_rsTaks = $query_rsTaks->fetch();
	$totalRows_rsTaks = $query_rsTaks->rowCount();

	$msg = [];
	do {
		$taskname = $row_rsTaks['task'];
		$taskstartdate = date("m-d-Y", strtotime($row_rsTaks['sdate']));
		$taskenddate = date("m-d-Y", strtotime($row_rsTaks['edate']));
		if ($t_date > $taskstartdate) {
			$msg[] = $taskname;
		}
	} while ($row_rsTaks = $query_rsTaks->fetch());

	$message = array();
	if (!empty($msg)) {
		$message = array("message" => "Error", "tasks" => json_encode($msg));
	} else {
		$message = array("message" => "Success");
	}

	echo json_encode($message);
}

if (isset($_POST['getMilestoneParentStart'])) {
	$msid = $_POST['mileid'];
	$query_rsMilestone = $db->prepare("SELECT * FROM tbl_milestone WHERE msid = :msid");
	$query_rsMilestone->execute(array(":msid" => $msid));
	$row_rsMilestone = $query_rsMilestone->fetch();
	echo json_encode($row_rsMilestone['edate']);
}

if (isset($_POST['getTaskParentStart'])) {
	$tkid = $_POST['tkid'];
	$query_rsT = $db->prepare("SELECT * FROM tbl_task WHERE tkid = :tkid");
	$query_rsT->execute(array(":tkid" => $tkid));
	$row_rsT = $query_rsT->fetch();
	echo json_encode($row_rsT['edate']);
}

if (isset($_POST["addMilestone"])) {
	$status = 1;
	$current_date = date("Y-m-d");
	$mlstdate = $_POST['startdate'];
	$milstdateformat = strtotime($mlstdate);
	$msstartdate = date("Y-m-d", $milstdateformat);
	$mlendate = $_POST['enddate'];
	$milenddateformat = strtotime($mlendate);
	$msenddate = date("Y-m-d", $milenddateformat);
	$projid = $_POST['projid'];
	$milestone = $_POST['milestone'];
	$parentid = '';
	if (!empty($_POST['parentid'])) {
		$parentid = $_POST['parentid'];
	} else {
		$parentid = null;
	}

	$paymentrequired = $_POST['paymentrequired'];
	if (!empty($paymentrequired)) {
		$paymentrequired = $paymentrequired;
	} else {
		$paymentrequired = 0;
	}

	try {
		$username = $_POST['user_name'];
		$outputid = $_POST['outputid'];
		$insertSQL = $db->prepare("INSERT INTO tbl_milestone (projid,outputid, milestone, parent, status, paymentrequired, sdate, edate, user_name,date_entered) VALUES (:projid, :outputid, :milestone, :parent, :status, :paymentrequired, :sdate, :edate, :user_name, :date_entered)");
		$results = $insertSQL->execute(array(':projid' => $projid, ":outputid" => $outputid, ':milestone' => $milestone, ':parent' => $parentid, ':status' => $status, ':paymentrequired' => $paymentrequired, ':sdate' => $msstartdate, ':edate' => $msenddate, ':user_name' => $username, ':date_entered' => $current_date));

		if ($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Added";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while adding the record!!";
		}
	} catch (PDOException $e) {
		$valid['success'] = false;
		$valid['messages'] = "Error: " . $e->getMessage();
	}
	echo json_encode($valid);
}

if (isset($_POST["editMilestone"])) {
	$status = 1;
	$current_date = date("Y-m-d");
	$mlstdate = $_POST['startdate'];
	$milstdateformat = strtotime($mlstdate);
	$msstartdate = date("Y-m-d", $milstdateformat);
	$mlendate = $_POST['enddate'];
	$milenddateformat = strtotime($mlendate);
	$msenddate = date("Y-m-d", $milenddateformat);
	$projid = $_POST['projid'];
	$milestone = $_POST['milestone'];
	$payment = $_POST['paymentrequired'];
	$parentid = '';

	if (!empty($_POST['parentid'])) {
		$parentid = $_POST['parentid'];
	} else {
		$parentid = null;
	}

	if (!empty($paymentrequired)) {
		$paymentrequired = $paymentrequired;
	} else {
		$paymentrequired = 0;
	}


	$username = $_POST['user_name'];
	$outputid = $_POST['outputid'];
	$msid = $_POST['msid'];

	$insertSQL = $db->prepare("UPDATE tbl_milestone SET  parent=:parent, milestone=:milestone,  status=:status, paymentrequired=:paymentrequired, sdate=:sdate, edate=:edate, changedby=:user_name,datechanged=:date_entered WHERE msid=:msid ");
	$results = $insertSQL->execute(array(":parent" => $parentid, ':milestone' => $milestone, ':status' => $status, ':paymentrequired' => $paymentrequired, ':sdate' => $msstartdate, ':edate' => $msenddate, ':user_name' => $username, ':date_entered' => $current_date, ":msid" => $msid));

	if ($results === TRUE) {
		$valid['success'] = true;
		$valid['messages'] = "Successfully Edited";
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while Editing the record!!";
	}
	echo json_encode($valid);
}

if (isset($_POST["addTask"])) {
	$status = 1;
	$current_date = date("Y-m-d");
	$tskstdate = $_POST['startdate'];
	$tskstdateformat = strtotime($tskstdate);
	$tskstartdate = date("Y-m-d", $tskstdateformat);
	$tskendate = $_POST['enddate'];
	$tskenddateformat = strtotime($tskendate);
	$tskenddate = date("Y-m-d", $tskenddateformat);
	$projid = $_POST['projid'];
	$task = $_POST['task'];
	if (!empty($_POST['parentid'])) {
		$parentid = $_POST['parentid'];
	} else {
		$parentid = null;
	}

	$username = $_POST['user_name'];
	$responsible = $_POST['responsible'];
	$outputid = $_POST['outputid'];
	$msid = $_POST['msid'];
	$insertSQL = $db->prepare("INSERT INTO tbl_task (msid, projid, outputid,  parenttask, task, status, sdate, edate,responsible, user_name,date_entered) VALUES (:msid, :projid,:outputid, :parenttask, :task, :status, :sdate, :edate,:responsible, :user_name,:date_entered)");
	$results = $insertSQL->execute(array(":msid" => $msid, ':projid' => $projid, ":outputid" => $outputid,  ":parenttask" => $parentid, ':task' => $task, ':status' => $status, ':sdate' => $tskstartdate, ':edate' => $tskenddate, ":responsible" => $responsible, ':user_name' => $username, ':date_entered' => $current_date));
	$valid = [];
	if ($results === TRUE) {
		$valid['success'] = true;
		$valid['messages'] = "Successfully Added";
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while adding the record!!";
	}
	echo json_encode($valid);
}


if (isset($_POST["editTask"])) {
	$status = 1;
	$current_date = date("Y-m-d");
	$tskstdate = $_POST['startdate'];
	$tskstdateformat = strtotime($tskstdate);
	$tskstartdate = date("Y-m-d", $tskstdateformat);
	$tskendate = $_POST['enddate'];
	$tskenddateformat = strtotime($tskendate);
	$tskenddate = date("Y-m-d", $tskenddateformat);
	$projid = $_POST['projid'];
	$task = $_POST['task'];
	$taskid = $_POST['taskid'];
	if (!empty($_POST['parentid'])) {
		$parentid = $_POST['parentid'];
	} else {
		$parentid = null;
	}

	$username = $_POST['user_name'];
	$outputid = $_POST['outputid'];
	$responsible = $_POST['responsible'];

	$insertSQL = $db->prepare("UPDATE tbl_task SET parenttask=:parentid,  task=:task, status=:status, sdate=:sdate, edate=:edate, responsible=:responsible, changedby=:user_name,datechanged=:date_entered WHERE tkid=:taskid ");
	$results = $insertSQL->execute(array(":parentid" => $parentid, ':task' => $task, ':status' => $status, ':sdate' => $tskstartdate, ':edate' => $tskenddate, ':responsible' => $responsible, ':user_name' => $username, ':date_entered' => $current_date, ":taskid" => $taskid));

	if ($results === TRUE) {
		$valid['success'] = true;
		$valid['messages'] = "Successfully Edited";
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while Editing the record!!";
	}
	echo json_encode($valid);
}

if (isset($_POST['deleteItem'])) {
	$deleteItem = $_POST['deleteItem'];
	$itemid = $_POST['itemId'];

	if ($deleteItem == "2") {
		//delete from tbl_milestone
		$deleteQuery = $db->prepare("DELETE FROM `tbl_milestone` WHERE msid=:itemid");
		$results = $deleteQuery->execute(array(':itemid' => $itemid));

		$parentid  = null;
		$insertSQL = $db->prepare("UPDATE tbl_milestone SET  parent=:parent WHERE parent=:msid ");
		$results = $insertSQL->execute(array(":parent" => $parentid, ":msid" => $itemid));


		// delete child data from tl_tasks
		$deleteQuery = $db->prepare("DELETE FROM `tbl_task` WHERE msid=:itemid");
		$results = $deleteQuery->execute(array(':itemid' => $itemid));

		if ($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Deleted";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while deletng the record!!";
		}
		echo json_encode($valid);
	} else if ($deleteItem == "3") {
		//delete only from te task table
		$deleteQuery = $db->prepare("DELETE FROM `tbl_task` WHERE tkid=:itemid");
		$results = $deleteQuery->execute(array(':itemid' => $itemid));

		$parentid  = null;
		$insertSQL = $db->prepare("UPDATE tbl_task SET  parenttask=:parent WHERE parenttask=:tkid ");
		$results = $insertSQL->execute(array(":parent" => $parentid, ":tkid" => $itemid));


		// // delete child data from Inspection 
		// $deleteQuery = $db->prepare("DELETE FROM `tbl_project_inspection_checklist` WHERE taskid=:itemid");
		// $results = $deleteQuery->execute(array(':itemid' => $itemid));

		// // delete child data from monitoring 
		// $deleteQuery = $db->prepare("DELETE FROM `tbl_project_monitoring_checklist` WHERE taskid=:itemid");
		// $results = $deleteQuery->execute(array(':itemid' => $itemid));

		if ($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Deleted";
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while deletng the record!!";
		}
		echo json_encode($valid);
	}
}

if (isset($_POST["finishAddItem"])) {
	$current_date = date("Y-m-d");
	$projid = $_POST['projid'];

	// check if has milestones 
	$query_rsMilestones = $db->prepare("SELECT * FROM tbl_milestone WHERE projid = :projid");
	$query_rsMilestones->execute(array(":projid" => $projid));
	$totalRows_rsMilestones = $query_rsMilestones->rowCount();

	if ($totalRows_rsMilestones > 0) {
		// check if milestones have tasks 
		while ($row_rsMilestones = $query_rsMilestones->fetch()) {
			$msid = $row_rsMilestones["msid"];
			$milestone = $row_rsMilestones["milestone"];

			$query_rsMilestoneTasks = $db->prepare("SELECT * FROM tbl_task WHERE msid = :msid AND projid = :projid");
			$query_rsMilestoneTasks->execute(array(":msid" => $msid, ":projid" => $projid));
			$totalRows_rsMilestoneTasks = $query_rsMilestoneTasks->rowCount();

			if ($totalRows_rsMilestoneTasks == 0) {
				$valid['success'] = false;
				$valid['messages'] = "Please add Task/s for milestone: " . $milestone;
				echo json_encode($valid);
			}
		}
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Please Add Activities!!";
		echo json_encode($valid);
	}

	$projstage = 5;

	$insertSQL = $db->prepare("UPDATE tbl_projects SET projstage=:projstage WHERE projid=:projid ");
	$results = $insertSQL->execute(array(":projstage" => $projstage, ":projid" => $projid));

	if ($results === TRUE) {
		$valid['success'] = true;
		$valid['messages'] = "Successfully Added All Activities";
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while finishing this stage!!";
	}
	echo json_encode($valid);
}
