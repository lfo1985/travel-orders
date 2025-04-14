<?php

namespace Tests\Unit;

use App\Enums\StatusOrderEnum;
use App\Exceptions\OrderNotFoundException;
use App\Exceptions\UpdateOrderStatusUnauthorizedException;
use App\Exceptions\UpdateStatusOrderFailedException;
use App\Models\Order;
use App\Repositories\Order\OrderRepository;
use App\Services\Order\OrderService;
use Tests\TestCase;

class UpdateStatusOrderServiceTest extends TestCase
{
    public function test_update_status_order_where_the_user_unauthorized(): void
    {
        $mockOrderRepository = $this->createMock(OrderRepository::class);
        $mockOrderRepository->method('getById')->willReturn(new Order([
            'id' => 1,
            'user_id' => 1,
            'costumer_name' => 'John Doe',
            'destination_name' => 'New York',
            'departure_date' => '2023-10-01',
            'return_date' => '2023-10-10',
        ]));

        $orderService = new OrderService($mockOrderRepository);
        $this->expectException(UpdateOrderStatusUnauthorizedException::class);
        $this->expectExceptionMessage('You cannot update your own order');
        $this->expectExceptionCode(403);
        $orderService->updateStatus(1, StatusOrderEnum::APPROVED, 1);
    }

    public function test_update_status_order_where_user_is_different(): void
    {
        $order = new Order([
            'id' => 1,
            'user_id' => 2,
            'costumer_name' => 'John Doe',
            'destination_name' => 'New York',
            'departure_date' => '2023-10-01',
            'return_date' => '2023-10-10',
            'status' => StatusOrderEnum::REQUESTED,
        ]);
        $orderUpdate = new Order([
            'id' => 1,
            'user_id' => 2,
            'costumer_name' => 'John Doe',
            'destination_name' => 'New York',
            'departure_date' => '2023-10-01',
            'return_date' => '2023-10-10',
            'status' => StatusOrderEnum::CANCELED,
        ]);
        $mockOrderRepository = $this->createMock(OrderRepository::class);
        $mockOrderRepository->method('getById')->willReturn($order);
        $mockOrderRepository->method('update')->willReturn($orderUpdate);

        $orderService = new OrderService($mockOrderRepository);
        $order = $orderService->updateStatus(1, StatusOrderEnum::CANCELED, 1);

        $this->assertEquals(StatusOrderEnum::label(StatusOrderEnum::REQUESTED), data_get($order, 'old_status'));
        $this->assertEquals(StatusOrderEnum::label(StatusOrderEnum::CANCELED), data_get($order, 'new_status'));
    }

    public function test_update_status_order_where_order_not_found(): void
    {
        $mockOrderRepository = $this->createMock(OrderRepository::class);
        $mockOrderRepository->method('getById')->willReturn(null);

        $orderService = new OrderService($mockOrderRepository);

        $this->expectException(OrderNotFoundException::class);
        $this->expectExceptionMessage('Order not found.');
        $this->expectExceptionCode(404);

        $orderService->updateStatus(1, StatusOrderEnum::APPROVED, 1);
    }

    public function test_update_status_order_status_throw_error_where_status_is_requested(): void
    {
        $mockOrderRepository = $this->createMock(OrderRepository::class);
        $mockOrderRepository->method('getById')->willReturn(new Order([
            'id' => 1,
            'user_id' => 2,
            'costumer_name' => 'John Doe',
            'destination_name' => 'New York',
            'departure_date' => '2023-10-01',
            'return_date' => '2023-10-10',
            'status' => StatusOrderEnum::REQUESTED,
        ]));

        $orderService = new OrderService($mockOrderRepository);

        $this->expectException(UpdateStatusOrderFailedException::class);
        $this->expectExceptionMessage('You can only update the order if the status is being changed to CANCELED or APPROVED');
        $this->expectExceptionCode(400);

        $orderService->updateStatus(1, StatusOrderEnum::REQUESTED, 1);
    }

    public function test_update_status_order_status_same_status(): void
    {
        $mockOrderRepository = $this->createMock(OrderRepository::class);
        $mockOrderRepository->method('getById')->willReturn(new Order([
            'id' => 1,
            'user_id' => 2,
            'costumer_name' => 'John Doe',
            'destination_name' => 'New York',
            'departure_date' => '2023-10-01',
            'return_date' => '2023-10-10',
            'status' => StatusOrderEnum::APPROVED,
        ]));

        $orderService = new OrderService($mockOrderRepository);

        $this->expectException(UpdateStatusOrderFailedException::class);
        $this->expectExceptionMessage('You cannot update the order to the same status');
        $this->expectExceptionCode(400);

        $orderService->updateStatus(1, StatusOrderEnum::APPROVED, 1);
    }

    public function test_update_status_order_throw_error_where_auth_id_is_missing()
    {
        $mockOrderRepository = $this->createMock(OrderRepository::class);
        $mockOrderRepository->method('getById')->willReturn(new Order([
            'id' => 1,
            'user_id' => 2,
            'costumer_name' => 'John Doe',
            'destination_name' => 'New York',
            'departure_date' => '2023-10-01',
            'return_date' => '2023-10-10',
        ]));

        $orderService = new OrderService($mockOrderRepository);

        $this->expectException(UpdateOrderStatusUnauthorizedException::class);
        $this->expectExceptionMessage('You are not authorized to update this order');
        $this->expectExceptionCode(401);

        $orderService->updateStatus(1, StatusOrderEnum::APPROVED, 0);
    }

    public function test_update_status_order_to_canceled_if_status_is_approved(): void
    {
        $mockOrderRepository = $this->createMock(OrderRepository::class);
        $mockOrderRepository->method('getById')->willReturn(new Order([
            'id' => 1,
            'user_id' => 2,
            'costumer_name' => 'John Doe',
            'destination_name' => 'New York',
            'departure_date' => '2023-10-01',
            'return_date' => '2023-10-10',
            'status' => StatusOrderEnum::APPROVED,
        ]));

        $orderService = new OrderService($mockOrderRepository);

        $this->expectException(UpdateStatusOrderFailedException::class);
        $this->expectExceptionMessage('You cannot to CANCEL the order if the status is APPROVED');
        $this->expectExceptionCode(400);

        $orderService->updateStatus(1, StatusOrderEnum::CANCELED, 1);
    }
}
