<?php
    session_start();
    require "mimoaConx.php";

    $user_id = $_SESSION['user_id'];

    $sqlDeleteOrd = "DELETE FROM orderprod where USER_ID = '" . $user_id ."' AND ORDER_STATUS = '" . 2 . "'";
    $resultDeleteOrd = $connect->query($sqlDeleteOrd);
    session_destroy();

    if($resultDeleteOrd){
        header("Location: mimoaLog.php");
        exit();
    }else{
        header("Location: mimoaLog.php");
    }
?>