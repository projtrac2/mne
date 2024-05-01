<?php
try {
    include '../controller.php';
    if (isset($_GET['get_indicator_markers'])) {
        $projid = $_GET['projid'];
        $output_id = $_GET['output_id'];
        $state_id = $_GET['state_id'];
        $site_id = $_GET['site_id'];

        $dom = new DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);
        $query = $db->prepare("SELECT * FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id = m.opid WHERE d.id = :output_id AND d.projid=:projid AND state=:state_id AND site_id=:site_id");
        $result = $query->execute(array(":output_id" => $output_id, ":projid" => $projid, ":state_id" => $state_id, ":site_id" => $site_id));
        if ($result) {
            header("Content-type: text/xml");
            while ($row = $query->fetch()) {
                $node = $dom->createElement("marker");
                $newnode = $parnode->appendChild($node);
                $newnode->setAttribute("lat", $row['lat']);
                $newnode->setAttribute("lng", $row['lng']);
            }
            echo $dom->saveXML();
        } else {
            die('Invalid query: ');
        }
    }


    if (isset($_GET['get_project_indicator_markers'])) {
        $indid = $_GET['output'];
        $projid = $_GET['projid'];
        $dom = new DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);
        $query = $db->prepare("SELECT * FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id = m.opid WHERE d.indicator = :indid AND d.projid=:projid");
        $result = $query->execute(array(":indid" => $indid, ":projid" => $projid));

        if ($result) {
            header("Content-type: text/xml");
            while ($row = $query->fetch()) {
                $node = $dom->createElement("marker");
                $newnode = $parnode->appendChild($node);
                $newnode->setAttribute("lat", $row['lat']);
                $newnode->setAttribute("lng", $row['lng']);
            }
            echo $dom->saveXML();
        } else {
            die('Invalid query: ');
        }
    }
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
