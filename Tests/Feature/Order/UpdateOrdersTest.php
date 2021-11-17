<?php /** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace PlugAndPay\Sdk\Tests\Feature\Order;

use PHPUnit\Framework\TestCase;
use PlugAndPay\Sdk\Entity\Order;
use PlugAndPay\Sdk\Enum\PaymentStatus;
use PlugAndPay\Sdk\Service\UpdateOrderService;
use PlugAndPay\Sdk\Tests\Feature\Order\Mock\OrderUpdateClientMock;

class UpdateOrdersTest extends TestCase
{
    /** @test */
    public function update_basic_order(): void
    {
        $client  = new OrderUpdateClientMock();
        $service = new UpdateOrderService($client);

        $service->update(1, function (Order $order) {
            $order->setHidden(true);
        });

        static::assertEquals(['is_hidden' => true], $client->requestBody());
        static::assertEquals('/v2/orders/1', $client->path());
    }

    /** @test */
    public function update_order_relations(): void
    {
        $client  = (new OrderUpdateClientMock())->billing()->payment();
        $service = new UpdateOrderService($client);

        $service->update(1, function (Order $order) {
            $order->billing()->setEmail('updated@email.nl');
            $order->billing()->address()->setCountry('BE');
            $order->billing()->address()->setCountry('BE');
            $order->payment()->setStatus(PaymentStatus::PAID);
        });

        static::assertEquals(
            [
                'billing' => [
                    'address' => [
                        'country' => 'BE',
                    ],
                    'email'   => 'updated@email.nl',
                ],
                'payment' => [
                    'status' => 'paid',
                ],
            ], $client->requestBody());
    }
}