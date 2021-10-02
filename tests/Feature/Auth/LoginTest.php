<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_example()
    {
        $this->user = User::factory()->create(
            [
                'email' => 'test@test.com',
                'password' => bcrypt('testing123')
            ]
        );

        $this
            ->post('/api/login', [
                'email' => 'test@test.com',
                'password' => 'testing123',
            ])
            ->assertSuccessful()
            ->assertJsonStructure([
                'status',
                'message',
                'data'
            ]);
    }
}
