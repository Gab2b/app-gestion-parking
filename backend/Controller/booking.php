<?php
/**
 * @var PDO $pdo
 */

require "backend/Model/booking.php";

if ($_SESSION["auth"] === true) {
    $vehicles = getUserVehicles($pdo, $_SESSION['user_id']);
}

require "backend/View/booking.php";