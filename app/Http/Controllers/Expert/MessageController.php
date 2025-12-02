<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Traveler;
use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{
    /**
     * @param \App\Models\Traveler $traveler
     */
    public function index(Traveler $traveler)
    {
        $expert = auth()->user(); // Expert user

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

        return view('expert.travelers.messages', [
            'traveler' => $traveler,
            'messages' => $messages,
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Traveler $traveler
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
