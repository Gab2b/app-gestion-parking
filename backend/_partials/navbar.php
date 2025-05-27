<?php
$current_page = $_GET['component'] ?? '';
?>

<nav class="navbar sticky-top navbar-expand-lg bg-body-tertiary mb-3" style="background-color: #08afe3!important; justify-content: space-around; font">
    <a href="index.php">
        <img src="frontend/assets/Logo Magnaud.jpg" alt="Logo" style="width: 160px;border-radius: 80px;">
    </a>
    <div class="right-icons">
        <div class="icon-alignment collapse navbar-collapse" style="gap: 50px; margin-left: 40px; font-size: 30px">
            <a class="nav-link" href="layout">Accueil</a>

            <?php if (isset($_SESSION['auth']) && $_SESSION['auth'] === true): ?>
                <a class="nav-link" href="booking">Réservation</a>
                <a class="nav-link" href="checkout">Panier</a>
                <a class="nav-link" href="vehicles" style="padding-right: 50px">Mes véhicules</a>

                <i class="fa-regular fa-user" style="margin-left: 60px; font-size: 25px"></i>
                <a href="profile" style="text-decoration: none">
                    <p style="font-size: 20px; margin-bottom: 0; margin-left: -30px; color: black">
            <?php
                if (preg_match("/^(.*)@/", $_SESSION['user_username'], $matches))
                {
                    echo $matches[1];
                } else {
                    echo $_SESSION['user_username'];
                }?>

                    </p>
                </a>

            <?php endif; ?>

            <?php if (!(isset($_SESSION['auth'])) || $_SESSION['auth'] === false): ?>
                <a href="login" style="text-decoration: none; color: black; margin-right: 150px;">
                    <p style="margin-bottom: 0">Réserver maintenant</p>
                </a>

                <a href="login" style="text-decoration: none; font-weight: bold; color: black">
                    <p style="margin-bottom: 0">Connexion / Inscription</p>
                </a>

            <?php endif; ?>

            <?php if (isset($_SESSION['auth']) && $_SESSION['auth'] === true): ?>
                <a href="index.php?disconnect=true" style="text-decoration: none; font-weight: bold; color: black">
                    <p style="margin-bottom: 0">Déconnexion</>
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>
