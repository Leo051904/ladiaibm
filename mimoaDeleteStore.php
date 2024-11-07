<?php
    session_start();
    require "mimoaConx.php";

    $storeid = $_POST['storeid'];

    $sqlRemove = "DELETE FROM store WHERE STORE_ID = '$storeid'";
    $resultRemove = $connect->query($sqlRemove);

    if ($resultRemove) {
        header("Location:mimoaViewStore.php");
    }

?>