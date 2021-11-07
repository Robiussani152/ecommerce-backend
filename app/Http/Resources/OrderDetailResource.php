<?php

namespace App\Http\Resources;

use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'product_id' => $this->product_id,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
            'product' => new ProductResource($this->product),
        ];
    }
}
