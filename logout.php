<?php
session_start();
$_SESSION = array();

if (!isset($_SESSION['MM_Username'])) {
    $last_url = isset($_SESSION['last_accessed_url']) ? $_SESSION['last_accessed_url'] : '/';
    header('Location: login.php?returnUrl=' . urlencode($last_url));
}


if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}
session_destroy();
header("location:index.php");
