<?php
function getReservations(PDO $pdo, int $id_owner, int $is_paid) {
    $query = 'SELECT reservations.id, id_parking_spot, reservations.id_vehicle, vehicle.license_plate, start_day, end_day, start_hour, end_hour, is_paid, owns_vehicle.id_owner, parking_spot.type FROM `reservations` LEFT JOIN owns_vehicle ON reservations.id_vehicle = owns_vehicle.id_vehicle LEFT JOIN parking_spot ON reservations.id_parking_spot = parking_spot.id LEFT JOIN `vehicle` ON vehicle.id = reservations.id_vehicle WHERE owns_vehicle.id_owner = :id_owner AND is_paid = :is_paid;';

    $getReservations = $pdo->prepare($query);
    $getReservations->bindParam(':id_owner', $id_owner);
    $getReservations->bindParam(':is_paid', $is_paid);
    $getReservations->execute();

    $allReservations = $getReservations->fetchAll(PDO::FETCH_ASSOC);
    return $allReservations;
}

function payThisReservation(PDO $pdo, int $id_reservation) {
    $query = "UPDATE `reservations` SET `is_paid` = '1' WHERE `reservations`.`id` = :id_reservation;";

    $payReservation = $pdo->prepare($query);
    $payReservation->bindParam(':id_reservation', $id_reservation);
    $payReservation->execute();

    return true;
}

function payAllReservations(PDO $pdo, int $id_owner) {
    $query = "UPDATE `reservations` LEFT JOIN owns_vehicle ON reservations.id_vehicle = owns_vehicle.id_vehicle SET reservations.is_paid = '1' WHERE owns_vehicle.id_owner = :id_owner AND is_paid = 0;";

    $payALlReservations = $pdo->prepare($query);
    $payALlReservations->bindParam(':id_owner', $id_owner);
    $payALlReservations->execute();

    return true;
}

function getUserVehicles(PDO $pdo, int $id): array
{
    $query = "SELECT vehicle.license_plate, vehicle.id FROM `vehicle` LEFT JOIN `owns_vehicle` ON owns_vehicle.id_vehicle = vehicle.id WHERE owns_vehicle.id_owner = :id";

    $allVehicles = $pdo->prepare($query);
    $allVehicles->bindParam(':id', $id);
    $allVehicles->execute();

    return $allVehicles->fetchAll(PDO::FETCH_ASSOC);
}

function getOldSpotInfos(PDO $pdo, int $oldSpotId)
{
    $query = "SELECT * FROM `parking_spot` WHERE `id` = :oldSpotId;";

    $getInfos = $pdo->prepare($query);
    $getInfos->bindParam(':oldSpotId', $oldSpotId);
    $getInfos->execute();

    return $getInfos->fetchAll(PDO::FETCH_ASSOC);
}

function getFreeSpots(PDO $pdo) {
    $query = "SELECT * FROM `parking_spot`";
    $allSpots = $pdo->prepare($query);

    $allSpots->execute();
    $allSpots = $allSpots->fetchAll(PDO::FETCH_ASSOC);

    return $allSpots;
}

function compareHourly(string $starting_day, string $ending_day, string $starting_hour, string $ending_hour): int{
    $hourly_days = calculateHours($starting_day, $ending_day, $starting_hour, $ending_hour);
    $parking_infos = require "backend/_partials/parkingInfos.php";
    $parking_rates = $parking_infos['parking_rate'];
    $price = 0;

    foreach ($hourly_days as $day) {
        foreach ($parking_rates as $parking_rate) {
            if ($day["day"] === $parking_rate['day']) {
                if ($parking_rate['prices']) {
                    if (is_int($parking_rate['prices'])) {
                        $starting_day_hour = new DateTime($day["starting_hour"]);
                        $ending_day_hour   = ($day["ending_hour"] === "24:00")
                            ? (clone $starting_day_hour)->modify('+1 day')->setTime(0, 0)
                            : new DateTime($day["ending_hour"]);

                        $interval = $starting_day_hour->diff($ending_day_hour);
                        $minutes  = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

                        $hours = (int)ceil($minutes / 60);

                        $price += $hours * $parking_rate['prices'];

                    } else if (is_array($parking_rate['prices'])) {
                        foreach ($parking_rate['prices'] as $range) {
                            $range_from = new DateTime($range['from']);
                            $range_to = ($range['to'] === $day["ending_hour"])
                                ? (clone $range_from)->modify('+1 day')->setTime(0, 0)
                                : new DateTime($range['to']);

                            $period_from = new DateTime($day["starting_hour"]);
                            $period_to = ($day["ending_hour"] === "24:00")
                                ? (clone $period_from)->modify('+1 day')->setTime(0, 0)
                                : new DateTime($day["ending_hour"]);

                            $start = max($range_from, $period_from);
                            $end = min($range_to, $period_to);

                            if ($start < $end) {
                                $interval = $start->diff($end);
                                $minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
                                $hours_due = ceil($minutes / 60);
                                $price += $hours_due * $range['price'];
                            }
                        }

                    }
                }
            }
        }
    }
    return $price;
}

function calculateHours(string $starting_day, string $ending_day, string $starting_hour, string $ending_hour): array
{
    $daylist = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    $hourly_days = [];
    if($starting_day === $ending_day){
        $hourly_days = [
            [
                'day' => $starting_day,
                'starting_hour' => $starting_hour,
                'ending_hour' => $ending_hour
            ]
        ];
    } else {
        $index=0;
        $current_day = $starting_day;
        while($current_day != $daylist[array_search($ending_day, $daylist) ]){

            $current_day = $daylist[array_search($starting_day, $daylist)+$index];

            if ($current_day === $starting_day) {
                $current_starting_hour = $starting_hour;
            } else {
                $current_starting_hour = '00:00';
            }

            if ($current_day === $ending_day) {
                $current_ending_hour = $ending_hour;
            } else {
                $current_ending_hour = '24:00';
            }

            if (array_search($current_day, $daylist)+$index === 6) {
                $index = -6;
            } else {
                $index++;
            }
            array_push($hourly_days, ['day' => $current_day, 'starting_hour' => $current_starting_hour, 'ending_hour' => $current_ending_hour]);

        }
    }

    return $hourly_days;
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

function updateReservation(PDO $pdo, int $reservationId, int $spotId, int $vehicleId) {
    $query = "UPDATE `reservations` SET `id_vehicle` = :vehicle_id, id_parking_spot = :spot_id WHERE `reservations`.`id` = :reservationId;";

    $updateReservation = $pdo->prepare($query);
    $updateReservation->bindParam(':reservationId', $reservationId);
    $updateReservation->bindParam(':spot_id', $spotId);
    $updateReservation->bindParam(':vehicle_id', $vehicleId);
    $updateReservation->execute();

    return true;
}

function deleteReservation(PDO $pdo, int $reservationId) {
    $query = "DELETE FROM `reservations` WHERE `reservations`.`id` = :reservationId;";

    $deleteReservation = $pdo->prepare($query);
    $deleteReservation->bindParam(':reservationId', $reservationId);
    $deleteReservation->execute();

    return true;
}