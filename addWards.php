<?php
//include_once 'projtrac-dashboard/resource/session.php';

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

if(isset($_POST["wd_id"]) && ($_POST["wd_id"]=="" || empty($_POST["wd_id"]))){
    echo '<input type="text" name="location" class="form-control" id="location" placeholder="Enter Level-1" required="required" style="height:35px; width:98%"/>'; 
}
elseif(isset($_POST["wd_id"]) && ($_POST["wd_id"] == $_POST["subc_ID"])){
    echo '<input type="text" name="location" class="form-control" id="location" placeholder="Enter Level-2" required="required" style="height:35px; width:98%"/>'; 
}
elseif(isset($_POST["wd_id"]) && $_POST["wd_id"]!=="1"){
    echo '<input type="text" name="location" class="form-control" id="location" placeholder="Enter Level-3" required="required" style="height:35px; width:98%"/>'; 
}
?>