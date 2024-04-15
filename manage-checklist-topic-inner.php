
<?php 
try {
	//code...

?>
			<div class="block-header" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; background-color:#000; color:#FFF">
				<h4 class="contentheader"><i class="fa fa-tachometer" aria-hidden="true"></i> Projects Inspection Checklists Topics
				</h4>
            </div>
                        <div class="body">
							<div style="margin-top:5px">
								<form id="<?php echo $formvalue; ?>" method="POST" name="<?php echo $formvalue; ?>" action="<?php echo $editFormAction; ?>" enctype="multipart/form-data" autocomplete="off">
									
									<fieldset class="scheduler-border">
										<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-plus-square" aria-hidden="true"></i> Add New Checklist Topic</legend>
										<div class="col-md-12">
											<label>Topic *:</label>
											<div class="form-line">
												<input type="text" class="form-control" name="topic" id="topic" value="<?php echo $row_edittopic["topic"]?>" style="border:#CCC thin solid; border-radius: 5px" required">
											</div>
										</div>
										<div  class="col-md-12">
											<label>Description :</label>
											<div>
												<textarea name="desc" class="form-control" id="desc" style="height:100px; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" ><?php echo $row_edittopic["description"]?></textarea>
											</div>
										</div>
										<div class="row clearfix">
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
											</div>
											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
												<input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
												<?php if($topicid){?>
												<input name="topicid" type="hidden" id="topicid" value="<?php echo $topicid; ?>" />
												<?php }?>
												<div class="btn-group">
													<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="<?php echo $mbraction; ?>" />
												</div>
												<input type="hidden" name="<?php echo $formname; ?>" value="<?php echo $formvalue; ?>" />
											</div>
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
											</div>
										</div>
									</fieldset>
								</form>
							</div>
                        </div>
                        <div class="body">
							<fieldset class="scheduler-border">
								<legend  class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><img src="images/indicator.png" alt="task" /> All Topics</legend>
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
										<thead>
											<tr id="colrow">
												<td width="5%"><strong id="colhead">SN</strong></td>
												<td width="28%"><strong id="colhead">Topic</strong></td>
												<td width="60%"><strong id="colhead">Description</strong></td>
												<td width="7%"><strong id="colhead">Action</strong></td>
											</tr>
										</thead>
										<tbody><?php
										if($totalRows_topics == 0){
											?>
											<tr>
												<td  colspan="9"><div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div></td>
											</tr>
										<?php }
										else{ 
											$sn = 0;
											do { 
												$sn = $sn + 1;
												?>
												<tr id="rowlines">
													<td><?php echo $sn; ?></td>
													<td><?php echo $row_topics['topic']; ?></div></td>
													<td><?php echo $row_topics['description']; ?></div></td>
													<td><a href="manage-checklist-topic?tp_id=<?php echo $row_topics['id']; ?>"><img src="images/edit.png" width="16" height="16" alt="Edit"  title="Edit Topic"/></a>&nbsp;&nbsp;<a href="manage-checklist-topic?del=1&tp_id=<?php echo $row_topics['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?')"><img src="images/delete.png" width="16" height="16" alt="Delete"  title="Delete Topic"/></a></div></td>
												</tr>
											<?php } while ($row_topics = $query_topics->fetch());
										}
										?>
										</tbody>
									</table>
								</div>
							</fieldset>
                        </div>
<?php 

} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>