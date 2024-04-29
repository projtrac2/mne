// check if the input has already been selected 
$(document).on("change", "#indicator", function (e) {
    var indicator = $(this).val();
	var projectid = $("#projectid").val();
    if (indicator != "") {
        $.ajax({
            type: "post",
            url: "assets/processor/dashboard-processor",
            data: {project_indicators_dashboard: 1, indid: indicator, projid: projectid},
            dataType: "json",
            success: function (response) {
                $("#inddesc").val(response.inddescription);
                $("#unit").html(response.unitofmeasure);
                $("#duration").html(response.indduration);
                $("#progresstable").html(response.progresstable);
                $("#chart-area").html("");
			
				const el = document.getElementById('chart-area');
				const data = {
					categories: response.months,
					series: {
					  column: [
						{
						  name: 'Achieved',
						  data: response.achieved,
						},
					  ],
					  line: [
						{
						  name: 'Target',
						  data: response.targeted,
						},
					  ],
					},
				};
				const options = {
					chart: { title: 'Indicator Performance', width: 470, height: 400 },
					yAxis: { title: 'Units (' + response.unitofmeasure + ')'},
					xAxis: { title: 'Month' },
				};

				const chart = toastui.Chart.columnLineChart({ el, data, options });
						
            }
        });
    }
});

// check if the input has already been selected 
/* $(document).on("change", "#indicator", function (e) {
    var indicator = $(this).val();
	var projectid = $("#projectid").val();
    if (indicator != "") {
        $.ajax({
            type: "post",
            url: "assets/processor/dashboard-processor",
            data: {project_indicator_progress_table: 1, indid: indicator, projid: projectid},
            dataType: "html",
            success: function (response) {
                $("#progresstable").html(response);
            }
        });
    }
}); */

