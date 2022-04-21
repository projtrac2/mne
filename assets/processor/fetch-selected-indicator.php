<?php
include_once "controller.php";

try {
    // delete information on page readty 
    if (isset($_POST['deleteItem'])) {
        $indid = base64_decode($_POST['indid']);
        $valid['success'] = true;
        $valid['messages'] = "Successfully Deleted";
        $deleteQuery = $db->prepare("DELETE FROM tbl_indicator WHERE indid=:indid");
        $results = $deleteQuery->execute(array(':indid' => $indid));

        if ($results) {
            if ($results === TRUE) {
                $valid['success'] = true;
                $valid['messages'] = "Successfully Deleted";
            } else {
                $valid['success'] = false;
                $valid['messages'] = "Error while deletng the record!!";
            }
            echo json_encode($valid);
        }
    }

    if (isset($_POST['more_info'])) {
        $indid = base64_decode($_POST['indid']);
        $query_rsProjects = $db->prepare("SELECT * FROM tbl_indicator WHERE indid=:indid");
        $query_rsProjects->execute(array(":indid" => $indid));
        $row_rsProjects = $query_rsProjects->fetch();
        $totalRows_rsProjects = $query_rsProjects->rowCount();
        $indcode = $row_rsProjects['indicator_code'];
        $indname = $row_rsProjects['indicator_name'];
        $inddesc = $row_rsProjects['indicator_description'];
        $calculationmethod = $row_rsProjects['indicator_calculation_method'];
        $indunit = $row_rsProjects['indicator_unit'];
		$inddir = $row_rsProjects['indicator_direction']; 
		$indicator_category = $row_rsProjects['indicator_category']; 
		$indicator_type = $row_rsProjects['indicator_type']; 
		$indicator_sector = $row_rsProjects['indicator_sector']; 
		$indicator_dept = $row_rsProjects['indicator_dept'];     
		$dissagragated = $row_rsProjects['indicator_disaggregation'];   
		$data_source = $row_rsProjects['source_data'];
		$ind_type =""; 
		$ind_dir="";
		
		if ($inddir == 1) {
			$ind_dir ='Upward';
		} else if ($inddir == 2) {
			$ind_dir = 'Downward';
		}

		$query_rsOPUnit = $db->prepare("SELECT unit FROM  tbl_measurement_units WHERE id ='$indunit'");
		$query_rsOPUnit->execute();
		$row_rsOPUnit = $query_rsOPUnit->fetch();
		$unit = $row_rsOPUnit['unit'];

		$query_rscalculation_Method = $db->prepare("SELECT * FROM tbl_indicator_calculation_method WHERE id=:calculationmethod");
        $query_rscalculation_Method->execute(array(":calculationmethod" => $calculationmethod));
        $row_rscalculation_Method = $query_rscalculation_Method->fetch();
		$totalRows_rscalculation_Method = $query_rscalculation_Method->rowCount();
		$calc_method = $row_rscalculation_Method['method'];
		$category ='';
		$calculation ='';
		$direction ="";
		$source_of_data ='';
		if($indicator_type ==1){
			$ind_type ="KPI";
			$query_aggregation = $db->prepare("SELECT * FROM tbl_kpi_aggregation_type WHERE active = 1");
			$query_aggregation->execute();
			$row_aggregation = $query_aggregation->fetch();

		}else{
			$ind_type ="Others";
			$query_rsIndSector = $db->prepare("SELECT * FROM tbl_sectors WHERE parent='0' and deleted='0' AND stid=:indicator_sector");
			$query_rsIndSector->execute(array(":indicator_sector"=>$indicator_sector));
			$row_rsIndSector = $query_rsIndSector->fetch();
			$totalRows_rsIndSector = $query_rsIndSector->rowCount();
			$sector = $row_rsIndSector['sector'];

			$query_rsIndDept = $db->prepare("SELECT * FROM tbl_sectors WHERE deleted='0' and stid=:indicator_dept");
			$query_rsIndDept->execute(array(":indicator_dept"=>$indicator_dept));
			$row_rsIndDept = $query_rsIndDept->fetch();
			$totalRows_rsIndDept = $query_rsIndDept->rowCount();
			$department = $row_rsIndDept['sector']; 

			if($indicator_category =="Output"){  
				if ($dissagragated == 1) {
					$type = 0;
					$query_diss_type = $db->prepare("SELECT * FROM tbl_indicator_measurement_variables_disaggregation_type WHERE indicatorid=:indid  ");
					$query_diss_type->execute(array(":indid" => $indid));

					$query_dataSource =  $db->prepare("SELECT * FROM tbl_data_source  WHERE id='$data_source'");
					$query_dataSource->execute();
					$row_dataSource = $query_dataSource->fetch();
					$source_data = $row_dataSource['source'];
					$source_of_data .='<li class="list-group-item"><strong>Source of Data: </strong>' . $source_data . ' </li> ';

					$category .= '  
					<div class="row clearfix">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="card">   
								<div class="body">
									<div class="header"> 
										<li class="list-group-item list-group-item list-group-item-action active">Disaggregation Types</li>
									</div>';
									$options = ' 
									<ul class="list-group">';
									$count =1;
									while ($row = $query_diss_type->fetch()) {
										$disaggregation_type =$row['disaggregation_type'];
										$query_diss = $db->prepare("SELECT * FROM tbl_indicator_disaggregation_types WHERE id=:disaggregation_type  ");
										$query_diss->execute(array(":disaggregation_type" => $disaggregation_type));
										$row_diss = $query_diss->fetch();
										$options .='<li class="list-group-item">' . $count++ .". ". $row_diss['category']. '</li>';
									}
									$options .= '</ul>';
									$category .= $options;
									$category .=' 
								</div>
							</div>
						</div>
					</div>';
				} else{
					$category .='<div class="row clearfix"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"></div></div>';
				} 

							 
								
			}else if($indicator_category =="Impact"){ 
				$calculation .='<li class="list-group-item"><strong>Calculation Method: </strong>' . $calc_method . ' </li> ';
				$direction .='<li class="list-group-item"><strong>Indicator Direction: </strong>' . $ind_dir . ' </li> ';
				$data ='';
				$query_measurement_variables = $db->prepare("SELECT * FROM  tbl_indicator_measurement_variables WHERE indicatorid = '$indid' AND  category='1' ORDER BY id");
				$query_measurement_variables->execute();
				$row_measurement_variables = $query_measurement_variables->fetch(); 

				$category .= '  
					<div class="row clearfix">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="card">   
								<div class="body">
									<div class="header"> 
										<li class="list-group-item list-group-item list-group-item-action active">Measurement Variable/s</li>
									</div>'; 
									$data .='
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<ul>';
											if($calculationmethod ==1){ 
												$variable = $row_measurement_variables['measurement_variable'];
												$data .=
												' <li class="list-group-item"><strong>Summation Measurement variables: </strong>' . $variable . ' </li> ';
											}else if($calculationmethod ==2){ 
												do{
													$variable =$row_measurement_variables['measurement_variable'];
													$type =$row_measurement_variables['type']; 
													
													if($type =='n'){ 
														$data .=
														'<li class="list-group-item"><strong>Numerator Measurement variables: </strong>' . $variable . ' </li> ';
													}else if($type =='d'){ 
														$data .=
														'<li class="list-group-item"><strong>Denominator Measurement variables: </strong>' . $variable . ' </li> ';  
													}
												}while($row_measurement_variables = $query_measurement_variables->fetch()); 
											}else if($calculationmethod ==3){
												$variable = $row_measurement_variables['measurement_variable'];
												$data .=
												'<li class="list-group-item"><strong>Numerator Measurement variables: </strong>' . $variable . ' </li>';   
											} 
											$data .="
										</ul>
									</div>"; 
										$category .= $data;  
										if ($dissagragated == 1) { 
											$category .= '  
											<div class="row clearfix">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="header"> 
													<li class="list-group-item list-group-item list-group-item-action active">Disaggregations </li>
												</div>
													<div class="table-responsive">
														<table class="table table-bordered table-striped table-hover" id="impact_table" style="width:100%">
															<thead>
																<tr>
																	<th width="10%">#</th>
																	<th width="30%">Dissagragation Type </th>
																	<th width="30%">Parent</th>
																	<th width="30%">Disaggregations</th>  
																</tr>
															</thead>
															<tbody id="impact_table_body">';  
																$query_rsIndType = $db->prepare("SELECT * FROM tbl_indicator_measurement_variables_disaggregation_type WHERE indicatorid= '$indid' and type=1 ORDER BY id");
																$query_rsIndType->execute();
																$row_rsIndType = $query_rsIndType->fetch();
																$indIndTypecount = $query_rsIndType->rowCount();
													
																	if($indIndTypecount >0){  
																		$rowno =0;
																		do{
																			$rowno++; 

																			$impact_dissagragated_type = $row_rsIndType['disaggregation_type']; 
																			$impact_dissagragated_parent = $row_rsIndType['parent']; 

																			$query_rsInd_type = $db->prepare("SELECT * FROM tbl_indicator_disaggregation_types WHERE id='$impact_dissagragated_type'");
																			$query_rsInd_type->execute();
																			$row_rsInd_type = $query_rsInd_type->fetch();
																			$dissagragated_type = $row_rsInd_type['category'];
																			

																			$query_rsInd_parent = $db->prepare("SELECT * FROM tbl_indicator_disaggregation_types WHERE id='$impact_dissagragated_parent'");
																			$query_rsInd_parent->execute();
																			$row_rsInd_parent = $query_rsInd_parent->fetch();
																			$parent = $row_rsInd_parent['category'];

																			
																			$query_rsInd_type_diss = $db->prepare("SELECT * FROM tbl_indicator_disaggregations WHERE indicatorid=:indicatorid AND disaggregation_type=:disaggregation_type");
																			$query_rsInd_type_diss->execute(array(":indicatorid"=>$indid, ":disaggregation_type"=>$impact_dissagragated_type));
																			$row_rsInd_type_diss = $query_rsInd_type_diss->fetch();
																			$data_diss =[];

																			do{
																				$data_diss[] = $row_rsInd_type_diss['disaggregation'];
																			}while($row_rsInd_type_diss = $query_rsInd_type_diss->fetch());
																			
																			
																			$category .= 
																			' <tr id="">
																				<td>'.$rowno.'</td> 
																				<td> 
																					'.$dissagragated_type.'
																				</td> 
																				<td>
																					'.$parent.'
																				</td> 
																				<td>
																				'.implode(",", $data_diss).'
																				</td> 
																			</tr>';
																		}while($row_rsIndType = $query_rsIndType->fetch());
																	} 
																$category .= 
															'</tbody>
														</table>
													</div>
												</div> 		
											</div>';
										} else {
											$category .='<div class="row clearfix"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"></div></div>';
										}  
									$category .=' 
								</div>
							</div>
						</div>
					</div>';  
			}else if($indicator_category =="Outcome"){
				$calculation .='<li class="list-group-item"><strong>Calculation Method: </strong>' . $calc_method . ' </li> ';
				$direction .='<li class="list-group-item"><strong>Indicator Direction: </strong>' . $ind_dir . ' </li> ';


				$category .= '  
					<div class="row clearfix">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="card">   
								<div class="body">
									<div class="header"> 
										<li class="list-group-item list-group-item list-group-item-action active"> Direct Measurement Variable/s</li>
									</div>'; 
									$data ='
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<ul>';
										$query_measurement_variables = $db->prepare("SELECT * FROM  tbl_indicator_measurement_variables WHERE indicatorid = '$indid' AND  category='2' ORDER BY id");
										$query_measurement_variables->execute();
										$row_measurement_variables = $query_measurement_variables->fetch(); 

											if($calculationmethod ==1){ 
												$variable = $row_measurement_variables['measurement_variable'];
												$data .=
												' <li class="list-group-item"><strong>Summation Measurement variables: </strong>' . $variable . ' </li> ';
											}else if($calculationmethod ==2){ 
												do{
													$variable =$row_measurement_variables['measurement_variable'];
													$type =$row_measurement_variables['type']; 
													
													if($type =='n'){ 
														$data .=
														'<li class="list-group-item"><strong>Numerator Measurement variables: </strong>' . $variable . ' </li> ';
													}else if($type =='d'){ 
														$data .=
														'<li class="list-group-item"><strong>Denominator Measurement variables: </strong>' . $variable . ' </li> ';  
													}
												}while($row_measurement_variables = $query_measurement_variables->fetch()); 
											}else if($calculationmethod ==3){
												$variable = $row_measurement_variables['measurement_variable'];
												$data .=
												'<li class="list-group-item"><strong>Numerator Measurement variables: </strong>' . $variable . ' </li>';   
											} 
											$data .="
										</ul>
									</div>"; 
									$category .= $data;
 

									$query_rsInd_Direct_Outcome_Type = $db->prepare("SELECT * FROM tbl_indicator_measurement_variables_disaggregation_type WHERE indicatorid= '$indid' and type=2 ORDER BY id");
									$query_rsInd_Direct_Outcome_Type->execute();
									$row_rsInd_Direct_Outcome_Type = $query_rsInd_Direct_Outcome_Type->fetch();
									$indInd_Direct_Outcome_Typecount = $query_rsInd_Direct_Outcome_Type->rowCount();
									if($indInd_Direct_Outcome_Typecount>0){
										$category .='
										<div class="row clearfix">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="header"> 
													<li class="list-group-item list-group-item list-group-item-action active">Direct Disaggregation </li>
												</div>
												<div class="table-responsive">
													<table class="table table-bordered table-striped table-hover" id="direct_outcome_table" style="width:100%">
														<thead>
															<tr>
																<th width="10%">#</th>
																<th width="30%">Dissagragation Type </th>
																<th width="30%">Parent</th>
																<th width="30%">Disaggregations</th>  
															</tr>
														</thead>  
														<tbody id="direct_outcome_table_body">';    
															if($indInd_Direct_Outcome_Typecount>0){
																$rowno =0;
																do{
																	$rowno++; 
																	$direct_outcome_dissagragated_type = $row_rsInd_Direct_Outcome_Type['disaggregation_type']; 
																	$direct_outcome_dissagragated_parent = $row_rsInd_Direct_Outcome_Type['parent']; 

																	$query_rsInd_type = $db->prepare("SELECT * FROM tbl_indicator_disaggregation_types WHERE id='$direct_outcome_dissagragated_type'");
																	$query_rsInd_type->execute();
																	$row_rsInd_type = $query_rsInd_type->fetch();
																	$dir_outcome_options = $row_rsInd_type['category'];

																
																	$query_rsInd_parent = $db->prepare("SELECT * FROM tbl_indicator_disaggregation_types WHERE id= '$direct_outcome_dissagragated_parent'");
																	$query_rsInd_parent->execute();
																	$row_rsInd_parent = $query_rsInd_parent->fetch();
																	$dir_parent_options = $row_rsInd_parent['category'];
																	

																	$query_rsInd_type_diss = $db->prepare("SELECT * FROM tbl_indicator_disaggregations WHERE indicatorid=:indicatorid AND disaggregation_type=:disaggregation_type");
																	$query_rsInd_type_diss->execute(array(":indicatorid"=>$indid, ":disaggregation_type"=>$direct_outcome_dissagragated_type));
																	$row_rsInd_type_diss = $query_rsInd_type_diss->fetch();
																	$data_diss =[];

																	do{
																		$data_diss[] = $row_rsInd_type_diss['disaggregation'];
																	}while($row_rsInd_type_diss = $query_rsInd_type_diss->fetch()); 

																	$category .= '
																	<tr id="direct'.$rowno.'">
																		<td>'.$rowno.'</td> 
																		<td> 
																		'.$dir_outcome_options.'
																		</td> 
																		<td> 
																			'.$dir_parent_options.'
																		</td> 
																		<td>
																		'.implode(",",$data_diss).'
																		</td>  
																	</tr>'; 
																}while($row_rsInd_Direct_Outcome_Type = $query_rsInd_Direct_Outcome_Type->fetch());
															}  
															$category .= '
														</tbody>
													</table>
												</div>
											</div>
										</div>'; 
									}else{
										$category .='
										<div class="row clearfix">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											
											</div>
										</div>';
									}
 
								$category .=' 
								</div>
							</div>
						</div>
					</div>'; 
				 
			} 
							 
		}
 

		$indicatordetails  = '
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">  
					<div class="header"> 
						<li class="list-group-item list-group-item list-group-item-action active">Indicator Name: ' . $indname . ' </li>
					</div>   
					<div class="body"> 
							<div class="col-md-6"> 
								<ul class="list-group"> 
									<li class="list-group-item"><strong>Indicator Code: </strong>' . $indcode . ' </li>
									'.$source_of_data.' 
									'.$calculation.' 
								</ul>
							</div>
							<div class="col-md-6"> 
								<ul class="list-group">
									<li class="list-group-item"><strong>Measurement Unit: </strong>' . $unit . ' </li> 
									'.$direction.'
								</ul>
							</div>     
							<div class="col-md-12">
								<li class="list-group-item list-group-item list-group-item-action active">Indicator Description:</li>
								'.$inddesc.'
							</div>
							'.$category.' 
					</div>
				</div>
			</div>
		</div>';
		echo $indicatordetails;
    }
} catch (PDOException $ex) {
    // $result = flashMessage("An error occurred: " .$ex->getMessage());
    print($ex->getMessage());
}

 