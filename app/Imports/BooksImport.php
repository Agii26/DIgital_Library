<?php

namespace App\Imports;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\DigitalBook;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;
use Illuminate\Support\Collection;

class BooksImport implements ToCollection, WithHeadingRow, WithEvents
{
    protected array $digitalQueue = [];

    public function __construct(
        protected array $coverMap = [],
        protected array $pdfMap   = []
    ) {}

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $accessionNo = trim($row['accession_no'] ?? '');
            $title       = trim($row['title']        ?? '');
            $author      = trim($row['author']       ?? '');
            $type        = strtolower(trim($row['type'] ?? 'physical'));

            if (!$accessionNo || !$title || !$author) continue;

            $coverKey   = strtolower($accessionNo);
            $coverImage = $this->coverMap[$coverKey] ?? null;
            $pdfFile    = $this->pdfMap[$coverKey]   ?? null;

            // Look for an existing master record (same title + author + type)
            $book = Book::whereRaw('LOWER(title) = ?', [strtolower($title)])
                        ->whereRaw('LOWER(author) = ?', [strtolower($author)])
                        ->where('type', $type)
                        ->first();

            if (!$book) {
                // Create new master record
                $book = Book::create([
                    'title'       => $title,
                    'author'      => $author,
                    'category'    => $row['category']    ?? null,
                    'type'        => $type,
                    'price'       => $row['price']        ?? 0,
                    'description' => $row['description']  ?? null,
                    'cover_image' => $coverImage,
                    'quantity'    => 0,
                ]);
            } else {
                // Update cover only if a new one was provided in this import
                if ($coverImage) {
                    $book->update(['cover_image' => $coverImage]);
                }
            }

            // Skip if this accession_no is already registered as a copy
            $alreadyExists = BookCopy::where('accession_no', $accessionNo)->exists();
            if (!$alreadyExists) {
                BookCopy::create([
                    'book_id'      => $book->id,
                    'accession_no' => $accessionNo,
                    'status'       => $row['status'] ?? 'available',
                ]);

                $book->syncQuantity();
            }

            // Queue digital books for DigitalBook record creation after all rows
            if ($type === 'digital' && $pdfFile && !isset($this->digitalQueue[$book->id])) {
                $this->digitalQueue[$book->id] = $pdfFile;
            }
        }
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function () {
                foreach ($this->digitalQueue as $bookId => $filePath) {
                    DigitalBook::updateOrCreate(
                        ['book_id'   => $bookId],
                        ['file_path' => $filePath]
                    );
                }
            },
        ];
    }
}