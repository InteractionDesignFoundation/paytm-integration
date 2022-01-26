<?php declare(strict_types=1);

namespace InteractionDesignFoundation\PaytmIntegration;

final class UnableToRetrieveTransactionToken extends \Exception
{
    public function __construct(string $resultCode, string $resultMsg)
    {
        parent::__construct("$resultCode: $resultMsg", 400);
    }
}
