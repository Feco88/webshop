<?php session_start(); ?>

<?php require "functions.php"; ?>
<?php require "header.php"; ?>

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

    // Egy felhasználó csak a saját rendeléseit lássa!
    $result = run_query("SELECT * FROM `orders` WHERE `id` = '$order_id'");
    $row = $result->fetch_assoc();

    if ($result->num_rows > 0 && $_SESSION["email"] !== $row["ordered_by_email"] || $result->num_rows === 0) {
        header("Location: my-orders.php");
    }
?>

<div class="container">
    <div class="row mt-5">
        <div class="col">
            <h3>Rendelési azonosító: #<?php echo $order_id; ?></h3>
        </div>
    </div>

    <?php get_my_order_details($order_id); ?>

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


<?php require "footer.php"; ?>