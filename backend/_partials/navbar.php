<?php
$current_page = $_GET['component'] ?? '';
?>

<nav>
    <a href="index.php">
<!--        <img src="assets/images/INSERTLOGOHERE.png" alt="Logo">-->
    </a>
    <?php if (isset($_SESSION['admin']) && $_SESSION['admin']) : ?>
<!--        <a href="index.php?component=INSERTADMINPAGE" class="--><?php //= ($current_page == 'INSERTADMINPAGE') ? 'active' : '' ?><!--">INSERTADMINPAGETITLE</a>-->
    <?php endif; ?>
    <div class="right-icons">
        <div class="icon-alignment">
            <i class="fa-regular fa-user"></i><?php echo isset($_SESSION['username']) ?? "login" ;?>
        </div>
    </div>
</nav>