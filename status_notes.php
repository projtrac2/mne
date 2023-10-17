<?php
if (isset($_POST["proj_id"]) && !empty($_POST["proj_id"])) {

	$projstatus = $_POST["status_id"];
	$projid = $_POST["proj_id"];
	//$projid = "78";
	$projOrigId = $_POST["projOrig_id"];


	echo '
<!-- ----------------------- -->
						<form class="form-horizontal" action="myprojectdash.php?projid=' . $projid . '" method="post"  id="updateForm" enctype="multipart/form-data">
							<fieldset class="scheduler-border"  width="80%">
		
							<legend  class="scheduler-border">Please Explain Below</legend>
							<div class="form-group">
								<div class="span5">
	                        <textarea class="span5" id="reason" name="reason" required></textarea ><br>
								</div>
							</div>
							<div class="form-group">
								<div class="span5">
									<br>
									Select and attach a file: 
									<input type="file" name="fileToUpload" id="fileToUpload" required>
								</div>
							</div>
							
							</fieldset>
						<br>
							<div class="form-group">
								<label class="col-sm-3 control-label"></label>
								<div class="col-sm-4 col-sm-offset-2">
								<input type="hidden" name="projid" value="' . $projid . '">
								<input type="hidden" name="projchange" value="' . $projstatus . '">
								<input type="hidden" name="projorigstatus" value="' . $projOrigId . '">
									<button type="submit" name="stchange" class="btn btn-primary">Submit</button>
								</div>
							</div>
						</form>';
}
