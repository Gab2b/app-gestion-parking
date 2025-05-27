<?php

function getUserVehicles(PDO $pdo, int $id): array
{
$query = "SELECT vehicle.license_plate, vehicle.id FROM `vehicle` LEFT JOIN `owns_vehicle` ON owns_vehicle.id_vehicle = vehicle.id WHERE owns_vehicle.id_owner = :id";

$allVehicles = $pdo->prepare($query);
$allVehicles->bindParam(':id', $id);
$allVehicles->execute();

return $allVehicles->fetchAll(PDO::FETCH_ASSOC);
}