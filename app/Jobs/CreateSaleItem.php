<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class CreateSaleItem implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private Collection $items;

    private Sale $sale;

    public function __construct(Sale $sale, Collection $items)
    {
        $this->items = $items;
        $this->sale = $sale;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->items->each(function ($item) {
            if (is_null($item['product_id'])) {
                $product = Product::create([
                    'name' => $item['name'],
                    'unit_price' => $item['unit_price']
                ]);

                $item['product_id'] = $product->id;
            }

            $this->sale->items()->create($item);
        });
    }
}
