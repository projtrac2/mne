<script type="text/javascript">
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);
function drawChart() {
	var data = google.visualization.arrayToDataTable([
		['Label','Count'],
		<?php 
		$nb=0;
		$nb=$nb-1;
			foreach($data as $key=>$value){ 
			$nb=$nb+1;
				echo "['".$key."',".$value."],";
			}
		?> 
	]);
	var options = {
		title: '<?php echo $nb.". ".$row['label'];?>',
		is3D: true,
		pieSliceTextStyle: {
			color: 'black',
		}
	};
	var chart = new google.visualization.PieChart(document.getElementById("chart_div<?php echo $objid.$fieldid;?>"));
	chart.draw(data,options);
}
</script>