<?php session_start(); ?>


<?php require "../functions.php"; ?>
<?php require "../header.php"; ?>

<?php
    $role = isset($_SESSION["role"]) ? $_SESSION["role"] : null;
    $first_name = isset($_SESSION["first_name"]) ? $_SESSION["first_name"] : null;
    $last_name = isset($_SESSION["last_name"]) ? $_SESSION["last_name"] : null;
?>

<?php check_permission($role, $first_name, $last_name, true, "dashboard.php"); ?>
<?php logout_user(); ?>

<div class="container">
    <h3 class="my-5">Vezérlőpult</h3>
    <div class="row">
        <?php show_card("Termékek", "primary", "products", "products.php"); ?>
        <?php show_card("Kategóriák", "dark", "categories", "categories.php"); ?>
        <?php show_card("Felhasználók", "warning", "users", "users.php"); ?>
        <?php show_card("Rendelések", "info", "orders", "orders.php"); ?>
    </div>
</div>

<?php require "../footer.php"; ?>