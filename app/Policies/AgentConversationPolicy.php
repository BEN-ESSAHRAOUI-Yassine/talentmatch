<?php

namespace App\Policies;

use App\Models\AgentConversation;
use App\Models\Analyse;
use App\Models\User;

class AgentConversationPolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, AgentConversation $agentConversation): bool
    {
        return $user->id === $agentConversation->analyse->offre->user_id;
    }

    public function create(User $user, Analyse $analyse): bool
    {
        return $user->id === $analyse->offre->user_id;
    }

    public function update(User $user, AgentConversation $agentConversation): bool
    {
        return false;
    }

    public function delete(User $user, AgentConversation $agentConversation): bool
    {
        return false;
    }

    public function restore(User $user, AgentConversation $agentConversation): bool
    {
        return false;
    }

    public function forceDelete(User $user, AgentConversation $agentConversation): bool
    {
        return false;
    }
}
