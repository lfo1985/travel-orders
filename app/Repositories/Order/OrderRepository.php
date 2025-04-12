<?php

namespace App\Repositories\Order;

use App\Models\Order;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class OrderRepository
{
    /**
     * Filter orders based on the provided filters.
     * 
     * @param array $filters
     * @param Order $order
     * @return Builder|Order
     */
    public function filtersOrders(array $filters = [], Order $order): Builder|Order
    {
        if(Arr::has($filters, 'status')) {
            $status = Arr::get($filters, 'status');
            $order = $order->byStatus($status);
        }

        if(Arr::has($filters, 'user_id')) {
            $userId = Arr::get($filters, 'user_id');
            $order = $order->byUserId($userId);
        }

        return $order;
    }

    /**
     * Get all orders.
     * 
     * @return Collection
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return $this->filtersOrders($filters, new Order())->paginate(50);
    }

    /**
     * Get orders by order ID.
     * 
     * @param int $id
     * @return Order
     */
    public function getById(int $id): ?Order
    {
        return Order::find($id);
    }

    /**
     * Create a new order.
     * 
     * @param array $data
     * @return Order
     */
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    /**
     * Update an existing order.
     * 
     * @param int $id
     * @param array $data
     * @return Order|null
     */
    public function update(int $id, array $data): Order|null
    {
        $order = Order::find($id);
        if ($order) {
            $order->update($data);
            return $order;
        }
        return null;
    }

    /**
     * Delete an order by ID.
     * 
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $order = Order::find($id);
        if ($order) {
            return $order->delete();
        }
        return false;
    }
}
