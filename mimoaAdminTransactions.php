<?php
session_start();
require "mimoaConx.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: mimoaLog.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch payment information along with product names, total amount, store_id, and store_branch
$sqlPaymentInfo = "SELECT 
                        pm.PAY_ID, 
                        pm.ORDER_ID, 
                        GROUP_CONCAT(op.ORDER_QUANTITY SEPARATOR '<br>') AS product_quantity,
                        SUM(p.PROD_PRICE * op.ORDER_QUANTITY) AS total_amount,
                        pm.PAY_METHOD, 
                        pm.PAY_STATUS, 
                        pm.PAY_DATETIME,
                        u.USER_ID, 
                        u.USER_FNAME, 
                        u.USER_LNAME,
                        op.GROUP_ID, 
                        op.ORDER_ID as order_id, 
                        GROUP_CONCAT(p.PROD_NAME SEPARATOR '<br>') AS product_names,
                        op.STORE_ID,
                        s.STORE_BRANCH
                    FROM payment pm
                    INNER JOIN orderprod op ON pm.ORDER_ID = op.ORDER_ID
                    INNER JOIN user u ON op.USER_ID = u.USER_ID
                    INNER JOIN product p ON op.PROD_ID = p.PROD_ID
                    INNER JOIN store s ON op.STORE_ID = s.STORE_ID
                    GROUP BY op.GROUP_ID";
$resultPaymentInfo = $connect->query($sqlPaymentInfo);

// Group orders by their group ID
$groupedOrders = [];
while ($rowPaymentInfo = $resultPaymentInfo->fetch_assoc()) {
    $group_id = $rowPaymentInfo['GROUP_ID'];
    if (!isset($groupedOrders[$group_id])) {
        $groupedOrders[$group_id] = [];
    }
    $groupedOrders[$group_id][] = $rowPaymentInfo;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Orders & Payment Information</title>
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
        background-color: #fff;
        width: 100%;
        border-collapse: collapse;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    th, td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #f2f2f2;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    tr:hover {
        background-color: #ddd;
    }
    .button-container {
        margin-top: 20px;
        margin-bottom: 20px;
    }
    .button {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
    }
    .button[name="mark_pending"] {
        background-color: green;
        color: white;
    }
    .button[name="mark_completed"] {
        background-color: orange;
        color: white;
    }
    .button:hover {
        background-color: #0056b3;
    }
</style>
</head>
<body>
    <h1>User Orders & Payment Information</h1>
    <div class="button-container">
        <a class="button" href="mimoaAdminpage.php">Back to Admin Page</a>
        <button class="button" onclick="location.reload();" name="refresh">Refresh</button>
    </div>
    <table>
        <tr>
            <th>Payment ID</th>
            <th>User ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Group ID</th>
            <th>Product Names</th>
            <th>Quantity</th>
            <th>Amount</th>
            <th>Method</th>
            <th>Status</th>
            <th>Store ID</th>
            <th>Store Branch</th>
            <th>Date Time</th>
            <th>Action</th>
        </tr>
        <?php
        foreach ($groupedOrders as $group_id => $orders) {
            $firstOrder = reset($orders);
            echo "<tr>";
            echo "<td>" . $firstOrder['PAY_ID'] . "</td>";
            echo "<td>" . $firstOrder['USER_ID'] . "</td>";
            echo "<td>" . $firstOrder['USER_FNAME'] . "</td>";
            echo "<td>" . $firstOrder['USER_LNAME'] . "</td>";
            echo "<td>" . $firstOrder['GROUP_ID'] . "</td>";
            echo "<td>" . $firstOrder['product_names'] . "</td>"; // Product names column
            echo "<td>" . $firstOrder['product_quantity'] . "</td>";
            echo "<td>" . $firstOrder['total_amount'] . "</td>"; // Total amount column
            echo "<td>" . ($firstOrder['PAY_METHOD'] == 2 ? 'GCASH' : 'CASH') . "</td>";
            echo "<td>";
            echo $firstOrder['PAY_STATUS'] == 2 ? 'PENDING' : 'COMPLETED';
            echo "</td>";
            echo "<td>" . $firstOrder['STORE_ID'] . "</td>";
            echo "<td>" . $firstOrder['STORE_BRANCH'] . "</td>";
            echo "<td>" . $firstOrder['PAY_DATETIME'] . "</td>";
            
            echo "<td>";
            // Display button based on payment status
            echo "<form action='mimoaUpdatePayStatus.php' method='POST'>";
            echo "<input type='hidden' name='user_id' value='$user_id'>";
            echo "<input type='hidden' name='group_id' value='$group_id'>";
            echo "<input type='hidden' name='order_id' value='" . $firstOrder['order_id'] . "'>";
            echo "<input type='hidden' name='pay_status' value='" . ($firstOrder['PAY_STATUS'] == 2 ? '1' : '2') . "'>";

            $buttonText = $firstOrder['PAY_STATUS'] == 2 ? 'Mark as Completed' : 'Mark as Pending';
            echo "<button class='button' type='submit' name='update_status'>$buttonText</button>";

            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>
