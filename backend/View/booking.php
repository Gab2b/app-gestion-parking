<link rel="stylesheet" href="frontend/css/booking.css">

<form id="reservationForm" class="reservation-form" method="post" action="traitement.php">
    <h2>Réserver une place</h2>

    <div>
        <div class="dropdown">
            <button id="vehicleSelector" class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Choisissez le véhicule
            </button>
            <ul class="dropdown-menu">
                <?php foreach ($vehicles as $vehicle) : ?>
                    <li><a href="#" class="dropdown-item"><?php echo $vehicle["license_plate"]?></a></li>
                <?php endforeach; ?>
            </ul>
            <input type="hidden" id="selectedVehicle" name="vehiclePlate">
        </div>
    </div>

    <div>
        <h5>Date de début</h5>
    </div>

    <div>
        <label for="date">Date de début</label>
        <input type="date" id="startingDate" name="startingDate" class="selector" required>
    </div>

    <div>
        <label for="start">Heure de début</label>
        <input type="time" id="startingHour" name="startingHour" class="selector" required>
    </div>

    <div>
        <h5>Date de fin</h5>
    </div>

    <div>
        <input type="checkbox" id="keepSameDate" name="keepSameDate" value="keepSameDate">
        <label for="keepSameDate">Garder le même jour</label>

    </div>

    <div>
        <label for="date">Date de fin</label>
        <input type="date" id="endingDate" name="endingDate" class="selector" required>
    </div>

    <div>
        <label for="end">Heure de fin</label>
        <input type="time" id="endingHour" name="endingHour" class="selector" required>
    </div>

    <div class="tarif" id="tarif">Tarif : 0 €</div>

    <input type="hidden" id="selectedPrice" name="price">


    <button id="submitButton" type="submit">Réserver</button>
</form>


<script src="https://www.paypal.com/sdk/js?client-id=AcvYnSX3G2y7mtXeT0PXd_BQpqqBEpgIPc3q1R9VrsCjLVEG_wZ4yIdq0VqER4ZkQEnyGYlPAg8Eh4Sv&currency=EUR"></script>
<script src="frontend/assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
<script src="frontend/assets/sweetalert2-11.22.0/dist/sweetalert2.all.min.js"></script>
<script src="backend/Assets/js/services/booking.js" type="module"></script>
<script type="module">
    import { calculatePrice, checkHourly, reserveSpot, reservePaidSpot} from "./backend/Assets/js/services/booking.js";

    document.addEventListener('DOMContentLoaded', async () => {
        const checkboxKeepSameDate =document.getElementById('keepSameDate')
        const startingDateInput = document.getElementById('startingDate')
        const finalDateInput = document.getElementById('endingDate')
        const submitButton = document.getElementById('submitButton')

        document.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', e => {
                e.preventDefault()

                const plate = e.target.textContent.trim()

                document.getElementById('vehicleSelector').textContent =  plate
                document.getElementById('selectedVehicle').value = plate
            });
        });

        checkboxKeepSameDate.addEventListener('click', () => {
            if (checkboxKeepSameDate.checked === true) {
                finalDateInput.disabled = true
                finalDateInput.value = startingDateInput.value
            } else if (checkboxKeepSameDate.checked === false) {
                finalDateInput.disabled = false
            }
        })

        document.querySelectorAll('.selector').forEach(input => {
            input.addEventListener('change', async () => {
                let filledFields = 0
                document.querySelectorAll('.selector').forEach(input => {
                    if (input.value) {
                        filledFields += 1
                    }
                })
                if (filledFields >= 3) {
                    await calculateComponentsValidity()
                }
            })
        })

        submitButton.addEventListener('click', async (e) => {
            e.preventDefault()
            let filledFields = 0

            document.querySelectorAll('.selector').forEach(input => {
                if (input.value) {
                    filledFields += 1
                }
            })

            const tarifText = document.getElementById('selectedPrice').value;
            const tarifValue = Number(tarifText.replace(/[^0-9.]/g, ''));

            const plateChosen = document.getElementById('selectedVehicle').value;

            console.log(Number.isInteger(tarifValue))
            console.log(tarifValue)
            console.log(document.getElementById('selectedPrice'))

            if (filledFields >= 3 && !Number.isNaN(tarifValue) && tarifValue > 0 && plateChosen) {
                const reservationForm = new FormData(document.getElementById('reservationForm'))
                let selectedSpot = null

                Swal.fire({
                    title: 'Recherche en cours…',
                    text: 'Vérification des disponibilités horaires…',
                    allowOutsideClick: false,
                    didOpen: async () => {
                        Swal.showLoading();

                        try {
                            const response = await checkHourly(reservationForm)

                            if (!response.success) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Aucune disponibilité',
                                    text: response.message || 'Aucune place n’est disponible pour l’instant.'
                                });
                                return;
                            }

                            const spots = response.spots; // Tableau d’emplacements disponibles
                            if (!spots || spots.length === 0) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Aucune place disponible',
                                    text: 'Essayez à un autre horaire.'
                                });
                                return;
                            }

                            const listingSpots = spots.map(spot => `<option value="${spot.id}">Place ${spot.type}, n°${spot.id}</option>`).join('');
                            const spotsSelect = `
                                                    <label for="spotSelect">Sélectionnez une place libre :</label><br>
                                                    <select id="spotSelect" class="swal2-select" style="margin-bottom:30px">${listingSpots}</select>
                                                    <div id="paypal-button-container"></div>
                                                `;

                            Swal.fire({
                                title: 'Choisissez votre place',
                                html: spotsSelect,
                                showCancelButton: true,
                                cancelButtonText: 'Annuler',
                                confirmButtonText: 'Payer plus tard',
                                preConfirm: () => {
                                    const selectedId = document.getElementById('spotSelect').value;
                                    if (!selectedId) {
                                        Swal.showValidationMessage('Veuillez choisir un emplacement.');
                                    }
                                    return selectedId;
                                }
                            }).then(async (result) => {
                                if (result.isConfirmed) {
                                    try {
                                        selectedSpot = document.getElementById('spotSelect').value
                                        const reserveResponse = await reserveSpot(reservationForm, selectedSpot)
                                        if (reserveResponse.success) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Réservation confirmée',
                                                text: `Place n°${selectedSpot} réservée : du ${reservationForm.get('startingDate')} ${reservationForm.get('startingHour')}, au ${reservationForm.get('endingDate')} ${reservationForm.get('endingHour')}`
                                            });
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Erreur de réservation',
                                                text: reserveResponse.message || 'Veuillez réessayer.'
                                            });
                                        }

                                    } catch (error) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Erreur serveur',
                                            text: error.message
                                        });
                                    }
                                }
                            });

                        } catch (error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur AJAX',
                                text: error.message
                            });

                        }

                        paypal.Buttons({
                            createOrder: function(data, actions) {

                                return actions.order.create({
                                    purchase_units: [{
                                        amount: {
                                            value: reservationForm.get('price'),
                                            currency_code: 'EUR'
                                        }
                                    }]
                                });
                            },
                            onApprove: function(data, actions) {
                                return actions.order.capture().then(function(details) {
                                    selectedSpot = document.getElementById('spotSelect').value
                                    reservePaidSpot(reservationForm, selectedSpot)
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Paiement effectué',
                                        text: `Merci ${details.payer.name.given_name} ! Votre paiement de ${details.purchase_units[0].amount.value} € a été confirmé.`,
                                    });
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
                    }
                });

            } else {
                console.error("Veuillez remplir tout les champs")
            }
        })
    })

    async function calculateComponentsValidity() {
        const startingDate = document.getElementById("startingDate").value;
        const endingDate = document.getElementById("endingDate").value;
        const startingHour = document.getElementById("startingHour").value;
        const endingHour = document.getElementById("endingHour").value;
        const tarifDisplay = document.getElementById("tarif");
        if (!startingDate || !endingDate || !startingHour || !endingHour) return;

        if (endingDate < startingDate || endingDate === startingDate && endingHour <= startingHour) {
            tarifDisplay.textContent = "L'heure de fin doit être après l'heure de début.";
            return;
        }

        const totalPrice = await calculatePrice(startingDate, endingDate, startingHour, endingHour)
        document.getElementById("selectedPrice").value = totalPrice
        tarifDisplay.textContent = `Tarif : ${totalPrice} €`;

    }
</script>