<?php

declare(strict_types=1);

namespace PlugAndPay\Sdk\Director\BodyTo;

use DateTimeImmutable;
use Exception;
use PlugAndPay\Sdk\Entity\Subscription;
use PlugAndPay\Sdk\Entity\SubscriptionBilling;
use PlugAndPay\Sdk\Entity\SubscriptionBillingSchedule;
use PlugAndPay\Sdk\Enum\Interval;
use PlugAndPay\Sdk\Enum\Mode;
use PlugAndPay\Sdk\Enum\Source;
use PlugAndPay\Sdk\Enum\SubscriptionStatus;
use PlugAndPay\Sdk\Exception\DecodeResponseException;

class BodyToSubscriptionBillingSchedule
{
    /**
     * @throws DecodeResponseException
     */
    public static function build(array $data): SubscriptionBillingSchedule
    {
        $billingSchedule = (new SubscriptionBillingSchedule())
            ->setInterval(Interval::from($data['interval']))
            ->setLast($data['last'])
            ->setLastAt($data['last_at'] ? self::date($data, 'last_at') : null)
            ->setLatest($data['latest'])
            ->setLatestAt($data['latest_at'] ? self::date($data, 'latest_at') : null)
            ->setNext($data['next'])
            ->setNextAt($data['next_at'] ? self::date($data, 'next_at') : null)
            ->setNext($data['remaining'])
            ->setTerminationAt($data['termination_at'] ? self::date($data, 'termination_at') : null)
        ;

        return $billingSchedule;
    }

    /**
     * @throws DecodeResponseException
     */
    private static function date(array $data, string $field): DateTimeImmutable
    {
        try {
            return new DateTimeImmutable($data[$field]);
        } catch (Exception $e) {
            /** @noinspection JsonEncodingApiUsageInspection */
            $body = (string)json_encode($data, JSON_ERROR_NONE);
            throw new DecodeResponseException($body, $field, $e);
        }
    }
}
