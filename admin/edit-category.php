<?php session_start(); ?>

<?php require "../functions.php"; ?>
<?php require "../header.php"; ?>

<?php
    $role = isset($_SESSION["role"]) ? $_SESSION["role"] : null;
    $first_name = isset($_SESSION["first_name"]) ? $_SESSION["first_name"] : null;
    $last_name = isset($_SESSION["last_name"]) ? $_SESSION["last_name"] : null;
?>

<?php check_permission($role, $first_name, $last_name, true, "edit-category.php"); ?>
<?php logout_user(); ?>

<?php $row = load_category_details($_GET["edit"]); ?>

<div class="container">
    <div class="row my-5">
        <a href="dashboard.php" class="ml-3">< Vissza a vezérlőpultra</a>
    </div>
    <div class="row mb-3">
        <div class="col">
            <h3>Kategória szerkesztése</h3>
        </div>
    </div>

    <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $inserted_values = array(
                "id" => $_GET["edit"],
                "origin_category" => $row["category_name"],
                "updated_category" => $_POST["updated_category"],
            );

            update_category($inserted_values);
        }
    ?>

    <form method="POST">
        <div class="form-row">
            <div class="form-group col-12">
                <label for="category_name">Kategória neve</label>
                <input class="form-control" type="text" name="updated_category" id="category_name" value="<?php echo $row["category_name"]; ?>">
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