<?php session_start(); ?>

<?php require "../functions.php"; ?>
<?php require "../header.php"; ?>

<?php
    $role = isset($_SESSION["role"]) ? $_SESSION["role"] : null;
    $first_name = isset($_SESSION["first_name"]) ? $_SESSION["first_name"] : null;
    $last_name = isset($_SESSION["last_name"]) ? $_SESSION["last_name"] : null;
?>

<?php check_permission($role, $first_name, $last_name, true, "users.php"); ?>
<?php logout_user(); ?>

<div class="container">
    <div class="row my-5">
        <a href="dashboard.php" class="ml-3">< Vissza a vezérlőpultra</a>
    </div>
    <div class="row mb-3">
        <div class="col">
            <h3>Felhasználók</h3>
        </div>
    </div>

    <?php
        if (isset($_GET["delete"]) && isset($_GET["email"])) {
            delete_user($_GET["delete"], $_GET["email"]);
        }
    ?>

    <div class="row">
        <div class="col">
            <?php
                if (isset($_GET["updated"])) {
                    $update_user = $_GET['updated'];
                    if (user_exist($update_user)) {
                        echo "<div class='alert alert-success' role='alert'>A következő felhasználó: <strong>$update_user</strong> adatai módosultak!</div>";
                    } else {
                        header("Location: users.php");
                    }
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
                        <th>Vezetéknév</th>
                        <th>Keresztnév</th>
                        <th>Email</th>
                        <th>Jogosultság</th>
                        <th>kezelés</th>
                    </tr>
                </thead>
                <tbody>
                    <?php show_users_table($_SESSION["email"]); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require "../footer.php"; ?>