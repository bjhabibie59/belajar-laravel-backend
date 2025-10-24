<?php

namespace App\Repositories;

use App\Contracts\BookRepositoryInterface;
use App\Models\Book;

class BookRepository extends BaseRepository implements BookRepositoryInterface
{
    public function __construct(Book $model)
    {
        parent::__construct($model);
    }

    public function findByIsbn(string $isbn)
    {
        return $this->model->where('isbn', $isbn)->first();
    }

    public function getAvailableBooks()
    {
        return $this->model->available()->get();
    }

    public function searchBooks(array $filters)
    {
        $query = $this->model->with(['author', 'publisher', 'category']);

        if (isset($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

        if (isset($filters['author_id'])) {
            $query->where('author_id', $filters['author_id']);
        }

        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if (isset($filters['available']) && $filters['available']) {
            $query->available();
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function decreaseStock($id, int $quantity)
    {
        $book = $this->find($id);
        return $book->decreaseStock($quantity);
    }

    public function increaseStock($id, int $quantity)
    {
        $book = $this->find($id);
        return $book->increaseStock($quantity);
    }
}
