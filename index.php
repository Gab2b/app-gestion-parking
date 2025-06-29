<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/';
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
        <link rel="stylesheet" href="frontend/assets/bootstrap-5.3.6-dist/css/bootstrap.css">
        <link rel="stylesheet" href="frontend/assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
        <link href="frontend/assets/fontawesome-free-6.7.2-web/css/fontawesome.css" rel="stylesheet" />
        <link href="frontend/assets/fontawesome-free-6.7.2-web/css/solid.css" rel="stylesheet" />
        <link rel="stylesheet" href="frontend/assets/sweetalert2-11.22.0/dist/sweetalert2.min.css">
        <base href="<?php echo $basePath; ?>">
    </head>

    <body class="<?= isset($_SESSION['auth']) ? 'with-navbar' : 'login-page' ?>">
        <?php require "backend/_partials/navbar.php";?>
        <div class="container">
            <?php

            $errors = [];

            $publicViews = array("login", "signup");
            $adminViews = array("admin_dashboard", "price_management", "spots_management");

            if (isset($_SESSION['auth'])) {
                if (isset($_GET['component'])) {
                    $componentName = cleanString($_GET['component']);

                    $adminAccess = false;
                    $userAccess = false;

                    if ($_SESSION['auth'] === true) {
                        $userAccess = true;
                        if (isset($_SESSION['status']) && $_SESSION['status'] === 1) {
                            $adminAccess = true;
                        }
                    };

                    if (isset($_GET) && in_array(cleanString($_GET['component']), $publicViews)) {
                        $errors[] = 'Vous êtes déjà connecté !';
                        header("Location: dashboard");

                    } else {
                        if ($userAccess && file_exists("backend/Controller/$componentName.php")) {
                            if (in_array($componentName, $adminViews)) {
                                if ($adminAccess) {
                                    require "backend/Controller/$componentName.php";
                                } else {
                                    require "backend/Controller/dashboard.php";
                                }
                            } else {
                                require "backend/Controller/$componentName.php";
                            }
                        } else {
                            require "backend/Controller/layout.php";
                        }
                    }

                } else {
                    require "backend/Controller/layout.php";
                }
            } else {
                if (isset($_GET['component']) && in_array(cleanString($_GET['component']), $publicViews)) {
                    require "backend/Controller/".cleanString($_GET['component']).".php";
                }
                else {
                    require "backend/Controller/layout.php";
                }
            }

            require "backend/_partials/errors.php";

            ?>
        </div>
    </body>
</html>