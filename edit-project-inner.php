<section class="content" style="margin-top:-20px; padding-bottom:0px">
	<div class="container-fluid">
		<div class="block-header">
			<?php
				echo $results;
			?>
			<h4 class="contentheader">
				<i class="fa fa-plus-square" aria-hidden="true"></i> Add New Project
			</h4>
		<div>
	</div>
	</div>
		<!-- Draggable Handles -->
		<div class="row clearfix"> 
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="body" style="margin-top:5px">
						<div class="stepwizard" style="margin-bottom:15px">
							<div class="stepwizard-row setup-panel bg-light-blue" style="margin-top:10px">
								<div class="stepwizard-step">
									<a href="#step-1" type="button" data-toggle="tab" class="btn btn-primary btn-circle"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
									<p>Project Details</p>
								</div>
								<div class="stepwizard-step">
									<a href="#step-2" type="button" data-toggle="tab" class="btn btn-default btn-circle disabled"><i class="fa fa-bullseye fa-3x" aria-hidden="true"></i></a></a>
									<p>Output Plan</p>
								</div>
								<div class="stepwizard-step">
									<a href="#step-3" type="button" data-toggle="tab" class="btn btn-default btn-circle disabled"><i class="fa fa-bullseye fa-3x" aria-hidden="true"></i></a></a>
									<p>Implementors</p>
								</div>
								<div class="stepwizard-step">
									<a href="#step-4" type="button" data-toggle="tab" class="btn btn-default btn-circle disabled">
										<i class="fa fa-bullseye fa-3x" aria-hidden="true"></i>
									</a>
									<p>Documents</p>
								</div>
								<div class="stepwizard-step">
									<a href="#step-5" type="button" data-toggle="tab" onclick="display_finish()" class="btn btn-default btn-circle disabled nextBtn1  finish"><i class="fa fa-check" aria-hidden="true"></i></a>
									<p>Finish</p>
								</div>
							</div>
						</div>
						<form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
							<fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">ADD PROJECT DETAILS</legend>
								<div class="col-md-6">
									<label class="control-label">Program Name*:</label>
									<div class="form-line">
										<input type="hidden" name="projid" id="projid" class="form-control" value="<?php echo $projid ?>">
										<input type="hidden" name="progid" id="progid" class="form-control" value="<?php echo $progid ?>">
										<input type="text" name="progid" id="prog" value="<?php echo $progname ?>" placeholder="Please enter name of your project" class="form-control" style="border:#CCC thin solid; border-radius: 5px" disabled>
									</div>
								</div>
								<div class="col-md-2">
									<label class="control-label">Program Start Year *:</label>
									<div class="form-line">
										<input type="text" name="progstartyear" id="progstartyear" value="<?php echo $syear ?>" placeholder="" class="form-control" style="border:#CCC thin solid; border-radius: 5px" disabled>
									</div>
								</div>
								<div class="col-md-2">
									<label class="control-label">Program End Year *:</label>
									<div class="form-line">
										<input type="text" name="progendyear" id="progendyear" value="<?php echo $syear + $years ?>" placeholder="" class="form-control" style="border:#CCC thin solid; border-radius: 5px" disabled>
									</div>
								</div>
								<div class="col-md-2">
									<label class="control-label">Program Duration *:</label>
									<div class="form-line">
										<input type="text" name="progduration" id="progduration" value="<?php echo $years ?>" placeholder="" class=" form-control" style="border:#CCC thin solid; border-radius: 5px" disabled>
									</div>
								</div>
								<div class="col-md-3">
									<label class="control-label">Project Code (Eg. 2018/12/AB23) *:</label>
									<span id="gt" style="display:none; color:#fff; background-color:#F44336; padding:5px"> Code Exists </span>
									<div class="form-line">
										<input type="text" name="projcode" onblur="validate_projcode()" id="projcode" value="<?= $projcode ?>" placeholder="Project Code" class="form-control" style="border:#CCC thin solid; border-radius: 5px" required="required">
										<span id="projcodemsg" style="color:red"> </span>
									</div>
								</div>
								<div class="col-md-9">
									<label class="control-label">Project Name *:</label>
									<div class="form-line">
										<input type="text" name="projname" id="projname" value="<?= $projname ?>" placeholder="Project Name" class="form-control" style="border:#CCC thin solid; border-radius: 5px" required>
									</div>
								</div>
								<div class="col-md-4">
									<label class="control-label">Project Start Financial Year *:</label>
									<div class="form-line">
										<input type="hidden" name="hfscyear" id="hfscyear" value="<?= $projfscyear ?>">
										<select name="projfscyear1" id="projfscyear1" onchange="finacial_year_change()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
											<option value="">.... Select Year from list ....</option>
                                            <?php
												$financialYears = array_unique($financialYear);
												foreach ($financialYears as $financialyr) {
													// get financial years 
													$query_rsYear =  $db->prepare("SELECT * FROM tbl_fiscal_year where yr ='$financialyr'");
													$query_rsYear->execute();
													$row_rsYear = $query_rsYear->fetch();

													$yrstartdate = $row_rsYear["sdate"];
													$yrenddate = $row_rsYear["edate"];
													$currdatetime = date("Y-m-d H:i:s");

													if ($currdatetime <= $yrenddate) {
														$finyear = $row_rsYear['year'];
														$finyearid = $row_rsYear['id'];
														$yr = $row_rsYear["yr"];
														if ($finyearid == $projfscyear) {
															echo '<option value="' . $finyearid . '" selected>' . $finyear . '</option>';
														} else {
															echo '<option value="' . $finyearid . '">' . $finyear . '</option>';
														}
													}
												}
                                            ?>
										</select>
										<span id="projfscyearmsg1" style="color:red"></span>
									</div>
								</div>
								<div class="col-md-4">
									<label for="projduration">Project Duration *:</label><span id="projdurationmsg" style="color:darkgoldenrod"></span>
									<div class="form-input">
										<input type="hidden" name="hduration" id="hduration">
										<input type="number" name="projduration1" onkeyup="project_duration_validate()" onchange="project_duration_validate()" value="<?php echo $projduration; ?>" id="projduration1" placeholder="Enter" class="form-control" required>
										<input type="hidden" name="hduration" id="hduration" value="<?php echo $projduration; ?>">
										<span id="projdurationmsg1" style="color:red"></span>
										<script>
                                        $(document).ready(function() {
                                            var progduration = $("#progduration").val();
                                            var remainingDuration = parseInt(progduration) * 365;
                                            var projdur = parseInt(<?php echo $projduration; ?>);
                                            $("#projdurationmsg").html(remainingDuration - projdur);
                                        });
                                    </script>
									</div>
								</div>
								<div class="col-md-4">
									<label for="projendyear">Project End Financial Year *:</label>
                                    <input type="text" name="projendyear" id="projendyear" class="form-control" value="<?php echo $projectendYear . "/" .  ($projectendYear + 1) ?>" disabled>
                                    <input type="hidden" name="hprojendyear" id="hprojendyear" value="<?php echo $projectendYear ?>" class="form-control" disabled>
                                    <input type="hidden" name="projendyearDate" id="projendyearDate" class="form-control" value="<?php echo $projectendYearDate ?>">
                                    <input type="hidden" name="projectStartingYear" id="projectStartingYear" value="<?php echo $projstartYear ?>" class="form-control">
                                    <span id="" style="color:red"></span>
								</div> 
								<div class="col-md-3">
									<label for="" class="control-label">Evaluation Required? *:</label>
									<div class="form-line">
										<input name="projevaluation" type="radio" value="1" id="evaluation1" class="with-gap radio-col-green evaluation" <?php echo ($projevaluation == 1) ? "checked": "";?> required="required" />
										<label for="evaluation1">YES</label>
										<input name="projevaluation" type="radio" value="0" id="evaluation2" class="with-gap radio-col-red evaluation" <?php echo ($projevaluation == 0) ? "checked": "";?> required="required" />
										<label for="evaluation2">NO</label>
									</div>
								</div>
								<div class="col-md-3">
									<label for="" class="control-label">Mapping Required? *:</label>
									<div class="form-line">
										<input name="projmapping" type="radio" value="1" id="mapp1" class="with-gap radio-col-green mapp" <?php echo ($projmapping == 1) ? "checked": "";?> required="required" />
										<label for="mapp1">YES</label>
										<input name="projmapping" type="radio" value="0" id="mapp2" class="with-gap radio-col-red mapp" <?php echo ($projmapping == 0) ? "checked": "";?> required="required" />
										<label for="mapp2">NO</label>																				
									</div>
								</div>
								<div class="col-md-3">
									<label for="" class="control-label">Inspection Required? *:</label>
									<div class="form-line"> 
										<input name="projinspection" type="radio" value="1" id="insp1" class="with-gap radio-col-green insp" <?php echo ($projinspection == 1) ? "checked": "";?> required="required" />
										<label for="insp1">YES</label>
										<input name="projinspection" type="radio" value="0" id="insp2" class="with-gap radio-col-red insp" <?php echo ($projinspection == 0) ? "checked": "";?> required="required" />
										<label for="insp2">NO</label> 
									</div>
								</div>
								<div class="col-md-3">
									<label class="control-label">Implementation Method *:</label>
									<div class="form-line">
										<select name="projimplmethod" id="projimplmethod" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
											<option value="">.... Select the method ....</option>
											<?php
											do {
												if ($row_rsProjImplMethod['id'] == $projcategory) {
                                                    echo '<option value="' . $row_rsProjImplMethod['id'] . '" selected>' . $row_rsProjImplMethod['method'] . '</option>';
                                                } else {
                                                    echo '<option value="' . $row_rsProjImplMethod['id'] . '">' . $row_rsProjImplMethod['method'] . '</option>';
                                                }
											} while ($row_rsProjImplMethod = $query_rsProjImplMethod->fetch());
											?>
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<label class="control-label">Project <?= $level1label ?>*:</label>
									<div class="form-line">
										<input type="hidden" name="level1label" id="level1label" value="<?= $projcommunity ?>" />
										<select name="projcommunity[]" id="projcommunity" onchange="conservancy()" data-actions-box="true" class="form-control show-tick selectpicker" title="Choose Multipe" multiple style="border:#CCC thin solid; border-radius:5px; width:98%; padding-left:50px" required>
											<?php
                                            function valid_data($db, $comm)
                                            {
                                                $query_ward = $db->prepare("SELECT id, state FROM tbl_state WHERE parent='$comm'");
                                                $query_ward->execute();

                                                $data_val = [];
                                                while ($row = $query_ward->fetch()) {
                                                    $level2 = $row['id'];

                                                    $query_rsLocations = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:id");
                                                    $query_rsLocations->execute(array(":id" => $level2));

                                                    $row_rsLocations = $query_rsLocations->fetch();
                                                    $total_locations = $query_rsLocations->rowCount();
                                                    if ($total_locations > 0) {
                                                        $data_val[] = true;
                                                    } else {
                                                        $data_val[]  = false;
                                                    }
                                                }

                                                if (in_array(true, $data_val)) {
                                                    return true;
                                                } else {
                                                    return false;
                                                }
                                            }
                                            do {
                                                $comm = $row_rsComm['id'];
                                                $handler = valid_data($db, $comm);
                                                if ($handler) {
                                            ?>
                                                    <option value="<?php echo $row_rsComm['id'] ?>"><?php echo $row_rsComm['state'] ?></option>
                                            <?php
                                                }
                                            } while ($row_rsComm = $query_rsComm->fetch());
                                            ?>
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<label class="control-label">Project <?= $level2label ?>*:</label>
									<div class="form-line">
										<input type="hidden" name="level2label" id="level2label" value="<?= $projlga ?>" />
										<select name="projlga[]" id="projlga" onchange="ecosystem()" class="form-control show-tick selectpicker" multiple data-actions-box="true" title="Choose Multipe" style="border:#CCC thin solid; border-radius:5px; width:98%; padding-right:0px" required>
											<option value="" style="padding-right:0px">.... Select <?= $level1label ?> First ....</option>
											<?php
												$data = '';
												$ward = explode(",", $projlga);
												$community = explode(",", $projcommunity);
												for ($j = 0; $j < count($community); $j++) {
													$query_Community = $db->prepare("SELECT id, state FROM tbl_state WHERE id='$community[$j]'");
													$query_Community->execute();
													$row_community = $query_Community->fetch();
													$level1 = $row_community['state'];

													$data .= '
													<optgroup label="' . $level1 . '"> ';
													$query_ward = $db->prepare("SELECT id, state FROM tbl_state WHERE parent='$community[$j]'");
													$query_ward->execute();
													while ($row = $query_ward->fetch()) {
														$level2 = $row['id'];
														$query_rsLocations = $db->prepare("SELECT id, state FROM tbl_state WHERE parent=:id");
														$query_rsLocations->execute(array(":id" => $level2));
														$row_rsLocations = $query_rsLocations->fetch();
														$total_locations = $query_rsLocations->rowCount();
														if ($total_locations > 0) {
															$data .= '<option value="' . $row['id'] . '"> ' . $row['state'] . '</option>';
														}
													}
													$data .= '
														<optgroup>';
												}
												echo $data;
                                            ?>
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<label class="control-label">Project <?= $level3label ?>*:</label>
									<div class="form-line"> 
										<input type="hidden" name="level3label" id="level3label" value="<?= $projstate ?>" />
										<select name="projstate[]" class="form-control show-tick selectpicker" onchange="forest()" data-actions-box="true" title="Choose Multipe" multiple id="projstate" style="border:#CCC thin solid; border-radius:5px; width:98%; padding-right:0px" required>
											<option value="" style="padding-right:0px">.... Select <?= $level2label ?> First ....</option>
											<?php
                                                $ward = explode(",", $projlga);
                                                $community = explode(",", $projcommunity);
                                                $data = '';
                                                for ($j = 0; $j < count($ward); $j++) {
                                                    $query_ward = $db->prepare("SELECT id, state, parent FROM tbl_state WHERE id='$ward[$j]'");
                                                    $query_ward->execute();
                                                    $row_ward = $query_ward->fetch();
                                                    $level2 = $row_ward['state'];
                                                    $parent = $row_ward['parent'];

                                                    $query_rsComm = $db->prepare("SELECT id, state FROM tbl_state WHERE parent IS NULL AND id='$parent' LIMIT 1");
                                                    $query_rsComm->execute();
                                                    $row_rsComm = $query_rsComm->fetch();
                                                    $community = $row_rsComm['state'];

                                                    $query_loca = $db->prepare("SELECT id, state FROM tbl_state WHERE parent='$ward[$j]'");
                                                    $query_loca->execute();
                                                    $data .= '<optgroup label="' . $level2 . ' (' .  $community . ')">';
                                                    while ($row = $query_loca->fetch()) {
                                                        $data .= '<option value="' . $row['id'] . '"> ' . $row['state'] . '</option>';
                                                    }
                                                    $data .= '<optgroup>';
                                                }
                                                echo $data;
                                            ?>
										</select>
									</div>
								</div>
								<script>
                                    $(document).ready(function() {
                                        var level1 = "[<?php echo $projcommunity ?>]";
                                        var projcommunity = JSON.parse(level1);
                                        $("#projcommunity").val(projcommunity);

                                        var level2 = "[<?php echo $projlga ?>]";
                                        var projlga = JSON.parse(level2);
                                        $("#projlga").val(projlga);

                                        var level3 = "[<?php echo $projstate ?>]";
                                        var projstate = JSON.parse(level3);
                                        $("#projstate").val(projstate);

                                    });
                                </script>
								<div class="col-md-12">
									<button class="btn btn-primary nextBtn pull-right" id="nextT" type="button">Next</button>
								</div>
							</fieldset>
							<fieldset class="scheduler-border row setup-content" id="step-2">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">ADD OUTPUT PLAN</legend>
								<fieldset class="scheduler-border">
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">3.0 Add Output Details </legend>
                                    <div class="col-md-12" id="projoutputTable">
                                        <div class="col-md-12" id="projoutputTable">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover" id="output_table" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="3%">#</th>
                                                            <th width="47%">Output</th>
                                                            <th width="30%">Indicator</th>
                                                            <th width="15%">Other Details</th>
                                                            <th width="5%">
                                                                <button type="button" name="addplus" id="addplus_output" onclick="add_row_output('<?= $projid ?>');" class="btn btn-success btn-sm">
                                                                    <span class="glyphicon glyphicon-plus">
                                                                    </span>
                                                                </button>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="output_table_body">
                                                        <tr></tr>
                                                        <?php
                                                        $states_p = '';
                                                        $query_rsOutput =  $db->prepare("SELECT * FROM tbl_project_details WHERE projid ='$projid' ORDER BY id");
                                                        $query_rsOutput->execute();
                                                        $row_rsOutput = $query_rsOutput->fetch();
                                                        $totalRows_rsOutput = $query_rsOutput->rowCount();
                                                        $rowno = 0;
                                                        $dboutputd = '';
                                                        if ($totalRows_rsOutput > 0) {
                                                            do {
                                                                $outputid = $row_rsOutput['outputid'];
                                                                $opids = $row_rsOutput['id'];
                                                                $indicatorID = $row_rsOutput['indicator'];
                                                                $rowno++;
                                                                //get output name 
                                                                $query_rsprogOutput =  $db->prepare("SELECT * FROM  tbl_progdetails WHERE id ='$outputid' ");
                                                                $query_rsprogOutput->execute();
                                                                $row_rsprogOutput = $query_rsprogOutput->fetch();
                                                                $totalRows_rsprogOutput = $query_rsprogOutput->rowCount();
                                                                $outputName = $row_rsprogOutput['output'];

                                                                //get indicator name 
                                                                $query_Indicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid ='$indicatorID' ");
                                                                $query_Indicator->execute();
                                                                $row = $query_Indicator->fetch();
                                                                $indname = $row['indicator_name'];
                                                                $ben_diss = $row['disaggregated'];
                                                                $unit = $row['indicator_unit'];

                                                                $query_rsunit = $db->prepare("SELECT * FROM tbl_measurement_units WHERE id ='$unit' ");
                                                                $query_rsunit->execute();
                                                                $row_rsunit = $query_rsunit->fetch();
                                                                $opunit = $row_rsunit['unit'];

																$indicator_of =$opunit . " of " . $opunit;
                                                                $projfscyear = $row_rsOutput['year'];
                                                                $query_rsFscYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year where id ='$projfscyear' ");
                                                                $query_rsFscYear->execute();
                                                                $row_rsFscYear = $query_rsFscYear->fetch();
                                                                $projstartYear = $row_rsFscYear['yr'];

                                                                $duration = floor($projduration / 365);
                                                                $remaining  = $projduration % 365;

                                                                if ($remaining > 0) {
                                                                    $duration = $duration  + 1;
                                                                }

                                                                $outputId = [];
                                                                for ($i  = 0; $i < $duration; $i++) {
                                                                    $query_Target = $db->prepare("SELECT  * FROM `tbl_progdetails` WHERE progid ='$progid' and  year ='$projstartYear' ");
                                                                    $query_Target->execute();
                                                                    $row_Target = $query_Target->fetch();
                                                                    $totalRows_Target = $query_Target->rowCount();
                                                                    do {
                                                                        $progTarget = $row_Target['target'];
                                                                        $indicator = $row_Target['indicator'];
                                                                        $projoutput = $row_Target['output'];

                                                                        // /get target used by all projects under the program 
                                                                        $query_projTarget = $db->prepare("SELECT SUM(target) as projtarget FROM  tbl_project_output_details WHERE progid='$progid' AND indicator ='$indicator' and year='$projstartYear' ");
                                                                        $query_projTarget->execute();
                                                                        $rowproj = $query_projTarget->fetch();
                                                                        $totalRows_Target = $query_projTarget->rowCount();

                                                                        // get targets of this project 
                                                                        $query_outputTarget = $db->prepare("SELECT SUM(target) as target FROM  tbl_project_output_details WHERE progid='$progid' AND indicator ='$indicator' and year='$projstartYear' AND projid='$projid' ");
                                                                        $query_outputTarget->execute();
                                                                        $row_outputTarget = $query_outputTarget->fetch();
                                                                        $totalRows_Target = $query_outputTarget->rowCount();
                                                                        $OutputTarget = $row_outputTarget['target'];

                                                                        $totalUsedTarget  =  $rowproj['projtarget'];
                                                                        $projTarget = ($progTarget - $totalUsedTarget) + $OutputTarget;
                                                                        if ($projTarget > 0) {
                                                                            $outputId[] = $indicator;
                                                                        }
                                                                    } while ($row_Target = $query_Target->fetch());
                                                                    $projstartYear++;
                                                                }

                                                                $outputId = array_unique($outputId);
                                                        ?>
                                                                <tr id="row<?= $rowno ?>">
                                                                    <td>
                                                                        <?= $rowno ?>
                                                                    </td>
                                                                    <td>
                                                                        <select data-id="<?= $rowno ?>" name="output[]" class="form-control validoutcome select_output" id="outputrow<?= $rowno ?>" onchange='getIndicator("row<?= $rowno ?>")' class="form-control validoutcome selectOutcome" required="required" disabled>
                                                                            <option value="">Select Output from list</option>
                                                                            <?php
                                                                            foreach ($outputId as $output) {
                                                                                $query_rsYear =  $db->prepare("SELECT id, output FROM tbl_progdetails where indicator ='$output' AND progid= '$progid' ");
                                                                                $query_rsYear->execute();
                                                                                $row_rsYear = $query_rsYear->fetch();
                                                                                $projoutput = $row_rsYear['output'];
                                                                                $opid = $row_rsYear['id'];

                                                                                if ($opid == $outputid) {
                                                                                    echo '<option value="' . $opid . '" selected>' . $projoutput . '</option>';
                                                                                } else {
                                                                                    echo '<option value="' . $opid . '">' . $projoutput . '</option>';
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <input type="hidden" name="outputIdsTrue[]" id="outputIdsTruerow<?= $rowno ?>" value="<?php echo $opids ?>" />
                                                                        <input type="hidden" name="ben_diss[]" id="ben_dissrow<?= $rowno  ?>" value="<?= $ben_diss ?>" />
                                                                    </td>
                                                                    <td>
                                                                        <input type="hidden" name="indicatorid[]" id="indicatoridrow<?= $rowno ?>" value="<?= $indicatorID ?>" />
                                                                        <input type="hidden" name="unit_measure[]" id="unit_measurerow<?= $rowno ?>" value="<?= $opunit ?>" />
                                                                        <input type="text" name="indicator[]" id="indicatorrow<?= $rowno ?>" placeholder="Enter" value=" <?= $indname ?>" class="form-control" disabled />
                                                                    </td>
                                                                    <td>
                                                                        <a type="button" data-toggle="modal" data-target="#outputItemModal" onclick='output_year("row<?= $rowno ?>")' id="outputItemModalBtnrow<?= $rowno ?>"> Edit Details</a>
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-danger btn-sm" id="delete" onclick='delete_row_output("row<?= $rowno ?>")'>
                                                                            <span class="glyphicon glyphicon-minus"></span>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            } while ($row_rsOutput = $query_rsOutput->fetch());
                                                            $states_p = '<input type="hidden" name="stateVal" id="stateVal" value="1">';
                                                        } else {
                                                            $states_p = '<input type="hidden" name="stateVal" id="stateVal" value="">';
                                                            ?>
                                                            <tr id="hideinfo">
                                                                <td colspan="5">
                                                                    No Output details found!!
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="val_states">
                                        <?= $states_p ?>
                                    </div>
                                </fieldset>
								<?php
									$query_OutputData = $db->prepare("SELECT * FROM tbl_project_details WHERE projid = '$projid'  ORDER BY id");
									$query_OutputData->execute();
									$rows_OutpuData = $query_OutputData->rowCount();
									$row_OutputData =  $query_OutputData->fetch();

									$counter = 0;
									$location_Targets = '';
									$elementT = '';
									$ben_diss = '';
									$opTable = ''; 

									if ($rows_OutpuData > 0) {
										
										do {
											$counter++;
											// get indicator name 
											$indicator = $row_OutputData['indicator'];
											$t_target = $row_OutputData['total_target'];
											$projoutputID = $row_OutputData['id'];

											$query_rsIndicator = $db->prepare("SELECT indicator_name, indid,indicator_disaggregation  FROM tbl_indicator WHERE indid ='$indicator'");
											$query_rsIndicator->execute();
											$row_rsIndicator = $query_rsIndicator->fetch();
											$indname = $row_rsIndicator['indicator_name'];
											$ben_diss = $row_rsIndicator['indicator_disaggregation'];

											
	
											// get unit 
											$query_Indicator = $db->prepare("SELECT tbl_measurement_units.unit FROM tbl_indicator INNER JOIN tbl_measurement_units ON tbl_measurement_units.id =tbl_indicator.indicator_unit WHERE tbl_indicator.indid ='$indicator' AND baseline=1 AND indicator_category='Output' ");
											$query_Indicator->execute();
											$row = $query_Indicator->fetch();
											$unit = $row['unit'];
	 
											// Get outputstart year
											$year = $row_OutputData['year'];
											$query_rsIndicatorYear =  $db->prepare("SELECT yr FROM tbl_fiscal_year WHERE id='$year'");
											$query_rsIndicatorYear->execute();
											$row_rsIndicatorYear = $query_rsIndicatorYear->fetch();
											$projstartyear = $row_rsIndicatorYear['yr'];
	
											// get output name 
											$outputid = $row_OutputData['outputid'];
											$query_rsOutput = $db->prepare("SELECT * FROM tbl_progdetails WHERE id='$outputid'");
											$query_rsOutput->execute();
											$row_rsOutput = $query_rsOutput->fetch();
											$outputName = $row_rsOutput['output'];
											$programid = $row_rsOutput['progid'];
	
											//get financial years with specific outputid 
											$query_projYear = $db->prepare("SELECT * FROM  tbl_project_output_details  WHERE projid = '$projid' and projoutputid = '$projoutputID' ORDER BY year");
											$query_projYear->execute();
											$rows_OutpuprojYear = $query_projYear->rowCount();
											$row_projYear =  $query_projYear->fetch();

											if ($rows_OutpuprojYear > 0) {
												$elementT .= '
												<div class="elementT" id="target_div_' . trim($projoutputID)  . '">
													  <div class="header">  
														  <div class="col-md-6 clearfix" style="margin-top:5px; margin-bottom:5px">
															  <h5 style="color:#FF5722"><strong> Output ' . $counter . ': ' .  $outputName . '</strong></h5>
															<input type="hidden" value="' . $outputName . '" id="workplan_opName' . trim($projoutputID) . '">
														  </div>
														  <div class="col-md-6 clearfix" style="margin-top:5px; margin-bottom:5px">
															  <h5 style="color:#FF5722"><strong> Indicator: ' . $indname . '</strong></h5>
																<input type="hidden" value="' . $indname . '" id="indicatorName' . trim($projoutputID) . '">
																<input type="hidden" value="' . $unit . '" id="unit' . trim($projoutputID) . '">
																<input type="hidden" value="' . $ben_diss . '" id="ben_diss_value' . trim($projoutputID) . '">
														  </div> 
													  </div>
														  <div class="row">
															  <div class="col-md-12">
																  <div class="spanYears">';
																		$TargetPlan = "";
																		$containerTH = "";
																		$containerTH2 = "";
																		$containerTB = "";
																		do {
																			$Pyear =  $row_projYear['year'];
																			$target =  $row_projYear['target'];
																			$Fyear =  $Pyear + 1;
	
																			// get program targets
																			$query_getProgTarget = $db->prepare("SELECT * FROM tbl_progdetails WHERE indicator ='$indicator' AND year='$Pyear' AND progid='$programid' ");
																			$query_getProgTarget->execute();
																			$row_rsProgTarget = $query_getProgTarget->fetch();
																			$totalRows_ProgTarget = $query_getProgTarget->rowCount();
																			$progtarget  =  $row_rsProgTarget['target'];
	
																			// get sum of all used program targets under specific indicator 
																			$query_rsprojTarget = $db->prepare("SELECT SUM(target) as projtarget FROM  tbl_project_output_details  WHERE progid='$programid' AND indicator ='$indicator' and year='$Pyear'  LIMIT 1 ");
																			$query_rsprojTarget->execute();
																			$row_rsprojTarget = $query_rsprojTarget->fetch();
																			$totalRows_rsprojTarget = $query_rsprojTarget->rowCount();
																			$totalUsedTarget  =  $row_rsprojTarget['projtarget'];
	
	
																			// get sum of the given project indicator targets
																			$query_proTargetSum = $db->prepare("SELECT SUM(target) as projtargets FROM  tbl_project_output_details WHERE progid='$programid' AND indicator ='$indicator' and year='$Pyear' and projid='$projid'  LIMIT 1 ");
																			$query_proTargetSum->execute();
																			$rowprojSum = $query_proTargetSum->fetch();
																			$targetSum = $rowprojSum['projtargets'];
	
																			$projTarget = ($progtarget - $totalUsedTarget)  + $targetSum;
																			$projTargetB = ($progtarget - $totalUsedTarget);
	
																			$containerTH .= ' <th>
																			' . $Pyear . '/' . $Fyear . '
																			<input type="hidden" class="output_years' . $projoutputID  . '" name="output_years' . $projoutputID  . '[]" value="' . $Pyear . ' " >
																			<input type="hidden" name="dboutputId[]" value="' . $outputName . ' " >  
																			<input type="hidden" id="outputName' . $projoutputID . '" name="outputName[]" value="' . $projoutputID . ' " > 
																			<input type="hidden"   id="cyear_target' . $projoutputID .  $Pyear . '" name="cyear_target' . $projoutputID . '[]" value="' . $projTarget . ' " >
																			<span>Program Plan Bal: </span>(<span style="color:red" id="year_target' . $projoutputID .  $Pyear . '" >' . number_format($projTargetB, 2) . '</span>) ' . $unit . '
																			</th>';
																			$containerTB .= '<td> 
																			<input type="number" data-id=""  name="target_year' . $projoutputID . '[]" placeholder="target" value="' . $target . '"  id="target_year' . $projoutputID . $Pyear . '" class="form-control workplanTarget' . $projoutputID . '"
																			onkeyup=get_op_sum_target(' . $projoutputID . ',' . $Pyear . ') required >
																			</td>';
																		} while ($row_projYear =  $query_projYear->fetch());
																		$elementT .= '   
																	</div>
															  </div>
														  </div>
	
														  <div class="table-responsive">
															  <table class="table table-bordered table-striped table-hover" id="targets" style="width:100%">
																  <thead>
																  <tr>
																	<th colspan="' . $rows_OutpuprojYear . '" >
																	<input type="hidden"   id="opid_name' . $projoutputID . '" name="opid_name' . $projoutputID . '[]" value="' . $outputName . ' " >
																	<input type="hidden"   id="coptarget_target' . $projoutputID . '" name="coptarget_target' . $projoutputID . '[]" value="' . $t_target . ' " >
																		<span>Output Target Bal: </span>
																		<span style="color:red" id="op_target' . $projoutputID . '" >
																			' . number_format(0, 2) . ' 
																		</span>
																		' . $unit . '
																	</th>
																  </tr>
																		<tr id="target_headrow' .  $counter  .  '">
																		  ' . $containerTH . '
																		</tr>
																  </thead>
																  <tbody>
																	  <tr id="target_bodyheadrow' .  $counter  .  '">
																		  ' . $containerTH2 . '
																	  </tr>
																	  <tr id="target_bodyrow' .  $counter  .  '">
																		  ' . $containerTB . '
																	  </tr>
																  </tbody>
															  </table>
														  </div>
														</div>
													  ';
											}
	
											// if($ben_diss == 1){
											// 	$query_rsOpDiss =  $db->prepare("SELECT * FROM tbl_output_disaggregation d INNER JOIN tbl_state s ON s.id = d.outputstate WHERE outputid = '$projoutputID' ");
											// 	$query_rsOpDiss->execute();
											// 	$row_rsOpDiss = $query_rsOpDiss->fetch();
											// 	$count_down  = $query_rsOpDiss->rowCount();
	
											// 	if ($count_down > 0) {
											// 		$location_Targets .= '
											// 		<div class"element" id="div_' . $projoutputID . '">
											// 			<div class="header">  
											// 				<div class="col-md-6 clearfix" style="margin-top:5px; margin-bottom:5px">
											// 					<h5 style="color:#FF5722"><strong> Output ' . $counter . ': ' .  $outputName . '</strong></h5>
											// 				</div>
											// 				<div class="col-md-6 clearfix" style="margin-top:5px; margin-bottom:5px">
											// 					<h5 style="color:#FF5722"><strong> Indicator: ' . $indname . '</strong></h5>
											// 				</div> 
											// 				<div class="col-md-6" style="margin-top:5px; margin-bottom:5px">
											// 					<h5 style="color:#2196F3"><strong> Indicator: ' . $indname . '</strong></h5>
											// 					<input type="hidden" value="' . $indname . '" id="indicatorName' . trim($projoutputID) . '">
											// 					<input type="hidden" value="' . $unit . '" id="unitNameL' . trim($projoutputID) . '">
											// 				</div>
											// 			</div>
											// 			<div class="row">
											// 				<div class="col-md-12"> 
											// 				</div>
											// 			</div> 
											// 			<div class="row clearfix" >
											// 				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
											// 					<div class="table-responsive">
											// 						<table class="table table-bordered table-striped table-hover" id="" style="width:100%">
											// 							<thead>  
											// 								<tr>
											// 									<th>#</th>
											// 									<th>Location</th>
											// 									<th>Value</th>
											// 								</tr>
											// 							</thead>
											// 							<tbody>';
											// 							$q =0;
											// 							do {
											// 								$q++;
											// 								$state = $row_rsOpDiss['outputstate'];
											// 								$level3 = $row_rsOpDiss['state'];
											// 								$total_target = $row_rsOpDiss['total_target'];
							
											// 								$query_rsOpDiss_val =  $db->prepare("SELECT * FROM tbl_indicator_level3_disaggregations s INNER JOIN tbl_projects_location_targets l ON l.locationdisid = s.id WHERE l.projid = '$projid' and outputid = '$projoutputID' AND  s.level3='$state' ");
											// 								$query_rsOpDiss_val->execute(); 
											// 								$row_rsOpDiss_val = $query_rsOpDiss_val->fetch();
											// 								$locations = $query_rsOpDiss_val->rowCount();
			
											// 								// get the forest 
											// 								$query_ward = $db->prepare("SELECT id, state, parent FROM tbl_state WHERE id='$state'");
											// 								$query_ward->execute();
											// 								$row_ward = $query_ward->fetch();
											// 								$level3 = $row_ward['state'];
			
											// 								$location_Targets .= '
											// 								<tr>
											// 									<th>'.$q.'</th>
											// 									<th colspan="2">
											// 										<input type="hidden"   name="locate_output_name[]" id="locate_opid' . $projoutputID . '" value="' . $outputName . '"/>  
											// 										<input type="hidden"   name="level3label' . $state . $projoutputID . '[]" id="level3label' . $state . $projoutputID . '" value="' . $level3 . '"/>  
											// 										<input type="hidden"   name="unitName' . $state . $projoutputID . '[]" id="unitName' . $state . $projoutputID . '" value="' . $unit . '"/>  
											// 										<input type="hidden" data-id="' . $level3 . '"  name="outputstate' . $projoutputID . '[]" class="outputstate' . $projoutputID . '" value="' . $state . '" /> 
											// 										' . $level3label . ': ' . $level3 . '
											// 										<input type="hidden"  class="form-control" id="ceilinglocation_target' . $state . $projoutputID . '"  name="ceiloutputlocationtarget' . $projoutputID . '[]" value="' . $total_target . '" />
											// 										<span id="state_ceil' . $state . $projoutputID . '" style="color:red" > (' . number_format(0, 2) . ')</span> ' . $unit . '
											// 									</th>
											// 								</tr>';
											// 								$p = 0;
											// 								do {
											// 									$name = $row_rsOpDiss_val['locationdisid'];
											// 									$location = $row_rsOpDiss_val['disaggregations'];
											// 									$value = $row_rsOpDiss_val['target'];
											// 									$p++;
											// 									$gen_number =  mt_rand(15, 500);
											// 									$number = $p . $gen_number; 
											// 									$location_Targets .= '
											// 									<tr>
											// 										<td>'.$q . "." . $p.'</td>
											// 										<td>' . $location . '</td>
											// 										<td>
											// 											 <input type="hidden"   name="outputlocation' . $state . $projoutputID . '[]" id="locate' . $number . '" value="' . $name . '"/>  
											// 											<input type="number" value="' . $value . '" data-loc="' . $location . '"  data-id="' . $projoutputID . '" id="locate_numb' . $number . '" placeholder="' . $unit . '" class="form-control locate_total' . $state .  $projoutputID . '" onkeyup=get_sum("' . $state . '","' . $number . '") onchange=get_sum("' . $state . '","' . $number . '")  name="outputlocationtarget' . $state . $projoutputID . '[]" value="" required />
											// 										</td> 
											// 									</tr>';
											// 								} while ($row_rsOpDiss_val = $query_rsOpDiss_val->fetch());
											// 							} while ($row_rsOpDiss = $query_rsOpDiss->fetch()); 
											// 							$location_Targets .= '
											// 							</tbody>
											// 						</table>
											// 					</div>
											// 				</div>
											// 			</div>
											// 		</div>';
											// 	}
											// } 

												$Targets = '
											<fieldset class="scheduler-border" id="op_targets_div_fieldset">
												<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">3.1 Output Plan Details </legend>
												<div class="row clearfix " id="rowcontainerrow">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<div class="card">
															<div class="header">  
																<div class="" style="margin-top:5px; margin-bottom:5px">
																	<h4 class="list-group-item list-group-item list-group-item-action active"><strong> Output Targets</strong></h5>
																</div>
															</div>
															<div class="body">
																<div id="op_targets_div">
																	<div class="elementT">
																	</div>
																	' . $elementT . '
																</div>
															</div>
														</div>
													</div>
												</div>
											</fieldset>';
 
												// $Targets .= '
												// <fieldset class="scheduler-border" id="location_targets_div_fieldset">
												// 	<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">3.2 Location Dissagragation Details </legend>		
												// 	<div class="row clearfix " id="rowcontainerrow">
												// 		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												// 			<div class="card">
												// 				<div class="header">   
												// 					<div class="" style="margin-top:5px; margin-bottom:5px">
												// 						<h4 class="list-group-item list-group-item list-group-item-action active"><strong> Output Distribution</strong></h5>
												// 					</div>
												// 				</div>
												// 				<div class="body">
												// 					<div id="location_targets_div">
												// 						<div class="element"> 
												// 						</div> 
												// 						'  . $location_Targets . '
												// 					</div>
												// 				</div>
												// 			</div>
												// 		</div>
												// 	</div>
												// </fieldset>'; 
											echo  $Targets;
											
										} while ($row_OutputData =  $query_OutputData->fetch());
									}else{
										?>
										<fieldset class="scheduler-border" id="op_targets_div_fieldset">
											<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">3.1 Output Plan Details </legend>
											<div id="op_targets_div">
												<div class="elementT">
												</div>
											</div>
										</fieldset>
										<fieldset class="scheduler-border" id="location_targets_div_fieldset">
											<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">3.2 Location Dissagragation Details </legend>
											<div id="location_targets_div">
												<div class="element">
												</div>
											</div>
										</fieldset>
										<?php
									}

									?>

 
								<div class="col-md-12">
									<ul class="list-inline pull-right">
										<li><button type="button" class="btn btn-warning prev-step">Previous</button></li>
										<li><button class="btn btn-primary nextBtn btn-sm" type="button">Next</button> </li>
									</ul>
								</div>
							</fieldset>
							<fieldset class="scheduler-border row setup-content" id="step-3">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">ADD IMPLEMENTORS</legend>
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Implementors</legend>
									<div class="col-md-6">
										<label class="control-label">Lead Implementor *:</label>
										<div class="form-line">
											<select name="projleadimplementor" id="projleadimplementor" onchange="project_lead_implementor()" class="form-control show-tick selectpicker" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
												<option value="">.... Select from list ....</option>
												<?php                                                
                                                do {
                                                    if ($row_rsPartner['id'] == $lead_implementer) {
                                                        echo '<option value="' . $row_rsPartner['id'] . '" selected>' . $row_rsPartner['financier'] . '</option>';
                                                    } else {
                                                        echo '<option value="' . $row_rsPartner['id'] . '">' . $row_rsPartner['financier'] . '</option>';
                                                    }
                                                } while ($row_rsPartner = $query_rsPartner->fetch());
												?>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<label class="control-label">Partner *:</label>
										<div class="form-line">
											<select name="projimplementingpartner[]" onchange="project_implementing_partner()" multiple id="projimplementingpartner" class="form-control show-tick selectpicker" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
												<option value="">.... Select from list ....</option>
												<?php
													$query_rsImplPartner =  $db->prepare("SELECT * FROM tbl_financiers WHERE active=1");
													$query_rsImplPartner->execute();
													$row_rsImplPartner = $query_rsImplPartner->fetch();
													$ImplId = 0;

													if (in_array($ImplId, $implementing_partner)) {
														echo '<option value="0" selected> Not applicable</option>';
													} else {
														echo '<option value="0"> Not applicable</option>';
													}

													do {
														$ptnid = $row_rsImplPartner['id'];
														$handler = true;
														if (in_array($ptnid, $implementing_partner)) {
															$handler = true;
														} else {
															$handler = false;
														}

														if ($ptnid != $lead_implementer) {
															if ($handler) {
																echo '<option value="' . $ptnid . '" selected> ' . $row_rsImplPartner['financier'] . '</option>';
															} else {
																echo '<option value="' . $ptnid . '"> ' . $row_rsImplPartner['financier'] . '</option>';
															}
														}
													} while ($row_rsImplPartner = $query_rsImplPartner->fetch());
                                                ?>
											</select>
										</div>
									</div>
									<div class="col-md-12">
									</div>
								</fieldset>
								<fieldset class="scheduler-border">
                                    <?php
										$query_rsOutputCeiling =  $db->prepare("SELECT SUM(budget) as budget FROM  tbl_project_details WHERE projid ='$projid'");
										$query_rsOutputCeiling->execute();
										$row_rsOutputCeiling = $query_rsOutputCeiling->fetch();
										$totalRows_rsOutputCeiling = $query_rsOutputCeiling->rowCount();
										$projcounter = $row_rsOutputCeiling['budget'];

										$query_rsBalanace =  $db->prepare("SELECT SUM(amountfunding) as budget FROM  tbl_projfunding WHERE projid ='$projid'");
										$query_rsBalanace->execute();
										$row_rsBalanace = $query_rsBalanace->fetch();
										$totalRows_rsBalanace = $query_rsBalanace->rowCount();
										$projfunds = $row_rsBalanace['budget'];
										$remaining_funds = $projcounter - $projfunds;
                                    ?>
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Project Financier/s</legend>
                                    <div class="col-md-6" id="indirectbenname">
                                        <label for="projindirectbeneficiary" id="projindirectbeneficiary" class="control-label">Total Output Cost *:</label>
                                        <div class="form-input">
                                            <input type="hidden" name="financierceiling" id="financierceiling" value="<?= $projcounter ?>">
                                            <input type="text" name="outputcost" id="outputcost" value="<?= $remaining_funds ?>" placeholder="0" class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-12" id="projfinancier">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover" id="financier_table" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">#</th>
                                                        <th width="30%">Source</th>
                                                        <th width="30%">Ceiling (Ksh)</th>
                                                        <th width="30%">Amount (Ksh)</th>
                                                        <th width="5%">
                                                            <button type="button" name="addplus" id="addplus_financier" onclick="add_row_financier();" class="btn btn-success btn-sm">
                                                                <span class="glyphicon glyphicon-plus">
                                                                </span>
                                                            </button>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="financier_table_body">
                                                    <tr></tr>
                                                    <?php
                                                    $rowno = 0;
                                                    $query_rsProjFinancier =  $db->prepare("SELECT * FROM tbl_projfunding WHERE projid =:projid");
                                                    $query_rsProjFinancier->execute(array(":projid" => $projid));
                                                    $row_rsProjFinancier = $query_rsProjFinancier->fetch();
                                                    $totalRows_rsProjFinancier = $query_rsProjFinancier->rowCount();

                                                    if ($totalRows_rsProjFinancier > 0) {
                                                        do {
                                                            $rowno++;
                                                            $sourcecategory =  $row_rsProjFinancier['sourcecategory'];
                                                            $projamountfunding =  $row_rsProjFinancier['amountfunding'];

                                                            $query_rsFunding =  $db->prepare("SELECT p.amountfunding, p.sourcecategory,f.type FROM tbl_myprogfunding p INNER JOIN tbl_funding_type f  ON  p.sourcecategory= f.id WHERE p.progid =:progid AND p.sourcecategory =:sourcecategory");
                                                            $query_rsFunding->execute(array(":progid" => $progid, ":sourcecategory" => $sourcecategory));
                                                            $row_rsFunding = $query_rsFunding->fetch();
                                                            $totalRows_rsFunding = $query_rsFunding->rowCount();

                                                            $source_name = $row_rsFunding['type'];
                                                            $localamount = $row_rsFunding['amountfunding'];


                                                            // get the sum of the amount of fuds used up by all pojects under this given program 
                                                            $query_rsprojectFunding =  $db->prepare("SELECT SUM(amountfunding) as amountfunding FROM tbl_projfunding WHERE sourcecategory =:sourcecategory AND progid=:progid");
                                                            $query_rsprojectFunding->execute(array(":progid" => $progid, ":sourcecategory" => $sourcecategory));
                                                            $row_rsprojectFunding = $query_rsprojectFunding->fetch();
                                                            $totalRows_rsprojectFunding = $query_rsprojectFunding->rowCount();
                                                            $amountprojectFunding = $row_rsprojectFunding['amountfunding'];

                                                            // Get the ceiling by adding the amount spent on this project to the overall remaining funds 
                                                            $remaining =  ($localamount - $amountprojectFunding) + $projamountfunding;
                                                            $remainingB =  $localamount - $amountprojectFunding;

                                                            $query_rsFunding =  $db->prepare("SELECT p.amountfunding, p.sourcecategory,f.type  FROM tbl_myprogfunding p INNER JOIN tbl_funding_type f  ON  p.sourcecategory= f.id WHERE progid =:progid");
                                                            $query_rsFunding->execute(array(":progid" => $progid));
                                                            $row_rsFunding = $query_rsFunding->fetch();
                                                            $totalRows_rsFunding = $query_rsFunding->rowCount();


                                                            $inputs = '<option value="">Select Financier from list</option>';
                                                            do {
                                                                $source_category = $row_rsFunding['sourcecategory'];
                                                                $source_name = $row_rsFunding['type'];
                                                                $progfunds = $row_rsFunding['amountfunding'];

                                                                $query_rsprojFunding =  $db->prepare("SELECT SUM(amountfunding) as amountfunding FROM tbl_projfunding WHERE sourcecategory =:sourcecategory AND progid=:progid ");
                                                                $query_rsprojFunding->execute(array(":progid" => $progid, ":sourcecategory" => $sourcecategory));
                                                                $row_rsprojFunding = $query_rsprojFunding->fetch();
                                                                $totalRows_rsprojFunding = $query_rsprojFunding->rowCount();
                                                                $projfunds = $row_rsprojFunding['amountfunding'];
                                                                $remainingA = ($progfunds - $projfunds) + $projamountfunding;


                                                                if ($remainingA > 0) {
                                                                    if ($source_category  == $sourcecategory) {
                                                                        $inputs .= '<option value="' . $source_category . '" selected>' . $source_name . '</option>';
                                                                    } else {
                                                                        $inputs .= '<option value="' . $source_category . '">' . $source_name . '</option>';
                                                                    }
                                                                }
                                                            } while ($row_rsFunding = $query_rsFunding->fetch());
                                                    		?>

                                                            <tr id="finrow<?= $rowno ?>">
                                                                <td>
                                                                    <?= $rowno ?>
                                                                </td>
                                                                <td>
                                                                    <select onchange='financeirChange("row<?= $rowno ?>")' data-id="<?= $rowno ?>" name="finance[]" id="financerow<?= $rowno ?>" class="form-control validoutcome selectedfinance" required="required">'
                                                                        <?php echo $inputs ?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="hidden" name="ceilingval[]" id="ceilingvalrow<?php echo $rowno ?>" value="<?php echo $remaining ?>" />
                                                                    <span id="financierCeilingrow<?php echo $rowno ?>" style="color:red">
                                                                        <?php echo number_format($remainingB, '2') ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="amountfunding[]" onkeyup='amountfunding("row<?php echo $rowno ?>")' id="amountfundingrow<?php echo $rowno ?>" placeholder="Enter" class="form-control" value="<?php echo $projamountfunding; ?>" required />
                                                                </td>

                                                                <td>
                                                                    <button type="button" class="btn btn-danger btn-sm" id="delete" onclick='delete_row_financier("finrow<?php echo $rowno ?>")'>
                                                                        <span class="glyphicon glyphicon-minus"></span>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        	<?php
                                                        } while ($row_rsProjFinancier = $query_rsProjFinancier->fetch());
                                                    } else {
                                                        ?>
                                                        <tr id="removeTr">
                                                            <td colspan="5">Add Financiers</td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </fieldset>
								<div class="col-md-12">
									<ul class="list-inline pull-right">
										<li><button type="button" class="btn btn-warning prev-step">Previous</button></li>
										<li><button class="btn btn-primary nextBtn nextBtn1 btn-sm" type="button">Next</button> </li>
									</ul>
								</div>
							</fieldset>
							<fieldset class="scheduler-border row setup-content" id="step-4">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">FILES</legend>
								<?php
									$stage = 1;
									$query_rsFile = $db->prepare("SELECT * FROM tbl_files WHERE projstage=:stage and projid=:projid");
									$query_rsFile->execute(array(":stage" => $stage, ":projid" => $projid));
									$row_rsFile = $query_rsFile->fetch();
									$totalRows_rsFile = $query_rsFile->rowCount();
                                ?>

                                <div class="row clearfix " id="rowcontainerrow">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="card">
                                            <div class="header">
                                                <div class="col-md-12 clearfix" style="margin-top:5px; margin-bottom:5px">
                                                    <h5 style="color:#FF5722"><strong> FILES </strong></h5>
                                                </div>
                                            </div>
                                            <div class="body">
                                                <div class="body table-responsive">
                                                    <table class="table table-bordered" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:2%">#</th>
                                                                <th style="width:68%">Purpose</th>
                                                                <th style="width:28%">Attachment</th>
                                                                <th style="width:2%">
                                                                    Delete
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="attachment_table">
                                                            <?php
                                                            if ($totalRows_rsFile > 0) {
                                                                $counter = 0;
                                                                do {
                                                                    $pdfname = $row_rsFile['filename'];
                                                                    $filecategory = $row_rsFile['fcategory'];
                                                                    $ext = $row_rsFile['ftype'];
                                                                    $filepath = $row_rsFile['floc'];
                                                                    $fid = $row_rsFile['fid'];
                                                                    $attachmentPurpose = $row_rsFile['reason'];
                                                                    $counter++;
                                                            ?>
                                                                    <tr id="mtng<?= $fid ?>">
                                                                        <td>
                                                                            <?= $counter ?>
                                                                        </td>
                                                                        <td>
                                                                            <?= $attachmentPurpose ?>
                                                                            <input type="hidden" name="fid[]" id="fid" class="" value="<?= $fid  ?>">
                                                                            <input type="hidden" name="ef[]" id="t" class="eattachment_purpose" value="<?= $attachmentPurpose  ?>">
                                                                        </td>
                                                                        <td>
                                                                            <?= $pdfname ?>
                                                                            <input type="hidden" name="adft[]" id="fid" class="eattachment_file" value="<?= $pdfname  ?>">
                                                                        </td>
                                                                        <td>
                                                                            <button type="button" class="btn btn-danger btn-sm" onclick='delete_attachment("mtng<?= $fid ?>")'>
                                                                                <span class="glyphicon glyphicon-minus"></span>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                            <?php
                                                                } while ($row_rsFile = $query_rsFile->fetch());
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row clearfix " id="">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="card">
                                            <div class="header">
                                                <div class="col-md-12 clearfix" style="margin-top:5px; margin-bottom:5px">
                                                    <h5 style="color:#FF5722"><strong> Add new file/s </strong></h5>
                                                </div>
                                            </div>
                                            <div class="body">
                                                <div class="body table-responsive">
                                                    <table class="table table-bordered" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:2%">#</th>
                                                                <th style="width:68%">Attachment</th>
                                                                <th style="width:28%">Purpose</th>
                                                                <th style="width:2%">
                                                                    <button type="button" name="addplus1" onclick="add_row_files_edit();" title="Add another document" class="btn btn-success btn-sm">
                                                                        <span class="glyphicon glyphicon-plus">
                                                                        </span>
                                                                    </button>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="meetings_table_edit">
                                                            <tr></tr>
                                                            <tr id="add_new_file">
                                                                <td colspan="4"> Add file </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

								<div class="col-md-12">
									<ul class="list-inline pull-right">
										<li><button type="button" class="btn btn-warning prev-step">Previous</button></li>
										<li><button class="btn btn-primary nextBtn btn-sm" onclick="display_finish()" type="button">Next</button> </li>
									</ul>
								</div>
							</fieldset>
							<fieldset class="scheduler-border row setup-content" id="step-5">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">FINISH</legend>
								<div class="row clearfix " id="">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="card">
											<div class="body">
												<div class="row">
													<div class="col-xs-12">
														<fieldset class="scheduler-border">
															<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">1.0) Project Details</legend>
															<div class="table-responsive">
																<table summary="This table shows how to create responsive tables using Bootstrap's default functionality" class="table table-bordered table-hover">
																	<thead>
																		<tr>
																			<th width="5%">#</th>
																			<th width="35%">Field</th>
																			<th width="60%">Value</th>
																		</tr>
																	</thead>
																	<tbody>
																		<tr>
																			<td>1</td>
																			<td>Programe Name</td>
																			<td id="progs"></td>
																		</tr>
																		<tr>
																			<td>2</td>
																			<td>Project Code</td>
																			<td id="projcodes"></td>
																		</tr>
																		<tr>
																			<td>3</td>
																			<td>Project Name</td>
																			<td id="projName"></td>
																		</tr>
																		<tr>
																			<td>6</td>
																			<td>Implementation Method</td>
																			<td id="implementation"></td>
																		</tr>
																		<tr>
																			<td>7</td>
																			<td>Financial Year </td>
																			<td id="projfscyears"></td>
																		</tr>
																		<tr>
																			<td>9</td>
																			<td>Project Duration </td>
																			<td id="projdurations"></td>
																		</tr>
																		<tr>
																			<td>10</td>
																			<td>Project Budget</td>
																			<td><span>Ksh.</span><span id="projcosts"></span></td>
																		</tr>
																		<tr>
																			<td>11</td>
																			<td>Evaluation Required?</td>
																			<td id="projeval"></td>
																		</tr>
																		<tr>
																			<td>11</td>
																			<td>Mapping Required?</td>
																			<td id="projmap"></td>
																		</tr>
																		<tr>
																			<td>11</td>
																			<td>Inspection Required?</td>
																			<td id="projinsps"></td>
																		</tr> 
																	</tbody>
																</table>
															</div>
															<div class="table-responsive">
																<table summary="This table shows how to create responsive tables using Bootstrap's default functionality" class="table table-bordered table-hover">
																	<thead>
																		<tr>
																			<th width="20%"><?= $level1label ?>/s</th>
																			<th width="20%"><?= $level2label ?>/s</th>
																			<th width="20%"><?= $level3label ?>/s</th>
																		</tr>
																	</thead>
																	<tbody>
																		<tr>
																			<td id="projcommunitys"></td>
																			<td id="projlgas"></td>
																			<td id="projstates"></td>
																		</tr>
																	</tbody>
																</table>
															</div>
														</fieldset>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row clearfix " id="">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="card">
											<div class="body">
												<div class="row">
													<div class="col-xs-12">
														<fieldset class="scheduler-border">
															<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">2.0) Ouput Plan Details</legend>
															<fieldset class="scheduler-border">
																<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">2.1) Output Details</legend>
																<div id="outputDataDisp">
																</div>
															</fieldset>
															<fieldset class="scheduler-border">
																<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">2.2) Output Yearly Plan</legend>
																<div id="workplanDetails">
																</div>
															</fieldset>
															<fieldset class="scheduler-border" id="location_sec_div">
																<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">2.3) Output Distribution Per Location</legend>
																<div id="locate_target_plan">
                                                                    <div class="elementDiv"></div>
																</div>
															</fieldset>
														</fieldset>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row clearfix " id="">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="card">
											<div class="body">
												<div class="row">
													<div class="col-xs-12">
														<fieldset class="scheduler-border">
															<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">3.0) Stakeholders Details</legend>
															<div class="table-responsive">
																<table summary="This table shows how to create responsive tables using Bootstrap's default functionality" class="table table-bordered table-hover">
																	<thead>
																		<tr>
																			<th width="5%">#</th>
																			<th width="35%">Field</th>
																			<th width="60%">Value</th>
																		</tr>
																	</thead>
																	<tbody>
																		<tr>
																			<td>1</td>
																			<td>Lead Implementor</td>
																			<td id="leadImple"></td>
																		</tr>
																		<tr>
																			<td>2</td>
																			<td>Implementing Partner</td>
																			<td id="ImplPart"></td>
																		</tr> 
																		<tr>
																			<td>4</td>
																			<td>Financier/s</td>
																			<td id="financiers"></td>
																		</tr>
																	</tbody>
																</table>
															</div>
														</fieldset>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row clearfix " id="">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="card">
											<div class="body">
												<div class="row">
													<div class="col-xs-12">
														<fieldset class="scheduler-border">
															<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">4.0) Files</legend>
															<div class="table-responsive">
																<table summary="This table shows how to create responsive tables using Bootstrap's default functionality" class="table table-bordered table-hover">
																	<thead>
																		<tr>
																			<th width="5%">#</th>
																			<th width="35%">Attachment Purpose</th>
																			<th width="60%">File Name</th>
																		</tr>
																	</thead>
																	<tbody id="files_attached">
																	</tbody>
																</table>
															</div>
														</fieldset>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row clearfix " id="">
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<input type="hidden" name="MM_insert" value="addprojectfrm">
										<input type="hidden" name="username" value="<?= $user_name ?>">
										<button type="button" class="btn btn-warning prev-step">Previous</button>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<input class="btn btn-success" name="report" type="submit" value="Save and Export as PDF !">
										<button class="btn btn-success" type="submit">Save! </button>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Start Modal Item Edit -->
<div class="modal fade" id="outputItemModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header" style="background-color:#03A9F4">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" style="color:#fff" align="center" id="modal-title"></h4>
			</div>
			<div class="modal-body" style="max-height:450px; overflow:auto;">
				<div class="card">
					<div class="row clearfix">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="body">
								<div class="div-result">
									<form class="form-horizontal" id="addprojoutput" action="" method="POST">
										<br />
										<div class="col-md-6">
											<label class="control-label">Start Year *:</label>
											<div class="form-line">
												<select name="outputfscyear" id="outputfscyear" onchange="output_year_change()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
													<option value="">.... Select Year from list ....</option>
												</select>
												<span id="outputfscyearmsg" style="color:red"></span>
											</div>
										</div>
										<div class="col-md-6">
											<label for="outputduration" class="control-label">Output Duration (Days) *:</label>
											<span id="outputdurationmsg1" style="color: red"></span>
											<div class="form-input">
												<input type="hidden" name="outputduration1" id="outputduration1">
												<input type="hidden" name="outputStartYear" id="outputStartYear">
												<input type="number" name="outputduration" id="outputduration" onkeyup="onKeyUpDays()" onchange="onKeyUpDays()" placeholder="Enter" class="form-control" required>
												<span id="outputdurationmsg" style="color:red"></span>
											</div>
										</div>
										<div class="col-md-6">
											<label for="outputbudget" class="control-label">Output Budget *:</label>
											<div class="form-input">
												<input type="hidden" name="indicatorid" id="indicatorids">
												<input type="hidden" name="myprogid" id="myprogid" value="<?= $progid ?>">
												<input type="hidden" name="outputids" id="outputids">
												<input type="hidden" name="rowno" id="rowno">
												<input type="number" name="outputbudget" onkeyup="budgetCalculate()" onchange="budgetCalculate()" id="outputbudget" placeholder="Enter" class="form-control" required>
												<span id="outputbudgetmsg" style="color:red"></span>
											</div>
										</div>
										<div class="col-md-6">
											<label for="outputceiling" class="control-label"> Output Budget Ceiling:</label>
											<div class="form-input">
												<input type="hidden" name="outputceilingVal" id="outputceilingVal">
												<input type="text" name="outputceiling" id="outputceiling" placeholder="Output Budget Ceiling" class="form-control" disabled>
											</div>
										</div>
										<div class="col-md-6">
											<label for="outputTarget" class="control-label">Total <span id="unit_measure"></span> *:</label>
											<span id="ceiling_output_target_msg" style="color: red"></span>
											<div class="form-input">
												<input type="hidden" name="label3level" id="label3level" value="<?= $level3label ?>" class="form-control">
												<input type="hidden" name="houtputTarget" id="houtputTarget" class="form-control">
												<input type="hidden" name="ceiling_output_target" id="ceiling_output_target" value="" class="form-control">
												<input type="number" name="outputTarget" id="outputTarget" onkeyup="optarget()" onchange="optarget()" placeholder="Enter Output Target" class="form-control" required="required">
											</div>
										</div>
										<div class="col-md-6">
											<label>Project Location Map Type *:</label>
											<div class="form-line">
												<select name="projwaypoints" class="form-control show-tick" id="projwaypoints" style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="true" required>
													<option value="">.... Select ....</option>
													<?php
													do {
														if ($projwaypoints == $row_rsMapType['id']) {
															?>
															<option value="<?php echo $row_rsMapType['id'] ?>" selected><?php echo $row_rsMapType['type'] ?></option>
															<?php
														} else {
														?>
															<option value="<?php echo $row_rsMapType['id'] ?>"><?php echo $row_rsMapType['type'] ?></option>
															<?php
														}
													} while ($row_rsMapType = $query_rsMapType->fetch());
													?>
												</select>
											</div>
										</div>
										<div class="col-md-12" id="ben_dissegragation">
											<label class="control-label">Output Distribution (<span id="diss_type"></span>)</label>
											<div class="table-responsive">
												<table class="table table-bordered table-striped table-hover" id="diss_state_table" style="width:100%">
													<thead>
														<tr>
															<th width="5%">#</th>
															<th width="60%"><?=$level3label?></th>
															<th width="25%">Output Share</th>
															<th width="10%">
																<button type="button" name="addplus" id="addplus" onclick="add_row_diss();" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button>
															</th>
														</tr>
													</thead>
													<tbody id="diss_state_body">
														<tr></tr>
														<tr id="remove_diss">
															<td align="center" colspan="5">Add Locations </td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="modal-footer">
											<div class="col-md-12 text-center">
												<input type="hidden" name="addoutput" id="addoutput" value="addoutput">
												<input type="hidden" name="opid" id="opid" value="">
												<input type="hidden" name="dben_diss" id="dben_diss" value="">
												<input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
												<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
												<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- /modal-body -->
		</div>
		<!-- /modal-content -->
	</div>
	<!-- /modal-dailog -->
</div>

<!-- End Item Edit -->
<script src="assets/custom js/add-project.js"></script>