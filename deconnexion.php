<?php
session_start();
$_SESSION = array(); //pour détruire les variables de session
// Si vous voulez détruire complètement la session, effacez également le cookie de session.
// Note : cela détruira la session et pas seulement les données de session !
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();
//Suivi d'une redirection vers la page d'accueil
header ("location:index.php");
?>
