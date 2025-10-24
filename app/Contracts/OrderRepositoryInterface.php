<?php

namespace App\Contracts;

interface OrderRepositoryInterface extends RepositoryInterface
{
    public function findByOrderNumber(string $orderNumber);
    public function getOrdersByCustomer($customerId);
    public function getOrdersByStatus(string $status);
    public function updateStatus($id, string $status);
}
