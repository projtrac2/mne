function budget_vs_total_cost(totalbudget, totalcost) {
   Morris.Donut({
      element: 'budget_vs_total_cost',
      data: [
         {
            label: 'Total Cost',
            value: totalbudget
         },
         {
            label: 'Budget Allocation',
            value: totalcost,
         }],
      colors: ['rgb(0, 188, 212)', 'rgb(255, 152, 0)'],
      formatter: function (y) {
         return 'Ksh.' + (y).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
      }
   });
}

function projects_per_department(labels, data) {
   config = {
      type: 'radar',
      data: {
         labels: labels,
         datasets: [{
            label: "Project ",
            data: data,
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
   return new Chart(document.getElementById("projects_per_department").getContext("2d"), config)
}