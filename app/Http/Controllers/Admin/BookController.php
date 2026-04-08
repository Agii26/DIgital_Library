<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\DigitalBook;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('copies')->withCount('copies as quantity');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('author', 'like', '%' . $request->search . '%')
                  ->orWhereHas('copies', fn($c) =>
                      $c->where('accession_no', 'like', '%' . $request->search . '%')
                  );
            });
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        // Status is now derived; filter via copies sub-query
        if ($request->status) {
            if ($request->status === 'available') {
                $query->whereHas('copies', fn($c) => $c->where('status', 'available'));
            } else {
                $query->whereDoesntHave('copies', fn($c) => $c->where('status', 'available'));
            }
        }

        $books = $query->latest()->paginate(15);
        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        return view('admin.books.create');
    }

    /**
     * Search existing books by title/author for the create form dropdown.
     * Returns JSON: [{id, title, author, cover_url}]
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $type  = $request->get('type', '');

        $books = Book::when($type, fn($q) => $q->where('type', $type))
            ->where(function ($q) use ($query) {
                $q->where('title',  'like', '%' . $query . '%')
                  ->orWhere('author', 'like', '%' . $query . '%');
            })
            ->orderBy('title')
            ->limit(20)
            ->get(['id', 'title', 'author', 'cover_image']);

        return response()->json($books->map(fn($b) => [
            'id'        => $b->id,
            'title'     => $b->title,
            'author'    => $b->author,
            'cover_url' => $b->cover_image ? asset('storage/' . $b->cover_image) : null,
        ]));
    }

    public function store(StoreBookRequest $request)
    {
        // Path A: admin selected an existing book from the dropdown
        if ($request->filled('existing_book_id')) {
            $book = Book::findOrFail($request->existing_book_id);

            BookCopy::create([
                'book_id'      => $book->id,
                'accession_no' => $request->accession_no,
                'status'       => 'available',
            ]);
            $book->syncQuantity();

            return redirect()->route('admin.books.index')
                ->with('success', "Copy added to \"{$book->title}\" (total copies: {$book->quantity}).");
        }

        // Path B: admin is adding a brand new book
        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
        }

        $book = Book::create([
            'title'       => $request->title,
            'author'      => $request->author,
            'category'    => $request->category,
            'type'        => $request->type,
            'price'       => $request->price,
            'cover_image' => $coverPath,
            'description' => $request->description,
            'quantity'    => 1,
        ]);

        BookCopy::create([
            'book_id'      => $book->id,
            'accession_no' => $request->accession_no,
            'status'       => 'available',
        ]);

        if ($request->type === 'digital') {
            $filePath = $request->file('file_path')->store('digital-books', 'public');
            DigitalBook::create([
                'book_id'   => $book->id,
                'file_path' => $filePath,
            ]);
        }

        return redirect()->route('admin.books.index')
            ->with('success', 'Book added successfully!');
    }

    public function show(Book $book)
    {
        $book->load('copies', 'digitalBook', 'physicalBorrows.user');
        return view('admin.books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $book->load('copies', 'digitalBook');
        return view('admin.books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:255',
            'category'    => 'nullable|string|max:255',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'file_path'   => 'nullable|file|mimes:pdf|max:51200',
        ]);

        // Block edit if another book record already has the same title + author + type
        $duplicate = Book::where('title', $request->title)
                         ->where('author', $request->author)
                         ->where('type', $book->type)
                         ->where('id', '!=', $book->id)
                         ->first();

        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors(['title' => "A {$book->type} book titled \"{$request->title}\" by \"{$request->author}\" already exists."]);
        }

        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
            $book->update(['cover_image' => $coverPath]);
        }

        $book->update([
            'title'       => $request->title,
            'author'      => $request->author,
            'category'    => $request->category,
            'price'       => $request->price,
            'description' => $request->description,
        ]);

        if ($book->type === 'digital' && $request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('digital-books', 'public');
            DigitalBook::updateOrCreate(
                ['book_id'   => $book->id],
                ['file_path' => $filePath]
            );
        }

        return redirect()->route('admin.books.index')
            ->with('success', 'Book updated successfully!');
    }

    /**
     * Update an individual copy's status (e.g. mark as damaged, lost).
     */
    public function updateCopy(Request $request, BookCopy $copy)
    {
        $request->validate([
            'status' => 'required|in:available,borrowed,reserved,damaged,lost',
        ]);

        $copy->update(['status' => $request->status]);
        $copy->book->syncQuantity();

        return back()->with('success', "Copy {$copy->accession_no} updated.");
    }

    public function destroy(Book $book)
    {
        // Copies are deleted via cascadeOnDelete in the migration
        $book->delete();
        return back()->with('success', 'Book deleted successfully!');
    }

    public function destroyCopy(BookCopy $copy)
    {
        $book = $copy->book;
        $copy->delete();
        $book->syncQuantity();
        return back()->with('success', "Copy {$copy->accession_no} removed.");
    }

    public function template()
    {
        $headers  = ['accession_no', 'title', 'author', 'category', 'type', 'price', 'description'];
        $callback = function () use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            fputcsv($file, ['ACC-00001', 'The Great Gatsby', 'F. Scott Fitzgerald', 'Fiction', 'physical', '250.00', '']);
            fputcsv($file, ['ACC-00002', 'The Great Gatsby', 'F. Scott Fitzgerald', 'Fiction', 'physical', '250.00', '']);
            fputcsv($file, ['ACC-00003', 'Digital Book', 'Author', 'Science', 'digital', '0.00', '']);
            fclose($file);
        };
        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="books_template.csv"',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:zip,xlsx,xls,csv|max:51200',
        ]);

        $file     = $request->file('file');
        $coverMap = [];
        $pdfMap   = [];

        if ($file->getClientOriginalExtension() === 'zip') {
            $zipDir = storage_path('app/import_tmp/' . uniqid());
            mkdir($zipDir, 0755, true);

            $zip = new \ZipArchive();
            $zip->open($file->getRealPath());
            $zip->extractTo($zipDir);
            $zip->close();

            $coversDir = $zipDir . DIRECTORY_SEPARATOR . 'covers';
            if (is_dir($coversDir)) {
                foreach (scandir($coversDir) as $filename) {
                    if ($filename === '.' || $filename === '..') continue;
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) continue;
                    $key            = strtolower(pathinfo($filename, PATHINFO_FILENAME));
                    $coverMap[$key] = \Storage::disk('public')->putFile(
                        'books/covers',
                        new \Illuminate\Http\File($coversDir . DIRECTORY_SEPARATOR . $filename)
                    );
                }
            }

            $pdfsDir = $zipDir . DIRECTORY_SEPARATOR . 'pdfs';
            if (is_dir($pdfsDir)) {
                foreach (scandir($pdfsDir) as $filename) {
                    if ($filename === '.' || $filename === '..') continue;
                    if (strtolower(pathinfo($filename, PATHINFO_EXTENSION)) !== 'pdf') continue;
                    $key          = strtolower(pathinfo($filename, PATHINFO_FILENAME));
                    $pdfMap[$key] = \Storage::disk('public')->putFile(
                        'digital-books',
                        new \Illuminate\Http\File($pdfsDir . DIRECTORY_SEPARATOR . $filename)
                    );
                }
            }

            $csvPath = null;
            foreach (scandir($zipDir) as $filename) {
                if (in_array(strtolower(pathinfo($filename, PATHINFO_EXTENSION)), ['csv', 'xlsx', 'xls'])) {
                    $csvPath = $zipDir . DIRECTORY_SEPARATOR . $filename;
                    break;
                }
            }

            $safeCsvPath = storage_path('app/import_tmp/' . uniqid() . '_import.' . pathinfo($csvPath, PATHINFO_EXTENSION));
            copy($csvPath, $safeCsvPath);
            \Illuminate\Support\Facades\File::deleteDirectory($zipDir);

            Excel::import(new \App\Imports\BooksImport($coverMap, $pdfMap), $safeCsvPath);
            @unlink($safeCsvPath);
        } else {
            $storedPath = $file->store('import_tmp', 'local');
            Excel::import(new \App\Imports\BooksImport(), storage_path('app/' . $storedPath));
            \Storage::disk('local')->delete($storedPath);
        }

        return back()->with('success', 'Books imported successfully.');
    }
}