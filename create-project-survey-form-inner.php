<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
			<!-- Draggable Handles -->
			<fieldset class="scheduler-border">
				<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-plus-square" aria-hidden="true"></i> <?php echo "Project ".$surveytype; ?> Survey</legend>
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
									<form id="<?=$formid?>" method="POST" name="<?=$formid?>" action="" enctype="multipart/form-data" autocomplete="off">
										
										<?php
										if($data_source  == 1){
											?>
											<div class="col-md-3" id="">
												<label for="" id="" class="control-label">Sample Size:</label>
												<div class="form-input"> 
													<input type="number" name="sample" id="sample" value="" placeholder="Number of Submissions" class="form-control">
												</div> 
											</div>
											<?php
										}
										?>
										
										<div class="col-md-3" id="respondent"> 
											<label>Enumerator Type *:</label>
											<div class="form-line">
												<select name="enumerator_type"  id="enumerator_type" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" false required>
													<option value="" selected="selected" class="selection">....Select Type First....</option>
													<option value="1"   class="selection">Inhouse</option>
													<option value="2"   class="selection">Outsourced</option>
												</select>
											</div>
										</div>
										
										<div class="col-md-3" id="">
											<label for="" id="" class="control-label">Survey Start Date *:</label>
											<div class="form-input"> 
												<input type="date" name="startdate" id="startdate" value="" onchange="start()" placeholder="Start Date" class="form-control" require="required">
											</div>
										</div>
										<div class="col-md-3" id="">
											<label for="" id="" class="control-label">Survey End Date *:</label>
											<div class="form-input"> 
												<input type="date" name="enddate" id="enddate" value="" onchange="end()" placeholder="End Date" class="form-control" require="required">
											</div>
										</div>
										<div class="row clearfix">                                 
											<div class="col-lg-12 col-md-12 col-sm-2 col-xs-2" align="center">
												<input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
												<input name="indid" type="hidden" id="indid" value="<?php echo $indid; ?>" /> 
												<input name="projid" type="hidden" id="projid" value="<?php echo $projid; ?>" /> 
												<input name="form_name" type="hidden" id="form_name" value="<?=$surveytype?>" /> 
												<input name="surveytype" type="hidden" id="surveytype" value="<?=$projstage?>" /> 
												<div class="btn-group">
													<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="Submit" />
												</div>
												<input type="hidden" name="MM_insert" value="<?=$formid?>" />
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</fieldset>
        </div>
    </div>
</div>