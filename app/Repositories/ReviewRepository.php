<?php

namespace App\Repositories;

use App\Models\Review;

class ReviewRepository extends BaseRepository
{
    public function __construct(Review $model)
    {
        parent::__construct($model);
    }

    public function getBookReviews($bookId)
    {
        return $this->model->where('book_id', $bookId)
            ->with('customer')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getCustomerReviews($customerId)
    {
        return $this->model->where('customer_id', $customerId)
            ->with('book')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
