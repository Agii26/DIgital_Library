<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get all conversations (unique users messaged with)
        $conversations = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($message) use ($user) {
                return $message->sender_id === $user->id
                    ? $message->receiver_id
                    : $message->sender_id;
            })
            ->map(function($messages) {
                return $messages->first();
            });

        $users = User::where('id', '!=', Auth::id())
            ->where('is_active', true)
            ->get();

        $unreadCount = Message::where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return view('messages.index', compact('conversations', 'users', 'unreadCount'));
    }

    public function show(User $user)
    {
        $authUser = Auth::user();

        // Mark messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $authUser->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = Message::where(function($q) use ($authUser, $user) {
                $q->where('sender_id', $authUser->id)
                  ->where('receiver_id', $user->id);
            })
            ->orWhere(function($q) use ($authUser, $user) {
                $q->where('sender_id', $user->id)
                  ->where('receiver_id', $authUser->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $users = User::where('id', '!=', $authUser->id)
            ->where('is_active', true)
            ->get();

        $conversations = Message::where('sender_id', $authUser->id)
            ->orWhere('receiver_id', $authUser->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($message) use ($authUser) {
                return $message->sender_id === $authUser->id
                    ? $message->receiver_id
                    : $message->sender_id;
            })
            ->map(function($messages) {
                return $messages->first();
            });

        return view('messages.show', compact('messages', 'user', 'users', 'conversations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'body'        => 'required|string|max:1000',
        ]);

        Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'body'        => $request->body,
        ]);

        return redirect()->route('messages.show', $request->receiver_id);
    }
}