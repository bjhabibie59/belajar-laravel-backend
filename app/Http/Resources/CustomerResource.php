<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'orders_count' => $this->when($this->relationLoaded('orders'), function () {
                return $this->orders->count();
            }),
            'total_spent' => $this->when($this->relationLoaded('orders'), function () {
                return $this->orders->where('status', 'completed')->sum('total_amount');
            }),
            'orders' => OrderResource::collection($this->whenLoaded('orders')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
