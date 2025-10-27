<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ReviewController;

Route::prefix('v1')->group(function () {

    // Books
    Route::apiResource('books', BookController::class);
    Route::post('books/{id}/update-stock', [BookController::class, 'updateStock']);

    // Categories
    Route::apiResource('categories', CategoryController::class);

    // Customers
    Route::apiResource('customers', CustomerController::class);
    Route::get('customers/top/list', [CustomerController::class, 'topCustomers']);

    // Orders
    Route::apiResource('orders', OrderController::class)->only(['store', 'show']);
    Route::put('orders/{id}/status', [OrderController::class, 'updateStatus']);
    Route::put('orders/{id}/cancel', [OrderController::class, 'cancel']);
    Route::get('customers/{customerId}/orders', [OrderController::class, 'customerOrders']);

    // Reviews
    Route::post('reviews', [ReviewController::class, 'store']);
    Route::put('reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('reviews/{id}', [ReviewController::class, 'destroy']);
    Route::get('books/{bookId}/reviews', [ReviewController::class, 'bookReviews']);
    Route::get('customers/{customerId}/reviews', [ReviewController::class, 'customerReviews']);

});
