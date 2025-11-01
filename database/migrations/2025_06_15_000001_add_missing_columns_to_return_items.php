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
        Schema::table('return_items', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('return_items', 'reason')) {
                $table->text('reason')->nullable()->after('subtotal');
            }
            
            if (!Schema::hasColumn('return_items', 'condition')) {
                $table->string('condition')->nullable()->after('reason');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('return_items', function (Blueprint $table) {
            if (Schema::hasColumn('return_items', 'reason')) {
                $table->dropColumn('reason');
            }
            
            if (Schema::hasColumn('return_items', 'condition')) {
                $table->dropColumn('condition');
            }
        });
    }
};
