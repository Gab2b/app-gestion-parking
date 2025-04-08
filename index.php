<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
//require "Includes/database.php";
//require "Includes/functions.php";

if (isset($_GET['disconnect']) && $_GET['disconnect'] == 'true') {
    $_SESSION = array();
    session_destroy();
    header("Location: index.php");
    exit();
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (isset($_GET['component'])) {
        $componentName = cleanString($_GET['component']);
        if (file_exists("Controller/$componentName.php")) {
            require "Controller/$componentName.php";
        }
    }
    exit();
}
?>

    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>App Gestion Parking</title>
<!--        <link rel="stylesheet" href="assets/CSS/style.css">-->
    </head>

<div class="container">
    <?php
    if (isset($_SESSION['auth'])) {
//        require "_partials/navbar.php";
        if (isset($_GET['component'])) {
            $componentName = cleanString($_GET['component']);

            $canAccess = false;

//            if ($_SESSION['admin']) {
//                $canAccess = true;
//            } else if ($componentName === "achat") {
//                $canAccess = true;
//            }

            if ($canAccess && file_exists("Controller/$componentName.php")) {
                require "Controller/$componentName.php";
            } else {
                require "Controller/achat.php";
            }
        } else {
            require "Controller/achat.php";
        }
    } else {
        require 'Controller/map.php';
    }

    ?>
</div>
</body>