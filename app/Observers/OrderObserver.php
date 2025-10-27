<?php

namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    public function created(Order $order)
    {
        Log::info("Order created: {$order->order_number}");
        // Bisa trigger email notification, dll
    }

    public function updated(Order $order)
    {
        if ($order->isDirty('status')) {
            Log::info("Order {$order->order_number} status changed to: {$order->status}");
            // Trigger notification berdasarkan status
        }
    }

    public function deleted(Order $order)
    {
        Log::info("Order deleted: {$order->order_number}");
    }
}
