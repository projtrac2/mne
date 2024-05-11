<?php

?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="header">
            <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
                    <tr>
                        <td width="80%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF">
                            <div align="left" style="vertical-align: text-bottom">
                                <font size="3" color="#FFC107"><i class="fa fa-cog" aria-hidden="true"></i> <strong>System Workflow Stages</strong></font>
                            </div>
                        </td>
                        <td width="20%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF">
                            <button type="button" id="modal_button" class="pull-right btn bg-deep-purple" data-toggle="modal" id="addItemModalBtn" data-target="#addItemModal">
                                <i class="fa fa-plus-square"></i> Add Item
                            </button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Stage</th>
                            <th>Parent</th>
                            <th>Priority</th>
                            <th>Timeline (Days)</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = $db->prepare("SELECT * FROM `tbl_project_workflow_stage` ORDER BY `id` ASC");
                        $sql->execute();
                        $rows_count = $sql->rowCount();
                        $output = array('data' => array());
                        if ($rows_count > 0) {
                            $active = "";
                            $sn = 0;
                            while ($row = $sql->fetch()) {
                                $sn++;
                                $itemId = $row['id'];
                                $stage = $row["stage"];
                                $description = $row["description"];
                                $parentid = $row["parent"];
                                $priority = $row["priority"];
                                $status = $row["active"];
                                $timeline = $row["timeline"];
                                $timeline = $timeline > 0 ? $timeline : "N/A";

                                // status
                                $active = ($row['active'] == 1) ? "<label class='label label-success'>Enabled</label>" : "<label class='label label-danger'>Disabled</label>";
                                if ($parentid > 0) {
                                    $sqlparent = $db->prepare("SELECT * FROM `tbl_project_workflow_stage` WHERE id='$parentid' ");
                                    $sqlparent->execute();
                                    $rowparent = $sqlparent->fetch();
                                    $parent = $rowparent['stage'];
                                }


                                $details = "{
                                    id:$itemId,
                                    stage:$stage,
                                    description:$description,
                                    parentid:$parentid,
                                    priority:$priority,
                                }"
                        ?>
                                <tr>
                                    <td><?= $sn ?></td>
                                    <td><?= $stage ?></td>
                                    <td><?= $parent ?></td>
                                    <td><?= $priority ?></td>
                                    <td><?= $timeline ?></td>
                                    <td><?= $active ?></td>
                                    <td>
                                        <!-- Single button -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Options <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">

                                                <li>
                                                    <a type="button" id="disableBtn" class="disableBtn" onclick="disable(<?= $itemId ?>, '<?= $stage ?>', <?php if ($status == 1) { ?> 'disable' <?php } else { ?> 'enable' <?php } ?>)">
                                                        <i class="glyphicon glyphicon-trash"></i> <?php if ($status == 1) { ?> Disable <?php } else { ?> Enable <?php } ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                        <?php
                            } // /while

                        } // if num_rows
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
<script src="assets/js/settings/permission.js"></script>

<script>
    const edit_workflow = (details) => {
        console.log(details);
    }

    function disable(id, name, action) {
        swal({
            title: "Are you sure?",
            text: `You want to ${action} stage ${name}!`,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willUpdate) => {
            if (willUpdate) {
                $.ajax({
                    type: "post",
                    url: '/system-workflow-stages-inner-update.php',
                    data: {
                        update_stage_status: "update_stage_status",
                        stage_id: id,
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