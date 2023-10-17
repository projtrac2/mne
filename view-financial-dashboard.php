<?php
require('includes/head.php');
if ($permission) {
	try {
		$query_stratplan =  $db->prepare("SELECT * FROM tbl_strategicplan WHERE current_plan=1");
		$query_stratplan->execute();
		$row_stratplan = $query_stratplan->fetch();
		$totalRows_stratplan = $query_stratplan->rowCount();

		$stplan = $plan = $vision = $mission = $noyears = $styear =  $total_strategic_plan_years = $strategic_plan_start_year = 0;
		$financial_year_details ="";
		if ($totalRows_stratplan > 0) {
			$stplan = $row_stratplan["id"];
			$plan = $row_stratplan["plan"];
			$vision = $row_stratplan["vision"];
			$mission = $row_stratplan["mission"];
			$noyears = $row_stratplan["years"];
			$styear = $row_stratplan["starting_year"];
			$total_strategic_plan_years = $row_stratplan["years"];
			$strategic_plan_start_year = $row_stratplan["starting_year"];
		} 

		$current_date = date("Y-m-d");
		$month =  date('m');
		$current_year =  ($month  < 7)  ? date("Y") - 1 : date("Y");


		$nextyear = $current_year + 1;
		$last_year = $current_year - 1;
		$currfinyear = $current_year . "/" . $nextyear;
		$prevfinyear = $last_year . "/" . $current_year;


		function current_financial_year($currentfinyear)
		{
			global $db;
			$query_crfinyear =  $db->prepare("SELECT * FROM tbl_fiscal_year WHERE yr='$currentfinyear'");
			$query_crfinyear->execute();
			$row_crfinyear = $query_crfinyear->fetch();
			$currentfinyearid = $row_crfinyear["id"];

			$query_crfinyear_budget = $db->prepare("SELECT sum(projcost) AS totalbudget FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE p.projfscyear = $currentfinyearid AND g.program_type=1");
			$query_crfinyear_budget->execute();
			$row_crfinyear_budget = $query_crfinyear_budget->fetch();
			$budget_total = $row_crfinyear_budget["totalbudget"];
			$budget_total = $budget_total > 0 ?  $budget_total : 0;
			$totalcrfybudget = number_format($budget_total, 2);

			$query_crfinyearalloc = $db->prepare("SELECT sum(b.budget) as totalamt FROM tbl_programs_based_budget b inner join tbl_programs g on g.progid=b.progid WHERE b.finyear=$currentfinyear AND g.program_type=1");
			$query_crfinyearalloc->execute();
			$row_crfinyearalloc = $query_crfinyearalloc->fetch();
			$totalcrfinyearalloc = $row_crfinyearalloc["totalamt"];
			$totalcrfinyearalloc = $row_crfinyearalloc && $totalcrfinyearalloc > 0 ?  $totalcrfinyearalloc : 0;
			$totalcrfinyearallocation = number_format($totalcrfinyearalloc, 2);

			$crfinyearrate = 0;
			if ($row_crfinyearalloc) {
				$crfinyearrate = $totalcrfinyearalloc > 0 &&  $budget_total ?  round(($totalcrfinyearalloc / $budget_total) * 100, 2) : 0;
			}
			return array("totalcrfybudget" => $totalcrfybudget, "crfinyearrate" => $crfinyearrate, "totalcrfinyearamt" => $totalcrfinyearallocation, "totalcrfinyearallocation" => $totalcrfinyearalloc);
		}

		// previous financial year detail
		function previous_financial_year($previousfinyear)
		{
			global $db;

			$query_crfinyear =  $db->prepare("SELECT * FROM tbl_fiscal_year WHERE yr='$previousfinyear'");
			$query_crfinyear->execute();
			$row_crfinyear = $query_crfinyear->fetch();
			$previousfinyearid = $row_crfinyear["id"];

			$query_pvfinyearbudget = $db->prepare("SELECT sum(projcost) AS totalbudget FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid WHERE program_type=1 AND p.projfscyear = $previousfinyearid");
			$query_pvfinyearbudget->execute();
			$row_pvfinyearbudget = $query_pvfinyearbudget->fetch();
			$total_budget = $row_pvfinyearbudget["totalbudget"];
			$total_budget = $total_budget > 0 ?  $total_budget : 0;
			$totalpvfybudget = number_format($total_budget, 2); 

			$query_pvfinyearalloc = $db->prepare("SELECT sum(b.budget) as totalamt FROM tbl_programs_based_budget b inner join tbl_programs g on g.progid=b.progid WHERE program_type=1 AND b.finyear=$previousfinyear");
			$query_pvfinyearalloc->execute();
			$row_pvfinyearalloc = $query_pvfinyearalloc->fetch();
			$totalpvfinyearalloc = $row_pvfinyearalloc["totalamt"];
			$totalpvfinyearalloc = $totalpvfinyearalloc > 0 ?  $totalpvfinyearalloc : 0;

			$pvfinyearrate = 0;

			$pvfinyearrate = $totalpvfinyearalloc > 0 &&  $total_budget > 0 ? round(($totalpvfinyearalloc / $total_budget) * 100, 2) : 0;
			$totalpvfinyearallocation = number_format($totalpvfinyearalloc, 2);

			return array("totalpvfinyearalloc" => $totalpvfinyearallocation, "pvfinyearrate" => $pvfinyearrate, "totalpvfybudget" => $totalpvfybudget, 'totalpvfinyearallocation' => $totalpvfinyearalloc);
		}

		function fund_sources()
		{
			global $db, $strategic_plan_start_year, $total_strategic_plan_years;
			$start_year = $strategic_plan_start_year;
			$query_rsFunding_type =  $db->prepare("SELECT id, type FROM tbl_funding_type");
			$query_rsFunding_type->execute();
			$totalRows_rsFunding_type = $query_rsFunding_type->rowCount();
			$data = [];
			if ($totalRows_rsFunding_type > 0) {
				while ($row_rsFunding_type = $query_rsFunding_type->fetch()) {
					$organization = $row_rsFunding_type['type'];
					$financier_id = $row_rsFunding_type['id'];
					$amount_contributed = [];
					for ($i = 0; $i < $total_strategic_plan_years; $i++) {
						$query_finyearamtgrants = $db->prepare("SELECT sum(amount) AS totalamt FROM tbl_financiers f  INNER JOIN tbl_financier_type t on f.type = t.id  INNER JOIN tbl_funding_type s on s.category = t.id INNER JOIN tbl_funds p on p.funder = f.id WHERE t.id = '$financier_id' AND p.financial_year=:financial_year_id");
						$query_finyearamtgrants->execute(array(":financial_year_id" => $start_year));
						$row_finyearamtgrants = $query_finyearamtgrants->fetch();
						$total_amount = !is_null($row_finyearamtgrants["totalamt"]) ? $row_finyearamtgrants["totalamt"] : 0;
						array_push($amount_contributed, $total_amount);
						$start_year++;
					}
					$information = array("name" => (string)$organization, "data" => $amount_contributed);
					array_push($data, $information);
				}
			}
			return json_encode($data);
		}

		function budget_vs_expenditure_per_year()
		{
			global $db, $strategic_plan_start_year, $total_strategic_plan_years;
			$start_year = $strategic_plan_start_year;
			$totalannualbdg =  $totalannualexp = [];
			for ($i = 0; $i < $total_strategic_plan_years; $i++) {
				$end_year = $start_year + 1;
				$start_date = date("Y-m-d", strtotime($start_year . "07-01"));
				$end_date = date("Y-m-d", strtotime($end_year . "06-30"));
				$query_crfinyearalloc = $db->prepare("SELECT sum(b.budget) as totalamt FROM tbl_programs_based_budget b inner join tbl_programs g on g.progid=b.progid WHERE g.program_type=1 AND b.finyear=:finyear");
				$query_crfinyearalloc->execute(array(":finyear" => $start_year));
				$row_crfinyearalloc = $query_crfinyearalloc->fetch();
				$totalannualbudget =  !is_null($row_crfinyearalloc["totalamt"]) ? $row_crfinyearalloc["totalamt"] : 0;

				$query_annualexpenditure = $db->prepare("SELECT sum(amount_requested) AS totalexpend FROM tbl_payments_request r inner join tbl_payments_disbursed d on d.request_id=r.request_id inner join tbl_projects p on p.projid=r.projid inner join tbl_programs g on g.progid=p.progid WHERE g.program_type=1 AND r.status=3 AND r.date_requested >=:start_date AND r.date_requested <=:end_date");
				$query_annualexpenditure->execute(array(":start_date" => $start_date, ":end_date" => $end_date));
				$row_annualexpenditure = $query_annualexpenditure->fetch();
				$totalannualexpenditure = !is_null($row_annualexpenditure["totalexpend"]) ? $row_annualexpenditure["totalexpend"] : 0;

				$query_annual_contractor_expenditure = $db->prepare("SELECT sum(requested_amount) AS totalexpend FROM tbl_contractor_payment_requests r inner join tbl_payments_disbursed d on d.request_id=r.request_id inner join tbl_projects p on p.projid=r.projid inner join tbl_programs g on g.progid=p.progid WHERE g.program_type=1 AND r.status=3 AND r.created_at >=:start_date AND r.created_at <= :end_date");
				$query_annual_contractor_expenditure->execute(array(":start_date" => $start_date, ":end_date" => $end_date));
				$row_annual_contractor_expenditure = $query_annual_contractor_expenditure->fetch();
				$total_annual_contractor_expenditure = !is_null($row_annual_contractor_expenditure["totalexpend"]) ? $row_annual_contractor_expenditure["totalexpend"] : 0;
				$totalannualexpenditure = $totalannualexpenditure + $total_annual_contractor_expenditure;
				array_push($totalannualbdg, (float)$totalannualbudget);
				array_push($totalannualexp, (float)$totalannualexpenditure);
				$start_year++;
			}
			return array("totalannualbdg" => $totalannualbdg, "totalannualexp" => $totalannualexp);
		}
		function department_utilization_rate()
		{
			global $db, $strategic_plan_start_year, $total_strategic_plan_years;
			$start_year = $strategic_plan_start_year;
			$query_depertments = $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent IS NOT NULL");
			$query_depertments->execute();
			$total_departments = $query_depertments->rowCount();
			$department_utilization_rate = [];

			if ($total_departments > 0) {
				while ($row_departments = $query_depertments->fetch()) {
					$stid = $row_departments['stid'];
					$sector = $row_departments['sector'];
					$utilization_rate = [];
					for ($i = 0; $i < $total_strategic_plan_years; $i++) {
						$end_year = $start_year + 1;
						$start_date = date("Y-m-d", strtotime($start_year . "07-01"));
						$end_date = date("Y-m-d", strtotime($end_year . "06-30"));
						$query_crfinyearalloc = $db->prepare("SELECT sum(b.budget) as totalamt FROM tbl_programs_based_budget b inner join tbl_programs g on g.progid=b.progid WHERE g.program_type=1 AND b.finyear=:finyear AND g.projsector=:sector_id");
						$query_crfinyearalloc->execute(array(":finyear" => $start_year, ":sector_id" => $stid));
						$row_crfinyearalloc = $query_crfinyearalloc->fetch();
						$totalannualbudget =  !is_null($row_crfinyearalloc["totalamt"]) ? $row_crfinyearalloc["totalamt"] : 0;

						$query_annualexpenditure = $db->prepare("SELECT sum(amount_requested) AS totalexpend FROM tbl_payments_request r inner join tbl_payments_disbursed d on d.request_id=r.request_id inner join tbl_projects p on p.projid=r.projid inner join tbl_programs g on g.progid=p.progid WHERE g.program_type=1 AND r.status=3 AND g.projsector=:sector_id AND r.date_requested >=:start_date AND r.date_requested <=:end_date");
						$query_annualexpenditure->execute(array(":sector_id" => $stid, ":start_date" => $start_date, ":end_date" => $end_date));
						$row_annualexpenditure = $query_annualexpenditure->fetch();
						$totalannualexpenditure = !is_null($row_annualexpenditure["totalexpend"]) ? $row_annualexpenditure["totalexpend"] : 0;

						$query_annual_contractor_expenditure = $db->prepare("SELECT sum(requested_amount) AS totalexpend FROM tbl_contractor_payment_requests r inner join tbl_payments_disbursed d on d.request_id=r.request_id inner join tbl_projects p on p.projid=r.projid inner join tbl_programs g on g.progid=p.progid WHERE g.program_type=1 AND r.status=3 AND g.projsector=:sector_id AND r.created_at >=:start_date AND r.created_at <= :end_date");
						$query_annual_contractor_expenditure->execute(array(":sector_id" => $stid, ":start_date" => $start_date, ":end_date" => $end_date));
						$row_annual_contractor_expenditure = $query_annual_contractor_expenditure->fetch();
						$total_annual_contractor_expenditure = !is_null($row_annual_contractor_expenditure["totalexpend"]) ? $row_annual_contractor_expenditure["totalexpend"] : 0;
						$totalannualexpenditure = $totalannualexpenditure + $total_annual_contractor_expenditure;

						$expenditure_rate = $totalannualbudget > 0 && $totalannualexpenditure ? (($totalannualexpenditure / $totalannualbudget) * 100) : 0;
						array_push($utilization_rate, round($expenditure_rate, 2));
						$start_year++;
					}
					$information = array("name" => (string)$sector, "data" => $utilization_rate);
					array_push($department_utilization_rate, $information);
				}
			}
			return json_encode($department_utilization_rate);
		}

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
		$funds_details = fund_sources();

		$current_financial_year_budget = current_financial_year($current_year);
		$last_financial_year_budget = previous_financial_year($last_year);
		$department_utilization = department_utilization_rate();


		$budget_expenditure = budget_vs_expenditure_per_year();
		$totalannualbdg = json_encode($budget_expenditure['totalannualbdg']);
		$totalannualexp = json_encode($budget_expenditure['totalannualexp']);

	
 
	} catch (PDOException $ex) {
		$result = flashMessage("An error occurred: " . $ex->getMessage());
		// print($result);
		var_dump($ex->getMessage());
	}
?>
	<!-- start body  -->
	<section class="content">
		<div class="container-fluid">
			<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
				<h4 class="contentheader">
					<?= $icon ?>
					<?= $pageTitle ?> 
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
											<div class="text"><?= $prevfinyear ?> FIN YEAR
												<h5 class="font-light" style="color:black"><i><strong>B:</strong> Ksh.<?= $last_financial_year_budget['totalpvfybudget'] ?></i><br><i style="color:white"><strong>A:</strong> Ksh.<?= $last_financial_year_budget['totalpvfinyearalloc'] ?></i></h5>
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
										if ($last_financial_year_budget['totalpvfinyearallocation'] > $current_financial_year_budget['totalcrfinyearallocation']) {
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
											<div class="text"><?= $currfinyear ?> FIN YEAR
												<h5 class="font-light" style="color:black"><i><strong>B:</strong> Ksh.<?= $current_financial_year_budget['totalcrfybudget'] ?></i><br><i style="color:white"><strong>A:</strong> Ksh.<?= $current_financial_year_budget['totalcrfinyearamt'] ?></i></h5>
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