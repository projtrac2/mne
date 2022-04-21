<?php
include_once "controller.php";

require '../../PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer;

try {
    $valid['success'] = array('success' => false, 'messages' => array());
    // function check if location has been assiged 
    function projstate_handler($db, $state, $projid)
    {
        $query_rsMap = $db->prepare("SELECT * FROM tbl_project_mapping WHERE projid=:projid and stid=:state");
        $query_rsMap->execute(array(":state" => $state, ":projid" => $projid));
        $row_rsMap = $query_rsMap->fetch();
        $totalRows_rsMap = $query_rsMap->rowCount();

        if ($totalRows_rsMap > 0) {
            return false;
        } else {
            return true;
        }
    }

    function sendMail($db, $mail, $responsible, $official_date, $stid, $team_members, $projid)
    {

        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid=:projid");
        $query_rsProjects->execute(array(":projid" => $projid));
        $row_rsProjects = $query_rsProjects->fetch();
        $projname = $row_rsProjects['projname'];
        $projcode = $row_rsProjects['projcode'];


        $query_rsTeam_responsible = $db->prepare("SELECT * FROM tbl_projteam2 WHERE ptid=:ptid ");
        $query_rsTeam_responsible->execute(array(":ptid" => $responsible));
        $row_rsTeam_responsible = $query_rsTeam_responsible->fetch();
        $totalRows_rsTeam_responsible = $query_rsTeam_responsible->rowCount();
        $preparationdays = $row_rsTeam_responsible['fullname'];
        $phone = $row_rsTeam_responsible['phone'];
	
		$query_org =  $db->prepare("SELECT * FROM tbl_company_settings");
		$query_org->execute();		
		$row_org = $query_org->fetch();
		
		$mainurl = $row_org["main_url"];

        for ($i = 0; $i < count($team_members); $i++) {
            $ptid = $team_members[$i];
            $query_rsTeam_members = $db->prepare("SELECT * FROM tbl_projteam2 WHERE ptid=:ptid ");
            $query_rsTeam_members->execute(array(":ptid" => $ptid));
            $row_rsTeam_members = $query_rsTeam_members->fetch();
            $totalRows_rsTeam_members = $query_rsTeam_members->rowCount();
            $receipient = $row_rsTeam_members['email'];
            $fullname = $row_rsTeam_members['fullname'];

            $query_rsComm =  $db->prepare("SELECT id, state FROM tbl_state WHERE id=:projcommunity");
            $query_rsComm->execute(array(":projcommunity" => $stid));
            $row_rsComm = $query_rsComm->fetch();
            $totalRows_rsComm = $query_rsComm->rowCount();
            $state = $row_rsComm['state'];

            $mainmessage = '';

            if ($responsible  == $ptid) {
                $subject = "Project Mapping";
                $mainmessage = '<p>Please note that you have been assigned the mapping team leader for the project detailed below.</p><p>Project Code:
                     ' . $projcode . '<br>Project Name: ' . $projname . '<br>Project Location: ' . $state . '<br> Mapping Date:' . $official_date . '</p><p>Prepare the required resources. </p>';
                $projlinktitle = 'Project Mapping Application';
            } else {
                $subject = "Project Mapping";
                $mainmessage = '<p>Please note that you have been included in the project team as detailed below.</p><p>Project Code:
                 ' . $projcode . '<br>Project Name: ' . $projname . '<br>Project Location: ' . $state . '<br> Mapping Date:' . $official_date . '</p><p>Please contact mapping team leader (' . $preparationdays . ': ' . $phone . ') for more info.</p>';
                $projlinktitle = 'Project Mapping Application';
            }

            $password = "softcimes@2018"; //email password
            $sender = "info@projtrac.co.ke"; //senders name  
            $senderName = "Projtrac Systems Ltd"; //senders name
			
            try {
                //Server settings
                // $mail->SMTPDebug = 2;
                $mail->isSMTP();
                $mail->Host       = 'mail.projtrac.co.ke';
                $mail->SMTPAuth   = true;
                $mail->Username   = $sender;
                $mail->Password   = $password;
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;
                // receipient 
                $mail->setFrom($sender, $senderName); //set senders name 
                $mail->addAddress($receipient);    //set the receipients of the email 

                $detailslink = '<a href="http:'.$mainurl.'add-map.php?user=' . $ptid . '" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Project Mapping Notification</a>';
                include('../../mapping-assignment-notification-body.php');
                $mail->isHTML(true);
                $mail->Subject = 'PROJECT MAPPING';
                $mail->Body    = $body;
                $mail->send();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }


    if (isset($_POST['get_coordinates'])) {
        $query_rsCoordinates = $db->prepare("SELECT latitude, longitude  FROM tbl_company_settings");
        $query_rsCoordinates->execute();
        $row_rsCoordinates = $query_rsCoordinates->fetch();
        $totalRows_rsCoordinates = $query_rsCoordinates->rowCount();
        if ($totalRows_rsCoordinates > 0) {
            $msg = array("msg" => true);
            $data =  array_merge($row_rsCoordinates, $msg);
            echo json_encode($data);
        } else {
            $msg = array("msg" => false);
            echo json_encode($msg);
        }
    }

    if (isset($_POST['get_edit'])) {
        $projid = $_POST['projid'];
        $query_rsMap = $db->prepare("SELECT * FROM tbl_project_mapping WHERE projid=:projid GROUP BY stid");
        $query_rsMap->execute(array(":projid" => $projid));
        $row_rsMap = $query_rsMap->fetch();
        $totalRows_rsMap = $query_rsMap->rowCount();

        $rowno = 0;
        $data = '
        <tr></tr>';
        $hids = [];
        if ($totalRows_rsMap > 0) {
            do {
                $rowno++;
                $mapping_date = $row_rsMap['mapping_date'];
                $responsible = $row_rsMap['responsible'];
                $compare = strtotime($row_rsMap['mapping_date']);
                $stid = $row_rsMap['stid'];
                $hid = $row_rsMap['id'];
                $today = strtotime(date("Y-m-d"));

                $query_rsMapped = $db->prepare("SELECT * FROM tbl_markers WHERE stid=:state");
                $query_rsMapped->execute(array(":state" => $stid));
                $row_rsMapped = $query_rsMapped->fetch();
                $totalRows_rsMapped = $query_rsMapped->rowCount();

                $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid=:projid");
                $query_rsProjects->execute(array(":projid" => $projid));
                $row_rsProjects = $query_rsProjects->fetch();
                $totalRows_rsProjects = $query_rsProjects->rowCount();
                $projlocation = $row_rsProjects['projlocation'];
                $projcommunity_val = $row_rsProjects['projcommunity'];

                $ecosystem = explode(",", $row_rsProjects['projlga']);
                $forest = explode(",", $row_rsProjects['projstate']);
                $forest_option = '<option value="">... Select from list ...</option>';

                if ($projlocation != null) {
                    $query_rsComm =  $db->prepare("SELECT id, state FROM tbl_state WHERE id=:projcommunity");
                    $query_rsComm->execute(array(":projcommunity" => $projcommunity_val));
                    $row_rsComm = $query_rsComm->fetch();
                    $totalRows_rsComm = $query_rsComm->rowCount();

                    // check if the ecosystems exists 
                    $query_rsProjlga =  $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:projcommunity");
                    $query_rsProjlga->execute(array(":projcommunity" => $projcommunity));
                    $row_rsProjlga = $query_rsProjlga->fetch();
                    $totalRows_rsProjlga = $query_rsProjlga->rowCount();

                    if ($totalRows_rsProjlga > 0) {
                        do {
                            // check if the forests exists 
                            $projlga = $row_rsProjlga['id'];
                            $ward = $row_rsProjlga['state'];
                            $community = $row_rsComm['state'];
                            if (in_array($projlga, $ecosystem)) {
                                $forest_option .= '<optgroup label="' . $ward . ' (' .  $community . ')">';
                                $query_rsProjstate =  $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:projlga");
                                $query_rsProjstate->execute(array(":projlga" => $projlga));
                                $row_rsProjstate = $query_rsProjstate->fetch();
                                $totalRows_rsProjstate = $query_rsProjstate->rowCount();
                                if ($totalRows_rsProjstate > 0) {
                                    do {
                                        $state = $row_rsProjstate['id'];
                                        // ensure if the location has been mapped does not reflect
                                        if (in_array($state, $forest)) {
                                            $handler = projstate_handler($db, $state, $projid);
                                            if ($state == $stid) {
                                                $forest_option .= '<option value="' . $row_rsProjstate['id'] . '" selected>' . $row_rsProjstate['state'] . '</option>';
                                            } else {
                                                $forest_option .= '<option value="' . $row_rsProjstate['id'] . '">' . $row_rsProjstate['state'] . '</option>';
                                            }
                                        }
                                    } while ($row_rsProjstate = $query_rsProjstate->fetch());
                                }
                                $forest_option .= '<optgroup>';
                            }
                        } while ($row_rsProjlga = $query_rsProjlga->fetch());
                    }
                } else {
                    $projcommunity_arr = explode(",", $projcommunity_val);
                    $conservancy = [];

                    for ($i = 0; $i < count($projcommunity_arr); $i++) {
                        // loop through the communities 
                        $projcommunity = $projcommunity_arr[$i];
                        $query_rsComm =  $db->prepare("SELECT id, state FROM tbl_state WHERE id=:projcommunity");
                        $query_rsComm->execute(array(":projcommunity" => $projcommunity));
                        $row_rsComm = $query_rsComm->fetch();
                        $totalRows_rsComm = $query_rsComm->rowCount();

                        // check if the ecosystems exists 
                        $query_rsProjlga =  $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:projcommunity");
                        $query_rsProjlga->execute(array(":projcommunity" => $projcommunity));
                        $row_rsProjlga = $query_rsProjlga->fetch();
                        $totalRows_rsProjlga = $query_rsProjlga->rowCount();

                        if ($totalRows_rsProjlga > 0) {
                            do {
                                // check if the forests exists 
                                $projlga = $row_rsProjlga['id'];
                                $ward = $row_rsProjlga['state'];
                                $community = $row_rsComm['state'];
                                if (in_array($projlga, $ecosystem)) {
                                    $forest_option .= '<optgroup label="' . $ward . ' (' .  $community . ')">';
                                    $query_rsProjstate =  $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:projlga");
                                    $query_rsProjstate->execute(array(":projlga" => $projlga));
                                    $row_rsProjstate = $query_rsProjstate->fetch();
                                    $totalRows_rsProjstate = $query_rsProjstate->rowCount();
                                    if ($totalRows_rsProjstate > 0) {
                                        do {
                                            $state = $row_rsProjstate['id'];
                                            // ensure if the location has been mapped does not reflect
                                            if (in_array($state, $forest)) {
                                                if ($state == $stid) {
                                                    $forest_option .= '<option value="' . $row_rsProjstate['id'] . '" selected>' . $row_rsProjstate['state'] . '</option>';
                                                } else {
                                                    $forest_option .= '<option value="' . $row_rsProjstate['id'] . '">' . $row_rsProjstate['state'] . '</option>';
                                                }
                                            }
                                        } while ($row_rsProjstate = $query_rsProjstate->fetch());
                                    }
                                    $forest_option .= '<optgroup>';
                                }
                            } while ($row_rsProjlga = $query_rsProjlga->fetch());
                        }
                    }
                }

                $query_rsteam = $db->prepare("SELECT * FROM tbl_project_mapping WHERE projid=:projid AND stid=:stid");
                $query_rsteam->execute(array(":projid" => $projid, ":stid" => $stid));
                $row_rsteam = $query_rsteam->fetch();
                $totalRows_rsteam = $query_rsteam->rowCount();
                $team_members = [];
                $team_member = '';
                do {
                    $team_members[] = $row_rsteam['ptid'];
                    $ptid = $row_rsteam['ptid'];
                    $query_rsTeam_members = $db->prepare("SELECT * FROM tbl_projteam2 WHERE ptid=:ptid ");
                    $query_rsTeam_members->execute(array(":ptid" => $ptid));
                    $row_rsTeam_members = $query_rsTeam_members->fetch();
                    $totalRows_rsTeam_members = $query_rsTeam_members->rowCount();

                    if ($responsible == $ptid) {
                        $team_member .= '<option value="' . $row_rsTeam_members['ptid'] . '" selected>' . $row_rsTeam_members['fullname'] . '</option>';
                    } else {
                        $team_member .= '<option value="' . $row_rsTeam_members['ptid'] . '">' . $row_rsTeam_members['fullname'] . '</option>';
                    }
                } while ($row_rsteam = $query_rsteam->fetch());

                $query_rsMembers = $db->prepare("SELECT * FROM tbl_projmembers WHERE projid=:projid");
                $query_rsMembers->execute(array(":projid" => $projid));
                $row_rsMembers = $query_rsMembers->fetch();
                $totalRows_rsMembers = $query_rsMembers->rowCount();

                $team = '';
                do {
                    $ptid = $row_rsMembers['ptid'];
                    $query_rsTeam = $db->prepare("SELECT * FROM tbl_projteam2 WHERE ptid=:ptid ");
                    $query_rsTeam->execute(array(":ptid" => $ptid));
                    $row_rsTeam = $query_rsTeam->fetch();
                    $totalRows_rsTeam = $query_rsTeam->rowCount();

                    if (in_array($ptid, $team_members)) {
                        $team .= '<option value="' . $row_rsTeam['ptid'] . '" selected>' . $row_rsTeam['fullname'] . '</option>';
                    } else {
                        $team .= '<option value="' . $row_rsTeam['ptid'] . '">' . $row_rsTeam['fullname'] . '</option>';
                    }
                } while ($row_rsMembers = $query_rsMembers->fetch());

                if ($today < $compare && $totalRows_rsMapped == 0) {
                    $data .= ' 
                    <tr id="row' . $rowno . '">
                        <td>' . $rowno . '</td>
                        <td>
                        <select  data-id="' . $rowno . '" name="forest[]" id="forestrow' . $rowno . '" class="form-control show-tick selectpicker validoutcome selectedfinance" onchange="state(' . $rowno . ')"  style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                            ' . $forest_option . '
                        </select>
                        </td>
                        <td>
                            <select name="team' . $stid . '[]" multiple id="teamrow' .   $rowno .    '" onchange="get_responsible(' . $rowno . ')" class="form-control selectpicker membersrow' . $rowno . '" data-actions-box="true" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                                ' . $team . '
                            </select>
                        </td>
                        <td>
                            <select name="responsible[]" multiple id="responsiblerow' .   $rowno .    '" class="form-control selectpicker"  style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                                ' . $team_member . '
                            </select>
                        </td>
                        <td>
                            <input type="hidden" name="hid[]" id="hidrow' . $rowno .  '"  class="form-control" value="' . $stid . '" style="width:85%; float:right" required/>
                            <input type="date" name="mdate[]" id="mdaterow' . $rowno .  '" class="form-control" value="' . $mapping_date . '" style="width:85%; float:right" required/>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_assign("row' . $rowno .  '")>
                                <span class="glyphicon glyphicon-minus"></span>
                            </button>
                        </td>
                    </tr>';
                } else {
                    $rowno = $rowno - 1;
                }
            } while ($row_rsMap = $query_rsMap->fetch());
        } else {
            $data .= '
            <tr></tr>
            <tr id="removeTr">
            <td colspan="6">Assign</td>
            </tr>';
        }
        echo $data;
    }

    if (isset($_POST['get_option'])) {
        $projid = $_POST['projid'];
        $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid=:projid");
        $query_rsProjects->execute(array(":projid" => $projid));
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();

        $projlocation = $row_rsProjects['projlocation'];
        $projcommunity_val = $row_rsProjects['projcommunity'];
        $ecosystem = explode(",", $row_rsProjects['projlga']);
        $forest = explode(",", $row_rsProjects['projstate']);
        $forest_option = '<option value="">... Select from list ...</option>';


        if ($projlocation != null) {
            $query_rsComm =  $db->prepare("SELECT id, state FROM tbl_state WHERE id=:projcommunity");
            $query_rsComm->execute(array(":projcommunity" => $projcommunity_val));
            $row_rsComm = $query_rsComm->fetch();
            $totalRows_rsComm = $query_rsComm->rowCount();

            // check if the ecosystems exists 
            $query_rsProjlga =  $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:projcommunity");
            $query_rsProjlga->execute(array(":projcommunity" => $projcommunity));
            $row_rsProjlga = $query_rsProjlga->fetch();
            $totalRows_rsProjlga = $query_rsProjlga->rowCount();

            if ($totalRows_rsProjlga > 0) {

                do {
                    // check if the forests exists 
                    $projlga = $row_rsProjlga['id'];
                    $ward = $row_rsProjlga['state'];
                    $community = $row_rsComm['state'];
                    if (in_array($projlga, $ecosystem)) {
                        $forest_option .= '<optgroup label="' . $ward . ' (' .  $community . ')">';
                        $query_rsProjstate =  $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:projlga");
                        $query_rsProjstate->execute(array(":projlga" => $projlga));
                        $row_rsProjstate = $query_rsProjstate->fetch();
                        $totalRows_rsProjstate = $query_rsProjstate->rowCount();
                        if ($totalRows_rsProjstate > 0) {
                            do {
                                $state = $row_rsProjstate['id'];
                                // ensure if the location has been mapped does not reflect
                                if (in_array($state, $forest)) {
                                    $handler = projstate_handler($db, $state, $projid);
                                    if ($handler) {
                                        $forest_option .= '<option value="' . $row_rsProjstate['id'] . '">' . $row_rsProjstate['state'] . '</option>';
                                    }
                                }
                            } while ($row_rsProjstate = $query_rsProjstate->fetch());
                        }
                        $forest_option .= '<optgroup>';
                    }
                } while ($row_rsProjlga = $query_rsProjlga->fetch());
            }
        } else {
            $projcommunity_arr = explode(",", $projcommunity_val);
            $conservancy = [];

            for ($i = 0; $i < count($projcommunity_arr); $i++) {
                // loop through the communities 
                $projcommunity = $projcommunity_arr[$i];
                $query_rsComm =  $db->prepare("SELECT id, state FROM tbl_state WHERE id=:projcommunity");
                $query_rsComm->execute(array(":projcommunity" => $projcommunity));
                $row_rsComm = $query_rsComm->fetch();
                $totalRows_rsComm = $query_rsComm->rowCount();

                // check if the ecosystems exists 
                $query_rsProjlga =  $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:projcommunity");
                $query_rsProjlga->execute(array(":projcommunity" => $projcommunity));
                $row_rsProjlga = $query_rsProjlga->fetch();
                $totalRows_rsProjlga = $query_rsProjlga->rowCount();

                if ($totalRows_rsProjlga > 0) {

                    do {
                        // check if the forests exists 
                        $projlga = $row_rsProjlga['id'];
                        $ward = $row_rsProjlga['state'];
                        $community = $row_rsComm['state'];
                        if (in_array($projlga, $ecosystem)) {
                            $forest_option .= '<optgroup label="' . $ward . ' (' .  $community . ')">';
                            $query_rsProjstate =  $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:projlga");
                            $query_rsProjstate->execute(array(":projlga" => $projlga));
                            $row_rsProjstate = $query_rsProjstate->fetch();
                            $totalRows_rsProjstate = $query_rsProjstate->rowCount();
                            if ($totalRows_rsProjstate > 0) {
                                do {
                                    $state = $row_rsProjstate['id'];
                                    // ensure if the location has been mapped does not reflect
                                    if (in_array($state, $forest)) {
                                        $handler = projstate_handler($db, $state, $projid);
                                        if ($handler) {
                                            $forest_option .= '<option value="' . $row_rsProjstate['id'] . '">' . $row_rsProjstate['state'] . '</option>';
                                        }
                                    }
                                } while ($row_rsProjstate = $query_rsProjstate->fetch());
                            }
                            $forest_option .= '<optgroup>';
                        }
                    } while ($row_rsProjlga = $query_rsProjlga->fetch());
                }
            }
        }

        $query_rsMembers = $db->prepare("SELECT * FROM tbl_projmembers WHERE projid=:projid");
        $query_rsMembers->execute(array(":projid" => $projid));
        $row_rsMembers = $query_rsMembers->fetch();
        $totalRows_rsMembers = $query_rsMembers->rowCount();

        function members_handler($db, $ptid)
        {
            $query_rsMapping = $db->prepare("SELECT * FROM tbl_projects p INNER JOIN tbl_project_mapping m ON m.projid= p.projid WHERE deleted='0' and mapped=0 AND m.ptid=:ptid");
            $query_rsMapping->execute(array(":ptid" => $ptid));
            $row_rsMapping = $query_rsMapping->fetch();
            $totalRows_rsMapping = $query_rsMapping->rowCount();
            if ($totalRows_rsMapping > 10) {
                return false;
            } else {
                return true;
            }
        }

        $team = '';
        do {
            $ptid = $row_rsMembers['ptid'];
            $query_rsTeam = $db->prepare("SELECT * FROM tbl_projteam2 WHERE ptid=:ptid");
            $query_rsTeam->execute(array(":ptid" => $ptid));
            $row_rsTeam = $query_rsTeam->fetch();
            $totalRows_rsTeam = $query_rsTeam->rowCount();
            // $handler = members_handler($db, $ptid);
            // if ($handler) {
            $team .= '<option value="' . $row_rsTeam['ptid'] . '">' . $row_rsTeam['fullname'] . '</option>';
            // }
        } while ($row_rsMembers = $query_rsMembers->fetch());

        $arr = array("team" => $team, "forest" => $forest_option);
        echo json_encode($arr);
    }

    if (isset($_POST['get_responsible'])) {
        $members = explode(",", $_POST['members']);
        $team = '<option value="">... Select from list ...</option>';;
        for ($i = 0; $i < count($members); $i++) {
            $ptid = $members[$i];
            if (!empty($ptid)) {
                $query_rsTeam = $db->prepare("SELECT * FROM tbl_projteam2 WHERE ptid=:ptid");
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
        $forest = $_POST['forest'];
        $mapping_date = $_POST['mdate'];
        $resp = $_POST['responsible'];

        for ($j = 0; $j < count($forest); $j++) {
            $stid = $forest[$j];
            $responsible = $resp[$j];
            $official_date = $mapping_date[$j];
            $team = $_POST['team' . $stid];
            $team_members = [];
            for ($i = 0; $i < count($team); $i++) {
                $ptid = $team[$i];
                $team_members[] = $team[$i];
                $insertSQL1 = $db->prepare("INSERT INTO tbl_project_mapping (projid, ptid, stid, responsible, mapping_date) VALUES(:projid, :ptid, :stid, :responsible, :mapping_date)");
                $result1  = $insertSQL1->execute(array(":projid" => $projid, ":ptid" => $ptid, ":stid" => $stid, ":responsible" => $responsible, ":mapping_date" => $official_date));
            }
            sendMail($db, $mail, $responsible, $official_date, $stid, $team_members, $projid);
        }
        if ($result1 === TRUE) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Created";
        } else {
            $valid['success'] = false;
            $valid['messages'] = "Error while creating the record!!";
        }

        echo json_encode($valid);
    }

    if (isset($_POST['edititem'])) {
        $valid = [];
        $projid = $_POST['projid'];
        $forest = $_POST['forest'];
        $hid = $_POST['hid'];
        $mapping_date = $_POST['mdate'];
        $resp = $_POST['responsible'];

        for ($t = 0; $t < count($hid); $t++) {
            $deleteQuery = $db->prepare("DELETE FROM `tbl_project_mapping` WHERE projid=:projid AND stid=:hid");
            $results = $deleteQuery->execute(array(':projid' => $projid, ":hid" => $hid[$t]));
        }

        if ($results) {
            for ($j = 0; $j < count($forest); $j++) {
                $stid = $forest[$j];
                $responsible = $resp[$j];
                $official_date = $mapping_date[$j];
                $team = $_POST['team' . $stid];
                $team_members = [];
                for ($i = 0; $i < count($team); $i++) {
                    $ptid = $team[$i];
                    $team_members[] = $ptid;
                    $insertSQL1 = $db->prepare("INSERT INTO tbl_project_mapping (projid, ptid, stid, responsible, mapping_date) VALUES(:projid, :ptid, :stid,:responsible, :mapping_date)");
                    $result1  = $insertSQL1->execute(array(":projid" => $projid, ":ptid" => $ptid, ":stid" => $stid, ":responsible" => $responsible, ":mapping_date" => $official_date));
                }
                sendMail($db, $mail, $responsible, $official_date, $stid, $team_members, $projid);
            }

            if ($result1 === TRUE) {
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
        $projid = $_POST['projid'];
        $deleteQuery = $db->prepare("DELETE FROM `tbl_project_mapping` WHERE projid=:projid AND stid=:hid");
        $results = $deleteQuery->execute(array(':projid' => $projid, ":hid" => $itemid));

        if ($results === TRUE) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Deleted";
        } else {
            $valid['success'] = false;
            $valid['messages'] = "Error while deletng the record!!";
        }
        echo json_encode($valid);
    }
} catch (PDOException $ex) {
    function flashMessage($data)
    {
        return $data;
    }
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $result;
}
