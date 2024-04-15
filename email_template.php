<?php
	try {

require('includes/head.php');
if ($permission) {


		$editFormAction = $_SERVER['PHP_SELF'];
		if (isset($_SERVER['QUERY_STRING'])) {
			$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
		}

		$results = "";

		if (isset($_POST["submit"])) {
			$today = date('Y-m-d');
			$title = $_POST["title"];
			$stage = $_POST["projstage"];
			$content = $_POST["content"];
			$id = $_POST["id"];
			$createdby = 1;

			if (empty($id)) {
				$save = $db->prepare("INSERT INTO tbl_email_templates SET title = :title, stage = :stage, content = :content, createdby = :createdby, dateCreated = :today");
				$result  = $save->execute(array(":title" => $title, ":stage" => $stage, ":content" => $content, ":createdby" => $createdby, ":today" => $today));
			} else {
				$save = $db->prepare("UPDATE tbl_email_templates SET title = :title, stage = :stage, content = :content, createdby = :createdby, dateCreated = :today WHERE id = :id");
				$result  = $save->execute(array(":title" => $title, ":stage" => $stage, ":content" => $content, ":createdby" => $createdby, ":today" => $today, ":id" => $id));
			}

			if ($result) {
				$msg = 'Template details successfully saved.';
				$results =
					"<script type=\"text/javascript\">
					swal({
						title: \"Success!\",
						text: \" $msg\",
						type: 'Success',
						timer: 3000,
						showConfirmButton: false 
					});
					setTimeout(function(){
						window.location.href = 'email_templates.php';
					}, 5000);
				</script>";
			} else {
				$msg = 'Can not save the provided template details!!';
				$results = "<script type=\"text/javascript\">
					swal({
						title: \"Error!\",
						text: \" $msg \",
						type: 'Danger',
						timer: 5000,
						showConfirmButton: false });
				</script>";
			}
		}


		$query_rsUsers = $db->prepare("SELECT * FROM tbl_projteam2 WHERE ptid = '$user_name'");
		$query_rsUsers->execute();
		$row_rsUsers = $query_rsUsers->fetch();
		$totalRows_rsUsers = $query_rsUsers->rowCount();

		if (isset($_GET["tempid"])) {
			$id = $_GET["tempid"];
			$querytemplate = $db->prepare("select * from `tbl_email_templates` where `id` = $id");
			$querytemplate->execute();
			$rowtemplate = $querytemplate->fetch();
			$totalRows_template = $querytemplate->rowCount();
		}
	
?>
	<!-- start body  -->
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<?= $icon ?>
					<?= $pageTitle ?>
					<div class="btn-group" style="float:right">
						<div class="btn-group" style="float:right">
						</div>
					</div>
				</h4>
			</div>
			<div class="row clearfix">
				<div class="block-header">
					<?= $results; ?>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<?php include_once("settings-menu.php"); ?>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="body">
							<form method="POST" name="addemailtemplate" action="" enctype="multipart/form-data" autocomplete="off">
								<fieldset class="scheduler-border">
									<legend class="scheduler-border" style="background-color:#c7e1e8;  border:#CCC thin dashed; border-radius:3px">Template Details</legend>
									<div class="col-lg-6">
										<label style="margin-top: 10px;">Template Title <span style="color:red;font-size:15px;">*</span> :</label>
										<input name="title" type="text" class="form-control" value="<?php echo $rowtemplate['title']; ?>" data-placeholder="Enter template title" style="border:#CCC thin solid; border-radius: 5px; padding-left:5px" required />
									</div>
									<div class="col-lg-6">
										<label style="margin-top: 10px;">Project Implementation Stage <span style="color:red;font-size:15px;">*</span> :</label>
										<select name="projstage" id="projstage" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
											<option value="">.... Select stage ....</option>
											<?php
											$initialstageid = $rowtemplate['stage'];
											// get project stages 
											$query_projstages =  $db->prepare("SELECT * FROM tbl_project_workflow_stage where active=1");
											$query_projstages->execute();
											
											while ($row_projstages = $query_projstages->fetch()) {

												$stageid = $row_projstages["id"];
												$stage = $row_projstages["stage"];
												$selected = "";
												if($initialstageid == $stageid){
													$selected = "selected";
												}
												
												echo '<option value="' . $stageid . '" '.$selected.'>' . $stage . '</option>';
											}
											?>
										</select>
									</div>
									<div class="col-lg-12">
										<label style="margin-top: 10px;">Template Content <span style="color:red;font-size:15px;">*</span> :</label>
										<textarea name="content" cols="45" rows="10" class="form-control txtboxes" id="template" style="width:100%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif; border:#CCC thin solid; border-radius: 5px; padding-left:5px" required><?php echo $rowtemplate['content']; ?></textarea>

									</div>
									<div class="col-lg-12">
										<label>Template Variables: </label>
										<div type="text" name="website" class="form-control" id="">[SITE_URL] [SITE_NAME] [FIRST_NAME] [LAST_NAME] [ADDRESS] [CITY] [MOBILE_NUMBER] [EMAIL] [PASSWORD]</div>
									</div>

									<div class="row clearfix">
										<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
										</div>
										<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
											<input type="hidden" value="<?php echo $rowtemplate['id']; ?>" name="id" />
											<div class="btn-group">
												<a href="email_templates.php" class="btn bg-orange waves-effect waves-light" type="button">Cancel</a>
											</div>
											<div class="btn-group">
												<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="<?php echo isset($id) && !empty($id) ? "Update" : "Save"; ?>" />
											</div>
											<input type="hidden" name="MM_insert" value="pmfrm" />
										</div>
										<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
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
} catch (PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
require('includes/footer.php');
?>
<script src="assets/custom js/indicator-details.js"></script>