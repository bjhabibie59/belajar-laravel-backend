<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_number' => $this->order_number,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'customer_name' => $this->customer?->name,
            'total_amount' => $this->total_amount,
            'total_amount_formatted' => 'Rp ' . number_format($this->total_amount, 0, ',', '.'),
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'order_date' => $this->order_date?->format('Y-m-d H:i:s'),
            'order_date_formatted' => $this->order_date?->translatedFormat('d F Y H:i'),
            'items' => OrderItemResource::collection($this->whenLoaded('orderItems')),
            'items_count' => $this->when($this->relationLoaded('orderItems'), function () {
                return $this->orderItems->count();
            }),
            'total_items' => $this->when($this->relationLoaded('orderItems'), function () {
                return $this->orderItems->sum('quantity');
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    private function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu',
            'processing' => 'Diproses',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Unknown'
        };
    }
}
