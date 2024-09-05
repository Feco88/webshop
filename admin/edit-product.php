<?php session_start(); ?>

<?php require "../functions.php"; ?>
<?php require "../header.php"; ?>

<?php
    $role = isset($_SESSION["role"]) ? $_SESSION["role"] : null;
    $first_name = isset($_SESSION["first_name"]) ? $_SESSION["first_name"] : null;
    $last_name = isset($_SESSION["last_name"]) ? $_SESSION["last_name"] : null;
?>

<?php check_permission($role, $first_name, $last_name, true, "edit-products.php"); ?>
<?php logout_user(); ?>

<?php $row = load_product_details($_GET["edit"]); ?>

<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $inserted_values = array(
        "id" => $_GET["edit"],
        "product_name" => $_POST["product_name"],
        "product_description" => $_POST["product_description"],
        "product_price" => $_POST["product_price"],
        "in_stock" => $_POST["in_stock"],
        "selected_category" => $_POST["selected_category"],
    );

    update_product($inserted_values);
}

?>

<div class="container">
    <div class="row my-5">
        <a href="dashboard.php" class="ml-3">< Vissza a vezérlőpultra</a>
    </div>
    <div class="row mb-3">
        <div class="col">
            <h3>Termék szerkesztése</h3>
        </div>
    </div>
    <form method="POST">
        <div class="form-row">
            <div class="form-group col-12">
                <label for="product_name">Termék neve</label>
                <input class="form-control" type="text" name="product_name" id="product_name" value="<?php echo $row["product_name"]; ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-12">
                <label for="product_description">Termék leírása</label>
                <textarea class="form-control" name="product_description" id="product_description" rows="5"><?php echo $row["product_description"]; ?></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-6">
                <label for="product_price">Termék ára</label>
                <div class="input-group">
                    <input class="form-control" type="number" name="product_price" id="product_price" value="<?php echo $row["product_price"]; ?>">
                    <div class="input-group-append">
                        <span class="input-group-text">Ft</span>
                    </div>
                </div>
            </div>
            <div class="form-group col-6">
                <label for="in_stock">Készleten</label>
                <div class="input-group">
                    <input class="form-control" type="number" name="in_stock" id="in_stock" value="<?php echo $row["in_stock"]; ?>">
                    <div class="input-group-append">
                        <span class="input-group-text">Db</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-12">
                <label for="product_category_selector">Kategória</label>
                <?php list_category_with_selected_option($row["category"]); ?>
            </div>
        </div>
        <div class="form-row my-4">
            <div class="form-group col-12 col-lg-4">
                <input class="form-control btn btn-success" type="submit" name="submit" value="Módosítás">
            </div>
        </div>
    </form>
</div>

<?php require "../footer.php"; ?>