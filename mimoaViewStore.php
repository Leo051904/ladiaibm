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
    <title>Product Table</title>
    <style>
         body {
            background-color: #5c6961;
            margin: 20px;
        }
        table {
            background-color: white;
            border-collapse: collapse;
            width: 100%;
        }
        h1 {
            color: white;
            text-shadow:
                -1px -1px 0 #000,  
                1px -1px 0 #000,
                -1px 1px 0 #000,
                1px 1px 0 #000;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .edit-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 6px 12px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            cursor: pointer;
            border-radius: 4px;
        }
        .edit-button:hover {
            background-color: #0056b3;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<h1>Store</h1>
<table border="1">
    <thead>
        <tr>
            <th>Store ID</th>
            <th>Store Branch</th>
            <th>Store City</th>
            <th>Store Province</th>
            <th>Store Phone</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <!-- PHP code to fetch data from database and display in table -->
        <?php
        // Connect to your database (assuming $connect is already defined)
        $sql = "SELECT * FROM store";
        $result = mysqli_query($connect, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $store_id = $row['STORE_ID'];
           
            echo "<tr>";
            echo "<td>" . $row['STORE_ID'] . "</td>";
            echo "<td>" . $row['STORE_BRANCH'] . "</td>";
            echo "<td>" . $row['STORE_CITY'] . "</td>";
            echo "<td>" . $row['STORE_PROVINCE'] . "</td>";
            echo "<td>" . $row['STORE_PHONE'] . "</td>";
            echo "<td>"; 
            echo "<form method='POST' action='mimoaAdminStoreEdit.php'>";
            echo "<input type='hidden' id='storeid' name='storeid' value='$store_id'>";
            echo "<input type='submit' name='edit-button' value='EDIT'>";
            echo "</form>";
            echo "<form method='POST' action='mimoaDeleteStore.php' onsubmit='return confirm(\"Are you sure you want to delete this store?\")'>";
            echo "<input type='hidden' id='storeid' name='storeid' value='$store_id'>";
            echo "<input type='submit' name='delete-button' value='DELETE'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
            
        }
        ?>
    </tbody>
</table>
<a href="mimoaAdminStore.php" class="back-button">Back</a>
</body>
</html>
