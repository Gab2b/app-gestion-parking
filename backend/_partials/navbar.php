<?php
$current_page = $_GET['component'] ?? '';
?>

<nav>
    <a href="index.php">
<!--        <img src="assets/images/INSERTLOGOHERE.png" alt="Logo">-->
    </a>
    <div class="right-icons">
        <div class="icon-alignment">
            <i class="fa-regular fa-user"></i>
            <?php echo (isset($_SESSION['auth']) && $_SESSION['auth'] === true) ? $_SESSION['user_username'] : 'login'; ?>

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

            <a class="nav-link" href="index.php?component=layout">Accueil</a>
            <a class="nav-link" href="index.php?component=booking">Réservation</a>
            <a class="nav-link" href="index.php?component=checkout">Panier</a>
        </div>
    </div>
</nav>