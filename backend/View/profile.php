<link rel="stylesheet" href="frontend/css/profile.css">

<br>
<div style="max-width: 700px; margin: auto;">
    <h1>Mon profil</h1>
    <div class="card">
        <div class="card-body">
            <p><strong>Prénom :</strong> <span id="firstnameDisplay">…</span></p>
            <p><strong>Nom :</strong> <span id="lastnameDisplay">…</span></p>
            <p><strong>Email :</strong> <span id="emailDisplay">…</span></p>
            <p><strong>Téléphone :</strong> <span id="phoneDisplay">…</span></p>
            <button id="editProfileBtn" class="btn btn-primary">Modifier</button>
        </div>
    </div>
</div>

<div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="profileForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Éditer mes infos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Prénom</label>
                    <input name="first_name" id="firstNameInput" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input name="last_name" id="lastNameInput" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" id="emailInput" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Téléphone</label>
                    <input name="phone_number" id="phoneInput" class="form-control">
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Enregistrer</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            </div>
        </form>
    </div>
</div>

<script src="frontend/assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
<script src="frontend/assets/sweetalert2-11.22.0/dist/sweetalert2.all.min.js"></script>
<script type="module" src="backend/Assets/js/services/profile.js"></script>
<script type="module" src="backend/Assets/js/components/profile.js"></script>

<script type="module">
    import { displayProfile } from "./backend/Assets/js/components/profile.js";
    import { updateProfile } from './backend/Assets/js/services/profile.js'

    document.addEventListener('DOMContentLoaded', async () => {

        displayProfile();

        const editBtn = document.getElementById('editProfileBtn');
        const modalEl = document.getElementById('profileModal');
        const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
        const form = document.getElementById('profileForm');

        editBtn.addEventListener('click', () => bsModal.show());

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            if (!form.checkValidity()) { form.reportValidity(); return; }

            const fd = new FormData(form);
            const response = await updateProfile(fd);

            if (response.success) {
                bsModal.hide();
                await displayProfile();
                Swal.fire('Succès', 'Profil mis à jour', 'success');
            } else {
                Swal.fire('Erreur', response.error || 'Mise à jour impossible', 'error');
            }
        });
    });
</script>
