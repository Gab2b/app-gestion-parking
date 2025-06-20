<?php
/**
 * @var PDO $pdo
 */

require "backend/Model/old_bookings.php";

if ($_SESSION['auth'] === true) {
    $oldReservations = getExpiredReservations($pdo, $_SESSION['user_id']);
}

require "backend/View/old_bookings.php";