<?php
function getLastThreeReservations(PDO $pdo, int $user_id) {
    $query = "SELECT * FROM `reservations` LEFT JOIN owns_vehicle ON owns_vehicle.id_vehicle = reservations.id_vehicle WHERE owns_vehicle.id_owner = :owner_id ORDER BY `reservations`.`start_day` DESC, `reservations`.`start_hour` DESC LIMIT 3;";

    $getLastThree = $pdo->prepare($query);
    $getLastThree->bindParam(":owner_id", $user_id, PDO::PARAM_INT);
    $getLastThree->execute();

    $lastThree = $getLastThree->fetchAll(PDO::FETCH_ASSOC);

    return $lastThree;
}

function getLastThreeVehicles(PDO $pdo, int $user_id) {
    $query = "SELECT license_plate FROM `vehicle` LEFT JOIN owns_vehicle ON owns_vehicle.id_vehicle = vehicle.id WHERE owns_vehicle.id_owner = :owner_id LIMIT 3;";

    $getLastThree = $pdo->prepare($query);
    $getLastThree->bindParam(":owner_id", $user_id, PDO::PARAM_INT);
    $getLastThree->execute();

    $lastThree = $getLastThree->fetchAll(PDO::FETCH_ASSOC);

    return $lastThree;
}

function getLastThreeExpiredReservations(PDO $pdo, int $user_id) {

    $query = "SELECT * FROM reservations LEFT JOIN owns_vehicle ON owns_vehicle.id_vehicle = reservations.id_vehicle WHERE CONCAT(end_day, ' ', end_hour) < NOW() AND owns_vehicle.id_owner = :owner_id  LIMIT 3;";

    $getLastThree = $pdo->prepare($query);
    $getLastThree->bindParam(":owner_id", $user_id, PDO::PARAM_INT);
    $getLastThree->execute();

    $lastThree = $getLastThree->fetchAll(PDO::FETCH_ASSOC);

    return $lastThree;
}