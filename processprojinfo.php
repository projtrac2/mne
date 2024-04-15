<?php 
try {
	//code...

//include_once 'projtrac-dashboard/resource/session.php';
include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';


if(isset($_POST['action'])){
	$proj= $_POST['action'];
	$query =$db->prepare("SELECT * FROM tbl_projects_photos m inner join tbl_projects p on p.projid=m.projid where p.deleted='0' AND p.projid='".$proj."' AND (ftype='jpg' OR ftype='png' OR ftype='jpeg')");
	
	$result = $query->execute();
	
	if (!$result) {
		 die('Invalid query: ');
	}else{
		//$rows = $query->fetch();
		echo '<div class="row">';
		
		while($rows = $query->fetch()){
			echo '<div class="col-md-3 lightbo"><a class="example-image-link" href="'. $rows['floc'] .'" data-lightbox="example-set" data-title="'. $rows['description'] .'">'.$rows["m.catid"].'
		<img class="example-image" src="'. $rows['floc'] .'" alt="" style="width:100%; height:100px"/>
		</a> </div>';
		}
	 	echo '</div>';
	}
}else if(isset($_GET['myprojid'])){
	$dom = new DOMDocument("1.0");
	$node = $dom->createElement("markers");
	$parnode = $dom->appendChild($node);
	$proj =$_GET['myprojid'];
	$query =$db->prepare("SELECT * FROM tbl_map_markers m inner join tbl_projects p on p.projid=m.projid where p.deleted='0' AND p.projid='$proj'");
	$result = $query->execute();
	if (!$result) {
		die('Invalid query: ');
	}else{
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
			$newnode->setAttribute("lat", $row['lat']);
			$newnode->setAttribute("long", $row['lng']);
			$newnode->setAttribute("type", $row['projstatus']);
		}
		
		echo $dom->saveXML();
	}
}

} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}

