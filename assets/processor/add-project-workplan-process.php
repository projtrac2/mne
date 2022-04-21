<?php 
include_once "controller.php";
try{
// yearly workplan  
if(isset($_POST['get_workplan'])){
    $opid = $_POST['opid'];
    $projid = $_POST['projid']; 
    $frequency = $_POST['frequency']; 
    
    $query_rsOutput =  $db->prepare("SELECT * FROM tbl_project_details WHERE projid =:projid  AND id=:opid");
    $query_rsOutput->execute(array(":projid"=>$projid, ":opid"=>$opid));
    $row_rsOutput = $query_rsOutput->fetch();
    $totalRows_rsOutput = $query_rsOutput->rowCount();

    $oipid = $row_rsOutput['outputid'];
    $indicatorID = $row_rsOutput['indicator'];
    //$duration = $row_rsOutput['duration'];
    $target = $row_rsOutput['total_target'];
    $year = $row_rsOutput['year'];   
  
    $query_tender =  $db->prepare("SELECT MIN(t.sdate) as sdate, MAX(t.edate) as edate FROM `tbl_project_tender_details`d inner join tbl_task t ON t.tkid=d.tasks WHERE d.outputid=:opid");
    $query_tender->execute(array(":opid"=>$opid));
    $row_tender = $query_tender->fetch();
    $sdate = $row_tender['sdate'];
    $edate = $row_tender['edate'];  
												
	$datetime1 = new DateTime($sdate);
	$datetime2 = new DateTime($edate);
	$duration = $datetime1->diff($datetime2)->format("%a");
 
    $opstart_year  =date('Y', strtotime($sdate));
    $opend_year  =date('Y', strtotime($edate));

  
    $opstart_month  =date('F', strtotime($sdate));
    $opstart_date  =date('d', strtotime($sdate));
 

    $month_arr = array('July', 'August', 'September', 'October', 'November', 'December','January', 'February', 'March', 'April', 'May', 'June' ); 
    $index = array_search($opstart_month, $month_arr); 
	//echo "TopIndex: ".$index;

    $firstDate  = date_create($sdate);
    $secondDate = date_create($edate);
 
    $duration = $firstDate->diff($secondDate)->format("%a");
    //$duration = $difference->days; 

  //echo $duration;
    $query_indicator =  $db->prepare("SELECT * FROM `tbl_indicator` WHERE indid = :indid ");
    $query_indicator->execute(array(":indid" => $indicatorID));
    $row_indicator = $query_indicator->fetch();
    $unitid = $row_indicator['indicator_unit']; 

     
    // get the unit of measure 
    $query_Indicator = $db->prepare("SELECT unit FROM tbl_measurement_units WHERE id =:unit ");
    $query_Indicator->execute(array(":unit" => $unitid));
    $row = $query_Indicator->fetch();
    $opunit = $row['unit'];
 
    // Get the output Name 
    $query_out = $db->prepare("SELECT * FROM tbl_progdetails WHERE id='$oipid'");
    $query_out->execute();
    $row_out = $query_out->fetch();
    $outputName = $row_out['output'];
     
    $query_dataColectionFreq =  $db->prepare("SELECT * FROM tbl_datacollectionfreq WHERE fqid=:fqid");
    $query_dataColectionFreq->execute(array(":fqid"=>$frequency));
    $row_dataColectionFreq = $query_dataColectionFreq->fetch();
    $level = $row_dataColectionFreq['level'];
 
    if($level == 6){
        $noofyears =$opend_year - $opstart_year;
        $startyear = $opstart_year + 1;  
        $smonth  =date('m', strtotime($sdate));
        if($smonth < 7 ){
            $noofyears = ($opend_year - $opstart_year)  + 1;  
            $opstart_year -=1;
            $startyear = $opstart_year + 1;
        } 

        $emonth  =date('m', strtotime($edate));
        if($emonth > 6 ){
            $noofyears = $opend_year - $opstart_year + 1;   
        } 
        
        $body = '';
        $header = '';
        $row =0;
        $title =''; 

        for ($rowno = 0; $rowno < $noofyears; $rowno++) {
            $row++; 
            $query_projYear = $db->prepare("SELECT * FROM tbl_project_output_details WHERE projid = :projid AND projoutputid = :opid AND year=:opstartyear ORDER BY year");
            $query_projYear->execute(array(":projid" => $projid, ":opid" => $opid, ":opstartyear" => $opstart_year));
            $rows_OutpuprojYear = $query_projYear->rowCount();
            $row_projYear =  $query_projYear->fetch();
   
            if($rows_OutpuprojYear > 0){  
                $Pyear =  $row_projYear['year'];
                $Target =  $row_projYear['target'];     
                $Eyear =  $Pyear + 1;   
                // $title .= '<th colspan="'.$noofyears.'">Total Target '.$target.' '. $opunit.'</th>';
                $header .= '<th>' . $Pyear . '/' . $Eyear . ' Targets (' . $opunit . ') 
                Target Ceiling <span id="year_targetmsg' . $opstart_year .  $opid . '" style="color:red"> ('.number_format(0).''. $opunit.')</span>  
                </th>';

                $body .= '
                <td>
                    <input type="hidden" name="target_year'.$opid .'[]" id="target_year'.$opid . $Pyear.'" value="' . $Pyear . '" class="t_year'.$opid .'">
                    <input type="hidden" name="ctarget" id="ctarget'.$opid . $Pyear.'" value="' . $Target . '">
                    <input type="number" name="target' . $opstart_year .$opid.'[]" id="yearTargets'.$opid . $Pyear.'" onkeyup="opyear('.$opid .','. $Pyear.')" value="' . $Target . '" placeholder="Enter '.$opunit.'" class="form-control year'.$Pyear . $opid.' target_value'.$Pyear . $opid.'" required>
                </td>';   
            }        
            $opstart_year++;
        }  
 
        $data = '
        <div class="table-responsive"> 
            <table class="table table-bordered table-striped table-hover" id="" style="width:100%">
                <thead>  
                    <tr>
                        '.$header.'
                    </tr>
                    </thead>
                    <tbody id="" >  
                        <tr>
                            '. $body .'  
                        </tr> 
                </tbody>
            </table> 
        </div>'; 
        echo $data;  

    }else if($level ==5){ 
        $durationinmonths = floor($duration / 30.4);  
        $remainingdays = $duration % 30.4;   

        if ($remainingdays > 10) {
            $durationinmonths = $durationinmonths + 1;
        } 

        $semi = floor($durationinmonths / 6);  
		if ($semi == 0) {
			$semi = $semi + 1;
		}else{
			$remainderq = $durationinmonths % 6;
			if($remainderq > 0 ){
				$semi += 1;
			}
		} 

        $noofyears = $semi / 2;  
        $startyear = $opstart_year + 1;
        $counter = 0;
        $containerTH ='';
        $containerTH2 ='';
        $containerTB =''; 

        for ($rowno = 0; $rowno < $noofyears; $rowno++) {
            $query_projYear = $db->prepare("SELECT * FROM tbl_project_output_details WHERE projid = :projid and projoutputid = :opid AND year=:opstartyear");
            $query_projYear->execute(array(":projid" => $projid, ":opid" => $opid, ":opstartyear"  => $opstart_year));
            $rows_OutpuprojYear = $query_projYear->rowCount();
            $row_projYear =  $query_projYear->fetch();
            $Target =  $row_projYear['target'];
 
            $counter++;
            
            if($rowno == 0){
				$qp = 4;
				if($semi < 4){
					$qp = $semi;
				} 
                $opstart_syear = (12 - $index)/6; 
                if($opstart_syear > 1 ){    
                    $containerTH .= '
                    <th colspan="'.$qp.'">' . $opstart_year .  "/" . $startyear . ' 
                        <input type="hidden" name="target_year'.$opid .'[]" id="target_year'.$opid . $opstart_year.'" value="' . $opstart_year . '" class="t_year'.$opid .'">
                        <input type="hidden" name="semianual[]" value="' . $Target  .  '" id="semi_targetc' . $opstart_year .  $opid . '" >
                        Target Ceiling <span id="semi_targetmsg' . $opstart_year .  $opid . '" style="color:red"> ('.number_format($Target).''. $opunit.')</span>  
                    </th>';

                    for ($j = 0; $j < $qp; $j++) {
                        $k = $j + 1;
                       $random_number =  rand(1, 10);
                        $containerTH2 .= '
                        <th width="300px">
                            Semi ' .  $k . ' Target
                        </th>';
                        $containerTB  .= '
                        <td>
                            <input type="number" name="target' . $opstart_year .$opid.'[]" id="semi_target' . $opstart_year . $opid. $random_number . '" onchange="semi('.$opstart_year .',' . $opid.','.$random_number.')" onkeyup="semi('.$opstart_year .',' . $opid.','.$random_number.')" placeholder="Enter '.$opunit.'" class="form-control semi' . $opstart_year  . $opid .' target_value'.$opstart_year . $opid.'" required>
                        </td>';
                    }
                    $semi = $semi - 2;
                }else{  
                    $random_number =  rand(10, 20); 
                        $containerTH .= '
                        <th>' . $opstart_year .  "/" . $startyear . ' 
                            <input type="hidden" name="target_year'.$opid .'[]" id="target_year'.$opid . $opstart_year.'" value="' . $opstart_year . '" class="t_year'.$opid .'">
                            <input type="hidden" name="semianual[]" value="' . $Target  .  '" id="semi_targetc' . $opstart_year .  $opid . '" >
                            Target Ceiling <span id="semi_targetmsg' . $opstart_year .  $opid . '" style="color:red"> ('.number_format($Target).''. $opunit.')</span>  
                        </th>';   
                        $containerTH2 .= '
                        <th width="300px">
                            Semi 2 Target
                        </th>'; 
                        $containerTB .= '
                        <td>
                            <input type="number" name="target' . $opstart_year .$opid.'[]" id="semi_target' . $opstart_year . $opid. $random_number . '" onchange="semi('.$opstart_year .',' . $opid.','.$random_number.')" onkeyup="semi('.$opstart_year .',' . $opid.','.$random_number.')" placeholder="Enter '.$opunit.'" class="form-control semi' . $opstart_year  . $opid .' target_value'.$opstart_year . $opid.'" required>
                        </td>'; 
                    $semi = $semi - 1;  
                } 
            }else{ 
                if ($semi > 2) {
                    $containerTH .= '
                    <th colspan="2">' . $opstart_year .  "/" . $startyear . ' 
                        <input type="hidden" name="target_year'.$opid .'[]" id="target_year'.$opid . $opstart_year.'" value="' . $opstart_year . '" class="t_year'.$opid .'">
                        <input type="hidden" name="semianual[]" value="' . $Target  .  '" id="semi_targetc' . $opstart_year .  $opid . '" >
                        Target Ceiling <span id="semi_targetmsg' . $opstart_year .  $opid . '" style="color:red"> ('.number_format($Target).''. $opunit.')</span>  
                    </th>';
                    for ($j = 0; $j < 2; $j++) {
                        $k = $j + 1;
                        $containerTH2 .= '
                        <th width="300px">
                            Semi ' .  $k . ' Target
                        </th>';
                        $containerTB  .= '
                        <td>
                            <input type="number" name="target' . $opstart_year .$opid.'[]" id="semi_target' . $opstart_year . $opid. $random_number . '" onchange="semi('.$opstart_year .',' . $opid.','.$random_number.')" onkeyup="semi('.$opstart_year .',' . $opid.','.$random_number.')" placeholder="Enter '.$opunit.'" class="form-control semi' . $opstart_year  . $opid .' target_value'.$opstart_year . $opid.'" required>
                        </td>';
                    }
                }else{
                    $containerTH .= '
                    <th colspan="'.$semi.'">' . $opstart_year .  "/" . $startyear . ' 
                        <input type="hidden" name="target_year'.$opid .'[]" id="target_year'.$opid . $opstart_year.'" value="' . $opstart_year . '" class="t_year'.$opid .'">
                        <input type="hidden" name="semianual[]" value="' . $Target  .  '" id="semi_targetc' . $opstart_year .  $opid . '" >
                        Target Ceiling <span id="semi_targetmsg' . $opstart_year .  $opid . '" style="color:red"> ('.number_format($Target).''. $opunit.')</span>  
                    </th>';                

                    for ($j = 0; $j < $semi; $j++) {
                        $k = $j + 1;
                        $containerTH2 .=
                            '<th width="300px"> Semi ' .  $k .
                            'Target</th>';
                        $containerTB  .= '
                        <td>
                            <input type="number" name="target' . $opstart_year .$opid.'[]" id="semi_target' . $opstart_year . $opid. $random_number . '" onchange="semi('.$opstart_year .',' . $opid.','.$random_number.')" onkeyup="semi('.$opstart_year .',' . $opid.','.$random_number.')" placeholder="Enter '.$opunit.'" class="form-control semi' . $opstart_year  . $opid .' target_value'.$opstart_year . $opid.'" required>
                        </td>';
                    }
                    $semi = $semi - 2;
                }
            }
            $opstart_year = $opstart_year + 1;
            $startyear = $startyear + 1;
        }

        $data = '
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="targets" style="width:100%">
                <thead>
                    <tr>
                        ' . $containerTH . '
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        ' . $containerTH2 .'
                    </tr>
                    <tr>
                        '. $containerTB . '
                    </tr>
                </tbody>
            </table>
        </div>
        ';
        echo $data;
    }else if($level ==4){
		//echo $duration."<br>";
        $durationinmonths = floor($duration / 30.4);
        $remainingdays = $duration % 30.4; 
        if ($remainingdays > 5) {
            $durationinmonths = $durationinmonths + 1;
        }
 
        $quaters = floor($durationinmonths / 3);
        if ($quaters == 0) {
            $quaters = $quaters + 1;
        }else{
            $remainderq = $durationinmonths % 3; 
            if($remainderq > 0){
                $quaters =$quaters +  1;
            }
        }
		
		
        $noofyears = $quaters / 4;
        $startyear = $opstart_year + 1;
        $counter = 0;
        $containerTH ='';
        $containerTH2 ='';
        $containerTB =''; 

        for ($rowno = 0; $rowno < $noofyears; $rowno++) {
            $query_projYear = $db->prepare("SELECT * FROM tbl_project_output_details WHERE projid = '$projid' and projoutputid = '$opid' AND year='$opstart_year'");
            $query_projYear->execute();
            $rows_OutpuprojYear = $query_projYear->rowCount();
            $row_projYear =  $query_projYear->fetch();
            $Target =  $row_projYear['target'];
            $counter++;

            if($rowno == 0){	
                $opstart_syear = floor((12 - $index)/3); //quarters in a year 
                $remainder = (12 - $index) % 3;

                if($opstart_syear == 0){
                    $opstart_syear +=1;
                } else{
                    if($remainder > 0){
                        $opstart_syear +=1;
                    }
                }   
    
                if($opstart_syear == 4){
					$qp = 4;
					if($quaters < 4){
						$qp = $quaters;
					}  
					//echo "No of quaters = ".$quaters." and rowno = ".$rowno."<br>";  
                    $containerTH .= '
                    <th colspan="'.$qp.'">' . $opstart_year .  "/" . $startyear . ' 
                        <input type="hidden" name="target_year'.$opid .'[]" id="target_year'.$opid . $opstart_year.'" value="' . $opstart_year . '" class="t_year'.$opid .'">
                        <input type="hidden" name="quater_targetc[]" value="' . $Target  .  '" id="quarter_targetc' . $opstart_year .  $opid . '" >
                        Target Ceiling <span id="quarter_targetmsg' . $opstart_year .  $opid . '" style="color:red"> ('.number_format($Target).''. $opunit.')</span>  
                    </th>';
                    for ($j = 0; $j < $qp; $j++) {
                       $random_number =  rand(1, 20);

                        $k = $j + 1;
                        $containerTH2 .= '
                        <th width="300px">
                            Quarter ' .  $k . ' Target
                        </th>'; 
                        $containerTB  .= '
                        <td>
                            <input type="number" name="target' . $opstart_year .$opid.'[]" id="quarter_target' . $opstart_year . $opid . $random_number. '" onchange="quarter('.$opstart_year .",".$opid .",".$random_number.')" onkeyup="quarter('.$opstart_year .",".$opid .",".$random_number.')" placeholder="Enter '.$opunit.'"  class="form-control quarter'  . $opstart_year . $opid. ' target_value'.$opstart_year . $opid.'" required>
                        </td>';
                    }
                    $quaters = $quaters - $qp; 
                }else{  
					//echo "No of quaters != ".$quaters." and opstart_syear = ".$opstart_syear."<br>";
                    $containerTH .= '
                    <th colspan="'.$opstart_syear.'">' . $opstart_year .  "/" . $startyear . ' 
                        <input type="hidden" name="target_year'.$opid .'[]" id="target_year'.$opid . $opstart_year.'" value="' . $opstart_year . '" class="t_year'.$opid .'">
                        <input type="hidden" name="quater_targetc[]" value="' . $Target  .  '" id="quarter_targetc' . $opstart_year .  $opid . '" >
                        Target Ceiling <span id="quarter_targetmsg' . $opstart_year .  $opid . '" style="color:red"> ('.number_format($Target).''. $opunit.')</span> 
                    </th>';  
                    $pcounter =0;
                    if($opstart_syear ==1){
                        $pcounter =3; 
                    }else if($opstart_syear == 2){
                        $pcounter =2; 
                    }else if($opstart_syear ==3){
                        $pcounter =1; 
                    }

                    for ($j = 0; $j < $opstart_syear; $j++) {
                        $pcounter++; 
                        $k = $j + 1;
                       $random_number =  rand(20, 40);

                        $containerTH2 .=
                            '<th width="300px"> Quarter ' .  $pcounter .
                            ' Target</th>';
                        $containerTB  .= '
                        <td>
                            <input type="number" name="target' . $opstart_year .$opid.'[]" id="quarter_target' . $opstart_year . $opid . $random_number. '" onchange="quarter('.$opstart_year .",".$opid .",".$random_number.')" onkeyup="quarter('.$opstart_year .",".$opid .",".$random_number.')" placeholder="Enter '.$opunit.'"  class="form-control quarter'  . $opstart_year . $opid. ' target_value'.$opstart_year . $opid.'" required>
                        </td>';
                    }

                    $quaters = $quaters - $opstart_syear;   
                } 
            }else{  
				//echo "No of quaters = ".$quaters." and rowno = ".$rowno."<br>";
                if ($quaters > 4) {
                    $containerTH .= '
                    <th colspan="4">' . $opstart_year .  "/" . $startyear . ' 
                        <input type="hidden" name="target_year'.$opid .'[]" id="target_year'.$opid . $opstart_year.'" value="' . $opstart_year . '" class="t_year'.$opid .'">
                        <input type="hidden" name="quater_targetc[]" value="' . $Target  .  '" id="quarter_targetc' . $opstart_year .  $opid . '" >
                        Target Ceiling <span id="quarter_targetmsg' . $opstart_year .  $opid . '" style="color:red"> ('.number_format($Target).''. $opunit.')</span> 
                    </th>'; 
                    for ($j = 0; $j < 4; $j++) {
                        $k = $j + 1;
                       $random_number =  rand(20, 40);
                        $containerTH2 .= '
                        <th width="300px">
                            Quarter ' .  $k . ' Target
                        </th>';
                        $containerTB  .= '
                        <td>
                            <input type="number" name="target' . $opstart_year .$opid.'[]" id="quarter_target' . $opstart_year . $opid . $random_number. '" onchange="quarter('.$opstart_year .",".$opid .",".$random_number.')" onkeyup="quarter('.$opstart_year .",".$opid .",".$random_number.')" placeholder="Enter '.$opunit.'"  class="form-control quarter'  . $opstart_year . $opid. ' target_value'.$opstart_year . $opid.'" required>
                        </td>';
                    }
                }else{
                    $containerTH .= '
                    <th colspan="'.$quaters.'">' . $opstart_year .  "/" . $startyear . ' 
                        <input type="hidden" name="target_year'.$opid .'[]" id="target_year'.$opid . $opstart_year.'" value="' . $opstart_year . '" class="t_year'.$opid .'">
                        <input type="hidden" name="quater_targetc[]" value="' . $Target  .  '" id="quarter_targetc' . $opstart_year .  $opid . '" >
                        Target Ceiling <span id="quarter_targetmsg' . $opstart_year .  $opid . '" style="color:red"> ('.number_format($Target).''. $opunit.')</span> 
                    </th>'; 
                    for ($j = 0; $j < $quaters; $j++) {
                        $k = $j + 1;
                       $random_number =  rand(60, 100);
                        $containerTH2 .=
                            '<th width="300px"> Quarter ' .  $k .
                            ' Target</th>';
                        $containerTB  .= '
                        <td>
                            <input type="number" name="target' . $opstart_year .$opid.'[]" id="quarter_target' . $opstart_year . $opid . $random_number. '" onchange="quarter('.$opstart_year .",".$opid .",".$random_number.')" onkeyup="quarter('.$opstart_year .",".$opid .",".$random_number.')" placeholder="Enter '.$opunit.'"  class="form-control quarter'  . $opstart_year . $opid. ' target_value'.$opstart_year . $opid.'" required>
                        </td>';
                    }
                }
                $quaters = $quaters - 4; 
            }
            $opstart_year = $opstart_year + 1;
            $startyear = $startyear + 1;
        }

        $data = '
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="targets" style="width:100%">
                <thead>
                    <tr>
                        ' . $containerTH . '
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        ' . $containerTH2 .'
                    </tr>
                    <tr>
                        '. $containerTB . '
                    </tr>
                </tbody>
            </table>
        </div>
        ';
        echo $data;
    }else if($level ==3){ 
        $yearsToCheck = range($opstart_year, $opend_year);
        $leap =[];
        foreach ($yearsToCheck as $year) {
            $isLeapYear = (bool) date('L', strtotime("$year-01-01"));
            if($isLeapYear === true)  $leap[] = $year;
        }  

        $number =  count($leap); 
        $duration -= $number; 
 

        $months = floor($duration / 30.42); 
        $remainingdays = $duration % 30.42;
          

        if ($remainingdays > 0) {
            $months = $months + 1;
        } 
 

        // // $noofyears = $months / 12;   

        $noofyears =$opend_year - $opstart_year;

        $startyear = $opstart_year + 1;  

        $smonth  =date('m', strtotime($sdate));

        if($smonth < 7 ){
            $noofyears = ($opend_year - $opstart_year)  + 1;  
            $opstart_year -=1;
            $startyear = $opstart_year + 1;
        } 

        $emonth  =date('m', strtotime($edate));
        if($emonth > 6 ){
            $noofyears = $opend_year - $opstart_year + 1;   
        } 
           
        $counter = 0;
        $containerTH ='';
        $containerTH2 ='';
        $containerTB =''; 
        for ($rowno = 0; $rowno < $noofyears; $rowno++) {
            $query_projYear = $db->prepare("SELECT * FROM tbl_project_output_details WHERE projid = :projid and projoutputid = :opid AND year=:opstartyear");
            $query_projYear->execute(array(":projid" => $projid, ":opid" => $opid, ":opstartyear" => $opstart_year));
            $rows_OutpuprojYear = $query_projYear->rowCount();
            $row_projYear =  $query_projYear->fetch();
            $Target =  $row_projYear['target']; 
            $counter++;

            if($rowno == 0){
                $opstart_syear = 12 - $index;  
                $pm = $index -1;

                if($months >= 12){
                    $containerTH .= '
                    <th colspan="'.$opstart_syear.'">' . $opstart_year .  "/" . $startyear . '
                        <input type="hidden" name="target_year'.$opid .'[]" id="target_year'.$opid . $opstart_year.'" value="' . $opstart_year . '" class="t_year'.$opid .'">
                        <input type="hidden" name="month_targetc[]" value="' . $Target  .  '" id="month_targetc' . $opstart_year .  $opid . '" >
                        Target Ceiling <span id="month_targetmsg' . $opstart_year .  $opid . '" style="color:red"> ('.number_format(0).''. $opunit.')</span>  
                    </th>'; 
                    for ($j = 0; $j < $opstart_syear; $j++) {
                        $k = $j + 1;  
                        $pm++; 
                       $random_number =  rand(20, 40);

                        $containerTH2 .=
                            '<th width="300px"> ' .  $month_arr[$pm] .
                            ' Target</th>';
                        $containerTB  .= '
                        <td>
                            <input type="number" name="target' . $opstart_year .$opid.'[]" id="month_target' . $opstart_year . $opid. $random_number. '" onchange="month(' . $opstart_year . "," . $opid . ",". $random_number. ')" onkeyup="month(' . $opstart_year . "," . $opid . ",". $random_number. ')" placeholder="Enter '.$opunit.'"  class="form-control month'  . $opstart_year . $opid . ' target_value'.$opstart_year . $opid.'" required>
                        </td>';
                    } 
                    $months = $months - $opstart_syear; 
                }else{ 
                    if($months < $opstart_syear ){ 
                        $containerTH .= '
                        <th colspan="'.$months.'">' . $opstart_year .  "/" . $startyear . ' 
                        <input type="hidden" name="target_year'.$opid .'[]" id="target_year'.$opid . $opstart_year.'" value="' . $opstart_year . '" class="t_year'.$opid .'">
                        <input type="hidden" name="month_targetc[]" value="' . $Target  .  '" id="month_targetc' . $opstart_year .  $opid . '" >
                            Target Ceiling <span id="month_targetmsg' . $opstart_year .  $opid . '" style="color:red"> ('.number_format(0).''. $opunit.')</span>  
                        </th>';  

                        for ($j = 0; $j < $months; $j++) {
                            $k = $j + 1; 
                            $random_number =  rand(100, 140);
                            $containerTH2 .=
                                '<th width="300px"> Month ' .  $k .
                                ' Target&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>';
                            $containerTB  .= '
                            <td>
                                <input type="number" name="target' . $opstart_year .$opid.'[]" id="month_target' . $opstart_year . $opid. $random_number. '" onchange="month(' . $opstart_year . "," . $opid . ",". $random_number. ')" onkeyup="month(' . $opstart_year . "," . $opid . ",". $random_number. ')" placeholder="Enter '.$opunit.'"  class="form-control month'  . $opstart_year . $opid . ' target_value'.$opstart_year . $opid.'" required>
                            </td>';
                        } 
                        $months -=$months; 
                    }else{ 
                        $containerTH .= '
                        <th colspan="'.$months.'">' . $opstart_year .  "/" . $startyear . ' 
                            <input type="hidden" name="target_year'.$opid .'[]" id="target_year'.$opid . $opstart_year.'" value="' . $opstart_year . '" class="t_year'.$opid .'">
                            <input type="hidden" name="month_targetc[]" value="' . $Target  .  '" id="month_targetc' . $opstart_year .  $opid . '" >
                            Target Ceiling <span id="month_targetmsg' . $opstart_year .  $opid . '" style="color:red"> ('.number_format(0).''. $opunit.')</span>  
                        </th>'; 
                        for ($j = 0; $j < $opstart_syear; $j++) {
                            $k = $j + 1; 
                            $random_number =  rand(10, 50);
                            $containerTH2 .=
                                '<th width="300px"> Month ' .  $k .
                                ' Target</th>';
                            $containerTB  .= '
                            <td>
                                <input type="number" name="target' . $opstart_year .$opid.'[]" id="month_target' . $opstart_year . $opid. $random_number. '" onchange="month(' . $opstart_year . "," . $opid . ",". $random_number. ')" onkeyup="month(' . $opstart_year . "," . $opid . ",". $random_number. ')" placeholder="Enter '.$opunit.'"  class="form-control month'  . $opstart_year . $opid . ' target_value'.$opstart_year . $opid.'" required>
                            </td>';
                        } 
                        $months -=$opstart_syear;  
                    }
                }   
            }else{   
                if ($months >= 12) {
                    $containerTH .= '
                    <th colspan="12">' . $opstart_year .  "/" . $startyear . ' 
                    <input type="hidden" name="target_year'.$opid .'[]" id="target_year'.$opid . $opstart_year.'" value="' . $opstart_year . '" class="t_year'.$opid .'">
                    <input type="hidden" name="month_targetc[]" value="' . $Target  .  '" id="month_targetc' . $opstart_year .  $opid . '" >
                        Target Ceiling <span id="month_targetmsg' . $opstart_year .  $opid . '" style="color:red"> ('.number_format(0).''. $opunit.')</span>  
                    </th>'; 
                    $pm=-1;
                    for ($j = 0; $j < 12; $j++) {
                        $k = $j + 1;
                        $pm++;
                        $random_number =  rand(150, 250);
                        $containerTH2 .= '
                        <th width="300px">
                             ' .  $month_arr[$pm] . ' Target
                        </th>';
                        $containerTB  .= '
                        <td>
                            <input type="number" name="target' . $opstart_year .$opid.'[]" id="month_target' . $opstart_year . $opid. $random_number. '" onchange="month(' . $opstart_year . "," . $opid . ",". $random_number. ')" onkeyup="month(' . $opstart_year . "," . $opid . ",". $random_number. ')" placeholder="Enter '.$opunit.'"  class="form-control month'  . $opstart_year . $opid . ' target_value'.$opstart_year . $opid.'" required>
                        </td>';
                    } 
                    $months = $months - 12;  
                }else{  
                    $containerTH .= '
                    <th colspan="'.$months.'">' . $opstart_year .  "/" . $startyear . ' 
                        <input type="hidden" name="target_year'.$opid .'[]" id="target_year'.$opid . $opstart_year.'" value="' . $opstart_year . '" class="t_year'.$opid .'">
                        <input type="hidden" name="month_targetc[]" value="' . $Target  .  '" id="month_targetc' . $opstart_year .  $opid . '" >
                        Target Ceiling <span id="month_targetmsg' . $opstart_year .  $opid . '" style="color:red"> ('.number_format(0).''. $opunit.')</span>  
                    </th>';                 
                    $pm =-1;
                    for ($j = 0; $j < $months; $j++) {
                        $k = $j + 1; 
                        $pm++;
                        $random_number =  rand(250, 300);

                        $containerTH2 .=
                            '<th width="300px">  ' .  $month_arr[$pm] .
                            ' Target</th>';

                        $containerTB  .= '
                        <td>
                            <input type="number" name="target' . $opstart_year .$opid.'[]" id="month_target' . $opstart_year . $opid. $random_number. '" onchange="month(' . $opstart_year . "," . $opid . ",". $random_number. ')" onkeyup="month(' . $opstart_year . "," . $opid . ",". $random_number. ')" placeholder="Enter '.$opunit.'"  class="form-control month'  . $opstart_year . $opid . ' target_value'.$opstart_year . $opid.'" required>
                        </td>';
                    }
                    $months = $months - $months; 
                } 
            }

            $opstart_year = $opstart_year + 1;
            $startyear = $startyear + 1;
        } 
        $data = '
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="targets" style="width:100%">
                <thead>
                    <tr>
                        ' . $containerTH . '
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        ' . $containerTH2 .'
                    </tr>
                    <tr>
                        '. $containerTB . '
                    </tr>
                </tbody>
            </table>
        </div>
        ';
        echo $data;
    }else if($level ==2){
        $duration =100;
        $weeks = floor($duration / 7);
        $remainingdays = $duration % 7;

        if ($remainingdays > 0) {
            $weeks = $weeks + 1;
        }
   
        $noofyears = $weeks / 52.14285714;
        $startyear = $opstart_year + 1;
        $counter = 0;
        $containerTH ='';
        $containerTH2 ='';
        $containerTB ='';

        for ($rowno = 0; $rowno < $noofyears; $rowno++) {
            $query_projYear = $db->prepare("SELECT * FROM tbl_project_output_details WHERE projid = :projid and projoutputid = :opid AND year=:opstartyear");
            $query_projYear->execute(array(":projid" => $projid, ":opid" => $opid, ":opstartyear" => $opstart_year));
            $rows_OutpuprojYear = $query_projYear->rowCount();
            $row_projYear =  $query_projYear->fetch();
            $Target =  $row_projYear['target'];

            $counter++;
            if ($weeks > 52.14285714) {
                $containerTH .= '
                <th colspan="52.14285714">' . $opstart_year .  "/" . $startyear . ' 
                    <input type="hidden" name="target_year'.$opid .'[]" id="target_year'.$opid . $opstart_year.'" value="' . $opstart_year . '" class="t_year'.$opid .'">
                    <input type="hidden" name="week_targetc[]" value="' . $Target  .  '" id="week_targetc' . $opstart_year .  $opid . '" >
                    Target Ceiling <span id="week_targetmsg' . $opstart_year .  $opid . '" style="color:red"> ('.number_format($Target).''. $opunit.')</span>  
                </th>';
                
                for ($j = 0; $j < 52.14285714; $j++) {
                    $k = $j + 1;
                    $random_number =  rand(300, 500);
                    $containerTH2 .= '
                    <th width="300px">
                        Week ' .  $k . ' Target &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>';
                    $containerTB  .= '
                    <td>
                        <input type="number" name="target' . $opstart_year . $opid .'[]" id="week_target' . $opstart_year . $opid. $random_number.'" onkeyup="week('. $opstart_year .",".$opid.",".$random_number.')" onchange="week('. $opstart_year .",".$opid.",".$random_number.')" placeholder="Enter '.$opunit.'"  class="form-control week' . $opstart_year . $opid . ' target_value'.$opstart_year . $opid.'"  required>
                    </td>';
                }
            }else{
                $containerTH .= '
                <th colspan="'.$weeks.'">' . $opstart_year .  "/" . $startyear . '
                    <input type="hidden" name="target_year'.$opid .'[]" id="target_year'.$opid . $opstart_year.'" value="' . $opstart_year . '" class="t_year'.$opid .'">
                    <input type="hidden" name="week_targetc[]" value="' . $Target  .  '" id="week_targetc' . $opstart_year .  $opid . '" >
                    Target Ceiling <span id="week_targetmsg' . $opstart_year .  $opid . '" style="color:red"> ('.number_format($Target).''. $opunit.')</span>  
                </th>';                 
                for ($j = 0; $j < $weeks; $j++) {
                    $k = $j + 1; 
                    $random_number =  rand(500, 600);

                    $containerTH2 .=
                        '<th width="300px"> Week ' .  $k .
                        ' Target&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>'; 
                        
                    $containerTB  .= '
                    <td>
                        <input type="number" name="target' . $opstart_year .$opid.'[]" id="week_target' . $opstart_year . $opid. $random_number.'" onkeyup="week('. $opstart_year .",".$opid.",".$random_number.')" onchange="week('. $opstart_year .",".$opid.",".$random_number.')" placeholder="Enter '.$opunit.'"  class="form-control week' . $opstart_year . $opid . ' target_value'.$opstart_year . $opid.'"  required>
                    </td>';
                }
            }
            $weeks = $weeks - 52.14285714;
            $opstart_year = $opstart_year + 1;
            $startyear = $startyear + 1;
        }

        $data = '
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="targets" style="width:100%">
                <thead>
                    <tr>
                        ' . $containerTH . '
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        ' . $containerTH2 .'
                    </tr>
                    <tr>
                        '. $containerTB . '
                    </tr>
                </tbody>
            </table>
        </div>';
        echo $data;
    } else if($level ==1){ 
        $noofyears = $duration / 365; 
        $startyear = $opstart_year + 1;
        $counter = 0;
        $containerTH ='';
        $containerTH2 ='';
        $containerTB ='';

        for ($rowno = 0; $rowno < $noofyears; $rowno++) {
            $query_projYear = $db->prepare("SELECT * FROM tbl_project_output_details WHERE projid = :projid and projoutputid = :opid AND year=:opstartyear");
            $query_projYear->execute(array(":projid" => $projid, ":opid" => $opid, ":opstartyear" => $opstart_year));
            $rows_OutpuprojYear = $query_projYear->rowCount();
            $row_projYear =  $query_projYear->fetch();
            $Target =  $row_projYear['target'];

            $counter++;
            if ($duration > 365) {
                $containerTH .= '
                <th colspan="365">' . $opstart_year .  "/" . $startyear . '
                    <input type="hidden" name="target_year'.$opid .'[]" id="target_year'.$opid . $opstart_year.'" value="' . $opstart_year . '" class="t_year'.$opid .'">
            
                    <input type="hidden" name="day_targetc[]" value="' . $Target  .  '" id="day_targetc' . $opstart_year .  $opid . '" >
                    Target Ceiling <span id="day_targetmsg' . $opstart_year .  $opid . '" style="color:red"> ('.number_format($Target).''. $opunit.')</span>  
                </th>';

                for ($j = 0; $j < 365; $j++) {
                    $k = $j + 1;
                    $random_number =  rand(300, 500);

                    $containerTH2 .= '
                    <th width="300px">
                        Day ' .  $k . ' Target &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>';
                    $containerTB  .= '
                    <td>
                        <input type="number" name="target' . $opstart_year .$opid.'[]" id="day_target' . $opstart_year .$opid. $random_number .'" onkeyup="day('. $opstart_year .",".$opid.",".$random_number.')" onchange="day('. $opstart_year .",".$opid.",".$random_number.')" placeholder="Enter '.$opunit.'" class="form-control day' . $opstart_year . $opid . ' target_value'.$opstart_year . $opid.'"  required>
                    </td>';
                }
            }else{
                $containerTH .= '
                <th colspan="'.$duration.'">' . $opstart_year .  "/" . $startyear . ' 
                    <input type="hidden" name="target_year'.$opid .'[]" id="target_year'.$opid . $opstart_year.'" value="' . $opstart_year . '" class="t_year'.$opid .'">
                    <input type="hidden" name="day_targetc[]" value="' . $Target  .  '" id="day_targetc' . $opstart_year .  $opid . '" >
                    Target Ceiling <span id="day_targetmsg' . $opstart_year .  $opid . '" style="color:red"> ('.number_format($Target).''. $opunit.')</span>  
                </th>';

                for ($j = 0; $j < $duration; $j++) {
                    $k = $j + 1; 
                    $random_number =  rand(500, 700);
                    $containerTH2 .=
                        '<th width="300px"> Day ' .  $k .
                        ' Target &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>';
                    $containerTB  .= '
                    <td>
                        <input type="number" name="target' . $opstart_year . $opid .'[]" id="day_target' . $opstart_year .$opid. $random_number .'" onkeyup="day('. $opstart_year. "," . $opid . ",".$random_number.')" onchange="day('. $opstart_year .",".$opid.",".$random_number.')" placeholder="Enter '.$opunit.'" class="form-control day' . $opstart_year . $opid . ' target_value'.$opstart_year . $opid.'"  required>
                    </td>';
                }
            }

            $duration = $duration - 365;
            $opstart_year = $opstart_year + 1;
            $startyear = $startyear + 1;
        }
        $data = '
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="targets" style="width:100%">
                <thead>
                    <tr>
                        ' . $containerTH . '
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        ' . $containerTH2 .'
                    </tr>
                    <tr>
                        '. $containerTB . '
                    </tr>
                </tbody>
            </table>
        </div>';
        echo $data;
    }                    
} 

if (isset($_POST["deleteItem"])) {
    $itemid = $_POST['itemId'];

    $deleteQueryR = $db->prepare("DELETE FROM `tbl_workplan_targets` WHERE projid=:itemid");
    $resultsR = $deleteQueryR->execute(array(':itemid' => $itemid));

    if ($results === TRUE) {
        if ($results === TRUE) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Deleted";
        } else {
            $valid['success'] = false;
            $valid['messages'] = "Error while deletng the record!!";
        }
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while deletng the record!!";
    }

    echo json_encode($valid);
}

} catch (PDOException $ex) {
    $result = "An error occurred: " . $ex->getMessage();
    print($result);
}