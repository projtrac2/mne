<?php
function get_page_full_url()
{
    $path = $_SERVER['REQUEST_URI'];
    $paths = explode("/", $path);
    $url_path = isset($paths[2]) ? explode(".", $paths[2]) : explode(".", $paths[1]);
    return $url_path[0];
}

$current_page_url = get_page_full_url();

$inactivity_time = 15 * 60;
$user_name = $contractor_email = $contractor_name = $avatar = '';
$user_name = $designation_id = $department_id = $section_id = $directorate_id = $avatar = $fullname = $designation = $user_department = $user_section = $user_directorate = $user_designation = '';


$auth_urls = array("index", "forgot-password", "reset-password");

session_start();
// Generate CSRF token and store it in the session
(!isset($_SESSION['csrf_token'])) ? $_SESSION['csrf_token'] =  bin2hex(random_bytes(32)) : '';


// Function to generate HTML with CSRF token input
function csrf_token_html()
{
    return   '<input type="hidden" id="csrf_token" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}

// Validate CSRF token
function validate_csrf_token($token)
{
    return isset($_SESSION['csrf_token']) && $token === $_SESSION['csrf_token'];
}


// Set the inactivity time of 60 minutes (3600 seconds)
$inactivity_time = 15 * 60;
if (isset($_SESSION['MM_Username'])) { // projects / dashboard
    if (isset($_SESSION['last_timestamp']) && (time() - $_SESSION['last_timestamp']) > $inactivity_time) {
        session_unset();
        session_destroy();
        // header("Location: index.php?action=$current_page_url");
        header("Location: index.php");
        exit();
    } else {
        session_regenerate_id(true);
        $_SESSION['last_timestamp'] = time();
    }

    $_SESSION['last_accessed_url'] = $_SERVER['REQUEST_URI'];

    $user_name = $_SESSION['MM_Username'];
    $designation_id = $_SESSION['designation'];
    $department_id = $_SESSION['ministry'];
    $section_id = $_SESSION['sector'];
    $directorate_id = $_SESSION['directorate'];
    $avatar = $_SESSION['avatar'];
    $fullname = $_SESSION['fullname'];
    $designation = $designation_id;

    $today = date('Y-m-d');
    $user_department = $department_id;
    $user_section = $section_id;
    $user_directorate = $directorate_id;
    $user_designation = $designation_id;
} else if (isset($_SESSION['MM_Username_First_Login'])) {  // set new password
    // if ($current_page_url != 'set-new-password.php') {
    //     header("location: set-new-password.php");
    // }
    $user_name = $_SESSION['MM_Username_First_Login'];
} else if (isset($_SESSION['MM_Username_Email'])) {
    if ($current_page_url != 'otp') {
        header("location: otp.php");
    }
    $contractor_email = $_SESSION['MM_Username_Email'];
} else {
    if (!in_array($current_page_url, $auth_urls)) {
        header("location: index.php");
    }
}
