<?php
try {
    require('includes/head.php');
    if ($permission) {
        $notification_group_id = $_GET['notification_group_id'];
        $notification_id = 0;
        $query_rsNotification_group = $db->prepare("SELECT * FROM tbl_notification_groups  WHERE id=:notification_group_id");
        $query_rsNotification_group->execute(array(":notification_group_id" => $notification_group_id));
        $totalRows_rsNotification_group = $query_rsNotification_group->rowCount();
        $rows_rsNotification_group = $query_rsNotification_group->fetch();
        $notification_group = $rows_rsNotification_group['area'];

?>
        <!-- start body  -->
        <section class="content">
            <div class="container-fluid">
                <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                    <h4 class="contentheader">
                        <?= $icon ?> <?= $notification_group . "  " . $pageTitle ?>
                        <div class="btn-group" style="float:right">
                            <div class="btn-group" style="float:right">
                                <a type="button" id="outputItemModalBtnrow" href="view-notifications.php" class="btn btn-warning pull-right" style="margin-right:10px;">
                                    Go Back
                                </a>
                            </div>
                        </div>
                    </h4>
                </div>
                <div class="row clearfix">
                    <div class="block-header">
                        <?= $results; ?>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <ul class="nav nav-tabs" style="font-size:14px">
                                    <li class="active">
                                        <a data-toggle="tab" href="#notification_types"><i class="fa fa-caret-square-o-down bg-deep-orange" aria-hidden="true"></i> Notification Areas&nbsp;<span class="badge bg-orange">|</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#notification"><i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> Notification Templates&nbsp;<span class="badge bg-blue">|</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="body">
                                <!-- ============================================================== -->
                                <!-- Start Page Content -->
                                <!-- ============================================================== -->

                                <div class="tab-content">
                                    <div id="notification_types" class="tab-pane fade in active">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="width:100%">
                                                    <thead style="width:100%">
                                                        <tr class="bg-blue" style="width:100%">
                                                            <th width="5%"><strong>#</strong></th>
                                                            <th width="35%"><strong>Notification</strong></th>
                                                            <th width="25%"><strong>Page URL</strong></th>
                                                            <?php
                                                            if ($notification_group_id != 1) {
                                                            ?>
                                                                <th width="25%"><strong>Stage</strong></th>
                                                                <th width="25%"><strong>Data Entry</strong></th>
                                                                <th width="25%"><strong>Approval</strong></th>
                                                            <?php
                                                            }
                                                            ?>
                                                            <th width="10%"><strong>Action</strong></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $query_rsNotifications = $db->prepare("SELECT * FROM tbl_notifications  WHERE notification_group_id=:notification_group_id");
                                                        $query_rsNotifications->execute(array(":notification_group_id" => $notification_group_id));
                                                        $totalRows_rsNotifications = $query_rsNotifications->rowCount();
                                                        if ($totalRows_rsNotifications > 0) {
                                                            $counter = 0;
                                                            while ($row_rsNotification = $query_rsNotifications->fetch()) {
                                                                $counter++;
                                                                $notification_id = $row_rsNotification['id'];
                                                                $notification = $row_rsNotification['notification'];
                                                                $stage_id = $row_rsNotification['stage_id'];
                                                                $data_entry = $row_rsNotification['data_entry'];
                                                                $approval = $row_rsNotification['approval'];
                                                                $status_id = $row_rsNotification['status'];
                                                                $page_url = $row_rsNotification['page_url'];
                                                                $status = $status_id == 1 ? "Disable" : "Enable";

                                                                $query_rsStage = $db->prepare("SELECT * FROM tbl_project_workflow_stage WHERE priority=:stage_id");
                                                                $query_rsStage->execute(array(":stage_id" => $stage_id));
                                                                $row_rsStage = $query_rsStage->fetch();
                                                                $totalRows_rsStage = $query_rsStage->rowCount();
                                                                $stage = $totalRows_rsStage > 0 ? $row_rsStage['stage'] : '';
                                                        ?>
                                                                <tr>
                                                                    <td width="5%"><?= $counter ?></td>
                                                                    <td width="35%"><?= $notification ?></td>
                                                                    <td width="25%"><?= $page_url ?></td>
                                                                    <?php
                                                                    if ($notification_group_id != 1) {
                                                                    ?>
                                                                        <td width="20%"><?= $stage ?></td>
                                                                        <td width="20%"><?= $data_entry ?> days</td>
                                                                        <td width="20%"><?= $approval ?> days</td>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                    <td width="10%">
                                                                        <div class="btn-group">
                                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                Options <span class="caret"></span>
                                                                            </button>
                                                                            <ul class="dropdown-menu">
                                                                                <li>
                                                                                    <a type="button" data-toggle="modal" data-target="#addNotification" id="moreNotificationBtn" onclick="get_notification_details('edit', <?= $notification_id ?>,'<?= $notification ?>', <?= $data_entry ?>, <?= $approval ?>, '<?= $page_url ?>')">
                                                                                        <i class="fa fa-file-text"></i> Edit
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a type="button" onclick="change_notification_group_status(<?= $notification_id ?>,<?= $status_id == 1 ? 0 : 1; ?>,'<?= $status ?>' ,'<?= $notification ?>')">
                                                                                        <i class="fa fa-file-text"></i> <?= $status ?>
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a href="view-notification-timelines.php?notification_id=<?= $notification_id ?>">
                                                                                        <i class="fa fa-file-text"></i> Timelines
                                                                                    </a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="notification" class="tab-pane fade">
                                        <div class="body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="width:100%">
                                                    <thead style="width:100%">
                                                        <tr class="bg-blue" style="width:100%">
                                                            <th width="5%"><strong>#</strong></th>
                                                            <th width="20%"><strong>Notification Type</strong></th>
                                                            <th width="30%"><strong>Subject</strong></th>
                                                            <th width="40%"><strong>Template</strong></th>
                                                            <th width="5%"><strong>Action</strong></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $query_rsNotification_Templates = $db->prepare("SELECT t.*, n.type, n.category FROM tbl_notification_templates t  INNER JOIN tbl_notification_types n ON  t.notification_type_id=n.id WHERE notification_group_id=:notification_group_id");
                                                        $query_rsNotification_Templates->execute(array(":notification_group_id" => $notification_group_id));
                                                        $totalRows_rsNotification_Timelines = $query_rsNotification_Templates->rowCount();
                                                        if ($totalRows_rsNotification_Timelines > 0) {
                                                            $counter = 0;
                                                            while ($row_rsNotification_Templates = $query_rsNotification_Templates->fetch()) {
                                                                $counter++;
                                                                $template_id = $row_rsNotification_Templates['id'];
                                                                $title = $row_rsNotification_Templates['title'];
                                                                $content = $row_rsNotification_Templates['content'];
                                                                $notification_type = $row_rsNotification_Templates['type'];
                                                                $status_id = $row_rsNotification_Templates['status'];
                                                                $status = $status_id == 1 ? "Disable" : "Enable";
                                                        ?>
                                                                <tr>
                                                                    <td width="5%"><?= $counter ?></td>
                                                                    <td width="20%"><?= $notification_type ?></td>
                                                                    <td width="20%"><?= $title ?></td>
                                                                    <td width="45%"><?= $content ?></td>
                                                                    <td width="10%">
                                                                        <div class="btn-group">
                                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                Options <span class="caret"></span>
                                                                            </button>
                                                                            <ul class="dropdown-menu">
                                                                                <li>
                                                                                    <a type="button" data-toggle="modal" data-target="#addNotificationTemplate" id="moreNotificationBtn" onclick="get_notification_template_details(<?= $template_id ?>, '<?= $notification_type ?>')">
                                                                                        <i class="fa fa-file-text"></i> Edit
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a type="button" onclick="change_notification_template_status(<?= $template_id ?>,<?= $status_id == 1 ? 0 : 1; ?>,'<?= $status ?>' ,'<?= $title ?>')">
                                                                                        <i class="fa fa-file-text"></i> <?= $status ?>
                                                                                    </a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- ============================================================== -->
                                <!-- End PAge Content -->
                                <!-- ============================================================== -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end body  -->

        <!-- add item -->
        <div class="modal fade" id="addNotification" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="submit_notification_form" action="" method="POST" enctype="multipart/form-data">
                        <div class="modal-header" style="background-color:#03A9F4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> Notification </h4>
                        </div>
                        <div class="modal-body">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
                                    <label for="program_target" class="control-label">Notification *:</label>
                                    <div class="form-input">
                                        <input type="text" name="notification" id="notification_name" placeholder="Enter" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
                                    <label for="program_target" class="control-label">Page URL *:</label>
                                    <div class="form-input">
                                        <input type="text" name="page_url" id="page_url" placeholder="Enter" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
                                    <label for="program_target" class="control-label">Data Entry *:</label>
                                    <div class="form-input">
                                        <input type="number" name="data_entry" id="data_entry" placeholder="Enter" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
                                    <label for="program_target" class="control-label">Approval *:</label>
                                    <div class="form-input">
                                        <input type="number" name="approval" id="approval" placeholder="Enter" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" modal-footer">
                            <div class="col-md-12 text-center">
                                <input type="hidden" name="notification_id" id="notification_id" value="">
                                <input type="hidden" name="store_notification" id="store_notification" value="new">
                                <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit-template" value="Submit" />
                                <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                            </div>
                        </div> <!-- /modal-footer -->
                    </form> <!-- /.form -->
                </div> <!-- /modal-content -->
            </div> <!-- /modal-dailog -->
        </div>
        <!-- End add item -->

        <!-- add item -->
        <div class="modal fade" id="addNotificationTemplate" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="submit_template_form" action="" method="POST" enctype="multipart/form-data">
                        <div class="modal-header" style="background-color:#03A9F4">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> Notification </h4>
                        </div>
                        <div class="modal-body">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
                                    <label for="program_target" class="control-label">Notification Type *:</label>
                                    <div class="form-input">
                                        <input type="text" name="notification_type" id="notification_type" placeholder="Enter" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
                                    <label for="program_target" class="control-label">Subject *:</label>
                                    <div class="form-input">
                                        <input type="text" name="title" id="title" placeholder="Enter" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label class="control-label">Body *:</label>
                                    <div class="form-line">
                                        <textarea name="content" cols="" rows="7" class="form-control" id="content" placeholder="Enter content " style="width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" modal-footer">
                            <div class="col-md-12 text-center">
                                <input type="hidden" name="notification_group_id" id="notification_group_id" value="<?= $notification_group_id ?>">
                                <input type="hidden" name="template_id" id="template_id" value="">
                                <input type="hidden" name="store_notification_template" id="store_notification_template" value="new">
                                <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit-template" value="Submit" />
                                <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                            </div>
                        </div> <!-- /modal-footer -->
                    </form> <!-- /.form -->
                </div> <!-- /modal-content -->
            </div> <!-- /modal-dailog -->
        </div>
        <!-- End add item -->
<?php
    } else {
        $results =  restriction();
        echo $results;
    }
    require('includes/footer.php');
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>

<script src="assets/js/notifications/index.js"></script>