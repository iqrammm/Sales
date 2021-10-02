<?php

namespace App\Observers;

use App\Models\Sale;

class SaleObserver
{
    /**
     * Handle the Sale "deleted" event.
     *
     * @param Sale $sale
     * @return void
     */
    public function deleted(Sale $sale)
    {
        $sale->items()->delete();
    }
}
