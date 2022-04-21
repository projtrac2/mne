<?php

include_once "controller.php";

try {
    // delete information on page readty 
    if (isset($_POST['deleteItem'])) {
        $indid = $_POST['indid'];
        $valid['success'] = true;
        $valid['messages'] = "Successfully Deleted";
        $deleteQuery = $db->prepare("DELETE FROM tbl_indicator WHERE indid=:indid");
        $results = $deleteQuery->execute(array(':indid' => $indid));

        if ($results) {
            if ($results === TRUE) {
                $valid['success'] = true;
                $valid['messages'] = "Successfully Deleted";
            } else {
                $valid['success'] = false;
                $valid['messages'] = "Error while deletng the record!!";
            }
            echo json_encode($valid);
        }
    }

    if (isset($_POST['more_info'])) {
        $indid = $_POST['indid'];
        $query_rsProjects = $db->prepare("SELECT i.indicator_code, i.indicator_name, i.indicator_description, i.indicator_direction, m.method, u.unit FROM tbl_indicator i inner join tbl_indicator_calculation_method m on m.id=i.indicator_calculation_method inner join tbl_measurement_units u on u.id=i.indicator_unit WHERE indid=:indid");
        $query_rsProjects->execute(array(":indid" => $indid));
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();
        $indcode = $row_rsProjects['indicator_code'];
        $indname = $row_rsProjects['indicator_name'];
        $inddesc = $row_rsProjects['indicator_description'];
        $calculationmethod = $row_rsProjects['method'];
        $indunit = $row_rsProjects['unit'];
        $inddir = $row_rsProjects['indicator_direction'];
		if($inddir == 1){
			$inddirection = "Upward";
		}else{
			$inddirection = "Downward";
		}

        $indicatordetails = '
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
					<div  class="header" style="background-color:#c7e1e8; border-radius:3px">
					</div> 
					<div class="body">
						<div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
							<div class="col-md-3 clearfix" style="margin-top:5px; margin-bottom:5px">
								<label class="control-label">Indicator Code:</label>
								<div class="form-line">
									<input type="text" class="form-control" value="' . $indcode . ' " readonly>
								</div>
							</div>
							<div class="col-md-12 clearfix" style="margin-top:5px; margin-bottom:5px">
								<label class="control-label">Indicator Name:</label>
								<div class="form-line">
									<input type="text" class="form-control" value="' . $indname . ' " readonly>
								</div>
							</div>
							<div class="col-md-12 clearfix" style="margin-top:5px; margin-bottom:5px">
								<label class="control-label">Indicator Description:</label>
								<div class="form-line">
									<input type="text" class="form-control" value="'.strip_tags($inddesc).'" readonly>
								</div>
							</div>
							<div class="col-md-6 clearfix" style="margin-top:5px; margin-bottom:5px">
								<label class="control-label">Indicator Calculation Method:</label>
								<div class="form-line">
									<input type="text" class="form-control" value="'.$calculationmethod.'" readonly>
								</div>
							</div>
							<div class="col-md-3 clearfix" style="margin-top:5px; margin-bottom:5px">
								<label class="control-label">Indicator Unit of Measure:</label>
								<div class="form-line">
									<input type="text" class="form-control" value="'.$indunit.'" readonly>
								</div>
							</div>
							<div class="col-md-3 clearfix" style="margin-top:5px; margin-bottom:5px">
								<label class="control-label">Indicator Direction:</label>
								<div class="form-line">
									<input type="text" class="form-control" value="'.$inddirection.'" readonly>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>';
        echo $indicatordetails;
    }
} catch (PDOException $ex) {
    // $result = flashMessage("An error occurred: " .$ex->getMessage());
    print($ex->getMessage());
}
