<?php
    session_start();
    require "mimoaConx.php";

    $user_id = $_SESSION['user_id'];
    $group_id = $_POST['groupid'];
   
    $sqlDeletePay = "DELETE FROM payment where GROUP_ID = '$group_id' AND PAY_STATUS = '2'";
    $resultDeletePay = $connect->query($sqlDeletePay);

    if($resultDeletePay){ 
        $sqlDeleteOrd = "DELETE FROM orderprod where USER_ID = '$user_id' AND ORDER_STATUS = '2' AND GROUP_ID = '$group_id'";
        $resultDeleteOrd = $connect->query($sqlDeleteOrd);
        header("Location: mimoaHomePage.php");
        exit();
    }