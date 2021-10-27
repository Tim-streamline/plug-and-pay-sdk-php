<?php

declare(strict_types=1);

namespace PlugAndPay\Sdk\Director\BodyTo;

use DateTimeImmutable;
use PlugAndPay\Sdk\Entity\Comment;

class BodyToComment
{
    public static function build(array $data): Comment
    {
        return (new Comment())
            ->setCreatedAt(new DateTimeImmutable($data['created_at']))
            ->setId($data['id'])
            ->setUpdatedAt(new DateTimeImmutable($data['updated_at']))
            ->setValue($data['value']);
    }

    /**
     * @param $comments
     * @return Comment[]
     */
    public static function buildMulti($comments): array
    {
        $result = [];
        foreach ($comments as $comment) {
            $result[] = self::build($comment);
        }
        return $result;
    }
}
