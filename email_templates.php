<?php
try {
	require('includes/head.php');
	if ($permission) {
		$query_templates = $db->prepare("SELECT * FROM tbl_email_templates WHERE id=:id");
		$query_templates->execute([':id' => 6]);
		$result = $query_templates->fetch(PDO::FETCH_OBJ);
?>
		<style>
			.wrapper {
				padding: 10px;
			}

			.m-flex {
				display: flex;
				justify-content: space-between;
				margin-bottom: 10px;
			}
		</style>
		<!-- start body  -->
		<section class="content">
			<div class="container-fluid">
				<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
					<h4 class="contentheader">
						<?= $icon . " " . $pageTitle ?>
						<?= $results; ?>
						<div class="btn-group" style="float:right">
							<div class="btn-group" style="float:right">
								<button type="button" id="outputItemModalBtnrow" class="btn btn-warning" style="margin-left: 10px;" onclick="window.history.back()">
									Go Back
								</button>
							</div>
						</div>
					</h4>
				</div>
				<div class="row clearfix">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="card">
							<div class="card-header">
								<?php include_once("settings-menu.php"); ?>
							</div>
							<div class="body">
								<div class="wrapper">
									<div class="m-flex">
										<h5>Email Template (HTML)</h5>
									</div>
									<form action="" method="post" id="email_template" enctype="multipart/form-data">
										<textarea name="content" class="editable" id="" cols="30" rows="10" style="width:100%; height: 100vh"><?= htmlentities($result->content) ?></textarea>
										<div style="text-align: center; margin-top: 10px;">
											<input type="hidden" name="store" value="store">
											<button type="submit" class="btn btn-success" id="save-btn">Save changes</button>
										</div>
									</form>
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
} catch (PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>
<script src="assets/js/settings/email/email_templates.js"></script>