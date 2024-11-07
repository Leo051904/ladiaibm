<?php
    session_start();
    require "mimoaConx.php";

    if (!isset($_SESSION['user_id'])) {
        header("Location: mimoaLog.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $prod_id = $_POST['prod_id'];
    $ord_status = $_POST['ord_status'];

    // Check if there are payments associated with the order
    $sqlCheckPayments = "SELECT * FROM payment WHERE ORDER_ID IN (SELECT ORDER_ID FROM orderprod WHERE PROD_ID = '$prod_id' AND USER_ID = '$user_id' AND ORDER_STATUS = '$ord_status')";
    $resultCheckPayments = $connect->query($sqlCheckPayments);

    if ($resultCheckPayments->num_rows > 0) {
        // If payments exist, delete them first
        $sqlDeletePayments = "DELETE FROM payment WHERE ORDER_ID IN (SELECT ORDER_ID FROM orderprod WHERE PROD_ID = '$prod_id' AND USER_ID = '$user_id' AND ORDER_STATUS = '$ord_status')";
        $resultDeletePayments = $connect->query($sqlDeletePayments);

        if ($resultDeletePayments) {
            // After deleting payments, delete the order from orderprod
            $sqlRemove = "DELETE FROM orderprod WHERE PROD_ID = '$prod_id' AND USER_ID = '$user_id' AND ORDER_STATUS = '$ord_status'";
            $resultRemove = $connect->query($sqlRemove);

            if ($resultRemove === TRUE) {
                header("Location:mimoaCart.php");
            }
        } else {
            // Handle error if payment deletion fails
            echo "Error deleting payments: " . $connect->error;
        }
    } else {
        // If no payments exist, directly delete the order from orderprod
        $sqlRemove = "DELETE FROM orderprod WHERE PROD_ID = '$prod_id' AND USER_ID = '$user_id' AND ORDER_STATUS = '$ord_status'";
        $resultRemove = $connect->query($sqlRemove);

        if ($resultRemove === TRUE) {
            header("Location:mimoaCart.php");
        }
    }
?>
