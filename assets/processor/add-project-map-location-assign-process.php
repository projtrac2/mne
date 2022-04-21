<?php
include_once('../../projtrac-dashboard/resource/Database.php');
include_once('../../projtrac-dashboard/resource/utilities.php');
include_once("../../system-labels.php");
require('../../vendor/autoload.php');
 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$valid['success'] = array('success' => false, 'messages' => array());

// pending may 2021
function sendMail($db, $projid, $opid)
{    
    $level3label = $GLOBALS["level3label"];

    $projlinktitle = 'Project Mapping Application';
    $subject = "Project Mapping";

    $query_rsProjects = $db->prepare("SELECT *  FROM tbl_projects WHERE deleted='0' and projid=:projid");
    $query_rsProjects->execute(array(":projid" => $projid));
    $row_rsProjects = $query_rsProjects->fetch();
    $projname = $row_rsProjects['projname'];
    $projcode = $row_rsProjects['projcode'];

    $query_rsLoations = $db->prepare("SELECT *  FROM tbl_project_mapping WHERE outputid=:opid and projid=:projid");
    $query_rsLoations->execute(array(":opid" => $opid, ":projid" => $projid));
    $row_rsLoations = $query_rsLoations->fetch();

    $query_rsOutput =  $db->prepare("SELECT * FROM tbl_project_details WHERE id ='$opid'  ORDER BY id ASC");
    $query_rsOutput->execute();
    $row_rsOutput = $query_rsOutput->fetch();
    $totalRows_rsOutput = $query_rsOutput->rowCount();
    $indicatorID = $row_rsOutput['indicator'];
    $outputid = $row_rsOutput['outputid'];

    $query_Indicator = $db->prepare("SELECT indicator_name,indicator_unit FROM tbl_indicator WHERE indid ='$indicatorID'");
    $query_Indicator->execute();
    $row = $query_Indicator->fetch();
    $indname = $row['indicator_name'];
    $unitid = $row['indicator_unit'];

    $query_rsOPUnit = $db->prepare("SELECT unit FROM  tbl_measurement_units WHERE id ='$unitid'");
    $query_rsOPUnit->execute();
    $row_rsOPUnit = $query_rsOPUnit->fetch();
    $opunit = $row_rsOPUnit['unit'];

    $query_out = $db->prepare("SELECT * FROM tbl_progdetails WHERE id='$outputid'");
    $query_out->execute();
    $row_out = $query_out->fetch();
    $outputName = $row_out['output'];

    do {
        $result_level = $row_rsLoations['location'];
        $stid = $row_rsLoations['stid'];
        $official_date = $row_rsLoations['mapping_date'];
        $responsible = $row_rsLoations['responsible'];
        $ptid = $row_rsLoations['ptid'];

        $locationName = '';
        if ($result_level != NULL) {
            $query_rsproject_val = $db->prepare("SELECT * FROM tbl_project_results_level_disaggregation WHERE id='$result_level'");
            $result = $query_rsproject_val->execute();
            $row_rsproject_val = $query_rsproject_val->fetch();
            $totalRows_rsproject_val = $query_rsproject_val->rowCount();
            $locationName = 'Output Location: ' . $row_rsproject_val['name'] . '<br>';
        }

        $data = "Mapping,";
        $hashptid = base64_encode($data .  $ptid);
        $hash_projid = base64_encode($data .  $projid);

        // state // forest
        $query_rsComm =  $db->prepare("SELECT id, state FROM tbl_state WHERE id=:projcommunity");
        $query_rsComm->execute(array(":projcommunity" => $stid));
        $row_rsComm = $query_rsComm->fetch();
        $totalRows_rsComm = $query_rsComm->rowCount();
        $state = $row_rsComm['state'];

        // responsible
        $query_rsTeam_responsible = $db->prepare("SELECT *  FROM tbl_projteam2 WHERE ptid=:ptid ");
        $query_rsTeam_responsible->execute(array(":ptid" => $responsible));
        $row_rsTeam_responsible = $query_rsTeam_responsible->fetch();
        $totalRows_rsTeam_responsible = $query_rsTeam_responsible->rowCount();
        $preparationdays = $row_rsTeam_responsible['fullname'];
        $phone = $row_rsTeam_responsible['phone'];


        // receipient 
        $query_rsTeam_members = $db->prepare("SELECT *  FROM tbl_projteam2 WHERE ptid=:ptid ");
        $query_rsTeam_members->execute(array(":ptid" => $ptid));
        $row_rsTeam_members = $query_rsTeam_members->fetch();
        $totalRows_rsTeam_members = $query_rsTeam_members->rowCount();
        $receipient = $row_rsTeam_members['email'];
        $fullname = $row_rsTeam_members['fullname'];
        $mainmessage = '';


        if ($responsible  == $ptid) {
            $mainmessage = '
                        <p>Please note that you have been assigned the mapping team leader for the project detailed below.</p>
                        <p>Project Code:' . $projcode . '<br>
                        Project Name: ' . $projname . '<br>
                        Output: ' . $outputName . '<br>
                        Output Unit of Measure: ' . $opunit . '<br>
                        ' . $level3label . ': ' . $state . '<br> 
                        ' . $locationName . '
                        Mapping Date:' . $official_date . '</p>
                        <p>Prepare the required resources. </p>';
        } else {
            $mainmessage = '<p>Please note that you have been included in the project team as detailed below.</p>
                    <p>Project Code:' . $projcode . '<br>
                    Project Name: ' . $projname . '<br>
                    Project Name: ' . $projname . '<br>
                    Output: ' . $outputName . '<br>
                    Output Unit of Measure: ' . $opunit . '<br>
                     ' . $level3label . ': ' . $state . '<br>
                     ' . $locationName . '
                    Mapping Date:' . $official_date . '</p>
                    <p>Please contact mapping team leader (' . $preparationdays . ': ' . $phone . ') for more info.</p>';
        }


        $detailslink = '<a href="http://34.74.197.215/ug-county/project-mapping.php" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Project Mapping Notification</a>';
        $query_url =  $db->prepare("SELECT * FROM tbl_company_settings");
        $query_url->execute();
        $row_url = $query_url->fetch();
        $url = $row_url["main_url"];
        $org = $row_url["company_name"];
        $org_email = $row_url["email_address"];
        $receipientName = $fullname;

        $subject = "Project Mapping";
        include('../../mapping-assignment-notification-body.php');

        try {
            $query_settings = $db->prepare("SELECT * FROM tbl_email_settings");
            $query_settings->execute();
            $settings = $query_settings->fetch();
            $sender  = $settings["username"];
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host       = $settings["host"];
            $mail->SMTPAuth   = $settings["SMTPAuth"];
            $mail->Username   = $settings["username"];
            $mail->Password   = $settings["password"];
            $mail->SMTPSecure = $settings["SMTPSecure"];
            $mail->Port       = $settings["port"];
            $mail->setFrom($sender, $org);
            $mail->addAddress($receipient, $receipientName);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            $mail->send();
            return true;
        } catch (\Throwable $th) {
            //throw $th;
        }
    } while ($row_rsLoations = $query_rsLoations->fetch());
}

if (isset($_POST['get_locations'])) {
    $opid = $_POST['opid'];
    $projid = $_POST['projid'];
    $data = '';
    $dissagragated = $_POST['dissagragated'];

    if ($dissagragated) {
        $query_rsdissegragations = $db->prepare("SELECT * FROM tbl_output_disaggregation d INNER JOIN tbl_state s ON s.id = d.outputstate WHERE projid=:projid and outputid=:opid ");
        $result = $query_rsdissegragations->execute(array(":projid" => $projid, ":opid" => $opid));
        $row_rsdissegragations = $query_rsdissegragations->fetch();
        $totalRows_rsdissegragations = $query_rsdissegragations->rowCount();

        $data .= '
        <input type="hidden" name="projid" id="projid"   value="' . $projid . '"  />
        <input type="hidden" name="opid" id="opid"   value="' . $opid . '"  />
        <input type="hidden" name="disaggregated_value" id="opid"   value="0" />';
        if ($totalRows_rsdissegragations > 0) {
            $st = 0;
            do {
                $row = 0;
                $st++;
                $opstate = $row_rsdissegragations['outputstate'];
                $forest = $row_rsdissegragations['state'];
                $oprow = $opstate . $st;
                $data .= '
                <tr>
                    <td>' . $st . '</td>
                    <td colspan="4"> <strong>
                        ' . $forest . ' ' . $level3label . ' </strong>
                        <input type="hidden" name="rowno[]" id="rowno"  value="' . $st . '" />
                        <input type="hidden" name="forest[]" id="forest' . $st . '"   value="' . $opstate . '"  />
                    </td>
                </tr>';

                // level4
                $query_rsproject_dissegragations = $db->prepare("SELECT s.disaggregations, s.id, l.target FROM tbl_indicator_level3_disaggregations s INNER JOIN tbl_projects_location_targets l ON l.locationdisid = s.id WHERE l.outputid ='$opid' AND l.projid='$projid' AND l.level3='$opstate'");
                $result = $query_rsproject_dissegragations->execute();
                $row_rsproject_dissegragations = $query_rsproject_dissegragations->fetch();
                $totalRows_rsproject_dissegragations = $query_rsproject_dissegragations->rowCount();

                if ($totalRows_rsproject_dissegragations > 0) {
                    do {
                        $rowno = $opstate . $st . $row;
                        $team = '';


                        $locationName = $row_rsproject_dissegragations['disaggregations'];
                        $result_level = $row_rsproject_dissegragations['id'];
                        $value = $row_rsproject_dissegragations['target'];
                        if ($value > 0) {
                            $row++;
                            $query_rsMembers = $db->prepare("SELECT *  FROM tbl_projmembers WHERE projid=:projid");
                            $query_rsMembers->execute(array(":projid" => $projid));
                            $row_rsMembers = $query_rsMembers->fetch();
                            $totalRows_rsMembers = $query_rsMembers->rowCount();

                            do {
                                $ptid = $row_rsMembers['ptid'];
                                $query_rsTeam = $db->prepare("SELECT *  FROM tbl_projteam2 WHERE ptid=:ptid ");
                                $query_rsTeam->execute(array(":ptid" => $ptid));
                                $row_rsTeam = $query_rsTeam->fetch();
                                $totalRows_rsTeam = $query_rsTeam->rowCount();

                                $team .= '<option value="' . $row_rsTeam['ptid'] . '">' . $row_rsTeam['fullname'] . '</option>';
                            } while ($row_rsMembers = $query_rsMembers->fetch());

                            $data .= '
                            <tr id=""> 
                                <td>' . $st . "." . $row . '</td>
                                <td>
                                    ' . $locationName . '
                                    <input type="hidden" name="result_level' . $oprow . '[]" id="result_level' . $rowno . '" class="form-control" value="' . $result_level . '" style="width:85%; float:right" required />
                                </td>
                                <td>
                                    <input type="hidden" name="lrow' . $oprow . '[]" id="lrow"  value="' . $rowno . '" />
                                    <select name="team' . $rowno . '[]" multiple id="team' . $rowno . '" onchange="get_responsible(' . $rowno . ')" class="form-control selectpicker" data-actions-box="true" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                                            ' . $team . '
                                    </select>
                                </td>
                                <td>
                                    <select name="responsible' . $oprow . '[]" id="responsible' . $rowno . '" class="form-control selectpicker" data-actions-box="true" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                                        <option value="">Select from list</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="date" name="mdate' . $oprow . '[]" id="mdate' . $rowno . '" placeholder="Enter" onchange="validate_date(' . $rowno . ')" class="form-control" style="width:85%; float:right" required />
                                </td>
                            </tr>';
                        }
                    } while ($row_rsproject_dissegragations = $query_rsproject_dissegragations->fetch());
                }
            } while ($row_rsdissegragations = $query_rsdissegragations->fetch());
        }
    } else {
        $query_rsdissegragations = $db->prepare("SELECT * FROM `tbl_output_disaggregation` where projid=:projid and outputid=:opid");
        $result = $query_rsdissegragations->execute(array(":projid" => $projid, ":opid" => $opid));
        $row_rsdissegragations = $query_rsdissegragations->fetch();
        $totalRows_rsdissegragations = $query_rsdissegragations->rowCount();
        $data .= '
        <input type="hidden" name="projid" id="projid"   value="' . $projid . '"  />
        <input type="hidden" name="opid" id="opid"   value="' . $opid . '"  />
        <input type="hidden" name="non_disaggregated_value" id="opid"   value="1" />';
        if ($totalRows_rsdissegragations > 0) {
            $rowno = 0;
            do {
                $rowno++;
                $opstate = $row_rsdissegragations['outputstate'];
                $query_rsForest = $db->prepare("SELECT id, state FROM tbl_state WHERE  id='$opstate' LIMIT 1");
                $query_rsForest->execute();
                $row_rsForest = $query_rsForest->fetch();
                $forest = $row_rsForest['state'];

                $query_rsMembers = $db->prepare("SELECT *  FROM tbl_projmembers WHERE projid=:projid");
                $query_rsMembers->execute(array(":projid" => $projid));
                $row_rsMembers = $query_rsMembers->fetch();
                $totalRows_rsMembers = $query_rsMembers->rowCount();

                $team = '';

                if ($totalRows_rsMembers > 0) {
                    do {
                        $ptid = $row_rsMembers['ptid'];
                        $query_rsTeam = $db->prepare("SELECT *  FROM tbl_projteam2 WHERE ptid=:ptid ");
                        $query_rsTeam->execute(array(":ptid" => $ptid));
                        $row_rsTeam = $query_rsTeam->fetch();
                        $totalRows_rsTeam = $query_rsTeam->rowCount();
                        $team .= '<option value="' . $row_rsTeam['ptid'] . '">' . $row_rsTeam['fullname'] . '</option>';
                    } while ($row_rsMembers = $query_rsMembers->fetch());
                } else {
                    $team .= '<option value="Assign project team first">Assign project team first</option>';
                }

                $data .= '
                <tr id=""> 
                    <td>' . $rowno . '</td>
                    <td>
                        ' . $forest . ' ' . $level3label . ' </strong> 
                        <input type="hidden" name="level3[]" id="forest' . $rowno . '"   value="' . $opstate . '"  />
                    </td>
                    <td>
                        <input type="hidden" name="lrow[]" id="lrow"  value="' . $rowno . '" />
                        <select name="team' . $opstate . '[]" multiple id="team' . $rowno . '" onchange="get_responsible(' . $rowno . ')" class="form-control selectpicker" data-actions-box="true" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                            ' . $team . '
                        </select>
                    </td>
                    <td>
                        <select name="responsible[]" id="responsible' . $rowno . '" class="form-control selectpicker" data-actions-box="true" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                            <option value="">Select from list</option>
                        </select>
                    </td>
                    <td>
                        <input type="date" name="mdate[]" id="mdate' . $rowno . '" placeholder="Enter" onchange="validate_date(' . $rowno . ')" class="form-control" style="width:85%; float:right" required />
                    </td>
                </tr>';
            } while ($row_rsdissegragations = $query_rsdissegragations->fetch());
        }
    }
    echo $data;
}

if (isset($_POST['get_responsible'])) {
    $members =  $_POST['members'];
    $team = '<option value="">... Select from list ...</option>';
    for ($i = 0; $i < count($members); $i++) {
        $ptid = $members[$i];
        if (!empty($ptid)) {
            $query_rsTeam = $db->prepare("SELECT *  FROM tbl_projteam2 WHERE ptid=:ptid ");
            $query_rsTeam->execute(array(":ptid" => $ptid));
            $row_rsTeam = $query_rsTeam->fetch();
            $totalRows_rsTeam = $query_rsTeam->rowCount();
            $team .= '<option value="' . $row_rsTeam['ptid'] . '">' . $row_rsTeam['fullname'] . '</option>';
        }
    }
    echo $team;
}

if (isset($_POST['newitem'])) {
    $valid = [];
    $projid = $_POST['projid'];
    $outputid = $_POST['opid'];


    if (isset($_POST['disaggregated_value'])) {
        $forest = $_POST['forest'];
        $rowno = $_POST['rowno'];
        for ($j = 0; $j < count($forest); $j++) {
            $stid = $forest[$j];
            $st = $stid . $rowno[$j];
            $resp = $_POST['responsible' . $st];
            $mapping_date = $_POST['mdate' . $st];
            $loc = $_POST['result_level' . $st];
            $lrow = $_POST['lrow' . $st];

            for ($pt = 0; $pt < count($resp); $pt++) {
                $locationName = $loc[$pt];
                $responsible = $resp[$pt];
                $official_date = $mapping_date[$pt];
                $lrowno = $lrow[$pt];
                $team = $_POST['team' . $lrowno];
                $ptid = implode(",", $team);
                $insertSQL1 = $db->prepare("INSERT INTO tbl_project_mapping (projid, outputid,location,  ptid, stid, responsible, mapping_date) VALUES(:projid,:outputid,:location, :ptid, :stid, :responsible, :mapping_date)");
                $result1  = $insertSQL1->execute(array(":projid" => $projid, ":outputid" => $outputid, ":location" => $locationName, ":ptid" => $ptid, ":stid" => $stid, ":responsible" => $responsible, ":mapping_date" => $official_date));
            }
        }
    } else if (isset($_POST['non_disaggregated_value'])) {
        $forest = $_POST['level3'];
        $resp = $_POST['responsible'];
        $mapping_date = $_POST['mdate'];

        for ($j = 0; $j < count($forest); $j++) {
            $stid = $forest[$j];
            $responsible = $resp[$j];
            $official_date = $mapping_date[$j];
            $team = $_POST['team' . $stid];
            $ptid = implode(",", $team);
            $insertSQL1 = $db->prepare("INSERT INTO tbl_project_mapping (projid, outputid, ptid, stid, responsible, mapping_date)  VALUES(:projid,:outputid, :ptid, :stid, :responsible, :mapping_date)");
            $result1  = $insertSQL1->execute(array(":projid" => $projid, ":outputid" => $outputid, ":ptid" => $ptid, ":stid" => $stid, ":responsible" => $responsible, ":mapping_date" => $official_date));
        }
    }

    if ($result1 === TRUE) {
        sendMail($db, $projid, $outputid);
        $valid['success'] = true;
        $valid['messages'] = "Successfully Created";
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while creating the record!!";
    }

    echo json_encode($valid);
}

if (isset($_POST['get_details'])) {
    $opid = $_POST['opid'];
    $projid = $_POST['projid'];
    $dissagragated = $_POST['dissagragated'];
    $data = '';

    if ($dissagragated == 1) {
        $query_rsdissegragations = $db->prepare("SELECT * FROM tbl_output_disaggregation d INNER JOIN tbl_state s ON s.id = d.outputstate WHERE outputid='$opid'  and projid='$projid' ");
        $result = $query_rsdissegragations->execute();
        $row_rsdissegragations = $query_rsdissegragations->fetch();
        $totalRows_rsdissegragations = $query_rsdissegragations->rowCount();

        if ($totalRows_rsdissegragations > 0) {
            $st = 0;
            $data .= '
            <input type="hidden" name="projid" id="projid"   value="' . $projid . '"  />
            <input type="hidden" name="opid" id="opid"   value="' . $opid . '"  />
            <input type="hidden" name="disaggregated_value" id="opid"   value="1" />';
            $st = 0;
            do {
                $row = 0;
                $st++;
                $forest = $row_rsdissegragations['state'];
                $opstate = $row_rsdissegragations['outputstate'];
                $oprow = $opstate . $st;

                $data .= '
                <tr>
                    <td>' . $st . '</td>
                    <td colspan="4"> <strong>
                        ' . $forest . ' </strong>
                        <input type="hidden" name="rowno[]" id="rowno"  value="' . $st . '" />
                        <input type="hidden" name="forest[]" id="forest' . $st . '"   value="' . $opstate . '"  />
                    </td>
                </tr>';

                $query_rs_locations = $db->prepare("SELECT * FROM tbl_project_mapping  WHERE outputid='$opid' AND projid='$projid' AND stid='$opstate'");
                $query_rs_locations->execute();
                $row_rs_locations = $query_rs_locations->fetch();

                do {
                    $row++;
                    $rowno = $opstate . $st . $row;
                    $result_level = $row_rs_locations['location'];
                    $responsible = $row_rs_locations['responsible'];
                    $mdate = $row_rs_locations['mapping_date'];
                    $ptid = explode(",", $row_rs_locations['ptid']);

                    $opteam = [];
                    for ($jp = 0; $jp < count($ptid); $jp++) {
                        $opteam[] = $ptid[$jp];
                    }

                    $query_rsproject_val = $db->prepare("SELECT * FROM tbl_indicator_level3_disaggregations WHERE id='$result_level'");
                    $result = $query_rsproject_val->execute();
                    $row_rsproject_val = $query_rsproject_val->fetch();
                    $totalRows_rsproject_val = $query_rsproject_val->rowCount();
                    $locationName = $row_rsproject_val['disaggregations'];

                    $resp_team = '';
                    for ($k = 0; $k < count($opteam); $k++) {
                        $query_rsResponsible = $db->prepare("SELECT *  FROM tbl_projteam2 WHERE ptid=:ptid");
                        $query_rsResponsible->execute(array(":ptid" => $opteam[$k]));
                        $row_rsResponsible = $query_rsResponsible->fetch();
                        $totalRows_rsResponsible = $query_rsResponsible->rowCount();

                        if ($row_rsResponsible['ptid'] == $responsible) {
                            $resp_team .= '<option value="' . $row_rsResponsible['ptid'] . '" selected>' . $row_rsResponsible['fullname'] . '</option>';
                        } else {
                            $resp_team .= '<option value="' . $row_rsResponsible['ptid'] . '">' . $row_rsResponsible['fullname'] . '</option>';
                        }
                    }

                    $team = '';
                    $query_rsMembers = $db->prepare("SELECT *  FROM tbl_projmembers WHERE projid=:projid");
                    $query_rsMembers->execute(array(":projid" => $projid));
                    $row_rsMembers = $query_rsMembers->fetch();
                    $totalRows_rsMembers = $query_rsMembers->rowCount();
                    $wild = '';

                    do {
                        $ptid = $row_rsMembers['ptid'];
                        $query_rs_team = $db->prepare("SELECT * FROM tbl_projteam2 WHERE ptid=:ptid ");
                        $query_rs_team->execute(array(":ptid" => $ptid));
                        $row_rs_team = $query_rs_team->fetch();
                        $totalRows_rsTeam = $query_rs_team->rowCount();
                        $fullname = $row_rs_team['fullname'];

                        if (in_array($row_rsMembers['ptid'], $opteam)) {
                            $wild .= '<option value="' . $ptid . '" selected>' . $fullname . '</option>';
                        } else {
                            $wild .= '<option value="' . $ptid . '">' . $fullname . '</option>';
                        }
                    } while ($row_rsMembers = $query_rsMembers->fetch());


                    $data .= '
                    <tr id=""> 
                        <td>' . $st . "." . $row . '</td>
                        <td>
                            ' . $locationName . '
                            <input type="hidden" name="result_level' . $oprow . '[]" id="result_level' . $rowno . '" class="form-control" value="' . $result_level . '" style="width:85%; float:right" required />
                            </td>
                        <td>
                            <input type="hidden" name="lrow' . $oprow . '[]" id="lrow"  value="' . $rowno . '" />
                            <select name="team' . $rowno . '[]" multiple id="team' . $rowno . '" onchange="get_responsible(' . $rowno . ')" class="form-control selectpicker" data-actions-box="true" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                                    ' . $wild . '
                                </select>
                            </td>
                        <td>
                            <select name="responsible' . $oprow . '[]" id="responsible' . $rowno . '" class="form-control selectpicker" data-actions-box="true" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                                <option value="">Select from list</option>resp
                                ' . $resp_team . '
                            </select>
                        </td>
                        <td>
                            <input type="date" name="mdate' . $oprow . '[]" value="' . $mdate . '" id="mdate' . $rowno . '" placeholder="Enter" onchange="validate_date(' . $rowno . ')" class="form-control" style="width:85%; float:right" required />
                        </td>
                    </tr>';
                } while ($row_rs_locations = $query_rs_locations->fetch());
            } while ($row_rsdissegragations = $query_rsdissegragations->fetch());
        }
    } else {

        $query_rs_locations = $db->prepare("SELECT * FROM tbl_project_mapping WHERE outputid='$opid' AND projid='$projid' ");
        $query_rs_locations->execute();
        $row_rs_locations = $query_rs_locations->fetch();
        $totalrow_rs_locations = $query_rs_locations->rowCount();

        if ($totalrow_rs_locations > 0) {
            $rowno = 0;
            $data .= '
            <input type="hidden" name="projid" id="projid"   value="' . $projid . '"  />
            <input type="hidden" name="opid" id="opid"   value="' . $opid . '"  />
            <input type="hidden" name="non_disaggregated_value" id="opid"   value="0" />';

            do {
                $rowno++;
                $projid = $row_rs_locations['projid'];
                $stid = $row_rs_locations['stid'];
                $opid = $row_rs_locations['outputid'];
                $responsible = $row_rs_locations['responsible'];
                $mapping_date = $row_rs_locations['mapping_date'];
                $ptid = explode(",", $row_rs_locations['ptid']);

                $opteam = [];
                for ($jp = 0; $jp < count($ptid); $jp++) {
                    $opteam[] = $ptid[$jp];
                }
                $resp_team = '';
                for ($k = 0; $k < count($opteam); $k++) {
                    $query_rsResponsible = $db->prepare("SELECT *  FROM tbl_projteam2 WHERE ptid=:ptid");
                    $query_rsResponsible->execute(array(":ptid" => $opteam[$k]));
                    $row_rsResponsible = $query_rsResponsible->fetch();
                    $totalRows_rsResponsible = $query_rsResponsible->rowCount();

                    if ($row_rsResponsible['ptid'] == $responsible) {
                        $resp_team .= '<option value="' . $row_rsResponsible['ptid'] . '" selected>' . $row_rsResponsible['fullname'] . '</option>';
                    } else {
                        $resp_team .= '<option value="' . $row_rsResponsible['ptid'] . '">' . $row_rsResponsible['fullname'] . '</option>';
                    }
                }

                $query_rsMembers = $db->prepare("SELECT *  FROM tbl_projmembers WHERE projid=:projid");
                $query_rsMembers->execute(array(":projid" => $projid));
                $row_rsMembers = $query_rsMembers->fetch();
                $totalRows_rsMembers = $query_rsMembers->rowCount();

                $wild = '';
                do {
                    $ptid = $row_rsMembers['ptid'];
                    $query_rs_team = $db->prepare("SELECT * FROM tbl_projteam2 WHERE ptid=:ptid ");
                    $query_rs_team->execute(array(":ptid" => $ptid));
                    $row_rs_team = $query_rs_team->fetch();
                    $totalRows_rsTeam = $query_rs_team->rowCount();
                    $fullname = $row_rs_team['fullname'];

                    if (in_array($row_rsMembers['ptid'], $opteam)) {
                        $wild .= '<option value="' . $ptid . '" selected>' . $fullname . '</option>';
                    } else {
                        $wild .= '<option value="' . $ptid . '">' . $fullname . '</option>';
                    }
                } while ($row_rsMembers = $query_rsMembers->fetch());

                $data .= '
                <tr id=""> 
                    <td>' . $rowno . '</td>
                    <td>
                        ' . $stid . ' ' . $level3label . ' </strong>
                        <input type="hidden" name="level3[]" id="forest' . $rowno . '"   value="' . $stid . '"  />
                    </td>
                    <td>
                        <input type="hidden" name="lrow[]" id="lrow"  value="' . $rowno . '" />
                        <select name="team' . $stid . '[]" multiple id="team' . $rowno . '" onchange="get_responsible(' . $rowno . ')" class="form-control selectpicker" data-actions-box="true" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                            ' . $wild . '
                        </select>
                    </td>
                    <td>
                        <select name="responsible[]" id="responsible' . $rowno . '" class="form-control selectpicker" data-actions-box="true" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                            <option value="">Select from list</option>
                            ' . $resp_team . '
                        </select>
                    </td>
                    <td>
                        <input type="date" name="mdate[]" id="mdate' . $rowno . '" placeholder="Enter" onchange="validate_date(' . $rowno . ')" class="form-control" style="width:85%; float:right" required value="' . $mapping_date . '" />
                    </td>
                </tr>';
            } while ($row_rs_locations = $query_rs_locations->fetch());
        }
    }
    echo $data;
}


if (isset($_POST['edititem'])) {
    $valid = [];
    $projid = $_POST['projid'];
    $outputid = $_POST['opid'];
    $deleteQuery = $db->prepare("DELETE FROM `tbl_project_mapping` WHERE projid=:projid AND outputid=:outputid");
    $results = $deleteQuery->execute(array(':projid' => $projid, ":outputid" => $outputid));


    if ($results) {
        $result1 = [];
        $valid = [];
        $projid = $_POST['projid'];
        $outputid = $_POST['opid'];
        if (isset($_POST['disaggregated_value'])) {
            $forest = $_POST['forest'];
            $rowno = $_POST['rowno'];

            for ($j = 0; $j < count($forest); $j++) {
                $stid = $forest[$j];
                $st = $stid . $rowno[$j];
                $resp = $_POST['responsible' . $st];
                $mapping_date = $_POST['mdate' . $st];
                $loc = $_POST['result_level' . $st];
                $lrow = $_POST['lrow' . $st];

                for ($pt = 0; $pt < count($resp); $pt++) {
                    $locationName = $loc[$pt];
                    $responsible = $resp[$pt];
                    $official_date = $mapping_date[$pt];
                    $lrowno = $lrow[$pt];
                    $team = $_POST['team' . $lrowno];
                    $ptid = implode(",", $team);
                    $insertSQL1 = $db->prepare("INSERT INTO tbl_project_mapping (projid, outputid,location,  ptid, stid, responsible, mapping_date) VALUES(:projid,:outputid,:location, :ptid, :stid, :responsible, :mapping_date)");
                    $result1  = $insertSQL1->execute(array(":projid" => $projid, ":outputid" => $outputid, ":location" => $locationName, ":ptid" => $ptid, ":stid" => $stid, ":responsible" => $responsible, ":mapping_date" => $official_date));
                }
            }
        } else if (isset($_POST['non_disaggregated_value'])) {
            $forest = $_POST['level3'];
            $resp = $_POST['responsible'];
            $mapping_date = $_POST['mdate'];

            for ($j = 0; $j < count($forest); $j++) {
                $stid = $forest[$j];
                $responsible = $resp[$j];
                $official_date = $mapping_date[$j];
                $team = $_POST['team' . $stid];
                $ptid = implode(",", $team);
                $insertSQL1 = $db->prepare("INSERT INTO tbl_project_mapping (projid, outputid, ptid, stid, responsible, mapping_date)  VALUES(:projid,:outputid, :ptid, :stid, :responsible, :mapping_date)");
                $result1  = $insertSQL1->execute(array(":projid" => $projid, ":outputid" => $outputid, ":ptid" => $ptid, ":stid" => $stid, ":responsible" => $responsible, ":mapping_date" => $official_date));
            }
        }

        if ($result1 === TRUE) {
            sendMail($db, $projid, $outputid);
            $valid['success'] = true;
            $valid['messages'] = "Successfully Updated";
        } else {
            $valid['success'] = false;
            $valid['messages'] = "Error while creating the record!!";
        }
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while creating the record!!";
    }
    echo json_encode($valid);
}

if (isset($_POST['get_more'])) {
    $opid = $_POST['opid'];
    $projid = $_POST['projid'];
    $dissagragated = $_POST['dissagragated'];
    $data = '';

    if ($dissagragated == 1) {
        $query_rsdissegragations = $db->prepare("SELECT * FROM tbl_output_disaggregation d INNER JOIN tbl_state s ON s.id = d.outputstate WHERE outputid='$opid'");
        $result = $query_rsdissegragations->execute();
        $row_rsdissegragations = $query_rsdissegragations->fetch();
        $totalRows_rsdissegragations = $query_rsdissegragations->rowCount();

        if ($totalRows_rsdissegragations > 0) {
            $st = 0;
            do {
                $row = 0;
                $st++;
                $opstate = $row_rsdissegragations['outputstate'];
                $forest = $row_rsdissegragations['state'];
                $oprow = $opstate . $st;
                $data .= '
                <tr>
                    <td>' . $st . '</td>
                    <td colspan="4"> <strong>' . $forest . ' </strong> 
                    </td>
                </tr>';

                $query_rs_locations = $db->prepare("SELECT * FROM tbl_project_mapping WHERE outputid='$opid' AND projid='$projid' AND stid='$opstate' ");
                $query_rs_locations->execute();
                $row_rs_locations = $query_rs_locations->fetch();
                do {

                    $row++;
                    $result_level = $row_rs_locations['location'];

                    $responsible = $row_rs_locations['responsible'];
                    $mdate = $row_rs_locations['mapping_date'];
                    $ptid = explode(",", $row_rs_locations['ptid']);


                    // $opteam = [];
                    // for($jp =0; $jp< count($ptid); $jp++){
                    //     $opteam[] = $ptid[$jp];
                    // }

                    $query_rsproject_val = $db->prepare("SELECT * FROM tbl_indicator_level3_disaggregations WHERE id='$result_level'");
                    $result = $query_rsproject_val->execute();
                    $row_rsproject_val = $query_rsproject_val->fetch();
                    $totalRows_rsproject_val = $query_rsproject_val->rowCount();
                    $locationName = $row_rsproject_val['disaggregations'];

                    $query_rsResponsible = $db->prepare("SELECT *  FROM tbl_projteam2 WHERE ptid=:ptid");
                    $query_rsResponsible->execute(array(":ptid" => $responsible));
                    $row_rsResponsible = $query_rsResponsible->fetch();
                    $totalRows_rsResponsible = $query_rsResponsible->rowCount();
                    $resp = $row_rsResponsible['fullname'];

                    $names = [];
                    for ($jp = 0; $jp < count($ptid); $jp++) {
                        $team = $ptid[$jp];
                        $query_rsMembers = $db->prepare("SELECT *  FROM tbl_projteam2 WHERE ptid=:ptid");
                        $query_rsMembers->execute(array(":ptid" => $team));
                        $row_rsMembers = $query_rsMembers->fetch();
                        $totalRows_rsMembers = $query_rsMembers->rowCount();
                        $names[] = $row_rsMembers['fullname'];
                    }

                    $data .= '
                    <tr id=""> 
                        <td>' . $st . "." . $row . '</td>
                        <td>' . $locationName . '</td>
                        <td>' . $resp . '</td>
                        <td>' . implode(",", $names) . '</td>
                        <td>' .  date("d M Y", strtotime($mdate)) . '</td>
                    </tr>';
                } while ($row_rs_locations = $query_rs_locations->fetch());
            } while ($row_rsdissegragations = $query_rsdissegragations->fetch());
        }
    } else {
        $query_rs_locations = $db->prepare("SELECT * FROM tbl_project_mapping WHERE outputid='$opid' AND projid='$projid' ");
        $query_rs_locations->execute();
        $row_rs_locations = $query_rs_locations->fetch();
        $totalrow_rs_locations = $query_rs_locations->rowCount();

        if ($totalrow_rs_locations > 0) {
            $rowno = 0;
            do {
                $rowno++;
                $projid = $row_rs_locations['projid'];
                $stid = $row_rs_locations['stid'];
                $opid = $row_rs_locations['outputid'];
                $responsible = $row_rs_locations['responsible'];
                $mapping_date = $row_rs_locations['mapping_date'];
                $ptid = explode(",", $row_rs_locations['ptid']);



                $query_rsForest = $db->prepare("SELECT id, state FROM tbl_state WHERE  id='$stid' LIMIT 1");
                $query_rsForest->execute();
                $row_rsForest = $query_rsForest->fetch();
                $forest = $row_rsForest['state'];

                $query_rsResponsible = $db->prepare("SELECT *  FROM tbl_projteam2 WHERE ptid=:ptid");
                $query_rsResponsible->execute(array(":ptid" => $responsible));
                $row_rsResponsible = $query_rsResponsible->fetch();
                $totalRows_rsResponsible = $query_rsResponsible->rowCount();
                $resp = $row_rsResponsible['fullname'];

                $names = [];
                $ptid_arr = [];
                for ($jp = 0; $jp < count($ptid); $jp++) {
                    $team = $ptid[$jp];
                    $query_rsMembers = $db->prepare("SELECT *  FROM tbl_projteam2 WHERE ptid=:ptid");
                    $query_rsMembers->execute(array(":ptid" => $team));
                    $row_rsMembers = $query_rsMembers->fetch();
                    $totalRows_rsMembers = $query_rsMembers->rowCount();
                    $names[] = $row_rsMembers['fullname'];
                }

                $data .= '
                <tr id=""> 
                    <td>' . $rowno . '</td>
                    <td>
                       ' . $forest . '
                    </td>
                    <td>
                         ' . implode(",", $names) . '
                    </td>
                    <td>
                         ' . $resp . '
                    </td>
                    <td>
                        ' . $mapping_date . '
                    </td>
                </tr>';
            } while ($row_rs_locations = $query_rs_locations->fetch());
        }
    }
    echo $data;
}

// delete mapping details for a specific output
if (isset($_POST["deleteItem"])) {
    $itemid = $_POST['itemId'];
    $projid = $_POST['projid'];
    $deleteQuery = $db->prepare("DELETE FROM `tbl_project_mapping` WHERE projid=:projid AND outputid=:outputid");
    $results = $deleteQuery->execute(array(':projid' => $projid, ":outputid" => $itemid));

    if ($results === TRUE) {
        $valid['success'] = true;
        $valid['messages'] = "Successfully Deleted";
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while deletng the record!!";
    }
    echo json_encode($valid);
}

// delete all mapping details for a whole project 
if (isset($_POST["deleteItems"])) {
    $projid = $_POST['projid'];
    $deleteQuery = $db->prepare("DELETE FROM `tbl_project_mapping` WHERE projid=:projid ");
    $results = $deleteQuery->execute(array(':projid' => $projid));

    if ($results === TRUE) {
        $valid['success'] = true;
        $valid['messages'] = "Successfully Deleted";
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while deletng the record!!";
    }
    echo json_encode($valid);
}


if (isset($_GET['more'])) {
    $mapping_id = $_GET['mapid'];
    $query_rsMap = $db->prepare("SELECT *  FROM tbl_project_mapping WHERE id=:mapping_id ");
    $query_rsMap->execute(array(":mapping_id" => $mapping_id));
    $row_rsMap = $query_rsMap->fetch();
    $totalRows_rsMap = $query_rsMap->rowCount();

    $responsible = $row_rsMap['responsible'];
    $projid = $row_rsMap['projid'];
    $opid = $row_rsMap['outputid'];
    $location = $row_rsMap['location'];
    $stid = $row_rsMap['stid'];
    $mapping_date = $row_rsMap['mapping_date'];
    $ptid = explode(",", $row_rsMap['ptid']);
    $user_name = 10;

    $locationName = '';

    if ($location != 0) {
        $query_rsComm =  $db->prepare("SELECT id, name FROM tbl_project_results_level_disaggregation WHERE id='$location'");
        $query_rsComm->execute();
        $row_rsComm = $query_rsComm->fetch();
        $totalRows_rsComm = $query_rsComm->rowCount();
        $name = $row_rsComm['name'];
        $locationName = $name;
    }

    $query_rsProjects = $db->prepare("SELECT *  FROM tbl_projects WHERE deleted='0' and projid=:projid");
    $query_rsProjects->execute(array(":projid" => $projid));
    $row_rsProjects = $query_rsProjects->fetch();
    $totalRows_rsProjects = $query_rsProjects->rowCount();
    $projname = $row_rsProjects['projname'];
    $projcode = $row_rsProjects['projcode'];


    $query_rsOutput =  $db->prepare("SELECT * FROM tbl_project_details WHERE id ='$opid'  ORDER BY id ASC");
    $query_rsOutput->execute();
    $row_rsOutput = $query_rsOutput->fetch();
    $totalRows_rsOutput = $query_rsOutput->rowCount();
    $indicatorID = $row_rsOutput['indicator'];
    $outputid = $row_rsOutput['outputid'];
    $mapping_type = $row_rsOutput['mapping_type'];

    $query_Indicator = $db->prepare("SELECT indicator_name,indicator_unit FROM tbl_indicator WHERE indid ='$indicatorID'");
    $query_Indicator->execute();
    $row = $query_Indicator->fetch();
    $indname = $row['indicator_name'];
    $unitid = $row['indicator_unit'];

    $query_rsOPUnit = $db->prepare("SELECT unit FROM  tbl_measurement_units WHERE id ='$unitid'");
    $query_rsOPUnit->execute();
    $row_rsOPUnit = $query_rsOPUnit->fetch();
    $opunit = $row_rsOPUnit['unit'];

    $query_out = $db->prepare("SELECT * FROM tbl_progdetails WHERE id='$outputid'");
    $query_out->execute();
    $row_out = $query_out->fetch();
    $rowCount = $query_out->rowCount();
    $outputName = $rowCount > 0 ? $row_out['output'] : "";

    // get the state location 
    $query_rsForest =  $db->prepare("SELECT id, state FROM tbl_state WHERE id=:projstate");
    $query_rsForest->execute(array(":projstate" => $stid));
    $row_rsForest = $query_rsForest->fetch();
    $totalRows_rsForest = $query_rsForest->rowCount();
    $map_state = $row_rsForest['state'];
    $names = [];
    for ($jp = 0; $jp < count($ptid); $jp++) {
        $team = $ptid[$jp];
        $query_rsMembers = $db->prepare("SELECT *  FROM tbl_projteam2 WHERE ptid=:ptid");
        $query_rsMembers->execute(array(":ptid" => $team));
        $row_rsMembers = $query_rsMembers->fetch();
        $totalRows_rsMembers = $query_rsMembers->rowCount();
        $names[] = $row_rsMembers['fullname'];
    }


    $query_rsResponsible = $db->prepare("SELECT *  FROM tbl_projteam2 WHERE ptid=:ptid");
    $query_rsResponsible->execute(array(":ptid" => $responsible));
    $row_rsResponsible = $query_rsResponsible->fetch();
    $totalRows_rsResponsible = $query_rsResponsible->rowCount();
    $responsible = $row_rsResponsible['fullname'];


    $location =  ($locationName != "") ? $map_state . "(" . $locationName . ")" :  $map_state;
    $data = '
       <tr id="">
          <td>1</td>
          <td>
             ' .  $location . '
          </td>
          <td>
                ' . $responsible . '
          </td>
          <td>
                ' . implode(",", $names) . '
          </td>
          <td>
             ' . $mapping_date . '
          </td>
       </tr>';

    echo $data;
}

if (isset($_GET['get_coordinates'])) {
    $mapping_id = $_GET['mapid'];
    try {
        $query_markers = $db->prepare("SELECT * FROM tbl_markers WHERE mapid=:mapping_id");
        $query_markers->execute(array("mapping_id" => $mapping_id, "opid" => $outputid));
        $total_markers = $query_markers->rowCount();
        if ($total_markers > 0) {
            while ($row = $query_markers->fetch()) {
                $items = array($row['lat'], $row['lng']);
                $data[] = $items;
            }
            echo json_encode(array("msg" => true, "markers" => $data));
        }
    } catch (PDOException $ex) {
        return array("msg" => true, "results" => $ex->getMessage());
    }
}
