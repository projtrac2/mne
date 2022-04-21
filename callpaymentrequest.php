<?php

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

if(isset($_POST['tskid'])) 
{
	$tkid = $_POST["tskid"];
	//$progress = $_POST["scprog"];
	$query_task = $db->prepare("SELECT * FROM tbl_task WHERE tkid='$tkid'");
	$query_task->execute();
	$rowdata = $query_task->fetch();
	$current_date = date("Y-m-d H:i:s");
	$rd2 = mt_rand(100,499); 
	$ref = 'TSK/'. date('y').'/'.$rd2.$tkid;
	$taskbudget =  number_format($rowdata['taskbudget'], 2);
	
	echo '<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="body">
						<div class="table-responsive">
						<div class="col-sm-12 inputGroupContainer" style="align:center">
								<table class="table table-bordered">
									<h4><font color="#174082">Task:</font><font color="#000"> '.$rowdata['task'].'</font></h4>
									<tr>
										<th width="15%"><font color="#174082">Task Cost:</font></th>
										<td width="85%"><font color="#000">Ksh.'.$taskbudget.'</font></td>
									</tr>
								</table>
						</div>
							<div class="form-group">
								<div class="col-sm-12 inputGroupContainer">
									<div class="input-group">
										<div style="margin-bottom:5px"><font color="#174082"><strong>Comments: </strong></font></div>                 
										<textarea name="comments" id="comments" cols="60" rows="5" style="font-size:13px; color:#000; width:99.5%"></textarea>
										<script>
											CKEDITOR.replace( "comments",
												{
													height: "150px",
													toolbar :
															[
														{ name: "clipboard", items : [ "Cut","Copy","Paste","PasteText","PasteFromWord","-","Undo","Redo" ] },
														{ name: "editing", items : [ "Find","Replace","-","SelectAll","-","Scayt" ] },
														{ name: "insert", items : [ "Image","Flash","Table","HorizontalRule","Smiley","SpecialChar","PageBreak"
															 ,"Iframe" ] },
															"/",
														{ name: "styles", items : [ "Styles","Format" ] },
														{ name: "basicstyles", items : [ "Bold","Italic","Strike","-","RemoveFormat" ] },
														{ name: "paragraph", items : [ "NumberedList","BulletedList","-","Outdent","Indent","-","Blockquote" ] },
														{ name: "links", items : [ "Link","Unlink","Anchor" ] },
														{ name: "tools", items : [ "Maximize","-","About" ] }
													]

												});
										</script>
									</div>
								</div>
							</div>
						</div>
						<input type="hidden" name="projid" id="projid" value="'.$rowdata['projid'].'"/>
						<input type="hidden" name="itemid" id="itemid" value="'.$tkid.'"/>
						<input type="hidden" name="cat" id="cat" value="1"/>
						<input type="hidden" name="amount" id="amount" value="'.$rowdata['taskbudget'].'"/>
						<input type="hidden" name="currentdate" id="currentdate" value="'.$current_date.'"/>
						<input type="hidden" name="refno" id="refno" value="'.$ref.'"/>
					</div>
				</div>
			</div>
		</div>';
}

if(isset($_POST['mstid'])) 
{
	$mstid = $_POST["mstid"];
	$amount = $_POST["amount"];

	$query_milestone = $db->prepare("SELECT * FROM tbl_milestone WHERE msid='$mstid'");
	$query_milestone->execute();
	$row = $query_milestone->fetch();	
	$current_date = date("Y-m-d H:i:s");
	$rd2 = mt_rand(500,999); 
	$ref = 'MST/'. date('y').'/'.$rd2.$mstid;
	
																					
	$query_rsMilestoneBudget =  $db->prepare("SELECT SUM(unit_cost) AS cost, SUM(units_no) AS units FROM tbl_project_tender_details c inner join tbl_task t ON t.tkid=c.tasks WHERE t.msid='$mstid'");
	$query_rsMilestoneBudget->execute();		
	$row_rsMilestoneBudget = $query_rsMilestoneBudget->fetch();
	$msitemscost = $row_rsMilestoneBudget['cost'];
	$msitems = $row_rsMilestoneBudget['units'];
	$mscost = $msitems * $msitemscost;
	$milestonebudget =  number_format($amount, 2);

	echo '<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="body">
						<div class="table-responsive">
							<div class="col-sm-12 inputGroupContainer" style="align:center">
								<font color="#174082">
									<table class="table table-bordered">
										<h4>Milestone: </font>
											<font color="#000">'.$row['milestone'].'</font></h4>
										<tr>
											<th width="15%">Milestone Cost:</font>
											</th>
											<td width="85%">
												<font color="#000">Ksh.'.$milestonebudget.'</font></td>
										</tr>
									</table>
							</div>
							<div class="form-group">
								<div class="col-sm-12 inputGroupContainer">
									<div class="input-group">
										<div style="margin-bottom:5px"><font color="#174082"><strong>Comments: </strong></font></div>                 
										<textarea name="comments" id="comments" cols="60" rows="5" style="font-size:13px; color:#000; width:99.5%; padding:10px"></textarea>
									</div>
								</div>
							</div>
						</div>
						<input type="hidden" name="projid" id="projid" value="'.$row['projid'].'"/>
						<input type="hidden" name="itemid" id="itemid" value="'.$mstid.'"/>
						<input type="hidden" name="cat" id="cat" value="2"/>
						<input type="hidden" name="amount" id="amount" value="'.$amount.'"/>
						<input type="hidden" name="currentdate" id="currentdate" value="'.$current_date.'"/>
						<input type="hidden" name="refno" id="refno" value="'.$ref.'"/>
					</div>
				</div>
			</div>
		</div>';
}
?>