<link rel="stylesheet" href="frontend/css/old_bookings.css"

<div class="container">
    <br>
    <h1>Réservations passées : </h1>
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

        </tr>
        </thead>
        <tbody>

        <?php foreach($oldReservations as $oldReservation) :?>

            <tr class="table align-middle">
                <td><?php echo$oldReservation['id']?></td>
                <td><?php echo$oldReservation['id_parking_spot']?></td>
                <td><?php echo$oldReservation['license_plate']?></td>
                <td><?php echo$oldReservation['type']?></td>
                <td><?php echo$oldReservation['start_day']?></td>
                <td><?php echo$oldReservation['start_hour']?></td>
                <td><?php echo$oldReservation['end_day']?></td>
                <td><?php echo$oldReservation['end_hour']?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>