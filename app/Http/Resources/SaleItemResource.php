<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'product_name' => $this->ref_num,
            'unit_price' => $this->total_amount,
            'quantity' => $this->date,
            'total_amount' => $this->items,
        ];
    }
}
