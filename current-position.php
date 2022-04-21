<?php 
//include_once 'projtrac-dashboard/resource/session.php';
include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';
	
$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);
$proj ='';
if(isset($_POST['projectid'])){
	$proj = $_POST['projectid'];
}
$query =$db->prepare("SELECT * FROM tbl_map_markers m inner join tbl_projects p on p.projid=m.projid where p.deleted='0' AND p.projid='$proj' ");
 
$result = $query->execute();

if (!$result) {
	die('Invalid query: ');
}

header("Content-type: text/xml");

//Iterate through the rows, adding XML nodes for each

while ($row = $query->fetch()){
  // Add to XML document node
  $node = $dom->createElement("marker");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("id",$row['id']);
  $newnode->setAttribute("projid",$row['projid']);
  $newnode->setAttribute("name",$row['projname']);
  $newnode->setAttribute("cost", number_format($row['projcost'], 2));
  $newnode->setAttribute("plat", $row['lat']);
 $newnode->setAttribute("plong", $row['lng']);
  $newnode->setAttribute("type", $row['projstatus']);
}

echo $dom->saveXML();
