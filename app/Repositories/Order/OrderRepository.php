<?php

namespace App\Repositories\Order;

use App\Enums\StatusOrderEnum;
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
            $order = $order->byStatus(StatusOrderEnum::from($status));
        }

        if(Arr::has($filters, 'user_id')) {
            $userId = Arr::get($filters, 'user_id');
            $order = $order->byUserId($userId);
        }

        if(Arr::has($filters, 'destination_name')) {
            $destinantionName = Arr::get($filters, 'destination_name');
            $order = $order->byDestinationName($destinantionName);
        }

        if(Arr::has($filters, 'departure_date_start') && Arr::has($filters, 'departure_date_end')) {
            $departureDateStart = dateFormat(Arr::get($filters, 'departure_date_start'));
            $departureDateEnd = dateFormat(Arr::get($filters, 'departure_date_end'));
            $order = $order->byDepartureDate($departureDateStart, $departureDateEnd);
        }

        if(Arr::has($filters, 'return_date_start') && Arr::has($filters, 'return_date_end')) {
            $returnDateStart = dateFormat(Arr::get($filters, 'return_date_start'));
            $returnDateEnd = dateFormat(Arr::get($filters, 'return_date_end'));
            $order = $order->byReturnDate($returnDateStart, $returnDateEnd);
        }

        if(Arr::has($filters, 'travel_departure_date') && Arr::has($filters, 'travel_return_date')) {
            $travelDepartureDate = dateFormat(Arr::get($filters, 'travel_departure_date'));
            $travelReturnDate = dateFormat(Arr::get($filters, 'travel_return_date'));
            $order = $order->byTravelDateRange($travelDepartureDate, $travelReturnDate);
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
        return Order::with('user')->find($id);
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
    public function update(int $id, array $data): ?Order
    {
        $order = $this->getById($id);

        if ($order->update($data)) {
            return new Order($data);
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
