<?php

function addNewVehicle(PDO $pdo, string $license_plate, string $brand, string $model, string $color)
{
    $query = "INSERT INTO `vehicle` (`license_plate`, `brand`, `model`, `color`) VALUES (:license_plate, :brand, :model, :color)";

    try {
        $insert = $pdo->prepare($query);

        $insert->bindParam(':license_plate', $license_plate);
        $insert->bindParam(':brand', $brand);
        $insert->bindParam(':model', $model);
        $insert->bindParam(':color', $color);

        $insert->execute();

        $newVehicleId = $pdo->lastInsertId();

        $query = "INSERT INTO `owns_vehicle` (`id_vehicle`, `id_owner`) VALUES (:vehicle_id, :owner_id)";

        $secondInsert = $pdo->prepare($query);
        $secondInsert->bindParam(':vehicle_id', $newVehicleId);
        $secondInsert->bindParam(':owner_id', $_SESSION['user_id']);

        $secondInsert->execute();

    } catch (PDOException $e) {
        return " Erreur " . $e->getCode() . ':</b>' . $e->getMessage();
    }

    return true;
}

function getAllUserVehiclesLicensesPlates(PDO $pdo, int $id): array
{
    $query = "SELECT vehicle.license_plate, vehicle.id FROM `vehicle` LEFT JOIN `owns_vehicle` ON owns_vehicle.id_vehicle = vehicle.id WHERE owns_vehicle.id_owner = :id";

    $allVehicles = $pdo->prepare($query);
    $allVehicles->bindParam(':id', $id);
    $allVehicles->execute();

    return $allVehicles->fetchAll(PDO::FETCH_ASSOC);
}

function getUserVehicles($pdo, $id)
{
    $query = "SELECT * FROM `vehicle` LEFT JOIN `owns_vehicle` ON owns_vehicle.id_vehicle = vehicle.id WHERE owns_vehicle.id_owner = :id";

    $userVehicles = $pdo->prepare($query);
    $userVehicles->bindParam(':id', $id);
    $userVehicles->execute();

    return $userVehicles->fetchAll(PDO::FETCH_ASSOC);
}

function deleteVehicle($pdo, int $id)
{
    $query = "DELETE FROM `vehicle` WHERE id = :id";

    $deleteVehicle = $pdo->prepare($query);
    $deleteVehicle->bindParam(':id', $id);
    $deleteVehicle->execute();

    return true;
}

function updateVehicle($pdo, int $id, string $license_plate, string $brand, string $model, string $color) {

    $query = "UPDATE `vehicle` SET license_plate = :license_plate, brand = :brand, model = :model, color = :color WHERE id = :id";

    $updateVehicle = $pdo->prepare($query);

    $updateVehicle->bindParam(':license_plate', $license_plate);
    $updateVehicle->bindParam(':brand', $brand);
    $updateVehicle->bindParam(':model', $model);
    $updateVehicle->bindParam(':color', $color);
    $updateVehicle->bindParam(':id', $id);

    $updateVehicle->execute();

    return true;
}