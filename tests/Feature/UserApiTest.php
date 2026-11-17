<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    protected function beforeRefreshingDatabase(): void
    {
        if (
            config('database.default') !== 'sqlite'
            || config('database.connections.sqlite.database') !== ':memory:'
            || config('database.connections.sqlite.url')
        ) {
            throw new RuntimeException('User API tests require an in-memory SQLite database. Run php artisan config:clear.');
        }
    }

    public function test_guest_receives_a_json_unauthorized_response(): void
    {
        $this->get('/api/user')
            ->assertUnauthorized()
            ->assertHeader('Content-Type', 'application/json');
    }

    public function test_valid_bearer_token_returns_only_public_user_fields(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $this->withToken($token)->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('id', $user->id)
            ->assertJsonPath('email', $user->email)
            ->assertJsonMissingPath('password')
            ->assertJsonMissingPath('remember_token');
    }

    public function test_revoked_bearer_token_is_rejected(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token');
        $token->accessToken->delete();

        $this->withToken($token->plainTextToken)->getJson('/api/user')
            ->assertUnauthorized();
    }

    public function test_expired_bearer_token_is_rejected(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token', ['*'], now()->subMinute());

        $this->withToken($token->plainTextToken)->getJson('/api/user')
            ->assertUnauthorized();
    }
}
