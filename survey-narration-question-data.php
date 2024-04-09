<?php
$qstid = (isset($_GET['qst'])) ? base64_decode($_GET['qst']) : header("Location: view-survey-data");
require('includes/head.php'); 
if ($permission) {
  try {
    $query_question = $db->prepare("SELECT * FROM tbl_project_outcome_evaluation_questions WHERE id=:qstid");
    $query_question->execute(array(":qstid" => $qstid));
    $row_question = $query_question->fetch();
	$parentid = $row_question["parent"];
	$question = $row_question["question"];
	$projid = $row_question["projid"];

    $query_proj = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
    $query_proj->execute(array(":projid" => $projid));
    $row_proj = $query_proj->fetch();
    $project = $row_proj["projname"];

	if(!empty($parentid) || $parentid != ''){
		$query_parent_question = $db->prepare("SELECT * FROM tbl_project_outcome_evaluation_questions WHERE id=:parentid");
		$query_parent_question->execute(array(":parentid" => $parentid));
		$row_parent_question = $query_parent_question->fetch();
		$parentquestion = $row_parent_question["question"];
	}

  }catch (PDOException $th){
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
  }

?>
   <!-- start body  -->
   <section class="content">
      <div class="container-fluid">
         <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
            <h4 class="contentheader">
              <i class="fa fa-columns" aria-hidden="true"></i>
               <?php echo $pageTitle ?>
               <div class="btn-group" style="float:right">
                  <div class="btn-group" style="float:right">
                    <input type="button" VALUE="Go Back" class="btn btn-warning pull-right" onclick="history.back(-1)" id="btnback">
                  </div>
               </div>
            </h4>
         </div>
         <div class="row clearfix">
            <div class="block-header">
               <?= $results; ?>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
               <div class="card">
                 <div class="header">
                   <div class="row clearfix">
                     <div class="col-md-12">
                       <h5 class="text-align-center bg-light-green" style="border-radius:4px; padding:5px; line-height:35px !important;">
                         <strong>Project Name: <?=$project?></strong>
                       </h5>
                     </div>
                   </div>
                 </div>
                  <div class="body">
                    <div class="row">
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <label class="control-label">Question :</label>
                          <div class="form-line">
                            <div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
                              <strong><?php echo $question; ?> </strong>
                            </div>
                          </div>
                        </div>
						<?php
						if($parentid != null || !empty($parentid) || $parentid != ''){
						?>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							  <label class="control-label">Parent Question :</label>
							  <div class="form-line">
								<div style="border:#CCC thin solid; border-radius:5px; height:auto; padding:10px; color:#3F51B5">
								  <strong><?php echo $parentquestion; ?> </strong>
								</div>
							  </div>
							</div>
						<?php
						}
						?>
                      </div>
                    </div>
                    <div class="row clearfix">
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <fieldset class="scheduler-border">
                          <legend class="scheduler-border" style="background-color:#03A9F4; border:#000 thin solid;; color:white"><strong> Data: </strong>
                          </legend>
                          <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <div class="col-md-12 table-responsive">
                                <table class="table table-bordered table-striped">
                                  <thead>
                                    <tr class="bg-light-blue">
                                      <th style="width:30%">Location</th>
                                      <th style="width:70%">Responses</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
									$query_proj_location =  $db->prepare("SELECT projstate FROM tbl_projects WHERE projid='$projid'");
									$query_proj_location->execute();
									$row_locatios = $query_proj_location->fetch();
									$proj_locations = $row_locatios["projstate"];
									$projlocations = explode(",",$proj_locations);
									$proj_location_count= count($projlocations);

									foreach($projlocations as $locations){
										$query_location =  $db->prepare("SELECT * FROM tbl_state WHERE id='$locations'");
										$query_location->execute();
										$row_location = $query_location->fetch();
										$location = $row_location["state"];
										
										$query_answers =  $db->prepare("SELECT * FROM tbl_project_evaluation_answers a left join tbl_project_evaluation_submission s on s.id=a.submissionid WHERE projid='$projid' and level3='$locations' and questionid='$qstid'");
										$query_answers->execute();
                                        $row_answers = $query_answers->fetchAll();
										$count_answers = $query_answers->rowCount();
										?>
                                        <tr class="bg-lime">
                                            <td class="bg-light-green" rowspan="<?=$count_answers?>"><font color="#000"><?php echo $location;?></font></td>
                                            <?php
                                            if($count_answers > 0){
                                              foreach($row_answers as $rows){
                                                $answer = $rows["answer"];
                                                ?>
                                                <td class="bg-lime text-center"><font color="#f7070b"><?=$answer?></font></td>
                                              <?php
												echo "</tr>";
                                              }
                                            }
											?>
										</tr>
										<?php
									}
									?>
                                  </tbody>
                                </table>
                              </div>
                            </div>							
                          </div>
                        </fieldset>
                      </div>
                    </div>
                  </div>
               </div>
            </div>
         </div>
   </section>
   <!-- end body  -->
<?php
} else {
   $results =  restriction();
   echo $results;
}

require('includes/footer.php');
?>
