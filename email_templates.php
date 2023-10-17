<?php
require('includes/head.php');
if ($permission) {
	try {
		$query_templates = $db->prepare("SELECT * FROM tbl_email_templates order by id asc");
		$query_templates->execute();
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
					<?= $icon ?>
					<?= $pageTitle ?>
					<div class="btn-group" style="float:right">
						<div class="btn-group" style="float:right">
							<a class="btn btn-block btn-sm btn-success btn-flat border-primary" href="email_template">
								<i class="fa fa-plus"></i> Add New Email Template
							</a>
						</div>
					</div>
				</h4>
			</div>
			<div class="row clearfix">
				<div class="col-md-12" style="margin-top:10px">
					<div class="card">
                            <div class="button-demo" style="margin-top:-15px; margin-left:0px; margin-right:-2px">
								<?php include_once("settings-menu.php"); ?>
							</div>
					</div>
				</div>
				<div class="block-header">
					<?= $results; ?>

				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="body">
							<div class="table-responsive">
								<table class="table tabe-hover table-bordered" id="list">
									<thead>
										<tr>
											<th class="text-center">#</th>
											<th data-orderable="false">Title</th>
											<th data-orderable="false">Stage</th>
											<th data-orderable="false">Content</th>
											<th>Status</th>
											<th data-orderable="false">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$i = 1;
										while ($row = $query_templates->fetch()){
											$stageid = $row['stage'];
											$query_stage = $db->prepare("SELECT stage FROM tbl_project_workflow_stage where id ='$stageid'");
											$query_stage->execute();
											$row_stage = $query_stage->fetch();
											?>
											<tr>
												<th class="text-center"><?php echo $i++ ?></th>
												<td><b><?php echo $row['title'] ?></b></td>
												<td><b><?php echo $row_stage['stage'] ?></b></td>
												<td><b><?php echo $row['content'] ?></b></td>
												<td><b> <span class="badge badge-<?php echo $row['active'] == 1 ? "success" : "warning"; ?>"></span> <?php echo $row['active'] == 1 ? "Active" : "Disabled"; ?></b></td>
												<td class="text-center">
													<div class="btn-group">
														<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															Action <span class="caret"></span>
														</button>
														<ul class="dropdown-menu">
															<li><a type="button" class="dropdown-item" href="email_template?tempid=<?php echo $row['id'] ?>"> <i class="glyphicon glyphicon-file"></i>View</a></li>
															<li><a type="button" class="dropdown-item" href="email_template?tempid=<?php echo $row['id'] ?>"><i class="glyphicon glyphicon-edit"></i> Edit</a></li>
															<li><a type="button" class="dropdown-item change_status" onchange="change_status()" data-id="<?php echo $row['id'] ?>"><i class="glyphicon glyphicon-trash"></i> <?php echo $row['active'] == 1 ? "Disable" : "Activate"; ?></a></li>
														</ul>
													</div>
												</td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
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
	$(document).ready(function() {
		$('#list').dataTable()
		$('.view_user').click(function() {
			uni_modal("<i class='fa fa-id-card'></i> User Details", "view_user.php?id=" + $(this).attr('data-id'))
		})
		$('.change_status').click(function() {
			_conf("Are you sure you want to disable this template?", "change_status", [$(this).attr('data-id')])
		})
	})

	function change_status($id) {
		start_load()
		$.ajax({
			url: 'ajax/ajax.php?action=change_status',
			method: 'POST',
			data: {
				change_status,
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully updated", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				}
			}
		})
	}
</script>
<script src="assets/custom js/indicator-details.js"></script>