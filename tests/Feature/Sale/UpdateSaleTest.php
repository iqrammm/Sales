<?php

namespace Tests\Feature\Sale;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateSaleTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->user);
    }

    public function test_can_update_item()
    {
        //Create The Sale

        $product = Product::create([
            'name' => 'test',
            'unit_price' => 100
        ]);

        $refNo = 'INV1002';

        $data = [
            'ref_num' => $refNo,
            'date' => '2021-01-01',
            'total_amount' => 100,
            'items' => [
                [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'unit_price' => $product->unit_price->getAmount(),
                    'quantity' => 2
                ]
            ]
        ];

        $this
            ->postJson('/api/sales', $data)
            ->assertSuccessful();

        $saleId = Sale::first()->id;

        $data = [
            'ref_num' => $refNo,
            'date' => '2021-01-01',
            'total_amount' => 300,
            'items' => [
                [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'unit_price' => $product->unit_price->getAmount(),
                    'quantity' => 3
                ]
            ]
        ];

        $this
            ->patchJson(route('api.sales.update', ['sale' => $saleId]), $data)
            ->assertSuccessful()
            ->assertJsonFragment([
                'ref_num' => 'INV1002',
                'total_amount' => [
                    'amount' => 300,
                    'currency' => 'MYR'
                ],
            ]);
    }
}
