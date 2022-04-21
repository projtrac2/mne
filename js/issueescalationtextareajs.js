
	// Replace the <textarea id="editor1"> with a CKEditor
	// instance, using default configuration.
	CKEDITOR.replace( 'comments',
	{
		height: 150,
		on :
		{
		instanceReady : function( ev )
		{
			// Output paragraphs as <p>Text</p>.
			this.dataProcessor.writer.setRules( 'p',
			{
				indent : false,
				breakBeforeOpen : false,
				breakAfterOpen : false,
				breakBeforeClose : false,
				breakAfterClose :false
			});
			this.dataProcessor.writer.setRules( 'ol',
			{
				indent : false,
				breakBeforeOpen : false,
				breakAfterOpen : false,
				breakBeforeClose : false,
				breakAfterClose :false
			});
			this.dataProcessor.writer.setRules( 'ul',
			{
				indent : false,
				breakBeforeOpen : false,
				breakAfterOpen : false,
				breakBeforeClose : false,
				breakAfterClose :false
			});
			this.dataProcessor.writer.setRules( 'li',
			{
				indent : false,
				breakBeforeOpen : false,
				breakAfterOpen : false,
				breakBeforeClose : false,
				breakAfterClose :false
			});
		}
		}
	});
	
	
	$('#costopt').change(function() {
		if( $(this).val() == 1) {
			var coststatus = $(this).val();
			var projID = $("#projid").val();
			var issueid = $("#issueid").val();
			$.ajax({
				type: 'POST',
				url: 'callcommitteeaction',
				//data: {'members_id': memberID},
				data: "coststatus="+coststatus+"&projid="+projID+"&issueid="+issueid,
				success: function (data) {
					$('#parameterschange').html(data);
					 $("#parChangeModal").modal({backdrop: "static"});
				}
			});
		} else {       
		}
	});
		
	$('#timelineopt').on('change',function(){
		if( $(this).val() == 1) {
			var timelinestatus = $(this).val();
			var projID = $("#projid").val();
			var issueid = $("#issueid").val();
			$.ajax({
				type: 'POST',
				url: 'callcommitteeaction',
				//data: {'members_id': memberID},
				data: "timelinestatus="+timelinestatus+"&projid="+projID+"&issueid="+issueid,
				success: function (data) {
					$('#parameterschange').html(data);
					 $("#parChangeModal").modal({backdrop: "static"});
				}
			});
		} else {       
		}
	});
	
	$('#scopeopt').on('change',function(){
		if( $(this).val() == 1) {
			var scopestatus = $(this).val();
			var projID = $("#projid").val();
			var issueid = $("#issueid").val();
			$.ajax({
				type: 'POST',
				url: 'callcommitteeaction',
				//data: {'members_id': memberID},
				data: "scopestatus="+scopestatus+"&projid="+projID+"&issueid="+issueid,
				success: function (data) {
					$('#parameterschange').html(data);
					 $("#parChangeModal").modal({backdrop: "static"});
				}
			});
		} else {       
		}
	});
	
	$('#costopt').change(function() {
		if( $(this).val() == 1) {
			$('#costopt').prop( "disabled", true );
		} else {       
			$('#costopt').prop( "disabled", false );
		}
	});
	
	$('#timelineopt').change(function() {
		if( $(this).val() == 1) {
			$('#timelineopt').prop( "disabled", true );
		} else {       
			$('#timelineopt').prop( "disabled", false );
		}
	});
	
	$('#scopeopt').change(function() {
		if( $(this).val() == 1) {
			$('#scopeopt').prop( "disabled", true );
		} else {       
			$('#scopeopt').prop( "disabled", false );
		}
	});