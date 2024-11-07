<?php
    session_start();
    require "mimoaConx.php";

    $prodid = $_POST['prodid'];

    $sqlRemove = "DELETE FROM product WHERE PROD_ID = '$prodid'";
    $resultRemove = $connect->query($sqlRemove);

    if ($resultRemove) {
        header("Location:mimoaViewProducts.php");
    }

?>