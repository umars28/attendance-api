<?php

namespace Database\Factories;

use App\Models\Epresence;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\ApprovalStatus;

class EpresenceFactory extends Factory
{
    protected $model = Epresence::class;

    public function definition()
    {
        return [
            'id_users' => User::factory(),
            'type' => $this->faker->randomElement(['in', 'out']),
            'waktu' => $this->faker->dateTimeBetween('-1 week', 'now')->format('Y-m-d H:i:s'),
            'is_approve' => ApprovalStatus::False,
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Epresence $epresence) {
        })->afterCreating(function (Epresence $epresence) {
        });
    }
}
