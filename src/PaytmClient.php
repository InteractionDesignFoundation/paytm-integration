<?php declare(strict_types=1);

namespace InteractionDesignFoundation\PaytmIntegration;

use GuzzleHttp\Client;
use paytm\paytmchecksum\PaytmChecksum;

final class PaytmClient
{
    private Client $client;

    public function __construct(private string $mid, private string $merchantKey, private string $environment, string $endpoint)
    {
        $this->client = new Client([
            'base_uri' => $endpoint,
        ]);
    }

    public function createTransactionToken(int $orderId, string $customerId, string $currency, float $amount): string
    {
        $payload = [];

        $payload['body'] = [
            'mid' => $this->mid,
            'orderId' => $orderId,
            'requestType' => 'Payment',
            'websiteName' => $this->environment,
            'txnAmount' => [
                'value' => $amount,
                'currency' => $currency,
            ],
            'userInfo' => [
                'custId' => $customerId,
            ]
        ];

        $checksum = PaytmChecksum::generateSignature(
            json_encode($payload['body'], \JSON_THROW_ON_ERROR | \JSON_UNESCAPED_SLASHES),
            $this->merchantKey
        );

        $payload['head'] = [
            'channelId' => 'WEB',
            'signature' => $checksum
        ];

        $response = $this->client->request('POST', "/theia/api/v1/initiateTransaction?mid={$this->mid}&orderId=$orderId", [
            'json' => $payload
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);

        if ($responseBody['body']['resultInfo']['resultStatus'] === 'S') {
            return $responseBody['body']['txnToken'];
        }

        throw new UnableToRetrieveTransactionToken($responseBody['body']['resultInfo']['resultCode'], $responseBody['body']['resultInfo']['resultMsg']);
    }
}
