const ajax_url = "./ajax/dashboard/index";

$(document).ready(function () {
   budget();
   fund_sources(funds_details, financial_years);
   budget_vs_expenditure();
   draw_issue_status_chart();

   draw_risks();

   google.charts.load("current", {
      packages: ["corechart"]
   });

   google.charts.setOnLoadCallback(drawChart);
   google.charts.setOnLoadCallback(chartdraw);
});

// get financial year to
function get_to_financial_years() {
   var financial_year_from_id = $("#financial_year_from_id").val();
   var level_one_id = $("#level_one_id").val();
   var level_two_id = $("#level_two_id").val();
   // if (financial_year_from_id != "") {
      $.ajax({
         type: "get",
         url: ajax_url,
         data: {
            get_to_financial_year: "get_to_financial_year",
            financial_year_from_id: financial_year_from_id,
            level_one_id: level_one_id,
            level_two_id: level_two_id
         },
         dataType: "json",
         success: function (response) {
            if (response.success) {
               $("#financial_year_to_id").html(response.financial_years);
               set_details(response.project_details, response.dashboard_projects_url);

            } else {
               error_alert("Sorry could not find any financial years")
            }
         },
      });
   // }
}

// get level 2
function get_level_two() {
   var level_one_id = $("#level_one_id").val();
   var financial_year_from_id = $("#financial_year_from_id").val();
   // if (level_one_id != "") {
      $.ajax({
         type: "get",
         url: ajax_url,
         data: {
            get_level2: "get_level2",
            financial_year_from_id: financial_year_from_id,
            level_one_id: level_one_id
         },
         dataType: "json",
         success: function (response) {
            if (response.success) {
               $("#level_two_id").html(response.level_two);
               set_details(response.project_details, response.dashboard_projects_url);

            } else {
               error_alert("Sorry could not find any level two")
            }
         },
      });
   // }
}

// get details
function get_project_details() {
   var financial_year_from_id = $("#financial_year_from_id").val();
   var financial_year_to_id = $("#financial_year_to_id").val();
   var level_one_id = $("#level_one_id").val();
   var level_two_id = $("#level_two_id").val();
   // if (financial_year_from_id != "") {
   $.ajax({
      type: "get",
      url: ajax_url,
      data: {
         get_project_details: "get_project_details",
         financial_year_from_id: financial_year_from_id,
         financial_year_to_id: financial_year_to_id,
         level_one_id: level_one_id,
         level_two_id: level_two_id
      },
      dataType: "json",
      success: function (response) {
         if (response.success) {
            $("#financial_year_to_id").html(response.financial_years);
            set_details(response.project_details, response.dashboard_projects_url);

         } else {
            error_alert("Sorry could not find any financial years")
         }
      },
   });
   // }
}

function set_details(details, projects_link) {
   $("#total").html(details.all);
   $("#complete").html(details.complete);
   $("#on_track").html(details.on_track);
   $("#pending").html(details.pending);
   $("#behind_schedule").html(details.behind_schedule);
   $("#awaiting_procurement").html(details.awaiting_procurement);
   $("#on_hold").html(details.on_hold);
   $("#cancelled").html(details.cancelled);

   $("#total_link").attr("href", `view-dashboard-projects.php?prjstatus=all${projects_link}`);
   $("#complete_link").attr("href", `view-dashboard-projects.php?prjstatus=complete${projects_link}`);
   $("#on_track_link").attr("href", `view-dashboard-projects.php?prjstatus=on-track${projects_link}`);
   $("#pending_link").attr("href", `view-dashboard-projects.php?prjstatus=pending${projects_link}`);
   $("#behind_schedule_link").attr("href", `view-dashboard-projects.php?prjstatus=behind-schedule${projects_link}`);
   $("#awaiting_procurement_link").attr("href", `view-dashboard-projects.php?prjstatus=awaiting-procurement${projects_link}`);
   $("#on_hold_link").attr("href", `view-dashboard-projects.php?prjstatus=on-hold${projects_link}`);
   $("#cancelled_link").attr("href", `view-dashboard-projects.php?prjstatus=cancelled${projects_link}`);
}


// issue Status
function draw_issue_status_chart() {
   Highcharts.chart('container', {
      chart: {
         type: 'column'
      },
      title: {
         text: '',
         align: 'center'
      },
      xAxis: {
         categories: financial_years,
         crosshair: true,
         accessibility: {
            description: 'Financial Years'
         }
      },
      yAxis: {
         min: 0,
      },
      plotOptions: {
         column: {
            pointPadding: 0.2,
            borderWidth: 0
         },
         series: {
            cursor: 'pointer',
            point: {
               events: {
                  click: function () {
                     var data_options = this.options;
                     location.href = `view-dashboard-issues.php?status=${data_options.key}&year=${data_options.year}`;
                  }
               }
            }
         }
      },
      series: issue_status,
   });
}

// fund sources
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
         categories: years,
      },
      tooltip: {
         y: {
            formatter: function (val) {
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

//financial years budget absorption rate
function budget_vs_expenditure() {
   var myChart = echarts.init(document.getElementById('budget_vs_expenditure'));
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
   myChart.setOption(option, true), $(function () {
      function resize() {
         setTimeout(function () {
            myChart.resize()
         }, 100)
      }

      $(window).on("resize", resize), $(".sidebartoggler").on("click", resize)
   });
}

// absorption per department
function budget() {
   var options = {
      series: [{
         name: 'Actual',
         data: budget_data
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
            formatter: (value) => value + '%',
         },
      },
      tooltip: {
         enabled: true,
         enabledOnSeries: undefined,
         shared: true,
         followCursor: false,
         intersect: false,
         inverseOrder: false,
         custom: undefined,
         hideEmptySeries: true,
         fillSeriesColor: false,
         theme: false,
         style: {
            fontSize: '12px',
         },
         onDatasetHover: {
            highlightDataSeries: false,
         },
         x: {
            show: true,
            format: 'dd MMM',
         },
         y: {
            formatter: undefined,
            title: {
               formatter: (seriesName) => seriesName,
            },
         },
         z: {
            formatter: undefined,
            title: 'Size: '
         },
         marker: {
            show: true,
         },
         items: {
            display: 'flex',
         },
         fixed: {
            enabled: false,
            position: 'topRight',
            offsetX: 0,
            offsetY: 0,
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
         position: 'top',
         markers: {
            fillColors: ['#00E396', '#775DD0']
         }
      }
   };
   var chart = new ApexCharts(document.querySelector("#cost_vs_budget"), options);
   chart.render();
}


function drawChart() {
   var data = google.visualization.arrayToDataTable(tender_projects);
   var options = {
      title: 'Distribution of projects per tender category (Number & Percentage)',
      pieHole: 0.4,
   };

   var chart = new google.visualization.PieChart(document.getElementById('proj_no_tender_category'));
   return chart.draw(data, options);
}


function chartdraw() {
   var data = google.visualization.arrayToDataTable(tender_cost);
   var options = {
      title: 'Distribution Projects cost per tender category (Ksh & Percentage)',
      is3D: true,
   };
   var chart = new google.visualization.PieChart(document.getElementById('proj_amt_tender_category'));
   return chart.draw(data, options);
}

function draw_risks() {
   // Substring template helper for the responsive labels
   Highcharts.Templating.helpers.substr = (s, from, length) =>
      s.substr(from, length);

   // Create the chart
   Highcharts.chart('container2', {
      chart: {
         type: 'heatmap',
         marginTop: 40,
         marginBottom: 80,
         plotBorderWidth: 1,
      },

      title: {
         text: null,
         style: {
            fontSize: '1em'
         }
      },

      xAxis: {
         categories: ['Negligible (1)', 'Minor (2)', 'Moderate (3)', 'Major (4)', 'Catastrophic (5)'],
         title: 'Impact',
      },

      yAxis: {
         categories: ['Rare (1)', 'Unlikely (2)', 'Possible (3)', 'Likely (4)', 'Very Likely (5)'],
         title: 'Likelihood',
         reversed: false
      },
      accessibility: {
         point: {
            descriptionFormat: '{(add index 1)}. ' +
               '{series.xAxis.categories.(x)} projects ' +
               '{series.yAxis.categories.(y)}, {value}.'
         }
      },
      colors: ['rgb(13, 163, 35)', 'rgb(173,255,47)', 'rgb(255,140,0)', 'rgb(255,0,0)'],
      colorAxis: {
         gridLineColor: '#ffffff',
         dataClassColor: 'category',
         dataClasses: [{
            name: 'Low (1-3)',
            from: 1,
            to: 3,
         }, {
            name: 'Moderate (4-6)',
            from: 4,
            to: 6
         }, {
            name: 'High (8-12)',
            from: 8,
            to: 12
         }, {
            name: 'Extreme (15-25)',
            from: 15,
            to: 25
         }]
      },
      plotOptions: {
         series: {
            point: {
               events: {
                  mouseOver: function () {
                     if (!this.dynamicData) {
                        var impact = this.x + 1;
                        var likelihood = this.y + 1;
                        fetch(`${ajax_url}?get_tooltip=get_tooltip&risk_level=${this.value}&impact=${impact}&likelihood=${likelihood}`)
                           .then(data => data.json())
                           .then(data => {
                              if (this.state === 'hover') {
                                 this.dynamicData = data;
                                 this.series.chart.tooltip.refresh(this);
                              }
                           }).catch(function (error) {
                              console.log('Request failed', error);
                           });
                     }
                  },
                  click: function () {
                     var impact = this.x + 1;
                     var likelihood = this.y + 1;
                     location.href = `view-dashboard-risks.php?risk_level=${this.value}&impact=${impact}&likelihood=${likelihood}`;
                  }
               }
            }
         }
      },

      tooltip: {
         formatter: function () {
            if (this.point.dynamicData) {
               return ` Risk Level: ${this.point.dynamicData.risk_severity}  <br/>Total Projects: ${this.point.dynamicData.tooltip}`
            }
            return 'Loading...';
         }
      },
      series: [{
         name: 'Risk Matrix',
         borderWidth: 1,
         borderColor: '#FFFFFF',
         data:
            [
               [0, 0, 1], [0, 1, 2], [0, 2, 3], [0, 3, 4], [0, 4, 5],
               [1, 0, 2], [1, 1, 4], [1, 2, 6], [1, 3, 8], [1, 4, 10],
               [2, 0, 3], [2, 1, 6], [2, 2, 9], [2, 3, 12], [2, 4, 15],
               [3, 0, 4], [3, 1, 8], [3, 2, 12], [3, 3, 16], [3, 4, 20],
               [4, 0, 5], [4, 1, 10], [4, 2, 15], [4, 3, 20], [4, 4, 25]
            ],
         dataLabels: {
            enabled: true,
            color: '#000000'
         }
      }],
      responsive: {
         rules: [{
            condition: {
               maxWidth: 500
            },
            chartOptions: {
               yAxis: {
                  labels: {
                     format: '{substr value 0 1}'
                  }
               }
            }
         }]
      }
   });
}