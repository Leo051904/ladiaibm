<?php
session_start();
require "mimoaConx.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: mimoaLog.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $updQuant = $_POST['quantiUp'];
    
    $prod_id = $_POST['prod'];
    $groupId = getCurrentGroupId($user_id);
    // Check if the quantity update is for "minus"
    if ($updQuant == "minus") {
        
        // Check if the current quantity is greater than 1 before allowing subtraction
        $sqlCheckQuantity = "SELECT ORDER_QUANTITY FROM orderprod WHERE PROD_ID = '$prod_id' AND USER_ID = '$user_id' AND GROUP_ID = '$groupId' AND ORDER_STATUS = 2";
        $resultCheckQuantity = $connect->query($sqlCheckQuantity);
        $rowCheckQuantity = $resultCheckQuantity->fetch_assoc();

        if ($rowCheckQuantity && $rowCheckQuantity['ORDER_QUANTITY'] > 1) {
            // Prepare and execute SQL to update the quantity and total amount for the specific product
            $sqlUpdateOrdProd = "UPDATE orderprod 
                                 SET ORDER_QUANTITY = ORDER_QUANTITY - 1,
                                     ORDER_TOTALAMOUNT = ORDER_QUANTITY * (SELECT PROD_PRICE FROM product WHERE PROD_ID = '$prod_id' AND ORDER_STATUS = 2')
                                 WHERE PROD_ID = '$prod_id' AND USER_ID = '$user_id'";
            $resultUpdateOrdProd = $connect->query($sqlUpdateOrdProd);

            if ($resultUpdateOrdProd === TRUE) {
                header("Location: mimoaCart.php");
                exit();
            }
        }
    } elseif ($updQuant == "add") {
        // Check if the current quantity is already zero
        $sqlCheckQuantity = "SELECT ORDER_QUANTITY FROM orderprod WHERE PROD_ID = '$prod_id' AND USER_ID = '$user_id' AND GROUP_ID = '$groupId'";
        $resultCheckQuantity = $connect->query($sqlCheckQuantity);
        $rowCheckQuantity = $resultCheckQuantity->fetch_assoc();

        if ($rowCheckQuantity && $rowCheckQuantity['ORDER_QUANTITY'] >= 0) {
            // Prepare and execute SQL to update the quantity and total amount for the specific product
            $sqlUpdateOrdProd = "UPDATE orderprod 
                                 SET ORDER_QUANTITY = ORDER_QUANTITY + 1,
                                     ORDER_TOTALAMOUNT = ORDER_QUANTITY * (SELECT PROD_PRICE FROM product WHERE PROD_ID = '$prod_id' )
                                 WHERE PROD_ID = '$prod_id' AND USER_ID = '$user_id' AND ORDER_STATUS = 2";
            $resultUpdateOrdProd = $connect->query($sqlUpdateOrdProd);

            if ($resultUpdateOrdProd === TRUE) {
                header("Location: mimoaCart.php");
                exit();
            }
        }
    }
}
function getCurrentGroupId($user_id) {
    global $connect;

    $currentGroupIdSql = "SELECT GROUP_ID FROM orderprod WHERE USER_ID = '$user_id' ORDER BY ORDER_DATETIME DESC LIMIT 1";
    $result = $connect->query($currentGroupIdSql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row["GROUP_ID"];
    } else {
        return null; // No orders found
    }
}
?>