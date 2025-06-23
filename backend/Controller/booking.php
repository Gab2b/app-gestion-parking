<?php
/**
 * @var PDO $pdo
 */

require "backend/Model/booking.php";

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&

    (
        $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'
    ) &&

    $_SESSION['auth'] === true
) {
    if (isset($_GET["action"])) {
        $action = $_GET["action"];
        switch ($action) {
            case "calculatePrice":
                $startingDate = strtolower(date('l', strtotime(cleanString($_GET["startingDate"]))));
                $endingDate = strtolower(date('l', strtotime(cleanString($_GET["endingDate"]))));
                $startingHour = cleanString($_GET["startingHour"]);
                $endingHour = cleanString($_GET["endingHour"]);

                $totalPrice = compareHourly($startingDate, $endingDate, $startingHour, $endingHour);

                if (!is_int($totalPrice)) {
                    header('Content-Type: application/json');
                    echo json_encode(['error'=>'Erreur de calcul du tarif']);
                    exit();
                }

                header('Content-Type: application/json');
                echo json_encode($totalPrice);
                exit();

                break;

            case "bookASpot":
                $startingDate = $_POST["startingDate"];
                $startingHour = $_POST["startingHour"];
                $endingHour = $_POST["endingHour"];

                if (isset($_POST["keepSameDate"])) {
                    $endingDate = $startingDate;
                } else {
                    $endingDate = $_POST["endingDate"];
                }

                $allSpots = getFreeSpots($pdo);
                $freeSpots = [];
                foreach ($allSpots as $spot) {
                    if (checkSpotHourly($pdo, $spot["id"], $startingDate, $endingDate, $startingHour, $endingHour)) {
                        $freeSpots[] = $spot;
                    }
                }

                echo json_encode(['success' => true, 'spots' => $freeSpots]);
                exit();

                break;

            case "holdASpot":
                $startingDate = $_POST["startingDate"];
                $startingHour = $_POST["startingHour"];
                $endingHour = $_POST["endingHour"];

                $vehiclePlate = $_POST["vehiclePlate"];

                if (isset($_POST["isPaid"])) {
                    $isPaid = 1;
                } else {
                    $isPaid = 0;
                }

                if (isset($_POST["keepSameDate"])) {
                    $endingDate = $startingDate;
                } else {
                    $endingDate = $_POST["endingDate"];
                }

                if (bookASpot($pdo, $_GET["spot"], $vehiclePlate ,$startingDate, $endingDate, $startingHour, $endingHour, $isPaid)) {
                    echo json_encode(['success' => true]);
                    exit();
                } else {
                    echo json_encode(['error'=>'Erreur de réservation côté serveur']);
                    exit();
                }
                break;
        }
    }
} else if ($_SESSION['auth'] === true) {
    $vehicles = getUserVehicles($pdo, $_SESSION['user_id']);
}

require "backend/View/booking.php";