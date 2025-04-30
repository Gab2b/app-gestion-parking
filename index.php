<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
require "backend/_partials/database.php";
require "backend/_partials/helpers.php";

if (isset($_GET['disconnect']) && $_GET['disconnect'] == 'true') {
    session_destroy();
    header("Location: index.php");
    exit();
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (isset($_GET['component'])) {
        $componentName = cleanString($_GET['component']);
        if (file_exists("backend/Controller/$componentName.php")) {
            require "backend/Controller/$componentName.php";
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
        <link rel="stylesheet" href="frontend/css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>

    <body class="<?= isset($_SESSION['auth']) ? 'with-navbar' : 'login-page' ?>">
        <div class="container">
            <?php
            require "backend/_partials/navbar.php";
            require "backend/_partials/errors.php";
            if (isset($_SESSION['auth'])) {
                var_dump($_SESSION);
                if (isset($_GET['component'])) {
                    $componentName = cleanString($_GET['component']);

                    $canAccess = false;


                    //            if ($_SESSION['admin']) {
        //                $canAccess = true;
        //            } else if ($componentName === "ADMIN VIEWS ONLY") {
        //                $canAccess = true;
        //            }

                    if ($canAccess && file_exists("Controller/$componentName.php")) {
                        require "Controller/$componentName.php";
                    } else {
                        require "backend/Controller/layout.php";
                    }
                } else {
                    require "backend/Controller/layout.php";
                }
            } else {
                if (isset($_GET['component']) && cleanString($_GET['component'])==="login") {
                    require "backend/Controller/login.php";
                }
                else {
                    require "backend/Controller/layout.php";
                }
            }

            ?>
        </div>
    </body>
</html>