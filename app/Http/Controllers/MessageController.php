<?php

namespace App\Http\Controllers;

use App\Ai\Agents\AnalyseConversationAgent;
use App\Http\Requests\StoreMessageRequest;
use App\Models\AgentConversation;
use App\Models\AgentConversationMessage;
use App\Models\Analyse;

class MessageController extends Controller
{
    public function store(Analyse $analyse, AgentConversation $conversation, StoreMessageRequest $request)
    {
        $this->authorize('create', [AgentConversationMessage::class, $conversation]);

        $analyse->loadMissing('candidat', 'offre');

        $agent = new AnalyseConversationAgent($analyse);
        $agent
            ->continue($conversation->id, as: auth()->user())
            ->prompt($request->input('content'));

        $conversation->touch();

        return redirect()->route('conversations.show', [$analyse, $conversation]);
    }
}
