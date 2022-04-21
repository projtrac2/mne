    <section class="content" style="margin-top:-20px; padding-bottom:0px">
        <div class="container-fluid">
            <div class="block-header">          
				<h4><i class="fa fa-list" aria-hidden="true"></i>  Monitor Project</h4>
            </div>
			<!--<div class="row clearfix outsideradius">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
                        <div class="alert bg-red message" style="font-size:16px"></div>
					</div>
				</div>
			</div>
			<div class="row clearfix withinradius">-->
			<div class="row clearfix">
				<div class="block-header">
				<?php 
					echo $results;
				?>
				</div>
				<!-- Advanced Form Example With Validation -->
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
					<?php
					//echo $totalRows_rsMyP;
					if($totalRows_rsMyP === 0){
					?>
                        <div class="header">
							<div style="color:#333; background-color:#EEE; width:100%; height:30px; padding-top:5px; padding-left:2px">
								<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-3px">
									<tr>
										<td width="50%" class="col-md-8" style="font-size:14px; font-weight:bold"><i class="fa fa-form" aria-hidden="true"></i>Project Monitoring Form</td>
										<td width="50%" class="col-md-4" style="font-size:12px; float:right">
											<a href="projects-monitoring" class="btn bg-orange waves-effect waves-light" style="height:25px">Close</a>
										</td>
									</tr>
								</table>
							</div>
                        </div>
					<?php
					}
					else{
					?>
                        <div class="body">
							<div style="color:#333; background-color:#EEE; width:98%; height:30px; padding-top:5px; padding-left:2px">
								<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-3px">
									<tr>
										<td width="50%" style="font-size:14px; font-weight:bold" align="center">
											<br><br><br><br>
											<font color="red" size="4">Sorry, you are not allowed to monitor this project!!!!</font>
											<br><br><br><br>
										</td>
									</tr>
								</table>
							</div>
						</div>
					<?php				
					}
					?>
                    </div>
                </div>
            </div>
            <!-- #END# Advanced Form Example With Validation -->
        </div>
    </section>