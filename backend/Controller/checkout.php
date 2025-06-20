<?php
/**
 * @var PDO $pdo
 */


require "backend/Model/checkout.php";

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&

    (
        $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'
    ) &&

    $_SESSION['auth'] === true
) {
    if (isset($_POST) && isset($_GET)) {
        switch ($_GET['action']) {
            case 'payOne':
                var_dump('payOne');
//                payThisReservation($pdo, $_POST['reservationId']);
                break;

            case 'payAll':
                var_dump('payALL');
//                payAllReservations($pdo, $_SESSION['user_id']);
                break;

            case 'getAvailableSpots':
                $startingDate = cleanString($_POST['startingDate']);
                $endingDate = cleanString($_POST['endingDate']);
                $startingHour = cleanString($_POST['startingHour']);
                $endingHour = cleanString($_POST['endingHour']);
                $oldSpotId = cleanString($_POST['oldSpotId']);

//                var_dump($oldSpotId, $startingDate, $endingDate, $startingHour, $endingHour);

                $allSpots = getFreeSpots($pdo);
                $freeSpots [] = getOldSpotInfos($pdo, $oldSpotId)[0];
                foreach ($allSpots as $spot) {
                    if (checkSpotHourly($pdo, $spot["id"], $startingDate, $endingDate, $startingHour, $endingHour)) {
                        $freeSpots[] = $spot;
                    }
                }

                echo json_encode(["spots" => $freeSpots]);
                exit();
                break;

            case 'getVehicles':
                echo json_encode(["userVehicles" => getUserVehicles($pdo, $_SESSION['user_id'])]);
                exit();
                break;

            case 'deleteReservation':
                $reservationId = cleanString($_POST['reservationId']);

                deleteReservation($pdo, $reservationId);

                echo json_encode(['success' => true]);
                exit();
                break;

            case 'updateReservation':

//                var_dump($_POST);

                $spotId = cleanString($_POST['spotSelected']);
                $reservationId = cleanString($_POST['reservationId']);
                $vehicleId = cleanString($_POST['vehicleSelected']);

                updateReservation($pdo, $reservationId, $spotId, $vehicleId);

                echo json_encode(["success" => true]);

                exit();
                break;

            default:
                var_dump('NOTHING');
                var_dump($_GET);
                break;
        }
    }


} else if ($_SESSION['auth'] === true) {
    $unpaidReservations = getReservations($pdo, $_SESSION['user_id'], 0);
    foreach ($unpaidReservations as &$unpaidReservation) {
        $startingTimestamp = strtotime($unpaidReservation['start_day']);
        $endingTimestamp = strtotime($unpaidReservation['end_day']);
        $startingDay = strtolower(date('l', $startingTimestamp));
        $endingDay = strtolower(date('l', $endingTimestamp));

        $totalPrice = compareHourly($startingDay, $endingDay, $unpaidReservation['start_hour'], $unpaidReservation['end_hour']);
        $unpaidReservation['total_price'] = $totalPrice;
    }
}

require "backend/View/checkout.php";