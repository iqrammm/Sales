<?php

namespace Tests\Feature\Sale;

use App\Constants\MediaCollection;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CreateSaleTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->user);
    }

    public function test_can_create_sale()
    {
        $product = Product::create([
            'name' => 'test',
            'unit_price' => 100
        ]);

        $data = [
            'ref_num' => 'INV1001',
            'date' => '2021-01-01',
            'total_amount' => 200,
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
            ->assertSuccessful()
            ->assertJsonFragment([
                'ref_num' => 'INV1001',
                'total_amount' => [
                    'amount' => 200,
                    'currency' => 'MYR'
                ],
                'date' => Carbon::parse('2021-01-01')->toDateTimeString(),
            ])
            ->assertJsonFragment([
                'product_name' => 'test',
                'unit_price' => [
                    'amount' => 100,
                    'currency' => 'MYR'
                ],
                'quantity' => 2,
                'total_amount' => [
                    'amount' => 200,
                    'currency' => 'MYR'
                ]
            ]);

    }

    public function test_sale_item_without_product_id()
    {
        $data = [
            'ref_num' => 'INV1001',
            'date' => '2021-01-01',
            'total_amount' => 246,
            'items' => [
                [
                    'product_id' => null,
                    'name' => 'Testing create',
                    'unit_price' => 123,
                    'quantity' => 2
                ]
            ]
        ];

        $this
            ->postJson('/api/sales', $data)
            ->assertSuccessful()
            ->assertJsonFragment([
                'ref_num' => 'INV1001',
                'total_amount' => [
                    'amount' => 246,
                    'currency' => 'MYR'
                ],
                'date' => Carbon::parse('2021-01-01')->toDateTimeString(),
            ])
            ->assertJsonFragment([
                'product_name' => 'Testing create',
                'unit_price' => [
                    'amount' => 123,
                    'currency' => 'MYR'
                ],
                'quantity' => 2,
                'total_amount' => [
                    'amount' => 246,
                    'currency' => 'MYR'
                ]
            ]);
    }

    public function test_image_can_be_attached_to_sale()
    {
        $file = UploadedFile::fake()->create('image_test.jpeg', 1000);

        Sale::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'user_id' => 1,
            'ref_num' =>'tet',
            'date' => now(),
        ]);

        $sale = Sale::query()->first();

        $sale
            ->addMedia($file)
            ->toMediaCollection(MediaCollection::PRODUCT_IMAGE);

        $this->assertEquals(
            'image_test.jpeg',
            $sale->getMedia(MediaCollection::PRODUCT_IMAGE)->last()->file_name
        );
    }
}
