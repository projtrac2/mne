<?php
    try {
require('includes/head.php');
if ($permission) {
    $notification_id = $_GET['notification_id'];
        $query_rsNotifications = $db->prepare("SELECT * FROM tbl_notifications  WHERE id=:notification_id");
        $query_rsNotifications->execute(array(":notification_id" => $notification_id));
        $Rows_rsNotifications = $query_rsNotifications->fetch();
        $totalRows_rsNotifications = $query_rsNotifications->rowCount();

        $notification_group_id = $totalRows_rsNotifications > 0 ? $Rows_rsNotifications['notification_group_id'] : '';
        $notification = $totalRows_rsNotifications > 0 ? $Rows_rsNotifications['notification'] : '';
   
?>
    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <?= $icon ?> <?= $notification . " " . $pageTitle ?>
                    <div class="btn-group" style="float:right">
                        <div class="btn-group" style="float:right">
                            <a type="button" id="outputItemModalBtnrow" href="view-notification-details.php?notification_group_id=<?= $notification_group_id ?>" class="btn btn-warning pull-right" style="margin-right:10px;">
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
                        <div class="body">
                            <!-- ============================================================== -->
                            <!-- Start Page Content -->
                            <!-- ============================================================== -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="width:100%">
                                    <thead style="width:100%">
                                        <tr class="bg-blue" style="width:100%">
                                            <th width="5%"><strong>#</strong></th>
                                            <th width="65%"><strong>Notification Type</strong></th>
                                            <th width="25%"><strong>Timeline</strong></th>
                                            <th width="10%"><strong>Action</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query_rsNotification_Timelines = $db->prepare("SELECT t.*, n.type, n.category FROM tbl_notification_timelines t  INNER JOIN tbl_notification_types n ON  t.notification_type_id=n.id WHERE notification_id=:notification_id");
                                        $query_rsNotification_Timelines->execute(array(":notification_id" => $notification_id));
                                        $totalRows_rsNotification_Timelines = $query_rsNotification_Timelines->rowCount();
                                        if ($totalRows_rsNotification_Timelines > 0) {
                                            $counter = 0;
                                            while ($row_rsNotification_Timelines = $query_rsNotification_Timelines->fetch()) {
                                                $counter++;
                                                $timeline_id = $row_rsNotification_Timelines['id'];
                                                $timeline = $row_rsNotification_Timelines['timeline'];
                                                $notification_type = $row_rsNotification_Timelines['type'];
                                                $category = $row_rsNotification_Timelines['category'];
                                                $status_id = $row_rsNotification_Timelines['status'];
                                                $status = $status_id == 1 ? "Disable" : "Enable";
                                                $timeline_days = $category == 1 ? $timeline . " days" : 'Not Applicable';
                                        ?>
                                                <tr>
                                                    <td width="5%"><?= $counter ?></td>
                                                    <td width="65%"><?= $notification_type ?></td>
                                                    <td width="20%"><?= $timeline_days ?></td>
                                                    <td width="10%">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Options <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <?php
                                                                if ($category == 1) {
                                                                ?>
                                                                    <li>
                                                                        <a type="button" data-toggle="modal" data-target="#addNotificationTimeline" id="moreNotificationBtn" onclick="get_notification_timeline_details('edit', <?= $timeline_id ?>,'<?= $notification_type ?>')">
                                                                            <i class="fa fa-file-text"></i> Edit
                                                                        </a>
                                                                    </li>
                                                                <?php
                                                                }
                                                                ?>
                                                                <li>
                                                                    <a type="button" onclick="change_notification_timeline_status(<?= $timeline_id ?>,<?= $status_id == 1 ? 0 : 1; ?>,'<?= $status ?>' ,'<?= $notification_type ?>')">
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

                            <!-- ============================================================== -->
                            <!-- End PAge Content -->
                            <!-- ============================================================== -->
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- end body  -->


    <!-- add item -->
    <div class="modal fade" id="addNotificationTimeline" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="submit_timeline_form" action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> Notification Timeline </h4>
                    </div>
                    <div class="modal-body">
                        <div class="row clearfix">

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label>Notification Type *:</label>
                                <div class="form-line">
                                    <input type="text" name="notification_type" id="notification_type" placeholder="Enter" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
                                <label for="program_target" class="control-label">Timeline (Days) *:</label>
                                <div class="form-input">
                                    <input type="text" name="timeline" id="timeline" placeholder="Enter" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=" modal-footer">
                        <div class="col-md-12 text-center">
                            <input type="hidden" name="notification_id" id="notification_id" value="<?= $notification_id ?>">
                            <input type="hidden" name="timeline_id" id="timeline_id" value="">
                            <input type="hidden" name="store_notifications_timelines" id="store_notifications_timelines" value="new">
                            <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit-timeline" value="Submit" />
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

} catch (PDOException $th) {
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>

<script src="assets/js/notifications/index.js"></script>