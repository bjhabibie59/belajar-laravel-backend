<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'isbn' => $this->isbn,
            'title' => $this->title,
            'description' => $this->description,
            'pages' => $this->pages,
            'publication_year' => $this->publication_year,
            'price' => $this->price,
            'price_formatted' => 'Rp ' . number_format($this->price, 0, ',', '.'),
            'stock' => $this->stock,
            'is_available' => $this->stock > 0,
            'cover_image' => $this->cover_image ? asset('storage/' . $this->cover_image) : null,
            'average_rating' => $this->when($this->relationLoaded('reviews'), function () {
                return round($this->reviews->avg('rating') ?? 0, 1);
            }),
            'reviews_count' => $this->when($this->relationLoaded('reviews'), function () {
                return $this->reviews->count();
            }),
            'author' => new AuthorResource($this->whenLoaded('author')),
            'publisher' => new PublisherResource($this->whenLoaded('publisher')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
