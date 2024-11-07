<?php 
    require "mimoaConx.php";
    $uID = $_POST['uID'];

    if(isset($uID)){
        header("Location:mimoaHomePage.php?uID=".$uID);
    }