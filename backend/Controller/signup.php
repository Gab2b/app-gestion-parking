<?php
/**
 * @var PDO $pdo
 */

require "backend/Model/signup.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = cleanString($_POST['mail']);
    $lastName = cleanString($_POST['last_name']);
    $firstName = cleanString($_POST['first_name']);
    $phone = cleanString($_POST['phone']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    var_dump($_POST);
    var_dump('Here');

    if (empty($password) || empty($confirmPassword)) {
        var_dump('Tous les champs sont obligatoires.');
    } elseif ($password !== $confirmPassword) {
        var_dump('Les mots de passe ne correspondent pas.');
    } elseif (userExists($pdo, $mail)) {
        var_dump('Ce nom d’utilisateur est déjà pris.');
    } else {
        var_dump('else');
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        if (registerUser($pdo, $hashedPassword, $lastName, $firstName, $phone, $mail)) {
            var_dump('Inscription réussie !');
        } else {
            var_dump('Erreur lors de l’enregistrement.');
        }
    }

    var_dump('End');
}

require "backend/View/signup.php";