<?php
/**
 * @var PDO $pdo
 */

require "backend/Model/admin_dashboard.php";

$allSpots = getFreeSpots($pdo);

$spotsStatus = [];
foreach ($allSpots as $spot) {
    $spotId = $spot['id'];  // ou 'number' si tu as un champ numéro
    $isFree = isSpotFree($pdo, $spotId);

    $spotsStatus[] = [
        'id' => $spotId,
        'number' => $spot['number'] ?? $spotId, // fallback sur ID si pas de 'number'
        'is_free' => $isFree
    ];
}

require "backend/View/admin_dashboard.php";