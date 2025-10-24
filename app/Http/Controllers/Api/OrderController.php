<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use App\Helpers\ResponseHelper;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(OrderRequest $request)
    {
        try {
            $order = $this->orderService->createOrder($request->validated());

            return ResponseHelper::success(
                new OrderResource($order),
                'Berhasil membuat pesanan',
                201
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $order = $this->orderService->getOrderById($id);

            return ResponseHelper::success(
                new OrderResource($order->load(['customer', 'orderItems.book'])),
                'Berhasil mengambil detail pesanan'
            );
        } catch (\Exception $e) {
            return ResponseHelper::notFound();
        }
    }

    public function updateStatus(UpdateOrderStatusRequest $request, $id)
    {
        try {
            $order = $this->orderService->updateOrderStatus($id, $request->status);

            return ResponseHelper::success(
                new OrderResource($order),
                'Berhasil mengupdate status pesanan'
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

    public function cancel($id)
    {
        try {
            $order = $this->orderService->cancelOrder($id);

            return ResponseHelper::success(
                new OrderResource($order),
                'Berhasil membatalkan pesanan'
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

    public function customerOrders($customerId)
    {
        try {
            $orders = $this->orderService->getOrdersByCustomer($customerId);

            return ResponseHelper::success(
                OrderResource::collection($orders),
                'Berhasil mengambil riwayat pesanan'
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }
}
