 <!--<div class="clearfix m-b-20">
    <div class="content" style="margin-top:-10px">-->
<div class="body">
	<!-- ============================================================== -->
	<!-- Start Page Content -->
	<!-- ============================================================== -->
	<div class="row">
		<!-- column -->
		<div class="col-md-12">
			<div class="card">
				<div class="header">
					<h4 class="card-title" style="margin:10px">Strategic Objectives' Planned Vs Achieved Outputs Per Year</h4> 
					<ul class="header-dropdown m-r--5">
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								<i class="material-icons">more_vert</i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a href="javascript:void(0);">Action</a></li>
								<li><a href="javascript:void(0);">Another action</a></li>
								<li><a href="javascript:void(0);">Something else here</a></li>
							</ul>
						</li>
					</ul>
				</div>
				<div class="card-body" style="padding-top:10px">
				<?php if(isset($_GET['obj']) && isset($_GET['output'])){ ?>
					<div class="col-md-4" align="right"><u><strong>Objective: <font color="#2196F3"><?=$objname?> </font></strong></u></div><div class="col-md-4" align="center"><u><strong>Output: <font color="#2196F3"><?=$objoutput?> </font></strong></u></div><div class="col-md-4"> <u><strong>Indicator: <font color="#2196F3"><?=$objopind?> </font></strong></u></div>
				<?php	} else{?>
				<div class="col-md-6" align="right"><u><strong>All Objectives</strong></u></div><div class="col-md-6"><u><strong>All Outputs</strong></u></div>
				<?php	} ?>
					<div id="bar-chart" style="width:100%; height:400px;" align="center"></div>
				</div>
			</div>
		</div>
		<!-- column -->
		<!-- column -->
		<div class="col-md-12">
			<div class="card">
				<div class="header">
					<h4 class="card-title" style="margin:10px">Strategic Objectives' Output Budget Vs Cost Per Year</h4>
					<ul class="header-dropdown m-r--5">
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								<i class="material-icons">more_vert</i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a href="javascript:void(0);">Action</a></li>
								<li><a href="javascript:void(0);">Another action</a></li>
								<li><a href="javascript:void(0);">Something else here</a></li>
							</ul>
						</li>
					</ul>
				</div>
				<div class="card-body" style="padding-top:10px">
					<div id="main" style="width:100%; height:400px;"></div>
				</div>
			</div>
		</div>
		<!-- column -->
	</div>
	<!-- ============================================================== -->
	<!-- End PAge Content -->
	<!-- ============================================================== -->
</div>