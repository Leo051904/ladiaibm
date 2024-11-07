<?php 
    session_start();
    require "mimoaConx.php";

    $storeid = $_POST['storeid'];
    $storebranch = $_POST['storebranch'];
    $storecity = $_POST['storecity'];
    $storeprov = $_POST['storeprov'];
    $storephone = $_POST['storephone'];

    $sql_update = "UPDATE store SET STORE_BRANCH = '$storebranch', STORE_CITY = '$storecity', STORE_PROVINCE = '$storeprov', STORE_PHONE = '$storephone' WHERE STORE_ID = '$storeid'";
    $resultStore = $connect->query($sql_update);

    if ($resultStore) {
        header("Location: mimoaViewStore.php");
    }
?>