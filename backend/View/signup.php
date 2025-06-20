<link rel="stylesheet" href="frontend/css/signup.css">

<h1>Créer un compte</h1><br>

<?php if (!empty($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php elseif (!empty($success)): ?>
    <p style="color: green;"><?php echo $success; ?></p>
<?php endif; ?>

<form method="post">
    <label for="first_name">Prénom :</label>
    <input type="text" name="first_name" required><br><br>

    <label for="last_name">Nom :</label>
    <input type="text" name="last_name" required><br><br>

    <label for="mail">E Mail</label>
    <input type="email" name="mail" required><br><br>

    <label for="phone">Enter your phone number:</label>
    <input type="tel" id="phone" name="phone" pattern="^0[1-9](\s?[0-9]{2}){4}$"><br><br>

    <label for="password">Mot de passe :</label>
    <input type="password" name="password" required><br><br>

    <label for="confirm_password">Confirmer le mot de passe :</label>
    <input type="password" name="confirm_password" required><br><br>

    <button type="submit">S'inscrire</button>
</form>

<br><br>
<p style="text-decoration: none;">Vous avez déjà un compte ?</p>
<a href="login">Cliquez ici pour vous connecter</a>