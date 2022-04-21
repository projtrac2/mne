<?php
include_once "controller.php";

if(isset($_POST['msid'])) 
{
	$msid = $_POST["msid"];
								
	$query_rsPMembers =  $db->prepare("SELECT tbl_projteam2.*, tbl_projmembers.pmid  FROM tbl_projteam2 LEFT JOIN tbl_projmembers ON tbl_projteam2.ptid=tbl_projmembers.ptid LEFT JOIN tbl_milestone ON tbl_projmembers.projid = tbl_milestone.projid WHERE tbl_milestone.msid = '$msid' ORDER BY tbl_projmembers.pmid ASC");
	$query_rsPMembers->execute();		
	$row_rsPMembers = $query_rsPMembers->fetch();
	
	$current_date = date("Y-m-d");

	echo '<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="body">
						<div class="table-responsive">
							<div class="col-md-12">
								<label>Inspector Officer *:</label>
								<div class="form-line">
									<select name="inspector" id="inspector" class="form-control show-tick" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px" required>
										<option value="" selected="selected" class="selection">Select Inspector Officer</option>';
										do {  
											echo '<option value="'.$row_rsPMembers['ptid'].'">'.$row_rsPMembers['title'].". ".$row_rsPMembers['fullname'].'</option>';
										} while ($row_rsPMembers = $query_rsPMembers->fetch());
									echo '</select>
								</div>
							</div>
							<input name="msid" type="hidden" value="'.$msid.'">
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
					</div>
				</div>
			</div>
		</div>';
}
?>