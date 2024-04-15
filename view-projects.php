<?php
try {
require('includes/head.php');

if ($permission) {

		if (isset($_GET["progid"]) && !empty($_GET["progid"])) {
			$progid = base64_decode($_GET["progid"]);
		}

		$current_date = date("Y-m-d");
		if (isset($_GET["adp"]) && isset($_GET["proj"]) && !empty($_GET["proj"])) {
			$currentfy = date("Y");

			//get financial years 
			$query_rsYear =  $db->prepare("SELECT id, year FROM tbl_fiscal_year WHERE yr=:yr");
			$query_rsYear->execute(array(":yr" => $currentfy));
			$row_rsYear = $query_rsYear->fetch();

			$currentfyid = $row_rsYear["id"];
			$projid = $_GET['proj'];

			$insert_query = $db->prepare("INSERT INTO tbl_annual_dev_plan (projid, financial_year, created_by, date_created) VALUES (:projid, :finyear, :user, :dates)");
			$results = $insert_query->execute(array(":projid" => $projid, ":finyear" => $currentfyid, ":user" => $user_name, ":dates" => $current_date));

			if ($results) {
				$msg = 'The project successfully added to ADP.';
				$results = "<script type=\"text/javascript\">
						swal({
						title: \"Success!\",
						text: \" $msg\",
						type: 'Success',
						timer: 2000,
						showConfirmButton: false });
						setTimeout(function(){
								window.location.href = 'strategic-plan-projects';
							}, 2000);
					</script>";
			}
		}

		$query_userdetails =  $db->prepare("SELECT ptid FROM tbl_projteam2 t inner join tbl_users u on u.pt_id=t.ptid WHERE u.username = '$user_name'");
		$query_userdetails->execute();
		$row_userdetails = $query_userdetails->fetch();


		//get financial years 
		$query_rsYear =  $db->prepare("SELECT id, year FROM tbl_fiscal_year");
		$query_rsYear->execute();
		$row_rsYear = $query_rsYear->fetch();
		$totalRows_rsYear = $query_rsYear->rowCount();

		//get subcounty
		$query_rsComm =  $db->prepare("SELECT id, state FROM tbl_state WHERE parent IS NULL ORDER BY state ASC");
		$query_rsComm->execute();
		$row_rsComm = $query_rsComm->fetch();
		$totalRows_rsComm = $query_rsComm->rowCount();

		//get mapping type 
		$query_rsMapType =  $db->prepare("SELECT id, type FROM tbl_map_type");
		$query_rsMapType->execute();
		$row_rsMapType = $query_rsMapType->fetch();
		$totalRows_rsMapType = $query_rsMapType->rowCount();

		//get project implementation methods 
		$query_rsProjImplMethod =  $db->prepare("SELECT id, method FROM tbl_project_implementation_method");
		$query_rsProjImplMethod->execute();
		$row_rsProjImplMethod = $query_rsProjImplMethod->fetch();
		$totalRows_rsProjImplMethod = $query_rsProjImplMethod->rowCount();

		// get project risks 
		$query_rsRiskCategories =  $db->prepare("SELECT rskid, category FROM tbl_projrisk_categories");
		$query_rsRiskCategories->execute();
		$row_rsRiskCategories = $query_rsRiskCategories->fetch();
		$totalRows_rsRiskCategories = $query_rsRiskCategories->rowCount();

		// get project risks 
		$query_strategic_plan =  $db->prepare("SELECT * FROM tbl_strategicplan where current_plan=1");
		$query_strategic_plan->execute();
		$row_strategic_plan = $query_strategic_plan->fetch();
		$currentplan = $row_strategic_plan["plan"];
		$currentplanid = $row_strategic_plan["id"];
	
?>
	<style>
		.modal-lg {
			max-width: 100% !important;
			width: 90%;
		}
	</style>
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
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="body">
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover" id="manageItemTable">
									<thead>
										<tr>
											<th width="5%">#</th>
											<th width="20%">Project Name</th>
											<th width="20%">Program Name</th>
											<th width="15%">Ministry</th>
											<th width="10%">Budget</th>
											<th width="10%">Financial Year</th>
											<th width="8%">Status</th>
											<th width="8%">Action</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
	</section>
	<!-- end body  -->
	<!-- Start Item Delete -->
	<div class="modal fade" tabindex="-1" role="dialog" id="removeItemModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i> Delete Item</h4>
				</div>
				<div class="modal-body">
					<div class="removeItemMessages"></div>
					<p align="center">Are you sure you want to delete this record?</p>
				</div>
				<div class="modal-footer removeProductFooter">
					<div class="col-md-12 text-center">
						<button type="button" class="btn btn-success" id="removeItemBtn"> <i class="fa fa-check-square-o"></i> Delete</button>
						<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Cancel</button>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" tabindex="-1" role="dialog" id="approveItemModals">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i> Undo? Remove Project from ADP</h4>
				</div>
				<div class="modal-body">
					<div class="undotemMessages"></div>
					<p align="center">Are you sure you want to remove this Project from ADP?</p>
				</div>
				<div class="modal-footer">
					<div class="col-md-12 text-center">
						<button type="button" class="btn btn-success" id="removeadpBtn"> <i class="fa fa-check-square-o"></i> Remove</button>
						<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Cancel</button>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<!-- Start Modal Item approve -->
	<div class="modal fade" id="approveItemModal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-edit"></i> Approve Project</h4>
				</div>
				<div class="modal-body" style="max-height:450px; overflow:auto;">
					<div class="div-result">
						<form class="form-horizontal" id="approveItemForm" action="general-settings/action/project-edit-action.php" method="POST">
							<br />
							<div class="col-md-12" id="aproveBody"></div>
							<div class="modal-footer approveItemFooter">
								<div class="col-md-12 text-center">
									<input type="hidden" name="approveitem" id="approveitem" value="1">
									<input type="hidden" name="user_name" id="user_name" value="<?= $user_name ?>">
									<input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Approve" />
									<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
								</div>
							</div> <!-- /modal-footer -->
						</form> <!-- /.form -->
					</div>
				</div> <!-- /modal-body -->
			</div>
			<!-- /modal-content -->
		</div>
	</div>

	<!-- Start Item more Info -->
	<div class="modal fade" tabindex="-1" role="dialog" id="moreItemModal">
		<div class="modal-dialog  modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="background-color:#03A9F4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info"></i> More Information</h4>
				</div>
				<div class="modal-body" id="moreinfo">
				</div>
				<div class="modal-footer">
					<div class="col-md-12 text-center">
						<button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Close</button>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>
	<!-- End  Item more Info -->
<?php
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');

} catch (PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>
<script type="text/javascript">
	function CallRiskAction(id) {
		$.ajax({
			type: 'post',
			url: 'callriskaction.php',
			data: {
				rskid: id
			},
			success: function(data) {
				$('#riskaction').html(data);
				$("#riskModal").modal({
					backdrop: "static"
				});
			}
		});
	}

	$(document).ready(function() {
		$(".account").click(function() {
			var X = $(this).attr('id');
			if (X == 1) {
				$(".submenus").hide();
				$(this).attr('id', '0');
			} else {
				$(".submenus").show();
				$(this).attr('id', '1');
			}
		});
		//Mouseup textarea false
		$(".submenus").mouseup(function() {
			return false
		});
		$(".account").mouseup(function() {
			return false
		});

		//Textarea without editing.
		$(document).mouseup(function() {
			$(".submenus").hide();
			$(".account").attr('id', '');
		});

	});
</script>
<script type="text/javascript">
	var url;
	url = "assets/processor/view-project-details?prg=<?php echo $progid; ?>&sp=<?= $currentplanid ?>";
</script>
<script src="assets/custom js/fetch-sp-projects.js"></script>