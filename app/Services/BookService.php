<?php

namespace App\Services;

use App\Contracts\BookRepositoryInterface;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\DB;

class BookService
{
    protected $bookRepository;
    protected $imageHelper;

    public function __construct(
        BookRepositoryInterface $bookRepository,
        ImageHelper $imageHelper
    ) {
        $this->bookRepository = $bookRepository;
        $this->imageHelper = $imageHelper;
    }

    public function getAllBooks()
    {
        return $this->bookRepository->all();
    }

    public function getBookById($id)
    {
        return $this->bookRepository->find($id);
    }

    public function searchBooks(array $filters)
    {
        return $this->bookRepository->searchBooks($filters);
    }

    public function createBook(array $data)
    {
        return DB::transaction(function () use ($data) {
            if (isset($data['cover_image'])) {
                $data['cover_image'] = $this->imageHelper->upload($data['cover_image'], 'books');
            }

            return $this->bookRepository->create($data);
        });
    }

    public function updateBook($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $book = $this->bookRepository->find($id);

            if (isset($data['cover_image'])) {
                // Delete old image
                if ($book->cover_image) {
                    $this->imageHelper->delete($book->cover_image);
                }

                $data['cover_image'] = $this->imageHelper->upload($data['cover_image'], 'books');
            }

            return $this->bookRepository->update($id, $data);
        });
    }

    public function deleteBook($id)
    {
        return DB::transaction(function () use ($id) {
            $book = $this->bookRepository->find($id);

            if ($book->cover_image) {
                $this->imageHelper->delete($book->cover_image);
            }

            return $this->bookRepository->delete($id);
        });
    }

    public function updateStock($id, int $quantity, string $type = 'increase')
    {
        if ($type === 'increase') {
            return $this->bookRepository->increaseStock($id, $quantity);
        }

        return $this->bookRepository->decreaseStock($id, $quantity);
    }
}
