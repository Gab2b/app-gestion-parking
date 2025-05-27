<?php
/**
 * @var PDO $pdo
 */

require "backend/Model/vehicles.php";

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&

    (
        $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'
    ) &&

    $_SESSION['auth'] === true
)
{
    $actionName = $_GET['action'] ?? null;
    switch ($actionName) {
        case 'addVehicle':
            $license_plate = !empty($_POST['license_plate']) ? cleanString($_POST['license_plate']) : null;
            $brand = !empty($_POST['brand']) ? cleanString($_POST['brand']) : null;
            $model = !empty($_POST['model']) ? cleanString($_POST['model']) : null;
            $color = !empty($_POST['color']) ? cleanString($_POST['color']) : null;

            if (
                empty($license_plate) ||
                empty($brand) ||
                empty($model) ||
                empty($color)
            ) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Tous les champs sont obligatoires']);
                exit();
            }

            $userVehiclesPlates = getAllUserVehiclesLicensesPlates($pdo, $_SESSION['user_id']);

            if ($userVehiclesPlates !== null) {
                foreach ($userVehiclesPlates as $userVehiclePlate) {
                    if ($userVehiclePlate['license_plate'] === $license_plate) {
                        header('Content-Type: application/json');
                        echo json_encode(['error' => "Vous avez déjà véhicule avec cette plaque d'immatriculation !"]);
                        exit();
                    }
                }

                $newVehicle = addNewVehicle($pdo, $license_plate, $brand, $model, $color);

                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit();
            }

            break;

        case 'updateVehicle':
            $license_plate = !empty($_POST['license_plate']) ? cleanString($_POST['license_plate']) : null;
            $brand = !empty($_POST['brand']) ? cleanString($_POST['brand']) : null;
            $model = !empty($_POST['model']) ? cleanString($_POST['model']) : null;
            $color = !empty($_POST['color']) ? cleanString($_POST['color']) : null;
            $vehicleId = !empty($_GET['id']) ? cleanString($_GET['id']) : null;

            if (!empty($vehicleId) && is_numeric($vehicleId)) {

                $vehicleId = intval($vehicleId);
                $userVehiclesPlates = getAllUserVehiclesLicensesPlates($pdo, $_SESSION['user_id']);

                if ($userVehiclesPlates !== null) {
                    foreach ($userVehiclesPlates as $userVehiclePlate) {
                        if ($userVehiclePlate['license_plate'] == $license_plate && $userVehiclePlate['id'] !== $vehicleId) {
                            header('Content-Type: application/json');
                            echo json_encode(['error' => "Un véhicule avec cette plaque d'immatriculation existe déjà"]);
                            exit();
                        }
                    }

                    updateVehicle($pdo, $vehicleId, $license_plate, $brand, $model, $color);

                    header('Content-Type: application/json');
                    echo json_encode(['success' => true]);
                    exit();

                } else {
                    header('Content-Type: application/json');
                    echo json_encode(['error' => "Problème serveur"]);
                    exit();
                }
            }

            header('Content-Type: application/json');
            echo json_encode(['error' => 'Vous tentez de supprimer un véhicule fantôme']);
            exit();

            break;

        case 'deleteVehicle':
            $vehicleId = !empty($_GET['id']) ? cleanString($_GET['id']) : null;
            if (!empty($vehicleId) && is_numeric($vehicleId)) {
                deleteVehicle($pdo, $vehicleId);
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit();
            }

            break;

        case 'showUserVehicles':
            $userVehicles = getUserVehicles($pdo, $_SESSION['user_id']);

            header('Content-Type: application/json');
            echo json_encode($userVehicles);
            exit();

            break;
    }
}

require "backend/View/vehicles.php";