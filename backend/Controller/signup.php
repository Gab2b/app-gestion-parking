<?php
/**
 * @var PDO $pdo
 */

require "backend/Model/signup.php";

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = cleanString($_POST['mail']);
    $lastName = cleanString($_POST['last_name']);
    $firstName = cleanString($_POST['first_name']);
    $phone = cleanString($_POST['phone']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($password) || empty($confirmPassword)) {
        $error = 'Tous les champs sont obligatoires.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Les mots de passe ne correspondent pas.';
    } elseif (userExists($pdo, $mail)) {
        $error = 'Ce mail est déjà utilisé.';
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        if (registerUser($pdo, $hashedPassword, $lastName, $firstName, $phone, $mail)) {
            $success = 'Inscription réussie !';
        } else {
            $error = 'Erreur lors de l’enregistrement.';
        }
    }
}


require "backend/View/signup.php";