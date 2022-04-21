<?php
$pageName = "Strategic Plans";
$replacement_array = array(
	'planlabel' => "CIDP",
	'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
$pageTitle = "Project Outputs Quartely Targets";

if ($permission) {
	try {
		if (isset($_GET["prg"]) && !empty($_GET["prg"])) {
			$progid = $_GET["prg"];
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
	} catch (PDOException $ex) {
		$results = flashMessage("An error occurred: " . $ex->getMessage());
	}
?>
	<style>
		#links a {
			color: #FFFFFF;
			text-decoration: none;
		}

		hr {
			display: block;
			margin-top: 0.5em;
			margin-bottom: 0.5em;
			margin-left: auto;
			margin-right: auto;
			border-style: inset;
			border-width: 1px;
		}

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
				<i class="fa fa-arrows" aria-hidden="true"></i> 
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
						<div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="manageItemTable">
                        <thead>
                            <tr>
                                <th width="4%">#</th>
                                <th width="27%">Project Name</th>
                                <th width="25%">Program Name</th>
                                <th width="8%">Project Type</th> 
                                <th width="6%">Status</th>
                                <th width="4%">Due Date</th>
                                <th width="12%">Action</th>
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
	<!-- Start Item more -->
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

<?php
} else {
	$results =  restriction();
	echo $results;
}

require('includes/footer.php');
?>
<script src="assets/custom js/fetch-workplan.js"></script>

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