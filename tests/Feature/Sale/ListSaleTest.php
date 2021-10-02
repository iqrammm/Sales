<?php

namespace Tests\Feature\Sale;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListSaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_listing_of_sales()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
