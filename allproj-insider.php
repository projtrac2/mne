<div class="tab-content"> 
	<div id="home" class="tab-pane fade in active">
        <div class="body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
						<?php //if(!empty($row_rsUpP['sdate']) || $row_rsUpP['sdate'] != ''){ ?>
							<tr id="colrow">
								<th width="4%"><strong>SN</strong></th>
								<th width="28%"><strong>Project</strong></th>
								<th width="12%"><strong><?=$departmentlabel?></strong></th>
								<th width="10%"><strong>Status & Progress(%)</strong></th>
								<th width="7%"><strong>Issues</strong></th>
								<th width="9%"><strong>Location</strong></th>
								<th width="10%"><strong>Fiscal Year</strong></th>
								<th width="10%"><strong>Scorecard</strong></th>
								<th width="10%"><strong>Last Update</strong></th> <!--COLSPAN=4-- 
								<th width="8%"><strong>Details</strong></th> <!--COLSPAN=4--> 
							</tr>
						<?php //}else{ ?>
							<!--<tr id="colrow">
								<th width="4%"><strong>SN</strong></th>
								<th width="50%"><strong>Project</strong></th>
								<th width="30%"><strong><?//=$departmentlabel?></strong></th>
								<th width="16%"><strong>Fiscal Year</strong></th>
							</tr>-->
						<?php //} ?>
                    </thead>
					<tbody>
					<!-- =========================================== -->
					<?php  include_once('allprojects-code.php');?>
                    </tbody>
				</table>
			</div>
		</div>
	</div>
	<div id="menu1" class="tab-pane fade">
        <div class="body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
						<?php //if(!empty($row_indProjs['sdate']) || $row_indProjs['sdate'] != ''){ ?>
							<tr id="colrow">
								<th width="4%"><strong>SN</strong></th>
								<th width="28%"><strong>Project</strong></th>
								<th width="12%"><strong><?=$departmentlabel?></strong></th>
								<th width="10%"><strong>Status & Progress(%)</strong></th>
								<th width="7%"><strong>Issues</strong></th>
								<th width="9%"><strong>Location</strong></th>
								<th width="10%"><strong>Fiscal Year</strong></th>
								<th width="10%"><strong>Scorecard</strong></th>
								<th width="10%"><strong>Last Update</strong></th> <!--COLSPAN=4-- 
								<th width="8%"><strong>Details</strong></th> <!--COLSPAN=4--> 
							</tr>
						<?php //}else{ ?>
							<!--<tr id="colrow">
								<th width="4%"><strong>SN</strong></th>
								<th width="50%"><strong>Project</strong></th>
								<th width="30%"><strong><?//=$departmentlabel?></strong></th>
								<th width="16%"><strong>Fiscal Year</strong></th>
							</tr>-->
						<?php //} ?>
                    </thead>
					<tbody>
					<!-- =========================================== -->
					<?php  include_once('all-ind-projects.php');?>
                    </tbody>
				</table>
			</div>
		</div>
	</div>
</div>