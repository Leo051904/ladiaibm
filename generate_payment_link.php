<?php
session_start();
require "mimoaConx.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: mimoaLog.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$totalAmount = 0;

// Calculate the total amount of the items in the cart
$sqlOrdProd = "SELECT op.*, p.* FROM orderprod op JOIN product p ON op.PROD_ID = p.PROD_ID WHERE op.USER_ID = '$user_id' AND op.ORDER_STATUS = 2";
$resultOrdProd = $connect->query($sqlOrdProd);
while ($rowOrdProd = $resultOrdProd->fetch_assoc()) {
    $totalAmount += $rowOrdProd['ORDER_TOTALAMOUNT'];
}

// Set up PayMongo API URL and secret key
$paymongo_url = "https://api.paymongo.com/v1/links";
$secret_key = "sk_test_XpM4eDPCm5H3Xi4FUHXvFwKm"; // Replace with your actual PayMongo secret key

// Prepare data for PayMongo API request
$data = [
    'data' => [
        'attributes' => [
            'amount' => $totalAmount * 100, // Convert PHP to centavos
            'description' => "Order payment for user ID $user_id",
            'remarks' => "Order payment for user $user_id",
            'redirect' => [
                'success' => "http://yourwebsite.com/success.php", // Replace with your success page URL
                'failed' => "http://yourwebsite.com/failed.php"    // Replace with your failed page URL
            ]
        ]
    ]
];

// Initialize cURL for PayMongo API request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $paymongo_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Basic ' . base64_encode("$secret_key:")
]);

// Execute the request and get the response
$response = curl_exec($ch);
curl_close($ch);

$response_data = json_decode($response, true);

// Check if the payment link was successfully created
if (isset($response_data['data']['attributes']['checkout_url'])) {
    $payment_link = $response_data['data']['attributes']['checkout_url'];
    header("Location: $payment_link"); // Redirect to PayMongo's checkout page
    exit;
} else {
    echo "Failed to create payment link. Please try again.";
}
?>
