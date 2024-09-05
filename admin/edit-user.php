<?php session_start(); ?>

<?php require "../functions.php"; ?>
<?php require "../header.php"; ?>

<?php
    $role = isset($_SESSION["role"]) ? $_SESSION["role"] : null;
    $first_name = isset($_SESSION["first_name"]) ? $_SESSION["first_name"] : null;
    $last_name = isset($_SESSION["last_name"]) ? $_SESSION["last_name"] : null;
?>

<?php check_permission($role, $first_name, $last_name, true, "edit-user.php"); ?>
<?php logout_user(); ?>

<?php $row = load_user_details($_GET["edit"]); ?>

<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $inserted_values = array(
        "id" => $_GET["edit"],
        "first_name" => $_POST["first_name"],
        "last_name" => $_POST["last_name"],
        "email" => $_POST["email"],
        "role" => $_POST["role"],
    );

    update_user($inserted_values);
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
            <div class="form-group col-6">
                <label for="last_name">Vezetéknév</label>
                <input class="form-control" type="text" name="last_name" id="last_name" value="<?php echo $row["last_name"]; ?>">
            </div>
            <div class="form-group col-6">
                <label for="first_name">Keresztnév</label>
                <input class="form-control" type="text" name="first_name" id="first_name" value="<?php echo $row["first_name"]; ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-6">
                <label for="email">Email cím</label>
                <input class="form-control" type="text" name="email" id="email" value="<?php echo $row["email"]; ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-12">
                <label for="user_role_selector">Jogosultság</label>
                <?php list_user_role_with_selected_option($row["role"]); ?>
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