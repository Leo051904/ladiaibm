<?php
require "mimoaConx.php";

// Read incoming JSON data from PayMongo
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if ($data['data']['attributes']['status'] === 'paid') {
    $reference_number = $data['data']['attributes']['reference_number']; // Use reference number to match your order

    // Update order status in your database
    $stmt = $connect->prepare("UPDATE orderprod SET ORDER_STATUS = 'paid' WHERE reference_number = ?");
    $stmt->bind_param("s", $reference_number);
    $stmt->execute();

    http_response_code(200); // Respond to PayMongo with a success status
} else {
    http_response_code(400); // Respond with error if status is not paid
}
?>
