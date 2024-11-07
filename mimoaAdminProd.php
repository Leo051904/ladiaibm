<?php 
    session_start();
    require "mimoaConx.php";

    if (!isset($_SESSION['user_id'])) {
        header("Location: mimoaLog.php");
        exit();
    }

    if(isset($_POST['submit'])) {
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];

        // File upload handling
        $target_directory = "C:/xampp/htdocs/webFinal2/"; // Specify the directory where you want to store the uploaded files
        $target_file = $target_directory . basename($_FILES["product_image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["product_image"]["tmp_name"]);
        if($check !== false) {
           // echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            //echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
           // echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["product_image"]["size"] > 50000000000) {
            //echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            //echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            // Insert the product into the database
            $sqlInsertProduct = "INSERT INTO product (PROD_NAME, PROD_PRICE, PROD_AVAIL,PROD_DATETIME) VALUES ('$product_name', '$product_price', 1,current_timestamp())";
            if ($connect->query($sqlInsertProduct) === TRUE) {
                $product_id = $connect->insert_id; // Get the last inserted product ID

                // Rename file as img + product ID
                $new_file_name = "img" . $product_id . "." . $imageFileType;
                //echo $new_file_name;
                if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_directory . $new_file_name)) {
                    //echo "The file ". htmlspecialchars( basename( $_FILES["product_image"]["name"])). " has been uploaded and renamed.";
                } else {
                    //echo "Sorry, there was an error uploading your file.";
                }
            } else {
                //echo "Error inserting product: " . $connect->error;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
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
    <h1>Product Management</h1>
    
    <form method="post" enctype="multipart/form-data">
        <h3>Add a Product</h3>
        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" required>
        <br><br>
        <label for="product_price">Product Price:</label>
        <input type="text" id="product_price" name="product_price" required>
        <br><br>
        <label for="product_image">Product Image:</label>
        <input type="file" id="product_image" name="product_image" accept="image/*" required>
        <br><br>
        <button type="submit" name="submit">Insert Product</button>
    </form>

    <form method="post" action="mimoaViewProducts.php">
        <button type="submit" name="submit">View Products</button>
    </form>
    <a href="mimoaAdminPage.php" class="back-button">Back to Homepage</a>
    <br><br>
    </div>
</body>
</html>
