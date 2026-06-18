<?php

namespace App\Policies;

use App\Models\Analyse;
use App\Models\User;

class AnalysePolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Analyse $analyse): bool
    {
        return $user->id === $analyse->offre->user_id;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Analyse $analyse): bool
    {
        return false;
    }

    public function delete(User $user, Analyse $analyse): bool
    {
        return false;
    }

    public function restore(User $user, Analyse $analyse): bool
    {
        return false;
    }

    public function forceDelete(User $user, Analyse $analyse): bool
    {
        return false;
    }
}
