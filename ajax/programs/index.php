<?php
include '../controller.php';
try {
    if (isset($_GET['get_total_number_of_programs'])) {
        if (isset($_GET["type"]) && $_GET["type"] == 1) {
            $sql = $db->prepare("SELECT * FROM `tbl_programs` g left join `tbl_projects` p on p.progid=g.progid WHERE g.program_type=0 ORDER BY `projfscyear` ASC");
            $sql->execute();
        } else {
            $sql = $db->prepare("SELECT * FROM `tbl_programs` WHERE program_type=0 ORDER BY `syear` ASC");
            $sql->execute();
        }

        $programs = 0;
        while ($row_rsProgram = $sql->fetch()) {
            $project_department = $row_rsProgram['projsector'];
            $project_section = $row_rsProgram['projdept'];
            $project_directorate = $row_rsProgram['directorate'];
            $page_detials = get_page_details($user_designation, "all-programs");
            $allow_read_records = $page_detials ? $page_detials['allow_read'] : false;
            $filter_department = view_record($project_department, $project_section, $project_directorate, $allow_read_records);
            if ($filter_department) {
                $programs++;
            }
        }
        echo json_encode(array("success" => true, "programs" => $programs));
    }

    // get department
    if (isset($_GET['get_sections'])) {
        $department_id = $_GET['department_id'];
        $sql = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent=:parent AND deleted=1");
        $result = $sql->execute(array(":parent" => $department_id));
        $sections = '<option value="">Select Section</option>';
        while ($row = $sql->fetch()) {
            $sections .= '<option value="' . $row['stid'] . '"> ' . $row['sector'] . '</option>';
        }
        echo json_encode(array("success" => $result, "sections" => $sections));
    }

    if (isset($_GET['get_directorate'])) {
        $sector_id = $_GET['sector_id'];
        $sql = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent=:parent  AND deleted=1");
        $result = $sql->execute(array(":parent" => $sector_id));
        $directorates = '<option value="">Select Directorate</option>';
        while ($row = $sql->fetch()) {
            $directorates .= '<option value="' . $row['stid'] . '"> ' . $row['sector'] . '</option>';
        }
        echo json_encode(array("success" => $result, "directorates" => $directorates));
    }

    if (isset($_GET['get_strategy'])) {
        $objid = $_GET['objid'];
        $query_strategy =  $db->prepare("SELECT * FROM tbl_objective_strategy where objid=:objid");
        $query_strategy->execute(array(":objid" => $objid));
        $totalRows_strategy = $query_strategy->rowCount();
        $strategy_options = '<option value="">.... Select Strategy from list ....</option>';
        if ($totalRows_strategy > 0) {
            while ($row_strategy = $query_strategy->fetch()) {
                $strategy_options .= '<option value="' . $row_strategy['id'] . '">' . $row_strategy['strategy'] . '</option>';
            }
        }
        echo $strategy_options;
    }

    if (isset($_GET['getprogindicator'])) {
        $query_Indicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indicator_category='Output' AND active='1' AND baseline='1'");
        $query_Indicator->execute();
        echo '<option value="">Select Indicator</option>';
        while ($row = $query_Indicator->fetch()) {
            $unit = $row['indicator_unit'];
            $query_opunit = $db->prepare("SELECT unit FROM  tbl_measurement_units  WHERE id ='$unit'");
            $query_opunit->execute();
            $opunit_ro = $query_opunit->fetch();
            $count_opunit = $query_opunit->rowCount();
            $opunit = ($count_opunit > 0) ? $opunit_ro['unit'] : "";

            $indname = $row['indicator_name'];
            echo '<option value="' . $row['indid'] . '"> ' . $opunit . " of " . $indname . '</option>';
        }
    }

    if (isset($_GET['getUnits'])) {
        $getUnits = $_GET['getUnits'];
        $indicator = $getUnits;
        $program_starting_year = $_GET['program_starting_year'];
        $years = $_GET['years'];
        $program_type = $_GET['program_type'];

        $query_Indicator = $db->prepare("SELECT unit, indicator_name FROM tbl_indicator i INNER JOIN tbl_measurement_units u ON u.id = i.indicator_unit WHERE i.indid = :indid");
        $query_Indicator->execute(array(":indid" => $getUnits));
        $row = $query_Indicator->fetch();
        $total = $query_Indicator->rowCount();

        $targets = array();
        if ($program_type == 1) {
            for ($i = 0; $i < $years; $i++) {
                $query_program = $db->prepare("SELECT SUM(target) as target FROM tbl_progdetails WHERE indicator ='$indicator' AND year='$program_starting_year'");
                $query_program->execute();
                $row_program = $query_program->fetch();
                $count_program = $query_program->rowCount();
                $program_target = ($count_program > 0) ? $row_program['target'] : 0;

                $query_strategicplan = $db->prepare("SELECT year_target FROM tbl_strategic_plan_op_indicator_targets WHERE op_indicator_id ='$indicator' AND year='$program_starting_year' ");
                $query_strategicplan->execute();
                $row_strategicplan = $query_strategicplan->fetch();
                $count_strategicplan = $query_strategicplan->rowCount();
                $strategicplan_targets = ($count_strategicplan > 0) ? $row_strategicplan['year_target'] : 0;
                $targets[] = $strategicplan_targets - $program_target;
                $program_starting_year++;
            }
        }

        if ($total > 0) {
            $unit = $row['unit'];
            $indicator_name = $row['indicator_name'];
            echo json_encode(array('success' => true, 'unit' => $unit, 'targets' =>  $targets, 'indicator_name' => $indicator_name));
        } else {
            echo json_encode(array('success' => false));
        }
    }
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
    echo $ex->getMessage();
}
