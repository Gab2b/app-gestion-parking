<?php

function getUserVehicles(PDO $pdo, int $id): array
    {
    $query = "SELECT vehicle.license_plate, vehicle.id FROM `vehicle` LEFT JOIN `owns_vehicle` ON owns_vehicle.id_vehicle = vehicle.id WHERE owns_vehicle.id_owner = :id";

    $allVehicles = $pdo->prepare($query);
    $allVehicles->bindParam(':id', $id);
    $allVehicles->execute();

    return $allVehicles->fetchAll(PDO::FETCH_ASSOC);
}

function getVehicleId(PDO $pdo, string $plate, int $id): int
{
    $query = "SELECT vehicle.id FROM `vehicle` LEFT JOIN `owns_vehicle` ON owns_vehicle.id_vehicle = vehicle.id WHERE owns_vehicle.id_owner = :id AND vehicle.license_plate = :plate";

    $vehicleID = $pdo->prepare($query);
    $vehicleID->bindParam(':id', $id);
    $vehicleID->bindParam(':plate', $plate);
    $vehicleID->execute();

    $result = $vehicleID->fetch(PDO::FETCH_ASSOC);
    return $result['id'];
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

function bookASpot(PDO $pdo, string $spot_id, string $vehicle_plate, string $starting_day, string $ending_day, string $starting_hour, string $ending_hour, int $isPaid)
{
    $vehicle_id = getVehicleId($pdo, $vehicle_plate, $_SESSION['user_id']);
    $query = 'INSERT INTO `reservations` (`id_parking_spot`, `id_vehicle`, `start_day`, `end_day`, `start_hour`, `end_hour`, `is_paid`) VALUES (:spot_id, :vehicle_id, :start_day, :end_day, :start_hour, :end_hour, :is_paid)';

    $bookSpot = $pdo->prepare($query);

    $bookSpot->bindParam(':spot_id', $spot_id);
    $bookSpot->bindParam(':vehicle_id', $vehicle_id);
    $bookSpot->bindParam(':start_day', $starting_day);
    $bookSpot->bindParam(':end_day', $ending_day);
    $bookSpot->bindParam(':start_hour', $starting_hour);
    $bookSpot->bindParam(':end_hour', $ending_hour);
    $bookSpot->bindParam(':is_paid', $isPaid);
    $bookSpot->execute();

    $bookSpot->closeCursor();

    return true;
}