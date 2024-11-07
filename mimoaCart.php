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
  <title>Cart</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #5c6961;
    }

    .container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #ccc;
      background-color: #C1E1C1;
      margin-top: 100px;
    }

    .container h1 {
  color: #50C878;
  text-align: center;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); /* Adds a shadow to the text */
  -webkit-text-stroke: 1px black; /* Adds an outline to the text */
  -webkit-text-fill-color: #50C878; /* Ensures the text color is applied */
}

    .cart-item {
      border: 1px solid #ccc;
      border-radius: 5px;
      padding: 20px;
      margin-bottom: 20px;
      background-color: #fff;
      padding-bottom: 45px;
    }

    .cart-item img {
      max-width: 80px;
      height: auto;
      border-radius: 5px;
      float: left;
      margin-right: 20px;
    }

    .cart-item h2 {
      color: #7D6608;
      text-shadow: 4px 4px 6px gray;
    }

    .cart-item p {
      font-weight: bold;
      color: #5D6D7E;
      font-size: 18px;
      margin-top: 0;
    }

    .cart-total {
      color: black;
      text-shadow: 4px 4px 6px gray;
    }

    .quantity {
      display: flex; /* Use flexbox for layout */
      align-items: center; /* Align items vertically in the center */
    }

    .quantity input[type="submit"] {
      background-color: #4CAF50;
      border: none;
      color: white;
      padding: 5px 10px;
      text-align: center;
      text-decoration: none;
      font-size: 16px;
      cursor: pointer;
      border-radius: 3px;
    }

    .quantity input {
      width: 30px;
      text-align: center;
      margin-right: 5px; /* Add some spacing between input and buttons */
    }
    .quantity input:hover {
      background-color: #3f8a42;
    }

    .total {
      font-weight: bold;
      float: right;
    }
    

    .checkout-button {
      background-color: #4CAF50;
      border: none;
      color: white;
      padding: 10px 20px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      margin-top: 10px;
      cursor: pointer;
      border-radius: 5px;
      float: right;
      margin-right: 10px;
    }

    .checkout-button:hover {
      background-color: #3f8a42;
    }

    .return-button {
      background-color: #f44336;
      border: none;
      color: white;
      padding: 10px 20px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      margin-top: 10px;
      cursor: pointer;
      border-radius: 5px;
      float: right;
    }
    .returnOrder-page {
  display: inline-block;
  background-color: #f44336;
  color: white;
  padding: 10px 20px;
  text-align: center;
  text-decoration: none;
  font-size: 16px;
  margin-top: 10px;
  cursor: pointer;
  border-radius: 5px;
  float: right;
  margin-right: 20px;

}

.returnOrder-page:hover {
  background-color: #d32f2f; /* Darken the background color on hover */
}

.remove-button {
  display: inline-block;
  background-color: #f44336;
  color: white;
  padding: 10px 20px;
  text-align: center;
  text-decoration: none;
  font-size: 16px;
  margin-top: 10px;
  cursor: pointer;
  border-radius: 5px;
  float: right;
  margin-top: 0px;
}

.remove-button:hover {
  background-color: #d32f2f; /* Darken the background color on hover */
}


    .modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.4);
}

.modal-content {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 40%;
}

/* Close button style */
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px; /* Add margin between the table and other content */
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px; /* Add padding to table cells */
        text-align: left;
    }

    th {
        background-color: #f2f2f2; /* Add background color to table header */
    }
    


  </style>
</head>
<body>
<div class="container">
    <h1>Your Cart</h1>
    <?php
        $sqlUser = "SELECT * FROM user WHERE USER_ID = '$user_id'";
        $resultUser = $connect->query($sqlUser);
        $rowUser = $resultUser->fetch_assoc();

        $sqlOrdProd = "SELECT op.*, p.* FROM orderprod op JOIN product p ON op.PROD_ID = p.PROD_ID WHERE op.USER_ID = '$user_id' AND op.ORDER_STATUS = 2";
        $resultOrdProd = $connect->query($sqlOrdProd);

        $totalAmount = 0;

        // Display product details dynamically
        while ($rowOrdProd = $resultOrdProd->fetch_assoc()) {
            $prod_id = $rowOrdProd['PROD_ID'];
            $sqlProd = "SELECT * FROM product WHERE PROD_ID = '$prod_id'";
            $resultProduct = $connect->query($sqlProd);
            $rowProduct = $resultProduct->fetch_assoc();
          

            echo "<div class='cart-item'>";
            echo "<img src='img" . $rowProduct['PROD_ID'] . ".png'>";
            echo "<h2>" . $rowProduct['PROD_NAME'] . "</h2>";
            echo "<p>₱" . number_format($rowProduct['PROD_PRICE'], 2) . "</p>";
            echo "<div class='quantity'>";
            echo "<form method='POST' action='mimoaQuantiUp.php'>";
            echo "<input type='hidden' id='user_id' name='user_id' value='<?php echo $user_id; ?>'>";
            echo "<input type='hidden' id='prod' name='prod' value='" . $rowProduct['PROD_ID'] . "'>";
            if ($rowOrdProd['ORDER_QUANTITY'] > 1) { 
              echo "<input type='hidden' id='quantiUp' name='quantiUp' value='minus'>";
              echo "<input type ='submit' name='minus' value='-'/> <br>";
            } else {
                echo "<input type='submit' name='minus' value='-' disabled>"; 
            }
            echo "</form>";

            echo "<input type='text' id='quantity' value='" . $rowOrdProd['ORDER_QUANTITY'] . "' readonly>";

            echo "<form method='POST' action='mimoaQuantiUp.php'>";
            echo "<input type='hidden' id='user_id' name='user_id' value='<?php echo $user_id; ?>'>";
            echo "<input type='hidden' id='prod' name='prod' value='" . $rowOrdProd['PROD_ID'] . "'>";
            echo "<input type='hidden' id='quantiUp' name='quantiUp' value='add'>";
            echo "<input type ='submit' name='add' value='+'/>";
            echo "</form>";    
            echo "</div>"; // Close quantity div
            echo "<p>Total: ₱" . number_format($rowOrdProd['ORDER_TOTALAMOUNT'], 2) . "</p>";
            echo "<br><form method='POST' action='mimoaRemoveFromCart.php'>"; // Form for removing the product from cart
            echo "<input type='hidden' id='ord_status' name='ord_status' value='" . $rowOrdProd['ORDER_STATUS'] . "'>";
            echo "<input type='hidden' name='prod_id' value='" . $rowOrdProd['PROD_ID'] . "'>"; // Hidden input for product ID
            echo "<input type='submit' class='remove-button' value='Remove from Cart'>"; // Remove from cart button
            echo "</form>";
            echo "</div>"; // Close product div
            $totalAmount += $rowProduct['PROD_PRICE'] * $rowOrdProd['ORDER_QUANTITY'];

        }
    ?>
        <div class='cart-total'><h2>Total Amount: ₱<?php echo number_format($totalAmount , 2) ?></h2></div>
        <?php
        $sqlCartItems = "SELECT COUNT(*) AS cartItems FROM orderprod WHERE USER_ID = '$user_id' AND ORDER_STATUS = '2'";
        $resultCartItems = $connect->query($sqlCartItems);
        $rowCartItems = $resultCartItems->fetch_assoc();
        $cartItemsCount = $rowCartItems['cartItems'];

        $checkOutDisabled = ($cartItemsCount == 0) ? "disabled" : "";
          echo "<button class='checkout-button' onclick='openModal()'" . $checkOutDisabled . ">Checkout</button>'";
        ?>
        <a href="mimoaOrder.php" class='returnOrder-page'>Return to Order Page</a>
        </div>
        <div id="myModal" class="modal">
          <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Checkout</h2>
            <p>Do you want to proceed with the checkout?</p>
            <?php
            // Query to get order details
            $sqlOrderDetails = "SELECT op.*, p.PROD_NAME, p.PROD_PRICE FROM orderprod op JOIN product p ON op.PROD_ID = p.PROD_ID WHERE op.USER_ID = '$user_id'";
            $resultOrderDetails = $connect->query($sqlOrderDetails);

            // Initialize total amount variable
            $totalAmount = 0;

            // Check if there are any orders
              if ($resultOrderDetails->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Product</th><th>Quantity</th><th>Price</th><th>Total</th></tr>";
                while ($rowOrderDetail = $resultOrderDetails->fetch_assoc()) {
                    $orderStatus = $rowOrderDetail['ORDER_STATUS'];
                    if ($orderStatus == 2) {
                        $total = $rowOrderDetail['ORDER_TOTALAMOUNT'];
                        $totalAmount += $total; // Add to total amount
                        echo "<tr>";
                        echo "<td>" . $rowOrderDetail['PROD_NAME'] . "</td>";
                        echo "<td>" . $rowOrderDetail['ORDER_QUANTITY'] . "</td>";
                        echo "<td>₱" . number_format($rowOrderDetail['PROD_PRICE'], 2) . "</td>";
                        echo "<td>₱" . number_format($total, 2) . "</td>";
                        echo "</tr>";
                    }
                }
                echo "</table>";
            } else {
                echo "No items in the cart.";
            }
            ?>
            <!-- Display total amount -->
            <p>Total Amount: ₱<?php echo number_format($totalAmount, 2); ?></p>
            <!-- Add form or button for actual checkout process here -->
            <form method="POST" action="page_payment2.php">
              <input type='submit' class='payment-button' value='Proceed To Payment'>
            </form>
          </div>
        </div>
        <script>
            // Function to open the modal
            function openModal() {
                document.getElementById("myModal").style.display = "block";
            }

            // Function to close the modal
            function closeModal() {
                document.getElementById("myModal").style.display = "none";
            }

            // Close the modal if user clicks outside of it
            window.onclick = function(event) {
                var modal = document.getElementById("myModal");
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        </script>
</body>
</html>