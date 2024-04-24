<?php
require('includes/head.php');
if ($permission) {
?>
    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <?= $icon ?>
                    <?= $pageTitle ?>
                    <div class="btn-group" style="float:right">
                        <div class="btn-group" style="float:right">
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
                                    <a data-toggle="tab" href="#notification_types"><i class="fa fa-caret-square-o-down bg-deep-orange" aria-hidden="true"></i> Notifications&nbsp;<span class="badge bg-orange">|</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#notification"><i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> Messages&nbsp;<span class="badge bg-blue">|</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
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
                                            <table class="table email-table no-wrap table-hover v-middle mb-0 font-14">
                                                <tbody>
                                                    <?php
                                                    $query_rsAlerts = $db->prepare("SELECT * FROM tbl_notification_status WHERE user_id=:user_id AND notification_type_id=3 ");
                                                    $query_rsAlerts->execute(array(":user_id" => $user_name));
                                                    $totalRows_rsAlerts = $query_rsAlerts->rowCount();
                                                    $alerts = '';
                                                    if ($totalRows_rsAlerts > 0) {
                                                        while ($row_rsAlerts = $query_rsAlerts->fetch()) {
                                                            $notification_id = $row_rsAlerts['notification_id'];
                                                            $title = $row_rsAlerts['title'];
                                                            $query_rsNotification = $db->prepare("SELECT * FROM tbl_notifications  WHERE id=:notification_id");
                                                            $query_rsNotification->execute(array(":notification_id" => $notification_id));
                                                            $Rows_rsNotification = $query_rsNotification->fetch();
                                                            $totalRows_rsNotification = $query_rsNotification->rowCount();
                                                            $notification = $totalRows_rsNotification > 0 ? $Rows_rsNotification['notification'] : '';
                                                    ?>
                                                            <!-- row -->
                                                            <tr>
                                                                <!-- label -->
                                                                <td class="pl-3">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="cst1" />
                                                                        <label class="custom-control-label" for="cst1">&nbsp;</label>
                                                                    </div>
                                                                </td>
                                                                <!-- star -->
                                                                <td><i class="fa fa-star text-warning"></i></td>
                                                                <td>
                                                                    <span class="mb-0 text-muted"><?= $notification ?></span>
                                                                </td>
                                                                <!-- Message -->
                                                                <td>
                                                                    <a class="link" href="javascript: void(0)">
                                                                        <span class="badge badge-pill text-white font-medium badge-danger mr-2">Alert</span>
                                                                        <span class="text-dark"><?= $title ?></span>
                                                                    </a>
                                                                </td>
                                                                <!-- Attachment -->
                                                                <td><i class="fa fa-paperclip text-muted"></i></td>
                                                                <!-- Time -->
                                                                <td class="text-muted"><?= date("d M", strtotime($row_rsAlerts['created_at'])) ?></td>
                                                            </tr>
                                                            <!-- row -->
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div id="notification" class="tab-pane ">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table email-table no-wrap table-hover v-middle mb-0 font-14 ">
                                                <tbody>
                                                    <!-- row -->
                                                    <?php
                                                    $query_rsNotifications = $db->prepare("SELECT s.*, t.type FROM tbl_notification_status s INNER JOIN tbl_notification_types t ON t.id = s.notification_type_id  WHERE user_id=:user_id AND seen=0 AND notification_type_id<>3 ");
                                                    $query_rsNotifications->execute(array(":user_id" => $user_name));
                                                    $totalRows_rsNotifications = $query_rsNotifications->rowCount();
                                                    $notifications = '';
                                                    if ($totalRows_rsNotifications > 0) {
                                                        while ($row_rsNotifications = $query_rsNotifications->fetch()) {
                                                            $notification_type = $row_rsNotifications['type'];
                                                            $title = $row_rsNotifications['title'];
                                                            $content = $row_rsNotifications['content'];
                                                            $notification_group_id = $row_rsNotifications['notification_group_id'];
                                                            $created_at = $row_rsNotifications['created_at'];
                                                            $notification_id = $row_rsNotifications['notification_id'];

                                                            $query_rsNotification = $db->prepare("SELECT * FROM tbl_notifications  WHERE id=:notification_id");
                                                            $query_rsNotification->execute(array(":notification_id" => $notification_id));
                                                            $Rows_rsNotification = $query_rsNotification->fetch();
                                                            $totalRows_rsNotification = $query_rsNotification->rowCount();
                                                            $notification = $totalRows_rsNotification > 0 ? $Rows_rsNotification['notification'] : '';
                                                    ?>
                                                            <tr>
                                                                <!-- label -->
                                                                <td class="pl-3">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="cst1" />
                                                                        <label class="custom-control-label" for="cst1">&nbsp;</label>
                                                                    </div>
                                                                </td>
                                                                <!-- star -->
                                                                <td><i class="fa fa-star text-<?php $notification_id == 34 ? "warning" : "success" ?>"></i></td>
                                                                <td>
                                                                    <span class="mb-0 text-muted"><?= $notification . $notification_id  ?></span>
                                                                </td>
                                                                <!-- Message -->
                                                                <td>
                                                                    <a class="link" href="javascript: void(0)">
                                                                        <span class="badge badge-pill text-white font-medium badge-success mr-2"><?= $title ?></span>
                                                                        <span class="text-dark"></span>
                                                                    </a>
                                                                </td>
                                                                <!-- Attachment -->
                                                                <td><i class="fa fa-paperclip text-muted"></i></td>
                                                                <!-- Time -->
                                                                <td class="text-muted"><?= date('d M Y', strtotime($created_at)) ?></td>
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
    </section>
    <!-- end body  -->
<?php
} else {
    $results =  restriction();
    echo $results;
}
require('includes/footer.php');
?>