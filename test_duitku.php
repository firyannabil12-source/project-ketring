<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$merchantCode = 'DS30279';
$apiKey = '4006f5bf6cb542395078e02c01c44fce';
$merchantOrderId = 'TEST-' . time();
$paymentAmount = 10000;
$signature = md5($merchantCode . $merchantOrderId . $paymentAmount . $apiKey);

$params = [
    'merchantCode' => $merchantCode,
    'paymentAmount' => $paymentAmount,
    'merchantOrderId' => $merchantOrderId,
    'productDetails' => 'Test',
    'email' => 'test@test.com',
    'phoneNumber' => '08123456789',
    'customerVaName' => 'Test User',
    'callbackUrl' => 'http://example.com/callback',
    'returnUrl' => 'http://example.com/return',
    'signature' => $signature,
    'expiryPeriod' => 10
];

$response = Illuminate\Support\Facades\Http::post('https://api-prod.duitku.com/api/merchant/createInvoice', $params);
echo "Status PROD: " . $response->status() . "\n";
echo "Body: " . $response->body() . "\n";
