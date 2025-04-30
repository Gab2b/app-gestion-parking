<?php

function getUser(PDO $pdo, string $mail): array | bool
{
    $query = "SELECT id, mail, password, isAdmin FROM user WHERE mail = :mail";

    $user = $pdo->prepare($query);
    $user->bindParam(':mail', $mail);
    $user->execute();

    return $user->fetch();

}