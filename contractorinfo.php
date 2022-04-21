<?php
//Include database configuration file
include('dbconfig.php');

if(isset($_POST["contr_id"]) && !empty($_POST["contr_id"])){

    //Get all state data
    $query_rsContractor = $db->query("SELECT * FROM tbl_contractor WHERE pinstatus='1'");

    //Get all state data
    $query = $db->query("SELECT * FROM tbl_contractor WHERE contrid = ".$_POST['contr_id']);
    
    //Count total number of rows
    $rowCount = $query->num_rows;
	$row = $query->fetch_assoc();
    $biztype = $row['businesstype'];
	
    //Get business type
    $sqlquery = $db->query("SELECT type FROM tbl_contractorbusinesstype WHERE id = ".$biztype);
	$bizrow = $sqlquery->fetch_assoc();
	
    //Display states list
    if($rowCount > 0){
        echo '<td>
				<select name="projcontractor" class="form-control" id="projcontractor" required="required" style="height:35px; width:98%">
					<option value="1">..Select..</option>';
					while($row_rsContractor = $query_rsContractor->fetch_assoc()){ 
						echo '<option value="'.$row_rsContractor["contrid"].'"'; if (!(strcmp($row_rsContractor["contractor_name"], $row["contractor_name"]))) { echo "selected=\"selected\"";} echo'>'.$row_rsContractor["contractor_name"].'</option>';
					} echo '</select></td><td><input type="text" name="pinnumber" id="pinnumber" class="txtbox" value="'.$row['pinno'].'" style="height:35px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled="disabled"/></td><td><input type="text" name="bizregno" id="bizregno" value="'.$row['busregno'].'" class="txtbox" style="height:35px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled="disabled"/></td><td><input type="text" name="biztype" id="biztype" value="'.$bizrow['type'].'" class="txtbox" style="height:35px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled="disabled"/></td>';
    }else{
        echo '<td><input type="text" name="pinnumber" id="pinnumber" class="txtbox" style="height:35px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled="disabled"/></td><td><input type="text" name="bizregno" id="bizregno" class="txtbox" style="height:35px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled="disabled"/></td><td><input type="text" name="biztype" id="biztype" class="txtbox" style="height:35px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" disabled="disabled"/></td>';
    }
}
?>
