<?php
include_once "controller.php";

$currentdate = date("Y-m-d");
if(isset($_POST['more'])){ 
    $itemId = $_POST['itemId'];
    $query_item = $db->prepare("SELECT * FROM tbl_strategic_plan_objectives WHERE kraid = '$itemId'");
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
							<th width="5%">#</th>
							<th width="95%">Strategic Objective </th> 
						</tr>
					</thead>
					<tbody>'; 
					$counter =0; 
					do{
					$counter++;
						$input .='<tr><td>'. $counter .'</td><td>'. $row_item['objective'] .'</td></tr>';
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

if(isset($_POST['edit'])){ 
    $itemId = $_POST['itemId'];
    $query_item = $db->prepare("SELECT * FROM tbl_key_results_area WHERE tbl_key_results_area.id = '$itemId'");
    $query_item->execute();
    $row_item = $query_item->fetch();
    $rows_count = $query_item->rowCount(); 
    echo json_encode($row_item);
}

if(isset($_POST["addkra"])){
    $kra =$_POST['addkra']; 
    $spid = $_POST['spid'];
    $user = $_POST['username']; 
	$sql = $db->prepare("INSERT INTO `tbl_key_results_area` (spid, kra, created_by, date_created)  VALUES(:spid, :kra, :user, :date)");
    $results = $sql->execute(array(":spid"=>$spid,":kra"=>$kra,":user"=>$user,":date"=>$currentdate));
    
    if($results === TRUE) {
        $valid['success'] = true;
        $valid['messages'] = "Successfully added";	
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while adding the record!!";
    }
    echo json_encode($valid);
}

if(isset($_POST["edititem"])){
    $kra =$_POST['editname']; 
    $itemid = $_POST['itemId']; 
    $sql = $db->prepare("UPDATE `tbl_key_results_area` SET  kra=:kra WHERE id =:id ");
    $results = $sql->execute(array(":kra"=>$kra,":id"=>$itemid));
    
    if($results === TRUE) {
        $valid['success'] = true;
        $valid['messages'] = "Successfully Updated";	
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while updatng the record!!";
    }
    echo json_encode($valid);
}

if(isset($_POST["deleteItem"])){
    $itemid = $_POST['itemId'];
    $deleteQuery = $db->prepare("DELETE FROM `tbl_key_results_area` WHERE id=:itemid");
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