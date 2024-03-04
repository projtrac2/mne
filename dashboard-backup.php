<?php
require('includes/head.php');
if ($permission) {
    try {

        $query_stratplan =  $db->prepare("SELECT * FROM tbl_strategicplan WHERE current_plan=1");
        $query_stratplan->execute();
        $row_stratplan = $query_stratplan->fetch();
        $totalRows_stratplan = $query_stratplan->rowCount();

        $stplan = $plan = $vision = $mission = $noyears = $styear =  $total_strategic_plan_years = $strategic_plan_start_year = 0;
        $financial_year_details = "";
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




    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
?>







<script>
    $(document).ready(function() {
        projects_per_department();
        budget();
    });

    function budget() {
        var options = {
            series: [{
                name: 'Actual',
                data: [<?= $budget_data ?>]
            }],
            chart: {
                height: 500,
                type: 'bar'
            },
            plotOptions: {
                bar: {
                    columnWidth: '60%'
                }
            },
            yaxis: {
                forceNiceScale: false,
                max: 100,
                labels: {
                    formatter: (value) => value.toFixed(0) + '%',
                },
            },
            colors: ['#00E396'],
            dataLabels: {
                enabled: false
            },
            legend: {
                show: true,
                showForSingleSeries: true,
                customLegendItems: ['Actual', 'Expected'],
                markers: {
                    fillColors: ['#00E396', '#775DD0']
                }
            }
        };
        var chart = new ApexCharts(document.querySelector("#cost_vs_budget"), options);
        chart.render();
    }

    function projects_per_department() {
        var config = {
            type: 'radar',
            data: {
                labels: <?php echo departments(); ?>,
                datasets: [{
                    label: " Project",
                    data: <?php echo $project_distribution_data; ?>,
                    borderColor: 'rgba(0, 188, 212, 0.8)',
                    backgroundColor: 'rgba(0, 188, 212, 0.5)',
                    pointBorderColor: 'rgba(0, 188, 212, 0)',
                    pointBackgroundColor: 'rgba(0, 188, 212, 0.8)',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                legend: false
            }
        }
        new Chart(document.getElementById("radar_chart").getContext("2d"), config);
    }

    google.charts.load("current", {
        packages: ["corechart"]
    });
    google.charts.setOnLoadCallback(drawChart);


    function drawChart() {
        var data = google.visualization.arrayToDataTable(<?= $tender_projects ?>);
        var options = {
            title: 'Distribution of projects per tender category (Number & Percentage)',
            is3D: false,
            sliceVisibilityThreshold: 0
        };

        var chart = new google.visualization.PieChart(document.getElementById('proj_no_tender_category'));
        chart.draw(data, options);
    }

    google.charts.load("current", {
        packages: ["corechart"]
    });

    google.charts.setOnLoadCallback(chartdraw);

    function chartdraw() {
        var data = google.visualization.arrayToDataTable(<?= $tender_cost ?>);
        var options = {
            title: 'Distribution Projects cost per tender category (Ksh & Percentage)',
            is3D: false,
            sliceVisibilityThreshold: 0
        };
        var chart = new google.visualization.PieChart(document.getElementById('proj_amt_tender_category'));
        chart.draw(data, options);
    }


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
</script>