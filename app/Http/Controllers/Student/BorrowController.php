<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\PhysicalBorrow;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BorrowController extends Controller
{
    public function index()
    {
        $borrows = PhysicalBorrow::with('book')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('student.borrows.index', compact('borrows'));
    }

    public function create()
    {
        $books = Book::where('type', 'physical')
            ->where('status', 'available')
            ->get();

        return view('student.borrows.create', compact('books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $user = Auth::user();

        // Check borrow limit
        $borrowLimit = Setting::get('student_borrow_limit', 2);
        $activeBorrows = PhysicalBorrow::where('user_id', $user->id)
            ->whereIn('status', ['reserved', 'approved', 'claimed'])
            ->count();

        if ($activeBorrows >= $borrowLimit) {
            return back()->withErrors(['book_id' => "You have reached your borrow limit of {$borrowLimit} books."]);
        }

        // Check if book is available
        $book = Book::findOrFail($request->book_id);
        if ($book->status !== 'available') {
            return back()->withErrors(['book_id' => 'This book is no longer available.']);
        }

        PhysicalBorrow::create([
            'user_id'     => $user->id,
            'book_id'     => $book->id,
            'status'      => 'reserved',
            'reserved_at' => now(),
        ]);

        $book->update(['status' => 'reserved']);

        return redirect()->route('student.borrows.index')
            ->with('success', 'Book reserved successfully! Please wait for admin approval.');
    }
    public function books(Request $request)
    {
        $query = Book::where('type', 'physical');

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('author', 'like', '%' . $request->search . '%')
                ->orWhere('accession_no', 'like', '%' . $request->search . '%');
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $books = $query->latest()->paginate(12);
        $categories = Book::where('type', 'physical')->distinct()->pluck('category')->filter();

        return view('student.books.index', compact('books', 'categories'));
    }
}