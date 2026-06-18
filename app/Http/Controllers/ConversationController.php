<?php

namespace App\Http\Controllers;

use App\Models\AgentConversation;
use App\Models\Analyse;

class ConversationController extends Controller
{
    public function store(Analyse $analyse)
    {
        $this->authorize('create', [AgentConversation::class, $analyse]);

        $analyse->loadMissing('candidat');

        $conversation = AgentConversation::create([
            'analyse_id' => $analyse->id,
            'user_id' => auth()->id(),
            'title' => 'Conversation - '.$analyse->candidat->name,
        ]);

        return redirect()->route('conversations.show', [$analyse, $conversation]);
    }

    public function show(Analyse $analyse, AgentConversation $conversation)
    {
        $this->authorize('view', $conversation);

        $analyse->loadMissing('candidat');
        $conversation->load('messages');

        return view('conversations.show', compact('analyse', 'conversation'));
    }
}
