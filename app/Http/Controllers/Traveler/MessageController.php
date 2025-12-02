<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expert;
use App\Models\Message;
use App\Models\Traveler;

class MessageController extends Controller
{
    /**
     * Traveler inbox showing all expert conversations.
     */
    public function index()
    {
        $traveler = auth()->user()->traveler;

        // Conversation list: all experts the traveler has exchanged messages with
        $expertConversations = Expert::whereHas('user', function ($q) use ($traveler) {
                $q->whereHas('receivedMessages', fn($m) => $m->where('sender_id', $traveler->user_id))
                  ->orWhereHas('sentMessages', fn($m) => $m->where('receiver_id', $traveler->user_id));
            })
            ->with('user')
            ->get();

        return view('traveler.messages.index', [
            'expertConversations' => $expertConversations,
        ]);
    }

    /**
     * Show a conversation between the traveler and the given expert.
     */
    public function show(Expert $expert)
    {
        $traveler = auth()->user()->traveler;

        // Load all messages between the two
        $messages = Message::where(function ($q) use ($traveler, $expert) {
                $q->where('sender_id', $traveler->user_id)
                  ->where('receiver_id', $expert->user_id);
            })
            ->orWhere(function ($q) use ($traveler, $expert) {
                $q->where('sender_id', $expert->user_id)
                  ->where('receiver_id', $traveler->user_id);
            })
            ->orderBy('created_at')
            ->get();

        return view('traveler.messages.show', [
            'expert'   => $expert,
            'messages' => $messages,
        ]);
    }

    /**
     * Store a new message from traveler → expert
     */
    public function store(Request $request, Expert $expert)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $traveler = auth()->user()->traveler;

        Message::create([
            'sender_id'   => $traveler->user_id,
            'receiver_id' => $expert->user_id,
            'content'     => $request->content,
        ]);

        return back();
    }
}
