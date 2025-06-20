<?php
function getUserProfile(PDO $pdo, int $userId)
{
    $query = "SELECT firstName, lastName, mail, phoneNumber FROM user WHERE id = :id";
    $getProfile = $pdo->prepare($query);
    $getProfile->bindValue(':id', $userId, PDO::PARAM_INT);
    $getProfile->execute();

    $profile = $getProfile->fetch(PDO::FETCH_ASSOC);

    return $profile;
}

function updateUserProfile(PDO $pdo, int $userId, string $firstName, string $lastName, string $email, string $phone)
{
    $query = "UPDATE user SET firstName = :first, lastName = :last, mail = :mail, phoneNumber = :phone WHERE id = :id";

    $updateProfile = $pdo->prepare($query);
    $updateProfile->bindValue(':first', $firstName);
    $updateProfile->bindValue(':last',  $lastName);
    $updateProfile->bindValue(':mail',  $email);
    $updateProfile->bindValue(':phone', $phone);
    $updateProfile->bindValue(':id',    $userId, PDO::PARAM_INT);

    $updateProfile->execute();

    return true;
}
