<div class="body">
	<div style="margin-top:5px">
		<form method="POST" name="addemailtemplate" action="" enctype="multipart/form-data" autocomplete="off">
			<?= csrf_token_html(); ?>
			<fieldset class="scheduler-border">
				<legend class="scheduler-border" style="background-color:#c7e1e8;  border:#CCC thin dashed; border-radius:3px">Template Details</legend>
				<div class="col-lg-12">
					<label style="margin-top: 10px;">Template Title <span style="color:red;font-size:15px;">*</span> :</label>
					<input name="title" type="text" class="form-control" value="<?php echo $rowtemplate['title']; ?>" data-placeholder="Enter template title" style="border:#CCC thin solid; border-radius: 5px; padding-left:5px" required />
				</div>
				<div class="col-lg-12">
					<label style="margin-top: 10px;">Template Content <span style="color:red;font-size:15px;">*</span> :</label>
					<textarea name="content" cols="45" rows="10" class="form-control txtboxes" id="template" style="width:100%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif; border:#CCC thin solid; border-radius: 5px; padding-left:5px" required><?php echo $rowtemplate['content']; ?></textarea>

				</div>
				<div class="col-lg-12">
					<label>Template Variables: </label>
					<div type="text" name="website" class="form-control" id="">[SITE_URL] [SITE_NAME] [FIRST_NAME] [LAST_NAME] [ADDRESS] [CITY] [MOBILE_NUMBER] [EMAIL] [PASSWORD]</div>
				</div>

				<div class="row clearfix">
					<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
						<input type="hidden" value="<?php echo $rowtemplate['id']; ?>" name="id" />
						<div class="btn-group">
							<a href="email_templates.php" class="btn bg-orange waves-effect waves-light" type="button">Cancel</a>
						</div>
						<div class="btn-group">
							<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="<?php echo isset($id) && !empty($id) ? "Update" : "Save"; ?>" />
						</div>
						<input type="hidden" name="MM_insert" value="pmfrm" />
					</div>
					<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>