<?php
include_once('../../projtrac-dashboard/resource/Database.php');
include_once('../../projtrac-dashboard/resource/utilities.php');
include_once("../../system-labels.php");
require('../../vendor/autoload.php');
 
function disaggregation($indid)
{
    global $db;
    $query_rsIndicator = $db->prepare("SELECT *  FROM tbl_indicator WHERE indid='$indid' ");
    $query_rsIndicator->execute();
    $row_rsIndicator = $query_rsIndicator->fetch();
    $totalRows_rsIndicator = $query_rsIndicator->rowCount();
    if ($totalRows_rsIndicator > 0) {
        $indicator_disaggregation = $row_rsIndicator['indicator_disaggregation'];
        if ($indicator_disaggregation > 0) {
            $query_rsIndicator1 = $db->prepare("SELECT d.type FROM `tbl_indicator_measurement_variables_disaggregation_type` as t INNER JOIN tbl_disaggregation_type as d  on  d.id = t.disaggregation_type WHERE indicatorid='$indid' ");
            $query_rsIndicator1->execute();
            $row_rsIndicator1 = $query_rsIndicator1->fetch();
            $totalRows_rsIndicator1 = $query_rsIndicator1->rowCount();
            if ($totalRows_rsIndicator1 > 0) {
                $type = $row_rsIndicator1['type'];
                if ($type == 1) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}



if(isset($_POST['add'])){
   $opid = $_POST['opid'];
   $projid = $_POST['projid'];
   $data ='';

   // Responsible 
   $query_rsOutputs = $db->prepare("SELECT *  FROM tbl_project_details WHERE projid=:projid and id=:outputid");
   $query_rsOutputs->execute(array(":projid" => $projid, ":outputid"=>$opid));
   $row_rsOutputs = $query_rsOutputs->fetch();
   $totalRows_rsOutputs = $query_rsOutputs->rowCount();

   if($totalRows_rsOutputs > 0){
      $indicator = $row_rsOutputs['indicator'];
      $disaggregated = disaggregation($indicator);

      if($disaggregated){

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
                                    '. $locationName .'
                                    <input type="hidden" name="result_level' . $oprow . '[]" id="result_level' . $rowno . '" class="form-control" value="' . $result_level . '" style="width:85%; float:right" required />
                                </td>
                                <td>
                                    <select name="responsible' . $oprow . '[]" id="responsible' . $rowno . '" class="form-control selectpicker" data-actions-box="true" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                                        <option value="">Select from list</option>
                                        ' . $team . '
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
      }else{
         $query_rsdissegragations = $db->prepare("SELECT * FROM `tbl_output_disaggregation` where projid=:projid and outputid=:opid");
        $result = $query_rsdissegragations->execute(array(":projid" => $projid, ":opid" => $opid));
        $row_rsdissegragations = $query_rsdissegragations->fetch();
        $totalRows_rsdissegragations = $query_rsdissegragations->rowCount();
        $data .= '
        <input type="hidden" name="projid" id="projid"   value="' . $projid . '"  />
        <input type="hidden" name="opid" id="opid"   value="' . $opid . '"  />';
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
                        <input type="hidden" name="level3[]" value="' . $opstate .'"  />
                    </td>
                    <td>
                        <select name="responsible[]"  class="form-control selectpicker" data-actions-box="true" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                            <option value="">Select from list</option>
                            ' . $team . '
                        </select>
                    </td>
                </tr>';
            } while ($row_rsdissegragations = $query_rsdissegragations->fetch());
        }
      }

   }

   echo $data;
}

if(isset($_POST['edit'])){

}