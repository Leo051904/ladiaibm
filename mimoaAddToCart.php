<?php
session_start();
require "mimoaConx.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: mimoaLog.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the form is submitted and the necessary data is provided
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addToCart']) && isset($_POST['product']) && isset($_POST['quantity']) && isset($_POST['selectedStore'])) {
    $product = $_POST['product'];
    $quantity = $_POST['quantity'];
    $selectedStore = $_POST['selectedStore'];

    // Fetch product details
    $sqlProd = "SELECT * FROM product WHERE PROD_NAME = '$product'";
    $resultProd = $connect->query($sqlProd);
    $rowProd = $resultProd->fetch_assoc();
    $prodId = $rowProd['PROD_ID'];
    $prodPrice = $rowProd['PROD_PRICE'];

    // Calculate total amount
    $totalAmount = $quantity * $prodPrice;

    // Check the latest order status of the user
    $latestOrderStatus = getLatestOrderStatus($user_id);

    // Start a transaction
    
    $connect->begin_transaction();
    
    // If the latest order status is 1 or no previous orders found, generate a new group ID
    if ($latestOrderStatus == 1 || $latestOrderStatus === null ) {
        $groupId = generateNewGroupId();
    } else {
        // Get the current group ID
        $groupId = getCurrentGroupId($user_id);
    }

    // Insert new order
    $insertOrderSql = "INSERT INTO orderprod (USER_ID, STORE_ID, PROD_ID, ORDER_QUANTITY, ORDER_TOTALAMOUNT, ORDER_STATUS, ORDER_DATETIME, GROUP_ID) 
                       VALUES ('$user_id', '$selectedStore', '$prodId', '$quantity', '$totalAmount', '2', current_timestamp(), '$groupId')";
    if ($connect->query($insertOrderSql) === TRUE) {
        // Commit transaction
        $connect->commit();
        //echo "New order added to cart successfully.";
        header("Location: mimoaOrder.php");
    } else {
        // Rollback transaction if error occurs
        $connect->rollback();
        echo "Error adding new order to cart: " . $connect->error;
        exit();
    }
} else {
    echo "Invalid request.";
}

$connect->close();

// Function to get the latest order status of the user
function getLatestOrderStatus($user_id) {
    global $connect;

    $latestOrderSql = "SELECT ORDER_STATUS FROM orderprod WHERE USER_ID = '$user_id' ORDER BY ORDER_DATETIME DESC LIMIT 1";
    $result = $connect->query($latestOrderSql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row["ORDER_STATUS"];
    } else {
        return null; // No orders found
    }
}

// Function to generate a new GROUP_ID for any user, incrementing the maximum used group ID by 1
function generateNewGroupId() {
    global $connect;

    // Get the maximum GROUP_ID used by any user and increment it by 1
    $maxGroupIdSql = "SELECT MAX(GROUP_ID) AS max_group_id FROM orderprod";
    $result = $connect->query($maxGroupIdSql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $maxGroupId = $row["max_group_id"];
        if ($maxGroupId === null) {
            return 1;
        } else {
            return $maxGroupId + 1;
        }
    } else {
        return 1; // If no existing GROUP_ID found, start from 1
    }
}

// Function to get the current group ID
function getCurrentGroupId($user_id) {
    global $connect;

    $currentGroupIdSql = "SELECT GROUP_ID FROM orderprod WHERE USER_ID = '$user_id' ORDER BY ORDER_DATETIME DESC LIMIT 1";
    $result = $connect->query($currentGroupIdSql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row["GROUP_ID"];
    } else {
        return null; // No orders found
    }
}
?>
