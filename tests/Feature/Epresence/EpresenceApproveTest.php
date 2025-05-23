<?php

namespace Tests\Feature\Epresence;

use App\Enums\ApprovalStatus;
use App\Models\Epresence;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EpresenceApproveTest extends TestCase
{
    use RefreshDatabase;

    public function test_approve_successful()
    {
        $supervisor = User::factory()->create(['npp' => 'SUP123']);
        $user = User::factory()->create(['npp_supervisor' => 'SUP123']);
        $epresence = Epresence::factory()->create(['id_users' => $user->id, 'is_approve' => false]);

        $response = $this->actingAs($supervisor)->patchJson("api/epresence/{$epresence->id}/approve");

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'message' => 'Presence has been approved successfully',
                ]);
    }

    public function test_approve_unauthorized()
    {
        $anotherSupervisor = User::factory()->create(['npp' => 'SUP999']);
        $user = User::factory()->create(['npp_supervisor' => 'SUP123']);
        $epresence = Epresence::factory()->create(['id_users' => $user->id, 'is_approve' => false]);

        $response = $this->actingAs($anotherSupervisor)->patchJson("api/epresence/{$epresence->id}/approve");

        $response->assertStatus(403)
                ->assertJson([
                    'status' => 'error',
                    'message' => 'You are not authorized to approve this record',
                ]);
    }
}

