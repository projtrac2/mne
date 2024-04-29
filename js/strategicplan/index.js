function delete_plan(planid) {
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover the data!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                swal("Strategic plan has been successfully deleted", {
                    icon: "success",
                });
            } else {
                swal("You have canceled the action!");
            }
        });
}