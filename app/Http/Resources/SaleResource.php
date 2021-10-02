<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
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
            'ref_num' => $this->ref_num,
            'total_amount' => $this->total_amount,
            'date' => $this->date,
            'items' => $this->items,
        ];
    }
}
