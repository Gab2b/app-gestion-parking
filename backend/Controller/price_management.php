<?php
/**
 * @var PDO $pdo
 */
require "backend/Model/price_management.php";

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'
) {
    $action = $_GET['action'] ?? '';
    if ($action === 'getRates') {
        echo json_encode(loadRates());
    } elseif ($action === 'saveRates') {
        $payload = json_decode(file_get_contents('php://input'), true);
        $ok = saveRates($payload);
        echo json_encode(['success' => $ok]);
    } else {
        echo json_encode(['error' => 'Action inconnue']);
    }
    exit();
}

require "backend/View/price_management.php";
