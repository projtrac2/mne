<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <?= $results ?>
                <div class="row clearfix">
                    <div class="col-md-12">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item list-group-item-action active"> <?= $form_name ?> </li>
                            <li class="list-group-item"><strong>Project Code: </strong> <?= $projcode ?> </li>
                            <li class="list-group-item"><strong>Project Name:</strong> <?= $projname ?> </li>
                            <li class="list-group-item"><strong><?=$level3label?>: </strong> <?= $state ?> </li>
                            <?php echo $locationName ?> 
                            <li class="list-group-item"><strong>Indicator: </strong> <?= $indicator_name ?> </li>
                            <li class="list-group-item"><strong>Unit of Measure: </strong> <?= $measurement_unit ?> </li>
                        </ul>
                    </div>
                </div>
            </div> 
            <div class="body">
                <div class="row clearfix">                    
                        <form action="" method="post" id="submitform">
                            <div class="col-md-12">
                                <fieldset class="scheduler-border row">
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Questions</legend>                                
									<?php 
                                    if($indicator_type == 1){
                                        $category =1;
                                        if($disaggregated == 1 ){ 
                                            ?>
                                            <div class="row clearfix">
                                                <div class="col-md-12">
                                                    <?php 
                                                        $tree = makeTree($indid, $category);  
                                                        printNode($tree[0], $category,$respondent, $placeholder); 
                                                    ?>
                                                </div>
                                            </div>
                                            <?php  
                                        }else{   
                                            ?>
                                            <div class="row clearfix">
                                                <div class="col-md-12">
                                                    <?php 
                                                        non_disaggregated($indid, $category,$respondent, $placeholder);  
                                                    ?>
                                                </div>
                                            </div>
                                            <?php 
                                        }
                                    }else if($indicator_type == 2){ 
							
                                        if($disaggregated == 1 ){                                              
                                            ?>
                                            <div class="row clearfix">
                                                <div class="col-md-12">
                                                    <h4>Direct Beneficiary</h4>
                                                    <?php 
                                                        $category =2;
                                                        $tree = makeTree($indid, $category);  
                                                        printNode($tree[0], $category,$respondent, $placeholder); 
                                                    ?>
                                                </div>
                                            </div>
											<?php 
											$category =3;
											$query = 'SELECT * FROM tbl_indicator_measurement_variables WHERE indicatorid =:indid and category=3';
											$query_rsindid = $db->prepare($query);
											$query_rsindid->execute(array(":indid"=>$indid));
											$row_rsindid = $query_rsindid->rowCount();
											if($row_rsindid > 0){
												?> 
												<div class="row clearfix">
													<div class="col-md-12">
														<h4>Indirect Beneficiary</h4>
														<?php 
														   $category =3;
														   $tree = makeTree($indid, $category);  
														   printNode($tree[0], $category,$respondent, $placeholder);
														?>
													</div>
												</div>
												<?php
											}
                                        }else{  
                                            ?>
                                            <div class="row clearfix">
                                                <div class="col-md-12">
                                                    <h4>Direct Beneficiary</h4>
                                                    <?php 
                                                       $category =2;
                                                       non_disaggregated($indid, $category,$respondent, $placeholder); 
                                                    ?>
                                                </div>
                                            </div>
											<?php 
											$category =3;
											$query = 'SELECT * FROM tbl_indicator_measurement_variables WHERE indicatorid =:indid and category=3';
											$query_rsindid = $db->prepare($query);
											$query_rsindid->execute(array(":indid"=>$indid));
											$row_rsindid = $query_rsindid->rowCount();
											if($row_rsindid > 0){
												?> 
												<div class="row clearfix">
													<div class="col-md-12">
														<h4>Indirect </h4>
														<?php 
															$category =3;
															non_disaggregated($indid, $category,$respondent, $placeholder);  
														?>
													</div>
												</div>
												<?php  
											} 
                                        }
                                    } 
                                ?>   
                                    <input type="hidden" name="form_id" value="<?= $form_id ?>"> 
                                    <input type="hidden" name="form_type" value="<?=$form_type?>"> 
                                    <input type="hidden" name="respondent" value="<?= $respondent ?>">
                                    <input type="hidden" name="level3" value="<?= $stid ?>">
                                    <input type="hidden" name="category" value="<?=$category?>">
                                    <input type="hidden" name="location" value="<?= $location_disaggregation ?>"> 
                                    <input type="hidden" name="lat"  id="lat" value="">
                                    <input type="hidden" name="lng"  id="lng" value=""> 
                                    <div class="col-md-12" align="center">
                                        <button type="submit" name="submit" class="btn btn-success">Update</button>
                                    </div>
                                </fieldset>
                            </div>
                        </form>  
                </div>
            </div>
        </div>
    </div>
</div> 