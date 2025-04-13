<?php

namespace Tests\Unit;

use App\Exceptions\OrderNotFoundException;
use App\Models\Order;
use App\Repositories\Order\OrderRepository;
use App\Services\Order\OrderService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    public function test_return_user_object_where_success_in_the_create_order(): void
    {
        $orderData = [
            'user_id' => 1,
            'costumer_name' => 'John Doe',
            'destination_name' => 'New York',
            'departure_date' => '2023-10-01',
            'return_date' => '2023-10-10',
        ];

        $mockOrderRepository = $this->createMock(OrderRepository::class);
        $mockOrderRepository->method('create')->willReturn(new Order($orderData));

        $orderService = new OrderService($mockOrderRepository);
        $order = $orderService->createOrder($orderData);

        $this->assertNotNull($order);
        $this->assertEquals(1, $order->user_id);
        $this->assertEquals('John Doe', $order->costumer_name);
        $this->assertEquals('New York', $order->destination_name);
        $this->assertEquals('01/10/2023', $order->departure_date);
        $this->assertEquals('10/10/2023', $order->return_date);
    }

    public function test_return_error_where_order_not_found(): void
    {
        $mockOrderRepository = $this->createMock(OrderRepository::class);
        $mockOrderRepository->method('getById')->willReturn(null);

        $orderService = new OrderService($mockOrderRepository);

        $this->expectException(OrderNotFoundException::class);
        $this->expectExceptionMessage('Order not found.');
        $this->expectExceptionCode(404);

        $orderService->getOrderById(999);
    }

    public function test_return_order_object_where_success_in_the_get_order_by_id(): void
    {
        $orderData = [
            'user_id' => 1,
            'costumer_name' => 'John Doe',
            'destination_name' => 'New York',
            'departure_date' => '2023-10-01',
            'return_date' => '2023-10-10',
        ];

        $mockOrderRepository = $this->createMock(OrderRepository::class);
        $mockOrderRepository->method('getById')->willReturn(new Order($orderData));

        $orderService = new OrderService($mockOrderRepository);
        $order = $orderService->getOrderById(1);

        $this->assertNotNull($order);
        $this->assertEquals(1, $order->user_id);
        $this->assertEquals('John Doe', $order->costumer_name);
        $this->assertEquals('New York', $order->destination_name);
        $this->assertEquals('01/10/2023', $order->departure_date);
        $this->assertEquals('10/10/2023', $order->return_date);
    }

    public function test_return_order_object_where_success_in_the_update_order(): void
    {
        $orderData = [
            'id' => 1,
            'user_id' => 1,
            'costumer_name' => 'John Doe',
            'destination_name' => 'New York',
            'departure_date' => '2023-10-01',
            'return_date' => '2023-10-10',
        ];

        $mockOrderRepository = $this->createMock(OrderRepository::class);
        $mockOrderRepository
            ->expects($this->once())
            ->method('getById')
            ->with(1)
            ->willReturn(new Order($orderData));
        $mockOrderRepository->method('update')->willReturn(new Order($orderData));

        $orderService = new OrderService($mockOrderRepository);
        $order = $orderService->updateOrder(1, $orderData);

        $this->assertNotNull($order);
        $this->assertEquals(1, $order->user_id);
        $this->assertEquals('John Doe', $order->costumer_name);
        $this->assertEquals('New York', $order->destination_name);
        $this->assertEquals('01/10/2023', $order->departure_date);
        $this->assertEquals('10/10/2023', $order->return_date);
    }

    public function test_return_error_where_order_not_found_in_the_update_order(): void
    {
        $mockOrderRepository = $this->createMock(OrderRepository::class);
        $mockOrderRepository->method('update')->willReturn(null);

        $orderService = new OrderService($mockOrderRepository);

        $this->expectException(OrderNotFoundException::class);
        $this->expectExceptionMessage('Order not found.');
        $this->expectExceptionCode(404);

        $orderService->updateOrder(999, []);
    }

    public function test_return_success_where_order_deleted(): void
    {
        $mockOrderRepository = $this->createMock(OrderRepository::class);
        $mockOrderRepository->method('getById')->willReturn(new Order([
            'user_id' => 1,
            'costumer_name' => 'John Doe',
            'destination_name' => 'New York',
            'departure_date' => '2023-10-01',
            'return_date' => '2023-10-10',
        ]));

        $mockOrderRepository->method('delete')->willReturn(true);

        $orderService = new OrderService($mockOrderRepository);
        $result = $orderService->deleteOrder(1);

        $this->assertTrue($result);
    }

    public function test_return_error_where_order_not_found_in_the_delete_order(): void
    {
        $mockOrderRepository = $this->createMock(OrderRepository::class);
        $mockOrderRepository->method('getById')->willReturn(null);

        $orderService = new OrderService($mockOrderRepository);

        $this->expectException(OrderNotFoundException::class);
        $this->expectExceptionMessage('Order not found.');
        $this->expectExceptionCode(404);

        $orderService->deleteOrder(999);
    }

    public function test_return_collection_where_success_in_the_get_all_orders(): void
    {
        $orderData = [
            [
                'user_id' => 1,
                'costumer_name' => 'John Doe',
                'destination_name' => 'New York',
                'departure_date' => '2023-10-01',
                'return_date' => '2023-10-10',
            ],
            [
                'user_id' => 2,
                'costumer_name' => 'Jane Doe',
                'destination_name' => 'Los Angeles',
                'departure_date' => '2023-11-01',
                'return_date' => '2023-11-10',
            ]
        ];

        $mockOrderRepository = $this->createMock(OrderRepository::class);
        $mockOrderRepository->method('getAll')->willReturn(new LengthAwarePaginator($orderData, 2, 50, 1));

        $orderService = new OrderService($mockOrderRepository);
        $orders = $orderService->getAllOrders(new Request());

        $this->assertIsObject($orders);
        $this->assertNotNull($orders);
        $this->assertCount(2, $orders);
    }
}