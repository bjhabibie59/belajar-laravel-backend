<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'total_orders' => $this->collection->count(),
                'total_amount' => $this->collection->sum('total_amount'),
                'total_amount_formatted' => 'Rp ' . number_format($this->collection->sum('total_amount'), 0, ',', '.'),
                'status_summary' => [
                    'pending' => $this->collection->where('status', 'pending')->count(),
                    'processing' => $this->collection->where('status', 'processing')->count(),
                    'completed' => $this->collection->where('status', 'completed')->count(),
                    'cancelled' => $this->collection->where('status', 'cancelled')->count(),
                ],
            ],
        ];
    }
}
