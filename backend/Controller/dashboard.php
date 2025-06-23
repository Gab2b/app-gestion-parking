<?php
/**
 * @var PDO $pdo
 */

require "backend/Model/dashboard.php";

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&

    (
        $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'
    ) &&

    $_SESSION['auth'] === true
) {
    try {
        $lastThreeReservations = getLastThreeReservations($pdo, $_SESSION['user_id']);
        $lastThreeVehicles = getLastThreeVehicles($pdo, $_SESSION['user_id']);
        $lastThreeExpiredReservations = getLastThreeExpiredReservations($pdo, $_SESSION['user_id']);

        echo json_encode([$lastThreeReservations, $lastThreeVehicles, $lastThreeExpiredReservations]);
        exit();
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
        exit();
    }
}

require "backend/View/dashboard.php";