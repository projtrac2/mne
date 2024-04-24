<?php

//include_once 'projtrac-dashboard/resource/session.php';
include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';
if(isset($_POST['more'])){ 
    $itemId = $_POST['itemId'];
    $query_item = $db->prepare("SELECT * FROM tbl_objective_strategy WHERE objid = '$itemId'");
    $query_item->execute();
    $row_item = $query_item->fetch();
    $rows_count = $query_item->rowCount();
    
    $input = '<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card"> 
            <div class="body">
				<table class="table table-bordered table-striped table-hover" id="moreInfo" style="width:100%">
					<thead>
						<tr>
							<th width="3%">#</th>
							<th width="87%">Strategies </th> 
							<th width="10%">Action </th> 
						</tr>
					</thead>
					<tbody>'; 
					$counter =0; 
					do{
					$counter++;
						$input .='<tr>
						<td>'. $counter .'</td>
						<td>'. $row_item['strategy'] .' </td>  
						<td>
						<div class="btn-group">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"  onchange="checkBoxes()"  aria-haspopup="true" aria-expanded="false">
								Options <span class="caret"></span>
							</button> 
							<ul class="dropdown-menu">
								<li>
									<a type="button" data-toggle="modal" data-target="#removeStrategyModal" id="removeStrategyModalBtn" onclick="removeStrategy('. $row_item['id'] .')">
										<i class="glyphicon glyphicon-trash"></i> Remove
									</a>
								</li>
							</ul>
						</div>
						</td>       
						</tr>';
					}while($row_item = $query_item->fetch()); 
					$input .='
					</tbody> 
				</table> 
            </div>
        </div>
    </div>
    </div>';  
    echo $input;
}


if(isset($_POST['moreInfo'])){ 
    $itemId = $_POST['itemId'];
    $query_Objective = $db->prepare("SELECT * FROM tbl_strategic_plan_objectives WHERE id = '$itemId'");
    $query_Objective->execute();
    $row_Objective = $query_Objective->fetch(); 
	
    $indicatorid =$row_Objective['indicator'];

    $query_rsKPI = $db->prepare("SELECT * FROM tbl_indicator WHERE indid = '$indicatorid'");
    $query_rsKPI->execute();
    $row_rsKPI = $query_rsKPI->fetch(); 
    $KPI =$row_rsKPI['indname']; 
    $unity =$row_rsKPI['unity'];

    $query_rsIndicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid = '$indicatorid'");
    $query_rsIndicator->execute();
    $row_rsIndicator = $query_rsIndicator->fetch(); 
    $indicator =$row_rsIndicator['indname'];

    $input = '<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card"> 
            <div class="header" align="center">
                <div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
                    <h4> Strategic Objective:-   ' . $row_Objective['objective'] . ' </h4> 
                </div>  
            </div>
            <div class="body">
            <div class="row clearfix"> 
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="">
                    <ul class="list-group">  
                        <li class="list-group-item"><strong>Outcome: </strong>' . $row_Objective['outcome'] . ' </li> 
                        <li class="list-group-item"><strong>KPI: </strong>' . $KPI . ' </li>
                        <li class="list-group-item"><strong>KPI Unity of Measure: </strong>' . $unity . ' </li>
                        <li class="list-group-item"><strong>KPI Baseline: </strong>' . $row_Objective['baseline'] . ' </li> 
                        <li class="list-group-item"><strong>KPI Target: </strong>' . $row_Objective['target'] . ' </li>  
                    </ul>
                </div>
            </div> 
            </div>
        </div>
    </div>
    </div>';  
    echo $input;
}


if(isset($_POST['edit'])){ 
    $itemId = $_POST['itemId'];

    //get indicators 
    $query_Indicators = $db->prepare("SELECT * FROM tbl_indicator where active='1' and type=1");
    $query_Indicators->execute();
    $row_indicators =$query_Indicators->fetch();

    // getobjective 
    $query_rsObjectives = $db->prepare("SELECT * FROM tbl_strategic_plan_objectives WHERE id = '$itemId'");
    $query_rsObjectives->execute();
    $row_rsObjectives =$query_rsObjectives->fetch();
	
	$indid = $row_rsObjectives['indicator'];

    //get KPI
    $query_inddetails = $db->prepare("SELECT * FROM tbl_indicator where indid='$indid'");
    $query_inddetails->execute();
    $row_inddetails =$query_inddetails->fetch(); 
	$indunity = $row_inddetails["unity"];
    
    //get kra   
    $query_item = $db->prepare("SELECT k.* FROM tbl_key_results_area k INNER JOIN tbl_strategic_plan_objectives o ON o.kraid = k.id WHERE o.id = '$itemId'");
    $query_item->execute();
    $row_item = $query_item->fetch();
    $rows_count = $query_item->rowCount(); 
	$input =  '   
		<fieldset class="scheduler-border">
			<div class="col-md-12">
				<label class="control-label">Strategic Objective *:</label>
				<div class="form-line">
					<input name="kraid" type="hidden" id="kraid" value="' . $row_item['id'] . '" /> 
					<input name="objid" type="hidden" id="objid" value="' . $row_rsObjectives['id'] . '" /> 
					<input name="objective" type="text" value="' . $row_rsObjectives['objective'] . '" class="form-control" style="width:100%; border:#CCC thin solid; border-radius: 5px"  required>
				</div>
			</div>	
			<div class="col-md-12">
				<label class="control-label">Outcome  *:</label>
				<div class="form-line"> 
					<input name="outcome" type="text" value="' . $row_rsObjectives['outcome'] . '" class="form-control" style="width:100%; border:#CCC thin solid; border-radius: 5px"  required>
				</div>
			</div>
			<div class="col-md-6">
				<label class="control-label">Key Performnce Indicator (KPI) *:</label>
				<div class="form-line">
					<select name="indicator" id="kpiindicator" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
						<option value="">.... Select  Indicator from list ....</option>'; 
						do {
							if($row_indicators['indid'] == $row_rsObjectives['indicator']  ){
								$input .='<option value="' . $row_indicators['indid'] .'" selected> '. $row_indicators['indname'] .'</option>';
							}else{
								$input .='<option value="' . $row_indicators['indid'] .'"> '. $row_indicators['indname'] .'</option>';
							} 
						} while ($row_indicators =$query_Indicators->fetch()); 
						$input .='
					</select> 
				</div>
			</div>
			<div class="col-md-3">
				<label class="control-label">KPI Baseline <span id="kpiunty1">('.$indunity.')</span> *: </label>
				<div class="form-line"><input name="kpibaseline" id="kpibaseline" type="hidden" value="'.$row_rsObjectives['baseline'].'"><strong style="color:#673AB7"><div id="kpibaseline2">'.$row_rsObjectives['baseline'].'</div></strong>
				</div>
			</div>
			<div class="col-md-3">
				<label class="control-label">Outcome Target <span id="kpiunty2">('.$indunity.')</span>*: </label>
				<div class="form-line"> 
					<input name="outcometarget" id="outcometarget" type="number" value="' . $row_rsObjectives['target'] . '" class="form-control" style="width:100%; border:#CCC thin solid; border-radius: 5px" placeholder="Enter Outcome target" required> 
				</div>
			</div>
		</fieldset>'; 
	$input .='  
		<fieldset class="scheduler-border"> 
			<div class="table-responsive">
				<table class="table table-bordered" id="funding_table">
					<thead>
						<tr>
							<th style="width:98%">Strategic Objectives</th>
							<th style="width:2%">
								<button type="button" name="addplus" onclick="add_row();" title="Add another field" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button>
							</th> 
						</tr>
					</thead> 
					<tbody>';
						$query_strategy = $db->prepare("SELECT * FROM tbl_objective_strategy WHERE objid = '$itemId'");
						$query_strategy->execute();
						$row_strategy = $query_strategy->fetch(); 
						$counter =1;
						do{ 
							$counter ++;
							$strategy = $row_strategy['strategy']; 
							$input .='<tr id = "row'. $counter. '">
								<td>
									<input type="text" name="strategic[]" id="strategic" class="form-control" value="'. $strategy .'" placeholder="Enter the Strategic Objective" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
								</td>
								<td>
									<button type="button" class="btn btn-danger btn-sm"  onclick=delete_row("row'. $counter. '")>
									<span class="glyphicon glyphicon-minus"></span></button>
								</td> 
							</tr>';     
						}while($row_strategy = $query_strategy->fetch()); 
						$input .='
					</tbody> 
				</table> 
				<input name="username" type="hidden" id="username" value="<?php echo $user_name; ?>" /> 
			</div> 
		</fieldset>
			<script>
		$(document).ready(function() {
			// get department
			$("#kpiindicator").on("change", function() {
				var kpiindicator = $(this).val();
				if (kpiindicator) {
					$.ajax({
						type: "post",
						url: "add-obj-process.php",
						data: {
							kpiindid: kpiindicator
						},
						dataType: "html",
						success: function(response) {
							$("#kpiunty1").html(response);
							$("#kpiunty2").html(response);
						}
					});
				}
			});
		
			$("#kpiindicator").on("change", function() {
				var kpiind = $( "#kpiindicator option:selected" ).val();
				var objid = $("#objid").val();
				if (kpiind) {
					$.ajax({
						type: "post",
						url: "add-obj-process.php",
						data: {
							objectiveid: objid,
							kpiindicator2: kpiind
						},
						dataType: "json",
						success: function(response) {
							if(response.basevalue > 0) {
								$("#kpibaseline").val(response.basevalue);
								$("#kpibaseline2").html(response.basevalue);
								$("#outcometarget").val(response.octg);
							} else {
								$("#kpibaseline2").html("<strong>Indicator does not have baseline value</strong>");
								$("#outcometarget").val("");
							}
						}
					});
				} else {
					$("#kpibaseline2").html("<strong>Please select KPI first</strong>");
					$("#outcometarget").val("");
				}
			});
		});
	</script>'; 
	echo $input;
}

if(isset($_POST["edititem"])){ 
	$objid = $_POST['objid'];  
	$objective = $_POST['objective'];
	$outcome = $_POST['outcome'];
	$indicator = $_POST['indicator'];
	$kpibaseline = $_POST['kpibaseline'];
	$outcometarget = $_POST['outcometarget'];
	$kraid = $_POST['kraid'];  
	$current_date = date("Y-m-d");
	$user = $_POST['username'];

	$ObjectivesInsert = $db->prepare("UPDATE tbl_strategic_plan_objectives SET kraid=:kraid, objective=:objective, outcome=:outcome, indicator=:indicator, baseline=:kpibaseline, target=:target, created_by=:user, date_created=:dates WHERE id='$objid'");
	$resultObjectives = $ObjectivesInsert->execute(array(":kraid" => $kraid, ":objective" => $objective, ":outcome" => $outcome, ":indicator" => $indicator, ":kpibaseline" => $kpibaseline, ":target" => $outcometarget, ":user" => $user, ":dates" => $current_date));

	if ($resultObjectives) {     
		$deleteQuery = $db->prepare("DELETE FROM tbl_objective_strategy WHERE objid=:objid");
		$results = $deleteQuery->execute(array(':objid' => $objid));
		$spcount = count($_POST["strategic"]);
		for ($m = 0; $m < $spcount; $m++){
			$strategy = $_POST['strategic'][$m];
			$kraInsert = $db->prepare("INSERT INTO tbl_objective_strategy(objid,strategy,created_by,date_created) VALUES (:objid, :strategy, :user, :dates)");
			$kraInsert->execute(array(":objid" => $objid, ":strategy" => $strategy, ":user" => $user, ":dates" => $current_date));
		}     
		if($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Updated";	
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while updatng the record!!";
		} 
	}  
    echo json_encode($valid);
}

if(isset($_POST["deleteItem"])){
    $itemid = $_POST['itemId'];
    $deleteQuery = $db->prepare("DELETE FROM `tbl_strategic_plan_objectives` WHERE id=:itemid");
    $results = $deleteQuery->execute(array(':itemid' => $itemid));

    $deleteQuery = $db->prepare("DELETE FROM `tbl_objective_strategy` WHERE objid=:itemid");
    $results = $deleteQuery->execute(array(':itemid' => $itemid));

    if($results === TRUE) {
        $valid['success'] = true;
        $valid['messages'] = "Successfully Deleted";	
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while deletng the record!!";
    }
    echo json_encode($valid);
}

if(isset($_POST["deleteStrategy"])){
    $itemid = $_POST['strategyid'];  
    $deleteQuery = $db->prepare("DELETE FROM `tbl_objective_strategy` WHERE id=:itemid");
    $results = $deleteQuery->execute(array(':itemid' => $itemid)); 
    if($results === TRUE) {
        $valid['success'] = true;
        $valid['messages'] = "Successfully Deleted";	
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while deletng the record!!";
    }
    echo json_encode($valid);
}