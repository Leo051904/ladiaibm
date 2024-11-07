<?php
    require "mimoaConx.php";

    $cFirst = $_POST["fname"];
    $cLast = $_POST["lname"];
    $cEmail = $_POST["email"];
    $cPhoneNum = $_POST["phone"];
    $cPswd = $_POST["pass"];

    $sql_insert = "INSERT INTO user (USER_ID, USER_FNAME, USER_LNAME, USER_EMAIL, USER_PHONE, USER_PASS, USER_LEVEL, USER_DATETIME) 
               VALUES (NULL, '$cFirst', '$cLast', '$cEmail', '$cPhoneNum', '$cPswd', '2', current_timestamp())";

    if ($connect->query($sql_insert) === TRUE) {
        header("Location: mimoaLog.php");
        exit();
    } else {
        
        echo "Error: " . $sql_insert . "<br>" . $connect->error;
    }

    $connect->close();


