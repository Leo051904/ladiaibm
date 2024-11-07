<?php
    session_start();
    require "mimoaConx.php";
    
    $user_id = $_SESSION['user_id'];

    $sqlProd = "SELECT * FROM product";
    $resultProduct = $connect->query($sqlProd);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Product Availability</title>
<style>
    body {
            background-color: #5c6961;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .product-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white; /* Set background color to white */
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 1px;
        }
        h2 {
            color:white;
            text-align: center;
            margin-bottom: 20px;
            text-shadow:
            -1px -1px 0 #000,  
            1px -1px 0 #000,
            -1px  1px 0 #000,
            1px  1px 0 #000;
        }
        .product-container {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }
        .product-name {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .buttons {
            display: flex;
            justify-content: space-between;
        }
        .button {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); /* Add shadow to the button */
        }

        .button:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Add shadow on hover */
        }
        .button-available {
            background-color: #4CAF50;
            color: white;
        }
        .button-available:hover {
            background-color: #469a49;
        }
        .button-unavailable {
            background-color: #f44336;
            color: white;
        }
        .button-unavailable:hover {
            background-color: #b73329;
        }

        .back-button {
            margin: 20px auto; 
            width: 200px;
            display: block;
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
            text-decoration: none;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
        }
</style>
</head>
<body>
    <div class="container">
        <h2>Product Availability</h2>
        <a href="mimoaAdminPage.php" class="back-button">Back to Admin Page</a>
        <?php
            if ($resultProduct->num_rows > 0) {
                while ($rowProduct = $resultProduct->fetch_assoc()) {
                    $product_id = $rowProduct['PROD_ID'];
                    $product_name = $rowProduct['PROD_NAME'];
                    $available = $rowProduct['PROD_AVAIL'];
                  
                    echo "<div class='product-container'>";
                    echo "<img src='img" . $rowProduct['PROD_ID'] . ".png'>";
                    echo "<p class='product-name'>$product_name</p>";
                    echo "<div class='buttons'>";
                    echo "<form action='mimoaUpdateProdAvail.php' method='post'>";
                    echo "<input type='hidden' name='product_id' value='$product_id'>";
                    echo "<button class='button button-available' type='submit' name='availability' value='1'";
                    if ($available == 1) echo " disabled";
                    echo ">" . ($available == 1 ? 'Available' : 'Set Available') . "</button>";;
                    echo "</form>";
                    echo "<form action='mimoaUpdateProdAvail.php' method='post'>";
                    echo "<input type='hidden' name='product_id' value='$product_id'>";
                    echo "<button class='button button-unavailable' type='submit' name='availability' value='0'";
                    if ($available == 0) echo " disabled";
                    echo ">" . ($available == 0 ? 'Unavailable' : 'Set Unavailable') . "</button>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "No products found.";
            }
        ?>
        
    </div>
</body>
</html>