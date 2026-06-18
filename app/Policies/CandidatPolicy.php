<?php

namespace App\Policies;

use App\Models\Candidat;
use App\Models\Offre;
use App\Models\User;

class CandidatPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Candidat $candidat): bool
    {
        return $user->id === $candidat->analyse->offre->user_id;
    }

    public function create(User $user, Offre $offre): bool
    {
        return $user->id === $offre->user_id;
    }

    public function update(User $user, Candidat $candidat): bool
    {
        return $user->id === $candidat->analyse->offre->user_id;
    }

    public function delete(User $user, Candidat $candidat): bool
    {
        return $user->id === $candidat->analyse->offre->user_id;
    }

    public function restore(User $user, Candidat $candidat): bool
    {
        return false;
    }

    public function forceDelete(User $user, Candidat $candidat): bool
    {
        return false;
    }
}
