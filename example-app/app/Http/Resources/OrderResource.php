<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'customer' => [
                'id' => $this->customer->id,
                'name' => $this->customer->name,
                'phone' => $this->customer->phone,
            ],

            'creator' => [
                'id' => $this->creator?->id,
                'name' => $this->creator?->name,
            ],

            'approver' => $this->approver ? [
                'id' => $this->approver->id,
                'name' => $this->approver->name,
            ] : null,

            'state' => class_basename($this->state),

            'total_amount' => (float) $this->total_amount,

            'items' => $this->items->map(function ($item) {
                return [
                    'product_id' => $item->product->id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => (float) $item->price,
                ];
            }),

            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}