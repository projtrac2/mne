<?php

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

if(isset($_POST['actid'])) 
{
	$actionid = $_POST["actid"];
	$reqid = $_POST["rqid"];
	if($actionid == 2){
	echo '
		<div class="col-sm-12 inputGroupContainer" style="margin-top:10px">
			<h4>PLEASE ADD APPROVAL COMMENTS BELOW IF NECESSARY
			</h4>
		</div>
											
											<div class="form-group">
												<div class="col-sm-12 inputGroupContainer">
													<div class="input-group">
														<div style="margin-bottom:5px"><font color="#174082"><strong>Comments: </strong></font></div>                 
														<textarea name="comments" id="comments" cols="60" rows="5" style="font-size:13px; color:#000; width:99.5%"></textarea>
													</div>
												</div>
											</div>
							<input type="hidden" name="actionstatus" id="actionstatus" value="'.$actionid.'"/>
							<input type="text" name="reqstid" id="reqstid" value="'.$reqid.'"/>
	';
	}else{
	echo '
		<div class="col-sm-12 inputGroupContainer" style="margin-top:10px">
			<h4>PLEASE ADD REJECTION COMMENTS BELOW
			</h4>
		</div><div class="form-group">
												<div class="col-sm-12 inputGroupContainer">
													<div class="input-group">
														<div style="margin-bottom:5px"><font color="#174082"><strong>Comments: </strong></font></div>                 
														<textarea name="comments" id="comments" cols="60" rows="5" style="font-size:13px; color:#000; width:99.5%" required></textarea>
													</div>
												</div>
											</div>
							<input type="hidden" name="actionstatus" id="actionstatus" value="'.$actionid.'"/>
	';	
	}
}
?>