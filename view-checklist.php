<?php 
try {

require('includes/head.php'); 
// get the functions selecting data 
require('functions/inspection.php');
require('functions/department.php');
require('functions/indicator.php');

	// get al active topics 
	$checklists = get_checklists();
	$departments = get_departments();

?>

<div class="card-header">
	<div class="row">
		<div class="col-md-7"></div>
		<div class="col-md-5">
			<div class="pull-right">
				<a href="add-checklist.php" class="btn btn-primary"> Add </a>
			</div>
		</div>
	</div>
</div>
<div class="body">
	<div class="table-responsive">
		<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
			<thead>
				<tr class="bg-orange">
					<th style="width:2%"></th>
					<th style="width:7%">#</th>
					<th style="width:30%">Checklist Name</th>
					<th style="width:25%">Output</th>
					<th style="width:23"><?= $departmentlabel ?></th>
					<th style="width:10">Date Added</th>
					<th style="width:5%">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if (!$checklists) {
				?>
					<tr>
						<td colspan="6">
							<div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div>
						</td>
					</tr>
					<?php } else {
					$nm = 0;
					foreach ($checklists as $checklist) {
						$nm = $nm + 1;
						$cklid = $checklist['id'];
						$er_id = base64_encode($cklid);
						$dept = $checklist['department'];
						$output = $checklist['output'];
						$checklistname = $checklist['name'];
						$dateadded = $checklist['date_created'];
						$indicator = get_indicator_by_indid($output);
						$indicator_name = ($indicator) ? $indicator['indicator_name'] : "";
						$ckdate = strtotime($dateadded);
						$checklistdate = date("d M Y", $ckdate);

						$query_dept = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid = :dept");
						$query_dept->execute(array(":dept" => $dept));
						$row_dept = $query_dept->fetch();
						$department = $row_dept["sector"];
					?>
						<tr data-toggle="collapse" data-target=".order<?php echo $nm; ?>" style="background-color:#eff9ca">
							<td align="center" class="mb-0">
								<button class="btn btn-link" title="Click once to expand and Click twice to Collapse!!">
									<i class="fa fa-plus-square" style="font-size:16px"></i>
								</button>
							</td>
							<td align="center"><?php echo $nm; ?></td>
							<td><?php echo $checklistname; ?></td>
							<td><?php echo $indicator_name; ?></td>
							<td><?php echo $department; ?></td>
							<td><?php echo $checklistdate; ?></td>
							<td>
								<div class="btn-group">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Options
										<span class="caret"></span>
									</button>
									<ul class="dropdown-menu">
										<li>
											<a type="button" href="edit-checklist.php?cklstid=<?= $er_id ?>" id="addFormModalBtn">
												<i class="fa fa-pencil-square"></i> </i> Edit
											</a>
										</li>
										<li>
											<a type="button" id="#removeItemModalBtn" onclick="removeItem('<?php echo $cklid ?>')">
												<i class="fa fa-trash-o"></i> Delete
											</a>
										</li>
									</ul>
								</div>
							</td>
						</tr>
						<?php
						$query_cklsttopics = $db->prepare("SELECT DISTINCT topic FROM tbl_inspection_checklist_questions WHERE checklistname = :cklid");
						$query_cklsttopics->execute(array(":cklid" => $cklid));
						$totalRows_cklsttopics = $query_cklsttopics->rowCount();
						?>
						<tr class="collapse order<?php echo $nm; ?>" style="background-color:#FF9800; color:#FFF">
							<th></th>
							<th>#</th>
							<th colspan="4">Checklist Topic</th>
							<th>Action</th>
						</tr>
						<?php
						if ($totalRows_cklsttopics == 0) {
						?>
							<tr class="collapse order<?php echo $nm; ?>">
								<td colspan="7">
									<div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div>
								</td>
							</tr>
							<?php } else {
							$num = 0;
							while ($row_cklsttopics = $query_cklsttopics->fetch()) {
								$num = $num + 1;
								$topicid = $row_cklsttopics['topic'];

								$query_topic = $db->prepare("SELECT topic FROM tbl_inspection_checklist_topics WHERE id = :topicid");
								$query_topic->execute(array(":topicid" => $topicid));
								$row_topic = $query_topic->fetch();
								$topic = $row_topic["topic"];

								$query_cklstqst = $db->prepare("SELECT id, question FROM tbl_inspection_checklist_questions WHERE topic = :topicid AND checklistname = :cklid ORDER BY id ASC");
								$query_cklstqst->execute(array(":topicid" => $topicid, ':cklid' => $cklid));

							?>
								<tr data-toggle="collapse" data-target=".topic<?php echo $nm . $num; ?>" class="collapse order<?php echo $nm; ?>" style="background-color:#CDDC39">
									<td align="center" class="mb-0">
										<button class="btn btn-link" title="Click once to expand and Click twice to Collapse!!"> <i class="more-less fa fa-plus-square" style="font-size:16px"></i>
										</button>
									</td>
									<td align="center"> <?php echo $nm . "." . $num; ?></td>
									<td colspan="4"><?php echo $topic; ?></td>
									<td>

									</td>
								</tr>
								<tr class="collapse topic<?php echo $nm . $num; ?>" style="background-color:#b8f9cb; color:#FFF">
									<th></th>
									<th>#</th>
									<th COLSPAN=4>Checklist Question</th>
									<th>Action</th>
								</tr>
								<?php
								$nmb = 0;
								while ($row_cklstqst = $query_cklstqst->fetch()) {
									$nmb = $nmb + 1;
									$qstid = $row_cklstqst['id'];
									$question = $row_cklstqst["question"];

								?>
									<tr class="collapse topic<?php echo $nm . $num; ?>" style="background-color:#FFF">
										<td style="background-color:#b8f9cb"></td>
										<td align="center"><?php echo $nm . "." . $num . "." . $nmb; ?></td>
										<td COLSPAN=4><?php echo $question; ?></td>
										<td>

										</td>
									</tr>
						<?php
								}
							}
						}
						?>

				<?php
					}
				}
				?>
			</tbody>
			<script type="text/javascript">
				/*******************************
				 * ACCORDION WITH TOGGLE ICONS
				 *******************************/

				function toggleIcon(e) {
					$(e.target)
						.find(".more-less")
						.toggleClass('fa fa-plus-square fa fa-minus-square');
				}

				$('.mb-0').on('hidden.bs.collapse', toggleIcon);
				$('.mb-0').on('shown.bs.collapse', toggleIcon);
			</script>
		</table>
	</div>
</div>
<?php
include_once('includes/footer.php');

} catch (PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());

}
?>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
	function removeItem(cklstid = null) {
		const url1 = 'ajax/inspection/index.php';
		if (cklstid) {
			swal({
					title: "Are you sure?",
					text: "Once deleted, you will not be able to recover this data!",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDelete) => {
					if (willDelete) {
						$.ajax({
							type: "post",
							url: url1,
							data: {
								cklstid: cklstid
							},
							dataType: "json",
							success: function(response) {
								if (response.success) {
									swal(response.msg, {
										icon: "success",
									});
									setTimeout(function() {
										location.reload(true)
									}, 3000);
								} else {
									swal(response.msg, {
										icon: "error",
									});
									setTimeout(function() {
										location.reload(true)
									}, 3000);
								}
							}
						});
					} else {
						swal("You have canceled the action!");
					}
				});
		} else {
			swal("Error id does not exist", {
				icon: "error",
			});
		}
	}
</script>