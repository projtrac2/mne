<?php

include_once "controller.php";	
try{
	$valid['success'] = array('success' => false, 'messages' => array());
	//This code for Create new Records // inserts data to table
	if(isset($_POST["newitem"])){
        $parent = ""; 
        $name =$_POST['name'];
        $url = $_POST['url'];
        $icon = $_POST['icons'];
        $status = 1;
        if($_POST['parent'] =="parent"){
          $parent =Null;
        }else{
           $parent = $_POST['parent']; 
        }

        $sql = $db->prepare("INSERT INTO tbl_sidebar_menu (parent,Name,icons, url, status) VALUES(:parent,:name,:icon, :url, :status )");
        $results = $sql->execute(array(":parent"=>$parent, ":name"=>$name,":icon"=>$icon, ":url"=>$url, ":status"=>$status));

		if($results === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "Successfully Added";	
		} else {
			$valid['success'] = false;
			$valid['messages'] = "Error while adding the record!!";
		}
		echo json_encode($valid);
    }
    
	if(isset($_POST["edititem"])){
        $parent = ""; 
        $name =$_POST['editname'];
        $url = $_POST['editurl'];
        $icon = $_POST['editicons'];
		$status = $_POST['editStatus'];
		$itemid = $_POST['itemId'];
        if($_POST['editparent'] =="parent"){
          $parent =Null;
        }else{
           $parent = $_POST['editparent']; 
        }

        $sql = $db->prepare("UPDATE tbl_sidebar_menu SET parent=:parent,Name=:name,icons=:icon, url=:url, status=:status WHERE id =:id");
        $results = $sql->execute(
        array(
        ":parent"=>$parent, 
        ":name"=>$name,
        ":icon"=>$icon,
        ":url"=>$url,
        ":status"=>$status, 
        ":id"=>$itemid
      ));

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
		$deleteQuery = $db->prepare("DELETE FROM `tbl_sidebar_menu` WHERE id=:itemid");
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

}catch (PDOException $ex){
    // $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $ex->getMessage();
}