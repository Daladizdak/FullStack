<?php
session_start();

// Completely destroy the session
$_SESSION = [];
session_unset();
session_destroy();

// Force delete session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect to index AFTER destroying session
header("Location: index.php");
exit;
