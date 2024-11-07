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

  // Fetch store data
  $sqlStore = "SELECT * FROM store";
  $resultStore = $connect->query($sqlStore);

  // Fetch product data
  $sqlProd = "SELECT * FROM product";
  $resultProduct = $connect->query($sqlProd);

  $sqlTotalItems = "SELECT COUNT(*) AS totalItems FROM product";
  $resultTotalItems = $connect->query($sqlTotalItems);
  $rowTotalItems = $resultTotalItems->fetch_assoc();
  $totalItems = $rowTotalItems['totalItems'];

  $sqlCartItems = "SELECT COUNT(*) AS cartItems FROM orderprod WHERE USER_ID = '$user_id' AND ORDER_STATUS = '2'";
  $resultCartItems = $connect->query($sqlCartItems);
  $rowCartItems = $resultCartItems->fetch_assoc();
  $cartItemsCount = $rowCartItems['cartItems'];

  // If the cart is empty, set a variable to disable the "View Cart" button
  $viewCartDisabled = ($cartItemsCount == 0) ? "disabled" : "";

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MANG INASAL Menu</title>
  <link rel="stylesheet" href="styles.css">
</head>
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
    }

    .container h1
    {
        font-family: 'Copperplate';
		    font-weight: bold;		
        color: #50C878;
        text-shadow: 2px 2px 4px black;
    }

    .product {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 20px;
    margin-bottom: 20px;
    background-color: #C1E1C1;
    }

    .product img {
    max-width: 50%;
    height: auto;
    border-radius: 5px;
    }

    .product h2 {
    margin-top: 10px;
    color: #7D6608;
    text-shadow: 4px 4px 6px gray;
    }

    .product p {
    font-weight: bold;
    color: #5D6D7E;
    font-size: 20px;
    }

    .input[name="addToCart"]{
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
}

    .input[name="addToCart"]:hover {
    background-color: #45a049;
}

/* Styling for the store selection */
.store-selection {
  margin-bottom: 20px;
  color: #50C878;
  text-shadow: 4px 4px 6px black;
}

.store-selection select {
  padding: 5px 10px;
  font-size: 16px;
  border-radius: 5px;
  border: 1px solid #ccc;
  background-color: #fff;
  color: #5D6D7E;
  margin-left: 15px;
}
    button[type="submit"] {
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
    }

    button[type="submit"]  {
    background-color: #45a049;
    }

    button[type="submit"]:hover , button[onclick]:hover{
    background-color: #98d69b;
    }

    button[type="button"] {
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
    }

    button[type="button"]  {
    background-color: #800020;
    }

    button[type="button"]:hover {
    background-color: #EE4B2B;
    }

    input[name="return"]{
    background-color: #800020;
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
    }

    input[name="return"]:hover {
    background-color: #EE4B2B;
    }
    .quantity button {
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
    }
</style>
<body>
  <div class="container">
    <h1>Mang Inasal Menu</h1>
    <h3 class="store-selection">Choose a Store: 
    <select name="store">
        <?php
          $sqlOrderProdLatest = "SELECT * FROM orderprod WHERE USER_ID = '$user_id' AND ORDER_STATUS = 2 ORDER BY ORDER_DATETIME DESC LIMIT $totalItems";
          $resultOrderProdLatest = $connect->query($sqlOrderProdLatest);

          if($rowOrderProdLatest = $resultOrderProdLatest->fetch_assoc()) {
            $selectedStoreId = $rowOrderProdLatest['STORE_ID'];
          }else{
            $selectedStoreId = 1;
          }
           // Set the ID of the store you want to pre-select
          while ($rowStore = $resultStore->fetch_assoc()) {
              $storeId = $rowStore['STORE_ID'];
              $selected = ($selectedStoreId == $storeId) ? "selected" : ""; // Check if this option should be selected
              echo "<option value='" . $storeId . "' $selected>" . $rowStore['STORE_BRANCH'] . "</option>";
          }
        ?>
    </select>
      <form method="POST" action="mimoaTransactionHistory.php">
          <input type="hidden" id="uID" name="uID" value="<?php echo $user_id; ?>">
          <button type="submit" name="transactionHistory">Transaction History</button>
      </form>
    </h3>
    <?php
        // Display product details dynamically
        while ($rowProduct = $resultProduct->fetch_assoc()) {

          $sqlOrderProd = "SELECT * FROM orderprod WHERE PROD_ID = '{$rowProduct['PROD_ID']}' AND USER_ID = '$user_id'";
          $resultOrderProduct = $connect->query($sqlOrderProd);
          $rowOrderProd = $resultOrderProduct->fetch_assoc();

          $sqlOrderProdLatest = "SELECT * FROM orderprod WHERE USER_ID = '$user_id' AND ORDER_STATUS = 2 ORDER BY ORDER_DATETIME DESC LIMIT $totalItems";
          $resultOrderProdLatest = $connect->query($sqlOrderProdLatest);
          
          $productAlreadyInCart = false;


          while ($rowOrderProdLatest = $resultOrderProdLatest->fetch_assoc()) {
              if($rowOrderProdLatest == null){
                $productAlreadyInCart = false;
              }
              else if ($rowOrderProdLatest != null &&$rowOrderProdLatest['PROD_ID'] == $rowProduct['PROD_ID']) {
                  $productAlreadyInCart = true;
                  break; // No need to continue checking if the product is found in one of the latest 3 orders
              }
          }
          
         
            echo "<div class='product'>";
            echo "<img src='img" . $rowProduct['PROD_ID'] . ".png'>";
            echo "<h2>" . $rowProduct['PROD_NAME'] . "</h2>";
            echo "<p>₱" . $rowProduct['PROD_PRICE'] . "</p>";
            echo "<form method='POST' action='mimoaAddToCart.php' onsubmit='updateQuantity(\"" . $rowProduct['PROD_NAME'] . "\", \"" . $rowProduct['PROD_ID'] . "\")'>";
            if ($productAlreadyInCart && $rowOrderProd != null) {
              echo "<button type='button' disabled>Already in Cart</button>";
            } else {
              if($rowProduct['PROD_AVAIL'] == 1){
                echo "<input type='hidden' id='uID' name='uID' value='" . $user_id . "'>";
                echo "<input type='hidden' name='product' value='" . $rowProduct['PROD_NAME'] . "'>";
                echo "<input type='hidden' id='" . $rowProduct['PROD_ID'] . "HiddenQuantity' name='quantity' value='1'>";
                echo "<input type='hidden' id='" . $rowProduct['PROD_ID'] . "SelectedStore' name='selectedStore' value=''>";
                echo "<button type='submit' name='addToCart'>Add to Cart</button>";
              }else{
                echo "<button type='button' disabled>Not Available</button>";
              }
            }
          

          echo "</form>";
          echo "</div>";
      }
      function getFileExtension($filename) {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if ($ext == "jpg" || $ext == "jpeg" || $ext == "gif" || $ext == "bmp") {
            return "." . $ext;
        }
        return ".png"; // default to PNG if the extension is not recognized
    }
?>
  <form method="POST" action="mimoaCart.php">
        <input type="hidden" id="uID" name="uID" value="<?php echo $user_id; ?>">
        <button type="submit" name="cart" <?php echo $viewCartDisabled; ?>>View Cart</button>
    </form>

    <form method="POST" action="mimoaHomePage.php">
        <input type="hidden" id="uID" name="uID" value="<?php echo $user_id; ?>">
        <input type="submit" name="return" value="Return">
    </form><br>

    

  </div>
  <script>
    function changeQuantity(button, value, productId) {
      var input = button.parentNode.querySelector('input[type="text"]');
      var currentValue = parseInt(input.value) || 0;
      var newValue = currentValue + value;
      if (newValue < 1) 
          return;
      input.value = newValue;

      var hiddenInput = document.getElementById(productId + 'HiddenQuantity');
      if(hiddenInput) hiddenInput.value = newValue;
    }

    function updateQuantity(productName, productId) {
      var selectedStore = document.querySelector('.store-selection select').value;
      var selectedStoreInput = document.getElementById(productId + 'SelectedStore');
      if(selectedStoreInput) selectedStoreInput.value = selectedStore;
    }
  </script>
</body>
</html>