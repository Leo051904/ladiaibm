<?php
    session_start();
    require "mimoaConx.php";
    
    $user_id = $_SESSION['user_id'];
    $prod_id = $_POST['product_id'];
    $avail = $_POST['availability'];

    $sqlProd = "UPDATE product SET PROD_AVAIL = '$avail' WHERE PROD_ID = '$prod_id'";
    $resultProduct = $connect->query($sqlProd);

    if($resultProduct){
        header("Location: mimoaAdminProdAvail.php");
    }

    
?>