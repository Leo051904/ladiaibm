<?php 
    session_start();
    require "mimoaConx.php";

    $prodid = $_POST['prodid'];
    $prodname = $_POST['prodname'];
    $prodprice = $_POST['prodprice'];

    $sql_update = "UPDATE product SET PROD_NAME = '$prodname', PROD_PRICE = '$prodprice' WHERE PROD_ID = '$prodid'";
    $resultProduct = $connect->query($sql_update);

    if ($resultProduct) {
        header("Location: mimoaViewProducts.php");
    }
?>