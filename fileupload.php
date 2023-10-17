	<script src="jquery.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.11.4/adapters/jquery.js"></script>
	<script language="javascript" type="text/javascript">
		$(document).ready(function() {
			$('#payment-receipt-form').on('submit', function(event) {
				event.preventDefault();
				var form_info = new FormData(this);
				form_info.append('file', $('#file')[0].files[0]);
				$.ajax({
					type: "POST",
					url: "savefiledata.php",
					data: form_info,
					dataType: "json",
					mimeType: 'multipart/form-data',
					cache: false,
					contentType: false,
					processData: false,
					success: function(response) {
						console.log(response);
						if (response == "true") {
							alert('Record successfully saved');
							window.location.reload();
						} else {
							alert('could not upload the file');
						}
					},
					error: function() {
						alert('Error');
					}
				});
				return false;
			});
		});
	</script>

	<!--  -->
	<form class="tagForm" action="" method="post" id="payment-receipt-form" enctype="multipart/form-data" autocomplete="off">
		<div class="modal-body">
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="body">
							<div class="table-responsive" style="background:#eaf0f9">
								<div id="receiveformcontent">
								</div>

								<div class="form-group">
									<label class="col-sm-4 control-label">
										<font color="#174082">Payment Release Date</font>
									</label>
									<div class="col-sm-6 inputGroupContainer">
										<div class="input-group date" id="bs_datepicker_component_container">
											<span class="input-group-addon"><i class="glyphicon glyphicon-th-list"></i></span>
											<input name="datepaid" type="text" title="d/m/Y" id="datepaid" class="form-control" style="border:#CCC thin solid; border-radius: 5px; padding-left:5px" placeholder="Click here to select date funds were release" />
										</div>
									</div>
								</div>

								<div class="form-group">
									<div class="col-sm-12 inputGroupContainer">
										<div class="input-group">
											<div style="margin-bottom:5px">
												<font color="#174082"><strong>Comments: </strong></font>
											</div>
											<textarea name="receivecomment" id="receivecomment" cols="60" rows="5" style="font-size:13px; color:#000; width:99.5%"></textarea>
											<script>
												// CKEDITOR.replace( "receivecomment",
												// 	{
												// 		height: "150px",
												// 		toolbar :
												// 				[
												// 			{ name: "clipboard", items : [ "Cut","Copy","Paste","PasteText","PasteFromWord","-","Undo","Redo" ] },
												// 			{ name: "editing", items : [ "Find","Replace","-","SelectAll","-","Scayt" ] },
												// 			{ name: "insert", items : [ "Image","Flash","Table","HorizontalRule","Smiley","SpecialChar","PageBreak"
												// 				 ,"Iframe" ] },
												// 				"/",
												// 			{ name: "styles", items : [ "Styles","Format" ] },
												// 			{ name: "basicstyles", items : [ "Bold","Italic","Strike","-","RemoveFormat" ] },
												// 			{ name: "paragraph", items : [ "NumberedList","BulletedList","-","Outdent","Indent","-","Blockquote" ] },
												// 			{ name: "links", items : [ "Link","Unlink","Anchor" ] },
												// 			{ name: "tools", items : [ "Maximize","-","About" ] }
												// 		]

												// 	});
											</script>
										</div>
									</div>
								</div>
								<div class="body">
									<table class="table table-bordered" id="funding_table">
										<tr>
											<th style="width:50%">Attachments</th>
										</tr>
										<tr>
											<td>
												<input type="file" name="file" id="file" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
		<div class="modal-footer">
			<div class="col-md-4">
			</div>
			<div class="col-md-4" align="center">
				<input type="hidden" name="username" id="username" value="<?php
																			echo 20
																			?>" />
				<input name="save" type="submit" class="btn btn-success waves-effect waves-light" id="tag-form-submit" value="Save" />
				<button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>

			</div>
			<div class="col-md-4">
			</div>
		</div>
	</form>