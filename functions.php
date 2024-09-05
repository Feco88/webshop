<?php

//////////////////////
/// DB CONNECTION ////
//////////////////////

function db_connection() {
    $server_name = "localhost";
    $username = "root";
    $password = "";
    $db_name = "webshop";

    $connection = new mysqli($server_name, $username, $password, $db_name);

    if ($connection->connect_error) {
        die("Connection failed: $connection->connect_error");
    } 

    return $connection;
}

function run_query($sql_query) {
    $connection = db_connection();
    return $connection->query($sql_query);
}
function run_query_and_get_id($sql_query) {
    $connection = db_connection();
    $connection->query($sql_query);
    return $connection->insert_id;
}

//////////////////////
////// PRODUCTS //////
//////////////////////

function get_products() {

    $result = run_query("SELECT * FROM `products`");
    if ($result->num_rows > 0) {
        while($result && $row = $result->fetch_assoc()) {
            $id = $row["id"];
            $product_name = $row["product_name"];
            $product_price = number_format($row["product_price"], "0", ",", ".");
            
            echo "<div class='col-sm-12 col-md-6 col-lg-3 mb-4'>
                <div class='card'>
                    <div class='card-body'>
                        <a href='product.php?id=$id'>
                            <img class='w-100' src='https://placehold.co/400x300?text=$product_name'>
                        </a>
                        <a href='product.php?id=$id'>
                            <h5 class='card-title my-3'>$product_name</h5>
                        </a>
                        <p class='card-subtitle text-muted mb-3'>$product_price Ft</p>
                        <a class='card-link' href='product.php?id=$id'>Megtekintés ></a>
                    </div>
                </div>
            </div>";
        }
    } else {
        echo "<p class='col'>Nincs találat.</p>";
    }
}


function get_product($product_id, $role, $cart) {
    $product_id = intval($product_id);
    
    $result = run_query("SELECT * FROM `products` WHERE id = $product_id");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $product_name = $row["product_name"];
            $product_price = number_format($row["product_price"], 0, ",", ".");
            $product_description = $row["product_description"];
            $in_stock = intval($row["in_stock"]);
            $in_stock_message = "";

            if (!isset($role)) {
                if ($in_stock === 0) {
                    $in_stock_message = "<p class='text-danger'>Nincs készleten.</p>";
                } else {
                    $in_stock_message = "<p>Készleten: $in_stock db</p>
                    <div class='alert alert-warning'>Rendelés leadása előtt, kérlek jelentkezz be!</div>";
                }
            }

            if (isset($role) && $role !== "admin") {
                $items_in_cart = array();
                for ($i = 0; $i < count($cart); $i++) {
                    array_push($items_in_cart, $cart[$i]["product_name"]);
                }

                $index = array_search($product_name, $items_in_cart);
                $available_quantity = $in_stock;
                $in_cart = 0;
                if ($index !== false) {
                    $in_cart = $cart[$index]["quantity"];
                    $available_quantity = $in_stock - $cart[$index]["quantity"];
                }

                if ($in_stock === 0) {
                    $in_stock_message = "<p class='text-danger'>Nincs készleten.</p>";
                } else {
                    if ($available_quantity === 0) {
                        $in_stock_message = "<p class='text-danger'>Készleten $in_stock db <span class='text-danger'>Kosárban: $in_cart db.</span></p>";
                    } else {
                        $in_stock_message = "<p class='text-danger'>Készleten $in_stock db <span class='text-danger'>Kosárban: $in_cart db.</span></p>
                        <form method='POST'>
                            <input type='number' class='form-control' value='1' min='1' max='$available_quantity' name='quantity'>
                            <button class='btn btn-success btn-lg mt-3' type='submit' name='add_to_cart'>Kosárba</button>
                        </form>";
                    }
                }
            }

            echo "<div class='col-sm-12 col-lg-6'>
                <img class='w-100 mb-4' src='https://placehold.co/400x300?text=$product_name'>
            </div>
            <div class='col-sm-12 col-lg-6'>
                <h3>$product_name</h3>
                <h5 class='text-secondary'>$product_price Ft</h5>
                <p>$product_description</p>
                $in_stock_message
            </div>";
        }
    } else {
        header("Location: products.php");
    }
}


//////////////////////
////// CATEGORY //////
//////////////////////

function get_categories() {

    $result = run_query("SELECT * FROM `categories`");
    if ($result->num_rows > 0) {
        while($result && $row = $result->fetch_assoc()) {

            $id = $row["id"];
            $category_name = $row["category_name"];

            echo "<div class='col-sm-12 col-md-6 col-lg-3 mb-4'>
                <div class='card'>
                    <div class='card-body'>
                        <a href='category.php?category_name=$category_name'>
                            <img class='w-100' src='https://placehold.co/400x300?text=$category_name'>
                        </a>
                        <a href='category.php?category_name=$category_name'>
                            <h5 class='card-title my-3'>$category_name</h5>
                        </a>
                        <a class='card-link' href='category.php?category_name=$category_name'>Megtekintés ></a>
                    </div>
                </div>
            </div>";
        }
    } else {
        echo "<p class='col'>Nincs találat.</p>";
    }
}

function get_products_by_category($category_name) {

    $result = run_query("SELECT * FROM `products` WHERE `category` = '$category_name'");
    if ($result->num_rows > 0) {
        while($result && $row = $result->fetch_assoc()) {

            $id = $row["id"];
            $product_name = $row["product_name"];
            $product_price = number_format($row["product_price"], "0", ",", ".");

            echo "<div class='col-sm-12 col-md-6 col-lg-3 mb-4'>
                <div class='card'>
                    <div class='card-body'>
                        <a href='product.php?id=$id'>
                            <img class='w-100' src='https://placehold.co/400x300?text=$product_name'>
                        </a>
                        <a href='product.php?id=$id'>
                            <h5 class='card-title my-3'>$product_name</h5>
                        </a>
                        <p class='card-subtitle text-muted mb-3'>$product_price Ft</p>
                        <a class='card-link' href='product.php?id=$id'>Megtekintés ></a>
                    </div>
                </div>
            </div>";
        }
    } else {
        header("Location: categories.php");
    }
}

//////////////////////
//////// ADMIN ///////
//////////////////////

function show_card($card_title, $card_bg, $table_name, $link_to) {

    $result = run_query("SELECT * FROM $table_name");

    if ($result->num_rows > 0) {
        $product_quantity = $result->num_rows;
    } else {
        $product_quantity = 0;
    }
    
    echo "
    <div class='col-12 col-md-6 col-lg-3'>
        <a class='text-decoration-none' href='$link_to'>
            <div class='card text-white bg-$card_bg mb-3'>
                <div class='card-body'>
                    <h5 class='card-title'>$card_title</h5>
                    <p class='card-text'>$product_quantity db</p>
                </div>        
            </div>
        </a>
    </div>";

}

function list_category_options() {

    $result = run_query("SELECT * FROM `categories`");
    if ($result->num_rows > 0) {
        echo "<select class='form-control' name='selected_category' id='product_category_selector'>
            <option selected>-- Válassz kategóriát --</option>";
    
        while($result && $row = $result->fetch_assoc()) {
            $category_name = $row["category_name"];
            echo "<option value='$category_name'>$category_name</option>";
        }
        echo "</select>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Még nincsenek kategóriák!</div>";
    }
}

function list_category_with_selected_option($origin_category) {

    $result = run_query("SELECT * FROM `categories`");
    if ($result->num_rows > 0) {
        echo "<select class='form-control' name='selected_category' id='product_category_selector'>";        
    
        while($result && $row = $result->fetch_assoc()) {

            $category_name = $row["category_name"];

            if ($category_name == $origin_category) {
                echo "<option value='$category_name' selected>$category_name</option>";
            } else {
                echo "<option value='$category_name'>$category_name</option>";
            }

        }
        echo "</select>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Még nincsenek kategóriák!</div>";
    }
}

function list_user_role_with_selected_option($origin_role) {
    // TODO: legyen dimanikus, ha későbbiekben további jogosultsági szintek kerülnének bevezetésre!!

    if ($origin_role === "admin") {
        echo "
            <select class='form-control' name='role' id='user_role_select'>
                <option value='admin' selected>admin</option>
                <option value='user'>user</option>
            </select>";
    } 
    if ($origin_role === "user") {
        echo "
            <select class='form-control' name='role' id='user_role_select'>
                <option value='user' selected>user</option>
                <option value='admin'>admin</option>
            </select>";
    }
}

function add_new_product($inserted_values) {

    $product_name = $inserted_values["product_name"];
    $product_description = $inserted_values["product_description"];
    $product_price = $inserted_values["product_price"];
    $in_stock = $inserted_values["in_stock"];
    $selected_category = $inserted_values["selected_category"];

    $result = run_query("SELECT * FROM `products` WHERE `product_name` = '$product_name'");
    if ($result->num_rows > 0) {
        echo "
        <div class='container'>
            <div class='row mt-5'>
                <div class='col-12'>
                    <div class='alert alert-danger' role='alert'>A megadott termék: <strong>$product_name</strong> már létezik!</div>
                </div>
            </div>
        </div>";
    } else {
        run_query("INSERT INTO `products` (`id`, `product_name`, `product_description`, `product_price`, `in_stock`, `category`) VALUES ('', '$product_name', '$product_description', '$product_price', '$in_stock', '$selected_category')");

        echo "<div class='container'>
            <div class='row mt-5'>
                <div class='col-12'>
                    <div class='alert alert-success' role='alert'>Új termék: <strong>$product_name</strong> hozzáadva!</div>
                </div>
            </div>
        </div>";
    }
}

function add_new_category($inserted_values) {

    $category_name = $inserted_values["category_name"];

    $result = run_query("SELECT * FROM `categories` WHERE `category_name` = '$category_name'");
    if ($result->num_rows > 0) {
        echo "
        <div class='container'>
            <div class='row mt-5'>
                <div class='col-12'>
                    <div class='alert alert-danger' role='alert'>A megadott kategória: <strong>$category_name</strong> már létezik!</div>
                </div>
            </div>
        </div>";
    } else {
        run_query("INSERT INTO `categories` (`id`, `category_name`) VALUES ('', '$category_name')");

        echo "<div class='container'>
            <div class='row mt-5'>
                <div class='col-12'>
                    <div class='alert alert-success' role='alert'>Új kategória: <strong>$category_name</strong> hozzáadva!</div>
                </div>
            </div>
        </div>";
    }
}

function show_products_table() {
    $result = run_query("SELECT * FROM `products`");
    if ($result->num_rows > 0) {
        while($result && $row = $result->fetch_assoc()) {

            $id = $row["id"];
            $product_name = $row["product_name"];
            $product_description = $row["product_description"];
            $product_price = number_format($row["product_price"], "0", ",", ".");
            $in_stock = $row["in_stock"];
            $category = $row["category"];


            if ($in_stock < 5) {
                $in_stock_color = "danger";
            } else {
                $in_stock_color = "dark";
            }

            if ($category == "uncategorized") {
                $bg = "bg-secondary";
            } else {
                $bg = "bg-light";
            }
            
            echo "
            <tr class='$bg'>
                <td>$id</td>
                <td>$product_name</td>
                <td>$product_description</td>
                <td>$product_price Ft</td>
                <td class='text-$in_stock_color'>$in_stock db</td>
                <td>$category</td>
                <td>
                    <a class='text-success' href='../product.php?id=$id'><i class='fa-solid fa-eye'></i></a>
                    <a class='text-warning' href='edit-product.php?edit=$id'><i class='fa-solid fa-gear'></i></a>
                    <a class='text-danger' href='?delete=$id&product_name=$product_name'><i class='fa-solid fa-trash'></i></a>
                </td>
            </tr>";

        }
    } else {
        echo "<tr ><td colspan='7' class='text-center'>Nincs termék.</td></tr>";
    }
}

function show_categories_table() {
    $result = run_query("SELECT * FROM `categories`");
    if ($result->num_rows > 0) {
        while($result && $row = $result->fetch_assoc()) {

            $id = $row["id"];
            $category_name = $row["category_name"];

            $category_result = run_query("SELECT * FROM `products` WHERE `category` = '$category_name'");

            echo "
            <tr>
                <td>$id</td>
                <td>$category_name</td>
                <td>$category_result->num_rows</td>
                <td>
                    <a class='text-warning' href='edit-category.php?edit=$id'><i class='fa-solid fa-gear'></i></a>
                    <a class='text-danger' href='?delete=$id&category_name=$category_name'><i class='fa-solid fa-trash'></i></a>
                </td>
            </tr>";

        }
    } else {
        echo "<tr ><td colspan='7' class='text-center'>Nincs termék.</td></tr>";
    }
}

function show_users_table($logged_in_email) {
    $result = run_query("SELECT * FROM `users`");
    if ($result->num_rows > 0) {
        while($result && $row = $result->fetch_assoc()) {

            $id = $row["id"];
            $last_name = $row["last_name"];
            $first_name = $row["first_name"];
            $email = $row["email"];
            $role = $row["role"];


            echo "
            <tr>
                <td>$id</td>
                <td>$last_name</td>
                <td>$first_name</td>
                <td>$email</td>
                <td>$role</td>
                <td><a class='text-warning mr-2' href='edit-user.php?edit=$id'><i class='fa-solid fa-gear'></i></a>"; 

                if ($logged_in_email != $email) {
                    echo "<a class='text-danger' href='?delete=$id&email=$email'><i class='fa-solid fa-trash'></i></a>";
                }
            echo "
                
                </td>
            </tr>";

        }
    } else {
        echo "<tr ><td colspan='7' class='text-center'>Nincs termék.</td></tr>";
    }
}

function delete_user($id, $email) {

    $result = run_query("SELECT * FROM `users` WHERE `id` = $id");
    if ($result->num_rows > 0) {
        run_query("DELETE FROM `users` WHERE `id` = $id");
        echo "<div class='alert alert-success' role='alert'>A kiválasztott felhasználó: <strong>$email</strong> törlésre került!</div>";
    } else {
        header("Location: users.php");
    }

}

function delete_product($id, $product_name) {
    run_query("DELETE FROM `products` WHERE `id` = $id");
    // TODO: check for errors!
    echo "<div class='alert alert-success' role='alert'>A kiválasztott termék: <strong>$product_name</strong> törlésre került!</div>";
}

function delete_category($id, $category_name) {

    $result = run_query("SELECT * FROM `categories` WHERE `id` = $id");
    if ($result->num_rows > 0) {
        run_query("DELETE FROM `categories` WHERE `id` = $id");
        run_query("UPDATE `products` SET `category` = 'uncategorized' WHERE `category` = '$category_name'");
        echo "<div class='alert alert-success' role='alert'>A kiválasztott kategória: <strong>$category_name</strong> törlésre került!</div>";
    } else {
        header("Location: categories.php");
    }

}

function load_product_details($id) {
    $result = run_query("SELECT * FROM `products` WHERE `id` = $id");
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        header("Location: products.php");
    }
}

function load_category_details($id) {
    $result = run_query("SELECT * FROM `categories` WHERE `id` = $id");
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        header("Location: categories.php");
    }
}

function load_user_details($id) {
    $result = run_query("SELECT * FROM `users` WHERE `id` = $id");
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        header("Location: users.php");
    }
}

function update_product($inserted_values) {
    $id = $inserted_values["id"];
    $product_name = $inserted_values["product_name"];
    $product_description = $inserted_values["product_description"];
    $product_price = $inserted_values["product_price"];
    $in_stock = $inserted_values["in_stock"];
    $selected_category = $inserted_values["selected_category"];

    run_query("UPDATE `products` SET `product_name` = '$product_name', `product_description` = '$product_description', `product_price` = '$product_price', `in_stock` = '$in_stock', `category` = '$selected_category' WHERE `id` = '$id'");

    header("Location: products.php?updated=$product_name");
}

function update_category($inserted_values) {
    $id = $inserted_values["id"];
    $origin_category = $inserted_values["origin_category"];
    $updated_category = $inserted_values["updated_category"];

    $result = run_query("SELECT * FROM `categories` WHERE `id` = $id");
    if ($result->num_rows > 0) {
        run_query("UPDATE `categories` SET `category_name` = '$updated_category' WHERE `id` = '$id'");
        run_query("UPDATE `products` SET `category` = '$updated_category' WHERE `category` = '$origin_category'");
        echo "<div class='alert alert-success' role='alert'>A kiválasztott kategória: <strong>$origin_category</strong> adatai módosultak!</div>";
    } else {
        header("Location: categories.php");
    }
}

function update_user($inserted_values) {
    $id = $inserted_values["id"];
    $first_name = $inserted_values["first_name"];
    $last_name = $inserted_values["last_name"];
    $email = $inserted_values["email"];
    $role = $inserted_values["role"];

    run_query("UPDATE `users` SET `first_name` = '$first_name', `last_name` = '$last_name', `email` = '$email', `role` = '$role' WHERE `id` = '$id'");

    header("Location: users.php?updated=$email");
}

function product_name_exist($updated_product) {
    $result = run_query("SELECT * FROM `products` WHERE `product_name` = '$updated_product'");
    return $result->num_rows > 0;
}

function user_exist($updated_user_email) {
    $result = run_query("SELECT * FROM `users` WHERE `email` = '$updated_user_email'");
    return $result->num_rows > 0;
}

function category_name_exist($updated_category) {
    $result = run_query("SELECT * FROM `categories` WHERE `category_name` = '$updated_category'");
    return $result->num_rows > 0;
}

function encrypt_password($password) {
    $hash_format = "$2y$10$";
    $salt = "thisismyverylongsaltstring";
    $hash_format_and_salt = $hash_format . $salt;

    return crypt($password, $hash_format_and_salt);
}

function register_user($inserted_values) {

    $last_name = $inserted_values["last_name"];
    $first_name = $inserted_values["first_name"];
    $email = $inserted_values["email"];
    $shipping_address = $inserted_values["shipping_address"];
    $password = $inserted_values["password"];
    $password_confirm = $inserted_values["password_confirm"];

    // email already in use??
    if (!email_not_used($email)) {
        generate_alert("Az emailcím már regisztrálva van!", "danger");
        return;
    }
    // passwords are matching??
    if (!passwords_matched($password,$password_confirm)) {
        generate_alert("A jelszavak nem egyeznek!", "danger");
        return;
    }

    $encrypted_password = encrypt_password($password);

    run_query("INSERT INTO `users` (`id`, `last_name`, `first_name`, `email`, `password`, `role`, `address`) VALUES ('', '$last_name', '$first_name', '$email', '$encrypted_password', 'user', '$shipping_address')");

    header("Location: login.php");
}

function email_not_used($email) {
    $result = run_query("SELECT * FROM `users` WHERE `email` = '$email'");
    return $result->num_rows === 0;
}

function passwords_matched($password,$password_confirm) {
    return $password === $password_confirm;
}

function generate_alert($message, $color) {
    echo "<div class='alert alert-$color' role='alert'>$message</div>";
}


function login_user($inserted_values) {
    $inserted_email = $inserted_values["email"];
    $inserted_password = $inserted_values["password"];

    $result = run_query("SELECT * FROM `users` WHERE `email` = '$inserted_email'");
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $hashed_password = $row["password"];
            
            $verify = password_verify($inserted_password, $hashed_password);
            if ($verify) {
                session_start();

                $_SESSION["id"] = $row["id"];
                $_SESSION["email"] = $row["email"];
                $_SESSION["first_name"] = $row["first_name"];
                $_SESSION["last_name"] = $row["last_name"];
                $_SESSION["role"] = $row["role"];
                $_SESSION["address"] = $row["address"];

                if ($row["role"] === "admin") {
                    header("Location: ./admin/dashboard.php");
                }
                if ($row["role"] === "user") {
                    header("Location: ./products.php");
                }

            } else {
                generate_alert("Az emailcím vagy a jelszó nem megfelelő!", "danger");
            }
        }
    } else {
        generate_alert("A megadott emailcímmel még nem regisztráltak!", "danger");
    }

}


function check_permission($role, $first_name, $last_name, $admin_restricted, $current_page) {
    if ($admin_restricted) {

        // ha nincs bejelentkezve vagy bevan jelentkezve, de nem admin
        if (!isset($role) || $role !== "admin") {
            header("Location: ../products.php");
        }

        // ha bevan jelentkezve és admin
        if (isset($role) && $role === "admin") {
            get_admin_navbar($current_page, $first_name, $last_name, $role);
        }
    }

    if (!$admin_restricted) {
        if (!isset($role)) {
            get_logged_out_navbar($current_page, $role);
        }
        if ($role === "user") {
            get_user_navbar($current_page, $first_name, $last_name, $role);
        }
        if ($role === "admin") {
            get_admin_navbar($current_page, $first_name, $last_name, $role);
        }
    }
}

function block_relogin($role) {
    if (isset($role)) {
        if ($role === "admin") {
            header("Location: ./admin/dashboard.php");
        }
        if ($role === "user") {
            header("Location: ./products.php");
        }
    }
}

function load_nav_menu_items($current_page, $role) {

    $menu_options = array();
    if ($role === "admin") {
        $menu_options = array(
            array(
                "menu_name" => "Vezérlőpult",
                "link_to" => "/webprog/webshop/admin/dashboard.php"
            ),
            array(
                "menu_name" => "Termékek",
                "link_to" => "products.php"
            ),
            array(
                "menu_name" => "Új termék",
                "link_to" => "add-new-product.php"
            ),
            array(
                "menu_name" => "Kategórák",
                "link_to" => "categories.php"
            ),
            array(
                "menu_name" => "Új kategória",
                "link_to" => "add-new-category.php"
            ),
            array(
                "menu_name" => "Bejelentkezés",
                "link_to" => "login.php"
            )
        );
    }

    if (!isset($role) || $role === "user") {
        $menu_options = array(
            array(
                "menu_name" => "Termékek",
                "link_to" => "products.php"
            ),
            array(
                "menu_name" => "Kategórák",
                "link_to" => "categories.php"
            ),
            array(
                "menu_name" => "Bejelentkezés",
                "link_to" => "login.php"
            )
        );
    }

    // adatbázisból betölteni??
    

    
    $items = "<ul class='navbar-nav'>";
    for ($i=0; $i < count($menu_options); $i++) { 

        if (isset($role) && $menu_options[$i]["link_to"] === "login.php") {
            continue;
        }

        $active = "";
        if ($current_page === $menu_options[$i]["link_to"]) {
            $active = "active";
        }
        
        $items .= "
            <li class='nav-item'>
                <a class='nav-link $active' href='" . $menu_options[$i]["link_to"] . "'>" . $menu_options[$i]["menu_name"] . "</a>
            </li>";
    } 
    $items .= "</ul>";
    echo $items;
}

function get_logged_out_navbar($current_page, $role) {

    echo "
        <div class='container-fluid'>
            <div class='row bg-light'>
                <div class='container'>

                    <nav class='navbar navbar-expand-lg navbar-light bg-light px-0'>
                    <a class='navbar-brand' href='#'>Webshop</a>
                    <button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarNav' aria-controls='navbarNav' aria-expanded='false' aria-label='Toggle navigation'>
                        <span class='navbar-toggler-icon'></span>
                    </button>
                        <div class='collapse navbar-collapse justify-content-end' id='navbarNav'>";

                        load_nav_menu_items($current_page, $role);

                    echo "
                        </div>
                    </nav>
                </div>
            </row>
        </div>
    ";
}

function get_user_navbar($current_page, $first_name, $last_name, $role) {
    echo "
        <div class='container-fluid'>
            <div class='row bg-light'>
                <div class='container'>

                    <nav class='navbar navbar-expand-lg navbar-light bg-light px-0'>
                    <a class='navbar-brand' href='#'>Webshop</a>
                    <button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarNav' aria-controls='navbarNav' aria-expanded='false' aria-label='Toggle navigation'>
                        <span class='navbar-toggler-icon'></span>
                    </button>
                        <div class='collapse navbar-collapse justify-content-end' id='navbarNav'>";

                        load_nav_menu_items($current_page, $role);

                        

                    echo "
                            <div class='dropdown '>
                                <a class='nav-link dropdown-toggle' href='#' role='button' data-toggle='dropdown' aria-expanded='false'>
                                    $last_name $first_name
                                </a>
                                <div class='dropdown-menu'>
                                    <a class='dropdown-item' href='/webprog/webshop/cart.php'>Kosár</a>
                                    <a class='dropdown-item' href='/webprog/webshop/my-orders.php'>Rendeléseim</a>
                                    <a class='dropdown-item' href='#'>Profil</a>
                                    <form method='POST'>
                                        <input class='dropdown-item' type='submit' name='logout' value='Kijelentkezés'>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </row>
        </div>
    ";
}

function get_admin_navbar($current_page, $first_name, $last_name, $role) {
    echo "
        <div class='container-fluid'>
            <div class='row bg-dark'>
                <div class='container'>

                    <nav class='navbar navbar-expand-lg navbar-dark bg-dark px-0'>
                    <a class='navbar-brand' href='#'>Webshop</a>
                    <button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarNav' aria-controls='navbarNav' aria-expanded='false' aria-label='Toggle navigation'>
                        <span class='navbar-toggler-icon'></span>
                    </button>
                        <div class='collapse navbar-collapse justify-content-end' id='navbarNav'>";

                        load_nav_menu_items($current_page, $role);

                        

                    echo "
                            <div class='dropdown'>
                                <a class='nav-link dropdown-toggle' href='#' role='button' data-toggle='dropdown' aria-expanded='false'>
                                    $last_name $first_name
                                </a>
                                <div class='dropdown-menu'>
                                    <a class='dropdown-item' href='/webprog/webshop/cart.php'>Kosár</a>
                                    <a class='dropdown-item' href='#'>Profil</a>
                                    <form method='POST'>
                                        <input class='dropdown-item' type='submit' name='logout' value='Kijelentkezés'>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </row>
        </div>
    ";
}

function logout_user() {
    
    if (isset($_POST["logout"])) {
        session_destroy();
        if (str_contains($_SERVER["REQUEST_URI"], "/webprog/webshop/admin")) {
            header("Location: ../login.php");
        } else {
            header("Location: ./login.php");
        }
    }
}

/////////////////////
/////// CART ////////
/////////////////////

function get_items_in_cart($cart) {
    if (count($cart) === 0) {
        echo 
        "<tr>
            <td colspan='6' class='text-center py-5'>A kosár üres!</td>
        </tr>";
    } else {
        for ($i=0; $i<count($cart); $i++) {
            $product_name = $cart[$i]["product_name"];
            $quantity = $cart[$i]["quantity"];
            $product_price = number_format($cart[$i]["product_price"], 0, ",", ".");
            $product_total_price = number_format($cart[$i]["product_total_price"], 0, ",", ".");

            echo 
            "<tr>
                <td>$product_name</td>
                <td>$quantity db</td>
                <td>$product_price Ft</td>
                <td>$product_total_price Ft</td>
                <td>
                    <a href='?remove_from_cart=$i'>
                        <button class='btn btn-danger'>
                            <i class='fa-solid fa-trash'></i>
                        </button>
                    </a>
                </td>
            </tr>";
        }
    }
}

function remove_item_from_cart($item_to_be_removed, $redirect_to) {
    unset($_SESSION["cart"][$item_to_be_removed]);
    $_SESSION["cart"] = array_values($_SESSION["cart"]);
    header("Location: $redirect_to.php");
}

function get_cart_total_price($cart) {
    $total_price = 0;
    for($i=0; $i<count($cart); $i++) {
        $total_price += $cart[$i]["product_total_price"];
    }
    echo number_format($total_price, 0, ",", ".");
}

function get_total_price_with_shipping_fee($cart) {
    $total_price = 0;
    $shipping_fee = 0;
    for($i=0; $i<count($cart); $i++) {
        $total_price += $cart[$i]["product_total_price"];
    }
    if ($total_price < 100000) {
        $shipping_fee = 1490;
    }
    $total_price += $shipping_fee;

    return array(
        "total" => $total_price,
        "shipping" => $shipping_fee
    );
}

function render_total_price_with_shipping_fee($cart) {
    $price = get_total_price_with_shipping_fee($cart);

    $total_price = number_format($price["total"], 0, ",", ".");
    $shipping_fee = number_format($price["shipping"], 0, ",", ".");

    echo "
        <p>Szállítási költség: $shipping_fee Ft</p>
        <h5>Fizetendő Összeg:</h5>
        <p>$total_price Ft</p>
    ";
}

function get_current_time_and_date() {
    return date("Y.m.d. H:i:s");
}

function register_order($email, $shipping_method, $shipping_address, $total_price, $cart) {

    $current_time = get_current_time_and_date();
    return run_query_and_get_id("INSERT INTO `orders` (`id`, `ordered_by_email`, `shipping_method`, `shipping_address`, `total_price`, `cart`, `order_placed_at`, `order_state`) VALUES (NULL, '$email', '$shipping_method', '$shipping_address', $total_price, '$cart', '$current_time', 'Feldolgozás alatt')");
}

function update_stock($cart) {
    for ($i=0; $i <count($cart); $i++) { 
        $product_name_in_cart = $cart[$i]["product_name"];
        $product_quantity_in_cart = $cart[$i]["quantity"];

        $result = run_query("SELECT * FROM `products` WHERE `product_name` = '$product_name_in_cart'");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $in_stock = intval($row["in_stock"]);

            $updated_stock_quantity = $in_stock - $product_quantity_in_cart;
            echo $updated_stock_quantity;

            run_query("UPDATE `products` SET `in_stock` = '$updated_stock_quantity' WHERE `product_name` = '$product_name_in_cart'");
        }
    }
}

function get_orders($role, $email) {

    if ($role === 'admin') {
        $result = run_query("SELECT * FROM `orders`");
    }
    if ($role === 'user') {
        $result = run_query("SELECT * FROM `orders` WHERE `ordered_by_email` = '$email'");
    }

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $order_id = $row["id"];
            $ordered_by_email = $row["ordered_by_email"];
            $shipping_method = $row["shipping_method"];
            $shipping_address = $row["shipping_address"];
            $total_price = number_format($row["total_price"], "0", ",", ".");
            $order_placed_at = $row["order_placed_at"];
            $order_state = $row["order_state"];

            echo 
            "<tr>
                <td>
                    <a href='order.php?order_id=$order_id'>#$order_id</a>
                </td>
                <td>$total_price Ft</td>";
                
                if ($role === 'admin') {
                    echo '<td>'.$ordered_by_email.'</td>';
                }

            echo "<td>$order_placed_at</td>
                <td>$order_state</td>
                <td>$shipping_address</td>
                <td>$shipping_method</td>
            </tr>";
        }
    } else {
        echo 
        "<tr>
            <td colspan='7' class='text-center py-5'>Még nincsenek rendelések!</td>
        </tr>";
    }
}

function get_order_items($order_id) {
    $result = run_query("SELECT * FROM `orders` WHERE `id` = '$order_id'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $order_id = $row["id"];
        $ordered_by_email = $row["ordered_by_email"];
        $shipping_method = $row["shipping_method"];
        $shipping_address = $row["shipping_address"];
        $total_price = number_format($row["total_price"], "0", ",", ".");
        $order_placed_at = $row["order_placed_at"];
        $order_state = $row["order_state"];
        $cart = json_decode($row["cart"], true);
        $ordered_items = "";

        for ($i=0; $i <count($cart); $i++) {

            $product_name = $cart[$i]["product_name"];
            $quantity = $cart[$i]["quantity"];
            $product_price = number_format($cart[$i]["product_price"], "0", ",", ".");
            $product_total_price = number_format($cart[$i]["product_total_price"], "0", ",", ".");
            $ordered_items .= 
            "<tr>
                <td>$product_name</td>
                <td>$quantity db</td>
                <td>$product_price Ft</td>
                <td>$product_total_price Ft</td>
            </tr>";
        }
        echo $ordered_items;
        
    } else {
        header("Location: orders.php");
    }
}

function get_order_details($order_id) {
    $result = run_query("SELECT * FROM `orders` WHERE `id` = '$order_id'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $order_id = $row["id"];
        $ordered_by_email = $row["ordered_by_email"];
        $shipping_method = $row["shipping_method"];
        $shipping_address = $row["shipping_address"];
        $total_price = number_format($row["total_price"], "0", ",", ".");
        $order_placed_at = $row["order_placed_at"];
        $order_state = $row["order_state"];

        if ($order_state == "Feldolgozás alatt") {
            $options = 
            "<select class='form-control mt-3' id='order_state' name='order_state'>
                <option selected>Feldolgozás alatt</option>
                <option>Szállítás alatt</option>
            </select>";
        }
        if ($order_state == "Szállítás alatt") {
            $options = 
            "<select class='form-control mt-3' id='order_state' name='order_state'>
                <option selected>Szállítás alatt</option>
                <option>Kézbesítve</option>
            </select>";
        }
        if ($order_state == "Kézbesítve") {
            $options = 
            "<select class='form-control mt-3' id='order_state' name='order_state'>
                <option selected>Kézbesítve</option>
            </select>";
            $submit_button = "";
        }

        if ($order_state != "Kézbesítve") {
            $submit_button = "<div class='row mt-3'>
                <div class='col'>
                    <input class='btn btn-success' type='submit' value='módosítás'>
                </div>
            </div>";
        }

        echo 
        "
            <div class='row mt-5'>
                <div class='col-4 col-lg-3'
                    <label for='order_state'>Rendelés Állapota</label>
                    <form method='POST'>
                        $options
                        $submit_button
                    </form>
                </div>
                <div class='col-4 col-lg-3'
                    <label for='ordered_by'>Megrendelő</label>
                    <form method='POST'>
                        <input class='form-control mt-3' id='ordered_by' value='$ordered_by_email' readonly>
                    </form>
                </div>
            </div>
        ";

    } else {
        header("Location: orders.php");
    }
}

function get_my_order_details($order_id) {
    $result = run_query("SELECT * FROM `orders` WHERE `id` = '$order_id'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $order_id = $row["id"];
        $ordered_by_email = $row["ordered_by_email"];
        $shipping_method = $row["shipping_method"];
        $shipping_address = $row["shipping_address"];
        $total_price = number_format($row["total_price"], "0", ",", ".");
        $order_placed_at = $row["order_placed_at"];
        $order_state = $row["order_state"];


        echo 
        "
            <div class='row mt-5'>
                <div class='col-4 col-lg-3'
                    <label for='order_state'>Rendelés Állapota</label>
                    <input class='form-control mt-3' id='order_state' value='$order_state' readonly>
                </div>
            </div>
        ";

    } else {
        header("Location: my-orders.php");
    }
}

?>
