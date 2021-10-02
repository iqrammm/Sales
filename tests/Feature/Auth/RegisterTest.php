<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_register_user()
    {
        $this
            ->post('/api/register', [
                'email' => 'test@test.com',
                'password' => 'testing123',
                'name' => 'Amirul Iqram',
                'password_confirmation' => 'testing123',
            ])
            ->assertSuccessful();
    }
}
