<?php
function getExpiredReservations(PDO $pdo, int $user_id) {

    $query = "SELECT reservations.id, id_parking_spot, reservations.id_vehicle, start_day, end_day, start_hour, end_hour, parking_spot.type, owns_vehicle.id_owner, vehicle.license_plate FROM reservations LEFT JOIN owns_vehicle ON owns_vehicle.id_vehicle = reservations.id_vehicle LEFT JOIN vehicle ON reservations.id_vehicle = vehicle.id LEFT JOIN parking_spot ON parking_spot.id = id_parking_spot WHERE CONCAT(end_day, ' ', end_hour) < NOW() AND owns_vehicle.id_owner = 1;SELECT reservations.id, id_parking_spot, reservations.id_vehicle, start_day, end_day, start_hour, end_hour, parking_spot.type, owns_vehicle.id_owner, vehicle.license_plate FROM reservations LEFT JOIN owns_vehicle ON owns_vehicle.id_vehicle = reservations.id_vehicle LEFT JOIN vehicle ON reservations.id_vehicle = vehicle.id LEFT JOIN parking_spot ON parking_spot.id = id_parking_spot WHERE CONCAT(end_day, ' ', end_hour) < NOW() AND owns_vehicle.id_owner = :owner_id;";

    $getLastThree = $pdo->prepare($query);
    $getLastThree->bindParam(":owner_id", $user_id, PDO::PARAM_INT);
    $getLastThree->execute();

    $lastThree = $getLastThree->fetchAll(PDO::FETCH_ASSOC);

    return $lastThree;
}