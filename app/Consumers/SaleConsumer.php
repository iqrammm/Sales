<?php

namespace App\Consumers;

use App\Events\SaleIsCreated;
use App\Events\SaleIsDeleted;
use App\Events\SaleIsUpdated;
use App\Jobs\CreateSaleItem;
use App\Models\Sale;
use EventSauce\LaravelEventSauce\Consumer;
use Supplycart\Money\Money;

final class SaleConsumer extends Consumer
{
    public function handleSaleIsCreated(SaleIsCreated $saleIsCreated)
    {
        $payload = $saleIsCreated->toPayload();

        $saleData = collect($payload['saleData']);

        $itemData = $payload['saleItemData'];

        $sale = Sale::create([
            'user_id' => $saleData->get('user_id'),
            'uuid' => $payload['uuid'],
            'ref_num' => $saleData->get('ref_num'),
            'total_amount' => $saleData->get('total_amount'),
            'date' => $saleData->get('date'),
        ]);

        $mappedItemData = collect($itemData)->map(function ($saleItem) {
            return [
                'product_id' => $saleItem['product_id'],
                'name' => $saleItem['name'],
                'unit_price' => $saleItem['unit_price'],
                'quantity' => $quantity = 2,
                'total_amount' => Money::of($saleItem['unit_price'])->multiply($quantity)->getAmount()
            ];
        });

        CreateSaleItem::dispatch($sale, $mappedItemData);
    }

    public function handleSaleIsUpdated(SaleIsUpdated $saleIsUpdated)
    {
        $payload = $saleIsUpdated->toPayload();

        $saleData = collect($payload['saleData']);

        $itemData = $payload['saleItemData'];

        $sale = Sale::query()
            ->firstWhere('uuid', $payload['uuid']);

        $sale->update([
            'user_id' => $saleData->get('user_id'),
            'ref_num' => $saleData->get('ref_num'),
            'total_amount' => $saleData->get('total_amount'),
            'date' => $saleData->get('date'),
        ]);

        $sale->items()->delete();

        $mappedItemData = collect($itemData)->map(function ($saleItem) {
            return [
                'product_id' => $saleItem['product_id'],
                'name' => $saleItem['name'],
                'unit_price' => $saleItem['unit_price'],
                'quantity' => $quantity = 2,
                'total_amount' => Money::of($saleItem['unit_price'])->multiply($quantity)->getAmount()
            ];
        });

        CreateSaleItem::dispatch($sale, $mappedItemData);
    }

    public function handleSaleIsDeleted(SaleIsDeleted $saleIsDeleted)
    {
        $payload = $saleIsDeleted->toPayload();

        Sale::query()->firstWhere('uuid', $payload['uuid'])->delete();
    }
}
