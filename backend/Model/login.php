<?php

function getUser(PDO $pdo, string $mail): array | bool
{
    $query = "SELECT id, mail, password FROM user WHERE mail = :mail";

    $user = $pdo->prepare($query);
    $user->bindParam(':mail', $mail);
    $user->execute();

    return $user->fetch();
}

function getAdminStatus(PDO $pdo, string $id): int
{
    $query = "SELECT status FROM is_admin WHERE user_id = :id";
    $status = $pdo->prepare($query);
    $status->bindParam(':id', $id);
    $status->execute();

    $response = $status->fetch(PDO::FETCH_ASSOC);
    return ($response['status'] && $response['status'] == 1) ? 1 : 0;
}