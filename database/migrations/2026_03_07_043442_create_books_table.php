<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('accession_no')->unique();
            $table->string('title');
            $table->string('author');
            $table->string('category')->nullable();
            $table->enum('type', ['physical', 'digital']);
            $table->enum('status', ['available', 'borrowed', 'reserved', 'damaged', 'lost'])->default('available');
            $table->decimal('price', 8, 2)->default(0);
            $table->string('cover_image')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};


