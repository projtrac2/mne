<section class="content" style="margin-top:-20px; padding-bottom:0px">
	<div class="container-fluid">
		<div class="block-header">
			<?php
			echo $results;
			?>
			<h4 class="contentheader"><i class="fa fa-plus-square" aria-hidden="true"></i> Project Locations</h4>
			<div>
			</div>
		</div>
		<!-- Draggable Handles -->
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="body" style="margin-top:5px">
						<form role="form" id="<?=$formName?>" name="<?=$formName?>" action="" method="post" autocomplete="off" enctype="multipart/form-data">
							<fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
								<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><?php echo $action;?> Location (If adding Level-2/Level-3, first Select Parent Location .i.e Level-1/Level-2)</legend>
								<div class="col-md-3">
								</div>
								<div class="col-md-6">
									<label for="projduration">Location Name *:</label><span id="dept" style="color:darkgoldenrod"></span>
									<div class="form-line">
										<div class="locations">
											<input type="text" name="location" class="form-control" id="location" value="<?php echo htmlentities($locname); ?>" required="required" style="height:35px; width:98%"/>
										</div>
										<div class="myward2"></div>
									</div>
								</div>
								<div class="col-md-3">
								</div>
								<div class="col-md-12">
									<ul class="list-inline" align="center">
										<li>
											<input name="user_name" type="hidden" id="user_name" value="<?php echo $username; ?>" />
											<input name="<?=$submitValue?>" type="submit" class="btn btn-success" id="submit" value="<?=$submitValue?>" style="margin-bottom:10px"/>
											<input type="hidden" name="<?=$submitAction?>" value="<?=$formName?>" />
										</li>
									</ul>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="body" style="margin-top:5px">
						<fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
							<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Locations List</legend>
							<div class="row clearfix">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="col-md-12 table-responsive">
										<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
											<thead>
												<tr id="colrow">
													<th width="10%" height="35"><div align="center"><strong id="colhead">SN</strong></div></th>
													<th width="80%"><div align="center"><strong id="colhead">Level-1/Level-2/Level-3 Name</strong></div></th>
													<th colspan="2" align="center">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php 
												$sn = 0;
												do { 
													$sn++;
													?>
													<tr id="rowlines" style="background-color:#e8eef7">
														<td width="10%" height="35"><div align="center"><?php echo $sn; ?></div></td>
														<td width="80%"><div align="left">&nbsp;&nbsp;<?php echo $row_rsAllLocations['state']; ?></div></td>
														<td width="5%"><div align="center"><a href="editlocations?edit=1&amp;stid=<?php echo $row_rsAllLocations['id']; ?>"><img src="images/edit.png" alt="edit" /></a></div></td>
														<td width="5%" height="35"><div align="center"><a href="locations?del=1&amp;stid=<?php echo $row_rsAllLocations['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?')"><img src="images/delete.png" alt="del" title="Delete This Sub County" /></div></td>
													</tr>
													<?php
													$ward = $row_rsAllLocations['id'];
										
													$query_rsAllWards = $db->prepare("SELECT * FROM `tbl_state` WHERE location='0' and parent='$ward' ORDER BY id ASC");
													$query_rsAllWards->execute();
													$row_rsAllWards = $query_rsAllWards->fetch();
													
													do { ?>
														<tr id="rowlines" style="background-color:#f9fbfc; border-bottom:#000 thin dashed">
														  <td width="10%" height="35"><div align="center"><b> . </b></div></td>
														  <td width="80%"><div align="left">&nbsp;&nbsp;-- <?php echo $row_rsAllWards['state']; ?></div></td>
														<td width="5%"><div align="center"><a href="editlocations?edit=1&amp;stid=<?php echo $row_rsAllWards['id']; ?>"><img src="images/edit.png" alt="edit" /></a></div></td>
														<td width="5%" height="35"><div align="center"><a href="locations?del=1&amp;stid=<?php echo $row_rsAllWards['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?')"><img src="images/delete.png" alt="del" title="Delete This Level-2" /></div></td>
														</tr>
														<?php
														$loc = $row_rsAllWards['id'];
														
														$query_rsAllLocs = $db->prepare("SELECT * FROM `tbl_state` WHERE location='1' and parent='$loc' ORDER BY id ASC");
														$query_rsAllLocs->execute();
														$row_rsAllLocs = $query_rsAllLocs->fetch();
														do { 
															if(empty($row_rsAllLocs['state']) || $row_rsAllLocs['state']==''){
																$projloc = "Location Not Defined";
															}else{
																$projloc = $row_rsAllLocs['state'];
															}
															?>
															
															<tr id="rowlines">
															  <td width="10%" height="35"><div align="center"><b> . </b></div></td>
															  <td width="80%"><div align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;---- <?php echo $projloc; ?></div></td>
															<td width="5%"><div align="center"><a href="editlocations?edit=1&amp;stid=<?php echo $row_rsAllLocs['id']; ?>"><img src="images/edit.png" alt="edit" /></a></div></td>
															<td width="5%" height="35"><div align="center"><a href="locations?del=1&amp;stid=<?php echo $row_rsAllLocs['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?')"><img src="images/delete.png" alt="del" title="Delete This Location" /></div></td>
															</tr>
														<?php } while ($row_rsAllLocs = $query_rsAllLocs->fetch()); ?>
													<?php } while ($row_rsAllWards = $query_rsAllWards->fetch()); ?>
												<?php } while ($row_rsAllLocations = $query_rsAllLocations->fetch()); ?>									
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
		</div>	
	</div>
</section>

<!-- End Item Edit -->