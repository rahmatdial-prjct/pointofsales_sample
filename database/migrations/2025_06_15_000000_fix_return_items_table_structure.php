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
        // Check if return_items table exists and fix its structure
        if (Schema::hasTable('return_items')) {
            Schema::table('return_items', function (Blueprint $table) {
                // Check if the old column exists and rename it
                if (Schema::hasColumn('return_items', 'return_id') && !Schema::hasColumn('return_items', 'return_transaction_id')) {
                    $table->renameColumn('return_id', 'return_transaction_id');
                }
                
                // Add missing columns if they don't exist
                if (!Schema::hasColumn('return_items', 'reason')) {
                    $table->text('reason')->nullable()->after('subtotal');
                }
                
                if (!Schema::hasColumn('return_items', 'condition')) {
                    $table->string('condition')->nullable()->after('reason');
                }
            });
        } else {
            // Create the table if it doesn't exist
            Schema::create('return_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('return_transaction_id')->constrained('returns')->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('restrict');
                $table->integer('quantity');
                $table->decimal('price', 12, 2);
                $table->decimal('subtotal', 12, 2);
                $table->text('reason')->nullable();
                $table->string('condition')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('return_items')) {
            Schema::table('return_items', function (Blueprint $table) {
                // Rename back to original column name
                if (Schema::hasColumn('return_items', 'return_transaction_id')) {
                    $table->renameColumn('return_transaction_id', 'return_id');
                }
                
                // Remove added columns
                if (Schema::hasColumn('return_items', 'reason')) {
                    $table->dropColumn('reason');
                }
                
                if (Schema::hasColumn('return_items', 'condition')) {
                    $table->dropColumn('condition');
                }
            });
        }
    }
};
