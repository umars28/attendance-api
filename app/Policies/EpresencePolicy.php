<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Epresence;

class EpresencePolicy
{
    public function approve(User $supervisor, Epresence $epresence): bool
    {
        return $epresence->user->npp_supervisor === $supervisor->npp;
    }

    public function create(User $user): bool
    {
        return $user->npp_supervisor !== null;
    }

}

