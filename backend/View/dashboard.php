<link rel="stylesheet" href="frontend/css/dashboard.css">

    <h1>Mon espace</h1>

    <h3>Bienvenue
        <?php
        if (preg_match("/^(.*)@/", $_SESSION['user_username'], $matches))
        {
        echo $matches[1];
        } else {
        echo $_SESSION['user_username'];
        }?></h3>
    <div>
        <div><a href="booked_spots">Mes réservations</a>
            <p>Affichage des trois dernières réservations</p>
        <div class="placeholderForFunction">

        </div></div>
        <div><a href="vehicles">Mes véhicules</a>
            <p>Affichage des trois derniers véhicules</p>
            <div class="placeholderForFunction">

        </div></div>
        <div><a href="old_bookings">Mes réservations passées</a>
            <p>Affichage des trois dernières réservations passées</p>

            <div class="placeholderForFunction">

        </div></div>
        <div class="cart d-flex justify-content-center"><a href="checkout">Mon panier</a>
        <div>

        </div></div>
    </div>

<script src="backend/Assets/js/components/dashboard.js" type="module"></script>
<script type="module">
    import { getUserData } from "./backend/Assets/js/components/dashboard.js";

    document.addEventListener('DOMContentLoaded', async () => {
        const userData = await getUserData()
        const placeholders = document.querySelectorAll('.placeholderForFunction')

        const dateIndicator = (date) => new Date(date).toLocaleDateString('fr-FR')
        const timeIndicator = (time) => time.slice(0,5)
        const badgeInfo = (txt, cls='secondary') =>
            `<span class="badge bg-${cls} me-1">${txt}</span>`

        placeholders[0].innerHTML = `
        <ul class="list-group">
            ${userData[0].map(reservation => `
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div>
                        ${badgeInfo(`#${reservation.id}`, 'primary')}
                        ${badgeInfo(`Place ${reservation.id_parking_spot}`)}
                        ${badgeInfo(`Véhicule ${reservation.id_vehicle}`,'info')}
                    </div>
                    <small>
                        ${dateIndicator(reservation.start_day)} ${timeIndicator(reservation.start_hour)}
                        &nbsp;→&nbsp;
                        ${dateIndicator(reservation.end_day)} ${timeIndicator(reservation.end_hour)}
                    </small>
                </li>
            `).join('')}
        </ul>`

        placeholders[1].innerHTML = `
        <ul class="list-group">
            ${userData[1].map(vehicle => `
               <li class="list-group-item">
                    <i class="fa fa-car me-2 text-secondary"></i>${vehicle.license_plate}
               </li>
            `).join('')}
        </ul>`

        placeholders[2].innerHTML = `
        <ul class="list-group">
            ${userData[2].map(old_reservation => `
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div>
                        ${badgeInfo(`#${old_reservation.id}`, 'dark')}
                        ${badgeInfo(`Place ${old_reservation.id_parking_spot}`)}
                    </div>
                    <small>
                        ${dateIndicator(old_reservation.start_day)} ${timeIndicator(old_reservation.start_hour)}
                        &nbsp;→&nbsp;
                        ${dateIndicator(old_reservation.end_day)} ${timeIndicator(old_reservation.end_hour)}
                    </small>
                </li>
            `).join('')}
        </ul>`
    })
</script>
