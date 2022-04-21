<?php

require_once('Connections/ProjMonEva.php'); 
//include_once 'projtrac-dashboard/resource/session.php';
include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

try{
	if(isset($_POST["observid"]) && $_POST["observid"]==2){
		$myprjid = $_POST["myprjid"];
		$opid = $_POST["opid"];
		$opdetailsid = $_POST["opdetailsid"];
		
		function risk_category_select_box($db,$myprjid,$opdetailsid)
		{ 
			$risk = '';
			$query_allrisks = $db->prepare("SELECT C.rskid, C.category FROM tbl_projrisk_categories C INNER JOIN tbl_projectrisks R ON C.rskid=R.rskid where R.projid='$myprjid' and outputid='$opdetailsid' ORDER BY R.id ASC");
			$query_allrisks->execute();
			$rows_allrisks = $query_allrisks->fetchAll();
			foreach($rows_allrisks as $row)
			{
				$risk .= '<option value="'.$row["rskid"].'">'.$row["category"].'</option>';
			}
			return $risk;
		}
			
		echo '<tr>
				<th style="width:2%">#</th>
				<th style="width:21%">Issue</th>
				<th style="width:75%">Description</th>
				<th style="width:2%"><button type="button" name="addplus" onclick="add_list'.$opid.'();" title="Add another question" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
			</tr>
			<tr>
				<td>1</td>
				<td>										
					<div class="form-line">
						<select name="issue'.$opid.'[]" id="issue'.$opid.'[]" class="form-control topic" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px"  required><option value="" selected="selected" class="selection">... Select ...</option>'.risk_category_select_box($db,$myprjid,$opdetailsid).'</select>
					</div>
				</td>
				<td>
					<input type="text" name="issuedescription'.$opid.'[]" id="issuedescription'.$opid.'[]" class="form-control"  placeholder="Description the issue here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required>
				</td>
				<td></td>
			</tr>';
	}elseif(isset($_POST["observid"]) && $_POST["observid"]==1){
		$opid = $_POST["opid"];
		 
			echo '
			<div class="col-md-12">
				<label class="control-label">Description  *: </label>
				<p align="left">
					<textarea name="observation'.$opid.'" cols="45" rows="5" class="txtboxes" id="observation'.$opid.'" required="required" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder="Briefly describe the goals and objectives of the project, the approaches and execution methods, resource estimates, people and organizations involved, and other relevant information that explains the need for project as well as the amount of work planned for implementation."></textarea>
					<script>
						CKEDITOR.replace("observation'.$opid.'", {
							height: 200,
							on: {
								instanceReady: function(ev) {
									// Output paragraphs as <p>Text</p>.
									this.dataProcessor.writer.setRules("p", {
										indent: false,
										breakBeforeOpen: false,
										breakAfterOpen: false,
										breakBeforeClose: false,
										breakAfterClose: false
									});
									this.dataProcessor.writer.setRules("ol", {
										indent: false,
										breakBeforeOpen: false,
										breakAfterOpen: false,
										breakBeforeClose: false,
										breakAfterClose: false
									});
									this.dataProcessor.writer.setRules("ul", {
										indent: false,
										breakBeforeOpen: false,
										breakAfterOpen: false,
										breakBeforeClose: false,
										breakAfterClose: false
									});
									this.dataProcessor.writer.setRules("li", {
										indent: false,
										breakBeforeOpen: false,
										breakAfterOpen: false,
										breakBeforeClose: false,
										breakAfterClose: false
									});
								}
							}
						});
					</script>
				</p>
			</div>';
	}
	
}catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $result;
}