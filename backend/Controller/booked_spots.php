<?php
/**
 * @var PDO $pdo
 */

require "backend/Model/checkout.php";

require "backend/Model/booked_spots.php";

$paidReservations = getReservations($pdo, $_SESSION['user_id'], 1);

require "backend/View/booked_spots.php";