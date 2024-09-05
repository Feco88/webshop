<?php session_start(); ?>

<?php require "functions.php"; ?>
<?php require "header.php"; ?>

<?php 
    if (isset($_SESSION["role"])) {
        block_relogin($_SESSION["role"]); 
    }
?>  
    

<div class="container">
    <div class="row justify-content-center align-items-center flex-column" style="height:100vh;">

        <div class="col-12 col-md-6 col-lg-4">

            <?php
                if($_SERVER["REQUEST_METHOD"] === "POST") {

                    $inserted_values = array(
                        "last_name" => $_POST["last_name"],
                        "first_name" => $_POST["first_name"],
                        "email" => $_POST["email"],
                        "shipping_address" => $_POST["shipping_address"],
                        "password" => $_POST["password"],
                        "password_confirm" => $_POST["password_confirm"],
                    );
        
                    register_user($inserted_values);
                }
            ?>

            <div class="card">
                <div class="card-body">
                    <h3 class="mb-5 text-center">Regisztrálás</h3>

                    <form method="POST">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="last_name">Vezetéknév</label>
                                <input class="form-control" type="text" id="last_name" name="last_name">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="first_name">Keresztnév</label>
                                <input class="form-control" type="text" id="first_name" name="first_name">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="email">Email</label>
                                <input class="form-control" type="email" id="email" name="email">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="shipping_address">Szállítási cím</label>
                                <input class="form-control" type="shipping_address" id="shipping_address" name="shipping_address">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="password">Jelszó</label>
                                <input class="form-control" type="password" id="password" name="password">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="password-confirm">Jelszó megerősítése</label>
                                <input class="form-control" type="password" id="password-confirm" name="password_confirm">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12">
                                <input class="form-control btn btn-success" type="submit" id="password-confirm" name="submit" value="Regisztrálás">
                            </div>
                        </div>

                        <div class="form-row d-flex justify-content-center">
                            <a href="login.php">Bejelentkezés</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require "footer.php"; ?>
