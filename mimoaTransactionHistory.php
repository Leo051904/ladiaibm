<?php
    session_start();
    require "mimoaConx.php";

    if (!isset($_SESSION['user_id'])) {
        header("Location: mimoaLog.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // Fetch order information along with product details
    $sqlOrderInfo = "SELECT 
                        op.GROUP_ID,
                        GROUP_CONCAT(op.ORDER_ID SEPARATOR '<br>') AS order_ids,
                        GROUP_CONCAT(p.PROD_ID SEPARATOR '<br>') AS prod_ids,
                        GROUP_CONCAT(p.PROD_NAME SEPARATOR '<br>') AS prod_names,
                        IF(COUNT(DISTINCT op.ORDER_ID) > 1, GROUP_CONCAT(op.ORDER_QUANTITY SEPARATOR '<br>'), SUM(op.ORDER_QUANTITY)) AS ORDER_QUANTITY,
                        GROUP_CONCAT(CONCAT(p.PROD_PRICE) SEPARATOR '<br>') AS PROD_PRICE,
                        CONCAT(SUM(op.ORDER_QUANTITY * p.PROD_PRICE)) AS total_amount,
                        MAX(op.ORDER_DATETIME) AS ORDER_DATETIME,
                        op.STORE_ID,
                        s.STORE_BRANCH  
                    FROM 
                        orderprod op
                    INNER JOIN 
                        product p ON op.PROD_ID = p.PROD_ID
                    INNER JOIN
                        store s ON op.STORE_ID = s.STORE_ID  
                    WHERE 
                        op.USER_ID = '$user_id' AND op.ORDER_STATUS = 1
                    GROUP BY 
                        op.GROUP_ID";


    $resultOrderInfo = $connect->query($sqlOrderInfo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Order History</title>
<style>
    body {
        margin: 30px;
        background-color: #5c6961;
    }
    h1 {
        color: white;
        text-shadow:
            -1px -1px 0 #000,  
            1px -1px 0 #000,
            -1px  1px 0 #000,
            1px  1px 0 #000;
    }
    table {
        background-color: white;
        width: 90%;
        border-collapse: collapse;
    }
    th, td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #f2f2f2;
    }
    .back-button {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 8px 12px;
        border-radius: 4px;
        cursor: pointer;
    }
    .back-button:hover {
        background-color: #0056b3;
    }
</style>
</head>
<body>
    <h1>User Order History</h1>
    <button class="back-button" onclick="window.location.href = 'mimoaOrder.php';">Back</button>
    <table>
        <tr>
            <th>Group ID</th>
            
            <th>Product Names</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total Amount</th>
            <th>Store ID</th>
            <th>Store Branch</th>
            <th>Date & Time</th>
            
        </tr>
        <?php
        // Loop through each order record
        while ($rowOrderInfo = $resultOrderInfo->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $rowOrderInfo['GROUP_ID'] . "</td>";
            
            echo "<td>" . $rowOrderInfo['prod_names'] . "</td>";
            echo "<td>" . $rowOrderInfo['ORDER_QUANTITY'] . "</td>";
            echo "<td>₱" . $rowOrderInfo['PROD_PRICE'] . "</td>";
            echo "<td>₱" . $rowOrderInfo['total_amount'] . "</td>";
            echo "<td>" . $rowOrderInfo['STORE_ID'] . "</td>"; 
            echo "<td>" . $rowOrderInfo['STORE_BRANCH'] . "</td>";
            echo "<td>" . $rowOrderInfo['ORDER_DATETIME'] . "</td>";
             
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>
