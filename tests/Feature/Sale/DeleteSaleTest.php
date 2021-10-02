<?php

namespace Tests\Feature\Sale;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteSaleTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->user);
    }

    public function test_can_delete_sale_test()
    {
        //Create The Sale

        $product = Product::create([
            'name' => 'test',
            'unit_price' => 100
        ]);

        $refNo = 'INV1001';

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

        $this
            ->deleteJson(route('api.sales.delete', ['sale' => $saleId]))
            ->assertSuccessful()
            ->assertJsonFragment([
                "Sale ${refNo} deleted"
            ]);

        $this->assertTrue(Sale::all()->isEmpty());
        $this->assertTrue(SaleItem::all()->isEmpty());
    }
}
