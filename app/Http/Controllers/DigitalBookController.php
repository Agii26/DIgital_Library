<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\DigitalSession;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DigitalBookController extends Controller
{
    public function index()
    {
        $books = Book::where('type', 'digital')
            ->where('status', 'available')
            ->with('digitalBook')
            ->latest()
            ->paginate(12);

        return view('digital.index', compact('books'));
    }

    public function read(Book $book)
    {
        if ($book->type !== 'digital' || !$book->digitalBook) {
            abort(404);
        }

        $user = Auth::user();

        // Check for active session
        $activeSession = DigitalSession::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->first();

        if (!$activeSession) {
            // Create new session
            $readingTime = (int) Setting::get('digital_reading_time', 60);

            $activeSession = DigitalSession::create([
                'user_id'    => $user->id,
                'book_id'    => $book->id,
                'started_at' => now(),
                'expires_at' => now()->addMinutes($readingTime),
                'is_active'  => true,
            ]);
        }

        $remainingSeconds = (int) now()->diffInSeconds($activeSession->expires_at, false);

        if ($remainingSeconds <= 0) {
            $activeSession->update(['is_active' => false]);

            // Start fresh session
            $readingTime = (int) Setting::get('digital_reading_time', 60);
            $activeSession = DigitalSession::create([
                'user_id'    => $user->id,
                'book_id'    => $book->id,
                'started_at' => now(),
                'expires_at' => now()->addMinutes($readingTime),
                'is_active'  => true,
            ]);

            $remainingSeconds = $readingTime * 60;
        }

        return view('digital.read', compact('book', 'activeSession', 'remainingSeconds'));
    }

    public function expire(DigitalSession $session)
    {
        if ($session->user_id === Auth::id()) {
            $session->update(['is_active' => false]);
        }

        return response()->json(['success' => true]);
    }
}