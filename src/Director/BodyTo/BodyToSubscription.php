<?php

declare(strict_types=1);

namespace PlugAndPay\Sdk\Director\BodyTo;

use DateTimeImmutable;
use Exception;
use PlugAndPay\Sdk\Entity\Subscription;
use PlugAndPay\Sdk\Enum\Mode;
use PlugAndPay\Sdk\Enum\Source;
use PlugAndPay\Sdk\Enum\SubscriptionStatus;
use PlugAndPay\Sdk\Exception\DecodeResponseException;

class BodyToSubscription
{
    /**
     * @throws \PlugAndPay\Sdk\Exception\DecodeResponseException
     */
    public static function build(array $data): Subscription
    {
        $subscription = (new Subscription(false))
            ->setId($data['id'])
            ->setCancelledAt($data['cancelled_at'] ? self::date($data, 'cancelled_at') : null)
            ->setCreatedAt(self::date($data, 'created_at'))
            ->setDeletedAt($data['deleted_at'] ? self::date($data, 'deleted_at') : null)
            ->setStatus(SubscriptionStatus::from($data['status']))
            ->setMode(Mode::from($data['mode']))
            ->setSource(Source::tryFrom($data['source']) ?? Source::UNKNOWN);

        if (isset($data['product'])) {
            $subscription->setProduct(BodyToProduct::build($data['product']));
        }

        if (isset($data['pricing'])) {
            $subscription->setPricing(BodyToPricing::build($data['pricing']));
        }

        return $subscription;
    }

    /**
     * @return Subscription[]
     * @throws DecodeResponseException
     */
    public static function buildMulti(array $data): array
    {
        $result = [];
        foreach ($data as $order) {
            $result[] = self::build($order);
        }

        return $result;
    }

    /**
     * @throws \PlugAndPay\Sdk\Exception\DecodeResponseException
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
