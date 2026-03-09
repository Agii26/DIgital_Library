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
        Schema::table('users', function (Blueprint $table) {
            $table->string('rfid_tag')->unique()->nullable()->after('email');
            $table->enum('role', ['admin', 'faculty', 'student'])->default('student')->after('rfid_tag');
            $table->string('student_id')->unique()->nullable()->after('role');
            $table->string('department')->nullable()->after('student_id');
            $table->boolean('is_active')->default(true)->after('department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['rfid_tag', 'role', 'student_id', 'department', 'is_active']);
        });
    }
};
