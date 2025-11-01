<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update existing data to use Indonesian values
        DB::statement("UPDATE returns SET status = 'menunggu' WHERE status = 'pending'");
        DB::statement("UPDATE returns SET status = 'disetujui' WHERE status = 'approved'");
        DB::statement("UPDATE returns SET status = 'ditolak' WHERE status = 'rejected'");

        // Then modify the enum to include Indonesian values
        DB::statement("ALTER TABLE returns MODIFY COLUMN status ENUM('menunggu', 'disetujui', 'ditolak') NOT NULL DEFAULT 'menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert data back to English values
        DB::statement("UPDATE returns SET status = 'pending' WHERE status = 'menunggu'");
        DB::statement("UPDATE returns SET status = 'approved' WHERE status = 'disetujui'");
        DB::statement("UPDATE returns SET status = 'rejected' WHERE status = 'ditolak'");

        // Revert enum back to English values
        DB::statement("ALTER TABLE returns MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending'");
    }
};
