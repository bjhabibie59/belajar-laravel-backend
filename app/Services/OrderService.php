<?php

namespace App\Services;

use App\Contracts\OrderRepositoryInterface;
use App\Contracts\BookRepositoryInterface;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{
    protected $orderRepository;
    protected $bookRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        BookRepositoryInterface $bookRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->bookRepository = $bookRepository;
    }

    public function createOrder(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Validate stock availability
            foreach ($data['items'] as $item) {
                $book = $this->bookRepository->find($item['book_id']);

                if ($book->stock < $item['quantity']) {
                    throw new Exception("Stok buku '{$book->title}' tidak mencukupi");
                }
            }

            // Calculate total
            $totalAmount = 0;
            foreach ($data['items'] as $item) {
                $book = $this->bookRepository->find($item['book_id']);
                $totalAmount += $book->price * $item['quantity'];
            }

            // Create order
            $order = $this->orderRepository->create([
                'order_number' => Order::generateOrderNumber(),
                'customer_id' => $data['customer_id'],
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'order_date' => now(),
            ]);

            // Create order items and decrease stock
            foreach ($data['items'] as $item) {
                $book = $this->bookRepository->find($item['book_id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $book->id,
                    'quantity' => $item['quantity'],
                    'price' => $book->price,
                    'subtotal' => $book->price * $item['quantity'],
                ]);

                $this->bookRepository->decreaseStock($book->id, $item['quantity']);
            }

            return $order->load(['orderItems.book', 'customer']);
        });
    }

    public function getOrderById($id)
    {
        return $this->orderRepository->find($id);
    }

    public function getOrdersByCustomer($customerId)
    {
        return $this->orderRepository->getOrdersByCustomer($customerId);
    }

    public function updateOrderStatus($id, string $status)
    {
        return $this->orderRepository->updateStatus($id, $status);
    }

    public function cancelOrder($id)
    {
        return DB::transaction(function () use ($id) {
            return $this->orderRepository->updateStatus($id, 'cancelled');
        });
    }
}
