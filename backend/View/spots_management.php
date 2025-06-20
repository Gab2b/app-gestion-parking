<link rel="stylesheet" href="frontend/css/spots_management.css">

<br>
<div style="display: flex; justify-content: space-between; align-items: center;">
    <h1>Gérer vos places de parking :</h1>
    <button id="spotAdditionButton" type="button" data-bs-toggle="modal" data-bs-target="#spotAdditionModal" class="btn btn-primary">Ajouter une place</button>
</div>
<br>

<div style="background-color: grey; padding: 10px; border-radius: 5px;">
    <table class="table table-striped table-hover" id="spotsTable">
        <thead>
        <tr style="font-size: 20px;">
            <th>ID</th>
            <th>Type de place</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Modal Ajout / Modification -->
<div class="modal fade" id="spotAdditionModal" tabindex="-1" aria-labelledby="spotModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="spotForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="spotModalLabel">Ajouter / Modifier une place</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="spotId" name="id" value="">
                <div class="mb-3">
                    <label for="spotType" class="form-label">Type de place</label>
                    <select class="form-select" id="spotType" name="type" required>
                        <option value="" disabled selected>Choisissez un type</option>
                        <option value="Normale">Normale</option>
                        <option value="Handicapée">Handicapée</option>
                        <option value="Électrique">Électrique</option>
                        <option value="Moto">Moto</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="confirmSpotAddition" class="btn btn-primary">Enregistrer</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            </div>
        </form>
    </div>
</div>

<script src="frontend/assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
<script src="frontend/assets/sweetalert2-11.22.0/dist/sweetalert2.all.min.js"></script>
<script src="./backend/Assets/js/services/spots_management.js" type="module"></script>
<script src="./backend/Assets/js/components/spots_management.js" type="module"></script>

<script type="module">
    import { getAllSpots, addSpot, updateSpot, deleteSpot } from "./backend/Assets/js/services/spots_management.js";
    import { displaySpots } from "./backend/Assets/js/components/spots_management.js";

    document.addEventListener("DOMContentLoaded", () => {
        const spotForm = document.getElementById("spotForm");
        const spotModal = document.getElementById("spotAdditionModal");
        const bootstrapModal = bootstrap.Modal.getOrCreateInstance(spotModal);
        const spotIdInput = document.getElementById("spotId");
        const spotTypeSelect = document.getElementById("spotType");
        const spotsTableBody = document.querySelector("#spotsTable tbody");

        // Affichage initial
        import("./backend/Assets/js/services/spots_management.js").then(async ({ getAllSpots }) => {
            await displaySpots();
        });

        // Gestion du formulaire ajout/modif
        spotForm.addEventListener("submit", async (e) => {
            e.preventDefault();

            if (!spotForm.checkValidity()) {
                spotForm.reportValidity();
                return;
            }

            const id = spotIdInput.value;
            const type = spotTypeSelect.value;

            let result;
            // Import dynamique des fonctions (on pourrait aussi importer en haut et ne pas faire ça à chaque fois)
            const services = await import("./backend/Assets/js/services/spots_management.js");

            if (id) {
                result = await services.updateSpot(id, type);
            } else {
                result = await services.addSpot(type);
            }

            if (result.success) {
                bootstrapModal.hide();
                spotForm.reset();
                spotIdInput.value = "";
                await displaySpots();
                Swal.fire('Succès', 'La place a bien été enregistrée', 'success');
            } else {
                Swal.fire('Erreur', result.error || 'Une erreur est survenue', 'error');
            }
        });

        // Gestion clic sur boutons Modifier et Supprimer (délégation)
        spotsTableBody.addEventListener("click", async (e) => {
            if (e.target.classList.contains("editSpotBtn")) {
                // Modifier
                spotIdInput.value = e.target.getAttribute("data-id");
                spotTypeSelect.value = e.target.getAttribute("data-type");
                bootstrapModal.show();
            } else if (e.target.classList.contains("deleteSpotBtn")) {
                // Supprimer
                const id = e.target.getAttribute("data-id");
                const confirmResult = await Swal.fire({
                    title: 'Êtes-vous sûr ?',
                    text: "Cette action est irréversible.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler'
                });

                if (confirmResult.isConfirmed) {
                    const services = await import("./backend/Assets/js/services/spots_management.js");
                    const result = await services.deleteSpot(id);

                    if (result.success) {
                        await displaySpots();
                        Swal.fire('Supprimé !', 'La place a bien été supprimée.', 'success');
                    } else {
                        Swal.fire('Erreur', result.error || 'Impossible de supprimer la place', 'error');
                    }
                }
            }
        });
    });
</script>
