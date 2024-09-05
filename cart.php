<?php session_start(); ?>

<?php require "functions.php"; ?>
<?php require "header.php"; ?>

<?php
    $role = isset($_SESSION["role"]) ? $_SESSION["role"] : null;
    $first_name = isset($_SESSION["first_name"]) ? $_SESSION["first_name"] : null;
    $last_name = isset($_SESSION["last_name"]) ? $_SESSION["last_name"] : null;
    $cart = isset($_SESSION["cart"]) ? $_SESSION["cart"] : array();
?>

<?php check_permission($role, $first_name, $last_name, false, "cart.php"); ?>
<?php logout_user(); ?>


<?php 

    if (isset($_GET["remove_from_cart"])) {
        remove_item_from_cart($_GET["remove_from_cart"], "cart");
    }
?>

<div class="container">
    <h3 class="my-5">Kosár</h3>
    <div class="row">
        <div class="col-12">
            <table class="table">
                <thead class="bg-dark text-white">
                    <th>Termék</th>
                    <th>Mennyiség</th>
                    <th>Egység Ár</th>
                    <th>Mennyiség Ár</th>
                    <th>Kezelés</th>
                </thead>

                <?php get_items_in_cart($cart); ?>
            </table>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <h5>Teljes Összeg:</h5>
            <p><?php get_cart_total_price($cart); ?> Ft</p>

            <?php

                if (count($cart) !== 0) {
                    echo "
                        <a href='./checkout.php'>
                            <button class='btn btn-success mt-3'>Rendelés véglegesítése</button>
                        </a>";
                }

            ?>
            
        </div>
    </div>
</div>

<?php require "footer.php"; ?>