import {getAllSpots} from "../services/spots_management.js";

export async function displaySpots() {
    const spotsTableBody = document.querySelector("#spotsTable tbody");
    spotsTableBody.innerHTML = "";

    const spots = await getAllSpots();

    spots.forEach(spot => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
                <td>${spot.id}</td>
                <td>${spot.type}</td>
                <td>
                    <button class="btn btn-sm btn-warning editSpotBtn" data-id="${spot.id}" data-type="${spot.type}">Modifier</button>
                    <button class="btn btn-sm btn-danger deleteSpotBtn" data-id="${spot.id}">Supprimer</button>
                </td>
            `;
        spotsTableBody.appendChild(tr);
    });
}