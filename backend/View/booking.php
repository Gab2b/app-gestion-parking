<body>
<form class="reservation-form" method="post" action="traitement.php">
    <h2>Réserver une place</h2>

    <div>
        <label for="vehicle">Immatriculation du véhicule</label>
        <input type="text" id="vehicle" name="vehicle" placeholder="AB-123-CD" required>
    </div>

    <div>
        <label for="date">Date</label>
        <input type="date" id="date" name="date" required>
    </div>

    <div>
        <label for="start">Heure de début</label>
        <input type="time" id="start" name="start" required>
    </div>

    <div>
        <label for="end">Heure de fin</label>
        <input type="time" id="end" name="end" required>
    </div>

    <div class="tarif" id="tarif">Tarif : 0 €</div>

    <button type="submit">Réserver</button>
</form>

<script>
    function calculerTarif() {
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