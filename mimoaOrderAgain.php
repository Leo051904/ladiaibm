<?php
session_start();
require "mimoaConx.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: mimoaLog.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if all necessary data is provided
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_ids']) && isset($_POST['group_id']) && isset($_POST['product_names']) && isset($_POST['order_quantity']) && isset($_POST['prod_prices']) && isset($_POST['total_amount']) && isset($_POST['order_datetime']) && isset($_POST['prod_ids'])) {
    $order_ids = $_POST['order_ids'];
    $group_id = $_POST['group_id'];
    $product_names = $_POST['product_names'];
    $order_quantity = $_POST['order_quantity'];
    $prod_prices = $_POST['prod_prices'];
    $total_amount = $_POST['total_amount'];
    $order_datetime = $_POST['order_datetime'];
    $prod_ids = $_POST['prod_ids'];

    // Insert the order into the table
    $insertOrderSql = "INSERT INTO orderprod (USER_ID, GROUP_ID, ORDER_ID, PROD_ID, ORDER_QUANTITY, PROD_PRICE, ORDER_TOTALAMOUNT, ORDER_STATUS, ORDER_DATETIME) VALUES ";

    // Split the values by <br> tag
    $order_ids_arr = explode("<br>", $order_ids);
    $product_names_arr = explode("<br>", $product_names);
    $order_quantity_arr = explode("<br>", $order_quantity);
    $prod_prices_arr = explode("<br>", $prod_prices);
    $prod_ids_arr = explode("<br>", $prod_ids);

    // Iterate over each item and insert into the database
    for ($i = 0; $i < count($order_ids_arr); $i++) {
        $order_id = $order_ids_arr[$i];
        $prod_id = $prod_ids_arr[$i];
        $quantity = $order_quantity_arr[$i];
        $prod_price = $prod_prices_arr[$i];
        
        // Insert the values into the SQL statement
        $insertOrderSql .= "('$user_id', '$group_id', '$order_id', '$prod_id', '$quantity', '$prod_price', '$total_amount', '2', '$order_datetime'), ";
    }

    // Remove the trailing comma and execute the query
    $insertOrderSql = rtrim($insertOrderSql, ", ");
    if ($connect->query($insertOrderSql) === TRUE) {
        // Order inserted successfully, redirect to some page
        header("Location: some_page.php");
    } else {
        // Error inserting order
        echo "Error inserting order: " . $connect->error;
    }
} else {
    echo "Invalid request.";
}

$connect->close();
?>
