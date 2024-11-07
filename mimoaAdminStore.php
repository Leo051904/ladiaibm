<?php 
    session_start();
    require "mimoaConx.php";

    if (!isset($_SESSION['user_id'])) {
        header("Location: mimoaLog.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Management</title>
    <style>
         body {
            background-color: #5c6961;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: white;
            max-width: 600px;
            margin: 20px auto; /* Center the container */
            padding: 20px; /* Add padding inside the container */
            border: 2px solid #ddd; /* Add a border */
            border-radius: 5px; /* Add border radius for rounded corners */
        }
        h1,h3 {
            text-align: center;
        }
        form {
            margin-bottom: 20px;
            max-width: 400px; /* Limiting form width */
            margin-left: auto;
            margin-right: auto;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"] {
            width: calc(100% - 16px); /* Adjusted width */
            padding: 6px; /* Reduced padding */
            margin-bottom: 10px;
        }
        button[type="submit"] {
            padding: 8px 20px; /* Adjusted padding */
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .edit-button {
            padding: 6px 10px; /* Adjusted padding */
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            text-decoration: none; /* Remove default link underline */
        }
        .edit-button:hover {
            background-color: #0056b3;
        }

        .back-button {
            margin: 20px auto; 
            width: 200px;
            display: block;
            text-align: center;
            margin-top: 40px;
            margin-bottom: 0px;
            text-decoration: none;
            background-color: #e93737;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .back-button:hover {
            background-color: #b73329;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Store Management</h1>
    
    <form method="post" action="mimoaAdminAddStore.php">
        <h3>Add a Store</h3>
        <label for="store_branch">Store Branch:</label>
        <input type="text" id="store_branch" name="store_branch" required>
        <br><br>
        <label for="store_city">Store City:</label>
        <input type="text" id="store_city" name="store_city" required>
        <br><br>
        <label for="store_prov">Store Province:</label>
        <input type="text" id="store_prov" name="store_prov" required>
        <br><br>
        <label for="store_phone">Store Phone:</label>
        <input type="text" id="store_phone" name="store_phone" required>
        <br><br>
        <button type="submit" name="submit">Insert Store</button>
    </form>

    <form method="post" action="mimoaViewStore.php">
        <button type="submit" name="submit">View Store</button>
    </form>
    <a href="mimoaAdminPage.php" class="back-button">Back to Homepage</a>
    </div>
    <br><br>
</body>
</html>