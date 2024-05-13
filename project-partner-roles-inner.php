<?php

/**
 * @return AllRoles
 * @tables == tbl_project_team_roles
 */
$stmt_project_roles = $db->prepare('SELECT * FROM tbl_partner_roles');
$stmt_project_roles->execute();
$roles = $stmt_project_roles->fetchAll(PDO::FETCH_OBJ);
$hash = 1;
?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <!-- card header -->
        <div class="header">
            <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
                    <tr>
                        <td width="80%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF">
                            <div align="left" style="vertical-align: text-bottom">
                                <font size="3" color="#FFF"><i class="fa fa-compass" aria-hidden="true"></i></font>
                                <font size="3" color="#FFC107"><strong>Partner Roles</strong></font>
                            </div>
                        </td>
                        <td width="20%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF">
                            <button type="button" id="modal_button" class="pull-right btn bg-deep-purple" data-toggle="modal" id="addItemModal" data-target="#addItemModal"> <i class="fa fa-plus-square"></i> Add Partner Role</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <!-- card header -->
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="direct_table">
                    <thead>
                        <tr>
                            <th style="width:5%">#</th>
                            <th style="width:30%">Role</th>
                            <th style="width:15%">status</th>
                            <th style="width:15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($roles as $key => $role) {

                            if ($role->status) {
                                $label = "<label class='label label-success'>Enabled</label>";
                                $wordings = 'disable';
                                $wordingsCapital = 'Disable';
                            } else {
                                $label = "<label class='label label-danger'>Disabled</label>";
                                $wordings = 'enable';
                                $wordingsCapital = 'Enable';
                            }
                        ?>
                            <tr>
                                <td style="width:5%"><?= $hash ?></td>
                                <td style="width:30%"><?= $role->role ?></td>
                                <td style="width:15%"><?= $label ?></td>
                                <td style="width:15%">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Options <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a type="button" data-toggle="modal" id="editItemModalBtn" data-target="#editItemModal<?= $role->id ?>"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
                                            <li>
                                                <a type="button" id="disableBtn" class="disableBtn" onclick="disable(<?= $role->id ?>, '<?= $role->role  ?>', '<?= $wordings ?>')">
                                                    <i class="glyphicon glyphicon-trash"></i> <?= $wordingsCapital ?>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php
                            $hash++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addItemModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" id="submitItemForm" action="general-settings/action/project-priorities-action" method="POST" enctype="multipart/form-data">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-plus"></i> Add Partner Role</h4>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="body">
                                    <div id="add-item-messages"></div>
                                    <div class="col-md-12 form-input">
                                        <label>
                                            <font color="#174082">Role name: </font>
                                        </label>
                                        <input type="text" class="form-control name_add" id="name" placeholder="Role name" name="name" required autocomplete="off">
                                        <span class="name_error text-danger"></span>
                                    </div>
                                    <!-- /form-group-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- /modal-body -->

                <div class="modal-footer">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light save-new-btn" id="tag-form-submit">save</button>
                    </div>
                </div> <!-- /modal-footer -->
            </form> <!-- /.form -->
        </div> <!-- /modal-content -->
    </div> <!-- /modal-dailog -->
</div>

<!-- edit item -->
<?php
foreach ($roles as $key => $role) {
?>
    <div class="modal fade" id="editItemModal<?= $role->id ?>" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form class="form-horizontal" id="submitItemForm" action="#" method="POST" enctype="multipart/form-data">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-plus"></i> Edit Project Team Roley</h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="hidden" class="role_id" name="role_id" value="<?= $role->id ?>">
                                    <div class="body">
                                        <div id="add-item-messages"></div>
                                        <div class="col-md-12 form-input">
                                            <label>
                                                <font color="#174082">Role name: </font>
                                            </label>
                                            <input type="text" class="form-control name" id="name" placeholder="Line name" name="name" required autocomplete="off" value="<?= $role->role ?>">
                                        </div>
                                        <!-- /form-group-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /modal-body -->

                    <div class="modal-footer">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                            <input name="save" type="button" class="btn btn-primary waves-effect waves-light save-edits-btn" id="tag-form-submit" value="Save" />
                        </div>
                    </div> <!-- /modal-footer -->
                </form> <!-- /.form -->
            </div> <!-- /modal-content -->
        </div> <!-- /modal-dailog -->
    </div>
    <!-- End edit item -->
<?php } ?>

<script>
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
            url: 'general-settings/action/partner-team-roles-action',
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
                url: 'general-settings/action/partner-team-roles-action',
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
                    url: 'general-settings/action/partner-team-roles-action',
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
</script>