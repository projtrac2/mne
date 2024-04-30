$('#save-btn').on('click', save_content);
	let edits = '';

	function getContent(el) {
		console.log($(el));
	}

	function save_content() {
        
		let vals = $('.editable').val();
		const data = new FormData();
		data.append('content', vals);
		$.ajax({
			url: '/ajax/settings/email/email-templates-actions.php',
			type: 'post',
			contentType: false,
			data: data,
			cache: false,
			processData: false,
			error: (error) => {
				console.log(error);
			},
			success: (response) => {
				if (response) {
					swal({
						title: "Changes saved successfully",
						text: `Successfully saved`,
						icon: "success",
					});
				} else {
					swal({
						title: "System error! Refresh and try again",
						text: `Error occurred`,
						icon: "error",
					});
				}
				setTimeout(() => {
					window.location.reload();
				}, 2000);
			}
		})
	}