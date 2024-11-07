<?php
    require "mimoaConx.php";
    session_start();

    $email = $_POST['email'];
    $password = $_POST['pass'];

    $sqlVerify = "SELECT * FROM user WHERE USER_EMAIL = '$email' AND USER_PASS = '$password'";
    $result = $connect->query($sqlVerify);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        $_SESSION['user_id'] = $row['USER_ID'];
        $_SESSION['user_level'] = $row['USER_LEVEL'];

        if ($row['USER_LEVEL'] == 1) {
            header("Location: mimoaAdminPage.php");
        } else if ($row['USER_LEVEL'] == 2) {
            header("Location: mimoaHomePage.php");
        }
    } else {
        header("Location: mimoaLogError.php");
    }
    $connect->close();
?>