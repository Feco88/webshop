<?php session_start(); ?>

<?php require "functions.php"; ?>
<?php require "header.php"; ?>

<?php
    $role = isset($_SESSION["role"]) ? $_SESSION["role"] : null;
    $first_name = isset($_SESSION["first_name"]) ? $_SESSION["first_name"] : null;
    $last_name = isset($_SESSION["last_name"]) ? $_SESSION["last_name"] : null;
?>

<?php check_permission($role, $first_name, $last_name, false, "product.php"); ?>
<?php logout_user(); ?>



<div class="container">
    <div class="row mt-5">
        <div class="col">
        <?php
    
    // TODO: erre majd kell egy függvény!!
    if (isset($_POST["add_to_cart"])) { 

        $id = $_GET["id"];
        $result = run_query("SELECT * FROM `products` WHERE `id` = '$id'");
        $row = $result->fetch_assoc();
        $item = array();

        if (!isset($_SESSION["cart"])) {
            $_SESSION["cart"] = array();
        }

        // ha még üres a kosár, akkor adja hozzá az első elemet
        if (count($_SESSION["cart"]) === 0) {
            $item = array(
                "product_name" => $row["product_name"],
                "quantity" => intval($_POST["quantity"]),
                "product_price" => intval($row["product_price"]),
                "product_total_price" => intval($_POST["quantity"]) *  intval($row["product_price"])
            );
            array_push($_SESSION["cart"], $item);
        } else {
            // ha van már legalább 1 elem a kosárban
            $item_already_in_the_cart = false;
            $items_in_cart = array();

            for ($i=0; $i <count($_SESSION["cart"]); $i++) {
                if ($_SESSION["cart"][$i]["product_name"] === $row["product_name"]) {
                    $item_already_in_the_cart = true;
                }
                $item = $_SESSION["cart"][$i];
                array_push($items_in_cart, $_SESSION["cart"][$i]["product_name"]);
            }
            if ($item_already_in_the_cart) {

                $index = array_search($row["product_name"], $items_in_cart);

                // ha elértük a készleten lévő darabszámot
                if ($_SESSION["cart"][$index]["quantity"] + $_POST["quantity"] > $row["in_stock"]) {
                    echo "Elérted a maximális mennyiséget!";
                } else {

                    $_SESSION["cart"][$index]["quantity"] += intval($_POST["quantity"]);
                    $_SESSION["cart"][$index]["product_total_price"] = $_SESSION["cart"][$index]["product_price"] * $_SESSION["cart"][$index]["quantity"];         
                }

            } else {
                $item = array(
                    "product_name" => $row["product_name"],
                    "quantity" => intval($_POST["quantity"]),
                    "product_price" => intval($row["product_price"]),
                    "product_total_price" => intval($_POST["quantity"]) *  intval($row["product_price"])
                );
                array_push($_SESSION["cart"], $item);
            }
        }
        $product_name = $item["product_name"];
        $quantity = $_POST["quantity"];
        generate_alert("$product_name db $quantity sikeresen a kosárhoz adva!", "success");

    }

?>
        </div>
    </div>
    <div class="row my-5">
        <div class="col">
            <a href="products.php">< Vissza a termékekhez</a>
        </div>
    </div>

    <div class="row">
        <?php 
            $cart = isset($_SESSION["cart"]) ? $_SESSION["cart"] : array();
            get_product(isset($_GET["id"]) ? intval($_GET["id"]) : 0, $role, $cart);  
        ?>
    </div>
</div>

<?php require "footer.php"; ?>