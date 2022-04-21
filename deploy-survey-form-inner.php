<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <div class="row clearfix">
                    <div class="col-md-12">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item list-group-item-action active">Form Name: <?=$form_name?> </li> 
                            <li class="list-group-item ">Change to be measured: <?=$indname?> </li> 
                            <li class="list-group-item ">Unit of Measure: <?=$unit?> </li> 
                            <li class="list-group-item ">Enumerator Type: <?=$enumeratortype?> </li> 
                        </ul>
                    </div>
                </div>
            </div>
            <div class="body">
                <div class="row clearfix">  
                    <div class="col-md-12" id="disaggregation_div">
                        <form id="addnewdata" method="POST" name="addnewdata" action="">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-plus-square" aria-hidden="true"></i> Add Location Disaggregations</legend>
                                <div class="col-md-12" id="outcome_direct_disagregation">
                                    <label class="control-label">Disaggregations *:</label>
                                    <div class="table-responsive"> 
                                        <table class="table table-bordered table-striped table-hover" id="direct_outcome_table" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th width="10%">#</th>  
                                                    <th width="30%"><?=$level3label?></th>
                                                    <th width="30%"><?=$level3label?> Disaggregations</th>  
                                                </tr>
                                            </thead>  
                                            <tbody id="">
                                                <?php 
                                                    $counter=0;
                                                    for($i=0; $i<count($level3); $i++){
                                                        $level3id = $level3[$i]; 
                                                        $counter++; 
                                                        $query_rsComm =  $db->prepare("SELECT id, state FROM tbl_state WHERE id=:level3");
                                                        $query_rsComm->execute(array(":level3"=>$level3id));
                                                        $row_rsComm = $query_rsComm->fetch();
                                                        $totalRows_rsComm = $query_rsComm->rowCount();
                                                        $state = $row_rsComm['state'];
                                                        ?>
                                                        <tr>
                                                            <td width="10%">
                                                                <?=$counter?>
                                                            </td>  
                                                            <td width="30%">
                                                                <?=$state?>
                                                                <input name="level3[]" type="hidden" id="level3" value="<?=$level3id;?>" />
                                                            </td>
                                                            <td width="30%">
                                                                <div class="form-input">
                                                                    <input type="text" name="disaggregations[]" id="disaggregations<?=$counter?>" placeholder="Enter" class="form-control" required>
                                                                </div>
                                                            </td>  
                                                        </tr>
                                                        <?php
                                                    }  
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div> 
                                <div class="row clearfix">                                 
                                    <div class="col-lg-12 col-md-12 col-sm-2 col-xs-2" align="center">
                                        <input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
                                        <input name="indid" type="hidden" id="indid" value="<?php echo $indid; ?>" /> 
                                        <input name="projid" type="hidden" id="projid" value="<?php echo $projid; ?>" /> 
                                        <input name="respondents_type" type="hidden" id="respondents_type" value="<?php echo $respondents_type; ?>" /> 
                                        <input name="form_id" type="hidden" id="form_id" value="<?=$formid?>" /> 
                                        <input type="hidden" name="insert" value="insert" />
                                        <div class="btn-group">
                                            <button name="button" type="submit" id="insert" class="btn bg-light-blue waves-effect waves-light" />
                                            Save
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    
                    <div class="col-md-12" id="enumerator_div">
                        <form id="addenumeratorbasefrm" method="POST" name="addenumeratorbasefrm" action="<?php echo $editFormAction; ?>" enctype="multipart/form-data" autocomplete="off">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-plus-square" aria-hidden="true"></i> Assign Enumerator</legend>
                                <div class="col-md-12" id="outcome_direct_disagregation">
                                    <label class="control-label">Locations *:</label>
                                    <div class="table-responsive"> 
                                        <table class="table table-bordered table-striped table-hover" id="direct_outcome_table" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th width="4%">#</th>  
                                                    <th width="32%"><?=$level3label?></th>
                                                    <th width="32%"><?=$ministrylabel?></th>
                                                    <th width="32%">Responsible</th>  
                                                </tr>
                                            </thead>  
                                            <tbody id="direct_outcome_table_body">
                                                <?php 
                                                    if(!$location){
                                                        $counter=0;
                                                        for($i=0; $i<count($level3); $i++){
                                                            $level3id = $level3[$i];
                                                            $counter++;
                                                            $query_rsComm =  $db->prepare("SELECT id, state FROM tbl_state WHERE id=:level3");
                                                            $query_rsComm->execute(array(":level3"=>$level3id));
                                                            $row_rsComm = $query_rsComm->fetch();
                                                            $totalRows_rsComm = $query_rsComm->rowCount();
                                                            $state = $row_rsComm['state'];
                                                            
                                                            ?>
                                                            <tr>
                                                                <td width="4%">
                                                                    <?=$counter?>
                                                                </td>  
                                                                <td width="32%">
                                                                    <?=$state?>
                                                                    <input name="level3[]" type="hidden" id="level3" value="<?=$level3id;?>" />
                                                                    <input name="indirectben[]" type="hidden" id="indirectben" value="" />
                                                                </td>  
                                                                <td width="32%">
                                                                    <select name="ministry" id="ministry<?=$counter?>" onchange="userMinistry(<?=$counter?>)"class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="false" required>
																		<option value="">.... Select <?=$ministrylabel?> ....</option>
																		<?php 
																			$query_ministry = $db->prepare("SELECT * FROM tbl_sectors WHERE parent = 0 and deleted = '0'");
																			$query_ministry->execute();
																			                                                            
																			while($row = $query_ministry->fetch()) {
																				$stid = $row['stid'];
																				$sector = $row['sector'];
																				
																				echo '<option value="' . $stid . '">' . $sector . '</option>'; 
																			}
																		?>
																	</select>
                                                                </td>
                                                                <td width="30%">
                                                                    <div class="form-line">
																		<?php 
                                                                        if($enumtype ==1){
                                                                            ?>
																			<select name="enumerators[]" class="form-control show-tick" id="inhouse<?=$counter?>" style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="true" required>
																				<option value="">.... Select <?=$ministrylabel?> first....</option>
																			</select>
                                                                            <?php 
                                                                        }else{
                                                                            ?>
																			<input type="email" name="enumerators[]" id="mail" placeholder="enter comma separated email addresses, if more than one" class="form-control" style="border:#CCC thin solid; border-radius: 5px" required>                                                                        
                                                                            <?php 
                                                                        }
																		?>
                                                                    </div>
                                                                </td>  
                                                            </tr>
                                                            <?php
                                                        } 
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>  
                                <div class="row clearfix">                                 
                                    <div class="col-lg-12 col-md-12 col-sm-2 col-xs-2" align="center">
                                        <input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
                                        <input name="indid" type="hidden" id="indid" value="<?php echo $indid; ?>" /> 
                                        <input name="projid" type="hidden" id="projid" value="<?php echo $projid; ?>" /> 
                                        <input name="form_id" type="hidden" id="form_id" value="<?=$formid?>" /> 
                                        <a href="project-survey" type="button" class="btn bg-orange"> Cancel </a>
                                        <input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="Submit" />
                                        <input type="hidden" name="MM_insert" value="addenumeratorbasefrme" />
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>