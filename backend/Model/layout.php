<?php
function getAdminStatus(PDO $pdo, string $id): array | int
{
    $query = "SELECT status FROM is_admin WHERE user_id = :id";
    $status = $pdo->prepare($query);
    $status->bindParam(':id', $id);
    $status->execute();

    return $status->fetch(PDO::FETCH_ASSOC);
}