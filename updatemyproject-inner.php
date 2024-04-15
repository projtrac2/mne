<?php 
try {
	//code...

?>
<div class="body" style="margin-top:5px">
	<div class="stepwizard">
		<div class="stepwizard-row setup-panel">
			<div class="stepwizard-step">
				<a href="#step-1" type="button" data-toggle="tab"   class="btn btn-primary btn-circle" ><i class="fa fa-info-circle" aria-hidden="true"></i></a>
				 <p>Project Details</p>
			</div>
			<div class="stepwizard-step">
				<a href="#step-2" type="button" data-toggle="tab"  class="btn btn-default btn-circle disabled"><i class="fa fa-bullseye fa-3x" aria-hidden="true"></i></a></a>
			  <p>Logical Framework</p>
			</div>
			<div class="stepwizard-step">
				<a href="#step-3" type="button" data-toggle="tab"  class="btn btn-default btn-circle disabled"><i class="fa fa-map-marker fa-3x" aria-hidden="true"></i></a>
				  <p>Location Details</p>
			</div>
			<div class="stepwizard-step">
				<a href="#step-4" type="button" data-toggle="tab"  class="btn btn-default btn-circle disabled"><i class="fa fa-calendar fa-3x" aria-hidden="true"></i></a>
				   <p>Scheduled tasks</p>
			</div>
			<div class="stepwizard-step">
				<a href="#step-5" type="button" data-toggle="tab" class="btn btn-default btn-circle disabled"><i class="fa fa-check" aria-hidden="true"></i></a>
				<p>Finish</p>
			</div>
		</div>
	</div>
	<form name="editprojectfrm" role="form" id="form" method="post" action="" enctype="multipart/form-data">
		<fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
			<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">EDIT PROJECT DETAILS</legend>
				<div class="col-md-4">
					<label  class="control-label">Project Code (Eg. 2018/12/AB23) *:</label>
					<span id="gt" style="display:none">Project Code Exists</span>
					<!-- <div class="form-line"> -->
					<input type="text" name="projcode" id="projcode" class="form-control" style="border:#CCC thin solid; border-radius: 5px" required="required" value="<?php echo $row_rsProject['projcode']?>" >
				</div>
				<div class="col-md-8">
					<label class="control-label">Project Name *:</label>
					<div class="form-line">
						<input type="text" name="projname" id="projname" placeholder="Please enter name of your project" class="form-control" style="border:#CCC thin solid; border-radius: 5px"  required value="<?php echo $row_rsProject['projname']?>">    
					</div>
				</div>
				<div class="col-md-4">
					<label class="control-label">Project <?=$ministrylabel?>*:</label>
					<div class="form-line">
					<select name="projsector" id="projsector" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
					<option value="">.... Select <?=$ministrylabel?> from list ....</option>
								<?php
									do {  
									?>
										<option value="<?php echo $row_rsSector['stid']?>"<?php if (!(strcmp($row_rsSector['sector'], $row_rsSect['sector']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsSector['sector']?></option>
									<?php
									} while ($row_rsSector = $query_rsSector->fetch());
									?>
					</select>
					</div>
				</div>		
				<div class="col-md-4">
					<label class="control-label">Project <?=$departmentlabel?> *:</label>
					<div class="form-line" >
						<select name="projdept" id="projdept"  class="form-control"  style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
						<option value="">... Select <?=$ministrylabel?> first ...</option>
									<?php
									do {  
									?>
										<option value="<?php echo $row_rsAllDept['stid'];?>"
											<?php 
												if(!(strcmp($row_rsAllDept['sector'], $row_rsDept['sector']))){
													echo "selected=\"selected\"";
												} 
											?>
										>
											<?php
												echo $row_rsAllDept['sector']
											?>
										</option>
									<?php
									} while ($row_rsAllDept = $query_rsAllDept->fetch());
									?>
						</select>
					</div>
				</div>
				<div class="col-md-4">
					<label class="control-label">Project Program  *:</label>
					<div class="form-line" >
						<select name="prog" id="prog"  class="form-control"  style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
						<?php
							do {  
							?>
								<option value="<?php echo $row_programs['progid']?>"<?php if (!(strcmp($row_programs['progname'], $row_prog['progname']))) {echo "selected=\"selected\"";} ?>><?php echo $row_programs['progname']?></option>
							<?php
							} while ($row_programs = $query_programs->fetch());
						?>
						</select>
					</div>
				</div>
				<div class="col-md-4">
					<label>Project Type *:</label>
					<div class="form-line">
						<select name="projtype" id="projtype" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
						<option value="">.... Select Project Type  from list ....</option>
						<?php
						do {  
						?>
							<option value="<?php echo $row_rsType['projtypelist']?>"<?php if (!(strcmp($row_rsType['projtypelist'], $row_rsTyp['projtypelist']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsType['projtypelist']?></option>
						<?php
						} while ($row_rsType = $query_rsType->fetch());
						?>
						</select>
					</div>
				</div>
				<div class="col-md-4">
					<label class="control-label">Implementation Method *:</label>
					<div class="form-line">
						<select name="projimplmethod" id="projimplmethod" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
							<option value="">.... Select the method ....</option>
							<?php
							do {  
							?>
								<option value="<?php echo $row_rsImpMethod['id']?>"<?php if (!(strcmp($row_rsImpMethod['method'], $row_rsImptMethod['method']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsImpMethod['method']?></option>
							<?php
							} while ($row_rsImpMethod = $query_rsImpMethod->fetch());
							?>
						</select>     
					</div>
				</div>
				<script src="http://afarkas.github.io/webshim/js-webshim/minified/polyfiller.js"></script>
				<script type="text/javascript">
				
					webshims.setOptions('forms-ext', {
						replaceUI: 'auto',
						types: 'number'
					});
					webshims.polyfill('forms forms-ext');
				</script>
				<div class="col-md-4">
					<label for="cost">Projtrac Cost</label>
					<input type="number" name="projcost" id="projcost" class="form-control" value="<?php echo $row_rsProject['projcost']?>">
				</div>
				<div class="col-md-4">
					<label class="control-label">Project Fiscal Year *:</label>
					<div class="form-line">
						<select name="projfscyear" id="projfscyear" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required" >
							<option value="">.... Select Year from list ....</option>
							<?php
							do {  
							?>
								<option value="<?php echo $row_rsFY['id']?>"<?php if (!(strcmp($row_rsFY['year'], $row_rsFYR['year']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsFY['year']?></option>
							<?php
							} while ($row_rsFY = $query_rsFY->fetch());
							?>

							</select>
					</div>
				</div>
				<div class="col-md-4">
					<label class="control-label">Project Monitoring Frequency *:</label>
					<div class="form-line">
						<select name="projdatafreq" id="projdatafreq" class="form-control show-tick" style="border:#000 1px solid black;; border-radius:5px" data-live-search="true" required>
							<option value="">.... Select from list ....</option>
							
							<?php
							do {  
							?>
								<option value="<?php echo $row_rsDCFreq['fqid']?>"<?php if (!(strcmp($row_rsDCFreq['frequency'], $row_rsDCFrq['frequency']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsDCFrq['frequency']?></option>
							<?php
							} while ($row_rsDCFreq = $query_rsDCFreq->fetch());
							?>
						</select>
					</div>
				</div>
				<div class="col-md-4">
					<label for="" class="control-label">Project Approval Date *:</label>
					<div class="form-line">
						 <input type="date" name="projappdate" placeholder="Please choose a date..." id="projappdate" title="d/m/Y" required="required" class="form-control" style="border:1px #CCC thin solid; border-radius:5px" value="<?php echo $row_rsProject['projappdate']?>" />
					</div>
                </div>	
				<div class="col-md-4">
					<label for="" class="control-label">Inspection Required? *:</label>
					<div class="form-line">
						<input name="projinspection" type="radio" value="1" id="insp1" class="with-gap radio-col-green"  required="required"/>
						<label for="insp1">YES</label>
						<input name="projinspection" type="radio" value="0" id="insp2" class="with-gap radio-col-red"  required="required"/>
						<label for="insp2">NO</label>
					</div>
				</div>	
				<div class="col-md-12">
					<button class="btn btn-primary nextBtn pull-right" type="button">Next</button>
				</div>
		</fieldset>
		<fieldset class="scheduler-border row setup-content" id="step-2">
			<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">EDIT LOGICAL FRAMEWORK INFORMATION</legend>
			<div class="col-md-12">
				<label class="control-label">Project Description *: <font align="left" style="background-color:#eff2f4">(Briefly describe goals and objectives of the project, approaches and execution methods, and other relevant information that explains the need for project.) </font></label>
				<p align="left">
				<textarea name="projdesc" cols="45" rows="5" class="txtboxes" id="projdesc" required="required" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder="Briefly describe the goals and objectives of the project, the approaches and execution methods, resource estimates, people and organizations involved, and other relevant information that explains the need for project as well as the amount of work planned for implementation."><?php echo $row_rsProject['projdesc']?> </textarea>
				<script>
						// Replace the <textarea id="editor1"> with a CKEditor
						// instance, using default configuration.
						CKEDITOR.replace( 'projdesc',
						{
							on :
							{
							instanceReady : function( ev )
							{
								// Output paragraphs as <p>Text</p>.
								this.dataProcessor.writer.setRules( 'p',
								{
									indent : false,
									breakBeforeOpen : false,
									breakAfterOpen : false,
									breakBeforeClose : false,
									breakAfterClose :false
								});
								this.dataProcessor.writer.setRules( 'ol',
								{
									indent : false,
									breakBeforeOpen : false,
									breakAfterOpen : false,
									breakBeforeClose : false,
									breakAfterClose :false
								});
								this.dataProcessor.writer.setRules( 'ul',
								{
									indent : false,
									breakBeforeOpen : false,
									breakAfterOpen : false,
									breakBeforeClose : false,
									breakAfterClose :false
								});
								this.dataProcessor.writer.setRules( 'li',
								{
									indent : false,
									breakBeforeOpen : false,
									breakAfterOpen : false,
									breakBeforeClose : false,
									breakAfterClose :false
								});
							}
							}
						});
				</script>  
				</p>
			</div>
			<div class="col-md-12">
				<label class="control-label">Expected Project Outcome *: <font align="left" style="background-color:#eff2f4">(Describe the short term and the medium term effects of the outputs)</font></label>
				<p align="left">
					<textarea name="Projexpoutcome" cols="45" rows="10" class="txtboxes" id="Projexpoutcome" required="required" style="width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"> <?php echo $row_rsProject['Projexpoutcome']?> </textarea> 
					<script>
					// Replace the <textarea id="editor1"> with a CKEditor
					// instance, using default configuration.
					CKEDITOR.replace( 'Projexpoutcome',{ on :
					{
						instanceReady : function( ev )
						{
							// Output paragraphs as <p>Text</p>.
							this.dataProcessor.writer.setRules( 'p',
								{
									indent : false,
									breakBeforeOpen : false,
									breakAfterOpen : false,
									breakBeforeClose : false,
									breakAfterClose :false
								});
							this.dataProcessor.writer.setRules( 'ol',
								{
									indent : false,
									breakBeforeOpen : false,
									breakAfterOpen : false,
									breakBeforeClose : false,
									breakAfterClose :false
								});
							this.dataProcessor.writer.setRules( 'ul',
								{
									indent : false,
									breakBeforeOpen : false,
									breakAfterOpen : false,
									breakBeforeClose : false,
									breakAfterClose :false
								});
							this.dataProcessor.writer.setRules( 'li',
								{
									indent : false,
									breakBeforeOpen : false,
									breakAfterOpen : false,
									breakBeforeClose : false,
									breakAfterClose :false
								});
							}
						}
					});
					</script>  
				</p>
			</div>
			<div class="col-md-12 table-responsive">
				<table class="table table-bordered table-striped table-hover js-basic-example table-responsive" id="item_table" style="width:100%">
					<tr>
						<th width="35%">Output</th>
						<th width="35%">Indicator</th>
						<th width="15%">Target</th>
						<th width="15%">Baseline</th>
					</tr>
					<tr>
						<td>
							<select name="expoutputname[]" id="expoutputname" class="form-control item_unit" required="required">
								<option value="">... Select Output ...</option>	
								<?php
									do {  
									?>
										<option value="<?php echo $row_rsOut['opid']?>"<?php if (!(strcmp($row_rsOut['output'], $row_rsOutPut['output']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsOut['output']?></option>
									<?php
									} while ($row_rsOut = $query_rsOut->fetch());
								?>
							</select>
						</td>
						<td>
							<select name="expoutputindicator[]" id="expoutputindicator" class="form-control item_unit" required="required">
									<option value="">... Select Indicator ...</option>
									<?php
									do {  
									?>
										<option value="<?php echo $row_rsInd['indid']?>"<?php if (!(strcmp($row_rsInd['indname'], $row_rsIndicator['indname']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsInd['indname']?></option>
									<?php
									} while ($row_rsInd = $query_rsInd->fetch());
								?>
							</select>
						</td>
						<td>
							<input type="number" name="expoutputvalue[]" placeholder="Output Value" class="form-control item_quantity" id="expoutputvalue" required="required" value="<?php echo $row_rsExp['expoutputvalue'];?>"/>
						</td>
						<td>
							<input type="number" name="outputbaseline[]" placeholder="Baseline Value" class="form-control item_quantity" id="outputbaseline" required="required" value="<?php echo $row_rsExp['outputbaseline'];?>" />
						</td>
					</tr>
				</table>
			</div>
            <div class="row clearfix">
			<h4 style="margin-left:15px; color:#FF5722">Project Risk Assumptions</h4>
			<?php if($count_rsProjRisk > 0){ ?>
				<div class="col-md-12 table-responsive output">
					<table class="table table-bordered table-striped table-hover js-basic-example table-responsive" style="width:100%" id="output">
						<tr>
							<th style="width:5%">#</th>
							<th style="width:85%">Risk/Assumption</th>
							<th style="width:10%">Action</th>
						</tr>
						<?php 
						$nm = 0;
						foreach($row_rsProjRisk as $row){
							$nm = $nm + 1;
							
							$query_riskassump = $db->prepare("SELECT * FROM tbl_projrisk_categories");	
							$query_riskassump->execute();
						?>
						<tr>
							<td>
								<?php echo $nm; ?>
							</td>
							<td>
								<?php echo $row['category']?>
							</td>
							<td>
								<button type="button" class="btn btn-warning sm" data-toggle="modal" data-target="#riskModal<?php echo $row['id'];?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&nbsp;
								<button type="button" class="btn btn-danger btn-sm pull-right deleterisk"  data-id="<?php echo $row['id']; ?>"><i class="fa fa-window-close" aria-hidden="true"></i></button>
								
								<div class="modal fade riskModal" id="riskModal<?php echo $row['id'];?>" tabindex="-1" role="dialog" aria-labelledby="riskModalLabel" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="riskModalLabel">Project Risk Assumptions</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<form action="mmeditaction.php" method="post" enctype="multipart/form-data" class="riskform">
										<div class="modal-body">
											<input type="hidden" name="projid" value="<?php echo $projid;?>">
											<input type="hidden" name="rkid" value="<?php echo $row['id'];?>">
											<input type="hidden" name="user_name" value="<?php echo $user_name;?>">
											<div class="col-md-12">
												<label for="">Risk</label>
												<select name="projriskass" class="form-control">
													<option value="">... Select Risk ...</option>
													<?php
													while($row_riskassump = $query_riskassump->fetch()){ 
													?>
														<option value="<?php echo $row_riskassump['rskid']?>"<?php if (!(strcmp($row_riskassump['category'], $row['category']))) {echo "selected=\"selected\"";} ?>><?php echo $row_riskassump['category']?></option>
													<?php
													} 
													?>
												</select>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
											<button type="submit" id="riskedit" class="btn btn-primary" name="mmupdate">Update</button>
										</div>
									</form>
									</div>
								</div>
								</div>
							</td>
						</tr>
						<?php
						}
						?>
					</table>
				</div>
				<?php } ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="header">
						<label class="control-label">Add New Project Risk Assumption(s)*:</label>
					</div>
					<div class="body">
						<select name="projrisk[]" id="optgroup" class="ms" multiple="multiple">
							<optgroup label="All Risk Categories">
							<?php
							while($row_risk = $query_risk->fetch()){ 
							?>
								<option value="<?php echo $row_risk['rskid']?>"><?php echo $row_risk['category']?></option>
							<?php
							} 
							?>
							</optgroup>
						</select>
					</div>
                </div>
            </div>
            <!--<div class="col-md-12">
				<label class="control-label">Project Assumption(s) *: <font align="left" style="background-color:#eff2f4">(List down a set of conditions that are expected to prevail for the project to be successful)</font></label>
				<p align="left">
					<textarea name="Projassump" cols="45" rows="10" class="txtboxes" id="Projassump" required="required"  style="width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
					<?php //echo $row_rsProject['assumptions'];?>
					</textarea>
					<script>
				// Replace the <textarea id="editor1"> with a CKEditor
				// instance, using default configuration.
						CKEDITOR.replace( 'Projassump',
						{
							on :
							{
								instanceReady : function( ev )
								{
									// Output paragraphs as <p>Text</p>.
									this.dataProcessor.writer.setRules( 'p',
										{
											indent : false,
											breakBeforeOpen : false,
											breakAfterOpen : false,
											breakBeforeClose : false,
											breakAfterClose :false
										});
									this.dataProcessor.writer.setRules( 'ol',
										{
											indent : false,
											breakBeforeOpen : false,
											breakAfterOpen : false,
											breakBeforeClose : false,
											breakAfterClose :false
										});
									this.dataProcessor.writer.setRules( 'ul',
										{
											indent : false,
											breakBeforeOpen : false,
											breakAfterOpen : false,
											breakBeforeClose : false,
											breakAfterClose :false
										});
									this.dataProcessor.writer.setRules( 'li',
										{
											indent : false,
											breakBeforeOpen : false,
											breakAfterOpen : false,
											breakBeforeClose : false,
											breakAfterClose :false
										});
								}
							}
						});
					</script>  
				</p>
			</div>-->
			<div class="col-md-12 table-responsive">
				<table class="table table-bordered table-striped table-hover js-basic-example table-responsive" id="item_table" style="width:100%">
						<tr>
							<th>*Project Team Leader</th>
							<th>*Deputy Team Leader</th>
							<th>*Project Officer</th>
							<th>*Monitoring Officer</th>
						</tr>						
						<tr>
							<td>
							<div class="form-group">
								<select name="projteamlead" id="projteamlead2" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="true" required>
									<option value="">.... Select from list ....</option>
									<?php
									do {  
									?>
										<option value="<?php echo $row_rsProjectTeam['ptid']?>"<?php if (!(strcmp($row_rsProjectTeam['fullname'], $row_rsProjectTeamLeader['fullname']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsProjectTeam['fullname']?></option>
									<?php
									} while ($row_rsProjectTeam = $query_rsProjectTeam->fetch());
									?>
								</select>
								</div>
							</td>
							<td>
							<div class="form-group">
								<select name="projdepteamlead" id="projdepteamlead" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="true" required>
									<option value="">.... Select from list ....</option>
									<?php
									do{  
									?>
										<option value="<?php echo $row_rsProjectTeam2['ptid']?>"<?php if (!(strcmp($row_rsProjectTeam2['fullname'], $row_rsProjectDeptTeamLeader['fullname']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsProjectTeam2['fullname']?></option>
									<?php
									} while ($row_rsProjectTeam2 = $query_rsProjectTeam2->fetch());
									?>
								</select>
								</div>
							</td>
							<td>
							<div class="form-group">
								<select name="projofficer" id="projofficer" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="true" required>
									<option value="">.... Select from list ....</option>
									<?php
										do {  
									?>
										<option value="<?php echo $row_rsProjectTeam6['ptid']?>"<?php if (!(strcmp($row_rsProjectTeam6['fullname'], $row_rsProjectOfficer['fullname']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsProjectTeam6['fullname']?></option>
									<?php
										} while ($row_rsProjectTeam6 = $query_rsProjectTeam6->fetch());
									?>
								</select>
								</div>
							</td>
							<td>
									<div class="form-group">
								<select name="projliasonofficer" id="projliasonofficer" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="true" required>
									<option value="">.... Select from list ....</option>
									<?php
										do {  
									?>
										<option value="<?php echo $row_rsProjectTeam7['ptid']?>"<?php if (!(strcmp($row_rsProjectTeam7['fullname'], $row_rsProjectLiasonOfficer['fullname']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsProjectTeam7['fullname']?></option>
									<?php
										} while ($row_rsProjectTeam7 = $query_rsProjectTeam7->fetch());
									?>
								</select>
								</div>
							</td>
						</tr>
				</table>
			</div>
			<div class="col-md-12">
				<ul class="list-inline pull-right">
					<li><button type="button" class="btn btn-default prev-step">Previous</button></li>
					<li><button class="btn btn-primary nextBtn btn-lg " type="button" >Next</button>  </li>
				</ul>
			</div>
		</fieldset>
		<fieldset class="scheduler-border row setup-content" id="step-3">
			<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">EDIT PROJECT LOCATION INFORMATION</legend>
			<div class="col-md-12 table-responsive">
				<table class="table table-bordered table-striped table-hover js-basic-example table-responsive" style="width:100%">
					<tr>
						<th>*Project <?=$level1label?></th>
						<th>*Project <?=$level2label?></th>
						<th>*Project <?=$level3label?></th>
					</tr>
					<tr>
						<td>
							<select name="projcommunity" id="projcommunity" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="true" required>
								<option value="">.... Select from list ....</option>
								<option value="101">All Sub Counties</option>
								<?php
								do {  
								?>
									<option value="<?php echo $row_rsCommunity['id']?>"<?php if (!(strcmp($row_rsCommunity['state'], $row_rsSubcounty['state']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsCommunity['state']?></option>
								<?php
								} while ($row_rsCommunity = $query_rsCommunity->fetch());
								?>
							</select>
						</td>
						<td id="projlga" >
							<select name="projlga" id="ward" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="true" required>
								<option value="">.... Select Sub-County First ....</option>
								<?php
								do {  
								?>
									<option value="<?php echo $row_rsAllWard['id'];?>" <?php if(!(strcmp($row_rsAllWard['state'], $row_rsward['state']))){ echo "selected=\"selected\""; } ?>> <?php echo $row_rsAllWard['state'] ?> </option>
								<?php
								} while ($row_rsAllWard = $query_rsAllWard->fetch());
								?>
							</select>
						</td>
						<td id="projstate">
							<select name="projstate" class="form-control show-tick" id="location" style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="true" required>
								<option value="">.... Select Ward First ....</option>
								<?php
									do {  
									?>
										<option value="<?php echo $row_rsAllLocation['id'];?>"<?php if(!(strcmp($row_rsAllLocation['state'], $row_rslocation['state']))){ echo "selected=\"selected\""; } ?>><?php echo $row_rsAllLocation['state'] ?></option>
									<?php
									} while ($row_rsAllLocation = $query_rsAllLocation->fetch());
								?>
							</select> 
						</td> 
					</tr>
				</table>
            </div>
			<div class="col-md-4">
				<label>Project Location Map Type</label>
				<div class="form-line">
					<select name="projwaypoints" class="form-control show-tick" id="projwaypoints" style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="true" required>
						<option value="">.... Select ....</option>
						<?php
							do {  
							?>
								<option value="<?php echo $row_rsMapType['typeid'];?>"<?php if(!(strcmp($row_rsMapType['type'], $row_rsMapTYP['type']))){ echo "selected=\"selected\""; } ?>><?php echo $row_rsMapType['type'] ?></option>
							<?php
							} while ($row_rsMapType = $query_rsMapType->fetch());
						?>
					</select> 
				</div>
			</div>
			<div class="col-md-12">
				<ul class="list-inline pull-right">
					<li><button type="button" class="btn btn-default prev-step">Previous</button></li>
					<li><button class="btn btn-primary nextBtn btn-lg " type="button" >Next</button></li>
				</ul>
			</div>
		</fieldset>
		<fieldset class="scheduler-border row setup-content" id="step-4">
			<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">EDIT PROJECT SCHEDULE INFORMATION</legend>
			<div class="col-md-12 table-responsive" id="result">
				<table class="table table-bordered table-striped table-hover js-basic-example table-responsive" style="width:100%">
					<tr>
						<th style="width:12%">Meeting Date</th>
						<th style="width:47%">Attachment</th>
						<th style="width:30%">Purpose</th>
						<th style="width:11%">Edit</th>
					</tr>
					<?php 
					foreach($row_rsMeeting as $row){
					?>
					<tr>
						<td id="mmdate">
							<?php echo $row['date']?>
						</td>
						<td id="mmfname">
							<?php echo $row['filename']?>
						</td>
						<td id="mmdesc">
							<?php echo $row['description']; ?>
						</td>
						<td>
							<button type="button" class="btn btn-warning sm" data-toggle="modal" data-target="#mmModal<?php echo $row['id'];?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&nbsp;
							<button type="button" class="btn btn-danger btn-sm pull-right delete" data-id="<?php echo $row['id']; ?>"><i class="fa fa-window-close" aria-hidden="true"></i></button>
							<div class="modal fade mmModal" id="mmModal<?php echo $row['id'];?>" tabindex="-1" role="dialog" aria-labelledby="mmModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="mmModalLabel" align="center">MEETING DETAILS</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<form action="mmeditaction.php" method="post" enctype="multipart/form-data" class="mmform">
									<div class="modal-body">
										<input type="hidden" name="projid" id="projid" value="<?php echo $row['projid'];?>">
										<input type="hidden" name="mid" value="<?php echo $row['id'];?>">
										<input type="hidden" name="user_name" value="<?php echo $user_name;?>">
										<div class="col-md-12">
											<label for="">Meeting Date</label>
											<input name="meetingdate" type="date" id="meetingdate" class="form-control" value="<?php echo $row['date'];?>"/>
										</div>
										<div class="col-md-12">
											<span>Existing file: <?php echo $row['filename'] ?></span><br>
											<label for="">Edit File </label>
											<input type="file" name="meetingattachment"  id="meetingattachment" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
										</div>												
										<div class="col-md-12">
											<label for="">Meeting Description/Purpose</label>
											<input type="text" name="meetingpurpose" class="form-control" id="meetingpurpose" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" value="<?php echo $row['description'];?>">
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										<button type="submit" class="btn btn-primary" name="mmupdate">Update</button>
									</div>
								</form>
								</div>
							</div>
							</div>
						</td>
					</tr>
					<?php
					}
					?>
				</table>
			</div>
			<div class="row clearfix">
				<div class="col-md-12">
					<div class="body table-responsive">
						<table class="table table-bordered" id="meetings_table" style="width:100%">
							<tr>
								<th style="width:20%">Meeting Date</th>
								<th style="width:30%">Attachment</th>
								<th style="width:58%">Purpose</th>
								<th style="width:2%"><button type="button" name="addplus1" onclick="add_row1();" title="Add another document" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
							</tr>
							<tr>
								<td>
									<div class="form-line" id="bs_datepicker_container">
										<input name="projmeetingdate[]" type="date" id="projmeetingdate" class="form-control" style="border:#CCC thin solid; border-radius: 5px"/>
									</div>
								</td>
								<td>
									<input type="file" name="meetingfiles[]" multiple id="meetingfiles[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
								</td>
								<td>
									<input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
								</td>
								<td></td>
							</tr>
						</table>
						<script type="text/javascript">
							function add_row1(){
								$rowno=$("#meetings_table tr").length;
								$rowno=$rowno+1;
								$("#meetings_table tr:last").after('<tr id="mtng'+$rowno+'"><td>'+
								'<div class="form-line">'+
										'<input name="projmeetingdate[]" type="date" id="projmeetingdate[]" class="form-control" style="border:#CCC thin solid; border-radius: 5px"/>'+
									'</div>'+ 
								'</td><td><input type="file" name="meetingfiles[]" id="meetingfiles[]" multiple class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_row1("mtng'+$rowno+'")><span class="glyphicon glyphicon-minus"></span></button></td></tr>');
							}
							function delete_row1(rowno){
								$('#'+rowno).remove();
							}
						</script>
					</div>
				</div>	
			</div>
			<div class="col-md-12 table-responsive">
				<table class="table table-bordered" id="item_table" style="width:100%">
					<tr>
						<th style="width:50%">*Project Start Date</th>
						<th style="width:50%">*Project End Date</th>
					</tr>
					<tr>
						<td>
							<div class="form-line" id="bs_datepicker_container">
								<input name="projstartdate" type="date" title="d/m/Y" id="projstartdate" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Click here to choose project start date" required value="<?php echo $row_rsProject['projstartdate']?>"/>
							</div>
						</td>
						<td>
							<div class="form-line" id="bs_datepicker_container">
								<input name="projenddate" type="date" title="d/m/Y" id="projenddate" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Click here to choose project end date" required value="<?php echo $row_rsProject['projenddate']?>"/>
							</div>
						</td>
					</tr>
				</table>
			</div>			
			<div class="col-md-12">
				<ul class="list-inline pull-right">
					<li><button type="button" class="btn btn-default prev-step">Previous</button></li>
					<li><button class="btn btn-primary nextBtn btn-lg " type="button" >Next</button>  </li>
				</ul>
			</div>
		</fieldset>
		<fieldset class="scheduler-border row setup-content" id="step-5">
			<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">FINISH</legend>
			<div class="col-md-12">
				<p>Take a moment to review the information you have entered in this form, and edit any which might not be correct.</p>
			</div>
			<div class="col-md-4">
			</div>
			<div class="col-md-4">
			</div>
			<div class="col-md-4">
				<ul class="list-inline pull-right">
					<input type="hidden" name="MM_update" value="editprojectfrm">
					<input type="hidden" name="projid" id="myprojid" value="<?php echo $projid; ?>">
					<input type="hidden" name="username" value="<?php echo $user_name; ?>">
					<button type="button" class="btn btn-default prev-step">Previous</button>
					<button class="btn btn-success pull-right" type="submit">Save!</button>
				</ul>
			</div>
		</fieldset>
	</form>
</div>
<?php 
} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>



<script>
$(document).ready(function () {
    $("#form").validate({
        ignore: [],
        rules:{
            firstname:{
                required:true
            },
            editor1: {
            ckeditor_required:true
            }         
        },
        errorPlacement: function (error, element) {
            var lastError = $(element).data('lastError'),
                newError = $(error).text();

            $(element).data('lastError', newError);

            if(newError !== '' && newError !== lastError){ 
                $(element).after('<div class="red">The field is Required</div>');  
            }
        },
        success: function (label, element) {
            $(element).next(".red").remove(); 
        }
    });

var navListItems = $('div.setup-panel div a'),
        allWells = $('.setup-content'),
        allNextBtn = $('.nextBtn');
        allPrevBtn = $('.prev-step');
allWells.hide();
navListItems.click(function (e) {
    e.preventDefault();
    var $target = $($(this).attr('href')),
        $item = $(this);

    if (!$item.hasClass('disabled')) {
        navListItems.removeClass('btn-primary').addClass('btn-default');
        $item.addClass('btn-primary');
        allWells.hide();
        $target.show();
        $target.find('input:eq(0)').focus();
    }
});

/* Handles validating using jQuery validate.
*/
allNextBtn.click(function(){
var prevStep;
    var curStep = $(this).closest(".setup-content"),
        curStepBtn = curStep.attr("id"),
        nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
        curInputs = curStep.find("input, select"),
        isValid = true;

    //Loop through all inputs in this form group and validate them.
    for(var i=0; i<curInputs.length; i++){
        if (!$(curInputs[i]).valid()){
            isValid = false;
        }
    }
    if (isValid){
        //Progress to the next page.
      nextStepWizard.addClass('verified');
      nextStepWizard.removeClass('disabled').trigger('click');    
    }
});
allPrevBtn.click(function (e) {
    var curStep = $(this).closest(".setup-content");
    curStepBtn = curStep.attr("id");
    prevStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().prev().children("a");
    prevStepWizard.removeClass('disabled').trigger('click');  
}); 

$('div.setup-panel div a.btn-primary').trigger('click');

});
</script>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  