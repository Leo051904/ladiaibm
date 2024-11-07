<?php
session_start();
require "mimoaConx.php";
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['paymethod']) && $user_id) {
    $pay_method = $_POST['paymethod'];

    // Loop through each order to check if payment already exists
    $sqlOrdProd = "SELECT DISTINCT op.ORDER_ID, op.ORDER_TOTALAMOUNT, op.GROUP_ID
                   FROM orderprod op
                   LEFT JOIN payment p ON op.ORDER_ID = p.ORDER_ID
                   WHERE op.USER_ID = '$user_id' AND p.PAY_METHOD IS NULL";
    $resultOrdProd = $connect->query($sqlOrdProd);

    while ($rowOrdProd = $resultOrdProd->fetch_assoc()) {
        $order_id = $rowOrdProd['ORDER_ID'];
        $total_amount = $rowOrdProd['ORDER_TOTALAMOUNT'];
        $group_id = $rowOrdProd['GROUP_ID'];
    
        // Calculate total amount for all orders with the same group ID
        $sqlTotalAmount = "SELECT SUM(ORDER_TOTALAMOUNT) AS total_amount 
                           FROM orderprod 
                           WHERE GROUP_ID = '$group_id'";
        $resultTotalAmount = $connect->query($sqlTotalAmount);
        $rowTotalAmount = $resultTotalAmount->fetch_assoc();
        $total_amount_group = $rowTotalAmount['total_amount'];
    
        // Check if payment already exists for this order
        $sqlCheckPayment = "SELECT * FROM payment WHERE ORDER_ID = '$order_id'";
        $resultCheckPayment = $connect->query($sqlCheckPayment);
    
        if ($resultCheckPayment && $resultCheckPayment->num_rows == 0) {
            // Insert payment record if it doesn't exist
            $sqlPayment = "INSERT INTO payment (ORDER_ID, GROUP_ID, PAY_AMOUNT, PAY_METHOD, PAY_STATUS, PAY_DATETIME) 
                           VALUES ('$order_id', '$group_id', '$total_amount_group', '$pay_method', 2, CURRENT_TIMESTAMP)";
            $resultPayment = $connect->query($sqlPayment);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Receipt</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #5c6961;
        margin: 0;
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }
    .container {
        max-width: 600px;
        margin: 0 auto;
        margin-bottom: 50px;
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    h1, h2, p {
        align-items: flex-end;
    }
    h1 {
        font-size: 25px;
    }

    h2 {
        font-size: 20px;
    }

    .order-details {
        margin-top: 20px;
        margin-bottom: 20px; /* Add margin to the bottom */
        line-height: 1.5; /* Increase line height for better readability */
    }


    .status {
        font-weight: bold;
        color: green;
        text-align: left;
    }
    .button-container {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
    .button {
        padding: 10px 20px;
        margin: 0 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
    }
    .button:hover {
        background-color: #0056b3;
    }
    input[type="submit" i] {
        padding: 10px 20px;
        margin: 0 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        background-color: #dc3545;
        color: #fff;
        text-decoration: none;
    }
    input[type="submit" i]:hover {
        background-color: #0056b3;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #f2f2f2;
    }
    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        text-align: center;
        border: 1px solid #888;
        width: 900px; /* Adjust the width as needed */
        height:750px;
        max-width: 1000px;
        max-height: 1000px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
    }
</style>
</head>
<body>
    <div class="container">
        <h1>Order Receipt</h1>
        <div class="order-details">
            <h2>Order Summary</h2>
            <p><strong>User ID:</strong> <?php echo isset($user_id) ? $user_id : 'N/A'; ?></p>
            <p><strong>Name:</strong> <?php 
                if(isset($user_id)) {
                    $sqlUser = "SELECT * from user WHERE USER_ID = '$user_id'";
                    $resultUser = $connect->query($sqlUser);
                    $rowUser = $resultUser->fetch_assoc();
                    echo isset($rowUser['USER_FNAME']) && isset($rowUser['USER_LNAME']) ? $rowUser['USER_FNAME'] . " " . $rowUser['USER_LNAME'] : 'N/A';
                    // Fetch and display the list of order IDs made by the same user
                    $sqlOrderIDs = "SELECT ORDER_ID, ORDER_STATUS FROM orderprod WHERE USER_ID = '$user_id'";
                    $resultOrderIDs = $connect->query($sqlOrderIDs);
                    if ($resultOrderIDs && $resultOrderIDs->num_rows > 0) {
                        echo "<br><strong>Order IDs:</strong> ";
                        while ($rowOrderID = $resultOrderIDs->fetch_assoc()) {
                            $orderIDs = $rowOrderID['ORDER_STATUS'];
                            if($rowOrderID['ORDER_STATUS'] == 2){
                                echo $rowOrderID['ORDER_ID'] . "";
                            }
                        }
                        echo "<br>";
                    } else {
                        echo "<br><strong>Order IDs:</strong> N/A<br>";
                    }
                } else {
                    echo 'N/A';
                }

                $sqlOrderDetailsStore = "SELECT op.*, s.STORE_BRANCH, s.STORE_ID FROM orderprod op JOIN store s ON op.STORE_ID = s.STORE_ID WHERE op.USER_ID ='$user_id' ORDER BY ORDER_DATETIME DESC LIMIT 1";
                $resultOrderDetailStore = $connect->query($sqlOrderDetailsStore);
                $rowOrderDetailStore = $resultOrderDetailStore->fetch_assoc();

                echo "<p><strong>Store:</strong> " . $rowOrderDetailStore['STORE_BRANCH'] . "</p>";
                echo "<p><strong>Date Time:</strong> " . $rowOrderDetailStore['ORDER_DATETIME'] . "</p>";
                
            ?></p>
            <h3>Items:</h3>
            <?php
                $sqlOrderDetails = "SELECT op.*, p.PROD_NAME, p.PROD_PRICE FROM orderprod op JOIN product p ON op.PROD_ID = p.PROD_ID WHERE op.USER_ID = '$user_id'";
                $resultOrderDetails = $connect->query($sqlOrderDetails);

                // Initialize total amount variable
                $totalAmount = 0;

                // Check if there are any orders
                if ($resultOrderDetails && $resultOrderDetails->num_rows > 0) {
                    echo "<table>";
                    echo "<tr><th>Product</th><th>Quantity</th><th>Price</th><th>Total</th></tr>";
                    while ($rowOrderDetail = $resultOrderDetails->fetch_assoc()) {
                        $total = $rowOrderDetail['ORDER_TOTALAMOUNT'];
                        $prodname = $rowOrderDetail['PROD_NAME'];
                        $prodquanti = $rowOrderDetail['ORDER_QUANTITY'];
                        $prodprice = $rowOrderDetail['PROD_PRICE'];
                        // Add to total amount
                        if($rowOrderDetail['ORDER_STATUS'] == 2){
                            $totalAmount += $total;
                            echo "<tr>";
                            echo "<td>" . $prodname . "</td>";
                            echo "<td>" . $prodquanti . "</td>";
                            echo "<td>₱" . number_format($prodprice, 2) . "</td>";
                            echo "<td>₱" . number_format($total, 2) . "</td>";
                            echo "</tr>";
                        }
                    }
                    echo "</table>";
                } else {
                    echo "<p>No items found.</p>";
                }
            ?>
            <p><strong>Total:</strong> ₱ <?php echo number_format($totalAmount, 2); ?></p>
            <h2>Status: <span class="status">
            <?php 
                // Check if there are pending payments for the user's orders
                $sqlPendingPayments = "SELECT op.ORDER_STATUS
                                    FROM orderprod op
                                    WHERE op.USER_ID = '$user_id'";
                $resultPendingPayments = $connect->query($sqlPendingPayments);

                $pending = false;

                if ($resultPendingPayments && $resultPendingPayments->num_rows > 0) {
                    // If there are pending orders, set the status to "PENDING"
                    while ($rowPending = $resultPendingPayments->fetch_assoc()) {
                        if ($rowPending['ORDER_STATUS'] == 2) {
                            // If any order is pending, set $pending to true and break the loop
                            $pending = true;
                            break;
                        }
                    }
                }

                if ($pending) {
                    echo 'PENDING';
                } else {
                    echo 'COMPLETED';
                }
            ?>
            </span></h2>
        </div>
        <div class="button-container">
            <button class="button" onclick="location.reload()">Refresh Status</button>
            <button class="button" onclick="window.location.href='mimoaCart.php'">Back</button>
            <?php 
                $sqlgrp = "SELECT * FROM payment WHERE PAY_STATUS = 2";
                $resultgrp = $connect->query($sqlgrp);
                $rowGrp = $resultgrp->fetch_assoc();
                $group_id = $rowGrp['GROUP_ID'];
            
            ?>
            <form action="mimoaCancelOrder.php" method="POST">
                <input type="hidden" id = "groupid" name="groupid" value="<?php echo $group_id?>">
                <input type="submit" name="cancel" value="Cancel"/>
            </form>
        </div>
    </div>
    <!-- Modal for completed transaction -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Transaction Completed!</p>
            <button class="button" onclick="returnToHomepage()">Return to Homepage</button>
        </div>
    </div>
    <script>
       // Get the modal
        var modal = document.getElementById("myModal");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // Function to return to homepage
        function returnToHomepage() {
            window.location.href = "mimoaHomePage.php";
        }

        // Close the modal when the user clicks on <span> (x)
        span.onclick = function(event) {
            event.stopPropagation(); // Prevent modal from closing when the span is clicked
            returnToHomepage(); // Redirect to homepage
        };

        // Check if the status is completed and show modal
        var status = document.querySelector('.status').textContent.trim();
        if (status === 'COMPLETED') {
            modal.style.display = "block";
        }
    </script>
</body>
</html>
