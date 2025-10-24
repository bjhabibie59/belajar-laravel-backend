<?php

namespace App\Repositories;

use App\Contracts\OrderRepositoryInterface;
use App\Models\Order;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function findByOrderNumber(string $orderNumber)
    {
        return $this->model->where('order_number', $orderNumber)->firstOrFail();
    }

    public function getOrdersByCustomer($customerId)
    {
        return $this->model->where('customer_id', $customerId)
            ->with(['orderItems.book'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getOrdersByStatus(string $status)
    {
        return $this->model->where('status', $status)
            ->with(['customer', 'orderItems.book'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function updateStatus($id, string $status)
    {
        $order = $this->find($id);

        if ($status === 'cancelled' && $order->status !== 'cancelled') {
            $order->markAsCancelled();
        } else {
            $order->update(['status' => $status]);
        }

        return $order;
    }
}
