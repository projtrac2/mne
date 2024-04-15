<?php
try{

//require_once('Connections/ProjMonEva.php'); 
//include_once 'projtrac-dashboard/resource/session.php';
include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

	$myprjid = $_POST["myprjid"];
	$opid = $_POST["opid"];
	$opdetailsid = $_POST["opdetailsid"];
	if(isset($_POST["observid"]) && $_POST["observid"]==2){
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
		echo '<tr>
				<th style="width:100%">Add Observation</th>
			</tr>
			<tr>
				<td>
					<input type="text" name="observation'.$opid.'" class="form-control"  placeholder="Enter your observation here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required>
				</td>
			</tr>';
	}
	
}catch (PDOException $th){
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());

}