<br>
<div style="display: flex; justify-content: space-between">
    <h1>
        Gérer vos véhicules :
    </h1>
    <button id="vehicleAdditionButton" type="button" data-bs-toggle="modal" data-bs-target="#vehicleAdditionModal" class="btn btn-primary ">Ajouter un véhicule</button>
</div>
<br>

<div style="background-color: grey">
<table class="table">
    <thead>
    <tr style="font-size: 25px">
        <td>Plaque d'immatriculation</td>
        <td>Marque</td>
        <td>Modèle</td>
        <td>Actions</td>
    </tr>
    </thead>

    <tbody>

    </tbody>
</table>
</div>

<?php require "backend/_partials/vehicleCreationModal.html" ?>

<script src="frontend/assets/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
<script src="frontend/assets/sweetalert2-11.22.0/dist/sweetalert2.all.min.js"></script>
<script src="backend/Assets/js/services/vehicle.js" type="module"></script>
<script type="module">
    import { displayUserVehicles } from "./backend/Assets/js/services/vehicle.js";
    import { addVehicle, updateVehicle, deleteVehicle, showUserVehicles } from "./backend/Assets/js/components/vehicle.js";

    document.addEventListener("DOMContentLoaded", async () => {

        const additionModalButton = document.getElementById("confirmVehicleAddition")

        await displayUserVehicles()

        additionModalButton.addEventListener("click", async (e) => {

            const additionModalForm = document.getElementById("vehicleAdditionForm")
            const additionModal = document.getElementById("vehicleAdditionModal")
            const bootstrapAdditionModal = bootstrap.Modal.getOrCreateInstance(additionModal)

            if(!additionModalForm.checkValidity()){
                additionModalForm.reportValidity()
                return false
            }

            let result = await addVehicle(additionModalForm)
            if ('success' in result && result['success'] === true) {
                bootstrapAdditionModal.hide()
                await displayUserVehicles()
                additionModalForm.reset()
            } else if ('error' in result) {
                Swal.fire({
                    title: 'Erreur',
                    text: result.error,
                    icon: 'error'
                })
            }

        })
    })
</script>