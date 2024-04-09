<?php 
try {
    //code...

?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <div class="block-header" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; padding-right:15px; background-color:#607D8B; color:#FFF">
                    <h4 class="contentheader"><i class="fa fa-arrows" aria-hidden="true"></i> Project Indicators Performance
                        <button  class="btn btn-primary pull-right" onclick="goBack()" type="button">Go Back</button>
                    </h4>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example " id="manageItemTable">
                        <thead>                  
                            <tr style="background-color:#0b548f; color:#FFF"> 
                                 <th rowspan="2" style='width:40%'>Result Level</th>
                                 <th rowspan="2" style='width:40%'>Indicator</th>
                                 <th rowspan="2" style='width:10%'>Baseline</th>
                                 <?php 
                                    $header ='';
                                    $header2 ='';
                                    $perc = 50 / $years ;
                                    for($i=0;  $i<$years; $i++){
                                        $endyear = $projstartyear + 1; 
                                        $fscyear = $projstartyear ."/". $endyear; 
                                        $header .= "<th colspan='3' rowspan=''>{$fscyear}</th>"; 

                                        $arr = array("Target", "Achieved", "Rate");
                                        for($j=0; $j < 3; $j++){
                                            $header2 .= "<th>{$arr[$j]}</th>"; 
                                        }                                       
                                        $projstartyear++;
                                    } 
                                    echo $header;
                                 ?> 
                            </tr>
                            <tr>
                                    <?=$header2?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $typeindicator =array("Impact", "Outcome", "Output");
                        $nm =0; 
                        for($q=0; $q<3; $q++){
                            $nm++; 
                            ?>
                            <tr>                      
                                <th colspan="<?=1+($projstartyear*3)?>" style='width=100%'><?=$typeindicator[$q]?></th>
                            </tr>
                            <?php  
                            $query_rsindicators='';
                            if($q == 0){
                                $query_rsindicators = $db->prepare("SELECT indicator_name FROM tbl_project_expected_impact_details m 
                                INNER JOIN tbl_indicator i ON m.indid = i.indid 
                                WHERE m.projid='$projid' GROUP BY id");
                            }else if($q == 1){
                                $query_rsindicators = $db->prepare("SELECT indicator_name FROM tbl_projects p
                                INNER JOIN tbl_programs g ON g.progid = p.progid 
                                INNER JOIN tbl_indicator i ON i.indid=g.outcomeIndicator 
                                WHERE m.projid='$projid' GROUP BY projid");
                            }else if($q == 2){
                                $query_rsindicators = $db->prepare("SELECT indicator_name FROM tbl_project_details m 
                                INNER JOIN tbl_indicator i ON m.indicator = i.indid 
                                WHERE m.projid='$projid' GROUP BY id");
                            }
                           
                            $query_rsindicators->execute(); 
                            $row_rsindicators = $query_rsindicators->fetch();
                            $totalRows_rsindicators = $query_rsindicators->rowCount();
                            $sn =0; 
                            $baseline =0;
                            if($totalRows_rsindicators > 0){
                                do{
                                    $sn ++; 
                                    $indicator = $row_rsindicators['indicator_name']; 
                                    echo '
                                    <tr>
                                        <td>'.$nm . "." .$sn.'</td>   
                                        <td>'.$indicator.'</td>
                                        <td>'.$baseline.'</td>
                                        ';
                                        $body2 ='';
                                        for($i=0;  $i<$years; $i++){
                                            $endyear = $projstartyear + 1; 
                                            $fscyear = $projstartyear ."/". $endyear; 
                                            $arr = array("Target", "Achieved", "Rate");
                                            $query_rsoutputValues='';

                                            $sdate ='2020';
                                            $edate = '';
                                            if($q == 0){
                                                $query_rsoutputValues = $db->prepare("SELECT ((SUM(actualoutput) /d.total_target)*100) as Rate, g.output, actualoutput, total_target, indicator_name FROM tbl_monitoringoutput m 
                                                INNER JOIN tbl_project_details d ON d.id = m.opid 
                                                INNER JOIN tbl_progdetails g ON g.id = d.outputid 
                                                INNER JOIN tbl_indicator i ON i.indid = g.indicator 
                                                WHERE m.projid='$projid' GROUP BY opid  ");
                                            }else if($q == 1){
                                                $query_rsoutputValues = $db->prepare("SELECT ((SUM(actualoutput) /d.total_target)*100) as Rate, g.output, actualoutput, total_target, indicator_name FROM tbl_monitoringoutput m 
                                                INNER JOIN tbl_project_details d ON d.id = m.opid 
                                                INNER JOIN tbl_progdetails g ON g.id = d.outputid 
                                                INNER JOIN tbl_indicator i ON i.indid = g.indicator 
                                                WHERE m.projid='$projid' GROUP BY opid  ");
                                            }else if($q == 2){
                                                $query_rsoutputValues = $db->prepare("SELECT ((SUM(actualoutput) /d.total_target)*100) as Rate, g.output, actualoutput, total_target, indicator_name FROM tbl_monitoringoutput m 
                                                INNER JOIN tbl_project_details d ON d.id = m.opid 
                                                INNER JOIN tbl_progdetails g ON g.id = d.outputid 
                                                INNER JOIN tbl_indicator i ON i.indid = g.indicator 
                                                WHERE m.projid='$projid' GROUP BY opid  ");
                                            } 

                                            $query_rsoutputValues->execute(); 
                                            $row_rsoutputValues = $query_rsoutputValues->fetch();
                                            $totalRows_rsoutputValues = $query_rsoutputValues->rowCount();
                                            $rate ="N/A";
                                            $target ="N/A";
                                            if($q == 2){
                                                $rate = number_format($row_rsoutputValues['Rate']); 
                                                $target = number_format($row_rsoutputValues['total_target']); 
                                            }

                                            $achieved =number_format($row_rsoutputValues['actualoutput']);


                                            $body2 .= "<td>{$target}</td>"; 
                                            $body2 .= "<td>{$achieved}</td>"; 
                                            $body2 .= "<td>{$rate}</td>";  
                                                                               
                                            $projstartyear++;
                                        }        
                                        echo $body2;                                                                           
                                    echo '</tr>';  
                                }while($row_rsindicators = $query_rsindicators->fetch());
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>
