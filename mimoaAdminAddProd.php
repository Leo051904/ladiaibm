<?php
    require "mimoaConx.php";

    $prodname = $_POST['product_name'];
    $prodprice = $_POST['product_price'];
   
    // Insert the product into the database
    $sql_insert = "INSERT INTO product (PROD_ID, PROD_NAME, PROD_PRICE, PROD_AVAIL, PROD_DATETIME) 
               VALUES (NULL, '$prodname', '$prodprice', 1, current_timestamp())";

    if ($connect->query($sql_insert) === TRUE) {
        // Get the last inserted product ID
        $product_id = $connect->insert_id;

        // Rename the uploaded file
        $target_directory = "C:/xampp/htdocs/webFinal2/";
        $imageFileType = strtolower(pathinfo($_FILES["product_image"]["name"], PATHINFO_EXTENSION));
        $new_file_name = "img" . $product_id . "." . $imageFileType;
        $new_file_path = $target_directory . $new_file_name;

        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $new_file_path)) {
            echo "The file ". htmlspecialchars(basename($_FILES["product_image"]["name"])). " has been uploaded and renamed.";
            // Redirect to another page
            header("Location: mimoaAdminProd.php");
            exit();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "Error: " . $sql_insert . "<br>" . $connect->error;
    }

    $connect->close();
?>