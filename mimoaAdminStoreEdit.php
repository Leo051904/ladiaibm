<?php
    session_start();
    require "mimoaConx.php";

    if (!isset($_SESSION['user_id'])) {
        header("Location: mimoaLog.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $storeid = $_POST['storeid'];

    $sql = "SELECT * FROM store WHERE STORE_ID = '$storeid'";
    $result = $connect->query($sql);
    $row = $result->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Store Information</title>
    <style>
         body {
            background-color: #5c6961;
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        .container {
            background-color: white;
            max-width: 450px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form {
            max-width: 100%;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"] {
            width: calc(100% - 16px);
            padding: 6px;
            margin-bottom: 10px;
        }
        button[type="submit"] {
            padding: 8px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            display: block;
            margin-top: 10px;
            margin-left: auto;
            margin-right: auto;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            text-align: center;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            line-height: 1; /* Center the text vertically */
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Edit Store Information</h1>
    <form method="POST" action="mimoaAdminEditStoreUpdate.php">
        <label for="storeid">Store ID:</label>
        <input type="text" id="storeid" name="storeid" value = "<?php echo $row['STORE_ID']?>" readonly>

        <label for="storebranch">Store Branch:</label>
        <input type="text" id="storebranch" name="storebranch" value = "<?php echo $row['STORE_BRANCH']?>"  required>

        <label for="storecity">Store City:</label>
        <input type="text" id="storecity" name="storecity" value = "<?php echo $row['STORE_CITY']?>"  required>

        <label for="storeprov">Store Province:</label>
        <input type="text" id="storeprov" name="storeprov" value = "<?php echo $row['STORE_PROVINCE']?>"  required>

        <label for="storephone">Store Phone:</label>
        <input type="text" id="storephone" name="storephone" value = "<?php echo $row['STORE_PHONE']?>"  required>

        <input type="submit" name="submit" value ="Update">
    </form>
    <a href="mimoaViewStore.php" class="back-button">Back</a>
    </div>
</body>
</html>