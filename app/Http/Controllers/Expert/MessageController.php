<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Traveler;
use App\Models\Message;

class MessageController extends Controller
{
    /**
     * Expert Inbox — list all travelers they’ve messaged
     */
    public function inbox()
    {
        $expert = auth()->user();

        // Get all travelers who have exchanged messages with this expert
        $threads = Traveler::whereHas('user.sentMessages', function ($q) use ($expert) {
                $q->where('receiver_id', $expert->id);
            })
            ->orWhereHas('user.receivedMessages', function ($q) use ($expert) {
                $q->where('sender_id', $expert->id);
            })
            ->with([
                'user',
                'user.sentMessages' => fn ($q) => $q->where('receiver_id', $expert->id),
                'user.receivedMessages' => fn ($q) => $q->where('sender_id', $expert->id),
            ])
            ->get()
            ->map(function ($traveler) use ($expert) {
                $traveler->last_message = Message::where(function ($q) use ($expert, $traveler) {
                        $q->where('sender_id', $expert->id)
                          ->where('receiver_id', $traveler->user_id);
                    })
                    ->orWhere(function ($q) use ($expert, $traveler) {
                        $q->where('sender_id', $traveler->user_id)
                          ->where('receiver_id', $expert->id);
                    })
                    ->latest()
                    ->first();

                return $traveler;
            })
            ->sortByDesc(fn ($t) => $t->last_message?->created_at)
            ->values();

        return view('expert.messages.index', [
            'threads' => $threads,
        ]);
    }


    /**
     * Show a specific message thread between Expert and Traveler
     */
    public function show(Traveler $traveler)
    {
        $expert = auth()->user();

        $messages = Message::where(function ($q) use ($expert, $traveler) {
                $q->where('sender_id', $expert->id)
                  ->where('receiver_id', $traveler->user_id);
            })
            ->orWhere(function ($q) use ($expert, $traveler) {
                $q->where('sender_id', $traveler->user_id)
                  ->where('receiver_id', $expert->id);
            })
            ->orderBy('created_at')
            ->get();

        return view('expert.messages.show', [
            'traveler' => $traveler,
            'messages' => $messages,
        ]);
    }


    /**
     * Send a message from Expert → Traveler
     */
    public function store(Request $request, Traveler $traveler)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $traveler->user_id,
            'content'     => $request->content,
        ]);

        return back();
    }
}
