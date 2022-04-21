<?php

include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';

if(isset($_POST["indid"])){
	$indid = $_POST["indid"];
	$lv3id = $_POST["lv3id"];
	$level3 = $_POST["level3"];
	$level3label = $_POST["lv3lb"];

	//$progress = $_POST["scprog"];
	$query_distype = $db->prepare("SELECT d.id, d.disaggregation_type, t.category FROM tbl_indicator_measurement_variables_disaggregation_type d inner join tbl_indicator_disaggregation_types t ON t.id=d.disaggregation_type WHERE indicatorid=:indid");
	$query_distype->execute(array(":indid" => $indid));
	$rowdata = $query_distype->fetch();
	
	$distypeid = $rowdata['id'];
	$distype = $rowdata['disaggregation_type'];
	$category = $rowdata['category'];
	
	echo '
	<div class="row clearfix">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
				<div class="body">
					<label>'.$level3.' '.$level3label.' Disaggregations *:</label>
					<div class="form-line">
						<input type="text" name="locdisaggregation" placeholder="Enter '.$category.'/s seperated by comma" class="form-control" required>
					</div>
					<input type="hidden" name="indid" value="'.$indid.'"/>
					<input type="hidden" name="distypeid" value="'.$distypeid.'"/>
					<input type="hidden" name="distype" value="'.$distype.'"/>
					<input type="hidden" name="level3id" id="level3id" value="'.$lv3id.'"/>
				</div>
			</div>
		</div>
	</div>';
}
?>