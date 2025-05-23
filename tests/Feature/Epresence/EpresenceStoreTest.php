<?php

namespace Tests\Feature\Epresence;

use App\Models\User;
use App\Models\Epresence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;
use Illuminate\Support\Facades\Gate;

class EpresenceStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_successful()
    {
        $user = User::factory()->create();

        Gate::shouldReceive('authorize')
            ->once()
            ->with('create', Epresence::class)
            ->andReturn(true);

        $payload = [
            'type' => 'IN',
            'waktu' => now()->format('Y-m-d H:i:s'),
        ];

        $response = $this->actingAs($user)->postJson('api/epresence', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Record created successfully',
                'data' => [
                    'type' => 'IN',
                    'is_approve' => false,
                ],
            ]);

        $this->assertDatabaseHas('epresence', [
            'id_users' => $user->id,
            'type' => strtolower($payload['type']),
            'waktu' => $payload['waktu'],
            'is_approve' => false,
        ]);
    }

    public function test_store_unauthorized()
    {
        $user = User::factory()->create();

        Gate::shouldReceive('authorize')
            ->once()
            ->with('create', Epresence::class)
            ->andThrow(\Illuminate\Auth\Access\AuthorizationException::class);

        $payload = [
            'type' => 'IN',
            'waktu' => now()->format('Y-m-d H:i:s'),
        ];

        $response = $this->actingAs($user)->postJson('api/epresence', $payload);

        $response->assertStatus(403)
            ->assertJson([
                'status' => 'error',
                'message' => 'You are not authorized to perform this action',
            ]);
    }

    public function test_store_validation_error()
    {
        $user = User::factory()->create();

        $this->actingAs($user);    

        Gate::partialMock()
            ->shouldReceive('authorize')
            ->zeroOrMoreTimes()
            ->andReturnTrue();

            $response = $this->postJson('api/epresence', [
                'type' => 'INVALID_TYPE', 
                'waktu' => now()->format('Y-m-d H:i:s'),
            ]);
        
            $response->assertStatus(422)
                     ->assertJson([
                         'status' => 'error',
                         'message' => 'The provided data is invalid',
                     ]);        
    }

    public function test_store_rule_one_in_one_out_per_day_fails()
    {
        $user = User::factory()->create();

        Gate::partialMock()
            ->shouldReceive('authorize')
            ->zeroOrMoreTimes()
            ->andReturnTrue();

        Epresence::create([
            'id_users' => $user->id,
            'type' => 'in',
            'waktu' => now()->format('Y-m-d H:i:s'),
            'is_approve' => false,
        ]);

        $payload = [
            'type' => 'IN',
            'waktu' => now()->format('Y-m-d H:i:s'),
        ];

        $response = $this->actingAs($user)->postJson('api/epresence', $payload);

        $response->assertStatus(422)
            ->assertJsonFragment([
                'status' => 'error',
                'message' => 'The provided data is invalid',
            ]);

        $this->assertArrayHasKey('waktu', $response->json('errors'));
    }
}
