<?php
//Include database configuration file
include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';
include_once("../../system-labels.php");

if (isset($_POST["indcode"]) && !empty($_POST["indcode"])) {
	//Get all state data
	$code = $_POST["indcode"];
	$indid = $_POST["indid"];

	$query_rsIndicator = $db->prepare("SELECT indid, indicator_code FROM tbl_indicator WHERE indicator_code = '$code'");
	$query_rsIndicator->execute();
	$row_rsIndicator = $query_rsIndicator->fetch();
	$indcount = $query_rsIndicator->rowCount();

	if (($indcount > 0 && empty($indid)) || ($indcount > 0 && !empty($indid) && $row_rsIndicator["indid"] != $indid)) {
		echo "<label>&nbsp;</label>
		<div class='alert bg-red alert-dismissible' role='alert' style='height:35px; padding-top:5px'>
			<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
			This Indicator Code (" . $code . ") already exist and can not use it again!!
		</div>";
	}
}

if (isset($_POST["indtype"]) && !empty($_POST["indtype"])) {
	//Get all state data
	$indtype = $_POST["indtype"];

	$query_indCat = $db->prepare("SELECT * FROM tbl_indicator_categories WHERE indicator_type=:indtype and active=1");
	$query_indCat->execute(array(":indtype" => $indtype));
	$row_indCat = $query_indCat->fetch();
	$rows = $query_indCat->rowCount();

	//Display states list
	if ($rows > 0) {
		echo '
		<option value="" selected="selected" class="selection">....Select Category....</option>';
		do {
			echo '<option value="' . $row_indCat['category'] . '">' . $row_indCat['description'] . '</option>';
		} while ($row_indCat = $query_indCat->fetch());
	}
}

// get department 
if (isset($_POST['sct_id'])) {
	$dept = $_POST['sct_id'];
	#echo 'department selected';
	$query_dep = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent='$dept' AND deleted='0'");
	$query_dep->execute();
	echo '<select name="department" id="department" class=" form-control show-tick" style="border:#CCC thin solid; border-radius:5px; width:98%"  required><option value="">Select Division</option>';
	while ($row = $query_dep->fetch()) {
		echo '<option value="' . $row['stid'] . '"> ' . $row['sector'] . '</option>';
	}
	echo '</select>';
}

if (isset($_POST['get_diss_type'])) {
	$type = $_POST['get_diss_type'];
	$query_diss_type = $db->prepare("SELECT * FROM tbl_indicator_disaggregation_types");
	$query_diss_type->execute();
	$options = '<option value="">Select from list</option>';
	while ($row = $query_diss_type->fetch()) {
		$options .=  '<option value="' . $row['id'] . '"> ' . $row['category'] . '</option>';
	}
	echo $options;
}

if (isset($_POST['get_unit'])) {
	$query_diss_type = $db->prepare("SELECT * FROM tbl_measurement_units ");
	$query_diss_type->execute();
	$options = '<option value="">Select from list</option>';
	while ($row = $query_diss_type->fetch()) {
		$options .=  '<option value="' . $row['id'] . '"> ' . $row['unit'] . '</option>';
	}
	echo $options;
}


if (isset($_POST['addunit']) && !empty($_POST['addunit'])) {
	$unit  = $_POST['unit'];
	$unitdescription = $_POST['unitdescription'];
	$valid = [];
	$insert = $db->prepare("INSERT INTO `tbl_measurement_units`(unit, description)  VALUES(:unit, :description)");
	$results  = $insert->execute(array(':unit' => $unit, ':description' => $unitdescription));

	if ($results === TRUE) {
		$valid['msg'] = true;
	} else {
		$valid['msg'] = false;
	}
	echo json_encode($valid);
}

if (isset($_POST['add_type_diss']) && !empty($_POST['add_type_diss'])) {
	$diss_type_name = $_POST['diss_type_name'];
	$dissegration_category = $_POST['disaggregation_cat'];
	$description = $_POST['disdescription'];
	$valid = [];
	$insert = $db->prepare("INSERT INTO `tbl_indicator_disaggregation_types`(category, Description, type)  VALUES(:category,:description, :type)");
	$results  = $insert->execute(array(":category" => $diss_type_name, ":description"=>$description, ":type" => $dissegration_category));

	if ($results === TRUE) {
		$valid['msg'] = true;
	} else {
		$valid['msg'] = false;
	}
	echo json_encode($valid);
}


if(isset($_POST['get_method'])){
	$data ='';
	if(isset($_POST['method']) && !empty($_POST['method'])){
		$method =$_POST['method'];
		$outcome_type =$_POST['outcome_type']; 

		$prefix= "";
		$measurement_variable ="";
		if($outcome_type ==3){
			$prefix = "impact";
			$measurement_variable = "Impact Measurement Variable";
		}else if($outcome_type ==1){
			$prefix ="direct_outcome";
			$measurement_variable = "Direct Measurement Variable";
		}else if($outcome_type ==2){
			$prefix ="indirect_outcome";
			$measurement_variable = "Indirect Measurement Variable";
		}

		if($method ==1){
			$data .=
			'<div class="col-md-12" id="">
				<label for="sum" id="" class="control-label">Summation Measurement variables *:</label>
				<div class="form-input">
					<input type="text" name="'.$prefix.'aggragate" id="'.$prefix.'aggragate" placeholder="'.$measurement_variable.'" class="form-control">
				</div>
			</div>';
		}else if($method ==2){
			$data .=
			'<div class="col-md-12" id="">
				<label for="sum" id="" class="control-label">Numerator Measurement variables *:</label>
				<div class="form-input">
					<input type="text" name="'.$prefix.'numerator" id="'.$prefix.'numerator" placeholder="'.$measurement_variable.'" class="form-control">
				</div>
			</div>
			<div class="col-md-12" id="">
				<label for="sum" id="denominator" class="control-label">Denominator Measurement variables *:</label>
				<div class="form-input">
					<input type="text" name="'.$prefix.'denominator" id="'.$prefix.'denominator" placeholder="'.$measurement_variable.'" class="form-control">
				</div>
			</div>';
		}else if($method ==3){
			$data .=
			'<div class="col-md-12" id="">
				<label for="sum" id="" class="control-label">Numerator Measurement variables *:</label>
				<div class="form-input">
					<input type="text" name="'.$prefix.'numerator" id="'.$prefix.'numerator" placeholder="'.$measurement_variable.'" class="form-control">
				</div>
			</div>';
		}
	}
	echo $data; 
}

if(isset($_POST['get_disaggregations'])){ 
	$query_diss_type = $db->prepare("SELECT * FROM tbl_indicator_disaggregation_types ORDER BY id ASC");
	$query_diss_type->execute();
	$options = '<option value="">Select from list</option>';
	while ($row = $query_diss_type->fetch()) {
		$options .=  '<option value="' . $row['id'] . '"> ' . $row['category'] . '</option>';
	}
	echo $options;
}
 
if (isset($_POST['getdisstype'])) {
	$type = $_POST['ind_diss'];
	$query_diss_type = $db->prepare("SELECT * FROM tbl_indicator_disaggregation_types  WHERE  id=:type");
	$query_diss_type->execute(array(":type"=>$type));
	$row = $query_diss_type->fetch();
	$type = $row['type']; 
	$data['success'] = false;

	if($type == 0){
		$data['success'] = true;
	} 

	echo json_encode($data);
}

if (isset($_POST["get_enumerator"]) && !empty($_POST["get_enumerator"])) {
	//Get all state data
	$ministry = $_POST["get_enumerator"];
 
	$query_rsMembers = $db->prepare("SELECT * FROM users u inner join  tbl_projteam2 t on t.ptid=u.pt_id WHERE ministry = :ministry and disabled='0' group by ptid");
	$query_rsMembers->execute(array(":ministry" => $ministry));
	$totalRows_rsMembers = $query_rsMembers->rowCount(); 
	
	if($totalRows_rsMembers > 0){
		echo '
		<option value="" class="selection">....select enumerator....</option>';
		while ($row_rsMembers = $query_rsMembers->fetch()) {                                                                             
			echo '<option value="' . $row_rsMembers['userid'] . '">' . $row_rsMembers['fullname'] . '</option>'; 
		}
	} else {
		echo '
		<option value="" class="selection">....No users in this '.$ministrylabel.'....</option>';
	}
}






