<?php

namespace App\Services;

use App\Repositories\ReviewRepository;
use App\Repositories\BookRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class ReviewService
{
    protected $reviewRepository;
    protected $bookRepository;

    public function __construct(
        ReviewRepository $reviewRepository,
        BookRepository $bookRepository
    ) {
        $this->reviewRepository = $reviewRepository;
        $this->bookRepository = $bookRepository;
    }

    public function createReview(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Validasi buku exists
            $this->bookRepository->find($data['book_id']);

            return $this->reviewRepository->create($data);
        });
    }

    public function getBookReviews($bookId)
    {
        return $this->reviewRepository->getBookReviews($bookId);
    }

    public function getCustomerReviews($customerId)
    {
        return $this->reviewRepository->getCustomerReviews($customerId);
    }

    public function updateReview($id, array $data)
    {
        return $this->reviewRepository->update($id, $data);
    }

    public function deleteReview($id)
    {
        return $this->reviewRepository->delete($id);
    }
}
