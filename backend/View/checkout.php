<link rel="stylesheet" href="frontend/css/checkout.css">

    <br>
    <h1>Réservations non payées : </h1>
    <br>
    <table class="table">
        <thead>
        <tr>
            <th scope="col"><a href="booking">N° de Réservation</a></th>
            <th scope="col"><a href="booking">N° de Place</a></th>
            <th scope="col"><a href="booking">Type</a></th>
            <th scope="col"><a href="booking">Véhicule</a></th>
            <th scope="col"><a href="booking">Jour de début</a></th>
            <th scope="col"><a href="booking">Heure de début</a></th>
            <th scope="col"><a href="booking">Jour de fin</a></th>
            <th scope="col"><a href="booking">Heure de fin</a></th>
            <th scope="col"><a href="booking">Prix</a></th>


            <th scope="col"><a href="#">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($unpaidReservations as &$unpaidReservation) :?>
                <tr class="table align-middle">
                    <td><?php echo$unpaidReservation['id']?></td>
                    <td><?php echo$unpaidReservation['id_parking_spot']?></td>
                    <td><?php echo$unpaidReservation['type']?></td>
                    <td><?php echo$unpaidReservation['license_plate']?></td>
                    <td><?php echo$unpaidReservation['start_day']?></td>
                    <td><?php echo$unpaidReservation['start_hour']?></td>
                    <td><?php echo$unpaidReservation['end_day']?></td>
                    <td><?php echo$unpaidReservation['end_hour']?></td>
                    <td id="<?php echo$unpaidReservation['total_price'] ?>" class="gotPrice"><?php echo$unpaidReservation['total_price']?> €</td>

                    <td>
                            <a data-id="<?php echo$unpaidReservation['id']?>"
                               data-spot-id="<?= $unpaidReservation['id_parking_spot'] ?>"
                               data-vehicle-id="<?= $unpaidReservation['license_plate'] ?>"
                               data-spot-type="<?= $unpaidReservation['type'] ?>"
                               data-start-day="<?= $unpaidReservation['start_day'] ?>"
                               data-start-hour="<?= $unpaidReservation['start_hour'] ?>"
                               data-end-day="<?= $unpaidReservation['end_day'] ?>"
                               data-end-hour="<?= $unpaidReservation['end_hour'] ?>"
                               data-total-price="<?= $unpaidReservation['total_price'] ?>"
                               class="editReservation"
                            >
                                <i class="fa-solid fa-file-pen"></i>
                            </a>

                            <a data-id="<?php echo$unpaidReservation['id']?>" class="deleteReservation">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                    </td>
                    <td>
                        <button id="<?php echo$unpaidReservation['id']?>" data-id="<?php echo$unpaidReservation['total_price'] ?>" class="payButton">Payer</button>
                    </td>
                </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <button id="oneTimePayment">Tout payer en une fois</button>
    <br> <br>
    <h4>Vous ne trouvez pas ce que vous cherchez ?</h4>
    <h5><a href="booked_spots">Voir les réservations déjà payées</a></h5>
    <br>

    <?php require "backend/_partials/hiddenForm.html" ?>

<script src="frontend/assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
<script src="frontend/assets/sweetalert2-11.22.0/dist/sweetalert2.all.min.js"></script>
<script src="https://www.paypal.com/sdk/js?client-id=AcvYnSX3G2y7mtXeT0PXd_BQpqqBEpgIPc3q1R9VrsCjLVEG_wZ4yIdq0VqER4ZkQEnyGYlPAg8Eh4Sv&currency=EUR"></script>
<script src="backend/Assets/js/services/checkout.js" type="module"></script>
<script type="module">
    import { payReservation, payAllReservations, getUserVehicles, getAvailableSpots, updateReservation, deleteReservation } from "./backend/Assets/js/services/checkout.js";

    document.addEventListener('DOMContentLoaded', async () => {
        const oneTimePayment = document.getElementById('oneTimePayment')
        const allPricesElement = document.querySelectorAll('.gotPrice')

        let totalPrice = 0
        for (let i=0; i<allPricesElement.length; i++) {
            totalPrice += parseInt(allPricesElement[i].id)
        }
        const allReservationsPrice = totalPrice

        document.querySelectorAll('.payButton').forEach(input => {
            const paypalToAlert = `<h4>Montant de la réservation : ${input.getAttribute("data-id")} €</h4><br><div id="paypal-single-button-container"></div>`
            input.addEventListener('click', () => {
                Swal.fire({
                    title: "Payer la réservation n°" + input.id,
                    html: paypalToAlert,
                    cancelButtonText: 'Annuler',
                    confirmButtonText: 'Payer plus tard',
                    didOpen : async () => {
                        paypal.Buttons({
                            createOrder: function(data, actions) {

                                return actions.order.create({
                                    purchase_units: [{
                                        amount: {
                                            value: input.getAttribute("data-id"),
                                            currency_code: 'EUR'
                                        }
                                    }]
                                });
                            },
                            onApprove: function(data, actions) {
                                return actions.order.capture().then(function(details) {
                                    payReservation(input.id)
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Paiement effectué',
                                        text: `Merci ${details.payer.name.given_name} ! Votre paiement de ${details.purchase_units[0].amount.value} € a été confirmé.`,
                                    }).then(() => {
                                        document.location.href = "booked_spots"
                                    })

                                });
                            },
                            onError: function(err) {
                                console.error(err);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erreur de paiement',
                                    text: 'Une erreur est survenue pendant le paiement.',
                                });
                            }
                        }).render('#paypal-single-button-container');
                    }
                })
            })
        })

        oneTimePayment.addEventListener('click', async () => {
            const paypalToAlert = `<h4>Montant des réservations : ${allReservationsPrice} €</h4><br><div id="paypal-button-container"></div>`
            Swal.fire({
                title: "Payer toutes les réservations en attente de paiement",
                html: paypalToAlert,
                cancelButtonText: 'Annuler',
                confirmButtonText: 'Payer plus tard',
            })

            paypal.Buttons({
                createOrder: function(data, actions) {

                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: allReservationsPrice,
                                currency_code: 'EUR'
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        payAllReservations()
                        Swal.fire({
                            icon: 'success',
                            title: 'Paiement effectué',
                            text: `Merci ${details.payer.name.given_name} ! Votre paiement de ${details.purchase_units[0].amount.value} € a été confirmé.`,
                        }).then(() => {
                            document.location.href = "booked_spots"
                        })
                    });
                },
                onError: function(err) {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur de paiement',
                        text: 'Une erreur est survenue pendant le paiement.',
                    });
                }
            }).render('#paypal-button-container');
        })

        document.querySelectorAll(".editReservation").forEach(input => {
            const htmlForm = `<?php require "backend/_partials/unpaidReservationsForms.html" ?>`

            input.addEventListener('click', () => {
                Swal.fire({
                    showConfirmButton: false,
                    html: htmlForm,
                    didOpen: async () => {
                        document.getElementById('reservationId').value = input.getAttribute("data-id")
                        document.getElementById('spotId').value = input.getAttribute("data-spot-id")
                        document.getElementById('vehicleId').value = input.getAttribute("data-vehicle-id")
                        document.getElementById('startDay').value = input.getAttribute("data-start-day")
                        document.getElementById('startHour').value = input.getAttribute("data-start-hour")
                        document.getElementById('endDay').value = input.getAttribute("data-end-day")
                        document.getElementById('endHour').value = input.getAttribute("data-end-hour")
                        document.getElementById('totalPrice').value = input.getAttribute("data-total-price")

                        document.getElementById('hiddenStartingDate').value = input.getAttribute("data-start-day")
                        document.getElementById('hiddenEndingDate').value = input.getAttribute("data-end-day")
                        document.getElementById('hiddenStartingHour').value = input.getAttribute("data-start-hour")
                        document.getElementById('hiddenEndingHour').value = input.getAttribute("data-end-hour")

                        const response = await getAvailableSpots(new FormData(document.getElementById('hiddenReservationForm')), input.getAttribute("data-spot-id"))
                        const listingSpots = (response.spots).map(spot => `<option value="${spot.id}">Place ${spot.type}, n°${spot.id}</option>`).join('')

                        const spotsSelect = document.getElementById('spotId')
                        spotsSelect.innerHTML = listingSpots;

                        const vehicles = await getUserVehicles()
                        const listingVehicles = (vehicles.userVehicles).map(vehicle => `<option value="${vehicle.id}">${vehicle.license_plate}</option>`).join('')

                        const vehiclesSelect = document.getElementById('vehicleId')
                        vehiclesSelect.innerHTML = listingVehicles

                        document.getElementById('editedFormSubmit').addEventListener('click', (e) => {
                            e.preventDefault()
                            const form = Swal.getHtmlContainer().querySelector('#unpaidReservationForm');
                            document.getElementById('reservationId').disabled = false

                            updateReservation(new FormData(form))
                            document.location.reload()
                        })

                    }
                })
            })
        })

        document.querySelectorAll(".deleteReservation").forEach(input => {
            input.addEventListener( 'click', () => {
                Swal.fire({
                    title: "Voulez vous vraiment supprimer cette réservation ?",
                    text: "Vous ne pourrez pas revenir en arrière!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Oui, la supprimer",
                    cancelButtonText: "Non"
                }).then((result) => {
                    deleteReservation(input.getAttribute('data-id'))
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "Réservation supprimée !",
                            text: "Votre réservation a bien été supprimée",
                            icon: "success"
                        });
                        input.parentElement.parentElement.innerHTML = ``
                    }
                });
            })
        })
    })
</script>