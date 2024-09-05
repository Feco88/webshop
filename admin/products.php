<?php session_start(); ?>

<?php require "../functions.php"; ?>
<?php require "../header.php"; ?>

<?php
    $role = isset($_SESSION["role"]) ? $_SESSION["role"] : null;
    $first_name = isset($_SESSION["first_name"]) ? $_SESSION["first_name"] : null;
    $last_name = isset($_SESSION["last_name"]) ? $_SESSION["last_name"] : null;
?>

<?php check_permission($role, $first_name, $last_name, true, "products.php"); ?>
<?php logout_user(); ?>

<div class="container">
    <div class="row my-5">
        <a href="dashboard.php" class="ml-3">< Vissza a vezérlőpultra</a>
    </div>
    <div class="row mb-3">
        <div class="col">
            <h3>Termékek</h3>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <?php
                if (isset($_GET["updated"])) {
                    $updated_product = $_GET['updated'];
                    if (product_name_exist($updated_product)) {
                        echo "<div class='alert alert-success' role='alert'>A következő termék: <strong>$updated_product</strong> adatai módosultak!</div>";
                    } else {
                        header("Location: products.php");
                    }
                }
            ?>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <?php 
                if (isset($_GET["delete"]) && isset($_GET["product_name"])) {
                    delete_product($_GET["delete"], $_GET["product_name"]);
                }
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="table">
                <thead class="bg-dark text-white">
                    <tr>
                        <th>id</th>
                        <th>név</th>
                        <th>leírás</th>
                        <th>ár</th>
                        <th>készleten</th>
                        <th>kategória</th>
                        <th>kezelés</th>
                    </tr>
                </thead>
                <tbody>
                    <?php show_products_table(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require "../footer.php"; ?>