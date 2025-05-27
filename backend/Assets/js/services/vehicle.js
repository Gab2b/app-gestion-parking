import { deleteVehicle, showUserVehicles, updateVehicle } from "../components/vehicle.js";

export const displayUserVehicles = async () => {
    const userVehicles = await showUserVehicles()
    const tbody = document.querySelector('tbody')
    const errorContainer = document.querySelector('#errorContainer')

    tbody.innerHTML = ''
    for (let i = 0; i < userVehicles.length; i++) {
        const tr = document.createElement('tr')
        tr.innerHTML = `
                    <td>${userVehicles[i].license_plate}</td>
                    <td>${userVehicles[i].brand}</td>
                    <td>${userVehicles[i].model}</td>
                    <td style="display:flex">
                        <button class="vehicleModificationButton" type="button" data-id="${userVehicles[i].id}" style="padding: 0!important; border: none!important; background: none!important; outline: none!important; box-shadow: none!important"><i class="fa-regular fa-pen-to-square" style="font-size:25px; color: black; margin-left: 10px; margin-right: 25px"></i></button>
                        <button class="vehicleDeletionButton" type="button" data-id="${userVehicles[i].id}" data-license_plate="${userVehicles[i].license_plate}" style="padding: 0!important; border: none!important; background: none!important; outline: none!important; box-shadow: none!important"><i class="fa-regular fa-square-minus" style="font-size:25px; color: red"></i></button>
                    </td>
                `
        tbody.appendChild(tr)
    }

    document.querySelectorAll('.vehicleModificationButton').forEach(button => {
        button.addEventListener('click', async () => {

            const vehicleId = button.dataset.id
            const vehicleArray = userVehicles.find(car => car.id == vehicleId)

            document.getElementById('retrieveLicense_plate').value = vehicleArray.license_plate
            document.getElementById('retrieveBrand').value = vehicleArray.brand
            document.getElementById('retrieveModel').value = vehicleArray.model
            document.getElementById('retrieveColor').value = vehicleArray.color

            const modificationModal = document.getElementById('vehicleModificationModal')
            const bootstrapModificationModal = bootstrap.Modal.getOrCreateInstance(modificationModal)

            bootstrapModificationModal.show()

            const modificationModalButton = document.getElementById("confirmVehicleModification")

            modificationModalButton.addEventListener('click', async () => {
                const modificationModalForm = document.getElementById("vehicleModificationForm")

                if(!modificationModalForm.checkValidity()){
                    modificationModalForm.reportValidity()
                    return false
                }

                let result = await updateVehicle(modificationModalForm, vehicleId)
                if ('success' in result && result['success'] === true) {
                    bootstrapModificationModal.hide()
                    await displayUserVehicles()
                    modificationModalForm.reset()
                } else if ('error' in result) {
                    Swal.fire({
                        title: 'Erreur',
                        text: result.error,
                        icon: 'error'
                    })
                }
            })
        })
    })

    document.querySelectorAll('.vehicleDeletionButton').forEach(button => {
        button.addEventListener('click', async () => {
            const vehicleId = button.dataset.id
            const vehicleLicensePlate = button.dataset.license_plate

            Swal.fire({
                title: 'Confirmation',
                text: 'Êtes vous sûr de vouloir supprimer ce véhicule ?',
                icon: 'question',
                showDenyButton: true,
                denyButtonText: 'Non',
                showConfirmButton: true,
                confirmButtonText: 'Oui',
                reverseButtons: true
            }).then(async (result) => {
                await deleteVehicle(vehicleId)
                await displayUserVehicles()
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Véhicule supprimé !',
                        text: `Le véhicule avec la plaque ${vehicleLicensePlate} à bien été supprimé!`,
                        icon: 'success'
                    })
                } else {
                    Swal.fire({
                        title: 'Erreur',
                        text: result.error,
                        icon: 'error'
                    })
                }
            });
        })
    })
}