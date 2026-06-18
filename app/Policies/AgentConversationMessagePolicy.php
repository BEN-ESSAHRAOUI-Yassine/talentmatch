<?php

namespace App\Policies;

use App\Models\AgentConversation;
use App\Models\AgentConversationMessage;
use App\Models\User;

class AgentConversationMessagePolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, AgentConversationMessage $agentConversationMessage): bool
    {
        return false;
    }

    public function create(User $user, AgentConversation $agentConversation): bool
    {
        return $user->id === $agentConversation->analyse->offre->user_id;
    }

    public function update(User $user, AgentConversationMessage $agentConversationMessage): bool
    {
        return false;
    }

    public function delete(User $user, AgentConversationMessage $agentConversationMessage): bool
    {
        return false;
    }

    public function restore(User $user, AgentConversationMessage $agentConversationMessage): bool
    {
        return false;
    }

    public function forceDelete(User $user, AgentConversationMessage $agentConversationMessage): bool
    {
        return false;
    }
}
