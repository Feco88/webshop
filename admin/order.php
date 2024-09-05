<?php session_start(); ?>

<?php require "../functions.php"; ?>
<?php require "../header.php"; ?>

<?php
    $role = isset($_SESSION["role"]) ? $_SESSION["role"] : null;
    $first_name = isset($_SESSION["first_name"]) ? $_SESSION["first_name"] : null;
    $last_name = isset($_SESSION["last_name"]) ? $_SESSION["last_name"] : null;
    $cart = isset($_SESSION["cart"]) ? $_SESSION["cart"] : array();
?>

<?php check_permission($role, $first_name, $last_name, false, "order.php"); ?>
<?php logout_user(); ?>

<?php

    $order_id = $_GET["order_id"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $modified_order_state = $_POST["order_state"];
        run_query("UPDATE `orders` SET `order_state` = '$modified_order_state' WHERE `id` = '$order_id'");
    } 

?>



<div class="container">
    <div class="row mt-5">
        <div class="col">
            <h3>Rendelési azonosító: #<?php echo $order_id; ?></h3>
        </div>
    </div>

    <?php get_order_details($order_id); ?>

    <div class="row my-5">
        <div class="col">
            <table class="table">
                <th class="bg-dark text-white">Termék név</th>
                <th class="bg-dark text-white">Mennyiség</th>
                <th class="bg-dark text-white">Egység ár</th>
                <th class="bg-dark text-white">Mennyiség ár</th>

                <?php get_order_items($order_id); ?>

            </table>
        </div>
    </div>
</div>


<?php require "../footer.php"; ?>