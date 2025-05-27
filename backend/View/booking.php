<form class="reservation-form" method="post" action="traitement.php">
    <h2>Réserver une place</h2>

    <div>
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Choisissez le véhicule
            </button>
            <ul class="dropdown-menu">
                <?php foreach ($vehicles as $vehicle) : ?>
                    <li><a href="#" class="dropdown-item"><?php echo $vehicle["license_plate"]?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div>
        <label for="date">Date</label>
        <input type="date" id="date" name="date" class="selector" required>
    </div>

    <div>
        <label for="start">Heure de début</label>
        <input type="time" id="start" name="start" class="selector" required>
    </div>

    <div>
        <label for="end">Heure de fin</label>
        <input type="time" id="end" name="end" class="selector" required>
    </div>

    <div class="tarif" id="tarif">Tarif : 0 €</div>

    <button type="submit">Réserver</button>
</form>

<script src="frontend/assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        document.querySelectorAll('.selector').forEach(input => {
            input.addEventListener('change', () => {
                console.log(input.value)
                let filledFields = 0
                document.querySelectorAll('.selector').forEach(input => {
                    if (input.value) {
                        filledFields += 1
                    }
                })
                if (filledFields === 3) {
                    console.log('Tarif')
                }
            })
        })
    })

    function calcucomponentslerTarif() {
        const startInput = document.getElementById("start").value;
        const endInput = document.getElementById("end").value;
        const tarifDisplay = document.getElementById("tarif");

        if (!startInput || !endInput) return;

        const start = new Date(`1970-01-01T${startInput}:00`);
        const end = new Date(`1970-01-01T${endInput}:00`);

        let total = 0;

        if (end <= start) {
            tarifDisplay.textContent = "L'heure de fin doit être après l'heure de début.";
            return;
        }

        let current = new Date(start);

        while (current < end) {
            const hour = current.getHours();
            const isDay = hour >= 8 && hour < 20;
            total += isDay ? 3 : 4;

            current.setHours(current.getHours() + 1);
        }

        tarifDisplay.textContent = `Tarif : ${total} €`;
    }
</script>