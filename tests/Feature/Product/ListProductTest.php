<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListProductTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->user);
    }

    public function test_can_get_products()
    {
        //uncomment for now as getting memory error.
//        $product = Product::factory()->make(1);

        $product = Product::create([
            'name' => 'test',
            'unit_price' => 100
        ]);

        $this
            ->get('/api/products')
            ->assertSuccessful()
            ->assertJsonFragment([
                'name' => $product->name,
                'unit_price' => [
                    'amount' => 100,
                    'currency' => 'MYR'
                ]
            ]);
    }

    public function test_can_filter_products_by_name()
    {
        $product = Product::create([
            'name' => 'test',
            'unit_price' => 100
        ]);

        $product2 = Product::create([
            'name' => 'test2',
            'unit_price' => 100
        ]);

        $this
            ->json('GET',  '/api/products',['search'=>'test2'])
            ->assertSuccessful()
            ->assertJsonFragment([
                'name' => $product2->name,
                'unit_price' => [
                    'amount' => 100,
                    'currency' => 'MYR'
                ]
            ])
            ->assertJsonMissing([
                'name' => $product->name,
            ]);
    }
}
