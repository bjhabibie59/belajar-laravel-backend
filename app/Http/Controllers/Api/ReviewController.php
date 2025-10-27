<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Services\ReviewService;
use App\Helpers\ResponseHelper;

class ReviewController extends Controller
{
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function store(ReviewRequest $request)
    {
        try {
            $review = $this->reviewService->createReview($request->validated());
            return ResponseHelper::success(
                new ReviewResource($review),
                'Berhasil menambahkan review',
                201
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

    public function bookReviews($bookId)
    {
        try {
            $reviews = $this->reviewService->getBookReviews($bookId);
            return ResponseHelper::success(
                ReviewResource::collection($reviews),
                'Berhasil mengambil review buku'
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

    public function customerReviews($customerId)
    {
        try {
            $reviews = $this->reviewService->getCustomerReviews($customerId);
            return ResponseHelper::success(
                ReviewResource::collection($reviews),
                'Berhasil mengambil review pelanggan'
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

    public function update(ReviewRequest $request, $id)
    {
        try {
            $review = $this->reviewService->updateReview($id, $request->validated());
            return ResponseHelper::success(
                new ReviewResource($review),
                'Berhasil mengupdate review'
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->reviewService->deleteReview($id);
            return ResponseHelper::success(null, 'Berhasil menghapus review');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

}
