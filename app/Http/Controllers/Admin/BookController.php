<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Models\Book;
use App\Models\DigitalBook;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BooksImport;

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
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'file_path'   => 'nullable|file|mimes:pdf|max:51200',
        ]);

        // Update cover image if provided
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
            $book->update(['cover_image' => $coverPath]);
        }

        // Update book fields
        $book->update([
            'title'       => $request->title,
            'author'      => $request->author,
            'category'    => $request->category,
            'price'       => $request->price,
            'status'      => $request->status,
            'description' => $request->description,
        ]);

        // Handle PDF upload for digital books
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

    public function destroy(Book $book)
    {
        $book->delete();
        return back()->with('success', 'Book deleted successfully!');
    }
   public function template()
    {
        $headers = ['accession_no','title','author','category','type','status','price','description'];
        $callback = function() use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            fputcsv($file, ['ACC-00001','Physical Book','Author','Fiction','physical','available','250.00','']);
            fputcsv($file, ['ACC-00002','Digital Book','Author','Science','digital','available','0.00','']);
            fclose($file);
        };
        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="books_template.csv"',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:zip,xlsx,xls,csv|max:51200',
        ]);

        $file       = $request->file('file');
        $coverMap   = [];
        $pdfMap     = [];
        $importFile = null; // will be an UploadedFile or path

        if ($file->getClientOriginalExtension() === 'zip') {

            $zipDir = storage_path('app/import_tmp/' . uniqid());
            mkdir($zipDir, 0755, true);

            $zip = new \ZipArchive();
            $zip->open($file->getRealPath());
            $zip->extractTo($zipDir);
            $zip->close();

            

            // Index covers — Windows-safe, no glob
            $coversDir = $zipDir . DIRECTORY_SEPARATOR . 'covers';
            if (is_dir($coversDir)) {
                foreach (scandir($coversDir) as $filename) {
                    if ($filename === '.' || $filename === '..') continue;
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) continue;
                    $key    = strtolower(pathinfo($filename, PATHINFO_FILENAME));
                    $stored = \Storage::disk('public')->putFile(
                        'books/covers',
                        new \Illuminate\Http\File($coversDir . DIRECTORY_SEPARATOR . $filename)
                    );
                    $coverMap[$key] = $stored;
                }
            }

            // Index PDFs — Windows-safe, no glob
            $pdfsDir = $zipDir . DIRECTORY_SEPARATOR . 'pdfs';
            if (is_dir($pdfsDir)) {
                foreach (scandir($pdfsDir) as $filename) {
                    if ($filename === '.' || $filename === '..') continue;
                    if (strtolower(pathinfo($filename, PATHINFO_EXTENSION)) !== 'pdf') continue;
                    $key    = strtolower(pathinfo($filename, PATHINFO_FILENAME));
                    $stored = \Storage::disk('public')->putFile(
                        'digital-books',
                        new \Illuminate\Http\File($pdfsDir . DIRECTORY_SEPARATOR . $filename)
                    );
                    $pdfMap[$key] = $stored;
                }
            }

            // Find CSV — Windows-safe
            $csvPath = null;
            foreach (scandir($zipDir) as $filename) {
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                if (in_array($ext, ['csv', 'xlsx', 'xls'])) {
                    $csvPath = $zipDir . DIRECTORY_SEPARATOR . $filename;
                    break;
                }
            }

            // Copy CSV to a safe temp location Laravel Excel can read
            $safeCsvPath = storage_path('app/import_tmp/' . uniqid() . '_import.' . pathinfo($csvPath, PATHINFO_EXTENSION));
            copy($csvPath, $safeCsvPath);

            // Cleanup extracted zip dir
            \Illuminate\Support\Facades\File::deleteDirectory($zipDir);

            Excel::import(new \App\Imports\BooksImport($coverMap, $pdfMap), $safeCsvPath);

            // Cleanup safe copy
            @unlink($safeCsvPath);

        } else {
            // Plain CSV/Excel — store it properly first so Laravel Excel can read it
            $storedPath = $file->store('import_tmp', 'local');
            Excel::import(new \App\Imports\BooksImport(), storage_path('app/' . $storedPath));
            \Storage::disk('local')->delete($storedPath);
        }

        return back()->with('success', 'Books imported successfully.');
    }
}