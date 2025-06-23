<?php

function userExists($pdo, $username) {
    $stmt = $pdo->prepare("SELECT id FROM user WHERE mail = ?");
    $stmt->execute([$username]);
    return $stmt->fetch() !== false;
}

function registerUser($pdo, $hashedPassword, $lastName, $firstName, $phone, $email) {
    $stmt = $pdo->prepare("INSERT INTO user (lastName, firstName, mail, phoneNumber, password) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$lastName, $firstName, $email, $phone, $hashedPassword]);
}