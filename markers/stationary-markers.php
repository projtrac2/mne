<?php
include_once("../Connections/Database.php");

// // Start XML file, create parent node

$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

if (isset($_GET['gmapping'])) {
    $mapid = $_GET['gmapping'];  
    $query = $db->prepare("SELECT * FROM tbl_markers m inner join tbl_projects p on p.projid=m.projid where p.deleted='0' AND m.mapid =:mapid");
    $result = $query->execute(array(":mapid" => $mapid));
     
    if ($result) {
        header("Content-type: text/xml");  
        while ($row = $query->fetch()) { 
            $node = $dom->createElement("marker");
            $newnode = $parnode->appendChild($node);
            $newnode->setAttribute("projid", $row['projid']);
            $newnode->setAttribute("name", $row['projname']);
            $newnode->setAttribute("lat", $row['lat']);
            $newnode->setAttribute("lng", $row['lng']);
            $newnode->setAttribute("type", $row['projstatus']);
        }
        echo $dom->saveXML();
    } else {
        die('Invalid query: ');
    }
}
