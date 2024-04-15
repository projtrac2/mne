<?php
try {
	//code...

?>

<div class="block-header">
	<h4 class="contentheader"><i class="fa fa-list" aria-hidden="true"></i>
		Assign Inspection Officers
	</h4>
</div>

<!-- Draggable Handles -->
<div class="row clearfix">
	<div class="block-header">
		<?php
echo $results;
?>
	</div>
	<!-- Advanced Form Example With Validation -->
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
		<ul class="nav nav-tabs" style="font-size:14px">
				<li class="active">
					<a data-toggle="tab" href="#home">
						<i class="fa fa-caret-square-o-down bg-deep-orange" aria-hidden="true"></i> Assign Officers &nbsp;
					</a>
				</li>
				<li>
					<a data-toggle="tab" href="#menu1">
						<i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> Inspections Pending &nbsp;
					</a>
				</li>
			</ul>
			<div class="tab-content">
				<div id="home" class="tab-pane fade in active">
				<div class="body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover js-basic-example " id="manageItemTable">
						<thead>
							<tr class="bg-orange">
								<th style="width:3%"></th>
								<th style="width:7%">#</th>
								<th style="width:10%">Code</th>
								<th style="width:60%" colspan="4">Project </th>
								<th style="width:15">Implementation Method </th>
							</tr>
						</thead>
						<tbody>
							<?php
if ($totalRows_rsProjects > 0) {
    $counter = 0;
    do {
        $projid = $row_rsProjects['projid'];
        $counter++;
        $implementation = $row_rsProjects['projcategory'];
        $query_rsImplementation = $db->prepare("SELECT * FROM tbl_project_implementation_method WHERE id='$implementation' ");
        $query_rsImplementation->execute();
        $row_rsImplementation = $query_rsImplementation->fetch();
        $implType = $row_rsImplementation['method'];

        $query_rsOutputs = $db->prepare("SELECT p.output as  output, o.id as opid, p.indicator FROM tbl_project_details o INNER JOIN tbl_progdetails p ON p.id = o.outputid WHERE projid='$projid' ");
        $query_rsOutputs->execute();
        $row_rsOutputs = $query_rsOutputs->fetch();
        $totalRows_rsOutputs = $query_rsOutputs->rowCount();

        if ($totalRows_rsOutputs > 0) {
            ?>
											<tr class="projects" style="background-color:#eff9ca">
												<td align="center" class="mb-0 output_class<?php echo $projid ?>" id="projects<?php echo $projid ?>" data-toggle="collapse" data-target=".project<?php echo $projid ?>">
													<button class="btn btn-link " title="Click to expand and Click to Collapse!!">
														<i class="fa fa-plus-square" style="font-size:16px"></i>
													</button>
												</td>
												<td align="center"><?=$counter?></td>
												<td><?php echo $row_rsProjects['projcode'] ?> <?=$projid?></td>
												<td colspan="4"><?php echo $row_rsProjects['projname'] ?></td>
												<td><?php echo $implType ?></td>
											</tr>
											<tr class="collapse project<?php echo $projid ?>" style="background-color:#42b6f5; color:#FFF">
												<th style="width:3%">#</th>
												<th style="width:40%" colspan="4">Output Name</th>
												<th style="width:40%" colspan="2">Indicator</th>
												<th style="width:7%">Action</th>
											</tr>
											<?php
$Ocounter = 0;
            do {
                $outputid = $row_rsOutputs['opid'];
                $indid = $row_rsOutputs['indicator'];

                //get indicator
                $query_rsIndicator = $db->prepare("SELECT *  FROM tbl_indicator WHERE indid='$indid' ");
                $query_rsIndicator->execute();
                $row_rsIndicator = $query_rsIndicator->fetch();
                $totalRows_rsIndicator = $query_rsIndicator->rowCount();

                $unit = $row_rsIndicator['indicator_unit'];
                $query_Indicator = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id =:unit ");
                $query_Indicator->execute(array(":unit" => $unit));
                $row = $query_Indicator->fetch();
                $unit = $row['unit'];
                $indid = $row_rsOutputs['indicator'];
                $Ocounter++;

                //get indicator
                $query_rsIndicator = $db->prepare("SELECT *  FROM tbl_indicator WHERE indid='$indid' ");
                $query_rsIndicator->execute();
                $row_rsIndicator = $query_rsIndicator->fetch();
                $totalRows_rsIndicator = $query_rsIndicator->rowCount();

                // Responsible
                $query_rsMembers = $db->prepare("SELECT *  FROM tbl_inspection_assignment WHERE projid=:projid and outputid=:outputid");
                $query_rsMembers->execute(array(":projid" => $projid, ":outputid" => $outputid));
                $row_rsMembers = $query_rsMembers->fetch();
                $totalRows_rsMembers = $query_rsMembers->rowCount();
                ?>
												<tr class="collapse project<?php echo $projid ?>" style="background-color:#e9e9e9">
													<td align="center"> <?php echo $counter . "." . $Ocounter ?></td>
													<td colspan="4"><?php echo $row_rsOutputs['output'] ?></td>
													<td colspan="2"><?php echo $row_rsIndicator['indicator_name'] ?></td>
													<td>
														<div class="btn-group">
															<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																Options <span class="caret"></span>
															</button>
															<ul class="dropdown-menu">
																<?php
if ($totalRows_rsMembers > 0) {
                    ?>
																	<li>
																		<a type="button" data-toggle="modal" data-target="#addFormModal" id="addFormModalBtn" onclick="edit(<?php echo $projid . ',' . $outputid ?>)">
																			<i class="fa fa-pencil-square"></i> </i> Edit
																		</a>
																	</li>
																	<li>
																		<a type="button" data-toggle="modal" data-target="#moreModal" id="moreModalBtn" onclick="more(<?php echo $projid . ',' . $outputid ?>)">
																			<i class="fa fa-file-text"></i> View
																		</a>
																	</li>
																<?php
} else {
                    ?>
																	<li>
																		<a type="button" data-toggle="modal" data-target="#addFormModal" id="addFormModalBtn" onclick="add(<?php echo $projid . ',' . $outputid ?>)">
																			<i class="fa fa-pencil-square"></i> </i> Assign
																		</a>
																	</li>
																<?php
}
                ?>
															</ul>
														</div>
													</td>
												</tr>
												<?php
} while ($row_rsOutputs = $query_rsOutputs->fetch());
        }
    } while ($row_rsProjects = $query_rsProjects->fetch());
} else {
    ?>
								<tr>
									<td colspan="8">No projects Awaiting Assignment</td>
								</tr>
							<?php
}
?>
						</tbody>
					</table>
				</div>
			</div>
				</div>
			<div id="menu1" class="tab-pane fade">
			</div>
		</div>
	</div>
</div>

<!-- More Item more -->
<div class="modal fade" tabindex="-1" role="dialog" id="moreModal">
	<div class="modal-dialog  modal-lg">
		<div class="modal-content">
			<div class="modal-header" style="background-color:#03A9F4">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info-circle"></i> More Information</h4>
			</div>
			<div class="modal-body">
				<div class="card">
					<div class="body" id="">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

									<div class="table-responsive">
										<table class="table table-bordered table-striped table-hover" id="assign_table" style="width:100%">
											<thead>
												<tr>
													<th width="5%">#</th>
													<th width="20%">Location</th>
													<th width="20%">Responsible</th>
												</tr>
											</thead>
											<tbody id="moreinfo">

											</tbody>
										</table>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="col-md-12 text-center">
					<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Close</button>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
<!-- End Item more -->


<!-- add item -->
<div class="modal fade" id="addFormModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="submitMilestoneForm" action="" method="POST" enctype="multipart/form-data">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> Assign </h4>
				</div>
				<div class="modal-body">
					<div class="card">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="body" id="">
									<div class="table-responsive">
										<table class="table table-bordered table-striped table-hover" id="assign_table" style="width:100%">
											<thead>
												<tr>
													<th width="5%">#</th>
													<th width="20%">Location</th>
													<th width="15%">Responsible</th>
												</tr>
											</thead>
											<tbody id="assign_table_body">

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> <!-- /modal-body -->

				<div class=" modal-footer">
					<div class="col-md-12 text-center">
						<input type="hidden" name="newitem" id="newitem" value="new">
						<input type="hidden" name="user_name" id="user_name" value="55">
						<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
						<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
					</div>
				</div> <!-- /modal-footer -->
			</form> <!-- /.form -->
		</div> <!-- /modal-content -->
	</div> <!-- /modal-dailog -->
</div>
<!-- End add item -->

<script src="assets/js/inspection/assign.js"></script>
<?php 
} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());

}

?>