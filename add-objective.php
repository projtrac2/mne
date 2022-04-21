<?php
$pageName = "Strategic Plans";
$replacement_array = array(
   'planlabel' => "CIDP",
   'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');

if ($permission) {
	$kraid = (isset($_GET['kra'])) ? base64_decode($_GET['kra']) : header("Location: view-strategic-plans.php");
	$pageTitle = "ADD STRATEGIC OBJECTIVE DETAILS";
	require('functions/strategicplan.php');

	try {
		$results_kra = get_kra($kraid);
		if (!$results_kra) {
			header("Location: view-strategic-plans.php");
		}

		$kraName = $results_kra['kra'];
		$stplan = base64_encode($results_kra['spid']);

		if (isset($_POST['addplan'])) {
			$objective = $_POST['objective'];
			$desc = $_POST['objdesc'];
			$kpi = 12;
			$kraid = $_POST['kraid'];
			$current_date = date("Y-m-d");

			$ObjectivesInsert = $db->prepare("INSERT INTO tbl_strategic_plan_objectives (kraid, objective, description, kpi, created_by, date_created) VALUES (:kraid, :objective, :desc, :kpi, :user, :dates)");
			$resultObjectives = $ObjectivesInsert->execute(array(":kraid" => $kraid, ":objective" => $objective, ":desc" => $desc, ":kpi" => $kpi, ":user" => $user_name, ":dates" => $current_date));

			if ($resultObjectives) {
				$objectiveid = $db->lastInsertId();
				$stcount = count($_POST["strategic"]);
				for ($cnt = 0; $cnt < $stcount; $cnt++) {
					$strategy = $_POST['strategic'][$cnt];
					$sqlinsert = $db->prepare("INSERT INTO tbl_objective_strategy (objid,strategy,created_by,date_created) VALUES (:objid, :strategy, :user, :dates)");
					$sqlinsert->execute(array(":objid" => $objectiveid, ":strategy" => $strategy, ":user" => $user_name, ":dates" => $current_date));
				}
				$kraid = base64_encode($kraid);
				$msg = 'Strategic Objective added successfully';
				if (isset($_POST['add'])) {
					$results = "<script type=\"text/javascript\">
	                        swal({
	                        title: \"Success!\",
	                        text: \" $msg\",
	                        type: 'Success',
	                        timer: 3000,
	                        showConfirmButton: false });
	                        setTimeout(function(){
	                            window.location.href = 'add-objective.php?kra=$kraid';
	                        }, 3000);
	                    </script>";
					echo $results;
				} elseif (isset($_POST['exit'])) {
					$results = "<script type=\"text/javascript\">
	                        swal({
	                        title: \"Success!\",
	                        text: \" $msg\",
	                        type: 'Success',
	                        timer: 3000,
	                        showConfirmButton: false });
	                        setTimeout(function(){
	                            window.location.href = 'view-strategic-plan-objectives.php?plan=$stplan';
	                        }, 3000);
	                    </script>";
					echo $results;
				}
			}
		}
	} catch (PDOException $ex) {
		$results = flashMessage("An error occurred: " . $ex->getMessage());
	}

?>
<script src="assets/ckeditor/ckeditor.js"></script>

   <!-- start body  -->
   <section class="content">
      <div class="container-fluid">
         <div class="block-header bg-blue-grey" width="100%" height<?php
$kraid = (isset($_GET['kra'])) ? base64_decode($_GET['kra']) : header("Location: view-strategic-plans.php");
$pageName = "ADD STRATEGIC OBJECTIVE DETAILS";
$Id = 2;
$subId = 5;
require('includes/head.php');
require('includes/header.php');
require('functions/strategicplan.php');


try {
	$results_kra = get_kra($kraid);
	if (!$results_kra) {
		header("Location: view-strategic-plans.php");
	}

	$kraName = $results_kra['kra'];
	$stplan = base64_encode($results_kra['spid']);

	if (isset($_POST['addplan'])) {
		$objective = $_POST['objective'];
		$desc = $_POST['objdesc'];
		$kpi = 12;
		$kraid = $_POST['kraid'];
		$current_date = date("Y-m-d");

		$ObjectivesInsert = $db->prepare("INSERT INTO tbl_strategic_plan_objectives (kraid, objective, description, kpi, created_by, date_created) VALUES (:kraid, :objective, :desc, :kpi, :user, :dates)");
		$resultObjectives = $ObjectivesInsert->execute(array(":kraid" => $kraid, ":objective" => $objective, ":desc" => $desc, ":kpi" => $kpi, ":user" => $user_name, ":dates" => $current_date));

		if ($resultObjectives) {
			$objectiveid = $db->lastInsertId();
			$stcount = count($_POST["strategic"]);
			for ($cnt = 0; $cnt < $stcount; $cnt++) {
				$strategy = $_POST['strategic'][$cnt];
				$sqlinsert = $db->prepare("INSERT INTO tbl_objective_strategy (objid,strategy,created_by,date_created) VALUES (:objid, :strategy, :user, :dates)");
				$sqlinsert->execute(array(":objid" => $objectiveid, ":strategy" => $strategy, ":user" => $user_name, ":dates" => $current_date));
			}


			$kraid = base64_encode($kraid);
			$msg = 'Strategic Objective added successfully';
			if (isset($_POST['add'])) {
				$results = "<script type=\"text/javascript\">
                        swal({
                        title: \"Success!\",
                        text: \" $msg\",
                        type: 'Success',
                        timer: 3000,
                        showConfirmButton: false });
                        setTimeout(function(){
                            window.location.href = 'add-objective.php?kra=$kraid';
                        }, 3000);
                    </script>";
				echo $results;
			} elseif (isset($_POST['exit'])) {
				$results = "<script type=\"text/javascript\">
                        swal({
                        title: \"Success!\",
                        text: \" $msg\",
                        type: 'Success',
                        timer: 3000,
                        showConfirmButton: false });
                        setTimeout(function(){
                            window.location.href = 'view-strategic-plan-objectives.php?plan=$stplan';
                        }, 3000);
                    </script>";
				echo $results;
			}
		}
	}
} catch (PDOException $ex) {
	$results = flashMessage("An error occurred: " . $ex->getMessage());
}


?>
<div class="body">
	<div class="body" id="objective_table"></div>

</div>

<?php
require('includes/footer.php');
?>
="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
            <h4 class="contentheader">
               <i class="fa fa-columns" aria-hidden="true"></i>
               <?php echo $pageTitle ?>
               <div class="btn-group" style="float:right">
                  <div class="btn-group" style="float:right">
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
                  <div class="body">
										<h5> <strong>Key Result Area:</strong><u> <?php echo $kraName ?> </u></h5>
										<form action="" method="POST" class="form-inline" role="form" id="stratcplan">
											<fieldset class="scheduler-border">
												<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Add Strategic Objectives. </legend>
												<div class="col-md-12">
													<label class="control-label">Strategic Objective *:</label>
													<div class="form-line">
														<input name="kraid" type="hidden" id="kraid" value="<?php echo $kraid; ?>" />
														<input name="objective" type="text" class="form-control" placeholder="Enter strategic objective" style="width:100%; border:#CCC thin solid; border-radius: 5px" required>
													</div>
												</div>
												<div class="col-md-12">
													<label class="control-label">Strategic Objective Description : <font align="left" style="background-color:#eff2f4"> </font></label>
													<p align="left">
														<textarea name="objdesc" cols="45" rows="4" class="txtboxes" id="objdesc" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder="Describe strategic objective"></textarea>
														<script>
															CKEDITOR.replace('objdesc', {
																height: 200,
																on: {
																	instanceReady: function(ev) {
																		// Output paragraphs as <p>Text</p>.
																		this.dataProcessor.writer.setRules('p', {
																			indent: false,
																			breakBeforeOpen: false,
																			breakAfterOpen: false,
																			breakBeforeClose: false,
																			breakAfterClose: false
																		});
																		this.dataProcessor.writer.setRules('ol', {
																			indent: false,
																			breakBeforeOpen: false,
																			breakAfterOpen: false,
																			breakBeforeClose: false,
																			breakAfterClose: false
																		});
																		this.dataProcessor.writer.setRules('ul', {
																			indent: false,
																			breakBeforeOpen: false,
																			breakAfterOpen: false,
																			breakBeforeClose: false,
																			breakAfterClose: false
																		});
																		this.dataProcessor.writer.setRules('li', {
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
												</div>
											</fieldset>
											<fieldset class="scheduler-border">
												<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Add Strategy(s).
												</legend>
												<div class="row clearfix">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<div class="card" style="margin-bottom:-20px">
															<div class="header">
																<i class="ti-link"></i>MULTIPLE STRATEGIES - WITH CLICK & ADD
															</div>
															<div class="body">
																<table class="table table-bordered" id="strategy_table">
																	<tr>
																		<th style="width:98%">Strategy</th>
																		<th style="width:2%"><button type="button" name="addplus" onclick="add_strow();" title="Add another field" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button>
																		</th>
																	</tr>
																	<tr>
																		<td>
																			<input type="text" name="strategic[]" id="strategic" class="form-control" placeholder="Enter a strategy " style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
																		</td>
																		<td></td>
																	</tr>
																</table>
																<input name="addplan" type="hidden" id="addplan" value="addplan" />
																<input name="username" type="hidden" id="username" value="<?php echo $user_name; ?>" />
																<div class="list-inline" align="center" style="margin-top:20px">
																	<button type="submit" name="add" class="btn btn-primary" id="">
																		Save and Add
																	</button>
																	<button type="submit" name="exit" class="btn btn-primary" id="">
																		Save and Exit
																	</button>
																</div>
															</div>
														</div>
													</div>
												</div>
											</fieldset>
										</form>
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
<script src="assets/js/strategicplan/view-kra-objective.js"></script>
