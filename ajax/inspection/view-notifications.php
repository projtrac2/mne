<?php
require('includes/head.php');
require('functions/indicator.php');
require('functions/department.php');

if ($permission) {

    $total_output_indicators = 0;
    $total_outcome_indicators = 0;
    $total_impact_indicators = 0;
    $output_indicators = get_output_indicators();
    $outcome_indicators = get_outcome_indicators();
    $impact_indicators = get_impact_indicators();

    if ($output_indicators) {
        $total_output_indicators = count($output_indicators);
    }

    if ($outcome_indicators) {
        $total_outcome_indicators = count($outcome_indicators);
    }

    if ($impact_indicators) {
        $total_impact_indicators = count($impact_indicators);
    }
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
                        <div class="body">
                            <!-- ============================================================== -->
                            <!-- Start Page Content -->
                            <!-- ============================================================== -->


                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="width:100%">
                                    <thead style="width:100%">
                                        <tr class="bg-blue" style="width:100%">
                                            <th width="5%"><strong>#</strong></th>
                                            <th width="85%"><strong>Notification</strong></th>
                                            <th width="10%"><strong>Action</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query_rsNotifications = $db->prepare("SELECT * FROM tbl_notification_groups");
                                        $query_rsNotifications->execute();
                                        $totalRows_rsNotifications = $query_rsNotifications->rowCount();
                                        if ($totalRows_rsNotifications > 0) {
                                            $counter = 0;
                                            while ($row_rsNotification = $query_rsNotifications->fetch()) {
                                                $counter++;
                                                $notification_group_id = $row_rsNotification['id'];
                                                $notification = $row_rsNotification['area'];
                                                $status_id = $row_rsNotification['status'];
                                                $status_id = $row_rsNotification['status'];
                                                $status_id = $row_rsNotification['status'];
                                                $status = $status_id == 1 ? "Disable"   : "Enable";
                                        ?>
                                                <tr>
                                                    <td width="5%"><?= $counter ?></td>
                                                    <td width="85%"><?= $notification ?></td>
                                                    <td width="10%">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Options <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a type="button" onclick="change_notification_status(<?= $notification_group_id ?>,<?= $status_id == 1 ? 0 : 1; ?>,'<?= $status ?>' ,'<?= $notification ?>')">
                                                                        <i class="fa fa-trash" style="font-size: 16px"></i> <?= $status ?>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="view-notification-details.php?notification_group_id=<?= $notification_group_id ?>">
                                                                        <i class="fa fa-file-text"></i> Details
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
<?php
} else {
    $results =  restriction();
    echo $results;
}
require('includes/footer.php');
?>

<script src="assets/js/notifications/index.js"></script>