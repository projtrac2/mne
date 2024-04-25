<?php 
include_once "controller.php";

try {
    if (isset($_POST['more_info'])) {
        $projid = $_POST['itemId'];
        $stid = $_POST['stid'];
        $query_rsMap = $db->prepare("SELECT *  FROM tbl_project_mapping WHERE projid=:projid && stid=:stid");
        $query_rsMap->execute(array(":projid" => $projid, ":stid" => $stid));
        $row_rsMap = $query_rsMap->fetch();
        $totalRows_rsMap = $query_rsMap->rowCount();
        $team = '';


        if ($totalRows_rsMap > 0) {
            $team .= '  
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card"> 
                    <div class="header" align="center">
                        <div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
                            Team Members
                        </div>
                    </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="milestoneTable" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th> 
                                            <th>Name</th>   
                                            <th>Role</th> 
                                            <th>Phone number </th> 
                                            <th>Email</th> 
                                        </tr>
                                    </thead>
                                    <tbody id="funding_table_body" >';
										$Ccounter = 0;
										do {
											$Ccounter++;
											$ptid = $row_rsMap['ptid'];
											$responsible = $row_rsMap['responsible'];

											$query_rsTeam_members = $db->prepare("SELECT *  FROM tbl_projteam2 WHERE ptid=:ptid ");
											$query_rsTeam_members->execute(array(":ptid" => $ptid));
											$row_rsTeam_members = $query_rsTeam_members->fetch();
											$totalRows_rsTeam_members = $query_rsTeam_members->rowCount();
											$role = '';
											$name = $row_rsTeam_members['fullname'];
											$email = $row_rsTeam_members['email'];
											$phone = $row_rsTeam_members['phone'];
											if ($responsible == $ptid) {
												$role = "Responsible";
											} else {
												$role = "Team Member";
											}
											$team .= ' 
                                            <tr>  
                                                <td >' . $Ccounter  . ' </td>
                                                <td >' . $name . ' </td>  
                                                <td> ' . $role . '</td>
                                                <td> ' . $phone . '</td>
                                                <td>' . $email  . ' </td>
                                            </tr>';
										} while ($row_rsMap = $query_rsMap->fetch());
										$team .= '  
                                    </tbody>
                                </table> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }

        $query_rsMapped = $db->prepare("SELECT * FROM tbl_markers WHERE projid=:projid AND stid=:state LIMIT 1");
        $query_rsMapped->execute(array(":projid" => $projid, ":state" => $stid));
        $row_rsMapped = $query_rsMapped->fetch();
        $totalRows_rsMapped = $query_rsMapped->rowCount();

        if ($totalRows_rsMapped > 0) {
            $team .=
                '<div class="col-md-12">
                    <ul class="list-group"> 
                    <li class="list-group-item list-group-item list-group-item-action active">Mapping Details </li>
                    <li class="list-group-item">Date Mapped: ' . date_format(date_create($row_rsMapped['mapped_date']), 'd M Y') . ' </li>
                    <li class="list-group-item">Comments: ' . $row_rsMapped['comment'] . ' </li>
                    </ul>
                </div>';
        }
        echo $team;
    }
} catch (PDOException $ex) {
    function flashMessage($data)
    {
        return $data;
    }
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $result;
}
