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
<?php
require('includes/head.php');
if ($permission) {

	try {
		$query_templates = $db->prepare("SELECT * FROM tbl_email_templates WHERE id=:id");
		$query_templates->execute([':id' => 6]);
		$result = $query_templates->fetch(PDO::FETCH_OBJ);
	} catch (PDOException $ex) {

		function flashMessage($flashMessages)
		{
			return $flashMessages;
		}

		$result = flashMessage("An error occurred: " . $ex->getMessage());
		echo $result;
	}
?>

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
								<textarea name="" class="editable" id="" cols="30" rows="10" style="width:100%; height: 100vh"><?= htmlentities($result->content) ?></textarea>
								<div style="text-align: center; margin-top: 10px;">
									<button class="btn btn-success" id="save-btn">Save changes</button>
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

<script>
	$('#save-btn').on('click', save_content);
	let edits = '';
	// $('.editable').on('change', (e) => {
	// 	edits = e.target.textContent;
	// });

	function getContent(el) {
		console.log($(el));
	}



	function save_content() {
		let vals = $('.editable').val();
		const data = new FormData();
		data.append('content', vals);
		$.ajax({
			url: 'general-settings/action/email-template-actions.php',
			type: 'post',
			contentType: false,
			data: data,
			cache: false,
			processData: false,
			error: (error) => {
				console.log(error);
			},
			success: (response) => {
				if (response) {
					swal({
						title: "Changes saved successfully",
						text: `Successfully saved`,
						icon: "success",
					});
				} else {
					swal({
						title: "System error! Refresh and try again",
						text: `Error occurred`,
						icon: "error",
					});
				}
				setTimeout(() => {
					window.location.reload();
				}, 2000);
			}
		})
	}
</script>