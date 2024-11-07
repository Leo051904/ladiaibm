<?php
    require "mimoaConx.php";

    $store_branch = $_POST['store_branch'];
    $store_city = $_POST['store_city'];
    $store_prov = $_POST['store_prov'];
    $store_phone = $_POST['store_phone'];
   

    $sql_insert = "INSERT INTO store (STORE_ID, STORE_BRANCH, STORE_CITY, STORE_PROVINCE, STORE_PHONE, STORE_DATETIME) 
               VALUES (NULL, '$store_branch', '$store_city', '$store_prov', '$store_phone',current_timestamp())";

    if ($connect->query($sql_insert) === TRUE) {
        header("Location: mimoaAdminStore.php");
        exit();
    } else {
        
        echo "Error: " . $sql_insert . "<br>" . $connect->error;
    }

    $connect->close();


