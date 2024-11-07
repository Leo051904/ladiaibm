<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mimoadbpract";

$connect = mysqli_connect($servername, $username, $password, $dbname);

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}
?>