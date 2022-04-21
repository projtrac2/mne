<?php require_once('Connections/ProjMonEva.php'); ?>
<?php
if(isset($_POST["tsk_id"]) && trim($_POST["tsk_id"]) != ''){
$tkstatus = $_POST["tsk_id"];
mysql_select_db($database_ProjMonEva, $ProjMonEva);
$query_rsTKStatus = "SELECT progress FROM tbl_task WHERE tkid='$tkstatus'";
$rsTKStatus = mysql_query($query_rsTKStatus, $ProjMonEva) or die(mysql_error());
$row_rsTKStatus = mysql_fetch_assoc($rsTKStatus);
$totalRows_rsTKStatus = mysql_num_rows($rsTKStatus);
$total = $row_rsTKStatus["progress"];

    $percent = $total."%"; 


    echo '<script>
    parent.document.getElementById("responseprog").innerHTML="<div style=\"width:'.$percent.';background:linear-gradient(to right, rgb(192, 209, 10) 0%,rgb(5, 140, 2) 100%); ;height:28px; border-radius: 5px;\">&nbsp;</div>";
    </script>';

    ob_flush(); 
    flush(); 
}
 