<div class="info-container">
    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?php echo "Welcome! " . ($user_details->username); ?>
    </div>
    <div class="btn-group user-helper-dropdown">
        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
        <ul class="dropdown-menu pull-right">
            <li><a href="javascript:void(0);"><i class="material-icons">person</i>My Profile</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="/javascript:void(0);"><i class="material-icons">work</i>Score Card</a></li>
            <li><a href="/viewprojectmsgs"><i class="material-icons">email</i>My Inbox</a></li>
            <li><a href="javascript:void(0);"><i class="material-icons">settings</i>Account Settings</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="logout.php"><i class="material-icons">input</i>Sign Out</a></li>
        </ul>
    </div>
</div>