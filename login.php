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
                        "email" => $_POST["email"],
                        "password" => $_POST["password"],
                    );
        
                    login_user($inserted_values);
                }
            ?>

            <div class="card">
                <div class="card-body">
                    <h3 class="mb-5 text-center">Bejelentkezés</h3>

                    <form method="POST">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="email">Email</label>
                                <input class="form-control" type="email" id="email" name="email">
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
                                <input class="form-control btn btn-success" type="submit" id="login" name="submit" value="Bejelentkezés">
                            </div>
                        </div>

                        <div class="form-row d-flex justify-content-center">
                            <a href="sign-up.php">Regisztrálás</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require "footer.php"; ?>
