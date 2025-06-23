<link rel="stylesheet" href="frontend/css/login.css">
<br>
<form method="post" id="login-form">
    <div class="mb-3">
        <label for="emailAddress" class="form-label">Adresse Mail</label>
        <input type="text" class="form-control" name="emailAddress" id="emailAddress" required>
    </div>
    <div class="mb-3">
        <label for="passcode" class="form-label">Mot de passe</label>
        <input type="password" name="passcode" class="form-control" id="passcode" required>
    </div>
    <button type="button" class="btn btn-primary" name="login_button" id="valid-login-btn">Valider</button>
</form>
<br><br>
<p style="text-decoration: none;">Vous n'avez pas encore de compte ?</p>
<a href="signup">Cliquez ici pour créer un compte</a>

<script src="./backend/Assets/js/services/login.js" type="module"></script>
<script type="module">
    import { login } from "./backend/Assets/js/services/login.js";

    document.addEventListener('DOMContentLoaded', () => {
        const loginBtn = document.querySelector('#valid-login-btn')
        loginBtn.addEventListener('click', async (e) => {
            e.preventDefault();

            const formLogin = document.querySelector('#login-form');
            if (!formLogin.checkValidity()) {
                formLogin.reportValidity();
                return;
            }

            const loginResult = await login(formLogin.elements['emailAddress'].value, formLogin.elements['passcode'].value);

            if (loginResult.hasOwnProperty('authentication')) {
                document.location.href = 'index.php';
            }
            // else if (loginResult.hasOwnProperty('errors')) {
            //     const errorsElement = document.querySelector('#errors');
            //     errorsElement.innerHTML = '';
            //     loginResult.errors.forEach(error => {
            //         const errorDiv = document.createElement('div');
            //         errorDiv.classList.add('alert', 'alert-danger');
            //         errorDiv.setAttribute('role', 'alert');
            //         errorDiv.textContent = error;
            //         errorDiv.style.opacity = 1;
            //         errorDiv.style.transition = 'opacity 1s';
            //         errorsElement.appendChild(errorDiv);
            //
            //         setTimeout(() => {
            //             errorDiv.style.opacity = 0;
            //         }, 1000,);
            //
            //         setTimeout(() => {
            //             errorsElement.innerHTML = '';
            //         }, 5000);
            //     });

            //
            //     console.log(loginResult.errors);
            //
            // }
        });
    });
</script>