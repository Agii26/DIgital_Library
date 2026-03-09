<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Models\Book;
use App\Models\DigitalBook;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('author', 'like', '%' . $request->search . '%')
                  ->orWhere('accession_no', 'like', '%' . $request->search . '%');
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $books = $query->latest()->paginate(15);
        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        return view('admin.books.create');
    }

    public function store(StoreBookRequest $request)
    {
        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
        }

        $book = Book::create([
            'accession_no' => $request->accession_no,
            'title'        => $request->title,
            'author'       => $request->author,
            'category'     => $request->category,
            'type'         => $request->type,
            'status'       => 'available',
            'price'        => $request->price,
            'cover_image'  => $coverPath,
            'description'  => $request->description,
        ]);

        if ($request->type === 'digital') {
            $filePath = $request->file('file_path')->store('digital-books', 'public');
            DigitalBook::create([
                'book_id'            => $book->id,
                'file_path'          => $filePath,
            ]);
        }

        return redirect()->route('admin.books.index')
            ->with('success', 'Book added successfully!');
    }

    public function show(Book $book)
    {
        $book->load('digitalBook', 'physicalBorrows.user');
        return view('admin.books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $book->load('digitalBook');
        return view('admin.books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:255',
            'category'    => 'nullable|string|max:255',
            'price'       => 'required|numeric|min:0',
            'status'      => 'required|in:available,borrowed,reserved,damaged,lost',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
            $book->update(['cover_image' => $coverPath]);
        }

        $book->update([
            'title'       => $request->title,
            'author'      => $request->author,
            'category'    => $request->category,
            'price'       => $request->price,
            'status'      => $request->status,
            'description' => $request->description,
        ]);

        if ($book->type === 'digital' && $book->digitalBook) {
            $book->digitalBook->update([
                'reading_time_limit' => $request->reading_time_limit,
            ]);
        }

        return redirect()->route('admin.books.index')
            ->with('success', 'Book updated successfully!');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return back()->with('success', 'Book deleted successfully!');
    }
}