<?php
//Include database configuration file
include('dbconfig.php');

if(isset($_POST["subc_id"]) && $_POST["subc_id"]=="1"){
    echo '<input type="text" name="wards" class="form-control" id="wards" value="Ward Not Required" style="height:35px; width:98%"/>'; 
}
?>