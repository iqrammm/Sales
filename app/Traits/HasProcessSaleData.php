<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait HasProcessSaleData
{
    private function processData(Request $request): array
    {
        return [
            'saleData' => [
                'user_id' => request()->user()->id,
                'ref_num' => $request->ref_num,
                'date' => $request->date,
                'total_amount' => $request->total_amount
            ],
            'saleItemData' => $request->items
        ];

    }
}
