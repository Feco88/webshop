<?php session_start(); ?>

<?php require "functions.php"; ?>
<?php require "header.php"; ?>

<?php
    $role = isset($_SESSION["role"]) ? $_SESSION["role"] : null;
    $first_name = isset($_SESSION["first_name"]) ? $_SESSION["first_name"] : null;
    $last_name = isset($_SESSION["last_name"]) ? $_SESSION["last_name"] : null;
?>

<?php check_permission($role, $first_name, $last_name, false, "category.php"); ?>
<?php logout_user(); ?>

<div class="container">
    <div class="row ml-0">
        <nav class="my-5" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Főoldal</a></li>
                <li class="breadcrumb-item"><a href="categories.php">Kategóriák</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $_GET["category_name"]; ?></li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <?php get_products_by_category($_GET["category_name"]); ?>        
    </div>
</div>

<?php require "footer.php"; ?>