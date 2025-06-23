<?php

function getAllSpots(PDO $pdo): array
{
    $query = "SELECT * FROM parking_spot";

    $getSpots = $pdo->prepare($query);
    $getSpots->execute();

    return $getSpots->fetchAll(PDO::FETCH_ASSOC);
}

function addSpot(PDO $pdo, string $type): bool
{
    $query = "INSERT INTO parking_spot (id, type) VALUES (:id, :type)";

    $addSpot = $pdo->prepare($query);
    $addSpot->bindValue(":type", $type);
    $addSpot->bindValue(":id", getLastSpotId($pdo)+1);
    $addSpot->execute();

    return true;
}

function updateSpot(PDO $pdo, int $id, string $type): bool
{
    $query = "UPDATE parking_spot SET type = :type WHERE id = :id";

    $updateSpot = $pdo->prepare($query);
    $updateSpot->bindValue(":type", $type);
    $updateSpot->bindValue(":id", $id);
    $updateSpot->execute();

    return true;
}

function deleteSpot(PDO $pdo, int $id): bool
{
    $query = "DELETE FROM parking_spot WHERE id = :id";

    $deleteSpot = $pdo->prepare($query);
    $deleteSpot->bindValue(":id", $id);
    $deleteSpot->execute();

    return true;
}

function getLastSpotId(PDO $pdo) {
    $lastSpotId = $pdo->query("SELECT id FROM parking_spot ORDER BY id DESC LIMIT 1");
    $result = $lastSpotId->fetch(PDO::FETCH_ASSOC);
    return $result ? (int)$result['id'] : 0;
}