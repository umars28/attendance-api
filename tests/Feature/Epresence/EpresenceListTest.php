<?php

namespace Tests\Feature\Epresence;

use Tests\TestCase;
use App\Models\User;
use App\Models\Epresence;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EpresenceListTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_epresence_list_for_user_without_subordinates()
    {
        $user = User::factory()->create();

        Epresence::factory()->create([
            'id_users' => $user->id,
            'type' => 'in',
            'waktu' => now()->subHours(2),
            'is_approve' => true,
        ]);
        Epresence::factory()->create([
            'id_users' => $user->id,
            'type' => 'out',
            'waktu' => now()->subHour(),
            'is_approve' => false,
        ]);

        $response = $this->actingAs($user)->getJson('api/epresence');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Success get data',
                 ]);

        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($user->id, $response->json('data')[0]['id_user']);
    }

    public function test_index_returns_epresence_list_for_user_with_subordinates()
    {
        $supervisor = User::factory()->create();

        $subordinate = User::factory()->create([
            'npp_supervisor' => $supervisor->npp,
        ]);

        Epresence::factory()->create([
            'id_users' => $subordinate->id,
            'type' => 'in',
            'waktu' => now()->startOfDay()->addHours(8), 
            'is_approve' => true,
        ]);
        
        Epresence::factory()->create([
            'id_users' => $subordinate->id,
            'type' => 'out',
            'waktu' => now()->startOfDay()->addHours(17),
            'is_approve' => true,
        ]);        

        $supervisor->setRelation('subordinates', collect([$subordinate]));

        $response = $this->actingAs($supervisor)->getJson('api/epresence');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Success get data',
                 ]);

        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($subordinate->id, $response->json('data')[0]['id_user']);
    }

    public function test_index_server_error_handling()
    {
        $user = User::factory()->make();

        $this->mock(\App\Http\Services\EpresenceService::class, function ($mock) use ($user) {
            $mock->shouldReceive('getList')
                 ->with($user)
                 ->andThrow(new \Exception('Unexpected error'));
        });

        $response = $this->actingAs($user)->getJson('api/epresence');

        $response->assertStatus(500)
                 ->assertJson([
                     'status' => 'error',
                     'message' => 'An unexpected server error occurred. Please try again later'
                 ]);
    }
}
