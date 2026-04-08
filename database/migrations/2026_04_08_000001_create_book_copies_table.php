<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_copies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->string('accession_no')->unique();
            $table->enum('status', ['available', 'borrowed', 'reserved', 'damaged', 'lost'])
                  ->default('available');
            $table->timestamps();
        });

        // Add quantity as a virtual/cache column on books.
        // We'll keep it synced via model events rather than a DB computed column
        // so it works across MySQL and SQLite equally.
        Schema::table('books', function (Blueprint $table) {
            $table->unsignedInteger('quantity')->default(0)->after('type');
        });

        // Migrate existing accession_no values into book_copies.
        // Each existing book row becomes its own first copy.
        DB::table('books')->orderBy('id')->each(function ($book) {
            DB::table('book_copies')->insert([
                'book_id'      => $book->id,
                'accession_no' => $book->accession_no,
                'status'       => $book->status,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
            DB::table('books')->where('id', $book->id)->update(['quantity' => 1]);
        });

        // Remove accession_no and status from books — they now live in book_copies.
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['accession_no', 'status']);
        });
    }

    public function down(): void
    {
        // Restore accession_no and status on books from their first copy
        Schema::table('books', function (Blueprint $table) {
            $table->string('accession_no')->nullable()->after('id');
            $table->enum('status', ['available', 'borrowed', 'reserved', 'damaged', 'lost'])
                  ->default('available')->after('quantity');
        });

        DB::table('book_copies')->orderBy('id')->each(function ($copy) {
            DB::table('books')->where('id', $copy->book_id)->update([
                'accession_no' => $copy->accession_no,
                'status'       => $copy->status,
            ]);
        });

        Schema::dropIfExists('book_copies');

        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
};