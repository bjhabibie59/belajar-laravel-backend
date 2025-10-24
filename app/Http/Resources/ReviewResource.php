<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'book' => new BookListResource($this->whenLoaded('book')),
            'book_title' => $this->book?->title,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'customer_name' => $this->customer?->name,
            'rating' => $this->rating,
            'rating_stars' => str_repeat('⭐', $this->rating),
            'comment' => $this->comment,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'created_at_human' => $this->created_at?->diffForHumans(),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
