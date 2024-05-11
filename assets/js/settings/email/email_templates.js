$(document).ready(function () {
	$("#email_template").submit(function (e) {
		e.preventDefault();
		$("#save-btn").prop("disabled", true);
		var data = $(this)[0];
		var form = new FormData(data);

		$.ajax({
			type: "post",
			url: "ajax/settings/email/email-templates-actions",
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			timeout: 600000,
			dataType: "json",
			success: function (response) {
				if (response.success) {
					success_alert("Success!");
				} else {
					sweet_alert("Error!");
				}
				$("#tag-form-submit").prop("disabled", false);
				setTimeout(() => {
					window.location.reload(true);
				}, 3000);
			}
		});
	});
});