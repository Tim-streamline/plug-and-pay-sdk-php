<?php

declare(strict_types=1);

namespace PlugAndPay\Sdk\Director\BodyTo;

use PlugAndPay\Sdk\Entity\Rate;
use PlugAndPay\Sdk\Entity\Tax;

class BodyToTax
{
    public static function build(array $data): Tax
    {
        return (new Tax())
            ->setAmount((float)$data['amount'])
            ->setRate(
                new Rate(
                    (float)$data['rate']['percentage'],
                    $data['rate']['country'],
                    $data['rate']['id']
                )
            );
    }

    /**
     * @return Tax[]
     */
    public static function buildMulti(array $data): array
    {
        $result = [];
        foreach ($data as $tax) {
            $result[] = self::build($tax);
        }
        return $result;
    }
}
