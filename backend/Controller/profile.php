<?php
/**
 * @var PDO $pdo
 */
//
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require "backend/Model/profile.php";

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&

    (
        $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'
    ) &&

    $_SESSION['auth'] === true
) {
    $action = $_GET['action'];

    switch ($action) {

        case 'getProfile':
            echo json_encode(getUserProfile($pdo, $_SESSION['user_id']));
            break;

        case 'updateProfile':
            $first = cleanString($_POST['first_name']);
            $last  = cleanString($_POST['last_name']);
            $mail  = cleanString($_POST['email']);
            $phone = cleanString($_POST['phone_number']);

            updateUserProfile($pdo, $_SESSION['user_id'], $first, $last, $mail, $phone);

            echo json_encode(['success' => true]);
            break;

        default:
            echo json_encode(['error' => 'Action inexistante']);
    }
    exit();
}

require "backend/View/profile.php";
