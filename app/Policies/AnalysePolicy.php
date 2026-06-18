<?php

namespace App\Policies;

use App\Models\Analyse;
use App\Models\User;

class AnalysePolicy
{
    public function retry(User $user, Analyse $analyse): bool
    {
        return $user->id === $analyse->offre->user_id;
    }
}
