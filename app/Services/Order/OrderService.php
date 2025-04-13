<?php

namespace App\Services\Order;

use App\Enums\StatusOrderEnum;
use App\Exceptions\OrderNotFoundException;
use App\Exceptions\UpdateOrderStatusUnauthorizedException;
use App\Exceptions\UpdateStatusOrderFailedException;
use App\Models\Order;
use App\Repositories\Order\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * OrderService constructor.
     * 
     * @param OrderRepository $orderRepository
     * @return void
     */
    public function __construct(private OrderRepository $orderRepository) {}

    /**
     * Create a new order.
     * 
     * @param array $data
     * @return Order
     */
    public function createOrder(array $data): Order
    {
        return $this->orderRepository->create($data);
    }

    /**
     * Get an order by ID.
     * 
     * @param int $id
     * @return Order|null
     */
    public function getOrderById($id): Order|null
    {
        $order = $this->orderRepository->getById($id);

        $this->checkOrderExists($order);

        return $order;
    }

    /**
     * Update an existing order.
     * 
     * @param int $id
     * @param array $data
     * @return Order
     * @throws OrderNotFoundException
     */
    public function updateOrder(int $id, array $data): Order
    {
        $order = $this->orderRepository->getById($id);
        
        $this->checkOrderExists($order);

        return $this->orderRepository->update($id, $data);
    }

    /**
     * Delete an order by ID.
     * 
     * @param int $id
     * @return bool
     * @throws OrderNotFoundException
     */
    public function deleteOrder(int $id): bool
    {
        $order = $this->orderRepository->getById($id);

        $this->checkOrderExists($order);

        return $this->orderRepository->delete($order->id);
    }

    /**
     * Get all orders.
     * 
     * @return object
     */
    public function getAllOrders(Request $request): object
    {
        $filters = $this->filterOrders($request);
        
        return $this->orderRepository->getAll($filters);
    }

    /**
     * Update the status of an order.
     * 
     * @param int $id
     * @param StatusOrderEnum $status
     * @param int $authId
     * @return Order
     * @throws OrderNotFoundException
     * @throws UpdateOrderStatusUnauthorizedException
     * @throws UpdateStatusOrderFailedException
     */
    public function updateStatus(int $id, StatusOrderEnum $status, int $authId): array
    {
        $order = $this->orderRepository->getById($id);

        $oldStatus = data_get($order, 'status');

        $this->checkDataUpdateStatusOrder($order, $status, $authId);

        $order = $this->orderRepository->update(
            $id,
            [
                'status' => $status->value
            ]
        );

        $newStatus = data_get($order, 'status');

        return [
            'order' => $order,
            'old_status' => StatusOrderEnum::label($oldStatus),
            'new_status' => StatusOrderEnum::label($newStatus),
        ];
    }

    /**
     * Check the data for updating the order status.
     * 
     * @param Order|null $order
     * @param StatusOrderEnum $status
     * @param int $authId
     * @return void
     */
    public function checkDataUpdateStatusOrder($order, StatusOrderEnum $status, int $authId): void
    {
        $this->checkOrderExists($order);

        if(!$authId) {
            throw new UpdateOrderStatusUnauthorizedException('You are not authorized to update this order', 401);
        }

        if(data_get($order, 'user_id') === $authId) {
            throw new UpdateOrderStatusUnauthorizedException('You cannot update your own order', 400);
        }

        if(data_get($status, 'value') === StatusOrderEnum::REQUESTED->value) {
            throw new UpdateStatusOrderFailedException('You can only update the order if the status is being changed to CANCELED or APPROVED', 400);
        }

        if(data_get($order, 'status.value') === data_get($status, 'value')) {
            throw new UpdateStatusOrderFailedException('You cannot update the order to the same status', 400);
        }

        if(data_get($order, 'status.value') === StatusOrderEnum::APPROVED->value && data_get($status, 'value') === StatusOrderEnum::CANCELED->value) {
            throw new UpdateStatusOrderFailedException('You cannot to CANCEL the order if the status is APPROVED', 400);
        }
    }

    /**
     * Check if an order exists.
     * 
     * @param Order|null $order
     * @return bool
     * @throws OrderNotFoundException
     */
    public function checkOrderExists(Order|null $order): bool
    {
        if (!$order) {
            throw new OrderNotFoundException('Order not found.', 404);
        }   
        return true;
    }

    /**
     * Filter orders based on request parameters.
     * 
     * @param Request $request
     * @return array
     */
    public function filterOrders(Request $request): array
    {
        $filters = [];

        if ($request->has('status')) {
            $filters['status'] = Str::upper($request->input('status'));
        }

        if ($request->has('user_id')) {
            $filters['user_id'] = $request->input('user_id');
        }

        if ($request->has('destination_name')) {
            $filters['destination_name'] = $request->input('destination_name');
        }

        if ($request->has('departure_date_start') && $request->has('departure_date_end')) {
            $filters['departure_date_start'] = $request->input('departure_date_start');
            $filters['departure_date_end'] = $request->input('departure_date_end');
        }

        if ($request->has('return_date_start') && $request->has('return_date_end')) {
            $filters['return_date_start'] = $request->input('return_date_start');
            $filters['return_date_end'] = $request->input('return_date_end');
        }

        if ($request->has('travel_departure_date') && $request->has('travel_return_date')) {
            $filters['travel_departure_date'] = $request->input('travel_departure_date');
            $filters['travel_return_date'] = $request->input('travel_return_date');
        }

        return $filters;
    }
}