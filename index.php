<?php declare(strict_types=1);

use paytm\paytmchecksum\PaytmChecksum;

require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// A random order ID, e.g. 15415
$orderId = time() % 100000;

$merchantId = $_SERVER['PAYTM_MERCHANT_ID'];
$merchantKey = $_SERVER['PAYTM_MERCHANT_KEY'];

$paytmParams = [];
$paytmParams['body'] = [
    'mid' => $merchantId,
    'orderId' => $orderId,
    'requestType' => 'Payment',
    'websiteName' => 'Interaction Design Foundation Inc',
    'callbackUrl' => "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}",
    'txnAmount' => [
        'value' => '100.00',
        'currency' => 'INR',
    ],
];

$checksum = PaytmChecksum::generateSignature(
    json_encode($paytmParams['body'], \JSON_THROW_ON_ERROR | \JSON_UNESCAPED_SLASHES),
    $merchantKey
);

$paytmParams['head'] = [
    'signature' => $checksum
];

$url = sprintf('%s/initiateTransaction?mid=%s&orderId=%s', $_SERVER['PAYTM_API_ENDPOINT'], $merchantId, $orderId);

$postData = json_encode($paytmParams, \JSON_THROW_ON_ERROR | \JSON_UNESCAPED_SLASHES);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-Type: application/json'));
$response = curl_exec($ch);

dump(json_decode($response, true));
