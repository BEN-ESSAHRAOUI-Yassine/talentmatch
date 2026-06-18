<?php

namespace App\Models;

use App\Enums\MessageRoleEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentConversationMessage extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'conversation_id',
        'agent',
        'content',
        'role',
        'user_id',
        'attachments',
        'tool_calls',
        'tool_results',
        'usage',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'role' => MessageRoleEnum::class,
        ];
    }

    public function agentConversation(): BelongsTo
    {
        return $this->belongsTo(AgentConversation::class, 'conversation_id');
    }

    public function setAgentConversationIdAttribute($value): void
    {
        $this->attributes['conversation_id'] = $value;
    }
}
