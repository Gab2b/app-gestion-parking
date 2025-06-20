import { getProfile } from "../services/profile.js";

export async function displayProfile() {
    const data = await getProfile();

    document.getElementById('firstnameDisplay').textContent = data.firstName
    document.getElementById('lastnameDisplay').textContent = data.lastName
    document.getElementById('emailDisplay').textContent = data.mail
    document.getElementById('phoneDisplay').textContent = data.phoneNumber

    document.getElementById('firstNameInput').value = data.firstName
    document.getElementById('lastNameInput').value = data.lastName
    document.getElementById('emailInput').value = data.mail
    document.getElementById('phoneInput').value = data.phoneNumber
}