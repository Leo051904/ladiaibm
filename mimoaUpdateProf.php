<?php 
    session_start();
    require "mimoaConx.php";
    $user_id = $_SESSION['user_id'];
    

    $cFirst = $_POST['firstName'];
    $cLast = $_POST['lastName'];
    $cEmail = $_POST['email'];
    $cPhoneNum = $_POST['phone'];
    $cPswd = $_POST['password'];

    $sql_update = "UPDATE user SET USER_FNAME = '$cFirst', USER_LNAME = '$cLast', USER_EMAIL = '$cEmail', USER_PHONE = '$cPhoneNum', USER_PASS = '$cPswd' WHERE USER_ID = '$user_id'";

    if ($connect->query($sql_update) === TRUE) {
        header("Location: mimoaAcc.php");
    }
?>