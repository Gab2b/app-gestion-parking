<?php
/**
 * @var PDO $pdo
 */

require "backend/Model/login.php";

if (!empty($_SERVER['CONTENT_TYPE']) && (($_SERVER['CONTENT_TYPE'] === 'application/json') || str_starts_with($_SERVER['CONTENT_TYPE'], 'application/x-www-form-urlencoded')))
{

    $emailAddress = !empty($_POST['email']) ? cleanString($_POST['email']) : null;
    $passcode = !empty($_POST['passcode']) ? cleanString($_POST['passcode']) : null;

    if (!empty($emailAddress) && !empty($passcode))
    {
        $user = getUser($pdo, $emailAddress);

        $isMatchPasscode = is_array($user) && password_verify($passcode, $user['password']);

        if ($isMatchPasscode)
        {
            $userStatus = getAdminStatus($pdo, $user['id']) ? 1 : 0;
            $_SESSION['auth'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_username'] = $user['mail'];
            $_SESSION['status'] = $userStatus;
            $errors[] = 'L\'identification a réussi';
            header('Content-Type: application/json');
            echo json_encode(['authentication' => true]);
            exit();
        }
        else
        {
            $errors[] = 'L\'identification a échoué';
            header("Content-Type: application/json");
            echo json_encode(['errors' => $errors]);
            exit();
        }

    }

    if (!empty($errors)) {
        header("Content-Type: application/json");
        echo json_encode(['errors' => $errors]);
        exit();
    }
}

require "backend/View/login.php";