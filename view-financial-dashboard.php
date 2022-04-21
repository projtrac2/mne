<?php
$replacement_array = array(
	'planlabel' => "CIDP",
	'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
if ($permission) {
	$pageTitle = "Financial Dashboard";
	try {
		$current_year = '';
		$current_date = date("Y-m-d");
		$month =  date('m');
		if ($month  < 7) {
			$current_year =  date("Y") - 1;
		} else {
			$current_year =  date("Y");
		}

		$nextyear = $current_year + 1;
		$last_year = $current_year - 1;
		$currfinyear = $current_year . "/" . $nextyear;
		$prevfinyear = $last_year . "/" . $current_year;

		$query_stratplan =  $db->prepare("SELECT * FROM tbl_strategicplan WHERE current_plan=1");
		$query_stratplan->execute();
		$row_stratplan = $query_stratplan->fetch();
		$totalRows_stratplan = $query_stratplan->rowCount();

		$stplan = $row_stratplan["id"];
		$plan = $row_stratplan["plan"];
		$vision = $row_stratplan["vision"];
		$mission = $row_stratplan["mission"];
		$noyears = $row_stratplan["years"];
		$styear = $row_stratplan["starting_year"];

		$total_strategic_plan_years = $row_stratplan["years"];
		$strategic_plan_start_year = $row_stratplan["starting_year"];

		function financial_years(int $total_strategic_plan_years, int $strategic_plan_start_year)
		{
			$financial_years = array();
			$years = array();
			for ($i = 0; $i < $total_strategic_plan_years; $i++) {
				$year = $strategic_plan_start_year++;
				$financial_year = $year . "/" . ($year + 1);
				array_push($financial_years, $financial_year);
				array_push($years, $year);
			}

			return array("financial_years" => $financial_years, "years" => $years);
		}

		$financial_year_details = financial_years($total_strategic_plan_years, $strategic_plan_start_year);
		$strategic_plan_financial_years = json_encode($financial_year_details['financial_years']);
		$strategic_plan_years = json_encode($financial_year_details['years']);

		function get_financial_year($year)
		{
			global $db;
			$query_crfinyear =  $db->prepare("SELECT * FROM tbl_fiscal_year WHERE yr='$year'");
			$query_crfinyear->execute();
			$row_crfinyear = $query_crfinyear->fetch();
			$count_crfinyear = $query_crfinyear->rowCount();

			if ($count_crfinyear > 0) {
				return $row_crfinyear;
			} else {
				return false;
			}
		}

		function current_financial_year($current_financial_year_id)
		{
			global $db;
			$query_crfinyearalloc = $db->prepare("SELECT sum(projcost) AS totalbudget FROM tbl_projects  WHERE projfscyear = '$current_financial_year_id'");
			$query_crfinyearalloc->execute();
			$row_crfinyearalloc = $query_crfinyearalloc->fetch();
			$totalcrfybudget = number_format($row_crfinyearalloc["totalbudget"], 2);

			$query_crfinyearamtmain = $db->prepare("SELECT sum(p.amount) AS totalamt FROM tbl_financiers f  INNER JOIN tbl_financier_type t on f.type = t.id  INNER JOIN tbl_funding_type s on s.category = t.id INNER JOIN tbl_funds p on p.funder = f.id WHERE p.financial_year= '$current_financial_year_id'");
			$query_crfinyearamtmain->execute();
			$row_crfinyearamtmain = $query_crfinyearamtmain->fetch();
			$totalcrfinyearamount = $row_crfinyearamtmain["totalamt"];


			$totalcrfinyearamt = number_format($totalcrfinyearamount, 2);
			$crfinyearrate = 0;
			if ($totalcrfinyearamount != 0) {
				$crfinyearrate = round(($totalcrfinyearamount / $row_crfinyearalloc["totalbudget"]) * 100, 1);
			}
			return array("totalcrfybudget" => $totalcrfybudget, "crfinyearrate" => $crfinyearrate, "totalcrfinyearamt" => $totalcrfinyearamt, "totalcrfinyearamount" => $totalcrfinyearamount);
		}

		// previous financial year detail
		function previous_financial_year($pvfinyearid)
		{
			global $db;
			$query_pvfinyearalloc = $db->prepare("SELECT sum(projcost) AS totalbudget FROM tbl_projects  WHERE projfscyear = '$pvfinyearid'");
			$query_pvfinyearalloc->execute();
			$row_pvfinyearalloc = $query_pvfinyearalloc->fetch();

			$query_pvfinyearamtmain = $db->prepare("SELECT sum(p.amount) AS totalamt FROM tbl_financiers f  INNER JOIN tbl_financier_type t on f.type = t.id  INNER JOIN tbl_funding_type s on s.category = t.id INNER JOIN tbl_funds p on p.funder = f.id WHERE p.financial_year='$pvfinyearid'");
			$query_pvfinyearamtmain->execute();
			$row_pvfinyearamtmain = $query_pvfinyearamtmain->fetch();
			$totalpvfinyearamount = $row_pvfinyearamtmain["totalamt"];

			$totalpvfinyearamt = number_format($totalpvfinyearamount, 2);
			$pvfinyearrate = 0;

			if ($totalpvfinyearamount > 0) {
				$pvfinyearrate = round(($totalpvfinyearamount / $row_pvfinyearalloc["totalbudget"]) * 100, 1);
			}

			$totalpvfybudget = number_format($row_pvfinyearalloc["totalbudget"], 2);
			return array("totalpvfinyearamt" => $totalpvfinyearamt, "pvfinyearrate" => $pvfinyearrate, "totalpvfybudget" => $totalpvfybudget, 'totalpvfinyearamount' => $totalpvfinyearamount);
		}

		$current_financial_year_details = get_financial_year($current_year);
		$current_financial_year_budget = $current_financial_year_details ? current_financial_year($current_financial_year_details['id']) : 0;

		$last_financial_year_details = get_financial_year($last_year);
		$last_financial_year_budget = $last_financial_year_details ? previous_financial_year($last_financial_year_details['id']) : 0;


		function get_funds_source()
		{
			global $db;
			$query_rsFunding_type =  $db->prepare("SELECT id, type FROM tbl_funding_type");
			$query_rsFunding_type->execute();
			$row_rsFunding_type = $query_rsFunding_type->fetchAll();
			$totalRows_rsFunding_type = $query_rsFunding_type->rowCount();

			if ($totalRows_rsFunding_type > 0) {
				return $row_rsFunding_type;
			} else {
				return false;
			}
		}

		function get_funds_source_amount($financier_id, $financial_year_id)
		{
			global $db;
			$query_finyearamtgrants = $db->prepare("SELECT sum(amount) AS totalamt FROM tbl_financiers f  INNER JOIN tbl_financier_type t on f.type = t.id  INNER JOIN tbl_funding_type s on s.category = t.id INNER JOIN tbl_funds p on p.funder = f.id WHERE t.id = '$financier_id' AND p.financial_year='$financial_year_id'");
			$query_finyearamtgrants->execute();
			$row_finyearamtgrants = $query_finyearamtgrants->fetch();
			$total_amount = $row_finyearamtgrants["totalamt"];
			return $total_amount;
		}

		function get_years(int $strategic_plan_years, int $strategic_plan_start_year)
		{
			$years = array();
			for ($i = 0; $i < $strategic_plan_years; $i++) {
				$year = $strategic_plan_start_year++;
				array_push($years, $year);
			}
			return $years;
		}


		// get financial contribution by different financier type categories 
		function fund_sources(int $total_strategic_plan_years, int $strategic_plan_start_year)
		{
			$data = array();
			$funding_types = get_funds_source();
			foreach ($funding_types as $funding_type) {
				$organization = $funding_type['type'];
				$financier_id = $funding_type['id'];
				$amount_contributed = array();
				for ($i = 0; $i < $total_strategic_plan_years; $i++) {
					$year = $strategic_plan_start_year++;
					$financial_year_details = get_financial_year($year);
					$financial_year_id = ($financial_year_details) ? $financial_year_details['id'] : 0;
					$amount = get_funds_source_amount($financier_id, $financial_year_id);
					array_push($amount_contributed, $amount != null ? $amount : 0);
				}
				$information = array("name" => (string)$organization, "data" => $amount_contributed);
				array_push($data, $information);
			}
			return json_encode($data);
		}

		$funds_details = fund_sources($total_strategic_plan_years, $strategic_plan_start_year);


		function annual_budget(int $current_financial_year_id)
		{
			global $db;
			$query_annualbudget = $db->prepare("SELECT sum(projcost) AS totalbudget FROM tbl_projects WHERE projfscyear = '$current_financial_year_id'");
			$query_annualbudget->execute();
			$row_annualbudget = $query_annualbudget->fetch();
			$totalannualbudget = $row_annualbudget["totalbudget"];
			$ttannualbudget = 0;
			if (!empty($totalannualbudget) || $totalannualbudget != '') {
				$ttannualbudget = $totalannualbudget / 1000000;
			}
			return $ttannualbudget;
		}

		function annual_expenditure(int $fnyearid)
		{
			global $db;
			$query_annualexpenditure = $db->prepare("SELECT sum(amountpaid) AS totalexpend FROM tbl_payments_disbursed d inner join tbl_payments_request r on r.id=d.reqid inner join tbl_projects p on p.projid=r.projid WHERE p.projfscyear = '$fnyearid'");
			$query_annualexpenditure->execute();
			$row_annualexpenditure = $query_annualexpenditure->fetch();
			$totalannualexpenditure = $row_annualexpenditure["totalexpend"];
			$ttannualexpenditure = 0;
			if (!empty($totalannualexpenditure) || $totalannualexpenditure != '') {
				$ttannualexpenditure = $totalannualexpenditure / 1000000;
			}
			return $ttannualexpenditure;
		}

		function budget_vs_expenditure(int $total_strategic_plan_years, int $strategic_plan_start_year)
		{
			$totalannualbdg =  $totalannualexp = array();
			for ($i = 0; $i < $total_strategic_plan_years; $i++) {
				$year = $strategic_plan_start_year++;
				$current_financial_year_details = get_financial_year($year);

				if ($current_financial_year_details) {
					$current_financial_year_id = $current_financial_year_details['id'];
					$ttannualbudget = annual_budget($current_financial_year_id);
					$ttannualexpenditure = annual_expenditure($current_financial_year_id);
					array_push($totalannualbdg, (float)$ttannualbudget);
					array_push($totalannualexp, (float)$ttannualexpenditure);
				}
			}
			return array("totalannualbdg" => $totalannualbdg, "totalannualexp" => $totalannualexp,);
		}

		$budget_expenditure = budget_vs_expenditure($total_strategic_plan_years, $strategic_plan_start_year);
		$totalannualbdg = json_encode($budget_expenditure['totalannualbdg']);
		$totalannualexp = json_encode($budget_expenditure['totalannualexp']);

		function get_departments()
		{
			global $db;
			$query_depertments = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent=0");
			$query_depertments->execute();
			$departments = $query_depertments->fetchAll();
			$total_departments = $query_depertments->rowCount();
			if ($total_departments > 0) {
				return $departments;
			} else {
				return false;
			}
		}

		function get_department_progam_budget($stid, $year)
		{
			global $db;
			$query_program_department_budget = $db->prepare("SELECT SUM(g.budget) as budget FROM `tbl_programs` as p INNER JOIN tbl_progdetails as g ON g.progid = p.progid WHERE projsector = $stid AND g.year = '$year'");
			$query_program_department_budget->execute();
			$rows_department_budget = $query_program_department_budget->fetch();
			$budget = $rows_department_budget["budget"];
			$ttannual_budget = 0;
			if (!empty($budget) || $budget != '') {
				$ttannual_budget = $budget;
			}
			return $ttannual_budget;
		}

		function get_project_department_expenditure(int $stid, int $fnyearid)
		{
			global $db;
			$query_annualexpenditure = $db->prepare("SELECT sum(amountpaid) AS totalexpend FROM tbl_payments_disbursed d 
			inner join tbl_payments_request r on r.id=d.reqid 
			inner join tbl_projects p on p.projid=r.projid 
			inner join tbl_programs g on p.progid = g.progid
			WHERE g.projsector = $stid AND  p.projfscyear = '$fnyearid'");
			$query_annualexpenditure->execute();
			$row_annualexpenditure = $query_annualexpenditure->fetch();
			$totalannualexpenditure = $row_annualexpenditure["totalexpend"];
			$ttannualexpenditure = 0;
			if (!empty($totalannualexpenditure) || $totalannualexpenditure != '') {
				$ttannualexpenditure = $totalannualexpenditure;
			}
			return $ttannualexpenditure;
		}

		function get_department_fund_utilization_rate(int $total_strategic_plan_years, int $strategic_plan_start_year)
		{
			$departments = get_departments();
			$department_utilization_rate = array();
			foreach ($departments as $department) {
				$stid = $department['stid'];
				$sector = $department['sector'];
				$utilization_rate = array();
				$incriment_strategic_plan_start_year = $strategic_plan_start_year;
				for ($i = 0; $i < $total_strategic_plan_years; $i++) {
					$year = $incriment_strategic_plan_start_year++;
					$current_financial_year_details = get_financial_year($year);
					if ($current_financial_year_details) {
						$current_financial_year_id = $current_financial_year_details['id'];
						$budget = get_department_progam_budget($stid, $year);
						$expenditure = get_project_department_expenditure($stid, $current_financial_year_id);
						$expenditure_rate = 0;
						if ($budget != 0 && $expenditure != 0) {
							$expenditure_rate = (($expenditure / $budget) * 100);
						}
						array_push($utilization_rate, $expenditure_rate);
					} else {
						array_push($utilization_rate, 0);
					}
				}
				$information = array("name" => (string)$sector, "data" => $utilization_rate);
				array_push($department_utilization_rate, $information);
			}
			return json_encode($department_utilization_rate);
		}

		$department_utilization = get_department_fund_utilization_rate($total_strategic_plan_years, $strategic_plan_start_year);
	} catch (PDOException $ex) {
		$result = flashMessage("An error occurred: " . $ex->getMessage());
		print($result);
	}
?>
	<!-- start body  -->
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<i class="fa fa-columns" aria-hidden="true"></i>
					<?php echo $pageTitle ?>
					<div class="btn-group" style="float:right; margin: right 10px;">
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
							<!-- ============================================================== -->
							<!-- Start Page Content -->
							<!-- ============================================================== -->
							<div class="row">

								<!-- Start financial comparison-->
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								</div>
								<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
									<div class="info-box bg-lime hover-expand-effect" style="height:130px">
										<div class="icon" style="width:20%">
											<i class="material-icons">verified_user</i>
										</div>
										<div class="content" style="width:80%">
											<div class="text"><?= $prevfinyear ?> FINANCIAL YEAR
												<h5 class="font-light" style="color:#673AB7"><i><strong>B:</strong> Ksh.<?= $last_financial_year_budget['totalpvfybudget'] ?></i></h5>
												<h5 class="font-light"><i><strong>A:</strong> Ksh.<?= $last_financial_year_budget['totalpvfinyearamt'] ?></i></h5>
											</div>
											<span class="text-danger"><?= $last_financial_year_budget['pvfinyearrate'] ?>%</span>
											<div class="progress" style="height: 10px">
												<div class="progress-bar bg-primary" role="progressbar" style="width: <?= $last_financial_year_budget['pvfinyearrate'] ?>%; height: 10px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
									<div class="info-box bg-light-blue hover-expand-effect" style="height:130px">
										<?php
										if ($last_financial_year_budget['totalpvfinyearamount'] > $current_financial_year_budget['totalcrfinyearamount']) {
										?>
											<div class="icon" style="color:red; width:20%">
												<i class="material-icons">
													<font color="#F44336">trending_down</font>
												</i>
											</div>
										<?php
										} else {
										?>
											<div class="icon" style="color:lime; width:20%">
												<i class="material-icons">
													<font color="#CDDC39">trending_up</font>
												</i>
											</div>
										<?php
										}
										?>
										<div class="content">
											<div class="text"><?= $currfinyear ?> FINANCIAL YEAR
												<h5 class="font-light" style="color:black"><i><strong>B:</strong> Ksh.<?= $current_financial_year_budget['totalcrfybudget'] ?></i></h5>
												<h5 class="font-light"><i><strong>A:</strong> Ksh.<?= $current_financial_year_budget['totalcrfinyearamt'] ?></i></h5>
											</div>
											<span class="text-danger"><?= $current_financial_year_budget['crfinyearrate'] ?>%</span>
											<div class="progress" style="height: 10px">
												<div class="progress-bar bg-lime" role="progressbar" style="width: <?= $current_financial_year_budget['crfinyearrate'] ?>%; height: 10px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
											</div>
										</div>
									</div>
								</div>
								<!-- End of Financial comparison -->

								<!-- Start of Fund Sources Per Year Column-->
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="card" style="margin-bottom:-10px">
										<div class="header">
											<h5 class="card-title" style="margin:5px"><strong>Fund Sources (Ksh. in millions)</strong></h5>
										</div>
										<div class="card-body" style="padding-top:0px">
											<div id="dynamicloaded" style="width:100%; height:400px;">
												<div id="fund_sources">
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- End of Fund Sources Per Year Column -->
								<!-- column -->
								<div class="col-md-12">
									<div class="card" style="margin-bottom:-10px">
										<div class="header">
											<h5 class="card-title" style="margin:5px"><strong>Budget Vs Expenditure Per Year</strong></h5>
										</div>
										<div class="card-body" style="padding-top:10px">
											<div id="budget_vs_expenditure" style="width:100%; height:400px;" align="center"></div>
										</div>
									</div>
								</div>
								<!-- column -->
								<!-- Start Departments Ranking-->
								<div class="col-lg-12">
									<div class="card" style="margin-bottom:-10px">
										<div class="header">
											<h5 class="card-title" style="margin:5px"><strong><?= $ministrylabelplural ?>' Annual Funds Utilization Rate</strong></h5>
										</div>
										<div class="card-body" style="padding-top:0px">
											<div id="dept-ranking" style="width:100%; height:400px;">
											</div>
										</div>
									</div>
								</div>
								<!-- End Departments Ranking -->
							</div>
							<!-- ============================================================== -->
							<!-- End PAge Content -->
							<!-- ============================================================== -->
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
<script src="assets/plugins/echarts/echarts-all.js"></script>

<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>

<script>
	let financial_years = '<?= $strategic_plan_financial_years ?>';
	financial_years = JSON.parse(financial_years);
	let fyears = '<?= $strategic_plan_years ?>';
	fyears = JSON.parse(fyears);

	let funds_details = '<?= $funds_details ?>';
	funds_details = JSON.parse(funds_details);

	let totalannualexp = '<?= $totalannualexp ?>';
	totalannualexp = JSON.parse(totalannualexp);
	let totalannualbdg = '<?= $totalannualbdg ?>';
	totalannualbdg = JSON.parse(totalannualbdg);


	let department_utilization = '<?= $department_utilization ?>';
	department_utilization = JSON.parse(department_utilization);

	$(document).ready(function() {
		fund_sources(funds_details, fyears);
		budget_vs_expenditure();
		department_utilization_rate();
	});

	function fund_sources(data, years) {
		var options = {
			chart: {
				height: 350,
				type: 'bar',
				stacked: true,
				stackType: '100%'
			},
			plotOptions: {
				bar: {
					horizontal: true,
				},

			},
			stroke: {
				width: 1,
				colors: ['#fff']
			},
			series: data,
			xaxis: {
				categories: fyears,
			},
			tooltip: {
				y: {
					formatter: function(val) {
						return val + "M"
					}
				}
			},
			fill: {
				opacity: 1
			},
			legend: {
				position: 'top',
				horizontalAlign: 'left',
				offsetX: 40
			}
		}
		var chart = new ApexCharts(document.querySelector("#fund_sources"), options);
		chart.render();
	}

	function budget_vs_expenditure() {
		var myChart = echarts.init(document.getElementById('budget_vs_expenditure'));

		// specify chart configuration item and data
		option = {
			tooltip: {
				trigger: 'axis'
			},
			legend: {
				data: ['Budget', 'Expenditure']
			},
			toolbox: {
				show: true,
				feature: {

					magicType: {
						show: true,
						type: ['line', 'bar']
					},
					restore: {
						show: true
					},
					saveAsImage: {
						show: true
					}
				}
			},
			color: ["#009efb", "#55ce63"],
			calculable: true,
			xAxis: [{
				type: 'category',
				data: financial_years,
			}],
			yAxis: [{
				type: 'value',
				axisLabel: {
					formatter: 'Ksh. {value}M'
				}
			}],
			series: [{
					name: 'Budget',
					type: 'bar',
					data: totalannualbdg,
					markPoint: {
						data: [{
								type: 'max',
								name: 'The highest'
							},
							{
								type: 'min',
								name: 'The Lowest'
							}
						]
					},
					markLine: {
						data: [{
							type: 'average',
							name: 'Average'
						}]
					}
				},
				{
					name: 'Expenditure',
					type: 'bar',
					data: totalannualexp,
					markPoint: {
						data: [{
								type: 'max',
								name: 'The highest'
							},
							{
								type: 'min',
								name: 'The Lowest'
							}
						]
					},
					markLine: {
						data: [{
							type: 'average',
							name: 'Average'
						}]
					}
				}
			]
		};


		// use configuration item and data specified to show chart
		myChart.setOption(option, true), $(function() {
			function resize() {
				setTimeout(function() {
					myChart.resize()
				}, 100)
			}
			$(window).on("resize", resize), $(".sidebartoggler").on("click", resize)
		});
	}

	function department_utilization_rate() {
		var options = {
			chart: {
				height: 350,
				type: 'bar',
				stacked: true,
				toolbar: {
					show: true
				},
				zoom: {
					enabled: true
				}
			},
			responsive: [{
				breakpoint: 480,
				options: {
					legend: {
						position: 'bottom',
						offsetX: -10,
						offsetY: 0
					}
				}
			}],
			plotOptions: {
				bar: {
					horizontal: false,
				},
			},
			series: department_utilization,
			xaxis: {
				categories: financial_years,
			},
			yAxis: [{
				type: 'value',
				axisLabel: {
					formatter: '{value}%'
				}
			}],
			tooltip: {
				y: {
					formatter: function(val) {
						return val + "%"
					}
				}
			},
			fill: {
				opacity: 1
			},
			legend: {
				position: 'right',
				offsetX: -20,
				offsetY: 5
			},
		}
		var chart = new ApexCharts(
			document.querySelector("#dept-ranking"),
			options
		);
		chart.render();
	}
</script>