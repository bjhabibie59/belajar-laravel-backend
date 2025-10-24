<?php

namespace App\Contracts;

interface BookRepositoryInterface extends RepositoryInterface
{
    public function findByIsbn(string $isbn);
    public function getAvailableBooks();
    public function searchBooks(array $filters);
    public function decreaseStock($id, int $quantity);
    public function increaseStock($id, int $quantity);
}
