<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected User $user;

    protected array $permissions = [];

    protected bool $seed = true;

    protected bool $asCustomer = true;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

}
