<?php

namespace App\Http\Controllers;

use App\Enums\StatusOrderEnum;
use App\Exceptions\OrderNotFoundException;
use App\Exceptions\UpdateDeleteOrderException;
use App\Exceptions\UpdateOrderStatusUnauthorizedException;
use App\Exceptions\UpdateStatusOrderFailedException;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Requests\UpdateStatusOrderRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\ShowOrderResource;
use App\Services\Order\OrderService;
use App\Services\Order\SendEmailUpdateStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private SendEmailUpdateStatusService $sendEmailUpdateStatusService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return sendData(new OrderCollection($this->orderService->getAllOrders($request)));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            $this->orderService->createOrder($request->validated());

            return sendSuccess(200, 'Order created successfully.');
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
            $authId = $request->headers->get('auth_id');
            $this->orderService->updateOrder($id, $authId, $request->validated());

            return sendSuccess(200, 'Order updated successfully.');
        } catch (OrderNotFoundException | UpdateDeleteOrderException $e) {
            return sendError($e->getCode(), $e->getMessage());
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return sendError(500, 'Error updating order.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id, Request $request)
    {
        try {
            $authId = $request->headers->get('auth_id');
            $this->orderService->deleteOrder($id, $authId);

            return sendSuccess(200, 'Order deleted successfully.');
        } catch (OrderNotFoundException | UpdateDeleteOrderException $e) {
            return sendError($e->getCode(), $e->getMessage());
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return sendError(500, 'Error deleting order.');
        }
    }

    public function updateStatus(UpdateStatusOrderRequest $request, int $id)
    {
        try {
            $authId = $request->headers->get('auth_id');
            $status = StatusOrderEnum::from(data_get($request, 'status'));

            $updateStatus = $this->orderService->updateStatus($id, $status, $authId);

            $oldStatus = data_get($updateStatus, 'old_status');
            $newStatus = data_get($updateStatus, 'new_status');

            $this->sendEmailUpdateStatusService->sendEmail(
                data_get($updateStatus, 'order'),
                $oldStatus,
                $newStatus
            );

            return sendSuccess(200, 'Order status updated successfully.');
        } catch (UpdateOrderStatusUnauthorizedException | UpdateStatusOrderFailedException | OrderNotFoundException $e) {
            return sendError($e->getCode(), $e->getMessage());
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return sendError(500, 'Error updating order status.');
        }
    }
}
