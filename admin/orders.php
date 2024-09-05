<?php session_start(); ?>

<?php require "../functions.php"; ?>
<?php require "../header.php"; ?>

<?php
    $role = isset($_SESSION["role"]) ? $_SESSION["role"] : null;
    $first_name = isset($_SESSION["first_name"]) ? $_SESSION["first_name"] : null;
    $last_name = isset($_SESSION["last_name"]) ? $_SESSION["last_name"] : null;
    $cart = isset($_SESSION["cart"]) ? $_SESSION["cart"] : array();
?>

<?php check_permission($role, $first_name, $last_name, false, "orders.php"); ?>
<?php logout_user(); ?>

<div class="container">
    <div class="row my-5">
        <a href="dashboard.php" class="ml-3">< Vissza a vezérlőpultra</a>
    </div>
    <div class="row mb-3">
        <div class="col">
            <h3>Rendelések</h3>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col">
            <table class="table">
                <th class="bg-dark text-white">Azonosító</th>
                <th class="bg-dark text-white">Fizetendő összeg</th>
                <th class="bg-dark text-white">Megrendelő</th>
                <th class="bg-dark text-white">Rendelés időpontja</th>
                <th class="bg-dark text-white">Állapot</th>
                <th class="bg-dark text-white">Szállítási cím</th>
                <th class="bg-dark text-white">Szállítási mód</th>

                <?php get_orders($role, $_SESSION["email"]); ?>

            </table>
        </div>
    </div>
</div>

<?php require "../footer.php"; ?>