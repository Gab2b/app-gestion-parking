<link rel="stylesheet" href="frontend/css/booked_spots.css">


    <br>
    <h1>Réservations payées : </h1>
    <br>
    <table class="table">
        <thead>
        <tr>
            <th scope="col"><a href="booking">N° de Réservation</a></th>
            <th scope="col"><a href="booking">N° de Place</a></th>
            <th scope="col"><a href="booking">Véhicule</a></th>
            <th scope="col"><a href="booking">Type</a></th>
            <th scope="col"><a href="booking">Jour de début</a></th>
            <th scope="col"><a href="booking">Heure de début</a></th>
            <th scope="col"><a href="booking">Jour de fin</a></th>
            <th scope="col"><a href="booking">Heure de fin</a></th>


            <th scope="col"><a href="#">Actions</th>
        </tr>
        </thead>
        <tbody>

        <?php foreach($paidReservations as $paidReservation) :?>

            <tr class="table align-middle">
                <td><?php echo$paidReservation['id']?></td>
                <td><?php echo$paidReservation['id_parking_spot']?></td>
                <td><?php echo$paidReservation['license_plate']?></td>
                <td><?php echo$paidReservation['type']?></td>
                <td><?php echo$paidReservation['start_day']?></td>
                <td><?php echo$paidReservation['start_hour']?></td>
                <td><?php echo$paidReservation['end_day']?></td>
                <td><?php echo$paidReservation['end_hour']?></td>

                <td>
                    <a data-id="<?php echo$paidReservation['id']?>"><i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h4>Vous ne trouvez pas ce que vous cherchez ?</h4>
    <h5><a href="checkout">Voir les réservations non payées</a></h5>