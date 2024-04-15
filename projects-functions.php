<?php
try {
	//code...


	function get_project_percentage($projid)
	{
		global $db;
		
		$query_rsMyP =  $db->prepare("SELECT *, projcost, projstartdate AS sdate, projenddate AS edate, projcategory, progress FROM tbl_projects WHERE deleted='0' AND projid = '$projid'");
		$query_rsMyP->execute();
		$row_rsMyP = $query_rsMyP->fetch(); 
		$percent2 = $row_rsMyP['progress'];
		return $percent2; 
		$percentage = 0;
		$numberofparameters = 0;
		$averageprogress = 0;
		$query_project_outputs = $db->prepare("SELECT * FROM tbl_project_details WHERE projid=:projid");
		$query_project_outputs->execute(array(":projid" => $projid));
		
		while($rows_project_outputs = $query_project_outputs->fetch()){
			$output_id = $rows_project_outputs["id"];
			$query_output_mapping_type = $db->prepare("SELECT indicator_mapping_type FROM tbl_project_output_designs d left join tbl_project_details o on o.id=d.output_id left join tbl_indicator i on i.indid=o.indicator WHERE d.output_id=:output_id");
			$query_output_mapping_type->execute(array(":output_id" => $output_id));
			$rows_output_mapping_type = $query_output_mapping_type->fetch();
			$output_mapping_type = $rows_output_mapping_type["indicator_mapping_type"];
			
			$output_location_level = $output_mapping_type == 1 ? 1 : 2;  // OnePoint : WayPoint/AreaPoint
			
			if($output_location_level == 2){
				$query_output_design = $db->prepare("SELECT id FROM tbl_project_output_designs WHERE output_id=:output_id");
				$query_output_design->execute(array(":output_id" => $output_id));
				$rows_output_design = $query_output_design->fetch();
				$designid = $rows_output_design["id"];
				
				$query_checklist = $db->prepare("SELECT checklist_id, target FROM tbl_project_monitoring_checklist WHERE design_id=:designid");
				$query_checklist->execute(array(":designid" => $designid));
				$totalRows_checklist = $query_checklist->rowCount();
						
				if ($totalRows_checklist > 0) { 
					while ($row_checklist = $query_checklist->fetch()) {
						$numberofparameters++;
						$achived = 0;
						$checklist_id = $row_checklist['checklist_id'];
						$target = $row_checklist['target'];
						
						$query_output_states = $db->prepare("SELECT outputstate FROM tbl_output_disaggregation WHERE outputid=:output_id");
						$query_output_states->execute(array(":output_id" => $output_id));
						
						while($row_output_states = $query_output_states->fetch()){
							$state_id = $row_output_states['outputstate'];
							
							$query_rsScore = $db->prepare("SELECT achieved FROM tbl_project_monitoring_checklist_score WHERE checklist_id=:checklist_id AND state_id=:state_id ORDER BY id DESC LIMIT 1");
							$query_rsScore->execute(array(":checklist_id" => $checklist_id, ':state_id' => $state_id));
							$totalRows_rsScore = $query_rsScore->rowCount();
							
							if ($totalRows_rsScore > 0) {
								$row_rsScore = $query_rsScore->fetch();
								$achived = $row_rsScore['achieved'];
							}
							
							$percentage += ($achived / $target) * 100;
						}
					}
					$averageprogress = $percentage/$numberofparameters;
					$averageprogress = number_format($averageprogress, 2);
				}
			} else {
				$query_output_design = $db->prepare("SELECT id,sites FROM tbl_project_output_designs WHERE output_id=:output_id");
				$query_output_design->execute(array(":output_id" => $output_id));
				
				while($rows_output_design = $query_output_design->fetch()){
					$design_sites = explode(",",$rows_output_design["sites"]);
					
					foreach($design_sites as $site_id){
						$designid = $rows_output_design["id"];
				
						$query_checklist = $db->prepare("SELECT checklist_id, target FROM tbl_project_monitoring_checklist WHERE design_id=:designid");
						$query_checklist->execute(array(":designid" => $designid));
						$totalRows_checklist = $query_checklist->rowCount();
								
						if ($totalRows_checklist > 0) { 
							while ($row_checklist = $query_checklist->fetch()) {
								$numberofparameters++;
								$achived = 0;
								$checklist_id = $row_checklist['checklist_id'];
								$target = $row_checklist['target'];
						
								$query_rsScore = $db->prepare("SELECT achieved FROM tbl_project_monitoring_checklist_score WHERE checklist_id=:checklist_id AND site_id=:site_id ORDER BY id DESC LIMIT 1");
								$query_rsScore->execute(array(":checklist_id" => $checklist_id, ":site_id" => $site_id));
								$totalRows_rsScore = $query_rsScore->rowCount();
								
								if ($totalRows_rsScore > 0) {
									$row_rsScore = $query_rsScore->fetch();
									$achived = $row_rsScore['achieved'];
								}
								
								$percentage += ($achived/$target) * 100;
							}
						}
					}
				}
				$averageprogress = $percentage/$numberofparameters;
				$averageprogress = number_format($averageprogress, 2);
			}
		}
		return $averageprogress;
	}


	function get_output_percentage($output_id)
	{
		global $db;
		$percentage = 0;
		$numberofparameters = 0;
		$averageprogress = 0;

		$query_tasks = $db->prepare("SELECT * FROM tbl_task WHERE outputid=:output_id");
		$query_tasks->execute(array(":output_id" => $output_id));
		$totalRows_tasks = $query_tasks->rowCount();
		if ($totalRows_tasks > 0) { 
			while ($row_tasks = $query_tasks->fetch()) {
				$task_id = $row_tasks['tkid'];
				$query_checklist = $db->prepare("SELECT checklist_id, target FROM tbl_project_monitoring_checklist WHERE projid=:projid AND task_id=:task_id");
				$query_checklist->execute(array(":projid" => $projid, ":task_id" => $task_id));
				$totalRows_checklist = $query_checklist->rowCount();
				
				if ($totalRows_checklist > 0) { 
					while ($row_checklist = $query_checklist->fetch()) {
						$numberofparameters++;
						$achived = 0;
						$checklist_id = $row_checklist['checklist_id'];
						$target = $row_checklist['target'];
				
						$query_rsScore = $db->prepare("SELECT achieved FROM tbl_project_monitoring_checklist_score WHERE checklist_id=:checklist_id ORDER BY id DESC LIMIT 1");
						$query_rsScore->execute(array(":checklist_id" => $checklist_id));
						$totalRows_rsScore = $query_rsScore->rowCount();
						
						if ($totalRows_rsScore > 0) {
							$row_rsScore = $query_rsScore->fetch();
							$achived = $row_rsScore['achieved'];
						}
						
						$percentage += ($achived / $target) * 100;
						$averageprogress = $percentage/$numberofparameters;
					}
				}
			}
		}
		return number_format($averageprogress, 2);
	}


	function get_site_percentage($output_id, $design_id, $site_id, $state_id)
	{
		global $db;
		$percentage = 0;
		$numberofparameters = 0;
		$averageprogress = 0; 
		$target = 0; 
		
		$query_output_mapping_type = $db->prepare("SELECT indicator_mapping_type FROM tbl_project_output_designs d left join tbl_project_details o on o.id=d.output_id left join tbl_indicator i on i.indid=o.indicator WHERE d.output_id=:output_id");
		$query_output_mapping_type->execute(array(":output_id" => $output_id));
		$rows_output_mapping_type = $query_output_mapping_type->fetch();
		$output_mapping_type = $rows_output_mapping_type["indicator_mapping_type"];
		
		$output_location_level = $output_mapping_type == 1 ? 1 : 2;
		
		if($output_location_level == 2){
			$query_output_design = $db->prepare("SELECT id FROM tbl_project_output_designs WHERE output_id=:output_id");
			$query_output_design->execute(array(":output_id" => $output_id));
			$rows_output_design = $query_output_design->fetch();
			$designid = $rows_output_design["id"];
			
			$query_checklist = $db->prepare("SELECT checklist_id, target FROM tbl_project_monitoring_checklist WHERE design_id=:designid");
			$query_checklist->execute(array(":designid" => $designid));
			$totalRows_checklist = $query_checklist->rowCount();
					
			if ($totalRows_checklist > 0) { 
				while ($row_checklist = $query_checklist->fetch()) {
					$numberofparameters++;
					$achived = 0;
					$checklist_id = $row_checklist['checklist_id'];
					$target = $row_checklist['target'];
			
					$query_rsScore = $db->prepare("SELECT achieved FROM tbl_project_monitoring_checklist_score WHERE checklist_id=:checklist_id AND state_id=:state_id ORDER BY id DESC LIMIT 1");
					$query_rsScore->execute(array(":checklist_id" => $checklist_id, ':state_id' => $state_id));
					$totalRows_rsScore = $query_rsScore->rowCount();
					
					if ($totalRows_rsScore > 0) {
						$row_rsScore = $query_rsScore->fetch();
						$achived = $row_rsScore['achieved'];
					}
					
					$percentage += ($achived / $target) * 100;
				}
				$averageprogress = $percentage/$numberofparameters;
				$averageprogress = number_format($averageprogress, 2);
			}
		} else {
			$query_output_design = $db->prepare("SELECT id,sites FROM tbl_project_output_designs WHERE output_id=:output_id");
			$query_output_design->execute(array(":output_id" => $output_id));
			
			while($rows_output_design = $query_output_design->fetch()){
				$design_sites = explode(",",$rows_output_design["sites"]);
				
				if(in_array($site_id,$design_sites)){
					$designid = $rows_output_design["id"];
			
					$query_checklist = $db->prepare("SELECT checklist_id, target FROM tbl_project_monitoring_checklist WHERE design_id=:designid");
					$query_checklist->execute(array(":designid" => $designid));
					$totalRows_checklist = $query_checklist->rowCount();
							
					if ($totalRows_checklist > 0) { 
						while ($row_checklist = $query_checklist->fetch()) {
							$numberofparameters++;
							$achived = 0;
							$checklist_id = $row_checklist['checklist_id'];
							$target = $row_checklist['target'];
					
							$query_rsScore = $db->prepare("SELECT achieved FROM tbl_project_monitoring_checklist_score WHERE checklist_id=:checklist_id AND site_id=:site_id ORDER BY id DESC LIMIT 1");
							$query_rsScore->execute(array(":checklist_id" => $checklist_id, ":site_id" => $site_id));
							$totalRows_rsScore = $query_rsScore->rowCount();
							
							if ($totalRows_rsScore > 0) {
								$row_rsScore = $query_rsScore->fetch();
								$achived = $row_rsScore['achieved'];
							}
							
							$percentage += ($achived/$target) * 100;
						}
						$averageprogress = $percentage/$numberofparameters;
						$averageprogress = number_format($averageprogress, 2);
					}
				}
			}
		}
		return $averageprogress;
	}

	function get_task_percentage($output_id, $design_id, $site_id, $state_id, $task_id)
	{
		global $db;
		$percentage = 0;
		$numberofparameters = 0;
		$averageprogress = 0; 
		$target = 0; 
		
		$query_output_mapping_type = $db->prepare("SELECT indicator_mapping_type FROM tbl_project_output_designs d left join tbl_project_details o on o.id=d.output_id left join tbl_indicator i on i.indid=o.indicator WHERE d.output_id=:output_id");
		$query_output_mapping_type->execute(array(":output_id" => $output_id));
		$rows_output_mapping_type = $query_output_mapping_type->fetch();
		$output_mapping_type = $rows_output_mapping_type["indicator_mapping_type"];
		
		$output_location_level = $output_mapping_type == 1 ? 1 : 2;
		
		if($output_location_level == 2){
			$query_output_design = $db->prepare("SELECT id FROM tbl_project_output_designs WHERE output_id=:output_id");
			$query_output_design->execute(array(":output_id" => $output_id));
			$rows_output_design = $query_output_design->fetch();
			$designid = $rows_output_design["id"];
			
			$query_checklist = $db->prepare("SELECT checklist_id, target FROM tbl_project_monitoring_checklist WHERE design_id=:designid AND task_id=:task_id");
			$query_checklist->execute(array(":designid" => $designid, ":task_id" => $task_id));
			$totalRows_checklist = $query_checklist->rowCount();
					
			if ($totalRows_checklist > 0) { 
				while ($row_checklist = $query_checklist->fetch()) {
					$numberofparameters++;
					$achived = 0;
					$checklist_id = $row_checklist['checklist_id'];
					$target = $row_checklist['target'];
			
					$query_rsScore = $db->prepare("SELECT achieved FROM tbl_project_monitoring_checklist_score WHERE checklist_id=:checklist_id AND state_id=:state_id ORDER BY id DESC LIMIT 1");
					$query_rsScore->execute(array(":checklist_id" => $checklist_id, ':state_id' => $state_id));
					$totalRows_rsScore = $query_rsScore->rowCount();
					
					if ($totalRows_rsScore > 0) {
						$row_rsScore = $query_rsScore->fetch();
						$achived = $row_rsScore['achieved'];
					}
					
					$percentage += ($achived / $target) * 100;
				}
				$averageprogress = $percentage/$numberofparameters;
				$averageprogress = number_format($averageprogress, 2);
			}
		} else {
			$query_output_design = $db->prepare("SELECT id,sites FROM tbl_project_output_designs WHERE output_id=:output_id");
			$query_output_design->execute(array(":output_id" => $output_id));
			
			while($rows_output_design = $query_output_design->fetch()){
				$design_sites = explode(",",$rows_output_design["sites"]);
				
				if(in_array($site_id,$design_sites)){
					$designid = $rows_output_design["id"];
			
					$query_checklist = $db->prepare("SELECT checklist_id, target FROM tbl_project_monitoring_checklist WHERE design_id=:designid AND task_id=:task_id");
					$query_checklist->execute(array(":designid" => $designid, ":task_id" => $task_id));
					$totalRows_checklist = $query_checklist->rowCount();
							
					if ($totalRows_checklist > 0) { 
						while ($row_checklist = $query_checklist->fetch()) {
							$numberofparameters++;
							$achived = 0;
							$checklist_id = $row_checklist['checklist_id'];
							$target = $row_checklist['target'];
					
							$query_rsScore = $db->prepare("SELECT achieved FROM tbl_project_monitoring_checklist_score WHERE checklist_id=:checklist_id AND site_id=:site_id ORDER BY id DESC LIMIT 1");
							$query_rsScore->execute(array(":checklist_id" => $checklist_id, ":site_id" => $site_id));
							$totalRows_rsScore = $query_rsScore->rowCount();
							
							if ($totalRows_rsScore > 0) {
								$row_rsScore = $query_rsScore->fetch();
								$achived = $row_rsScore['achieved'];
							}
							
							$percentage += ($achived/$target) * 100;
						}
						$averageprogress = $percentage/$numberofparameters;
						$averageprogress = number_format($averageprogress, 2);
					}
				}
			}
		}
		return $averageprogress;
	}

} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}