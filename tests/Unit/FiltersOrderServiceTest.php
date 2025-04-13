<?php

namespace Tests\Unit;

use App\Repositories\Order\OrderRepository;
use App\Services\Order\OrderService;
use Illuminate\Http\Request;
use Tests\TestCase;

class FiltersOrderServiceTest extends TestCase
{
    public function test_filters_orders_by_user_id(): void
    {
        $userId = 1;
        $request = new Request(['user_id' => $userId]);

        $mockOrderRepository = $this->createMock(OrderRepository::class);
        $orderService = new OrderService($mockOrderRepository);

        $filters = $orderService->filterOrders($request);

        $this->assertArrayHasKey('user_id', $filters);
        $this->assertEquals($userId, $filters['user_id']);
    }

    public function test_filters_orders_by_status(): void
    {
        $request = new Request(['status' => 'approved']);

        $mockOrderRepository = $this->createMock(OrderRepository::class);
        $orderService = new OrderService($mockOrderRepository);

        $filters = $orderService->filterOrders($request);

        $this->assertArrayHasKey('status', $filters);
        $this->assertEquals('APPROVED', $filters['status']);
    }

    public function test_filters_orders_by_departure_date(): void
    {
        $departureDateStart = '2023-10-01';
        $departureDateEnd = '2023-10-01';
        $request = new Request(
            [
                'departure_date_start' => $departureDateStart,
                'departure_date_end' => $departureDateEnd
            ]
        );

        $mockOrderRepository = $this->createMock(OrderRepository::class);
        $orderService = new OrderService($mockOrderRepository);

        $filters = $orderService->filterOrders($request);

        $this->assertArrayHasKey('departure_date_start', $filters);
        $this->assertEquals($departureDateStart, $filters['departure_date_start']);
        $this->assertArrayHasKey('departure_date_end', $filters);
        $this->assertEquals($departureDateEnd, $filters['departure_date_end']);
    }

    public function test_filters_orders_by_return_date(): void
    {
        $returnDateStart = '2023-10-01';
        $returnDateEnd = '2023-10-01';
        $request = new Request(
            [
                'return_date_start' => $returnDateStart,
                'return_date_end' => $returnDateEnd
            ]
        );

        $mockOrderRepository = $this->createMock(OrderRepository::class);
        $orderService = new OrderService($mockOrderRepository);

        $filters = $orderService->filterOrders($request);

        $this->assertArrayHasKey('return_date_start', $filters);
        $this->assertEquals($returnDateStart, $filters['return_date_start']);
        $this->assertArrayHasKey('return_date_end', $filters);
        $this->assertEquals($returnDateEnd, $filters['return_date_end']);
    }

    public function test_filters_order_by_range_of_travel_dates(): void
    {
        $travelDepartureDate = '2023-10-01';
        $travelReturnDate = '2023-10-01';
        $request = new Request(
            [
                'travel_departure_date' => $travelDepartureDate,
                'travel_return_date' => $travelReturnDate
            ]
        );

        $mockOrderRepository = $this->createMock(OrderRepository::class);
        $orderService = new OrderService($mockOrderRepository);

        $filters = $orderService->filterOrders($request);

        $this->assertArrayHasKey('travel_departure_date', $filters);
        $this->assertEquals($travelDepartureDate, $filters['travel_departure_date']);
        $this->assertArrayHasKey('travel_return_date', $filters);
        $this->assertEquals($travelReturnDate, $filters['travel_return_date']);
    }
}
