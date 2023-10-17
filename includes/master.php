<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
try {
    function get_status($projstatus)
    {
        global $db;
        $query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
        $query_Projstatus->execute(array(":projstatus" => $projstatus));
        $row_Projstatus = $query_Projstatus->fetch();
        $total_Projstatus = $query_Projstatus->rowCount();
        $status = "";
        if ($total_Projstatus > 0) {
            $status_name = $row_Projstatus['statusname'];
            $status_class = $row_Projstatus['class_name'];
            $status = '<button type="button" class="' . $status_class . '" style="width:100%">' . $status_name . '</button>';
        }
        return $status;
    }

    function check_if_assigned($project_stage, $projid)
    {
        global $db, $user_name;
        $query_Projstatus =  $db->prepare("SELECT * FROM tbl_project_standins WHERE project_stage = :project_stage AND stand_in_for=:user_name AND projid=:projid");
        $query_Projstatus->execute(array(":project_stage" => $project_stage, ":user_name" => $user_name, ":projid" => $projid));
        $row_Projstatus = $query_Projstatus->fetch();
        return $row_Projstatus;
    }

    function get_financial_plan($projid)
    {
        global $page_actions, $db;
        $query_rsPlan = $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid = :projid");
        $query_rsPlan->execute(array(":projid" => $projid));
        $totalRows_plan = $query_rsPlan->rowCount();

        $hashproc = base64_encode($projid);
        if ($totalRows_plan > 0) {
            $link = ' 
            <li>
                <a type="button" href="add-financial-plan?proj=<?= $encode_projid ?>" id="addFormModalBtn">
                    <i class="fa fa-plus-square-o"></i> Edit Plan
                </a>
            </li>';
            if (in_array("approve_stage", $page_actions)) {
                $link = '
                <li>
                    <a type="button" id="#finishAddItemModalBtn" href="add-project-output-designs.php?projid=' . $hashproc . '" title="Click here to APPROVE activities">
                        <i class="fa fa-hand-paper-o"></i> Approve Plan
                    </a>
                </li>';
            }
        } else {
            $link = '
            <li>
                <a type="button" href="add-financial-plan?proj=<?= $encode_projid ?>" id="addFormModalBtn">
                    <i class="fa fa-plus-square-o"></i> Add Plan
                </a>
            </li>';
            if (in_array("assign_project", $page_actions)) {
                $link = ' 
                <li>
                    <a type="button" id="#finishAddItemModalBtn" href="add-project-output-designs.php?projid=' . $hashproc . '" title="Click here to adding activities">
                        <i class="fa fa-hand-paper-o"></i> Assign project
                    </a>
                </li>';
            }
        }
        return $link;
    }

    function get_procurement_plan($projid)
    {
        global $page_actions, $db;
        $query_rsPlan = $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid = :projid");
        $query_rsPlan->execute(array(":projid" => $projid));
        $totalRows_plan = $query_rsPlan->rowCount();

        $hashproc = base64_encode($projid);
        if ($totalRows_plan > 0) {
            $link = ' 
            <li>
                <a type="button" href="add-procurement-details?prj=<?= $hashproc ?>" id="addFormModalBtn">
                    <i class="fa fa-plus-square-o"></i> Edit Procurement
                </a>
            </li>';
            if (in_array("approve_stage", $page_actions)) {
                $link = '
                <li>
                    <a type="button" id="#finishAddItemModalBtn" href="add-project-output-designs.php?projid=' . $hashproc . '" title="Click here to APPROVE activities">
                        <i class="fa fa-hand-paper-o"></i> Approve Procurement
                    </a>
                </li>';
            }
        } else {
            $link = '
            <li>
                <a type="button" href="add-procurement-details?prj=<?= $hashproc ?>" id="addFormModalBtn">
                    <i class="fa fa-plus-square-o"></i> Add Procurement
                </a>
            </li>';
            if (in_array("assign_project", $page_actions)) {
                $link = ' 
                <li>
                    <a type="button" id="#finishAddItemModalBtn" href="add-project-output-designs.php?projid=' . $hashproc . '" title="Click here to adding activities">
                        <i class="fa fa-hand-paper-o"></i> Assign project
                    </a>
                </li>';
            }
        }
        return $link;
    }


    function get_mne_plan($projid)
    {
        global $page_actions, $db;
        $query_rsPlan = $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid = :projid");
        $query_rsPlan->execute(array(":projid" => $projid));
        $totalRows_plan = $query_rsPlan->rowCount();

        $hashproc = base64_encode($projid);
        if ($totalRows_plan > 0) {
            $link = ' 
            <li>
                <a type="button" href="add-procurement-details?prj=<?= $hashproc ?>" id="addFormModalBtn">
                    <i class="fa fa-plus-square-o"></i> Edit Procurement
                </a>
            </li>';
            if (in_array("approve_stage", $page_actions)) {
                $link = '
                <li>
                    <a type="button" id="#finishAddItemModalBtn" href="add-project-output-designs.php?projid=' . $hashproc . '" title="Click here to APPROVE activities">
                        <i class="fa fa-hand-paper-o"></i> Approve Procurement
                    </a>
                </li>';
            }
        } else {
            $link = '
            <li>
                <a type="button" href="add-procurement-details?prj=<?= $hashproc ?>" id="addFormModalBtn">
                    <i class="fa fa-plus-square-o"></i> Add Procurement
                </a>
            </li>';
            if (in_array("assign_project", $page_actions)) {
                $link = ' 
                <li>
                    <a type="button" id="#finishAddItemModalBtn" href="add-project-output-designs.php?projid=' . $hashproc . '" title="Click here to adding activities">
                        <i class="fa fa-hand-paper-o"></i> Assign project
                    </a>
                </li>';
            }
        }
        return $link;
    }


    function get_mapping($projid)
    {
        global $page_actions, $db;
        $query_rsPlan = $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid = :projid");
        $query_rsPlan->execute(array(":projid" => $projid));
        $totalRows_plan = $query_rsPlan->rowCount();

        $hashproc = base64_encode($projid);
        if ($totalRows_plan > 0) {
            $link = ' 
            <li>
                <a type="button" href="add-procurement-details?prj=<?= $hashproc ?>" id="addFormModalBtn">
                    <i class="fa fa-plus-square-o"></i> Edit Procurement
                </a>
            </li>';
            if (in_array("approve_stage", $page_actions)) {
                $link = '
                <li>
                    <a type="button" id="#finishAddItemModalBtn" href="add-project-output-designs.php?projid=' . $hashproc . '" title="Click here to APPROVE activities">
                        <i class="fa fa-hand-paper-o"></i> Approve Procurement
                    </a>
                </li>';
            }
        } else {
            $link = '
            <li>
                <a type="button" href="add-procurement-details?prj=<?= $hashproc ?>" id="addFormModalBtn">
                    <i class="fa fa-plus-square-o"></i> Add Procurement
                </a>
            </li>';
            if (in_array("assign_project", $page_actions)) {
                $link = ' 
                <li>
                    <a type="button" id="#finishAddItemModalBtn" href="add-project-output-designs.php?projid=' . $hashproc . '" title="Click here to adding activities">
                        <i class="fa fa-hand-paper-o"></i> Assign project
                    </a>
                </li>';
            }
        }
        return $link;
    }


    function get_team($projid)
    {
        global $page_actions, $db;
        $query_rsPlan = $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid = :projid");
        $query_rsPlan->execute(array(":projid" => $projid));
        $totalRows_plan = $query_rsPlan->rowCount();

        $hashproc = base64_encode($projid);
        if ($totalRows_plan > 0) {
            $link = ' 
            <li>
                <a type="button" href="add-procurement-details?prj=<?= $hashproc ?>" id="addFormModalBtn">
                    <i class="fa fa-plus-square-o"></i> Edit Procurement
                </a>
            </li>';
            if (in_array("approve_stage", $page_actions)) {
                $link = '
                <li>
                    <a type="button" id="#finishAddItemModalBtn" href="add-project-output-designs.php?projid=' . $hashproc . '" title="Click here to APPROVE activities">
                        <i class="fa fa-hand-paper-o"></i> Approve Procurement
                    </a>
                </li>';
            }
        } else {
            $link = '
            <li>
                <a type="button" href="add-procurement-details?prj=<?= $hashproc ?>" id="addFormModalBtn">
                    <i class="fa fa-plus-square-o"></i> Add Procurement
                </a>
            </li>';
            if (in_array("assign_project", $page_actions)) {
                $link = ' 
                <li>
                    <a type="button" id="#finishAddItemModalBtn" href="add-project-output-designs.php?projid=' . $hashproc . '" title="Click here to adding activities">
                        <i class="fa fa-hand-paper-o"></i> Assign project
                    </a>
                </li>';
            }
        }
        return $link;
    }


    function get_checklist($projid)
    {
        global $page_actions, $db;
        $query_rsPlan = $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid = :projid");
        $query_rsPlan->execute(array(":projid" => $projid));
        $totalRows_plan = $query_rsPlan->rowCount();

        $hashproc = base64_encode($projid);
        if ($totalRows_plan > 0) {
            $link = ' 
            <li>
                <a type="button" href="add-procurement-details?prj=<?= $hashproc ?>" id="addFormModalBtn">
                    <i class="fa fa-plus-square-o"></i> Edit Procurement
                </a>
            </li>';
            if (in_array("approve_stage", $page_actions)) {
                $link = '
                <li>
                    <a type="button" id="#finishAddItemModalBtn" href="add-project-output-designs.php?projid=' . $hashproc . '" title="Click here to APPROVE activities">
                        <i class="fa fa-hand-paper-o"></i> Approve Procurement
                    </a>
                </li>';
            }
        } else {
            $link = '
            <li>
                <a type="button" href="add-procurement-details?prj=<?= $hashproc ?>" id="addFormModalBtn">
                    <i class="fa fa-plus-square-o"></i> Add Procurement
                </a>
            </li>';
            if (in_array("assign_project", $page_actions)) {
                $link = ' 
                <li>
                    <a type="button" id="#finishAddItemModalBtn" href="add-project-output-designs.php?projid=' . $hashproc . '" title="Click here to adding activities">
                        <i class="fa fa-hand-paper-o"></i> Assign project
                    </a>
                </li>';
            }
        }
        return $link;
    }

    function get_program_of_works($projid)
    {
        global $page_actions, $db;
        $query_rsPlan = $db->prepare("SELECT * FROM tbl_project_direct_cost_plan WHERE projid = :projid");
        $query_rsPlan->execute(array(":projid" => $projid));
        $totalRows_plan = $query_rsPlan->rowCount();

        $hashproc = base64_encode($projid);
        if ($totalRows_plan > 0) {
            $link = ' 
            <li>
                <a type="button" href="add-procurement-details?prj=<?= $hashproc ?>" id="addFormModalBtn">
                    <i class="fa fa-plus-square-o"></i> Edit Procurement
                </a>
            </li>';
            if (in_array("approve_stage", $page_actions)) {
                $link = '
                <li>
                    <a type="button" id="#finishAddItemModalBtn" href="add-project-output-designs.php?projid=' . $hashproc . '" title="Click here to APPROVE activities">
                        <i class="fa fa-hand-paper-o"></i> Approve Procurement
                    </a>
                </li>';
            }
        } else {
            $link = '
            <li>
                <a type="button" href="add-procurement-details?prj=<?= $hashproc ?>" id="addFormModalBtn">
                    <i class="fa fa-plus-square-o"></i> Add Procurement
                </a>
            </li>';
            if (in_array("assign_project", $page_actions)) {
                $link = ' 
                <li>
                    <a type="button" id="#finishAddItemModalBtn" href="add-project-output-designs.php?projid=' . $hashproc . '" title="Click here to adding activities">
                        <i class="fa fa-hand-paper-o"></i> Assign project
                    </a>
                </li>';
            }
        }
        return $link;
    }

    function get_activities($projid)
    {
        global $page_actions, $db;
        $query_rsDesigns = $db->prepare("SELECT * FROM tbl_project_output_designs WHERE projid=:projid ORDER BY id");
        $query_rsDesigns->execute(array(":projid" => $projid));
        $totalRows_rsDesigns = $query_rsDesigns->rowCount();

        $hashproc = base64_encode($projid);
        $link = '';

        return $link;
    }


    $query_rsProjects = $db->prepare("SELECT p.*, s.sector FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND p.projstage = :projstage ORDER BY p.projid DESC");
    $query_rsProjects->execute(array(":projstage" => $project_stage));
    $totalRows_rsProjects = $query_rsProjects->rowCount();

    if ($totalRows_rsProjects > 0) {
        $counter = 0;
        while ($row_rsProjects = $query_rsProjects->fetch()) {
            $counter++;
            $project_name = $row_rsProjects['projname'];
            $project_code = $row_rsProjects['projcode'];
            $projid = $row_rsProjects['projid'];
            $worked_on = $row_rsProjects['proj_substage'];
            $projstatus = 11;
            $status = get_status($projstatus);
            $link = "";
            $hashproc = base64_encode("encodefnprj{$projid}");
            if ($project_stage == 1) {
                //     // $link = get_activities($projid, $project_stage);
                $query_rsDesigns = $db->prepare("SELECT * FROM tbl_project_output_designs s INNER JOIN tbl_project_details d ON d.id = s.output_id  WHERE projid=:projid");
                $query_rsDesigns->execute(array(":projid" => $projid));
                $totalRows_rsDesigns = $query_rsDesigns->rowCount();
                if ($totalRows_rsDesigns > 0) {
                    $link .= '
                    <li>
                        <a type="button" id="#finishAddItemModalBtn" href="add-project-output-designs.php?projid=' . $hashproc . '" title="Click here to adding activities">
                            <i class="fa fa-hand-paper-o"></i> Edit Activities
                        </a>
                    </li>';
                    if (in_array("approve_stage", $page_actions)) {
                        $link .= '
                        <li>
                            <a type="button" id="#finishAddItemModalBtn" href="add-project-output-designs.php?projid=' . $hashproc . '" title="Click here to APPROVE activities">
                                <i class="fa fa-hand-paper-o"></i> Add Activities
                            </a>
                        </li>';
                    }
                } else {
                    $link .= '
                    <li>
                        <a type="button" id="#finishAddItemModalBtn" href="add-project-output-designs.php?projid=' . $hashproc . '" title="Click here to adding activities">
                            <i class="fa fa-hand-paper-o"></i> Add Activities
                        </a>
                    </li>';
                    if (in_array("assign_project", $page_actions)) {
                        $link .= ' 
                        <li>
                            <a type="button" id="#finishAddItemModalBtn" href="add-project-output-designs.php?projid=' . $hashproc . '" title="Click here to adding activities">
                                <i class="fa fa-hand-paper-o"></i> Assign project
                            </a>
                        </li>';
                    }
                }
            }
            //  else if ($project_stage == 2) {
            //     $link = get_financial_plan($projid, $project_stage);
            // } else if ($project_stage == 3) { 
            //     $link = get_program_of_works($projid, $project_stage);
            // } else if ($project_stage == 4) { 
            //     $link = get_program_of_works($projid, $project_stage);
            // } else if ($project_stage == 5) { 
            //     $link = get_program_of_works($projid, $project_stage);
            // } else if ($project_stage == 7) {
            //     $link = get_program_of_works($projid, $project_stage);
            // }

?>
            <tr class="projects" style="background-color:#eff9ca">
                <td align="center"><?= $counter ?></td>
                <td><?= $project_code ?></td>
                <td><?= $project_name ?></td>
                <td><?= date("d M Y") ?></td>
                <td><?= $status ?></td>
                <td>
                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Options <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <?php
                            if (in_array("create", $page_actions)) {
                            ?>
                                <li>
                                    <?= $link ?>
                                </li>
                            <?php
                            }
                            ?>
                            <li>
                                <a type="button" data-toggle="modal" data-target="#moreModal" id="moreModalBtn" onclick="more(<?= $projid ?>)">
                                    <i class="fa fa-file-text"></i> View More
                                </a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
<?php
        }
    }
} catch (PDOException $ex) {
    // $results = flashMessage("An error occurred: " . $ex->getMessage());
}
