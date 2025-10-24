<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\BookListResource;
use App\Services\BookService;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;

class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function index(Request $request)
    {
        try {
            $filters = $request->only([
                'title', 'author_id', 'category_id',
                'min_price', 'max_price', 'available', 'per_page'
            ]);

            $books = $this->bookService->searchBooks($filters);

            return ResponseHelper::success(
                BookListResource::collection($books)->response()->getData(true),
                'Berhasil mengambil data buku'
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

    public function store(BookRequest $request)
    {
        try {
            $book = $this->bookService->createBook($request->validated());

            return ResponseHelper::success(
                new BookResource($book),
                'Berhasil menambahkan buku',
                201
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $book = $this->bookService->getBookById($id);

            return ResponseHelper::success(
                new BookResource($book->load(['author', 'publisher', 'category', 'reviews.customer'])),
                'Berhasil mengambil detail buku'
            );
        } catch (\Exception $e) {
            return ResponseHelper::notFound();
        }
    }

    public function update(BookRequest $request, $id)
    {
        try {
            $book = $this->bookService->updateBook($id, $request->validated());

            return ResponseHelper::success(
                new BookResource($book),
                'Berhasil mengupdate buku'
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->bookService->deleteBook($id);

            return ResponseHelper::success(null, 'Berhasil menghapus buku');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:increase,decrease'
        ]);

        try {
            $result = $this->bookService->updateStock(
                $id,
                $request->quantity,
                $request->type
            );

            if (!$result && $request->type === 'decrease') {
                return ResponseHelper::error('Stok tidak mencukupi');
            }

            return ResponseHelper::success(null, 'Berhasil update stok');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }
}
