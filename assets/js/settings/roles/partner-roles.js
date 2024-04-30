$('.save-new-btn').on('click', (e) => {
    e.preventDefault();
    let parent = $('.save-new-btn').parent().parent().parent();
    let name = parent.find('.name_add').val();
    if (!name) {
        parent.find('.name_error').text('field required');
        return;
    } else {
        parent.find('.name_error').text('');
    }


    let data = new FormData();
    data.append('name', name);
    data.append('newItem', 'newItem');

    $.ajax({
        url: 'ajax/settings/roles/partner-team-roles-action.php',
        type: 'post',
        data: data,
        processData: false,
        cache: false,
        contentType: false,
        error: (error) => {
            console.log(error);
        },
        success: (response) => {
            console.log(response);
            response = JSON.parse(response);
            if (response) {
                swal({
                    title: "Notification !",
                    text: `Successfully saved record. Page will refresh`,
                    icon: "success",
                });
            } else {
                swal({
                    title: "Notification !",
                    text: `Error please try again after page refreshes`,
                    icon: "error",
                });

            }
            setTimeout(function() {
                window.location.reload();
            }, 2000);
        }
    })

})

$('.save-edits-btn').each((index, element) => {
    $(element).on('click', (e) => {
        e.preventDefault();
        let parent = $(element).parent().parent().parent();
        let role_id = parent.find('.role_id').val();
        let name = parent.find('.name').val();

        let data = new FormData();
        data.append('itemid', role_id)
        data.append('name', name);
        data.append('editItem', 'editItem');

        $.ajax({
            url: 'ajax/settings/roles/partner-team-roles-action.php',
            type: 'post',
            data: data,
            processData: false,
            cache: false,
            contentType: false,
            error: (error) => {
                console.log(error);
            },
            success: (response) => {
                console.log(response);
                response = JSON.parse(response);
                if (response) {
                    swal({
                        title: "Notification !",
                        text: `Successfully updated record. Page will refresh`,
                        icon: "success",
                    });
                } else {
                    swal({
                        title: "Notification !",
                        text: `Error please try again after page refreshes`,
                        icon: "error",
                    });
                }
                setTimeout(function() {
                    window.location.reload(true);
                }, 2000);
            }
        })
    });
});

    // change status
function disable(id, name, action) {
    swal({
        title: "Are you sure?",
        text: `You want to  ${action} ${name}!`,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willUpdate) => {
        if (willUpdate) {
            $.ajax({
                type: "post",
                url: 'ajax/settings/roles/partner-team-roles-action.php',
                data: {
                    deleteItem: "deleteItem",
                    itemId: id,
                },
                dataType: "json",
                success: function(response) {
                    if (response == true) {
                        swal({
                            title: "Notification !",
                            text: `Successfully ${status}`,
                            icon: "success",
                        });
                    } else {
                        swal({
                            title: "Notification !",
                            text: `Error ${status}`,
                            icon: "error",
                        });
                    }
                    setTimeout(function() {
                        window.location.reload(true);
                    }, 3000);
                }
            });
        } else {
            swal("You cancelled the action!");
        }
    })
}