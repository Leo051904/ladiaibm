<?php 
    session_start();
    require "mimoaConx.php";
    $user_id = $_SESSION['user_id'];
    
    $order_id = $_POST['order_id'];
    $pay_status = $_POST['pay_status'];

    // Update payment status for the selected order
    $sql_update = "UPDATE payment SET PAY_STATUS = $pay_status WHERE ORDER_ID = $order_id";

    if ($connect->query($sql_update) === TRUE) {
        // Fetch group ID of the selected order
        $sql_group_id = "SELECT GROUP_ID FROM orderprod WHERE ORDER_ID = $order_id";
        $result_group_id = $connect->query($sql_group_id);
        $row_group_id = $result_group_id->fetch_assoc();
        $group_id = $row_group_id['GROUP_ID'];

        // Update payment status for all orders in the same group with the same user ID
        $sql_update_orderprod = "UPDATE orderprod 
                                 SET ORDER_STATUS = $pay_status 
                                 WHERE GROUP_ID = $group_id";
        $connect->query($sql_update_orderprod);

        // Update payment status for all payments in the same group
        $sql_update_payment = "UPDATE payment 
                               SET PAY_STATUS = $pay_status 
                               WHERE ORDER_ID IN (SELECT ORDER_ID FROM orderprod WHERE GROUP_ID = $group_id)";
        $connect->query($sql_update_payment);

        header("Location: mimoaAdminTransactions.php");
    }
?>
