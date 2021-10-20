<?php

declare(strict_types=1);

namespace PlugAndPay\Sdk\Service;

use PlugAndPay\Sdk\Contract\ClientGetInterface;
use PlugAndPay\Sdk\Director\ResponseToOrder;
use PlugAndPay\Sdk\Entity\Order;
use PlugAndPay\Sdk\Entity\Response;
use PlugAndPay\Sdk\Exception\NotFoundException;

class FetchOrderService
{
    private ClientGetInterface $client;

    public function __construct(ClientGetInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws NotFoundException
     */
    public function find(int $id): Order
    {
        $response = $this->client->get("/orders/$id");
        if ($response->status() === Response::HTTP_NOT_FOUND) {
            throw new NotFoundException('Order', $id);
        }
        return (new ResponseToOrder())->build($response->body());
    }

    /**
     * @return Order[]
     */
    public function get(): array
    {
        $response = $this->client->get('/orders');
        return (new ResponseToOrder())->buildMulti($response->body());
    }
}
