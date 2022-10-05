<?php

declare(strict_types=1);

namespace PlugAndPay\Sdk\Director\BodyTo;

use PlugAndPay\Sdk\Entity\PriceOriginal;

class BodyToPriceOriginal
{
    public static function build(array $data): PriceOriginal
    {
        return (new PriceOriginal())
            ->setAmount((float) $data['amount'])
            ->setAmountWithTax((float) $data['amount_with_tax']);
    }
}
