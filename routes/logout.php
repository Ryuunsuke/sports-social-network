<?php
session_start();        // Start or resume the session
$_SESSION = [];         // Clear all session variables

// If you want to remove the session cookie as well:
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();
header("Location: ../index.php");
exit();
?>