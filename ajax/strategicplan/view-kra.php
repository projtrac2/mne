<?php

include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';
include_once("../../includes/system-labels.php");

require('../../functions/strategicplan.php');


$valid['success'] = array('success' => false, 'messages' => array());

if (isset($_POST['more'])) {
    $itemId = $_POST['itemId'];
    $kra_strategic_objectives = get_kra_strategic_objectives($itemId);
    $input = '
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card"> 
                    <div class="body">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="moreInfo" style="width:100%">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="95%">Strategic Objective </th> 
                                </tr>
                            </thead>
                            <tbody>';
    if (count($kra_strategic_objectives) > 0) {
        $counter = 0;
        foreach ($kra_strategic_objectives as $kra_strategic_objective) {
            $counter++;
            $input .= '<tr><td>' . $counter . '</td><td>' . $kra_strategic_objective['objective'] . '</td></tr>';
        }
    } else {
        $input .= '<tr align="center" style="color:red"><td>No data found!!!</td></tr>';
    }
    $input .= '
                            </tbody> 
                        </table> 
                    </div>
                </div>
            </div>
        </div>';
    echo $input;
}

if (isset($_POST['edit'])) {
    $itemId = $_POST['itemId'];
    $kra = get_kra($itemId);
    if ($kra) {
        echo json_encode($kra);
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while editing the record!!";
    }
}

if (isset($_POST["addkra"])) {
    $kra = $_POST['addkra'];
    $spid = $_POST['spid'];
    $user = $_POST['username'];
    $currentdate = date("Y-m-d");

    $query_rsKra = $db->prepare("SELECT *  FROM tbl_key_results_area WHERE kra=:kra and spid=:spid");
    $query_rsKra->execute(array(":spid" => $spid, ":kra" => $kra));
    $row_rsKra = $query_rsKra->fetch();
    $totalRows_rsKra = $query_rsKra->rowCount();

    if ($totalRows_rsKra == 0) {
        $sql = $db->prepare("INSERT INTO `tbl_key_results_area` (spid, kra, created_by, date_created)  VALUES(:spid, :kra, :user, :date)");
        $results = $sql->execute(array(":spid" => $spid, ":kra" => $kra, ":user" => $user, ":date" => $currentdate));

        if ($results === TRUE) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully added";
        } else {
            $valid['success'] = false;
            $valid['messages'] = "Error while adding the record!!";
        }
    } else {
        $valid['success'] = true;
        $valid['messages'] = "Successfully added";
    }

    echo json_encode($valid);
}

if (isset($_POST["edititem"])) {
    $kra = $_POST['editname'];
    $itemid = $_POST['itemId'];
    $kra_data = get_kra($itemid);
    if ($kra_data) {
        $sql = $db->prepare("UPDATE `tbl_key_results_area` SET  kra=:kra WHERE id =:id ");
        $results = $sql->execute(array(":kra" => $kra, ":id" => $itemid));
        if ($results === TRUE) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Updated";
        } else {
            $valid['success'] = false;
            $valid['messages'] = "Error while updating the record!!";
        }
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while updating the record!!";
    }

    echo json_encode($valid);
}

if (isset($_POST["deleteItem"])) {
    $itemid = $_POST['itemId'];
    $kra = get_kra($itemid);
    if ($kra) {
        $deleteQuery = $db->prepare("DELETE FROM `tbl_key_results_area` WHERE id=:itemid");
        $results = $deleteQuery->execute(array(':itemid' => $itemid));
        if ($results === TRUE) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Deleted";
        } else {
            $valid['success'] = false;
            $valid['messages'] = "Error while deleting the record!!";
        }
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while deleting the record!!";
    }
    echo json_encode($valid);
}
