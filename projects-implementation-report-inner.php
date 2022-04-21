<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <div class="block-header" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; background-color:#607D8B; color:#FFF">
                    <h4 class="contentheader"><i class="fa fa-line-chart" aria-hidden="true"></i> Projects Implementation Reports
                    </h4>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="manageItemTable">
                        <thead>                  
                            <tr>
                                <th width="5%">#</th>
                                <th width="28%">Project Name</th>
                                <th width="8%">Status</th>
                                <th width="10%">Progress</th>
                                <th width="10%">Cost</th>
                                <th width="8%">Location</th>
                                <th width="8%">Start/End Date</th>
                                <th width="8%">Report</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php              
                                $sql = $db->prepare("SELECT * FROM `tbl_projects` WHERE projstatus > 9 ORDER BY `projid` ASC");
                                $sql->execute();
                                $rows_count = $sql->rowCount(); 
                                if ($rows_count > 0) {
                                    // $row = $result->fetch_array();
                                    $active = "";
                                    $sn = 0;
                                    while ($row = $sql->fetch()) {
                                        $sn++;
                                        $itemId = $row['projid'];

                                        $query_rsBudget =  $db->prepare("SELECT SUM(budget) as budget FROM tbl_project_details WHERE projid ='$itemId'");
                                        $query_rsBudget->execute();
                                        $row_rsBudget = $query_rsBudget->fetch();
                                        $totalRows_rsBudget = $query_rsBudget->rowCount();
                                        $projbudget = $row_rsBudget['budget'];

                                        $projname = $row["projname"];
                                        $projcost = $row["projcost"];
                                        $budget = number_format($projbudget, 2);
                                        $progid = $row["progid"];
                                        $projstatus = $row["projstatus"];
                                        $projstartdate = $row["projstartdate"];
                                        $projenddate = $row["projenddate"];
                                        $projstate = explode(",",$row['projstate']);
                                
                                        $status = $db->prepare("SELECT * FROM `tbl_status` WHERE statusid=:statusid LIMIT 1");
                                        $status->execute(array(":statusid" => $projstatus));
                                        $rowstatus = $status->fetch();
                                        $status = $rowstatus["statusname"]; 
                                        $progress = 45;

                                        $states = array();
                                        for($i=0; $i<   count($projstate); $i++){
                                            $state = $db->prepare("SELECT * FROM `tbl_state` WHERE id=:stateid LIMIT 1");
                                            $state->execute(array(":stateid" => $projstate[$i]));
                                            $rowstate = $state->fetch();
                                            $state = $rowstate["state"]; 
                                            array_push($states, $state);
                                        }

                                        

                                        // project percentage progress
                                        $query_rsMlsProg =  $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid = :projid");
                                        $query_rsMlsProg->execute(array(":projid" => $itemId));	
                                        $row_rsMlsProg = $query_rsMlsProg->fetch();
                                        $percent2 = 0; 
                                        if($row_rsMlsProg["mlprogress"] >0 && $row_rsMlsProg["nmb"] > 0 ){
                                            $prjprogress = $row_rsMlsProg["mlprogress"]/$row_rsMlsProg["nmb"];
                                            $percent2 = round($prjprogress,2);
                                        }
                                            

                                        $active = '';
                                        if($projstatus == 3){
                                            $active = '<button type="button" class="btn bg-yellow waves-effect" style="width:100%">'.$status. '</button>';
                                        }else if($projstatus == 4){
                                            $active = '<button type="button" class="btn btn-primary waves-effect" style="width:100%">'.$status.'</button>';
                                        }else if($projstatus == 11){
                                            $active = '<button type="button" class="btn bg-red waves-effect" style="width:100%">'.$status. '</button>';
                                        }else if($projstatus == 5){
                                            $active = '<button type="button" class="btn btn-success waves-effect" style="width:100%">'.$status. '</button>';
                                        }else if($projstatus == 1){
                                            $active = '<button type="button" class="btn bg-grey waves-effect" style="width:100%">'.$status. '</button>';
                                        }else if($projstatus == 2){
                                            $active = '<button type="button" class="btn bg-brown waves-effect" style="width:100%">'.$status. '</button>';
                                        }else if($projstatus == 6){
                                            $active = '<button type="button" class="btn bg-pink waves-effect" style="width:100%">'.$status. '</button>';
                                        }
                                        $percent ='';
                                        if ($percent2 < 100) {
                                            $percent = '
                                            <div class="progress" style="height:20px; font-size:10px; color:black">
                                                <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="'.$percent2.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$percent2.'%; height:20px; font-size:10px; color:black">
                                                    '.$percent2.'%
                                                </div>
                                            </div>';
                                        } 
                                        elseif ($percent2 ==100){
                                            $percent = '
                                            <div class="progress" style="height:20px; font-size:10px; color:black">
                                                <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="'.$percent2.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$percent2.'%; height:20px; font-size:10px; color:black">
                                                '.$percent2.'%                                   
                                                </div>
                                            </div>';
                                        }
                                        
                                        echo '<tr>
                                            <td>'.$sn.'</td>
                                            <td>'.$projname.'</td>
                                            <td>'.$active.'</td>
                                            <td>'.$percent.'</td>
                                            <td>'.number_format($projcost, 2).'</td>
                                            <td>'.implode(" | ", $states).'</td>
                                            <td>'.$projstartdate.'/'.$projenddate.'</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Options 
                                                            <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a type="button" href="project-general-report.php?projid='.$itemId.'"> 
                                                                <i class="fa fa-file"></i> General Report
                                                            </a>
                                                        </li>';
                                                        /* <li>
                                                            <a type="button" href="proj-indicator-tracking-table.php?projid='.$itemId.'"> 
                                                                <i class="fa fa-line-chart"></i> Indicator Tracking
                                                            </a>
                                                        </li>
                                                        echo '
														<li>
                                                            <a type="button" href="project-success-stories.php?projid='.$itemId.'"> 
                                                                <i class="fa fa-users"></i> Success Stories
                                                            </a>
                                                        </li>
														<li>
                                                            <a type="button" href="proj-indicator-tracking-table.php?projid='.$itemId.'"> 
                                                                <i class="fa fa-balance-scale"></i> Evaluation Report
                                                            </a>
                                                        </li>'; */
                                                   echo ' </ul> 
                                                </div>
                                            </td>
                                        </tr>';
                                        // $output['data'][] = array($sn,$projname,$progname,$budget,$projYear,$active,$button);
                                    } // /while 

                                } // if num_rows
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
