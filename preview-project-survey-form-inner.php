<?php 

try {
	//code...

?>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card" style="padding:5px">
			<!-- Draggable Handles -->
			<fieldset class="scheduler-border">
				<div class="row clearfix">
					<div class="block-header">
						<div class="header">
							<?= $results ?>
							<div class="row clearfix">
								<div class="col-md-12">
									<ul class="list-group">
										<li class="list-group-item list-group-item list-group-item-action active">Project Name: <?= $projname ?> </li>
										<li class="list-group-item"><strong><?=$formtype?> : </strong> <?= $resultstype ?> </li>
										<li class="list-group-item"><strong>Change to be measured: </strong> <?= $indname ?> </li>
										<li class="list-group-item"><strong>Unit of Measure: </strong> <?= $unit ?> </li>
										<li class="list-group-item"><strong>Source of Data: </strong> <?= $datasource ?> </li>
									</ul>
								</div> 
							</div>
						</div> 
						<div class="body">
							<div class="row clearfix">  
								<div class="col-md-12">										
									<?php
									if($data_source  == 1){
										?>
										<div class="col-md-3" id="">
											<label for="" id="" class="control-label">Sample Size:</label>
											<div class="form-input"> 
												<div class="form-control"><?php echo $sample; ?></div>
											</div> 
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<label class="control-label"><strong>Key Question/s: </strong></label>
											<div>
												<table class="table table-bordered table-striped table-hover dataTable">
													<thead>
														<tr>
															<th style="width:5%">#</th>
															<th style="width:95%">Question</th>
														</tr>
													</thead>
													<tbody>
														<?php 
														$query_questions = $db->prepare("SELECT * FROM tbl_project_outcome_evaluation_questions WHERE projid=:projid ORDER BY id ASC");
														$query_questions->execute(array(":projid"=>$projid));
														 
														$counter = 0; 
														while ($row = $query_questions->fetch()) {		
															$counter++;
															$question = $row['question'];
															
															echo '
															<tr>
																<td style="width:5%">' . $counter . '</td>
																<td style="width:95%">' . $question . '</td>
															</tr>'; 
														}
														?>
													</tbody>
												</table>
											</div>
										</div>
										<?php
									} else {
									?>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<label for="" id="" class="control-label">Key Question:</label>
											<div class="form-input"> 
												<div class="form-control"><?php echo $unit." of ". $indname; ?></div>
											</div> 
										</div>
										<?php
									} 
									?>
									
									<div class="col-md-3"> 
										<label>Enumerator Type *:</label>
										<div class="form-line">
											<div class="form-control"><?php echo $enumeratortype; ?></div>
										</div>
									</div>
									
									<div class="col-md-3" id="">
										<label for="" id="" class="control-label">Survey Start Date *:</label>
										<div class="form-input"> 
											<div class="form-control"><?php echo $startdate; ?></div>
										</div>
									</div>
									<div class="col-md-3" id="">
										<label for="" id="" class="control-label">Survey End Date *:</label>
										<div class="form-input"> 
											<div class="form-control"><?php echo $enddate; ?></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</fieldset>
        </div>
    </div>
</div>

<?php 
} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>