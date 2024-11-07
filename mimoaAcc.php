<?php
    session_start();
    require "mimoaConx.php";

    if (!isset($_SESSION['user_id'])) {
        header("Location: mimoaLog.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM user WHERE USER_ID = '$user_id'";
    $result = $connect->query($sql);
    $row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Account</title>
  <link rel="stylesheet" href="styles.css">
</head>
<style>
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    padding-top:100px;
    background-color: #5c6961;
    }

    .container {
    max-width: 500px;
    margin: 50px auto;
    padding: 20px;
    border: 2px solid #ccc;
    border-radius: 5px;
    background-color: white;
    }

    h1 {
    text-align: center;
    font-family: 'Copperplate';
	font-weight: bold;
	font-style: italic;
    color: #50C878;
    text-shadow: 1px 1px 2px black;
    }

    .form-group {
    margin-bottom: 15px;
    }

    label {
    display: block;
    margin-bottom: 5px;
    }

    input[type="text"],
    input[type="email"],
    input[type="tel"],
    input[type="password"] {
    width: 95%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    }

    button ,
    input[type="submit"]{
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    border: none;
    color: white;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    }

    button:hover {
    background-color: #45a049;
    }
</style>
<body>
  <div class="container">
    <h1>Mang Inasal Website Account</h1>
    <div class="account-info">
    <form method="POST" action="mimoaUpdateProf.php">
        <input type="hidden" id="uID" name="uID" value="<?php echo $uID; ?>">
        <div class="form-group">
            <label for="ID">ID:</label>
            <input type="text" id="ID" name="ID" value="<?php echo $row["USER_ID"]?>" disabled>
        </div>
        <div class="form-group">
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" value="<?php echo $row["USER_FNAME"]?>" required>
        </div>
        <div class="form-group">
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" value="<?php echo $row["USER_LNAME"]?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $row["USER_EMAIL"]?>" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" value="<?php echo $row["USER_PHONE"]?>" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="<?php echo $row["USER_PASS"]?>" required>
        </div>
        <input type="submit" name="update" value="Update">
    </form><br>
    <form method="POST" action="mimoaHomePage.php">
        <input type="hidden" id="uID" name="uID" value="<?php echo $uID; ?>">
        <input type="submit" name="back" value="Back">
    </form><br>
  </div>

</body>
</html>
