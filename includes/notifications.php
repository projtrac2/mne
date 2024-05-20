<div class="collapse navbar-collapse" id="navbar-collapse">
    <ul class="nav navbar-nav navbar-right">
        <!-- Notifications -->
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" id="notif" data-toggle="dropdown" role="button">
                <span class="label label-pill label-danger " id="notif_count" style="border-radius:10px;"></span> <span class="fa fa-bell" style="font-size:18px;"></span>
            </a>
            <ul class="dropdown-menu">
                <li class="header">NOTIFICATIONS</li>
                <li class="body">
                    <ul class="menu" id="mynotif">
                        <li class="">
                            <a href="javascript:void(0);">View All Messages</a>
                        </li>
                    </ul>
                </li>
                <li class="footer">
                    <a href="view-user-notifications.php">View All Notifications</a>
                </li>
            </ul>
        </li>
        <!-- #END# Notifications -->
        <!-- Tasks -->
        <li class="dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle" id="msg" data-toggle="dropdown" role="button">
                <span class="label label-pill label-danger " id="number" style=" border-radius:10px;"></span> <span class="fa fa-envelope-o" style="font-size:18px;"></span>
            </a>
            <ul class="dropdown-menu">
                <li class="header">MESSAGES</li>
                <li class="body">
                    <ul class="menu" id="mymsg">
                    </ul>
                </li>
                <li class="footer">
                    <a href="view-user-notifications.php">View All Messages</a>
                </li>
            </ul>
            <!-- /.dropdown-messages -->
        </li>
        <!-- #END# Messsages -->
    </ul>
</div>

<script>
    const notification_url = "ajax/notifications/fetchnotifications";
    $(document).ready(function() {
        function load_unseen_notification(view = '') {
            $.ajax({
                url: notification_url,
                method: "get",
                data: {
                    get_alerts: "get_alerts",
                    view: view
                },
                dataType: "json",
                success: function(data) {
                    if (data.success) {
                        $('#mynotif').html(data.alerts);
                        $('#notif_count').html(data.total_alerts);
                    } else {
                        error_alert("Sorry error occurred");
                    }
                }
            });
        }

        function load_unseen_message(read = '') {
            $.ajax({
                url: notification_url,
                method: "get",
                data: {
                    get_notifications: "get_notifications",
                    read: read
                },
                dataType: "json",
                success: function(data) {
                    if (data.success) {
                        $('#mymsg').html(data.notifications);
                        $('#number').html(data.total_notifications);
                    } else {
                        error_alert("Sorry error occurred");
                    }
                }
            });
        }

        $(document).on('click', '#notif', function() {
            $('.count').html('');
            load_unseen_notification('yes');
        });

        $(document).on('click', '#msg', function() {
            $('.number').html('');
            load_unseen_message('yes');
        });

        setInterval(function() {
            load_unseen_notification();
            load_unseen_message();
        }, 5000);
    });
</script>