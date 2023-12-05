<?php
require('includes/head.php');

if ($permission) {
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
				<?php
				if (isset($_GET["mainmenu"]) && !empty($_GET["mainmenu"])) {
					include_once('project-main-menu-inner.php');
				} elseif (isset($_GET["workflow"]) && !empty($_GET["workflow"])) {
					include_once('system-workflow-stages-inner.php');
				} elseif (isset($_GET["evtype"]) && !empty($_GET["evtype"])) {
					include_once('project-evaluation-types-inner.php');
				} elseif (isset($_GET["biztype"]) && !empty($_GET["biztype"])) {
					include_once('project-contractor-business-types-inner.php');
				} elseif (isset($_GET["contractornationality"]) && !empty($_GET["contractornationality"])) {
					include_once('project-contractor-nationality-inner.php');
				} elseif (isset($_GET["currency"]) && !empty($_GET["currency"])) {
					include_once('project-currency-inner.php');
				} elseif (isset($_GET["dtfreq"]) && !empty($_GET["dtfreq"])) {
					include_once('project-data-collection-frequency-inner.php');
				} elseif (isset($_GET["donationtype"]) && !empty($_GET["donationtype"])) {
					include_once('project-donation-type-inner.php');
				} elseif (isset($_GET["leavecat"]) && !empty($_GET["leavecat"])) {
					include_once('project-employee-leave-categories-inner.php');
				} elseif (isset($_GET["fiscalyear"]) && !empty($_GET["fiscalyear"])) {
					include_once('project-fiscal-year-inner.php');
				} elseif (isset($_GET["fundingtype"]) && !empty($_GET["fundingtype"])) {
					include_once('project-funding-type-inner.php');
				} elseif (isset($_GET["implmethod"]) && !empty($_GET["implmethod"])) {
					include_once('project-implementation-method-inner.php');
				} elseif (isset($_GET["severity"]) && !empty($_GET["severity"])) {
					include_once('project-issue-severity-inner.php');
				} elseif (isset($_GET["maptype"]) && !empty($_GET["maptype"])) {
					include_once('project-map-type-inner.php');
				} elseif (isset($_GET["paymentstatus"]) && !empty($_GET["paymentstatus"])) {
					include_once('project-payment-status-inner.php');
				} elseif (isset($_GET["projectstatus"]) && !empty($_GET["projectstatus"])) {
					include_once('project-status-inner.php');
				} elseif (isset($_GET["designations"]) && !empty($_GET["designations"])) {
					include_once('designation-inner.php');
				}  elseif (isset($_GET["permission"]) && !empty($_GET["permission"])) {
					include_once('view-permission.php');
				}
				elseif (isset($_GET["procurementmethod"]) && !empty($_GET["procurementmethod"])) {
					include_once('project-procurement-method-inner.php');
				} elseif (isset($_GET["tendercat"]) && !empty($_GET["tendercat"])) {
					include_once('project-tender-category-inner.php');
				} elseif (isset($_GET["tendertype"]) && !empty($_GET["tendertype"])) {
					include_once('project-tender-type-inner.php');
				} elseif (isset($_GET["titles"]) && !empty($_GET["titles"])) {
					include_once('project-titles-inner.php');
				} elseif (isset($_GET["timelines"]) && !empty($_GET["timelines"])) {
					include_once('project-workflow-stage-timelines-inner.php');
				} elseif (isset($_GET["priorities"]) && !empty($_GET["priorities"])) {
					include_once('project-priorities-inner.php');
				} elseif (isset($_GET["indcat"]) && !empty($_GET["indcat"])) {
					include_once('project-indicator-category-inner.php');
				} elseif (isset($_GET["financialplan"]) && !empty($_GET["financialplan"])) {
					include_once('project-financial-plan-inner.php');
				}
				?>
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

<script type="text/javascript">
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
</script>