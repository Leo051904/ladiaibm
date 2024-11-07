<?php
require "mimoaConx.php";

$payment_id = $_GET['payment_id']; // Payment ID from PayMongo

// PayMongo API URL to check payment status
$paymongo_url = "https://api.paymongo.com/v1/payments/$payment_id";
$secret_key = "sk_test_XpM4eDPCm5H3Xi4FUHXvFwKm"; // Replace with your actual secret key

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $paymongo_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Basic ' . base64_encode("$secret_key:")
]);

// Execute the request and get the response
$response = curl_exec($ch);
curl_close($ch);

$response_data = json_decode($response, true);
if (isset($response_data['data']['attributes']['status']) && $response_data['data']['attributes']['status'] === 'paid') {
    echo "Payment successful!";
} else {
    echo "Payment not completed yet.";
}
?>
