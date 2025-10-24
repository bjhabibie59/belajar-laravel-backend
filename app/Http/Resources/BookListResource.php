<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Resource sederhana untuk listing buku tanpa relasi lengkap
        return [
            'id' => $this->id,
            'isbn' => $this->isbn,
            'title' => $this->title,
            'price' => $this->price,
            'price_formatted' => 'Rp ' . number_format($this->price, 0, ',', '.'),
            'stock' => $this->stock,
            'is_available' => $this->stock > 0,
            'cover_image' => $this->cover_image ? asset('storage/' . $this->cover_image) : null,
            'author_name' => $this->author?->name,
            'category_name' => $this->category?->name,
            'average_rating' => round($this->reviews->avg('rating') ?? 0, 1),
        ];
    }
}
