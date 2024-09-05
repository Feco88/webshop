<?php session_start(); ?>

<?php require "functions.php"; ?>
<?php require "header.php"; ?>

<?php
    $role = isset($_SESSION["role"]) ? $_SESSION["role"] : null;
    $first_name = isset($_SESSION["first_name"]) ? $_SESSION["first_name"] : null;
    $last_name = isset($_SESSION["last_name"]) ? $_SESSION["last_name"] : null;
    $cart = isset($_SESSION["cart"]) ? $_SESSION["cart"] : array();
?>

<?php check_permission($role, $first_name, $last_name, false, "checkout.php"); ?>
<?php logout_user(); ?>


<?php 

    if (isset($_GET["remove_from_cart"])) {
        remove_item_from_cart($_GET["remove_from_cart"], "cart");
    }
?>

<div class="container">

    <div class="row">
        <div class="col">
            <?php
                if ($_SERVER["REQUEST_METHOD"] === "POST") {
                    $price = get_total_price_with_shipping_fee($cart);
                    $order_id = register_order(
                        $_SESSION["email"],
                        $_POST["shipping_method"],
                        $_POST["shipping_address"],
                        $price["total"],
                        json_encode($_SESSION["cart"], JSON_UNESCAPED_UNICODE)
                    );

                    update_stock($cart);
                    $_SESSION["cart"] = array();
                    echo "<meta http-equiv='refresh' content='0;url=order.php?order_id={$order_id}'>";
                }
            ?>
        </div>
    </div>

    <h3 class="my-5">Rendelés részletei</h3>
    <div class="row">
        <div class="col-12 col-lg-6">
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
        <div class="col-12 col-lg-6">
            <form method="POST">
                <div class="row">
                    <div class="col-12">
                        <?php render_total_price_with_shipping_fee($cart); ?>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">                             
                        <h5>Szállítási Mód</h5>
                        <div class="form-check form-check-inline">
                            <input onclick="check_radio_value()" class="form-check-input" type="radio" name="shipping_method" id="home_delivery" value="Házhoz szállítás" checked>
                            <label class="form-check-label" for="home_delivery">Házhoz szállítás</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input onclick="check_radio_value()" class="form-check-input" type="radio" name="shipping_method" id="parcel_machine" value="Csomagponti átvétel">
                            <label class="form-check-label" for="parcel_machine">Csomagponti átvétel</label>
                        </div>
                    </div> 
                </div>
                <div id="shipping-address-col" class="row mt-3">
                    <div class="col">
                        <h5>Szállítási cím</h5>
                        <input name="shipping_address" id="address" class="form-control" type="text" value="<?php echo $_SESSION["address"]; ?>" readonly>
                    </div>
                </div>
                <div id="fox-post-widget" class="row mt-3 d-none">
                    <div class="col">
                        <iframe frameborder="0" loading="lazy" src="https://cdn.foxpost.hu/apt-finder/v1/app/?discount=1" width="100%" height="1000px"></iframe>
                    </div>
                </div>
                <div class="row mt-3 mb-5">
                    <div class="col">
                        <input id="place-an-order-button" type="submit" class="btn btn-success" value="Rendelés leadása">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require "footer.php"; ?>


<script>

    const user_address = document.getElementById("address").value
    const foxPostWidget = document.getElementById("fox-post-widget")
    const placeAnOrderButton = document.getElementById("place-an-order-button")
    const shippingAddressCol = document.getElementById("shipping-address-col")

    function check_radio_value() {
        const radio_buttons = document.getElementsByName("shipping_method")
        let selected_radio = ""
        for (const radio of radio_buttons) {
            if (radio.checked) {
                selected_radio = radio.id
                break
            }
        }
        if (selected_radio === "home_delivery") {
            document.getElementById("address").value = user_address
            foxPostWidget.classList.add("d-none")
            shippingAddressCol.classList.remove("d-none")
            placeAnOrderButton.classList.remove("d-none")
        }
        if (selected_radio === "parcel_machine") {
            foxPostWidget.classList.remove("d-none")
            placeAnOrderButton.classList.add("d-none")
            shippingAddressCol.classList.add("d-none")
        }
    }
    function receiveMessage(event) {
        var apt = JSON.parse(event.data);
        document.getElementById("address").value = apt.address
        placeAnOrderButton.classList.remove("d-none")
        shippingAddressCol.classList.remove("d-none")
    }

    window.addEventListener('message', receiveMessage, false);
</script>