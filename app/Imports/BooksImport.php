<?php

namespace App\Imports;

use App\Models\Book;
use App\Models\DigitalBook;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;

class BooksImport implements ToModel, WithHeadingRow, WithUpserts, WithEvents
{
    // Collect digital book mappings to process after all rows are upserted
    protected array $digitalQueue = [];

    public function __construct(
        protected array $coverMap = [],
        protected array $pdfMap   = []
    ) {}

    public function model(array $row)
    {
        $key        = strtolower(trim($row['accession_no'] ?? ''));
        $coverImage = $this->coverMap[$key] ?? null;
        $pdfFile    = $this->pdfMap[$key]   ?? null;

        $existing = Book::where('accession_no', $row['accession_no'])->first();

        $type = strtolower(trim($row['type'] ?? 'physical'));

        // Queue digital books for DigitalBook record creation after upsert
        if ($type === 'digital' && $pdfFile) {
            $this->digitalQueue[trim($row['accession_no'])] = $pdfFile;
        }

        return new Book([
            'accession_no' => trim($row['accession_no']),
            'title'        => trim($row['title']),
            'author'       => trim($row['author']),
            'category'     => $row['category']   ?? null,
            'type'         => $type,
            'status'       => $row['status']      ?? 'available',
            'price'        => $row['price']       ?? 0,
            'description'  => $row['description'] ?? null,
            'cover_image'  => $coverImage ?? ($existing->cover_image ?? null),
        ]);
    }

    public function uniqueBy()
    {
        return 'accession_no';
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function () {
                foreach ($this->digitalQueue as $accessionNo => $filePath) {
                    $book = Book::where('accession_no', $accessionNo)->first();
                    if (!$book) continue;

                    DigitalBook::updateOrCreate(
                        ['book_id'   => $book->id],
                        ['file_path' => $filePath]
                    );
                }
            },
        ];
    }
}