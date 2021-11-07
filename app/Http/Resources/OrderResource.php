<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'invoice_no' => $this->invoice_no,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'instruction' => $this->instruction,
            'customer' => $this->whenLoaded('customer'),
            'details' => OrderDetailResource::collection($this->details),
            'histories' => $this->histories
        ];
    }
}
