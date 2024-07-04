<?php
session_start(); // Démarre ou reprend la session

// Détruit toutes les données de session
$_SESSION = array();

// Si vous utilisez des cookies de session, vous devriez aussi les détruire
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalement, détruire la session
session_destroy();

// Redirection vers la page de connexion ou une autre page après la déconnexion
header("Location: login.php");
exit;
?>