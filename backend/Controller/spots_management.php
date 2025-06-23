<?php
/**
* @var PDO $pdo
*/

require "backend/Model/spots_management.php";

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&

    (
        $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'
    ) &&

    $_SESSION['auth'] === true
) {
    $action = $_GET['action'];

    switch ($action) {
        case 'getAllSpots':
            echo json_encode(getAllSpots($pdo));
            break;

        case 'createSpot':
            $type = cleanString($_POST['type']);

            addSpot($pdo, $type);

            echo json_encode(['success' => true]);
            break;

        case 'updateSpot':
            $id = cleanString($_POST['id']);
            $type = cleanString($_POST['type']);

            updateSpot($pdo, $id, $type);

            echo json_encode(['success' => true]);
            break;

        case 'deleteSpot':
            $id = cleanString($_POST['id']);

            deleteSpot($pdo, $id);

            echo json_encode(['success' => true]);
            break;

        default:
            echo json_encode(['error' => 'Action inconnue']);
    }
    exit();
}

require "backend/View/spots_management.php";
