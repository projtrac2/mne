<?php

include_once "controller.php";	
try{
	$valid['success'] = array('success' => false, 'messages' => array());
	if(isset($_POST["deleteItem"])){
        $itemid = $_POST['itemId'];
        
		$deleteQuery = $db->prepare("DELETE FROM `tbl_programs` WHERE progid=:itemid");
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
