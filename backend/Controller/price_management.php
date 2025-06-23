<?php

require "backend/Model/price_management.php";


if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
$_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {

$action = $_GET['action'] ?? '';

if ($action === 'getRates') {
echo json_encode(loadRates());
exit;
}

if ($action === 'saveRates') {
$payload = json_decode(file_get_contents('php://input'), true);
$ok = saveRates($payload);
echo json_encode($ok ? ['success'=>true] : ['error'=>'write_failed']);
exit;
}

echo json_encode(['error' => 'unknown_action']);
exit;
}

require "backend/View/price_management.php";
