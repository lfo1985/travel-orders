<?php

namespace App\Services\Order;

use App\Exceptions\OrderNotFoundException;
use App\Models\Order;
use App\Repositories\Order\OrderRepository;

class OrderService
{
    private OrderRepository $orderRepository;

    /**
     * OrderService constructor.
     * 
     * @param OrderRepository $orderRepository
     * @return void
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

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
        if (!$order) {
            throw new OrderNotFoundException('Order not found.', 404);
        }
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
        $order = $this->orderRepository->update($id, $data);
        if (!$order) {
            throw new OrderNotFoundException('Order not found.', 404);
        }
        return $order;
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
        if (!$order) {
            throw new OrderNotFoundException('Order not found.', 404);
        }
        return $this->orderRepository->delete($order->id);
    }

    /**
     * Get all orders.
     * 
     * @return object
     */
    public function getAllOrders(array $filters): object
    {
        return $this->orderRepository->getAll($filters);
    }
}