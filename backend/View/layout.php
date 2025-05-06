<div class="container">
    <h1>Parking de la Cathérale</h1>
    <?php if (!(isset($_SESSION['auth'])) || $_SESSION['auth'] === false): ?>
    <a href="index.php?component=login">
        <h1>Connexion / Inscription</h1>
    </a>
    <?php endif; ?>
    <?php if (isset($_SESSION['auth']) && $_SESSION['auth'] === true): ?>
    <a href="index.php?disconnect=true">
        <h1>Déconnexion</h1>
    </a>
    <?php endif; ?>

</div>