<?php

namespace App\Http\Controllers;

use App\Enums\StatusOrderEnum;
use App\Exceptions\OrderNotFoundException;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\ShowOrderResource;
use App\Services\Order\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {
        $this->orderService = $orderService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = [];

        if(data_get($request, 'status')) {
            Arr::set($filters, 'status', StatusOrderEnum::from(data_get($request, 'status')));
        }

        if(data_get($request, 'user_id')) {
            Arr::set($filters, 'user_id', data_get($request, 'user_id'));
        }

        return sendData(new OrderCollection($this->orderService->getAllOrders($filters)));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            $order = $this->orderService->createOrder($request->validated());
            return sendSuccess(200, 'Order created successfully.', ['order' => $order]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return sendError(500, 'Error creating order.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $order = $this->orderService->getOrderById($id);
            return sendData(new ShowOrderResource($order));
        } catch (OrderNotFoundException $e) {
            return sendError($e->getCode(), $e->getMessage());
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return sendError(500, 'Error fetching order.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, int $id)
    {
        try {
            $order = $this->orderService->updateOrder($id, $request->validated());
            return sendSuccess(200, 'Order updated successfully.', ['order' => $order]);
        } catch (OrderNotFoundException $e) {
            return sendError($e->getCode(), $e->getMessage());
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return sendError(500, 'Error updating order.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $this->orderService->deleteOrder($id);
            return sendSuccess(200, 'Order deleted successfully.');
        } catch (OrderNotFoundException $e) {
            return sendError($e->getCode(), $e->getMessage());
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return sendError(500, 'Error deleting order.');
        }
    }
}
