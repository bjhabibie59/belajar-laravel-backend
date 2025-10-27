<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use App\Helpers\ResponseHelper;


class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        try {
            $categories = $this->categoryService->getAllCategories();
            return ResponseHelper::success(
                CategoryResource::collection($categories),
                'Berhasil mengambil data kategori'
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

    public function store(CategoryRequest $request)
    {
        try {
            $category = $this->categoryService->createCategory($request->validated());
            return ResponseHelper::success(
                new CategoryResource($category),
                'Berhasil menambahkan kategori',
                201
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $category = $this->categoryService->getCategoryById($id);
            return ResponseHelper::success(
                new CategoryResource($category->load('books')),
                'Berhasil mengambil detail kategori'
            );
        } catch (\Exception $e) {
            return ResponseHelper::notFound();
        }
    }

    public function update(CategoryRequest $request, $id)
    {
        try {
            $category = $this->categoryService->updateCategory($id, $request->validated());
            return ResponseHelper::success(
                new CategoryResource($category),
                'Berhasil mengupdate kategori'
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->categoryService->deleteCategory($id);
            return ResponseHelper::success(null, 'Berhasil menghapus kategori');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

}
