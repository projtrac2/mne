<?php
try {
    include_once("../controller.php");

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
        $messages = "Error adding record";
        $result = false;
        if (validate_csrf_token($_POST['csrf_token'])) {
            $kra = $_POST['addkra'];
            $spid = $_POST['spid'];
            $user = $_POST['username'];
            $currentdate = date("Y-m-d");

            $query_rsKra = $db->prepare("SELECT *  FROM tbl_key_results_area WHERE kra=:kra and spid=:spid");
            $query_rsKra->execute(array(":spid" => $spid, ":kra" => $kra));
            $row_rsKra = $query_rsKra->fetch();
            $totalRows_rsKra = $query_rsKra->rowCount();

            $messages = "Successfully added";
            $result = true;
            if ($totalRows_rsKra == 0) {
                $sql = $db->prepare("INSERT INTO `tbl_key_results_area` (spid, kra, created_by, date_created)  VALUES(:spid, :kra, :user, :date)");
                $results = $sql->execute(array(":spid" => $spid, ":kra" => $kra, ":user" => $user, ":date" => $currentdate));
                if (!$results) {
                    $messages = "Error adding record";
                    $result = true;
                }
            }
        }

        echo json_encode(array("success" => $result, "messages" => $messages));
    }

    if (isset($_POST["edititem"])) {
        $messages = "Error while updating the record!!";
        $result = false;
        if (validate_csrf_token($_POST['csrf_token'])) {
            $kra = $_POST['editname'];
            $itemid = $_POST['itemId'];
            $kra_data = get_kra($itemid);
            if ($kra_data) {
                $sql = $db->prepare("UPDATE `tbl_key_results_area` SET  kra=:kra WHERE id =:id ");
                $results = $sql->execute(array(":kra" => $kra, ":id" => $itemid));
                if ($results) {
                    $messages = "Successfully updated record";
                    $result = true;
                }
            }
        }

        echo json_encode(array("success" => $result, "messages" => $messages));
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
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
