<?php
function isSpotFree(PDO $pdo, string $spot_id): bool
{
    $now = new DateTime();
    $oneMinuteLater = (clone $now)->modify('+1 minute');

    $starting_day = $now->format('Y-m-d');
    $starting_hour = $now->format('H:i');

    $ending_day = $oneMinuteLater->format('Y-m-d');
    $ending_hour = $oneMinuteLater->format('H:i');

    return checkSpotHourly($pdo, $spot_id, $starting_day, $ending_day, $starting_hour, $ending_hour);
}

function getFreeSpots(PDO $pdo) {
    $query = "SELECT * FROM `parking_spot`";
    $allSpots = $pdo->prepare($query);

    $allSpots->execute();
    $allSpots = $allSpots->fetchAll(PDO::FETCH_ASSOC);

    return $allSpots;
}

function checkSpotHourly (PDO $pdo, string $spot_id, string $starting_day, string $ending_day, string $starting_hour, string $ending_hour) {

    $query = "SELECT * FROM `reservations` WHERE `id_parking_spot` = :id";

    $getSpotReservations = $pdo->prepare($query);
    $getSpotReservations->bindParam(':id', $spot_id);
    $getSpotReservations->execute();

    $allReservations = $getSpotReservations->fetchAll(PDO::FETCH_ASSOC);

    $start = new DateTime("$starting_day $starting_hour");
    $end = new DateTime("$ending_day $ending_hour");

    foreach ($allReservations as $reservation) {

        $existingStart = new DateTime($reservation['start_day'].' '.$reservation['start_hour']);
        $existingEnd   = new DateTime($reservation['end_day'].' '.$reservation['end_hour']);

        if (
            !($end <= $existingStart || $start >= $existingEnd)
        )
        {
            return false;
        }
    }

    return true;

}
